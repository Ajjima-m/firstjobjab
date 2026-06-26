SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `job_tracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `job_tracker`;

-- 1. สร้างตารางผู้ใช้งาน (users)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. สร้างตารางบันทึกการสมัครงาน (job_applications)
CREATE TABLE IF NOT EXISTS `job_applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `company_name` VARCHAR(100) NOT NULL,
    `position` VARCHAR(100) NOT NULL,
    `salary` INT DEFAULT NULL,
    `work_days` VARCHAR(100) DEFAULT NULL,
    `status` ENUM('สมัครแล้ว', 'รอสัมภาษณ์', 'รอ Offer', 'รอเรียก', 'ยืนยันเข้าทำงาน') NOT NULL DEFAULT 'สมัครแล้ว',
    `details` TEXT DEFAULT NULL,
    `applied_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. เพิ่มข้อมูลผู้ใช้เริ่มต้นสำหรับทดสอบ (รหัสผ่านคือ password123 สำหรับทั้งสองบัญชี)
-- ใช้ Password Hash ที่สร้างจากฟังก์ชัน password_hash() ของ PHP
INSERT INTO `users` (`username`, `password`, `name`, `role`) VALUES
('admin_test', '$2y$10$g9G7x63HhH6fU6Z2K2wXOu0MvE8Z0Y19M6W89pZ28vI8W5vY1gD2W', 'แฟน (Admin)', 'admin'),
('user_test', '$2y$10$g9G7x63HhH6fU6Z2K2wXOu0MvE8Z0Y19M6W89pZ28vI8W5vY1gD2W', 'เราเอง (User)', 'user');

-- 4. เพิ่มข้อมูลการสมัครงานสมมติเพื่อทดสอบหน้าแดชบอร์ด
INSERT INTO `job_applications` (`user_id`, `company_name`, `position`, `salary`, `work_days`, `status`, `details`, `applied_date`) VALUES
(1, 'Google Thailand', 'Software Engineer', 85000, 'จันทร์ - ศุกร์ (Hybrid)', 'รอสัมภาษณ์', 'สวัสดิการดีมาก มีอาหารกลางวันฟรี', '2026-06-20'),
(2, 'Agoda', 'Full Stack Developer', 60000, 'จันทร์ - ศุกร์', 'สมัครแล้ว', 'เน้นทักษะ PHP และ JavaScript', '2026-06-25');
