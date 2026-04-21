<?php
require 'vendor/autoload.php';

require_once 'env.php';

header("Content-Type: application/javascript");

$shopifyDomain = getDotEnv('SHOPIFY_DOMAIN_URL');
$apiKey = getDotEnv('SHOPIFY_APIKEY');

$tickIconUrl = $shopifyDomain . "/application/assets/images/TickMinor.svg";

echo "const ENV = {\n";
echo "    SHOPIFY_DOMAIN_URL: '" . $shopifyDomain . "',\n";
echo "    API_KEY: '" . $apiKey . "'\n";
echo "};\n";

echo "document.documentElement.style.setProperty('--shopify-domain', '" . $shopifyDomain . "');\n";
echo "document.documentElement.style.setProperty('--api-key', '" . $apiKey . "');\n";
echo "document.documentElement.style.setProperty('--tick-icon-url', 'url(\"" . $tickIconUrl . "\")');\n";
