<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json');

$dirPath = dirname(__DIR__);
include($dirPath . "/application/library/config.php");

$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : null;

$draw            = $_POST['draw'] ?? '';
$row             = intval($_POST['start'] ?? '');
$rowperpage      = intval($_POST['length'] ?? '');
$searchValue     = $_POST['search']['value'] ?? '';
$orderColumnIndex = intval($_POST['order'][0]['column'] ?? '');
$orderColumn     = $_POST['columns'][$orderColumnIndex]['data'] ?? '';
$orderDir        = (($_POST['order'][0]['dir'] ?? '') === 'desc') ? 'DESC' : 'ASC';



// $get_contract_orders_qry = $db->query("SELECT * FROM subscriptionOrderContract INNER JOIN customers ON subscriptionOrderContract.shopify_customer_id = customers.shopify_customer_id WHERE subscriptionOrderContract.store_id = '$store_id' group by subscriptionOrderContract.order_id order by subscriptionOrderContract.order_no desc");
// $get_contract_order = $get_contract_orders_qry->fetchAll(PDO::FETCH_ASSOC);



// Build search for both sides (note table aliases differ)
$searchSoc = "";
$searchBa  = "";
$params = [
  ':store_id'     => $store_id,
  ':row'          => (int)$row,
  ':rowperpage'   => (int)$rowperpage,
];

if (!empty($searchValue)) {
  $searchSoc = " AND (c.name LIKE :search OR c.email LIKE :search OR soc.order_no LIKE :search)";
  $searchBa  = " AND (c.name LIKE :search OR c.email LIKE :search OR ba.order_no LIKE :search)";
  $params[':search'] = "%{$searchValue}%";
}

$sql = "
    SELECT
        t.*,
        -- filtered count across the UNION result
        COUNT(*) OVER() AS totalFiltered,

        -- total (unfiltered) rows from BOTH sources combined
        (
            (SELECT COUNT(DISTINCT soc2.order_id)
             FROM subscriptionOrderContract soc2
             WHERE soc2.store_id = :store_id)
            +
            (SELECT COUNT(*)
             FROM billingAttempts ba2
             WHERE ba2.store_id = :store_id
               AND ba2.status = 'Success')
        ) AS totalRecords
    FROM (
        -- Subscriptions side (grouped by order_id like your original)
        SELECT 
            soc.id,
            soc.order_id,
            soc.contract_id,
            soc.order_no,
            soc.plan_type,
            soc.created_at,
            c.name,
            c.email,
            c.store,
            'subscription' AS record_type
        FROM subscriptionOrderContract soc
        INNER JOIN customers c
            ON soc.shopify_customer_id = c.shopify_customer_id
        WHERE soc.store_id = :store_id
        $searchSoc
        GROUP BY soc.order_id

        UNION ALL

        -- Billing attempts side (successful only)
        SELECT
            ba.id,
            ba.order_id,
            ba.contract_id,
            ba.order_no,
            plan_type,
            ba.created_at,
            c.name,
            c.email,
            c.store,
            'billing' AS record_type
        FROM billingAttempts ba
        INNER JOIN customers c
            ON ba.shopify_customer_id = c.shopify_customer_id
        WHERE ba.store_id = :store_id
          AND ba.status = 'Success'
          $searchBa
    ) AS t
    ORDER BY t.order_no DESC
    LIMIT :row, :rowperpage
";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) {
  $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$getContractData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Extract totals (same on every row)
$totalRecords  = !empty($rows) ? (int)$getContractData[0]['totalRecords']  : 0;
$totalFiltered = !empty($rows) ? (int)$getContractData[0]['totalFiltered'] : 0;


// echo('<pre>');
// echo(count($getContractData));
// print_r($getContractData);

// $getContractData = array_merge($get_contract_orders, $get_billingAttempts_orders);


// Totals for DataTables
$totalRecords  = !empty($getContractData) ? $getContractData[0]['totalRecords'] : 0;
$totalFiltered = !empty($getContractData) ? $getContractData[0]['totalFiltered'] : 0;

$data = [];

$get_contract_orders_qry = $db->query("SELECT shop_timezone, shop FROM store_details WHERE store_id = '$store_id' LIMIT 1");


$get_store_details = $get_contract_orders_qry->fetch(PDO::FETCH_ASSOC);
$shop_timezone = $get_store_details['shop_timezone'];


function getShopTimezoneDate($date, $shop_timezone)
{

  $dt = new DateTime($date);
  $tz = new DateTimeZone($shop_timezone); // or whatever zone you're after
  $dt->setTimezone($tz);
  $dateTime = $dt->format('Y-m-d H:i:s');
  $shopify_date = date("d M Y", strtotime($dateTime));

  return $shopify_date;
}


