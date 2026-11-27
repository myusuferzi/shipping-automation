<?php
$host     = 'localhost';
$dbname   = 'shipping_automation';
$username = 'root';
$password = ''; // Veritabanı şifrenizi buraya giriniz

$db = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
?>
