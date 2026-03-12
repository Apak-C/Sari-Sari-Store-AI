<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Grab the exact data from the HTML form inputs
    $fullName = $_POST['fullName'];
    $storeName = $_POST['storeName'];
    $email = $_POST['email']; 
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // UPDATED: Changed 'email' to 'username' to match your database columns perfectly
    $sql = "INSERT INTO users (full_name, store_name, username, password_hash) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Attach the 4 string variables
    $stmt->bind_param("ssss", $fullName, $storeName, $email, $hashed_password);

    // Execute and give feedback
    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful! You can now log in.');
                window.location.href = 'Login.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>