<?php
$host = "localhost";
$db   = "helper_app";
$user = "root";
$pass = "root12345"; // your database password

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed");
}
