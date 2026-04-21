<div class="search-form">

   <div id="subscription-list-search">

      <div class="formbox">

         <div class="plan-heading Polaris-Stack__Item Polaris-Stack__Item--fill">

            <h3 class="polaris_SubscriptionHeaders">My Plans</h3>

         </div>

         <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented" data-buttongroup-segmented="true">

            <div class="sd_select_plan_view">

               <div class="Polaris-ButtonGroup__Item">

                  <button class="Polaris-Button select_plan_view <?php if ($subscription_plans_view == 'grid') {
                                                                     echo 'sd_selectedList';
                                                                  } ?>" id="sd_grid_view" plan-view='grid' type="button" aria-pressed="false">

                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">

                        <metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>

                        <g>
                           <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)">
                              <path d="M405.7,4993.2c-91.9-32.5-206.7-135.9-256.5-227.8c-42.1-80.4-42.1-82.3-42.1-1056.5c0-962.7,0-978.1,42.1-1052.7c47.9-90,122.5-162.7,216.3-212.5c63.2-34.4,128.2-36.4,1043.1-36.4c962.7,0,978,0,1052.7,42.1c90,47.8,162.7,122.5,212.5,216.3c34.4,63.2,36.4,128.2,36.4,1043.1c0,970.4,0,976.1-42.1,1054.6c-53.6,101.4-174.2,204.8-271.8,233.5C2291,5029.6,495.7,5025.8,405.7,4993.2z M2371.4,3714.7l-5.7-962.7h-957h-957l-5.7,962.7l-3.8,960.8h966.6h966.6L2371.4,3714.7z"></path>
                              <path d="M3994.4,4991.3c-105.3-36.4-204.8-122.5-254.6-222c-42.1-84.2-42.1-84.2-44-1052.7V2750l49.8-99.5c57.4-116.8,141.6-187.6,268-225.9c137.8-40.2,1862.3-40.2,1981,1.9c120.6,44,199.1,111,254.6,223.9l49.8,99.5v962.7v964.6l-47.9,93.8c-51.7,103.3-143.6,181.8-252.7,222C5891.2,5031.5,4101.6,5029.6,3994.4,4991.3z M5954.3,3708.9v-966.6h-957h-957v966.6v966.6h957h957V3708.9z"></path>
                              <path d="M7581.2,4995.1c-99.5-32.5-254.6-195.2-285.2-300.5c-17.2-65.1-21-319.6-17.2-1024c5.7-918.7,5.7-939.8,47.9-1014.4c47.8-90,122.5-162.7,216.3-212.5c63.2-34.4,128.2-36.4,1043.1-36.4c962.7,0,978,0,1052.7,42.1c90,47.8,162.7,122.5,212.4,216.3c34.5,63.2,36.4,128.2,36.4,1043.1c0,970.4,0,976.1-42.1,1054.6c-53.6,101.4-174.2,204.8-271.8,233.5C9474.1,5027.7,7676.9,5025.8,7581.2,4995.1z M9548.8,3714.7L9543,2752h-957h-957l-5.7,962.7l-3.8,960.8h966.6h966.6L9548.8,3714.7z"></path>
                              <path d="M333,1364.3c-109.1-63.2-181.8-155-216.3-277.5c-15.3-53.6-19.1-354.1-15.3-1016.3l5.7-939.8l57.4-93.8c40.2-63.2,91.9-114.8,162.7-155l103.4-63.2h978h978l103.4,63.2c70.8,40.2,122.5,91.9,162.7,155l57.4,93.8l5.7,945.5c3.8,606.7-1.9,970.4-15.3,1018.2c-30.6,112.9-122.5,225.9-229.7,279.5l-93.8,47.8h-972.3H432.5L333,1364.3z M2375.2,120.2v-957h-966.6H442.1v957v957h966.6h966.6V120.2z"></path>
                              <path d="M3954.2,1385.4c-42.1-19.1-103.4-65.1-137.8-99.5c-122.5-130.1-120.6-109.1-120.6-1169.4c0-1073.7-1.9-1045,135.9-1177.1c130.1-122.5,109.1-120.6,1169.4-120.6c1073.7,0,1045-1.9,1177.1,135.9c122.5,130.1,120.6,109.1,120.6,1165.6c0,1056.5,1.9,1035.5-120.6,1165.6c-132.1,137.8-101.4,135.9-1180.9,135.9C4095.8,1421.7,4025,1417.9,3954.2,1385.4z M5954.3,120.2v-957h-957h-957v957v957h957h957V120.2z"></path>
                              <path d="M7523.8,1375.8c-99.5-49.8-201-172.3-229.7-275.6c-30.6-112.9-24.9-1889.1,7.7-1981c40.2-109.1,118.7-201,222-252.6l93.8-47.9h972.3h972.3l99.5,57.4c109.1,63.2,164.6,130.1,206.7,250.7c42.1,118.7,42.1,1868.1,0,1986.7c-42.1,120.6-97.6,187.6-206.7,250.7l-99.5,57.4h-976.1l-976.1-1.9L7523.8,1375.8z M9552.6,120.2v-957h-966.6h-966.6v957v957h966.6h966.6V120.2z"></path>
                              <path d="M442.1-2174.6c-132.1-42.1-231.6-124.4-298.6-250.7c-34.4-63.2-36.4-128.2-36.4-1043.1c0-970.4,0-976.1,42.1-1054.6c53.6-101.5,174.2-204.8,271.8-233.5c105.3-32.5,1871.9-32.5,1981,0c49.8,15.3,114.8,59.3,176.1,120.6c145.5,145.5,145.5,143.6,137.8,1225c-5.7,853.6-7.7,924.4-42.1,985.7c-49.8,93.8-122.5,168.4-212.5,216.3c-74.6,42.1-93.8,42.1-1024,45.9C916.7-2161.2,468.9-2167,442.1-2174.6z M2371.4-3472.3l3.8-962.7h-966.6H442.1v953.2c0,524.4,5.7,960.8,13.4,966.6c5.7,7.7,440.2,11.5,960.8,9.6l949.3-5.7L2371.4-3472.3z"></path>
                              <path d="M4000.2-2184.2c-111-38.3-185.6-103.3-246.9-208.6l-57.4-99.5v-972.3V-4437l47.9-93.8c51.7-103.4,143.5-181.8,252.6-222c105.3-36.4,1896.7-36.4,2002,0c109.1,40.2,201,118.7,252.7,222l47.9,93.8v964.6v962.7l-49.8,99.5c-26.8,55.5-78.5,122.5-111,147.4c-135.9,103.3-151.2,105.3-1148.4,105.3C4287.3-2159.3,4051.8-2165.1,4000.2-2184.2z M5954.3-3468.5V-4435h-957h-957v966.6v966.6h957h957V-3468.5z"></path>
                              <path d="M7619.5-2174.6c-132.1-42.1-231.6-124.4-298.6-250.7c-34.5-63.2-36.4-132.1-42.1-1004.8c-3.8-704.3,0-958.9,17.2-1024c32.5-112.9,185.7-269.9,296.7-302.4c109.1-32.5,1875.7-32.5,1981,0c97.6,28.7,218.2,132.1,271.8,233.5c42.1,78.5,42.1,84.2,42.1,1054.6c0,914.9-1.9,980-36.4,1043.1c-49.7,93.8-122.5,168.4-212.4,216.3c-74.6,42.1-93.8,42.1-1024,45.9C8094.2-2161.2,7646.3-2167,7619.5-2174.6z M9543-3468.5v-957l-960.8-5.7l-962.7-3.8v953.2c0,524.4,5.7,960.8,13.4,966.6c5.7,7.7,440.2,11.5,960.8,9.6l949.3-5.7V-3468.5z"></path>
                           </g>
                        </g>

                     </svg>

                  </button>

               </div>

               <div class="Polaris-ButtonGroup__Item">

                  <button class="Polaris-Button select_plan_view <?php if ($subscription_plans_view == 'list') {
                                                                     echo 'sd_selectedList';
                                                                  } ?>" id="sd_list_view" plan-view='list' type="button" aria-pressed="true">

                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <rect width="3" height="2" y="7" fill="#5C5F62" rx="1"></rect>
                        <rect width="3" height="2" y="3" fill="#5C5F62" rx="1"></rect>
                        <rect width="3" height="2" y="11" fill="#5C5F62" rx="1"></rect>
                        <rect width="3" height="2" y="15" fill="#5C5F62" rx="1"></rect>
                        <path fill="#5C5F62" d="M5 8a1 1 0 0 1 1-1h13a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1ZM5 4a1 1 0 0 1 1-1h13a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1ZM5 12a1 1 0 0 1 1-1h13a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1ZM5 16a1 1 0 0 1 1-1h13a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1Z"></path>
                     </svg>

                  </button>

               </div>

            </div>

            <div class="create_plan_button">

               <div id="PolarisPortalsContainer" class="t-right top-banner-create-subscription">

                  <button class="Polaris-Button Polaris-Button--primary CreateSubscriptipnGroup sd_button createplan_button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Create Plan</span></span></button>

               </div>

            </div>

         </div>

      </div>

      <div class="input-box sd_plan_grid_view <?php if ($subscription_plans_view == 'list') {
                                                   echo 'display-hide-label';
                                                } ?>">

         <input type="text" placeholder="Enter Plan Name" id="search-subscription-text" class="form-control">

      </div>

   </div>

