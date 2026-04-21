<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
// include('../header.php');

// include("../navigation.php");
// Get the requested membership plan ID and shop name from the request
$membership_plan_id = $_REQUEST['get_member_id'];  // Equivalent to $request->get('get_member_id')

// Assuming you have a PDO connection stored in $pdo

// Prepare the SQL query for membership plans data
$sql = "SELECT * 
        FROM membership_plans 
        JOIN membership_plan_groups 
        ON membership_plan_groups.membership_plan_id = membership_plans.id 
        WHERE membership_plans.store = :store 
        AND membership_plans.id = :membership_plan_id
        AND membership_plan_groups.group_status = :group_status
        ";

// Prepare the statement
$stmt = $db->prepare($sql);

// Bind parameters to prevent SQL injection
$group_status = 'enable';
$stmt->bindParam(':store', $store);
$stmt->bindParam(':membership_plan_id', $membership_plan_id);
$stmt->bindParam(':group_status', $group_status);

// Execute the query
$stmt->execute();

// Fetch the results
$RequestedMembership_Name = $stmt->fetchAll(PDO::FETCH_OBJ);
// print_r($member_plans_data);die;

// Prepare the SQL query for membership perks data
// $sql_perks = "SELECT * 
//               FROM membership_perks 
//               WHERE store = :store 
//               AND membership_plan_id = :membership_plan_id";

// // Prepare the statement for membership perks
// $stmt_perks = $db->prepare($sql_perks);

// // Bind parameters
// $stmt_perks->bindParam(':store', $store);
// $stmt_perks->bindParam(':membership_plan_id', $membership_plan_id);

// // Execute the query
// $stmt_perks->execute();

// // Fetch the results
// $get_perksData = $stmt_perks->fetchAll(PDO::FETCH_ASSOC);
// if (count($get_perksData)  < 1) {
//     echo "<script>open('/stagesub/admin/memberships/perks.php?get_member_id=".$membership_plan_id."','_self')</script>";
//     die;

// }
// print_r($get_perksData);die;
?>
<?php
// if(count($get_perksData) > 0){
?>
<style type="text/css">
    #sd_selected_existing_collection_id {
        width: 100%;
    }

    .Polaris-TextContainer {
        margin-bottom: 2rem;
    }

    .Polaris-Collapsible {
        max-height: none !important;
    }

    .Edit_delete-selected-item {
        cursor: pointer;
    }
</style>

