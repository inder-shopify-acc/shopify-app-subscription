

/************************************************************  EDIT BUTTON OPERATION **************************/
console.log("membership perk js code start...");


// for Create perk page ................................................................................................\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// for create perk page ................................
var sd_selectedtierGroupIdArray = [];
var sd_selectedtierGroupId = '';
for (let tierGid = 0; tierGid < 3; tierGid++) {
    sd_selectedtierGroupId = $('.tierGroupIdArray' + tierGid).val();
    if (sd_selectedtierGroupId != '') {
        sd_selectedtierGroupIdArray.push(sd_selectedtierGroupId);
    }
}

var nextPerkButton = $("#next_DiscountButton");
var previousPerkButton = $("#previous_DiscountButton");
var savePerkTierButton = $("#save_DiscountButton");
var tierGroupId = 0;
console.log(sd_selectedtierGroupIdArray, 'lllllllllllllloooooooooooooo')
sd_selectedtierGroupId = sd_selectedtierGroupIdArray[tierGroupId];
jQuery('.TierSelected-' + sd_selectedtierGroupId).css("display", "block");
var allCoupanCodes = [];
var earlyAccessProIdArray = [];
var earlyAccessProTitleArray = [];
var earlyAccessColIdArray = [];
var earlyAccessColTitleArray = [];

// nextPerkButton.on("click", async function () {
//     console.log('next');
//     let checkboxFreeship = $('.freeShipCheckbox-' + sd_selectedtierGroupId).is(':checked');
//     let checkboxDiscount = $('.discountCheckbox-' + sd_selectedtierGroupId).is(':checked');
//     let checkboxFreeProduct = $('.freeProductCheckbox-' + sd_selectedtierGroupId).is(':checked');
//     let earlyAccessCheckbox = $('.earlyAccessCheckbox-' + sd_selectedtierGroupId).is(':checked');
//     let checkboxBirthday = $('.birthdayBox-' + sd_selectedtierGroupId).is(':checked');
//     let checkboxCustum = $('.customCheckbox-' + sd_selectedtierGroupId).is(':checked');
//     let freeshipInputCode;
//     let discountInputCode;
//     let freeshipCodeExists = false;
//     let discountCodeExists = false;

//     let perkFormValidations = true;
//     let checkboxValidation = false;
//     jQuery('.minPurchaseAmount_TextFieldError_' + sd_selectedtierGroupId).hide();
//     jQuery('.sd_perkViewErrors').css("display", "none");

//     shopify.loading(true);

//     if (checkboxBirthday) {
//         checkboxValidation = true;
//     }

//     // freeshipping validation check....................................... 

//     if (checkboxFreeship) {
//         checkboxValidation = true;
//         amtQtyCheck = $('.minimumDiscountCode-' + sd_selectedtierGroupId).val();
//         console.log('amtQtyCheck', amtQtyCheck)
//         let amountQty = '';

//         if (amtQtyCheck == 'none') {
//             perkFormValidations = true;
//         }
//         else {
//             if (amtQtyCheck == 'minPurchase_Amount' || amtQtyCheck == 'minQuantity_items') {
//                 amountQty = $('.minPurchaseAmount' + sd_selectedtierGroupId).val();
//             }
//             if (amountQty == '' || amountQty <= 0) {
//                 $('.minPurchaseAmount_TextFieldError_' + sd_selectedtierGroupId).show();
//                 perkFormValidations = false;
//             }
//         }
//         if ($('.freeShipValue-' + sd_selectedtierGroupId).val() != '') {
//             freeshipInputCode = $('.freeShipValue-' + sd_selectedtierGroupId).val().trim();
//             freeshipCodeExists = allCoupanCodes.includes(freeshipInputCode);

//         } else {
//             jQuery('.free_shipingCode_Error_' + sd_selectedtierGroupId).css("display", "block");
//             perkFormValidations = false;
//         }
//     }


//     // Discount validation check....................................... 

//     if (checkboxDiscount) {
//         checkboxValidation = true;
//         if ($('.discountInputBox-' + sd_selectedtierGroupId).val() != '') {
//             discountInputCode = $('.discountInputBox-' + sd_selectedtierGroupId).val().trim();
//             discountCodeExists = allCoupanCodes.includes(discountInputCode);

//         } else {
//             jQuery('.Existing_DiscountCode-Error-' + sd_selectedtierGroupId).css("display", "block").text('Discount code is required!');
//             perkFormValidations = false;
//         }

//         let discountPercentageinput = $('#DiscountCodePercentage-' + sd_selectedtierGroupId).val();
//         if (discountPercentageinput == 0 || discountPercentageinput == '') {
//             jQuery('.DiscountPercentage-Error-' + sd_selectedtierGroupId).css("display", "block").text('Enter percentage value & should be greater than 0');
//             perkFormValidations = false;
//         } else {

//             // console.log('free percentage code value is not empty');
//         }

//         let freeDiscountProductSelect = $('#PolarisSelect-' + sd_selectedtierGroupId).val();
//         if (freeDiscountProductSelect == 'collection') {
//             let freeProductId = $('.collectionTitleName-' + sd_selectedtierGroupId);
//             if (freeProductId.length === 0) {
//                 jQuery('.sd_selected_existing_collection-Error-' + sd_selectedtierGroupId).css("display", "block").text('Add collection');
//                 perkFormValidations = false;
//             }
//         }

//         if (freeDiscountProductSelect == 'product') {
//             let freeProductId = $('.productTitleName-' + sd_selectedtierGroupId);
//             if (freeProductId.length === 0) {
//                 jQuery('.sd_selected_existing_product-Error-' + sd_selectedtierGroupId).css("display", "block").text('Add product');
//                 perkFormValidations = false;
//             }
//         }
//     }

//     // signup Product validation check....................................... 

//     if (checkboxFreeProduct) {
//         checkboxValidation = true;

//         let freeProductId = $('#free_gift-upon-signUp-' + sd_selectedtierGroupId);
//         //  console.log(freeProductId);
//         //  console.log(freeProductId.length);
//         if (freeProductId.length === 0) {
//         } else {
//             jQuery('.free_gift-upon-signUp-Error-' + sd_selectedtierGroupId).css("display", "block");
//             perkFormValidations = false;
//         }
//     }

//     // early sale access check....................................... 

//     if (earlyAccessCheckbox) {
//         checkboxValidation = true;
//         noOfSaleDays = $('#EarlySaleAccessDays-' + sd_selectedtierGroupId).val();
//         if (noOfSaleDays.trim() === '' || isNaN(noOfSaleDays) || parseInt(noOfSaleDays) < 0 || parseInt(noOfSaleDays) > 9) {
//             jQuery('.EarlySaleAccess-Error-' + sd_selectedtierGroupId).css("display", "block");
//             perkFormValidations = false;
//         }
//     }


//     if (freeshipInputCode != undefined || discountInputCode != undefined) {

//         if (freeshipInputCode == discountInputCode) {
//             shopify.toast.show("Freeship code and discount code can't be same.", { isError: true });
//             return false;
//         }

//         if (freeshipCodeExists || discountCodeExists) {
//             shopify.toast.show("Codes are used in last step", { isError: true });
//             perkFormValidations = false;
//         }

//     }

//     if (!checkboxValidation) {
//         jQuery('.tier_perk_Error_' + sd_selectedtierGroupId).css("display", "block").text("Choose atleast one checkbox from first 5 checkbox");
//         shopify.loading(false);
//         return false;
//     }

//     if (!perkFormValidations) {
//         shopify.loading(false);
//         return false;
//     }


//     if (perkFormValidations) {
//         //  console.log('11111111111');
//         let idDynamicValue = sd_selectedtierGroupId;
//         let freeshipInputFieldId = "free_shipingCode-" + idDynamicValue;
//         let discountCoupanCodeInputFieldId = "DiscountProductCode_Field-" + idDynamicValue;
//         let freeshipCoupanCode = $("#" + freeshipInputFieldId).val();
//         let discountCoupanCode = $("#" + discountCoupanCodeInputFieldId).val();
//         let checkFreeArray = '';
//         let checkDiscountArray = '';
//         if (freeshipCoupanCode != '') {
//             checkFreeArray = allCoupanCodes.includes(freeshipCoupanCode);
//         }
//         if (discountCoupanCode != '') {
//             checkDiscountArray = allCoupanCodes.includes(discountCoupanCode);
//         }
//         if (checkFreeArray) {
//             //  console.log('FreeShip Code already exists');
//             $(".ExistingFree_shipingCode_Error").css("display", "block");
//             shopify.loading(false);
//             return false;
//         } else if (checkDiscountArray) {
//             //  console.log('Discount Code already exists');
//             $(".ExistingFree_shipingCode_Error").css("display", "none");
//             $(".Existing_DiscountCode-Error").css("display", "block");
//             shopify.loading(false);
//             return false;
//         }
//         allCoupanCodes.push(freeshipCoupanCode);
//         allCoupanCodes.push(discountCoupanCode);
//         console.log(allCoupanCodes);
//         let data = {'discountCoupanCode': freeshipCoupanCode, 'discountCoupanCode' :discountCoupanCode}
        
        
//         // let csrfToken = $('input[name="_token"]').val();
//         let ajaxParameters = {

//             method: "POST",
//             dataValues: {
//                 data,
//                 action: "checkCoupansCode"
//             }  
//         };
       
//         shopify.loading(true);
//         // let ajaxResult = await ajaxCall('update-popular-plan', fd);
//         let response = await AjaxCall(ajaxParameters);

//         shopify.loading(false);

//         if (response.status == true && response.type == 'freeShipExists') {
//             //  console.log('FreeShip Code already exists');
//             $(".ExistingFree_shipingCode_Error").css("display", "block");
//             return false;
//         } else if (response.status == true && response.type == 'discountExists') {
//             //  console.log('Discount Code already exists');
//             $(".ExistingFree_shipingCode_Error").css("display", "none");
//             $(".Existing_DiscountCode-Error").css("display", "block");
//             return false;
//         } else {
//             $(".ExistingFree_shipingCode_Error").css("display", "none");
//             $(".Existing_DiscountCode-Error").css("display", "none");

//             if (tierGroupId < 0) {
//                 tierGroupId = 0;
//             }
//             if (sd_selectedtierGroupIdArray.length > tierGroupId) {
//                 tierGroupId++;
//                 sd_selectedtierGroupId = sd_selectedtierGroupIdArray[tierGroupId];
//             }

//             jQuery('.edit-perks-form-selected').css("display", "none");
//             jQuery('.TierSelected-' + sd_selectedtierGroupId).css("display", "block");
//             manageButtons(tierGroupId);
//             // stepsButton(tierGroupId);
//         }
            
           
        
//     } else {
//         jQuery('.tier_perk_Error_' + sd_selectedtierGroupId).css("display", "block").text("Choose atleast one checkbox from first 5 checkbox");
//         shopify.loading(false);
//     }
// });


