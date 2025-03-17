<?php
include 'session.php';

if (isset($_GET['index']) && isset($_SESSION['blood_banks'][$_GET['index']])) {
    array_splice($_SESSION['blood_banks'], $_GET['index'], 1);
}

header("Location: index.php");
exit;
?>
