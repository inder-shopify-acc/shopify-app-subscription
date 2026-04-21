	<?php
     function create_app_subscription($store, $SHOPIFY_DOMAIN_URL)
	{

			if ($store == 'crate61.myshopify.com' && $data['plan_name']) {

				$memberPlanPrice = 75;
			} else {

				$memberPlanPrice = $member_plan_data['price'];
			}

			if ($store == 'mini-cart-development.myshopify.com' || $store == 'shine-predictive-search.myshopify.com' || $store == 'mytab-shinedezign.myshopify.com' || $store == 'predictive-search.myshopify.com' || $store == 'simar-test.myshopify.com' || $store == 'inder-store-credit.myshopify.com' || $store == 'shineinfotest.myshopify.com' || $store == 'test-store-phoenixt.myshopify.com' || $store == 'pheonix-store-local.myshopify.com') {

				$test_charge = true;
			} else {

				$test_charge = false;
			}

			try {

				$return_url = $SHOPIFY_DOMAIN_URL . '/admin/dashboard.php?shop=' . $store;


				$create_app_subscription = 'mutation appSubscriptionCreate($lineItems: [AppSubscriptionLineItemInput!]!, $name: String!, $returnUrl: URL!, $test: Boolean, $trialDays: Int) {

						appSubscriptionCreate(lineItems: $lineItems, name: $name, returnUrl: $returnUrl, test: $test, trialDays: $trialDays) {

						appSubscription {

							id

							name

							test

							trialDays

							returnUrl

							lineItems{

								id

								plan{

									pricingDetails{

										__typename

									}

								}

							}

						}

						confirmationUrl

						userErrors {

							field

							message

						}

						}

					}';

				$create_app_subscription_payload = [

						"lineItems"=> [
 
                            [
 
                                "plan"=> [
 
                                    "appRecurringPricingDetails"=> [
 
                                        "price"=> [
 
                                        "amount"=> $memberPlanPrice,
 
                                        "currencyCode"=> "USD"
 
                                        ],
 
                                    "interval"=> "EVERY_30_DAYS"
 
                                    ]
 
                                ],
                                "plan" => [
                                    "appUsagePricingDetails"=> [
                                        "terms"=> "5% + $0.12 Subscription & membership charge",
                                        "cappedAmount"=> [
                                          "amount"=> 100000.00,
                                          "currencyCode"=> "USD"
                                        ]
                                    ]
                                ]
 
                            ]
 
                        ],

					"name" => $data['plan_name'] . " SUBSCRIPTION APP PRICING",

					"replacementBehavior" => "APPLY_IMMEDIATELY",

					"returnUrl" => $return_url,

					"test" => $test_charge,

				];

				// echo $create_app_subscription;

				// echo'<br>===================================================<br>';

				$createAppSubscription = $this->graphqlQuery($create_app_subscription, null, null, $create_app_subscription_payload);

				// echo '<pre>';

				// print_r($createAppSubscription);

				// die;

				$createAppSubscription_error = $createAppSubscription['data']['appSubscriptionCreate']['userErrors'];

				if (!count($createAppSubscription_error)) {

					// $appUsageLineItemId = $createAppSubscription['data']['appSubscriptionCreate']['appSubscription']['lineItems']['id'];

					$appUsagePlanId = $createAppSubscription['data']['appSubscriptionCreate']['appSubscription']['id'];

					$app_recurring_and_usage_plan_id = substr($appUsagePlanId, strrpos($appUsagePlanId, '/') + 1);

					//save appUsageRecurringId and app usagePlanItemId

					$fields = array(

						"appSubscriptionPlanId" => $app_recurring_and_usage_plan_id,

						'status' => '0',

						'planName' => $data['plan_name'],

						'price' => $member_plan_data['price'],

						'plan_id' => $data['plan_id'],

						'store_id' => $this->store_id,

						'subscription_emails' => $member_plan_data['subscription_emails'],

						'trial' => $member_plan_data['trial'],

					);

					// $whereCondition = array(

					// 	'store_id' => $this->store_id

					// );

					$this->insert_row('storeInstallOffers', $fields);

					return json_encode(array("status" => true, 'recurring_id' => $appUsagePlanId, 'confirmationUrl' => $createAppSubscription['data']['appSubscriptionCreate']['confirmationUrl'])); // return json

				} else {

					return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

				}
			} catch (Exception $e) {

				// echo '<pre>';

				// print_r($e->getMessage());

				// die;

				return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

		}
	}
    