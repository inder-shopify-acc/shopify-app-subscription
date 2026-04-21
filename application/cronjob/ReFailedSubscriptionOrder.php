<?php

// error_reporting(0);
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);


// error_reporting(E_ALL);

use Mpdf\Tag\Pre;
use PHPShopify\ShopifySDK;

$dirPath = dirname(dirname(__DIR__));

include $dirPath . "/application/library/config.php";

require_once(dirname((dirname(dirname(__file__)))) . "/application/controller/Mainfunction.php");

$current_date = gmdate("Y-m-d H:i:s"); // UTC time

$SERVER_ADDR = $_SERVER['SERVER_ADDR'];

$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];


$currentDate = strtok(gmdate("Y-m-d H:i:s"), " ");




include $dirPath . "/graphLoad/autoload.php";

$after_cycle_update = '0';

$row_data_query = $db->query(
    "SELECT s.store,
        s.access_token, 
        b.refailed_status_count,
        b.store_id,
        c.email, 
        cs.failed_status_attempt,
        cs.afterBillingAttemptFail, 
        cs.day_before_retrying,
        e.contract_id,
        store_details.store_email,
        e.delivery_billing_type,
        e.delivery_policy_value,
        b.billingAttemptResponseDate,
        b.billingAttemptId
    FROM contract_setting cs
    JOIN install s ON cs.store_id = s.id
    JOIN subscriptionOrderContract e ON cs.store_id = e.store_id
    JOIN billingAttempts b ON e.contract_id = b.contract_id
    JOIN customers c ON cs.store_id = c.store_id
    JOIN store_details ON cs.store_id = store_details.store_id
    WHERE b.status = 'Failure' 
    AND DATE_ADD(b.billing_attempt_date, INTERVAL cs.day_before_retrying DAY) <= '$currentDate'
    AND e.contract_status = 'A'
   
    LIMIT 2;
    "
);


$row_count = $row_data_query->rowCount();

