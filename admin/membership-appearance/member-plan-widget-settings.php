<?php
include("../header.php");
include("../navigation.php");
$stmt = $db->query("SELECT * FROM member_plans_widgets WHERE store = '$store' LIMIT 1");
$plan_widget_settings = $stmt->fetch(PDO::FETCH_OBJ);
if ($plan_widget_settings) {
    $widget_settings = $plan_widget_settings;
} else {
    $widget_settings = [
        'heading_text' => 'Membership Plans',
        'heading_text_alignment' => 'center',
        'background_color1' => '#fffafa0c',
        'background_color2' => '#ffffff',
        'text_color' => '#000000',
        'heading_text_color' => '#000000',
        'most_popular_text' => 'Most Popular',
        'most_popular_text_color' => '#000000',
        'most_popular_background1' => '#68ebd166',
        'most_popular_background2' => '#95feb940',
        'widget_outline_color' => '#4DFF9E',
        'month_text' => 'month',
        'week_text' => 'week',
        'day_text' => 'day',
        'year_text' => 'year',
        'offer_text' => 'Avail Exclusive Offers!',
        'offer_background1' => '#68ebd166',
        'offer_background2' => '#95feb940',
        'perks_heading_text' => 'What is included in {{plan_name}}',
        'tick_icon_color' => '#4DFF9E',
        'button_background_color' => '#000000',
        'button_text_color' => '#ffffff',
        'button_text' => 'Subscribe',
        'free_shipping_perk_description_all_orders' => 'Get Free Shipping on all orders',
        'free_shipping_perk_description_min_amt' => 'Get free shipping on spending minimum amount of {{minimum_purchase_amount}}',
        'free_shipping_perk_description_min_qty' => 'Get free shipping on spending minimum {{minimum_purchase_quantity}} quantity of items.',
        'product_collection_discount_perk_description' => 'Get {{discounted_product_collection_percentageoff}}% off on {{discounted_product_title}} {{products}}/{{collection}}',
        'all_products_discount_perk_description' => 'Get {{discounted_product_collection_percentageoff}}% off on all Products',
        'free_gift_perk_description' => 'Get free {{Free_gift_uponsignup_productName}} product upon sign up',
    ];
    $widget_settings = (object) ($widget_settings);
}
?>

