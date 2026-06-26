<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
  🧠 ใช้แบบนี้:
  - local (docker-compose) → db
  - server (Render) → ENV
*/

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'job_tracker';
$user = getenv('DB_USER') ?: 'tracker_user';
$pass = getenv('DB_PASS') ?: 'tracker_password_123';
$charset = 'utf8mb4';

$port = getenv('DB_PORT') ?: 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}