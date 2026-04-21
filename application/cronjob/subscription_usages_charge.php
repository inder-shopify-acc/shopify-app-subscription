<?php

// error_reporting(1);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// // error_reporting(E_ALL);
// error_reporting(E_ALL ^ E_NOTICE);

$dirPath = dirname(dirname(__DIR__));

use PHPShopify\ShopifySDK;

require($dirPath . "/PHPMailer/src/PHPMailer.php");
require($dirPath . "/PHPMailer/src/SMTP.php");
require($dirPath . "/PHPMailer/src/Exception.php");

include $dirPath . "/graphLoad/autoload.php";
include $dirPath . "/application/library/config.php";

// $get_selling_plan_query = $db->query("SELECT * FROM `subscriptionPlanGroupsDetails` WHERE `selling_plan_id` = '3312058564' AND `store_id` = '32'");
// $data = $get_selling_plan_query->fetch(PDO::FETCH_ASSOC);
// // print_r($data); die;

// $contract_cron_query = $db->query("SELECT * FROM contract_cron WHERE status = 'pending'");
// $contract_cron_data = $contract_cron_query->fetch(PDO::FETCH_ASSOC);

// test renewal billing
$currentDate = strtok(gmdate("Y-m-d H:i:s"), " ");

// Uncomment for testing:
// $currentDate = strtok(gmdate("2025-06-11 16:39:01"), " ");
// echo "current billing date: " . $currentDate . "<br>";

// === DO NOT TOUCH: YOUR ORIGINAL QUERY ===
// $row_data_query = $db->query("
//    SELECT *
//     FROM billingAttempts
//     WHERE billing_attempt_date < '2025-06-04' 
//     AND billing_attempt_date > '2025-05-21' 
//     AND store_id = '32' 
//     AND status = 'Success'
//     AND usage_status = 'pending'
//     GROUP BY contract_id
//     LIMIT 10;

// ");

// $row_count = $row_data_query->rowCount();

// echo "Total billing: " . $row_count . "<br>";

// $row_data = $row_data_query->fetchAll(PDO::FETCH_ASSOC);

$store = 'thediyart1.myshopify.com';

$access_token = 'YOUR_ACCESS_TOKEN'; 

$store_id = '32';

$config = [
    'ShopUrl' => $store,
    'AccessToken' => $access_token
];

$shopifies = new ShopifySDK($config);
        $getCurrency = '{

            subscriptionContract(id :"gid://shopify/SubscriptionContract/20027474116") {

                originOrder{

                    name

                    tags

                    customerLocale

                    createdAt

                    shippingLine{

                        code

                        originalPriceSet{

                            shopMoney{

                                amount

                                currencyCode

                            }

                        }

                    }
                        customAttributes{

                                key

                                value

                            }

                    shippingAddress{

                        firstName

                        lastName

                        address1

                        address2

                        phone

                        city

                        country

                        company

                        province

                        provinceCode

                        zip

                        countryCodeV2

                    }

                    billingAddress{

                        firstName

                        lastName

                        address1

                        address2

                        phone

                        city

                        country

                        company

                        province

                        provinceCode

                        zip

                        countryCodeV2

                    }

                }

                customer{

                    displayName

                    email

                    state

                    tags

                }

                customerPaymentMethod{

                    id

                    instrument{

                        __typename

                        ... on CustomerCreditCard {

                            brand

                            expiresSoon

                            expiryMonth

                            expiryYear

                            firstDigits

                            lastDigits

                            name

                        }

                        ... on CustomerShopPayAgreement {

                            expiresSoon

                            expiryMonth

                            expiryYear

                            lastDigits

                            name

                        }

                        ... on CustomerPaypalBillingAgreement{

                            paypalAccountEmail

                        }

                    }

                }

                nextBillingDate

                billingPolicy{

                    anchors{

                        cutoffDay

                        day

                        month

                        type

                    }

                }

                lines(first:50){

                    edges{

                        node{

                            customAttributes{

                                key

                                value

                            }

                            id

                            quantity

                            sellingPlanId

                            productId

                            variantId

                            variantTitle

                            title

                            sku

                            variantImage{

                                src

                            }

                            lineDiscountedPrice{

                                amount

                            }

                            discountAllocations{

                                amount{

                                  amount

                                }

                                discount{

                                  __typename

                                 ... on SubscriptionManualDiscount{

                                    title

                                }

                              }

                            }

                            pricingPolicy {

                                basePrice{

                                    amount

                                }

                                cycleDiscounts {

                                    adjustmentType

                                    afterCycle

                                    computedPrice{

                                        amount

                                    }

                                    adjustmentValue{

                                        ... on MoneyV2{

                                            amount

                                        }

                                        ... on SellingPlanPricingPolicyPercentageValue{

                                            percentage

                                        }

                                    }

                                }



                            }

                            currentPrice{

                                amount

                                currencyCode

                            }

                        }

                    }

                }

            }

        }';

        $getCustomerData = $shopifies->GraphQL->post($getCurrency);
        echo "<pre>";
        print_r($getCustomerData);
// foreach ($row_data as $rowVal) {

//     // print_r($rowVal);

//     $order_id = $rowVal['order_id'];
//     $contract_id = $rowVal['contract_id'];
//     $usage_status = $rowVal['usage_status'];

//     if (!empty($order_id)) {
//         $getCurrency = '{
//             order(id: "gid://shopify/Order/' . $order_id . '") {
//                 presentmentCurrencyCode
//             }
//         }';

