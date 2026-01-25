-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2026 at 08:43 AM
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
-- Database: `logistics2`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `approver` varchar(50) DEFAULT NULL,
  `status` enum('Approved','Pending','Rejected') DEFAULT 'Pending',
  `date_approved` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`id`, `trip_id`, `approver`, `status`, `date_approved`, `remarks`, `approver_id`) VALUES
(1, 1, 'Admin A', 'Approved', '2026-01-16', 'All clear', NULL),
(2, 2, 'Admin B', 'Pending', NULL, 'Waiting for documentation', NULL);

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
(7, 3, 'Emission Test', '2025-03-05', '2026-03-05', 'EMI-001', 'uploads/documents/emi1.pdf', 'Emission compliance test', '2026-01-25 05:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `license` varchar(30) DEFAULT NULL,
  `status` enum('Active','On Leave','Inactive') DEFAULT 'Active',
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT 0.0,
  `safety_score` int(11) DEFAULT 0,
  `on_time_rate` int(11) DEFAULT 0,
  `total_trips` int(11) DEFAULT 0,
  `total_distance` decimal(10,2) DEFAULT 0.00,
  `incidents` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `license`, `status`, `contact`, `email`, `address`, `emergency_contact`, `blood_type`, `join_date`, `expiry`, `rating`, `safety_score`, `on_time_rate`, `total_trips`, `total_distance`, `incidents`, `created_at`, `updated_at`) VALUES
(8, 'Juan Dela Cruz', 'PH01-2023-001', 'Active', '09171234567', 'juan.delacruz@example.com', '123 Mabini St, Manila', 'Jose Dela Cruz - 09179876543', 'O+', '2023-01-10', '2025-01-10', 4.8, 97, 95, 150, 2000.50, 0, '2026-01-24 19:37:33', '2026-01-24 19:37:33'),
(9, 'Maria Santos', 'PH01-2023-002', 'On Leave', '09172345678', 'maria.santos@example.com', '45 Rizal Ave, Quezon City', 'Ana Santos - 09178889999', 'A+', '2022-03-15', '2024-03-15', 4.6, 92, 90, 130, 1700.25, 1, '2026-01-24 19:37:33', '2026-01-24 19:37:33'),
(10, 'Jose Rizal', 'PH01-2023-003', 'Active', '09173456789', 'jose.rizal@example.com', '12 Bonifacio St, Makati', 'Paciano Rizal - 09175553322', 'B+', '2021-07-20', '2023-07-20', 4.9, 99, 97, 180, 2200.75, 0, '2026-01-24 19:37:33', '2026-01-24 19:37:33'),
(11, 'Ana Magtanggol', 'PH01-2023-004', 'Inactive', '09174567890', 'ana.magtanggol@example.com', '78 Mabuhay St, Pasig', 'Luis Magtanggol - 09176664455', 'AB+', '2020-11-05', '2022-11-05', 4.2, 88, 85, 100, 1200.00, 2, '2026-01-24 19:37:33', '2026-01-24 19:37:33'),
(12, 'Pedro Penduko', 'PH01-2023-005', 'Active', '09175678901', 'pedro.penduko@example.com', '33 Katipunan Ave, Manila', 'Lola Rosa - 09177778888', 'O-', '2023-05-12', '2025-05-12', 4.7, 95, 93, 140, 1800.40, 0, '2026-01-24 19:37:33', '2026-01-24 19:37:33');

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

-- --------------------------------------------------------

--
-- Table structure for table `driver_behavior_incidents`
--

CREATE TABLE `driver_behavior_incidents` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `type` enum('Speeding','Harsh Braking','Excessive Idle') NOT NULL,
  `severity` enum('Low','Medium','High') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `incident_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_behavior_summary`
--

