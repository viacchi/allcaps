-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2026 at 01:28 AM
-- Server version: 10.4.32-MariaDB
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `department`, `budget_year`, `total_amount`, `used_amount`, `created_at`, `updated_at`, `remaining_amount`) VALUES
(1, 'Logistic 2', '2026', 500000.00, 0.00, '2026-02-11 15:18:20', '2026-02-13 00:04:34', 500000.00);

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
(1, 1, 'Registration', '2025-01-01', '2026-01-01', 'REG-001', 'uploads/documents/reg1.pdf', 'Vehicle registration document', '2026-01-25 05:20:14'),
(2, 1, 'Insurance', '2025-02-01', '2026-02-01', 'INS-001', 'uploads/documents/ins1.pdf', 'Comprehensive insurance', '2026-01-25 05:20:14'),
(3, 2, 'Registration', '2025-03-01', '2026-03-01', 'REG-002', 'uploads/documents/reg2.pdf', 'Vehicle registration document', '2026-01-25 05:20:14'),
(4, 2, 'Insurance', '2025-03-15', '2026-03-15', 'INS-002', 'uploads/documents/ins2.pdf', 'Third-party insurance', '2026-01-25 05:20:14'),
(5, 3, 'Registration', '2025-01-20', '2026-01-20', 'REG-003', 'uploads/documents/reg3.pdf', 'Vehicle registration document', '2026-01-25 05:20:14'),
(6, 3, 'Insurance', '2025-02-10', '2026-02-10', 'INS-003', 'uploads/documents/ins3.pdf', 'Comprehensive insurance', '2026-01-25 05:20:14'),
(7, 3, 'Emission Test', '2025-03-05', '2026-03-05', 'EMI-001', 'uploads/documents/emi1.pdf', 'Emission compliance test', '2026-01-25 05:20:14'),
(8, 1, 'Registration', '2026-02-06', '2050-02-06', NULL, NULL, NULL, '2026-02-05 22:05:23'),
(9, 1, 'Registration', '2026-02-12', '2030-02-12', NULL, '../uploads/1770842273_Black.jpg', NULL, '2026-02-11 20:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
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
  `incidents` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `user_id`, `full_name`, `license`, `status`, `address`, `emergency_contact`, `blood_type`, `join_date`, `expiry`, `rating`, `safety_score`, `on_time_rate`, `total_trips`, `created_at`, `updated_at`, `total_distance`, `incidents`) VALUES
(20, 17, 'Richard Dela Rosa', NULL, 'Active', NULL, NULL, NULL, NULL, '2027-02-09', 4.50, 95.00, 90.00, 12, '2026-02-08 23:16:47', '2026-02-09 02:26:21', 1200.50, 1),
(21, 20, 'Ricardo  Tavara Jr', NULL, 'Active', NULL, NULL, NULL, NULL, '2027-02-09', 4.30, 92.00, 88.00, 10, '2026-02-08 23:16:47', '2026-02-09 02:26:21', 980.00, 0);

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
  `expense_type` enum('Fuel','Maintenance','Repair','Parts','Other') NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT 'Logistic 2',
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `liters` decimal(10,2) DEFAULT NULL,
  `fuel_type` varchar(20) DEFAULT NULL,
  `request_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `budget_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_requests`
--

INSERT INTO `expense_requests` (`id`, `expense_type`, `requested_by`, `department`, `vehicle_id`, `driver_id`, `liters`, `fuel_type`, `request_date`, `amount`, `description`, `contact`, `receipt_path`, `status`, `approved_by`, `approved_date`, `budget_id`, `created_at`) VALUES
(1, 'Fuel', 'Richard Dela Rosa', 'Logistic 2', 1, 20, 50.00, 'Diesel', '2026-02-11', 3500.00, 'Fuel refill for delivery trip', '09123456789', NULL, 'Pending', NULL, NULL, 1, '2026-02-11 15:18:34');

--
-- Triggers `expense_requests`
--
DELIMITER $$
CREATE TRIGGER `trg_after_expense_approved` AFTER UPDATE ON `expense_requests` FOR EACH ROW BEGIN

    IF NEW.status = 'Approved' 
       AND OLD.status != 'Approved'
       AND NEW.expense_type = 'Fuel' THEN

        -- Deduct from budget
        UPDATE logistics_budgets
        SET used_amount = used_amount + NEW.amount
        WHERE id = NEW.budget_id;

        -- Insert into fuel_expenses
        INSERT INTO fuel_expenses (
            request_id,
            vehicle_id,
            driver_id,
            liters,
            cost,
            fuel_type,
            receipt_path,
            date,
            status
        )
        VALUES (
            NEW.id,
            NEW.vehicle_id,
            NEW.approved_by,
            NEW.liters,
            NEW.amount,
            NEW.fuel_type,
            NEW.receipt_path,
            NEW.request_date,
            'Approved'
        );

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_expenses`
--

