<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'its120_inventory';

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("DROP TABLE IF EXISTS sales");
$conn->query("DROP TABLE IF EXISTS daily_sales");
$conn->query("DROP TABLE IF EXISTS inventory");

$sql = "CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category VARCHAR(100) DEFAULT 'Other',
    image VARCHAR(500) DEFAULT '',
    cost DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id)
)";

$conn->query($sql);

$sql = "CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    cost DECIMAL(10,2) DEFAULT 0,
    sale_date DATE NOT NULL,
    sale_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_date (sale_date)
)";

$conn->query($sql);

$sql = "CREATE TABLE daily_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity_sold INT NOT NULL DEFAULT 0,
    revenue DECIMAL(10,2) NOT NULL DEFAULT 0,
    cost DECIMAL(10,2) DEFAULT 0,
    profit DECIMAL(10,2) DEFAULT 0,
    sale_date DATE NOT NULL,
    UNIQUE KEY unique_user_product_date (user_id, product_id, sale_date),
    INDEX idx_user (user_id)
)";

$conn->query($sql);

echo "Tables created with user support!<br>";

echo "<br><strong>Database setup complete!</strong><br><a href='Home.php'>Go to Home</a>";

$conn->close();
