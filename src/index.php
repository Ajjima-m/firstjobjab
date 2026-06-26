<?php
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
     $db_status = "เชื่อมต่อฐานข้อมูลสำเร็จ! 🎉";
} catch (\PDOException $e) {
     $db_status = "เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Tracker - Test Page</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4f8; color: #333; margin: 0; padding: 40px; display: flex; justify-content: center; }
        .container { max-width: 700px; width: 100%; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { color: #2c3e50; margin-top: 0; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .status { padding: 15px; border-radius: 6px; margin: 20px 0; font-weight: bold; font-size: 1.1em; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #f7fafc; color: #4a5568; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold; }
        .badge-admin { background: #ebdcfc; color: #6b46c1; }
        .badge-user { background: #e2f4ff; color: #2b6cb0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Job Application Tracker Ecosystem พร้อมใช้งานแล้ว!</h1>
        <p>ยินดีด้วยครับ! สภาพแวดล้อม Docker สำหรับพัฒนาเว็บไซต์ของพวกคุณทำงานได้อย่างถูกต้องแล้ว</p>
        
        <div class="status <?php echo strpos($db_status, 'สำเร็จ') !== false ? 'success' : 'error'; ?>">
            <?php echo $db_status; ?>
        </div>

        <?php if (strpos($db_status, 'สำเร็จ') !== false): ?>
            <h2>👥 บัญชีผู้ใช้เริ่มต้นในระบบ (จาก init.sql)</h2>
            <table>
                <tr>
                    <th>ชื่อ</th>
                    <th>Username</th>
                    <th>สิทธิ์การใช้งาน (Role)</th>
                </tr>
                <?php
                $stmt = $pdo->query('SELECT username, name, role FROM users');
                while ($row = $stmt->fetch()) {
                    $badge_class = $row['role'] == 'admin' ? 'badge-admin' : 'badge-user';
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td><code>{$row['username']}</code></td>
                            <td><span class='badge {$badge_class}'>{$row['role']}</span></td>
                          </tr>";
                }
                ?>
            </table>
            <p style="color: #718096; font-size: 0.9em; margin-top: 10px;">* รหัสผ่านเริ่มต้นของทั้งสองบัญชีคือ: <code>password123</code></p>
        <?php endif; ?>
    </div>
</body>
</html>
