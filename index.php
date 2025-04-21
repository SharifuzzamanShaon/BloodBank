<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit;
}

// Redirect to the appropriate dashboard based on the user's role
if ($_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit;
} elseif ($_SESSION['role'] === 'user') {
    header("Location: user_dashboard.php");
    exit;
}
?>
