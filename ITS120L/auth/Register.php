<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Sari-Store AI</title>
    <link rel="stylesheet" href="../Register.css">
</head>
<body>
    <div class="background-container">
        <div class="blurred-background"></div>
    </div>
    
    <div class="form-container">
        <div class="logo">
            <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <text x="30" y="45" font-family="Arial, sans-serif" font-size="48" font-weight="bold" fill="#1a4d7a" text-anchor="middle">A</text>
                <path d="M 15 30 Q 20 25, 25 30 Q 30 25, 35 30 Q 40 25, 45 30" stroke="#6dd5ed" stroke-width="2" fill="none" stroke-linecap="round"/>
            </svg>
        </div>
        
        <h1 class="form-title">
            Create your Sari-Store<br>
            <span class="ai-text">AI Account</span>
        </h1>
        
        <form class="registration-form" id="registrationForm" action="register_process.php" method="POST">
            <div class="input-group">
                <input type="text" id="fullName" name="fullName" placeholder="Username" required>
            </div>
            
            <div class="input-group">
                <input type="text" id="storeName" name="storeName" placeholder="Store Name" required>
            </div>
            
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="input-group" style="position: relative;">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span id="togglePassword" class="show-hide-text">Show</span>
            </div>
            
            <button type="submit" class="create-account-btn">Create Account</button>
        </form>
        
        <div class="footer-link">
            <a href="Login.php" class="login-link">Already have account?</a>
        </div>
    </div>
    
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Check what type the input is currently
            const isPassword = password.getAttribute('type') === 'password';
            
            // Swap the type
            if (isPassword) {
                password.setAttribute('type', 'text');
                this.textContent = 'Hide';
            } else {
                password.setAttribute('type', 'password');
                this.textContent = 'Show';
            }
        });

    </script>
</body>
</html>
