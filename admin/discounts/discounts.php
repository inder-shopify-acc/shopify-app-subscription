<?php

include("../header.php");
include $dirPath."/graphLoad/autoload.php";
// $search_member_plan = $_GET['search_member_plan'] ?? '';
$search_discount_plan = isset($_GET['search_discount_plan']) ? trim($_GET['search_discount_plan']) : '';
$shop = $store; // or wherever you're getting the shop value from

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$params = [':shop' => $shop];
$search_sql = '';


// if (!empty($search_member_plan)) {
//     $search_sql = 'AND mp.membership_plan_name LIKE :search_member_plan';
//     $params[':search_member_plan'] = '%' . $search_member_plan . '%';
// }

if (!empty($search_discount_plan)) {
    $sql .= " AND (discount_name LIKE :search OR product_name LIKE :search)";
}


// $sql = "
//     SELECT 
//         mp.id,
//         mp.membership_plan_name,
//         mp.membership_product_type,
//         mpd.product_id,
//         (
//             SELECT GROUP_CONCAT(mpg2.membership_group_name)
//             FROM membership_plan_groups mpg2
//             WHERE mpg2.membership_plan_id = mp.id AND mpg2.group_status = 'enable'
//         ) AS member_group_name,
//         (
//             SELECT GROUP_CONCAT(mpg3.membership_group_id)
//             FROM membership_plan_groups mpg3
//             WHERE mpg3.membership_plan_id = mp.id
//         ) AS member_group_ids,
//         COUNT(DISTINCT mpd.variant_id) AS Totalproducts,
//         (
//             SELECT GROUP_CONCAT(mpks.freeshipping_pricerule_id)
//             FROM membership_perks mpks
//             WHERE mpks.membership_plan_id = mp.id
//         ) AS freeshipping_member_perk_ids,
//         (
//             SELECT GROUP_CONCAT(mpks2.discounted_product_collection_price_rule_id)
//             FROM membership_perks mpks2
//             WHERE mpks2.membership_plan_id = mp.id
//         ) AS discounted_product_collection_price_rule_id
//     FROM membership_plans mp
//     LEFT JOIN membership_plan_groups mpg ON mpg.membership_plan_id = mp.id
//     LEFT JOIN membership_product_details mpd ON mpd.membership_plan_id = mp.id
//     WHERE mp.store = :shop
//     AND mp.plan_status = 'enable'
//     $search_sql
//     GROUP BY mp.id, mp.membership_plan_name, mp.membership_product_type, mpd.product_id
//     ORDER BY mp.id DESC
//     LIMIT $limit OFFSET $offset
// ";

$sql = "SELECT * FROM `discount_raoul`  WHERE store = :shop LIMIT 1";

if (!empty($search_discount_plan)) {
    $params[':search'] = '%' . $search_discount_plan . '%';
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$discount_plans_data = $stmt->fetchAll(PDO::FETCH_OBJ);
// print_r($discount_plans_data);die;


?>


<?php

include("../navigation.php");

?>
<div id="create_discountPlan_wrapper" class="create_discount_wrapper display-hide-label">
   <?php include('discount_create.php'); ?>
</div>
<div id="edit_memberPlan_wrapper" class="display-hide-label">
    <?php include('discount_edit.php'); ?>
</div>
<div id="list_discountPlan_wrapper" class="list_discountPlan_wrapper">
    <?php include('discount_list.php'); ?>
</div>
<?php include("../footer.php"); ?>
<!-- <div id="edit_memberPlan_wrapper" class="display-hide-label">
  @include('Members.memberPlan_edit')
</div>
<div id="list_discountPlan_wrapper" class="list_discountPlan_wrapper">
  @include('Members.memberPlan_list')
</div> -->