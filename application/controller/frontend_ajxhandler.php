

<?php
// echo "hehehh";die;
// error_reporting(0);
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     http_response_code(200);
//     exit();
// }

require_once('../library/config.php');
switch ($_REQUEST['action']) {

    case "checkCustomerSubscription":

        $shopify_customer_id = $_REQUEST['customer_id'];

        $query = $db->prepare("SELECT * FROM subscriptionOrderContract WHERE shopify_customer_id ='$shopify_customer_id'");

        $query->execute();

        $row_count = $query->rowCount();

        if ($row_count) {

            $accountPageHandle = '';

            echo json_encode(array("customer_exist" => 'yes', 'accountPageHandle' => $accountPageHandle));
        } else {

            echo json_encode(array("customer_exist" => 'no'));
        }

        break;





    case "subscriptionPlanGroupsDetails":

        $store = $_REQUEST['store'];

        $product_id = $_REQUEST['product_id'];

        $store_query = $db->query("SELECT id from install where store = '$store'");

        $store_query_data = $store_query->fetch(PDO::FETCH_ASSOC);

        $store_id = $store_query_data['id'];



        $currentPlanDetails_query = $db->query("SELECT planName,plan_id from storeInstallOffers where store_id = '$store_id' and status = '1'");

        $store_install_data = $currentPlanDetails_query->fetch(PDO::FETCH_ASSOC);

        $planName = $store_install_data['planName'];

        $plan_id = $store_install_data['plan_id'];

        if ($plan_id != '1') {

            $app_subscription_status = 'ACTIVE';
        } else {

            $app_subscription_status = "FREE";
        }



        $currentPlanDetails_query = $db->query("SELECT subscription_plan_group_id from subscriptionPlanGroupsProducts where store_id = '$store_id' and product_id = '$product_id'");

        $subscription_plan_group_id = $currentPlanDetails_query->fetchAll(PDO::FETCH_ASSOC);

        if ($subscription_plan_group_id) {

            $subscription_group_ids = array_column($subscription_plan_group_id, 'subscription_plan_group_id');

            $subscription_group_ids_string = implode(',', $subscription_group_ids);

            $subscriptionPlanGroupsDetails_query = $db->query("SELECT max_cycle,delivery_policy,delivery_billing_type,billing_policy,discount_value,selling_plan_id,plan_type,discount_type,discount_offer,recurring_discount_offer,change_discount_after_cycle,discount_type_after,discount_value_after FROM subscriptionPlanGroupsDetails WHERE store_id = '$store_id' and subscription_plan_group_id IN ($subscription_group_ids_string)");

            $subscriptionPlanGroupsDetails = $subscriptionPlanGroupsDetails_query->fetchAll(PDO::FETCH_ASSOC);
        } else {

            $subscriptionPlanGroupsDetails = '';
        }



        $store_widget_data_query = $db->query("SELECT * from widget_settings where store_id = '$store_id'");

        $store_widget_data = $store_widget_data_query->fetch(PDO::FETCH_ASSOC);

        echo json_encode(array('subscriptionPlanGroupsDetails' => $subscriptionPlanGroupsDetails, 'app_subscription_status' => $app_subscription_status, 'store_widget_data' => $store_widget_data));

        break;
    // membereship 

    case "all-memberplans-list":
        // Database connection

        // Get request data
        $store = $_GET['store'] ?? '';
        $customer_id = $_GET['customer_id'] ?? '';
        $runAjax = $_GET['runAjax'] ?? '';

        $customer_data = [];

        if (!empty($customer_id)) {
            $stmt = $db->prepare("
        SELECT mp.free_shipping_code, mp.freeshipping_selected_value, mp.min_quantity_items, mp.min_purchase_amount_value,
               mp.discounted_product_collection_code, mp.discounted_product_collection_percentageoff,
               mp.discounted_product_collection_type, mp.discounted__product_title, mp.discounted__collection_title
        FROM membership_perks mp
        JOIN purchased_membership_product_details pmpd ON pmpd.variant_id = mp.variant_id
        JOIN purchased_membership_details pmd ON pmd.contract_id = pmpd.contract_id
        WHERE mp.store = ? AND pmpd.customer_id = ? AND pmd.plan_status = 'A'
    ");
            $stmt->execute([$store, $customer_id]);
            $customer_data = $stmt->fetchAll();
        }

        // Fetch member_groups_perks_product
        $stmt = $db->prepare("
    SELECT DISTINCT mpg.membership_group_id, *
    FROM membership_plan_groups mpg
    JOIN membership_plans mp ON mp.id = mpg.membership_plan_id
    JOIN membership_perks mperks ON mperks.membership_group_id = mpg.membership_group_id
    JOIN membership_product_details mpd ON mpd.membership_group_id = mperks.membership_group_id
    WHERE mpg.store = ? AND mp.plan_status = 'enable'
");
        $stmt->execute([$store]);
        $member_groups_perks_product = $stmt->fetchAll();

        // Fetch member_plan_data
        $stmt = $db->prepare("SELECT * FROM membership_plans WHERE store = ?");
        $stmt->execute([$store]);
        $member_plan_data = $stmt->fetchAll();

        // Drawer setting
        $stmt = $db->prepare("SELECT * FROM drawer_settings WHERE store = ?");
        $stmt->execute([$store]);
        $drawer_setting = $stmt->fetch();

        // Product widget settings
        $stmt = $db->prepare("SELECT * FROM product_widget_settings WHERE store = ?");
        $stmt->execute([$store]);
        $product_widget_settings = $stmt->fetch();

        // Plans widget settings
        $stmt = $db->prepare("SELECT * FROM member_plans_widgets WHERE store = ?");
        $stmt->execute([$store]);
        $plans_widget_settings = $stmt->fetch();

        // Member group details
        $stmt = $db->prepare("SELECT * FROM membership_groups_details WHERE store = ?");
        $stmt->execute([$store]);
        $member_group_details = $stmt->fetchAll();

        // Countdown data
        $stmt = $db->prepare("SELECT * FROM count_down_settings WHERE store = ?");
        $stmt->execute([$store]);
        $getCountDownData = $stmt->fetch();

        // Early sale data
        $stmt = $db->prepare("SELECT * FROM membership_early_sales WHERE store = ?");
        $stmt->execute([$store]);
        $getEarlySaleData = $stmt->fetch();

        // JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'member_plan_data' => $member_plan_data,
            'member_groups_perks_product' => $member_groups_perks_product,
            'member_group_details' => $member_group_details,
            'drawer_setting' => $drawer_setting,
            'product_widget_settings' => $product_widget_settings,
            'plans_widget_settings' => $plans_widget_settings,
            'customer_data' => $customer_data,
            'getCountDownData' => $getCountDownData,
            'getEarlySaleData' => $getEarlySaleData
        ]);

        break;
        case "get-customer-data":
        // echo "bxsudsu";die;
        $store = $_REQUEST['store'] ?? '';
        $shopify_customer_id = $_REQUEST['customer_id'] ?? '';

        if ($store == 'thediyart1.myshopify.com') {
            if ($shopify_customer_id == '7977278701764') {
                $shopify_customer_id = '7888419946692';
            }
        }

        // print_r($shopify_customer_id);
        if (!empty($shopify_customer_id)) {
            $stmt = $db->prepare("
                SELECT 
                    mp.free_shipping_code, 
                    mp.discounted_product_collection_checked_value,
                    mp.free_shipping_checked_value,
                    mp.freeshipping_selected_value, 
                    mp.min_quantity_items, 
                    mp.min_purchase_amount_value,
                    mp.discounted_product_collection_code, 
                    mp.discounted_product_collection_percentageoff,
                    mp.discounted_product_collection_type, 
                    mp.discounted__product_title, 
                    mp.discounted__collection_title
                FROM membership_perks mp
                JOIN subscritionOrderContractProductDetails pmpd 
                    ON pmpd.variant_id = mp.variant_id
                JOIN subscriptionOrderContract pmd 
                    ON pmd.contract_id = pmpd.contract_id
                WHERE mp.store = ? 
                    AND pmd.shopify_customer_id = ? 
                    AND pmd.contract_status = 'A'
            ");
            // print_r($stmt);
            $stmt->execute([$store, $shopify_customer_id]);
            $customer_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode([
            'customer_data' => $customer_data,
        ]);
        break;
}
