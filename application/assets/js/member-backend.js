

    /*
     * include the
     * App Bridge Url
    
    */
    //  console.log(shopify);  

    var shop = shopify.config.shop;
    var host = shopify.config.host;
    var apiKey = shopify.config.apiKey;
    var app_redirect_url = 'memberships/';


    function callShopifyModal(modalType) 
    {
        let  modalTitle = modelMessage = primaryBtnText = closeBtnText = '';
        switch (modalType) {
            case 'delete-membership-plan':
                modalTitle = 'Warning';
                modelMessage = `Are you sure you want to delete this membership? This will delete all data associated with this membership, including the member product and cancel all recurring payments. 
                This action cannot be undone.`;
                closeBtnText = 'Close';
                primaryBtnText  = 'Delete';
                primaryBtnId  = 'sd-delete-membership-plan';
                primaryBtnClass  = 'sd-delete-membership-plan';
            break;
            case 'upgrade-member-plan':
                // Assuming the button has a class named "btn-class"
                let buttonText = $('.sd-plan-upgrade-btn').text();
                if(buttonText =='Upgrade'){
                    buttonText = 'Upgrade';
                }else{
                    buttonText = 'Downgrade';
                }
                modalTitle = 'Warning';
                modelMessage = `Are you sure you want to ${buttonText} this membership? This will change your plan and your next billing on the next charge date. This action cannot be undone.`;
                closeBtnText = 'Close';
                primaryBtnText  = buttonText;
                primaryBtnId  = 'sd-upgrade-member-plan';
                primaryBtnClass  = 'sd-upgrade-member-plan';
            break;
            case 'activate-member-plan':
                modalTitle = 'Warning';
                modelMessage = `Are you sure to reactivate the member plan?`;
                closeBtnText = 'Close';
                primaryBtnText  = 'Reactivate';
                primaryBtnId  = 'sd-reactivate-member-plan';
                primaryBtnClass  = 'sd-cancel-member-plan';
            break;
            case 'pause-member-plan':
                modalTitle = 'Warning';
                modelMessage = `Are you sure to pause the member plan?`;
                closeBtnText = 'Close';
                primaryBtnText  = 'Pause';
                primaryBtnId  = 'sd-pause-member-plan';
                primaryBtnClass  = 'sd-cancel-member-plan';
            break;
            case 'cancel-member-plan':
                modalTitle = 'Warning';
                modelMessage = `Are you sure to cancel the member plan?`;
                closeBtnText = 'Close';
                primaryBtnText  = 'Cancel';
                primaryBtnId  = 'sd-cancel-member-plan';
                primaryBtnClass  = 'sd-cancel-member-plan';
            break;
            case 'skip-member-plan':
                modalTitle = 'Warning';
                modelMessage = `Are you sure to skip the member plan?`;
                closeBtnText = 'Close';
                primaryBtnText  = 'Skip';
                primaryBtnId  = 'sd-skip-member-plan';
                primaryBtnClass  = 'sd-skip-member-plan';
            break;
            case 'delete-variant-card':
                modalTitle = 'Warning';
                modelMessage = `Are you sure to delete this tier?`;
                closeBtnText = 'Close';
                primaryBtnText  = 'Delete';
                primaryBtnId  = 'sd-delete-plan-tier';
                primaryBtnClass  = 'sd-delete-plan-tier';
            break;
        }

        let modalAttributes = {
            primaryBtnClass: primaryBtnClass, 
            modalTitle: modalTitle, 
            primaryBtnId: primaryBtnId, 
            modelMessage: modelMessage, 
            closeBtnText: closeBtnText,
            primaryBtnText : primaryBtnText,
        };
        createShopifyModal(modalAttributes);
        document.getElementById('sd-ui-modal').show();
    }

    
    function show_toast(message, isError) {
        shopify.toast.show(message, {isError : isError });
    }

    function createShopifyModal(modalAttributes) {
        let primaryBtnClass = modalAttributes.primaryBtnClass;
        let primaryBtnId = modalAttributes.primaryBtnId;
        let modalTitle = modalAttributes.modalTitle;
        let primaryBtnText = modalAttributes.primaryBtnText;
        let closeBtnText = modalAttributes.closeBtnText;
        let modelMessage = modalAttributes.modelMessage;

        let modal = jQuery('<ui-modal id="sd-ui-modal"></ui-modal>');


        let paragraph = jQuery('<p>').text(modelMessage);
        // paragraph.style.padding = '10px';
        modal.append(paragraph);

        let titleBar = jQuery('<ui-title-bar>').attr('title', modalTitle);
        modal.append(titleBar);

        let primaryButton = jQuery('<button>').attr('variant', 'primary').attr('id', primaryBtnId).addClass(primaryBtnClass).text(primaryBtnText);
        titleBar.append(primaryButton);

        let closeButton = $('<button>').attr('onclick', 'closeShopifyModal()').text(closeBtnText);

        titleBar.append(closeButton);
        jQuery('body').append(modal);
    }

    function serializeObject(formID) {
        var formData = $('#' + formID).serializeArray();
        var formDataObject = {};
        $.each(formData, function(index, field) {
            formDataObject[field.name] = field.value;
        });
        formDataObject['templateName'] = formID;
        formDataObject['store'] = shop;
        return formDataObject;
    }

    function closeShopifyModal() {
        document.getElementById('sd-ui-modal').hide();
    };

    function handleLinkRedirect(remoteURL, event) 
    {

        let fullURL;
        console.log(remoteURL, 'swdjchbjn')
        console.log(SHOPIFY_DOMAIN_URL, 'swdjchbjn')

        if (remoteURL.includes('?')) {
            fullURL = `${SHOPIFY_DOMAIN_URL}/admin${remoteURL}&shop=${shop}`;
        } else {
            fullURL = `${SHOPIFY_DOMAIN_URL}/admin${remoteURL}?shop=${shop}`;

        }
        console.log(fullURL, 'kihhhhhihddddddddddddd')
        open(fullURL, '_self');
        return;
    }

    function backRedirect(remoteURL)
    {
        const folderName = '<?php echo config("custom.FOLDER_NAME"); ?>';
        const fullURL = `/${folderName}${remoteURL}&shop=${shop}`;
        // history.dispatch(Redirect.Action.PUSH, fullURL);
        history.pushState(null, '', fullURL);
    }

    jQuery('body').on('click', '.sd_clickme', function() {
        const folderName = '<?php echo config("custom.FOLDER_NAME"); ?>';
        let remoteURL = '/upgrade-plans';
        let fullURL;
        if (remoteURL.includes('?')) {
            fullURL = `/${folderName}${remoteURL}&shop=${shop}&host=${host}`;
        } else {
            fullURL = `/${folderName}${remoteURL}?shop=${shop}&host=${host}`;
        }
        open(fullURL, '_self');
    });



    function toggleMenus($menuName) {
        if (jQuery('.'+$menuName+'.sub-menu ul').is(":visible")) {
            jQuery('.'+$menuName+'.sub-menu ul').slideUp(700);
            jQuery('.'+$menuName+' .sd_drop_view_icon').hide();
            jQuery('.'+$menuName+' .sd_drop_hide_icon').show();
        } else {
            jQuery('.'+$menuName+'.sub-menu ul').slideDown(700);
            jQuery('.'+$menuName+' .sd_drop_view_icon').show();
            jQuery('.'+$menuName+' .sd_drop_hide_icon').hide();
        }
    }

    function checkEmailValid(email) {
        let regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        }
        else {
            return true;
        }
    }

