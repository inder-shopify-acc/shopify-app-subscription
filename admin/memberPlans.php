<?php

use PHPShopify\ShopifySDK;

$dirPath = dirname(__DIR__);
require_once($dirPath . "/application/library/shopify.php");
require_once($dirPath . "/graphLoad/autoload.php");
include($dirPath . "/application/library/config.php");

$store = isset($_GET['shop']) ? trim(filter_var($_GET['shop'], FILTER_SANITIZE_URL)) : '';

if (!isset($_GET['code'])) {
   $pageType = 'after_installation';
   $code = '';
} else {
   $pageType = 'on_installation';
   $code = trim($_GET['code']);
}

function PostPutApi($url, $action, $access_token, $arrayfield)
{
   $curl = curl_init();
   curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $action,
      CURLOPT_POSTFIELDS => $arrayfield,
      CURLOPT_HTTPHEADER => array(
         "cache-control: no-cache",
         "content-type: application/json",
         "x-shopify-access-token:{$access_token}"
      ),
   ));

   $response = curl_exec($curl);
   $err = curl_error($curl);
   curl_close($curl);

   return $err ? null : json_decode($response, true);
}

function recurringBillingCharge($billingData)
{
   global $SHOPIFY_APIKEY, $SHOPIFY_SECRET, $SHOPIFY_DOMAIN_URL, $db, $SHOPIFY_API_VERSION;

   $store = $billingData['store'];
   $getCurrentUtcTime = gmdate("Y-m-d H:i:s");
   $store_id = '';

   if ($billingData['pageType'] == 'on_installation') {
      $shopifyClient = new ShopifyClient($store, "", $SHOPIFY_APIKEY, $SHOPIFY_SECRET);
      $access_token = $shopifyClient->getAccessToken($billingData['sd_code']);

      if ($access_token != '') {
         $uninstall_details_query = "SELECT store_id FROM `uninstalls` WHERE store = '$store'";
         $result = $db->query($uninstall_details_query);
         $uninstall_details = $result->fetch(PDO::FETCH_ASSOC);

         if ($uninstall_details) {
            $store_id = $uninstall_details['store_id'];
            $sql_query_insert = "INSERT INTO install (id, store, access_token, app_status, created_at) VALUES ('$store_id', '$store', '$access_token', '1', '$getCurrentUtcTime')";
            $db->exec($sql_query_insert);
         } else {
            $sql_query_insert = "INSERT INTO install (store, access_token, app_status, created_at) VALUES ('$store', '$access_token', '1', '$getCurrentUtcTime')";
            $db->exec($sql_query_insert);
            $store_id = $db->lastInsertId();
         }

         $config = [
            'ShopUrl' => $store,
            'AccessToken' => $access_token,
         ];
         $shopify_graphql_object = new ShopifySDK($config);

         // Get theme ID
         $graphQL = <<<GQL
         query {
            translatableResources(first:1, resourceType:ONLINE_STORE_THEME) {
               edges {
                     node {
                        resourceId
                     }
               }
            }
         }
         GQL;

         $activeThemeArray = $shopify_graphql_object->GraphQL->post($graphQL, null, null, null);
         $ThemeGraphqlId = $activeThemeArray["data"]["translatableResources"]["edges"][0]["node"]["resourceId"];
         $theme_id = substr($ThemeGraphqlId, strrpos($ThemeGraphqlId, "/") + 1);

         // Get appInstallation ID
         $app_installation_query = '{ appInstallation { id } }';
         $app_installation_data = $shopify_graphql_object->GraphQL->post($app_installation_query, null, null, null);
         $app_installation_id = $app_installation_data['data']['appInstallation']['id'];

         try {
            $themeSupportCheck = PostPutApi("https://$store/admin/api/$SHOPIFY_API_VERSION/themes/$theme_id/assets.json?asset[key]=templates/product.json", 'GET', $access_token, '');
            $supportsBlocks = $themeSupportCheck ? 'true' : 'false';
            $notSupportsBlocks = $themeSupportCheck ? 'false' : 'true';
         } catch (Exception $e) {
            $supportsBlocks = 'false';
            $notSupportsBlocks = 'true';
         }

         // Create App-Owned Metafields
         $createAppOwnedMetafield = <<<GQL
         mutation CreateAppOwnedMetafield(\$metafieldsSetInput: [MetafieldsSetInput!]!) {
            metafieldsSet(metafields: \$metafieldsSetInput) {
               metafields {
                     id
                     namespace
                     key
                     type
                     value
               }
               userErrors {
                     field
                     message
               }
            }
         }
         GQL;

         $webhookParameters = [
            "metafieldsSetInput" => [
               [
                  "namespace" => "theme_support",
                  "key" => "support_theme_block",
                  "type" => "boolean",
                  "value" => $supportsBlocks,
                  "ownerId" => $app_installation_id
               ],
               [
                  "namespace" => "theme_not_support",
                  "key" => "support_theme_block_not",
                  "type" => "boolean",
                  "value" => $notSupportsBlocks,
                  "ownerId" => $app_installation_id
               ]
            ]
         ];
         $shopify_graphql_object->GraphQL->post($createAppOwnedMetafield, null, null, $webhookParameters);

         // Add uninstall webhook
         try {
            $createWebhook = <<<GQL
            mutation webhookSubscriptionCreate(\$topic: WebhookSubscriptionTopic!, \$webhookSubscription: WebhookSubscriptionInput!) {
               webhookSubscriptionCreate(topic: \$topic, webhookSubscription: \$webhookSubscription) {
                  userErrors { field message }
                  webhookSubscription { id }
               }
            }
            GQL;

            $webhookParameters = [
               "topic" => 'APP_UNINSTALLED',
               "webhookSubscription" => [
                  "callbackUrl" => $SHOPIFY_DOMAIN_URL . "/application/webhooks/app_uninstall.php",
                  "format" => "JSON"
               ]
            ];
            $shopify_graphql_object->GraphQL->post($createWebhook, null, null, $webhookParameters);
         } catch (Exception $e) {
            echo $e->getMessage();
         }

         // Store details
         $storeData = PostPutApi("https://$store/admin/api/$SHOPIFY_API_VERSION/shop.json", 'GET', $access_token, '');
         $shop = $storeData['shop'] ?? [];

         $storeEmail = $shop['email'] ?? '';
         $currency = $shop['currency'] ?? '';
         $currencyCode = str_replace(['{{amount}}', '{{amount_with_comma_separator}}'], '', $shop['money_in_emails_format'] ?? '');
         $shop_timezone = $shop['iana_timezone'] ?? '';
         $shop_owner = $shop['shop_owner'] ?? '';
         $shop_name = $shop['name'] ?? '';
         $shop_plan = $shop['plan_name'] ?? '';
         $plusPlan = ($shop['plan_display_name'] ?? '') === 'Shopify Plus' ? '1' : '0';

         if ($store_id) {
            $store_details_query = "SELECT * FROM `store_details` WHERE store_id = '$store_id'";
            $result = $db->query($store_details_query);
            $store_details = $result->fetch(PDO::FETCH_ASSOC);

            if ($store_details) {
               $update = "UPDATE `store_details` SET store_id = '$store_id', store_email = '$storeEmail', shopify_plus = '$plusPlan', currency = '$currency', currencyCode = '$currencyCode', shop_timezone = '$shop_timezone', owner_name = '$shop_owner', shop_name = '$shop_name', shop_plan = '$shop_plan' WHERE store_id = '$store_id'";
               $db->exec($update);
            } else {
               $insert = "INSERT INTO store_details (store_id, shop, store_email, shopify_plus, currency, currencyCode, shop_timezone, owner_name, shop_name, shop_plan) VALUES ('$store_id', '$store', '$storeEmail', '$plusPlan', '$currency', '$currencyCode', '$shop_timezone', '$shop_owner', '$shop_name', '$shop_plan')";
               $db->exec($insert);
            }
         }
      }
   }

   $db->exec("UPDATE `storeInstallOffers` SET status = '0' WHERE store_id = '$store_id' AND status = '1'");
   $created_at = date('Y-m-d H:i:s');
   $trial_days = $billingData['sd_trialDays'];
   $planName = $billingData['sd_planName'];

   $insert_offer = "INSERT INTO storeInstallOffers (plan_id, store_id, planName, subscriptionPlans, subscriptionContracts, price, trial, subscription_emails, created_at)
        VALUES ('1', '$store_id', '$planName', '-1', '-1', '0', '$trial_days', '10000', '$created_at')";
   $db->exec($insert_offer);
   // if($store == 'pheonix-store-local.myshopify.com' || $store == 'test-store-phoenixt.myshopify.com'){
   $store_details_query = "SELECT access_token, id FROM install WHERE store = '$store'";
   $result = $db->query($store_details_query);

   $store_details = $result->fetch(PDO::FETCH_ASSOC);
   $config = array(

      'ShopUrl' => $store,

      'AccessToken' => $store_details['access_token'],

   );
   // print_r($config);
   $shopify_graphql_object = new ShopifySDK($config);
   $return_url = $SHOPIFY_DOMAIN_URL . '/admin/dashboard.php?shop=' . $store;


   $create_app_subscription = 'mutation appSubscriptionCreate($lineItems: [AppSubscriptionLineItemInput!]!, $name: String!, $returnUrl: URL!, $test: Boolean, $trialDays: Int) {
 
                  appSubscriptionCreate(lineItems: $lineItems, name: $name, returnUrl: $returnUrl, test: $test, trialDays: $trialDays) {
 
                  appSubscription {
 
                     id
 
                     name
 
                     test
 
                     trialDays
 
                     returnUrl
 
                     lineItems{
 
                        id
 
                        plan{
 
                           pricingDetails{
 
                              __typename
 
                           }
 
                        }
 
                     }
 
                  }
 
                  confirmationUrl
 
                  userErrors {
 
                     field
 
                     message
 
                  }
 
                  }
 
               }';

   // $create_app_subscription_payload = [

   //    "lineItems" => [

   //       [

   //          "plan" => [

   //             "appRecurringPricingDetails" => [

   //                "price" => [

   //                   "amount" => '9.99',

   //                   "currencyCode" => "USD"

   //                ],

   //                "interval" => "EVERY_30_DAYS"

   //             ]

   //          ],
   //          "plan" => [
   //             "appUsagePricingDetails" => [
   //                "terms" => "5% + $0.12 Subscription & membership charge",
   //                "cappedAmount" => [
   //                   "amount" => 100000.00,
   //                   "currencyCode" => "USD"
   //                ]
   //             ]
   //          ]

   //       ]

   //    ],

   //    "name" => $planName . " SUBSCRIPTION APP PRICING",

   //    "replacementBehavior" => "APPLY_IMMEDIATELY",

   //    "returnUrl" => $return_url,

   //    "test" => false,

   // ];

   $test = false;

   if ($store == 'pheonix-store-local-2.myshopify.com' || $store == 'test-store-phoenixt.myshopify.com' || $store == 'kawals-store.myshopify.com' || $store == 'mini-cart-development.myshopify.com' || $store == 'new-preorder-server-test.myshopify.com' || $store == 'abhishek-store12.myshopify.com') {
      $test = true;
   }


   $create_app_subscription_payload = [

      "lineItems" => [
         [
            "plan" => [
               "appRecurringPricingDetails" => [
                  "price" => [
                     "amount" => 9.99,
                     "currencyCode" => "USD"
                  ],
                  "interval" => "EVERY_30_DAYS"
               ]
            ]
         ],
         [
            "plan" => [
               "appUsagePricingDetails" => [
                  "cappedAmount" => [
                     "amount" => 100000.00,
                     "currencyCode" => "USD"
                  ],
                  "terms" => "5% + $0.12 Subscription & membership charge"
               ]
            ]
         ]
      ],

      "name" => $planName . " SUBSCRIPTION APP PRICING",

      "replacementBehavior" => "APPLY_IMMEDIATELY",

      "returnUrl" => $return_url,

      "test" => $test,

   ];




   // echo $create_app_subscription;

   // echo'<br>===================================================<br>';

   $createAppSubscription = $shopify_graphql_object->GraphQL->post($create_app_subscription, null, null, $create_app_subscription_payload);

   // if($store == 'pheonix-store-local-2.myshopify.com'){
   //    print_r($createAppSubscription);
   //    die;
   // }

   // echo '<pre>';

   // print_r($createAppSubscription);

   // die;

   $createAppSubscription_error = $createAppSubscription['data']['appSubscriptionCreate']['userErrors'];

   if (!count($createAppSubscription_error)) {

      // $appUsageLineItemId = $createAppSubscription['data']['appSubscriptionCreate']['appSubscription']['lineItems']['id'];

      $appUsagePlanId = $createAppSubscription['data']['appSubscriptionCreate']['appSubscription']['id'];

      $app_recurring_and_usage_plan_id = substr($appUsagePlanId, strrpos($appUsagePlanId, '/') + 1);

      //save appUsageRecurringId and app usagePlanItemId

      // $fields = array(

      //    "appSubscriptionPlanId" => $app_recurring_and_usage_plan_id,

      //    'status' => '0',

      //    'planName' => 'abc',

      //    'price' => '9.99',

      //    'plan_id' => '11',

      //    'store_id' => $store_id,

      //    'subscription_emails' => null,

      //    'trial' => null,

      // );

      // $whereCondition = array(

      //    'store_id' => $this->store_id

      // );


      // $this->insert_row('storeInstallOffers', $fields);
      // if ($row_count > 0) {
      // } else {

      // }
      // echo $updateInstallData_qry;
      // die;


      $install_action_url = $createAppSubscription['data']['appSubscriptionCreate']['confirmationUrl'];
      echo "<script>window.top.location.href='" . $install_action_url . "'</script>";
      exit;

      // return json_encode(array("status" => true, 'recurring_id' => $appUsagePlanId, 'confirmationUrl' => $createAppSubscription['data']['appSubscriptionCreate']['confirmationUrl'])); // return json

   } else {

      // return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

   }
}
// else{

//    $install_action_url = $SHOPIFY_DOMAIN_URL . '/admin/dashboard.php?themeConfiguration=' . $billingData['sd_configureTheme'] . '&shop=' . $store;
// }
//  return $install_action_url;
// }

// Run installation
$recurringBillingValues = [
   "sd_trialDays" => '3',
   "pageType" => $pageType,
   "sd_code" => $code,
   "sd_planName" => 'Limited special',
   "sd_configureTheme" => 'Yes',
   "store" => $store
];

$app_installation = recurringBillingCharge($recurringBillingValues);
 
   // $app_installation = str_replace(["\n", "\r"], '', trim($app_installation));
   // header("Location: " . $app_installation);
   // exit;