CREATE TABLE `driver_behavior_summary` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `month` year(4) NOT NULL,
  `speeding` int(11) DEFAULT 0,
  `harsh_braking` int(11) DEFAULT 0,
  `idle_time` int(11) DEFAULT 0,
  `trips` int(11) DEFAULT 0,
  `score` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_behavior_trends`
--

CREATE TABLE `driver_behavior_trends` (
  `id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `speeding` int(11) DEFAULT 0,
  `harsh_braking` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `driver_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuel_expenses`
--

INSERT INTO `fuel_expenses` (`id`, `vehicle_id`, `date`, `liters`, `cost`, `driver_id`, `created_at`) VALUES
(0, 0, '2026-01-25', 5.00, 250.00, 10, '2026-01-24 19:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `severity` enum('Low','Medium','High') DEFAULT 'Low',
  `incident_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_attachments`
--

CREATE TABLE `incident_attachments` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_cases`
--

CREATE TABLE `incident_cases` (
  `id` int(11) NOT NULL,
  `case_number` varchar(50) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `severity` enum('Low','Medium','High') DEFAULT 'Low',
  `date` datetime DEFAULT NULL,
  `reported_by` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Under Investigation','Pending Review','Resolved','Closed') DEFAULT 'Under Investigation',
  `resolution_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_cases`
--

INSERT INTO `incident_cases` (`id`, `case_number`, `driver_id`, `vehicle_id`, `type`, `severity`, `date`, `reported_by`, `location`, `description`, `status`, `resolution_notes`) VALUES
(1, 'CASE-20260124-143959', 5, 0, 'Traffic Violation', 'Medium', '2026-01-24 21:39:00', 'Driver', 'EDSA Quezon Ave.', 'hhh', 'Closed', 'hhhh'),
(2, 'CASE-20260124-144004', 5, 0, 'Traffic Violation', 'Medium', '2026-01-24 21:39:00', 'Driver', 'EDSA Quezon Ave.', 'hhh', 'Closed', 'waaa');

-- --------------------------------------------------------

--
-- Table structure for table `incident_comments`
--

CREATE TABLE `incident_comments` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `commented_by` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id`, `vehicle_id`, `type`, `date`, `cost`, `notes`, `status`, `created_at`) VALUES
(1, 3, 'Oil Change', '2026-01-23', 1200.00, '', 'Completed', '2026-01-19 16:48:00'),
(2, 2, 'Tire Replacement', '2026-01-20', 3300.00, 'uhm\r\n', 'Completed', '2026-01-19 16:53:41'),
(3, 1, 'Brake Service', '2026-02-02', 1250.00, '', 'Completed', '2026-01-19 18:38:01');

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

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_records`
--

CREATE TABLE `maintenance_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_records`
--

INSERT INTO `maintenance_records` (`id`, `vehicle_id`, `type`, `date`, `cost`, `status`) VALUES
(1, 1, 'Oil Change', '2024-10-15', 2500.00, 'Completed'),
(2, 2, 'Tire Replacement', '2024-10-18', 8000.00, 'Completed'),
(3, 3, 'Engine Inspection', '2024-10-20', 5000.00, 'Completed'),
(4, 4, 'Brake Service', '2024-10-12', 6500.00, 'Completed');

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
(7, 1, 8, '2026-01-25', 'Delivery', 'Deliver documents to Quezon City', 'Pending'),
(8, 2, 9, '2026-01-26', 'Pickup', 'Pickup items from Manila', 'Pending'),
(9, 3, 10, '2026-01-27', 'Client Meeting', 'Transport client from airport to office', 'Pending'),
(10, 4, 11, '2026-01-28', 'Transport', 'Deliver small parcel to Makati', 'Pending'),
(11, 5, 12, '2026-01-29', 'Urgent Delivery', 'Deliver urgent package to Ortigas', 'Pending');

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
  `end_location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `vehicle_id`, `driver_id`, `route`, `dispatch_date`, `return_date`, `purpose`, `notes`, `planned_distance`, `actual_distance`, `planned_duration`, `actual_duration`, `fuel_used`, `fuel_cost`, `status`, `on_time_percentage`, `idle_time`, `route_deviation`, `departure_time`, `arrival_time`, `trip_code`, `start_location`, `end_location`) VALUES