//get contract active products
if ($row_count > 0) {
    $row_data = $row_data_query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($row_data as $rowVal) {

        $store_id = $rowVal['store_id'];
        $store_name = $rowVal['store'];
        $store_access_token = $rowVal['access_token'];
        $contract_id = $rowVal['contract_id'];
        $refailed_status_counts = $rowVal['refailed_status_count'];
        $after_billing_bttemptFail_status = $rowVal['afterBillingAttemptFail'];
        $customer_email = $rowVal['email'];
        $admin_email = $rowVal['store_email'];
        $failed_status_attempt_limit = $rowVal['failed_status_attempt'];
        $refailed_status_count_current = $refailed_status_counts + 1;
        $intervalCount = $rowVal['delivery_policy_value'];
        $billingType = $rowVal['delivery_billing_type'];
        $skipOrderDate = $rowVal['billingAttemptResponseDate'];
        $billingAttempt_id = $rowVal['billingAttemptId'];
        $newobj = new Mainfunction($store_name);
        $config = array(
    
            'ShopUrl' => $rowVal['store'],
            'AccessToken' => $rowVal['access_token']
    
        );
    
        $shopifies = new ShopifySDK($config);
        if ($refailed_status_counts == $failed_status_attempt_limit) {
      
            if ($after_billing_bttemptFail_status == 'Pause') {
                $statusChangeTo = 'PAUSED';
            } elseif ($after_billing_bttemptFail_status == 'Cancel') {
                $statusChangeTo = 'CANCELLED';
            }else {
                $statusChangeTo = 'SKIPPED';
            }
            $adminEmail = $admin_email;
            $customerEmail = $customer_email;
            $store_name_val = $store_name;
            $store_access_token_val = $store_access_token;
            $contract_details = [];
            $contract_product_details = [];

            $order_contract_status = ($after_billing_bttemptFail_status == 'Cancel') ? 'C' : (($after_billing_bttemptFail_status == 'Pause') ? 'P' : 'A');

            try{
                $get_customer_payment_method = '{
                    subscriptionContract(id: "gid://shopify/SubscriptionContract/'.trim($contract_id).'"){
                    customerPaymentMethod{
                        id
                        ... on CustomerPaymentMethod{
                        id
                        instrument{
                            __typename
                            ... on CustomerCreditCard{
                            brand
                            expiryYear
                            expiryMonth
                            lastDigits
                            }
                            ... on CustomerShopPayAgreement{
                                expiryMonth
                                expiryYear
                                isRevocable
                                maskedNumber
                                lastDigits
                                name
                                inactive
                            }
                            ... on CustomerPaypalBillingAgreement{
                                billingAddress{
                                countryCode
                                country
                                province
                                }
                                paypalAccountEmail
                            }
                        }
                        }
                    }
                    }
                }';

                $contractMethodToken = $shopifies->GraphQL->post($get_customer_payment_method);
    
                
                $customerPaymentMethod = $contractMethodToken['data']['subscriptionContract']['customerPaymentMethod'];
                $paymentMethodToken =  substr($customerPaymentMethod['id'], strrpos($customerPaymentMethod['id'], '/') + 1);

                $getContractData_qry = $db->query("SELECT p.payment_instrument_value,p.payment_method_token,o.after_cycle,o.order_currency,o.after_cycle_update,o.min_cycle,o.discount_type,o.discount_value,o.recurring_discount_type,o.recurring_discount_value,o.next_billing_date,o.order_no,o.order_id,o.created_at,o.updated_at,o.contract_status,o.contract_products,o.delivery_policy_value,o.billing_policy_value,o.delivery_billing_type,a.first_name as shipping_first_name,a.last_name as shipping_last_name,a.address1 as shipping_address1,a.address2 as shipping_address2,a.city as shipping_city,a.province as shipping_province,a.country as shipping_country,a.company as shipping_company,a.phone as shipping_phone,a.province_code as shipping_province_code,a.country_code as shipping_country_code,a.zip as shipping_zip,a.delivery_method as shipping_delivery_method,a.delivery_price as shipping_delivery_price,b.first_name as billing_first_name,b.last_name as billing_last_name,b.address1 as billing_address1,b.address2 as billing_address2,b.city as billing_city,b.province as billing_province,b.country as billing_country,b.company as billing_company,b.phone as billing_phone,b.province_code as billing_province_code,b.country_code as billing_country_code,b.zip as billing_zip,d.store_email,d.shop_timezone,c.name,c.email,c.shopify_customer_id,cs.cancel_subscription,cs.edit_product_price,cs.skip_upcoming_order,cs.skip_upcoming_fulfillment,cs.pause_resume_subscription,cs.add_subscription_product,cs.add_out_of_stock_product,cs.edit_product_quantity,cs.edit_out_of_stock_product_quantity,cs.attempt_billing,cs.delete_product,cs.edit_shipping_address from subscriptionOrderContract as o
                INNER JOIN contract_setting as contract_settng ON o.store_id = contract_settng.store_id
                INNER JOIN subscriptionContractShippingAddress as a ON o.contract_id = a.contract_id
                INNER JOIN subscriptionContractBillingAddress as b ON o.contract_id = b.contract_id
                INNER JOIN customers as c ON c.shopify_customer_id = o.shopify_customer_id
                INNER JOIN store_details as d ON d.store_id = a.store_id
                INNER JOIN customerContractPaymentmethod AS p ON p.store_id = o.store_id
                INNER JOIN customer_settings as cs ON cs.store_id = o.store_id
                where o.contract_id = '$contract_id' and p.payment_method_token = '$paymentMethodToken'");

                $contract_details = $getContractData_qry->fetchAll(PDO::FETCH_ASSOC);

                $getContractProductData_qry = $db->query("SELECT * FROM subscritionOrderContractProductDetails
                where contract_id = '$contract_id' and store_id = '$store_id'");
                $contract_product_details = $getContractProductData_qry->fetchAll(PDO::FETCH_ASSOC);
               
            }catch (Exception $e) {

                // echo '<pre>';
                // print_r($e->getMessage());
                // die;
        
            }
            $response = $newobj->updateSubscriptionStatusFailed($contract_id, $statusChangeTo, $adminEmail,  $customerEmail, $store_name_val, $store_access_token_val,$contract_details[0],$contract_product_details, $skipOrderDate);
            
            $response_message = json_decode($response, true);
           
            if ($after_billing_bttemptFail_status != 'Skip') {
                
                $updatableOrderContract = "UPDATE subscriptionOrderContract SET updated_at = '$current_date', contract_status = '$order_contract_status' where contract_id = '$contract_id'";
            } else {
                $next_billing_date = date('Y-m-d', strtotime('+'.$intervalCount.' '.$billingType, strtotime($skipOrderDate)));
                // echo $next_billing_date;
                $updatableOrderContract = "UPDATE subscriptionOrderContract SET updated_at = '$current_date', next_billing_date = '$next_billing_date', contract_status = '$order_contract_status' where contract_id = '$contract_id'";
                
                $insertbillingattempt = "UPDATE billingAttempts SET status = 'Skip', updated_at = '$current_date' WHERE contract_id = '$contract_id' AND billingAttemptId = '$billingAttempt_id' AND status = 'Failure' AND store_id = '$store_id'";

                $queryResult = $db->query($insertbillingattempt);
            }
            // update data subscriptionOrderContract
            if ($response_message['status'] == true) {
                $queryResult = $db->query($updatableOrderContract);
                // print_r($response_message);
            }
        }else {
            
    
            //get contract products
            $get_contract_products_query = $db->query("SELECT variant_id,contract_line_item_id,recurring_computed_price FROM subscritionOrderContractProductDetails WHERE contract_id = '$contract_id' AND product_contract_status = '1'");
            $contract_products_array = $get_contract_products_query->fetchAll(PDO::FETCH_ASSOC);
        
            $contract_products = implode(',', array_column($contract_products_array, 'variant_id'));
        
            $idempotencyKey = uniqid();
            $currentDates = new DateTime();
            $formattedDate = $currentDates->format('Y-m-d\TH:i:s\Z');
            try{
                $graphQL_billingAttemptCreate = 'mutation {
                    subscriptionBillingAttemptCreate(
                        subscriptionContractId: "gid://shopify/SubscriptionContract/'.$contract_id.'"
                        subscriptionBillingAttemptInput: {idempotencyKey: "'.$idempotencyKey.'", originTime: "'.$formattedDate.'" }
                    ) {
                        subscriptionBillingAttempt  {
                        id
                        subscriptionContract
                        {
                            nextBillingDate
                            billingPolicy{
                            interval
                                intervalCount
                                maxCycles
                                minCycles
                                anchors{
                                day
                                type
                                month
                                }
    
                        }
                        deliveryPolicy{
                            interval
                                intervalCount
                                anchors{
                                day
                                type
                                month
                                }
                        }
                    }
                    }
                    userErrors {
                        field
                        message
                    }
                    }
                }';
                $billingAttemptCreateApi_execution = $shopifies->GraphQL->post($graphQL_billingAttemptCreate);
           
        
                $billingAttemptCreateApi_error = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['userErrors'];
                if (!count($billingAttemptCreateApi_error)) {
        
                    $billingAttemptId = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['id'];   
                    $billingAttempt_id = substr($billingAttemptId, strrpos($billingAttemptId, '/') + 1);
                    $refailed_status_counts = '0';

                    $insertbillingattempt = "UPDATE billingAttempts 
                            SET store_id = '$store_id', 
                                contract_products = '$contract_products', 
                                billingAttemptId = '$billingAttempt_id', 
                                billing_attempt_date = '$currentDate', 
                                status = 'Success', 
                                refailed_status_count = '$refailed_status_counts', 
                                renewal_date = '$currentDate', 
                                billingAttemptResponseDate = '$currentDate', 
                                updated_at = '$current_date' 
                            WHERE contract_id = '$contract_id' 
                            AND status = 'Failure' 
                            AND store_id = '$store_id'";
        
        
                    $queryResult = $db->query($insertbillingattempt);
        
        
                    // if value is active in general setting table
                    $intervalCount = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['subscriptionContract']['billingPolicy']['intervalCount'];
        
                    $billingType = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['subscriptionContract']['billingPolicy']['interval'];
        
                    if ($billingType == 'DAY') {
        
                        $TotalIntervalCount =  $intervalCount;
                    } else if ($billingType == 'WEEK') {
        
                        $TotalIntervalCount = (7 *  $intervalCount);
                    } else if ($billingType == 'MONTH') {
        
                        $TotalIntervalCount = (30 *  $intervalCount);
                    } else if ($billingType == 'YEAR') {
        
                        $TotalIntervalCount = (365 *  $intervalCount);
                    }
        
                    $newRenewalDate = date('Y-m-d', strtotime('+' . $TotalIntervalCount . ' day', strtotime($currentDate)));
        
                    $updatable = "UPDATE subscriptionOrderContract SET updated_at = '$current_date', next_billing_date = '$newRenewalDate' WHERE contract_id = '$contract_id'";

        
                    $queryResult = $db->query($updatable);
        
                    // echo ('Subscription payment successfully');
                } else {
            
                }
            }catch (Exception $e) {
                // print_r($e->getMessage());

                // set contract status in process no
        
                // file_put_contents($dirPath . "/application/assets/txt/webhooks/renew_cronjob_hit.txt", "\n\n cron job catch error contract id = " . $contract_id . " and " . $e->getMessage(), FILE_APPEND | LOCK_EX);
            }
        }
    
    }
}else{
    // echo ('Not dataFound');
}


$db = null;
