<?php
include("../header.php");
include("../navigation.php");
$drawerData_qry = $db->query("SELECT * FROM drawer_settings WHERE store = '$store' LIMIT 1");
$drawer_data = $drawerData_qry->fetch(PDO::FETCH_OBJ);
if(!$drawer_data) {
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
     $drawer_data = (object) $default_values;
}
// $drawer_data = $drawer_data[0];
// print_r($drawer_data);die;
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
            font-weight: 600;
            color: rgb(99, 115, 129);
        }
        .product_widget_settings .sd_field_lab {
            padding: 0px;
            padding-right: 7px;
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
            pointer-events: auto;
            user-select: none;
            font-size: 15px;
            z-index: 2;
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
        .product_widget_settings .sd_input_class:focus-visible{
        outline:none;
        }
        .product_widget_settings .sd_input_outer_div{
            line-height: 1.4375em;
            font-size: 1rem;
            font-weight: 700;
            color: rgb(33, 43, 54);
            box-sizing: border-box;
            cursor: text;
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
            background: none;
            margin: 0px;
            -webkit-tap-highlight-color: transparent;
            display: block;
            min-width: 0px;
            width: 100%;
            animation-name: mui-auto-fill-cancel;
            animation-duration: 10ms;
            line-height: 1.57143;
            font-size:11px;
            font-family: "Public Sans", sans-serif;
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
        .product_widget_settings .sd_legend> span {
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
            row-gap:2rem;
        }
        legend.membership_plan_name {
            border-radius: 5px;
            padding: 3px 7px;
        }

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
            border:none;
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
            max-width:100%;
        }
        .sd_discount_card_main {
            border: 2px solid #7ec7a2;
            border-radius: 8px;
            display: flex;
            overflow: hidden;
            justify-content: space-between;
        }
        .sd_discount_card_content {
            padding: 10px;
        }
        .sd_discount_coupon_code {
            display: flex;
            position: relative;
            font-weight: bold;
            background: #bee7d2;
            height: 100%;
            white-space: nowrap;
            align-items: center;
            justify-content: center;
        }
        .sd_discount_card_main h3 {
            font-weight: 600;
            font-size: 16px;
            color: #fff;
            display: flex;
            align-items: center;
        }
        .scissors {
            position: absolute;
            top: 7px;
            color: #3c9a5d;
            left: -7px;
            transform: rotate(90deg);
        }
        .sd_discount_card_main p {
            font-size: 13px;
            color: #fff;
            position: relative;
            top: -3px;
        }

        .sd_discount_coupon_code_apply button {
            padding: 13px 10px !important;
            cursor: pointer;
        }
        .sd_discount_coupon_code button {
            background: transparent !important;
            border: none !important;
            transform: rotate(90deg);
        }
        .sd_discount_coupon_code_main {
            width: 60px;
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
                    <h1 class="Polaris-Heading">Discount drawer</h1>
                    <!-- <button id="saveDrawerSetting" class="Polaris-Button Polaris-Button--primary" type="button">
                            <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Text">Save</span>
                            </span>
                        </button> -->
               </div>
          </div>
     </div>
     <div class="Polaris-Page__Content product_widget_settings">
          <div class="Polaris-Layout">
               <div class="Polaris-Layout__Section Polaris-Layout__Section--secondary">
                    <form id="sd_discount_drawer_settings" method="post">
                         <div class="page-container1">
                              <div class="builder-container">
                                   <div class="Polaris-FormLayout">
                                        <div class="Polaris-FormLayout__Items">
                                             <!-- Drawer heading -->
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" for="heading_text">Drawer heading</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 id="heading_text"
                                                                 name="drawer_heading"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?= isset($drawer_data->drawer_heading) ? $drawer_data->drawer_heading : 'Discounts & Offers' ?>"
                                                                 data-id="sd_discount_drawer_heading"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Drawer heading</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>

                                             <!-- Free shipping text -->
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" for="charge_every_text">Free shipping text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 id="charge_every_text"
                                                                 name="free_shipping_text"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?= isset($drawer_data->free_shipping_text) ? $drawer_data->free_shipping_text : 'Use code {free_shipping} to get free shipping.' ?>"
                                                                 data-id="sd_free_shipping_text"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Free shipping text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="Polaris-FormLayout__Items">
                                             <!-- Percent off text -->
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" for="day_text">Percent off text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 id="day_text"
                                                                 name="percent_off_text"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?= isset($drawer_data->percent_off_text) ? $drawer_data->percent_off_text : 'use code {discount_name} to get {discount_value} off.' ?>"
                                                                 data-id="sd_percent_off_discount"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Percent off text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>

                                             <!-- Discount button text -->
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" for="week_text">Discount button text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 id="week_text"
                                                                 name="discount_button_text"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?= isset($drawer_data->discount_button_text) ? $drawer_data->discount_button_text : 'Apply Discounts' ?>"
                                                                 data-id="sd_discount_button"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Discount button text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="Polaris-FormLayout__Items">
                                             <!-- Free shipping minimum quantity text -->
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" for="day_text">Free shipping minimum quantity text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 id="day_text"
                                                                 name="free_shipping_minimum_quantity"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?= isset($drawer_data->free_shipping_minimum_quantity) ? $drawer_data->free_shipping_minimum_quantity : 'Applicable on minimum {minimum_quantity} quantity.' ?>"
                                                                 data-id="sd_free_shipping_minimum_quantity"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Minimum quantity text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>

                                             <!-- Free shipping minimum amount text -->
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" for="week_text">Free shipping minimum amount text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 id="week_text"
                                                                 name="free_shipping_minimum_amount"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?= isset($drawer_data->free_shipping_minimum_amount) ? $drawer_data->free_shipping_minimum_amount : 'Applicable on minimum {minimum_amount} order amount.' ?>"
                                                                 data-id="sd_free_shipping_minimum_amount"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Minimum amount text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="Polaris-FormLayout__Items">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="day_text" id="day_text">Percent off specific product text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 aria-invalid="false"
                                                                 id="day_text"
                                                                 name="percent_off_product_text"
                                                                 type="text"
                                                                 data-id="sd_percent_off_specific_product"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?php echo isset($drawer_data->percent_off_product_text) ? $drawer_data->percent_off_product_text : 'Applicable only on {product_name}'; ?>"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Percent off text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Percent off specific collection text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 aria-invalid="false"
                                                                 id="week_text"
                                                                 name="percent_off_collection_text"
                                                                 type="text"
                                                                 data-id="sd_percent_off_specific_collection"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?php echo isset($drawer_data->percent_off_collection_text) ? $drawer_data->percent_off_collection_text : 'Applicable only on {collection_name} collection products'; ?>"
                                                                 type-attr="text"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Discount button text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="Polaris-FormLayout__Items">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="month_text" id="month_text">Copied message text</label>
                                                       <div class="sd_input_outer_div">
                                                            <input
                                                                 aria-invalid="false"
                                                                 id="month_text"
                                                                 name="copied_message_text"
                                                                 type="text"
                                                                 class="sd_input_class membershipAllTextBox"
                                                                 value="<?php echo isset($drawer_data->copied_message_text) ? $drawer_data->copied_message_text : 'Code {discount_name} copied successfully.'; ?>"
                                                                 type-attr="text"
                                                                 data-id="sd_code_applied_message"
                                                            />
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Copied message text</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Copy button color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->copied_button_color) ? $drawer_data->copied_button_color : '#000000'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="copied_button_color"
                                                                                          name="copied_button_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->copied_button_color) ? $drawer_data->copied_button_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="tick-color"
                                                                                          data-id="sd_copied_icon"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Copy Button Color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="Polaris-FormLayout__Item">
                                             <div class="MuiTextField-root sd_field_div">
                                                  <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="border_radius" id="border_radius">Button border radius</label>
                                                  <div class="sd_input_outer_div">
                                                       <input
                                                            aria-invalid="false"
                                                            id="border_radius"
                                                            name="discount_button_radius"
                                                            type="number"
                                                            min="1"
                                                            class="sd_input_class membershipAllTextBox"
                                                            value="<?php echo isset($drawer_data->discount_button_radius) ? $drawer_data->discount_button_radius : '1'; ?>"
                                                            type-attr="border-radius"
                                                            data-id="sd_discount_button"
                                                       />
                                                       <fieldset aria-hidden="true" class="sd_fieldlist">
                                                            <legend class="sd_legend"><span>Border Radius</span></legend>
                                                       </fieldset>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="Polaris-FormLayout__Items color">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Drawer background color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->drawer_background_color) ? $drawer_data->drawer_background_color : '#ffffff'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="radio_button_color"
                                                                                          name="drawer_background_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->drawer_background_color) ? $drawer_data->drawer_background_color : '#ffffff'; ?>"
                                                                                          data-coloris=""
                                                                                          data-id="sidebar"
                                                                                          type-attr="bg-color"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Drawer background color</span></legend>
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
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->heading_text_color) ? $drawer_data->heading_text_color : '#000000'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="heading_text_color"
                                                                                          name="heading_text_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->heading_text_color) ? $drawer_data->heading_text_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="color"
                                                                                          data-id="sd_discount_drawer_heading"
                                                                                     />
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
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Copied text color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->all_text_color) ? $drawer_data->all_text_color : '#000000'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="text_color"
                                                                                          name="all_text_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->all_text_color) ? $drawer_data->all_text_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="color"
                                                                                          data-id="sd_discount_text"
                                                                                     />
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
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Cross button color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->cross_button_color) ? $drawer_data->cross_button_color : '#000000'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label"></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="widget_outline_color"
                                                                                          name="cross_button_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->cross_button_color) ? $drawer_data->cross_button_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          data-id="close-button"
                                                                                          type-attr="color"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Cross button color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="Polaris-FormLayout__Items color">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Code bar background color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color:<?php echo isset($drawer_data->code_bg_color) ? $drawer_data->code_bg_color : '#63db69'; ?>;">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="text_color"
                                                                                          name="code_bg_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->code_bg_color) ? $drawer_data->code_bg_color : '#63db69'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="bg-color"
                                                                                          data-id="sd_discount_code_bar"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Code bar background color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Code bar text color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color:<?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="widget_outline_color"
                                                                                          name="code_bar_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          data-id="sd_discount_code_bar"
                                                                                          type-attr="color"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Code bar color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="Polaris-FormLayout__Items color">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Inner code bar background</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color:<?php echo isset($drawer_data->code_bar_inner_bg_color) ? $drawer_data->code_bar_inner_bg_color : '#000000'; ?>;">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="text_color"
                                                                                          name="code_bar_inner_bg_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->code_bar_inner_bg_color) ? $drawer_data->code_bar_inner_bg_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="bg-color"
                                                                                          data-id="sd_inner_code_bar"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Code bar background</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Inner code bar color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color:<?php echo isset($drawer_data->code_bar_inner_color) ? $drawer_data->code_bar_inner_color : '#ffffff'; ?>;">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="widget_outline_color"
                                                                                          name="code_bar_inner_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->code_bar_inner_color) ? $drawer_data->code_bar_inner_color : '#ffffff'; ?>"
                                                                                          data-coloris=""
                                                                                          data-id="sd_inner_code_bar"
                                                                                          type-attr="color"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Inner code bar background color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="Polaris-FormLayout__Items color">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Scissor color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color:<?php echo isset($drawer_data->scissor_color) ? $drawer_data->scissor_color : '#ffffff'; ?>;">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="text_color"
                                                                                          name="scissor_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->scissor_color) ? $drawer_data->scissor_color : '#ffffff'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="color"
                                                                                          data-id="scissors"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Scissor color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Code bar border color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color:<?php echo isset($drawer_data->code_bar_border_color) ? $drawer_data->code_bar_border_color : '#000000'; ?>;">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="widget_outline_color"
                                                                                          name="code_bar_border_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->code_bar_border_color) ? $drawer_data->code_bar_border_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          data-id="sd_discount_code_bar"
                                                                                          type-attr="border-color"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Code bar border color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="Polaris-FormLayout__Items color">
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Discount button background</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->button_bg_color) ? $drawer_data->button_bg_color : '#000000'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="text_color"
                                                                                          name="button_bg_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->button_bg_color) ? $drawer_data->button_bg_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          type-attr="bg-color"
                                                                                          data-id="sd_discount_button"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Offer button background</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="Polaris-FormLayout__Item">
                                                  <div class="MuiTextField-root sd_field_div">
                                                       <label class="MuiInputLabel-shrink sd_fields_label sd_field_lab" data-shrink="true" for="heading_text" id="heading_text">Discount button text color</label>
                                                       <div class="sd_input_outer_div">
                                                            <div class="Polaris-FormLayout__Item">
                                                                 <div class="Polaris-Connected">
                                                                      <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                                           <div class="Polaris-TextField example square">
                                                                                <div class="clr-field" style="color: <?php echo isset($drawer_data->button_text_color) ? $drawer_data->button_text_color : '#ffffff'; ?>">
                                                                                     <button type="button" aria-labelledby="clr-open-label" disabled></button>
                                                                                     <input
                                                                                          type="text"
                                                                                          id="widget_outline_color"
                                                                                          name="button_text_color"
                                                                                          class="coloris instance1 input-color-textbox Polaris-TextField__Input membershipAllTextBox"
                                                                                          value="<?php echo isset($drawer_data->button_text_color) ? $drawer_data->button_text_color : '#000000'; ?>"
                                                                                          data-coloris=""
                                                                                          data-id="sd_discount_button"
                                                                                          type-attr="color"
                                                                                     />
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <fieldset aria-hidden="true" class="sd_fieldlist">
                                                                 <legend class="sd_legend"><span>Offer button color</span></legend>
                                                            </fieldset>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <!-- Preview -->
                                    
                                   </div>
                              </div>
                              <div class="sd_plan_widget_right_column">
                                             <h1 class="sd_headingText Polaris-Heading sd_headingtextColor" style="color: #ffffff; text-align: center;">Preview</h1>

                                             <div class="preview-container product_widget_preview" id="previewContainer">
                                                  <div class="sd_membership_widget_wrapper" id="sd_membership_widget_wrapper">
                                                       <div class="sidebar" id="sidebar" style="background:<?php echo isset($drawer_data->drawer_background_color) ? $drawer_data->drawer_background_color : '#ffffff'; ?>;">
                                                            <h1 class="Polaris-Heading sd_discount_drawer_heading" style="color: <?php echo isset($drawer_data->heading_text_color) ? $drawer_data->heading_text_color : '#000000'; ?>;">
                                                                 <?php echo isset($drawer_data->drawer_heading) ? $drawer_data->drawer_heading : 'Discounts & Offers'; ?>
                                                            </h1>
                                                            <div class="close-button" id="closeButton" style="color: <?php echo isset($drawer_data->cross_button_color) ? $drawer_data->cross_button_color : '#000000'; ?>;">
                                                                 &times;
                                                            </div>
                                                            <ul id="sd_all_coupon_content">
                                                                 <li>
                                                                      <div
                                                                           class="sd_discount_card_main sd_discount_code_bar"
                                                                           style="background-color: <?php echo isset($drawer_data->code_bg_color) ? $drawer_data->code_bg_color : '#63db69'; ?>;border:2px solid <?php echo isset($drawer_data->code_bar_border_color) ? $drawer_data->code_bar_border_color : '#000000'; ?>;"
                                                                      >
                                                                           <div class="sd_discount_card_content">
                                                                                <h3 class="sd_discount_code_bar" style="color:<?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;">
                                                                                     {free_shipping}
                                                                                     <button id="copied_code_value" value="`+discountCode+`" style="border: none; background-color: unset;" onclick="copy(this)" disabled>
                                                                                          <?xml ?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                                                                          <svg
                                                                                               class="sd_copied_icon"
                                                                                               fill="<?php echo isset($drawer_data->copied_button_color) ? $drawer_data->copied_button_color : ''; ?>"
                                                                                               width="20px"
                                                                                               height="20px"
                                                                                               viewBox="0 0 512 512"
                                                                                               xmlns="http://www.w3.org/2000/svg"
                                                                                          >
                                                                                               <title>ionicons-v5-e</title>
                                                                                               <path d="M408,480H184a72,72,0,0,1-72-72V184a72,72,0,0,1,72-72H408a72,72,0,0,1,72,72V408A72,72,0,0,1,408,480Z" />
                                                                                               <path d="M160,80H395.88A72.12,72.12,0,0,0,328,32H104a72,72,0,0,0-72,72V328a72.12,72.12,0,0,0,48,67.88V160A80,80,0,0,1,160,80Z" />
                                                                                          </svg>
                                                                                     </button>
                                                                                </h3>
                                                                                <p
                                                                                     class="sd_discount_code_bar sd_free_shipping_text"
                                                                                     style="color: <?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;"
                                                                                >
                                                                                     Use code {free_shipping} to get free shipping.
                                                                                </p>
                                                                                <p
                                                                                     class="sd_discount_code_bar sd_free_shipping_minimum_quantity"
                                                                                     style="color: <?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;"
                                                                                >
                                                                                     Applicable on minimum {minimum_quantity} quantity.
                                                                                </p>
                                                                                <p
                                                                                     class="sd_discount_code_bar sd_free_shipping_minimum_amount"
                                                                                     style="color: <?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;"
                                                                                >
                                                                                     Applicable on minimum {minimum_amount} order amount.
                                                                                </p>
                                                                           </div>
                                                                           <div class="sd_discount_coupon_code_main">
                                                                                <div
                                                                                     class="sd_discount_coupon_code sd_inner_code_bar"
                                                                                     style="background:<?php echo isset($drawer_data->code_bar_inner_bg_color) ? $drawer_data->code_bar_inner_bg_color : '#000000'; ?>;"
                                                                                >
                                                                                     <span class="scissors" style="color: <?php echo isset($drawer_data->scissor_color) ? $drawer_data->scissor_color : '#ffffff'; ?>;">
                                                                                          ✂
                                                                                     </span>
                                                                                     <button
                                                                                          class="code sd_inner_code_bar"
                                                                                          disabled
                                                                                          style="color:<?php echo isset($drawer_data->code_bar_inner_color) ? $drawer_data->code_bar_inner_color : '#ffffff'; ?>;"
                                                                                     >
                                                                                          free
                                                                                     </button>
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </li>

                                                                 <li>
                                                                      <div
                                                                           class="sd_discount_card_main sd_discount_code_bar"
                                                                           style="background-color: <?php echo isset($drawer_data->code_bg_color) ? $drawer_data->code_bg_color : '#63db69'; ?>;border:2px solid <?php echo isset($drawer_data->code_bar_border_color) ? $drawer_data->code_bar_border_color : '#000000'; ?>;"
                                                                      >
                                                                           <div class="sd_discount_card_content">
                                                                                <h3 class="sd_discount_code_bar" style="color:<?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;">
                                                                                     {discount_name}
                                                                                     <button disabled id="copied_code_value" value="`+discountCode+`" style="border: none; background-color: unset;" onclick="copy(this)">
                                                                                          <?xml?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                                                                          <svg
                                                                                               class="sd_copied_icon"
                                                                                               fill="<?php echo isset($drawer_data->copied_button_color) ? $drawer_data->copied_button_color : ''; ?>"
                                                                                               width="20px"
                                                                                               height="20px"
                                                                                               viewBox="0 0 512 512"
                                                                                               xmlns="http://www.w3.org/2000/svg"
                                                                                          >
                                                                                               <title>ionicons-v5-e</title>
                                                                                               <path d="M408,480H184a72,72,0,0,1-72-72V184a72,72,0,0,1,72-72H408a72,72,0,0,1,72,72V408A72,72,0,0,1,408,480Z" />
                                                                                               <path d="M160,80H395.88A72.12,72.12,0,0,0,328,32H104a72,72,0,0,0-72,72V328a72.12,72.12,0,0,0,48,67.88V160A80,80,0,0,1,160,80Z" />
                                                                                          </svg>
                                                                                     </button>
                                                                                </h3>
                                                                                <p
                                                                                     class="sd_discount_code_bar sd_percent_off_discount"
                                                                                     style="color: <?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;"
                                                                                >
                                                                                     Use code {discount_name} to get {discount_value}% off.
                                                                                </p>
                                                                                <p
                                                                                     class="sd_discount_code_bar sd_percent_off_specific_product"
                                                                                     style="color: <?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;"
                                                                                >
                                                                                     Applicable only on {product_name}.
                                                                                </p>
                                                                                <p
                                                                                     class="sd_discount_code_bar sd_percent_off_specific_collection"
                                                                                     style="color: <?php echo isset($drawer_data->code_bar_color) ? $drawer_data->code_bar_color : '#000000'; ?>;"
                                                                                >
                                                                                     Applicable only on {collection_name} collection products
                                                                                </p>
                                                                           </div>
                                                                           <div class="sd_discount_coupon_code_main">
                                                                                <div
                                                                                     class="sd_discount_coupon_code sd_inner_code_bar"
                                                                                     style="background:<?php echo isset($drawer_data->code_bar_inner_bg_color) ? $drawer_data->code_bar_inner_bg_color : '#000000'; ?>;"
                                                                                >
                                                                                     <span class="scissors" style="color: <?php echo isset($drawer_data->scissor_color) ? $drawer_data->scissor_color : '#ffffff'; ?>;">
                                                                                          ✂
                                                                                     </span>
                                                                                     <button
                                                                                          class="code sd_inner_code_bar"
                                                                                          style="color: <?php echo isset($drawer_data->code_bar_inner_color) ? $drawer_data->code_bar_inner_color : '#ffffff'; ?>;"
                                                                                     >
                                                                                          30% OFF
                                                                                     </button>
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </li>
                                                            </ul>
                                                            <div class="sd_discount_card_message" style="display: grid;">
                                                                 <span class="sd_code_applied_message sd_discount_text" style="color: <?php echo isset($drawer_data->all_text_color) ? $drawer_data->all_text_color : '#000000'; ?>;">
                                                                      <?php echo isset($drawer_data->copied_message_text) ? $drawer_data->copied_message_text : 'Code {discount_name} copied successfully.'; ?>
                                                                 </span>
                                                            </div>
                                                       </div>

                                                       <button
                                                            style="border-radius:<?php echo isset($drawer_data->discount_button_radius) ? $drawer_data->discount_button_radius : '1'; ?>px; color: <?php echo isset($drawer_data->button_text_color) ? $drawer_data->button_text_color : '#ffffff'; ?>; background:<?php echo isset($drawer_data->button_bg_color) ? $drawer_data->button_bg_color : '#000000'; ?>; padding:10px;"
                                                            class="sd_discount_button"
                                                            disabled
                                                       >
                                                            <?php echo isset($drawer_data->discount_button_text) ? $drawer_data->discount_button_text : 'Apply Discounts'; ?>
                                                       </button>
                                                       <button style="color: #fff; background: black; padding: 10px;" disabled>Checkout</button>
                                                  </div>
                                             </div>
                                             <div class="SD_saveButtonWidgets discount_drwr">
                                                  <button id="resetDrawerSetting" class="Polaris-Button Polaris-Button--primary sd_planDrawerSetting" type="button" btn-type="Reset">
                                                       <span class="Polaris-Button__Content">
                                                            <span class="Polaris-Button__Text">Reset</span>
                                                       </span>
                                                  </button>
                                                  <button id="saveDrawerSetting" class="Polaris-Button Polaris-Button--primary sd_planDrawerSetting" type="button" btn-type="Save">
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

<?php include("../footer.php"); ?>