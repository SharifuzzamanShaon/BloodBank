<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit;
} elseif ($_SESSION['role'] === 'user') {
    header("Location: user_dashboard.php");
    exit;
}
?>
