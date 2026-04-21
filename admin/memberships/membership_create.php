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
    .css-ims6kp {
        display: flex;
        flex-direction: column;
        gap: 8px;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        z-index: 9;
        border-radius: 50%;
        position: absolute;
        color: rgb(145, 158, 171);
        background-color: rgba(145, 158, 171, 0.08);
        transition: opacity 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    }
    .css-x3wokz {
        width: 32px;
        height: 32px;
    }
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
                        <label class="Polaris-Heading">Create plans</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="" class="sd_create_memberPlan_wrapper">
        <div class="create_plangroup_left">
            <form method="post" id="member_plan_form">
                <input type="hidden" id="db_edit_subscriptionplan_id" value="">
                <input type="hidden" id="popular_plan_active" value="">
                        <div class="Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">
                            <div class="Polaris-Page-Header__Row">
                                <div class="Polaris-Page-Header__TitleWrapper">
                                    <div class="step-bar-wrapper">
                                        <ul class="step-wrapper">
                                            <li id="li_step1" class="active"><span><a>Step 1</a></span><a><svg class="icon icon-left"><use xlink:href="#icon-left"></use></svg></a></li>
                                            <li id="li_step2" class=""><span><a>Step 2</a></span><a><svg class="step step-mid"><use xlink:href="#icon-mid"></use></svg></a></li>
                                            <li id="li_step3" class=""><span><a>Step 3</a></span><a><svg class="step step-mid"><use xlink:href="#icon-mid"></use></svg></a></li>
                                        </ul>
                                    </div>
                                    <!-- <ul class="form-steps">
                                        <li id="li_step1" class="done"><span class="step-tick"><img
                                            src="<?php //echo config('custom.DOMAIN_NAME'); ?>/public/assets/images/TickMinor.svg"></span><span
                                            class="step-number display-hide-label">1</span></li>
                                        <li id="li_step2" class="active"><span class="step-tick display-hide-label"><img
                                            src="<?php //echo config('custom.DOMAIN_NAME'); ?>/public/assets/images/TickMinor.svg"></span><span
                                            class="step-number">2</span></li>
                                        <li id="li_step3" class=""><span class="step-tick display-hide-label"><img
                                            src="<?php //echo config('custom.DOMAIN_NAME'); ?>/public/assets/images/TickMinor.svg"></span><span
                                            class="step-number">3</span></li>
                                    </ul> -->
                                </div>
                            </div>
                        </div>




                    <!-- *******************************************************  STEP 1 ******************************************************-->
                    <div class="form-steps-tab subscription-create-step1">

                                    <div class="Polaris-Card__Section">
                                        <div class="Polaris-FormLayout">
                                            <div class="sd-boxshadow">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="">
                                                        <div class="Polaris-Labelled__LabelWrapper">
                                                            <div class="Polaris-Label">
                                                                <label id="PolarisTextField3Label" for="PolarisTextField3" class="Polaris-Label__Text">Member plan name</label>
                                                            </div>
                                                            <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext">Your customers will see the name you choose, so make sure it's a good one since it will be visible to the public.</span>
                                                            </div>
                                                        </div>
                                                        <div class="Polaris-Connected">
                                                            <div
                                                                class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField memberplan_name_field">
                                                                    <input
                                                                        autocomplete="off" class="Polaris-TextField__Input memberPlanNameValidation"
                                                                        type="text" aria-describedby="ruleContentError"
                                                                        aria-labelledby="ruleContentLabel"
                                                                        aria-invalid="true" value=""
                                                                        id="memberPlan_name" name="memberPlan_name"
                                                                        maxlength="50">
                                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                                </div>
                                                                <span
                                                                    class="memberPlan_name_error error_messages"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Stack Polaris-Stack--vertical">
                                                        <div class="Polaris-Stack__Item">
                                                            <div> <label class="Polaris-Choice" for="add_custom_product"><span class="Polaris-Choice__Control"><span class="Polaris-RadioButton"><input id="add_custom_product" name="add_member_product" type="radio" class="Polaris-RadioButton__Input" aria-describedby="optionalHelpText" checked="" value="custom_product"><span class="Polaris-RadioButton__Backdrop"></span></span></span><span class="Polaris-Choice__Label"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Add Custom Product</span></span></label>
                                                                <div class="Polaris-Choice__Descriptions">
                                                                    <div class="Polaris-Choice__HelpText" id="optionalHelpText"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular Polaris-Text--subdued">Membership will be added on the product which will be created by the app .</span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="Polaris-Stack__Item">
                                                            <div> <label class="Polaris-Choice" for="add_existing_product"><span class="Polaris-Choice__Control"><span class="Polaris-RadioButton"><input id="add_existing_product" name="add_member_product" type="radio" class="Polaris-RadioButton__Input" aria-describedby="disabledHelpText" value="existing_product"><span class="Polaris-RadioButton__Backdrop"></span></span></span><span class="Polaris-Choice__Label"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Add membership on existing product</span></span></label>
                                                                <div class="Polaris-Choice__Descriptions">
                                                                    <div class="Polaris-Choice__HelpText" id="disabledHelpText"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular Polaris-Text--subdued">Membership will be added on the store product which you select.</span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div> -->
                                                <div class="custom_product_data">
                                                    <!-- <div class="Polaris-FormLayout__Item ">  
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label"><label
                                                                    id="PolarisTextField3Label"
                                                                    for="PolarisTextField3"
                                                                    class="Polaris-Label__Text">Member plan page url</label>
                                                                </div>
                                                                <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext">This will be visible to your customers as the page's URL!</span>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div
                                                                    class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div
                                                                        class="Polaris-TextField Polaris-TextField--hasValue">
                                                                        <div class="Polaris-TextField__Prefix"
                                                                            id="PolarisTextField1-Prefix">
                                                                            https://<?php //echo $_GET['shop']; ?>/product/
                                                                        </div>
                                                                        <input type="text" name="product_page_url"
                                                                            id="product_page_url"
                                                                            class="Polaris-TextField__Input sd_validate_expration"
                                                                            aria-labelledby="PolarisTextField3Label"
                                                                            aria-invalid="false" value=""
                                                                            maxlength="200" autocomplete="off">
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="product_page_url_error error_messages"></span>
                                                        </div>
                                                    </div> -->
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label"><label
                                                                    id="PolarisTextField3Label"
                                                                    for="PolarisTextField3"
                                                                    class="Polaris-Label__Text">Member plan description <span class="optional-field" style="color: #fff;  font-size: 12px;">(Optional)</span></label>
                                                                </div>
                                                                <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext">This serves as the product description for your Shopify item, visible to your customers!</span>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div
                                                                    class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <textarea class="Polaris-TextField" id="member_product_description" type="text" value="" maxlength="1000"
                                                                        name="member_product_description" aria-labelledby="TextField6Label" aria-invalid="false" autocomplete="off"></textarea><span
                                                                        class="member_product_description_error error_messages"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label">
                                                                    <label id="PolarisTextField1Label"
                                                                        for="PolarisTextField1"
                                                                        class="Polaris-Label__Text">
                                                                    <span
                                                                        class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Member plan image url <span class="optional-field" style="color: #fff;  font-size: 12px;">(Optional)</span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="Polaris-Labelled__Action">
                                                                    <a target="_blank" class="Polaris-Link"
                                                                        href="https://<?php echo $_GET['shop']; ?>/admin/settings/files"
                                                                        rel="noopener noreferrer"
                                                                        data-polaris-unstyled="true">Don't have url? Click
                                                                    here</a>
                                                                    <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext">This will appear to your customers as they checkout.</span>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div
                                                                    class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div
                                                                        class="Polaris-TextField Polaris-TextField--hasValue">
                                                                        <input type="text" name="member_product_image"
                                                                            id="member_product_image"
                                                                            class="Polaris-TextField__Input sd_validate_expration"
                                                                            aria-labelledby="PolarisTextField3Label"
                                                                            aria-invalid="false" value=""
                                                                            autocomplete="off">
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="member_product_image_error error_messages"></span>
                                                    </div>
                                                </div>
                                                <div class="select_existing_product">
                                                    <!-- <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label"><label
                                                                    id="PolarisTextField3Label"
                                                                    for="PolarisTextField3"
                                                                    class="Polaris-Label__Text">Select product</label>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div
                                                                    class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField product_url_field">
                                                                        <button class="Polaris-Button" type="button"
                                                                            id="add_memberPlan_product"> <span
                                                                            class="Polaris-Button__Content"> <span
                                                                            class="Polaris-Button__Text">Add
                                                                        product</span> </span> </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sd_selected_existing_product"></div>
                                                        <span
                                                            class="existing_product_error error_messages"></span>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right-button-groups">
                                        <div class="Polaris-ButtonGroup step1_button_group">
                                            <div class="Polaris-ButtonGroup__Item"><button
                                                class="Polaris-Button Polaris-Button--primary subscription_validate_step1"
                                                type="button"><span class="Polaris-Button__Content"><span
                                                class="Polaris-Button__Text">Next</span></span></button>
                                            </div>
                                        </div>
                                        <div id="PolarisPortalsContainer"></div>
                                    </div>




                    </div>


                    <!-- *******************************************************  STEP 2 ******************************************************-->
                    <div
                        class="Polaris-Layout__AnnotatedSection sd-subscription-group-form form-steps-tab subscription-create-step2 display-hide-label">
                        <div class="Polaris-Layout__AnnotationWrapper create-selling-plan-form">
                            <div class="Polaris-Layout__AnnotationContent">
                                <div class="">
                                    <div class="Polaris-Card__Section">
                                        <div class="Polaris-FormLayout">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-TextContainer subscription-step-heading">
                                                    <h2 class="Polaris-Heading">Add member plan tier</h2>
                                                    <p> </p>
                                                </div>
                                            </div>
                                            <div class="sd_selling_form">
                                                <div class="Polaris-FormLayout__Items">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label"> <label id="TextField2Label"
                                                                    for="TextField2"
                                                                    class="Polaris-Label__Text">Tier name</label>
                                                                </div>
                                                                <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext">Name your tier, whether it's Bronze, Silver, Gold, or your only tier, which you can label as "Default." This name might be displayed to your customers.</span>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div
                                                                    class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField">
                                                                        <input type="hidden" id="new_card_no"
                                                                            value="">
                                                                        <input type="hidden" id="membergroupid"
                                                                            value="">
                                                                        <input type="hidden"
                                                                            id="edit_group_card_serial_no" value="">
                                                                        <input id="member_plan_tier_name" type="text"
                                                                            value=""
                                                                            class="Polaris-TextField__Input sd_validate_expration"
                                                                            maxlength="50" name="member_plan_tier_name"
                                                                            aria-labelledby="TextField6Label"
                                                                            aria-invalid="false" autocomplete="off">
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="member_plan_tier_name_error error_messages"></span>
                                                    </div>
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label"> <label id="TextField2Label"
                                                                    for="TextField2"
                                                                    class="Polaris-Label__Text">Unique customer tag</label>
                                                                </div>
                                                                <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext"> Choose an exclusive identifier for your tier. This won't be visible to your customers; it will solely be utilized within the tag appended to the backend Customer account.</span>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <div
                                                                    class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField">
                                                                        <input
                                                                            id="member_plan_tier_handle" type="text"
                                                                            value=""
                                                                            class="Polaris-TextField__Input sd_validate_expration"
                                                                            maxlength="50" name="member_plan_tier_handle"
                                                                            aria-labelledby="TextField6Label"
                                                                            aria-invalid="false" autocomplete="off">
                                                                        <div class="Polaris-TextField__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="member_plan_tier_handle_error error_messages"></span>
                                                    </div>
                                                </div>
                                                <div class="Polaris-FormLayout__Items">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="">
                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label"> <label id="TextField2Label" for="TextField2" class="Polaris-Label__Text">Tier description</label> </div>
                                                                <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                                                                <span class="tooltiptext">This serves as the plan description, visible to your customers!.</span>
                                                                </div>
                                                            </div>
                                                            <div class="Polaris-Connected">
                                                                <textarea class="Polaris-TextField" id="group_description" type="text" value=""
                                                                    class="Polaris-TextField__Input sd_validate_expration" maxlength="1000" name="group_description"
                                                                    aria-labelledby="TextField6Label" aria-invalid="false" autocomplete="off"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- sd_per_delivery_fields-->
                                                <div class="selling_plan_options">
                                                    <div class="Polaris-Card__Section member_plan_option_form" data-id="1">
                                                        <?php //echo selling_plan_option_form();
                                                            ?>
                                                    </div>
                                                </div>

                                                <!-- Free trial -->

                                                <div class="Polaris-FormLayout__Items">
                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="enable_copyright_box">

                                                            <div class="Polaris-Labelled__LabelWrapper">
                                                                <div class="Polaris-Label">
                                                                    <label id="TextFieldStatus" for="TextFieldStatus1" class="Polaris-Label__Text">Offer trial</label>
                                                                </div>
                                                            </div>

                                                            <div class="Polaris-Connected">
                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                    <div class="Polaris-TextField">
                                                                        <label class="switch">
                                                                            <input type="checkbox" name="member_offer_trial_status" id="member_offer_trial_period_status" class="member_offer_trial_period_status" value="">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tooltip">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                    <path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/>
                                                                    <path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/>
                                                                    <path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/>
                                                                </svg>
                                                                <span class="tooltiptext">
                                                                    A discount will be applied to the customer’s membership plan when this plan is purchased. For a Free Trial Offer, the discount cannot be disabled or changed in subscriptions if the recurring discount is not set.
                                                                </span>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>


                                                <!-- Free Trial Time Option -->

                                                <div class="Polaris-FormLayout__Items sd_membership_discount_free_trial display-hide-label">
                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="Polaris-Labelled__LabelWrapper">
                                                            <div class="Polaris-Label">
                                                                <label id="sd_order_fulfillment_frequency" for="PolarisTextField7" class="Polaris-Label__Text">Free trial period</label>
                                                            </div>

                                                            <div class="tooltip">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                    <path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/>
                                                                    <path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/>
                                                                    <path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/>
                                                                </svg>
                                                                <span class="tooltiptext">
                                                                The free trial period sets the time between order deliveries. It must be at least 1 day, week, month, or year. For example, if set to 1 week and the first order is today, the next will be one week later</span>
                                                            </div>
                                                        </div>

                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField sd-small-textfield">
                                                                    <span class="sd-text-label">Every</span>
                                                                    <input placeholder="eg. 1" name="member_offer_trial_period_value" value="1" id="member_offer_trial_period_value" class="Polaris-TextField__Input preview_plan" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="number" aria-labelledby="PolarisTextField7Label" aria-invalid="false" autocomplete="off" maxlength="4">
                                                                    <div class="Polaris-TextField__Backdrop"></div>

                                                                    <div class="Polaris-Select sd-small-select">
                                                                        <select id="member_offer_trial_period_type" name="member_offer_trial_period_type" class="Polaris-Select__Input" aria-invalid="false">
                                                                            <option value="days">DAY</option>
                                                                            <option value="weeks">WEEK</option>
                                                                            <option value="months">MONTH</option>
                                                                            <option value="years">YEAR</option>
                                                                        </select>
                                                                        <div class="Polaris-Select__Content" aria-hidden="true">
                                                                            <span class="Polaris-Select__SelectedOption">DAY</span>
                                                                            <span class="Polaris-Select__Icon">
                                                                                <span class="Polaris-Icon">
                                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                        <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                        <div class="Polaris-Select__Backdrop"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <span class="member_free_trial_frequency_value_error error_messages"></span> <br>

                                                        <!-- Renew Original Date Checkbox -->
                                                        <div class="Polaris-Choice">
                                                            <input type="checkbox" id="renew_original_date" name="renew_original_date" class="Polaris-Choice__Input renew_original_date" value="false">
                                                            <label for="renew_original_date" class="Polaris-Choice__Label" style="color: #1669b2;">
                                                                Calculate first billing after trial from original oder date
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>

                                                 <!-- minimum and maximum iteration cycle -->

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="enable_copyright_box">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="TextField1Label3" for="TextField3" class="Polaris-Label__Text">Offer discount(optional)</label>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField">

                                                                        <label class="switch">

                                                                            <input type="checkbox" name="membership_subscription_discount" id="membership_subscription_discount_status" value="">

                                                                            <span class="slider round"></span>

                                                                        </label>

                                                                    </div>

                                                                </div>

                                                                
                                                            </div>

                                                            <div class="tooltip">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                    <path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62" />
                                                                    <path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62" />
                                                                    <path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62" />
                                                                </svg>
                                                                <span class="tooltiptext">
                                                                    Discount will be applied on customer membership when this plan is purchased. After that it cannot be disabled or changed in subscriptions, if the recurring discount is not set.
                                                                </span>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="membership_sd_subscription_discount_offer_wrapper display-hide-label">

                                                    <div class="Polaris-FormLayout__Items">

                                                        <div class="Polaris-FormLayout__Item">

                                                            <div class="">

                                                                <div class="Polaris-Labelled__LabelWrapper">

                                                                    <div class="Polaris-Label"><label id="PolarisTextField7Label" for="PolarisTextField7" class="Polaris-Label__Text">Discount value</label></div>

                                                                </div>

                                                                <div class="Polaris-Connected">

                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                        <div class="Polaris-TextField">

                                                                            <input placeholder="eg. 10" min="1" name="membership_subscription_discount_value" id="membership_subscription_discount_value" autocomplete="off" class="Polaris-TextField__Input preview_plan" aria-labelledby="PolarisTextField7Label" aria-invalid="false" value="" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =

                                                                        !!this.value &amp;&amp; Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" type="number" maxlength="4">

                                                                            <div class="Polaris-TextField__Backdrop"></div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <span class="membership_subscription_discount_value_error error_messages"></span> <br>

                                                    
                                                            </div>

                                                        </div>

                                                        <div class="Polaris-FormLayout__Item">

                                                            <div class="">

                                                                <div class="Polaris-Labelled__LabelWrapper hide-label">

                                                                    <div class="Polaris-Label"><label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">hidden</label></div>

                                                                </div>

                                                                <div class="Polaris-Connected">

                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                        <div class="Polaris-Select">

                                                                            <select id="membership_subscription_discount_type" name="membership_subscription_discount_type" class="Polaris-Select__Input" aria-invalid="false">

                                                                                <option value="Percent Off(%)">Percent Off(%)</option>

                                                                                <option value="Discount Off">Discount Off</option>

                                                                            </select>

                                                                            <div class="Polaris-Select__Content" aria-hidden="true">

                                                                                <span class="Polaris-Select__SelectedOption">Percent Off(%)</span>

                                                                                <span class="Polaris-Select__Icon">

                                                                                    <span class="Polaris-Icon">

                                                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">

                                                                                            <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>

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


                                                    </div>

                                                </div>

                                                <!-- change discount after start -->

                                                <div class="membership_sd_change_discount_after">

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="enable_copyright_box">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="TextField1Label3" for="TextField3" class="Polaris-Label__Text">Change discount after(optional)</label>

                                                                </div>

                                                                <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField">

                                                                        <label class="switch">

                                                                            <input type="checkbox" name="membership_subscription_discount_after" id="membership_subscription_discount_after_status" value="">

                                                                            <span class="slider round"></span>

                                                                        </label>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                                <div class="tooltip">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                        <path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62" />
                                                                        <path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62" />
                                                                        <path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62" />
                                                                    </svg>
                                                                    <span class="tooltiptext">
                                                                        Customer membership discount will be changed after the order cycle you mentioned, after that discount will not be disabled or changed.
                                                                    </span>
                                                                </div>

                                                            </div>

                                                        
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="membership_sd_subscription_discount_offer_after_wrapper display-hide-label">

                                                    <!-- <div class="membership_sd_subscription_discount_offer_after_wrapper display-hide-label membership_sd_discount_change_div">

                                                        <div class="Polaris-FormLayout__Items">

                                                            <div class="Polaris-FormLayout__Item">

                                                                <div class="">

                                                                    <div class="Polaris-Labelled__LabelWrapper">

                                                                        <div class="Polaris-Label">

                                                                            <label id="PolarisTextField7Label" for="PolarisTextField7" class="Polaris-Label__Text">Change discount after cycle</label>

                                                                        </div>

                                                                    </div>

                                                                    <div class="Polaris-Connected">

                                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                            <div class="Polaris-TextField">

                                                                                <input placeholder="eg. 4" name="membership_change_discount_after_cycle" id="membership_change_discount_after_cycle" class="Polaris-TextField__Input preview_plan" min="1" type="number" autocomplete="off" aria-labelledby="PolarisTextField7Label" aria-invalid="false" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value = !!this.value &amp;&amp; Math.abs(this.value) > 0 ? Math.abs(this.value) : null" type="number" maxlength="4" value="">

                                                                                <div class="Polaris-TextField__Backdrop"></div>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                            
                                                                </div>

                                                                <span class="membership_change_discount_after_cycle_error error_messages"></span> <br>



                                                            </div>

                                                        </div>

                                                    </div> -->

                                                    <div class="Polaris-FormLayout__Items">

                                                        <div class="Polaris-FormLayout__Item">

                                                            <div class="">

                                                                <div class="Polaris-Labelled__LabelWrapper">

                                                                    <div class="Polaris-Label"><label id="PolarisTextField7Label" for="PolarisTextField7" class="Polaris-Label__Text">Discount value</label></div>

                                                                </div>

                                                                <div class="Polaris-Connected">

                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                        <div class="Polaris-TextField">

                                                                            <input placeholder="eg. 5" min="1" name="membership_discount_value_after" id="membership_discount_value_after" class="Polaris-TextField__Input preview_plan" aria-labelledby="PolarisTextField7Label" autocomplete="off" aria-invalid="false" value="" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =

                                                                        !!this.value &amp;&amp; Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" type="number" maxlength="4">

                                                                            <div class="Polaris-TextField__Backdrop"></div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <span class="membership_discount_value_after_error error_messages"></span> <br>

                                                                
                                                            </div>

                                                        </div>

                                                        <div class="Polaris-FormLayout__Item">

                                                            <div class="">

                                                                <div class="Polaris-Labelled__LabelWrapper hide-label">

                                                                    <div class="Polaris-Label"><label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">hidden</label></div>

                                                                </div>

                                                                <div class="Polaris-Connected">

                                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                        <div class="Polaris-Select">

                                                                            <select id="membership_subscription_discount_type_after" name="membership_subscription_discount_type_after" class="Polaris-Select__Input" aria-invalid="false">

                                                                                <option value="Percent Off(%)">Percent Off(%)</option>

                                                                                <option value="Discount Off">Discount Off</option>

                                                                            </select>

                                                                            <div class="Polaris-Select__Content" aria-hidden="true">

                                                                                <span class="Polaris-Select__SelectedOption">Percent Off(%)</span>

                                                                                <span class="Polaris-Select__Icon">

                                                                                    <span class="Polaris-Icon">

                                                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">

                                                                                            <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>

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

                                                    </div>

                                                </div>

                                                <!-- change discount after end-->

                                                <!-- Free trial end -->
                                                 
                                            </div>


                                            <button class="Polaris-Button Polaris-Button--plain show_another_option"
                                                type="button"><span class="Polaris-Button__Content"><span
                                                class="Polaris-Button__Text">Add another
                                            option</span></span></button>
                                        </div>
                                        <div class="right-button-groups">
                                            <div class="Polaris-ButtonGroup ">
                                                <div class="create-subscription-buttons">
                                                    <div
                                                        class="Polaris-ButtonGroup__Item step2_button_cancel display-hide-label">
                                                        <button class="Polaris-Button go-to-step3"
                                                            type="button">Cancel</button>
                                                    </div>
                                                    <div class="Polaris-ButtonGroup__Item step2_button_previous"><button
                                                        class="Polaris-Button go-to-step1" type="button">
                                                        Previous</button>
                                                    </div>
                                                </div>
                                                <div class="edit-subscription-buttons display-hide-label">
                                                    <div class="Polaris-ButtonGroup__Item  edit-cancel "><button
                                                        class="Polaris-Modal-CloseButton copy_sellingform_to_create Polaris-Button"
                                                        type="button">Cancel</button></div>
                                                </div>
                                                <div class="Polaris-ButtonGroup__Item edit-create-add-btn"><button
                                                    id="sd_add_group"
                                                    class="step2_button_submit Polaris-Button Polaris-Button--primary subscription_validate_step2"
                                                    type="button">Add</button></div>
                                            </div>
                                            <div id="PolarisPortalsContainer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- *******************************************************  STEP 3 ******************************************************-->
                    <div
                        class="Polaris-Layout__AnnotatedSection form-steps-tab subscription-create-step3 display-hide-label">
                        <div class="Polaris-Layout__AnnotationWrapper sd-group-schemes">
                            <div class="Polaris-Layout__AnnotationContent sd-group-schemes-wrapper">
                                <div
                                    class="Polaris-Page-Header Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">
                                    <div class="Polaris-Page-Header__Row">
                                        <div class="Polaris-Page-Header__TitleWrapper">
                                            <div>
                                                <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">
                                                    <h2 class="Polaris-Heading">Member
                                                        plans
                                                    </h2>
                                                </div>
                                            </div>
                                            <!-- <div class="Polaris-Stack__Item add_new_member_plan"></div> -->
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="sd_main_card_wrapper">
                                        <div class="Polaris-Layout">
                                            <div class="sd-group-plan-card-wrapper">
                                            <div class="add_new_member_plan Polaris-Layout__Section Polaris-Layout__Section--oneThird"></div>
                                            </div>
                                            <!-- to create new member plan tier -->
                                            <!-- <div class="add_new_member_plan"></div> -->
                                            <!-- create new member plan tier ends here -->
                                        </div>
                                        <div class="Polaris-Banner display-hide-label group-plan-error add-least-group-error Polaris-Banner--statusCritical Polaris-Banner--withinPage"
                                            tabindex="0" role="alert" aria-live="polite"
                                            aria-labelledby="PolarisBanner18Heading"
                                            aria-describedby="PolarisBanner18Content">
                                            <div class="Polaris-Banner__Ribbon">
                                                <span
                                                    class="Polaris-Icon Polaris-Icon--colorCritical Polaris-Icon--applyColor">
                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                                        aria-hidden="true">
                                                        <path
                                                            d="M11.768.768a2.5 2.5 0 0 0-3.536 0L.768 8.232a2.5 2.5 0 0 0 0 3.536l7.464 7.464a2.5 2.5 0 0 0 3.536 0l7.464-7.464a2.5 2.5 0 0 0 0-3.536L11.768.768zM9 6a1 1 0 1 1 2 0v4a1 1 0 1 1-2 0V6zm2 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="Polaris-Banner__ContentWrapper">
                                                <div class="Polaris-Banner__Content" id="PolarisBanner18Content">
                                                    <p>Please add atleast 1 plan .<span class="sd_link go-to-step2">Click
                                                        here </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="right-button-groups">
                            <div class="Polaris-ButtonGroup step3_button_group">
                                <div class="Polaris-ButtonGroup__Item"><button class="Polaris-Button step3_previous"
                                    type="button"><span class="Polaris-Button__Content"><span
                                    class="Polaris-Button__Text">Previous</span></span></button></div>
                                <div class="Polaris-ButtonGroup__Item"><button
                                    class="save-member-plan Polaris-Button Polaris-Button--primary" data-id="create"
                                    type="button"><span class="Polaris-Button__Content"><span
                                    class="Polaris-Button__Text">Finish</span></span></button></div>
                            </div>
                            <div id="PolarisPortalsContainer"></div>
                        </div>
                    </div>






                <!-- Polaris-Layout -->
            </form>
        </div>
       <!-- create plan preview right start -->
       <div class="create_plangroup_right">
           <h3>Widget Preview</h3>
        <div class="preview-container product_widget_preview" id="previewContainer">
            <div class="sd_membership_widget_wrapper" id="sd_membership_widget_wrapper">
            <fieldset class="widget_outline_color" style="border:2px solid <?php echo $product_widget_settings->widget_outline_color ?? '#000000'; ?>; background: linear-gradient(to right, <?php echo $product_widget_settings->background_color1 ?? '#fffafa0c'; ?>, <?php echo $product_widget_settings->background_color2 ?? '#ffffff'; ?>); border-radius:<?php echo $product_widget_settings->border_radius ?? 1; ?>px;">
    <legend class="membership_plan_name" style="color:<?php echo $product_widget_settings->heading_text_color ?? '#000000'; ?>">
        <?php echo $product_widget_settings->heading_text ?? 'Membership Plans'; ?>
    </legend>
    <div class="sd_membership_wrapper">
        <div class="sd_membership_wrapper">
            <div class="sd_membership_radio_wrapper widget_text_color" style="color:<?php echo $product_widget_settings->text_color ?? '#000000'; ?>;">
                <div class="sd_membership_amount_wrapper">
                    <div class="sd_plan_option sd_plan_option1 active_option_bgColor" id="sd_first_option" style="border-radius: <?php echo $product_widget_settings->border_radius ?? 1; ?>px; display: block; background: linear-gradient(to right, <?php echo $product_widget_settings->active_option_bgColor1 ?? '#ffffff'; ?>, <?php echo $product_widget_settings->active_option_bgColor2 ?? '#ffffff'; ?>);">
                        <div class="purchase-label-price">
                            <label>
                                <input type="radio" name="charges" checked id="charges1" class="radio_button_color" style="border: 4px solid <?php echo $product_widget_settings->radio_button_color ?? '#000000'; ?>">
                                <span class="charge_every_text"><?php echo $product_widget_settings->charge_every_text ?? 'charge every'; ?></span>
                                <span class="sd_option_value">1</span>
                                <span class="day_text sd_option_type"><?php echo $product_widget_settings->day_text ?? 'day'; ?></span>
                            </label>
                            <span class="sd_membership_amount price_color" style="color: <?php echo $product_widget_settings->price_color ?? '#000000'; ?>">
                                <span class="sd_option_price">$10 USD</span>
                            </span>
                        </div>
                    </div>

                    <div class="sd_plan_option sd_plan_option2" id="sd_second_option" style="display: block;">
                        <div class="purchase-label-price">
                            <label>
                                <input type="radio" name="charges" id="charges2">
                                <span class="charge_every_text"><?php echo $product_widget_settings->charge_every_text ?? 'charge every'; ?></span>
                                <span class="sd_option_value">1</span>
                                <span class="week_text sd_option_type"><?php echo $product_widget_settings->week_text ?? 'Week'; ?></span>
                            </label>
                            <span class="sd_membership_amount price_color" style="color: <?php echo $product_widget_settings->price_color ?? '#000000'; ?>">
                                <span class="sd_option_price">$20 USD</span> 
                            </span>
                        </div>
                    </div>

                    <div class="sd_plan_option sd_plan_option3" id="sd_third_option" style="display: block;">
                        <div class="purchase-label-price">
                            <label>
                                <input type="radio" name="charges" id="charges3">
                                <span class="charge_every_text"><?php echo $product_widget_settings->charge_every_text ?? 'charge every'; ?></span>
                                <span class="sd_option_value">1</span>
                                <span class="month_text sd_option_type"><?php echo $product_widget_settings->month_text ?? 'Month'; ?></span>
                            </label>
                            <span class="sd_membership_amount price_color" style="color: <?php echo $product_widget_settings->price_color ?? '#000000'; ?>">
                                <span class="sd_option_price">$30 USD</span> 
                            </span>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</fieldset>

            </div>
        </div>
       </div>
       <!-- create plan preview right end -->
    </div>
</div>
<!-- <script>
    $('#btn').on('click', function() {
        let selectedSize = $('input[name="size"]:checked').val();
        let output = $('#output');
        output.text(selectedSize ? `You selected ${selectedSize}` : `You haven't selected any size`);
    });
</script> -->