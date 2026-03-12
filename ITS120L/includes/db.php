<?php
$host = 'localhost';
$dbname = 'christian_store'; // This matches the database we just made!
$username = 'root';          // XAMPP's default username
$password = '';              // XAMPP's default password (blank)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>