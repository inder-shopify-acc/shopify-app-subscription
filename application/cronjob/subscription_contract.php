<?php
// error_reporting(1);
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);
// error_reporting(E_ALL ^ E_NOTICE);

$dirPath = dirname(dirname(__DIR__));



use PHPShopify\ShopifySDK;



require($dirPath . "/PHPMailer/src/PHPMailer.php");

require($dirPath . "/PHPMailer/src/SMTP.php");

require($dirPath . "/PHPMailer/src/Exception.php");

include $dirPath . "/graphLoad/autoload.php";

include $dirPath . "/application/library/config.php";

$contract_cron_query = $db->query("Select * FROM contract_cron WHERE status = 'pending'");

$contract_cron_data = $contract_cron_query->fetch(PDO::FETCH_ASSOC);

// print_r($contract_cron_data);die;

if ($contract_cron_data) {

    $store = $contract_cron_data['store'];

    if ($store == 'test-store-phoenixt.myshopify.com') {
        // die();
    }

    $order_id = $contract_cron_data['order_id'];

    $processDataParameters = array(

        'store' => $store,

        'order_id' => $order_id,

        'db' => $db,

        'dirPath' => $dirPath,

        'SHOPIFY_DOMAIN_URL' => $SHOPIFY_DOMAIN_URL,

        'SHOPIFY_API_VERSION' => $SHOPIFY_API_VERSION

    );

    $contract_html = ''; //mail content variable defined

    $merchant_subscription_title = '';

    $mail_count = 1;

    $store_id = '';

    $customerEmail = '';

    $order_token = '';

    $contract_id = '';

    $payment_instrument_type = '';

    $customerId = '';

    $email_template_data = '';

    $replace_with_value = '';

    $subscription_line_items = '';

    $orderTagsArray = array();

    $customerTagsArray = array();

    $billingAddressAry = array(

        'firstName' => '',

        'lastName' => '',

        'address1' => '',

        'address2' => '',

        'phone' => ''

    );

    $shopifies = array();

    processContracts($processDataParameters, $contract_html, $merchant_subscription_title, $mail_count, $store_id, $customerEmail, $order_token, $contract_id, $payment_instrument_type, $customerId, $customerTagsArray, $orderTagsArray, $billingAddressAry, $shopifies, $email_template_data, $replace_with_value, $subscription_line_items);
}


