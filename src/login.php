<?php
// src/login.php
require_once 'config.php';

// ถ้าล็อกอินอยู่แล้ว ให้เด้งไปหน้าแดชบอร์ดเลย
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // เตรียมคำสั่ง SQL ตรวจสอบ User
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // ตรวจสอบรหัสผ่านที่แฮชไว้
        if ($user && password_verify($password, $user['password'])) {
            // บันทึกข้อมูลลง Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // ล็อกอินสำเร็จ ส่งไปหน้าแดชบอร์ด
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Username หรือ Password ไม่ถูกต้อง!";
        }
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Job Tracker</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body class="login-body"> <div class="login-card">
        <h2>🔒 เข้าสู่ระบบ</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</body>
</html>