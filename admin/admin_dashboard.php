<?php
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/navigation.php');

$stores = [];
$errorMessage = null;

try {
    $today = date('Y-m-d');

    $query = "
        SELECT 
            sd.shop_name,
            COALESCE(tc.total_customers, 0) AS total_customers,
            COALESCE(nc.new_customers_today, 0) AS new_customers_today,
            COALESCE(ts.today_revenue, 0) AS today_revenue
        FROM store_details sd
        LEFT JOIN (
            SELECT store_id, COUNT(DISTINCT id) AS total_customers
            FROM customers
            GROUP BY store_id
        ) tc ON tc.store_id = sd.store_id
        LEFT JOIN (
            SELECT store_id, COUNT(DISTINCT id) AS new_customers_today
            FROM customers
            WHERE DATE(created_at) = :today
            GROUP BY store_id
        ) nc ON nc.store_id = sd.store_id
        LEFT JOIN (
            SELECT store_id, SUM(total_sale) AS today_revenue
            FROM contract_sale
            WHERE DATE(created_at) = :today
            GROUP BY store_id
        ) ts ON ts.store_id = sd.store_id
        ORDER BY sd.shop_name
    ";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':today', $today);
    $stmt->execute();
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = 'Error loading dashboard data. Please try again later.';
    error_log("Dashboard Error: " . $e->getMessage());
}
?>

<div class="Polaris-Layout">
    <div class="Polaris-Layout__Section sd-dashboard-page">
        <div class="Polaris-Page">
            <div class="Polaris-Page-Header Polaris-Page-Header--isSingleRow Polaris-Page-Header--mobileView Polaris-Page-Header--noBreadcrumbs Polaris-Page-Header--mediumTitle">
                <div class="Polaris-Page-Header__Row">
                    <div class="Polaris-Page-Header__Title">
                        <h1 class="Polaris-Heading">Admin Dashboard</h1>
                    </div>
                </div>
            </div>

            <div class="Polaris-Page__Content">
                <div class="Polaris-DataTable sd_admin_dashboard_table">
                    <div class="Polaris-DataTable__ScrollContainer">
                        <div class="Polaris-DataTable sd_common_datatable">
                            <table class="Polaris-DataTable__Table" id="subscription_order_table">
                                <thead>
                                    <tr>
                                        <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--firstColumn Polaris-DataTable__Cell--header">Store Name</th>
                                        <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric">Total Customers</th>
                                        <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric">New Customers Joined Today</th>
                                        <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric">Today's Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($errorMessage): ?>
                                        <tr>
                                            <td colspan="4" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?= htmlspecialchars($errorMessage) ?></td>
                                        </tr>
                                    <?php elseif (count($stores) > 0): ?>
                                        <?php foreach ($stores as $store): ?>
                                            <tr class="Polaris-DataTable__TableRow">
                                                <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop"><?= htmlspecialchars($store['shop_name']) ?></td>
                                                <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?= number_format($store['total_customers']) ?></td>
                                                <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric"><?= number_format($store['new_customers_today']) ?></td>
                                                <td class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">$<?= number_format($store['today_revenue'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--numeric">No stores found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once(__DIR__ . '/footer.php'); ?>