</div>

<?php

$result = $db->query("SELECT PG.subscription_plangroup_id,PG.id , PG.store_id ,PG.plan_name ,(SELECT GROUP_CONCAT(delivery_policy, ' ', delivery_billing_type ) AS sellingplanslist FROM `subscriptionPlanGroupsDetails` as P WHERE P.subscription_plan_group_id = PG.subscription_plangroup_id) as sellingplanslist, COUNT(DISTINCT P.variant_id) as Totalproducts ,COUNT(DISTINCT D.selling_plan_id) as totalsellingplans FROM `subscriptionPlanGroups` as PG LEFT JOIN `subscriptionPlanGroupsProducts` as P ON PG.subscription_plangroup_id = P.subscription_plan_group_id LEFT JOIN `subscriptionPlanGroupsDetails` as D ON PG.subscription_plangroup_id = D.subscription_plan_group_id WHERE PG.store_id = '$store_id' GROUP BY PG.subscription_plangroup_id, D.subscription_plan_group_id , P.subscription_plan_group_id");

$store_all_subscription_plans = $result->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="cardboxes sd_plan_grid_view <?php if($subscription_plans_view == 'list'){ echo 'display-hide-label'; } ?> <?php if($store_all_subscription_plans){ echo ''; }else{ echo 'no_plan_exist'; } ?>">

   <div class="Polaris-Layout subscription-list-start sd_subscription_list sd_plan_grid_view <?php if ($subscription_plans_view == 'list') { echo 'display-hide-label';} ?>">

      <?php

      if ($store_all_subscription_plans) {

         $sd_plan_list = '';

         foreach ($store_all_subscription_plans as $store_all_subscription_plan) {

            $sd_plan_list .= '<tr>

            <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">' . $store_all_subscription_plan['plan_name'] . '</td>

            <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">' . $store_all_subscription_plan['totalsellingplans'] . '</td>

            <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">' . $store_all_subscription_plan['Totalproducts'] . '</td>

            <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">

            <button subscription-group-id="' . $store_all_subscription_plan['subscription_plangroup_id'] . '" class="Polaris-Button  edit-subscription-group" type="button" data-type="edit"><svg width="81" height="64" viewBox="0 0 81 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_112649_532286)">
            <rect x="22.5" y="12" width="40" height="40" rx="10" fill="#141E2D"/>
            <rect x="23" y="12.5" width="39" height="39" rx="9.5" stroke="white" stroke-opacity="0.2"/>
            <path d="M36.2778 37.3266C36.2778 37.8212 36.6788 38.2222 37.1734 38.2222C37.411 38.2222 37.6388 38.1279 37.8068 37.9599L46.2333 29.5333L44.9667 28.2667L36.5401 36.6932C36.3721 36.8612 36.2778 37.089 36.2778 37.3266ZM35.5 40C34.9477 40 34.5 39.5523 34.5 39V36.637C34.5 36.3714 34.6056 36.1168 34.7936 35.9292L46.2333 24.5111C46.4111 24.3481 46.6076 24.2222 46.8227 24.1333C47.0378 24.0444 47.2636 24 47.5 24C47.737 24 47.9667 24.0444 48.1889 24.1333C48.4111 24.2222 48.6037 24.3556 48.7667 24.5333L49.9889 25.7778C50.1667 25.9407 50.2964 26.1333 50.3782 26.3556C50.46 26.5778 50.5006 26.8 50.5 27.0222C50.5 27.2593 50.4594 27.4853 50.3782 27.7004C50.297 27.9156 50.1673 28.1117 49.9889 28.2889L38.5707 39.7071C38.3831 39.8946 38.1288 40 37.8636 40H35.5ZM45.5889 28.9111L44.9667 28.2667L46.2333 29.5333L45.5889 28.9111Z" fill="white"/>
            </g>
            <defs>
            <filter id="filter0_d_112649_532286" x="0.5" y="-4" width="80" height="80" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
            <feOffset dx="-2" dy="4"/>
            <feGaussianBlur stdDeviation="10"/>
            <feComposite in2="hardAlpha" operator="out"/>
            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0"/>
            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_112649_532286"/>
            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_112649_532286" result="shape"/>
            </filter>
            </defs>
            </svg>
</button>

            <button class="Polaris-Button delete_subscription_plan"  subscription-group-id="' . $store_all_subscription_plan['subscription_plangroup_id'] . '"  data-id="' . $store_all_subscription_plan['id'] . '" type="button">

               <span class="Polaris-Button__Content">

                  <span class="Polaris-Button__Text">

                  <svg width="81" height="64" viewBox="0 0 81 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <g filter="url(#filter0_d_112604_500973)">
                     <rect x="22.5" y="12" width="40" height="40" rx="10" fill="#141E2D"/>
                     <rect x="23" y="12.5" width="39" height="39" rx="9.5" stroke="white" stroke-opacity="0.2"/>
                     <path d="M44.3462 30.3448C44.856 30.3448 45.2692 30.7154 45.2692 31.1724V35.8621C45.2692 36.3191 44.856 36.6897 44.3462 36.6897C43.8364 36.6897 43.4231 36.3191 43.4231 35.8621V31.1724C43.4231 30.7154 43.8364 30.3448 44.3462 30.3448Z" fill="white"/>
                     <path d="M41.5769 31.1724C41.5769 30.7154 41.1636 30.3448 40.6538 30.3448C40.144 30.3448 39.7308 30.7154 39.7308 31.1724V35.8621C39.7308 36.3191 40.144 36.6897 40.6538 36.6897C41.1636 36.6897 41.5769 36.3191 41.5769 35.8621V31.1724Z" fill="white"/>
                     <path fill-rule="evenodd" clip-rule="evenodd" d="M39.1153 27.0345C39.1153 25.3586 40.6307 24 42.4999 24C44.3692 24 45.8845 25.3586 45.8845 27.0345H49.5769C50.0867 27.0345 50.5 27.405 50.5 27.8621C50.5 28.3191 50.0867 28.6897 49.5769 28.6897H48.6537L48.6536 34.7036C48.6536 36.5575 48.6535 37.4845 48.2511 38.1926C47.8971 38.8154 47.3322 39.3218 46.6375 39.6392C45.8476 40 44.8137 40 42.7458 40H42.2538C40.1859 40 39.1519 40 38.3621 39.6392C37.6673 39.3218 37.1024 38.8154 36.7485 38.1925C36.346 37.4843 36.346 36.5573 36.3461 34.7033L36.3462 28.6897H35.4231C34.9133 28.6897 34.5 28.3191 34.5 27.8621C34.5 27.405 34.9133 27.0345 35.4231 27.0345H39.1153ZM40.9615 27.0345C40.9615 26.2727 41.6503 25.6552 42.4999 25.6552C43.3496 25.6552 44.0384 26.2727 44.0384 27.0345H40.9615ZM38.1924 28.6897H46.8076L46.8074 34.7035C46.8074 35.6578 46.806 36.274 46.7632 36.7429C46.7222 37.1926 46.6527 37.3593 46.6061 37.4411C46.4291 37.7525 46.1467 38.0057 45.7993 38.1644C45.7081 38.2061 45.5221 38.2685 45.0206 38.3052C44.4975 38.3435 43.8102 38.3448 42.7458 38.3448H42.2538C41.1894 38.3448 40.5021 38.3435 39.979 38.3052C39.4775 38.2685 39.2915 38.2061 39.2002 38.1644C38.8528 38.0057 38.5704 37.7525 38.3934 37.4411C38.3469 37.3592 38.2773 37.1925 38.2364 36.7429C38.1937 36.2739 38.1922 35.6577 38.1923 34.7034L38.1924 28.6897Z" fill="white"/>
                     </g>
                     <defs>
                     <filter id="filter0_d_112604_500973" x="0.5" y="-4" width="80" height="80" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                     <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                     <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                     <feOffset dx="-2" dy="4"/>
                     <feGaussianBlur stdDeviation="10"/>
                     <feComposite in2="hardAlpha" operator="out"/>
                     <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0"/>
                     <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_112604_500973"/>
                     <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_112604_500973" result="shape"/>
                     </filter>
                     </defs>
                     </svg>


                  </span>

               </span>

            </button></td>

            </tr>';

            $sellingPlanList_array =  explode(",", $store_all_subscription_plan['sellingplanslist']);

      ?>

            <div data-search-planname="<?php echo $store_all_subscription_plan['plan_name']; ?>" id="subscription_list_<?php echo $store_all_subscription_plan['subscription_plangroup_id']; ?>" class="subscription-list-card Polaris-Layout__Section Polaris-Layout__Section--oneHalf subscription_list_<?php echo $store_all_subscription_plan['subscription_plangroup_id']; ?>">

               <div class="Polaris-Card">

                  <div class="sd-upper-wrapper">

                     <div class="Polaris-Card__Header">

                        <div class="Polaris-Stack Polaris-Stack--alignmentCenter">

                           <div class="Polaris-Stack__Item">

                              <h2 class="Polaris-Heading subscription_heading"><span class="list_planname"><?php echo $store_all_subscription_plan['plan_name']; ?></span><span plan-name-value="<?php echo $store_all_subscription_plan['plan_name']; ?>" subscription-group-id="<?php echo $store_all_subscription_plan['subscription_plangroup_id']; ?>" class="change_plan_name">

                                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path d="M4.27778 15.3266C4.27778 15.8212 4.67878 16.2222 5.17345 16.2222C5.41099 16.2222 5.63881 16.1279 5.80678 15.9599L14.2333 7.53333L12.9667 6.26667L4.54011 14.6932C4.37214 14.8612 4.27778 15.089 4.27778 15.3266ZM3.5 18C2.94771 18 2.5 17.5523 2.5 17V14.637C2.5 14.3714 2.60562 14.1168 2.79356 13.9292L14.2333 2.51111C14.4111 2.34815 14.6076 2.22222 14.8227 2.13333C15.0378 2.04444 15.2636 2 15.5 2C15.737 2 15.9667 2.04444 16.1889 2.13333C16.4111 2.22222 16.6037 2.35556 16.7667 2.53333L17.9889 3.77778C18.1667 3.94074 18.2964 4.13333 18.3782 4.35556C18.46 4.57778 18.5006 4.8 18.5 5.02222C18.5 5.25926 18.4594 5.48533 18.3782 5.70044C18.297 5.91556 18.1673 6.1117 17.9889 6.28889L6.57067 17.7071C6.38313 17.8946 6.12878 18 5.86356 18H3.5ZM13.5889 6.91111L12.9667 6.26667L14.2333 7.53333L13.5889 6.91111Z" fill="white" />
                                    </svg>



                                 </span>

                              </h2>

                              <div class="planname_input_wrapper display-hide-label" />

                              <input type="text" value="<?php echo $store_all_subscription_plan['plan_name']; ?>" name="subscription_heading" maxlength="50" class="subscription_plan_name restrict_input_single_quote" />

                              <img src="<?php echo $image_folder; ?>MobileCancelMajor.svg" class="plan_name_actions cancel_plan_name" />

                              <img class="plan_name_actions save_plan_name" case-type="list" subscription-group-id="<?php echo $store_all_subscription_plan['subscription_plangroup_id'] ?>" src="<?php echo $image_folder; ?>MobileAcceptMajor.svg" />

                           </div>

                        </div>

                     </div>

                  </div>

                  <div id="mini_<?php echo $store_all_subscription_plan['subscription_plangroup_id']; ?>" class="subscription_mini_inner_wrapper">

                     <div class="Polaris-Card__Section inner-box-cont bot-bx">

                        <div class="Polaris-Card__SectionHeader box-header">

                           <a href="javascript:;" class="edit-subscription-group" subscription-group-id="<?php echo $store_all_subscription_plan['subscription_plangroup_id'] ?>">
                              <h3 aria-label="Items" class="Polaris-Subheading sd_sellingplans" data-type="selling_plans">Selling plans</h3>

                              <div class="Polaris-ButtonGroup">
                           </a>

                           <div class="Polaris-ButtonGroup__Item Polaris-ButtonGroup__Item--plain"><?php echo $store_all_subscription_plan['totalsellingplans']; ?></div>

                        </div>

                     </div>

                     <div class="sd_selling_plans">

                        <div class="list-selling-plans-detail">Delivery Every <?php echo implode(",", array_chunk($sellingPlanList_array, 3)[0]); ?></div>

                     </div>

                  </div>


                  <div class="Polaris-ResourceList__ResourceListWrapper sd_selling_products">

                     <ul>

                     </ul>

                  </div>

               </div>
               <div class="Polaris-Card__Footer">

                  <div class="Polaris-Stack Polaris-Stack--alignmentCenter">

                     <div class="Polaris-Card__Section inner-box-cont">

                        <div class="Polaris-Card__SectionHeader box-header">

                           <a href="javascript:;" class="edit-subscription-group" subscription-group-id="<?php echo $store_all_subscription_plan['subscription_plangroup_id'] ?>" data-type="products">
                              <h3 aria-label="Items" class="Polaris-Subheading sd_sellingplans">Products</h3>

                              <div class="Polaris-ButtonGroup">
                           </a>

                           <div class="Polaris-ButtonGroup__Item Polaris-ButtonGroup__Item--plain"><?php echo $store_all_subscription_plan['Totalproducts']; ?></div>

                        </div>

                     </div>


                     <div class="Polaris-ButtonGroup">

                        <div class="Polaris-ButtonGroup__ItemEdit"><button subscription-group-id="<?php echo $store_all_subscription_plan['subscription_plangroup_id'] ?>" class="Polaris-Button Polaris-Button--primary light-bg sd_button  edit-subscription-group Polaris-ButtonGroup__ItemEditButton" type="button" data-type="edit">Edit</button></div>

                        <div class="Polaris-ButtonGroup__Item">

                           <button class="Polaris-Button remove-btn delete_subscription_plan" subscription-group-id="<?php echo $store_all_subscription_plan['subscription_plangroup_id']; ?>" data-id="<?php echo $store_all_subscription_plan['id'] ?>" type="button">


                              <svg width="40" height="38" viewBox="0 0 40 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <rect x="0.905273" width="38.3448" height="38" rx="10" fill="#141E2D" />
                                 <rect x="1.40527" y="0.5" width="37.3448" height="37" rx="9.5" stroke="white" stroke-opacity="0.2" />
                                 <path d="M21.9238 17.3448C22.4336 17.3448 22.8469 17.7154 22.8469 18.1724V22.8621C22.8469 23.3191 22.4336 23.6897 21.9238 23.6897C21.414 23.6897 21.0008 23.3191 21.0008 22.8621V18.1724C21.0008 17.7154 21.414 17.3448 21.9238 17.3448Z" fill="white" />
                                 <path d="M19.1546 18.1724C19.1546 17.7154 18.7413 17.3448 18.2315 17.3448C17.7217 17.3448 17.3085 17.7154 17.3085 18.1724V22.8621C17.3085 23.3191 17.7217 23.6897 18.2315 23.6897C18.7413 23.6897 19.1546 23.3191 19.1546 22.8621V18.1724Z" fill="white" />
                                 <path fill-rule="evenodd" clip-rule="evenodd" d="M16.693 14.0345C16.693 12.3586 18.2083 11 20.0776 11C21.9469 11 23.4622 12.3586 23.4622 14.0345H27.1546C27.6644 14.0345 28.0777 14.405 28.0777 14.8621C28.0777 15.3191 27.6644 15.6897 27.1546 15.6897H26.2314L26.2313 21.7036C26.2312 23.5575 26.2312 24.4845 25.8288 25.1926C25.4748 25.8154 24.9099 26.3218 24.2152 26.6392C23.4253 27 22.3914 27 20.3235 27H19.8315C17.7636 27 16.7296 27 15.9398 26.6392C15.245 26.3218 14.6801 25.8154 14.3261 25.1925C13.9237 24.4843 13.9237 23.5573 13.9238 21.7033L13.9239 15.6897H13.0008C12.491 15.6897 12.0777 15.3191 12.0777 14.8621C12.0777 14.405 12.491 14.0345 13.0008 14.0345H16.693ZM18.5391 14.0345C18.5391 13.2727 19.2279 12.6552 20.0776 12.6552C20.9273 12.6552 21.6161 13.2727 21.6161 14.0345H18.5391ZM15.7701 15.6897H24.3852L24.3851 21.7035C24.3851 22.6578 24.3836 23.274 24.3409 23.7429C24.2999 24.1926 24.2303 24.3593 24.1838 24.4411C24.0068 24.7525 23.7244 25.0057 23.377 25.1644C23.2857 25.2061 23.0998 25.2685 22.5983 25.3052C22.0752 25.3435 21.3879 25.3448 20.3235 25.3448H19.8315C18.7671 25.3448 18.0798 25.3435 17.5567 25.3052C17.0551 25.2685 16.8692 25.2061 16.7779 25.1644C16.4305 25.0057 16.1481 24.7525 15.9711 24.4411C15.9246 24.3592 15.855 24.1925 15.8141 23.7429C15.7713 23.2739 15.7699 22.6577 15.7699 21.7034L15.7701 15.6897Z" fill="white" />
                              </svg>

                           </button>

                        </div>

                     </div>

                     <div id="PolarisPortalsContainer"></div>

                  </div>
               </div>

            </div>
   </div>

</div><!-- header wrapper -->

</div>


<?php } ?>

</div>

<?php } else { ?>

   </div>

   <div id="plan-no-list">

      <div class="Polaris-Card">

         <div class="Polaris-Card__Section">

            <div class="Polaris-EmptyState Polaris-EmptyState--withinContentContainer">

               <div class="Polaris-EmptyState__Section">

                  <div class="Polaris-EmptyState__DetailsContainer">

                     <div class="Polaris-EmptyState__Details">

                        <div class="Polaris-TextContainer">

                           <p class="Polaris-DisplayText Polaris-DisplayText--sizeSmall">Create your first plan</p>

                           <div class="Polaris-EmptyState__Content">

                              <p>Start selling your products with amazing plans.</p>

                           </div>

                        </div>

                        <div class="Polaris-EmptyState__Actions">

                           <div class="Polaris-Stack Polaris-Stack--spacingTight Polaris-Stack--distributionCenter Polaris-Stack--alignmentCenter">

                              <div class="Polaris-Stack__Item"><button class="Polaris-Button Polaris-Button--primary CreateSubscriptipnGroup" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Start Now</span></span></button></div>

                              <div class="Polaris-Stack__Item"><a class="Polaris-Button" href="<?php echo $SHOPIFY_DOMAIN_URL; ?>/admin/video_tutorials.php?shop=<?php echo $store; ?>" data-polaris-unstyled="true"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Learn more</span></span></a></div>

                           </div>

                        </div>

                     </div>

                  </div>

                  <div class="Polaris-EmptyState__ImageContainer"><img src="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png" role="presentation" alt="" class="Polaris-EmptyState__Image"></div>

               </div>

            </div>

         </div>

      </div>

      <div id="PolarisPortalsContainer"></div>

   </div>

<?php } ?>



<div id="no_search_result" class="display-hide-label">No matching records found.<a onclick="list_subscription_mode()">Clear search</a></div>

</div>

<div class="sd_plan_list_view sd_common_datatable <?php if ($subscription_plans_view == 'grid') {
                                                      echo 'display-hide-label';
                                                   } ?>">

   <div class="sd_subscription_table">

      <table class="Polaris-DataTable__Table dataTable no-footer" id="subscription_plan_table" role="grid" aria-describedby="subscriptionTable_info">

         <thead>

            <tr role="row">

               <th data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--firstColumn Polaris-DataTable__Cell--header sorting_disabled" scope="col" aria-sort="none" rowspan="1" colspan="1" style="width: 200px; font-size: 16px">Plan Name</th>

               <th data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--firstColumn Polaris-DataTable__Cell--header sorting_disabled" scope="col" aria-sort="none" rowspan="1" colspan="1" style="width: 200px; font-size: 16px">Selling Plans</th>

               <th data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric sorting_disabled" scope="col" aria-sort="none" rowspan="1" colspan="1" style="width: 200px font-size: 16px;">Products</th>

               <th data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric sorting_disabled" scope="col" aria-sort="none" rowspan="1" colspan="1" style="width: 60px; font-size: 16px">Action</th>

            </tr>

         </thead>

         <tbody>

            <?php echo $sd_plan_list; ?>

         </tbody>

      </table>

   </div>

</div>

<script>

</script>