function processContracts($processDataParameters, $contract_html, $merchant_subscription_title, $mail_count, $store_id, $customerEmail, $order_token, $contract_id, $payment_instrument_type, $customerId, $customerTagsArray, $orderTagsArray, $billingAddressAry, $shopifies, $email_template_data, $replace_with_value, $subscription_line_items)
{
    // echo $mail_count;
    $all_shopify_currencies = [

        'AED' => 'د.إ',
        'AFN' => '؋',
        'ALL' => 'L',
        'AMD' => '֏',
        'ANG' => 'ƒ',
        'AOA' => 'Kz',
        'ARS' => '$',
        'AUD' => '$',
        'AWG' => 'ƒ',
        'AZN' => '₼',
        'BAM' => 'KM',
        'BBD' => '$',
        'BDT' => '৳',
        'BGN' => 'лв',
        'BHD' => '.د.ب',
        'BIF' => 'FBu',
        'BMD' => '$',
        'BND' => '$',
        'BOB' => 'Bs.',
        'BRL' => 'R$',
        'BSD' => '$',
        'BWP' => 'P',
        'BZD' => '$',

        'CAD' => '$',
        'CDF' => 'FC',
        'CHF' => 'CHF',
        'CLP' => '$',
        'CNY' => '¥',
        'COP' => '$',
        'CRC' => '₡',
        'CVE' => '$',
        'CZK' => 'Kč',
        'DJF' => 'Fdj',
        'DKK' => 'kr',
        'DOP' => '$',
        'DZD' => 'د.ج',
        'EGP' => 'E£',
        'ERN' => 'Nfk',
        'ETB' => 'Br',
        'EUR' => '€',
        'FJD' => '$',
        'FKP' => '£',
        'GBP' => '£',
        'GEL' => '₾',
        'GHS' => '₵',
        'GIP' => '£',
        'GMD' => 'D',
        'GNF' => 'FG',

        'GTQ' => 'Q',
        'GYD' => '$',
        'HKD' => '$',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => 'Ft',
        'IDR' => 'Rp',
        'ILS' => '₪',
        'INR' => '₹',
        'ISK' => 'kr',
        'JMD' => '$',
        'JOD' => 'د.ا',
        'JPY' => '¥',
        'KES' => 'KSh',
        'KGS' => 'лв',
        'KHR' => '៛',
        'KMF' => 'CF',
        'KRW' => '₩',
        'KWD' => 'د.ك',
        'KYD' => '$',
        'KZT' => '₸',
        'LAK' => '₭',
        'LBP' => 'L£',
        'USD' => '$',
        'BTN' => 'Nu.',
        'BYN' => 'Br',
        'CUC' => '$',
        'CUP' => '$'

    ];

    $after_cycle_update = '0';

    $store = $processDataParameters['store'];

    $order_id = $processDataParameters['order_id'];

    $db = $processDataParameters['db'];

    $dirPath = $processDataParameters['dirPath'];

    $SHOPIFY_DOMAIN_URL = $processDataParameters['SHOPIFY_DOMAIN_URL'];

    $SHOPIFY_API_VERSION = $processDataParameters['SHOPIFY_API_VERSION'];

    $total_subscription_price = 0;

    // get store access token and store id

    $store_install_query = $db->query("Select access_token, store_id, shop_timezone, shop_name, store_email FROM install LEFT JOIN store_details ON install.id = store_details.store_id WHERE install.store = '$store'");

    $store_install_data = $store_install_query->fetch(PDO::FETCH_ASSOC);
    // print_r($store);

    if ($store_install_data) {

        $access_token = $store_install_data['access_token'];

        $store_id = $store_install_data['store_id'];

        $shop_timezone = $store_install_data['shop_timezone'];
    } else {

        file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n store_install_data not available. " . $contract_id, FILE_APPEND | LOCK_EX);

        die("Something went wrong. Pease try again later.");
    }
    $email_temlate_query = $db->query("Select * FROM subscription_purchase_template WHERE store_id = '$store_id'");

    $email_template_data = $email_temlate_query->fetch(PDO::FETCH_ASSOC);

    $manage_subscription_url = 'https://' . $store . '/account';

    if (!empty($email_template_data)) {

        $template_type = $email_template_data['template_type'];

        $show_order_number = $email_template_data['show_order_number'];

        $show_shipping_address = $email_template_data['show_shipping_address'];

        $show_billing_address = $email_template_data['show_billing_address'];

        $show_payment_method = $email_template_data['show_payment_method'];

        $show_line_items = $email_template_data['show_line_items'];

        $show_currency = $email_template_data['show_currency'];

        $email_subject = $email_template_data['subject'];

        $from_email = $email_template_data['from_email'];

        $logo = $email_template_data['logo'];

        if ($email_template_data['logo'] != '') {

            $logo = '<img border="0" style="display:block;color:#000000;text-decoration:none;font-family:Helvetica,arial,sans-serif;font-size:16px;" width="' . $email_template_data['logo_width'] . '" alt="" class="sd_logo_view" src="' . $email_template_data['logo'] . '" height="' . $email_template_data['logo_height'] . '" class="CToWUd" data-bit="iit">';
        }

        $ccc_email = $email_template_data['ccc_email'];

        $bcc_email = $email_template_data['bcc_email'];

        $reply_to = $email_template_data['bcc_email'];

        $logo_height = $email_template_data['logo_height'];

        $logo_width = $email_template_data['logo_width'];

        $logo_alignment = $email_template_data['logo_alignment'];

        $thanks_img = $email_template_data['thanks_img'];

        if ($thanks_img != '') {

            $thanks_img = '<img border="0" style="display:block;color:#000000;text-decoration:none;font-family:Helvetica,arial,sans-serif;font-size:16px;" width="' . $email_template_data['thanks_img_width'] . '" alt="" class="sd_thanks_img_view" src="' . $email_template_data['thanks_img'] . '" height="' . $email_template_data['thanks_img_height'] . '" class="CToWUd" data-bit="iit">';
        }

        $thanks_img_width = $email_template_data['thanks_img_width'];

        $thanks_img_height = $email_template_data['thanks_img_height'];

        $thanks_img_alignment = $email_template_data['thanks_img_alignment'];

        $heading_text = $email_template_data['heading_text'];

        $heading_text_color = $email_template_data['heading_text_color'];

        $content_text = $email_template_data['content_text'];

        $text_color = $email_template_data['text_color'];

        $manage_subscription_txt = $email_template_data['manage_subscription_txt'];

        if ($email_template_data['manage_subscription_url'] != '') {

            $manage_subscription_url = $email_template_data['manage_subscription_url'];
        }

        $manage_button_text_color = $email_template_data['manage_button_text_color'];

        $manage_button_background = $email_template_data['manage_button_background'];

        $shipping_address_text = $email_template_data['shipping_address_text'];

        $shipping_address = $email_template_data['shipping_address'];

        $billing_address = $email_template_data['billing_address'];

        $billing_address_text = $email_template_data['billing_address_text'];

        $next_charge_date_text = $email_template_data['next_charge_date_text'];

        $payment_method_text = $email_template_data['payment_method_text'];

        $ending_in_text = $email_template_data['ending_in_text'];

        $qty_text = $email_template_data['qty_text'];

        $footer_text = $email_template_data['footer_text'];

        $custom_template = $email_template_data['custom_template'];

        $delivery_every_text = $email_template_data['delivery_every_text'];

        $order_number_text = $email_template_data['order_number_text'];
    } else {

        $template_type = 'default';

        $email_subject = 'Your recurring order purchase confirmation';

        $ccc_email = '';

        $bcc_email = '';

        $reply_to = '';

        $logo = '<img src="https://yulanda-unpanelled-superzealously.ngrok-free.dev/application/assets/images/logo.png" height="63"  width="166">';

        $thanks_img = '<img src="https://yulanda-unpanelled-superzealously.ngrok-free.dev/application/assets/images/thank_you.jpg" height="63"  width="166">';

        $logo_height = '63';

        $logo_width = '166';

        $logo_alignment = 'center';

        $thanks_img_width = '166';

        $thanks_img_height = '63';

        $thanks_img_alignment = 'center';

        $heading_text = 'Welcome';

        $heading_text_color = '#495661';

        $content_text = '<h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Hi {{customer_name}}</h2><h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Thank you for your purchase!</h2> <p style="line-height:150%;font-size:16px;margin:0">We are getting your order ready to be shipped. We will notify you when it has been sent.</p>';

        $text_color = '#000000';

        $manage_subscription_txt = 'Manage Subscription';

        $manage_button_text_color = '#ffffff';

        $manage_button_background = '#337ab7';

        $shipping_address_text = 'Shipping address';

        $shipping_address = '<p>{{shipping_full_name}}</p><p>{{shipping_address1}}</p><p>{{shipping_city}},{{shipping_province_code}} - {{shipping_zip}}</p>';

        $billing_address_text = 'Billing address';

        $billing_address = '<p>{{billing_full_name}}</p><p>{{billing_address1}}</p><p>{{billing_city}},{{billing_province_code}} - {{billing_zip}}</p>';

        $next_renewal_date_text = 'Next billing date';

        $payment_method_text = 'Payment method';

        $ending_in_text = 'Ending with';

        $footer_text = '<p style="line-height:150%;font-size:14px;margin:0">Thank You</p>';

        $currency = '';

        $show_currency = '1';

        $show_order_number = '1';

        $show_shipping_address = '1';

        $show_billing_address = '1';

        $show_payment_method = '1';

        $show_line_items = '1';

        $next_charge_date_text = 'Next billing date';

        $delivery_every_text = 'Delivery every';

        $custom_template = '';

        $order_number_text = 'Order No.';

        $qty_text = 'Quantity';
    }
    $contract_details_query = $db->query("Select * FROM contract_details WHERE store = '$store' AND order_id = '$order_id' AND status = 'pending'");

    $contract_details_data = $contract_details_query->fetch(PDO::FETCH_ASSOC);
    $plan_type = '';
    if ($contract_details_data) {

        $contract_id = $contract_details_data['contract_id'];

        $contract_payload_json = $contract_details_data['contract_payload'];

        $contract_payload = json_decode($contract_payload_json, true);

        /***** From Webhook File *****/



        // load graphql

        $config = array(

            'ShopUrl' => $store,

            'AccessToken' => $access_token

        );

        $shopifies = new ShopifySDK($config);

        $get_contract_lines = '{

            subscriptionContract(id :"gid://shopify/SubscriptionContract/' . $contract_id . '") {

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

        try {
            $contract_detail = $shopifies->GraphQL->post($get_contract_lines);            
            $proceed_contract = 'Yes';
            //  Bonne custom work  

            $customAttributes_custom = [];

            if (isset($contract_detail['data']['subscriptionContract']['lines']['edges'])) {

                foreach ($contract_detail['data']['subscriptionContract']['lines']['edges'] as $line) {

                    $node = $line['node'];
                    $purchage_plan_amount = $node['currentPrice']['amount'];
                    $purchage_plan_variantId = $node['variantId'];
                    $purchage_plan_variantId = basename($purchage_plan_variantId);
                    if (isset($node['customAttributes'])) {

                        foreach ($node['customAttributes'] as $attribute) {

                            $key = $attribute['key'];

                            $value = $attribute['value'];

                            $customAttributes_custom[$key] = $value;
                        }
                    }
                }
            }


            $sd_gender = $customAttributes_custom['_gender'] ?? '';
            $sd_productType = $customAttributes_custom['_productType'] ?? '';
            $sd_size = $customAttributes_custom['_itemSize'] ?? '';

            //  Bonne custom work end //

            $order_details_data = $contract_detail['data']['subscriptionContract']['originOrder'];

            $currentUtcDate = gmdate("Y-m-d H:i:s");

            // $currentUtcDate = $contract_detail['data']['subscriptionContract']['originOrder']['createdAt'];

            //customer data

            $customerId = $contract_payload['customer_id'];

            // membership work 
            if ($store == 'thediyart1.myshopify.com') {

                $plan_type = $customAttributes_custom['_plan_type'] ?? 'membership';
            } else {

                $plan_type = $customAttributes_custom['_plan_type'] ?? 'subscription';
            }
            if ($plan_type == 'membership' || $store == 'thediyart1.myshopify.com') {
                // $mail_count = 0;
                $customerTagsArray = $contract_detail['data']['subscriptionContract']['customer']['tags'];
                $graphql_selling_plan_id = $contract_detail['data']['subscriptionContract']['lines']['edges'][0]['node']['sellingPlanId'];
                $selling_plan_id = substr($graphql_selling_plan_id, strrpos($graphql_selling_plan_id, '/') + 1);
                $sql = "SELECT membership_plan_groups.unique_handle FROM membership_plan_groups JOIN membership_groups_details ON membership_groups_details.membership_group_id = membership_plan_groups.membership_group_id WHERE membership_plan_groups.store = :store AND membership_groups_details.membership_option_id = :selling_plan_id LIMIT 1";

                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':store' => $store,
                    ':selling_plan_id' => $selling_plan_id
                ]);

                $get_member_plan_handle = $stmt->fetch(PDO::FETCH_OBJ); // fetch as object (like Laravel's ->first())
                // print_r($get_member_plan_handle);
                // die;

                if (!empty($get_member_plan_handle)) {
                    $add_plan_tag = array($get_member_plan_handle->unique_handle);
                }

                // customer Tag Update
                try {
                    $getQuery = '{
                                    customer(id: "gid://shopify/Customer/' . $customerId . '") {
                                        id
                                        firstName
                                        tags
                                    }
                                }';
                    $getCustomerData = $shopifies->GraphQL->post($getQuery);
                    $getCustomerTags = $getCustomerData['data']['customer']['tags'] ?? [];
                    if (in_array('non-member', $getCustomerTags)) {
                        // Remove 'non-member' from the array
                        $getCustomerTags = array_filter($getCustomerTags, function($tag) {
                            return $tag !== 'non-member';
                        });
                    
                        $getCustomerTags = array_values($getCustomerTags);
                    }
                    $mergedTags = array_merge($getCustomerTags, $add_plan_tag);
                    $mergedTagsString = implode(',', $mergedTags);

                    $customerUpdateMutation = 'mutation customerUpdate($input: CustomerInput!) {
                                                                    customerUpdate(input: $input) {
                                                                        userErrors {
                                                                        field
                                                                        message
                                                                        }
                                                                        customer {
                                                                        id
                                                                        tags
                                                                        }
                                                                    }
                                                                }';
                    $customerUpdateVariables = [
                        "input" => [
                            "id" => "gid://shopify/Customer/$customerId",
                            "tags" => $mergedTagsString,
                        ],
                    ];
                    $customerUpdateExecution = $shopifies->GraphQL->post($customerUpdateMutation, null, null, $customerUpdateVariables);
                    $userErrors = $customerUpdateExecution['data']['customerUpdate']['userErrors'];
                    //die;
                } catch (Exception $e) {
                    echo "<b>Error in customerUpdate mutation= </b>";
                    echo $e->getMessage() . "<br>";
                    echo $e->getLine() . "<br>";
                    echo $e->getFile() . "<br>";
                }
            }

            $customerEmail = $contract_detail['data']['subscriptionContract']['customer']['email'];

            $customerName = $contract_detail['data']['subscriptionContract']['customer']['displayName'];

            $customerAccountState = $contract_detail['data']['subscriptionContract']['customer']['state'];

            $customerLocale = $order_details_data['customerLocale'];

            //send account activation mail to the customer if customer account is not activated

            if ($customerAccountState == 'DISABLED') {

                try {

                    PostPutApi('https://' . $store . '/admin/api/' . $SHOPIFY_API_VERSION . '/customers/' . $customerId . '/send_invite.json', 'POST', $access_token, '');
                } catch (Exception $e) {

                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n Customer account activationg failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            }

            $shippingAddressAry = $order_details_data['shippingAddress'];

            $shippingLineAry =  $order_details_data['shippingLine'];

            $order_number = $order_details_data['name'];

            $order_no = (int) filter_var($order_details_data['name'], FILTER_SANITIZE_NUMBER_INT);

            //get subscription and membership email template data

            if ($plan_type == 'membership' || $store == 'thediyart1.myshopify.com') {
                $customerTagsArray = $contract_detail['data']['subscriptionContract']['customer']['tags'];

                $orderTagsArray = $order_details_data['tags'];

                array_push($orderTagsArray, 'sd_subscription_order', 'sd_send_order_invoice');
                // $purchase_tag = $plan_type == 'membership' ? 'sd_membership_customer' : 'sd_subscription_customer'
                array_push($customerTagsArray, "sd_membership_customer", "sd_subscription_customer");
            }

            // add tag to customer
            $allSubscriptionCustomerTags = implode(",", $customerTagsArray);
            $addTagQuery = 'mutation tagsAdd($id: ID!, $tags: [String!]!) {
                tagsAdd(id: $id, tags: $tags) {
                    userErrors {
                        field
                        message
                    }
                }
            }';
            $tagsParameters = [
                "id" => "gid://shopify/Customer/$customerId",
                "tags" => $allSubscriptionCustomerTags,
            ];

            $billingPolicyValue = $contract_payload['billing_policy']['interval_count'];

            $payment_method_token  =  substr($contract_detail['data']['subscriptionContract']['customerPaymentMethod']['id'], strrpos($contract_detail['data']['subscriptionContract']['customerPaymentMethod']['id'], '/') + 1);

            $payment_instrument_type = $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['__typename'];

            if ($payment_instrument_type == 'CustomerCreditCard') {

                $customerPaymentMethodArray = array(

                    'last_digits' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['lastDigits'],

                    'brand' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['brand'],

                    'name' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['name'],

                    'month' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['expiryMonth'],

                    'year' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['expiryYear']

                );
            } else if ($payment_instrument_type == 'CustomerShopPayAgreement') {

                $customerPaymentMethodArray = array(

                    'last_digits' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['lastDigits'],

                    'brand' => '',

                    'name' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['name'],

                    'month' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['expiryMonth'],

                    'year' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['expiryYear']

                );
            } else if ($payment_instrument_type == 'CustomerPaypalBillingAgreement') {

                $customerPaymentMethodArray = array(

                    'last_digits' => '',

                    'brand' => '',

                    'name' => $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['paypalAccountEmail'],

                    'month' => '',

                    'year' => ''

                );
            }



            $deliveryPolicyValue = $contract_payload['delivery_policy']['interval_count'];

            $deliveryBillingType = $contract_payload['delivery_policy']['interval'];

            $nextBillingDate = $contract_detail['data']['subscriptionContract']['nextBillingDate'];

            $renewalDate = strtok($nextBillingDate, 'T');

            $dt = new DateTime($nextBillingDate);

            $tz = new DateTimeZone($shop_timezone); // or whatever zone you're after

            $dt->setTimezone($tz);

            $dateTime = $dt->format('Y-m-d H:i:s');

            $shop_renewalDate =  date("d M Y", strtotime($dateTime));

            $cut_off_days = 0;

            $min_cycle = 1;

            $anchor_day = 0;



            if ($contract_payload['billing_policy']['max_cycles']) {

                $max_cycle = $contract_payload['billing_policy']['max_cycles'];

                $max_cycle_text = '<div style="font-family: inherit; text-align: inherit">Expires after : ' . $max_cycle . ' Cycle</div>';
            } else {

                $max_cycle = 0;

                $max_cycle_text = '';
            }



            if ($max_cycle == 1) {

                $contract_status = 'P';
            } else {

                $contract_status = 'A';
            }

            if (!empty($contract_payload['billing_policy']['min_cycles'])) {

                $min_cycle = $contract_payload['billing_policy']['min_cycles'];
            }

            if (!empty($contract_detail['data']['subscriptionContract']['billingPolicy']['anchors'])) {

                if (!empty($contract_detail['data']['subscriptionContract']['billingPolicy']['anchors'][0]['cutoffDay'])) {

                    $cut_off_days = $contract_detail['data']['subscriptionContract']['billingPolicy']['anchors'][0]['cutoffDay'];
                }

                if (!empty($contract_detail['data']['subscriptionContract']['billingPolicy']['anchors'][0]['day'])) {

                    $anchor_day = $contract_detail['data']['subscriptionContract']['billingPolicy']['anchors'][0]['day'];
                }
            }



            try {

                $getContractDraft = 'mutation {

                subscriptionContractUpdate(

                    contractId: "gid://shopify/SubscriptionContract/' . $contract_id . '"

                ) {

                    draft {

                        id

                        deliveryPrice{

                            amount

                        }

                    }

                    userErrors {

                        field

                        message

                    }

                }

            }';

                $contractDraftArray = $shopifies->GraphQL->post($getContractDraft);

                $draftContract_execution_error = $contractDraftArray['data']['subscriptionContractUpdate']['userErrors'];

                if (!count($draftContract_execution_error)) {

                    $contractDraftid = $contractDraftArray['data']['subscriptionContractUpdate']['draft']['id'];

                    $ship_delivery_price = $contractDraftArray['data']['subscriptionContractUpdate']['draft']['deliveryPrice']['amount'];
                }
            } catch (Exception $e) {

                // return 'error';

            }



            $contract_variant_ids =  array();

            $sub_count = 1;



            try {

                if ($mail_count == 1) {

                    $subscription_line_items = '<table style="width:100%;border-spacing:0;border-collapse:collapse">

                <tbody>

                    <tr>

                        <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;">

                            <center>

                                <table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

                                    <tbody>

                                        <tr>

                                            <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

                                                <table style="width:100%;border-spacing:0;border-collapse:collapse">

                                                    <tbody>

                                                        <tr style="width:100%">

                                                            <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;">

                                                                <table style="border-spacing:0;border-collapse:collapse">

                                                                <tbody>';
                }


                $email_order_currency = '';

                foreach ($contract_detail['data']['subscriptionContract']['lines']['edges'] as $contractData) {
                    // array to get all selling plan ids added with the product in order

                    if ($show_currency == '1') {

                        $email_order_currency = $contractData['node']['currentPrice']['currencyCode'];
                    }

                    $order_currency = $contractData['node']['currentPrice']['currencyCode'];

                    $currency_code = $all_shopify_currencies[$contractData['node']['currentPrice']['currencyCode']];

                    $discount_type = 'P';

                    $discount_value = 0;

                    $recurring_discount_type = 'P';

                    $recurring_discount_value = 0;

                    $after_cycle = 0;

                    $recurring_computed_price = 0;

                    $coupon_name = 'empty';

                    $discounted_value = 0;

                    $selling_plan_id = substr($contractData['node']['sellingPlanId'], strrpos($contractData['node']['sellingPlanId'], '/') + 1);

                    $contract_line_id =  substr($contractData['node']['id'], strrpos($contractData['node']['id'], '/') + 1);

                    if ($contractData['node']['discountAllocations']) {

                        $coupon_name = $contractData['node']['discountAllocations'][0]['discount']['title'];

                        $discounted_value = $contractData['node']['discountAllocations'][0]['amount']['amount'];
                    }

                    if (!empty($contractData['node']['pricingPolicy'])) {

                        if ($contractData['node']['pricingPolicy']['cycleDiscounts'][0]['adjustmentType'] == 'PERCENTAGE') {

                            $discount_type = 'P';

                            $discount_value = $contractData['node']['pricingPolicy']['cycleDiscounts'][0]['adjustmentValue']['percentage'];
                        } else {

                            $discount_type = 'A';

                            $discount_value = $contractData['node']['pricingPolicy']['cycleDiscounts'][0]['adjustmentValue']['amount'];
                        }



                        //recurring discount data start here

                        if (count($contractData['node']['pricingPolicy']['cycleDiscounts']) > 1) {

                            $after_cycle = $contractData['node']['pricingPolicy']['cycleDiscounts'][1]['afterCycle'];

                            if ($contractData['node']['pricingPolicy']['cycleDiscounts'][1]['adjustmentType'] == 'PERCENTAGE') {

                                $recurring_discount_type = 'P';

                                $recurring_discount_value = $contractData['node']['pricingPolicy']['cycleDiscounts'][1]['adjustmentValue']['percentage'];
                            } else {

                                $recurring_discount_type = 'A';

                                $recurring_discount_value = $contractData['node']['pricingPolicy']['cycleDiscounts'][1]['adjustmentValue']['amount'];
                            }

                            $recurring_computed_price = $contractData['node']['pricingPolicy']['cycleDiscounts'][1]['computedPrice']['amount'];

                            if ($after_cycle == 1) {

                                //update the discount for next cycle if after cycle is 1

                                if (!count($draftContract_execution_error)) {

                                    try {

                                        $updateContractLineItemPrice = 'mutation {

                                        subscriptionDraftLineUpdate(

                                            draftId: "' . $contractDraftid . '"

                                            lineId: "' . $contractData['node']['id'] . '"

                                            input: { currentPrice: ' . $recurring_computed_price . ' }

                                        ) {

                                            lineUpdated {

                                                    id

                                                    currentPrice{

                                                    amount

                                                }

                                            }

                                            userErrors {

                                                field

                                                message

                                                code

                                            }

                                        }

                                    }';

                                        $updateContractLine_execution = $shopifies->GraphQL->post($updateContractLineItemPrice);

                                        $updateContractLine_execution_error = $updateContractLine_execution['data']['subscriptionDraftLineUpdate']['userErrors'];

                                        if (!count($updateContractLine_execution_error)) {

                                            $after_cycle_update = '1';
                                        }
                                    } catch (Exception $e) {

                                        // return 'error';

                                    }
                                }
                            }
                        }
                    }



                    $productName = $contractData['node']['title'];

                    $variantName = $contractData['node']['variantTitle'];

                    if ($variantName == '-' || $variantName == 'Default Title') {

                        $variantName = '';
                    }

                    $productId = substr($contractData['node']['productId'], strrpos($contractData['node']['productId'], '/') + 1);



                    $variantId = substr($contractData['node']['variantId'], strrpos($contractData['node']['variantId'], '/') + 1);

                    if (!empty($contractData['node']['variantImage'])) {

                        $variantImage = $contractData['node']['variantImage']['src'];
                    } else {

                        $variantImage = $SHOPIFY_DOMAIN_URL . '/application/assets/images/no-image.png';
                    }

                    $quantity = $contractData['node']['quantity'];

                    $subscriptionPrice = $contractData['node']['currentPrice']['amount'];

                    array_push($contract_variant_ids, $variantId);

                    $subscription_line_items .= '<tr style="border-bottom: 1px solid #f3f3f3;">

                            <td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

                                <img src="' . $variantImage . '" align="left" width="60" height="60" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" class="CToWUd" data-bit="iit">

                            </td>

                            <td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%">

                                <span style="font-size:16px;font-weight:600;line-height:1.4;color:' . $heading_text_color . '" class="sd_text_color_view"> ' . $productName . '-' . $variantName . ' x ' . $quantity . '</span><br>

                                <span style="font-size:14px;color:' . $text_color . '">' . $delivery_every_text . ' : ' . $deliveryPolicyValue . ' ' . $deliveryBillingType . '</span><br>

                                <span style="font-size:14px;color:' . $text_color . '">' . $next_charge_date_text . ' : ' . $shop_renewalDate . ' </span><br>

                            </span></td>

                            <td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap">

                                <p class="sd_text_color_view" style="color:' . $text_color . ';line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">

                                ' . $currency_code . '' . $subscriptionPrice * $quantity . ' ' . $email_order_currency . '

                                </p>

                            </td>

                        </tr>&nbsp;';

                    try {
                        $sql = "
                        SELECT 
                            perk_free_gift_product_id, 
                            perk_free_gift_variant_id, 
                            free_gift_uponsignupSelected_Value, 
                            free_gift_uponsignupSelectedDays,
                            Free_gift_uponsignup_productName,
                            gift_uponsignup_variantName
                        FROM membership_perks
                        WHERE free_gift_uponsignupSelected_Value != 0
                            AND variant_id = :variant_id
                            AND store = :store
                            AND free_gift_uponsignupSelectedDays != 'Immediately_after_signup'
                            AND free_gift_uponsignupSelectedDays IS NOT NULL
                        LIMIT 1
                    ";

                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                            ':variant_id' => $variantId,
                            ':store' => $store,
                        ]);

                        $freeGiftData = $stmt->fetch(PDO::FETCH_OBJ);

                        $currentDate = date('Y-m-d');
                        $free_gift_date = $currentDate;
                        if (count((array)$freeGiftData) > 0) {
                            $Free_gift_uponsignup_productName = $freeGiftData->Free_gift_uponsignup_productName ?? '';
                            $gift_uponsignup_variantName = $freeGiftData->gift_uponsignup_variantName ?? '';
                            $perk_free_gift_product_id = $freeGiftData->perk_free_gift_product_id ?? '';
                            $perk_free_gift_variant_id = $freeGiftData->perk_free_gift_variant_id ?? '';
                            $free_gift_uponsignupSelectedDays = $freeGiftData->free_gift_uponsignupSelectedDays ?? '';
                            $free_gift_status = 'pending';
                            $free_gift_date = date('Y-m-d', strtotime('+' . $free_gift_uponsignupSelectedDays, strtotime($currentDate))) ?? '';
                        } else {
                            $Free_gift_uponsignup_productName =  Null;
                            $gift_uponsignup_variantName =  Null;
                            $perk_free_gift_product_id =  Null;
                            $perk_free_gift_variant_id = Null;
                            $free_gift_uponsignupSelectedDays = Null;
                            $free_gift_status = 'completed';
                        }

                        $product_name = str_replace("'", "", $productName);

                        $variant_name = htmlspecialchars($variantName ?? '', ENT_QUOTES);

                        $variant_image = htmlspecialchars($variantImage ?? '', ENT_QUOTES);

                        $total_subscription_price += $subscriptionPrice * $quantity;

                        $sql_ordercontract_query = "INSERT INTO subscritionOrderContractProductDetails (`store_id`, `contract_id`, `plan_type`, `product_id`, `variant_id`, `product_name`, `variant_name`, `variant_image`, `recurring_computed_price`, `quantity`, `subscription_price`, `contract_line_item_id`,`coupon_applied`,`coupon_value`,`created_at`,`free_gift_date`, `free_gift_status`, `perk_free_gift_product_id`, `perk_free_gift_variant_id`, `Free_gift_uponsignup_productName`, `gift_uponsignup_variantName`) VALUES ('$store_id', '$contract_id','$plan_type', '$productId', '$variantId', '$product_name', '$variant_name', '$variant_image', '$recurring_computed_price', '$quantity', '$subscriptionPrice', '$contract_line_id','$coupon_name','$discounted_value','$currentUtcDate', '$free_gift_date', '$free_gift_status', '$perk_free_gift_product_id', '$perk_free_gift_variant_id', '$Free_gift_uponsignup_productName', '$gift_uponsignup_variantName')";

                        $db->exec($sql_ordercontract_query);
                    } catch (Exception $e) {
                        $proceed_contract = 'No';

                        file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n multiple_insert_update_row subscritionOrderContractProductDetails 2 failed. loop " . $sub_count . "____" . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                    }

                    $sub_count++;
                }

                // $contract_html .= $subscription_line_items;

            } catch (Exception $e) {

                file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n Error in forloops . " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }


            //commit contract draft

            if (!count($draftContract_execution_error)) {

                try {

                    $updateContractStatus = 'mutation {

                    subscriptionDraftCommit(draftId: "' . $contractDraftid . '") {

                        contract {

                            id

                            status

                        }

                        userErrors {

                            field

                            message

                        }

                    }

                }';

                    $commitContractStatus_execution = $shopifies->GraphQL->post($updateContractStatus);

                    $commitContractStatus_execution_error = $commitContractStatus_execution['data']['subscriptionDraftCommit']['userErrors'];
                } catch (Exception $e) {

                    // return 'error';

                }
            }


            try {

                $customerPaymentMethodArray = json_encode($customerPaymentMethodArray);

                $check_entry = $db->prepare("SELECT * FROM customerContractPaymentmethod WHERE LOWER(store_id) = LOWER('$store_id') AND LOWER(shopify_customer_id) = LOWER('$customerId') AND LOWER(payment_method_token) = LOWER('$payment_method_token')");

                $check_entry->execute();

                $entry_count = $check_entry->rowCount();

                if ($entry_count) {

                    $update_payment_method = $db->prepare("UPDATE customerContractPaymentmethod SET payment_instrument_type = '$payment_instrument_type', payment_instrument_value = '$customerPaymentMethodArray' WHERE store_id = '$store_id' AND shopify_customer_id = '$customerId' AND payment_method_token = '$payment_method_token'");

                    $update_payment_method->execute();
                } else {

                    $insert_payment_method = $db->prepare("INSERT INTO customerContractPaymentmethod (store_id, shopify_customer_id, payment_method_token, payment_instrument_type, payment_instrument_value, created_at) VALUES ('$store_id', '$customerId', '$payment_method_token', '$payment_instrument_type', '$customerPaymentMethodArray', '$currentUtcDate')");

                    $insert_payment_method->execute();
                }
            } catch (Exception $e) {
          
                $proceed_contract = 'No';

                file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n insert update into customerContractPaymentmethod failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }

            $contract_productid_string = implode(',', $contract_variant_ids);


            try {

                $sd_size = strtoupper($sd_size);

                $sd_productType = strtoupper($sd_productType);

                $sd_gender = strtoupper($sd_gender);



                if ($store == 'c401e3-2.myshopify.com' || $store == 'mini-cart-development.myshopify.com') {

                    $renewalDate = modifyDate($renewalDate);
                }

                // $get_selling_plan_query = $db->query("SELECT * FROM `subscriptionPlanGroupsDetails` WHERE `selling_plan_id` = '$selling_plan_id' AND `store_id` = '$store_id'");

                if ($plan_type == 'subscription') {
                    $get_selling_plan_query = $db->query("SELECT * FROM `subscriptionPlanGroupsDetails` WHERE `selling_plan_id` = '$selling_plan_id' AND `store_id` = '$store_id'");
                } else {

                    $get_selling_plan_query = $db->query("SELECT * FROM `membership_groups_details` WHERE `membership_option_id` = '$selling_plan_id' AND `store` = '$store'");
                }
            
                $selling_plan_data = $get_selling_plan_query->fetch(PDO::FETCH_ASSOC);
           
                if ($selling_plan_data['offer_trial_status'] == 1) {

                    // if ($selling_plan_data['renew_on_original_date'] == 0) {
                    if ($selling_plan_data['renew_on_original_date'] === 'false' || $selling_plan_data['renew_on_original_date'] === '0') {
                       $date = date('Y-m-d');
                        $final_trial_period_value =  $selling_plan_data['trial_period_value'] +  $selling_plan_data['trial_period_value'];
                        $interval = $final_trial_period_value . ' ' . $selling_plan_data['trial_period_type'];
                        $renewalDate = date('Y-m-d', strtotime($date . ' + ' . $interval)); // Modify the date dynamically
                    } else {
                        $date = date('Y-m-d');
                        $interval = $selling_plan_data['trial_period_value'] . ' ' . $selling_plan_data['trial_period_type'];
                        $renewalDate = date('Y-m-d', strtotime($date . ' + ' . $interval)); // Modify the date                      
                    }
                }

               
                // Usage Charge amount
                $data_storeInstallOffers_query = $db->query("SELECT appSubscriptionPlanId, planName, store_id FROM storeInstallOffers WHERE store_id = $store_id AND status = '1' ORDER BY id DESC LIMIT 1");

                $get_data_storeInstall_offers = $data_storeInstallOffers_query->fetch(PDO::FETCH_ASSOC);

                $appSubPlanId = $get_data_storeInstall_offers['appSubscriptionPlanId'];
                $plan_name = $get_data_storeInstall_offers['planName'];
                if ($appSubPlanId) {

                    appUsageRecordCreate($appSubPlanId, $purchage_plan_amount, $shopifies, $get_data_storeInstall_offers['store_id'], $db, $plan_name, $order_currency, $contract_id);
                }
                $sql_ordercontract_query = "INSERT INTO subscriptionOrderContract (`store_id`, `contract_id`, `plan_type`, `contract_products`, `order_id`, `order_no`, `shopify_customer_id`, `billing_policy_value`, `delivery_policy_value`, `delivery_billing_type`, `min_cycle`, `max_cycle`, `anchor_day`, `cut_off_days`, `after_cycle`, `after_cycle_update`,`selling_plan_id`, `discount_type`, `discount_value`, `recurring_discount_type`, `recurring_discount_value`, `next_billing_date`, `contract_status`, `firstRenewal_dateTime`,`created_at`,`order_currency`,`updated_at`,`sd_gender`,`sd_product_type`,`sd_size`) VALUES ('$store_id', '$contract_id', '$plan_type', '$contract_productid_string', '$order_id', '$order_no', '$customerId', '$billingPolicyValue', '$deliveryPolicyValue', '$deliveryBillingType', '$min_cycle', '$max_cycle', '$anchor_day', '$cut_off_days', '$after_cycle','$after_cycle_update','$selling_plan_id', '$discount_type', '$discount_value', '$recurring_discount_type', '$recurring_discount_value', '$renewalDate', '$contract_status', '$nextBillingDate','$currentUtcDate','$order_currency','$currentUtcDate','$sd_gender','$sd_productType','$sd_size')";
                $db->exec($sql_ordercontract_query);
            } catch (Exception $e) {
                $proceed_contract = 'No';
                file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n multiple_insert_update_row subscriptionOrderContract 1 failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }

            // insert data into contract_sale
            try {

                $insert_contract_sale_query = "INSERT INTO contract_sale (`store_id`,`contract_id`,`total_sale`,`contract_currency`) VALUES('$store_id','$contract_id', '$total_subscription_price','$order_currency')";

                $db->exec($insert_contract_sale_query);
            } catch (Exception $e) {       
                $proceed_contract = 'No';
                file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n multiple_insert_update_row subscriptionOrderContract 1 failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }

            // insert customer values

            try {

                $sql = "INSERT INTO customers (`store`, `store_id`, `shopify_customer_id`, `email`, `name`, `created_at`) VALUES (:store, :store_id, :shopify_customer_id, :email, :name, :created_at)
                ON DUPLICATE KEY UPDATE 
                email = VALUES(email),
                name = VALUES(name)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':store', $store);
                $stmt->bindParam(':store_id', $store_id);
                $stmt->bindParam(':shopify_customer_id', $customerId);
                $stmt->bindParam(':email', $customerEmail);
                $stmt->bindParam(':name', $customerName);
                $stmt->bindParam(':created_at', $currentUtcDate);
                $insert = $stmt->execute();
            } catch (Exception $e) {
                $proceed_contract = 'No';
                file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n multiple_insert_update_row single customers 3 failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }

            if (!empty($shippingAddressAry)) {

                $ship_first_name = $shippingAddressAry['firstName'];

                $ship_last_name = $shippingAddressAry['lastName'];

                $ship_address1 = $shippingAddressAry['address1'];

                $ship_address2 = $shippingAddressAry['address2'];

                $ship_city = $shippingAddressAry['city'];

                $ship_province = $shippingAddressAry['province'];

                $ship_country = $shippingAddressAry['country'];

                $ship_company = $shippingAddressAry['company'];

                $ship_phone = $shippingAddressAry['phone'];

                $ship_province_code = $shippingAddressAry['provinceCode'];

                $ship_country_code = $shippingAddressAry['countryCodeV2'];

                $ship_delivery_method = $shippingLineAry['code'];

                // $ship_delivery_price = $shippingLineAry['originalPriceSet']['shopMoney']['amount'];

                $ship_zip = $shippingAddressAry['zip'];

                try {

                    $check_entry_subcontract_ship = $db->prepare("SELECT * FROM subscriptionContractShippingAddress WHERE LOWER(store_id) = LOWER('$store_id') AND LOWER(contract_id) = LOWER('$contract_id')");

                    $check_entry_subcontract_ship->execute();

                    $subcontract_entry_count = $check_entry_subcontract_ship->rowCount();

                    if ($subcontract_entry_count) {

                        // $update_subcontract_ship = $db->prepare("UPDATE subscriptionContractShippingAddress SET first_name = '$ship_first_name', last_name = '$ship_last_name', address1 = '$ship_address1', address2 = '$ship_address2', city = '$ship_city', province = '$ship_province', country = '$ship_country', company = '$ship_company', phone = '$ship_phone', province_code = '$ship_province_code', country_code = '$ship_country_code', delivery_method = '$ship_delivery_method', delivery_price = '$ship_delivery_price', zip = '$ship_zip' WHERE store_id = '$store_id' AND contract_id = '$contract_id'");

                        // $update_subcontract_ship->execute();

                        $sql = "UPDATE subscriptionContractShippingAddress SET 
                            first_name = :first_name,
                            last_name = :last_name,
                            address1 = :address1,
                            address2 = :address2,
                            city = :city,
                            province = :province,
                            country = :country,
                            company = :company,
                            phone = :phone,
                            province_code = :province_code,
                            country_code = :country_code,
                            delivery_method = :delivery_method,
                            delivery_price = :delivery_price,
                            zip = :zip
                        WHERE store_id = :store_id AND contract_id = :contract_id";

                        $update_subcontract_ship = $db->prepare($sql);

                        $update_subcontract_ship->bindParam(':first_name', $ship_first_name);
                        $update_subcontract_ship->bindParam(':last_name', $ship_last_name);
                        $update_subcontract_ship->bindParam(':address1', $ship_address1);
                        $update_subcontract_ship->bindParam(':address2', $ship_address2);
                        $update_subcontract_ship->bindParam(':city', $ship_city);
                        $update_subcontract_ship->bindParam(':province', $ship_province);
                        $update_subcontract_ship->bindParam(':country', $ship_country);
                        $update_subcontract_ship->bindParam(':company', $ship_company);
                        $update_subcontract_ship->bindParam(':phone', $ship_phone);
                        $update_subcontract_ship->bindParam(':province_code', $ship_province_code);
                        $update_subcontract_ship->bindParam(':country_code', $ship_country_code);
                        $update_subcontract_ship->bindParam(':delivery_method', $ship_delivery_method);
                        $update_subcontract_ship->bindParam(':delivery_price', $ship_delivery_price);
                        $update_subcontract_ship->bindParam(':zip', $ship_zip);
                        $update_subcontract_ship->bindParam(':store_id', $store_id);
                        $update_subcontract_ship->bindParam(':contract_id', $contract_id);

                        $update = $update_subcontract_ship->execute();
                    } else {

                        // $insert_subcontract_ship = $db->prepare("INSERT INTO subscriptionContractShippingAddress (store_id, contract_id, first_name, last_name, address1, address2, city, province, country, company, phone, province_code, country_code, delivery_method, delivery_price, zip, created_at) VALUES ('$store_id', '$contract_id', '$ship_first_name', '$ship_last_name', '$ship_address1', '$ship_address2', '$ship_city', '$ship_province', '$ship_country', '$ship_company', '$ship_phone', '$ship_province_code', '$ship_country_code', '$ship_delivery_method', '$ship_delivery_price', '$ship_zip', '$currentUtcDate')");

                        // $insert_subcontract_ship->execute();

                        $sql = "INSERT INTO subscriptionContractShippingAddress (
                            store_id, contract_id, first_name, last_name, address1, address2, city, province, country,
                            company, phone, province_code, country_code, delivery_method, delivery_price, zip, created_at
                        ) VALUES (
                            :store_id, :contract_id, :first_name, :last_name, :address1, :address2, :city, :province, :country,
                            :company, :phone, :province_code, :country_code, :delivery_method, :delivery_price, :zip, :created_at
                        )";

                        $insert_subcontract_ship = $db->prepare($sql);

                        $insert_subcontract_ship->bindParam(':store_id', $store_id);
                        $insert_subcontract_ship->bindParam(':contract_id', $contract_id);
                        $insert_subcontract_ship->bindParam(':first_name', $ship_first_name);
                        $insert_subcontract_ship->bindParam(':last_name', $ship_last_name);
                        $insert_subcontract_ship->bindParam(':address1', $ship_address1);
                        $insert_subcontract_ship->bindParam(':address2', $ship_address2);
                        $insert_subcontract_ship->bindParam(':city', $ship_city);
                        $insert_subcontract_ship->bindParam(':province', $ship_province);
                        $insert_subcontract_ship->bindParam(':country', $ship_country);
                        $insert_subcontract_ship->bindParam(':company', $ship_company);
                        $insert_subcontract_ship->bindParam(':phone', $ship_phone);
                        $insert_subcontract_ship->bindParam(':province_code', $ship_province_code);
                        $insert_subcontract_ship->bindParam(':country_code', $ship_country_code);
                        $insert_subcontract_ship->bindParam(':delivery_method', $ship_delivery_method);
                        $insert_subcontract_ship->bindParam(':delivery_price', $ship_delivery_price);
                        $insert_subcontract_ship->bindParam(':zip', $ship_zip);
                        $insert_subcontract_ship->bindParam(':created_at', $currentUtcDate);

                        $insert = $insert_subcontract_ship->execute();
                    }
                } catch (Exception $e) {
                    $proceed_contract = 'No';
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n insert update into subscriptionContractShippingAddress if failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            } else {

                try {

                    $check_entry_subcontract_ship = $db->prepare("SELECT * FROM subscriptionContractShippingAddress WHERE LOWER(store_id) = LOWER('$store_id') AND LOWER(contract_id) = LOWER('$contract_id')");

                    $check_entry_subcontract_ship->execute();

                    $subcontract_entry_count = $check_entry_subcontract_ship->rowCount();

                    if (!$subcontract_entry_count) {

                        $insert_subcontract_ship = $db->prepare("INSERT INTO subscriptionContractShippingAddress (store_id, contract_id, created_at) VALUES ('$store_id', '$contract_id', '$currentUtcDate')");

                        $insert_subcontract_ship->execute();
                    }
                } catch (Exception $e) {
                    $proceed_contract = 'No';
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n insert update into subscriptionContractShippingAddress else failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            }

            //save contract Billing address

            $billingAddressAry = $contract_detail['data']['subscriptionContract']['originOrder']['billingAddress'];

            if (!empty($billingAddressAry)) {

                $bill_first_name = $billingAddressAry['firstName'];

                $bill_last_name = $billingAddressAry['lastName'];

                $bill_address1 = $billingAddressAry['address1'];

                $bill_address2 = $billingAddressAry['address2'];

                $bill_city = $billingAddressAry['city'];

                $bill_province = $billingAddressAry['province'];

                $bill_country = $billingAddressAry['country'];

                $bill_company = $billingAddressAry['company'];

                $bill_phone = $billingAddressAry['phone'];

                $bill_province_code = $billingAddressAry['provinceCode'];

                $bill_country_code = $billingAddressAry['countryCodeV2'];

                $bill_zip = $billingAddressAry['zip'];

                try {

                    $check_entry_subcontract_bill = $db->prepare("SELECT * FROM subscriptionContractBillingAddress WHERE LOWER(store_id) = LOWER('$store_id') AND LOWER(contract_id) = LOWER('$contract_id')");

                    $check_entry_subcontract_bill->execute();

                    $subcontract_bill_entry_count = $check_entry_subcontract_bill->rowCount();

                    if ($subcontract_bill_entry_count) {

                        // $update_subcontract_bill = $db->prepare("UPDATE subscriptionContractBillingAddress SET first_name = '$bill_first_name', last_name = '$bill_last_name', address1 = '$bill_address1', address2 = '$bill_address2', city = '$bill_city', province = '$bill_province', country = '$bill_country', company = '$bill_company', phone = '$bill_phone', province_code = '$bill_province_code', country_code = '$bill_country_code', zip = '$bill_zip' WHERE store_id = '$store_id' AND contract_id = '$contract_id'");

                        // $update_subcontract_bill->execute();
                        $sql = "UPDATE subscriptionContractBillingAddress SET 
                            first_name = :first_name,
                            last_name = :last_name,
                            address1 = :address1,
                            address2 = :address2,
                            city = :city,
                            province = :province,
                            country = :country,
                            company = :company,
                            phone = :phone,
                            province_code = :province_code,
                            country_code = :country_code,
                            zip = :zip
                        WHERE store_id = :store_id AND contract_id = :contract_id";

                        $update_subcontract_bill = $db->prepare($sql);

                        $update_subcontract_bill->bindParam(':first_name', $bill_first_name);
                        $update_subcontract_bill->bindParam(':last_name', $bill_last_name);
                        $update_subcontract_bill->bindParam(':address1', $bill_address1);
                        $update_subcontract_bill->bindParam(':address2', $bill_address2);
                        $update_subcontract_bill->bindParam(':city', $bill_city);
                        $update_subcontract_bill->bindParam(':province', $bill_province);
                        $update_subcontract_bill->bindParam(':country', $bill_country);
                        $update_subcontract_bill->bindParam(':company', $bill_company);
                        $update_subcontract_bill->bindParam(':phone', $bill_phone);
                        $update_subcontract_bill->bindParam(':province_code', $bill_province_code);
                        $update_subcontract_bill->bindParam(':country_code', $bill_country_code);
                        $update_subcontract_bill->bindParam(':zip', $bill_zip);
                        $update_subcontract_bill->bindParam(':store_id', $store_id);
                        $update_subcontract_bill->bindParam(':contract_id', $contract_id);

                        $update = $update_subcontract_bill->execute();

                    } else {

                        // $insert_subcontract_bill = $db->prepare("INSERT INTO subscriptionContractBillingAddress (store_id, contract_id, first_name, last_name, address1, address2, city, province, country, company, phone, province_code, country_code, zip, created_at) VALUES ('$store_id', '$contract_id', '$bill_first_name', '$bill_last_name', '$bill_address1', '$bill_address2', '$bill_city', '$bill_province', '$bill_country', '$bill_company', '$bill_phone', '$bill_province_code', '$bill_country_code', '$bill_zip', '$currentUtcDate')");

                        // $insert_subcontract_bill->execute();

                        $sql = "INSERT INTO subscriptionContractBillingAddress (
                            store_id, contract_id, first_name, last_name, address1, address2, city, province, country,
                            company, phone, province_code, country_code, zip, created_at
                        ) VALUES (
                            :store_id, :contract_id, :first_name, :last_name, :address1, :address2, :city, :province, :country,
                            :company, :phone, :province_code, :country_code, :zip, :created_at
                        )";
                
                        $insert_subcontract_bill = $db->prepare($sql);
                        
                        $insert_subcontract_bill->bindParam(':store_id', $store_id);
                        $insert_subcontract_bill->bindParam(':contract_id', $contract_id);
                        $insert_subcontract_bill->bindParam(':first_name', $bill_first_name);
                        $insert_subcontract_bill->bindParam(':last_name', $bill_last_name);
                        $insert_subcontract_bill->bindParam(':address1', $bill_address1);
                        $insert_subcontract_bill->bindParam(':address2', $bill_address2);
                        $insert_subcontract_bill->bindParam(':city', $bill_city);
                        $insert_subcontract_bill->bindParam(':province', $bill_province);
                        $insert_subcontract_bill->bindParam(':country', $bill_country);
                        $insert_subcontract_bill->bindParam(':company', $bill_company);
                        $insert_subcontract_bill->bindParam(':phone', $bill_phone);
                        $insert_subcontract_bill->bindParam(':province_code', $bill_province_code);
                        $insert_subcontract_bill->bindParam(':country_code', $bill_country_code);
                        $insert_subcontract_bill->bindParam(':zip', $bill_zip);
                        $insert_subcontract_bill->bindParam(':created_at', $currentUtcDate);
                        
                        $insert = $insert_subcontract_bill->execute();
                
                    }
                } catch (Exception $e) {
                    $proceed_contract = 'No';
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n insert update into subscriptionContractBillingAddress if failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            } else {

                try {

                    $check_entry_subcontract_bill = $db->prepare("SELECT * FROM subscriptionContractBillingAddress WHERE LOWER(store_id) = LOWER('$store_id') AND LOWER(contract_id) = LOWER('$contract_id')");

                    $check_entry_subcontract_bill->execute();

                    $subcontract_bill_entry_count = $check_entry_subcontract_bill->rowCount();

                    if (!$subcontract_bill_entry_count) {

                        $insert_subcontract_bill = $db->prepare("INSERT INTO subscriptionContractBillingAddress (store_id, contract_id, created_at) VALUES ('$store_id', '$contract_id', '$currentUtcDate')");

                        $insert_subcontract_bill->execute();
                    }
                } catch (Exception $e) {
                    $proceed_contract = 'No';
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n insert update into subscriptionContractBillingAddress else failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            }



            $last_four_digits = $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['lastDigits'] ?? '';

            $card_brand = $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['brand'] ?? '';

            $card_name = $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['name'] ?? '';

            $card_expire_month = $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['expiryMonth'] ?? '';

            $card_expire_year = $contract_detail['data']['subscriptionContract']['customerPaymentMethod']['instrument']['expiryYear'] ?? '';



            $replace_with_value = array('contract_id' => $contract_id, 'customerEmail' => $customerEmail, 'customerName' => $customerName, 'customerId' => $customerId, 'nextBillingDate' => $nextBillingDate, 'ship_first_name' => $ship_first_name . ' ' . $ship_last_name, 'ship_address1' => $ship_address1, 'ship_company' => $ship_company, 'ship_city' => $ship_city, 'ship_province' => $ship_province, 'ship_province_code' => $ship_province_code, 'ship_zip' => $ship_zip, 'bill_first_name' => $bill_first_name . ' ' . $bill_last_name, 'bill_address1' => $bill_address1, 'bill_city' => $bill_city, 'bill_province_code' => $bill_province_code, 'bill_zip' => $bill_zip, 'footer_text' => $footer_text, 'last_four_digits' => $last_four_digits, 'card_expire_month' => $card_expire_month, 'card_expire_year' => $card_expire_year, 'shop_name' => $store_install_data['shop_name'], 'store_email' => $store_install_data['store_email'], 'store' => $store, 'manage_subscription_url' => $manage_subscription_url, 'email_subject' => $email_subject, 'heading_text_color' => $heading_text_color, 'text_color' => $text_color, 'heading_text' => $heading_text, 'logo_image' => $logo, 'manage_button_background' => $manage_button_background, 'manage_subscription_txt' => $manage_subscription_txt, 'manage_button_text_color' => $manage_button_text_color, 'shipping_address_text' => $shipping_address_text, 'billing_address_text' => $billing_address_text, 'payment_method_text' => $payment_method_text, 'ending_in_text' => $ending_in_text, 'qty_text' => $qty_text, 'logo_height' => $logo_height, 'logo_width' => $logo_width, 'thanks_image' => $thanks_img, 'thanks_image_height' => $thanks_img_height, 'thanks_image_width' => $thanks_img_width, 'logo_alignment' => $logo_alignment, 'thanks_image_alignment' => $thanks_img_alignment, 'card_brand' => $card_brand, 'order_number' => $order_number, 'shipping_address' => $shipping_address, 'billing_address' => $billing_address, 'custom_template' => $custom_template, 'ccc_email' => $ccc_email, 'bcc_email' => $bcc_email, 'reply_to' => $reply_to);

            //save order token and checkout token in subscriptionOrderContract table

            if ($mail_count == 1) {

                try {

                    $getOrderData = PostPutApi('https://' . $store . '/admin/api/' . $SHOPIFY_API_VERSION . '/orders/' . $order_id . '.json?fields=token', 'GET', $access_token, '');
                } catch (Exception $e) {
                    $proceed_contract = 'No';
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n getOrderData PostPutApi failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }

                $order_token = $getOrderData['order']['token'];
            }



            try {

                $update_suborder_contract = $db->prepare("UPDATE subscriptionOrderContract SET order_token = '$order_token' WHERE store_id = '$store_id' AND order_id = '$order_id'");

                $update_suborder_contract->execute();
            } catch (Exception $e) {
                file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n update subscriptionOrderContract failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }

            /***** From Webhook File *****/
            if ($proceed_contract == 'Yes') {
                $contract_details_query = "Update contract_details SET status = 'completed', plan_type = '$plan_type' WHERE store = '$store' AND order_id = '$order_id' AND contract_id = '$contract_id'";
                $contract_details_data = $db->exec($contract_details_query);
                $mail_count++;
                if ($plan_type == 'membership') {
                    send_membership_email($store, $customerId, $selling_plan_id, $contract_id, $purchage_plan_variantId, $store_install_data, $db, $SHOPIFY_DOMAIN_URL);
                }
                processContracts($processDataParameters, $contract_html, $merchant_subscription_title, $mail_count, $store_id, $customerEmail, $order_token, $contract_id, $payment_instrument_type, $customerId, $customerTagsArray, $orderTagsArray, $billingAddressAry, $shopifies, $email_template_data, $replace_with_value, $subscription_line_items);
            } else {

                $sendMailArray = array(

                    'sendTo' =>  'ranabikram87570@gmail.com',

                    'subject' => 'Contract not proceeded',

                    'mailBody' => 'Please check the contract id ' . $contract_id . ' of store ' . $store,

                    'mailHeading' => '',

                    'ccc_email' => 'ranabikram87570@gmail.com',

                    'bcc_email' => '',

                    'reply_to' => '',

                );
                // print_r($sendMailArray);

                try {
                    if ($plan_type == 'subscription') {

                        sendMail($sendMailArray, 'false', $store_id, $db, $store);
                    }

                    if ((!empty($email_template_data)  && $plan_type == 'membership') || (!empty($email_template_data) && $store == 'thediyart1.myshopify.com')) {
                        send_membership_email($store, $customerId, $selling_plan_id, $contract_id, $purchage_plan_variantId, $store_install_data, $db, $SHOPIFY_DOMAIN_URL);
                    }
                } catch (Exception $e) {
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n sendMail() function 1 failed. " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            }
        } catch (Exception $e) {
            $proceed_contract = 'No';
            file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n Grphql failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
        }
    } else {
        $contract_details_query = $db->query("Select plan_type FROM contract_details WHERE store = '$store' AND order_id = '$order_id' AND status = 'completed'");

        $contract_details_data = $contract_details_query->fetch(PDO::FETCH_ASSOC);
        $plan_type = $contract_details_data['plan_type'];
        /***** From Webhook File *****/
        // echo('else part no any cron data');
        // get store detail and email notification setting

        try {

            $result = $db->query("SELECT store_email, customer_subscription_purchase, admin_subscription_purchase FROM store_details, email_notification_setting WHERE store_details.store_id = '$store_id' and email_notification_setting.store_id = '$store_id'");

            $get_store_details = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n Select store_email, new_subscription_purchase failed. " . $e->getMessage(), FILE_APPEND | LOCK_EX);
        }

        // add tag to customer

        $allSubscriptionCustomerTags = implode(",", $customerTagsArray);

        $allSubscriptionOrderTags = implode(",", $orderTagsArray);

        //add tag to the order

        $addTagQuery = 'mutation tagsAdd($id: ID!, $tags: [String!]!) {

            tagsAdd(id: $id, tags: $tags) {

                userErrors {

                    field

                    message

                }

            }

        }';

        $orderTagsParameters = [

            "id" => "gid://shopify/Order/$order_id",

            "tags" => $allSubscriptionOrderTags

        ];

        try {

            $shopifies->GraphQL->post($addTagQuery, null, null, $orderTagsParameters);
        } catch (Exception $e) {

            file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n add tag to custoemer graphql failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
        }

        $addTagQuery = 'mutation tagsAdd($id: ID!, $tags: [String!]!) {

            tagsAdd(id: $id, tags: $tags) {

                userErrors {

                    field

                    message

                }

            }

        }';

        $tagsParameters = [

            "id" => "gid://shopify/Customer/$customerId",

            "tags" => $allSubscriptionCustomerTags

        ];

        // if($allSubscriptionCustomerTags != '' && $customerId != '') {

        try {

            $shopifies->GraphQL->post($addTagQuery, null, null, $tagsParameters);
        } catch (Exception $e) {
            file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n add tag to custoemer graphql failed. " . $contract_id . " " . $e->getMessage(), FILE_APPEND | LOCK_EX);
        }

        // }

        //send mail to the customer and admin if setting is on

        if ($get_store_details && $plan_type == 'subscription') {

            $customer_subscription_purchase = $get_store_details['customer_subscription_purchase'];

            $admin_subscription_purchase = $get_store_details['admin_subscription_purchase'];

            $send_to_email = '';

            if ($replace_with_value['custom_template'] != '' && $replace_with_value['custom_template'] != '<br>'  && $template_type == 'custom') {

                $subscription_line_items .=

                    '             </tbody>

                            </table>

                        </td>

                    </tr>

                </tbody>

            </table>

            </td>

            </tr>

            </tbody>

            </table>

            </center>

            </td>

            </tr>

            </tbody>

              </table>

              </div>

              </div>

          </td>

            </tr>

                </tbody>

                </table>

                </div>



              ';

                $contract_html = str_replace("{{subscription_line_items}}", $subscription_line_items, $replace_with_value['custom_template']);
            } else if ($template_type == 'default') {

                if ($show_line_items == '1') {

                    $contract_html .= $subscription_line_items . '</tbody>

                                            </table>

                                        </td>

                                    </tr>

                                </tbody>

                                </table>

                                </td>

                                </tr>

                                </tbody>

                                </table>

                                </center>

                                </td>

                                </tr>

                                </tbody>

                                </table>';
                }

                $contract_html .= '<table style="width:100%;border-spacing:0;border-collapse:collapse">

                            <tbody>

                                <tr>

                                    <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0">

                                        <center>

                                            <table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

                                                <tbody>

                                                    <tr>

                                                        <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

                                                            <table style="width:100%;border-spacing:0;border-collapse:collapse">

                                                                <tbody>

                                                                    <tr>';

                if ($show_shipping_address == '1') {

                    if (!empty(trim($replace_with_value['ship_first_name']))) {

                        $shipping_address = $replace_with_value['shipping_address'];
                    } else {

                        $shipping_address = 'No shipping address.';
                    }





                    $contract_html .= '

                                                                        <td class="m_-1845756208323497270customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%" valign="top">

                                                                            <h4 style="font-weight:500;font-size:16px;color:' . $replace_with_value['heading_text_color'] . ';margin:0 0 5px" class="sd_heading_text_color_view sd_shipping_address_text_view">' . $replace_with_value['shipping_address_text'] . '</h4>

                                                                            <div class="sd_shipping_address_view sd_text_color_view" style="color:' . $replace_with_value['text_color'] . ';"><p style="color:' . $replace_with_value['text_color'] . ';line-height:150%;font-size:16px;margin:0">' . $shipping_address . '</div>

                                                                        </td>';
                }

                if ($show_billing_address == '1') {

                    $contract_html .= '<td class="m_-1845756208323497270customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%" valign="top">

                                                                            <h4 style="font-weight:500;font-size:16px;color:' . $replace_with_value['heading_text_color'] . ';margin:0 0 5px" class="sd_heading_text_color_view sd_billing_address_text_view">' . $replace_with_value['billing_address_text'] . '</h4>

                                                                            <div class="sd_billing_address_view sd_text_color_view" style="color:' . $replace_with_value['text_color'] . ';"><p style="color:' . $replace_with_value['text_color'] . ';line-height:150%;font-size:16px;margin:0">' . $replace_with_value['billing_address'] . '</p></div>

                                                                        </td>';
                }

                $contract_html .= '</tr>

                                                                </tbody>

                                                            </table>';

                if ($show_payment_method == '1') {

                    $contract_html .= '<table style="width:100%;border-spacing:0;border-collapse:collapse">

                                                                    <tbody>

                                                                        <tr>

                                                                            <td class="m_-1845756208323497270customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%" valign="top">

                                                                                <h4 style="font-weight:500;font-size:16px;color:' . $replace_with_value['heading_text_color'] . ';margin:0 0 5px" class="sd_heading_text_color_view sd_payment_method_text_view">' . $replace_with_value['payment_method_text'] . '</h4>

                                                                                <p style="color:' . $replace_with_value['text_color'] . ';line-height:150%;font-size:16px;margin:0">

                                                                                    {{card_brand}}

                                                                                    <span style="font-size:16px;color:' . $replace_with_value['text_color'] . '" class="sd_text_color_view sd_ending_in_text_view">' . $replace_with_value['ending_in_text'] . ' {{last_four_digits}}</span><br>

                                                                                </p>

                                                                            </td>

                                                                        </tr>

                                                                    </tbody>

                                                                </table>';
                }

                $contract_html .= '</td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </center>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                        <table style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid">

                            <tbody>

                                <tr>

                                    <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:35px 0">

                                        <center>

                                            <table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

                                                <tbody>

                                                    <tr>

                                                        <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

                                                        <div class="sd_footer_text_view">' . $replace_with_value['footer_text'] . '</div>

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </center>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                        <img src="https://ci4.googleusercontent.com/proxy/C5WAwfRu-nhYYB726ZtDmBBZxH2ZQQgtpxwmJT5KONtMOVp6k7laRdD7JghQXsHLcYM4veQr436syfT22M4kVYeof9oM4TIq5I7li0_YUjrim2hpHv5dYG7V9z9OmFYRRwYK3KgYIf0ck0d_WTq1EjhX_DpBFoi4n20fTmcCfJxl76PIrL1HodOHxbkR8PrieSaJX9F3tcNZb-9L3JTm7_owWlAKVQ64kFMBmJHwK7I=s0-d-e1-ft#https://cdn.shopify.com/shopifycloud/shopify/assets/themes_support/notifications/spacer-1a26dfd5c56b21ac888f9f1610ef81191b571603cb207c6c0f564148473cab3c.png" class="m_-1845756208323497270spacer CToWUd" height="1" style="min-width:600px;height:0" data-bit="iit">

                    </td>

                </tr>

                </tbody>

                </table>

                            </div>

                            </div>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

            </div>

            ';
            }

            $html_result = str_replace(

                array(

                    '{{subscription_contract_id}}',
                    '{{customer_email}}',
                    '{{customer_name}}',
                    '{{customer_id}}',
                    '{{next_order_date}}',
                    '{{shipping_full_name}}',
                    '{{shipping_address1}}',
                    '{{shipping_company}}',
                    '{{shipping_city}}',
                    '{{shipping_province}}',
                    '{{shipping_province_code}}',
                    '{{shipping_zip}}',
                    '{{billing_full_name}}',
                    '{{billing_address1}}',
                    '{{billing_city}}',
                    '{{billing_province_code}}',
                    '{{billing_zip}}',
                    '{{footer_text}}',
                    '{{last_four_digits}}',
                    '{{card_expire_month}}',
                    '{{card_expire_year}}',
                    '{{shop_name}}',
                    '{{shop_email}}',
                    '{{shop_domain}}',
                    '{{manage_subscription_url}}',
                    '{{email_subject}}',
                    '{{header_text_color}}',
                    '{{text_color}}',
                    '{{heading_text}}',
                    '{{logo_image}}',
                    '{{manage_subscription_button_color}}',
                    '{{manage_subscription_button_text}}',
                    '{{manage_subscription_button_text_color}}',
                    '{{shipping_address_text}}',
                    '{{billing_address_text}}',
                    '{{payment_method_text}}',
                    '{{ending_in_text}}',
                    '{{quantity_text}}',
                    '{{logo_height}}',
                    '{{logo_width}}',
                    '{{thanks_image}}',
                    '{{thanks_image_height}}',
                    '{{thanks_image_width}}',
                    '{{logo_alignment}}',
                    '{{thanks_image_alignment}}',
                    '{{card_brand}}',
                    '{{order_number}}',
                    '{{shipping_address}}',
                    '{{billing_address}}',
                    '{{custom_template}}',
                    '{{ccc_email}}',
                    '{{bcc_email}}',
                    '{{reply_to}}'

                ),

                array_values($replace_with_value),

                $contract_html

            );


            echo $html_result;

            $send_to_email = '';

            $store_email = $get_store_details['store_email'];

            if ($customer_subscription_purchase == '1' && $admin_subscription_purchase == '1') {

                $send_to_email = array($customerEmail, $store_email);
            } else if ($customer_subscription_purchase == '1' && $admin_subscription_purchase != '1') {

                $send_to_email = $customerEmail;
            } else if ($customer_subscription_purchase != '1' && $admin_subscription_purchase == '1') {

                $send_to_email = $store_email;
            }



            if ($send_to_email != '' && $template_type != 'none' && strlen(preg_replace('/[\x00-\x1F\x7F]/', '', $html_result)) != 0) {

                $sendMailArray = array(

                    'sendTo' =>  $send_to_email,

                    'subject' => $replace_with_value['email_subject'],

                    'mailBody' => $html_result,

                    'mailHeading' => '',

                    'ccc_email' => $replace_with_value['ccc_email'],

                    'bcc_email' => $replace_with_value['bcc_email'],

                    'reply_to' => $replace_with_value['reply_to'],

                );

                try {


                    sendMail($sendMailArray, 'false', $store_id, $db, $store);

                    // sendMail($sendMailArray, 'false', $store_id, $db, $store);
                } catch (Exception $e) {
                    file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n sendMail() function 1 failed. " . $e->getMessage(), FILE_APPEND | LOCK_EX);
                }
            }
        }

        /***** From Webhook File *****/
        try{
        $contract_cron_update_query = "Update contract_cron SET status = 'completed' WHERE store = '$store' AND order_id = '$order_id'";

        $db->exec($contract_cron_update_query);
        }catch (Exception $e) {
            file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n sendMail() function 1 failed. " . $e->getMessage(), FILE_APPEND | LOCK_EX);
        }

        exit;
    }

    $db = null;
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

    if ($err) {

        echo "cURL Error #:" . $err;
    } else {

        return json_decode($response, true);
    }
}



