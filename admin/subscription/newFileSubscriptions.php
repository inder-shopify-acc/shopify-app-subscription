<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json');

function sendJson($response)
{
  echo json_encode($response);
  exit;
}

// $dirPath = dirname(__DIR__);
$dirPath = dirname(__DIR__, 2);
include($dirPath . "/application/library/config.php");

// === DataTables request params ===
$draw        = $_POST['draw'] ?? 1;
$row         = (int)($_POST['start'] ?? 0);
$rowperpage  = (int)($_POST['length'] ?? 10);
$columnIndex = $_POST['order'][0]['column'] ?? 0;
$columnName  = $_POST['columns'][$columnIndex]['data'] ?? 'id';
$sortOrder   = ($_POST['order'][0]['dir'] ?? 'asc') == 'desc' ? 'DESC' : 'ASC';
$searchValue = $_POST['search']['value'] ?? '';

$store_id    = $_POST['store_id'] ?? 1;
$store       = $_POST['store'] ?? 'pheonix-store-local.myshopify.com';
$logged_in_customer_id = '';

// --- Column mapping (DataTables -> SQL columns) ---
$columnMap = [
  "customer"          => "c.name",
  "contract_id"       => "o.contract_id",
  "order_type"        => "o.plan_type",
  "order_no"          => "o.order_no",
  "plan_type"         => "o.plan_type",
  "created_at"        => "o.created_at",
  "next_billing_date" => "o.next_billing_date",
  "status"            => "o.contract_status",
  "view"              => "o.id" // safe fallback
];


