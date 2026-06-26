<?php
// src/add-job.php
require_once 'config.php';

// เช็คความปลอดภัย: ถ้ายังไม่ได้ล็อกอิน ให้เด้งกลับไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success_msg = '';
$error_msg = '';

// ตรวจสอบเมื่อมีการกดปุ่มส่งข้อมูล (Submit Form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name']);
    $position     = trim($_POST['position']);
    $salary       = !empty($_POST['salary']) ? intval($_POST['salary']) : null;
    $work_days    = trim($_POST['work_days']);
    $status       = $_POST['status'];
    $details      = trim($_POST['details']);
    $applied_date = $_POST['applied_date'];
    $user_id      = $_SESSION['user_id']; // บันทึกว่างานนี้เป็นของคนที่ล็อกอินอยู่

    if (!empty($company_name) && !empty($position) && !empty($applied_date)) {
        try {
            $sql = "INSERT INTO job_applications (user_id, company_name, position, salary, work_days, status, details, applied_date) 
                    VALUES (:user_id, :company_name, :position, :salary, :work_days, :status, :details, :applied_date)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'user_id'      => $user_id,
                'company_name' => $company_name,
                'position'     => $position,
                'salary'       => $salary,
                'work_days'    => $work_days,
                'status'       => $status,
                'details'      => $details,
                'applied_date' => $applied_date
            ]);

            $success_msg = "บันทึกข้อมูลการสมัครงานสำเร็จแล้ว! 🎉";
        } catch (\PDOException $e) {
            $error_msg = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage();
        }
    } else {
        $error_msg = "กรุณากรอกข้อมูลในช่องที่จำเป็นให้ครบถ้วน (บริษัท, ตำแหน่ง, วันที่สมัคร)";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลการสมัครงาน - Job Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                💼 Job Tracker
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 แดชบอร์ด</a></li>
                <li class="active"><a href="add-job.php">➕ เพิ่มประวัติงาน</a></li>
            </ul>
            <div class="sidebar-footer">
                <div style="margin-bottom: 10px; font-size: 0.9rem; color: #94a3b8;">
                    ผู้ใช้: <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>
                </div>
                <a href="logout.php" class="btn-logout">ออกจากระบบ</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>➕ เพิ่มประวัติการสมัครงาน</h1>
            </div>

            <div class="form-card">
                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success"><?= $success_msg ?></div>
                <?php endif; ?>
                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error"><?= $error_msg ?></div>
                <?php endif; ?>

                <form action="add-job.php" method="POST">
                    <div class="form-group">
                        <label>ชื่อบริษัท <span style="color:red;">*</span></label>
                        <input type="text" name="company_name" required placeholder="เช่น Google, Agoda">
                    </div>
                    <div class="form-group">
                        <label>ตำแหน่งงาน <span style="color:red;">*</span></label>
                        <input type="text" name="position" required placeholder="เช่น Backend Developer">
                    </div>
                    <div class="form-group">
                        <label>เงินเดือน (บาท)</label>
                        <input type="number" name="salary" placeholder="เช่น 30000">
                    </div>
                    <div class="form-group">
                        <label>วันทำงาน / รูปแบบการทำงาน</label>
                        <input type="text" name="work_days" placeholder="เช่น จันทร์-ศุกร์, Hybrid">
                    </div>
                    <div class="form-group">
                        <label>สถานะปัจจุบัน</label>
                        <select name="status">
                            <option value="สมัครแล้ว">สมัครแล้ว</option>
                            <option value="รอสัมภาษณ์">รอสัมภาษณ์</option>
                            <option value="รอ Offer">รอ Offer</option>
                            <option value="รอเรียก">รอเรียก</option>
                            <option value="ยืนยันเข้าทำงาน">ยืนยันเข้าทำงาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>วันที่สมัคร <span style="color:red;">*</span></label>
                        <input type="date" name="applied_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>รายละเอียดเพิ่มเติม</label>
                        <textarea name="details" placeholder="สวัสดิการ รายละเอียดโน้ตต่างๆ..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary">บันทึกข้อมูล</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>