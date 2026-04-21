<?php
include("../header.php");
include("../navigation.php");
$count_down_settings_Query = $db->query("SELECT * FROM count_down_settings WHERE store = '$store' LIMIT 1");
$countDownData = $count_down_settings_Query->fetch(PDO::FETCH_OBJ);
if (!$countDownData) {
    $default_values = [
        'drawer_heading' => 'Discounts & Offers ',
        'free_shipping_text' => 'Use code {free_shipping} to get free shipping.',
        'percent_off_text' => 'use code {discount_name} to get {discount_value} off.',
        'discount_button_text' => 'Apply Discounts',
        'discount_button_radius' => '1',
        'drawer_background_color' => '#ffffff',
        'heading_text_color' => '#000000',
        'all_text_color' => '#000000',
        'cross_button_color' => '#000000',
        'button_bg_color' => '#000000',
        'button_text_color' => '#ffffff',
        'copied_message_text' => 'Code {discount_name} copied successfully.',
        'scissor_color' => '#ffffff',
        'code_bar_inner_bg_color' => '#000000',
        'code_bar_border_color' => '#000000',
        'code_bar_inner_color' => '#ffffff',
        'code_bg_color' => '#63db69',
        'code_bar_color' => '#000000',
        'copied_button_color' => '#000000',
        'free_shipping_minimum_quantity' => 'Applicable on minimum {minimum_quantity} quantity.',
        'free_shipping_minimum_amount' => 'Applicable on minimum {minimum_amount} order amount.',
        'percent_off_product_text' => 'Applicable only on {product_name}',
        'percent_off_collection_text' => 'Applicable only on {collection_name} collection products'
    ];
    $countDownData = (object) $default_values;
}
?>
<style>
    /* input fields design */
    .countDownData .sd_field_div {
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

    .countDownData .sd_field_label.MuiInputLabel-shrink {
        line-height: 1.5;
        font-size: 1.5rem;
        font-family: "Public Sans", sans-serif;
        font-weight: 600;
        color: rgb(99, 115, 129);
    }

    .countDownData .sd_field_lab {
        padding-right: 7px;
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
        transition: color 200ms cubic-bezier(0, 0, 0.2, 1) 0ms,
            transform 200ms cubic-bezier(0, 0, 0.2, 1) 0ms,
            max-width 200ms cubic-bezier(0, 0, 0.2, 1) 0ms;
        z-index: 1;
        pointer-events: auto;
        user-select: none;
        font-size: 15px;
    }

    .countDownData .sd_field_lab::after {
        content: "";
        position: absolute;
        background: #141e2d;
        width: 100%;
        height: 22px;
        left: 0;
        top: 0;
        z-index: -1;
    }

    .countDownData .sd_input_class:focus-visible {
        outline: none;
    }

    .countDownData .sd_input_outer_div {
        line-height: 1.4375em;
        font-size: 1rem;
        font-family: "Public Sans", sans-serif;
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

    .countDownData .sd_input_class {
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
        /* font-size: 11px; */
        font-family: "Public Sans", sans-serif;
        font-weight: 400;
        padding: 12.5px 14px;
    }

    .countDownData .sd_fieldlist {
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

    .countDownData .sd_legend {
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

    .countDownData .sd_legend>span {
        padding-left: 5px;
        padding-right: 5px;
        display: inline-block;
        opacity: 0;
        visibility: visible;
    }

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

    /* Style the preview container */
    .preview-container {
        box-shadow: rgba(145, 158, 171, 0.2) 0px 0px 2px 0px,
            rgba(145, 158, 171, 0.12) 0px 12px 24px -4px;
        border-radius: 16px;
        padding: 24px;
    }

    .builder-container {
        width: 60%;
        box-shadow: rgba(145, 158, 171, 0.2) 0px 0px 2px 0px,
            rgba(145, 158, 171, 0.12) 0px 12px 24px -4px;
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

    .countDownData .Polaris-FormLayout__Item {
        /* margin-left: 1rem; */
        max-width: 100%;
    }

    .sd_count-down-all-layouts .countdown_lauout_main {
        margin-bottom: 1rem;
    }

    .sd_count-down-all-layouts .countdown_lauout_main:first-child {
        margin-top: 1rem;
    }

    .sd_count-down-all-layouts .countdown_lauout_main:last-child {
        margin-bottom: 0;
    }

    .countdown_lauout_main {
        border: 1px solid #c6bfe0;
        fill: #faf9ff;
        stroke-width: 1px;
        background: #faf9ff;
        width: 100%;
        max-width: 500px;
    }

    .countdown_lauout_1 {
        display: flex;
        justify-content: space-around;
        padding: 20px 15px;
        align-items: center;
    }

    .countdown_heading h4 {
        color: #c6bfe0;
        font-size: 16px;
        font-weight: 700;
        line-height: 21px;
        letter-spacing: -0.32px;
        max-width: 100px;
    }

    .countdown_lauout_timing {
        text-align: center;
        position: relative;
    }

    .countdown_lauout_timing h3 {
        color: #c6bfe0;
        font-size: 18px;
        font-weight: 700;
        line-height: normal;
        border: 1px solid #c6bfe0;
        background: #faf9ff;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        margin: auto;
    }

    .countdown_lauout_timing p,
    .singlebanner_lauout1_content p {
        color: #c6bfe0;
        font-weight: 500;
        line-height: normal;
        letter-spacing: -0.16px;
        font-size: 12px;
        padding-top: 3px;
    }

    .countdown_lauout_timing span {
        position: absolute;
        top: 7px;
        right: -27px;
        font-size: 25px;
        color: #c5bfe0;
    }

    .countdown_lauout_2 h3 {
        border: none;
        font-size: 28px;
    }

    .countdown_lauout_2 .countdown_lauout_timing span,
    .countdown_lauout_3 .countdown_lauout_timing span {
        right: -100%;
        transform: translateX(-100%);
    }

    .countdown_lauout_1.countdown_lauout_2,
    .countdown_lauout_1.countdown_lauout_3 {
        padding: 20px 30px;
    }

    .countdown_lauout_1.countdown_lauout_3 {
        display: block;
    }

    .countdown_lauout_inner3 {
        display: flex;
        justify-content: space-between;
    }

    .countdown_lauout_3 .countdown_heading h4,
    .countdown_lauout_4 .countdown_heading h4 {
        text-align: center;
        max-width: 100%;
        margin-bottom: 10px;
    }

    .countdown_lauout_4 {
        padding: 12px 25px;
    }

    .countdown_lauout_dbl h3 {
        border-radius: 2px;
        width: 26px;
        background: #c6bfe0;
        color: #fff;
    }

    .countdown_lauout_dbl {
        display: flex;
        gap: 1px;
    }

    .countdown_lauout_4 .countdown_lauout_timing span {
        right: -35px;
    }

    .Sd_saveButtonCountdownWidgets {
        text-align: right;
        margin-top: 1rem;
    }

    .sd-sale-banner {
        font-family: 'Roboto', sans-serif;
    }

    .sd-sale-banner p {
        color: #fff;
        padding: 5px 10px 5px 10px;
        text-align: center;
        /* font-size: 2em */
    }

    .upgrade-plan-msg {
        font-size: 16px;
    }

    .sd_clickme {
        color: blue;
        cursor: pointer;
    }

    .cart-btn-tooltip {
        position: absolute;
        top: -23px;
        right: -25px;
    }

    #sd_sale_countdown_settings .Polaris-IndexTable__TableCell {
        background: #0b111a;
        color: #fff;
        border-radius: 7px;
        border: none !important;
    }

    .sd_field_div .sd_input_outer_div select {
        background: transparent;
    }

    .countDownData .sd_input_outer_div .Polaris-FormLayout__Item {
        margin-left: 8px;
    }
</style>


<div class="sd-prefix-main">
    <div class="Polaris-Page-Header__Row">
        <div class="Polaris-Page-Header__TitleWrapper">
            <div class="Polaris-Header-Title__TitleAndSubtitleWrapper plan_widget_top">
                <h1 class="Polaris-Heading">Early sale countdown settings</h1>
            </div>
        </div>
    </div>
    <div class="Polaris-Page__Content countDownData">
        <div class="Polaris-Layout">
            <div class="Polaris-Layout__Section Polaris-Layout__Section--secondary">
                <form id="sd_sale_countdown_settings" method="post">
                    <div class="page-container1">
                        <div class="builder-container">
                            <div class="Polaris-FormLayout">
                                <!-- inderjit-s16.myshopify.com -->

                                <div class="Polaris-FormLayout__Items" style="display: flex;">
                                    <!-- Show add to cart button  -->
                                    <div class="Polaris-FormLayout__Item">
                                        <table class="Polaris-IndexTable__Table Polaris-IndexTable__Table--unselectable Polaris-IndexTable__Table--sticky">
                                            <tbody>
                                                <tr class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                    <td class="Polaris-IndexTable__TableCell">
                                                        <span class="sd_member_permission">Show add to cart button</span>
                                                        <label class="switch">
                                                            <input type="checkbox" class="show_cart_btn" id="sd_show_cart_btn"
                                                                <?php echo (isset($countDownData) && $countDownData->show_cart_btn == '1') ? 'checked' : ''; ?>>
                                                            <span class="slider round"></span>
                                                            <div class="Polaris-FormLayout__Item cart-btn-tooltip">
                                                                <div class="tooltip">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                        <path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"></path>
                                                                        <path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"></path>
                                                                        <path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"></path>
                                                                    </svg>
                                                                    <span class="tooltiptext" style="width: 270px;white-space: normal;bottom: 0;">
                                                                        If you enable this checkbox, the 'Add to Cart' button will be shown on the storefront, allowing customers to buy the product at its original price before the sale. And if you disable the checkbox, the 'Add to Cart' button will not be shown until the sale starts.
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                                <div style="margin-top: 10px;">
                                    <div class="Polaris-FormLayout__Items">
                                        <!-- Select the layout -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="sd_select_countdown_layout" id="select_layout_alignment">Select the layout</label>
                                                <div class="sd_input_outer_div">
                                                    <select id="sd_select_countdown_layout" name="select_layout" class="sd_select_layout_alignment countDownAllTextBox" aria-invalid="false" type-attr="select-option" data-id="sd_headingTag">
                                                        <?php
                                                        if ($countDownData === null) {
                                                            $countDownData = new stdClass();
                                                            $countDownData->select_layout = 'layout1';
                                                        }
                                                        ?>
                                                        <option value="layout1" <?php echo (isset($countDownData->select_layout) && $countDownData->select_layout == 'layout1') ? 'selected' : ''; ?>>Layout 1</option>
                                                        <option value="layout2" <?php echo (isset($countDownData->select_layout) && $countDownData->select_layout == 'layout2') ? 'selected' : ''; ?>>Layout 2</option>
                                                        <option value="layout3" <?php echo (isset($countDownData->select_layout) && $countDownData->select_layout == 'layout3') ? 'selected' : ''; ?>>Layout 3</option>
                                                        <option value="layout4" <?php echo (isset($countDownData->select_layout) && $countDownData->select_layout == 'layout4') ? 'selected' : ''; ?>>Layout 4</option>
                                                    </select>
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Select the layout</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- set the title text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="title_text" id="title_text">Sale start text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_title_text" name="title_text" type="text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->title_text) ? $countDownData->title_text : 'Big Sale'; ?>" type-attr="text" data-id="title_text">
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Sale start text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Polaris-FormLayout__Items">
                                        <!-- Day text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="day_text" id="day_text">Days text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_day_text" name="day_text" type="text" data-id="day_text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->day_text) ? $countDownData->day_text : 'Day'; ?>" type-attr="text" />
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Day Text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hours Text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="hour_text" id="hour_text">Hours text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_hour_text" name="hour_text" type="text" data-id="hour_text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->hours_text) ? $countDownData->hours_text : 'Hour'; ?>" type-attr="text" />
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Hours Text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="Polaris-FormLayout__Items">
                                        <!-- Mint text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="mint_text" id="mint_text">Minutes text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_mint_text" name="mint_text" type="text" data-id="mint_text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->mint_text) ? $countDownData->mint_text : 'Min'; ?>" type-attr="text" />
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Minutes Text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Seconds Text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Seconds text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_second_text" name="second_text" type="text" data-id="second_text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->second_text) ? $countDownData->second_text : 'Sec'; ?>" type-attr="text" />
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Second Text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Polaris-FormLayout__Items">
                                        <!-- Sale End Text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="sale_end_text" id="sale_end_text">Sale end text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_sale_end_text" name="sale_end_text" type="text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->sale_end_text) ? $countDownData->sale_end_text : 'Sales ends in'; ?>" type-attr="text" data-id="sale_end_text">
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Sale end text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Discount Text -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="discount_text" id="discount_text">Discount text</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_discount_text" name="discount_text" type="text" data-id="discount_text" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->discount_text) ? $countDownData->discount_text : 'Use {discount_code} coupon code and get {discount_off}% Off on checkout'; ?>" type-attr="text" />
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Discount Text</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Polaris-FormLayout__Items">
                                        <!-- Outer Border Radius -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="outer_border_radius" id="outer_border_radius">Outer border radius</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="sd_outer_border_radius" name="outer_border_radius" type="number" min="1" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->outer_border_radius) ? $countDownData->outer_border_radius : '5'; ?>" type-attr="border-radius" data-id="outer_radius">
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Outer border radius</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Outer Border Color -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="outer_border_color" id="outer_border_color">Outer border color</label>
                                                <div class="sd_input_outer_div">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField example square">
                                                                    <div class="clr-field" style="color: <?php echo isset($countDownData->outer_border_color) ? $countDownData->outer_border_color : '#000000'; ?>;">
                                                                        <button type="button" aria-labelledby="clr-open-label"></button>
                                                                        <input type="text" id="sd_outer_border_color" name="outer_border_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input countDownAllTextBox" value="<?php echo isset($countDownData->outer_border_color) ? $countDownData->outer_border_color : '#000000'; ?>" data-coloris="" data-id="outer_border_color" type-attr="border-color">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Outer border Color</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="Polaris-FormLayout__Items">
                                        <!-- set the inner radius -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="inner_border_radius" id="inner_border_radius">Inner border radius</label>
                                                <div class="sd_input_outer_div">
                                                    <input aria-invalid="false" id="inner_border_radius" name="inner_border_radius" type="number" min="1" class="sd_input_class countDownAllTextBox" value="<?php echo isset($countDownData->inner_border_radius) ? $countDownData->inner_border_radius : '5'; ?>" type-attr="border-radius" data-id="inner_radius">
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Border Radius</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- set the inner border color -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="inner_border_color" id="inner_border_color">Inner border color</label>
                                                <div class="sd_input_outer_div">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField example square">
                                                                    <div class="clr-field" style="color: <?php echo isset($countDownData->inner_border_color) ? $countDownData->inner_border_color : '#000000'; ?>">
                                                                        <button type="button" aria-labelledby="clr-open-label"></button>
                                                                        <input type="text" id="sd_inner_border_color" name="inner_border_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input countDownAllTextBox" value="<?php echo isset($countDownData->inner_border_color) ? $countDownData->inner_border_color : '#000000'; ?>" data-coloris="" data-id="inner_border_color" type-attr="border-color">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Inner border Color</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- set the title text color -->
                                    <div class="Polaris-FormLayout__Items">
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="title_text_color" id="title_text_color">Text color</label>
                                                <div class="sd_input_outer_div mb-10">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField example square">
                                                                    <div class="clr-field" style="color: <?php echo isset($countDownData->text_color) ? $countDownData->text_color : '#000000'; ?>">
                                                                        <button type="button" aria-labelledby="clr-open-label"></button>
                                                                        <input type="text" id="sd_text_color" name="text_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input countDownAllTextBox" value="<?php echo isset($countDownData->text_color) ? $countDownData->text_color : '#000000'; ?>" data-coloris="" data-id="text_color" type-attr="color">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Text Color</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- set the time text color -->
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="MuiTextField-root sd_field_div">
                                                <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="time_text_color" id="time_text_color">Time text color</label>
                                                <div class="sd_input_outer_div mb-10">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField example square">
                                                                    <div class="clr-field" style="color: <?php echo isset($countDownData->time_text_color) ? $countDownData->time_text_color : '#ffffff'; ?>">
                                                                        <button type="button" aria-labelledby="clr-open-label"></button>
                                                                        <input type="text" id="sd_time_text_color" name="time_text_color" class="coloris instance1 input-color-textbox Polaris-TextField__Input countDownAllTextBox" value="<?php echo isset($countDownData->time_text_color) ? $countDownData->time_text_color : '#ffffff'; ?>" data-coloris="" data-id="time_text_color" type-attr="color">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset aria-hidden="true" class="sd_fieldlist">
                                                        <legend class="sd_legend"><span>Time text color</span></legend>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- set the background color -->
                                    <div class="Polaris-FormLayout__Item">
                                        <div class="MuiTextField-root sd_field_div">
                                            <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="outer_bgcolor1">Outer background color</label>
                                            <div class="sd_input_outer_div mb-10">
                                                <div class="Polaris-FormLayout__Items mb-0">
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField example square">
                                                                    <div class="clr-field" style="color: <?php echo isset($countDownData->outer_bgcolor1) ? $countDownData->outer_bgcolor1 : '#ffffff'; ?>">
                                                                        <button type="button" aria-labelledby="clr-open-label"></button>
                                                                        <input type="text" id="outer_bgcolor1" name="outer_bgcolor1" class="coloris instance1 input-color-textbox Polaris-TextField__Input countDownAllTextBox" value="<?php echo isset($countDownData->outer_bgcolor1) ? $countDownData->outer_bgcolor1 : '#ffffff'; ?>" type-attr="gradient-color" data-id="outer_bg_color" data-coloris="" gradient-code="outerCountDownBgColor">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="Polaris-FormLayout__Item">
                                                        <div class="Polaris-Connected">
                                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                <div class="Polaris-TextField example square">
                                                                    <div class="clr-field" style="color: <?php echo isset($countDownData->outer_bgcolor2) ? $countDownData->outer_bgcolor2 : '#ffffff'; ?>">
                                                                        <button type="button" aria-labelledby="clr-open-label"></button>
                                                                        <input type="text" id="outer_bgcolor2" name="outer_bgcolor2" class="coloris instance1 input-color-textbox Polaris-TextField__Input countDownAllTextBox" value="<?php echo isset($countDownData->outer_bgcolor2) ? $countDownData->outer_bgcolor2 : '#ffffff'; ?>" type-attr="gradient-color" data-id="outer_bg_color" data-coloris="" gradient-code="outerCountDownBgColor">
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
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="sd_plan_widget_right_column">
                            <h1 class="sd_headingText Polaris-Heading sd_headingtextColor" style="color: #ffffff; text-align: center">
                                Preview
                            </h1>
                            <div class="preview-container product_widget_preview" id="previewContainer">
                                <div class="sd_membership_widget_wrapper sd_count-down-all-layouts" id="sd_countdown_time_wrapper">
                                    <div class="countdown_lauout_main layout1 outer_radius outer_border_color outer_bg_color"
                                        style="display: <?= ($countDownData->select_layout == 'layout1') ? 'block' : 'none'; ?>;
           border-color: <?= $countDownData->outer_border_color ?? '#000000'; ?>;
           border-radius: <?= $countDownData->outer_border_radius ?? '5'; ?>px;
           background: linear-gradient(to right, <?= $countDownData->outer_bgcolor1 ?? '#ffffff'; ?>, <?= $countDownData->outer_bgcolor2 ?? '#ffffff'; ?>);">

                                        <div class="countdown_lauout_1">
                                            <div class="countdown_heading">
                                                <h4 class="title_text sale_end_text text_color"
                                                    style="color: <?= $countDownData->text_color ?? '#000000'; ?>">
                                                    <?= $countDownData->title_text ?? 'Big Sale'; ?>
                                                </h4>
                                            </div>

                                            <?php for ($i = 0; $i < 4; $i++): ?>
                                                <div class="countdown_lauout_timing">
                                                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color"
                                                        style="background: linear-gradient(to right, <?= $countDownData->inner_bgcolor1 ?? '#000000'; ?>, <?= $countDownData->inner_bgcolor2 ?? '#000000'; ?>);
                           border-color: <?= $countDownData->inner_border_color ?? '#000000'; ?>;
                           color: <?= $countDownData->time_text_color ?? '#ffffff'; ?>;
                           border-radius: <?= $countDownData->inner_border_radius ?? '5'; ?>px;">
                                                        00
                                                    </h3>
                                                    <?php if ($i < 3): ?>
                                                        <span class="text_color" style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">:</span>
                                                    <?php endif; ?>

                                                    <?php
                                                    $labels = ['day_text' => 'Day', 'hour_text' => 'Hour', 'mint_text' => 'Min', 'second_text' => 'Sec'];
                                                    $keys = array_keys($labels);
                                                    ?>
                                                    <p class="<?= $keys[$i]; ?> text_color"
                                                        style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">
                                                        <?= $countDownData->{$keys[$i]} ?? $labels[$keys[$i]]; ?>
                                                    </p>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>


                                    <div class="countdown_lauout_main layout2 outer_radius outer_border_color outer_bg_color"
                                        style="display: <?= ($countDownData->select_layout == 'layout2') ? 'block' : 'none'; ?>;
           border-color: <?= $countDownData->outer_border_color ?? '#000000'; ?>;
           border-radius: <?= $countDownData->outer_border_radius ?? '5'; ?>px;
           background: linear-gradient(to right, <?= $countDownData->outer_bgcolor1 ?? '#ffffff'; ?>, <?= $countDownData->outer_bgcolor2 ?? '#ffffff'; ?>);">

                                        <div class="countdown_lauout_1 countdown_lauout_2">
                                            <?php for ($i = 0; $i < 4; $i++): ?>
                                                <div class="countdown_lauout_timing">
                                                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color"
                                                        style="background: linear-gradient(to right, <?= $countDownData->inner_bgcolor1 ?? '#000000'; ?>, <?= $countDownData->inner_bgcolor2 ?? '#000000'; ?>);
                           border-color: <?= $countDownData->inner_border_color ?? '#000000'; ?>;
                           color: <?= $countDownData->time_text_color ?? '#ffffff'; ?>;
                           border-radius: <?= $countDownData->inner_border_radius ?? '5'; ?>px;">
                                                        00
                                                    </h3>
                                                    <?php if ($i < 3): ?>
                                                        <span class="text_color" style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">:</span>
                                                    <?php endif; ?>
                                                    <p class="<?= $keys[$i]; ?> text_color"
                                                        style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">
                                                        <?= $countDownData->{$keys[$i]} ?? $labels[$keys[$i]]; ?>
                                                    </p>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>


                                    <div class="countdown_lauout_main layout3 outer_radius outer_border_color outer_bg_color"
                                        style="display: <?= ($countDownData->select_layout == 'layout3') ? 'block' : 'none'; ?>;
           border-color: <?= $countDownData->outer_border_color ?? '#000000'; ?>;
           border-radius: <?= $countDownData->outer_border_radius ?? '5'; ?>px;
           background: linear-gradient(to right, <?= $countDownData->outer_bgcolor1 ?? '#ffffff'; ?>, <?= $countDownData->outer_bgcolor2 ?? '#ffffff'; ?>);">

                                        <div class="countdown_lauout_1 countdown_lauout_3">
                                            <div class="countdown_heading">
                                                <h4 class="title_text sale_end_text text_color"
                                                    style="color: <?= $countDownData->text_color ?? '#000000'; ?>">
                                                    <?= $countDownData->title_text ?? 'Big Sale'; ?>
                                                </h4>
                                            </div>

                                            <div class="countdown_lauout_inner3">
                                                <?php for ($i = 0; $i < 4; $i++): ?>
                                                    <div class="countdown_lauout_timing">
                                                        <h3 class="inner_radius inner_border_color time_text_color inner_bg_color"
                                                            style="background: linear-gradient(to right, <?= $countDownData->inner_bgcolor1 ?? '#000000'; ?>, <?= $countDownData->inner_bgcolor2 ?? '#000000'; ?>);
                               border-color: <?= $countDownData->inner_border_color ?? '#000000'; ?>;
                               color: <?= $countDownData->time_text_color ?? '#ffffff'; ?>;
                               border-radius: <?= $countDownData->inner_border_radius ?? '5'; ?>px;">
                                                            00
                                                        </h3>
                                                        <?php if ($i < 3): ?>
                                                            <span class="text_color" style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">:</span>
                                                        <?php endif; ?>
                                                        <p class="<?= $keys[$i]; ?> text_color"
                                                            style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">
                                                            <?= $countDownData->{$keys[$i]} ?? $labels[$keys[$i]]; ?>
                                                        </p>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="countdown_lauout_main layout4 outer_radius outer_border_color outer_bg_color"
                                        style="display: <?= ($countDownData->select_layout == 'layout4') ? 'block' : 'none'; ?>;
           border-color: <?= $countDownData->outer_border_color ?? '#000000'; ?>;
           border-radius: <?= $countDownData->outer_border_radius ?? '5'; ?>px;
           background: linear-gradient(to right, <?= $countDownData->outer_bgcolor1 ?? '#ffffff'; ?>, <?= $countDownData->outer_bgcolor2 ?? '#ffffff'; ?>);">

                                        <div class="countdown_lauout_4">
                                            <div class="countdown_heading">
                                                <h4 class="title_text sale_end_text text_color"
                                                    style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">
                                                    <?= $countDownData->title_text ?? 'Big Sale'; ?>
                                                </h4>
                                            </div>

                                            <div class="countdown_lauout_inner3">
                                                <?php
                                                $labels = [
                                                    ['class' => 'day_text', 'text' => $countDownData->day_text ?? 'Day'],
                                                    ['class' => 'hour_text', 'text' => $countDownData->hour_text ?? 'Hour'],
                                                    ['class' => 'mint_text', 'text' => $countDownData->mint_text ?? 'Min'],
                                                    ['class' => 'second_text', 'text' => $countDownData->second_text ?? 'Sec'],
                                                ];
                                                foreach ($labels as $index => $label):
                                                ?>
                                                    <div class="countdown_lauout_timing">
                                                        <div class="countdown_lauout_dbl">
                                                            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color"
                                                                style="background: linear-gradient(to right, <?= $countDownData->inner_bgcolor1 ?? '#000000'; ?>, <?= $countDownData->inner_bgcolor2 ?? '#000000'; ?>);
                                   border-radius: <?= $countDownData->inner_border_radius ?? '5'; ?>px;
                                   border-color: <?= $countDownData->inner_border_color ?? '#000000'; ?>;
                                   color: <?= $countDownData->time_text_color ?? '#ffffff'; ?>;">
                                                                0
                                                            </h3>
                                                            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color"
                                                                style="background: linear-gradient(to right, <?= $countDownData->inner_bgcolor1 ?? '#000000'; ?>, <?= $countDownData->inner_bgcolor2 ?? '#000000'; ?>);
                                   border-radius: <?= $countDownData->inner_border_radius ?? '5'; ?>px;
                                   border-color: <?= $countDownData->inner_border_color ?? '#000000'; ?>;
                                   color: <?= $countDownData->time_text_color ?? '#ffffff'; ?>;">
                                                                0
                                                            </h3>
                                                        </div>
                                                        <?php if ($index < 3): ?>
                                                            <span class="text_color" style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">:</span>
                                                        <?php endif; ?>
                                                        <p class="<?= $label['class']; ?> text_color" style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">
                                                            <?= $label['text']; ?>
                                                        </p>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sd-sale-banner outer_radius outer_border_color outer_bg_color"
                                        style="border: 1px solid <?= $countDownData->outer_border_color ?? '#000000'; ?>;
           border-radius: <?= $countDownData->outer_border_radius ?? '5'; ?>px;
           background: linear-gradient(to right, <?= $countDownData->outer_bgcolor1 ?? '#ffffff'; ?>, <?= $countDownData->outer_bgcolor2 ?? '#ffffff'; ?>);">

                                        <p class="text_color discount_text" style="color: <?= $countDownData->text_color ?? '#000000'; ?>;">
                                            <?= $countDownData->discount_text ?? 'Use {discount_code} coupon code and get {discount_off}% off on checkout'; ?>
                                        </p>
                                    </div>

                                    <div class="sd_membership_message" style="margin-top:15px;">
                                        <div class="sd_tooltip_note">
                                            <b>Important note</b>
                                        </div>
                                        <span style="color:#fff; padding-bottom:10px;">
                                            <i>
                                                <ul>
                                                    <li>The text used inside '{ }' represents a variable (for example, {discount_code}).</li>
                                                    <li>Do not remove these variables as they represent their corresponding values.</li>
                                                    <li>Below are their corresponding values:</li>
                                                    <li>Discount code - {discount_code}</li>
                                                    <li>Percentage discount off - {discount_off}</li>
                                                </ul>
                                            </i>
                                        </span>
                                    </div>


                                </div>
                                <div class="Sd_saveButtonCountdownWidgets">

                                    <?php //if (!empty($plansData['charge_id']) && $plansData['plan_status'] === 'active' && $plansData['current_plan'] !== 'free'): 
                                    ?>
                                    <button id="resetSaleCountDown" class="Polaris-Button Polaris-Button--primary sd_SaleCountDownValue" type="button" btn-type="Reset">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Reset</span>
                                        </span>
                                    </button>
                                    <button id="saveSaleCountDown" class="Polaris-Button Polaris-Button--primary sd_SaleCountDownValue" type="button" btn-type="Save">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Save</span>
                                        </span>
                                    </button>
                                    <? php // else: 
                                    ?>
                                    <!-- <a href="#" class="Polaris-Button Polaris-Button--primary sd_save_disabled_btn">
                                            <span class="Polaris-Button__Content sd_svg_span">
                                                <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z" fill="#ffffff"></path>
                                                </svg>
                                                <span class="Polaris-Button__Text">Reset</span>
                                            </span>
                                        </a>
                                        <a href="#" class="Polaris-Button Polaris-Button--primary sd_save_disabled_btn">
                                            <span class="Polaris-Button__Content sd_svg_span">
                                                <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z" fill="#ffffff"></path>
                                                </svg>
                                                <span class="Polaris-Button__Text">Save</span>
                                            </span>
                                        </a> -->
                                    <!-- <div class="Polaris-FormLayout__ItemSave">
                                            <p class="upgrade-plan-msg">
                                                <a class="sd_handle_navigation_redirect sd_clickme" value="/upgrade-plans">Upgrade your plan</a> to access this feature
                                            </p>
                                        </div> -->
                                    <?php //endif; 
                                    ?>

                                </div>

                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script></script>
<?php include('../footer.php'); ?>