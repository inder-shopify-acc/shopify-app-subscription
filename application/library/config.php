<?php


$dirPath = dirname(dirname(__DIR__));
require_once($dirPath . "/env.php");


$MYSQL_HOST = getDotEnv('MYSQL_HOST');

$MYSQL_DB = getDotEnv('MYSQL_DB');

$MYSQL_USER =  getDotEnv('MYSQL_USER');

$MYSQL_PASS =  getDotEnv('MYSQL_PASS');

$SHOPIFY_APIKEY = getDotEnv('SHOPIFY_APIKEY');

$SHOPIFY_SECRET =  getDotEnv('SHOPIFY_SECRET');

$API_SECRET_KEY = getDotEnv('SHOPIFY_SECRET');

$SHOPIFY_API_VERSION = "2025-04";

$SHOPIFY_DOMAIN_URL = getDotEnv('SHOPIFY_DOMAIN_URL');

$SHOPIFY_DIRECTORY_NAME = "";

$SHOPIFY_REDIRECT_URI = getDotEnv('SHOPIFY_DOMAIN_URL') . "/admin/memberPlans.php?billingStatus=unattempted";

$SHOPIFY_SCOPES = "read_translations,
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

$image_folder = getDotEnv('SHOPIFY_DOMAIN_URL') . "/application/assets/images/";

$app_extension_id = getDotEnv('APP_EXTENSION_ID');

$theme_block_name = getDotEnv('THEME_BLOCK_NAME');

$app_name = getDotEnv('APP_NAME');

try {
    // echo "mysql:host=$MYSQL_HOST;dbname=$MYSQL_DB", $MYSQL_USER, $MYSQL_PASS;
    $db = new PDO("mysql:host=$MYSQL_HOST;dbname=$MYSQL_DB", $MYSQL_USER, $MYSQL_PASS, array(PDO::ATTR_PERSISTENT => true));

    // echo "Connected to $MYSQL_DB at $MYSQL_HOST successfully.";

} catch (PDOException $pe) {

    file_put_contents($dirPath . "/application/assets/txt/webhooks/config.txt", "\n\n DB not connected. " . $pe->getMessage(), FILE_APPEND | LOCK_EX);

    // die("Could not connect to the database $MYSQL_DB :" . $pe->getMessage());

    die("Could not connect to the database :");
}
