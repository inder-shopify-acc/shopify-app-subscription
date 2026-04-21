<?php

include("header.php");

// echo "<script> const AjaxCallFrom = 'backendAjaxCall';</script>";

$current_date = gmdate("Y-m-d");  //get utc current date

$dt = new DateTime($current_date);

$tz = new DateTimeZone($shop_timezone); // or whatever zone you're after

$dt->setTimezone($tz);

$shop_current_date = $dt->format('Y-m-d');

$last_date = $shop_current_date;

//GET STORE DETAILS DATA

// $where_condition = array(

//   'store_id' => $mainobj->store_id

// );

// $fields = array('currency','currencyCode');

// $store_details_data = $mainobj->single_row_value('store_details',$fields,$where_condition,'and','');



$store_details_data_qry = $db->query("SELECT currency,currencyCode FROM `store_details` WHERE store_id = '$store_id'");

$store_details_data = $store_details_data_qry->fetch(PDO::FETCH_ASSOC);





$currency = $store_details_data['currency'];

$currencyCode = $store_details_data['currencyCode'];

$start_date = date('Y-m-d', strtotime('-7 days', strtotime($last_date)));



if (isset($_GET['start_date']) && isset($_GET['last_date'])) {

  $start_date = $_GET['start_date'] ?? '';

  $last_date =  $_GET['last_date'] ?? '';

  if ($start_date != '' && $last_date == '') {

    $last_date = date('Y-m-d', strtotime('+7 days', strtotime($start_date)));
  } else if ($start_date == '' && $last_date != '') {

    $start_date = date('Y-m-d', strtotime('-7 days', strtotime($last_date)));
  } else if ($start_date == '' && $last_date == '') {

    $last_date = $current_date;

    $start_date = date('Y-m-d', strtotime('-7 days', strtotime($last_date)));
  }
}

$query_last_date =  date('Y-m-d', strtotime('+1 days', strtotime($last_date)));



//MAY NEED TO BE CHANGE IN THE FUTUTRE DUE TO THE LARGE DB HITS

$get_shop_analytics_data_qry = $db->query("SELECT subscriptionPlanGroups.store_id, (SELECT COUNT(*) FROM subscriptionPlanGroups as g WHERE g.store_id='$store_id' ) as total_subscription_group,(SELECT COUNT(*) FROM subscriptionOrderContract as s WHERE s.contract_status = 'A' and s.store_id='$store_id' AND s.plan_type = 'subscription') as total_active_subscription_contract,(SELECT COUNT(*) FROM subscriptionOrderContract as s WHERE s.contract_status = 'P' and s.store_id='$store_id' and s.plan_type = 'subscription' ) as total_pause_subscription_contract FROM subscriptionPlanGroups, subscriptionOrderContract where subscriptionPlanGroups.store_id = '$store_id' AND subscriptionOrderContract.plan_type = 'subscription'  GROUP BY subscriptionPlanGroups.store_id");

$get_shop_analytics_data = $get_shop_analytics_data_qry->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT COUNT(*) FROM subscriptionOrderContract WHERE store_id = ? AND contract_status = 'A' AND plan_type = 'subscription'");
$stmt->execute([$store_id]);
$activePlansA = $stmt->fetchColumn();

// Active Plans - Status P
$stmt = $db->prepare("SELECT COUNT(*) FROM subscriptionOrderContract WHERE store_id = ? AND contract_status = 'P' AND plan_type = 'subscription'");
$stmt->execute([$store_id]);
$activePlansP = $stmt->fetchColumn();

// Active Plans - Status C
$stmt = $db->prepare("SELECT COUNT(*) FROM subscriptionOrderContract WHERE store_id = ? AND contract_status = 'C' AND plan_type = 'subscription'");
$stmt->execute([$store_id]);
$activePlansC = $stmt->fetchColumn();


$selling_plan_count_data_qry = $db->query("SELECT selling_plan_id, COUNT(*) AS total_orders FROM subscriptionOrderContract  where subscriptionOrderContract.store_id = '$store_id' AND subscriptionOrderContract.plan_type = 'subscription' GROUP BY selling_plan_id  order by COUNT(*) desc, id desc limit 7");

$selling_plan_count_data = $selling_plan_count_data_qry->fetchAll(PDO::FETCH_ASSOC);



if (!empty($selling_plan_count_data)) {

  $selling_plan_id_array = array_column($selling_plan_count_data, 'selling_plan_id');

  $selling_plan_id_array = array_filter($selling_plan_id_array, fn($value) => !is_null($value) && $value !== '');

  $selling_plan_id = implode(',', $selling_plan_id_array);

  $get_selling_and_group_name_qry = $db->query("SELECT subscriptionPlanGroupsDetails.plan_name,subscriptionPlanGroupsDetails.selling_plan_id, subscriptionPlanGroups.plan_name as selling_plan_group_name FROM subscriptionPlanGroupsDetails INNER JOIN subscriptionPlanGroups ON subscriptionPlanGroups.subscription_plangroup_id = subscriptionPlanGroupsDetails.subscription_plan_group_id WHERE subscriptionPlanGroupsDetails.selling_plan_id IN ($selling_plan_id)");

  $get_selling_and_group_name = $get_selling_and_group_name_qry->fetchAll(PDO::FETCH_ASSOC);
}

$date_range_sale_from_billingAttempts_qry = $db->query("SELECT BA.created_at, BA.order_total,O.order_currency,O.contract_id FROM billingAttempts AS BA, subscriptionOrderContract as O where BA.store_id = '$store_id' and BA.status = 'Success' and BA.contract_id = O.contract_id AND BA.plan_type = 'subscription' AND O.plan_type = 'subscription'  and BA.created_at BETWEEN '$start_date' AND '$query_last_date'");

$date_range_sale_from_billingAttempts = $date_range_sale_from_billingAttempts_qry->fetchAll(PDO::FETCH_ASSOC);

$total_date_price_array = $date_range_sale_from_billingAttempts;



