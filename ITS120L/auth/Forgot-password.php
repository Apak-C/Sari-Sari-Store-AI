<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Sari-Sari Store Ai</title>
    <link rel="stylesheet" href="../Login.css">
    <link rel="stylesheet" href="../Forgot-password.css">
</head>
<body>
    <div class="background"></div>
    
    <div class="login-container">
        <a href="Login.php" class="logo-link">
            <img src="../images/Login.png" alt="Sari-Sari Store Ai Logo" class="logo" id="logo">
        </a>
        
        <h1 class="heading">
            <span class="heading-line">Forgot your</span>
            <span class="heading-main">Password?</span>
            <span class="heading-line">We'll help you recover it</span>
        </h1>
        
        <form class="login-form" action="forgot_process.php" method="POST">
            <div class="input-group">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email address" 
                    class="input-field"
                    required
                    autocomplete="email"
                >
            </div>
            
            <button type="submit" class="login-button">
                SEND VERIFICATION CODE
            </button>
        </form>
        
        <div class="footer-links">
            <a href="Login.php" class="footer-link">Back to Login</a>
            <a href="Register.php" class="footer-link" id="createAccount">Create Account</a>
        </div>
    </div>
</body>
</html>