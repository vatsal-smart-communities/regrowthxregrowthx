<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Not authorized, redirect to main store
    $_SESSION['admin_error'] = "Access Denied: You must be logged in as an Administrator.";
    header("Location: ../index.php");
    exit();
}
?>
