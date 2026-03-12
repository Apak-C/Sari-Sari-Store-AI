<?php
// 1. Open the session memory
session_start();

// 2. Erase all data (this deletes the user_id)
session_unset();

// 3. Destroy the session completely
session_destroy();

// 4. Send them back to your Login page
header("Location: Login.php");
exit();
?>