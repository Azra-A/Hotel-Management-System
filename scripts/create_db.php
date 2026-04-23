<?php

$host = "localhost";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS hotel_management CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

    $pdo->exec($sql);

    echo "<h3>Database 'hotel_management' created successfully (or already exists)!</h3>";
} catch (PDOException $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
}
?>