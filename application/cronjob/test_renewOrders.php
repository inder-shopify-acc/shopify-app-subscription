<?php
// error_reporting(0);
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);



use PHPShopify\ShopifySDK;

$dirPath = dirname(dirname(__DIR__));

include $dirPath."/graphLoad/autoload.php";

include $dirPath . "/application/library/config.php";

$current_date = gmdate("Y-m-d H:i:s");

echo $SERVER_ADDR = $_SERVER['SERVER_ADDR'];

echo $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

file_put_contents($dirPath . "/application/assets/txt/webhooks/renew_cronjob_hit.txt", "\n\n SERVER_ADDR = ".$SERVER_ADDR." and REMOTE_ADDR =".$REMOTE_ADDR." cron job hit time ".$current_date, FILE_APPEND | LOCK_EX);

$currentDate = strtok(gmdate("Y-m-d H:i:s"), " ");

die;

file_put_contents($dirPath . "/application/assets/txt/webhooks/renew_cronjob_hit.txt", "\n\n cron job hit time ".$current_date, FILE_APPEND | LOCK_EX);

$currentDate = strtok(gmdate("Y-m-d H:i:s"), " ");

     // load graphql

     $config = array(

        'ShopUrl' => 'predictive-search.myshopify.com',

        'AccessToken' => 'YOUR_ACCESS_TOKEN' //'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'

    );

    $shopifies = new ShopifySDK($config);

    $current_date = gmdate("Y-m-d H:i:s");

    $currentDate = strtok(gmdate("Y-m-d H:i:s"), " ");

    include $dirPath . "/graphLoad/autoload.php";

    $after_cycle_update = '0';

    $row_data_query = $db->query("SELECT s.store,e.after_cycle_update,e.after_cycle,e.recurring_discount_value,e.contract_id,e.store_id,e.next_billing_date,s.access_token from subscriptionOrderContract e, install s, billingAttempts as b where e.store_id = 112 AND e.next_billing_date = '$currentDate' AND  e.contract_status = 'A' AND contract_inprocess = 'no' AND e.next_billing_date NOT IN (SELECT b.renewal_date FROM billingAttempts b WHERE e.contract_id = b.contract_id AND b.status = 'Skip' AND b.renewal_date = '$currentDate') GROUP BY e.contract_id limit 0,10");

    $row_count = $row_data_query->rowCount();

    //get contract active products

    if ($row_count > 0) {

        $row_data = $row_data_query->fetchAll(PDO::FETCH_ASSOC);

        echo '<pre>';

        print_r($row_data);

        $contracts_in_process = (implode(",",(array_column($row_data,'contract_id'))));

        $update_contract_process_status = $db->query("UPDATE subscriptionOrderContract SET contract_inprocess = 'yes' where contract_id IN ($contracts_in_process)");

    }

    die;

    $get_contract_lines = '{

        subscriptionContract(id :"gid://shopify/SubscriptionContract/6990200968") {

            originOrder{

                name

                shippingLine{

                    code

                    originalPriceSet{

                        shopMoney{

                            amount

                            currencyCode

                        }

                    }

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

    billingPolicy{

      interval

      intervalCount

    }

    deliveryPolicy{

      interval

      intervalCount

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

            lines(first:100){

                edges{

                    node{

                        id

                        quantity

                        sellingPlanId

                        productId

                        variantId

                        variantTitle

                        title

                        quantity

                        variantImage{

                            src

                        }

                        lineDiscountedPrice{

                            amount

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

        echo '<pre>';

        print_r($contract_detail);



    }catch (Exception $e) {

        print_r($e->getMessage());

    }

$db = null;

