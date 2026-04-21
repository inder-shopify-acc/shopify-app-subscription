<?php
// echo "scjfidjrjh";
// die;
if(isset($_REQUEST['member_plan_id'])) {
    $Requested_MembershipPlanID = $_REQUEST['member_plan_id'];

}else if(isset($_REQUEST['get_member_id'])) {
    $Requested_MembershipPlanID = $_REQUEST['get_member_id'];

}
// $RequestedShop_Name = $request->all()['shop'];

// $storeDetails = $this->getStoreDetailsByDomain($store);

$sql = "
        SELECT *
        FROM membership_plans
        INNER JOIN membership_plan_groups
            ON membership_plan_groups.membership_plan_id = membership_plans.id
        WHERE membership_plans.store = :store
          AND membership_plans.id = :plan_id
    ";
// echo $sql;die;
$stmt = $db->prepare($sql);
$stmt->execute([
    ':store' => $store,
    ':plan_id' => $Requested_MembershipPlanID
]);

$member_plans_data = $stmt->fetchAll(PDO::FETCH_OBJ);
// echo "hdjhjhdjd";
// print_r($member_plans_data);die;

?>
<style type="text/css">
    #sd_selected_existing_collection_id {
        width: 100%;
        margin-left: -20px;
    }

    .Polaris-TextContainer {
        margin-bottom: 2rem;
    }

    .Polaris-Collapsible {
        max-height: none !important;
    }

    .delete-selected-item {
        cursor: pointer;
    }
</style>


