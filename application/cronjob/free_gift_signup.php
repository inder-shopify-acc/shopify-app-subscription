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
try {
    $currentDate = date('Y-m-d');

    $sql = "SELECT subscritionOrderContractProductDetails.*, customers.name,  customers.email,  customers.shopify_customer_id
                    FROM subscritionOrderContractProductDetails
                    JOIN subscriptionOrderContract 
                        ON subscriptionOrderContract.contract_id = subscritionOrderContractProductDetails.contract_id
                    JOIN customers  
                        ON subscriptionOrderContract.shopify_customer_id = customers.shopify_customer_id
                    WHERE DATE(subscritionOrderContractProductDetails.free_gift_date) <= :currentDate
                    AND subscritionOrderContractProductDetails.free_gift_status = 'pending'
                    AND subscritionOrderContractProductDetails.perk_free_gift_product_id  != ''
                    AND subscritionOrderContractProductDetails.plan_type = 'membership'
                    LIMIT 10";

    $stmt = $db->prepare($sql);
    $stmt->execute(['currentDate' => $currentDate]);

    $free_gift_customers = $stmt->fetchAll(PDO::FETCH_OBJ);
    // print_r($free_gift_customers);
    // echo "<pre>";
    foreach ($free_gift_customers as $key => $value) {

        // print_r($value);

        $variant_id = $value->variant_id;

        $store_id = $value->store_id;
        $customerId = $value->shopify_customer_id;
        $customer_name = $value->name;
        $customerEmail = $value->email;
        $contract_id = $value->contract_id;
        // $customerEmail = 'inderjit17000@gmail.com';
        $Free_gift_uponsignup_productName = $value->Free_gift_uponsignup_productName;
        // echo $Free_gift_uponsignup_productName.'<br>';
        $variantFreeGiftId = $value->perk_free_gift_variant_id;
        $store_details_sql = "SELECT store_details.*, install.access_token
                    FROM store_details
                    JOIN install 
                        ON store_details.store_id = install.id
                    
                    WHERE install.id = :store_id LIMIT 1";
        $store_data_query = $db->prepare($store_details_sql);
        $store_data_query->execute(['store_id' => $store_id]);
        $store_details_data = $store_data_query->fetch(PDO::FETCH_OBJ);
        $store = $store_details_data->shop;
        $shop_name = $store_details_data->shop_name;
        $shop_timezone = $store_details_data->shop_timezone;
        $access_token = $store_details_data->access_token;
        $config = array(
            'ShopUrl' => $store,
            'AccessToken' => $access_token,
        );
        // print_r($config);die;
        $shopify = new ShopifySDK($config);
        $input = [
            "input" => [
                "customerId" => "gid://shopify/Customer/" . $customerId . "",
                "email" => $customerEmail,
                "note" => "Test draft order",
                "lineItems" => [
                    [
                        "title" => $Free_gift_uponsignup_productName,
                        "quantity" => 1,
                        "originalUnitPrice" => 0,
                        "variantId" => $variantFreeGiftId,
                        "appliedDiscount" => [
                            "description" => "Hurray! you get the free gift 🎁",
                            "value" => 100,
                            "amount" => 0,
                            "valueType" => "PERCENTAGE",
                        ],
                    ],
                ],
            ],
        ];

        $createDraftOrder = 'mutation createDraftOrder($input: DraftOrderInput!) {
    
                                        draftOrderCreate(input: $input) {
    
                                            draftOrder {
    
                                            id
    
                                            invoiceUrl
    
    
    
                                            }
    
                                            userErrors {
    
                                            field
    
                                            message
    
                                            }
    
                                        }
    
                                    }';

        try {

            $draftData = $shopify->GraphQL->post($createDraftOrder, null, null, $input);
            // print_r($draftData);
            if ($draftData) {

                $sql = "UPDATE subscritionOrderContractProductDetails 
                                SET free_gift_status = 'completed' 
                                WHERE variant_id = :variant_id AND contract_id = :contract_id"
                                ;

                $stmt = $db->prepare($sql);
                $updateStatus = $stmt->execute(['variant_id' => $variant_id, 'contract_id' => $contract_id]);


                if ($updateStatus) {

                    $template_data = [];

                    $template_data['template_name'] = 'membership_free_gifts';

                    $template_data['shop_name'] = $shop_name;

                    $template_data['discount_coupon_content'] = true;

                    $template_data['free_shipping_coupon_content'] = true;

                    $template_data['free_signup_product_content'] = true;

                    $template_data['free_gift_uponsignupSelectedDays'] = true;
                    $template_data['store'] = $store;
                    $template_data['db'] = $db;

                    try {

                        $email_template = membershipAllEmailTemplates($template_data);
                        // print_r($email_template);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    $draftInvoice = $draftData['data']['draftOrderCreate']['draftOrder']['invoiceUrl'];

                    echo '$draftInvoice' . $draftInvoice;

                    $subject = $email_template['subject'];

                    $bodyData = $email_template['email_template_html'];

                    $body = str_replace(array('{{customer_name}}', '{{free_signup_product}}', '{{immediate_sign_up_produt}}', '{{store_name}}'), array($customer_name, $Free_gift_uponsignup_productName, $draftInvoice, $store), $bodyData);

                    $ccc_email = '';

                    $bcc_email = '';

                    $reply_to = '';
                    $data = array(

                        'email_template_array' =>
                        array(
                            'subject' => $subject,
                            'send_to_email' => $customerEmail,
                            'template_name' => 'membership_free_gifts',
                            'emailBody' => $body,
                            'reply_to' => $reply_to,
                            'bcc_email' => $bcc_email,
                            'ccc_email' => $ccc_email,
                            'store_id' => $store_id,
                            'db' => $db
                        )

                    );
                    print_r($data);
                    // echo "yes";
                    emailSend($data);
                }
            }
        } catch (Exception $e) {

            echo $e->getMessage();
        }
    }




    // echo "<pre>";

    // print_r($free_gift_customers);

    // die;

} catch (Exception $e) {  // Specify the exception type you expect to catch, e.g., Exception

    echo $e->getMessage();  // Output the error message

}








