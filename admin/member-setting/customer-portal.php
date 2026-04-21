@extends('layouts.app')

@section('content')
<div class="sd-prefix-main">
    <div class="Polaris-Layout__Section sd-dashboard-page">
        <div class="contact_form">
            <div class="Polaris-Page-Header__Row">
                <!-- <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <nav role="navigation">
                        <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership"
                            onclick="handleLinkRedirect('/all-appearance-settings')">
                            <span class="Polaris-Breadcrumbs__ContentWrapper">
                                <span class="Polaris-Breadcrumbs__Icon">
                                    <span class="Polaris-Icon">
                                        <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false"
                                            aria-hidden="true">
                                            <path
                                                d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">
                                            </path>
                                        </svg>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </nav>
                </div> -->
                <div class="Polaris-Page-Header__TitleWrapper">
                    <div>
                        <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">
                            <h1 class="Polaris-Heading">Member portal</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Polaris-Page__Content">
                <div
                    style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb; --p-frame-offset:0px;">
                    <form class="sd_email_setting_margin" id="sd_customer_portal_form">
                        @csrf
                        <div class="Polaris-FormLayout">
                            <div class="Polaris-FormLayout__Items">
                                <!-- Heading Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="heading" class="Polaris-Label__Text">Heading</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_heading" name="membership_heading_text" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="usernameHelpText"
                                                        aria-labelledby="usernameLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_heading_text ?? 'Membership' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Next Renewal Date Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="next_renewal_date" class="Polaris-Label__Text">Next renewal
                                                    date</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_next_renewal_date" name="membership_next_renewal_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="passwordHelpText" aria-labelledby="passwordLabel"
                                                        aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_next_renewal_text ?? 'Next Renewal Date' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Membership Period Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="membership_period" class="Polaris-Label__Text">Membership
                                                    period</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_membership_period" name="membership_period_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="usernameHelpText" aria-labelledby="usernameLabel"
                                                        aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_period_text ?? 'Membership Period' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- See More Details Field  -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="see_more_details" class="Polaris-Label__Text">See more
                                                    details</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_see_more_details" name="membership_more_details_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="seeMoreDetailsHelpText"
                                                        aria-labelledby="seeMoreDetailsLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_more_details_text ?? 'See More Details' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Active Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="active_field" class="Polaris-Label__Text">Membership active
                                                    status</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_active_field" name="membership_active_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="activeFieldHelpText"
                                                        aria-labelledby="activeFieldLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_active_text ?? 'Active' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paused Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="paused_field" class="Polaris-Label__Text">Membership paused
                                                    status</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_paused_field" name="membership_paused_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="pausedFieldHelpText"
                                                        aria-labelledby="pausedFieldLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_paused_text ?? 'Paused' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Cancelled Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="cancelled_field" class="Polaris-Label__Text">Membership
                                                    cancelled status</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_cancelled_field" name="membership_cancelled_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="cancelledFieldHelpText"
                                                        aria-labelledby="cancelledFieldLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_cancelled_text ?? 'Cancelled' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Queued Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="queued_field" class="Polaris-Label__Text">Membership queued
                                                    status</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_queued_field" name="membership_queued_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="queuedFieldHelpText"
                                                        aria-labelledby="queuedFieldLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_queued_text ?? 'Queued' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Payment Method Type Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="payment_method_type" class="Polaris-Label__Text">Payment
                                                    method type</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_payment_method_type" name="membership_payment_method_type_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="paymentMethodTypeHelpText"
                                                        aria-labelledby="paymentMethodTypeLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_payment_method_type_text ?? 'Payment Method Type' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Holder Name Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="card_holder_name" class="Polaris-Label__Text">Card holder
                                                    name</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_card_holder_name" name="membership_card_holder_name_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="cardHolderNameHelpText"
                                                        aria-labelledby="cardHolderNameLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_card_holder_name_text ?? 'Card Holder Name' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Card Expiry Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="card_expiry" class="Polaris-Label__Text">Card expiry</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_card_expiry" name="membership_card_expiry_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="cardExpiryHelpText"
                                                        aria-labelledby="cardExpiryLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_card_expiry_text ?? 'Card Expiry' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="amount" class="Polaris-Label__Text">Amount</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_amount" name="membership_amount_text" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="amountHelpText"
                                                        aria-labelledby="amountLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_amount_text ?? 'Amount' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Membership Details Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="membership_details" class="Polaris-Label__Text">Membership
                                                    details</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_membership_details" name="membership_membership_details_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="membershipDetailsHelpText"
                                                        aria-labelledby="membershipDetailsLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_membership_details_text ?? ' Membership Details' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upcoming Renewals Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="order_history" class="Polaris-Label__Text">History</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="order_history" name="order_history"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="order_historyHelpText"
                                                        aria-labelledby="order_historysLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->order_history ?? 'History' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Payment Details Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="payment_details" class="Polaris-Label__Text">Payment
                                                    details</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_payment_details" name="membership_payment_details_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="paymentDetailsHelpText"
                                                        aria-labelledby="paymentDetailsLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_payment_details_text ?? 'Payment Details' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Manage Membership Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="manage_membership" class="Polaris-Label__Text">Manage
                                                    membership</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_manage_membership" name="membership_manage_membership_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="manageMembershipHelpText"
                                                        aria-labelledby="manageMembershipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_manage_membership_text ?? 'Manage Membership' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Hide Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="hide" class="Polaris-Label__Text">Hide</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_hide" name="membership_hide_text" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="hideHelpText"
                                                        aria-labelledby="hideLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_hide_text ?? 'Hide' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pause Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="pause" class="Polaris-Label__Text">Pause</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_pause" name="membership_pause_text" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="pauseHelpText"
                                                        aria-labelledby="pauseLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_pause_text ?? 'Pause' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="Polaris-FormLayout__Items">
                                <!-- Cancel Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="cancel" class="Polaris-Label__Text">Cancel</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_cancel" name="membership_cancel_text" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="cancelHelpText"
                                                        aria-labelledby="cancelLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_cancel_text ?? 'Cancel' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Re-Activate Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="reactivate" class="Polaris-Label__Text">Re-activate</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_reactivate" name="membership_reactivate_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="reactivateHelpText"
                                                        aria-labelledby="reactivateLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_reactivate_text ?? 'Re-activate' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Cancel Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="table_serial_no" class="Polaris-Label__Text">Sr no</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="table_serial_no" name="table_serial_no" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="cancelHelpText"
                                                        aria-labelledby="cancelLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->table_serial_no ?? 'Sr no' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Re-Activate Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="table_plan_name" class="Polaris-Label__Text">Plan name</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="table_plan_name" name="table_plan_name"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="reactivateHelpText"
                                                        aria-labelledby="reactivateLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->table_plan_name ?? '' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Cancel Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="table_plan_status" class="Polaris-Label__Text">Membership status</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="table_plan_status" name="table_plan_status" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="cancelHelpText"
                                                        aria-labelledby="cancelLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->table_plan_status ?? '' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Re-Activate Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="table_plan_action" class="Polaris-Label__Text">Membership action</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="table_plan_action" name="table_plan_action"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="reactivateHelpText"
                                                        aria-labelledby="reactivateLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->table_plan_action ?? '' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Note Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_note" class="Polaris-Label__Text">Membership note</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_note" name="plan_note" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="noteLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_note ?? 'Note' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Note Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_note_message" class="Polaris-Label__Text">Membership message note</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_note_message" name="plan_note_message"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="noteMessageHelpText"
                                                        aria-labelledby="noteMessageLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_note_message ?? 'You will be able to cancel or pause your Membership after completing your minimum payment cycles.' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Skipped Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_skipped" class="Polaris-Label__Text">Membership skipped</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_skipped" name="plan_skipped" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="skipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_skipped ?? 'Skipped' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Note Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_date" class="Polaris-Label__Text">Membership skip date</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_date" name="plan_date"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="skipDateHelpText"
                                                        aria-labelledby="skipDateLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_date ?? 'Skipped Plan Date' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Skipped Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_skip_btn" class="Polaris-Label__Text">Membership skip button text</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_skip_btn" name="plan_skip_btn" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="skipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_skip_btn ?? 'Skip' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_pending_msg" class="Polaris-Label__Text">Pending plan message</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_pending_msg" name="plan_pending_msg"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="skipDateHelpText"
                                                        aria-labelledby="skipDateLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_pending_msg ?? 'Not any pending orders' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- failded Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_failed_msg" class="Polaris-Label__Text">Failed plan message</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_failed_msg" name="plan_failed_msg" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="skipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_failed_msg ?? 'Not any failed orders' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- skipped Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="plan_skipped_msg" class="Polaris-Label__Text">Skipped plan message</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="plan_skipped_msg" name="plan_skipped_msg"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="skipDateHelpText"
                                                        aria-labelledby="skipDateLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->plan_skipped_msg ?? 'Not any skipped orders' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Skipped Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="past_order_txt" class="Polaris-Label__Text">Past orders</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_past_order_txt" name="past_order_txt" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="skipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->past_order_txt ?? 'Past orders' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="upcoming_renewals" class="Polaris-Label__Text">Upcoming
                                                    renewals</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="sd_upcoming_renewals" name="membership_upcoming_renewals_text"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="upcomingRenewalsHelpText"
                                                        aria-labelledby="upcomingRenewalsLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->membership_upcoming_renewals_text ?? 'Upcoming Renewals' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Skipped Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="pending_order_txt" class="Polaris-Label__Text">Pending</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="pending_order_txt" name="pending_order_txt" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="skipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->pending_order_txt ?? 'Pendings' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="failed_order_txt" class="Polaris-Label__Text">Failed</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="failed_order_txt" name="failed_order_txt"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="upcomingRenewalsHelpText"
                                                        aria-labelledby="upcomingRenewalsLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->failed_order_txt ?? 'Faileds' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Items">
                                <!-- Skipped Field -->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="order_no_txt" class="Polaris-Label__Text">Order no</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="order_no_txt" name="order_no_txt" class="Polaris-TextField__Input"
                                                        type="text" aria-describedby="noteHelpText"
                                                        aria-labelledby="skipLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->order_no_txt ?? 'Order no' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Message-->
                                <div class="Polaris-FormLayout__Item">
                                    <div class="">
                                        <div class="Polaris-Labelled__LabelWrapper">
                                            <div class="Polaris-Label">
                                                <label for="update_btn_txt" class="Polaris-Label__Text">Update button text</label>
                                            </div>
                                        </div>
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField">
                                                    <input id="update_btn_txt" name="update_btn_txt"
                                                        class="Polaris-TextField__Input" type="text"
                                                        aria-describedby="upcomingRenewalsHelpText"
                                                        aria-labelledby="upcomingRenewalsLabel" aria-invalid="false"
                                                        value="{{ $customerPortalData->update_btn_txt ?? 'Update' }}">
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Save Button -->
                        <div class="Polaris-FormLayout__ItemSave">
                            <button id="sd_resetCustomerPortal" class="Polaris-Button Polaris-Button--primary sd_CustomerPortalValues">
                                <span class="Polaris-Button__Content" btn-type ="Reset">
                                    <span class="Polaris-Button__Text">Reset</span>
                                </span>
                            </button>
                            <button id="sd_saveCustomerPortal" class="Polaris-Button Polaris-Button--primary sd_CustomerPortalValues"
                                type="submit" btn-type ="Save">
                                <span class="Polaris-Button__Content">
                                    <span class="Polaris-Button__Text">Save</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
