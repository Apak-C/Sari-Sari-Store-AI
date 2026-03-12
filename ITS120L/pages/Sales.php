<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/Login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

date_default_timezone_set('Asia/Manila');
$currentDate = date('l, M, j, Y');

require_once '../includes/db_connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_to_cart') {
            $product_id = (int)$_POST['product_id'];
            $result = $conn->query("SELECT * FROM inventory WHERE id = $product_id AND user_id = $user_id");
            $product = $result->fetch_assoc();
            
            if ($product && $product['stock'] > 0) {
                $found = false;
                foreach ($_SESSION['cart'] as &$cartItem) {
                    if ($cartItem['id'] === $product_id) {
                        if ($cartItem['qty'] < $product['stock']) {
                            $cartItem['qty']++;
                        }
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $_SESSION['cart'][] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'cost' => $product['cost'],
                        'qty' => 1,
                        'image' => $product['image']
                    ];
                }
            }
        }
        elseif ($_POST['action'] === 'update_qty') {
            $index = (int)$_POST['cart_index'];
            $change = (int)$_POST['change'];
            if (isset($_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['qty'] += $change;
                if ($_SESSION['cart'][$index]['qty'] <= 0) {
                    unset($_SESSION['cart'][$index]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                }
            }
        }
        elseif ($_POST['action'] === 'remove_item') {
            $index = (int)$_POST['cart_index'];
            if (isset($_SESSION['cart'][$index])) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
        }
        elseif ($_POST['action'] === 'clear_cart') {
            $_SESSION['cart'] = [];
        }
        elseif ($_POST['action'] === 'complete_sale') {
            if (!empty($_SESSION['cart'])) {
                $today = date('Y-m-d');
                $now = date('H:i:s');
                
                foreach ($_SESSION['cart'] as $item) {
                    $total = $item['price'] * $item['qty'];
                    $totalCost = $item['cost'] * $item['qty'];
                    $profit = $total - $totalCost;
                    
                    $stmt = $conn->prepare("INSERT INTO sales (user_id, product_id, product_name, quantity, unit_price, total, cost, sale_date, sale_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisidddss", $user_id, $item['id'], $item['name'], $item['qty'], $item['price'], $total, $totalCost, $today, $now);
                    $stmt->execute();
                    $stmt->close();
                    
                    $conn->query("UPDATE inventory SET stock = GREATEST(0, stock - " . $item['qty'] . ") WHERE id = " . $item['id'] . " AND user_id = $user_id");
                    
                    $conn->query("INSERT INTO daily_sales (user_id, product_id, product_name, quantity_sold, revenue, cost, profit, sale_date) 
                        VALUES ($user_id, " . $item['id'] . ", '" . $conn->real_escape_string($item['name']) . "', " . $item['qty'] . ", " . $total . ", " . $totalCost . ", " . $profit . ", '$today')
                        ON DUPLICATE KEY UPDATE quantity_sold = quantity_sold + " . $item['qty'] . ", revenue = revenue + " . $total . ", cost = cost + " . $totalCost . ", profit = profit + " . $profit);
                }
                $_SESSION['cart'] = [];
            }
        }
    }
    header('Location: sales.php');
    exit();
}

$result = $conn->query("SELECT * FROM inventory WHERE stock > 0 AND user_id = $user_id ORDER BY id DESC");
$products = $result->fetch_all(MYSQLI_ASSOC);
$cart = $_SESSION['cart'];
$cartTotal = 0;
$cartCost = 0;
foreach ($cart as $item) {
    $cartTotal += $item['price'] * $item['qty'];
    $cartCost += $item['cost'] * $item['qty'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/Sales.css">
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
            <a href="report.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                Report
            </a>
            <a href="inventory.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                Inventory
            </a>
            <a href="sales.php" class="active">
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
            <h1 class="page-title">Sales Entry</h1>
            <span class="page-date"><?php echo $currentDate; ?></span>
        </div>

        <div class="sales-layout">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">Products</h2>
                    <span class="section-count"><?php echo count($products); ?> items</span>
                </div>
                <div class="search-box">
                    <input type="text" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()">
                </div>
                <div class="products-grid" id="productsGrid">
                    <?php foreach ($products as $product): ?>
                        <form method="POST" class="product-card">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="product-btn">
                                <img src="<?php echo htmlspecialchars($product['image'] ?: 'https://via.placeholder.com/80x80?text=No+Image'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                <div class="product-price">&#8369;<?php echo number_format($product['price'], 2); ?></div>
                                <div class="product-stock <?php echo $product['stock'] <= 10 ? 'low' : ''; ?>">Stock: <?php echo $product['stock']; ?></div>
                            </button>
                        </form>
                    <?php endforeach; ?>
                    <?php if (empty($products)): ?>
                        <div class="no-products">No products available. <a href="inventory.php">Add products</a></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">Cart</h2>
                    <span class="section-count"><?php echo count($cart); ?> items</span>
                </div>
                
                <div class="cart-items">
                    <?php if (empty($cart)): ?>
                        <div class="empty-cart">
                            <div class="empty-icon">&#128722;</div>
                            <p>Cart is empty</p>
                            <p>Click a product to add it</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($cart as $index => $item): ?>
                            <div class="cart-item">
                                <img src="<?php echo htmlspecialchars($item['image'] ?: 'https://via.placeholder.com/50x50'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="cart-item-info">
                                    <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="cart-item-price">&#8369;<?php echo number_format($item['price'], 2); ?> each</div>
                                </div>
                                <div class="cart-item-qty">
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="update_qty">
                                        <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                        <input type="hidden" name="change" value="-1">
                                        <button type="submit" class="qty-btn minus">-</button>
                                    </form>
                                    <span class="qty-display"><?php echo $item['qty']; ?></span>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="update_qty">
                                        <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                        <input type="hidden" name="change" value="1">
                                        <button type="submit" class="qty-btn plus">+</button>
                                    </form>
                                </div>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="remove_item">
                                    <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                    <button type="submit" class="remove-btn">&times;</button>
                                </form>
                                <span class="subtotal-cell">&#8369;<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="cart-footer">
                    <div class="cart-total">
                        <span class="cart-total-label">Total:</span>
                        <span class="cart-total-amount">&#8369;<?php echo number_format($cartTotal, 2); ?></span>
                    </div>
                    <div class="cart-actions">
                        <form method="POST">
                            <input type="hidden" name="action" value="clear_cart">
                            <button type="submit" class="btn-clear">Clear Cart</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="action" value="complete_sale">
                            <button type="submit" class="btn-checkout" <?php echo empty($cart) ? 'disabled' : ''; ?>>Complete Sale</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function filterProducts() {
            const search = document.getElementById('productSearch').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                const name = card.querySelector('.product-name').textContent.toLowerCase();
                card.style.display = name.includes(search) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
