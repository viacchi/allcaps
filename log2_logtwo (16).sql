-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 16, 2026 at 03:08 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `log2_logtwo`
--

-- --------------------------------------------------------

--
-- Table structure for table `behavior_incidents`
--

CREATE TABLE `behavior_incidents` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `severity` enum('High','Medium','Low') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `behavior_incidents`
--

INSERT INTO `behavior_incidents` (`id`, `driver_id`, `type`, `severity`, `location`, `speed`, `date`, `notes`, `created_at`) VALUES
(1, 20, 'Speeding', 'High', 'EDSA Makati', 95, '2026-02-08 09:30:00', 'Exceeded speed limit', '2026-02-09 02:16:37'),
(2, 21, 'Harsh Braking', 'Medium', 'C5 Taguig', NULL, '2026-02-07 14:15:00', 'Sudden stop due to traffic', '2026-02-09 02:16:37');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `budget_year` year(4) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `used_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `date_obtained` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `driver_id`, `name`, `date_obtained`, `expiry_date`) VALUES
(1, 20, 'Defensive Driving Certificate', '2025-06-01', '2027-06-01'),
(2, 21, 'Heavy Vehicle License', '2024-03-15', '2026-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `compliance_documents`
--

CREATE TABLE `compliance_documents` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `document_number` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compliance_documents`
--

