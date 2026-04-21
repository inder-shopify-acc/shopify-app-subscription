<?php
// error_reporting(0);

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

use PHPShopify\ShopifySDK;

$dirPath = dirname(dirname(__DIR__));

include $dirPath . "/application/library/config.php";

$current_date = gmdate("Y-m-d H:i:s");

$SERVER_ADDR = $_SERVER['SERVER_ADDR'];

$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

file_put_contents($dirPath . "/application/assets/txt/webhooks/renew_cronjob_hit.txt", "\n\n SERVER_ADDR = " . $SERVER_ADDR . " and REMOTE_ADDR =" . $REMOTE_ADDR . " cron job hit time " . $current_date, FILE_APPEND | LOCK_EX);

$currentDate = strtok(gmdate("Y-m-d H:i:s"), " ");

file_put_contents($dirPath . "/application/assets/txt/webhooks/cronjob.txt", $currentDate, FILE_APPEND | LOCK_EX);

include $dirPath . "/graphLoad/autoload.php";

$after_cycle_update = '0';

// $row_data_query = $db->query("SELECT s.store,e.plan_type,e.after_cycle_update,e.after_cycle,e.recurring_discount_value,e.contract_id,e.store_id,e.next_billing_date,s.access_token from subscriptionOrderContract e, install s, billingAttempts as b where e.store_id = s.id AND e.next_billing_date = '$currentDate' AND  e.contract_status = 'A' AND contract_inprocess = 'no' AND e.next_billing_date NOT IN (SELECT b.renewal_date FROM billingAttempts b WHERE e.contract_id = b.contract_id AND b.status = 'Skip' AND b.renewal_date = '$currentDate') GROUP BY e.contract_id limit 0,10");
// $after_cycle_update = '0';

$row_data_query = $db->query("SELECT s.store,e.after_cycle_update,e.after_cycle,e.recurring_discount_value,e.contract_id,e.store_id,e.next_billing_date,s.access_token, e.selling_plan_id, e.plan_type, e.max_cycle, e.shopify_customer_id from subscriptionOrderContract e, install s, billingAttempts as b where e.store_id = s.id AND e.next_billing_date = '$currentDate' AND  e.contract_status = 'A' AND contract_inprocess = 'no' AND e.next_billing_date NOT IN (SELECT b.renewal_date FROM billingAttempts b WHERE e.contract_id = b.contract_id AND b.status = 'Skip' AND b.renewal_date = '$currentDate') GROUP BY e.contract_id limit 0,10");

$row_count = $row_data_query->rowCount();

