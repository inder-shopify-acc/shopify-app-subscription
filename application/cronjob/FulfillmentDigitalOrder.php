<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$dirPath = dirname(dirname(__DIR__));

use PHPShopify\ShopifySDK;

require($dirPath . "/PHPMailer/src/Exception.php");
include $dirPath . "/graphLoad/autoload.php";
include $dirPath . "/application/library/config.php";

// Batch settings
$limit  = 50;
$offset = 0;

$SHOPIFY_API_VERSION = '2025-04';

while (true) {

	$contract_cron_query = $db->query("
        SELECT * 
        FROM contract_cron 
        WHERE status = 'completed' AND fulfill_status = 0
        ORDER BY id ASC
        LIMIT $limit OFFSET $offset
    ");
	$contract_cron_data  = $contract_cron_query->fetchAll(PDO::FETCH_ASSOC);

	if (empty($contract_cron_data)) {
		echo "No unfulfillment jobs.\n";
		break;
	}
	echo (count($contract_cron_data));


	foreach ($contract_cron_data as $row) {
		$store    = $row['store'];
		// $store    = 'test-store-phoenixt.myshopify.com';
		$order_id = $row['order_id'];
		// $order_id = '6747358855223';


		//  $store_install_query = $db->query("
		//     SELECT access_token, store_id, shop_timezone, shop_name, store_email 
		//     FROM install 
		//     LEFT JOIN store_details ON install.id = store_details.store_id 
		//     WHERE install.store = '$store'
		// ");

		$store_install_query = $db->query("
            SELECT access_token, id, store
            FROM install WHERE store = '$store'
        ");
		$store_install_data = $store_install_query->fetch(PDO::FETCH_ASSOC);


		if (!$store_install_data) {
			file_put_contents(
				$dirPath . "/application/assets/txt/webhooks/fulfillment_status_create.txt",
				"\n\n store_install_data not available. " . $row['id'],
				FILE_APPEND | LOCK_EX
			);
			// Mark back to pending so it can retry later
			// $db->prepare("UPDATE contract_cron SET status = 'pending' WHERE id = ?")->execute([$row['id']]);
			continue;
		}

		$access_token = $store_install_data['access_token'];

		// echo "Processing fulfillment for Order: {$order_id} on Store: {$store}\n";

		$config = [
			'ShopUrl'    => $store,
			'AccessToken' => $access_token,
			'ApiVersion' => $SHOPIFY_API_VERSION
		];

		$shopify = new ShopifySDK($config);


		// Step 1: Get fulfillment orders
		$getFulfillmentOrdersQuery = '
			query {
				order(id: "gid://shopify/Order/' . $order_id . '") {
					fulfillmentOrders(first: 100) {
						nodes {
							id
							status
							lineItems(first: 100) {
								nodes {
									id
									remainingQuantity
								}
							}
						}
					}
				}
			}';

		// Send the request
		$response = $shopify->GraphQL->post($getFulfillmentOrdersQuery);
		$fulfillmentOrders_errors = $response['data']['appUsageRecordCreate']['userErrors'] ?? [];

		// echo "<pre>";
		// print_r($response);
		// echo "</pre>";
		// die;

		if (!empty($fulfillmentOrders_errors)) {

			// print_r($fulfillmentOrders_errors);
			// $db->prepare("UPDATE contract_cron SET status = 'pending' WHERE id = ?")->execute([$row['id']]);
			continue;
		} else {

			$fulfillmentOrders = $response['data']['order']['fulfillmentOrders']['nodes'] ?? [];

			foreach ($fulfillmentOrders as $fo) {
				if ($fo['status'] == "OPEN") {
					
					$foLineItems = [];
					foreach ($fo['lineItems']['nodes'] as $li) {
						if ($li['remainingQuantity'] > 0) {
							$foLineItems[] = [
								"id"       => $li['id'],
								"quantity" => $li['remainingQuantity']
							];
						}
					}

					if (empty($foLineItems)) {
						echo "No line items to fulfill for fulfillment order: " . $fo['id'] . "\n";
						// Mark as done
						continue;
					}


					// Step 2: Create fulfillment
					$fulfillMutation = <<<GRAPHQL
					mutation fulfillOrder(\$input: FulfillmentV2Input!) {
					fulfillmentCreateV2(fulfillment: \$input) {
						fulfillment {
						id
						status
						createdAt
						}
						userErrors {
						field
						message
						}
					}
					}
					GRAPHQL;

					$variables = [
						"input" => [
							"notifyCustomer" => false,
							"trackingInfo"   => null,
							"lineItemsByFulfillmentOrder" => [
								[
									"fulfillmentOrderId"        => $fo['id'],
									"fulfillmentOrderLineItems" => $foLineItems
								]
							]
						]
					];


					$result = $shopify->GraphQL->post($fulfillMutation, null, null, $variables);

					if (!empty($result['data']['fulfillmentCreateV2']['userErrors'])) {
						// echo "Errors creating fulfillment:\n";
						// print_r($result['data']['fulfillmentCreateV2']['userErrors']);
						
					} else {
						echo "Fulfillment created: " . $result['data']['fulfillmentCreateV2']['fulfillment']['id'] . "\n";
						$stmt = $db->prepare("UPDATE contract_cron SET fulfill_status = '1' WHERE order_id = ? AND store = ?");
                        $result = $stmt->execute([$order_id, $store]);

					}
                    echo 'here';
					die;

				}else{
					echo "Fulfillment order not open: " . $fo['id'] . " Status: " . $fo['status'] . "\n";
					$stmt = $db->prepare("UPDATE contract_cron SET fulfill_status = '1' WHERE order_id = ? AND store = ?");
                    $result = $stmt->execute([$order_id, $store]);

					// continue;
				}


			}
		}
	}

	$offset += $limit;
	sleep(1); // Prevent Shopify rate limit
}