function sendMail($sendMailArray, $testMode, $store_id, $db, $store)
{

    $email_configuration = 'false';

    $email_host =  "smtp.gmail.com";
    $username = "shopify@phoenixtechnologies.io";
    $password = "gqumwotkgxekdowf";
    $from_email = "shopify@phoenixtechnologies.io";
    $encryption = 'tls';
    $port_number = 587;


    //For pending mail

    if (array_key_exists("store_id", $sendMailArray)) {

        $store_id = $sendMailArray['store_id'];
    } else {

        $store_id = $store_id;
    }

    $store_detail_query = $db->query("SELECT pending_emails, store_email,shop_name FROM email_counter, store_details WHERE email_counter.store_id = '$store_id' AND store_details.store_id = '$store_id'");

    $store_detail = $store_detail_query->fetch(PDO::FETCH_ASSOC);



    $subject = $sendMailArray['subject'] ?? '';

    $sendTo = $sendMailArray['sendTo'] ?? '';

    $email_template_body = $sendMailArray['mailBody'] ?? '';

    $mailHeading = $sendMailArray['mailHeading'] ?? '';



    $check_entry = $db->prepare("SELECT * FROM email_configuration WHERE LOWER(store_id) = LOWER('$store_id')");

    $check_entry->execute();

    $email_configuration_data = $check_entry->rowCount();

    if ($email_configuration_data) {

        $query = "SELECT * FROM email_configuration WHERE store_id = '$store_id' ORDER BY id DESC";

        $result = $db->query($query);

        $email_configuration_data = $result->fetch(PDO::FETCH_ASSOC);
    }
    // print_r($email_configuration_data);

    if ($email_configuration_data) {

        if ($email_configuration_data['email_enable'] == 'checked') {

            $email_host = $email_configuration_data['email_host'] ?? '';

            $username = $email_configuration_data['username'] ?? '';

            $password = $email_configuration_data['password'] ?? '';

            $from_email = $email_configuration_data['from_email'] ?? '';

            $encryption = $email_configuration_data['encryption'] ?? '';

            $port_number = $email_configuration_data['port_number'] ?? '';

            $email_configuration = 'true';
        }
    }

    $pending_emails = $store_detail['pending_emails'] ?? '';



    $mail =  new PHPMailer\PHPMailer\PHPMailer();

    $mail->IsSMTP();

    $mail->CharSet = "UTF-8";

    $mail->Host = $email_host;

    $mail->SMTPDebug = 1;

    $mail->Port = $port_number; //465 or 587

    $mail->SMTPDebug = false;

    $mail->SMTPSecure = $encryption;

    $mail->SMTPAuth = true;

    $mail->IsHTML(true);

    //Authentication

    $mail->Username = $username;

    $mail->Password = $password;

    //Set Params

    $mail->addReplyTo($sendMailArray['reply_to']);

    $mail->SetFrom($from_email);

    if (is_array($sendTo)) {

        $mail->AddAddress($sendTo[0]);

        $mail->AddAddress($sendTo[1]);

        $decrease_counter = 2;
    } else {

        $mail->AddAddress($sendTo);

        $decrease_counter = 1;
    }

    $mail->Subject = $subject;

    $mail->Body = $email_template_body;

    if (!$mail->Send()) {
        echo 'mail not sent';
    } else {
        echo 'mail sent successfully';
    }
}



