<style>
    .css-1mq8drp {
        padding: 8px;
        margin: auto;
        width: 144px;
        height: 144px;
        cursor: pointer;
        overflow: hidden;
        border-radius: 50%;
        border: 1px dashed rgba(145, 158, 171, 0.2);
    }

    .css-1t5p1px {
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 50%;
        position: relative;
    }

    .discount_form .Polaris-FormLayout {
        background: rgb(20 30 45);
        padding: 20px 20px 20px 0;
        border-radius: 10px;
    }

    .submit_discount_button {
        margin-left: 1.6rem;
    }

    .discount-grid-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .polaris-main-list-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .polaris-inner-list ul {
        width: 100%;
        padding-left: 8px;
    }

    .polaris-inner-list .Polaris-ResourceItem__ItemWrapper {
        border: 1px solid #fff;
        border-radius: 6px !important;
        padding: 2px 10px;
    }

    .polaris-inner-list .Polaris-ResourceItem__Container {
        padding: 0;
        align-items: center;
    }

    .polaris-inner-list .Polaris-ResourceItem__Content h3 {
        color: #fff;
    }

    .polaris-inner-list .Polaris-ResourceItem__ListItem {
        margin-bottom: 10px;
    }

    .polaris-inner-list .Polaris-ResourceItem__ListItem:last-child {
        margin-bottom: 0;
    }

    .polaris-inner-list .Polaris-ResourceItem__ListItem+.Polaris-ResourceItem__ListItem {
        border-top: 0;
    }

    .polaris-inner-list .Polaris-Avatar img {
        width: 23px;
        height: 23px;
    }

    .polaris-inner-list .Polaris-Avatar--sizeMedium {
        width: 23px;
    }

    .polaris-inner-list .Polaris-ResourceItem__Container {
        min-height: 3.4rem;
    }

    @media(max-width:576px) {

        .discount-grid-box,
        .polaris-main-list-section {
            grid-template-columns: 1fr;
        }
    }

    /****** cross icon start */
    .Polaris-ResourceItem__ItemWrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        /* optional spacing between item and remove button */
        padding: 6px 10px;
        /* border: 1px solid #dfe3e8;
    border-radius: 6px;
    background-color: #ffffff; */
    }

    .Polaris-discountProductItem {
        flex: 1;
        /* Take up remaining width */
        display: flex;
        align-items: center;
    }

    .Polaris-discountRemoveProductButton {
        background: transparent;
        border: none;
        padding: 6px;
        cursor: pointer;
        align-self: center;
    }

    /****** cross icon end */

    /***** checkbox start here */

    .checkbox-wrapper-21 {
        display: flex;
        gap: 10px;
    }

    .checkbox-wrapper-21 h3 {
        color: #fff;
        font-size: 14px;
    }

    .discount_checkbox {
        margin: 1.6rem 0;
        margin-left: 2rem;
    }

    .discount_form .CreateDiscountPlan.sd_button {
        margin-top: 0;
        background: transparent;
        border-color: #fff;
        padding: 8px 10px;
        margin-bottom: 1rem;
    }

    .discount_form .CreateDiscountPlan.sd_button:hover {
        background: #400790;
        border-color: #400790;
    }

    .sd_collection_button {
        margin-top: 1.6rem;
    }

    /***** checkbox end here */
</style>


