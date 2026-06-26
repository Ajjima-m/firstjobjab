<?php
// src/dashboard.php
require_once 'config.php';

// เช็คความปลอดภัย: ถ้ายังไม่ได้ล็อกอิน ให้เด้งกลับไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

try {
    // 🔍 1. เขียน SQL แยกตามสิทธิ์การเข้าถึงข้อมูล
    if ($role === 'admin') {
        // แอดมิน: ดึงข้อมูลงานของทุกคน + JOIN ตาราง users เพื่อดึงชื่อผู้สมัครมาโชว์ด้วย
        $sql = "SELECT ja.*, u.name AS applicant_name 
                FROM job_applications ja
                JOIN users u ON ja.user_id = u.id
                ORDER BY ja.applied_date DESC, ja.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        // ผู้ใช้ทั่วไป: ดึงเฉพาะข้อมูลของตัวเอง
        $sql = "SELECT ja.*, u.name AS applicant_name 
                FROM job_applications ja
                JOIN users u ON ja.user_id = u.id
                WHERE ja.user_id = :user_id
                ORDER BY ja.applied_date DESC, ja.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
    }
    
    // ดึงข้อมูลทั้งหมดออกมาในรูปแบบ Array
    $jobs = $stmt->fetchAll();

} catch (\PDOException $e) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage());
}

// 🎨 ฟังก์ชันช่วยเลือกคลาสสีของ Badge ตามสถานะงาน
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'สมัครแล้ว':
            return 'badge-default';
        case 'รอสัมภาษณ์':
            return 'badge-interview';
        case 'รอ Offer':
        case 'รอเรียก':
            return 'badge-offer';
        case 'ยืนยันเข้าทำงาน':
            return 'badge-success';
        default:
            return 'badge-default';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Job Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                💼 Job Tracker
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="dashboard.php">📊 แดชบอร์ด</a></li>
                <li><a href="add-job.php">➕ เพิ่มประวัติงาน</a></li>
            </ul>
            <div class="sidebar-footer">
                <div style="margin-bottom: 10px; font-size: 0.9rem; color: #94a3b8;">
                    ผู้ใช้: <strong><?= htmlspecialchars($_SESSION['name']) ?></strong> 
                    <span class="badge <?= $role === 'admin' ? 'badge-admin' : 'badge-user' ?>" style="padding: 2px 6px; font-size: 0.75rem;">
                        <?= ucfirst($role) ?>
                    </span>
                </div>
                <a href="logout.php" class="btn-logout">ออกจากระบบ</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>📊 รายการประวัติการสมัครงาน</h1>
                <p style="color: #64748b; margin-top: 5px;">
                    <?= $role === 'admin' ? 'แสดงข้อมูลการสมัครงานของทุกคนในระบบ' : 'แสดงข้อมูลการสมัครงานของคุณเองทั้งหมด' ?>
                </p>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>วันที่สมัคร</th>
                            <th>บริษัท</th>
                            <th>ตำแหน่ง</th>
                            <th>เงินเดือน (บาท)</th>
                            <th>รูปแบบงาน</th>
                            <?php if ($role === 'admin'): ?>
                                <th>ผู้สมัคร</th> <?php endif; ?>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jobs)): ?>
                            <tr>
                                <td colspan="<?= $role === 'admin' ? 7 : 6 ?>" style="text-align: center; color: #64748b; padding: 40px 0;">
                                    📭 ยังไม่มีข้อมูลการสมัครงานในระบบในขณะนี้
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($job['applied_date']))) ?></td>
                                    <td style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($job['company_name']) ?></td>
                                    <td><?= htmlspecialchars($job['position']) ?></td>
                                    <td>
                                        <?= $job['salary'] ? number_format($job['salary']) : '<span style="color:#94a3b8;">ไม่ได้ระบุ</span>' ?>
                                    </td>
                                    <td><?= htmlspecialchars($job['work_days'] ?: '-') ?></td>
                                    
                                    <?php if ($role === 'admin'): ?>
                                        <td>
                                            <span class="badge badge-user" style="color: #1e293b; background: #f1f5f9;">
                                                👤 <?= htmlspecialchars($job['applicant_name']) ?>
                                            </span>
                                        </td>
                                    <?php endif; ?>

                                    <td>
                                        <span class="badge <?= getStatusBadgeClass($job['status']) ?>">
                                            <?= htmlspecialchars($job['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>