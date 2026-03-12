<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // SECURITY KICK-OUT: If they try to skip the verification code step, kick them out
    if (!isset($_SESSION['reset_email']) || !isset($_SESSION['verified_to_reset'])) {
        echo "<script>
                alert('Unauthorized access. Please verify your email first.');
                window.location.href = 'forgot-password.php';
              </script>";
        exit();
    }

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];

    // 1. Check if the passwords match
    if ($new_password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match. Please try again.');
                window.location.href = 'Reset-password.php';
              </script>";
        exit();
    }

    // 2. Encrypt the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Save the new password to the database AND delete the 6-digit code so it cannot be reused
    $sql = "UPDATE users SET password_hash = ?, reset_code = NULL WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        
        // 4. Success! Erase the temporary reset sessions so they have to log in normally
        unset($_SESSION['reset_email']);
        unset($_SESSION['verified_to_reset']);

        echo "<script>
                alert('Password successfully changed! You can now log in.');
                window.location.href = 'Login.php';
              </script>";
    } else {
        echo "<script>
                alert('Database error. Please try again.');
                window.location.href = 'Reset-password.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>