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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_product') {
            $name = trim($_POST['product_name']);
            $price = (float)$_POST['product_price'];
            $stock = (int)$_POST['product_stock'];
            $category = trim($_POST['product_category']);
            $image = $_POST['product_image'] ?? '';
            $cost = (float)($_POST['product_cost'] ?? 0);
            
            $stmt = $conn->prepare("INSERT INTO inventory (user_id, name, price, stock, category, image, cost) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isdissd", $user_id, $name, $price, $stock, $category, $image, $cost);
            $stmt->execute();
            $message = "Product added successfully!";
            $stmt->close();
        }
        
        elseif ($_POST['action'] === 'update_stock') {
            $id = (int)$_POST['product_id'];
            $change = (int)$_POST['stock_change'];
            
            $conn->query("UPDATE inventory SET stock = GREATEST(0, stock + $change) WHERE id = $id AND user_id = $user_id");
        }
        
        elseif ($_POST['action'] === 'delete_product') {
            $id = (int)$_POST['product_id'];
            $conn->query("DELETE FROM inventory WHERE id = $id AND user_id = $user_id");
        }
        
        elseif ($_POST['action'] === 'restock') {
            $id = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            $conn->query("UPDATE inventory SET stock = stock + $quantity WHERE id = $id AND user_id = $user_id");
            $message = "Stock updated successfully!";
        }
    }
    header('Location: inventory.php');
    exit();
}

$result = $conn->query("SELECT * FROM inventory WHERE user_id = $user_id ORDER BY id DESC");
$products = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/Inventory.css">
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
            <a href="inventory.php" class="active">
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
            <h1 class="page-title">Inventory</h1>
            <span class="page-date"><?php echo $currentDate; ?></span>
        </div>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="page-actions">
            <button class="btn btn-primary" id="openAddModalBtn">+ Add New Product</button>
        </div>

        <div class="search-box">
            <span class="search-icon">&#128269;</span>
            <input type="text" id="searchInput" placeholder="Search products..." onkeyup="searchProducts()">
        </div>

        <div class="content-card">
            <div class="table-header">
                <span>ID</span>
                <span>Product</span>
                <span>Category</span>
                <span>Price</span>
                <span>Stock</span>
                <span>Actions</span>
            </div>
            
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="table-row" data-name="<?php echo strtolower(htmlspecialchars($product['name'])); ?>">
                        <span><?php echo $product['id']; ?></span>
                        <div class="product-cell">
                            <?php if ($product['image']): ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="" class="product-thumb">
                            <?php else: ?>
                                <div class="product-thumb" style="display:flex;align-items:center;justify-content:center;font-size:20px;">&#128230;</div>
                            <?php endif; ?>
                            <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
                        </div>
                        <span><span class="category-badge"><?php echo htmlspecialchars($product['category'] ?? 'Other'); ?></span></span>
                        <span class="price-cell">&#8369;<?php echo number_format($product['price'], 2); ?></span>
                        <div class="stock-cell">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update_stock">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="stock_change" value="-1">
                                <button type="submit" class="stock-btn minus">-</button>
                            </form>
                            <span class="stock-value <?php echo $product['stock'] <= 10 ? 'low' : ''; ?>"><?php echo $product['stock']; ?></span>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update_stock">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="stock_change" value="1">
                                <button type="submit" class="stock-btn plus">+</button>
                            </form>
                        </div>
                        <div class="action-btns">
                            <button class="stock-btn restock" onclick="openRestockModal(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')" title="Restock">&#8635;</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="action-btn delete" title="Delete" onclick="return confirm('Delete this product?');">&#10005;</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No products in inventory</p>
                    <p>Click "+ Add New Product" to get started</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="closeAddModal">&times;</span>
            <h2 class="modal-title">Add New Product</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_product">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" placeholder="Enter product name" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="product_category">
                        <option value="Food">Food</option>
                        <option value="Beverage">Beverage</option>
                        <option value="Snacks">Snacks</option>
                        <option value="Household">Household</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price (PHP)</label>
                    <input type="number" name="product_price" step="0.01" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Cost (PHP) - for profit tracking</label>
                    <input type="number" name="product_cost" step="0.01" placeholder="0.00" value="0">
                </div>
                <div class="form-group">
                    <label>Initial Stock</label>
                    <input type="number" name="product_stock" placeholder="0" required>
                </div>
                <div class="form-group">
                    <label>Image URL (optional)</label>
                    <input type="text" name="product_image" placeholder="https://...">
                </div>
                <button type="submit" class="btn-submit">Add Product</button>
            </form>
        </div>
    </div>

    <div id="restockModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="closeRestockModal">&times;</span>
            <h2 class="modal-title">Restock Product</h2>
            <p class="modal-product-name" id="restockProductName"></p>
            <form method="POST">
                <input type="hidden" name="action" value="restock">
                <input type="hidden" name="product_id" id="restockProductId">
                <div class="form-group">
                    <label>Quantity to Add</label>
                    <input type="number" name="quantity" min="1" placeholder="Enter quantity" required>
                </div>
                <button type="submit" class="btn-submit">Add Stock</button>
            </form>
        </div>
    </div>

    <script>
        const addModal = document.getElementById('addModal');
        const restockModal = document.getElementById('restockModal');
        
        document.getElementById('openAddModalBtn').onclick = () => addModal.style.display = 'flex';
        document.getElementById('closeAddModal').onclick = () => addModal.style.display = 'none';
        document.getElementById('closeRestockModal').onclick = () => restockModal.style.display = 'none';
        
        window.onclick = (e) => {
            if (e.target === addModal) addModal.style.display = 'none';
            if (e.target === restockModal) restockModal.style.display = 'none';
        };
        
        function openRestockModal(id, name) {
            document.getElementById('restockProductId').value = id;
            document.getElementById('restockProductName').textContent = 'Restocking: ' + name;
            restockModal.style.display = 'flex';
        }
        
        function searchProducts() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.table-row');
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                row.style.display = name.includes(search) ? 'grid' : 'none';
            });
        }
    </script>
</body>
</html>
