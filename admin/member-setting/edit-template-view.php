<?php
include("../header.php");

include("../navigation.php");
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
$template_name = isset($_GET['template_name']) ? $_GET['template_name'] : '';
// echo $template_name;die;
$template_name .= 's';


$template_data_for = [];
$template_data_for['template_name'] = $template_name;
$template_data_for['email_type'] = 'static_email';
$template_data_for['shop_name'] = $store;
$template_data_for['discount_coupon_content'] = true;
$template_data_for['free_shipping_coupon_content'] = true;
$template_data_for['early_sale_content'] = true;
$template_data_for['free_signup_product_content'] = true;
$template_data_for['free_gift_uponsignupSelectedDays'] = true;
try {

    // echo "jdjdjddj";
    $emailSettings = $mainobj->membershipAllEmailTemplates($template_data_for);
    // print_r($emailSettings);
    // echo $email_template_html;
    // die;
} catch (Exception $e) {
    echo $e->getMessage();
}

// print_r($email_template_html);die;
?>




<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">





<?php

$template_type_array = [
    'template_type' => $template_name,
];

// Assuming all the following variables are already defined in your PHP scope:
// $logo, $logo_alignment, $logo_width, $logo_height, $customer_name, $plan_name

$content_text = '
<body style="background-color: rgb(136, 189, 191);">
    <div style="background: rgb(243, 243, 243); padding: 40px; max-width: 746px; margin: 30px auto;">
        <div style="background: white; padding: 40px; max-width: 746px; margin: 10px auto; padding: 0px; text-align: ' . htmlspecialchars($logo_alignment ?? '') . '">
            <img class="" alt="logo" src="' . htmlspecialchars($logo ?? '') . '" width="' . htmlspecialchars($logo_width ?? '') . 'px" height="' . htmlspecialchars($logo_height ?? '') . 'px">
            <h1 style="font-family: \'Lobster\', cursive; text-align: center; font-size:25px;">Subscription Cancelled</h1>
            <p style="margin: 20px 10px 20px; font-size: 20px; font-family: \'Times New Roman\', Times, serif; text-align: center;">
                Hey ' . htmlspecialchars($customer_name ?? '') . ' ,<br> 
                We would like to inform you that your subscription for <b>' . htmlspecialchars($plan_name ?? '') . '</b> has been canceled. 
                We understand that this decision may have been made for various reasons, and we appreciate the time you spent with our service. 
                If you have any feedback or concerns regarding your experience or if you would like to explore alternative subscription options, 
                please do not hesitate to reach out to our customer support team. We value your patronage and hope to assist you with any future needs or questions you may have.
            </p>
            <div style="padding: 20px 150px 40px;">
                <a href="#" style="display: inline-block; text-align: center; background: rgb(255, 178, 0); color: rgb(243, 243, 243); font-weight: bold; padding: 1.18em 1.32em 1.03em; line-height: 1; border-radius: 1em; position: relative; min-width: 8.23em; text-decoration: none; font-family: \'Montserrat\', \'Roboto\', Helvetica, Arial, sans-serif; font-size: 1.75rem;">
                    Purchase Another Subscription
                </a>
            </div>
        </div>
        <div>
            <h3 style="font-family: \'Indie Flower\', cursive; font-style: italic; text-align: center; color: rgb(111, 111, 111);">Stay in touch</h3>
        </div>
    </div>
</body>';

?>