//         $getCustomerData = $shopifies->GraphQL->post($getCurrency);
//     } else {
//         echo "Warning: order_id is empty for contract_id: " . $rowVal['contract_id'] . "<br>";
//     }

//     $getCustomerData = $shopifies->GraphQL->post($getCurrency);

//     // print_r($getCustomerData);
//     $currencyCode = $getCustomerData['data']['order']['presentmentCurrencyCode'] ?? 'USD';

//     $get_subbscription_plan_query = $db->query("SELECT * FROM `subscritionOrderContractProductDetails` WHERE `contract_id` = $contract_id AND `store_id` = '32'");
//     $data = $get_subbscription_plan_query->fetch(PDO::FETCH_ASSOC);

//     $recurring_computed_price = $data['recurring_computed_price'];
//     $subscription_price =  $data['subscription_price'];

//     // print_r($data);


//     // Usage Charge amount
//     $data_storeInstallOffers_query = $db->query("SELECT appSubscriptionPlanId, planName, store_id FROM storeInstallOffers WHERE store_id = $store_id AND status = '1' ORDER BY id DESC LIMIT 1");

//     $get_data_storeInstall_offers = $data_storeInstallOffers_query->fetch(PDO::FETCH_ASSOC);

//     $appSubPlanId = $get_data_storeInstall_offers['appSubscriptionPlanId'];
//     $plan_name = $get_data_storeInstall_offers['planName'];


//     // if ($recurring_computed_price != 0.00 || $recurring_computed_price != 0) {
//     echo "order id number: " . $order_id . "<br>";
//     echo "contract number: " . $contract_id . "<br>";
//     echo "Currency Code: " . $currencyCode . "<br>";
//     echo "recurring_computed_price: " . $recurring_computed_price . "<br>";
//     echo "subscription_price: " . $subscription_price . "<br>";
//     echo "appSubPlanId: " . $appSubPlanId . "<br>";
//     echo "usage_status: " . $usage_status . "<br>";
//     echo "plan_name: " . $plan_name . "<br><br>";
//     // } else {
//     //     echo 'dcnkchkas';
//     // }

//     // if ($appSubPlanId) {
//     //     // die();
//     //     appUsageRecordCreate($appSubPlanId, $recurring_computed_price, $shopifies, $store_id, $db, $plan_name, $currencyCode, $contract_id);
//     // }
// }
// die;
// // public function appUsageRecordCreate($data)
// function appUsageRecordCreate($app_sub_plan_id, $price, $shopifies, $store_id, $db, $plan_name, $order_currency, $contract_id)
// {

//     $Planprice  = $price;
//     $SubplanItemId = $app_sub_plan_id;
//     $storeId  = $store_id;

//     // Calculate 5% of the plan price
//     $percentage = 5;
//     $extra_charge = 0.12;
//     $amount = ($Planprice * $percentage / 100); // add extra charge 0.15

//     // Set a minimum charge of $1 if neede +++


//     $miniCharge = 0.10;
//     // price: { amount: ' . $amount . ', currencyCode: USD }
//     if ($amount >= $miniCharge) {

//         $amount = $amount + $extra_charge;

//         $craete_action_on_billing = '
//         mutation {
//             appUsageRecordCreate(
//                 subscriptionLineItemId: "gid://shopify/AppSubscriptionLineItem/' . $SubplanItemId . '?v=1&index=0",
//                 description: "5% of per order +  0.12 tax",
//                 price: { amount: ' . $amount . ', currencyCode:  ' . $order_currency . ' }
//             ) {
//                 appUsageRecord {
//                     id
//                     price {
//                         amount
//                         currencyCode
//                     }
//                 }
//                 userErrors {
//                     field
//                     message
//                 }
//             }
//         }';

//         // Extracting values from response
//         $response = $shopifies->GraphQL->post($craete_action_on_billing);
//         $errors = $response['data']['appUsageRecordCreate']['userErrors'] ?? [];

//         // echo '<pre>';
//         // print_r($response);
//         // echo '</pre>';

//         $usage_desc = '5% charge for new plan "' . $plan_name . '"';
//         // Optional: Escape double quotes if not using prepared statements
//         $usage_desc = str_replace('"', '\"', $usage_desc);

//         $amount = $amount = $response['data']['appUsageRecordCreate']['appUsageRecord']['price']['amount'] ?? 0;
//         $usage_amount = is_numeric($amount)
//             ? number_format($amount, 2, '.', '')  // ensures 2 decimal places like "24.20"
//             : '0.00';

//         if ($response && empty($errors)) {

//             $usageRecodeId = $response['data']['appUsageRecordCreate']['appUsageRecord']['id'];
//             $current_date = date('Y-m-d H:i:s');

//             $stmt = $db->prepare("INSERT INTO UsageCharge (store_id, price, charge_amount, charged_at, sub_plan_item_id, usage_record_id,order_currency ,contract_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
//             $savedata = $stmt->execute([$storeId, $price, $usage_amount, $current_date, $SubplanItemId, $usageRecodeId, $order_currency, $contract_id]);

//             $stmtBillingUpdate = $db->query("UPDATE  billingAttempts SET usage_status = 'completed' WHERE store_id = '32' AND contract_id= '$contract_id' ");

//             echo 'Usage charge taken successfully.';
//             return $savedata;
//         } else {
//             echo ('some error');
//         }
//     } else {
//         echo ('Charge less than 0.10');
//     }
// }
