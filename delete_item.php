<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$conn = new mysqli('localhost', 'root', '', 'users_management');
$conn->query("DELETE FROM items WHERE id = $id");

header('Location: admin_dashboard.php');
