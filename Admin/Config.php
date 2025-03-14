<?php
session_start(); // Add this at the top
$host = "localhost";
$username = "root";
$password = "";
$database = "college_admin";
$port = 3307;

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>