<div class="sd-prefix-main">
    <div class="sd-subscription-page-title">
        <div class="Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">
            <div class="Polaris-Page-Header__Row">
                <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <nav role="navigation">
                        <a class="Polaris-Breadcrumbs__Breadcrumb  back_button_memberplan" onclick="backMemberPlans();">
                            <span class="Polaris-Breadcrumbs__ContentWrapper">
                                <span class="Polaris-Breadcrumbs__Icon">
                                    <span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z"></path>
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
                            <label class="Polaris-Heading">Create discount</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="Polaris-Layout__Section sd-dashboard-page">

        <div class="Polaris-Page discount_form">
            <div class="Polaris-Page__Content">
                <div style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb; --p-frame-offset:0px;">

                    <div class="Polaris-FormLayout">

                        <div class="discount-grid-box">

                            <!-- Discount Name -->
                            <div class="Polaris-FormLayout__Items">
                                <div class="Polaris-FormLayout__Item">
                                    <div class="Polaris-Label"><label id="PolarisTextField3Label" for="PolarisTextField3" class="Polaris-Label__Text">Discount Name</label></div>
                                    <div class="Polaris-Connected">
                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                            <div class="Polaris-TextField Polaris-discountFormDisplay">
                                                <input class="Polaris-TextField__Input" type="text" maxlength="50" placeholder="Enter Discount Name" id="discount_name" name="discount_name" autocomplete="off">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php echo discountPlanFormError('discount_plan_name_error'); ?>

                                </div>

                            </div>

                            <!-- Discount Type (Percentage) -->
                            <div class="Polaris-FormLayout__Items">
                                <div class="Polaris-FormLayout__Item">
                                    <div class="Polaris-Label"><label id="PolarisTextField3Label" for="PolarisTextField3" class="Polaris-Label__Text">Discount %</label></div>
                                    <div class="Polaris-Connected">
                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                            <div class="Polaris-TextField Polaris-discountFormDisplay">
                                                <input class="Polaris-TextField__Input" type="number" placeholder="Enter Discount Percentage" id="discount_value" name="discount_value" min="0" max="100">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo discountPlanFormError('discount_plan_value_error'); ?>
                                </div>

                            </div>

                        </div>


                        <div class="polaris-main-list-section">
                            <div class="polaris-inner-list">
                                <!-- Applicable Products -->
                                <div class="Polaris-FormLayout__Items">
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="Polaris-Label">
                                            <label class="Polaris-Label__Text">Applicable Products</label>
                                        </div>

                                        <button parent-id="create-discount-section-products-show" class="Polaris-Button Polaris-Button--primary Create-discount-plan sd_button add_new_product_button" type="button">
                                            <span class="Polaris-Button__Content">
                                                <span class="Polaris-Button__Text">Select Products</span>
                                            </span>
                                        </button>

                                        <?php echo discountPlanFormError('discount_product_error'); ?>
                                    </div>
                                </div>

                                <!-- List of Products -->
                                <div class="Polaris-FormLayout__Item">

                                    <div>
                                        <div class="Polaris-Stack Polaris-Stack--spacingTight Polaris-discountFormDisplay show_selected_discount_products" id="selected-discount-products-list">
                                            <ul class="Polaris-ResourceList" id="discount-section-products-show-list">

                                                <!-- Example Product Item -->
                                                <li class="Polaris-ResourceItem__ListItem" id="discount_display_product_50566832619840">
                                                    <div class="Polaris-ResourceItem__ItemWrapper">
                                                        <div class="Polaris-ResourceItem Polaris-ResourceItem--selectable Polaris-discountProductItem">
                                                            <div class="Polaris-ResourceItem__Container">

                                                                <div class="Polaris-ResourceItem__Owned">
                                                                    <div class="Polaris-ResourceItem__Media">
                                                                        <span role="img" class="Polaris-Avatar Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage">
                                                                            <img src="https://shinedezigninfotech.com/stagesub/application/assets/images/no-image.png" class="Polaris-Avatar__Image" alt="Product Image" role="presentation">
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="Polaris-ResourceItem__Content">
                                                                    <h3>
                                                                        <span class="Polaris-TextStyle--variationStrong">12wedrfvg 1qwd</span>
                                                                    </h3>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button type="button" variant-id="50566832619840" product-id="9787155743040" class="Polaris-Tag__Button Polaris-discountRemoveProductButton" aria-label="Remove product">
                                                            <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                    <path d="m11.414 10 4.293-4.293a.999.999 0 1 0-1.414-1.414L10 8.586 5.707 4.293a.999.999 0 1 0-1.414 1.414L8.586 10l-4.293 4.293a.999.999 0 1 0 1.414 1.414L10 11.414l4.293 4.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z"></path>
                                                                </svg>
                                                            </span>
                                                        </button>

                                                    </div>
                                                </li>


                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="polaris-inner-list">
                                <!-- Applicable Collection -->
                                <div class="Polaris-FormLayout__Items">
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="Polaris-Label">
                                            <label class="Polaris-Label__Text">Applicable Collections</label>
                                        </div>

                                        <button parent-id="create-discount-section-collection-show" class="Polaris-Button Polaris-Button--primary sd_collection_button Create-discount-plan sd_button add_new_discount_collection" type="button">
                                            <span class="Polaris-Button__Content">
                                                <span class="Polaris-Button__Text">Select Collection</span>
                                            </span>
                                        </button>

                                        <?php echo discountPlanFormError('discount_collection_error'); ?>
                                    </div>
                                </div>


                                <!-- List of Collection -->
                                <div class="Polaris-FormLayout__Item">

                                    <div>
                                        <div class="Polaris-Stack Polaris-Stack--spacingTight Polaris-discountFormDisplay show_selected_discount_products" id="selected-discount-collection-list">
                                            <ul class="Polaris-ResourceList" id="discount-section-collection-show-list">

                                                <!-- Example collection Item -->
                                                <li class="Polaris-ResourceItem__ListItem" id="discount_display_product_50566832619840">
                                                    <div class="Polaris-ResourceItem__ItemWrapper">
                                                        <div class="Polaris-ResourceItem Polaris-ResourceItem--selectable Polaris-discountProductItem">
                                                            <div class="Polaris-ResourceItem__Container">

                                                                <div class="Polaris-ResourceItem__Owned">
                                                                    <div class="Polaris-ResourceItem__Media">
                                                                        <span role="img" class="Polaris-Avatar Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage">
                                                                            <img src="https://shinedezigninfotech.com/stagesub/application/assets/images/no-image.png" class="Polaris-Avatar__Image" alt="Product Image" role="presentation">
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="Polaris-ResourceItem__Content">
                                                                    <h3>
                                                                        <span class="Polaris-TextStyle--variationStrong">12wedrfvg 1qwd</span>
                                                                    </h3>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button type="button" variant-id="50566832619840" product-id="9787155743040" class="Polaris-Tag__Button Polaris-discountRemoveProductButton" aria-label="Remove product">
                                                            <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                    <path d="m11.414 10 4.293-4.293a.999.999 0 1 0-1.414-1.414L10 8.586 5.707 4.293a.999.999 0 1 0-1.414 1.414L8.586 10l-4.293 4.293a.999.999 0 1 0 1.414 1.414L10 11.414l4.293 4.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z"></path>
                                                                </svg>
                                                            </span>
                                                        </button>

                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Enable/Disable Automatic Discount -->
                        <div class="Polaris-FormLayout__Items discount_checkbox">
                            <div class="checkbox-wrapper-21">
                                <!-- <label class="control control--checkbox" for="enable_discount">
                                        Enable automatic discount
                                        <input type="checkbox" id="enable_discount" name="enable_discount">
                                        <div class="control__indicator"></div>
                                    </label> -->

                                <label class="switch">

                                    <input type="checkbox" class="email_template_notification" id="enable_discount" name="enable_discount" checked="">
                                    <span class="slider round"></span>

                                </label>
                                <h3> Enable automatic discount</h3>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="button" class="Polaris-Button Polaris-Button--primary submit_discount_button" id="sd_add_edit_discount">
                            <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Text">Save</span>
                            </span>
                        </button>

                        <!-- Response/Error -->
                        <div class="error"></div>
                        <div class="response"></div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</div>