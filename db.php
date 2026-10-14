<?php
$host = 'localhost';
$dbname = 'ticketing_db';
$username = 'root';
$password = 'root'; // Default MAMP MySQL password is often 'root' or empty ('')

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>