function modifyDate($date)
{
    $timestamp = strtotime($date);

    $day = date('d', $timestamp);

    $month = date('m', $timestamp);

    $target_day = 20;

    $target_month = $month;



    if ($day >= $target_day) {

        $target_month++;
    }



    $target_month_name = date('F', mktime(0, 0, 0, $target_month, 1));

    $modified_date = $target_day . ' ' . $target_month_name . ' ' . date('Y', $timestamp);



    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {

        $modified_date = date('Y-m-d', strtotime($modified_date));
    }

    return $modified_date;
}


// public function appUsageRecordCreate($data)
function appUsageRecordCreate($app_sub_plan_id, $price, $shopifies, $store_id, $db, $plan_name, $order_currency, $contract_id)
{

    $Planprice  = $price;
    $SubplanItemId = getAppSubscriptionLineId($shopifies)[0]['id'];
    $storeId  = $store_id;

    // Calculate 5% of the plan price
    $percentage = 5;
    $extra_charge = 0.12;
    $amount = ($Planprice * $percentage / 100); // add extra charge 0.15

    // Set a minimum charge of $1 if neede +++


    $miniCharge = 0.10;
    // price: { amount: ' . $amount . ', currencyCode: USD }
    if ($amount >= $miniCharge) {

        $amount = $amount + $extra_charge;

        $craete_action_on_billing = '
        mutation {
            appUsageRecordCreate(
                subscriptionLineItemId: "' . $SubplanItemId . '",
                description: "5% of per order +  0.12 tax",
                price: { amount: ' . $amount . ', currencyCode:  ' . $order_currency . ' }
            ) {
                appUsageRecord {
                    id
                    price {
                        amount
                        currencyCode
                    }
                }
                userErrors {
                    field
                    message
                }
            }
        }';

        // Extracting values from response
        $response = $shopifies->GraphQL->post($craete_action_on_billing);
        $errors = $response['data']['appUsageRecordCreate']['userErrors'] ?? [];

        $usage_desc = '5% charge for new plan "' . $plan_name . '"';
        // Optional: Escape double quotes if not using prepared statements
        $usage_desc = str_replace('"', '\"', $usage_desc);

        $amount = $amount = $response['data']['appUsageRecordCreate']['appUsageRecord']['price']['amount'] ?? 0;
        $usage_amount = is_numeric($amount)
            ? number_format($amount, 2, '.', '')  // ensures 2 decimal places like "24.20"
            : '0.00';

        if ($response && empty($errors)) {

            $usageRecodeId = $response['data']['appUsageRecordCreate']['appUsageRecord']['id'];
            $current_date = date('Y-m-d H:i:s');

            $stmt = $db->prepare("INSERT INTO UsageCharge (store_id, price, charge_amount, charged_at, sub_plan_item_id, usage_record_id,order_currency ,contract_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $savedata = $stmt->execute([$storeId, $price, $usage_amount, $current_date, $app_sub_plan_id, $usageRecodeId, $order_currency, $contract_id]);

            echo 'Usage charge taken successfully.';
            return $savedata;
        } else {
            echo ('some error');
        }
    }
}

function getAppSubscriptionLineId($shopifies) {

    $query = '{
  currentAppInstallation {
    activeSubscriptions {
      name
      status
      lineItems {
        id
        plan {
          pricingDetails {
            __typename
          }
        }
      }
    }
  }
}';
    $data = $shopifies->GraphQL->post($query);
    $filteredSubscriptions = [];
 
foreach ($data['data']['currentAppInstallation']['activeSubscriptions'] as $subscription) {
    foreach ($subscription['lineItems'] as $item) {
        if ($item['plan']['pricingDetails']['__typename'] === 'AppUsagePricing') {
            $filteredSubscriptions[] = $item;
        }
    }
}
return $filteredSubscriptions;
 
}
// member plan email
function send_membership_email($store, $customerId, $selling_plan_id, $contract_id, $variantId, $store_install_data, $db, $SHOPIFY_DOMAIN_URL)
{
    // print_r($store);
    // print_r($customerId);
    // print_r($selling_plan_id);
    // print_r($contract_id);
    // print_r($variantId);
    // print_r($store_install_data);
    // print_r($db);
    // print_r($SHOPIFY_DOMAIN_URL);

    $currentDate = date('Y-m-d');

    // $store_details_data = $this->getStoreDetailsByDomain($store);
    $store_install_data = (object) $store_install_data;

    $store_currency = '';
    $store = trim($store);

    // print_r($store_install_data);

    $owner_email = $store_install_data->store_email;
    $shop_name = $store_install_data->shop_name;
    $store_id = $store_install_data->store_id;
    $shop_timezone = $store_install_data->shop_timezone;
    $access_token = $store_install_data->access_token;

    $config = array(
        'ShopUrl' => $store,
        'AccessToken' => $access_token
    );

    $shopifies = new ShopifySDK($config);

    // $emailConfirmation = DB::table('email_notification_settings')->where('store', $store)->first();
    $result = $db->query("SELECT * FROM email_notification_settings WHERE store = '$store' LIMIT 1");
    $emailConfirmation = $result->fetch(PDO::FETCH_OBJ);

    // print_r($emailConfirmation);

    $admin_email_confirm = $emailConfirmation->admin_new_purchase_plan ?? '';
    $customer_email_confirm = $emailConfirmation->customer_new_purchase_plan ?? '';
    try {

        // $getEmailTempDetails = DB::table('new_purchase_plans')
        //     ->select('new_purchase_plans.*')
        //     ->where('new_purchase_plans.store', $store)
        //     ->first();

        $ew_purchase_plans_query = $db->query("SELECT * FROM new_purchase_plans WHERE store = '$store' LIMIT 1");
        $getEmailTempDetails = $ew_purchase_plans_query->fetch(PDO::FETCH_OBJ);

        // print_r($getEmailTempDetails);
        // die;

        // we are using subscritionOrderContractProductDetails  for purchased_membership_product_details table ++++++

        $result = $db->query("
            SELECT subscritionOrderContractProductDetails.*, 
                membership_perks.*, 
                membership_plans.*
            FROM subscritionOrderContractProductDetails
            INNER JOIN membership_perks 
                ON membership_perks.variant_id = subscritionOrderContractProductDetails.variant_id
            INNER JOIN membership_plans 
                ON membership_plans.id = membership_perks.membership_plan_id
            WHERE subscritionOrderContractProductDetails.variant_id = '$variantId'
            AND membership_perks.store = '$store'
            AND subscritionOrderContractProductDetails.contract_id = '$contract_id'
        ");


        $customer_data = $result->fetchAll(PDO::FETCH_OBJ);

        // print_r($customer_data);
        if (empty($customer_data)) {
            echo 'Data not found';
        }

        echo "SELECT * FROM customers WHERE store_id = '$store' AND shopify_customer_id = '$customerId' LIMIT 1";
        $result = $db->query("SELECT * FROM customers WHERE store_id = '$store_id' AND shopify_customer_id = '$customerId' LIMIT 1");
        $customerDetails = $result->fetch(PDO::FETCH_OBJ);
        $store_id = $customerDetails->store_id;
        $customerName = $customerDetails->name;
        $customerEmail = $customerDetails->email;

        $emailTemplateDetails = '';
        foreach ($customer_data as $value) {
            $discounted_product_collection_code = $value->discounted_product_collection_code ?? "";
            $free_shipping_code = $value->free_shipping_code ?? "";
            $early_sale_days = $value->no_of_sale_days ?? "";
            $freeshipping_selected_value = $value->freeshipping_selected_value ?? "";
            $min_purchase_amount_value = $value->min_purchase_amount_value ?? "";
            $min_quantity_items = $value->min_quantity_items ?? "";
            $free_gift_checked = $value->Free_gift_uponsignup_checkbox ?? "";
            $variantFreeGiftId = $value->perk_free_gift_variant_id ?? "";
            $freeGiftDate = $value->free_gift_date ?? $currentDate;
            $free_gift_uponsignupSelected_Value = $value->free_gift_uponsignupSelected_Value ?? "";
            $Free_gift_uponsignup_productName = $value->Free_gift_uponsignup_productName ?? "";
            $discounted_product_collection_percentageoff = $value->discounted_product_collection_percentageoff ?? "";
            $discounted_product_collection_type = $value->discounted_product_collection_type ?? "";
            $discounted__product_title = $value->discounted__product_title ?? "";
            $discounted__collection_title = $value->discounted__collection_title ?? "";
            $free_gift_uponsignupSelectedDays = $value->free_gift_uponsignupSelectedDays ?? "";
            $purchasedMembershipPlanName = $value->membership_plan_name ?? "";
            $purchasedMembershipTierName = $value->perks_type_value ?? "";
            $purchasedMembershipPlanName = $purchasedMembershipPlanName . '-' . $purchasedMembershipTierName;
            $coupon_code = $discounted_product_collection_code ?? "";
            $free_sign_up = $Free_gift_uponsignup_productName ?? "";
            $free_sign_up_produt_date = $freeGiftDate ?? "";
            $template_data_for = [];
            $template_data_for['template_name'] = 'new_purchase_plans';
            $template_data_for['email_type'] = 'dynamic_email';
            $template_data_for['shop_name'] = $store;
            $template_data_for['shop'] = $shop_name;
            $template_data_for['discount_coupon_content'] = false;
            $template_data_for['free_shipping_coupon_content'] = false;
            $template_data_for['free_gift_uponsignupSelectedDays'] = $free_gift_uponsignupSelectedDays;
            $template_data_for['free_signup_product_content'] = false;
            $template_data_for['early_sale_content'] = false;
            $discount_on = '';
            $free_shipping_on = '';
            if ($discounted_product_collection_type == 'N') {
                $discount_on = '';
            } else if ($discounted_product_collection_type == 'P') {
                $discount_on = '% off on ' . $discounted__product_title . ' product';
            } else if ($discounted_product_collection_type == 'C') {
                $discount_on = '% off on ' . $discounted__collection_title . ' collection';
            }

            if ($freeshipping_selected_value == 'min_purchase_amount') {
                $free_shipping_on = ' (on purchasing minimum amount of ' . $store_currency . ' ' . $min_purchase_amount_value . ')';
            } else if ($freeshipping_selected_value == 'min_quantity_items') {
                $free_shipping_on = ' (on purchasing minimum quantity of ' . $min_quantity_items . ')';
            } else {
                $free_shipping_on = '';
            }
            if ($free_shipping_code != '' || $free_shipping_code != null) {
                $template_data_for['free_shipping_coupon_content'] = true;
            }
            if ($coupon_code != '' || $coupon_code != null) {
                $template_data_for['discount_coupon_content'] = true;
            }
            if ($Free_gift_uponsignup_productName != '' || $Free_gift_uponsignup_productName != null) {
                $template_data_for['free_signup_product_content'] = true;
            }
            $emailTemplateDetails = membershipAllEmailTemplates($template_data_for, $db, $SHOPIFY_DOMAIN_URL);

            // print_r($emailTemplateDetails);

            $subject = $getEmailTempDetails->subject ?? $emailTemplateDetails['subject'];
            $custom_email_html = $getEmailTempDetails->custom_email_html ?? '';
            $ccc_email = $getEmailTempDetails->ccc_email ?? $emailTemplateDetails['ccc_email'];
            $bcc_email = $getEmailTempDetails->bcc_email ?? $emailTemplateDetails['bcc_email'];
            $reply_to = $getEmailTempDetails->reply_to ?? $emailTemplateDetails['reply_to'];
            $email_template_html = $emailTemplateDetails['email_template_html'];

            $input = [
                "input" => [
                    "customerId" => "gid://shopify/Customer/" . $customerId . "",
                    "email" => $customerEmail,
                    "shippingLine" => [
                        "title" => "Custom Shipping",
                        "price" => 0.00
                    ],
                    "note" => "Test draft order",
                    "lineItems" => [
                        [
                            "title" => $Free_gift_uponsignup_productName,
                            "quantity" => 1,
                            "originalUnitPrice" => 0,
                            "variantId" => $variantFreeGiftId,
                            "appliedDiscount" => [
                                "description" => "Hurray! you get the free gift 🎁",
                                "value" => 100,
                                "amount" => 0,
                                "valueType" => "PERCENTAGE",
                            ],
                        ],
                    ],
                ],
            ];
            $createDraftOrder = 'mutation createDraftOrder($input: DraftOrderInput!) {
                draftOrderCreate(input: $input) {
                    draftOrder {
                    id
                    invoiceUrl
                    }
                    userErrors {
                    field
                    message
                    }
                }
            }';


            if ($free_gift_checked == 1 && $free_gift_uponsignupSelectedDays == 'Immediately_after_signup') {
                $data = $shopifies->GraphQL->post($createDraftOrder, null, null, $input);

                if ($data) {
                    $draftOrderId = $data['data']['draftOrderCreate']['draftOrder']['id'];
                    $draftInvoice = $data['data']['draftOrderCreate']['draftOrder']['invoiceUrl'];
                    // $draftInvoice
                    if ($custom_email_html != null || !empty($custom_email_html)) {
                        $body = $custom_email_html;
                        $body = str_replace(array("{{customer_name}}", "{{customer_email}}", "{{coupon_code}}", "{{free_shipping_code}}", "{{free_signup_product}}", "{{free_sign_up_produt_date}}", "{{immediate_sign_up_produt}}", "{{plan_name}}", "{{percentage_discount}}% off", "{{store_name}}"), array($customerName, $customerEmail, $coupon_code, $free_shipping_code . '' . $free_shipping_on, $free_sign_up, $free_sign_up_produt_date, $draftInvoice, $purchasedMembershipPlanName, $discounted_product_collection_percentageoff . ' ' . $discount_on, $store), $body);
                    } else {
                        $body = $email_template_html;
                        $body = str_replace(array("{{customer_name}}", "{{customer_email}}", "{{coupon_code}}", "{{free_shipping_code}}", "{{free_signup_product}}", "{{free_sign_up_produt_date}}", "{{immediate_sign_up_produt}}", "{{percentage_discount}}% off", "{{plan_name}}", "{{store_name}}"), array($customerName, $customerEmail, $coupon_code, $free_shipping_code . '' . $free_shipping_on, $free_sign_up, $free_sign_up_produt_date, $draftInvoice, $discounted_product_collection_percentageoff . ' ' . $discount_on, $purchasedMembershipPlanName, $store), $body);
                    }
                    if ($customer_email_confirm == 1) {
                        emailSend($store, $body, $subject, $customerEmail, $ccc_email, $bcc_email, $reply_to, $db,  $store_id);
                    }
                    if ($admin_email_confirm == 1) {
                        emailSend($store, $body, $subject, $owner_email, $ccc_email, $bcc_email, $reply_to, $db,  $store_id);
                    }
                } else {
                    echo "<br><b>draft Order Error</b>";
                }
            } else {
                if ($custom_email_html != null || !empty($custom_email_html)) {
                    $body = $custom_email_html;
                    $body = str_replace(array("{{customer_name}}", "{{customer_email}}", "{{coupon_code}}", "{{free_shipping_code}}", "{{free_signup_product}}", "{{free_sign_up_produt_date}}", "{{percentage_discount}}% off", "{{plan_name}}", "{{store_name}}"), array($customerName, $customerEmail, $coupon_code, $free_shipping_code . '' . $free_shipping_on, $free_sign_up, $free_sign_up_produt_date, $discounted_product_collection_percentageoff . ' ' . $discount_on, $purchasedMembershipPlanName, $store), $body);
                    echo '<b><i>Successfully custom template email.......' . $customerEmail . '</i></i></b>';
                } else {
                    $body = $email_template_html;
                    $body = str_replace(array("{{customer_name}}", "{{customer_email}}", "{{coupon_code}}", "{{free_shipping_code}}", "{{free_signup_product}}", "{{free_sign_up_produt_date}}", "{{percentage_discount}}% off", "{{plan_name}}", "{{store_name}}"), array($customerName, $customerEmail, $coupon_code, $free_shipping_code . '' . $free_shipping_on, $free_sign_up, $free_sign_up_produt_date, $discounted_product_collection_percentageoff . ' ' . $discount_on, $purchasedMembershipPlanName, $store), $body);
                    echo '<b><i>Successfully Default Template email.......' . $customerEmail . '</i></b>';
                }

                if ($customer_email_confirm == 1) {
                    // echo "teeja";  
                    emailSend($store, $body, $subject, $customerEmail, $ccc_email, $bcc_email, $reply_to, $db,  $store_id);
                }
                if ($admin_email_confirm == 1) {
                    // echo "chautha";
                    emailSend($store, $body, $subject, $owner_email, $ccc_email, $bcc_email, $reply_to, $db,  $store_id);
                }
            }
        }
    } catch (Exception $e) {
        echo '<b>Email not send.</b><br><pre>' . $e . '</pre>';
    }
}


