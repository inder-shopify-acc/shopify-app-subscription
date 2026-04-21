<?php
echo phpinfo();
die;
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);

use PHPShopify\ShopifySDK;

require_once __DIR__ . "/graphLoad/autoload.php";

require_once __DIR__ .  "/application/library/config.php";

$store = 'renbe-uk.myshopify.com';

$store_install_query = $db->query("Select access_token, store_id FROM install LEFT JOIN store_details ON install.id = store_details.store_id WHERE install.store = '$store'");

$store_install_data = $store_install_query->fetch(PDO::FETCH_ASSOC);

$access_token = $store_install_data['access_token'];

$store_id = $store_install_data['store_id'];

$config = array(

  'ShopUrl' => $store,

  'AccessToken' => $access_token

);

$shopifies = new ShopifySDK($config);



  // $get_order_number = '{

  //   order(id: "gid://shopify/Order/4907373789367"){

  //   name

  //     subtotalPriceSet{

  //         shopMoney{

  //           currencyCode

  //           amount

  //         }

  //         presentmentMoney{

  //           amount

  //           currencyCode

  //         }

  //     }

  //   }

  // }';

  // $contract_detail = $shopifies->GraphQL->post($get_order_number);

  // echo '<pre>';

  // print_r($contract_detail);

  // die;

// $currency_symbols = [

//   'USD' => '$',

//   'AED' => 'د.إ',

//   'AFN' => '؋',

//   'ALL' => 'L',

//   'AMD' => '֏',

//   'ANG' => 'ƒ',

//   'AOA' => 'Kz',

//   'ARS' => '$',

//   'AUD' => '$',

//   'AWG' => 'ƒ',

//   'AZN' => '₼',

//   'BAM' => 'KM',

//   'BBD' => '$',

//   'BDT' => '৳',

//   'BGN' => 'лв',

//   'BHD' => '.د.ب',

//   'BIF' => 'FBu',

//   'BMD' => '$',

//   'BND' => '$',

//   'BOB' => 'Bs.',

//   'BRL' => 'R$',

//   'BSD' => '$',

//   'BTN' => 'Nu.',

//   'BWP' => 'P',

//   'BYN' => 'Br',

//   'BZD' => '$',

//   'CAD' => '$',

//   'CDF' => 'FC',

//   'CHF' => 'CHF',

//   'CLP' => '$',

//   'CNY' => '¥',

//   'COP' => '$',

//   'CRC' => '₡',

//   'CUC' => '$',

//   'CUP' => '$',

//   'CVE' => '$',

//   'CZK' => 'Kč',

//   'DJF' => 'Fdj',

// ];

// $all_shopify_currencies = [ 'AED' => 'د.إ','AFN' => '؋','ALL' => 'L','AMD' => '֏','ANG' => 'ƒ','AOA' => 'Kz','ARS' => '$','AUD' => '$','AWG' => 'ƒ','AZN' => '₼','BAM' => 'KM','BBD' => '$','BDT' => '৳','BGN' => 'лв','BHD' => '.د.ب','BIF' => 'FBu','BMD' => '$','BND' => '$','BOB' => 'Bs.','BRL' => 'R$','BSD' => '$','BWP' => 'P','BZD' => '$',

// 'CAD' => '$','CDF' => 'FC','CHF' => 'CHF','CLP' => '$','CNY' => '¥','COP' => '$','CRC' => '₡','CVE' => '$','CZK' => 'Kč','DJF' => 'Fdj','DKK' => 'kr','DOP' => '$','DZD' => 'د.ج','EGP' => 'E£','ERN' => 'Nfk','ETB' => 'Br','EUR' => '€','FJD' => '$','FKP' => '£','GBP' => '£','GEL' => '₾','GHS' => '₵','GIP' => '£','GMD' => 'D','GNF' => 'FG',

// 'GTQ' => 'Q','GYD' => '$','HKD' => '$','HNL' => 'L','HRK' => 'kn','HTG' => 'G','HUF' => 'Ft','IDR' => 'Rp','ILS' => '₪','INR' => '₹','ISK' => 'kr','JMD' => '$','JOD' => 'د.ا','JPY' => '¥','KES' => 'KSh','KGS' => 'лв','KHR' => '៛','KMF' => 'CF','KRW' => '₩','KWD' => 'د.ك','KYD' => '$','KZT' => '₸','LAK' => '₭','LBP' => 'L£'];

// $array_diff_check = array_diff_key($all_shopify_currencies,$currency_symbols);

// $array_diff_check2 = array_diff_key($currency_symbols,$all_shopify_currencies);

// echo '<pre>';

// print_r($array_diff_check);

// echo '<pre>';

// print_r($array_diff_check2);



$getSubscriptionContractDraftId = '

{

  subscriptionDraft(id) {

    # SubscriptionDraft fields

  }

}

';







$get_contract_lines = '{

  subscriptionContract(id :"gid://shopify/SubscriptionContract/14938079514") {

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



  // file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n ".$contract_id." Grphql limit. ". $contract_detail['extensions']['cost']['throttleStatus']['currentlyAvailable'], FILE_APPEND | LOCK_EX);

}catch (Exception $e) {

  echo '<pre>';

  print_r($e->getMessage());

  // file_put_contents($dirPath . "/application/assets/txt/webhooks/subscription_contracts_create.txt", "\n\n Grphql failed. ".$contract_id." ". $e->getMessage(), FILE_APPEND | LOCK_EX);

}



   // update total_sale table

  // $check_entry_contract_sale = $db->prepare("SELECT id FROM contract_sale WHERE  store_id = '12' and contract_id = '4241850606'");

  // $check_entry_contract_sale->execute();

  // $contract_sale_entry_count = $check_entry_contract_sale->rowCount();



  //  if(!$contract_sale_entry_count) {

  //   echo '1'; die;

  //      $insert_contract_sale = $db->prepare("INSERT INTO contract_sale (store_id, contract_id, total_sale,contract_currency) VALUES ('$store_id', '$subscription_contract_id', '$order_total','$order_currency')");

  //      $insert_contract_sale->execute();

  //  }else{

  //   echo '11'; die;

  //     $update_contract_sale = $db->query("UPDATE contract_sale SET total_sale = total_sale+46 WHERE store_id = '12' and contract_id = '4241850606'");

  //  }



?>

<!-- <script>

  $.getScript("https://cdn.shopify.com/s/javascripts/currencies.js?store=subscription-store-one.myshopify.com", function(){

    return;

  });

  var Currency = {

    /* … rates … */

    convert: function(amount, from, to) {

        return (amount * this.rates[from]) / this.rates[to];

    }

  }

</script>

<?php

//echo "<script type='text/javascript'>console.log(Currency.convert(100,USD,EUR));</script>";

?> -->