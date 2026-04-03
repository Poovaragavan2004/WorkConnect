-- ============================================================
-- WorkConnect (LocalWorks) Database
-- Database Name : localworks_db
-- ============================================================

CREATE DATABASE IF NOT EXISTS `localworks_db`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `localworks_db`;

-- ============================================================
-- TABLE: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- TABLE: customers
-- ============================================================
CREATE TABLE IF NOT EXISTS `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- TABLE: workers
-- ============================================================
CREATE TABLE IF NOT EXISTS `workers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `profession` VARCHAR(100) NOT NULL,
  `experience_years` INT NOT NULL DEFAULT 0,
  `hourly_rate` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `service_area` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `skills` TEXT DEFAULT NULL,
  `profile_photo` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending','approved','suspended') NOT NULL DEFAULT 'pending',
  `rating` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` INT NOT NULL DEFAULT 0,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  `background_checked` TINYINT(1) NOT NULL DEFAULT 0,
  `insured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- TABLE: bookings
-- ============================================================
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `worker_id` INT NOT NULL,
  `service_type` VARCHAR(150) NOT NULL,
  `booking_date` DATE NOT NULL,
  `booking_time` TIME NOT NULL,
  `service_address` TEXT NOT NULL,
  `job_details` TEXT DEFAULT NULL,
  `estimated_earnings` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pending','accepted','completed','declined') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`worker_id`) REFERENCES `workers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Admin (password: "password" hashed with password_hash)
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Customers (password: "password123" for all)
INSERT INTO `customers` (`full_name`, `email`, `password`, `phone`, `address`) VALUES
('Rahul Sharma', 'rahul@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', '12, MG Road, Coimbatore, Tamil Nadu 641001'),
('Priya Nair', 'priya@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', '45, Anna Nagar, Chennai, Tamil Nadu 600040'),
('Arun Kumar', 'arun@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', '78, Gandhi Street, Madurai, Tamil Nadu 625001'),
('Deepa Lakshmi', 'deepa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', '23, Nehru Layout, Trichy, Tamil Nadu 620001'),
('Karthik Rajan', 'karthik@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543214', '56, Lake View Road, Ooty, Tamil Nadu 643001');

-- Workers (password: "password123" for all)
INSERT INTO `workers` (`full_name`, `email`, `password`, `phone`, `profession`, `experience_years`, `hourly_rate`, `service_area`, `bio`, `skills`, `status`, `rating`, `total_reviews`, `is_available`, `background_checked`, `insured`) VALUES
('Murugan S', 'murugan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776611', 'Plumber', 8, 45.00, 'Coimbatore - 15 km radius', 'Experienced plumber specializing in residential and commercial plumbing. Expert in pipe fitting, leak repairs, and bathroom installations.', 'Pipe Fitting, Leak Repair, Bathroom Installation, Water Heater', 'approved', 4.70, 32, 1, 1, 1),
('Suresh P', 'suresh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776622', 'Electrician', 12, 55.00, 'Chennai - 20 km radius', 'Licensed electrician with over a decade of experience. Handling wiring, panel upgrades, and smart-home installations.', 'Wiring, Panel Upgrade, Smart Home, Lighting, Generator', 'approved', 4.90, 58, 1, 1, 1),
('Lakshmi R', 'lakshmi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776633', 'Carpenter', 6, 40.00, 'Madurai - 10 km radius', 'Skilled carpenter providing custom furniture making, door & window fitting, and modular kitchen installations.', 'Furniture Making, Door Fitting, Modular Kitchen, Wood Polish', 'approved', 4.50, 21, 1, 0, 0),
('Ganesh V', 'ganesh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776644', 'Painter', 5, 35.00, 'Trichy - 12 km radius', 'Professional painter for interior and exterior painting. Expertise in texture paint, wall design, and waterproofing.', 'Interior Painting, Exterior Painting, Texture, Waterproofing', 'approved', 4.30, 15, 1, 0, 1),
('Rajesh K', 'rajesh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776655', 'Landscaper', 4, 50.00, 'Ooty - 25 km radius', 'Creative landscaper offering garden design, lawn maintenance, and terrace gardening services across the Nilgiris.', 'Garden Design, Lawn Care, Terrace Garden, Tree Trimming', 'approved', 4.60, 27, 0, 1, 0),
('Vijay M', 'vijay@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776666', 'Handyman', 3, 30.00, 'Coimbatore - 10 km radius', 'All-round handyman for small home repairs, furniture assembly, TV mounting, and general maintenance work.', 'Home Repair, Furniture Assembly, TV Mount, Maintenance', 'pending', 0.00, 0, 1, 0, 0),
('Kumar T', 'kumar@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9988776677', 'Electrician', 2, 35.00, 'Chennai - 15 km radius', 'Junior electrician looking for opportunities. Trained in domestic wiring and fan/light installations.', 'Wiring, Fan Installation, Light Fitting', 'pending', 0.00, 0, 1, 0, 0);

-- Bookings
INSERT INTO `bookings` (`customer_id`, `worker_id`, `service_type`, `booking_date`, `booking_time`, `service_address`, `job_details`, `estimated_earnings`, `status`) VALUES
(1, 1, 'Plumbing Repair', '2026-02-25', '10:00:00', '12, MG Road, Coimbatore, Tamil Nadu 641001', 'Kitchen sink is leaking and the tap needs replacement. Please bring necessary parts.', 90.00, 'accepted'),
(2, 2, 'Electrical Work', '2026-02-26', '14:30:00', '45, Anna Nagar, Chennai, Tamil Nadu 600040', 'Need to install 3 new ceiling fans and replace old switchboard in the hall.', 110.00, 'pending'),
(3, 3, 'Carpentry', '2026-02-27', '09:00:00', '78, Gandhi Street, Madurai, Tamil Nadu 625001', 'Build a custom bookshelf for the study room. Dimensions: 6ft x 4ft.', 80.00, 'pending'),
(1, 4, 'General Maintenance', '2026-02-24', '11:00:00', '12, MG Road, Coimbatore, Tamil Nadu 641001', 'Repaint the bedroom walls. Light blue colour preferred.', 70.00, 'completed'),
(4, 2, 'Electrical Work', '2026-02-28', '16:00:00', '23, Nehru Layout, Trichy, Tamil Nadu 620001', 'Inverter installation and wiring for backup power in the living room.', 110.00, 'accepted'),
(5, 5, 'Landscaping', '2026-03-01', '08:00:00', '56, Lake View Road, Ooty, Tamil Nadu 643001', 'Design and set up a small terrace garden with flowering plants and herbs.', 100.00, 'pending'),
(2, 1, 'Plumbing Repair', '2026-02-23', '15:00:00', '45, Anna Nagar, Chennai, Tamil Nadu 600040', 'Bathroom pipe burst. Need urgent repair.', 90.00, 'declined'),
(3, 4, 'General Maintenance', '2026-03-02', '13:00:00', '78, Gandhi Street, Madurai, Tamil Nadu 625001', 'Full exterior wall painting for 2-bedroom house.', 70.00, 'pending');