/**
 * ajax error message
 *
 */
function ajax_error_msg(message) {
    var toastErrHtml = message + `
        <button type="button" class="Polaris-Frame-Toast__CloseButton" onclick="closeToast();">
            <span class="Polaris-Icon">
                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                    <path d="M11.414 10l6.293-6.293a1 1 0 1 0-1.414-1.414L10 8.586 3.707 2.293a1 1 0 0 0-1.414 1.414L8.586 10l-6.293 6.293a1 1 0 1 0 1.414 1.414L10 11.414l6.293 6.293A.998.998 0 0 0 18 17a.999.999 0 0 0-.293-.707L11.414 10z">
                    </path>
                </svg>
            </span>
        </button>`;
    jQuery('#Toast-Frame-Advanced-Preorder').html(toastErrHtml);
    ToastFadeInOut('Error');
}




function ajax_successmsg(status, message) {
    var toastHtml = message + `
        <button type="button" class="Polaris-Frame-Toast__CloseButton" onclick="closeToast();">
            <span class="Polaris-Icon">
                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                    <path d="M11.414 10l6.293-6.293a1 1 0 1 0-1.414-1.414L10 8.586 3.707 2.293a1 1 0 0 0-1.414 1.414L8.586 10l-6.293 6.293a1 1 0 1 0 1.414 1.414L10 11.414l6.293 6.293A.998.998 0 0 0 18 17a.999.999 0 0 0-.293-.707L11.414 10z">
                    </path>
                </svg>
            </span>
        </button>`;
    jQuery('#Toast-Frame-Advanced-Preorder').html(toastHtml);
    if (status == 'false') {
        ToastFadeInOut('Error');
    } else {
        ToastFadeInOut('Success');
    }
}

  
function ToastFadeInOut(toast) {
    var x = document.getElementById("Toast-Frame-Advanced-Preorder");
    x.className = "Polaris-Frame-Toast " + toast + "-Toast-Preorder";
    setTimeout(function() {
        x.className = x.className.replace("Polaris-Frame-Toast " + toast + "-Toast-Preorder", "");
        jQuery('#Toast-Frame-Advanced-Preorder').html('');
    }, 3000);
}


    // test email code
   
    // add editor and the value to the fields
    if ((jQuery('#page_type').val()) == 'email_setting_page') {
        // console.log('new');
        var add_editor_to_fields = [];
        var jsonData = jQuery('#template_data_array').val();
        var dataArray = JSON.parse(jsonData);
        add_editor_to_fields['content_heading'] = dataArray['content_heading'];
        add_editor_to_fields['footer_content'] = dataArray['footer_content'];
        add_editor_to_fields['content_heading_text'] = dataArray['content_heading_text'];
        add_editor_to_fields['discount_coupon_content'] = dataArray['discount_coupon_content'];
        add_editor_to_fields['free_shipping_coupon_content'] = dataArray['free_shipping_coupon_content'];
        add_editor_to_fields['early_sale_content'] = dataArray['early_sale_content'];
        add_editor_to_fields['free_signup_product_content'] = dataArray['free_signup_product_content'];
        add_editor_to_fields['footer_text'] = dataArray['footer_text'];
        add_editor_to_fields['free_gift_signup_product'] = dataArray['free_gift_signup_product'];
        add_editor_to_fields['custom_email_html'] = dataArray['custom_email_html'];

        for (var key in add_editor_to_fields) {
            jQuery("#" + key).Editor({
                'insert_img': false,
                'block_quote': false,
                'fonts': false,
                'undo': false,
                'redo': false,
                'strikeout': false,
                'hr_line': false,
                'print': false,
                'togglescreen': false,
                'splchars': false,
                'unlink': false,
                'styles': false,
                'insert_table': false,
                'indent': false,
                'outdent': false,
                'rm_format': false,
                'select_all': false,
                'ol': false,
                'ul': false
            });
            $('.subscription-edit-tabs .sd_' + key + ' .Editor-editor').attr('data-id', 'sd_' + key + '_view');

            if (add_editor_to_fields[key] != 'empty') {
                $('.subscription-edit-tabs .sd_' + key + ' .Editor-editor').html(add_editor_to_fields[key]);
            }
        }
    } else {
        // console.log('Perksy Memberships');
    }




