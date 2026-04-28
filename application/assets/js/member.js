
var all_member_plans_array = [], already_created_member_plans = [], member_plan_group_product_data = [], plan_action_type = '', customer_name = '', active_plan = '', db_edit_subscriptiongroup_id = '', form_type = '', custom_product_image = '', member_plan_delete_index = '', db_edit_subscriptionplan_id = '', delete_member_product_type = '', delete_member_plan_id = '', delete_member_group_id = '', reset_template_setting = '', delete_member_group_ids = '', delete_member_product_id = '', delete_variant_id = '', upgradeOptionId = '', optionPriceValue = '', optionChargeValue = '', product_line_id = '', contractId = '', ption_charge_type = '', nchor_day = 0, parent_element = '', customer_email = '', sd_subscription_edit_case_already_existing_plans_array = {}, selected_button, edit_membership_id = '', edit_membership_modal, anchor_day = '', specific_member_plan_options_array, selected_popular_plan = '', checked_checkbox = '0', delete_product_id = '';
// const weekDaysArray = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
var currency_code = jQuery('#SHOPIFY_CURRENCY_CODE').val();
var currency = jQuery('#SHOPIFY_CURRENCY').val();
var app_redirect_url = 'stagesub/memberships/';
// console.log('currency_code', currency_code);
// console.log('currency', currency);

if (document.getElementById('membership_details_array')) {
    
    var membershipDetails = jQuery('#membership_details_array').val();
    const plan_type = jQuery('#membership_details_array').attr('data-attr');
    // var data_attr = jQuery('#membership_details_array').attr('data-id');
    var membershipDetailsArray = JSON.parse(membershipDetails);
    // Display the parsed array object in the console
    try {
        var membershipDetailsArray = JSON.parse(membershipDetails);
        // console.log(membershipDetailsArray);
        $.getScript("https://cdn.shopify.com/s/javascripts/currencies.js", function () {
            return;
        }).then(() => {
            var currency_amount = Currency.convert(100, 'INR', 'USD');
            // console.log('currency_amount', currency_amount);
            var total_membership_sale = 0;
            $.each(membershipDetailsArray, function (key, value) {
                // console.log('contract_currency', value.contract_currency);
                var currency_amount = Currency.convert(value.total_sale, value.contract_currency,
                    currency);
                    // console.log('currency_amount', currency_amount);
                    total_membership_sale = total_membership_sale + currency_amount;
                });
                console.log('00000000000000000000', total_membership_sale);
            // console.log('total membership sales',total_membership_sale);
            if (plan_type == 'membership') {

                document.getElementById('sd_total_sales').textContent =
                    currency_code + total_membership_sale.toFixed(2);

            } else {

                document.getElementById('sd_total_sales_sub').textContent =
                    currency_code + total_membership_sale.toFixed(2);
            }

        });
        var Currency = {
            convert: function (amount, from, to) {
                return (amount * this.rates[from]) / this.rates[to];
            }
        }
    } catch (e) {
        console.error('Error parsing JSON:', e);
    }
}



// Function to handle the back button click
function handleBackButtonClick() {
    jQuery('.create-memberPlan-buttons, .create_memberPlan_wrapper').addClass("display-hide-label");
    jQuery('.list_memberPlan_wrapper, .top-banner-create-memberPlan').removeClass("display-hide-label");
    jQuery('.member_plan_option_form').html();
}

jQuery("body").on("click", ".CreateMemberPlan", function () {
    parent_element = 'create_memberPlan_wrapper';

    jQuery('.create-memberPlan-buttons,.create_memberPlan_wrapper,.subscription-create-step1')
        .removeClass("display-hide-label");

    jQuery('.list_memberPlan_wrapper,.top-banner-create-memberPlan,.subscription-create-step2,.list_memberPlan_wrapper,.subscription-create-step3')
        .addClass("display-hide-label");

    sd_frequency_card_serialno = 0;

    var selling_plan_options = selling_plan_option_form();

    jQuery('#' + parent_element + ' .member_plan_option_form')
        .html(DOMPurify.sanitize(selling_plan_options));
});

jQuery('body').on('click', '.modal_cancel_button', function () {
    jQuery('.Polaris-Modal-Dialog__Container').addClass('display-hide-label');
});

jQuery('body').on('focusout', '#memberPlan_name', function () {
    parent_element = 'create_memberPlan_wrapper';
    var member_plan_name = jQuery('#' + parent_element + ' #memberPlan_name').val();
    var product_page_url = jQuery('#' + parent_element + ' #product_page_url').val();
    if (member_plan_name != '' && product_page_url == '') {
        product_handle = member_plan_name.replace(/\s+/g, '-').toLowerCase();
        jQuery('#' + parent_element + ' #product_page_url').val(product_handle);
    }
});

jQuery('body').on('click', '.show_another_option', function () {

    var option_html = selling_plan_option_form();
    var member_plan_option_length = jQuery('#' + parent_element + ' .member_plan_option_form').length;

    if (member_plan_option_length >= 2) {
        jQuery('#' + parent_element + ' .show_another_option').addClass('display-hide-label');
    }

    // 🔐 sanitize dynamic html
    const safeOptionHtml = DOMPurify.sanitize(option_html);

    // 🔐 safe wrapper create
    var nextId = member_plan_option_length + 1;

    var $wrapper = jQuery('<div>', {
        class: 'Polaris-Card__Section member_plan_option_form',
        'data-id': nextId
    });

    var $deleteBtn = jQuery('<div>', {
        class: 'delete_selected_option'
    }).html(`
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="30" width="30" class="delete_selected_option">
            <path d="M8 3.994C8 2.893 8.895 2 10 2s2 .893 2 1.994h4c.552 0 1 .446 1 .997 0 .55-.448.997-1 .997H4c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zM5 14.508V8h2v6.508a.5.5 0 00.5.498H9V8h2v7.006h1.5a.5.5 0 00.5-.498V8h2v6.508A2.496 2.496 0 0112.5 17h-5C6.12 17 5 15.884 5 14.508z" fill="#5C5F62"></path>
        </svg>
    `);

    var $content = jQuery('<div>').html(safeOptionHtml);

    $wrapper.append($deleteBtn, $content);

    jQuery('#' + parent_element + ' .selling_plan_options').append($wrapper);

    jQuery('#' + parent_element + ' .subscription_discount_status_wrapper').addClass('display-hide-label');
    jQuery('#' + parent_element + ' .set_member_price').removeClass('display-hide-label');
});

jQuery('body').on('click', '.delete_selected_option', function () {
    jQuery(this).parent('.member_plan_option_form').remove();
    jQuery('#' + parent_element + ' .show_another_option').removeClass('display-hide-label');
});



function isUrlValid(userInput) {
    var res = userInput.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
    if (res == null) {
        return false;
    }
    else {
        return true;
    }
}



jQuery('body').on('click', '.subscription_validate_step1', async function () {

    parent_element = 'create_memberPlan_wrapper'
    var member_plan_name = $.trim(jQuery('#' + parent_element + ' #memberPlan_name').val());
    var member_plan_image = $.trim(jQuery('#' + parent_element + ' #member_product_image').val());
    // console.log(member_plan_image,'inder')
    var check_validation = true;

    //handle validations error
    if (member_plan_name == '') {
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('Enter member plan name');
        check_validation = false;
    } else if (containsSpecialChars(member_plan_name) == true) {
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('Special characters are not allowed');
        check_validation = false;
    } else if (member_plan_image.length > 0 && isUrlValid(member_plan_image) == false) {
        jQuery('#' + parent_element + ' .member_product_image_error').text('Invalid image url');
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('');
        check_validation = false;
    } else {
        let ajaxParameters = {

            method: "POST",

            dataValues: {
                action: "memberPlanNameValidation",
                member_plan_name

            }
        };

        let result = await AjaxCall(ajaxParameters);
        console.log(result, 'popoppppppppppppp')
        if (result.isError == true) {
            jQuery('#' + parent_element + ' .memberPlan_name_error').text(
                'Name already exists in the database');
            check_validation = false;
            // return;
        } else {
            check_validation = true;
            var product_url = jQuery('#product_page_url').val();
            var product_description = jQuery('#member_product_description').val();
            if (product_url == '') {
                jQuery('.product_page_url_error').text('Add product url');
                // check_validation = false;
            }
            // }
            if (check_validation == true) {
                remove_error_messages();
                if (jQuery('.sd-group-plan-card')[0]) {
                    if (!jQuery('#sd_group_card_serialno_2')[0]) {
                        jQuery('.add_new_member_plan').html(
                            `<div class="Polaris-Layout__Section Polaris-Layout__Section--oneThird">
                                <div class="Polaris-Card1">
                                    <div class="Polaris-Card__Section sd_frequency_section sd_add_new_plan go-to-step2" attr-form-type="new" attr-form-id="" style="height:330px;">
                                        <div class="MuiBox-root css-1mq8drp" role="presentation" tabindex="0"><div class="MuiBox-root css-1t5p1px"><div class="MuiStack-root upload-placeholder css-ims6kp"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="32"><path d="M6.25 10a.75.75 0 0 1 .75-.75h2.25v-2.25a.75.75 0 0 1 1.5 0v2.25h2.25a.75.75 0 0 1 0 1.5h-2.25v2.25a.75.75 0 0 1-1.5 0v-2.25h-2.25a.75.75 0 0 1-.75-.75Z" fill="#5C5F62"></path><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"></path></svg><span class="MuiTypography-root MuiTypography-caption css-176slt">Add new tier</span></div></div></div>
                                    </div>
                                </div>
                            </div>`
                        );
                    } else {
                        jQuery('.add_new_member_plan').html('');
                    }
                    step3_preps();
                } else {
                    step2_preps('add');
                }
            }
            // if(selected_product_type == 'custom_product'){
            jQuery('.set_member_price').removeClass('display-hide-label');
            jQuery('.subscription_discount_status_wrapper').addClass(
                'display-hide-label');

        }
    }
});

function containsSpecialChars(str) {
    const specialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    return specialChars.test(str);
}



jQuery("body").on("click", ".go-to-step2", function () {
    checked_checkbox = '0';
    remove_error_messages();
    // new added code
    form_type = 'add';
    jQuery('.Polaris-Heading').text('Add member plan tier');
    $('#' + parent_element + ' #member_plan_tier_handle').prop("disabled", false);
    step2_preps(form_type);
});

function step2_preps(caseType) {
    if (caseType == 'edit') {
        jQuery('.step2_button_submit').html('Update');
    } else if (caseType == 'add') {
        reset_form("sd-subscription-group-form"); // reset form for new entry
        jQuery('.step2_button_submit').html('Add');
        jQuery('#' + parent_element + ' .member_plan_option_form').not(':first').remove();
    }

    if (jQuery('#' + parent_element + ' .sd-group-plan-card')[0]) {
        jQuery('.step2_button_cancel').removeClass('display-hide-label');
        jQuery('.step2_button_previous').addClass('display-hide-label');
    } else {
        jQuery('.step2_button_cancel').addClass('display-hide-label');
        jQuery('.step2_button_previous').removeClass('display-hide-label');
    }
    // show the button to add another option if hidden
    jQuery('#' + parent_element + ' .show_another_option').removeClass('display-hide-label');
    done_step('1');
    active_step('2');
    unfinshed_step('3');
    jQuery("#member_plan_tier_name").focus();
}

function step3_preps() {
    done_step('1');
    done_step('2');
    active_step('3');
    jQuery('#edit_group_card_serial_no').val('');
    jQuery('#membergroupid').val('');
}

jQuery("body").on("click", ".go-to-step3", function () {
    step3_preps();
});

function reset_form(parentclass) {
    jQuery("." + parentclass).find("input[type=text],input[type=hidden],input[type=number],textarea").val("");
    jQuery("." + parentclass).find('.sd_set_anchor_date_wrapper').addClass('display-hide-label');
    jQuery("." + parentclass).find("input[type=checkbox]").each(function () {
        if (this.checked) {
            $(this).prop('checked', false);
        }
    });
    change_select_html('.per_delivery_order_frequency_type', 'DAY');
    change_select_html('.sd_prepaid_billing_type', 'DAY');
}




function change_select_html(elementvalue, newvalue) {
    jQuery(elementvalue).parent().find(".Polaris-Select__SelectedOption").html(newvalue);
    jQuery(elementvalue + ' option').prop('selected', false);
    jQuery(elementvalue + " option[value='" + newvalue + "']").prop('selected', true);
}

function change_select_html_index(elementvalue, newvalue, key_index) {
    jQuery('#' + parent_element + ' ' + elementvalue).eq(key_index).parent().find(".Polaris-Select__SelectedOption")
        .html(newvalue);
    jQuery('#' + parent_element + ' ' + elementvalue + ' option').eq(key_index).prop('selected', false);
    jQuery('#' + parent_element + ' ' + elementvalue + ' option[value=' + newvalue + ']').eq(key_index).prop(
        'selected', true);
}

function active_step(step) {
    jQuery('#li_step' + step).addClass('active');
    jQuery('#li_step' + step).removeClass('completed');
    jQuery('#li_step' + step + ' .step-tick').addClass('display-hide-label');
    jQuery('#li_step' + step + ' .step-number').removeClass('display-hide-label');
    //for tab content
    jQuery('.form-steps-tab').addClass('display-hide-label');
    jQuery('.subscription-create-step' + step).removeClass('display-hide-label');
}


function backMemberPlans() {
    // jQuery('.create_memberPlan_wrapper, .list_memberPlan_wrapper, #edit_memberPlan_wrapper').addClass(
    //     'display-hide-label');
    // jQuery('.list_memberPlan_wrapper,.top-banner-create-memberPlan').removeClass('display-hide-label');
    // const folderName = '<?php //echo config('custom.FOLDER_NAME');?>';
    // const fullURL = '${app_redirect_url}membership-plan-list?shop=${shop}&host=${host}';
    // open(fullURL, '_self');
    location.reload();
}

function done_step(step) {
    jQuery('#li_step' + step).removeClass('active');
    jQuery('#li_step' + step).addClass('completed');
    jQuery('#li_step' + step + ' .step-tick').removeClass('display-hide-label');
    jQuery('#li_step' + step + ' .step-number').addClass('display-hide-label');
}

function unfinshed_step(step) {
    jQuery('#li_step' + step).removeClass('active');
    jQuery('#li_step' + step).removeClass('completed');
    jQuery('#li_step' + step + ' .step-tick').addClass('display-hide-label');
    jQuery('#li_step' + step + ' .step-number').removeClass('display-hide-label');
}




