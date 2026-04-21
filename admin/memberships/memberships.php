    <?php
include("../header.php");
include $dirPath."/graphLoad/autoload.php";
$search_member_plan = $_GET['search_member_plan'] ?? '';
$shop = $store; // or wherever you're getting the shop value from

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$params = [':shop' => $shop];
$search_sql = '';

if (!empty($search_member_plan)) {
    $search_sql = 'AND mp.membership_plan_name LIKE :search_member_plan';
    $params[':search_member_plan'] = '%' . $search_member_plan . '%';
}

$sql = "
    SELECT 
        mp.id,
        mp.membership_plan_name,
        mp.membership_product_type,
        mpd.product_id,
        (
            SELECT GROUP_CONCAT(mpg2.membership_group_name)
            FROM membership_plan_groups mpg2
            WHERE mpg2.membership_plan_id = mp.id AND mpg2.group_status = 'enable'
        ) AS member_group_name,
        (
            SELECT GROUP_CONCAT(mpg3.membership_group_id)
            FROM membership_plan_groups mpg3
            WHERE mpg3.membership_plan_id = mp.id
        ) AS member_group_ids,
        COUNT(DISTINCT mpd.variant_id) AS Totalproducts,
        (
            SELECT GROUP_CONCAT(mpks.freeshipping_pricerule_id)
            FROM membership_perks mpks
            WHERE mpks.membership_plan_id = mp.id
        ) AS freeshipping_member_perk_ids,
        (
            SELECT GROUP_CONCAT(mpks2.discounted_product_collection_price_rule_id)
            FROM membership_perks mpks2
            WHERE mpks2.membership_plan_id = mp.id
        ) AS discounted_product_collection_price_rule_id
    FROM membership_plans mp
    LEFT JOIN membership_plan_groups mpg ON mpg.membership_plan_id = mp.id
    LEFT JOIN membership_product_details mpd ON mpd.membership_plan_id = mp.id
    WHERE mp.store = :shop
    AND mp.plan_status = 'enable'
    $search_sql
    GROUP BY mp.id, mp.membership_plan_name, mp.membership_product_type, mpd.product_id
    ORDER BY mp.id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$member_plans_data = $stmt->fetchAll(PDO::FETCH_OBJ);
// print_r($member_plans_data);die;
// Get product_widget_settings
$stmt2 = $db->prepare("SELECT * FROM product_widget_settings WHERE store = :shop LIMIT 1");
$stmt2->execute([':shop' => $shop]);
$product_widget_settings = $stmt2->fetch(PDO::FETCH_OBJ);
// print_r($product_widget_settings);

// Get plansData
// $stmt3 = $db->prepare("SELECT store, plan_status, current_plan, charge_id FROM installs WHERE store = :shop LIMIT 1");
// $stmt3->execute([':shop' => $shop]);
// $plansData = $stmt3->fetch(PDO::FETCH_OBJ);

// Get countplans
$stmt4 = $db->prepare("SELECT COUNT(*) FROM membership_plans WHERE store = :shop AND plan_status = 'enable'");
$stmt4->execute([':shop' => $shop]);
$countplans = $stmt4->fetchColumn();

// print_r($countplans);
?>


<?php

include("../navigation.php");
// echo 'store details data';
//    echo '<pre>';
//    print_r($store_details_data);
//    die;
?>
<div id="create_memberPlan_wrapper" class="create_memberPlan_wrapper display-hide-label">
   <?php include('membership_create.php'); ?>
</div>
<div id="edit_memberPlan_wrapper" class="display-hide-label">
    <?php include('membership_edit.php'); ?>
</div>
<div id="list_memberPlan_wrapper" class="list_memberPlan_wrapper">
    <?php include('membership_list.php'); ?>
</div>
<?php include("../footer.php"); ?>
<!-- <div id="edit_memberPlan_wrapper" class="display-hide-label">
  @include('Members.memberPlan_edit')
</div>
<div id="list_memberPlan_wrapper" class="list_memberPlan_wrapper">
  @include('Members.memberPlan_list')
</div> -->