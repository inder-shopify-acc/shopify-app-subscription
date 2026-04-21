<?php
//echo 'sxkidcik'.$store_details_data['currencyCode']; 
//  print_r($member_plans_data);die;
?>
<style>
  .sd_memberPlan_list .Polaris-DataTable__Cell--numeric:last-child {
    text-align: center;
  }

  .sd_memberPlan_list div.member_plan_actions {
    display: flex;
    padding: 3px;
    column-gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .upgrade-plan-msg {
    font-size: 13px;
  }

  .sd_clickme {
    color: blue;
    cursor: pointer;
  }

  #plan-no-list {
    display: flex;
  }

  #plan-no-list .Polaris-Stack.Polaris-Stack--spacingTight {
    display: flex;
    justify-content: center;
    gap: 10px;
    padding-bottom: 1rem;
  }

  #plan-no-list .Polaris-EmptyState__Details,
  #plan-no-list .Polaris-EmptyState__ImageContainer {
    text-align: center;
  }

  #plan-no-list .Polaris-EmptyState__Actions {
    margin-top: 20px;
  }
</style>

<div class="sd-prefix-main">
  <div class="search-form">
    <div id="subscription-list-search">
      <div class="formbox">
        <div class="sd-planList-withBtn">
          <h3 class="Polaris-Heading">My plans</h3>
          <div id="PolarisPortalsContainer" class="t-right top-banner-create-memberPlan">
            <input type="hidden" value="<?php echo $currency; ?>" id="currency_code">
            <?php
            // if($plansData->current_plan=='free' && $countplans < 1) { 
            ?>
            <button class="Polaris-Button Polaris-Button--primary CreateMemberPlan sd_button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Create plan</span></span></button>
            <?php
            // } else 
            // { 
            ?>
            <?php
            //  if($plansData->charge_id != '' && $plansData->plan_status =='active' && $plansData->current_plan != 'free') { 
            ?>
            <!-- <button class="Polaris-Button Polaris-Button--primary CreateMemberPlan sd_button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Create plan</span></span></button> -->
            <?php
            // } else { 
            ?>
            <!-- <button class="Polaris-Button Polaris-Button--primary sd_button sd_save_disabled_btn" type="button">
              <span class="Polaris-Button__Content sd_svg_span">
                <svg class="sd_lock_svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM12 13.25C12.4142 13.25 12.75 13.5858 12.75 14V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V14C11.25 13.5858 11.5858 13.25 12 13.25Z" fill="#fffff" />
                </svg>
                <span class="Polaris-Button__Text">Create plan</span>
              </span>
            </button>
            <div>
              <p class="upgrade-plan-msg"><a class="sd_handle_navigation_redirect sd_clickme" value="/upgrade-plans">Upgrade your plan</a> to unlock the ability to create multiple membership plans</p>
            </div> -->
            <?php
            // }
            ?>
            <?php
            //  } 
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="cardboxes <?php if (sizeof($member_plans_data)) {
                          echo '';
                        } else {
                          echo 'no_plan_exist';
                        } ?>">
    <div class="Polaris-ResourceList__FiltersWrapper">
      <div class="Polaris-Filters">
        <div class="Polaris-Filters-ConnectedFilterControl__ProxyButtonContainer" aria-hidden="true">

        </div>
        <div class="Polaris-Filters-ConnectedFilterControl__Wrapper">
          <div class="Polaris-Filters-ConnectedFilterControl Polaris-Filters-ConnectedFilterControl--right">
            <div class="Polaris-Filters-ConnectedFilterControl__CenterContainer">
              <div class="Polaris-Filters-ConnectedFilterControl__Item">
                <div class="Polaris-Labelled--hidden">
                  <div class="Polaris-Labelled__LabelWrapper">
                    <div class="Polaris-Label">
                      <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">Search members</label>
                    </div>
                  </div>
                  <div class="Polaris-Connected">
                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                      <div class="Polaris-TextField member-fillters">
                        <div class="Polaris-TextField__Prefix" id="PolarisTextField1Prefix">
                          <span class="Polaris-Filters__SearchIcon">
                            <span class="Polaris-Icon">
                              <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                <path d="M8 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm9.707 4.293-4.82-4.82a5.968 5.968 0 0 0 1.113-3.473 6 6 0 0 0-12 0 6 6 0 0 0 6 6 5.968 5.968 0 0 0 3.473-1.113l4.82 4.82a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414z">

                                </path>
                              </svg>
                            </span>
                          </span>
                        </div>
                        <input id="search-membership-text" placeholder="Search plans" class="Polaris-TextField__Input Polaris-TextField__Input--hasClearButton" aria-labelledby="PolarisTextField1Label PolarisTextField1Prefix" aria-invalid="false" value="<?php echo $search_member_plan; ?>">
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
          <div class="Polaris-Filters__TagsContainer" aria-live="polite">

          </div>
        </span>
      </div>
    </div>
    <div class="memberPlan-list-start sd_memberPlan_list">
      <?php
      if (sizeof($member_plans_data)) {
      ?>
        <div class="Polaris-DataTable sd_common_datatable">
          <div class="Polaris-DataTable__ScrollContainer">
            <div class="">
              <div class="Polaris-Card">
                <div class="">
                  <div class="Polaris-DataTable__Navigation">
                    <button class="Polaris-Button Polaris-Button--disabled Polaris-Button--plain Polaris-Button--iconOnly" aria-label="Scroll table left one column" aria-disabled="true" type="button" tabindex="-1">
                      <span class="Polaris-Button__Content">
                        <span class="Polaris-Button__Icon">
                          <span class="Polaris-Icon">
                            <span class="Polaris-Text--root Polaris-Text--bodySm Polaris-Text--regular Polaris-Text--visuallyHidden">
                            </span>
                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                              <path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414l-4.293 4.293 4.293 4.293a.999.999 0 0 1-.707 1.707z">
                              </path>
                            </svg>
                          </span>
                        </span>
                      </span>
                    </button>
                    <button class="Polaris-Button Polaris-Button--plain Polaris-Button--iconOnly" aria-label="Scroll table right one column" type="button">
                      <span class="Polaris-Button__Content">
                        <span class="Polaris-Button__Icon">
                          <span class="Polaris-Icon">
                            <span class="Polaris-Text--root Polaris-Text--bodySm Polaris-Text--regular Polaris-Text--visuallyHidden">
                            </span>
                            <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                              <path d="M8 16a.999.999 0 0 1-.707-1.707l4.293-4.293-4.293-4.293a.999.999 0 1 1 1.414-1.414l5 5a.999.999 0 0 1 0 1.414l-5 5a.997.997 0 0 1-.707.293z">
                              </path>
                            </svg>
                          </span>
                        </span>
                      </span>
                    </button>
                  </div>
                  <div class="Polaris-DataTable Polaris-DataTable__ShowTotals">
                    <div class="Polaris-DataTable__ScrollContainer">
                      <table class="Polaris-DataTable__Table">
                        <thead>
                          <tr>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Member plan name</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Member plan tiers</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Membership perks</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($member_plans_data as $member_plan_data) { ?>
                            <tr class="Polaris-DataTable__TableRow Polaris-DataTable--hoverable">
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo $member_plan_data->membership_plan_name; ?></td>
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">

                                <?php foreach (explode(",", $member_plan_data->member_group_name) as $key => $member_plan_name) { ?>
                                  <span class="Polaris-Badge Polaris-Badge--statusSuccess">
                                    <?php echo $member_plan_name; ?></span>
                                <?php } ?>

                              </td>
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><button class="Polaris-Button edit-membership-perk" data-member-id="<?php echo $member_plan_data->id; ?>" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                    <path d="M14.846 1.403l3.752 3.753.625-.626A2.653 2.653 0 0015.471.778l-.625.625zm2.029 5.472l-3.752-3.753L1.218 15.028 0 19.998l4.97-1.217L16.875 6.875z" fill="#5C5F62" />
                                  </svg></button></td>
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                                <div class="member_plan_actions"><button class="Polaris-Button edit-membership-plan" type="button" data-member-id="<?php echo $member_plan_data->id; ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                      <path d="M14.846 1.403l3.752 3.753.625-.626A2.653 2.653 0 0015.471.778l-.625.625zm2.029 5.472l-3.752-3.753L1.218 15.028 0 19.998l4.97-1.217L16.875 6.875z" fill="#5C5F62" />
                                    </svg></button><button class="Polaris-Button  delete_member_plan" sd-ui-modal-id="delete-membership-plan" data-perk-freeshipping-ids="<?php echo $member_plan_data->freeshipping_member_perk_ids; ?>" data-perk-pricerule-ids="<?php echo $member_plan_data->discounted_product_collection_price_rule_id; ?>" data-member-id="<?php echo $member_plan_data->id; ?>" data-member-productType="<?php echo $member_plan_data->membership_product_type; ?>" data-member-product_id="<?php echo $member_plan_data->product_id; ?>" data-group-ids="<?php echo $member_plan_data->member_group_ids; ?>">
                                    <svg width="20" viewBox="0 0 20 20" height="20px" width="20px">
                                      <path fill-rule="evenodd" d="M14 4h3a1 1 0 011 1v1H2V5a1 1 0 011-1h3V1.5A1.5 1.5 0 017.5 0h5A1.5 1.5 0 0114 1.5V4zM8 2v2h4V2H8zM3 8h14v10.5a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 18.5V8zm4 3H5v6h2v-6zm4 0H9v6h2v-6zm2 0h2v6h-2v-6z" fill="#5C5F62"></path>
                                    </svg></button></div>
                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="Polaris-DataTable__Footer">
                      <?php
                      //  echo $member_plans_data->appends([
                      //     'shop' => $_GET['shop'],
                      //     'host' => $_GET['host'],
                      //     'search_member_plan' => isset($_GET['search_member_plan']) ? $_GET['search_member_plan'] : ''
                      // ])->links(); 
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php }
      if (isset($_GET['search_member_plan']) && ($_GET['search_member_plan'] != '') && count($member_plans_data) == 0) { ?>
          <div id="no_search_result">No search result found.</div>
        <?php }
      if (count($member_plans_data) == 0) { ?>
          <div id="plan-no-list">
            <div class="Polaris-Card">
              <div class="Polaris-Card__Section">
                <div class="Polaris-EmptyState Polaris-EmptyState--withinContentContainer">
                  <div class="Polaris-EmptyState__Section">
                    <div class="Polaris-EmptyState__DetailsContainer">
                      <div class="Polaris-EmptyState__ImageContainer"><img src="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png" role="presentation" alt="" class="Polaris-EmptyState__Image"></div>
                    </div>
                    <div class="Polaris-EmptyState__Details">
                      <div class="Polaris-TextContainer">
                        <p class="Polaris-DisplayText Polaris-DisplayText--sizeSmall">Create your first plan</p>
                        <p style="margin-top: 0;">Start selling your products with amazing plans.</p>
                      </div>
                      <div class="Polaris-EmptyState__Actions">
                        <div class="Polaris-Stack Polaris-Stack--spacingTight Polaris-Stack--distributionCenter Polaris-Stack--alignmentCenter">
                          <div class="Polaris-Stack__Item"><button class="Polaris-Button Polaris-Button--primary CreateMemberPlan" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Start Now</span></span></button></div>
                          <div class="Polaris-Stack__Item sd_learnMore">
                            <a class="Polaris-Button" data-confirmbox="no" tabindex="0" href="https://shinedezign.gitbook.io/elite-memberships/" data-polaris-unstyled="true" target="_blank">
                              <span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Learn more</span></span></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div id="PolarisPortalsContainer"></div>
          </div>
        <?php } ?>

        </div>
    </div>
    <div id="PolarisPortalsContainer"></div>

  </div>