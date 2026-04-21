<?php

/*

This file contains all global variables & db connection & API Library Connection

*/

/* ==============  Debug Mode ==============*/

ob_start();

set_time_limit(0);

// ini_set("display_errors", 1);

// error_reporting(E_ALL);



/* ==============  Include GraphQL and Shopify Curl support file to run API's ==============*/

$dirPath = dirname(dirname(__DIR__));

require_once($dirPath . "/application/library/shopify.php");

require_once($dirPath . "/graphLoad/autoload.php");

require_once($dirPath . "/env.php");


if (isset($_GET['shop'])) {

	$store = $_GET['shop'];
}



class Keys
{

	/* ==============  App Standardize Variables Declaration ==============*/

	public $MYSQL_HOST, $MYSQL_DB, $MYSQL_USER, $MYSQL_PASS, $SHOPIFY_APIKEY, $SHOPIFY_SECRET, $SHOPIFY_SCOPES, $SHOPIFY_DIRECTORY_NAME, $SHOPIFY_API_VERSION, $SHOPIFY_DOMAIN_URL, $SHOPIFY_REDIRECT_URI, $db;



	public function __construct()
	{

		$this->TESTING_STORES = array('demo-subscription-app-testing.myshopify.com', 'dev-subscription.myshopify.com', 'dev-nisha-subscription.myshopify.com', 'testing-neha-subscription.myshopify.com', 'testing-advanced-wholesale-pro.myshopify.com', 'test-store-phoenixt.myshopify.com', 'testingphoenixsub.myshopify.com', 'test-store-phoenixt.myshopify.com', 'pheonix-store-local.myshopify.com', 'pheonix-store-local-2.myshopify.com', 'pheonix-appstore.myshopify.com', 'thediyart1.myshopify.com');

		$this->MYSQL_HOST = getDotEnv('MYSQL_HOST');

		$this->MYSQL_DB = getDotEnv('MYSQL_DB');

		$this->MYSQL_USER =  getDotEnv('MYSQL_USER');

		$this->MYSQL_PASS =  getDotEnv('MYSQL_PASS');

		$this->SHOPIFY_APIKEY = getDotEnv('SHOPIFY_APIKEY');

		$this->SHOPIFY_SECRET =  getDotEnv('SHOPIFY_SECRET');

		// $this->SHOPIFY_SCOPES = "read_content,write_content,read_themes,write_themes,read_products,write_products,read_customers,write_customers,read_orders,write_orders,read_own_subscription_contracts,write_own_subscription_contracts,read_customer_payment_methods,read_shopify_payments_accounts,read_translations,write_translations,read_merchant_managed_fulfillment_orders,write_merchant_managed_fulfillment_orders,write_third_party_fulfillment_orders";

		$this->SHOPIFY_SCOPES = "read_translations,
read_themes,
write_products,
write_customers,
write_orders,
write_discounts,
write_draft_orders,
write_own_subscription_contracts,
read_customer_payment_methods,
read_shopify_payments_accounts,
write_merchant_managed_fulfillment_orders,
write_third_party_fulfillment_orders,
write_price_rules,
write_content,
write_metaobjects,
write_metaobject_definitions,
unauthenticated_read_metaobjects,
read_all_orders,
write_publications,
write_locations,
read_returns,
read_products,
read_customers,
read_orders,
read_discounts,
read_draft_orders,
read_own_subscription_contracts,
read_merchant_managed_fulfillment_orders,
read_third_party_fulfillment_orders,
read_price_rules,
read_content,
read_metaobjects,
read_metaobject_definitions,
read_publications,
read_locations";

		$this->SHOPIFY_API_VERSION = "2025-04";

		$this->SHOPIFY_DIRECTORY_NAME = "";

		$this->SHOPIFY_DOMAIN_URL = getDotEnv('SHOPIFY_DOMAIN_URL');

		$this->SHOPIFY_REDIRECT_URI = getDotEnv('SHOPIFY_DOMAIN_URL') . "/admin/memberPlans.php?billingStatus=unattempted";

		$this->image_folder = getDotEnv('SHOPIFY_DOMAIN_URL') . "/application/assets/images/";

		$this->db = $this->connection();

		$this->created_at = date('Y-m-d H:i:s');

		/* ==============  App Error Variables Declaration  ==============*/

		$this->installtion_error =	"Something went wrong in Installation Process ,Please try installing again or contact Us at <a href = 'mailto: Support@phoenixtechnologies.io '>Support@phoenixtechnologies.io </a>";

		$this->app_extension_id = getDotEnv('APP_EXTENSION_ID');

		$this->theme_block_name = getDotEnv('THEME_BLOCK_NAME');

		$this->app_name = getDotEnv('APP_NAME');

		$this->theme_assets_fields = array("store_id", "asset_name", "asset_id");

		$this->subscritionOrderContractProductDetails_fields = array("store_id", "contract_id", "product_id", "variant_id", "product_name", "product_handle", "variant_name", "variant_image", 'recurring_computed_price', "quantity", "subscription_price", "contract_line_item_id", "created_at");

		$this->contract_sale_fields = array("store_id", "contract_id", "total_sale", "created_at");

		$this->subscriptionOrderContract_fields = array("store_id", "contract_id", "contract_products", "order_id", "order_no", "shopify_customer_id", "billing_policy_value", "delivery_policy_value", "delivery_billing_type", 'min_cycle', 'max_cycle', 'anchor_day', 'cut_off_days', 'after_cycle', "selling_plan_id", "discount_type", "discount_value", 'recurring_discount_type', 'recurring_discount_value', "next_billing_date", "firstRenewal_dateTime", "created_at", "updated_at");

		$this->customers_fields = array("store_id", "shopify_customer_id", "email", "name", "created_at");

		$this->storeInstallOffers_fields = array("", "", "");

		$this->theme_assets_values = array();

		$this->subscritionOrderContractProductDetails_values = array();

		$this->subscriptionOrderContract_values = array();

		$this->contract_sale_values = array();

		$this->customers_values = array();

		$this->input_max_character = 50;
	}



	/* ==============  Database Connection ==============*/

	public function connection()
	{

		try {

			$result = new PDO("mysql:host=$this->MYSQL_HOST;dbname=$this->MYSQL_DB", $this->MYSQL_USER, $this->MYSQL_PASS);
		} catch (PDOException $pe) {

			die("Could not connect to the database $MYSQL_DB :" . $pe->getMessage());
		}

		return $result;
	}

	public function customQuery($query)
	{

		$result = $this->db->query($query);

		$row_data = $result->fetchAll(PDO::FETCH_ASSOC);

		// $this->db=null; //close db connection

		return $row_data;	// return associative array

	}

	public function __destruct()

	{

		$this->db = null;
	}
}//Class End
