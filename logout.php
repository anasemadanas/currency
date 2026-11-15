<?php
require_once "includes/auth.php";

// Logout is POST-only so a link or image on another site cannot log the user out.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

csrf_check();
logout_user();
header("Location: login.php");
exit;