function discount_off_function(discount_type, selected_discount) {
    return `<div class="">
      <div class="Polaris-Labelled__LabelWrapper hide-label">
        <div class="Polaris-Label"><label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">hidden</label></div>
    </div>
      <div class="Polaris-Connected">
          <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
              <div class="Polaris-TextField sd-small-textfield"> <span class="sd-text-label">Discount</span><input placeholder="Enter Value" name="` +
        discount_type + `" class="Polaris-TextField__Input preview_plan ` + discount_type +
        `" min="1" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" aria-labelledby="PolarisTextField7Label" aria-invalid="false" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =
!!this.value &amp;&amp; Math.abs(this.value) > 0 ? Math.abs(this.value) : null" value="" maxlength="4">
                  <div class="Polaris-TextField__Backdrop"></div>
                  <div class="Polaris-Select sd-small-select"> <select name="per_delivery_order_frequency_type" class="Polaris-Select__Input ` +
        discount_type +
        `_type" aria-invalid="false">
                          <option value="Percent Off(%)">Percent Off(%)</option>
                          <option value="Discount Off">Discount Off</option>
                      </select>
                      <div class="Polaris-Select__Content" aria-hidden="true"> <span class="Polaris-Select__SelectedOption">` +
        selected_discount + `</span> <span class="Polaris-Select__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                      <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>
                                  </svg> </span> </span> </div>
                      <div class="Polaris-Select__Backdrop"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>`;
}





function selling_plan_option_form() {
    return `<div class="sd_check_parent_div">
    <input type = "hidden" class="membership_option_id" value="">
     <div class="Polaris-FormLayout__Items">
        <div class="Polaris-FormLayout__Item set_member_price">
            <div class="enable_copyright_box">
                <div class="Polaris-Labelled__LabelWrapper">
                    <div class="Polaris-Label"> <label id="TextField1Label3" for="TextField3" class="Polaris-Label__Text">Tier price</label> </div>
                    <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                    <span class="tooltiptext">Select pricing structure that best suits your membership preferences.</span></div>
                </div>
                <div class="Polaris-Connected">
                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                        <div class="Polaris-TextField__Prefix" id="PolarisTextField1-Prefix">${currency_code}</div>

                            <input id="tier_option_price" autocomplete="off" class="Polaris-TextField__Input tier_option_price tierDescriptionValue sd-polaris-number" data-col-name="tier_option_price" name="tier_option_price" type="text">

                            <div class="Polaris-TextField__Backdrop"></div>
                        </div>
                    </div>
                </div>
                <span class="tier_option_price_error error_messages"></span>
            </div>
        </div>
        <div class="Polaris-FormLayout__Item">
             <div class="">
                 <div class="Polaris-Labelled__LabelWrapper">
                     <div class="Polaris-Label"><label id="sd_order_fulfillment_frequency" for="PolarisTextField7" class="Polaris-Label__Text">Charge customer</label></div>
                     <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                    <span class="tooltiptext">Select frequency that best suits your membership preferences.</span></div>
                 </div>
                 <div class="Polaris-Connected">
                     <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                         <div class="Polaris-TextField sd-small-textfield"> <span class="sd-text-label">Every</span><input placeholder="Enter Value" name="option_charge_value" class="Polaris-TextField__Input preview_plan option_charge_value tierDescriptionValue" data-col-name="option_charge_value" min="1" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" aria-labelledby="PolarisTextField7Label" aria-invalid="false" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =
   !!this.value &amp;&amp; Math.abs(this.value) > 0 ? Math.abs(this.value) : null" value="" maxlength="2">
                             
                             <div class="Polaris-Select sd-small-select"> <select name="per_delivery_order_frequency_type" class="Polaris-Select__Input per_delivery_order_frequency_type tierDescriptionValue" data-col-name="time_period" id="per_delivery_order_frequency_type" aria-invalid="false">
                                     <option value="DAY">Day</option>
                                     <option value="WEEK">Week</option>
                                     <option value="MONTH">Month</option>
                                     <option value="YEAR">Year</option>
                                 </select>
                                 <div class="Polaris-Select__Content" aria-hidden="true"> <span class="per_delivery_order_frequency_option Polaris-Select__SelectedOption">DAY</span> <span class="Polaris-Select__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                 <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>
                                             </svg> </span> </span> </div>
                               
                             </div>
                         </div>
                     </div>
                 </div>
                 <span class="option_charge_value_error error_messages"></span>
             </div>
         </div>
     </div>
     </div>
     <div class="Polaris-FormLayout__Items">
         <div class="Polaris-FormLayout__Item sd_set_anchor_date_wrapper display-hide-label">
             <div class="enable_copyright_box">
                 <div class="Polaris-Connected">
                     <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                         <div class="Polaris-TextField"> <label class="switch"> <input type="checkbox" name="sd_set_anchor_date" class="sd_set_anchor_date"> <span class="slider round"></span>Set charge date</label> </div>
                     </div>
                 </div>
             </div>
         </div>
             <div class="Polaris-FormLayout__Item sd_anchor_option_wrapper display-hide-label">
                 <div class="">
                     <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label"><label id="PolarisTextField7Label" for="PolarisTextField7" class="Polaris-Label__Text">Select option</label></div>
                     </div>
                     <div class="Polaris-Connected">
                         <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                             <div class="Polaris-Select"> <select name="sd_anchor_option" class="Polaris-Select__Input sd_anchor_option" aria-invalid="false">
                                     <option value="On Purchase Day">On purchase day</option>
                                     <option value="On Specific Day">On specific day</option>
                                 </select>
                                 <div class="Polaris-Select__Content" aria-hidden="true"> <span class="Polaris-Select__SelectedOption">On Purchase Day</span> <span class="Polaris-Select__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                 <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>
                                             </svg> </span> </span> </div>
                                 <div class="Polaris-Select__Backdrop"></div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <span class="sd_anchor_option_error error_messages"></span>
             </div>
             <div class="Polaris-FormLayout__Item sd_anchor_week_day_wrapper display-hide-label">
                 <div class="">
                     <div class="Polaris-Labelled__LabelWrapper">
                         <div class="Polaris-Label"><label id="PolarisTextField7Label" for="PolarisTextField7" class="Polaris-Label__Text">Select day</label></div>
                     </div>
                     <div class="Polaris-Connected">
                         <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                             <div class="Polaris-Select"> <select name="sd_anchor_week_day" class="Polaris-Select__Input sd_anchor_week_day" aria-invalid="false">
                                     <option value="Sunday">Sunday</option>
                                     <option value="Monday">Monday</option>
                                     <option value="Tuesday">Tuesday</option>
                                     <option value="Wednesday">Wednesday</option>
                                     <option value="Thursday">Thursday</option>
                                     <option value="Friday">Friday</option>
                                     <option value="Saturday">Saturday</option>
                                 </select>
                                 <div class="Polaris-Select__Content" aria-hidden="true"> <span class="Polaris-Select__SelectedOption">Sunday</span> <span class="Polaris-Select__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                 <path d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z"></path>
                                             </svg> </span> </span> </div>
                                 <div class="Polaris-Select__Backdrop"></div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <span class="sd_anchor_week_day_error error_messages"></span>
             </div>
             <div class="Polaris-FormLayout__Item display-hide-label sd_anchor_month_day_wrapper">
                 <div class="">
                     <div class="Polaris-Labelled__LabelWrapper">
                         <div class="Polaris-Label"><label id="PolarisSelect2Label" for="sd_subscriptionPlan" class="Polaris-Label__Text">Month date</label></div>
                     </div>
                     <div class="Polaris-Connected">
                         <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                             <div class="Polaris-TextField"> <input min="1" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" aria-labelledby="PolarisTextField7Label" aria-invalid="false" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =
   !!this.value &amp;&amp; Math.abs(this.value) > 0 ? Math.abs(this.value) : null" value="" maxlength="2" class="Polaris-TextField__Input sd_anchor_month_day" name="sd_anchor_month_day">
                                 <div class="Polaris-TextField__Suffix" id="PolarisTextField1-Suffix">Day</div>
                                 <div class="Polaris-TextField__Backdrop"></div>
                             </div>
                         </div>
                         <div id="PolarisPortalsContainer"></div>
                     </div>
                 </div>
                 <span class="sd_anchor_month_day_error error_messages"></span>
             </div>
         </div> <!-- cut off day start -->
         <div class="Polaris-FormLayout__Items">
             <div class="Polaris-FormLayout__Item ">
                 <div class="">
                     <div class="Polaris-Labelled__LabelWrapper">
                         <div class="Polaris-Label"><label id="PolarisSelect2Label" for="sd_subscriptionPlan" class="Polaris-Label__Text">Minimum number of cycle</label></div>
                         <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                        <span class="tooltiptext">Minimum number of billing iteration you want to bind your customers with, before they can cancel their membership. Default value is one (the very first billing iteration).</span></div>
                     </div>
                     <div class="Polaris-Connected">
                         <div class="Polaris-Connected__Item Polaris-Connected__Item--primary sd_zindex">
                             <div class="Polaris-TextField"> <input min="1" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" aria-labelledby="PolarisTextField7Label" aria-invalid="false" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =
   !!this.value &amp;&amp; Math.abs(this.value) > 0 ? Math.abs(this.value) : null" value="" maxlength="2" class="Polaris-TextField__Input min_cycle tierDescriptionValue" data-col-name="min_cycle" name="min_cycle">
                                 <div class="Polaris-TextField__Backdrop"></div>
                             </div>
                         </div>
                         <div id="PolarisPortalsContainer"></div>
                     </div>
                 </div>
                 <span class="min_cycle_error error_messages"></span>
             </div>
            <div class="Polaris-FormLayout__Item">
                <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label"> <label id="TextField2Label" for="TextField2" class="Polaris-Label__Text">Maximum number of cycle</label> </div>
                            <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                            <span class="tooltiptext">Maximum number of billing iteration you want to bind your customers with, before they can cancel their membership. Default value is one (the very first billing iteration).</span></div>
                        </div>
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField"> <input min="1" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" type="number" aria-labelledby="PolarisTextField7Label"        aria-invalid="false" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.value =
                                    !!this.value &amp;&amp; Math.abs(this.value) > 0 ? Math.abs(this.value) : null" value="" maxlength="2" class="Polaris-TextField__Input max_cycle tierDescriptionValue" data-col-name="max_cycle" name="max_cycle">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>
                            </div>
                        </div>
                     <span class="max_cycle_error error_messages"></span>
                </div>
            </div>
         </div>
        <div class="Polaris-FormLayout__Items">
            <div class="Polaris-FormLayout__Item ">
                <div class="">
                    <div class="Polaris-Labelled__LabelWrapper">
                         <div class="Polaris-Label"><label id="PolarisSelect2Label" for="sd_subscriptionPlan" class="Polaris-Label__Text">Tier Description</label></div>
                         <div class="tooltip"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path fill-rule="evenodd" d="M10 7.25c-.69 0-1.25.56-1.25 1.25a.75.75 0 0 1-1.5 0 2.75 2.75 0 1 1 3.758 2.56.61.61 0 0 0-.226.147.154.154 0 0 0-.032.046.75.75 0 0 1-1.5-.003c0-.865.696-1.385 1.208-1.586a1.25 1.25 0 0 0-.458-2.414Z" fill="#5C5F62"/><path d="M10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="#5C5F62"/><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"/></svg>
                        <span class="tooltiptext">Explain the tier to customers so they are aware of the cancellation policy.</span></div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <textarea class="Polaris-TextField tier_description" data-attr="descriptionTextbox" id="description" type="text" value="" maxlength="1000" name="description" aria-labelledby="TextField6Label" aria-invalid="false" autocomplete="off"></textarea>
                            <span class="description_error error_messages"></span>
                        </div>
                    </div>
                </div>
                <span class="description_error error_messages"></span>
            </div>
        </div>
         `;
}




// var unique_handle_array = [];
var checkHandleArray = false;

function removeSpecialChars(text) {
    return text.replace(/[^A-Z0-9]/ig, "_");
}

// var random_number_generate = Math.floor(Math.random() * 100);

