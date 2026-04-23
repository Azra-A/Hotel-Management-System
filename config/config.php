<?php
require_once __DIR__ . '/../app/helpers/Csrf.php';
require_once __DIR__ . '/../app/helpers/CsvExporter.php';

Csrf::init();


$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "hotel_management";

try {
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

    $conn = new PDO($dsn, $dbUser, $dbPass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>