<div class="sd-prefix-main">
    <div class="sd-subscription-page-title">
        <div class="Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">
            <div class="Polaris-Page-Header__Row">
                <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <nav role="navigation">
                        <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership" onclick="handleLinkRedirect('/memberships/memberships.php')">
                            <span class="Polaris-Breadcrumbs__ContentWrapper"> <span class="Polaris-Breadcrumbs__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">
                                            </path>
                                        </svg> </span> </span>
                            </span>
                        </a>
                    </nav>
                </div>
                <div class="Polaris-Page-Header__TitleWrapper">
                    <div>
                        <div class="sd-planList-withBtn">
                            <label class="Polaris-Heading">Edit perks</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // This assumes you have access to all the same variables as in the Blade template
    // $RequestedMembership_Name, $get_perksData, $storecurrencyCode, etc.
    $tierGroupIdArray = [];
    if (!empty($RequestedMembership_Name)) {
        foreach ($RequestedMembership_Name as $index => $data) {
            $tierName = trim($data->membership_group_id);
            $tierGroupIdArray[] = $tierName;
        }
    }
    ?>
    <form id="update_membership_type_perks_form" action="" method="POST">
        <div class="Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">

            <div class="Polaris-Page-Header__Row">
                <input type="hidden" class="Edit_tierGroupIdArray0" value="<?php echo isset($tierGroupIdArray[0]) ? htmlspecialchars($tierGroupIdArray[0]) : ''; ?>">
                <input type="hidden" class="Edit_tierGroupIdArray1" value="<?php echo isset($tierGroupIdArray[1]) ? htmlspecialchars($tierGroupIdArray[1]) : ''; ?>">
                <input type="hidden" class="Edit_tierGroupIdArray2" value="<?php echo isset($tierGroupIdArray[2]) ? htmlspecialchars($tierGroupIdArray[2]) : ''; ?>">

                <div class="Polaris-Page-Header__TitleWrapper">

                </div>
            </div>

            <div class="Polaris-Layout">
                <div class="Polaris-Layout__AnnotatedSection">
                    <div class="Polaris-Layout__AnnotationWrapper">
                        <?php
                        if (count($get_perksData) > 0) {
                            $arrayNo = 0;


                            foreach ($get_perksData as $index => $data) {
                        ?>
                                <div
                                    edit_updated_id="<?php echo htmlspecialchars($data['id']); ?>"
                                    class="membership_perk_right getTierId-<?php echo htmlspecialchars($arrayNo); ?> Polaris-Layout__AnnotationContent edit-TierSelected-<?php echo htmlspecialchars($data['membership_group_id']); ?> edit-perks-form-selected"
                                    perks-type="<?php echo htmlspecialchars($data['perks_type_value']); ?>"
                                    membership-group-id="<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                    membership_plan_id="<?php echo htmlspecialchars($data['membership_plan_id']); ?>"
                                    style="display: none">
                                    <h3 style="font-size: 21px;padding: 20px 5px;font-weight: 600;">
                                        Tier Name : <?php echo isset($RequestedMembership_Name[$arrayNo]->membership_group_name) ? htmlspecialchars($RequestedMembership_Name[$arrayNo]->membership_group_name) : ''; ?>
                                    </h3>
                                    <p class="edit_tier_perk_Error_<?php echo htmlspecialchars($data['membership_group_id']); ?> sd_perkViewErrors" style="color:red; display:none"></p>
                                    <div class="Polaris-Card__Section">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <label class="Polaris-Choice" for="edit-PolarisCheckbox2-<?php echo htmlspecialchars($data['membership_group_id']); ?>">
                                                    <span class="Polaris-Choice__Control">
                                                        <span class="Polaris-Checkbox">
                                                            <input
                                                                id="edit-PolarisCheckbox2-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                name="discount_<?php echo htmlspecialchars($data['membership_group_id']); ?>_option"
                                                                type="checkbox"
                                                                class="Polaris-Checkbox__Input perks-beforechecked freeShipEditCheckbox-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                aria-invalid="false"
                                                                role="checkbox"
                                                                aria-checked="false"
                                                                value="1"
                                                                <?php echo ($data['free_shipping_checked_value'] == '1') ? 'checked' : ''; ?>>
                                                            <span class="Polaris-Checkbox__Backdrop"></span>
                                                            <span class="Polaris-Checkbox__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span class="Polaris-Choice__Label">Free shipping</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="Edit-Polaris-Layout__AnnotationContent edit-Polaris-Collapsible"
                                            aria-expanded="false"
                                            style="<?php if ($data['free_shipping_checked_value'] == '1') {
                                                        echo 'max-height: none !important; display: block !important; overflow: visible !important;';
                                                    } else {
                                                        echo 'max-height: none; overflow: visible; display: none;';
                                                    } ?>">
                                            <div class="Polaris-TextContainer">
                                                <div class="Polaris-FormLayout">
                                                    <div role="group" class="Polaris-FormLayout--condensed">
                                                        <div class="Polaris-FormLayout__Items">
                                                            <!-- Free shipping code input -->
                                                            <div class="Polaris-FormLayout__Item">
                                                                <div>
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label class="Polaris-Label__Text">Free shipping code</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Connected">
                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                            <div class="Polaris-TextField">
                                                                                <input type="hidden" id="old_edit-free_shipingCode-<?= $data['membership_group_id'] ?>" value="<?= $data['free_shipping_code'] ?? '' ?>">
                                                                                <input type="hidden" id="edit-free_shipping_price_rule_ID-<?= $data['membership_group_id'] ?>" value="<?= $data['freeshipping_pricerule_id'] ?? '' ?>">
                                                                                <input name="perk-discounted_shipping-code"
                                                                                    id="edit-free_shipingCode-<?= $data['membership_group_id'] ?>"
                                                                                    class="Polaris-TextField__Input test capitalDiscountInput freeShipEditValue-<?= $data['membership_group_id'] ?> checkCodeExists"
                                                                                    type="text"
                                                                                    aria-labelledby="PolarisTextField1Label"
                                                                                    aria-invalid="false"
                                                                                    code-value="<?= $data['free_shipping_code'] ?? '' ?>"
                                                                                    value="<?= $data['free_shipping_code'] ?? '' ?>">
                                                                                <div class="Polaris-TextField__Backdrop"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="Edit-free_shipingCode_Error_<?= $data['membership_group_id'] ?> sd_perkViewErrors" style="color:red;display:none">
                                                                        Shiping code field is required!
                                                                    </p>
                                                                    <p class="Edit-ExistingFree_shipingCode_Error_<?= $data['membership_group_id'] ?> sd_perkViewErrors ExistingFree_shipingCode_Error" style="color:red;display:none">
                                                                        Code Taken! Use another code!
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- Minimum purchase amount/quantity -->
                                                            <div class="Polaris-FormLayout__Item">
                                                                <div>
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label class="Polaris-Label__Text">Minimum purchase amount/quantity</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Connected">
                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                            <div class="Polaris-TextField">
                                                                                <select class="edit-minimumDiscountCode"
                                                                                    id="Edit-minimumDiscountCode-<?= $data['membership_group_id'] ?>"
                                                                                    Edit-minimumDiscountCode-attr="<?= $data['perks_type_value'] ?>"
                                                                                    edit-member-grpup-id="<?= $data['membership_group_id'] ?>">
                                                                                    <option value="none" <?= $data['freeshipping_selected_value'] == 'none' ? 'selected' : '' ?>>
                                                                                        No minimum requirements
                                                                                    </option>
                                                                                    <option value="min_purchase_amount"
                                                                                        <?= $data['freeshipping_selected_value'] == 'min_purchase_amount' ? 'selected' : '' ?>
                                                                                        edit-min-purchase-amountValue="<?= $data['min_purchase_amount_value'] ?>">
                                                                                        Minimum purchase amount (<?= $currency_code ?? '' ?>)
                                                                                    </option>
                                                                                    <option value="min_quantity_items"
                                                                                        <?= $data['freeshipping_selected_value'] == 'min_quantity_items' ? 'selected' : '' ?>
                                                                                        edit-max-purchase-amountValue="<?= $data['min_quantity_items'] ?>">
                                                                                        Minimum quantity of items
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="Edit-minimumDiscountCode_Error_<?= $data['membership_group_id'] ?> sd_perkViewErrors" style="color: red; display: none">
                                                                        Minimum purchase amount field is required!
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- Conditional input field for purchase amount or quantity -->
                                                            <div class="Polaris-FormLayout__Item test-<?= $data['membership_group_id']  ?>">
                                                                <?php if ($data['freeshipping_selected_value'] == 'min_purchase_amount'): ?>
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label class="Polaris-Label__Text">Enter minimum purchase amount</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Connected">
                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                            <div class="Polaris-TextField">
                                                                                <input name="minPurchaseAmount_TextField_Gold"
                                                                                    id="edit-minPurchaseAmount_TextField_<?= $data['membership_group_id'] ?>"
                                                                                    class="Polaris-TextField__Input test editMinPurchaseAmount<?= $data['membership_group_id'] ?> qtyAmtValidation"
                                                                                    type="number"
                                                                                    min="1"
                                                                                    max="99999"
                                                                                    value="<?= $data['min_purchase_amount_value'] ?? '1' ?>">
                                                                                <div class="Polaris-TextField__Backdrop"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php elseif ($data['freeshipping_selected_value'] == 'min_quantity_items'): ?>
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label class="Polaris-Label__Text">Enter minimum quantity items</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Connected">
                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                            <div class="Polaris-TextField">
                                                                                <input name="maxPurchaseAmount_TextField_Gold"
                                                                                    id="edit-maxPurchaseAmount_TextField_<?= $data['membership_group_id'] ?>"
                                                                                    class="Polaris-TextField__Input test editMinPurchaseAmount<?= $data['membership_group_id'] ?> qtyAmtValidation"
                                                                                    type="number"
                                                                                    min="1"
                                                                                    max="99999"
                                                                                    value="<?= $data['min_quantity_items'] ?? '1' ?>">
                                                                                <div class="Polaris-TextField__Backdrop"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <p class="Edit-minPurchaseAmount_TextFieldError_<?= $data['membership_group_id'] ?> sd_perkViewErrors" style="color: red; display:none;">
                                                                    Enter minimum quantity/amount and should be greater than 0
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Informational text and links -->
                                                <p>
                                                    Create a discount code that is only valid for customers with the tags
                                                    <span class="Polaris-Tag"><span class="Polaris-Tag__TagText">sd_membership_customer : Active</span></span>
                                                    and
                                                    <span class="Polaris-Tag"><span class="Polaris-Tag__TagText"><?= $RequestedMembership_Name[$arrayNo]->unique_handle ?? '' ?> : Active</span></span>.
                                                </p>
                                                <p>
                                                    For more information,
                                                    <a class="Polaris-Link" href="https://shinedezign.gitbook.io/elite-memberships/" target="_blank">check out this help article</a>
                                                    or
                                                    <button type="button" class="Polaris-Link sd_handle_navigation_redirect" value="/admin/contactUs.php">contact us</button>.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-Card__Section">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <label class="Polaris-Choice" for="edit-PolarisCheckbox3-<?= $data['membership_group_id'] ?>">
                                                    <span class="Polaris-Choice__Control">
                                                        <span class="Polaris-Checkbox">
                                                            <input id="edit-PolarisCheckbox3-<?= $data['membership_group_id'] ?>"
                                                                name="discount"
                                                                type="checkbox"
                                                                class="Polaris-Checkbox__Input discountEditCheckbox-<?= $data['membership_group_id'] ?>"
                                                                aria-invalid="false"
                                                                role="checkbox"
                                                                aria-checked="true"
                                                                value=""
                                                                <?= $data['discounted_product_collection_checked_value'] == 1 ? 'checked' : '' ?>>
                                                            <span class="Polaris-Checkbox__Backdrop"></span>
                                                            <span class="Polaris-Checkbox__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span class="Polaris-Choice__Label">Discounted products or collections</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div style="<?php echo ($data['discounted_product_collection_checked_value'] == '1') ? 'max-height: none !important; display: block !important; overflow: visible !important;' : 'max-height: none; overflow: visible; display: none;'; ?>" class="Edit-Polaris-Layout__AnnotationContent edit-Polaris-Collapsible" aria-expanded="true">
                                            <div class="Polaris-TextContainer"></div>
                                            <div class="Polaris-FormLayout">
                                                <div role="group" class="Polaris-FormLayout--condensed">
                                                    <div class="Polaris-FormLayout__Items">
                                                        <!-- Code Field -->
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div>
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="PolarisTextField8" class="Polaris-Label__Text">Code</label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Connected">
                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                        <div class="Polaris-TextField">
                                                                            <input type="hidden" id="old_Edit-DiscountProductCode_Field-<?php echo $data['membership_group_id']; ?>" value="<?php echo $data['discounted_product_collection_code'] ?? ''; ?>">
                                                                            <input type="hidden" id="Edit-DiscountProductCode_Field_price_rule_ID-<?php echo $data['membership_group_id']; ?>" value="<?php echo $data['discounted_product_collection_price_rule_id'] ?? ''; ?>">

                                                                            <input name="perk-discounted_products-code" id="Edit-DiscountProductCode_Field-<?php echo $data['membership_group_id']; ?>" class="Polaris-TextField__Input capitalDiscountInput checkCodeExists discountEditValue-<?php echo $data['membership_group_id']; ?>" type="text" value="<?php echo $data['discounted_product_collection_code'] ?? ''; ?>">
                                                                            <div class="Polaris-TextField__Backdrop"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="Edit-DiscountCode-Error-<?php echo $data['membership_group_id']; ?> sd_perkViewErrors" style="display: none;color: red;">Discount code is required!</p>
                                                                <p class="Edit-Existing_DiscountCode-Error-<?php echo $data['membership_group_id']; ?> Existing_DiscountCode-Error sd_perkViewErrors" style="display: none;color: red;">Discount code is already taken!</p>
                                                            </div>
                                                        </div>
                                                        <!-- Percentage Off -->
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div>
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="PolarisTextField9" class="Polaris-Label__Text">Percentage off</label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Connected">
                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                        <div class="Polaris-TextField">
                                                                            <input name="perk-discounted_products-amount" id="Edit-DiscountCodePercentage-<?php echo $data['membership_group_id']; ?>" class="Polaris-TextField__Input Polaris-TextField__Input--suffixed discountEditPercentValue-<?php echo $data['membership_group_id']; ?> numberPercentage" type="text" value="<?php echo $data['discounted_product_collection_percentageoff'] ?? ''; ?>">
                                                                            <div class="Polaris-TextField__Suffix">% off</div>
                                                                            <div class="Polaris-TextField__Backdrop"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="Edit-DiscountPercentage-Error-<?php echo $data['membership_group_id']; ?> sd_perkViewErrors" style="display: none;color: red;">Discount % is required & greater than 0</p>
                                                            </div>
                                                        </div>

                                                        <!-- Discount Applies To -->
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div>
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="PolarisSelect6" class="Polaris-Label__Text">Discount applies to</label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Select">
                                                                    <select id="edit-PolarisSelect-<?php echo $data['membership_group_id']; ?>" name="perk-discounted_products-applies_to" class="Polaris-Select__Input edit-DiscountAppliedSelect_box" edit-DiscountAppliedSelectedTier_Value="<?php echo $data['perks_type_value'] ?? ''; ?>" edit-member-grpup-id="<?php echo $data['membership_group_id'] ?? ''; ?>">
                                                                        <option value="all" <?php echo ($data['discounted_product_collection_type'] == 'N') ? 'selected' : ''; ?>>All products</option>
                                                                        <option value="collection" <?php echo ($data['discounted_product_collection_type'] == 'C') ? 'selected' : ''; ?>>Specific collection</option>
                                                                        <option value="product" <?php echo ($data['discounted_product_collection_type'] == 'P') ? 'selected' : ''; ?>>Specific product</option>
                                                                    </select>
                                                                    <div class="Polaris-Select__Content" aria-hidden="true">
                                                                        <span class="Polaris-Select__SelectedOption_<?php echo $data['membership_group_id']; ?> sd_discount_select_value">
                                                                            <?php
                                                                            if ($data['discounted_product_collection_type'] == 'C') {
                                                                                echo 'Specific collection';
                                                                            } elseif ($data['discounted_product_collection_type'] == 'P') {
                                                                                echo 'Specific product';
                                                                            } else {
                                                                                echo 'All Product';
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                        <span class="Polaris-Select__Icon select-collection-product">
                                                                            <span class="Polaris-Icon">
                                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                    <path d="..."></path>
                                                                                </svg>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="Polaris-Select__Backdrop"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Specific Collection or Product Display -->
                                                <div class="edit-perksDiscountApplies-<?php echo $data['membership_group_id']; ?>">
                                                    <?php if ($data['discounted_product_collection_type'] == 'C') : ?>
                                                        <!-- Collection Block -->
                                                        <div class="Polaris-FormLayout__Item edit-selected-collectionbox-<?php echo $data['membership_group_id']; ?>">
                                                            
                                                            <!-- You can copy the rest of the HTML in this block and replace Blade variables with PHP echo -->
                                                            <div class="Polaris-FormLayout__Items edit-sd_selected_existing_collection-<?php echo $data['membership_group_id']; ?>" id="edit-sd_selected_existing_collection_id">
                                                                <div class="Polaris-FormLayout__Item">
                                                                    <div class="">
                                                                        <div class="Polaris-Labelled__LabelWrapper">
                                                                            <div class="Polaris-Label">
                                                                                <label id="PolarisTextField4Label" for="PolarisTextField4" class="Polaris-Label__Text">Collection</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="Polaris-Connected">
                                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                                                                    <input disabled id="<?php if (!empty($data['discounted__collection_id'])) { echo $data['discounted__collection_id']; } ?>" name="perk-discounted_products-applies_to-collection_title" class="Polaris-TextField__Input edit-collectionTitleName-<?php echo $data['membership_group_id']; ?>" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="<?php if (!empty($data['discounted__collection_title'])) { echo $data['discounted__collection_title']; } ?>">
                                                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-FormLayout__Item">
                                                                    <div>
                                                                        <div class="Polaris-Labelled__LabelWrapper">
                                                                            <div class="Polaris-Label">
                                                                                <label class="Polaris-Label__Text">&nbsp;</label>
                                                                            </div>
                                                                        </div>
                                                                        <button id="edit-RemoveSelectedDiscountField" value="<?php echo $data['membership_group_id'] ?? ''; ?>" class="Polaris-Button" type="button">
                                                                            <span class="Polaris-Button__Content">
                                                                                <span class="Polaris-Button__Icon">
                                                                                    <span class="Polaris-Icon">
                                                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                            <path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path>
                                                                                        </svg>
                                                                                    </span>
                                                                                </span>
                                                                            </span>
                                                                        </button>
                                                                        <input type="hidden" name="perk-discounted_products-applies_to-collection_id" value="<?php if (!empty($data['discounted__collection_id'])) { echo $data['discounted__collection_id']; } ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-FormLayout__Items sd_perkViewErrors edit-sd_selected_existing_collection-Error-<?php echo $data['membership_group_id']; ?>" id="sd_selected_existing_collection_id" style="color: red;display: none;">Add collection!
                                                            </div>


                                                        </div>
                                                    <?php elseif ($data['discounted_product_collection_type'] == 'P') : ?>
                                                        <!-- Product Block -->
                                                        <div class="Polaris-FormLayout__Item edit-selected-selectproductbox-<?php echo $data['membership_group_id']; ?>">
                                                            <div class="Polaris-FormLayout__Items sd_selected_existing_specificProduct-<?php echo $data['membership_group_id']; ?>" id="edit-sd_selected_existing_collection_specificProduct_id">
                                                                <div class="Polaris-FormLayout__Item">
                                                                    <div class="">
                                                                        <div class="Polaris-Labelled__LabelWrapper">
                                                                            <div class="Polaris-Label">
                                                                                <label id="PolarisTextField4Label" for="PolarisTextField4" class="Polaris-Label__Text">Product</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="Polaris-Connected">
                                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled"> 

                                                                                    
                                                                                    <!-- <input disabled="" id="@if ($data->discounted__product_id) {{ $data->discounted__product_id }}@else @endif" name="perk-discounted_products-applies_to-collection_title" class="Polaris-TextField__Input edit-productTitleName-{{ $data->membership_group_id }}" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="@if ($data->discounted__product_title) {{ $data->discounted__product_title }}@else @endif"> -->
                                                                                    <input disabled="" id="<?php if ($data['discounted__product_id']) { echo $data['discounted__product_id']; } ?>" name="perk-discounted_products-applies_to-collection_title" class="Polaris-TextField__Input edit-productTitleName-<?php echo $data['membership_group_id']; ?>" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="<?php if ($data['discounted__product_title']) { echo $data['discounted__product_title']; } ?>">
                                                                                    
                                                                                    <div class="Polaris-TextField__Backdrop">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-FormLayout__Item">
                                                                <div>
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div>
                                                                    </div>
                                                                    <button id="edit-RemoveSelected_productDiscountField" value="<?php echo $data['membership_group_id'] ?? ''; ?>" class="Polaris-Button" type="button">
                                                                        <span class="Polaris-Button__Content">
                                                                            <span class="Polaris-Button__Icon">
                                                                                <span class="Polaris-Icon">
                                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                        <path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </span>
                                                                        </span>
                                                                    </button>
                                                                    <input type="hidden" name="perk-discounted_products-applies_to-collection_id" value="<?php if ($data['discounted__product_id']) { echo $data['discounted__product_id']; } ?>">
                                                                </div>

                                                                </div>
                                                            </div>
                                                            <div class="Polaris-FormLayout__Items edit-sd_selected_existing_product-Error-<?php echo $data['membership_group_id']; ?> sd_perkViewErrors" id="edit-sd_selected_existing_product_id" style="color:red;display:none;">Add product!
                                                            </div>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Tagging Help Text -->
                                            <div class="Polaris-TextContainer">
                                                <p></p>
                                                <p>
                                                    Specify that products should be discounted when the following tags are present on the Shopify customer:
                                                    <span class="Polaris-Tag"><span class="Polaris-Tag__TagText">sd_membership_customer : Active</span></span>
                                                    &nbsp; and &nbsp;
                                                    <span class="Polaris-Tag">
                                                        <span class="Polaris-Tag__TagText">
                                                            <?php echo $RequestedMembership_Name[$arrayNo]->unique_handle ?? ''; ?> : Active
                                                        </span>
                                                    </span>.
                                                </p>
                                                <p>
                                                    For more information, <a class="Polaris-Link" href="https://shinedezign.gitbook.io/elite-memberships/" target="_blank">check out this help article</a>
                                                    or <button type="button" class="Polaris-Link sd_handle_navigation_redirect" value="/admin/contactUs.php">contact us</button>.
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="Polaris-Card__Section">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <label class="Polaris-Choice" for="edit-PolarisCheckbox4-<?php echo $data['membership_group_id']; ?>">
                                                    <span class="Polaris-Choice__Control">
                                                        <span class="Polaris-Checkbox">
                                                            <input id="edit-PolarisCheckbox4-<?php echo $data['membership_group_id']; ?>" name="discount" type="checkbox" class="Polaris-Checkbox__Input freeProductEditCheckbox-<?php echo $data['membership_group_id']; ?>" aria-invalid="false" role="checkbox" aria-checked="true" value="1" <?php echo ($data['Free_gift_uponsignup_checkbox'] == '1') ? 'checked' : ''; ?>>
                                                            <span class="Polaris-Checkbox__Backdrop"></span>
                                                            <span class="Polaris-Checkbox__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span class="Polaris-Choice__Label">Free gift upon signup</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div style="<?php echo ($data['Free_gift_uponsignup_checkbox'] == '1') ? 'max-height: none !important; display: block !important; overflow: visible !important;' : 'max-height: none; overflow: visible; display: none;'; ?>" class="edit-free_gift-upon-signUp-<?php echo $data['membership_group_id']; ?>" aria-expanded="true">
                                            <div class="Polaris-TextContainer">
                                                <p>Sending new members a free gift is a great way to show appreciation and build brand loyalty</p>
                                            </div>
                                            <div class="Polaris-FormLayout sd_select_product_button set-edit-free_upon_sign_up_button-<?php echo $data['membership_group_id']; ?>">
                                                <?php if ($data['Free_gift_uponsignup_checkbox'] == '1') { ?>
                                                    <div role="group" class="Polaris-FormLayout--condensed">
                                                        <div class="Polaris-FormLayout__Items">
                                                            <div class="Polaris-FormLayout__Item">
                                                                <div class="">
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label id="PolarisTextField20Label" for="PolarisTextField20" class="Polaris-Label__Text">Product</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Connected">
                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                            <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                                                                <input edit-free-gift_selected_productid="<?php echo !empty($data['perk_free_gift_product_id']) ? $data['perk_free_gift_product_id'] : ''; ?>" name="edit-perk-free_gift-product_title" id="edit-Free-gift_uponsignup_productName-<?php echo $data['membership_group_id']; ?>" disabled class="Polaris-TextField__Input freeProductName<?php echo $data['membership_group_id']; ?>" type="text" aria-labelledby="PolarisTextField20Label" aria-invalid="false" value="<?php echo !empty($data['Free_gift_uponsignup_productName']) ? $data['Free_gift_uponsignup_productName'] : ''; ?>">
                                                                                <div class="Polaris-TextField__Backdrop"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-FormLayout__Item">
                                                                <div class="">
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label id="PolarisTextField21Label" for="PolarisTextField21" class="Polaris-Label__Text">Variant</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Connected">
                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                            <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                                                                <input edit-free-gift_selected_variantid="<?php echo !empty($data['perk_free_gift_variant_id']) ? $data['perk_free_gift_variant_id'] : ''; ?>" name="perk-free_gift-variant_id" id="Free-gift_uponsignup_variantName-<?php echo $data['membership_group_id']; ?>" disabled class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField21Label" aria-invalid="false" value="<?php echo !empty($data['gift_uponsignup_variantName']) ? $data['gift_uponsignup_variantName'] : ''; ?>">
                                                                                <div class="Polaris-TextField__Backdrop"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-FormLayout__Item">
                                                                <div>
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label class="Polaris-Label__Text">&nbsp;</label>
                                                                        </div>
                                                                    </div>
                                                                    <button class="Polaris-Button edit-remove_free_gift_upon_signup freeGiftEditPercentValue-<?php echo $data['membership_group_id']; ?>" edit-getselectedtier_value="<?php echo $data['membership_group_id']; ?>" type="button">
                                                                        <span class="Polaris-Button__Content">
                                                                            <span class="Polaris-Button__Icon">
                                                                                <span class="Polaris-Icon">
                                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                        <path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </span>
                                                                        </span>
                                                                    </button>
                                                                    <input type="hidden" name="edit-perk-free_gift-product_id" id="edit-perk-free_gift-product_id-<?php echo $data['membership_group_id']; ?>" value="<?php echo !empty($data['perk_free_gift_product_id']) ? $data['perk_free_gift_product_id'] : ''; ?>">
                                                                    <input type="hidden" name="edit-perk-free_gift-variant_id" id="edit-perk-free_gift-variant_id-<?php echo $data['membership_group_id']; ?>" value="<?php echo !empty($data['perk_free_gift_variant_id']) ? $data['perk_free_gift_variant_id'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div role="group" class="Polaris-FormLayout--grouped">
                                                        <div class="Polaris-FormLayout__Items">
                                                            <div class="Polaris-FormLayout__Item">
                                                                <div class="">
                                                                    <div class="Polaris-Labelled__LabelWrapper">
                                                                        <div class="Polaris-Label">
                                                                            <label id="PolarisSelect11Label" for="edit-free_gift_uponsignupSelectedDays-<?php echo $data['membership_group_id']; ?>" class="Polaris-Label__Text">To keep new members engaged longer, we recommend shipping the gift later.</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="Polaris-Select">
                                                                        <select edit-get_tiername="<?php echo $data['perks_type_value']; ?>" id="edit-free_gift_uponsignupSelectedDays-<?php echo $data['membership_group_id']; ?>" name="edit-perk-free_gift-delay-<?php echo $data['membership_group_id']; ?>" class="Polaris-Select__Input edit-select_gift_uponsignup" aria-invalid="false">
                                                                            <option value="Immediately after signup" edit-free_gift_uponsignupSelected_Value="0" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '0') ? 'selected' : ''; ?>>Immediately after signup</option>
                                                                            <option value="1 day" edit-free_gift_uponsignupSelected_Value="1" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '1') ? 'selected' : ''; ?>>1 day</option>
                                                                            <option value="2 days" edit-free_gift_uponsignupSelected_Value="2" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '2') ? 'selected' : ''; ?>>2 days</option>
                                                                            <option value="3 days" edit-free_gift_uponsignupSelected_Value="3" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '3') ? 'selected' : ''; ?>>3 days</option>
                                                                            <option value="4 days" edit-free_gift_uponsignupSelected_Value="4" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '4') ? 'selected' : ''; ?>>4 days</option>
                                                                            <option value="5 days" edit-free_gift_uponsignupSelected_Value="5" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '5') ? 'selected' : ''; ?>>5 days</option>
                                                                            <option value="6 days" edit-free_gift_uponsignupSelected_Value="6" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '6') ? 'selected' : ''; ?>>6 days</option>
                                                                            <option value="7 days" edit-free_gift_uponsignupSelected_Value="7" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '7') ? 'selected' : ''; ?>>7 days</option>
                                                                            <option value="14 days" edit-free_gift_uponsignupSelected_Value="14" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '14') ? 'selected' : ''; ?>>14 days</option>
                                                                            <option value="30 days" edit-free_gift_uponsignupSelected_Value="30" <?php echo ($data['free_gift_uponsignupSelected_Value'] == '30') ? 'selected' : ''; ?>>30 days</option>
                                                                        </select>
                                                                        <div class="Polaris-Select__Content" aria-hidden="true">
                                                                            <span class="Polaris-Select__SelectedOption edit-perk-free_gift-delay-<?php echo $data['membership_group_id']; ?>">
                                                                                <?php echo ($data['free_gift_uponsignupSelectedDays'] == 'Immediately_after_signup') ? 'Immediately after signup' : $data['free_gift_uponsignupSelectedDays']; ?>
                                                                            </span>
                                                                            <span class="Polaris-Select__Icon">
                                                                                <span class="Polaris-Icon">
                                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                        <path d="M7.676 9h4.648c.563 0 .879-.603.53-1.014l-2.323-2.746a.708.708 0 0 0-1.062 0l-2.324 2.746c-.347.411-.032 1.014.531 1.014Zm4.648 2h-4.648c-.563 0-.878.603-.53 1.014l2.323 2.746c.27.32.792.32 1.062 0l2.323-2.746c.349-.411.033-1.014-.53-1.014Z"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                        <div class="Polaris-Select__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <button class="Polaris-Button edit-free_gift-upon-signUp" id="edit-free_gift-upon-signUp-<?php echo $data['membership_group_id']; ?>" edit-free_gift-sigup-attr="<?php echo $data['perks_type_value']; ?>" edit-member-group-id="<?php echo $data['membership_group_id']; ?>" type="button">
                                                        <span class="Polaris-Button__Content">
                                                            <span class="Polaris-Button__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                            <span class="Polaris-Button__Text">Select a product</span>
                                                        </span>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <div class="edit-free_gift-upon-signUp-Error-<?php echo $data['membership_group_id']; ?>" style="display: none;color: red;">Required field!</div>
                                        </div>
                                    </div>


                                    <!-- Early Access Sale field start -->
                                    <div class="Polaris-Card__Section sd_perk_check_div4">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <label class="Polaris-Choice" for="edit-PolarisCheckbox5-<?php echo htmlspecialchars($data['membership_group_id']); ?>">
                                                    <span class="Polaris-Choice__Control">
                                                        <span class="Polaris-Checkbox">
                                                            <input
                                                                id="edit-PolarisCheckbox5-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                name="earlyAccess"
                                                                type="checkbox"
                                                                class="Polaris-Checkbox__Input earlyAccessEditCheckbox-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                aria-invalid="false"
                                                                role="checkbox"
                                                                aria-checked="true"
                                                                value="1"
                                                                <?php if ($data['early_access_checked_value'] == '1') echo 'checked'; ?>>
                                                            <span class="Polaris-Checkbox__Backdrop"></span>
                                                            <span class="Polaris-Checkbox__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span class="Polaris-Choice__Label">Early sale access</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div style="<?php echo ($data['early_access_checked_value'] == '1') ? 'max-height: none !important; display: block !important; overflow: visible !important;' : 'max-height: none; overflow: visible; display: none;'; ?>" class="edit-free_gift-upon-signUp-<?php echo htmlspecialchars($data['membership_group_id']); ?>" aria-expanded="true">
                                            <div class="Polaris-TextContainer">
                                                <!-- <p>it so non-members can't use it.</p> -->
                                            </div>
                                            <div class="Polaris-FormLayout">
                                                <div role="group" class="Polaris-FormLayout--condensed">
                                                    <div class="Polaris-FormLayout__Items">
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div>
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label id="Polar6isTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Number of early days</label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Connected">
                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                        <div class="Polaris-TextField" style="width: 400px;">
                                                                            <input
                                                                                name="perk-early-sale-access"
                                                                                id="edit-EarlySaleAccessDays-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                                class="Polaris-TextField__Input edit-EarlySaleAccessDays-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                                type="number"
                                                                                aria-labelledby="PolarisTextField8Label"
                                                                                aria-invalid="false"
                                                                                placeholder="maximum 9 days"
                                                                                value="<?php echo htmlspecialchars($data['no_of_sale_days'] ?? ''); ?>"
                                                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                                maxlength="1">
                                                                            <div class="Polaris-TextField__Backdrop"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="edit-EarlySaleAccess-Error-<?php echo htmlspecialchars($data['membership_group_id']); ?> sd_perkViewErrors" style="display: none;color: red;">
                                                                    No. of early days is required!
                                                                </p>
                                                                <div class="Polaris-TextContainer">
                                                                    <br>
                                                                    <p>Configure sale details in the sidebar by enabling "early sale access" and setting the start and end dates. Include sale products or collections and specify discounts.</p>
                                                                    <p>Enabling early sale access allows customers with this perk to access the sale before others. For example, if the sale starts on January 16th and ends on January 23rd, with early access set to 3 days, those with the perk can access the sale on January 13th. Others gain access on January 16th.</p>
                                                                    <p>Need help with early sale access? Contact us with your idea.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="Polaris-TextContainer"></div>
                                        </div>
                                    </div>


                                    <div class="Polaris-Card__Section sd_perk_check_div3" style="display:none">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <label class="Polaris-Choice" for="edit-PolarisCheckbox9-<?php echo htmlspecialchars($data['membership_group_id']); ?>">
                                                    <span class="Polaris-Choice__Control">
                                                        <span class="Polaris-Checkbox">
                                                            <?php $bbbbb = $data->birthday_rewards ?? '0'; ?>
                                                            <input
                                                                id="edit-PolarisCheckbox9-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                name="birthday-reward"
                                                                type="checkbox"
                                                                class="Polaris-Checkbox__Input birthdayEditCheckbox-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                aria-invalid="false"
                                                                role="checkbox"
                                                                aria-checked="true"
                                                                value="1"
                                                                <?php if ($bbbbb == '1') echo 'checked'; ?>>
                                                            <span class="Polaris-Checkbox__Backdrop"></span>
                                                            <span class="Polaris-Checkbox__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span class="Polaris-Choice__Label">Birthday Rewards</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div style="<?php echo ($data->birthday_rewards == '1') ? 'max-height: none !important; display: block !important; overflow: visible !important;' : 'max-height: none; overflow: visible; display: none;'; ?>" class="Polaris-Collapsible" aria-expanded="true">
                                            <div class="Polaris-TextContainer" style="margin-top:2rem;">
                                                <p>If you check the "Birthday Rewards" checkbox, it means you will give rewards to members on their birthday who purchase this plan.</p>
                                                <p>You can set the birthday rewards by clicking on the "Birthday Rewards" tab in the sidebar. After that, you can customize the birthday reward settings.</p>
                                            </div>
                                        </div>
                                    </div>

                                 
                                <!-- 
                                    <div class="Polaris-Card__Section sd_perk_check_div3">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <label class="Polaris-Choice" for="edit-PolarisCheckbox7-<?php echo htmlspecialchars($data['membership_group_id']); ?>">
                                                    <span class="Polaris-Choice__Control">
                                                        <span class="Polaris-Checkbox">
                                                            <input
                                                                id="edit-PolarisCheckbox7-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                name="perk-custom"
                                                                type="checkbox"
                                                                class="Polaris-Checkbox__Input customEditCheckbox-<?php echo htmlspecialchars($data['membership_group_id']); ?>"
                                                                aria-invalid="false"
                                                                role="checkbox"
                                                                aria-checked="true"
                                                                value="1"
                                                                <?php if ($data->custom_perk_checkbox == '1') echo 'checked'; ?>>
                                                            <span class="Polaris-Checkbox__Backdrop"></span>
                                                            <span class="Polaris-Checkbox__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span class="Polaris-Choice__Label">Custom perk</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div style="<?php echo ($data->custom_perk_checkbox == '1') ? 'max-height: none !important; display: block !important; overflow: visible !important;' : 'max-height: none; overflow: visible; display: none;'; ?>" class="Polaris-Collapsible" aria-expanded="true">
                                            <div class="Polaris-TextContainer" style="margin-top:2rem;">
                                                <p>All active members are tagged in Shopify with the tags
                                                    &nbsp;
                                                    <span class="Polaris-Tag"><span title="CM-,1160, Membership: Active" class="Polaris-Tag__TagText">sd_membership_customer : Active.</span></span>&nbsp;
                                                    and &nbsp;
                                                    <span class="Polaris-Tag"><span title="CM-,1160, Tier: ,tier1" class="Polaris-Tag__TagText"><?php echo htmlspecialchars($RequestedMembership_Name[$arrayNo]->unique_handle ?? ''); ?> : Active</span></span>
                                                    Using these tags, you can identify your active members.
                                                </p>
                                                <p>Having trouble with your custom perk? Reach out to us with your idea.</p>
                                            </div>
                                            <button class="Polaris-Button Polaris-Button--primary edit-sd_contact_us" type="button">
                                                <span class="Polaris-Button__Content">
                                                    <span class="Polaris-Button__Text sd_handle_navigation_redirect" href="javascript:void(0)" value="/admin/contactUs.php">Contact us</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                 -->
                                </div>
                        <?php $arrayNo++;
                            }
                        }

                        ?>
                    </div>
                    <div class="membership_edit_perkBtn">
                        <div class="Polaris-Stack__Item">
                            <button class="Polaris-Button Polaris-Button--primary" type="button" id="previous_EditDiscountButton" style="display: none;">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Text">Previous</span>
                                </span>
                            </button>
                        </div>

                        <div class="Polaris-Stack__Item">
                            <button class="Polaris-Button Polaris-Button--primary" type="button" id="next_EditDiscountButton">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Text">Next</span>
                                </span>
                            </button>
                        </div>

                        <div class="Polaris-Stack__Item">
                            <button class="Polaris-Button Polaris-Button--primary" type="button" id="edit_DiscountButton" style="display: none;">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Text">Save</span>
                                </span>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
    </form>
</div>
</div>
<?php

//  }else {
//     include('create-perks.php');
// }
?>

<?php
// include("../footer.php");
?>

<script>
    var app_redirect_url = '/membership/';
</script>