// edit member plan
jQuery("body").on('click', '#sd_edit_group', async function (e) {

    // console.log('already_created_member_plans', already_created_member_plans);
    remove_error_messages();
    var tempCustomerTag = false;
    var member_plan_tier_handle = jQuery('#' + parent_element + ' #member_plan_tier_handle').val();
    frequency_number_validation = true;
    let check_plan_options_validations = true;
    let checkPlanOptionsValidations = true;
    let ajaxError = false;
    var member_plan_tier_name = jQuery.trim(jQuery('#' + parent_element + ' #member_plan_tier_name').val());
    var popular_plan_id = removeSpecialChars(member_plan_tier_name);
    var group_description = jQuery('#' + parent_element + ' #group_description').val();
    if (member_plan_tier_handle == '') {
        jQuery('.member_plan_tier_handle_error').text('Enter customer tag');
        check_plan_options_validations = false;
    }

    // if (jQuery('#' + parent_element + ' #' + popular_plan_id + '_popular_plan').is(':checked')) {
    //     var popular_plan = '1';
    // } else {
    //     var popular_plan = '0';
    // }
    var edited_card_number = null,
        member_group_id = '';
    edited_group_id = jQuery('#membergroupid').val();
    if (form_type == 'edit') {
        edited_card_number = jQuery('#edit_group_card_serial_no').val();
    }
    if (member_plan_tier_name == '') {
        jQuery('.member_plan_tier_name_error').text('Enter member plan tier name');
        check_plan_options_validations = false;
    } else if (all_member_plans_array.length > 0) {
        var tier_name_index = all_member_plans_array.findIndex(function (item) {
            return (((item.member_plan_tier_name).toLowerCase()) == ((member_plan_tier_name)
                .toLowerCase()))
        });
        if (tier_name_index > -1 && tier_name_index !=
            edited_card_number) { // only splice array when item is found
            jQuery('.member_plan_tier_name_error').text('Tier name already exist');
            check_plan_options_validations = false;
        }
    }

    if ((parent_element == 'edit_memberPlan_wrapper' && form_type != 'edit') || (parent_element == 'create_memberPlan_wrapper')) {
        if ($.trim(member_plan_tier_handle) == '') {
            jQuery('.member_plan_tier_handle_error').text('Enter customer tag');
            check_plan_options_validations = false;
        } else if ($.trim(member_plan_tier_handle) != '') {
            if (all_member_plans_array.length > 0) {
                var tier_name_index = all_member_plans_array.findIndex(function (item) {
                    return (((item.member_plan_tier_handle).toLowerCase()) == ((member_plan_tier_handle).toLowerCase()))
                });
                if (tier_name_index > -1 && tier_name_index != edited_card_number) {
                    jQuery('.member_plan_tier_handle_error').text('Customer Tag already exist');
                    check_plan_options_validations = false;
                }
            }
        }
    }

    // Free trial validation
    const isCheckedstatus = jQuery('#' + parent_element + ' #member_offer_trial_period_status').prop("checked");
    console.log("isChecked:", isCheckedstatus);
    console.log("parent_element:", parent_element);
    // const member_offer_trial_period_val = jQuery("#member_offer_trial_period_value").val().trim();
  
    // free trial update
    var member_offer_trial_period_status_checked = jQuery('#' + parent_element + ' #member_offer_trial_period_status').prop("checked");
    var member_offer_trial_period_status_value = jQuery('#' + parent_element + ' #member_offer_trial_period_status').val().trim();
    var member_offer_trial_period_value = jQuery('#' + parent_element + ' #member_offer_trial_period_value').val().trim();
    var member_offer_trial_period_type_value = jQuery('#' + parent_element + ' #member_offer_trial_period_type').val();
    var renew_original_date_value = jQuery('#' + parent_element + ' #renew_original_date').val();

    var membership_subscription_discount_status = jQuery('#' + parent_element + ' #membership_subscription_discount_status').prop("checked");
    var membership_subscription_discount_status_value = jQuery('#' + parent_element + ' #membership_subscription_discount_status').val().trim();
    var membership_subscription_discount_value = jQuery('#' + parent_element + ' #membership_subscription_discount_value').val().trim();
    var membership_subscription_discount_type = jQuery('#' + parent_element + ' #membership_subscription_discount_type').val();

    var membership_subscription_discount_after_status = jQuery('#' + parent_element + ' #membership_subscription_discount_after_status').prop("checked");
    var membership_subscription_discount_after_status_value = jQuery('#' + parent_element + ' #membership_subscription_discount_after_status').val();
    // var membership_change_discount_after_cycle = jQuery('#' + parent_element + ' #membership_change_discount_after_cycle').val().trim();
    var membership_discount_value_after = jQuery('#' + parent_element + ' #membership_discount_value_after').val().trim();
    var membership_subscription_discount_type_after = jQuery('#' + parent_element + ' #membership_subscription_discount_type_after').val();

    // Console output update
    console.log("member_offer_trial_period_status_checked:", member_offer_trial_period_status_checked);
    console.log("membership_subscription_discount_type:", membership_subscription_discount_type);
    console.log("Trial period value:", member_offer_trial_period_value);
    console.log("Trial member_offer_trial_period_type_value value:", member_offer_trial_period_type_value);
    console.log("Renew original date value:", renew_original_date_value);

    if (isCheckedstatus && member_offer_trial_period_value == '') {
        jQuery('.member_free_trial_frequency_value_error').text('Free trial period value is required.');
        checkPlanOptionsValidations = false;
    }
    if (membership_subscription_discount_status && membership_subscription_discount_value == '') {
        jQuery('.membership_subscription_discount_value_error').text('discount value is required.');
        checkPlanOptionsValidations = false;
     
    }
    if (membership_subscription_discount_after_status && membership_discount_value_after == '') {
        jQuery('.membership_discount_value_after_error').text('discount value after is required.');
        checkPlanOptionsValidations = false;
    }

    new_member_plans_array = {};
    if (check_plan_options_validations == true) {
        new_member_plans_array['member_plan_tier_name'] = member_plan_tier_name;
        new_member_plans_array['member_plan_tier_handle'] = member_plan_tier_handle;
        new_member_plans_array['group_description'] = group_description;
        // new_member_plans_array['popular_plan'] = popular_plan;
        new_member_plans_array['memberPlan_products'] = selected_prd_vrt_ids;
        new_member_plans_array['popular_plan_id'] = popular_plan_id;
        new_member_plans_array['member_group_id'] = edited_group_id;

        // new_member_plans_array['offer_trial_status'] = member_offer_trial_period_status_checked;
        new_member_plans_array['trial_period_value'] = member_offer_trial_period_value;
        new_member_plans_array['offer_trial_status'] = member_offer_trial_period_status_value;
        new_member_plans_array['trial_period_type'] = member_offer_trial_period_type_value;
        new_member_plans_array['renew_on_original_date'] = renew_original_date_value;

        new_member_plans_array['discount_status'] = membership_subscription_discount_status_value;
        new_member_plans_array['discount_value'] = membership_subscription_discount_value;
        new_member_plans_array['discount_type'] = membership_subscription_discount_type;
      
        new_member_plans_array['discount_after_status'] = membership_subscription_discount_after_status_value;
        // new_member_plans_array['change_discount_after_cycle'] = membership_change_discount_after_cycle;
        new_member_plans_array['discount_value_after'] = membership_discount_value_after;
        new_member_plans_array['discount_type_after'] = membership_subscription_discount_type_after;

    }
    new_member_plans_array['member_plan_options'] = {};

    if (frequency_number_validation) {
        // check_plan_options_validations = true;
        jQuery('#' + parent_element + ' .member_plan_option_form').each(function (i, obj) {
            if (i == 0) { }


            new_member_plans_array['member_plan_options'][i] = {};
            // if(selected_product_type == 'custom_product'){
            var tier_option_price = jQuery(obj).find('#tier_option_price').val();
            if (tier_option_price == '') {
                jQuery(obj).find('.tier_option_price_error').text('Enter tier price');
            }
            // }
            var delivery_period = jQuery(obj).find('.option_charge_value').val();

            if (delivery_period == '') {
                jQuery(obj).find('.option_charge_value_error').text('Enter charge frequency value');
                check_plan_options_validations = false;
            } else if (i > 0) { // no two order charge frequency type should same
                if (new_member_plans_array['member_plan_options'][i - 1]['option_charge_type'] ==
                    jQuery(obj).find(
                        '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption')
                        .text()) {
                    jQuery(obj).find('.option_charge_value_error').text(
                        'No two charge frequency type can be same.');
                    check_plan_options_validations = false;
                }
                if ((i == 2) && (new_member_plans_array['member_plan_options'][i - 2][
                    'option_charge_type'
                ] == jQuery(obj).find(
                    '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption')
                    .text())) {
                    jQuery(obj).find('.option_charge_value_error').text(
                        'No two charge frequency type can be same.');
                    check_plan_options_validations = false;
                }
            }

            if ((jQuery(obj).find('input.sd_set_anchor_date').is(':checked')) && (jQuery(obj).find(
                '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption')
                .text() == 'MONTH')) {
                if (jQuery(obj).find('.sd_anchor_month_day').val() == '') {
                    jQuery(obj).find('.sd_anchor_month_day_error').text('Enter Month Date');
                    check_plan_options_validations = false;
                } else if (jQuery(obj).find('.sd_anchor_month_day').val() > 30) {
                    jQuery(obj).find('.sd_anchor_month_day_error').text(
                        'Enter value between 1 and 30');
                    check_plan_options_validations = false;
                }
            }


            if (jQuery(obj).find('.membership_subscription_discount_status').is(':checked')) {

                
                if (jQuery(obj).find('.discount_value').val() == '') {
                    jQuery(obj).find('.discount_value_error').text('Enter discount value');
                    check_plan_options_validations = false;
                } else if ((jQuery(obj).find('.discount_value_type').val() == 'Percent Off(%)') && (
                    jQuery(obj).find('discount_value').val() > 100)) {
                    jQuery(obj).find('.discount_value_error').text(
                        'Discount percentage value should not be greater than 100');
                    check_plan_options_validations = false;
                }

                if (jQuery(obj).find('.subscription_discount_after_status').is(':checked')) {
                    if (jQuery(obj).find('.discount_value_after').val() == '') {
                        jQuery(obj).find('.discount_value_after_error').text(
                            'Enter discount value');
                        check_plan_options_validations = false;
                    } else if ((jQuery(obj).find('.discount_value_after_type').val() ==
                        'Percent Off(%)') && (jQuery(obj).find('discount_value_after').val() >
                            100)) {
                        jQuery(obj).find('.discount_value_after_error').text(
                            'Discount percentage value should not be greater than 100');
                        check_plan_options_validations = false;
                    }
                    if (jQuery(obj).find('.change_discount_after_cycle').val() == '') {
                        jQuery(obj).find('.discount_value_after_error').text('Enter Cycle value');
                        check_plan_options_validations = false;
                    }
                }
            }

            if ((jQuery(obj).find('.min_cycle').val() != '') && (jQuery(obj).find('.max_cycle').val() != '')) {
                // console.log('min cycle value = ',typeof jQuery(obj).find('.min_cycle').val());
                // console.log('max cycle value = ',typeof  jQuery(obj).find('.max_cycle').val());
                // console.log(parseInt(jQuery(obj).find('.max_cycle').val()));
                if (parseInt(jQuery(obj).find('.min_cycle').val()) > parseInt(jQuery(obj).find('.max_cycle').val())) {
                    jQuery(obj).find('.min_cycle_error').text('Minimum cycle value should be equal or less than the maximum cycle value.');
                    check_plan_options_validations = false;
                }
            }

            if ((jQuery(obj).find('.max_cycle').val() != '')) {
                if (parseInt(jQuery(obj).find('.max_cycle').val()) <= 1) {
                    jQuery(obj).find('.max_cycle_error').text('Maximum cycle value should be greator than 1.');
                    check_plan_options_validations = false;
                }
            }

            if (jQuery(obj).find('.per_delivery_order_frequency_option.Polaris-Select__SelectedOption').text() ==
                'WEEK') {
                day_of_week = jQuery(obj).find('.sd_anchor_week_day').val();
                anchor_day = (weekDaysArray.indexOf(day_of_week) + 1);
            } else if (jQuery(obj).find(
                '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption').text() ==
                'MONTH') {
                anchor_day = jQuery(obj).find('.sd_anchor_month_day').val();
            }
            
            if (checkPlanOptionsValidations == false) {
                console.log('checkPlanOptionsValidations', checkPlanOptionsValidations);
                return false;
            }

            if (check_plan_options_validations == true) {
                new_member_plans_array['member_plan_options'][i]['option_charge_value'] = delivery_period;
                new_member_plans_array['member_plan_options'][i]['option_charge_type'] = jQuery(obj).find('.per_delivery_order_frequency_option.Polaris-Select__SelectedOption').text();
                new_member_plans_array['member_plan_options'][i]['set_anchor_date'] = jQuery(obj).find('input.sd_set_anchor_date').is(':checked');
                new_member_plans_array['member_plan_options'][i]['anchor_type'] = jQuery(obj).find('.sd_anchor_option').val();
                new_member_plans_array['member_plan_options'][i]['anchor_day'] = anchor_day;
                new_member_plans_array['member_plan_options'][i]['discount_offer'] = jQuery(obj).find('.subscription_discount_status').is(':checked');
                new_member_plans_array['member_plan_options'][i]['after_discount_offer'] = jQuery(obj).find('.subscription_discount_after_status').is(':checked');
                new_member_plans_array['member_plan_options'][i]['discount_value'] = jQuery(obj).find('.discount_value').val();
                new_member_plans_array['member_plan_options'][i]['discount_type'] = jQuery(obj).find('.discount_value_type').val();
                new_member_plans_array['member_plan_options'][i]['change_discount_after_cycle'] = jQuery(obj).find('.change_discount_after_cycle').val();
                new_member_plans_array['member_plan_options'][i]['after_discount_value'] = jQuery(obj).find('.discount_value_after').val();
                new_member_plans_array['member_plan_options'][i]['after_discount_type'] = jQuery(obj).find('.discount_value_after_type').val();
                new_member_plans_array['member_plan_options'][i]['min_cycle'] = jQuery(obj).find('.min_cycle').val();
                new_member_plans_array['member_plan_options'][i]['max_cycle'] = jQuery(obj).find('.max_cycle').val();
                new_member_plans_array['member_plan_options'][i]['description'] = jQuery(obj).find('.tier_description').val();
                // console.log(new_member_plans_array['member_plan_options'][i]['max_cycle']);
                new_member_plans_array['member_plan_options'][i]['membership_option_id'] = jQuery(obj).find('.membership_option_id').val();
                new_member_plans_array['member_plan_options'][i]['option_price'] = jQuery(obj).find('.tier_option_price').val();
            }
        });

        new_member_plans_array['member_plan_group_product_data'] = member_plan_group_product_data;
        new_member_plans_array['variant_id'] = jQuery('#group_variant_id').val();
        new_member_plans_array['member_plan_id'] = jQuery('#member_plan_id').val();
        new_member_plans_array['already_created_member_plans'] = already_created_member_plans;

        if (check_plan_options_validations == true && checkPlanOptionsValidations == true) {
            var new_member_plan_data = new FormData();
            new_member_plan_data.append('ajaxData', JSON.stringify(new_member_plans_array));
            shopify.loading(true);

            let ajaxParameters = {
                method: "POST",
                dataValues: {
                    action: "update-member-plan",
                    data: JSON.stringify(new_member_plans_array) // ✅ Send FormData directly
                }
            };

            console.log(ajaxParameters, 'ajaxParameters')

            var ajaxResult = await AjaxCall(ajaxParameters);
            shopify.loading(false);
            console.log(ajaxResult.isError);

            if (ajaxResult.isError == false) {
                // show_toast(response.message, false);
                console.log('result_message = ', ajaxResult.message);
                location.reload();

            } else {
                show_toast(ajaxResult.message, ajaxResult.isError);
                console.log('erroe');
            }

        }
    } else {
        let remove_class_params = {
            class_elements: [{
                name: "add-10only-frequency-error",
                classname: "display-hide-label"
            }]
        };
        remove_class(remove_class_params);
    }
});

