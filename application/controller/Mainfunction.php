<?php

/* Backend Module

This file contains all DB functions & ajax handling functions

*/
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);
// error_reporting(0);

use PHPShopify\ShopifySDK;

require_once(dirname((dirname(dirname(__file__)))) . "/application/library/commonmodal.php");



class MainFunction extends CommonModal
{

	public $current_recurring_billing_id = "";

	public $weekDaysArray = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

	public function __construct($store)
	{

		parent::__construct($store);
	}



	public function send_test_email($data)
	{

		$sendMailTo = $data['send_to_email'];

		$email_template_data = $this->email_template($data, 'send_test_email');

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

				return $contract_deleted_mail;
			} catch (Exception $e) {

				return $contract_deleted_mail;
			}
		} else {

			echo json_encode(array("status" => 'false', 'message' => 'Enable default/custom template to send email.'));
		}
	}



	public function update_delivery_billing_frequency($data)
	{

		$contract_id = $data['wherecondition']['contract_id'];

		$delivery_billing_frequencies = $data['data_values'];

		$line_item_id = $delivery_billing_frequencies['line_item_id'];

		$selling_plan_name = 'Every ' . $delivery_billing_frequencies['delivery_billing_frequency'] . ' ' . strtolower($delivery_billing_frequencies['delivery_billing_type']);

		$contract_draft_id = $this->getContractDraftId($contract_id);

		$update_frequencies = $this->update_deliveryBilling_frequency($contract_draft_id, $delivery_billing_frequencies);

		$update_selling_planName = $this->update_selling_planName($contract_draft_id, $line_item_id, $selling_plan_name);

		if ($update_frequencies) {

			$this->commitContractDraft($contract_draft_id);

			$fields = array(

				'billing_policy_value' => $data['data_values']['delivery_billing_frequency'],

				'delivery_policy_value' => $data['data_values']['delivery_billing_frequency'],

				'delivery_billing_type' => strtolower($data['data_values']['delivery_billing_type'])

			);

			$updateInstallData = $this->update_row('subscriptionOrderContract', $fields, $data['wherecondition'], 'and');

			echo $updateInstallData;
		}
	}



	public function save_invoice_settings($invoice_data)
	{

		if (!empty($_FILES["logo"]["name"]) && isset($_FILES["logo"]["name"])) {

			$s_logo = $_FILES["logo"]["name"];

			$logotemp_name =  $_FILES["logo"]["tmp_name"];

			$logoStatus = 'true';
		} else {

			$logoStatus = 'false';
		}



		if (isset($_FILES["signature_img"]["name"]) && (!empty($_FILES["signature_img"]["name"]))) {

			$s_signature = $_FILES["signature_img"]["name"];

			$signtemp_name =  $_FILES["signature_img"]["tmp_name"];

			$signStatus = 'true';
		} else {

			$signStatus = 'false';
		}

		$target_dir = dirname((dirname(dirname(__file__)))) . "/application/assets/images/invoice/";



		//==================upload logo image=========================//

		if ($logoStatus == 'true') {



			$target_file = $target_dir . basename($s_logo);

			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

			$mimetype = mime_content_type($logotemp_name);

			// die;

			if (in_array($mimetype, array('image/jpeg', 'image/gif', 'image/png', 'image/jpg'))) {

				$s_logo = md5($s_logo) . '.' . $imageFileType;

				$uploadlogo = $target_dir . "logo/" . $s_logo;

				if (move_uploaded_file($logotemp_name, $uploadlogo)) {

					$invoice_data['logo'] = $s_logo;
				} else {

					echo json_encode(array("status" => 'false', 'message' => "Sorry, there was an error uploading your logo file."));

					exit;
				}
			} else if (($_FILES["imglogo"]["size"] > 2000000)) {

				echo json_encode(array("status" => 'false', 'message' => 'Logo File size exceed 2MB'));

				exit;
			} else {

				echo json_encode(array("status" => 'false', 'message' => 'Sorry,File not uploaded, may be file too large and only JPG, JPEG, PNG & GIF files are allowed for logo.'));

				exit;
			}
		}



		if ($signStatus == 'true') {

			$target_file = $target_dir . basename($s_signature);

			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

			$mimetype1 = mime_content_type($signtemp_name);

			if (in_array($mimetype1, array('image/jpeg', 'image/gif', 'image/png', 'image/jpg'))) {

				$s_signature = md5($s_signature) . '.' . $imageFileType;

				$uploadsign = $target_dir . "signature/" . $s_signature;

				if (move_uploaded_file($signtemp_name, $uploadsign)) {

					$invoice_data['signature'] = $s_signature;
				} else {

					echo json_encode(array("status" => 'false', 'message' => "Sorry, there was an error uploading your signature file."));

					exit;
				}
			} else if (($_FILES["imgsign"]["size"] > 2000000)) {

				echo json_encode(array("status" => 'false', 'message' => 'Signature File size exceed 2MB'));
			} else {

				echo json_encode(array("status" => 'false', 'message' => 'Sorry,File not uploaded, may be file too large and only JPG, JPEG, PNG & GIF files are allowed for signature.'));

				exit;
			}
		}

		$whereStoreDetailCondition = array(

			'store_id' => $this->store_id

		);

		$columnsToRemove = ['store', 'action'];

		foreach ($columnsToRemove as $column) {

			unset($invoice_data[$column]);
		}



		//delete previous logo and signature

		$logo_sign_fields = array('logo', 'signature');

		$logo_sign_data = $this->single_row_value('invoice_template_settings', $logo_sign_fields, $whereStoreDetailCondition, 'and', '');

		if ($logo_sign_data) {

			$logo = $logo_sign_data['logo'];

			$signature = $logo_sign_data['signature'];

			if ($logoStatus == 'true') {

				$logofilePath = $target_dir . '/logo/' . $logo; // Replace with the actual file path

				if (file_exists($logofilePath)) {

					unlink($logofilePath);
				}
			}

			if ($signStatus == 'true') {

				$signfilepath = $target_dir . '/signature/' . $signature;

				if (file_exists($signfilepath)) {

					unlink($signfilepath);
				}
			}
		}

		$save_invoice_data = $this->insertupdateajax('invoice_template_settings', $invoice_data, $whereStoreDetailCondition, 'and');

		echo ($save_invoice_data);
	}



	public function email_template($data, $show_template_for)
	{

		$shop_domain = $this->store;

		$deleted_product = ' ';

		$new_added_products = ' ';

		$updated_product = ' ';

		$skipped_order_date = ' ';

		$actual_fulfillment_date = '';

		$new_scheduled_date = '';

		$new_shipping_price = ' ';

		$new_renewal_date = '';

		$selling_plan_name = 'Delivery Every Day';

		$order_number = '#1129';

		$next_order_date = date('Y-m-d H:i:s');

		$product_quantity = '1';
		$product_name = 'Sprouts';
		$variant_name = 'salad';
		$delivery_cycle = '2';
		$billing_cycle = '2';
		$thanks_img = '';
		$logo = '';

		$product_image_url = $this->SHOPIFY_DOMAIN_URL . '/application/assets/images/sprouts-salad.jpg';

		$whereCondition = array(

			"store_id" => $this->store_id,

		);

		$template_name = $data['template_type'];

		$template_heading = ucwords(str_replace(array('_', 'status'), array(' ', ''), $template_name));

		$fields = array('shop_name', 'store_email');

		$store_details_data = $this->single_row_value('store_details', $fields, $whereCondition, 'and', '');

		$shop_name = $store_details_data['shop_name'];

		$shop_email = $store_details_data['store_email'];



		//set default value for the email template fields

		$customer_name = 'Honey Bailey';

		$customer_email = 'honey@gmail.com';

		$customer_id = '6578937358';

		$subscription_contract_id = '#757899098';

		$shipping_full_name = 'Honey Bailey';

		$shipping_company = 'Company name';

		$shipping_address1 = '215 Bell St';

		$shipping_address2 = '215 Bell St2';

		$shipping_city = 'Melbourne';

		$shipping_province = 'Victoria';

		$shipping_country = 'Australia';

		$shipping_phone = '(03) 9485 0100';

		$shipping_province_code = 'AUS';

		$shipping_country_code = '61';

		$shipping_zip = '3071';



		$billing_full_name = 'Honey Bailey';

		$billing_company = 'Company name';

		$billing_address1 = '215 Bell St';

		$billing_address2 = '215 Bell St2';

		$billing_city = 'Melbourne';

		$billing_province = 'Victoria';

		$billing_country = 'Australia';

		$billing_phone = '(03) 9485 0100';

		$billing_province_code = 'AUS';

		$billing_country_code = '61';

		$billing_zip = '3071';



		$delivery_method = 'Standard';

		$delivery_price = '100';

		$payment_method_token = '';

		$card_expire_month = 'March'; // March

		$card_expire_year = '2024';

		$last_four_digits = '4242';

		$card_brand = 'Visa';



		if ($show_template_for == 'send_dynamic_email') {

			$province_code = '';

			$country_code = '';
      // echo '<pre>';
			// print_r($data);
			// die;
			if (isset($data['shipping_data']['province_code'])) {
				$province_code = $data['shipping_data']['province_code'];
			}

			if (isset($data['shipping_data']['country_code'])) {
				$country_code = $data['shipping_data']['country_code'];
			}

			$get_customer_data = $data['contract_details'] ?? [];

			$customer_name = $get_customer_data['name'] ?? '';

			$customer_email = $get_customer_data['email'] ?? '';

			$customer_id = $get_customer_data['shopify_customer_id'] ?? '';

			$subscription_contract_id = '#' . $data['contract_id'];

			if ($template_name == 'shipping_address_update_template') {

				$shipping_full_name = $data['shipping_data']['first_name'] . ' ' . $data['shipping_data']['last_name'];

				$shipping_company = $data['shipping_data']['company'];

				$shipping_address1 = $data['shipping_data']['address1'];

				$shipping_address2 = $data['shipping_data']['address2'];

				$shipping_city = $data['shipping_data']['city'];

				$shipping_province = $data['shipping_data']['province'];

				$shipping_country = $data['shipping_data']['country'];

				$shipping_phone = $data['shipping_data']['phone'];

				$shipping_province_code = $province_code;

				$shipping_country_code = $country_code;

				$shipping_zip = $data['shipping_data']['zip'];
			} else {

				$shipping_full_name =
				($get_customer_data['shipping_first_name'] ?? '') . ' ' .
				($get_customer_data['shipping_last_name'] ?? '');

				$shipping_company = $get_customer_data['shipping_company'] ?? '';

				$shipping_address1 = $get_customer_data['shipping_address1'] ?? '';

				$shipping_address2 = $get_customer_data['shipping_address2'] ?? '';

				$shipping_city = $get_customer_data['shipping_city'] ?? '';

				$shipping_province = $get_customer_data['shipping_province'] ?? '';

				$shipping_country = $get_customer_data['shipping_country'] ?? '';

				$shipping_phone = $get_customer_data['shipping_phone'] ?? '';

				$shipping_province_code = $get_customer_data['shipping_province_code'] ?? '';

				$shipping_country_code = $get_customer_data['shipping_country_code'] ?? '';

				$shipping_zip = $get_customer_data['shipping_zip'] ?? '';
			}

	    $order_number = '#' . ($get_customer_data['order_no'] ?? '');

		  $billing_full_name =
				($get_customer_data['billing_first_name'] ?? '') . ' ' .
				($get_customer_data['billing_last_name'] ?? '');

			$billing_company = $get_customer_data['billing_company'] ?? '';

			$billing_address1 = $get_customer_data['billing_address1'] ?? '';

			$billing_address2 = $get_customer_data['billing_address2'] ?? '';

			$billing_city = $get_customer_data['billing_city'] ?? '';

			$billing_province = $get_customer_data['billing_province'] ?? '';

			$billing_country = $get_customer_data['billing_country'] ?? '';

			$billing_phone = $get_customer_data['billing_phone'] ?? '';

			$billing_province_code = $get_customer_data['billing_province_code'] ?? '';

			$billing_country_code = $get_customer_data['billing_country_code'] ?? '';

			$billing_zip = $get_customer_data['billing_zip'] ?? '';



			$delivery_method = $get_customer_data['shipping_delivery_method'] ?? '';

			$delivery_price = $get_customer_data['shipping_delivery_price'] ?? '';

			$payment_method_token = $get_customer_data['payment_method_token'] ?? '';

			$payment_json = $get_customer_data['payment_instrument_value'] ?? '';

      $payment_instrument_value = !empty($payment_json) ? json_decode($payment_json) : null;

				if ($payment_instrument_value && isset($payment_instrument_value->month)) {
						$dateObj = DateTime::createFromFormat('!m', $payment_instrument_value->month);
						$card_expire_month = $dateObj ? $dateObj->format('F') : '';
				} else {
						$card_expire_month = '';
				}

				$card_expire_year    = $payment_instrument_value->year ?? '';
				$last_four_digits    = $payment_instrument_value->last_digits ?? '';
				$card_brand          = $payment_instrument_value->brand ?? '';
		}

		$template_data = $this->single_row_value($template_name, 'all', $whereCondition, 'and', '');

		if ($template_name == 'subscription_purchase_template') {

			$content_text = '<h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Hi {{customer_name}}</h2><h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Thank you for your purchase!</h2> <p style="line-height:150%;font-size:16px;margin:0">We are getting your order ready to be shipped. We will notify you when it has been sent.</p>';
		} else {

			switch ($template_name) {

				case "subscription_status_cancelled_template":

					$content_heading = 'Subscription with id {{subscription_contract_id}} has been cancelled';

					break;

				case "subscription_status_resumed_template":

					$content_heading = 'Subscription with id {{subscription_contract_id}} has been resumed';

					break;

				case "subscription_status_paused_template":

					$content_heading = 'Subscription with id {{subscription_contract_id}} has been paused';

					break;

				case "product_added_template":

					$content_heading = 'Product(s) {{new_added_products}} has been added in the Subscription with id {{subscription_contract_id}}';

					break;

				case "product_removed_template":

					$content_heading = 'Product {{removed_product}} has been removed from the Subscription with id {{subscription_contract_id}}';

					if ($show_template_for == 'send_dynamic_email') {

						$deleted_product_line_id = $data['deleted_product_line_id'];

						$filteredArray = array_filter($data['contract_product_details'], function ($item) use ($deleted_product_line_id) {

							return $item['contract_line_item_id'] === $deleted_product_line_id;
						});

						$targetItem = array_shift($filteredArray);

						if ($targetItem) {

							$deleted_product = $targetItem['product_name'] . ' ' . $targetItem['variant_name'];
						}
					}

					break;

				case "product_updated_template":

					$content_heading = 'Subscription product {{updated_product}} has been updated in the subscription with id {{subscription_contract_id}}';

					if ($show_template_for == 'send_dynamic_email') {

						$updated_product = $data['updated_product']['product_title'];
					}

					break;

				case "skip_order_template":

					$content_heading = 'Subscription order of the date {{skipped_order_date}} has been skipped in the subscription with id {{subscription_contract_id}}';

					if ($show_template_for == 'send_dynamic_email') {

						$skipped_order_date = date('d M Y', strtotime($data['skipped_order_date']));
					}

					break;

				case "billing_attempted_template":

					$content_heading = 'Thanks for your order {{order_number}}!. We’ll get it to your doorstep as soon as possible! You will get a shipping notification once your order has left our shop and is on the way to you! ';

					break;

				case "reschedule_fulfillment_template":

					$content_heading = 'Subscription with id {{subscription_contract_id}} has been rescheduled the fulfillment of the date {{actual_fulfillment_date}} to the {{new_scheduled_date}}';

					if ($show_template_for == 'send_dynamic_email') {

						$actual_fulfillment_date = $data['actual_fulfillment_date'];

						$new_scheduled_date = $data['new_scheduled_date'];
					}

					break;

				case "shipping_address_update_template":

					$content_heading = 'Shipping address of the subscription id {{subscription_contract_id}} has been changed and the shipping price is {{new_shipping_price}}';

					if ($show_template_for == 'send_dynamic_email') {

						if (isset($data['shipping_data']['delivery_price'])) {

							$new_shipping_price = $this->getCurrencySymbol($get_customer_data['order_currency']) . '' . $data['shipping_data']['delivery_price'];
						}
					}

					break;

				case "payment_failed_template":

					$content_heading = 'Subscription order payment has been failed of the subscription with id {{subscription_contract_id}}';

					break;

				case "payment_declined_template":

					$content_heading = 'Subscription order payment has been declined of the subscription with id {{subscription_contract_id}}';

					break;

				case "payment_pending_template":

					$content_heading = 'Subscription order payment has been declined of the subscription with id {{subscription_contract_id}}';

					break;

				case "subscription_renewal_date_update_template":

					$content_heading = 'Renewal date of the subscription with Id {{subscription_contract_id}} has been updated. The new renewal date of the subscription is {{new_renewal_date}}';

					if ($show_template_for == 'send_dynamic_email') {

						$new_renewal_date = $data['new_renewal_date'];
					}

					break;

				case "upcoming_orders_template":

					$content_heading = 'We wanted to remind you about your upcoming subscription order scheduled for {{renewal_date}}';

					if ($show_template_for == 'send_dynamic_email') {

						$new_renewal_date = $data['next_billing_date'];
					}

					break;

				default:

					$content_heading = '';
			}

			$content_text = '<h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Hi {{customer_name}}</h2><h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">' . $content_heading . '</h2> <p style="line-height:150%;font-size:16px;margin:0">Please visit manage subscription portal to confirm.</p>';
		}



		if (!empty($template_data)) {

			$template_type = $template_data['template_type'];

			$email_subject = $template_data['subject'];

			$ccc_email = $template_data['ccc_email'];

			$bcc_email = $template_data['bcc_email'];

			$from_email = $template_data['from_email'];

			$reply_to = $template_data['reply_to'];

			$logo_height = $template_data['logo_height'];

			$logo_width = $template_data['logo_width'];

			$logo_alignment = $template_data['logo_alignment'];

			$logo = '<img class="sd_logo_view" border="0" style="display:' . ($template_data['logo'] == '' ? 'none' : 'block') . ';color:#000000;text-decoration:none;font-family:Helvetica,arial,sans-serif;font-size:16px;float:' . $logo_alignment . '" width="' . $logo_width . '" src="' . $template_data['logo'] . '" height="' . $logo_height . '" data-bit="iit">';

			$thanks_img_width = $template_data['thanks_img_width'];

			$thanks_img_height = $template_data['thanks_img_height'];

			$thanks_img_alignment = $template_data['thanks_img_alignment'];

			$thanks_img = '<img class="sd_thanks_img_view" border="0" style="display:' . ($template_data['thanks_img'] == '' ? 'none' : 'block') . ';color:#000000;text-decoration:none;font-family:Helvetica,arial,sans-serif;font-size:16px;float:' . $thanks_img_alignment . '" width="' . $thanks_img_width . '" src="' . $template_data['thanks_img'] . '" height="' . $thanks_img_height . '" data-bit="iit">';

			$heading_text = $template_data['heading_text'];

			$heading_text_color = $template_data['heading_text_color'];

			$content_text = $template_data['content_text'];

			$text_color = $template_data['text_color'];

			$manage_subscription_txt = $template_data['manage_subscription_txt'];

			$manage_subscription_url = $template_data['manage_subscription_url'];

			if ($manage_subscription_url == '') {

				$manage_subscription_url = 'https://' . $this->store . '/account';
			}

			$manage_button_text_color = $template_data['manage_button_text_color'];

			$manage_button_background = $template_data['manage_button_background'];

			$shipping_address_text = $template_data['shipping_address_text'];

			$shipping_address = $template_data['shipping_address'];

			$billing_address = $template_data['billing_address'];

			$billing_address_text = $template_data['billing_address_text'];

			$next_renewal_date_text = $template_data['next_renewal_date_text'];

			$payment_method_text = $template_data['payment_method_text'];

			$ending_in_text = $template_data['ending_in_text'];

			$qty_text = $template_data['qty_text'];

			$footer_text = $template_data['footer_text'];

			$currency = '';

			$show_currency = $template_data['show_currency'];

			if ($template_data['show_currency'] == '1') {

				$currency = $this->currency;
			}

			$next_charge_date_text = $template_data['next_charge_date_text'];

			$delivery_every_text = $template_data['delivery_every_text'];

			$custom_template = $template_data['custom_template'];

			$order_number_text = $template_data['order_number_text'];



			$show_currency = $template_data['show_currency'];

			$show_shipping_address = $template_data['show_shipping_address'];

			$show_billing_address = $template_data['show_billing_address'];

			$show_line_items = $template_data['show_line_items'];

			$show_payment_method = $template_data['show_payment_method'];

			$custom_template = $template_data['custom_template'];

			$show_order_number = $template_data['show_order_number'];
		} else {

			$template_type = 'default';

			$ccc_email = '';

			$bcc_email = '';

			$reply_to = '';

			$logo_height = '63';

			$logo_width = '166';

			$logo_alignment = 'center';

			$thanks_img_width = '166';

			$thanks_img_height = '63';

			$thanks_img_alignment = 'center';

			$logo = '<img class="sd_logo_view" border="0" style="color:#000000;text-decoration:none;font-family:Helvetica,arial,sans-serif;font-size:16px;float:' . $logo_alignment . '" width="' . $logo_width . '" src="' . $this->SHOPIFY_DOMAIN_URL . '/application/assets/images/logo.png" height="' . $logo_height . '" data-bit="iit">';

			$thanks_img = '<img class="sd_thanks_img_view" border="0" style="color:#000000;text-decoration:none;font-family:Helvetica,arial,sans-serif;font-size:16px;float:' . $thanks_img_alignment . '" width="' . $thanks_img_width . '" src="' . $this->SHOPIFY_DOMAIN_URL . '/application/assets/images/thank_you.jpg" height="' . $thanks_img_height . '" data-bit="iit">';

			$heading_text = 'Welcome';

			$heading_text_color = '#495661';

			$text_color = '#000000';

			$manage_subscription_txt = 'Manage Subscription';

			$manage_subscription_url = 'https://' . $this->store . '/account';

			$manage_button_text_color = '#ffffff';

			$manage_button_background = '#337ab7';

			$shipping_address_text = 'Shipping address';

			$shipping_address = '<p>{{shipping_full_name}}</p><p>{{shipping_address1}}</p><p>{{shipping_city}},{{shipping_province_code}} - {{shipping_zip}}</p>';

			$billing_address_text = 'Billing address';

			$billing_address = '<p>{{billing_full_name}}</p><p>{{billing_address1}}</p><p>{{billing_city}},{{billing_province_code}} - {{billing_zip}}</p>';

			$next_renewal_date_text = 'Next billing date';

			$payment_method_text = 'Payment method';

			$ending_in_text = 'Ending with';

			$footer_text = '<p style="line-height:150%;font-size:14px;margin:0">Thank You</p>';

			$currency = '';

			$currency = $this->currency;

			$next_charge_date_text = 'Next billing date';

			$delivery_every_text = 'Delivery every';

			$order_number_text = 'Order No.';

			if ($template_name == 'subscription_purchase_template' || $template_name == 'upcoming_orders_template') {

				$email_subject = 'Your recurring order purchase confirmation';

				$show_currency = '1';

				$show_shipping_address = '1';

				$show_billing_address = '1';

				$show_line_items = '1';

				$show_payment_method = '1';

				$custom_template = '';

				$show_order_number = '1';
			} else {

				$email_subject = str_replace('Template', '', $template_heading);

				$show_currency = '0';

				$show_shipping_address = '0';

				$show_billing_address = '0';

				$show_line_items = '0';

				$show_payment_method = '0';

				$custom_template = '';

				$show_order_number = '0';
			}
		}



		// if($this->store == 'predictive-search.myshopify.com'){

		// 	echo $content_text;

		// }



		$subscription_line_items = '<table style="width:100%;border-spacing:0;border-collapse:collapse" class="sd_show_line_items ' . ($show_line_items == '0' ? 'display-hide-label' : '') . '">

		<tbody>

			<tr>

				<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;">

					<center>

						<table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

							<tbody>

								<tr>

									<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

										<table style="width:100%;border-spacing:0;border-collapse:collapse">

											<tbody>

												<tr style="width:100%">

													<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:15px">

														<table style="border-spacing:0;border-collapse:collapse">

															<tbody>';



		// echo $card_brand; die;

		if ($show_template_for == 'send_dynamic_email') {

			$contract_product_details = $data['contract_product_details'] ?? [];

			if ($contract_product_details != '') {
        if($get_customer_data['order_currency'] ?? '' != ''){
				  $currency_code = $this->getCurrencySymbol($get_customer_data['order_currency'] ?? '');
				}else{
					$currency_code = '';
				}

				$updated_at_date = $get_customer_data['updated_at'] ?? '';

				$date_time_array = $updated_at_date !== '' ? explode(' ', $updated_at_date) : [];

				if ($template_name == 'subscription_renewal_date_update_template') {

					$next_billing_date = $data['new_renewal_date'];
				} else {
					$time_part = $date_time_array[1] ?? '';
					$next_billing_date = $get_customer_data['next_billing_date'] ?? '';
				  if($next_billing_date != ''){
						$next_billing_date = $this->getShopTimezoneDate(
							(($get_customer_data['next_billing_date'] ?? '') . ' ' . $time_part),
							($get_customer_data['shop_timezone'] ?? '')
						);
					}
					// $next_billing_date = $this->getShopTimezoneDate((($get_customer_data['next_billing_date'] ?? ''). ' ' . $date_time_array[1]), ($get_customer_data['shop_timezone'] ?? ''));
				}

				// $next_billing_date = $this->getShopTimezoneDate((($get_customer_data['next_billing_date'] ?? '') . ' ' . $date_time_array[1]), ($get_customer_data['shop_timezone'] ?? ''));

				if (($data['contract_details']['after_cycle_update'] ?? '') == '1' && ($data['contract_details']['after_cycle'] ?? '') != 0) {

					$get_subscription_price_column = 'recurring_computed_price';
				} else {

					$get_subscription_price_column = 'subscription_price';
				}

				foreach ($contract_product_details as $key => $prdVal) {

					$product_quantity = $prdVal['quantity'];

					$product_price = $prdVal[$get_subscription_price_column] * $product_quantity;

					if ($template_name == 'product_updated_template') {

						$deleted_product_line_id = $data['updated_product']['line_id'];

						if ($prdVal['contract_line_item_id'] == $deleted_product_line_id) {

							$product_quantity = $data['updated_product']['prd_qty'];

							$product_price = $data['updated_product']['prd_price'] * $product_quantity;
						}
					}

					if ($template_name == 'product_removed_template' && $data['deleted_product_line_id'] == $prdVal['contract_line_item_id']) {

						continue;
					}

					$subscription_line_items .= '<tr style="border-bottom: 1px solid #f3f3f3;">

					<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

						<img src="' . $prdVal['variant_image'] . '" align="left" width="60" height="60" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" class="CToWUd" data-bit="iit">

					</td>

					<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%">

						<span style="font-size:16px;font-weight:600;line-height:1.4;color:' . $heading_text_color . '" class="sd_heading_text_color_view">' . $prdVal['product_name'] . ' ' . $prdVal['variant_name'] . ' x ' . $product_quantity . '</span><br>

						<span class="sd_text_color_view" style="font-size:14px;color:' . $text_color . '"><span class = "sd_delivery_every_text_view">' . $delivery_every_text . '</span> : ' . $get_customer_data['delivery_policy_value'] . ' ' . $get_customer_data['delivery_billing_type'] . '</span><br>

						<span style="font-size:14px;color:' . $text_color . '">' . $next_charge_date_text . ' : ' . $next_billing_date . '</span><br>

					</td>

					<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap">

						<p class="sd_text_color_view" style="color:' . $text_color . ';line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">

							' . $currency_code . '' . $product_price . ' <span class="sd_show_currency ' . ($show_currency == '0' ? 'display-hide-label' : '') . '">' . $get_customer_data['order_currency'] . '</span>

						</p>

					</td>

					</tr>';
				}
			}

			if ($template_name = 'product_added_template') {
				if (isset($data['new_added_products'])) {
					$new_products = $data['new_added_products'];
					$productTitles = [];
					foreach ($new_products as $item) {
						$productTitles[] = $item['product_name'] . '-' . $item['variant_name'];
					}
					$new_added_products = implode(', ', $productTitles);
					foreach ($new_products as $key => $newPrd) {

						if ($get_customer_data['recurring_discount_value'] != 0 && $get_customer_data['after_cycle_update'] == '1') {

							$product_price = $newPrd['recurring_computed_price'];
						} else {

							$product_price = $newPrd['subscription_price'];
						}

						$subscription_line_items .= '<tr style="border-bottom: 1px solid #f3f3f3;">

						<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

							<img src="' . $newPrd['variant_image'] . '" align="left" width="60" height="60" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" class="CToWUd" data-bit="iit">

						</td>

						<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%">

							<span style="font-size:16px;font-weight:600;line-height:1.4;color:' . $heading_text_color . '" class="sd_heading_text_color_view">' . $newPrd['product_name'] . ' ' . $newPrd['variant_name'] . ' x 1</span><br>

							<span class="sd_text_color_view" style="font-size:14px;color:' . $text_color . '"><span class = "sd_delivery_every_text_view">' . $delivery_every_text . '</span> : ' . $get_customer_data['delivery_policy_value'] . ' ' . $get_customer_data['delivery_billing_type'] . '</span><br>

							<span style="font-size:14px;color:' . $text_color . '">' . $next_charge_date_text . ' : ' . $next_billing_date . '</span><br>

						</td>

						<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap">

							<p class="sd_text_color_view" style="color:' . $text_color . ';line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">

								' . $currency_code . '' . $product_price . ' <span class="sd_show_currency ' . ($show_currency == '0' ? 'display-hide-label' : '') . '">' . $get_customer_data['order_currency'] . '</span>

							</p>

						</td>

						</tr>';
					}
				}
			}
		} else {

			$subscription_line_items .= '<tr style="border-bottom: 1px solid #f3f3f3;">

									<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

										<img src="' . $product_image_url . '" align="left" width="60" height="60" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" class="CToWUd" data-bit="iit">

									</td>

									<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%">

										<span style="font-size:16px;font-weight:600;line-height:1.4;color:' . $heading_text_color . '" class="sd_heading_text_color_view">' . $product_name . '-' . $variant_name . ' x 2</span><br>

										<span class="sd_text_color_view" style="font-size:14px;color:' . $text_color . '"><span class="sd_delivery_every_text_view">' . $delivery_every_text . '</span> : 1 month</span><br>

										<span class="sd_text_color_view" style="font-size:14px;color:' . $text_color . '"><span class="sd_next_charge_date_text_view">' . $next_charge_date_text . '</span> : 5 May, 2023</span><br>

									</td>

									<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap">

										<p class="sd_text_color_view" style="color:' . $text_color . ';line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">

											' . $this->currency_code . '100.00 <span class="sd_show_currency ' . ($show_currency == '0' ? 'display-hide-label' : '') . '">' . $this->currency . '</span>

										</p>

									</td>

								</tr>

								<tr style="border-bottom: 1px solid #f3f3f3;">

								<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

									<img src="' . $product_image_url . '" align="left" width="60" height="60" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" class="CToWUd" data-bit="iit">

								</td>

								<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%">

									<span style="font-size:16px;font-weight:600;line-height:1.4;color:' . $heading_text_color . '" class="sd_heading_text_color_view">' . $product_name . '-' . $variant_name . ' x 2</span><br>

									<span class="sd_text_color_view" style="font-size:14px;color:' . $text_color . '"><span class="sd_delivery_every_text_view">' . $delivery_every_text . '</span> : 1 month</span><br>

									<span class="sd_text_color_view" style="font-size:14px;color:' . $text_color . '"><span class="sd_next_charge_date_text_view">' . $next_charge_date_text . '</span> : 5 May, 2023</span><br>

							    </td>

								<td style="padding:15px 0px;font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap">

									<p class="sd_text_color_view" style="color:' . $text_color . ';line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">

										' . $this->currency_code . '100.00 <span class="sd_show_currency ' . ($show_currency == '0' ? 'display-hide-label' : '') . '">' . $this->currency . '</span>

									</p>

								</td>

							</tr>';
		}

		$subscription_line_items .= '</tbody>

											</table>

										</td>

									</tr>

								</tbody>

								</table>

								</td>

								</tr>

								</tbody>

								</table>

								</center>

								</td>

								</tr>

								</tbody>

								</table>';



		$default_email_template = '<div style="background-color:#efefef" bgcolor="#efefef">

			<table role="presentation" cellpadding="0" cellspacing="0" style="border-spacing:0!important;border-collapse:collapse;margin:0;padding:0;width:100%!important;min-width:320px!important;height:100%!important;background-image: url(' . $this->SHOPIFY_DOMAIN_URL . '/application/assets/images/default_template_background.jpg);background-repeat:no-repeat;background-size:100% 100%;background-position:center" width="100%" height="100%">

				<tbody>

					<tr>

					  <td valign="top" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:15px;color:#191d48;word-break:break-word;">

							<div id="m_-5083759200921609693m_-526092176599779985hs_cos_wrapper_main" style="color:inherit;font-size:inherit;line-height:inherit">  <div id="m_-5083759200921609693m_-526092176599779985section-0" style="padding-top:20px;padding-bottom: 20px;">

							  <div style="max-width: 644px;width:100%;Margin-left:auto;Margin-right:auto;border-collapse:collapse;border-spacing:0;background-color:#ffffff;" bgcolor="#ffffff">

	<table style="height:100%!important;width:100%!important;border-spacing:0;border-collapse:collapse;">

		<tbody>

			<tr>

				<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

					<table class="m_-1845756208323497270header" style="width:100%;border-spacing:0;border-collapse:collapse;margin:40px 0 20px">

						<tbody>

							<tr>

								<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

									<center>

									<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" role="module" style="width:100%;border-spacing:0;border-collapse:collapse;">

										<tbody>

											<tr role="module-content">

												<td height="100%" valign="top">

												<table width="100%" style="width:100%;border-spacing:0;border-collapse:collapse;margin:0px 0px 0px 0px" cellpadding="0" cellspacing="0" align="left" border="0" bgcolor="">

													<tbody>

											    <tr>

												<td style="padding:0px;margin:0px;border-spacing:0">

												<table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">

													<tbody>

														<tr>

															<td style="font-size:6px;line-height:10px;padding:0px 0px 0px 0px" valign="top" align="center">

															' . $logo . '

															</td>

														</tr>

														<tr>

															<td style="font-size:6px;line-height:10px;padding:0px 0px 0px 0px" valign="top" align="center">

															' . $thanks_img . '

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

							<table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

								<tbody>

									<tr>

										<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

											<table style="width:100%;border-spacing:0;border-collapse:collapse">

												<tbody>

													<tr>

														<td class="m_-1845756208323497270shop-name__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

														 <div class="sd_heading_text_color_view" style="color:' . $heading_text_color . '">

														   <h1 style="font-weight:normal;font-size:30px;margin:0" class="sd_heading_text_view">

															' . $heading_text . '

															</h1>

														 </div>

														</td>

														<td class="m_-1845756208323497270order-number__cell sd_show_order_number ' . ($show_order_number == '0' ? 'display-hide-label' : '') . '" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;text-transform:uppercase;font-size:14px;color:#999" align="right">

														<table style="width:100%;text-align:right;">

															<tbody>

															   <tr> <td> <span style="font-size:13px,font-weight:600;color:' . $text_color . '" class="sd_order_number_text_view sd_text_color_view"><b>' . $order_number_text . '</b></span> </td> </tr>

															   <tr> <td> <span class="sd_text_color_view" style="font-size:16px;color:' . $text_color . '"> ' . $order_number . ' </span> </td> </tr>

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

						</center>

					</td>

				</tr>

			</tbody>

		</table>

		<table style="width:100%;border-spacing:0;border-collapse:collapse">

			<tbody>

				<tr>

					<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;border-width:0">

						<center>

							<table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

								<tbody>

									<tr>

										<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

											<div class="sd_content_text_view sd_text_color_view" style="color:' . $text_color . ';">

												' . $content_text . '

											</div>

											<table style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px">

												<tbody>

													<tr>

														<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;line-height:0em">&nbsp;</td>

													</tr>

													<tr>

														<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

															<table class="m_-1845756208323497270button m_-1845756208323497270main-action-cell" style="border-spacing:0;border-collapse:collapse;float:left;margin-right:15px">

																<tbody>

																	<tr>

																		<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;border-radius:4px" align="center" class="sd_manage_button_background_view"  bgcolor="' . $manage_button_background . '"><a href="' . $manage_subscription_url . '" class="sd_manage_button_text_color_view sd_manage_subscription_txt_view sd_manage_subscription_url_view" target="_blank" style="font-size:16px;text-decoration:none;display:block;color:' . $manage_button_text_color . ';padding:20px 25px">' . $manage_subscription_txt . '</a></td>

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

						</center>

					</td>

				</tr>

			</tbody>

		</table>

	    ' . $subscription_line_items . '

		<table style="width:100%;border-spacing:0;border-collapse:collapse">

			<tbody>

				<tr>

					<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0">

						<center>

							<table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

								<tbody>

									<tr>

										<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

											<table style="width:100%;border-spacing:0;border-collapse:collapse">

												<tbody>

													<tr>

														<td class="m_-1845756208323497270customer-info__item sd_show_shipping_address ' . ($show_shipping_address == '0' ? 'display-hide-label' : '') . '" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%" valign="top">

															<h4 style="font-weight:500;font-size:16px;color:' . $heading_text_color . ';margin:0 0 5px" class="sd_heading_text_color_view sd_shipping_address_text_view">' . $shipping_address_text . '</h4>

															<div class="sd_shipping_address_view sd_text_color_view" style="color:' . $text_color . ';">' . $shipping_address . '</div>

														</td>

														<td class="m_-1845756208323497270customer-info__item sd_show_billing_address ' . ($show_billing_address == '0' ? 'display-hide-label' : '') . '" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%" valign="top">

															<h4 style="font-weight:500;font-size:16px;color:' . $heading_text_color . ';margin:0 0 5px" class="sd_heading_text_color_view sd_billing_address_text_view">' . $billing_address_text . '</h4>

															<div class="sd_billing_address_view sd_text_color_view" style="color:' . $text_color . ';">' . $billing_address . '</div>

														</td>

													</tr>

												</tbody>

											</table>

											<div class="sd_show_payment_method ' . ($show_payment_method == '0' ? 'display-hide-label' : '') . '">

											<table style="width:100%;border-spacing:0;border-collapse:collapse">

												<tbody>

													<tr>

														<td class="m_-1845756208323497270customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%" valign="top">

															<h4 style="font-weight:500;font-size:16px;color:' . $heading_text_color . ';margin:0 0 5px" class="sd_heading_text_color_view sd_payment_method_text_view">' . $payment_method_text . '</h4>

															<p style="color:' . $text_color . ';line-height:150%;font-size:16px;margin:0" class="sd_text_color_view">

																{{card_brand}}

																<span style="font-size:16px;color:' . $text_color . '" class="sd_text_color_view sd_ending_in_text_view">' . $ending_in_text . '{{last_four_digits}}</span><br>

															</p>

														</td>

													</tr>

												</tbody>

											</table>

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

		<table style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid">

			<tbody>

				<tr>

					<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:35px 0">

						<center>

							<table class="m_-1845756208323497270container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto">

								<tbody>

									<tr>

										<td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif">

										<div class="sd_footer_text_view">' . $footer_text . '</div>

										</td>

									</tr>

								</tbody>

							</table>

						</center>

					</td>

				</tr>

			</tbody>

		</table>

		<img src="https://ci4.googleusercontent.com/proxy/C5WAwfRu-nhYYB726ZtDmBBZxH2ZQQgtpxwmJT5KONtMOVp6k7laRdD7JghQXsHLcYM4veQr436syfT22M4kVYeof9oM4TIq5I7li0_YUjrim2hpHv5dYG7V9z9OmFYRRwYK3KgYIf0ck0d_WTq1EjhX_DpBFoi4n20fTmcCfJxl76PIrL1HodOHxbkR8PrieSaJX9F3tcNZb-9L3JTm7_owWlAKVQ64kFMBmJHwK7I=s0-d-e1-ft#https://cdn.shopify.com/shopifycloud/shopify/assets/themes_support/notifications/spacer-1a26dfd5c56b21ac888f9f1610ef81191b571603cb207c6c0f564148473cab3c.png" class="m_-1845756208323497270spacer CToWUd" height="1" style="min-width:600px;height:0" data-bit="iit">

	</td>

</tr>

</tbody>

</table>

			</div>

			</div>

			</div>

		</td>

	</tr>

</tbody>

</table>

</div>';

		if ($custom_template != '' && $custom_template != '<br>' && $template_type == 'custom') {

			$template_data = $custom_template;
		} else if ($template_type == 'default') {

			$doc = new DOMDocument();
			$doc->loadHTML('<?xml encoding="UTF-8">' . $default_email_template, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

			$xpath = new DOMXPath($doc);

			$elementsToRemove = $xpath->query("//*[contains(@class,'display-hide-label')]");

			foreach ($elementsToRemove as $element) {

				$element->parentNode->removeChild($element);
			}

			$modifiedHtml = $doc->saveHTML($doc->documentElement);

			// $modifiedHtml = mb_convert_encoding($modifiedHtml, 'HTML-ENTITIES', 'UTF-8');

			$template_data = urldecode($modifiedHtml);
		}

		$count = -1;

		$result = str_replace(

			array('{{subscription_contract_id}}', '{{customer_email}}', '{{customer_name}}', '{{customer_id}}', '{{next_order_date}}', '{{selling_plan_name}}', '{{shipping_full_name}}', '{{shipping_address1}}', '{{shipping_company}}', '{{shipping_city}}', '{{shipping_province}}', '{{shipping_province_code}}', '{{shipping_zip}}', '{{billing_full_name}}', '{{billing_address1}}', '{{billing_city}}', '{{billing_province}}', '{{billing_province_code}}', '{{billing_zip}}', '{{product_quantity}}', '{{subscription_line_items}}', '{{last_four_digits}}', '{{card_expire_month}}', '{{card_expire_year}}', '{{shop_name}}', '{{shop_email}}', '{{shop_domain}}', '{{manage_subscription_url}}', '{{delivery_cycle}}', '{{billing_cycle}}', '{{email_subject}}', '{{header_text_color}}', '{{text_color}}', '{{heading_text}}', '{{logo_image}}', '{{manage_subscription_button_color}}', '{{manage_subscription_button_text}}', '{{manage_subscription_button_text_color}}', '{{shipping_address_text}}', '{{billing_address_text}}', '{{payment_method_text}}', '{{ending_in_text}}', '{{logo_height}}', '{{logo_width}}', '{{thanks_image}}', '{{thanks_image_height}}', '{{thanks_image_width}}', '{{logo_alignment}}', '{{thanks_image_alignment}}', '{{card_brand}}', '{{order_number}}', '{{new_added_products}}', '{{removed_product}}', '{{updated_product}}', '{{skipped_order_date}}', '{{actual_fulfillment_date}}', '{{new_scheduled_date}}', '{{new_shipping_price}}', '{{new_renewal_date}}', '{{renewal_date}}'),

			array($subscription_contract_id, $customer_email, $customer_name, $customer_id, $next_order_date, $selling_plan_name, $shipping_full_name, $shipping_address1, $shipping_company, $shipping_city, $shipping_province, $shipping_province_code, $shipping_zip, $billing_full_name, $billing_address1, $billing_city, $billing_province, $billing_province_code, $billing_zip, $product_quantity, $subscription_line_items, $last_four_digits, $card_expire_month, $card_expire_year, $shop_name, $shop_email, $shop_domain, $manage_subscription_url, $delivery_cycle, $billing_cycle, $email_subject, $heading_text_color, $text_color, $heading_text, $logo, $manage_button_background, $manage_subscription_txt, $manage_button_text_color, $shipping_address_text, $billing_address_text, $payment_method_text, $ending_in_text, $logo_height, $logo_width, $thanks_img, $thanks_img_height, $thanks_img_width, $logo_alignment, $thanks_img_alignment, $card_brand, $order_number, $new_added_products, $deleted_product, $updated_product, $skipped_order_date, $actual_fulfillment_date, $new_scheduled_date, $new_shipping_price, $new_renewal_date, $new_renewal_date),

			$template_data,

			$count

		);



		$return_template_array = array(

			'test_email_content' => $result,

			'default_email_template' => $default_email_template,

			'email_subject' => $email_subject,

			'ccc_email' => $ccc_email,

			'bcc_email' => $bcc_email,

			'reply_to' => $reply_to,

			'template_type' => $template_type,

		);

		return $return_template_array;
	}



	public function view_subscription_plan($subscription_group_id)
	{

		$whereCondition = array(

			"subscription_plangroup_id" => $subscription_group_id

		);

		$store_edit_subscription_plan = $this->table_row_value('subscriptionPlanGroups', "all", $whereCondition, 'and', '');



		if (is_int($store_edit_subscription_plan)) {

			return json_encode(array("status" => false, 'error' => '', 'message' => '404'));
		}

		$subscription_plan_name =  htmlspecialchars_decode($store_edit_subscription_plan[0]['plan_name']);

		$subscription_products  =  $this->table_row_value('subscriptionPlanGroupsProducts', "all", array("subscription_plan_group_id" => $subscription_group_id), 'and', '');

		$subscription_frequency_plans = $this->table_row_value('subscriptionPlanGroupsDetails', "all", array("subscription_plan_group_id	" => $subscription_group_id), 'and', '');

		return json_encode(array("status" => true, "subscription_plan_name" => $subscription_plan_name, "subscription_products" => $subscription_products, "subscription_frequency_plans" => $subscription_frequency_plans));
	}



	public function sellingPlanFormError($error_name, $plan_error)
	{

		return '<div class="Polaris-Labelled__Error display-hide-label frequency-plan-error ' . $error_name . '">

		<div id="PolarisTextField4Error" class="Polaris-InlineError">

		   <div class="Polaris-InlineError__Icon">

			  <span class="Polaris-Icon">

				 <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">

					<path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zM9 9a1 1 0 0 0 2 0V7a1 1 0 1 0-2 0v2zm0 4a1 1 0 1 0 2 0 1 1 0 0 0-2 0z"></path>

				 </svg>

			  </span>

		   </div>

		   ' . $plan_error . '

		</div>

	 </div>';
	}



	public function addAppMetafields($theme_id)
	{

		$app_installation_query = '{

			appInstallation{

				id

			}

		}';

		$app_installation_execution = $this->graphqlQuery($app_installation_query, null, null, null);

		$app_installation_id = $app_installation_execution['data']['appInstallation']['id'];

		//check if theme_support 2.0

		try {

			$get_theme_support = $this->PostPutApi('https://' . $this->store . '/admin/api/' . $this->SHOPIFY_API_VERSION . '/themes/' . $theme_id . '/assets.json?asset[key]=templates/product.json', 'GET', $this->access_token, '');

			if ($get_theme_support) {

				$theme_block_support = 'support_theme_block';

				$theme_block_support_not = 'support_theme_block_not';

				$meta_field_value_not = "false";

				$meta_field_value = "true";
			} else {

				$theme_block_support = 'support_theme_block';

				$meta_field_value = "false";

				$theme_block_support_not = 'support_theme_block_not';

				$meta_field_value_not = "true";
			}
		} catch (Exception $e) {

			$theme_block_support = 'support_theme_block';

			$meta_field_value = "false";

			$theme_block_support_not = 'support_theme_block_not';

			$meta_field_value_not = "true";
		}



		try {

			$createAppOwnedMetafield =  'mutation CreateAppOwnedMetafield($metafieldsSetInput: [MetafieldsSetInput!]!) {

				  metafieldsSet(metafields: $metafieldsSetInput) {

					metafields {

					  id

					  namespace

					  key

					  type

					  value

					}

					userErrors {

					  field

					  message

					}

				  }

			  }';



			$webhookParameters = [

				"metafieldsSetInput" => [

					[

						"namespace" => "theme_support",

						"key" => $theme_block_support,

						"type" => "boolean",

						"value" => $meta_field_value,

						"ownerId" => $app_installation_id

					],

					[

						"namespace" => "theme_not_support",

						"key" => $theme_block_support_not,

						"type" => "boolean",

						"value" => $meta_field_value_not,

						"ownerId" => $app_installation_id

					]

				]

			];

			$AppOwnedMetafieldGet = $this->graphqlQuery($createAppOwnedMetafield, null, null, $webhookParameters);

			$AppOwnedMetafield_error = $AppOwnedMetafieldGet['data']['metafieldsSet']['userErrors'];

			if (!count($AppOwnedMetafield_error)) {

				return true;
			} else {

				return false;
			}
		} catch (Exception $e) {

			return false;
		}
	}



	function add_custom_metafields()
	{

		$app_installation_query = '{

				appInstallation{

					id

				}

			}';

		$app_installation_execution = $this->graphqlQuery($app_installation_query, null, null, null);

		$app_installation_id = $app_installation_execution['data']['appInstallation']['id'];

		try {

			$createAppOwnedMetafield =  'mutation CreateAppOwnedMetafield($metafieldsSetInput: [MetafieldsSetInput!]!) {

					metafieldsSet(metafields: $metafieldsSetInput) {

					  metafields {

						id

						namespace

						key

						type

						value

					  }

					  userErrors {

						field

						message

					  }

					}

				}';



			$webhookParameters = [

				"metafieldsSetInput" => [

					[

						"namespace" => "theme_support",

						"key" => "support_theme_block",

						"type" => "boolean",

						"value" => "false",

						"ownerId" => $app_installation_id

					],

					[

						"namespace" => "theme_not_support",

						"key" => "support_theme_block_not",

						"type" => "boolean",

						"value" => "true",

						"ownerId" => $app_installation_id

					]

				]

			];

			$AppOwnedMetafieldGet = $this->graphqlQuery($createAppOwnedMetafield, null, null, $webhookParameters);

			// echo '<pre>';

			// print_r($AppOwnedMetafieldGet);

			$AppOwnedMetafield_error = $AppOwnedMetafieldGet['data']['metafieldsSet']['userErrors'];

			if (!count($AppOwnedMetafield_error)) {

				return true;
			} else {

				return false;
			}
		} catch (Exception $e) {

			return false;
		}
	}



	public function create_app_subscription($data)
	{

		// if($this->store == 'predictive-search.myshopify.com'){

		//    echo '<pre>';

		//    print_r($data);

		//    die;

		// }

		$wherePlanCondition = array(

			'id' => $data['plan_id'],

			'planName' => $data['plan_name'],

		);

		$fields = array('price', 'subscription_emails', 'trial');

		$member_plan_data = $this->single_row_value('memberPlanDetails', $fields, $wherePlanCondition, 'and', '');

		if ($member_plan_data) {

			$memberPlanPrice = '';

			if ($this->store == 'crate61.myshopify.com' && $data['plan_name']) {

				$memberPlanPrice = 75;
			} else {

				$memberPlanPrice = $member_plan_data['price'];
			}

			if ($this->store == 'mini-cart-development.myshopify.com' || $this->store == 'shine-predictive-search.myshopify.com' || $this->store == 'mytab-shinedezign.myshopify.com' || $this->store == 'predictive-search.myshopify.com' || $this->store == 'kawals-store.myshopify.com' || $this->store == 'inder-store-credit.myshopify.com' || $this->store == 'shineinfotest.myshopify.com' || $this->store == 'test-store-phoenixt.myshopify.com' || $this->store == 'pheonix-store-local.myshopify.com' || $this->store == 'pheonix-appstore.myshopify.com') {

				$test_charge = true;
			} else {

				$test_charge = false;
			}

			try {

				$return_url = $this->SHOPIFY_DOMAIN_URL . '/admin/app_plans.php?shop=' . $this->store;

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

					"lineItems" => [
						[
							"plan" => [
								"appRecurringPricingDetails" => [
									"price" => [
										"amount" => 9.99,
										"currencyCode" => "USD"
									],
									"interval" => "EVERY_30_DAYS"
								]
							]
						],
						[
							"plan" => [
								"appUsagePricingDetails" => [
									"cappedAmount" => [
										"amount" => 100000.00,
										"currencyCode" => "USD"
									],
									"terms" => "5% + $0.12 Subscription & membership charge"
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
				// echo('hii');
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

						'status' => '1',

						'planName' => 'Limited Special',

						'price' => '9.99',

						'plan_id' => '11',

						'store_id' => $this->store_id,

						'subscription_emails' => '10000',

						'trial' => '0',

						'plan_status' => '1'


					);

					// $whereCondition = array(

					// 	'store_id' => $this->store_id

					// );



					$wherePro = [
						'store_id'      => $this->store_id
					];
					$data = $this->getAppPlan();
					// if($store == 'test-store-phoenixt.myshopify.com') {
					$found = false;

					foreach ($data['data']['currentAppInstallation']['activeSubscriptions'] as $subscription) {
						// print_r($subscription);
						if ($subscription['name'] === 'Limited Special SUBSCRIPTION APP PRICING') {
							foreach ($subscription['lineItems'] as $item) {
								$pricingDetails = $item['plan']['pricingDetails'];
								if (
									isset($pricingDetails['__typename']) &&
									$pricingDetails['__typename'] === 'AppRecurringPricing' &&
									isset($pricingDetails['price']['amount']) &&
									$pricingDetails['price']['amount'] == 9.99
								) {
									$found = true;
									break 2; // Exit both loops
								}
							}
						}
					}


					$productInsert = $this->insertupdateajax('storeInstallOffers', $fields, $wherePro, 'and');


					// $this->insert_row('storeInstallOffers', $fields);

					return json_encode(array("status" => true, 'recurring_id' => $appUsagePlanId, 'confirmationUrl' => $createAppSubscription['data']['appSubscriptionCreate']['confirmationUrl'])); // return json

				} else {

					return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

				}
			} catch (Exception $e) {

				// echo '<pre>';

				// print_r($e->getMessage());

				// die;

				return json_encode(array("status" => false, 'message' => $e)); // return json

			}
		} else {

			return json_encode(array("status" => false, 'message' => 'Something went wrong')); // return json

		}
	}



	// public function create_frequency_plans_array($frequency_plans, $mode, $total_sellling_plans_names, $data_from)
	// {
	// 	// print_r($frequency_plans);
	// 	$sellingPlans = "[";

	// 	$selling_plan_id = '';

	// 	$index = 1;

	// 	$mainGroupOption = '';

	// 	foreach ($frequency_plans as $key => $frequency_plan) {

	// 		if (is_array($frequency_plan)) {

	// 			$frequency_plan = (object)$frequency_plan;
	// 		}

	// 		if ($frequency_plan->frequency_plan_type == "Prepaid") {

	// 			$billing_interval = strtoupper($frequency_plan->per_delivery_order_frequency_type);

	// 			$billing_intervalCount = $frequency_plan->prepaid_billing_value;
	// 		} else if ($frequency_plan->frequency_plan_type == "Pay Per Delivery") {

	// 			$billing_interval = strtoupper($frequency_plan->per_delivery_order_frequency_type);

	// 			$billing_intervalCount = $frequency_plan->per_delivery_order_frequency_value;
	// 		}

	// 		$frequency_plan_name = $frequency_plan->frequency_plan_name;

	// 		$sellingplanid = $frequency_plan->sellingplanid;

	// 		$delivery_interval = strtoupper($frequency_plan->per_delivery_order_frequency_type);

	// 		$delivery_intervalCount = $frequency_plan->per_delivery_order_frequency_value;

	// 		$options = 'Delivery every ' . $delivery_intervalCount . ' ' . $delivery_interval . '(s) and Billing every' . $billing_intervalCount . ' ' . $billing_interval . '(' . $frequency_plan->frequency_plan_type . ')';



	// 		if ($index > 1) {

	// 			$mainGroupOption .= ',' . $options;
	// 		} else {

	// 			$mainGroupOption .= $options;
	// 		}

	// 		$adjustmentType =  "";

	// 		$adjustmentValue =  "";

	// 		$pricingpolicies = "pricingPolicies: []";

	// 		$anchors = 'anchors:[]';

	// 		$cutoffDay = '';

	// 		$anchor_month_day = ((int)(date("d")));

	// 		if ($frequency_plan->per_delivery_order_frequency_type != 'DAY') {

	// 			$per_delivery_order_frequency_type = $frequency_plan->per_delivery_order_frequency_type . 'DAY';
	// 		} else {

	// 			$per_delivery_order_frequency_type = $frequency_plan->per_delivery_order_frequency_type;
	// 		}

	// 		// if($frequency_plan->per_delivery_order_frequency_type == 'WEEK'){

	// 		// 	$weekDaysIndex = array_search(date('l'), $this->weekDaysArray) + 1;

	// 		// 	$anchors = 'anchors: {

	// 		// 		day: '.$weekDaysIndex.'

	// 		// 		type: '.$per_delivery_order_frequency_type.'

	// 		// 	}';

	// 		// }else if($frequency_plan->per_delivery_order_frequency_type == 'MONTH'){

	// 		// 	$anchors = 'anchors: {

	// 		// 		day: '.$anchor_month_day.'

	// 		// 		type: '.$per_delivery_order_frequency_type.'

	// 		// 	}';

	// 		// }

	// 		// post anchors in billingPolicy

	// 		if (($data_from == 'backend_subscription' && property_exists($frequency_plan, 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $frequency_plan->sd_set_anchor_date == 'on')) {

	// 			if ($frequency_plan->sd_anchor_option == 'On Specific Day') {



	// 				if ($frequency_plan->sd_anchor_month_day == '') {

	// 					$weekDaysIndex = array_search($frequency_plan->sd_anchor_week_day, $this->weekDaysArray) + 1;

	// 					$anchors = 'anchors: {

	// 								day: ' . $weekDaysIndex . '

	// 								type: ' . $per_delivery_order_frequency_type . '

	// 							}

	// 							';
	// 				} else if ($frequency_plan->sd_anchor_month_day != '' && $frequency_plan->per_delivery_order_frequency_type != 'DAY') {

	// 					$anchors = 'anchors: {

	// 								day: ' . $frequency_plan->sd_anchor_month_day . '

	// 								type: ' . $per_delivery_order_frequency_type . '

	// 							}

	// 							';
	// 				}

	// 				if ($frequency_plan->cut_off_days != '' && $frequency_plan->cut_off_days != 0) {

	// 					$cutoffDay = 'cutoff: ' . $frequency_plan->cut_off_days;
	// 				}
	// 			}
	// 		}
	// 		// print_r($frequency_plan);
	// 		// die();
	// 		// if($this->store == '4d03e4-3.myshopify.com') {
	// 		// 	print_r($frequency_plan);
	// 		// }

	// 		// if (($data_from == 'backend_subscription' && (property_exists($frequency_plan, 'subscription_discount') || (property_exists($frequency_plan, 'offer_trial_status') && $frequency_plan->offer_trial_status != 0) ) ) || ($data_from == 'product_app_extension' && $frequency_plan->subscription_discount == 'on')) {
	//         if (($data_from == 'backend_subscription' && (!empty($frequency_plan->subscription_discount_value) || (!empty($frequency_plan->offer_trial_status) && $frequency_plan->offer_trial_status == 'on'))) || ($data_from == 'product_app_extension' && isset($frequency_plan->subscription_discount) && $frequency_plan->subscription_discount == 'on')) {

	// 			$recurring_discount = '';

	// 			$adjustmentType = "";

	// 			$adjustmentType1 = "";

	// 			if ($frequency_plan->subscription_discount_type == 'Percent Off(%)') {

	// 				$adjustmentType = "PERCENTAGE";

	// 				$adjustmentType1 = "percentage : ";

	// 				$discount_symbol = '%';
	// 			} else {

	// 				$adjustmentType = "FIXED_AMOUNT";

	// 				$adjustmentType1 = "fixedValue : ";

	// 				$discount_symbol = $this->currency_code;
	// 			}

	// 			$regular_discount  = 'save ' . $frequency_plan->subscription_discount_value . ' ' . $discount_symbol;

	// 			$adjustmentValue =  "{ " . $adjustmentType1 . $frequency_plan->subscription_discount_value . " }";

	// 			// if (($data_from == 'backend_subscription' && (property_exists($frequency_plan, 'subscription_discount_after') || (property_exists($frequency_plan, 'offer_trial_status') && $frequency_plan->offer_trial_status != 0))) || ($data_from == 'product_app_extension' && $frequency_plan->subscription_discount_after == 'on')) {
	//             if (($data_from == 'backend_subscription' && ((!empty($frequency_plan->subscription_discount_after) && $frequency_plan->subscription_discount_after == 'on') || (!empty($frequency_plan->offer_trial_status) && $frequency_plan->offer_trial_status == 'on'))) || ($data_from == 'product_app_extension' && !empty($frequency_plan->subscription_discount_after) && $frequency_plan->subscription_discount_after == 'on')) {

	// 				// if ($this->store == 'test-store-phoenixt.myshopify.com') {
	// 				// 	print_r($frequency_plans);
	// 				// 	print_r($data_from);

	// 				// }

	// 				$discount_after_cycle = $frequency_plan->change_discount_after_cycle;

	// 				if ($frequency_plan->subscription_discount_type_after == 'Percent Off(%)') {

	// 					$adjustmentType_after = "PERCENTAGE";

	// 					$adjustmentType1_after = "percentage : ";

	// 					$discount_symbol = '%';
	// 				} else {

	// 					$adjustmentType_after = "FIXED_AMOUNT";

	// 					$adjustmentType1_after = "fixedValue : ";

	// 					$discount_symbol = $this->currency_code;
	// 				}

	// 				$adjustmentValue_after =  "{ " . $adjustmentType1_after . $frequency_plan->discount_value_after . " }";

	// 				$recurring_discount = '{recurring: {

	// 						adjustmentType: ' . $adjustmentType_after . '

	// 						adjustmentValue: ' . $adjustmentValue_after . '

	// 						afterCycle :  ' . $discount_after_cycle . '

	// 					}}';

	// 				if ($frequency_plan->change_discount_after_cycle == 1) {

	// 					$discount_after = 'on first order then ' . $frequency_plan->discount_value_after . ' ' . $discount_symbol;
	// 				} else {

	// 					$discount_after = 'on first ' . $frequency_plan->change_discount_after_cycle . ' orders then ' . $frequency_plan->discount_value_after . ' ' . $discount_symbol;
	// 				}
	// 			}


	// 			$pricingpolicies = 'pricingPolicies: [

	// 					{

	// 						fixed: {

	// 							adjustmentType: ' . $adjustmentType . '

	// 							adjustmentValue: ' . $adjustmentValue . '

	// 						}

	// 					}

	// 					' . $recurring_discount . '

	// 				]';
	// 		}



	// 		if ($mode == 'update') {

	// 			$selling_plan_id = 'id:"gid://shopify/SellingPlan/' . $key . '"';
	// 		}

	// 		if ($index > 1) {

	// 			$total_sellling_plans_names .= ',';
	// 		}

	// 		$total_sellling_plans_names .= $frequency_plan->per_delivery_order_frequency_value . ' ' . $frequency_plan->per_delivery_order_frequency_type;

	// 		if (empty($frequency_plan->maximum_number_cycle)) {

	// 			$max_cycle_mutation = '';

	// 			$maxCycle = '';
	// 		} else {

	// 			$maxCycle = $frequency_plan->maximum_number_cycle;

	// 			$max_cycle_mutation = ", maxCycles : " . $maxCycle;
	// 		}

	// 		$minCycle = $frequency_plan->minimum_number_cycle;

	// 		if (empty($frequency_plan->minimum_number_cycle)) {

	// 			$minCycle = 1;
	// 		}

	// 		// $delivery_every = ''.$frequency_plan->per_delivery_order_frequency_type;

	// 		// if($frequency_plan->per_delivery_order_frequency_value != 1){

	// 		// 	$delivery_every = $frequency_plan->per_delivery_order_frequency_value.' '.ucfirst(strtolower($frequency_plan->per_delivery_order_frequency_type)).'(s)';

	// 		// }

	// 		// $selling_plan_name = $prepaid_delivery.' Delivery Every '.$delivery_every; //this name will be displayed on cart and checkout page on frontend


	// 		// if($this->store == '4d03e4-3.myshopify.com') {
	// 		// 	echo $pricingpolicies;
	// 		// }
	// 		$sellingPlans .= '{

	// 						' . $selling_plan_id . '

	// 						name: "' . addslashes($frequency_plan_name) . '"

	// 						options: "' . $options . '"

	// 						position: ' . $index . '

	// 						category: SUBSCRIPTION

	// 						inventoryPolicy:{

	// 						reserve : ON_FULFILLMENT

	// 						}

	// 						billingPolicy: {

	// 						recurring: {

	// 						' . $anchors . '

	// 						interval: ' . $billing_interval . ', intervalCount: ' . $billing_intervalCount . ', minCycles : ' . $minCycle . ' ' . $max_cycle_mutation . ' } }

	// 						deliveryPolicy: { recurring: {

	// 							intent:FULFILLMENT_BEGIN,

	// 							' . $anchors . '

	// 							' . $cutoffDay . '

	// 							preAnchorBehavior : ASAP

	// 						interval: ' . $delivery_interval . ', intervalCount: ' . $delivery_intervalCount . '} }

	// 						description : "' . trim($frequency_plan->sd_description, "") . '"

	// 						' . $pricingpolicies . '

	// 					}';

	// 		$index++;
	// 	}

	// 	$sellingPlans .= "]";

	// 	$selling_plan_array = array(

	// 		'selling_plans' => $sellingPlans,

	// 		'total_sellling_plans_names' => $total_sellling_plans_names,

	// 		'main_group_option' => trim($mainGroupOption, ","),

	// 	);

	// 	return $selling_plan_array;
	// }

	public function create_frequency_plans_array($frequency_plans, $mode, $total_sellling_plans_names, $data_from)
	{
		$sellingPlans = [];
		$mainGroupOption = '';
		$index = 1;
		foreach ($frequency_plans as $key => $plan) {
			if (is_array($plan)) {
				$plan = (object)$plan;
			}
			$billing_interval = strtoupper($plan->per_delivery_order_frequency_type);
			$billing_intervalCount = $plan->frequency_plan_type == "Prepaid"
				? $plan->prepaid_billing_value
				: $plan->per_delivery_order_frequency_value;
			$delivery_interval = strtoupper($plan->per_delivery_order_frequency_type);
			$delivery_intervalCount = $plan->per_delivery_order_frequency_value;
			$plan_name = $plan->frequency_plan_name;
			$options = "Delivery every {$delivery_intervalCount} {$delivery_interval}(s) and Billing every {$billing_intervalCount} {$billing_interval} ({$plan->frequency_plan_type})";
			$mainGroupOption .= ($index > 1 ? ',' : '') . $options;
			$total_sellling_plans_names .= ($index > 1 ? ',' : '') . $plan->per_delivery_order_frequency_value . ' ' . $plan->per_delivery_order_frequency_type;
			// Default Policies
			$billingPolicy = [
				'recurring' => [
					'interval' => $billing_interval,
					'intervalCount' => (int)$billing_intervalCount,
					'minCycles' => !empty($plan->minimum_number_cycle) ? (int)$plan->minimum_number_cycle : 1,
				]
			];
			if (!empty($plan->maximum_number_cycle)) {
				$billingPolicy['recurring']['maxCycles'] = (int)$plan->maximum_number_cycle;
			}
			$deliveryPolicy = [
				'recurring' => [
					'intent' => 'FULFILLMENT_BEGIN',
					'interval' => $delivery_interval,
					'intervalCount' => (int)$delivery_intervalCount,
					'preAnchorBehavior' => 'ASAP',
				]
			];
			// Anchors and Cutoffs
			if (($data_from === 'backend_subscription' && property_exists($plan, 'sd_set_anchor_date')) ||
				($data_from === 'product_app_extension' && $plan->sd_set_anchor_date === 'on')
			) {
				$anchorType = $plan->per_delivery_order_frequency_type !== 'DAY'
					? $plan->per_delivery_order_frequency_type . 'DAY'
					: $plan->per_delivery_order_frequency_type;
				if ($plan->sd_anchor_option === 'On Specific Day') {
					if ($plan->sd_anchor_month_day !== '') {
						$anchorDay = (int)$plan->sd_anchor_month_day;
					} else {
						$anchorDay = array_search($plan->sd_anchor_week_day, $this->weekDaysArray) + 1;
					}
					$anchor = ['day' => $anchorDay, 'type' => $anchorType];
					$billingPolicy['recurring']['anchors'] = [$anchor];
					$deliveryPolicy['recurring']['anchors'] = [$anchor];
				}
				if (!empty($plan->cut_off_days)) {
					$deliveryPolicy['recurring']['cutoff'] = (int)$plan->cut_off_days;
				}
			}
		
			// Pricing Policies
			$pricingPolicies = [];
			if (
				($data_from === 'backend_subscription' &&
					(!empty($plan->subscription_discount_value) || (($plan->offer_trial_status ?? null) === 'on'))) ||
				($data_from === 'product_app_extension' && (($plan->subscription_discount ?? null) === 'on'))
			) {
				$adjustmentType = $plan->subscription_discount_type === 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
				$valueKey = $adjustmentType === 'PERCENTAGE' ? 'percentage' : 'fixedValue';
				$discountValue = (float)$plan->subscription_discount_value;
				$pricingPolicies[] = [
					'fixed' => [
						'adjustmentType' => $adjustmentType,
						'adjustmentValue' => [
							$valueKey => $discountValue
						]
					]
				];
				// if (
				// 	($data_from === 'backend_subscription' &&
				// 		($plan->subscription_discount_after === 'on' || $plan->offer_trial_status === 'on')) ||
				// 	($data_from === 'product_app_extension' && $plan->subscription_discount_after === 'on')
				// )
	    if	(
						($data_from === 'backend_subscription' &&
								(
										(isset($plan->subscription_discount_after) && (($plan->subscription_discount_after ?? null) === 'on')) ||
										(isset($plan->offer_trial_status) && (($plan->offer_trial_status ?? null) === 'on'))
								)
						) ||
						($data_from === 'product_app_extension' &&
								isset($plan->subscription_discount_after) &&
								(($plan->subscription_discount_after ?? null) === 'on')
						)
				)
				 {
					$discountAfterValue = (float)$plan->discount_value_after;
					$afterType = $plan->subscription_discount_type_after === 'Percent Off(%)' ? 'PERCENTAGE' : 'FIXED_AMOUNT';
					$afterValueKey = $afterType === 'PERCENTAGE' ? 'percentage' : 'fixedValue';
					$afterCycle = (int)$plan->change_discount_after_cycle;
					$pricingPolicies[] = [
						'recurring' => [
							'adjustmentType' => $afterType,
							'adjustmentValue' => [
								$afterValueKey => $discountAfterValue
							],
							'afterCycle' => $afterCycle
						]
					];
				}
			}
			$sellingPlan = [
				'name' => $plan_name,
				'options' => $options,
				'position' => $index,
				'category' => 'SUBSCRIPTION',
				'inventoryPolicy' => [
					'reserve' => 'ON_FULFILLMENT'
				],
				'billingPolicy' => $billingPolicy,
				'deliveryPolicy' => $deliveryPolicy,
				'description' => trim($plan->sd_description ?? '', ''),
				'pricingPolicies' => $pricingPolicies
			];
			if ($mode === 'update' && !empty($plan->sellingplanid)) {
				$sellingPlan['id'] = "gid://shopify/SellingPlan/" . $plan->sellingplanid;
			}
			$sellingPlans[] = $sellingPlan;
			$index++;
		}
		return [
			'selling_plans' => $sellingPlans,
			'total_sellling_plans_names' => $total_sellling_plans_names,
			'main_group_option' => $mainGroupOption
		];
	}


	public function addMultipleGroupToVariant($variant_id, $subscription_id_array)
	{

		$add_multiple_group = 'mutation {

			productVariantJoinSellingPlanGroups(

			id: "' . $variant_id . '"

			sellingPlanGroupIds: ["' . $subscription_id_array . '"]

			) {

			productVariant {

				id

			}

			userErrors {

				field

				message

			}

			}

		}';

		return $this->graphqlQuery($add_multiple_group, null, null, null);
	}



	public function addMultipleSubscriptionGroup($data)
	{

		$subscriptionGroup_id_array = implode('","', $data['group_id_array']);

		$subscription_plan_product_insert_values = array();

		$product_id = str_replace("gid://shopify/Product/", "", $data['product_id']);

		if (!array_key_exists('variant_id', $data)) {

			$product_variant_ids_data = $this->getProductData('["' . $data['product_id'] . '"]');

			$product_variant_ids = $product_variant_ids_data['data']['nodes'][0]['variants']['edges'];

			foreach ($product_variant_ids as $key => $variant_data) {

				try {

					$add_multiple_group_execution = $this->addMultipleGroupToVariant($variant_data['node']['id'], $subscriptionGroup_id_array);

					$add_multiple_group_error = $add_multiple_group_execution['data']['productVariantJoinSellingPlanGroups']['userErrors'];

					if (!count($add_multiple_group_error)) {

						foreach ($data['group_id_array'] as $key => $value) {

							$single_subscription_plan_product = array();

							$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $value);

							$variant_id = str_replace("gid://shopify/ProductVariant/", "", $variant_data['node']['id']);

							$product_title = $product_variant_ids_data['data']['nodes'][0]['title'];

							$variant_title = $variant_data['node']['title'];

							$variant_image_array = $variant_data['node']['image'];

							if (empty($product_variant_ids_data['data']['nodes'][0]['image'])) {

								if (empty($product_variant_ids_data['data']['nodes'][0]['featuredImage'])) {

									$variant_image = $this->image_folder . 'no-image.png'; // add image folder path on live

								} else {

									$variant_image =  $product_variant_ids_data['data']['nodes'][0]['featuredImage']['originalSrc'];
								}
							} else {

								$variant_image =  $variant_data['node']['image']['originalSrc'];
							}

							array_push($single_subscription_plan_product, $this->store_id, $sellingPlanGroupId, $product_id, $variant_id, htmlspecialchars($product_title, ENT_QUOTES), htmlspecialchars($variant_title, ENT_QUOTES), $variant_image, $this->created_at);

							array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
						}
					} else {

						return json_encode(array("status" => false, 'error' => 'mutation execution error', 'message' => 'mutation execution error')); // return json

					}
				} catch (Exception $e) {

					return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'mutation execution error')); // return json

				}
			}
		} else {

			$add_multiple_group_execution = $this->addMultipleGroupToVariant($data['variant_id'], $subscriptionGroup_id_array);

			$getVariantData = $this->getVariantDetail('["' . $data['variant_id'] . '"]');

			foreach ($data['group_id_array'] as $key => $value) {

				$single_subscription_plan_product = array();

				$variant_id =  str_replace("gid://shopify/ProductVariant/", "", $data['variant_id']);

				$product_title = $getVariantData['data']['nodes'][0]['product']['title'];

				$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $value);

				$variant_title = $getVariantData['data']['nodes'][0]['title'];

				if (empty($getVariantData['data']['nodes'][0]['image'])) {

					if (empty($getVariantData['data']['nodes'][0]['featuredImage'])) {

						$variant_image = $this->image_folder . 'no-image.png'; // add image folder path on live

					} else {

						$variant_image = $getVariantData['data']['nodes'][0]['product']['featuredImage']['originalSrc'];
					}
				} else {

					$variant_image = $getVariantData['data']['nodes'][0]['featuredImage']['originalSrc'];
				}

				array_push($single_subscription_plan_product, $this->store_id, $sellingPlanGroupId, $product_id, $variant_id, htmlspecialchars($product_title, ENT_QUOTES), htmlspecialchars($variant_title, ENT_QUOTES), $variant_image, $this->created_at);

				array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
			}
		}

		try {

			$subscription_plan_product_insert_fields = array("store_id", "subscription_plan_group_id", "product_id", "variant_id", "product_name", "variant_name", "Imagepath", "created_at");

			return	$this->multiple_insert_update_row('subscriptionPlanGroupsProducts', $subscription_plan_product_insert_fields, $subscription_plan_product_insert_values);
		} catch (Exception $e) {

			return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Group Products Insert error')); // return json

		}
	}



	// public function create_subscription($data, $data_from)
	// {

	// 	// print_r($data);
	// 	// die();

	// 	$total_sellling_plans_names = '';

	// 	$whereCondition = array(

	// 		'store_id' => $this->store_id,

	// 		'plan_name' => $data['plan_name']

	// 	);
	// 	// print_r($whereCondition);die;
	// 	$checkSameNamePlanExist = $this->table_row_check('subscriptionPlanGroups', $whereCondition, 'and');

	// 	if ($checkSameNamePlanExist) {

	// 		return json_encode(array("status" => false, 'error' => 'Same Plan Group Name error', 'message' => 'Plan Group with same name already exist')); // return json

	// 	}

	// 	$get_last_group_position = $this->customQuery("select group_position from subscriptionPlanGroups order by group_position desc limit 0,1");

	// 	if (empty($get_last_group_position)) {

	// 		$group_position = 1;
	// 	} else {

	// 		$group_position = intval($get_last_group_position[0]['group_position']) + 1;
	// 	}

	// 	$subscription_plan_product_insert_fields = array("store_id", "subscription_plan_group_id", "product_id", "variant_id", "product_name", "variant_name", "Imagepath", "created_at");

	// 	$subscriptionPlanGroupsDetails_fields = array("store_id", "subscription_plan_group_id", "selling_plan_id", "plan_type", "plan_name", "plan_description", "delivery_policy", "billing_policy", "delivery_billing_type", "discount_offer", "discount_value", "discount_type", "recurring_discount_offer", "discount_value_after", "discount_type_after", "change_discount_after_cycle", "min_cycle", "max_cycle", "anchor_day", "anchor_type", "cut_off_days", "created_at", "offer_trial_status", "trial_period_value", "trial_period_type", "renew_on_original_date");

	// 	$subscription_plan_product_insert_values = array();

	// 	$subscriptionPlanGroupsDetails_values = array();



	// 	$productIds = $data['product_ids'];

	// 	$subscriptionplansdata	= $data['frequency_plan'];

	// 	$plan_name	= $data['plan_name'];

	// 	$store_id	=  $this->store_id;



	// 	//product app extesnion

	// 	if ($data_from == 'backend_subscription') {

	// 		$frequency_plans = json_decode($subscriptionplansdata);

	// 		$product_ids = json_decode($productIds);
	// 	} else {

	// 		$frequency_plans = $subscriptionplansdata;

	// 		$product_ids = $productIds;
	// 	}

	// 	$sellingPlansToCreate = $this->create_frequency_plans_array($frequency_plans, "create", '', $data_from);
	// 	// print_r($sellingPlansToCreate);die;

	// 	// if ($this->store == 'test-store-phoenixt.myshopify.com') {
	// 	// 	print_r($sellingPlansToCreate);
	// 	// 	die;
	// 	// }

	// 	if ($data_from == 'backend_subscription') {

	// 		$i = 0;

	// 		$product_params = "[";

	// 		foreach ($product_ids as $key => $product_id) {

	// 			if ($i > 0) {

	// 				$product_params .= ',';
	// 			}

	// 			$product_params .= '"gid://shopify/ProductVariant/' . $product_id->variant_id . '"';

	// 			$i++;
	// 		}

	// 		$product_params .= "]";
	// 	} else {

	// 		$i = 0;

	// 		if (array_key_exists("variant_ids", $data) &&  $data['variant_ids'] != '') {

	// 			$product_params = '["' . $data['variant_ids'] . '"]';
	// 		} else {

	// 			$product_variant_ids_data = $this->getProductVariant($data['product_ids']);

	// 			$product_variant_ids = $product_variant_ids_data['data']['product']['variants']['edges'];

	// 			$product_params = "[";

	// 			foreach ($product_variant_ids as $key => $variant_id) {

	// 				if ($i > 0) {

	// 					$product_params .= ',';
	// 				}

	// 				$product_params .= '"' . $variant_id['node']['id'] . '"';

	// 				$i++;
	// 			}

	// 			$product_params .= "]";
	// 		}
	// 	}

	// 	$unique_option = rand(pow(10, 3 - 1), pow(10, 3) - 1);

	// 	try {

	// 		$graphQL_sellingPlanGroupCreate = 'mutation {

	// 			  sellingPlanGroupCreate(

	// 					input: {

	// 					  name: "' . addslashes($data['plan_name']) . '"

	// 					  merchantCode: "' . addslashes($data['plan_name']) . '"

	// 					  options: ["' . $sellingPlansToCreate['main_group_option'] . '"]

	// 					  position: ' . $group_position . '

	// 					  sellingPlansToCreate: ' . $sellingPlansToCreate['selling_plans'] . '

	// 					  appId:  "a7rYz8vMnx43qJdpF5zLm9k1wXbQp0V7wKhG"

	// 					}

	// 					resources: { productIds: [], productVariantIds: ' . $product_params . '}

	// 				) {

	// 				sellingPlanGroup {

	// 				  id

	// 				  appId

	// 				  sellingPlans(first: 20){

	// 					edges{

	// 						node{

	// 							id

	// 							name

	// 						}

	// 					}

	// 				  }

	// 				}

	// 				userErrors {

	// 				  field

	// 				  message

	// 				}

	// 			  }

	// 			}';
	// 		// echo $graphQL_sellingPlanGroupCreate;die;	


	// 		$sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate);

	// 		// if($this->store == 'test-store-phoenixt.myshopify.com'){
	// 		//     echo $sellingPlanGroupCreateapi_execution;
	// 		// }

	// 		$sellingPlanGroupCreateapi_error = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['userErrors'];
	// 	} catch (Exception $e) {

	// 		return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'mutation execution error')); // return json

	// 	}

	// 	if (!count($sellingPlanGroupCreateapi_error)) {

	// 		$list_card_all_li = '';
	// 		$data_search_product = '';

	// 		$sellingPlanGroupId_complete = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['id'];

	// 		$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupId_complete);

	// 		$subscription_plan_table_insert_values =  array(

	// 			"subscription_plangroup_id" => $sellingPlanGroupId,

	// 			"store_id " => $store_id,

	// 			"plan_name" => htmlspecialchars($plan_name, ENT_QUOTES),

	// 			"group_position" => $group_position,

	// 			"created_at" => $this->created_at

	// 		);

	// 		$create_list_card_html = '';

	// 		if ($data_from == 'backend_subscription') {

	// 			$product_li_counter = 1;

	// 			foreach ($product_ids as $key => $product_id) {

	// 				//for db_entry because we get sellingPlanGroupId after mutation otherwise it could be adjusted in above loop

	// 				$single_subscription_plan_product = array();

	// 				array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id->product_id, $product_id->variant_id, $product_id->product_title, $product_id->variant_title, $product_id->image, $this->created_at);

	// 				array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);

	// 				//list_card_li - show only first 5

	// 				if ($product_li_counter < 5) {

	// 					$list_card_all_li .= '<li class="Polaris-ResourceItem__ListItem">

	// 							<div class="Polaris-ResourceItem__ItemWrapper">

	// 								<div class="Polaris-ResourceItem">

	// 									<div class="Polaris-ResourceItem__Container">

	// 										<div class="Polaris-ResourceItem__Owned">

	// 										<div class="Polaris-ResourceItem__Media"><span class="Polaris-Thumbnail Polaris-Thumbnail--sizeMedium"><img src="' . $product_id->image . '" ></span></div>

	// 										</div>

	// 									</div>

	// 								</div>

	// 							</div>

	// 							</li>';
	// 				}

	// 				//list_card_search_parameters

	// 				$data_search_product .= $product_id->product_title . ' ' . $product_id->variant_title;

	// 				$product_li_counter++;
	// 			}
	// 		} else { // for product app extension

	// 			$product_id = str_replace("gid://shopify/Product/", "", $product_ids);

	// 			if (!(array_key_exists("variant_ids", $data))) {

	// 				$getProductData = $this->getProductData('["' . $productIds . '"]');

	// 				$product_variant_array = $getProductData['data']['nodes'][0]['variants']['edges'];

	// 				$product_image =  $getProductData['data']['nodes'][0]['featuredImage'];

	// 				$product_title = $getProductData['data']['nodes'][0]['title'];



	// 				foreach ($product_variant_array as $prd_variant) {

	// 					$single_subscription_plan_product = array();

	// 					$variant_id =  str_replace("gid://shopify/ProductVariant/", "", $prd_variant['node']['id']);

	// 					$variant_image_array = $prd_variant['node']['image'];

	// 					if (empty($variant_image_array)) {

	// 						if (empty($product_image)) {

	// 							$variant_image = $this->image_folder . 'no-image.png'; // add image folder path on live

	// 						} else {

	// 							$variant_image =  $product_image['originalSrc'];
	// 						}
	// 					} else {

	// 						$variant_image =  $variant_image_array['originalSrc'];
	// 					}

	// 					$variant_title = $prd_variant['node']['title'];

	// 					array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id, $variant_id, htmlspecialchars($product_title, ENT_QUOTES), htmlspecialchars($variant_title, ENT_QUOTES), $variant_image, $this->created_at);

	// 					array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
	// 				}
	// 			} else {

	// 				$single_subscription_plan_product = array();

	// 				$getVariantData = $this->getVariantDetail('["' . $data['variant_ids'] . '"]');

	// 				$variant_id =  str_replace("gid://shopify/ProductVariant/", "", $data['variant_ids']);

	// 				$product_title = $getVariantData['data']['nodes'][0]['product']['title'];

	// 				$variant_title = $getVariantData['data']['nodes'][0]['title'];

	// 				if (empty($getVariantData['data']['nodes'][0]['image'])) {

	// 					if (empty($getVariantData['data']['nodes'][0]['featuredImage'])) {

	// 						$variant_image =  $this->image_folder . 'no-image.png'; // add image folder path on live

	// 					} else {

	// 						$variant_image = $getVariantData['data']['nodes'][0]['product']['featuredImage']['originalSrc'];
	// 					}
	// 				} else {

	// 					$variant_image = $getVariantData['data']['nodes'][0]['featuredImage']['originalSrc'];
	// 				}

	// 				array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id, $variant_id, htmlspecialchars($product_title, ENT_QUOTES), htmlspecialchars($variant_title, ENT_QUOTES), $variant_image, $this->created_at);

	// 				array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
	// 			}
	// 		}

	// 		try {

	// 			$subscription_group_db_id = $this->insert_row('subscriptionPlanGroups', $subscription_plan_table_insert_values);
	// 		} catch (Exception $e) {

	// 			return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Insert error')); // return json

	// 		}



	// 		try {

	// 			$this->multiple_insert_update_row('subscriptionPlanGroupsProducts', $subscription_plan_product_insert_fields, $subscription_plan_product_insert_values);
	// 		} catch (Exception $e) {

	// 			return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Group Products Insert error')); // return json

	// 		}

	// 		$sellingplans = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['sellingPlans']['edges'];

	// 		foreach ($sellingplans as $key => $sellingplan) {

	// 			$single_subscriptionPlanGroupsDetail = array();

	// 			if (is_array($frequency_plans[$key])) {

	// 				$frequencyPlan_index = (object)($frequency_plans[$key]);
	// 			} else {

	// 				$frequencyPlan_index = $frequency_plans[$key];
	// 			}

	// 			if (property_exists($frequencyPlan_index, 'offer_trial_status') && $frequencyPlan_index->offer_trial_status == "on") {
	// 				$offer_trial_status = 1;
	// 			} else {
	// 				$offer_trial_status = 0;
	// 			}
	// 			if (property_exists($frequencyPlan_index, 'renew_original_date') && $frequencyPlan_index->renew_original_date == "true") {
	// 				$renew_on_original_date = 1;
	// 			} else {
	// 				$renew_on_original_date = 0;
	// 			}


	// 			if (($data_from == 'backend_subscription' && property_exists($frequencyPlan_index, 'subscription_discount')) || ($data_from == 'product_app_extension' && $frequencyPlan_index->subscription_discount == 'on') || $frequencyPlan_index->offer_trial_status == "on") {
	// 				$subscription_discount = 1;
	// 			} else {

	// 				$subscription_discount = 0;
	// 			}

	// 			if ($frequencyPlan_index->frequency_plan_type == 'Prepaid') {

	// 				$plan_type = 2;
	// 			} else {

	// 				$plan_type = 1;
	// 			}

	// 			if ($frequencyPlan_index->cut_off_days != '') {

	// 				$cut_off_days = $frequencyPlan_index->cut_off_days;
	// 			} else {

	// 				$cut_off_days = 0;
	// 			}

	// 			$anchor_day = 0;

	// 			$anchor_type = '2';

	// 			if (($data_from == 'backend_subscription' && property_exists($frequencyPlan_index, 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $frequencyPlan_index->sd_set_anchor_date == 'on')) {

	// 				if ($frequencyPlan_index->sd_anchor_option ==  'On Purchase Day') {

	// 					$anchor_type = '0';
	// 				} else {

	// 					$anchor_type = '1';

	// 					if ($frequencyPlan_index->sd_anchor_month_day == '') {

	// 						$anchor_day = array_search($frequencyPlan_index->sd_anchor_week_day, $this->weekDaysArray) + 1;
	// 					} else {

	// 						$anchor_day =  $frequencyPlan_index->sd_anchor_month_day;
	// 					}
	// 				}
	// 			}



	// 			if (empty($frequencyPlan_index->prepaid_billing_value)) {

	// 				$billing_value = $frequencyPlan_index->per_delivery_order_frequency_value;
	// 			} else {

	// 				$billing_value = $frequencyPlan_index->prepaid_billing_value;
	// 			}

	// 			if (empty($frequencyPlan_index->subscription_discount_value)) {

	// 				$discount_value = 0;
	// 			} else {

	// 				$discount_value = $frequencyPlan_index->subscription_discount_value;
	// 			}

	// 			if ($frequencyPlan_index->subscription_discount_type == 'Percent Off(%)') {

	// 				$discount_type = "P";
	// 			} else {

	// 				$discount_type = "A";
	// 			}



	// 			if (empty($frequencyPlan_index->minimum_number_cycle)) {

	// 				$minCycle = 1;
	// 			} else {

	// 				$minCycle = $frequencyPlan_index->minimum_number_cycle;
	// 			}

	// 			if (empty($frequencyPlan_index->maximum_number_cycle)) {

	// 				$maxCycle = 0;

	// 				$max_cycle_mutation = '';
	// 			} else {

	// 				$maxCycle = $frequencyPlan_index->maximum_number_cycle;

	// 				$max_cycle_mutation = ", maxCycles : " . $maxCycle;
	// 			}

	// 			// recurring discount after cycle

	// 			// if (property_exists($frequencyPlan_index , 'subscription_discount_after')) {

	// 			if (($data_from == 'backend_subscription' && property_exists($frequencyPlan_index, 'subscription_discount_after')) || ($data_from == 'product_app_extension' && $frequencyPlan_index->subscription_discount_after == 'on') || $frequencyPlan_index->offer_trial_status == "on") {

	// 				$subscription_discount_after = 1;
	// 			} else {

	// 				$subscription_discount_after = 0;
	// 			}

	// 			if (empty($frequencyPlan_index->discount_value_after)) {

	// 				$discount_value_after = 0;
	// 			} else {

	// 				$discount_value_after = $frequencyPlan_index->discount_value_after;
	// 			}

	// 			if ($frequencyPlan_index->subscription_discount_type_after == 'Percent Off(%)') {

	// 				$discount_type_after = "P";
	// 			} else {

	// 				$discount_type_after = "A";
	// 			}

	// 			if (empty($frequencyPlan_index->change_discount_after_cycle)) {

	// 				$change_discount_after_cycle = 0;
	// 			} else {

	// 				$change_discount_after_cycle =  $frequencyPlan_index->change_discount_after_cycle;
	// 			}



	// 			$selling_plan_id = str_replace("gid://shopify/SellingPlan/", "", $sellingplan['node']['id']);

	// 			// array_push($single_subscriptionPlanGroupsDetail,$store_id,$sellingPlanGroupId,$selling_plan_id,$plan_type,htmlspecialchars($frequencyPlan_index->frequency_plan_name, ENT_QUOTES),htmlspecialchars($frequencyPlan_index->sd_description, ENT_QUOTES),$frequencyPlan_index->per_delivery_order_frequency_value,$billing_value,$frequencyPlan_index->per_delivery_order_frequency_type,$subscription_discount,$discount_value,$discount_type,$subscription_discount_after,$discount_value_after,$discount_type_after,$change_discount_after_cycle,$minCycle,$maxCycle,$anchor_day,$anchor_type,$cut_off_days,$this->created_at);


	// 			array_push($single_subscriptionPlanGroupsDetail, $store_id, $sellingPlanGroupId, $selling_plan_id, $plan_type, htmlspecialchars($frequencyPlan_index->frequency_plan_name, ENT_QUOTES), htmlspecialchars($frequencyPlan_index->sd_description, ENT_QUOTES), $frequencyPlan_index->per_delivery_order_frequency_value, $billing_value, $frequencyPlan_index->per_delivery_order_frequency_type, $subscription_discount, $discount_value, $discount_type, $subscription_discount_after, $discount_value_after, $discount_type_after, $change_discount_after_cycle, $minCycle, $maxCycle, $anchor_day, $anchor_type, $cut_off_days, $this->created_at, $offer_trial_status, $frequencyPlan_index->offer_trial_period_value, $frequencyPlan_index->offer_trial_period_type, $renew_on_original_date);

	// 			array_push($subscriptionPlanGroupsDetails_values, $single_subscriptionPlanGroupsDetail);
	// 		}



	// 		try {


	// 			$this->multiple_insert_update_row('subscriptionPlanGroupsDetails', $subscriptionPlanGroupsDetails_fields, $subscriptionPlanGroupsDetails_values);
	// 		} catch (Exception $e) {

	// 			return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Details Insert error')); // return json

	// 		}



	// 		if ($data_from == 'backend_subscription') {

	// 			$selling_plan_create_card_array = array(

	// 				'plan_name' => $plan_name,

	// 				'selling_plan_group_id' => $sellingPlanGroupId,

	// 				'product_id_array' => $product_ids,

	// 				'total_selling_plan_count' => count((array)$sellingplans),

	// 				'total_sellling_plans_names' => $sellingPlansToCreate['total_sellling_plans_names'],

	// 			);

	// 			$create_list_card_html = $this->create_plan_list_card($selling_plan_create_card_array);
	// 		}

	// 		$result = array('status' => true, 'message' => "Subscription Created Successfully", 'list_card_html' => $create_list_card_html, 'selling_plan_group_id' => $sellingPlanGroupId, 'selling_plan_list_card_array' => $selling_plan_create_card_array);

	// 		return json_encode($result); // return json

	// 	} else {

	// 		if (is_array($sellingPlanGroupCreateapi_error)) {

	// 			if (strpos($sellingPlanGroupCreateapi_error[0]['message'], 'does not exist') !== false) {

	// 				return json_encode(array("status" => false, 'error' => json_encode($sellingPlanGroupCreateapi_error), 'message' => 'Some of the selected product does not exist in the shopify store')); // return json

	// 			}
	// 		} else {

	// 			return json_encode(array("status" => false, 'error' => $sellingPlanGroupCreateapi_error)); // return json

	// 		}
	// 	}
	// }

	public function create_subscription($data, $data_from)
	{
		// print_r($data);
		// die();
		$total_sellling_plans_names = '';
		$whereCondition = array(
			'store_id' => $this->store_id,
			'plan_name' => $data['plan_name']
		);
		// print_r($whereCondition);die;
		$checkSameNamePlanExist = $this->table_row_check('subscriptionPlanGroups', $whereCondition, 'and');
		if ($checkSameNamePlanExist) {
			return json_encode(array("status" => false, 'error' => 'Same Plan Group Name error', 'message' => 'Plan Group with same name already exist')); // return json
		}
		$get_last_group_position = $this->customQuery("select group_position from subscriptionPlanGroups order by group_position desc limit 0,1");
		if (empty($get_last_group_position)) {
			$group_position = 1;
		} else {
			$group_position = intval($get_last_group_position[0]['group_position']) + 1;
		}
		$subscription_plan_product_insert_fields = array("store_id", "subscription_plan_group_id", "product_id", "variant_id", "product_name", "variant_name", "Imagepath", "created_at");
		$subscriptionPlanGroupsDetails_fields = array("store_id", "subscription_plan_group_id", "selling_plan_id", "plan_type", "plan_name", "plan_description", "delivery_policy", "billing_policy", "delivery_billing_type", "discount_offer", "discount_value", "discount_type", "recurring_discount_offer", "discount_value_after", "discount_type_after", "change_discount_after_cycle", "min_cycle", "max_cycle", "anchor_day", "anchor_type", "cut_off_days", "created_at", "offer_trial_status", "trial_period_value", "trial_period_type", "renew_on_original_date");
		$subscription_plan_product_insert_values = array();
		$subscriptionPlanGroupsDetails_values = array();
		$productIds = $data['product_ids'];
		$subscriptionplansdata	= $data['frequency_plan'];
		$plan_name	= $data['plan_name'];
		$store_id	=  $this->store_id;
		//product app extesnion
		if ($data_from == 'backend_subscription') {
			$frequency_plans = json_decode($subscriptionplansdata);
			$product_ids = json_decode($productIds);
		} else {
			$frequency_plans = $subscriptionplansdata;
			$product_ids = $productIds;
		}
		// echo('hlw');
		$sellingPlansToCreate = $this->create_frequency_plans_array($frequency_plans, "create", '', $data_from);
		// print_r($sellingPlansToCreate);
		// die;
		if ($data_from == 'backend_subscription') {
			$i = 0;
			$product_params = "[";
			foreach ($product_ids as $key => $product_id) {
				if ($i > 0) {
					$product_params .= ',';
				}
				$product_params .= '"gid://shopify/ProductVariant/' . $product_id->variant_id . '"';
				$i++;
			}
			$product_params .= "]";
		} else {
			$i = 0;
			if (array_key_exists("variant_ids", $data) &&  $data['variant_ids'] != '') {
				$product_params = '["' . $data['variant_ids'] . '"]';
			} else {
				$product_variant_ids_data = $this->getProductVariant($data['product_ids']);
				$product_variant_ids = $product_variant_ids_data['data']['product']['variants']['edges'];
				$product_params = "[";
				foreach ($product_variant_ids as $key => $variant_id) {
					if ($i > 0) {
						$product_params .= ',';
					}
					$product_params .= '"' . $variant_id['node']['id'] . '"';
					$i++;
				}
				$product_params .= "]";
			}
		}
		$unique_option = rand(pow(10, 3 - 1), pow(10, 3) - 1);
		try {
			$graphQL_sellingPlanGroupCreate = '
					mutation sellingPlanGroupCreate($input: SellingPlanGroupInput!, $resources: SellingPlanGroupResourceInput!) {
					sellingPlanGroupCreate(input: $input, resources: $resources) {
						sellingPlanGroup {
						id
						appId
						sellingPlans(first: 20) {
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
					}';
			$variables = [
				'input' => [
					'name' => $data['plan_name'],
					'merchantCode' => $data['plan_name'],
					'options' => [$sellingPlansToCreate['main_group_option']],
					'position' => $group_position,
					'sellingPlansToCreate' => $sellingPlansToCreate['selling_plans'], // must be an array
					'appId' => 'a7rYz8vMnx43qJdpF5zLm9k1wXbQp0V7wKhG'
				],
				'resources' => [
					'productIds' => [],
					'productVariantIds' => json_decode($product_params, true) // must be an array of IDs
				]
			];
			// echo $graphQL_sellingPlanGroupCreate;die;	
			// if($this->store == 'predictive-search.myshopify.com'){
			//       echo $graphQL_sellingPlanGroupCreate;
			// }
			$sellingPlanGroupCreateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupCreate, null, null, $variables);
			// print_r($sellingPlanGroupCreateapi_execution);die;
			$sellingPlanGroupCreateapi_error = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['userErrors'];
		} catch (Exception $e) {
			return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'mutation execution error')); // return json
		}
		if (!count($sellingPlanGroupCreateapi_error)) {
			$list_card_all_li = '';
			$data_search_product = '';
			$sellingPlanGroupId_complete = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['id'];
			$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupId_complete);
			$subscription_plan_table_insert_values =  array(
				"subscription_plangroup_id" => $sellingPlanGroupId,
				"store_id " => $store_id,
				"plan_name" => htmlspecialchars($plan_name, ENT_QUOTES),
				"group_position" => $group_position,
				"created_at" => $this->created_at
			);
			$create_list_card_html = '';
			if ($data_from == 'backend_subscription') {
				$product_li_counter = 1;
				foreach ($product_ids as $key => $product_id) {
					//for db_entry because we get sellingPlanGroupId after mutation otherwise it could be adjusted in above loop
					$single_subscription_plan_product = array();
					array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id->product_id, $product_id->variant_id, $product_id->product_title, $product_id->variant_title, $product_id->image, $this->created_at);
					array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
					//list_card_li - show only first 5
					if ($product_li_counter < 5) {
						$list_card_all_li .= '<li class="Polaris-ResourceItem__ListItem">
								<div class="Polaris-ResourceItem__ItemWrapper">
									<div class="Polaris-ResourceItem">
										<div class="Polaris-ResourceItem__Container">
											<div class="Polaris-ResourceItem__Owned">
											<div class="Polaris-ResourceItem__Media"><span class="Polaris-Thumbnail Polaris-Thumbnail--sizeMedium"><img src="' . $product_id->image . '" ></span></div>
											</div>
										</div>
									</div>
								</div>
								</li>';
					}
					//list_card_search_parameters
					$data_search_product .= $product_id->product_title . ' ' . $product_id->variant_title;
					$product_li_counter++;
				}
			} else { // for product app extension
				$product_id = str_replace("gid://shopify/Product/", "", $product_ids);
				if (!(array_key_exists("variant_ids", $data))) {
					$getProductData = $this->getProductData('["' . $productIds . '"]');
					$product_variant_array = $getProductData['data']['nodes'][0]['variants']['edges'];
					$product_image =  $getProductData['data']['nodes'][0]['featuredImage'];
					$product_title = $getProductData['data']['nodes'][0]['title'];
					foreach ($product_variant_array as $prd_variant) {
						$single_subscription_plan_product = array();
						$variant_id =  str_replace("gid://shopify/ProductVariant/", "", $prd_variant['node']['id']);
						$variant_image_array = $prd_variant['node']['image'];
						if (empty($variant_image_array)) {
							if (empty($product_image)) {
								$variant_image = $this->image_folder . 'no-image.png'; // add image folder path on live
							} else {
								$variant_image =  $product_image['originalSrc'];
							}
						} else {
							$variant_image =  $variant_image_array['originalSrc'];
						}
						$variant_title = $prd_variant['node']['title'];
						array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id, $variant_id, htmlspecialchars($product_title, ENT_QUOTES), htmlspecialchars($variant_title, ENT_QUOTES), $variant_image, $this->created_at);
						array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
					}
				} else {
					$single_subscription_plan_product = array();
					$getVariantData = $this->getVariantDetail('["' . $data['variant_ids'] . '"]');
					$variant_id =  str_replace("gid://shopify/ProductVariant/", "", $data['variant_ids']);
					$product_title = $getVariantData['data']['nodes'][0]['product']['title'];
					$variant_title = $getVariantData['data']['nodes'][0]['title'];
					if (empty($getVariantData['data']['nodes'][0]['image'])) {
						if (empty($getVariantData['data']['nodes'][0]['featuredImage'])) {
							$variant_image =  $this->image_folder . 'no-image.png'; // add image folder path on live
						} else {
							$variant_image = $getVariantData['data']['nodes'][0]['product']['featuredImage']['originalSrc'];
						}
					} else {
						$variant_image = $getVariantData['data']['nodes'][0]['featuredImage']['originalSrc'];
					}
					array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id, $variant_id, htmlspecialchars($product_title, ENT_QUOTES), htmlspecialchars($variant_title, ENT_QUOTES), $variant_image, $this->created_at);
					array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
				}
			}
			try {
				$subscription_group_db_id = $this->insert_row('subscriptionPlanGroups', $subscription_plan_table_insert_values);
			} catch (Exception $e) {
				return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Insert error')); // return json
			}
			try {
				$this->multiple_insert_update_row('subscriptionPlanGroupsProducts', $subscription_plan_product_insert_fields, $subscription_plan_product_insert_values);
			} catch (Exception $e) {
				return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Group Products Insert error')); // return json
			}
			$sellingplans = $sellingPlanGroupCreateapi_execution['data']['sellingPlanGroupCreate']['sellingPlanGroup']['sellingPlans']['edges'];
			foreach ($sellingplans as $key => $sellingplan) {
				$single_subscriptionPlanGroupsDetail = array();
				if (is_array($frequency_plans[$key])) {
					$frequencyPlan_index = (object)($frequency_plans[$key]);
				} else {
					$frequencyPlan_index = $frequency_plans[$key];
				}
				if (property_exists($frequencyPlan_index, 'offer_trial_status') && $frequencyPlan_index->offer_trial_status == "on") {
					$offer_trial_status = 1;
				} else {
					$offer_trial_status = 0;
				}
				if (property_exists($frequencyPlan_index, 'renew_original_date') && $frequencyPlan_index->renew_original_date == "true") {
					$renew_on_original_date = 1;
				} else {
					$renew_on_original_date = 0;
				}
				if (($data_from == 'backend_subscription' && property_exists($frequencyPlan_index, 'subscription_discount')) || ($data_from == 'product_app_extension' && (isset($frequencyPlan_index->subscription_discount) && $frequencyPlan_index->subscription_discount == 'on')) || (isset($frequencyPlan_index->offer_trial_status) && $frequencyPlan_index->offer_trial_status == "on")) {
					$subscription_discount = 1;
				} else {
					$subscription_discount = 0;
				}
				if ($frequencyPlan_index->frequency_plan_type == 'Prepaid') {
					$plan_type = 2;
				} else {
					$plan_type = 1;
				}
				if ($frequencyPlan_index->cut_off_days != '') {
					$cut_off_days = $frequencyPlan_index->cut_off_days;
				} else {
					$cut_off_days = 0;
				}
				$anchor_day = 0;
				$anchor_type = '2';
				if (($data_from == 'backend_subscription' && property_exists($frequencyPlan_index, 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $frequencyPlan_index->sd_set_anchor_date == 'on')) {
					if ($frequencyPlan_index->sd_anchor_option ==  'On Purchase Day') {
						$anchor_type = '0';
					} else {
						$anchor_type = '1';
						if ($frequencyPlan_index->sd_anchor_month_day == '') {
							$anchor_day = array_search($frequencyPlan_index->sd_anchor_week_day, $this->weekDaysArray) + 1;
						} else {
							$anchor_day =  $frequencyPlan_index->sd_anchor_month_day;
						}
					}
				}
				if (empty($frequencyPlan_index->prepaid_billing_value)) {
					$billing_value = $frequencyPlan_index->per_delivery_order_frequency_value;
				} else {
					$billing_value = $frequencyPlan_index->prepaid_billing_value;
				}
				if (empty($frequencyPlan_index->subscription_discount_value)) {
					$discount_value = 0;
				} else {
					$discount_value = $frequencyPlan_index->subscription_discount_value;
				}
				if ($frequencyPlan_index->subscription_discount_type == 'Percent Off(%)') {
					$discount_type = "P";
				} else {
					$discount_type = "A";
				}
				if (empty($frequencyPlan_index->minimum_number_cycle)) {
					$minCycle = 1;
				} else {
					$minCycle = $frequencyPlan_index->minimum_number_cycle;
				}
				if (empty($frequencyPlan_index->maximum_number_cycle)) {
					$maxCycle = 0;
					$max_cycle_mutation = '';
				} else {
					$maxCycle = $frequencyPlan_index->maximum_number_cycle;
					$max_cycle_mutation = ", maxCycles : " . $maxCycle;
				}
				// recurring discount after cycle
				// if (property_exists($frequencyPlan_index , 'subscription_discount_after')) {
				if (($data_from == 'backend_subscription' && property_exists($frequencyPlan_index, 'subscription_discount_after')) || ($data_from == 'product_app_extension' && (isset($frequencyPlan_index->subscription_discount_after) && $frequencyPlan_index->subscription_discount_after == 'on')) || (isset($frequencyPlan_index->offer_trial_status) && $frequencyPlan_index->offer_trial_status == "on")) {
					$subscription_discount_after = 1;
				} else {
					$subscription_discount_after = 0;
				}
				if (empty($frequencyPlan_index->discount_value_after)) {
					$discount_value_after = 0;
				} else {
					$discount_value_after = $frequencyPlan_index->discount_value_after;
				}
				if ($frequencyPlan_index->subscription_discount_type_after == 'Percent Off(%)') {
					$discount_type_after = "P";
				} else {
					$discount_type_after = "A";
				}
				if (empty($frequencyPlan_index->change_discount_after_cycle)) {
					$change_discount_after_cycle = 0;
				} else {
					$change_discount_after_cycle =  $frequencyPlan_index->change_discount_after_cycle;
				}
				$selling_plan_id = str_replace("gid://shopify/SellingPlan/", "", $sellingplan['node']['id']);
				// array_push($single_subscriptionPlanGroupsDetail,$store_id,$sellingPlanGroupId,$selling_plan_id,$plan_type,htmlspecialchars($frequencyPlan_index->frequency_plan_name, ENT_QUOTES),htmlspecialchars($frequencyPlan_index->sd_description, ENT_QUOTES),$frequencyPlan_index->per_delivery_order_frequency_value,$billing_value,$frequencyPlan_index->per_delivery_order_frequency_type,$subscription_discount,$discount_value,$discount_type,$subscription_discount_after,$discount_value_after,$discount_type_after,$change_discount_after_cycle,$minCycle,$maxCycle,$anchor_day,$anchor_type,$cut_off_days,$this->created_at);
				array_push($single_subscriptionPlanGroupsDetail, $store_id, $sellingPlanGroupId, $selling_plan_id, $plan_type, htmlspecialchars($frequencyPlan_index->frequency_plan_name, ENT_QUOTES), htmlspecialchars($frequencyPlan_index->sd_description, ENT_QUOTES), $frequencyPlan_index->per_delivery_order_frequency_value, $billing_value, $frequencyPlan_index->per_delivery_order_frequency_type, $subscription_discount, $discount_value, $discount_type, $subscription_discount_after, $discount_value_after, $discount_type_after, $change_discount_after_cycle, $minCycle, $maxCycle, $anchor_day, $anchor_type, $cut_off_days, $this->created_at, $offer_trial_status, $frequencyPlan_index->offer_trial_period_value, $frequencyPlan_index->offer_trial_period_type, $renew_on_original_date);
				array_push($subscriptionPlanGroupsDetails_values, $single_subscriptionPlanGroupsDetail);
			}
			try {
				// print_r($subscriptionPlanGroupsDetails_fields );
				// print_r($subscriptionPlanGroupsDetails_values );
				$this->multiple_insert_update_row('subscriptionPlanGroupsDetails', $subscriptionPlanGroupsDetails_fields, $subscriptionPlanGroupsDetails_values);
			} catch (Exception $e) {

				return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Details Insert error')); // return json
			}
			if ($data_from == 'backend_subscription') {
				$selling_plan_create_card_array = array(
					'plan_name' => $plan_name,
					'selling_plan_group_id' => $sellingPlanGroupId,
					'product_id_array' => $product_ids,
					'total_selling_plan_count' => count((array)$sellingplans),
					'total_sellling_plans_names' => $sellingPlansToCreate['total_sellling_plans_names'],
				);
				$create_list_card_html = $this->create_plan_list_card($selling_plan_create_card_array);
			}
			$result = array('status' => true, 'message' => "Subscription Created Successfully", 'list_card_html' => $create_list_card_html, 'selling_plan_group_id' => $sellingPlanGroupId, 'selling_plan_list_card_array' => $selling_plan_create_card_array);
			return json_encode($result); // return json
		} else {
			if (is_array($sellingPlanGroupCreateapi_error)) {
				if (strpos($sellingPlanGroupCreateapi_error[0]['message'], 'does not exist') !== false) {
					return json_encode(array("status" => false, 'error' => json_encode($sellingPlanGroupCreateapi_error), 'message' => 'Some of the selected product does not exist in the shopify store')); // return json
				}
			} else {
				return json_encode(array("status" => false, 'error' => $sellingPlanGroupCreateapi_error)); // return json
			}
		}
	}



	// public function delete_selling_plan($data, $data_from)
	// {

	// 	try {

	// 		$graphQL_sellingPlanGroupDelete = 'mutation {

	// 		  sellingPlanGroupUpdate(

	// 			id: "gid://shopify/SellingPlanGroup/' . $data['subscription_group_id'] . '"

	// 			input: {

	// 			  sellingPlansToDelete : "gid://shopify/SellingPlan/' . $data['selling_plan_id'] . '"

	// 			  appId:  "a7rYz8vMnx43qJdpF5zLm9k1wXbQp0V7wKhG"

	// 			}

	// 		   )

	// 		  {

	// 			sellingPlanGroup {

	// 			  id

	// 			  sellingPlans(first: 20){

	// 				edges{

	// 					node{

	// 						id

	// 						name

	// 					}

	// 				}

	// 			  }

	// 			}

	// 			userErrors {

	// 			  field

	// 			  message

	// 			}

	// 		  }

	// 		}';

	// 		$sellingPlanGroupDeleteapi_execution = $this->graphqlQuery($graphQL_sellingPlanGroupDelete, null, null, null);

	// 		$sellingPlanGroupDeleteapi_error = $sellingPlanGroupDeleteapi_execution['data']['sellingPlanGroupUpdate']['userErrors'];

	// 		if (!count($sellingPlanGroupDeleteapi_error)) {

	// 			$whereCondition	=  array(

	// 				"selling_plan_id"  => $data['selling_plan_id']

	// 			);

	// 			try {

	// 				$this->delete_row('subscriptionPlanGroupsDetails', $whereCondition, '');

	// 				return json_encode(array("status" => true, 'message' => 'Selling Plan Deleted Successfully')); // return json

	// 			} catch (Exception $e) {

	// 				return json_encode(array("status" => true, 'error' => $e->getMessage(), 'message' => 'Selling Plan Deleted Successfully')); // return json

	// 			}
	// 		} else if ($sellingPlanGroupDeleteapi_error[0]['message'] == "Selling plans to delete can't result in a selling plan group with no selling plan.") {

	// 			return json_encode(array("status" => false, 'message' => 'Atleast one plan should be in the group'));
	// 		}
	// 	} catch (Exception $e) {

	// 		return json_encode(array("status" => false, 'message' => $e->getMessage())); // return json

	// 	}
	// }

	public function delete_selling_plan($data, $data_from)
	{
		try {
			$graphQL_sellingPlanGroupDelete = 'mutation sellingPlanGroupUpdate($id: ID!, $input: SellingPlanGroupInput!) {
				sellingPlanGroupUpdate(id: $id, input: $input) {
					sellingPlanGroup {
					id
					sellingPlans(first: 20) {
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
				';
			$variables = [
				"id" => "gid://shopify/SellingPlanGroup/" . $data['subscription_group_id'],
				"input" => [
					"sellingPlansToDelete" => [
						"gid://shopify/SellingPlan/" . $data['selling_plan_id']
					]
				]
			];
			// print_r($variables);
			$sellingPlanGroupDeleteapi_execution = $this->graphqlQuery($graphQL_sellingPlanGroupDelete, null, null, $variables);
			$sellingPlanGroupDeleteapi_error = $sellingPlanGroupDeleteapi_execution['data']['sellingPlanGroupUpdate']['userErrors'];
			if (!count($sellingPlanGroupDeleteapi_error)) {
				$whereCondition	=  array(
					"selling_plan_id"  => $data['selling_plan_id']
				);
				try {
					$this->delete_row('subscriptionPlanGroupsDetails', $whereCondition, '');
					return json_encode(array("status" => true, 'message' => 'Selling Plan Deleted Successfully')); // return json
				} catch (Exception $e) {
					return json_encode(array("status" => true, 'error' => $e->getMessage(), 'message' => 'Selling Plan Deleted Successfully')); // return json
				}
			} else if ($sellingPlanGroupDeleteapi_error[0]['message'] == "Selling plans to delete can't result in a selling plan group with no selling plan.") {
				return json_encode(array("status" => false, 'message' => 'Atleast one plan should be in the group'));
			}
		} catch (Exception $e) {
			return json_encode(array("status" => false, 'message' => $e->getMessage())); // return json
		}
	}


	// public function edit_subscription($data, $data_from)
	// {

	// 	// print_r($data);die();
	// 	// if($this->store == 'test-store-phoenixt.myshopify.com'){

	// 	// 	// echo "jkijisjjjjjjjknnj";die;
	// 	// }
	// 	$data_search_product = '';
	// 	$total_sellling_plans_names = '';
	// 	$mainGroupOption_update = '';

	// 	if ($data_from == 'backend_subscription') {

	// 		$edit_data = json_decode($data['edit_case_data']);
	// 	} else {

	// 		$group_name_change_data = array(

	// 			'subscription_group_id' => $data['subscription_plan_id'],

	// 			'plan_name' => $data['plan_name'],

	// 		);

	// 		$group_name_change_response = json_decode($this->mini_subscription_planName_change($group_name_change_data));

	// 		if ($group_name_change_response->status == false) {

	// 			return  json_encode($group_name_change_response);
	// 		}

	// 		$edit_data = (object)$data;
	// 	}

	// 	$sellingPlanGroupID_GraphQl = 'gid://shopify/SellingPlanGroup/' . $edit_data->subscription_plan_id;

	// 	$subscription_plan_product_insert_fields = array("store_id", "subscription_plan_group_id", "product_id", "variant_id", "product_name", "variant_name", "Imagepath", "created_at");

	// 	$subscriptionPlanGroupsDetails_fields = array("store_id", "subscription_plan_group_id", "selling_plan_id", "plan_type", "plan_name", "plan_description", "delivery_policy", "billing_policy", "delivery_billing_type", "discount_offer", "discount_value", "discount_type", "recurring_discount_offer", "discount_value_after", "discount_type_after", "change_discount_after_cycle", "min_cycle", "max_cycle", "anchor_day", "anchor_type", "cut_off_days", "created_at", "offer_trial_status", "trial_period_value", "trial_period_type", "renew_on_original_date");



	// 	$subscription_plan_product_insert_values = array();

	// 	$subscriptionPlanGroupsDetails_values = array();

	// 	if ($data_from == 'backend_subscription') {

	// 		$store_id = $data['insertdata']['store_id'];

	// 		$product_ids = json_decode($data['insertdata']['product_ids']);
	// 	}

	// 	$existing_plans = $edit_data->sd_subscription_edit_case_already_existing_plans_array;



	// 	$sellingPlansUpdate = $this->create_frequency_plans_array($existing_plans, "update", $total_sellling_plans_names, $data_from);

	// 	$mainGroupOption_update .= $sellingPlansUpdate['main_group_option'];

	// 	$totalSellingPlanNames = $sellingPlansUpdate['total_sellling_plans_names'];

	// 	$sellingPlansCreate = '[]';

	// 	$new_plans = $edit_data->sd_subscription_edit_case_to_be_added_new_plans_array; //new added selling plans

	// 	if (count($new_plans)) {

	// 		$sellingPlansCreate_array = $this->create_frequency_plans_array($new_plans, "create", $sellingPlansUpdate['total_sellling_plans_names'] . ',', $data_from);

	// 		$sellingPlansCreate = $sellingPlansCreate_array['selling_plans'];

	// 		$totalSellingPlanNames = $sellingPlansCreate_array['total_sellling_plans_names'];

	// 		if ($mainGroupOption_update == '') {

	// 			$mainGroupOption_update .= $sellingPlansCreate_array['main_group_option'];
	// 		} else {

	// 			$mainGroupOption_update .= ',' . $sellingPlansCreate_array['main_group_option'];
	// 		}
	// 	}



	// 	$delete_frequency_plans_array = $edit_data->sd_subscription_edit_case_to_be_deleted_plans_array; //existing deleted selling plans

	// 	$sellingPlansToDelete	= "[";

	// 	if (count($delete_frequency_plans_array)) {

	// 		foreach ($delete_frequency_plans_array  as $delete_frequency_plan) {

	// 			$sellingPlansToDelete .= '"gid://shopify/SellingPlan/' . $delete_frequency_plan . '"';
	// 		}
	// 	}

	// 	$sellingPlansToDelete .= "]";

	// 	$unique_option = rand(pow(10, 3 - 1), pow(10, 3) - 1);



	// 	try {

	// 		$graphQL_sellingPlanGroupUpdate = 'mutation {

	// 			sellingPlanGroupUpdate(

	// 				id: "' . $sellingPlanGroupID_GraphQl . '"

	// 				input: {

	// 				name: "' . addslashes($edit_data->plan_name) . '"

	// 				merchantCode: "' . addslashes($edit_data->plan_name) . '"

	// 				options : ["' . $mainGroupOption_update . '"]

	// 				sellingPlansToUpdate : ' . $sellingPlansUpdate['selling_plans'] . '

	// 				sellingPlansToCreate : ' . $sellingPlansCreate . '

	// 				appId:  "a7rYz8vMnx43qJdpF5zLm9k1wXbQp0V7wKhG"

	// 				}

	// 			)

	// 			{

	// 				sellingPlanGroup {

	// 				id

	// 				sellingPlans(first: 20){

	// 					edges{

	// 						node{

	// 							id

	// 							name

	// 						}

	// 					}

	// 				}

	// 				}

	// 				userErrors {

	// 				field

	// 				message

	// 				}

	// 			}

	// 			}';

	// 		// if($this->store == 'predictive-search.myshopify.com'){

	// 		// 	echo $graphQL_sellingPlanGroupUpdate;

	// 		// 	// die;

	// 		// }

	// 		$sellingPlanGroupUpdateapi_execution = $this->graphqlQuery($graphQL_sellingPlanGroupUpdate, null, null, null);

	// 		// if($this->store == 'predictive-search.myshopify.com'){

	// 		// 	print_r($sellingPlanGroupUpdateapi_execution);

	// 		// 	die;

	// 		// }

	// 		$sellingPlanGroupUpdateapi_error = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['userErrors'];
	// 	} catch (Exception $e) {

	// 		// if($this->store == 'predictive-search.myshopify.com'){

	// 		// 	echo $graphQL_sellingPlanGroupUpdate;

	// 		// 	echo '<pre>';

	// 		// 	print_r($e->getMessage());

	// 		// 	die;

	// 		// }

	// 		return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json

	// 	}



	// 	if (!count($sellingPlanGroupUpdateapi_error)) {

	// 		$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['id']);

	// 		if ($sellingPlanGroupId == $edit_data->subscription_plan_id) {
	// 		} else {

	// 			// It's just a double security check

	// 			return json_encode(array('status' => false, 'message' => "Something Went Wrong as edit Subscription ID don't match the mutation subscription ID . This quite a rare case if it occur."));
	// 		}

	// 		$list_card_all_li = '';

	// 		if ($data_from == 'backend_subscription') {

	// 			// Check if products data need to be updates

	// 			if (strlen($edit_data->updateproducts)) {

	// 				if (count((array)$edit_data->removeproducts)) {

	// 					$remove_products_array =    preg_filter(['/^/', '/$/'], ['"gid://shopify/ProductVariant/', '"'], array_column((array)$edit_data->removeproducts, 'variant_id'));

	// 					$remove_product_params_array = array_chunk($remove_products_array, 100);

	// 					foreach ($remove_product_params_array as $param_key => $remove_param_value) {

	// 						$remove_product_params = implode(',', $remove_param_value);

	// 						//mutation to update products in subscription group

	// 						$remove_product_array =  $this->remove_subscription_productVariants('[' . $remove_product_params . ']', $sellingPlanGroupID_GraphQl);
	// 					}

	// 					try {

	// 						$whereCondition = array(

	// 							"store_id" => $store_id,

	// 							"subscription_plan_group_id" => $edit_data->subscription_plan_id

	// 						);

	// 						$this->delete_row('subscriptionPlanGroupsProducts', $whereCondition, 'and');
	// 					} catch (Exception $e) {
	// 					}
	// 				}
	// 			}

	// 			if (count((array)$product_ids)) {

	// 				$product_li_counter = 1;

	// 				try {

	// 					$product_params = "";

	// 					foreach ($product_ids as $key => $product_id) {

	// 						if ($product_li_counter > 1) {

	// 							$product_params .= ',';
	// 						}

	// 						$product_params .= '"gid://shopify/ProductVariant/' . $product_id->variant_id . '"';

	// 						//for db_entry because we get sellingPlanGroupId after mutation otherwise it could be adjusted in above loop

	// 						$single_subscription_plan_product = array();

	// 						array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id->product_id, $product_id->variant_id, htmlspecialchars($product_id->product_title, ENT_QUOTES), htmlspecialchars($product_id->variant_title, ENT_QUOTES), $product_id->image, $this->created_at);

	// 						array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);

	// 						$data_search_product .= $product_id->product_title . ' ' . $product_id->variant_title;

	// 						if ($product_li_counter < 5) {

	// 							$list_card_all_li .= '<li class="Polaris-ResourceItem__ListItem">

	// 										<div class="Polaris-ResourceItem__ItemWrapper">

	// 											<div class="Polaris-ResourceItem">

	// 											<div class="Polaris-ResourceItem__Container">

	// 												<div class="Polaris-ResourceItem__Owned">

	// 													<div class="Polaris-ResourceItem__Media"><span class="Polaris-Thumbnail Polaris-Thumbnail--sizeMedium"><img src="' . $product_id->image . '" ></span></div>

	// 												</div>

	// 											</div>

	// 											</div>

	// 										</div>

	// 									</li>';
	// 						}

	// 						$product_li_counter++;
	// 					}

	// 					$product_params_array = array_chunk(explode(',', $product_params), 100);

	// 					foreach ($product_params_array as $param_key => $param_value) {

	// 						$product_params = implode(',', $param_value);

	// 						//mutation to update products in subscription group

	// 						$this->add_subscription_products('[' . $product_params . ']', $sellingPlanGroupID_GraphQl);
	// 					}
	// 				} catch (Exception $e) {

	// 					return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Products Insert error')); // return json

	// 				}





	// 				if (strlen($edit_data->updateproducts)) {

	// 					try {

	// 						$this->multiple_insert_row('subscriptionPlanGroupsProducts', $subscription_plan_product_insert_fields, $subscription_plan_product_insert_values);
	// 					} catch (Exception $e) {

	// 						return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Products Insert error')); // return json

	// 					}
	// 				}
	// 			}
	// 		}

	// 		if (!empty($existing_plans)) {

	// 			$existing_subscriptionPlanGroupsDetails_values = array();

	// 			foreach ($existing_plans as $key => $existingplan) {

	// 				if ($data_from == 'product_app_extension') {

	// 					$existingplan = (object)$existingplan;
	// 				}

	// 				if ($existingplan->frequency_plan_type == 'Prepaid') {

	// 					$plan_type = 2;

	// 					$billing_value = $existingplan->prepaid_billing_value;
	// 				} else {

	// 					$plan_type = 1;

	// 					$billing_value = $existingplan->per_delivery_order_frequency_value;
	// 				}

	// 				if ($existingplan->subscription_discount_value != '' && $existingplan->subscription_discount_value != 0 || $existingplan->offer_trial_status == "on") {

	// 					$subscription_discount = '1';

	// 					$discount_value = $existingplan->subscription_discount_value;
	// 				} else {

	// 					$subscription_discount = '0';

	// 					$discount_value = 0;
	// 				}

	// 				if ($existingplan->subscription_discount_type == 'Percent Off(%)') {

	// 					$discount_type = 'P';
	// 				} else {

	// 					$discount_type = 'A';
	// 				}

	// 				// recurring discount after cycle

	// 				$subscription_discount_after = '0';

	// 				$discount_value_after = 0;

	// 				if (($data_from == 'backend_subscription' && property_exists($existingplan, 'subscription_discount_after')) || ($data_from == 'product_app_extension' && $existingplan->subscription_discount_after == 'on') || $existingplan->offer_trial_status == "on") {

	// 					if ($existingplan->discount_value_after != '') {

	// 						$subscription_discount_after = '1';

	// 						$discount_value_after = $existingplan->discount_value_after;
	// 					}
	// 				}



	// 				if ($existingplan->subscription_discount_type_after == 'Percent Off(%)') {

	// 					$discount_type_after = 'P';
	// 				} else {

	// 					$discount_type_after = 'A';
	// 				}



	// 				if (empty($existingplan->change_discount_after_cycle)) {

	// 					$change_discount_after_cycle = 0;
	// 				} else {

	// 					$change_discount_after_cycle =  $existingplan->change_discount_after_cycle;
	// 				}



	// 				if (empty($existingplan->minimum_number_cycle)) {

	// 					$minCycle = 1;
	// 				} else {

	// 					$minCycle = $existingplan->minimum_number_cycle;
	// 				}

	// 				if (empty($existingplan->maximum_number_cycle)) {

	// 					$maxCycle = 0;

	// 					$max_cycle_mutation = '';
	// 				} else {

	// 					$maxCycle = $existingplan->maximum_number_cycle;

	// 					$max_cycle_mutation = ", maxCycles : " . $maxCycle;
	// 				}
	// 				// print_r($existingplan);
	// 				if (property_exists($existingplan, 'offer_trial_status') && ($existingplan->offer_trial_status == "on" || $existingplan->offer_trial_status == 1)) {
	// 					$offer_trial_period_value = $existingplan->offer_trial_period_value;
	// 					$offer_trial_status = 1;
	// 					$offer_trial_period_type = $existingplan->offer_trial_period_type;
	// 				} else {
	// 					$offer_trial_period_value = 0;
	// 					$offer_trial_status = 0;
	// 					$offer_trial_period_type = 'days';
	// 				}
	// 				if (property_exists($existingplan, 'renew_original_date') && $existingplan->renew_original_date == 1 || $existingplan->renew_original_date == true) {
	// 					$renew_on_original_date = 1;
	// 				} else {
	// 					$renew_on_original_date = 0;
	// 				}
	// 				$anchor_day = 0;

	// 				$anchor_type = '2'; // it means anchor date checkbox is disable

	// 				if (($data_from == 'backend_subscription' && property_exists($existingplan, 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $existingplan->sd_set_anchor_date == 'on')) {

	// 					if ($existingplan->sd_anchor_option ==  'On Purchase Day') {

	// 						$anchor_type = '0';  // on purchase day

	// 					} else {

	// 						$anchor_type = '1'; // on specific day

	// 						if ($existingplan->sd_anchor_month_day == '') {

	// 							$anchor_day = array_search($existingplan->sd_anchor_week_day, $this->weekDaysArray) + 1;
	// 						} else {

	// 							$anchor_day =  $existingplan->sd_anchor_month_day;
	// 						}
	// 					}
	// 				}

	// 				if ($existingplan->cut_off_days != '') {

	// 					$cut_off_days = $existingplan->cut_off_days;
	// 				} else {

	// 					$cut_off_days = 0;
	// 				}

	// 				$existing_subscriptionPlanGroupsDetail = array();
	// 				// echo($renew_on_original_date);
	// 				array_push($existing_subscriptionPlanGroupsDetail, $this->store_id, $sellingPlanGroupId, $existingplan->sellingplanid, $plan_type, htmlspecialchars($existingplan->frequency_plan_name, ENT_QUOTES), htmlspecialchars($existingplan->sd_description, ENT_QUOTES), $existingplan->per_delivery_order_frequency_value, $billing_value, $existingplan->per_delivery_order_frequency_type, $subscription_discount, $discount_value, $discount_type, $subscription_discount_after, $discount_value_after, $discount_type_after, $change_discount_after_cycle, $minCycle, $maxCycle, $anchor_day, $anchor_type, $cut_off_days, $this->created_at, $offer_trial_status, $offer_trial_period_value, $offer_trial_period_type, $renew_on_original_date);
	// 				array_push($existing_subscriptionPlanGroupsDetails_values, $existing_subscriptionPlanGroupsDetail);
	// 				// array_push($existing_subscriptionPlanGroupsDetail,$store_id,$sellingPlanGroupId,$selling_plan_id,$plan_type,htmlspecialchars($existingplan->frequency_plan_name, ENT_QUOTES),htmlspecialchars($existingplan->sd_description, ENT_QUOTES),$existingplan->per_delivery_order_frequency_value,$billing_value,$existingplan->per_delivery_order_frequency_type,$subscription_discount,$discount_value,$discount_type,$subscription_discount_after,$discount_value_after,$discount_type_after,$change_discount_after_cycle,$minCycle,$maxCycle,$anchor_day,$anchor_type,$cut_off_days,$this->created_at, $offer_trial_status, $existingplan->offer_trial_period_value, $existingplan->offer_trial_period_type, $renew_on_original_date);
	// 				//  print_r($subscriptionPlanGroupsDetails_fields);
	// 				//  print_r($existing_subscriptionPlanGroupsDetails_values);

	// 				try {

	// 					$this->multiple_insert_update_row('subscriptionPlanGroupsDetails', $subscriptionPlanGroupsDetails_fields, $existing_subscriptionPlanGroupsDetails_values);
	// 				} catch (Exception $e) {

	// 					return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Details Update error')); // return json

	// 				}
	// 			}
	// 		}

	// 		if (count($new_plans)) {

	// 			$new_sellingPlanName_array = array_column($new_plans, 'frequency_plan_name');

	// 			$sellingPlanGroupArray = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['sellingPlans']['edges'];

	// 			foreach ($sellingPlanGroupArray as $key => $newplan) {

	// 				if (in_array($newplan['node']['name'], $new_sellingPlanName_array)) {

	// 					$sellingPlanIndex = array_search($newplan['node']['name'], array_column($new_plans, 'frequency_plan_name'));

	// 					$single_subscriptionPlanGroupsDetail = array();

	// 					if ($data_from == 'product_app_extension') {

	// 						$new_plans[$sellingPlanIndex] = (object)$new_plans[$sellingPlanIndex];
	// 					}

	// 					if ($new_plans[$sellingPlanIndex]->frequency_plan_type == 'Prepaid') {

	// 						$plan_type = 2;

	// 						$deliveryPolicy = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_value;

	// 						$delivery_billing_type = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_type;
	// 					} else {

	// 						$plan_type = 1;

	// 						$deliveryPolicy = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_value;

	// 						$delivery_billing_type = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_type;
	// 					}

	// 					if (empty($new_plans[$sellingPlanIndex]->prepaid_billing_value)) {

	// 						$billing_value = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_value;
	// 					} else {

	// 						$billing_value = $new_plans[$sellingPlanIndex]->prepaid_billing_value;
	// 					}

	// 					if (empty($new_plans[$sellingPlanIndex]->subscription_discount_value)) {

	// 						$discount_value = 0;

	// 						$subscription_discount = '0';
	// 					} else {

	// 						$discount_value = $new_plans[$sellingPlanIndex]->subscription_discount_value;

	// 						$subscription_discount = '1';
	// 					}



	// 					if (empty($new_plans[$sellingPlanIndex]->discount_value_after)) {

	// 						$discount_value_after = 0;

	// 						$recurring_discount_offer = '0';
	// 					} else {

	// 						$discount_value_after = $new_plans[$sellingPlanIndex]->discount_value_after;

	// 						$recurring_discount_offer = '1';
	// 					}



	// 					if ($new_plans[$sellingPlanIndex]->subscription_discount_type == 'Percent Off(%)') {

	// 						$discount_type = "P";
	// 					} else {

	// 						$discount_type = "A";
	// 					}



	// 					if ($new_plans[$sellingPlanIndex]->subscription_discount_type_after == 'Percent Off(%)') {

	// 						$discount_type_after = "P";
	// 					} else {

	// 						$discount_type_after = "A";
	// 					}



	// 					if (empty($new_plans[$sellingPlanIndex]->change_discount_after_cycle)) {

	// 						$change_discount_after_cycle = 0;
	// 					} else {

	// 						$change_discount_after_cycle =  $new_plans[$sellingPlanIndex]->change_discount_after_cycle;
	// 					}

	// 					if (empty($new_plans[$sellingPlanIndex]->minimum_number_cycle)) {

	// 						$minCycle = 1;
	// 					} else {

	// 						$minCycle = $new_plans[$sellingPlanIndex]->minimum_number_cycle;
	// 					}

	// 					if (empty($new_plans[$sellingPlanIndex]->maximum_number_cycle)) {

	// 						$maxCycle = 0;

	// 						$max_cycle_mutation = '';
	// 					} else {

	// 						$maxCycle = $new_plans[$sellingPlanIndex]->maximum_number_cycle;

	// 						$max_cycle_mutation = ", maxCycles : " . $maxCycle;
	// 					}

	// 					$anchor_day = 0;

	// 					$anchor_type = '2'; // it means anchor date checkbox is disable

	// 					if (($data_from == 'backend_subscription' && property_exists($new_plans[$sellingPlanIndex], 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $new_plans[$sellingPlanIndex]->sd_set_anchor_date == 'on')) {

	// 						if ($$new_plans[$sellingPlanIndex]->sd_anchor_option ==  'On Purchase Day') {

	// 							$anchor_type = '0';  // on purchase day

	// 						} else {

	// 							$anchor_type = '1'; // on specific day

	// 							if ($new_plans[$sellingPlanIndex]->sd_anchor_month_day == '') {

	// 								$anchor_day = array_search($new_plans[$sellingPlanIndex]->sd_anchor_week_day, $this->weekDaysArray) + 1;
	// 							} else {

	// 								$anchor_day =  $new_plans[$sellingPlanIndex]->sd_anchor_month_day;
	// 							}
	// 						}
	// 					}
	// 					if (property_exists($new_plans[$sellingPlanIndex], 'offer_trial_status') && ($new_plans[$sellingPlanIndex]->offer_trial_status == "on" || $new_plans[$sellingPlanIndex]->offer_trial_status == 1)) {
	// 						$offer_trial_period_value = $new_plans[$sellingPlanIndex]->offer_trial_period_value;
	// 						$offer_trial_status = 1;
	// 						$offer_trial_period_type = $new_plans[$sellingPlanIndex]->offer_trial_period_type;
	// 					} else {
	// 						$offer_trial_period_value = 0;
	// 						$offer_trial_status = 0;
	// 						$offer_trial_period_type = 'days';
	// 					}
	// 					if (property_exists($new_plans[$sellingPlanIndex], 'renew_original_date') && $new_plans[$sellingPlanIndex]->renew_original_date == 1 || $new_plans[$sellingPlanIndex]->renew_original_date == true) {
	// 						$renew_on_original_date = 1;
	// 					} else {
	// 						$renew_on_original_date = 0;
	// 					}
	// 					if ($new_plans[$sellingPlanIndex]->cut_off_days != '') {

	// 						$cut_off_days = $new_plans[$sellingPlanIndex]->cut_off_days;
	// 					} else {

	// 						$cut_off_days = 0;
	// 					}

	// 					$selling_plan_id = str_replace("gid://shopify/SellingPlan/", "", $newplan['node']['id']);

	// 					array_push($single_subscriptionPlanGroupsDetail, $this->store_id, $sellingPlanGroupId, $selling_plan_id, $plan_type, $new_plans[$sellingPlanIndex]->frequency_plan_name, htmlspecialchars($new_plans[$sellingPlanIndex]->sd_description, ENT_QUOTES), $deliveryPolicy, $billing_value, $delivery_billing_type, $subscription_discount, $discount_value, $discount_type, $recurring_discount_offer, $discount_value_after, $discount_type_after, $change_discount_after_cycle, $minCycle, $maxCycle, $anchor_day, $anchor_type, $cut_off_days, $this->created_at,$offer_trial_status, $offer_trial_period_value, $offer_trial_period_type, $renew_on_original_date);

	// 					array_push($subscriptionPlanGroupsDetails_values, $single_subscriptionPlanGroupsDetail);
	// 				}
	// 			}

	// 			try {
	// 				foreach ($subscriptionPlanGroupsDetails_values as &$row) {
	// 					foreach ($row as $key => $value) {
	// 						if ($value === '' || is_null($value)) {
	// 							$row[$key] = 'null';
	// 						}
	// 					}
	// 				}
	// 				// if($this->store == 'test-store-phoenixt.myshopify.com'){

	// 				// 	print_r($subscriptionPlanGroupsDetails_fields );
	// 				// 	print_r($subscriptionPlanGroupsDetails_values );
	// 				// }

	// 				$this->multiple_insert_row('subscriptionPlanGroupsDetails', $subscriptionPlanGroupsDetails_fields, $subscriptionPlanGroupsDetails_values);
	// 			} catch (Exception $e) {

	// 				return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Details Insert error')); // return json

	// 			}
	// 		}

	// 		if ($data_from == 'backend_subscription') {

	// 			$selling_plan_create_card_array = array(

	// 				'product_name' => $data_search_product,

	// 				'plan_name' => $data['insertdata']['plan_name'],

	// 				'selling_plan_group_id' => $sellingPlanGroupId,

	// 				'product_id_array' => $product_ids,

	// 				'total_selling_plan_count' => (count(explode(",", $totalSellingPlanNames))),

	// 				'product_image_list' => $list_card_all_li,

	// 				'total_sellling_plans_names' => $totalSellingPlanNames,

	// 			);

	// 			$create_list_card_html = $this->create_plan_list_card($selling_plan_create_card_array);
	// 		} else {

	// 			$create_list_card_html = '';
	// 		}

	// 		return json_encode(array("status" => true, 'message' => 'Subscription Plan Group Updated Successfully', 'list_card_html' => $create_list_card_html, 'selling_plan_group_id' => $sellingPlanGroupId)); // return json

	// 	} else {

	// 		// if update mutation don't fire sucessfully

	// 		$message = $sellingPlanGroupUpdateapi_error[0]['message'];

	// 		$mutation_update =  $sellingPlanGroupUpdateapi_error;

	// 		return json_encode(array("status" => false, 'error' => $mutation_update, 'message' => $message)); // return json

	// 	}
	// }

	public function edit_subscription($data, $data_from)
	{
		// print_r($data);die();
		$data_search_product = '';
		$total_sellling_plans_names = '';
		$mainGroupOption_update = '';
		if ($data_from == 'backend_subscription') {
			$edit_data = json_decode($data['edit_case_data']);
		} else {
			$group_name_change_data = array(
				'subscription_group_id' => $data['subscription_plan_id'],
				'plan_name' => $data['plan_name'],
			);
			$group_name_change_response = json_decode($this->mini_subscription_planName_change($group_name_change_data));
			if ($group_name_change_response->status == false) {
				return  json_encode($group_name_change_response);
			}
			$edit_data = (object)$data;
		}
		$sellingPlanGroupID_GraphQl = 'gid://shopify/SellingPlanGroup/' . $edit_data->subscription_plan_id;
		$subscription_plan_product_insert_fields = array("store_id", "subscription_plan_group_id", "product_id", "variant_id", "product_name", "variant_name", "Imagepath", "created_at");
		$subscriptionPlanGroupsDetails_fields = array("store_id", "subscription_plan_group_id", "selling_plan_id", "plan_type", "plan_name", "plan_description", "delivery_policy", "billing_policy", "delivery_billing_type", "discount_offer", "discount_value", "discount_type", "recurring_discount_offer", "discount_value_after", "discount_type_after", "change_discount_after_cycle", "min_cycle", "max_cycle", "anchor_day", "anchor_type", "cut_off_days", "created_at", "offer_trial_status", "trial_period_value", "trial_period_type", "renew_on_original_date");
		$subscription_plan_product_insert_values = array();
		$subscriptionPlanGroupsDetails_values = array();
		if ($data_from == 'backend_subscription') {
			$store_id = $data['insertdata']['store_id'];
			$product_ids = json_decode($data['insertdata']['product_ids']);
		}
		$existing_plans = $edit_data->sd_subscription_edit_case_already_existing_plans_array;
		$sellingPlansUpdate = $this->create_frequency_plans_array($existing_plans, "update", $total_sellling_plans_names, $data_from);
		$mainGroupOption_update .= $sellingPlansUpdate['main_group_option'];
		$totalSellingPlanNames = $sellingPlansUpdate['total_sellling_plans_names'];
		$sellingPlansCreate = '[]';
		$new_plans = $edit_data->sd_subscription_edit_case_to_be_added_new_plans_array; //new added selling plans
		if (count($new_plans)) {
			$sellingPlansCreate_array = $this->create_frequency_plans_array($new_plans, "create", $sellingPlansUpdate['total_sellling_plans_names'] . ',', $data_from);
			$sellingPlansCreate = $sellingPlansCreate_array['selling_plans'];
			$totalSellingPlanNames = $sellingPlansCreate_array['total_sellling_plans_names'];
			if ($mainGroupOption_update == '') {
				$mainGroupOption_update .= $sellingPlansCreate_array['main_group_option'];
			} else {
				$mainGroupOption_update .= ',' . $sellingPlansCreate_array['main_group_option'];
			}
		}
		$delete_frequency_plans_array = $edit_data->sd_subscription_edit_case_to_be_deleted_plans_array; //existing deleted selling plans
		$sellingPlansToDelete	= "[";
		if (count($delete_frequency_plans_array)) {
			foreach ($delete_frequency_plans_array  as $delete_frequency_plan) {
				$sellingPlansToDelete .= '"gid://shopify/SellingPlan/' . $delete_frequency_plan . '"';
			}
		}
		$sellingPlansToDelete .= "]";
		$unique_option = rand(pow(10, 3 - 1), pow(10, 3) - 1);

		try {
			$graphQL_sellingPlanGroupUpdate = '
				mutation sellingPlanGroupUpdate($id: ID!, $input: SellingPlanGroupInput!) {
				sellingPlanGroupUpdate(id: $id, input: $input) {
					sellingPlanGroup {
					id
					sellingPlans(first: 20) {
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
			}';
			$variables = [
				'id' => $sellingPlanGroupID_GraphQl,
				'input' => [
					'name' => $edit_data->plan_name,
					'merchantCode' => $edit_data->plan_name,
					'options' => [$mainGroupOption_update],
					'appId' => 'a7rYz8vMnx43qJdpF5zLm9k1wXbQp0V7wKhG',
					'sellingPlansToUpdate' => $sellingPlansUpdate['selling_plans']
				],


			];
			// if (!empty($sellingPlansCreate)) {
			// 	$variables['input']['sellingPlansToCreate'] = $sellingPlansCreate;
			// }
			if (!empty($sellingPlansCreate) && is_array($sellingPlansCreate)) {
				$variables['input']['sellingPlansToCreate'] = $sellingPlansCreate;
			}


			$sellingPlanGroupUpdateapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupUpdate, null, null, $variables);
			// if($this->store == 'test-store-phoenixt.myshopify.com'){
			// 	print_r($sellingPlanGroupUpdateapi_execution);
			// 	die;
			// }


			$sellingPlanGroupUpdateapi_error = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['userErrors'];
		} catch (Exception $e) {
			// if($this->store == 'predictive-search.myshopify.com'){
			// 	echo $graphQL_sellingPlanGroupUpdate;
			// 	echo '<pre>';
			// 	print_r($e->getMessage());
			// 	die;
			// }
			return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json
		}
		if (!count($sellingPlanGroupUpdateapi_error)) {
			$sellingPlanGroupId = str_replace("gid://shopify/SellingPlanGroup/", "", $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['id']);
			if ($sellingPlanGroupId == $edit_data->subscription_plan_id) {
			} else {
				// It's just a double security check
				return json_encode(array('status' => false, 'message' => "Something Went Wrong as edit Subscription ID don't match the mutation subscription ID . This quite a rare case if it occur."));
			}
			$list_card_all_li = '';
			if ($data_from == 'backend_subscription') {
				// Check if products data need to be updates
				if (strlen($edit_data->updateproducts)) {
					if (count((array)$edit_data->removeproducts)) {
						$remove_products_array =    preg_filter(['/^/', '/$/'], ['"gid://shopify/ProductVariant/', '"'], array_column((array)$edit_data->removeproducts, 'variant_id'));
						$remove_product_params_array = array_chunk($remove_products_array, 100);
						foreach ($remove_product_params_array as $param_key => $remove_param_value) {
							$remove_product_params = implode(',', $remove_param_value);
							//mutation to update products in subscription group
							$remove_product_array =  $this->remove_subscription_productVariants('[' . $remove_product_params . ']', $sellingPlanGroupID_GraphQl);
						}
						try {
							$whereCondition = array(
								"store_id" => $store_id,
								"subscription_plan_group_id" => $edit_data->subscription_plan_id
							);
							$this->delete_row('subscriptionPlanGroupsProducts', $whereCondition, 'and');
						} catch (Exception $e) {
						}
					}
				}
				if (count((array)$product_ids)) {
					$product_li_counter = 1;
					try {
						$product_params = "";
						foreach ($product_ids as $key => $product_id) {
							if ($product_li_counter > 1) {
								$product_params .= ',';
							}
							$product_params .= '"gid://shopify/ProductVariant/' . $product_id->variant_id . '"';
							//for db_entry because we get sellingPlanGroupId after mutation otherwise it could be adjusted in above loop
							$single_subscription_plan_product = array();
							array_push($single_subscription_plan_product, $store_id, $sellingPlanGroupId, $product_id->product_id, $product_id->variant_id, htmlspecialchars($product_id->product_title, ENT_QUOTES), htmlspecialchars($product_id->variant_title, ENT_QUOTES), $product_id->image, $this->created_at);
							array_push($subscription_plan_product_insert_values, $single_subscription_plan_product);
							$data_search_product .= $product_id->product_title . ' ' . $product_id->variant_title;
							if ($product_li_counter < 5) {
								$list_card_all_li .= '<li class="Polaris-ResourceItem__ListItem">
											<div class="Polaris-ResourceItem__ItemWrapper">
												<div class="Polaris-ResourceItem">
												<div class="Polaris-ResourceItem__Container">
													<div class="Polaris-ResourceItem__Owned">
														<div class="Polaris-ResourceItem__Media"><span class="Polaris-Thumbnail Polaris-Thumbnail--sizeMedium"><img src="' . $product_id->image . '" ></span></div>
													</div>
												</div>
												</div>
											</div>
										</li>';
							}
							$product_li_counter++;
						}
						$product_params_array = array_chunk(explode(',', $product_params), 100);
						foreach ($product_params_array as $param_key => $param_value) {
							$product_params = implode(',', $param_value);
							//mutation to update products in subscription group
							$this->add_subscription_products('[' . $product_params . ']', $sellingPlanGroupID_GraphQl);
						}
					} catch (Exception $e) {
						return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Products Insert error')); // return json
					}
					if (strlen($edit_data->updateproducts)) {
						try {
							$this->multiple_insert_row('subscriptionPlanGroupsProducts', $subscription_plan_product_insert_fields, $subscription_plan_product_insert_values);
						} catch (Exception $e) {
							return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Products Insert error')); // return json
						}
					}
				}
			}
			if (!empty($existing_plans)) {
				$existing_subscriptionPlanGroupsDetails_values = array();
				foreach ($existing_plans as $key => $existingplan) {
					if ($data_from == 'product_app_extension') {
						$existingplan = (object)$existingplan;
					}
					if ($existingplan->frequency_plan_type == 'Prepaid') {
						$plan_type = 2;
						$billing_value = $existingplan->prepaid_billing_value;
					} else {
						$plan_type = 1;
						$billing_value = $existingplan->per_delivery_order_frequency_value;
					}
					if ($existingplan->subscription_discount_value != '' && $existingplan->subscription_discount_value != 0 || $existingplan->offer_trial_status == "on") {
						$subscription_discount = '1';
						$discount_value = $existingplan->subscription_discount_value;
					} else {
						$subscription_discount = '0';
						$discount_value = 0;
					}
					if ($existingplan->subscription_discount_type == 'Percent Off(%)') {
						$discount_type = 'P';
					} else {
						$discount_type = 'A';
					}
					// recurring discount after cycle
					$subscription_discount_after = '0';
					$discount_value_after = 0;
					if (($data_from == 'backend_subscription' && property_exists($existingplan, 'subscription_discount_after')) || ($data_from == 'product_app_extension' && $existingplan->subscription_discount_after == 'on') || $existingplan->offer_trial_status == "on") {
						if ($existingplan->discount_value_after != '') {
							$subscription_discount_after = '1';
							$discount_value_after = $existingplan->discount_value_after;
						}
					}
					if ($existingplan->subscription_discount_type_after == 'Percent Off(%)') {
						$discount_type_after = 'P';
					} else {
						$discount_type_after = 'A';
					}
					if (empty($existingplan->change_discount_after_cycle)) {
						$change_discount_after_cycle = 0;
					} else {
						$change_discount_after_cycle =  $existingplan->change_discount_after_cycle;
					}
					if (empty($existingplan->minimum_number_cycle)) {
						$minCycle = 1;
					} else {
						$minCycle = $existingplan->minimum_number_cycle;
					}
					if (empty($existingplan->maximum_number_cycle)) {
						$maxCycle = 0;
						$max_cycle_mutation = '';
					} else {
						$maxCycle = $existingplan->maximum_number_cycle;
						$max_cycle_mutation = ", maxCycles : " . $maxCycle;
					}

					if (property_exists($existingplan, 'offer_trial_status') && ((isset($existingplan->offer_trial_status) && $existingplan->offer_trial_status == "on") || (isset($existingplan->offer_trial_status) && $existingplan->offer_trial_status == 1))) {
						$offer_trial_period_value = $existingplan->offer_trial_period_value;
						$offer_trial_status = 1;
						$offer_trial_period_type = $existingplan->offer_trial_period_type;
					} else {
						$offer_trial_period_value = 0;
						$offer_trial_status = 0;
						$offer_trial_period_type = 'days';
					}

					if (property_exists($existingplan, 'renew_original_date') && (isset($existingplan->renew_original_date) && $existingplan->renew_original_date == 1) || (isset($existingplan->renew_original_date) && $existingplan->renew_original_date == true)) {
						$renew_on_original_date = 1;
					} else {
						$renew_on_original_date = 0;
					}
					$anchor_day = 0;
					$anchor_type = '2'; // it means anchor date checkbox is disable
					if (($data_from == 'backend_subscription' && property_exists($existingplan, 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $existingplan->sd_set_anchor_date == 'on')) {
						if ($existingplan->sd_anchor_option ==  'On Purchase Day') {
							$anchor_type = '0';  // on purchase day
						} else {
							$anchor_type = '1'; // on specific day
							if ($existingplan->sd_anchor_month_day == '') {
								$anchor_day = array_search($existingplan->sd_anchor_week_day, $this->weekDaysArray) + 1;
							} else {
								$anchor_day =  $existingplan->sd_anchor_month_day;
							}
						}
					}
					if ($existingplan->cut_off_days != '') {
						$cut_off_days = $existingplan->cut_off_days;
					} else {
						$cut_off_days = 0;
					}
					$existing_subscriptionPlanGroupsDetail = array();

					array_push($existing_subscriptionPlanGroupsDetail, $this->store_id, $sellingPlanGroupId, $existingplan->sellingplanid, $plan_type, htmlspecialchars($existingplan->frequency_plan_name, ENT_QUOTES), htmlspecialchars($existingplan->sd_description, ENT_QUOTES), $existingplan->per_delivery_order_frequency_value, $billing_value, $existingplan->per_delivery_order_frequency_type, $subscription_discount, $discount_value, $discount_type, $subscription_discount_after, $discount_value_after, $discount_type_after, $change_discount_after_cycle, $minCycle, $maxCycle, $anchor_day, $anchor_type, $cut_off_days, $this->created_at, $offer_trial_status, $offer_trial_period_value, $offer_trial_period_type, $renew_on_original_date);
					array_push($existing_subscriptionPlanGroupsDetails_values, $existing_subscriptionPlanGroupsDetail);
					// array_push($existing_subscriptionPlanGroupsDetail,$store_id,$sellingPlanGroupId,$selling_plan_id,$plan_type,htmlspecialchars($existingplan->frequency_plan_name, ENT_QUOTES),htmlspecialchars($existingplan->sd_description, ENT_QUOTES),$existingplan->per_delivery_order_frequency_value,$billing_value,$existingplan->per_delivery_order_frequency_type,$subscription_discount,$discount_value,$discount_type,$subscription_discount_after,$discount_value_after,$discount_type_after,$change_discount_after_cycle,$minCycle,$maxCycle,$anchor_day,$anchor_type,$cut_off_days,$this->created_at, $offer_trial_status, $existingplan->offer_trial_period_value, $existingplan->offer_trial_period_type, $renew_on_original_date);
					//  print_r($subscriptionPlanGroupsDetails_fields);
					//  print_r($existing_subscriptionPlanGroupsDetails_values);
					try {
						$this->multiple_insert_update_row('subscriptionPlanGroupsDetails', $subscriptionPlanGroupsDetails_fields, $existing_subscriptionPlanGroupsDetails_values);
					} catch (Exception $e) {
						return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Details Update error')); // return json
					}
				}
			}
			if (count($new_plans)) {

				$new_sellingPlanName_array = array_column($new_plans, 'frequency_plan_name');
				$sellingPlanGroupArray = $sellingPlanGroupUpdateapi_execution['data']['sellingPlanGroupUpdate']['sellingPlanGroup']['sellingPlans']['edges'];

				foreach ($sellingPlanGroupArray as $key => $newplan) {

					if (in_array($newplan['node']['name'], $new_sellingPlanName_array)) {

						$sellingPlanIndex = array_search($newplan['node']['name'], array_column($new_plans, 'frequency_plan_name'));
						$single_subscriptionPlanGroupsDetail = array();
						if ($data_from == 'product_app_extension') {
							$new_plans[$sellingPlanIndex] = (object)$new_plans[$sellingPlanIndex];
						}
						if ($new_plans[$sellingPlanIndex]->frequency_plan_type == 'Prepaid') {
							$plan_type = 2;
							$deliveryPolicy = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_value;
							$delivery_billing_type = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_type;
						} else {
							$plan_type = 1;
							$deliveryPolicy = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_value;
							$delivery_billing_type = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_type;
						}
						if (empty($new_plans[$sellingPlanIndex]->prepaid_billing_value)) {
							$billing_value = $new_plans[$sellingPlanIndex]->per_delivery_order_frequency_value;
						} else {
							$billing_value = $new_plans[$sellingPlanIndex]->prepaid_billing_value;
						}
						if (empty($new_plans[$sellingPlanIndex]->subscription_discount_value)) {
							$discount_value = 0;
							$subscription_discount = '0';
						} else {
							$discount_value = $new_plans[$sellingPlanIndex]->subscription_discount_value;
							$subscription_discount = '1';
						}
						if (empty($new_plans[$sellingPlanIndex]->discount_value_after)) {
							$discount_value_after = 0;
							$recurring_discount_offer = '0';
						} else {
							$discount_value_after = $new_plans[$sellingPlanIndex]->discount_value_after;
							$recurring_discount_offer = '1';
						}
						if ($new_plans[$sellingPlanIndex]->subscription_discount_type == 'Percent Off(%)') {
							$discount_type = "P";
						} else {
							$discount_type = "A";
						}
						if ($new_plans[$sellingPlanIndex]->subscription_discount_type_after == 'Percent Off(%)') {
							$discount_type_after = "P";
						} else {
							$discount_type_after = "A";
						}
						if (empty($new_plans[$sellingPlanIndex]->change_discount_after_cycle)) {
							$change_discount_after_cycle = 0;
						} else {
							$change_discount_after_cycle =  $new_plans[$sellingPlanIndex]->change_discount_after_cycle;
						}
						if (empty($new_plans[$sellingPlanIndex]->minimum_number_cycle)) {
							$minCycle = 1;
						} else {
							$minCycle = $new_plans[$sellingPlanIndex]->minimum_number_cycle;
						}
						if (empty($new_plans[$sellingPlanIndex]->maximum_number_cycle)) {
							$maxCycle = 0;
							$max_cycle_mutation = '';
						} else {
							$maxCycle = $new_plans[$sellingPlanIndex]->maximum_number_cycle;
							$max_cycle_mutation = ", maxCycles : " . $maxCycle;
						}

						$anchor_day = 0;
						$anchor_type = '2'; // it means anchor date checkbox is disable
						if (($data_from == 'backend_subscription' && property_exists($new_plans[$sellingPlanIndex], 'sd_set_anchor_date')) || ($data_from == 'product_app_extension' && $new_plans[$sellingPlanIndex]->sd_set_anchor_date == 'on')) {
							if ($$new_plans[$sellingPlanIndex]->sd_anchor_option ==  'On Purchase Day') {
								$anchor_type = '0';  // on purchase day
							} else {
								$anchor_type = '1'; // on specific day
								if ($new_plans[$sellingPlanIndex]->sd_anchor_month_day == '') {
									$anchor_day = array_search($new_plans[$sellingPlanIndex]->sd_anchor_week_day, $this->weekDaysArray) + 1;
								} else {
									$anchor_day =  $new_plans[$sellingPlanIndex]->sd_anchor_month_day;
								}
							}
						}
						if ($new_plans[$sellingPlanIndex]->cut_off_days != '') {
							$cut_off_days = $new_plans[$sellingPlanIndex]->cut_off_days;
						} else {
							$cut_off_days = 0;
						}
						$selling_plan_id = str_replace("gid://shopify/SellingPlan/", "", $newplan['node']['id']);

						array_push($single_subscriptionPlanGroupsDetail, $this->store_id, $sellingPlanGroupId, $selling_plan_id, $plan_type, $new_plans[$sellingPlanIndex]->frequency_plan_name, htmlspecialchars($new_plans[$sellingPlanIndex]->sd_description, ENT_QUOTES), $deliveryPolicy, $billing_value, $delivery_billing_type, $subscription_discount, $discount_value, $discount_type, $recurring_discount_offer, $discount_value_after, $discount_type_after, $change_discount_after_cycle, $minCycle, $maxCycle, $anchor_day, $anchor_type, $cut_off_days, $this->created_at,  $offer_trial_status, $offer_trial_period_value, $offer_trial_period_type, $renew_on_original_date);
						array_push($subscriptionPlanGroupsDetails_values, $single_subscriptionPlanGroupsDetail);
					}
				}
				try {

					$this->multiple_insert_row('subscriptionPlanGroupsDetails', $subscriptionPlanGroupsDetails_fields, $subscriptionPlanGroupsDetails_values);
				} catch (Exception $e) {
					// print_r($e->getMessage());
					return json_encode(array("status" => false, 'error' => $e->getMessage(), 'message' => 'subscription Plan Groups Details Insert error')); // return json
				}
			}
			if ($data_from == 'backend_subscription') {
				$selling_plan_create_card_array = array(
					'product_name' => $data_search_product,
					'plan_name' => $data['insertdata']['plan_name'],
					'selling_plan_group_id' => $sellingPlanGroupId,
					'product_id_array' => $product_ids,
					'total_selling_plan_count' => (count(explode(",", $totalSellingPlanNames))),
					'product_image_list' => $list_card_all_li,
					'total_sellling_plans_names' => $totalSellingPlanNames,
				);
				$create_list_card_html = $this->create_plan_list_card($selling_plan_create_card_array);
			} else {
				$create_list_card_html = '';
			}
			return json_encode(array("status" => true, 'message' => 'Subscription Plan Group Updated Successfully', 'list_card_html' => $create_list_card_html, 'selling_plan_group_id' => $sellingPlanGroupId)); // return json
		} else {
			// if update mutation don't fire sucessfully
			$message = $sellingPlanGroupUpdateapi_error[0]['message'];
			$mutation_update =  $sellingPlanGroupUpdateapi_error;
			return json_encode(array("status" => false, 'error' => $mutation_update, 'message' => $message)); // return json
		}
	}



	public function create_plan_list_card($selling_plan_create_card_array)
	{

		$selling_plan_names_array = explode(",", $selling_plan_create_card_array['total_sellling_plans_names']);

		$three_selling_plans = implode(",", array_chunk($selling_plan_names_array, 3)[0]);

		$create_list_card_html  =   '<div data-search-planname="' . $selling_plan_create_card_array['plan_name'] . '" id="subscription_list_' . $selling_plan_create_card_array['selling_plan_group_id'] . '" class="subscription-list-card Polaris-Layout__Section Polaris-Layout__Section--oneHalf subscription_list_' . $selling_plan_create_card_array['selling_plan_group_id'] . '">

		<div class="Polaris-Card">
		<div class="sd-upper-wrapper">

		   <div class="Polaris-Card__Header">

			  <div class="Polaris-Stack Polaris-Stack--alignmentCenter">

				 <div class="Polaris-Stack__Item">

					<h2 class="Polaris-Heading subscription_heading"><span class="list_planname">' . htmlspecialchars($selling_plan_create_card_array['plan_name'], ENT_QUOTES) . '</span><span plan-name-value="' . htmlspecialchars($selling_plan_create_card_array['plan_name'], ENT_QUOTES) . '" subscription-group-id="' . $selling_plan_create_card_array['selling_plan_group_id'] . '" class="change_plan_name"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path d="M4.27778 15.3266C4.27778 15.8212 4.67878 16.2222 5.17345 16.2222C5.41099 16.2222 5.63881 16.1279 5.80678 15.9599L14.2333 7.53333L12.9667 6.26667L4.54011 14.6932C4.37214 14.8612 4.27778 15.089 4.27778 15.3266ZM3.5 18C2.94771 18 2.5 17.5523 2.5 17V14.637C2.5 14.3714 2.60562 14.1168 2.79356 13.9292L14.2333 2.51111C14.4111 2.34815 14.6076 2.22222 14.8227 2.13333C15.0378 2.04444 15.2636 2 15.5 2C15.737 2 15.9667 2.04444 16.1889 2.13333C16.4111 2.22222 16.6037 2.35556 16.7667 2.53333L17.9889 3.77778C18.1667 3.94074 18.2964 4.13333 18.3782 4.35556C18.46 4.57778 18.5006 4.8 18.5 5.02222C18.5 5.25926 18.4594 5.48533 18.3782 5.70044C18.297 5.91556 18.1673 6.1117 17.9889 6.28889L6.57067 17.7071C6.38313 17.8946 6.12878 18 5.86356 18H3.5ZM13.5889 6.91111L12.9667 6.26667L14.2333 7.53333L13.5889 6.91111Z" fill="white"></path>
                                    </svg></span></h2>

					<div class="planname_input_wrapper display-hide-label" />

					<input type="text" value="' . htmlspecialchars($selling_plan_create_card_array['plan_name'], ENT_QUOTES) . '" name="subscription_heading"  class="subscription_plan_name sd_validate_expration" />

					<img  src = "' . $this->image_folder . 'MobileCancelMajor.svg" class="plan_name_actions cancel_plan_name" />

					<img class = "plan_name_actions save_plan_name" subscription-group-id="' . $selling_plan_create_card_array['selling_plan_group_id'] . '" src="' . $this->image_folder . 'MobileAcceptMajor.svg" />

					</div>

				 </div>

			  </div>

		   </div>

		   <div id="subscription_mini_' . $selling_plan_create_card_array['selling_plan_group_id'] . '" class="subscription_mini_inner_wrapper" >

			  <div class="Polaris-Card__Section inner-box-cont bot-bx">

			  <div class="Polaris-Card__SectionHeader box-header">

			  <a href="javascript:;" class="edit-subscription-group" subscription-group-id="' . $selling_plan_create_card_array['selling_plan_group_id'] . '" data-type="selling_plans">

			  <h3 aria-label="Items" class="Polaris-Subheading sd_sellingplans">Selling Plans</h3>

			  </a>

			  <div class="Polaris-ButtonGroup">

				 <div class="Polaris-ButtonGroup__Item Polaris-ButtonGroup__Item--plain">' . $selling_plan_create_card_array['total_selling_plan_count'] . '</div>

			  </div>

			  </div>

			  <div class="sd_selling_plans">

			  <div class="list-selling-plans-detail">Delivery Every ' . $three_selling_plans . '</div>

			  ';





		$create_list_card_html  .= '</div>

		   </div>
		   </div>

		   <div class="Polaris-Card__Footer">

			  <div class="Polaris-Stack Polaris-Stack--alignmentCenter">

				<div class="Polaris-Card__Section inner-box-cont bot-bx">

	   <div class="Polaris-Card__SectionHeader box-header">

	   <a href="javascript:;" class="edit-subscription-group" subscription-group-id="' . $selling_plan_create_card_array['selling_plan_group_id'] . '" data-type="products">

	   <h3 aria-label="Items" class="Polaris-Subheading">Products</h3>

	   </a>

	   <div class="Polaris-ButtonGroup">

		  <div class="Polaris-ButtonGroup__Item Polaris-ButtonGroup__Item--plain">' . count((array)$selling_plan_create_card_array['product_id_array']) . '</div>

	   </div>

        </div>

					<div class="Polaris-ButtonGroup">

					   <div class="Polaris-ButtonGroup__ItemEdit"><button subscription-group-id="' . $selling_plan_create_card_array['selling_plan_group_id'] . '" class="Polaris-Button Polaris-Button--primary light-bg sd_button  edit-subscription-group Polaris-ButtonGroup__ItemEditButton" type="button"><span class="Polaris-Button__Content">Edit</span></button></div>

					   <div class="Polaris-ButtonGroup__Item">

						  <button class="Polaris-Button remove-btn delete_subscription_plan"  subscription-group-id="' . $selling_plan_create_card_array['selling_plan_group_id'] . '" type="button">

								<svg width="40" height="38" viewBox="0 0 40 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <rect x="0.905273" width="38.3448" height="38" rx="10" fill="#141E2D"></rect>
                                 <rect x="1.40527" y="0.5" width="37.3448" height="37" rx="9.5" stroke="white" stroke-opacity="0.2"></rect>
                                 <path d="M21.9238 17.3448C22.4336 17.3448 22.8469 17.7154 22.8469 18.1724V22.8621C22.8469 23.3191 22.4336 23.6897 21.9238 23.6897C21.414 23.6897 21.0008 23.3191 21.0008 22.8621V18.1724C21.0008 17.7154 21.414 17.3448 21.9238 17.3448Z" fill="white"></path>
                                 <path d="M19.1546 18.1724C19.1546 17.7154 18.7413 17.3448 18.2315 17.3448C17.7217 17.3448 17.3085 17.7154 17.3085 18.1724V22.8621C17.3085 23.3191 17.7217 23.6897 18.2315 23.6897C18.7413 23.6897 19.1546 23.3191 19.1546 22.8621V18.1724Z" fill="white"></path>
                                 <path fill-rule="evenodd" clip-rule="evenodd" d="M16.693 14.0345C16.693 12.3586 18.2083 11 20.0776 11C21.9469 11 23.4622 12.3586 23.4622 14.0345H27.1546C27.6644 14.0345 28.0777 14.405 28.0777 14.8621C28.0777 15.3191 27.6644 15.6897 27.1546 15.6897H26.2314L26.2313 21.7036C26.2312 23.5575 26.2312 24.4845 25.8288 25.1926C25.4748 25.8154 24.9099 26.3218 24.2152 26.6392C23.4253 27 22.3914 27 20.3235 27H19.8315C17.7636 27 16.7296 27 15.9398 26.6392C15.245 26.3218 14.6801 25.8154 14.3261 25.1925C13.9237 24.4843 13.9237 23.5573 13.9238 21.7033L13.9239 15.6897H13.0008C12.491 15.6897 12.0777 15.3191 12.0777 14.8621C12.0777 14.405 12.491 14.0345 13.0008 14.0345H16.693ZM18.5391 14.0345C18.5391 13.2727 19.2279 12.6552 20.0776 12.6552C20.9273 12.6552 21.6161 13.2727 21.6161 14.0345H18.5391ZM15.7701 15.6897H24.3852L24.3851 21.7035C24.3851 22.6578 24.3836 23.274 24.3409 23.7429C24.2999 24.1926 24.2303 24.3593 24.1838 24.4411C24.0068 24.7525 23.7244 25.0057 23.377 25.1644C23.2857 25.2061 23.0998 25.2685 22.5983 25.3052C22.0752 25.3435 21.3879 25.3448 20.3235 25.3448H19.8315C18.7671 25.3448 18.0798 25.3435 17.5567 25.3052C17.0551 25.2685 16.8692 25.2061 16.7779 25.1644C16.4305 25.0057 16.1481 24.7525 15.9711 24.4411C15.9246 24.3592 15.855 24.1925 15.8141 23.7429C15.7713 23.2739 15.7699 22.6577 15.7699 21.7034L15.7701 15.6897Z" fill="white"></path>
                              </svg>

								

						  </button>

					   </div>

					</div>

					<div id="PolarisPortalsContainer"></div>

				 </div>

			  </div>
		     </div>
		   </div>

		</div>';

		return $create_list_card_html;
	}



	public function add_subscription_products($addproducts, $subscription_plan_id)
	{

		try {

			$graphQL_sellingPlanGroupAddProducts = 'mutation {

				sellingPlanGroupAddProductVariants(

				id: "' . $subscription_plan_id . '"

				productVariantIds: ' . $addproducts . '

				) {



				userErrors {

				field

				message

				}

				}

			}';

			$sellingPlanGroupAddProductsapi_execution = $this->shopify_graphql_object->GraphQL->post($graphQL_sellingPlanGroupAddProducts);
		} catch (Exception $e) {

			return json_encode(array("status" => false, 'error' => $e->getMessage())); // return json

		}
	}



	public function create_numeric_array($array)
	{

		$arrayTemp = array();

		$i = 0;

		foreach ($array as $key => $val) {

			$arrayTemp[$i] = $val;

			$i++;
		}

		return $arrayTemp;
	}







	public function createWebhooks($create_webhooks)
	{

		foreach ($create_webhooks as $key => $val) {

			try {

				$createWebhook =  'mutation webhookSubscriptionCreate($topic: WebhookSubscriptionTopic!, $webhookSubscription: WebhookSubscriptionInput!) {

					webhookSubscriptionCreate(topic: $topic, webhookSubscription: $webhookSubscription) {

					userErrors {

						field

						message

					}

					webhookSubscription {

						id

					}

					}

				}';

				$webhookParameters = [

					"topic" => $key,

					"webhookSubscription" => [

						"callbackUrl" => $val,

						"format" => "JSON"

					]

				];

				$createWebhookGet = $this->graphqlQuery($createWebhook, null, null, $webhookParameters);
			} catch (Exception $e) {

				echo $e;
			}
		}
	}





	// public function defaultProductSetting($store_id){

	// 	$productSettingArray = Array

	// 	(

	// 	"widgetTitle" =>"Purchase Options",

	// 	"oneTimePurchaseOptionHeading" => "One Time Purchase",

	// 	"subscriptionOptionHeading" => "Subscribe and Save",

	// 	"frequencyPlansDisplayHeading" =>  "Delivery Every",

	// 	"frequencyPlanTitleText" => "{{sellingPlanName}}",

	// 	"showTooltip" => 'Yes',

	// 	"tooltipTitle" =>  "Subscription detail",

	// 	"defaultTooltipDesctiption" =>  "Below are the details",

	// 	"pricePlacement"=>"BEFORE",

	// 	"text_colour" => "#242424",

	// 	"background_colour" => "transparent",

	// 	"border_colour" => "#1e2d4d",

	// 	"radio_button_colour" => "#9edda3",

	// 	"tooltip_Icon_Color" => "#e79d24",

	// 	"Tooltip_Text_Color" =>  "#60b63d",

	// 	"top_margin" => 20,

	// 	"bottom_margin" => 20,

	// 	"border_thickness" => 5,

	// 	"created_at" => date("Y-m-d h:i:s")

	// 	);

	//     return $productSettingArray;

	// }



	public function recurringBillingCharge($billingData)
	{

		$getCurrentUtcTime = $this->getCurrentUtcTime();

		$wherestoreCondition = array(

			'store' => $billingData['store']

		);

		$trial_used = $this->table_row_check('trial_used', $wherestoreCondition, 'and');

		if ($billingData['pageType'] == 'on_installation') {

			//get access token

			$shopifyClient = new ShopifyClient($billingData['store'], "", $this->SHOPIFY_APIKEY, $this->SHOPIFY_SECRET);

			$access_token = $shopifyClient->getAccessToken($billingData['sd_code']);

			//save data in the intall table

			if ($access_token != '') {

				//check entry in uninstall table

				$fields = array('store_id');

				$uninstall_details = $this->table_row_value('uninstalls', $fields, $wherestoreCondition, 'and', '');

				if ($uninstall_details) { // it means the app is reinstalls within 48 hours then insert the previous store_id in the install table

					$store_id = $uninstall_details[0]['store_id'];

					$fields = array(

						'id' => $store_id,

						'store' =>  $billingData['store'],

						'access_token' => $access_token,

						'app_status' => '1',

						'user_type' => 'new',

						'created_at' => $getCurrentUtcTime

					);
				} else { // insert entry with new store_id

					$fields = array(

						'store' =>  $billingData['store'],

						'access_token' => $access_token,

						'app_status' => '1',

						// 'new_install' => '0',

						'user_type' => 'new',

						'created_at' => $getCurrentUtcTime

					);
				}

				// echo '<pre>';

				// print_r($fields);

				$saveInstallData = $this->insert_row('install', $fields);

				//save data in the intall table end

				$this->storeInstallDetails($billingData['store'], 'set');

				$this->init_GraphQL_Object();

				// add metafields in the app

				$theme_id = $this->getActiveTheme();

				$this->addAppMetafields($theme_id);

				//add uninstall webhook

				$create_webhooks = array(

					"APP_UNINSTALLED" => $this->SHOPIFY_DOMAIN_URL . "/application/webhooks/app_uninstall.php"

				);

				$this->createWebhooks($create_webhooks);

				//get shop data

				$storeData = $this->getShopData($billingData['store']);

				$storeEmail =  $storeData['shop']['email'];

				$currency = $storeData['shop']['currency'];

				$shopFormat = $storeData['shop']['money_in_emails_format'];

				$currencyCode1 = str_replace("{{amount}}", "", $shopFormat);

				$currencyCode = str_replace("{{amount_with_comma_separator}}", "", $currencyCode1);

				$shop_timezone = $storeData['shop']['iana_timezone'];

				$shop_owner = $storeData['shop']['shop_owner'];

				$shop_name = $storeData['shop']['name'];

				$shop_plan = $storeData['shop']['plan_name'];

				if ($storeData['shop']['plan_display_name'] == 'Shopify Plus') {

					$plusPlan = '1';
				} else {

					$plusPlan = '0';
				}

				if ($saveInstallData) {

					$savedDataArray = json_decode($saveInstallData);

					$store_id = $savedDataArray->id;

					//Insert data in store_details table

					$whereStoreDetailCondition = array(

						'store_id' => $store_id

					);

					$fields = array(

						'store_id' => $store_id,

						'store_email' => $storeEmail,

						'shopify_plus' => $plusPlan,

						'currency' => $currency,

						'currencyCode' => $currencyCode,

						'shop_timezone' => $shop_timezone,

						'owner_name' => $shop_owner,

						'shop_name' => $shop_name,

						'shop_plan' => $shop_plan

					);

					$this->insertupdateajax('store_details', $fields, $whereStoreDetailCondition, 'and');
				}
			}

			// $updatePlan = 'Yes';

		}

		$ActiveStoreidCondition = array(

			'store_id' => $store_id,

			'status' => 1,

		);

		$fields = array(

			'status' => 0

		);

		$updateInstallData = $this->update_row('storeInstallOffers', $fields, $ActiveStoreidCondition, 'and');

		$fields = array(

			'plan_id' => 1,

			'store_id' => $store_id,

			'planName' => $billingData['sd_planName'],

			'subscriptionPlans' => '-1', // empty value for free plan

			'subscriptionContracts' => '-1', //empty value for free plna

			'price' => 0,

			'trial' => $billingData['sd_trialDays'],

			'subscription_emails' => 10000,

			'created_at' => $this->created_at

		);

		$saveInstallData = $this->insert_row('storeInstallOffers', $fields);

		return json_encode(array("status" => true, 'confirmationUrl' => $this->SHOPIFY_DOMAIN_URL . '/admin/dashboard.php?themeConfiguration=' . $billingData['sd_configureTheme'] . '&shop=' . $billingData['store'], 'recurring_id' => '')); // return json

	}



	public function getAllThemes()
	{

		$shopifyClient = new ShopifyClient($this->store, $this->access_token, $this->SHOPIFY_APIKEY, $this->SHOPIFY_SECRET);

		return $shopifyClient->call("GET", "/admin/api/" . $this->SHOPIFY_API_VERSION . "/themes.json");
	}



	public function themeConfiguration()
	{

		$whereStoreDetailCondition = array(

			'store_id' => $this->store_id

		);

		$currentUtcDate = $this->getCurrentUtcTime();

		//insert data into productpagesetting table

		// $fields = array(

		// 	'store_id' => $this->store_id,

		// 	'widgetTitle' => 'Purchase Options',

		// 	'oneTimePurchaseOptionHeading' => 'One Time Purchase',

		// 	'payperdeliveryOptionHeading' => 'Pay Per Delivery Subscription',

		// 	'prepaidOptionHeading' => 'Prepaid Subscription',

		// 	'pay_per_select_label' => 'Delivery Every',

		// 	'prepaid_select_label' => 'Pre-pay For',

		// 	'discount_label' => 'Save',

		// 	'price_text' => 'each',

		// 	'showPlanDescription' => 'Yes',

		// 	'planDescriptionTitle' => 'Subscription detail',

		// 	'oneTimePurchaseDescription' => 'One Time Purchase Product',

		// 	'border_style' => 'none',

		// 	'border_radious' => '0',

		// 	'radio_button_colour' => '#9edda3',

		// 	'top_margin' => '0',

		// 	'bottom_margin' => '0',

		// 	'border_thickness' => '0',

		// );

		// $this->insertupdateajax('productPageSetting',$fields,$whereStoreDetailCondition,'and');

		//insert data into productpagesetting table end



		//insert data into customer_settings table

		$fields = array(

			'store_id' => $this->store_id,

		);

		$this->insertupdateajax('customer_settings', $fields, $whereStoreDetailCondition, 'and');

		//insert data into customer_settings table end



		// insert data into contract setting table

		$fields = array(

			'store_id' => $this->store_id,

			'afterBillingAttemptFail' => 'Active',

			'remove_product' => 'Yes',

			'after_product_delete_contract' => 'Pause'

		);

		$this->insertupdateajax('contract_setting', $fields, $whereStoreDetailCondition, 'and');



		//insert data into email setting table

		$fields = array(

			'store_id' => $this->store_id,

		);

		$this->insertupdateajax('email_notification_setting', $fields, $whereStoreDetailCondition, 'and');

		// insert data into email_counter table

		$this->insertupdateajax('email_counter', $whereStoreDetailCondition, $whereStoreDetailCondition, 'and');

		// insert data into email_counter table end



		// add webhooks

		// $create_webhooks = array(

		// 	"APP_UNINSTALLED" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/app_uninstall.php",

		// 	"PRODUCTS_DELETE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/product_delete.php",

		// 	"PRODUCTS_UPDATE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/product_update.php",

		// 	"CUSTOMER_PAYMENT_METHODS_UPDATE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/customer_payment_methods_update.php",

		// 	"CUSTOMER_PAYMENT_METHODS_CREATE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/customer_payment_methods_create.php",

		// 	"CUSTOMER_PAYMENT_METHODS_REVOKE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/customer_payment_methods_revoke.php",

		// 	"CUSTOMERS_UPDATE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/customers_update.php",

		// 	"SUBSCRIPTION_CONTRACTS_CREATE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/subscription_contracts_create.php",

		// 	"SUBSCRIPTION_CONTRACTS_UPDATE" =>  $this->SHOPIFY_DOMAIN_URL."/application/webhooks/subscription_contracts_update.php",

		// 	"SUBSCRIPTION_BILLING_ATTEMPTS_SUCCESS" =>  $this->SHOPIFY_DOMAIN_URL."/application/webhooks/subscription_billing_attempts_success.php",

		// 	"SUBSCRIPTION_BILLING_ATTEMPTS_FAILURE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/subscription_billing_attempts_failure.php",

		// 	"SUBSCRIPTION_BILLING_ATTEMPTS_CHALLENGED"=>$this->SHOPIFY_DOMAIN_URL."/application/webhooks/subscription_billing_attempts_challenged.php",

		// 	"SHOP_UPDATE" => $this->SHOPIFY_DOMAIN_URL."/application/webhooks/shop_update.php",

		// 	"THEMES_UPDATE" =>  $this->SHOPIFY_DOMAIN_URL."/application/webhooks/theme_update.php",

		// );

		// $this->createWebhooks($create_webhooks);

	}



	public function planName_Duplicacy($planName)
	{  
		$where = array(

			'store_id' => $this->store_id,

			'plan_name' => htmlspecialchars($planName, ENT_QUOTES)

		);

		$checkSameNamePlanExist = $this->table_row_check('subscriptionPlanGroups', $where, 'and');

		if ($checkSameNamePlanExist) {

			return json_encode(array("status" => false, 'error' => 'Same Plan Group Name error', 'message' => 'Plan Group with same name already exist')); // return json

		} else {

			return json_encode(array("status" => true));
		}
	}



	public function mini_subscription_planName_change($data)
	{

		$where = "store_id = '" . $this->store_id . "' AND subscription_plangroup_id = '" . $data['subscription_group_id'] . "'";

		$checksubscription_plangroup_idExist = $this->table_row_check('subscriptionPlanGroups', $where, '');

		if (!$checksubscription_plangroup_idExist) {

			return json_encode(array("status" => 404, 'error' => 'Subscription Group Id not found', 'message' => 'Subscription Group Id not found')); // return json

		}

		$where = "store_id = '" . $this->store_id . "' AND  LOWER(plan_name) = LOWER('" . htmlspecialchars($data['plan_name'], ENT_QUOTES) . "') AND subscription_plangroup_id != '" . $data['subscription_group_id'] . "'";

		$checkSameNamePlanExist = $this->table_row_check('subscriptionPlanGroups', $where, '');

		if ($checkSameNamePlanExist) {

			return json_encode(array("status" => false, 'error' => 'Same Plan Group Name error', 'message' => 'Plan Group with same name already exist')); // return json

		}



		$fields = array("plan_name" => htmlspecialchars($data['plan_name'], ENT_QUOTES));

		$whereCondition = array("subscription_plangroup_id " => $data['subscription_group_id']);

		return $response = $this->update_row('subscriptionPlanGroups', $fields, $whereCondition, 'and');
	}



	// -------------------------------------------For frontend -----------------------------------------



	// contract_line_item_id

	public function getCustomerSubscriptionDetails($contractID, $store_id)
	{

		$pastOdrerData = $this->getContractOrders($contractID, ''); // get all the product variant data

		//get payment method token

		$contractMethodToken = $this->getContractPaymentToken($contractID);

		$paymentMethodToken =  substr($contractMethodToken['data']['subscriptionContract']['customerPaymentMethod']['id'], strrpos($contractMethodToken['data']['subscriptionContract']['customerPaymentMethod']['id'], '/') + 1);

		$whereCondition = array(

			'contract_id' => $contractID

		);



		$getContractData = $this->customQuery("SELECT ct.discount,ct.discount_type,p.payment_instrument_value,p.payment_method_token,o.next_billing_date,o.order_no,o.order_id,o.created_at,o.updated_at,o.contract_status,o.contract_products,o.delivery_policy_value,o.billing_policy_value,o.delivery_billing_type,a.first_name as shipping_first_name,a.last_name as shipping_last_name,a.address1 as shipping_address1,a.address2 as shipping_address2,a.city as shipping_city,a.province as shipping_province,a.country as shipping_country,a.company as shipping_company,a.phone as shipping_phone,a.province_code as shipping_province_code,a.country_code as shipping_country_code,a.zip as shipping_zip,a.delivery_method as shipping_delivery_method,a.delivery_price as shipping_delivery_price,b.first_name as billing_first_name,b.last_name as billing_last_name,b.address1 as billing_address1,b.address2 as billing_address2,b.city as billing_city,b.province as billing_province,b.country as billing_country,b.company as billing_company,b.phone as billing_phone,b.province_code as billing_province_code,b.country_code as billing_country_code,b.zip as billing_zip,d.currency,d.currencyCode,d.store_email,d.shop_timezone,c.name,c.email,cs.cancel_subscription, cs.skip_upcoming_order,cs.skip_upcoming_fulfillment,cs.pause_resume_subscription, cs.add_subscription_product,cs.edit_product_quantity, cs.delete_product,cs.edit_shipping_address,cs.edit_out_of_stock_product_quantity,cs.add_out_of_stock_product from subscriptionOrderContract as o

		INNER JOIN subscriptionContractShippingAddress as a ON o.contract_id = a.contract_id

		INNER JOIN contract_setting as ct ON o.store_id = ct.store_id

		INNER JOIN subscriptionContractBillingAddress as b ON o.contract_id = b.contract_id

		INNER JOIN customers as c ON c.shopify_customer_id = o.shopify_customer_id

		INNER JOIN store_details as d ON d.store_id = a.store_id

		INNER JOIN customer_settings as cs ON cs.store_id = o.store_id

		INNER JOIN customerContractPaymentmethod AS p ON p.store_id = o.store_id where o.contract_id = '$contractID' and p.payment_method_token = '$paymentMethodToken'");

		if (!empty($getContractData)) {

			$get_billing_Attempts = $this->table_row_value('billingAttempts', 'all', $whereCondition, 'and', '');

			$contract_product_details = $this->table_row_value('subscritionOrderContractProductDetails', 'all', $whereCondition, 'and', '');

			$whereOrderCondition = array(

				'order_id' => substr($pastOdrerData['id'], strrpos($pastOdrerData['id'], '/') + 1),

				'contract_id' => $contractID

			);

			$getRescheduleOrders = $this->table_row_value('reschedule_fulfillment', 'all', $whereOrderCondition, 'and', '');

			$variant_ids_array = [];

			if ($getContractData[0]['edit_out_of_stock_product_quantity'] == '0') {

				$variant_string = '';

				foreach ($contract_product_details as $key => $value) {

					if ($value['product_shopify_status'] == 'Active' && $value['product_contract_status'] == '1')

						$variant_string .= ',"gid://shopify/ProductVariant/' . $value['variant_id'] . '"';
				}

				$variant_string = substr($variant_string, 1); // remove leading ","

				$variantIds_string = '[' . $variant_string . ']';

				$variantDetails = $this->getVariantDetail($variantIds_string); // get variant inventory quantity from api

				$variant_ids_array = $variantDetails['data']['nodes'];
			}

			echo json_encode(array("pastOrdersHtml" => $pastOdrerData, "billingAttemptData" => $get_billing_Attempts, "orderContractDetails" => $getContractData, "contract_product_details" => $contract_product_details, "variant_inventory_array" => $variant_ids_array, "reschedule_orders" => $getRescheduleOrders));
		} else {

			echo json_encode(array("pastOrdersHtml" => '', "billingAttemptData" => '', "orderContractDetails" => '', "contract_product_details" => '', "variant_inventory_array" => '', "reschedule_orders" => ''));
		}
	}

	// public function productpagesetting($storeID){

	// 	$whereCondition = array('store_id'=> $storeID );

	// 	$productpagesetting = $this->table_row_value('productPageSetting','*',$whereCondition,'and','');

	// 	return json_encode($productpagesetting);

	// }





	public function subscriptionPlanGroupsDetails($data)
	{

		$app_subscription_status = $this->getBillingStatus();

		$whereStoreCondition = array(

			'store' => $data['store']

		);

		$fields = array('id');

		$store_id_data = $this->table_row_value('install', $fields, $whereStoreCondition, 'and', '');

		$whereCondition = array(

			'store_id' => $this->store_id

		);

		$store_widget_data = $this->single_row_value('widget_settings', 'All', $whereCondition, 'and', '');



		$store_id =  $store_id_data[0]['id'];

		$whereGroupCondition = array(

			'store_id' => $store_id,

			'product_id' => $data['product_id']

		);

		$subscription_plan_group_id = $this->table_row_value('subscriptionPlanGroupsProducts', 'subscription_plan_group_id', $whereGroupCondition, 'and', '');

		if ($subscription_plan_group_id) {

			$subscription_group_ids = array_column($subscription_plan_group_id, 'subscription_plan_group_id');

			$subscription_group_ids_string = implode(',', $subscription_group_ids);

			$subscriptionPlanGroupsDetails = $this->customQuery("SELECT max_cycle,delivery_policy,delivery_billing_type,billing_policy,discount_value,selling_plan_id,plan_type,discount_type,discount_offer,recurring_discount_offer,change_discount_after_cycle,discount_type_after,discount_value_after FROM subscriptionPlanGroupsDetails WHERE store_id = '$store_id' and subscription_plan_group_id IN ($subscription_group_ids_string)");
		} else {

			$subscriptionPlanGroupsDetails = '';
		}

		$whereCondition = array(

			'store_id' => $store_id

		);

		return json_encode(array('subscriptionPlanGroupsDetails' => $subscriptionPlanGroupsDetails, 'app_subscription_status' => $app_subscription_status, 'store_widget_data' => $store_widget_data));
	}
	// Membership functions +++

	// public function memberPlanNameValidation()
	// {
	//     $shop = $request->get('shop');
	//     $member_plan_name = $request->input('member_plan_name');
	//     $existing_plan = DB::table('membership_plans')->where('store', $shop)->where('plan_status','enable')->where( DB::raw('LOWER(membership_plan_name)'), strtolower($member_plan_name))->first();
	//     if ($existing_plan) {
	//         $result = array('isError' => true, 'message' => "Plan name with same name already exists");
	//         return json_encode($result);
	//     } else {
	//         $result = array('isError' => false);
	//         return json_encode($result);
	//     }
	// }

	public function sendTestEmail1($data)
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

		// print_r($template_data);
		// die;
		// Fetch email template
		$email_template = $this->membershipAllEmailTemplates($template_data);

		if (!empty($email_template)) {

			$sendMailArray = array(
				'sendTo'      => $data['email_template_array']['send_to_email'],
				'subject'     => $email_template['subject'],
				'mailBody'    =>  $email_template['email_template_html'],
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

	public function emailNotificationSettings($request)
	{

		$store = $request['shop'];
		$checkBoxValue = $request['checkBoxValue'];
		$column_name = $request['column_name'];

		$updateData = [$column_name => $checkBoxValue, 'store' => $store];

		$whereStoreDetailCondition = array(

			'store' => $store
		);



		$this->insertupdateajax('email_notification_settings', $updateData, $whereStoreDetailCondition, '');

		return json_encode(["status" => true, 'message' => 'Settings updated successfully']);
	}

	// Automatic discount
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
			$discount_name          = $decodedData['discount_name'] ?? '';
			$discount_value         = $decodedData['discount_value'] ?? '';
			$discount_checked_value = $decodedData['discount_checked_value'] ?? 'Inactive';
			$discount_product_id    = $decodedData['discount_select_products_id'] ?? '';
			$discount_collection_id = $decodedData['discount_select_collections_id'] ?? '';
			$discount_function_id =   $decodedData['discount_function_id'] ?? '';
			$start_date_type = gmdate("Y-m-d\TH:i:s\Z");
			$end_date = new DateTime("now", new DateTimeZone("UTC"));
			$end_date->modify('+1 year');
			$end_date_type = $end_date->format("Y-m-d\TH:i:s\Z");
    
			$setMetafield = 'mutation MetafieldsSet($metafields: [MetafieldsSetInput!]!) {
				metafieldsSet(metafields: $metafields) {
					metafields {
						key
						namespace
						value
						createdAt
						updatedAt
					}
					userErrors {
						field
						message
						code
					}
				}
			}';

			if (!$discount_function_id) {

				$createDiscount = 'mutation discountAutomaticAppCreate($automaticAppDiscount: DiscountAutomaticAppInput!) {
					discountAutomaticAppCreate(automaticAppDiscount: $automaticAppDiscount) {
						userErrors {
							field
							message
						}
						automaticAppDiscount {
							discountId
							title
							startsAt
							endsAt
							status
							appDiscountType {
								appKey
								functionId
							}
							combinesWith {
								orderDiscounts
								productDiscounts
								shippingDiscounts
							}
						}
					}
				}';

				$variables = [
					"automaticAppDiscount" => [
						"title" => $discount_name,
						"functionId" => $SHOPIFY_FUNCTION_ID,
						"startsAt" => $start_date_type,
						"discountClasses" => ['ORDER'],
						"combinesWith" => [
							"orderDiscounts" => false,
							"productDiscounts" => false,
							"shippingDiscounts" => false
						]
					]
				];

				$graphQL_get_discount_status = $this->shopify_graphql_object->GraphQL->post($createDiscount, null, null, $variables);
				$graphQL_get_discount_status_error = $graphQL_get_discount_status['data']['discountAutomaticAppCreate']['userErrors'];
				$discount_function_id = $graphQL_get_discount_status['data']['discountAutomaticAppCreate']['automaticAppDiscount']['discountId'];
		   
			}

			// echo ($discount_checked_value);
			$metafieldVariables = [
				"metafields" => [
					[
						"key" => "function-configuration",
						"namespace" => "phoenix-automatic-discount",
						"ownerId" => $discount_function_id,
						"type" => "json",
						"value" => json_encode([
							'cartLinePercentage' =>  $discount_value,
							'productId' => $discount_product_id,
							'collectionIds' => $discount_collection_id,
							'status' => $discount_checked_value,
							'title' => $discount_name
						])
					]
				]
			];

			try {

				$graphQL_get_metafield_status = $this->shopify_graphql_object->GraphQL->post($setMetafield, null, null, $metafieldVariables);
				// print_r($graphQL_get_metafield_status);
			} catch (\Throwable $th) {
				throw $th;
			}

			if (empty($graphQL_get_discount_status_error)) {
				$productRow = [
					'store'          => $this->store,
					'discount_name'  => $discount_name,
					'discount_value' => $discount_value,
					'status'         => $discount_checked_value,
					'product_id'     => $discount_product_id,
					'collection_id'  => $discount_collection_id,
					'discount_id'    => $discount_function_id ?? '',
					'product_title'  => $decodedData['discount_select_products_title'] ?? '',
					'collection_title'  => $decodedData['discount_select_collections_title'] ?? '',
					'product_image'  => $decodedData['discount_select_products_image'] ?? '',
					'collection_image'  => $decodedData['discount_select_collections_image'] ?? '',
					'created_at'     => date('Y-m-d'),
				];

				$productRowDetails = array_filter($productRow, fn($val) => $val !== null && $val !== '');
				$wherePro = [
					'store'      => $this->store,
				];

				$productInsert = $this->insertupdateajax('automatic_discount', $productRowDetails, $wherePro, 'AND');

				return json_encode([
					"status" => true,
					"message" => "Discount saved successfully.",
					"discount_details" => $productInsert,
				], JSON_PRETTY_PRINT);
			} else {
				return json_encode([
					"status" => false,
					"message" => "GraphQL Error",
					"error"  => $graphQL_get_discount_status_error
				], JSON_PRETTY_PRINT);
			}
		} catch (\Exception $e) {
			return json_encode([
				"status" => false,
				"message" => "Exception: " . $e->getMessage()
			], JSON_PRETTY_PRINT);
		}
	}
	public function getAppPlan()
	{
		$query =
			'{
					currentAppInstallation {
						activeSubscriptions {
						name
						status
						lineItems {
							id
							plan {
								pricingDetails {
									__typename
									... on AppRecurringPricing {
									price {
										amount
										currencyCode
									}
									}
									... on AppUsagePricing {
									cappedAmount {
										amount
										currencyCode
									}
									terms
									}
								}
							}
						}
					}
				}
			}';
		$data = $this->shopify_graphql_object->GraphQL->post($query, null, null, null);
		return $data;
		// print_r($data);
	}
}
