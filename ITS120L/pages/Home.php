<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/Login.php');
    exit();
}

$userFullName = $_SESSION['user_full_name'] ?? 'Christian Warren';
$storeName = $_SESSION['store_name'] ?? 'Christian Store';

date_default_timezone_set('Asia/Manila');
$philippinesDate = date('F j, Y'); 
$philippinesTime = date('g:i A'); 
$philippinesDay = date('l'); 

$storeStatus = "Open";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/Home.css">
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="../images/Login.png" alt="Logo" style="width:45px;height:45px;object-fit:contain;">
        </div>
        <nav class="nav">
            <a href="Home.php" class="active">
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
            <a href="sales.php">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                Sales
            </a>
        </nav>
        
        <div class="logout-wrapper">
            <button class="logout-btn" onclick="window.location.href='../auth/Logout.php'">Logout</button>
        </div>
    </aside>

    <div class="main-content">
        <div class="welcome-section">
            <h1 class="welcome-greeting">Welcome back, <?php echo htmlspecialchars($userFullName); ?>!</h1>
            <p class="store-name"><?php echo htmlspecialchars($storeName); ?></p>
        </div>

        <div class="status-banner">
            <div class="status-left">
                <span class="status-indicator"></span>
                <span>Store is <?php echo $storeStatus; ?></span>
            </div>
            <div class="status-right">
                <span class="datetime-display">
                    <strong><?php echo $philippinesDay; ?></strong>, <?php echo $philippinesDate; ?> | <?php echo $philippinesTime; ?> PHT
                </span>
            </div>
        </div>

        <div class="quick-actions">
            <a href="sales.php" class="action-card">
                <span class="action-icon">&#128722;</span>
                <h2 class="action-title">Start a New Sale</h2>
                <p class="action-description">Process a new customer transaction and complete the sale</p>
            </a>

            <a href="inventory.php" class="action-card">
                <span class="action-icon">&#128230;</span>
                <h2 class="action-title">Receive Delivery</h2>
                <p class="action-description">Add new stock items and update your inventory</p>
            </a>

            <a href="report.php" class="action-card">
                <span class="action-icon">&#128202;</span>
                <h2 class="action-title">View Reports</h2>
                <p class="action-description">Check analytics, AI predictions, and sales reports</p>
            </a>
        </div>
    </div>
</body>
</html>
