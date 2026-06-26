<?php
// src/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // เปิดใช้งาน Session ทุกครั้งที่เรียกใช้ไฟล์นี้
}

$host = 'db';
$db   = 'job_tracker';
$user = 'tracker_user';
$pass = 'tracker_password_123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}