CREATE TABLE `fuel_expenses` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `liters` decimal(10,2) NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `fuel_type` varchar(20) DEFAULT NULL,
  `gas_station` varchar(100) DEFAULT NULL,
  `receipt_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('Approved','Pending','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `fuel_expenses`
--
DELIMITER $$
CREATE TRIGGER `check_budget_before_fuel_insert` BEFORE INSERT ON `fuel_expenses` FOR EACH ROW BEGIN
    DECLARE remaining DECIMAL(15,2);

    SELECT b.remaining_amount INTO remaining
    FROM budgets b
    JOIN expense_requests er ON er.budget_id = b.id
    WHERE er.id = NEW.request_id;

    IF remaining < NEW.cost THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Insufficient budget balance';
    END IF;
END
$$
DELIMITER ;

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

INSERT INTO `maintenance` (`id`, `vehicle_id`, `type`, `date`, `notes`, `status`, `created_at`, `priority`, `external_id`, `source`) VALUES
(1, 3, 'Oil Change', '2026-01-23', '', 'Completed', '2026-01-19 16:48:00', 'Low', NULL, 'LOCAL'),
(2, 2, 'Tire Replacement', '2026-01-20', 'uhm\r\n', 'Pending', '2026-01-19 16:53:41', 'Low', NULL, 'LOCAL'),
(3, 1, 'Brake Service', '2026-02-02', '', 'Pending', '2026-01-19 18:38:01', 'Low', NULL, 'LOCAL'),
(4, 3, 'Oil Change', '2026-02-01', '', 'Pending', '2026-01-31 22:09:48', 'Low', NULL, 'LOCAL'),
(5, 3, 'Oil Change', '2026-02-08', 'asasasa', 'Pending', '2026-02-08 07:31:04', 'Low', NULL, 'LOCAL'),
(6, 5, 'Engine Inspection', '2026-02-09', 'may tumutunog', 'Pending', '2026-02-08 07:34:08', 'Low', NULL, 'LOCAL'),
(7, 3, 'Tire Replacement', '2026-02-08', 'butas\r\n', 'Pending', '2026-02-08 08:12:42', 'High', NULL, 'LOCAL'),
(8, 3, 'Oil Change', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'Completed', '2026-02-08 14:23:10', 'Low', 3, 'LOG1'),
(9, 3, 'Tire Replacement', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'Completed', '2026-02-08 14:23:10', 'High', 2, 'LOG1'),
(10, 5, 'Engine Inspection', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'Completed', '2026-02-08 14:23:10', 'Low', 1, 'LOG1'),
(11, 1, 'Brake Service', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'In Progress', '2026-02-08 14:32:18', 'Low', 4, 'LOG1'),
(15, 3, 'Oil Change', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'Scheduled', '2026-02-08 20:26:06', 'Low', 7, 'LOG1'),
(16, 3, 'Oil Change', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'Scheduled', '2026-02-08 20:26:06', 'Low', 6, 'LOG1'),
(17, 2, 'Tire Replacement', '2026-02-08', 'Mechanic - Ricardo  Tavara Jr', 'In Progress', '2026-02-08 20:26:06', 'Low', 5, 'LOG1'),
(64, 5, 'Brake Service', '2026-02-09', 'mahina brake', 'Pending', '2026-02-09 04:34:11', 'High', NULL, 'LOCAL'),
(72, 5, 'Brake Service', '2026-02-10', 'Mechanic - Ricardo  Tavara Jr', 'Scheduled', '2026-02-09 04:55:13', 'High', 8, 'LOG1'),
(160, 3, 'Oil Change', '2026-02-11', NULL, 'Pending', '2026-02-10 21:49:15', 'Low', NULL, 'LOCAL');

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
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` int(11) NOT NULL,
  `req_id` varchar(50) NOT NULL,
  `requester` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `request_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requisitions`
--

