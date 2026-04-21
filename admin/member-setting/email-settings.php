<?php
include("../header.php");
include("../navigation.php");
// Get email notification settings
$stmt1 = $db->prepare("SELECT * FROM email_notification_settings WHERE store = :store LIMIT 1");
$stmt1->execute(['store' => $store]);
$emailSettings = $stmt1->fetch(PDO::FETCH_OBJ); // or FETCH_ASSOC if you prefer an array
// print_r($emailSettings);die;
// // Get plans data
// $stmt2 = $db->prepare("SELECT plan_status, current_plan, charge_id FROM install WHERE store = :store LIMIT 1");
// $stmt2->execute(['store' => $store]);
// $plansData = $stmt2->fetch(PDO::FETCH_OBJ);

if ($emailSettings) {
    $admin_new_purchase_plan = $emailSettings->admin_new_purchase_plan;
    $customer_new_purchase_plan = $emailSettings->customer_new_purchase_plan;
    $admin_plan_payment_pending = $emailSettings->admin_plan_payment_pending;
    $customer_plan_payment_pending = $emailSettings->customer_plan_payment_pending;
    $admin_credit_card_expiring = $emailSettings->admin_credit_card_expiring;
    $customer_credit_card_expiring = $emailSettings->customer_credit_card_expiring;
    $admin_plan_payment_failed = $emailSettings->admin_plan_payment_failed;
    $customer_plan_payment_failed = $emailSettings->customer_plan_payment_failed;
    $admin_plan_payment_declined = $emailSettings->admin_plan_payment_declined;
    $customer_plan_payment_declined = $emailSettings->customer_plan_payment_declined;
    $admin_membership_cancelled = $emailSettings->admin_membership_cancelled;
    $customer_membership_cancelled = $emailSettings->customer_membership_cancelled;
    $admin_membership_upgrade = $emailSettings->admin_membership_upgrade;
    $customer_membership_upgrade = $emailSettings->customer_membership_upgrade;
    $admin_membership_downgrade = $emailSettings->admin_membership_downgrade;
    $customer_membership_downgrade = $emailSettings->customer_membership_downgrade;
    $admin_membership_renew = $emailSettings->admin_membership_renew;
    $customer_membership_renew = $emailSettings->customer_membership_renew;
    $admin_membership_free_gift = $emailSettings->admin_membership_free_gift;
    $customer_membership_free_gift = $emailSettings->customer_membership_free_gift;
    $admin_membership_skip_membership = $emailSettings->admin_membership_skip_membership;
    $customer_membership_skip_membership = $emailSettings->customer_membership_skip_membership;
} else {
    $admin_new_purchase_plan = '1';
    $customer_new_purchase_plan = '1';
    $admin_plan_payment_pending = '1';
    $customer_plan_payment_pending = '1';
    $admin_credit_card_expiring = '1';
    $customer_credit_card_expiring = '1';
    $admin_plan_payment_failed = '1';
    $customer_plan_payment_failed = '1';
    $admin_plan_payment_declined = '1';
    $customer_plan_payment_declined = '1';
    $admin_membership_cancelled = '1';
    $customer_membership_cancelled = '1';
    $admin_membership_upgrade = '1';
    $customer_membership_upgrade = '1';
    $admin_membership_downgrade = '1';
    $customer_membership_downgrade = '1';
    $admin_membership_renew = '1';
    $customer_membership_renew = '1';
    $admin_membership_free_gift = '1';
    $customer_membership_free_gift = '1';
    $admin_membership_skip_membership = '1';
    $customer_membership_skip_membership = '1';
}

