
if (!window.location.pathname.includes("/your-subscriptions")) {
    var SHOPIFY_DOMAIN_URL = "https://yulanda-unpanelled-superzealously.ngrok-free.dev";
    var random_number = (Math.random() + 1).toString(36).substring(7);
    var accountPageHandle,
        productpagesetting,
        app_status,
        ajaxResponseData,
        currentProductVariantsObj,
        currentProductID,
        CurrentVariantObjectIndex,
        CurrentVariant,
        selling_plans,
        variantID = "",
        timezone,
        form_type = "create",
        widget_template,
        One_Time_Purchase_html,
        Pay_Per_Delivery_html,
        Prepaid_html,
        selling_planId_index,
        plan_type,
        show_cut_original_price = "",
        hidden_selling_plan_name = "No",
        total_payable_text,
        total_discount_value = "",
        discountRibbonStyle,
        per_quantityPrice = "",
        billingCycle,
        deliveryCycle,
        total_saved_discount_value,
        loaded_variant = "",
        plan_description,
        planDescription,
        subscription_detail_content,
        theme_block_support,
        get_forms,
        active_purchase_option,
        secondary_purchase_option,
        all_plans_single_discount = "",
        all_plans_options_list = "",
        checkout_charge_amount,
        selected_plan_type,
        day_frequency_text,
        week_frequency_text,
        month_frequency_text,
        year_frequency_text,
        selected_background_color,
        total_payable_amount_text,
        discount_background_color,
        discount_text_color,
        widget_background_color,
        save_discount_label,
        total_saved_discount_label,
        widget_template,
        subscription_detail,
        orders_text,
        then_text,
        on_first_text,
        pd_selling_plans_label,
        subscription_options_text,
        primary_price_color,
        secondary_price_color,
        options_bg_color1,
        options_bg_color2,
        subscription_widget_block = "";
    var sd_shopCurrency = Shopify.currency.active;
    var store = Shopify.shop;
    ajaxurl =
        SHOPIFY_DOMAIN_URL +
        "/application/controller/frontend_ajxhandler.php?store=" +
        store;
    params = new URLSearchParams(window.location.search);

    var priceSelectors = [
        ".product-price",
        ".price.price--large",
        ".product__price",
        ".product__price--holder",
        ".yv-product-price",
        ".product-single__prices",
        ".price-area",
    ];
    get_forms = document.body.querySelectorAll('form[action*="/cart/add"]');
    var currentUrl = window.location.href;
    var lastIndex = currentUrl.lastIndexOf('/');
    var accountPage = currentUrl.substring(lastIndex + 1);
    let membership_product_description = freeshipping_option = get_cart_forms = selling_plan_option = onload_description_text = onload_description = selling_plan_description = '';
    var product_widget_settings, member_groups_perks_product, plans_widget_settings, getEarlySaleData, getCountDownData, customer_data, drawer_setting, getBirthdayRecords = '';

    let checkoutBtn = document.querySelectorAll('button[name="checkout"], button[form="CartDrawer-Form"], input[name="checkout"], #checkout');
    var obj, freeShippingObj, discountCode, freeShippingCode, productAction;
    var sale_no_of_sale_days = 0;
    var CurrentVariant;

    // console.log('SDMembershipConfig', SDMembershipConfig);
    let lengthOfMetaObject = Object.keys(metaObjectDataJson?.sd_memberships_metaobject_definition ?? {}).length;
    // let lengthOfcustomerdata = Object.keys(customerMetaDataJson?.sd_customers_metaobject_definition ?? {}).length;
    //get currency code using currency symbol

    var runAjax = 'yes';
    var runCustomerAjax = 'yes';

    console.log(metaObjectDataJson, 'kawal');

    // if (lengthOfcustomerdata >= 1) {
    //     var getCustomerSaleDetails = customerMetaDataJson.sd_customers_metaobject_definition.membership_customer_data;
    //     customer_data = customerMetaDataJson.sd_customers_metaobject_definition.customerData;
    //     console.log(customer_data,'customer_datacustomer_datacustomer_data')
    //     // runCustomerAjax = 'no'
    // }
    //  else {
    var getCustomerSaleDetails;
    // }
    let tempPageUrl = accountPage;
    let temp_index = tempPageUrl.indexOf('?');
    tempPageUrl = temp_index !== -1 ? tempPageUrl.substring(0, temp_index) : tempPageUrl;

    if (lengthOfMetaObject >= 1) {
        // console.log('metaobject true, ajax false');
        var product_widget_settings = metaObjectDataJson.sd_memberships_metaobject_definition.product_widget_settings;
        var drawer_setting = metaObjectDataJson.sd_memberships_metaobject_definition.drawer_setting;
        var member_groups_perks_product = metaObjectDataJson.sd_memberships_metaobject_definition.member_groups_perks_product;
        var plans_widget_settings = metaObjectDataJson.sd_memberships_metaobject_definition.plans_widget_settings;
        var member_group_details = metaObjectDataJson.sd_memberships_metaobject_definition.member_group_details;
        var getCountDownData = metaObjectDataJson.sd_memberships_metaobject_definition.getCountDownData;
        var getEarlySaleData = metaObjectDataJson.sd_memberships_metaobject_definition.early_sale_access;
        var getBirthdayRecords = metaObjectDataJson.sd_memberships_metaobject_definition.getBirthdayRecords;
        var birthdayFormData = metaObjectDataJson.sd_memberships_metaobject_definition.birthdayFormData;
        runAjax = 'no';
    } else {
        // console.log('metaobject false, ajax true');
        var obj, product_widget_settings, drawer_setting, member_groups_perks_product, plans_widget_settings, member_group_details, getCountDownData;
    }


    console.log('runAjax', getEarlySaleData);

    var obj, plans_widget_settings, member_groups_perks_product, member_group_details, drawer_setting, product_widget_settings, freeShippingObj, discountCode;

    function selling_plan_select() {
        get_all_selling_plan_name = document.body.querySelectorAll('form[action*="/cart/add"] input[name="selling_plan"]');
        selected_selling_plan_id = document.querySelector('input[name="sd_selling_plan"]:checked').value;
        selected_price = document.querySelector('input[name="sd_selling_plan"]:checked').getAttribute('selling_plan_price');
        selling_plan_description = document.querySelector('input[name="sd_selling_plan"]:checked').getAttribute('selling_plan_description');
        if (selling_plan_description == null || selling_plan_description == 'null') {
            document.querySelector('.sd_tier_description').innerHTML = '';
            document.querySelector('.sd_tier_description_heading').innerHTML = '';
            document.querySelector('.sd_tier_description_wrapper').style.background = '';
            document.querySelector('.sd_tier_description_wrapper').style.padding = '0';
            document.querySelector('.sd_tier_description_wrapper').style.margin = '0';
        } else {
            document.querySelector('.sd_tier_description').innerHTML = selling_plan_description;
            let element = document.querySelector('.sd_tier_description_heading');
            if (element) {
                document.querySelector('.sd_tier_description_heading').innerHTML = product_widget_settings?.description_text ?? `Description`;
            }
            document.querySelector('.sd_tier_description_wrapper').style.background = "linear-gradient(to right," + (product_widget_settings?.description_background_color1 ?? '#ffffff') + ", " + (product_widget_settings?.description_background_color2 ?? '#ffffff') + ")";
            document.querySelector('.sd_tier_description_wrapper').style.borderRadius = (product_widget_settings?.border_radius ?? '1') + 'px';
            document.querySelector('.sd_tier_description_wrapper').style.padding = '10px';
            document.querySelector('.sd_tier_description_wrapper').style.margin = '10px';
        }

        let all_price_selectors = priceSelectors.join();
        document.querySelector(all_price_selectors).innerHTML = selected_price;
        get_all_selling_plan_name.forEach(function (element) {
            element.value = selected_selling_plan_id;
        });
        checkActivePlan();
    }


    if (pageType === 'customers/login') {
        let anchor = document.createElement("a");
        anchor.setAttribute("name", "membershipAnchor");
        anchor.setAttribute("id", "sd_purchase_membership");
        anchor.setAttribute("class", "sd_purchase-membership");
        anchor.href = "https://www.shopcasabianca.com/pages/trade-membership-registration";
        anchor.textContent = "Join our members-only Trade Program";
        // anchor.style.marginTop = '-18px';
        let form = document.getElementById("customer_login");
        form.appendChild(anchor);
        setTimeout(() => {
            let customBtnCondtion = document.querySelectorAll(".wsaio_form");
            if (customBtnCondtion) {
                // console.log('lllllllllllllllllllll', customBtnCondtion);
                customBtnCondtion.forEach(el => {
                    el.remove()
                });
            }
        }, 5000);
    }







    function checkMembershipWidgetCreate() {
        console.log('first1')
        CurrentVariantObjectIndex = "VID_" + variantID;
        CurrentVariant = SDMembershipConfig['variant'][CurrentVariantObjectIndex];
        if (typeof CurrentVariant !== "undefined") {
            console.log('first11')

            if (CurrentVariant.variant_available) {
                console.log('first111')

                create_membership_widget(CurrentVariant);
            }
        }
    }



    function removeElement(className) {
        var selection = document.querySelector('.' + className) !== null;
        if (selection) {
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++) {
                elements[i].parentNode.removeChild(elements[i]);
            }
        }
    }



    function checkActivePlan() {
        const planOptions = document.querySelectorAll('.sd_plan_option');
        planOptions.forEach(option => {
            option.classList.remove('activemethod');
            option.removeAttribute('style');
            const radioInput = option.querySelector('input[type="radio"]');
            if (radioInput) {
                radioInput.removeAttribute('style');
            }
        });
        const radioButtons = document.getElementsByName('sd_selling_plan');
        radioButtons.forEach(radio => {
            if (radio.checked) {
                radio.closest('.sd_plan_option').classList.add('activemethod');
            }
        });
        active_purchase_option = document.querySelector('.sd_plan_option.activemethod');
        if (active_purchase_option) {
            active_purchase_option_radio = document.querySelector('.sd_plan_option.activemethod input[type=radio]:checked');
            active_purchase_option_radio.style.borderColor = product_widget_settings?.radio_button_color ?? '#000000';
            active_purchase_option.style.borderRadius = (product_widget_settings?.border_radius ?? '1') + 'px';
            active_purchase_option.style.background = "linear-gradient(to right," + (product_widget_settings?.active_option_bgColor1 ?? '#ffffff') + ", " + (product_widget_settings?.active_option_bgColor2 ?? '#ffffff') + ")";
        } else {
        }
    }

    function create_membership_widget(CurrentVariant) {

        console.log(CurrentVariant, 'CurrentVariant');

        onload_description_text = '';
        onload_description = '';
        removeElement('sd_membership_widget_wrapper');
        if ((Object.keys(CurrentVariant['allocations']['selling_plans']['list'])).length > 0) {
            console.log('first2')

            let get_quantity_selector = document.querySelectorAll('input[name="quantity"]');
            get_quantity_selector.forEach(element => {
                element.parentNode.style.display = 'none';
            });
            const replacement_delivery_option_text = {
                "day": product_widget_settings?.day_text ?? 'day',
                "week": product_widget_settings?.week_text ?? 'week',
                "month": product_widget_settings?.month_text ?? 'month',
                "year": product_widget_settings?.year_text ?? 'year',
            };
            member_plans = `<div class="sd_membership_widget_wrapper">
                    <fieldset style="border:2px solid ${product_widget_settings?.widget_outline_color ?? '#000000'}; background: linear-gradient(to right, ${product_widget_settings?.background_color1 ?? '#fffafa0c'},  ${product_widget_settings?.background_color2 ?? '#ffffff'}); border-radius:${product_widget_settings?.border_radius ?? 1}px;">
                    <legend style="color:${product_widget_settings?.heading_text_color ?? '#000000'}">${product_widget_settings?.heading_text ?? 'Membership Plansyy'}</legend>
                        <div class="sd_membership_wrapper">
                        <div class="sd_membership_wrapper">
                            <div class="sd_membership_radio_wrapper" style="color:${product_widget_settings?.text_color ?? '#000000'};">
                            <div class="sd_membership_amount_wrapper">`;
            Object.keys(CurrentVariant['allocations']['selling_plans']['list']).forEach(function (key, value) {
                selling_plan_product_data = CurrentVariant['allocations']['selling_plans']['list'][key];
                if (value == 0 && (selling_plan_product_data.plan_description != null)) {
                    onload_description_text = product_widget_settings?.description_text ?? `Description`;
                    onload_description = selling_plan_product_data.plan_description;
                }
                selling_plan_data = SDMembershipConfig['selling_plans']['list'][key];
                const pattern = new RegExp(Object.keys(replacement_delivery_option_text).join("|"), "g");
                const selling_plan_option_value = (selling_plan_data.options.value).replace(pattern, matched => replacement_delivery_option_text[matched]);
                member_plans += `<div class="sd_plan_option ${value == 0 ? 'activemethod' : ''}" style="display: block;">
                            <div class="purchase-label-price">
                                <label for="sd_selling_plan_${selling_plan_product_data.selling_plan_id}">
                                <input type="radio" name="sd_selling_plan" id="sd_selling_plan_${selling_plan_product_data.selling_plan_id}" value="${selling_plan_product_data.selling_plan_id}"  ${value == 0 ? 'checked' : ''} selling_plan_price = "${selling_plan_product_data.pa_selling_plan_allocation_price}" selling_plan_description = "${selling_plan_product_data.plan_description}"  onclick="selling_plan_select()">${product_widget_settings?.charge_every_text ?? `charge every `} ${selling_plan_option_value}</label>
                                <span style="color: ${product_widget_settings?.text_color ?? `#000000`}" class="sd_membership_amount">` + selling_plan_product_data.pa_selling_plan_allocation_price + `</span>
                            </div>
                        </div>`;
            });
            member_plans += `</div>
                            </div>
                        </div>
                        </div>
                        </fieldset>

                        <fieldset style="border:2px solid ${product_widget_settings?.widget_outline_color ?? '#000000'}; background: linear-gradient(to right, ${product_widget_settings?.background_color1 ?? '#fffafa0c'},  ${product_widget_settings?.background_color2 ?? '#ffffff'}); border-radius:${product_widget_settings?.border_radius ?? 1}px;">
                            <legend style="color:${product_widget_settings?.heading_text_color ?? '#000000'}">${product_widget_settings?.description_text ?? 'Discription'}</legend>
                            <div class="sd_tier_description_wrapper">
                                <p class="sd_tier_description" style="color:${product_widget_settings?.text_color ?? '#000000'}">` + onload_description + `</p>
                            </div>
                        </fieldset>


                        <div class="sd_membershipPrc sd_description_tooltip">
                            <div class="sd_membership_privacy"><div class="sd_recurringMsg">
                                <img src="https://membershipsclub.com/membership/public/assets/images/membership_detail.png">
                                ${product_widget_settings?.subscription_details_text ?? 'Membership Details'}
                                </div>
                                <div class="Polaris-PositionedOverlay">
                                    <div class="Polaris-Tooltip-TooltipOverlay" data-polaris-layer="true">
                                        <div id="PolarisTooltipContent2" role="tooltip" class="Polaris-Tooltip-TooltipOverlay__Content">${product_widget_settings?.subscription_details ?? 'Have complete control of your Membership. Skip, pause or cancel membership any time in accordance with the membership policy and your needs.'}</div>
                                    </div>
                                </div></div>
                        </div>
                    </div>`;
            let check_block_wrapper = document.getElementById('advanced-membership-block-wrapper');

            // console.log(check_block_wrapper,'check_block_wrapper');
            // return false;
            theme_block_supported = true;

            if (theme_block_supported) {
                if (check_block_wrapper) {
                    check_block_wrapper.innerHTML = member_plans;
                    if (document.body.querySelector('form[action*="/cart/add"] .sd_membership_widget_wrapper')) {
                    } else {
                        get_forms.forEach(function (element) {
                            var existingInput = element.querySelector('input[name="selling_plan"]');
                            if (!existingInput) {
                                element.insertAdjacentHTML('afterbegin', '<input type="hidden" name="selling_plan" value="">');
                            }
                        });
                        hidden_selling_plan_name = 'Yes';
                    }
                } else {
                    console.log("product block not added");
                }
            } else {
                get_forms.forEach(function (element) {
                    var add_element_selector = '';
                    let submit_button = element.querySelector("[name='add']" + add_element_selector);
                    if (submit_button != null) {
                        submit_button.insertAdjacentHTML('beforebegin', member_plans);
                    }
                });
            }
            const descriptionWrapper = document.querySelector('.sd_tier_description_wrapper');
            if (descriptionWrapper) {
                if (onload_description != '') {
                    document.querySelector('.sd_tier_description_wrapper').style.background = "linear-gradient(to right," + (product_widget_settings?.description_background_color1 ?? '#ffffff') + ", " + (product_widget_settings?.description_background_color2 ?? '#ffffff') + ")";
                    document.querySelector('.sd_tier_description_wrapper').style.borderRadius = (product_widget_settings?.border_radius ?? '1') + 'px';
                    document.querySelector('.sd_tier_description_wrapper').style.padding = '10px';
                    document.querySelector('.sd_tier_description_wrapper').style.margin = '10px';
                }
            }

            selected_selling_plan = document.querySelector('input[name="sd_selling_plan"]:checked').value;
            get_forms.forEach(function (element) {
                var existingInput = element.querySelector('input[name="selling_plan"]');
                var existingInputAtrr = element.querySelector('input[name="properties[_plan_type]"]');
                if (existingInput) {
                    existingInput.value = selected_selling_plan;
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selling_plan';
                    hiddenInput.value = selected_selling_plan;
                    element.insertBefore(hiddenInput, element.firstChild);
                }
                if (existingInputAtrr) {
                    existingInputAtrr.value = 'membership'
                } else {
                    element.insertAdjacentHTML('afterbegin', `<input type="hidden" name="properties[_plan_type]" value="membership">`);
                }
            });

            hidden_selling_plan_name = 'Yes';
            checkActivePlan();
            var abcDivs = document.querySelectorAll('.sd_plan_option');
            abcDivs.forEach(function (abcDiv) {
                abcDiv.addEventListener('click', function () {
                    var radioBtn = abcDiv.querySelector('.sd_plan_option input[type="radio"]');
                    if (radioBtn) {
                        var radioBtnId = radioBtn.id;
                        document.getElementById(radioBtnId).click();
                    }
                });
            });

        } else {
            console.log('first3')

            let get_quantity_selector = document.querySelectorAll('input[name="quantity"]');
            get_quantity_selector.forEach(element => {
                element.parentNode.style.display = 'flex';
            });
        }
    }
    function hide_quantity_and_addToCart(arr) {
        console.log(CurrentVariant, 'CurrentVariant')
        var elementPos = arr.map(function (x) { return x.id; }).indexOf(parseInt(variantID));
        var objectFound = arr[elementPos];
        if ((Object.keys(CurrentVariant['allocations']['selling_plans']['list']).length > 0) && (typeof objectFound !== "undefined")) {
            let addButton = document.querySelector('button[name="add"]');
            if (addButton) {
                addButton.parentNode.style.display = 'none';
                let sd_already_message = document.querySelector('.sd_item_cart');
                if (!sd_already_message) {
                    let span = document.createElement('span');
                    span.className = 'sd_item_cart';
                    span.textContent = 'This item is already in the cart.';
                    addButton.parentNode.insertAdjacentElement('afterend', span);
                }
            }
        } else {
            let addButton = document.querySelector('button[name="add"]');
            if (addButton) {
                addButton.parentNode.style.display = 'block';
                let sd_already_message = document.querySelector('.sd_item_cart');
                if (sd_already_message) {
                    sd_already_message.remove();
                }
            }
        }
    }

    function hide_quantitySelector_cartPage(arr) {
        arr.map(async (variantData, index) => {
            if (variantData.selling_plan_allocation) {
                selling_plan_name = variantData.selling_plan_allocation.selling_plan.name;
                if (variantData.sku != null && (('sd-membership-plan:' + selling_plan_name).includes(variantData.sku + '-')) && (document.getElementsByName("updates[]")[index])) {
                    document.getElementsByName("updates[]")[
                        index
                    ].parentElement.style.display = "none";
                }
            }
        });
    }


    async function getCartData() {
        try {
            let cartApi = await fetch("/cart.json");
            let cartData = await cartApi.json();
            return cartData;
        } catch (error) {
        }
    }

    var freeShippingCode;
    // var finalData
    var productAction;
    if (runAjax == 'no' && runCustomerAjax == 'yes') {
        // getCustomerData()
    }
    async function getCustomerData() {
        const formData = new URLSearchParams();
        formData.append("store", store);
        formData.append("customer_id", customer_id);
        formData.append("action", "get-customer-data");

        const response = await fetch(ajaxurl, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: formData.toString(),
        });

        const result = await response.json();
        return result.customer_data;
    }

    // console.log(customer_data)
    async function crafthubDiscount() {
        if (window.location.pathname.includes("/cart") && app_status == 1) {
            if (store == 'thediyart1.myshopify.com' || store == 'test-store-phoenixt.myshopify.com') {
                const data = await getCustomerData();
                console.log(data, 'kkjk')
                if (data.length != 0) {
                    const drawer_data = data[0];
                    console.log(drawer_data, 'jjjj')
                    if (
                        drawer_data.discounted_product_collection_code != null && drawer_data.discounted_product_collection_checked_value == 1
                    ) {

                        document.querySelector("#checkout").addEventListener("click",(e)=> {
                            e.stopPropagation()
                            console.log("checkout click")
                            redirectForDiscount(drawer_data.discounted_product_collection_code);
                        })

                        
                    }
                }
            }
        }
    }
    crafthubDiscount()

    function applyDiscountDrawer(customer_data) {

        console.log(customer_data, 'hhhhhhhhhhhhhhhhhhhhhhhh')
        if (customer_data.length != 0) {
            let discountCodeLink = document.createElement('button');
            discountCodeLink.id = "openButton"
            discountCodeLink.innerText = drawer_setting.discount_button_text
            discountCodeLink.style.cssText = `background:` + drawer_setting.button_bg_color + `;
                                        color:`+ drawer_setting.button_text_color + `;
                                        cursor:pointer;
                                        border-radius:`+ drawer_setting.discount_button_radius + `px;`
            let existingButton = document.querySelectorAll('#openButton')
            checkoutBtn.forEach((bt) => {
                if (existingButton.length == 0) {
                    bt.insertAdjacentElement('beforebegin', discountCodeLink)
                }
            })
            let getBody = document.querySelector('body');
            let bodyDiv = document.createElement('div');
            var sideBarContentHtml = '';
            sideBarContentHtml += `
        <div class="sidebar sd_sidebar_drawer" id="sidebar" style="background:`+ drawer_setting.drawer_background_color + `;">
          <h2 style="color:`+ drawer_setting.heading_text_color + `;">` + drawer_setting.drawer_heading + `</h2>
          <div class="close-button" id="closeButton" style="color:`+ drawer_setting.cross_button_color + `;">&times;</div>
          <ul id="sd_all_coupon_content">
          </ul>`
            sideBarContentHtml += `<div id="sd_applied_code_error"></div></div>`
            let existingSidebar = document.querySelectorAll('#sidebar');
            if (existingSidebar.length == 0) {
                bodyDiv.innerHTML = sideBarContentHtml
            }
            getBody.insertAdjacentElement('afterbegin', bodyDiv)
            const openButton = document.getElementById("openButton");
            const closeButton = document.getElementById("closeButton");
            const sidebar = document.getElementById("sidebar");
            const sideBarContent = document.getElementById("sd_all_coupon_content");
            let freeShippingText = drawer_setting.free_shipping_text
            let percentOffText = drawer_setting.percent_off_text
            openButton.addEventListener("click", (e) => {
                e.preventDefault();
                closeButton.style.display = 'block'
                var sideBarHtml = '';
                sidebar.style.right = "0"
                var percentOffTextFinal = ''
                var discountCodeCondition = ''
                var freeShippingTextFinal = ''
                var freeShippingCondition = ''
                var shopify_currency_rate = Shopify.currency;
                var shopify_conversion_rate = shopify_currency_rate.rate;
                customer_data.map((drawer_data) => {
                    if (drawer_data.discounted_product_collection_code != null && drawer_data.discounted_product_collection_checked_value == 1) {
                        if (percentOffText.includes('{discount_name}')) {
                            percentOffTextFinal = percentOffText.replaceAll('{discount_name}', '<b>' + drawer_data.discounted_product_collection_code + '</b>').replaceAll('{discount_value}', '<b>' + drawer_data.discounted_product_collection_percentageoff + '%</b>')
                        }
                        if (drawer_data.discounted_product_collection_type == 'C') {
                            let collectionConText = drawer_setting.percent_off_collection_text
                            discountCodeCondition = collectionConText.replace('{collection_name}', '<b>' + drawer_data.discounted__collection_title + '</b>')
                        } else if (drawer_data.discounted_product_collection_type == 'P') {
                            let productConText = drawer_setting.percent_off_product_text
                            discountCodeCondition = productConText.replace('{product_name}', '<b>' + drawer_data.discounted__product_title + '</b>')
                        }
                        sideBarHtml += `<li><div class="sd_discount_card_main sd_discount_code_bar" style="background-color: ` + drawer_setting.code_bg_color + `;border:2px solid` + drawer_setting.code_bar_border_color + `;">
                    <div class="sd_discount_card_content">
                    <h3 class="sd_discount_code_bar" style="color:`+ drawer_setting.code_bar_color + `;">` + drawer_data.discounted_product_collection_code + `
                         <button id="copied_code_value" class="sd_copy_code_btn" value="`+ drawer_data.discounted_product_collection_code + `" style="border:none;background-color:unset;" onclick="copy(this)" ><?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                         <svg class="sd_copied_icon" fill="`+ drawer_setting.copied_button_color + `" width="20px" height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-e</title><path d="M408,480H184a72,72,0,0,1-72-72V184a72,72,0,0,1,72-72H408a72,72,0,0,1,72,72V408A72,72,0,0,1,408,480Z"/><path d="M160,80H395.88A72.12,72.12,0,0,0,328,32H104a72,72,0,0,0-72,72V328a72.12,72.12,0,0,0,48,67.88V160A80,80,0,0,1,160,80Z"/></svg></button>
                    </h3>
                     <p class="sd_discount_code_bar sd_free_shipping_text" style="color:`+ drawer_setting.code_bar_color + `;">` + percentOffTextFinal + `</p>
                     <p class="sd_discount_code_bar sd_free_shipping_text" style="color:`+ drawer_setting.code_bar_color + `;">` + discountCodeCondition + `</p>
                    </div>
                     <div class="sd_discount_coupon_code_main">
                         <div class="sd_discount_coupon_code sd_inner_code_bar" style="background-color:`+ drawer_setting.code_bar_inner_bg_color + `;">
                             <span class="scissors" style="color:`+ drawer_setting.scissor_color + `;">✂</span>
                             <button class="code sd_inner_code_bar" disabled="" style="color:`+ drawer_setting.code_bar_inner_color + `;">` + drawer_data.discounted_product_collection_percentageoff + `%OFF</button>
                         </div>
                     </div>
                 </div></li>`;
                    }
                    if (drawer_data.free_shipping_code && drawer_data.free_shipping_checked_value == 1) {
                        if (freeShippingText.includes('{free_shipping}')) {
                            freeShippingTextFinal = freeShippingText.replace('{free_shipping}', '<b>' + drawer_data.free_shipping_code + '</b>')
                        }
                        if (drawer_data.freeshipping_selected_value == 'min_purchase_amount') {
                            let minimumAmountCon = drawer_setting.free_shipping_minimum_amount;
                            freeShippingCondition = minimumAmountCon.replace('{minimum_amount}', ' <b>' + currencyES6FormatMembership(shopify_conversion_rate * (drawer_data.min_purchase_amount_value)) + '</b>')
                        } else if (drawer_data.freeshipping_selected_value == 'min_quantity_items') {
                            let minimumQuantityCon = drawer_setting.free_shipping_minimum_quantity;
                            freeShippingCondition = minimumQuantityCon.replace('{minimum_quantity}', '<b>' + drawer_data.min_quantity_items + '</b>')
                        }
                        sideBarHtml += `<li><div class="sd_discount_card_main sd_discount_code_bar" style="background-color: ` + drawer_setting.code_bg_color + `;border:2px solid` + drawer_setting.code_bar_border_color + `;">
                        <div class="sd_discount_card_content">
                        <h3 class="sd_discount_code_bar" style="color:`+ drawer_setting.code_bar_color + `;">` + drawer_data.free_shipping_code + `
                             <button id="copied_code_value"  class="sd_copy_code_btn" value="`+ drawer_data.free_shipping_code + `" style="border:none;background-color:unset;" onclick="copy(this)"><?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                             <svg class="sd_copied_icon" fill="`+ drawer_setting.copied_button_color + `" width="20px" height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-e</title><path d="M408,480H184a72,72,0,0,1-72-72V184a72,72,0,0,1,72-72H408a72,72,0,0,1,72,72V408A72,72,0,0,1,408,480Z"/><path d="M160,80H395.88A72.12,72.12,0,0,0,328,32H104a72,72,0,0,0-72,72V328a72.12,72.12,0,0,0,48,67.88V160A80,80,0,0,1,160,80Z"/></svg></button>
                        </h3>
                         <p class="sd_discount_code_bar sd_free_shipping_text" style="color:`+ drawer_setting.code_bar_color + `;">` + freeShippingTextFinal + `</p>
                         <p class="sd_discount_code_bar sd_free_shipping_text" style="color:`+ drawer_setting.code_bar_color + `;">` + freeShippingCondition + `</p>
                        </div>
                         <div class="sd_discount_coupon_code_main">
                             <div class="sd_discount_coupon_code sd_inner_code_bar" style="background-color:`+ drawer_setting.code_bar_inner_bg_color + `;">
                                 <span class="scissors" style="color:`+ drawer_setting.scissor_color + `;">✂</span>
                                 <button class="code sd_inner_code_bar" disabled="" style="color:`+ drawer_setting.code_bar_inner_color + `;">FREE</button>
                             </div>
                         </div>
                     </div></li>`;
                    }

                })
                sideBarContent.innerHTML = sideBarHtml
            });
            closeButton.addEventListener("click", () => {
                sidebar.style.right = '-500px'
                sideBarContent.innerHTML = '<li></li>';
                closeButton.style.display = 'none';
                let code_error_element = document.getElementById('sd_applied_code_error');
                code_error_element.innerHTML = '';
            });
            open_cart_drawer();
        }
    }

    open_cart_drawer();


    function open_cart_drawer() {
        let addToCart = document.querySelectorAll('input[name="add"], button[name="add"],#cart-icon-bubble,form[action="/cart"] button,.quantity__button, input[name="plus"], input[name="minus"]')
        addToCart.forEach(function (e) {
            e.addEventListener('click', function () {
                setTimeout(async function () {
                    if (checkoutBtn != undefined) {

                        const customer_data = await getCustomerData()
                        console.log(customer_data, 'kdockovofvj')
                        applyDiscountDrawer(customer_data)

                        const checkOutButton = document.querySelector('button[name="checkout"]');


                        // if (checkOutButton) {
                        //     checkOutButton.addEventListener("click", (e) => {
                        //         e.preventDefault();

                        //         if (store == 'thediyart1.myshopify.com' || store == 'test-store-phoenixt.myshopify.com') {
                        //             setTimeout(() => {
                        //                 redirectForDiscount();
                        //             }, 2000);
                        //         } else {
                        //             // Allow normal checkout for other stores
                        //             // document.querySelector('#cart-notification-form').submit();
                        //         }
                        //     });
                        // }


                        if (checkOutButton) {

                            if (store == 'thediyart1.myshopify.com' || store == 'test-store-phoenixt.myshopify.com') {
                                checkOutButton.addEventListener("click", async (e) => {
                                    // e.preventDefault();
                                    e.stopPropagation()
                                    const dataCustomer = await getCustomerData();
                                    console.log(dataCustomer, 'RRRRRRRRRR');

                                    if (dataCustomer.length == 0) return;

                                    const drawer_data = dataCustomer[0];
                                    console.log(drawer_data, 'Customer Data MMMMMM');

                                    const discountCode = drawer_data.discounted_product_collection_code;

                                    if (discountCode && drawer_data.discounted_product_collection_checked_value == 1) {

                                        await redirectForDiscount(discountCode);
                                    } else {

                                        document.querySelector('#cart-notification-form').submit();
                                    }


                                });
                            } else {
                                // Other stores – normal submit
                                // document.querySelector('#cart-notification-form').submit();
                            }
                        }
                    }
                }, 2000)
            })
        })
    }

    // Rediredt for discount



    async function redirectForDiscount(discountCode) {
        try {
            const cartDataDetails = await getCartData();

            if (discountCode && cartDataDetails?.items?.length > 0) {
                let cartItems = [];

                cartDataDetails.items.forEach(item => {
                    const cart_variant_id = item.id;
                    const cart_quantity = item.quantity;

                    cartItems.push(`${cart_variant_id}:${cart_quantity}`);
                    console.log("Product ID:", cart_variant_id);
                    console.log("Quantity:", cart_quantity);
                });

                const cartQueryString = cartItems.join(',');
                const redirectUrl = `https://${store}/cart/${cartQueryString}?discount=${discountCode}`;

                window.location.href = redirectUrl;
            } else {
                console.log("Cart data not found.");
            }
        } catch (err) {
            console.error("Redirect failed:", err);
        }
    }


    function copy(element) {
        let codeValue = element.value;
        element.innerHTML = `
        <?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
        <svg width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
            <title>cross-circle</title>
            <desc>Created with Sketch Beta.</desc>
            <defs>
        </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-570.000000, -1089.000000)" fill="#000000">
                    <path d="M591.657,1109.24 C592.048,1109.63 592.048,1110.27 591.657,1110.66 C591.267,1111.05 590.633,1111.05 590.242,1110.66 L586.006,1106.42 L581.74,1110.69 C581.346,1111.08 580.708,1111.08 580.314,1110.69 C579.921,1110.29 579.921,1109.65 580.314,1109.26 L584.58,1104.99 L580.344,1100.76 C579.953,1100.37 579.953,1099.73 580.344,1099.34 C580.733,1098.95 581.367,1098.95 581.758,1099.34 L585.994,1103.58 L590.292,1099.28 C590.686,1098.89 591.323,1098.89 591.717,1099.28 C592.11,1099.68 592.11,1100.31 591.717,1100.71 L587.42,1105.01 L591.657,1109.24 L591.657,1109.24 Z M586,1089 C577.163,1089 570,1096.16 570,1105 C570,1113.84 577.163,1121 586,1121 C594.837,1121 602,1113.84 602,1105 C602,1096.16 594.837,1089 586,1089 L586,1089 Z" id="cross-circle" sketch:type="MSShapeGroup">
        </path>
                </g>
            </g>
        </svg>`
        var inp = document.createElement('input');
        document.body.appendChild(inp)
        inp.value = codeValue
        inp.select();
        document.execCommand('copy', false);
        inp.remove();
        let appliedCodeError = document.getElementById('sd_applied_code_error')
        let copied_code_message = drawer_setting.copied_message_text
        let copied_message_text = copied_code_message.replace('{discount_name}', '<b>' + codeValue + '</b>')
        appliedCodeError.innerHTML = '<p style="color:' + drawer_setting.all_text_color + ';">' + copied_message_text + '</p>'
        let copyIcon = document.querySelectorAll('.sd_copy_code_btn');
        copyIcon.forEach((btn) => {
            btn.innerHTML = `<svg class="sd_copied_icon" fill="` + drawer_setting.copied_button_color + `" width="20px" height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-e</title><path d="M408,480H184a72,72,0,0,1-72-72V184a72,72,0,0,1,72-72H408a72,72,0,0,1,72,72V408A72,72,0,0,1,408,480Z"></path><path d="M160,80H395.88A72.12,72.12,0,0,0,328,32H104a72,72,0,0,0-72,72V328a72.12,72.12,0,0,0,48,67.88V160A80,80,0,0,1,160,80Z"></path></svg>`
        })
        element.innerHTML = `
    <?xml version="1.0"?><svg heigth="20px" fill="`+ drawer_setting.copied_button_color + `" width="20px" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="24px" height="24px">    <path d="M12,2C6.477,2,2,6.477,2,12c0,5.523,4.477,10,10,10s10-4.477,10-10C22,6.477,17.523,2,12,2z M17.707,9.707l-7,7 C10.512,16.902,10.256,17,10,17s-0.512-0.098-0.707-0.293l-3-3c-0.391-0.391-0.391-1.023,0-1.414s1.023-0.391,1.414,0L10,14.586 l6.293-6.293c0.391-0.391,1.023-0.391,1.414,0S18.098,9.316,17.707,9.707z"/></svg>`
    }


    // var customer_id =  '';  // testing code 
    getMembershipData();
    // var product_widget_settings = 
    async function getMembershipData() {
        if (runAjax == 'no') {
            console.log('ppppppppppppppppp')
            // customer_data = '';
            if (customer_id !== '') {
                console.log('cccccccccccccccc', customerMetaDataJson)

                // if (customerMetaDataJson && customerMetaDataJson.sd_customers_metaobject_definition && customer) {
                //     var customer_Jsondata = customerMetaDataJson.sd_customers_metaobject_definition.customerData;
                //     console.log('customer_Jsondata', customer_Jsondata);
                //     if (customer_Jsondata) { // Check if customer_Jsondata is not undefined
                //         customer_data = customer_Jsondata.filter(item => item.shopify_customer_id == customer_id);
                //         console.log('customer_data', customer_data);

                //         if (customer_data.length != 0) {
                //             const max_sale_days = Math.max(...customer_data.map(item => item.no_of_sale_days));
                //             const max_sale_days_data = customer_data.filter(item => item.no_of_sale_days === max_sale_days);
                //             sale_no_of_sale_days = max_sale_days;
                //         }
                //     } else {
                //         console.log("Error: customer_Jsondata is undefined");
                //     }
                // } else {
                //     console.log("Error: customerMetaDataJson or its property is null or undefined");
                // }
            }


            product_widget_settings = metaObjectDataJson.sd_memberships_metaobject_definition.product_widget_settings;
            drawer_setting = metaObjectDataJson.sd_memberships_metaobject_definition.drawer_setting;
            member_groups_perks_product = metaObjectDataJson.sd_memberships_metaobject_definition.member_groups_perks_product;
            plans_widget_settings = metaObjectDataJson.sd_memberships_metaobject_definition.plans_widget_settings;
            member_group_details = metaObjectDataJson.sd_memberships_metaobject_definition.member_group_details;
            getCountDownData = metaObjectDataJson.sd_memberships_metaobject_definition.getCountDownData;
            getEarlySaleData = metaObjectDataJson.sd_memberships_metaobject_definition.early_sale_access;
            getBirthdayRecords = metaObjectDataJson.sd_memberships_metaobject_definition.getBirthdayRecords;
            birthdayFormData = metaObjectDataJson.sd_memberships_metaobject_definition.birthdayFormData ?? '';

            if (MembershipPageType === 'product') {
                checkSaleProduct();
            }
            membershipDatafunc();
        }
        else {
            const data = { "store": store, "customer_id": customer_id, action: "all-memberplans-list" };
            await fetch(ajaxurl, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, *cors, same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data), // body data type must match "Content-Type" header
            }).then((obj) => {
                return obj.json()
            }).then((obj) => {

                plans_widget_settings = obj.plans_widget_settings;
                member_groups_perks_product = obj.member_groups_perks_product;
                member_plan_data = obj.member_plan_data;
                member_group_details = obj.member_groups_details;
                drawer_setting = obj.drawer_setting;
                product_widget_settings = obj.product_widget_settings;
                drawer_setting = obj.drawer_setting
                getCountDownData = obj.getCountDownData;
                getEarlySaleData = obj.getEarlySaleData;
                freeShippingObj = obj.free_shipping_code;
                discountCode = obj.discount_code;
                customer_data = obj.customer_data;
                // console.log('obj => ',obj)
                // console.log('getEarlySaleData => ',getEarlySaleData)
                if (MembershipPageType === 'product') {
                    checkSaleProduct();
                }
                membershipDatafunc();
            });
        }
    }

    function currencyES6FormatMembership(money) {
        return new Intl.NumberFormat(localeCountry, {
            style: "currency",
            currency: sd_shopCurrency,
        }).format(money);
    }

    function getVariant_member() {
      if (Shopify.designMode) {
        variantID = loaded_variant;
      } else {
        variantID = window.ShopifyAnalytics.meta["selectedVariantId"];
      }

      if (variantID == "" || variantID == undefined || variantID == null) {
        let urlQuery = window.location.href;
        urlQuery = new URL(urlQuery);
        variantID = urlQuery.searchParams.get("variant");
        if (variantID == "" || variantID == undefined || variantID == null) {
          if (document.getElementsByName("id").length) {
            variantID = store == 'thediyart1.myshopify.com' ? document.querySelector('.product__info-container input[name="id"]').value :  document.getElementsByName("id")[0].value;
           
          } else {
            firstVariant = currentProductVariants[0];
            variantID = firstVariant.id;
          }
        } else {
        }
      }
    }
    async function membershipDatafunc() {
        if ((window.location.pathname).includes('/products/')) {
            getVariant_member();
            checkMembershipWidgetCreate();
        }
        // show member plans on product page
        // document.addEventListener("DOMContentLoaded", function (event) {
        if ((window.location.pathname).includes('/products/')) {
            getVariant_member();
            checkMembershipWidgetCreate();
            document.addEventListener('change', function (e) {
                // console.log('chnangee');
                if (Shopify.designMode) {
                    changeVariantID = loaded_variant;
                } else {
                    changeVariantID = window.ShopifyAnalytics.meta["selectedVariantId"];
                    if (store == 'blacklandvapes.myshopify.com') {
                        getQueryParamValue().then((variantValue) => {
                            changeVariantID = variantValue;
                            // console.log('function changeVariantID', changeVariantID);
                            if (variantID != changeVariantID) {
                                variantID = changeVariantID;
                                checkMembershipWidgetCreate();
                            }

                        }).catch((error) => {
                            console.error('Error:', error);
                        });
                    }
                }
                if (variantID != changeVariantID) {
                    variantID = changeVariantID;
                    checkMembershipWidgetCreate();
                }
            });
            getCartData().then(cartData => {
                setTimeout(() => {
                    hide_quantity_and_addToCart(cartData.items);
                    hide_quantitySelector_cartPage(cartData.items);
                }, 500);
            });

            const addButton = document.querySelector('button[name="add"]');
            if (addButton !== undefined && addButton !== null) {
                addButton.addEventListener('click', function () {
                    window.setTimeout(function () {
                        getCartData().then(cartData => {
                            hide_quantity_and_addToCart(cartData.items);
                            hide_quantitySelector_cartPage(cartData.items);
                        });
                    }, 800);
                });
            }
        }
        // });

        if (pageType == 'cart') {
            getCartData().then(cartData => {
                hide_quantitySelector_cartPage(cartData.items);
                reload_on();
            });
            function reload_on() {
                let delete_btn = document.querySelectorAll('a[href*="/cart/change?"]');
                delete_btn.forEach((el) => {
                    el.addEventListener('click', () => {
                        window.setTimeout(function () {
                            location.reload(false);
                        }, 500);
                    })
                })
            }
        }

        //check if section block is added or not
        let check_plan_list_block = document.getElementById('advanced-membership-list-wrapper');
        if (check_plan_list_block) {
            $heading_text = plans_widget_settings.heading_text;
            $heading_tag = plans_widget_settings.heading_tag;
            $heading_text_alignment = plans_widget_settings.heading_text_alignment;
            $heading_text_color = plans_widget_settings.heading_text_color;
            $background_color1 = plans_widget_settings.background_color1;
            $background_color2 = plans_widget_settings.background_color2;
            $text_color = plans_widget_settings.text_color;
            $most_popular_text = plans_widget_settings.most_popular_text;
            $most_popular_text_color = plans_widget_settings.most_popular_text_color;
            $most_popular_background1 = plans_widget_settings.most_popular_background1;
            $most_popular_background2 = plans_widget_settings.most_popular_background2;
            $widget_outline_color = plans_widget_settings.widget_outline_color;
            $month_text = plans_widget_settings.month_text;
            $week_text = plans_widget_settings.week_text;
            $day_text = plans_widget_settings.day_text;
            $year_text = plans_widget_settings.year_text;
            $offer_text = plans_widget_settings.offer_text;
            $offer_background1 = plans_widget_settings.offer_background1;
            $offer_background2 = plans_widget_settings.offer_background2;
            $perks_heading_text = plans_widget_settings.perks_heading_text;
            $tick_icon_color = plans_widget_settings.tick_icon_color;
            $button_background_color = plans_widget_settings.button_background_color;
            $button_text_color = plans_widget_settings.button_text_color;
            $button_text = plans_widget_settings.button_text;
            $free_shipping_perk_description_all_orders = plans_widget_settings.free_shipping_perk_description_all_orders;
            $free_shipping_perk_description_minimum_amount = plans_widget_settings.free_shipping_perk_description_min_amt;
            $free_shipping_perk_description_minimum_quantity = plans_widget_settings.free_shipping_perk_description_min_qty;
            $products_text = plans_widget_settings.products_text;
            $collection_text = plans_widget_settings.collection_text;
            $product_collection_perk_description = plans_widget_settings.product_collection_perk_description;
            $product_collection_discount_perk_description = plans_widget_settings.product_collection_discount_perk_description;
            $all_products_discount_perk_discription = plans_widget_settings.all_products_discount_perk_description;
            $free_gift_perk_description = plans_widget_settings.free_gift_perk_description;
            $early_sale_access_line = plans_widget_settings.early_sale_access_line ?? 'Get free {{number_of_days}}-day early access to the sale';

            var all_member_plans_list = `<div class=""><${$heading_tag} class="sd_headingText ${$heading_tag} Polaris-Heading sd_headingtextColor" style="color: ${$heading_text_color}; text-align:${$heading_text_alignment}">${$heading_text}</${$heading_tag}><div class="membership-plan-section">
        <div class="membership-plan-row">`;
            for (var j = 0; j < member_groups_perks_product.length; j++) {
                let plan_detail = member_groups_perks_product[j];

                // console.log('plan_detail => ', plan_detail);

                var membership_plan_id = plan_detail.membership_plan_id;
                // var member_plan_index = member_plan_data.findIndex(item => item.id === parseInt(membership_plan_id));
                var shopify_currency_rate = Shopify.currency;
                var shopify_conversion_rate = shopify_currency_rate.rate;
                // membership_product_url = member_plan_data[member_plan_index].membership_product_url;
                membership_product_url = plan_detail.membership_product_url;
                var membership_group_id = plan_detail.membership_group_id;
                var most_popular = '', shipping_offer = ''; fieldset_div = '';
                widget_border_style = `border: 2px solid ${$widget_outline_color};`;
                if (plan_detail.popular_plan == '1') {
                    most_popular = `<div class="popular-tag" style="background:linear-gradient(to right, ${$most_popular_background1},${$most_popular_background2});">
                        <h3 style="color:${$most_popular_text_color};">${$most_popular_text}</h3>
                        </div>`;
                    fieldset_div = `<fieldset aria-hidden="true" class="sd_fieldlist" style="border: 2px solid ${$widget_outline_color};"><legend class="sd_legend" align="center"><span>${$most_popular_text}</span></legend></fieldset>`;
                    widget_border_style = '';
                }
                all_member_plans_list += `
                <div class="membership-plan-card" style="background:linear-gradient(to right, ${$background_color1},${$background_color2});${widget_border_style}">
                ${most_popular}
                <div class="card-inner-details">
                    <h2 style="color:${$heading_text_color}">${plan_detail.membership_group_name}</h2>
                    <h6>${plan_detail.group_description}</h6>
                    <div class="sd_membership_options">`;
                all_member_plans_list += `<div class="total-plan-price">
                        <span class="text-6xl">${currencyES6FormatMembership((plan_detail.variant_price) * shopify_conversion_rate)}</span>
                        <sub class="text-base"><span>${sd_shopCurrency}</span></sub>
                    </div>`;
                all_member_plans_list += `</div>
                </div>
                <div class="best-plan-offer" style="background:linear-gradient(to right, ${$offer_background1},${$offer_background2}); border-top: 2px solid ${$widget_outline_color};border-bottom: 2px solid ${$widget_outline_color};">
                    <h5 style="color:${$heading_text_color}">${$offer_text}</h5>
                </div>
                <div class="plan-include-details">
                    <h4 style="color:${$heading_text_color};">${$perks_heading_text.replace(/{{plan_name}}/g, plan_detail.membership_group_name)}</h4><ul>`;

                // check if free shipping perk is enabled
                if (plan_detail.free_shipping_checked_value == '1') {
                    if (plan_detail.freeshipping_selected_value == 'none') {
                        var shipping_offer = $free_shipping_perk_description_all_orders;
                    } else if (plan_detail.freeshipping_selected_value == 'min_purchase_amount') {
                        var shipping_offer = $free_shipping_perk_description_minimum_amount;
                        shipping_offer = shipping_offer.replace(/{{minimum_purchase_amount}}/g, currencyES6FormatMembership(plan_detail.min_purchase_amount_value) + ' ' + sd_shopCurrency);
                    } else if (plan_detail.freeshipping_selected_value == 'min_quantity_items') {
                        var shipping_offer = $free_shipping_perk_description_minimum_quantity;
                        shipping_offer = shipping_offer.replace(/{{minimum_purchase_quantity}}/g, plan_detail.min_quantity_items);
                    }
                    all_member_plans_list += `
                        <li> <svg width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z" fill="`+ $tick_icon_color + `"></path>
                            </svg>
                            ${shipping_offer}
                        </li>`;
                }
                if (plan_detail.discounted_product_collection_checked_value == '1') {
                    if (plan_detail.discounted_product_collection_type == 'N') {
                        product_collection_discount_offer = $all_products_discount_perk_discription;
                        discounted_product_collection_title = '';
                        product_collection_type = '';
                        product_collection_discount_offer = product_collection_discount_offer.replace(/{{discounted_product_collection_percentageoff}}/g, plan_detail.discounted_product_collection_percentageoff);
                    } else if (plan_detail.discounted_product_collection_type == 'P') {
                        product_collection_discount_offer = $product_collection_discount_perk_description;
                        product_collection_discount_offer = product_collection_discount_offer.replace(/{{discounted_product_collection_percentageoff}}/g, plan_detail.discounted_product_collection_percentageoff);
                        discounted_product_collection_title = plan_detail.discounted__product_title;
                        product_collection_type = 'product';
                    } else if (plan_detail.discounted_product_collection_type == 'C') {
                        product_collection_discount_offer = $product_collection_discount_perk_description;
                        product_collection_discount_offer = product_collection_discount_offer.replace(/{{discounted_product_collection_percentageoff}}/g, plan_detail.discounted_product_collection_percentageoff);
                        discounted_product_collection_title = plan_detail.discounted__collection_title;
                        product_collection_type = 'collection';
                    }
                    product_collection_discount_offer = product_collection_discount_offer.replace(/{{discounted_product_title}}/g, discounted_product_collection_title);
                    product_collection_discount_offer = product_collection_discount_offer.replace(["{{products}}/{{collection}}"], [product_collection_type]);
                    all_member_plans_list += `<li> <svg width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z" fill="`+ $tick_icon_color + `"></path>
                            </svg>
                            ${product_collection_discount_offer}
                        </li>`;
                }
                if (plan_detail.early_access_checked_value == '1') {
                    all_member_plans_list += `<li> <svg width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z" fill="`+ $tick_icon_color + `"></path>
                            </svg>
                            ${$early_sale_access_line.replace(/{{number_of_days}}/g, plan_detail.no_of_sale_days)}
                        </li>`;
                }
                if (plan_detail.Free_gift_uponsignup_checkbox == '1') {
                    all_member_plans_list += `<li> <svg width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path d="M16.493 7.068a.75.75 0 10-1.06-1.061l1.06 1.06zm-7.456 6.395l-.53.53a.75.75 0 001.06 0l-.53-.53zm-3.47-4.53a.75.75 0 00-1.06 1.06l1.06-1.06zm9.866-2.926l-6.926 6.926 1.06 1.06 6.926-6.925-1.06-1.061zm-5.866 6.926l-4-4-1.06 1.06 4 4 1.06-1.06z" fill="`+ $tick_icon_color + `"></path>
                            </svg>
                            ${$free_gift_perk_description.replace(/{{Free_gift_uponsignup_productName}}/g, plan_detail.Free_gift_uponsignup_productName)}
                        </li>`;
                }
                all_member_plans_list += `
                    </ul>
                        <a href="/products/${membership_product_url}?variant=${plan_detail.variant_id}" class="try-plan-btn" style="color:${$button_text_color}; text-decoration: none;">
                            <div>${$button_text}</div>
                        </a>
                    </div>
                ${fieldset_div}
                </div>`;
            }
            all_member_plans_list += `</div>
        </div>
        </div>
    </div>
    </div>`;
            if (document.getElementById('advanced-membership-list-wrapper')) {
                document.getElementById('advanced-membership-list-wrapper').innerHTML = all_member_plans_list;
            }
        }
        // hide quantity selector and add to cart button start
        if (pageType == 'cart') {
            const customer_data = await getCustomerData()
            console.log(customer_data, 'kdockovofvj')
            applyDiscountDrawer(customer_data)
        }

        // console.log('tempPageUrl',tempPageUrl)
        // console.log('accountPage',accountPage)

        // if (accountPage === 'account' || tempPageUrl === 'account' || accountPage === 'customers/account') {
        //     console.log('step 1');
        //     async function getPortalData() {
        //         const data = { "store": store, "customer_id": customer_id, action:"all_plan_list_client" };
        //         const response = await fetch(ajaxurl, {
        //             method: "POST",
        //             mode: "cors",
        //             cache: "no-cache",
        //             credentials: "same-origin",
        //             headers: {
        //                 "Content-Type": "application/json",
        //             },
        //             body: JSON.stringify(data),
        //         });
        //         const responseData = await response.json();
        //         const portal_data = responseData.portal_data;
        //         const view_data = responseData.view_data;

        //         let plansData = `<div class="customer_portal_spinner"></div>
        //         <div class="sd_main_customer_portal" style="width: 100%; margin-bottom: 20px;">
        //         <div class="main-card1" style="width: 100%; margin-bottom: 20px;">
        //         <table role="table" class="order-history">
        //         <thead role="rowgroup">
        //             <tr role="row">
        //                 <th scope="col" role="columnheader"><h3>${portal_data.table_serial_no ?? 'Sr No'}</h3></th>
        //                 <th scope="col" role="columnheader"><h3>${portal_data.table_plan_name ?? 'Plan Name'}</h3></th>
        //                 <th scope="col" role="columnheader"><h3>${portal_data.table_plan_status ?? 'Status'}</h3></th>
        //                 <th scope="col" role="columnheader"><h3>${portal_data.table_plan_action ?? 'Action'}</h3></th>
        //             </tr>
        //         </thead>
        //         <tbody role="rowgroup">`;

        //         view_data.forEach((value, key) => {
        //             let plan_status, plan_status_class;
        //             if (value.plan_status === 'A') {
        //                 plan_status = 'Active';
        //                 plan_status_class = "sd_plan_active";
        //             } else if (value.plan_status === 'P') {
        //                 plan_status = 'Paused';
        //                 plan_status_class = "sd_plan_pause";
        //             } else {
        //                 plan_status = 'Cancelled';
        //                 plan_status_class = "sd_plan_cancel";
        //             }
        //             plansData += `<tr role="row">
        //         <td headers="RowOrder ColumnDate" role="cell">${key + 1}</td>
        //         <td headers="RowOrder ColumnDate" role="cell">${value.product_name}</td>
        //         <td headers="RowOrder ColumnDate" role="cell"><sup class="${plan_status_class} sd_membership_badges">${plan_status}</sup></td>
        //         <td headers="RowOrder ColumnDate" role="cell"><button class="sd_view_membership" style="margin: 0 0 13px;" data-attr="${value.customer_id}" id="${value.contract_id}">Manage Membership</button></td>
        //             </tr>
        //             </tbody>`;
        //                 });

        //                 plansData += `</table>
        //             </div>
        //         </div>`;
        //         let parentDiv2 = document.createElement('div');
        //         parentDiv2.className = 'sd_all_plans_data';
        //         parentDiv2.style.display = 'block !important';
        //         parentDiv2.style.margin = '0 !important';
        //         parentDiv2.innerHTML = plansData;
        //         let parentDiv3 = document.querySelector('.customer.account > div:last-child');
        //         parentDiv3.parentNode.insertBefore(parentDiv2, parentDiv3);
        //     }

        //     // if (store == "60f7eb.myshopify.com") {
        //     //     console.log('hello---->1');
        //     //     getPortalData();
        //     // } else {
        //     //     if (store == 'blacklandvapes.myshopify.com') {
        //     //         let manageId2 = document.getElementById("sd_manage_perksy_memberships");
        //     //         if (manageId2) {
        //     //             let anchorTag = document.createElement('a');
        //     //             anchorTag.href = '/apps/your-membership';
        //     //             anchorTag.classList.add('sd_manage_membership_link');
        //     //             anchorTag.classList.add('sd_custom-manage-btn');
        //     //             anchorTag.innerText = 'Manage Membership';
        //     //             manageId2.appendChild(anchorTag);
        //     //         }                    
        //     //     }
        //     //     else{
        //     //         let manageId = document.querySelector(".order-history");
        //     //         if (manageId) {
        //     //             let anchorTag = document.createElement('a');
        //     //             anchorTag.href = '/apps/your-membership';
        //     //             anchorTag.classList.add('sd_manage_membership_link');
        //     //             anchorTag.innerText = 'Manage Membership';
        //     //             manageId.insertAdjacentElement('afterend', anchorTag);
        //     //         }
        //     //     }
        //     // }


        // }

        var backButton;


        // console.log('accountPage => ',accountPage);
        // console.log('tempPageUrl => ',tempPageUrl);
        if (accountPage === 'memberships' || accountPage === 'account' || tempPageUrl === 'your-membership') {
            // console.log('Inderjeet singh');
            setTimeout(() => {
                let viewMembership = document.querySelectorAll('.sd_view_membership')
                viewMembership.forEach(element => {
                    element.addEventListener('click', async function () {
                        let loader = document.getElementsByClassName('customer_portal_spinner')
                        loader[0].classList.add('active')
                        let customer_id = element.getAttribute('data-attr')
                        let contract_id = element.getAttribute('id')

                        const data = { "store": store, "contract_id": contract_id, "customer_id": customer_id, action: "shopify-app-proxy" }
                        await fetch(ajaxurl, {
                            method: "POST", // *GET, POST, PUT, DELETE, etc.
                            mode: "cors", // no-cors, *cors, same-origin
                            cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                            credentials: "same-origin", // include, *same-origin, omit
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(data), // body data type must match "Content-Type" header
                        }).then((data) => {
                            return data.json()
                        }).then((data) => {
                            loader[0].classList.remove('active')
                            const viewData = document.querySelector('.sd_main_customer_portal')
                            viewData.innerHTML = data.view_data
                            var accordionHeaders = document.querySelectorAll('.accordion-header');
                            var seeMoreBtn = document.querySelector('.sd_see_more_btn');
                            var hideContentBtn = document.querySelector('.sd_hide_content_btn');
                            var customerPortalMoreContent = document.querySelector('.sd_customer_portal_more_content');
                            accordionHeaders.forEach(function (accordionHeader) {
                                accordionHeader.addEventListener('click', function () {
                                    var accordionContent = this.nextElementSibling;
                                    if (accordionContent.style.display === 'none' || accordionContent.style.display === '') {
                                        accordionContent.style.display = 'block';
                                    } else {
                                        accordionContent.style.display = 'none';
                                    }
                                    var arrow = this.querySelector('.arrow');
                                    if (arrow.classList.contains('rotate')) {
                                        arrow.classList.remove('rotate');
                                    } else {
                                        arrow.classList.add('rotate');
                                    }
                                });
                            });
                            if (seeMoreBtn != null) {
                                seeMoreBtn.addEventListener('click', function () {
                                    customerPortalMoreContent.style.display = (customerPortalMoreContent.style.display === 'none' || customerPortalMoreContent.style.display === '') ? 'block' : 'none';
                                });
                            }
                            if (hideContentBtn != null) {
                                hideContentBtn.addEventListener('click', function () {
                                    document.querySelectorAll('.see-more-btn').forEach(function (btn) {
                                        btn.style.display = 'block';
                                    });
                                    customerPortalMoreContent.style.display = 'none';
                                });
                            }
                            var membershipAction = document.querySelectorAll('.sd_manage_membership');
                            membershipAction.forEach(element => {
                                element.addEventListener('click', async (e, i) => {
                                    loader[0].classList.add('active')
                                    const contract_id = element.getAttribute('data-attr')
                                    const activePlan = element.getAttribute('attr-plan')
                                    const activeTier = element.getAttribute('attr-tier')
                                    const status = element.value
                                    const customer_id = ShopifyAnalytics.meta.page.customerId
                                    const data = { "store": store, "contract_id": contract_id, "status": status, "customer_id": customer_id, 'active_plan': activePlan, 'active_tier': activeTier, action: "membership_status_change" }
                                    await fetch(ajaxurl, {
                                        method: "POST",
                                        mode: "cors",
                                        cache: "no-cache",
                                        credentials: "same-origin",
                                        headers: {
                                            "Content-Type": "application/json",
                                        },
                                        body: JSON.stringify(data),
                                    }).then((response) => {
                                        return response.json()
                                    }).then((response) => {
                                        loader[0].classList.remove('active')
                                        if (response.isError == false) {
                                            let messageDiv = document.querySelector('.sd_manage_membership_buttons')
                                            messageDiv.innerHTML = '<h3 style="color:#2fbe21;">' + response.message + '</h3>'
                                        } else {
                                            let messageDiv = document.querySelector('.sd_manage_membership_buttons')
                                            messageDiv.innerHTML = '<h3 style="color:#ff0000;">' + response.message + '</h3>'
                                        }
                                        setTimeout(() => {
                                            if (store == "inderjit-s16.myshopify.com" || store == "60f7eb.myshopify.com") {
                                                console.log('hello---->2');

                                                location.reload();
                                            } else {
                                                window.location.href = 'https://' + store + '/apps/your-membership'
                                            }
                                        }, 2000);
                                    });
                                })
                            });

                            let updatePayment = document.querySelectorAll('.sd-update-payment')
                            updatePayment.forEach(element => {
                                element.addEventListener('click', async function () {
                                    let payment_token = element.getAttribute('payment-token');
                                    let customer_email = element.getAttribute('customer-email');
                                    const data = { "payment_token": payment_token, "customer_email": customer_email, "shop_name": store, action: "membership-payment-update" }
                                    await fetch(ajaxurl, {
                                        method: "POST", // *GET, POST, PUT, DELETE, etc.
                                        mode: "cors", // no-cors, *cors, same-origin
                                        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                                        credentials: "same-origin", // include, *same-origin, omit
                                        headers: {
                                            "Content-Type": "application/json",
                                        },
                                        body: JSON.stringify(data), // body data type must match "Content-Type" header
                                    }).then((response) => {
                                        return response.json()
                                    }).then((response) => {
                                        let update_payment_message = document.getElementById('payment_method_message')
                                        update_payment_message.innerHTML = response.message
                                    });
                                });
                            });
                            let skipBtn = document.querySelectorAll('.sd_skip_button');
                            document.getElementById('btnClose').addEventListener('click', function () {
                                closeModel();
                            })
                            function closeModel() {
                                document.getElementById('myModal').style.display = 'none';
                            }
                            skipBtn.forEach((button) => {
                                button.addEventListener('click', function () {
                                    document.getElementById('myModal').style.display = 'flex';
                                    document.getElementById('btnOk').addEventListener('click', async function () {
                                        loader[0].classList.add('active');
                                        let contract_id = button.getAttribute('data-attr');
                                        let customer_id = button.getAttribute('attr-id');
                                        let upcoming_date = button.getAttribute('attr-date');
                                        let membership_plan_name = button.getAttribute('attr-plan');
                                        const data = {
                                            "contract_id": contract_id,
                                            "customer_id": customer_id,
                                            "shop_name": store,
                                            "upcoming_date": upcoming_date,
                                            "membership_plan_name": membership_plan_name,
                                            "action": "membership-plan-skip"
                                        };
                                        const response = await fetch(ajaxurl, {
                                            method: "POST",
                                            mode: "cors",
                                            cache: "no-cache",
                                            credentials: "same-origin",
                                            headers: {
                                                "Content-Type": "application/json",
                                            },
                                            body: JSON.stringify(data),
                                        });

                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        const responseData = await response.json();
                                        closeModel();

                                        loader[0].classList.remove('active')
                                        button.classList.add('sd_skip_btn_disable');
                                        button.innerText = 'Skipped';
                                        let statusText = button.getAttribute("attr-date");
                                        document.getElementById(statusText).innerHTML = "Skipped";
                                        document.getElementById(statusText).classList.add('sd_skip_btn_disable');
                                    });
                                });
                                closeModel();
                            });

                        }).catch((err) => {
                        })
                        backButton = document.getElementById('sd_memberPortal_back')
                        backButton.addEventListener('click', (e) => {
                            location.reload(true);
                        })
                    })
                })
            }, 1500);
        }
    }

    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }


    async function checkSaleProduct() {
        // console.log('hererereererer')
        // customer_id = '7682224226617'; // just testing
        // console.log('checkSaleProduct function running');
        let productId = MembershipProductId;
        // console.log(MembershipProductId,'MembershipProductId');
        let collectionId = sdMembershipCollectionId;
        const current_date = new Date();
        let saleProductExists = false;
        let sale_start_date = '';
        let sale_end_date = '';

        // console.log('getEarlySaleData',getEarlySaleData)

        if (getEarlySaleData && getEarlySaleData.sale_start_date !== undefined) {
            // console.log('Sale is live.');
            sale_start_date = getEarlySaleData.sale_start_date;
            sale_end_date = getEarlySaleData.sale_end_date;
        } else {
            console.log('Sale not found on this product.');
            return false;
        }

        sale_start_date = isNaN(new Date(sale_start_date).getTime()) ? '' : new Date(sale_start_date);
        sale_end_date = isNaN(new Date(sale_end_date).getTime()) ? '' : new Date(sale_end_date);
        let no_of_sale_days = 0;
        let restricted_early_access = getEarlySaleData.restricted_early_access;
        let sale_productType = getEarlySaleData.sale_productType;

        if (sale_productType == 'P') {
            productId = productId.toString();
            let early_access_product_id = getEarlySaleData.early_access_product_id;
            if (early_access_product_id.includes(productId)) {
                saleProductExists = true;
            }
        }
        if (sale_productType == 'C') {
            let early_access_collection_id = getEarlySaleData.early_access_collection_id;
            let collectionArray2 = early_access_collection_id.split(',').map(Number);
            for (let i = 0; i < collectionId.length; i++) {
                if (collectionArray2.includes(collectionId[i])) {
                    saleProductExists = true;
                    break;
                }
            }
        }
        if (sale_productType == 'N') {
            saleProductExists = true;
        }
        console.log('getEarlySaleData', getEarlySaleData)
        console.log(saleProductExists, 'jjjjjjjjjjjjjjj', runCustomerAjax)
        if (saleProductExists && getEarlySaleData.sale_status == 'Active') {
            if (customer_id) {
                if (runCustomerAjax == 'yes') {
                    try {
                        const data = { "store": store, "customer_id": customer_id, action: "check-sale-product" };
                        const response = await fetch(ajaxurl, {
                            method: "POST",
                            mode: "cors",
                            cache: "no-cache",
                            credentials: "same-origin",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(data),
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        const obj = await response.json();

                        let early_access_checked_value = obj.checkPrimeCustomer?.early_access_checked_value ?? '0';

                        if (early_access_checked_value == '1') {
                            no_of_sale_days = obj.checkPrimeCustomer.no_of_sale_days;
                            // no_of_sale_days = Number(no_of_sale_days);
                            // console.log('hello maine kar dikahay.')
                        } else {
                            no_of_sale_days = 0;
                        }
                    } catch (error) {
                        console.error('Error making API request:', error);
                    }
                } else {
                    // no_of_sale_days = getCustomerSaleDetails.no_of_sale_days ?? '0';
                    // console.log(sale_no_of_sale_days, 'sale_no_of_sale_days')

                    no_of_sale_days = sale_no_of_sale_days ?? '0';

                }
            }

            // console.log(no_of_sale_days, 'no_of_sale_days')

            // no_of_sale_days = 4; // justing testing 
            let discountInputCode = document.createElement("input");
            discountInputCode.type = "hidden";
            discountInputCode.name = "discount";
            discountInputCode.value = getEarlySaleData.discount_coupan ?? '';

            let timerDiv = document.createElement("div");
            timerDiv.classList.add("sd_countdown");
            // Find the form
            // let cartForms = document.querySelectorAll('form[action="/cart/add"]');
            // cartForms.forEach(function(form) {
            //     // Append the hidden input element to the form
            //     form.appendChild(discountInputCode.cloneNode());
            //     console.log(discountInputCode)
            // });

            if (sale_start_date !== '' && sale_end_date !== '' && no_of_sale_days !== '') {
                let final_start_date = new Date(sale_start_date);
                final_start_date.setDate(sale_start_date.getDate() - no_of_sale_days);

                if (!(sale_end_date instanceof Date) || isNaN(sale_end_date.getTime())) {
                    sale_end_date = new Date(sale_end_date);
                }

                if (current_date < final_start_date) {
                    // console.log('Sale Start timer');
                    // let cartBtns = document.querySelectorAll('.product-form__buttons');
                    let cartBtns = document.querySelectorAll('form[action="/cart/add"]'); 
                    if (cartBtns.length) {
                        cartBtns.forEach((cartBtn) => {

                            // console.log(getCountDownData.show_cart_btn, 'getCountDownData');

                            if (getCountDownData.show_cart_btn !== '1') {
                                cartBtn.innerHTML = '';
                            }

                            let discountInputClone = discountInputCode.cloneNode(true);
                            cartBtn.insertAdjacentElement("beforebegin", discountInputClone);
                            cartBtn.insertAdjacentHTML("afterend", `<div class="sd-sale-banner" style="border:1px solid ${getCountDownData.outer_border_color ?? '#000000'}; border-radius:${getCountDownData.outer_border_radius ?? '5'}px; background: linear-gradient(to right, ${getCountDownData.outer_bgcolor1 ?? '#ffffff'}, ${getCountDownData.outer_bgcolor2 ?? '#ffffff'})"><p style="color : ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.discount_text.replace("{discount_code}", getEarlySaleData.discount_coupan).replace("{discount_off}", getEarlySaleData.sale_discount)}</p></div>`);
                            cartBtn.insertAdjacentElement("afterend", timerDiv);
                            CountDownTimer(final_start_date, timerDiv);
                        });
                    }
                }

                console.log('final_start_date', final_start_date);
                console.log('no_of_sale_days', no_of_sale_days);
                console.log('sale_end_date', sale_end_date);
                console.log('current_date', current_date);

                if (current_date >= final_start_date && current_date <= sale_end_date) {
                    // console.log('Sale End timer');
                    // let cartBtns = document.querySelectorAll('.product-form__buttons');
                    let cartBtns = document.querySelectorAll('form[action="/cart/add"]'); 
                    if (cartBtns.length) {
                        cartBtns.forEach((cartBtn) => {
                            let discountInputClone = discountInputCode.cloneNode(true);
                            cartBtn.insertAdjacentElement("beforebegin", discountInputClone);
                            cartBtn.insertAdjacentHTML("afterend", `<div class="sd-sale-banner" style="border:1px solid ${getCountDownData.outer_border_color ?? '#000000'}; border-radius:${getCountDownData.outer_border_radius ?? '5'}px; background: linear-gradient(to right, ${getCountDownData.outer_bgcolor1 ?? '#ffffff'}, ${getCountDownData.outer_bgcolor2 ?? '#ffffff'})"><p style="color : ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.discount_text.replace("{discount_code}", getEarlySaleData.discount_coupan).replace("{discount_off}", getEarlySaleData.sale_discount)}</p></div>`);
                            cartBtn.insertAdjacentElement("afterend", timerDiv);
                            getCountDownData.title_text = 'Sale End in'
                            CountDownTimer(sale_end_date, timerDiv);
                        });
                    }
                }

            }
        }

    }

    function CountDownTimer(sale_start_date, timerDiv) {
        // Convert to Date object if needed
        if (!(sale_start_date instanceof Date)) {
            sale_start_date = new Date(sale_start_date);
        }

        // Validate date
        if (isNaN(sale_start_date.getTime())) {
            console.error("Invalid date format for sale_start_date");
            return;
        }

        let countDownDate = sale_start_date.getTime();

        let intervalId = setInterval(function () {
            let now = new Date().getTime();
            let distance = countDownDate - now;

            if (distance < 0) {
                clearInterval(intervalId);
                timerDiv.innerHTML = "Sale Started"; // or any final message
                return;
            }

            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            let saleTime = selectTimerTemplate(days, hours, minutes, seconds);
            timerDiv.innerHTML = saleTime;

        }, 1000);

        return intervalId;
    }



    function selectTimerTemplate(days, hours, minutes, seconds) {
        let layout = '';

        switch (getCountDownData.select_layout) {
            case 'layout1':
                layout = `<div class="countdown_lauout_main layout1 outer_radius outer_border_color outer_bg_color" style="border: 1px solid ${getCountDownData.outer_border_color ?? '#000000'}; border-radius: ${getCountDownData.outer_border_radius ?? '5'}px; background: linear-gradient(to right, ${getCountDownData.outer_bgcolor1 ?? '#ffffff'}, ${getCountDownData.outer_bgcolor2 ?? '#ffffff'})">
    <div class="countdown_lauout_1">
        <div class="countdown_heading">
            <h4 class="title_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.title_text ?? 'Big Sale'}</h4>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'}; border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px">${days}</h3>
            <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">:</span>
            <p class="day_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.day_text ?? 'Day'}</p>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'}">${hours}</h3>
            <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">:</span>
            <p class="hour_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.hour_text ?? 'Hour'}</p>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'}">${minutes}</h3>
            <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">:</span>
            <p class="mint_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.mint_text ?? 'Min'}</p>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'}">${seconds}</h3>
            <p class="second_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.second_text ?? 'Sec'}</p>
        </div>
    </div>
</div>`;
                break;
            case 'layout2':
                layout = `<div class="countdown_lauout_main layout2 outer_radius outer_border_color outer_bg_color" style="border: 1px solid ${getCountDownData.outer_border_color ?? '#000000'}; border-radius: ${getCountDownData.outer_border_radius ?? '5'}px; background: linear-gradient(to right, ${getCountDownData.outer_bgcolor1 ?? '#ffffff'}, ${getCountDownData.outer_bgcolor2 ?? '#ffffff'})">
    <div class="countdown_lauout_1 countdown_lauout_2">
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${days}</h3>
            <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
            <p class="day_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.day_text ?? 'Day'}</p>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${hours}</h3>
            <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
            <p class="hour_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.hour_text ?? 'Hour'}</p>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${minutes}</h3>
            <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
            <p class="mint_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.mint_text ?? 'Min'}</p>
        </div>
        <div class="countdown_lauout_timing">
            <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${seconds}</h3>
            <p class="second_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.second_text ?? 'Sec'}</p>
        </div>
    </div>
</div>`;
                break;
            case 'layout3':

                layout = `<div class="countdown_lauout_main layout3 outer_radius outer_border_color outer_bg_color" style=" border: 1px solid ${getCountDownData.outer_border_color ?? '#000000'}; border-radius: ${getCountDownData.outer_border_radius ?? '5'}px; background: linear-gradient(to right, ${getCountDownData.outer_bgcolor1 ?? '#ffffff'}, ${getCountDownData.outer_bgcolor2 ?? '#ffffff'})">
    <div class="countdown_lauout_1 countdown_lauout_3">
        <div class="countdown_heading">
            <h4 class="title_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.title_text ?? 'Big Sale'}</h4>
        </div>
        <div class="countdown_lauout_inner3">
            <div class="countdown_lauout_timing">
                <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${days}</h3>
                <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
                <p class="day_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.day_text ?? 'Day'}</p>
            </div>
            <div class="countdown_lauout_timing">
                <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${hours}</h3>
                <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
                <p class="hour_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.hour_text ?? 'Hour'}</p>
            </div>
            <div class="countdown_lauout_timing">
                <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${minutes}</h3>
                <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
                <p class="mint_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.mint_text ?? 'Min'}</p>
            </div>
            <div class="countdown_lauout_timing">
                <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${seconds}</h3>
                <p class="second_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.second_text ?? 'Sec'}</p>
            </div>
        </div>
    </div>
</div>`;
                break;
            case 'layout4':

                const daydigits = splitNumberUsingForLoop(days);
                const hourdigits = splitNumberUsingForLoop(hours);
                const mintdigits = splitNumberUsingForLoop(minutes);
                const secdigits = splitNumberUsingForLoop(seconds);

                layout = `<div class="countdown_lauout_main layout4 outer_radius outer_border_color outer_bg_color" style=" border: 1px solid ${getCountDownData.outer_border_color ?? '#000000'}; border-radius: ${getCountDownData.outer_border_radius ?? '5'}px; background: linear-gradient(to right, ${getCountDownData.outer_bgcolor1 ?? '#ffffff'}, ${getCountDownData.outer_bgcolor2 ?? '#ffffff'}); border-radius">
    <div class="countdown_lauout_4">
        <div class="countdown_heading">
            <h4 class="title_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'}">${getCountDownData.title_text ?? 'Big Sale'}</h4>
        </div>
        <div class="countdown_lauout_inner3">
            <div class="countdown_lauout_timing">
                <div class="countdown_lauout_dbl">
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${daydigits[0] ?? '0'}</h3>
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${daydigits[1] ?? '0'}</h3>
                </div>
                <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
                <p class="day_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.day_text ?? 'Day'}</p>
            </div>
            <div class="countdown_lauout_timing">
                <div class="countdown_lauout_dbl">
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${hourdigits[0] ?? '0'}</h3>
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${hourdigits[1] ?? '0'}</h3>
                </div>
                <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
                <p class="hour_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.hour_text ?? 'Hour'}</p>
            </div>
            <div class="countdown_lauout_timing">
                <div class="countdown_lauout_dbl">
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${mintdigits[0] ?? '0'}</h3>
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${mintdigits[1] ?? '0'}</h3>
                </div>
                <span class="text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">:</span>
                <p class="mint_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.mint_text ?? 'Min'}</p>
            </div>
            <div class="countdown_lauout_timing">
                <div class="countdown_lauout_dbl">
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${secdigits[0] ?? '0'}</h3>
                    <h3 class="inner_radius inner_border_color time_text_color inner_bg_color" style="background: linear-gradient(to right, ${getCountDownData.inner_bgcolor1 ?? '#000000'}, ${getCountDownData.inner_bgcolor2 ?? '#000000'}); border-radius: ${getCountDownData.inner_border_radius ?? '5px'}px; border-color: ${getCountDownData.inner_border_color ?? '#000000'}; color: ${getCountDownData.time_text_color ?? '#ffffff'};">${secdigits[1] ?? '0'}</h3>
                </div>
                <p class="second_text text_color" style="color: ${getCountDownData.text_color ?? '#000000'};">${getCountDownData.second_text ?? 'Sec'}</p>
            </div>
        </div>
    </div>
</div>`;
                break;
        }
        return layout;
    }

    function splitNumberUsingForLoop(number) {
        const digits = [];
        const numberString = number.toString();

        // Check if the number is a single digit
        if (numberString.length === 1) {
            digits.push(0);
        }

        for (let i = 0; i < numberString.length; i++) {
            const digit = parseInt(numberString[i]);
            digits.push(digit);
        }
        return digits;
    }


    function getQueryParamValue() {
        return new Promise((resolve, reject) => {
            setTimeout(function () {
                let currentUrl = window.location.href;
                let searchParams = new URLSearchParams(new URL(currentUrl).search);
                resolve(searchParams.get('variant'));
            }, 1000);
        });
    }




}