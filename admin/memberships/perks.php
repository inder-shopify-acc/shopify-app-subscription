<?php
include('../header.php');

include("../navigation.php");
$membership_plan_id = $_REQUEST['get_member_id'];  
$Requested_MembershipPlanID = $_REQUEST['get_member_id'];
$sql_perks = "SELECT * 
              FROM membership_perks 
              WHERE store = :store 
              AND membership_plan_id = :membership_plan_id";

// Prepare the statement for membership perks
$stmt_perks = $db->prepare($sql_perks);

// Bind parameters
$stmt_perks->bindParam(':store', $store);
$stmt_perks->bindParam(':membership_plan_id', $membership_plan_id);

// Execute the query
$stmt_perks->execute();

// Fetch the results
$get_perksData = $stmt_perks->fetchAll(PDO::FETCH_ASSOC);
if($store == 'test-store-phoenixt.myshopify.com') {

    // print_r($get_perksData);
    // die;
}
if(count($get_perksData) <= 0) {
    include("create-perks.php");
}else {

    include("edit-perks.php");
}

include("../footer.php");
?>