$email_notification_array = [
    'Membership purchase' => 'new_purchase_plan',
    // 'Plan payment pending' => 'plan_payment_pending',
    // 'Credit card expiring' => 'credit_card_expiring',
    // 'Membership payment failed' => 'plan_payment_failed',
    // 'Membership payment declined' => 'plan_payment_declined',
    'Membership status update' => 'membership_cancelled',
    // 'Membership upgrade' => 'membership_upgrade',
    // 'Membership downgrade' => 'membership_downgrade',
    'Membership renew' => 'membership_renew',
    'Membership free gift' => 'membership_free_gift',
    'Membership plan skip' => 'membership_skip_membership',
];
?>
<div id="sd_test_email_modal_membership" class="display-hide-label">
    <div class="Polaris-Modal-Dialog__Container" data-polaris-layer="true" data-polaris-overlay="true">
        <div class="sd_modal_container">
            <div role="dialog" aria-modal="true" aria-labelledby="Polarismodal-header26" tabindex="-1"
                class="Polaris-Modal-Dialog">
                <div class="Polaris-Modal-Dialog__Modal" id="sd_global_modal_container">
                    <div class="Polaris-Modal-Header">
                        <div id="Polarismodal-header26" class="Polaris-Modal-Header__Title">
                            <h2 class="Polaris-DisplayText Polaris-DisplayText--sizeSmall">Send Test Mail</h2>
                        </div>
                        <button class="Polaris-Modal-CloseButton close_email_template_modal" aria-label="Close">
                            <span class="Polaris-Icon Polaris-Icon--colorBase Polaris-Icon--applyColor">
                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"
                                    width="10px">
                                    <path
                                        d="m11.414 10 6.293-6.293a1 1 0 1 0-1.414-1.414L10 8.586 3.707 2.293a1 1 0 0 0-1.414 1.414L8.586 10l-6.293 6.293a1 1 0 1 0 1.414 1.414L10 11.414l6.293 6.293A.998.998 0 0 0 18 17a.999.999 0 0 0-.293-.707L11.414 10z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div class="Polaris-Modal__BodyWrapper">
                        <div class="Polaris-Modal__Body Polaris-Scrollable Polaris-Scrollable--vertical"
                            data-polaris-scrollable="true">
                            <section class="Polaris-Modal-Section">
                                <div class="Polaris-TextContainer">
                                    <p></p>
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label id="PolarisTextField1Label" for="PolarisTextField1"
                                                    class="Polaris-Label__Text">Send to email</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField Polaris-TextField--hasValue">
                                                    <input id="send_test_email_membership" autocomplete="off"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-labelledby="PolarisTextField1Label" aria-invalid="false"
                                                        value="">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="Polaris-InlineError" id="test_email_error"></span>
                                    </div>
                                    <p></p>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="Polaris-Modal-Footer">
                        <div class="Polaris-Modal-Footer__FooterContent">
                            <div class="Polaris-Stack Polaris-Stack--alignmentCenter">
                                <div class="Polaris-Stack__Item Polaris-Stack__Item--fill"></div>
                                <div class="Polaris-Stack__Item">
                                    <div class="Polaris-ButtonGroup">
                                        <div class="Polaris-ButtonGroup__Item"><button
                                                class="close_email_template_modal Polaris-Button confirm"
                                                type="button">Cancel</button></div>
                                        <div class="Polaris-ButtonGroup__Item"><button
                                                class="sd_confirm_send_email Polaris-Button Polaris-Button--primary"
                                                type="button" template-name="">Send</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="Polaris-Layout__Section sd-dashboard-page">
    <div class="sd-prefix-main sd_membership_email_templates">
        <div class="Polaris-Page-Header__Row">
            <!-- <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <nav role="navigation"> <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership"
                            onclick="handleLinkRedirect('/all-settings')"> <span
                                class="Polaris-Breadcrumbs__ContentWrapper"> <span class="Polaris-Breadcrumbs__Icon"> <span
                                        class="Polaris-Icon"> <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                            focusable="false" aria-hidden="true">
                                            <path
                                                d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">
                                            </path>
                                        </svg> </span> </span> </span> </a> </nav>
                </div> -->
            <div class="Polaris-Page-Header__TitleWrapper">
                <div>
                    <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">
                        <h1 class="Polaris-heading">Email templates</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-LegacyCard">
                    <div class="Polaris-IndexTable sd_email_setting_top_margin">
                        <div class="Polaris-IndexTable__IndexTableWrapper">
                            <div class="Polaris-IndexTable-ScrollContainer ">
                                <table
                                    class="Polaris-IndexTable__Table Polaris-IndexTable__Table--unselectable Polaris-IndexTable__Table--sticky sd_email_setting_margin">
                                    <thead>
                                        <tr>
                                            <th class="Polaris-IndexTable__TableHeading Polaris-IndexTable__TableHeading--unselectable"
                                                data-index-table-heading="true">Name</th>
                                            <!-- <th class="Polaris-IndexTable__TableHeading Polaris-IndexTable__TableHeading--second Polaris-IndexTable__TableHeading--unselectable"
                                                data-index-table-heading="true">Send to admin</th> -->
                                            <th class="Polaris-IndexTable__TableHeading Polaris-IndexTable__TableHeading--second Polaris-IndexTable__TableHeading--unselectable"
                                                data-index-table-heading="true">Send to customer</th>
                                            <th class="Polaris-IndexTable__TableHeading Polaris-IndexTable__TableHeading--unselectable"
                                                data-index-table-heading="true"></th>
                                            <th class="Polaris-IndexTable__TableHeading Polaris-IndexTable__TableHeading--last Polaris-IndexTable__TableHeading--unselectable"
                                                data-index-table-heading="true">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($email_notification_array as $key => $value): ?>
                                            <tr class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell"><?php echo htmlspecialchars($key); ?></td>

                                                <!-- Admin Notification Toggle -->
                                                <!-- <td class="Polaris-IndexTable__TableCell">
                                                    <label class="switch">
                                                        <input type="checkbox" class="email_template_notification_membership"
                                                            data-field="admin_<?php echo htmlspecialchars($value); ?>"
                                                            <?php if (!empty(${'admin_' . $value}) && ${'admin_' . $value} == '1') echo 'checked'; ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td> -->

                                                <!-- Customer Notification Toggle -->
                                                <td class="Polaris-IndexTable__TableCell">
                                                    <label class="switch">
                                                        <input type="checkbox" class="email_template_notification_membership"
                                                            data-field="customer_<?php echo htmlspecialchars($value); ?>"
                                                            <?php if (!empty(${'customer_' . $value}) && ${'customer_' . $value} == '1') echo 'checked'; ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>

                                                <td class="Polaris-IndexTable__TableCell"></td>

                                                <!-- Actions -->
                                                <td class="Polaris-IndexTable__TableCell">
                                                    <!-- Edit Template -->
                                                    <span>
                                                        <button class="Polaris-Button edit-template-membership" type="button"
                                                            data-template="<?php echo htmlspecialchars($value); ?>"
                                                            onclick="handleLinkRedirect('/member-setting/edit-template-view.php?template_name=<?php echo urlencode($value); ?>')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                <path d="M14.846 1.403l3.752 3.753.625-.626A2.653 2.653 0 0015.471.778l-.625.625zm2.029 5.472l-3.752-3.753L1.218 15.028 0 19.998l4.97-1.217L16.875 6.875z"
                                                                    fill="#5C5F62"></path>
                                                            </svg>
                                                        </button>
                                                    </span>

                                                    <!-- Tooltip -->
                                                    <span class="Polaris-PositionedOverlay display-hide-label">
                                                        <div class="Polaris-Tooltip-TooltipOverlay" data-polaris-layer="true">
                                                            <div id="PolarisTooltipContent2" role="tooltip"
                                                                class="Polaris-Tooltip-TooltipOverlay__Content">Manage Template</div>
                                                        </div>
                                                    </span>

                                                    <!-- Send Test Email -->
                                                    <span>
                                                        <button class="Polaris-Button send_test_email_membership"
                                                            data-template="<?php echo htmlspecialchars($value); ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                                                <path d="M3.415.189a1 1 0 011.1-.046l15 9a1 1 0 010 1.714l-15 9a1 1 0 01-1.491-1.074L4.754 11H10a1 1 0 100-2H4.753l-1.73-7.783A1 1 0 013.416.189z"
                                                                    fill="#5C5F62" />
                                                            </svg>
                                                        </button>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                    <div class="Polaris-IndexTable__ScrollBarContainer Polaris-IndexTable--scrollBarContainerHidden">
                        <div class="Polaris-IndexTable__ScrollBar"
                            style="--pc-index-table-scroll-bar-content-width:828px;">
                            <div class="Polaris-IndexTable__ScrollBarContent">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../footer.php"); ?>