// add member plan
jQuery("body").on('click', '#sd_add_group', async function (e) { // add frequency button click
    console.log('Click for add')
    remove_error_messages();
    // console.log('checked checkbox on adding group', checked_checkbox);
    var tempCustomerTag = false;
    frequency_number_validation = true;
    var check_plan_options_validations = true
    var parent_element = 'create_memberPlan_wrapper'
    const member_plan_tier_handle = jQuery(`#${parent_element} #member_plan_tier_handle`).val();
    // console.log(memberPlanTierHandle, 'memberPlanTierHandle')
    let frequencyNumberValidation = true;
    let checkPlanOptionsValidations = true;
    let ajaxError = false;

    const member_plan_tier_name = jQuery.trim(jQuery(`#${parent_element} #member_plan_tier_name`).val());
    const popular_plan_id = removeSpecialChars(member_plan_tier_name);
    const group_description = jQuery(`#${parent_element} #group_description`).val();

    if (member_plan_tier_handle === '') {
        jQuery('.member_plan_tier_handle_error').text('Enter customer tag');
        checkPlanOptionsValidations = false;
    }

    var edited_card_number = null,
    member_group_id = '';
    edited_group_id = jQuery('#membergroupid').val();
    if (form_type == 'edit') {
        edited_card_number = jQuery('#edit_group_card_serial_no').val();
    }
    if (member_plan_tier_name == '') {
        jQuery('.member_plan_tier_name_error').text('Enter member plan tier name');
        check_plan_options_validations = false;
    } else if (all_member_plans_array.length > 0) {
        var tier_name_index = all_member_plans_array.findIndex(function (item) {
            return (((item.member_plan_tier_name).toLowerCase()) == ((member_plan_tier_name)
                .toLowerCase()))
        });
        if (tier_name_index > -1 && tier_name_index !=
            edited_card_number) { // only splice array when item is found
            jQuery('.member_plan_tier_name_error').text('Tier name already exist');
            check_plan_options_validations = false;
        }
    }


    // free trial
    var member_offer_trial_period_status_checked = jQuery('#' + parent_element + ' #member_offer_trial_period_status').prop("checked");
    var member_offer_trial_period_status_value = jQuery('#' + parent_element + ' #member_offer_trial_period_status').val().trim();
    var member_offer_trial_period_value = jQuery('#' + parent_element + ' #member_offer_trial_period_value').val().trim();
    var member_offer_trial_period_type_value = jQuery('#' + parent_element + ' #member_offer_trial_period_type').val();
    var renew_original_date_value = jQuery('#' + parent_element + ' #renew_original_date').val();

    var membership_subscription_discount_status = jQuery('#' + parent_element + ' #membership_subscription_discount_status').prop("checked");
    var membership_subscription_discount_status_value = jQuery('#' + parent_element + ' #membership_subscription_discount_status').val().trim();
    var membership_subscription_discount_value = jQuery('#' + parent_element + ' #membership_subscription_discount_value').val().trim();
    var membership_subscription_discount_type = jQuery('#' + parent_element + ' #membership_subscription_discount_type').val();

    var membership_subscription_discount_after_status = jQuery('#' + parent_element + ' #membership_subscription_discount_after_status').prop("checked");
    var membership_subscription_discount_after_status_value = jQuery('#' + parent_element + ' #membership_subscription_discount_after_status').val().trim();
    // var membership_change_discount_after_cycle = jQuery('#' + parent_element + ' #membership_change_discount_after_cycle').val().trim();
    var membership_discount_value_after = jQuery('#' + parent_element + ' #membership_discount_value_after').val().trim();
    var membership_subscription_discount_type_after = jQuery('#' + parent_element + ' #membership_subscription_discount_type_after').val().trim();

    // Console output
    console.log("membership_subscription_discount_status:", membership_subscription_discount_status);
    console.log("membership_subscription_discount_status_value:", membership_subscription_discount_status_value);
    console.log("membership_subscription_discount_type:", membership_subscription_discount_type);
    console.log("membership_subscription_discount_value:", membership_subscription_discount_value);
    console.log("member_offer_trial_period_val:", member_offer_trial_period_value);
    console.log("Trial member_offer_trial_period_type_value value:", member_offer_trial_period_type_value);
    console.log("Renew original date value:", renew_original_date_value);

    // free trial validation
    const isChecked = jQuery('#' + parent_element + ' #member_offer_trial_period_status').prop("checked");


    var checkFreeTrialValidation = true;

    if (isChecked && member_offer_trial_period_value == '') {
        jQuery('.member_free_trial_frequency_value_error').text('Free trial period value is required.');
        checkPlanOptionsValidations = false;
    }
    if (membership_subscription_discount_status && membership_subscription_discount_value === '') {
        jQuery('.membership_subscription_discount_value_error').text('discount value is required.');
        checkPlanOptionsValidations = false;
     
    }
    if (membership_subscription_discount_after_status && membership_discount_value_after === '') {
        jQuery('.membership_discount_value_after_error').text('discount value after is required.');
        checkPlanOptionsValidations = false;
    }

    
    // if (jQuery('#' + parent_element + ' #' + popular_plan_id + '_popular_plan').is(':checked')) {
    //     var popular_plan = '1';
    // } else {
    //     var popular_plan = '0';
    // }

    

    if ((parent_element == 'edit_memberPlan_wrapper' && form_type != 'edit') || (parent_element == 'create_memberPlan_wrapper')) {
        if ($.trim(member_plan_tier_handle) == '') {
            jQuery('.member_plan_tier_handle_error').text('Enter customer tag');
            check_plan_options_validations = false;
        } else if ($.trim(member_plan_tier_handle) != '') {
       
            if (all_member_plans_array.length > 0) {
                var tier_name_index = all_member_plans_array.findIndex(function (item) {
                    return (((item.member_plan_tier_handle).toLowerCase()) == ((member_plan_tier_handle).toLowerCase()))
                });
                if (tier_name_index > -1 && tier_name_index != edited_card_number) {
                    jQuery('.member_plan_tier_handle_error').text('Customer Tag already exist');
                    check_plan_options_validations = false;
                }
            }
            var ajaxUrl = 'check-customer-tag';
            var customer_tag_array = {};
            customer_tag_array['customer_tag'] = member_plan_tier_handle;

            // var fd = new FormData();
            // fd.append('ajaxData', JSON.stringify(customer_tag_array));
            // fd.append('action', 'check-customer-tag'); //

            if (checkPlanOptionsValidations == false) {
                console.log('checkPlanOptionsValidations', checkPlanOptionsValidations);
                return false;
            }

            shopify.loading(true);

            let ajaxParameters = {
                method: "POST",
                dataValues: {
                    action: "check-customer-tag",
                    data: JSON.stringify(customer_tag_array) // ✅ Send FormData directly
                }
            };
            console.log(ajaxParameters, 'ajaxParameters')
            var ajaxResult = await AjaxCall(ajaxParameters);

            shopify.loading(false);
            // console.log('ajax resultttttttttttt = ', ajaxResult);
            if (ajaxResult.isError == true) {
                // console.log('ajax result errrrrrrrrrrrrrrrrrrrrrr= ', ajaxResult.isError);
                jQuery('.member_plan_tier_handle_error').text('Customer Tag already exist');
                check_plan_options_validations = false;
            } else {
                // console.log('ajax result = ', ajaxResult.isError);
            }
        }
    }

    new_member_plans_array = {};
    if (check_plan_options_validations == true) {
        new_member_plans_array['member_plan_tier_name'] = member_plan_tier_name;
        // new_member_plans_array['selected_product_type'] = selected_product_type;
        new_member_plans_array['member_plan_tier_handle'] = member_plan_tier_handle;
        new_member_plans_array['group_description'] = group_description;
        new_member_plans_array['popular_plan'] = checked_checkbox;
        new_member_plans_array['memberPlan_products'] = selected_prd_vrt_ids;
        new_member_plans_array['popular_plan_id'] = popular_plan_id;
        new_member_plans_array['member_group_id'] = edited_group_id;


        // new_member_plans_array['offer_trial_status'] = member_offer_trial_period_status_checked;
        new_member_plans_array['trial_period_value'] = member_offer_trial_period_value;
        new_member_plans_array['offer_trial_status'] = member_offer_trial_period_status_value;
        new_member_plans_array['trial_period_type'] = member_offer_trial_period_type_value;
        new_member_plans_array['renew_on_original_date'] = renew_original_date_value;

        new_member_plans_array['discount_status'] = membership_subscription_discount_status_value;
        new_member_plans_array['discount_value'] = membership_subscription_discount_value;
        new_member_plans_array['discount_type'] = membership_subscription_discount_type;
      
        new_member_plans_array['discount_after_status'] = membership_subscription_discount_after_status_value;
        // new_member_plans_array['change_discount_after_cycle'] = membership_change_discount_after_cycle;
        new_member_plans_array['discount_value_after'] = membership_discount_value_after;
        new_member_plans_array['discount_type_after'] = membership_subscription_discount_type_after;  
 
    }

    new_member_plans_array['member_plan_options'] = {};

    if (frequency_number_validation) {
        // check_plan_options_validations = true;
        jQuery('#' + parent_element + ' .member_plan_option_form').each(function (i, obj) {
            if (i == 0) { }
            new_member_plans_array['member_plan_options'][i] = {};
 
            // if(selected_product_type == 'custom_product'){
            var tier_option_price = jQuery(obj).find('#tier_option_price').val();
            if (tier_option_price == '') {
                jQuery(obj).find('.tier_option_price_error').text('Enter tier price');
            }
            // }
            var delivery_period = jQuery(obj).find('.option_charge_value').val();

            if (delivery_period == '') {
                jQuery(obj).find('.option_charge_value_error').text('Enter charge frequency value');
                check_plan_options_validations = false;
            } else if (i > 0) { // no two order charge frequency type should same
                if (new_member_plans_array['member_plan_options'][i - 1]['option_charge_type'] ==
                    jQuery(obj).find(
                        '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption')
                        .text()) {
                    jQuery(obj).find('.option_charge_value_error').text(
                        'No two charge frequency type can be same.');
                    check_plan_options_validations = false;
                }
                if ((i == 2) && (new_member_plans_array['member_plan_options'][i - 2][
                    'option_charge_type'
                ] == jQuery(obj).find(
                    '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption')
                    .text())) {
                    jQuery(obj).find('.option_charge_value_error').text(
                        'No two charge frequency type can be same.');
                    check_plan_options_validations = false;
                }
            }

            if ((jQuery(obj).find('input.sd_set_anchor_date').is(':checked')) && (jQuery(obj).find(
                '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption')
                .text() == 'MONTH')) {
                if (jQuery(obj).find('.sd_anchor_month_day').val() == '') {
                    jQuery(obj).find('.sd_anchor_month_day_error').text('Enter Month Date');
                    check_plan_options_validations = false;
                } else if (jQuery(obj).find('.sd_anchor_month_day').val() > 30) {
                    jQuery(obj).find('.sd_anchor_month_day_error').text(
                        'Enter value between 1 and 30');
                    check_plan_options_validations = false;
                }
            }

            // free trial validation membership
            if (jQuery(obj).find('.subscription_discount_status').is(':checked')) {

                if (jQuery(obj).find('.discount_value').val() == '') {
                    jQuery(obj).find('.discount_value_error').text('Enter discount value');
                    check_plan_options_validations = false;
                } else if ((jQuery(obj).find('.discount_value_type').val() == 'Percent Off(%)') && (
                    jQuery(obj).find('discount_value').val() > 100)) {
                    jQuery(obj).find('.discount_value_error').text(
                        'Discount percentage value should not be greater than 100');
                    check_plan_options_validations = false;
                }

                if (jQuery(obj).find('.subscription_discount_after_status').is(':checked')) {
                    if (jQuery(obj).find('.discount_value_after').val() == '') {
                        jQuery(obj).find('.discount_value_after_error').text(
                            'Enter discount value');
                        check_plan_options_validations = false;
                    } else if ((jQuery(obj).find('.discount_value_after_type').val() ==
                        'Percent Off(%)') && (jQuery(obj).find('discount_value_after').val() >
                            100)) {
                        jQuery(obj).find('.discount_value_after_error').text(
                            'Discount percentage value should not be greater than 100');
                        check_plan_options_validations = false;
                    }
                    if (jQuery(obj).find('.change_discount_after_cycle').val() == '') {
                        jQuery(obj).find('.discount_value_after_error').text('Enter Cycle value');
                        check_plan_options_validations = false;
                    }
                }
            }
            if ((jQuery(obj).find('.min_cycle').val() != '') && (jQuery(obj).find('.max_cycle').val() != '')) {
                // console.log('min cycle value = ',typeof jQuery(obj).find('.min_cycle').val());
                // console.log('max cycle value = ',typeof  jQuery(obj).find('.max_cycle').val());
                if (parseInt(jQuery(obj).find('.min_cycle').val()) > parseInt(jQuery(obj).find('.max_cycle').val())) {
                    jQuery(obj).find('.min_cycle_error').text('Minimum cycle value should be equal or less than the maximum cycle value.');
                    check_plan_options_validations = false;
                }
            }
            if (jQuery(obj).find('.per_delivery_order_frequency_option.Polaris-Select__SelectedOption').text() ==
                'WEEK') {
                day_of_week = jQuery(obj).find('.sd_anchor_week_day').val();
                anchor_day = (weekDaysArray.indexOf(day_of_week) + 1);
            } else if (jQuery(obj).find(
                '.per_delivery_order_frequency_option.Polaris-Select__SelectedOption').text() ==
                'MONTH') {
                anchor_day = jQuery(obj).find('.sd_anchor_month_day').val();
            }

            if (check_plan_options_validations == true) {
                new_member_plans_array['member_plan_options'][i]['option_charge_value'] = delivery_period;
                new_member_plans_array['member_plan_options'][i]['option_charge_type'] = jQuery(obj).find('.per_delivery_order_frequency_option.Polaris-Select__SelectedOption').text();
                new_member_plans_array['member_plan_options'][i]['set_anchor_date'] = jQuery(obj).find('input.sd_set_anchor_date').is(':checked');
                new_member_plans_array['member_plan_options'][i]['anchor_type'] = jQuery(obj).find('.sd_anchor_option').val();
                new_member_plans_array['member_plan_options'][i]['anchor_day'] = anchor_day;
                new_member_plans_array['member_plan_options'][i]['discount_offer'] = jQuery(obj).find('.subscription_discount_status').is(':checked');
                new_member_plans_array['member_plan_options'][i]['after_discount_offer'] = jQuery(obj).find('.subscription_discount_after_status').is(':checked');
                new_member_plans_array['member_plan_options'][i]['discount_value'] = jQuery(obj).find('.discount_value').val();
                new_member_plans_array['member_plan_options'][i]['discount_type'] = jQuery(obj).find('.discount_value_type').val();
                new_member_plans_array['member_plan_options'][i]['change_discount_after_cycle'] = jQuery(obj).find('.change_discount_after_cycle').val();
                new_member_plans_array['member_plan_options'][i]['after_discount_value'] = jQuery(obj).find('.discount_value_after').val();
                new_member_plans_array['member_plan_options'][i]['after_discount_type'] = jQuery(obj).find('.discount_value_after_type').val();
                new_member_plans_array['member_plan_options'][i]['min_cycle'] = jQuery(obj).find('.min_cycle').val();
                new_member_plans_array['member_plan_options'][i]['max_cycle'] = jQuery(obj).find('.max_cycle').val();
                new_member_plans_array['member_plan_options'][i]['description'] = jQuery(obj).find('.tier_description').val();
                new_member_plans_array['member_plan_options'][i]['membership_option_id'] = jQuery(obj).find('.membership_option_id').val();
                // if(selected_product_type == 'custom_product'){
                new_member_plans_array['member_plan_options'][i]['option_price'] = jQuery(obj).find('.tier_option_price').val();
                // }
            }
        });
        new_member_plans_array['member_plan_group_product_data'] = member_plan_group_product_data;
        if (check_plan_options_validations == true) {
            // console.log('new_member_plans_array', new_member_plans_array);

            if (form_type == 'edit') {
                all_member_plans_array[edited_card_number] = new_member_plans_array;
            } else {
                all_member_plans_array.push(new_member_plans_array);
            }
            let edit_frequency_card_serial_no = jQuery('#edit_group_card_serial_no').val();
            let membergroupid = jQuery('#membergroupid').val();
            let group_variant_id = jQuery('#group_variant_id').val();
            let new_card_no = jQuery('#new_card_no').val();
            db_edit_subscriptionplan_id = jQuery('#db_edit_subscriptionplan_id').val();
            create_member_plan_card(edit_frequency_card_serial_no, db_edit_subscriptionplan_id, membergroupid, group_variant_id, new_card_no, '');
            done_step('1');
            done_step('2');
            active_step('3');
        } else {
            new_member_plans_array['popular_plan'] = checked_checkbox;
            // console.log('new_member_plans_array nhi bna', new_member_plans_array);
        }
        jQuery('#create_memberPlan_wrapper .step3_button_group  button.step3_previous').addClass('go-to-step1');
        jQuery('#create_memberPlan_wrapper .step3_button_group  button.step3_previous').removeClass('go-to-step2');
    } else {
        let remove_class_params = {
            class_elements: [{
                name: "add-10only-frequency-error",
                classname: "display-hide-label"
            }]
        };
        remove_class(remove_class_params);
    }

});