<div class="sd-prefix-main">

    <div class="Polaris-Layout__Section sd-email-template-page">

        <input type="hidden" id="page_type" value="email_setting_page">

        <input type="hidden" id="template_data_array" value="<?php echo htmlspecialchars(json_encode($emailSettings)); ?>">

        <div id="PolarisPortalsContainer" class="t-right top-banner-create-memberPlan">

        </div>

        <div class="Polaris-Page-Header__Row">

            <div class="Polaris-Page-Header__BreadcrumbWrapper">

                <nav role="navigation">

                    <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership" onclick="handleLinkRedirect('/member-setting/email-settings.php')"> <span class="Polaris-Breadcrumbs__ContentWrapper">

                            <span class="Polaris-Breadcrumbs__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">

                                        <path d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">

                                        </path>

                                    </svg> </span> </span> </span> </a>

                </nav>

            </div>

            <div class="Polaris-Page-Header__TitleWrapper sd_email_template_header">

                <div>

                    <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">

                        <h2 class="Polaris-Heading">

                            <?php

                            switch ($template_name) {

                                case 'new_purchase_plans':

                                    echo 'Membership purchase';

                                    break;

                                case 'plan_payment_pendings':

                                    echo 'Membership payment pending';

                                    break;

                                case 'credit_card_expirings':

                                    echo 'Credit card expiring';

                                    break;

                                case 'plan_payment_faileds':

                                    echo 'Membership payment failed';

                                    break;

                                case 'plan_payment_declineds':

                                    echo 'Membership payment declined';

                                    break;

                                case 'membership_cancelleds':

                                    echo 'Membership status update';

                                    break;

                                case 'membership_upgrades':

                                    echo 'Membership upgrade';

                                    break;

                                case 'membership_downgrades':

                                    echo 'Membership downgrade';

                                    break;

                                case 'membership_renews':

                                    echo 'Membership renew';

                                    break;

                                case 'membership_free_gifts':

                                    echo 'Membership free gift';

                                    break;
                            }

                            ?>

                        </h2>

                    </div>

                </div>

                <div>



                    <div class="Polaris-Header-Title__TitleAndSubtitleWrapper reset-save-button">
                        <?php //if ($plansData->charge_id != '' && $plansData->plan_status == 'active' && $plansData->current_plan != 'free'): ?>
                            <button class="sd_app_enable_btn sd_save_email_template_membership sd_button" type="button" data-id="sd_<?php echo $template_name; ?>" btn-type="Reset">
                                Reset
                            </button>
                            <button class="sd_app_enable_btn sd_save_email_template_membership sd_button" type="button" data-id="sd_<?php echo $template_name; ?>" btn-type="Save">
                                Save
                            </button>
                        <?php //else: ?>
                            <!-- <button class="sd_app_enable_btn sd_button sd_save_disabled_btn" type="button">
                                <span class="sd_svg_span">
                                    <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z" fill="#fffff">
                                        </path>
                                    </svg>
                                    Reset
                                </span>
                            </button>
                            <button class="sd_app_enable_btn sd_button sd_save_disabled_btn" type="button">
                                <span class="sd_svg_span">
                                    <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z" fill="#fffff">
                                        </path>
                                    </svg>
                                    Save
                                </span>
                            </button>
                            <div class="upgrade-message">
                                <p class="upgrade-plan-msg">
                                    <a class="sd_handle_navigation_redirect sd_clickme" value="/upgrade-plans">Upgrade your plan</a> to access this feature
                                </p>
                            </div> -->
                        <?php //endif; ?>
                    </div>


                </div>

            </div>

        </div>

        <div class="">

            <form method="post" id="sd_<?php echo $template_name; ?>">

                <input type="hidden" id="store" name="store" value="<?php echo $shop; ?>">



                <div class="Polaris-Page__Content">

                    <div class="Polaris-Layout editEmail_template_main_section">

                        <div class="Polaris-Layout__Section Polaris-Layout__Section--secondary">

                            <div class="Polaris-Box" style="--pc-box-background:var(--p-color-bg-surface);--pc-box-min-height:100%;--pc-box-overflow-x:clip;--pc-box-overflow-y:clip;--pc-box-padding-block-start-xs:var(--p-space-400);--pc-box-padding-block-end-xs:var(--p-space-400);--pc-box-padding-inline-start-xs:var(--p-space-400);--pc-box-padding-inline-end-xs:var(--p-space-400)">



                                <!-- <div>  -->

                                <ul class="custom-default-temp">

                                    <li class="Polaris-Tabs__TabContainer" role="presentation">

                                        <button group="subscription-edit-tabs" target-tab="edit_default_template" role="tab" type="button" data-radio-id="default-temp" class="sd_Tabs_membership subscription-edit-tabs-title Polaris-Tabs__Tab Polaris-Tabs__Tab--selected selectTemplate">

                                            <input type="radio" id="default-temp" checked>

                                            <span class="Polaris-Tabs__Title">Default template</span>

                                        </button>

                                    </li>

                                    <li class="Polaris-Tabs__TabContainer" role="presentation"><button group="subscription-edit-tabs" target-tab="edit_custom_template" type="button" data-radio-id="custom-temp" class="sd_Tabs_membership subscription-edit-tabs-title Polaris-Tabs__Tab selectTemplate">

                                            <input type="radio" id="custom-temp"><span class="Polaris-Tabs__Title">Custom template</span>

                                        </button>

                                    </li>

                                </ul>

                                <!-- </div> -->

                                <div class="Polaris-Box" style="--pc-box-border-block-end:var(--p-border-divider);--pc-box-padding-inline-start-xs:var(--p-space-2);--pc-box-padding-inline-end-xs:var(--p-space-2)">

                                    <ul role="tablist" class="Polaris-Tabs">

                                        <li class="Polaris-Tabs__TabContainer" role="presentation"><button group="subscription-edit-tabs" target-tab="edit_default_template" role="tab" type="button" class="sd_Tabs_membership subscription-edit-tabs-title Polaris-Tabs__Tab editorSelectTemplate">

                                                <span class="Polaris-Tabs__Title">Editor</span></button></li>

                                        <li class="Polaris-Tabs__TabContainer" role="presentation"><button group="subscription-edit-tabs" target-tab="edit_email_template" role="tab" type="button" class="sd_Tabs_membership subscription-edit-tabs-title Polaris-Tabs__Tab">

                                                <span class="Polaris-Tabs__Title">Email

                                                    setting</span></button></li>

                                        <li class="Polaris-Tabs__TabContainer" role="presentation"><button group="subscription-edit-tabs" target-tab="add_dynamic_variables" role="tab" type="button" class="sd_Tabs_membership subscription-edit-tabs-title Polaris-Tabs__Tab">

                                                <span class="Polaris-Tabs__Title">Tags</span></button></li>



                                    </ul>

                                </div>

                                <div class="Polaris-Tabs__Panel subscription-edit-tabs Polaris-Tabs__Panel--hidden" id="add_dynamic_variables" role="tabpanel" aria-labelledby="all-customers-1" tabindex="-1">

                                    <div class="sd_email_tags">

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Customer

                                                            name</label></div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" type="button" data-value="<?php echo $customer_name; ?>"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Customer email</label></div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $customer_email; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Plan

                                                            name</label>

                                                    </div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $plan_name; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Membership plan skip date</label>

                                                    </div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $skip_date; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Plan

                                                            status</label>

                                                    </div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $plan_status; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Store

                                                            name</label>

                                                    </div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $store_name; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Coupon

                                                            code</label>

                                                    </div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $coupon_code; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">
                                            <div class="">
                                                <div class="Polaris-Labelled__LabelWrapper">
                                                    <div class="Polaris-Label">
                                                        <label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Free shipping code</label>
                                                    </div>
                                                    <div class="Polaris-Labelled__Action">
                                                        <button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $free_shipping_code; ?>" type="button">
                                                            <span class="Polaris-Button__Content">
                                                                <span class="Polaris-Button__Text">
                                                                    <svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">
                                            <div class="">
                                                <div class="Polaris-Labelled__LabelWrapper">
                                                    <div class="Polaris-Label">
                                                        <label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Number of days for early access sale</label>
                                                    </div>
                                                    <div class="Polaris-Labelled__Action">
                                                        <button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $number_of_days; ?>" type="button">
                                                            <span class="Polaris-Button__Content">
                                                                <span class="Polaris-Button__Text">
                                                                    <svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Free sign

                                                            up

                                                            product</label></div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $free_sign_up; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Free sign

                                                            up

                                                            product date</label></div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $free_sign_up_produt_date; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Immediate

                                                            sign up

                                                            product</label></div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $immediate_sign_up_produt; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                        <div id="polaris-example" class="PolarisExampleWrapper_Example__NK9R0">

                                            <div class="">

                                                <div class="Polaris-Labelled__LabelWrapper">

                                                    <div class="Polaris-Label"><label id=":R1n6:Label" for=":R1n6:" class="Polaris-Label__Text">Percentage

                                                            discount</label></div>

                                                    <div class="Polaris-Labelled__Action"><button class="Polaris-Button Polaris-Button--plain sd_copy_element" data-value="<?php echo $percentage_discount; ?>" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text"><svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">

                                                                        <path fill-rule="evenodd" d="M6.515 4.75a2 2 0 0 1 1.985-1.75h3a2 2 0 0 1 1.985 1.75h.265a2.25 2.25 0 0 1 2.25 2.25v7.75a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.75a2.25 2.25 0 0 1 2.25-2.25h.265Zm1.985-.25h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm-1.987 1.73.002.02h-.265a.75.75 0 0 0-.75.75v7.75c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.75-.75h-.265a2 2 0 0 1-1.985 1.75h-3a2 2 0 0 1-1.987-1.77Z" fill="#5C5F62" />

                                                                    </svg></span></span></button></div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="Polaris-Tabs__Panel subscription-edit-tabs Polaris-Tabs__Panel--hidden" id="edit_email_template" role="tabpanel" aria-labelledby="all-customers-1" tabindex="-1">

                                    <!-- all -->

                                    <div class="Polaris-Card__Section">

                                        <div class="Polaris-FormLayout sd_form_template_layout">

                                            <!-- email templates field start here -->

                                            <div class="Polaris-FormLayout__Item">

                                                <div class="input_border">

                                                    <div class="Polaris-Labelled__LabelWrapper">

                                                        <div class="Polaris-Label">

                                                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Subject</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-Connected">

                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                            <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                <input id="subject" name="subject" autocomplete="off" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['subject']; ?>">

                                                                <div class="Polaris-TextField__Backdrop">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="Polaris-FormLayout__Item">

                                                <div class="input_border">

                                                    <div class="Polaris-Labelled__LabelWrapper">

                                                        <div class="Polaris-Label">

                                                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">CC

                                                                    email</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-Connected">

                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                            <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                <input id="ccc_email" name="ccc_email" autocomplete="off" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['ccc_email']; ?>">

                                                                <div class="Polaris-TextField__Backdrop">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="Polaris-FormLayout__Item">

                                                <div class="input_border">

                                                    <div class="Polaris-Labelled__LabelWrapper">

                                                        <div class="Polaris-Label">

                                                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">BCC

                                                                    email</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-Connected">

                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                            <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                <input id="bcc_email" name="bcc_email" autocomplete="off" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['bcc_email']; ?>">

                                                                <div class="Polaris-TextField__Backdrop">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="Polaris-FormLayout__Item">

                                                <div class="input_border">

                                                    <div class="Polaris-Labelled__LabelWrapper">

                                                        <div class="Polaris-Label">

                                                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Reply

                                                                    to</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-Connected">

                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                            <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                <input id="reply_to" name="reply_to" autocomplete="off" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['reply_to']; ?>">

                                                                <div class="Polaris-TextField__Backdrop">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>



                                            </div>

                                            <!-- email templates field ends here -->

                                        </div>

                                    </div>

                                </div>

                                <div class="Polaris-Tabs__Panel subscription-edit-tabs Polaris-Tabs__Panel--hidden" id="edit_custom_template" role="tabpanel" aria-labelledby="accepts-marketing-1" tabindex="-1">

                                    <!-- <div id="myMonacoEditor" style="width:800px;height:600px;"></div> -->

                                    <div class="Polaris-Card__Section">

                                        <div class="Polaris-FormLayout">

                                            <div class="Polaris-FormLayout__Item sd_custom_template">

                                                <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary sd_custom_email_html">

                                                        <textarea class="content customeditor" id="custom_email_html" name="custom_email_html"><?php echo $emailSettings['custom_email_html']; ?></textarea>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="Polaris-Tabs__Panel subscription-edit-tabs <?php echo ($templateName == 'new_purchase_plans') ? 'sd-email-bodyContent-wrapper' : ''; ?>" id="edit_default_template" role="tabpanel" aria-labelledby="accepts-marketing-1" tabindex="-1">


                                    <div class="Polaris-Card__Section builder_menu_section">

                                        <div class="Polaris-FormLayout sd_email_style">

                                            <div class="Polaris-Box sd_email_box" attr-key="logo_setting" style="--pc-box-background:var(--p-color-bg);--pc-box-border-radius:var(--p-border-radius-2);--pc-box-overflow-x:hidden;--pc-box-overflow-y:hidden;--pc-box-padding-block-end-xs:var(--p-space-4);--pc-box-padding-block-end-sm:var(--p-space-5);--pc-box-padding-block-start-xs:var(--p-space-4);--pc-box-padding-block-start-sm:var(--p-space-5);--pc-box-padding-inline-start-xs:var(--p-space-4);--pc-box-padding-inline-start-sm:var(--p-space-5);--pc-box-padding-inline-end-xs:var(--p-space-4);--pc-box-padding-inline-end-sm:var(--p-space-5);--pc-box-shadow:var(--p-shadow-md)">

                                                <div class="Polaris-iconbox">

                                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 97.68" style="enable-background:new 0 0 122.88 97.68" xml:space="preserve">

                                                        <style type="text/css">
                                                            .st0 {

                                                                fill-rule: evenodd;

                                                                clip-rule: evenodd;

                                                            }
                                                        </style>

                                                        <g>

                                                            <path class="st0" d="M0,0h122.88v97.68H0V0L0,0z M34.56,25.4c4.41,0,7.99,3.58,7.99,7.99c0,4.42-3.58,7.99-7.99,7.99 c-4.41,0-7.99-3.58-7.99-7.99C26.57,28.97,30.15,25.4,34.56,25.4L34.56,25.4z M68.2,59.7l15.99-27.64l16.99,42.96l-79.27,0v-5.33 l6.66-0.33l6.66-16.32l3.33,11.66h9.99l8.66-22.31L68.2,59.7L68.2,59.7z M9.13,8.09h104.63v81.49H9.13V8.09L9.13,8.09z" />

                                                        </g>

                                                    </svg>

                                                </div>

                                                <h3 class="Polaris-Text--root Polaris-Text--bodyMd">Logo

                                                </h3>

                                            </div>

                                            <div class="Polaris-Box sd_email_box" attr-key="heading_setting" style="--pc-box-background:var(--p-color-bg);--pc-box-border-radius:var(--p-border-radius-2);--pc-box-overflow-x:hidden;--pc-box-overflow-y:hidden;--pc-box-padding-block-end-xs:var(--p-space-4);--pc-box-padding-block-end-sm:var(--p-space-5);--pc-box-padding-block-start-xs:var(--p-space-4);--pc-box-padding-block-start-sm:var(--p-space-5);--pc-box-padding-inline-start-xs:var(--p-space-4);--pc-box-padding-inline-start-sm:var(--p-space-5);--pc-box-padding-inline-end-xs:var(--p-space-4);--pc-box-padding-inline-end-sm:var(--p-space-5);--pc-box-shadow:var(--p-shadow-md)">

                                                <div class="Polaris-iconbox">

                                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 120.26" style="enable-background:new 0 0 122.88 120.26" xml:space="preserve">

                                                        <g>

                                                            <polygon points="0,14.54 0,0 49.81,0 49.81,14.54 36.93,17.03 36.93,51.7 85.98,51.7 85.98,17.03 73.1,14.54 73.1,0 85.98,0 110,0 122.88,0 122.88,14.54 110,17.03 110,103.31 122.88,105.79 122.88,120.26 73.1,120.26 73.1,105.79 85.98,103.31 85.98,70.3 36.93,70.3 36.93,103.31 49.81,105.79 49.81,120.26 0,120.26 0,105.79 12.8,103.31 12.8,17.03 0,14.54" />

                                                        </g>

                                                    </svg>

                                                </div>

                                                <h3 class="Polaris-Text--root Polaris-Text--bodyMd">Header

                                                </h3>

                                            </div>

                                            <div class="Polaris-Box sd_email_box" attr-key="email_content_setting" style="--pc-box-background:var(--p-color-bg);--pc-box-border-radius:var(--p-border-radius-2);--pc-box-overflow-x:hidden;--pc-box-overflow-y:hidden;--pc-box-padding-block-end-xs:var(--p-space-4);--pc-box-padding-block-end-sm:var(--p-space-5);--pc-box-padding-block-start-xs:var(--p-space-4);--pc-box-padding-block-start-sm:var(--p-space-5);--pc-box-padding-inline-start-xs:var(--p-space-4);--pc-box-padding-inline-start-sm:var(--p-space-5);--pc-box-padding-inline-end-xs:var(--p-space-4);--pc-box-padding-inline-end-sm:var(--p-space-5);--pc-box-shadow:var(--p-shadow-md)">

                                                <div class="Polaris-iconbox">



                                                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 119.45 122.88">

                                                        <title>email-address</title>

                                                        <path d="M0,58.68V53.56L39.82,88.38.52,119.18A6.14,6.14,0,0,1,0,116.71v-58Zm82-6a24,24,0,0,1-1,6.19,15.32,15.32,0,0,1-2.48,4.85,10.89,10.89,0,0,1-3.83,3.18A11.09,11.09,0,0,1,69.83,68a8.69,8.69,0,0,1-2.13-.25A7.6,7.6,0,0,1,65.79,67a6.7,6.7,0,0,1-1.58-1.21,6.8,6.8,0,0,1-.69-.82,8.54,8.54,0,0,1-6.69,2.92,8,8,0,0,1-2.52-.39,7.53,7.53,0,0,1-2.19-1.19l0,0a7.89,7.89,0,0,1-1.71-1.88,10.11,10.11,0,0,1-1.19-2.52v0a15.7,15.7,0,0,1-.55-6.27v0a23.36,23.36,0,0,1,1.46-5.95,16.59,16.59,0,0,1,2.83-4.71,12.06,12.06,0,0,1,4-3.09,11.11,11.11,0,0,1,4.79-1,14.3,14.3,0,0,1,3.67.45,11.15,11.15,0,0,1,3.17,1.39l1.63,1.09a.65.65,0,0,1,.28.59L69.11,59.36a3.29,3.29,0,0,0,.29,2.22,1.75,1.75,0,0,0,1.54.61,2.63,2.63,0,0,0,1.61-.56A5.63,5.63,0,0,0,74,59.74a12,12,0,0,0,1.09-3,20.78,20.78,0,0,0,.47-3.83,24.13,24.13,0,0,0-.65-7.4,13.59,13.59,0,0,0-2.76-5.39,11.76,11.76,0,0,0-4.67-3.32A17.51,17.51,0,0,0,61,35.68a16.06,16.06,0,0,0-4.45.6,14.57,14.57,0,0,0-3.91,1.79A15.31,15.31,0,0,0,49.32,41a18.56,18.56,0,0,0-2.54,3.93,24,24,0,0,0-1.69,4.76,29.77,29.77,0,0,0-.74,5.42h0A25.78,25.78,0,0,0,45,62.81a14,14,0,0,0,2.77,5.57,11.75,11.75,0,0,0,4.76,3.38,18.42,18.42,0,0,0,6.77,1.15,19.82,19.82,0,0,0,2.16-.12c.74-.08,1.51-.21,2.28-.37s1.48-.33,2.12-.52a15.05,15.05,0,0,0,1.7-.61.64.64,0,0,1,.84.34.55.55,0,0,1,0,.17L69.41,76a.67.67,0,0,1-.28.68,10.53,10.53,0,0,1-2,1,17.2,17.2,0,0,1-2.47.76,23.7,23.7,0,0,1-2.73.49,23.18,23.18,0,0,1-2.75.16,24.74,24.74,0,0,1-12.17-2.77,16.89,16.89,0,0,1-4.39-3.45,18.16,18.16,0,0,1-3.09-4.79l0,0a24,24,0,0,1-1.73-5.95A35.46,35.46,0,0,1,37.41,55a32.6,32.6,0,0,1,1-6.86,28,28,0,0,1,2.36-6.12A23.13,23.13,0,0,1,44.37,37a21.6,21.6,0,0,1,4.74-3.82,22.27,22.27,0,0,1,5.65-2.37A24.52,24.52,0,0,1,61.07,30a22.8,22.8,0,0,1,11.5,2.77,17.53,17.53,0,0,1,4.28,3.4,18.32,18.32,0,0,1,3,4.59,22.32,22.32,0,0,1,1.76,5.6A28.62,28.62,0,0,1,82,52.71Zm-25.7,3a15.3,15.3,0,0,0,0,2.79,5.85,5.85,0,0,0,.48,1.9,2.42,2.42,0,0,0,.83,1,2.13,2.13,0,0,0,1.19.32,2.1,2.1,0,0,0,.89-.2,3.08,3.08,0,0,0,.87-.67,5.64,5.64,0,0,0,.83-1.17,10.13,10.13,0,0,0,.69-1.58l1-11-.17,0a5.62,5.62,0,0,0-.74,0,5,5,0,0,0-2.25.49A4.51,4.51,0,0,0,58.27,49a9.45,9.45,0,0,0-1.2,2.7,23.81,23.81,0,0,0-.73,4ZM.15,43.47C1,40.14,5.72,38,8.34,36.14V21.85a6,6,0,0,1,6-6H36.81L58.26.57A3.24,3.24,0,0,1,62,.59L83.08,15.85h22a6,6,0,0,1,6,6V36.1c2.82,2,8.34,4.72,8.34,8.49v.15L105.11,56.83v-35H14.34v34L.15,43.47Zm119.3,11.31v61.93a6.12,6.12,0,0,1-1.42,3.93L78.34,89.43l41.11-34.65Zm-11,68.1H8.26L45.71,93.53l11.15,9.75a3.83,3.83,0,0,0,5.15-.09L72.33,94.5l36.09,28.38Z" />

                                                    </svg>

                                                </div>

                                                <h3 class="Polaris-Text--root Polaris-Text--bodyMd">

                                                    Body content</h3>

                                            </div>

                                            <div class="Polaris-Box sd_email_box" attr-key="manage_button_setting" style="--pc-box-background:var(--p-color-bg);--pc-box-border-radius:var(--p-border-radius-2);--pc-box-overflow-x:hidden;--pc-box-overflow-y:hidden;--pc-box-padding-block-end-xs:var(--p-space-4);--pc-box-padding-block-end-sm:var(--p-space-5);--pc-box-padding-block-start-xs:var(--p-space-4);--pc-box-padding-block-start-sm:var(--p-space-5);--pc-box-padding-inline-start-xs:var(--p-space-4);--pc-box-padding-inline-start-sm:var(--p-space-5);--pc-box-padding-inline-end-xs:var(--p-space-4);--pc-box-padding-inline-end-sm:var(--p-space-5);--pc-box-shadow:var(--p-shadow-md)">

                                                <div class="Polaris-iconbox">

                                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 117.824" enable-background="new 0 0 122.88 117.824" xml:space="preserve">

                                                        <g>

                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M122.774,16.459L122.774,16.459c0,5.393-4.412,9.805-9.805,9.805H92.202 c1.457-2.919,2.278-6.212,2.278-9.697c0-3.571-0.861-6.941-2.387-9.913h20.876C118.362,6.654,122.774,11.066,122.774,16.459 L122.774,16.459z M89.306,101.257c0,9.15-7.418,16.567-16.568,16.567s-16.567-7.417-16.567-16.567 c0-9.149,7.417-16.567,16.567-16.567S89.306,92.107,89.306,101.257L89.306,101.257z M122.869,101.148L122.869,101.148 c0,5.393-4.413,9.805-9.806,9.805H92.202c1.457-2.919,2.278-6.212,2.278-9.696c0-3.571-0.861-6.941-2.387-9.913h20.97 C118.457,91.344,122.869,95.756,122.869,101.148L122.869,101.148z M53.272,110.953H9.816c-5.393,0-9.805-4.412-9.805-9.805l0,0 c0-5.393,4.412-9.805,9.805-9.805h43.565c-1.525,2.972-2.387,6.342-2.387,9.913C50.994,104.741,51.815,108.034,53.272,110.953 L53.272,110.953z M28.326,58.717c0,9.149,7.418,16.567,16.568,16.567c9.149,0,16.567-7.418,16.567-16.567 c0-9.15-7.418-16.568-16.567-16.568C35.744,42.148,28.326,49.566,28.326,58.717L28.326,58.717z M0,58.608L0,58.608 c0,5.393,4.414,9.805,9.805,9.805h15.675c-1.457-2.92-2.278-6.169-2.278-9.696c0-3.528,0.861-6.941,2.387-9.914H9.805 C4.412,48.803,0,53.215,0,58.608L0,58.608z M64.409,68.413h48.666c5.392,0,9.805-4.412,9.805-9.805l0,0 c0-5.394-4.412-9.806-9.805-9.806H64.301c1.525,2.973,2.387,6.386,2.387,9.914C66.688,62.244,65.866,65.493,64.409,68.413 L64.409,68.413z M89.306,16.567c0,9.15-7.418,16.567-16.568,16.567S56.17,25.718,56.17,16.567C56.17,7.417,63.587,0,72.737,0 S89.306,7.417,89.306,16.567L89.306,16.567z M53.272,26.264H9.853c-5.393,0-9.805-4.413-9.805-9.805l0,0 c0-5.393,4.412-9.805,9.805-9.805h43.528c-1.525,2.972-2.387,6.342-2.387,9.913C50.994,20.052,51.815,23.345,53.272,26.264 L53.272,26.264z" />

                                                        </g>

                                                    </svg>

                                                </div>

                                                <h3 class="Polaris-Text--root Polaris-Text--bodyMd">Button</h3>

                                            </div>

                                            <div class="Polaris-Box sd_email_box" attr-key="footer_setting" style="--pc-box-background:var(--p-color-bg);--pc-box-border-radius:var(--p-border-radius-2);--pc-box-overflow-x:hidden;--pc-box-overflow-y:hidden;--pc-box-padding-block-end-xs:var(--p-space-4);--pc-box-padding-block-end-sm:var(--p-space-5);--pc-box-padding-block-start-xs:var(--p-space-4);--pc-box-padding-block-start-sm:var(--p-space-5);--pc-box-padding-inline-start-xs:var(--p-space-4);--pc-box-padding-inline-start-sm:var(--p-space-5);--pc-box-padding-inline-end-xs:var(--p-space-4);--pc-box-padding-inline-end-sm:var(--p-space-5);--pc-box-shadow:var(--p-shadow-md)">

                                                <div class="Polaris-iconbox">



                                                    <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 380.24">

                                                        <path d="M34.66 0h442.68C496.4 0 512 15.6 512 34.66v310.92c0 19.06-15.6 34.66-34.66 34.66H34.66C15.6 380.24 0 364.64 0 345.58V34.66C0 15.6 15.6 0 34.66 0zm173.92 264.36c5.76 5.04 6.34 13.81 1.3 19.57-5.05 5.76-13.81 6.35-19.57 1.3l-52.73-46.19c-5.76-5.05-6.35-13.81-1.3-19.58.43-.49.89-.94 1.37-1.36l52.66-46.14c5.76-5.04 14.52-4.46 19.57 1.31 5.04 5.76 4.46 14.52-1.3 19.57l-40.82 35.76 40.82 35.76zm113.11 20.87c-5.76 5.05-14.52 4.46-19.57-1.3-5.04-5.76-4.46-14.53 1.3-19.57l40.82-35.76-40.82-35.76c-5.76-5.05-6.34-13.81-1.3-19.57 5.05-5.77 13.81-6.35 19.57-1.31l52.66 46.14c.48.42.94.87 1.37 1.36 5.05 5.77 4.46 14.53-1.3 19.58l-52.73 46.19zm-65.95-124.31c1.74-7.47 9.22-12.12 16.69-10.38 7.47 1.74 12.12 9.22 10.38 16.69l-30.13 129.04c-1.74 7.48-9.22 12.13-16.69 10.39-7.47-1.74-12.12-9.22-10.38-16.69l30.13-129.05zM22.03 97.05v251.91a9.56 9.56 0 0 0 9.59 9.59H481.8a9.56 9.56 0 0 0 9.59-9.59V97.05H22.03zm422.32-58.09c9.46 0 17.12 7.67 17.12 17.12 0 9.46-7.66 17.12-17.12 17.12-9.45 0-17.12-7.66-17.12-17.12 0-9.45 7.67-17.12 17.12-17.12zm-116.03 0c9.46 0 17.12 7.67 17.12 17.12 0 9.46-7.66 17.12-17.12 17.12-9.45 0-17.11-7.66-17.11-17.12 0-9.45 7.66-17.12 17.11-17.12zm58.02 0c9.45 0 17.12 7.67 17.12 17.12 0 9.46-7.67 17.12-17.12 17.12-9.45 0-17.12-7.66-17.12-17.12 0-9.45 7.67-17.12 17.12-17.12z" />

                                                    </svg>

                                                </div>

                                                <h3 class="Polaris-Text--root Polaris-Text--bodyMd">Footer

                                                </h3>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="sd_menu_settings display-hide-label">

                                        <div class="Polaris-Card__Section builder_port_section sd_settings_details logo_setting display-hide-label">

                                            <div class="inner_headbar">

                                                <div class="back_addfield_btn" attr-keyhide="zero-item">

                                                    <span class="Polaris-Icon Polaris-Icon--newDesignLanguage">

                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg_375hu" focusable="false" aria-hidden="true">

                                                            <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16z">

                                                            </path>

                                                        </svg>

                                                    </span>

                                                    <span>Back</span>

                                                </div>

                                                <div class="inner_headbar_heading go_to_menu">Logo style

                                                </div>

                                            </div>

                                            <div class="Polaris-Card__Section">

                                                <div class="Polaris-FormLayout">

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Logo</span>

                                                                    </label>

                                                                </div>

                                                                <div class="Polaris-Labelled__Action">

                                                                    <a target="_blank" class="Polaris-Link" href="https://<?php echo $shop; ?>/admin/settings/files" rel="noopener noreferrer" data-polaris-unstyled="true">Don't

                                                                        have url? Click here</a>

                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                        <input id="logo" name="logo" data-style="src" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields sd_add_style" data-id="sd_logo_view" type="text" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['logo']; ?>">

                                                                        <div class="Polaris-TextField__Backdrop">

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>



                                                    </div>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Logo

                                                                            height</span>

                                                                    </label>

                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                        <input id="logo_height" name="logo_height" data-style="height" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields sd_add_style" data-id="sd_logo_view" type="number" min="1" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['logo_height']; ?>">

                                                                        <div class="Polaris-TextField__Spinner" aria-hidden="true">px</div>

                                                                        <div class="Polaris-TextField__Backdrop">

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>



                                                    </div>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Logo

                                                                            width</span>

                                                                    </label>

                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                        <input id="logo_width" name="logo_width" data-style="width" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields sd_add_style" data-id="sd_logo_view" type="number" min="1" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['logo_width']; ?>">

                                                                        <div class="Polaris-TextField__Spinner" aria-hidden="true">px</div>

                                                                        <div class="Polaris-TextField__Backdrop">

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>



                                                    </div>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label"><label id="PolarisSelect1Label" for="logo_alignment" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Logo

                                                                            alignment</span></label></div>

                                                            </div>

                                                            <div class="Polaris-Select">

                                                                <select id="logo_alignment" name="logo_alignment" data-style="text-align" class="Polaris-Select__Input sd_default_template_text_fields sd_add_style" data-id="sd_logo_align" aria-invalid="false" value="<?php echo isset($emailSettings['logo_alignment']) ? $emailSettings['logo_alignment'] : 'Left'; ?>">

                                                                    <option value="Left" <?php if (isset($emailSettings['logo_alignment']) && $emailSettings['logo_alignment'] == 'Left') {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                                        Left
                                                                    </option>
                                                                    <option value="Center" <?php if (isset($emailSettings['logo_alignment']) && $emailSettings['logo_alignment'] == 'Center') {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                                        Center
                                                                    </option>
                                                                    <option value="Right" <?php if (isset($emailSettings['logo_alignment']) && $emailSettings['logo_alignment'] == 'Right') {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                                        Right
                                                                    </option>

                                                                </select>

                                                                <div class="Polaris-Select__Content" aria-hidden="true">

                                                                    <span class="Polaris-Select__SelectedOption">
                                                                        <?php echo isset($emailSettings['logo_alignment']) ? $emailSettings['logo_alignment'] : ''; ?>
                                                                    </span>

                                                                    <span class="Polaris-Select__Icon">
                                                                        <span class="Polaris-Icon">
                                                                            <span class="Polaris-Text--root Polaris-Text--bodySm Polaris-Text--regular Polaris-Text--visuallyHidden"></span>
                                                                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                                <path d="M7.676 9h4.648c.563 0 .879-.603.53-1.014l-2.323-2.746a.708.708 0 0 0-1.062 0l-2.324 2.746c-.347.411-.032 1.014.531 1.014Zm4.648 2h-4.648c-.563 0-.878.603-.53 1.014l2.323 2.746c.27.32.792.32 1.062 0l2.323-2.746c.349-.411.033-1.014-.53-1.014Z"></path>
                                                                            </svg>
                                                                        </span>
                                                                    </span>

                                                                </div>

                                                                <div class="Polaris-Select__Backdrop">
                                                                </div>

                                                            </div>


                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">
                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">
                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">
                                                                            Logo Content background color
                                                                        </span>
                                                                    </label>
                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField example square">

                                                                        <div class="clr-field" style="color: <?php echo isset($emailSettings['logo_bg_color']) ? $emailSettings['logo_bg_color'] : 'white'; ?>">

                                                                            <button type="button" aria-labelledby="clr-open-label"></button>

                                                                            <input
                                                                                type="text"
                                                                                id="logo_content_bg_color"
                                                                                data-style="background"
                                                                                name="logo_bg_color"
                                                                                data-id="sd_logo_content_bg_color"
                                                                                class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input"
                                                                                value="<?php echo isset($emailSettings['logo_bg_color']) ? $emailSettings['logo_bg_color'] : '#BBF3E2'; ?>"
                                                                                data-coloris="">

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>


                                                </div>

                                            </div>

                                        </div>

                                        <div class="Polaris-Card__Section builder_port_section sd_settings_details heading_setting display-hide-label">

                                            <div class="inner_headbar">

                                                <div class="back_addfield_btn" attr-keyhide="zero-item">

                                                    <span class="Polaris-Icon Polaris-Icon--newDesignLanguage">

                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg_375hu" focusable="false" aria-hidden="true">

                                                            <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16z">

                                                            </path>

                                                        </svg>

                                                    </span>

                                                    <span>Back</span>

                                                </div>

                                                <div class="inner_headbar_heading go_to_menu">Header

                                                    style</div>

                                            </div>

                                            <div class="Polaris-FormLayout">

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Header

                                                                        text</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                    <input id="heading_text" maxlength="100" name="heading_text" data-style="url" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields" data-id="sd_heading_view" type="text" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo $emailSettings['heading_text']; ?>">

                                                                    <div class="Polaris-TextField__Backdrop">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>



                                                </div>

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Header

                                                                        text color</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField example square">

                                                                    <div class="clr-field" style="color: <?php echo isset($emailSettings['heading_text_color']) ? $emailSettings['heading_text_color'] : ''; ?>">

                                                                        <button type="button" aria-labelledby="clr-open-label"></button>

                                                                        <input
                                                                            type="text"
                                                                            id="heading_text_color"
                                                                            data-style="color"
                                                                            name="heading_text_color"
                                                                            data-id="sd_heading_view"
                                                                            class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input"
                                                                            value="<?php echo isset($emailSettings['heading_text_color']) ? $emailSettings['heading_text_color'] : ''; ?>"
                                                                            data-coloris="">

                                                                    </div>

                                                                </div>

                                                            </div>


                                                        </div>

                                                    </div>



                                                </div>



                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">

                                                                        Header background color</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField example square">

                                                                    <div class="clr-field" style="color: <?php echo isset($emailSettings['header_bg_color']) ? $emailSettings['header_bg_color'] : '#BBF3E2'; ?>">

                                                                        <button type="button" aria-labelledby="clr-open-label"></button>

                                                                        <input
                                                                            type="text"
                                                                            id="header_bg_color"
                                                                            data-style="background"
                                                                            name="header_bg_color"
                                                                            data-id="sd_header_bg_color"
                                                                            class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input"
                                                                            value="<?php echo isset($emailSettings['header_bg_color']) ? $emailSettings['header_bg_color'] : '#BBF3E2'; ?>"
                                                                            data-coloris="">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>



                                                </div>

                                            </div>

                                        </div>

                                        <div class="Polaris-Card__Section builder_port_section sd_settings_details email_content_setting display-hide-label">

                                            <div class="inner_headbar">

                                                <div class="back_addfield_btn" attr-keyhide="zero-item">

                                                    <span class="Polaris-Icon Polaris-Icon--newDesignLanguage">

                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg_375hu" focusable="false" aria-hidden="true">

                                                            <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16z">

                                                            </path>

                                                        </svg>

                                                    </span>

                                                    <span>Back</span>

                                                </div>

                                                <div class="inner_headbar_heading go_to_menu">Body content

                                                </div>

                                            </div>

                                            <div class="Polaris-FormLayout">

                                                <!-- <div class="Polaris-FormLayout__Item">

                                                            <div class="input_border">

                                                                <div class="Polaris-Labelled__LabelWrapper">

                                                                    <div class="Polaris-Label">

                                                                        <label id="PolarisTextField1Label"

                                                                            for="PolarisTextField1"

                                                                            class="Polaris-Label__Text">

                                                                            <span

                                                                                class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Email

                                                                                background color</span>

                                                                        </label>

                                                                    </div>

                                                                </div>

                                                                <div class="Polaris-Connected">

                                                                    <div

                                                                        class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                        <div class="Polaris-TextField example square">

                                                                            <div class="clr-field"

                                                                                style="color: {{ $emailSettings['email_background_color'] ?? '' }}">

                                                                                <button type="button"

                                                                                    aria-labelledby="clr-open-label"></button>

                                                                                <input type="text"

                                                                                    id="email_background_color"

                                                                                    data-style="background"

                                                                                    name="email_background_color"

                                                                                    data-id="sd_email_background_view"

                                                                                    class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1  Polaris-TextField__Input"

                                                                                    value="{{ $emailSettings['email_background_color'] ?? '' }}"

                                                                                    data-coloris="">

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>



                                                        </div> -->







                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Content

                                                                        background color</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField example square">

                                                                    <div class="clr-field" style="color: <?php echo isset($emailSettings['content_background']) ? $emailSettings['content_background'] : ''; ?>">

                                                                        <button type="button" aria-labelledby="clr-open-label"></button>

                                                                        <input
                                                                            type="text"
                                                                            id="content_background"
                                                                            data-style="background"
                                                                            name="content_background"
                                                                            data-id="sd_content_text_view"
                                                                            class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input"
                                                                            value="<?php echo isset($emailSettings['content_background']) ? $emailSettings['content_background'] : ''; ?>"
                                                                            data-coloris="">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>

                                                </div>

                                                <?php if ($templateName == 'membership_free_gifts' || $templateName == 'new_purchase_plans'): ?>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">
                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Free gift link text</span>
                                                                    </label>

                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                        <input
                                                                            id="gift_link_text"
                                                                            maxlength="100"
                                                                            name="gift_link_text"
                                                                            data-style="url"
                                                                            autocomplete="off"
                                                                            class="Polaris-TextField__Input sd_default_template_text_fields"
                                                                            data-id="sd_gift_link_text_view"
                                                                            type="text"
                                                                            placeholder=""
                                                                            aria-labelledby="PolarisTextField1Label"
                                                                            aria-invalid="false"
                                                                            value="<?php echo isset($emailSettings['gift_link_text']) ? $emailSettings['gift_link_text'] : ''; ?>">

                                                                        <div class="Polaris-TextField__Backdrop"></div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                <?php endif; ?>


                                                <?php if ($templateName == 'new_purchase_plans'): ?>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">
                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">
                                                                            Tick color
                                                                        </span>
                                                                    </label>

                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField example square">

                                                                        <div class="clr-field" style="color: <?php echo isset($emailSettings['tick_bg_color']) ? $emailSettings['tick_bg_color'] : 'white'; ?>">

                                                                            <button type="button" aria-labelledby="clr-open-label"></button>

                                                                            <input
                                                                                type="text"
                                                                                id="tick_bg_color"
                                                                                data-style="fill"
                                                                                name="tick_bg_color"
                                                                                data-id="sd_tick_bg_color"
                                                                                class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input"
                                                                                value="<?php echo isset($emailSettings['tick_bg_color']) ? $emailSettings['tick_bg_color'] : '#BBF3E2'; ?>"
                                                                                data-coloris="">

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                <?php endif; ?>




                                                <?php if ($templateName != 'new_purchase_plans'): ?>

                                                    <div class="Polaris-FormLayout__Item sd_content_heading">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">
                                                                <label id="PolarisSelect1Label" for="content_heading" class="Polaris-Label__Text">
                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">
                                                                        Body content text
                                                                    </span>
                                                                </label>
                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_content_heading_view" id="content_heading" name="content_heading"><?php echo isset($emailSettings['content_heading']) ? $emailSettings['content_heading'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                <?php endif; ?>



                                                <?php if ($templateName == 'new_purchase_plans'): ?>

                                                    <div class="Polaris-FormLayout__Item">

                                                        <div class="input_border">

                                                            <div class="Polaris-Labelled__LabelWrapper">

                                                                <div class="Polaris-Label">

                                                                    <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                        <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Features heading</span>

                                                                    </label>

                                                                </div>

                                                            </div>

                                                            <div class="Polaris-Connected">

                                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                    <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                        <input id="feature_heading" maxlength="100" name="feature_heading" data-style="url" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields" data-id="sd_feature_heading_view" type="text" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo isset($emailSettings['feature_heading']) ? $emailSettings['feature_heading'] : ''; ?>">

                                                                        <div class="Polaris-TextField__Backdrop">
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_content_heading_text">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="content_heading_text" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Body heading text</span></label></div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_content_heading_text_view" id="content_heading_text" name="content_heading_text"><?php echo isset($emailSettings['content_heading_text']) ? $emailSettings['content_heading_text'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_discount_coupon_content">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="discount_coupon_content" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Discount content text</span></label></div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_discount_coupon_content_view" id="discount_coupon_content" name="discount_coupon_content"><?php echo isset($emailSettings['discount_coupon_content']) ? $emailSettings['discount_coupon_content'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_free_shipping_coupon_content">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="free_shipping_coupon_content" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Freeship content text</span></label></div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_free_shipping_coupon_content_view" id="free_shipping_coupon_content" name="free_shipping_coupon_content"><?php echo isset($emailSettings['free_shipping_coupon_content']) ? $emailSettings['free_shipping_coupon_content'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_early_sale_content">
                                                        <div class="Polaris-Labelled__LabelWrapper">
                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="early_sale_content" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Early sale content text</span></label></div>
                                                        </div>
                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <textarea class="content customeditor sd_early_sale_content_view" id="early_sale_content" name="early_sale_content"><?php echo isset($emailSettings['early_sale_content']) ? $emailSettings['early_sale_content'] : ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_free_signup_product_content">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="free_signup_product_content" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Free signup product content text</span></label></div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_free_signup_product_content_view" id="free_signup_product_content" name="free_signup_product_content"><?php echo isset($emailSettings['free_signup_product_content']) ? $emailSettings['free_signup_product_content'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_free_gift_signup_product">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="free_gift_signup_product" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Immediate sign up gift text</span></label></div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_free_gift_signup_product_view" id="free_gift_signup_product" name="free_gift_signup_product"><?php echo isset($emailSettings['free_gift_signup_product']) ? $emailSettings['free_gift_signup_product'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-FormLayout__Item sd_footer_content">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label"><label id="PolarisSelect1Label" for="footer_content" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Body footer content text</span></label></div>

                                                        </div>

                                                        <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <textarea class="content customeditor sd_footer_content_view" id="footer_content" name="footer_content"><?php echo isset($emailSettings['footer_content']) ? $emailSettings['footer_content'] : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                <?php endif; ?>




                                            </div>

                                        </div>

                                        <div class="Polaris-Card__Section builder_port_section sd_settings_details manage_button_setting display-hide-label">

                                            <div class="inner_headbar">

                                                <div class="back_addfield_btn" attr-keyhide="zero-item">

                                                    <span class="Polaris-Icon Polaris-Icon--newDesignLanguage">

                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg_375hu" focusable="false" aria-hidden="true">

                                                            <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16z">

                                                            </path>

                                                        </svg>

                                                    </span>

                                                    <span>Back</span>

                                                </div>

                                                <div class="inner_headbar_heading go_to_menu">Button

                                                    style</div>

                                            </div>

                                            <div class="Polaris-FormLayout">

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Button

                                                                        text</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                    <input id="manage_button" name="manage_button" data-style="txt" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields" data-id="sd_manage_button_view" type="text" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" maxlength="50" value="<?php echo $emailSettings['manage_button'] ?? ''; ?>">

                                                                    <div class="Polaris-TextField__Backdrop">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>



                                                </div>

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Button

                                                                        url</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField Polaris-TextField--hasValue">

                                                                    <input id="manage_button_url" name="manage_button_url" data-style="href" autocomplete="off" class="Polaris-TextField__Input sd_default_template_text_fields" data-id="" type="text" placeholder="" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="<?php echo isset($emailSettings['manage_button_url']) ? $emailSettings['manage_button_url'] : ''; ?>">

                                                                    <div class="Polaris-TextField__Backdrop">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>



                                                </div>

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Button

                                                                        text color</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField example square">

                                                                    <div class="clr-field" style="color: <?php echo isset($emailSettings['manage_btn_text_color']) ? $emailSettings['manage_btn_text_color'] : ''; ?>">

                                                                        <button type="button" aria-labelledby="clr-open-label"></button>

                                                                        <input type="text" id="manage_btn_text_color" data-style="color" name="manage_btn_text_color" data-id="sd_manage_button_view" class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input" value="<?php echo isset($emailSettings['manage_btn_text_color']) ? $emailSettings['manage_btn_text_color'] : ''; ?>" data-coloris="">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>



                                                </div>

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">

                                                                        Button Content background color</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField example square">

                                                                    <div class="clr-field" style="color: <?php echo isset($emailSettings['button_content_bg_color']) ? $emailSettings['button_content_bg_color'] : 'white'; ?>">

                                                                        <button type="button" aria-labelledby="clr-open-label"></button>

                                                                        <input type="text" id="button_content_bg_color" data-style="background" name="button_content_bg_color" data-id="sd_button_content_bg_color" class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input" value="<?php echo isset($emailSettings['button_content_bg_color']) ? $emailSettings['button_content_bg_color'] : 'white'; ?>" data-coloris="">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>



                                                </div>

                                                <div class="Polaris-FormLayout__Item">

                                                    <div class="input_border">

                                                        <div class="Polaris-Labelled__LabelWrapper">

                                                            <div class="Polaris-Label">

                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                    <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Button

                                                                        background color</span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                        <div class="Polaris-Connected">

                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                                <div class="Polaris-TextField example square">

                                                                    <div class="clr-field" style="color: <?php echo isset($emailSettings['manage_btn_bg_color']) ? $emailSettings['manage_btn_bg_color'] : ''; ?>">

                                                                        <button type="button" aria-labelledby="clr-open-label"></button>

                                                                        <input type="text" id="manage_btn_bg_color" data-style="background" name="manage_btn_bg_color" data-id="sd_manage_button_view" class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input" value="<?php echo isset($emailSettings['manage_btn_bg_color']) ? $emailSettings['manage_btn_bg_color'] : ''; ?>" data-coloris="">

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>


                                                    </div>



                                                </div>

                                            </div>

                                        </div>

                                        <div class="Polaris-Card__Section builder_port_section sd_settings_details footer_setting display-hide-label">

                                            <div class="inner_headbar">

                                                <div class="back_addfield_btn" attr-keyhide="zero-item">

                                                    <span class="Polaris-Icon Polaris-Icon--newDesignLanguage">

                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg_375hu" focusable="false" aria-hidden="true">

                                                            <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16z">

                                                            </path>

                                                        </svg>

                                                    </span>

                                                    <span>Back</span>

                                                </div>

                                                <div class="inner_headbar_heading go_to_menu">Footer

                                                    style</div>

                                            </div>

                                            <div class="Polaris-FormLayout__Item">

                                                <div class="input_border">

                                                    <div class="Polaris-Labelled__LabelWrapper">

                                                        <div class="Polaris-Label">

                                                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">

                                                                <span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">

                                                                    Footer background color</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="Polaris-Connected">

                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                            <div class="Polaris-TextField example square">

                                                                <div class="clr-field" style="color: <?php echo isset($emailSettings['footer_bg_color']) ? $emailSettings['footer_bg_color'] : '#BBF3E2'; ?>">

                                                                    <button type="button" aria-labelledby="clr-open-label"></button>

                                                                    <input type="text" id="footer_bg_color" data-style="background" name="footer_bg_color" data-id="sd_footer_bg_color" class="input-color-textbox sd_default_template_text_fields sd_add_style coloris instance1 Polaris-TextField__Input" value="<?php echo isset($emailSettings['footer_bg_color']) ? $emailSettings['footer_bg_color'] : '#BBF3E2'; ?>" data-coloris="">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="Polaris-FormLayout">

                                        <div class="Polaris-FormLayout__Item sd_footer_text">

                                            <div class="Polaris-Labelled__LabelWrapper">

                                                <div class="Polaris-Label"><label id="PolarisSelect1Label" for="footer_text" class="Polaris-Label__Text"><span class="Polaris-Text--root Polaris-Text--bodyMd Polaris-Text--regular">Footer content</span></label></div>

                                            </div>

                                            <div class="Polaris-Connected Polaris-Connected--newDesignLanguage">

                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                                                    <textarea class="content customeditor sd_footer_text_view" id="footer_text" name="footer_text"><?php echo isset($emailSettings['footer_text']) ? $emailSettings['footer_text'] : ''; ?></textarea>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="Polaris-Layout__Section editable_email_template">
                            <div class="Polaris-Box" style="--pc-box-background:var(--p-color-bg-surface);--pc-box-min-height:100%;--pc-box-overflow-x:clip;--pc-box-overflow-y:clip;--pc-box-padding-block-start-xs:var(--p-space-400);--pc-box-padding-block-end-xs:var(--p-space-400);--pc-box-padding-inline-start-xs:var(--p-space-400);--pc-box-padding-inline-end-xs:var(--p-space-400)">

                                <div class="Polaris-Box__Header" style="border-bottom:1px solid #d4d4d4;">

                                    <h2 class="Polaris-Text--root Polaris-Text--headingMd Polaris-Text--semibold sd-preview-template">

                                        Preview</h2>

                                </div>

                                <div class="Polaris-Box__Section sd_default_template_preview">

                                    <div class="sd_template_view">

                                        <?php echo isset($emailSettings['email_template_html']) ? $emailSettings['email_template_html'] : ''; ?>

                                    </div>

                                </div>

                            </div>

                            <div class="Polaris-Box__Section sd_custom_template_preview display-hide-label">

                                <div class="sd_custom_email_html_view sd_custom_email_html">

                                    <?php if (!empty($emailSettings['custom_email_html'])) {
                                        echo htmlspecialchars_decode($emailSettings['custom_email_html'], ENT_QUOTES);
                                    } else {
                                        echo '<div style="border : 5px solid #eeeeee; padding:25px;">
                    
                    <div><svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000" preserveAspectRatio="xMidYMid meet">
                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                            <path d="M145 401 l-110 -6 -3 -159 c-1 -88 1 -163 5 -166 5 -4 51 -1 103 6 52 7 150 16 218 20 l122 7 -1 46 c0 25 -6 93 -12 151 l-12 105 -100 1 c-55 0 -149 -2 -210 -5z m275 -19 c0 -15 -141 -162 -155 -162 -8 0 -56 34 -107 76 -51 43 -88 71 -81 64 6 -7 9 -19 6 -26 -4 -10 0 -12 11 -8 12 5 15 2 10 -10 -5 -12 -2 -15 10 -10 12 5 15 2 10 -12 -5 -16 -4 -16 5 -4 10 13 15 8 12 -12 0 -4 7 -4 16 -1 12 5 15 2 10 -10 -4 -12 -2 -14 10 -10 12 5 15 2 10 -10 -4 -11 -2 -14 9 -10 7 3 14 0 14 -7 0 -6 4 -9 9 -5 5 3 12 1 16 -5 15 -24 48 -4 123 75 l80 85 7 -73 c4 -39 8 -97 8 -127 l2 -55 -133 -11 c-73 -7 -159 -15 -190 -19 l-57 -7 50 53 50 53 -59 -52 c-57 -49 -85 -62 -55 -25 8 9 9 14 3 10 -7 -4 -11 32 -13 118 -2 144 -13 133 124 138 183 7 245 7 245 -1z"/>
                            <path d="M180 350 c0 -6 26 -10 59 -10 33 0 63 5 66 10 4 6 -18 10 -59 10 -37 0 -66 -4 -66 -10z"/>
                            <path d="M340 206 c0 -5 72 -55 100 -70 8 -4 3 3 -12 16 -31 27 -88 62 -88 54z"/>
                        </g>
                    </svg></div>

                    <h1>Create Your Custom Email Template</h1><br>
                    <p>Dear Shop Owner,</p>
                    <p>We are excited to announce a new feature in our app that allows you to customize your email templates. With this feature, you can create a unique and engaging email experience for your brand.</p><br>
                    <p><b>How to use tags and copy.</b></p>
                    <p>Tags allow you to add personal information to your template, such as the customer name, email address, store name, coupon codes, etc.</p>
                    <p>Tags allow you to add personal information to your template. For example, if you want to add the customer name to your template, you can use the following code:</p><br>
                    <p style="background-color: #eeeeee;">{{customer_name}}, {{customer_email}}, {{store_name}}, {{coupon_code}}, {{percentage_discount}}</p><br>
                    <p>We hope you enjoy this new feature. If you have any questions or issues, please contact our support team.</p>
                    <p>Thank You</p>
                </div>';
                                    } ?>
                                </div>

                            </div>

                        </div>


                    </div>
                    <div id="PolarisPortalsContainer"></div>

            </form>

        </div>

    </div>




    <?php

    include("../footer.php");
    ?>