function membershipAllEmailTemplates($template_data)
{
    echo "hre enter";
    //     print_r($template_data);die;
    $store_name = $template_data['shop_name'];
    $template_name = $template_data['template_name'];

    // $show_template_for = $template_data['email_type'];
    $sql = "SELECT * FROM `$template_name` WHERE `store` = :store LIMIT 1";
    $db = $template_data['db'];
    $stmt = $db->prepare($sql);
    $stmt->execute(['store' => $template_data['store']]);

    $template_setting = $stmt->fetch(PDO::FETCH_OBJ);
    // print_r($template_setting);
    // die;
    $custom_email_html = isset($template_setting->custom_email_html) ? $template_setting->custom_email_html : '';
    if (empty((array) $template_setting)) {

        $ccc_email = '';

        $bcc_email = '';

        $reply_to = '';

        $content_heading = '';

        $logo = $template_data['image_folder'] . "logo.png";



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





            case "membership_free_gifts":

                $subject = 'You get the free gift"';

                $heading_text = 'Hurray! you get the free gift';

                $footer_text = 'Thank you';

                $manage_button = 'View Account';

                $gift_link_text = 'Redeem now';

                $content_heading = '<p class="" >Hey <b> {{customer_name}} </b>, <br><br>We are so excited to have you as a member ! As a thank-you for signing up, we are giving you a free gift. Your gift is a <b> {{free_signup_product}} </b>. We think you will love it!. We hope you enjoy your gift. We are always adding new products and services, so be sure to check back often.<br><br> To claim your gift, simply click on the link below:</p>';

                $content_heading .= '<br><b><i><a href =" {{immediate_sign_up_produt}} " class="sd_gift_link_text_view">' . $gift_link_text . '</a></i></b>';

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
    }

    if ($manage_button_url == '') {

        $manage_button_url = 'https://' . $store_name . '/account';
    }

    if ($logo == '') {

        $logo = $template_data['image_folder'] . 'logo.png';
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
function emailSend($data)
{
    try {

        // echo "final";
        // print_r($data);
        $store_id = $data['email_template_array']['store_id'];
        $ccc_email = $data['email_template_array']['ccc_email'];
        $bcc_email = $data['email_template_array']['bcc_email'];
        $reply_to = $data['email_template_array']['reply_to'];
        $mail =  new PHPMailer\PHPMailer\PHPMailer();
        $db = $data['email_template_array']['db'];
        try {

            $result = $db->query("SELECT * FROM email_configuration WHERE store_id = '$store_id' LIMIT 1");
            $emailConfiguration = $result->fetch(PDO::FETCH_OBJ);
            print_r($emailConfiguration);
        }catch(Exception $e) {
            echo $e->getMessage();
        }


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
            

            $emailHost =  "smtp.gmail.com";
            $emailUsername = "shopify@phoenixtechnologies.io";
            $emailPassword = "gqumwotkgxekdowf";
            $senderName = "shopify@phoenixtechnologies.io";
            $emailEncriptionType = 'tls';
            $emailPortNumber = 587;
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
        echo "khurana residence chandigarh UT LOCAL";
        if (!$mail->send()) {
            echo 'Mail not sent. Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Mail sent successfully';
        }
        

    } catch (Exception $e) {
        return false;
    }
}
