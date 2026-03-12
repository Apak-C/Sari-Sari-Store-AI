<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/Login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

date_default_timezone_set('Asia/Manila');
$currentDate = date('l, M j, Y');

require_once '../includes/db_connect.php';

$today = date('Y-m-d');
$weekAgo = date('Y-m-d', strtotime('-7 days'));

$inventoryResult = $conn->query("SELECT * FROM inventory WHERE user_id = $user_id ORDER BY id");
$inventory = $inventoryResult->fetch_all(MYSQLI_ASSOC);

$totalValue = 0;
$lowStockCount = 0;
foreach ($inventory as $item) {
    $totalValue += $item['price'] * $item['stock'];
    if ($item['stock'] <= 10) {
        $lowStockCount++;
    }
}

$todaySalesResult = $conn->query("
    SELECT SUM(quantity_sold) as total_items, SUM(revenue) as total_revenue, SUM(cost) as total_cost, SUM(profit) as total_profit
    FROM daily_sales 
    WHERE user_id = $user_id AND sale_date = '$today'
");
$todaySales = $todaySalesResult->fetch_assoc();
$todayItems = $todaySales['total_items'] ?? 0;
$todayRevenue = $todaySales['total_revenue'] ?? 0;
$todayCost = $todaySales['total_cost'] ?? 0;
$todayProfit = $todaySales['total_profit'] ?? 0;

$weekSalesResult = $conn->query("
    SELECT SUM(quantity_sold) as total_items, SUM(revenue) as total_revenue, SUM(cost) as total_cost, SUM(profit) as total_profit
    FROM daily_sales 
    WHERE user_id = $user_id AND sale_date >= '$weekAgo'
");
$weekSales = $weekSalesResult->fetch_assoc();
$weekItems = $weekSales['total_items'] ?? 0;
$weekRevenue = $weekSales['total_revenue'] ?? 0;
$weekCost = $weekSales['total_cost'] ?? 0;
$weekProfit = $weekSales['total_profit'] ?? 0;

$salesResult = $conn->query("
    SELECT product_name, SUM(quantity_sold) as total_qty, SUM(revenue) as total_revenue 
    FROM daily_sales 
    WHERE user_id = $user_id AND sale_date >= '$weekAgo' 
    GROUP BY product_id, product_name 
    ORDER BY total_qty DESC
");
$salesData = $salesResult->fetch_all(MYSQLI_ASSOC);

$hotProducts = array_slice($salesData, 0, 3);
$coldProducts = array_slice(array_reverse($salesData), 0, 3);

$predictions = [];
foreach ($inventory as $item) {
    $dailySalesResult = $conn->query("
        SELECT sale_date, quantity_sold 
        FROM daily_sales 
        WHERE user_id = $user_id AND product_id = " . $item['id'] . " 
        AND sale_date >= '$weekAgo'
        ORDER BY sale_date ASC
    ");
    $dailySales = $dailySalesResult->fetch_all(MYSQLI_ASSOC);
    
    $totalSold7Days = 0;
    $daysWithSales = count($dailySales);
    
    if ($daysWithSales > 0) {
        foreach ($dailySales as $day) {
            $totalSold7Days += $day['quantity_sold'];
        }
        $avgDailySales = $totalSold7Days / 7;
    } else {
        $avgDailySales = 0;
    }
    
    $currentStock = $item['stock'];
    
    if ($avgDailySales > 0) {
        $daysUntilStockout = floor($currentStock / $avgDailySales);
        $stockoutDate = date('Y-m-d', strtotime("+$daysUntilStockout days"));
        
        $status = 'ok';
        if ($daysUntilStockout <= 3) {
            $status = 'critical';
        } elseif ($daysUntilStockout <= 7) {
            $status = 'warning';
        }
        
        $predictions[] = [
            'name' => $item['name'],
            'image' => $item['image'],
            'stock' => $currentStock,
            'avg_daily_sales' => $avgDailySales,
            'days_until_stockout' => $daysUntilStockout,
            'stockout_date' => $stockoutDate,
            'status' => $status
        ];
    }
}

usort($predictions, function($a, $b) {
    return $a['days_until_stockout'] - $b['days_until_stockout'];
});

$topSeller = !empty($hotProducts) ? $hotProducts[0]['product_name'] : 'N/A';
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/Report.css">
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="../images/Login.png" alt="Logo" style="width:45px;height:45px;object-fit:contain;">
        </div>
        <nav class="nav">
            <a href="Home.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                Home
            </a>
            <a href="report.php" class="active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                Report
            </a>
            <a href="inventory.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                Inventory
            </a>
            <a href="sales.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                Sales
            </a>
        </nav>
        
        <div class="logout-wrapper">
            <button class="logout-btn" onclick="window.location.href='../auth/Logout.php'">Logout</button>
        </div>
    </aside>
    
    <main class="main">
        <div class="page-header">
            <h1 class="page-title">Report</h1>
            <span class="page-date"><?php echo $currentDate; ?></span>
        </div>
        
        <div class="cards">
            <div class="card inventory">
                <div class="label">Total Inventory Value</div>
                <div class="value">&#8369;<?php echo number_format($totalValue, 2); ?></div>
            </div>
            <div class="card critical">
                <div class="label">Low Stock Alert</div>
                <div class="value"><?php echo $lowStockCount; ?> ITEM<?php echo $lowStockCount != 1 ? 'S' : ''; ?></div>
            </div>
            <div class="card topseller">
                <div class="label">Top Seller (Weekly)</div>
                <div class="value"><?php echo htmlspecialchars($topSeller); ?></div>
            </div>
        </div>

        <div class="section-title" style="margin-top: 32px;">&#128197; Today's Sales</div>
        <div class="cards">
            <div class="card">
                <div class="label">Items Sold</div>
                <div class="value"><?php echo $todayItems; ?></div>
            </div>
            <div class="card">
                <div class="label">Total Revenue</div>
                <div class="value" style="color: #10b981;">&#8369;<?php echo number_format($todayRevenue, 2); ?></div>
            </div>
            <div class="card">
                <div class="label">Profit</div>
                <div class="value <?php echo $todayProfit >= 0 ? 'profit-positive' : 'profit-negative'; ?>">
                    <?php echo $todayProfit >= 0 ? '' : '-'; ?>&#8369;<?php echo number_format(abs($todayProfit), 2); ?>
                </div>
            </div>
        </div>

        <div class="section-title" style="margin-top: 32px;">&#128200; Weekly Summary (Last 7 Days)</div>
        <div class="cards">
            <div class="card">
                <div class="label">Total Items Sold</div>
                <div class="value"><?php echo $weekItems; ?></div>
            </div>
            <div class="card">
                <div class="label">Total Revenue</div>
                <div class="value" style="color: #10b981;">&#8369;<?php echo number_format($weekRevenue, 2); ?></div>
            </div>
            <div class="card">
                <div class="label">Total Expenses</div>
                <div class="value" style="color: #dc2626;">&#8369;<?php echo number_format($weekCost, 2); ?></div>
            </div>
            <div class="card <?php echo $weekProfit >= 0 ? 'profit-card' : 'loss-card'; ?>">
                <div class="label"><?php echo $weekProfit >= 0 ? 'Net Profit' : 'Net Loss'; ?></div>
                <div class="value <?php echo $weekProfit >= 0 ? 'profit-positive' : 'profit-negative'; ?>">
                    <?php echo $weekProfit >= 0 ? '+' : '-'; ?>&#8369;<?php echo number_format(abs($weekProfit), 2); ?>
                </div>
            </div>
        </div>
        
        <div class="analytics-section">
            <div class="analytics-card">
                <h2 class="section-title hot">&#128293; Hot Products</h2>
                <div class="product-list">
                    <?php if (!empty($hotProducts)): ?>
                        <?php foreach ($hotProducts as $index => $product): ?>
                            <div class="product-item hot-item">
                                <span class="rank">#<?php echo $index + 1; ?></span>
                                <span class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></span>
                                <span class="product-stats"><?php echo $product['total_qty']; ?> sold | &#8369;<?php echo number_format($product['total_revenue'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">No sales data yet</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="analytics-card">
                <h2 class="section-title cold">&#128164; Cold Products</h2>
                <div class="product-list">
                    <?php if (!empty($coldProducts)): ?>
                        <?php foreach ($coldProducts as $index => $product): ?>
                            <div class="product-item cold-item">
                                <span class="rank">#<?php echo count($coldProducts) - $index; ?></span>
                                <span class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></span>
                                <span class="product-stats"><?php echo $product['total_qty']; ?> sold | &#8369;<?php echo number_format($product['total_revenue'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">No sales data yet</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <h2 class="section-title">&#129302; AI Stock-Out Prediction</h2>
        <p class="section-subtitle">Based on last 7 days sales data</p>
        
        <div class="prediction-card">
            <?php if (!empty($predictions)): ?>
                <?php foreach ($predictions as $pred): ?>
                    <div class="prediction-item <?php echo $pred['status']; ?>">
                        <img class="thumb" src="<?php echo htmlspecialchars($pred['image'] ?: 'https://via.placeholder.com/48x48?text=No+Image'); ?>" alt="<?php echo htmlspecialchars($pred['name']); ?>">
                        <div class="prediction-info">
                            <span class="name"><?php echo htmlspecialchars($pred['name']); ?></span>
                            <span class="stock-info">Stock: <?php echo $pred['stock']; ?> | Avg Daily: <?php echo number_format($pred['avg_daily_sales'], 1); ?></span>
                        </div>
                        <div class="prediction-result">
                            <?php if ($pred['days_until_stockout'] > 0): ?>
                                <span class="days"><?php echo $pred['days_until_stockout']; ?> days</span>
                                <span class="date">until <?php echo date('M j', strtotime($pred['stockout_date'])); ?></span>
                            <?php else: ?>
                                <span class="days out">OUT OF STOCK</span>
                            <?php endif; ?>
                        </div>
                        <span class="badge <?php echo $pred['status']; ?>">
                            <?php if ($pred['status'] === 'critical'): ?>
                                CRITICAL
                            <?php elseif ($pred['status'] === 'warning'): ?>
                                WARNING
                            <?php else: ?>
                                OK
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data" style="padding: 40px;">No prediction data available. Start making sales to see predictions.</div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { 
                timeZone: 'Asia/Manila', 
                weekday: 'long', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric'
            };
            const parts = new Intl.DateTimeFormat('en-US', options).formatToParts(now);
            const weekday = parts.find(p => p.type === 'weekday').value;
            const month = parts.find(p => p.type === 'month').value;
            const day = parts.find(p => p.type === 'day').value;
            const year = parts.find(p => p.type === 'year').value;
            
            document.querySelector('.page-date').textContent = `${weekday}, ${month} ${day}, ${year}`;
        }
        
        updateClock();
    </script>
</body>
</html>