if (!empty($getContractData)) {
  foreach ($getContractData as $value) {

    $store = $get_store_details['shop'];
    $order_id = $value['order_id'];
    $customer_name = $value['name'];
    $customer_email = $value['email'];

    // initials name
    $initials = '';
    // if (!empty($value['name'])) {

    //   //  $initials = implode('', array_map(fn($n) => strtoupper($n[0]), explode(' ', $value['name'])));
    //   $nameParts = explode(' ', $customer_name);
    //   $initials = strtoupper(
    //     (isset($nameParts[0]) ? substr($nameParts[0], 0, 1) : '') .
    //       (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    //   );
    // }


  if (!empty($value['name'])) {

    $nameParts = explode(' ', $customer_name);

    $initials = mb_strtoupper(
        (isset($nameParts[0]) ? mb_substr($nameParts[0], 0, 1, 'UTF-8') : '') .
        (isset($nameParts[1]) ? mb_substr($nameParts[1], 0, 1, 'UTF-8') : ''),
        'UTF-8'
    );
  }



    $actions = '
        <a href="downloadInvoice.php?shop=' . $store . '&order_id=' . $order_id . '" title="Print/Download" class="sd_subscriptionordertext">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="22 12 40 40" fill="none">

                      <g>

                        <rect x="22" y="12" width="40" height="40" rx="10" fill="#141E2D" />

                        <rect x="22.5" y="12.5" width="39" height="39" rx="9.5" stroke="white" stroke-opacity="0.2" />

                        <path d="M35.4375 39.5C34.8573 39.5 34.3009 39.2695 33.8907 38.8593C33.4805 38.4491 33.25 37.8927 33.25 37.3125V34.1875C33.25 33.9389 33.3488 33.7004 33.5246 33.5246C33.7004 33.3488 33.9389 33.25 34.1875 33.25C34.4361 33.25 34.6746 33.3488 34.8504 33.5246C35.0262 33.7004 35.125 33.9389 35.125 34.1875V37.3125C35.125 37.485 35.265 37.625 35.4375 37.625H48.5625C48.6454 37.625 48.7249 37.5921 48.7835 37.5335C48.8421 37.4749 48.875 37.3954 48.875 37.3125V34.1875C48.875 33.9389 48.9738 33.7004 49.1496 33.5246C49.3254 33.3488 49.5639 33.25 49.8125 33.25C50.0611 33.25 50.2996 33.3488 50.4754 33.5246C50.6512 33.7004 50.75 33.9389 50.75 34.1875V37.3125C50.75 37.8927 50.5195 38.4491 50.1093 38.8593C49.6991 39.2695 49.1427 39.5 48.5625 39.5H35.4375Z" fill="white" />

                        <path d="M41.0624 31.6112V24.5C41.0624 24.2514 41.1612 24.0129 41.337 23.8371C41.5128 23.6613 41.7513 23.5625 41.9999 23.5625C42.2485 23.5625 42.487 23.6613 42.6628 23.8371C42.8386 24.0129 42.9374 24.2514 42.9374 24.5V31.6112L45.3999 29.15C45.4869 29.063 45.5902 28.994 45.7039 28.9469C45.8175 28.8998 45.9394 28.8756 46.0624 28.8756C46.1854 28.8756 46.3073 28.8998 46.4209 28.9469C46.5346 28.994 46.6379 29.063 46.7249 29.15C46.8119 29.237 46.8809 29.3403 46.928 29.454C46.9751 29.5676 46.9993 29.6895 46.9993 29.8125C46.9993 29.9355 46.9751 30.0574 46.928 30.171C46.8809 30.2847 46.8119 30.388 46.7249 30.475L42.6624 34.5375C42.5754 34.6246 42.4722 34.6937 42.3585 34.7408C42.2448 34.7879 42.123 34.8122 41.9999 34.8122C41.8768 34.8122 41.755 34.7879 41.6413 34.7408C41.5276 34.6937 41.4244 34.6246 41.3374 34.5375L37.2749 30.475C37.1879 30.388 37.1189 30.2847 37.0718 30.171C37.0247 30.0574 37.0005 29.9355 37.0005 29.8125C37.0005 29.6895 37.0247 29.5676 37.0718 29.454C37.1189 29.3403 37.1879 29.237 37.2749 29.15C37.3619 29.063 37.4652 28.994 37.5789 28.9469C37.6925 28.8998 37.8144 28.8756 37.9374 28.8756C38.0604 28.8756 38.1823 28.8998 38.2959 28.9469C38.4096 28.994 38.5129 29.063 38.5999 29.15L41.0624 31.6112Z" fill="white" />

                      </g>

                    </svg>
        </a>
        <a class="send_mail_invoice sd_subscriptionordertext" href="#" data-customerEmail="' . $customer_email . '" data-orderId="' . $order_id . '" title="Mail">
             <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="22 12 40 40" fill="none">

                      <g>

                        <rect x="22" y="12" width="40" height="40" rx="10" fill="#141E2D" />

                        <rect x="22.5" y="12.5" width="39" height="39" rx="9.5" stroke="white" stroke-opacity="0.2" />

                        <g clip-path="url(#clip0)">

                          <path d="M47.8467 24.75H36.1531C34.6032 24.75 33.3467 26.0065 33.3467 27.5565V36.4435C33.3467 37.9935 34.6032 39.25 36.1531 39.25H47.8467C49.3966 39.25 50.6531 37.9935 50.6531 36.4435V27.5565C50.6531 26.0065 49.3966 24.75 47.8467 24.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />

                          <path d="M33.3467 28.2583L41.2188 31.8749C41.4638 31.9875 41.7303 32.0457 41.9999 32.0457C42.2696 32.0457 42.536 31.9875 42.781 31.8749L50.6531 28.2583" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />

                        </g>

                      </g>

                      <defs>

                        <clipPath id="clip0">

                          <rect width="24" height="14.5" fill="white" transform="translate(30 24.75)" />

                        </clipPath>

                      </defs>

                    </svg>
        </a>
        <a href="invoiceDetails.php?shop=' . $store . '&order_id=' . $order_id . '&customer_email=' . $customer_email . '" title="View/Edit" class="sd_subscriptionordertext">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="22 12 40 40" fill="none">

                      <g>

                        <rect x="22" y="12" width="40" height="40" rx="10" fill="#141E2D" />

                        <rect x="22.5" y="12.5" width="39" height="39" rx="9.5" stroke="white" stroke-opacity="0.2" />

                        <path d="M49.47 31.83C48.882 30.3088 47.861 28.9933 46.5334 28.046C45.2058 27.0988 43.6298 26.5613 42 26.5C40.3703 26.5613 38.7942 27.0988 37.4666 28.046C36.139 28.9933 35.1181 30.3088 34.53 31.83C34.4903 31.9399 34.4903 32.0601 34.53 32.17C35.1181 33.6912 36.139 35.0067 37.4666 35.954C38.7942 36.9012 40.3703 37.4387 42 37.5C43.6298 37.4387 45.2058 36.9012 46.5334 35.954C47.861 35.0067 48.882 33.6912 49.47 32.17C49.5097 32.0601 49.5097 31.9399 49.47 31.83ZM42 35.25C41.3572 35.25 40.7289 35.0594 40.1944 34.7023C39.66 34.3452 39.2434 33.8376 38.9974 33.2437C38.7514 32.6499 38.6871 31.9964 38.8125 31.366C38.9379 30.7355 39.2474 30.1564 39.7019 29.7019C40.1565 29.2474 40.7355 28.9378 41.366 28.8124C41.9964 28.687 42.6499 28.7514 43.2438 28.9974C43.8376 29.2434 44.3452 29.6599 44.7023 30.1944C45.0594 30.7289 45.25 31.3572 45.25 32C45.2487 32.8615 44.9059 33.6874 44.2967 34.2966C43.6875 34.9058 42.8616 35.2487 42 35.25Z" fill="white" />

                        <path d="M42 34.375C43.3117 34.375 44.375 33.3117 44.375 32C44.375 30.6883 43.3117 29.625 42 29.625C40.6883 29.625 39.625 30.6883 39.625 32C39.625 33.3117 40.6883 34.375 42 34.375Z" fill="white" />

                      </g>

                    </svg>
        </a>
    ';


    $data[] = [
      "customer"   => '<div class="sd_customerData"><span class="user-name">' . $initials . '</span><div class="sd_customerDetails"><strong>' . $customer_name . '</strong><br><span>' . $customer_email . '</span></div></div>',
      "order_no" => '<a class="Polaris-Navigation__Item" href="https://' . $store . '/admin/orders/' . $value['order_id'] . '" target="_blank" data-polaris-unstyled="true" data-order-id="' . $value['order_id'] . '">#' . $value['order_no'] . '</a>',
      "order_type" => '<span class="Polaris-Badge">' . $value['plan_type'] . '</span>',
      "order_date" => getShopTimezoneDate($value['created_at'], $shop_timezone),
      'action'     => $actions
    ];
  }
}


$response = [
  "draw"            => intval($draw),
  "recordsTotal"    => intval($totalRecords),
  "recordsFiltered" => intval($totalFiltered),
  "data"            => $data
];

echo json_encode($response);