INSERT INTO `purchase_requisitions` (`id`, `req_id`, `requester`, `department`, `request_date`, `status`, `created_at`) VALUES
(1, 'PR-2026-0001', 'Via Jeves', 'Log2 Dept', '2026-02-12', 'Pending', '2026-02-12 07:12:59');

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
(3, 1, 'P1003', 'Printer Ink', 3, 1200.00, 3600.00);

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
(6, '2026-01-22', 'Misc', 500.00, NULL, NULL, 'Parking fees', '2026-01-24 16:42:52'),
(7, '2026-02-01', 'Fuel', 1800.00, 1, 20, 'Diesel refill for delivery truck', '2026-02-12 21:50:04'),
(8, '2026-02-03', 'Maintenance', 2200.00, 2, 21, 'Oil change and tire check', '2026-02-12 21:50:04'),
(9, '2026-02-05', 'Repairs', 1500.00, 3, 20, 'Brake pad replacement', '2026-02-12 21:50:04'),
(10, '2026-02-07', 'Licensing', 900.00, 4, 21, 'Vehicle registration renewal', '2026-02-12 21:50:04'),
(11, '2026-02-09', 'Fuel', 1700.00, 2, 21, 'Petrol refill for van', '2026-02-12 21:50:04'),
(12, '2026-02-10', 'Misc', 600.00, NULL, NULL, 'Parking fees for deliveries', '2026-02-12 21:50:04');

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
(17, 6, 21, 'Warehouse to Depot', '2026-02-15', '2026-02-15', 'Inventory Transfer', 'Move inventory items', 22.00, 23.00, '1h 10m', '1h 15m', 5.50, 275.00, 'Pending', 0, 0, 0, '08:00:00', '09:15:00', 'TRIP-20260215-006', 'Warehouse', 'Depot', NULL, NULL);

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
(20, 'Ricardo  Tavara Jr', '09955465659', 'kaydotavara07@gmail.com', '$2y$10$iHNJ4rNC8P7AqpSvrFtXmeshGH0VmAatRCLl54ytwgUMjrMwrYSoO', 'Driver', 'Active', '2026-02-08 21:01:52');

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
  `last_maintenance` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate`, `vehicle`, `model`, `type`, `year`, `status`, `last_maintenance`) VALUES
(1, 'ABC-1234', 'Toyota Hiace', 'Van 2022', 'Van', 2022, 'Available', '2026-02-02'),
(2, 'ISZ-2021', 'Isuzu NPR', 'Truck 2021', 'Truck', 2021, 'On Trip', '2026-01-20'),
(3, 'HON-500', 'Honda CB500', 'Motorcycle 2023', 'Motorcycle', 2023, 'Available', '2026-01-23'),
(4, 'MIF-2020', 'Mitsubishi Fuso', 'Truck 2020', 'Truck', 2020, 'Reserved', '2024-06-10'),
(5, 'TOY-2019', 'Toyota Fortuner', 'Car 2019', 'Car', 2019, 'Available', '2026-02-09'),
(6, 'SUZ-2022', 'Suzuki Carry', 'Van 2022', 'Van', 2022, 'Under Maintenance', '2024-08-25');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_costs`
--

CREATE TABLE `vehicle_costs` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `month_year` date NOT NULL,
  `fuel_cost` decimal(15,2) DEFAULT 0.00,
  `maintenance_cost` decimal(15,2) DEFAULT 0.00,
  `repair_cost` decimal(15,2) DEFAULT 0.00,
  `total_cost` decimal(15,2) GENERATED ALWAYS AS (`fuel_cost` + `maintenance_cost` + `repair_cost`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_costs`
--

INSERT INTO `vehicle_costs` (`id`, `vehicle_id`, `month_year`, `fuel_cost`, `maintenance_cost`, `repair_cost`, `created_at`) VALUES
(1, 1, '2026-02-01', 3500.00, 2200.00, 0.00, '2026-02-12 21:50:39'),
(2, 2, '2026-02-01', 2800.00, 2500.00, 1500.00, '2026-02-12 21:50:39'),
(3, 3, '2026-02-01', 2450.00, 1800.00, 1200.00, '2026-02-12 21:50:39'),
(4, 4, '2026-02-01', 3150.00, 0.00, 900.00, '2026-02-12 21:50:39'),
(5, 5, '2026-02-01', 2000.00, 1000.00, 500.00, '2026-02-12 21:50:39');

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vehicle_live_status`  AS SELECT `v`.`id` AS `id`, `v`.`plate` AS `plate`, `v`.`vehicle` AS `vehicle`, `v`.`status` AS `vehicle_condition`, CASE WHEN exists(select 1 from `maintenance` `m` where `m`.`vehicle_id` = `v`.`id` AND `m`.`status` in ('Scheduled','Pending','In Progress') limit 1) THEN 'Maintenance' WHEN exists(select 1 from `trips` `t` where `t`.`vehicle_id` = `v`.`id` AND `t`.`return_date` is null AND `t`.`status` <> 'Cancelled' limit 1) THEN 'On Trip' WHEN exists(select 1 from `reservations` `r` where `r`.`vehicle_id` = `v`.`id` AND `r`.`status` in ('Pending','Approved') limit 1) THEN 'Reserved' ELSE 'Available' END AS `live_availability` FROM `vehicles` AS `v` ;

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `fk_expense_budget` (`budget_id`),
  ADD KEY `fk_expense_driver` (`driver_id`);

--
-- Indexes for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `fk_fuel_request` (`request_id`);

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
-- Indexes for table `vehicle_costs`
--
ALTER TABLE `vehicle_costs`
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
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expense_requests`
--
ALTER TABLE `expense_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `incident_cases`
--
ALTER TABLE `incident_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

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
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `expense_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicle_costs`
--
ALTER TABLE `vehicle_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `expense_requests`
--
ALTER TABLE `expense_requests`
  ADD CONSTRAINT `expense_requests_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `expense_requests_ibfk_2` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`),
  ADD CONSTRAINT `fk_expense_budget` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_expense_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  ADD CONSTRAINT `fk_fuel_request` FOREIGN KEY (`request_id`) REFERENCES `expense_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_expenses_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `expense_requests` (`id`),
  ADD CONSTRAINT `fuel_expenses_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `fuel_expenses_ibfk_3` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`user_id`);

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
  ADD CONSTRAINT `purchase_requisition_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE;

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
