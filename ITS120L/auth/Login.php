<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sari-Sari Store Ai</title>
    <link rel="stylesheet" href="../Login.css">
    <style>
        /* CSS for the Show Password text */
        .show-hide-text {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6dd5ed;
            font-size: 14px;
            font-weight: bold;
            user-select: none;
        }
        .show-hide-text:hover {
            color: #1a4d7a;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    
    <div class="login-container">
        <a href="index.php" class="logo-link">
            <img src="../images/Login.png" alt="Sari-Sari Store Ai Logo" class="logo" id="logo">
        </a>
        
        <h1 class="heading">
            <span class="heading-line">Log in to your</span>
            <span class="heading-main">Sari-Sari</span>
            <span class="heading-line">Store Ai</span>
        </h1>
        
        <form class="login-form" action="Login_process.php" method="POST">
            <div class="input-group">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Email" 
                    class="input-field"
                    required
                >
            </div>
            
            <div class="input-group" style="position: relative;">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Password" 
                    class="input-field"
                    required
                >
                <span id="togglePassword" class="show-hide-text">Show</span>
            </div>
            
            <button type="submit" class="login-button">
                LOGIN
            </button>
        </form>
        
        <div class="footer-links">
            <a href="Forgot-password.php" class="footer-link">Forgot password?</a>
            <a href="Register.php" class="footer-link">Create Account</a>
        </div>
    </div>

    <script>
        // Show/Hide Password Logic
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                const isPassword = password.getAttribute('type') === 'password';
                if (isPassword) {
                    password.setAttribute('type', 'text');
                    this.textContent = 'Hide';
                } else {
                    password.setAttribute('type', 'password');
                    this.textContent = 'Show';
                }
            });
        }
    </script>
</body>
</html>