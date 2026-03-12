<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sari-Sari Store Ai</title>
    <link rel="stylesheet" href="../Login.css">
    <style>
        /* Reusing your Show/Hide Password CSS */
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
        <a href="Login.php" class="logo-link">
            <img src="../images/Login.png" alt="Sari-Sari Store Ai Logo" class="logo" id="logo">
        </a>
        
        <h1 class="heading">
            <span class="heading-line">Create a</span>
            <span class="heading-main">New Password</span>
        </h1>
        
        <form class="login-form" action="reset_process.php" method="POST">
            
            <div class="input-group" style="position: relative;">
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    placeholder="New Password" 
                    class="input-field"
                    required
                >
                <span id="toggleNewPassword" class="show-hide-text">Show</span>
            </div>
            
            <div class="input-group" style="position: relative;">
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Confirm New Password" 
                    class="input-field"
                    required
                >
                <span id="toggleConfirmPassword" class="show-hide-text">Show</span>
            </div>
            
            <button type="submit" class="login-button">
                SAVE PASSWORD
            </button>
        </form>
    </div>

    <script>
        // A reusable function so we can apply the logic to both password boxes easily
        function setupPasswordToggle(toggleId, inputId) {
            const toggleBtn = document.getElementById(toggleId);
            const passwordInput = document.getElementById(inputId);

            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                
                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    this.textContent = 'Hide';
                } else {
                    passwordInput.setAttribute('type', 'password');
                    this.textContent = 'Show';
                }
            });
        }

        // Activate the toggle for both input boxes
        setupPasswordToggle('toggleNewPassword', 'new_password');
        setupPasswordToggle('toggleConfirmPassword', 'confirm_password');
    </script>
</body>
</html>