function membershipAllEmailTemplates($template_data, $db, $SHOPIFY_DOMAIN_URL)
{

    $store_name = $template_data['shop'];
    $store = $template_data['shop_name'];
    $template_name = $template_data['template_name'];

    $free_shipping_condition = $template_data['free_shipping_coupon_content'] ?? '';

    $discount_coupon_condition = $template_data['discount_coupon_content'] ?? '';

    $free_signup_product_condition = $template_data['free_signup_product_content'] ?? '';

    $free_gift_uponsignupSelectedDays = $template_data['free_gift_uponsignupSelectedDays'] ?? ' ';

    $query = "SELECT * FROM $template_name WHERE store = '$store' LIMIT 1";
    $result = $db->query($query);
    $template_setting = $result->fetch(PDO::FETCH_OBJ);

    $custom_email_html = $template_setting->custom_email_html ?? '';


    if (empty((array) $template_setting)) {

        $ccc_email = '';

        $bcc_email = '';

        $reply_to = '';

        $content_heading = '';

        $logo = $SHOPIFY_DOMAIN_URL . '/application/assets/images/emai-logo.png';

        $customer_name = 'Honey Bailey';

        $heading_text_color = '#000000';

        $header_bg_color = '#50E1B0';

        $footer_bg_color = '#50E1B0';

        $logo_height = '38';

        $logo_width = '170';

        $logo_alignment = 'Left';

        $manage_button_url = 'https://' . $store_name . '/account';

        $manage_btn_text_color = '#000000';

        $manage_btn_bg_color = '#50E1B0';

        $custom_email_html = null;

        $content_background = '#FFFFFF;';

        $email_background_color = '#F3F3F3';

        $button_content_bg_color = '#FFFFFF';

        $logo_content_bg_color = '#FFFFFF';

        $logo_bg_color = '#FFFFFF';

        $tick_bg_color = '#50E1B0';

        $gift_link_text = 'Redeem now';



        switch ($template_name) {

            case "new_purchase_plans":

                $subject = 'New Membership';

                $heading_text = 'Congratulations !';

                $footer_text = 'Thank you';

                $manage_button = 'View Account';

                $feature_heading = 'Exclusive Benefits Await You!';



                $discount_coupon_content = '<h2 style="text-align:inherit"><b>Exclusive Discounts</b></h2><div>Apply <b>{{coupon_code}}</b> coupon code during checkout to avail an exciting <b>{{percentage_discount}}% off</b> discount on your total purchase. Treat yourself or your loved ones to our premium products while saving big.</div>';



                $free_shipping_coupon_content = '<h2 style="text-align:inherit"><b>Free Shipping Offer</b></h2><div>Use <b>{{free_shipping_code}}</b> coupon code during checkout to enjoy free shipping on your order. Now, shop with ease and have your favorite products delivered right to your doorstep without any extra shipping charges!</div>';



                $free_signup_product_content = '<h2 style="text-align:inherit"><b>Free Sign Up Product</b></h2><div>Your free sign-up product, <b> {{free_signup_product}} </b> is ready for you to enjoy. Your Free Signup Product Date is <b>{{free_sign_up_produt_date}}</b></div>';



                $free_gift_signup_product = '<h2 style="text-align:inherit"><b>Immediate Sign Up Gift</b></h2><div>This gift is one of our most popular products in the store, and we know you are going to love it!. To receive your <b> {{free_signup_product}} </b>, simply click on this link:</div>';



                $footer_content = '<p>Thank you for choosing our membership plan <b>( {{plan_name}} )</b>! We look forward to helping you succeed.<br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>We value your business and appreciate your understanding.<br><br>Sincerely,<br>{{store_name}}</p>';



                $content_heading_text = '<p>Hey <b> {{customer_name}} </b>, <br><br></p> <p> We are excited to have you on board as a new member. Your membership has started today. During that time, you will enjoy a host of exclusive benefits. <br><br> We are confident that our membership program will help you achieve your goals and reach your full potential. We are here to support you every step of the way.</p>';



                $content_heading = '<table style="width:100%; border-spacing:0; border-collapse:collapse;">

                            <tbody>

                                <tr>

                                    <td style="border-width:0; padding-top: 10px; padding-bottom: 25px;">

                                        <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">

                                            <tbody >

                                            <tr>

                                                <td><div class="sd_content_heading_text_view">' . $content_heading_text . '</div>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                        <table style="width:100%; border-spacing:0; border-collapse:collapse;">

                                <tbody>

                                    <tr>

                                        <td style="padding-top: 0; padding-bottom: 20px; border-width:0;">

                                            <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">

                                            <thead><th>';

                $content_heading .= '<h2 style="font-size: 16px; font-style: normal; font-weight: 600;" class="sd_feature_heading_view" >' . $feature_heading . '</h2>';

                $content_heading .= '</th></thead>

                                                <tbody>

                                                    <tr>

                                                        <td style="border-radius: 10px; padding: 28px 33px;">

                                                            <ul style="padding: 0; list-style: none; margin: 0;">';



                if ($discount_coupon_condition == true) {

                    $content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_discount_coupon_content_view">' . $discount_coupon_content . '</div></li>';
                }



                if ($free_shipping_condition == true) {

                    $content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_shipping_coupon_content_view">' . $free_shipping_coupon_content . '</div></li>';
                }



                if ($free_signup_product_condition == true) {

                    $content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_signup_product_content_view">' . $free_signup_product_content . '</div></li>';
                }

                if ($free_gift_uponsignupSelectedDays == 'Immediately_after_signup' || $free_gift_uponsignupSelectedDays === true) {

                    $content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_gift_signup_product_view">' . $free_gift_signup_product . '</div></li><br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
                }

                $content_heading .= '</ul>

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </td>

                                    </tr>

                                        <td>';

                $content_heading .= '<div class="sd_footer_content_view">' . $footer_content . '</div>';

                $content_heading .= '</td>

                                    <tr>

                                    </tr>

                                </tbody>

                            </table>';

                break;



            case "plan_payment_pendings":

                $subject = 'Your Plan Payment is Pending.';

                $heading_text = 'Plan Payment Pending';

                $footer_text = 'Thank You';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> I am writing to let you know that your payment for your <b> {{plan_name}} </b>is Pending. Please make your payment to avoid service interruption.<br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>We value your business and appreciate your understanding.<br><br>Sincerely,<br>{{store_name}}</p>';

                break;

            case "credit_card_expirings":

                $subject = 'Credit Card Expiring Soon';

                $heading_text = 'Credit Card Expiring';

                $footer_text = 'Thank You';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> Your credit card for your <b> {{plan_name}} </b>subscription is expiring soon. Please update your payment information to avoid service interruption.You can update your payment information by logging into your account.<br><br>Thank you for your continued business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

                break;

            case "plan_payment_faileds":

                $subject = 'Your Plan Payment is Failed';

                $heading_text = 'Payment failed';

                $footer_text = 'Thank you';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We noticed that your payment for your {{plan_name}} subscription failed. Please update your payment information or make a payment to avoid service interruption.<br><br>You can update your payment information or make a payment by logging into your account<br><br>if you are having trouble making a payment, please contact our customer support team for assistance.<br><br>Thank you for your attention to this matter.<br><br>Sincerely,<br><br>{{store_name}}</p>';

                break;



            case "membership_free_gifts":

                $subject = 'You get the free gift"';

                $heading_text = 'Hurray! you get the free gift';

                $footer_text = 'Thank you';

                $manage_button = 'View Account';

                $gift_link_text = 'Redeem now';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We are so excited to have you as a member ! As a thank-you for signing up, we are giving you a free gift. Your gift is a <b> {{free_signup_product}} </b>. We think you will love it!. We hope you enjoy your gift. We are always adding new products and services, so be sure to check back often.<br><br> To claim your gift, simply click on the link below:</p>';

                $content_heading .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';

                break;



            case "plan_payment_declineds":

                $subject = 'Your Payment has been declined';

                $heading_text = 'Transaction Declined';

                $footer_text = 'Thank you';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We noticed that your payment for your {{plan_name}} subscription was declined. Please update your payment information or make a payment to avoid service interruption.<br><br>You can update your payment information or make a payment by logging into your account.If you are having trouble making a payment, please contact our customer support team for assistance.<br><br>We understand that there may be various reasons for a declined payment, so please do not hesitate to reach out to us if you have any feedback or concerns. We value your patronage and hope to continue serving you in the future.<br><br>Sincerely,<br><br>{{store_name}}</p>';

                break;

            case "membership_skip_memberships":

                $subject = 'Your membership plan has been skipped';

                $heading_text = 'Membership plan skipped';

                $footer_text = 'Thank you';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hello <b>{{customer_name}}</b>, <br><br>We wanted to inform you that your<b> {{plan_name}} </b>subscription on upcoming date<b> {{skip_date}} </b>has been skipped.<br><br>You can easily see details by logging into your account. if you encounter any challenges or require assistance, our dedicated customer support team is available to help you.<br><br>We understand that situations arise, leading to plan skips. If you have any feedback or concerns, we encourage you to reach out. Your satisfaction is of the utmost importance to us, and we look forward to resolving any issues promptly.<br><br>Best regards,<br><br>{{store_name}}</p>';


                break;

            case "membership_cancelleds":

                $subject = 'Membership {{plan_status}}';

                $heading_text = 'Membership {{plan_status}}';

                $footer_text = 'Stay in touch';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We would like to inform you that your subscription for {{plan_name}} has been <b>{{plan_status}}</b>. We understand that this decision may have been made for various reasons, and we appreciate the time you spent with our service. If you have any feedback or concerns regarding your experience or if you would like to explore alternative subscription options, please do not hesitate to reach out to our customer support team. We value your patronage and hope to assist you with any future needs or questions you may have.<br><br>Thank you for your business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

                break;

            case "membership_upgrades":

                $subject = 'Your Plan has been upgraded';

                $heading_text = 'Plan upgraded';

                $footer_text = 'Thank You';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> Congratulations on upgrading to {{plan_name}} ! We are excited to have you as a member of our premium subscription plan. With {{plan_name}}, you will get access to all of our features and benefits, and We are confident that you will love your new subscription plan. <br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team. We are here to help you make the most of your new subscription.<br><br>Thank you for choosing {{store_name}}!<br><br> Sincerely, <br><br>{{store_name}} </p>';

                break;

            case "membership_downgrades":

                $subject = 'Your Plan has been downgraded';

                $heading_text = 'Plan Downgraded';

                $footer_text = 'Thank You';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We understand that your needs may change, and we are here to support you. We have downgraded your subscription to <b> {{plan_name}}</b> at your request. <br><br>If you have any feedback or concerns about your experience with us, please do not hesitate to reach out to our customer support team. We are always looking for ways to improve, and your feedback is valuable to us.<br><br>We hope that you will continue to use our service in the future. If you decide that you need more features or benefits, you can always <b><i>upgrade</i></b> your subscription at any time.<br><br>Thank you for your business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

                break;

            case "membership_renews":

                $subject = 'Your Plan has been Renewed';

                $heading_text = 'Plan Renew';

                $footer_text = 'Thank You';

                $manage_button = 'View Account';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We are excited to let you know that your subscription to  <b> {{plan_name}} <i> has been renewed! </i></b>. <br><br>We appreciate your continued business and look forward to serving you for another year. If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>Thank you for choosing {{store_name}}!<br><br>Sincerely,<br><br>{{store_name}}</p>';

                break;
        }
    } else {

        $content_heading = '';

        $subject = $template_setting->subject ?? '';

        $ccc_email = $template_setting->ccc_email ?? '';

        $bcc_email = $template_setting->bcc_email ?? '';

        $reply_to = $template_setting->reply_to ?? '';

        $custom_email_html = $template_setting->custom_email_html ?? '';

        $logo_height = $template_setting->logo_height ?? '';

        $logo_width = $template_setting->logo_width ?? '';

        $logo_alignment = $template_setting->logo_alignment ?? '';

        $logo = $template_setting->logo ?? '';

        $heading_text = $template_setting->heading_text ?? '';

        $heading_text_color = $template_setting->heading_text_color ?? '';

        $email_background_color = $template_setting->email_background_color ?? '';

        $content_background = $template_setting->content_background ?? '';

        $content_heading = $template_setting->content_heading ?? '';

        $manage_button = $template_setting->manage_button ?? '';

        $manage_button_url = $template_setting->manage_button_url ?? '';

        $discount_coupon_content = $template_setting->discount_coupon_content ?? '';

        $free_shipping_coupon_content = $template_setting->free_shipping_coupon_content ?? '';

        $free_signup_product_content = $template_setting->free_signup_product_content ?? '';

        $manage_btn_text_color = $template_setting->manage_btn_text_color ?? '';

        $manage_btn_bg_color = $template_setting->manage_btn_bg_color ?? '';

        $footer_text = $template_setting->footer_text ?? '';

        $footer_bg_color = $template_setting->footer_bg_color ?? '';

        $header_bg_color = $template_setting->header_bg_color ?? '';

        $button_content_bg_color = $template_setting->button_content_bg_color ?? '';

        $logo_content_bg_color = $template_setting->logo_content_bg_color ?? '';

        $content_heading_text = $template_setting->content_heading_text ?? '';

        $feature_heading = $template_setting->feature_heading ?? '';

        $footer_content = $template_setting->footer_content ?? '';

        $free_gift_signup_product = $template_setting->free_gift_signup_product ?? '';

        $gift_link_text = $template_setting->gift_link_text ?? 'Reedem now';

        $logo_bg_color = $template_setting->logo_bg_color ?? '#ffffff';

        $tick_bg_color = $template_setting->tick_bg_color ?? '#50E1B0';



        if ($template_name == 'new_purchase_plans') {

            $content_heading = '<table style="width:100%; border-spacing:0; border-collapse:collapse;">

                            <tbody>

                                <tr>

                                    <td style="border-width:0; padding-top: 10px; padding-bottom: 25px;">

                                        <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">

                                            <tbody >

                                            <tr>

                                                <td><div class="sd_content_heading_text_view">' . $content_heading_text . '</div>

                                                </td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                        <table style="width:100%; border-spacing:0; border-collapse:collapse;">

                                <tbody>

                                    <tr>

                                        <td style="padding-top: 0; padding-bottom: 20px; border-width:0;">

                                            <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">

                                            <thead><th>';

            $content_heading .= '<h2 style="font-size: 16px; font-style: normal; font-weight: 600;" class="sd_feature_heading_view" >' . $feature_heading . '</h2>';

            $content_heading .= '</th></thead>

                                                <tbody>

                                                    <tr>

                                                        <td style="border-radius: 10px; padding: 28px 33px;">

                                                            <ul style="padding: 0; list-style: none; margin: 0;">';



            if ($discount_coupon_condition == true) {

                $content_heading .= '<li style="display:flex; font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_discount_coupon_content_view">' . $discount_coupon_content . '</div></li>';
            }



            if ($free_shipping_condition == true) {

                $content_heading .= '<li style="display:flex;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_shipping_coupon_content_view">' . $free_shipping_coupon_content . '</div></li>';
            }



            if ($free_signup_product_condition == true) {

                $content_heading .= '<li style="display:flex;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_signup_product_content_view">' . $free_signup_product_content . '</div></li>';
            }

            if ($free_gift_uponsignupSelectedDays == 'Immediately_after_signup' || $free_gift_uponsignupSelectedDays === true) {

                $content_heading .= '<li style="display:flex;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_gift_signup_product_view">' . $free_gift_signup_product . '</div></li><br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
            }

            $content_heading .= '</ul>

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </td>

                                    </tr>

                                        <td>';

            $content_heading .= '<div class="sd_footer_content_view">' . $footer_content . '</div>';

            $content_heading .= '</td>

                                    <tr>

                                    </tr>

                                </tbody>

                            </table>';
        }

        // if ($template_name == 'membership_free_gifts') {
        //         $content_heading .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
        // }

    }

    if ($manage_button_url == '') {

        $manage_button_url = 'https://' . $store_name . '/account';
    }

    if ($logo == '') {

        $logo = $SHOPIFY_DOMAIN_URL . '/application/assets/images/emai-logo.png';
    }

    $email_template_html = '<html><head><style>

    @import url("https://fonts.googleapis.com/css2 family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap");

    * {

        font-family: Public Sans, sans-serif;

    }

    h1, h2, h3, h4, h5, h6, p {

        margin: 0;

    }

    @media print {

        * {

        -webkit-print-color-adjust: exact;

        }

        html {

        background: none;

        padding: 0;

        }

        body {

        box-shadow: none;

        margin: 0;

        }

        span:empty {

        display: none;

        }

        .add, .cut {

        display: none;

        }

    }

    @page {

        margin: 0;

    }

    .w-40 {

        width: 40px;

        min-width: 40px;

    }

    .icon-with-text p {

        line-height: 24px;

        font-size: 15px;

    }

    @media (max-width: 430px) {

        .preorder-img {

        margin-top: 0 !important;

        }

        .take_your_shopify_text {

        line-height: 1.3 !important;

        }

        .take_your_shopify_text br {

        display: none;

        }

        .h-25 {

        font-size: 24px !important;

        line-height: normal;

        }

        .h-18 br {

        display: none;

        font-size: 18px !important;

        }

        #services_column {

        width: 100% !important;

        }

        .services_column_main td {

        width: 100% !important;

        }

        .services_column_main {

        display: flex;

        flex-direction: column;

        }

        .icon-with-text {

        margin: 10px;

        }

        .header_social_icon a {

        margin-right: 5px !important;

        }

    }

    .icon-with-text img {

        height: 41px;

    }

    </style>

    </head>

        <body class="sd_email_background_view" style="background:#EBE8E5;">

            <div class="main-template" style="max-width: 600px; margin: auto;">

                <div class="main-temp-inner sd_logo_content_bg_color" style=" background-color: ' . $logo_bg_color . '">

                    <div class="header-temp" style="border-radius: 5px; padding: 14px 20px;">

                        <table class="sd_logo_content_bg_color" style="width: 100%; border-spacing: 0; border-collapse: collapse; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">

                            <tbody>

                                <tr>

                                    <td>

                                        <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">

                                            <tbody>

                                                <tr>



                                                    <td>

                                                    <div class="sd_logo_align" style="text-align:' . $logo_alignment . '">

                                                    <img class="sd_logo_view" border="0" style="color:#000000; text-decoration:none;font-size:16px;"  height="' . $logo_height . 'px;" width="' . $logo_width . 'px;" alt="" data-proportionally-constrained="true" data-responsive="true" src="' . $logo . '">

                                                    </div>

                                                    </td>



                                                </tr>

                                            </tbody>

                                        </table>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">

                    <tbody>

                        <tr>

                            <td class="sd_header_bg_color" style=" background-color: ' . $header_bg_color . '; border-width: 0;">

                                <center>

                                    <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">

                                        <tbody>

                                            <tr>

                                                <td>

                                                    <div style="color: rgb(0, 0, 0) !important;">

                                                        <h1 class="sd_heading_view" style="padding: 20px;  font-size: 23px; font-weight: 600; color:' . $heading_text_color . '">' . $heading_text . '</h1>

                                                    </div>

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </center>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <table class="sd_content_text_view" style="width: 100%; border-spacing: 0; border-collapse: collapse; background-color: ' . $content_background . '">

                    <tbody>

                        <tr>

                            <td style="border-width: 0; padding: 20px;">

                                <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">

                                    <tbody>

                                        <tr>

                                            <td>

                                                <div class="sd_content_heading_view">' . $content_heading . '</div>';



    if ($template_name == 'membership_free_gifts') {

        $email_template_html .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
    }



    $email_template_html .= '</td>

                                        </tr>

                                    </tbody>

                                </table>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <table class="sd_button_content_bg_color" style="width: 100%; border-spacing: 0; border-collapse: collapse; background-color: ' . $button_content_bg_color . '">

                    <tbody>

                        <tr>

                            <td style="padding-top: 20px; padding-bottom: 20px; border-width: 0;">

                                <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">

                                    <tbody>

                                        <tr align="center">

                                            <td>

                                                <a href=' . $manage_button_url . ' class="sd_manage_button_view" style="text-decoration: none; position: relative; z-index: 1; border-radius: 4px; background: ' . $manage_btn_bg_color . '; color: ' . $manage_btn_text_color . '; font-size: 14px; font-weight: 500; display: inline-block; padding: 15px 11px; text-transform: uppercase;">' . $manage_button . '</a>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <table  class="sd_footer_bg_color" style="width: 100%; border-spacing: 0; border-collapse: collapse; background-color: ' . $footer_bg_color . ';">

                    <tbody>

                        <tr>

                            <td align="center" style="padding-top: 5px; border-width: 0; padding-bottom:5px;">

                                <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">

                                    <tbody>

                                        <tr>

                                            <td align="center">

                                                <p class="sd_footer_text_view" style="padding: 10px 0; color: #000; text-align: center; font-size: 15px; font-weight: 400;">' . $footer_text . '</p>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </body>

    </html>';



    // Create and return an array with template data
    $return_template_array = [

        'email_template_html' => $email_template_html ?? '',

        'subject' => $subject ?? '',

        'ccc_email' => $ccc_email ?? '',

        'bcc_email' => $bcc_email ?? '',

        'reply_to' => $reply_to ?? '',

        'logo_height' => $logo_height ?? '',

        'logo_width' => $logo_width ?? '',

        'logo_alignment' => $logo_alignment ?? '',

        'logo' => $logo ?? '',

        'heading_text' => $heading_text ?? '',

        'heading_text_color' => $heading_text_color ?? '',

        'manage_button' => $manage_button ?? '',

        'manage_button_url' => $manage_button_url ?? '',

        'manage_btn_text_color' => $manage_btn_text_color ?? '',

        'manage_btn_bg_color' => $manage_btn_bg_color ?? '',

        'footer_text' => $footer_text ?? '',

        'custom_email_html' => $custom_email_html ?? '',

        'content_background' => $content_background ?? '',

        'email_background_color' => $email_background_color ?? '',

        'template_settings' => $template_setting ?? '',

        'content_heading' => $content_heading ?? '',

        'footer_content' => $footer_content ?? '',

        'tick_bg_color' => $tick_bg_color ?? '',

        'discount_coupon_content' => $discount_coupon_content ?? '',

        'free_shipping_coupon_content' => $free_shipping_coupon_content ?? '',

        'free_signup_product_content' => $free_signup_product_content ?? '',

        'content_heading_text' => $content_heading_text ?? '',

        'feature_heading' => $feature_heading ?? '',

        'header_bg_color' => $header_bg_color ?? '',

        'free_gift_signup_product' => $free_gift_signup_product ?? '',

        'gift_link_text' => $gift_link_text ?? '',

        'logo_bg_color' => $logo_bg_color ?? '',

        'button_content_bg_color' => $button_content_bg_color ?? '',

    ];

    // return $email_template_html;
    return $return_template_array;
}



