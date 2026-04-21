<?php
//echo 'sxkidcik'.$store_details_data['currencyCode']; 
//  print_r($discount_plans_data);die;
// error_reporting(0);
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
          <h3 class="Polaris-Heading">Discount plans</h3>
          <div id="PolarisPortalsContainer" class="t-right top-banner-create-discountPlan">
            <input type="hidden" value="<?php echo $currency; ?>" id="currency_code">
            <?php
            // if($plansData->current_plan=='free' && $countplans < 1) { 
            ?>
            <button class="Polaris-Button Polaris-Button--primary CreateDiscountPlan sd_button" type="button"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Create Discount plan</span></span></button>
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
  <div class="cardboxes <?php if (sizeof($discount_plans_data)) {
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
                        <input id="search-subscription-text" placeholder="Search plans" class="Polaris-TextField__Input Polaris-TextField__Input--hasClearButton" aria-labelledby="PolarisTextField1Label PolarisTextField1Prefix" aria-invalid="false" value="<?php echo $search_discount_plan; ?>">
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
      if (sizeof($discount_plans_data)) {
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
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Discount name</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Product name</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Discount %</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Status</th>
                            <th data-polaris-header-cell="true" aria-sort="none" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric" scope="col">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($discount_plans_data as $member_plan_data) { ?>
                            <tr class="Polaris-DataTable__TableRow Polaris-DataTable--hoverable">
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo $member_plan_data->discount_name; ?></td>
                              
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo $member_plan_data->product_name; ?></td>

                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo $member_plan_data->discount_value; ?></td>

                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?php echo ucfirst($member_plan_data->status); ?></td>

                         
                              <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">
                                <div class="member_plan_actions"><button class="Polaris-Button edit-membership-plan" type="button" data-member-id="<?php echo $member_plan_data->id; ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" height="20px" width="20px">
                                      <path d="M14.846 1.403l3.752 3.753.625-.626A2.653 2.653 0 0015.471.778l-.625.625zm2.029 5.472l-3.752-3.753L1.218 15.028 0 19.998l4.97-1.217L16.875 6.875z" fill="#5C5F62" />
                                    </svg></button><button class="Polaris-Button  delete_member_plan" sd-ui-modal-id="delete-membership-plan" data-perk-freeshipping-ids="<?php echo $member_plan_data->id; ?>" data-perk-pricerule-ids="<?php echo $member_plan_data->id; ?>" data-member-id="<?php echo $member_plan_data->id; ?>" data-member-productType="<?php echo $member_plan_data->membership_product_type; ?>" data-member-product_id="<?php echo $member_plan_data->id; ?>" data-group-ids="<?php echo $member_plan_data->id; ?>">
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
                      //  echo $discount_plans_data->appends([
                      //     'shop' => $_GET['shop'],
                      //     'host' => $_GET['host'],
                      //     'search_discount_plan' => isset($_GET['search_discount_plan']) ? $_GET['search_discount_plan'] : ''
                      // ])->links(); 
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php }
      if (isset($_GET['search_discount_plan']) && ($_GET['search_discount_plan'] != '') && count($discount_plans_data) == 0) { ?>
          <div id="no_search_result">No search result found.</div>
        <?php }
      if (count($discount_plans_data) == 0) { ?>
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