// ............................................dashboard highchart code start
var membershipStoreCurrency = $('#membershipStoreCurrency').val();
if(membershipStoreCurrency){
        (function($) {
            $(document).ready(function() {
                var start = moment().subtract(6, 'days');
                var end = moment();
                var chart;
                async function cb(start, end) {
                    var dateRange = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
                    $('#reportrange span').html(dateRange);
                   
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action:"view-sale-data",
                            store: shop,
                            dateRange: dateRange,
                        },
                        success: function(response) {
                            const data = JSON.parse(response)
                            var dataYear = data.dataYear;
                            var productDetailsTable = data.productDetailsTable;
                            console.log(productDetailsTable, 'vvvvvvvvvvvvvv')
                            var recurringTable = data.recurringTable;
                            var salesData = {}
                            var dateArray = [];
                            if(Array.isArray(productDetailsTable)) {

                                productDetailsTable?.forEach(function(product) {
                                    var date = product.created_at.split(' ')[0];
                                    if (!salesData[date]) {
                                        salesData[date] = 0;
                                    }
                                    salesData[date] += parseFloat(product.subscription_price);
                                    dateArray.push(date);
                                });
                            }
                            if(Array.isArray(recurringTable)) {

                                recurringTable?.forEach(function(order) {
                                    var date = order.created_at.split(' ')[0];
                                    if (!salesData[date]) {
                                        salesData[date] = 0;
                                    }
                                    salesData[date] += parseFloat(order.order_total);
                                   if(dateArray.includes(date)){
    
                                   }else{
                                    dateArray.push(date);
                                   }
                                });
                            }

                            dateArray.sort((a, b) => new Date(a) - new Date(b));
                            var salesSeriesData = [];
                            if(dateArray){
                                dateArray.forEach(date => {
                                    var totalSale = salesData[date] || 0;
                                    salesSeriesData.push([new Date(date).getTime(), totalSale]);
                                });
                            }

                            if (chart) {

                                // console.log('salesSeriesData',salesSeriesData);
                                // console.log('dataYear',dataYear);
                                chart.update({
                                    title: {
                                        text: 'Total membership sale for ' + dataYear,
                                        align: 'left'
                                    },
                                    series: [{
                                        type: 'line',
                                        name: dataYear,
                                        data: salesSeriesData,
                                    }]
                                });
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.error("Error sending date range to the controller:", textStatus,
                                errorThrown);
                        }
                    });
                }

                // Initialize the date range picker
                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1, 'month').endOf('month')
                        ],
                        'This Year': [moment().startOf('year'), moment().endOf('year')],
                        'Last Year': [moment().subtract(1, 'year').startOf('year'), moment()
                            .subtract(1, 'year').endOf('year')
                        ],
                    }
                }, cb);

                cb(start, end);

                // Assuming you have your data for the line chart

                Highcharts.setOptions({
                    accessibility: {
                        enabled: false
                    }
                });

                var chart = Highcharts.chart('HighContainer', {
                    title: {
                        text: 'Total membership sale',
                        align: 'left'
                    },
                    xAxis: {
                        type: 'datetime',
                        labels: {
                            formatter: function() {
                                return Highcharts.dateFormat('%b %d, %Y', this.value);
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Sales in '+ membershipStoreCurrency,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    },
                    tooltip: {
                        valueDecimals: 3,
                            useHTML: true,
                            style: {
                                fontWeight: 'bold',
                                width: '450px', 
                                padding: '10px'     
                            }
                        
                    },
                    plotOptions: {
                        series: {
                            borderRadius: '25%',
                        }
                    },
                    series: [{
                        // Your series data goes here
                    }]
                });

            });
        })(jQuery);

        // Function to get formatted Dates
        function getFormattedDatesInRange(startDate, endDate) {
            const dates = [];
            let currentDate = new Date(startDate);

            while (currentDate <= endDate) {
                const formattedDate = currentDate.getDate() + " " + currentDate.toLocaleString('default', {
                    month: 'short'
                }) + ", " + currentDate.getFullYear();
                dates.push(formattedDate);
                currentDate.setDate(currentDate.getDate() + 1);
            }

            const totalDays = Math.round((endDate - startDate) / (24 * 60 * 60 * 1000)) + 1;

            return {
                dates,
                totalDays
            };
        }
        function getFormattedMonthsInYear(year) {
            const months = [];
            for (let month = 0; month < 12; month++) {
                const formattedMonth = new Date(year, month, 1).toLocaleString('default', {
                    month: 'short'
                }) + " " + year;
                months.push(formattedMonth);
            }
            return months;
        }
    }
//............................................................... dashboard highchart code start



$('body .sd_handle_navigation_redirect').on('click', function(e) {
    e.preventDefault();
    let fullUrl = window.location.href
    let afterSlash = fullUrl.split('/').pop();
    let finalValue = afterSlash.split('?')[0];
    let remoteURL = $(this).attr('value');
    if('/'+finalValue != remoteURL) {
        const folderName = 'stagesub';
        const fullURL = `/${remoteURL}?shop=${shop}&host=${host}`;
        // console.log('fullURL --> ',fullURL)
        open(fullURL, '_self');
    }
    
});

