-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 09:33 PM
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
(7, 3, 'Emission Test', '2025-03-05', '2026-03-05', 'EMI-001', 'uploads/documents/emi1.pdf', 'Emission compliance test', '2026-01-25 05:20:14'),
(8, 1, 'Registration', '2026-02-06', '2050-02-06', NULL, NULL, NULL, '2026-02-05 22:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `license` varchar(30) DEFAULT NULL,
  `status` enum('Active','On Leave','Inactive') DEFAULT 'Active',
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

INSERT INTO `drivers` (`id`, `user_id`, `license`, `status`, `address`, `emergency_contact`, `blood_type`, `join_date`, `expiry`, `rating`, `safety_score`, `on_time_rate`, `total_trips`, `total_distance`, `incidents`, `created_at`, `updated_at`) VALUES
(13, 12, 'DL-900001', 'Active', 'Quezon City', 'Maria Dela Cruz – 09171230001', 'O+', '2023-01-10', '2028-01-10', 4.6, 96, 97, 150, 18250.50, 1, '2026-01-31 21:14:44', '2026-01-31 21:14:44'),
(14, 13, 'DL-900002', 'Active', 'Manila', 'Ana Villanueva – 09171230002', 'A+', '2022-06-15', '2027-06-15', 4.3, 92, 94, 132, 16540.75, 2, '2026-01-31 21:14:44', '2026-01-31 21:14:44'),
(15, 14, 'DL-900003', 'On Leave', 'Caloocan', 'Rene Fernandez – 09171230003', 'B+', '2021-09-20', '2026-09-20', 4.1, 90, 91, 98, 13420.10, 3, '2026-01-31 21:14:44', '2026-01-31 21:14:44'),
(16, 15, 'DL-900004', 'Active', 'Pasig City', 'Liza Navarro – 09171230004', 'O-', '2024-02-01', '2029-02-01', 4.8, 98, 99, 75, 8540.00, 0, '2026-01-31 21:14:44', '2026-01-31 21:14:44');

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

--
-- Dumping data for table `fuel_expenses`
--

INSERT INTO `fuel_expenses` (`id`, `vehicle_id`, `date`, `liters`, `cost`, `receipt_path`, `driver_id`, `created_at`, `status`, `fuel_type`) VALUES
(1, 3, '2026-02-01', 5.00, 250.00, 'uploads/fuel_receipts/1769902050_3994.jpg', 15, '2026-01-31 23:27:30', 'Pending', NULL),
(2, 3, '2026-02-01', 2.00, 100.00, 'uploads/fuel_receipts/1769904964_5599.jpg', 15, '2026-02-01 00:16:04', 'Pending', NULL),
(3, 3, '2026-02-01', 5.00, 250.00, 'uploads/fuel_receipts/1769905019_4694.png', 14, '2026-02-01 00:16:59', 'Pending', NULL),
(4, 3, '2026-02-01', 5.00, 250.00, 'uploads/fuel_receipts/1769905201_2853.png', 14, '2026-02-01 00:20:01', 'Pending', NULL),
(5, 1, '2026-02-01', 5.00, 300.00, NULL, 14, '2026-02-01 11:12:34', 'Pending', 'Gasoline'),
(6, 2, '2026-02-01', 4.00, 300.00, 'uploads/fuel_receipts/1769944748_697f36ac8c2a2.jpg', 14, '2026-02-01 11:19:08', 'Pending', 'Diesel'),
(8, 1, '2026-02-03', 6.00, 300.00, 'uploads/fuel_receipts/1770092033_6981760175ad8.jpg', 13, '2026-02-03 04:13:53', 'Pending', 'Bio-Diesel');

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
  `resolution_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_cases`
--

INSERT INTO `incident_cases` (`id`, `case_number`, `driver_id`, `vehicle_id`, `type`, `severity`, `date`, `reported_by`, `location`, `description`, `status`, `resolution_notes`) VALUES
(1, 'CASE-20260124-143959', 5, NULL, 'Traffic Violation', 'Medium', '2026-01-24 21:39:00', 'Driver', 'EDSA Quezon Ave.', 'hhh', 'Closed', 'hhhh'),
(2, 'CASE-20260124-144004', 5, NULL, 'Traffic Violation', 'Medium', '2026-01-24 21:39:00', 'Driver', 'EDSA Quezon Ave.', 'hhh', 'Closed', 'waaa'),
(5, 'CASE-20260207-092008', 15, 3, 'Accident', 'High', '2026-02-07 16:19:00', 'Driver', 'EDSA Quezon Ave.', 'aaa', 'Under Investigation', NULL);

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
(3, 1, 'Brake Service', '2026-02-02', 1250.00, '', 'Completed', '2026-01-19 18:38:01'),
(4, 3, 'Oil Change', '2026-02-01', 5000.00, '', 'In Progress', '2026-01-31 22:09:48');

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

-- --------------------------------------------------------

--
-- Table structure for table `terms_acceptance`
--

CREATE TABLE `terms_acceptance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `accepted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, 1, NULL, 'Warehouse to City Center', '2026-01-25', '2026-01-25', 'Delivery', 'Handle with care', 25.50, 26.00, '01:30', '01:35', 3.20, 200.50, 'On-Time', 100, 5, 0, '08:00:00', '09:35:00', 'TRIP-20260125-001', 'Warehouse', 'City Center'),
(8, 2, NULL, 'Depot to Main Office', '2026-01-24', '2026-01-24', 'Pickup', 'Fragile items', 15.00, 15.50, '01:00', '01:05', 2.00, 120.00, 'Delayed', 80, 3, 0, '09:00:00', '10:05:00', 'TRIP-20260124-002', 'Depot', 'Main Office'),
(9, 3, NULL, 'Factory to Port', '2026-01-23', '2026-01-23', 'Shipping', 'Urgent', 50.00, 52.00, '02:30', '02:40', 6.50, 400.00, 'On-Time', 100, 10, 2, '07:00:00', '09:40:00', 'TRIP-20260123-003', 'Factory', 'Port'),
(10, 4, NULL, 'Office to Supplier', '2026-01-22', '2026-01-22', 'Materials Pickup', 'Check goods', 30.00, 31.00, '01:45', '01:50', 4.00, 250.00, 'Cancelled', 0, 0, 0, '10:00:00', '11:50:00', 'TRIP-20260122-004', 'Office', 'Supplier'),
(11, 5, NULL, 'Headquarters to Warehouse', '2026-01-21', '2026-01-21', 'Inventory Transfer', 'No issues', 20.00, 20.50, '01:15', '01:20', 2.50, 150.00, 'On-Time', 100, 2, 0, '08:30:00', '09:50:00', 'TRIP-20260121-005', 'Headquarters', 'Warehouse');

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
(8, 'Karen Lopez', '09170000003', 'karen.staff@logistics.com', '$2y$10$staffhash1', 'Staff', 'Active', '2026-01-31 21:13:43'),
(9, 'Michael Tan', '09170000004', 'michael.staff@logistics.com', '$2y$10$staffhash2', 'Staff', 'Active', '2026-01-31 21:13:43'),
(10, 'Jenny Ramos', '09170000005', 'jenny.emp@logistics.com', '$2y$10$emphash1', 'Employee', 'Active', '2026-01-31 21:13:43'),
(11, 'Paul Santos', '09170000006', 'paul.emp@logistics.com', '$2y$10$emphash2', 'Employee', 'Active', '2026-01-31 21:13:43'),
(12, 'Juan Dela Cruz', '09170000007', 'juan.driver@logistics.com', '$2y$10$driverhash1', 'Driver', 'Active', '2026-01-31 21:13:43'),
(13, 'Mark Villanueva', '09170000008', 'mark.driver@logistics.com', '$2y$10$driverhash2', 'Driver', 'Active', '2026-01-31 21:13:43'),
(14, 'Leo Fernandez', '09170000009', 'leo.driver@logistics.com', '$2y$10$driverhash3', 'Driver', 'Active', '2026-01-31 21:13:43'),
(15, 'Eric Navarro', '09170000010', 'eric.driver@logistics.com', '$2y$10$driverhash4', 'Driver', 'Active', '2026-01-31 21:13:43');

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
  ADD UNIQUE KEY `license_unique` (`license`),
  ADD KEY `fk_drivers_users` (`user_id`);

--
-- Indexes for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `driver_behavior`
--
ALTER TABLE `driver_behavior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fuel_expenses`
--
ALTER TABLE `fuel_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_cases`
--
ALTER TABLE `incident_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `terms_acceptance`
--
ALTER TABLE `terms_acceptance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_drivers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