<div class="sd-prefix-main">

    <div class="sd-subscription-page-title">
        <div
            class="Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">
            <div class="Polaris-Page-Header__Row">
                <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <nav role="navigation">
                        <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership" onclick="handleLinkRedirect('/memberships/memberships.php')">
                            <span class="Polaris-Breadcrumbs__ContentWrapper"> <span class="Polaris-Breadcrumbs__Icon">
                                    <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                            focusable="false" aria-hidden="true">
                                            <path
                                                d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">
                                            </path>
                                        </svg>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </nav>
                </div>
                <div class="Polaris-Page-Header__TitleWrapper">
                    <div>
                        <div class="sd-planList-withBtn">
                            <label class="Polaris-Heading">Create perks</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form id="update_membership_type_perks_form" action="" method="POST">
        <div class="Polaris-Layout">
            <div class="Polaris-Layout__AnnotatedSection">
                <div class="Polaris-Layout__AnnotationWrapper">
                    <?php
                    $tierGroupIdArray = [];

                    if (!empty($member_plans_data)) {
                        foreach ($member_plans_data as $index => $data) {
                            // print_r($data); 
                            $tierName = trim($data->membership_group_id);
                            $tierGroupIdArray[] = $tierName;
                        }
                    }
                    ?>
                    <input type="hidden" class="tierGroupIdArray0" value="<?php echo isset($tierGroupIdArray[0]) ? $tierGroupIdArray[0] : ''; ?>">
                    <input type="hidden" class="tierGroupIdArray1" value="<?php echo isset($tierGroupIdArray[1]) ? $tierGroupIdArray[1] : ''; ?>">
                    <input type="hidden" class="tierGroupIdArray2" value="<?php echo isset($tierGroupIdArray[2]) ? $tierGroupIdArray[2] : ''; ?>">

                    <?php if (!empty($member_plans_data) && count($member_plans_data) > 0) { ?>
                        <?php foreach ($member_plans_data as $index => $data) { ?>
                            <div class="Polaris-Layout__AnnotationContent TierSelected-<?php echo htmlspecialchars($data->membership_group_id); ?> edit-perks-form-selected"
                                perks-type="<?php echo htmlspecialchars($data->membership_group_name); ?>"
                                membership-group-id="<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                membership_plan_id="<?php echo htmlspecialchars($data->membership_plan_id); ?>">
                                <div class="Polaris-Card__Section">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item selectedPerkTierName">
                                            <h3>Tier Name : <?php echo htmlspecialchars($data->membership_group_name); ?></h3>
                                        </div>
                                    </div>
                                    <p class="<?php echo htmlspecialchars('tier_perk_Error_' . $data->membership_group_id); ?> sd_perkViewErrors" style="color:red; display:none"></p>
                                </div>
                                <div class="Polaris-Card__Section perkCheckDiv sd_perk_check_div1">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item ">
                                            <label class="Polaris-Choice"
                                                for="<?php echo htmlspecialchars('PolarisCheckbox2-' . $data->membership_group_id); ?>">
                                                <span class="Polaris-Choice__Control">
                                                    <span class="Polaris-Checkbox">
                                                        <input id="<?php echo htmlspecialchars('PolarisCheckbox2-' . $data->membership_group_id); ?>"
                                                            name="<?php echo htmlspecialchars('discount-' . $data->membership_group_id . '_option'); ?>"
                                                            checkbox-name="checkbox" type="checkbox"
                                                            class="Polaris-Checkbox__Input freeShippingCheckbox freeShipCheckbox-<?php echo $data->membership_group_id; ?>"
                                                            aria-invalid="false" role="checkbox" aria-checked="false" value="1">
                                                        <span class="Polaris-Checkbox__Backdrop"></span>
                                                        <span class="Polaris-Checkbox__Icon">
                                                            <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                                                    focusable="false" aria-hidden="true">
                                                                    <path
                                                                        d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                                <span class="Polaris-Choice__Label">Free shipping</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="max-height: none; overflow: visible;" class="Polaris-Collapsible" aria-expanded="false">
                                        <div class="Polaris-TextContainer">
                                            <div class="Polaris-FormLayout">
                                                <div role="group" class="Polaris-FormLayout--condensed">
                                                    <div class="Polaris-FormLayout__Items">
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div class="">
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Free shipping code</label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Connected">
                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                        <div class="Polaris-TextField">
                                                                            <input name="perk-discounted_shipping-code"
                                                                                id="free_shipingCode-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                                class="Polaris-TextField__Input test freeshipCoupanCode capitalDiscountInput checkCopanCode1 checkCodeExists freeShipValue-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                                type="text"
                                                                                aria-labelledby="PolarisTextField1Label"
                                                                                aria-invalid="false" value="">
                                                                            <div class="Polaris-TextField__Backdrop"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="free_shipingCode_Error_<?php echo htmlspecialchars($data->membership_group_id); ?> freeShipCodeError sd_perkViewErrors" style="color:red;display:none">
                                                                    Shipping code field is required!
                                                                </p>
                                                                <p class="ExistingFree_shipingCode_Error_<?php echo htmlspecialchars($data->membership_group_id); ?> ExistingFree_shipingCode_Error sd_perkViewErrors" style="color:red;display:none">
                                                                    Code Taken! Use another code!
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="Polaris-FormLayout__Item">
                                                            <div class="">
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label id="PolarisTextField9Label" for="freeShippingCode" class="Polaris-Label__Text">Minimum purchase amount/quantity</label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Connected">
                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                        <div class="Polaris-TextField">
                                                                            <select class="minimumDiscountCode-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                                id="minimumDiscountCode"
                                                                                minimumDiscountCode-attr="<?php echo htmlspecialchars($data->membership_group_id); ?>">
                                                                                <option value="none" selected>No minimum requirements</option>
                                                                                <option value="minPurchase_Amount">
                                                                                    Minimum purchase amount
                                                                                    (<?php echo !empty($currency_code) ? htmlspecialchars($currency_code) : ''; ?>)
                                                                                </option>
                                                                                <option value="minQuantity_items">Minimum quantity of items</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="minimumDiscountCode_Error_<?php echo htmlspecialchars($data->membership_group_id); ?> sd_perkViewErrors" style="color: red;display: none">
                                                                    Minimum purchase amount field is required!
                                                                </p>
                                                            </div>
                                                        </div>
  
                                                    </div>
                                                    <div class="Polaris-FormLayout__Item test-<?php echo htmlspecialchars($data->membership_group_id); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <p>
                                                Create a discount code that is only valid for customers with the tags &nbsp;
                                                <span class="Polaris-Tag">
                                                    <span title="CM-,1160, Membership: Active" class="Polaris-Tag__TagText">sd_membership_customer : Active</span>
                                                </span>
                                                &nbsp;and&nbsp;
                                                <span class="Polaris-Tag">
                                                    <span title="CM-,1160, Tier: ,tier1" class="Polaris-Tag__TagText">
                                                        <?php echo htmlspecialchars($data->unique_handle); ?> : Active
                                                    </span>
                                                </span>.
                                            </p>
                                            <p>
                                                For more information,
                                                <a class="Polaris-Link" href="https://shinedezign.gitbook.io/elite-memberships/" target="_blank">check out this help article</a>
                                                or
                                                <button type="button" class="Polaris-Link sd_handle_navigation_redirect" href="javascript:void(0)" value="/contact-us">contact us</button>.
                                            </p>
                                        </div>
                                    </div>

                                </div>
                                <div class="Polaris-Card__Section perkCheckDiv sd_perk_check_div2">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item ">
                                            <label class="Polaris-Choice" for="PolarisCheckbox3-<?php echo htmlspecialchars($data->membership_group_id); ?>">
                                                <span class="Polaris-Choice__Control">
                                                    <span class="Polaris-Checkbox">
                                                        <input id="PolarisCheckbox3-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                            checkbox-name="checkbox" name="discount" type="checkbox"
                                                            class="Polaris-Checkbox__Input discountCheckbox-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                            aria-invalid="false" role="checkbox" aria-checked="true" value="1">
                                                        <span class="Polaris-Checkbox__Backdrop"></span>
                                                        <span class="Polaris-Checkbox__Icon"><span class="Polaris-Icon">
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

                                    <div style="max-height: none; overflow: visible;" class="Polaris-Collapsible" aria-expanded="true">
                                        <div class="Polaris-TextContainer"></div>
                                        <div class="Polaris-FormLayout">
                                            <div role="group" class="Polaris-FormLayout--condensed">
                                                <div class="Polaris-FormLayout__Items">
                                                    <!-- Discount Code Field -->
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div>
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label">
                                                                    <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Code</label>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField">
                                                                        <input name="perk-discounted_products-code"
                                                                            id="DiscountProductCode_Field-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                            class="Polaris-TextField__Input discountCoupanCode capitalDiscountInput checkCodeExists checkCopanCode2 discountInputBox-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                            type="text" aria-labelledby="PolarisTextField8Label" aria-invalid="false" value="">
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="DiscountCode-Error-<?php echo htmlspecialchars($data->membership_group_id); ?> sd_perkViewErrors" style="display: none;color: red;">Discount code is required!</p>
                                                            <p class="Existing_DiscountCode-Error-<?php echo htmlspecialchars($data->membership_group_id); ?> Existing_DiscountCode-Error sd_perkViewErrors" style="display: none;color: red;">Discount code is already taken!</p>
                                                        </div>
                                                    </div>

                                                    <!-- Percentage Off Field -->
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div>
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label">
                                                                    <label id="PolarisTextField9Label" for="PolarisTextField9" class="Polaris-Label__Text">Percentage off</label>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField">
                                                                        <input name="perk-discounted_products-amount"
                                                                            id="DiscountCodePercentage-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                            class="Polaris-TextField__Input Polaris-TextField__Input--suffixed discountpercent-<?php echo htmlspecialchars($data->membership_group_id); ?> numberPercentage"
                                                                            type="number" aria-labelledby="PolarisTextField9Label PolarisTextField9Suffix" aria-invalid="false" value="">
                                                                        <div class="Polaris-TextField__Suffix" id="PolarisTextField9Suffix">% off</div>
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="DiscountPercentage-Error-<?php echo htmlspecialchars($data->membership_group_id); ?> discountCodeError sd_perkViewErrors" style="display: none;color: red;">Discount % is required!</p>
                                                        </div>
                                                    </div>

                                                    <!-- Discount Applies To -->
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div>
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label">
                                                                    <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Discount applies to</label>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Select">
                                                                <select id="PolarisSelect-<?php echo htmlspecialchars($data->membership_group_id); ?>"
                                                                    name="perk-discounted_products-applies_to"
                                                                    class="Polaris-Select__Input DiscountAppliedSelect_box"
                                                                    aria-invalid="false"
                                                                    DiscountAppliedSelectedTier_Value="<?php echo htmlspecialchars($data->membership_group_id); ?>">
                                                                    <option value="all">All products</option>
                                                                    <option value="collection">Specific collection</option>
                                                                    <option value="product">Specific product</option>
                                                                </select>
                                                                <div class="Polaris-Select__Content" aria-hidden="true">
                                                                    <span class="Polaris-Select__SelectedOption">All products</span>
                                                                    <span class="Polaris-Select__Icon select-collection-product">
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

                                            <div class="perksDiscountApplies-<?php echo htmlspecialchars($data->membership_group_id); ?>"></div>
                                        </div>

                                        <div class="Polaris-TextContainer">
                                            <p></p>
                                            <p>
                                                Specify that products should be discounted when the following tags are present on the Shopify customer: &nbsp;
                                                <span class="Polaris-Tag">
                                                    <span title="CM-,1160, Membership: Active" class="Polaris-Tag__TagText">sd_membership_customer : Active</span>
                                                </span>
                                                &nbsp; and &nbsp;
                                                <span class="Polaris-Tag">
                                                    <span title="CM-,1160, Tier: ,tier1" class="Polaris-Tag__TagText">
                                                        <?php echo htmlspecialchars($data->unique_handle); ?> : Active
                                                    </span>
                                                </span>.
                                            </p>
                                            <p>
                                                For more information,
                                                <a class="Polaris-Link" href="https://shinedezign.gitbook.io/elite-memberships/" target="_blank">check out this help article</a>
                                                or
                                                <button type="button" class="Polaris-Link sd_handle_navigation_redirect" href="javascript:void(0)" value="/contact-us">contact us</button>.
                                            </p>
                                        </div>
                                    </div>
                                </div>



                                <div class="Polaris-Card__Section perkCheckDiv sd_perk_check_div3">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item ">
                                            <label class="Polaris-Choice" for="PolarisCheckbox4-<?php echo $data->membership_group_id; ?>">
                                                <span class="Polaris-Choice__Control">
                                                    <span class="Polaris-Checkbox">
                                                        <input id="PolarisCheckbox4-<?php echo $data->membership_group_id; ?>"
                                                            checkbox-name="checkbox" name="discount" type="checkbox"
                                                            class="Polaris-Checkbox__Input freeProductCheckbox-<?php echo $data->membership_group_id; ?>"
                                                            aria-invalid="false" role="checkbox" aria-checked="true" value="1">
                                                        <span class="Polaris-Checkbox__Backdrop"></span>
                                                        <span class="Polaris-Checkbox__Icon">
                                                            <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                    <path
                                                                        d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                                <span class="Polaris-Choice__Label">Free gift upon signup</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div style="max-height: none; overflow: visible; display: none;"
                                        class="free_gift-upon-signUp-<?php echo $data->membership_group_id; ?>" aria-expanded="true">
                                        <div class="Polaris-TextContainer">
                                            <p>Sending new members a free gift is a great way to show appreciation and build brand loyalty.</p>
                                        </div>
                                        <button class="Polaris-Button free_gift-upon-signUp"
                                            id="free_gift-upon-signUp-<?php echo $data->membership_group_id; ?>"
                                            free_gift-sigup-attr="<?php echo $data->membership_group_id; ?>" type="button">
                                            <span class="Polaris-Button__Content freeProductCheckboxvalue-<?php echo $data->membership_group_id; ?>">
                                                <span class="Polaris-Button__Icon">
                                                    <span class="Polaris-Icon">
                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                            <path
                                                                d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </span>
                                                <span class="Polaris-Button__Text">Select a product</span>
                                            </span>
                                        </button>

                                        <div class="free_gift-upon-signUp-Error-<?php echo $data->membership_group_id; ?> sd_perkViewErrors"
                                            style="display: none;color: red;">Select free product</div>
                                    </div>
                                </div>



                                <!-- Early Access Sale field start -->
                                <div class="Polaris-Card__Section perkCheckDiv sd_perk_check_div4">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item ">
                                            <label class="Polaris-Choice" for="PolarisCheckbox5-<?php echo $data->membership_group_id; ?>">
                                                <span class="Polaris-Choice__Control">
                                                    <span class="Polaris-Checkbox">
                                                        <input id="PolarisCheckbox5-<?php echo $data->membership_group_id; ?>"
                                                            checkbox-name="checkbox" name="earlyAccess" type="checkbox"
                                                            class="Polaris-Checkbox__Input earlyAccessCheckbox-<?php echo $data->membership_group_id; ?>"
                                                            aria-invalid="false" role="checkbox" aria-checked="true" value="1">
                                                        <span class="Polaris-Checkbox__Backdrop"></span>
                                                        <span class="Polaris-Checkbox__Icon"><span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                    <path
                                                                        d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                                <span class="Polaris-Choice__Label">Early sale access</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div style="max-height: none; overflow: visible;" class="Polaris-Collapsible" aria-expanded="true">
                                        <div class="Polaris-TextContainer"></div>
                                        <div class="Polaris-FormLayout">
                                            <div role="group" class="Polaris-FormLayout--condensed">
                                                <div class="Polaris-FormLayout__Items">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label">
                                                                    <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">
                                                                        No of early days
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField" style=" width: 400px;">
                                                                        <input name="perk-early-sale-access"
                                                                            id="EarlySaleAccessDays-<?php echo $data->membership_group_id; ?>"
                                                                            class="Polaris-TextField__Input Early-sale-<?php echo $data->membership_group_id; ?>"
                                                                            type="number"
                                                                            aria-labelledby="PolarisTextField8Label"
                                                                            aria-invalid="false"
                                                                            placeholder="maximum 9 days"
                                                                            value=""
                                                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                            maxlength="1">
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="EarlySaleAccess-Error-<?php echo $data->membership_group_id; ?> sd_perkViewErrors" style="display: none;color: red;">
                                                                No. of early days is required!
                                                            </p>
                                                            <div class="Polaris-TextContainer">
                                                                <br>
                                                                <p>Configure sale details in the sidebar by enabling "early sale access" and setting the start and end dates. Include sale products or collections and specify discounts.</p>
                                                                <p>Enabling early sale access allows customers with this perk to access the sale before others. For example, if the sale starts on January 16th and ends on January 23rd, with early access <br>set to 3 days, those with the perk can access the sale on January 13th. Others gain access on January 16th.</p>
                                                                <p>Need help with a early sale access? Contact us with your idea.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="Polaris-TextContainer"></div>
                                    </div>
                                </div>

                                <!-- Early access sale filed end  -->



                                <!-- Bitrthday Rewards filed start  -->
                                <div class="Polaris-Card__Section perkCheckDiv sd_perk_check_div3" style="display:none;">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item ">
                                            <label class="Polaris-Choice" for="PolarisCheckbox9-<?php echo $data->membership_group_id; ?>">
                                                <span class="Polaris-Choice__Control">
                                                    <span class="Polaris-Checkbox">
                                                        <input id="PolarisCheckbox9-<?php echo $data->membership_group_id; ?>"
                                                            checkbox-name="checkbox<?php echo $data->membership_group_id; ?>"
                                                            name="perk-custom" type="checkbox"
                                                            class="Polaris-Checkbox__Input birthdayBox-<?php echo $data->membership_group_id; ?>"
                                                            aria-invalid="false" role="checkbox" aria-checked="true" value="1">
                                                        <span class="Polaris-Checkbox__Backdrop"></span>
                                                        <span class="Polaris-Checkbox__Icon">
                                                            <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                    <path
                                                                        d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                                <span class="Polaris-Choice__Label">Birthday rewards</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div style="max-height: none; overflow: visible;" class="Polaris-Collapsible" aria-expanded="true">
                                        <div class="Polaris-TextContainer" style="margin-top:2rem;">
                                            <p>If you check the "Birthday Rewards" checkbox, it means you will give rewards to members on their birthday who purchase this plan.</p>
                                            <p>You can set the birthday rewards by clicking on the "Birthday Rewards" tab in the sidebar. After that, you can customize the birthday reward settings.</p>
                                        </div>
                                    </div>
                                </div>

                                <!--birthday rewards filed end  -->





                                <!-- Custom perk filed start  -->
                                 
                                <!-- <div class="Polaris-Card__Section perkCheckDiv sd_perk_check_div3">
                                    <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Item ">
                                            <label class="Polaris-Choice" for="PolarisCheckbox7-<?php echo $data->membership_group_id; ?>">
                                                <span class="Polaris-Choice__Control">
                                                    <span class="Polaris-Checkbox">
                                                        <input id="PolarisCheckbox7-<?php echo $data->membership_group_id; ?>"
                                                            checkbox-name="checkbox<?php echo $data->membership_group_id; ?>"
                                                            name="perk-custom" type="checkbox"
                                                            class="Polaris-Checkbox__Input customCheckbox-<?php echo $data->membership_group_id; ?>"
                                                            aria-invalid="false" role="checkbox" aria-checked="true" value="1">
                                                        <span class="Polaris-Checkbox__Backdrop"></span>
                                                        <span class="Polaris-Checkbox__Icon">
                                                            <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                    <path
                                                                        d="M14.723 6.237a.94.94 0 0 1 .053 1.277l-5.366 6.193a.834.834 0 0 1-.611.293.83.83 0 0 1-.622-.264l-2.927-3.097a.94.94 0 0 1 0-1.278.82.82 0 0 1 1.207 0l2.297 2.43 4.763-5.498a.821.821 0 0 1 1.206-.056Z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                                <span class="Polaris-Choice__Label">Custom perk</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="max-height: none; overflow: visible;" class="Polaris-Collapsible" aria-expanded="true">
                                        <div class="Polaris-TextContainer" style="margin-top:2rem;">
                                            <p>All active members are tagged in Shopify with the tags
                                                &nbsp;
                                                <span class="Polaris-Tag"><span title="CM-,1160, Membership: Active"
                                                        class="Polaris-Tag__TagText">sd_membership_customer : Active.</span></span>
                                                &nbsp; and &nbsp;
                                                <span class="Polaris-Tag"><span title="CM-,1160, Tier: ,tier1"
                                                        class="Polaris-Tag__TagText"><?php echo $data->unique_handle; ?> : Active</span></span>
                                                Using these tags, you can identify your active members and offer.
                                            </p>
                                            <p>Having trouble with your custom perk? Reach out to us with your idea.</p>
                                        </div>
                                        <button class="Polaris-Button Polaris-Button--primary sd_contact_us" type="button">
                                            <span class="Polaris-Button__Content">
                                                <span class="Polaris-Button__Text sd_handle_navigation_redirect"
                                                    href="javascript:void(0)" value="/contact-us">Contact us</span>
                                            </span>
                                        </button>
                                    </div>
                                </div> -->

                                <!--custom perk filed end  -->

                            </div>
                    <?php
                        }
                    }
                    ?>
                    </input>
                </div>
                <!-- Next Previous Save button here -->
                <div class="Polaris-Layout__Section">
                    <!-- <span id="messageSpan" style="color:red; float: right;">Next button is disabled.Please check any checkbox first.</span> -->
                    <div class="Polaris-PageActions">
                        <div class="Polaris-Stack Polaris-Stack--spacingTight Polaris-Stack--distributionTrailing">
                            <div class="Polaris-Stack__Item">
                                <button class="Polaris-Button Polaris-Button--primary" type="button"
                                    id="previous_DiscountButton" style="display: none;">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Previous
                                        </span>
                                    </span>
                                </button>
                            </div>

                            <div class="Polaris-Stack__Item">
                                <button class="Polaris-Button Polaris-Button--primary nextDiscountButton" type="button"
                                    id="next_DiscountButton">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Next
                                        </span>
                                    </span>
                                </button>
                            </div>

                            <div class="Polaris-Stack__Item">
                                <button class="Polaris-Button Polaris-Button--primary" type="button"
                                    id="save_DiscountButton" style="display: none;">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Save
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
</div>