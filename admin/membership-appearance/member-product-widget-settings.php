<?php
include("../header.php");
include("../navigation.php");
$stmt = $db->query("SELECT * FROM product_widget_settings WHERE store = '$store' LIMIT 1");
$product_widget_settings = $stmt->fetch(PDO::FETCH_OBJ);
if (!$product_widget_settings) {
    $defaultValues = [
        'heading_text' => 'Membership Plans',
        'heading_text_color' => '#000000',
        'background_color1' => ' #fffafa0c',
        'background_color2' => '#ffffff',
        'radio_button_color' => '#000000',
        'text_color' => '#000000',
        'widget_outline_color' => '#4DFF9E',
        'charge_every_text' => 'charge every',
        'active_option_bgColor1' => '#fffff',
        'active_option_bgColor2' => '#fffff',
        'border_radius' => '1',
        'day_text' => 'day',
        'week_text' => 'week',
        'month_text' => 'month',
        'year_text' => 'year',
        'price_color' => '#000000',
        'description_text' => 'Description',
        'subscription_details_text' => 'Membership Details',
        'subscription_details' => 'Have complete control of your Membership. Skip, pause or cancel membership any time in accordance with the membership policy and your needs.',
        'description_background_color1' => '#ffffff',
        'description_background_color2' => '#ffffff',
    ];
    $product_widget_settings = (object) $defaultValues;
}
?>
<style>
    /* input fields design */
    .product_widget_settings .sd_field_div {
        display: inline-flex;
        flex-direction: column;
        position: relative;
        min-width: 0px;
        padding: 0px;
        margin: 0px;
        border: 0px;
        vertical-align: top;
        width: 100%;
    }

    .product_widget_settings .sd_field_label.MuiInputLabel-shrink {
        line-height: 1.5;
        font-size: 1.5rem;
        font-family: "Public Sans", sans-serif;
        font-weight: 600;
        color: rgb(99, 115, 129);
    }

    .product_widget_settings .sd_field_lab {
        padding: 0px;
        line-height: 1.57143;
        font-weight: 700;
        color: #fff;
        display: block;
        transform-origin: left top;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: calc(133% - 32px);
        position: absolute;
        left: 0px;
        top: 0px;
        transform: translate(14px, -9px) scale(0.75);
        transition: color 200ms cubic-bezier(0, 0, 0.2, 1) 0ms, transform 200ms cubic-bezier(0, 0, 0.2, 1) 0ms, max-width 200ms cubic-bezier(0, 0, 0.2, 1) 0ms;
        z-index: 1;
        pointer-events: auto;
        user-select: none;
        font-size: 15px;
        padding-right: 7px;
    }

    .product_widget_settings .sd_field_lab::after {
        content: "";
        position: absolute;
        background: #141e2d;
        width: 100%;
        height: 22px;
        left: 0;
        top: 0;
        z-index: -1;
    }

    .product_widget_settings .sd_input_class:focus-visible {
        outline: none;
    }

    .product_widget_settings .sd_input_outer_div {
        line-height: 1.4375em;
        font-size: 1rem;
        font-weight: 700;
        color: rgb(33, 43, 54);
        box-sizing: border-box;
        cursor: text;
        /* display: inline-flex; */
        -webkit-box-align: center;
        align-items: center;
        width: 100%;
        position: relative;
        border-radius: 8px;
    }

    .product_widget_settings .sd_input_class {
        font-style: inherit;
        font-variant: inherit;
        font-stretch: inherit;
        font-optical-sizing: inherit;
        font-kerning: inherit;
        font-feature-settings: inherit;
        font-variation-settings: inherit;
        letter-spacing: inherit;
        color: rgba(109, 113, 117, 1);
        border: 0px;
        /* box-sizing: content-box; */
        background: none;
        /* height: 1.4375em; */
        margin: 0px;
        -webkit-tap-highlight-color: transparent;
        display: block;
        min-width: 0px;
        width: 100%;
        animation-name: mui-auto-fill-cancel;
        animation-duration: 10ms;
        line-height: 1.57143;
        font-size: 11px;
        font-weight: 400;
        padding: 12.5px 14px;
    }

    .product_widget_settings .sd_fieldlist {
        text-align: left;
        position: absolute;
        inset: -5px 0px 0px;
        margin: 0px;
        padding: 0px 8px;
        pointer-events: none;
        border-radius: inherit;
        border-style: solid;
        border-width: 1px;
        overflow: hidden;
        min-width: 0%;
        border-color: #fff;
        transition: border-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    }

    .product_widget_settings .sd_legend {
        float: unset;
        width: auto;
        overflow: hidden;
        display: block;
        padding: 0px;
        height: 11px;
        font-size: 0.75em;
        visibility: hidden;
        max-width: 100%;
        transition: max-width 100ms cubic-bezier(0, 0, 0.2, 1) 50ms;
        white-space: nowrap;
    }

    .product_widget_settings .sd_legend>span {
        padding-left: 5px;
        padding-right: 5px;
        display: inline-block;
        opacity: 0;
        visibility: visible;
    }


    /* product widget css start */
    /* .product_widget_preview div.sd_plan_option {
        border-bottom: 1px solid #DBDBDB;
        padding-bottom: 10px;
        padding-top: 11px;
    }
    .product_widget_preview .purchase-label-price {
        /* color: #000; */
    /* font-size: 14px;
        font-weight: 500;
        letter-spacing: -.28px;
        display: flex;
        justify-content: space-between;
    }
    .product_widget_preview label {
        /* color: #666; */
    /* font-size: 14px;
        font-weight: 400;
        letter-spacing: -.24px;
        display: flex;
        align-items: center;
        column-gap: 10px;
    }
    .product_widget_preview input[type=radio] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
        font: inherit;
        color: currentColor;
        width: 18px;
        height: 18px;
        border: 4px solid #D9D9D9;
        border-radius: 50%;
        transform: translateY(-0.075em);
        display: grid;
        place-content: center;
    }
    .product_widget_preview .purchase-label-price {
        /* color: #000; */
    /* font-size: 14px;
        font-weight: 500;
        letter-spacing: -.28px;
        display: flex;
        justify-content: space-between;
    }   */
    /* .product_widget_preview input[type=radio]:checked {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
        font: inherit;
        color: currentColor;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        transform: translateY(-0.075em);
        display: grid;
        place-content: center;
    } */

    /* Add styles for the page container */
    .page-container1 {
        display: flex;
        justify-content: space-between;
        row-gap: 2rem;
    }

    legend.membership_plan_name {
        /* background: #000; */
        border-radius: 5px;
        padding: 3px 7px;
    }

    /* Style the builder container */
    /* .builder-container, .preview-container {
                flex: 1;
                width: 50%;
                box-shadow: rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px;
                border-radius: 16px;
                padding: 24px;
            } */

    /* Style the preview container */
    .preview-container {
        box-shadow: rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px;
        border-radius: 16px;
        padding: 24px;
    }

    .builder-container {
        width: 60%;
        box-shadow: rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px;
        border-radius: 16px;
        padding: 24px;
    }

    .input-color-textbox {
        width: auto;
        height: 32px;
        padding: 0 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: inherit;
        font-size: inherit;
        font-weight: inherit;
        box-sizing: border-box;
    }

    .example {
        margin-bottom: 1px;
        margin-top: -10px;
    }

    .square .clr-field button,
    .circle .clr-field button {
        width: 22px;
        height: 22px;
        left: 5px;
        right: auto;
        border-radius: 50%;
    }

    .square .clr-field input,
    .circle .clr-field input {
        padding-left: 36px !important;
        border: none;
    }

    .circle .clr-field button {
        border-radius: 50%;
    }

    .full .clr-field button {
        width: 100%;
        height: 100%;
        border-radius: 5px;
    }

    .product_widget_settings .Polaris-FormLayout__Item {
        margin-left: 1rem;
        max-width: 100%;
    }
