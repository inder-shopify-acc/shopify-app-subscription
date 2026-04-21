<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include("../application/library/config.php");

if (isset($_GET['shop'])) {
    $store = filter_var(trim($_GET['shop']), FILTER_SANITIZE_URL);
    $store_name = strtok($store, '.');
} else {
    // If shop parameter is missing, stop execution
    exit("Shop parameter missing.");
}

$query = $db->prepare("SELECT * FROM `InstallAndStoreinstalloffer` WHERE store = :store");
$query->bindParam(':store', $store);
$query->execute();

$row_count = $query->rowCount();

if ($row_count > 0) {
    $row_data = $query->fetch(PDO::FETCH_ASSOC);

    // Check if store_id is empty or not
    //   if($store == 'pheonix-store-local.myshopify.com' || $store == 'test-store-phoenixt.myshopify.com'){
        $store_details_query = "SELECT * FROM storeInstallOffers JOIN install ON install.id = storeInstallOffers.store_id WHERE store = '$store'";
         
         $result_row_count = $db->query($store_details_query);
         
         $store_details = $result_row_count->fetch(PDO::FETCH_ASSOC);
         $appPlanId = $store_details['appSubscriptionPlanId'];
        //  print_r($store_details);

        // die;
    // }
    if (empty($row_data['store_id']) || empty($appPlanId)) {
        $install_action_url = "/admin/memberPlans.php";
    } else {
        $install_action_url = "/admin/dashboard.php";
    }

    echo "<script>window.location.href = '" . $install_action_url . "?shop=" . urlencode($store) . "';</script>";
    exit;
} else {
    $client_id = urlencode($SHOPIFY_APIKEY);
    $scopes = urlencode($SHOPIFY_SCOPES);
    $redirect_uri = urlencode($SHOPIFY_REDIRECT_URI);

    $install_action_url = "https://{$store}/admin/oauth/authorize?client_id={$client_id}&scope={$scopes}&redirect_uri={$redirect_uri}";

    header("Location: $install_action_url");
    exit;
}
?>
