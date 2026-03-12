<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // 1. Check if the email exists in the database (remember, we store emails in the 'username' column)
    $sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // 2. Generate a random 6-digit code
        $reset_code = rand(100000, 999999);

        // 3. Save this code into the database for this specific user
        $update_sql = "UPDATE users SET reset_code = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $reset_code, $email);
        $update_stmt->execute();

        // Save the email in a session so the next page knows who is trying to reset
        $_SESSION['reset_email'] = $email;

        // 4. Simulate sending the email (For local testing)
        echo "<script>
                alert('SIMULATION: An email was sent to $email. \\n\\nYour recovery code is: $reset_code');
                window.location.href = 'Verify-code.php';
              </script>";
    } else {
        // If email doesn't exist in the database
        echo "<script>
                alert('We could not find an account with that email address.');
                window.location.href = 'forgot-password.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>