//get contract active products
// echo ($row_count);
// die;
if ($row_count > 0) {

	$row_data = $row_data_query->fetchAll(PDO::FETCH_ASSOC);

	// echo '<pre>';

	// print_r($row_data);
	// die;

	$contracts_in_process = (implode(",", (array_column($row_data, 'contract_id'))));

	file_put_contents($dirPath . "/application/assets/txt/webhooks/RenewSubscriptionOrder.txt", "\n\n contract order data. " . json_encode($row_data) . " current date" . $current_date, FILE_APPEND | LOCK_EX);

	foreach ($row_data as $rowVal) {

		$store_id = $rowVal['store_id'];
		$store = $rowVal['store'];
		$contract_id = $rowVal['contract_id'];
        // if($store == 'thediyart1.myshopify.com'){
		// 	print_r($row_data);
		// 	// die;
		// }
		$execute_cron = 'Yes';

		$config = array(

			'ShopUrl' => $rowVal['store'],
			'AccessToken' => $rowVal['access_token']
		);

		$shopifies = new ShopifySDK($config);

		$update_contract_process_status = $db->query("UPDATE subscriptionOrderContract SET contract_inprocess = 'yes' where contract_id = $contract_id");

		$plan_type = $rowVal['plan_type'];

		// new function  

		$max_cycle = $rowVal['max_cycle'];
		$shopify_customer_id = $rowVal['shopify_customer_id'];
		$execute_cron = 'Yes';

		if ($rowVal['max_cycle'] && $plan_type == 'membership') {

			$sql = "SELECT COUNT(contract_id) AS order_count 
			FROM `billingAttempts` 
			WHERE `status` = 'Success' 
			AND `contract_id` = '$contract_id'";
			// AND customer_id = '$shopify_customer_id'";

			$get_orders_count = $db->query($sql);


			// $get_orders_count = $row_data_query->fetchAll(PDO::FETCH_ASSOC);

			if ($rowVal['max_cycle']  <= ($get_orders_count + 1)) {

				// PAUSE AND REMOVE CUSTOMER TAG MEMBERSHIP CONTRACT IF THE MAX LIMIT REACHED

				// DB::table('purchased_membership_details')
				// ->where('contract_id', '=', $membership->contract_id)
				// ->update(['plan_status' => 'P']);
				$selling_plan_id = $rowVal['selling_plan_id'];
				$sql = $db->query("SELECT membership_plan_groups.unique_handle FROM membership_plan_groups JOIN membership_groups_details ON membership_groups_details.membership_group_id = membership_plan_groups.membership_group_id WHERE membership_plan_groups.store = '$store' AND membership_groups_details.membership_option_id = '$selling_plan_id' LIMIT 1");
				// AND customer_id = '$shopify_customer_id'";

				$tag_data = $sql->fetch(PDO::FETCH_OBJ);
				// print_r($tag_data);
				$db->query("UPDATE subscriptionOrderContract SET contract_status = 'P' WHERE contract_id = '$contract_id'");


				try {

					$customerUpdateMutation = ' mutation removeTags($id: ID!, $tags: [String!]!) {
						tagsRemove(id: $id, tags: $tags) {
						node {
							id
						}
						userErrors {
							message
						}
						}
					}';
					// make dynamic value 
					$customer_tag = $tag_data->unique_handle ?? null;
					$customerUpdateVariables = [
						"id" => "gid://shopify/Customer/" . $rowVal['shopify_customer_id'],
						"tags" => "" . $customer_tag . "",
					];
					$customerUpdateExecution = $shopifies->GraphQL->post($customerUpdateMutation, null, null, $customerUpdateVariables);
					$action = 'remove';
					$userErrors = $customerUpdateExecution['data']['customerUpdate']['userErrors'];
				} catch (Exception $e) {
					echo '<pre>';
					print_r($e->getMessage());
				}
			}
		}

		try {

			file_put_contents($dirPath . "/application/assets/txt/webhooks/RenewSubscriptionOrder.txt", "\n\n contract in process. " . $contract_id . " current date" . $current_date, FILE_APPEND | LOCK_EX);
		} catch (Exception $e) {
		}



		// $update_contract_processStatus = "UPDATE subscriptionOrderContract SET contract_inprocess = 'yes' where contract_id = '$contract_id' and store_id = '$store_id'";

		// $update_contract_processStatus_Result = $db->query($update_contract_processStatus);



		if ($execute_cron == 'Yes') {

			$config = array(

				'ShopUrl' => $rowVal['store'],

				'AccessToken' => $rowVal['access_token']

			);

			$shopifies = new ShopifySDK($config);

			//get contract products

			$get_contract_products_query = $db->query("SELECT variant_id,contract_line_item_id,recurring_computed_price,subscription_price FROM subscritionOrderContractProductDetails WHERE contract_id = '$contract_id' AND product_contract_status = '1'");

			$contract_products_array = $get_contract_products_query->fetchAll(PDO::FETCH_ASSOC);
			$contract_products = implode(',', array_column($contract_products_array, 'variant_id'));  // variant_id
			$price_array = array_column($contract_products_array, 'subscription_price');
			$purchage_plan_amount = array_sum($price_array); // total amount

			$idempotencyKey = uniqid();

			try {

				$graphQL_billingAttemptCreate = 'mutation {

					subscriptionBillingAttemptCreate(

						subscriptionContractId: "gid://shopify/SubscriptionContract/' . $contract_id . '"

						subscriptionBillingAttemptInput: {idempotencyKey: "' . $idempotencyKey . '"}

					) {

						subscriptionBillingAttempt  {

							id

							subscriptionContract

							{

								nextBillingDate

								billingPolicy{

									interval

									intervalCount

									maxCycles

									minCycles

									anchors{

										day

										type

										month

									}

								}

								deliveryPolicy{

									interval

									intervalCount

									anchors{

										day

										type

										month

									}

								}

							}

						}

						userErrors {

							field

							message

						}

			 	    }

		   	    }';

				$billingAttemptCreateApi_execution = $shopifies->GraphQL->post($graphQL_billingAttemptCreate);

				echo '<pre>';
				print_r($billingAttemptCreateApi_execution);


				$billingAttemptCreateApi_error = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['userErrors'];

				// print_r($billingAttemptCreateApi_error);

				if (!count($billingAttemptCreateApi_error)) {

					$billingAttemptId = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['id'];

					$billingAttempt_id = substr($billingAttemptId, strrpos($billingAttemptId, '/') + 1);

					$insertbillingattempt = "INSERT INTO billingAttempts (store_id,plan_type,contract_id,shopify_customer_id,contract_products,billingAttemptId,billing_attempt_date,status,renewal_date,updated_at) VALUES ('$store_id','$plan_type','$contract_id','$shopify_customer_id','$contract_products','$billingAttempt_id','$currentDate','Pending','$currentDate','$current_date')";

					$queryResult = $db->query($insertbillingattempt);

					// if value is active in general setting table

					$intervalCount = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['subscriptionContract']['billingPolicy']['intervalCount'];

					$billingType = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['subscriptionContract']['billingPolicy']['interval'];

					if ($billingType == 'DAY') {

						$TotalIntervalCount =  $intervalCount;
					} else if ($billingType == 'WEEK') {

						$TotalIntervalCount = (7 *  $intervalCount);
					} else if ($billingType == 'MONTH') {

						$TotalIntervalCount = (30 *  $intervalCount);
					} else if ($billingType == 'YEAR') {

						$TotalIntervalCount = (365 *  $intervalCount);
					}

					// Usages Charge amount

					$data_storeInstallOffers_query = $db->query("SELECT appSubscriptionPlanId, planName, store_id FROM storeInstallOffers WHERE store_id = $store_id ORDER BY id DESC LIMIT 1");
					$get_data_storeInstall_offers = $data_storeInstallOffers_query->fetch(PDO::FETCH_ASSOC);
					// print_r($get_data_storeInstall_offers);
					$appSubPlanId = $get_data_storeInstall_offers['appSubscriptionPlanId'];

					// planItemId is app subscription id.
					if ($appSubPlanId) {
						// appUsageRecordCreate($appSubPlanId, $purchage_plan_amount, $shopifies, $get_data_storeInstall_offers['store_id'], $db);
					}

					$newRenewalDate = date('Y-m-d', strtotime('+' . $TotalIntervalCount . ' day', strtotime($currentDate)));

					$updatable = "UPDATE subscriptionOrderContract SET updated_at = '$current_date', next_billing_date = '$newRenewalDate' where contract_id = '$contract_id'";

					$queryResult = $db->query($updatable);
				}
			} catch (Exception $e) {

				echo '<pre>';
				print_r($e->getMessage());

				// die;
				// set contract status in process no

				file_put_contents($dirPath . "/application/assets/txt/webhooks/renew_cronjob_hit.txt", "\n\n cron job catch error contract id = " . $contract_id . " and " . $e->getMessage(), FILE_APPEND | LOCK_EX);

				$update_contract_processStatus = "UPDATE subscriptionOrderContract SET contract_inprocess = 'no' where contract_id = '$contract_id' and store_id = '$store_id'";

				$update_contract_processStatus_Result = $db->query($update_contract_processStatus);
			}
		}
	}
}


