<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];

    // Check if we remember their email from the previous page
    if (!isset($_SESSION['reset_email'])) {
        echo "<script>
                alert('Session expired. Please start over.');
                window.location.href = 'forgot-password.php';
              </script>";
        exit();
    }

    $email = $_SESSION['reset_email'];

    // Check if the database has a matching email AND matching reset code
    $sql = "SELECT user_id FROM users WHERE username = ? AND reset_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Success! Set a flag to allow them into the Reset Password page
        $_SESSION['verified_to_reset'] = true;
        
        // Instantly redirect them to create a new password
        header("Location: Reset-password.php");
        exit();
    } else {
        // Wrong code
        echo "<script>
                alert('Invalid verification code. Please try again.');
                window.location.href = 'verify-code.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>