//total subscription orders

$get_total_subscription_orders_qry = $db->query("SELECT COUNT(distinct(order_no)) as total_contract_orders from subscriptionOrderContract where  store_id = '$store_id' AND plan_type = 'subscription'");

$get_total_subscription_orders = $get_total_subscription_orders_qry->fetch(PDO::FETCH_ASSOC);



$get_billing_attempt_orders_qry = $db->query("SELECT COUNT(distinct(order_no)) as total_billing_attempt_orders from billingAttempts where  store_id = '$store_id' AND plan_type = 'subscription'");

$get_billing_attempt_orders = $get_billing_attempt_orders_qry->fetch(PDO::FETCH_ASSOC);



$total_orders = $get_total_subscription_orders['total_contract_orders'] + $get_billing_attempt_orders['total_billing_attempt_orders'];



//total order sale from contract_sale table

$fields = array('total_sale', 'contract_currency', 'contract_id');

// $get_billing_attempt_orders_sale = $mainobj->table_row_value('contract_sale',$fields,$where_condition,'and','');

$get_billing_attempt_orders_sale_qry = $db->query("SELECT total_sale,contract_currency,contract_id FROM `contract_sale` WHERE store_id='$store_id'");

$get_billing_attempt_orders_sale = $get_billing_attempt_orders_sale_qry->fetchAll(PDO::FETCH_ASSOC);


$stmt = $db->prepare("SELECT * FROM contract_sale WHERE store_id = ?");
$stmt->execute([$store_id]);
$membership_orders_sale = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="Polaris-Layout">

  <?php

  include("navigation.php");

  ?>

  <style>
    #sd_subscription_graph ul li .highcharts-a11y-proxy-button {

      pointer-events: none;

    }
  </style>