nextPerkButton.on("click", async function () {
    console.log('next edit button');
    let checkboxFreeship = $('.freeShipCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxDiscount = $('.discountCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxFreeProduct = $('.freeProductCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let earlyAccessCheckbox = $('.earlyAccessCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxBirthday = $('.birthdayBox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxCustum = $('.customCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let freeshipInputCode;
    let discountInputCode;
    let freeshipCodeExists = false;
    let discountCodeExists = false;

    let perkFormValidations = true;
    let checkboxValidation = false;
    jQuery('.minPurchaseAmount_TextFieldError_' + sd_selectedtierGroupId).hide();
    jQuery('.sd_perkViewErrors').css("display", "none");

    shopify.loading(true);

    if (checkboxBirthday) {
        checkboxValidation = true;
    }

    // freeshipping validation check....................................... 

    if (checkboxFreeship) {
        checkboxValidation = true;
        amtQtyCheck = $('.minimumDiscountCode-' + sd_selectedtierGroupId).val();
        console.log('amtQtyCheck', amtQtyCheck)
        let amountQty = '';

        if (amtQtyCheck == 'none') {
            perkFormValidations = true;
        }
        else {
            if (amtQtyCheck == 'minPurchase_Amount' || amtQtyCheck == 'minQuantity_items') {
                amountQty = $('.minPurchaseAmount' + sd_selectedtierGroupId).val();
            }
            if (amountQty == '' || amountQty <= 0) {
                $('.minPurchaseAmount_TextFieldError_' + sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }
        if ($('.freeShipValue-' + sd_selectedtierGroupId).val() != '') {
            freeshipInputCode = $('.freeShipValue-' + sd_selectedtierGroupId).val().trim();
            freeshipCodeExists = allCoupanCodes.includes(freeshipInputCode);

        } else {
            jQuery('.free_shipingCode_Error_' + sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }


    // Discount validation check....................................... 

    if (checkboxDiscount) {
        checkboxValidation = true;
        if ($('.discountInputBox-' + sd_selectedtierGroupId).val() != '') {
            discountInputCode = $('.discountInputBox-' + sd_selectedtierGroupId).val().trim();
            discountCodeExists = allCoupanCodes.includes(discountInputCode);

        } else {
            jQuery('.Existing_DiscountCode-Error-' + sd_selectedtierGroupId).css("display", "block").text('Discount code is required!');
            perkFormValidations = false;
        }

        let discountPercentageinput = $('#DiscountCodePercentage-' + sd_selectedtierGroupId).val();
        if (discountPercentageinput == 0 || discountPercentageinput == '') {
            jQuery('.DiscountPercentage-Error-' + sd_selectedtierGroupId).css("display", "block").text('Enter percentage value & should be greater than 0');
            perkFormValidations = false;
        } else {

            // console.log('free percentage code value is not empty');
        }

        let freeDiscountProductSelect = $('#PolarisSelect-' + sd_selectedtierGroupId).val();
        if (freeDiscountProductSelect == 'collection') {
            let freeProductId = $('.collectionTitleName-' + sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                jQuery('.sd_selected_existing_collection-Error-' + sd_selectedtierGroupId).css("display", "block").text('Add collection');
                perkFormValidations = false;
            }
        }

        if (freeDiscountProductSelect == 'product') {
            let freeProductId = $('.productTitleName-' + sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                jQuery('.sd_selected_existing_product-Error-' + sd_selectedtierGroupId).css("display", "block").text('Add product');
                perkFormValidations = false;
            }
        }
    }


    // signup Product validation check....................................... 

    if (checkboxFreeProduct) {
        checkboxValidation = true;

        let freeProductId = $('#free_gift-upon-signUp-' + sd_selectedtierGroupId);
        //  console.log(freeProductId);
        //  console.log(freeProductId.length);
        if (freeProductId.length === 0) {
        } else {
            jQuery('.free_gift-upon-signUp-Error-' + sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }

    // early sale access check....................................... 

    if (earlyAccessCheckbox) {
        checkboxValidation = true;
        noOfSaleDays = $('#EarlySaleAccessDays-' + sd_selectedtierGroupId).val();
        if (noOfSaleDays.trim() === '' || isNaN(noOfSaleDays) || parseInt(noOfSaleDays) < 0 || parseInt(noOfSaleDays) > 9) {
            jQuery('.EarlySaleAccess-Error-' + sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }


    // if (freeshipInputCode != undefined || discountInputCode != undefined) {

    //     if (freeshipInputCode == discountInputCode) {
    //         shopify.toast.show("Freeship code and discount code can't be same.", { isError: true });
    //         return false;
    //     }

    //     if (freeshipCodeExists || discountCodeExists) {
    //         console.log('used in last step 1');
    //         shopify.toast.show("Codes are used in last step", { isError: true });
    //         perkFormValidations = false;
    //     }

    // }

    if (freeshipInputCode != undefined || discountInputCode != undefined) {

        // Ignore duplicate check for currently entered codes
        let codesToCheck = allCoupanCodes.filter(code => 
            code.trim().toUpperCase() !== (freeshipInputCode ? freeshipInputCode.trim().toUpperCase() : '') &&
            code.trim().toUpperCase() !== (discountInputCode ? discountInputCode.trim().toUpperCase() : '')
        );

        freeshipCodeExists = codesToCheck.includes(freeshipInputCode?.trim().toUpperCase());
        discountCodeExists = codesToCheck.includes(discountInputCode?.trim().toUpperCase());

        if (freeshipInputCode && discountInputCode &&
            freeshipInputCode.trim().toUpperCase() === discountInputCode.trim().toUpperCase()) {
            shopify.toast.show("Freeship code and discount code can't be same.", { isError: true });
            return false;
        }

        console.log("checkboxFreeship:", checkboxFreeship);
        console.log("allCoupanCodes on first create:", allCoupanCodes);
        console.log("Entered codes:", freeshipInputCode, discountInputCode);
        console.log("freeshipCodeExists:", freeshipCodeExists);
        console.log("discountCodeExists:", discountCodeExists);

        if (freeshipCodeExists || discountCodeExists) {
            console.log('used in last step 1');
            shopify.toast.show("Codes are used in last step", { isError: true });
            perkFormValidations = false;
        }
    }
    
    if (!checkboxValidation) {
        jQuery('.tier_perk_Error_' + sd_selectedtierGroupId).css("display", "block").text("Choose atleast one checkbox from first 5 checkbox");
        shopify.loading(false);
        return false;
    }

    if (!perkFormValidations) {
        shopify.loading(false);
        return false;
    }


    if (perkFormValidations) {
        console.log('next11111111111');
        let idDynamicValue = sd_selectedtierGroupId;
        let freeshipInputFieldId = "free_shipingCode-" + idDynamicValue;
        let discountCoupanCodeInputFieldId = "DiscountProductCode_Field-" + idDynamicValue;
        let freeshipCoupanCode = $("#" + freeshipInputFieldId).val().trim();
        let discountCoupanCode = $("#" + discountCoupanCodeInputFieldId).val().trim();
        let checkFreeArray = false;
        let checkDiscountArray = false;

        // if (freeshipCoupanCode != '') {
        //     checkFreeArray = allCoupanCodes.includes(freeshipCoupanCode);
        // }
        // if (discountCoupanCode != '') {
        //     checkDiscountArray = allCoupanCodes.includes(discountCoupanCode);
        // }

        //  Filter out current values from duplicate list

        let codesToCheck = allCoupanCodes.map(c => c.trim().toUpperCase())
            .filter(code => 
                code != (freeshipCoupanCode ? freeshipCoupanCode.toUpperCase() : '') &&
                code != (discountCoupanCode ? discountCoupanCode.toUpperCase() : '')
            );

        if (freeshipCoupanCode != '') {
            checkFreeArray = codesToCheck.includes(freeshipCoupanCode.toUpperCase());
        }

        if (discountCoupanCode != '') {
            checkDiscountArray = codesToCheck.includes(discountCoupanCode.toUpperCase());
        }

         console.log('checkFreeArray', checkFreeArray);
         console.log('checkFreeArray', checkDiscountArray);


        // if (checkFreeArray) {
        //     //  console.log('FreeShip Code already exists');
        //     $(".ExistingFree_shipingCode_Error").css("display", "block"); 
        //     shopify.loading(false);
        //     return false;
        // } else if (checkDiscountArray) {
        //     //  console.log('Discount Code already exists');
        //     $(".ExistingFree_shipingCode_Error").css("display", "none");
        //     $(".Existing_DiscountCode-Error").css("display", "block");
        //     shopify.loading(false);
        //     return false;
        // }

        if (checkFreeArray) {
            $(".ExistingFree_shipingCode_Error_" + idDynamicValue).css("display", "block");
            shopify.loading(false);
            return false;
        } 
        else if (checkDiscountArray) {
            $(".ExistingFree_shipingCode_Error_" + idDynamicValue).css("display", "none");
            $(".Existing_DiscountCode-Error-" + idDynamicValue).css("display", "block");
            shopify.loading(false);
            return false;
        }

        allCoupanCodes.push(freeshipCoupanCode);
        allCoupanCodes.push(discountCoupanCode);
        
        console.log(allCoupanCodes);
        let data = {'discountCoupanCode': freeshipCoupanCode, 'discountCoupanCode' :discountCoupanCode}
        
        
        // let csrfToken = $('input[name="_token"]').val();
        let ajaxParameters = {

            method: "POST",
            dataValues: {
                data,
                action: "checkCoupansCode"
            }  
        };
       
        shopify.loading(true);
        // let ajaxResult = await ajaxCall('update-popular-plan', fd);
        let response = await AjaxCall(ajaxParameters);

        shopify.loading(false);

        if (response.status == true && response.type == 'freeShipExists') {
            //  console.log('FreeShip Code already exists');
            $(".ExistingFree_shipingCode_Error").css("display", "block");
            return false;
        } else if (response.status == true && response.type == 'discountExists') {
            //  console.log('Discount Code already exists');
            $(".ExistingFree_shipingCode_Error").css("display", "none");
            $(".Existing_DiscountCode-Error").css("display", "block");
            return false;
        } else {
            $(".ExistingFree_shipingCode_Error").css("display", "none");
            $(".Existing_DiscountCode-Error").css("display", "none");

            if (tierGroupId < 0) {
                tierGroupId = 0;
            }
            if (sd_selectedtierGroupIdArray.length > tierGroupId) {
                tierGroupId++;
                sd_selectedtierGroupId = sd_selectedtierGroupIdArray[tierGroupId];
            }

            jQuery('.edit-perks-form-selected').css("display", "none");
            jQuery('.TierSelected-' + sd_selectedtierGroupId).css("display", "block");
            manageButtons(tierGroupId);
            // stepsButton(tierGroupId);
        }
            
           
        
    } else {
        jQuery('.tier_perk_Error_' + sd_selectedtierGroupId).css("display", "block").text("Choose atleast one checkbox from first 5 checkbox");
        shopify.loading(false);
    }
});

previousPerkButton.on("click", function () {
    allCoupanCodes.pop();
    allCoupanCodes.pop();
    if (sd_selectedtierGroupIdArray.length == tierGroupId) {
        tierGroupId--;
    }
    if (sd_selectedtierGroupIdArray.length > tierGroupId) {
        tierGroupId--;
        sd_selectedtierGroupId = sd_selectedtierGroupIdArray[tierGroupId];
    }
    jQuery('.edit-perks-form-selected').css("display", "none");
    jQuery('.TierSelected-' + sd_selectedtierGroupId).css("display", "block");
    manageButtons(tierGroupId);
    console.log(allCoupanCodes);
    // stepsButton(tierGroupId);
});


// manages previous next save button

if (sd_selectedtierGroupIdArray.length == 1) {
    previousPerkButton.hide();
    nextPerkButton.hide();
    savePerkTierButton.show();
}


jQuery('body').on('click', '#save_DiscountButton', async function () {
    let checkboxFreeship = $('.freeShipCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxDiscount = $('.discountCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxFreeProduct = $('.freeProductCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let earlyAccessCheckbox = $('.earlyAccessCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxBirthday = $('.birthdayBox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxCustum = $('.customCheckbox-' + sd_selectedtierGroupId).is(':checked');
    $('.minPurchaseAmount_TextFieldError_' + sd_selectedtierGroupId).hide();
    let freeshipInputCode;
    let discountInputCode;
    let freeshipCodeExists = false;
    let discountCodeExists = false;
    let perkFormValidations = true;
    let checkboxValidation = false;

    jQuery('.sd_perkViewErrors').css("display", "none");
    shopify.loading(true);

    if (checkboxBirthday) {
        checkboxValidation = true;
    }

    // freeshipping validation check....................................... 

    if (checkboxFreeship) {
        checkboxValidation = true;
        amtQtyCheck = $('.minimumDiscountCode-' + sd_selectedtierGroupId).val();

        let amountQty = '';
        if (amtQtyCheck == 'none') {
            perkFormValidations = true;
        }
        else {
            if (amtQtyCheck == 'minPurchase_Amount' || amtQtyCheck == 'minQuantity_items') {
                amountQty = $('.minPurchaseAmount' + sd_selectedtierGroupId).val();
            }
            if (amountQty == '' || amountQty <= 0) {
                $('.minPurchaseAmount_TextFieldError_' + sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }
        if ($('.freeShipValue-' + sd_selectedtierGroupId).val() != '') {
            freeshipInputCode = $('.freeShipValue-' + sd_selectedtierGroupId).val().trim();
            freeshipCodeExists = allCoupanCodes.includes(freeshipInputCode);
            //  console.log(freeshipCodeExists,freeshipInputCode )



        } else {
            jQuery('.free_shipingCode_Error_' + sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }

    // Discount validation check....................................... 

    if (checkboxDiscount) {
        checkboxValidation = true;

        if ($('.discountInputBox-' + sd_selectedtierGroupId).val() != '') {
            discountInputCode = $('.discountInputBox-' + sd_selectedtierGroupId).val().trim();
            discountCodeExists = allCoupanCodes.includes(discountInputCode);
            //  console.log(discountCodeExists,discountInputCode )

        } else {
            jQuery('.Existing_DiscountCode-Error-' + sd_selectedtierGroupId).css("display", "block").text('Discount code is required!');
            perkFormValidations = false;
        }

        let discountPercentageinput = $('#DiscountCodePercentage-' + sd_selectedtierGroupId).val();
        if (discountPercentageinput == 0 || discountPercentageinput == '') {
            jQuery('.DiscountPercentage-Error-' + sd_selectedtierGroupId).css("display", "block").text('Enter percentage value & should be greater than 0');
            perkFormValidations = false;
        } else {

            // console.log('free percentage code value is not empty');
        }

        let freeDiscountProductSelect = $('#PolarisSelect-' + sd_selectedtierGroupId).val();
        if (freeDiscountProductSelect == 'collection') {
            let freeProductId = $('.collectionTitleName-' + sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                jQuery('.sd_selected_existing_collection-Error-' + sd_selectedtierGroupId).css("display", "block").text('Add collection');
                perkFormValidations = false;
            }
        }

        if (freeDiscountProductSelect == 'product') {
            let freeProductId = $('.productTitleName-' + sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                jQuery('.sd_selected_existing_product-Error-' + sd_selectedtierGroupId).css("display", "block").text('Add product');
                perkFormValidations = false;
            }
        }
    }

    // signup Product validation check....................................... 

    if (checkboxFreeProduct) {
        checkboxValidation = true;

        let freeProductId = $('#free_gift-upon-signUp-' + sd_selectedtierGroupId);
        // console.log(freeProductId);
        // console.log(freeProductId.length);
        if (freeProductId.length === 0) {
        } else {
            jQuery('.free_gift-upon-signUp-Error-' + sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }

    // early sale access check....................................... 


    if (earlyAccessCheckbox) {
        checkboxValidation = true;
        let noOfSaleDays = $('#EarlySaleAccessDays-' + sd_selectedtierGroupId).val();
        if (noOfSaleDays.trim() === '' || isNaN(noOfSaleDays) || parseInt(noOfSaleDays) < 0 || parseInt(noOfSaleDays) > 9) {
            jQuery('.EarlySaleAccess-Error-' + sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }


    


    if ((freeshipInputCode && freeshipInputCode.trim() !== '') || 
        (discountInputCode && discountInputCode.trim() !== '')) {

        // Ignore duplicate check for currently entered codes
        let codesToCheck = allCoupanCodes.map(code => code.trim().toUpperCase())
            .filter(code => 
                code !== (freeshipInputCode ? freeshipInputCode.trim().toUpperCase() : '') &&
                code !== (discountInputCode ? discountInputCode.trim().toUpperCase() : '')
            );

          freeshipCodeExists = freeshipInputCode 
            ? codesToCheck.includes(freeshipInputCode.trim().toUpperCase()) 
            : false;

          discountCodeExists = discountInputCode 
            ? codesToCheck.includes(discountInputCode.trim().toUpperCase()) 
            : false;


        if (freeshipInputCode && discountInputCode &&
            freeshipInputCode.trim().toUpperCase() === discountInputCode.trim().toUpperCase()) {
            shopify.toast.show("Freeship code and discount code can't be same.", { isError: true });
            return false;
        }

        

        if (freeshipCodeExists || discountCodeExists) {
            console.log('used in last step 2');
            shopify.toast.show("Codes are used in last step", { isError: true });
            perkFormValidations = false;
        }
    }


    if (!perkFormValidations) {
        shopify.loading(false);
        return false;
    }


    if (perkFormValidations) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        let ErrroPerksCheckbox = false;
        let ErrorBeforeSubmit = false;
        var obj = {};
        let membership_group_ID;

        for (let t_id = 0; t_id < sd_selectedtierGroupIdArray.length; t_id++) {
            let sd_selectedtierGroupId = sd_selectedtierGroupIdArray[t_id];
            let getTier_Value = sd_selectedtierGroupId;
            let Free_shippingCheckbox = jQuery('#PolarisCheckbox2-' + getTier_Value).is(':checked');
            let DiscountProductCollectionCheckbox = jQuery('#PolarisCheckbox3-' + getTier_Value).is(':checked');
            let freeGiftUponsignUpcheckbox = jQuery('#PolarisCheckbox4-' + getTier_Value).is(':checked');
            let customperk = jQuery('#PolarisCheckbox7-' + getTier_Value).is(':checked');
            let birthday_rewards = jQuery('#PolarisCheckbox9-' + getTier_Value).is(':checked');
            let earlyAccessCheck = jQuery('#PolarisCheckbox5-' + getTier_Value).is(':checked');
            let membership_group_ID = jQuery(".TierSelected-" + getTier_Value).attr("membership-group-id");
            let membership_plan_id = jQuery(".TierSelected-" + getTier_Value).attr("membership_plan_id");
            let perks_type_value = jQuery(".TierSelected-" + getTier_Value).attr("perks-type");


            obj[membership_group_ID] = {};
            obj[membership_group_ID]["perks_type_value"] = perks_type_value;
            obj[membership_group_ID]["membership_group_id"] = membership_group_ID;
            obj[membership_group_ID]["membership_plan_id"] = membership_plan_id;
            obj[membership_group_ID]["store"] = shop;


            if (Free_shippingCheckbox == true) {
                if (jQuery('#free_shipingCode-' + getTier_Value).val() == "") {
                    jQuery('.Polaris-Layout__AnnotationContent').hide();
                    jQuery('.TierSelected-' + getTier_Value).show();
                    jQuery('.free_shipingCode_Error_' + getTier_Value).show();
                    jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                    jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                    ErrorBeforeSubmit = true;

                } else {
                    jQuery('.free_shipingCode_Error_' + getTier_Value).hide();
                    obj[membership_group_ID]["free_shipping_checked_value"] = '1';
                    obj[membership_group_ID]["free_shipping_code"] = jQuery('#free_shipingCode-' +
                        getTier_Value).val().trim();
                }

                if (jQuery('.minimumDiscountCode-' + getTier_Value).children("option:selected").val() ==
                    "minPurchase_Amount") {
                    var minPurchaseAmount_TextField_Value = jQuery('#minPurchaseAmount_TextField_' +
                        getTier_Value).val();
                    if (minPurchaseAmount_TextField_Value == "") {

                        jQuery('.Polaris-Layout__AnnotationContent').hide();
                        jQuery('.TierSelected-' + getTier_Value).show();
                        jQuery('.minPurchaseAmount_TextFieldError_' + getTier_Value).show();
                        jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                        jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                        ErrorBeforeSubmit = true;
                    } else {
                        jQuery('.minPurchaseAmount_TextFieldError_' + getTier_Value).hide();
                        obj[membership_group_ID]['freeshipping_selected_value'] = 'min_purchase_amount';
                        obj[membership_group_ID]['min_purchase_amount_value'] =
                            minPurchaseAmount_TextField_Value;
                    }
                } else if (jQuery('.minimumDiscountCode-' + getTier_Value).children("option:selected")
                    .val() == "minQuantity_items") {
                    var maxPurchaseAmount_TextField = jQuery('#maxPurchaseAmount_TextField_' +
                        getTier_Value).val();

                    if (maxPurchaseAmount_TextField == "") {

                        jQuery('.Polaris-Layout__AnnotationContent').hide();
                        jQuery('.TierSelected-' + getTier_Value).show();
                        jQuery('.maxPurchaseAmount_TextFieldError_' + getTier_Value).show();
                        jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                        jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                        ErrorBeforeSubmit = true;
                    } else {
                        jQuery('.maxPurchaseAmount_TextFieldError_' + getTier_Value).hide();
                        obj[membership_group_ID]['freeshipping_selected_value'] = 'min_quantity_items';
                        obj[membership_group_ID]['min_quantity_items'] = maxPurchaseAmount_TextField;
                    }
                } else {
                    obj[membership_group_ID]['freeshipping_selected_value'] = 'none';
                }
                ErrroPerksCheckbox = true;
            }


            if (DiscountProductCollectionCheckbox == true) {
                if (jQuery('#DiscountProductCode_Field-' + getTier_Value).val() == "") {
                    jQuery('.Polaris-Layout__AnnotationContent').hide();
                    jQuery('.TierSelected-' + getTier_Value).show();
                    jQuery('.DiscountCode-Error-' + getTier_Value).show();
                    jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                    jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                    ErrorBeforeSubmit = true;
                } else {
                    obj[membership_group_ID]["discounted_product_collection_checked_value"] = '1';
                    obj[membership_group_ID]['discounted_product_collection_code'] = jQuery(
                        '#DiscountProductCode_Field-' + getTier_Value).val().trim();
                    jQuery('.DiscountCode-Error-' + getTier_Value).hide();
                }

                if (jQuery('#DiscountCodePercentage-' + getTier_Value).val() == "") {
                    jQuery('.Polaris-Layout__AnnotationContent').hide();
                    jQuery('.TierSelected-' + getTier_Value).show();
                    jQuery('.DiscountPercentage-Error-' + getTier_Value).show();
                    jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                    jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                    ErrorBeforeSubmit = true;
                } else {

                    obj[membership_group_ID]['discounted_product_collection_percentageoff'] = jQuery(
                        '#DiscountCodePercentage-' + getTier_Value).val();
                    jQuery('.DiscountPercentage-Error-' + getTier_Value).hide();
                }


                if (jQuery('#PolarisSelect-' + getTier_Value).find(":selected").val() == "all") {

                    obj[membership_group_ID]['discounted_product_collection_type'] = 'N';
                } else if (jQuery('#PolarisSelect-' + getTier_Value).find(":selected").val() ==
                    "collection") {

                    if (jQuery('#collectionDiscountApplied-' + getTier_Value).length) {
                        if (jQuery('.collectionTitleName-' + getTier_Value).length) {

                            let collectionTitleName = jQuery('.collectionTitleName-' + getTier_Value).val();
                            let collectionTitle_id = jQuery('.collectionTitleName-' + getTier_Value).attr('id');
                            obj[membership_group_ID]['discounted_product_collection_type'] = 'C';
                            obj[membership_group_ID]['discounted__collection_title'] = collectionTitleName;
                            obj[membership_group_ID]['discounted__collection_id'] = collectionTitle_id;
                            jQuery('.sd_selected_existing_collection-Error-' + getTier_Value).hide();
                        } else {
                            jQuery('.Polaris-Layout__AnnotationContent').hide();
                            jQuery('.TierSelected-' + getTier_Value).show();
                            jQuery('.sd_selected_existing_collection-Error-' + getTier_Value).show();
                            jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                            jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                            ErrorBeforeSubmit = true;
                        }
                    }
                } else {

                    if (jQuery('#specificProduct_DiscountApplied-' + getTier_Value).length) {
                        if (jQuery('.productTitleName-' + getTier_Value).length) {
                            let productTitleName = jQuery('.productTitleName-' + getTier_Value).val();
                            let productTitle_id = jQuery('.productTitleName-' + getTier_Value).attr(
                                'id');
                            obj[membership_group_ID]['discounted_product_collection_type'] = 'P';
                            obj[membership_group_ID]['discounted__product_title'] = productTitleName;
                            obj[membership_group_ID]['discounted__product_id'] = productTitle_id;
                            jQuery('.sd_selected_existing_product-Error-' + getTier_Value).hide();
                        } else {
                            jQuery('.Polaris-Layout__AnnotationContent').hide();
                            jQuery('.TierSelected-' + getTier_Value).show();
                            jQuery('.sd_selected_existing_product-Error-' + getTier_Value).show();
                            jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                            jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                            ErrorBeforeSubmit = true;
                        }
                    }
                }
                ErrroPerksCheckbox = true;
            } else {
                obj[membership_group_ID]['discounted_product_collection_type'] = 'N';
            }



            if (freeGiftUponsignUpcheckbox == true) {
                if (jQuery('#free_gift-upon-signUp-' + getTier_Value).length) {
                    jQuery('.Polaris-Layout__AnnotationContent').hide();
                    jQuery('.TierSelected-' + getTier_Value).show();
                    jQuery('.free_gift-upon-signUp-Error-' + getTier_Value).show();
                    jQuery(".Polaris-Select__SelectedOptionData").text(getTier_Value);
                    jQuery("#PolarisSelect1").val(getTier_Value).attr("selected", "selected");
                    ErrorBeforeSubmit = true;

                } else {
                    jQuery('.free_gift-upon-signUp-Error-' + getTier_Value).hide();
                    let Free_gift_uponsignup_productName = jQuery('#Free-gift_uponsignup_productName-' + getTier_Value).val();
                    let gift_uponsignup_variantName = jQuery('#Free-gift_uponsignup_variantName-' + getTier_Value).val();
                    let free_gift_uponsignupSelectedDays = jQuery('#free_gift_uponsignupSelectedDays-' + getTier_Value).find(":selected").val();
                    if (free_gift_uponsignupSelectedDays == 'Immediately after signup') {
                        free_gift_uponsignupSelectedDays = 'Immediately_after_signup'
                    }
                    let perk_free_gift_product_id = jQuery('#perk-free_gift-product_id-' + getTier_Value).val();
                    let perk_free_gift_variant_id = $('input[free-gift_selected_variantid]').attr('free-gift_selected_variantid');
                    let free_gift_uponsignupSelected_Value = jQuery('#free_gift_uponsignupSelectedDays-' + getTier_Value).find(":selected").attr('free_gift_uponsignupselected_value');
                    obj[membership_group_ID]['Free_gift_uponsignup_checkbox'] = '1';
                    obj[membership_group_ID]['Free_gift_uponsignup_productName'] = Free_gift_uponsignup_productName;
                    obj[membership_group_ID]['gift_uponsignup_variantName'] = gift_uponsignup_variantName;
                    obj[membership_group_ID]['free_gift_uponsignupSelectedDays'] = free_gift_uponsignupSelectedDays;
                    obj[membership_group_ID]['free_gift_uponsignupSelected_Value'] = free_gift_uponsignupSelected_Value;
                    obj[membership_group_ID]['perk_free_gift_product_id'] = perk_free_gift_product_id;
                    obj[membership_group_ID]['perk_free_gift_variant_id'] = perk_free_gift_variant_id;
                }
                ErrroPerksCheckbox = true;
            }




            if (earlyAccessCheck == true) {
                obj[membership_group_ID]['early_access_checked_value'] = '1';
                obj[membership_group_ID]['no_of_sale_days'] = jQuery('#EarlySaleAccessDays-' + getTier_Value).val().trim();
                ErrroPerksCheckbox = true;
            }

            if (customperk == true) {
                obj[membership_group_ID]['custom_perk_checkbox'] = '1';
                ErrroPerksCheckbox = true;
            }

            if (birthday_rewards == true) {
                obj[membership_group_ID]['birthday_rewards'] = '1';
                ErrroPerksCheckbox = true;
            }


        }


        if (ErrroPerksCheckbox == false) {
            // console.log('select atleast one checkbox!');
        } else {
            if (ErrorBeforeSubmit == true) {
                // console.log('error');
            } else {

                shopify.loading(true);
                jQuery("#save_DiscountButton").attr("disabled", true);
                let data = { obj: obj, store: shop, shop: shop }
              
                
                let ajaxParameters = {

                    method: "POST",

                    dataValues: {
                        data,
                        action: "membershipPerksSave"

                    }
                };

                let result = await AjaxCall(ajaxParameters);
                var beforeChecked = result.beforeChecked;
                let message = result.message;
                var status = result.status;
                if (status == false) {
                    jQuery("#save_DiscountButton").attr("disabled", false);
                    shopify.loading(false);
                    shopify.toast.show("Data saved successfully", { isError: false });
                    let app_redirect_link = `${SHOPIFY_DOMAIN_URL}/admin/memberships/memberships.php?shop=${shop}&host=${host}`;
                    open(app_redirect_link, '_self');
                } else if (status == true) {
                    jQuery("#save_DiscountButton").attr("disabled", false);
                    shopify.loading(false);
                    shopify.toast.show("Data saved successfully", { isError: false });
                    return false;
                } else if (status == 'exists') {
                    // console.log("exists");
                    shopify.loading(false);
                    jQuery("#save_DiscountButton").attr("disabled", false);
                    if (beforeChecked == 'free_shipping_code') {
                        jQuery('.Polaris-Layout__AnnotationContent').hide();
                        jQuery('.Polaris-Layout__AnnotationContent[membership-group-id="' +
                            message + '"]').show();
                        var perks_type = jQuery(
                            '.Polaris-Layout__AnnotationContent[membership-group-id="' +
                            message + '"]').attr("perks-type");
                        jQuery(".Polaris-Select__SelectedOptionData").text(perks_type);
                        jQuery("#PolarisSelect1").val(perks_type).attr("selected",
                            "selected");
                        jQuery("#save_DiscountButton").attr("disabled", false);
                        jQuery('.ExistingFree_shipingCode_Error').hide();
                        jQuery('.ExistingFree_shipingCode_Error_' + perks_type).fadeOut(500)
                            .fadeIn(500);
                    }
                    if (beforeChecked == 'discountCode') {
                        shopify.loading(false);

                        jQuery('.Polaris-Layout__AnnotationContent').hide();
                        jQuery('.Polaris-Layout__AnnotationContent[membership-group-id="' +
                            message + '"]').show();
                        var perks_type = jQuery(
                            '.Polaris-Layout__AnnotationContent[membership-group-id="' +
                            message + '"]').attr("perks-type");
                        jQuery(".Polaris-Select__SelectedOptionData").text(perks_type);
                        jQuery("#PolarisSelect1").val(perks_type).attr("selected",
                            "selected");
                        jQuery("#save_DiscountButton").attr("disabled", false);
                        jQuery('.Existing_DiscountCode-Error-' + perks_type).fadeOut(500)
                            .fadeIn(500);
                    }
                }
                
                
            }
        }
    }
    else {
        // console.log('code 1');
        shopify.toast.show("Please check atleast one checkbox from first 5 checkbox", { isError: true });
        nextEditButtonCondition = false;
    }
});


// for edit perk page ................................................................................................\\\\\\\\\\\\\\\\\\\\\\\\\\\\
var Edit_sd_selectedtierGroupIdArray = [];
for (let tierGid = 0; tierGid < 3; tierGid++) {
    let Edit_sd_selectedtierGroupId = $('.Edit_tierGroupIdArray' + tierGid).val();
    if (Edit_sd_selectedtierGroupId != '') {
        Edit_sd_selectedtierGroupIdArray.push(Edit_sd_selectedtierGroupId);
    }
}

var Edit_tierGroupId = 0;
var Edit_sd_selectedtierGroupId = Edit_sd_selectedtierGroupIdArray[tierGroupId];
jQuery('.edit-TierSelected-' + Edit_sd_selectedtierGroupId).css("display", "block");
var editCoupanCodes = [];
var nextEditPerkButton = $("body #next_EditDiscountButton");
var previousEditPerkButton = $("#previous_EditDiscountButton");
var editPerkTierButton = $("#edit_DiscountButton");

// next button code for edit perk page...........................................................................................\\\\\\\\\\\\\\\\\\

nextEditPerkButton.on("click", function () {
    console.log('hello next');
    console.log(Edit_sd_selectedtierGroupId,'Edit_sd_selectedtierGroupId')
    let checkboxFreeship = $('body .freeShipEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxDiscount = $('body .discountEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxFreeProduct = $('body .freeProductEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let earlyAccessCheckbox = $('body .earlyAccessEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxBirthday = $('body .birthdayEditCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxCustum = $('body .customEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let freeshipInputCode;
    let discountInputCode;
    let freeshipCodeExists = false;
    let discountCodeExists = false;
    let nextButtonCondition = false;
    let perkFormValidations = true;
    let checkboxValidation = false;


    jQuery('body .sd_perkViewErrors').css("display", "none");
    shopify.loading(true);
    if (checkboxBirthday) {
        checkboxValidation = true;
    }

    // freeshipping validation check....................................... 

    if (checkboxFreeship) {
        checkboxValidation = true;
        freeshipInputCode = $('body .freeShipEditValue-' + Edit_sd_selectedtierGroupId);
        freeshipInputCode = freeshipInputCode.val().trim();

        let amtQtyCheck = $('#Edit-minimumDiscountCode-' + Edit_sd_selectedtierGroupId).val();
        // console.log('amtQtyCheck',amtQtyCheck);
        if (amtQtyCheck == 'min_quantity_items' || amtQtyCheck == 'min_purchase_amount') {
            let amountQty = $('body .editMinPurchaseAmount' + Edit_sd_selectedtierGroupId).val();
            if (amountQty == '' || amountQty <= 0) {
                $('body .Edit-minPurchaseAmount_TextFieldError_' + Edit_sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }


        if (freeshipInputCode.length !== 0) {
            freeshipCodeExists = editCoupanCodes.includes(freeshipInputCode);

            nextButtonCondition = true;
        }
        else {
            $('body .Edit-free_shipingCode_Error_' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }
    }
    // Discount validation check....................................... 

    if (checkboxDiscount) {
        checkboxValidation = true;

        if ($('#Edit-DiscountProductCode_Field-' + Edit_sd_selectedtierGroupId).val().trim() != '') {
            discountInputCode = $('#Edit-DiscountProductCode_Field-' + Edit_sd_selectedtierGroupId).val().trim();
            nextButtonCondition = true;
        } else {
            $('body .Edit-DiscountCode-Error-' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }

        let discountPercentageinput = $('#Edit-DiscountCodePercentage-' + Edit_sd_selectedtierGroupId).val();
        if (discountPercentageinput == 0 || discountPercentageinput == '') {
            $('body .Edit-DiscountPercentage-Error-' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }

        let freeDiscountProductSelect = $('#edit-PolarisSelect-' + Edit_sd_selectedtierGroupId).val();
        if (freeDiscountProductSelect == 'collection') {
            let freeProductId = $('body .edit-collectionTitleName-' + Edit_sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                $('body .edit-sd_selected_existing_collection-Error-' + Edit_sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }

        if (freeDiscountProductSelect == 'product') {
            let freeProductId = $('body .edit-productTitleName-' + Edit_sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                $('body .edit-sd_selected_existing_product-Error-' + Edit_sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }

        if (discountInputCode.length !== 0) {
            discountCodeExists = editCoupanCodes.includes(discountInputCode);

            nextButtonCondition = true;
        }
        else {
            $('body .Edit-free_shipingCode_Error_' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }

    }
    // signup Product validation check....................................... 
    if (checkboxFreeProduct) {
        checkboxValidation = true;
        let freeProductId = $('#edit-free_gift-upon-signUp-' + Edit_sd_selectedtierGroupId);
        if (!(freeProductId.length === 0)) {
            jQuery('body .edit-free_gift-upon-signUp-Error-' + Edit_sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }


    // early sale access check....................................... 
    if (earlyAccessCheckbox) {
        checkboxValidation = true;
        let noOfSaleDays = $('#edit-EarlySaleAccessDays-' + Edit_sd_selectedtierGroupId).val();
        if (noOfSaleDays.trim() === '' || isNaN(noOfSaleDays) || parseInt(noOfSaleDays) < 0 || parseInt(noOfSaleDays) > 9) {
            jQuery('body .edit-EarlySaleAccess-Error-' + Edit_sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }



    if (freeshipInputCode != undefined || discountInputCode != undefined) {

        if (freeshipInputCode == discountInputCode) {
            shopify.toast.show("Freeship code and discount code can't be same", { isError: true });
            perkFormValidations = false;
            return false;
        }

        if (freeshipCodeExists || discountCodeExists) {
            shopify.toast.show("Codes are used in last step", { isError: true });
            perkFormValidations = false;
        }
    }

    if (!checkboxValidation) {
        jQuery('body .edit_tier_perk_Error_' + Edit_sd_selectedtierGroupId).css("display", "block").text("Choose atleast one checkbox from first 5 checkbox");
        shopify.loading(false);
        return false;
    }

    if (!perkFormValidations) {
        shopify.loading(false);
        return false;
    }


    if (perkFormValidations) {
        // console.log('Validation true');
        Edit_tierGroupId++;
        Edit_sd_selectedtierGroupId = Edit_sd_selectedtierGroupIdArray[Edit_tierGroupId];
        jQuery('body .edit-perks-form-selected').css("display", "none");
        jQuery('body .edit-TierSelected-' + Edit_sd_selectedtierGroupId).css("display", "block");
        manageEditButtons(Edit_tierGroupId);
        if (!freeshipCodeExists && !discountCodeExists) {
            if (freeshipInputCode != undefined) {
                editCoupanCodes.push(freeshipInputCode);
            }
            if (discountInputCode != undefined) {
                editCoupanCodes.push(discountInputCode);
            }
        }
        console.log(editCoupanCodes);
        shopify.loading(false);
    } else {
        // console.log('code 2');
        shopify.toast.show("Please check atleast one checkbox from first 5 checkbox", { isError: true });
        shopify.loading(false);
    }
});

// previous button code for edit perk page.......................................................................................\\\\\\\\\\\\\\\\\\\\\\\\\\\

previousEditPerkButton.on("click", function () {
    Edit_tierGroupId--;
    Edit_sd_selectedtierGroupId = Edit_sd_selectedtierGroupIdArray[Edit_tierGroupId];
    let checkboxFreeship = $('.freeShipEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxDiscount = $('.discountEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let freeshipInputCode;
    let discountInputCode;

    if (checkboxFreeship) {
        freeshipInputCode = $('.freeShipEditValue-' + Edit_sd_selectedtierGroupId);
        freeshipInputCode = freeshipInputCode.val().trim();
    }

    if (checkboxDiscount) {
        discountInputCode = $('.discountEditValue-' + Edit_sd_selectedtierGroupId);
        discountInputCode = discountInputCode.val().trim();
    }

    if (editCoupanCodes.includes(freeshipInputCode)) {
        let removeElement = editCoupanCodes.indexOf(freeshipInputCode)
        editCoupanCodes.splice(removeElement, 1)
    }
    if (editCoupanCodes.includes(discountInputCode)) {
        let removeElement = editCoupanCodes.indexOf(discountInputCode)
        editCoupanCodes.splice(removeElement, 1)
    }


    if (Edit_sd_selectedtierGroupIdArray.length == Edit_tierGroupId) {
        Edit_tierGroupId--;
    }

    jQuery('.edit-perks-form-selected').css("display", "none");
    jQuery('.edit-TierSelected-' + Edit_sd_selectedtierGroupId).css("display", "block");
    manageEditButtons(Edit_tierGroupId);
    console.log(editCoupanCodes);
});


if (Edit_sd_selectedtierGroupIdArray.length == 1) {
    previousEditPerkButton.hide();
    nextEditPerkButton.hide();
    editPerkTierButton.show();
}

// edit Perk save button ................................................................................................................\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
jQuery('body').on('click', '#edit_DiscountButton', async function () {
    console.log('edit discount');
    let checkboxFreeship = $('.freeShipEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxDiscount = $('.discountEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxFreeProduct = $('.freeProductEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let earlyAccessCheckbox = $('.earlyAccessEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let checkboxBirthday = $('.birthdayEditCheckbox-' + sd_selectedtierGroupId).is(':checked');
    let checkboxCustum = $('.customEditCheckbox-' + Edit_sd_selectedtierGroupId).is(':checked');
    let freeshipInputCode;
    let discountInputCode;
    let freeshipCodeExists = false;
    let discountCodeExists = false;
    let nextButtonCondition = false;
    let perkFormValidations = true;
    let checkboxValidation = false;

    jQuery('.sd_perkViewErrors').css("display", "none");
    shopify.loading(true);
    if (checkboxBirthday) {
        checkboxValidation = true;
    }
    // freeshipping validation check....................................... 
    if (checkboxFreeship) {
        checkboxValidation = true;
        freeshipInputCode = $('.freeShipEditValue-' + Edit_sd_selectedtierGroupId);
        freeshipInputCode = freeshipInputCode.val().trim();

        let amtQtyCheck = $('#Edit-minimumDiscountCode-' + Edit_sd_selectedtierGroupId).val();
        // console.log('amtQtyCheck',amtQtyCheck);
        if (amtQtyCheck == 'min_quantity_items' || amtQtyCheck == 'min_purchase_amount') {
            let amountQty = $('.editMinPurchaseAmount' + Edit_sd_selectedtierGroupId).val();
            if (amountQty == '' || amountQty <= 0) {
                $('.Edit-minPurchaseAmount_TextFieldError_' + Edit_sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }

        if (freeshipInputCode.length !== 0) {
            freeshipCodeExists = editCoupanCodes.includes(freeshipInputCode);
            nextButtonCondition = true;
        }
        else {
            $('.Edit-free_shipingCode_Error_' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }
    }
    // Discount validation check....................................... 

    if (checkboxDiscount) {
        checkboxValidation = true;

        if ($('#Edit-DiscountProductCode_Field-' + Edit_sd_selectedtierGroupId).val().trim() != '') {
            discountInputCode = $('#Edit-DiscountProductCode_Field-' + Edit_sd_selectedtierGroupId).val().trim();
            discountCodeExists = editCoupanCodes.includes(discountInputCode);

            nextButtonCondition = true;
        }
        else {
            $('.Edit-DiscountCode-Error-' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }

        let discountPercentageinput = $('#Edit-DiscountCodePercentage-' + Edit_sd_selectedtierGroupId).val();
        if (discountPercentageinput == 0 || discountPercentageinput == '') {
            $('.Edit-DiscountPercentage-Error-' + Edit_sd_selectedtierGroupId).show();
            perkFormValidations = false;
        }

        let freeDiscountProductSelect = $('#edit-PolarisSelect-' + Edit_sd_selectedtierGroupId).val();
        if (freeDiscountProductSelect == 'collection') {
            let freeProductId = $('.edit-collectionTitleName-' + Edit_sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                $('.edit-sd_selected_existing_collection-Error-' + Edit_sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }

        if (freeDiscountProductSelect == 'product') {
            let freeProductId = $('.edit-productTitleName-' + Edit_sd_selectedtierGroupId);
            if (freeProductId.length === 0) {
                $('.edit-sd_selected_existing_product-Error-' + Edit_sd_selectedtierGroupId).show();
                perkFormValidations = false;
            }
        }

    }
    // signup Product validation check....................................... 
    if (checkboxFreeProduct) {
        checkboxValidation = true;
        let freeProductId = $('#edit-free_gift-upon-signUp-' + Edit_sd_selectedtierGroupId);
        if (!(freeProductId.length === 0)) {
            jQuery('.edit-free_gift-upon-signUp-Error-' + Edit_sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }


    // early sale access check....................................... 
    if (earlyAccessCheckbox) {
        checkboxValidation = true;
        let noOfSaleDays = $('#edit-EarlySaleAccessDays-' + Edit_sd_selectedtierGroupId).val();
        if (noOfSaleDays.trim() === '' || isNaN(noOfSaleDays) || parseInt(noOfSaleDays) < 0 || parseInt(noOfSaleDays) > 9) {
            jQuery('.edit-EarlySaleAccess-Error-' + Edit_sd_selectedtierGroupId).css("display", "block");
            perkFormValidations = false;
        }
    }


    if (freeshipInputCode != undefined || discountInputCode != undefined) {
        if (freeshipInputCode == discountInputCode) {
            shopify.toast.show("Freeship code and discount code can't be same", { isError: true });
            perkFormValidations = false;
            return false;
        }
        if (freeshipCodeExists || discountCodeExists) {
            shopify.toast.show("Codes are used in last step", { isError: true });
            perkFormValidations = false;
        }
    }

    if (!checkboxValidation) {
        // console.log('hello connnnnnn')
        jQuery('.edit_tier_perk_Error_' + Edit_sd_selectedtierGroupId).css("display", "block").text("Choose atleast one checkbox from first 5 checkbox");
        shopify.loading(false);
        return false;
    }

    if (!perkFormValidations) {
        // console.log('Validation false');
        shopify.loading(false);
        return false;
    }

    shopify.loading(false);


    if (perkFormValidations) {
        editCoupanCodes.push(freeshipInputCode);
        editCoupanCodes.push(discountInputCode);

        let obj = {
            "edit_Tier_Value": [],
            "free_shipping": [],
            "discount_product_collection": [],
            "Free_gift_upon_signup": [],
            "early_sale_access": [],
            "birthday_rewards": [],
            "custom_perk": []
        }
        let flag = true;
        // jQuery("#selected-perks-data > option").each(function() {
        let totalEditPerks = Edit_sd_selectedtierGroupIdArray.length;

        for (let x = 0; x < totalEditPerks; x++) {

            let edit_Tier_Value = Edit_sd_selectedtierGroupIdArray[x];
            // console.log(edit_Tier_Value);
            let edit_updated_id = jQuery('.edit-TierSelected-' + edit_Tier_Value).attr('edit_updated_id');
            obj.edit_Tier_Value.push({
                'edit_Tier_Value': edit_Tier_Value
            });
            let old_edit_free_shipingCode = jQuery(`#old_edit-free_shipingCode-${edit_Tier_Value}`).val().trim();
            let edit_free_shipping_price_rule_ID = jQuery(`#edit-free_shipping_price_rule_ID-${edit_Tier_Value}`).val().trim();
            let old_Edit_DiscountProductCode_Field = jQuery(`#old_Edit-DiscountProductCode_Field-${edit_Tier_Value}`).val().trim();
            let old_Edit_DiscountProductCode_price_ruleID = jQuery(`#Edit-DiscountProductCode_Field_price_rule_ID-${edit_Tier_Value}`).val();

            //freeshiping code...........................................................................................................................................

            if (jQuery('#edit-PolarisCheckbox2-' + edit_Tier_Value).is(':checked')) {
                let edit_free_shipingCode = jQuery(`#edit-free_shipingCode-${edit_Tier_Value}`).val().trim();
                let check_purchase_amount_value = jQuery('#Edit-minimumDiscountCode-' + edit_Tier_Value + ' option:selected').val();
                let edit_free_shipping_checked_value = '1';
                let min_purchase_amount_value = '';
                let max_purchase_amount_value = '';
                let none_purchase_amount_value = '';

                if (edit_free_shipingCode.trim() === "") {
                    jQuery('.edit-perks-form-selected').hide();
                    jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                    jQuery(`.Edit-free_shipingCode_Error_${edit_Tier_Value}`).show();
                    jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                    jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected", "selected");
                    flag = false;
                    removeElement = editCoupanCodes.indexOf(freeshipInputCode)
                    editCoupanCodes.splice(removeElement, 1)

                    removeElement = editCoupanCodes.indexOf(discountInputCode)
                    editCoupanCodes.splice(removeElement, 1)
                } else {
                    jQuery(`.Edit-free_shipingCode_Error_${edit_Tier_Value}`).hide();
                }

                if (check_purchase_amount_value == "min_purchase_amount") {
                    min_purchase_amount_value = jQuery(`#edit-minPurchaseAmount_TextField_${edit_Tier_Value}`).val()
                    if (min_purchase_amount_value.trim() === "") {
                        //form selected
                        jQuery('.edit-perks-form-selected').hide();
                        jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                        jQuery(`.Edit-minPurchaseAmount_TextFieldError_${edit_Tier_Value}`).show();
                        //tier selected select box
                        jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                        jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected",
                            "selected");
                        let removeElement = editCoupanCodes.indexOf(freeshipInputCode)
                        editCoupanCodes.splice(removeElement, 1)
                        removeElement = editCoupanCodes.indexOf(discountInputCode)
                        editCoupanCodes.splice(removeElement, 1)
                        flag = false;

                    } else {
                        jQuery(`.Edit-minPurchaseAmount_TextFieldError_${edit_Tier_Value}`).hide();
                    }
                } else if (check_purchase_amount_value == "min_quantity_items") {
                    max_purchase_amount_value = jQuery(
                        `#edit-maxPurchaseAmount_TextField_${edit_Tier_Value}`).val();
                    if (max_purchase_amount_value.trim() === "") {
                        //form selected
                        jQuery('.edit-perks-form-selected').hide();
                        jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                        jQuery(`.Edit-maxPurchaseAmount_TextFieldError_${edit_Tier_Value}`).show();
                        //tier selected select box
                        jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                        jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected",
                            "selected");
                        let removeElement = editCoupanCodes.indexOf(freeshipInputCode)
                        editCoupanCodes.splice(removeElement, 1)
                        removeElement = editCoupanCodes.indexOf(discountInputCode)
                        editCoupanCodes.splice(removeElement, 1)
                        flag = false;

                    } else {
                        jQuery(`.Edit-maxPurchaseAmount_TextFieldError_${edit_Tier_Value}`).hide();
                    }
                }
                obj.free_shipping.push({
                    'edit_updated_id': edit_updated_id,
                    'old_edit_free_shipingCode': old_edit_free_shipingCode,
                    'edit_free_shipping_price_rule_ID': edit_free_shipping_price_rule_ID,
                    'edit_free_shipping_checked_value': edit_free_shipping_checked_value,
                    'edit_shipping_code_value': edit_free_shipingCode,
                    'check_purchase_amount_value': check_purchase_amount_value,
                    'min_purchase_amount_value': min_purchase_amount_value,
                    'min_quantity_items': max_purchase_amount_value
                });
            } else {
                obj.free_shipping.push({
                    'edit_updated_id': edit_updated_id,
                    'old_edit_free_shipingCode': old_edit_free_shipingCode,
                    'edit_free_shipping_price_rule_ID': edit_free_shipping_price_rule_ID,
                    'edit_free_shipping_checked_value': '',
                    'edit_shipping_code_value': '',
                    'check_purchase_amount_value': '',
                    'min_purchase_amount_value': '',
                    'min_quantity_items': ''
                });
            }

            //Discount coupan ............................................................................................................................................

            if (jQuery('#edit-PolarisCheckbox3-' + edit_Tier_Value).is(':checked')) {
                let product_collectionCode_Field_code_value = jQuery(`#Edit-DiscountProductCode_Field-${edit_Tier_Value}`).val().trim();
                let product_collectionCode_Field_percentage_value = jQuery(`#Edit-DiscountCodePercentage-${edit_Tier_Value}`).val();
                let checkededit_PolarisSelected_value = jQuery('#edit-PolarisSelect-' + edit_Tier_Value + ' option:selected').val();
                let edit_discounted_product_collection_checked_value = '1';
                let discounted_product_collection_type = '';
                let productTitle_id = '';
                let collectionTitle_id = '';
                let edit_collectionTitleName = '';
                let edit_productTitleName = '';

                if (product_collectionCode_Field_code_value.trim() === "") {
                    //form selected
                    jQuery('.edit-perks-form-selected').hide();
                    jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                    jQuery(`.Edit-DiscountCode-Error-${edit_Tier_Value}`).show();
                    //tier selected select box
                    jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                    jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected", "selected");
                    flag = false;

                } else {
                    jQuery(`.Edit-DiscountCode-Error-${edit_Tier_Value}`).hide();
                }

                if (product_collectionCode_Field_percentage_value.trim() === "") {
                    //form selected
                    jQuery('.edit-perks-form-selected').hide();
                    jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                    jQuery(`.Edit-DiscountPercentage-Error-${edit_Tier_Value}`).show();
                    //tier selected select box
                    jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                    jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected", "selected");
                    flag = false;

                } else {
                    jQuery(`.Edit-DiscountPercentage-Error-${edit_Tier_Value}`).hide();
                }

                if (checkededit_PolarisSelected_value == 'collection') {
                    if ($("#edit-collectionDiscountApplied-" + edit_Tier_Value).length == 0) {
                        discounted_product_collection_type = 'C';
                        collectionTitle_id = jQuery('.edit-collectionTitleName-' + edit_Tier_Value).attr('id');
                        edit_collectionTitleName = jQuery(`.edit-collectionTitleName-${edit_Tier_Value}`).val();
                        jQuery(`.edit-sd_selected_existing_collection-Error-${edit_Tier_Value}`).hide();
                    } else {
                        //form selected
                        jQuery('.edit-perks-form-selected').hide();
                        jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                        jQuery(`.edit-sd_selected_existing_collection-Error-${edit_Tier_Value}`).show();
                        //tier selected select box
                        jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                        jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected", "selected");
                        flag = false;
                    }
                } else if (checkededit_PolarisSelected_value == 'product') {
                    if ($("#edit-specificProduct_DiscountApplied-" + edit_Tier_Value).length == 0) {
                        discounted_product_collection_type = 'P';
                        productTitle_id = jQuery('.edit-productTitleName-' + edit_Tier_Value).attr('id');
                        edit_productTitleName = jQuery(`.edit-productTitleName-${edit_Tier_Value}`).val();
                        jQuery(`.edit-sd_selected_existing_product-Error-${edit_Tier_Value}`).hide();

                    } else {

                        //form selected
                        jQuery('.edit-perks-form-selected').hide();
                        jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                        jQuery(`.edit-sd_selected_existing_product-Error-${edit_Tier_Value}`).show();
                        //tier selected select box
                        jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                        jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected", "selected");
                        flag = false;
                    }
                } else {
                    discounted_product_collection_type = 'N';
                }
                obj.discount_product_collection.push({
                    'edit_updated_id': edit_updated_id,
                    'edit_discounted_product_collection_checked_value': edit_discounted_product_collection_checked_value,
                    'old_Edit_DiscountProductCode_Field': old_Edit_DiscountProductCode_Field,
                    'old_Edit_DiscountProductCode_price_ruleID': old_Edit_DiscountProductCode_price_ruleID,
                    'product_collectionCode_Field_code_value': product_collectionCode_Field_code_value,
                    'product_collectionCode_Field_percentage_value': product_collectionCode_Field_percentage_value,
                    'discounted_product_collection_type': discounted_product_collection_type,
                    'edit_collectionTitleName': edit_collectionTitleName,
                    'collectionTitle_id': collectionTitle_id,
                    'edit_productTitleName': edit_productTitleName,
                    'productTitle_id': productTitle_id
                });
            } else {
                obj.discount_product_collection.push({
                    'edit_updated_id': '',
                    'edit_discounted_product_collection_checked_value': '',
                    'old_Edit_DiscountProductCode_Field': old_Edit_DiscountProductCode_Field,
                    'old_Edit_DiscountProductCode_price_ruleID': old_Edit_DiscountProductCode_price_ruleID,
                    'product_collectionCode_Field_code_value': '',
                    'product_collectionCode_Field_percentage_value': '',
                    'discounted_product_collection_type': '',
                    'edit_collectionTitleName': '',
                    'collectionTitle_id': '',
                    'edit_productTitleName': '',
                    'productTitle_id': ''
                });
            }
            //free sign up gift ...........................................................................................................................................

            if (jQuery('#edit-PolarisCheckbox4-' + edit_Tier_Value).is(':checked')) {

                if ($("#edit-free_gift-upon-signUp-" + edit_Tier_Value).length == 0) {
                    let edit_Free_gift_uponsignup_checkbox = '1';
                    let free_gift_productTitle = jQuery(`#edit-Free-gift_uponsignup_productName-${edit_Tier_Value}`).val();
                    let Free_gift_uponsignup_variantName = jQuery(`#Free-gift_uponsignup_variantName-${edit_Tier_Value}`).val();
                    let edit_free_gift_uponsignupSelectedDays = jQuery('#edit-free_gift_uponsignupSelectedDays-' + edit_Tier_Value + ' option:selected').val();
                    if (edit_free_gift_uponsignupSelectedDays == 'Immediately after signup') {
                        edit_free_gift_uponsignupSelectedDays = 'Immediately_after_signup'
                    }
                    let edit_free_gift_uponsignupSelectedValue = jQuery(
                        '#edit-free_gift_uponsignupSelectedDays-' + edit_Tier_Value + ' option:selected').attr('edit-free_gift_uponsignupselected_value');

                    let edit_free_gift_selected_productid = jQuery(
                        `#edit-Free-gift_uponsignup_productName-${edit_Tier_Value}`).attr('edit-free-gift_selected_productid');
                    let edit_free_gift_selected_variantid = jQuery(`#Free-gift_uponsignup_variantName-${edit_Tier_Value}`).attr('edit-free-gift_selected_variantid');

                    jQuery(`.edit-free_gift-upon-signUp-Error-${edit_Tier_Value}`).hide();
                    obj.Free_gift_upon_signup.push({
                        'edit_Free_gift_uponsignup_checkbox': edit_Free_gift_uponsignup_checkbox,
                        'free_gift_productTitle': free_gift_productTitle,
                        'edit_free_gift_selected_productid': edit_free_gift_selected_productid,
                        'Free_gift_uponsignup_variantName': Free_gift_uponsignup_variantName,
                        'edit_free_gift_selected_variantid': edit_free_gift_selected_variantid,
                        'edit_free_gift_uponsignupSelectedDays': edit_free_gift_uponsignupSelectedDays,
                        'edit_free_gift_uponsignupSelectedValue': edit_free_gift_uponsignupSelectedValue
                    });
                } else {

                    //form selected
                    jQuery('.edit-perks-form-selected').hide();
                    jQuery(`.edit-TierSelected-${edit_Tier_Value}`).show();
                    jQuery(`.edit-free_gift-upon-signUp-Error-${edit_Tier_Value}`).show();
                    //tier selected select box
                    jQuery(".edit-Polaris-Select__SelectedOptionData").text(edit_Tier_Value);
                    jQuery("#selected-perks-data").val(edit_Tier_Value).attr("selected", "selected");
                    flag = false;
                }
            } else {
                obj.Free_gift_upon_signup.push({
                    'edit_Free_gift_uponsignup_checkbox': '',
                    'free_gift_productTitle': '',
                    'edit_free_gift_selected_productid': '',
                    'Free_gift_uponsignup_variantName': '',
                    'edit_free_gift_selected_variantid': '',
                    'edit_free_gift_uponsignupSelectedDays': '',
                    'edit_free_gift_uponsignupSelectedValue': ''
                });
            }



            //early accesss...........................................................................................................................................
            if (jQuery('#edit-PolarisCheckbox5-' + edit_Tier_Value).is(':checked')) {
                let no_of_sale_days = jQuery(`#edit-EarlySaleAccessDays-${edit_Tier_Value}`).val();
                obj.early_sale_access.push({
                    'no_of_sale_days': no_of_sale_days,
                    'early_access_checked_value': '1',
                });
            } else {
                obj.early_sale_access.push({
                    'edit_updated_id': edit_updated_id,
                    'early_access_checked_value': '0',
                });
            }

            // custom perk.........................................................................

            if (jQuery('#edit-PolarisCheckbox7-' + edit_Tier_Value).is(':checked')) {
                obj.custom_perk.push({
                    'checked_custom_perk': '1'
                });
            } else {
                obj.custom_perk.push({
                    'checked_custom_perk': null
                });
            }


            // birthday_rewards .........................................................................

            if (jQuery('#edit-PolarisCheckbox9-' + edit_Tier_Value).is(':checked')) {
                obj.birthday_rewards.push({
                    'birthday_rewards': '1'
                });
            } else {
                obj.birthday_rewards.push({
                    'birthday_rewards': null
                });
            }
        }

        if (flag == true) {
            
            jQuery("#edit_DiscountButton").attr("disabled", false);

            shopify.loading(true);

            // jQuery.ajax({
            //     type: 'POST',
            //     dataType: "json",
            //     url: "perks-update",
            // data = {
            //     store: shop,
            //     obj: obj,
            // }
            // success: function(data) {
            let ajaxParameters = {

                method: "POST",

                dataValues: {
                    obj,
                    action: "membershipPerksUpdate"

                }
            };

            let data = await AjaxCall(ajaxParameters);
            jQuery("#edit_DiscountButton").attr("disabled", false);
            let message = data.message;
            let status = data.status;

            console.log("Status:", SHOPIFY_DOMAIN_URL);
        
            let check_perk_value = data.check_perk_value;
            // console.log(message);
            if (status == 'exists') {
                if (check_perk_value == 'free_shipping_perk') {
                    jQuery('.Polaris-Layout__AnnotationContent').hide();
                    jQuery('.edit-TierSelected-' + message).show();
                    jQuery(".edit-Polaris-Select__SelectedOptionData").text(message);
                    jQuery("#selected-perks-data").val(message).attr("selected",
                        "selected");
                    jQuery('.Edit-ExistingFree_shipingCode_Error_' + message).fadeOut(500)
                        .fadeIn(500);
                    jQuery("#edit_DiscountButton").attr("disabled", false);

                    shopify.loading(false);

                    return false;
                } else {
                    jQuery('.Edit-ExistingFree_shipingCode_Error_' + message).hide();

                    shopify.loading(false);

                }

                if (check_perk_value == 'productCollection_perk') {
                    jQuery('.Polaris-Layout__AnnotationContent').hide();
                    jQuery('.edit-TierSelected-' + message).show();
                    jQuery(".edit-Polaris-Select__SelectedOptionData").text(message);
                    jQuery("#selected-perks-data").val(message).attr("selected",
                        "selected");
                    jQuery('.Edit-Existing_DiscountCode-Error-' + message).fadeOut(500)
                        .fadeIn(500);
                    jQuery("#edit_DiscountButton").attr("disabled", false);

                    shopify.loading(false);

                    return false;
                } else {
                    jQuery('.Edit-Existing_DiscountCode-Error-' + message).hide();

                    shopify.loading(false);

                }
            } else if (status == false) {
                jQuery("#edit_DiscountButton").attr("disabled", false);
                let app_redirect_link = `${SHOPIFY_DOMAIN_URL}/admin/memberships/memberships.php?shop=${shop}&host=${host}`;
                console.log(app_redirect_link);
               
                // let app_redirect_link = '/membership-plan-list';
                shopify.toast.show('Data updated successfully', { isError: false });
                
                open(app_redirect_link, '_self');

                jQuery("#edit_DiscountButton").attr("disabled", false);
                shopify.loading(false);
            } else {
                console.log('hiiiii');
                shopify.loading(false);
                shopify.toast.show('Data updated successfully', { isError: false });

                jQuery("#edit_DiscountButton").attr("disabled", false);
            }
            // },
            // error: function(response) {
            //     shopify.loading(false);
            //     shopify.toast.show(response.message, {isError : true });

            // }
            // });
        } else {
            console.log('error');
        }
    } else {
        console.log('errorrrrrrrrrrr');
        shopify.loading(false);
        // console.log('code 3');
        shopify.toast.show("Please check atleast one checkbox from first 3 checkbox", { isError: true });
        nextEditButtonCondition = false;
    }
});

function manageButtons(stepArrayKey) {

    if (stepArrayKey == 0 && sd_selectedtierGroupIdArray.length != 1) {
        previousPerkButton.hide();
        nextPerkButton.show();
        savePerkTierButton.hide();

    }
    if (stepArrayKey == 1 && sd_selectedtierGroupIdArray.length != 2) {
        previousPerkButton.show();
        nextPerkButton.show();
        savePerkTierButton.hide();
    }

    if (stepArrayKey == 1 && sd_selectedtierGroupIdArray.length == 2) {
        previousPerkButton.show();
        nextPerkButton.hide();
        savePerkTierButton.show();
    }
    if (stepArrayKey == 2 && sd_selectedtierGroupIdArray.length == 3) {
        previousPerkButton.show();
        nextPerkButton.hide();
        savePerkTierButton.show();
    }
}


function manageEditButtons(Edit_tierGroupId) {

    if (Edit_tierGroupId == 0 && Edit_sd_selectedtierGroupIdArray.length != 1) {
        previousEditPerkButton.hide();
        nextEditPerkButton.show();
        editPerkTierButton.hide();

    }
    if (Edit_tierGroupId == 1 && Edit_sd_selectedtierGroupIdArray.length != 2) {
        previousEditPerkButton.show();
        nextEditPerkButton.show();
        editPerkTierButton.hide();
    }

    if (Edit_tierGroupId == 1 && Edit_sd_selectedtierGroupIdArray.length == 2) {
        previousEditPerkButton.show();
        nextEditPerkButton.hide();
        editPerkTierButton.show();
    }
    if (Edit_tierGroupId == 2 && Edit_sd_selectedtierGroupIdArray.length == 3) {
        previousEditPerkButton.show();
        nextEditPerkButton.hide();
        editPerkTierButton.show();
    }
}



async function checkDiscountCoupan(codeValue, group_id) {
    // let csrfToken = $('input[name="_token"]').val();
    sd_selectedtierGroupId = group_id;
    jQuery('.sd_birthday-discount-Error').css("display", "none")
    // $.ajax({
    // type: "POST",
    // url: "checkCoupansCode",
    data = {
        store: shop,
        freeshipCoupanCode: codeValue,
        // _token: csrfToken
    }
    let ajaxParameters = {

        method: "POST",

        dataValues: {
            data,
            action: "checkCoupansCode"

        }
    };

    let result = await AjaxCall(ajaxParameters);
    // success: function(response) {
    //  console.log(response);
    if (result.status == true) {
        // console.log(response);
        // if(response.type== 'freeShipExists'){
        //     jQuery('.free_shipingCode_Error_' + sd_selectedtierGroupId).css("display", "block").text('code already exists');
        //     jQuery('.Edit-free_shipingCode_Error_' + sd_selectedtierGroupId).css("display", "block").text('code already exists');
        //     jQuery('.sd_birthday-discount-Error').css("display", "block").text('The discount code you entered is already in use.');
        //     jQuery('.sd_birthday-freeship-Error').css("display", "block").text('The freeship code you entered is already in use.');

        // }
        // if(response.type== 'discountExists'){
        //     jQuery('.Existing_DiscountCode-Error-' + sd_selectedtierGroupId).css("display", "block").text('code already exists');
        //     jQuery('.Edit-DiscountCode-Error-' + sd_selectedtierGroupId).css("display", "block").text('code already exists');
        // }

        let code = result.code;
        shopify.toast.show("'" + `${code}` + "'" + " code already exists", { isError: true });
        $('#next_EditDiscountButton').css('background', 'grey');
        $('#next_EditDiscountButton').prop('disabled', true);
        $('#next_DiscountButton').css('background', 'grey');
        $('#next_DiscountButton').prop('disabled', true);
        $('#save_DiscountButton').css('background', 'grey');
        $('#save_DiscountButton').prop('disabled', true);
        $('#edit_DiscountButton').css('background', 'grey');
        $('#edit_DiscountButton').prop('disabled', true);
    } else {
        $('#next_EditDiscountButton').css('background', 'rgb(33, 43, 54)');
        $('#next_EditDiscountButton').prop('disabled', false);
        $('#next_DiscountButton').css('background', 'rgb(33, 43, 54)');
        $('#next_DiscountButton').prop('disabled', false);
        $('#save_DiscountButton').css('background', 'rgb(33, 43, 54)');
        $('#save_DiscountButton').prop('disabled', false);
        $('#edit_DiscountButton').css('background', 'rgb(33, 43, 54)');
        $('#edit_DiscountButton').prop('disabled', false);

    }

    // error: function(error) {
    //     // console.log(error,'error');
    //     shopify.toast.show("An error occurred while checking the username", {isError : true });
    // }
    // });
}


jQuery('body').on('change', '#minimumDiscountCode', function () {
    let get_TierValue = jQuery(this).attr('minimumDiscountCode-attr');
    let GetPurchaseAmountselected_Value = jQuery(this).val();

    if (GetPurchaseAmountselected_Value === "minPurchase_Amount") {
        jQuery('.test-' + get_TierValue).html(`
                <div class="PurchaseAmountField-outerdiv-${get_TierValue}">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Enter minimum purchase amount</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                                <input name="minPurchaseAmount_TextField_${get_TierValue}" id="minPurchaseAmount_TextField_${get_TierValue}" class="Polaris-TextField__Input test minPurchaseAmount${get_TierValue} qtyAmtValidation" type="number" min="1" max="99999" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="1">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="minPurchaseAmount_TextFieldError_${get_TierValue}" style="color: red;display:none;">Enter minimum amount/quantity and should be greater than 0</p>
                </div>
            `);
    } else if (GetPurchaseAmountselected_Value === "minQuantity_items") {
        jQuery('.test-' + get_TierValue).html(`
                <div class="PurchaseAmountField-outerdiv-${get_TierValue}">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Enter minimum quanity of items</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                                <input name="maxPurchaseAmount_TextField_${get_TierValue}" id="maxPurchaseAmount_TextField_${get_TierValue}" class="Polaris-TextField__Input test minPurchaseAmount${get_TierValue} qtyAmtValidation" type="number" aria-labelledby="PolarisTextField1Label" aria-invalid="false" min="1" max="99999" value="1">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="minPurchaseAmount_TextFieldError_${get_TierValue}" style="color: red; display:none;">Enter minimum amount/quantity and should be greater than 0</p>
                </div>
            `);
    } else {
        jQuery('.test-' + get_TierValue).empty();
    }
});

/**
* For
* Selected the discount applies to
*/
jQuery('body').on('change', '.DiscountAppliedSelect_box', function () {
    let DiscountAppliedSelectedTier_Value = jQuery(this).attr('DiscountAppliedSelectedTier_Value');
    let CurrentDiscountValue = jQuery(this).val();
    if (CurrentDiscountValue == "all") {
        $('.perksDiscountApplies-' + DiscountAppliedSelectedTier_Value).empty();
    } else if (CurrentDiscountValue == "collection") {

        jQuery('.perksDiscountApplies-' + DiscountAppliedSelectedTier_Value).html(`
                <div class="Polaris-FormLayout__Item">
                    <button class="Polaris-Button collectionDiscountApplied" id="collectionDiscountApplied-${DiscountAppliedSelectedTier_Value}" type="button" SelectedCollectionButton-attr="${DiscountAppliedSelectedTier_Value}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a Collection</span></span>
                    </button>
                    <div class="Polaris-FormLayout__Items sd_selected_existing_collection-${DiscountAppliedSelectedTier_Value}" id="sd_selected_existing_collection_id"></div>
                    <div class="Polaris-FormLayout__Items sd_selected_existing_collection-Error-${DiscountAppliedSelectedTier_Value}" id="sd_selected_existing_collection_id" style="color:red;display:none;">Required Field!</div>
                </div>
            `);
    } else {
        jQuery('.perksDiscountApplies-' + DiscountAppliedSelectedTier_Value).html(`
                <div class="Polaris-FormLayout__Item">
                        <button class="Polaris-Button specificProduct_DiscountApplied" id="specificProduct_DiscountApplied-${DiscountAppliedSelectedTier_Value}" type="button" SelectedCollectionButton-attr="${DiscountAppliedSelectedTier_Value}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a Product</span></span>
                        </button>
                        <div class="Polaris-FormLayout__Items sd_selected_existing_specificProduct-${DiscountAppliedSelectedTier_Value}" id="sd_selected_existing_collection_specificProduct_id"></div>
                        <div class="Polaris-FormLayout__Items sd_selected_existing_product-Error-${DiscountAppliedSelectedTier_Value}" id="sd_selected_existing_collection_id" style="color:red;display:none;">Required Field!</div>
                   </div>
            `);
    }
});

/**
* For
* selected collection
*/
//  selected_prd_vrt_ids = {};
jQuery('body').on('click', '.collectionDiscountApplied', async function () {
    let SelectedCollectionButton_value = jQuery(this).attr('SelectedCollectionButton-attr');
    const selected = await shopify.resourcePicker({
        type: 'collection',
        action: 'select',
        selectionIds: [],
        multiple: false
    });

    let title = selected[0].title;
    let id = selected[0].id;

    jQuery('#collectionDiscountApplied-' + SelectedCollectionButton_value).hide();
    jQuery('.sd_selected_existing_collection-Error-' + SelectedCollectionButton_value).hide();
    jQuery('.sd_selected_existing_collection-' + SelectedCollectionButton_value).html(`
        <div class="Polaris-FormLayout__Item">
            <div class="">
                <div class="Polaris-Labelled__LabelWrapper">
                    <div class="Polaris-Label">
                        <label id="PolarisTextField4Label" for="PolarisTextField4" class="Polaris-Label__Text">Collection</label>
                    </div>
                </div>
                <div class="Polaris-Connected">
                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                        <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                            <input disabled id="${id}" name="perk-discounted_products-applies_to-collection_title" id="PolarisTextField4"  class="Polaris-TextField__Input collectionTitleName-${SelectedCollectionButton_value}" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="${title}">
                            <div class="Polaris-TextField__Backdrop">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="Polaris-FormLayout__Item"><div><div class="Polaris-Labelled__LabelWrapper"><div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div></div><button id="RemoveSelectedDiscountField" value="${SelectedCollectionButton_value}" class="Polaris-Button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg></span></span></span></button><input type="hidden" name="perk-discounted_products-applies_to-collection_id" value="396469993725"></div></div>`
    );
});


/**
* Remove discount
* field data
*/


jQuery('body').on('click', '#RemoveSelectedDiscountField', function () {
    let getTierValue = jQuery(this).val();
    jQuery('#collectionDiscountApplied-' + getTierValue).show();
    jQuery('.sd_selected_existing_collection-' + getTierValue).empty();
});


/**
* For
* selected product
*/
//  selected_specific_prd_vrt_ids = {};
jQuery('body').on('click', '.specificProduct_DiscountApplied', async function () {
    let SelectedSpecificProduct_Button_value = jQuery(this).attr('SelectedCollectionButton-attr');
    const selected = await shopify.resourcePicker({
        type: 'product',
        action: 'select',
        selectionIds: [],
        multiple: false,
        filter: {
            hidden: false,
            variants: false,
        },
    });

    let productTitle = selected[0].title;
    let product_id = selected[0].id;
    jQuery('#specificProduct_DiscountApplied-' + SelectedSpecificProduct_Button_value).hide();
    jQuery('.sd_selected_existing_specificProduct-' + SelectedSpecificProduct_Button_value)
        .show();
    jQuery('.sd_selected_existing_product-Error-' + SelectedSpecificProduct_Button_value)
        .hide();
    jQuery('.sd_selected_existing_specificProduct-' + SelectedSpecificProduct_Button_value).html(`
        <div class="Polaris-FormLayout__Item">
            <div class="">
                <div class="Polaris-Labelled__LabelWrapper">
                    <div class="Polaris-Label">
                        <label id="PolarisTextField4Label" for="PolarisTextField4" class="Polaris-Label__Text">Product</label>
                    </div>
                </div>
                <div class="Polaris-Connected">
                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                        <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                            <input disabled id="${product_id}" name="perk-discounted_products-applies_to-collection_title" id="PolarisTextField4"  class="Polaris-TextField__Input productTitleName-${SelectedSpecificProduct_Button_value}" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="${productTitle}">
                            <div class="Polaris-TextField__Backdrop">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="Polaris-FormLayout__Item"><div><div class="Polaris-Labelled__LabelWrapper"><div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div></div><button id="RemoveSelected_productDiscountField" value="${SelectedSpecificProduct_Button_value}" class="Polaris-Button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg></span></span></span></button><input type="hidden" name="perk-discounted_products-applies_to-collection_id" value="396469993725"></div></div>`
    );
});
//  });



/**
 * Remove discount
 * product field data
 */
jQuery('body').on('click', '#RemoveSelected_productDiscountField', function () {
    let getTierValue = jQuery(this).val();
    jQuery('#specificProduct_DiscountApplied-' + getTierValue).show();
    jQuery('.sd_selected_existing_specificProduct-' + getTierValue).empty();
});

jQuery('body').on('click', '.RemoveSelected_productSaleField', function () {
    let getTierValue = jQuery(this).val();
    // jQuery('#specificProduct_SaleApplied-' + getTierValue).show();
    jQuery('.sd_selected_existing_specificProduct-' + getTierValue).empty();
});



/**
 * Click the
 * Free gift upon signup button
 */

jQuery('body').on('click', '.free_gift-upon-signUp', async function () {
    let Selected_free_gift_sigup_attr = jQuery(this).attr('free_gift-sigup-attr');
    const selected = await shopify.resourcePicker({
        type: 'variant',
        action: 'select',
        selectionIds: [],
        multiple: false,
        filter: {
            hidden: false,
            variants: true,
        },
    });
    let productTitle = selected[0].displayName;
    let productID = selected[0].product.id;
    let variant_displayName = (selected[0].title).trim();
    let variant_displayId = (selected[0].id).trim();
    // return false;
    jQuery('#free_gift-upon-signUp-' + Selected_free_gift_sigup_attr).remove();
    jQuery('.free_gift-upon-signUp-Error-' + Selected_free_gift_sigup_attr).remove();
    jQuery('.free_gift-upon-signUp-' + Selected_free_gift_sigup_attr).last().append(`
            <div class="Polaris-FormLayout">
                <div role="group" class="Polaris-FormLayout--condensed">
                    <div class="Polaris-FormLayout__Items">
                        <div class="Polaris-FormLayout__Item">
                            <div class="">
                                <div class="Polaris-Labelled__LabelWrapper">
                                    <div class="Polaris-Label">
                                        <label id="PolarisTextField20Label" for="PolarisTextField20" class="Polaris-Label__Text">Selected item</label>
                                    </div>
                                </div>
                                <div class="Polaris-Connected">
                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                        <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                            <input free-gift_selected_productID = "${productID}" name="perk-free_gift-product_title" id="Free-gift_uponsignup_productName-${Selected_free_gift_sigup_attr}" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField20Label" aria-invalid="false" value="${productTitle}">
                                            <div class="Polaris-TextField__Backdrop">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Polaris-FormLayout__Item">
                            <div class="">
                                <div class="Polaris-Labelled__LabelWrapper">
                                    <div class="Polaris-Label">
                                        <label id="PolarisTextField21Label" for="PolarisTextField21" class="Polaris-Label__Text">Variant</label>
                                    </div>
                                </div>
                                <div class="Polaris-Connected">
                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                        <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                            <input free-gift_selected_variantID = "${variant_displayId}" name="perk-free_gift-variant_id" id="Free-gift_uponsignup_variantName-${Selected_free_gift_sigup_attr}" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField21Label" aria-invalid="false" value="${variant_displayName}">
                                            <div class="Polaris-TextField__Backdrop">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Polaris-FormLayout__Item">
                            <div>
                                <div class="Polaris-Labelled__LabelWrapper">
                                    <div class="Polaris-Label">
                                        <label class="Polaris-Label__Text">&nbsp;</label>
                                    </div>
                                </div>
                                <button class="Polaris-Button remove_free_gift_upon_signup" getSelectedTier_value = "${Selected_free_gift_sigup_attr}" type="button">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Icon">
                                            <span class="Polaris-Icon">
                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg>
                                            </span>
                                        </span>
                                    
                                    </span>
                                </button>
                                <input type="hidden" name="perk-free_gift-product_id" id="perk-free_gift-product_id-${Selected_free_gift_sigup_attr}" value="${productID}">
                                <input type="hidden" name="perk-free_gift-variant_id" id="perk-free_gift-variant_id-${Selected_free_gift_sigup_attr}" value="${variant_displayName}">
                            </div>
                        </div>
                    </div>
                </div>
                <div role="group" class="Polaris-FormLayout--grouped">
                    <div class="Polaris-FormLayout__Items">
                        <div class="Polaris-FormLayout__Item">
                            <div class="">
                                <div class="Polaris-Labelled__LabelWrapper">
                                    <div class="Polaris-Label">
                                        <label id="PolarisSelect11Label" for="free_gift_uponsignupSelectedDays-${Selected_free_gift_sigup_attr}" class="Polaris-Label__Text">How many days after signup should the gift be shipped? We suggest sending this later on to keep new members engaged for longer.</label>
                                    </div>
                                </div>
                                <div class="Polaris-Select">
                                    <select get_tierName = "${Selected_free_gift_sigup_attr}" id="free_gift_uponsignupSelectedDays-${Selected_free_gift_sigup_attr}" name="perk-free_gift-delay-${Selected_free_gift_sigup_attr}" class="Polaris-Select__Input select_gift_uponsignup" aria-invalid="false">
                                        <option value="Immediately after signup" free_gift_uponsignupSelected_Value="0" selected>Immediately after signup</option>
                                        <option value="1 day"  free_gift_uponsignupSelected_Value="1">1 day</option>
                                        <option value="2 days" free_gift_uponsignupSelected_Value="2">2 days</option>
                                        <option value="3 days" free_gift_uponsignupSelected_Value="3">3 days</option>
                                        <option value="4 days" free_gift_uponsignupSelected_Value="4">4 days</option>
                                        <option value="5 days" free_gift_uponsignupSelected_Value="5">5 days</option>
                                        <option value="6 days" free_gift_uponsignupSelected_Value="6">6 days</option>
                                        <option value="7 days"  free_gift_uponsignupSelected_Value="7">7 days</option>
                                        <option value="14 days" free_gift_uponsignupSelected_Value="14">14 days</option>
                                        <option value="30 days" free_gift_uponsignupSelected_Value="30">30 days</option>
                                    </select>
                                    <div class="Polaris-Select__Content" aria-hidden="true">
                                        <span class="Polaris-Select__SelectedOption perk-free_gift-delay-${Selected_free_gift_sigup_attr}">Immediately after signup</span>
                                        <span class="Polaris-Select__Icon">
                                            <span class="Polaris-Icon">
                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M7.676 9h4.648c.563 0 .879-.603.53-1.014l-2.323-2.746a.708.708 0 0 0-1.062 0l-2.324 2.746c-.347.411-.032 1.014.531 1.014Zm4.648 2h-4.648c-.563 0-.878.603-.53 1.014l2.323 2.746c.27.32.792.32 1.062 0l2.323-2.746c.349-.411.033-1.014-.53-1.014Z"></path></svg>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="Polaris-Select__Backdrop"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);
});


/**
     * selected
     * gift mix upon signup
     */
jQuery('body').on('click', '.remove_free_gift_upon_signup', function () {
    var getRemovedSelectedTier_value = jQuery(this).attr("getSelectedTier_value");

    jQuery('.free_gift-upon-signUp-' + getRemovedSelectedTier_value).html(`
            <div class="Polaris-FormLayout__Item">
               <button class="Polaris-Button free_gift-upon-signUp"  id="free_gift-upon-signUp-${getRemovedSelectedTier_value}" free_gift-sigup-attr="${getRemovedSelectedTier_value}" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a product</span></span>
                </button>
                <div class="free_gift-upon-signUp-Error-${getRemovedSelectedTier_value}" style="display: none;color: red;">Required Field!</div>
            </div>
        `);
});

/*********************************************** Edit perks form Here *************************************************/
/**
 * Edit Step 1
 * Free shipping field Here
 */
jQuery('body').on('change', '.edit-minimumDiscountCode', function () {

    var get_TierValue = jQuery(this).attr('Edit-minimumDiscountCode-attr');
    var GetEditMemberGroupId = jQuery(this).attr('edit-member-grpup-id');
    var GetPurchaseAmountEdit_selected_Value = jQuery(this).val();

    // console.log(GetPurchaseAmountEdit_selected_Value, 'GetPurchaseAmountEdit_selected_Value');
    // console.log(get_TierValue, 'get_TierValue');

    if (GetPurchaseAmountEdit_selected_Value === "min_purchase_amount") {

        var get_edit_min_purchase_amountValue = jQuery("#Edit-minimumDiscountCode-" + get_TierValue +
            " option:selected").attr('edit-min-purchase-amountValue');

        // console.log(get_edit_min_purchase_amountValue, 'get_edit_min_purchase_amountValue');


        if (typeof get_edit_min_purchase_amountValue === "undefined") {
            var set_edit_min_purchase_amountValue = '1';
        } else {
            var set_edit_min_purchase_amountValue = get_edit_min_purchase_amountValue;
        }
        jQuery('.test-' + GetEditMemberGroupId).html(`
                <div class="PurchaseAmountField-outerdiv-${get_TierValue}">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Enter minimum purchase amount</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                                <input name="edit-minPurchaseAmount_TextField_${GetEditMemberGroupId}" id="edit-minPurchaseAmount_TextField_${GetEditMemberGroupId}" class="Polaris-TextField__Input test editMinPurchaseAmount${GetEditMemberGroupId} qtyAmtValidation" type="number" min="1" max="99999" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="${set_edit_min_purchase_amountValue}">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="Edit-minPurchaseAmount_TextFieldError_${GetEditMemberGroupId} sd_perkViewErrors" style="color: red;display:none;">Enter minimum quantity/amount and should be greater than 0</p>
                </div>
            `);
    } else if (GetPurchaseAmountEdit_selected_Value === "min_quantity_items") {
        var get_edit_max_purchase_amountValue = jQuery("#Edit-minimumDiscountCode-" + get_TierValue +
            " option:selected").attr('edit-max-purchase-amountvalue');

        if (typeof get_edit_max_purchase_amountValue === "undefined") {
            var set_edit_max_purchase_amountValue = '1';
        } else {
            var set_edit_max_purchase_amountValue = get_edit_max_purchase_amountValue;
        }
        jQuery('.test-' + GetEditMemberGroupId).html(`
                <div class="PurchaseAmountField-outerdiv-${GetEditMemberGroupId}">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Enter minimum quanity items</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                                <input name="edit-maxPurchaseAmount_TextField_${GetEditMemberGroupId}" id="edit-maxPurchaseAmount_TextField_${GetEditMemberGroupId}" class="Polaris-TextField__Input test editMinPurchaseAmount${GetEditMemberGroupId} qtyAmtValidation" type="number" min="1" max="99999" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="${set_edit_max_purchase_amountValue}">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="Edit-minPurchaseAmount_TextFieldError_${GetEditMemberGroupId} sd_perkViewErrors" style="color: red; display:none;">Enter minimum quantity/amount and should be greater than 0</p>
                </div>
            `);
    } else {
        jQuery('.test-' + GetEditMemberGroupId).empty();
    }
});

/**
 * Step 1
 * Free shipping field  End Here
 */
var selected_perks_data = jQuery("#selected-perks-data option:selected").val();
var checkPolarisSelect1 = jQuery("#selected-perks-data option:selected").attr('membership-group-id');
jQuery(".edit-Polaris-Select__SelectedOptionData").text(selected_perks_data);

jQuery('.edit-TierSelected-' + checkPolarisSelect1).css("display", "block");

jQuery("#selected-perks-data").change(function () {

    var getcheckPolarisSelect1 = jQuery("#selected-perks-data option:selected").val();

    jQuery("#selected-perks-data > option").each(function () {
        if (getcheckPolarisSelect1 == this.value) {
            // console.log(getcheckPolarisSelect1);
            jQuery(".edit-Polaris-Select__SelectedOption").text(this.value);
            jQuery('.edit-TierSelected-' + this.value).css("display", "block");
        } else {
            jQuery('.edit-TierSelected-' + this.value).css("display", "none");
        }
    });
});

/********************************************************* END ************************************************/
/************************************************************ End  **************************/

// Remove colletion from product or collection perks
jQuery('body').on('click', '#edit-RemoveSelectedDiscountField', function () {

    var getTierValue = jQuery(this).val();
    jQuery(".edit-selected-collectionbox-" + getTierValue).empty();
    jQuery('.edit-selected-collectionbox-' + getTierValue).html(`
                    <div class="Polaris-FormLayout__Item">
                        <button class="Polaris-Button edit-collectionDiscountApplied" id="edit-collectionDiscountApplied-${getTierValue}" type="button" edit-SelectedCollectionButton-attr="${getTierValue}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a Collection</span></span>
                        </button>
                        <div class="Polaris-FormLayout__Items edit-sd_selected_existing_collection-${getTierValue}" id="sd_selected_existing_collection_id"></div>
                        <div class="Polaris-FormLayout__Items edit-sd_selected_existing_collection-Error-${getTierValue}" id="sd_selected_existing_collection_id" style="color:red;display:none;">Required Field!</div>
                    </div>
                `);
    jQuery('.edit-sd_selected_existing_collection-' + getTierValue).empty();
});

// Remove specific product
jQuery('body').on('click', '#edit-RemoveSelected_productDiscountField', function () {

    var getTierValue = jQuery(this).val();
    jQuery(".edit-selected-selectproductbox-" + getTierValue).empty();
    jQuery('.edit-selected-selectproductbox-' + getTierValue).html(`
                    <div class="Polaris-FormLayout__Item">
                         <button class="Polaris-Button edit-specificProduct_DiscountApplied" id="edit-specificProduct_DiscountApplied-${getTierValue}" type="button" edit-SelectedCollectionButton-attr="${getTierValue}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a Product</span></span>
                            </button>
                            <div class="Polaris-FormLayout__Items edit-sd_selected_existing_specificProduct-${getTierValue}" id="sd_selected_existing_collection_specificProduct_id" style="width:50%;"></div>
                            <div class="Polaris-FormLayout__Items edit-sd_selected_existing_product-Error-${getTierValue}" id="sd_selected_existing_product_id" style="color:red;display:none;">Required Field!</div>
                    </div>
                `);
});
/**
    * For edit the
    * Selected the discount applies to
    */
jQuery('body').on('change', '.edit-DiscountAppliedSelect_box', function () {
    var editDiscountAppliedSelectedTier_Value = jQuery(this).attr('edit-DiscountAppliedSelectedTier_Value');
    var GetEditMemberGroupId = jQuery(this).attr('edit-member-grpup-id');
    var currentOptionText = $(this).find('option:selected').text();
    // console.log(currentOptionText,'currentOptionText')
    var CurrentDiscountValue = jQuery(this).val();
    jQuery('.Polaris-Select__SelectedOption_' + GetEditMemberGroupId).text(currentOptionText)
    if (CurrentDiscountValue == "all") {
        $('.edit-perksDiscountApplies-' + GetEditMemberGroupId).empty();
    } else if (CurrentDiscountValue == "collection") {

        jQuery('.edit-perksDiscountApplies-' + GetEditMemberGroupId).html(`
                <div class="Polaris-FormLayout__Item edit-selected-collectionbox-${GetEditMemberGroupId}">
                    <button class="Polaris-Button edit-collectionDiscountApplied" id="edit-collectionDiscountApplied-${GetEditMemberGroupId}" type="button" edit-SelectedCollectionButton-attr="${GetEditMemberGroupId}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a Collection</span></span>
                    </button>
                    <div class="Polaris-FormLayout__Items edit-sd_selected_existing_collection-${GetEditMemberGroupId}" id="sd_selected_existing_collection_id"></div>
                    <div class="Polaris-FormLayout__Items edit-sd_selected_existing_collection-Error-${GetEditMemberGroupId} sd_perkViewErrors" id="sd_selected_existing_collection_id" style="color:red;display:none;">Add collection!</div>
                </div>
            `);
    } else {
        jQuery('.edit-perksDiscountApplies-' + GetEditMemberGroupId).html(`
                <div class="Polaris-FormLayout__Item edit-selected-selectproductbox-${GetEditMemberGroupId}">
                        <button class="Polaris-Button edit-specificProduct_DiscountApplied" id="edit-specificProduct_DiscountApplied-${GetEditMemberGroupId}" type="button" edit-SelectedCollectionButton-attr="${GetEditMemberGroupId}"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a Product</span></span>
                        </button>
                        <div class="Polaris-FormLayout__Items edit-sd_selected_existing_specificProduct-${GetEditMemberGroupId}" id="sd_selected_existing_collection_specificProduct_id"></div>
                        <div class="Polaris-FormLayout__Items edit-sd_selected_existing_product-Error-${GetEditMemberGroupId} sd_perkViewErrors" id="sd_selected_existing_collection_id" style="color:red;display:none;">Add product!</div>
                   </div>
            `);
    }
});

// End

/*****************************************************************************/

/**
 * Edit collection pop up
 *
 */
jQuery('body').on('click', '.edit-collectionDiscountApplied', async function () {
    let SelectedCollectionButton_value = jQuery(this).attr('edit-SelectedCollectionButton-attr');
    const selected = await shopify.resourcePicker({
        type: 'collection',
        action: 'select',
        selectionIds: [],
        multiple: false
    });

    let title = selected[0].title;
    let id = selected[0].id;

    jQuery('.edit-selected-collectionbox-' + SelectedCollectionButton_value).empty();
    jQuery('.edit-selected-collectionbox-' + SelectedCollectionButton_value).html(
        `
            <div class="Polaris-FormLayout__Items edit-sd_selected_existing_collection-${SelectedCollectionButton_value}" id="sd_selected_existing_collection_id">
            <div class="Polaris-FormLayout__Item">
                <div class="">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField4Label" for="PolarisTextField4" class="Polaris-Label__Text">Collection</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                <input disabled id="${id}" name="perk-discounted_products-applies_to-collection_title" id="PolarisTextField4"  class="Polaris-TextField__Input edit-collectionTitleName-${SelectedCollectionButton_value}" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="${title}">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Polaris-FormLayout__Item"><div><div class="Polaris-Labelled__LabelWrapper"><div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div></div><button id="edit-RemoveSelectedDiscountField" value="${SelectedCollectionButton_value}" class="Polaris-Button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg></span></span></span></button><input type="hidden" name="perk-discounted_products-applies_to-collection_id" value="396469993725"></div></div></div>`
    );
});
//end***********************************************

/**
 * Edit product pop up
 *
 */
jQuery('body').on('click', '.edit-specificProduct_DiscountApplied', async function () {
    let SelectedSpecificProduct_Button_value = jQuery(this).attr('edit-SelectedCollectionButton-attr');
    const selected = await shopify.resourcePicker({
        type: 'product',
        action: 'select',
        selectionIds: [],
        multiple: false,
        filter: {
            hidden: false,
            variants: false,
        },
    });

    let productTitle = selected[0].title;
    let product_id = selected[0].id;

    jQuery('.edit-selected-selectproductbox-' + SelectedSpecificProduct_Button_value).empty();

    jQuery('.edit-selected-selectproductbox-' + SelectedSpecificProduct_Button_value).html(
        `
            <div class="Polaris-FormLayout__Items edit-sd_selected_existing_specificProduct-${SelectedSpecificProduct_Button_value}" id="edit-sd_selected_existing_collection_specificProduct_id">
                <div class="Polaris-FormLayout__Item">
                    <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="PolarisTextField4Label" for="PolarisTextField4" class="Polaris-Label__Text">Product</label>
                            </div>
                        </div>
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                    <input disabled="" id="${product_id}" name="perk-discounted_products-applies_to-collection_title" class="Polaris-TextField__Input edit-productTitleName-${SelectedSpecificProduct_Button_value}" type="text" aria-labelledby="PolarisTextField4Label" aria-invalid="false" value="${productTitle}">
                                    <div class="Polaris-TextField__Backdrop">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Polaris-FormLayout__Item"><div><div class="Polaris-Labelled__LabelWrapper"><div class="Polaris-Label"><label class="Polaris-Label__Text">&nbsp;</label></div></div><button id="edit-RemoveSelected_productDiscountField" value="${SelectedSpecificProduct_Button_value}" class="Polaris-Button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg></span></span></span></button><input type="hidden" name="perk-discounted_products-applies_to-collection_id" value="${product_id}"></div></div></div>`
    );
});
/*****************************************************************************/    /*****************************************************************************/




/**
 * Select
 * product for free gift upon sign up
 */
jQuery('body').on('click', '.edit-free_gift-upon-signUp', async function () {
    let Selected_free_gift_sigup_attr = jQuery(this).attr('edit-member-group-id');
    const selected = await shopify.resourcePicker({
        type: 'variant',
        action: 'select',
        selectionIds: [],
        multiple: false,
        filter: {
            hidden: false,
            variants: true,
        },
    });
    let productTitle = selected[0].displayName;
    let productID = selected[0].product.id;
    let variant_displayName = (selected[0].title).trim();
    let variant_displayId = (selected[0].id).trim();
    jQuery('.set-edit-free_upon_sign_up_button-' + Selected_free_gift_sigup_attr).append(`
            <div role="group" class="Polaris-FormLayout--condensed">
                <div class="Polaris-FormLayout__Items">
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="PolarisTextField20Label" for="PolarisTextField20" class="Polaris-Label__Text">Product</label>
                                </div>
                            </div>
                            <div class="Polaris-Connected">
                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                    <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                        <input edit-free-gift_selected_productid="${productID}" name="edit-perk-free_gift-product_title" id="edit-Free-gift_uponsignup_productName-${Selected_free_gift_sigup_attr}" disabled="" class="Polaris-TextField__Input freeProductName${Selected_free_gift_sigup_attr}" type="text" aria-labelledby="PolarisTextField20Label" aria-invalid="false" value="${productTitle}">
                                        <div class="Polaris-TextField__Backdrop">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="PolarisTextField21Label" for="PolarisTextField21" class="Polaris-Label__Text">Variant</label>
                                </div>
                            </div>
                            <div class="Polaris-Connected">
                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                    <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                        <input edit-free-gift_selected_variantid="${variant_displayId}" name="perk-free_gift-variant_id" id="Free-gift_uponsignup_variantName-${Selected_free_gift_sigup_attr}" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField21Label" aria-invalid="false" value="${variant_displayName}">
                                        <div class="Polaris-TextField__Backdrop">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Polaris-FormLayout__Item">
                        <div>
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label class="Polaris-Label__Text">&nbsp;</label>
                                </div>
                            </div>
                            <button class="Polaris-Button edit-remove_free_gift_upon_signup" edit-getselectedtier_value="${Selected_free_gift_sigup_attr}" edit-member-group-id="${Selected_free_gift_sigup_attr}" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon">
                                        <span class="Polaris-Icon">
                                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg>
                                        </span>
                                    </span>
                                </span>
                            </button>
                            <input type="hidden" name="edit-perk-free_gift-product_id" id="edit-perk-free_gift-product_id-${Selected_free_gift_sigup_attr}" value="${productID}">
                            <input type="hidden" name="edit-perk-free_gift-variant_id" id="edit-perk-free_gift-variant_id-${Selected_free_gift_sigup_attr}" value="${variant_displayId}">
                        </div>
                    </div>
                </div>
            </div>
            <div role="group" class="Polaris-FormLayout--grouped">
                <div class="Polaris-FormLayout__Items">
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="PolarisSelect11Label" for="edit-free_gift_uponsignupSelectedDays-${Selected_free_gift_sigup_attr}" class="Polaris-Label__Text">How many days after signup should the gift be shipped? We suggest sending this later on to keep new members engaged for longer.</label>
                                </div>
                            </div>
                            <div class="Polaris-Select">
                                <select edit-get_tiername="${Selected_free_gift_sigup_attr}" id="edit-free_gift_uponsignupSelectedDays-${Selected_free_gift_sigup_attr}" name="edit-perk-free_gift-delay-${Selected_free_gift_sigup_attr}" class="Polaris-Select__Input edit-select_gift_uponsignup" aria-invalid="false">
                                    <option value="Immediately after signup"  edit-free_gift_uponsignupSelected_Value="0">Immediately after signup</option>
                                    <option value="1 day" edit-free_gift_uponsignupSelected_Value="1">1 day</option>
                                    <option value="2 days" edit-free_gift_uponsignupSelected_Value="2">2 days</option>
                                    <option value="3 days"  edit-free_gift_uponsignupSelected_Value="3">3 days</option>
                                    <option value="4 days"  edit-free_gift_uponsignupSelected_Value="4">4 days</option>
                                    <option value="5 days"  edit-free_gift_uponsignupSelected_Value="5">5 days</option>
                                    <option value="6 days"  edit-free_gift_uponsignupSelected_Value="6">6 days</option>
                                    <option value="6 days"  edit-free_gift_uponsignupSelected_Value="7">7 days</option>
                                    <option value="14 days" edit-free_gift_uponsignupSelected_Value="14">14 days</option>
                                    <option value="30 days" edit-free_gift_uponsignupSelected_Value="30">30 days</option>
                                </select>
                                <div class="Polaris-Select__Content" aria-hidden="true">
                                    <span class="Polaris-Select__SelectedOption edit-perk-free_gift-delay-${Selected_free_gift_sigup_attr}">Immediately after signup</span>
                                    <span class="Polaris-Select__Icon">
                                        <span class="Polaris-Icon">
                                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M7.676 9h4.648c.563 0 .879-.603.53-1.014l-2.323-2.746a.708.708 0 0 0-1.062 0l-2.324 2.746c-.347.411-.032 1.014.531 1.014Zm4.648 2h-4.648c-.563 0-.878.603-.53 1.014l2.323 2.746c.27.32.792.32 1.062 0l2.323-2.746c.349-.411.033-1.014-.53-1.014Z"></path></svg>
                                        </span>
                                    </span>
                                </div>
                                <div class="Polaris-Select__Backdrop"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

    $('#edit-free_gift-upon-signUp-' + Selected_free_gift_sigup_attr).remove();
});

/*****************************************************************************/    /*****************************************************************************/


// early sale access js code starting..........................................................................................................................

/**
    * For
    * Selected Sales applies on.............
    */
let totalProductsSelected = 0;
let totalCollectionsSelected = 0;
let selected_sale_prd_vrt_ids = {};
let sale_selected_col_ids = {};
let productSelected_idsObjArray = [];
let collectionSelected_titleAraay = [];
let productSelected_titleAraay = [];
let collectionSelected_idsObjArray = [];
let collectionIdObj = {}
let productIdObj = {}

let sale_itemTye = $('.sd_selected_productsIds').attr('item-type');
if (sale_itemTye == 'P') {
    let sd_getSelectedProducts = $('.sd_selected_productsIds').val();
    let productsArray = jsonProDataParse(sd_getSelectedProducts);
    productSelected_idsObjArray = productSelected_idsObjArray.concat(productsArray);
} else if (sale_itemTye == 'C') {
    let sd_getSelectedCollections = $('.sd_selected_productsIds').val();
    let collectionsArray = jsonColDataParse(sd_getSelectedCollections);
    collectionSelected_idsObjArray = collectionSelected_idsObjArray.concat(collectionsArray);
}


jQuery('body').on('change', '.saleSelectbox', function () {
    let CurrentDiscountValue = jQuery(this).val();
    if (CurrentDiscountValue == "Whole store products") {
        $('.perksSaleApplies').empty();
    } else if (CurrentDiscountValue == "Specific collections") {
        collSelectedCondition = (totalCollectionsSelected > 0) ? totalCollectionsSelected + " Collections selected" : "";
        jQuery('.perksSaleApplies').html(`
                 <div class="Polaris-FormLayout__Item">
                     <button class="Polaris-Button collectionSaleApplied" id="collectionSaleApplied" type="button" SelectedCollectionButton-attr=""><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Add Collections</span></span>
                     </button><span class="totalCollectionsSelected" style="font-size: 13px; margin-left: 19px;">${collSelectedCondition}</span>
                     <div class="Polaris-FormLayout__Items sd_selected_existing_specificProduct" id="sd_selected_existing_collection_id" ></div>
                     <div class="Polaris-FormLayout__Items sd_selected_sale_collection-Error sd_earlySaleErrors" id="sd_selected_existing_collection_id" style="color:red;display:none;">Required Field!</div>
                 </div>
             `);
    } else {
        proSelectedCondition = (totalProductsSelected > 0) ? totalProductsSelected + " Products selected" : "";
        jQuery('.perksSaleApplies').html(`
                 <div class="Polaris-FormLayout__Item">
                         <button class="Polaris-Button specificProduct_SaleApplied" id="specificProduct_SaleApplied" type="button" SelectedCollectionButton-attr=""><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Add Products</span></span>
                         </button> <span class="totalProductsSelected" style="font-size: 13px; margin-left: 19px;">${proSelectedCondition}</span>
                         <div class="Polaris-FormLayout__Items sd_selected_existing_specificProduct" id="sd_selected_existing_collection_specificProduct_id"></div>
                         <div class="Polaris-FormLayout__Items sd_selected_sale_product-Error sd_earlySaleErrors" id="sd_selected_existing_product_id" style="color:red;display:none;">Required Field!</div>
                    </div>
             `);
    }
});

function addColGraphQlId(c_id) {
    collectionIdObj = { id: "gid://shopify/Collection/" + c_id };
    return collectionIdObj;
}

function addProGraphQlId(c_id) {
    productIdObj = { id: "gid://shopify/Product/" + c_id };
    return productIdObj;
}

function jsonProDataParse(data) {
    let jsonArrayProducts = JSON.parse(data);
    jsonArrayProducts = jsonArrayProducts.map(addProGraphQlId);
    return jsonArrayProducts;
}
function jsonColDataParse(data) {
    let jsonArrayProducts = JSON.parse(data);
    jsonArrayProducts = jsonArrayProducts.map(addColGraphQlId);
    return jsonArrayProducts;
}


///  selected Sale Products................/////////////......................................

jQuery('body').on('click', '.specificProduct_SaleApplied', async function () {
    // console.log(productSelected_idsObjArray);
    const selected = await shopify.resourcePicker({
        type: 'product',
        selectionIds: productSelected_idsObjArray,
        multiple: true,
        filter: {
            hidden: false,
            variants: false,
        },
    });

    if (selected == null) {
        // console.log('hhhhhhhhh');
        return false;
    }
    collectionSelected_idsObjArray = [];

    if (productSelected_idsObjArray == undefined) {
        productSelected_idsObjArray = [];
    }

    if (productSelected_idsObjArray.length > 0) {
        productSelected_idsObjArray = [];
    }
    let itemType = 'Product';
    selectedItemsTable();
    appendTableItems(selected, itemType);

    jQuery('.sd_selected_existing_specificProduct').show();
    jQuery('.sd_selected_existing_product-Error').hide();
});

///  selected Sale collection................/////////////......................................

jQuery('body').on('click', '.collectionSaleApplied', async function () {

    const selected = await shopify.resourcePicker({
        type: 'collection',
        selectionIds: collectionSelected_idsObjArray,
        multiple: true,
        filter: {
            hidden: false,
            variants: false,
        },
    })
    productSelected_idsObjArray = [];
    //  let SelectedSaleCollection_value = jQuery(this).attr('SelectedCollectionButton-attr');
    if (collectionSelected_idsObjArray == undefined) {
        collectionSelected_idsObjArray = [];
    }
    if (collectionSelected_idsObjArray.length > 0) {
        collectionSelected_idsObjArray = [];
    }
    let itemType = 'Collection';
    selectedItemsTable();
    appendTableItems(selected, itemType);
    jQuery('#collectionSaleApplied').show();
    jQuery('.sd_selected_existing_collection-Error').hide();
});


// search table items function..............................................................
jQuery('body').on('keyup', '#search_selected_product', function () {
    let searchTerm = jQuery(this).val().toLowerCase();
    let tableRows = jQuery('.SelectedProductLists');

    tableRows.each(function () {
        let rowData = jQuery(this).text().toLowerCase();
        if (rowData.includes(searchTerm)) {
            jQuery(this).show();
        } else {
            jQuery(this).hide();
        }
    });
});

// Remove table items function..............................................................    
function removeItemFromArray(arr, value) {
    let index = -1;
    if (typeof (value) == "string") {
        index = arr.indexOf(value);
    } else {
        index = arr.map((data) => data.id).indexOf(value.id);
    }
    if (index > -1) {
        arr.splice(index, 1);
    };
    return arr;
}


jQuery('body').on('click', '.delete-selected-item', function () {
    let itemType = jQuery(this).attr('item-type');
    let itemGroupId = jQuery(this).attr('group-id');
    let itemObj = jQuery(this).attr('product-object');
    let arrayObj = { id: itemObj };
    let removetr = jQuery(this).closest('tr');

    switch (itemType) {
        case 'Product':
            //  console.log(productSelected_idsObjArray.length);
            removeItemFromArray(productSelected_idsObjArray, arrayObj);
            if (productSelected_idsObjArray.length == 0) {
                jQuery('.allItemsTable').css("display", "none");
            }
            break;

        case 'Collection':
            removeItemFromArray(collectionSelected_idsObjArray, arrayObj);
            if (collectionSelected_idsObjArray.length == 0) {
                jQuery('.allItemsTable').css("display", "none");
            }
            break;
    }
    removetr.remove();
});

// append selected products and collections
function appendTableItems(selected, itemType) {
    for (let col = 0; col < selected.length; col++) {
        let pro_title = selected[col].title;
        let pro_id = selected[col].id;
        let idWithoutString = '';
        let pro_img_src = '';
        if (itemType == 'Collection') {
            sale_selected_col_ids = { id: pro_id };
            collectionSelected_idsObjArray.push(sale_selected_col_ids);
            collectionSelected_titleAraay.push(pro_title);
            idWithoutString = pro_id.replace("gid://shopify/Collection/", "");
            if (selected[col].image && selected[col].image.length > 0) {
                pro_img_src = selected[col].image[0]?.['originalSrc'] || 'https://cdn.shopify.com/s/files/1/0829/6575/8262/products/AAUvwnj0ICORVuxs41ODOvnhvedArLiSV20df7r8XBjEUQ_s900-c-k-c0x00ffffff-no-rj_58e5329e-7eb7-49a4-86fc-2db2f8e43ead_40x40@3x.jpg?v=1694182351';
            } else {
                pro_img_src = 'https://cdn.shopify.com/s/files/1/0829/6575/8262/products/AAUvwnj0ICORVuxs41ODOvnhvedArLiSV20df7r8XBjEUQ_s900-c-k-c0x00ffffff-no-rj_58e5329e-7eb7-49a4-86fc-2db2f8e43ead_40x40@3x.jpg?v=1694182351';
            }


        } else {
            selected_sale_prd_vrt_ids = { id: pro_id };
            productSelected_idsObjArray.push(selected_sale_prd_vrt_ids);
            productSelected_titleAraay.push(pro_title);
            idWithoutString = pro_id.replace("gid:\/\/shopify\/Product\/", "");

            if (selected[col].images && selected[col].images.length > 0) {
                pro_img_src = selected[col].images[0]?.['originalSrc'] || 'https://cdn.shopify.com/s/files/1/0829/6575/8262/products/AAUvwnj0ICORVuxs41ODOvnhvedArLiSV20df7r8XBjEUQ_s900-c-k-c0x00ffffff-no-rj_58e5329e-7eb7-49a4-86fc-2db2f8e43ead_40x40@3x.jpg?v=1694182351';
            } else {
                pro_img_src = 'https://cdn.shopify.com/s/files/1/0829/6575/8262/products/AAUvwnj0ICORVuxs41ODOvnhvedArLiSV20df7r8XBjEUQ_s900-c-k-c0x00ffffff-no-rj_58e5329e-7eb7-49a4-86fc-2db2f8e43ead_40x40@3x.jpg?v=1694182351';
            }

        }

        jQuery('.sd_selected_existing_specificProduct tbody').append(`
         <tr class="Polaris-DataTable__TableRow SelectedProductLists Polaris-DataTable--hoverable">
             <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                 <img src="${pro_img_src}" height="50px" width="50px" style="border-radius:1px;"><span>${pro_title}</span>
             </td>
             <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                 <div table-col-index="${col}" class="delete-selected-item get-product-info" product-image="${pro_img_src}" product-id="${idWithoutString}" product-title="${pro_title}" product-object="${pro_id}" group-id="" item-type="${itemType}">
                 <span class="deleted-svg-item"><svg width="25" height="25" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M13.3477 14.6397C13.4164 14.7134 13.4992 14.7725 13.5912 14.8135C13.6832 14.8545 13.7825 14.8765 13.8832 14.8783C13.9839 14.88 14.084 14.8615 14.1773 14.8238C14.2707 14.7861 14.3556 14.7299 14.4268 14.6587C14.498 14.5875 14.5541 14.5027 14.5919 14.4093C14.6296 14.3159 14.6481 14.2159 14.6463 14.1152C14.6446 14.0145 14.6225 13.9151 14.5815 13.8231C14.5405 13.7311 14.4814 13.6483 14.4077 13.5797L11.6877 10.8597L14.4077 8.13968C14.5402 7.9975 14.6124 7.80946 14.6089 7.61516C14.6055 7.42086 14.5268 7.23547 14.3894 7.09806C14.252 6.96064 14.0666 6.88193 13.8723 6.8785C13.678 6.87508 13.4899 6.9472 13.3477 7.07968L10.6277 9.79968L7.90775 7.07968C7.76557 6.9472 7.57753 6.87508 7.38322 6.8785C7.18892 6.88193 7.00354 6.96064 6.86613 7.09806C6.72871 7.23547 6.65 7.42086 6.64657 7.61516C6.64314 7.80946 6.71527 7.9975 6.84775 8.13968L9.56775 10.8597L6.84775 13.5797C6.77406 13.6483 6.71496 13.7311 6.67397 13.8231C6.63297 13.9151 6.61093 14.0145 6.60916 14.1152C6.60738 14.2159 6.6259 14.3159 6.66362 14.4093C6.70135 14.5027 6.75749 14.5875 6.82871 14.6587C6.89993 14.7299 6.98476 14.7861 7.07815 14.8238C7.17154 14.8615 7.27157 14.88 7.37227 14.8783C7.47297 14.8765 7.57229 14.8545 7.66429 14.8135C7.75628 14.7725 7.83909 14.7134 7.90775 14.6397L10.6277 11.9197L13.3477 14.6397Z" fill="black"/>
                     </svg></span>
                 </div>
             </td>
         </tr>`);
    }
}



// append selected products and collections table
function selectedItemsTable() {
    return jQuery('.sd_selected_existing_specificProduct').html(`
 <div class="Polaris-ResourceList__ResourceListWrapper sd-members-main-row allItemsTable">
     <div class="Polaris-ResourceList__FiltersWrapper">
         <div class="Polaris-Filters">
             <div class="Polaris-Filters-ConnectedFilterControl__ProxyButtonContainer" aria-hidden="true"></div>
             <div class="Polaris-Filters-ConnectedFilterControl__Wrapper">
                 <div class="Polaris-Filters-ConnectedFilterControl Polaris-Filters-ConnectedFilterControl--right">
                     <div class="Polaris-Filters-ConnectedFilterControl__CenterContainer">
                         <div class="Polaris-Filters-ConnectedFilterControl__Item">
                             <div class="Polaris-Labelled--hidden">
                                 <div class="Polaris-Labelled__LabelWrapper">
                                     <div class="Polaris-Label">
                                         <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">Search Products</label>
                                     </div>
                                 </div>
                                 <div class="Polaris-Connected">
                                     <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                         <div class="Polaris-TextField member-fillters">
                                             <div class="Polaris-TextField__Prefix" id="PolarisTextField1Prefix">
                                                 <span class="Polaris-Filters__SearchIcon">
                                                     <span class="Polaris-Icon">
                                                         <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                             <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                                         </svg>
                                                     </span>
                                                 </span>
                                             </div>
                                             <input id="search_selected_product" placeholder="Search products" class="Polaris-TextField__Input Polaris-TextField__Input--hasClearButton" aria-labelledby="PolarisTextField1Label PolarisTextField1Prefix" aria-invalid="false" value="">
                                             <div class="Polaris-TextField__Backdrop"></div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <span class="Polaris-VisuallyHidden">
                 <div class="Polaris-Filters__TagsContainer" aria-live="polite"></div>
             </span>
         </div>
     </div>
     <div class="sd_resourceList_main" style="max-height:450px;">
         <table class="Polaris-DataTable__Table allItemsTable">
             <tbody>  
             </tbody>
         </table>
         <div class="Polaris-DataTable__Footer"></div>
     </div>
 </div>`);
}


// console.log('backend02 js file running');

//............................... Bithdays gift code ................................


$('body').on('change', '.sd_customerSelect_box', function () {
    $('.sd_errors').css('display', 'none');
    let selectedOptionText = $(this).find('option:selected').text();
    $('.sd_selectedCustomers').text(selectedOptionText);
    let selectedOption = $(this).val();
    // console.log(selectedOption, 'selectedOption');
    switch (selectedOption) {
        case 'none':
            $('.sd_birthdayCustomersOption').html(``);
            break;

        case 'all':
            $('.sd_birthdayCustomersOption').html(`
            <div class="PurchaseAmountField-outerdiv">
                <div class="Polaris-Labelled__LabelWrapper">
                    <div class="Polaris-Label">
                        <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Note :- </label>
                    </div>
                </div>
                <div class="Polaris-Connected">
                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                        <div class="birthday-message-note">
                            <p>All store cutomers are eligible for birthday gift.</p>
                            <div class="Polaris-TextField__Backdrop">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
            break;

        case 'only-members':
            $('.sd_birthdayCustomersOption').html(`
            <div class="PurchaseAmountField-outerdiv">
                <div class="Polaris-Labelled__LabelWrapper">
                    <div class="Polaris-Label">
                        <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Note :- </label>
                    </div>
                </div>
                <div class="Polaris-Connected">
                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                        <div class="birthday-message-note">
                            <p>Customers who have purchased a membership with the birthday gift feature are eligible for the birthday gift.</p>
                            <div class="Polaris-TextField__Backdrop">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
            break;

        case 'min-amount':
            $('.sd_birthdayCustomersOption').html(`
                <div class="PurchaseAmountField-outerdiv">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Enter minimum purchase amount ($)</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                                <input name="minimum-sale-amount" id="minimum-sale-amount" class="Polaris-TextField__Input test minimum-sale-amount qtyAmtValidation" type="number" min="1" max="99999" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="1">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="minimum-sale-amount-Error sd_errors" style="color: red;display:none;">Enter minimum amount and should be greater than 0</p>
                </div>
            `);
            break;

        case 'min-orders':
            $('.sd_birthdayCustomersOption').html(`
                <div class="PurchaseAmountField-outerdiv">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Enter minimum purchase order</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                                <input name="minimum-sale-order" id="minimum-sale-order" class="Polaris-TextField__Input test minimum-sale-order qtyAmtValidation" type="number" min="1" max="99999" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="1">
                                <div class="Polaris-TextField__Backdrop">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="minimum-sale-order-Error sd_errors" style="color: red;display:none;">Enter minimum order and should be greater than 0</p>
                </div>
            `);
            break;
    }
});

$('body').on('change', '.sd_birthdayGiftSelect_box', function () {
    $('.sd_errors').css('display', 'none');
    let selectedOptionText = $(this).find('option:selected').text();
    $('.sd_selectedGiftOption').text(selectedOptionText);
    let selectedOption = $(this).val();
    // console.log(selectedOption, 'selectedOption');
    $('.sd_birthdayAdd-Discount').html('');
    $('.sd_remove-collection').html('');

    switch (selectedOption) {
        case 'none':
            $('.sd_birthdayGiftCollection').html('');
            break;

        case 'product':
            $('.sd_birthdayGiftCollection').html(`
                <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Select the product for birthday gift</label>
                        </div>
                    </div>
                    
                    <div style="max-height: none; overflow: visible;" class="birthdayGift-product" aria-expanded="true">
                        <div class="Polaris-FormLayout__Item">
                            <button class="Polaris-Button birthdayGiftProduct" id="birthdayGift_product" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon"><span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                        </svg>
                                    </span></span>
                                </span>
                                <span class="Polaris-Button__Text selectBdayGift">Select gift product</span>
                            </button>
                        </div>
                        <div class="Polaris-FormLayout__Item selectedProductVariant" style="display:flex;"> </div>
                        <div class="birthdayGiftproduct-Error sd_errors" style="display: none;color: red;">Select a product as a birthday gift.</div>
                    </div>
                </div>
            `);
            break;

        case 'discount':
            $('.sd_birthdayGiftCollection').html(`
            <div class="Polaris-FormLayout__Item">
                <div class="">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField3Label" for="PolarisTextField3" class="Polaris-Label__Text">Add discount for birthday gift</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            
                            <div class="Polaris-FormLayout__Items">

                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Discount code</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input name="birthday-discount" id="sd_birthday-discount" class="Polaris-TextField__Input capitalDiscountInput checkCodeExists sd_birthday-discount" type="text" aria-labelledby="PolarisTextField8Label" aria-invalid="false" code-value="" value="">
                                                    <div class="Polaris-TextField__Backdrop">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="sd_birthday-discount-Error sd_errors" style="display: none;color: red;">Discount code
                                            is required!</p>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisTextField9Label" for="PolarisTextField9" class="Polaris-Label__Text">Percentage
                                                    off</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input name="sd_birthday-Percentage" id="sd_birthday-Percentage" class="Polaris-TextField__Input Polaris-TextField__Input--suffixed  sd_birthday-Percentage numberPercentage" type="text" aria-labelledby="PolarisTextField9Label PolarisTextField9Suffix" aria-invalid="false" value="">
                                                    <div class="Polaris-TextField__Suffix" id="PolarisTextField9Suffix">% off
                                                    </div>
                                                    <div class="Polaris-TextField__Backdrop">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="sd_birthday-Percentage-Error sd_errors" style="display: none;color: red;">Discount % is required &amp; gretor than 0</p>
                                    </div>
                                </div>

                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="edit-PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Discount
                                                    applies to</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Select">
                                            <select id="sd_PolarisSelect" name="perk-discounted_products-applies_to" class="Polaris-Select__Input sd_birthday-discountSelect_box" aria-invalid="false">
                                                <option value="all" selected="">
                                                    All products</option>
                                                <option value="collection">
                                                    Specific collection</option>
                                                <option value="product">
                                                    Specific product</option>
                                            </select>
                                            <div class="Polaris-Select__Content" aria-hidden="true">
                                                <span class="sd_birthday_discount_select_value">
                                                        All Product
                                                </span>
                                                <span class="Polaris-Select__Icon select-collection-product">
                                                    <span class="Polaris-Icon">
                                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                            <path d="M7.676 9h4.648c.563 0 .879-.603.53-1.014l-2.323-2.746a.708.708 0 0 0-1.062 0l-2.324 2.746c-.347.411-.032 1.014.531 1.014Zm4.648 2h-4.648c-.563 0-.878.603-.53 1.014l2.323 2.746c.27.32.792.32 1.062 0l2.323-2.746c.349-.411.033-1.014-.53-1.014Z">
                                                            </path>
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
            </div>
            `);
            break;

        case 'freeshiping':
            $('.sd_birthdayGiftCollection').html(`
            <div class="Polaris-FormLayout__Item">
                <div class="">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisTextField3Label" for="PolarisTextField3" class="Polaris-Label__Text">Add freeship discount code for birthday gift</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            
                            <div class="Polaris-FormLayout__Items">

                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisTextField8Label" for="PolarisTextField8" class="Polaris-Label__Text">Freeship discount code</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input name="birthday-freeship" id="sd_birthday-freeship" class="Polaris-TextField__Input capitalDiscountInput checkCodeExists sd_birthday-freeship" type="text" aria-labelledby="PolarisTextField8Label" aria-invalid="false" value="">
                                                    <div class="Polaris-TextField__Backdrop">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="sd_birthday-freeship-Error sd_errors" style="display: none;color: red;">Freeship code
                                            is required!</p>
                                    </div>
                                </div>
                                <div class="Polaris-FormLayout__Item"></div>
                                <div class="Polaris-FormLayout__Item"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `);
            break;

        case 'template':
            $('.sd_birthdayGiftCollection').html(`
            <div class="Polaris-FormLayout__Item">
                <div class="">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                        <label id="PolarisTextField3Label" for="PolarisTextField3" class="Polaris-Label__Text">Write birthday wish message for your customers.</label>
                        </div>
                    </div>
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <p> Setup the template in Birthday rewards -> Birthday email template.
                            <span class="member_product_description_error error_messages"></span>
                        </div>
                    </div>
                </div>
            </div>
            `);
            break;

        // case 'amountoff':
        // $('.sd_birthdayGiftCollection').html('');
        // break;

        // case 'mysterygift':
        // $('.sd_birthdayGiftCollection').html('');
        // break;
    }
});

///  selected Sale Products................/////////////......................................

let freeBdayGift = [];
jQuery('body').on('click', '.birthdayGiftProduct', async function () {
    $('.sd_errors').css('display', 'none');
    $('.selectedVariantErrorMsg').css({ 'display': 'none' });


    const selected = await shopify.resourcePicker({
        type: 'variant',
        action: 'select',
        selectionIds: freeBdayGift,
        multiple: false,
        filter: {
            hidden: false,
            variants: true,
        },
    });
    let productTitle = selected[0].displayName;
    let productID = selected[0].product.id;
    let variant_displayName = (selected[0].title).trim();
    let variant_displayId = (selected[0].id).trim();
    let selectedId = '';
    if (freeBdayGift === undefined) {
        freeBdayGift = [];
    }
    if (freeBdayGift.length > 0) {
        freeBdayGift = [];
    }
    selectedId = { id: `${selected[0].id}` };
    freeBdayGift.push(selectedId);
    $('.selectBdayGift').text(`Change product`);
    $('.selectedProductVariant').html(`
                <div class="Polaris-FormLayout__Item">
                    <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="PolarisTextField20Label" for="PolarisTextField20" class="Polaris-Label__Text">Selected item</label>
                            </div>
                        </div>
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                    <input name="birthday_gift-product_title" id="birthday-gift_selectedProduct" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField20Label" aria-invalid="false" value ="${productTitle}" selected-product-id="${productID}">
                                    <div class="Polaris-TextField__Backdrop">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="Polaris-FormLayout__Item">
                    <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="PolarisTextField21Label" for="PolarisTextField21" class="Polaris-Label__Text">Variant</label>
                            </div>
                        </div>
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                    <input id="birthday-gift_selectedVariant" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField21Label" aria-invalid="false" value ="${variant_displayName}" selected-variant-id="${variant_displayId}">
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>
                            </div>
                        </div>
                        <span class="selectedVariantErrorMsg" style="color:red; display:none;"></span>
                    </div>
                </div>

                <div class="Polaris-FormLayout__Item">
                    <div>
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label class="Polaris-Label__Text">&nbsp;</label>
                            </div>
                        </div>
                        <button class="Polaris-Button remove-selectedProductVariant" type="button">
                            <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Icon">
                                    <span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg>
                                    </span>
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            `);
});


jQuery('body').on('click', '.remove-selectedProductVariant', function () {
    // $('. birthdayGift-product').html(``);
    $('.sd_birthdayGiftCollection').html(`
                <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Select the product for birthday gift</label>
                        </div>
                    </div>
                    
                    <div style="max-height: none; overflow: visible;" class="birthdayGift-product" aria-expanded="true">
                        <div class="Polaris-FormLayout__Item">
                            <button class="Polaris-Button birthdayGiftProduct" id="birthdayGift_product" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon"><span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                        </svg>
                                    </span></span>
                                </span>
                                <span class="Polaris-Button__Text selectBdayGift">Select gift product</span>
                            </button>
                        </div>
                        <div class="Polaris-FormLayout__Item selectedProductVariant" style="display:flex;"> </div>
                        <div class="birthdayGiftproduct-Error sd_errors" style="display: none;color: red;">Select a product as a birthday gift.</div>
                    </div>
                </div>
            `);
});

jQuery('body').on('click', '.remove-discountedProductVariant', function () {
    // $('. birthdayGift-product').html(``);
    $('.sd_remove-collection').html(``);
    $('.sd_birthdayDiscountProduct').text('Add Product');
    $('.sd_discounted-collection').text('Add Collection');
    discountedProductObjId = [];
    discountedCollectionObjId = [];
});


$('.birthdayFormAllTextBox').on('input', function () {
    let textValue = $(this).val();
    let dataId = $(this).attr("data-id");
    let typeAttr = $(this).attr("type-attr");
    let previewClass = '.' + dataId;

    switch (typeAttr) {
        case 'text':
            $(previewClass).text(textValue);
            break;

        case 'color':
            $(previewClass).css({
                'color': textValue,
            });
            break;
        case 'bg-color':
            $(previewClass).css({
                'background': textValue,
            });
            break;

        case 'border-color':
            $(previewClass).css({
                'border-color': textValue,
            });
            break;

        case 'border-radius':
            $(previewClass).css({
                'border-radius': textValue + 'px',
            });
            break;

        case 'font-size':
            $(previewClass).css({
                'font-size': textValue + 'px',
            });
            break;

        case 'width':
            $(previewClass).css({
                'width': textValue + 'px',
            });
            break;

        case 'height':
            $(previewClass).css({
                'height': textValue + 'px',
            });
            break;

        case 'image':
            $(previewClass).attr({
                'src': textValue,
            });
            break;

        case 'gradient-color':
            var gradientCode = $(this).attr("gradient-code");

            if (gradientCode == 'cardBgColor') {
                var color1 = $('#card_bg_color1').val();
                var color2 = $('#card_bg_color2').val();
            }

            let gradient = 'linear-gradient(to right, ' + color1 + ', ' + color2 + ')';
            $(previewClass).css('background', gradient);
            break;
    }
});



jQuery("body").on("click", ".sd_save_birthday_widget", async function () {
    let serailize_object = serializeObject('sd_birthday_widget_settings');
    let ajaxUrl = 'save-birthday-widget';
    let a = $('.sd_birthday_widget_settings').serialize();
    let btnType = $(this).attr('btn-type');
    serailize_object.btnType = btnType;
    let fd = new FormData();
    // return false;
    fd.append('Obj', JSON.stringify(serailize_object, btnType));
    const loading = Loading.create(app);
    loading.dispatch(Loading.Action.START);
    let ajaxResult = await ajaxCall(ajaxUrl, fd);
    loading.dispatch(Loading.Action.STOP);
    if (ajaxResult.status) {
        show_toast(ajaxResult.message, false);
        location.reload();
    } else {
        show_toast(ajaxResult.message, true);
    }
});



jQuery("body").on("click", ".sd_birthday-gift-save", async function (e) {
    e.preventDefault();
    shopify.loading(true);
    $('.sd_errors').css('display', 'none');
    let Obj = {};
    let birthdayGiftData = {};
    let eligibleCustomerData = {};
    let ajaxCondition = true;
    let btnType = $(this).attr('btn-type');
    let selectedCustomers = $('.sd_customerSelect_box option:selected').val();
    let selectedbirthdayGiftType = $('.sd_birthdayGiftSelect_box option:selected').val();
    birthdayGiftData['selectedbirthdayGiftType'] = selectedbirthdayGiftType;
    eligibleCustomerData['selectedCustomers'] = selectedCustomers;

    if (selectedCustomers == 'none') {
        $('.sd_customerSelect_box-error').css('display', 'block');
        ajaxCondition = false;
    } else {
        if (selectedCustomers == 'min-amount') {
            let minAmount = $('#minimum-sale-amount').val();
            eligibleCustomerData['minAmount'] = minAmount;
            if (minAmount == '' || minAmount < 1) {
                $('.minimum-sale-amount-Error').css('display', 'block');
                ajaxCondition = false;
            }
        }

        if (selectedCustomers == 'min-orders') {
            let minOrder = $('#minimum-sale-order').val();
            eligibleCustomerData['minOrder'] = minOrder;
            if (minOrder == '' || minOrder < 1) {
                $('.minimum-sale-order-Error').css('display', 'block');
                ajaxCondition = false;
            }
        }
    }

    if (selectedbirthdayGiftType == 'none') {
        $('.sd_birthdayGiftSelect_box-error').css('display', 'block');
        ajaxCondition = false;
    } else {
        if (selectedbirthdayGiftType == 'product') {
            let productTitle = $('#birthday-gift_selectedProduct').val();
            let productID = $('#birthday-gift_selectedProduct').attr('selected-product-id');
            let variantTitle = $('#birthday-gift_selectedVariant').val();
            let variantID = $('#birthday-gift_selectedVariant').attr('selected-variant-id');
            if (productTitle === undefined && variantTitle === undefined) {
                $('.birthdayGiftproduct-Error').css('display', 'block');
                ajaxCondition = false;
            } else {
                birthdayGiftData['productTitle'] = productTitle;
                birthdayGiftData['productID'] = productID;
                birthdayGiftData['variantTitle'] = variantTitle;
                birthdayGiftData['variantID'] = variantID;
            }
        } else if (selectedbirthdayGiftType == 'discount') {
            let discountedCode = $('#sd_birthday-discount').val();
            let discountedPercentage = $('#sd_birthday-Percentage').val();

            if (discountedCode === '') {
                $('.sd_birthday-discount-Error').css('display', 'block');
                ajaxCondition = false;

            } else {
                birthdayGiftData['discountedCode'] = discountedCode;
            }
            if (discountedPercentage === '') {
                $('.sd_birthday-Percentage-Error').css('display', 'block');
                ajaxCondition = false;

            } else {
                birthdayGiftData['discountedPercentage'] = discountedPercentage;
            }

            let discountItemType = $('.sd_birthday-discountSelect_box option:selected').val();
            birthdayGiftData['discountItemType'] = discountItemType;

            if (discountItemType == 'collection') {
                let discountedCollectionTitle = $('#birthday-discounted_selectedCollection').val();
                let discountedCollectionId = $('#birthday-discounted_selectedCollection').attr('selected-collection-id');
                if (discountedCollectionTitle === undefined && discountedCollectionId === undefined) {
                    $('.sd_birthdayDiscountCollection-Error').css('display', 'block').text('Select the collection');
                    ajaxCondition = false;
                } else {
                    birthdayGiftData['discountedCollectionTitle'] = discountedCollectionTitle;
                    birthdayGiftData['discountedCollectionId'] = discountedCollectionId;
                }
            }

            if (discountItemType == 'product') {
                let discountedProductTitle = $('#birthday-discounted_selectedProduct').val();
                let discountedProductId = $('#birthday-discounted_selectedProduct').attr('selected-product-id');
                if (discountedProductTitle === undefined && discountedProductId === undefined) {
                    $('.sd_birthdayDiscountProduct-Error').css('display', 'block').text('Select the product');
                    ajaxCondition = false;
                } else {
                    birthdayGiftData['discountedProductTitle'] = discountedProductTitle;
                    birthdayGiftData['discountedProductId'] = discountedProductId;
                }
            }

        } else if (selectedbirthdayGiftType == 'freeshiping') {
            let freeshipCode = $('#sd_birthday-freeship').val();
            if (freeshipCode === '') {
                $('.sd_birthday-freeship-Error').css('display', 'block');
                ajaxCondition = false;

            } else {
                birthdayGiftData['freeshipCode'] = freeshipCode;
            }
        }

    }
    birthdayGiftData['btnType'] = btnType;
    Obj.eligibleCustomerData = eligibleCustomerData;
    Obj.birthdayGiftData = birthdayGiftData;
    let csrfToken = $('input[name="_token"]').val();
    shopify.loading(true);

    if (ajaxCondition) {
        $.ajax({
            type: "POST",
            url: "save-birthdaygift-details",
            data: {
                store: shop,
                Obj: Obj,
                _token: csrfToken
            },
            success: function (response) {
                show_toast(response.message, false);
                shopify.loading(false);
                location.reload();
            },
            error: function (error) {
                show_toast(error, true);
                // console.log(error);
                shopify.loading(false);
            }
        });
    }
});



$('body').on('change', '.sd_birthday-discountSelect_box', function () {
    $('.sd_errors').css('display', 'none');
    let selectedOptionText = $(this).find('option:selected').text();
    $('.sd_birthday_discount_select_value').text(selectedOptionText);
    let selectedOption = $(this).val();
    switch (selectedOption) {
        case 'all':
            $('.sd_birthday_discount_select_value').text('All products');
            $('.sd_birthdayAdd-Discount').html(``);
            break;
        case 'collection':
            $('.sd_birthday_discount_select_value').text('Specific collection');
            $('.sd_birthdayAdd-Discount').html(`
                <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Select the Collection for birthday gift</label>
                        </div>
                    </div>
                    
                    <div style="max-height: none; overflow: visible;" class="birthdayGift-product" aria-expanded="true">
                        <div class="Polaris-FormLayout__Item">
                            <button class="Polaris-Button sd_birthdayDiscountCollection sd_discounted-collection" id="sd_birthdayDiscountCollection" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon"><span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                        </svg>
                                    </span></span>
                                </span>
                                <span class="Polaris-Button__Text selectBdayCollection">Add Collection</span>
                            </button>
                        </div>
                        <div class="Polaris-FormLayout__Item selectedProductVariant" style="display:flex;"> </div>
                        <div class="sd_birthdayDiscountCollection-Error sd_errors" style="display: none;color: red;">Select a product as a birthday gift.</div>
                    </div>
                </div>
            `);
            break;
        case 'product':
            $('.sd_birthday_discount_select_value').text('Specific product');
            $('.sd_birthdayAdd-Discount').html(`
                <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Select the product for birthday gift</label>
                        </div>
                    </div>
                    
                    <div style="max-height: none; overflow: visible;" class="birthdayGift-product" aria-expanded="true">
                        <div class="Polaris-FormLayout__Item">
                            <button class="Polaris-Button sd_birthdayDiscountProduct sd_birthdayDiscountCollection" id="sd_birthdayDiscountProduct" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon"><span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                        </svg>
                                    </span></span>
                                </span>
                                <span class="Polaris-Button__Text selectBdayCollection">Add Product</span>
                            </button>
                        </div>
                        <div class="Polaris-FormLayout__Item selectedProductVariant" style="display:flex;"> </div>
                        <div class="sd_birthdayDiscountProduct-Error sd_errors" style="display: none;color: red;">Select a product as a birthday gift.</div>
                    </div>
                </div>
            `);
            break;
    }
});


let discountedProductObjId = [];
let discountedCollectionObjId = [];

$('body').on('click', '.sd_birthdayDiscountProduct', async function () {
    const selected = await shopify.resourcePicker({
        type: 'product',
        action: 'select',
        selectionIds: discountedProductObjId,
        multiple: false,
        filter: {
            hidden: false,
            variants: false,
        },
    });

    let productTitle = selected[0].title;
    let product_id = selected[0].id;

    collectionSelected_idsObjArray = [];

    if (discountedProductObjId == undefined) {
        discountedProductObjId = [];
    }

    if (discountedProductObjId.length > 0) {
        discountedProductObjId = [];
    }
    $('.sd_birthdayAdd-Discount').html(`

                <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Select the product for birthday gift</label>
                        </div>
                    </div>
                    
                    <div style="max-height: none; overflow: visible;" class="birthdayGift-product" aria-expanded="true">
                        <div class="Polaris-FormLayout__Item">
                            <button class="Polaris-Button sd_birthdayDiscountProduct sd_birthdayDiscountCollection" id="sd_birthdayDiscountProduct" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon"><span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                        </svg>
                                    </span></span>
                                </span>
                                <span class="Polaris-Button__Text selectBdayCollection">Change Product</span>
                            </button>
                        </div>
                        <div class="Polaris-FormLayout__Item selectedProductVariant" style="display:flex;"> </div>
                        <div class="sd_birthdayDiscountProduct-Error sd_errors" style="display: none;color: red;">Select a product as a birthday gift.</div>
                    </div>
                </div>

                <div class="Polaris-FormLayout__Item sd_remove-collection selectedProductVariant">
                    <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="PolarisTextField20Label" for="PolarisTextField20" class="Polaris-Label__Text">Product</label>
                            </div>
                        </div>
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                    <input name="birthday_gift-product_title" id="birthday-discounted_selectedProduct" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField20Label" aria-invalid="false" value="${productTitle}" selected-product-id="${product_id}">
                                    <div class="Polaris-TextField__Backdrop">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="Polaris-FormLayout__Item sd_remove-collection">
                    <div>
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label class="Polaris-Label__Text">&nbsp;</label>
                            </div>
                        </div>
                        <button class="Polaris-Button remove-discountedProductVariant " type="button">
                            <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Icon">
                                    <span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg>
                                    </span>
                                </span>
                                <span class="Polaris-Button__Text">Remove product</span>
                            </span>
                        </button>
                    </div>
                </div>
            `);
    let pro_obj = { 'id': product_id };
    discountedProductObjId.push(pro_obj);
    // $('.sd_birthdayDiscountProduct').text('Change Product');

    // });
});

$('body').on('click', '.sd_discounted-collection', async function () {

    const selected = await shopify.resourcePicker({
        type: 'collection',
        action: 'select',
        selectionIds: discountedCollectionObjId,
        multiple: false
    });

    let title = selected[0].title;
    let id = selected[0].id;

    collectionSelected_idsObjArray = [];
    // picker.dispatch(ResourcePicker.Action.OPEN);

    // picker.subscribe(ResourcePicker.Action.SELECT, (payload) => {
    if (discountedCollectionObjId == undefined) {
        discountedCollectionObjId = [];
    }

    if (discountedCollectionObjId.length > 0) {
        discountedCollectionObjId = [];
    }
    $('.sd_birthdayAdd-Discount').html(`

                <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">Select the Collection for birthday gift</label>
                        </div>
                    </div>
                    
                    <div style="max-height: none; overflow: visible;" class="birthdayGift-product" aria-expanded="true">
                        <div class="Polaris-FormLayout__Item">
                            <button class="Polaris-Button sd_birthdayDiscountCollection sd_discounted-collection" id="sd_birthdayDiscountCollection" type="button">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Icon"><span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                        </svg>
                                    </span></span>
                                </span>
                                <span class="Polaris-Button__Text selectBdayCollection">Change Collection</span>
                            </button>
                        </div>
                        <div class="Polaris-FormLayout__Item selectedProductVariant" style="display:flex;"> </div>
                        <div class="sd_birthdayDiscountCollection-Error sd_errors" style="display: none;color: red;">Select a product as a birthday gift.</div>
                    </div>
                </div>

                <div class="Polaris-FormLayout__Item selectedProductVariant sd_remove-collection">
                    <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="PolarisTextField20Label" for="PolarisTextField20" class="Polaris-Label__Text">Collection</label>
                            </div>
                        </div>
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField Polaris-TextField--hasValue Polaris-TextField--disabled">
                                    <input name="birthday_gift-Collection_title" id="birthday-discounted_selectedCollection" disabled="" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField20Label" aria-invalid="false" value="${title}" selected-collection-id="${id}">
                                    <div class="Polaris-TextField__Backdrop">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="Polaris-FormLayout__Item sd_remove-collection">
                    <div>
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label class="Polaris-Label__Text">&nbsp;</label>
                            </div>
                        </div>
                        <button class="Polaris-Button remove-discountedProductVariant" type="button">
                            <span class="Polaris-Button__Content">
                                <span class="Polaris-Button__Icon">
                                    <span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 3.994c0-1.101.895-1.994 2-1.994s2 .893 2 1.994h4c.552 0 1 .446 1 .997a1 1 0 0 1-1 .997h-12c-.552 0-1-.447-1-.997s.448-.997 1-.997h4zm-3 10.514v-6.508h2v6.508a.5.5 0 0 0 .5.498h1.5v-7.006h2v7.006h1.5a.5.5 0 0 0 .5-.498v-6.508h2v6.508a2.496 2.496 0 0 1-2.5 2.492h-5c-1.38 0-2.5-1.116-2.5-2.492z"></path></svg>
                                    </span>
                                </span>
                                <span class="Polaris-Button__Text">Remove Collection</span>
                            </span>
                        </button>
                    </div>
                </div>
            `);
    let col_obj = { 'id': id };
    discountedCollectionObjId.push(col_obj);
    // $('.sd_discounted-collection').text('Change Collection');

    // });
});


$('body').on('change', '.sd_birthdayWishMsg', function () {
    let selectedOptionText = $(this).find('option:selected').text();
    $('.sd_selectedCustomers').text(selectedOptionText);
    let selectedOption = $(this).val();
    console.log(selectedOption, 'selectedOption');
    switch (selectedOption) {

        case 'product':
            $('.sd_wish_message').css('display', 'none');
            $('.sd_wish_product').css('display', 'block');
            break;

        case 'discount':
            $('.sd_wish_message').css('display', 'none');
            $('.sd_wish_discount').css('display', 'block');
            break;

        case 'template':
            $('.sd_wish_message').css('display', 'none');
            $('.sd_wish_template').css('display', 'block');
            break;

        case 'freeshiping':
            $('.sd_wish_message').css('display', 'none');
            $('.sd_wish_freeshiping').css('display', 'block');
            break;
    }
});


$('.birthday-email-setting').on('input', function () {
    let textValue = $(this).val();
    let dataId = $(this).attr("data-id");
    let typeAttr = $(this).attr("type-attr");

    if (typeAttr == "checkbox") {
        textValue = $(this).is(":checked") ? 1 : 0;
    }

    console.log(textValue, 'value')
    console.log(dataId, 'dataId')
    console.log(typeAttr, 'typeAttr')
    let previewClass = '.' + dataId;

    switch (typeAttr) {
        case 'text':
            $(previewClass).text(textValue);
            break;

        case 'href':
            console.log(previewClass, 'previewClass');
            console.log(textValue, 'textValue');
            $(previewClass).attr("href", textValue);
            break;

        case 'checkbox':
            if (textValue) {
                $(previewClass).css("display", "block");
            } else {
                $(previewClass).css("display", "none");
            }
            break;

        case 'color':
            $(previewClass).css({
                'color': textValue,
            });
            break;
        case 'bg-color':
            $(previewClass).css({
                'background': textValue,
            });
            break;

        case 'border-color':
            $(previewClass).css({
                'border-color': textValue,
            });
            break;

        case 'border-radius':
            $(previewClass).css({
                'border-radius': textValue + 'px',
            });
            break;

        case 'font-size':
            $(previewClass).css({
                'font-size': textValue + 'px',
            });
            break;

        case 'width':
            $(previewClass).css({
                'width': textValue + 'px',
            });
            break;

        case 'opacity':
            $(previewClass).css({
                'opacity': textValue + '%',
            });
            break;

        case 'bg-opacity':
            let bgColor = $('.message-bg-div').css('background-color');
            let rgbaComponents = bgColor.match(/\d+/g);

            if (rgbaComponents.length === 3) {
                rgbaComponents.push('1'); // Default alpha value if not present
            }
            let alphaValue = parseFloat(textValue) / 100;
            $(previewClass).css(
                'background-color', 'rgba(' + rgbaComponents[0] + ', ' + rgbaComponents[1] + ', ' + rgbaComponents[2] + ', ' + alphaValue + ')'
            );
            break;

        case 'background-color': {
            $(previewClass).css({
                'background-color': textValue,
            });
        }
        case 'height':
            $(previewClass).css({
                'height': textValue + 'px',
            });
            break;

        case 'image':
            $(previewClass).attr({
                'src': textValue,
            });
            break;

        case 'border-width':
            $(previewClass).css({
                'border-width': textValue + 'px',
            });
            break;

        case 'text-area':
            textValue = textValue.replace(/\n/g, '<br>');
            $(previewClass).html(textValue);
            $('.sd_help_msg').text('Use <br> tags to break line');
            break;

        case 'bg_image':
            let path = $(this).attr("path-id");
            console.log('hhhh')
            changeBackground(textValue, previewClass, path);
            break;

        case 'gradient-color':
            var gradientCode = $(this).attr("gradient-code");
            console.log('gradientCode');
            console.log(gradientCode);
            console.log('previewClass');
            console.log(previewClass);

            if (gradientCode == 'cardBgColor') {
                var color1 = $('#card_bg_color1').val();
                var color2 = $('#card_bg_color2').val();
            }

            let gradient = 'linear-gradient(to right, ' + color1 + ', ' + color2 + ')';
            console.log('gradient');
            console.log(gradient);
            $(previewClass).css('background', gradient);
            break;
    }
});

function changeBackground(textValue, previewClass, path) {
    const backgrounds = {
        'background01': 'background01.jpg',
        'background02': 'background02.jpg',
        'background03': 'background03.jpg',
        'background04': 'background04.jpg',
        'background05': 'background05.jpg',
        'background06': 'background06.jpg',
        'background07': 'background07.jpg',
        'background08': 'background08.jpg',
        'background09': 'background09.jpg',
        'background10': 'background10.jpg',
        'background11': 'background11.jpg',
        'background12': 'background12.jpg',
        'background13': 'background13.jpg'
    };
    const imageUrl = path + 'public/assets/images/Background/' + backgrounds[textValue];
    $(previewClass).attr('src', imageUrl);
}

$('.numberValidation').on('input', function () {
    // Get the input value
    let value = parseInt($(this).val());

    // Ensure the value is within the range of 0 to 100
    if (value < 0) {
        $(this).val(0);
    } else if (value > 100) {
        $(this).val(100);
    }
});

$("body").on("click", ".sd_save_birthday_email_temp", async function () {
    let serialize_object = $('#sd_birthday_template_settings').serializeArray();
    let ajaxUrl = 'save-birthday-email-temp?store=' + shop;
    let btnType = $(this).attr('btn-type');
    let data = {};
    $.each(serialize_object, function (index, obj) {
        data[obj.name] = obj.value;
    });
    data.btnType = btnType;
    // console.log('formdata', data);
    const fd = new FormData();
    fd.append('obj', JSON.stringify(data));
    const loading = Loading.create(app);
    loading.dispatch(Loading.Action.START);
    try {
        const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: fd
        });
        const ajaxResult = await response.json();
        loading.dispatch(Loading.Action.STOP);
        if (ajaxResult.status) {
            show_toast(ajaxResult.message, false);
            location.reload();
        } else {
            show_toast(ajaxResult.message, true);
        }
    } catch (error) {
        console.error('Error:', error);
        loading.dispatch(Loading.Action.STOP);
        show_toast('An error occurred. Please try again later.', true);
    }
});



$("body").on("input", "#search_bd_customer", function () {
    var searchText = $(this).val().toLowerCase();

    $('.Polaris-DataTable__TableRow').each(function () {
        var row = $(this);
        var found = false;
        row.find('.Polaris-DataTable__Cell').each(function () {
            var cellText = $(this).text().toLowerCase();
            if (cellText.includes(searchText)) {
                found = true;
                return false;
            }
        });
        if (found) {
            row.show();
        } else {
            row.hide();
        }
    });
});

var customer_id = '';
var upgrade_member_plan_data = {};
var skip_member_plan_data = {};
var change_status_member_plan_data = {};


jQuery("body").on("click", ".sd-plan-upgrade-btn", function () {
    let upgradeOptionId = $('.optionCardSelected').attr('data-card-id');
    let contractId = $('.optionCardSelected').attr('plan-act-id');
    let option_charge_type = $('.optionCardSelected .optionChargeType').text();
    let active_plan = $('.planAndTierName').text();
    let optionChargeValue = $('.optionCardSelected .optionChargeValue').attr('time-value');
    let optionPriceValue = $('.optionCardSelected .optionPriceValue').attr('price-value');
    let product_line_id = $('#option_product_line_id').val();
    let customer_email = $(this).attr('attr-email');
    let customer_name = $(this).attr('attr-name');
    let customer_id = $(this).attr('attr-customer-id');
    let membership_group_id = $(this).attr('attr-group-id');
    active_plan = active_plan + '(' + option_charge_type + ')';
    let plan_action_type = $(this).text();
    let cardNo = $('.optionCardSelected').attr('card-no');

    upgrade_member_plan_data = {
        'upgradeOptionId': upgradeOptionId,
        'contractId': contractId,
        'option_charge_type': option_charge_type,
        'active_plan': active_plan,
        'optionChargeValue': optionChargeValue,
        'optionPriceValue': optionPriceValue,
        'product_line_id': product_line_id,
        'customer_email': customer_email,
        'customer_name': customer_name,
        'customer_id': customer_id,
        'active_plan': active_plan,
        'plan_action_type': plan_action_type,
        'cardNo': cardNo,
        'membership_group_id': membership_group_id
    };

    let modalType = jQuery(this).attr('sd-ui-modal-id');
    callShopifyModal(modalType);

});

jQuery('body').on('click', '.sd-delete-plan-tier', async function (e) {
    e.preventDefault();
    if (parent_element == 'create_memberPlan_wrapper') {
        all_member_plans_array.splice(member_plan_delete_index, 1);
        delete_member_card(member_plan_delete_index, '');
        delete_member_plan_modal.dispatch(Modal.Action.CLOSE);
        reset_form('sd-subscription-group-form');
        all_member_plans_array.splice(member_plan_delete_index, 1);
    } else {
        var ajaxUrl = 'delete-member-group';
        delete_member_group_data = {};
        delete_member_group_data['member_group_id'] = delete_member_group_id;
        delete_member_group_data['delete_variant_id'] = delete_variant_id;
        delete_member_group_data['delete_product_id'] = delete_product_id;
        var delete_member_group_formData = new FormData();
        delete_member_group_formData.append('ajaxData', JSON.stringify(delete_member_group_data));

        shopify.loading(true);
        closeShopifyModal();
        // var ajaxResult = await ajaxCall(ajaxUrl, delete_member_group_formData);

        let ajaxParameters = {
            method: "POST",
            dataValues: {
                data: JSON.stringify(delete_member_group_data), // ✅ Send FormData directly
                action: "delete-member-group"
            }
        };
    
        let ajaxResult = await AjaxCall(ajaxParameters);

        console.log('ajaxresult', ajaxResult);
        shopify.loading(false);

        show_toast(ajaxResult.message, ajaxResult.isError);
        if (ajaxResult.isError == false) {
            delete_member_card(member_plan_delete_index, '');
            reset_form('sd-subscription-group-form');
            // location.reload();
        }
    }
});

// function AjaxCall(data) {

//     return new Promise(function(Resolve, Reject) {
//         jQuery.ajax({
//             url: ajaxurl,
//             type: data.method,
//             data: data.dataValues,
//             dataType: "json",
//             success: function(result) {

//                 let json = jQuery.parseJSON(JSON.stringify(result));

//                 Resolve(json);

//             },

//             error: function(response) {

//             }

//         });

//     });

// }




jQuery('body').on('click', '.sd-upgrade-member-plan', async function (e) {
    e.preventDefault();
    customer_id = $('.planAndTierName').attr('attr-customer-id')
    console.log(customer_id, 'customer_id222');
    let ajaxUrl = 'upgrade-plan-option';
    upgrade_option_data = {};
    upgrade_option_data['shop'] = shop;
    upgrade_option_data['upgradeOptionId'] = upgrade_member_plan_data.upgradeOptionId;
    upgrade_option_data['contractId'] = upgrade_member_plan_data.contractId;
    upgrade_option_data['option_charge_type'] = upgrade_member_plan_data.option_charge_type;
    upgrade_option_data['optionChargeValue'] = upgrade_member_plan_data.optionChargeValue;
    upgrade_option_data['optionPriceValue'] = upgrade_member_plan_data.optionPriceValue;
    upgrade_option_data['product_line_id'] = upgrade_member_plan_data.product_line_id;
    upgrade_option_data['customer_email'] = upgrade_member_plan_data.customer_email;
    upgrade_option_data['active_plan'] = upgrade_member_plan_data.active_plan;
    upgrade_option_data['customer_name'] = upgrade_member_plan_data.customer_name;
    upgrade_option_data['plan_action_type'] = upgrade_member_plan_data.plan_action_type;
    upgrade_option_data['membership_group_id'] = upgrade_member_plan_data.membership_group_id;
    upgrade_option_data['customer_id'] = upgrade_member_plan_data.customer_id;

    let update_plan_formData = new FormData();
    update_plan_formData.append('ajaxData', JSON.stringify(upgrade_option_data));
    shopify.loading(true);
    closeShopifyModal();
    let ajaxResult = await ajaxCall(ajaxUrl, update_plan_formData);
    shopify.loading(false);
    show_toast(ajaxResult.message, ajaxResult.isError);
    if (ajaxResult.isError == false) {
        location.reload();
    }
});

jQuery('body').on('click', '.sd-plan-cancel-btn', async function (e) {
    e.preventDefault();
    let upgradeOptionId = $('.activatedOptionPlan').attr('data-card-id');
    let contractId = $('.activatedOptionPlan').attr('plan-act-id');
    let option_charge_type = $('.activatedOptionPlan .optionChargeType').text();
    let active_tier = $('.planAndTierName').attr('attr-tier');
    let active_plan = $('.planAndTierName').attr('attr-plan');
    let optionChargeValue = $('.optionCardSelected .optionChargeValue').attr('time-value');
    let optionPriceValue = $('.optionCardSelected .optionPriceValue').attr('price-value');
    let product_line_id = $('#option_product_line_id').val();
    let customer_email = $(this).attr('attr-email');
    let customer_name = $(this).attr('attr-name');
    let button_plan_type = $(this).attr('data-action');
    let customer_id = $('.planAndTierName').attr('attr-customer-id');
    let membership_group_id = $(this).attr('attr-group-id');

    active_plan = active_plan + '(' + option_charge_type + ')';
    let plan_action_type = $(this).text();
    let cardNo = $('.optionCardSelected').attr('card-no');

    change_status_member_plan_data = {
        'upgradeOptionId': upgradeOptionId,
        'contractId': contractId,
        'option_charge_type': option_charge_type,
        'active_plan': active_plan,
        'optionChargeValue': optionChargeValue,
        'optionPriceValue': optionPriceValue,
        'product_line_id': product_line_id,
        'customer_email': customer_email,
        'customer_name': customer_name,
        'customer_id': customer_id,
        'active_plan': active_plan,
        'plan_action_type': plan_action_type,
        'active_tier': active_tier,
        'button_plan_type': button_plan_type,
        'cardNo': cardNo,
        'membership_group_id': membership_group_id
    };
    let modalType = jQuery(this).attr('sd-ui-modal-id');
    callShopifyModal(modalType);
});


jQuery('body').on('click', '.sd-cancel-member-plan', async function (e) {
    e.preventDefault();
    let ajaxUrl = 'cancel-plan-option';
    customer_id = $('.planAndTierName').attr('attr-customer-id')
    cancel_option_data = {};
    cancel_option_data['store'] = shop;
    cancel_option_data['customerId'] = customer_id
    cancel_option_data['contract_id'] = change_status_member_plan_data.contractId;
    cancel_option_data['option_charge_type'] = change_status_member_plan_data.option_charge_type;
    cancel_option_data['optionChargeValue'] = change_status_member_plan_data.optionChargeValue;
    cancel_option_data['optionPriceValue'] = change_status_member_plan_data.optionPriceValue;
    cancel_option_data['product_line_id'] = change_status_member_plan_data.product_line_id;
    cancel_option_data['customer_email'] = change_status_member_plan_data.customer_email;
    cancel_option_data['active_plan'] = change_status_member_plan_data.active_plan;
    cancel_option_data['customer_name'] = change_status_member_plan_data.customer_name;
    cancel_option_data['active_tier'] = change_status_member_plan_data.active_tier;
    cancel_option_data['button_plan_type'] = change_status_member_plan_data.button_plan_type;
    cancel_option_data['membership_group_id'] = change_status_member_plan_data.membership_group_id;
    let update_plan_formData = new FormData();
    update_plan_formData.append('ajaxData', JSON.stringify(cancel_option_data));
    shopify.loading(true);
    closeShopifyModal();
    let ajaxResult = await ajaxCall(ajaxUrl, update_plan_formData);
    shopify.loading(false);
    show_toast(ajaxResult.message, ajaxResult.isError);
    if (ajaxResult.isError == false) {
        location.reload();
    }

});


jQuery('body').on('click', '.sd_skip_btn', async function (e) {
    e.preventDefault();
    let skip_customer_id = $(this).attr("customer-id");
    let skip_contract_id = $(this).attr("data-attr");
    let skip_date = $(this).attr("attr-date");
    let skip_plan_name = $(this).attr("attr-plan");
    let skip_tier_name = $(this).attr("attr-tier");

    skip_member_plan_data = {
        'skip_customer_id': skip_customer_id,
        'skip_contract_id': skip_contract_id,
        'skip_date': skip_date,
        'skip_plan_name': skip_plan_name,
        'skip_tier_name': skip_tier_name
    };
    let modalType = jQuery(this).attr('sd-ui-modal-id');
    callShopifyModal(modalType);
    // skip_member_plan.dispatch(Modal.Action.OPEN);
});

jQuery('body').on('click', '.sd-skip-member-plan', async function (e) {
    e.preventDefault();
    let csrfToken = $('input[name="_token"]').val();
    shopify.loading(true);
    $.ajax({
        type: "POST",
        url: "skip-member-plan",
        data: {
            shop: shop,
            skip_customer_id: skip_member_plan_data.skip_customer_id,
            skip_contract_id: skip_member_plan_data.skip_contract_id,
            skip_plan_name: skip_member_plan_data.skip_plan_name,
            skip_tier_name: skip_member_plan_data.skip_tier_name,
            skip_date: skip_member_plan_data.skip_date,
            _token: csrfToken

        },
        success: function (response) {
            if (response.status == true) {
                show_toast(response.message, false);
                location.reload();
            } else {
                show_toast(response.message, true);
            }
            shopify.loading(false);
        },
        error: function (error) {
            console.log(error);
            shopify.loading(false);
        }
    });
});

var delete_member_plan_data = {};
var delayTimer;

jQuery("body").on("click", ".delete_member_plan", function () {
    let delete_member_plan_id = jQuery(this).attr('data-member-id');
    let delete_member_group_ids = jQuery(this).attr('data-group-ids');
    let delete_member_product_type = jQuery(this).attr('data-member-productType');
    let delete_member_product_id = jQuery(this).attr('data-member-product_id');
    let delete_member_perk_freeshipping_ids = jQuery(this).attr('data-perk-freeshipping-ids');
    let delete_member_perk_pricerule_ids = jQuery(this).attr('data-perk-pricerule-ids');
    let modalType = jQuery(this).attr('sd-ui-modal-id');
    delete_member_plan_data = {
        'member_plan_id': delete_member_plan_id,
        'member_group_ids': delete_member_group_ids,
        'member_product_type': delete_member_product_type,
        'member_product_id': delete_member_product_id,
        'member_perk_freeshipping_ids': delete_member_perk_freeshipping_ids,
        'member_perk_pricerule_ids': delete_member_perk_pricerule_ids
    };
    callShopifyModal(modalType);
});

jQuery("body").on("click", ".go-to-step1", function () {
    jQuery('.show_selected_products').removeClass('display-hide-label');
    active_step('1');
});


jQuery("body").on("input", ".sd-polaris-number", function () {
    console.log('jjfbwefbwfbqwfqwfq');
    let inputValue = $(this).val();
    console.log('inputValue', inputValue);

    let regex = /^\d*\.?\d{0,2}$/;
    if (!regex.test(inputValue)) {
        $(this).val(inputValue.replace(/[^\d.]/g, '').replace(/(\.\d\d)\d+/g, '$1'));
    }
});

jQuery('body').on('click', '.display_remove_variant', function () {
    let deleted_variant_id = jQuery(this).attr('variant-id');
    jQuery('#display_variant_' + deleted_variant_id).remove();
    let variant_index = selected_prd_vrt_ids['variant_item_data'].findIndex(function (item) {
        return item.variant_id == deleted_variant_id
    });
    if (variant_index > -1) { // only splice array when item is found
        (selected_prd_vrt_ids['variant_item_data']).splice(variant_index,
            1); // 2nd parameter means remove one item only
    }
});

jQuery("body").on("change", ".subscription_discount_status", function () {
    if (jQuery(this).prop("checked") == true) {
        jQuery(this).closest('.member_plan_option_form').find(
            '.sd_subscription_discount_offer_wrapper,.sd_change_discount_after').removeClass(
                'display-hide-label');
    } else {
        jQuery(this).closest('.member_plan_option_form').find('.subscription_discount_after_status').prop(
            'checked', false);
        jQuery(this).closest('.member_plan_option_form').find(
            '.subscription_discount_value,.change_discount_after_cycle,.discount_value_after').val('');
        jQuery(this).closest('.member_plan_option_form').find(
            '.sd_subscription_discount_offer_wrapper,.sd_change_discount_after,.sd_subscription_discount_offer_after_wrapper'
        ).addClass('display-hide-label');
    }
});
jQuery("body").on("change", ".subscription_discount_after_status", function () {
    if ($(this).prop("checked") == true) {
        jQuery(this).closest('.member_plan_option_form').find(
            '.sd_subscription_discount_offer_after_wrapper').removeClass('display-hide-label');
        // scrollToBottom('.sd_selling_form');
    } else {
        jQuery(this).closest('.member_plan_option_form').find(
            '.change_discount_after_cycle,.discount_value_after').val('');
        jQuery(this).closest('.member_plan_option_form').find(
            '.sd_subscription_discount_offer_after_wrapper').addClass('display-hide-label');
    }
});


jQuery('body').on('click', '.sd-delete-membership-plan', async function (e) {
    e.preventDefault();
    shopify.loading(true);
    // let ajaxUrl = 'delete-member-plan';
    // let delete_member_plan_formData = new FormData();
    // delete_member_plan_formData.append('ajaxData', JSON.stringify(delete_member_plan_data));
    closeShopifyModal();
    let ajaxParameters = {

        method: "POST",

        dataValues: {
            data: delete_member_plan_data,
            action: "deleteMemberPlan"

        }
    };

    let ajaxResult = await AjaxCall(ajaxParameters);
    // let ajaxResult = await ajaxCall(ajaxUrl, delete_member_plan_formData);
    show_toast(ajaxResult.message, ajaxResult.isError);
    if (ajaxResult.isError == false) {
        location.reload();
    }
    shopify.loading(false);
});

jQuery('body').on('click', '#view-member-details', function () {
    shopify.loading(true);
    let single_details_member_id = jQuery(this).attr('plan-customer-id');
    let fullURL = `${SHOPIFY_DOMAIN_URL}/admin/memberships/memberships.php?single_member_id=${single_details_member_id}&shop=${shop}&host=${host}`;
    open(fullURL, '_self');
});

jQuery('body').on('click', '#view-customer-details', function () {
    shopify.loading(true);
    let single_details_member_id = jQuery(this).attr('plan-customer-id');
    let fullURL = `${app_redirect_url}customer-view-details?single_member_id=${single_details_member_id}&shop=${shop}&host=${host}`;
    open(fullURL, '_self');
});

jQuery('body').on('click', '#edit-birthday-rewards', function () {
    shopify.loading(true);
    let single_details_member_id = jQuery(this).attr('plan-customer-id');
    let fullURL = `${app_redirect_url}edit-rewards-details?single_member_id=${single_details_member_id}&shop=${shop}&host=${host}`;
    open(fullURL, '_self');

});

jQuery('body').on('click', '.previous_member_details-button', function () {
    shopify.loading(true);
    let fullURL = `${app_redirect_url}members?shop=${shop}&host=${host}`;
    open(fullURL, '_self');
});


jQuery('body').on('click', '.previous_member_profile-button', function () {
    shopify.loading(true);
    let customer_plan_id = jQuery(this).attr('customer-id');
    let fullURL = `${app_redirect_url}member-view-details?single_member_id=${customer_plan_id}&shop=${shop}&host=${host}`;
    open(fullURL, '_self');

});

jQuery('body').on('click', '.edit-membership-perk', function () {
    shopify.loading(true);
    let get_member_id = jQuery(this).attr("data-member-id");
    let fullURL = `${SHOPIFY_DOMAIN_URL}/admin/memberships/perks.php?get_member_id=${get_member_id}&shop=${shop}&host=${host}`;
    open(fullURL, '_self');
});

jQuery('body').on('keyup', '#search-membership-text', function () {
    clearTimeout(delayTimer);
    delayTimer = setTimeout(function () {
        let search_subscription_text = $('#search-membership-text').val();
        if (search_subscription_text !== '') {
            let fullURL = `${SHOPIFY_DOMAIN_URL}/admin/memberships/memberships.php?search_member_plan=${encodeURIComponent(search_subscription_text)}&shop=${shop}&host=${host}`;
            console.log(fullURL, 'fullURLfullURL')
            open(fullURL, '_self');
        } else {
            let fullURL = `${SHOPIFY_DOMAIN_URL}/admin/memberships/memberships.php?search_member_plan=${''}&shop=${shop}&host=${host}`;
            console.log(fullURL, 'fullURLfullURL')
            open(fullURL, '_self');
        }
    }, 1000);
});

jQuery("body").on('change', '.per_delivery_order_frequency_type', function (e) { //sd_prepaid_fullfillment_type
    let selected_value = $(this).val();
    jQuery(this).closest('.member_plan_option_form').find(
        '.sd_set_anchor_date_wrapper,.sd_anchor_option_wrapper,.sd_anchor_month_day_wrapper,.sd_anchor_week_day_wrapper,.cut_off_days'
    ).addClass('display-hide-label');
    jQuery(this).closest('.member_plan_option_form').find('.sd_set_anchor_date').prop('checked', false);
    jQuery(this).closest('.member_plan_option_form').find(
        '.sd_anchor_option .Polaris-Select__SelectedOption').first().text('On Purchase Day');
    jQuery(this).closest('.member_plan_option_form').find(
        '.sd_anchor_option .Polaris-Select__SelectedOption').first().text('On Purchase Day');
    jQuery(this).closest('.member_plan_option_form').find('.sd_anchor_option option').prop('selected',
        false);
    jQuery(this).closest('.member_plan_option_form').find(".sd_anchor_option option[value='On Purchase Day']").prop('selected', true);
});

jQuery("body").on('change', '.sd_select_option', function (e) {
    let selected_value = jQuery(this).val();
    change_select_html('#sd_select_option', selected_value);
});

jQuery("body").on("change", ".Polaris-Select__Input", function () {
    let selected_option_value = jQuery(this).val();
    jQuery(this).parent().find(".Polaris-Select__SelectedOption").html(selected_option_value);
});

jQuery("body").on("change", ".sd_set_anchor_date", function () {
    if (jQuery(this).prop("checked") == true) {
        $(this).closest('.member_plan_option_form').find('.sd_anchor_option_wrapper').removeClass(
            'display-hide-label');
    } else {
        jQuery(this).closest('.member_plan_option_form').find('.sd_anchor_option').parent().find(
            ".Polaris-Select__SelectedOption").html('On Purchase Day');
        jQuery(this).closest('.member_plan_option_form').find('.sd_anchor_option option').prop('selected',
            false);
        jQuery(this).closest('.member_plan_option_form').find(
            ".sd_anchor_option option[value='On Purchase Day']").prop('selected', true);
        jQuery(this).closest('.member_plan_option_form').find(
            '.sd_anchor_option_wrapper,.sd_anchor_month_day_wrapper,.sd_anchor_week_day_wrapper,.cut_off_days'
        ).addClass('display-hide-label');
    }
});
jQuery("body").on("change", ".sd_anchor_option", function () {
    if ((jQuery(this).closest('.member_plan_option_form').find('.sd_anchor_option option:selected')
        .val()) == 'On Purchase Day') {
        jQuery(this).closest('.member_plan_option_form').find(
            '.sd_anchor_week_day_wrapper,.sd_anchor_month_day_wrapper,.cut_off_days').addClass(
                'display-hide-label');
    } else {
        let delivery_billing_type = jQuery(this).closest('.member_plan_option_form').find(
            '.per_delivery_order_frequency_type option:selected').val();
        if (delivery_billing_type == 'WEEK') {
            jQuery(this).closest('.member_plan_option_form').find(
                '.sd_anchor_week_day_wrapper,.cut_off_days').removeClass('display-hide-label');
        } else {
            jQuery(this).closest('.member_plan_option_form').find(
                '.sd_anchor_month_day_wrapper,.cut_off_days').removeClass('display-hide-label');
        }
    }
});

jQuery('body').on('click', '.tierDetailsCard', function () {
    let upgradeCardId = $(this).attr('id');
    let unActCardNo = $(this).attr('card-no');
    let actCardNo = $('.activatedOptionPlan').attr('card-no');
    $(".tierDetailsCard").removeClass('optionCardSelected');
    $(".tierDetailsCard").css('border', '1px solid #edeff1');


    if (upgradeCardId) {
        $(this).addClass('optionCardSelected');
        $(this).css('border', '1px solid green');
        $('.sd-plan-upgrade-btn').prop('disabled', false);
        $('.sd-plan-upgrade-btn').css({
            opacity: 1,
            cursor: 'default'
        });
    }
    if (actCardNo > unActCardNo) {
        $('.sd-plan-upgrade-btn').text('Downgrade');
        $('.sd-plan-cancel-btn').css({
            opacity: 0.6,
            cursor: 'not-allowed',
        });
        $('.sd-plan-cancel-btn').prop('disabled', true);
    } else if (actCardNo < unActCardNo) {
        $('.sd-plan-upgrade-btn').text('Upgrade');
        $('.sd-plan-cancel-btn').css({
            opacity: 0.6,
            cursor: 'not-allowed',
        });
        $('.sd-plan-cancel-btn').prop('disabled', true);
    } else {
        $('.sd-plan-upgrade-btn').css({
            opacity: 0.6,
            cursor: 'not-allowed',
        });
        $('.sd-plan-upgrade-btn').prop('disabled', true);
        $('.sd-plan-cancel-btn').prop('disabled', false);
        $('.sd-plan-cancel-btn').css({
            opacity: 1,
            cursor: 'default'
        });
    }
    $(".activatedOptionPlan ").css('border', '1px solid #edeff1');
});


jQuery('body').on('click', '#edit-member-plan-details', function () {
    shopify.loading(true);
    let membership_id = jQuery(this).attr('membership_id');
    let customer_id = jQuery(this).attr('customer-id');
    let tier_id = jQuery(this).attr('tier-id');
    let membership_group_id = jQuery(this).attr('membership_group_id');

    if (membership_id && customer_id && tier_id && membership_group_id) {
        let fullURL = `${app_redirect_url}edit-member-profile?membership_id=${membership_id}&customer_id=${customer_id}&tier_id=${tier_id}&membership_group_id=${membership_group_id}&shop=${shop}&host=${host}`;
        open(fullURL, '_self');
    } else {
        shopify.toast.show('Request not processed due to insufficient details', { isError: true });
    }
    shopify.loading(false);
});

// change widget option on input the tier optiona
jQuery('body').on('input', '.tier_option_price, .option_charge_value, .per_delivery_order_frequency_type', function () {
    let parentDiv = $(this).closest('.member_plan_option_form');
    let dataId = '';
    if (parentDiv.length > 0) {
        dataId = parentDiv.data('id');
    }
    let option_price = jQuery(this).closest('.member_plan_option_form .tier_option_price').val();
    let option_value = jQuery(this).closest('.member_plan_option_form .option_charge_value').val();
    let option_charge_type = jQuery(this).closest('.member_plan_option_form').find('select[name=per_delivery_order_frequency_type]').val();

    if (option_price !== undefined) {
        jQuery('.sd_plan_option' + dataId + ' .sd_option_price').text(option_price);
    } else if (option_value !== undefined) {
        jQuery('.sd_plan_option' + dataId + ' .sd_option_value').text(option_value);
    }
    jQuery('.sd_plan_option' + dataId + ' .sd_option_type').text(option_charge_type.toLowerCase());
});



jQuery('body').on('input', '.membershipAllTextBox', function () {
    // console.log('first')
    let textValue = $(this).val();
    console.log(textValue, 'val')
    let dataId = $(this).attr("data-id");
    console.log(dataId, 'dataId')
    let typeAttr = $(this).attr("type-attr");
    console.log(typeAttr, 'dataId')
    let previewClass = '.' + dataId;
    switch (typeAttr) {
        case 'text':
            $(previewClass).text(textValue);
            break;
        case 'color':
            $(previewClass).css({
                'color': textValue,
            });
            break;
        case 'bg-color':
            $(previewClass).css({
                'background': textValue,
            });
            break;
        case 'tick-color':
            previewClass = previewClass + ' ' + 'path';
            $(previewClass).css({
                'fill': textValue,
            });
            break;
        case 'border-color':
            $(previewClass).css({
                'border-color': textValue,
            });
            break;
        case 'border-radius':
            $(previewClass).css({
                'border-radius': textValue + 'px',
            });
            break;
        case 'text-align':
            $(previewClass).css({
                'text-align': textValue,
            });
            break;
        case 'headingTag-change':
            console.log('preview class = ', previewClass);
            console.log('text value = ', textValue);
            $(previewClass).replaceWith(function () {
                return $('<' + textValue + '>', {
                    html: $(this).html(),
                    class: $(this).attr('class'),
                    style: $(this).attr('style')
                });
            });
            break;
        case 'gradient-color':
            let gradientCode = $(this).attr("gradient-code");
            let color1 = color2 = '';

            if (gradientCode == 'cardBgColor') {
                color1 = $('#planBgColor1').val();
                color2 = $('#bgColor2').val();
            } else if (gradientCode == 'offerBgColor') {
                color1 = $('#offerBgColor1').val();
                color2 = $('#offerBgColor2').val();
            } else if (gradientCode == 'mostPopBgColor') {
                color1 = $('#mostPopBgColor1').val();
                color2 = $('#mostPopBgColor2').val();
            } else if (gradientCode == 'widgetBgColor') {
                color1 = $('#background_color1').val();
                color2 = $('#background_color2').val();
            } else if (gradientCode == 'activeOptionBGcolor') {
                color1 = $('#active_option_bgColor1').val();
                color2 = $('#active_option_bgColor2').val();
            }

            let gradient = 'linear-gradient(to right, ' + color1 + ', ' + color2 + ')';
            $(previewClass).css('background', gradient);
            break;
    }
});


jQuery('body').on('input', '.countDownAllTextBox', function () {
    // console.log('new');
    let textValue = $(this).val();
    let dataId = $(this).attr("data-id");
    let typeAttr = $(this).attr("type-attr");
    let previewClass = '.' + dataId;
    switch (typeAttr) {
        case 'text':
            $(previewClass).text(textValue);
            break;
        case 'color':
            $(previewClass).css({
                'color': textValue,
            });
            break;
        case 'bg-color':
            $(previewClass).css({
                'background': textValue,
            });
            break;
        case 'border-color':
            $(previewClass).css({
                'border-color': textValue,
            });
            break;
        case 'border-radius':
            $(previewClass).css({
                'border-radius': textValue + 'px',
            });
            break;
        case 'text-align':
            $(previewClass).css({
                'text-align': textValue,
            });
            break;

        case 'gradient-color':
            let gradientCode = $(this).attr("gradient-code");
            let color1 = color2 = '';
            if (gradientCode == 'outerCountDownBgColor') {
                color1 = $('#outer_bgcolor1').val();
                color2 = $('#outer_bgcolor2').val();
            }
            if (gradientCode == 'innerCountDownBgColor') {
                color1 = $('#inner_bgcolor1').val();
                color2 = $('#inner_bgcolor2').val();
            }
            let gradient = 'linear-gradient(to right, ' + color1 + ', ' + color2 + ')';
            $(previewClass).css('background', gradient);
            break;
    }
});


jQuery("body").on("click", ".sd_ProductWidgetsValue", async function () {
    let serailize_object = serializeObject('sd_product_widget_settings');
    let btnType = $(this).attr('btn-type');
    serailize_object.btnType = btnType;
    // let fd = new FormData();
    // fd.append('ajaxData', JSON.stringify(serailize_object,));

    shopify.loading(true);

    let ajaxParameters = {
        method: "POST",
        dataValues: {
            serailize_object,
            action: "save-product-widget"
        }
    };
    let ajaxResult = await AjaxCall(ajaxParameters);
    shopify.loading(false);
    if (ajaxResult.status) {
        shopify.toast.show(ajaxResult.message, false);
        location.reload();
    } else {
        shopify.toast.show(ajaxResult.message, { isError: true });
    }

});


jQuery("body").on("click", ".sd_planWidgetsValue", async function () {
    console.log('first')
    let serailize_object = serializeObject('sd_plan_widget_settings');
    let ajaxUrl = 'save-widget-settings';
    let a = $('#sd_plan_widget_settings').serialize();
    let btnType = $(this).attr('btn-type');
    serailize_object.btnType = btnType;
    console.log('formdata', serailize_object);
    // console.log(btnType); 
    // let fd = new FormData();
    // console.log(fd, 'fd')
    // fd.append('ajaxData', JSON.stringify(serailize_object, btnType));
    // console.log(fd, 'fd')
    shopify.loading(true);
    let ajaxParameters = {
        method: "POST",
        dataValues: {
            serailize_object,
            action: "save-widget-settings"
        }
    };
    let ajaxResult = await AjaxCall(ajaxParameters);
    shopify.loading(false);
    console.log('widget ajax', ajaxResult);

    if (ajaxResult.status) {
        shopify.toast.show(ajaxResult.message, false);
        location.reload();
    } else {
        shopify.toast.show(ajaxResult.message, { isError: true });
    }
});

jQuery("body").on("click", ".sd_SaleCountDownValue", async function () {
    console.log('first')
    let serailize_object = serializeObject('sd_sale_countdown_settings');
    let ajaxUrl = 'save-countdown-settings';
    let a = $('#sd_sale_countdown_settings').serialize();
    let btnType = $(this).attr('btn-type');
    let cart_btn = $('.show_cart_btn').is(":checked");
    cart_btn = cart_btn ? "1" : "0";
    serailize_object.btnType = btnType;
    serailize_object.show_cart_btn = cart_btn;
    // console.log('formdata', serailize_object);
    // console.log(btnType);

    // let fd = new FormData();

    // fd.append('ajaxData', JSON.stringify(serailize_object, btnType));

    shopify.loading(true);
    let ajaxParameters = {
        method: "POST",
        dataValues: {
            serailize_object,
            action: "save-countdown-settings"
        }
    };
    let ajaxResult = await AjaxCall(ajaxParameters);
    shopify.loading(false);


    if (ajaxResult.status) {
        shopify.toast.show(ajaxResult.message, { isError: false });
        location.reload();
    } else {
        shopify.toast.show(ajaxResult.message, { isError: true });
    }
});

jQuery("body").on("click", "#cancel-member-plan-details, .sd_edit_memberProfile", async function () {
    let dropdownContent = jQuery(this).siblings('.dropdown-content');
    jQuery('.dropdown-content').not(dropdownContent).removeClass("show");
    dropdownContent.toggleClass("show");
});


// ............................................Purchase Subscription>>>>>>>>>>>>>>>>>>

jQuery("body").on("click", ".sd_subscription_purchase", async function () {
    let subscriptionType = jQuery(this).attr('subscription-type');
    let csrfToken = $('input[name="_token"]').val();
    // console.log(subscriptionType,'subscriptionType');
    $.ajax({
        type: "POST",
        url: "purchased-app-pricing",
        data: {
            shop: shop,
            subscriptionType: subscriptionType,
            _token: csrfToken
        },
        success: function (response) {
            if (response.status == true) {
                window.top.location.href = response.confirmationUrl;
            } else {
                shopify.toast.show(response.message, { isError: true });
            }
        },
        error: function (error) {
            shopify.toast.show(error, { isError: true });
        }
    });
})



// save early sale products/collections...................................................

jQuery("body").on("click", ".sd_SaveEarlyAccessData", async function (e) {
    e.preventDefault();
    console.log('kkkk');
    jQuery('.sd_earlySaleErrors').css("display", "none");
    let buttonType = $(this).attr('btn-type');
    let sale_start_date = $('#earlySaleAccessStartDate').val();
    let sale_end_date = $('#earlySaleAccessEndDate').val();
    let sale_discount = $('#earlySalePercentage').val();
    let saleValidation = true;
    let csrfToken = $('input[name="_token"]').val();
    let selectBoxValue = $('.saleAppliedSelect_box').find(":selected").val();
    let collectionIds = [];
    let collectionTitles = [];
    let collectionImages = [];
    let productIds = [];
    let productTitles = [];
    let productImages = [];

    shopify.loading(true);

    if (sale_start_date == '') {
        jQuery('.earlyAccessSaleStartDate-Error').css("display", "block");
        saleValidation = false;
    }
    if (sale_end_date == '') {
        jQuery('.earlyAccessSaleEndDate-Error').css("display", "block");
        saleValidation = false;
    }

    if (sale_discount == 0 || sale_discount == '') {
        jQuery('.earlySalePercentage-Error').css("display", "block").text('Enter percentage value & should be greater than 0');
        saleValidation = false;
    }

    const date1 = new Date(sale_start_date);
    const date2 = new Date(sale_end_date);
    if (date1 >= date2) {
        jQuery('.earlyAccessSaleEndDate-Error').css("display", "block").text("End date can't be equal to or earlier than start date");
        saleValidation = false;
    }

    if (selectBoxValue == 'Specific products') {
        let elements = document.getElementsByClassName("get-product-info");
        for (let i = 0; i < elements.length; i++) {
            let productId = elements[i].getAttribute("product-id");
            let productTitle = elements[i].getAttribute("product-title");
            let productImage = elements[i].getAttribute("product-image");
            productIds.push(productId);
            productTitles.push(productTitle);
            productImages.push(productImage);
        }
        if (productIds.length == 0) {
            jQuery('.sd_selected_sale_product-Error').css("display", "block").text("Add products");
            saleValidation = false;
        }
    }
    if (selectBoxValue == 'Specific collections') {
        let elements = document.getElementsByClassName("get-product-info");
        // Iterate through the elements and extract collection IDs

        for (let i = 0; i < elements.length; i++) {
            let collectionId = elements[i].getAttribute("product-id");
            let collectionTitle = elements[i].getAttribute("product-title");
            let collectionImage = elements[i].getAttribute("product-image");
            collectionIds.push(collectionId);
            collectionTitles.push(collectionTitle);
            collectionImages.push(collectionImage);
        }

        if (collectionIds.length == 0) {
            jQuery('.sd_selected_sale_collection-Error').css("display", "block").text("Add Collections");
            saleValidation = false;
        }
    }

    shopify.loading(false);


    if (saleValidation) {
        shopify.loading(true);

        var ajaxUrl = 'save-earlySale-data';

        // customer_tag_array['customer_tag'] = member_plan_tier_handle;

        // var fd = new FormData();
        // fd.append('ajaxData', JSON.stringify(customer_tag_array));
        // fd.append('action', 'save-earlySale-data'); // ✅ Add action directly to FormData

        shopify.loading(true);

        let ajaxParameters = {
            method: "POST",
            dataValues: {
                sale_start_date: sale_start_date,
                buttonType: buttonType,
                sale_end_date: sale_end_date,
                sale_discount: sale_discount,
                productIds: productIds,
                collectionIds: collectionIds,
                productTitles: productTitles,
                collectionTitles: collectionTitles,
                collectionImages: collectionImages,
                productImages: productImages,
                selectBoxValue: selectBoxValue,
                action: "save-earlySale-data"
            }
        };
        console.log(ajaxParameters, 'ajaxParameters')
        var ajaxResult = await AjaxCall(ajaxParameters);

        shopify.loading(false);
        // console.log('ajax resultttttttttttt = ', ajaxResult);
        if (ajaxResult.isError === true) {
            shopify.toast.show("Something went wrong", { isError: true });
        } else {
            if (ajaxResult.status === true) {
                shopify.toast.show(ajaxResult.message, { isError: false });
            } else {
                shopify.toast.show(ajaxResult.message, { isError: true });
            }
        }

    }
});


jQuery("body").on("click", "#sd_contactUs", function (e) {
    e.preventDefault();
    $('#invalid_email1').hide();
    let csrfToken = $('input[name="_token"]').val();
    let sd_query_type = $('#sd_query_type').val();
    let sd_customerName = $('#sd_customerName').val().trim();
    let sd_customerEmail = $('#sd_customerEmail').val();
    let sd_customerMsg = $('#sd_customerMsg').val().trim();

    if (sd_query_type == '' || sd_customerEmail == '' || sd_customerName == '' || sd_query_type == '' || sd_customerMsg == '') {
        shopify.toast.show('Please fill all the fields', { isError: true });
        return false;
    }
    if (checkEmailValid(sd_customerEmail) == false) {
        $('#invalid_email1').show();
        return false;
    }

    shopify.loading(true);

    $.ajax({
        type: "POST",
        url: "send-custom-perk",
        data: {
            shop: shop,
            sd_query_type: sd_query_type,
            sd_customerName: sd_customerName,
            sd_customerShop: shop,
            sd_customerEmail: sd_customerEmail,
            sd_customerMsg: sd_customerMsg,
            _token: csrfToken
        },
        success: function (response) {
            if (response.status == true) {
                shopify.toast.show(response.message, { isError: false });
            } else {
                shopify.toast.show(response.message, { isError: true });
            }
            shopify.loading(false);
        },
        error: function (error) {
            shopify.toast.show('Email not sent', { isError: true });
            shopify.loading(false);
        }
    });

});

jQuery("body").on("change", "#sd_select_countdown_layout", function () {
    let selectedValue = $(this).val();
    console.log("Selected Layout: " + selectedValue);
    $('.countdown_lauout_main').css('display', 'none');
    $('.' + selectedValue).css('display', 'block');
});


jQuery("body").on("click", ".sd_reset_email_template", function () {

    shopify.loading(true);

    let template_name = $(this).attr("data-id");
    let csrfToken = $('input[name="_token"]').val();

    $.ajax({
        type: "POST",
        url: "reset-email-template",
        data: {
            shop: shop,
            template_name: template_name,
            _token: csrfToken
        },
        success: function (response) {
            if (response.status == true) {
                shopify.toast.show(response.message, { isError: false });
                shopify.loading(false);
                location.reload();
            } else {
                shopify.toast.show(response.message, { isError: true });
                shopify.loading(false);
            }
        },
        error: function () {
            shopify.toast.show('An error occurred while checking the username.', { isError: true });
            shopify.loading(false);
        }
    });
});



jQuery('body').on('input', '.numberPercentage', function () {
    let inputValue01 = $(this).val();
    inputValue01 = inputValue01.replace(/[^0-9]/g, '');
    inputValue01 = Math.min(100, inputValue01);
    $(this).val(inputValue01);
});

function extractNumberFromId(id) {
    var match = id.match(/\d+/);
    return match ? match[0] : null;
}

jQuery('body').on('blur', '.checkCodeExists', function () {
    let checkDiscountCode = $(this).attr("code-value");
    let group_id = $(this).attr('id');
    group_id = extractNumberFromId(group_id);
    let codeValue = $(this).val().trim();

    if (codeValue !== checkDiscountCode) {
        checkDiscountCoupan(codeValue, group_id);
    }
});


// jQuery('body').on('click', '.selectTemplate', function () {
//     let radioId = $(this).data("radio-id");
//     $('input[type="radio"]').prop("checked", false);
//     $("#" + radioId).prop("checked", true);
//     $("input[name='gender']:checked").val();
//     let targetTab = $(this).attr("target-tab");
//     $('.editorSelectTemplate').attr("target-tab", targetTab);
// });


jQuery('body').on('change', '.email_template_notification_membership', async function () {
 
    let template_name = $(this).attr('data-field');
    let csrfToken = $('input[name="_token"]').val();
    let checkBoxValue = '';
    if ($(this).is(':checked')) {
        checkBoxValue = 1;
    } else {
        checkBoxValue = 0;
    }

    let ajaxParameters = {

        method: "POST",
        dataValues: {
            checkBoxValue: checkBoxValue,
            shop: shop,
            column_name: template_name,
            action: "email-notification-settings"
        }
    };

    var ajaxResult = await AjaxCall(ajaxParameters);

    if (ajaxResult.isError === true) {
        shopify.toast.show("An error occurred while update the setting", { isError: true });
    } else {
        if (ajaxResult.status === true) {
            console.log('true')
            shopify.toast.show(ajaxResult.message, { isError: false });
        } else {
            console.log('false')
            shopify.toast.show(ajaxResult.message, { isError: true });
        }
    }
});

jQuery('body').on('click', '.sd_planDrawerSetting', async function () {
    let serailize_object = serializeObject('sd_discount_drawer_settings');
    let btnType = $(this).attr('btn-type');
    serailize_object.btnType = btnType;
    // let ajaxUrl = 'save-drawer-setting';
    // let fd = new FormData();
    // fd.append('ajaxData', JSON.stringify(serailize_object,));
    shopify.loading(true);
    let ajaxParameters = {
        method: "POST",
        dataValues: {
            serailize_object,
            action: 'save-drawer-setting'

        }
    };
    shopify.loading(true);
    let ajaxResult = await AjaxCall(ajaxParameters);
    shopify.loading(false);
    // console.log(ajaxResult);
    if (ajaxResult.status) {
        shopify.toast.show(ajaxResult.message, { isError: false });
        location.reload();
    } else {
        shopify.toast.show(ajaxResult.message, { isError: true });
        location.reload();
    }
})

// Customer Portal Script code
jQuery('body').on('click', '.sd_CustomerPortalValues', function (e) {
    e.preventDefault();
    let customer_portal_data = serializeObject('sd_customer_portal_form');
    let csrfToken = $('input[name="_token"]').val();
    let btnType = $(this).attr('btn-type');
    $.ajax({
        url: 'membership-customer-portal',
        method: 'POST',
        data: {
            shop: shop,
            store: shop,
            btnType: btnType,
            customer_portal_data: customer_portal_data,
            _token: csrfToken
        },
        success: function (response) {
            shopify.toast.show(response.message, { isError: false });
            location.reload();
        },
        error: function (xhr, status, error) {
            shopify.toast.show(error.message, { isError: true });
        }
    });
});

jQuery('body').on('click', '.Polaris-TopBar__NavigationIcon', function () {
    $('.for__mobile').css('display', 'block')
    $('.sd_toggle_cross').css('display', 'block')
})

jQuery('body').on('click', '.sd_toggle_cross', function () {
    $('.for__mobile').css('display', 'none')
    $(this).hide()
})



jQuery('body').on('change', '.popular_plan_checkbox', function () {
    let check_box = '';
    if (jQuery(this).prop('checked')) {
        check_box = true;
    } else {
        check_box = false;
    }
    jQuery('input[name="popular_plan"]').prop('checked', false);  // trigger = input[name="popular_plan"]
    jQuery(this).prop('checked', check_box);
});

jQuery('body').on('click', '#sd_saveEmailConfiguration', function (e) {
    e.preventDefault();
    let emailHost = $('#sd_emailHost').val();
    let emailUsername = $('#sd_userName').val();
    let emailPassword = $('#sd_password').val();
    let emailEncryptionType = $('#sd_encryptionType').val();
    let emailPortNumber = $('#sd_emailPort').val();
    let nameFrom = $('#sd_emailFrom').val();
    let checkBoxConfiguration = $('#sd_enableConfig').is(':checked');
    let isFormValid = true;
    let csrfToken = $('input[name="_token"]').val();


    if (emailHost === '') {
        $('#emailHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">SMTP host address cannot be empty.</span>'
        );
        isFormValid = false;
    }
    if (emailUsername === '') {
        $('#usernameHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Username cannot be empty.</span>'
        );
        isFormValid = false;
    }

    if (emailPassword === '') {
        $('#passwordHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Password cannot be empty.</span>'
        );
        isFormValid = false;
    }


    if (emailPortNumber === '') {
        $('#portNumberHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Port number cannot be empty.</span>'
        );
        isFormValid = false;
    }
    else {
        // Use a regular expression to validate that the input contains only numbers
        let numberRegex = /^[0-9]+$/;
        if (!numberRegex.test(emailPortNumber)) {
            isFormValid = false;
            $('#portNumberHelpText').html(
                '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Port number must contain only numbers.</span>'
            );
        }
    }

    if (nameFrom === '') {
        $('#fromNameHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">From Name cannot be empty.</span>'
        );
    }



    // Form data object
    if (isFormValid) {
        let formData = {
            emailHost: emailHost,
            emailUsername: emailUsername,
            emailPassword: emailPassword,
            emailEncryptionType: emailEncryptionType,
            emailPortNumber: emailPortNumber,
            nameFrom: nameFrom,
            shop: shop,
            checkBoxConfiguration: checkBoxConfiguration,
            _token: csrfToken
        };

        // Perform AJAX request to save the form data
        $.ajax({
            url: 'email-config-FormData',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.hasOwnProperty('error')) {
                    shopify.toast.show(response.error, { isError: true });
                } else {
                    shopify.toast.show(response.message, { isError: false });
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                shopify.toast.show('An error occurred while submitting the email configuration', { isError: true });
            }
        });
    }
});

jQuery('body').on('click', '#sd_submitTestEmail', function (event) {
    event.preventDefault();
    let emailHost = $('#sd_emailHost').val();
    let emailUsername = $('#sd_userName').val();
    let emailPassword = $('#sd_password').val();
    let emailEncryptionType = $('#sd_encryptionType').val();
    let emailPortNumber = $('#sd_emailPort').val();
    let nameFrom = $('#sd_emailFrom').val();
    let emailTest = $('#sd_testEmail').val().trim();
    let isFormValid = true;

    if (emailHost === '') {
        $('#emailHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">SMTP host address cannot be empty.</span>'
        );
        isFormValid = false;
    }
    if (emailUsername === '') {
        $('#usernameHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Username cannot be empty.</span>'
        );
        isFormValid = false;
    }

    if (emailPassword === '') {
        $('#passwordHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Password cannot be empty.</span>'
        );
        isFormValid = false;
    }


    if (emailPortNumber === '') {
        $('#portNumberHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Port number cannot be empty.</span>'
        );
        isFormValid = false;
    }
    else {
        // Use a regular expression to validate that the input contains only numbers
        let numberRegex = /^[0-9]+$/;
        if (!numberRegex.test(emailPortNumber)) {
            isFormValid = false;
            $('#portNumberHelpText').html(
                '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Port number must contain only numbers.</span>'
            );
        }
    }

    if (nameFrom === '') {
        $('#fromNameHelpText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">From name cannot be empty.</span>'
        );
    }

    $('#emailErrorText').empty();
    isFormValid = true;

    if (emailTest === '') {
        $('#emailErrorText').html(
            '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Email address cannot be empty.</span>'
        );
        isFormValid = false;
    } else {
        // Use a regular expression to validate email format
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailTest)) {
            isFormValid = false;
            $('#emailErrorText').html(
                '<span class="Polaris-Text--root Polaris-Text--break Polaris-Text--subdued" style="color:red;">Invalid email address.</span>'
            );
        }
    }
    let csrfToken = $('input[name="_token"]').val();
    if (isFormValid) {
        let formData = {
            emailHost: emailHost,
            emailUsername: emailUsername,
            emailPassword: emailPassword,
            emailEncryptionType: emailEncryptionType,
            emailPortNumber: emailPortNumber,
            nameFrom: nameFrom,
            emailTest: emailTest,
            shop: shop,
            _token: csrfToken
        };

        $.ajax({
            url: 'testEmailSend',
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.hasOwnProperty('error')) {
                    shopify.toast.show(response.message, { isError: true });
                    $("#sd_testEmail").val("");

                } else {
                    shopify.toast.show(response.message, { isError: false });
                    $("#sd_testEmail").val("");
                }
            },
            error: function (xhr, status, error) {
                shopify.toast.show('An error occurred while sending the email', { isError: true });
            }
        });
    }
});

jQuery('body').on('input', '.capitalDiscountInput', function () {
    let currentInput = $(this).val();
    let sanitizedInput = currentInput.replace(/[^a-zA-Z0-9]+/g, '').toUpperCase();
    $(this).val(sanitizedInput);
});

jQuery("body").on("input", ".tierDescriptionValue", function () {
    let currentTimePeriod = jQuery(this).closest('.member_plan_option_form').find('#per_delivery_order_frequency_type').val();
    currentTimePeriod = currentTimePeriod.toLowerCase();
    let tierprice = jQuery(this).closest('.member_plan_option_form').find('#tier_option_price').val();
    let tierCharge = jQuery(this).closest('.member_plan_option_form').find('.option_charge_value').val();
    let minCycle = jQuery(this).closest('.member_plan_option_form').find('.min_cycle').val();
    let maxCycle = jQuery(this).closest('.member_plan_option_form').find('.max_cycle').val();

    let tierDescription = '';
    if (tierprice != '' && tierCharge != '' && maxCycle != '' && minCycle != '') {
        tierDescription = `This plan costs ${currency_code}${tierprice} per ${tierCharge} ${currentTimePeriod}. The plan has a minimum of ${minCycle} cycles. This means that you cannot cancel or pause your plan before completing ${minCycle} cycles. After completing ${maxCycle} maximum cycles, your plan will automatically be paused.`;
    }
    if (tierprice != '' && tierCharge != '' && minCycle != '' && maxCycle == '') {
        tierDescription = `This plan costs ${currency_code}${tierprice} per ${tierCharge} ${currentTimePeriod}. The plan has a minimum of ${minCycle} cycles. This means that you cannot cancel or pause your plan before completing ${minCycle} cycles.`;

    }
    if (tierprice != '' && tierCharge != '' && maxCycle != '' && minCycle == '') {
        tierDescription = `This plan costs ${currency_code}${tierprice} per ${tierCharge} ${currentTimePeriod}. After completing ${maxCycle} maximum cycles, your plan will automatically be paused.`;
    }

    if (tierprice != '' && tierCharge != '' && maxCycle == '' && minCycle == '') {
        tierDescription = `This plan costs ${currency_code}${tierprice} per ${tierCharge} ${currentTimePeriod}.`;
    }

    jQuery(this).closest('.member_plan_option_form').find(".tier_description").val(tierDescription);

});

jQuery('body').on('keyup', '.qtyAmtValidation', function () {
    let inputValue = $(this).val();
    if (inputValue.length > 5) {
        inputValue = inputValue.slice(0, 5);
        $(this).val(inputValue);
    }
});

jQuery('body').on('change', '.member-portal-permission', function () {
    let checkboxName = $(this).attr('data-field');
    let checkboxValue = $(this).is(':checked');
    let csrfToken = $('input[name="_token"]').val();
    $.ajax({
        type: "POST",
        url: 'update-member-portal-permisssions',
        data: {
            _token: csrfToken,
            store: shop,
            shop: shop,
            checkboxName: checkboxName,
            checkboxValue: checkboxValue ? 'enabled' : 'disabled'
        },
        success: function (response) {
            shopify.toast.show(response.message, { isError: false });
        },
        error: function (error) {
            shopify.toast.show(error, { isError: true });
        }
    });

});

jQuery('body').on('click', '.sd_save_email_template_membership', async function () {
    let formId = jQuery(this).attr("data-id")
    let btnType = $(this).attr('btn-type');
    let serailize_object = serializeObject(formId);
    serailize_object.btnType = btnType;
    // let fd = new FormData();
    // fd.append('ajaxData', JSON.stringify(serailize_object,));

    shopify.loading(true);
    let ajaxParameters = {

        method: "POST",

        dataValues: {
            serailize_object,
            action: "save-email-template"
        }
    };
    let ajaxResult = await AjaxCall(ajaxParameters);
    if (ajaxResult.status) {
        shopify.loading(false);
        shopify.toast.show(ajaxResult.message, { isError: false });
        location.reload();
    } else {
        shopify.toast.show(ajaxResult.message, { isError: true });
    }
});

//    serch member
jQuery('body').on('keyup', '#search_member', function () {
    clearTimeout(delayTimer);
    delayTimer = setTimeout(function () {
        let search_member_text = $('#search_member').val();
        if (search_member_text !== '') {
            let fullURL = `${app_redirect_url}members?search_member=${encodeURIComponent(search_member_text)}&shop=${shop}&host=${host}`;
            open(fullURL, '_self');
        } else {
            let fullURL = `${app_redirect_url}members?shop=${shop}&host=${host}`;
            open(fullURL, '_self');
        }
    }, 1000);
});

jQuery('body').on('click', '.sd_confirm_send_email', async function () {
    let send_to_email = jQuery('#send_test_email_membership').val();
    let test_email_template_name = jQuery(this).attr('template-name');
    if (send_to_email == '') {
        jQuery('#test_email_error').text('Please enter the email');
    } else {
        let email_template_array = {};
        email_template_array['send_to_email'] = send_to_email;
        email_template_array['template_name'] = test_email_template_name;
        // let ajaxUrl = 'send-template-email';
        let ajaxParameters = {

            method: "POST",

            dataValues: {
                email_template_array,
                action: "sendTemplateEmail"

            }
        };
        shopify.loading(true);
        let ajaxResult = await AjaxCall(ajaxParameters);
        shopify.loading(false);

        if (ajaxResult.status) {
            shopify.toast.show(ajaxResult.message, { isError: false });
            jQuery('#sd_test_email_modal_membership').addClass('display-hide-label');

        } else {
            shopify.toast.show(ajaxResult.message, { isError: true });
            jQuery('#sd_test_email_modal_membership').addClass('display-hide-label');
        }
    }
});

// to copy the text
jQuery("body").on('click', '.sd_copy_element', function () {
    let text = jQuery(this).attr('data-value');
    if (window.clipboardData && window.clipboardData.setData) {
        return clipboardData.setData("Text", text);
        $('.respnse').html("COPIED!");
    } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        let textarea = document.createElement("textarea");
        textarea.textContent = text;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);
        textarea.select();
        try {
            return document.execCommand("copy");
        } catch (ex) {
            return false;
        } finally {
            document.body.removeChild(textarea);
            shopify.toast.show('Copied successfully', { isError: false });
        }
    }
});

jQuery('body').on('input', '.Editor-editor', function () {
    let content_div = jQuery(this).attr('data-id'); //sd_content_heading_view
    // console.log('content_div = ', content_div);
    let content_html = jQuery(this).html();
    let replace_values = {
        sd_: "",
        _view: "",
    };
    let textarea_id = content_div.replace(/sd_|_view/gi, function (matched) {
        return replace_values[matched];
    });
    console.log(textarea_id, 'textarea_id');
    console.log(content_div, 'content_div');

    if (content_html.includes('<pre contenteditable="true">')) {
        // console.log(content_html,'content_html');
        content_html = (content_html.replace(/^<pre contenteditable="true">|<\/pre>$/g, ""));
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = "<div>" + content_html + "</div>";
        const divElement = tempDiv.firstChild;
        const h2Element = divElement.firstChild;
        if (h2Element) {
            jQuery('.' + content_div + ', #' + textarea_id).html(h2Element.textContent);
        } else {
            jQuery('.' + content_div + ', #' + textarea_id).html('');
        }
    } else {
        console.log('elsePartRunning');
        console.log('.' + content_div + ', #' + textarea_id);
        jQuery('.' + content_div + ', #' + textarea_id).html(content_html);
    }
});

jQuery('body').on('click', '.send_test_email_membership', function () {
    $('#send_test_email_membership').val('');
    jQuery('#test_email_error').text('');
    jQuery('#sd_test_email_modal_membership').removeClass('display-hide-label');
    let template_name = jQuery(this).attr('data-template')
    jQuery('.sd_confirm_send_email').attr('template-name', template_name);
});

jQuery('body').on('click', '.close_email_template_modal', function () {
    jQuery('#sd_test_email_modal_membership').addClass('display-hide-label');
});

jQuery('body').on('input', '#send_test_email_membership', function () {
    let mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    let check_email = jQuery(this).val();
    if (check_email.match(mailformat)) {
        jQuery('#test_email_error').text('');
        jQuery('.sd_confirm_button').removeClass('btn-disable-loader');
    } else {
        jQuery('#test_email_error').text('Enter valid email');
        jQuery('.sd_confirm_button').addClass('btn-disable-loader');
    }
});


jQuery('body').on('click', '.sd-update-payment', async function () {
    let payment_token = jQuery(this).attr('payment-token');
    let customer_email = jQuery(this).attr('customer-email');
    console.log('payment_token', payment_token);
    let ajaxUrl = 'update-payment-method';
    let update_payment_array = {};
    update_payment_array['payment_token'] = payment_token;
    update_payment_array['customer_email'] = customer_email;
    let fd = new FormData();
    fd.append('ajaxData', JSON.stringify(update_payment_array));
    shopify.loading(true);
    let ajaxResult = await ajaxCall(ajaxUrl, fd);
    shopify.loading(false);
    if (ajaxResult.isError == true) {
        // console.log(ajaxResult);
        show_toast(ajaxResult.message, true);
    } else {
        // console.log(ajaxResult.message, false);
        show_toast(ajaxResult.message, false);
    }
});

jQuery("body").on('change', 'input[name="popular_plan"]', async function (e) {

    let membership_group_id = jQuery(this).attr('attr-id');
    let popular_plan = '';
    if ($(this).is(':checked')) {
        popular_plan = '1';
        selected_popular_plan = jQuery(this).attr('id');
    } else {
        popular_plan = '0';
    }
    let popular_plans_array = {};
    popular_plans_array['popular_plan'] = popular_plan;
    popular_plans_array['plan_group_id'] = membership_group_id;
    popular_plans_array['member_plan_id'] = jQuery('#member_plan_id').val();
    let fd = new FormData();
    fd.append('ajaxData', JSON.stringify(popular_plans_array));
    
    let ajaxParameters = {

        method: "POST",
        dataValues: {
            data : JSON.stringify(popular_plans_array),
            action: "update-popular-plan"
        }  
    };
   
    shopify.loading(true);
    // let ajaxResult = await ajaxCall('update-popular-plan', fd);
    let ajaxResult = await AjaxCall(ajaxParameters);
    console.log(ajaxResult, 'checkbox click');
    shopify.loading(false);

    show_toast(ajaxResult.message, ajaxResult.isError);
    if (ajaxResult.isError == false) {
        shopify.loading(false);
    }
});

//click on the tabs
jQuery('body').on('click', '.sd_Tabs_membership', function () {
    let target_tab = jQuery(this).attr('target-tab');
    let data_group = jQuery(this).attr('group');
    if (target_tab == 'edit_custom_template' || target_tab == 'edit_default_template') {
        if (target_tab == 'edit_custom_template') {
            show_custom_template_preview = 'sd_custom_template_preview';
            hide_default_template_preview = 'sd_default_template_preview';
        } else if (target_tab == 'edit_default_template') {
            hide_default_template_preview = 'sd_custom_template_preview';
            show_custom_template_preview = 'sd_default_template_preview';
        }
        jQuery('.' + show_custom_template_preview).removeClass('display-hide-label');
        jQuery('.' + hide_default_template_preview).addClass('display-hide-label');
    }
    jQuery('.' + data_group + '-title').removeClass('Polaris-Tabs__Tab--selected');
    jQuery('.' + data_group).addClass('Polaris-Tabs__Panel--hidden');
    jQuery('.sd_Tabs_membership[target-tab="' + target_tab + '"]').addClass('Polaris-Tabs__Tab--selected');
    jQuery('#' + target_tab).removeClass('Polaris-Tabs__Panel--hidden');
});

jQuery('body').on('click', '.button', function (event) {
    event.preventDefault();
});
/**
 * Perks Discount
 * Slide Toggle
 */
$('body .Polaris-Collapsible').hide();
$('body .perks-beforechecked').hide();

$("body .Polaris-Checkbox__Input").click(function () {
    console.log('working click')
    jQuery(this).closest('.Polaris-FormLayout').next().slideToggle('slow');
});

jQuery("body .edit-Polaris-Collapsible").click(function () {
    console.log('working click1')

    jQuery(this).closest('.Polaris-FormLayout').next().slideToggle('slow');
});

/**
 * Restricted content
 * pop up modal
 */

jQuery('body').on('click', '.RestrictedContent_AdvanceInsButton', function () {
    myModal.dispatch(Modal.Action.OPEN);
});

jQuery('body').on('click', '.restrictedContent_added', function () {
    restrictedContentAdded_Modal.dispatch(Modal.Action.OPEN);
});

jQuery('body').on('click', '.sd_email_box', function () {
    let open_setting = jQuery(this).attr('attr-key');
    jQuery('.sd_menu_settings,.' + open_setting).removeClass('display-hide-label');
    jQuery('.builder_menu_section').addClass('display-hide-label');
});

jQuery('body').on('click', '.back_addfield_btn', function () {
    jQuery('.sd_menu_settings,.builder_port_section').addClass('display-hide-label');
    jQuery('.builder_menu_section').removeClass('display-hide-label');
});

/**
 * Remove
 *  free gift upon sign up vlaues
 */

jQuery('.edit-select_gift_uponsignup').on('change', function (e) {
    e.preventDefault()
    let selectedValue = $(this).find('option:selected').val();
    let labelClass = $(this).attr('name')
    // console.log(selectedValue);
    if (selectedValue == 'Immediately_after_signup') {
        // console.log(labelClass,'labelClass');
        $('.' + labelClass).eq(0).html('Immediately after signup');
        // $('.'+labelClass).text('Immediately after signup')
    }
})

jQuery('body').on('click', '.edit-remove_free_gift_upon_signup', function () {
    let getRemovedSelectedTier_value = jQuery(this).attr("edit-getselectedtier_value");
    jQuery('.set-edit-free_upon_sign_up_button-' + getRemovedSelectedTier_value).empty();
    jQuery('.set-edit-free_upon_sign_up_button-' + getRemovedSelectedTier_value).html(`
        <div class="Polaris-FormLayout__Item">
            <button class="Polaris-Button edit-free_gift-upon-signUp" id="edit-free_gift-upon-signUp-${getRemovedSelectedTier_value}" edit-free_gift-sigup-attr="${getRemovedSelectedTier_value}" edit-member-group-id="${getRemovedSelectedTier_value}" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Icon"><span class="Polaris-Icon"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path></svg></span></span><span class="Polaris-Button__Text">Select a product</span></span>
            </button>
        </div>`);
});

jQuery('body').on('input', '.sd_default_template_text_fields', function () {
    let content_div = jQuery(this).attr('data-id');
    let gradient_color1 = '';
    let gradient_color2 = '';
    let content_value = '';
    let data_style = '';
    // console.log(content_div,'content_div2');
    if (content_div == 'options_bg_color1_preview' || content_div == 'options_bg_color2_preview') {
        gradient_color1 = $("input[name=options_bg_color1]").val();
        gradient_color2 = $("input[name=options_bg_color2]").val();
        jQuery('.active_option_bg_color').css('background', 'linear-gradient(to right, ' + gradient_color1 +
            ', ' + gradient_color2 + ')');
    } else {
        content_value = jQuery(this).val();
        if (jQuery(this).hasClass('sd_add_style')) {
            data_style = jQuery(this).attr('data-style');
            if (data_style == 'color' || data_style == 'background' || data_style == 'float' ||
                data_style == 'border-color' || data_style == 'border-style' || data_style == 'text-align' || data_style == 'fill') {
                if (content_value == 'Center') {
                    jQuery('.' + content_div).css(data_style, '');
                    jQuery('.' + content_div).css('text-align', content_value);
                } else {
                    jQuery('.' + content_div).css(data_style, content_value);
                }
            } else {
                if (data_style == 'src' && content_value == '') {
                    jQuery('.' + content_div).hide();
                }
                if (data_style == 'src' && content_value != '') {
                    jQuery('.' + content_div).show();
                    jQuery('.' + content_div).attr(data_style, content_value);
                } else {
                    jQuery('.' + content_div).attr(data_style, content_value);
                }
            }
        } else {
            jQuery('.' + content_div).html(content_value);
        }
    }
});

jQuery('body').on('click', '.sd_instruction_btn', function () {
    jQuery('.sd_instructions_common_card').removeClass('sd_instruction_active')
    let instructionId = $(this).val();
    // console.log(instructionId)
    jQuery('#' + instructionId).addClass('sd_instruction_active')
})