jQuery('body').on('click', '.save-member-plan', async function () {
    if (all_member_plans_array.length <= 0) {
        show_toast('Add atleast one member tier.', true);
    } else {
        var page_type = jQuery(this).attr('data-id');
        var memberPlan_name = jQuery('#' + parent_element + ' #memberPlan_name').val();
        var memberPlan_id = jQuery('#' + parent_element + ' #member_plan_id').val();
        var checkedCheckbox = $('.popular_plan_checkbox:checked');
        if (checkedCheckbox.length === 1) {
            // console.log('checkbox is checked');
            var checkedValue = checkedCheckbox.val();
        } else {
            // console.log('checkbox is checked');
            var checkedValue = null;
        }

        // return false;
        var product_image = jQuery('#' + parent_element + ' #member_product_image').val();

        if (product_image.length > 0 && isUrlValid(product_image) == false) {
            jQuery('#' + parent_element + ' .member_product_image_error').text('Invalid image url');
            jQuery('#' + parent_element + ' .memberPlan_name_error').text('');
            check_validation = false;
            $('button[target-tab="memberplan_edit_settings"]').click()
            return false;
        }

        var ajaxUrl = page_type + '-member-plan';
        member_plan_data = {};
        member_plan_data['memberPlan_name'] = memberPlan_name;
        member_plan_data['memberPlan_id'] = memberPlan_id;
        member_plan_data['plan_product_type'] = 'custom_product';
        // if(selected_product_type == 'custom_product'){
        member_plan_data['member_product_url'] = jQuery('#' + parent_element + ' #product_page_url').val();
        member_plan_data['member_product_image'] = product_image;
        member_plan_data['already_created_member_plans'] = already_created_member_plans;
        member_plan_data['member_plan_group_product_data'] = member_plan_group_product_data;
        member_plan_data['member_product_description'] = jQuery('#' + parent_element + ' #member_product_description').val();
        member_plan_data['popular_plan'] = checkedValue;

        // }
        member_plan_data['memberPlan_data'] = all_member_plans_array;

        var fd = new FormData();
        fd.append('ajaxData', JSON.stringify(member_plan_data));
        let ajaxParameters = {

            method: "POST",

            dataValues: {
                action: ajaxUrl,
                data: JSON.stringify(member_plan_data)
            }
        };
        shopify.loading(true);

        var ajaxResult = await AjaxCall(ajaxParameters);
        shopify.loading(false);
        show_toast(ajaxResult.message, ajaxResult.isError);
        if (ajaxResult.isError == false) {
            // console.log('no error');
            if (page_type == 'update') {
                fullURL = `${app_redirect_url}membership-plan-list?shop=${shop}&host=${host}`;
                open(fullURL, '_self');
                shopify.loading(false);
            } else {
                fullURL = `${SHOPIFY_DOMAIN_URL}/admin/memberships/perks.php?member_plan_id=${ajaxResult.membership_plan_id}&shop=${shop}&host=${host}`;
                console.log(fullURL, 'fullURL')
                open(fullURL, '_self');
                shopify.loading(false);
            }
        } else {
            // console.log('yes error');
            shopify.loading(false);
        }
    }
});

function serializeObject(formID) {
    var formData = $('#' + formID).serializeArray();
    var formDataObject = {};
    $.each(formData, function (index, field) {
        formDataObject[field.name] = field.value;
    });
    formDataObject['templateName'] = formID;
    formDataObject['store'] = shop;
    return formDataObject;
}


// get data for edit member plan
jQuery('body').on('click', '.edit-membership-plan', async function () {

    all_member_plans_array = [];
    jQuery('.subscription-create-step3').removeClass('display-hide-label');
    // return false;
    var db_edit_memberPlan_id = jQuery(this).attr('data-member-id');
    console.log(db_edit_memberPlan_id);

    var new_card_no = '';
    var ajaxUrl = 'get-member-plan';
    get_member_plan = {};
    get_member_plan['member_plan_id'] = db_edit_memberPlan_id;
    var get_member_plan_data = new FormData();
    get_member_plan_data.append('ajaxData', JSON.stringify(get_member_plan));

    let ajaxParameters = {
        method: "POST",
        dataValues: {
            action: "get-member-plan",
            data: JSON.stringify(get_member_plan) // ✅ Send FormData directly
        }
    };

    shopify.loading(true);

    let ajaxResult = await AjaxCall(ajaxParameters);
    // console.log(ajaxResult, 'ajaxParameters')
    console.log("Group Detail Data:", ajaxResult.member_group_detail);
    // console.log("Matching Detail:", ajaxResult.member_group_id);

    shopify.loading(false);

    if (ajaxResult.isError == false) {

        parent_element = 'edit_memberPlan_wrapper';
        jQuery('#' + parent_element + ' .sd-group-plan-card-wrapper').html(
            '<div class="add_new_member_plan Polaris-Layout__Section Polaris-Layout__Section--oneThird"></div>'
        );
        var member_plan_data = ajaxResult.member_plan_data;

        var member_group_detail = ajaxResult.member_group_detail;
        console.log('member_plan_data', member_plan_data);

        document.getElementById("create_memberPlan_wrapper").classList.add("display-hide-label");
        document.getElementById('edit_memberPlan_wrapper').className = '';
        jQuery(".list_memberPlan_wrapper,.top-banner-create-memberPlan").addClass("display-hide-label");
        jQuery('.membership_edit_settings').find('#memberPlan_name').val(member_plan_data[0]
            .membership_plan_name);
        jQuery('.membership_edit_settings').find('#member_plan_id').val(member_plan_data[0].id);
        jQuery('.membership_edit_settings').find('#product_page_url').val(member_plan_data[0]
            .membership_product_url);
        jQuery('.membership_edit_settings').find('#member_product_description').val(member_plan_data[0]
            .membership_product_description);
        jQuery('.membership_edit_settings').find('#member_product_image').val(member_plan_data[0]
            .image_path);

        member_plan_data.forEach(function (obj, key) {
            console.log('member_plan_object_data', obj);
            console.log('key', key);
            var member_group_id = obj.membership_group_id;
            console.log("Group ID:", member_group_id);
            var group_variant_id = obj.variant_id;
            var product_id = obj.product_id;
            var member_group_details = member_group_detail[member_group_id];
            var member_plan_tier_name = obj.membership_group_name;
            var member_plan_tier_handle = obj.unique_handle;
            var group_description = obj.group_description;
            var popular_plan_id = removeSpecialChars(member_plan_tier_name);
            var popular_plan = obj.popular_plan;

            // ✅ Safely extract from the first entry in the group's details
          
            if (member_group_details && Object.keys(member_group_details).length > 0) {
       
                // let firstGroupId = Object.keys(member_group_details)[0];
                // let option = member_group_details[membership_group_id][0];

                let option = member_group_details[0]; // Take first option (or loop if needed)
                console.log("opction:", option);

                offer_trial_status = option.offer_trial_status;
                trial_period_value = option.trial_period_value;
                trial_period_type = option.trial_period_type;
                renew_on_original_date = option.renew_on_original_date;

                discount_offer = option.discount_offer;
                discount_value = option.discount_value;
                discount_type = option.discount_type;

                after_discount_offer = option.after_discount_offer;
                // change_discount_after_cycle = option.change_discount_after_cycle;
                after_discount_value = option.after_discount_value;
                after_discount_type = option.after_discount_type;
        
            }

 
            new_member_plans_array = {};
            new_member_plans_array['member_plan_tier_name'] = member_plan_tier_name;
            new_member_plans_array['popular_plan_id'] = popular_plan_id;
            new_member_plans_array['member_plan_tier_handle'] = member_plan_tier_handle;
            new_member_plans_array['group_description'] = group_description;
            new_member_plans_array['popular_plan'] = popular_plan;
            new_member_plans_array['member_group_id'] = member_group_id;
            new_member_plans_array['member_plan_options'] = member_group_details;

            new_member_plans_array['offer_trial_status'] = offer_trial_status;
            new_member_plans_array['trial_period_value'] = trial_period_value;
            new_member_plans_array['trial_period_type'] = trial_period_type;
            new_member_plans_array['renew_on_original_date'] = renew_on_original_date;

            new_member_plans_array['discount_status'] = discount_offer;
            new_member_plans_array['discount_value'] = discount_value;
            new_member_plans_array['discount_type'] = discount_type;

            new_member_plans_array['discount_after_status'] = after_discount_offer;
            // new_member_plans_array['change_discount_after_cycle'] = change_discount_after_cycle;
            new_member_plans_array['discount_value_after'] = after_discount_value;
            new_member_plans_array['discount_type_after'] = after_discount_type;
          
            create_member_plan_card(key, db_edit_memberPlan_id, member_group_id, group_variant_id, new_card_no, product_id);
            all_member_plans_array.push(new_member_plans_array);
            // already_created_member_group_ids.push(member_group_id);
        });
        already_created_member_plans = JSON.parse(JSON.stringify(all_member_plans_array));
        member_plan_group_product_data = ajaxResult.member_plan_data;
        sd_frequency_card_serialno = member_plan_data.length; // The new card that will be created get this serial no.
        jQuery('#db_edit_memberplan_id').val(db_edit_memberPlan_id);
        if (member_plan_data.length != 3) {
            jQuery('.sd_main_card_wrapper .add_new_member_plan').html(
                `<div class="Polaris-Layout__Section Polaris-Layout__Section--oneThird">
                        <div class="Polaris-Card1">
                            <div class="Polaris-Card__Section sd_frequency_section sd_add_new_plan go-to-step2" attr-form-type="new" attr-form-id="" style="height:330px;">
                                <div class="MuiBox-root css-1mq8drp" role="presentation" tabindex="0"><div class="MuiBox-root css-1t5p1px"><div class="MuiStack-root upload-placeholder css-ims6kp"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="32"><path d="M6.25 10a.75.75 0 0 1 .75-.75h2.25v-2.25a.75.75 0 0 1 1.5 0v2.25h2.25a.75.75 0 0 1 0 1.5h-2.25v2.25a.75.75 0 0 1-1.5 0v-2.25h-2.25a.75.75 0 0 1-.75-.75Z" fill="#5C5F62"></path><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"></path></svg><span class="MuiTypography-root MuiTypography-caption css-176slt">Add new tier</span></div></div></div>
                            </div>
                        </div>
                    </div>`);
            jQuery('.add_another_member_tier').removeClass('display-hide-label');
        } else {
            jQuery('.add_new_member_plan').html('');
        }
  
       // add first member plan options
        var selling_plan_options = selling_plan_option_form();
        jQuery('#' + parent_element + ' .member_plan_option_form')
            .html(DOMPurify.sanitize(selling_plan_options));
    } else {
        show_toast(ajaxResult.message, ajaxResult.isError);
    }
});



function remove_error_messages() {
    jQuery('.error_messages').text('');
}


