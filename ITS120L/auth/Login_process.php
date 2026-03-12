<?php

session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, full_name, store_name, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    
        if (password_verify($password, $user['password_hash'])) {
         
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_full_name'] = $user['full_name'];
            $_SESSION['store_name'] = $user['store_name'];
       
            header("Location: ../pages/Home.php");
            exit();

        } else {
 
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'Login.php';
                  </script>";
        }
    } else {
   
        echo "<script>
                alert('No account found with that email.');
                window.location.href = 'Login.php';
              </script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>