</style>
<div class="sd-prefix-main">
    <div class="Polaris-Page-Header__Row">
        <!-- <div class="Polaris-Page-Header__BreadcrumbWrapper">
                <nav role="navigation"> <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership"
                        onclick="handleLinkRedirect('/all-appearance-settings')"> <span class="Polaris-Breadcrumbs__ContentWrapper">
                            <span class="Polaris-Breadcrumbs__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20"
                            class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                            <path
                            d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">
                            </path>
                            </svg> </span> </span> </span> </a> </nav>
            </div> -->
        <div class="Polaris-Page-Header__TitleWrapper">
            <div class="Polaris-Header-Title__TitleAndSubtitleWrapper plan_widget_top">
                <h1 class="Polaris-Heading">Product widget</h1>
                <!-- <a href="https://<?php //echo $_GET['shop'];
                                        ?>/admin/themes/current/editor?template=product&addAppBlockId=08cff738-e93f-46b7-83d1-dbbcea5a1fb3/product_block&target=template" target="_blank"><button class="sd_app_enable_btn">Add plan widget</button></a> -->
            </div>
        </div>
    </div>
    <div class="Polaris-Page__Content product_widget_settings">

        <div class="Polaris-Layout">
            <div class="Polaris-Layout__Section Polaris-Layout__Section--secondary">
                <form id="sd_product_widget_settings" method="post">
                    <div class="page-container1">
                        <div class="builder-container">
                            <div class="Polaris-FormLayout">
                                <div class="Polaris-FormLayout__Items">
                                    <!-- Widget heading -->
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Widget heading</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="heading_text" name="heading_text" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->heading_text) ? $product_widget_settings->heading_text : 'Membership Plans'; ?>" data-id="membership_plan_name" type-attr="text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Widget heading</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Charge every text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="charge_every_text" name="charge_every_text" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->charge_every_text) ? $product_widget_settings->charge_every_text : 'charge every'; ?>" type-attr="text" data-id="charge_every_text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Charge Every Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Items">
                                    <!-- Widget heading Text color -->
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="day_text" id="day_text">Day text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="day_text" name="day_text" type="text" data-id="day_text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->day_text) ? $product_widget_settings->day_text : 'day'; ?>" type-attr="text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Day Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Widget heading -->
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Week text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="week_text" name="week_text" type="text" data-id="week_text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->week_text) ? $product_widget_settings->week_text : 'week'; ?>" type-attr="text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Week Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Items">
                                    <!-- Widget heading -->
                                    <!-- Widget heading Text color -->
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="month_text" id="month_text">Month text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="month_text" name="month_text" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->month_text) ? $product_widget_settings->month_text : 'month'; ?>" type-attr="text" data-id="month_text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Month Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="year_text" id="year_text">Year text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="year_text" name="year_text" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->year_text) ? $product_widget_settings->year_text : 'year'; ?>" type-attr="text" data-id="year_text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Year Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Items">
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="description_text" id="description_text">Description text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="description_text" name="description_text" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->description_text) ? $product_widget_settings->description_text : 'Description'; ?>" type-attr="text" data-id="description_text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Description Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="subscription_details_text" id="subscription_details_text">Subscription details text</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="subscription_details_text" name="subscription_details_text" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->subscription_details_text) ? $product_widget_settings->subscription_details_text : 'Membership Details'; ?>" type-attr="text" data-id="subscription_details_text">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Subscription details Text</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Items">
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="subscription_details" id="subscription_details">Subscription details</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="subscription_details" name="subscription_details" type="text" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->subscription_details) ? $product_widget_settings->subscription_details : 'Have complete control of your Membership. Skip, pause or cancel membership any time in accordance with the membership policy and your needs.'; ?>" type-attr="text" data-id="subscription_details">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Subscription details</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="border_radius" id="border_radius">Border radius</label>
                                            <div class="sd_input_outer_div">
                                                <input aria-invalid="false" id="border_radius" name="border_radius" type="number" min="1" class="sd_input_class membershipAllTextBox" value="<?php echo isset($product_widget_settings->border_radius) ? $product_widget_settings->border_radius : '1'; ?>" type-attr="border-radius" data-id="widget_outline_color">
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Border Radius</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="Polaris-FormLayout__Items color">
                                    <!-- Widget heading -->

                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Radio button color</label>
                                            <div class="sd_input_outer_div">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->radio_button_color) ? $product_widget_settings->radio_button_color : '#000000'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="radio_button_color" name="radio_button_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->radio_button_color) ? $product_widget_settings->radio_button_color : '#000000'; ?>" data-coloris="" data-id="radio_button_color" type-attr="border-color">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Radio Button Color</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Heading text color</label>
                                            <div class="sd_input_outer_div">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->heading_text_color) ? $product_widget_settings->heading_text_color : '#000000'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="heading_text_color" name="heading_text_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->heading_text_color) ? $product_widget_settings->heading_text_color : '#000000'; ?>" data-coloris="" type-attr="color" data-id="membership_plan_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Heading Text Color</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Items color">
                                    <!-- Widget heading -->
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Text color</label>
                                            <div class="sd_input_outer_div">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->text_color) ? $product_widget_settings->text_color : '#000000'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="text_color" name="text_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->text_color) ? $product_widget_settings->text_color : '#000000'; ?>" data-coloris="" type-attr="color" data-id="widget_text_color">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Text color</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Widget outline color</label>
                                            <div class="sd_input_outer_div">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->widget_outline_color) ? $product_widget_settings->widget_outline_color : '#000000'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="widget_outline_color" name="widget_outline_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->widget_outline_color) ? $product_widget_settings->widget_outline_color : '#000000'; ?>" data-coloris="" data-id="widget_outline_color" type-attr="border-color">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <fieldset aria-hidden="true" class="sd_fieldlist">
                                                    <legend class="sd_legend"><span>Widget Outline Color</span></legend>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="background_color1">Background color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Items">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->background_color1) ? $product_widget_settings->background_color1 : '#fffafa0c'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="background_color1" name="background_color1" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->background_color1) ? $product_widget_settings->background_color1 : '#fffafa0c'; ?>" type-attr="gradient-color" data-id="widget_outline_color" data-coloris="" gradient-code="widgetBgColor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->background_color2) ? $product_widget_settings->background_color2 : '#ffffff'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="background_color2" name="background_color2" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->background_color2) ? $product_widget_settings->background_color2 : '#ffffff'; ?>" type-attr="gradient-color" data-id="widget_outline_color" data-coloris="" gradient-code="widgetBgColor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Background Color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>


                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="background_color1">Active option background color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Items">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->active_option_bgColor1) ? $product_widget_settings->active_option_bgColor1 : '#ffffff'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="active_option_bgColor1" name="active_option_bgColor1" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->active_option_bgColor) ? $product_widget_settings->active_option_bgColor : '#ffffff'; ?>" data-coloris="" type-attr="gradient-color" data-id="active_option_bgColor" type-attr="bg-color" gradient-code="activeOptionBGcolor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->active_option_bgColor2) ? $product_widget_settings->active_option_bgColor2 : '#ffffff'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="active_option_bgColor2" name="active_option_bgColor2" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->active_option_bgColor2) ? $product_widget_settings->active_option_bgColor2 : '#ffffff'; ?>" type-attr="gradient-color" data-coloris="" data-id="active_option_bgColor" type-attr="bg-color" gradient-code="activeOptionBGcolor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Active Option Background Color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="background_color1">Description background color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Items">
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->description_background_color1) ? $product_widget_settings->description_background_color1 : '#ffffff'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="description_background_color1" name="description_background_color1" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->description_background_color1) ? $product_widget_settings->description_background_color1 : '#ffffff'; ?>" data-coloris="" type-attr="gradient-color" data-id="active_option_bgColor" type-attr="bg-color" gradient-code="activeOptionBGcolor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="Polaris-FormLayout__Item">
                                                    <div class="Polaris-Connected">
                                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                            <div class="Polaris-TextField example square">
                                                                <div class="clr-field" style="color: <?php echo isset($product_widget_settings->description_background_color2) ? $product_widget_settings->description_background_color2 : '#ffffff'; ?>">
                                                                    <button type="button" aria-labelledby="clr-open-label"></button>
                                                                    <input type="text" id="description_background_color2" name="description_background_color2" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->description_background_color2) ? $product_widget_settings->description_background_color2 : '#ffffff'; ?>" type-attr="gradient-color" data-coloris="" data-id="active_option_bgColor" type-attr="bg-color" gradient-code="activeOptionBGcolor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Description background color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="price_color" id="price_color"> Price color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?php echo isset($product_widget_settings->price_color) ? $product_widget_settings->price_color : '#000000'; ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="price_color" name="price_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?php echo isset($product_widget_settings->price_color) ? $product_widget_settings->price_color : '#000000'; ?>" data-coloris="" data-id="price_color" type-attr="color">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Price Color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="sd_plan_widget_right_column">
                            <h1 class="sd_headingText Polaris-Heading sd_headingtextColor" style="color: #ffffff; text-align:center">Preview</h1>
                            <div class="preview-container product_widget_preview" id="previewContainer">
                                <div class="sd_membership_widget_wrapper" id="sd_membership_widget_wrapper">
                                    <fieldset class="widget_outline_color" style="border:2px solid <?php echo isset($product_widget_settings->widget_outline_color) ? $product_widget_settings->widget_outline_color : '#000000'; ?>; background: linear-gradient(to right,<?php echo isset($product_widget_settings->background_color1) ? $product_widget_settings->background_color1 : '#fffafa0c'; ?>,  <?php echo isset($product_widget_settings->background_color2) ? $product_widget_settings->background_color2 : '#ffffff'; ?>); border-radius:<?php echo isset($product_widget_settings->border_radius) ? $product_widget_settings->border_radius : 1; ?>px;">
                                        <legend class="membership_plan_name" style="color:<?php echo isset($product_widget_settings->heading_text_color) ? $product_widget_settings->heading_text_color : '#000000'; ?>">
                                            <?php echo isset($product_widget_settings->heading_text) ? $product_widget_settings->heading_text : 'Membership Plans'; ?></legend>
                                        <div class="sd_membership_wrapper">
                                            <div class="sd_membership_wrapper">
                                                <div class="sd_membership_radio_wrapper widget_text_color" style="color:<?php echo isset($product_widget_settings->text_color) ? $product_widget_settings->text_color : '#000000'; ?>;">
                                                    <div class="sd_membership_amount_wrapper">
                                                        <div class="sd_plan_option active_option_bgColor widget_outline_color" style=" border-radius:<?php echo isset($product_widget_settings->border_radius) ? $product_widget_settings->border_radius : 1; ?>px;  display: block; background: linear-gradient(to right,<?php echo isset($product_widget_settings->active_option_bgColor1) ? $product_widget_settings->active_option_bgColor1 : '#ffffff'; ?>,  <?php echo isset($product_widget_settings->active_option_bgColor2) ? $product_widget_settings->active_option_bgColor2 : '#ffffff'; ?>);">
                                                            <div class="purchase-label-price">
                                                                <label>
                                                                    <input type="radio" checked="" class="radio_button_color" style="border: 4px solid <?php echo isset($product_widget_settings->radio_button_color) ? $product_widget_settings->radio_button_color : '#000000'; ?>"><span class="charge_every_text"><?php echo isset($product_widget_settings->charge_every_text) ? $product_widget_settings->charge_every_text : 'charge every'; ?></span>
                                                                    1 <span class="day_text"><?php echo isset($product_widget_settings->day_text) ? $product_widget_settings->day_text : 'day'; ?></span></label>
                                                                <span class="sd_membership_amount price_color" style="color: <?php echo isset($product_widget_settings->price_color) ? $product_widget_settings->price_color : '#000000'; ?>">€0,95 EUR</span>
                                                            </div>
                                                        </div>
                                                        <div class="sd_plan_option" style="display: block;">
                                                            <div class="purchase-label-price">
                                                                <label>
                                                                    <input type="radio"><span class="charge_every_text"><?php echo isset($product_widget_settings->charge_every_text) ? $product_widget_settings->charge_every_text : 'charge every'; ?></span>
                                                                    1 <span class="week_text"><?php echo isset($product_widget_settings->week_text) ? $product_widget_settings->week_text : 'week'; ?></span></label>
                                                                <span class="sd_membership_amount price_color" style="color: <?php echo isset($product_widget_settings->price_color) ? $product_widget_settings->price_color : '#000000'; ?>">€0,95 EUR</span>
                                                            </div>
                                                        </div>
                                                        <div class="sd_plan_option" style="display: block;">
                                                            <div class="purchase-label-price">
                                                                <label>
                                                                    <input type="radio"><span class="charge_every_text"><?php echo isset($product_widget_settings->charge_every_text) ? $product_widget_settings->charge_every_text : 'charge every'; ?></span>
                                                                    1 <span class="month_text"><?php echo isset($product_widget_settings->month_text) ? $product_widget_settings->month_text : 'month'; ?></span></label>
                                                                <span class="sd_membership_amount price_color" style="color: <?php echo isset($product_widget_settings->price_color) ? $product_widget_settings->price_color : '#000000'; ?>">€0,95 EUR</span>
                                                            </div>
                                                        </div>
                                                        <div class="sd_plan_option" style="display: block;">
                                                            <div class="purchase-label-price">
                                                                <label>
                                                                    <input type="radio"><span class="charge_every_text"><?php echo isset($product_widget_settings->charge_every_text) ? $product_widget_settings->charge_every_text : 'charge every'; ?></span>
                                                                    1 <span class="year_text"><?php echo isset($product_widget_settings->year_text) ? $product_widget_settings->year_text : 'year'; ?></span></label>
                                                                <span class="sd_membership_amount price_color" style="color: <?php echo isset($product_widget_settings->price_color) ? $product_widget_settings->price_color : '#000000'; ?>">€0,95 EUR</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="SD_saveButtonWidgets">
                                <button id="resetProductWidgets" class="Polaris-Button Polaris-Button--primary sd_ProductWidgetsValue" type="button" btn-type="Reset">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Reset</span>
                                    </span>
                                </button>
                                <button id="saveProductWidgets" class="Polaris-Button Polaris-Button--primary sd_ProductWidgetsValue" type="button" btn-type="Save">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Save</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script></script>
<?php
include('../footer.php');
?>