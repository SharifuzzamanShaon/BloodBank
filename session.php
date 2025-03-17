<?php
session_start();
if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];  // Initialize as an empty array
}
?>