function emailSend($store, $body, $subject, $customerEmail, $ccc_email, $bcc_email, $reply_to, $db, $store_id)
{
    try {
        $store_id =  $store_id;
        $query = "
            SELECT store_details.*, install.access_token
            FROM store_details
            INNER JOIN install ON store_details.store_id = install.id
            WHERE store_details. store_id = '$store_id'
            LIMIT 1
        ";

        $result = $db->query($query);
        $storeDetails = $result->fetch(PDO::FETCH_OBJ);

        $storeName = $storeDetails->shop_name;
        $mail =  new PHPMailer\PHPMailer\PHPMailer();

        $result = $db->query("SELECT * FROM email_configuration WHERE store_id = '$store_id' LIMIT 1");
        $emailConfiguration = $result->fetch(PDO::FETCH_OBJ);


        if (count((array)$emailConfiguration) > 0) {
            $checkCondtion = $emailConfiguration->email_enable;
        } else {
            $checkCondtion = 'false';
        }

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        if ($checkCondtion == 'checked') {
            $emailHost = $emailConfiguration->email_host;
            $emailUsername = $emailConfiguration->username;
            $emailPassword = $emailConfiguration->password;
            $emailEncriptionType = $emailConfiguration->encryption;
            $emailPortNumber = $emailConfiguration->port_number;
            $senderName = $emailConfiguration->from_email;
        } else {

            $emailHost =  "smtp.gmail.com";
            $emailUsername = "shopify@phoenixtechnologies.io";
            $emailPassword = "gqumwotkgxekdowf";
            $emailEncriptionType = 'tls';
            $emailPortNumber = 587;
            $username = "shopify@phoenixtechnologies.io";
            $senderName = $username;
        }
        $mail->Host = $emailHost;
        $mail->Username = $emailUsername;
        $mail->Password = $emailPassword;
        $mail->SMTPSecure = $emailEncriptionType;
        $mail->Port = $emailPortNumber;

        if ($checkCondtion == 'false') {
            $mail->setFrom($username, $senderName);
        } else {
            $mail->setFrom($emailUsername, $senderName);
        }

        $mail->addAddress($customerEmail);

        // Adding CC recipients
        if (!empty($ccc_email)) {
            if (is_array($ccc_email)) {
                foreach ($ccc_email as $cc_email) {
                    $mail->addCC(trim($cc_email));
                }
            } else {
                $cc_emails = explode(',', $ccc_email);
                foreach ($cc_emails as $cc_email) {
                    $mail->addCC(trim($cc_email));
                }
            }
        }

        // Adding BCC recipients
        if (!empty($bcc_email)) {
            if (is_array($bcc_email)) {
                foreach ($bcc_email as $bcc_email) {
                    $mail->addBCC(trim($bcc_email));
                }
            } else {
                $bcc_emails = explode(',', $bcc_email);
                foreach ($bcc_emails as $bcc_email) {
                    $mail->addBCC(trim($bcc_email));
                }
            }
        }

        if (!empty($reply_to)) {
            $mail->addReplyTo($reply_to);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->Send()) {
            echo 'mail not sent';
        } else {
            echo 'mail sent successfully';
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}
