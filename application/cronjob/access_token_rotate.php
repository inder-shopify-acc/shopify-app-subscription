<?php
// error_reporting(0);
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);

$dirPath = dirname(dirname(__DIR__));

use PHPShopify\ShopifySDK;

require($dirPath . "/PHPMailer/src/PHPMailer.php");

require($dirPath . "/PHPMailer/src/SMTP.php");

require($dirPath . "/PHPMailer/src/Exception.php");

include $dirPath . "/graphLoad/autoload.php";

include $dirPath . "/application/library/config.php";

$sql = "SELECT * FROM install WHERE token_update = '0' LIMIT 3";

$stmt = $db->prepare($sql);
$stmt->execute();

$installData = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($installData);
// if ($installData['store'] == 'pheonix-appstore.myshopify.com') {

// die("end here");
$api_key = "d709a55a306371c33e8afea7330f46ad";
$shared_secret = "7b3cb843874a3352c2ae728150ae56d6";
$refresh_token = "859b67245ed358800752a2dbdd86c7ef-1760676280";
if (!empty($installData)) {
    foreach ($installData as $key => $value) {
        // if( $value['store'] == 'pheonix-appstore.myshopify.com') {
            $access_token = $value["access_token"];

            $post_data = array(
                "client_id" => $api_key,
                "client_secret" => $shared_secret,
                "refresh_token" => $refresh_token,
                "access_token" => $access_token,
            );
            $data_string = json_encode($post_data);

            $headers = array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Content-Length:" . strlen($data_string)
            );

            $handler = curl_init('https://' . $value['store'] . '/admin/oauth/access_token.json');
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handler, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handler, CURLOPT_HTTPHEADER, $headers);

            // The response includes the updated access token for future requests.
            $response = json_decode(curl_exec($handler));
            print_r($response);
            $access_token = $response->access_token;

            file_put_contents($dirPath . "/application/assets/txt/webhooks/token_update.txt", "{store: " . $value['store'] . ", update_token: " . $access_token . "}", FILE_APPEND);
            $db->query("UPDATE install SET access_token = '" . $access_token . "', token_update = '1' WHERE store = '" . $value['store'] . "'");
        // }else {
        //         echo "store not found";
        //     }
    }
}else {
    echo "another store";
}
// } else {
//     echo "No data found";
// }
// echo "store found";
// } else {
//     echo "store not found";
// }
