@extends('layouts.app')
@section('content')
<div class="sd-prefix-main">
    <div class="Polaris-Layout__Section sd-dashboard-page">
        <div class="contact_form">
            <div class="Polaris-Page-Header__Row">
                <!-- <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <nav role="navigation"> <a class="Polaris-Breadcrumbs__Breadcrumb backButtonLink back_button_membership"
                            onclick="handleLinkRedirect('/all-settings')"> <span class="Polaris-Breadcrumbs__ContentWrapper">
                                <span class="Polaris-Breadcrumbs__Icon"> <span class="Polaris-Icon"> <svg viewBox="0 0 20 20"
                                            class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                            <path
                                                d="M17 9H5.414l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L5.414 11H17a1 1 0 1 0 0-2z">
                                            </path>
                                        </svg> </span> </span> </span> </a> </nav>
                </div> -->
                <div class="Polaris-Page-Header__TitleWrapper">
                    <div>
                        <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">
                            <h1 class="Polaris-Heading">Member portal permissions</h1>
                        </div>
                    </div>
                </div>
            </div>
           <!-- add content here -->
           <div class="Polaris-Page__Content">
    <div class="Polaris-Layout">
      <div class="Polaris-Layout__AnnotatedSection">
        <div class="Polaris-Layout__AnnotationWrapper">
          <div class="Polaris-Layout__Annotation member_portal_left sd_email_setting_margin">
            <div class="Polaris-TextContainer">
              <h2 class="Polaris_text_sub_heading" id="storeDetails"></h2>
              <div class="Polaris-Box sd_memberPortal_text" style="--pc-box-color:var(--p-color-text-subdued)">
                <p>Members have permission to manage their membership purchases and access information provided by the merchant. </p>
              </div>
            </div>
            <div class="Polaris-Layout__AnnotationContent memberPortal_setting">
            <div class="Polaris-LegacyCard">
                        <div class="Polaris-IndexTable">
                            <div class="Polaris-IndexTable__IndexTableWrapper">
                                <div class="Polaris-IndexTable-ScrollContainer">
                                    <table class="Polaris-IndexTable__Table Polaris-IndexTable__Table--unselectable Polaris-IndexTable__Table--sticky">

                                    <tbody>
                                            <tr
                                                class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell">
                                                <span class="sd_member_permission">Member can cancel their membership.</span>
                                                    <label class="switch">
                                                        <input type="checkbox" class="member-portal-permission"
                                                            id="sd_cancel_membership_portal" data-field="cancel_membership"
                                                            {{ $memberCheckboxData && $memberCheckboxData->cancel_membership === 'enabled' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr
                                                class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell">
                                                <span class="sd_member_permission">Member can see their upcoming membership renewal.</span>
                                                    <label class="switch">
                                                        <input type="checkbox" class="member-portal-permission"
                                                            id="sd_upcoming_membership_portal"
                                                            data-field="skip_upcoming_membership_renewal"
                                                            {{ $memberCheckboxData && $memberCheckboxData->skip_upcoming_membership_renewal === 'enabled' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr
                                                class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell">
                                                    <span class="sd_member_permission"> Member can pause and resume their membership renewal.</span>
                                                    <label class="switch">
                                                        <input type="checkbox" class="member-portal-permission"
                                                            id="sd_pause_resume_membership_portal"
                                                            data-field="pause_resume_membership_renewal"
                                                            {{ $memberCheckboxData && $memberCheckboxData->pause_resume_membership_renewal === 'enabled' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                          {{--  <tr
                                                class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell">
                                                    <span class="sd_member_permission">Member can upgrade/downgrade membership.</span>
                                                    <label class="switch">
                                                        <input type="checkbox" class="member-portal-permission"
                                                            id="sd_upgrade_downgrade_membership_portal"
                                                            data-field="upgrade_downgrade_membership"
                                                            {{ $memberCheckboxData && $memberCheckboxData->upgrade_downgrade_membership === 'enabled' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr> --}}
                                            <tr
                                                class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell">
                                                <span class="sd_member_permission">Member can skip membership. </span>
                                                    <label class="switch">
                                                        <input type="checkbox" class="member-portal-permission"
                                                            id="sd_membership_skip_portal"
                                                            data-field="membership_skip"
                                                            {{ $memberCheckboxData && $memberCheckboxData->membership_skip === 'enabled' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr> 
                                            <tr
                                                class="Polaris-IndexTable__TableRow Polaris-IndexTable__TableRow--unclickable">
                                                <td class="Polaris-IndexTable__TableCell">
                                                    <span class="sd_member_permission">Enable Membership History</span>
                                                    <label class="switch">
                                                        <input type="checkbox" class="member-portal-permission"
                                                            id="sd_history_membership_portal"
                                                            data-field="membership_history"
                                                            {{ $memberCheckboxData && $memberCheckboxData->membership_history === 'enabled' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>

                                                </td>
                                            </tr> 
                                        </tbody>

                                    </table>
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
  <!-- add content ends here -->
        </div>
    </div>
</div>
    
@endsection