<style>
    /* input fields design */
    .plans_tiles_settings .sd_field_div {
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

    .sd_field_div .sd_input_outer_div select {
        background: transparent;
    }

    .plans_tiles_settings .sd_field_label.MuiInputLabel-shrink {
        line-height: 1.5;
        font-size: 1.5rem;
        font-family: "Public Sans", sans-serif;
        font-weight: 600;
        color: rgb(99, 115, 129);
    }

    .plans_tiles_settings .sd_field_lab {
        padding: 0px;
        line-height: 1.57143;
        font-weight: 700;
        color: rgb(255 255 255);
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

    .plans_tiles_settings .sd_field_lab::after {
        content: "";
        position: absolute;
        background: #141e2d;
        width: 100%;
        height: 22px;
        left: 0;
        top: 0;
        z-index: -1;
    }

    .plans_tiles_settings .sd_input_class:focus-visible {
        outline: none;
    }

    .plans_tiles_settings .sd_input_outer_div {
        line-height: 1.4375em;
        font-size: 1rem;
        font-family: "Public Sans", sans-serif;
        font-weight: 400;
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

    .plans_tiles_settings .sd_input_class {
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
        font-family: "Public Sans", sans-serif;
        font-weight: 400;
        padding: 12.5px 14px;
    }

    .plans_tiles_settings .sd_fieldlist {
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
        border-color: rgba(145, 158, 171, 0.2);
        transition: border-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        border-color: #fff;
    }

    .plans_tiles_settings .sd_legend {
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

    .plans_tiles_settings .sd_legend>span {

        display: inline-block;
        opacity: 0;
        visibility: visible;
    }


    /* product widget css start */
    .product_widget_preview div.sd_plan_option {
        border-bottom: 1px solid #DBDBDB;
        padding-bottom: 10px;
        padding-top: 11px;
    }

    .product_widget_preview .purchase-label-price {
        /* color: #000; */
        font-size: 14px;
        font-weight: 500;
        letter-spacing: -.28px;
        display: flex;
        justify-content: space-between;
    }

    .product_widget_preview label {
        /* color: #666; */
        font-size: 14px;
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
        font-size: 14px;
        font-weight: 500;
        letter-spacing: -.28px;
        display: flex;
        justify-content: space-between;
    }

    .product_widget_preview input[type=radio]:checked {
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
        padding-left: 10px;
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

    .plans_tiles_settings .Polaris-FormLayout__Item {
        margin-left: 0;
        max-width: 100%;
    }
</style>
<div class="sd-prefix-main">
    <div class="Polaris-Page-Header__Row">

        <div class="Polaris-Page-Header__TitleWrapper">
            <div>
                <div class="Polaris-Header-Title__TitleAndSubtitleWrapper plan_widget_top">
                    <h1 class="Polaris-Heading">Plans widget</h1>
                    <a href="https://<?= $store; ?>/admin/themes/current/editor?template=index&addAppBlockId=08cff738-e93f-46b7-83d1-dbbcea5a1fb3/planTiles_block&target=section"
                        target="_blank"><button class="sd_app_enable_btn Polaris-Button--primary">Add plan widget</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="Polaris-Page__Content plans_tiles_settings">
        <div class="Polaris-Layout">
            <div class="Polaris-Layout__Section Polaris-Layout__Section--secondary">
                <form id="sd_plan_widget_settings" method="post">
                    <div class="page-container1">
                        <div class="builder-container">
                            <!-- Most popular text and offer texyt -->
                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="heading_text" id="heading_text">Heading text</label>
                                        <div class="sd_input_outer_div">
                                            <input id="heading_text" name="heading_text" autocomplete="off"
                                                class="sd_input_class membershipAllTextBox" type="text"
                                                aria-labelledby="sd_headingTextLabel" aria-invalid="false"
                                                value="<?php echo isset($widget_settings->heading_text) ? htmlspecialchars($widget_settings->heading_text) : ''; ?>"
                                                data-id="sd_headingText" type-attr="text" maxlength="25">
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Heading text</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="heading_text_alignment"
                                            id="heading_text_alignment">Heading text alignment</label>
                                        <div class="sd_input_outer_div">
                                            <select id="heading_text_alignment" name="heading_text_alignment"
                                                class="sd_heading_text_alignment membershipAllTextBox"
                                                aria-invalid="false" type-attr="text-align" data-id="sd_headingText">
                                                <option value="center" <?php echo (isset($widget_settings->heading_text_alignment) && $widget_settings->heading_text_alignment == 'center') ? 'selected' : ''; ?>>Center</option>
                                                <option value="left" <?php echo (isset($widget_settings->heading_text_alignment) && $widget_settings->heading_text_alignment == 'left') ? 'selected' : ''; ?>>Left</option>
                                                <option value="right" <?php echo (isset($widget_settings->heading_text_alignment) && $widget_settings->heading_text_alignment == 'right') ? 'selected' : ''; ?>>Right</option>
                                            </select>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Heading text Alignment</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="heading_tag_alignment"
                                            id="heading_tag_alignment">Heading tag</label>
                                        <div class="sd_input_outer_div">
                                            <select id="heading_tag" name="heading_tag"
                                                class="sd_heading_tag_alignment membershipAllTextBox"
                                                aria-invalid="false" type-attr="headingTag-change"
                                                data-id="sd_headingTag">
                                                <?php
                                                $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                                                foreach ($tags as $tag) {
                                                    $selected = (isset($widget_settings->heading_tag) && $widget_settings->heading_tag == $tag) ? 'selected' : '';
                                                    echo "<option value=\"$tag\" $selected>$tag</option>";
                                                }
                                                ?>
                                            </select>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Heading text Alignment</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="heading_text" id="heading_text">Button text</label>
                                        <div class="sd_input_outer_div">
                                            <input id="buttonText" name="button_text" autocomplete="off"
                                                class="sd_input_class membershipAllTextBox" type="text"
                                                aria-labelledby="buttonTextLabel" aria-invalid="false"
                                                value="<?php echo isset($widget_settings->button_text) ? htmlspecialchars($widget_settings->button_text) : ''; ?>"
                                                data-id="sd_buttonTextColor" type-attr="text" maxlength="25">
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Button Text</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- day text and year text -->
                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="dayText" id="dayText">Day text</label>
                                        <div class="sd_input_outer_div">
                                            <input id="dayText" name="day_text" autocomplete="off"
                                                class="sd_input_class membershipAllTextBox" type="text"
                                                aria-labelledby="dayTextLabel" aria-invalid="false"
                                                value="<?php echo isset($widget_settings->day_text) ? htmlspecialchars($widget_settings->day_text) : ''; ?>"
                                                data-id="sd_timePeriod" type-attr="text" maxlength="25">
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Day Text</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="yearText" id="yearText">Year text</label>
                                        <div class="sd_input_outer_div">
                                            <input id="yearText" name="year_text" autocomplete="off"
                                                class="sd_input_class membershipAllTextBox" type="text"
                                                aria-labelledby="yearTextLabel" aria-invalid="false"
                                                value="<?php echo isset($widget_settings->year_text) ? htmlspecialchars($widget_settings->year_text) : ''; ?>"
                                                data-id="sd_timePeriod" type-attr="text" maxlength="25">
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Year Text</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- month text and week text -->
                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="monthText" id="monthText">Month text</label>
                                        <div class="sd_input_outer_div">
                                            <input id="monthText" name="month_text" autocomplete="off"
                                                class="sd_input_class membershipAllTextBox" type="text"
                                                aria-labelledby="monthTextLabel" aria-invalid="false"
                                                value="<?php echo isset($widget_settings->month_text) ? htmlspecialchars($widget_settings->month_text) : ''; ?>"
                                                data-id="sd_timePeriod" type-attr="text" maxlength="25">
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Month Text</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                            data-shrink="true" for="weekText" id="weekText">Week text</label>
                                        <div class="sd_input_outer_div">
                                            <input id="weekText" name="week_text" autocomplete="off"
                                                class="sd_input_class membershipAllTextBox" type="text"
                                                aria-labelledby="weekTextLabel" aria-invalid="false"
                                                value="<?php echo isset($widget_settings->week_text) ? htmlspecialchars($widget_settings->week_text) : ''; ?>"
                                                data-id="sd_timePeriod" type-attr="text" maxlength="25">
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Week Text</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- most popular text -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="mostPopularText" id="mostPopularText">Most popular text</label>
                                    <div class="sd_input_outer_div">
                                        <input id="mostPopularText" name="most_popular_text" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="pmostPopularTextLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->most_popular_text) ? htmlspecialchars($widget_settings->most_popular_text) : ''; ?>"
                                            data-id="sd_mostPopularText" type-attr="text" maxlength="25">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Most Popular Text</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Offer Text Field -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="offerText" id="offerText">Offer text</label>
                                    <div class="sd_input_outer_div">
                                        <input id="offerText" name="offer_text" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="offerTextLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->offer_text) ? htmlspecialchars($widget_settings->offer_text) : ''; ?>"
                                            data-id="sd_offerText" type-attr="text" maxlength="60">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Offer Text</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Perks Heading Text Field -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="perksHeadingText" id="perksHeadingText">Perks heading text</label>
                                    <div class="sd_input_outer_div">
                                        <input id="perksHeadingText" name="perks_heading_text" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="planHeadingLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->perks_heading_text) ? htmlspecialchars($widget_settings->perks_heading_text) : ''; ?>"
                                            data-id="sd_perksHeadingText sd_headingtextColor" type-attr="text" maxlength="80">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Perks Heading Text</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Shipping Description All Orders Field -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="freeShippingDescriptionAllOrders" id="freeShippingDescriptionAllOrders">
                                        Free shipping description all orders
                                    </label>
                                    <div class="sd_input_outer_div">
                                        <input id="freeShippingDescriptionAllOrders" name="free_shipping_perk_description_all_orders" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="freeShippingDescriptionLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->free_shipping_perk_description_all_orders) ? htmlspecialchars($widget_settings->free_shipping_perk_description_all_orders) : ''; ?>"
                                            data-id="sd_freeShippingDescriptionAllOrders" type-attr="text" maxlength="100">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Free Shipping Description All Orders</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Shipping Description Minimum Amount Field -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="freeShippingDescriptionMiniAmt" id="freeShippingDescriptionMiniAmt">
                                        Free shipping description minimum amount
                                    </label>
                                    <div class="sd_input_outer_div">
                                        <input id="freeShippingDescriptionMiniAmt" name="free_shipping_perk_description_min_amt" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="freeShippingDescriptionLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->free_shipping_perk_description_min_amt) ? htmlspecialchars($widget_settings->free_shipping_perk_description_min_amt) : ''; ?>"
                                            data-id="sd_freeShippingDescriptionAllOrders" type-attr="text" maxlength="100">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Free Shipping Description Minimum Amount</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>


                            <!-- Free Shipping Description Minimum Quantity -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="freeShippingDescriptionMiniQty" id="freeShippingDescriptionMiniQty">
                                        Free shipping description minimum quantity
                                    </label>
                                    <div class="sd_input_outer_div">
                                        <input id="freeShippingDescriptionMiniQty" autocomplete="off"
                                            name="free_shipping_perk_description_min_qty"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="freeShippingDescriptionMiniQtyLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->free_shipping_perk_description_min_qty) ? htmlspecialchars($widget_settings->free_shipping_perk_description_min_qty) : ''; ?>"
                                            data-id="sd_freeShippingDescriptionAllOrders" type-attr="text" maxlength="100">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Free Shipping Description Minimum Quantity</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Product/Collection Discount Perk Description -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="productCollectionDescription" id="productCollectionDescription">
                                        Product/Collection discount perk description
                                    </label>
                                    <div class="sd_input_outer_div">
                                        <input id="productCollectionDescription" name="product_collection_discount_perk_description" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="productCollectionDescriptionLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->product_collection_discount_perk_description) ? htmlspecialchars($widget_settings->product_collection_discount_perk_description) : ''; ?>"
                                            data-id="sd_productCollectionDescription" type-attr="text" maxlength="100">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Product/Collection Discount Perk Description</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- All Products Discount Perk Description -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab"
                                        data-shrink="true" for="allProductsDiscountPerkDescription" id="allProductsDiscountPerkDescription">
                                        All products discount perk description
                                    </label>
                                    <div class="sd_input_outer_div">
                                        <input id="allProductsDiscountPerkDescription" name="all_products_discount_perk_description" autocomplete="off"
                                            class="sd_input_class membershipAllTextBox" type="text"
                                            aria-labelledby="allProductsDiscountPerkDescriptionLabel" aria-invalid="false"
                                            value="<?php echo isset($widget_settings->all_products_discount_perk_description) ? htmlspecialchars($widget_settings->all_products_discount_perk_description) : ''; ?>"
                                            data-id="sd_productCollectionDescription" type-attr="text" maxlength="100">
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>All Products Discount Perk Description</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>


                            <!-- Heading color and text color -->
                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Heading color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->heading_text_color ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="headingtextColor" name="heading_text_color" data-id="sd_headingtextColor" type-attr="color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->heading_text_color ?? '' ?>" data-coloris="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Heading color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Text color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->text_color ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="textColor" name="text_color" data-id="sd_textColor" type-attr="color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->text_color ?? '' ?>" data-coloris="">
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
                            </div>

                            <!-- Most popular text color and widget outline color -->
                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Most popular text color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->most_popular_text_color ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="mostPopularTextColor" name="most_popular_text_color" data-id="sd_mostPopularTextColor" type-attr="color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->most_popular_text_color ?? '' ?>" data-coloris="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Most Popular Text Color</span></legend>
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
                                                            <div class="clr-field" style="color: <?= $widget_settings->widget_outline_color ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="widgetOutlineColor" name="widget_outline_color" data-id="sd_widgetOutlineColor" type-attr="border-color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->widget_outline_color ?? '' ?>" data-coloris="">
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

                            <!-- Button background color and button text color -->
                            <div class="Polaris-FormLayout__Items color">
                                <div class="Polaris-FormLayout__Item sd-field">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Button background color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->button_background_color ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input aria-invalid="false" name="button_background_color" data-id="sd_buttonBgColor" type-attr="bg-color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->button_background_color ?? '' ?>" data-coloris="" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Button Background Color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Item">
                                    <div class="MuiTextField-root sd_field_div">
                                        <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Button text color</label>
                                        <div class="sd_input_outer_div">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->button_text_color ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="buttonTextColor" name="button_text_color" data-id="sd_buttonTextColor" type-attr="color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->button_text_color ?? '' ?>" data-coloris="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                <legend class="sd_legend"><span>Button Text Color</span></legend>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Background Colors -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="background_color1">Card background color</label>
                                    <div class="sd_input_outer_div">
                                        <div class="Polaris-FormLayout__Items">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->background_color1 ?? '' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="planBgColor1" data-id="sd_planBgColor" name="background_color1" type-attr="gradient-color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->background_color1 ?? '' ?>" data-id="sd_planBgColor1" data-coloris="" gradient-code="cardBgColor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?= $widget_settings->background_color2 ?? '#ffffff' ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="bgColor2" data-id="sd_planBgColor" name="background_color2" type-attr="gradient-color" class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox" value="<?= $widget_settings->background_color2 ?? '' ?>" data-coloris="" data-id="sd_bgColor2" gradient-code="cardBgColor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span> Card Background Color </span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>


                            <!-- Offer Background -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="background_color1">Offer heading background</label>
                                    <div class="sd_input_outer_div">
                                        <div class="Polaris-FormLayout__Items">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?php echo htmlspecialchars($widget_settings->most_popular_background1 ?? ''); ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="offerBgColor1" name="offer_background1" data-id="sd_offerBgColor" type-attr="gradient-color"
                                                                    class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                    value="<?php echo htmlspecialchars($widget_settings->offer_background1 ?? ''); ?>" data-coloris="" gradient-code="offerBgColor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?php echo htmlspecialchars($widget_settings->background_color2 ?? '#ffffff'); ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="offerBgColor2" name="offer_background2" data-id="sd_offerBgColor" type-attr="gradient-color"
                                                                    class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                    value="<?php echo htmlspecialchars($widget_settings->offer_background2 ?? ''); ?>" data-coloris="" gradient-code="offerBgColor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Offer Heading Background</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Most Popular Background -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="background_color1">Most popular background</label>
                                    <div class="sd_input_outer_div">
                                        <div class="Polaris-FormLayout__Items">
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?php echo htmlspecialchars($widget_settings->most_popular_background1 ?? ''); ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="mostPopBgColor1" name="most_popular_background1" data-id="sd_mostPopBgColor" type-attr="gradient-color"
                                                                    class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                    value="<?php echo htmlspecialchars($widget_settings->most_popular_background1 ?? ''); ?>" data-coloris="" gradient-code="mostPopBgColor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="Polaris-FormLayout__Item">
                                                <div class="Polaris-Connected">
                                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                        <div class="Polaris-TextField example square">
                                                            <div class="clr-field" style="color: <?php echo htmlspecialchars($widget_settings->background_color2 ?? '#ffffff'); ?>">
                                                                <button type="button" aria-labelledby="clr-open-label"></button>
                                                                <input type="text" id="mostPopBgColor2" name="most_popular_background2" data-id="sd_mostPopBgColor" type-attr="gradient-color"
                                                                    class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                    value="<?php echo htmlspecialchars($widget_settings->most_popular_background2 ?? ''); ?>" data-coloris="" gradient-code="mostPopBgColor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Most Popular Background</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <!-- Tick Icon Color -->
                            <div class="Polaris-FormLayout__Item sd-field">
                                <div class="MuiTextField-root sd_field_div">
                                    <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Tick icon color</label>
                                    <div class="sd_input_outer_div">
                                        <div class="Polaris-FormLayout__Item">
                                            <div class="Polaris-Connected">
                                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                    <div class="Polaris-TextField example square">
                                                        <div class="clr-field" style="color: <?php echo htmlspecialchars($widget_settings->tick_icon_color ?? ''); ?>">
                                                            <button type="button" aria-labelledby="clr-open-label"></button>
                                                            <input type="text" id="tickIconColor" name="tick_icon_color" data-id="sd_tickIconColor" type-attr="tick-color"
                                                                class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                value="<?php echo htmlspecialchars($widget_settings->tick_icon_color ?? ''); ?>" data-coloris="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset aria-hidden="true" class="sd_fieldlist">
                                            <legend class="sd_legend"><span>Tick Icon Color</span></legend>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <!-- Member Plan preview widget start here -->
                        <div class="sd_plan_widget_right_column">
                            <h1 class="sd_headingText Polaris-Heading sd_headingtextColor sd_headingTag"
                                style="color: <?php echo htmlspecialchars($widget_settings->heading_text_color ?? ''); ?>; 
                                    text-align: <?php echo htmlspecialchars($widget_settings->heading_text_alignment ?? 'center'); ?>">
                                                        <?php echo htmlspecialchars($widget_settings->heading_text ?? ''); ?>
                                                    </h1>
                                                    <div class="preview-container" id="previewContainer">
                                                        <div class="membership-plan-card sd_widgetOutlineColor sd_planBgColor" id="previewCard"
                                                            style="background: linear-gradient(to right, <?php echo htmlspecialchars($widget_settings->background_color1 ?? ''); ?>, <?php echo htmlspecialchars($widget_settings->background_color2 ?? ''); ?>); 
                                        border: 2px solid <?php echo htmlspecialchars($widget_settings->widget_outline_color ?? '#68EBD1'); ?>">
                                                            <div class="sd_mostPopularTextColor popular-tag sd_mostPopBgColor" id="popularTag"
                                                                style="background: linear-gradient(to right, <?php echo htmlspecialchars($widget_settings->most_popular_background1 ?? ''); ?>, <?php echo htmlspecialchars($widget_settings->most_popular_background2 ?? ''); ?>); 
                                            color: <?php echo htmlspecialchars($widget_settings->most_popular_text_color ?? ''); ?>">
                                                                <h3 class="sd_mostPopularText">
                                                                    <?php echo htmlspecialchars($widget_settings->most_popular_text ?? ''); ?>
                                                                </h3>
                                                            </div>
                                                            <div class="card-inner-details">
                                                                <h2 style="color: <?php echo htmlspecialchars($widget_settings->heading_text_color ?? ''); ?>" class="sd_headingtextColor">BasicPlan</h2>
                                                                <h4 style="color: <?php echo htmlspecialchars($widget_settings->heading_text_color ?? ''); ?>" class="sd_headingtextColor">FOR INDIVIDUALS & SMALL BUSINESSES</h4>
                                                                <p class="sd_textColor" style="color: <?php echo htmlspecialchars($widget_settings->text_color ?? ''); ?>">
                                                                    Everything you need to create your store, ship products, and process payments
                                                                </p>
                                                                <div class="total-plan-price">
                                                                    <span class="text-6xl">₹1,994</span>
                                                                    <sub class="text-base"><span>INR/</span>
                                                                        <span class="sd_timePeriod"><?php echo htmlspecialchars($widget_settings->month_text ?? ''); ?></span>
                                                                    </sub>
                                                                </div>
                                                            </div>
                                                            <div class="best-plan-offer sd_offerBgColor sd_widgetOutlineColor"
                                                                style="background: linear-gradient(to right, <?php echo htmlspecialchars($widget_settings->offer_background1 ?? ''); ?>, <?php echo htmlspecialchars($widget_settings->offer_background2 ?? ''); ?>); 
                                            border-bottom: 2px solid <?php echo htmlspecialchars($widget_settings->widget_outline_color ?? ''); ?>; 
                                            border-top: 2px solid <?php echo htmlspecialchars($widget_settings->widget_outline_color ?? '#68EBD1'); ?>">
                                                                <h5 class="sd_offerText sd_headingtextColor" style="color: <?php echo htmlspecialchars($widget_settings->heading_text_color ?? ''); ?>;">
                                                                    <?php echo htmlspecialchars($widget_settings->offer_text ?? ''); ?>
                                        </h5>
                                    </div>
                                    <div class="plan-include-details">
                                        <h4 class="sd_perksHeadingText sd_headingtextColor" style="color: <?php echo htmlspecialchars($widget_settings->heading_text_color ?? ''); ?>">
                                            <?php echo htmlspecialchars($widget_settings->perks_heading_text ?? ''); ?>
                                        </h4>
                                        <ul class="perks-list-offers sd_textColor" style="color: <?php echo htmlspecialchars($widget_settings->text_color ?? ''); ?>">
                                            <li id="perkList1">
                                                <svg class="sd_tickIconColor" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                                    <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z"
                                                        fill="<?php echo htmlspecialchars($widget_settings->tick_icon_color ?? ''); ?>"></path>
                                                </svg>
                                                <p class="sd_freeShippingDescriptionAllOrders">
                                                    <?php echo htmlspecialchars($widget_settings->free_shipping_perk_description_all_orders ?? ''); ?>
                                                </p>
                                            </li>
                                            <li id="perkList2">
                                                <svg class="sd_tickIconColor" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                                    <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z"
                                                        fill="<?php echo htmlspecialchars($widget_settings->tick_icon_color ?? ''); ?>"></path>
                                                </svg>
                                                <p class="sd_productCollectionDescription">
                                                    <?php echo htmlspecialchars($widget_settings->all_products_discount_perk_description ?? ''); ?>
                                                </p>
                                            </li>
                                            <li id="perkList3">
                                                <svg class="sd_tickIconColor" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                                    <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z"
                                                        fill="<?php echo htmlspecialchars($widget_settings->tick_icon_color ?? ''); ?>"></path>
                                                </svg>
                                                <p class="sd_freeGiftDescription">
                                                    <?php echo htmlspecialchars($widget_settings->free_gift_perk_description ?? ''); ?>
                                                </p>
                                            </li>
                                        </ul>
                                        <div class="try-plan-btn sd_buttonBgColor" style="background-color:<?php echo htmlspecialchars($widget_settings->button_background_color ?? ''); ?>;">
                                            <a href="#" style="color:<?php echo htmlspecialchars($widget_settings->button_text_color ?? ''); ?>" class="sd_buttonTextColor">
                                                <?php echo htmlspecialchars($widget_settings->button_text ?? ''); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="SD_saveButtonWidgets">
                                <button id="sd_resetbtnwidgets" class="Polaris-Button Polaris-Button--primary sd_planWidgetsValue" type="button" btn-type="Reset">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Reset</span>
                                    </span>
                                </button>
                                <button id="saveButtonWidgets" class="Polaris-Button Polaris-Button--primary sd_planWidgetsValue" type="button" btn-type="Save">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Save</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <!-- Member Plan preview widget ends here -->
                </form>
            </div>
        </div>
    </div>

</div>
<?php
include("../footer.php");
?>