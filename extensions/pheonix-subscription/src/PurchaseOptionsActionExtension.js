import {
  AdminAction,
  BlockStack,
  Box,
  Button,
  InlineStack,
  Text,
  TextField,
  NumberField,
  Select,
  ChoiceList,
  Checkbox,
  ProgressIndicator
} from '@shopify/ui-extensions/admin';

export default function PurchaseOptionsAction(
  extensions,
  root,
  { i18n, close, data },
) {
  let planType = 'Pay Per Delivery'
  let planName = '';
  let sellingPlanName = '';
  let internalDescription = '';
  let deliveryFrequencyValue = 1;
  let deliveryDiscountValue = 0;
  let deliveryFrequencyUnit = 'DAY';
  let discountType = 'percentageOff';
  let subscription_plan_group_detail;
  let special_character_format = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
  let ajax_url = 'https://yulanda-unpanelled-superzealously.ngrok-free.dev/application/controller/ajaxhandler.php';
  console.log('Data', data);



  const handleClose = () => {
    console.log('Closing');
    close();
  };

  const getDiscountLabel = (discountType) => {
    switch (discountType) {
      case 'percentageOff':
        return 'Percentage off';
      case 'amountOff':
        return 'Amount off';
      case 'flatRate':
        return 'Flat rate';
      default:
        return 'Discount';
    }
  };

  const planNameField = root.createComponent(TextField, {
    label: 'Plan name',
    value: planName,
    // error: 'dehdbhdbhc',
    // placeholder: 'Subscribe and save',
    // helpText:
    //   'Customers will see this on storefront product pages that have subscriptions',
    onChange: (value) => {
      planName = value;
    },
  });
  const sellingPlanNameField = root.createComponent(TextField, {
    label: 'Selling plan name',
    value: sellingPlanName,
    // placeholder: 'Subscribe and save',
    // helpText:
    //   'Customers will see this on storefront product pages that have subscriptions',
    onChange: (value) => {
      sellingPlanName = value;
    },
  });
  const internalDescriptionField = root.createComponent(TextField, {
    label: 'Customer facing description(optional)',
    value: internalDescription,
    helpText: 'For your reference only',
    onChange: (value) => {
      internalDescription = value;
    },
  });

  const discountField = root.createComponent(NumberField, {
    label: getDiscountLabel(discountType),
    value: deliveryDiscountValue,
    onChange: (value) => {
      deliveryDiscountValue = value;
    },
  });
  // const applyDiscountCheckbox = root.createComponent(Checkbox, {
  //   label: 'Apply Discount',
  //   checked: true,
  //   onChange: () => { applyDiscount }
  // }
  // )
  // const discountTypeChoiceList = root.createComponent(ChoiceList, {
  //   title: 'Discount type',
  //   selected: discountType,
  //   value: discountType,
  //   onChange: (value) => {
  //     discountType = value;
  //     discountField.updateProps({ label: getDiscountLabel(discountType) });
  //     root.update();
  //   },
  //   choices: [
  //     { label: 'Percentage off', id: 'percentageOff' },
  //     { label: 'Amount off', id: 'amountOff' },
  //     { label: 'Flat rate', id: 'flatRate' },
  //   ],
  // });

  const deliveryFrequencyField = root.createComponent(NumberField, {
    label: 'Delivery frequency',
    value: deliveryFrequencyValue,
    onChange: (value) => {
      deliveryFrequencyValue = value;
    },
  });

  const deliveryFrequencyUnitSelect = root.createComponent(Select, {
    label: 'Delivery interval',
    value: deliveryFrequencyUnit,
    options: [
      { label: 'day', value: 'DAY' },
      { label: 'Week', value: 'WEEK' },
      { label: 'Month', value: 'MONTH' },
      { label: 'Year', value: 'YEAR' },
    ],
    onChange: (value) => {
      deliveryFrequencyUnit = value;
    },
  });

  // const planTypeField = root.createComponent(Select, {
  //   label: 'Plan type',
  //   value: planType,
  //   options: [
  //     { label: 'Pay per delivery', value: 'Pay Per Delivery' },
  //     { label: 'Prepaid', value: 'prepaid' },
  //   ],
  //   onChange: (value) => {
  //     planType = value;
  //   },
  // });
  function appendError(fieldName, errorText) {

    fieldName.updateProps({

      error: errorText

    });

    root.mount()

  }
  const progressIndicator = root.createComponent(
    ProgressIndicator,
    {
      size: 'small-200',
    },
  );

  const handleGetPlanData = async () => {
    validate_token().then(response => {
      if (response.ok) {
        response.json().then(json => {
          if (json.status == true) {

            store_name = json.store_name;
            // default_selling_plan_array();
            fetch(ajax_url + '?action=product_app_extension_fetchGroupDetail&store=' + store_name, {
              method: 'POST',
              headers: { Accept: 'application/json', },
              body: JSON.stringify(Object.assign({},  data.selected[0])),
            }).then(response => {
              if (response.ok) {
                response.json().then(json => {
                  if (json.status == true) {
                    // selling_plan_group_id = (data.sellingPlanGroupId).replace("gid://shopify/SellingPlanGroup/", "");
                    subscription_plan_group_detail = json?.subscription_plan_group_detail
                    subscription_plan_group_detail.forEach(element => {
                      planName = element?.plan_name
                      sellingPlanName = element?.frequency_plan_name
                      deliveryFrequencyUnit = element.per_delivery_order_frequency_type
                      deliveryFrequencyValue = element.per_delivery_order_frequency_value
                      internalDescription = element.sd_description
                      planNameField.updateProps({value:element?.plan_name})
                      sellingPlanNameField.updateProps({value:element?.frequency_plan_name})
                      deliveryFrequencyField.updateProps({value: element.per_delivery_order_frequency_value})
                      deliveryFrequencyUnitSelect.updateProps({value: element.per_delivery_order_frequency_type})
                      internalDescriptionField.updateProps({value: element.sd_description})
                    });
                    root.mount();
                  }
                })
              }
            })
          }
        })
      }
    })
  }
  if(data.selected[0].sellingPlanId && data != undefined) {
    handleGetPlanData()
  }
  const checkValidations = ()=> {
    
      appendError(planNameField, '')
      appendError(planNameField, '');
      appendError(sellingPlanNameField, '')
      appendError(sellingPlanNameField, '');
      appendError(deliveryFrequencyField, '');
      appendError(deliveryFrequencyField, '');
      if (planName.trim() == '') {
        appendError(planNameField, 'Selling plan name is required.');
        return false;
      }
  
      if (planName.length > 50) {
        appendError(planNameField, 'Selling plan name value should not be greater than 50 characters.');
        return false;
      }
  
      if (sellingPlanName.trim() == '') {
        appendError(sellingPlanNameField, 'Selling plan name is required.');
        return false;
      }
  
      if (sellingPlanName.length > 50) {
        appendError(sellingPlanNameField, 'Selling plan name value should not be greater than 50 characters.');
        return false;
      }
  
      // Check if the deliveryFrequencyValue is a number and greater than zero
      if (deliveryFrequencyValue == '') {
        appendError(deliveryFrequencyField, 'Delivery Period is required.');
        return false;
      } else if (/[a-zA-Z]/.test(deliveryFrequencyValue) || special_character_format.test(deliveryFrequencyValue)) {
        appendError(deliveryFrequencyField, 'Delivery period value should only contain numbers.');
        return false;
      }
      else if (deliveryFrequencyValue.length > 4) {
        appendError(deliveryFrequencyField, 'Delivery period value should not be greater than 4 digits.');
        return false;
      } else if (parseInt(deliveryFrequencyValue) < 0) {
        appendError(deliveryFrequencyField, 'Delivery period value should not be greater than 0.');
        return false;
      }
      return true;
  }
  const handleSave = async () => {
    const productVariantId = data?.selected[0]?.id
    root.appendChild(progressIndicator);
      if(checkValidations() == true) {
        validate_token().then(response => {
    
          if (response.ok) {
    
            response.json().then(json => {
    
              if (json.status == true) {
                store_name = json.store_name;
                if(data.selected[0].sellingPlanId) {
                  subscription_plan_group_detail[0]['frequency_plan_type'] = subscription_plan_group_detail[0].frequency_plan_type == 1 ? 'Pay Per Delivery' : 'Prepaid';
                  subscription_plan_group_detail[0]['frequency_plan_name'] = sellingPlanName
                  subscription_plan_group_detail[0]["per_delivery_order_frequency_value"] = deliveryFrequencyValue
                  subscription_plan_group_detail[0]['per_delivery_order_frequency_type'] = deliveryFrequencyUnit
                  subscription_plan_group_detail[0]["sd_description"] =  internalDescription
                  const sellingPlanArray = {
                    sd_subscription_edit_case_already_existing_plans_array: {[subscription_plan_group_detail[0].sellingplanid]:
                      subscription_plan_group_detail[0],
                    },
                    subscription_plan_id: (data.selected[0].sellingPlanId).replace("gid://shopify/SellingPlanGroup/", ""),
                    plan_name: planName,
                    sd_subscription_edit_case_to_be_added_new_plans_array: [],
                    store_data: {sellingPlanGroupId: data.selected[0].sellingPlanId}
                  }
                  if(productVariantId.includes("gid://shopify/ProductVariant/")) {
                    sellingPlanArray['variant_ids'] = productVariantId
                    sellingPlanArray['product_ids'] = ''
                  }else {
                    sellingPlanArray['product_ids'] = productVariantId
                  }
                  fetch(ajax_url + '?action=product_app_extension_edit&store=' + store_name, {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: JSON.stringify(Object.assign({}, sellingPlanArray)),
                  })
                    .then(response => {
                      if (response.ok) {
                        response.json()
                          .then(json => {
                            console.log(json, 'oooooooooooo');
                            if (json.status == true) {
                              close()
                              console.log(json, 'jjjjjjjjjjjjjjjjjjjj');
                            } else if (json.status == false) {
                              appendError(planNameField, json.message)
                              return false;
                            }
                          })
                          .catch(error => console.error('Error parsing JSON:', error));
                      } else {
                        console.error('Response not OK:', response);
                      }
                    })
                    .catch(error => console.error('Fetch error:', error));
                }else {
                  const sellingPlanArray = {
                    frequency_plan: [
                      {
                        "frequency_plan_name": sellingPlanName,
                        "plan_name": "",
                        "sellingplanid": "",
                        "frequency_plan_type": "Pay Per Delivery",
                        "subscription_discount": "",
                        "subscription_discount_after": "",
                        "sd_set_anchor_date": "",
                        "per_delivery_order_frequency_value": deliveryFrequencyValue,
                        "prepaid_billing_value": "",
                        "subscription_discount_value": "",
                        "per_delivery_order_frequency_type": deliveryFrequencyUnit,
                        "subscription_discount_type": "Percent Off(%)",
                        "change_discount_after_cycle": "",
                        "cut_off_days": "",
                        "discount_value_after": "",
                        "subscription_discount_type_after": "Percent Off(%)",
                        "maximum_number_cycle": "",
                        "minimum_number_cycle": "",
                        "sd_anchor_month_day": "",
                        "sd_anchor_option": "",
                        "sd_anchor_week_day": "",
                        "selling_plan_name": "Delivery Every Day",
                        "sd_description": internalDescription
                      },
                    ],
                    plan_name: planName
                  }
                  if(productVariantId.includes("gid://shopify/ProductVariant/")) {
                    sellingPlanArray['variant_ids'] = productVariantId
                    sellingPlanArray['product_ids'] = ''
                  }else {
                    sellingPlanArray['product_ids'] = productVariantId
                  }
                  fetch(ajax_url + '?action=product_app_extension_create&store=' + store_name, {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: JSON.stringify(Object.assign({}, sellingPlanArray)),
                  })
                    .then(response => {
                      if (response.ok) {
                        response.json()
                          .then(json => {
                            console.log(json, 'oooooooooooo');
                            if (json.status == true) {
                              close()
                              console.log(json, 'jjjjjjjjjjjjjjjjjjjj');
                            } else if (json.status == false) {
                              appendError(planNameField, json.message)
                              return false;
                            }
                          })
                          .catch(error => console.error('Error parsing JSON:', error));
                      } else {
                        console.error('Response not OK:', response);
                      }
                    })
                    .catch(error => console.error('Fetch error:', error));
                }
              }
            });
          }
        });
      }
    
  }
  const handleUpdate = async ()=> {

  }
  async function validate_token() {

    // subscription_group['token'] = token;

    let validate_token_response = fetch(ajax_url + '?action=product_app_extension_validate', {

      // method: 'GET',

      // headers: {Accept: 'application/json', },

      // body : JSON.stringify(Object.assign({}, subscription_group)),

    });

    return validate_token_response;

  }
  const primaryAction = root.createFragment();
  const secondaryAction = root.createFragment();

  primaryAction.appendChild(
    root.createComponent(Button, {
      onPress: handleSave
    }, data?.selected[0]?.sellingPlanId ? 'Update' : 'Save')
  );
  secondaryAction.appendChild(
    root.createComponent(Button, { onPress: handleClose }, 'Cancel')
  );
  root.append(
    root.createComponent(
      AdminAction,
      {
        // primaryAction: root.createComponent(
        //   Button,
        //   {onPress: handleSave},
        //   'Done',
        // ),
        // secondaryAction: root.createComponent(
        //   Button,
        //   {onPress: handleClose},
        //   'Close',
        // ),
        primaryAction,
        secondaryAction
      },
      root.createComponent(
        BlockStack,
        { gap: 'large' },
        planNameField,
        sellingPlanNameField,
        internalDescriptionField,
        // root.createComponent(Box, null, discountTypeChoiceList),
        root.createComponent(
          Box,
          null,
          root.createComponent(
            InlineStack,
            {
              gap: true,
              inlineAlignment: 'end',
              blockAlignment: 'end',
            },
            deliveryFrequencyField,
            deliveryFrequencyUnitSelect,
            // discountField,
          ),
        ),
      ),
    ),
  );
  root.mount();
}