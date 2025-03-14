<?php
require_once 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$conn->query("DELETE FROM students WHERE id = $id");
header("Location: dashboard.php");
?>