(1, 1, NULL, 'Manila - Quezon City', '2026-01-17', NULL, 'Delivery', 'Fragile cargo', 45.00, 47.00, '2.5 hours', '2.8 hours', 12.50, 625.00, 'On-Time', 95, 15, 2, '08:00:00', '10:48:00', NULL, NULL, NULL),
(2, 2, NULL, 'Makati - Pasig', '2026-01-16', '2026-01-17', 'Service Call', 'Urgent maintenance', 32.00, 35.00, '1.5 hours', '2.2 hours', 18.30, 915.00, 'Delayed', 68, 35, 3, '09:00:00', '11:13:00', NULL, NULL, NULL),
(3, 3, NULL, 'Taguig - Manila', '2026-01-17', NULL, 'Pickup', '', 20.00, 22.00, '1 hour', '1.2 hours', 5.00, 250.00, 'Pending', 0, 0, 0, '07:30:00', '08:50:00', NULL, NULL, NULL),
(7, 1, 8, 'Warehouse to City Center', '2026-01-25', '2026-01-25', 'Delivery', 'Handle with care', 25.50, 26.00, '01:30', '01:35', 3.20, 200.50, 'On-Time', 100, 5, 0, '08:00:00', '09:35:00', 'TRIP-20260125-001', 'Warehouse', 'City Center'),
(8, 2, 9, 'Depot to Main Office', '2026-01-24', '2026-01-24', 'Pickup', 'Fragile items', 15.00, 15.50, '01:00', '01:05', 2.00, 120.00, 'Delayed', 80, 3, 0, '09:00:00', '10:05:00', 'TRIP-20260124-002', 'Depot', 'Main Office'),
(9, 3, 10, 'Factory to Port', '2026-01-23', '2026-01-23', 'Shipping', 'Urgent', 50.00, 52.00, '02:30', '02:40', 6.50, 400.00, 'On-Time', 100, 10, 2, '07:00:00', '09:40:00', 'TRIP-20260123-003', 'Factory', 'Port'),
(10, 4, 11, 'Office to Supplier', '2026-01-22', '2026-01-22', 'Materials Pickup', 'Check goods', 30.00, 31.00, '01:45', '01:50', 4.00, 250.00, 'Cancelled', 0, 0, 0, '10:00:00', '11:50:00', 'TRIP-20260122-004', 'Office', 'Supplier'),
(11, 5, 12, 'Headquarters to Warehouse', '2026-01-21', '2026-01-21', 'Inventory Transfer', 'No issues', 20.00, 20.50, '01:15', '01:20', 2.50, 150.00, 'On-Time', 100, 2, 0, '08:30:00', '09:50:00', 'TRIP-20260121-005', 'Headquarters', 'Warehouse');

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
  `role` enum('Admin','Staff','Employee','Driver') DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `phone_number`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Via Jeves', '09123456789', 'via@gmail.com', 'pass123', 'Admin', 'Active', '2026-01-17 12:24:00'),