function create_member_plan_card(edit_frequency_card_serial_no, db_edit_subscriptionplan_id, membergroupid, group_variant_id, new_card_no, product_id) {
    jQuery('.add-least-frequency-error').addClass('display-hide-label');

    function safeAttr(value) {
        return String(value || '').replace(/[^a-zA-Z0-9_-]/g, '');
    }

    function safeText(value) {
        return String(value ?? '');
    }

    function makeIconButtonSvg(type) {
        if (type === 'edit') {
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px"><path d="M14.846 1.403l3.752 3.753.625-.626A2.653 2.653 0 0015.471.778l-.625.625zm2.029 5.472l-3.752-3.753L1.218 15.028 0 19.998l4.97-1.217L16.875 6.875z" fill="#5C5F62"></path></svg>';
        }

        return '<svg width="20" viewBox="0 0 20 20" height="20px"><path fill-rule="evenodd" d="M14 4h3a1 1 0 011 1v1H2V5a1 1 0 011-1h3V1.5A1.5 1.5 0 017.5 0h5A1.5 1.5 0 0114 1.5V4zM8 2v2h4V2H8zM3 8h14v10.5a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 18.5V8zm4 3H5v6h2v-6zm4 0H9v6h2v-6zm2 0h2v6h-2v-6z" fill="#5C5F62"></path></svg>';
    }

    let frequencyType;
    let card_class;
    let subscription_plan_form_data;
    let card_serial_no;
    let case_type;
    let edit_case_new_card_value = safeAttr(new_card_no);

    if (db_edit_subscriptionplan_id.length) {
        if (membergroupid.length) {
            card_class = "edit-case-already-plan";
            case_type = "update";
            checkexistingplanschange = true;
        } else {
            card_class = "edit-case-new-plan";

            if (edit_frequency_card_serial_no.length) {
                case_type = "update";
            } else {
                case_type = "add";
                edit_case_new_card_value = safeAttr(sd_subscription_edit_case_to_be_added_new_plans_array.length);
            }
        }
    } else {
        card_class = "create-case-plan";

        if (edit_frequency_card_serial_no.length) {
            case_type = "update";
        } else {
            case_type = "add";
        }
    }

    if (case_type == "add") {
        card_serial_no = safeAttr(sd_frequency_card_serialno);
    } else {
        card_serial_no = safeAttr(edit_frequency_card_serial_no);
        jQuery("#sd_group_card_serialno_" + card_serial_no).remove();
    }

    let safeMemberGroupId = safeAttr(membergroupid);
    let safeGroupVariantId = safeAttr(group_variant_id);
    let safeProductId = safeAttr(product_id);
    let random_number_generate = Math.floor(Math.random() * 100);

    let $card = jQuery('<div>', {
        id: 'sd_group_card_serialno_' + card_serial_no,
        class: 'Polaris-Layout__Section Polaris-Layout__Section--oneThird sd-group-plan-card ' + card_class
    }).attr('attr-edit-case-new-plan-array-index', edit_case_new_card_value);

    let $cardInner = jQuery('<div>', {
        class: 'Polaris-Card1'
    });

    let $stack = jQuery('<div>', {
        class: 'Polaris-HorizontalStack'
    }).attr('style', '--pc-horizontal-stack-block-align:center;--pc-horizontal-stack-wrap:wrap');

    let $actions = jQuery('<div>', {
        class: 'member_plan_actions'
    });

    let $editButton = jQuery('<button>', {
        class: 'edit_group_card Polaris-Button',
        type: 'button'
    })
        .attr('attr-edit-case-new-plan-array-index', edit_case_new_card_value)
        .attr('attr-card-serial-no', card_serial_no)
        .attr('attr-id', safeMemberGroupId)
        .attr('attr-variant_id', safeGroupVariantId)
        .html(makeIconButtonSvg('edit'));

    let $deleteButton = jQuery('<button>', {
        class: 'remove-btn Polaris-Button delete_member_card',
        type: 'button'
    })
        .attr('attr-card-serial-no', card_serial_no)
        .attr('attr-id', safeMemberGroupId)
        .attr('attr-variant_id', safeGroupVariantId)
        .attr('attr-product-id', safeProductId)
        .html(makeIconButtonSvg('delete'));

    $actions.append($editButton, $deleteButton);
    $stack.append($actions);

    let $section = jQuery('<div>', {
        class: 'Polaris-Card__Section sd_frequency_section'
    });

    let $sectionHeader = jQuery('<div>', {
        class: 'Polaris-Card__SectionHeader'
    });

    let $frequencyStack = jQuery('<div>', {
        class: 'Polaris-Stack Polaris-Stack--alignmentBaseline sd_frequency_stack'
    });

    let $frequencyPlans = jQuery('<div>', {
        class: 'Polaris-Stack__Item Polaris-Stack__Item--fill sd_frequencyPlans'
    });

    let $activeImg = jQuery('<img>', {
        src: SHOPIFY_DOMAIN_URL + '/application/assets/images/membership_active_user1.png',
        class: 'sd_active_user_image'
    });

    $frequencyPlans.append($activeImg);
    $frequencyStack.append($frequencyPlans);
    $sectionHeader.append($frequencyStack);

    let $mainList = jQuery('<ul>', {
        class: 'Polaris-List'
    });

    let $plansOptionsList = jQuery('<div>', {
        class: 'plans_options_List'
    });

    jQuery('<li>', {
        class: 'Polaris-List__Item',
        text: safeText(new_member_plans_array['member_plan_tier_name'])
    }).appendTo($plansOptionsList);

    jQuery('<li>', {
        class: 'Polaris-List__Item'
    }).append(jQuery('<b>', { text: 'Member plan tier options' })).appendTo($plansOptionsList);

    let $optionList = jQuery('<ul>', {
        class: 'Polaris-List option_list'
    });

    let $formLayout = jQuery('<div>', {
        class: 'Polaris-FormLayout'
    });

    let $group = jQuery('<div>', {
        role: 'group',
        class: 'Polaris-FormLayout--grouped'
    });

    let $items = jQuery('<div>', {
        class: 'Polaris-FormLayout__Items'
    });

    jQuery.each(new_member_plans_array['member_plan_options'], function (index, obj) {
        let $item = jQuery('<div>', {
            class: 'Polaris-FormLayout__Item'
        });

        let $wrapper = jQuery('<div>');

        let $labelWrapper = jQuery('<div>', {
            class: 'Polaris-Labelled__LabelWrapper'
        });

        let $labelDiv = jQuery('<div>', {
            class: 'Polaris-Label'
        });

        let $label = jQuery('<label>', {
            id: ':R1mq6:Label',
            for: ':R1mq6:',
            class: 'Polaris-Label__Text',
            text: safeText(obj.option_charge_value) + ' ' + safeText(obj.option_charge_type)
        });

        $labelDiv.append($label);
        $labelWrapper.append($labelDiv);

        let $connected = jQuery('<div>', {
            class: 'Polaris-Connected'
        });

        let $connectedItem = jQuery('<div>', {
            class: 'Polaris-Connected__Item Polaris-Connected__Item--primary'
        });

        let $priceSpan = jQuery('<span>', {
            class: '',
            text: safeText(currency_code) + ' ' + safeText(obj.option_price)
        });

        $connectedItem.append($priceSpan);
        $connected.append($connectedItem);
        $wrapper.append($labelWrapper, $connected);
        $item.append($wrapper);
        $items.append($item);
    });

    $group.append($items);
    $formLayout.append($group);
    $optionList.append($formLayout);
    $mainList.append($plansOptionsList, $optionList);

    let check_checkbox = new_member_plans_array['popular_plan'] == '1';

    let $popularPlan = jQuery('<div>', {
        class: 'sd_popular_plan'
    });

    let $popularLabel = jQuery('<label>', {
        class: 'Polaris-Choice Polaris-Checkbox__ChoiceLabel',
        for: random_number_generate + '_popular_plan'
    });

    let $choiceControl = jQuery('<span>', {
        class: 'Polaris-Choice__Control'
    });

    let $checkboxSpan = jQuery('<span>', {
        class: 'Polaris-Checkbox'
    });

    let $popularInput = jQuery('<input>', {
        id: random_number_generate + '_popular_plan',
        value: safeText(new_member_plans_array['member_plan_tier_name']),
        name: 'popular_plan',
        type: 'checkbox',
        class: 'Polaris-Checkbox__Input popular_plan_checkbox',
        'aria-invalid': 'false',
        role: 'checkbox',
        'aria-checked': 'false'
    }).attr('attr-id', safeMemberGroupId);

    if (check_checkbox) {
        $popularInput.prop('checked', true);
    }

    let $backdrop = jQuery('<span>', {
        class: 'Polaris-Checkbox__Backdrop'
    });

    let $checkboxIcon = jQuery('<span>', {
        class: 'Polaris-Checkbox__Icon'
    }).html(
        '<span class="Polaris-Icon">' +
            '<span class="Polaris-Text--root Polaris-Text--visuallyHidden"></span>' +
            '<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">' +
                '<path fill-rule="evenodd" d="M14.03 7.22a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 1 1 1.06-1.06l1.72 1.72 3.97-3.97a.75.75 0 0 1 1.06 0Z"></path>' +
            '</svg>' +
        '</span>'
    );

    $checkboxSpan.append($popularInput, $backdrop, $checkboxIcon);
    $choiceControl.append($checkboxSpan);

    let $choiceLabel = jQuery('<span>', {
        class: 'Polaris-Choice__Label'
    }).append(jQuery('<span>', { text: 'Make this plan popular' }));

    $popularLabel.append($choiceControl, $choiceLabel);
    $popularPlan.append($popularLabel);

    $section.append($sectionHeader, $mainList, $popularPlan);
    $cardInner.append($stack, $section);
    $card.append($cardInner);

    if (case_type == "add") {
        jQuery('#' + parent_element)
            .find('.sd-group-plan-card-wrapper .add_new_member_plan')
            .before($card);

        sd_frequency_card_serialno++;
    } else {
        jQuery('#' + parent_element)
            .find('#sd_group_card_serialno_' + card_serial_no)
            .remove();

        if (card_serial_no == 0) {
            jQuery('#' + parent_element)
                .find('.sd-group-plan-card-wrapper .add_new_member_plan')
                .before($card);
        } else {
            let before_elements = parseInt(card_serial_no) - 1;

            jQuery('#' + parent_element)
                .find("#sd_group_card_serialno_" + before_elements)
                .after($card);
        }
    }

    if (jQuery('.sd-group-plan-card').length <= 2) {
        let $addPlanWrapper = jQuery('<div>', {
            class: 'Polaris-Layout__Section Polaris-Layout__Section--oneThird'
        });

        let $addPlanCard = jQuery('<div>', {
            class: 'Polaris-Card1'
        });

        let $addPlanSection = jQuery('<div>', {
            class: 'Polaris-Card__Section sd_frequency_section sd_add_new_plan go-to-step2'
        })
            .attr('attr-form-type', 'new')
            .attr('attr-form-id', '')
            .css('height', '330px');

        $addPlanSection.html(
            '<div class="MuiBox-root css-1mq8drp" role="presentation" tabindex="0">' +
                '<div class="MuiBox-root css-1t5p1px">' +
                    '<div class="MuiStack-root upload-placeholder css-ims6kp">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="32">' +
                            '<path d="M6.25 10a.75.75 0 0 1 .75-.75h2.25v-2.25a.75.75 0 0 1 1.5 0v2.25h2.25a.75.75 0 0 1 0 1.5h-2.25v2.25a.75.75 0 0 1-1.5 0v-2.25h-2.25a.75.75 0 0 1-.75-.75Z" fill="#5C5F62"></path>' +
                            '<path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"></path>' +
                        '</svg>' +
                        '<span class="MuiTypography-root MuiTypography-caption css-176slt">Add new tier</span>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        $addPlanCard.append($addPlanSection);
        $addPlanWrapper.append($addPlanCard);

        jQuery('.add_new_member_plan').empty().append($addPlanWrapper);
    } else {
        jQuery('.add_new_member_plan').empty();
    }

    jQuery('#sd_add_frequency_span').text('Add Frequency');
    jQuery('#sd_add_group').removeClass('update_member_plan');
    jQuery('.edit_frequency_button_group').addClass('display-hide-label');
    reset_form("sd-subscription-group-form");
}



// const okButton_existing_product = Button.create(app, {
//     label: 'Ok'
// });
// okButton_existing_product.subscribe(Button.Action.CLICK, () => {
//     // Do something with the click action
// });
// const cancelButton_existing_product = Button.create(app, {
//     label: 'Cancel'
// });
// cancelButton_existing_product.subscribe(Button.Action.CLICK, () => {
//     // Do something with the click action
// });

//resource picker product select
selected_prd_vrt_ids = {};

jQuery("body").on("click", "#add_memberPlan_product", function () {
    if (Object.keys(selected_prd_vrt_ids).length === 0 || (typeof (selected_prd_vrt_ids[
        'variant_item_data']) !== 'undefined' && (selected_prd_vrt_ids['variant_item_data'])
            .length == 0
    )) {
        productWithVariantsSelected = {};
    } else {
        selected_variant_ids_array = [];
        jQuery.each(selected_prd_vrt_ids['variant_item_data'], function (pop_key, pop_value) {
            var pop_key = {
                id: 'gid://shopify/ProductVariant/' + (pop_value.variant_id),
            };
            selected_variant_ids_array.push(pop_key);
        });
        productWithVariantsSelected = {
            id: 'gid://shopify/Product/' + selected_prd_vrt_ids['product_id'],
            variants: selected_variant_ids_array
        };
    }
    const picker = ResourcePicker.create(app, {
        resourceType: ResourcePicker.ResourceType.Product,
        options: {
            initialSelectionIds: [productWithVariantsSelected],
            selectMultiple: false,
            showHidden: false,
        },
    });
    picker.dispatch(ResourcePicker.Action.OPEN);
    picker.subscribe(ResourcePicker.Action.SELECT, (payload) => {
        if ((payload.selection).length > 0) {
            selected_variants_array = payload.selection[0]['variants'];
            selected_variant_html =
                '<ul class="Polaris-ResourceList" id="create-section-products-show">';
            var selected_vrt_ids_array = [];
            selected_variants_array.forEach(function (variantItem) {
                variant_item_data = {};
                if (typeof variantItem['image'] !== 'undefined' && (variantItem['image'])
                    .length > 0) {
                    variant_image = variantItem['image']['originalSrc'];
                } else if (typeof payload.selection[0]['images'] !== 'undefined' && (payload
                    .selection[0]['images']).length > 0) {
                    variant_image = payload.selection[0]['images'][0]['originalSrc'];
                } else {
                    variant_image = '';
                }
                variant_id = ((variantItem['id']).replace("gid://shopify/ProductVariant/",
                    "")).trim();
                variant_item_data['variant_id'] = variant_id;
                variant_item_data['variant_image'] = variant_image;
                variant_item_data['product_name'] = variantItem['displayName'];

                selected_vrt_ids_array.push(variant_item_data);
                product_id = ((variantItem['product']['id']).replace(
                    "gid://shopify/Product/", "")).trim();
                selected_variant_html += `
                <li class="Polaris-ResourceItem__ListItem" id="display_variant_` + variant_id +
                    `">
                <div class="Polaris-ResourceItem__ItemWrapper">
                <div class="Polaris-ResourceItem Polaris-ResourceItem--selectable">
                <div class="Polaris-ResourceItem__Container">
                <div class="Polaris-ResourceItem__Owned">
                <div class="Polaris-ResourceItem__Media"><span role="img" class="Polaris-Avatar Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage"><img src="` +
                    variant_image + `" class="Polaris-Avatar__Image" alt="" role="presentation"></span></div>
                </div>
                <div class="Polaris-ResourceItem__Content">
                <h3><span class="Polaris-TextStyle--variationStrong">` + variantItem['displayName'] + `</span></h3>
                <div></div>
                </div>
                </div>
                </div>
                <button type="button" variant-id="` + variant_id + `" product-id="` + product_id + `" class="Polaris-Tag__Button display_remove_variant">
                <span class="Polaris-Icon">
                <span class="Polaris-VisuallyHidden"></span>
                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                <path d="m11.414 10 4.293-4.293a.999.999 0 1 0-1.414-1.414L10 8.586 5.707 4.293a.999.999 0 1 0-1.414 1.414L8.586 10l-4.293 4.293a.999.999 0 1 0 1.414 1.414L10 11.414l4.293 4.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z"></path>
                </svg>
                </span>
                </button>
                </div>
                </li>`;
            });
            selected_prd_vrt_ids['product_id'] = product_id;
            selected_prd_vrt_ids['variant_item_data'] = selected_vrt_ids_array;
            selected_variant_html += `</ul>`;
            jQuery('.sd_selected_existing_product').html(selected_variant_html);
        } else {
            selected_prd_vrt_ids
        }
    });
    productWithVariantsSelected = {};
});




// var existing_product_modalOptions = {
//     title: 'Warning',
//     message: 'You can create only one member plan at a time with the existing product. If you have created more than one member plans then it will be deleted automatically.',
//     footer: {
//         buttons: {
//             primary: okButton_existing_product,
//             secondary: [cancelButton_existing_product],
//         },
//     },
// };
// var member_plan_error = Modal.create(app, existing_product_modalOptions);
// var closeUnsubscribe = member_plan_error.subscribe(Modal.Action.CLOSE, () => {
//     // Do something with the close event
// });

// var okButton_delete_created_memberPlan = Button.create(app, {
//     label: 'Ok'
// });
// var cancelButton_delete_created_memberPlan = Button.create(app, {
//     label: 'Cancel'
// })
// okButton_delete_created_memberPlan.subscribe(Button.Action.CLICK, async () => {
//     var ajaxUrl = 'delete-member-plan';
//     delete_member_plan_data = {};
//     delete_member_plan_data['member_plan_id'] = delete_member_plan_id;
//     delete_member_plan_data['member_group_ids'] = delete_member_group_ids;
//     delete_member_plan_data['member_product_type'] = delete_member_product_type;
//     delete_member_plan_data['member_product_id'] = delete_member_product_id;
//     delete_member_plan_data['member_perk_freeshipping_ids'] = delete_member_perk_freeshipping_ids;
//     delete_member_plan_data['member_perk_pricerule_ids'] = delete_member_perk_pricerule_ids;
//     var delete_member_plan_formData = new FormData();
//     delete_member_plan_formData.append('ajaxData', JSON.stringify(delete_member_plan_data));
//     
//     shopify.loading(true);
//     delete_created_member_plan.dispatch(Modal.Action.CLOSE);
//     var ajaxResult = await ajaxCall(ajaxUrl, delete_member_plan_formData);
//     shopify.loading(false);
//     show_toast(ajaxResult.message, ajaxResult.isError);
//     if (ajaxResult.isError == false) {
//         // history.dispatch(History.Action.PUSH, `/membership-staging/membership-plan-list`);
//         // window.location.href = `membership-plan-list?host=${host}&shop=${shop}`;


//         // var fullURL = `${app_redirect_url}membership-plan-list?host=${host}&shop=${shop}`;
// open(fullURL, '_self');


//         location.reload();
//     }
// });
// cancelButton_delete_created_memberPlan.subscribe(Button.Action.CLICK, () => {
//     delete_created_member_plan.dispatch(Modal.Action.CLOSE);
// });







//edit membership start
// var edit_membership = {
//     title: 'Warning',
//     message: 'Are you sure you want to deactivate the plan? ',
//     footer: {
//         buttons: {
//             primary: okButton_edit_membership,
//             secondary: [cancelButton_edit_membership],
//         },
//     },
// };

// var okButton_edit_membership = Button.create(app, {
//     label: 'Ok'
// });
// var cancelButton_edit_membership = Button.create(app, {
//     label: 'Cancel'
// })

// cancelButton_edit_membership.subscribe(Button.Action.CLICK, async() => {
//     edit_membership_modal.dispatch(Modal.Action.CLOSE);
// });
jQuery("body").on("click", ".sd_update_membership_option", async function () {
    var previous_option_id = jQuery('#previous_option_id').val();
    var option_select = document.querySelector('#sd_select_option');
    var selected_option_id = option_select.options[option_select.selectedIndex].getAttribute(
        'data-option-id');
    // var selected_variant_id = option_select.options[option_select.selectedIndex].getAttribute('data-variant_id');
    if (previous_option_id == selected_option_id) {
        jQuery('.Polaris-Modal-Dialog__Container').addClass('display-hide-label');
    } else {
        let selected_option_index = specific_member_plan_options_array.map(function (e) {
            return e.membership_option_id;
        }).indexOf(selected_option_id);
        selected_option_data = specific_member_plan_options_array[selected_option_index];
        ajaxUrl = 'update-existing-membership';
        selected_option_array = {};
        // selected_option_array['selected_variant_id'] = selected_variant_id;
        selected_option_array['selected_option_data'] = selected_option_data;
        selected_option_array['edit_contract_id'] = edit_contract_id;
        selected_option_array['edit_line_item_id'] = edit_line_item_id;
        var plan_option_data = new FormData();
        var aq = plan_option_data.append('ajaxData', JSON.stringify(selected_option_array));
        var ajaxResult = await ajaxCall(ajaxUrl, plan_option_data);
    }
});

jQuery("body").on("click", ".sd_edit_membership", async function () {
    selected_button = jQuery(this);
    selected_option_id = jQuery(this).attr('data-member_plan_option_id');
    edit_memberplan_id = jQuery(this).attr('data-memberplan_id');
    edit_contract_id = jQuery(this).attr('data-contract_id');
    edit_line_item_id = jQuery(this).attr('data-line_item_id');
    ajaxUrl = 'view-member-modal';
    membership_plan_array = {};
    membership_plan_array['selected_option_id'] = selected_option_id;
    membership_plan_array['edit_memberplan_id'] = edit_memberplan_id;
    var membership_data = new FormData();
    membership_data.append('ajaxData', JSON.stringify(membership_plan_array));
    var ajaxResult = await ajaxCall(ajaxUrl, membership_data);
    specific_member_plan_options_array = ajaxResult.sd_plan_option_array;
    jQuery('.sd_modal_content').html(ajaxResult.plan_options);
    jQuery('.sd_modal_heading').text('Select Tier to update');
    jQuery('.Polaris-Modal-Dialog__Container').removeClass('display-hide-label');
    jQuery('.Polaris-Modal-Dialog__Container .modal_ok_button').addClass('sd_update_membership_option');
});




jQuery("body").on("click", ".sd_deactivate_membership", function () {
    selected_button = jQuery(this);
    membership_contract_id = jQuery(this).attr('data-contract_id');
    status_change_to = jQuery(this).attr('data-status_changeto');
    deactivate_membership_modal.dispatch(Modal.Action.OPEN);
});

//delete member plan card
jQuery("body").on("click", ".delete_member_card", function (e) {
    e.preventDefault();
    // console.log('parent element', parent_element);
    member_plan_delete_index = jQuery(this).attr('attr-card-serial-no');
    delete_member_group_id = jQuery(this).attr('attr-id');
    delete_variant_id = jQuery(this).attr('attr-variant_id');
    delete_product_id = jQuery(this).attr('attr-product-id');
    if (jQuery('.sd-group-plan-card').length == 1) {
        show_toast('Atleast one tier should be there in the plan.', true);
    } else {
        // delete_member_plan_modal.dispatch(Modal.Action.OPEN);
        modalType = 'delete-variant-card';
        callShopifyModal(modalType);
    }
});




//edit member plan group card start
jQuery("body").on('click', '.edit_group_card', function () {
    remove_error_messages();
    var container = $(this).closest('.sd-group-plan-card');
    // Find the checkbox with the name 'popular_plan' within the container
    var checkbox = container.find('input[name="popular_plan"]');
    // Check if the checkbox is checked
    if (checkbox.is(':checked')) {
        checked_checkbox = '1';
    } else {
        checked_checkbox = '0';
    }
    // console.log('checked_checkbox = ', checked_checkbox);
    form_type = 'edit';
    jQuery('.Polaris-Heading').text('Edit member plan tier');
    let card_serial_no = jQuery(this).attr('attr-card-serial-no');
    let membergroupid = jQuery(this).attr('attr-id');
    // console.log('membergroupid', membergroupid);
    let group_variant_id = jQuery(this).attr('attr-variant_id');
    let card_detail, new_card_no;
    to_check_existing_plan_array = Object.assign({},
    sd_subscription_edit_case_already_existing_plans_array);
    jQuery('#edit_group_card_serial_no').val(card_serial_no);
    jQuery('#membergroupid').val(membergroupid);
    jQuery('#group_variant_id').val(group_variant_id);
    console.log(parent_element)
    if (membergroupid.length) {
        $('#' + parent_element + ' #member_plan_tier_handle').prop("disabled", true);
    } else {
        $('#' + parent_element + ' #member_plan_tier_handle').prop("disabled", false);
    }
    card_detail = all_member_plans_array[card_serial_no];
    console.log(card_detail, 'card_detailcard_detailcard_detailcard_detail')

    jQuery('.sd_add_group').addClass('update_member_plan');
    jQuery('#' + parent_element + ' #member_plan_tier_name').val(card_detail.member_plan_tier_name);
    jQuery('#' + parent_element + ' #member_plan_tier_handle').val(card_detail.member_plan_tier_handle);
    jQuery('#' + parent_element + ' #group_description').val(card_detail.group_description);

  


    if (card_detail.offer_trial_status == 'on' || card_detail.offer_trial_status == '1') {
        jQuery('#' + parent_element + '#member_offer_trial_period_status').prop("checked");
        jQuery('#' + parent_element + ' #member_offer_trial_period_status').click()
        jQuery('#' + parent_element + ' #member_offer_trial_period_status').val('on');

        jQuery('#' + parent_element + ' #member_offer_trial_period_value').val(card_detail.trial_period_value)
        jQuery('#' + parent_element + ' #member_offer_trial_period_type').val(card_detail.trial_period_type).trigger('change');
    }

    if (card_detail.renew_on_original_date == 'true') {
        jQuery('#' + parent_element + ' #renew_original_date').prop('checked', true);
        jQuery('#' + parent_element + ' #renew_original_date').val(card_detail.renew_on_original_date);
    } else {
        jQuery('#' + parent_element + ' #renew_original_date').prop('checked', false);
    }

    
    if (card_detail.discount_status == 'on' || card_detail.discount_status == '1') {
        var discount_type = card_detail.discount_type;
        var offer_discount_type = (discount_type == 'P' || discount_type == 'Percent Off(%)') ? 'Percent Off(%)' : 'Discount Off';
        console.log('offer_discount_type :', offer_discount_type);

        jQuery('#' + parent_element + ' #membership_subscription_discount_status').click()
        jQuery('#' + parent_element + ' #membership_subscription_discount_status').val('on');
        jQuery('#' + parent_element + ' #membership_subscription_discount_value').val(card_detail.discount_value);
        jQuery('#' + parent_element + ' #membership_subscription_discount_type').val(offer_discount_type).trigger('change');
    }

    if (card_detail.discount_after_status == 'on' || card_detail.discount_after_status == '1') {
        
        // var after_discount_type = (card_detail.discount_type_after == 'P') ? 'Percent Off(%)' : 'Discount Off';

        var after_discount_type = (card_detail.discount_type_after == 'P' || card_detail.discount_type_after == 'Percent Off(%)') ? 'Percent Off(%)' : 'Discount Off';
        jQuery('#' + parent_element + ' #membership_subscription_discount_after_status').click()
        jQuery('#' + parent_element + ' #membership_subscription_discount_after_status').val('on');
        // jQuery('#' + parent_element + ' #membership_change_discount_after_cycle').val(Number(card_detail.change_discount_after_cycle));

        jQuery('#' + parent_element + ' #membership_discount_value_after').val(card_detail.discount_value_after);
        jQuery('#' + parent_element + ' #membership_subscription_discount_type_after').val(after_discount_type).trigger('change');
    }

    jQuery('#' + parent_element + ' #group_description').val(card_detail.group_description);
    // var selected_product_type = jQuery('input[name="add_member_product"]:checked').val();
    var key_index = 0;
    Object.values(card_detail.member_plan_options).forEach(obj => {
        jQuery('#' + parent_element + ' .show_another_option').removeClass(
            'display-hide-label');
        if (!(jQuery('#' + parent_element + ' .member_plan_option_form')[key_index])) {
            jQuery('#' + parent_element + ' .show_another_option').click();
        }
        jQuery('#' + parent_element + ' .membership_option_id').eq(key_index).val(obj
            .membership_option_id);
        // if(selected_product_type == 'custom_product'){
        jQuery('#' + parent_element + ' .tier_option_price').eq(key_index).val(obj.option_price);
        jQuery('#' + parent_element + ' .option_charge_value').eq(key_index).val(obj.option_charge_value);
        change_select_html_index('.per_delivery_order_frequency_type', obj.option_charge_type, key_index);
        jQuery('#' + parent_element + ' .min_cycle').eq(key_index).val(obj.min_cycle);
        jQuery('#' + parent_element + ' .max_cycle').eq(key_index).val(obj.max_cycle);
        jQuery('#' + parent_element + ' .tier_description').eq(key_index).val(obj.description);
        if (obj.set_anchor_date == true) {
            jQuery('#' + parent_element + ' .sd_set_anchor_date').eq(key_index).prop('checked', true);
            change_select_html_index('.sd_anchor_option', (obj.sd_anchor_option), key_index);
            if (obj.anchor_type == 'On Specific Day' && anchor_day != 0) {
                change_select_html_index('.per_delivery_order_frequency_type', weekDaysArray[obj.anchor_day - 1], key_index);
            } else if (obj.anchor_type == 'On Specific Day' && obj.anchor_day != 0) {
                jQuery('.sd_anchor_month_day').eq(key_index).val(obj.anchor_day);
            }
        }

        // free trial
        
        key_index++;
    });
    // To delete the empty options check the deleted options
    if (key_index != 3) { //this means no options are empty
        if (key_index == 2 && (jQuery('#' + parent_element + ' .member_plan_option_form')[2])) {
            if (jQuery('#' + parent_element + ' .member_plan_option_form')[2]) {
                (jQuery('#' + parent_element + ' .member_plan_option_form')[2]).remove();
            }
        } else if (key_index == 1 && (jQuery('#' + parent_element + ' .member_plan_option_form')[1])) {
            if (jQuery('#' + parent_element + ' .member_plan_option_form')[2]) {
                (jQuery('#' + parent_element + ' .member_plan_option_form')[2]).remove();
            }
            if (jQuery('#' + parent_element + ' .member_plan_option_form')[1]) {
                (jQuery('#' + parent_element + ' .member_plan_option_form')[1]).remove();
            }
        }
    }
    active_step('2');
    step2_preps('edit');
    done_step('1');
    unfinshed_step('3');
    if (key_index > 2) {
        jQuery('.show_another_option').addClass('display-hide-label');
    }
    // }
});


function delete_member_card(member_plan_index, member_plan_id) {
    jQuery("#sd_group_card_serialno_" + member_plan_index).remove();
    sd_frequency_card_serialno--;
    let attr_edit_case_new_plan_array_index = 0;
    //after deletion re-arrange the cards - start
    jQuery('.sd-group-plan-card').each(function (i, obj) {
        jQuery(this).attr("id", "sd_group_card_serialno_" + i);
        jQuery(this).find(".delete_member_card").attr("attr-card-serial-no", i);
        jQuery(this).find(".edit_group_card").attr("attr-card-serial-no", i);
    });
    // var selected_product_type = jQuery('input[name="add_member_product"]:checked').val();
    if (jQuery('#' + parent_element + ' .sd-group-plan-card').length <= 2) {
        jQuery('#' + parent_element + ' .add_new_member_plan').html(
            `<div class="Polaris-Layout__Section Polaris-Layout__Section--oneThird">
                        <div class="Polaris-Card1">
                            <div class="Polaris-Card__Section sd_frequency_section sd_add_new_plan go-to-step2" attr-form-type="new" attr-form-id="" style="height:330px;">
                                <div class="MuiBox-root css-1mq8drp" role="presentation" tabindex="0"><div class="MuiBox-root css-1t5p1px"><div class="MuiStack-root upload-placeholder css-ims6kp"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="32"><path d="M6.25 10a.75.75 0 0 1 .75-.75h2.25v-2.25a.75.75 0 0 1 1.5 0v2.25h2.25a.75.75 0 0 1 0 1.5h-2.25v2.25a.75.75 0 0 1-1.5 0v-2.25h-2.25a.75.75 0 0 1-.75-.75Z" fill="#5C5F62"></path><path fill-rule="evenodd" d="M10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm0-1.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" fill="#5C5F62"></path></svg><span class="MuiTypography-root MuiTypography-caption css-176slt">Add new tier</span></div></div></div>
                            </div>
                        </div>
                    </div>`);
    } else { }
}

jQuery("body").on("click", ".membership_plan_save", async function () {

    parent_element = 'edit_memberPlan_wrapper';

    member_plan_name = $.trim(jQuery('#' + parent_element + ' #memberPlan_name').val());
    member_plan_description = $.trim(jQuery('#' + parent_element + ' #member_product_description').val());
    member_plan_image_url = $.trim(jQuery('#' + parent_element + ' #member_product_image').val());
    memberPlan_id = jQuery('#' + parent_element + ' #member_plan_id').val();

    if (member_plan_image_url.length > 0 && isUrlValid(member_plan_image_url) == false) {
        jQuery('#' + parent_element + ' .member_product_image_error').text('Invalid image url');
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('');
    }

    console.log(memberPlan_id);

    //handle validations error
    if (member_plan_name == '') {
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('Enter member plan name');
        check_validation = false;
    } else if (containsSpecialChars(member_plan_name) == true) {
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('Special characters are not allowed');
        check_validation = false;
    } else if (member_plan_image_url.length > 0 && isUrlValid(member_plan_image_url) == false) {
        jQuery('#' + parent_element + ' .member_product_image_error').text('Invalid image url');
        jQuery('#' + parent_element + ' .memberPlan_name_error').text('');
        check_validation = false;
    } else {

        check_validation = true;
        shopify.loading(true);

        const update_member_plan = {
            member_plan_name: member_plan_name,
            member_plan_description: member_plan_description,
            member_plan_image_url: member_plan_image_url,
            shop: shop,
            member_plan_group_product_data: member_plan_group_product_data,
            memberPlan_id: memberPlan_id
        };

        console.log(update_member_plan);

        // Set up AJAX parameters
        let ajaxParameters = {
            method: "POST",
            dataValues: {
                action: "edit-membership-plan",
                data: JSON.stringify(update_member_plan)
            }
        };

        // Send request
        let ajaxResult = await AjaxCall(ajaxParameters);

        console.log(ajaxResult, 'ajaxParameters')
        shopify.loading(false);

        if (ajaxResult.isError == false) {
            // show_toast(response.message, false);
            console.log('result_message = ', ajaxResult.message);
            location.reload();

        } else {
            console.log('erroe');
            show_toast(ajaxResult.message, ajaxResult.isError);
        }

    }

});


// Membership free trial


$(document).ready(function () {

    jQuery("body").on("click", "#member_offer_trial_period_status", function () {
        membershiptoggleFreeElementStatus(this);
    });

    function membershiptoggleFreeElementStatus(element) {
        const $targetElement = jQuery(element);
        const targetId = $targetElement.attr("id");

        if (targetId === "member_offer_trial_period_status") {
  
            const isChecked = $targetElement.prop("checked");

            $targetElement.val(isChecked ? 'on' : '');

            if (isChecked) {

                // $('body #membership_change_discount_after_cycle').val(1);
                $('body .sd_discount_change_div').hide();
                jQuery('.membership_sd_subscription_discount_offer_wrapper,.membership_sd_change_discount_after,.membership_sd_subscription_discount_offer_after_wrapper').removeClass('display-hide-label');

                // If checked, set related checkboxes to 'on', check, and disable them
                jQuery("#membership_subscription_discount_status, #membership_subscription_discount_after_status")
                    .val("on")
                    .prop("checked", true)
                    .prop("disabled", true);

            } else {
                console.log('kkkkkkk',isChecked);
                // jQuery('body #membership_change_discount_after_cycle').val('');
                $('body .membership_sd_discount_change_div').show();
                jQuery('body #membership_offer_trial_period_value').val('');
                jQuery('#membership_renew_original_date').val('false').prop("checked", false);
                // jQuery("#membership_subscription_discount_status, #membership_subscription_discount_after_status").prop("disabled", false);
                // jQuery("#membership_subscription_discount_status").prop("disabled", false);
                jQuery("body #membership_subscription_discount_status").prop("disabled", false);

                // jQuery("#membership_subscription_discount_status").removeAttr("disabled");
              
            }

            // Toggle visibility of the trial-related label
            jQuery('.sd_membership_discount_free_trial').toggleClass('display-hide-label', !isChecked);
        }

        // Log the updated statuses for debugging
        console.log("Updated:", {
            offer_trial_period_status: jQuery("#member_offer_trial_period_status").val(),
            membership_subscription_discount_status: jQuery("#membership_subscription_discount_status").val(),
            subscription_discount_after_status: jQuery("#membership_subscription_discount_after_status").val(),
        });
    }

    
    jQuery("body").on("change", "#membership_subscription_discount_status", function () {
        
        console.log('membership_subscription_discount_status:', jQuery(this).prop("checked"));
        // console.log('membership_subscription_discount_after_status:', jQuery('#membership_subscription_discount_after_status').prop("checked"));

        if (jQuery(this).prop("checked") == true) {
            console.log('ggggg',jQuery(this).prop("checked"));
            jQuery(this).val('on');
            jQuery('body .membership_sd_subscription_discount_offer_wrapper').removeClass('display-hide-label');
            jQuery('body .membership_sd_change_discount_after').removeClass('display-hide-label');
            jQuery('body .membership_sd_subscription_discount_offer_after_wrapper ').removeClass('display-hide-label');
            jQuery("body #membership_subscription_discount_after_status").val("on").prop("checked", true).prop("disabled", true);
            
        } else {

            jQuery('body #membership_subscription_discount_status').val('');
            jQuery('body #membership_subscription_discount_after_status').val('');
            jQuery('body #membership_subscription_discount_after_status').prop('checked', false);
            jQuery('body #membership_subscription_discount_after_status').prop("disabled", false);
            // jQuery("#membership_subscription_discount_after_status").val("").prop("checked", false).prop("disabled", false);

            jQuery('body #membership_subscription_discount_value,#membership_discount_value_after').val('');
            jQuery('body .membership_sd_subscription_discount_offer_wrapper, body .membership_sd_change_discount_after, body .membership_sd_subscription_discount_offer_after_wrapper').addClass('display-hide-label');
            // jQuery('body .membership_sd_subscription_discount_offer_after_wrapper').addClass('display-hide-label');
        }
    });



    jQuery("body").on("change", "#membership_subscription_discount_after_status", function () {
        if (jQuery(this).prop("checked") == true) {
            console.log('membership_subscription_discount_after_status:', jQuery(this).prop("checked"));

            jQuery(this).val('on');
            jQuery('.membership_sd_subscription_discount_offer_after_wrapper').removeClass('display-hide-label');

            // scrollToBottom('.sd_selling_form');
            jQuery('body .membership_sd_discount_change_div').show();
        } else {
            jQuery('body #membership_subscription_discount_after_status').val('');  // overwrite status
            jQuery('body #membership_discount_value_after').val('');  // overwrite value
            // jQuery('#membership_change_discount_after_cycle,#membership_discount_value_after').val('');
            jQuery('.membership_sd_subscription_discount_offer_after_wrapper').addClass('display-hide-label');
            jQuery('body .membership_sd_discount_change_div').hide();
        }
    });

});

// add  Automatic discount ninjja code
jQuery("body").on("click", ".CreateDiscountPlan", function () {
    console.log('hlw');
    parent_element = 'create_discount_wrapper';
    jQuery('#create_discountPlan_wrapper,.sd_create_discount_wrapper').removeClass("display-hide-label");

     jQuery('.list_discountPlan_wrapper,.top-banner-create-discountPlan').addClass("display-hide-label");
  

});

// add or update save button

jQuery("body").on('click', '#sd_add_edit_discount', async function (e) {
    console.log('Click for add & edit discount plan');
    remove_error_messages();

    let discountPlanValidation = true;

    const discount_plan_name = jQuery(`#discount_name`).val();
    const discount_plan_value = jQuery(`#discount_value`).val();
    const discount_check_status = jQuery('#enable_discount').is(':checked');
    const discount_checked_value = discount_check_status ? "Active" : "Inactive";

    // Product info
    const $firstProductLi = $('#discount-section-products-show-list .Polaris-ResourceItem__ListItem').first();
    const productOriginalId = $firstProductLi.find('.Polaris-discountRemoveProductButton').attr('original-product-id');
    const productTitle = $firstProductLi.find('.Polaris-TextStyle--variationStrong').text().trim();
    const product_imageUrl = $firstProductLi.find('img.Polaris-Avatar__Image').attr('src');

    // Collection info
    const $firstCollectionLi = $('#discount-section-collection-show-list li').first();
    const collectionOriginalId = $firstCollectionLi.find('button').attr('original-collection-id');
    const collectionTitle = $firstCollectionLi.find('span.Polaris-TextStyle--variationStrong').text().trim();
    const collectionImage = $firstCollectionLi.find('img.Polaris-Avatar__Image').attr('src');

    // Validation block
    if (discount_plan_name == '') {
        jQuery('.discount_plan_name_error').show().text('Enter discount name');
        discountPlanValidation = false;
    } else {
        jQuery('.discount_plan_name_error').hide().text('');
    }

    if (discount_plan_value == '') {
        jQuery('.discount_plan_value_error').show().text('Enter discount value');
        discountPlanValidation = false;
    } else if (parseFloat(discount_plan_value) < 0) {
        jQuery('.discount_plan_value_error').show().text('Discount cannot be less than 0');
        discountPlanValidation = false;
    } else if (parseFloat(discount_plan_value) > 100) {
        jQuery('.discount_plan_value_error').show().text('Discount cannot be greater than 100');
        discountPlanValidation = false;
    } else {
        jQuery('.discount_plan_value_error').hide().text('');
    }

    if (!productOriginalId) {
        jQuery('.discount_product_error').show().text('Select a product');
        discountPlanValidation = false;
    } else {
        jQuery('.discount_product_error').hide().text('');
    }

    if (!collectionOriginalId) {
        jQuery('.discount_collection_error').show().text('Select a collection');
        discountPlanValidation = false;
    } else {
        jQuery('.discount_collection_error').hide().text('');
    }

    if (!discountPlanValidation) {
        console.log('Validation failed');
        return false;
    }

    console.log('Save data');

    const discount_function_id = $('#discount_function_form').data('discount-function-id') ?? '';

    const discount_array = {
        discount_name: discount_plan_name,
        discount_value: discount_plan_value,
        discount_checked_value: discount_checked_value,
        discount_function_id: discount_function_id,
        discount_select_products_id: productOriginalId,
        discount_select_products_title: productTitle,
        discount_select_products_image: product_imageUrl,
        discount_select_collections_id: collectionOriginalId,
        discount_select_collections_title: collectionTitle,
        discount_select_collections_image: collectionImage
    };

    shopify.loading(true);
    const ajaxParameters = {
        method: "POST",
        dataValues: {
            action: "discount-raoul-code",
            data: JSON.stringify(discount_array)
        }
    };
    console.log(ajaxParameters, 'ajaxParameters');

    const ajaxResult = await AjaxCall(ajaxParameters);
    shopify.loading(false);

    if (ajaxResult.isError) {
        console.log('AJAX error:', ajaxResult.isError);
        return false;
    }

    console.log('AJAX success:', ajaxResult.message);
    location.reload();
});

// add product
jQuery("body").on('click', '.add_new_product_button', async function (e) {
    
    // console.log(SHOPIFY_DOMAIN_URL);

    let parentWrapperId = e.target.getAttribute('parent-id');
    let html_tag = '';
    // let pre_selected_products = {};
    if (typeof pre_selected_products != 'object') pre_selected_products = {};
    let proids = [];

    // Prepare pre-selected product IDs (if any)
    if (typeof already_added_products != 'undefined') {
        pre_selected_products = already_added_products;
        $.each(pre_selected_products, function (productId) {
            proids.push({ id: productId });
        });
    }

    // Open Shopify product picker
    const products_selection = await shopify.resourcePicker({
        type: 'product',
        multiple: false,
        selectionIds: proids,
        filter:{variants:false}
    });

    if (products_selection) {
        pre_selected_products = {};

        products_selection.selection.forEach(item => {
            let originalProductId = item.id; // GID
            let productId = item.id.replace(/[^\d]/g, '');

            // Save selected product ID
            pre_selected_products[item.id] = []; // variants list not needed

            let productImage = item.images?.[0]?.originalSrc || SHOPIFY_DOMAIN_URL + '/application/assets/images/no-image.png';

            // Create product-only HTML (no variants shown)
            html_tag = `
                <li class="Polaris-ResourceItem__ListItem" id="discount_display_product_${productId}">
                    <div class="Polaris-ResourceItem__ItemWrapper">
                        <div class="Polaris-ResourceItem Polaris-ResourceItem--selectable Polaris-discountProductItem">
                            <div class="Polaris-ResourceItem__Container">
                                <div class="Polaris-ResourceItem__Owned">
                                    <div class="Polaris-ResourceItem__Media">
                                        <span role="img" class="Polaris-Avatar Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage">
                                            <img src="${productImage}" class="Polaris-Avatar__Image" alt="Product Image" role="presentation">
                                        </span>
                                    </div>
                                </div>
                                <div class="Polaris-ResourceItem__Content">
                                    <h3>
                                        <span class="Polaris-TextStyle--variationStrong">${item.title}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <button type="button" original-product-id="${originalProductId}" product-id="${productId}" class="Polaris-Tag__Button Polaris-discountRemoveProductButton" aria-label="Remove product">
                            <span class="Polaris-Icon">
                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                    <path d="m11.414 10 4.293-4.293a.999.999 0 1 0-1.414-1.414L10 8.586 5.707 4.293a.999.999 0 1 0-1.414 1.414L8.586 10l-4.293 4.293a.999.999 0 1 0 1.414 1.414L10 11.414l4.293 4.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </li>
            `;
        });

        already_added_products = pre_selected_products;

        // Render selected products list
        if ($('#discount-section-products-show-list').length > 0) {
            // $('#discount-section-products-show-list').html(html_tag);
            $('#discount-section-products-show-list').html(html_tag);

        } else {
            console.warn('Target UL not found');
        }
    }
});

// add collection 
jQuery("body").on('click', '.add_new_discount_collection', async function (e) {
    
    let html_tag = '';
    let pre_selected_collections = {};
    let collectionIds = [];

    // Ensure the global tracker exists
    window.already_added_collections = window.already_added_collections || {};

    // Pre-select existing collection if available (only one allowed)
    const selectedKeys = Object.keys(window.already_added_collections);
    if (selectedKeys.length > 0) {
        collectionIds.push({ id: selectedKeys[0] });
    }

    // Open Shopify collection picker
    const collections_selection = await shopify.resourcePicker({
        type: 'collection',
        multiple: false, // Single selection only
        selectionIds: collectionIds
    });

    if (collections_selection && collections_selection.selection.length > 0) {
        const item = collections_selection.selection[0]; // Only one item
        const originalCollectionId = item.id;
        const collectionId = item.id.replace(/[^\d]/g, '');
        const collectionTitle = item.title;
        const collectionImage = item.image?.originalSrc || SHOPIFY_DOMAIN_URL + '/application/assets/images/no-image.png';

        // Save the selected collection
        pre_selected_collections[item.id] = true;
        window.already_added_collections = pre_selected_collections;

        // Create the HTML to display the selected collection
        html_tag = `
            <li class="Polaris-ResourceItem__ListItem" id="discount_display_collection_${collectionId}">
                <div class="Polaris-ResourceItem__ItemWrapper">
                    <div class="Polaris-ResourceItem Polaris-ResourceItem--selectable Polaris-discountProductItem">
                        <div class="Polaris-ResourceItem__Container">
                            <div class="Polaris-ResourceItem__Owned">
                                <div class="Polaris-ResourceItem__Media">
                                    <span role="img" class="Polaris-Avatar Polaris-Avatar--sizeMedium Polaris-Avatar--hasImage">
                                        <img src="${collectionImage}" class="Polaris-Avatar__Image" alt="Collection Image" role="presentation">
                                    </span>
                                </div>
                            </div>
                            <div class="Polaris-ResourceItem__Content">
                                <h3>
                                    <span class="Polaris-TextStyle--variationStrong">${collectionTitle}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <button type="button" collection-id="${collectionId}" original-collection-id="${originalCollectionId}" class="Polaris-Tag__Button Polaris-discountRemoveProductButton" aria-label="Remove collection">
                        <span class="Polaris-Icon">
                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false">
                                <path d="m11.414 10 4.293-4.293a.999.999 0 1 0-1.414-1.414L10 8.586 5.707 4.293a.999.999 0 1 0-1.414 1.414L8.586 10l-4.293 4.293a.999.999 0 1 0 1.414 1.414L10 11.414l4.293 4.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </li>
        `;

        // Replace the previous collection with the new one in the list
        $('#discount-section-collection-show-list').html(html_tag);
    } else {
        console.warn('No collection selected or picker was cancelled.');
    }
});

// Remove the parent product and collection <li> of the clicked button
jQuery("body").on('click', '.Polaris-discountRemoveProductButton', function () {
    
    $(this).closest('li.Polaris-ResourceItem__ListItem').remove();

    if ($('#discount-section-products-show-list li').length == 0) {
        // $('.selected-discount-products-list').addClass('display-hide-label');
    }
});