INSERT INTO `compliance_documents` (`id`, `vehicle_id`, `document_type`, `issue_date`, `expiry_date`, `document_number`, `file_path`, `notes`, `created_at`) VALUES
(1, 1, 'Registration', '2025-01-01', '2027-01-01', 'REG-001', 'uploads/documents/reg1.pdf', 'Vehicle registration document', '2026-01-25 05:20:14'),
(2, 1, 'Insurance', '2025-02-01', '2027-02-01', 'INS-001', 'uploads/documents/ins1.pdf', 'Comprehensive insurance', '2026-01-25 05:20:14'),
(3, 2, 'Registration', '2025-03-01', '2026-03-01', 'REG-002', 'uploads/documents/reg2.pdf', 'Vehicle registration document', '2026-01-25 05:20:14'),
(4, 2, 'Insurance', '2025-03-15', '2026-03-15', 'INS-002', 'uploads/documents/ins2.pdf', 'Third-party insurance', '2026-01-25 05:20:14'),
(5, 3, 'Registration', '2025-01-20', '2027-01-20', 'REG-003', 'uploads/documents/reg3.pdf', 'Vehicle registration document', '2026-01-25 05:20:14'),
(6, 3, 'Insurance', '2025-02-10', '2027-02-10', 'INS-003', 'uploads/documents/ins3.pdf', 'Comprehensive insurance', '2026-01-25 05:20:14'),
(7, 3, 'Emission Test', '2025-03-05', '2026-03-05', 'EMI-001', 'uploads/documents/emi1.pdf', 'Emission compliance test', '2026-01-25 05:20:14'),
(8, 1, 'Registration', '2026-02-06', '2050-02-06', NULL, NULL, NULL, '2026-02-05 22:05:23'),
(9, 1, 'Registration', '2026-02-12', '2030-02-12', NULL, '../uploads/1770842273_Black.jpg', NULL, '2026-02-11 20:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `dispatches`
--

CREATE TABLE `dispatches` (
  `id` int(11) NOT NULL,
  `tracking_id` varchar(50) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `status` enum('Pending','Assigned','On Duty','Completed','Cancelled') DEFAULT 'Pending',
  `proof_image` varchar(255) DEFAULT NULL,
  `start_location` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `dispatch_date` datetime DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatches`
--

INSERT INTO `dispatches` (`id`, `tracking_id`, `vehicle_id`, `driver_id`, `status`, `proof_image`, `start_location`, `destination`, `dispatch_date`, `return_date`, `created_at`) VALUES
(1, 'TRK-387E67', 7, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'SM Mall of Asia', '2026-02-14 00:03:08', '2026-02-14 09:54:17', '2026-02-13 16:03:08'),
(2, 'TRK-AF92D4', 8, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'bestlink college of the philippes', '2026-02-14 00:04:08', '2026-02-14 08:17:07', '2026-02-13 16:04:08'),
(3, 'TRK-265893', 9, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'bestlink college of the philippes', '2026-02-14 00:05:39', '2026-02-14 01:31:12', '2026-02-13 16:05:39'),
(4, 'TRK-1CE967', 10, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Bestlink College of the Philippines - Criminology Department, Heavenly Drive, Heavenly-Arsen, San Agustin, 5th District, Caloocan, Eastern Manila District, Metro Manila, 1400, Philippines', '2026-02-14 00:07:22', '2026-02-14 01:19:49', '2026-02-13 16:07:22'),
(5, 'TRK-BBEEBD', 11, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Bestlink College of the Philippines - Criminology Department, Heavenly Drive, Heavenly-Arsen, San Agustin, 5th District, Caloocan, Eastern Manila District, Metro Manila, 1400, Philippines', '2026-02-14 00:26:48', '2026-02-14 00:42:28', '2026-02-13 16:26:48'),
(6, 'TRK-4EDCA5', 12, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Makati SM Felicidad SY Center for the Elderly, J. P. Rizal Street, Tejeros, District I, Makati, Southern Manila District, Metro Manila, 1204, Philippines', '2026-02-14 01:25:15', '2026-02-14 01:29:17', '2026-02-13 17:25:15'),
(7, 'TRK-9A1E88', 13, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Moo Duk Kwan Soo Bahk Do, 51, S. Artiaga Street, Barangay 36-D, Poblacion District, Davao City, Davao Region, 8000, Philippines', '2026-02-14 08:14:57', '2026-02-14 08:16:56', '2026-02-14 00:14:57'),
(8, 'TRK-1281BD', 0, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'ACE Hardware, Quirino Highway, Pasong Putik Proper, 5th District, Quezon City, Eastern Manila District, Metro Manila, 1118, Philippines', '2026-02-14 10:11:37', '2026-02-14 23:01:33', '2026-02-14 02:11:37'),
(9, 'TRK-C86A5F', 0, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Bagong Silang, District 1, Caloocan, Northern Manila District, Metro Manila, 1428, Philippines', '2026-02-14 11:21:28', '2026-02-14 23:01:12', '2026-02-14 03:21:28'),
(10, 'TRK-841271', 0, 20, 'Assigned', NULL, 'Headquarters (Main Hub)', 'Bestlink College of the Philippines, 1071, Quirino Highway, Kaligayahan, 5th District, Quezon City, Eastern Manila District, Metro Manila, 1123, Philippines', '2026-02-14 14:02:15', NULL, '2026-02-14 06:02:15'),
(11, 'TRK-017132', 0, 20, 'Assigned', NULL, 'Headquarters (Main Hub)', 'Makati', '2026-02-14 14:03:48', NULL, '2026-02-14 06:03:48'),
(12, 'TRK-017132', 0, 20, 'Assigned', NULL, 'Headquarters (Main Hub)', 'Makati', '2026-02-14 14:03:48', NULL, '2026-02-14 06:03:48'),
(13, 'TRK-6DDBFC', 0, 24, 'Completed', '1771052100_17710520849861662894987546812618.jpg', 'Headquarters (Main Hub)', 'BGC, McKinley Parkway, Fort Bonifacio, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1635, Philippines', '2026-02-14 14:53:42', '2026-02-14 14:55:28', '2026-02-14 06:53:42'),
(14, 'TRK-37599B', 0, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Makati SM Felicidad SY Center for the Elderly, J. P. Rizal Street, Tejeros, District I, Makati, Southern Manila District, Metro Manila, 1204, Philippines', '2026-02-14 23:02:27', '2026-02-14 23:03:11', '2026-02-14 15:02:27'),
(15, 'TRK-10F1DB', 0, 24, 'Completed', '1771084531_1000353601.jpg', 'Headquarters (Main Hub)', 'Mandaluyong, Eastern Manila District, Metro Manila, Philippines', '2026-02-14 23:06:53', '2026-02-14 23:55:41', '2026-02-14 15:06:53'),
(16, 'TRK-064A5A', 0, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'Pasay, Southern Manila District, Metro Manila, Philippines', '2026-02-15 00:07:26', '2026-02-15 00:56:47', '2026-02-14 16:07:26'),
(17, 'TRK-1BEABA', 0, 24, 'Completed', NULL, 'Headquarters (Main Hub)', 'SM Mall of Asia, Seaside Boulevard, Barangay 76, Zone 10, District 1, Pasay, Southern Manila District, Metro Manila, 1300, Philippines', '2026-02-15 07:44:45', '2026-02-15 09:51:49', '2026-02-14 23:44:45'),
(18, 'TRK-60E6CC', 0, 24, 'Completed', '1771139384_1000352612.jpg', 'Headquarters (Main Hub)', 'SM Mall of Asia, Seaside Boulevard, Barangay 76, Zone 10, District 1, Pasay, Southern Manila District, Metro Manila, 1300, Philippines', '2026-02-15 14:25:39', '2026-02-15 15:09:51', '2026-02-15 06:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `assigned_vehicle_id` int(11) DEFAULT NULL,
  `license` varchar(30) DEFAULT NULL,
  `status` enum('Active','On Leave','Inactive') DEFAULT 'Active',
  `address` varchar(255) DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `safety_score` decimal(5,2) DEFAULT 0.00,
  `on_time_rate` decimal(5,2) DEFAULT 0.00,
  `total_trips` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_distance` decimal(10,2) DEFAULT 0.00,
  `incidents` int(11) DEFAULT 0,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `user_id`, `full_name`, `assigned_vehicle_id`, `license`, `status`, `address`, `emergency_contact`, `blood_type`, `join_date`, `expiry`, `rating`, `safety_score`, `on_time_rate`, `total_trips`, `created_at`, `updated_at`, `total_distance`, `incidents`, `profile_picture`) VALUES
(20, 17, 'Richard Dela Rosa', NULL, NULL, 'Active', NULL, NULL, NULL, NULL, '2027-02-09', 4.50, 95.00, 90.00, 12, '2026-02-08 23:16:47', '2026-02-09 02:26:21', 1200.50, 1, NULL),
(21, 20, 'Ricardo  Tavara Jr', NULL, NULL, 'Active', NULL, NULL, NULL, NULL, '2027-02-09', 4.30, 92.00, 88.00, 10, '2026-02-08 23:16:47', '2026-02-09 02:26:21', 980.00, 0, NULL),
(23, 17, 'Richard Dela Rosa', NULL, 'L001-2345', 'Active', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0, '2026-02-13 08:07:27', '2026-02-13 08:07:27', 0.00, 0, NULL),
(24, 22, 'Emers', 1, 'LIC-4957', 'Active', NULL, '', NULL, '2026-02-13', NULL, NULL, 0.00, 0.00, 0, '2026-02-13 14:05:29', '2026-02-15 01:05:32', 0.00, 0, 'uploads/profile_pics/driver_22_1771116315.png'),
(25, 23, 'Testers', NULL, 'PENDING-4061', 'Active', NULL, NULL, NULL, '2026-02-14', NULL, NULL, 0.00, 0.00, 0, '2026-02-13 17:31:55', '2026-02-13 17:31:55', 0.00, 0, NULL),
(26, 24, 'Trishia Del Norte', NULL, 'PENDING-5121', 'Active', NULL, NULL, NULL, '2026-02-14', NULL, NULL, 0.00, 0.00, 0, '2026-02-14 06:52:29', '2026-02-14 06:52:29', 0.00, 0, NULL),
(27, 25, 'Agrifino Dacles', NULL, 'PENDING-3027', 'Active', NULL, NULL, NULL, '2026-02-15', NULL, NULL, 0.00, 0.00, 0, '2026-02-14 23:56:15', '2026-02-14 23:56:15', 0.00, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `driver_behavior`
--

CREATE TABLE `driver_behavior` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `score` int(11) NOT NULL,
  `speeding` int(11) DEFAULT 0,
  `harsh_braking` int(11) DEFAULT 0,
  `idle_time` int(11) DEFAULT 0,
  `trips` int(11) DEFAULT 0,
  `month_year` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_behavior`
--

INSERT INTO `driver_behavior` (`id`, `driver_id`, `driver_name`, `score`, `speeding`, `harsh_braking`, `idle_time`, `trips`, `month_year`, `created_at`) VALUES
(4, 20, 'Richard Dela Rosa', 85, 2, 1, 30, 12, '2026-02-01', '2026-02-09 02:17:52'),
(5, 21, 'Ricardo Tavara Jr', 90, 1, 0, 20, 10, '2026-02-01', '2026-02-09 02:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `expense_requests`
--

CREATE TABLE `expense_requests` (
  `id` int(11) NOT NULL,
  `expense_type` varchar(100) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT 'Logistic 2',
  `request_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `driver_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `expense_requests`
--

INSERT INTO `expense_requests` (`id`, `expense_type`, `requested_by`, `department`, `request_date`, `amount`, `description`, `receipt_path`, `contact`, `status`, `created_at`, `updated_at`, `driver_id`, `vehicle_id`) VALUES
(1, 'Fuel Reimbursement', 'Via Jeves', 'Logistics 2', '2026-02-10', 10000.00, 'Fuel Expense for February Month', NULL, '09123456789', 'Approved', '2026-02-14 08:00:33', '2026-02-14 14:54:13', NULL, NULL),
(2, 'Vehicle Maintenance', 'Via Jeves', 'Logistics 2', '2026-02-12', 10000.50, 'Regular MAintenance', NULL, '09123456789', 'Approved', '2026-02-14 08:00:33', '2026-02-14 14:54:10', NULL, NULL),
(3, 'Fuel Reimbursement', 'Via Jeves', 'Logistics 2', '2026-02-10', 10000.00, 'Fuel Expense for Emergencies', NULL, '09123456789', 'Approved', '2026-02-14 08:00:33', '2026-02-14 14:54:07', NULL, NULL),
(4, 'Fuel Reimbursement', 'Via Jeves', 'Logistic 2', '2026-02-14', 12500.00, 'Fuel expense for weekly deliveries', NULL, '09123456789', 'Approved', '2026-02-14 14:02:59', '2026-02-14 14:41:21', NULL, NULL),
(5, 'Vehicle Maintenance', 'Mark Dela Cruz', 'Logistic 2', '2026-02-13', 18500.50, 'Brake replacement and oil change', NULL, '09987654321', 'Approved', '2026-02-14 14:02:59', '2026-02-14 14:52:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fuel_expenses`
--

CREATE TABLE `fuel_expenses` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `liters` decimal(10,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) DEFAULT 'Pending',
  `fuel_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_cases`
--

CREATE TABLE `incident_cases` (
  `id` int(11) NOT NULL,
  `case_number` varchar(50) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `severity` enum('Low','Medium','High') DEFAULT 'Low',
  `date` datetime DEFAULT NULL,
  `reported_by` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Under Investigation','Pending Review','Resolved','Closed') DEFAULT 'Under Investigation',
  `resolution_notes` text DEFAULT NULL,
  `attachments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_cases`
--

INSERT INTO `incident_cases` (`id`, `case_number`, `driver_id`, `vehicle_id`, `type`, `severity`, `date`, `reported_by`, `location`, `description`, `status`, `resolution_notes`, `attachments`) VALUES
(1, 'CASE-20260124-143959', 5, NULL, 'Traffic Violation', 'Medium', '2026-01-24 21:39:00', 'Driver', 'EDSA Quezon Ave.', 'hhh', 'Closed', 'hhhh', NULL),
(2, 'CASE-20260124-144004', 5, NULL, 'Traffic Violation', 'Medium', '2026-01-24 21:39:00', 'Driver', 'EDSA Quezon Ave.', 'hhh', 'Closed', 'waaa', NULL),
(5, 'CASE-20260207-092008', 15, 3, 'Accident', 'High', '2026-02-07 16:19:00', 'Driver', 'EDSA Quezon Ave.', 'aaa', 'Under Investigation', NULL, NULL),
(6, 'CASE-20260209-033641', 20, 3, 'Traffic Violation', 'Medium', '2026-02-09 11:36:00', 'Admin', 'EDSA Quezon Ave.', 'nag go sa red stoplight', 'Under Investigation', NULL, NULL),
(7, 'CASE-20260211-215755', 17, 3, 'Accident', 'High', '2026-02-12 04:00:00', 'Driver', 'EDSA Quezon Ave.', 'accident po', 'Under Investigation', NULL, NULL),
(8, 'CASE-20260211-222636', 0, 3, 'Speeding', 'Medium', '2026-02-12 05:26:00', 'Admin', 'EDSA Quezon Ave.', 'a', 'Under Investigation', NULL, ''),
(9, 'CASE-20260211-222949', 0, 3, 'Speeding', 'Medium', '2026-02-12 05:26:00', 'Admin', 'EDSA Quezon Ave.', 'a', 'Under Investigation', NULL, ''),
(10, 'CASE-20260211-223150', 0, 3, 'Speeding', 'Medium', '2026-02-12 05:26:00', 'Admin', 'EDSA Quezon Ave.', 'a', 'Under Investigation', NULL, ''),
(11, 'CASE-20260211-223239', 17, 3, 'Speeding', 'Medium', '2026-02-12 05:32:00', 'Admin', 'EDSA Quezon Ave.', 'a', 'Under Investigation', NULL, ''),
(12, 'CASE-20260211-232445', 20, 2, 'Accident', 'High', '2026-02-12 06:24:00', 'Admin', 'EDSA Quezon Ave.', 'a', 'Under Investigation', NULL, '1770848685_Black.jpg'),
(13, 'CASE-20260212-000650', 17, 3, 'Customer Complaint', 'Medium', '2026-02-12 07:06:00', 'Admin', 'EDSA Quezon Ave.', ';;', 'Under Investigation', NULL, '1770851210_Black.jpg'),
(14, 'CASE-20260212-000828', 17, 2, 'Traffic Violation', 'Low', '2026-02-12 07:08:00', 'Admin', 'EDSA Quezon Ave.', 'aaa', 'Under Investigation', NULL, '1770851308_Black.jpg'),
(15, 'CASE-20260212-001144', 17, 2, 'Accident', 'High', '2026-02-12 07:11:00', 'Admin', 'EDSA Quezon Ave.', 'aa', 'Under Investigation', NULL, '../uploads/incidents/1770851504_Black.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `cost` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('Scheduled','Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `priority` enum('Low','Medium','High') DEFAULT 'Low',
  `external_id` int(11) DEFAULT NULL,
  `source` enum('LOCAL','LOG1') DEFAULT 'LOCAL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id`, `vehicle_id`, `type`, `date`, `cost`, `notes`, `status`, `created_at`, `priority`, `external_id`, `source`) VALUES
(1, 3, 'Oil Change', '2026-01-23', 0.00, '', 'Completed', '2026-01-19 16:48:00', 'Low', NULL, 'LOCAL'),
(2, 2, 'Tire Replacement', '2026-01-20', 0.00, 'uhm\r\n', 'Pending', '2026-01-19 16:53:41', 'Low', NULL, 'LOCAL'),
(3, 1, 'Brake Service', '2026-02-02', 0.00, '', 'Pending', '2026-01-19 18:38:01', 'Low', NULL, 'LOCAL'),
(4, 3, 'Oil Change', '2026-02-01', 0.00, '', 'Pending', '2026-01-31 22:09:48', 'Low', NULL, 'LOCAL'),
(5, 3, 'Oil Change', '2026-02-08', 0.00, 'asasasa', 'Pending', '2026-02-08 07:31:04', 'Low', NULL, 'LOCAL'),
(6, 5, 'Engine Inspection', '2026-02-09', 0.00, 'may tumutunog', 'Pending', '2026-02-08 07:34:08', 'Low', NULL, 'LOCAL'),
(7, 3, 'Tire Replacement', '2026-02-08', 0.00, 'butas\r\n', 'Pending', '2026-02-08 08:12:42', 'High', NULL, 'LOCAL'),
(8, 3, 'Oil Change', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'Completed', '2026-02-08 14:23:10', 'Low', 3, 'LOG1'),
(9, 3, 'Tire Replacement', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'Completed', '2026-02-08 14:23:10', 'High', 2, 'LOG1'),
(10, 5, 'Engine Inspection', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'Completed', '2026-02-08 14:23:10', 'Low', 1, 'LOG1'),
(11, 1, 'Brake Service', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'In Progress', '2026-02-08 14:32:18', 'Low', 4, 'LOG1'),
(15, 3, 'Oil Change', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'Scheduled', '2026-02-08 20:26:06', 'Low', 7, 'LOG1'),
(16, 3, 'Oil Change', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'Scheduled', '2026-02-08 20:26:06', 'Low', 6, 'LOG1'),
(17, 2, 'Tire Replacement', '2026-02-08', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'In Progress', '2026-02-08 20:26:06', 'Low', 5, 'LOG1'),
(64, 5, 'Brake Service', '2026-02-09', 0.00, 'mahina brake', 'Pending', '2026-02-09 04:34:11', 'High', NULL, 'LOCAL'),
(72, 5, 'Brake Service', '2026-02-10', 0.00, 'Mechanic - Ricardo  Tavara Jr', 'Scheduled', '2026-02-09 04:55:13', 'High', 8, 'LOG1'),
(160, 3, 'Oil Change', '2026-02-11', 0.00, NULL, 'Pending', '2026-02-10 21:49:15', 'Low', NULL, 'LOCAL'),
(249, 3, 'Oil Change', '2026-02-26', 0.00, 'test', 'Pending', '2026-02-13 09:25:33', 'Low', NULL, 'LOCAL');

--
-- Triggers `maintenance`
--
DELIMITER $$
CREATE TRIGGER `maintenance_complete_vehicle` AFTER UPDATE ON `maintenance` FOR EACH ROW BEGIN
    IF NEW.status = 'Completed' THEN
        UPDATE vehicles
        SET status = 'Available'
        WHERE id = NEW.vehicle_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `maintenance_sync_vehicle` AFTER INSERT ON `maintenance` FOR EACH ROW BEGIN
    IF NEW.status IN ('Pending','In Progress','Scheduled') THEN
        UPDATE vehicles
        SET status = 'Under Maintenance',
            last_maintenance = NEW.date
        WHERE id = NEW.vehicle_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `maintenance_update_sync` AFTER UPDATE ON `maintenance` FOR EACH ROW BEGIN
    -- If maintenance becomes active
    IF NEW.status IN ('Scheduled', 'Pending', 'In Progress') THEN
        UPDATE vehicles
        SET status = 'Under Maintenance',
            last_maintenance = NEW.date
        WHERE id = NEW.vehicle_id;
    END IF;

    -- If maintenance is completed
    IF NEW.status = 'Completed' THEN
        UPDATE vehicles
        SET status = 'Available'
        WHERE id = NEW.vehicle_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_approvals`
--

CREATE TABLE `maintenance_approvals` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `cost` decimal(10,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_approvals`
--

INSERT INTO `maintenance_approvals` (`id`, `vehicle_id`, `type`, `requested_by`, `request_date`, `cost`, `status`, `rejection_reason`, `notes`) VALUES
(1, 3, 'Oil Change', 'Logistics Admin', '2026-02-09 02:18:16', 2500.00, 'Approved', NULL, 'Routine maintenance'),
(2, 2, 'Brake Repair', 'Fleet Manager', '2026-02-09 02:18:16', 4500.00, 'Pending', NULL, 'Brake noise reported');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_behavior_trends`
--

CREATE TABLE `monthly_behavior_trends` (
  `id` int(11) NOT NULL,
  `month_date` date NOT NULL,
  `total_speeding` int(11) DEFAULT 0,
  `total_harsh_braking` int(11) DEFAULT 0,
  `total_idle_time` int(11) DEFAULT 0,
  `total_trips` int(11) DEFAULT 0,
  `avg_score` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_behavior_trends`
--

INSERT INTO `monthly_behavior_trends` (`id`, `month_date`, `total_speeding`, `total_harsh_braking`, `total_idle_time`, `total_trips`, `avg_score`, `created_at`) VALUES
(1, '2026-02-01', 3, 1, 50, 22, 87.5, '2026-02-09 02:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT '#',
  `color` enum('yellow','red','blue','green') DEFAULT 'blue',
  `icon` varchar(50) DEFAULT 'fa-info-circle',
  `read_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `link`, `color`, `icon`, `read_status`, `created_at`) VALUES
(1, 'Maintenance Due', 'Vehicle ABC-1234 requires maintenance', '/maintenance', 'yellow', 'fa-wrench', 0, '2026-02-09 02:18:34'),
(2, 'Fuel Expense Pending', 'Fuel expense awaiting approval', '/fuel-expenses', 'blue', 'fa-gas-pump', 0, '2026-02-09 02:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition`
--

CREATE TABLE `purchase_requisition` (
  `id` int(11) NOT NULL,
  `req_id` varchar(50) NOT NULL,
  `requester` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `request_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requisition`
--

INSERT INTO `purchase_requisition` (`id`, `req_id`, `requester`, `department`, `request_date`, `status`, `created_at`) VALUES
(1, 'PR-2026-0001', 'Via Jeves', 'Log2 Dept', '2026-02-12', 'Pending', '2026-02-12 07:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` int(11) NOT NULL,
  `req_id` varchar(50) NOT NULL,
  `requester` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `request_date` date NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requisitions`
--

INSERT INTO `purchase_requisitions` (`id`, `req_id`, `requester`, `department`, `request_date`, `product_id`, `product_name`, `quantity`, `unit_price`, `status`, `created_at`) VALUES
(1, 'PR-2026-0001', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214ETQIC', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 1, 11000.00, 'Approved', '2026-02-14 15:09:10'),
(2, 'PR-2026-0002', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214OOI4K', 'Toyota Glanza Alloy Wheel', 1, 9000.00, 'Approved', '2026-02-14 15:09:10'),
(3, 'PR-2026-0003', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214JOY7C', 'Hyundai Creta – 16-Inch Alloy Wheels', 1, 55000.00, 'Approved', '2026-02-14 15:09:10'),
(4, 'PR-2026-0004', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214JROKA', '2018-2019 Toyota Camry NON-AFS Headlight – OEM', 1, 30000.00, 'Approved', '2026-02-14 15:09:10'),
(5, 'PR-2026-0005', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD202602141JHN0', 'Toyota Camry SE 2018-2020 Headlight – Left (Driver Side, Aftermarket)', 1, 14000.00, 'Approved', '2026-02-14 15:09:10'),
(6, 'PR-2026-0006', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214GS6LR', '2018-2023 Toyota Camry Headlight Assembly – OEM Right (Passenger Side)', 1, 35000.00, 'Rejected', '2026-02-14 15:09:10'),
(7, 'PR-2026-0007', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214FGH9V', 'SPARCO-CORSA Steering Wheel Cover (Black, Japan Style)', 1, 1200.00, 'Approved', '2026-02-14 15:09:10'),
(8, 'PR-2026-0008', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260214GC7O0', 'Toyota Red Suede Steering Booster / Wheel Cover (Non-Slip)', 1, 400.00, 'Approved', '2026-02-14 15:09:10'),
(9, 'PR-2026-0009', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD202602149UIC7', '4X 16-Inch Wheel Covers (Snap-On, R16, Steel Rim)', 1, 2000.00, 'Rejected', '2026-02-14 15:09:10'),
(10, 'PR-2026-0010', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260209SHP58', 'Toyota Fortuner', 1, 2200000.00, 'Pending', '2026-02-14 15:09:10'),
(11, 'PR-2026-0011', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260209ACB9K', 'Suzuki Carry Utility Van 1.5L', 1, 820000.00, 'Rejected', '2026-02-14 15:09:10'),
(12, 'PR-2026-0012', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260209HKZNM', 'Mitsubishi Fuso Canter Cruise (Minibus)', 1, 2600000.00, 'Approved', '2026-02-14 15:09:10'),
(13, 'PR-2026-0013', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260209WNKAJ', 'Mitsubishi Fuso Canter FE73 (6-Wheeler)', 1, 2400000.00, 'Approved', '2026-02-14 15:09:10'),
(14, 'PR-2026-0014', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD202602095J9D5', '2012 Isuzu NPR', 1, 980000.00, 'Approved', '2026-02-14 15:09:10'),
(15, 'PR-2026-0015', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD20260209G0UJU', 'Honda CB500', 1, 379000.00, 'Approved', '2026-02-14 15:09:10'),
(16, 'PR-2026-0016', 'Via Jeves', 'Log2 Dept', '2026-02-14', 'PROD202602098QOMK', 'Toyota Hiace', 1, 1379000.00, 'Approved', '2026-02-14 15:09:10'),
(17, 'PR-2026-0017', 'Admin', 'Log2 Dept', '2026-02-15', 'PROD20260214GC7O0', 'Toyota Red Suede Steering Booster / Wheel Cover (Non-Slip)', 1, 400.00, 'Pending', '2026-02-15 02:24:19'),
(18, 'PR-2026-0018', 'Admin', 'Log2 Dept', '2026-02-15', 'PROD20260214GC7O0', 'Toyota Red Suede Steering Booster / Wheel Cover (Non-Slip)', 1, 400.00, 'Pending', '2026-02-15 02:40:13');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_items`
--

CREATE TABLE `purchase_requisition_items` (
  `id` int(11) NOT NULL,
  `requisition_id` int(11) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requisition_items`
--

INSERT INTO `purchase_requisition_items` (`id`, `requisition_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 'P1001', 'Laptop', 2, 35000.00, 70000.00),
(2, 1, 'P1002', 'Mouse', 5, 500.00, 2500.00),
(3, 1, 'P1003', 'Printer Ink', 3, 1200.00, 3600.00),
(4, 1, 'PROD20260214ETQIC', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 1, 11000.00, 11000.00),
(5, 1, 'PROD20260214OOI4K', 'Toyota Glanza Alloy Wheel', 1, 9000.00, 9000.00),
(6, 1, 'PROD20260214JOY7C', 'Hyundai Creta – 16-Inch Alloy Wheels', 1, 55000.00, 55000.00),
(7, 1, 'PROD20260214JROKA', '2018-2019 Toyota Camry NON-AFS Headlight – OEM', 1, 30000.00, 30000.00),
(8, 1, 'PROD202602141JHN0', 'Toyota Camry SE 2018-2020 Headlight – Left (Driver Side, Aftermarket)', 1, 14000.00, 14000.00),
(9, 1, 'PROD20260214GS6LR', '2018-2023 Toyota Camry Headlight Assembly – OEM Right (Passenger Side)', 1, 35000.00, 35000.00),
(10, 1, 'PROD20260214FGH9V', 'SPARCO-CORSA Steering Wheel Cover (Black, Japan Style)', 1, 1200.00, 1200.00),
(11, 1, 'PROD20260214GC7O0', 'Toyota Red Suede Steering Booster / Wheel Cover (Non-Slip)', 1, 400.00, 400.00),
(12, 1, 'PROD202602149UIC7', '4X 16-Inch Wheel Covers (Snap-On, R16, Steel Rim)', 1, 2000.00, 2000.00),
(13, 1, 'PROD20260209SHP58', 'Toyota Fortuner', 1, 2200000.00, 2200000.00),
(14, 1, 'PROD20260209ACB9K', 'Suzuki Carry Utility Van 1.5L', 1, 820000.00, 820000.00),
(15, 1, 'PROD20260209HKZNM', 'Mitsubishi Fuso Canter Cruise (Minibus)', 1, 2600000.00, 2600000.00),
(16, 1, 'PROD20260209WNKAJ', 'Mitsubishi Fuso Canter FE73 (6-Wheeler)', 1, 2400000.00, 2400000.00),
(17, 1, 'PROD202602095J9D5', '2012 Isuzu NPR', 1, 980000.00, 980000.00),
(18, 1, 'PROD20260209G0UJU', 'Honda CB500', 1, 379000.00, 379000.00),
(19, 1, 'PROD202602098QOMK', 'Toyota Hiace', 1, 1379000.00, 1379000.00);

-- --------------------------------------------------------

--
-- Table structure for table `request_expenses`
--

CREATE TABLE `request_expenses` (
  `id` int(11) NOT NULL,
  `expense_type` varchar(50) NOT NULL,
  `request_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `requested_by` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `reserved_date` date DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','Approved','Completed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `vehicle_id`, `driver_id`, `reserved_date`, `purpose`, `notes`, `status`) VALUES
(12, 1, 20, '2026-02-10', 'Delivery', 'Deliver office supplies', 'Pending'),
(13, 2, 21, '2026-02-11', 'Pickup', 'Pick up client documents', 'Approved'),
(14, 3, 20, '2026-02-12', 'Maintenance', 'Take vehicle to service center', 'Completed'),
(15, 4, 21, '2026-02-13', 'Delivery', 'Transport fragile items', 'Pending'),
(16, 5, 20, '2026-02-14', 'Client Meeting', 'Drive to Makati office', 'Approved'),
(17, 6, 21, '2026-02-15', 'Inventory Transfer', 'Move items to warehouse', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `terms_acceptance`
--

CREATE TABLE `terms_acceptance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `accepted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms_acceptance`
--

INSERT INTO `terms_acceptance` (`id`, `user_id`, `accepted_at`) VALUES
(1, 17, '2026-02-09 02:18:39'),
(2, 20, '2026-02-09 02:18:39'),
(3, 17, '2026-02-09 02:18:44'),
(4, 20, '2026-02-09 02:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `transport_expenses`
--

CREATE TABLE `transport_expenses` (
  `expense_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `category` enum('Fuel','Maintenance','Repairs','Licensing','Misc') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_expenses`
--

INSERT INTO `transport_expenses` (`expense_id`, `date`, `category`, `amount`, `vehicle_id`, `driver_id`, `description`, `created_at`) VALUES
(1, '2026-01-10', 'Fuel', 1500.00, 1, NULL, 'Diesel refill for delivery truck', '2026-01-24 16:42:52'),
(2, '2026-01-12', 'Maintenance', 2500.00, 1, NULL, 'Oil change and tire check', '2026-01-24 16:42:52'),
(3, '2026-01-15', 'Repairs', 1200.00, 2, NULL, 'Brake pad replacement', '2026-01-24 16:42:52'),
(4, '2026-01-18', 'Licensing', 800.00, 3, NULL, 'Vehicle registration renewal', '2026-01-24 16:42:52'),
(5, '2026-01-20', 'Fuel', 1300.00, 2, NULL, 'Petrol refill for van', '2026-01-24 16:42:52'),
(6, '2026-01-22', 'Misc', 500.00, NULL, NULL, 'Parking fees', '2026-01-24 16:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `dispatch_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `planned_distance` decimal(10,2) DEFAULT NULL,
  `actual_distance` decimal(10,2) DEFAULT NULL,
  `planned_duration` varchar(20) DEFAULT NULL,
  `actual_duration` varchar(20) DEFAULT NULL,
  `fuel_used` decimal(10,2) DEFAULT NULL,
  `fuel_cost` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','On-Time','Delayed','Cancelled') DEFAULT 'Pending',
  `on_time_percentage` int(11) DEFAULT NULL,
  `idle_time` int(11) DEFAULT NULL,
  `route_deviation` int(11) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `trip_code` varchar(50) DEFAULT NULL,
  `start_location` varchar(100) DEFAULT NULL,
  `end_location` varchar(100) DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `proof_uploaded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `vehicle_id`, `driver_id`, `route`, `dispatch_date`, `return_date`, `purpose`, `notes`, `planned_distance`, `actual_distance`, `planned_duration`, `actual_duration`, `fuel_used`, `fuel_cost`, `status`, `on_time_percentage`, `idle_time`, `route_deviation`, `departure_time`, `arrival_time`, `trip_code`, `start_location`, `end_location`, `proof_image`, `proof_uploaded_at`) VALUES
(12, 1, 20, 'Warehouse to Office', '2026-02-10', '2026-02-10', 'Delivery', 'Handle with care', 20.00, 21.00, '1 hour', '1h 10m', 5.00, 250.00, 'On-Time', 100, 5, 0, '08:00:00', '09:10:00', 'TRIP-20260210-001', 'Warehouse', 'Office', NULL, NULL),
(13, 2, 21, 'Depot to Client Site', '2026-02-11', '2026-02-11', 'Pickup', 'Urgent delivery', 15.00, 16.00, '45 mins', '50 mins', 3.00, 150.00, 'Delayed', 80, 2, 1, '09:00:00', '09:50:00', 'TRIP-20260211-002', 'Depot', 'Client Site', NULL, NULL),
(14, 3, 20, 'Service Center Trip', '2026-02-12', '2026-02-12', 'Maintenance', 'Check brakes and tires', 10.00, 10.00, '30 mins', '30 mins', 2.00, 100.00, 'On-Time', 100, 0, 0, '10:00:00', '10:30:00', 'TRIP-20260212-003', 'Office', 'Service Center', NULL, NULL),
(15, 4, 21, 'Office to Warehouse', '2026-02-13', '2026-02-13', 'Delivery', 'Transport boxes', 25.00, 26.00, '1h 20m', '1h 25m', 6.00, 300.00, 'On-Time', 95, 3, 0, '08:30:00', '09:55:00', 'TRIP-20260213-004', 'Office', 'Warehouse', NULL, NULL),
(16, 5, 20, 'Makati Office to HQ', '2026-02-14', '2026-02-14', 'Client Meeting', 'Meet with client', 18.00, 18.50, '50 mins', '55 mins', 4.00, 200.00, 'On-Time', 100, 1, 0, '07:45:00', '08:40:00', 'TRIP-20260214-005', 'Makati Office', 'HQ', NULL, NULL),
(17, 6, 21, 'Warehouse to Depot', '2026-02-15', '2026-02-15', 'Inventory Transfer', 'Move inventory items', 22.00, 23.00, '1h 10m', '1h 15m', 5.50, 275.00, 'Pending', 0, 0, 0, '08:00:00', '09:15:00', 'TRIP-20260215-006', 'Warehouse', 'Depot', NULL, NULL),
(18, 1, 24, 'Custom Pin', '2026-02-13', NULL, NULL, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 'TRIP-20260213-907', 'HQ', 'Custom Pin', NULL, NULL),
(19, 1, 24, 'Bestlink College of the Philippines', '2026-02-13', NULL, NULL, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 'TRIP-20260213-619', 'HQ', 'Bestlink College of the Philippines', NULL, NULL),
(20, 1, 24, 'Bestlink College of the Philippines - Criminology Department', '2026-02-13', NULL, NULL, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 'TRIP-20260213-828', 'HQ', 'Bestlink College of the Philippines - Criminology Department', NULL, NULL),
(21, 1, 24, 'Bestlink College of the Philippines - Criminology Department', '2026-02-13', NULL, NULL, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 'TRIP-20260213-669', 'HQ', 'Bestlink College of the Philippines - Criminology Department', NULL, NULL),
(22, 1, 24, 'SM Mall of Asia', '2026-02-13', NULL, NULL, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 'TRIP-20260213-862', 'HQ', 'SM Mall of Asia', NULL, NULL),
(23, 1, 24, 'SM Mall of Asia', '2026-02-13', NULL, NULL, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 'TRIP-20260213-783', 'HQ', 'SM Mall of Asia', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff','Employee','Driver') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `phone_number`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(6, 'Alice Mendoza', '09170000001', 'alice.admin@logistics.com', '$2y$10$adminhash1', 'Admin', 'Active', '2026-01-31 21:13:43'),
(7, 'Robert Cruz', '09170000002', 'robert.admin@logistics.com', '$2y$10$adminhash2', 'Admin', 'Active', '2026-01-31 21:13:43'),
(10, 'Jenny Ramos', '09170000005', 'jenny.emp@logistics.com', '$2y$10$emphash1', 'Employee', 'Active', '2026-01-31 21:13:43'),
(11, 'Paul Santos', '09170000006', 'paul.emp@logistics.com', '$2y$10$emphash2', 'Employee', 'Active', '2026-01-31 21:13:43'),
(16, 'Jenny Flores', '09241234508', 'jenny.flores@microfinance.com', '$2y$10$iHNJ4rNC8P7AqpSvrFtXmeshGH0VmAatRCLl54ytwgUMjrMwrYSoO', 'Staff', 'Active', '2026-02-08 21:01:51'),
(17, 'Richard Dela Rosa', '09331234517', 'richard.delarosa@microfinance.com', '$2y$10$iHNJ4rNC8P7AqpSvrFtXmeshGH0VmAatRCLl54ytwgUMjrMwrYSoO', 'Driver', 'Active', '2026-02-08 21:01:51'),
(18, 'Hazel Perez', '09341234518', 'hazel.perez@microfinance.com', '$2y$10$iHNJ4rNC8P7AqpSvrFtXmeshGH0VmAatRCLl54ytwgUMjrMwrYSoO', 'Staff', 'Active', '2026-02-08 21:01:51'),
(19, 'Jane Doe', '09371234521', 'jane.doe@microfinance.com', '$2y$10$iHNJ4rNC8P7AqpSvrFtXmeshGH0VmAatRCLl54ytwgUMjrMwrYSoO', 'Staff', 'Active', '2026-02-08 21:01:51'),
(20, 'Ricardo  Tavara Jr', '09955465659', 'kaydotavara07@gmail.com', '$2y$10$iHNJ4rNC8P7AqpSvrFtXmeshGH0VmAatRCLl54ytwgUMjrMwrYSoO', 'Driver', 'Active', '2026-02-08 21:01:52'),
(21, 'Super Admin', '09000000000', 'admin@test.com', '$2y$10$YS3Srs57fOBzzoUy4O2ftuGbqkRxWOhjbLIZK.Fs83ErMen60kO7q', 'Admin', 'Active', '2026-02-13 07:47:32'),
(22, 'Emers', '11111111111111', 'jhon.emerwin05@gmail.com', '$2y$10$8eymWqRoNkNNg.Tks5w0QOCBf6RV054vFjCX/RKST9ys0DlK6caHW', 'Driver', 'Active', '2026-02-13 13:35:42'),
(23, 'Testers', 'www', 'jhon@gmail.com', '$2y$10$eDAYk4SvlXRx9TE9uUx/pO7iK4SaQAwV4xC4JkuYvA2j.VlE.gZ1G', 'Driver', 'Active', '2026-02-13 17:31:55'),
(24, 'Trishia Del Norte', '09267516157', 'trishiakayed@gmail.com', '$2y$10$bmPYgGdT3OBBcjWu57jhGOtJCbogCZIPoqh8MCDV.eUBB7H90Ayuu', 'Driver', 'Active', '2026-02-14 06:52:29'),
(25, 'Agrifino Dacles', '09816611115', 'agrifinodacles@gmail.com', '$2y$10$UF.YD6X9WdCmrHYCTwSdDuGQN8crTeUzWegI7/EG5.wnHvi5QwT3u', 'Driver', 'Active', '2026-02-14 23:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `plate` varchar(20) NOT NULL,
  `vehicle` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `status` enum('Available','On Trip','Reserved','Under Maintenance','Inactive') DEFAULT 'Available',
  `last_maintenance` date DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate`, `vehicle`, `model`, `type`, `year`, `status`, `last_maintenance`, `lat`, `lng`) VALUES
(1, 'ABC-1234', 'Toyota Hiace', 'Van 2022', 'Van', 2022, 'Available', '2026-02-02', NULL, NULL),
(2, 'ISZ-2021', 'Isuzu NPR', 'Truck 2021', 'Truck', 2021, 'On Trip', '2026-01-20', NULL, NULL),
(3, 'HON-500', 'Honda CB500', 'Motorcycle 2023', 'Motorcycle', 2023, 'Under Maintenance', '2026-02-26', NULL, NULL),
(4, 'MIF-2020', 'Mitsubishi Fuso', 'Truck 2020', 'Truck', 2020, 'Reserved', '2024-06-10', NULL, NULL),
(5, 'TOY-2019', 'Toyota Fortuner', 'Car 2019', 'Car', 2019, 'Available', '2026-02-09', NULL, NULL),
(6, 'SUZ-2022', 'Suzuki Carry', 'Van 2022', 'Van', 2022, 'Under Maintenance', '2024-08-25', NULL, NULL),
(7, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL),
(8, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL),
(9, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL),
(10, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL),
(11, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL),
(12, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL),
(13, 'TEMP-001', NULL, 'Generic Truck', 'Truck', NULL, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_inspections`
--

CREATE TABLE `vehicle_inspections` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `type` enum('Pre-Trip','Post-Trip') NOT NULL,
  `battery_status` enum('Pass','Fail') DEFAULT 'Pass',
  `lights_status` enum('Pass','Fail') DEFAULT 'Pass',
  `oil_status` enum('Pass','Fail') DEFAULT 'Pass',
  `water_status` enum('Pass','Fail') DEFAULT 'Pass',
  `brakes_status` enum('Pass','Fail') DEFAULT 'Pass',
  `air_status` enum('Pass','Fail') DEFAULT 'Pass',
  `gas_status` enum('Pass','Fail') DEFAULT 'Pass',
  `engine_status` enum('Pass','Fail') DEFAULT 'Pass',
  `tire_status` enum('Pass','Fail') DEFAULT 'Pass',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vehicle_live_status`
-- (See below for the actual view)
--
CREATE TABLE `vehicle_live_status` (
`id` int(11)
,`plate` varchar(20)
,`vehicle` varchar(50)
,`vehicle_condition` enum('Available','On Trip','Reserved','Under Maintenance','Inactive')
,`live_availability` varchar(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_requests`
--

CREATE TABLE `vehicle_requests` (
  `id` int(11) NOT NULL,
  `plate` varchar(20) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Synced') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `vehicle_live_status`
--
DROP TABLE IF EXISTS `vehicle_live_status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`logJHGmicro`@`localhost` SQL SECURITY DEFINER VIEW `vehicle_live_status`  AS SELECT `v`.`id` AS `id`, `v`.`plate` AS `plate`, `v`.`vehicle` AS `vehicle`, `v`.`status` AS `vehicle_condition`, CASE WHEN exists(select 1 from `maintenance` `m` where `m`.`vehicle_id` = `v`.`id` AND `m`.`status` in ('Scheduled','Pending','In Progress') limit 1) THEN 'Maintenance' WHEN exists(select 1 from `trips` `t` where `t`.`vehicle_id` = `v`.`id` AND `t`.`return_date` is null AND `t`.`status` <> 'Cancelled' limit 1) THEN 'On Trip' WHEN exists(select 1 from `reservations` `r` where `r`.`vehicle_id` = `v`.`id` AND `r`.`status` in ('Pending','Approved') limit 1) THEN 'Reserved' ELSE 'Available' END AS `live_availability` FROM `vehicles` AS `v` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `behavior_incidents`
--
ALTER TABLE `behavior_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `compliance_documents`
--
ALTER TABLE `compliance_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `dispatches`
--
ALTER TABLE `dispatches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_unique` (`license`),
  ADD KEY `fk_drivers_users` (`user_id`);

--
-- Indexes for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_requests`
--
ALTER TABLE `expense_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `incident_cases`
--
ALTER TABLE `incident_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_incident_vehicle` (`vehicle_id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD UNIQUE KEY `uq_external_id` (`external_id`),
  ADD KEY `fk_maintenance_vehicle` (`vehicle_id`);

--
-- Indexes for table `maintenance_approvals`
--
ALTER TABLE `maintenance_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `monthly_behavior_trends`
--
ALTER TABLE `monthly_behavior_trends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requisition`
--
ALTER TABLE `purchase_requisition`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `req_id` (`req_id`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `req_id` (`req_id`);

--
-- Indexes for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisition_id` (`requisition_id`);

--
-- Indexes for table `request_expenses`
--
ALTER TABLE `request_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `terms_acceptance`
--
ALTER TABLE `terms_acceptance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_terms_user` (`user_id`);

--
-- Indexes for table `transport_expenses`
--
ALTER TABLE `transport_expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trip_code` (`trip_code`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `fk_driver` (`driver_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_inspections`
--
ALTER TABLE `vehicle_inspections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_requests`
--
ALTER TABLE `vehicle_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `behavior_incidents`
--
ALTER TABLE `behavior_incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `compliance_documents`
--
ALTER TABLE `compliance_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dispatches`
--
ALTER TABLE `dispatches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expense_requests`
--
ALTER TABLE `expense_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `incident_cases`
--
ALTER TABLE `incident_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=466;

--
-- AUTO_INCREMENT for table `maintenance_approvals`
--
ALTER TABLE `maintenance_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `monthly_behavior_trends`
--
ALTER TABLE `monthly_behavior_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_requisition`
--
ALTER TABLE `purchase_requisition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `request_expenses`
--
ALTER TABLE `request_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `terms_acceptance`
--
ALTER TABLE `terms_acceptance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transport_expenses`
--
ALTER TABLE `transport_expenses`
  MODIFY `expense_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vehicle_inspections`
--
ALTER TABLE `vehicle_inspections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_requests`
--
ALTER TABLE `vehicle_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `behavior_incidents`
--
ALTER TABLE `behavior_incidents`
  ADD CONSTRAINT `behavior_incidents_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_behavior_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certifications`
--
ALTER TABLE `certifications`
  ADD CONSTRAINT `certifications_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `compliance_documents`
--
ALTER TABLE `compliance_documents`
  ADD CONSTRAINT `compliance_documents_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_driver_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_drivers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incident_cases`
--
ALTER TABLE `incident_cases`
  ADD CONSTRAINT `fk_incident_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `fk_maintenance_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_approvals`
--
ALTER TABLE `maintenance_approvals`
  ADD CONSTRAINT `maintenance_approvals_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD CONSTRAINT `purchase_requisition_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisition` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`);

--
-- Constraints for table `terms_acceptance`
--
ALTER TABLE `terms_acceptance`
  ADD CONSTRAINT `fk_terms_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transport_expenses`
--
ALTER TABLE `transport_expenses`
  ADD CONSTRAINT `transport_expenses_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_expenses_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `fk_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