try {

  $sortColumn = $columnMap[$columnName] ?? "o.id";

  // --- Customer condition ---
  $whereCustomerCondition = "";
  if (!empty($logged_in_customer_id)) {
    $whereCustomerCondition = " AND o.shopify_customer_id = :customer_id";
  }

  // --- Search condition ---
  $searchCondition = "";
  if (!empty($searchValue)) {
    $searchCondition = " AND (
      c.name LIKE :search 
      OR c.email LIKE :search 
      OR o.order_no LIKE :search
      OR o.contract_id LIKE :search
  )";
  }

  // === Count total records ===
  $totalQuery = "SELECT COUNT(*) as allcount 
               FROM subscriptionOrderContract as o 
               WHERE o.store_id = :store_id";
  $totalStmt = $db->prepare($totalQuery);
  $totalStmt->bindValue(':store_id', $store_id, PDO::PARAM_INT);
  $totalStmt->execute();
  $totalRecords = $totalStmt->fetch(PDO::FETCH_ASSOC)['allcount'] ?? 0;

  // === Count filtered records ===
  $filteredQuery = "
    SELECT COUNT(*) as allcount
    FROM subscriptionOrderContract as o
    INNER JOIN customers as c ON c.shopify_customer_id = o.shopify_customer_id
    WHERE o.store_id = :store_id $whereCustomerCondition $searchCondition
  ";

  $filteredStmt = $db->prepare($filteredQuery);
  $filteredStmt->bindValue(':store_id', $store_id, PDO::PARAM_INT);

  if (!empty($logged_in_customer_id)) {
    $filteredStmt->bindValue(':customer_id', $logged_in_customer_id, PDO::PARAM_INT);
  }
  if (!empty($searchValue)) {
    $filteredStmt->bindValue(':search', "%$searchValue%", PDO::PARAM_STR);
  }
  $filteredStmt->execute();
  $totalFiltered = $filteredStmt->fetch(PDO::FETCH_ASSOC)['allcount'];

  // === Main SQL ===
  $sql = "SELECT 
        o.id, o.store_id, o.order_id, o.plan_type, o.contract_id,
        o.billing_policy_value, o.delivery_policy_value,
        o.delivery_billing_type, o.contract_status,
        o.order_token, o.order_no, o.created_at, o.updated_at, o.new_contract,
        o.next_billing_date,
        c.name, c.email, c.shopify_customer_id,
        d.shop_timezone 
    FROM subscriptionOrderContract AS o
    INNER JOIN customers AS c ON o.shopify_customer_id = c.shopify_customer_id
    INNER JOIN store_details AS d ON d.store_id = o.store_id
    WHERE o.store_id = :store_id
    $whereCustomerCondition
    $searchCondition
    ORDER BY o.order_id DESC
    LIMIT :row, :rowperpage";

  $stmt = $db->prepare($sql);

  // Bind common
  $stmt->bindValue(':store_id', $store_id, PDO::PARAM_INT);
  $stmt->bindValue(':row', (int)$row, PDO::PARAM_INT);
  $stmt->bindValue(':rowperpage', (int)$rowperpage, PDO::PARAM_INT);

  if (!empty($logged_in_customer_id)) {
    $stmt->bindValue(':customer_id', $logged_in_customer_id, PDO::PARAM_INT);
  }
  if (!empty($searchValue)) {
    $stmt->bindValue(':search', "%$searchValue%", PDO::PARAM_STR);
  }

  // Execute
  $stmt->execute();
  $getContractData = $stmt->fetchAll(PDO::FETCH_ASSOC);


  // Shop timezone (if data exists)
  if (!empty($getContractData)) {
    $shop_timezone = $getContractData[0]['shop_timezone'];
  }

  // === Format data for DataTables ===
  $data = [];

  // $get_contract_orders_qry = $db->query("SELECT shop_timezone, shop FROM store_details WHERE store_id = '$store_id' LIMIT 1");
  // $get_store_details = $get_contract_orders_qry->fetch(PDO::FETCH_ASSOC);


  function getShopTimezoneDate($date, $shop_timezone)
  {

    $dt = new DateTime($date);
    $tz = new DateTimeZone($shop_timezone); // or whatever zone you're after
    $dt->setTimezone($tz);
    $dateTime = $dt->format('Y-m-d H:i:s');
    $shopify_date = date("d M Y", strtotime($dateTime));

    return $shopify_date;
  }


  foreach ($getContractData as $value) {

    // echo count($value);
    // echo "<pre>";
    // print_r($value);

    // Customer link
    // $orderLink = 'https://' . $store . '/admin/orders/' . $value['order_id'];

    if ($logged_in_customer_id == '') {
      $orderLink = 'https://' . $store . '/admin/orders/' . $value['order_id'];
    } else {
      $orderLink = 'https://' . $store . '/account/orders/' . $value['order_token'];
    }

    // initials name
    $initials = '';

    // if (!empty($value['name'])) {

    //   //  $initials = implode('', array_map(fn($n) => strtoupper($n[0]), explode(' ', $value['name'])));
    //   $nameParts = explode(' ', $value['name']);
    //   $initials = strtoupper(
    //     (isset($nameParts[0]) ? substr($nameParts[0], 0, 1) : '') .
    //       (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    //   );
    // }

    if (!empty($value['name'])) {
      $name = $value['name'];

      // Split into parts by space
      $nameParts = explode(' ', $name);
      // Get first letter of each part (multibyte safe)
      foreach ($nameParts as $part) {
        $initials .= mb_substr($part, 0, 1, 'UTF-8');
      }
      // Make uppercase
      $initials = mb_strtoupper($initials, 'UTF-8');
    }

    // billing type
    $planType = ($value['delivery_policy_value'] == $value['billing_policy_value'])
      ? 'Pay Per Delivery'
      : 'Prepaid';

    // contract status badge
    if ($value['contract_status'] == 'A') {
      $contract_status = "Active";
      $contract_statusClass = "statusSuccess";
      $addCircle = '<span class="Polaris-Badge__Pip"><span class="Polaris-VisuallyHidden"></span></span>';
    } else if ($value['contract_status'] == 'P') {
      $contract_status = "Paused";
      $contract_statusClass = "statusAttention";
      $addCircle = '';
    } else {
      $contract_status = "Cancelled";
      $contract_statusClass = "statusInfo";
      $addCircle = '';
    }

    // New contract badge
    $unread_contract = '';
    $add_new_class = '';
    $updated_at_date = $value['updated_at'];
    $date_time_array = explode(' ', $updated_at_date);

    if ($value['new_contract'] == '1') {
      $unread_contract = '<div p-color-scheme="light" class="sd_contract_unread"><span class="Polaris-Badge Polaris-Badge--statusAttention"><span class="Polaris-VisuallyHidden">New</span><span>New</span></span><div id="PolarisPortalsContainer"></div></div>';
      $add_new_class = 'sd_new_contract';
    }

    $view = '<a class="Polaris-Navigation__Item sd_view_subscription navigate_contract_detail" data-query-string="' . $value['contract_id'] . '" tabindex="0" href="javascript:void(0)" data-polaris-unstyled="true"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="20px" padding="10px" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M19.928 9.629c-2.137-5.343-6.247-7.779-10.355-7.565-4.06.21-7.892 3.002-9.516 7.603l-.118.333.118.333c1.624 4.601 5.455 7.393 9.516 7.603 4.108.213 8.218-2.222 10.355-7.565l.149-.371-.149-.371zm-9.928 5.371a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"></path><circle cx="10" cy="10" r="3"></circle></svg></a>';


    // Order Type
    $plan_type =
      '<div class="Polaris-ShadowBevel" 
        style="--pc-shadow-bevel-z-index: 32; 
               --pc-shadow-bevel-content-xs: &quot;&quot;; 
               --pc-shadow-bevel-box-shadow-xs: var(--p-shadow-100); 
               --pc-shadow-bevel-border-radius-xs: var(--p-border-radius-300);">
        <div class="Polaris-Box" 
            style="--pc-box-background:var(--p-color-bg-surface);
                   --pc-box-min-height:100%;
                   --pc-box-overflow-x:clip;
                   --pc-box-overflow-y:clip;
                   --pc-box-padding-block-start-xs:var(--p-space-400);
                   --pc-box-padding-block-end-xs:var(--p-space-400);
                   --pc-box-padding-inline-start-xs:var(--p-space-400);
                   --pc-box-padding-inline-end-xs:var(--p-space-400)">
            <span class="Polaris-Badge sd_order_type_badge">
                <span class="Polaris-Text--root Polaris-Text--bodySm">' . $value['plan_type'] . '</span>
            </span>
        </div>
    </div>';

    $contractId =
      $unread_contract .
      '<a class="Polaris-Navigation__Item navigate_contract_detail" 
        onmouseover="show_title(this)" 
        onmouseout="hide_title(this)" 
        data-query-string="' . $value['contract_id'] . '" 
        data-confirmbox="no" 
        tabindex="0" 
        href="javascript:void(0)" 
        data-polaris-unstyled="true">
        #' . $value['contract_id'] . '
    </a>
    <div class="Polaris-PositionedOverlay display-hide-label">
        <div class="Polaris-Tooltip-TooltipOverlay" data-polaris-layer="true">
            <div id="PolarisTooltipContent2" role="tooltip" class="Polaris-Tooltip-TooltipOverlay__Content">View</div>
        </div>
    </div>';

    // Build JSON row
    $data[] = [
      "customer"      => '<div class="sd_customerData"><span class="user-name">' . $initials . '</span><div class="sd_customerDetails"><strong>' . $value['name'] . '</strong><br><span>' . $value['email'] . '</div></span></div>',

      "contract_id" => $contractId,
      // "subscription_id" => $subscription_id,
      "order_type"     => $plan_type,
      "order_no"      => '<a class="Polaris-Navigation__Item"  href="' . $orderLink . '" target="_blank" data-polaris-unstyled="true">#' . $value['order_no'] . '</a>',
      "plan_type"    => $planType,
      "created_at"    => getShopTimezoneDate($value['created_at'], $shop_timezone),
      "next_billing_date" => getShopTimezoneDate(($value['next_billing_date'] . ' ' . $date_time_array[1]), $shop_timezone),
      "status"        => '<div><span class="Polaris-Badge Polaris-Badge--' . $contract_statusClass . ' Polaris-Badge--progressComplete">' . $addCircle . '' . $contract_status . '</span> <div id="PolarisPortalsContainer"></div>',
      "view"        => $view
    ];
  }


  // === Final JSON response ===

  // printf($draw);
  // echo '</br>';
  // printf($totalRecords);
  // echo '</br>';
  // printf($totalFiltered);
  // echo '</br>';
  // print_r($data);

  $response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
  ];

  echo json_encode($response);
} catch (Exception $e) {
  // Return error in JSON
  sendJson([
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => $e->getMessage()  // this will trigger JS alert
  ]);
}
