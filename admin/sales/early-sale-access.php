


<?php
    include("../header.php");

    include("../navigation.php");
?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .percentOff_input .Polaris-TextField__Suffix {
        position: absolute;
        right: 0;
    }

    .deleted-svg-item {
        cursor: pointer;
    }

    .Polaris-Button[disabled] {
        opacity: 0.5;
    }

    .sd-members-main-row.allItemsTable td {
        padding: 10px 1.6rem !important;
    }

    .sd-members-main-row.allItemsTable td:first-child {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .allItemsTable-inner-data {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .allItemsTable-inner-data-img {
        width: 60px;
        height: 60px;
        text-align: center;
        flex: 0 0 60px;
    }

    .allItemsTable-inner-data-img img {
        top: 0 !important;
    }

    .sd-calender-svg svg {
        position: absolute;
        height: 22px;
        right: 0;
        top: 50%;
        transform: translate(-10px, -50%);
    }

    .daterangepicker .calendar-time .hourselect,
    .daterangepicker .minuteselect {
        width: 70px !important;
    }

    .daterangepicker select.yearselect {
        width: 73px !important;
    }

    .upgrade-plan-msg {
        font-size: 16px;
    }

    .sd_clickme {
        color: blue;
        cursor: pointer;
    }
</style>

<div class="sd-prefix-main">
    <div class="Polaris-Layout__Section sd-dashboard-page">
        <div class="">
            <div class="Polaris-Page-Header__Row">
                <div class="Polaris-Page-Header__TitleWrapper">
                    <div>
                        <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">
                            <h1 class="Polaris-Heading Polaris-DisplayText Polaris-DisplayText--sizeLarge">Early sale access</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Polaris-Page__Content">
                <div style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb; --p-frame-offset:0px;">
                    <!-- Page Content Here -->
                    
                    <?php
                        // Example values from backend (simulate your data retrieval)

                        // $earlySaleData = [
                        //     'sale_productType' => 'C',
                        //     'early_access_product_id' => '123,456',
                        //     'early_access_collection_id' => '',
                        //     'sale_start_date' => '2025-04-10 10:00',
                        //     'sale_end_date' => '2025-04-12 18:00',
                        //     'sale_discount' => '10'
                        // ];

                    
                        // echo($store);
                        
                        // Query to get early sale data
                        $query = "SELECT * FROM `membership_early_sales` WHERE store = '$store' LIMIT 1";
                        $stmt = $db->query($query);
                        $earlySaleData = $stmt->fetch(PDO::FETCH_ASSOC);

                        $itemtype = $earlySaleData['sale_productType'] ?? 'C'; //default

                        // echo $earlySaleData['sale_start_date'];

                        // Debug output
                        // print_r($earlySaleData);
                        // die;

                        // Query to get plan data
                        // $query2 = "SELECT plan_status, current_plan, charge_id FROM install WHERE store = :store";
                        // $stmt2 = $db->prepare($query2);
                        // $stmt2->bindParam(':store', $store);
                        // $stmt2->execute();
                        // $plansData = $stmt2->fetch(PDO::FETCH_ASSOC);
                    ?>

                    
                    <form class="sd_email_setting_margin">
                        <?php

                            $itemtype = isset($earlySaleData['sale_productType']) ? $earlySaleData['sale_productType'] : 'N';

                            $selectedIds = '';
                            if ($itemtype == 'P') {
                                $selectedIds = $earlySaleData['early_access_product_id'] ?? '';
                            } elseif ($itemtype == 'C') {
                                $selectedIds = $earlySaleData['early_access_collection_id'] ?? '';
                            }
                        ?>


                        <!-- Simulated CSRF (if needed, otherwise you can skip this) -->
                        <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">

                        <input type="hidden" class="sd_selected_productsIds" item-type="<?php echo $itemtype; ?>" value="[<?php echo $selectedIds; ?>]">
                        <!-- main div -->
                        <div class="Polaris-FormLayout">
                            <div class="Polaris-FormLayout__Items">

                                <!-- Sale Start Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div>
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="earlySaleAccessStartDate" class="Polaris-Label__Text">Start date</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                <input type="text" class="Polaris-TextField__Input earlySaleAccessStartDate Polaris-TextField__Input--suffixed" 
                                                 id="earlySaleAccessStartDate" 
                                                 name="daterange" date-id="" value="<?php echo htmlspecialchars($earlySaleData['sale_start_date'] ?? '', ENT_QUOTES); ?>" />
                                                    <div class="Polaris-TextField__Backdrop sd-calender-svg">
                                                        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><path d="M113.854,22.323h-9.667V19.646a7.25,7.25,0,0,0-14.5,0v2.677H38.313V19.646a7.25,7.25,0,0,0-14.5,0v2.677H14.146a1.75,1.75,0,0,0-1.75,1.75v89.781a1.751,1.751,0,0,0,1.75,1.75h99.708a1.751,1.751,0,0,0,1.75-1.75V24.073A1.75,1.75,0,0,0,113.854,22.323ZM93.187,19.646a3.75,3.75,0,0,1,7.5,0V28.5a3.75,3.75,0,0,1-7.5,0Zm-65.874,0a3.75,3.75,0,0,1,7.5,0V28.5a3.75,3.75,0,0,1-7.5,0Zm-3.5,6.177V28.5a7.25,7.25,0,0,0,14.5,0V25.823H89.687V28.5a7.25,7.25,0,0,0,14.5,0V25.823H112.1V44.2H15.9V25.823ZM15.9,112.1V47.7H112.1v64.4Z"/><path d="M40.2,56H31.479a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75H40.2a1.749,1.749,0,0,0,1.75-1.75V57.75A1.749,1.749,0,0,0,40.2,56Zm-1.75,8.719H33.229V59.5h5.218Z"/><path d="M58.972,56H50.253a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.75,1.75,0,0,0,1.75-1.75V57.75A1.75,1.75,0,0,0,58.972,56Zm-1.75,8.719H52V59.5h5.219Z"/><path d="M77.747,56H69.028a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.749,1.749,0,0,0,1.75-1.75V57.75A1.749,1.749,0,0,0,77.747,56ZM76,64.719H70.778V59.5H76Z"/><path d="M96.521,56H87.8a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75h8.718a1.749,1.749,0,0,0,1.75-1.75V57.75A1.749,1.749,0,0,0,96.521,56Zm-1.75,8.719H89.553V59.5h5.218Z"/><path d="M40.2,74H31.479a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75H40.2a1.749,1.749,0,0,0,1.75-1.75V75.75A1.749,1.749,0,0,0,40.2,74Zm-1.75,8.719H33.229V77.5h5.218Z"/><path d="M58.972,74H50.253a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.75,1.75,0,0,0,1.75-1.75V75.75A1.75,1.75,0,0,0,58.972,74Zm-1.75,8.719H52V77.5h5.219Z"/><path d="M77.747,74H69.028a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.749,1.749,0,0,0,1.75-1.75V75.75A1.749,1.749,0,0,0,77.747,74ZM76,82.719H70.778V77.5H76Z"/><path d="M96.521,74H87.8a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75h8.718a1.749,1.749,0,0,0,1.75-1.75V75.75A1.749,1.749,0,0,0,96.521,74Zm-1.75,8.719H89.553V77.5h5.218Z"/><path d="M40.2,92H31.479a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75H40.2a1.749,1.749,0,0,0,1.75-1.75V93.75A1.749,1.749,0,0,0,40.2,92Zm-1.75,8.719H33.229V95.5h5.218Z"/><path d="M58.972,92H50.253a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.75,1.75,0,0,0,1.75-1.75V93.75A1.75,1.75,0,0,0,58.972,92Zm-1.75,8.719H52V95.5h5.219Z"/><path d="M77.747,92H69.028a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.749,1.749,0,0,0,1.75-1.75V93.75A1.749,1.749,0,0,0,77.747,92ZM76,100.719H70.778V95.5H76Z"/><path d="M96.521,92H87.8a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75h8.718a1.749,1.749,0,0,0,1.75-1.75V93.75A1.749,1.749,0,0,0,96.521,92Zm-1.75,8.719H89.553V95.5h5.218Z"/></svg>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="earlyAccessSaleStartDate-Error sd_earlySaleErrors" style="display: none;color: red;">
                                            Select start date!
                                        </p>
                                    </div>
                                </div>
                                <!-- End Sale Start Field -->

                                <!--start End Date Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisTextField9Label" for="PolarisTextField9" class="Polaris-Label__Text">End date</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input type="text" class="Polaris-TextField__Input earlySaleAccessEndDate Polaris-TextField__Input--suffixed" 
                                                    id="earlySaleAccessEndDate" 
                                                    value="<?php echo isset($earlySaleData['sale_end_date']) ? htmlspecialchars($earlySaleData['sale_end_date']) : ''; ?>"
                                                    />
                                                    <div class="Polaris-TextField__Backdrop sd-calender-svg">
                                                        <svg id="Layer_2"  data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><path d="M113.854,22.323h-9.667V19.646a7.25,7.25,0,0,0-14.5,0v2.677H38.313V19.646a7.25,7.25,0,0,0-14.5,0v2.677H14.146a1.75,1.75,0,0,0-1.75,1.75v89.781a1.751,1.751,0,0,0,1.75,1.75h99.708a1.751,1.751,0,0,0,1.75-1.75V24.073A1.75,1.75,0,0,0,113.854,22.323ZM93.187,19.646a3.75,3.75,0,0,1,7.5,0V28.5a3.75,3.75,0,0,1-7.5,0Zm-65.874,0a3.75,3.75,0,0,1,7.5,0V28.5a3.75,3.75,0,0,1-7.5,0Zm-3.5,6.177V28.5a7.25,7.25,0,0,0,14.5,0V25.823H89.687V28.5a7.25,7.25,0,0,0,14.5,0V25.823H112.1V44.2H15.9V25.823ZM15.9,112.1V47.7H112.1v64.4Z"/><path d="M40.2,56H31.479a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75H40.2a1.749,1.749,0,0,0,1.75-1.75V57.75A1.749,1.749,0,0,0,40.2,56Zm-1.75,8.719H33.229V59.5h5.218Z"/><path d="M58.972,56H50.253a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.75,1.75,0,0,0,1.75-1.75V57.75A1.75,1.75,0,0,0,58.972,56Zm-1.75,8.719H52V59.5h5.219Z"/><path d="M77.747,56H69.028a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.749,1.749,0,0,0,1.75-1.75V57.75A1.749,1.749,0,0,0,77.747,56ZM76,64.719H70.778V59.5H76Z"/><path d="M96.521,56H87.8a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75h8.718a1.749,1.749,0,0,0,1.75-1.75V57.75A1.749,1.749,0,0,0,96.521,56Zm-1.75,8.719H89.553V59.5h5.218Z"/><path d="M40.2,74H31.479a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75H40.2a1.749,1.749,0,0,0,1.75-1.75V75.75A1.749,1.749,0,0,0,40.2,74Zm-1.75,8.719H33.229V77.5h5.218Z"/><path d="M58.972,74H50.253a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.75,1.75,0,0,0,1.75-1.75V75.75A1.75,1.75,0,0,0,58.972,74Zm-1.75,8.719H52V77.5h5.219Z"/><path d="M77.747,74H69.028a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.749,1.749,0,0,0,1.75-1.75V75.75A1.749,1.749,0,0,0,77.747,74ZM76,82.719H70.778V77.5H76Z"/><path d="M96.521,74H87.8a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75h8.718a1.749,1.749,0,0,0,1.75-1.75V75.75A1.749,1.749,0,0,0,96.521,74Zm-1.75,8.719H89.553V77.5h5.218Z"/><path d="M40.2,92H31.479a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75H40.2a1.749,1.749,0,0,0,1.75-1.75V93.75A1.749,1.749,0,0,0,40.2,92Zm-1.75,8.719H33.229V95.5h5.218Z"/><path d="M58.972,92H50.253a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.75,1.75,0,0,0,1.75-1.75V93.75A1.75,1.75,0,0,0,58.972,92Zm-1.75,8.719H52V95.5h5.219Z"/><path d="M77.747,92H69.028a1.75,1.75,0,0,0-1.75,1.75v8.719a1.75,1.75,0,0,0,1.75,1.75h8.719a1.749,1.749,0,0,0,1.75-1.75V93.75A1.749,1.749,0,0,0,77.747,92ZM76,100.719H70.778V95.5H76Z"/><path d="M96.521,92H87.8a1.749,1.749,0,0,0-1.75,1.75v8.719a1.749,1.749,0,0,0,1.75,1.75h8.718a1.749,1.749,0,0,0,1.75-1.75V93.75A1.749,1.749,0,0,0,96.521,92Zm-1.75,8.719H89.553V95.5h5.218Z"/></svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="earlyAccessSaleEndDate-Error sd_earlySaleErrors" style="display: none;color: red;">
                                            Select end date !
                                        </p>
                                    </div>
                                </div>
                                <!-- End end Date Field -->

                                <!-- sale discount -->
                                <div class="Polaris-FormLayout__Item percentOff_input">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisTextField9Label" for="PolarisTextField9" class="Polaris-Label__Text">How much % off on selected items</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input 
                                                        name="perk-discounted_products-amount-earlySale" 
                                                        id="earlySalePercentage" 
                                                        class="Polaris-TextField__Input Polaris-TextField__Input--suffixed earlySalePercentage numberPercentage" 
                                                        type="number" 
                                                        aria-labelledby="PolarisTextField9Label PolarisTextField9Suffix" 
                                                        aria-invalid="false" 
                                                        value="<?php echo isset($earlySaleData['sale_discount']) ? htmlspecialchars($earlySaleData['sale_discount']) : ''; ?>"
                                                    >
                                                    <div class="Polaris-TextField__Suffix" id="PolarisTextField9Suffix">%
                                                        off
                                                    </div>
                                                    <div class="Polaris-TextField__Backdrop">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="earlySalePercentage-Error earlySalePercentageError sd_earlySaleErrors" style="display: none;color: red;">
                                            Discount % is required!
                                        </p>
                                    </div>
                                </div>
                                <!-- End sale discount -->

                            </div>

                            <div class="Polaris-FormLayout__Items">

                                <!-- Select Product and collection -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisSelect6Label" for="PolarisSelect6" class="Polaris-Label__Text">
                                                    Sale applies to
                                                </label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Select">
                                            <select id="saleSelectbox" name="perk-discounted_products-applies_to" class="Polaris-Select__Input saleSelectbox saleAppliedSelect_box" aria-invalid="false" saleAppliedSelectedTier_Value="">
                                                <option value="Whole store products" <?php echo ($itemtype == 'N') ? 'selected' : ''; ?>>Whole store products</option>
                                                <option value="Specific collections" <?php echo ($itemtype == 'C') ? 'selected' : ''; ?>>Specific collections</option>
                                                <option value="Specific products" <?php echo ($itemtype == 'P') ? 'selected' : ''; ?>>Specific product</option>
                                            </select>
                                            <div class="Polaris-Select__Content" aria-hidden="true">
                                                <span class="Polaris-Select__SelectedOption">
                                                    <?php
                                                        if ($itemtype == 'C') {
                                                            echo "Specific collections";
                                                        } elseif ($itemtype == 'P') {
                                                            echo "Specific products";
                                                        } else {
                                                            echo "Whole store products";
                                                        }
                                                    ?>
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
                                <!-- End Select Product and collection -->

                                <!-- no of days field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="PolarisTextField8Label" for="PolarisTextField8"
                                                class="Polaris-Label__Text">Early access duration</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-Connected">
                                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                            <div class="Polaris-TextField">
                                                <div class="Polaris-TextField__Backdrop"></div>
                                            </div>
                                        </div>
                                        <p class ="sd_sale-Msg" style="color: #f9fafb;">Note : <span
                                            style="color: grey; font-size: 13px;">Members with purchased
                                            memberships receive early access to sales, while others gain access
                                            on the sale start date.</span>
                                        </p>
                                        <!-- <p class="noOfDaysEarlySaleAccess-Error sd_earlySaleErrors" style="display: none;color: red;">No. of early days is required! </p> -->
                                    </div>
                                    </div>
                                </div>
                                <!-- end no of days field -->

                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                    </div>
                                </div>

                            </div>

                            <div class="Polaris-FormLayout__Item">
                                <div class="perksSaleApplies">
                               
                                    <?php if ($itemtype == 'C'): ?>
                                        <div class="Polaris-FormLayout__Item selected-saleCollectionbox">
                                            <div class="sd_selected_existing_Salecollection" id="sd_selected_existing_Salecollection_id">
                                                <div class="Polaris-FormLayout__Item">
                                                    <button class="Polaris-Button collectionSaleApplied" id="collectionSaleApplied" type="button" SelectedCollectionButton-attr="">
                                                        <span class="Polaris-Button__Content">
                                                            <span class="Polaris-Button__Icon">
                                                                <span class="Polaris-Icon">
                                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"     focusable="false" aria-hidden="true">
                                                                    <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                                                </svg>
                                                                </span>
                                                            </span>
                                                            <span class="Polaris-Button__Text">Add collections</span>
                                                        </span>
                                                    </button>

                                                    <div class="sd_selected_existing_specificProduct" id="sd_selected_existing_Sale_collection">
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
                                                                                                <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">Search Collections</label>
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

                                                            <?php
                                                                $totalItemsId = explode(",", $earlySaleData['early_access_collection_id']);
                                                                $totalItemsTitle = explode(",", $earlySaleData['early_access_collection_title']);
                                                                $totalItemsImages = explode(",", $earlySaleData['early_access_collection_images']);
                                                                $arrLength = count($totalItemsId);
                                                            ?>

                                                            <div class="sd_resourceList_main" style="max-height:450px;">
                                                                <table class="Polaris-DataTable__Table allItemsTable">
                                                                    <tbody>
                                                                        <?php for ($col = 0; $col < $arrLength; $col++): ?>
                                                                        <tr class="Polaris-DataTable__TableRow SelectedProductLists Polaris-DataTable--hoverable">
                                                                            <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                                                                                <div class="allItemsTable-inner-data">
                                                                                    <div class="allItemsTable-inner-data-img">
                                                                                        <img src="<?php echo $totalItemsImages[$col] ?? 'https://cdn.shopify.com/s/files/1/0829/6575/8262/products/AAUvwnj0ICORVuxs41ODOvnhvedArLiSV20df7r8XBjEUQ_s900-c-k-c0x00ffffff-no-rj_58e5329e-7eb7-49a4-86fc-2db2f8e43ead_40x40@3x.jpg?v=1694182351'; ?>" height="50px" width="50px" style="border-radius:1px;">
                                                                                    </div>
                                                                                    <span><?php echo $totalItemsTitle[$col] ?? ''; ?></span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                                                                                <div class="delete-selected-item get-product-info"
                                                                                    product-image="<?php echo $totalItemsImages[$col] ?? ''; ?>"
                                                                                    product-id="<?php echo $totalItemsId[$col] ?? ''; ?>"
                                                                                    product-title="<?php echo $totalItemsTitle[$col] ?? ''; ?>"
                                                                                    product-object="gid://shopify/Collection/<?php echo $totalItemsId[$col] ?? ''; ?>"
                                                                                    group-id="<?php echo $earlySaleData['membership_group_id'] ?? ''; ?>"
                                                                                    item-type="Collection">
                                                                                    <span class="deleted-svg-item">
                                                                                        <svg width="25" height="25" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <path d="...your SVG path here..." fill="black" />
                                                                                        </svg>
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php endfor; ?>
                                                                    </tbody>
                                                                </table>
                                                                <div class="Polaris-DataTable__Footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="sd_earlySaleErrors Polaris-FormLayout__Items sd_selected_sale_collection-Error" id="sd_selected_existing_collection_id" style="color:red;display:none;">
                                                        Required Field!
                                                    </div>

                                                    <div style="display:none;">
                                                        <p class="CollectionArray- getSelectedCollections" id="getSelectedCollections">
                                                            <?php echo $earlySaleData['early_access_collection_id'] ?? ''; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($itemtype == 'P'): ?>
                                        <!-- You can add similar logic for Specific Products here -->
                                        <div class="Polaris-FormLayout__Item selected-saleProductbox">
                                            <div class="Polaris-FormLayout__Items sd_selected_existing_specificSaleProduct" id="sd_selected_existing_collection_specificSaleProduct_id">
                                                <div class="Polaris-FormLayout__Item">
                                                    <button class="Polaris-Button specificProduct_SaleApplied" id="specificProduct_SaleApplied" type="button" SelectedCollectionButton-attr="">
                                                        <span class="Polaris-Button__Content">
                                                            <span class="Polaris-Button__Icon">
                                                                <span class="Polaris-Icon">
                                                                    <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                                                        <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                            <span class="Polaris-Button__Text">Add products</span>
                                                        </span>
                                                    </button>

                                                    <div class="sd_selected_existing_specificProduct" id="sd_selected_existing_collection_specificProduct_id">
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
                                                                    <?php
                                                                        $totalItemsId = explode(",", $earlySaleData['early_access_product_id']);
                                                                        $totalItemsTitle = explode(",", $earlySaleData['early_access_product_title']);
                                                                        $totalItemsImages = explode(",", $earlySaleData['early_access_product_images']);
                                                                        $arrLength = count($totalItemsTitle);
                                                                    ?>
                                                                    <tbody>
                                                                        <?php for ($col = 0; $col < $arrLength; $col++): ?>
                                                                            <tr class="Polaris-DataTable__TableRow SelectedProductLists Polaris-DataTable--hoverable">
                                                                                <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                                                                                    <img src="<?= $totalItemsImages[$col] ?? 'https://cdn.shopify.com/s/files/1/0829/6575/8262/products/default.jpg' ?>" height="50px" width="50px" style="border-radius:1px;">
                                                                                    <span><?= $totalItemsTitle[$col] ?? '' ?></span>
                                                                                </td>
                                                                                <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                                                                                    <div class="delete-selected-item get-product-info" 
                                                                                        product-image="<?= $totalItemsImages[$col] ?? '' ?>" 
                                                                                        product-id="<?= $totalItemsId[$col] ?? '' ?>" 
                                                                                        product-title="<?= $totalItemsTitle[$col] ?? '' ?>" 
                                                                                        product-object="gid://shopify/Product/<?= $totalItemsId[$col] ?? '' ?>" 
                                                                                        group-id="<?= $earlySaleData['membership_group_id'] ?? '' ?>" 
                                                                                        item-type="Product">
                                                                                        <span class="deleted-svg-item">
                                                                                            <!-- SVG icon unchanged -->
                                                                                            <svg width="25" height="25" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <path d="M13.3477 14.6397C13.4164 14.7134 13.4992 14.7725 13.5912 14.8135C13.6832 14.8545 13.7825 14.8765 13.8832 14.8783C13.9839 14.88 14.084 14.8615 14.1773 14.8238C14.2707 14.7861 14.3556 14.7299 14.4268 14.6587C14.498 14.5875 14.5541 14.5027 14.5919 14.4093C14.6296 14.3159 14.6481 14.2159 14.6463 14.1152C14.6446 14.0145 14.6225 13.9151 14.5815 13.8231C14.5405 13.7311 14.4814 13.6483 14.4077 13.5797L11.6877 10.8597L14.4077 8.13968C14.5402 7.9975 14.6124 7.80946 14.6089 7.61516C14.6055 7.42086 14.5268 7.23547 14.3894 7.09806C14.252 6.96064 14.0666 6.88193 13.8723 6.8785C13.678 6.87508 13.4899 6.9472 13.3477 7.07968L10.6277 9.79968L7.90775 7.07968C7.76557 6.9472 7.57753 6.87508 7.38322 6.8785C7.18892 6.88193 7.00354 6.96064 6.86613 7.09806C6.72871 7.23547 6.65 7.42086 6.64657 7.61516C6.64314 7.80946 6.71527 7.9975 6.84775 8.13968L9.56775 10.8597L6.84775 13.5797C6.77406 13.6483 6.71496 13.7311 6.67397 13.8231C6.63297 13.9151 6.61093 14.0145 6.60916 14.1152C6.60738 14.2159 6.6259 14.3159 6.66362 14.4093C6.70135 14.5027 6.75749 14.5875 6.82871 14.6587C6.89993 14.7299 6.98476 14.7861 7.07815 14.8238C7.17154 14.8615 7.27157 14.88 7.37227 14.8783C7.47297 14.8765 7.57229 14.8545 7.66429 14.8135C7.75628 14.7725 7.83909 14.7134 7.90775 14.6397L10.6277 11.9197L13.3477 14.6397Z" fill="black"></path>
                                                                                            </svg>
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endfor; ?>
                                                                    </tbody>
                                                                </table>
                                                                <div class="Polaris-DataTable__Footer"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="sd_earlySaleErrors Polaris-FormLayout__Items sd_selected_sale_product-Error" id="sd_selected_sale_product_id" style="color:red;display:none;">
                                                        Required Field!
                                                    </div>

                                                    <div style="display:none;">
                                                        <p class="productArray- getSelectedProducts" id="getSelectedProducts"><?= $earlySaleData['early_access_product_id'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                        <!-- save button -->
                        <?php
                        $saleStatus = isset($earlySaleData['sale_status']) ? $earlySaleData['sale_status'] : '';
                        ?>

                        <?php //if ($plansData['charge_id'] != '' && $plansData['plan_status'] == 'active' && $plansData['current_plan'] != 'free'): ?>
                            <div class="Polaris-FormLayout__ItemSave">
                                <?php if ($saleStatus == ''): ?>
                                    <button id="sd_SaveEarlyAccessData" class="Polaris-Button Polaris-Button--primary sd_SaveEarlyAccessData" btn-type="Save">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Save</span>
                                        </span>
                                    </button>
                                <?php elseif ($saleStatus != 'Paused' || $saleStatus == 'Active'): ?>
                                    <button id="sd_SaveEarlyAccessData" class="Polaris-Button Polaris-Button--primary sd_SaveEarlyAccessData" btn-type="Save">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Update</span>
                                        </span>
                                    </button>
                                <?php endif; ?>

                                <?php if ($saleStatus == 'Paused'): ?>
                                    <button id="sd_SaveEarlyAccessData" class="Polaris-Button Polaris-Button--primary sd_SaveEarlyAccessData" btn-type="Save">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Activate</span>
                                        </span>
                                    </button>
                                <?php endif; ?>

                                <?php if ($saleStatus != 'Paused' && $saleStatus != ''): ?>
                                    <button id="sd_resetCustomerPortal" class="Polaris-Button Polaris-Button--primary sd_SaveEarlyAccessData" btn-type="Paused">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Pause</span>
                                        </span>
                                    </button>
                                <?php endif; ?>

                                <?php if ($saleStatus == 'Paused' || $saleStatus == 'Active'): ?>
                                    <button id="sd_SaveEarlyAccessData" class="Polaris-Button Polaris-Button--primary sd_SaveEarlyAccessData" btn-type="Delete">
                                        <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text">Delete</span>
                                        </span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php //else: ?>
                            <!-- <div class="Polaris-FormLayout__ItemSave">
                                <a href="#" class="Polaris-Button Polaris-Button--primary sd_save_disabled_btn">
                                    <span class="Polaris-Button__Content sd_svg_span">
                                        <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z"
                                                fill="#fffff">
                                            </path>
                                        </svg>
                                        <span class="Polaris-Button__Text">Reset</span>
                                    </span>
                                </a>
                                 <a href="#" class="Polaris-Button Polaris-Button--primary sd_save_disabled_btn">
                                    <span class="Polaris-Button__Content sd_svg_span">
                                        <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z"
                                                fill="#fffff">
                                            </path>
                                        </svg>
                                        <span class="Polaris-Button__Text">Save</span>
                                    </span>
                                </a> -->

                                <!-- <button id="sd_SaveEarlyAccessData" class="Polaris-Button Polaris-Button--primary sd_SaveEarlyAccessData" btn-type="Save">
                                    <span class="Polaris-Button__Content">
                                        <span class="Polaris-Button__Text">Save</span>
                                    </span>
                                </button>

                                <p class="upgrade-plan-msg">
                                    <a class="sd_handle_navigation_redirect sd_clickme" value="/upgrade-plans">Upgrade your plan</a> to access this feature
                                </p>
                            </div>  -->
                        <?php //endif; ?>

                    </form>
                    
                    <!-- For test -->
                    <!-- <button id="UsageButton" class="Polaris-Button Polaris-Button--primary " btn-type="Save">
                        <span class="Polaris-Button__Content">
                            <span class="Polaris-Button__Text">App Usage Record</span>
                        </span>
                    </button> -->


                </div>
            </div>
        </div>
    </div>
</div>


<?php
include("../footer.php");
?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('.earlySaleAccessStartDate, .earlySaleAccessEndDate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: false,
            minDate: moment().toDate(),
            maxYear: parseInt(moment().format('YYYY'), 10),
            locale: {
            format: 'YYYY-MM-DD HH:mm'
        }
    });

    $('.earlySaleAccessStartDate, .earlySaleAccessEndDate').on('apply.daterangepicker', function(ev, picker) {
        var formattedDateTime = picker.startDate.format('YYYY-MM-DD HH:mm');
        $(this).val(formattedDateTime);
    });
    });
</script>


