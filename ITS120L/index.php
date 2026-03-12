<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Sari Store Inventory</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Logo Section -->
            <div class="logo-container">
                <svg class="logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Circuit traces -->
                    <path d="M 20 50 L 30 50" stroke="#5ac8e8" stroke-width="3" fill="none"/>
                    <path d="M 20 45 L 30 45" stroke="#5ac8e8" stroke-width="2" fill="none"/>
                    <path d="M 20 55 L 30 55" stroke="#5ac8e8" stroke-width="2" fill="none"/>
                    <!-- Letter A -->
                    <path d="M 35 70 L 45 30 L 55 30 L 65 70 M 40 50 L 60 50" stroke="#1f3e80" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="heading">
                Unlock Smarter<br>
                Inventory for Your<br>
                Sari-Sari Store
            </h1>

            <!-- Action Buttons -->
            <div class="button-container">
                <button class="btn btn-primary" onclick="handleLogin()">Login</button>
                <button class="btn btn-primary" onclick="handleRegister()">Register</button>
            </div>

            <!-- Bottom Navigation -->
            <nav class="bottom-nav">
                <a href="#predict" class="nav-item" onclick="handleNavClick('predict')">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 18C8.1 18 9 18.9 9 20C9 21.1 8.1 22 7 22C5.9 22 5 21.1 5 20C5 18.9 5.9 18 7 18Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 18C18.1 18 19 18.9 19 20C19 21.1 18.1 22 17 22C15.9 22 15 21.1 15 20C15 18.9 15.9 18 17 18Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 20H3C2.4 20 2 19.6 2 19V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H19" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 20L15 20" stroke="#666666" stroke-width="2" stroke-linecap="round"/>
                        <path d="M2 7L22 7" stroke="#666666" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 7L7 2L9 2L9 7" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="nav-text">Predict Stock-Outs</span>
                </a>

                <a href="#reduce" class="nav-item" onclick="handleNavClick('reduce')">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#666666" stroke-width="2"/>
                        <path d="M12 6V12M12 12V18" stroke="#666666" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 8L16 16M16 8L8 16" stroke="#666666" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="nav-text">Reduce Waste</span>
                </a>

                <a href="#boost" class="nav-item" onclick="handleNavClick('boost')">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 18L9 12L13 16L21 6" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 6H15M21 6V12" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="nav-text">Boost Sales & Profits</span>
                </a>
            </nav>
        </div>
    </div>

    <script>
    function handleLogin() {
        window.location.href = "auth/Login.php"; 
    }

    function handleRegister() {
        window.location.href = "auth/Register.php"; 
    }

    function handleNavClick(item) {
        console.log('Navigation clicked:', item);
        event.preventDefault();
    }
</script>
</body>
</html>
