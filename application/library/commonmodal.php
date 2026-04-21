<?php

/* This file contains all basic db functions of insert , update , delete , duplicate & store install data*/

$dirPath = dirname(dirname(__DIR__));
require_once($dirPath . "/env.php");
require($dirPath . "/PHPMailer/src/PHPMailer.php");

require($dirPath . "/PHPMailer/src/SMTP.php");

require($dirPath . "/PHPMailer/src/Exception.php");

require_once($dirPath . "/application/library/keys.php");



use PHPShopify\ShopifySDK;

class CommonModal
{



	public $MYSQL_HOST,$MYSQL_DB,$MYSQL_USER,$MYSQL_PASS,$API_SECRET_KEY,$SHOPIFY_API_VERSION,$app_extension_id,$theme_block_name,$app_name,$created_at,$image_folder,$db,$store, $access_token, $app_status,$SHOPIFY_DOMAIN_URL, $store_id, $shopify_graphql_object, $currency, $currency_code, $shop_timezone, $shop_plan, $subscription_plans_view, $SHOPIFY_APIKEY;

	public function __construct($store)
	{
    // $SHOPIFY_FUNCTION_ID = 'bc1cf909-5937-4c47-b136-1dfe1dbb385e';
		$SHOPIFY_FUNCTION_ID = '019cf6ac-7557-7dd1-a307-b23f0b67b5b5';
		$this->MYSQL_HOST = getDotEnv('MYSQL_HOST');

		$this->MYSQL_DB = getDotEnv('MYSQL_DB');

		$this->MYSQL_USER = getDotEnv('MYSQL_USER');

		$this->MYSQL_PASS = getDotEnv('MYSQL_PASS');

		$this->SHOPIFY_APIKEY = getDotEnv('SHOPIFY_APIKEY');

		$this->API_SECRET_KEY =   getDotEnv('SHOPIFY_SECRET');

		$this->SHOPIFY_API_VERSION = "2025-04";

		$this->app_extension_id = getDotEnv('APP_EXTENSION_ID');

		$this->theme_block_name = getDotEnv('THEME_BLOCK_NAME');

		$this->app_name = getDotEnv('APP_NAME');

		$this->created_at = date('Y-m-d H:i:s');

		$this->image_folder = getDotEnv('SHOPIFY_DOMAIN_URL') . "/application/assets/images/";

		$this->SHOPIFY_DOMAIN_URL = getDotEnv('SHOPIFY_DOMAIN_URL');

		$this->db = $this->connection();

		$this->storeInstallDetails($store, 'set');

		$this->init_GraphQL_Object();
	}



	public function storeInstallDetails($store, $type)
	{

		$whereCondition = array(

			"store" => $store

		);

		$fields = "all";



		$store_install_data = $this->customQuery("Select access_token,store,store_id,shop_timezone,currency,currencyCode,shop_plan,subscription_plans_view FROM install LEFT JOIN store_details ON  install.id = store_details.store_id WHERE install.store = '$store'");

		if (count($store_install_data) != 0) {

			if ($type == 'set') {

				$this->access_token = $store_install_data[0]['access_token'];

				$this->store  = $store_install_data[0]['store'];

				$this->store_id = $store_install_data[0]['store_id'];

				$this->currency = $store_install_data[0]['currency'];

				$this->currency_code = $store_install_data[0]['currencyCode'];

				$this->shop_timezone = $store_install_data[0]['shop_timezone'];

				$this->shop_plan = $store_install_data[0]['shop_plan'];

				$this->subscription_plans_view = $store_install_data[0]['subscription_plans_view'];
			} else if ($type == 'return') {

				return json_encode($store_install_data);
			}
		}
	}



	public function init_GraphQL_Object()
	{

		$config = array(

			'ShopUrl' => $this->store,

			'AccessToken' => $this->access_token,

		);
		$this->shopify_graphql_object = new ShopifySDK($config);
	}



	/* To Check If Rows Exist Start */

	public function table_row_check($tableName, $whereCondition, $whereMode)
	{

		$where = "";

		if (is_array($whereCondition)) {

			$keys = array_keys($whereCondition);

			$size = sizeof($whereCondition);

			for ($x = 0; $x < $size; $x++) {

				if ($x > 0) {

					$where .= $whereMode;
				}

				$where .= " LOWER(" . $keys[$x] . ") = LOWER('" . $whereCondition[$keys[$x]] . "')";
			}
		} else if (is_string($whereCondition)) {

			$where = $whereCondition;
		}



		$query = $this->db->prepare("SELECT * FROM $tableName WHERE $where");

		$query->execute();

		$row_count = $query->rowCount();

		return $row_count; // return integer

	}

	/* To Check If Rows Exist End */



	public function single_row_value($tableName, $fields, $whereCondition, $whereMode, $limit)
	{

		$where = "";

		$keys = array_keys($whereCondition);

		$size = sizeof($whereCondition);

		for ($x = 0; $x < $size; $x++) {

			if ($x > 0) {

				$where .= $whereMode;
			}

			$where .= " " . $keys[$x] . " = '" . $whereCondition[$keys[$x]] . "' ";
		}

		if (is_array($fields)) {

			$field = implode(",", $fields);
		}

		if (is_string($fields)) {

			$field = "*";
		}

		$check_entry  = $this->table_row_check($tableName, $whereCondition, $whereMode);

		if ($check_entry) {

			$query = "SELECT $field FROM $tableName WHERE $where ORDER BY id DESC $limit";

			$result = $this->db->query($query);

			$row_data = $result->fetch(PDO::FETCH_ASSOC);

			return $row_data;	// return associative array

		} else {

			return $check_entry;
		}
	}



	/* To Get Fields Values From Table Start */

	public function table_row_value($tableName, $fields, $whereCondition, $whereMode, $limit)
	{

		$where = "";

		$keys = array_keys($whereCondition);

		$size = sizeof($whereCondition);

		for ($x = 0; $x < $size; $x++) {

			if ($x > 0) {

				$where .= $whereMode;
			}

			$where .= " " . $keys[$x] . " = '" . $whereCondition[$keys[$x]] . "' ";
		}

		if (is_array($fields)) {

			$field = implode(",", $fields);
		}

		if (is_string($fields)) {

			$field = "*";
		}

		$check_entry  = $this->table_row_check($tableName, $whereCondition, $whereMode);

		if ($check_entry) {

			$query = "SELECT $field FROM $tableName WHERE $where ORDER BY id DESC $limit";

			$result = $this->db->query($query);

			$row_data = $result->fetchAll(PDO::FETCH_ASSOC);

			return $row_data;	// return associative array

		} else {

			return $check_entry;
		}
	}

	/* To Get Fields Values From Table End */



	public function customQuery($query)
	{

		$result = $this->db->query($query);

		$row_data = $result->fetchAll(PDO::FETCH_ASSOC);

		return $row_data;	// return associative array

	}



	public function connection()
	{

		try {

			$result = new PDO("mysql:host=$this->MYSQL_HOST;dbname=$this->MYSQL_DB", $this->MYSQL_USER, $this->MYSQL_PASS);
		} catch (PDOException $pe) {

			die("Could not connect to the database $MYSQL_DB :" . $pe->getMessage());
		}

		return $result;
	}



	/*public function insert_row($tableName, $fields)
	{

		$where = "";
		$status = '';
		$message = '';
		$last_inserted_id = '';

		$values = array();

		foreach ($fields as $k => $val) {

			array_push($values, $val);
		}

		$columns = implode(", ", array_keys($fields));

		// $values  = "'" . implode("','", $values) . "'";

		$values  = '"' . implode('","', $values) . '"';

		$sql_query_insert = "INSERT INTO $tableName ($columns) VALUES ($values)";

		$ins = $this->db->exec($sql_query_insert);

		if ($ins) {

			$status = true;

			$last_inserted_id = $this->db->lastInsertId();

			$message = "Saved Successfully";
		} else {

			$status = false;

			$last_inserted_id = '';

			$message = "Error: " . $this->db->rollback();
		}

		return json_encode(array("status" => $status, 'message' => $message, 'id' => $last_inserted_id)); // return json

	}
	*/
	public function insert_row($tableName, $fields)
	{
		$status = false;
		$message = '';
		$last_inserted_id = '';

		try {
			// Start a transaction if not already active
			if (!$this->db->inTransaction()) {
				$this->db->beginTransaction();
			}

			$columns = implode(", ", array_keys($fields));

			// Escape values to handle double quotes in HTML
			$values = implode(',', array_map(function ($value) {
				return '"' . addslashes($value) . '"'; // Escape special characters
			}, array_values($fields)));

			$sql_query_insert = "INSERT INTO $tableName ($columns) VALUES ($values)";

			$ins = $this->db->exec($sql_query_insert);

			if ($ins) {
				$status = true;
				$last_inserted_id = $this->db->lastInsertId();
				$message = "Saved Successfully";

				$this->db->commit();
			} else {
				throw new Exception("Insert failed.");
			}
		} catch (Exception $e) {
			// Rollback only if an active transaction exists
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			$message = "Error: " . $e->getMessage();
		}

		return json_encode(array("status" => $status, 'message' => $message, 'id' => $last_inserted_id));
	}



	// for single and multiple rows : insert and update

	public function multiple_insert_update_row($tableName, $fields, $fields_values)
	{

		$where = "";
		$status = '';
		$message = '';

		$values = "";

		$result = array();

		foreach ($fields_values as $row) {

			$resultRow = array();

			for ($i = 0; $i < count($fields); $i++) {

				$resultRow[$fields[$i]] = $row[$i];
			}

			$result[] = $resultRow;
		}

		$update_string_value = '';

		$modifiedColumns = array();

		foreach ($fields as $key => $value) {

			if ($key != 0) {

				$update_string_value .=  ', ' . $value . ' = VALUES(' . $value . ')';
			} else {

				$update_string_value .=  $value . ' = VALUES(' . $value . ')';
			}

			$modifiedColumns[] = ":" . $value;
		}

		$columns = implode(", ", $fields);



		// Implode the modified column names back into a string

		$modifiedColumnsString = implode(", ", $modifiedColumns);

		// code for inderting all special character in the database

		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $this->db->prepare("INSERT INTO $tableName ($columns) VALUES ($modifiedColumnsString) ON DUPLICATE KEY UPDATE $update_string_value");

		foreach ($result as $record) {

			if ($stmt->execute($record)) {

				$upd = true;
			} else {

				$upd = false;
			}
		}

		if ($upd) {

			$status = true;

			$message = "Updated Successfully";

			$last_inserted_id = $this->db->lastInsertId();
		} else {

			$status = false;

			$message = "Error: " . $this->db->rollback();
		}

		// return json_encode(array("status"=>$status,'message'=>$message, 'id' => $last_inserted_id)); // return json

	}





	public function multiple_insert_row($tableName, $fields, $fields_values)
	{

		$where = "";
		$status = '';
		$message = '';
		$last_inserted_id = '';

		$values = "";

		foreach ($fields_values as $k => $val) {

			if ($k != 0) {

				$values .= ",";
			}

			$values .= "(";

			$values .= "'" . implode("','", $val) . "'";

			$values .= ")";
		}

		$columns = implode(", ", $fields);



		$sql_query_insert = "INSERT INTO $tableName ($columns) VALUES $values";

		$ins = $this->db->exec($sql_query_insert);

		if ($ins) {

			$status = true;

			$last_inserted_id = $this->db->lastInsertId();

			$message = "Saved Successfully";
		} else {

			$status = false;

			$last_inserted_id = '';

			$message = "Error: " . $this->db->rollback();
		}

		return json_encode(array("status" => $status, 'message' => $message, 'id' => $last_inserted_id)); // return json

	}



	public function insertupdateajax($tableName, $fields, $whereCondition, $whereMode)
	{

		$check_entry = $this->table_row_check($tableName, $whereCondition, $whereMode);
		try {
			//code...
			if ($check_entry) {

				return $this->update_row($tableName, $fields, $whereCondition, $whereMode);
			} else {

				return $this->insert_row($tableName, $fields);
			}
		} catch (Exception $th) {
			echo $th->getMessage();
		}
	}

	public function update_row_perks($tableName, $fields, $whereCondition, $whereMode)
	{

		$where = "";
		$status = '';
		$message = '';

		if (is_array($whereCondition)) {

			$keys = array_keys($whereCondition);

			$size = sizeof($whereCondition);

			for ($x = 0; $x < $size; $x++) {

				if ($x > 0) {

					$where .= $whereMode;
				}

				$where .= " " . $keys[$x] . " = '" . $whereCondition[$keys[$x]] . "' ";
			}
		}



		$valueSets = array();



		foreach ($fields as $key => $value) {

			$valueSets[] = $key . "= '" . $value . "'";
		}

		$sql_update = "UPDATE $tableName SET " . join(",", $valueSets) . " WHERE $where";
		// echo($sql_update);
		$upd =  $this->db->query($sql_update);

		if ($upd) {

			$status = true;

			$message = "Updated Successfully";
		} else {

			$status = false;

			$message = "Error: " . $this->db->rollback();
		}

		// return json_encode(array("status" => $status, 'message' => $message)); // return json

	}

	public function update_row($tableName, $fields, $whereCondition, $whereMode)
	{

		$where = "";
		$status = '';
		$message = '';

		if (is_array($whereCondition)) {

			$keys = array_keys($whereCondition);

			$size = sizeof($whereCondition);

			for ($x = 0; $x < $size; $x++) {

				if ($x > 0) {

					$where .= $whereMode;
				}

				$where .= " " . $keys[$x] . " = '" . $whereCondition[$keys[$x]] . "' ";
			}
		}



		$valueSets = array();



		foreach ($fields as $key => $value) {

			$valueSets[] = $key . "= '" . $value . "'";
		}

		$sql_update = "UPDATE $tableName SET " . join(",", $valueSets) . " WHERE $where";

		$upd =  $this->db->query($sql_update);

		if ($upd) {

			$status = true;

			$message = "Updated Successfully";
		} else {

			$status = false;

			$message = "Error: " . $this->db->rollback();
		}

		return json_encode(array("status" => $status, 'message' => $message)); // return json

	}



	/* To delete row start*/

	public function delete_row($tableName, $whereCondition, $whereMode)
	{

		$where = "";

		$keys = array_keys($whereCondition);

		$size = sizeof($whereCondition);

		if ($whereMode == 'IN') {

			$where = $keys[0] . " IN ( " . $whereCondition[$keys[0]] . " ) ";
		} else {

			for ($x = 0; $x < $size; $x++) {

				if ($x > 0) {

					$where .= $whereMode;
				}

				$where .= " " . $keys[$x] . " = '" . $whereCondition[$keys[$x]] . "' ";
			}
		}

		try {

			$query = "Delete FROM $tableName WHERE $where";

			$result =  $this->db->exec($query);

			if ($result) {

				$status = true;

				$message = "Delete Successfully";
			} else {

				$status = false;

				$message = "Error: " . $this->db->rollback();
			}

			return json_encode(array("status" => $status, 'message' => $message)); // return json

		} catch (Exception $e) {

			return json_encode(array("status" => true, 'message' => 'No data found')); // return json

		}
	}

	/* To delete row end */



	public function duplicate_row($tableName, $fields, $whereCondition, $whereMode, $copytocolumn)
	{

		$columns = implode(",", $fields);

		$where = "";

		$keys = array_keys($whereCondition);

		$size = sizeof($whereCondition);

		for ($x = 0; $x < $size; $x++) {

			if ($x > 0) {

				$where .= $whereMode;
			}

			$where .= " " . $keys[$x] . " = '" . $whereCondition[$keys[$x]] . "' ";
		}

		$query  = "INSERT INTO $tableName ( $columns ) SELECT $columns from  $tableName where $where";

		$result =  $this->db->exec($query);

		$id     =  $this->db->lastInsertId();

		$copyquery = "UPDATE $tableName  SET plan_name=CONCAT('Copy Of ',plan_name) WHERE id= $id";

		$copycolumn_result =  $this->db->exec($copyquery);

		if ($copycolumn_result) {

			$status = true;

			$message = "Duplicate Successfully";
		} else {

			$status = false;

			$message = "Error: " . $this->db->rollback();
		}

		return json_encode(array("status" => $status, 'message' => $message)); // return json



	}



	public function graphqlQuery($query, $parm1, $parm2, $parm3)
	{

		$resultArray = $this->shopify_graphql_object->GraphQL->post($query, $parm1, $parm2, $parm3);

		return  $resultArray;
	}



	// remove subscription product variants

	public function remove_subscription_productVariants($removeproducts, $subscription_plan_id)
	{

		try {

			$graphQL_sellingPlanGroupRemoveProducts = 'mutation {

				sellingPlanGroupRemoveProductVariants(

				id: "' . $subscription_plan_id . '"

				productVariantIds: ' . $removeproducts . '

				){

					userErrors {

					field

					message

					}

				}

			}';

			$sellingPlanGroupRemoveProductsapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupRemoveProducts);

			$sellingPlanGroupRemoveProductsapi_error = $sellingPlanGroupRemoveProductsapi_execution['data']['sellingPlanGroupRemoveProductVariants']['userErrors'];

			if (!count($sellingPlanGroupRemoveProductsapi_error)) {

				return json_encode(array("status" => true, 'error' => '')); // return json

			} else {

				return json_encode(array("status" => false, 'error' => $sellingPlanGroupRemoveProductsapi_error));
			}
		} catch (Exception $e) {

			return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json

		}
	}





	public function getRecurringBillingStatus($billingId)
	{

		$getBillingStatus = 'query {

			node(id: "gid://shopify/AppSubscription/' . $billingId . '") {

			  ...on AppSubscription {

				status

				name

				trialDays

				lineItems {

				  plan {

					pricingDetails {

					  ...on AppRecurringPricing {

						interval

					  }

					}

				  }

				}

			  }

			}

		}';

		return	$graphQL_getBillingStatus = $this->graphqlQuery($getBillingStatus, null, null, null);
	}

	public function getContractDraftId($contractId)
	{

		try {

			$getContractDraft = 'mutation {

			  subscriptionContractUpdate(

				contractId: "gid://shopify/SubscriptionContract/' . $contractId . '"

			  ) {

				draft {

				  id

				}

				userErrors {

				  field

				  message

				}

			  }

			}';

			$contractDraftArray = $this->graphqlQuery($getContractDraft, null, null, null);

			$draftContract_execution_error = $contractDraftArray['data']['subscriptionContractUpdate']['userErrors'];

			if (!count($draftContract_execution_error)) {

				return $contractDraftArray['data']['subscriptionContractUpdate']['draft']['id'];
			}
		} catch (Exception $e) {

			return 'error';
		}
	}



	public function checkShopifyPaymentRequirement()
	{

		$check_payment_requirement = '{

			shop {

			  features {

				eligibleForSubscriptions

			  }

			}

		}';

		$graphql_check_payment_response = $this->graphqlQuery($check_payment_requirement, null, null, null);

		return  $graphql_check_payment_response['data']['shop']['features']['eligibleForSubscriptions'];
	}



	public function billingAttemptApi($contractId)
	{

		$idempotencyKey = uniqid();

		$currentDate = new DateTime();

		$formattedDate = $currentDate->format('Y-m-d\TH:i:s\Z');

		try {

			$graphQL_billingAttemptCreate = 'mutation {

					subscriptionBillingAttemptCreate(

						subscriptionContractId: "gid://shopify/SubscriptionContract/' . $contractId . '"

						subscriptionBillingAttemptInput: {idempotencyKey: "' . $idempotencyKey . '", originTime: "' . $formattedDate . '" }

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

			$billingAttemptCreateApi_execution = $this->graphqlQuery($graphQL_billingAttemptCreate, null, null, null);

			// if($this->store == 'predictive-search.myshopify.com'){

			//    echo '<pre>';

			//    print_r($billingAttemptCreateApi_execution);

			//    die;

			// }

			$billingAttemptCreateApi_error = $billingAttemptCreateApi_execution['data']['subscriptionBillingAttemptCreate']['userErrors'];

			if (!count($billingAttemptCreateApi_error)) {

				return  $billingAttemptCreateApi_execution;
			} else {

				return 'error';
			}
		} catch (Exception $e) {

			return 'error';
		}
	}

	public function getActiveSubscriptions($customer_id)
	{

		// echo $customer_id;

		return $this->customQuery("SELECT COUNT(*) as total FROM subscriptionOrderContract WHERE store_id = '$this->store_id' AND shopify_customer_id = '$customer_id' AND contract_status = 'A'");
	}

	public function customerTagUpdate($plan_type, $status, $customer_id, $contract_id)
	{

		// echo $customer_id;die;


		if ($plan_type == 'membership') {
			$sql = "
				SELECT unique_handle
				FROM membership_plan_groups
				JOIN membership_groups_details 
					ON membership_plan_groups.membership_group_id = membership_groups_details.membership_group_id
				JOIN subscriptionOrderContract  
					ON subscriptionOrderContract.selling_plan_id = membership_groups_details.membership_option_id
					
				WHERE subscriptionOrderContract.store_id = :store_id 
					AND subscriptionOrderContract.contract_id = :contract_id
				LIMIT 1
			";


			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':store_id' => $this->store_id,
				':contract_id' => $contract_id
			]);

			$get_member_plan_handle = $stmt->fetch(PDO::FETCH_OBJ); // fetch as object (like Laravel's ->first())
			$customerTag = $get_member_plan_handle->unique_handle;
			// print_r($get_member_plan_handle);
			// die;
			if ($status == 'CANCELLED') {


				$updateCustomerTag = 'mutation {
					tagsRemove(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["' . $customerTag . '"]) {
					  node {
						id
					  }
					}
				  }';
				$craftUpdateCustomerTag = 'mutation {
					tagsAdd(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["non-member"]) {
					  node {
						id
					  }
					}
				  }';
			} elseif ($status == 'PAUSED') {


				$updateCustomerTag = 'mutation {
					tagsRemove(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["' . $customerTag . '"]) {
					  node {
						id
					  }
					}
				  }';
				$craftUpdateCustomerTag = 'mutation {
					tagsAdd(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["non-member"]) {
					  node {
						id
					  }
					}
				  }';
			} else {
				$updateCustomerTag = 'mutation {
					tagsAdd(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["' . $customerTag . '"]) {
					  node {
						id
					  }
					}
				  }';
				$craftUpdateCustomerTag = 'mutation {
					tagsRemove(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["non-member"]) {
					  node {
						id
					  }
					}
				  }';
			}
		} else {
			$activeSubscription = $this->getActiveSubscriptions($customer_id);

			$totalActiveSubscriptions = $activeSubscription[0]['total'];
			if ($totalActiveSubscriptions == 0) {

				$updateCustomerTag = 'mutation {
	
					tagsRemove(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["sd_subscription_customer"]) {
	
						node {
	
							id
	
							}
	
						}
	
					}';
				$craftUpdateCustomerTag = 'mutation {
						tagsAdd(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["non-member"]) {
						  node {
							id
						  }
						}
					  }';
			} else {

				$updateCustomerTag = 'mutation {
	
					tagsAdd(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["sd_subscription_customer"]) {
	
						node {
	
							id
	
						}
	
					}
	
				}';
				$craftUpdateCustomerTag = 'mutation {
					tagsRemove(id: "gid://shopify/Customer/' . $customer_id . '", tags: ["non-member"]) {
					  node {
						id
					  }
					}
				  }';
			}
		}


		$updateContractStatus_execution = $this->graphqlQuery($updateCustomerTag, null, null, null);
		if ($this->store == 'thediyart1.myshopify.com' || $this->store == 'test-store-phoenixt.myshopify.com') {

			$craftUpdateContractStatus_execution = $this->graphqlQuery($craftUpdateCustomerTag, null, null, null);
		}

		// print_r($updateContractStatus_execution);

	}

	// public function updateSubscriptionStatusCommit($contractId, $status, $adminEmail, $customerEmail, $ajaxCallFrom, $next_billing_date, $delivery_billing_type, $billing_policy_value, $contract_details, $contract_product_details)
	// {

	// 	//check if setting changes or not

	// 	if ($status == 'CANCELLED') {

	// 		$column_name = 'cancel_subscription';
	// 	} else {

	// 		$column_name = 'pause_resume_subscription';
	// 	}

	// 	$check_contract_update_setting = $this->getSettingData('customer_settings', $column_name);

	// 	if ($check_contract_update_setting[$column_name] == '1' || $ajaxCallFrom == 'backendAjaxCall') {

	// 		$subscription_draft_contract_id = $this->getContractDraftId($contractId);

	// 		$changeStatus = $this->updateSubscriptionStatus($subscription_draft_contract_id, $status);

	// 		if ($changeStatus == 'Success') {

	// 			$commitChanges =	$this->commitContractDraft($subscription_draft_contract_id);

	// 			if ($commitChanges == 'Success') {

	// 				$whereCondition = array(

	// 					'contract_id' => $contractId

	// 				);

	// 				if ($status == 'ACTIVE') {

	// 					$changedContractStatus = 'Resumed';

	// 					$currentDate = date('Y-m-d');

	// 					if ($currentDate > $next_billing_date) {

	// 						$newRenewalDate = $next_billing_date;

	// 						if ($delivery_billing_type == 'DAY') {

	// 							$TotalIntervalCount =  $billing_policy_value;
	// 						} else if ($delivery_billing_type == 'WEEK') {

	// 							$TotalIntervalCount = (7 *  $billing_policy_value);
	// 						} else if ($delivery_billing_type == 'MONTH') {

	// 							$TotalIntervalCount = (30 *  $billing_policy_value);
	// 						} else if ($delivery_billing_type == 'YEAR') {

	// 							$TotalIntervalCount = (365 *  $billing_policy_value);
	// 						}

	// 						$TotalIntervalCount = $this->getBillingRenewalDays(strtoupper($delivery_billing_type), $billing_policy_value);

	// 						while ($newRenewalDate <= $currentDate) {

	// 							$newRenewalDate = date('Y-m-d', strtotime('+' . $TotalIntervalCount . ' day', strtotime($next_billing_date)));

	// 							$next_billing_date = $newRenewalDate;
	// 						}
	// 					} else {

	// 						$newRenewalDate = $next_billing_date;
	// 					}

	// 					try {

	// 						$fields = array(

	// 							'contract_status' => $status[0],

	// 							'next_billing_date'	=> $newRenewalDate,

	// 							'updated_at' => gmdate('Y-m-d H:i:s')

	// 						);

	// 						$this->update_row('subscriptionOrderContract', $fields, $whereCondition, 'and');
	// 					} catch (Exception $e) {
	// 					}
	// 				} else if ($status == 'CANCELLED') {

	// 					$changedContractStatus = 'Cancelled';
	// 				} else {

	// 					$changedContractStatus = 'Paused';
	// 				}

	// 				if ($status == 'CANCELLED' || $status == 'PAUSED') {

	// 					try {

	// 						$fields = array(

	// 							'contract_status' => $status[0],

	// 							'updated_at' => gmdate('Y-m-d H:i:s')

	// 						);

	// 						$this->update_row('subscriptionOrderContract', $fields, $whereCondition, 'and');
	// 					} catch (Exception $e) {
	// 					}
	// 				}

	// 				$get_fields = 'customer_subscription_status_' . strtolower($changedContractStatus) . ',admin_subscription_status_' . strtolower($changedContractStatus);

	// 				$contract_deleted_mail = $this->getSettingData('email_notification_setting', $get_fields);

	// 				$email_send_to = '';

	// 				if ($contract_deleted_mail['customer_subscription_status_' . strtolower($changedContractStatus)] == '1' && $contract_deleted_mail['admin_subscription_status_' . strtolower($changedContractStatus)] == '1') {

	// 					$email_send_to = array($customerEmail, $adminEmail);
	// 				} else if ($contract_deleted_mail['customer_subscription_status_' . strtolower($changedContractStatus)] != '1' && $contract_deleted_mail['admin_subscription_status_' . strtolower($changedContractStatus)] == '1') {

	// 					$email_send_to = $adminEmail;
	// 				} else if ($contract_deleted_mail['customer_subscription_status_' . strtolower($changedContractStatus)] == '1' && $contract_deleted_mail['admin_subscription_status_' . strtolower($changedContractStatus)] != '1') {

	// 					$email_send_to = $customerEmail;
	// 				}



	// 				if ($email_send_to != '') {
	// 					if ($contract_details[0]['plan_type'] == 'membership') {
	// 						$customer_name = $contract_details[0]['name'];
	// 						$plan_name = $contract_product_details[0]['product_name'] . '-' . $contract_product_details[0]['variant_name'];
	// 						$template_data = [];
	// 						$template_data['template_name'] = 'membership_cancelleds';
	// 						$template_data['email_type'] = '';
	// 						$template_data['shop_name'] = $this->store;
	// 						$template_data['discount_coupon_content'] = true;
	// 						$template_data['free_shipping_coupon_content'] = true;
	// 						$template_data['free_signup_product_content'] = true;
	// 						$template_data['free_gift_uponsignupSelectedDays'] = true;
	// 						$email_template = $this->membershipAllEmailTemplates($template_data);
	// 						$body = $email_template['email_template_html'];
	// 						// print_r($email_template);
	// 						// die;
	// 						$subject = str_replace(array("{{plan_status}}"), array(strtolower($status)), $email_template['subject']);
	// 						$bodyData  = str_replace(array("{{customer_name}}", "{{plan_name}}", "{{plan_status}}", "{{store_name}}"), array($customer_name, $plan_name, strtolower($status), $this->store), $body);
	// 						// $body = str_replace(array("{{customer_name}}", "{{plan_name}}", "{{skip_date}}", "{{store_name}}"), array($customer_name, $plan_name, $emailupcoming_date, $this->store), $body);
	// 						// $body = str_replace(array("{{customer_name}}", "{{plan_name}}", "{{skip_date}}", "{{store_name}}"), array($customer_name, $plan_name, $emailupcoming_date, $this->store), $body);
	// 						$data = array(

	// 							'email_template_array' =>
	// 							array(
	// 								'subject' => $subject,
	// 								'send_to_email' => $email_send_to,
	// 								'template_name' => 'membership_cancelleds',
	// 								'emailBody' => $bodyData,
	// 								'reply_to' => $email_template['reply_to'],
	// 								'bcc_email' => $email_template['bcc_email'],
	// 								'ccc_email' => $email_template['ccc_email'],
	// 							)

	// 						);
	// 						// print_r($data);
	// 						$this->emailSend($data);
	// 					} else {

	// 						$data = array(

	// 							'template_type' => 'subscription_status_' . strtolower($changedContractStatus) . '_template',

	// 							'contract_id' => $contractId,

	// 							'contract_details' => $contract_details[0],

	// 							'contract_product_details' => $contract_product_details

	// 						);

	// 						$email_template_data = $this->email_template($data, 'send_dynamic_email');

	// 						if ($email_template_data['template_type'] != 'none') {

	// 							$sendMailArray = array(

	// 								'sendTo' =>  $email_send_to,

	// 								'subject' => $email_template_data['email_subject'],

	// 								'mailBody' => $email_template_data['test_email_content'],

	// 								'mailHeading' => '',

	// 								'ccc_email' => $email_template_data['ccc_email'],

	// 								'bcc_email' =>  $email_template_data['bcc_email'],

	// 								// 'from_email' =>  $from_email,

	// 								'reply_to' => $email_template_data['reply_to']

	// 							);

	// 							try {

	// 								$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
	// 							} catch (Exception $e) {
	// 							}
	// 						}
	// 					}
	// 				}

	// 				$this->customerTagUpdate($contract_details[0]['plan_type'], $status, $contract_details[0]['shopify_customer_id'], $contractId);

	// 				return json_encode(array("status" => true, 'message' => 'Subscription Updated Successfully'));
	// 			} else {

	// 				return json_encode(array("status" => false, 'message' => 'Error Occured'));
	// 			}
	// 		} else {

	// 			return json_encode(array("status" => false, 'message' => 'Error Occured'));
	// 		}
	// 	} else {

	// 		return json_encode(array("status" => false, 'message' => 'Subscription Update setting has been disabled'));
	// 	}
	// }
	
	// public function updateSubscriptionStatusCommit($contractId, $status, $adminEmail, $customerEmail, $ajaxCallFrom, $next_billing_date, $delivery_billing_type, $billing_policy_value, $contract_details, $contract_product_details)
	public function updateSubscriptionStatusCommit($contractId, $status, $adminEmail, $customerEmail, $ajaxCallFrom, $next_billing_date, $delivery_billing_type, $billing_policy_value)
	{   

        $sql =
			"SELECT subscriptionOrderContract.plan_type, subscriptionOrderContract.shopify_customer_id, customers.email, customers.name, subscritionOrderContractProductDetails.product_name, subscritionOrderContractProductDetails.variant_name FROM subscriptionOrderContract
				INNER JOIN customers  ON customers.shopify_customer_id = subscriptionOrderContract.shopify_customer_id
				INNER JOIN subscritionOrderContractProductDetails  ON subscritionOrderContractProductDetails.contract_id = subscriptionOrderContract.contract_id
            where subscriptionOrderContract.contract_id = '$contractId'AND subscriptionOrderContract.store_id = '$this->store_id' LIMIT 1";


		$contract_details = $this->customQuery($sql);


		// $store_all_subscription_plans_qry = "SELECT * FROM `subscritionOrderContractProductDetails` WHERE contract_id = '$contractId' and store_id = '$this->store_id' LIMIT 1";
		// $contract_product_details = $this->customQuery($sql);

		// Check if setting changes are allowed
		$column_name = ($status == 'CANCELLED') ? 'cancel_subscription' : 'pause_resume_subscription';
		$check_contract_update_setting = $this->getSettingData('customer_settings', $column_name);

		if ($check_contract_update_setting[$column_name] != '1' && $ajaxCallFrom != 'backendAjaxCall') {
			return json_encode(["status" => false, "message" => "Subscription Update setting has been disabled"]);
		}

		$subscription_draft_contract_id = $this->getContractDraftId($contractId);
		if ($this->updateSubscriptionStatus($subscription_draft_contract_id, $status) != 'Success') {
			return json_encode(["status" => false, "message" => "Error Occured"]);
		}

		if ($this->commitContractDraft($subscription_draft_contract_id) != 'Success') {
			return json_encode(["status" => false, "message" => "Error Occured"]);
		}

		$whereCondition = ['contract_id' => $contractId];
		$currentDate = date('Y-m-d');
		$changedContractStatus = ($status == 'ACTIVE') ? 'Resumed' : (($status == 'CANCELLED') ? 'Cancelled' : 'Paused');

		if ($status == 'ACTIVE') {
			if ($currentDate > $next_billing_date) {
				$TotalIntervalCount = $this->getBillingRenewalDays(strtoupper($delivery_billing_type), $billing_policy_value);
				do {
					$next_billing_date = date('Y-m-d', strtotime("+{$TotalIntervalCount} day", strtotime($next_billing_date)));
				} while ($next_billing_date <= $currentDate);
			}
			try {
				$this->update_row('subscriptionOrderContract', [
					'contract_status' => $status[0],
					'next_billing_date' => $next_billing_date,
					'updated_at' => gmdate('Y-m-d H:i:s')
				], $whereCondition, 'and');
			} catch (Exception $e) {}
		} elseif ($status == 'CANCELLED' || $status == 'PAUSED') {
			try {
				$this->update_row('subscriptionOrderContract', [
					'contract_status' => $status[0],
					'updated_at' => gmdate('Y-m-d H:i:s')
				], $whereCondition, 'and');
			} catch (Exception $e) {}
		}

		// Email sending logic
		$get_fields = 'customer_subscription_status_' . strtolower($changedContractStatus) . ',admin_subscription_status_' . strtolower($changedContractStatus);
		$contract_deleted_mail = $this->getSettingData('email_notification_setting', $get_fields);
  	$email_send_to = '';
		if ($contract_deleted_mail['customer_subscription_status_' . strtolower($changedContractStatus)] == '1') {
			$email_send_to = $customerEmail;
		}
		if ($contract_deleted_mail['admin_subscription_status_' . strtolower($changedContractStatus)] == '1') {
			$email_send_to = ($email_send_to) ? [$customerEmail, $adminEmail] : $adminEmail;
		}
		
		if ($email_send_to) {
			if ($contract_details[0]['plan_type'] == 'membership') {
				$customer_name = $contract_details[0]['name'];
				$plan_name = $contract_details[0]['product_name'] . '-' . $contract_details[0]['variant_name'];
				$template_data = [
					'template_name' => 'membership_cancelleds',
					'email_type' => '',
					'shop_name' => $this->store,
					'discount_coupon_content' => true,
					'free_shipping_coupon_content' => true,
					'free_signup_product_content' => true,
					'free_gift_uponsignupSelectedDays' => true
				];
				$email_template = $this->membershipAllEmailTemplates($template_data);
				$subject = str_replace(["{{plan_status}}"], [strtolower($status)], $email_template['subject']);
				$bodyData = str_replace(
					["{{customer_name}}", "{{plan_name}}", "{{plan_status}}", "{{store_name}}"],
					[$customer_name, $plan_name, strtolower($status), $this->store],
					$email_template['email_template_html']
				);
				$this->emailSend([
					'email_template_array' => [
						'subject' => $subject,
						'send_to_email' => $email_send_to,
						'template_name' => 'membership_cancelleds',
						'emailBody' => $bodyData,
						'reply_to' => $email_template['reply_to'],
						'bcc_email' => $email_template['bcc_email'],
						'ccc_email' => $email_template['ccc_email'],
					]
				]);
			} else {
				$email_template_data = $this->email_template([
					'template_type' => 'subscription_status_' . strtolower($changedContractStatus) . '_template',
					'contract_id' => $contractId,
					// 'contract_details' => $contract_details[0],
					// 'contract_product_details' => $contract_product_details
				], 'send_dynamic_email');

				if ($email_template_data['template_type'] != 'none') {
					try {
						$this->sendMail([
							'sendTo' => $email_send_to,
							'subject' => $email_template_data['email_subject'],
							'mailBody' => $email_template_data['test_email_content'],
							'mailHeading' => '',
							'ccc_email' => $email_template_data['ccc_email'],
							'bcc_email' => $email_template_data['bcc_email'],
							'reply_to' => $email_template_data['reply_to']
						], 'false');
					} catch (Exception $e) {
             echo $e->getMessage(); die;
					}
				}
			}
		}

		$this->customerTagUpdate($contract_details[0]['plan_type'], $status, $contract_details[0]['shopify_customer_id'], $contractId);

		return json_encode(["status" => true, 'message' => 'Subscription Updated Successfully']);
	}


	//   dunning management mail function
	public function updateSubscriptionStatusFailed($contract_id, $status, $adminEmail, $customerEmail, $store_name, $store_access_token, $contract_details, $contract_product_details, $skipOrderDate)
	{

		// $status ?? = 'PAUSED';
		$contractId = $contract_id;

		$configdata = array(
			'ShopUrl' => $store_name,
			'AccessToken' => $store_access_token
		);

		$shopifies = new ShopifySDK($configdata);
		if ($status != 'SKIPPED') {

			$subscription_draft_contractId = $this->getContractDraftId($contractId);

			$changeStatus = $this->updateSubscriptionStatus($subscription_draft_contractId, $status);

			if ($changeStatus == 'Success') {

				$commitChanges =	$this->commitContractDraft($subscription_draft_contractId);

				$responseData = json_encode($commitChanges, true);
			}
		} else {
			$commitChanges = 'Success';
		}

		// Check if cancellation was successful
		if ($status == 'PAUSED') {
			$changedContractStatus = 'Paused';
		} elseif ($status == 'CANCELLED') {
			$changedContractStatus = 'Cancel';
		}


		$get_fields = $status == 'SKIPPED' ? 'customer_skip_order, admin_skip_order' : 'customer_subscription_status_' . strtolower($changedContractStatus) . ',admin_subscription_status_' . strtolower($changedContractStatus);
		// print_r($get_fields);die;
		$contract_deleted_mail = $this->getSettingData('email_notification_setting', $get_fields);

		$email_send_to = '';
		// $email_send_to = array($customerEmail, $adminEmail);
		$email_send_to = array($customerEmail);
		$template_type =  $status == 'SKIPPED' ? 'skip_order_template' : 'subscription_status_' . strtolower($changedContractStatus) . '_template';
		if ($email_send_to != '') {

			$data = array(

				'template_type' => $template_type,
				'contract_id' => $contractId,
				'contract_details' => $contract_details,
				'contract_product_details' => $contract_product_details,
				'skipped_order_date' => $skipOrderDate
			);
			// print_r($data);

			$email_template_data = $this->email_template($data, 'send_dynamic_email');
			// print_r($email_template_data);
			if ($email_template_data['template_type'] != 'none') {

				$sendMailArray = array(

					'sendTo' =>  $email_send_to,

					'subject' => $email_template_data['email_subject'],

					'mailBody' => $email_template_data['test_email_content'],

					'mailHeading' => '',

					'ccc_email' => $email_template_data['ccc_email'],

					'bcc_email' =>  $email_template_data['bcc_email'],

					'reply_to' => $email_template_data['reply_to']

				);
				if ($commitChanges == 'Success') {
					$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
					return json_encode(["status" => true, "message" => "Subscription " . strtolower($status) . " and Email Sent"]);
				} else {
					return json_encode(["status" => false, "message" => "Subscription " . strtolower($status) . " Failed", "errors" => $responseData]);
				}
			}
		} else {

			if ($commitChanges == 'Success') {
				return json_encode(["status" => true, "message" => "Subscription " . strtolower($status) . " successfully"]);
			} else {
				return json_encode(["status" => false, "message" => "Subscription " . strtolower($status) . " Failed", "errors" => $responseData]);
			}
		}
	}


	public function updateSubscriptionStatus($subscription_draft_contract_id, $contractStatus)
	{

		try {

			$updateContractStatus = 'mutation {

			subscriptionDraftUpdate(

				draftId: "' . $subscription_draft_contract_id . '"

				input: { status : ' . $contractStatus . ' }

			) {

				draft {

				id

				}

				userErrors {

				field

				message

				}

			}

			}';

			$updateContractStatus_execution = $this->graphqlQuery($updateContractStatus, null, null, null);

			$updateContractStatus_execution_error = $updateContractStatus_execution['data']['subscriptionDraftUpdate']['userErrors'];

			if (!count($updateContractStatus_execution_error)) {

				return 'Success';
			}
		} catch (Exception $e) {

			return 'error';
		}
	}



	public function update_deliveryBilling_frequency($contract_draft_id, $delivery_billing_frequencies)
	{

		try {

			$updateDeliveryBillingFrequency = '

			mutation {

				subscriptionDraftUpdate(

				  draftId: "' . $contract_draft_id . '"

				  input: {



					deliveryPolicy: {

						interval: ' . $delivery_billing_frequencies['delivery_billing_type'] . ',

						intervalCount: ' . $delivery_billing_frequencies['delivery_billing_frequency'] . '

					},

					billingPolicy: {

						interval: ' . $delivery_billing_frequencies['delivery_billing_type'] . ',

						intervalCount: ' . $delivery_billing_frequencies['delivery_billing_frequency'] . '

					},

				  }

				) {

					draft {

						deliveryPolicy{

							interval

							intervalCount

						}

						billingPolicy{

							interval

							intervalCount

						}

					}

					userErrors {

						field

						message

					}

				}

			  }

			';

			$updateDeliveryBillingFrequency_execution = $this->graphqlQuery($updateDeliveryBillingFrequency, null, null, null);

			$updateDeliveryBillingFrequency_error = $updateDeliveryBillingFrequency_execution['data']['subscriptionDraftUpdate']['userErrors'];

			if (!count($updateDeliveryBillingFrequency_error)) {

				return array("status" => true, "delivery_billing_data" => $updateDeliveryBillingFrequency_execution);
			} else {

				return array("status" => false, "message" => $updateDeliveryBillingFrequency_error[0]['message']);
			}
		} catch (Exception $e) {

			return array("status" => false, "message" => $e->getMessage());
		}
	}



	public function commitContractDraft($subscription_draft_contract_id)
	{

		try {

			$updateContractStatus = 'mutation {

				subscriptionDraftCommit(draftId: "' . $subscription_draft_contract_id . '") {

				contract {

					id

					status

				}

				userErrors {

					field

					message

				}

				}

			}';

			$commitContractStatus_execution = $this->graphqlQuery($updateContractStatus, null, null, null);

			$commitContractStatus_execution_error = $commitContractStatus_execution['data']['subscriptionDraftCommit']['userErrors'];

			if (!count($commitContractStatus_execution_error)) {

				return 'Success';
			} else {

				return 'error';
			}
		} catch (Exception $e) {

			return 'error';
		}
	}



	public function getBillingStatus()
	{

		$whereCondition = array(

			'store_id' => $this->store_id,

			'status' => '1'

		);

		$currentPlanDetails = $this->table_row_value('storeInstallOffers', 'All', $whereCondition, 'and', '');

		$planName = $currentPlanDetails[0]['planName'];

		$plan_id = $currentPlanDetails[0]['plan_id'];

		if ($plan_id != '1') {

			return 'ACTIVE';
		} else {

			return "FREE";
		}
	}

	// get total contract orders

	public function totalContractOrders($contract_id)
	{

		$get_contract_total_orders =  $this->customQuery("SELECT COUNT(*) as recurringOrderTotal FROM billingAttempts WHERE store_id = '$this->store_id' AND contract_id = '$contract_id' AND status = 'Success'");

		return $total_contract_orders = ($get_contract_total_orders[0]['recurringOrderTotal'] + 1);
	}

	public function addNewContractProducts($newProductsAry, $productDetail, $added_by, $contract_details, $contract_product_details)
	{

		$check_customer_setting = $this->getSettingData('customer_settings', 'add_subscription_product');

		$all_new_contract_products = array();

		if ($check_customer_setting['add_subscription_product'] == '1' || $added_by == 'Admin') {

			$draft_id = $this->getContractDraftId($productDetail['contract_id']);

			foreach ($newProductsAry as $value) {

				//add new line in the contract mutation

				$whereCondition = array(

					'contract_id' => $productDetail['contract_id']

				);

				$fields = array('discount_type', 'discount_value', 'recurring_discount_type', 'recurring_discount_value', 'selling_plan_id', 'after_cycle', 'billing_policy_value', 'delivery_policy_value', 'after_cycle_update');

				$get_contract_data = $this->single_row_value('subscriptionOrderContract', $fields, $whereCondition, 'and', '');

				$price = $value['price'];

				$price = ($price * ($get_contract_data['billing_policy_value'] / $get_contract_data['delivery_policy_value']));

				$computedPrice = 0;

				if ($get_contract_data['discount_value'] != 0 || $get_contract_data['recurring_discount_value'] != 0) {

					if ($get_contract_data['discount_type'] == 'A') {

						if ($price > $get_contract_data['discount_value']) {

							$price = $price - $get_contract_data['discount_value'];
						} else {

							$price = 0;
						}
					} else if ($get_contract_data['discount_type'] == 'P') {

						$price = $price - ($price * ($get_contract_data['discount_value'] / 100));
					}



					// if recurring discount is applied

					if ($get_contract_data['recurring_discount_value'] != 0) {

						if ($get_contract_data['recurring_discount_type'] == 'A') {

							$adjustmentType = 'FIXED_AMOUNT';

							$fixedorpercntValue = "fixedValue:" . $get_contract_data['recurring_discount_value'];

							if ($value['price'] > $get_contract_data['recurring_discount_value']) {

								$computedPrice = $value['price'] - $get_contract_data['recurring_discount_value'];
							} else {

								$computedPrice = 0;
							}
						} else {

							$adjustmentType = 'PERCENTAGE';

							$fixedorpercntValue = "percentage:" . $get_contract_data['recurring_discount_value'];

							$computedPrice = $value['price'] - ($value['price'] * ($get_contract_data['recurring_discount_value'] / 100));
						}

						$cycleDiscount = 'cycleDiscounts : {

											adjustmentType: ' . $adjustmentType . '

											adjustmentValue :  {

												' . $fixedorpercntValue . '

											}

											afterCycle: ' . $get_contract_data['after_cycle'] . '

											computedPrice:' . $computedPrice . '

										}';

						$pricingPolicy = 'pricingPolicy :{

											basePrice : ' . $price . '

											' . $cycleDiscount . '

										}';

						if ($get_contract_data['after_cycle_update'] == '1') {

							$price = $computedPrice;
						}
					} else {

						$pricingPolicy = '';
					}
				} else {

					$pricingPolicy = '';
				}

				try {

					$addNewLineItem = 'mutation{

							subscriptionDraftLineAdd(

							draftId: "' . $draft_id . '"

							input: {

								productVariantId: "gid://shopify/ProductVariant/' . $value['variant_id'] . '"

								quantity: ' . $value['quantity'] . '

								currentPrice: ' . number_format((float)$price, 2, '.', '') . '

								' . $pricingPolicy . '

							}

							) {

								lineAdded {

									id

								}

								userErrors {

									field

									message

								}

							}

						}';

					$addNewLineItem_execution = $this->graphqlQuery($addNewLineItem, null, null, null);

					$addNewLineItem_error = $addNewLineItem_execution['data']['subscriptionDraftLineAdd']['userErrors'];
				} catch (Exception $e) {

					return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

				}

				if (!count($addNewLineItem_error)) {

					$AddLineItemId = substr($addNewLineItem_execution['data']['subscriptionDraftLineAdd']['lineAdded']['id'], strrpos($addNewLineItem_execution['data']['subscriptionDraftLineAdd']['lineAdded']['id'], '/') + 1);

					$fields = array(

						"store_id" => $this->store_id,

						"contract_id" => $productDetail['contract_id'],

						"product_id" => $value['product_id'],

						"variant_id" =>  $value['variant_id'],

						"product_name" => $value['product_title'],

						// "variant_name" =>  $value['variant_title'],

						"variant_name" => str_replace('"', '', $value['variant_title']),

						"subscription_price" => number_format((float)$price, 2, '.', ''),

						'quantity' => $value['quantity'],

						"contract_line_item_id" => $AddLineItemId,

						"recurring_computed_price" => number_format((float)$computedPrice, 2, '.', ''),

						"variant_image" => $value['image'],

					);

					$insert_row = $this->insert_row('subscritionOrderContractProductDetails', $fields);



					array_push($all_new_contract_products, $fields);
				} else {

					$addNewLineItem_execution_error  = $addNewLineItem_execution['data']['subscriptionDraftLineAdd']['userErrors'];
				}
			}



			$newProductsAdded =	$this->commitContractDraft($draft_id);

			if ($newProductsAdded == 'Success') {

				$send_mail_to = '';

				$product_added_mail = $this->getSettingData('email_notification_setting', 'customer_product_added,admin_product_added');

				if ($product_added_mail['customer_product_added'] == '1' && $product_added_mail['admin_product_added'] == '1') {

					$send_mail_to = array($productDetail['customerEmail'], $productDetail['adminEmail']);
				} else if ($product_added_mail['customer_product_added'] == '1' && $product_added_mail['admin_product_added'] != '1') {

					$send_mail_to = $productDetail['customerEmail'];
				} else if ($product_added_mail['customer_product_added'] != '1' && $product_added_mail['admin_product_added'] == '1') {

					$send_mail_to = $productDetail['adminEmail'];
				}

				if ($send_mail_to != '') {

					$data = array(

						'template_type' => 'product_added_template',

						'contract_id' => $productDetail['contract_id'],

						'contract_details' => $contract_details[0],

						'contract_product_details' => $contract_product_details,

						'new_added_products' => $all_new_contract_products

					);

					$email_template_data = $this->email_template($data, 'send_dynamic_email');

					if ($email_template_data['template_type'] != 'none') {

						$sendMailArray = array(

							'sendTo' =>  $send_mail_to,

							'subject' => $email_template_data['email_subject'],

							'mailBody' => $email_template_data['test_email_content'],

							'mailHeading' => '',

							'ccc_email' => $email_template_data['ccc_email'],

							'bcc_email' =>  $email_template_data['bcc_email'],

							// 'from_email' =>  $from_email,

							'reply_to' => $email_template_data['reply_to']

						);

						try {

							$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
						} catch (Exception $e) {
						}
					}

					// 	$mailBody = '<table class="module" role="module" data-type="text" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;" data-muid="2f94ef24-a0d9-4e6f-be94-d2d1257946b0" data-mc-module-version="2019-10-22">

					// 	<tbody>

					// 	  <tr>

					// 		<td style="padding:18px 50px 18px 50px; line-height:22px; text-align:inherit; background-color:#FFFFFF;" height="100%" valign="top" bgcolor="#FFFFFF" role="module-content"><div><div style="font-family: inherit; text-align: center"><span style="font-size: 16px; font-family: inherit">New Product(s) has been added in the Subscription with  Id #'.$productDetail['contract_id'].'</span></div><div></div></div></td>

					// 	  </tr>

					// 	</tbody>

					//   </table>';

					// 	$sendMailArray = array(

					// 		'sendTo' =>  $send_mail_to,

					// 		'subject' => 'New Product Added in the Subscription',

					// 		'mailBody' => $mailBody,

					// 		'mailHeading' => 'Product Added in the Subscription'

					// 	);

					// 	$this->sendMail($sendMailArray,'false');

				}

				return json_encode(array("status" => true, 'message' => 'Product(s) Added Successfully')); // return json

			} else {

				return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Add Product Setting has been disabled')); // return json

		}
	}



	public function getCurrencySymbol($currencyCode)
	{

		$currency_list = array(

			"AFA" => "؋",
			"ALL" => "Lek",
			"DZD" => "دج",
			"AOA" => "Kz",
			"ARS" => "$",
			"AMD" => "֏",
			"AWG" => "ƒ",
			"AUD" => "$",
			"AZN" => "m",
			"BSD" => "B$",
			"BHD" => ".د.ب",
			"BDT" => "৳",
			"BBD" => "Bds$",
			"BYR" => "Br",
			"BEF" => "fr",
			"BZD" => "$",
			"BMD" => "$",
			"BTN" => "Nu.",
			"BTC" => "฿",
			"BOB" => "Bs.",
			"BAM" => "KM",
			"BWP" => "P",
			"BRL" => "R$",
			"GBP" => "£",
			"BND" => "B$",
			"BGN" => "Лв.",
			"BIF" => "FBu",
			"KHR" => "KHR",
			"CAD" => "$",
			"CVE" => "$",
			"KYD" => "$",
			"XOF" => "CFA",
			"XAF" => "FCFA",
			"XPF" => "₣",
			"CLP" => "$",
			"CNY" => "¥",
			"COP" => "$",
			"KMF" => "CF",
			"CDF" => "FC",
			"CRC" => "₡",
			"HRK" => "kn",
			"CUC" => "$, CUC",
			"CZK" => "Kč",
			"DKK" => "Kr.",
			"DJF" => "Fdj",
			"DOP" => "$",
			"XCD" => "$",

			"EGP" => "ج.م",
			"ERN" => "Nfk",
			"EEK" => "kr",
			"ETB" => "Nkf",
			"EUR" => "€",
			"FKP" => "£",
			"FJD" => "FJ$",
			"GMD" => "D",
			"GEL" => "ლ",
			"DEM" => "DM",
			"GHS" => "GH₵",
			"GIP" => "£",
			"GRD" => "₯, Δρχ, Δρ",
			"GTQ" => "Q",
			"GNF" => "FG",
			"GYD" => "$",
			"HTG" => "G",
			"HNL" => "L",
			"HKD" => "$",
			"HUF" => "Ft",
			"ISK" => "kr",
			"INR" => "Rs.",
			"IDR" => "Rp",
			"IRR" => "﷼",
			"IQD" => "د.ع",
			"ILS" => "₪",
			"ITL" => "L,£",
			"JMD" => "J$",
			"JPY" => "¥",
			"JOD" => "ا.د",
			"KZT" => "лв",
			"KES" => "KSh",
			"KWD" => "ك.د",
			"KGS" => "лв",
			"LAK" => "₭",

			"LVL" => "Ls",
			"LBP" => "£",
			"LSL" => "L",
			"LRD" => "$",
			"LYD" => "د.ل",
			"LTL" => "Lt",
			"MOP" => "$",
			"MKD" => "ден",
			"MGA" => "Ar",
			"MWK" => "MK",
			"MYR" => "RM",
			"MVR" => "Rf",
			"MRO" => "MRU",
			"MUR" => "₨",
			"MXN" => "$",
			"MDL" => "L",
			"MNT" => "₮",
			"MAD" => "MAD",
			"MZM" => "MT",
			"MMK" => "K",
			"NAD" => "$",
			"NPR" => "₨",
			"ANG" => "ƒ",
			"TWD" => "$",
			"NZD" => "$",
			"NIO" => "C$",
			"NGN" => "₦",
			"KPW" => "₩",
			"NOK" => "kr",
			"OMR" => ".ع.ر",
			"PKR" => "₨",
			"PAB" => "B/.",
			"PGK" => "K",
			"PYG" => "₲",
			"PEN" => "S/.",
			"PHP" => "₱",
			"PLN" => "zł",
			"QAR" => "ق.ر",
			"RON" => "lei",
			"RUB" => "₽",
			"RWF" => "FRw",
			"SVC" => "₡",
			"WST" => "SAT",
			"SAR" => "﷼",
			"RSD" => "din",
			"SCR" => "SRe",
			"SLL" => "Le",
			"SGD" => "$",
			"SKK" => "Sk",
			"SBD" => "Si$",
			"SOS" => "Sh.so.",
			"ZAR" => "R",
			"KRW" => "₩",
			"XDR" => "SDR",
			"LKR" => "Rs",
			"SHP" => "£",
			"SDG" => ".س.ج",
			"SRD" => "$",
			"SZL" => "E",
			"SEK" => "kr",
			"CHF" => "CHf",
			"SYP" => "LS",
			"STD" => "Db",
			"TJS" => "SM",
			"TZS" => "TSh",
			"THB" => "฿",
			"TOP" => "$",
			"TTD" => "$",
			"TND" => "ت.د",
			"TRY" => "₺",
			"TMT" => "T",
			"UGX" => "USh",
			"UAH" => "₴",
			"AED" => "إ.د",
			"UYU" => "$",
			"USD" => "$",
			"UZS" => "лв",
			"VUV" => "VT",
			"VEF" => "Bs",
			"VND" => "₫",
			"YER" => "﷼",
			"ZMK" => "ZK"

		);

		return $currency_list[$currencyCode];
	}



	// send mail to customer to update payment details

	public function sendPaymentUpdateMail($paymentUpdateToken, $emailSendTo)
	{

		try {

			$updatePayment =  'mutation customerPaymentMethodSendUpdateEmail($customerPaymentMethodId: ID!) {

				customerPaymentMethodSendUpdateEmail(customerPaymentMethodId: $customerPaymentMethodId) {

				  customer {

					id

				  }

				  userErrors {

					field

					message

				  }

				}

			  }';

			$updatePaymentParameters = [

				"customerPaymentMethodId" => "gid://shopify/CustomerPaymentMethod/" . $paymentUpdateToken,

				"email" => [

					"bcc" => "vipingarg.shinedezign@gmail",

					"body" => "Update Payment Method",

					"customMessage" => "payment update",

					"from" => "vipingarg.shinedezign@gmail",

					"subject" => "Update Payment Method",

					"to" => $emailSendTo

				]

			];

			$updatePaymentGet = $this->graphqlQuery($updatePayment, null, null, $updatePaymentParameters);

			$updatePaymentGet_error = $updatePaymentGet['data']['customerPaymentMethodSendUpdateEmail']['userErrors'];
		} catch (Exception $e) {

			return json_encode(array("status" => false, 'message' => 'Error occured')); // return json

		}

		if (!count($updatePaymentGet_error)) {

			return json_encode(array("status" => true, 'message' => 'Payment Method Update Request has been sent to the Customer mail')); // return json

		} else {

			return json_encode(array("status" => true, 'message' => $updatePaymentGet_error[0]['message'])); // return json

		}
	}



	public function getProductData($productIdsArray)
	{

		if (is_array($productIdsArray)) {

			$getVariant = 30;
		} else {

			$getVariant = 100;
		}

		try {

			$getProductData = '{

			nodes(ids: ' . $productIdsArray . ') {

			  ...on Product {

				title

				id

				featuredImage {

				  originalSrc

				}

				variants(first:' . $getVariant . '){

					edges{

					  node{

						id

						title

						image{

							originalSrc

						  }

					  }

					}

				}

			  }

			}

		  }';

			return $this->graphqlQuery($getProductData, null, null, null);
		} catch (Exception $e) {

			return json_encode(array("status" => false, 'message' => 'Error occured')); // return json

		}
	}



	public function getGroupProductVariant($sellingPlanGroupId, $product_id)
	{

		$get_groupProduct = 'query{

			sellingPlanGroup(id : "' . $sellingPlanGroupId . '") {

				productCount

				productVariantCount

				appliesToProduct(productId : "' . $product_id . '")

			}

		}';

		$graphQL_get_groupProduct = $this->graphqlQuery($get_groupProduct, null, null, null);

		return $graphQL_get_groupProduct;
	}





	// public function getVariantData

	public function getProductVariant($product_id)
	{

		$getVariantData = '{

			product(id:"' . $product_id . '") {

				variants(first: 100){

					edges{

						node{

							id

						}

					}

				}

			}

		}';

		$variantData_execution =  $this->graphqlQuery($getVariantData, null, null, null);

		return $variantData_execution;
	}



	public function getVariantData($cursor, $product_id)
	{

		if ($cursor != '') {

			$after_cursor = ', after : "' . $cursor . '"';
		} else {

			$after_cursor = '';
		}

		$getVariantData = '{

			product(id:"gid://shopify/Product/' . $product_id . '") {

				handle

			variants(first: 80 ' . $after_cursor . '){

				pageInfo {

					hasNextPage

					hasPreviousPage

				}

				edges{

				  cursor

				  node{

					id

					title

					price

					inventoryQuantity

					image{

						originalSrc

					}

				  }

				}

			}

			}

		}';

		$variantData_execution =  $this->graphqlQuery($getVariantData, null, null, null);

		return $variantData_execution;
	}



	public function getVariantDetail($variant_id)
	{

		$getSingleVariantData = '{

			nodes(ids: ' . $variant_id . ') {

			...on ProductVariant {

				id

				inventoryQuantity

				availableForSale

				inventoryPolicy

				product{

					onlineStorePreviewUrl

				}

			}

			}

		}';

		$getSingleVariantData_execution =  $this->graphqlQuery($getSingleVariantData, null, null, null);

		return $getSingleVariantData_execution;
	}



	public function getAllProducts($cursor, $query)
	{

		if ($cursor == '') {

			$get_products_after = '';
		} else {

			$get_products_after = 'after : "' . $cursor . '",';
		}

		if ($query == '') {

			$get_search_products = ', query: "status:ACTIVE"';
		} else {

			$get_search_products = ',  query: "title:*' . $query . '* AND status:ACTIVE"';
		}

		try {

			$getProductData = '{

				products(first: 10, ' . $get_products_after . '  ' . $get_search_products . ', reverse:true) {

					pageInfo {
                        hasNextPage
                        hasPreviousPage
                    }
                    edges {
                        cursor
                        node {
                            title
                            id
                            handle
                            featuredMedia {
                                preview {
                                    image {
                                        url
                                    }
                                }
                            }
                            totalInventory
                            variantsCount {
                                count
                            }
                            variants(first: 20) {
                                pageInfo {
                                    hasNextPage
                                    hasPreviousPage
                                }
                                edges {
                                    cursor
                                    node {
                                        id
                                        title
                                        price
                                        inventoryQuantity
                                        inventoryPolicy
                                        image {
                                            url
                                        }
                                        inventoryItem {
                                            requiresShipping
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

				

			}';

			$prouductData_execution = $this->graphqlQuery($getProductData, null, null, null);
		} catch (Exception $e) {

			$prouductData_execution = ''; // return json

		}

		return $prouductData_execution;
	}



	public function getShopData($store)
	{

		$getShopData = $this->PostPutApi('https://' . $this->store . '/admin/api/' . $this->SHOPIFY_API_VERSION . '/shop.json', 'GET', $this->access_token, '');

		return $getShopData;
	}



	public function update_selling_planName($contract_draft_id, $line_item_id, $selling_plan_name)
	{

		try {

			$update_selling_plan_name_mutation = 'mutation {

			subscriptionDraftLineUpdate(

			draftId: "' . $contract_draft_id . '"

			lineId: "gid://shopify/SubscriptionLine/' . $line_item_id . '"

			input: {

				sellingPlanName : "' . $selling_plan_name . '"

			}

			) {

				lineUpdated {

					id

					quantity

					currentPrice{

						amount

					}

					productId

				}

				draft {

					id

				}

				userErrors {

					field

					message

					code

				}

			}

		}';



			$productUpdateData_execution = $this->graphqlQuery($update_selling_plan_name_mutation, null, null, null);

			$productUpdateDataapi_error = $productUpdateData_execution['data']['subscriptionDraftLineUpdate']['userErrors'];

			if (!count($productUpdateDataapi_error)) {

				return true;
			} else {

				return false;
			}
		} catch (Exception $e) {

			return array("status" => false, 'error' => $e->getMessage(), 'message' => 'Quantity Upate error'); // return json

		}
	}



	//   update quantity of product subscription

	public function updateSubscriptionProductQty($data)
	{

		$get_fields = 'edit_product_quantity,edit_product_price';

		$check_customer_update = $this->getSettingData('customer_settings', $get_fields);

		if ($data['updated_by'] == 'Admin') {

			$update_product = 'true';

			$update_data = '{ quantity: ' . $data['prd_qty'] . ', currentPrice : ' . $data['prd_price'] . '}';
		} else if ($data['prd_old_price'] != $data['prd_price'] && $data['prd_old_qty'] != $data['prd_qty'] && $check_customer_update['edit_product_price'] == '1' && $check_customer_update['edit_product_quantity'] == '1') {

			$update_product = 'true';

			$update_data = '{ quantity: ' . $data['prd_qty'] . ', currentPrice : ' . $data['prd_price'] . '}';
		} else if ($data['prd_old_price'] == $data['prd_price'] && $data['prd_old_qty'] != $data['prd_qty'] && $check_customer_update['edit_product_quantity'] == '1') {

			$update_product = 'true';

			$update_data = '{ quantity: ' . $data['prd_qty'] . '}';
		} else if ($data['prd_old_qty'] == $data['prd_qty'] && $data['prd_old_price'] != $data['prd_price'] && $check_customer_update['edit_product_price'] == '1') {

			$update_product = 'true';

			$update_data = '{ currentPrice: ' . $data['prd_price'] . '}';
		} else {

			$update_product = 'false';
		}

		if ($update_product == 'true') {

			$contract_draftId = $this->getContractDraftId(trim($data['contract_id']));

			try {

				$productUpdateData =   'mutation {

				subscriptionDraftLineUpdate(

				draftId: "' . $contract_draftId . '"

				lineId: "gid://shopify/SubscriptionLine/' . $data['line_id'] . '"

				input: ' . $update_data . '

				) {

					lineUpdated {

						id

						quantity

						currentPrice{

							amount

						}

						productId

					}

					draft {

						id

					}

					userErrors {

						field

						message

						code

					}

				}

			}';

				$productUpdateData_execution = $this->graphqlQuery($productUpdateData, null, null, null);

				$productUpdateDataapi_error = $productUpdateData_execution['data']['subscriptionDraftLineUpdate']['userErrors'];
			} catch (Exception $e) {

				return array("status" => false, 'error' => $e->getMessage(), 'message' => 'Quantity Upate error'); // return json

			}

			if (!count($productUpdateDataapi_error)) {

				$this->commitContractDraft($contract_draftId);

				$updatedPrdQty = $productUpdateData_execution['data']['subscriptionDraftLineUpdate']['lineUpdated']['quantity'];

				$updatedPrdPrice = $productUpdateData_execution['data']['subscriptionDraftLineUpdate']['lineUpdated']['currentPrice']['amount'];

				$fields = array(

					'quantity' => $updatedPrdQty,

					'subscription_price' => $updatedPrdPrice

				);

				$whereCondition = array(

					'contract_line_item_id' => $data['line_id']

				);

				$this->update_row('subscritionOrderContractProductDetails', $fields, $whereCondition, 'and');

				if ($data['prd_old_price'] != $data['prd_price']) { // remove all the discount applied if price edited

					$contract_fields = array(

						'discount_value' => 0,

						'recurring_discount_value' => 0,

						'after_cycle_update' => '0',

						'after_cycle' => 0

					);

					$whereContractCondition = array(

						'contract_id' => $data['contract_id']

					);

					$this->update_row('subscriptionOrderContract', $contract_fields, $whereContractCondition, 'and');
				}

				//send product updation mail to customer and admin

				$get_fields = 'customer_product_updated,admin_product_updated';

				$quantity_updated = $this->getSettingData('email_notification_setting', $get_fields);

				$send_mail_to = '';

				if ($quantity_updated['customer_product_updated'] == '1' && $quantity_updated['admin_product_updated'] == '1') {

					$send_mail_to = array($data['customerEmail'], $data['adminEmail']);
				} else if ($quantity_updated['customer_product_updated'] == '1' && $quantity_updated['admin_product_updated'] != '1') {

					$send_mail_to = $data['customerEmail'];
				} else if ($quantity_updated['customer_product_updated'] != '1' && $quantity_updated['admin_product_updated'] == '1') {

					$send_mail_to = $data['adminEmail'];
				}

				if ($send_mail_to != '') {

					$data = array(

						'template_type' => 'product_updated_template',

						'contract_id' => $data['contract_id'],

						'contract_details' => $data['specific_contract_data'][0],

						'contract_product_details' => $data['contract_product_details'],

						'updated_product' => $data

					);

					$email_template_data = $this->email_template($data, 'send_dynamic_email');

					if ($email_template_data['template_type'] != 'none') {

						$sendMailArray = array(

							'sendTo' =>  $send_mail_to,

							'subject' => $email_template_data['email_subject'],

							'mailBody' => $email_template_data['test_email_content'],

							'mailHeading' => '',

							'ccc_email' => $email_template_data['ccc_email'],

							'bcc_email' =>  $email_template_data['bcc_email'],

							'reply_to' => $email_template_data['reply_to']

						);

						try {

							$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
						} catch (Exception $e) {
						}
					}

					// $mailBody = '<table class="module" role="module" data-type="text" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;" data-muid="2f94ef24-a0d9-4e6f-be94-d2d1257946b0" data-mc-module-version="2019-10-22">

					// 	<tbody>

					// 	  <tr>

					// 		<td style="padding:18px 50px 18px 50px; line-height:22px; text-align:inherit; background-color:#FFFFFF;" height="100%" valign="top" bgcolor="#FFFFFF" role="module-content"><div><div style="font-family: inherit; text-align: center"><span style="font-size: 16px; font-family: inherit">Product "'.$data['product_title'].'" has been updated in Subscription with Id '.$data['contract_id'].', now the product quantity is '.$updatedPrdQty.' and the product price is '.$updatedPrdPrice.'.</span></div><div></div></div></td>

					// 	  </tr>

					// 	</tbody>

					//   </table>';

					// $sendMailArray = array(

					// 	'sendTo' =>  $send_mail_to,

					// 	'subject' => 'Product Update',

					// 	'mailBody' => $mailBody,

					// 	'mailHeading' => 'Product has been Updated of the Subscription #'.$data['contract_id']

					// );

					// $this->sendMail($sendMailArray,'false');

				}

				$result = array('status' => true, 'message' => "Product Updated Successfully");

				return json_encode($result); // return json

			} else {

				return json_encode(array("status" => false, 'error' => $productUpdateDataapi_error)); // return json

			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Update Product Setting has been disabled')); // return json

		}
	}



	public function getShippingRates($draftId, $shippingDataValues)
	{

		try {

			$shippingAddressRate = '{

			subscriptionDraft(id: "' . $draftId . '") {

				shippingOptions(

				deliveryAddress: {

					address1: "' . $shippingDataValues['address1'] . '"

					address2: "' . $shippingDataValues['address2'] . '"

					company: "' . $shippingDataValues['company'] . '"

					city: "' . $shippingDataValues['city'] . '"

					country: "' . $shippingDataValues['country'] . '"

					province: "' . $shippingDataValues['province'] . '"

					zip: "' . $shippingDataValues['zip'] . '"

					firstName: "' . $shippingDataValues['first_name'] . '"

					lastName: "' . $shippingDataValues['last_name'] . '"

					phone: "' . $shippingDataValues['phone'] . '"

				}

				) {

				__typename

				... on SubscriptionShippingOptionResultSuccess {

					shippingOptions {

						title

						description

						presentmentTitle

						code

						price {

						amount

						currencyCode

						}

					}

				}

				... on SubscriptionShippingOptionResultFailure {

					message

				}

				}

			}

		}';

			$shippingData = $this->graphqlQuery($shippingAddressRate, null, null, null);

			return $shippingData;
		} catch (Exception $e) {

			return 'error';
		}
	}



	public function updateShippingAddressAndRates($draftId, $shippingDataValues)
	{

		if (isset($shippingDataValues['delivery_price'])) {

			$delivery_price_value = 'deliveryPrice : "' . $shippingDataValues['delivery_price'] . '"';
		} else {

			$delivery_price_value = '';
		}

		try {

			$updateShippingAddress = '

			mutation {

				subscriptionDraftUpdate(

				  draftId: "' . $draftId . '"

				  input: {

					deliveryMethod: {

					  shipping: {

						address: {

							address1: "' . $shippingDataValues['address1'] . '"

							address2: "' . $shippingDataValues['address2'] . '"

							company: "' . $shippingDataValues['company'] . '"

							city: "' . $shippingDataValues['city'] . '"

							country: "' . $shippingDataValues['country'] . '"

							province: "' . $shippingDataValues['province'] . '"

							zip: "' . $shippingDataValues['zip'] . '"

							firstName: "' . $shippingDataValues['first_name'] . '"

							lastName: "' . $shippingDataValues['last_name'] . '"

							phone: "' . $shippingDataValues['phone'] . '"

						}

					  }

					}

					' . $delivery_price_value . '

				  }

				) {

				  draft {

					deliveryPrice {

					  amount

					  currencyCode

					}

					deliveryMethod {

					  __typename



					  ... on SubscriptionDeliveryMethodShipping {

						address {

						  address1

						  city

						  provinceCode

						  countryCode

						}

						shippingOption {

						  title

						  presentmentTitle

						  code

						  description

						}

					  }

					}

				  }

				  userErrors {

					field

					message

				  }

				}

			  }

			';

			$updateShippingAddress_execution = $this->graphqlQuery($updateShippingAddress, null, null, null);

			$updateShippingAddress_error = $updateShippingAddress_execution['data']['subscriptionDraftUpdate']['userErrors'];

			// if($this->store == 'mytab-shinedezign.myshopify.com'){

			// 	echo '<pre>';

			// 	print_r($updateShippingAddress_execution);

			// 	die;

			// }

			if (!count($updateShippingAddress_error)) {

				return array("status" => true, "shipping_data" => $updateShippingAddress_execution);
			} else {

				return array("status" => false, "message" => $updateShippingAddress_error[0]['message']);
			}
		} catch (Exception $e) {

			// if($this->store == 'mytab-shinedezign.myshopify.com'){

			// 	echo '<pre>';

			// 	print_r($e->getMessage());

			// 	die;

			// }

			return array("status" => false, "message" => $e->getMessage());
		}
	}







	public function updateShippingAddress($contractId, $shippingDataValues, $ajaxCallFrom, $contract_details, $contract_product_details)
	{

		if (is_object($shippingDataValues)) {

			$shippingDataValues = (array) $shippingDataValues;
		}

		$check_customer_setting = $this->getSettingData('customer_settings', 'edit_shipping_address');

		$customer_email = $shippingDataValues['sendMailToCustomer'];

		$get_store_detail = $this->customQuery("SELECT currencyCode,name FROM customers,store_details WHERE store_details.store_id = '$this->store_id' and customers.store_id = '$this->store_id' and customers.email = '$customer_email'");

		$shop_currency_code = $get_store_detail[0]['currencyCode'];

		if ($get_store_detail[0]['name'] != '') {

			$customer_name = $get_store_detail[0]['name'];
		} else {

			$customer_name = $shippingDataValues['sendMailToCustomer'];
		}

		if ($check_customer_setting['edit_shipping_address'] == '1' || $ajaxCallFrom == 'backendAjaxCall') {

			$draftId =  $this->getContractDraftId($contractId);

			$shippingAddresses = $this->updateShippingAddressAndRates($draftId, $shippingDataValues);

			if ($shippingAddresses['status'] == true) {

				$addressUpdated =	$this->commitContractDraft($draftId);

				if ($addressUpdated != 'error') {

					$fields = array(

						'first_name' => htmlspecialchars($shippingDataValues['first_name'], ENT_QUOTES),

						'last_name' => htmlspecialchars($shippingDataValues['last_name'], ENT_QUOTES),

						'company' => htmlspecialchars($shippingDataValues['company'], ENT_QUOTES),

						'city' => htmlspecialchars($shippingDataValues['city'], ENT_QUOTES),

						'address1' => htmlspecialchars($shippingDataValues['address1'], ENT_QUOTES),

						'address2' => htmlspecialchars($shippingDataValues['address2'], ENT_QUOTES),

						'country' => htmlspecialchars($shippingDataValues['country'], ENT_QUOTES),

						'province' => htmlspecialchars($shippingDataValues['province'], ENT_QUOTES),

						'phone' => htmlspecialchars($shippingDataValues['phone'], ENT_QUOTES),

						'country_code' => htmlspecialchars($shippingAddresses['shipping_data']['data']['subscriptionDraftUpdate']['draft']['deliveryMethod']['address']['countryCode'], ENT_QUOTES),

						'zip' => htmlspecialchars($shippingDataValues['zip'], ENT_QUOTES),

					);

					if (isset($shippingDataValues['delivery_price'])) {

						$fields['delivery_price'] = htmlspecialchars($shippingDataValues['delivery_price'], ENT_QUOTES);
					}

					$whereCondition = array(

						'contract_id' => $contractId

					);

					$updateAddress =	json_decode($this->update_row('subscriptionContractShippingAddress', $fields, $whereCondition, 'and'));

					if ($updateAddress->status == 1) {

						$get_fields = 'customer_shipping_address_update,admin_shipping_address_update';

						$shipping_Address_change = $this->getSettingData('email_notification_setting', $get_fields);

						$sendMailTo = '';

						if ($shipping_Address_change['customer_shipping_address_update'] == '1' && $shipping_Address_change['admin_shipping_address_update'] == '1') {

							$sendMailTo = array($shippingDataValues['sendMailToAdmin'], $shippingDataValues['sendMailToCustomer']);
						} else if ($shipping_Address_change['customer_shipping_address_update'] != '1' && $shipping_Address_change['admin_shipping_address_update'] == '1') {

							$sendMailTo = $shippingDataValues['sendMailToAdmin'];
						} else if ($shipping_Address_change['customer_shipping_address_update'] == '1' && $shipping_Address_change['admin_shipping_address_update'] != '1') {

							$sendMailTo = $shippingDataValues['sendMailToCustomer'];
						}

						if ($sendMailTo != '') {

							$data = array(

								'template_type' => 'shipping_address_update_template',

								'contract_id' => $contractId,

								'contract_details' => $contract_details[0],

								'contract_product_details' => $contract_product_details,

								'shipping_data' => $shippingDataValues,

							);

							$email_template_data = $this->email_template($data, 'send_dynamic_email');

							if ($email_template_data['template_type'] != 'none') {

								$sendMailArray = array(

									'sendTo' =>  $sendMailTo,

									'subject' => $email_template_data['email_subject'],

									'mailBody' => $email_template_data['test_email_content'],

									'mailHeading' => '',

									'ccc_email' => $email_template_data['ccc_email'],

									'bcc_email' =>  $email_template_data['bcc_email'],

									'reply_to' => $email_template_data['reply_to']

								);

								try {

									$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
								} catch (Exception $e) {
								}
							}
						}
					}

					return json_encode(array("status" => true, 'message' => 'Shipping Address Updated Successfully'));
				} else {

					return json_encode(array("status" => false, 'message' => 'Update Shipping Address error'));
				}
			} else {

				return json_encode(array("status" => false, 'message' => $shippingAddresses['message']));
			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Enter Valid Shipping Address'));
		}
	}







	public function removeSubscriptionProduct($contractId, $lineId, $deleted_from, $customerEmail, $adminEmail, $contract_details, $contract_product_details)
	{

		//check if customer can delete product or not

		$check_customer_setting =  $this->getSettingData('customer_settings', 'delete_product');

		if ($check_customer_setting['delete_product'] == '1' || $deleted_from == 'Admin') {

			$contract_draftId = $this->getContractDraftId($contractId);

			try {

				$removeProduct = 'mutation {

					subscriptionDraftLineRemove(

					draftId: "' . $contract_draftId . '"

					lineId: "gid://shopify/SubscriptionLine/' . $lineId . '"

					) {

					lineRemoved {

						id

					}

					draft {

						id

					}

					userErrors {

						field

						message

						code

					}

					}

				}';

				$removeProduct_execution = $this->graphqlQuery($removeProduct, null, null, null);



				$removeProduct_error = $removeProduct_execution['data']['subscriptionDraftLineRemove']['userErrors'];
			} catch (Exception $e) {

				return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'Product Removed error')); // return json

			}

			if (!count($removeProduct_error)) {

				$commitContractChanges = $this->commitContractDraft($contract_draftId);

				if ($commitContractChanges != 'error') {

					$fields = array(

						'product_contract_status' => '0'

					);

					$whereCondition = array(

						'contract_line_item_id' => $lineId

					);

					$this->update_row('subscritionOrderContractProductDetails', $fields, $whereCondition, 'and');

					// send mail to the customer or admin

					$get_fields = 'customer_product_removed,admin_product_removed';

					$product_deleted_mail = $this->getSettingData('email_notification_setting', $get_fields);

					$sendMailTo = '';

					if ($product_deleted_mail['customer_product_removed'] == '1' && $product_deleted_mail['admin_product_removed'] == '1') {

						$sendMailTo = array($customerEmail, $adminEmail);
					} else if ($product_deleted_mail['customer_product_removed'] != '1' && $product_deleted_mail['admin_product_removed'] == '1') {

						$sendMailTo = $adminEmail;
					} else if ($product_deleted_mail['customer_product_removed'] == '1' && $product_deleted_mail['admin_product_removed'] != '1') {

						$sendMailTo = $customerEmail;
					}

					if ($sendMailTo != '') {

						$data = array(

							'template_type' => 'product_removed_template',

							'contract_id' => $contractId,

							'contract_details' => $contract_details[0],

							'contract_product_details' => $contract_product_details,

							'deleted_product_line_id' => $lineId

						);

						$email_template_data = $this->email_template($data, 'send_dynamic_email');

						// if($this->store == 'predictive-search.myshopify.com'){

						//   echo '<pre>';

						//   print_r($email_template_data);

						//   die;

						// }

						if ($email_template_data['template_type'] != 'none') {

							$sendMailArray = array(

								'sendTo' =>  $sendMailTo,

								'subject' => $email_template_data['email_subject'],

								'mailBody' => $email_template_data['test_email_content'],

								'mailHeading' => '',

								'ccc_email' => $email_template_data['ccc_email'],

								'bcc_email' =>  $email_template_data['bcc_email'],

								'reply_to' => $email_template_data['reply_to']

							);

							try {

								$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
							} catch (Exception $e) {
							}
						}
					}

					return json_encode(array("status" => true, 'message' => 'Product Removed Successfully')); // return json

				} else {

					return json_encode(array("status" => false, 'message' => $removeProduct_error[0]['message'])); // return json

				}
			} else {

				return json_encode(array("status" => false, 'message' => $removeProduct_error[0]['message'])); // return json

			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Delete Product Setting has been disabled')); // return json

		}
	}



	//this function used in both manaully billing attempt and contract renewal cron job

	public function getContractProducts($contract_id)
	{

		$get_contract_products = $this->customQuery("SELECT variant_id,contract_line_item_id,recurring_computed_price,subscription_price FROM subscritionOrderContractProductDetails WHERE contract_id = '$contract_id' AND product_contract_status = '1'");

		return $get_contract_products;
	}

	public function updateLineItemPrice($updateLineItemArray, $contract_id)
	{

		$contractDraftid =  $this->getContractDraftId($contract_id);

		$change_after_cycle_update = '0';

		foreach ($updateLineItemArray as $key => $value) {

			$lineItemId = $value['contract_line_item_id'];

			$recurring_computed_price = $value['recurring_computed_price'];

			try {

				$updateContractLineItemPrice = 'mutation {

					subscriptionDraftLineUpdate(

					  draftId: "' . $contractDraftid . '"

					  lineId: "gid://shopify/SubscriptionLine/' . $lineItemId . '"

					  input: { currentPrice: ' . $recurring_computed_price . ' }

					) {

					  lineUpdated {

						id

						currentPrice{

						  amount

						}

					  }

					  userErrors {

						field

						message

						code

					  }

					}

				  }';

				$updateContractLine_execution = $this->graphqlQuery($updateContractLineItemPrice, null, null, null);

				$updateContractLine_execution_error = $updateContractLine_execution['data']['subscriptionDraftLineUpdate']['userErrors'];

				if (!count($updateContractLine_execution_error)) {

					$change_after_cycle_update = '1';
				}
			} catch (Exception $e) {

				return 'error';
			}
		}

		$this->commitContractDraft($contractDraftid);

		return $change_after_cycle_update;
	}



	public function manualBillingAttempt($contractData)
	{

		$billingRenewalDate = $contractData['actualAttemptDate'];

		$whereCondition = array(

			'store_id' => $this->store_id,

			'contract_id' => $contractData['contract_id'],

			'renewal_date' => $billingRenewalDate,

		);

		$check_skip_status = $this->table_row_check('billingAttempts', $whereCondition, 'and');



		// get contract data to check the recurring discount applied or not

		$whereContractCondition = array(

			'contract_id' => $contractData['contract_id']

		);

		$fields = array('plan_type', 'after_cycle_update', 'recurring_discount_value', 'after_cycle', 'contract_status');

		$get_contract_data = $this->single_row_value('subscriptionOrderContract', $fields, $whereContractCondition, 'and', '');

		if ($check_skip_status == 0 && (!empty($get_contract_data)) && $get_contract_data['contract_status'] == 'A') {

			$contract_product_data = $this->getContractProducts($contractData['contract_id']);

			$contract_products = implode(',', array_column($contract_product_data, 'variant_id'));

			$price_array = array_column($contract_product_data, 'recurring_computed_price');

			$purchage_plan_amount = array_sum($price_array); // total amount
			if ($purchage_plan_amount > 0) {
				$purchage_plan_amount = $purchage_plan_amount;
			} else {
				$price_array = array_column($contract_product_data, 'subscription_price');

				$purchage_plan_amount = array_sum($price_array); // total amount
			}
			// echo $purchage_plan_amount;

			$after_cycle_update = '0';

			if ($get_contract_data['after_cycle_update'] != '1' && $get_contract_data['recurring_discount_value'] != 0) {

				$total_contract_orders = $this->totalContractOrders($contractData['contract_id']);

				if ($total_contract_orders >= $get_contract_data['after_cycle']) {

					$after_cycle_update_status = $this->updateLineItemPrice($contract_product_data, $contractData['contract_id']);

					if ($after_cycle_update_status != 'error') {

						$after_cycle_update = $after_cycle_update_status;
					}
				}
			} else {

				$after_cycle_update = '1';
			}

			$billingAttemptResult =  $this->billingAttemptApi($contractData['contract_id']);

			if ($billingAttemptResult != 'error') {

				$currentDate  = date('Y-m-d');

				$created_at = date('Y-m-d H:i:s');

				$contract_id = $contractData['contract_id'];

				$billingAttemptId = $billingAttemptResult['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['id'];

				$billingAttempt_id = substr($billingAttemptId, strrpos($billingAttemptId, '/') + 1);



				$fields = array(

					'contract_id' => $contract_id,

					'store_id' => $this->store_id,

					'billingAttemptId' => $billingAttempt_id,

					'plan_type' => $get_contract_data['plan_type'],

					'billing_attempt_date' => $currentDate,

					'contract_products' => $contract_products,

					'status' => 'Pending',

					'renewal_date' => $billingRenewalDate,

					'updated_at' => gmdate('Y-m-d H:i:s')

				);

				$this->insert_row('billingAttempts', $fields);

				$query = "SELECT appSubscriptionPlanId, planName, store_id FROM storeInstallOffers WHERE store_id = '$this->store_id' AND status = '1' ORDER BY id DESC LIMIT 1";

				// echo "SELECT * FROM membership_plans WHERE store = '$shop' AND plan_status = 'enable' AND LOWER(membership_plan_name) = '$member_plan_name' LIMIT 1";

				$data_storeInstallOffers_query = $this->db->prepare($query);

				$data_storeInstallOffers_query->execute();
				$row_count = $data_storeInstallOffers_query->rowCount();
				if ($row_count > 0) {
					$get_data_storeInstall_offers = $data_storeInstallOffers_query->fetch(PDO::FETCH_ASSOC);
					$plan_name = $get_data_storeInstall_offers['planName'];
					$appSubPlanId = $get_data_storeInstall_offers['appSubscriptionPlanId'];
					if ($appSubPlanId) {
						// $this->appUsageRecordCreate($appSubPlanId, $purchage_plan_amount, $get_data_storeInstall_offers['store_id'], $plan_name);
					}
				}


				// if value is active in general setting table NEHAA

				$billingType = $billingAttemptResult['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['subscriptionContract']['billingPolicy']['interval'];

				$billingPolicyValue = $billingAttemptResult['data']['subscriptionBillingAttemptCreate']['subscriptionBillingAttempt']['subscriptionContract']['billingPolicy']['intervalCount'];

				$TotalIntervalCount = $this->getBillingRenewalDays(strtoupper($billingType), $billingPolicyValue);

				$newRenewalDate = date('Y-m-d', strtotime('+' . $TotalIntervalCount . ' day', strtotime($billingRenewalDate)));



				//update renewal date in subscriptionOrderContract table

				$whereCondition = array(

					'contract_id' => $contract_id,

					'store_id' => $this->store_id

				);

				$fields = array(

					'next_billing_date' => $newRenewalDate,

					'after_cycle_update' => $after_cycle_update,

					'updated_at' => gmdate('Y-m-d H:i:s')

				);

				$update_contract_Renewal = $this->update_row('subscriptionOrderContract', $fields, $whereCondition, 'and');

				return json_encode(array("status" => true, 'message' => 'Billing Attempt Created Successfully')); // return json

			} else {

				return json_encode(array("status" => false, 'message' => 'Error in Creating Billing Attempt')); // return json

			}
		} else {

			return json_encode(array("status" => false, 'message' => "Can't attempt billing")); // return json

		}
	}



	public function getUpcomingFulfillments($contract_id)
	{

		$get_fulfillment_orders = '

			{

				subscriptionContract(id : "gid://shopify/SubscriptionContract/' . $contract_id . '"){

					createdAt

					orders(first : 1, reverse:true){

						edges{

						cursor

							node{

								id

								name

							}

						}

					}

				}

			}';

		$contract_detail = $this->graphqlQuery($get_fulfillment_orders, null, null, null);

		$graphql_order_id = $contract_detail['data']['subscriptionContract']['orders']['edges'][0]['node']['id'];

		$order_name = $contract_detail['data']['subscriptionContract']['orders']['edges'][0]['node']['name'];



		$all_contract_line_items = [];

		$orders_contracts_lineItems = '{

				order(id:"' . $graphql_order_id . '"){

				  id

				  name

					lineItems(first:15){

						edges{

							node{

								id

								contract{

								id

								}

							}

						}

					}

				}

			}';

		$line_items_with_contractId = $this->graphqlQuery($orders_contracts_lineItems, null, null, null);

		foreach ($line_items_with_contractId['data']['order']['lineItems']['edges'] as $key => $value) {

			$contract_id = substr($value['node']['contract']['id'], strrpos($value['node']['contract']['id'], '/') + 1);

			$all_contract_line_items[$contract_id][] = substr($value['node']['id'], strrpos($value['node']['id'], '/') + 1);
		}

		$rest_order_id = substr($graphql_order_id, strrpos($graphql_order_id, '/') + 1);

		$order_upcoming_fulfillments = $this->PostPutApi('https://' . $this->store . '/admin/api/' . $this->SHOPIFY_API_VERSION . '/orders/' . $rest_order_id . '/fulfillment_orders.json?status=scheduled', 'GET', $this->access_token, '');

		return (array('all_contract_line_items' => $all_contract_line_items, 'order_upcoming_fulfillments' => $order_upcoming_fulfillments, 'order_name' => $order_name));
	}



	// get contract orders

	public function getContractOrders($contractId, $cursor)
	{

		if ($cursor == '') {

			$get_orders_after = '';
		} else {

			$get_orders_after = 'after : "' . $cursor . '"';
		}

		$getSubscriptionData = 'query {

				subscriptionContract(id : "gid://shopify/SubscriptionContract/' . $contractId . '"){

					createdAt

					orders(first : 1, reverse:true){

						edges{

						cursor

							node{

								id

								name

								fulfillmentOrders(first : 20 query: "status:SCHEDULED"){

									edges{

										node{

											id

											status

											fulfillAt

										}

									}

								}

							}

						}

					}

				}

			}';

		try {

			$getContractOrders = $this->graphqlQuery($getSubscriptionData, null, null, null);

			$fulfillments_array  = $getContractOrders['data']['subscriptionContract']['orders']['edges'][0]['node'];

			return $fulfillments_array;
		} catch (Exception $e) {

			print_r($e->getMessage());
		}
	}



	//get last fulfillment data

	public function getLastFulfillment($orderId)
	{

		$getLastFulfillments = 'query {

					order(id:"' . $orderId . '"){

								id

								name

					fulfillmentOrders(first : 20 reverse : true){

						edges{

							node{

							id

							status

							fulfillAt

							}

						}

				}

			}

			}';

		$graphQL_getLastFulfillments = $this->graphqlQuery($getLastFulfillments, null, null, null);

		return $graphQL_getLastFulfillments['data']['order']['fulfillmentOrders']['edges'][0]['node']['fulfillAt'];
	}



	//  Reschedule orders

	public function rescheduleOrder($scheduleData)
	{

		if (is_object($scheduleData)) {

			$scheduleData = (array) $scheduleData;
		}

		$check_customer_Setting = $this->getSettingData('customer_settings', 'skip_upcoming_fulfillment');

		if ($check_customer_Setting['skip_upcoming_fulfillment'] == '1' || $scheduleData['rescheduled_by'] == 'Admin') {

			// check if the fulfillment already rescheduled or not

			$whereFulfillmentCondition = array(

				'actual_fulfillment_date' => strtok($scheduleData['actualFulfillmentDate'], 'T'),

				'contract_id' => $scheduleData['contract_id']

			);

			$check_reschedule_entry = $this->table_row_check('reschedule_fulfillment', $whereFulfillmentCondition, 'and');

			$whereContractid = array(

				'contract_id' => $scheduleData['contract_id']

			);

			$check_contract_exist = $this->table_row_check('subscriptionOrderContract', $whereContractid, 'and');

			if ($check_reschedule_entry || (!($check_contract_exist))) {

				echo json_encode(array("status" => false, 'message' => 'Something went wrong'));
			} else {

				$TotalIntervalCount = $this->getBillingRenewalDays(strtoupper($scheduleData['delivery_billing_type']), $scheduleData['deliveryCycle']);

				$whereCondition = array(

					'contract_id' => $scheduleData['contract_id']

				);

				$fields = array('next_billing_date');

				$order_renewal_date = $this->table_row_value('subscriptionOrderContract', $fields, $whereCondition, 'and', '');

				$next_schedule_date = $order_renewal_date[0]['next_billing_date'];



				$updated_renewal_date = date('Y-m-d', strtotime('+' . $TotalIntervalCount . ' day', strtotime($next_schedule_date)));

				try {

					$rescheduleFulfillments = 'mutation {

					fulfillmentOrderReschedule(

					id: "' . $scheduleData['fulfillmentOrderId'] . '",

					fulfillAt: "' . $next_schedule_date . '"

					)

					{

					fulfillmentOrder {

						fulfillAt

					}

					userErrors {

						field

						message

					}

					}

				}';

					$graphQL_rescheduleFulfillments = $this->graphqlQuery($rescheduleFulfillments, null, null, null);

					$rescheduleFulfillments_error = $graphQL_rescheduleFulfillments['data']['fulfillmentOrderReschedule']['userErrors'];

					if (!count($rescheduleFulfillments_error)) {

						//save fulfillment data in db table reschedule_fulfillment

						$whereCondition = array(

							'contract_id' => $scheduleData['contract_id'],

							'fulfillment_orderId' =>  substr($scheduleData['fulfillmentOrderId'], strrpos($scheduleData['fulfillmentOrderId'], '/') + 1),

							'order_id' => substr($scheduleData['order_id'], strrpos($scheduleData['order_id'], '/') + 1)

						);

						$check_entry = $this->table_row_check('reschedule_fulfillment', $whereCondition, 'and');

						if ($check_entry) {

							$fields = array(

								'new_fulfillment_date' => $next_schedule_date,

							);

							$rescheduled_fulfillment =	$this->update_row('reschedule_fulfillment', $fields, $whereCondition, 'and');
						} else {

							$fields = array(

								'store_id' => $this->store_id,

								'contract_id' => $scheduleData['contract_id'],

								'fulfillment_orderId' => substr($scheduleData['fulfillmentOrderId'], strrpos($scheduleData['fulfillmentOrderId'], '/') + 1),

								'order_id' => substr($scheduleData['order_id'], strrpos($scheduleData['order_id'], '/') + 1),

								'order_no' => (int) filter_var($scheduleData['order_no'], FILTER_SANITIZE_NUMBER_INT),

								'actual_fulfillment_date' =>  strtok($scheduleData['actualFulfillmentDate'], 'T'),

								'new_fulfillment_date' => $next_schedule_date

							);

							$this->insert_row('reschedule_fulfillment', $fields);

							$rescheduled_fulfillment =	$this->update_row('reschedule_fulfillment', $fields, $whereCondition, 'and');
						}

						if ($rescheduled_fulfillment) {

							$update_renewal_date = $this->setRenewalDate($scheduleData['contract_id'], $updated_renewal_date);

							if ($update_renewal_date['status'] == false) {

								echo json_encode(array("status" => false, 'message' => 'Fulfillment is Rescheduled but renewal date is not updated'));
							} else {

								$fields = array('shop_timezone');

								$where_store_condition = array('store_id' => $this->store_id);

								$get_store_timezone = $this->table_row_value('store_details', $fields, $where_store_condition, 'and', '');

								$shop_timezone = $get_store_timezone[0]['shop_timezone'];

								$get_fields = 'admin_reschedule_fulfillment,customer_reschedule_fulfillment';

								$reschedule_fulfillment = $this->getSettingData('email_notification_setting', $get_fields);

								$sendMailTo = '';

								if ($reschedule_fulfillment['admin_reschedule_fulfillment'] == '1' && $reschedule_fulfillment['customer_reschedule_fulfillment'] == '1') {

									$sendMailTo = array($scheduleData['customerEmail'], $scheduleData['adminEmail']);
								} else if ($reschedule_fulfillment['admin_reschedule_fulfillment'] != '1' && $reschedule_fulfillment['customer_reschedule_fulfillment'] == '1') {

									$sendMailTo = $scheduleData['customerEmail'];
								} else if ($reschedule_fulfillment['admin_reschedule_fulfillment'] == '1' && $reschedule_fulfillment['customer_reschedule_fulfillment'] != '1') {

									$sendMailTo = $scheduleData['adminEmail'];
								}

								if ($sendMailTo != '') {

									$data = array(

										'template_type' => 'reschedule_fulfillment_template',

										'contract_id' => $scheduleData['contract_id'],

										'contract_details' => $scheduleData['specific_contract_data'][0],

										'contract_product_details' => $scheduleData['contract_product_details'],

										'actual_fulfillment_date' =>   date('d M Y', strtotime(strtok($scheduleData['actualFulfillmentDate'], 'T'))),

										'new_scheduled_date' => $this->getShopTimezoneDate($next_schedule_date, $shop_timezone),

									);

									$email_template_data = $this->email_template($data, 'send_dynamic_email');

									if ($email_template_data['template_type'] != 'none') {

										$sendMailArray = array(

											'sendTo' =>  $sendMailTo,

											'subject' => $email_template_data['email_subject'],

											'mailBody' => $email_template_data['test_email_content'],

											'mailHeading' => '',

											'ccc_email' => $email_template_data['ccc_email'],

											'bcc_email' =>  $email_template_data['bcc_email'],

											// 'from_email' =>  $from_email,

											'reply_to' => $email_template_data['reply_to']

										);

										try {

											$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
										} catch (Exception $e) {

											if ($this->store == 'predictive-search.myshopify.com') {

												echo 'mail is not sent.';
											}
										}
									}
								}
							}
						}

						echo json_encode(array("status" => true, 'message' => 'Fulfillment is Rescheduled'));
					}
				} catch (Exception $e) {

					echo json_encode(array("status" => false, 'message' => $e->getMessage()));
				}
			}
		} else {

			echo json_encode(array("status" => false, 'message' => 'Rescheduled setting has been disabled'));
		}
	}



	public function setRenewalDate($contract_id, $renewal_date)
	{

		$graphQL_rescheduleFulfillments = 'mutation {

				subscriptionContractSetNextBillingDate(

				contractId: "gid://shopify/SubscriptionContract/' . $contract_id . '"

				date: "' . $renewal_date . '"

				) {

				contract {

					nextBillingDate

				}

				userErrors {

					field

					message

				}

				}

			}';

		$graphQL_rescheduleFulfillments_execution = $this->graphqlQuery($graphQL_rescheduleFulfillments, null, null, null);

		$reschedule_error = $graphQL_rescheduleFulfillments_execution['data']['subscriptionContractSetNextBillingDate']['userErrors'];

		if (!count($reschedule_error)) {

			$whereCondition = array(

				'contract_id' => $contract_id

			);

			$fields = array(

				'next_billing_date' => $renewal_date,

				'updated_at' => gmdate('Y-m-d H:i:s')

			);

			$rescheduled_fulfillment =	$this->update_row('subscriptionOrderContract', $fields, $whereCondition, 'and');

			return array("status" => true, 'message' => 'Renewal Date Updated');
		} else {

			return array("status" => false, 'message' => 'Error in updating renewal date');
		}
	}



	// skip contract order

	// skip contract order

	public function skipContractOrder($contractId, $skipOrderDate, $billingPolicy, $delivery_billingType, $customerEmail, $adminEmail, $skippedFrom, $contract_details, $contract_product_details, $plan_type)
	{

		//check if setting changed by the admin for customer contract page

		// print_r($contract_details[0]['name']);
		// die;

		$check_skip_setting = $this->getSettingData('customer_settings', 'skip_upcoming_order');

		if ($skippedFrom == 'Admin' || $check_skip_setting['skip_upcoming_order'] == '1') {

			$where_condition = array(

				'contract_id' => $contractId,

				'store_id' => $this->store_id,

				'renewal_date' => $skipOrderDate,

			);

			$check_attempt_entry =  $this->table_row_check('billingAttempts', $where_condition, 'and');

			if ($check_attempt_entry == 0) {

				$fields = array(

					'contract_id' => $contractId,

					'store_id' => $this->store_id,

					'billing_attempt_date' => date('Y-m-d'),

					'renewal_date' => $skipOrderDate,

					'status' => 'Skip',

					'plan_type' => $plan_type,

					'updated_at' => gmdate('Y-m-d H:i:s')

				);

				$skipOrderData = $this->insert_row('billingAttempts', $fields);

				$next_billing_date = date('Y-m-d', strtotime('+' . $billingPolicy . ' ' . $delivery_billingType, strtotime($skipOrderDate)));

				$whereCondition = array(

					'contract_id' => $contractId

				);

				$fields = array(

					'next_billing_date'	=> $next_billing_date,

					'updated_at' => gmdate('Y-m-d H:i:s')

				);

				$update_next_billing_date = $this->update_row('subscriptionOrderContract', $fields, $whereCondition, 'and');



				if ($skipOrderData && $update_next_billing_date) {

					//send mail to customer and admin

					//send mail to customer and admin

					$get_fields = 'customer_skip_order,admin_skip_order';

					$skip_order_mail = $this->getSettingData('email_notification_setting', $get_fields);

					$send_mail_to = '';

					if ($skip_order_mail['customer_skip_order'] == '1' && $skip_order_mail['admin_skip_order'] == '1') {

						$send_mail_to = array($customerEmail, $adminEmail);
					} else if ($skip_order_mail['customer_skip_order'] != '1' && $skip_order_mail['admin_skip_order'] == '1') {

						$send_mail_to = $adminEmail;
					} else if ($skip_order_mail['customer_skip_order'] == '1' && $skip_order_mail['admin_skip_order'] != '1') {

						$send_mail_to = $customerEmail;
					}

					if ($send_mail_to != '') {

						$data = array(

							'template_type' => 'skip_order_template',

							'contract_id' => $contractId,

							'contract_details' => $contract_details[0],

							'contract_product_details' => $contract_product_details,

							'skipped_order_date' => $skipOrderDate

						);
						if ($plan_type == 'membership') {
							// echo "hwrew";
							// die;
							$emailupcoming_date = new DateTime($skipOrderDate);
							$emailupcoming_date = $emailupcoming_date->format('F d, Y');
							$customer_email = $contract_details[0]['email'];
							$customer_name = $contract_details[0]['name'];
							$plan_name = $contract_product_details[0]['product_name'] . '-' . $contract_product_details[0]['variant_name'];
							$template_data = [];
							$template_data['template_name'] = 'membership_skip_memberships';
							$template_data['email_type'] = '';
							$template_data['shop_name'] = $this->store;
							$template_data['discount_coupon_content'] = true;
							$template_data['free_shipping_coupon_content'] = true;
							$template_data['free_signup_product_content'] = true;
							$template_data['free_gift_uponsignupSelectedDays'] = true;
							$email_template = $this->membershipAllEmailTemplates($template_data);
							$body = $email_template['email_template_html'];
							$body = str_replace(array("{{customer_name}}", "{{plan_name}}", "{{skip_date}}", "{{store_name}}"), array($customer_name, $plan_name, $emailupcoming_date, $this->store), $body);
							$data = array(

								'email_template_array' =>
								array(
									'subject' => $email_template['subject'],
									'send_to_email' => $send_mail_to,
									'template_name' => 'membership_skip_membership',
									'emailBody' => $body,
									'reply_to' => $email_template['reply_to'],
									'bcc_email' => $email_template['bcc_email'],
									'ccc_email' => $email_template['ccc_email'],
								)

							);
							// print_r($data);
							$this->emailSend($data);
						} else {

							$email_template_data = $this->email_template($data, 'send_dynamic_email');
							if ($email_template_data['template_type'] != 'none') {

								$sendMailArray = array(

									'sendTo' =>  $send_mail_to,

									'subject' => $email_template_data['email_subject'],

									'mailBody' => $email_template_data['test_email_content'],

									'mailHeading' => '',

									'ccc_email' => $email_template_data['ccc_email'],

									'bcc_email' =>  $email_template_data['bcc_email'],

									// 'from_email' =>  $from_email,

									'reply_to' => $email_template_data['reply_to']

								);

								try {

									$order_skipped_mail = $this->sendMail($sendMailArray, 'false');
								} catch (Exception $e) {
								}
							}
						}
					}

					return json_encode(array("status" => true, 'message' => 'Order is skipped'));
				} else {
					echo "hetrererere";
					return json_encode(array("status" => false, 'message' => 'something went wrong'));
				}
			} else {

				echo "3456";

				return json_encode(array("status" => false, 'message' => 'something went wrong'));
			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Setting for skip upcoming order has been disabled'));
		}
	}



	public function getContractPaymentToken($contract_id)
	{

		$get_customer_payment_method = '{

			subscriptionContract(id: "gid://shopify/SubscriptionContract/' . trim($contract_id) . '"){



     customerPaymentMethod{

        id

        ... on CustomerPaymentMethod{

          id

          instrument{

            __typename

            ... on CustomerCreditCard{

              brand

              expiryYear

              expiryMonth

			  lastDigits

            }

			... on CustomerShopPayAgreement{

				expiryMonth

				expiryYear

				isRevocable

				maskedNumber

				lastDigits

				name

				inactive

			  }

			  ... on CustomerPaypalBillingAgreement{

				billingAddress{

				  countryCode

				  country

				  province

				}

				paypalAccountEmail

			  }

          }

        }

      }

			}

		}';

		$get_contract_payment_execution =  $this->graphqlQuery($get_customer_payment_method, null, null, null);

		return $get_contract_payment_execution;
	}



	//get customer contract using contract_id

	public function getContractPaymentToken_old($contract_id)
	{

		$get_contract_payment = '{

			subscriptionContract(id: "gid://shopify/SubscriptionContract/' . trim($contract_id) . '"){

			customerPaymentMethod{

				id

			}

			}

		}';

		$get_contract_payment_execution =  $this->graphqlQuery($get_contract_payment, null, null, null);

		return $get_contract_payment_execution;
	}



	public function getCurrentUtcTime()
	{

		return gmdate("Y-m-d H:i:s");
	}





	public function getBillingRenewalDays($billingType, $billingPolicyValue)
	{

		if ($billingType == 'DAY') {

			$TotalIntervalCount =  $billingPolicyValue;
		} else if ($billingType == 'WEEK') {

			$TotalIntervalCount = (7 *  $billingPolicyValue);
		} else if ($billingType == 'MONTH') {

			$TotalIntervalCount = (30 *  $billingPolicyValue);
		} else if ($billingType == 'YEAR') {

			$TotalIntervalCount = (365 *  $billingPolicyValue);
		}

		return $TotalIntervalCount;
	}



	//get active theme id

	public function getActiveTheme()
	{

		$graphQL = "query{

				translatableResources(first:1, resourceType:ONLINE_STORE_THEME ){

				edges{

					node{

					resourceId

					}

				}

				}

			}

			";

		$activeThemeArray = $this->graphqlQuery($graphQL, null, null, null);

		$ThemeGraphqlId = $activeThemeArray["data"]["translatableResources"]["edges"][0]["node"]["resourceId"];

		return substr($ThemeGraphqlId, strrpos($ThemeGraphqlId, "/") + 1);
	}



	//check weather the app is enabled or disabled from the customizer

	public function checkAppStatus()
	{

		$app_status = 'false';

		$themeId = $this->getActiveTheme();

		$get_theme_files = $this->PostPutApi('https://' . $this->store . '/admin/api/' . $this->SHOPIFY_API_VERSION . '/themes/' . $themeId . '/assets.json?asset[key]=config/settings_data.json', 'GET', $this->access_token, '');

		$theme_extension_block = json_decode($get_theme_files['asset']['value']);

		if ($theme_extension_block->current) {

			if ($theme_extension_block->current->blocks) {

				$current_blocks = $theme_extension_block->current->blocks;

				$searchedValue = 'shopify://apps/' . $this->app_name . '/blocks/' . $this->theme_block_name . '/' . $this->app_extension_id;

				foreach ($current_blocks as $app_block) {

					if ($app_block->type == $searchedValue) {

						$app_status =  $app_block->disabled;
					}
				}

				if ($app_status) {

					return 'false';
				} else {

					return 'true';
				}
			} else {

				return 'false';
			}
		} else {

			return 'false';
		}
	}



	public function testMailIssue()
	{

		$dirPath = dirname(dirname(__DIR__));

		$message = "The message..." . "\n";

		$myfile = "txtFiles/testMail.txt";

		file_put_contents($myfile, $message, FILE_APPEND | LOCK_EX);
	}



	// this function using in  subscription_billing_attempt_success webhook

	public function getOrderData($order_id)
	{

		$get_order_number = '{

			   order(id: "gid://shopify/Order/' . $order_id . '"){

				 name

				 subtotalPriceSet{

				   shopMoney{

					 amount

				   }

				 }

			   }

			}';

		$get_order_number_execution = $this->graphqlQuery($get_order_number, null, null, null);

		return $get_order_number_execution;
	}



	// send mail function

	public function sendMail($sendMailArray, $testMode)
	{
		//general mail configuration
		$email_configuration = getDotEnv('EMAIL_CONFIGURATION');
		$email_host = getDotEnv('EMAIL_HOST');
		$username = getDotEnv('EMAIL_USERNAME');
		$password = getDotEnv('EMAIL_PASSWORD');
		$from_email = getDotEnv('FROM_EMAIL');
		$encryption = getDotEnv('ENCRYPTION');
		$port_number = getDotEnv('PORT_NUMBER');


		//For pending mail

		if (array_key_exists("store_id", $sendMailArray)) {

			$store_id = $sendMailArray['store_id'];
		} else {

			$store_id = $this->store_id;
		}

		$store_detail = $this->customQuery("SELECT store_email,shop_name FROM store_details WHERE store_id = '$store_id'");



		if ($testMode == 'true') { // it testing the mail configuration setting

			$email_host = $sendMailArray['dataValues']['email_host'] ?? '';

			$username = $sendMailArray['dataValues']['username'] ?? '';

			$password = $sendMailArray['dataValues']['password'] ?? '';

			$from_email = $sendMailArray['dataValues']['from_email'] ?? '';

			$encryption = $sendMailArray['dataValues']['encryption'] ?? '';

			$port_number = $sendMailArray['dataValues']['port_number'] ?? '';

			$subject = $sendMailArray['dataValues']['subject'] ?? '';

			$sendTo = $sendMailArray['dataValues']['sendTo'] ?? '';

			$mailBody = $sendMailArray['dataValues']['mailBody'] ?? '';

			$mailHeading = '';

			$email_template_body = $mailBody;
		} else { // check if the email configuration setting exist and email enable is checked

			$subject = $sendMailArray['subject'] ?? '';

			$sendTo = $sendMailArray['sendTo'] ?? '';

			$mailBody = $sendMailArray['mailBody'] ?? '';

			$mailHeading = $sendMailArray['mailHeading'] ?? '';

			$whereCondition = array(

				'store_id' => $this->store_id

			);

			$email_configuration_data = $this->table_row_value('email_configuration', 'all', $whereCondition, 'and', '');
     
			if ($email_configuration_data) {

				if ($email_configuration_data[0]['email_enable'] == 'checked') {

					$email_host = $email_configuration_data[0]['email_host'] ?? '';

					$username = $email_configuration_data[0]['username'] ?? '';

					$password = $email_configuration_data[0]['password'] ?? '';

					$from_email = $email_configuration_data[0]['from_email'] ?? '';

					$encryption = $email_configuration_data[0]['encryption'] ?? '';

					$port_number = $email_configuration_data[0]['port_number'] ?? '';

					$email_configuration = 'true';
				}
			}

			if ($mailHeading == '') {

				$email_template_body = $mailBody;
			} else {

				$email_template_body = $this->email_templates($mailBody, $mailHeading);
			}
		}



		$mail =  new PHPMailer\PHPMailer\PHPMailer();

		$mail->IsSMTP();

		$mail->CharSet = "UTF-8";

		$mail->Host = $email_host;

		$mail->SMTPDebug = 1;

		$mail->Port = $port_number; //465 or 587

		$mail->SMTPDebug = false;

		$mail->SMTPSecure = $encryption;

		$mail->SMTPAuth = true;

		$mail->IsHTML(true);



		//Authentication

		$mail->Username = $username;

		$mail->Password = $password;

		if (isset($sendMailArray['ccc_email'])) {

			$mail->addCC($sendMailArray['ccc_email']);
		}

		if (isset($sendMailArray['bcc_email'])) {

			$mail->addBCC($sendMailArray['bcc_email']);
		}


		if (($email_configuration_data != false && ($email_configuration_data[0]['email_enable'] == 'checked')) || $testMode == 'true') {

			$mail->SetFrom($username, $from_email);
		} else {

			$mail->SetFrom($from_email);
		}

		//Set Params

		if (isset($sendMailArray['reply_to'])) {

			$mail->addReplyTo($sendMailArray['reply_to']);
		} else {

			$mail->addReplyTo($store_detail[0]['store_email'], $store_detail[0]['shop_name']);
		}



		if (is_array($sendTo)) {

			$mail->AddAddress($sendTo[0]);

			$mail->AddAddress($sendTo[1]);

			$decrease_counter = 2;
		} else {

			$mail->AddAddress($sendTo);

			$decrease_counter = 1;
		}

		$mail->Subject = $subject;

		$mail->Body = $email_template_body;

		if ($testMode == 'sendInvoice') {

			$mail->addAttachment($sendMailArray['mailAttachment']);
		}

		if (!$mail->Send()) {
			return json_encode(array("status" => false, "message" => $mail->ErrorInfo));
		} else {
			// if($email_configuration == 'false'){

			// 	$whereCondition = array('store_id'=>$store_id);

			// 	$pending_emails = ($pending_emails - $decrease_counter);

			// 	$fields = array(

			//      'pending_emails' => $pending_emails

			// 	);

			// 	$this->update_row('email_counter',$fields,$whereCondition,'and');

			// }

			return json_encode(array("status" => true, "message" => 'Email Sent Successfully'));
		}
	}



	public function email_templates($email_body, $email_heading)
	{

		//get data from email settings table

		$whereStoreCondition = array(

			'store_id' => $this->store_id

		);

		$email_Settings_data = $this->table_row_value('email_settings', 'all', $whereStoreCondition, 'and', '');

		$logo_url = $this->image_folder . 'logo.png';

		$footer_text = 'Thank You';

		$social_link_html = '';



		if (!empty($email_Settings_data)) {

			if ($email_Settings_data[0]['footer_text'] != '') {

				$footer_text = $email_Settings_data[0]['footer_text'];
			}

			if ($email_Settings_data[0]['logo_url'] != '') {

				$logo_url = $email_Settings_data[0]['logo_url'];
			}

			if ($email_Settings_data[0]['enable_social_link'] == '1') {

				if ($email_Settings_data[0]['facebook_link'] != '') {

					$social_link_html .= '<li class="fb_contents" style="list-style: none;margin: 0 10px;display:inline-block;"><a href="' . $email_Settings_data[0]['facebook_link'] . '" target="_blank" style="color:#3c5996;background: #f6f9ff;border: 1px solid #f2f2f2;border-radius: 50%;width: 35px;height: 35px;display: inline-block;text-align: center;line-height:40px;"><img width="15px" height="15px" style="margin-top:6px;" src="https://lh3.googleusercontent.com/-e2x3nBYmZfk/X7dtRSyslDI/AAAAAAAAB0M/KTW6KLFg6eEzbpKaZXcXAvhjiIJoOBJUQCK8BGAsYHg/s64/2020-11-19.png" class="uqvYjb KgFPz" alt="" aria-label="Picture. Press Enter to open it in a new page."></i></a></li>';
				}

				if ($email_Settings_data[0]['twitter_link'] != '') {

					$social_link_html .= '<li class="tw_contents" style="list-style: none;margin: 0 10px;display:inline-block;"><a href="' . $email_Settings_data[0]['twitter_link'] . '" target="_blank" style="color:#58acec;background: #f6f9ff;border: 1px solid #f2f2f2;border-radius: 50%;width: 35px;height: 35px;display: inline-block;text-align: center;line-height:40px;"><img width="15px" height="15px" style="margin-top:6px;" src="https://lh3.googleusercontent.com/-hmg-zXw5RG0/X7dtSbfpWcI/AAAAAAAAB0Q/3twwSmDKpMsqDo8eSKAID8X8k4olFidsACK8BGAsYHg/s64/2020-11-19.png" class="uqvYjb KgFPz" alt="" aria-label="Picture. Press Enter to open it in a new page."></a></li>';
				}

				if ($email_Settings_data[0]['instagram_link'] != '') {

					$social_link_html .= '<li class="ins_contents" style="list-style: none;margin: 0 10px;display:inline-block;"><a href="' . $email_Settings_data[0]['instagram_link'] . '" target="_blank" style="color:#db4d45;background: #f6f9ff;border: 1px solid #f2f2f2;border-radius: 50%;width: 35px;height: 35px;display: inline-block;text-align: center;line-height:40px;"><img width="15px" height="15px" style="margin-top:6px;" src="https://lh3.googleusercontent.com/-DdrdoKZW5dA/X7dtTOJjmZI/AAAAAAAAB0U/jxIyk80qIG81JptOG_c9zHF7MgIrPpGrQCK8BGAsYHg/s64/2020-11-19.png" class="uqvYjb KgFPz" alt="" aria-label="Picture. Press Enter to open it in a new page."></i></a></li>';
				}

				if ($email_Settings_data[0]['linkedin_link'] != '') {

					$social_link_html .= '<li class="linkedin_contents" style="list-style: none;margin: 0 10px; display:inline-block;"><a href="' . $email_Settings_data[0]['linkedin_link'] . '" target="_blank" style="color:#0e7ab7;background: #f6f9ff;border: 1px solid #f2f2f2;border-radius: 50%;width: 35px;height: 35px;display: inline-block;text-align: center;line-height:40px;"><img width="15px"style="margin-top:6px;" height="15px" src="https://lh3.googleusercontent.com/-7Fkye-Jqt-c/X7dtT-C4GFI/AAAAAAAAB0Y/OEf5Fp97T6AO-v8sRbs7cpF-p5l_C_RAACK8BGAsYHg/s64/2020-11-19.png" class="uqvYjb KgFPz" alt="" aria-label="Picture. Press Enter to open it in a new page."></i></a></li>';
				}
			}
		}

		// }

		$email_template_body = '<center class="wrapper" data-link-color="#1188E6" data-body-style="font-size:14px; font-family:inherit; color:#000000; background-color:#f3f3f3;">

		<div class="webkit">

		   <table cellpadding="0" cellspacing="0" border="0" width="100%" class="wrapper" bgcolor="#f3f3f3">

			  <tbody><tr>

				 <td valign="top" bgcolor="#f3f3f3" width="100%" style="padding-top:50px;padding-bottom:50px;">

					<table width="100%" role="content-container" class="outer" align="center" cellpadding="0" cellspacing="0" border="0">

					   <tbody><tr>

						  <td width="100%">

							 <table width="100%" cellpadding="0" cellspacing="0" border="0">

								<tbody><tr>

								   <td>

									  <!--[if mso]>

									  <center>

										 <table>

											<tr>

											   <td width="600">

												  <![endif]-->

												  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px;" align="center">

													 <tbody><tr>

														<td role="modules-container" style="padding:0px 0px 0px 0px; color:#000000; text-align:left;" bgcolor="#FFFFFF" width="100%" align="left">

														   <table class="module preheader preheader-hide" role="module" data-type="preheader" border="0" cellpadding="0" cellspacing="0" width="100%" style="display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">

															  <tbody><tr>

																 <td role="module-content">

																	<p></p>

																 </td>

															  </tr>

														   </tbody></table>

														   <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" role="module" data-type="columns" style="padding:30px 0px 30px 0px;" bgcolor="#f2eefb" data-distribution="1">

															  <tbody>

																 <tr role="module-content">

																	<td height="100%" valign="top">

																	   <table width="600" style="width:600px; border-spacing:0; border-collapse:collapse; margin:0px 0px 0px 0px;" cellpadding="0" cellspacing="0" align="left" border="0" bgcolor="" class="column column-0">

																		  <tbody>

																			 <tr>

																				<td style="padding:0px;margin:0px;border-spacing:0;">

																				   <table class="wrapper" role="module" data-type="image" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;" data-muid="79178f70-3054-4e9f-9b29-edfe3988719e">

																					  <tbody>

																						 <tr>

																							<td style="font-size:6px; line-height:10px; padding:0px 0px 0px 0px;" valign="top" align="center">

																							   <img class="max-width" border="0" style="display:block; color:#000000; text-decoration:none; font-family:Helvetica, arial, sans-serif; font-size:16px;height:63px;width:166px;" width="166" alt="" data-proportionally-constrained="true" data-responsive="false" src="' . $logo_url . '" height="63">

																							</td>

																						 </tr>

																					  </tbody>

																				   </table>

																				</td>

																			 </tr>

																		  </tbody>

																	   </table>

																	</td>

																 </tr>

															  </tbody>

														   </table>

                                                            ' . $email_body . '

														   <div data-role="module-unsubscribe" class="module" role="module" data-type="unsubscribe" style="color:#444444; font-size:12px; line-height:20px; padding:16px 16px 16px 16px; text-align:Center;" data-muid="4e838cf3-9892-4a6d-94d6-170e474d21e5">

															  <p style="font-size:12px; line-height:20px;"><a href="https://' . $this->store . '" target="_blank" class="Unsubscribe--unsubscribePreferences" style="">' . $this->store . '</a></p>

														   </div>

														   <div data-role="module-unsubscribe" class="module" role="module" data-type="unsubscribe" style="color:#444444; font-size:12px; line-height:20px; padding:0px 16px 16px 16px; text-align:Center;" data-muid="4e838cf3-9892-4a6d-94d6-170e474d21e5">

														   <p style="font-size:12px; line-height:20px;">' . $footer_text . '</p>

														</div>

														   <table border="0" cellpadding="0" cellspacing="0" class="module" data-role="module-button" data-type="button" role="module" style="table-layout:fixed;" width="100%" data-muid="de63a5a7-03eb-460a-97c7-d2535151ca0b">

															  <tbody>

																 <tr>

																	<td align="center" bgcolor="" class="outer-td" style="padding:0px 0px 20px 0px;">

																	   <table border="0" cellpadding="0" cellspacing="0" class="wrapper-mobile" style="text-align:center;">

																		  <tbody>

																			 <tr>

																				<td align="center" bgcolor="#f5f8fd" class="inner-td" style="border-radius:6px; font-size:16px; text-align:center; background-color:inherit;">' . $social_link_html . '</td>

																			 </tr>

																		  </tbody>

																	   </table>

																	</td>

																 </tr>

															  </tbody>

														   </table>

														</td>

													 </tr>

												  </tbody></table>

											   </td>

											</tr>

										 </table>

									  </center>

								   </td>

								</tr>

							 </tbody></table>

						  </td>

					   </tr>

					</tbody></table>

				 </td>

			  </tr>

		   </tbody></table>

		</div>

	 </center>';

		return $email_template_body;
	}



	// public function delete_subscription($ids)
	// {

	// 	$sellingPlanGroupId = $ids['subscription_plangroup_id'];

	// 	try {

	// 		$graphQL_sellingPlanGroupDelete = 'mutation {

	// 		sellingPlanGroupDelete(id: "gid://shopify/SellingPlanGroup/' . $sellingPlanGroupId . '") {

	// 		deletedSellingPlanGroupId

	// 			userErrors {

	// 				code

	// 				field

	// 				message

	// 			}

	// 		}

	// 	}';

	// 		$sellingPlanGroupDeleteapi_execution = $this->graphqlQuery($graphQL_sellingPlanGroupDelete, null, null, null);
	// 	} catch (Exception $e) {

	// 		return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json

	// 	}

	// 	$sellingPlanGroupDeleteapi_error = $sellingPlanGroupDeleteapi_execution['data']['sellingPlanGroupDelete']['userErrors'];

	// 	if (!count($sellingPlanGroupDeleteapi_error)) {

	// 		$response = json_decode($this->delete_row($_REQUEST['table'], $_REQUEST['wherecondition'], $_REQUEST['wheremode']));

	// 		if ($response->status == true) {

	// 			return json_encode(array('status' => true, 'message' => "Deleted Successfully")); // return json

	// 		} else {

	// 			return json_encode(array('status' => false, 'message' => "Database delete query error")); // return json

	// 		}
	// 	} else {

	// 		if ($sellingPlanGroupDeleteapi_error[0]['code'] == 'GROUP_DOES_NOT_EXIST') {

	// 			try {

	// 				$response = json_decode($this->delete_row($_REQUEST['table'], $_REQUEST['wherecondition'], $_REQUEST['wheremode']));

	// 				if ($response->status == true) {

	// 					return json_encode(array('status' => true, 'message' => "Deleted Successfully")); // return json

	// 				} else {

	// 					return json_encode(array("status" => false, 'error' => $sellingPlanGroupDeleteapi_error, 'message' => '404')); // return json

	// 				}
	// 			} catch (Exception $e) {

	// 				return json_encode(array("status" => false, 'error' => $sellingPlanGroupDeleteapi_error, 'message' => '404'));
	// 			}
	// 		} else {

	// 			return json_encode(array("status" => false, 'error' => $sellingPlanGroupDeleteapi_error, 'message' => "Delete mutation error")); // return json

	// 		}
	// 	}
	// }

	public function delete_subscription($ids)
	{

		$sellingPlanGroupId = $ids['subscription_plangroup_id'];

		try {

			$graphQL_sellingPlanGroupDelete = 'mutation sellingPlanGroupDelete($id: ID!) {
				sellingPlanGroupDelete(id: $id) {
					deletedSellingPlanGroupId
					userErrors {
					field
					message
					}
				}
				}';
			$variables = array('id' => "gid://shopify/SellingPlanGroup/$sellingPlanGroupId");
			$sellingPlanGroupDeleteapi_execution = $this->graphqlQuery($graphQL_sellingPlanGroupDelete, null, null, $variables);
		} catch (Exception $e) {

			return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json

		}

		$sellingPlanGroupDeleteapi_error = $sellingPlanGroupDeleteapi_execution['data']['sellingPlanGroupDelete']['userErrors'];

		if (!count($sellingPlanGroupDeleteapi_error)) {

			$response = json_decode($this->delete_row($_REQUEST['table'], $_REQUEST['wherecondition'], $_REQUEST['wheremode']));

			if ($response->status == true) {

				return json_encode(array('status' => true, 'message' => "Deleted Successfully")); // return json

			} else {

				return json_encode(array('status' => false, 'message' => "Database delete query error")); // return json

			}
		} else {

			if ($sellingPlanGroupDeleteapi_error[0]['code'] == 'GROUP_DOES_NOT_EXIST') {

				try {

					$response = json_decode($this->delete_row($_REQUEST['table'], $_REQUEST['wherecondition'], $_REQUEST['wheremode']));

					if ($response->status == true) {

						return json_encode(array('status' => true, 'message' => "Deleted Successfully")); // return json

					} else {

						return json_encode(array("status" => false, 'error' => $sellingPlanGroupDeleteapi_error, 'message' => '404')); // return json

					}
				} catch (Exception $e) {

					return json_encode(array("status" => false, 'error' => $sellingPlanGroupDeleteapi_error, 'message' => '404'));
				}
			} else {

				return json_encode(array("status" => false, 'error' => $sellingPlanGroupDeleteapi_error, 'message' => "Delete mutation error")); // return json

			}
		}
	}



	// public function getSettingData($tableName,$column_name){

	// 	$whereStoreCondition = array(

	// 		'store_id' => $this->store_id

	// 	);

	// 	$fields = array($column_name);

	// 	$get_setting_data = $this->table_row_value($tableName,$fields,$whereStoreCondition,'and','');

	// 	return $get_setting_data[0][$column_name];

	// }

	public function getSettingData($tableName, $column_name)
	{

		$whereStoreCondition = array(

			'store_id' => $this->store_id

		);

		$fields = array($column_name);

		$get_setting_data = $this->single_row_value($tableName, $fields, $whereStoreCondition, 'and', '');

		// return $get_setting_data[0][$column_name];

		return $get_setting_data;
	}





	public function PostPutApi($url, $action, $access_token, $arrayfield)

	{

		$curl = curl_init();

		curl_setopt_array($curl, array(

			CURLOPT_URL => $url,

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_TIMEOUT => 30,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => $action,

			CURLOPT_POSTFIELDS => $arrayfield,

			CURLOPT_HTTPHEADER => array(

				"cache-control: no-cache",

				"content-type: application/json",

				"x-shopify-access-token:{$access_token}"

			),

		));



		$response = curl_exec($curl);

		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {

			echo "cURL Error #:" . $err;
		} else {

			return json_decode($response, true);
		}
	}



	public function getShopTimezoneDateFormat($date, $shop_timezone)
	{

		$dt = new DateTime($date);

		$tz = new DateTimeZone($shop_timezone); // or whatever zone you're after

		$dt->setTimezone($tz);

		$dateTime = $dt->format('Y-m-d');

		return $dateTime; //use in analytics

	}



	public function getShopTimezoneDate($date, $shop_timezone)
	{

		$dt = new DateTime($date);

		$tz = new DateTimeZone($shop_timezone); // or whatever zone you're after

		$dt->setTimezone($tz);

		$dateTime = $dt->format('Y-m-d H:i:s');

		$shopify_date =  date("d M Y", strtotime($dateTime));

		return $shopify_date;
	}



	//   bone custom work function     



	public function getReplaceAllProducts($newProductObj, $oldData, $all_contractIds, $contract_details)

	{

		// print_r($contract_details);

		$added_by = $deleted_from = 'Admin';

		$check_customer_setting = $this->getSettingData('customer_settings', 'add_subscription_product');

		$all_new_contract_products = [];

		$error_Count = 0;

		if ($check_customer_setting['add_subscription_product'] == '1' || $added_by == 'Admin') {

			foreach ($all_contractIds as $c_id) {



				$draft_id = $this->getContractDraftId($c_id);

				$error_Count = 0;



				$value = $newProductObj[0];

				// foreach ($newProductObj as $value) {

				$whereCondition = ['contract_id' => $c_id];

				$get_contract_data = $this->single_row_value('subscriptionOrderContract', ['discount_type', 'discount_value', 'recurring_discount_type', 'recurring_discount_value', 'selling_plan_id', 'after_cycle', 'billing_policy_value', 'delivery_policy_value', 'after_cycle_update'], $whereCondition, 'and', '');



				$price = $value['price'] * ($get_contract_data['billing_policy_value'] / $get_contract_data['delivery_policy_value']);

				$computedPrice = 0;



				if ($get_contract_data['discount_value'] != 0 || $get_contract_data['recurring_discount_value'] != 0) {

					if ($get_contract_data['discount_type'] == 'A') {

						$price = max(0, $price - $get_contract_data['discount_value']);
					} else if ($get_contract_data['discount_type'] == 'P') {

						$price = $price - ($price * ($get_contract_data['discount_value'] / 100));
					}



					if ($get_contract_data['recurring_discount_value'] != 0) {

						if ($get_contract_data['recurring_discount_type'] == 'A') {

							$adjustmentType = 'FIXED_AMOUNT';

							$fixedorpercntValue = "fixedValue:" . $get_contract_data['recurring_discount_value'];

							$computedPrice = max(0, $value['price'] - $get_contract_data['recurring_discount_value']);
						} else {

							$adjustmentType = 'PERCENTAGE';

							$fixedorpercntValue = "percentage:" . $get_contract_data['recurring_discount_value'];

							$computedPrice = $value['price'] - ($value['price'] * ($get_contract_data['recurring_discount_value'] / 100));
						}

						$cycleDiscount = 'cycleDiscounts : {

													 adjustmentType: ' . $adjustmentType . ',

													 adjustmentValue:  {

														 ' . $fixedorpercntValue . '

													 },

													 afterCycle: ' . $get_contract_data['after_cycle'] . ',

													 computedPrice:' . $computedPrice . '

												 }';

						$pricingPolicy = 'pricingPolicy :{

													 basePrice : ' . $price . ',

													 ' . $cycleDiscount . '

												 }';

						if ($get_contract_data['after_cycle_update'] == '1') {

							$price = $computedPrice;
						}
					} else {

						$pricingPolicy = '';
					}
				} else {

					$pricingPolicy = '';
				}



				try {

					$addNewLineItem = 'mutation{

												 subscriptionDraftLineAdd(

													 draftId: "' . $draft_id . '",

													 input: {

														 productVariantId: "gid://shopify/ProductVariant/' . $value['variant_id'] . '",

														 quantity: ' . $value['quantity'] . ',

														 currentPrice: ' . number_format((float)$price, 2, '.', '') . ',

														 ' . $pricingPolicy . '

													 }

												 ) {

													 lineAdded {

														 id

													 },

													 userErrors {

														 field

														 message

													 }

												 }

											 }';



					$addNewLineItem_execution = $this->graphqlQuery($addNewLineItem, null, null, null);

					$addNewLineItem_error = $addNewLineItem_execution['data']['subscriptionDraftLineAdd']['userErrors'];
				} catch (Exception $e) {

					return json_encode(["status" => false, 'message' => 'Something went wrong']);
				}

				// print_r($addNewLineItem_error);



				try {

					if (!count($addNewLineItem_error)) {

						$AddLineItemId = substr($addNewLineItem_execution['data']['subscriptionDraftLineAdd']['lineAdded']['id'], strrpos($addNewLineItem_execution['data']['subscriptionDraftLineAdd']['lineAdded']['id'], '/') + 1);

						$fields = [

							"store_id" => $this->store_id,

							"contract_id" => $c_id,

							"product_id" => $value['product_id'],

							"variant_id" => $value['variant_id'],

							"product_name" => $value['product_title'],

							"variant_name" => str_replace('"', '', $value['variant_title']),

							"subscription_price" => number_format((float)$price, 2, '.', ''),

							'quantity' => $value['quantity'],

							"contract_line_item_id" => $AddLineItemId,

							"recurring_computed_price" => number_format((float)$computedPrice, 2, '.', ''),

							"variant_image" => $value['image'],

							"variant_sku" => $value['sku'],

						];

						$insert_row = $this->insert_row('subscritionOrderContractProductDetails', $fields);



						array_push($all_new_contract_products, $fields);
					} else {

						$addNewLineItem_execution_error = $addNewLineItem_execution['data']['subscriptionDraftLineAdd']['userErrors'];

						echo 'Error in --> $addNewLineItem_execution_error';

						$error_Count = 1;
					}
				} catch (Exception $e) {

					echo $e->getMessage();

					return json_encode(["status" => false, 'message' => 'Something went wrong']);
				}





				$oldDataByContactId = $oldData[$c_id];

				foreach ($oldDataByContactId as $data) {

					$lineId = $data['contract_line_item_id'];

					$oldProductsdelete = $this->bonneRemoveSubscriptionProduct($draft_id, $lineId, $deleted_from);
				}

				$updateNextBillingDate = $this->updateNextBillingDate($draft_id);

				$newProductsAdded = $this->bonneCommitContractDraft($draft_id);

				$contract_fields = array(

					'next_billing_date' => $this->boneCycleCustomDate(),

				);

				$whereContractCondition = array(

					'contract_id' => $c_id

				);

				$this->update_row('subscriptionOrderContract', $contract_fields, $whereContractCondition, 'and');

				if ($newProductsAdded == 'Success') {

					$send_mail_to = '';

					$product_added_mail = $this->getSettingData('email_notification_setting', 'customer_product_added,admin_product_added');

					// print_r($product_added_mail,'1');

					if ($product_added_mail['customer_product_added'] == '1' && $product_added_mail['admin_product_added'] == '1') {

						$send_mail_to = array($contract_details[$c_id][0]['email'], $contract_details[$c_id][0]['store_email']);
					} else if ($product_added_mail['customer_product_added'] == '1' && $product_added_mail['admin_product_added'] != '1') {

						$send_mail_to = $contract_details[$c_id][0]['email'];
					} else if ($product_added_mail['customer_product_added'] != '1' && $product_added_mail['admin_product_added'] == '1') {

						$send_mail_to = $contract_details[$c_id][0]['store_email'];
					}

					if ($send_mail_to != '') {

						$data = array(

							'template_type' => 'product_added_template',

							'contract_id' => $c_id,

							'contract_details' => $contract_details[$c_id][0],

							'contract_product_details' => '',

							'new_added_products' => $all_new_contract_products

						);

						$email_template_data = $this->email_template($data, 'send_dynamic_email');



						// just for testing........



						// $send_mail_to = array('kawal13@yopmail.com', $contract_details[$c_id][0]['store_email']);



						////////////////////////////





						if ($email_template_data['template_type'] != 'none') {

							$sendMailArray = array(

								'sendTo' =>  $send_mail_to,

								'subject' => $email_template_data['email_subject'],

								'mailBody' => $email_template_data['test_email_content'],

								'mailHeading' => '',

								'ccc_email' => $email_template_data['ccc_email'],

								'bcc_email' =>  $email_template_data['bcc_email'],

								// 'from_email' =>  $from_email,

								'reply_to' => $email_template_data['reply_to']

							);

							try {

								// print_r($sendMailArray);

								$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');

								// echo 'hello1<br>';

								// print_r($contract_deleted_mail);

							} catch (Exception $e) {
							}
						}
					}
				}
			}



			if ($newProductsAdded == 'Success') {

				return json_encode(["status" => true, 'message' => 'Product(s) Replace Successfully']);
			} else {

				return json_encode(["status" => false, 'message' => 'Something went wrong']);
			}
		} else {

			return json_encode(["status" => false, 'message' => 'Add Product Setting has been disabled']);
		}
	}

	public function bonneRemoveSubscriptionProduct($contract_draftId, $lineId, $deleted_from)

	{

		// Check if customer can delete product or not

		$check_customer_setting = $this->getSettingData('customer_settings', 'delete_product');



		if ($check_customer_setting['delete_product'] == '1' || $deleted_from == 'Admin') {



			// $contract_draftId = $this->getContractDraftId($contractId);

			try {

				$removeProduct = 'mutation {

                subscriptionDraftLineRemove(

                    draftId: "' . $contract_draftId . '"

                    lineId: "gid://shopify/SubscriptionLine/' . $lineId . '"

                ) {

                    lineRemoved {

                        id

                    }

                    draft {

                        id

                    }

                    userErrors {

                        field

                        message

                        code

                    }

                }

            }';



				$removeProduct_execution = $this->graphqlQuery($removeProduct, null, null, null);

				$removeProduct_error = $removeProduct_execution['data']['subscriptionDraftLineRemove']['userErrors'];

				// $commitContractChanges = $this->bonneCommitContractDraft($contract_draftId);

				$fields = array(

					'product_contract_status' => '0'

				);

				$whereCondition = array(

					'contract_line_item_id' => $lineId

				);

				$this->update_row('subscritionOrderContractProductDetails', $fields, $whereCondition, 'and');
			} catch (Exception $e) {

				return json_encode(["status" => false, 'error' => $e->getMessage(), 'message' => 'Product Removed error']);
			}



			if (!count($removeProduct_error)) {
			} else {

				// return json_encode(["status" => false, 'message' => $removeProduct_error[0]['message']]);

			}
		} else {

			return json_encode(["status" => false, 'message' => 'Delete Product Setting has been disabled']);
		}
	}

	public function bonneCommitContractDraft($subscription_draft_contract_id)

	{

		try {

			// Construct GraphQL mutation for committing contract draft

			$updateContractStatus = 'mutation {

				subscriptionDraftCommit(draftId: "' . $subscription_draft_contract_id . '") {

					contract {

						id

						status

						lines(first:50) {

							edges {

								node {

									id

									quantity

									title

									productId

									variantId

									variantTitle

									currentPrice {

										amount

									}

									discountAllocations {

										discount {

											__typename

										}

									}

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



			// Execute GraphQL mutation

			$commitContractStatus_execution = $this->graphqlQuery($updateContractStatus, null, null, null);



			// Check for GraphQL user errors

			$commitContractStatus_execution_errors = $commitContractStatus_execution['data']['subscriptionDraftCommit']['userErrors'];



			if (!count($commitContractStatus_execution_errors)) {

				return 'Success';
			} else {

				// Errors occurred, handle appropriately

				return 'Error in this variable $commitContractStatus_execution_errors';
			}
		} catch (Exception $e) {

			return 'bonneCommitContractDraft function Error ----->' . $e->getMessage();
		}
	}

	public function updateNextBillingDate($draft_id)
	{

		try {

			$next_billing_date = $this->boneCycleCustomDate();

			$input = [

				"nextBillingDate" => $next_billing_date,

			];



			$updateNextBilling = 'mutation($draftId: ID!, $input: SubscriptionDraftInput!) {

				subscriptionDraftUpdate(draftId: $draftId, input: $input) {

					draft {

						id

						nextBillingDate

					}

					userErrors {

						field

						message

					}

				}

			}';



			$variables = [

				'draftId' => $draft_id,

				'input' => $input

			];



			$updateNextBilling_execution = $this->graphqlQuery($updateNextBilling, null, null, $variables);

			$updateNextBilling_error = $updateNextBilling_execution['data']['subscriptionDraftUpdate']['userErrors'];
		} catch (Exception $e) {

			return json_encode(["status" => false, 'message' => 'Something went wrong']);
		}
	}



	function boneCycleCustomDate()
	{

		$starting_date = date('Y-m-d');

		$date = new DateTime($starting_date);

		if ($date->format('d') >= 20) {

			$date->modify('first day of next month');
		}

		$date->setDate($date->format('Y'), $date->format('m'), 20);

		return $date->format('Y-m-d');
	}
	// membership functions start

	public function memberPlanNameValidation($data)
	{
		// print_r($data);
		$shop = $data['store'];
		$member_plan_name = $data['member_plan_name'];

		$query = "SELECT * FROM membership_plans WHERE store = '$shop' AND plan_status = 'enable' AND LOWER(membership_plan_name) = '$member_plan_name' LIMIT 1";
		// echo "SELECT * FROM membership_plans WHERE store = '$shop' AND plan_status = 'enable' AND LOWER(membership_plan_name) = '$member_plan_name' LIMIT 1";

		$query = $this->db->prepare($query);

		$query->execute();

		$row_count = $query->rowCount();
		if ($row_count > 0) {
			$result = array('isError' => true, 'message' => "Plan name with same name already exists");
			return json_encode($result);
		} else {
			$result = array('isError' => false);
			return json_encode($result);
		}
		// return integer
		// $this->table_row_check('membership_plans',$where,'');
		// $existing_plan = DB::table('membership_plans')->where('store', $shop)->where('plan_status','enable')->where( DB::raw('LOWER(membership_plan_name)'), strtolower($member_plan_name))->first();
		// if ($existing_plan) {
		//     $result = array('isError' => true, 'message' => "Plan name with same name already exists");
		//     return json_encode($result);
		// } else {
		//     $result = array('isError' => false);
		//     return json_encode($result);
		// }x
	}
	public function checkCustomerTag($data)
	{

		$store = $data['store'];
		$ajax_data = json_decode($data['data']);
		$customer_tag = $ajax_data->customer_tag;
		$whereCondition = array(

			"store" => $store,
			'unique_handle' => $customer_tag
		);
		$existing_tags = $this->table_row_check('membership_plan_groups', $whereCondition, 'and');
		// $existing_tags = DB::table('membership_plan_groups')->where('store', $store)->where('unique_handle', $customer_tag)->first();
		if ($existing_tags) {
			return json_encode(['isError' => true, 'message' => 'Membership tag already exists']);
		} else {
			return json_encode(['isError' => false, 'message' => 'Membership tag not exists']);
		}
	}

	// public function create_custom_product($product_data)
	// {
	// 	// echo "here";
	// 	// die;
	// 	$productOptions = [];
	// 	$variants = [];

	// 	foreach ($product_data['memberPlan_data'] as $key => $val) {
	// 		$tierName = $val['member_plan_tier_name'];
	// 		$price = $val['member_plan_options'][0]['option_price'];
	// 		$sku = "sd-membership-plan:" . $tierName;

	// 		$productOptions[] = "{ name: \"{$tierName}\" }";
	// 		$variants[] = "{
	// 		optionValues: [
	// 			{ optionName: \"Title\", name: \"{$tierName}\" }
	// 		],
	// 		price: {$price},
	// 		sku: \"{$sku}\",
	// 		taxable: false
	// 		}";
	// 	}
	// 	$productOptionsValues = implode(", ", $productOptions);
	// 	$variantsString = implode(", ", $variants);
	// 	$originalSource = !empty($product_data['member_product_image']) ? $product_data['member_product_image'] : '';
	// 	$fileField = '';
	// 	if (!empty($originalSource)) {
	// 		$fileField = ", files: { originalSource: \"{$originalSource}\" }";
	// 	}
	// 	$product_description = $product_data['member_product_description'];
	// 	$memberPlan_name = $product_data['memberPlan_name'];
	// 	$member_product_url = $product_data['member_product_url'];
	// 	try {
	// 		$query = <<<GRAPHQL
	// 		mutation createProductSet {
	// 		productSet(
	// 			synchronous: true,
	// 			input: {
				
	// 			title: "{$memberPlan_name}",
	// 			descriptionHtml: "{$product_description}",
	// 			vendor: "Phoenix Membership Product",
	// 			productType: "sd-membership",
	// 			status: ACTIVE,
	// 			handle: "{$member_product_url}",
	// 			{$fileField}
	// 			productOptions: [
	// 			{
	// 				name: "Title",
	// 				position: 1,
	// 				values: [
	// 				{$productOptionsValues}
	// 				]
	// 			}
	// 			],
	// 			variants: [
	// 			{$variantsString}
	// 			]
	// 		}) {
	// 			product {
	// 			id
	// 			handle
	// 			title
	// 			media(first: 10) {
	// 				edges {
	// 				node {
	// 					preview {
	// 					image {
	// 						url
	// 					}
	// 					}
	// 				}
	// 				}
	// 			}
	// 			variants(first: 250) {
	// 				nodes {
	// 				id
	// 				price
	// 				title
	// 				image {
	// 					url
	// 				}
	// 				}
	// 			}
	// 			}
	// 			productSetOperation {
	// 			id
	// 			status
	// 			userErrors {
	// 				code
	// 				field
	// 				message
	// 			}
	// 			}
	// 			userErrors {
	// 			code
	// 			field
	// 			message
	// 			}
	// 		}
	// 		}
	// 		GRAPHQL;

	// 		$variantResponse = $this->shopify_graphql_object->GraphQL->post($query);
	// 		if (!empty($variantResponse['data']['productSet'])) {
	// 			$create_product_execution = $variantResponse['data']['productSet']['product'];
	// 			$productId = $variantResponse['data']['productSet']['product']['id'];
	// 			$tesing = <<<GRAPHQL
	// 			query getPublications {
	// 				publications(first: 20) {
	// 					edges {
	// 						node {
	// 							id
	// 							name
	// 						}
	// 					}
	// 				}
	// 			}
	// 			GRAPHQL;

	// 			$df = $this->shopify_graphql_object->GraphQL->post($tesing);
	// 			// print_r($df);
	// 			$onlineStoreIdData = $df['data']['publications']['edges'];
	// 			$dataArray = array_column($onlineStoreIdData, 'node');
	// 			foreach ($dataArray as $onlineStore) {
	// 				if ($onlineStore['name'] == 'Online Store') {
	// 					$onlineStoreId = $onlineStore['id'];
	// 					$active = <<<GRAPHQL
	// 						mutation publishProduct {
	// 						publishablePublish(input: {publicationId: "{$onlineStoreId}"}, id: "{$productId}") {
	// 							userErrors {
	// 								field
	// 								message
	// 							}
	// 							publishable {
	// 								... on Product {
	// 									id
	// 								}
	// 							}
	// 						}
	// 					}
	// 					GRAPHQL;
	// 					$sds = $this->shopify_graphql_object->GraphQL->post($active);
	// 					return $create_product_execution;
	// 				}
	// 			}
	// 		}
	// 	} catch (Exception $e) {
	// 		return $e->getMessage();
	// 	}
	// }

	public function create_custom_product($product_data)
	{

		// echo "here";
		// die;
		$productOptions = [];
		$variants = [];

		foreach ($product_data['memberPlan_data'] as $key => $val) {
			$tierName = $val['member_plan_tier_name'];
			$price = $val['member_plan_options'][0]['option_price'];
			$sku = "sd-membership-plan:" . $tierName;

			// $productOptions[] = "{ name: \"{$tierName}\" }";
			$productOptions[] = ['name' => $tierName];

			$variants[] = [
				'optionValues' => [
					[
						'optionName' => 'Title',
						'name' => $tierName
					]
				],
				'price' => (float) $price,
				'sku' => $sku,
				'taxable' => false
			];
		}

		// $productOptionsValues = implode(", ", $productOptions); // before
		$productOptionsValues = $productOptions; // it's now a valid array of objects

		// $variantsString = implode(", ", $variants);  // before
		$variantsString = $variants;

		$originalSource = !empty($product_data['member_product_image']) ? $product_data['member_product_image'] : '';
		$fileField = '';
		if (!empty($originalSource)) {
			$fileField = ", files: { originalSource: \"{$originalSource}\" }";
		}
		$product_description = $product_data['member_product_description'];
		$memberPlan_name = $product_data['memberPlan_name'];
		$member_product_url = $product_data['member_product_url'] ?? '';

		try {

			$query = <<<GRAPHQL
			mutation createProductSet(\$productSet: ProductSetInput!, \$synchronous: Boolean!) {
				productSet(synchronous: \$synchronous, input: \$productSet) {
					product {
					id
					handle
					title
					media(first: 10) {
						edges {
						node {
							preview {
							image {
								url
							}
							}
						}
						}
					}
					variants(first: 250) {
						nodes {
						id
						price
						title
						image {
							url
						}
						}
					}
					}
					productSetOperation {
					id
					status
					userErrors {
						code
						field
						message
					}
					}
					userErrors {
					code
					field
					message
					}
				}
			}
			GRAPHQL;

			$variables = [
				'synchronous' => true, // or false as needed
				'productSet' => [
					'title' => $memberPlan_name,
					'descriptionHtml' => $product_description,
					'vendor' => 'Phoenix Membership Product',
					'productType' => 'sd-membership',
					'status' => 'ACTIVE',
					'handle' => $member_product_url,
					// Optional featuredMedia, media, etc.
					// Use $fileField as array if applicable

					// Product options
					'productOptions' => [
						[
							'name' => 'Title',
							'position' => 1,
							'values' => $productOptionsValues
						]
					],
					'variants' => $variantsString

				]
			];

			// print_r($query);

			$variantResponse = $this->shopify_graphql_object->GraphQL->post($query, null, null, $variables);


			// print_r($variantResponse);
			if (!empty($variantResponse['data']['productSet'])) {

				$create_product_execution = $variantResponse['data']['productSet']['product'];
				$productId = $variantResponse['data']['productSet']['product']['id'];

				$tesing = 'query {
					publications(first: 20) {
						edges {
							node {
								id
								name
							}
						}
					}
				}';

				$publicationsResponse = $this->shopify_graphql_object->GraphQL->post($tesing);

				// Extract the publication ID for "Online Store"
				$onlineStoreId = null;
				if (!empty($publicationsResponse['data']['publications']['edges'])) {
					foreach ($publicationsResponse['data']['publications']['edges'] as $edge) {
						if ($edge['node']['name'] === 'Online Store') {
							$onlineStoreId = $edge['node']['id'];
							break;
						}
					}
				}


				// 2. Publish Product if Online Store ID found
				if ($onlineStoreId && $productId) {
					$publishMutation = <<<GRAPHQL
					mutation publishProduct {
						publishablePublish(input: {publicationId: "{$onlineStoreId}"}, id: "{$productId}") {
							userErrors {
								field
								message
							}
							publishable {
								... on Product {
									id
								}
							}
						}
					}
				GRAPHQL;

					$publishResponse = $this->shopify_graphql_object->GraphQL->post($publishMutation);

					// Optional: log userErrors from publish
					if (!empty($publishResponse['data']['publishablePublish']['userErrors'])) {

						print_r($publishResponse['data']['publishablePublish']['userErrors']);
					}
				}

				return $create_product_execution;
			}
		} catch (Exception $e) {
			echo "Message: " . $e->getMessage() . "\n";
			echo "File: " . $e->getFile() . "\n";
			echo "Line: " . $e->getLine() . "\n";
			echo "Trace:\n" . $e->getTraceAsString();
			return $e->getMessage();
		}
	}

	// public function get_group_position($store)
	// {
	// 	$whereCondition = array(

	// 		"store" => $store,
	// 	);
	// 	$existing_member_plans = $this->table_row_check('membership_plan_groups', $whereCondition, '');
	// 	if ($existing_member_plans) {
	// 		// It exists

	// 		$data = $this->customQuery("SELECT group_position FROM membership_plan_groups WHERE store = '$store'");
	// 		// print_r($data);
	// 		$get_group_position = $data['group_position'];
	// 		$group_position = $get_group_position + 1;
	// 	} else {
	// 		// It does not exist
	// 		$group_position = 1;
	// 	}
	// 	return $group_position;
	// }

	public function get_group_position($store)
	{
		$whereCondition = array(

			"store" => $store,
		);
		$existing_member_plans = $this->table_row_check('membership_plan_groups', $whereCondition, '');
		if ($existing_member_plans) {
			// It exists

			$data = $this->customQuery("SELECT group_position FROM membership_plan_groups WHERE store = '$store'");
			$positions = array_column($data, 'group_position'); 
			$get_group_position = max($positions);           
			$group_position = $get_group_position + 1;    

		} else {
			// It does not exist
			$group_position = 1;
		}
		return $group_position;
	}

	
	// public function create_member_options_array($member_group_data, $plan_mode, $currencyCode)
	// {
	// 	// echo 'djdj';
	// 	$sellingPlans = "[";
	// 	$selling_plan_id = '';
	// 	$index = 1;
	// 	$mainGroupOption = '';
	// 	// print_r($member_group_data);die;
	// 	$member_offer_trial_period_status = 0;

	// 	// if (array_key_exists('member_offer_trial_period_status', (array)$member_group_data) && $member_group_data->member_offer_trial_period_status) {
	// 	// if (array_key_exists('member_offer_trial_period_status', (array)$member_group_data) && $member_group_data->member_offer_trial_period_status) {
	// 	if (((array_key_exists('offer_trial_status', (array)$member_group_data) || array_key_exists('member_offer_trial_period_status', (array)$member_group_data)) && $member_group_data->offer_trial_status) || $member_group_data->member_offer_trial_period_status) {
	// 		// print_r($member_group_data->member_offer_trial_period_status);
	// 		// print_r($member_group_data);die;
	// 		$member_offer_trial_period_status = 1;
	// 		$discount_type = $member_group_data->discount_type;
	// 		$adjustmentType = $discount_type == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
	// 		$discount_value = $member_group_data->discount_value;
	// 		$adjustmentValueType = $discount_type == 'Percent Off(%)' ? 'percentage' : 'fixedValue';

	// 		$discount_value_after = $member_group_data->discount_value_after;
	// 		$discount_type_after = $member_group_data->discount_type_after;
	// 		$adjustmentTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
	// 		$adjustmentValueTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'percentage' : 'fixedValue';
	// 	}
	// 	$membership_discount_status = 0;
	// 	if ((array_key_exists('discount_status', (array)$member_group_data) && $member_group_data->discount_status)) {
	// 		$membership_discount_status = 1;
	// 		$discount_type = $member_group_data->discount_type;
	// 		$adjustmentType = $discount_type == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
	// 		$discount_value = $member_group_data->discount_value;
	// 		$adjustmentValueType = $discount_type == 'Percent Off(%)' ? 'percentage' : 'fixedValue';
	// 	} else {

	// 		$discount_value_after = 0;
	// 		$adjustmentTypeAfter = 'PERCENTAGE';
	// 		$adjustmentValueTypeAfter = 'percentage';
	// 	}

	// 	$membership_discount_after_status = 0;
	// 	if ((array_key_exists('discount_after_status', (array)$member_group_data) && $member_group_data->discount_after_status)) {

	// 		$membership_discount_after_status = 1;
	// 		$discount_value_after = $member_group_data->discount_value_after;
	// 		$discount_type_after = $member_group_data->discount_type_after;
	// 		$adjustmentTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
	// 		$adjustmentValueTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'percentage' : 'fixedValue';
	// 	}

	// 	// die;
	// 	foreach ($member_group_data->member_plan_options as $key => $plan_option) {
	// 		$plan_option = (object)$plan_option;
	// 		$charge_period = $plan_option->option_charge_value;
	// 		$charge_period_type = $plan_option->option_charge_type;
	// 		$randomNumber = uniqid(mt_rand(), true);
	// 		$options = $charge_period . ' ' . strtolower($charge_period_type);
	// 		$selling_plan_id = '';
	// 		if ($plan_mode == 'update') {
	// 			if ($plan_option->membership_option_id != null) {
	// 				$selling_plan_id = 'id:"gid://shopify/SellingPlan/' . $plan_option->membership_option_id . '"';
	// 			} else {
	// 				continue;
	// 			}
	// 		} else if ($plan_mode == 'create') {
	// 			// echo 'create plan mode<br>';
	// 			if ($plan_option->membership_option_id == null) {
	// 				$selling_plan_id = '';
	// 			} else {
	// 				continue;
	// 			}
	// 		}

	// 		if ($index > 1) {
	// 			$mainGroupOption .= ',' . $options . '-' . $randomNumber;
	// 		} else {
	// 			$mainGroupOption .= $options . '-' . $randomNumber;
	// 		}
	// 		$adjustmentValue = "";
	// 		$pricingpolicies = "pricingPolicies: []";
	// 		$anchors = 'anchors:[]';
	// 		$cutoffDay = '';
	// 		if ($plan_option->option_charge_type != 'DAY') {
	// 			$per_delivery_order_frequency_type = $plan_option->option_charge_type . 'DAY';
	// 		} else {
	// 			$per_delivery_order_frequency_type = $plan_option->option_charge_type;
	// 		}
	// 		$recurring_discount = '';
	// 		// adjustmentType: PERCENTAGE 
	// 		//adjustmentType: FIXED 

	// 		if ($member_offer_trial_period_status == 1 || $membership_discount_status == 1) {
	// 			$pricingpolicies = '
	// 			pricingPolicies: [
    //                 {
    //                     fixed: {
    //                         adjustmentType: ' . $adjustmentType . ' 
    //                         adjustmentValue:
    //                         {
    //                             ' . $adjustmentValueType . ' : ' . $discount_value . '
    //                         }
    //                     }
    //                 }
	// 				{
	// 					recurring: {
	// 						adjustmentType: ' . $adjustmentTypeAfter . '
	// 						adjustmentValue: { ' . $adjustmentValueTypeAfter . ' : ' . $discount_value_after . ' }
	// 						afterCycle :  1

	// 					}}
    //             ]
    //             ';
	// 		} else if ($plan_option->option_price) {
	// 			$pricingpolicies = 'pricingPolicies: [
    //                 {
    //                     fixed: {
    //                         adjustmentType: PRICE
    //                         adjustmentValue:
    //                         {
    //                             fixedValue : ' . $plan_option->option_price . '
    //                         }
    //                     }
    //                 }
    //             ]';
	// 		}

	// 		// $discountAfter = '';
	// 		// if (($membership_discount_after_status == 1 && $membership_discount_status == 1) || $member_offer_trial_period_status == 1) {
	// 		// 	$discountAfter = ',
	// 		// 		{
	// 		// 			recurring: {
	// 		// 				adjustmentType: "' . $adjustmentTypeAfter . '",
	// 		// 				adjustmentValue: { ' . $adjustmentValueTypeAfter . ': ' . $discount_value_after . ' },
	// 		// 				afterCycle: 1
	// 		// 			}
	// 		// 		}';
	// 		// }

	// 		// if ($member_offer_trial_period_status == 1 || $membership_discount_status == 1) {
	// 		// 	$pricingpolicies = '
	// 		// 	pricingPolicies: [
	// 		// 		{
	// 		// 			fixed: {
	// 		// 				adjustmentType: "' . $adjustmentType . '",
	// 		// 				adjustmentValue: {
	// 		// 					' . $adjustmentValueType . ': ' . $discount_value . '
	// 		// 				}
	// 		// 			}
	// 		// 		}' . $discountAfter . '
	// 		// 	]';
	// 		// }


	// 		$maximum_number_cycle_string = '';
	// 		$minimum_number_cycle_string = '';
	// 		$tier_description = '';
	// 		if (!empty($plan_option->max_cycle)) {
	// 			$maximum_number_cycle_string = ', maxCycles : ' . $plan_option->max_cycle;
	// 		}

	// 		if (!empty($plan_option->min_cycle)) {
	// 			$minimum_number_cycle_string = ', minCycles : ' . $plan_option->min_cycle;
	// 		}
	// 		if (!empty($plan_option->description)) {
	// 			$tier_description = 'description: "' . trim($plan_option->description) . '"';
	// 		}
	// 		$frequency_plan_name = $member_group_data->member_plan_tier_name . '-' . ' Billed every ' . $plan_option->option_charge_value . ' ' . strtolower($plan_option->option_charge_type);
	// 		$sellingPlans .= '{
    //                 ' . $selling_plan_id . '
    //                 name: "' . $frequency_plan_name . '"
    //                 options: "' . $options . '"
	// 				position: ' . $key . '
    //                 category: SUBSCRIPTION
    //                 inventoryPolicy:{
    //                 reserve : ON_FULFILLMENT
    //                 }
    //                 billingPolicy: {
    //             recurring: {
    //                 ' . $anchors . '
    //                 interval: ' . $charge_period_type . ', intervalCount: ' . $charge_period . '' . $maximum_number_cycle_string . '' . $minimum_number_cycle_string . ' } }
    //                 ' . $tier_description . '
    //                 deliveryPolicy: { recurring: {
    //                     intent:FULFILLMENT_BEGIN,
    //                     ' . $anchors . '
    //                     preAnchorBehavior : ASAP
    //                 interval: ' . $charge_period_type . ', intervalCount: ' . $charge_period . '} }
    //                 ' . $pricingpolicies . '
    //             }';
	// 		$index++;
	// 	}
	// 	$sellingPlans .= "]";
	// 	$selling_plan_array = array(
	// 		'selling_plans' => $sellingPlans,
	// 		'main_group_option' => trim($mainGroupOption, ","),
	// 	);
	// 	return $selling_plan_array;
	// }

	public function create_member_options_array($member_group_data, $plan_mode, $currencyCode)
	{
		$sellingPlans = [];
		$index = 1;
		$mainGroupOption = '';
		$member_offer_trial_period_status = 0;

		if (
			((array_key_exists('offer_trial_status', (array)$member_group_data) || array_key_exists('member_offer_trial_period_status', (array)$member_group_data)) && ($member_group_data->offer_trial_status ?? false)) ||
			($member_group_data->member_offer_trial_period_status ?? false)
		) {
			$member_offer_trial_period_status = 1;
			$discount_type = $member_group_data->discount_type;
			$adjustmentType = $discount_type == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
			$discount_value = $member_group_data->discount_value;
			$adjustmentValueType = $discount_type == 'Percent Off(%)' ? 'percentage' : 'fixedValue';

			$discount_value_after = $member_group_data->discount_value_after;
			$discount_type_after = $member_group_data->discount_type_after;
			$adjustmentTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
			$adjustmentValueTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'percentage' : 'fixedValue';
		}

		$membership_discount_status = 0;
		if (!empty($member_group_data->discount_status)) {
			$membership_discount_status = 1;
			$discount_type = $member_group_data->discount_type;
			$adjustmentType = $discount_type == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
			$discount_value = $member_group_data->discount_value;
			$adjustmentValueType = $discount_type == 'Percent Off(%)' ? 'percentage' : 'fixedValue';
		} else {
			$discount_value_after = 0;
			$adjustmentTypeAfter = 'PERCENTAGE';
			$adjustmentValueTypeAfter = 'percentage';
		}

		$membership_discount_after_status = 0;
		if (!empty($member_group_data->discount_after_status)) {
			$membership_discount_after_status = 1;
			$discount_value_after = $member_group_data->discount_value_after;
			$discount_type_after = $member_group_data->discount_type_after;
			$adjustmentTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
			$adjustmentValueTypeAfter = $discount_type_after == 'Percent Off(%)' ? 'percentage' : 'fixedValue';
		}

		foreach ($member_group_data->member_plan_options as $key => $plan_option_raw) {
			$plan_option = (object)$plan_option_raw;
			$charge_period = $plan_option->option_charge_value;
			$charge_period_type = $plan_option->option_charge_type;
			$randomNumber = uniqid(mt_rand(), true);
			$options = $charge_period . ' ' . strtolower($charge_period_type);

			if ($plan_mode === 'update' && $plan_option->membership_option_id != null) {
				$selling_plan_id = "gid://shopify/SellingPlan/{$plan_option->membership_option_id}";
			} elseif ($plan_mode === 'create' && $plan_option->membership_option_id == null) {
				$selling_plan_id = null;
			} else {
				continue;
			}

			$mainGroupOption .= ($index > 1 ? ',' : '') . $options . '-' . $randomNumber;

			// Pricing Policies
			$pricingPolicies = [];
			if ($member_offer_trial_period_status === 1 || $membership_discount_status === 1) {
				$pricingPolicies[] = [
					'fixed' => [
						'adjustmentType' => $adjustmentType,
						'adjustmentValue' => [
							$adjustmentValueType => (float)$discount_value
						]
					]
				];
				$pricingPolicies[] = [
					'recurring' => [
						'adjustmentType' => $adjustmentTypeAfter,
						'adjustmentValue' => [
							$adjustmentValueTypeAfter => (float)$discount_value_after
						],
						'afterCycle' => 1
					]
				];
			} elseif (!empty($plan_option->option_price)) {
				$pricingPolicies[] = [
					'fixed' => [
						'adjustmentType' => 'PRICE',
						'adjustmentValue' => [
							'fixedValue' => (float)$plan_option->option_price
						]
					]
				];
			}

			// Billing Policy
			$billingPolicy = [
				'recurring' => [
					'interval' => strtoupper($charge_period_type),
					'intervalCount' => (int)$charge_period,
					'anchors' => [],
				]
			];
			if (!empty($plan_option->min_cycle)) {
				$billingPolicy['recurring']['minCycles'] = (int)$plan_option->min_cycle;
			}
			if (!empty($plan_option->max_cycle)) {
				$billingPolicy['recurring']['maxCycles'] = (int)$plan_option->max_cycle;
			}

			// Delivery Policy
			$deliveryPolicy = [
				'recurring' => [
					'intent' => 'FULFILLMENT_BEGIN',
					'interval' => strtoupper($charge_period_type),
					'intervalCount' => (int)$charge_period,
					'preAnchorBehavior' => 'ASAP',
					'anchors' => [],
				]
			];

			$sellingPlan = [
				'name' => $member_group_data->member_plan_tier_name . ' - Billed every ' . $charge_period . ' ' . strtolower($charge_period_type),
				'options' => $options,
				'position' => $index,
				'category' => 'SUBSCRIPTION',
				'inventoryPolicy' => [
					'reserve' => 'ON_FULFILLMENT'
				],
				'billingPolicy' => $billingPolicy,
				'deliveryPolicy' => $deliveryPolicy,
				'pricingPolicies' => $pricingPolicies,
			];

			if (!empty($plan_option->description)) {
				$sellingPlan['description'] = trim($plan_option->description);
			}

			if (!empty($selling_plan_id)) {
				$sellingPlan['id'] = $selling_plan_id;
			}

			$sellingPlans[] = $sellingPlan;
			$index++;
		}

		return [
			'selling_plans' => $sellingPlans,
			'main_group_option' => trim($mainGroupOption, ','),
		];
	}


	// public function create_member_plan($data)
	// {
	// 	$get_all_data = json_decode($data['data'], true);

	// 	$memberPlanGroups = $get_all_data['memberPlan_data'];
	// 	// print_r($get_all_data);	die;

	// 	$popular_plan = $get_all_data['popular_plan'];

	// 	$group_position = $this->get_group_position($this->store);

	// 	if ($get_all_data['plan_product_type'] == 'custom_product') {

	// 		// print_r($get_all_data);
	// 		$custom_product_data = $this->create_custom_product($get_all_data);

	// 		if ($custom_product_data == 'error') {
	// 			return json_encode(["isError" => true, 'message' => 'mutation execution error']);
	// 		}
	// 	}

	// 	$product_description = $get_all_data['member_product_description'] ?? null;
	// 	$product_url = $get_all_data['member_product_url'] ?? null;

	// 	$subscription_plan_payload = [
	// 		"store" => trim($this->store),
	// 		"membership_plan_name" => trim($get_all_data['memberPlan_name']),
	// 		"membership_product_type" => trim($get_all_data['plan_product_type']),
	// 		"membership_product_url" => trim($custom_product_data['handle']),
	// 		"membership_product_description" => trim($product_description),
	// 	];
	// 	// $save_member_plan = $this->insert_row($tableName,$fields);
	// 	$sql_query_insert = "INSERT INTO `membership_plans` (`store`, `membership_plan_name`, `membership_product_type`, `membership_product_url`, `membership_product_description`) 
    //     VALUES (:store, :membership_plan_name, :membership_product_type, :membership_product_url, :membership_product_description)";


	// 	$stmt = $this->db->prepare($sql_query_insert);
	// 	$stmt->execute([
	// 		':store' => trim($this->store),
	// 		':membership_plan_name' => trim($get_all_data['memberPlan_name']),
	// 		':membership_product_type' => trim($get_all_data['plan_product_type']),
	// 		':membership_product_url' => trim($custom_product_data['handle']),
	// 		':membership_product_description' => trim($product_description),
	// 	]);

	// 	$membership_plan_id = $this->db->lastInsertId();


	// 	$all_member_group_ids_array = [];
	// 	$offer_trial_status = 0;
	// 	foreach ($memberPlanGroups as $key => $val) {
	// 		// echo('llll');
	// 		// print_r($val);
	// 		$sellingPlansToCreate = $this->create_member_options_array((object)$val, 'create', $this->currency_code);
	// 		// print_r($sellingPlansToCreate);
	// 		// die;

	// 		// if (array_key_exists('offer_trial_status', $val) && $val['offer_trial_status'] == 'on') {
	// 		if ((array_key_exists('offer_trial_status', $val) && $val['offer_trial_status'] == 'on') || (array_key_exists('member_offer_trial_period_status', $val) && $val['member_offer_trial_period_status'] == 'on')) {

	// 			// echo "sdjhj";
	// 			$offer_trial_status = 1;
	// 			$trial_period_value = $val['trial_period_value'];
	// 			$trial_period_type = $val['trial_period_type'] ?? 'days';
	// 			$discount_type = $val['discount_type'];
	// 			$discount_value = $val['discount_value'];
	// 			$discount_value_after = $val['discount_value_after'];
	// 			$discount_type_after = $val['discount_type_after'];
	// 			$renew_original_date = $val['renew_on_original_date'] == 'true' ? 'true' : 'false';
	// 		} else {
	// 			$offer_trial_status = 0;
	// 			// $discount_type = '';
	// 			// $discount_value_after = '';
	// 			// $discount_type_after = '';
	// 			$trial_period_value = null;
	// 			$trial_period_type = 'days';
	// 			$renew_original_date = 'false';
	// 		}
	// 		// discount_offer_status
	// 		if (array_key_exists('discount_status', $val) && ($val['discount_status'] == 'on' || $val['discount_status'] == '1')) {
	// 			// echo "sdjhj";die;
	// 			$discount_status = 1;
	// 			$discount_value = $val['discount_value'];
	// 			$discount_type = $val['discount_type'];
	// 		} else {
	// 			$discount_status = 0;
	// 			$discount_type = '';
	// 			$discount_value = 0;
	// 			$discount_after_status = 0;
	// 			$discount_value_after = '';
	// 			$discount_type_after = '';
	// 		}
	// 		// after_discount_offer_status
	// 		if (array_key_exists('discount_after_status', $val) && ($val['discount_after_status'] == 'on' || $val['discount_after_status'] == '1')) {
	// 			// echo "sdjhj";die;
	// 			$discount_after_status = 1;
	// 			$discount_value_after = $val['discount_value_after'];
	// 			$discount_type_after = $val['discount_type_after'];
	// 		} else {
	// 			$discount_after_status = 0;
	// 		}

	// 		$variant_data_array = $custom_product_data['variants'];
	// 		$variant_array_string = $variant_data_array['nodes'][$key]['id'];
	// 		$variant_id = str_replace("gid://shopify/ProductVariant/", "", $variant_data_array['nodes'][$key]['id']);

	// 		$memberPlan_description = !empty($val['group_description']) ? 'description: "' . $val['group_description'] . '"' : '';

	// 		try {

	// 			$graphQL_sellingPlanGroupCreate = 'mutation {
	// 				sellingPlanGroupCreate(
	// 						input: {
	// 							name: "' . $val['member_plan_tier_name'] . '"
	// 							merchantCode: "' . $val['member_plan_tier_name'] . '"
	// 							options: ["' . $sellingPlansToCreate['main_group_option'] . '"]
	// 							position: ' . $group_position . '
	// 							' . $memberPlan_description . '
	// 							sellingPlansToCreate: ' . $sellingPlansToCreate['selling_plans'] . '
	// 							appId:  "4trw27bTrit21member7KePw22asg445r78arewphoenix-membership"
	// 						}
	// 						resources: { productIds: [], productVariantIds: "' . $variant_array_string . '"}
	// 					) {
	// 					sellingPlanGroup {
	// 					id
	// 					appId
	// 					sellingPlans(first: 4){
	// 						edges{
	// 							node{
	// 								id
	// 								name
	// 							}
	// 						}
	// 					}
	// 					}
	// 					userErrors {
	// 					field
	// 					message
	// 					}
	// 				}
	// 			}';

	// 			$sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate);

				

	// 			// print_r($variables);
	// 		    // die;
	// 			// echo $graphQL_sellingPlanGroupCreate;die;
	// 			// print_r($sellingPlanGroupCreateapi_execution);

	// 			$sellingPlanGroupCreateapi_error = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['userErrors'];
	// 		} catch (Exception $e) {
	// 			$sql_query_delete = "DELETE FROM `membership_plans` WHERE `id` = '$membership_plan_id'";
	// 			$this->db->query($sql_query_delete);

	// 			// MembershipPlan::where('id', $membership_plan_id)->delete();
	// 			return json_encode(["isError" => true, 'error' => $e->getMessage(), 'message' => 'mutation execution error']);
	// 		}

	// 		if (!count($sellingPlanGroupCreateapi_error)) {
	// 			$sellingPlanGroupId_complete = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['id'];
	// 			$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupId_complete);

	// 			array_push($all_member_group_ids_array, $sellingPlanGroupId);

	// 			$add_popular_plan = ($popular_plan == trim($val['member_plan_tier_name'])) ? '1' : '0';

	// 			$memberPlan_group_payload = [
	// 				"membership_group_id" => trim($sellingPlanGroupId),
	// 				"store" => trim($this->store),
	// 				"membership_plan_id" => trim($membership_plan_id),
	// 				"group_position" => trim($group_position),
	// 				"popular_plan" => $add_popular_plan,
	// 				"membership_group_name" => trim($val['member_plan_tier_name']),
	// 				"group_description" => trim($val['group_description']),
	// 				"unique_handle" => trim($val['member_plan_tier_handle']),
	// 				"variant_id" => trim($variant_id),
	// 			];

	// 			try {
	// 				$check_sql = "SELECT COUNT(*) FROM membership_plan_groups WHERE membership_group_id = :membership_group_id";
	// 				$stmt = $this->db->prepare($check_sql);
	// 				$stmt->execute([':membership_group_id' => $memberPlan_group_payload['membership_group_id']]);
	// 				$exists = $stmt->fetchColumn();

	// 				if ($exists) {
	// 					// Update
	// 					$update_sql = "UPDATE membership_plan_groups SET 
	// 						store = :store,
	// 						membership_plan_id = :membership_plan_id,
	// 						group_position = :group_position,
	// 						popular_plan = :popular_plan,
	// 						membership_group_name = :membership_group_name,
	// 						group_description = :group_description,
	// 						unique_handle = :unique_handle,
	// 						variant_id = :variant_id
	// 						WHERE membership_group_id = :membership_group_id";

	// 					$stmt = $this->db->prepare($update_sql);
	// 				} else {
	// 					// Insert
	// 					$insert_sql = "INSERT INTO membership_plan_groups (
	// 						membership_group_id, store, membership_plan_id, group_position, popular_plan,
	// 						membership_group_name, group_description, unique_handle, variant_id
	// 					) VALUES (
	// 						:membership_group_id, :store, :membership_plan_id, :group_position, :popular_plan,
	// 						:membership_group_name, :group_description, :unique_handle, :variant_id
	// 					)";

	// 					$stmt = $this->db->prepare($insert_sql);
	// 				}

	// 				$stmt->execute([
	// 					':membership_group_id' => $memberPlan_group_payload['membership_group_id'],
	// 					':store' => $memberPlan_group_payload['store'],
	// 					':membership_plan_id' => $memberPlan_group_payload['membership_plan_id'],
	// 					':group_position' => $memberPlan_group_payload['group_position'],
	// 					':popular_plan' => $memberPlan_group_payload['popular_plan'],
	// 					':membership_group_name' => $memberPlan_group_payload['membership_group_name'],
	// 					':group_description' => $memberPlan_group_payload['group_description'],
	// 					':unique_handle' => $memberPlan_group_payload['unique_handle'],
	// 					':variant_id' => $memberPlan_group_payload['variant_id'],
	// 				]);
	// 			} catch (Exception $e) {
	// 				$sql_query_delete = "DELETE FROM `membership_plans` WHERE `id` = '$membership_plan_id'";
	// 				$this->db->query($sql_query_delete);
	// 				foreach ($all_member_group_ids_array as $val) {
	// 					try {
	// 						$this->delete_memberPlan_group($val);
	// 					} catch (Exception $e) {
	// 					}
	// 				}
	// 				return json_encode(['isError' => true, 'message' => $e->getMessage()]);
	// 			}

	// 			$product_image = $custom_product_data['media']['edges'][0]['node']['preview']['image']['url'] ?? '';

	// 			$memberPlan_products_payload = [
	// 				"store" => trim($this->store),
	// 				"membership_group_id" => trim($sellingPlanGroupId),
	// 				"membership_plan_id" => trim($membership_plan_id),
	// 				"product_id" => trim(str_replace("gid://shopify/Product/", "", $custom_product_data['id'])),
	// 				"variant_id" => trim($variant_id),
	// 				"variant_price" => trim($custom_product_data['variants']['nodes'][$key]['price']),
	// 				"product_name" => trim($custom_product_data['title'] . '-' . $custom_product_data['variants']['nodes'][$key]['title']),
	// 				"image_path" => trim($product_image),
	// 			];

	// 			try {
	// 				$check_sql = "SELECT COUNT(*) FROM membership_product_details WHERE membership_plan_id = :membership_plan_id AND membership_group_id = :membership_group_id AND variant_id = :variant_id";
	// 				$stmt = $this->db->prepare($check_sql);
	// 				$stmt->execute([
	// 					':membership_plan_id' => $memberPlan_products_payload['membership_plan_id'],
	// 					':membership_group_id' => $memberPlan_products_payload['membership_group_id'],
	// 					':variant_id' => $memberPlan_products_payload['variant_id']
	// 				]);
	// 				$exists = $stmt->fetchColumn();

	// 				if ($exists) {
	// 					// Update
	// 					$update_sql = "UPDATE membership_product_details SET 
	// 						store = :store,
	// 						product_id = :product_id,
	// 						product_name = :product_name,
	// 						image_path = :image_path
	// 						WHERE membership_plan_id = :membership_plan_id 
	// 						AND membership_group_id = :membership_group_id 
	// 						AND variant_id = :variant_id";
	// 					$stmt = $this->db->prepare($update_sql);
	// 				} else {
	// 					// Insert
	// 					$insert_sql = "INSERT INTO membership_product_details (
	// 						store, membership_group_id, membership_plan_id, product_id, variant_id,
	// 						variant_price, product_name, image_path
	// 					) VALUES (
	// 						:store, :membership_group_id, :membership_plan_id, :product_id, :variant_id,
	// 						:variant_price, :product_name, :image_path
	// 					)";
	// 					$stmt = $this->db->prepare($insert_sql);
	// 				}

	// 				$stmt->execute([
	// 					':store' => $memberPlan_products_payload['store'],
	// 					':membership_group_id' => $memberPlan_products_payload['membership_group_id'],
	// 					':membership_plan_id' => $memberPlan_products_payload['membership_plan_id'],
	// 					':product_id' => $memberPlan_products_payload['product_id'],
	// 					':variant_id' => $memberPlan_products_payload['variant_id'],
	// 					':variant_price' => $memberPlan_products_payload['variant_price'],
	// 					':product_name' => $memberPlan_products_payload['product_name'],
	// 					':image_path' => $memberPlan_products_payload['image_path']
	// 				]);
	// 			} catch (Exception $e) {
	// 				$sql_query_delete = "DELETE FROM `membership_plans` WHERE `id` = '$membership_plan_id'";
	// 				$this->db->query($sql_query_delete);
	// 				foreach ($all_member_group_ids_array as $val) {
	// 					try {
	// 						$this->delete_memberPlan_group($val);
	// 					} catch (Exception $e) {
	// 					}
	// 				}
	// 				return json_encode(['isError' => true, 'message' => $e->getMessage()]);
	// 			}

	// 			$groupOptions = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['sellingPlans']['edges'];
	// 			$membership_groupOptions_payload = [];

	// 			foreach ($groupOptions as $option_key => $groupOption) {
	// 				$member_plan_options = $val['member_plan_options'][$option_key];
	// 				$single_groupOption_details = [
	// 					'store' => trim($this->store),
	// 					'membership_group_id' => trim($sellingPlanGroupId),
	// 					'membership_plan_id' => trim($membership_plan_id),
	// 					'membership_option_id' => trim(str_replace("gid://shopify/SellingPlan/", "", $groupOption['node']['id'])),
	// 					'description' => trim($member_plan_options['description'] ?? ''),
	// 					'option_charge_type' => trim($member_plan_options['option_charge_type']),
	// 					'option_charge_value' => trim($member_plan_options['option_charge_value']),
	// 					// 'discount_offer' => $member_plan_options['discount_offer'] ? '1' : '0',
	// 					'discount_type' => $member_plan_options['offer_discount_type'] == 'Discount Off' ? 'A' : 'P',  //Percent Off(%)
	// 					'discount_value' => $member_plan_options['discount_value'] ?? 0,
	// 					// 'after_discount_offer' => $member_plan_options['after_discount_offer'] ? '1' : '0',
	// 					'after_discount_type' => $member_plan_options['after_discount_type'] == 'Discount Off' ? 'A' : 'P',
	// 					'after_discount_value' => $member_plan_options['after_discount_value'] ?? 0,
	// 					'change_discount_after_cycle' => $member_plan_options['change_discount_after_cycle'] ?? 0,
	// 					'min_cycle' => $member_plan_options['min_cycle'] ?? null,
	// 					'max_cycle' => $member_plan_options['max_cycle'] ?? null,
	// 					'option_price' => trim($member_plan_options['option_price']),
	// 					'anchor_type' => $member_plan_options['set_anchor_date'] ? ($member_plan_options['anchor_type'] == 'On Purchase Day' ? '1' : '2') : '0',
	// 					'anchor_day' => 0,
	// 				];
	// 				array_push($membership_groupOptions_payload, $single_groupOption_details);
	// 			}

	// 			try {
	// 				foreach ($membership_groupOptions_payload as $row) {
	// 					// print_r($row);
	// 					// Check if record exists
	// 					$row['trial_period_value'] = $trial_period_value;
	// 					$row['offer_trial_status'] = $offer_trial_status;
	// 					$row['trial_period_type'] = $trial_period_type;
	// 					$row['discount_type'] = $discount_type == 'Discount Off' ? 'A' : 'P';
	// 					$row['discount_value'] = $discount_value ?? 0;
	// 					$row['after_discount_type'] = $discount_type_after == 'Discount Off' ? 'A' : 'P';
	// 					$row['after_discount_value'] = $discount_value_after ?? 0;
	// 					$row['change_discount_after_cycle'] = 1;
	// 					$row['renew_on_original_date'] = $renew_original_date;
	// 					$row['discount_offer'] = $discount_status;
	// 					$row['after_discount_offer'] = $discount_after_status;
	// 					// print_r($row);
	// 					// $checkQuery = "SELECT id FROM membership_groups_details WHERE store = :store AND membership_option_id = :membership_option_id";
	// 					// $stmt = $this->db->prepare($checkQuery);
	// 					// $stmt->execute([
	// 					// 	':store' => $row['store'],
	// 					// 	':membership_option_id' => $row['membership_option_id'],
	// 					// ]);

	// 					// if ($stmt->rowCount() > 0) {
	// 					$saleDataArray = [
	// 						'sale_status' => 'Paused',
	// 					];

	// 					$whereCondition = [
	// 						'store' => $row['store'],
	// 						'membership_option_id' => $row['membership_option_id']
	// 					];

	// 					// Insert or update the data in the database
	// 					$row = array_filter($row, function ($val) {
	// 						return $val !== null && $val !== '';
	// 					});
	// 					// print_r($row);
	// 					$checkedUpdate = $this->insertupdateajax('membership_groups_details', $row, $whereCondition, 'AND');
	// 					// $this->insertupdateajax('membership_early_sales', $row, $whereCondition, 'AND');
	// 					// Update
	// 					// 	$updateQuery = "UPDATE membership_groups_details SET 
	// 					// 		membership_group_id = :membership_group_id,
	// 					// 		membership_plan_id = :membership_plan_id,
	// 					// 		description = :description,
	// 					// 		option_charge_type = :option_charge_type,
	// 					// 		option_charge_value = :option_charge_value,
	// 					// 		discount_offer = :discount_offer,
	// 					// 		discount_type = :discount_type,
	// 					// 		discount_value = :discount_value,
	// 					// 		after_discount_offer = :after_discount_offer,
	// 					// 		after_discount_type = :after_discount_type,
	// 					// 		after_discount_value = :after_discount_value,
	// 					// 		change_discount_after_cycle = :change_discount_after_cycle,
	// 					// 		min_cycle = :min_cycle,
	// 					// 		max_cycle = :max_cycle,
	// 					// 		option_price = :option_price,
	// 					// 		anchor_type = :anchor_type,
	// 					// 		anchor_day = :anchor_day,
	// 					// 		offer_trial_status = :offer_trial_status,
	// 					// 		trial_period_type = :trial_period_type,
	// 					// 		trial_period_value = :trial_period_value,
	// 					// 		renew_on_original_date = :renew_on_original_date
	// 					// 		WHERE store = :store AND membership_option_id = :membership_option_id";
	// 					// 	$stmt = $this->db->prepare($updateQuery);
	// 					// 	$stmt->execute($row);
	// 					// } else {
	// 					// 	// Insert
	// 					// 	$insertQuery = "INSERT INTO membership_groups_details (
	// 					// 		store, membership_group_id, membership_plan_id, membership_option_id, description,
	// 					// 		option_charge_type, option_charge_value, discount_offer, discount_type, discount_value,
	// 					// 		after_discount_offer, after_discount_type, after_discount_value, change_discount_after_cycle,
	// 					// 		min_cycle, max_cycle, option_price, anchor_type, anchor_day, trial_period_value, offer_trial_status, trial_period_type , renew_on_original_date
	// 					// 	) VALUES ( 
	// 					// 		:store, :membership_group_id, :membership_plan_id, :membership_option_id, :description,
	// 					// 		:option_charge_type, :option_charge_value, :discount_offer, :discount_type, :discount_value,
	// 					// 		:after_discount_offer, :after_discount_type, :after_discount_value, :change_discount_after_cycle,
	// 					// 		:min_cycle, :max_cycle, :option_price, :anchor_type, :anchor_day, :trial_period_value, :offer_trial_status, :trial_period_type , :renew_on_original_date
	// 					// 	)";
	// 					// 	$stmt = $this->db->prepare($insertQuery);
	// 					// 	$stmt->execute($row);
	// 					// }
	// 				}
	// 			} catch (Exception $e) {
	// 				// Rollback logic similar to Laravel
	// 				$this->db->prepare("DELETE FROM membership_plans WHERE id = ?")->execute([$membership_plan_id]);

	// 				foreach ($all_member_group_ids_array as $group_id) {
	// 					try {
	// 						$this->delete_memberPlan_group($group_id);
	// 					} catch (Exception $ex) {
	// 						// optional: log exception
	// 					}
	// 				}

	// 				echo json_encode(['isError' => true, 'message' => $e->getMessage()]);
	// 				exit;
	// 			}
	// 		} else {
	// 			if (!empty($all_member_group_ids_array)) {
	// 				foreach ($all_member_group_ids_array as $val) {
	// 					try {
	// 						$this->delete_memberPlan_group($val);
	// 					} catch (Exception $e) {
	// 					}
	// 				}
	// 			}
	// 			$this->db->prepare("DELETE FROM membership_plans WHERE id = ?")->execute([$membership_plan_id]);
	// 			return json_encode(['isError' => true, 'message' => $sellingPlanGroupCreateapi_error]);
	// 		}

	// 		$group_position++;
	// 	}

	// 	$this->upsertMetaObject();
	// 	return json_encode(['isError' => false, 'message' => "Membership plan created successfully", 'membership_plan_id' => $membership_plan_id]);
	// }

	public function create_member_plan($data)
	{
		$get_all_data = json_decode($data['data'], true);

		$memberPlanGroups = $get_all_data['memberPlan_data'];
		// print_r($get_all_data);	die;

		$popular_plan = $get_all_data['popular_plan'];

		$group_position = $this->get_group_position($this->store);

		if ($get_all_data['plan_product_type'] == 'custom_product') {

			// print_r($get_all_data);
			$custom_product_data = $this->create_custom_product($get_all_data);

			// print_r($custom_product_data);

			if ($custom_product_data == 'error') {
                 echo('here3');
				return json_encode(["isError" => true, 'message' => 'mutation execution error']);
			}
		}

		$product_description = $get_all_data['member_product_description'] ?? null;
		$product_url = $get_all_data['member_product_url'] ?? null;

		$subscription_plan_payload = [
			"store" => trim($this->store),
			"membership_plan_name" => trim($get_all_data['memberPlan_name']),
			"membership_product_type" => trim($get_all_data['plan_product_type']),
			"membership_product_url" => trim($custom_product_data['handle']),
			"membership_product_description" => trim($product_description),
		];
		// $save_member_plan = $this->insert_row($tableName,$fields);
		$sql_query_insert = "INSERT INTO `membership_plans` (`store`, `membership_plan_name`, `membership_product_type`, `membership_product_url`, `membership_product_description`) 
        VALUES (:store, :membership_plan_name, :membership_product_type, :membership_product_url, :membership_product_description)";


		$stmt = $this->db->prepare($sql_query_insert);
		$stmt->execute([
			':store' => trim($this->store),
			':membership_plan_name' => trim($get_all_data['memberPlan_name']),
			':membership_product_type' => trim($get_all_data['plan_product_type']),
			':membership_product_url' => trim($custom_product_data['handle']),
			':membership_product_description' => trim($product_description),
		]);

		$membership_plan_id = $this->db->lastInsertId();


		$all_member_group_ids_array = [];
		$offer_trial_status = 0;
		foreach ($memberPlanGroups as $key => $val) {

			// print_r($val);
			// echo "okkkkkkkkk\n";

			$sellingPlansToCreate = $this->create_member_options_array((object)$val, 'create', $this->currency_code);
			// print_r($sellingPlansToCreate);
			// die;

			// if (array_key_exists('offer_trial_status', $val) && $val['offer_trial_status'] == 'on') {
			if ((array_key_exists('offer_trial_status', $val) && $val['offer_trial_status'] == 'on') || (array_key_exists('member_offer_trial_period_status', $val) && $val['member_offer_trial_period_status'] == 'on')) {

				// echo "sdjhj";
				$offer_trial_status = 1;
				$trial_period_value = $val['trial_period_value'];
				$trial_period_type = $val['trial_period_type'] ?? 'days';
				$discount_type = $val['discount_type'];
				$discount_value = $val['discount_value'];
				$discount_value_after = $val['discount_value_after'];
				$discount_type_after = $val['discount_type_after'];
				$renew_original_date = $val['renew_on_original_date'] == 'true' ? 'true' : 'false';
			} else {
				$offer_trial_status = 0;
				// $discount_type = '';
				// $discount_value_after = '';
				// $discount_type_after = '';
				$trial_period_value = null;
				$trial_period_type = 'days';
				$renew_original_date = 'false';
			}
			// discount_offer_status
			if (array_key_exists('discount_status', $val) && ($val['discount_status'] == 'on' || $val['discount_status'] == '1')) {
				// echo "sdjhj";die;
				$discount_status = 1;
				$discount_value = $val['discount_value'];
				$discount_type = $val['discount_type'];
			} else {
				$discount_status = 0;
				$discount_type = '';
				$discount_value = 0;
				$discount_after_status = 0;
				$discount_value_after = '';
				$discount_type_after = '';
			}
			// after_discount_offer_status
			if (array_key_exists('discount_after_status', $val) && ($val['discount_after_status'] == 'on' || $val['discount_after_status'] == '1')) {
				// echo "sdjhj";die;
				$discount_after_status = 1;
				$discount_value_after = $val['discount_value_after'];
				$discount_type_after = $val['discount_type_after'];
			} else {
				$discount_after_status = 0;
			}

			$variant_data_array = $custom_product_data['variants'];
			$variant_array_string = $variant_data_array['nodes'][$key]['id'] ?? null;
			

			$variant_id = str_replace("gid://shopify/ProductVariant/", "", $variant_data_array['nodes'][$key]['id']);
			// $memberPlan_description = !empty($val['group_description']) ? 'description: "' . $val['group_description'] . '"' : '';
			$memberPlan_description = !empty($val['group_description']) ? ['description' => $val['group_description']] : [];

        
			try {

				// $graphQL_sellingPlanGroupCreate = 'mutation {
				// 	sellingPlanGroupCreate(
				// 			input: {
				// 				name: "' . $val['member_plan_tier_name'] . '"
				// 				merchantCode: "' . $val['member_plan_tier_name'] . '"
				// 				options: ["' . $sellingPlansToCreate['main_group_option'] . '"]
				// 				position: ' . $group_position . '
				// 				' . $memberPlan_description . '
				// 				sellingPlansToCreate: ' . $sellingPlansToCreate['selling_plans'] . '
				// 				appId:  "4trw27bTrit21member7KePw22asg445r78arewphoenix-membership"
				// 			}
				// 			resources: { productIds: [], productVariantIds: "' . $variant_array_string . '"}
				// 		) {
				// 		sellingPlanGroup {
				// 		id
				// 		appId
				// 		sellingPlans(first: 4){
				// 			edges{
				// 				node{
				// 					id
				// 					name
				// 				}
				// 			}
				// 		}
				// 		}
				// 		userErrors {
				// 		field
				// 		message
				// 		}
				// 	}
				// }';
				// // echo $graphQL_sellingPlanGroupCreate;die;
				// $sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate);

				$graphQL_sellingPlanGroupCreate = <<<GRAPHQL
				mutation sellingPlanGroupCreateMutation(\$input: SellingPlanGroupInput!, \$resources: SellingPlanGroupResourceInput!) {
				sellingPlanGroupCreate(
					input: \$input,
					resources: \$resources
				) {
					sellingPlanGroup {
					id
					appId
					sellingPlans(first: 4) {
						edges {
						node {
							id
							name
						}
						}
					}
					}
					userErrors {
					field
					message
					}
				}
				}
				GRAPHQL;

				// Final GraphQL variables array

				$variables = [
					'input' => array_merge(
						[
							'name' => $val['member_plan_tier_name'],
							'merchantCode' => $val['member_plan_tier_name'],
							'options' => [$sellingPlansToCreate['main_group_option']],
							'position' => (int)$group_position,
						],
						$memberPlan_description,
						[
							'sellingPlansToCreate' => $sellingPlansToCreate['selling_plans'],
							'appId' => '4trw27bTrit21member7KePw22asg445r78arewphoenix-membership',
						]
					),
					'resources' => [
						'productIds' => [],
						'productVariantIds' => [$variant_array_string],
					],
				];

				// print_r($variables);
			    // die;

				$sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate, null, null, $variables);

			
				// print_r($sellingPlanGroupCreateapi_execution);

				$sellingPlanGroupCreateapi_error = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['userErrors'];
			} catch (Exception $e) {
				$sql_query_delete = "DELETE FROM `membership_plans` WHERE `id` = '$membership_plan_id'";
				$this->db->query($sql_query_delete);
                echo('here1');
				// MembershipPlan::where('id', $membership_plan_id)->delete();
				return json_encode(["isError" => true, 'error' => $e->getMessage(), 'message' => 'mutation execution error']);
			}

			if (!count($sellingPlanGroupCreateapi_error)) {
				$sellingPlanGroupId_complete = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['id'];
				$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupId_complete);
				array_push($all_member_group_ids_array, $sellingPlanGroupId);

				$add_popular_plan = ($popular_plan == trim($val['member_plan_tier_name'])) ? '1' : '0';

				$memberPlan_group_payload = [
					"membership_group_id" => trim($sellingPlanGroupId),
					"store" => trim($this->store),
					"membership_plan_id" => trim($membership_plan_id),
					"group_position" => trim($group_position),
					"popular_plan" => $add_popular_plan,
					"membership_group_name" => trim($val['member_plan_tier_name']),
					"group_description" => trim($val['group_description']),
					"unique_handle" => trim($val['member_plan_tier_handle']),
					"variant_id" => trim($variant_id),
				];

				try {
					$check_sql = "SELECT COUNT(*) FROM membership_plan_groups WHERE membership_group_id = :membership_group_id";
					$stmt = $this->db->prepare($check_sql);
					$stmt->execute([':membership_group_id' => $memberPlan_group_payload['membership_group_id']]);
					$exists = $stmt->fetchColumn();

					if ($exists) {
						// Update
						$update_sql = "UPDATE membership_plan_groups SET 
							store = :store,
							membership_plan_id = :membership_plan_id,
							group_position = :group_position,
							popular_plan = :popular_plan,
							membership_group_name = :membership_group_name,
							group_description = :group_description,
							unique_handle = :unique_handle,
							variant_id = :variant_id
							WHERE membership_group_id = :membership_group_id";

						$stmt = $this->db->prepare($update_sql);
					} else {
						// Insert
						$insert_sql = "INSERT INTO membership_plan_groups (
							membership_group_id, store, membership_plan_id, group_position, popular_plan,
							membership_group_name, group_description, unique_handle, variant_id
						) VALUES (
							:membership_group_id, :store, :membership_plan_id, :group_position, :popular_plan,
							:membership_group_name, :group_description, :unique_handle, :variant_id
						)";

						$stmt = $this->db->prepare($insert_sql);
					}

					$stmt->execute([
						':membership_group_id' => $memberPlan_group_payload['membership_group_id'],
						':store' => $memberPlan_group_payload['store'],
						':membership_plan_id' => $memberPlan_group_payload['membership_plan_id'],
						':group_position' => $memberPlan_group_payload['group_position'],
						':popular_plan' => $memberPlan_group_payload['popular_plan'],
						':membership_group_name' => $memberPlan_group_payload['membership_group_name'],
						':group_description' => $memberPlan_group_payload['group_description'],
						':unique_handle' => $memberPlan_group_payload['unique_handle'],
						':variant_id' => $memberPlan_group_payload['variant_id'],
					]);
				} catch (Exception $e) {
					$sql_query_delete = "DELETE FROM `membership_plans` WHERE `id` = '$membership_plan_id'";
					$this->db->query($sql_query_delete);
					foreach ($all_member_group_ids_array as $val) {
						try {
							$this->delete_memberPlan_group($val);
						} catch (Exception $e) {
						}
					}
					return json_encode(['isError' => true, 'message' => $e->getMessage()]);
				}

				$product_image = $custom_product_data['media']['edges'][0]['node']['preview']['image']['url'] ?? '';

				$memberPlan_products_payload = [
					"store" => trim($this->store),
					"membership_group_id" => trim($sellingPlanGroupId),
					"membership_plan_id" => trim($membership_plan_id),
					"product_id" => trim(str_replace("gid://shopify/Product/", "", $custom_product_data['id'])),
					"variant_id" => trim($variant_id),
					"variant_price" => trim($custom_product_data['variants']['nodes'][$key]['price']),
					"product_name" => trim($custom_product_data['title'] . '-' . $custom_product_data['variants']['nodes'][$key]['title']),
					"image_path" => trim($product_image),
				];

				try {
					$check_sql = "SELECT COUNT(*) FROM membership_product_details WHERE membership_plan_id = :membership_plan_id AND membership_group_id = :membership_group_id AND variant_id = :variant_id";
					$stmt = $this->db->prepare($check_sql);
					$stmt->execute([
						':membership_plan_id' => $memberPlan_products_payload['membership_plan_id'],
						':membership_group_id' => $memberPlan_products_payload['membership_group_id'],
						':variant_id' => $memberPlan_products_payload['variant_id']
					]);
					$exists = $stmt->fetchColumn();

					if ($exists) {
						// Update
						$update_sql = "UPDATE membership_product_details SET 
							store = :store,
							product_id = :product_id,
							product_name = :product_name,
							image_path = :image_path
							WHERE membership_plan_id = :membership_plan_id 
							AND membership_group_id = :membership_group_id 
							AND variant_id = :variant_id";
						$stmt = $this->db->prepare($update_sql);
					} else {
						// Insert
						$insert_sql = "INSERT INTO membership_product_details (
							store, membership_group_id, membership_plan_id, product_id, variant_id,
							variant_price, product_name, image_path
						) VALUES (
							:store, :membership_group_id, :membership_plan_id, :product_id, :variant_id,
							:variant_price, :product_name, :image_path
						)";
						$stmt = $this->db->prepare($insert_sql);
					}

					$stmt->execute([
						':store' => $memberPlan_products_payload['store'],
						':membership_group_id' => $memberPlan_products_payload['membership_group_id'],
						':membership_plan_id' => $memberPlan_products_payload['membership_plan_id'],
						':product_id' => $memberPlan_products_payload['product_id'],
						':variant_id' => $memberPlan_products_payload['variant_id'],
						':variant_price' => $memberPlan_products_payload['variant_price'],
						':product_name' => $memberPlan_products_payload['product_name'],
						':image_path' => $memberPlan_products_payload['image_path']
					]);
				} catch (Exception $e) {
					$sql_query_delete = "DELETE FROM `membership_plans` WHERE `id` = '$membership_plan_id'";
					$this->db->query($sql_query_delete);
					foreach ($all_member_group_ids_array as $val) {
						try {
							$this->delete_memberPlan_group($val);
						} catch (Exception $e) {
						}
					}
					return json_encode(['isError' => true, 'message' => $e->getMessage()]);
				}

				$groupOptions = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['sellingPlans']['edges'];
				$membership_groupOptions_payload = [];

				foreach ($groupOptions as $option_key => $groupOption) {
					$member_plan_options = $val['member_plan_options'][$option_key];
					$single_groupOption_details = [
						'store' => trim($this->store),
						'membership_group_id' => trim($sellingPlanGroupId),
						'membership_plan_id' => trim($membership_plan_id),
						'membership_option_id' => trim(str_replace("gid://shopify/SellingPlan/", "", $groupOption['node']['id'])),
						'description' => trim($member_plan_options['description'] ?? ''),
						'option_charge_type' => trim($member_plan_options['option_charge_type']),
						'option_charge_value' => trim($member_plan_options['option_charge_value']),
						// 'discount_offer' => $member_plan_options['discount_offer'] ? '1' : '0',
						// 'discount_type' => $member_plan_options['offer_discount_type'] == 'Discount Off' ? 'A' : 'P',  //Percent Off(%)
						'discount_type' => (isset($member_plan_options['offer_discount_type']) && $member_plan_options['offer_discount_type'] == 'Discount Off') ? 'A' : 'P',
						'discount_value' => $member_plan_options['discount_value'] ?? 0,
						// 'after_discount_offer' => $member_plan_options['after_discount_offer'] ? '1' : '0',
						'after_discount_type' => (isset($member_plan_options['after_discount_type']) && $member_plan_options['after_discount_type'] == 'Discount Off') ? 'A' : 'P',
						'after_discount_value' => $member_plan_options['after_discount_value'] ?? 0,
						'change_discount_after_cycle' => $member_plan_options['change_discount_after_cycle'] ?? 0,
						'min_cycle' => $member_plan_options['min_cycle'] ?? null,
						'max_cycle' => $member_plan_options['max_cycle'] ?? null,
						'option_price' => trim($member_plan_options['option_price']),
						'anchor_type' => $member_plan_options['set_anchor_date'] ? ($member_plan_options['anchor_type'] == 'On Purchase Day' ? '1' : '2') : '0',
						'anchor_day' => 0,
					];
					array_push($membership_groupOptions_payload, $single_groupOption_details);
				}

				try {
					foreach ($membership_groupOptions_payload as $row) {
						// print_r($row);
						// Check if record exists
						$row['trial_period_value'] = $trial_period_value;
						$row['offer_trial_status'] = $offer_trial_status;
						$row['trial_period_type'] = $trial_period_type;
						$row['discount_type'] = $discount_type == 'Discount Off' ? 'A' : 'P';
						$row['discount_value'] = $discount_value ?? 0;
						$row['after_discount_type'] = $discount_type_after == 'Discount Off' ? 'A' : 'P';
						$row['after_discount_value'] = $discount_value_after ?? 0;
						$row['change_discount_after_cycle'] = 1;
						$row['renew_on_original_date'] = $renew_original_date;
						$row['discount_offer'] = $discount_status;
						$row['after_discount_offer'] = $discount_after_status;
						// print_r($row);
						// $checkQuery = "SELECT id FROM membership_groups_details WHERE store = :store AND membership_option_id = :membership_option_id";
						// $stmt = $this->db->prepare($checkQuery);
						// $stmt->execute([
						// 	':store' => $row['store'],
						// 	':membership_option_id' => $row['membership_option_id'],
						// ]);

						// if ($stmt->rowCount() > 0) {
						$saleDataArray = [
							'sale_status' => 'Paused',
						];

						$whereCondition = [
							'store' => $row['store'],
							'membership_option_id' => $row['membership_option_id']
						];

						// Insert or update the data in the database
						$row = array_filter($row, function ($val) {
							return $val !== null && $val !== '';
						});
						// print_r($row);
						$checkedUpdate = $this->insertupdateajax('membership_groups_details', $row, $whereCondition, 'AND');
						// $this->insertupdateajax('membership_early_sales', $row, $whereCondition, 'AND');
						// Update
						// 	$updateQuery = "UPDATE membership_groups_details SET 
						// 		membership_group_id = :membership_group_id,
						// 		membership_plan_id = :membership_plan_id,
						// 		description = :description,
						// 		option_charge_type = :option_charge_type,
						// 		option_charge_value = :option_charge_value,
						// 		discount_offer = :discount_offer,
						// 		discount_type = :discount_type,
						// 		discount_value = :discount_value,
						// 		after_discount_offer = :after_discount_offer,
						// 		after_discount_type = :after_discount_type,
						// 		after_discount_value = :after_discount_value,
						// 		change_discount_after_cycle = :change_discount_after_cycle,
						// 		min_cycle = :min_cycle,
						// 		max_cycle = :max_cycle,
						// 		option_price = :option_price,
						// 		anchor_type = :anchor_type,
						// 		anchor_day = :anchor_day,
						// 		offer_trial_status = :offer_trial_status,
						// 		trial_period_type = :trial_period_type,
						// 		trial_period_value = :trial_period_value,
						// 		renew_on_original_date = :renew_on_original_date
						// 		WHERE store = :store AND membership_option_id = :membership_option_id";
						// 	$stmt = $this->db->prepare($updateQuery);
						// 	$stmt->execute($row);
						// } else {
						// 	// Insert
						// 	$insertQuery = "INSERT INTO membership_groups_details (
						// 		store, membership_group_id, membership_plan_id, membership_option_id, description,
						// 		option_charge_type, option_charge_value, discount_offer, discount_type, discount_value,
						// 		after_discount_offer, after_discount_type, after_discount_value, change_discount_after_cycle,
						// 		min_cycle, max_cycle, option_price, anchor_type, anchor_day, trial_period_value, offer_trial_status, trial_period_type , renew_on_original_date
						// 	) VALUES ( 
						// 		:store, :membership_group_id, :membership_plan_id, :membership_option_id, :description,
						// 		:option_charge_type, :option_charge_value, :discount_offer, :discount_type, :discount_value,
						// 		:after_discount_offer, :after_discount_type, :after_discount_value, :change_discount_after_cycle,
						// 		:min_cycle, :max_cycle, :option_price, :anchor_type, :anchor_day, :trial_period_value, :offer_trial_status, :trial_period_type , :renew_on_original_date
						// 	)";
						// 	$stmt = $this->db->prepare($insertQuery);
						// 	$stmt->execute($row);
						// }
					}
				} catch (Exception $e) {
					// Rollback logic similar to Laravel
					$this->db->prepare("DELETE FROM membership_plans WHERE id = ?")->execute([$membership_plan_id]);

					foreach ($all_member_group_ids_array as $group_id) {
						try {
							$this->delete_memberPlan_group($group_id);
						} catch (Exception $ex) {
							// optional: log exception
						}
					}

					echo json_encode(['isError' => true, 'message' => $e->getMessage()]);
					exit;
				}
			} else {
				if (!empty($all_member_group_ids_array)) {
					foreach ($all_member_group_ids_array as $val) {
						try {
							$this->delete_memberPlan_group($val);
						} catch (Exception $e) {
						}
					}
				}
				$this->db->prepare("DELETE FROM membership_plans WHERE id = ?")->execute([$membership_plan_id]);
				return json_encode(['isError' => true, 'message' => $sellingPlanGroupCreateapi_error]);
			}

			$group_position++;
		}

		$this->upsertMetaObject();
		return json_encode(['isError' => false, 'message' => "Membership plan created successfully", 'membership_plan_id' => $membership_plan_id]);
	}

	public function delete_memberPlan_group($member_plan_group_id)
	{
		$graphQL_sellingPlanGroupDelete = 'mutation {
			sellingPlanGroupDelete(id: "gid://shopify/SellingPlanGroup/' . $member_plan_group_id . '") {
			deletedSellingPlanGroupId
				userErrors {
					code
					field
					message
				}
			}
		}';
		$this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupDelete, null, null, null);
	}

	// create discount
	function createSaleDiscount($store, $saleData, $action)
	{
		$access_token = $this->access_token;
		$shopify_api_version = $this->SHOPIFY_API_VERSION; // Adjust as necessary

		$currentDate = date("Y-m-d\TH:i:s\Z");
		// $sale_start_date = $this->convertDateFormat($saleData['sale_start_date']);
		// $sale_end_date = $this->convertDateFormat($saleData['sale_end_date']);
		$sale_start_date = $saleData['sale_start_date'];
		$sale_end_date = new DateTime(strval($saleData['sale_end_date']));

		if (!$sale_start_date || !$sale_end_date) {
			return "Invalid sale dates";
		}
		// $checkNumberofEarlyDays = DB::table('membership_perks')->select('no_of_sale_days')->where('store', '=', $store)->orderByDesc('no_of_sale_days')->first();

		$sql = "SELECT no_of_sale_days FROM membership_perks WHERE store = :store ORDER BY no_of_sale_days DESC LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['store' => $this->store]);
		$checkNumberofEarlyDays = $stmt->fetch(PDO::FETCH_OBJ);

		// print_r($checkNumberofEarlyDays);

		if ($checkNumberofEarlyDays) {
			$no_of_sale_days = $checkNumberofEarlyDays->no_of_sale_days ?? 0;
		} else {
			$no_of_sale_days = 0;
		}

		if (!is_numeric($no_of_sale_days)) {
			$no_of_sale_days = intval($no_of_sale_days);
		}

		/// Calculate final sale start date based on number of early sale days
		// $finalSaleStart = clone $sale_start_date;
		// $finalSaleStart->modify('-' . $no_of_sale_days . ' days');
		// $finalSaleStart = $finalSaleStart->format("Y-m-d\TH:i:s\Z");
		$finalSaleStart = (new DateTime($sale_start_date))->modify('-' . $no_of_sale_days . ' days')->format("Y-m-d\TH:i:s\Z");

		$Type = $action['type'];
		$priceRuleid = $action['priceRuleid'];
		$discountRuleid = $action['discountRuleid'];
		$discountCode = $action['code'];
		$sale_discount = $saleData['sale_discount'];
		$productType = $action['productType'];

		$customer_segments = $saleData['customer_segments'] ?? '';

		$oldCustomerSegments = [];

		if (!empty($customer_segments)) {
			$oldCustomerSegments = explode(',', $customer_segments);
			$oldCustomerSegments = array_map('trim', $oldCustomerSegments);
			// print_r($oldCustomerSegments);
		} else {
			// echo "Customer IDs are empty.";
		}

		$newCustomerSegments = $this->updateCustomerTags($this->store, $currentDate, $sale_start_date, $oldCustomerSegments);
		// print_r($newCustomerSegments);

		$sale_end_date_formatted = $sale_end_date->format("Y-m-d\TH:i:s\Z");
		$create_salePriceRule_payload = [
			"price_rule" => [
				"value_type" => "percentage",
				"value" => -1 * $sale_discount,
				"starts_at" => $finalSaleStart,
				"ends_at" => $sale_end_date_formatted,
				"usage_limit" => null,
				"once_per_customer" => 'false',
				"prerequisite_subtotal_range" => null,
				"prerequisite_quantity_range" => null,
				"prerequisite_shipping_price_range" => null,
				"customer_segment_prerequisite_ids" => [],
				// "target_type" => "line_item",
				"title" => "EARLY-SALE-DISCOUNT (Do not Delete)",
			],
		];
		// print_r($finalSaleStart);


		if ($currentDate < $sale_start_date) {
			if (!empty($newCustomerSegments)) {
				$create_salePriceRule_payload['price_rule']['customer_selection'] = "prerequisite";
				$create_salePriceRule_payload['price_rule']['customer_segment_prerequisite_ids'] = $newCustomerSegments;
			} else {
				$create_salePriceRule_payload['price_rule']['customer_selection'] = "all";
				$newCustomerSegments = [];
			}
		} else {
			$create_salePriceRule_payload['price_rule']['customer_selection'] = "all";
			$create_salePriceRule_payload['price_rule']['customer_segment_prerequisite_ids'] = [];
			$newCustomerSegments = [];
		}


		if ($productType != 'N') {
			$productIds = $saleData['productIds'] ?? [];
			$collectionIds = $saleData['collectionIds'] ?? [];
			$create_salePriceRule_payload['price_rule']['target_selection'] = "entitled";
			$create_salePriceRule_payload['price_rule']['target_type'] = "line_item";
			$create_salePriceRule_payload['price_rule']['allocation_method'] = "each";
			$create_salePriceRule_payload['price_rule']['entitled_product_ids'] = $productIds;
			$create_salePriceRule_payload['price_rule']['entitled_collection_ids'] = $collectionIds;
		} else {
			$create_salePriceRule_payload['price_rule']['target_type'] = "line_item";
			$create_salePriceRule_payload['price_rule']['target_selection'] = "all";
			$create_salePriceRule_payload['price_rule']['allocation_method'] = "across";
			$create_salePriceRule_payload['price_rule']['entitled_product_ids'] = [];
			$create_salePriceRule_payload['price_rule']['entitled_collection_ids'] = [];
		}
		// print_r($create_salePriceRule_payload);
		// $create_salePriceRule_payload['price_rule']['once_per_customer'] = 'false';
		if ($Type == 'PUT') {
			$url = 'https://' . $store . '/admin/api/' . $shopify_api_version . '/price_rules/' . $priceRuleid . '.json';
			$method = 'PUT';
		} else {
			$url = 'https://' . $store . '/admin/api/' . $shopify_api_version . '/price_rules.json';
			$method = 'POST';
		}

		$priceRuleJsonData = json_encode($create_salePriceRule_payload);
		// $priceRuleJsonData = json_decode($priceRuleJsonData, true); // convert JSON string to PHP array

		// Remove null value
		// $priceRuleJsonData = array_filter($priceRuleJsonData, function ($val) {
		// 	return $val !== null && $val !== '';
		// });
		//  echo($method);
		//  echo($access_token);
		//  echo($url);
		//  print_r($create_salePriceRule_payload);

		try {
			$create_priceRule_execution = $this->PostPutApi($url, $method, $access_token, $priceRuleJsonData);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		// echo('strat');
		// print_r($create_priceRule_execution);
		// echo('end');

		if (isset($create_priceRule_execution['errors'])) {
			return json_encode($create_priceRule_execution);
		} else {
			if ($Type == 'PUT') {
				$idsArray = [
					'saleDiscountId' => $discountRuleid,
					'priceRuleId' => $priceRuleid,
					'discountCode' => $discountCode,
					'newCustomerSegments' => $newCustomerSegments
				];
				return $idsArray;
			} else {

				$priceRuleId = $create_priceRule_execution['price_rule']['admin_graphql_api_id'];
				$priceRuleWithoutGid = str_replace("gid://shopify/PriceRule/", "", $priceRuleId);
				$create_saleDiscount_payload = [
					"discount_code" => [
						"code" => $discountCode,
					],
				];
				$discountJsonData = json_encode($create_saleDiscount_payload);
				// $discountJsonData = json_decode($discountJsonData, true); // convert JSON string to PHP array

				$create_Discount_execution = $this->PostPutApi('https://' . $store . '/admin/api/' . $shopify_api_version . '/price_rules/' . $priceRuleWithoutGid . '/discount_codes.json', 'POST', $access_token, $discountJsonData);

				// echo '<pre>';
				// print_r($create_Discount_execution);
				// echo '</pre>';
				if (isset($create_Discount_execution['errors'])) {
					return json_encode($create_Discount_execution);
				} else {
					$saleDiscountId = $create_Discount_execution['discount_code']['id'];
					$idsArray = [
						'saleDiscountId' => $saleDiscountId,
						'priceRuleId' => $priceRuleWithoutGid,
						'discountCode' => $discountCode,
						'newCustomerSegments' => $newCustomerSegments
					];
					return $idsArray;
				}
			}
		}
	}


	// early sales
	public function save_earlySale_data($request)
	{
		// echo '<pre>';
		// print_r($request);
		// die();
		$saleData = $request;
		$storeName = trim($this->store);
		$buttonType = $saleData['buttonType'];


		// // Get store details (you need to implement this function)
		// // Instantiate ShopifySDK (make sure it's available in your codebase)
		// Fetch existing early sale data from your database
		// $earlySaleData = $this->customQuery("SELECT access_token, store, shop_timezone, currency, currencyCode, shop_plan, subscription_plans_view FROM install WHERE store = '$storeName'");

		// $earlySaleDatas = $this->customQuery("SELECT * FROM membership_early_sales WHERE store = '$storeName' LIMIT 1");
		// $earlySaleData = $earlySaleDatas[0];

		$sql = "SELECT * FROM `membership_early_sales` WHERE `store` = :store LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['store' => $this->store]);

		$earlySaleData = $stmt->fetch(PDO::FETCH_ASSOC);
		// echo '<pre>';
		// print_r($earlySaleData);

		$saleAutomaticDiscountId = $earlySaleData['discount_priceRule_id'] ?? '';

		if ($buttonType == 'Paused') {
			try {
				$id = str_replace("gid://shopify/DiscountCodeNode/", "", $saleAutomaticDiscountId);
				$SaleInput = $SaleInput = [
					"id" => "gid://shopify/DiscountCodeNode/" . $id,
				];

				$mutation_api = 'mutation discountCodeDeactivate($id: ID!) {
                    discountCodeDeactivate(id: $id) {
                      userErrors {
                        field
                        message
                        code
                      }
                      codeDiscountNode {
						id
                      }
                    }
                  }';


				$Salesdata = $this->shopify_graphql_object->GraphQL->post($mutation_api, null, null, $SaleInput);
				// print_r($Salesdata);
				if (count($Salesdata['data']['discountCodeDeactivate']['codeDiscountNode'])) {
					$saleAutomaticDiscountId = $Salesdata['data']['discountCodeDeactivate']['codeDiscountNode']['id'];
					$saleAutomaticDiscountId = str_replace("gid://shopify/PriceRule/", "", $saleAutomaticDiscountId);
				} else {
					$ErrorApiMsg = $Salesdata['data']['discountCodeDeactivate']['userErrors']['message'];
					return json_encode(['status' => false, 'message' => $ErrorApiMsg]);
				}

				// MembershipEarlySale::updateOrInsert(['store' => $storeName, 'discount_priceRule_id' => $saleAutomaticDiscountId], $saleDataArray);
				$saleDataArray = [
					'sale_status' => 'Paused',
				];

				$whereCondition = [
					'store' => $storeName,
					'discount_priceRule_id' => $saleAutomaticDiscountId
				];

				// Insert or update the data in the database
				$checkedUpdate = $this->insertupdateajax('membership_early_sales', $saleDataArray, $whereCondition, 'AND');
				// print_r($checkedUpdate);

				$this->upsertMetaObject();

				return json_encode(['status' => true, 'message' => 'Successfully paused']);
			} catch (\Exception $e) {
				return json_encode(['status' => false, 'message' => $e->getMessage()]);
			}
		}

		if ($buttonType == 'Delete') {
			try {
				if ($saleAutomaticDiscountId) {

					$id = str_replace("gid://shopify/DiscountCodeNode/", "", $saleAutomaticDiscountId);

					// $SaleInput = $SaleInput = [

					// 	"id" => "gid://shopify/DiscountCodeNode/" . $id,
					// ];

					$mutation_api = 'mutation {
						discountCodeDelete(id: "gid://shopify/DiscountCodeNode/' . $id . '") {
							deletedCodeDiscountId
							userErrors {
								message
							}
						}
					}';


					$Salesdata = $this->shopify_graphql_object->GraphQL->post($mutation_api);


					if (isset($Salesdata['data']['discountCodeDelete']['userErrors']) && !empty($Salesdata['data']['discountCodeDelete']['userErrors'])) {
						$ErrorApiMsg = $Salesdata['data']['discountCodeDelete']['userErrors'][0]['message'] ?? 'Unknown error';
						return json_encode(['status' => false, 'message' => $ErrorApiMsg]);
					}
					// MembershipEarlySale::where('store', $storeName)->where('discount_priceRule_id', $saleAutomaticDiscountId)->delete();

					$whereCondition = [
						'store' => $storeName,
						'discount_priceRule_id' => $saleAutomaticDiscountId
					];
					// die;
					$response = $this->delete_row('membership_early_sales', $whereCondition, "AND");
				} else {
					// MembershipEarlySale::where('store', $storeName)->delete();
					$whereCondition = [
						'store' => $storeName
					];
					$response = $this->delete_row('membership_early_sales', $whereCondition, "");
				}

				$this->upsertMetaObject();

				return json_encode(['status' => true, 'message' => 'Successfully Deleted']);
			} catch (\Exception $e) {
				return json_encode(['status' => false, 'message' => $e->getMessage()]);
			}
		}

		if ($buttonType == 'Save') {
			try {

				// Process the sale start and end dates
				// $sale_start_date = $this->convertDateFormat($saleData['sale_start_date']);
				$sale_start_date = $saleData['sale_start_date'];
				$sale_end_date =  $saleData['sale_end_date'];
				$selectBoxValue = $saleData['selectBoxValue'];
				$saleItemType = ($selectBoxValue == "Specific products") ? "P" : (($selectBoxValue == "Specific collections") ? "C" : "N");
				$sale_discount = $saleData['sale_discount'];

				// Process product and collection data
				$productIds = isset($saleData['productIds']) ? implode(',', $saleData['productIds']) : '';
				$productTitles = isset($saleData['productTitles']) ? implode(',', $saleData['productTitles']) : '';
				$productImages = isset($saleData['productImages']) ? implode(',', $saleData['productImages']) : '';
				$collectionIds = isset($saleData['collectionIds']) ? implode(',', $saleData['collectionIds']) : '';
				$collectionTitles = isset($saleData['collectionTitles']) ? implode(',', $saleData['collectionTitles']) : '';
				$collectionImages = isset($saleData['collectionImages']) ? implode(',', $saleData['collectionImages']) : '';

				// Set sale discount ID and coupon code
				$discount_id = $earlySaleData['discount_id'] ?? '';
				$saleDiscountCode = $earlySaleData['discount_coupan'] ?? "BIGSALE" . rand(1001, 9999);

				// Prepare action data
				$action = [
					'priceRuleid' => $saleAutomaticDiscountId,
					'discountRuleid' => $discount_id,
					'type' => $saleAutomaticDiscountId ? 'PUT' : 'POST',
					'productType' => $saleItemType,
					'code' => $saleDiscountCode
				];


				// Handle price rule creation or update
				$priceruleData = $this->createSaleDiscount($storeName, $saleData, $action);
				// die;
				// $priceruleData['saleDiscountId'];
				// echo '<pre>';
				// print_r($priceruleData);
				// echo '</pre>';
				// die;
				$saleAutomaticDiscountId = $priceruleData['priceRuleId'];
				$discount_id = $priceruleData['saleDiscountId'];

				// Prepare the sale data to be inserted or updated in the database
				$saleDataArray = [
					'store' => $storeName,
					'sale_start_date' => $sale_start_date,
					'sale_end_date' => $sale_end_date,
					'sale_status' => 'Active',
					'sale_productType' => $saleItemType,
					'sale_discount' => $sale_discount,
					'discount_id' => $discount_id,
					'discount_priceRule_id' => $saleAutomaticDiscountId,
					'discount_coupan' => $saleDiscountCode,
					'customer_segments' => implode(',', $priceruleData['newCustomerSegments']),
					'early_access_collection_title' => $collectionTitles,
					'early_access_collection_images' => $collectionImages,
					'early_access_collection_id' => $collectionIds,
					'early_access_product_title' => $productTitles,
					'early_access_product_images' => $productImages,
					'early_access_product_id' => $productIds,
					'restricted_early_access' => '0',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				];

				$whereCondition = [
					'store' => $storeName,
					'discount_priceRule_id' => $saleAutomaticDiscountId
				];

				// Insert or update the data in the database
				// MembershipEarlySale::updateOrInsert(['store' => $storeName, 'discount_priceRule_id' => $saleAutomaticDiscountId], $saleDataArray);
				$checkedUpdate = $this->insertupdateajax('membership_early_sales', $saleDataArray, $whereCondition, 'AND');
				// print_r($checkedUpdate);

				$this->upsertMetaObject();

				if ($checkedUpdate) {
					return json_encode(array("status" => true,  'message' => 'Successfully saved'));
				}
			} catch (Exception $e) {
				return json_encode(['status' => false, 'message' => $e->getMessage()]);
			}
		}
	}


	public function getStoreDetailsByDomain($shop)
	{

		$query = "
			SELECT store_details.*, install.access_token
			FROM store_details
			JOIN install ON store_details.store = install.store
			WHERE store_details.store = '$shop'
			LIMIT 1
		";

		$StoreDetailsData = $this->customQuery($query);
		return $StoreDetailsData;
	}

	public function convertDateFormat($dateString)
	{
		$date = new DateTime($dateString);
		return $date;
	}

	public function updateOrInsertMembershipEarlySale($storeName, $saleAutomaticDiscountId, $saleDataArray)
	{
		// First, check if the record already exists
		$checkQuery = "
			SELECT * FROM membership_early_sales 
			WHERE store = '$storeName' 
			AND discount_priceRule_id = '$saleAutomaticDiscountId' 
			LIMIT 1
		";

		$existingRecord = $this->customQuery($checkQuery);

		// If record exists, perform an UPDATE
		if ($existingRecord) {
			$updateQuery = "UPDATE membership_early_sales SET ";

			$this->customQuery($updateQuery);
		} else {
			// If the record does not exist, perform an INSERT
			$columns = implode(", ", array_keys($saleDataArray));  // Get columns as a string
			$values = "'" . implode("', '", array_map([$this->db, 'real_escape_string'], array_values($saleDataArray))) . "'";  // Escape and prepare values

			$insertQuery = "
				INSERT INTO membership_early_sales (store, discount_priceRule_id, $columns) 
				VALUES ('$storeName', '$saleAutomaticDiscountId', $values)
			";

			// Execute the insert query using customQuery
			$this->customQuery($insertQuery);
		}
	}
	//end early sales


	function checkIfStoreIsPrivate()
	{
		return isset($this->SHOPIFY_APIKEY) && isset($this->API_SECRET_KEY)
			&& $this->SHOPIFY_APIKEY !== null && $this->API_SECRET_KEY !== null
			&& strlen($this->SHOPIFY_APIKEY) > 0 && strlen($this->API_SECRET_KEY) > 0;
	}
	function getShopifyURLForStore($endpoint)
	{
		return $this->checkIfStoreIsPrivate() ?
			'https://' . $this->SHOPIFY_APIKEY . ':' . $this->API_SECRET_KEY . '@' . $this->store . '/admin/api/' . $this->SHOPIFY_API_VERSION . '/' . $endpoint
			:
			'https://' . $this->store . '/admin/api/' . $this->SHOPIFY_API_VERSION . '/' . $endpoint;
	}

	//    -------old---------

	// public function membershipPerksSave($data)
	// {


	// 	$currentDate = date('Y-m-d');

	// 	$RequestedShop_Name = $this->store;

	// 	// $storeDetails = $this->getStoreByDomain($RequestedShop_Name);

	// 	$shopName = $this->store;

	// 	// print_r($RequestedShop_Name);

	// 	// print_r($storeDetails);

	// 	// die;


	// 	$GetPerks_Values = $data['data'];

	// 	if (!empty($GetPerks_Values)) {

	// 		$Get_TierValue = $GetPerks_Values["obj"];

	// 		if (sizeof($Get_TierValue)) {

	// 			$flag = false;



	// 			foreach ($Get_TierValue as $key => $value) {

	// 				$checkfreeproductcheckbox = false;

	// 				$shop = $this->store;

	// 				$checkeFree_shipping_code = '';

	// 				$checkeFree_discountProduct_codeField = '';

	// 				if (array_key_exists("free_shipping_code", $value)) {

	// 					/*

	//                      * checked Discount

	//                      * code

	//                      */

	// 					$checkeFree_shipping_code = trim($value["free_shipping_code"]);



	// 					$response = $this->checkCodeExist($checkeFree_shipping_code);
	// 					if ($response['errors']) {

	// 						$flag = true;
	// 					} else {

	// 						$status = 'exists';

	// 						$message = $key;

	// 						return json_encode(array("beforeChecked" => 'free_shipping_code', "status" => $status, "message" => $message));
	// 					}
	// 				}



	// 				if (array_key_exists("discounted_product_collection_code", $value)) {



	// 					/*

	//                      * checked product

	//                      *  discount code

	//                      */

	// 					$checkeFree_discountProduct_codeField = trim($value["discounted_product_collection_code"]);



	// 					$response = $this->checkCodeExist($checkeFree_discountProduct_codeField);



	// 					if ($response['errors']) {

	// 						$flag = true;
	// 					} else {

	// 						$status = 'exists';

	// 						$message = $key;

	// 						return json_encode(array("beforeChecked" => 'discountCode', "status" => $status, "message" => $message));
	// 					}
	// 				}



	// 				if (array_key_exists("Free_gift_uponsignup_checkbox", $value)) {

	// 					$checkfreeproductcheckbox = true;

	// 					$Free_gift_uponsignup_checkbox = trim($value["Free_gift_uponsignup_checkbox"]);

	// 					$Free_gift_uponsignup_productName = trim($value["Free_gift_uponsignup_productName"]);

	// 					$gift_uponsignup_variantName = trim($value["gift_uponsignup_variantName"]);

	// 					$free_gift_uponsignupSelectedDays = trim($value["free_gift_uponsignupSelectedDays"]);

	// 					$free_gift_uponsignupSelected_Value = trim($value["free_gift_uponsignupSelected_Value"]);

	// 					$perk_free_gift_product_id = trim($value["perk_free_gift_product_id"]);

	// 					$perk_free_gift_variant_id = trim($value["perk_free_gift_variant_id"]);

	// 					// $free_gift_uponsignup_date = date('Y-m-d', strtotime("+ $free_gift_uponsignupSelected_Value days", strtotime($currentDate)));

	// 					$flag = true;
	// 				}



	// 				if (array_key_exists("custom_perk_checkbox", $value)) {

	// 					$custom_perk_checkbox = trim($value["custom_perk_checkbox"]);

	// 					$flag = true;
	// 				}

	// 				if (array_key_exists("birthday_rewards", $value)) {
	// 					$birthday_rewards = trim($value["birthday_rewards"]);
	// 					$flag = true;
	// 				}

	// 				sleep(1);
	// 			} // foreach loop 1 close





	// 			$InsetFlag = false;

	// 			foreach ($Get_TierValue as $key => $value) {
	// 				// print_r($value);die;
	// 				/*

	//                  * Free shipping

	//                  * Created Discount

	//                  */

	// 				$membership_group_id = trim($value["membership_group_id"]);

	// 				// $member_group_data = DB::table('membership_plan_groups')->where('store', $shop)->where('membership_group_id', $membership_group_id)->first();
	// 				$sql = "SELECT * FROM membership_plan_groups WHERE store = :store AND membership_group_id = :membership_group_id LIMIT 1";
	// 				$stmt = $this->db->prepare($sql);
	// 				$stmt->execute([
	// 					'store' => $shop,
	// 					'membership_group_id' => $membership_group_id
	// 				]);
	// 				// Fetch as object (like Laravel's `first()`)
	// 				$member_group_data = $stmt->fetch(PDO::FETCH_OBJ);
	// 				// $membership_group_id;

	// 				$unique_handle = $member_group_data->unique_handle;

	// 				$value['variant_id'] = $member_group_data->variant_id;



	// 				// Assuming $this->db is your PDO connection and $shop is already defined

	// 				$sql = "SELECT free_shipping_code, discounted_product_collection_code 
	// 						FROM membership_perks 
	// 						WHERE store = :shop";

	// 				$stmt = $this->db->prepare($sql);
	// 				$stmt->execute(['shop' => $shop]);

	// 				// Fetch as objects
	// 				$checkCouponCodes = $stmt->fetchAll(PDO::FETCH_OBJ);




	// 				$coupanCheck = null;



	// 				foreach ($checkCouponCodes as $coupanCode) {

	// 					if ($coupanCode->free_shipping_code) {

	// 						if ($checkeFree_shipping_code == $coupanCode->free_shipping_code) {

	// 							$coupanCheck = 1;

	// 							break;
	// 						}
	// 					} elseif ($coupanCode->discounted_product_collection_code) {

	// 						if ($checkeFree_discountProduct_codeField == $coupanCode->discounted_product_collection_code) {

	// 							$coupanCheck = 1;

	// 							break;
	// 						}
	// 					}
	// 				}



	// 				// $checkCouponCodes

	// 				// $getSegmentId = DB::table('membership_perks')->where('store', $shop)->where('membership_group_id', $membership_group_id)->first();

	// 				$sql = "SELECT * FROM membership_perks 
	// 						WHERE store = :store AND membership_group_id = :membership_group_id 
	// 						LIMIT 1";

	// 				$stmt = $this->db->prepare($sql);
	// 				$stmt->execute([
	// 					':store' => $shop,
	// 					':membership_group_id' => $membership_group_id
	// 				]);

	// 				$getSegmentId = $stmt->fetch(PDO::FETCH_OBJ);


	// 				if ($getSegmentId) {

	// 					// $getSegmentId = DB::table('membership_perks')->where('store', $shop)->where('membership_group_id', $membership_group_id)->first();

	// 					$segmentId = "gid://shopify/Segment/" . $getSegmentId->segment_id;
	// 				} else {

	// 					$segmentFreeGraphQL = 'mutation segmentCreate($segmentName: String!, $segmentQuery: String!) {

	//                         segmentCreate(name: $segmentName, query: $segmentQuery) {

	//                             segment {

	//                                 id

	//                                 name

	//                                 query

	//                             }

	//                             userErrors {

	//                                 message

	//                                 field

	//                             }

	//                         }

	//                     }';

	// 					$segment_random_no = rand(1000, 9999);

	// 					$segmentName = 'shineD-' . $segment_random_no . '-' . $unique_handle;

	// 					$segmentQuery = "customer_tags CONTAINS '$unique_handle'";

	// 					$segmentInput = [

	// 						"segmentName" => $segmentName,

	// 						"segmentQuery" => $segmentQuery,

	// 					];

	// 					$segmentData = $this->shopify_graphql_object->GraphQL->post($segmentFreeGraphQL, null, null, $segmentInput);

	// 					// print_r($segmentInput);

	// 					// print_r($segmentData); die;

	// 					if ($segmentData) {

	// 						$segmentId = $segmentData['data']['segmentCreate']['segment']['id'];

	// 						if (!$segmentId) {

	// 							$segmentId_userErrors = $segmentData['data']['segmentCreate']['userErrors']['message'];

	// 							return json_encode(['message' => $segmentId_userErrors]);
	// 						} else {

	// 							$segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);
	// 						}



	// 						$sql = "INSERT INTO membership_perks (
	// 									membership_group_id, 
	// 									segment_id, 
	// 									store
	// 								) VALUES (
	// 									:membership_group_id, 
	// 									:segment_id, 
	// 									:store
	// 								)
	// 								ON DUPLICATE KEY UPDATE 
	// 									segment_id = VALUES(segment_id),
	// 									store = VALUES(store)";

	// 						$stmt = $this->db->prepare($sql);
	// 						$stmt->execute([
	// 							':membership_group_id' => $membership_group_id,
	// 							':segment_id' => $segment_Id,
	// 							':store' => $shop
	// 						]);
	// 					}

	// 					// die;

	// 				}



	// 				if (array_key_exists("free_shipping_checked_value", $value)) {

	// 					$free_shipping_code = trim($value["free_shipping_code"]);

	// 					$freeshipping_selected_value = trim($value["freeshipping_selected_value"]);

	// 					$perks_type_value = trim($value["perks_type_value"]);





	// 					if ($freeshipping_selected_value == 'min_purchase_amount') {

	// 						$min_purchase_amount_value = trim($value["min_purchase_amount_value"]);

	// 						$input = [

	// 							"freeShippingCodeDiscount" => [

	// 								"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 								'appliesOncePerCustomer' => false,

	// 								"title" => 'membership-app' . '-' . $free_shipping_code,

	// 								"code" => $free_shipping_code,

	// 								"combinesWith" => [

	// 									"orderDiscounts" => true,

	// 									"productDiscounts" => true,

	// 								],

	// 								"minimumRequirement" => [

	// 									"subtotal" => [

	// 										"greaterThanOrEqualToSubtotal" => $min_purchase_amount_value,

	// 									],

	// 								],

	// 								"customerSelection" => [

	// 									"all" => false,

	// 									"customerSegments" => [

	// 										"add" => [$segmentId],

	// 									],

	// 								],

	// 								"destination" => [

	// 									"all" => true,

	// 								],

	// 							],

	// 						];
	// 					} else if ($freeshipping_selected_value == 'min_quantity_items') {

	// 						$max_purchase_amount_value = trim($value["min_quantity_items"]);



	// 						$input = [

	// 							"freeShippingCodeDiscount" => [

	// 								"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 								'appliesOncePerCustomer' => false,

	// 								"title" => 'membership-app' . '-' . $free_shipping_code,

	// 								"code" => $free_shipping_code,

	// 								"combinesWith" => [

	// 									"orderDiscounts" => true,

	// 									"productDiscounts" => true,

	// 								],

	// 								"minimumRequirement" => [

	// 									"quantity" => [

	// 										"greaterThanOrEqualToQuantity" => $max_purchase_amount_value,

	// 									],

	// 								],

	// 								"customerSelection" => [

	// 									"all" => false,

	// 									"customerSegments" => [

	// 										"add" => [$segmentId],

	// 									],

	// 								],

	// 								"destination" => [

	// 									"all" => true,

	// 								],

	// 							],

	// 						];
	// 					} else {



	// 						$input = [

	// 							"freeShippingCodeDiscount" => [

	// 								"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 								'appliesOncePerCustomer' => false,

	// 								"title" => 'membership-app' . '-' . $free_shipping_code,

	// 								"code" => $free_shipping_code,

	// 								"combinesWith" => [

	// 									"orderDiscounts" => true,

	// 									"productDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"all" => false,

	// 									"customerSegments" => [

	// 										"add" => [$segmentId],

	// 									],

	// 								],

	// 								"destination" => [

	// 									"all" => true,

	// 								],

	// 							],

	// 						];
	// 					}



	// 					$freeShipping_Discount = 'mutation discountCodeFreeShippingCreate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!) {

	//                         discountCodeFreeShippingCreate(freeShippingCodeDiscount: $freeShippingCodeDiscount) {

	//                             codeDiscountNode {

	//                                 id

	//                                 codeDiscount {

	//                                 ... on DiscountCodeFreeShipping {

	//                                     title

	//                                     startsAt

	//                                 }

	//                                 }

	//                             }

	//                             userErrors {

	//                                 field

	//                                 code

	//                                 message

	//                             }

	//                             }

	//                     }';



	// 					$data = $this->shopify_graphql_object->GraphQL->post($freeShipping_Discount, null, null, $input);



	// 					if ($data) {

	// 						$freeshipCodeId = $data['data']['discountCodeFreeShippingCreate']['codeDiscountNode']['id'];

	// 						$freeshipCodeId = str_replace("gid://shopify/DiscountCodeNode/", "", $freeshipCodeId);

	// 						$value['freeshipping_pricerule_id'] = $freeshipCodeId;

	// 						$segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);

	// 						$value['segment_id'] = $segment_Id;
	// 					}
	// 				}



	// 				/*

	//                  * checked product collection Discount

	//                  * code created

	//                  */

	// 				if (array_key_exists("discounted_product_collection_checked_value", $value)) {



	// 					$discounted_product_collection_code = trim($value["discounted_product_collection_code"]);

	// 					$discounted_product_collection_percentageoff = trim($value["discounted_product_collection_percentageoff"]);

	// 					$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);

	// 					$perks_type_value = trim($value["perks_type_value"]);



	// 					if ($discounted_product_collection_type == 'C') {



	// 						$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);

	// 						$discounted__collection_title = trim($value["discounted__collection_title"]);

	// 						$discounted__collection_id = trim($value["discounted__collection_id"]);



	// 						$input = [

	// 							"priceRule" => [

	// 								"title" => "membership-app" . '-' . $discounted_product_collection_code,

	// 								"validityPeriod" => [

	// 									"start" => date("Y-m-d"),

	// 								],

	// 								"value" => [

	// 									"percentageValue" => -1 * $discounted_product_collection_percentageoff,

	// 								],

	// 								"target" => "LINE_ITEM",

	// 								"allocationMethod" => "ACROSS",

	// 								"combinesWith" => [

	// 									"shippingDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"forAllCustomers" => false,

	// 									"segmentIds" => [

	// 										$segmentId,

	// 									],

	// 								],

	// 								"itemEntitlements" => [

	// 									"collectionIds" => [

	// 										$discounted__collection_id,

	// 									],

	// 								],

	// 								// "usageLimit" => 1,

	// 							],

	// 							"priceRuleDiscountCode" => [

	// 								"code" => $discounted_product_collection_code,

	// 							],

	// 						];
	// 					} else if ($discounted_product_collection_type == 'P') {



	// 						$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);

	// 						$discounted__product_title = trim($value["discounted__product_title"]);

	// 						$discounted__product_id = trim($value["discounted__product_id"]);



	// 						$input = [

	// 							"priceRule" => [

	// 								"title" => "membership-app" . '-' . $discounted_product_collection_code,

	// 								"validityPeriod" => [

	// 									"start" => date("Y-m-d"),

	// 								],

	// 								"value" => [

	// 									"percentageValue" => -1 * $discounted_product_collection_percentageoff,

	// 								],

	// 								"target" => "LINE_ITEM",

	// 								"allocationMethod" => "ACROSS",

	// 								"combinesWith" => [

	// 									"shippingDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"forAllCustomers" => false,

	// 									"segmentIds" => [

	// 										$segmentId,

	// 									],

	// 								],

	// 								"itemEntitlements" => [

	// 									"productIds" => [

	// 										$discounted__product_id,

	// 									],

	// 								],

	// 								// "usageLimit" => 1,

	// 							],

	// 							"priceRuleDiscountCode" => [

	// 								"code" => $discounted_product_collection_code,

	// 							],

	// 						];
	// 					} else {

	// 						$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);



	// 						$input = [

	// 							"priceRule" => [

	// 								"title" => "membership-app" . '-' . $discounted_product_collection_code,

	// 								"validityPeriod" => [

	// 									"start" => date("Y-m-d"),

	// 								],

	// 								"value" => [

	// 									"percentageValue" => -1 * $discounted_product_collection_percentageoff,

	// 								],

	// 								"target" => "LINE_ITEM",

	// 								"allocationMethod" => "ACROSS",

	// 								"combinesWith" => [

	// 									"shippingDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"forAllCustomers" => false,

	// 									"segmentIds" => [

	// 										$segmentId,

	// 									],

	// 								],

	// 								"itemEntitlements" => [

	// 									"targetAllLineItems" => true,

	// 								],

	// 								// "usageLimit" => 1,

	// 							],

	// 							"priceRuleDiscountCode" => [

	// 								"code" => $discounted_product_collection_code,

	// 							],

	// 						];
	// 					}



	// 					$discountCodeApplied = 'mutation priceRuleCreate($priceRule: PriceRuleInput!, $priceRuleDiscountCode: PriceRuleDiscountCodeInput!) {

	//                         priceRuleCreate(priceRule: $priceRule, priceRuleDiscountCode: $priceRuleDiscountCode) {

	//                             priceRule {

	//                                 id

	//                                 allocationLimit

	//                             }

	//                             priceRuleDiscountCode {

	//                                 id

	//                                 code

	//                                 usageCount

	//                             }

	//                             priceRuleUserErrors {

	//                                 code

	//                                 field

	//                                 message

	//                             }

	//                             }

	//                         }

	//                     ';

	// 					$data = $this->shopify_graphql_object->GraphQL->post($discountCodeApplied, null, null, $input);

	// 					// die($data);

	// 					if ($data) {

	// 						$discountCodeId = $data['data']['priceRuleCreate']['priceRule']['id'];

	// 						$discountCodeId = str_replace("gid://shopify/PriceRule/", "", $discountCodeId);

	// 						$value['discounted_product_collection_price_rule_id'] = $discountCodeId;

	// 						$segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);

	// 						$value['segment_id'] = $segment_Id;
	// 					}
	// 				}



	// 				/**

	// 				 * checked the free

	// 				 *

	// 				 */

	// 				// $free_gift_uponsignup_date = false;



	// 				if (array_key_exists("Free_gift_uponsignup_checkbox", $value)) {

	// 					$Free_gift_uponsignup_checkbox = trim($value["Free_gift_uponsignup_checkbox"]);

	// 					$Free_gift_uponsignup_productName = trim($value["Free_gift_uponsignup_productName"]);

	// 					$gift_uponsignup_variantName = trim($value["gift_uponsignup_variantName"]);

	// 					$free_gift_uponsignupSelectedDays = trim($value["free_gift_uponsignupSelectedDays"]);

	// 					$free_gift_uponsignupSelected_Value = trim($value["free_gift_uponsignupSelected_Value"]);

	// 					$perk_free_gift_product_id = trim($value["perk_free_gift_product_id"]);

	// 					$perk_free_gift_variant_id = trim($value["perk_free_gift_variant_id"]);

	// 					// $free_gift_uponsignup_date = date('Y-m-d', strtotime("+ $free_gift_uponsignupSelected_Value days", strtotime($currentDate)));

	// 					// $segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);

	// 					// $value['segment_id'] = $segment_Id;



	// 				}
	// 				/*

	//                  * checked Sale Product/Collection 

	//                  * code created

	//                  */

	// 				if (array_key_exists("early_access_checked_value", $value)) {
	// 					$earlySaleAccess_checkbox = trim($value["early_access_checked_value"]);
	// 					$earlySaleAccess_days = trim($value["no_of_sale_days"]);
	// 				}

	// 				/** early_access_collection_id

	// 				 * checked the free

	// 				 *

	// 				 */






	// 				/**

	// 				 * checked the free

	// 				 *

	// 				 */

	// 				if (array_key_exists("custom_perk_checkbox", $value)) {

	// 					$check_custom_perk_checkbox = trim($value["custom_perk_checkbox"]);
	// 				}

	// 				if (array_key_exists("birthday_rewards", $value)) {

	// 					$check_birthday_rewards = trim($value["birthday_rewards"]);
	// 				}


	// 				$whereCondition = [
	// 					'membership_group_id' => $value['membership_group_id'],
	// 					'store' => $this->store
	// 				];

	// 				$insertValue = $this->update_row('membership_perks', $value, $whereCondition, "AND");
	// 				if ($checkfreeproductcheckbox) {

	// 					$sql = "UPDATE membership_perks  SET free_gift_uponsignupSelected_Value = :free_gift_uponsignupSelected_Value WHERE membership_group_id = :membership_group_id";
	// 					$stmt = $this->db->prepare($sql);
	// 					$stmt->execute([
	// 						':free_gift_uponsignupSelected_Value' => $free_gift_uponsignupSelected_Value,
	// 						':membership_group_id' => $membership_group_id
	// 					]);
	// 				}



	// 				$InsetFlag = true;

	// 				sleep(1);
	// 			}



	// 			if ($InsetFlag == true) {

	// 				$status = false;

	// 				$message = "Discount Code Created successfully!";

	// 				$this->upsertMetaObject();

	// 				return json_encode(['status' => $status, 'message' => $message]);
	// 			} else {

	// 				$status = false;

	// 				$message = "Something went wrong!";

	// 				return json_encode(['status' => $status, 'message' => $message]);
	// 			}
	// 		}
	// 	}
	// }

	public function membershipPerksSave($data)
	{


		$currentDate = date('Y-m-d');

		$RequestedShop_Name = $this->store;
		$shopName = $this->store;
		$GetPerks_Values = $data['data'];

		if (!empty($GetPerks_Values)) {

			$Get_TierValue = $GetPerks_Values["obj"];

			if (sizeof($Get_TierValue)) {

				$flag = false;



				foreach ($Get_TierValue as $key => $value) {

					$checkfreeproductcheckbox = false;

					$shop = $this->store;

					$checkeFree_shipping_code = '';

					$checkeFree_discountProduct_codeField = '';

					if (array_key_exists("free_shipping_code", $value)) {

						/*

                         * checked Discount

                         * code

                         */

						$checkeFree_shipping_code = trim($value["free_shipping_code"]);



						$response = $this->checkCodeExist($checkeFree_shipping_code);
						if ($response['errors']) {

							$flag = true;
						} else {

							$status = 'exists';

							$message = $key;

							return json_encode(array("beforeChecked" => 'free_shipping_code', "status" => $status, "message" => $message));
						}
					}



					if (array_key_exists("discounted_product_collection_code", $value)) {



						/*

                         * checked product

                         *  discount code

                         */

						$checkeFree_discountProduct_codeField = trim($value["discounted_product_collection_code"]);



						$response = $this->checkCodeExist($checkeFree_discountProduct_codeField);



						if (isset($response['errors'])) {

							$flag = true;
						} else {

							$status = 'exists';

							$message = $key;

							return json_encode(array("beforeChecked" => 'discountCode', "status" => $status, "message" => $message));
						}
					}



					if (array_key_exists("Free_gift_uponsignup_checkbox", $value)) {

						$checkfreeproductcheckbox = true;

						$Free_gift_uponsignup_checkbox = trim($value["Free_gift_uponsignup_checkbox"]);

						$Free_gift_uponsignup_productName = trim($value["Free_gift_uponsignup_productName"]);

						$gift_uponsignup_variantName = trim($value["gift_uponsignup_variantName"]);

						$free_gift_uponsignupSelectedDays = trim($value["free_gift_uponsignupSelectedDays"]);

						$free_gift_uponsignupSelected_Value = trim($value["free_gift_uponsignupSelected_Value"]);

						$perk_free_gift_product_id = trim($value["perk_free_gift_product_id"]);

						$perk_free_gift_variant_id = trim($value["perk_free_gift_variant_id"]);

						$flag = true;
					}



					if (array_key_exists("custom_perk_checkbox", $value)) {

						$custom_perk_checkbox = trim($value["custom_perk_checkbox"]);

						$flag = true;
					}

					if (array_key_exists("birthday_rewards", $value)) {
						$birthday_rewards = trim($value["birthday_rewards"]);
						$flag = true;
					}

					sleep(1);
				} // foreach loop 1 close





				$InsetFlag = false;

				foreach ($Get_TierValue as $key => $value) {
					// print_r($value);die;
					/*

                     * Free shipping

                     * Created Discount

                     */

					$membership_group_id = trim($value["membership_group_id"]);

					$sql = "SELECT * FROM membership_plan_groups WHERE store = :store AND membership_group_id = :membership_group_id LIMIT 1";
					$stmt = $this->db->prepare($sql);
					$stmt->execute([
						'store' => $shop,
						'membership_group_id' => $membership_group_id
					]);
					// Fetch as object (like Laravel's `first()`)
					$member_group_data = $stmt->fetch(PDO::FETCH_OBJ);

					$unique_handle = $member_group_data->unique_handle;

					$value['variant_id'] = $member_group_data->variant_id;



					// Assuming $pdo is your PDO connection and $shop is already defined

					$sql = "SELECT free_shipping_code, discounted_product_collection_code 
							FROM membership_perks 
							WHERE store = :shop";

					$stmt = $this->db->prepare($sql);
					$stmt->execute(['shop' => $shop]);

					// Fetch as objects
					$checkCouponCodes = $stmt->fetchAll(PDO::FETCH_OBJ);




					$coupanCheck = null;



					foreach ($checkCouponCodes as $coupanCode) {

						if ($coupanCode->free_shipping_code) {

							if ($checkeFree_shipping_code == $coupanCode->free_shipping_code) {

								$coupanCheck = 1;

								break;
							}
						} elseif ($coupanCode->discounted_product_collection_code) {

							if ($checkeFree_discountProduct_codeField == $coupanCode->discounted_product_collection_code) {

								$coupanCheck = 1;

								break;
							}
						}
					}



					// $checkCouponCodes


					$sql = "SELECT * FROM membership_perks 
							WHERE store = :store AND membership_group_id = :membership_group_id 
							LIMIT 1";

					$stmt = $this->db->prepare($sql);
					$stmt->execute([
						':store' => $shop,
						':membership_group_id' => $membership_group_id
					]);

					$getSegmentId = $stmt->fetch(PDO::FETCH_OBJ);


					if ($getSegmentId) {


						$segmentId = "gid://shopify/Segment/" . $getSegmentId->segment_id;
					} else {

						$segmentFreeGraphQL = 'mutation segmentCreate($segmentName: String!, $segmentQuery: String!) {

                            segmentCreate(name: $segmentName, query: $segmentQuery) {

                                segment {

                                    id

                                    name

                                    query

                                }

                                userErrors {

                                    message

                                    field

                                }

                            }

                        }';

						$segment_random_no = rand(1000, 9999);

						$segmentName = 'shineD-' . $segment_random_no . '-' . $unique_handle;

						$segmentQuery = "customer_tags CONTAINS '$unique_handle'";

						$segmentInput = [

							"segmentName" => $segmentName,

							"segmentQuery" => $segmentQuery,

						];

						$segmentData = $this->shopify_graphql_object->GraphQL->post($segmentFreeGraphQL, null, null, $segmentInput);

						if ($segmentData) {

							$segmentId = $segmentData['data']['segmentCreate']['segment']['id'];

							if (!$segmentId) {

								$segmentId_userErrors = $segmentData['data']['segmentCreate']['userErrors']['message'];

								return json_encode(['message' => $segmentId_userErrors]);
							} else {

								$segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);
							}



							$sql = "INSERT INTO membership_perks (
										membership_group_id, 
										segment_id, 
										store
									) VALUES (
										:membership_group_id, 
										:segment_id, 
										:store
									)
									ON DUPLICATE KEY UPDATE 
										segment_id = VALUES(segment_id),
										store = VALUES(store)";

							$stmt = $this->db->prepare($sql);
							$stmt->execute([
								':membership_group_id' => $membership_group_id,
								':segment_id' => $segment_Id,
								':store' => $shop
							]);
						}
					}



					if (array_key_exists("free_shipping_checked_value", $value)) {

						$free_shipping_code = trim($value["free_shipping_code"]);

						$freeshipping_selected_value = trim($value["freeshipping_selected_value"]);

						$perks_type_value = trim($value["perks_type_value"]);





						if ($freeshipping_selected_value == 'min_purchase_amount') {

							$min_purchase_amount_value = trim($value["min_purchase_amount_value"]);

							$input = [

								"freeShippingCodeDiscount" => [

									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

									'appliesOncePerCustomer' => false,

									"title" => 'membership-app' . '-' . $free_shipping_code,

									"code" => $free_shipping_code,

									"combinesWith" => [

										"orderDiscounts" => true,

										"productDiscounts" => true,

									],

									"minimumRequirement" => [

										"subtotal" => [

											"greaterThanOrEqualToSubtotal" => $min_purchase_amount_value,

										],

									],

									"customerSelection" => [

										"all" => false,

										"customerSegments" => [

											"add" => [$segmentId],

										],

									],

									"destination" => [

										"all" => true,

									],

								],

							];
						} else if ($freeshipping_selected_value == 'min_quantity_items') {

							$max_purchase_amount_value = trim($value["min_quantity_items"]);



							$input = [

								"freeShippingCodeDiscount" => [

									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

									'appliesOncePerCustomer' => false,

									"title" => 'membership-app' . '-' . $free_shipping_code,

									"code" => $free_shipping_code,

									"combinesWith" => [

										"orderDiscounts" => true,

										"productDiscounts" => true,

									],

									"minimumRequirement" => [

										"quantity" => [

											"greaterThanOrEqualToQuantity" => $max_purchase_amount_value,

										],

									],

									"customerSelection" => [

										"all" => false,

										"customerSegments" => [

											"add" => [$segmentId],

										],

									],

									"destination" => [

										"all" => true,

									],

								],

							];
						} else {



							$input = [

								"freeShippingCodeDiscount" => [

									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

									'appliesOncePerCustomer' => false,

									"title" => 'membership-app' . '-' . $free_shipping_code,

									"code" => $free_shipping_code,

									"combinesWith" => [

										"orderDiscounts" => true,

										"productDiscounts" => true,

									],

									"customerSelection" => [

										"all" => false,

										"customerSegments" => [

											"add" => [$segmentId],

										],

									],

									"destination" => [

										"all" => true,

									],

								],

							];
						}



						$freeShipping_Discount = 'mutation discountCodeFreeShippingCreate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!) {

                            discountCodeFreeShippingCreate(freeShippingCodeDiscount: $freeShippingCodeDiscount) {

                                codeDiscountNode {

                                    id

                                    codeDiscount {

                                    ... on DiscountCodeFreeShipping {

                                        title

                                        startsAt

                                    }

                                    }

                                }

                                userErrors {

                                    field

                                    code

                                    message

                                }

                                }

                        }';



						$data = $this->shopify_graphql_object->GraphQL->post($freeShipping_Discount, null, null, $input);



						if ($data) {

							$freeshipCodeId = $data['data']['discountCodeFreeShippingCreate']['codeDiscountNode']['id'];

							$freeshipCodeId = str_replace("gid://shopify/DiscountCodeNode/", "", $freeshipCodeId);

							$value['freeshipping_pricerule_id'] = $freeshipCodeId;

							$segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);

							$value['segment_id'] = $segment_Id;
						}
					}



					/*

                     * checked product collection Discount

                     * code created

                     */

					if (array_key_exists("discounted_product_collection_checked_value", $value)) {


						$discounted_product_collection_code = trim($value["discounted_product_collection_code"]);

						$discounted_product_collection_percentageoff = trim($value["discounted_product_collection_percentageoff"]);

						$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);

						$perks_type_value = trim($value["perks_type_value"]);



						if ($discounted_product_collection_type == 'C') {

							$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);
							$discounted__collection_title = trim($value["discounted__collection_title"]);
							$discounted__collection_id = trim($value["discounted__collection_id"]);
							$discounted__product_id = trim($value["discounted__product_id"]);
							$input = [
								'basicCodeDiscount' => [
									'title' => "membership-app-" . $discounted_product_collection_code,
									'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
									'code' =>  $discounted_product_collection_code,
									'customerSelection' => [
										'all' => false,
										'customerSegments' => [
											'add' => [$segmentId],
										],
									],
									'customerGets' => [
										'value' => [
											'percentage' => 1 * ($discounted_product_collection_percentageoff / 100)
										],
										'items' => [
											'collections' => [
												'add' => [$discounted__collection_id]
											],
										],
									],
									'appliesOncePerCustomer' => false,
									'combinesWith' => [
										'shippingDiscounts' => true,
									],
								]
							];
						} else if ($discounted_product_collection_type == 'P') {

							$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);

							$discounted__product_title = trim($value["discounted__product_title"]);

							$discounted__product_id = trim($value["discounted__product_id"]);

							$input = [
								'basicCodeDiscount' => [
									'title' => "membership-app-" . $discounted_product_collection_code,
									'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
									'code' =>  $discounted_product_collection_code,
									'customerSelection' => [
										'all' => false,
										'customerSegments' => [
											'add' => [$segmentId],
										],
									],
									'customerGets' => [
										'value' => [
											'percentage' => 1 * ($discounted_product_collection_percentageoff / 100)
										],
										'items' => [
											'products' => [
												'productsToAdd' => [$discounted__product_id]
											],
										],
									],
									'appliesOncePerCustomer' => false,
									'combinesWith' => [
										'shippingDiscounts' => true,
									],
								]
							];
						} else {

							$discounted_product_collection_type = trim($value["discounted_product_collection_type"]);

							$input = [
								'basicCodeDiscount' => [
									'title' => "membership-app-" . $discounted_product_collection_code,
									'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
									'code' =>  $discounted_product_collection_code,
									'customerSelection' => [
										'all' => false,
										'customerSegments' => [
											'add' => [$segmentId],
										],
									],
									'customerGets' => [
										'value' => [
											'percentage' => 1 * ($discounted_product_collection_percentageoff / 100)
										],
										'items' => [
											"all" => true,
										],
									],
									'appliesOncePerCustomer' => false,
									'combinesWith' => [
										'shippingDiscounts' => true,
									],
								]
							];
						}

						$discountCodeApplied =
							'mutation CreateDiscountCode($basicCodeDiscount: DiscountCodeBasicInput!) {
                                discountCodeBasicCreate(basicCodeDiscount: $basicCodeDiscount) {
									codeDiscountNode {
									id
									codeDiscount {
										... on DiscountCodeBasic {
										title
										startsAt
										endsAt
										customerSelection {
											... on DiscountCustomers {
											customers {
												id
											}
											}
										}
										customerGets {
											value {
											... on DiscountPercentage {
												percentage
											}
											}
										}
										}
									}
									}
									userErrors {
									field
									message
									}
								}
                            }
						';

						$data = $this->shopify_graphql_object->GraphQL->post($discountCodeApplied, null, null, $input);

						if ($data) {
							$discountCodeId = $data['data']['discountCodeBasicCreate']['codeDiscountNode']['id'];
							$discountCodeId = str_replace("gid://shopify/DiscountCodeNode/", "", $discountCodeId);
							$value['discounted_product_collection_price_rule_id'] = $discountCodeId;
							$segment_Id = str_replace("gid://shopify/Segment/", "", $segmentId);
							$value['segment_id'] = $segment_Id;
						}
					}



					/**

					 * checked the free

					 *

					 */

					// $free_gift_uponsignup_date = false;



					if (array_key_exists("Free_gift_uponsignup_checkbox", $value)) {

						$Free_gift_uponsignup_checkbox = trim($value["Free_gift_uponsignup_checkbox"]);

						$Free_gift_uponsignup_productName = trim($value["Free_gift_uponsignup_productName"]);

						$gift_uponsignup_variantName = trim($value["gift_uponsignup_variantName"]);

						$free_gift_uponsignupSelectedDays = trim($value["free_gift_uponsignupSelectedDays"]);

						$free_gift_uponsignupSelected_Value = trim($value["free_gift_uponsignupSelected_Value"]);

						$perk_free_gift_product_id = trim($value["perk_free_gift_product_id"]);

						$perk_free_gift_variant_id = trim($value["perk_free_gift_variant_id"]);
					}

					/*

                     * checked Sale Product/Collection 

                     * code created

                     */

					if (array_key_exists("early_access_checked_value", $value)) {
						$earlySaleAccess_checkbox = trim($value["early_access_checked_value"]);
						$earlySaleAccess_days = trim($value["no_of_sale_days"]);
					}

					/** early_access_collection_id

					 * checked the free

					 *

					 */






					/**

					 * checked the free

					 *

					 */

					if (array_key_exists("custom_perk_checkbox", $value)) {

						$check_custom_perk_checkbox = trim($value["custom_perk_checkbox"]);
					}

					if (array_key_exists("birthday_rewards", $value)) {

						$check_birthday_rewards = trim($value["birthday_rewards"]);
					}


					$whereCondition = [
						'membership_group_id' => $value['membership_group_id'],
						'store' => $this->store
					];

					$insertValue = $this->update_row('membership_perks', $value, $whereCondition, "AND");
					if ($checkfreeproductcheckbox) {

						$sql = "UPDATE membership_perks  SET free_gift_uponsignupSelected_Value = :free_gift_uponsignupSelected_Value WHERE membership_group_id = :membership_group_id";
						$stmt = $this->db->prepare($sql);
						$stmt->execute([
							':free_gift_uponsignupSelected_Value' => $free_gift_uponsignupSelected_Value,
							':membership_group_id' => $membership_group_id
						]);
					}



					$InsetFlag = true;

					sleep(1);
				}



				if ($InsetFlag == true) {

					$status = false;

					$message = "Discount Code Created successfully!";

					$this->upsertMetaObject();

					return json_encode(['status' => $status, 'message' => $message]);
				} else {

					$status = false;

					$message = "Something went wrong!";

					return json_encode(['status' => $status, 'message' => $message]);
				}
			}
		}
	}
	public function checkCoupansCode($data)
	{
		// Get input from POST request
		// $RequestedShop_Name = $_POST['store'] ?? '';
		// print_r($data);
		$checkeFree_shipping_code = $data['data']['freeshipCoupanCode'] ?? '';
		$checkeFree_discountProduct_codeField = $data['data']['discountCoupanCode'] ?? '';

		// Get store details (Assumes you have a custom function for this)
		// $storeDetails = getStoreByDomain($RequestedShop_Name);

		// Shopify config


		// Initialize Shopify SDK
		// $shopify = new ShopifySDK($config); // Ensure ShopifySDK is included and available

		$responseData = ['status' => false];

		// Check for free shipping code
		if (!empty($checkeFree_shipping_code)) {
			try {
				$response = $this->checkCodeExist($checkeFree_shipping_code);
				if ($response['errors']) {
					$responseData = ['status' => false, 'type' => 'freeShipExists', 'code' => $checkeFree_shipping_code];
				} else {
					$responseData = ['status' => true, 'type' => 'freeShipExists', 'code' => $checkeFree_shipping_code];
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}

			// $headers = $this->getShopifyHeadersForStore($this->access_token);

			// $response = $this->makeAnAPICallToShopify('GET', $endpoint, null, $headers);
			// if ($response['statusCode'] == '200') {
			// 	$responseData = ['status' => true, 'type' => 'freeShipExists', 'code' => $checkeFree_shipping_code];
			// } else {
			// 	$responseData = ['status' => false, 'type' => 'freeShipExists', 'code' => $checkeFree_shipping_code];
			// }
		}

		// Check for discount code
		if (!empty($checkeFree_discountProduct_codeField)) {

			$response = $this->checkCodeExist($checkeFree_shipping_code);
			if ($response['errors']) {
				$responseData = ['status' => false, 'type' => 'discountExists', 'code' => $checkeFree_discountProduct_codeField];
			} else {
				$responseData = ['status' => true, 'type' => 'discountExists', 'code' => $checkeFree_discountProduct_codeField];
			}
		}

		// Return JSON response
		// header('Content-Type: application/json');
		// echo json_encode($responseData);
		// exit;
		return json_encode($responseData);
	}
	public function checkCodeExist($code)
	{
		$endpoint = $this->getShopifyURLForStore("discount_codes/lookup.json?code={$code}");
		$response = $this->PostPutApi($endpoint, 'GET', $this->access_token, '');
		return $response;
	}


	public function delete_member_plan($data)
	{

		$shop = $this->store;
		// $get_delete_member_data = json_decode($request->get('ajaxData'));
		$member_product_id = $data['data']['member_product_id'];
		// echo '<pre>';
		// print_r($get_delete_member_data);
		// die;
		$member_plan_groups = $data['data']['member_group_ids'];
		$member_plan_id = $data['data']['member_plan_id'];
		$member_plan_groups_array = explode(",", $member_plan_groups);

		$sql = "SELECT segment_id FROM membership_perks WHERE store = :store AND membership_plan_id = :membership_plan_id LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':store' => $shop,
			':membership_plan_id' => $member_plan_id
		]);
		$member_perk_segment_ids = $stmt->fetchColumn(); // gets the single value


	$member_perk_segment_ids = array_filter(
		explode(",", $member_perk_segment_ids ?? ''),
		fn($value) => $value !== ''
	);

	$member_perk_freeshipping_ids = array_filter(
		explode(",", $data['data']['member_perk_freeshipping_ids'] ?? ''),
		fn($value) => $value !== ''
	);

	$member_perk_pricerule_ids = array_filter(
		explode(",", $data['data']['member_perk_pricerule_ids'] ?? ''),
		fn($value) => $value !== ''
	);

		// if (!empty($member_perk_freeshipping_ids) || !empty($member_perk_pricerule_ids) || !empty($member_perk_segment_ids)) {
		// 	$delete_membership_perk_data = $this->makeADeleteCallToPerkData($member_perk_freeshipping_ids, $member_perk_pricerule_ids, $member_perk_segment_ids);
		// }

		// foreach ($member_plan_groups_array as $key => $group_id) {

		// echo $group_id;
		// $delete_member_group_data = $this->delete_member_plan_group($group_id, $shop , $member_plan_id);
		// }

		try {
			$sql = "UPDATE membership_plans SET plan_status = :status WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':status' => 'disable',
				':id' => $member_plan_id
			]);


			// DB::table('membership_plan_groups')
			// ->where('store', '=', $shop)
			// ->where('membership_plan_id', '=', $member_plan_id)
			// ->delete();

			// MembershipPlan::where('id', $member_plan_id)->delete();
			// MembershipPerk::where('membership_plan_id', $member_plan_id)->delete();

			// $membershipOptionId = DB::table('membership_groups_details')->where('store', $shop)->where('membership_plan_id', $member_plan_id)->value('membership_option_id');
			// echo '<pre>';
			// print_r($membershipOptionId);
			// die;

			// MembershipGroupsDetail::where('membership_plan_id', $member_plan_id)->delete();

			// DB::table('purchased_membership_details')->where('store', $shop)->where('product_id', $member_product_id)->update(['plan_status' => 'D']);

			// if ($get_delete_member_data->member_product_type == 'custom_product') {
			// $product_id = $get_delete_member_data['member_product_id'];
			$product_delete_status = $this->delete_product($member_product_id);
			// } else {
			//     // echo $get_delete_member_data->member_product_type;
			// }
			$this->upsertMetaObject();
			return json_encode(array("isError" => false, 'message' => 'Member plan deleted successfully!')); // return json

		} catch (Exception $e) {
			$this->upsertMetaObject();

			return json_encode(array("isError" => true, 'error' => $e->getMessage(), 'message' => 'Something went wrong')); // return json
		}
	}

	public function makeADeleteCallToPerkData($member_perk_freeshipping_ids, $member_perk_pricerule_ids, $member_perk_segment_ids)
	{

		if (!empty($member_perk_freeshipping_ids)) {
			foreach ($member_perk_freeshipping_ids as $key => $freeshipping_id) {

				if (is_numeric($freeshipping_id)) {
					$freeshipping_id = "gid://shopify/DiscountCodeNode/" . $freeshipping_id;
				}

				$Delete_Free_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {
                discountCodeDelete(id: $id) {
                        deletedCodeDiscountId
                        userErrors {
                          field
                          message
                        }
                      }
                    }';
				$Delete_freeshipping_inputvalue = ["id" => $freeshipping_id];
				$get_Delete_Free_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_Free_shipping_discountCode, null, null, $Delete_freeshipping_inputvalue);
			}
		}
		if (!empty($member_perk_pricerule_ids)) {
			foreach ($member_perk_pricerule_ids as $key => $discount_productCollection_rule_ids) {

				if (is_numeric($discount_productCollection_rule_ids)) {
					$discount_productCollection_rule_ids = "gid://shopify/PriceRule/" . $discount_productCollection_rule_ids;
				}

				$DeleteDiscountCodeProductcollection = 'mutation priceRuleDelete($id: ID!) {
                priceRuleDelete(id: $id) {
                    priceRuleUserErrors {
                      field
                      message
                      code
                    }
                    deletedPriceRuleId
                  }
                }';
				$Delete_productcollection_discountCode_checked_input = ["id" => $discount_productCollection_rule_ids];
				$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post($DeleteDiscountCodeProductcollection, null, null, $Delete_productcollection_discountCode_checked_input);
			}
		}
		if (!empty($member_perk_segment_ids)) {
			foreach ($member_perk_segment_ids as $segment_id) {
				$segment_id = "gid://shopify/Segment/" . $segment_id;
				$DeleteSegment = 'mutation segmentDelete($id: ID!) {
                        segmentDelete(id: $id) {
                            deletedSegmentId
                            userErrors {
                                field
                                message
                            }
                        }
                    }';

				$Delete_segment_checked_input = ["id" => $segment_id];
				$get_Delete_segment_ID = $this->shopify_graphql_object->GraphQL->post($DeleteSegment, null, null, $Delete_segment_checked_input);
			}
		}

		return true;
	}

	public function delete_product($product_id)
	{
		try {
			$delete_product = 'mutation {
				productDelete(
					input: {
						id: "gid://shopify/Product/' . $product_id . '"
					}
				) {
					deletedProductId
					shop {
						id
					}
					userErrors {
						field
						message
					}
				}
			}';
			$deleteMemberProduct_execution = $this->shopify_graphql_object->GraphQL->post($delete_product);
			$memberPlanProductDelete_error = $deleteMemberProduct_execution['data']['productDelete']['userErrors'];
			if (count($memberPlanProductDelete_error)) {
				return $memberPlanProductDelete_error;
			} else {
				return 'success';
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	// update perks data perks_update old
	// public function membershipPerksUpdate($request)
	// {

	// 	$RequestedShop_Name = $this->store;;
	// 	// $storeDetails = $this->getStoreDetailsByDomain($RequestedShop_Name);
	// 	$shopName = $this->store;
	// 	$currentDate = date('Y-m-d');
	// 	$updatePerks_Values = $request;

	// 	// echo '<pre>';
	// 	// print_r($request);
	// 	// die;

	// 	if (!empty($updatePerks_Values)) {

	// 		$obj_values = $updatePerks_Values["obj"];
	// 		$flag = false;

	// 		$editTierValues = $obj_values['edit_Tier_Value'] ?? [];

	// 		$freeShippingArr = $obj_values['free_shipping'] ?? [];
	// 		$discountCollectionArr = $obj_values['discount_product_collection'] ?? [];

	// 		for ($count = 0; $count < count($editTierValues); $count++) {

	// 			$global_tier_value = $editTierValues[$count]['edit_Tier_Value'];

	// 			// Use customQuery for SELECT
	// 			$query = "SELECT * FROM membership_plan_groups WHERE store = '$RequestedShop_Name' AND membership_group_id = '$global_tier_value' LIMIT 1";
	// 			$results = $this->customQuery($query);

	// 			$member_group_data = $results[0] ?? null;

	// 			if (!$member_group_data) {
	// 				continue;
	// 			}

	// 			$unique_handle = $member_group_data['unique_handle'];

	// 			$edit_free_shipping_checked_value = $freeShippingArr[$count]['edit_free_shipping_checked_value'] ?? 0;
	// 			$edit_discounted_product_collection_checked_value = $discountCollectionArr[$count]['edit_discounted_product_collection_checked_value'] ?? 0;

	// 			// ========== FREE SHIPPING PERK ==========
	// 			if ($edit_free_shipping_checked_value == 1) {
	// 				$edit_shipping_code_value = $freeShippingArr[$count]['edit_shipping_code_value'] ?? '';
	// 				$old_edit_free_shipingCode = $freeShippingArr[$count]['old_edit_free_shipingCode'] ?? '';

	// 				if ($edit_shipping_code_value !== $old_edit_free_shipingCode) {
	// 					try {
	// 						$response = $this->checkCodeExist($edit_shipping_code_value);
	// 						if (!$response['errors']) {

	// 							$responseData = [
	// 								"status" => "exists",
	// 								"message" => $global_tier_value,
	// 								"check_perk_value" => "free_shipping_perk"
	// 							];
	// 							return json_encode($responseData);
	// 						}
	// 					} catch (Exception $e) {
	// 						echo $e->getMessage();
	// 					}
	// 				}

	// 				$flag = true;
	// 			} else {
	// 				$flag = true;
	// 			}

	// 			// ========== DISCOUNT COLLECTION PERK ==========
	// 			if ($edit_discounted_product_collection_checked_value == 1) {
	// 				$new_code = $discountCollectionArr[$count]['product_collectionCode_Field_code_value'] ?? '';
	// 				$old_code = $discountCollectionArr[$count]['old_Edit_DiscountProductCode_Field'] ?? '';

	// 				if ($new_code !== $old_code) {

	// 					try {
	// 						$response = $this->checkCodeExist($new_code);
	// 						if (!$response['errors']) {

	// 							return json_encode([
	// 								"status" => "exists",
	// 								"message" => $global_tier_value,
	// 								"check_perk_value" => "productCollection_perk"
	// 							]);
	// 						}
	// 					} catch (Exception $e) {
	// 						echo $e->getMessage();
	// 					}
	// 				}

	// 				$flag = true;
	// 			} else {
	// 				$flag = true;
	// 			}

	// 			// Add delay for rate limiting
	// 			sleep(1);
	// 		}

	// 		/**

	// 		 * IF codtion is True

	// 		 * for all tier

	// 		 **/

	// 		if ($flag == true) {

	// 			$saveStatus = false;

	// 			for ($count = 0; $count < count($obj_values['edit_Tier_Value']); $count++) {



	// 				$global_tier_value = $obj_values['edit_Tier_Value'][$count]['edit_Tier_Value'];

	// 				$query = "SELECT segment_id FROM membership_perks WHERE store = '{$RequestedShop_Name}' AND membership_group_id = '{$global_tier_value}' LIMIT 1";

	// 				$result = $this->customQuery($query);
	// 				$segmentId = $result[0]['segment_id'] ?? null;


	// 				// echo $RequestedShop_Name;
	// 				// echo $global_tier_value;
	// 				// echo $segmentId;
	// 				// die;


	// 				if ($segmentId) {

	// 					$segmentId = "gid://shopify/Segment/" . $segmentId;
	// 				} else {

	// 					$segmentFreeGraphQL = 'mutation segmentCreate($segmentName: String!, $segmentQuery: String!) {

	//                         segmentCreate(name: $segmentName, query: $segmentQuery) {

	//                             segment {

	//                                 id

	//                                 name

	//                                 query

	//                             }

	//                             userErrors {

	//                                 message

	//                                 field

	//                             }

	//                         }

	//                     }';

	// 					$segment_random_no = rand(1000, 9999);

	// 					$segmentName = 'shineD-' . $segment_random_no . '-' . $unique_handle;

	// 					$segmentQuery = "customer_tags CONTAINS '$unique_handle'";

	// 					$segmentInput = [

	// 						"segmentName" => $segmentName,
	// 						"segmentQuery" => $segmentQuery,

	// 					];

	// 					$segmentData = $this->shopify_graphql_object->GraphQL->post($segmentFreeGraphQL, null, null, $segmentInput);

	// 					if ($segmentData) {

	// 						$segmentId = $segmentData['data']['segmentCreate']['segment']['id'];
	// 						$segmentId_for_db = $segmentData['data']['segmentCreate']['segment']['id'];

	// 						if (!$segmentId) {

	// 							$segmentId_userErrors = $segmentData['data']['segmentCreate']['userErrors']['message'];

	// 							return json_encode(['message' => $segmentId_userErrors]);
	// 						} else {

	// 							$segmentId_for_db = str_replace("gid://shopify/Segment/", "", $segmentId_for_db);
	// 						}

	// 						$sql = "INSERT INTO membership_perks (
	// 							membership_group_id, 
	// 							segment_id, 
	// 							store
	// 						) VALUES (
	// 							:membership_group_id, 
	// 							:segment_id, 
	// 							:store
	// 						)
	// 						ON DUPLICATE KEY UPDATE 
	// 						segment_id = VALUES(segment_id),
	// 						store = VALUES(store)";

	// 						$stmt = $this->db->prepare($sql);
	// 						$stmt->execute([
	// 							':membership_group_id' => $global_tier_value,
	// 							':segment_id' => $segmentId_for_db,
	// 							':store' => $RequestedShop_Name
	// 						]);
	// 					}

	// 					$edit_updated_id = $obj_values['free_shipping'][$count]['edit_updated_id'];

	// 					$edit_free_shipping_checked_value = $obj_values['free_shipping'][$count]['edit_free_shipping_checked_value'];



	// 					$edit_shipping_code_value = $obj_values['free_shipping'][$count]['edit_shipping_code_value'];

	// 					$check_purchase_amount_value = $obj_values['free_shipping'][$count]['check_purchase_amount_value'];

	// 					$min_purchase_amount_value = $obj_values['free_shipping'][$count]['min_purchase_amount_value'];

	// 					$max_purchase_amount_value = $obj_values['free_shipping'][$count]['min_quantity_items'];



	// 					$new_free_shipping_price_rule_ID = '';



	// 					$edit_discounted_product_collection_checked_value = $obj_values['discount_product_collection'][$count]['edit_discounted_product_collection_checked_value'];

	// 					$product_collectionCode_Field_code_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_code_value'];

	// 					$product_collectionCode_Field_percentage_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_percentage_value'];

	// 					$discounted_product_collection_type = $obj_values['discount_product_collection'][$count]['discounted_product_collection_type'];

	// 					$collectionTitle_id = $obj_values['discount_product_collection'][$count]['collectionTitle_id'];

	// 					$productTitle_id = $obj_values['discount_product_collection'][$count]['productTitle_id'];

	// 					$edit_collectionTitleName = $obj_values['discount_product_collection'][$count]['edit_collectionTitleName'];

	// 					$edit_productTitleName = $obj_values['discount_product_collection'][$count]['edit_productTitleName'];

	// 					$discounted_product_collection_price_rule_id = '';



	// 					$edit_Free_gift_uponsignup_checkbox = $obj_values['Free_gift_upon_signup'][$count]['edit_Free_gift_uponsignup_checkbox'];

	// 					$free_gift_productTitle = $obj_values['Free_gift_upon_signup'][$count]['free_gift_productTitle'];

	// 					$edit_free_gift_selected_productid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_productid'];

	// 					$Free_gift_uponsignup_variantName = $obj_values['Free_gift_upon_signup'][$count]['Free_gift_uponsignup_variantName'];

	// 					$edit_free_gift_selected_variantid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_variantid'];

	// 					$edit_free_gift_uponsignupSelectedDays = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedDays'];

	// 					$edit_free_gift_uponsignupSelectedValue = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedValue'];

	// 					// $edit_free_gift_uponsignup_date = date('Y-m-d', strtotime("+ $edit_free_gift_uponsignupSelectedValue days", strtotime($currentDate)));

	// 					$edit_checked_custom_perk = $obj_values['custom_perk'][$count]['checked_custom_perk'];
	// 					$edit_birthday_rewards = $obj_values['birthday_rewards'][$count]['birthday_rewards'] ?? '0';

	// 					$edit_no_of_sale_days = $obj_values['early_sale_access'][$count]['no_of_sale_days'] ?? '';
	// 					$edit_early_access_checked_value = $obj_values['early_sale_access'][$count]['early_access_checked_value'] ?? '0';

	// 					/**

	// 					 * cross checked the

	// 					 * free shipping checked value

	// 					 **/

	// 					if ($edit_free_shipping_checked_value == 1) {



	// 						$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];



	// 						/**

	// 						 * check discount code

	// 						 * is exists or not

	// 						 */

	// 						$response = $this->checkCodeExist($old_edit_free_shipingCode);

	// 						if (!$response['errors']) {



	// 							$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];

	// 							if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

	// 								$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
	// 							}

	// 							$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

	// 						discountCodeDelete(id: $id) {

	// 							    deletedCodeDiscountId

	// 							    userErrors {

	// 							      field

	// 							      message

	// 							    }

	// 							  }

	// 							}';

	// 							$Delete_firstFree_shipping_discountCode_checked_input = ["id" => $old_edit_free_shipping_price_rule_ID];

	// 							$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_firstFree_shipping_discountCode_checked_input);
	// 						}



	// 						/**

	// 						 * create  update / new

	// 						 * create discount code

	// 						 */

	// 						if (!empty($min_purchase_amount_value)) {



	// 							$input = [

	// 								"freeShippingCodeDiscount" => [

	// 									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 									'appliesOncePerCustomer' => false,

	// 									"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

	// 									"code" => $edit_shipping_code_value,

	// 									"combinesWith" => [

	// 										"orderDiscounts" => true,

	// 										"productDiscounts" => true,

	// 									],

	// 									"minimumRequirement" => [

	// 										"subtotal" => [

	// 											"greaterThanOrEqualToSubtotal" => $min_purchase_amount_value,

	// 										],

	// 									],

	// 									"customerSelection" => [

	// 										"all" => false,

	// 										"customerSegments" => [

	// 											"add" => [$segmentId],

	// 										],

	// 									],

	// 									"destination" => [

	// 										"all" => true,

	// 									],

	// 								],

	// 							];
	// 						} else if (!empty($max_purchase_amount_value)) {

	// 							$input = [

	// 								"freeShippingCodeDiscount" => [

	// 									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 									'appliesOncePerCustomer' => false,

	// 									"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

	// 									"code" => $edit_shipping_code_value,

	// 									"combinesWith" => [

	// 										"orderDiscounts" => true,

	// 										"productDiscounts" => true,

	// 									],

	// 									"minimumRequirement" => [

	// 										"quantity" => [

	// 											"greaterThanOrEqualToQuantity" => $max_purchase_amount_value,

	// 										],

	// 									],

	// 									"customerSelection" => [

	// 										"all" => false,

	// 										"customerSegments" => [

	// 											"add" => [$segmentId],

	// 										],

	// 									],

	// 									"destination" => [

	// 										"all" => true,

	// 									],

	// 								],

	// 							];
	// 						} else {

	// 							$input = [

	// 								"freeShippingCodeDiscount" => [

	// 									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 									'appliesOncePerCustomer' => false,

	// 									"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

	// 									"code" => $edit_shipping_code_value,

	// 									"combinesWith" => [

	// 										"orderDiscounts" => true,

	// 										"productDiscounts" => true,

	// 									],

	// 									"customerSelection" => [

	// 										"all" => false,

	// 										"customerSegments" => [

	// 											"add" => [$segmentId],

	// 										],

	// 									],

	// 									"destination" => [

	// 										"all" => true,

	// 									],

	// 								],

	// 							];
	// 						}

	// 						$updateDiscount_code_Values = 'mutation discountCodeFreeShippingCreate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!) {

	// 								discountCodeFreeShippingCreate(freeShippingCodeDiscount: $freeShippingCodeDiscount) {

	// 								    codeDiscountNode {

	// 								      id

	// 								      codeDiscount {

	// 								        ... on DiscountCodeFreeShipping {

	// 								          title

	// 								          startsAt

	// 								        }

	// 								      }

	// 								    }

	// 								    userErrors {

	// 								      field

	// 								      code

	// 								      message

	// 								    }

	// 								  }

	// 								}';





	// 						$updateFreeshipping_discountCode = $this->shopify_graphql_object->GraphQL->post($updateDiscount_code_Values, null, null, $input);

	// 						// if($RequestedShop_Name='predictive-search.myshopify.com'){
	// 						//     // echo'<pre>';
	// 						//     // echo'$segmentId-----------------------------------------'.$segmentId;
	// 						//     // print_r($updateFreeshipping_discountCode); die;
	// 						//  }

	// 						if ($updateFreeshipping_discountCode) {

	// 							$new_free_shipping_price_rule_ID = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['codeDiscountNode']['id'];

	// 							if (!$new_free_shipping_price_rule_ID) {

	// 								$new_free_shipping_price_rule_ID_userErrors = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['userErrors']['message'];

	// 								return json_encode(['message' => $new_free_shipping_price_rule_ID_userErrors]);
	// 							}
	// 						}
	// 					} else {



	// 						/**

	// 						 * Delete existing

	// 						 * Discount code

	// 						 */

	// 						$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];

	// 						$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];

	// 						if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

	// 							$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
	// 						}

	// 						if (!empty($old_edit_free_shipingCode)) {


	// 							/**

	// 							 * Before Delete

	// 							 * checked the discount code exists or not

	// 							 */

	// 							$response = $this->checkCodeExist($old_edit_free_shipingCode);

	// 							if (!$response['errors']) {



	// 								/**

	// 								 * Delete the discount

	// 								 * if free shipping  unchecked or have values

	// 								 **/

	// 								$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

	//                                 discountCodeDelete(id: $id) {

	//                                         deletedCodeDiscountId

	//                                         userErrors {

	//                                         field

	//                                         message

	//                                         }

	//                                     }

	//                                     }';

	// 								$Delete_id_input = ["id" => $old_edit_free_shipping_price_rule_ID];

	// 								$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_id_input);

	// 								if ($get_Delete_firstFree_shipping_discountCodeID) {

	// 									$new_free_shipping_price_rule_ID = null;
	// 								}
	// 							}
	// 						}
	// 					}



	// 					/**

	// 					 * checked the product colleciton

	// 					 * checked value

	// 					 **/



	// 					if ($edit_discounted_product_collection_checked_value == 1) {

	// 						$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

	// 						$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

	// 						if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

	// 							$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/PriceRule/" . $old_Edit_DiscountProductCode_price_ruleID;
	// 						}

	// 						/**

	// 						 * check productcollection discount code

	// 						 * is exists or not

	// 						 */

	// 						$response = $this->checkCodeExist($old_Edit_DiscountProductCode_Field);

	// 						if (!$response['errors']) {



	// 							/**

	// 							 * Delete product

	// 							 * collection discount code if exists

	// 							 */

	// 							$old_edit_productCollection_price_rule_ID = 'mutation priceRuleDelete($id: ID!) {

	// 								priceRuleDelete(id: $id) {

	// 								    priceRuleUserErrors {

	// 								      field

	// 								      message

	// 								      code

	// 								    }

	// 								    deletedPriceRuleId

	// 								  }

	// 								}';

	// 							$Delete_productcollection_discountCode_checked_input = ["id" => $old_Edit_DiscountProductCode_price_ruleID];

	// 							$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post($old_edit_productCollection_price_rule_ID, null, null, $Delete_productcollection_discountCode_checked_input);
	// 						}



	// 						/**

	// 						 * create  update / new

	// 						 * create product colleciton discount code

	// 						 */

	// 						if ($discounted_product_collection_type == 'C') {

	// 							$input = [

	// 								"priceRule" => [

	// 									"title" => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,

	// 									"validityPeriod" => [

	// 										"start" => date("Y-m-d"),

	// 									],

	// 									"value" => [

	// 										"percentageValue" => -1 * $product_collectionCode_Field_percentage_value,

	// 									],

	// 									"target" => "LINE_ITEM",

	// 									"allocationMethod" => "ACROSS",

	// 									"combinesWith" => [

	// 										"shippingDiscounts" => true,

	// 									],

	// 									"customerSelection" => [

	// 										"forAllCustomers" => false,

	// 										"segmentIds" => [

	// 											$segmentId,

	// 										],

	// 									],

	// 									"itemEntitlements" => [

	// 										"collectionIds" => [

	// 											$collectionTitle_id,

	// 										],

	// 									],

	// 									// "usageLimit" => 1,

	// 								],

	// 								"priceRuleDiscountCode" => [

	// 									"code" => $product_collectionCode_Field_code_value,

	// 								],

	// 							];
	// 						} else if ($discounted_product_collection_type == 'P') {

	// 							$input = [

	// 								"priceRule" => [

	// 									"title" => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,

	// 									"validityPeriod" => [

	// 										"start" => date("Y-m-d"),

	// 									],

	// 									"value" => [

	// 										"percentageValue" => -1 * $product_collectionCode_Field_percentage_value,

	// 									],

	// 									"target" => "LINE_ITEM",

	// 									"allocationMethod" => "ACROSS",

	// 									"combinesWith" => [

	// 										"shippingDiscounts" => true,

	// 									],

	// 									"customerSelection" => [

	// 										"forAllCustomers" => false,

	// 										"segmentIds" => [

	// 											$segmentId,

	// 										],

	// 									],

	// 									"itemEntitlements" => [

	// 										"productIds" => [

	// 											$productTitle_id,

	// 										],

	// 									],

	// 									// "usageLimit" => 1,

	// 								],

	// 								"priceRuleDiscountCode" => [

	// 									"code" => $product_collectionCode_Field_code_value,

	// 								],

	// 							];
	// 						} else {

	// 							$input = [

	// 								"priceRule" => [

	// 									"title" => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,

	// 									"validityPeriod" => [

	// 										"start" => date("Y-m-d"),

	// 									],

	// 									"value" => [

	// 										"percentageValue" => -1 * $product_collectionCode_Field_percentage_value,

	// 									],

	// 									"target" => "LINE_ITEM",

	// 									"allocationMethod" => "ACROSS",

	// 									"combinesWith" => [

	// 										"shippingDiscounts" => true,

	// 									],

	// 									"customerSelection" => [

	// 										"forAllCustomers" => false,

	// 										"segmentIds" => [

	// 											$segmentId,

	// 										],

	// 									],

	// 									"itemEntitlements" => [

	// 										"targetAllLineItems" => true,

	// 									],

	// 									// "usageLimit" => 1,

	// 								],

	// 								"priceRuleDiscountCode" => [

	// 									"code" => $product_collectionCode_Field_code_value,

	// 								],

	// 							];
	// 						}



	// 						$update_discount_productcollection_data = 'mutation priceRuleCreate($priceRule: PriceRuleInput!, $priceRuleDiscountCode: PriceRuleDiscountCodeInput!) {

	// 							priceRuleCreate(priceRule: $priceRule, priceRuleDiscountCode: $priceRuleDiscountCode) {

	// 								priceRule {

	// 								  id

	// 								  allocationLimit

	// 								}

	// 								priceRuleDiscountCode {

	// 								  id

	// 								  code

	// 								  usageCount

	// 								}

	// 								priceRuleUserErrors {

	// 								  code

	// 								  field

	// 								  message

	// 								}

	// 							  }

	// 							}

	// 						';

	// 						$data = $this->shopify_graphql_object->GraphQL->post($update_discount_productcollection_data, null, null, $input);



	// 						if ($data) {

	// 							$discounted_product_collection_price_rule_id .= $data['data']['priceRuleCreate']['priceRule']['id'];
	// 						}
	// 					} else {


	// 						/**

	// 						 * Delete product

	// 						 * collection discount code if exists

	// 						 */


	// 						$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

	// 						$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

	// 						if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

	// 							$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/PriceRule/" . $old_Edit_DiscountProductCode_price_ruleID;
	// 						}

	// 						if (!empty($old_Edit_DiscountProductCode_Field)) {



	// 							$old_edit_productCollection_price_rule_ID = 'mutation priceRuleDelete($id: ID!) {

	//                                 priceRuleDelete(id: $id) {

	//                                     priceRuleUserErrors {

	//                                     field

	//                                     message

	//                                     code

	//                                     }

	//                                     deletedPriceRuleId

	//                                 }

	//                                 }';

	// 							$Delete_productcollection_discountCode_checked_input = ["id" => $old_Edit_DiscountProductCode_price_ruleID];

	// 							$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post($old_edit_productCollection_price_rule_ID, null, null, $Delete_productcollection_discountCode_checked_input);
	// 						}
	// 					}



	// 					/**

	// 					 * checked free giftupon

	// 					 * signup checkbox

	// 					 **/



	// 					if ($edit_Free_gift_uponsignup_checkbox == '1') {

	// 						// make condition  of free gift



	// 					}

	// 					/**

	// 					 * saved the

	// 					 * Data with new price rule id in db

	// 					 */

	// 					$whereCondition = [
	// 						'id' => $edit_updated_id,
	// 						'store' => $this->store,
	// 					];
	// 					// print_r($whereCondition);
	// 					$value = ['free_shipping_checked_value' => $edit_free_shipping_checked_value, 'free_shipping_code' => $edit_shipping_code_value, 'freeshipping_selected_value' => $check_purchase_amount_value, 'min_purchase_amount_value' => $min_purchase_amount_value, 'min_quantity_items' => $max_purchase_amount_value, 'freeshipping_pricerule_id' => $new_free_shipping_price_rule_ID, 'discounted_product_collection_checked_value' => $edit_discounted_product_collection_checked_value, 'discounted_product_collection_code' => $product_collectionCode_Field_code_value, 'discounted_product_collection_percentageoff' => $product_collectionCode_Field_percentage_value, 'discounted_product_collection_type' => $discounted_product_collection_type, 'discounted__collection_title' => $edit_collectionTitleName, 'discounted__collection_id' => $collectionTitle_id, 'discounted__product_title' => $edit_productTitleName, 'discounted__product_id' => $productTitle_id, 'discounted_product_collection_price_rule_id' => $discounted_product_collection_price_rule_id, 'Free_gift_uponsignup_checkbox' => $edit_Free_gift_uponsignup_checkbox, 'Free_gift_uponsignup_productName' => $free_gift_productTitle, 'gift_uponsignup_variantName' => $Free_gift_uponsignup_variantName, 'free_gift_uponsignupSelectedDays' => $edit_free_gift_uponsignupSelectedDays, 'free_gift_uponsignupSelected_Value' => $edit_free_gift_uponsignupSelectedValue, 'perk_free_gift_product_id' => $edit_free_gift_selected_productid, 'perk_free_gift_variant_id' => $edit_free_gift_selected_variantid, 'custom_perk_checkbox' => $edit_checked_custom_perk, 'no_of_sale_days' => $edit_no_of_sale_days, 'early_access_checked_value' => $edit_early_access_checked_value, 'birthday_rewards' => $edit_birthday_rewards];

	// 					$value = array_filter($value, function ($val) {
	// 						return $val !== null && $val !== '';
	// 					});
	// 					// print_r($value);
	// 					// die;

	// 					$checkSavedData = $this->update_row_perks('membership_perks', $value, $whereCondition, "AND");
	// 					if ($checkSavedData) {

	// 						$saveStatus = false;
	// 					} else {

	// 						$saveStatus = true;
	// 					}

	// 					sleep(1);
	// 				}


	// 				$edit_updated_id = $obj_values['free_shipping'][$count]['edit_updated_id'];

	// 				$edit_free_shipping_checked_value = $obj_values['free_shipping'][$count]['edit_free_shipping_checked_value'];



	// 				$edit_shipping_code_value = $obj_values['free_shipping'][$count]['edit_shipping_code_value'];

	// 				$check_purchase_amount_value = $obj_values['free_shipping'][$count]['check_purchase_amount_value'];

	// 				$min_purchase_amount_value = $obj_values['free_shipping'][$count]['min_purchase_amount_value'];

	// 				$max_purchase_amount_value = $obj_values['free_shipping'][$count]['min_quantity_items'];



	// 				$new_free_shipping_price_rule_ID = '';



	// 				$edit_discounted_product_collection_checked_value = $obj_values['discount_product_collection'][$count]['edit_discounted_product_collection_checked_value'];

	// 				$product_collectionCode_Field_code_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_code_value'];

	// 				$product_collectionCode_Field_percentage_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_percentage_value'];

	// 				$discounted_product_collection_type = $obj_values['discount_product_collection'][$count]['discounted_product_collection_type'];

	// 				$collectionTitle_id = $obj_values['discount_product_collection'][$count]['collectionTitle_id'];

	// 				$productTitle_id = $obj_values['discount_product_collection'][$count]['productTitle_id'];

	// 				$edit_collectionTitleName = $obj_values['discount_product_collection'][$count]['edit_collectionTitleName'];

	// 				$edit_productTitleName = $obj_values['discount_product_collection'][$count]['edit_productTitleName'];

	// 				$discounted_product_collection_price_rule_id = '';



	// 				$edit_Free_gift_uponsignup_checkbox = $obj_values['Free_gift_upon_signup'][$count]['edit_Free_gift_uponsignup_checkbox'];

	// 				$free_gift_productTitle = $obj_values['Free_gift_upon_signup'][$count]['free_gift_productTitle'];

	// 				$edit_free_gift_selected_productid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_productid'];

	// 				$Free_gift_uponsignup_variantName = $obj_values['Free_gift_upon_signup'][$count]['Free_gift_uponsignup_variantName'];

	// 				$edit_free_gift_selected_variantid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_variantid'];

	// 				$edit_free_gift_uponsignupSelectedDays = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedDays'];

	// 				$edit_free_gift_uponsignupSelectedValue = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedValue'];

	// 				// $edit_free_gift_uponsignup_date = date('Y-m-d', strtotime("+ $edit_free_gift_uponsignupSelectedValue days", strtotime($currentDate)));

	// 				$edit_checked_custom_perk = $obj_values['custom_perk'][$count]['checked_custom_perk'];
	// 				$edit_birthday_rewards = $obj_values['birthday_rewards'][$count]['birthday_rewards'] ?? '0';

	// 				$edit_no_of_sale_days = $obj_values['early_sale_access'][$count]['no_of_sale_days'] ?? '';
	// 				$edit_early_access_checked_value = $obj_values['early_sale_access'][$count]['early_access_checked_value'] ?? '0';

	// 				/**

	// 				 * cross checked the

	// 				 * free shipping checked value

	// 				 **/


	// 				if ($edit_free_shipping_checked_value == 1) {


	// 					$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];

	// 					/**

	// 					 * check discount code

	// 					 * is exists or not

	// 					 */

	// 					$response = $this->checkCodeExist($old_edit_free_shipingCode);

	// 					if (!$response['errors']) {


	// 						$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];

	// 						if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

	// 							$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
	// 						}

	// 						$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

	// 						discountCodeDelete(id: $id) {

	// 							    deletedCodeDiscountId

	// 							    userErrors {

	// 							      field

	// 							      message

	// 							    }

	// 							  }

	// 							}';

	// 						$Delete_firstFree_shipping_discountCode_checked_input = ["id" => $old_edit_free_shipping_price_rule_ID];

	// 						$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_firstFree_shipping_discountCode_checked_input);
	// 					}


	// 					/**

	// 					 * create  update / new

	// 					 * create discount code

	// 					 */

	// 					if (!empty($min_purchase_amount_value)) {



	// 						$input = [

	// 							"freeShippingCodeDiscount" => [

	// 								"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 								'appliesOncePerCustomer' => false,

	// 								"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

	// 								"code" => $edit_shipping_code_value,

	// 								"combinesWith" => [

	// 									"orderDiscounts" => true,

	// 									"productDiscounts" => true,

	// 								],

	// 								"minimumRequirement" => [

	// 									"subtotal" => [

	// 										"greaterThanOrEqualToSubtotal" => $min_purchase_amount_value,

	// 									],

	// 								],

	// 								"customerSelection" => [

	// 									"all" => false,

	// 									"customerSegments" => [

	// 										"add" => [$segmentId],

	// 									],

	// 								],

	// 								"destination" => [

	// 									"all" => true,

	// 								],

	// 							],

	// 						];
	// 					} else if (!empty($max_purchase_amount_value)) {

	// 						$input = [

	// 							"freeShippingCodeDiscount" => [

	// 								"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 								'appliesOncePerCustomer' => false,

	// 								"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

	// 								"code" => $edit_shipping_code_value,

	// 								"combinesWith" => [

	// 									"orderDiscounts" => true,

	// 									"productDiscounts" => true,

	// 								],

	// 								"minimumRequirement" => [

	// 									"quantity" => [

	// 										"greaterThanOrEqualToQuantity" => $max_purchase_amount_value,

	// 									],

	// 								],

	// 								"customerSelection" => [

	// 									"all" => false,

	// 									"customerSegments" => [

	// 										"add" => [$segmentId],

	// 									],

	// 								],

	// 								"destination" => [

	// 									"all" => true,

	// 								],

	// 							],

	// 						];
	// 					} else {

	// 						$input = [

	// 							"freeShippingCodeDiscount" => [

	// 								"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

	// 								'appliesOncePerCustomer' => false,

	// 								"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

	// 								"code" => $edit_shipping_code_value,

	// 								"combinesWith" => [

	// 									"orderDiscounts" => true,

	// 									"productDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"all" => false,

	// 									"customerSegments" => [

	// 										"add" => [$segmentId],

	// 									],

	// 								],

	// 								"destination" => [

	// 									"all" => true,

	// 								],

	// 							],

	// 						];
	// 					}

	// 					$updateDiscount_code_Values = 'mutation discountCodeFreeShippingCreate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!) {

	// 								discountCodeFreeShippingCreate(freeShippingCodeDiscount: $freeShippingCodeDiscount) {

	// 								    codeDiscountNode {

	// 								      id

	// 								      codeDiscount {

	// 								        ... on DiscountCodeFreeShipping {

	// 								          title

	// 								          startsAt

	// 								        }

	// 								      }

	// 								    }

	// 								    userErrors {

	// 								      field

	// 								      code

	// 								      message

	// 								    }

	// 								  }

	// 								}';


	// 					$updateFreeshipping_discountCode = $this->shopify_graphql_object->GraphQL->post($updateDiscount_code_Values, null, null, $input);


	// 					// if($RequestedShop_Name='predictive-search.myshopify.com'){
	// 					//     // echo'<pre>';
	// 					//     // echo'$segmentId-----------------------------------------'.$segmentId;
	// 					//     // print_r($updateFreeshipping_discountCode); die;
	// 					//  }

	// 					if ($updateFreeshipping_discountCode) {

	// 						$new_free_shipping_price_rule_ID = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['codeDiscountNode']['id'];

	// 						// print_r($new_free_shipping_price_rule_ID); 
	// 						if (!$new_free_shipping_price_rule_ID) {

	// 							$new_free_shipping_price_rule_ID_userErrors = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['userErrors']['message'];

	// 							return json_encode(['message' =>  $new_free_shipping_price_rule_ID_userErrors]);
	// 						}
	// 					}
	// 				} else {



	// 					/**

	// 					 * Delete existing

	// 					 * Discount code

	// 					 */

	// 					$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];

	// 					$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];

	// 					if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

	// 						$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
	// 					}

	// 					if (!empty($old_edit_free_shipingCode)) {



	// 						/**

	// 						 * Before Delete

	// 						 * checked the discount code exists or not

	// 						 */

	// 						$response = $this->checkCodeExist($old_edit_free_shipingCode);

	// 						if (!$response['errors']) {





	// 							/**

	// 							 * Delete the discount

	// 							 * if free shipping  unchecked or have values

	// 							 **/

	// 							$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

	//                                 discountCodeDelete(id: $id) {

	//                                         deletedCodeDiscountId

	//                                         userErrors {

	//                                         field

	//                                         message

	//                                         }

	//                                     }

	//                                     }';

	// 							$Delete_id_input = ["id" => $old_edit_free_shipping_price_rule_ID];

	// 							$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_id_input);

	// 							if ($get_Delete_firstFree_shipping_discountCodeID) {

	// 								$new_free_shipping_price_rule_ID = null;
	// 							}
	// 						}
	// 					}
	// 				}



	// 				/**

	// 				 * checked the product colleciton

	// 				 * checked value

	// 				 **/



	// 				if ($edit_discounted_product_collection_checked_value == 1) {

	// 					$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

	// 					$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

	// 					if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

	// 						$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/PriceRule/" . $old_Edit_DiscountProductCode_price_ruleID;
	// 					}

	// 					/**

	// 					 * check productcollection discount code

	// 					 * is exists or not

	// 					 */

	// 					$response = $this->checkCodeExist($old_Edit_DiscountProductCode_Field);

	// 					if (!$response['errors']) {



	// 						/**

	// 						 * Delete product

	// 						 * collection discount code if exists

	// 						 */

	// 						$old_edit_productCollection_price_rule_ID = 'mutation priceRuleDelete($id: ID!) {

	// 								priceRuleDelete(id: $id) {

	// 								    priceRuleUserErrors {

	// 								      field

	// 								      message

	// 								      code

	// 								    }

	// 								    deletedPriceRuleId

	// 								  }

	// 								}';

	// 						$Delete_productcollection_discountCode_checked_input = ["id" => $old_Edit_DiscountProductCode_price_ruleID];

	// 						$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post($old_edit_productCollection_price_rule_ID, null, null, $Delete_productcollection_discountCode_checked_input);
	// 					}



	// 					/**

	// 					 * create  update / new

	// 					 * create product colleciton discount code

	// 					 */

	// 					if ($discounted_product_collection_type == 'C') {

	// 						$input = [

	// 							"priceRule" => [

	// 								"title" => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,

	// 								"validityPeriod" => [

	// 									"start" => date("Y-m-d"),

	// 								],

	// 								"value" => [

	// 									"percentageValue" => -1 * $product_collectionCode_Field_percentage_value,

	// 								],

	// 								"target" => "LINE_ITEM",

	// 								"allocationMethod" => "ACROSS",

	// 								"combinesWith" => [

	// 									"shippingDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"forAllCustomers" => false,

	// 									"segmentIds" => [

	// 										$segmentId,

	// 									],

	// 								],

	// 								"itemEntitlements" => [

	// 									"collectionIds" => [

	// 										$collectionTitle_id,

	// 									],

	// 								],

	// 								// "usageLimit" => 1,

	// 							],

	// 							"priceRuleDiscountCode" => [

	// 								"code" => $product_collectionCode_Field_code_value,

	// 							],

	// 						];
	// 					} else if ($discounted_product_collection_type == 'P') {

	// 						$input = [

	// 							"priceRule" => [

	// 								"title" => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,

	// 								"validityPeriod" => [

	// 									"start" => date("Y-m-d"),

	// 								],

	// 								"value" => [

	// 									"percentageValue" => -1 * $product_collectionCode_Field_percentage_value,

	// 								],

	// 								"target" => "LINE_ITEM",

	// 								"allocationMethod" => "ACROSS",

	// 								"combinesWith" => [

	// 									"shippingDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"forAllCustomers" => false,

	// 									"segmentIds" => [

	// 										$segmentId,

	// 									],

	// 								],

	// 								"itemEntitlements" => [

	// 									"productIds" => [

	// 										$productTitle_id,

	// 									],

	// 								],

	// 								// "usageLimit" => 1,

	// 							],

	// 							"priceRuleDiscountCode" => [

	// 								"code" => $product_collectionCode_Field_code_value,

	// 							],

	// 						];
	// 					} else {

	// 						$input = [

	// 							"priceRule" => [

	// 								"title" => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,

	// 								"validityPeriod" => [

	// 									"start" => date("Y-m-d"),

	// 								],

	// 								"value" => [

	// 									"percentageValue" => -1 * $product_collectionCode_Field_percentage_value,

	// 								],

	// 								"target" => "LINE_ITEM",

	// 								"allocationMethod" => "ACROSS",

	// 								"combinesWith" => [

	// 									"shippingDiscounts" => true,

	// 								],

	// 								"customerSelection" => [

	// 									"forAllCustomers" => false,

	// 									"segmentIds" => [

	// 										$segmentId,

	// 									],

	// 								],

	// 								"itemEntitlements" => [

	// 									"targetAllLineItems" => true,

	// 								],

	// 								// "usageLimit" => 1,

	// 							],

	// 							"priceRuleDiscountCode" => [

	// 								"code" => $product_collectionCode_Field_code_value,

	// 							],

	// 						];
	// 					}



	// 					$update_discount_productcollection_data = 'mutation priceRuleCreate($priceRule: PriceRuleInput!, $priceRuleDiscountCode: PriceRuleDiscountCodeInput!) {

	// 							priceRuleCreate(priceRule: $priceRule, priceRuleDiscountCode: $priceRuleDiscountCode) {

	// 								priceRule {

	// 								  id

	// 								  allocationLimit

	// 								}

	// 								priceRuleDiscountCode {

	// 								  id

	// 								  code

	// 								  usageCount

	// 								}

	// 								priceRuleUserErrors {

	// 								  code

	// 								  field

	// 								  message

	// 								}

	// 							  }

	// 							}

	// 						';

	// 					$data = $this->shopify_graphql_object->GraphQL->post($update_discount_productcollection_data, null, null, $input);



	// 					if ($data) {

	// 						$discounted_product_collection_price_rule_id .= $data['data']['priceRuleCreate']['priceRule']['id'];
	// 					}
	// 				} else {



	// 					/**

	// 					 * Delete product

	// 					 * collection discount code if exists

	// 					 */


	// 					$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

	// 					$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

	// 					if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

	// 						$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/PriceRule/" . $old_Edit_DiscountProductCode_price_ruleID;
	// 					}

	// 					if (!empty($old_Edit_DiscountProductCode_Field)) {



	// 						$old_edit_productCollection_price_rule_ID = 'mutation priceRuleDelete($id: ID!) {

	//                                 priceRuleDelete(id: $id) {

	//                                     priceRuleUserErrors {

	//                                     field

	//                                     message

	//                                     code

	//                                     }

	//                                     deletedPriceRuleId

	//                                 }

	//                                 }';

	// 						$Delete_productcollection_discountCode_checked_input = ["id" => $old_Edit_DiscountProductCode_price_ruleID];

	// 						$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post($old_edit_productCollection_price_rule_ID, null, null, $Delete_productcollection_discountCode_checked_input);
	// 					}
	// 				}



	// 				/**

	// 				 * checked free giftupon

	// 				 * signup checkbox

	// 				 **/

	// 				// Free gift upon signup

	// 				if ($edit_Free_gift_uponsignup_checkbox == '1') {

	// 					// make condition  of free gift



	// 				}

	// 				/**

	// 				 * saved the

	// 				 * Data with new price rule id in db

	// 				 */

	// 				$whereCondition = [
	// 					'id' => $edit_updated_id,
	// 					'store' => $this->store,
	// 				];
	// 				// print_r($whereCondition);
	// 				$value = ['free_shipping_checked_value' => $edit_free_shipping_checked_value ? 1 : 0, 'free_shipping_code' => $edit_shipping_code_value, 'freeshipping_selected_value' => $check_purchase_amount_value, 'min_purchase_amount_value' => $min_purchase_amount_value, 'min_quantity_items' => $max_purchase_amount_value, 'freeshipping_pricerule_id' => $new_free_shipping_price_rule_ID, 'discounted_product_collection_checked_value' => $edit_discounted_product_collection_checked_value ? 1 : 0, 'discounted_product_collection_code' => $product_collectionCode_Field_code_value, 'discounted_product_collection_percentageoff' => $product_collectionCode_Field_percentage_value, 'discounted_product_collection_type' => $discounted_product_collection_type, 'discounted__collection_title' => $edit_collectionTitleName, 'discounted__collection_id' => $collectionTitle_id, 'discounted__product_title' => $edit_productTitleName, 'discounted__product_id' => $productTitle_id, 'discounted_product_collection_price_rule_id' => $discounted_product_collection_price_rule_id, 'Free_gift_uponsignup_checkbox' => $edit_Free_gift_uponsignup_checkbox ? 1 : 0, 'Free_gift_uponsignup_productName' => $free_gift_productTitle, 'gift_uponsignup_variantName' => $Free_gift_uponsignup_variantName, 'free_gift_uponsignupSelectedDays' => $edit_free_gift_uponsignupSelectedDays, 'free_gift_uponsignupSelected_Value' => $edit_free_gift_uponsignupSelectedValue, 'perk_free_gift_product_id' => $edit_free_gift_selected_productid, 'perk_free_gift_variant_id' => $edit_free_gift_selected_variantid, 'custom_perk_checkbox' => $edit_checked_custom_perk ? 1 : 0, 'no_of_sale_days' => $edit_no_of_sale_days, 'early_access_checked_value' => $edit_early_access_checked_value ? 1 : 0, 'birthday_rewards' => $edit_birthday_rewards];

	// 				$value = array_filter($value, function ($val) {
	// 					return $val !== null && $val !== '';
	// 				});
	// 				// print_r($value);
	// 				$this->update_row_perks('membership_perks', $value, $whereCondition, "AND");

	// 				// print_r($value);


	// 				sleep(1);
	// 			}

	// 			// echo('save');

	// 			$status = false;

	// 			$message = 'Data has been updated successfully!';

	// 			$this->upsertMetaObject();
	// 			return json_encode(['status' => $status, 'message' => $message]);
	// 		} else {

	// 			$status = true;

	// 			$message = 'No changes in the tier form!';
	// 			$this->upsertMetaObject();
	// 			return json_encode(['status' => $status, 'message' => $message]);
	// 		}
	// 		// $this->upsertMetaObject();
	// 	}
	// }


	//  -----------------new-----------

	public function membershipPerksUpdate($request)
	{

		$RequestedShop_Name = $this->store;
		$shopName = $this->store;
		$currentDate = date('Y-m-d');
		$updatePerks_Values = $request;

		if (!empty($updatePerks_Values)) {

			$obj_values = $updatePerks_Values["obj"];
			$flag = false;

			$editTierValues = $obj_values['edit_Tier_Value'] ?? [];

			$freeShippingArr = $obj_values['free_shipping'] ?? [];
			$discountCollectionArr = $obj_values['discount_product_collection'] ?? [];

			for ($count = 0; $count < count($editTierValues); $count++) {

				$global_tier_value = $editTierValues[$count]['edit_Tier_Value'];

				// Use customQuery for SELECT
				$query = "SELECT * FROM membership_plan_groups WHERE store = '$RequestedShop_Name' AND membership_group_id = '$global_tier_value' AND group_status = 'enable' LIMIT 1";
				$results = $this->customQuery($query);

				$member_group_data = $results[0] ?? null;

				if (!$member_group_data) {
					continue;
				}

				$unique_handle = $member_group_data['unique_handle'];

				$edit_free_shipping_checked_value = $freeShippingArr[$count]['edit_free_shipping_checked_value'] ?? 0;
				$edit_discounted_product_collection_checked_value = $discountCollectionArr[$count]['edit_discounted_product_collection_checked_value'] ?? 0;

				// ========== FREE SHIPPING PERK ==========
				if ($edit_free_shipping_checked_value == 1) {
					$edit_shipping_code_value = $freeShippingArr[$count]['edit_shipping_code_value'] ?? '';
					$old_edit_free_shipingCode = $freeShippingArr[$count]['old_edit_free_shipingCode'] ?? '';

					if ($edit_shipping_code_value !== $old_edit_free_shipingCode) {
						try {
							$response = $this->checkCodeExist($edit_shipping_code_value);
							if (!$response['errors']) {

								$responseData = [
									"status" => "exists",
									"message" => $global_tier_value,
									"check_perk_value" => "free_shipping_perk"
								];
								return json_encode($responseData);
							}
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					}

					$flag = true;
				} else {
					$flag = true;
				}

				// ========== DISCOUNT COLLECTION PERK ==========
				if ($edit_discounted_product_collection_checked_value == 1) {
					$new_code = $discountCollectionArr[$count]['product_collectionCode_Field_code_value'] ?? '';
					$old_code = $discountCollectionArr[$count]['old_Edit_DiscountProductCode_Field'] ?? '';

					if ($new_code !== $old_code) {

						try {
							$response = $this->checkCodeExist($new_code);
							if (!$response['errors']) {

								return json_encode([
									"status" => "exists",
									"message" => $global_tier_value,
									"check_perk_value" => "productCollection_perk"
								]);
							}
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					}

					$flag = true;
				} else {
					$flag = true;
				}

				// Add delay for rate limiting
				sleep(1);
			}

			/**

			 * IF codtion is True

			 * for all tier

			 **/

			if ($flag == true) {

				$saveStatus = false;

				for ($count = 0; $count < count($obj_values['edit_Tier_Value']); $count++) {



					$global_tier_value = $obj_values['edit_Tier_Value'][$count]['edit_Tier_Value'];

					$query = "SELECT segment_id FROM membership_perks WHERE store = '{$RequestedShop_Name}' AND membership_group_id = '{$global_tier_value}' LIMIT 1";

					$result = $this->customQuery($query);
					$segmentId = $result[0]['segment_id'] ?? null;



					if ($segmentId) {

						$segmentId = "gid://shopify/Segment/" . $segmentId;
					} else {

						$segmentFreeGraphQL = 'mutation segmentCreate($segmentName: String!, $segmentQuery: String!) {

                            segmentCreate(name: $segmentName, query: $segmentQuery) {

                                segment {

                                    id

                                    name

                                    query

                                }

                                userErrors {

                                    message

                                    field

                                }

                            }

                        }';

						$segment_random_no = rand(1000, 9999);

						$segmentName = 'shineD-' . $segment_random_no . '-' . $unique_handle;

						$segmentQuery = "customer_tags CONTAINS '$unique_handle'";

						$segmentInput = [

							"segmentName" => $segmentName,
							"segmentQuery" => $segmentQuery,

						];

						$segmentData = $this->shopify_graphql_object->GraphQL->post($segmentFreeGraphQL, null, null, $segmentInput);

						if ($segmentData) {

							$segmentId = $segmentData['data']['segmentCreate']['segment']['id'];
							$segmentId_for_db = $segmentData['data']['segmentCreate']['segment']['id'];

							if (!$segmentId) {

								$segmentId_userErrors = $segmentData['data']['segmentCreate']['userErrors']['message'];

								return json_encode(['message' => $segmentId_userErrors]);
							} else {

								$segmentId_for_db = str_replace("gid://shopify/Segment/", "", $segmentId_for_db);
							}

							$sql = "INSERT INTO membership_perks (
								membership_group_id, 
								segment_id, 
								store
							) VALUES (
								:membership_group_id, 
								:segment_id, 
								:store
							)
							ON DUPLICATE KEY UPDATE 
							segment_id = VALUES(segment_id),
							store = VALUES(store)";

							$stmt = $this->db->prepare($sql);
							$stmt->execute([
								':membership_group_id' => $global_tier_value,
								':segment_id' => $segmentId_for_db,
								':store' => $RequestedShop_Name
							]);
						}

						$edit_updated_id = $obj_values['free_shipping'][$count]['edit_updated_id'];

						$edit_free_shipping_checked_value = $obj_values['free_shipping'][$count]['edit_free_shipping_checked_value'];

						$edit_shipping_code_value = $obj_values['free_shipping'][$count]['edit_shipping_code_value'];

						$check_purchase_amount_value = $obj_values['free_shipping'][$count]['check_purchase_amount_value'];

						$min_purchase_amount_value = $obj_values['free_shipping'][$count]['min_purchase_amount_value'];

						$max_purchase_amount_value = $obj_values['free_shipping'][$count]['min_quantity_items'];

						$new_free_shipping_price_rule_ID = '';

						$edit_discounted_product_collection_checked_value = $obj_values['discount_product_collection'][$count]['edit_discounted_product_collection_checked_value'];

						$product_collectionCode_Field_code_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_code_value'];

						$product_collectionCode_Field_percentage_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_percentage_value'];

						$discounted_product_collection_type = $obj_values['discount_product_collection'][$count]['discounted_product_collection_type'];

						$collectionTitle_id = $obj_values['discount_product_collection'][$count]['collectionTitle_id'];

						$productTitle_id = $obj_values['discount_product_collection'][$count]['productTitle_id'];

						$edit_collectionTitleName = $obj_values['discount_product_collection'][$count]['edit_collectionTitleName'];

						$edit_productTitleName = $obj_values['discount_product_collection'][$count]['edit_productTitleName'];

						$discounted_product_collection_price_rule_id = '';

						$edit_Free_gift_uponsignup_checkbox = $obj_values['Free_gift_upon_signup'][$count]['edit_Free_gift_uponsignup_checkbox'];

						$free_gift_productTitle = $obj_values['Free_gift_upon_signup'][$count]['free_gift_productTitle'];

						$edit_free_gift_selected_productid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_productid'];

						$Free_gift_uponsignup_variantName = $obj_values['Free_gift_upon_signup'][$count]['Free_gift_uponsignup_variantName'];

						$edit_free_gift_selected_variantid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_variantid'];

						$edit_free_gift_uponsignupSelectedDays = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedDays'];

						$edit_free_gift_uponsignupSelectedValue = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedValue'];

						// $edit_free_gift_uponsignup_date = date('Y-m-d', strtotime("+ $edit_free_gift_uponsignupSelectedValue days", strtotime($currentDate)));

						$edit_checked_custom_perk = $obj_values['custom_perk'][$count]['checked_custom_perk'];
						$edit_birthday_rewards = $obj_values['birthday_rewards'][$count]['birthday_rewards'] ?? '0';

						$edit_no_of_sale_days = $obj_values['early_sale_access'][$count]['no_of_sale_days'] ?? '';
						$edit_early_access_checked_value = $obj_values['early_sale_access'][$count]['early_access_checked_value'] ?? '0';

						/**

						 * cross checked the

						 * free shipping checked value

						 **/
						if ($edit_free_shipping_checked_value == 1) {


							$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];


							/**

							 * check discount code

							 * is exists or not

							 */

							$response = $this->checkCodeExist($old_edit_free_shipingCode);

							if (!$response['errors']) {



								$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];


								if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

									$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
								}
							}
							/**

							 * create  update / new

							 * create discount code

							 */
							if (!empty($min_purchase_amount_value)) {

								$input = [
									"id" => $old_edit_free_shipping_price_rule_ID,

									"freeShippingCodeDiscount" => [

										"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

										'appliesOncePerCustomer' => false,

										"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

										"code" => $edit_shipping_code_value,

										"combinesWith" => [

											"orderDiscounts" => true,

											"productDiscounts" => true,

										],

										"minimumRequirement" => [

											"subtotal" => [

												"greaterThanOrEqualToSubtotal" => $min_purchase_amount_value,

											],

										],

										"customerSelection" => [

											"all" => false,

											"customerSegments" => [

												"add" => [$segmentId],

											],

										],

										"destination" => [

											"all" => true,

										],

									],

								];
							} else if (!empty($max_purchase_amount_value)) {

								$input = [
									"id" => $old_edit_free_shipping_price_rule_ID,
									"freeShippingCodeDiscount" => [

										"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

										'appliesOncePerCustomer' => false,

										"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

										"code" => $edit_shipping_code_value,

										"combinesWith" => [

											"orderDiscounts" => true,

											"productDiscounts" => true,

										],

										"minimumRequirement" => [

											"quantity" => [

												"greaterThanOrEqualToQuantity" => $max_purchase_amount_value,

											],

										],

										"customerSelection" => [

											"all" => false,

											"customerSegments" => [

												"add" => [$segmentId],

											],
										],
										"destination" => [

											"all" => true,
										],
									],

								];
							} else {

								$input = [
									"id" => $old_edit_free_shipping_price_rule_ID,
									"freeShippingCodeDiscount" => [

										"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

										'appliesOncePerCustomer' => false,

										"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

										"code" => $edit_shipping_code_value,

										"combinesWith" => [

											"orderDiscounts" => true,

											"productDiscounts" => true,

										],

										"customerSelection" => [

											"all" => false,

											"customerSegments" => [

												"add" => [$segmentId],

											],

										],

										"destination" => [

											"all" => true,

										],

									],

								];
							}

							if (empty($old_edit_free_shipping_price_rule_ID)) {

								$updateDiscount_code_Values = 'mutation discountCodeFreeShippingCreate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!) {

									discountCodeFreeShippingCreate(freeShippingCodeDiscount: $freeShippingCodeDiscount) {

									    codeDiscountNode {

									      id

									      codeDiscount {

									        ... on DiscountCodeFreeShipping {

									          title

									          startsAt

									        }

									      }

									    }

									    userErrors {

									      field

									      code

									      message

									    }

									  }

									}';
								$updateFreeshipping_discountCode = $this->shopify_graphql_object->GraphQL->post($updateDiscount_code_Values, null, null, $input);

								if ($updateFreeshipping_discountCode) {

									$new_free_shipping_price_rule_ID = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['codeDiscountNode']['id'];

									if (!$new_free_shipping_price_rule_ID) {

										$new_free_shipping_price_rule_ID_userErrors = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['userErrors']['message'];

										return json_encode(['message' => $new_free_shipping_price_rule_ID_userErrors]);
									}
								}
							} else {

								$updateDiscount_code_Values = 'mutation discountCodeFreeShippingUpdate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!, $id: ID!) {

									discountCodeFreeShippingUpdate(freeShippingCodeDiscount: $freeShippingCodeDiscount, id: $id) {

										codeDiscountNode {

											id

											codeDiscount {

											... on DiscountCodeFreeShipping {

												title

												startsAt

											}

											}

										}

										userErrors {

											field

											code

											message

										}

									}

								}';

								$updateFreeshipping_discountCode = $this->shopify_graphql_object->GraphQL->post($updateDiscount_code_Values, null, null, $input);

								if ($updateFreeshipping_discountCode) {

									$new_free_shipping_price_rule_ID = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingUpdate']['codeDiscountNode']['id'];

									if (!$new_free_shipping_price_rule_ID) {

										$new_free_shipping_price_rule_ID_userErrors = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingUpdate']['userErrors']['message'];

										return json_encode(['message' => $new_free_shipping_price_rule_ID_userErrors]);
									}
								}
							}
						} else {
							/**

							 * Delete existing

							 * Discount code

							 */

							$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];
							$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];
							if (is_numeric($old_edit_free_shipping_price_rule_ID)) {
								$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
							}

							if (!empty($old_edit_free_shipingCode)) {
								/**

								 * Before Delete

								 * checked the discount code exists or not

								 */

								$response = $this->checkCodeExist($old_edit_free_shipingCode);

								if (!$response['errors']) {

									/**

									 * Delete the discount

									 * if free shipping  unchecked or have values

									 **/

									$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

                                    discountCodeDelete(id: $id) {

                                            deletedCodeDiscountId

                                            userErrors {

                                            field

                                            message

                                            }

                                        }

                                        }';

									$Delete_id_input = ["id" => $old_edit_free_shipping_price_rule_ID];

									$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_id_input);

									if ($get_Delete_firstFree_shipping_discountCodeID) {

										$new_free_shipping_price_rule_ID = null;
									}
								}
							}
						}
						/**

						 * checked the product colleciton

						 * checked value

						 **/
						if ($edit_discounted_product_collection_checked_value == 1) {
							$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

							$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

							if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

								$old_Edit_DiscountProductCode_price_ruleID = $old_Edit_DiscountProductCode_price_ruleID ? "gid://shopify/DiscountCodeNode/" . $old_Edit_DiscountProductCode_price_ruleID : '';
								// $discount_id_params = "".'id' => $old_Edit_DiscountProductCode_price_ruleID."";
							}

							/**

							 * check productcollection discount code

							 * is exists or not

							 */

							$response = $this->checkCodeExist($old_Edit_DiscountProductCode_Field);
							if (!$response['errors']) {
								/**

								 * Delete product

								 * collection discount code if exists

								 */
							}
							/**

							 * create  update / new

							 * create product colleciton discount code

							 */

							if ($discounted_product_collection_type == 'C') {

								$input = [
									'id' => $old_Edit_DiscountProductCode_price_ruleID,
									'basicCodeDiscount' => [
										'title' => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,
										'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
										'code' =>  $product_collectionCode_Field_code_value,
										'customerSelection' => [
											'all' => false,
											'customerSegments' => [
												'add' => [$segmentId],
											],
										],
										'customerGets' => [
											'value' => [
												'percentage' => 1 * ($product_collectionCode_Field_percentage_value / 100)
											],
											'items' => [
												'collections' => [
													'add' => [$collectionTitle_id]
												],
											],
										],
										'appliesOncePerCustomer' => false,
										'combinesWith' => [
											'shippingDiscounts' => true,
										],
									]
								];
							} else if ($discounted_product_collection_type == 'P') {


								$input = [
									'id' => $old_Edit_DiscountProductCode_price_ruleID,
									'basicCodeDiscount' => [
										'title' => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,
										'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
										'code' =>  $product_collectionCode_Field_code_value,
										'customerSelection' => [
											'all' => false,
											'customerSegments' => [
												'add' => [$segmentId],
											],
										],
										'customerGets' => [
											'value' => [
												'percentage' => 1 * ($product_collectionCode_Field_percentage_value / 100)
											],
											'items' => [
												'products' => [
													'productsToAdd' => [$productTitle_id]
												],
											],
										],
										'appliesOncePerCustomer' => false,
										'combinesWith' => [
											'shippingDiscounts' => true,
										],
									]
								];
							} else {

								$input = [
									'id' => $old_Edit_DiscountProductCode_price_ruleID,
									'basicCodeDiscount' => [
										'title' => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,
										'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
										'code' =>  $product_collectionCode_Field_code_value,
										'customerSelection' => [
											'all' => false,
											'customerSegments' => [
												'add' => [$segmentId],
											],
										],
										'customerGets' => [
											'value' => [
												'percentage' => 1 * ($product_collectionCode_Field_percentage_value / 100)
											],
											'items' => [
												"all" => true,
											],
										],
										'appliesOncePerCustomer' => false,
										'combinesWith' => [
											'shippingDiscounts' => true,
										],
									]
								];
							}

							if (empty($old_Edit_DiscountProductCode_price_ruleID)) {
								$update_discount_productcollection_data = 'mutation CreateDiscountCode($basicCodeDiscount: DiscountCodeBasicInput!) {
									discountCodeBasicCreate(basicCodeDiscount: $basicCodeDiscount) {
										codeDiscountNode {
										id
										}
										userErrors {
										field
										code
										message
										}
									}
								}';
								$data = $this->shopify_graphql_object->GraphQL->post($update_discount_productcollection_data, null, null, $input);

								if ($data) {

									$discounted_product_collection_price_rule_id .= $data['data']['discountCodeBasicCreate']['codeDiscountNode']['id'];
								}
							} else {
								$update_discount_productcollection_data = 'mutation discountCodeBasicUpdate($id: ID!, $basicCodeDiscount: DiscountCodeBasicInput!) {
									discountCodeBasicUpdate(id: $id, basicCodeDiscount: $basicCodeDiscount) {
										codeDiscountNode {
										id
										}
										userErrors {
										field
										code
										message
										}
									}
								}';
								$data = $this->shopify_graphql_object->GraphQL->post($update_discount_productcollection_data, null, null, $input);

								if ($data) {

									$discounted_product_collection_price_rule_id .= $data['data']['discountCodeBasicUpdate']['codeDiscountNode']['id'];
								}
							}
						} else {


							/**

							 * Delete product

							 * collection discount code if exists

							 */


							$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

							$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

							if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

								$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/DiscountCodeNode/" . $old_Edit_DiscountProductCode_price_ruleID;
							}

							if (!empty($old_Edit_DiscountProductCode_Field)) {

								$old_edit_productCollection_price_rule_ID = 'mutation discountCodeDelete($id: ID!) {

									discountCodeDelete(id: $id) {

											deletedCodeDiscountId

											userErrors {

											field

											message

											}

										}

									}';

								$Delete_productcollection_discountCode_checked_input = ["id" => $old_Edit_DiscountProductCode_price_ruleID];

								$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post($old_edit_productCollection_price_rule_ID, null, null, $Delete_productcollection_discountCode_checked_input);
								if ($get_Delete_productcollection_discountCodeID) {
									$discounted_product_collection_price_rule_id = '';
								}
							}
						}



						/**

						 * checked free giftupon

						 * signup checkbox

						 **/



						if ($edit_Free_gift_uponsignup_checkbox == '1') {

							// make condition  of free gift



						}

						/**

						 * saved the

						 * Data with new price rule id in db

						 */

						$whereCondition = [
							'id' => $edit_updated_id,
							'store' => $this->store,
						];

						$value = ['free_shipping_checked_value' => $edit_free_shipping_checked_value, 'free_shipping_code' => $edit_shipping_code_value, 'freeshipping_selected_value' => $check_purchase_amount_value, 'min_purchase_amount_value' => $min_purchase_amount_value, 'min_quantity_items' => $max_purchase_amount_value, 'freeshipping_pricerule_id' => $new_free_shipping_price_rule_ID, 'discounted_product_collection_checked_value' => $edit_discounted_product_collection_checked_value, 'discounted_product_collection_code' => $product_collectionCode_Field_code_value, 'discounted_product_collection_percentageoff' => $product_collectionCode_Field_percentage_value, 'discounted_product_collection_type' => $discounted_product_collection_type, 'discounted__collection_title' => $edit_collectionTitleName, 'discounted__collection_id' => $collectionTitle_id, 'discounted__product_title' => $edit_productTitleName, 'discounted__product_id' => $productTitle_id, 'discounted_product_collection_price_rule_id' => $discounted_product_collection_price_rule_id, 'Free_gift_uponsignup_checkbox' => $edit_Free_gift_uponsignup_checkbox, 'Free_gift_uponsignup_productName' => $free_gift_productTitle, 'gift_uponsignup_variantName' => $Free_gift_uponsignup_variantName, 'free_gift_uponsignupSelectedDays' => $edit_free_gift_uponsignupSelectedDays, 'free_gift_uponsignupSelected_Value' => $edit_free_gift_uponsignupSelectedValue, 'perk_free_gift_product_id' => $edit_free_gift_selected_productid, 'perk_free_gift_variant_id' => $edit_free_gift_selected_variantid, 'custom_perk_checkbox' => $edit_checked_custom_perk, 'no_of_sale_days' => $edit_no_of_sale_days, 'early_access_checked_value' => $edit_early_access_checked_value, 'birthday_rewards' => $edit_birthday_rewards];

						$value = array_filter($value, function ($val, $key) {

							// Always keep these keys, even if value is null or empty

							if ($key === 'freeshipping_pricerule_id' || $key === 'discounted_product_collection_price_rule_id') {

								return true;
							}

							// Otherwise, filter out null and empty string values

							return $val !== null && $val !== '';
						}, ARRAY_FILTER_USE_BOTH);

						$checkSavedData = $this->update_row_perks('membership_perks', $value, $whereCondition, "AND");
						if ($checkSavedData) {

							$saveStatus = false;
						} else {

							$saveStatus = true;
						}

						sleep(1);
					}

					$edit_updated_id = $obj_values['free_shipping'][$count]['edit_updated_id'];

					$edit_free_shipping_checked_value = $obj_values['free_shipping'][$count]['edit_free_shipping_checked_value'];



					$edit_shipping_code_value = $obj_values['free_shipping'][$count]['edit_shipping_code_value'];

					$check_purchase_amount_value = $obj_values['free_shipping'][$count]['check_purchase_amount_value'];

					$min_purchase_amount_value = $obj_values['free_shipping'][$count]['min_purchase_amount_value'];

					$max_purchase_amount_value = $obj_values['free_shipping'][$count]['min_quantity_items'];



					$new_free_shipping_price_rule_ID = '';



					$edit_discounted_product_collection_checked_value = $obj_values['discount_product_collection'][$count]['edit_discounted_product_collection_checked_value'];

					$product_collectionCode_Field_code_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_code_value'];

					$product_collectionCode_Field_percentage_value = $obj_values['discount_product_collection'][$count]['product_collectionCode_Field_percentage_value'];

					$discounted_product_collection_type = $obj_values['discount_product_collection'][$count]['discounted_product_collection_type'];

					$collectionTitle_id = $obj_values['discount_product_collection'][$count]['collectionTitle_id'];

					$productTitle_id = $obj_values['discount_product_collection'][$count]['productTitle_id'];

					$edit_collectionTitleName = $obj_values['discount_product_collection'][$count]['edit_collectionTitleName'];

					$edit_productTitleName = $obj_values['discount_product_collection'][$count]['edit_productTitleName'];

					$discounted_product_collection_price_rule_id = '';



					$edit_Free_gift_uponsignup_checkbox = $obj_values['Free_gift_upon_signup'][$count]['edit_Free_gift_uponsignup_checkbox'];

					$free_gift_productTitle = $obj_values['Free_gift_upon_signup'][$count]['free_gift_productTitle'];

					$edit_free_gift_selected_productid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_productid'];

					$Free_gift_uponsignup_variantName = $obj_values['Free_gift_upon_signup'][$count]['Free_gift_uponsignup_variantName'];

					$edit_free_gift_selected_variantid = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_selected_variantid'];

					$edit_free_gift_uponsignupSelectedDays = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedDays'];

					$edit_free_gift_uponsignupSelectedValue = $obj_values['Free_gift_upon_signup'][$count]['edit_free_gift_uponsignupSelectedValue'];

					$edit_checked_custom_perk = $obj_values['custom_perk'][$count]['checked_custom_perk'];
					$edit_birthday_rewards = $obj_values['birthday_rewards'][$count]['birthday_rewards'] ?? '0';

					$edit_no_of_sale_days = $obj_values['early_sale_access'][$count]['no_of_sale_days'] ?? '';
					$edit_early_access_checked_value = $obj_values['early_sale_access'][$count]['early_access_checked_value'] ?? '0';

					/**

					 * cross checked the

					 * free shipping checked value

					 **/
					if ($edit_free_shipping_checked_value == 1) {

						$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];
						if (empty($old_edit_free_shipping_price_rule_ID)) {
							$old_edit_free_shipingCode = $edit_shipping_code_value;
						}
						/**
						
						 * check discount code

						 * is exists or not

						 */

						$response = $this->checkCodeExist($old_edit_free_shipingCode);

						if (!($response['errors'] ?? false)) {


							$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];

							if (!empty($old_edit_free_shipping_price_rule_ID)) {

								if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

									$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
								}
							}
							// if(!empty($old_edit_free_shipping_price_rule_ID)) {

							// 	if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

							// 		$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
							// 	}

							// 	$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

							// 	discountCodeDelete(id: $id) {

							// 			deletedCodeDiscountId

							// 			userErrors {

							// 			  field

							// 			  message

							// 			}

							// 		  }

							// 		}';

							// 	$Delete_firstFree_shipping_discountCode_checked_input = ["id" => $old_edit_free_shipping_price_rule_ID];
							// 	$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_firstFree_shipping_discountCode_checked_input);
							// 	if ($this->store == 'test-store-phoenixt.myshopify.com') {
							// 		print_r($get_Delete_firstFree_shipping_discountCodeID);
							// 	}
							// 	if ($get_Delete_firstFree_shipping_discountCodeID) {
							// 		$new_free_shipping_price_rule_ID = null;
							// 	}
							// }
						}


						/**

						 * create  update / new

						 * create discount code

						 */

						if (!empty($min_purchase_amount_value)) {



							$input = [
								"id" => $old_edit_free_shipping_price_rule_ID,
								"freeShippingCodeDiscount" => [

									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

									'appliesOncePerCustomer' => false,

									"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

									"code" => $edit_shipping_code_value,

									"combinesWith" => [

										"orderDiscounts" => true,

										"productDiscounts" => true,

									],

									"minimumRequirement" => [

										"subtotal" => [

											"greaterThanOrEqualToSubtotal" => $min_purchase_amount_value,

										],

									],

									"customerSelection" => [

										"all" => false,

										"customerSegments" => [

											"add" => [$segmentId],

										],

									],

									"destination" => [

										"all" => true,

									],

								],

							];
						} else if (!empty($max_purchase_amount_value)) {

							$input = [
								"id" => $old_edit_free_shipping_price_rule_ID,
								"freeShippingCodeDiscount" => [

									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

									'appliesOncePerCustomer' => false,

									"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

									"code" => $edit_shipping_code_value,

									"combinesWith" => [

										"orderDiscounts" => true,

										"productDiscounts" => true,

									],

									"minimumRequirement" => [

										"quantity" => [

											"greaterThanOrEqualToQuantity" => $max_purchase_amount_value,

										],

									],

									"customerSelection" => [

										"all" => false,

										"customerSegments" => [

											"add" => [$segmentId],

										],

									],

									"destination" => [

										"all" => true,

									],

								],

							];
						} else {

							$input = [
								"id" => $old_edit_free_shipping_price_rule_ID,
								"freeShippingCodeDiscount" => [

									"startsAt" => gmdate('Y-m-d\TH:i:s\Z'),

									'appliesOncePerCustomer' => false,

									"title" => $edit_shipping_code_value . '-' . 'membership-app' . '-' . $global_tier_value,

									"code" => $edit_shipping_code_value,

									"combinesWith" => [

										"orderDiscounts" => true,

										"productDiscounts" => true,

									],

									"customerSelection" => [

										"all" => false,

										"customerSegments" => [

											"add" => [$segmentId],

										],

									],

									"destination" => [

										"all" => true,

									],

								],

							];
						}

						if (!empty($old_edit_free_shipping_price_rule_ID)) {
							$updateDiscount_code_Values = 'mutation discountCodeFreeShippingUpdate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!, $id: ID!) {

								discountCodeFreeShippingUpdate(freeShippingCodeDiscount: $freeShippingCodeDiscount, id: $id) {

								codeDiscountNode {

								  id

								  codeDiscount {

									... on DiscountCodeFreeShipping {

									  title

									  startsAt

									}

								  }

								}

								userErrors {

								  field

								  code

								  message

								}

							  }

							}';


							$updateFreeshipping_discountCode = $this->shopify_graphql_object->GraphQL->post($updateDiscount_code_Values, null, null, $input);

							if ($updateFreeshipping_discountCode) {

								$new_free_shipping_price_rule_ID = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingUpdate']['codeDiscountNode']['id'];

								if (!$new_free_shipping_price_rule_ID) {

									$new_free_shipping_price_rule_ID_userErrors = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingUpdate']['userErrors']['message'];

									return json_encode(['message' =>  $new_free_shipping_price_rule_ID_userErrors]);
								}
							}
						} else {
							$updateDiscount_code_Values = 'mutation discountCodeFreeShippingCreate($freeShippingCodeDiscount: DiscountCodeFreeShippingInput!) {

										discountCodeFreeShippingCreate(freeShippingCodeDiscount: $freeShippingCodeDiscount) {

											codeDiscountNode {

											id

											codeDiscount {

												... on DiscountCodeFreeShipping {

												title

												startsAt

												}

											}

											}

											userErrors {

											field

											code

											message

											}

										}

										}';


							$updateFreeshipping_discountCode = $this->shopify_graphql_object->GraphQL->post($updateDiscount_code_Values, null, null, $input);

							if ($updateFreeshipping_discountCode) {

								$new_free_shipping_price_rule_ID = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['codeDiscountNode']['id'];

								if (!$new_free_shipping_price_rule_ID) {

									$new_free_shipping_price_rule_ID_userErrors = $updateFreeshipping_discountCode['data']['discountCodeFreeShippingCreate']['userErrors']['message'];

									return json_encode(['message' =>  $new_free_shipping_price_rule_ID_userErrors]);
								}
							}
						}
					} else {



						/**

						 * Delete existing

						 * Discount code

						 */

						$old_edit_free_shipingCode = $obj_values['free_shipping'][$count]['old_edit_free_shipingCode'];

						$old_edit_free_shipping_price_rule_ID = $obj_values['free_shipping'][$count]['edit_free_shipping_price_rule_ID'];

						if (is_numeric($old_edit_free_shipping_price_rule_ID)) {

							$old_edit_free_shipping_price_rule_ID = "gid://shopify/DiscountCodeNode/" . $old_edit_free_shipping_price_rule_ID;
						}

						if (!empty($old_edit_free_shipingCode)) {



							/**

							 * Before Delete

							 * checked the discount code exists or not

							 */

							$response = $this->checkCodeExist($old_edit_free_shipingCode);

							if (!$response['errors']) {





								/**

								 * Delete the discount

								 * if free shipping  unchecked or have values

								 **/

								$Delete_firstFree_shipping_discountCode = 'mutation discountCodeDelete($id: ID!) {

                                    discountCodeDelete(id: $id) {

										deletedCodeDiscountId

										userErrors {

										field

										message

										}

									}

                                }';

								$new_free_shipping_price_rule_ID = null;

								$Delete_id_input = ["id" => $old_edit_free_shipping_price_rule_ID];

								$get_Delete_firstFree_shipping_discountCodeID = $this->shopify_graphql_object->GraphQL->post($Delete_firstFree_shipping_discountCode, null, null, $Delete_id_input);

								if ($get_Delete_firstFree_shipping_discountCodeID) {

									$new_free_shipping_price_rule_ID = null;
								}
							}
						}
					}



					/**

					 * checked the product colleciton

					 * checked value

					 **/



					if ($edit_discounted_product_collection_checked_value == 1) {

						$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

						$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

						if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

							$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/DiscountCodeNode/" . $old_Edit_DiscountProductCode_price_ruleID;
						}

						/**

						 * check productcollection discount code

						 * is exists or not

						 */

						$response = $this->checkCodeExist($old_Edit_DiscountProductCode_Field);

				  	/**

						 * create  update / new

						 * create product colleciton discount code

						 */

						if ($discounted_product_collection_type == 'C') {



							$input = [
								'id' => $old_Edit_DiscountProductCode_price_ruleID,
								'basicCodeDiscount' => [
									'title' => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,
									'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
									'code' =>  $product_collectionCode_Field_code_value,
									'customerSelection' => [
										'all' => false,
										'customerSegments' => [
											'add' => [$segmentId],
										],
									],
									'customerGets' => [
										'value' => [
											'percentage' => 1 * ($product_collectionCode_Field_percentage_value / 100)
										],
										'items' => [
											'collections' => [
												'add' => [$collectionTitle_id]
											],
										],
									],
									'appliesOncePerCustomer' => false,
									'combinesWith' => [
										'shippingDiscounts' => true,
									],
								]
							];
						} else if ($discounted_product_collection_type == 'P') {



							$input = [
								'id' => $old_Edit_DiscountProductCode_price_ruleID,
								'basicCodeDiscount' => [
									'title' => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,
									'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
									'code' =>  $product_collectionCode_Field_code_value,
									'customerSelection' => [
										'all' => false,
										'customerSegments' => [
											'add' => [$segmentId],
										],
									],
									'customerGets' => [
										'value' => [
											'percentage' => 1 * ($product_collectionCode_Field_percentage_value / 100)
										],
										'items' => [
											'products' => [
												'productsToAdd' => [$productTitle_id]
											],
										],
									],
									'appliesOncePerCustomer' => false,
									'combinesWith' => [
										'shippingDiscounts' => true,
									],
								]
							];
						} else {



							$input = [
								'id' => $old_Edit_DiscountProductCode_price_ruleID,
								'basicCodeDiscount' => [
									'title' => $product_collectionCode_Field_code_value . '-' . "membership-app" . '-' . $global_tier_value,
									'startsAt' => gmdate("Y-m-d\T00:00:00\Z"),
									'code' =>  $product_collectionCode_Field_code_value,
									'customerSelection' => [
										'all' => false,
										'customerSegments' => [
											'add' => [$segmentId],
										],
									],
									'customerGets' => [
										'value' => [
											'percentage' => 1 * ($product_collectionCode_Field_percentage_value / 100)
										],
										'items' => [
											"all" => true,
										],
									],
									'appliesOncePerCustomer' => false,
									'combinesWith' => [
										'shippingDiscounts' => true,
									],
								]
							];
						}


						if (empty($old_Edit_DiscountProductCode_price_ruleID)) {
							$update_discount_productcollection_data = 'mutation CreateDiscountCode($basicCodeDiscount: DiscountCodeBasicInput!) {
								discountCodeBasicCreate(basicCodeDiscount: $basicCodeDiscount) {
									codeDiscountNode {
									id
									}
									userErrors {
									field
									code
									message
									}
								}
							}';
							$data = $this->shopify_graphql_object->GraphQL->post($update_discount_productcollection_data, null, null, $input);

							if ($data) {

								$discounted_product_collection_price_rule_id .= $data['data']['discountCodeBasicCreate']['codeDiscountNode']['id'];
							}
						} else {
							$update_discount_productcollection_data = 'mutation discountCodeBasicUpdate($id: ID!, $basicCodeDiscount: DiscountCodeBasicInput!) {
								discountCodeBasicUpdate(id: $id, basicCodeDiscount: $basicCodeDiscount) {
									codeDiscountNode {
									id
									}
									userErrors {
									field
									code
									message
									}
								}
							}';
							$data = $this->shopify_graphql_object->GraphQL->post($update_discount_productcollection_data, null, null, $input);

							if ($data) {

								$discounted_product_collection_price_rule_id .= $data['data']['discountCodeBasicUpdate']['codeDiscountNode']['id'];
							}
						}
					} else {



						/**

						 * Delete product

						 * collection discount code if exists

						 */


						$old_Edit_DiscountProductCode_Field = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_Field'];

						$old_Edit_DiscountProductCode_price_ruleID = $obj_values['discount_product_collection'][$count]['old_Edit_DiscountProductCode_price_ruleID'];

						if (is_numeric($old_Edit_DiscountProductCode_price_ruleID)) {

							$old_Edit_DiscountProductCode_price_ruleID = "gid://shopify/DiscountCodeNode/"  . $old_Edit_DiscountProductCode_price_ruleID;
						}

						if (!empty($old_Edit_DiscountProductCode_Field)) {



							$old_edit_productCollection_price_rule_ID = 'mutation discountCodeDelete($id: ID!) {

							 	discountCodeDelete(id: $id) {

							 			deletedCodeDiscountId

							 			userErrors {

							 			field

							 			message

							 			}

							 		}

							 	}';

							$Delete_productcollection_discountCode_checked_input = ["id" => $old_Edit_DiscountProductCode_price_ruleID];

							$get_Delete_productcollection_discountCodeID = $this->shopify_graphql_object->GraphQL->post(
								$old_edit_productCollection_price_rule_ID,
								null,
								null,

								$Delete_productcollection_discountCode_checked_input
							);

							if ($get_Delete_productcollection_discountCodeID) {
								$discounted_product_collection_price_rule_id = '';
							}
						}
					}
					/**

					 * checked free giftupon

					 * signup checkbox

					 **/

					// Free gift upon signup

					if ($edit_Free_gift_uponsignup_checkbox == '1') {

						// make condition  of free gift

					}

					/**

					 * saved the

					 * Data with new price rule id in db

					 */

					$whereCondition = [
						'id' => $edit_updated_id,
						'store' => $this->store,
					];
					$value = ['free_shipping_checked_value' => $edit_free_shipping_checked_value ? 1 : 0, 'free_shipping_code' => $edit_shipping_code_value, 'freeshipping_selected_value' => $check_purchase_amount_value, 'min_purchase_amount_value' => $min_purchase_amount_value, 'min_quantity_items' => $max_purchase_amount_value, 'freeshipping_pricerule_id' => $new_free_shipping_price_rule_ID, 'discounted_product_collection_checked_value' => $edit_discounted_product_collection_checked_value ? 1 : 0, 'discounted_product_collection_code' => $product_collectionCode_Field_code_value, 'discounted_product_collection_percentageoff' => $product_collectionCode_Field_percentage_value, 'discounted_product_collection_type' => $discounted_product_collection_type, 'discounted__collection_title' => $edit_collectionTitleName, 'discounted__collection_id' => $collectionTitle_id, 'discounted__product_title' => $edit_productTitleName, 'discounted__product_id' => $productTitle_id, 'discounted_product_collection_price_rule_id' => $discounted_product_collection_price_rule_id, 'Free_gift_uponsignup_checkbox' => $edit_Free_gift_uponsignup_checkbox ? 1 : 0, 'Free_gift_uponsignup_productName' => $free_gift_productTitle, 'gift_uponsignup_variantName' => $Free_gift_uponsignup_variantName, 'free_gift_uponsignupSelectedDays' => $edit_free_gift_uponsignupSelectedDays, 'free_gift_uponsignupSelected_Value' => $edit_free_gift_uponsignupSelectedValue, 'perk_free_gift_product_id' => $edit_free_gift_selected_productid, 'perk_free_gift_variant_id' => $edit_free_gift_selected_variantid, 'custom_perk_checkbox' => $edit_checked_custom_perk ? 1 : 0, 'no_of_sale_days' => $edit_no_of_sale_days, 'early_access_checked_value' => $edit_early_access_checked_value ? 1 : 0, 'birthday_rewards' => $edit_birthday_rewards];
					$value = array_filter($value, function ($val, $key) {

						// Always keep these keys, even if value is null or empty

						if ($key === 'freeshipping_pricerule_id' || $key === 'discounted_product_collection_price_rule_id') {

							return true;
						}

						// Otherwise, filter out null and empty string values

						return $val !== null && $val !== '';
					}, ARRAY_FILTER_USE_BOTH);


					$this->update_row_perks('membership_perks', $value, $whereCondition, "AND");

					sleep(1);
				}

				$status = false;

				$message = 'Data has been updated successfully!';

				$this->upsertMetaObject();
				return json_encode(['status' => $status, 'message' => $message]);
			} else {

				$status = true;

				$message = 'No changes in the tier form!';
				$this->upsertMetaObject();
				return json_encode(['status' => $status, 'message' => $message]);
			}
		}
	}

	public function createMetaObject()
	{


		try {

			$graphql_metaobject = 'mutation CreateMetaobjectDefinition($definition: MetaobjectDefinitionCreateInput!) {
                metaobjectDefinitionCreate(definition: $definition) {
                  metaobjectDefinition {
                    name
                    type
                    fieldDefinitions {
                      name
                      key
                    }
                  }
                  userErrors {
                    field
                    message
                    code
                  }
                }
              }';
			$definition = [
				"definition" => [
					"access" => ["storefront" => "PUBLIC_READ"],
					"name" => "memberships-json-data",
					"type" => "sd-memberships-json-definition-phoenix",
					"description" => "Do not remove",
					"capabilities" => [
						"onlineStore" => [
							"data" => ["urlHandle" => "advanced-memberships-handle"],
							"enabled" => true,
						],
						"publishable" => ["enabled" => true]
					],
					"fieldDefinitions" => [
						[
							"name" => "membershipsMetaobjectDefinition",
							"key" => "sd_memberships_metaobject_definition",
							"type" => "json"
						],
					]
				]
			];

			$metobjData = $this->shopify_graphql_object->GraphQL->post($graphql_metaobject, null, null, $definition);		
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function createCustomerMetaObject()
	{
		try {

			$graphql_metaobject = 'mutation CreateMetaobjectDefinition($definition: MetaobjectDefinitionCreateInput!) {
                metaobjectDefinitionCreate(definition: $definition) {
                  metaobjectDefinition {
                    name
                    type
                    fieldDefinitions {
                      name
                      key
                    }
                  }
                  userErrors {
                    field
                    message
                    code
                  }
                }
              }';
			$definition = [
				"definition" => [
					"access" => ["storefront" => "PUBLIC_READ"],
					"name" => "memberships-customers-json-data",
					"type" => "sd-customers-json-definition",
					"description" => "Do not remove",
					"capabilities" => [
						"onlineStore" => [
							"data" => ["urlHandle" => "membership-customers-handle"],
							"enabled" => true,
						],
						"publishable" => ["enabled" => true]
					],
					"fieldDefinitions" => [
						[
							"name" => "customersMetaobjectDefinition",
							"key" => "sd_customers_metaobject_definition",
							"type" => "json"
						],
					]
				]
			];

			$metobjData = $this->shopify_graphql_object->GraphQL->post($graphql_metaobject, null, null, $definition);
			// echo'createCustomerMetaObject';
			// print_r($metobjData);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}


	function getMetaObjectDefinition()
	{
		try {
			$getDefinition = <<<Query
		{
				metaobjectDefinitionByType(type: "sd-memberships-json-definition-phoenix") {
						id
				}
		}
		Query;
			$metaDefData = $this->shopify_graphql_object->GraphQL->post($getDefinition);
			$checkId = $metaDefData['data']['metaobjectDefinitionByType'];
			if (!($checkId)) {
				$definitionExists = "no";
			} else {
				$definitionExists = "yes";
			}
			return $definitionExists;
		} catch (Exception $e) {
			// echo $e->getMessage();
		}
	}


	function getCustomerMetaObjectDefinition()
	{

		try {
			$getDefinition = <<<Query
                {
                    metaobjectDefinitionByType(type: "sd-customers-json-definition") {
                        id
                    }
                }
            Query;

			$metaDefData = $this->shopify_graphql_object->GraphQL->post($getDefinition);
			$checkId = $metaDefData['data']['metaobjectDefinitionByType'];
			if (!($checkId)) {
				$definitionExists = "no";
			} else {
				$definitionExists = "yes";
			}
			// echo'definitionExists'.$definitionExists;

			return $definitionExists;
		} catch (Exception $e) {
			// echo $e->getMessage();
		}
	}


	function upsertMetaObject()
	{
		$checkMetaDefinitionExixts = $this->getMetaObjectDefinition();
		// $checkMetaDefinitionExixts = $this->getCustomerMetaObjectDefinition();
		// $this->upsertCustomerMetaObject();
		// if ($checkMetaDefinitionExixts == 'no') {
		// 	// $this->createCustomerMetaObject();
		// }
		if ($checkMetaDefinitionExixts == 'no') {
			$this->createMetaObject();
		}
		$sql = "SELECT * FROM count_down_settings WHERE store = :store LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([':store' => $this->store]);
		$getCountDownData = $stmt->fetch(PDO::FETCH_OBJ);

		$sql = "
			SELECT *
			FROM membership_plan_groups
			INNER JOIN membership_plans ON membership_plans.id = membership_plan_groups.membership_plan_id
			INNER JOIN membership_perks ON membership_perks.membership_group_id = membership_plan_groups.membership_group_id
			INNER JOIN membership_product_details ON membership_product_details.membership_group_id = membership_perks.membership_group_id
			WHERE membership_plan_groups.store = :store
			AND membership_plans.plan_status = 'enable'
		";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([':store' => $this->store]);
		$member_groups_perks_product = $stmt->fetchAll(PDO::FETCH_OBJ);
		// print_r($member_groups_perks_product);
		// Get membership plans as objects
		$sql1 = "SELECT * FROM membership_plans WHERE store = :store";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([':store' => $this->store]);
		$member_plan_data = $stmt1->fetchAll(PDO::FETCH_OBJ);

		// Get drawer setting as object
		$sql2 = "SELECT * FROM drawer_settings WHERE store = :store LIMIT 1";
		$stmt2 = $this->db->prepare($sql2);
		$stmt2->execute([':store' => $this->store]);
		$drawer_setting = $stmt2->fetch(PDO::FETCH_OBJ);

		// Get product widget setting as object
		$sql3 = "SELECT * FROM product_widget_settings WHERE store = :store LIMIT 1";
		$stmt3 = $this->db->prepare($sql3);
		$stmt3->execute([':store' => $this->store]);
		$product_widget_settings = $stmt3->fetch(PDO::FETCH_OBJ);

		// Get plans widget setting as object
		$sql4 = "SELECT * FROM member_plans_widgets WHERE store = :store LIMIT 1";
		$stmt4 = $this->db->prepare($sql4);
		$stmt4->execute([':store' => $this->store]);
		$plans_widget_settings = $stmt4->fetch(PDO::FETCH_OBJ);

		// Get early sale access as object
		$sql5 = "SELECT * FROM membership_early_sales WHERE store = :store LIMIT 1";
		$stmt5 = $this->db->prepare($sql5);
		$stmt5->execute([':store' => $this->store]);
		$early_sale_access = $stmt5->fetch(PDO::FETCH_OBJ);

		// Get membership group details as objects
		$sql6 = "SELECT * FROM membership_groups_details WHERE store = :store";
		$stmt6 = $this->db->prepare($sql6);
		$stmt6->execute([':store' => $this->store]);
		$member_group_details = $stmt6->fetchAll(PDO::FETCH_OBJ);

		// $birthdayFormData = DB::table('birthday_widget_settings')->where('birthday_widget_settings.store', $this->store)->first();
		// $getBirthdayRecords = DB::table('birth_date_records')->where('birth_date_records.store', $this->store)->get();
		$getBirthdayRecords = $birthdayFormData = '';
		// Create an associative array with variable names as keys and values as values
		$variablesArray = [
			'getCountDownData' => $getCountDownData,
			'member_groups_perks_product' => $member_groups_perks_product,
			'member_plan_data' => $member_plan_data,
			'drawer_setting' => $drawer_setting,
			'product_widget_settings' => $product_widget_settings,
			'plans_widget_settings' => $plans_widget_settings,
			'member_group_details' => $member_group_details,
			'early_sale_access' => $early_sale_access,
			'birthdayFormData' => $birthdayFormData,
			'getBirthdayRecords' => $getBirthdayRecords,
		];

		$jsonArray = json_encode($variablesArray);

		try {
			$metaObjectCreate = 'mutation metaobjectUpsert($handle: MetaobjectHandleInput!, $metaobject: MetaobjectUpsertInput!) {
                metaobjectUpsert(handle: $handle, metaobject: $metaobject) {
                metaobject {
                    id
                }
                userErrors {
                    field
                    message
                }
                }
            }';

			$dataInput = [
				"handle" => [
					"handle" => "advanced-memberships-handle",
					"type" => "sd-memberships-json-definition-phoenix"
				],
				"metaobject" => [
					"capabilities" => [
						"publishable" => [
							"status" => "ACTIVE"
						]
					],
					"fields" => [
						[
							"key" => "sd_memberships_metaobject_definition",
							"value" => "$jsonArray"
						]
					],
					"handle" => "advanced-memberships-handle"
				]
			];
			$metobjData = $this->shopify_graphql_object->GraphQL->post($metaObjectCreate, null, null, $dataInput);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	function upsertCustomerMetaObject()
	{

		$checkMetaDefinitionExixts = $this->getCustomerMetaObjectDefinition();

		if ($checkMetaDefinitionExixts == 'no') {
			$this->createCustomerMetaObject();
		}

		// Get countdown settings (single row as object)
		$sql1 = "SELECT * FROM count_down_settings WHERE store = :store LIMIT 1";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([':store' => $this->store]);
		$getCountDownData = $stmt1->fetch(PDO::FETCH_OBJ);

		// Get customer membership perk data (multiple rows as objects)
		$sql2 = "
			SELECT 
				mp.free_shipping_code, 
				mp.freeshipping_selected_value, 
				mp.min_quantity_items, 
				mp.min_purchase_amount_value, 
				mp.discounted_product_collection_code, 
				mp.discounted_product_collection_percentageoff, 
				mp.discounted_product_collection_type, 
				mp.discounted__product_title, 
				mp.discounted__collection_title, 
				pmd.shopify_customer_id, 
				mp.early_access_checked_value, 
				mp.no_of_sale_days
			FROM membership_perks mp
			JOIN subscritionOrderContractProductDetails pmpd ON pmpd.variant_id = mp.variant_id
			JOIN subscriptionOrderContract pmd ON pmd.contract_id = pmpd.contract_id
			WHERE mp.store = :store
			AND pmd.contract_status = 'A'
		";
		try {
			//code...
			// print_r($sql2);
			$stmt2 = $this->db->prepare($sql2);
			$stmt2->execute([':store' => $this->store]);
			$customerData = $stmt2->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) {
			echo $e->getMessage();
			// throw $th;
		}




		// Create an associative array with variable names as keys and values as values
		$variablesArray = [
			// 'membership_customer_data' => $membership_customer_data,
			'customerData' => $customerData,
		];

		$customerJsonArray = json_encode($variablesArray);

		try {

			$metaObjectCreate = 'mutation metaobjectUpsert($handle: MetaobjectHandleInput!, $metaobject: MetaobjectUpsertInput!) {
                metaobjectUpsert(handle: $handle, metaobject: $metaobject) {
                metaobject {
                    id
                }
                userErrors {
                    field
                    message
                }
                }
            }';

			$dataInput = [
				"handle" => [
					"handle" => "membership-customers-handle",
					"type" => "sd-customers-json-definition"
				],
				"metaobject" => [
					"capabilities" => [
						"publishable" => [
							"status" => "ACTIVE"
						]
					],
					"fields" => [
						[
							"key" => "sd_customers_metaobject_definition",
							"value" => "$customerJsonArray"
						]
					],
					"handle" => "membership-customers-handle"
				]
			];
			$metobjData = $this->shopify_graphql_object->GraphQL->post($metaObjectCreate, null, null, $dataInput);
			// echo'upsertCustomerMetaObject';
			// print_r($metobjData);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	//get data memberplan

	public function get_member_plan($request)
	{
		// Decode the incoming JSON string from 'data'
		$store = trim($this->store);
		// echo($store);
		$data = json_decode($request['data'], true);
		$member_plan_id = $data['member_plan_id'];

		// Optional: debug the raw request
		// print_r($request); die;

		// If you're in Laravel and `$request` is an instance of Request:
		// $get_authorization_token = $request->header('Authorization');
		// echo($get_authorization_token);

		// if (!$get_authorization_token) {
		// 	return json_encode(['isError' => true, 'message' => 'Authorization token not provided.']);
		// }

		try {
			$sql = "SELECT * FROM membership_groups_details 
			WHERE membership_plan_id = :member_plan_id 
			AND store = :store";

			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':member_plan_id' => $member_plan_id,
				':store' => $store
			]);

			$get_groupby_data = $stmt->fetchAll(PDO::FETCH_OBJ);
			// $get_groupby_data = $get_member_group_details->groupBy('id');
			// print_r($get_groupby_data);
			// die;

		} catch (Exception $e) {
			echo $e->getMessage();
			return json_encode(['isError' => true, 'message' => 'Failed to fetch group details.']);
		}

		try {

			// echo($member_plan_id);
			$sql_get_query = "
				SELECT 
					membership_plans.membership_plan_name,
					membership_plans.id,
					membership_plans.membership_product_url,
					membership_plans.membership_product_description,
					membership_plan_groups.membership_group_id,
					membership_plan_groups.membership_plan_id,
					membership_plan_groups.membership_group_name,
					membership_plan_groups.unique_handle,
					membership_plan_groups.variant_id,
					membership_plan_groups.group_description,
					membership_plan_groups.popular_plan,
					membership_plan_groups.group_status,
					membership_product_details.membership_group_id,
					membership_product_details.product_id,
					membership_product_details.variant_id,
					membership_product_details.variant_price,
					membership_product_details.product_name,
					membership_product_details.image_path
				FROM membership_plans
				JOIN membership_plan_groups 
					ON membership_plan_groups.membership_plan_id = membership_plans.id
				JOIN membership_product_details 
					ON membership_product_details.membership_group_id = membership_plan_groups.membership_group_id
				WHERE membership_plans.id = :member_plan_id
				AND membership_plans.store = :store
				AND membership_plan_groups.group_status = 'enable';
			";

			$stmt = $this->db->prepare($sql_get_query);
			$stmt->execute([
				':member_plan_id' => $member_plan_id,
				':store' => $store
			]);

			$get_member_plan_data = $stmt->fetchAll(PDO::FETCH_OBJ);
			// print_r($get_member_plan_data);

		} catch (Exception $e) {
			echo $e->getMessage();
			return json_encode(['isError' => true, 'message' => 'Failed to fetch plan data.']);
		}

		// $this->upsertMetaObject($store); // Assuming this method logs/syncs something

		// Reindex member group data by membership_group_id for JS compatibility
		// $group_data_indexed = [];

		foreach ($get_groupby_data as $item) {
			$groupId = $item->membership_group_id;
			if (!isset($group_data_indexed[$groupId])) {
				$group_data_indexed[$groupId] = [];
			}
			$group_data_indexed[$groupId][] = $item;
		}
		// print_r($group_data_indexed);

		// Get first group ID from member_plan_data if available
		$first_group_id = null;
		if (!empty($get_member_plan_data)) {
			$first_group_id = $get_member_plan_data[0]->membership_group_id;
		}

		return json_encode([
			'isError' => false,
			'member_plan_data' => $get_member_plan_data,
			'member_group_detail' => $group_data_indexed
		]);
	}

	// edit member plan
	public function edit_membership_plan($request)
	{
		// print_r($request);
		$store = trim($this->store);
		$data = json_decode($request['data'], true);

		// Access the 'member_plan_group_product_data' array
		$member_plan_group_product_data = $data['member_plan_group_product_data'];

		$member_product_id = $member_plan_group_product_data[0]['product_id'];

		// Access other data fields
		$memberPlan_id = $data['memberPlan_id'];
		$member_plan_name = $data['member_plan_name'];
		$member_plan_image_url = $data['member_plan_image_url'];
		$member_plan_description = $data['member_plan_description'];

		// Prepare and execute query
		$sql = "SELECT * FROM membership_plans 
        WHERE LOWER(membership_plan_name) = :plan_name 
        AND id != :plan_id 
        AND store = :store";

		$stmt = $this->db->prepare($sql);

		$stmt->execute([
			':plan_name' => strtolower($member_plan_name),
			':plan_id'   => $memberPlan_id,
			':store'     => $store
		]);

		$existingPlan = $stmt->fetch(PDO::FETCH_OBJ);

		if ($existingPlan) {
			$result = array('isError' => true, 'message' => "Plan name with same name already exists", 'membership_plan_id' => $memberPlan_id);
			return json_encode($result); // return json
		} else {
			try {
				// $update_member_plan_product = 'mutation productUpdate($input: ProductInput!) {
				// 	productUpdate(input: $input) {
				// 		product {
				// 			id
				// 			images(first:10){
				// 			edges{
				// 				node{
				// 					src
				// 				}
				// 			}
				// 			}
				// 			variants(first:100){
				// 				edges{
				// 					node{
				// 					id
				// 					price
				// 					}
				// 				}
				// 			}
				// 		}
				// 		userErrors {
				// 			field
				// 			message
				// 		}
				// 	}
				// }';

				// if (empty($member_plan_image_url)) {
				// 	$input = [
				// 		"input" => [
				// 			"id" => "gid://shopify/Product/" . $member_product_id,
				// 			"title" => $member_plan_name,
				// 			"bodyHtml" => $member_plan_description,
				// 		],
				// 	];
				// } else {
				// 	$input = [
				// 		"input" => [
				// 			"id" => "gid://shopify/Product/" . $member_product_id,
				// 			"title" => $member_plan_name,
				// 			"bodyHtml" => $member_plan_description,
				// 			"images" => array([
				// 				"altText" => "test",
				// 				"src" => $member_plan_image_url,
				// 			]),
				// 		],
				// 	];
				// }

				$update_member_plan_product = 'mutation productUpdate($product: ProductUpdateInput!) {
					productUpdate(product: $product) {
						product {
							id
							media(first:10){
							edges{
								node{
									preview{
										image {
											url
										}
									}
								}
							}
							}
							variants(first:100){
								edges{
									node{
									id
									price
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

				if (empty($member_plan_image_url)) {
					$product = [
						"product" => [
							"id" => "gid://shopify/Product/" . $member_product_id,
							"title" => $member_plan_name,
							"descriptionHtml" => $member_plan_description,
						],
					];
				} else {
					$product = [
						"product" => [
							"id" => "gid://shopify/Product/" . $member_product_id,
							"title" => $member_plan_name,
							"descriptionHtml" => $member_plan_description,
							"images" => array([
								"alt" => "test",
								"originalSource" => $member_plan_image_url,
								"mediaContentType" => "IMAGE"
							]),
						],
					];
				}
				$update_product_execution = $this->shopify_graphql_object->GraphQL->post($update_member_plan_product, null, null, $product);
				$updateProductapi_error = $update_product_execution['data']['productUpdate']['userErrors'];
				// if($store=='4f0430-2.myshopify.com'){
				//     echo'<pre>';
				// print_r($update_product_execution);

				// }
				if (!count($updateProductapi_error)) {

					// $member_plan_data = array(
					// 	"id" => $memberPlan_id,
					// 	"store" => $store,
					// 	"membership_plan_name" => trim($member_plan_name),
					// 	"membership_product_description" => trim($member_plan_description),
					// );
					// $update_member_plan = membership_plans::upsert($member_plan_data,['store','id'],['store','membership_plan_name','membership_product_description']);

					$memberPlanDataArray = [
						'store' => $store,
						'id' => $memberPlan_id,
						'membership_plan_name' => $member_plan_name,
						'membership_product_description' => $member_plan_description,
						'updated_at' => date('Y-m-d H:i:s')
					];

					$whereCondition = [
						'store' => $store,
						'id' => $memberPlan_id
					];

					// Insert or update the data in the database
					$checkedUpdate = $this->insertupdateajax('membership_plans', $memberPlanDataArray, $whereCondition, "AND");

					$productDataArray = [
						'image_path' => $member_plan_image_url,
						'product_name' => trim($member_plan_name),
						'updated_at' => date('Y-m-d H:i:s')
					];

					$whereCondition = [
						'store' => $store,
						'product_id' => $member_product_id
					];

					// DB::table('membership_product_details')->where('product_id', $member_product_id)->update(['image_path' => $member_plan_image_url, 'product_name' => trim($member_plan_name)]);
					$checkedUpdateProductDetails = $this->update_row('membership_product_details', $productDataArray, $whereCondition, "AND");

					$result = array('isError' => false, 'message' => "Membership plan updated successfully");

					// print_r($result);die;
					$this->upsertMetaObject();
					return json_encode($result); // return json
				} else {
					$result = array('isError' => true, 'message' => "Something went wrong");
					return json_encode($result); // return json
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}
	public function membershipAllEmailTemplates($template_data)
	{

		$store_name = $template_data['shop_name'];
		$template_name = $template_data['template_name'];

		$free_shipping_condition = $template_data['free_shipping_coupon_content'] ?? '';
		$discount_coupon_condition = $template_data['discount_coupon_content'] ?? '';
		$free_signup_product_condition = $template_data['free_signup_product_content'] ?? '';
		$free_gift_uponsignupSelectedDays = $template_data['free_gift_uponsignupSelectedDays'] ?? ' ';
		// $show_template_for = $template_data['email_type'];
		$sql = "SELECT * FROM `$template_name` WHERE `store` = :store LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['store' => $this->store]);

		$template_setting = $stmt->fetch(PDO::FETCH_OBJ);
		// print_r($template_setting);
		// die;
		$custom_email_html = isset($template_setting->custom_email_html) ? $template_setting->custom_email_html : '';
		if (empty((array) $template_setting)) {

			$ccc_email = '';

			$bcc_email = '';

			$reply_to = '';

			$content_heading = '';

			$logo = $this->image_folder + "logo.png";



			// Set default values for the email template fields

			$customer_name = 'Honey Bailey';

			$heading_text_color = '#000000';

			$header_bg_color = '#50E1B0';

			$footer_bg_color = '#50E1B0';

			$logo_height = '38';

			$logo_width = '170';

			$logo_alignment = 'Left';

			$manage_button_url = 'https://' . $store_name . '/account';

			$manage_btn_text_color = '#000000';

			$manage_btn_bg_color = '#50E1B0';

			$custom_email_html = null;

			$content_background = '#FFFFFF;';

			$email_background_color = '#F3F3F3';

			$button_content_bg_color = '#FFFFFF';

			$logo_content_bg_color = '#FFFFFF';

			$logo_bg_color = '#FFFFFF';

			$tick_bg_color = '#50E1B0';

			$gift_link_text = 'Redeem now';



			switch ($template_name) {

				case "new_purchase_plans":

					$subject = 'New Membership';

					$heading_text = 'Congratulations !';

					$footer_text = 'Thank you';

					$manage_button = 'View Account';

					$feature_heading = 'Exclusive Benefits Await You!';



					$discount_coupon_content = '<h2 style="text-align:inherit"><b>Exclusive Discounts</b></h2><div>Apply <b>{{coupon_code}}</b> coupon code during checkout to avail an exciting <b>{{percentage_discount}}% off</b> discount on your total purchase. Treat yourself or your loved ones to our premium products while saving big.</div>';



					$free_shipping_coupon_content = '<h2 style="text-align:inherit"><b>Free Shipping Offer</b></h2><div>Use <b>{{free_shipping_code}}</b> coupon code during checkout to enjoy free shipping on your order. Now, shop with ease and have your favorite products delivered right to your doorstep without any extra shipping charges!</div>';



					$free_signup_product_content = '<h2 style="text-align:inherit"><b>Free Sign Up Product</b></h2><div>Your free sign-up product, <b> {{free_signup_product}} </b> is ready for you to enjoy. Your Free Signup Product Date is <b>{{free_sign_up_produt_date}}</b></div>';



					$free_gift_signup_product = '<h2 style="text-align:inherit"><b>Immediate Sign Up Gift</b></h2><div>This gift is one of our most popular products in the store, and we know you are going to love it!. To receive your <b> {{free_signup_product}} </b>, simply click on this link:</div>';



					$footer_content = '<p>Thank you for choosing our membership plan <b>( {{plan_name}} )</b>! We look forward to helping you succeed.<br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>We value your business and appreciate your understanding.<br><br>Sincerely,<br>{{store_name}}</p>';



					$content_heading_text = '<p>Hey <b> {{customer_name}} </b>, <br><br></p> <p> We are excited to have you on board as a new member. Your membership has started today. During that time, you will enjoy a host of exclusive benefits. <br><br> We are confident that our membership program will help you achieve your goals and reach your full potential. We are here to support you every step of the way.</p>';



					$content_heading = '<table style="width:100%; border-spacing:0; border-collapse:collapse;">
 
                                <tbody>
 
                                    <tr>
 
                                        <td style="border-width:0; padding-top: 10px; padding-bottom: 25px;">
 
                                            <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">
 
                                                <tbody >
 
                                                <tr>
 
                                                    <td><div class="sd_content_heading_text_view">' . $content_heading_text . '</div>
 
                                                    </td>
 
                                                </tr>
 
                                                </tbody>
 
                                            </table>
 
                                        </td>
 
                                    </tr>
 
                                </tbody>
 
                            </table>
 
                           <table style="width:100%; border-spacing:0; border-collapse:collapse;">
 
                                    <tbody>
 
                                        <tr>
 
                                            <td style="padding-top: 0; padding-bottom: 20px; border-width:0;">
 
                                                <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">
 
                                                <thead><th>';

					$content_heading .= '<h2 style="font-size: 16px; font-style: normal; font-weight: 600;" class="sd_feature_heading_view" >' . $feature_heading . '</h2>';

					$content_heading .= '</th></thead>
 
                                                    <tbody>
 
                                                        <tr>
 
                                                            <td style="border-radius: 10px; padding: 28px 33px;">
 
                                                                <ul style="padding: 0; list-style: none; margin: 0;">';



					if ($discount_coupon_condition == true) {

						$content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_discount_coupon_content_view">' . $discount_coupon_content . '</div></li>';
					}



					if ($free_shipping_condition == true) {

						$content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_shipping_coupon_content_view">' . $free_shipping_coupon_content . '</div></li>';
					}



					if ($free_signup_product_condition == true) {

						$content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_signup_product_content_view">' . $free_signup_product_content . '</div></li>';
					}

					if ($free_gift_uponsignupSelectedDays == 'Immediately_after_signup' || $free_gift_uponsignupSelectedDays === true) {

						$content_heading .= '<li style="display:flex;font-size:15px;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_gift_signup_product_view">' . $free_gift_signup_product . '</div></li><br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
					}

					$content_heading .= '</ul>
 
                                                            </td>
 
                                                        </tr>
 
                                                    </tbody>
 
                                                </table>
 
                                            </td>
 
                                        </tr>
 
                                            <td>';

					$content_heading .= '<div class="sd_footer_content_view">' . $footer_content . '</div>';

					$content_heading .= '</td>
 
                                        <tr>
 
                                        </tr>
 
                                    </tbody>
 
                                </table>';

					break;



				case "plan_payment_pendings":

					$subject = 'Your Plan Payment is Pending.';

					$heading_text = 'Plan Payment Pending';

					$footer_text = 'Thank You';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> I am writing to let you know that your payment for your <b> {{plan_name}} </b>is Pending. Please make your payment to avoid service interruption.<br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>We value your business and appreciate your understanding.<br><br>Sincerely,<br>{{store_name}}</p>';

					break;

				case "credit_card_expirings":

					$subject = 'Credit Card Expiring Soon';

					$heading_text = 'Credit Card Expiring';

					$footer_text = 'Thank You';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> Your credit card for your <b> {{plan_name}} </b>subscription is expiring soon. Please update your payment information to avoid service interruption.You can update your payment information by logging into your account.<br><br>Thank you for your continued business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					break;

				case "plan_payment_faileds":

					$subject = 'Your Plan Payment is Failed';

					$heading_text = 'Payment failed';

					$footer_text = 'Thank you';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We noticed that your payment for your {{plan_name}} subscription failed. Please update your payment information or make a payment to avoid service interruption.<br><br>You can update your payment information or make a payment by logging into your account<br><br>if you are having trouble making a payment, please contact our customer support team for assistance.<br><br>Thank you for your attention to this matter.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					break;



				case "membership_free_gifts":

					$subject = 'You get the free gift"';

					$heading_text = 'Hurray! you get the free gift';

					$footer_text = 'Thank you';

					$manage_button = 'View Account';

					$gift_link_text = 'Redeem now';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We are so excited to have you as a member ! As a thank-you for signing up, we are giving you a free gift. Your gift is a <b> {{free_signup_product}} </b>. We think you will love it!. We hope you enjoy your gift. We are always adding new products and services, so be sure to check back often.<br><br> To claim your gift, simply click on the link below:</p>';

					$content_heading .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';

					break;



				case "plan_payment_declineds":

					$subject = 'Your Payment has been declined';

					$heading_text = 'Transaction Declined';

					$footer_text = 'Thank you';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We noticed that your payment for your {{plan_name}} subscription was declined. Please update your payment information or make a payment to avoid service interruption.<br><br>You can update your payment information or make a payment by logging into your account.If you are having trouble making a payment, please contact our customer support team for assistance.<br><br>We understand that there may be various reasons for a declined payment, so please do not hesitate to reach out to us if you have any feedback or concerns. We value your patronage and hope to continue serving you in the future.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					break;

				case "membership_skip_memberships":

					$subject = 'Your membership plan has been skipped';

					$heading_text = 'Membership plan skipped';

					$footer_text = 'Thank you';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hello <b>{{customer_name}}</b>, <br><br>We wanted to inform you that your<b> {{plan_name}} </b>subscription on upcoming date<b> {{skip_date}} </b>has been skipped.<br><br>You can easily see details by logging into your account. if you encounter any challenges or require assistance, our dedicated customer support team is available to help you.<br><br>We understand that situations arise, leading to plan skips. If you have any feedback or concerns, we encourage you to reach out. Your satisfaction is of the utmost importance to us, and we look forward to resolving any issues promptly.<br><br>Best regards,<br><br>{{store_name}}</p>';


					break;

				case "membership_cancelleds":

					$subject = 'Membership {{plan_status}}';

					$heading_text = 'Membership {{plan_status}}';

					$footer_text = 'Stay in touch';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We would like to inform you that your subscription for {{plan_name}} has been <b>{{plan_status}}</b>. We understand that this decision may have been made for various reasons, and we appreciate the time you spent with our service. If you have any feedback or concerns regarding your experience or if you would like to explore alternative subscription options, please do not hesitate to reach out to our customer support team. We value your patronage and hope to assist you with any future needs or questions you may have.<br><br>Thank you for your business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					break;

				case "membership_upgrades":

					$subject = 'Your Plan has been upgraded';

					$heading_text = 'Plan upgraded';

					$footer_text = 'Thank You';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> Congratulations on upgrading to {{plan_name}} ! We are excited to have you as a member of our premium subscription plan. With {{plan_name}}, you will get access to all of our features and benefits, and We are confident that you will love your new subscription plan. <br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team. We are here to help you make the most of your new subscription.<br><br>Thank you for choosing {{store_name}}!<br><br> Sincerely, <br><br>{{store_name}} </p>';

					break;

				case "membership_downgrades":

					$subject = 'Your Plan has been downgraded';

					$heading_text = 'Plan Downgraded';

					$footer_text = 'Thank You';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We understand that your needs may change, and we are here to support you. We have downgraded your subscription to <b> {{plan_name}}</b> at your request. <br><br>If you have any feedback or concerns about your experience with us, please do not hesitate to reach out to our customer support team. We are always looking for ways to improve, and your feedback is valuable to us.<br><br>We hope that you will continue to use our service in the future. If you decide that you need more features or benefits, you can always <b><i>upgrade</i></b> your subscription at any time.<br><br>Thank you for your business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					break;

				case "membership_renews":

					$subject = 'Your Plan has been Renewed';

					$heading_text = 'Plan Renew';

					$footer_text = 'Thank You';

					$manage_button = 'View Account';

					$content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We are excited to let you know that your subscription to  <b> {{plan_name}} <i> has been renewed! </i></b>. <br><br>We appreciate your continued business and look forward to serving you for another year. If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>Thank you for choosing {{store_name}}!<br><br>Sincerely,<br><br>{{store_name}}</p>';

					break;
			}
		} else {

			$content_heading = '';

			$subject = $template_setting->subject ?? '';

			$ccc_email = $template_setting->ccc_email ?? '';

			$bcc_email = $template_setting->bcc_email ?? '';

			$reply_to = $template_setting->reply_to ?? '';

			$custom_email_html = $template_setting->custom_email_html ?? '';

			$logo_height = $template_setting->logo_height ?? '';

			$logo_width = $template_setting->logo_width ?? '';

			$logo_alignment = $template_setting->logo_alignment ?? '';

			$logo = $template_setting->logo;

			$heading_text = $template_setting->heading_text;

			$heading_text_color = $template_setting->heading_text_color;

			$email_background_color = $template_setting->email_background_color;

			$content_background = $template_setting->content_background;

			$content_heading = $template_setting->content_heading ?? '';

			$manage_button = $template_setting->manage_button;

			$manage_button_url = $template_setting->manage_button_url;

			$discount_coupon_content = $template_setting->discount_coupon_content ?? '';

			$free_shipping_coupon_content = $template_setting->free_shipping_coupon_content ?? '';

			$free_signup_product_content = $template_setting->free_signup_product_content ?? '';

			$manage_btn_text_color = $template_setting->manage_btn_text_color;

			$manage_btn_bg_color = $template_setting->manage_btn_bg_color;

			$footer_text = $template_setting->footer_text;

			$footer_bg_color = $template_setting->footer_bg_color ?? '';

			$header_bg_color = $template_setting->header_bg_color ?? '';

			$button_content_bg_color = $template_setting->button_content_bg_color ?? '';

			$logo_content_bg_color = $template_setting->logo_content_bg_color ?? '';

			$content_heading_text = $template_setting->content_heading_text ?? '';

			$feature_heading = $template_setting->feature_heading ?? '';

			$footer_content = $template_setting->footer_content ?? '';

			$free_gift_signup_product = $template_setting->free_gift_signup_product ?? '';

			$gift_link_text = $template_setting->gift_link_text ?? 'Reedem now';

			$logo_bg_color = $template_setting->logo_bg_color ?? '#ffffff';

			$tick_bg_color = $template_setting->tick_bg_color ?? '#50E1B0';



			if ($template_name == 'new_purchase_plans') {

				$content_heading = '<table style="width:100%; border-spacing:0; border-collapse:collapse;">
 
                                <tbody>
 
                                    <tr>
 
                                        <td style="border-width:0; padding-top: 10px; padding-bottom: 25px;">
 
                                            <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">
 
                                                <tbody >
 
                                                <tr>
 
                                                    <td><div class="sd_content_heading_text_view">' . $content_heading_text . '</div>
 
                                                    </td>
 
                                                </tr>
 
                                                </tbody>
 
                                            </table>
 
                                        </td>
 
                                    </tr>
 
                                </tbody>
 
                            </table>
 
                           <table style="width:100%; border-spacing:0; border-collapse:collapse;">
 
                                    <tbody>
 
                                        <tr>
 
                                            <td style="padding-top: 0; padding-bottom: 20px; border-width:0;">
 
                                                <table class="temp-container" style="width:   100%; text-align:left; border-spacing:0; border-collapse:collapse; margin:0 auto;">
 
                                                <thead><th>';

				$content_heading .= '<h2 style="font-size: 16px; font-style: normal; font-weight: 600;" class="sd_feature_heading_view" >' . $feature_heading . '</h2>';

				$content_heading .= '</th></thead>
 
                                                    <tbody>
 
                                                        <tr>
 
                                                            <td style="border-radius: 10px; padding: 28px 33px;">
 
                                                                <ul style="padding: 0; list-style: none; margin: 0;">';



				if ($discount_coupon_condition == true) {

					$content_heading .= '<li style="display:flex; font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_discount_coupon_content_view">' . $discount_coupon_content . '</div></li>';
				}



				if ($free_shipping_condition == true) {

					$content_heading .= '<li style="display:flex;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_shipping_coupon_content_view">' . $free_shipping_coupon_content . '</div></li>';
				}



				if ($free_signup_product_condition == true) {

					$content_heading .= '<li style="display:flex;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_signup_product_content_view">' . $free_signup_product_content . '</div></li>';
				}

				if ($free_gift_uponsignupSelectedDays == 'Immediately_after_signup' || $free_gift_uponsignupSelectedDays === true) {

					$content_heading .= '<li style="display:flex;font-style:normal;font-weight:500;align-items:center;margin-bottom:13px;line-height:24px;"><span style="padding-right:13px;display:flex;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="15" viewBox="0 0 122.88 109.76" style="enable-background:new 0 0 122.88 109.76" xml:space="preserve"><style>.st0{fill-rule:evenodd;clip-rule:evenodd;fill:' . $tick_bg_color . ';}</style><g><path class="st0 sd_tick_bg_color" d="M0,52.88l22.68-0.3c8.76,5.05,16.6,11.59,23.35,19.86C63.49,43.49,83.55,19.77,105.6,0h17.28C92.05,34.25,66.89,70.92,46.77,109.76C36.01,86.69,20.96,67.27,0,52.88L0,52.88z"/></g></svg></span><div class="sd_free_gift_signup_product_view">' . $free_gift_signup_product . '</div></li><br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
				}

				$content_heading .= '</ul>
 
                                                            </td>
 
                                                        </tr>
 
                                                    </tbody>
 
                                                </table>
 
                                            </td>
 
                                        </tr>
 
                                            <td>';

				$content_heading .= '<div class="sd_footer_content_view">' . $footer_content . '</div>';

				$content_heading .= '</td>
 
                                        <tr>
 
                                        </tr>
 
                                    </tbody>
 
                                </table>';
			}



			// if ($template_name == 'membership_free_gifts') {

			//         $content_heading .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';

			// }



		}

		if ($manage_button_url == '') {

			$manage_button_url = 'https://' . $store_name . '/account';
		}

		if ($logo == '') {

			$logo = $this->image_folder . 'logo.png';
		}



		$email_template_html = '<html><head><style>
 
        @import url("https://fonts.googleapis.com/css2 family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap");
 
        * {
 
            font-family: Public Sans, sans-serif;
 
        }
 
        h1, h2, h3, h4, h5, h6, p {
 
          margin: 0;
 
        }
 
        @media print {
 
          * {
 
            -webkit-print-color-adjust: exact;
 
          }
 
          html {
 
            background: none;
 
            padding: 0;
 
          }
 
          body {
 
            box-shadow: none;
 
            margin: 0;
 
          }
 
          span:empty {
 
            display: none;
 
          }
 
          .add, .cut {
 
            display: none;
 
          }
 
        }
 
        @page {
 
          margin: 0;
 
        }
 
        .w-40 {
 
          width: 40px;
 
          min-width: 40px;
 
        }
 
        .icon-with-text p {
 
          line-height: 24px;
 
          font-size: 15px;
 
        }
 
        @media (max-width: 430px) {
 
          .preorder-img {
 
            margin-top: 0 !important;
 
          }
 
          .take_your_shopify_text {
 
            line-height: 1.3 !important;
 
          }
 
          .take_your_shopify_text br {
 
            display: none;
 
          }
 
          .h-25 {
 
            font-size: 24px !important;
 
            line-height: normal;
 
          }
 
          .h-18 br {
 
            display: none;
 
            font-size: 18px !important;
 
          }
 
          #services_column {
 
            width: 100% !important;
 
          }
 
          .services_column_main td {
 
            width: 100% !important;
 
          }
 
          .services_column_main {
 
            display: flex;
 
            flex-direction: column;
 
          }
 
          .icon-with-text {
 
            margin: 10px;
 
          }
 
          .header_social_icon a {
 
            margin-right: 5px !important;
 
          }
 
        }
 
        .icon-with-text img {
 
          height: 41px;
 
        }
 
      </style>
 
      </head>
 
            <body class="sd_email_background_view" style="background:#EBE8E5;">
 
                <div class="main-template" style="max-width: 600px; margin: auto;">
 
                    <div class="main-temp-inner sd_logo_content_bg_color" style=" background-color: ' . $logo_bg_color . '">
 
                        <div class="header-temp" style="border-radius: 5px; padding: 14px 20px;">
 
                            <table class="sd_logo_content_bg_color" style="width: 100%; border-spacing: 0; border-collapse: collapse; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
 
                                <tbody>
 
                                    <tr>
 
                                        <td>
 
                                            <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">
 
                                                <tbody>
 
                                                    <tr>
 
 
 
                                                     <td>
 
                                                     <div class="sd_logo_align" style="text-align:' . $logo_alignment . '">
 
                                                     <img class="sd_logo_view" border="0" style="color:#000000; text-decoration:none;font-size:16px;"  height="' . $logo_height . 'px;" width="' . $logo_width . 'px;" alt="" data-proportionally-constrained="true" data-responsive="true" src="' . $logo . '">
 
                                                     </div>
 
                                                     </td>
 
 
 
                                                    </tr>
 
                                                </tbody>
 
                                            </table>
 
                                        </td>
 
                                    </tr>
 
                                </tbody>
 
                            </table>
 
                        </div>
 
                    </div>
 
                    <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">
 
                        <tbody>
 
                            <tr>
 
                                <td class="sd_header_bg_color" style=" background-color: ' . $header_bg_color . '; border-width: 0;">
 
                                    <center>
 
                                        <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">
 
                                            <tbody>
 
                                                <tr>
 
                                                    <td>
 
                                                        <div style="color: rgb(0, 0, 0) !important;">
 
                                                            <h1 class="sd_heading_view" style="padding: 20px;  font-size: 23px; font-weight: 600; color:' . $heading_text_color . '">' . $heading_text . '</h1>
 
                                                        </div>
 
                                                    </td>
 
                                                </tr>
 
                                            </tbody>
 
                                        </table>
 
                                    </center>
 
                                </td>
 
                            </tr>
 
                        </tbody>
 
                    </table>
 
                    <table class="sd_content_text_view" style="width: 100%; border-spacing: 0; border-collapse: collapse; background-color: ' . $content_background . '">
 
                        <tbody>
 
                            <tr>
 
                                <td style="border-width: 0; padding: 20px;">
 
                                    <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">
 
                                        <tbody>
 
                                            <tr>
 
                                                <td>
 
                                                    <div class="sd_content_heading_view">' . $content_heading . '</div>';



		if ($template_name == 'membership_free_gifts') {

			$email_template_html .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';
		}



		$email_template_html .= '</td>
 
                                            </tr>
 
                                        </tbody>
 
                                    </table>
 
                                </td>
 
                            </tr>
 
                        </tbody>
 
                    </table>
 
                    <table class="sd_button_content_bg_color" style="width: 100%; border-spacing: 0; border-collapse: collapse; background-color: ' . $button_content_bg_color . '">
 
                        <tbody>
 
                            <tr>
 
                                <td style="padding-top: 20px; padding-bottom: 20px; border-width: 0;">
 
                                    <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">
 
                                        <tbody>
 
                                            <tr align="center">
 
                                                <td>
 
                                                    <a href=' . $manage_button_url . ' class="sd_manage_button_view" style="text-decoration: none; position: relative; z-index: 1; border-radius: 4px; background: ' . $manage_btn_bg_color . '; color: ' . $manage_btn_text_color . '; font-size: 14px; font-weight: 500; display: inline-block; padding: 15px 11px; text-transform: uppercase;">' . $manage_button . '</a>
 
                                                </td>
 
                                            </tr>
 
                                        </tbody>
 
                                    </table>
 
                                </td>
 
                            </tr>
 
                        </tbody>
 
                    </table>
 
                    <table  class="sd_footer_bg_color" style="width: 100%; border-spacing: 0; border-collapse: collapse; background-color: ' . $footer_bg_color . ';">
 
                        <tbody>
 
                            <tr>
 
                                <td align="center" style="padding-top: 5px; border-width: 0; padding-bottom:5px;">
 
                                    <table class="temp-container" style="width:   100%; text-align: left; border-spacing: 0; border-collapse: collapse; margin: 0 auto;">
 
                                        <tbody>
 
                                            <tr>
 
                                                <td align="center">
 
                                                    <p class="sd_footer_text_view" style="padding: 10px 0; color: #000; text-align: center; font-size: 15px; font-weight: 400;">' . $footer_text . '</p>
 
                                                </td>
 
                                            </tr>
 
                                        </tbody>
 
                                    </table>
 
                                </td>
 
                            </tr>
 
                        </tbody>
 
                    </table>
 
                </div>
 
            </body>
 
        </html>';



		// Create and return an array with template data

		$return_template_array = [

			'email_template_html' => $email_template_html ?? '',

			'subject' => $subject ?? '',

			'ccc_email' => $ccc_email ?? '',

			'bcc_email' => $bcc_email ?? '',

			'reply_to' => $reply_to ?? '',

			'logo_height' => $logo_height ?? '',

			'logo_width' => $logo_width ?? '',

			'logo_alignment' => $logo_alignment ?? '',

			'logo' => $logo ?? '',

			'heading_text' => $heading_text ?? '',

			'heading_text_color' => $heading_text_color ?? '',

			'manage_button' => $manage_button ?? '',

			'manage_button_url' => $manage_button_url ?? '',

			'manage_btn_text_color' => $manage_btn_text_color ?? '',

			'manage_btn_bg_color' => $manage_btn_bg_color ?? '',

			'footer_text' => $footer_text ?? '',

			'custom_email_html' => $custom_email_html ?? '',

			'content_background' => $content_background ?? '',

			'email_background_color' => $email_background_color ?? '',

			'template_settings' => $template_setting ?? '',

			'content_heading' => $content_heading ?? '',

			'footer_content' => $footer_content ?? '',

			'tick_bg_color' => $tick_bg_color ?? '',

			'discount_coupon_content' => $discount_coupon_content ?? '',

			'free_shipping_coupon_content' => $free_shipping_coupon_content ?? '',

			'free_signup_product_content' => $free_signup_product_content ?? '',

			'content_heading_text' => $content_heading_text ?? '',

			'feature_heading' => $feature_heading ?? '',

			'header_bg_color' => $header_bg_color ?? '',

			'free_gift_signup_product' => $free_gift_signup_product ?? '',

			'gift_link_text' => $gift_link_text ?? '',

			'logo_bg_color' => $logo_bg_color ?? '',

			'button_content_bg_color' => $button_content_bg_color ?? '',

		];


		// print_r($return_template_array);
		// return $email_template_html;

		return $return_template_array;
	}

	// update second part plan

	// public function update_member_plan($request)
	// {

	// 	// $allHeaders = $request->headers->all();
	// 	// $get_response = $request->getContent();
	// 	// $getauthorization_token = $request->header('Authorization');
	// 	// $varify_token = $this->jwtValidateToken($getauthorization_token);

	// 	// $get_all_data = json_decode($request->get('ajaxData'));
	// 	// $get_all_data = json_decode($request['data'], true);
	// 	$get_all_data = json_decode($request['data']); // ← returns an object

	// 	// print_r($get_all_data);die;
	// 	$store = trim($this->store);

	// 	$memberPlanGroups = $get_all_data->member_plan_options;
	// 	$mainGroupOption_update = '';
	// 	$member_group_id = $get_all_data->member_group_id;
	// 	$member_plan_tier_name = $get_all_data->member_plan_tier_name;
	// 	$member_plan_tier_handle = $get_all_data->member_plan_tier_handle;
	// 	$member_plan_group_product_data = $get_all_data->member_plan_group_product_data;
	// 	$membership_plan_id = $get_all_data->member_plan_id;
	// 	$previous_created_plan = $get_all_data->already_created_member_plans;

	// 	// Example for product_id from nested object array
	// 	$product_id = '';
	// 	if (!empty($member_plan_group_product_data) && isset($member_plan_group_product_data[0]->product_id)) {
	// 		$product_id = $member_plan_group_product_data[0]->product_id;
	// 	}

	// 	$group_position = $this->get_group_position($store);

	// 	if ($member_group_id == '') {
	// 		// echo('here');
	// 		// $existing_tags = DB::table('membership_plan_groups')->where('store', $store)->where('unique_handle', $member_plan_tier_handle)->first();

	// 		$whereCondition = array(
	// 			"store" => $store,
	// 			"unique_handle" => $member_plan_tier_handle
	// 		);

	// 		$existing_tags = $this->table_row_check('membership_plan_groups', $whereCondition, "AND");

	// 		// $existing_memberPlanName = DB::table('MembershipPlan')->where('store', $store)->where('membership_plan_name', $member_plan_tier_name)->where('plan_status', 'enable')->first();

	// 		$whereCondition = [
	// 			'store' => $store,
	// 			'membership_plan_name' => $member_plan_tier_name,
	// 			'plan_status' => 'enable'
	// 		];


	// 		$existing_memberPlanName = $this->table_row_check('membership_plans', $whereCondition, "AND");

	// 		if ($existing_tags) {
	// 			return json_encode(['isError' => true, 'message' => 'Customer Tag already exists']);
	// 		} else if ($existing_memberPlanName) {
	// 			return json_encode(['status' => true, 'message' => 'Membership name already exists']);
	// 		} else {

	// 			//create new member plans
	// 			$variant_array = [];
	// 			$variant_array['member_plan_tier_name'] = $member_plan_tier_name;
	// 			$variant_array['product_id'] = $product_id;
	// 			$variant_array['option_price'] = $get_all_data->member_plan_options->{0}->option_price;
	// 			// print_r($variant_array);


	// 			$new_product_variant = $this->addNewVariant($variant_array, $store); //add new variant(s) in the 

	// 			// print_r($new_product_variant);die;

	// 			if ($new_product_variant['status'] != 'error') {
	// 				$new_product_variant = $new_product_variant['data'];
	// 				//add new group in the member plan
	// 				$sellingPlansToCreate = $this->create_member_options_array($get_all_data, 'create', $this->currency_code);
	// 				$variant_array_string = '["' . $new_product_variant['productVariants'][0]['id'] . '"]';
	// 				$group_description = '';
	// 				if (!empty($new_product_variant['featuredImage'])) {
	// 					$product_image = $new_product_variant['featuredImage']['url'];
	// 				}
	// 				if (!empty($get_all_data->group_description)) {
	// 					$group_position_desc = 'description: ' . $get_all_data->group_description;
	// 				}
	// 				try {
	// 					$graphQL_sellingPlanGroupCreate = 'mutation {
    //                             sellingPlanGroupCreate(
    //                                     input: {
    //                                     name: "' . $member_plan_tier_name . '"
    //                                     merchantCode: "' . $member_plan_tier_name . '"
    //                                     options: ["' . $sellingPlansToCreate['main_group_option'] . '"]
    //                                     position: ' . $group_position . '
    //                                     description:"' . $group_position_desc . '"
    //                                     sellingPlansToCreate: ' . $sellingPlansToCreate['selling_plans'] . '
    //                                     appId:  "4trw27bTrit21member7KePw22asg445r78arewphoenix-membership"

    //                                     }
    //                                     resources: { productIds: [], productVariantIds: ' . $variant_array_string . '}
    //                                 ) {
    //                                 sellingPlanGroup {
    //                                 id
    //                                 appId
    //                                 sellingPlans(first: 4){
    //                                     edges{
    //                                         node{
    //                                             id
    //                                             name
    //                                         }
    //                                     }
    //                                 }
    //                                 }
    //                                 userErrors {
    //                                 field
    //                                 message
    //                                 }
    //                             }
    //                     }';

	// 					$sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate);
	// 					$sellingPlanGroupCreateapi_error = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['userErrors'];

	// 					// print_r($sellingPlanGroupCreateapi_execution);

	// 					if (!count($sellingPlanGroupCreateapi_error)) {
	// 						$selling_plan_group_array = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate'];
	// 						// save perk data
	// 						$membership_perks_group_id = $selling_plan_group_array['sellingPlanGroup']['id'];
	// 						// $save_edit_perks_data = new MembershipPerk;
	// 						// $save_edit_perks_data->membership_group_id = str_replace("gid://shopify/SellingPlanGroup/", "", $membership_perks_group_id);
	// 						// $save_edit_perks_data->membership_plan_id = $membership_plan_id;
	// 						// $save_edit_perks_data->perks_type_value = $member_plan_tier_name;
	// 						// $save_edit_perks_data->save();


	// 						// Clean up the group ID (same as Laravel's str_replace)
	// 						$clean_membership_group_id = str_replace("gid://shopify/SellingPlanGroup/", "", $membership_perks_group_id);

	// 						// Build payload
	// 						$insert_data = [
	// 							'store' => $store,
	// 							'membership_group_id' => $clean_membership_group_id,
	// 							'membership_plan_id' => $membership_plan_id,
	// 							'perks_type_value' => $member_plan_tier_name
	// 						];

	// 						// Build SQL statement
	// 						$sql = "INSERT INTO membership_perks (store, membership_group_id, membership_plan_id, perks_type_value) 
	// 							VALUES (:store, :membership_group_id, :membership_plan_id, :perks_type_value)";

	// 						// Prepare and execute
	// 						$stmt = $this->db->prepare($sql);
	// 						$stmt->execute($insert_data);

	// 						$variant_id = str_replace("gid://shopify/ProductVariant/", "", $new_product_variant['productVariants'][0]['id']);
	// 						$product_name = $new_product_variant['product']['title'] . '-' . $new_product_variant['productVariants'][0]['title'];
	// 						$variant_price = $new_product_variant['productVariants'][0]['price'];
	// 					} else {
	// 						$result = array('isError' => true, 'message' => 'Member group create error');
	// 						return json_encode($result); // return json
	// 					}
	// 				} catch (Exception $e) {
	// 					return json_encode(array("isError" => true, 'error' => $e->getMessage(), 'message' => 'mutation execution error')); // return json
	// 				}
	// 			} else {
	// 				return json_encode(array("isError" => true, 'error' => 'error', 'message' => $new_product_variant['message'])); // return json
	// 			}
	// 		}
	// 	} else {
	// 		// update existing member plans
	// 		$previous_group_index = array_search($member_group_id, array_column($previous_created_plan, 'member_group_id'));
	// 		$previous_member_plan_options_ids = array_column((array) $previous_created_plan[$previous_group_index]->member_plan_options, 'membership_option_id');
	// 		$current_member_plan_options_ids = array_column((array) $get_all_data->member_plan_options, 'membership_option_id');
	// 		$deleted_options_ids_array = array_diff($previous_member_plan_options_ids, $current_member_plan_options_ids);
	// 		$deleted_member_plan_option_ids = '[]';
	// 		if ($deleted_options_ids_array) {
	// 			$option_ids_array_suffix = preg_filter('/^/', '"gid://shopify/SellingPlan/', $deleted_options_ids_array);
	// 			$option_ids_array_prefix = preg_filter('/$/', '"', $option_ids_array_suffix);
	// 			$deleted_member_plan_option_ids = "[" . implode(",", $option_ids_array_prefix) . "]";
	// 		}

	// 		$sellingPlansUpdate = $this->create_member_options_array($get_all_data, 'update', $this->currency_code);
	// 		//  print_r($sellingPlansUpdate);
	// 		//  die;

	// 		// $mainGroupOption_update .= $sellingPlansUpdate['main_group_option'];
	// 		if ($mainGroupOption_update == '') {
	// 			$mainGroupOption_update .= $sellingPlansUpdate['main_group_option'];
	// 		} else {
	// 			$mainGroupOption_update .= ',' . $sellingPlansUpdate['main_group_option'];
	// 		}


	// 		$sellingPlansCreate = '[]';
	// 		$empty_option_id = null;

	// 		$new_created_plans = array_filter((array) $get_all_data->member_plan_options, function ($value) use ($empty_option_id) {
	// 			return $value->membership_option_id == $empty_option_id;
	// 		});

	// 		if (count($new_created_plans)) {
	// 			// echo '<pre>';
	// 			// print_r($new_created_plans);
	// 			// die;
	// 			$sellingPlansCreate_array = $this->create_member_options_array($get_all_data, "create", $this->currency_code);
	// 			$sellingPlansCreate = $sellingPlansCreate_array['selling_plans'];

	// 			if ($mainGroupOption_update == '') {
	// 				$mainGroupOption_update .= $sellingPlansCreate_array['main_group_option'];
	// 			} else {
	// 				$mainGroupOption_update .= ',' . $sellingPlansCreate_array['main_group_option'];
	// 			}
	// 		}


	// 		$variant_id = $get_all_data->variant_id;
	// 		$product_name = $get_all_data->member_plan_group_product_data[0]->membership_plan_name . '-' . $member_plan_tier_name;
	// 		$product_image = $get_all_data->member_plan_group_product_data[0]->image_path;


	// 		try {

	// 			// print_r([
	// 			// 	'member_group_id' => $member_group_id,
	// 			// 	'member_plan_tier_name' => $member_plan_tier_name,
	// 			// 	'mainGroupOption_update' => $mainGroupOption_update,
	// 			// 	'sellingPlansToUpdate' => $sellingPlansUpdate['selling_plans'],
	// 			// 	'sellingPlansToCreate' => $sellingPlansCreate,
	// 			// 	'sellingPlansToDelete' => $deleted_member_plan_option_ids
	// 			// ]);

	// 			$graphQL_sellingPlanGroupUpdate = 'mutation {
	// 				sellingPlanGroupUpdate(
	// 					id: "gid://shopify/SellingPlanGroup/' . $member_group_id . '"
	// 					input: {
	// 						name: "' . htmlspecialchars_decode($member_plan_tier_name, ENT_QUOTES) . '"
	// 						merchantCode: "' . htmlspecialchars_decode($member_plan_tier_name, ENT_QUOTES) . '"
	// 						options : ["' . $mainGroupOption_update . '"]
	// 						sellingPlansToUpdate : ' . $sellingPlansUpdate['selling_plans'] . '
	// 						sellingPlansToCreate : ' . $sellingPlansCreate . '
	// 						sellingPlansToDelete : ' . $deleted_member_plan_option_ids . '
	// 						appId:  "4trw27bTrit21member7KePw22asg445r78arewphoenix-membership"
	// 					}
	// 				)
	// 				{
	// 					sellingPlanGroup {
	// 						id
	// 						sellingPlans(first: 20){
	// 							edges{
	// 								node{
	// 									id
	// 									name
	// 									pricingPolicies{
	// 										__typename
	// 										... on SellingPlanFixedPricingPolicy{
	// 											adjustmentType
	// 											adjustmentValue{
	// 												__typename
	// 												... on MoneyV2{
	// 													amount
	// 												}
	// 											}
	// 										}
	// 									}
	// 								}
	// 							}
	// 						}
	// 					}
	// 					userErrors {
	// 					field
	// 					message
	// 					}
	// 				}
	// 			}';
	// 			$sellingPlanGroupUpdateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupUpdate, null, null, null);
	// 			$sellingPlanGroupUpdateapi_error = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['userErrors'];
	// 		} catch (Exception $e) {
	// 			// echo($e->getMessage());
	// 			return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json
	// 		}

	// 		//save new group data into database if no error exists
	// 		if (!count($sellingPlanGroupUpdateapi_error)) {
	// 			$price = '';
	// 			$price = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['sellingPlans']['edges'][0]['node']['pricingPolicies'][0]['adjustmentValue']['amount'] ?? 0;
	// 			// $variants_data = $get_all_data->member_plan_group_product_data;
	// 			$selling_group_id = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['id'];
	// 			$target_group_id = str_replace("gid://shopify/SellingPlanGroup/",  "", $selling_group_id);
	// 			$option_id = '';
	// 			if ($price == 0) {
	// 				foreach ($previous_created_plan as $plan) {
	// 					if ($plan->member_group_id == $target_group_id && !empty($plan->member_plan_options)) {
	// 						// print_r($plan->member_plan_options); 
	// 						$option_id = $plan->member_plan_options[0]->membership_option_id;
	// 						break;
	// 					}
	// 				}
	// 				foreach ((array) $get_all_data->member_plan_options as $option) {
	// 					if ($option->membership_option_id == $option_id) {
	// 						$price = $option->option_price;
	// 						break;
	// 					}
	// 				}
	// 			}
	// 			// echo $price;
	// 			$updateProductVariant = '
	// 			mutation {
	// 				productVariantsBulkUpdate(
	// 					productId: "gid://shopify/Product/' . $product_id . '",
	// 					variants: [
	// 						{
	// 							id: "gid://shopify/ProductVariant/' . $variant_id . '",
	// 							price: "' . $price . '"
	// 						}
	// 					]
	// 				) {
	// 					product {
	// 						id
	// 					}
	// 					productVariants {
	// 						id
	// 						price
	// 					}
	// 					userErrors {
	// 						field
	// 						message
	// 					}
	// 				}
	// 			}';
	// 			try {
	// 				//code...
	// 				$update_variant_execution = $this->shopify_graphql_object->GraphQL->post($updateProductVariant);
	// 				$update_variant_error = $update_variant_execution['data']['productVariantsBulkUpdate']['userErrors'];
	// 				if (!count($update_variant_error)) {
	// 					$variant_price = $update_variant_execution['data']['productVariantsBulkUpdate']['variants'][0]['price'];
	// 				} else {
	// 					$variant_price = $member_plan_group_product_data[0]->variant_price;
	// 				}

	// 				//delete member plan option from db

	// 				// if (!empty($deleted_options_ids_array)) {
	// 				// 	// MembershipGroupsDetail::whereIn('membership_option_id', $deleted_options_ids_array)->delete();
	// 				// 	$placeholders = rtrim(str_repeat('?,', count($deleted_options_ids_array)), ',');
	// 				// 	// Prepare the SQL statement
	// 				// 	$sql = "DELETE FROM membership_groups_details WHERE membership_option_id IN ($placeholders)";
	// 				// 	$stmt = $this->db->prepare($sql);
	// 				// 	// Execute with array values
	// 				// 	$stmt->execute($deleted_options_ids_array);
	// 				// }

	// 				if (!empty($deleted_options_ids_array)) {
	// 					// Filter out any empty/invalid values
	// 					$deleted_options_ids_array = array_filter($deleted_options_ids_array, function ($id) {
	// 						return !empty($id) && is_numeric($id);
	// 					});

	// 					if (!empty($deleted_options_ids_array)) {
	// 						$placeholders = rtrim(str_repeat('?,', count($deleted_options_ids_array)), ',');
	// 						$sql = "DELETE FROM membership_groups_details WHERE membership_option_id IN ($placeholders)";
	// 						$stmt = $this->db->prepare($sql);
	// 						$stmt->execute(array_values($deleted_options_ids_array));
	// 					}
	// 				}

	// 				$selling_plan_group_array = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate'];
	// 			} catch (Exception $th) {
	// 				echo $th->getMessage();
	// 			}
	// 		} else {
	// 			$result = array('isError' => true, 'message' => $sellingPlanGroupUpdateapi_error);
	// 			return json_encode($result); // return json
	// 		}
	// 	}
	// 	// update condition ends here

	// 	$sellingPlanGroupId_complete = $selling_plan_group_array['sellingPlanGroup']['id'];
	// 	$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupId_complete);
	// 	// if ($popular_plan == trim($member_plan_tier_name)) {
	// 	//     $add_popular_plan = '1';
	// 	// } else {
	// 	//     $add_popular_plan = '0';
	// 	// }
	// 	$memberPlan_group_payload = array(
	// 		"membership_group_id" => trim($sellingPlanGroupId),
	// 		"store" => trim($this->store),
	// 		"membership_plan_id" => trim($membership_plan_id),
	// 		"group_position" => trim($group_position),
	// 		"group_description" => trim($get_all_data->group_description),
	// 		// "popular_plan" => trim($add_popular_plan),
	// 		"membership_group_name" => trim($member_plan_tier_name),
	// 		"unique_handle" => trim($member_plan_tier_handle),
	// 		"variant_id" => trim($variant_id),
	// 	);

	// 	try {
	// 		// $memberplan_group_save = MembershipPlanGroup::updateOrCreate(['membership_group_id' => $sellingPlanGroupId], $memberPlan_group_payload);

	// 		$whereCondition = [
	// 			'store' => $store,
	// 			'membership_group_id' => $sellingPlanGroupId
	// 		];

	// 		// Insert or update the data in the database
	// 		$checkedUpdate = $this->insertupdateajax('membership_plan_groups', $memberPlan_group_payload, $whereCondition, "AND");

	// 		//Update or create perks
	// 		$memberPlan_group_payload_perks_details = array(
	// 			"store" => $store,
	// 			"membership_group_id" => trim($sellingPlanGroupId),
	// 			"membership_plan_id" => trim($membership_plan_id),
	// 			"perks_type_value" => trim($member_plan_tier_name),
	// 		);

	// 		// $memberplan_group_save = MembershipPerk::updateOrCreate(['membership_group_id' => $sellingPlanGroupId], $memberPlan_group_payload_perks_details);
	// 		$whereCondition = [
	// 			'store' => $store,
	// 			'membership_group_id' => trim($sellingPlanGroupId)
	// 		];

	// 		// Insert or update the data in the database
	// 		$checkedUpdate = $this->insertupdateajax('membership_perks', $memberPlan_group_payload_perks_details, $whereCondition, "AND");
	// 		//End
	// 	} catch (Exception $e) {
	// 		try {
	// 			$this->delete_memberPlan_group($sellingPlanGroupId);
	// 		} catch (Exception $e) {
	// 		}
	// 		$result = array('isError' => true, 'message' => $e->getMessage());
	// 		return json_encode($result); // return json
	// 	}

	// 	//save plan data in product details table
	// 	$single_memberplan_products_payload = array();

	// 	$memberPlan_products_payload = array(
	// 		"store" => $this->store,
	// 		"membership_group_id" => trim($sellingPlanGroupId),
	// 		"membership_plan_id" => trim($membership_plan_id),
	// 		"product_id" => trim($product_id),
	// 		"variant_id" => trim($variant_id),
	// 		"variant_price" => trim($variant_price),
	// 		"product_name" => trim($product_name),
	// 		"image_path" => trim($product_image),
	// 	);
	// 	// handel if value null
	// 	$memberPlan_products_payload = array_filter($memberPlan_products_payload, function ($val) {
	// 		return $val !== null && $val !== '';
	// 	});

	// 	// array_push($single_memberplan_products_payload, $memberPlan_products_payload);
	// 	try {

	// 		// $memberplan_products_save = MembershipProductDetail::upsert($single_memberplan_products_payload, ['membership_plan_id', 'membership_group_id', 'variant_id'], ['store', 'product_id', 'product_name', 'variant_price', 'image_path']);

	// 		// print_r($memberPlan_products_payload);
	// 		$whereCondition = [
	// 			'store' => $store,
	// 			'membership_plan_id' => $membership_plan_id,
	// 			'membership_group_id' => $sellingPlanGroupId,
	// 			'variant_id' => $variant_id
	// 		];

	// 		$checkedUpdateProductDetails = $this->insertupdateajax('membership_product_details', $memberPlan_products_payload, $whereCondition, "AND");

	// 		// print_r($checkedUpdateProductDetails);

	// 	} catch (Exception $e) {
	// 		//delete created group
	// 		try {
	// 			// MembershipPlanGroup::where('membership_group_id', $sellingPlanGroupId)->delete();
	// 			$whereCondition = [
	// 				'store' => $store,
	// 				'membership_group_id' => $sellingPlanGroupId
	// 			];

	// 			$response = $this->delete_row('membership_plan_groups', $whereCondition, "AND");
	// 		} catch (Exception $e) {
	// 		}
	// 		try {
	// 			$this->delete_memberPlan_group($sellingPlanGroupId);
	// 		} catch (Exception $e) {
	// 		}
	// 		$result = array('isError' => true, 'message' => $e->getMessage());
	// 		return json_encode($result); // return json
	// 	}

	// 	// save data in member plan group details table
	// 	$groupOptions = $selling_plan_group_array['sellingPlanGroup']['sellingPlans']['edges'];
	// 	$membership_groupOptions_payload = array();
	// 	foreach ($groupOptions as $key => $groupOption) {
	// 		// print_r($get_all_data);
	// 		if (is_array($get_all_data->member_plan_options)) {
	// 			$member_plan_options = $get_all_data->member_plan_options[$key];
	// 		} else {
	// 			$member_plan_options = $get_all_data->member_plan_options->$key;
	// 		}

	// 		// print_r($member_plan_options);
	// 		// die;

	// 		$offer_discount = '0';
	// 		$offer_discount_after = '0';
	// 		$discount_value = 0;
	// 		$discount_value_after = 0;
	// 		$discount_type = null;
	// 		$discount_type_after = null;
	// 		$anchor_type = '0';
	// 		$min_cycle = null;
	// 		$max_cycle = null;
	// 		$after_cycle_value = 0;
	// 		// $max_cycle = null;
	// 		$anchor_day = 0;
	// 		$option_description = null;
	// 		$option_price = '';
	// 		//check if discount is enable
	// 		if ($member_plan_options->discount_offer == true) {
	// 			$offer_discount = '1';
	// 			if ($member_plan_options->offer_discount_type == 'Discount Off') {
	// 				$discount_type = 'A';
	// 			} else {
	// 				$discount_type = 'P';
	// 			}
	// 			$discount_value = $member_plan_options->discount_value;
	// 			if ($member_plan_options->after_discount_offer == true) {
	// 				$offer_discount_after = '1';
	// 				if ($member_plan_options->after_discount_type == 'Discount Off') {
	// 					$discount_type_after = 'A';
	// 				} else {
	// 					$discount_type_after = 'P';
	// 				}
	// 				$discount_value_after = $member_plan_options->after_discount_value;
	// 				$after_cycle_value = $member_plan_options->change_discount_after_cycle;
	// 			}
	// 		}
	// 		if (!empty($member_plan_options->min_cycle)) {
	// 			$min_cycle = $member_plan_options->min_cycle;
	// 		}
	// 		if (!empty($member_plan_options->max_cycle)) {
	// 			$max_cycle = $member_plan_options->max_cycle;
	// 		}
	// 		if (!empty($member_plan_options->description)) {
	// 			$option_description = $member_plan_options->description;
	// 		}
	// 		// check if anchors are enable
	// 		if (isset($member_plan_options->set_anchor_date) && $member_plan_options->set_anchor_date = true) {
	// 			if ($member_plan_options->anchor_type == 'On Purchase Day') {
	// 				$anchor_type = '1';
	// 			} else if ($member_plan_options->anchor_type == 'On Specific Day') {
	// 				$anchor_type = '2';
	// 			}
	// 		} else if ($member_plan_options->anchor_type != '0') {
	// 			$anchor_type = $member_plan_options->anchor_type;
	// 		}

	// 		$charge_period_value = $member_plan_options->option_charge_value;
	// 		$charge_period_type = $member_plan_options->option_charge_type;
	// 		$option_price = $member_plan_options->option_price;
	// 		$option_plan_id = str_replace("gid://shopify/SellingPlan/", "", $groupOption['node']['id']);
	// 		$renew_original_date = 'false';
	// 		// after_discount_type
	// 		if (array_key_exists('offer_trial_status', (array)$get_all_data) && $get_all_data->offer_trial_status == 'on') {
	// 			// echo "sdjhj";

	// 			$offer_trial_status = 1;
	// 			$trial_period_value = $get_all_data->trial_period_value;
	// 			$trial_period_type = $get_all_data->trial_period_type ?? 'days';
	// 			$renew_original_date = $get_all_data->renew_on_original_date == 'true'  ? 'true' : 'false';
	// 			$discount_type = $get_all_data->discount_type;
	// 			$discount_value = $get_all_data->discount_value;
	// 			$discount_value_after = $get_all_data->discount_value_after;
	// 			$discount_type_after = $get_all_data->discount_type_after;
	// 			$discount_type_after = $discount_type_after == 'Discount Off' ? 'A' : 'P';
	// 			$discount_type = $discount_type == 'Discount Off' ? 'A' : 'P';
	// 		} else {
	// 			$offer_trial_status = 0;
	// 			$trial_period_value = null;
	// 			$trial_period_type = 'days';
	// 			$renew_original_date = 'false';
	// 		}

	// 		if (array_key_exists('discount_status', (array)$get_all_data) && ($get_all_data->discount_status == 'on' || $get_all_data->discount_status == '1')) {
	// 			// echo "sdjhj";die;
	// 			$discount_status = 1;
	// 			$discount_type = $get_all_data->discount_type;
	// 			$discount_type = $discount_type == 'Discount Off' ? 'A' : 'P';
	// 			$discount_value = $get_all_data->discount_value;
	// 			$discount_after_status = 1;
	// 			$discount_value_after = $get_all_data->discount_value_after;
	// 			$discount_type_after = $get_all_data->discount_type_after;
	// 			$discount_type_after = $discount_type_after == 'Discount Off' ? 'A' : 'P';
	// 		} else {
	// 			$discount_status = 0;
	// 			$discount_type = '';
	// 			$discount_value = '';
	// 			$discount_after_status = 0;
	// 			$discount_value_after = '';
	// 			$discount_type_after = '';
	// 		}
	// 		if (array_key_exists('discount_after_status', (array)$get_all_data) && ($get_all_data->discount_after_status == 'on' || $get_all_data->discount_after_status == '1')) {
	// 			$discount_after_status = 1;
	// 			$discount_value_after = $get_all_data->discount_value_after;
	// 			$discount_type_after = $get_all_data->discount_type_after;
	// 			$discount_type_after = $discount_type_after == 'Discount Off' ? 'A' : 'P';
	// 		} else {
	// 			$discount_after_status = 0;
	// 		}
	// 		$single_groupOption_details = array(
	// 			'store' => $store,
	// 			'offer_trial_status' => $offer_trial_status,
	// 			'trial_period_value' => $trial_period_value,
	// 			'trial_period_type' => $trial_period_type,
	// 			'discount_offer' => $discount_status,
	// 			'after_discount_offer'	=> $discount_after_status,
	// 			'renew_on_original_date' => $renew_original_date,
	// 			'membership_group_id' => trim($sellingPlanGroupId),
	// 			'membership_plan_id' => trim($membership_plan_id),
	// 			'membership_option_id' => trim($option_plan_id),
	// 			'description' => trim($option_description),
	// 			'option_charge_type' => trim($charge_period_type),
	// 			'option_charge_value' => trim($charge_period_value),
	// 			'discount_type' => trim($discount_type ?? 'P'),
	// 			'discount_value' => trim($discount_value),
	// 			'after_discount_type' => trim($discount_type_after ?? 'P'),
	// 			'after_discount_value' => trim($discount_value_after),
	// 			'change_discount_after_cycle' => trim($after_cycle_value),
	// 			'min_cycle' => trim($min_cycle),
	// 			'max_cycle' => trim($max_cycle),
	// 			'option_price' => trim($option_price),
	// 			'anchor_type' => trim($anchor_type),
	// 			'anchor_day' => trim($anchor_day),
	// 		);
	// 		// print_r($single_groupOption_details);die;
	// 		// echo('hkkk');
	// 		// handel if value null
	// 		$single_groupOption_details = array_filter($single_groupOption_details, function ($val) {
	// 			return $val !== null && $val !== '';
	// 		});
	// 		// array_push($membership_groupOptions_payload, $single_groupOption_details);
	// 		try {
	// 			// $memberplan_products_save = MembershipGroupsDetail::upsert($membership_groupOptions_payload, ['store', 'membership_option_id'], ['store', 'membership_group_id', 'description', 'option_charge_type', 'option_charge_value', 'discount_offer', 'discount_type', 'discount_value', 'option_price', 'after_discount_offer', 'after_discount_type', 'after_discount_value', 'change_discount_after_cycle', 'min_cycle', 'max_cycle', 'anchor_type', 'anchor_day']);
	// 			$whereCondition = [
	// 				'store' => $store,
	// 				'membership_option_id' => $option_plan_id
	// 			];

	// 			$memberplan_products_save = $this->insertupdateajax('membership_groups_details', $single_groupOption_details, $whereCondition, "AND");
	// 		} catch (Exception $e) {
	// 			echo $e->getMessage();
	// 			// MembershipPlanGroup::where('membership_group_id', $sellingPlanGroupId)->delete();
	// 			$whereCondition = [
	// 				'store' => $store,
	// 				'membership_group_id' => $sellingPlanGroupId
	// 			];
	// 			$response = $this->delete_row('membership_plan_groups', $whereCondition, "AND");

	// 			try {
	// 				$this->delete_memberPlan_group($sellingPlanGroupId);
	// 			} catch (Exception $e) {
	// 			}
	// 			$result = array('isError' => true, 'message' => $e->getMessage());
	// 			$this->upsertMetaObject();
	// 			return json_encode($result); // return json

	// 		}
	// 	}
	// 	// group option array ends here


	// 	$result = array('isError' => false, 'message' => "Membership plan updated successfully", 'membership_plan_id' => $membership_plan_id);
	// 	$this->upsertMetaObject();
	// 	return json_encode($result); // return json

	// }

	public function update_member_plan($request)
	{

		// $allHeaders = $request->headers->all();
		// $get_response = $request->getContent();
		// $getauthorization_token = $request->header('Authorization');
		// $varify_token = $this->jwtValidateToken($getauthorization_token);

		// $get_all_data = json_decode($request->get('ajaxData'));
		// $get_all_data = json_decode($request['data'], true);
		$get_all_data = json_decode($request['data']); // ← returns an object

		// print_r($get_all_data);die;
		$store = trim($this->store);

		$memberPlanGroups = $get_all_data->member_plan_options;
		$mainGroupOption_update = '';
		$member_group_id = $get_all_data->member_group_id;
		$member_plan_tier_name = $get_all_data->member_plan_tier_name;
		$member_plan_tier_handle = $get_all_data->member_plan_tier_handle;
		$member_plan_group_product_data = $get_all_data->member_plan_group_product_data;
		$membership_plan_id = $get_all_data->member_plan_id;
		$previous_created_plan = $get_all_data->already_created_member_plans;

		// Example for product_id from nested object array
		$product_id = '';
		if (!empty($member_plan_group_product_data) && isset($member_plan_group_product_data[0]->product_id)) {
			$product_id = $member_plan_group_product_data[0]->product_id;
		}

		$group_position = $this->get_group_position($store);
	
		if ($member_group_id == '') {
			// echo('here');
			// $existing_tags = DB::table('membership_plan_groups')->where('store', $store)->where('unique_handle', $member_plan_tier_handle)->first();

			$whereCondition = array(
				"store" => $store,
				"unique_handle" => $member_plan_tier_handle
			);

			$existing_tags = $this->table_row_check('membership_plan_groups', $whereCondition, "AND");

			// $existing_memberPlanName = DB::table('MembershipPlan')->where('store', $store)->where('membership_plan_name', $member_plan_tier_name)->where('plan_status', 'enable')->first();

			$whereCondition = [
				'store' => $store,
				'membership_plan_name' => $member_plan_tier_name,
				'plan_status' => 'enable'
			];


			$existing_memberPlanName = $this->table_row_check('membership_plans', $whereCondition, "AND");

			if ($existing_tags) {
				return json_encode(['isError' => true, 'message' => 'Customer Tag already exists']);
			} else if ($existing_memberPlanName) {
				return json_encode(['status' => true, 'message' => 'Membership name already exists']);
			} else {

				//create new member plans
				$variant_array = [];
				$variant_array['member_plan_tier_name'] = $member_plan_tier_name;
				$variant_array['product_id'] = $product_id;
				$variant_array['option_price'] = $get_all_data->member_plan_options->{0}->option_price;
				// print_r($variant_array);


				$new_product_variant = $this->addNewVariant($variant_array, $store); //add new variant(s) in the 
                // echo('addNewVarianT');
				// print_r($new_product_variant);

				if ($new_product_variant['status'] != 'error') {
					$new_product_variant = $new_product_variant['data'];
					//add new group in the member plan
					$sellingPlansToCreate = $this->create_member_options_array($get_all_data, 'create', $this->currency_code);
					// echo('kkkkkk');
					// print_r($sellingPlansToCreate);
					$variant_array_string = $new_product_variant['productVariants'][0]['id'];
				
					$group_description = '';
					if (!empty($new_product_variant['featuredImage'])) {
						$product_image = $new_product_variant['featuredImage']['url'];
					}
					if (!empty($get_all_data->group_description)) {
						$group_position_desc = 'description: ' . $get_all_data->group_description;
					}
					try {

						// $graphQL_sellingPlanGroupCreate = 'mutation {
                        //         sellingPlanGroupCreate(
                        //                 input: {
                        //                 name: "' . $member_plan_tier_name . '"
                        //                 merchantCode: "' . $member_plan_tier_name . '"
                        //                 options: ["' . $sellingPlansToCreate['main_group_option'] . '"]
                        //                 position: ' . $group_position . '
                        //                 description:"' . $group_position_desc . '"
                        //                 sellingPlansToCreate: ' . $sellingPlansToCreate['selling_plans'] . '
                        //                 appId:  "4trw27bTrit21member7KePw22asg445r78arewphoenix-membership"

                        //                 }
                        //                 resources: { productIds: [], productVariantIds: ' . $variant_array_string . '}
                        //             ) {
                        //             sellingPlanGroup {
                        //             id
                        //             appId
                        //             sellingPlans(first: 4){
                        //                 edges{
                        //                     node{
                        //                         id
                        //                         name
                        //                     }
                        //                 }
                        //             }
                        //             }
                        //             userErrors {
                        //             field
                        //             message
                        //             }
                        //         }
                        // }';
                        // print_r($graphQL_sellingPlanGroupCreate);
						// $sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate);

						$graphQL_sellingPlanGroupCreate = <<<GQL
							mutation sellingPlanGroupCreate(\$input: SellingPlanGroupInput!, \$resources: SellingPlanGroupResourceInput!) {
								sellingPlanGroupCreate(
									input: \$input
									resources: \$resources
								) {
									sellingPlanGroup {
									id
									appId
									sellingPlans(first: 4) {
										edges {
										node {
											id
											name
										}
										}
									}
									}
									userErrors {
									field
									message
									}
								}
							}
							GQL;

						// $sellingPlansToCreate = $this->create_member_options_array((object)$val, 'create', $this->currency_code);
					
						$variables = [
							'input' => [
								'name' => $member_plan_tier_name,
								'merchantCode' => $member_plan_tier_name,
								'options' => [$sellingPlansToCreate['main_group_option']],
								'position' => (int)$group_position,
								'description' => $group_position_desc,
								'sellingPlansToCreate' => $sellingPlansToCreate['selling_plans'],
								'appId' => '4trw27bTrit21member7KePw22asg445r78arewphoenix-membership',
							],
							'resources' => [
								'productIds' => [],
								'productVariantIds' => [$variant_array_string],
							],
						];

                    
						$sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate,null, null, $variables);
                        // if ($store == 'test-store-phoenixt.myshopify.com') {
                        //     print_r($sellingPlanGroupCreateapi_execution);
						// }
						$sellingPlanGroupCreateapi_error = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['userErrors'];

						// print_r($sellingPlanGroupCreateapi_execution);

						if (!count($sellingPlanGroupCreateapi_error)) {
							$selling_plan_group_array = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate'];
							// save perk data
							$membership_perks_group_id = $selling_plan_group_array['sellingPlanGroup']['id'];
							// $save_edit_perks_data = new MembershipPerk;
							// $save_edit_perks_data->membership_group_id = str_replace("gid://shopify/SellingPlanGroup/", "", $membership_perks_group_id);
							// $save_edit_perks_data->membership_plan_id = $membership_plan_id;
							// $save_edit_perks_data->perks_type_value = $member_plan_tier_name;
							// $save_edit_perks_data->save();


							// Clean up the group ID (same as Laravel's str_replace)
							$clean_membership_group_id = str_replace("gid://shopify/SellingPlanGroup/", "", $membership_perks_group_id);

							// Build payload
							$insert_data = [
								'store' => $store,
								'membership_group_id' => $clean_membership_group_id,
								'membership_plan_id' => $membership_plan_id,
								'perks_type_value' => $member_plan_tier_name
							];

							// Build SQL statement
							$sql = "INSERT INTO membership_perks (store, membership_group_id, membership_plan_id, perks_type_value) 
								VALUES (:store, :membership_group_id, :membership_plan_id, :perks_type_value)";

							// Prepare and execute
							$stmt = $this->db->prepare($sql);
							$stmt->execute($insert_data);

							$variant_id = str_replace("gid://shopify/ProductVariant/", "", $new_product_variant['productVariants'][0]['id']);
							$product_name = $new_product_variant['product']['title'] . '-' . $new_product_variant['productVariants'][0]['title'];
							$variant_price = $new_product_variant['productVariants'][0]['price'];
						} else {
							$result = array('isError' => true, 'message' => 'Member group create error');
							return json_encode($result); // return json
						}
					} catch (Exception $e) {
						 echo('here2');
						return json_encode(array("isError" => true, 'error' => $e->getMessage(), 'message' => 'mutation execution error')); // return json
					}
				} else {
					return json_encode(array("isError" => true, 'error' => 'error', 'message' => $new_product_variant['message'])); // return json
				}
			}
		} else {
			// update existing member plans
			$previous_group_index = array_search($member_group_id, array_column($previous_created_plan, 'member_group_id'));
			$previous_member_plan_options_ids = array_column((array) $previous_created_plan[$previous_group_index]->member_plan_options, 'membership_option_id');
			$current_member_plan_options_ids = array_column((array) $get_all_data->member_plan_options, 'membership_option_id');
			$deleted_options_ids_array = array_diff($previous_member_plan_options_ids, $current_member_plan_options_ids);
			$deleted_member_plan_option_ids = [];
			if ($deleted_options_ids_array) {

				// echo('here88888');
				$option_ids_array_suffix = preg_filter('/^/', '"gid://shopify/SellingPlan/', $deleted_options_ids_array);
				$option_ids_array_prefix = preg_filter('/$/', '"', $option_ids_array_suffix);
				$deleted_member_plan_option_ids = "[" . implode(",", $option_ids_array_prefix) . "]";
			}

		    // if (!empty($deleted_options_ids_array)) {
			// 	$deleted_member_plan_option_ids = array_map(function ($id) {
			// 		return "gid://shopify/SellingPlan/" . $id;
			// 	}, $deleted_options_ids_array);
			// }

			
			$sellingPlansUpdate = $this->create_member_options_array($get_all_data, 'update', $this->currency_code);
      
            // print_r($sellingPlansUpdate);
			// echo('okkk1');

			// $mainGroupOption_update .= $sellingPlansUpdate['main_group_option'];
			if ($mainGroupOption_update == '') {
				$mainGroupOption_update .= $sellingPlansUpdate['main_group_option'];
			} else {
				$mainGroupOption_update .= ',' . $sellingPlansUpdate['main_group_option'];
			}


			$sellingPlansCreate = []; 
			$empty_option_id = null;

			$new_created_plans = array_filter((array) $get_all_data->member_plan_options, function ($value) use ($empty_option_id) {
				return $value->membership_option_id == $empty_option_id;
			});
      

			if (count($new_created_plans)) {
				// echo '<pre>';
				// echo 'bbbbbbbbbb';
				// print_r($new_created_plans);
			
				$sellingPlansCreate_array = $this->create_member_options_array($get_all_data, "create", $this->currency_code);
				$sellingPlansCreate = $sellingPlansCreate_array['selling_plans'];

				if ($mainGroupOption_update == '') {
					$mainGroupOption_update .= $sellingPlansCreate_array['main_group_option'];
				} else {
					$mainGroupOption_update .= ',' . $sellingPlansCreate_array['main_group_option'];
				}
			}


			$variant_id = $get_all_data->variant_id;
			$product_name = $get_all_data->member_plan_group_product_data[0]->membership_plan_name . '-' . $member_plan_tier_name;
			$product_image = $get_all_data->member_plan_group_product_data[0]->image_path;

			
			try {

				// print_r([
				// 	'member_group_id' => $member_group_id,
				// 	'member_plan_tier_name' => $member_plan_tier_name,
				// 	'mainGroupOption_update' => $mainGroupOption_update,
				// 	'sellingPlansToUpdate' => $sellingPlansUpdate['selling_plans'],
				// 	'sellingPlansToCreate' => $sellingPlansCreate,
				// 	'sellingPlansToDelete' => $deleted_member_plan_option_ids
				// ]);
		 

				// $graphQL_sellingPlanGroupUpdate = 'mutation {
				// 	sellingPlanGroupUpdate(
				// 		id: "gid://shopify/SellingPlanGroup/' . $member_group_id . '"
				// 		input: {
				// 			name: "' . htmlspecialchars_decode($member_plan_tier_name, ENT_QUOTES) . '"
				// 			merchantCode: "' . htmlspecialchars_decode($member_plan_tier_name, ENT_QUOTES) . '"
				// 			options : ["' . $mainGroupOption_update . '"]
				// 			sellingPlansToUpdate : ' . $sellingPlansUpdate['selling_plans'] . '
				// 			sellingPlansToCreate : ' . $sellingPlansCreate . '
				// 			sellingPlansToDelete : ' . $deleted_member_plan_option_ids . '
				// 			appId:  "4trw27bTrit21member7KePw22asg445r78arewphoenix-membership"
				// 		}
				// 	)
				// 	{
				// 		sellingPlanGroup {
				// 			id
				// 			sellingPlans(first: 20){
				// 				edges{
				// 					node{
				// 						id
				// 						name
				// 						pricingPolicies{
				// 							__typename
				// 							... on SellingPlanFixedPricingPolicy{
				// 								adjustmentType
				// 								adjustmentValue{
				// 									__typename
				// 									... on MoneyV2{
				// 										amount
				// 									}
				// 								}
				// 							}
				// 						}
				// 					}
				// 				}
				// 			}
				// 		}
				// 		userErrors {
				// 		field
				// 		message
				// 		}
				// 	}
				// }';
				// $sellingPlanGroupUpdateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupUpdate, null, null, null);

				$graphQL_sellingPlanGroupUpdate = <<<GQL
					mutation sellingPlanGroupUpdate(\$id: ID!, \$input: SellingPlanGroupInput!) {
					sellingPlanGroupUpdate(id: \$id, input: \$input) {
						sellingPlanGroup {
						id
						sellingPlans(first: 20) {
							edges {
							node {
								id
								name
								pricingPolicies {
								__typename
								... on SellingPlanFixedPricingPolicy {
									adjustmentType
									adjustmentValue {
									__typename
									... on MoneyV2 {
										amount
									}
									}
								}
								}
							}
							}
						}
						}
						userErrors {
						field
						message
						}
					}
					}
					GQL;

					$variables = [
						'id' => 'gid://shopify/SellingPlanGroup/' . $member_group_id,
						'input' => [
							'name' => htmlspecialchars_decode($member_plan_tier_name, ENT_QUOTES),
							'merchantCode' => htmlspecialchars_decode($member_plan_tier_name, ENT_QUOTES),
							'options' => [$mainGroupOption_update],
							'sellingPlansToUpdate' => $sellingPlansUpdate['selling_plans'],
							'sellingPlansToCreate' => $sellingPlansCreate,
							'sellingPlansToDelete' => $deleted_member_plan_option_ids,
							'appId' => '4trw27bTrit21member7KePw22asg445r78arewphoenix-membership',
						],
					];

				
				$sellingPlanGroupUpdateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupUpdate, null, null, $variables);
		
				$sellingPlanGroupUpdateapi_error = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['userErrors'];
				// print_r($sellingPlanGroupUpdateapi_execution);
			    // echo('okkk2');

			} catch (Exception $e) {
				// echo($e->getMessage());
				return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json
			}

			//save new group data into database if no error exists
			if (!count($sellingPlanGroupUpdateapi_error)) {
				$price = '';
				$price = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['sellingPlans']['edges'][0]['node']['pricingPolicies'][0]['adjustmentValue']['amount'] ?? 0;
				// $variants_data = $get_all_data->member_plan_group_product_data;
				$selling_group_id = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['id'];
				$selling_group_perks_title = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['sellingPlans']['edges'][0]['node']['name'];

				$target_group_id = str_replace("gid://shopify/SellingPlanGroup/",  "", $selling_group_id);
				$option_id = '';
				
		
				if ($price == 0) {
					foreach ($previous_created_plan as $plan) {
						if ($plan->member_group_id == $target_group_id && !empty($plan->member_plan_options)) {
							// print_r($plan->member_plan_options); 
							$option_id = $plan->member_plan_options[0]->membership_option_id;
							break;
						}
					}
					foreach ((array) $get_all_data->member_plan_options as $option) {
						if ($option->membership_option_id == $option_id) {
							$price = $option->option_price;
							break;
						}
					}
				}

				
				$updateProductVariant = '
				mutation {
					productVariantsBulkUpdate(
						productId: "gid://shopify/Product/' . $product_id . '",
						variants: [
							{
								id: "gid://shopify/ProductVariant/' . $variant_id . '",
								price: "' . $price . '"
		
							}
						]
					) {
						product {
							id
						}
						productVariants {
							id
							price
						}
						userErrors {
							field
							message
						}
					}
				}';

				
				try {
					//code...
		
					$update_variant_execution = $this->shopify_graphql_object->GraphQL->post($updateProductVariant);
					
					$update_variant_error = $update_variant_execution['data']['productVariantsBulkUpdate']['userErrors'];
					if (!count($update_variant_error)) {
						$variant_price = $update_variant_execution['data']['productVariantsBulkUpdate']['productVariants'][0]['price'];
					} else {
						$variant_price = $member_plan_group_product_data[0]->variant_price;
					}
					
   
					//delete member plan option from db

					// if (!empty($deleted_options_ids_array)) {
					// 	// MembershipGroupsDetail::whereIn('membership_option_id', $deleted_options_ids_array)->delete();
					// 	$placeholders = rtrim(str_repeat('?,', count($deleted_options_ids_array)), ',');
					// 	// Prepare the SQL statement
					// 	$sql = "DELETE FROM membership_groups_details WHERE membership_option_id IN ($placeholders)";
					// 	$stmt = $this->db->prepare($sql);
					// 	// Execute with array values
					// 	$stmt->execute($deleted_options_ids_array);
					// }

					if (!empty($deleted_options_ids_array)) {
						// Filter out any empty/invalid values
						$deleted_options_ids_array = array_filter($deleted_options_ids_array, function ($id) {
							return !empty($id) && is_numeric($id);
						});

						if (!empty($deleted_options_ids_array)) {
							$placeholders = rtrim(str_repeat('?,', count($deleted_options_ids_array)), ',');
							$sql = "DELETE FROM membership_groups_details WHERE membership_option_id IN ($placeholders)";
							$stmt = $this->db->prepare($sql);
							$stmt->execute(array_values($deleted_options_ids_array));
						}
					}

					$selling_plan_group_array = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate'];
				} catch (Exception $th) {
					echo $th->getMessage();
				} 
			} else {
				$result = array('isError' => true, 'message' => $sellingPlanGroupUpdateapi_error);
				return json_encode($result); // return json
			}
		}
		// update condition ends here

		$sellingPlanGroupId_complete = $selling_plan_group_array['sellingPlanGroup']['id'];
		$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupId_complete);
		// if ($popular_plan == trim($member_plan_tier_name)) {
		//     $add_popular_plan = '1';
		// } else {
		//     $add_popular_plan = '0';
		// }
		$memberPlan_group_payload = array(
			"membership_group_id" => trim($sellingPlanGroupId),
			"store" => trim($this->store),
			"membership_plan_id" => trim($membership_plan_id),
			"group_position" => trim($group_position),
			"group_description" => trim($get_all_data->group_description),
			// "popular_plan" => trim($add_popular_plan),
			"membership_group_name" => trim($member_plan_tier_name),
			"unique_handle" => trim($member_plan_tier_handle),
			"variant_id" => trim($variant_id),
		);
     
		try {
			// $memberplan_group_save = MembershipPlanGroup::updateOrCreate(['membership_group_id' => $sellingPlanGroupId], $memberPlan_group_payload);

			$whereCondition = [
				'store' => $store,
				'membership_group_id' => $sellingPlanGroupId
			];

			// Insert or update the data in the database
			$checkedUpdate = $this->insertupdateajax('membership_plan_groups', $memberPlan_group_payload, $whereCondition, "AND");

			//Update or create perks
			$memberPlan_group_payload_perks_details = array(
				"store" => $store,
				"membership_group_id" => trim($sellingPlanGroupId),
				"membership_plan_id" => trim($membership_plan_id),
				"perks_type_value" => trim($member_plan_tier_name),
			);

			// $memberplan_group_save = MembershipPerk::updateOrCreate(['membership_group_id' => $sellingPlanGroupId], $memberPlan_group_payload_perks_details);
			$whereCondition = [
				'store' => $store,
				'membership_group_id' => trim($sellingPlanGroupId)
			];

			// Insert or update the data in the database
			$checkedUpdate = $this->insertupdateajax('membership_perks', $memberPlan_group_payload_perks_details, $whereCondition, "AND");
	
			//End
		} catch (Exception $e) {
			try {
				$this->delete_memberPlan_group($sellingPlanGroupId);
			} catch (Exception $e) {
			}
			$result = array('isError' => true, 'message' => $e->getMessage());
			return json_encode($result); // return json
		}

		//save plan data in product details table
		$single_memberplan_products_payload = array();

		$memberPlan_products_payload = array(
			"store" => $this->store,
			"membership_group_id" => trim($sellingPlanGroupId),
			"membership_plan_id" => trim($membership_plan_id),
			"product_id" => trim($product_id),
			"variant_id" => trim($variant_id),
			"variant_price" => trim($variant_price),
			"product_name" => trim($product_name),
			"image_path" => trim($product_image),
		);
		// handel if value null
		$memberPlan_products_payload = array_filter($memberPlan_products_payload, function ($val) {
			return $val !== null && $val !== '';
		});

		// array_push($single_memberplan_products_payload, $memberPlan_products_payload);
		try {

			// $memberplan_products_save = MembershipProductDetail::upsert($single_memberplan_products_payload, ['membership_plan_id', 'membership_group_id', 'variant_id'], ['store', 'product_id', 'product_name', 'variant_price', 'image_path']);

			// print_r($memberPlan_products_payload);
			$whereCondition = [
				'store' => $store,
				'membership_plan_id' => $membership_plan_id,
				'membership_group_id' => $sellingPlanGroupId,
				'variant_id' => $variant_id
			];

			$checkedUpdateProductDetails = $this->insertupdateajax('membership_product_details', $memberPlan_products_payload, $whereCondition, "AND");

			// print_r($checkedUpdateProductDetails);

		} catch (Exception $e) {
			//delete created group
			try {
				// MembershipPlanGroup::where('membership_group_id', $sellingPlanGroupId)->delete();
				$whereCondition = [
					'store' => $store,
					'membership_group_id' => $sellingPlanGroupId
				];

				$response = $this->delete_row('membership_plan_groups', $whereCondition, "AND");
			} catch (Exception $e) {
			}
			try {
				$this->delete_memberPlan_group($sellingPlanGroupId);
			} catch (Exception $e) {
			}
			$result = array('isError' => true, 'message' => $e->getMessage());
			return json_encode($result); // return json
		}

		// save data in member plan group details table
	
		// print_r($selling_plan_group_array);
		$groupOptions = $selling_plan_group_array['sellingPlanGroup']['sellingPlans']['edges'];
		$membership_groupOptions_payload = array();
		foreach ($groupOptions as $key => $groupOption) {
			// print_r($get_all_data);
			if (is_array($get_all_data->member_plan_options)) {
				$member_plan_options = $get_all_data->member_plan_options[$key];
			} else {
				$member_plan_options = $get_all_data->member_plan_options->$key;
			}

			// print_r($member_plan_options);
			// die;

			$offer_discount = '0';
			$offer_discount_after = '0';
			$discount_value = 0;
			$discount_value_after = 0;
			$discount_type = null;
			$discount_type_after = null;
			$anchor_type = '0';
			$min_cycle = null;
			$max_cycle = null;
			$after_cycle_value = 0;
			// $max_cycle = null;
			$anchor_day = 0;
			$option_description = null;
			$option_price = '';
			//check if discount is enable
			if ($member_plan_options->discount_offer == true) {
				$offer_discount = '1';
				if ($member_plan_options->offer_discount_type == 'Discount Off') {
					$discount_type = 'A';
				} else {
					$discount_type = 'P';
				}
				$discount_value = $member_plan_options->discount_value;
				if ($member_plan_options->after_discount_offer == true) {
					$offer_discount_after = '1';
					if ($member_plan_options->after_discount_type == 'Discount Off') {
						$discount_type_after = 'A';
					} else {
						$discount_type_after = 'P';
					}
					$discount_value_after = $member_plan_options->after_discount_value;
					$after_cycle_value = $member_plan_options->change_discount_after_cycle;
				}
			}
			if (!empty($member_plan_options->min_cycle)) {
				$min_cycle = $member_plan_options->min_cycle;
			}
			if (!empty($member_plan_options->max_cycle)) {
				$max_cycle = $member_plan_options->max_cycle;
			}
			if (!empty($member_plan_options->description)) {
				$option_description = $member_plan_options->description;
			}
			// check if anchors are enable
			if (isset($member_plan_options->set_anchor_date) && $member_plan_options->set_anchor_date = true) {
				if ($member_plan_options->anchor_type == 'On Purchase Day') {
					$anchor_type = '1';
				} else if ($member_plan_options->anchor_type == 'On Specific Day') {
					$anchor_type = '2';
				}
			} else if ($member_plan_options->anchor_type != '0') {
				$anchor_type = $member_plan_options->anchor_type;
			}

			$charge_period_value = $member_plan_options->option_charge_value;
			$charge_period_type = $member_plan_options->option_charge_type;
			$option_price = $member_plan_options->option_price;
			$option_plan_id = str_replace("gid://shopify/SellingPlan/", "", $groupOption['node']['id']);


			$renew_original_date = 'false';
			// after_discount_type
			if (array_key_exists('offer_trial_status', (array)$get_all_data) && $get_all_data->offer_trial_status == 'on') {
				// echo "sdjhj";

				$offer_trial_status = 1;
				$trial_period_value = $get_all_data->trial_period_value;
				$trial_period_type = $get_all_data->trial_period_type ?? 'days';
				$renew_original_date = $get_all_data->renew_on_original_date == 'true'  ? 'true' : 'false';
				$discount_type = $get_all_data->discount_type;
				$discount_value = $get_all_data->discount_value;
				$discount_value_after = $get_all_data->discount_value_after;
				$discount_type_after = $get_all_data->discount_type_after;
				$discount_type_after = $discount_type_after == 'Discount Off' ? 'A' : 'P';
				$discount_type = $discount_type == 'Discount Off' ? 'A' : 'P';
			} else {
				$offer_trial_status = 0;
				$trial_period_value = null;
				$trial_period_type = 'days';
				$renew_original_date = 'false';
			}

			if (array_key_exists('discount_status', (array)$get_all_data) && ($get_all_data->discount_status == 'on' || $get_all_data->discount_status == '1')) {
				// echo "sdjhj";die;
				$discount_status = 1;
				$discount_type = $get_all_data->discount_type;
				$discount_type = $discount_type == 'Discount Off' ? 'A' : 'P';
				$discount_value = $get_all_data->discount_value;
				$discount_after_status = 1;
				$discount_value_after = $get_all_data->discount_value_after;
				$discount_type_after = $get_all_data->discount_type_after;
				$discount_type_after = $discount_type_after == 'Discount Off' ? 'A' : 'P';
			} else {
				$discount_status = 0;
				$discount_type = '';
				$discount_value = '';
				$discount_after_status = 0;
				$discount_value_after = '';
				$discount_type_after = '';
			}
			if (array_key_exists('discount_after_status', (array)$get_all_data) && ($get_all_data->discount_after_status == 'on' || $get_all_data->discount_after_status == '1')) {
				$discount_after_status = 1;
				$discount_value_after = $get_all_data->discount_value_after;
				$discount_type_after = $get_all_data->discount_type_after;
				$discount_type_after = $discount_type_after == 'Discount Off' ? 'A' : 'P';
			} else {
				$discount_after_status = 0;
			}
			$single_groupOption_details = array(
				'store' => $store,
				'offer_trial_status' => $offer_trial_status,
				'trial_period_value' => $trial_period_value,
				'trial_period_type' => $trial_period_type,
				'discount_offer' => $discount_status,
				'after_discount_offer'	=> $discount_after_status,
				'renew_on_original_date' => $renew_original_date,
				'membership_group_id' => trim($sellingPlanGroupId ?? ''),
				'membership_plan_id' => trim($membership_plan_id ?? ''),
				'membership_option_id' => trim($option_plan_id ?? ''),
				'description' => trim($option_description ?? ''),
				'option_charge_type' => trim($charge_period_type ?? ''),
				'option_charge_value' => trim($charge_period_value ?? ''),
				'discount_type' => trim($discount_type ?? 'P'),
				'discount_value' => trim($discount_value ?? ''),
				'after_discount_type' => trim($discount_type_after ?? 'P'),
				'after_discount_value' => trim($discount_value_after ?? ''),
				'change_discount_after_cycle' => trim($after_cycle_value ?? ''),
				'min_cycle' => trim($min_cycle ?? ''),
				'max_cycle' => trim($max_cycle ?? ''),
				'option_price' => trim($option_price ?? ''),
				'anchor_type' => trim($anchor_type ?? ''),
				'anchor_day' => trim($anchor_day ?? ''),
			);
			// print_r($single_groupOption_details);die;
			// echo('hkkk');
			// handel if value null
			$single_groupOption_details = array_filter($single_groupOption_details, function ($val) {
				return $val !== null && $val !== '';
			});
			// array_push($membership_groupOptions_payload, $single_groupOption_details);
			try {
				// $memberplan_products_save = MembershipGroupsDetail::upsert($membership_groupOptions_payload, ['store', 'membership_option_id'], ['store', 'membership_group_id', 'description', 'option_charge_type', 'option_charge_value', 'discount_offer', 'discount_type', 'discount_value', 'option_price', 'after_discount_offer', 'after_discount_type', 'after_discount_value', 'change_discount_after_cycle', 'min_cycle', 'max_cycle', 'anchor_type', 'anchor_day']);
				$whereCondition = [
					'store' => $store,
					'membership_option_id' => $option_plan_id
				];

				$memberplan_products_save = $this->insertupdateajax('membership_groups_details', $single_groupOption_details, $whereCondition, "AND");
			} catch (Exception $e) {
				echo $e->getMessage();
				// MembershipPlanGroup::where('membership_group_id', $sellingPlanGroupId)->delete();
				$whereCondition = [
					'store' => $store,
					'membership_group_id' => $sellingPlanGroupId
				];
				$response = $this->delete_row('membership_plan_groups', $whereCondition, "AND");

				try {
					$this->delete_memberPlan_group($sellingPlanGroupId);
				} catch (Exception $e) {
				}
				$result = array('isError' => true, 'message' => $e->getMessage());
				$this->upsertMetaObject();
				return json_encode($result); // return json

			}
		}
		// group option array ends here


		$result = array('isError' => false, 'message' => "Membership plan updated successfully", 'membership_plan_id' => $membership_plan_id);
		$this->upsertMetaObject();
		return json_encode($result); // return json

	}


	// new variant create in the existing product
	public function addNewVariant($variant_array, $store)
	{
		try {
			$get_product_options_query = '
            {
                product(id:"gid://shopify/Product/' . $variant_array['product_id'] . '"){
                  id
                  title
                  options{
                    name
                    id
                  }
                }
            }';
			$get_product_options_execution = $this->shopify_graphql_object->GraphQL->post($get_product_options_query, null, null, null);
			$get_product_option = $get_product_options_execution['data']['product']['options'];


			$options_string = [];
			for ($i = 0; $i < count($get_product_option); $i++) {
				$options_string[] = '{ name: "' . $variant_array['member_plan_tier_name'] . '", optionId: "' . $get_product_option[$i]['id'] . '" }';
			}

			// Combine options into a valid GraphQL string
			$variantsString = implode(", ", $options_string);

			// Now, let's build the mutation string
			$product_variant_create = 'mutation {
				productVariantsBulkCreate(
					productId: "gid://shopify/Product/' . $variant_array['product_id'] . '"
					variants: [
						{
							price: "' . $variant_array['option_price'] . '",
							optionValues: [' . $variantsString . '],
							taxable: false,
							inventoryItem: {
							    tracked: false,
                                requiresShipping: false,
								sku: "sd-membership-plan:' . $variant_array['member_plan_tier_name'] . '"
							}
						}
					]
				) {
					product {
						id
						title
						handle
						description
						featuredMedia {
							preview {
								image {
									url
								}
							}
						}
					}
					productVariants {
						id
						price
						title
					}
					userErrors {
						code
						field
						message
					}
				}
			}';

			$add_new_variant_execution = $this->shopify_graphql_object->GraphQL->post($product_variant_create);
			$new_variant_create_error = $add_new_variant_execution['data']['productVariantsBulkCreate']['userErrors'];

			if (!count($new_variant_create_error)) {
				$result = [
					'status' => 'not-error',
					'data' => $add_new_variant_execution['data']['productVariantsBulkCreate'],
				];
				return $result;
			} else {
				$message = $add_new_variant_execution['data']['productVariantsBulkCreate']['userErrors'][0]['message'] ?? 'Something went wrong';
				$result = [
					'status' => 'error',
					'message' => $message,
				];
				return $result;
			}
		} catch (Exception $e) {
			return 'error';
		}
	}

	function updateCustomerTags($store, $currentDate, $sale_start_date, $customerTags)
	{
		$sql = "
			SELECT membership_perks.no_of_sale_days, membership_perks.segment_id
			FROM membership_perks
			JOIN membership_plans ON membership_plans.id = membership_perks.membership_plan_id
			WHERE membership_plans.plan_status = 'enable'
			AND membership_perks.store = :store
			AND membership_perks.early_access_checked_value = '1'
			ORDER BY membership_perks.no_of_sale_days DESC
		";

		$stmt = $this->db->prepare($sql);
		$stmt->execute(['store' => $store]);

		$getMembershipData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (!empty($getMembershipData)) {
			foreach ($getMembershipData as $item) {
				$no_of_days = (int) $item['no_of_sale_days'];

				// $final_date = clone $sale_start_date;
				// $final_date->sub(new DateInterval('P' . $no_of_days . 'D'));
				// $final_date_str = $final_date->format('Y-m-d\TH:i:s\Z');
				$final_date_str = (new DateTime($sale_start_date))->sub(new DateInterval('P' . $no_of_days . 'D'))->format('Y-m-d\TH:i:s\Z');
				if ($currentDate >= $final_date_str) {
					$customerTags[] = $item['segment_id'];
				}
			}
		}

		$customerTags = array_unique($customerTags);
		return $customerTags;
	}

	//  delete plan 
	public function delete_member_group($request)
	{
		// print_r($request);
		// $allHeaders = $request->headers->all();
		// $get_response = $request->getContent();
		// $getauthorization_token = $request->header('Authorization');
		// $get_delete_group_data = json_decode($request->get('ajaxData'));

		$get_delete_group_data = json_decode($request['data']); // ← returns an object
		// print_r($get_delete_group_data);
		// die;
		$store = $this->store;
		$plan_group_id = $get_delete_group_data->member_group_id;
		$delete_variant_id = $get_delete_group_data->delete_variant_id;
		$delete_product_id = $get_delete_group_data->delete_product_id;
		$delete_product_variant = $this->delete_product_variant($delete_variant_id, $delete_product_id);
		// echo '<pre>';
		// print_r($delete_product_variant);
		// die;
		if ($delete_product_variant != 'error') {
			// disable group from db
			// $disable_group_status = MembershipPlanGroup::where('membership_group_id', $plan_group_id)->update(['group_status' => 'disable']);

			$fields = array(
				'group_status' => 'disable',
			);

			$whereCondition = array(
				'store' => $this->store,
				'membership_group_id' => $plan_group_id
			);

			$disable_group_status = $this->update_row('membership_plan_groups', $fields, $whereCondition, 'AND');

			// DB::table('membership_perks')->where([['membership_group_id', '=', $plan_group_id], ['store', '=', $store]])->delete();



			// Call the delete_row method to delete the record
			$result = $this->delete_row('membership_perks', $whereCondition, 'AND');

			$this->upsertMetaObject();
			return json_encode(array("isError" => false, 'message' => 'Member plan group deleted successfully!!')); // return json
		} else {
			return json_encode(array("isError" => true, 'message' => 'Member plan group delete error!!')); // return json
		}
	}

	// public function delete_product_variant($variant_id)
	// {
	// 	try {
	// 		$delete_product_variant = 'mutation {
	// 			productVariantDelete(id: "gid://shopify/ProductVariant/' . $variant_id . '") {
	// 				deletedProductVariantId
	// 				product {
	// 				id
	// 				title
	// 				}
	// 				userErrors {
	// 				field
	// 				message
	// 				}
	// 			}
	// 		}';
	// 		$delete_product_variant_execution = $this->shopify_graphql_object->GraphQL->post($delete_product_variant, null, null, null);
	// 		$delete_product_variant_execution_error = $delete_product_variant_execution['data']['productVariantDelete']['userErrors'];
	// 		// echo '<pre>';
	// 		// print_r($delete_product_variant_execution);
	// 		if (!count($delete_product_variant_execution_error)) {
	// 			return $delete_product_variant_execution;
	// 		} else {
	// 			return 'success';
	// 		}
	// 	} catch (Exception $e) {
	// 		// echo '<pre>';
	// 		// print_r($e->getMessage());
	// 		// die;
	// 		return 'error';
	// 	}
	// }

	public function delete_product_variant($variant_id, $product_id)
	{
		try {
			// $delete_product_variant = 'mutation {
			// 	productVariantDelete(id: "gid://shopify/ProductVariant/' . $variant_id . '") {
			// 		deletedProductVariantId
			// 		product {
			// 		id
			// 		title
			// 		}
			// 		userErrors {
			// 		field
			// 		message
			// 		}
			// 	}
			// }';

			// $product_gid = "gid://shopify/Product/".$product_id."";
			// $variantsIds = "gid://shopify/ProductVariant/" . $variant_id."";
			$delete_product_variant =
				'mutation {
                    productVariantsBulkDelete(productId: "gid://shopify/Product/' . $product_id . '", variantsIds: ["gid://shopify/ProductVariant/' . $variant_id . '"]) {
                        product {
                            id
                            title
                        }
                        userErrors {
                            field
                            message
                        }
					}
				}';

			// $variables = [
			//     "productId" => "gid://shopify/Product/".$product_id."",
			//     "variantsIds" => ["gid://shopify/ProductVariant/" . $variant_id]
			// ];
			// print_r($variables);
			//code...
			$delete_product_variant_execution = $this->shopify_graphql_object->GraphQL->post($delete_product_variant, null, null);
			// print_r($delete_product_variant_execution);die;
			$delete_product_variant_execution_error = $delete_product_variant_execution['data']['productVariantsBulkDelete']['userErrors'];
			// echo '<pre>';
			if (!count($delete_product_variant_execution_error)) {
				return $delete_product_variant_execution;
			} else {
				return 'success';
			}
		} catch (Exception $e) {
			// echo '<pre>';
			// print_r($e->getMessage());
			// die;
			return 'error';
		}
	}
	public function saveAppearanceData($data)
	{
		$action = $data['action'];
		$data = $data['serailize_object'];
		$buttonType = $data['btnType'];
		$storeName = $this->store;
		unset($data['btnType']);
		if (array_key_exists('templateName', $data)) {
			unset($data['templateName']);
		}
	
		$tableName = ($action == 'save-drawer-setting' ? 'drawer_settings' : ($action == 'save-widget-settings' ? 'member_plans_widgets' : ($action == 'save-countdown-settings' ? 'count_down_settings' : 'product_widget_settings')));
		$defaultValues = [
			'drawer_settings' => [
				'drawer_heading' => 'Discounts & Offers ',
				'free_shipping_text' => 'Use code {free_shipping} to get free shipping.',
				'percent_off_text' => 'use code {discount_name} to get {discount_value} off.',
				'discount_button_text' => 'Apply Discounts',
				'discount_button_radius' => '1',
				'drawer_background_color' => '#ffffff',
				'heading_text_color' => '#000000',
				'all_text_color' => '#000000',
				'cross_button_color' => '#000000',
				'button_bg_color' => '#000000',
				'button_text_color' => '#ffffff',
				'copied_message_text' => 'Code {discount_name} copied successfully.',
				'scissor_color' => '#ffffff',
				'code_bar_inner_bg_color' => '#000000',
				'code_bar_border_color' => '#000000',
				'code_bar_inner_color' => '#ffffff',
				'code_bg_color' => '#63db69',
				'code_bar_color' => '#000000',
				'copied_button_color' => '#000000',
				'free_shipping_minimum_quantity' => 'Applicable on minimum {minimum_quantity} quantity.',
				'free_shipping_minimum_amount' => 'Applicable on minimum {minimum_amount} order amount.',
				'percent_off_product_text' => 'Applicable only on {product_name}',
				'percent_off_collection_text' => 'Applicable only on {collection_name} collection products'
			],
			'member_plans_widgets' => [
				'heading_text' => 'Membership Plans',
				'heading_text_alignment' => 'center',
				'background_color1' => '#fffafa0c',
				'background_color2' => '#ffffff',
				'text_color' => '#000000',
				'heading_text_color' => '#000000',
				'most_popular_text' => 'Most Popular',
				'most_popular_text_color' => '#000000',
				'most_popular_background1' => '#68ebd166',
				'most_popular_background2' => '#95feb940',
				'widget_outline_color' => '#4DFF9E',
				'month_text' => 'month',
				'week_text' => 'week',
				'day_text' => 'day',
				'year_text' => 'year',
				'offer_text' => 'Avail Exclusive Offers!',
				'offer_background1' => '#68ebd166',
				'offer_background2' => '#95feb940',
				'perks_heading_text' => 'What is included in {{plan_name}}',
				'tick_icon_color' => '#4DFF9E',
				'button_background_color' => '#000000',
				'button_text_color' => '#ffffff',
				'button_text' => 'Subscribe',
				'heading_tag' => '30',
				'free_shipping_perk_description_all_orders' => 'Get Free Shipping on all orders',
				'free_shipping_perk_description_min_amt' => 'Get free shipping on spending minimum amount of {{minimum_purchase_amount}}',
				'free_shipping_perk_description_min_qty' => 'Get free shipping on spending minimum {{minimum_purchase_quantity}} quantity of items.',
				'product_collection_discount_perk_description' => 'Get {{discounted_product_collection_percentageoff}}% off on {{discounted_product_title}} {{products}}/{{collection}}',
				'all_products_discount_perk_description' => 'Get {{discounted_product_collection_percentageoff}}% off on all Products',
				'free_gift_perk_description' => 'Get free {{Free_gift_uponsignup_productName}} product upon sign up',
			],
			'product_widget_settings' => [
				'heading_text' => 'Membership Plans',
				'heading_text_color' => '#000000',
				'background_color1' => ' #fffafa0c',
				'background_color2' => '#ffffff',
				'radio_button_color' => '#000000',
				'text_color' => '#000000',
				'widget_outline_color' => '#4DFF9E',
				'charge_every_text' => 'charge every',
				'active_option_bgColor1' => '#fffff',
				'active_option_bgColor2' => '#fffff',
				'border_radius' => '1',
				'day_text' => 'day',
				'week_text' => 'week',
				'month_text' => 'month',
				'year_text' => 'year',
				'price_color' => '#000000',
				'description_text' => 'Description',
				'subscription_details_text' => 'Membership Details',
				'subscription_details' => 'Have complete control of your Membership. Skip, pause or cancel membership any time in accordance with the membership policy and your needs.',
				'description_background_color1' => '#ffffff',
				'description_background_color2' => '#ffffff',
			],
			'count_down_settings' => [
				'title_text' => 'Big Sale',
				'sale_end_text' => 'Sale End in',
				'day_text' => 'Day',
				'hour_text' => 'Hour',
				'inner_bgcolor1' => '#000000',
				'inner_bgcolor2' => '#000000',
				'inner_border_color' => '#000000',
				'inner_border_radius' => '5',
				'mint_text' => 'Min',
				'outer_bgcolor1' => '#ffffff',
				'outer_bgcolor2' => '#ffffff',
				'outer_border_color' => '#000000',
				'outer_border_radius' => '5',
				'second_text' => 'Sec',
				'time_text_color' => '#ffffff',
				'text_color' => '#000000',
				'select_layout' => 'layout1',
				'discount_text' => 'Use {discount_code} coupan code and get {discount_off}% Off on checkout',
			]

		];
		$where_condition = ['store' => $storeName];
		try {
			if ($buttonType == 'Save') {
				foreach ($defaultValues[$tableName] as $column => $defaultValue) {
					if (empty($data[$column])) {
						$data[$column] = $defaultValue;
					}
				}
				$this->upsertMetaObject();
				$this->insertupdateajax($tableName, $data, $where_condition, '');
				// $insertData = DrawerSetting::updateOrInsert(['store' => $storeName], $ajaxData);
				// $this->upsertMetaObject($storeName);
				return json_encode(array('status' => true, 'message' => 'Successfully update'));
			} else {
				// DB::table('drawer_settings')->where('store', $storeName)->update($defaultValues);
				$this->upsertMetaObject();
				$this->update_row($tableName, $defaultValues[$tableName], $where_condition, '');
				return json_encode(['status' => true, 'message' => 'Setting reset successfully']);
			}
		} catch (\Exception $e) {
			return json_encode(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
		}
	}
	// popular plan
	public function update_popular_plan($request)
	{

		// print_r($request);
		// $get_all_data = json_decode($request->get('ajaxData'));
		$get_all_data = json_decode($request['data']); // ← returns an object
		// print_r($get_all_data);
		// die;

		$member_group_id =  $get_all_data->plan_group_id;
		$popular_plan = $get_all_data->popular_plan;
		$member_plan_id = $get_all_data->member_plan_id;

		// $popular_plan_update = MembershipPlanGroup::where('membership_plan_id', $member_plan_id)->update(['popular_plan' => '0']);
		$fields = array(
			'popular_plan' => '0',
		);

		$whereCondition = array(
			'store' => $this->store,
			'membership_plan_id' => $member_plan_id
		);

		$popular_plan_update = $this->update_row('membership_plan_groups', $fields, $whereCondition, 'AND');

		// $popular_plan_upate = MembershipPlanGroup::where('membership_group_id', $member_group_id)->update(['popular_plan' => $popular_plan]);
		$fields = array(
			'popular_plan' => $popular_plan,
		);

		$whereCondition = array(
			'store' => $this->store,
			'membership_group_id' => $member_group_id
		);
		$popular_plan_upate = $this->update_row('membership_plan_groups', $fields, $whereCondition, 'AND');

		$result = array('isError' => false, 'message' => "Saved successfully");

		$this->upsertMetaObject();
		return json_encode($result); // return json

	}
	public function sendMembershipEmail($data)
	{
		// Initialize template data
		$template_data = [];
		$template_data['template_name'] = ($data['email_template_array']['template_name'] ?? '') . 's';
		$template_data['email_type'] = $data['email_template_array']['send_to_email'] ?? '';
		$template_data['shop_name'] = $this->store;
		$template_data['discount_coupon_content'] = true;
		$template_data['free_shipping_coupon_content'] = true;
		$template_data['early_sale_content'] = true;
		$template_data['free_signup_product_content'] = true;
		$template_data['free_gift_uponsignupSelectedDays'] = true;
		$mailBody = $data['email_template_array']['emailBody'] ? $data['email_template_array']['emailBody'] : $this->membershipAllEmailTemplates($template_data)['email_template_html'];
		// print_r($template_data);
		// die;
		// Fetch email template
		// $email_template = $this->membershipAllEmailTemplates($template_data);

		if (!empty($email_template)) {
			// switch ($data['email_template_array']['template_name']) {
			// 	case 'membership_skip_membership':
			// 		// $mailBody =  $this->getMembershipMailBody();
			// 		break;

			// 	default:
			// 		# code...
			// 		break;
			// }

			$sendMailArray = array(
				'sendTo'      => $data['email_template_array']['send_to_email'],
				'subject'     => $email_template['subject'],
				'mailBody'    =>  $mailBody,
				'ccc_email'   => $email_template['ccc_email'] ?? '',
				'bcc_email'   => $email_template['bcc_email'] ?? '',
				'reply_to'    => $email_template['reply_to'] ?? '',
			);

			// print_r(($sendMailArray));

			try {
				$contract_deleted_mail = $this->sendMail($sendMailArray, 'false');
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {

			echo json_encode(['status' => false, 'message' => 'Invalid email template data']);
		}
	}
	function emailSend($data)
	{
		try {


			// print_r($data);die;
			$store_id = $this->store_id;
			$ccc_email = $data['email_template_array']['ccc_email'];
			$bcc_email = $data['email_template_array']['bcc_email'];
			$reply_to = $data['email_template_array']['reply_to'];
			$mail =  new PHPMailer\PHPMailer\PHPMailer();
			$result = $this->db->query("SELECT * FROM email_configuration WHERE store_id = '$store_id' LIMIT 1");
			$emailConfiguration = $result->fetch(PDO::FETCH_OBJ);

			// print_r($emailConfiguration);

			if (count((array)$emailConfiguration) > 0) {
				$checkCondtion = $emailConfiguration->email_enable;
			} else {
				$checkCondtion = 'false';
			}

			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->SMTPAuth = true;

			if ($checkCondtion == 'checked') {
				$emailHost = $emailConfiguration->email_host;
				$emailUsername = $emailConfiguration->username;
				$emailPassword = $emailConfiguration->password;
				$emailEncriptionType = $emailConfiguration->encryption;
				$emailPortNumber = $emailConfiguration->port_number;
				$senderName = $emailConfiguration->from_email;
			} else {
				$username = getDotEnv('EMAIL_USERNAME');
				$emailHost = getDotEnv('EMAIL_HOST');
				$emailUsername = getDotEnv('EMAIL_USERNAME');
				$emailPassword = getDotEnv('EMAIL_PASSWORD');
				$senderName = getDotEnv('EMAIL_USERNAME');
				$emailEncriptionType = getDotEnv('ENCRYPTION');
				$emailPortNumber = getDotEnv('PORT_NUMBER');
				// $username = "notify@shinedezignapps.pro";
			}
			$mail->Host = $emailHost;
			$mail->Username = $emailUsername;
			$mail->Password = $emailPassword;
			$mail->SMTPSecure = $emailEncriptionType;
			$mail->Port = $emailPortNumber;

			if ($checkCondtion == 'false') {
				$mail->setFrom($username, $senderName);
			} else {
				$mail->setFrom($emailUsername, $senderName);
			}
			if (is_array($data['email_template_array']['send_to_email'])) {
				# code...
				$mail->addAddress($data['email_template_array']['send_to_email'][0]);
				$mail->addAddress($data['email_template_array']['send_to_email'][1]);
			} else {
				$mail->addAddress($data['email_template_array']['send_to_email']);
			}

			// Adding CC recipients
			if (!empty($ccc_email)) {
				if (is_array($ccc_email)) {
					foreach ($ccc_email as $cc_email) {
						$mail->addCC(trim($cc_email));
					}
				} else {
					$cc_emails = explode(',', $ccc_email);
					foreach ($cc_emails as $cc_email) {
						$mail->addCC(trim($cc_email));
					}
				}
			}

			// Adding BCC recipients
			if (!empty($bcc_email)) {
				if (is_array($bcc_email)) {
					foreach ($bcc_email as $bcc_email) {
						$mail->addBCC(trim($bcc_email));
					}
				} else {
					$bcc_emails = explode(',', $bcc_email);
					foreach ($bcc_emails as $bcc_email) {
						$mail->addBCC(trim($bcc_email));
					}
				}
			}

			if (!empty($reply_to)) {
				$mail->addReplyTo($reply_to);
			}

			$mail->isHTML(true);
			$mail->Subject = $data['email_template_array']['subject'];
			$mail->Body = $data['email_template_array']['emailBody'];

			if (!$mail->Send()) {
				// return json_encode(array("status"=>false, "message"=>$mail->ErrorInfo));
				// echo 'mail not sent';
			} else {
				// return json_encode(array("status"=>true, "message"=>'Email Sent Successfully'));
				// echo 'mail sent successfully';
			}
		} catch (Exception $e) {
			return false;
		}
	}
	public function saveEmailTemplate($data)
	{

		$shopName = $this->store;
		// $ajaxData = $request->input('ajaxData');
		// Decode JSON data
		$get_all_data = $data['serailize_object'];
		// print_r($get_all_data);
		$templateName = $get_all_data['templateName'];
		$buttonType = $get_all_data['btnType'];
		unset($get_all_data['templateName']);
		unset($get_all_data['btnType']);
		// echo $templateName;die;

		if ($templateName != 'sd_new_purchase_plans') {
			// echo "hdejhfvujhfj";
			if ($get_all_data['content_heading'] == '<p class=""><br></p>') {
				$get_all_data['content_heading'] = '';
				unset($get_all_data['content_heading_text']);
			}
		} else {
			// echo "hwere";
			unset($get_all_data['content_heading']);
			if ($get_all_data['content_heading_text'] == '<p style="font-size: 14px;"><br></p>') {
				$get_all_data['content_heading_text'] = '';
			}
			if ($get_all_data['discount_coupon_content'] == '<h2 style="text-align:inherit"><br></h2>') {
				$get_all_data['discount_coupon_content'] = '';
			}
			if ($get_all_data['free_shipping_coupon_content'] == '<h2 style="text-align:inherit"><br></h2>') {
				$get_all_data['free_shipping_coupon_content'] = '';
			}
			if ($get_all_data['early_sale_content'] == '<h2 style="text-align:inherit"><br></h2>') {
				$get_all_data['early_sale_content'] = '';
			}
			if ($get_all_data['free_signup_product_content'] == '<h2 style="text-align:inherit"><br></h2>') {
				$get_all_data['free_signup_product_content'] = '';
			}
			if ($get_all_data['free_gift_signup_product'] == '<h2 style="text-align:inherit"><br></h2>') {
				$get_all_data['free_gift_signup_product'] = '';
			}
			if ($get_all_data['footer_content'] == '<p><br></p>') {
				$get_all_data['footer_content'] = '';
			}
		}
		// echo '<pre>';
		// print_r($get_all_data);  die;

		$columnDefaultsValues = [
			'ccc_email' => null,
			'bcc_email' => null,
			'reply_to' => null,
			'custom_email_html' => null,
			'logo' => null,
			'logo_height' => 40,
			'logo_width' => 170,
			'logo_alignment' => 'left',
			'logo_bg_color' => '#FFFFFF',
			'header_bg_color' => '#50E1B0',
			'footer_bg_color' => '#50E1B0',
			'button_content_bg_color' => '#FFFFFF',
			'heading_text_color' => '#000000',
			'email_background_color' => '#F3F3F3',
			'content_background' => '#FFFFFF',
			'manage_button' => 'View your Account',
			'manage_button_url' => null,
			'manage_btn_text_color' => '#000000',
			'manage_btn_bg_color' => '#50E1B0',
			'footer_text' => 'Thank You',
		];

		$whereCondition = ['store' => $shopName];
		// Save the data using the modelName ( make sure to handle exceptions )

		try {

			switch ($templateName) {

				case 'sd_new_purchase_plans':
					$columnDefaultsValues['subject'] = 'New membership purchased';
					$columnDefaultsValues['heading_bg_color'] = '#50E1B0';
					$columnDefaultsValues['logo_content_bg_color'] = '#FFFFFF';
					$columnDefaultsValues['tick_bg_color'] = '#50E1B0';
					$columnDefaultsValues['heading_text'] = 'Congratulations';
					$columnDefaultsValues['feature_heading'] = 'Exclusive Benefits Await You!';
					$columnDefaultsValues['gift_link_text'] = 'Redeem now';
					$columnDefaultsValues['content_heading_text'] = '<p>Hey <b> {{customer_name}} </b>, <br><br> </p><p>We are excited to have you on board as a new member. Your membership has started today. During that time, you will enjoy a host of exclusive benefits. <br><br> We are confident that our membership program will help you achieve your goals and reach your full potential. We are here to support you every step of the way.</p>';
					$columnDefaultsValues['discount_coupon_content'] = '<h2 style="text-align:inherit"><b>Exclusive Discounts</b></h2><div>Apply <b>{{coupon_code}}</b> coupon code during checkout to avail an exciting <b>{{percentage_discount}}% off</b> discount on your total purchase. Treat yourself or your loved ones to our premium products while saving big.</div>';
					$columnDefaultsValues['free_shipping_coupon_content'] = '<h2 style="text-align:inherit"><b>Free Shipping Offer</b></h2><div>Use <b>{{free_shipping_code}}</b> coupon code during checkout to enjoy free shipping on your order. Now, shop with ease and have your favorite products delivered right to your doorstep without any extra shipping charges!</div>';
					$columnDefaultsValues['free_signup_product_content'] = '<h2 style="text-align:inherit"><b>Free Sign Up Product</b></h2><div>Your free sign-up product, <b> {{free_signup_product}} </b> is ready for you to enjoy. Your Free Signup Product Date is <b>{{free_sign_up_produt_date}}</b></div>';
					$columnDefaultsValues['early_sale_content'] = '<h2 style="text-align:inherit"><b>Early Sale Access</b></h2><div> You have been granted, <b> early access to the sale for {{number_of_days}} days</b>.</div>';
					$columnDefaultsValues['free_gift_signup_product'] = '<h2 style="text-align:inherit"><b>Immediate Sign Up Gift</b></h2><div>This gift is one of our most popular products in the store, and we know you are going to love it!. To receive your <b> {{free_signup_product}} </b>, simply click on this link:</div>';
					$columnDefaultsValues['footer_content'] = '<p>Thank you for choosing our membership plan <b>( {{plan_name}} )</b>! We look forward to helping you succeed.<br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>We value your business and appreciate your understanding.<br><br>Sincerely,<br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						// $insertData = NewPurchasePlan::updateOrInsert(['store' => $shopName], $get_all_data);
						// print_r($get_all_data);
						$insertData = $this->insertupdateajax('new_purchase_plans', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('new_purchase_plans', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					break;





				case 'sd_plan_payment_pendings':

					$columnDefaultsValues['subject'] = 'Your Plan Payment is Pending';
					$columnDefaultsValues['heading_text'] = 'Plan Payment Pending';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> I am writing to let you know that your payment for your <b> {{plan_name}} </b>is Pending. Please make your payment to avoid service interruption.<br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>We value your business and appreciate your understanding.<br><br>Sincerely,<br>{{store_name}}</p>';
					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('plan_payment_pendings', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('plan_payment_pendings', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}
					// PlanPaymentPending::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );
					break;





				case 'sd_credit_card_expirings':
					$columnDefaultsValues['subject'] = 'Credit Card Expiring Soon';
					$columnDefaultsValues['heading_text'] = 'Credit Card Expiring';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> Your credit card for your <b> {{plan_name}} </b>subscription is expiring soon. Please update your payment information to avoid service interruption.You can update your payment information by logging into your account.<br><br>Thank you for your continued business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('credit_card_expirings', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('credit_card_expirings', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					// CreditCardExpiring::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );

					break;

				case 'sd_plan_payment_faileds':
					$columnDefaultsValues['subject'] = 'Your Plan Payment is Failed';
					$columnDefaultsValues['heading_text'] = 'Payment failed';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We noticed that your payment for your {{plan_name}} subscription failed. Please update your payment information or make a payment to avoid service interruption.<br><br>You can update your payment information or make a payment by logging into your account<br><br>if you are having trouble making a payment, please contact our customer support team for assistance.<br><br>Thank you for your attention to this matter.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('plan_payment_faileds', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('plan_payment_faileds', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					// PlanPaymentFailed::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );

					break;

				case 'sd_plan_payment_declineds':
					$columnDefaultsValues['subject'] = 'Your Payment has been declined';
					$columnDefaultsValues['heading_text'] = 'Transaction Declined';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We noticed that your payment for your {{plan_name}} subscription was declined. Please update your payment information or make a payment to avoid service interruption.<br><br>You can update your payment information or make a payment by logging into your account.If you are having trouble making a payment, please contact our customer support team for assistance.<br><br>We understand that there may be various reasons for a declined payment, so please do not hesitate to reach out to us if you have any feedback or concerns. We value your patronage and hope to continue serving you in the future.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('plan_payment_declineds', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('plan_payment_declineds', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					// PlanPaymentDeclined::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );

					break;

				case 'sd_membership_cancelleds':
					$columnDefaultsValues['subject'] = 'Membership {{plan_status}}';
					$columnDefaultsValues['heading_text'] = 'Membership {{plan_status}}';
					$columnDefaultsValues['content_heading'] = 'Hey <b> {{customer_name}} </b>, <br><br> We would like to inform you that your subscription for {{plan_name}} has been <b>{{plan_status}}</b>. We understand that this decision may have been made for various reasons, and we appreciate the time you spent with our service. If you have any feedback or concerns regarding your experience or if you would like to explore alternative subscription options, please do not hesitate to reach out to our customer support team. We value your patronage and hope to assist you with any future needs or questions you may have.<br><br>Thank you for your business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('membership_cancelleds', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('membership_cancelleds', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}
					// MembershipCancelled::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );
					break;

				case 'sd_membership_upgrades':
					$columnDefaultsValues['subject'] = 'Your Plan has been upgraded';
					$columnDefaultsValues['heading_text'] = 'Plan upgraded';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> Congratulations on upgrading to {{plan_name}} ! We are excited to have you as a member of our premium subscription plan. With {{plan_name}}, you will get access to all of our features and benefits, and We are confident that you will love your new subscription plan. <br><br>If you have any questions or concerns, please do not hesitate to contact our customer support team. We are here to help you make the most of your new subscription.<br><br>Thank you for choosing {{store_name}}!<br><br> Sincerely, <br><br>{{store_name}} </p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('membership_upgrades', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('membership_upgrades', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}
					// MembershipUpgrade::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );
					break;

				case 'sd_membership_downgrades':
					$columnDefaultsValues['subject'] = 'Your Plan has been downgraded';
					$columnDefaultsValues['heading_text'] = 'Plan Downgraded';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We understand that your needs may change, and we are here to support you. We have downgraded your subscription to <b> {{plan_name}}</b> at your request. <br><br>If you have any feedback or concerns about your experience with us, please do not hesitate to reach out to our customer support team. We are always looking for ways to improve, and your feedback is valuable to us.<br><br>We hope that you will continue to use our service in the future. If you decide that you need more features or benefits, you can always <b><i>upgrade</i></b> your subscription at any time.<br><br>Thank you for your business.<br><br>Sincerely,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('membership_downgrades', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('membership_downgrades', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					// MembershipDowngrade::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );


					break;

				case 'sd_membership_skip_memberships':
					$columnDefaultsValues['subject'] = 'Your membership plan has been skipped';
					$columnDefaultsValues['heading_text'] = 'Membership plan skipped';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hello <b>{{customer_name}}</b>, <br><br>We wanted to inform you that your<b> {{plan_name}} </b>subscription on upcoming date<b> {{skip_date}} </b>has been skipped.<br><br>You can easily see details by logging into your account. if you encounter any challenges or require assistance, our dedicated customer support team is available to help you.<br><br>We understand that situations arise, leading to plan skips. If you have any feedback or concerns, we encourage you to reach out. Your satisfaction is of the utmost importance to us, and we look forward to resolving any issues promptly.<br><br>Best regards,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('membership_skip_memberships', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('membership_skip_memberships', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					// MembershipSkipMembership::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );

					break;





				case 'sd_membership_renews':
					$columnDefaultsValues['subject'] = 'Your Plan has been Renewed';
					$columnDefaultsValues['heading_text'] = 'Plan Renew';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br> We are excited to let you know that your subscription to  <b> {{plan_name}} <i> has been renewed! </i></b>. <br><br>We appreciate your continued business and look forward to serving you for another year. If you have any questions or concerns, please do not hesitate to contact our customer support team.<br><br>Thank you for choosing {{store_name}}!<br><br>Sincerely,<br><br>{{store_name}}</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('membership_renews', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('membership_renews', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}

					// MembershipRenew::upsert( $get_all_data, [ 'store' ], $templatesColumnNames );

					break;

				case 'sd_membership_free_gifts':

					$columnDefaultsValues['subject'] = 'You get the free gift';
					$columnDefaultsValues['gift_link_text'] = 'Redeem now';
					$columnDefaultsValues['heading_text'] = 'Hurray! you get the free gift';
					$columnDefaultsValues['content_heading'] = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We are so excited to have you as a member ! As a thank-you for signing up, we are giving you a free gift. Your gift is a <b> {{free_signup_product}} </b>. We think you will love it!. We hope you enjoy your gift. We are always adding new products and services, so be sure to check back often.<br><br> To claim your gift, simply click on the link below:</p>';

					if ($buttonType == 'Save') {
						foreach ($columnDefaultsValues as $column => $defaultValue) {
							if (empty($get_all_data[$column])) {
								$get_all_data[$column] = $defaultValue;
							}
						}
						$insertData = $this->insertupdateajax('membership_free_gifts', $get_all_data, $whereCondition, '');
						return json_encode(array('status' => true, 'message' => 'Successfully Saved'));
					} else {
						$this->update_row('membership_free_gifts', $columnDefaultsValues, $whereCondition, '');
						return json_encode(['status' => true, 'message' => 'Reset successfully']);
					}
					break;

					// default:

					return json_encode(['status' => false, 'message' => 'Invalid templateName.'], 400);
			}

			return json_encode(['status' => true, 'message' => 'Template saved successfully.'], 200);
		} catch (\Exception $e) {
			echo $e;
			return json_encode(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
		}
	}

	// public function appUsageRecordCreate($data)
	public function appUsageRecordCreate($app_sub_plan_id, $price, $store_id, $plan_name)
	{

		$Planprice  = $price;

		$SubplanItemId = $app_sub_plan_id;
		$storeId  = $store_id;
		// Calculate 5% of the plan price
		$percentage = 5;
		$amount = ($Planprice * $percentage / 100) + 0.12; // add extra charge 0.15
		// Set a minimum charge of $1 if neede +++

		$miniCharge = 0.10;
		if ($amount >= $miniCharge) {

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
			$response = $this->shopify_graphql_object->GraphQL->post($craete_action_on_billing);
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
				$stmt = $this->db->prepare("INSERT INTO UsageCharge (store_id, price, charge_amount, charged_at, sub_plan_item_id, usage_record_id) VALUES (?, ?, ?, ?, ?, ?)");
				$savedata = $stmt->execute([$storeId, $price, $usage_amount, $current_date, $SubplanItemId, $usageRecodeId]);

				// echo 'Usage charge taken successfully.';
				return $savedata;
			} else {
				echo ('some error');
			}
		}
	}
	public function viewMembershipAnalytics($data)
	{
		$store = $data['store'] ?? null;
		$dateRange = $data['dateRange'] ?? null;

		if ($store && $dateRange) {
			// Split the date range
			$dateParts = explode(' - ', $dateRange);
			$startDate = date('Y-m-d 00:00:00', strtotime(trim($dateParts[0])));
			$endDate = date('Y-m-d 00:00:00', strtotime(trim($dateParts[1] . ' +1 day'))); // include full end day

			// Determine data year label
			$startYear = date('Y', strtotime($startDate));
			$endYear = date('Y', strtotime($endDate));
			$dataYear = ($startYear === $endYear) ? $startYear : "$startYear-$endYear";

			// Query product details
			$stmt = $this->db->prepare("SELECT * FROM subscritionOrderContractProductDetails WHERE store_id = ? AND created_at >= ? AND created_at <= ? AND plan_type = ?");
			$stmt->execute([$this->store_id, $startDate, $endDate, 'membership']);
			$productDetailsTable = $stmt->fetchAll();

			// Query recurring orders
			$stmt = $this->db->prepare("SELECT * FROM billingAttempts WHERE store_id = ? AND created_at >= ? AND created_at <= ? AND plan_type = ?");
			$stmt->execute([$this->store_id, $startDate, $endDate, 'membership']);
			$recurringTable = $stmt->fetchAll();

			// Fallbacks (optional, not strictly necessary if fetchAll returns arrays)
			$productDetailsTable = $productDetailsTable ?: 0;
			$recurringTable = $recurringTable ?: 0;

			// Return JSON response
			header('Content-Type: application/json');
			echo json_encode([
				'productDetailsTable' => $productDetailsTable,
				'recurringTable' => $recurringTable,
				'dataYear' => $dataYear,
			]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required parameters']);
		}
	}

	// autodicount code
	public function discountAutomaticCreateUpdate($data)
	{   
    // $SHOPIFY_FUNCTION_ID = 'bc1cf909-5937-4c47-b136-1dfe1dbb385e';
		$SHOPIFY_FUNCTION_ID = '019cf6ac-7557-7dd1-a307-b23f0b67b5b5';
		$date = date('Y-m-d H:i:s');
		try {
			$jsonData = $_REQUEST['data'] ?? '';
			if (empty($jsonData)) {
				throw new \Exception("No data received.");
			}

			$decodedData = json_decode($jsonData, true); // Convert JSON to associative array
			// print_r($decodedData);
			// die;
		
			// Extract discount values
			$discount_name          = $decodedData['discount_name'] ?? '';
			$discount_value         = $decodedData['discount_value'] ?? '';
			$discount_checked_value = $decodedData['discount_checked_value'] ?? 'Inactive';

			// Step 1: Prepare base discount row
			$discountRow = [
				'discount_name' => $discount_name,
				'discount_value' => $discount_value,
				'status' => $discount_checked_value,
				'store' => $this->store,
				'created_at' => date('Y-m-d'),
			];

			$where = ['store' => $this->store, 'discount_name' => $discount_name];
            $discountRowDetails = array_filter($discountRow, function ($val) {
				return $val !== null && $val !== '';
			});
		
			// Save or update discount
			// $discount_details = $this->insertupdateajax('automatic_discount', $discountRowDetails, $where, 'AND');
            
			//++++++++++ GrphQL +++++
            $start_date_type = gmdate("Y-m-d\TH:i:s\Z"); // Current UTC time in ISO 8601 format
         
			$end_date = new DateTime("now", new DateTimeZone("UTC"));
			$end_date->modify('+1 year');
			$end_date_type = $end_date->format("Y-m-d\TH:i:s\Z"); // +1 year from now in UTC


			$createDiscount = 'mutation {
				discountAutomaticAppCreate(
					automaticAppDiscount: {
					title: "phoenix-membership-discount"
					functionId: "' . $SHOPIFY_FUNCTION_ID . '"
			
					startsAt: "2025-06-01T00:00:00"
					}
				) {
					automaticAppDiscount {
					discountId
					}
					userErrors {
					field
					message
					}
				}
			}';
  
			// $graphQL_get_discount_status = $this->shopify_graphql_object->GraphQL->post($createDiscount);

			// $createDiscount = 'mutation discountAutomaticAppCreate($automaticAppDiscount: DiscountAutomaticAppInput!) {
			//     discountAutomaticAppCreate(automaticAppDiscount: $automaticAppDiscount) {
			// 	userErrors {
			// 		field
			// 		message
			// 		}
			// 		automaticAppDiscount {
			// 		discountId
			// 		title
			// 		startsAt
			// 		endsAt
			// 		status
			// 		appDiscountType {
			// 			appKey
			// 			functionId
			// 		}
			// 		combinesWith {
			// 			orderDiscounts
			// 			productDiscounts
			// 			shippingDiscounts
			// 		}
			// 		}
			// 	}
			// }';



			// $variables = [
			// 	"automaticAppDiscount" => [
			// 		"title" => "phoenix-membership-discount",
			// 		"functionId" => $SHOPIFY_FUNCTION_ID,
			// 		"startsAt" => $date_type 
			// 	]
			// ];

			$variables = [
				"automaticAppDiscount" => [
					"title" => "phoenix-membership-discount",
					"functionId" => $SHOPIFY_FUNCTION_ID,
					"startsAt" => $start_date_type,
					"endsAt" => $start_date_type,
					"combinesWith" => [
						"orderDiscounts" => false,
						"productDiscounts" => false,
						"shippingDiscounts" => false
					],
					"metafields" => [
						[
							"namespace" => "default",
							"key" => "function-configuration",
							"type" => "json",
							"value" => json_encode([
								"discounts" => [
									[
										"value" => ["fixedAmount" => ["amount" => 40]],
										"targets" => [
											["orderSubtotal" => ["excludedVariantIds" => []]]
										]
									]
								],
								"discountApplicationStrategy" => "FIRST"
							])
						]
					]
				]
			];

            // print_r($createDiscount);

            $graphQL_get_discount_status = $this->shopify_graphql_object->GraphQL->post($createDiscount, null, null, $variables);

            // print_r($graphQL_get_discount_status);
          
			$graphQL_get_discount_status_error = $graphQL_get_discount_status['data']['discountAutomaticAppCreate']['userErrors'];

			if (empty($graphQL_get_discount_status_error)) {
				// echo('error here');
				// return json_encode(array("status" => true, 'error' => ''));
			} else {
                echo('errrrrrrrrrrr');
				//  print_r($graphQL_get_discount_status_error);
			     
				return json_encode(array("status" => false, 'error' => $graphQL_get_discount_status_error));
			}
         

			// Save Discount product data
	        $ProductStoreData = []; 

			foreach ($decodedData['discount_select_products'] as $product) {
				$productRow = [
					'store'          => $this->store,
					'discount_name'  => $discount_name,
					'discount_value' => $discount_value,
					'status'         => $discount_checked_value,
					'product_id'     => $product['product_id'] ?? '',
					'variant_id'     => $product['variant_id'] ?? '',
					'product_title'  => $product['title'] ?? '',
					'image'          => $product['image'] ?? '',
					'created_at'     => date('Y-m-d H:i:s'),
				];

				// ✅ Use productRow here, not discountRow
				$productRowDetails = array_filter($productRow, function ($val) {
					return $val != null && $val != '';
				});

				$wherePro = [
					'store'      => $this->store,
					'product_id' => $productRow['product_id'],
					'variant_id' => $productRow['variant_id']
				];

				$ProductStoreData[] = $this->insertupdateajax('automatic_discount', $productRowDetails, $wherePro, 'AND');
			}
			
            
            if (!empty($ProductStoreData)) {

				$productIds = [];

				foreach ($ProductStoreData as $entry) {
					$decoded = json_decode($entry, true);
					if (!empty($decoded['id'])) {
						$productIds[] = (int) $decoded['id']; // Cast to integer for safety
					}
				}

				$discountstoredProducts = [];

				if (!empty($productIds)) {
					$idsString = implode(',', $productIds); 
					$sql = "SELECT * FROM automatic_discount WHERE id IN ($idsString)";
					$discountstoredProducts = $this->customQuery($sql);
				}

				// print_r($discountstoredProducts);
			}


			// add collection product discount
			foreach ($decodedData['discount_select_collections'] as $collectionProduct) {
				$collectionProductRow = [
					'store'          => $this->store,
					'discount_name'  => $discount_name,
					'discount_value' => $discount_value,
					'status'         => $discount_checked_value,
					'product_id'     => $collectionProduct['id'] ?? '',
					'product_title'  => $collectionProduct['title'] ?? '',
					'image'          => $collectionProduct['image'] ?? '',
					'created_at'     => date('Y-m-d'),
				];

				$collectionProductRowDetails = array_filter($collectionProductRow, function ($val) {
					return $val != null && $val != '';
				});

				$wherePro = [
					'store'      => $this->store,
					'product_id' => $collectionProduct['id'],
				];

				$collectionStoreData[] = $this->insertupdateajax('automatic_discount', $collectionProductRowDetails, $wherePro, 'AND');

			}

			if (!empty($collectionStoreData)) {
				$collectionIds = [];

				foreach ($collectionStoreData as $entry) {
					$decoded = json_decode($entry, true);
					if (!empty($decoded['id'])) {
						$collectionIds[] = (int) $decoded['id']; // Ensure ID is an integer
					}
				}

				$storedCollectionDiscounts = [];

				if (!empty($collectionIds)) {
					$idsString = implode(',', $collectionIds); 
					$sql = "SELECT * FROM automatic_discount WHERE id IN ($idsString)";
					$storedCollectionDiscounts = $this->customQuery($sql);
				}

			
			}

			// Return success JSON
			return json_encode(['status' => true, 'message' => 'Discount saved successfully.', 'discount_product' => $discountstoredProducts, 'discount_collection' => $storedCollectionDiscounts], JSON_PRETTY_PRINT);
		
		} catch (\Exception $e) {
			return json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()], JSON_PRETTY_PRINT);
		}
	}
}
