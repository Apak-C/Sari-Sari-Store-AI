<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code - Sari-Sari Store Ai</title>
    <link rel="stylesheet" href="../Login.css">
    <link rel="stylesheet" href="../Forgot-password.css">
    <style>
        /* Cleaned up and fixed Code Input Styling for a white background */
        .code-input {
            width: 50px; 
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #d1d5db; /* Visible light gray border */
            border-radius: 10px;
            background: #f9fafb; /* Very light gray background */
            color: #1a4d7a; /* Your brand's dark blue text! */
            outline: none;
            transition: all 0.2s ease;
        }

        .code-input:focus {
            border-color: #6dd5ed !important; /* Your brand's light blue */
            box-shadow: 0 0 0 3px rgba(109, 213, 237, 0.2) !important;
            background: #ffffff;
        }

        /* Fixed the email display banner so it is readable too */
        .email-display {
            background: #f0f8ff; 
            padding: 12px; 
            border-radius: 8px; 
            text-align: center; 
            margin-bottom: 20px; 
            color: #1a4d7a; 
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #b3d4ec;
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
            <span class="heading-line">Enter verification</span>
            <span class="heading-main">Code</span>
        </h1>
        
        <div class="email-display" id="emailDisplay">
            <?php 
            session_start();
            if(isset($_SESSION['reset_email'])) {
                echo "Check your email: " . htmlspecialchars($_SESSION['reset_email']);
            } else {
                echo "Session expired. Please try again.";
            }
            ?>
        </div>
        
        <div id="message" class="message" style="display: none;"></div>
        
        <form class="login-form" id="verifyCodeForm" action="verify_process.php" method="POST">
            
            <input type="hidden" name="code" id="finalCode">

            <div class="input-group" style="margin-bottom: 20px;">
                <div class="code-inputs" id="codeInputs" style="display: flex; justify-content: space-between; gap: 8px;">
                    <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                </div>
            </div>
            
            <button type="submit" class="login-button" id="verifyBtn">
                VERIFY CODE
            </button>
        </form>
        
        <div class="footer-links">
            <a href="forgot-password.php" class="footer-link" id="resendLink">Resend Code</a>
            <a href="Login.php" class="footer-link">Back to Login</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('verifyCodeForm');
        const codeInputs = document.querySelectorAll('.code-input');
        const finalCodeInput = document.getElementById('finalCode');

        codeInputs[0].focus();

        codeInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = value;

                if (value && index < codeInputs.length - 1) {
                    codeInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    codeInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                pastedData.split('').forEach((char, i) => {
                    if (codeInputs[index + i]) {
                        codeInputs[index + i].value = char;
                    }
                });
                if (pastedData.length === 6) {
                    codeInputs[5].focus();
                }
            });
        });

        form.addEventListener('submit', (e) => {
            const code = Array.from(codeInputs).map(input => input.value).join('');
            
            if (code.length !== 6) {
                e.preventDefault();
                alert('Please enter the complete 6-digit code');
            } else {
                finalCodeInput.value = code;
            }
        });
    </script>
</body>
</html>