<input type="hidden" value='<?php echo json_encode($membership_orders_sale); ?>' id="membership_details_array" data-attr="subscription">
  <!-- <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> -->

  <script src="https://code.highcharts.com/highcharts.js"></script>

  <script src="https://code.highcharts.com/modules/exporting.js"></script>

  <script src="https://code.highcharts.com/modules/export-data.js"></script>

  <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

  <div class="Polaris-Layout__Section sd-dashboard-page">

    <input type="hidden" value="<?php echo $shop_current_date; ?>" id="shop_current_date">

    <div>

      <div class="Polaris-Page-Header Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">

        <div class="Polaris-Page-Header__Row">

          <div class="Polaris-Page-Header__TitleWrapper">

            <div>

              <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">

                <h1 class="Polaris-DisplayText Polaris-DisplayText--sizeLarge">Analytics</h1>

              </div>

            </div>

          </div>

        </div>

      </div>

      <div class="Polaris-Page__Content">

        <div class="sd_susbscriptionAnalytics">

          <div class="Polaris-FormLayout">

            <div role="group" class="Polaris-FormLayout--grouped">

              <div class="Polaris-FormLayout__Items">

                <div class="Polaris-FormLayout__Item sd_productPageSetting">

                  <div class="Polaris-CardNew">

                    <div class="sd_settingPage">

                      <div class="Polaris-Card__Section sd_analytic_badge Polaris-Stack__Item Polaris-Stack__Item--fill ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                          <circle cx="28" cy="28" r="28" fill="#1B254B" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M31.2586 13.7216C31.5649 13.8719 31.8128 14.1193 31.9639 14.4252C32.115 14.7311 32.1607 15.0783 32.0939 15.4129L30.4027 23.8753H38.828C39.1245 23.8753 39.4148 23.9605 39.6643 24.1208C39.9137 24.281 40.1119 24.5096 40.2351 24.7793C40.3583 25.049 40.4014 25.3485 40.3593 25.642C40.3172 25.9355 40.1916 26.2107 39.9974 26.4349L26.5912 41.9036C26.3675 42.1613 26.065 42.3378 25.7305 42.4057C25.3961 42.4736 25.0487 42.4289 24.7423 42.2788C24.4359 42.1286 24.1877 41.8814 24.0364 41.5755C23.8851 41.2697 23.8392 40.9224 23.9058 40.5877L25.5971 32.1253H17.1718C16.8752 32.1253 16.585 32.0401 16.3355 31.8798C16.086 31.7196 15.8879 31.491 15.7647 31.2213C15.6414 30.9516 15.5983 30.6521 15.6405 30.3586C15.6826 30.0651 15.8082 29.7899 16.0023 29.5657L29.4086 14.097C29.6323 13.839 29.9351 13.6623 30.2697 13.5944C30.6044 13.5265 30.9521 13.5712 31.2586 13.7216Z" fill="#6AD2FF" />
                        </svg>
                        <div class="sd_analytic_badge_text">
                          <h2 class="Polaris-Heading">Revenue from memberships & subscriptions</h2>
                          <span class="Polaris-TextStyle--variationSubdued" id="sd_total_sales_sub"></span>
                        </div>
                      </div>

                    </div>

                  </div>

                </div>

                <div class="Polaris-FormLayout__Item sd_productPageSetting">

                  <div class="Polaris-CardNew">

                    <div class="sd_settingPage">

                      <div class="Polaris-Card__Section sd_analytic_badge Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                          <rect width="56" height="56" rx="28" fill="#1B254B" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8438 26.875V29.125C24.8438 29.7963 25.1104 30.4402 25.5851 30.9149C26.0598 31.3896 26.7037 31.6563 27.375 31.6563H29.625C30.2963 31.6563 30.9402 31.3896 31.4149 30.9149C31.8896 30.4402 32.1563 29.7963 32.1563 29.125V26.875C32.1563 26.2037 31.8896 25.5598 31.4149 25.0851C30.9402 24.6104 30.2963 24.3438 29.625 24.3438H27.375C26.7037 24.3438 26.0598 24.6104 25.5851 25.0851C25.1104 25.5598 24.8438 26.2037 24.8438 26.875ZM16.4062 18.4375V20.6875C16.4062 21.3588 16.6729 22.0027 17.1476 22.4774C17.6223 22.9521 18.2662 23.2188 18.9375 23.2188H21.1875C21.8588 23.2188 22.5027 22.9521 22.9774 22.4774C23.4521 22.0027 23.7188 21.3588 23.7188 20.6875V18.4375C23.7188 17.7662 23.4521 17.1223 22.9774 16.6476C22.5027 16.1729 21.8588 15.9062 21.1875 15.9062H18.9375C18.2662 15.9062 17.6223 16.1729 17.1476 16.6476C16.6729 17.1223 16.4062 17.7662 16.4062 18.4375ZM33.2813 35.3125V37.5625C33.2813 38.2338 33.5479 38.8777 34.0226 39.3524C34.4973 39.8271 35.1412 40.0938 35.8125 40.0938H38.0625C38.7338 40.0938 39.3777 39.8271 39.8524 39.3524C40.3271 38.8777 40.5938 38.2338 40.5938 37.5625V35.3125C40.5938 34.6412 40.3271 33.9973 39.8524 33.5226C39.3777 33.0479 38.7338 32.7813 38.0625 32.7813H35.8125C35.1412 32.7813 34.4973 33.0479 34.0226 33.5226C33.5479 33.9973 33.2813 34.6412 33.2813 35.3125Z" fill="#05CD99" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M22.875 20.4062H37.5C37.8728 20.4068 38.2301 20.5552 38.4937 20.8188C38.7573 21.0824 38.9057 21.4397 38.9063 21.8125V25.75C38.9057 26.1228 38.7573 26.4801 38.4937 26.7437C38.2301 27.0073 37.8728 27.1557 37.5 27.1562H34.125C33.9012 27.1562 33.6866 27.2451 33.5284 27.4034C33.3701 27.5616 33.2813 27.7762 33.2813 28C33.2813 28.2238 33.3701 28.4384 33.5284 28.5966C33.6866 28.7549 33.9012 28.8438 34.125 28.8438H37.5C38.3201 28.8438 39.1076 28.5175 39.6881 27.9381C40.2677 27.3574 40.5934 26.5705 40.5938 25.75V21.8125C40.5938 20.9924 40.2675 20.2049 39.6881 19.6244C39.1074 19.0448 38.3205 18.7191 37.5 18.7188H22.875C22.6512 18.7188 22.4366 18.8076 22.2784 18.9659C22.1201 19.1241 22.0313 19.3387 22.0313 19.5625C22.0313 19.7863 22.1201 20.0009 22.2784 20.1591C22.4366 20.3174 22.6512 20.4062 22.875 20.4062ZM29.625 35.5938H19.5C19.1272 35.5932 18.7699 35.4448 18.5063 35.1812C18.2427 34.9176 18.0943 34.5603 18.0938 34.1875V30.25C18.0943 29.8772 18.2427 29.5199 18.5063 29.2563C18.7699 28.9927 19.1272 28.8443 19.5 28.8438H25.6875C25.9113 28.8438 26.1259 28.7549 26.2841 28.5966C26.4424 28.4384 26.5313 28.2238 26.5313 28C26.5313 27.7762 26.4424 27.5616 26.2841 27.4034C26.1259 27.2451 25.9113 27.1562 25.6875 27.1562H19.5C18.6799 27.1562 17.8924 27.4825 17.3119 28.0619C16.7323 28.6426 16.4066 29.4295 16.4062 30.25V34.1875C16.4062 35.0076 16.7325 35.7951 17.3119 36.3756C17.8926 36.9552 18.6795 37.2809 19.5 37.2812H29.625C29.8488 37.2812 30.0634 37.1924 30.2216 37.0341C30.3799 36.8759 30.4688 36.6613 30.4688 36.4375C30.4688 36.2137 30.3799 35.9991 30.2216 35.8409C30.0634 35.6826 29.8488 35.5938 29.625 35.5938Z" fill="#05CD99" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M36.9712 29.6542L35.3186 28.0005L36.9712 26.3467C37.0495 26.2684 37.1116 26.1754 37.154 26.0731C37.1964 25.9708 37.2182 25.8612 37.2182 25.7505C37.2182 25.6397 37.1964 25.5301 37.154 25.4278C37.1116 25.3255 37.0495 25.2325 36.9712 25.1542C36.8929 25.0759 36.8 25.0138 36.6977 24.9714C36.5953 24.929 36.4857 24.9072 36.375 24.9072C36.2642 24.9072 36.1546 24.929 36.0523 24.9714C35.95 25.0138 35.857 25.0759 35.7787 25.1542L33.5287 27.4042C33.4503 27.4825 33.3882 27.5754 33.3457 27.6777C33.3033 27.78 33.2815 27.8897 33.2815 28.0005C33.2815 28.1112 33.3033 28.2209 33.3457 28.3232C33.3882 28.4255 33.4503 28.5184 33.5287 28.5967L35.7787 30.8467C35.857 30.925 35.95 30.9871 36.0523 31.0295C36.1546 31.0719 36.2642 31.0937 36.375 31.0937C36.4857 31.0937 36.5953 31.0719 36.6977 31.0295C36.8 30.9871 36.8929 30.925 36.9712 30.8467C37.0495 30.7684 37.1116 30.6754 37.154 30.5731C37.1964 30.4708 37.2182 30.3612 37.2182 30.2505C37.2182 30.1397 37.1964 30.0301 37.154 29.9278C37.1116 29.8255 37.0495 29.7325 36.9712 29.6542ZM29.0962 39.2842L31.3462 37.0342C31.4246 36.9559 31.4868 36.863 31.5292 36.7607C31.5716 36.6584 31.5934 36.5487 31.5934 36.438C31.5934 36.3272 31.5716 36.2175 31.5292 36.1152C31.4868 36.0129 31.4246 35.92 31.3462 35.8417L29.0962 33.5917C28.9381 33.4336 28.7236 33.3447 28.5 33.3447C28.2763 33.3447 28.0618 33.4336 27.9037 33.5917C27.7456 33.7498 27.6567 33.9643 27.6567 34.188C27.6567 34.4116 27.7456 34.6261 27.9037 34.7842L29.5563 36.438L27.9037 38.0917C27.8254 38.17 27.7633 38.263 27.7209 38.3653C27.6785 38.4676 27.6567 38.5772 27.6567 38.688C27.6567 38.7987 27.6785 38.9083 27.7209 39.0106C27.7633 39.1129 27.8254 39.2059 27.9037 39.2842C27.982 39.3625 28.075 39.4246 28.1773 39.467C28.2796 39.5094 28.3892 39.5312 28.5 39.5312C28.6107 39.5312 28.7203 39.5094 28.8227 39.467C28.925 39.4246 29.0179 39.3625 29.0962 39.2842Z" fill="#05CD99" />
                        </svg>
                        <a href="<?php echo $SHOPIFY_DOMAIN_URL; ?>/admin/plans/subscription_group.php?shop=<?php echo $store; ?>" target="_blank">
                          <div class="sd_analytic_badge_text">

                            <h2 class="Polaris-Heading">Total Plans</h2>
                            <span class="Polaris-TextStyle--variationSubdued"><?php if (!empty($get_shop_analytics_data['total_subscription_group'])) {
                                                                                echo $get_shop_analytics_data['total_subscription_group'];
                                                                              } else {
                                                                                echo '0';
                                                                              } ?></span>

                          </div>
                        </a>
                      </div>

                    </div>

                  </div>

                </div>

                <div class="Polaris-FormLayout__Item sd_productPageSetting">

                  <div class="Polaris-CardNew">



                    <div class="sd_settingPage">


                      <div class="Polaris-Card__Section sd_analytic_badge Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                          <rect width="56" height="56" rx="28" fill="#1B254B" />
                          <g clip-path="url(#clip0_112622_318548)">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M26.6718 16.0322C25.0994 16.0322 23.5914 16.6569 22.4795 17.7687C21.3677 18.8806 20.743 20.3886 20.743 21.961C20.743 23.5334 21.3677 25.0414 22.4795 26.1533C23.5914 27.2652 25.0994 27.8898 26.6718 27.8898C28.2442 27.8898 29.7522 27.2652 30.8641 26.1533C31.976 25.0414 32.6006 23.5334 32.6006 21.961C32.6006 20.3886 31.976 18.8806 30.8641 17.7687C29.7522 16.6569 28.2442 16.0322 26.6718 16.0322ZM26.6718 29.0756C23.8319 29.0756 21.247 29.8985 19.3462 31.0582C18.3976 31.6368 17.5865 32.3198 17.0008 33.0621C16.4245 33.7901 16 34.664 16 35.5972C16 36.5992 16.4873 37.3889 17.1893 37.9521C17.8533 38.4857 18.7296 38.8391 19.6604 39.0857C21.5316 39.5802 24.0288 39.7474 26.6718 39.7474C26.9453 39.7474 27.2161 39.7454 27.4841 39.7415C27.6827 39.7387 27.8774 39.6862 28.0504 39.5886C28.2233 39.491 28.369 39.3515 28.4741 39.183C28.5791 39.0144 28.6402 38.8222 28.6516 38.6239C28.663 38.4256 28.6244 38.2277 28.5394 38.0482C28.0898 37.0965 27.8569 36.0569 27.8576 35.0044C27.8576 33.5198 28.3117 32.1443 29.0872 31.0048C29.2025 30.8355 29.2719 30.6391 29.2887 30.435C29.3056 30.2308 29.2692 30.0258 29.1832 29.8399C29.0973 29.6539 28.9646 29.4935 28.7981 29.3741C28.6317 29.2547 28.4371 29.1804 28.2335 29.1586C27.7228 29.1032 27.2022 29.0756 26.6718 29.0756ZM35.9966 30.2578C35.8922 30.0786 35.7427 29.93 35.5629 29.8267C35.3832 29.7234 35.1795 29.669 34.9721 29.669C34.7648 29.669 34.5611 29.7234 34.3813 29.8267C34.2016 29.93 34.052 30.0786 33.9476 30.2578L32.8686 32.1099L30.7745 32.5629C30.5718 32.6067 30.3841 32.703 30.2302 32.8421C30.0763 32.9812 29.9616 33.1582 29.8975 33.3555C29.8334 33.5528 29.8222 33.7635 29.865 33.9664C29.9078 34.1694 30.0031 34.3576 30.1413 34.5123L31.569 36.1107L31.3532 38.2427C31.3323 38.449 31.3658 38.6572 31.4505 38.8465C31.5352 39.0357 31.6681 39.1995 31.8358 39.3214C32.0036 39.4433 32.2003 39.5191 32.4065 39.5412C32.6127 39.5633 32.8211 39.531 33.0109 39.4474L34.9721 38.5818L36.9334 39.4474C37.1231 39.531 37.3315 39.5633 37.5377 39.5412C37.7439 39.5191 37.9407 39.4433 38.1084 39.3214C38.2762 39.1995 38.409 39.0357 38.4937 38.8465C38.5784 38.6572 38.612 38.449 38.5911 38.2427L38.3753 36.1095L39.8029 34.5123C39.9412 34.3576 40.0365 34.1694 40.0793 33.9664C40.1221 33.7635 40.1109 33.5528 40.0468 33.3555C39.9827 33.1582 39.868 32.9812 39.7141 32.8421C39.5602 32.703 39.3725 32.6067 39.1697 32.5629L37.0757 32.1099L35.9966 30.2578Z" fill="#F90182" />
                          </g>
                          <defs>
                            <clipPath id="clip0_112622_318548">
                              <rect width="26.1048" height="25.779" fill="white" transform="translate(15 15)" />
                            </clipPath>
                          </defs>
                        </svg>
                        <a href="<?php echo $SHOPIFY_DOMAIN_URL; ?>/admin/subscription/subscriptions.php?shop=<?php echo $store; ?>&search_contract=active" target="_blank">
                          <div class="sd_analytic_badge_text">


                            <h2 class="Polaris-Heading pollaris_AnalyticsCardSection p-0">Total Active Subscriptions</h2>

                            <span class="Polaris-TextStyle--variationSubdued"><?php echo $activePlansA ?? "0" ;?></span>



                          </div>
                        </a>
                      </div>

                    </div>

                  </div>

                </div>

                <div class="Polaris-FormLayout__Item sd_productPageSetting">

                  <div class="Polaris-CardNew">

                    <div class="sd_settingPage">

                      <div class="Polaris-Card__Section sd_analytic_badge Polaris-Stack__Item Polaris-Stack__Item--fill">

                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                          <circle cx="28" cy="28" r="28" fill="#1B254B" />
                          <path d="M34.102 20.597C34.1672 20.9956 34.5119 21.2788 34.9031 21.2788C34.9466 21.2788 34.9908 21.2753 35.0351 21.2681C35.4781 21.1957 35.7785 20.778 35.7061 20.335C35.3425 18.1089 34.2446 16.0229 32.6147 14.4613C32.2906 14.1508 31.7761 14.1617 31.4657 14.4859C31.1552 14.8099 31.1662 15.3244 31.4903 15.6349C32.8673 16.9543 33.7949 18.7166 34.102 20.597ZM19.2267 15.6349C19.5508 15.3244 19.5618 14.8099 19.2513 14.4859C18.9407 14.1617 18.4263 14.1507 18.1022 14.4613C16.4722 16.0229 15.3743 18.109 15.0107 20.335C14.9384 20.778 15.2388 21.1957 15.6818 21.2681C15.7262 21.2753 15.7703 21.2788 15.8138 21.2788C16.205 21.2788 16.5498 20.9956 16.6149 20.597C16.922 18.7166 17.8496 16.9543 19.2267 15.6349ZM27.7799 28.3369H22.937C23.0767 29.5495 24.1091 30.4943 25.3585 30.4943C26.6079 30.4943 27.6402 29.5495 27.7799 28.3369ZM28.966 24.6939C28.966 22.7512 30.5466 21.1707 32.4893 21.1707C32.5066 21.1707 32.524 21.171 32.5414 21.1713C32.2138 18.4333 30.354 16.112 27.7383 15.2052C27.6453 13.9736 26.6134 13 25.3584 13C24.1035 13 23.0716 13.9736 22.9786 15.2051C20.0905 16.2063 18.1238 18.9321 18.1238 22.0391V24.5542C18.1238 24.8475 17.8852 25.0861 17.5919 25.0861C17.143 25.0861 16.7792 25.45 16.7792 25.8988C16.7792 26.3476 17.143 26.7115 17.5919 26.7115H28.966V24.6939Z" fill="#6AD2FF" />
                          <path d="M38.8692 30.8576C38.4265 30.3038 37.8041 29.904 37.1157 29.7317L36.2237 29.5069L34.3875 29.0442V24.7294C34.3875 23.693 33.5714 22.8209 32.5353 22.7964C31.4679 22.7712 30.5917 23.6322 30.5917 24.6938L30.5918 32.6097L28.6924 32.1688C28.3927 32.0987 28.0819 32.0907 27.779 32.1452C26.7215 32.3356 25.9539 33.2718 25.9539 34.3715C25.9518 34.529 25.9272 34.7635 26.1088 34.8357L28.5949 36.0133C29.2988 36.347 29.901 36.8622 30.3396 37.506C30.8923 38.3177 31.6619 38.9587 32.5651 39.3599L32.887 39.5029V40.1519C32.887 40.4766 33.1502 40.7398 33.4749 40.7398H38.9725C39.2972 40.7398 39.5604 40.4766 39.5604 40.1519V32.8194C39.5628 32.1149 39.3173 31.4182 38.8692 30.8576Z" fill="#6AD2FF" />
                        </svg>
                        <a href="<?php echo $SHOPIFY_DOMAIN_URL; ?>/admin/subscription/subscriptions.php?shop=<?php echo $store; ?>&search_contract=pause" target="_blank">
                          <div class="sd_analytic_badge_text">


                            <h2 class="Polaris-Heading pollaris_AnalyticsCardSection p-0">Total Pause Subscriptions</h2><span class="Polaris-TextStyle--variationSubdued"><?php echo $activePlansP ?? "0"; ?></span>

                          </div>

                        </a>
                      </div>

                    </div>

                  </div>

                </div>

                <div class="Polaris-FormLayout__Item sd_productPageSetting">

                  <div class="Polaris-CardNew">

                    <div class="sd_settingPage">

                      <div class="Polaris-Card__Section sd_analytic_badge Polaris-Stack__Item Polaris-Stack__Item--fill">

                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                          <circle cx="28" cy="28" r="28" fill="#1B254B" />
                          <path d="M34.102 20.597C34.1672 20.9956 34.5119 21.2788 34.9031 21.2788C34.9466 21.2788 34.9908 21.2753 35.0351 21.2681C35.4781 21.1957 35.7785 20.778 35.7061 20.335C35.3425 18.1089 34.2446 16.0229 32.6147 14.4613C32.2906 14.1508 31.7761 14.1617 31.4657 14.4859C31.1552 14.8099 31.1662 15.3244 31.4903 15.6349C32.8673 16.9543 33.7949 18.7166 34.102 20.597ZM19.2267 15.6349C19.5508 15.3244 19.5618 14.8099 19.2513 14.4859C18.9407 14.1617 18.4263 14.1507 18.1022 14.4613C16.4722 16.0229 15.3743 18.109 15.0107 20.335C14.9384 20.778 15.2388 21.1957 15.6818 21.2681C15.7262 21.2753 15.7703 21.2788 15.8138 21.2788C16.205 21.2788 16.5498 20.9956 16.6149 20.597C16.922 18.7166 17.8496 16.9543 19.2267 15.6349ZM27.7799 28.3369H22.937C23.0767 29.5495 24.1091 30.4943 25.3585 30.4943C26.6079 30.4943 27.6402 29.5495 27.7799 28.3369ZM28.966 24.6939C28.966 22.7512 30.5466 21.1707 32.4893 21.1707C32.5066 21.1707 32.524 21.171 32.5414 21.1713C32.2138 18.4333 30.354 16.112 27.7383 15.2052C27.6453 13.9736 26.6134 13 25.3584 13C24.1035 13 23.0716 13.9736 22.9786 15.2051C20.0905 16.2063 18.1238 18.9321 18.1238 22.0391V24.5542C18.1238 24.8475 17.8852 25.0861 17.5919 25.0861C17.143 25.0861 16.7792 25.45 16.7792 25.8988C16.7792 26.3476 17.143 26.7115 17.5919 26.7115H28.966V24.6939Z" fill="#6AD2FF" />
                          <path d="M38.8692 30.8576C38.4265 30.3038 37.8041 29.904 37.1157 29.7317L36.2237 29.5069L34.3875 29.0442V24.7294C34.3875 23.693 33.5714 22.8209 32.5353 22.7964C31.4679 22.7712 30.5917 23.6322 30.5917 24.6938L30.5918 32.6097L28.6924 32.1688C28.3927 32.0987 28.0819 32.0907 27.779 32.1452C26.7215 32.3356 25.9539 33.2718 25.9539 34.3715C25.9518 34.529 25.9272 34.7635 26.1088 34.8357L28.5949 36.0133C29.2988 36.347 29.901 36.8622 30.3396 37.506C30.8923 38.3177 31.6619 38.9587 32.5651 39.3599L32.887 39.5029V40.1519C32.887 40.4766 33.1502 40.7398 33.4749 40.7398H38.9725C39.2972 40.7398 39.5604 40.4766 39.5604 40.1519V32.8194C39.5628 32.1149 39.3173 31.4182 38.8692 30.8576Z" fill="#6AD2FF" />
                        </svg>
                        <a href="<?php echo $SHOPIFY_DOMAIN_URL; ?>/admin/subscription/subscriptions.php?shop=<?php echo $store; ?>&search_contract=pause" target="_blank">
                          <div class="sd_analytic_badge_text">


                            <h2 class="Polaris-Heading pollaris_AnalyticsCardSection p-0">Total Cancelled Subscriptions</h2><span class="Polaris-TextStyle--variationSubdued"><?php echo $activePlansC ?? "0"; ?></span>

                          </div>

                        </a>
                      </div>

                    </div>

                  </div>

                </div>

                <div class="Polaris-FormLayout__Item sd_productPageSetting">

                  <div class="Polaris-CardNew">

                    <div class="sd_settingPage">
                      <div class="Polaris-Card__Section sd_analytic_badge Polaris-Stack__Item Polaris-Stack__Item--fill">

                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
                          <circle cx="28" cy="28" r="28" fill="#1B254B" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M19.1 16C19.4183 16 19.7235 16.1264 19.9485 16.3515C20.1736 16.5765 20.3 16.8818 20.3 17.2V18.4C22.3755 16.839 24.903 15.9965 27.5 16C34.1276 16 39.5 21.3724 39.5 28C39.5 34.6276 34.1276 40 27.5 40C20.8724 40 15.5 34.6276 15.5 28C15.5 27.6817 15.6264 27.3765 15.8515 27.1515C16.0765 26.9264 16.3817 26.8 16.7 26.8C17.0183 26.8 17.3235 26.9264 17.5485 27.1515C17.7736 27.3765 17.9 27.6817 17.9 28C17.9001 30.1952 18.6526 32.3241 20.032 34.0318C21.4114 35.7395 23.3344 36.9228 25.4804 37.3846C27.6265 37.8465 29.8661 37.5588 31.8258 36.5696C33.7855 35.5805 35.347 33.9495 36.2501 31.9487C37.1531 29.9478 37.3432 27.6979 36.7885 25.5739C36.2339 23.4499 34.968 21.5802 33.202 20.2763C31.436 18.9725 29.2764 18.3132 27.0833 18.4085C24.8901 18.5038 22.7959 19.3479 21.1496 20.8H23.9C24.2183 20.8 24.5235 20.9264 24.7485 21.1515C24.9736 21.3765 25.1 21.6817 25.1 22C25.1 22.3183 24.9736 22.6235 24.7485 22.8485C24.5235 23.0736 24.2183 23.2 23.9 23.2H20.3C19.6635 23.2 19.053 22.9472 18.6029 22.4971C18.1529 22.047 17.9 21.4365 17.9 20.8V17.2C17.9 16.8818 18.0264 16.5765 18.2515 16.3515C18.4765 16.1264 18.7817 16 19.1 16ZM27.5 23.2C27.8183 23.2 28.1235 23.3264 28.3485 23.5515C28.5736 23.7765 28.7 24.0817 28.7 24.4V26.8H31.1C31.4183 26.8 31.7235 26.9264 31.9485 27.1515C32.1736 27.3765 32.3 27.6817 32.3 28C32.3 28.3183 32.1736 28.6235 31.9485 28.8485C31.7235 29.0736 31.4183 29.2 31.1 29.2H28.7V31.6C28.7 31.9183 28.5736 32.2235 28.3485 32.4485C28.1235 32.6736 27.8183 32.8 27.5 32.8C27.1817 32.8 26.8765 32.6736 26.6515 32.4485C26.4264 32.2235 26.3 31.9183 26.3 31.6V29.2H23.9C23.5817 29.2 23.2765 29.0736 23.0515 28.8485C22.8264 28.6235 22.7 28.3183 22.7 28C22.7 27.6817 22.8264 27.3765 23.0515 27.1515C23.2765 26.9264 23.5817 26.8 23.9 26.8H26.3V24.4C26.3 24.0817 26.4264 23.7765 26.6515 23.5515C26.8765 23.3264 27.1817 23.2 27.5 23.2Z" fill="#05CD99" />
                        </svg>
                        <a href="https://<?php echo $store; ?>/admin/orders?inContextTimeframe=none&query=sd_recurring_subscription_order" target="_blank">
                          <div class="sd_analytic_badge_text">
                            <h2 class="Polaris-Heading pollaris_AnalyticsCardSection p-0">Total Recurring Subscriptions Orders</h2><span class="Polaris-TextStyle--variationSubdued"><?php echo $get_billing_attempt_orders['total_billing_attempt_orders']; ?></span>

                          </div>

                        </a>
                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

            <div id="PolarisPortalsContainer"></div>

          </div>

        </div>

      </div>

      <div p-color-scheme="light">

        <div class="Polaris-Layout">

          <div class="Polaris-Layout__Section">

            <div class="Polaris-Card">

              <div class="Polaris-Card__Header sd_graph_header">

                <h2 class="Polaris-Heading">Total Recurring Sale Growth</h2>

                <button title="Choose Date" class="Polaris-Button open__datePicker polarisDatePicker" id="chooseOrderDatePicker" type="button">

                  <svg viewBox="0 0 20 20" style="height: 18px;">

                    <path fill-rule="evenodd" d="M17.5 2H15V1a1 1 0 10-2 0v1H6V1a1 1 0 00-2 0v1H2.5C1.7 2 1 2.7 1 3.5v15c0 .8.7 1.5 1.5 1.5h15c.8 0 1.5-.7 1.5-1.5v-15c0-.8-.7-1.5-1.5-1.5zM3 18h14V8H3v10z" fill="#5C5F62"></path>

                  </svg>

                  <div class="date_range_inner polarisDatePicker"><?php echo date("d M Y", strtotime($start_date)) . ' - ' . date("d M Y", strtotime($last_date)); ?></div>

                </button>

              </div>

              <!-- <div class="Polaris-Card__Section"> -->

              <!-- <div class="Polaris-Layout SD_Dashboard SD_Advanced_Front_Grids">

            <div class="Polaris-Layout__Section">

               <div class="Polaris-Card">

                  <div class="Polaris-Card__Section"> -->

              <!-- date filter start here -->

              <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">

                <div class="choose_dateReport" style="display:none;">

                  <div class="Polaris-Card__Section">

                    <div class="Polaris-FormLayout">

                      <div class="Polaris-FormLayout">

                        <div class="Polaris-FormLayout__Items">

                          <div class="Polaris-FormLayout__Item">

                            <div class="Polaris-Connected">

                              <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                <div class="Polaris-TextField">

                                  <input type="text" id="startdateReport" class="Polaris-TextField__Input" value="<?php echo $start_date; ?>">

                                  <div class="Polaris-TextField__Backdrop"></div>

                                </div>

                              </div>

                            </div>

                          </div>

                          <div class="Polaris-FormLayout__Item">

                            <div class="Polaris-Connected">

                              <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                <div class="Polaris-TextField">

                                  <input autocomplete="off" type="text" name="enddateReport" id="enddateReport" class="Polaris-TextField__Input" value="<?php echo $last_date; ?>">

                                  <div class="Polaris-TextField__Backdrop"></div>

                                </div>

                              </div>

                            </div>

                          </div>

                          <div class="Polaris-FormLayout__Item">

                            <button class="Polaris-Button Polaris-Button--primary" type="button" data-type="orders" id="submitOrderdate">

                              <span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Apply</span></span>

                            </button>

                          </div>

                        </div>

                      </div>

                    </div>

                    <!-- </div>

                      </div>

                  </div> -->

                    <!-- date filter end here -->

                  </div>

                </div>

                <!-- </div>

         </div> -->

                <div id="sd_subscription_graph"></div>

              </div>

            </div>

          </div>

          <div class="Polaris-Layout__Section Polaris-Layout__Section--secondary sd_purchased_plans">

            <div class="Polaris-Card">

              <div class="Polaris-Card__Header">

                <h2 class="Polaris-Heading">7 most purchased Selling plans</h2>

              </div>

              <div class="">

                <div class="Polaris-DataTable__Navigation">

                  <button class="Polaris-Button Polaris-Button--disabled Polaris-Button--plain Polaris-Button--iconOnly" aria-label="Scroll table left one column" type="button" disabled="">

                    <span class="Polaris-Button__Content">

                      <span class="Polaris-Button__Icon">

                        <span class="Polaris-Icon">

                          <span class="Polaris-VisuallyHidden"></span>

                          <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">

                            <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16z"></path>

                          </svg>

                        </span>

                      </span>

                    </span>

                  </button>

                  <button class="Polaris-Button Polaris-Button--plain Polaris-Button--iconOnly" aria-label="Scroll table right one column" type="button">

                    <span class="Polaris-Button__Content">

                      <span class="Polaris-Button__Icon">

                        <span class="Polaris-Icon">

                          <span class="Polaris-VisuallyHidden"></span>

                          <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">

                            <path d="M8 16a.999.999 0 0 1-.707-1.707L11.586 10 7.293 5.707a.999.999 0 1 1 1.414-1.414l5 5a.999.999 0 0 1 0 1.414l-5 5A.997.997 0 0 1 8 16z"></path>

                          </svg>

                        </span>

                      </span>

                    </span>

                  </button>

                </div>

                <?php if (!empty($get_selling_and_group_name)) { ?>

                  <div class="Polaris-DataTable">

                    <div class="Polaris-DataTable__ScrollContainer">

                      <table class="Polaris-IndexTable__Table most_purchased_selling">

                        <thead>

                          <tr>

                            <th class="Polaris-IndexTable__TableHeading Polaris_TableHead" data-index-table-heading="true"><b>Plan</b></th>

                            <th class="Polaris-IndexTable__TableHeading Polaris_TableHead" data-index-table-heading="true"><b>Selling Plan</b></th>

                            <th class="Polaris-IndexTable__TableHeading Polaris-IndexTable__TableHeading--last Polaris_TableHead" data-index-table-heading="true"><b>Total Subscriptions</b></th>

                          </tr>

                        </thead>

                        <tbody>

                          <?php foreach ($get_selling_and_group_name as $key => $value) {

                            $selling_plan_id_index =  array_search($value['selling_plan_id'], $selling_plan_id_array);

                            $selling_plan_count = $selling_plan_count_data[$selling_plan_id_index]['total_orders'];

                          ?>

                            <tr class="Polaris-DataTable__TableRow Polaris-DataTable--hoverable">
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><a href="<?php echo $SHOPIFY_DOMAIN_URL; ?>/admin/plans/subscription_group.php?shop=<?php echo $store; ?>&search_subscription_group_name=<?php echo $value['selling_plan_group_name'];  ?>" target="_blank"><?php echo $value['selling_plan_group_name']; ?></td>

                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo $value['plan_name']; ?></a></td>

                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo $selling_plan_count; ?></td>

                            </tr>

                          <?php } ?>

                        </tbody>

                      </table>

                    <?php } else { ?>

                      <div class="sd_no_sellingPlan">No data available!</div>

                    <?php } ?>

                    </div>

                  </div>

              </div>

            </div>

            <div id="PolarisPortalsContainer"></div>

          </div>

        </div>

        <?php

        include("footer.php");

        ?>

        <script>
          $.getScript("https://cdn.shopify.com/s/javascripts/currencies.js", function() {

            return;

          }).then(() => {

            // var contract_sale = '<?php //echo json_encode($get_billing_attempt_orders_sale); 
                                    ?>';

            // var total_contract_sale = 0;

            // $.each(JSON.parse(contract_sale), function( key, value ) {

            //   var currency_amount = Currency.convert(value.total_sale,value.contract_currency,'<?php //echo $currency; 
                                                                                                  ?>');

            //   console.log(currency_amount);

            //   total_contract_sale = total_contract_sale + currency_amount;

            // });

            // jQuery('.sd_total_sales').html('<?php //echo $mainobj->currency_code; 
                                                ?>'+''+total_contract_sale.toFixed(2));





            // create arrays for chart

            var total_date_price_array = '<?php echo json_encode($total_date_price_array); ?>';

            let date_price_array = {};

            $.each(JSON.parse(total_date_price_array), function(key, value) {

              var dtes = value['created_at'].substring(0, value['created_at'].indexOf(' '));

              var contract_amount = Currency.convert(value['order_total'], value['order_currency'], '<?php echo $currency; ?>');

              if (date_price_array[dtes]) {

              } else {

                date_price_array[dtes] = [];

              }

              date_price_array[dtes].push(contract_amount);

            });

            console.log(date_price_array);

            var newDatapointspartlabel = [];

            var newDatapointsparthigh = [];

            $.each(date_price_array, function(key, value) {

              newDatapointspartlabel.push(key);

              newDatapointsparthigh.push((value.reduce((partialSum, a) => partialSum + a, 0)).toFixed(2));

            });

            var chartPart = Highcharts.chart('sd_subscription_graph', {

              chart: {
                type: 'spline',
                backgroundColor: '#161b22',
                style: {
                  fontFamily: 'Segoe UI'
                }
              },

              title: {
                text: null
              },

              subtitle: {
                text: null
              },


              xAxis: {
                categories: newDatapointspartlabel,
                labels: {
                  style: {
                    color: '#aaa'
                  }
                },
                lineColor: '#2a2d38',
                tickColor: '#2a2d38'
              },

              yAxis: {
                allowDecimals: true,
                title: {
                  text: "Sales in <?php echo $currency; ?>",
                  style: {
                    color: '#aaa'
                  }
                },
                labels: {
                  style: {
                    color: '#aaa'
                  }
                },
                gridLineColor: '#2a2d38'
              },

              legend: {
                itemStyle: {
                  color: '#fff'
                }
              },

              tooltip: {
                backgroundColor: '#ff0080',
                style: {
                  color: '#fff'
                },
                formatter: function() {
                  return `$${this.y.toFixed(2)}`;
                }
              },


              series: [

                {

                  name: 'Total sale ' + ((newDatapointsparthigh.map(Number)).reduce((partialSum, a) => partialSum + a, 0)),

                  data: newDatapointsparthigh.map(Number),

                  color: '#ff0080',
                  marker: {
                    radius: 6,
                    fillColor: '#ff0080',
                    lineWidth: 0
                  }

                }
              ],

              credits: {
                enabled: false
              },

              responsive: {

                rules: [{

                  condition: {

                    maxWidth: 500

                  },

                  chartOptions: {
                    yAxis: {
                      labels: {
                        align: 'left',
                        y: -5
                      },
                      title: {
                        text: null
                      }
                    }
                  }

                }]

              }

            });

          });

          var Currency = {

            convert: function(amount, from, to) {

              return (amount * this.rates[from]) / this.rates[to];

              console.log('2');

            }

          }

          //analytics datepicker start



          //date filter click on analytic page

          let shop_current_date = jQuery("#shop_current_date").val();

          let check_start_date_input = document.getElementById('startdateReport');

          if (check_start_date_input) {

            jQuery("#startdateReport,#enddateReport").datepicker({

              "dateFormat": "yy-mm-dd",

              maxDate: shop_current_date,

              onSelect: function(dateText) {

                let start_date = jQuery('#startdateReport').val();

                let last_date = jQuery('#enddateReport').val();

                if (start_date > last_date) {

                  last_date = start_date;

                }

                if (last_date < start_date) {

                  start_date = last_date;

                }

                $('#startdateReport').datepicker("setDate", start_date);

                $('#enddateReport').datepicker("setDate", last_date);

              }

            });

          }
        </script>