// public function appUsageRecordCreate($data)
function appUsageRecordCreate($app_sub_plan_id, $price, $shopifies, $store_id, $db)
{

	$Planprice  = $price;
	$SubplanItemId = $app_sub_plan_id;
	$plan_name  = "Your Plan anme"; // make daynamic
	$storeId  = $store_id;

	// Calculate 5% of the plan price
	$percentage = 5;
	$amount = ($Planprice * $percentage / 100) + 0.12; // add extra charge 0.15

	// Set a minimum charge of $1 if neede +++

	// $miniCharge = 1;
	// if ($amount < $miniCharge) {
	//     $amount = $miniCharge;
	// }

	$craete_action_on_billing = '
	mutation {
		appUsageRecordCreate(
			subscriptionLineItemId: "gid://shopify/AppSubscriptionLineItem/' . $SubplanItemId . '?v=1&index=0",
			description: "5% commission on per orders +  0.12 tax",
			price: { amount: ' . $amount . ', currencyCode: USD }
		) {
			appUsageRecord {
				id
				price {
					amount
					currencyCode
				}
			}
			userErrors {
				field
				message
			}
		}
	}';

	// Extracting values from response
	$response = $shopifies->GraphQL->post($craete_action_on_billing);
	$errors = $response['data']['appUsageRecordCreate']['userErrors'] ?? [];

	// echo '<pre>';
	// print_r($errors);
	// echo '</pre>';

	$usage_desc = '5% charge for new plan "' . $plan_name . '"';
	// Optional: Escape double quotes if not using prepared statements
	$usage_desc = str_replace('"', '\"', $usage_desc);

	$amount = $amount = $response['data']['appUsageRecordCreate']['appUsageRecord']['price']['amount'] ?? 0;
	$usage_amount = is_numeric($amount)
		? number_format($amount, 2, '.', '')  // ensures 2 decimal places like "24.20"
		: '0.00';


	if ($response && empty($errors)) {

		$usageRecodeId = $response['data']['appUsageRecordCreate']['appUsageRecord']['id'];
		$current_date = date('Y-m-d H:i:s');
		$stmt = $db->prepare("INSERT INTO UsageCharge (store_id, price, charge_amount, charged_at, sub_plan_item_id, usage_record_id) VALUES (?, ?, ?, ?, ?, ?)");
		$savedata = $stmt->execute([$storeId, $price, $usage_amount, $current_date, $SubplanItemId, $usageRecodeId]);

		echo 'Usage charge taken successfully.';
		return $savedata;
	} else {
		echo ('some error');
	}
}


function addRemoveCustomerTag($store, $action, $customer_id, $config, $removeTag)
{
	try {
		// $store_details_data = $this->getStoreDetailsByDomain($store);
		// $access_token = $store_details_data->access_token;
		// $config = [
		// 	'ShopUrl' => $store,
		// 	'AccessToken' => $access_token,
		// ];
		// $shopify = new ShopifySDK($config);
		$shopifies = new ShopifySDK($config);
		$customer_gid = "gid://shopify/Customer/" . $customer_id . "";


		// Construct GraphQL mutations
		$tagRemoveMutation = 'mutation {
		tagsRemove(id: "' . $customer_gid . '", tags: ["' . $removeTag . '"]) {
			node {
				id
			}
			userErrors {
				field
				message
			}
		}
	}';



		// Execute GraphQL mutations
		$tagRemoveData = $shopifies->GraphQL->post($tagRemoveMutation, null, null, null);
		// $tagAddData = $shopifies->GraphQL->post($tagAddMutation, null, null, null);
		// Return success message
		return json_encode(['status' => true, 'message' => 'Customer tag updated successfully.']);
	} catch (Exception $e) {
		// Return error message
		return json_encode(['status' => false, 'message' => $e->getMessage()]);
	}
}
$db = null;