(2, 'Trishia Del Norte', '09234567891', 'trishia@gmail.com', 'pass123', 'Staff', 'Active', '2026-01-17 12:24:00'),
(3, 'Admin', '09170000001', 'admin@gmail.com', 'pass123', 'Admin', 'Active', '2026-01-25 03:41:03'),
(4, 'Staff User', '09170000002', 'staff@system.com', '$2y$10$staffhashedpassword', 'Staff', 'Active', '2026-01-25 03:41:19'),
(5, 'Employee User', '09170000003', 'employee@system.com', '$2y$10$employeehashedpassword', 'Employee', 'Active', '2026-01-25 03:41:34');

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
  `status` enum('Active','Inactive','Maintenance') DEFAULT 'Active',
  `availability` enum('Available','Assigned','Maintenance') DEFAULT 'Available',
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `last_maintenance` date DEFAULT NULL,
  `location` point DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate`, `vehicle`, `model`, `type`, `year`, `status`, `availability`, `lat`, `lng`, `last_maintenance`, `location`) VALUES
(0, 'TEJ-470', NULL, 'Toyota Corolla', 'Car', 2017, 'Active', 'Available', NULL, NULL, '2025-06-18', NULL),
(1, 'ABC-1234', 'Toyota Hiace', 'Van 2022', 'Van', 2022, 'Active', 'Available', 14.61, 120.982, '2024-08-15', NULL),
(2, 'ISZ-2021', 'Isuzu NPR', 'Truck 2021', 'Truck', 2021, 'Active', 'Assigned', 14.62, 120.99, '2024-09-01', NULL),
(3, 'HON-500', 'Honda CB500', 'Motorcycle 2023', 'Motorcycle', 2023, 'Inactive', 'Available', 14.605, 120.985, '2024-07-20', NULL),
(4, 'MIF-2020', 'Mitsubishi Fuso', 'Truck 2020', 'Truck', 2020, 'Maintenance', 'Maintenance', 14.615, 120.978, '2024-06-10', NULL),
(5, 'TOY-2019', 'Toyota Fortuner', 'Car 2019', 'Car', 2019, 'Inactive', 'Available', 14.6, 120.99, '2024-05-15', NULL),
(6, 'SUZ-2022', 'Suzuki Carry', 'Van 2022', 'Van', 2022, 'Active', 'Available', 14.625, 120.995, '2024-08-25', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `fk_approver` (`approver_id`);

--
-- Indexes for table `behavior_incidents`
--
ALTER TABLE `behavior_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `license_unique` (`license`);

--
-- Indexes for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_behavior_incidents`
--
ALTER TABLE `driver_behavior_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `driver_behavior_summary`
--
ALTER TABLE `driver_behavior_summary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `driver_behavior_trends`
--
ALTER TABLE `driver_behavior_trends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `incident_attachments`
--
ALTER TABLE `incident_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_id` (`incident_id`);

--
-- Indexes for table `incident_cases`
--
ALTER TABLE `incident_cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incident_comments`
--
ALTER TABLE `incident_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_id` (`incident_id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_maintenance_vehicle` (`vehicle_id`);

--
-- Indexes for table `maintenance_approvals`
--
ALTER TABLE `maintenance_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `maintenance_records`
--
ALTER TABLE `maintenance_records`
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
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `behavior_incidents`
--
ALTER TABLE `behavior_incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compliance_documents`
--
ALTER TABLE `compliance_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driver_behavior_incidents`
--
ALTER TABLE `driver_behavior_incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_behavior_summary`
--
ALTER TABLE `driver_behavior_summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_behavior_trends`
--
ALTER TABLE `driver_behavior_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_attachments`
--
ALTER TABLE `incident_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_cases`
--
ALTER TABLE `incident_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incident_comments`
--
ALTER TABLE `incident_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `maintenance_approvals`
--
ALTER TABLE `maintenance_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_behavior_trends`
--
ALTER TABLE `monthly_behavior_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transport_expenses`
--
ALTER TABLE `transport_expenses`
  MODIFY `expense_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`),
  ADD CONSTRAINT `fk_approver` FOREIGN KEY (`approver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `behavior_incidents`
--
ALTER TABLE `behavior_incidents`
  ADD CONSTRAINT `behavior_incidents_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `driver_behavior_incidents`
--
ALTER TABLE `driver_behavior_incidents`
  ADD CONSTRAINT `driver_behavior_incidents_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `driver_behavior_summary`
--
ALTER TABLE `driver_behavior_summary`
  ADD CONSTRAINT `driver_behavior_summary_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  ADD CONSTRAINT `fuel_expenses_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `fuel_expenses_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`);

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_attachments`
--
ALTER TABLE `incident_attachments`
  ADD CONSTRAINT `incident_attachments_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_comments`
--
ALTER TABLE `incident_comments`
  ADD CONSTRAINT `incident_comments_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`);

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
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
