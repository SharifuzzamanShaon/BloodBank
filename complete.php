<?php
include 'session.php';

if (isset($_GET['index']) && isset($_SESSION['tasks'][$_GET['index']])) {
    $_SESSION['tasks'][$_GET['index']]['completed'] = true;
}

header("Location: index.php");
exit;
?>
