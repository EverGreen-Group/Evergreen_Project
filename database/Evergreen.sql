-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2024 at 07:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tfms`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_addresses`
--

CREATE TABLE `application_addresses` (
  `address_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_addresses`
--

INSERT INTO `application_addresses` (`address_id`, `application_id`, `line1`, `line2`, `city`, `district`, `postal_code`, `latitude`, `longitude`) VALUES
(12, 25, 'mongo', NULL, 'ff', 'Other', '500', 6.89086094, 79.86760449),
(13, 26, 'mongo', NULL, 'ff', 'Kandy', '500', 7.54810667, 80.52557314);

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `document_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` enum('nic','ownership_proof','tax_receipts','bank_passbook','grama_cert') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`document_id`, `application_id`, `document_type`, `file_path`, `uploaded_at`) VALUES
(31, 25, 'nic', 'uploads/supplier_documents/1731139038_nic_672f15deabd16.png', '2024-11-09 07:57:18'),
(32, 25, 'ownership_proof', 'uploads/supplier_documents/1731139038_ownership_proof_672f15deac28a.pdf', '2024-11-09 07:57:18'),
(33, 25, 'tax_receipts', 'uploads/supplier_documents/1731139038_tax_receipts_672f15deac70d.png', '2024-11-09 07:57:18'),
(34, 25, 'bank_passbook', 'uploads/supplier_documents/1731139038_bank_passbook_672f15dead06e.png', '2024-11-09 07:57:18'),
(35, 25, 'grama_cert', 'uploads/supplier_documents/1731139038_grama_cert_672f15dead514.png', '2024-11-09 07:57:18'),
(36, 26, 'nic', 'uploads/supplier_documents/1731155902_nic_672f57be8489d.png', '2024-11-09 12:38:22'),
(37, 26, 'ownership_proof', 'uploads/supplier_documents/1731155902_ownership_proof_672f57be84f5d.pdf', '2024-11-09 12:38:22'),
(38, 26, 'tax_receipts', 'uploads/supplier_documents/1731155902_tax_receipts_672f57be851fb.png', '2024-11-09 12:38:22'),
(39, 26, 'bank_passbook', 'uploads/supplier_documents/1731155902_bank_passbook_672f57be856a3.png', '2024-11-09 12:38:22'),
(40, 26, 'grama_cert', 'uploads/supplier_documents/1731155902_grama_cert_672f57be86f2b.png', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_infrastructure`
--

CREATE TABLE `application_infrastructure` (
  `infrastructure_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `access_road` enum('Paved Road','Gravel Road','Estate Road','Footpath Only') NOT NULL,
  `vehicle_access` enum('All Weather Access','Fair Weather Only','Limited Access','No Vehicle Access') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_infrastructure`
--

INSERT INTO `application_infrastructure` (`infrastructure_id`, `application_id`, `access_road`, `vehicle_access`, `created_at`) VALUES
(3, 25, 'Paved Road', 'All Weather Access', '2024-11-09 07:57:18'),
(4, 26, 'Gravel Road', 'All Weather Access', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_ownership_details`
--

CREATE TABLE `application_ownership_details` (
  `ownership_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `ownership_type` varchar(50) NOT NULL,
  `ownership_duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_ownership_details`
--

INSERT INTO `application_ownership_details` (`ownership_id`, `application_id`, `ownership_type`, `ownership_duration`) VALUES
(5, 25, 'Private Owner', 2),
(6, 26, 'Private Owner', 2);

-- --------------------------------------------------------

--
-- Table structure for table `application_property_details`
--

CREATE TABLE `application_property_details` (
  `property_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `total_land_area` decimal(10,2) NOT NULL,
  `tea_cultivation_area` decimal(10,2) NOT NULL,
  `elevation` int(11) NOT NULL,
  `slope` enum('Flat','Gentle','Moderate','Steep') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_property_details`
--

INSERT INTO `application_property_details` (`property_id`, `application_id`, `total_land_area`, `tea_cultivation_area`, `elevation`, `slope`, `created_at`) VALUES
(3, 25, 2.00, 2.00, 0, 'Flat', '2024-11-09 07:57:18'),
(4, 26, 2.00, 3.00, 2, 'Flat', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_structures`
--

CREATE TABLE `application_structures` (
  `structure_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `structure_type` enum('Storage Facility','Worker Rest Area','Equipment Storage','Living Quarters','None') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_structures`
--

INSERT INTO `application_structures` (`structure_id`, `application_id`, `structure_type`) VALUES
(3, 25, 'Storage Facility'),
(4, 26, 'Storage Facility'),
(5, 26, 'Worker Rest Area');

-- --------------------------------------------------------

--
-- Table structure for table `application_tea_details`
--

CREATE TABLE `application_tea_details` (
  `tea_detail_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `plant_age` int(11) NOT NULL,
  `monthly_production` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_tea_details`
--

INSERT INTO `application_tea_details` (`tea_detail_id`, `application_id`, `plant_age`, `monthly_production`) VALUES
(4, 25, 2, 400),
(5, 26, 2, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `application_tea_varieties`
--

CREATE TABLE `application_tea_varieties` (
  `variety_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `variety_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_tea_varieties`
--

INSERT INTO `application_tea_varieties` (`variety_id`, `application_id`, `variety_name`) VALUES
(11, 25, 'TRI 2023'),
(12, 25, 'TRI 2025'),
(13, 26, 'TRI 2023');

-- --------------------------------------------------------

--
-- Table structure for table `application_water_sources`
--

CREATE TABLE `application_water_sources` (
  `water_source_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `source_type` enum('Natural Spring','Well','Stream/River','Rain Water','Public Water Supply') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_water_sources`
--

INSERT INTO `application_water_sources` (`water_source_id`, `application_id`, `source_type`) VALUES
(5, 25, 'Rain Water'),
(6, 25, 'Public Water Supply'),
(7, 26, 'Well'),
(8, 26, 'Public Water Supply');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Half-day','Leave') NOT NULL,
  `working_hours` decimal(4,2) DEFAULT NULL,
  `check_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `skeleton_id` int(11) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_quantity` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `team_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_skeletons`
--

CREATE TABLE `collection_skeletons` (
  `skeleton_id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_skeletons`
--

INSERT INTO `collection_skeletons` (`skeleton_id`, `route_id`, `team_id`, `vehicle_id`, `shift_id`, `created_at`, `is_active`) VALUES
(8, 1, 4, 1, 1, '2024-11-09 19:53:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crates`
--

CREATE TABLE `crates` (
  `crate_id` varchar(20) NOT NULL,
  `status` enum('Available','In Use','Maintenance','Retired') DEFAULT 'Available',
  `crate_size` enum('Small','Medium','Large') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crates`
--

INSERT INTO `crates` (`crate_id`, `status`, `crate_size`) VALUES
('CRATE001', 'Available', 'Medium'),
('CRATE002', 'In Use', 'Large'),
('CRATE003', 'Available', 'Small');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_fee` decimal(6,2) DEFAULT NULL,
  `tracking_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_metrics`
--

CREATE TABLE `daily_metrics` (
  `metrics_id` int(11) NOT NULL,
  `collection_date` date NOT NULL,
  `total_leaves` decimal(10,2) DEFAULT 0.00,
  `total_weight` decimal(10,2) DEFAULT 0.00,
  `total_crates` int(11) DEFAULT 0,
  `average_quality` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_metrics`
--

INSERT INTO `daily_metrics` (`metrics_id`, `collection_date`, `total_leaves`, `total_weight`, `total_crates`, `average_quality`, `created_at`) VALUES
(1, '2023-10-10', 1000.00, 91.25, 2, 4.50, '2024-11-02 07:14:30'),
(2, '2023-10-11', 1200.00, 80.00, 1, 4.00, '2024-11-02 07:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('NIC Copy','Photo','Ownership Proof','Survey Plan','Tax Receipt','Bank Statement','Passbook','Grama Certificate','Agrarian Certificate') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `user_id`, `document_type`, `file_path`, `upload_date`) VALUES
(1, 1, 'Photo', 'https://i.ytimg.com/vi/rHia1Kiwa0k/sddefault.jpg', '2024-11-04 15:10:04'),
(2, 3, 'Photo', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQK7qjttX5ka75LeZWFXC984Cm4XQ0NpZT4jg&s', '2024-11-04 16:04:57'),
(3, 17, 'Photo', 'https://i.ytimg.com/vi/D9YZw_X5UzQ/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCyeyOBvIr-5hwa2KhAa07uagHaJw', '2024-11-05 08:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `license_no` varchar(50) NOT NULL,
  `status` enum('Available','On Route','Off Duty') DEFAULT 'Available',
  `experience_years` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driver_id`, `employee_id`, `license_no`, `status`, `experience_years`) VALUES
(1, 5, 'DL123456', 'Available', 5),
(2, 6, 'DL123457', 'Available', 3),
(3, 13, '12313213213', 'Available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `driving_partners`
--

CREATE TABLE `driving_partners` (
  `partner_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('Available','On Route','Off Duty') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driving_partners`
--

INSERT INTO `driving_partners` (`partner_id`, `employee_id`, `status`) VALUES
(1, 7, 'Available'),
(2, 8, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('M','F','Other') DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `status` enum('Active','Inactive','On Leave') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `user_id`, `salary`, `hire_date`, `dob`, `gender`, `shift_id`, `contact_number`, `emergency_contact`, `status`) VALUES
(3, 3, 50000.00, '2021-06-10', '1990-03-05', 'F', 1, '0771234567', '0779876543', 'Active'),
(4, 4, 60000.00, '2020-10-20', '1988-07-11', 'M', 2, '0761234567', '0769876543', 'On Leave'),
(5, 1, 30000.00, '2022-01-15', '1985-06-20', 'M', 1, '0771234567', '0777654321', 'Active'),
(6, 2, 28000.00, '2022-02-20', '1990-09-15', 'F', 1, '0772345678', '0778765432', 'Active'),
(7, 3, 32000.00, '2022-01-10', '1988-11-30', 'M', 2, '0773456789', '0779876543', 'Active'),
(8, 4, 31000.00, '2022-03-05', '1992-12-25', 'F', 2, '0774567890', '0770987654', 'Active'),
(9, 13, 60000.00, '2022-01-01', '1980-01-15', 'M', NULL, '1234567890', '0987654321', 'Active'),
(10, 14, 55000.00, '2022-02-01', '1985-05-20', 'F', NULL, '1234567891', '0987654322', 'Active'),
(11, 15, 50000.00, '2022-03-01', '1978-07-25', 'M', NULL, '1234567892', '0987654323', 'Inactive'),
(12, 16, 52000.00, '2022-04-01', '1990-10-10', 'F', NULL, '1234567893', '0987654324', 'Active'),
(13, 17, 123.00, '2024-11-05', '2024-11-11', 'M', 1, '0779998336', '0779998336', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `Fertilizer`
--

CREATE TABLE `Fertilizer` (
  `id` int(11) NOT NULL,
  `fertilizer_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_inventory`
--

CREATE TABLE `fertilizer_inventory` (
  `inventory_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_orders`
--

CREATE TABLE `fertilizer_orders` (
  `order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Delivered','Cancelled') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_order_items`
--

CREATE TABLE `fertilizer_order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `fertilizer_type_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_types`
--

CREATE TABLE `fertilizer_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `recommended_usage` text DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `available_quantity` decimal(10,2) DEFAULT 0.00,
  `min_stock_level` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_managers`
--

CREATE TABLE `inventory_managers` (
  `inv_manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `land_details`
--

CREATE TABLE `land_details` (
  `land_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ownership_type` enum('Private Owner','Joint Owner','Lease Holder','Government Permit','Other') NOT NULL,
  `ownership_duration` int(11) NOT NULL,
  `total_land_area` decimal(5,2) NOT NULL,
  `tea_cultivation_area` decimal(5,2) NOT NULL,
  `elevation` decimal(5,2) NOT NULL,
  `slope` enum('Flat','Gentle','Moderate','Steep') NOT NULL,
  `plant_age` int(11) NOT NULL,
  `existing_production` decimal(10,2) NOT NULL,
  `water_sources` longtext NOT NULL,
  `access_road` enum('Paved Road','Gravel Road','Estate Road','Footpath Only') NOT NULL,
  `vehicle_access` enum('All Weather Access','Fair Weather Only','Limited Access','No Vehicle Access') NOT NULL,
  `structures` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land_details`
--

INSERT INTO `land_details` (`land_id`, `user_id`, `ownership_type`, `ownership_duration`, `total_land_area`, `tea_cultivation_area`, `elevation`, `slope`, `plant_age`, `existing_production`, `water_sources`, `access_road`, `vehicle_access`, `structures`) VALUES
(1, 5, 'Private Owner', 10, 50.00, 20.00, 600.00, 'Gentle', 5, 1500.00, 'Spring Water', 'Paved Road', 'All Weather Access', 'Tea Processing Shed');

-- --------------------------------------------------------

--
-- Table structure for table `land_inspections`
--

CREATE TABLE `land_inspections` (
  `inspection_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `inspection_date` date NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `type` enum('Supplier','Warehouse','Factory','Customer') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `machine_id` int(11) NOT NULL,
  `machine_type` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `status` enum('Operational','Maintenance','Broken') DEFAULT 'Operational',
  `next_maintenance_date` date DEFAULT NULL,
  `last_maintenance_date` date DEFAULT NULL,
  `installation_date` date NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_usage`
--

CREATE TABLE `machine_usage` (
  `usage_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `manager_type` enum('Vehicle Manager','Inventory Manager','Supplier Relations Manager','Employee Manager') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `employee_id`, `manager_type`, `status`) VALUES
(5, 9, 'Vehicle Manager', 'Active'),
(6, 10, 'Inventory Manager', 'Active'),
(7, 11, 'Supplier Relations Manager', 'Inactive'),
(8, 12, 'Employee Manager', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('Email','SMS','System') NOT NULL,
  `status` enum('Pending','Sent','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `operator_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `certification_level` varchar(50) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_name`, `location`, `details`, `code`, `price`, `profit`, `margin`, `quantity`, `unit`, `image_path`) VALUES
(5, 'red tea', 'warehouse-a', 'red', 'red', 12345.00, 23.00, 32.00, 12, 'item', '673a305a21a7b.png'),
(8, 'Tea', 'warehouse-b', 'super liyana', 'qwe', 2500.00, 500.00, 500.00, 20000, 'item', '673978432e964.svg'),
(9, 'asdfgh', 'warehouse-a', 'asdfgh', 'saas', 100.00, 300.00, 500.00, 20000, 'kg', '673a2f8ad49f9.png'),
(10, 'Lakshitha', 'warehouse-a', 'dfsdfs', 'sdfsdfs', 123456.00, 12.00, 12.00, 1999, 'item', '6739ab5919be0.png');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `batch_number` varchar(50) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_by_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(6, 'Driver'),
(8, 'Driving Partner'),
(3, 'Employee'),
(9, 'Employee Manager'),
(11, 'Inventory Manager'),
(4, 'Operator'),
(1, 'Super Admin'),
(5, 'Supplier'),
(2, 'Supplier Manager'),
(10, 'Vehicle Manager'),
(7, 'Website User');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) DEFAULT NULL,
  `start_location_lat` decimal(10,8) DEFAULT NULL,
  `start_location_long` decimal(11,8) DEFAULT NULL,
  `end_location_lat` decimal(10,8) DEFAULT NULL,
  `end_location_long` decimal(11,8) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `number_of_suppliers` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `route_name`, `start_location_lat`, `start_location_long`, `end_location_lat`, `end_location_long`, `date`, `number_of_suppliers`, `status`, `is_deleted`) VALUES
(8, 'murshid bawa', 6.21730370, 80.25386360, 6.21730370, 80.25386360, '2024-11-07', 2, 'Active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `shift_name`, `shift_start`, `shift_end`) VALUES
(1, 'Evening Shift', '16:00:00', '19:00:00'),
(2, 'Night Shift', '18:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_applications`
--

CREATE TABLE `supplier_applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `primary_phone` varchar(15) NOT NULL,
  `secondary_phone` varchar(15) DEFAULT NULL,
  `whatsapp_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_applications`
--

INSERT INTO `supplier_applications` (`application_id`, `user_id`, `status`, `primary_phone`, `secondary_phone`, `whatsapp_number`, `created_at`, `updated_at`) VALUES
(25, 21, 'pending', '0779998336', NULL, NULL, '2024-11-09 07:57:18', '2024-11-09 07:57:18'),
(26, 17, 'pending', '0779998336', NULL, NULL, '2024-11-09 12:38:22', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_bank_info`
--

CREATE TABLE `supplier_bank_info` (
  `bank_info_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `account_holder_name` varchar(255) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_type` enum('Savings','Current') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_bank_info`
--

INSERT INTO `supplier_bank_info` (`bank_info_id`, `application_id`, `account_holder_name`, `bank_name`, `branch_name`, `account_number`, `account_type`, `created_at`) VALUES
(3, 25, 'Simaak Niyaz', 'Sampath Bank', 'Thimbirigasyaya', '21313221312321', 'Current', '2024-11-09 07:57:18'),
(4, 26, '213213', 'Bank of Ceylon', '21321321', '2121321321321', 'Current', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `driver_id`, `partner_id`, `manager_id`, `status`, `created_at`, `is_visible`) VALUES
(3, 'Team Alpha', 1, 1, 5, 'Active', '2024-11-02 09:27:39', 1),
(4, 'Team Beta', 3, 2, 5, 'Active', '2024-11-02 09:27:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_progress`
--

CREATE TABLE `team_progress` (
  `progress_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tea_leaves_inventory`
--

CREATE TABLE `tea_leaves_inventory` (
  `inventory_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quality_grade` enum('A','B','C','D') NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `received_date` date NOT NULL,
  `processing_status` enum('Raw','Processing','Processed') DEFAULT 'Raw'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tea_products`
--

CREATE TABLE `tea_products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Black','Green','White','Oolong','Blend') NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `min_stock_level` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Available','Out of Stock','Discontinued') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` enum('Pending','Approved','Rejected','None') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `nic`, `date_of_birth`, `gender`, `role_id`, `created_at`, `approval_status`) VALUES
(1, 'superadmin@example.com', 'password_hash_1', 'Alice', 'Wright', '123456789V', '1985-04-20', 'Female', 1, '2024-11-02 05:32:54', 'Approved'),
(2, 'manager@example.com', 'password_hash_2', 'Bob', 'Green', '987654321V', '1990-07-15', 'Male', 2, '2024-11-02 05:32:54', 'Approved'),
(3, 'employee@example.com', 'password_hash_3', 'Clara', 'Stone', '543216789V', '1992-03-30', 'Female', 3, '2024-11-02 05:32:54', 'Approved'),
(4, 'operator@example.com', 'password_hash_4', 'Derek', 'Hall', '654321987V', '1988-12-01', 'Male', 4, '2024-11-02 05:32:54', 'Pending'),
(5, 'supplier@example.com', 'password_hash_5', 'John', 'Doe', '789456123V', '1980-11-11', 'Male', 5, '2024-11-02 05:32:54', 'Approved'),
(6, 'driver@example.com', 'password_hash_6', 'Peter', 'Black', '123987654V', '1995-06-25', 'Male', 6, '2024-11-02 05:32:54', 'Approved'),
(7, 'customer@example.com', 'password_hash_7', 'Liam', 'Lee', '456123789V', '1998-09-09', 'Male', 7, '2024-11-02 05:32:54', 'Pending'),
(8, 'drivingpartner@example.com', 'password_hash_8', 'Emma', 'Johnson', '321654987V', '1989-02-14', 'Female', 8, '2024-11-02 05:32:54', 'Approved'),
(9, 'driver1@example.com', 'hashed_password1', 'John', 'Doe', 'NIC001', '1985-06-20', 'Male', 2, '2024-11-02 08:03:55', 'Approved'),
(10, 'driver2@example.com', 'hashed_password2', 'Jane', 'Smith', 'NIC002', '1990-09-15', 'Female', 2, '2024-11-02 08:03:55', 'Approved'),
(11, 'partner1@example.com', 'hashed_password3', 'Michael', 'Johnson', 'NIC003', '1988-11-30', 'Male', 3, '2024-11-02 08:03:55', 'Approved'),
(12, 'partner2@example.com', 'hashed_password4', 'Emily', 'Davis', 'NIC004', '1992-12-25', 'Female', 3, '2024-11-02 08:03:55', 'Approved'),
(13, 'vehicle_manager@example.com', 'password123', 'John', 'Doe', 'NIC123456V', '1980-01-15', 'Male', 2, '2024-11-02 09:19:45', 'Approved'),
(14, 'inventory_manager@example.com', 'password123', 'Jane', 'Smith', 'NIC123457V', '1985-05-20', 'Female', 2, '2024-11-02 09:19:45', 'Approved'),
(15, 'supplier_manager@example.com', 'password123', 'Jim', 'Beam', 'NIC123458V', '1978-07-25', 'Male', 2, '2024-11-02 09:19:45', 'Approved'),
(16, 'employee_manager@example.com', 'password123', 'Sara', 'Connor', 'NIC123459V', '1990-10-10', 'Female', 2, '2024-11-02 09:19:45', 'Approved'),
(17, 'babydriver@gmail.com', '$2y$10$RYkHOo5LwYABMTU0RzB89.9AjqH8ENqFRp2YSvgPSputSCtJ1O3nm', 'Baby', 'Driver', '200206400133', '2002-03-04', 'Male', 6, '2024-11-05 08:03:43', 'Approved'),
(18, 'fortniteman@gmail.com', 'pog', 'fortnite', 'man', '200206400132', '2024-11-01', 'Male', 5, '2024-11-05 12:42:11', 'Approved'),
(20, 'birdy@gmail.com', '$2y$10$1gkBt1HCCIx2.Y7k329zReVlBLRrNtA63P6DaGE9HAZBvvU1svhH6', 'Birdy', 'Wings', '123213214', '2018-06-12', 'Female', 1, '2024-11-06 14:52:09', 'Approved'),
(21, 'becomesupplier@gmail.com', '$2y$10$SmrFz1JWLszj3uRH3qR3MOTDyH5n/FT6Q7zl/hMT6pxBoO9TdEYNe', 'Become', 'Supplier', '3143143131', '2016-01-04', 'Male', 7, '2024-11-07 19:49:36', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `district` enum('Kandy','Nuwara Eliya','Badulla','Ratnapura','Galle','Matara','Kalutara','Other') NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `gps_latitude` decimal(9,6) DEFAULT NULL,
  `gps_longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE `user_contacts` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `primary_phone` varchar(10) NOT NULL,
  `secondary_phone` varchar(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `whatsapp_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `file_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` (`photo_id`, `user_id`, `file_name`, `upload_date`, `is_active`, `file_type`) VALUES
(1, 4, '150', '2024-11-09 20:57:05', 1, 'jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `status` enum('Available','In Use','Maintenance') DEFAULT 'Available',
  `owner_name` varchar(100) DEFAULT NULL,
  `owner_contact` varchar(15) DEFAULT NULL,
  `capacity` decimal(8,2) DEFAULT NULL,
  `vehicle_type` enum('Truck','Van','Car','Bus','Three-Wheeler','Other') DEFAULT NULL,
  `insurance_expiry_date` date DEFAULT NULL,
  `road_tax_expiry_date` date DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `engine_number` varchar(50) DEFAULT NULL,
  `chassis_number` varchar(50) DEFAULT NULL,
  `seating_capacity` int(11) DEFAULT NULL,
  `condition` enum('New','Good','Fair','Poor') DEFAULT NULL,
  `last_serviced_date` date DEFAULT NULL,
  `last_maintenance` date DEFAULT NULL,
  `next_maintenance` date DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel_type` enum('Petrol','Diesel','Electric','Hybrid') DEFAULT 'Petrol',
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `license_plate`, `status`, `owner_name`, `owner_contact`, `capacity`, `vehicle_type`, `insurance_expiry_date`, `road_tax_expiry_date`, `color`, `engine_number`, `chassis_number`, `seating_capacity`, `condition`, `last_serviced_date`, `last_maintenance`, `next_maintenance`, `mileage`, `fuel_type`, `registration_date`) VALUES
(1, 'WP-1234', 'Available', 'John Silva', '0771234567', 1000.50, 'Truck', '2025-12-31', '2024-11-30', 'Blue', 'EN1234567890', 'CH1234567890', 2, 'Good', '2023-10-01', '2023-10-01', '2024-10-01', 15000, 'Diesel', '2022-01-15'),
(2, 'WP-5678', 'Available', 'Nimal Perera', '0719876543', 500.75, 'Van', '2024-06-30', '2023-12-31', 'White', 'EN0987654321', 'CH0987654321', 8, 'New', '2023-09-15', '2023-09-15', '2024-09-15', 5000, 'Petrol', '2021-05-20'),
(7, 'AB-1234', 'Available', 'John Silva', '0771234567', 213.00, 'Truck', '2024-11-06', '2024-10-29', 'Red', 'EN1234567890', 'CH1234567890', 2, 'Fair', '2024-10-29', '2024-11-08', '2024-11-05', 213, 'Diesel', '2024-11-28');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_documents`
--

CREATE TABLE `vehicle_documents` (
  `vehicle_document_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `document_type` enum('Service Record','Insurance','Road Tax','Maintenance Record','Image','Ownership Proof') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_documents`
--

INSERT INTO `vehicle_documents` (`vehicle_document_id`, `vehicle_id`, `document_type`, `file_path`, `upload_date`) VALUES
(1, 1, 'Image', 'https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg', '2024-11-03 14:13:09'),
(2, 2, 'Image', 'https://i.ikman-st.com/mazda-bongo-1997-for-sale-puttalam-2/cdd5b09e-ab3f-42c4-8642-575b1bc9072b/620/466/fitted.jpg', '2024-11-03 15:16:15'),
(3, 7, 'Image', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrLhTwmjDEshchAYXpCwmtkDEh4ywp6MOQrA&s', '2024-11-03 18:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_managers`
--

CREATE TABLE `vehicle_managers` (
  `vehicle_manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owner_history`
--

CREATE TABLE `vehicle_owner_history` (
  `history_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_contact` varchar(15) DEFAULT NULL,
  `ownership_start_date` date NOT NULL,
  `ownership_end_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `vehicle_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_service_records`
--

CREATE TABLE `vehicle_service_records` (
  `record_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `service_type` enum('Routine','Repair','Inspection','Upgrade') NOT NULL,
  `service_center` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `next_service_due` date DEFAULT NULL,
  `vehicle_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` decimal(10,2) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive','Maintenance') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `worker_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_addresses`
--
ALTER TABLE `application_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `application_addresses_ibfk_1` (`application_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `application_documents_ibfk_1` (`application_id`);

--
-- Indexes for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  ADD PRIMARY KEY (`infrastructure_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  ADD PRIMARY KEY (`ownership_id`),
  ADD KEY `application_ownership_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_property_details`
--
ALTER TABLE `application_property_details`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `application_property_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_structures`
--
ALTER TABLE `application_structures`
  ADD PRIMARY KEY (`structure_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  ADD PRIMARY KEY (`tea_detail_id`),
  ADD KEY `application_tea_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  ADD PRIMARY KEY (`variety_id`),
  ADD KEY `application_tea_varieties_ibfk_1` (`application_id`);

--
-- Indexes for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  ADD PRIMARY KEY (`water_source_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `skeleton_id` (`skeleton_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  ADD PRIMARY KEY (`skeleton_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `crates`
--
ALTER TABLE `crates`
  ADD PRIMARY KEY (`crate_id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `daily_metrics`
--
ALTER TABLE `daily_metrics`
  ADD PRIMARY KEY (`metrics_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `license_no` (`license_no`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `driving_partners`
--
ALTER TABLE `driving_partners`
  ADD PRIMARY KEY (`partner_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`verification_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `Fertilizer`
--
ALTER TABLE `Fertilizer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fertilizer_type_id` (`fertilizer_type_id`);

--
-- Indexes for table `fertilizer_types`
--
ALTER TABLE `fertilizer_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  ADD PRIMARY KEY (`inv_manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `land_details`
--
ALTER TABLE `land_details`
  ADD PRIMARY KEY (`land_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `land_inspections`
--
ALTER TABLE `land_inspections`
  ADD PRIMARY KEY (`inspection_id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`machine_id`);

--
-- Indexes for table `machine_usage`
--
ALTER TABLE `machine_usage`
  ADD PRIMARY KEY (`usage_id`),
  ADD KEY `machine_id` (`machine_id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`operator_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `supplier_applications_ibfk_1` (`user_id`);

--
-- Indexes for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  ADD PRIMARY KEY (`bank_info_id`),
  ADD UNIQUE KEY `unique_account_number` (`account_number`),
  ADD KEY `idx_application_id` (`application_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `partner_id` (`partner_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `tea_products`
--
ALTER TABLE `tea_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- Indexes for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  ADD PRIMARY KEY (`vehicle_document_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  ADD PRIMARY KEY (`vehicle_manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_document_id` (`vehicle_document_id`);

--
-- Indexes for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_document_id` (`vehicle_document_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_addresses`
--
ALTER TABLE `application_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  MODIFY `infrastructure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  MODIFY `ownership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `application_property_details`
--
ALTER TABLE `application_property_details`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_structures`
--
ALTER TABLE `application_structures`
  MODIFY `structure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  MODIFY `tea_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  MODIFY `variety_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  MODIFY `water_source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  MODIFY `skeleton_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_metrics`
--
ALTER TABLE `daily_metrics`
  MODIFY `metrics_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driving_partners`
--
ALTER TABLE `driving_partners`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Fertilizer`
--
ALTER TABLE `Fertilizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_types`
--
ALTER TABLE `fertilizer_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  MODIFY `inv_manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `land_details`
--
ALTER TABLE `land_details`
  MODIFY `land_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `land_inspections`
--
ALTER TABLE `land_inspections`
  MODIFY `inspection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_usage`
--
ALTER TABLE `machine_usage`
  MODIFY `usage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `operator_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  MODIFY `bank_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team_progress`
--
ALTER TABLE `team_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tea_products`
--
ALTER TABLE `tea_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contacts`
--
ALTER TABLE `user_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  MODIFY `vehicle_document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  MODIFY `vehicle_manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application_addresses`
--
ALTER TABLE `application_addresses`
  ADD CONSTRAINT `application_addresses_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `application_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  ADD CONSTRAINT `application_infrastructure_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  ADD CONSTRAINT `application_ownership_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_property_details`
--
ALTER TABLE `application_property_details`
  ADD CONSTRAINT `application_property_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_structures`
--
ALTER TABLE `application_structures`
  ADD CONSTRAINT `application_structures_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  ADD CONSTRAINT `application_tea_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  ADD CONSTRAINT `application_tea_varieties_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  ADD CONSTRAINT `application_water_sources_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`skeleton_id`) REFERENCES `collection_skeletons` (`skeleton_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collections_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collections_ibfk_4` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  ADD CONSTRAINT `collection_skeletons_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collection_skeletons_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collection_skeletons_ibfk_4` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `driving_partners`
--
ALTER TABLE `driving_partners`
  ADD CONSTRAINT `driving_partners_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD CONSTRAINT `email_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`);

--
-- Constraints for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD CONSTRAINT `fertilizer_inventory_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `fertilizer_types` (`type_id`),
  ADD CONSTRAINT `fertilizer_inventory_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  ADD CONSTRAINT `fertilizer_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  ADD CONSTRAINT `fertilizer_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `fertilizer_orders` (`order_id`),
  ADD CONSTRAINT `fertilizer_order_items_ibfk_2` FOREIGN KEY (`fertilizer_type_id`) REFERENCES `fertilizer_types` (`type_id`);

--
-- Constraints for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  ADD CONSTRAINT `inventory_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `land_details`
--
ALTER TABLE `land_details`
  ADD CONSTRAINT `land_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `land_inspections`
--
ALTER TABLE `land_inspections`
  ADD CONSTRAINT `land_inspections_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`),
  ADD CONSTRAINT `land_inspections_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `machine_usage`
--
ALTER TABLE `machine_usage`
  ADD CONSTRAINT `machine_usage_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`),
  ADD CONSTRAINT `machine_usage_ibfk_2` FOREIGN KEY (`operator_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `operators`
--
ALTER TABLE `operators`
  ADD CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `customer_orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tea_products` (`product_id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tea_products` (`product_id`),
  ADD CONSTRAINT `product_inventory_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `refresh_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`);

--
-- Constraints for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  ADD CONSTRAINT `supplier_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  ADD CONSTRAINT `supplier_bank_info_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`),
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`partner_id`) REFERENCES `driving_partners` (`partner_id`),
  ADD CONSTRAINT `teams_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD CONSTRAINT `team_progress_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_progress_ibfk_2` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE CASCADE;

--
-- Constraints for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  ADD CONSTRAINT `tea_leaves_inventory_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD CONSTRAINT `user_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD CONSTRAINT `user_photos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  ADD CONSTRAINT `vehicle_documents_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  ADD CONSTRAINT `vehicle_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  ADD CONSTRAINT `vehicle_owner_history_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_owner_history_ibfk_2` FOREIGN KEY (`vehicle_document_id`) REFERENCES `vehicle_documents` (`vehicle_document_id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  ADD CONSTRAINT `vehicle_service_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_service_records_ibfk_2` FOREIGN KEY (`vehicle_document_id`) REFERENCES `vehicle_documents` (`vehicle_document_id`) ON DELETE SET NULL;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `inventory_managers` (`inv_manager_id`);

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2024 at 07:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tfms`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_addresses`
--

CREATE TABLE `application_addresses` (
  `address_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_addresses`
--

INSERT INTO `application_addresses` (`address_id`, `application_id`, `line1`, `line2`, `city`, `district`, `postal_code`, `latitude`, `longitude`) VALUES
(12, 25, 'mongo', NULL, 'ff', 'Other', '500', 6.89086094, 79.86760449),
(13, 26, 'mongo', NULL, 'ff', 'Kandy', '500', 7.54810667, 80.52557314);

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `document_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` enum('nic','ownership_proof','tax_receipts','bank_passbook','grama_cert') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`document_id`, `application_id`, `document_type`, `file_path`, `uploaded_at`) VALUES
(31, 25, 'nic', 'uploads/supplier_documents/1731139038_nic_672f15deabd16.png', '2024-11-09 07:57:18'),
(32, 25, 'ownership_proof', 'uploads/supplier_documents/1731139038_ownership_proof_672f15deac28a.pdf', '2024-11-09 07:57:18'),
(33, 25, 'tax_receipts', 'uploads/supplier_documents/1731139038_tax_receipts_672f15deac70d.png', '2024-11-09 07:57:18'),
(34, 25, 'bank_passbook', 'uploads/supplier_documents/1731139038_bank_passbook_672f15dead06e.png', '2024-11-09 07:57:18'),
(35, 25, 'grama_cert', 'uploads/supplier_documents/1731139038_grama_cert_672f15dead514.png', '2024-11-09 07:57:18'),
(36, 26, 'nic', 'uploads/supplier_documents/1731155902_nic_672f57be8489d.png', '2024-11-09 12:38:22'),
(37, 26, 'ownership_proof', 'uploads/supplier_documents/1731155902_ownership_proof_672f57be84f5d.pdf', '2024-11-09 12:38:22'),
(38, 26, 'tax_receipts', 'uploads/supplier_documents/1731155902_tax_receipts_672f57be851fb.png', '2024-11-09 12:38:22'),
(39, 26, 'bank_passbook', 'uploads/supplier_documents/1731155902_bank_passbook_672f57be856a3.png', '2024-11-09 12:38:22'),
(40, 26, 'grama_cert', 'uploads/supplier_documents/1731155902_grama_cert_672f57be86f2b.png', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_infrastructure`
--

CREATE TABLE `application_infrastructure` (
  `infrastructure_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `access_road` enum('Paved Road','Gravel Road','Estate Road','Footpath Only') NOT NULL,
  `vehicle_access` enum('All Weather Access','Fair Weather Only','Limited Access','No Vehicle Access') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_infrastructure`
--

INSERT INTO `application_infrastructure` (`infrastructure_id`, `application_id`, `access_road`, `vehicle_access`, `created_at`) VALUES
(3, 25, 'Paved Road', 'All Weather Access', '2024-11-09 07:57:18'),
(4, 26, 'Gravel Road', 'All Weather Access', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_ownership_details`
--

CREATE TABLE `application_ownership_details` (
  `ownership_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `ownership_type` varchar(50) NOT NULL,
  `ownership_duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_ownership_details`
--

INSERT INTO `application_ownership_details` (`ownership_id`, `application_id`, `ownership_type`, `ownership_duration`) VALUES
(5, 25, 'Private Owner', 2),
(6, 26, 'Private Owner', 2);

-- --------------------------------------------------------

--
-- Table structure for table `application_property_details`
--

CREATE TABLE `application_property_details` (
  `property_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `total_land_area` decimal(10,2) NOT NULL,
  `tea_cultivation_area` decimal(10,2) NOT NULL,
  `elevation` int(11) NOT NULL,
  `slope` enum('Flat','Gentle','Moderate','Steep') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_property_details`
--

INSERT INTO `application_property_details` (`property_id`, `application_id`, `total_land_area`, `tea_cultivation_area`, `elevation`, `slope`, `created_at`) VALUES
(3, 25, 2.00, 2.00, 0, 'Flat', '2024-11-09 07:57:18'),
(4, 26, 2.00, 3.00, 2, 'Flat', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_structures`
--

CREATE TABLE `application_structures` (
  `structure_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `structure_type` enum('Storage Facility','Worker Rest Area','Equipment Storage','Living Quarters','None') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_structures`
--

INSERT INTO `application_structures` (`structure_id`, `application_id`, `structure_type`) VALUES
(3, 25, 'Storage Facility'),
(4, 26, 'Storage Facility'),
(5, 26, 'Worker Rest Area');

-- --------------------------------------------------------

--
-- Table structure for table `application_tea_details`
--

CREATE TABLE `application_tea_details` (
  `tea_detail_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `plant_age` int(11) NOT NULL,
  `monthly_production` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_tea_details`
--

INSERT INTO `application_tea_details` (`tea_detail_id`, `application_id`, `plant_age`, `monthly_production`) VALUES
(4, 25, 2, 400),
(5, 26, 2, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `application_tea_varieties`
--

CREATE TABLE `application_tea_varieties` (
  `variety_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `variety_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_tea_varieties`
--

INSERT INTO `application_tea_varieties` (`variety_id`, `application_id`, `variety_name`) VALUES
(11, 25, 'TRI 2023'),
(12, 25, 'TRI 2025'),
(13, 26, 'TRI 2023');

-- --------------------------------------------------------

--
-- Table structure for table `application_water_sources`
--

CREATE TABLE `application_water_sources` (
  `water_source_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `source_type` enum('Natural Spring','Well','Stream/River','Rain Water','Public Water Supply') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_water_sources`
--

INSERT INTO `application_water_sources` (`water_source_id`, `application_id`, `source_type`) VALUES
(5, 25, 'Rain Water'),
(6, 25, 'Public Water Supply'),
(7, 26, 'Well'),
(8, 26, 'Public Water Supply');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Half-day','Leave') NOT NULL,
  `working_hours` decimal(4,2) DEFAULT NULL,
  `check_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `skeleton_id` int(11) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_quantity` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `team_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_skeletons`
--

CREATE TABLE `collection_skeletons` (
  `skeleton_id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_skeletons`
--

INSERT INTO `collection_skeletons` (`skeleton_id`, `route_id`, `team_id`, `vehicle_id`, `shift_id`, `created_at`, `is_active`) VALUES
(8, 1, 4, 1, 1, '2024-11-09 19:53:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crates`
--

CREATE TABLE `crates` (
  `crate_id` varchar(20) NOT NULL,
  `status` enum('Available','In Use','Maintenance','Retired') DEFAULT 'Available',
  `crate_size` enum('Small','Medium','Large') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crates`
--

INSERT INTO `crates` (`crate_id`, `status`, `crate_size`) VALUES
('CRATE001', 'Available', 'Medium'),
('CRATE002', 'In Use', 'Large'),
('CRATE003', 'Available', 'Small');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_fee` decimal(6,2) DEFAULT NULL,
  `tracking_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_metrics`
--

CREATE TABLE `daily_metrics` (
  `metrics_id` int(11) NOT NULL,
  `collection_date` date NOT NULL,
  `total_leaves` decimal(10,2) DEFAULT 0.00,
  `total_weight` decimal(10,2) DEFAULT 0.00,
  `total_crates` int(11) DEFAULT 0,
  `average_quality` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_metrics`
--

INSERT INTO `daily_metrics` (`metrics_id`, `collection_date`, `total_leaves`, `total_weight`, `total_crates`, `average_quality`, `created_at`) VALUES
(1, '2023-10-10', 1000.00, 91.25, 2, 4.50, '2024-11-02 07:14:30'),
(2, '2023-10-11', 1200.00, 80.00, 1, 4.00, '2024-11-02 07:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('NIC Copy','Photo','Ownership Proof','Survey Plan','Tax Receipt','Bank Statement','Passbook','Grama Certificate','Agrarian Certificate') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `user_id`, `document_type`, `file_path`, `upload_date`) VALUES
(1, 1, 'Photo', 'https://i.ytimg.com/vi/rHia1Kiwa0k/sddefault.jpg', '2024-11-04 15:10:04'),
(2, 3, 'Photo', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQK7qjttX5ka75LeZWFXC984Cm4XQ0NpZT4jg&s', '2024-11-04 16:04:57'),
(3, 17, 'Photo', 'https://i.ytimg.com/vi/D9YZw_X5UzQ/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCyeyOBvIr-5hwa2KhAa07uagHaJw', '2024-11-05 08:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `license_no` varchar(50) NOT NULL,
  `status` enum('Available','On Route','Off Duty') DEFAULT 'Available',
  `experience_years` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driver_id`, `employee_id`, `license_no`, `status`, `experience_years`) VALUES
(1, 5, 'DL123456', 'Available', 5),
(2, 6, 'DL123457', 'Available', 3),
(3, 13, '12313213213', 'Available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `driving_partners`
--

CREATE TABLE `driving_partners` (
  `partner_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('Available','On Route','Off Duty') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driving_partners`
--

INSERT INTO `driving_partners` (`partner_id`, `employee_id`, `status`) VALUES
(1, 7, 'Available'),
(2, 8, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('M','F','Other') DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `status` enum('Active','Inactive','On Leave') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `user_id`, `salary`, `hire_date`, `dob`, `gender`, `shift_id`, `contact_number`, `emergency_contact`, `status`) VALUES
(3, 3, 50000.00, '2021-06-10', '1990-03-05', 'F', 1, '0771234567', '0779876543', 'Active'),
(4, 4, 60000.00, '2020-10-20', '1988-07-11', 'M', 2, '0761234567', '0769876543', 'On Leave'),
(5, 1, 30000.00, '2022-01-15', '1985-06-20', 'M', 1, '0771234567', '0777654321', 'Active'),
(6, 2, 28000.00, '2022-02-20', '1990-09-15', 'F', 1, '0772345678', '0778765432', 'Active'),
(7, 3, 32000.00, '2022-01-10', '1988-11-30', 'M', 2, '0773456789', '0779876543', 'Active'),
(8, 4, 31000.00, '2022-03-05', '1992-12-25', 'F', 2, '0774567890', '0770987654', 'Active'),
(9, 13, 60000.00, '2022-01-01', '1980-01-15', 'M', NULL, '1234567890', '0987654321', 'Active'),
(10, 14, 55000.00, '2022-02-01', '1985-05-20', 'F', NULL, '1234567891', '0987654322', 'Active'),
(11, 15, 50000.00, '2022-03-01', '1978-07-25', 'M', NULL, '1234567892', '0987654323', 'Inactive'),
(12, 16, 52000.00, '2022-04-01', '1990-10-10', 'F', NULL, '1234567893', '0987654324', 'Active'),
(13, 17, 123.00, '2024-11-05', '2024-11-11', 'M', 1, '0779998336', '0779998336', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `Fertilizer`
--

CREATE TABLE `Fertilizer` (
  `id` int(11) NOT NULL,
  `fertilizer_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_inventory`
--

CREATE TABLE `fertilizer_inventory` (
  `inventory_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_orders`
--

CREATE TABLE `fertilizer_orders` (
  `order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Delivered','Cancelled') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_order_items`
--

CREATE TABLE `fertilizer_order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `fertilizer_type_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_types`
--

CREATE TABLE `fertilizer_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `recommended_usage` text DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `available_quantity` decimal(10,2) DEFAULT 0.00,
  `min_stock_level` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_managers`
--

CREATE TABLE `inventory_managers` (
  `inv_manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `land_details`
--

CREATE TABLE `land_details` (
  `land_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ownership_type` enum('Private Owner','Joint Owner','Lease Holder','Government Permit','Other') NOT NULL,
  `ownership_duration` int(11) NOT NULL,
  `total_land_area` decimal(5,2) NOT NULL,
  `tea_cultivation_area` decimal(5,2) NOT NULL,
  `elevation` decimal(5,2) NOT NULL,
  `slope` enum('Flat','Gentle','Moderate','Steep') NOT NULL,
  `plant_age` int(11) NOT NULL,
  `existing_production` decimal(10,2) NOT NULL,
  `water_sources` longtext NOT NULL,
  `access_road` enum('Paved Road','Gravel Road','Estate Road','Footpath Only') NOT NULL,
  `vehicle_access` enum('All Weather Access','Fair Weather Only','Limited Access','No Vehicle Access') NOT NULL,
  `structures` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land_details`
--

INSERT INTO `land_details` (`land_id`, `user_id`, `ownership_type`, `ownership_duration`, `total_land_area`, `tea_cultivation_area`, `elevation`, `slope`, `plant_age`, `existing_production`, `water_sources`, `access_road`, `vehicle_access`, `structures`) VALUES
(1, 5, 'Private Owner', 10, 50.00, 20.00, 600.00, 'Gentle', 5, 1500.00, 'Spring Water', 'Paved Road', 'All Weather Access', 'Tea Processing Shed');

-- --------------------------------------------------------

--
-- Table structure for table `land_inspections`
--

CREATE TABLE `land_inspections` (
  `inspection_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `inspection_date` date NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `type` enum('Supplier','Warehouse','Factory','Customer') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `machine_id` int(11) NOT NULL,
  `machine_type` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `status` enum('Operational','Maintenance','Broken') DEFAULT 'Operational',
  `next_maintenance_date` date DEFAULT NULL,
  `last_maintenance_date` date DEFAULT NULL,
  `installation_date` date NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_usage`
--

CREATE TABLE `machine_usage` (
  `usage_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `manager_type` enum('Vehicle Manager','Inventory Manager','Supplier Relations Manager','Employee Manager') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `employee_id`, `manager_type`, `status`) VALUES
(5, 9, 'Vehicle Manager', 'Active'),
(6, 10, 'Inventory Manager', 'Active'),
(7, 11, 'Supplier Relations Manager', 'Inactive'),
(8, 12, 'Employee Manager', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('Email','SMS','System') NOT NULL,
  `status` enum('Pending','Sent','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `operator_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `certification_level` varchar(50) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_name`, `location`, `details`, `code`, `price`, `profit`, `margin`, `quantity`, `unit`, `image_path`) VALUES
(5, 'red tea', 'warehouse-a', 'red', 'red', 12345.00, 23.00, 32.00, 12, 'item', '673a305a21a7b.png'),
(8, 'Tea', 'warehouse-b', 'super liyana', 'qwe', 2500.00, 500.00, 500.00, 20000, 'item', '673978432e964.svg'),
(9, 'asdfgh', 'warehouse-a', 'asdfgh', 'saas', 100.00, 300.00, 500.00, 20000, 'kg', '673a2f8ad49f9.png'),
(10, 'Lakshitha', 'warehouse-a', 'dfsdfs', 'sdfsdfs', 123456.00, 12.00, 12.00, 1999, 'item', '6739ab5919be0.png');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `batch_number` varchar(50) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_by_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(6, 'Driver'),
(8, 'Driving Partner'),
(3, 'Employee'),
(9, 'Employee Manager'),
(11, 'Inventory Manager'),
(4, 'Operator'),
(1, 'Super Admin'),
(5, 'Supplier'),
(2, 'Supplier Manager'),
(10, 'Vehicle Manager'),
(7, 'Website User');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) DEFAULT NULL,
  `start_location_lat` decimal(10,8) DEFAULT NULL,
  `start_location_long` decimal(11,8) DEFAULT NULL,
  `end_location_lat` decimal(10,8) DEFAULT NULL,
  `end_location_long` decimal(11,8) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `number_of_suppliers` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `route_name`, `start_location_lat`, `start_location_long`, `end_location_lat`, `end_location_long`, `date`, `number_of_suppliers`, `status`, `is_deleted`) VALUES
(8, 'murshid bawa', 6.21730370, 80.25386360, 6.21730370, 80.25386360, '2024-11-07', 2, 'Active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `shift_name`, `shift_start`, `shift_end`) VALUES
(1, 'Evening Shift', '16:00:00', '19:00:00'),
(2, 'Night Shift', '18:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_applications`
--

CREATE TABLE `supplier_applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `primary_phone` varchar(15) NOT NULL,
  `secondary_phone` varchar(15) DEFAULT NULL,
  `whatsapp_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_applications`
--

INSERT INTO `supplier_applications` (`application_id`, `user_id`, `status`, `primary_phone`, `secondary_phone`, `whatsapp_number`, `created_at`, `updated_at`) VALUES
(25, 21, 'pending', '0779998336', NULL, NULL, '2024-11-09 07:57:18', '2024-11-09 07:57:18'),
(26, 17, 'pending', '0779998336', NULL, NULL, '2024-11-09 12:38:22', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_bank_info`
--

CREATE TABLE `supplier_bank_info` (
  `bank_info_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `account_holder_name` varchar(255) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_type` enum('Savings','Current') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_bank_info`
--

INSERT INTO `supplier_bank_info` (`bank_info_id`, `application_id`, `account_holder_name`, `bank_name`, `branch_name`, `account_number`, `account_type`, `created_at`) VALUES
(3, 25, 'Simaak Niyaz', 'Sampath Bank', 'Thimbirigasyaya', '21313221312321', 'Current', '2024-11-09 07:57:18'),
(4, 26, '213213', 'Bank of Ceylon', '21321321', '2121321321321', 'Current', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `driver_id`, `partner_id`, `manager_id`, `status`, `created_at`, `is_visible`) VALUES
(3, 'Team Alpha', 1, 1, 5, 'Active', '2024-11-02 09:27:39', 1),
(4, 'Team Beta', 3, 2, 5, 'Active', '2024-11-02 09:27:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_progress`
--

CREATE TABLE `team_progress` (
  `progress_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tea_leaves_inventory`
--

CREATE TABLE `tea_leaves_inventory` (
  `inventory_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quality_grade` enum('A','B','C','D') NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `received_date` date NOT NULL,
  `processing_status` enum('Raw','Processing','Processed') DEFAULT 'Raw'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tea_products`
--

CREATE TABLE `tea_products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Black','Green','White','Oolong','Blend') NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `min_stock_level` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Available','Out of Stock','Discontinued') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` enum('Pending','Approved','Rejected','None') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `nic`, `date_of_birth`, `gender`, `role_id`, `created_at`, `approval_status`) VALUES
(1, 'superadmin@example.com', 'password_hash_1', 'Alice', 'Wright', '123456789V', '1985-04-20', 'Female', 1, '2024-11-02 05:32:54', 'Approved'),
(2, 'manager@example.com', 'password_hash_2', 'Bob', 'Green', '987654321V', '1990-07-15', 'Male', 2, '2024-11-02 05:32:54', 'Approved'),
(3, 'employee@example.com', 'password_hash_3', 'Clara', 'Stone', '543216789V', '1992-03-30', 'Female', 3, '2024-11-02 05:32:54', 'Approved'),
(4, 'operator@example.com', 'password_hash_4', 'Derek', 'Hall', '654321987V', '1988-12-01', 'Male', 4, '2024-11-02 05:32:54', 'Pending'),
(5, 'supplier@example.com', 'password_hash_5', 'John', 'Doe', '789456123V', '1980-11-11', 'Male', 5, '2024-11-02 05:32:54', 'Approved'),
(6, 'driver@example.com', 'password_hash_6', 'Peter', 'Black', '123987654V', '1995-06-25', 'Male', 6, '2024-11-02 05:32:54', 'Approved'),
(7, 'customer@example.com', 'password_hash_7', 'Liam', 'Lee', '456123789V', '1998-09-09', 'Male', 7, '2024-11-02 05:32:54', 'Pending'),
(8, 'drivingpartner@example.com', 'password_hash_8', 'Emma', 'Johnson', '321654987V', '1989-02-14', 'Female', 8, '2024-11-02 05:32:54', 'Approved'),
(9, 'driver1@example.com', 'hashed_password1', 'John', 'Doe', 'NIC001', '1985-06-20', 'Male', 2, '2024-11-02 08:03:55', 'Approved'),
(10, 'driver2@example.com', 'hashed_password2', 'Jane', 'Smith', 'NIC002', '1990-09-15', 'Female', 2, '2024-11-02 08:03:55', 'Approved'),
(11, 'partner1@example.com', 'hashed_password3', 'Michael', 'Johnson', 'NIC003', '1988-11-30', 'Male', 3, '2024-11-02 08:03:55', 'Approved'),
(12, 'partner2@example.com', 'hashed_password4', 'Emily', 'Davis', 'NIC004', '1992-12-25', 'Female', 3, '2024-11-02 08:03:55', 'Approved'),
(13, 'vehicle_manager@example.com', 'password123', 'John', 'Doe', 'NIC123456V', '1980-01-15', 'Male', 2, '2024-11-02 09:19:45', 'Approved'),
(14, 'inventory_manager@example.com', 'password123', 'Jane', 'Smith', 'NIC123457V', '1985-05-20', 'Female', 2, '2024-11-02 09:19:45', 'Approved'),
(15, 'supplier_manager@example.com', 'password123', 'Jim', 'Beam', 'NIC123458V', '1978-07-25', 'Male', 2, '2024-11-02 09:19:45', 'Approved'),
(16, 'employee_manager@example.com', 'password123', 'Sara', 'Connor', 'NIC123459V', '1990-10-10', 'Female', 2, '2024-11-02 09:19:45', 'Approved'),
(17, 'babydriver@gmail.com', '$2y$10$RYkHOo5LwYABMTU0RzB89.9AjqH8ENqFRp2YSvgPSputSCtJ1O3nm', 'Baby', 'Driver', '200206400133', '2002-03-04', 'Male', 6, '2024-11-05 08:03:43', 'Approved'),
(18, 'fortniteman@gmail.com', 'pog', 'fortnite', 'man', '200206400132', '2024-11-01', 'Male', 5, '2024-11-05 12:42:11', 'Approved'),
(20, 'birdy@gmail.com', '$2y$10$1gkBt1HCCIx2.Y7k329zReVlBLRrNtA63P6DaGE9HAZBvvU1svhH6', 'Birdy', 'Wings', '123213214', '2018-06-12', 'Female', 1, '2024-11-06 14:52:09', 'Approved'),
(21, 'becomesupplier@gmail.com', '$2y$10$SmrFz1JWLszj3uRH3qR3MOTDyH5n/FT6Q7zl/hMT6pxBoO9TdEYNe', 'Become', 'Supplier', '3143143131', '2016-01-04', 'Male', 7, '2024-11-07 19:49:36', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `district` enum('Kandy','Nuwara Eliya','Badulla','Ratnapura','Galle','Matara','Kalutara','Other') NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `gps_latitude` decimal(9,6) DEFAULT NULL,
  `gps_longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE `user_contacts` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `primary_phone` varchar(10) NOT NULL,
  `secondary_phone` varchar(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `whatsapp_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `file_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` (`photo_id`, `user_id`, `file_name`, `upload_date`, `is_active`, `file_type`) VALUES
(1, 4, '150', '2024-11-09 20:57:05', 1, 'jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `status` enum('Available','In Use','Maintenance') DEFAULT 'Available',
  `owner_name` varchar(100) DEFAULT NULL,
  `owner_contact` varchar(15) DEFAULT NULL,
  `capacity` decimal(8,2) DEFAULT NULL,
  `vehicle_type` enum('Truck','Van','Car','Bus','Three-Wheeler','Other') DEFAULT NULL,
  `insurance_expiry_date` date DEFAULT NULL,
  `road_tax_expiry_date` date DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `engine_number` varchar(50) DEFAULT NULL,
  `chassis_number` varchar(50) DEFAULT NULL,
  `seating_capacity` int(11) DEFAULT NULL,
  `condition` enum('New','Good','Fair','Poor') DEFAULT NULL,
  `last_serviced_date` date DEFAULT NULL,
  `last_maintenance` date DEFAULT NULL,
  `next_maintenance` date DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel_type` enum('Petrol','Diesel','Electric','Hybrid') DEFAULT 'Petrol',
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `license_plate`, `status`, `owner_name`, `owner_contact`, `capacity`, `vehicle_type`, `insurance_expiry_date`, `road_tax_expiry_date`, `color`, `engine_number`, `chassis_number`, `seating_capacity`, `condition`, `last_serviced_date`, `last_maintenance`, `next_maintenance`, `mileage`, `fuel_type`, `registration_date`) VALUES
(1, 'WP-1234', 'Available', 'John Silva', '0771234567', 1000.50, 'Truck', '2025-12-31', '2024-11-30', 'Blue', 'EN1234567890', 'CH1234567890', 2, 'Good', '2023-10-01', '2023-10-01', '2024-10-01', 15000, 'Diesel', '2022-01-15'),
(2, 'WP-5678', 'Available', 'Nimal Perera', '0719876543', 500.75, 'Van', '2024-06-30', '2023-12-31', 'White', 'EN0987654321', 'CH0987654321', 8, 'New', '2023-09-15', '2023-09-15', '2024-09-15', 5000, 'Petrol', '2021-05-20'),
(7, 'AB-1234', 'Available', 'John Silva', '0771234567', 213.00, 'Truck', '2024-11-06', '2024-10-29', 'Red', 'EN1234567890', 'CH1234567890', 2, 'Fair', '2024-10-29', '2024-11-08', '2024-11-05', 213, 'Diesel', '2024-11-28');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_documents`
--

CREATE TABLE `vehicle_documents` (
  `vehicle_document_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `document_type` enum('Service Record','Insurance','Road Tax','Maintenance Record','Image','Ownership Proof') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_documents`
--

INSERT INTO `vehicle_documents` (`vehicle_document_id`, `vehicle_id`, `document_type`, `file_path`, `upload_date`) VALUES
(1, 1, 'Image', 'https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg', '2024-11-03 14:13:09'),
(2, 2, 'Image', 'https://i.ikman-st.com/mazda-bongo-1997-for-sale-puttalam-2/cdd5b09e-ab3f-42c4-8642-575b1bc9072b/620/466/fitted.jpg', '2024-11-03 15:16:15'),
(3, 7, 'Image', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrLhTwmjDEshchAYXpCwmtkDEh4ywp6MOQrA&s', '2024-11-03 18:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_managers`
--

CREATE TABLE `vehicle_managers` (
  `vehicle_manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owner_history`
--

CREATE TABLE `vehicle_owner_history` (
  `history_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_contact` varchar(15) DEFAULT NULL,
  `ownership_start_date` date NOT NULL,
  `ownership_end_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `vehicle_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_service_records`
--

CREATE TABLE `vehicle_service_records` (
  `record_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `service_type` enum('Routine','Repair','Inspection','Upgrade') NOT NULL,
  `service_center` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `next_service_due` date DEFAULT NULL,
  `vehicle_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` decimal(10,2) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive','Maintenance') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `worker_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_addresses`
--
ALTER TABLE `application_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `application_addresses_ibfk_1` (`application_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `application_documents_ibfk_1` (`application_id`);

--
-- Indexes for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  ADD PRIMARY KEY (`infrastructure_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  ADD PRIMARY KEY (`ownership_id`),
  ADD KEY `application_ownership_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_property_details`
--
ALTER TABLE `application_property_details`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `application_property_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_structures`
--
ALTER TABLE `application_structures`
  ADD PRIMARY KEY (`structure_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  ADD PRIMARY KEY (`tea_detail_id`),
  ADD KEY `application_tea_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  ADD PRIMARY KEY (`variety_id`),
  ADD KEY `application_tea_varieties_ibfk_1` (`application_id`);

--
-- Indexes for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  ADD PRIMARY KEY (`water_source_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `skeleton_id` (`skeleton_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  ADD PRIMARY KEY (`skeleton_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `crates`
--
ALTER TABLE `crates`
  ADD PRIMARY KEY (`crate_id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `daily_metrics`
--
ALTER TABLE `daily_metrics`
  ADD PRIMARY KEY (`metrics_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `license_no` (`license_no`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `driving_partners`
--
ALTER TABLE `driving_partners`
  ADD PRIMARY KEY (`partner_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`verification_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `Fertilizer`
--
ALTER TABLE `Fertilizer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fertilizer_type_id` (`fertilizer_type_id`);

--
-- Indexes for table `fertilizer_types`
--
ALTER TABLE `fertilizer_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  ADD PRIMARY KEY (`inv_manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `land_details`
--
ALTER TABLE `land_details`
  ADD PRIMARY KEY (`land_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `land_inspections`
--
ALTER TABLE `land_inspections`
  ADD PRIMARY KEY (`inspection_id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`machine_id`);

--
-- Indexes for table `machine_usage`
--
ALTER TABLE `machine_usage`
  ADD PRIMARY KEY (`usage_id`),
  ADD KEY `machine_id` (`machine_id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`operator_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `supplier_applications_ibfk_1` (`user_id`);

--
-- Indexes for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  ADD PRIMARY KEY (`bank_info_id`),
  ADD UNIQUE KEY `unique_account_number` (`account_number`),
  ADD KEY `idx_application_id` (`application_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `partner_id` (`partner_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `tea_products`
--
ALTER TABLE `tea_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- Indexes for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  ADD PRIMARY KEY (`vehicle_document_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  ADD PRIMARY KEY (`vehicle_manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_document_id` (`vehicle_document_id`);

--
-- Indexes for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_document_id` (`vehicle_document_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_addresses`
--
ALTER TABLE `application_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  MODIFY `infrastructure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  MODIFY `ownership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `application_property_details`
--
ALTER TABLE `application_property_details`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_structures`
--
ALTER TABLE `application_structures`
  MODIFY `structure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  MODIFY `tea_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  MODIFY `variety_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  MODIFY `water_source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  MODIFY `skeleton_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_metrics`
--
ALTER TABLE `daily_metrics`
  MODIFY `metrics_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driving_partners`
--
ALTER TABLE `driving_partners`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Fertilizer`
--
ALTER TABLE `Fertilizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_types`
--
ALTER TABLE `fertilizer_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  MODIFY `inv_manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `land_details`
--
ALTER TABLE `land_details`
  MODIFY `land_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `land_inspections`
--
ALTER TABLE `land_inspections`
  MODIFY `inspection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_usage`
--
ALTER TABLE `machine_usage`
  MODIFY `usage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `operator_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  MODIFY `bank_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team_progress`
--
ALTER TABLE `team_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tea_products`
--
ALTER TABLE `tea_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contacts`
--
ALTER TABLE `user_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  MODIFY `vehicle_document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  MODIFY `vehicle_manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application_addresses`
--
ALTER TABLE `application_addresses`
  ADD CONSTRAINT `application_addresses_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `application_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  ADD CONSTRAINT `application_infrastructure_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  ADD CONSTRAINT `application_ownership_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_property_details`
--
ALTER TABLE `application_property_details`
  ADD CONSTRAINT `application_property_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_structures`
--
ALTER TABLE `application_structures`
  ADD CONSTRAINT `application_structures_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  ADD CONSTRAINT `application_tea_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  ADD CONSTRAINT `application_tea_varieties_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  ADD CONSTRAINT `application_water_sources_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`skeleton_id`) REFERENCES `collection_skeletons` (`skeleton_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collections_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collections_ibfk_4` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  ADD CONSTRAINT `collection_skeletons_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collection_skeletons_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collection_skeletons_ibfk_4` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `driving_partners`
--
ALTER TABLE `driving_partners`
  ADD CONSTRAINT `driving_partners_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD CONSTRAINT `email_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`);

--
-- Constraints for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD CONSTRAINT `fertilizer_inventory_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `fertilizer_types` (`type_id`),
  ADD CONSTRAINT `fertilizer_inventory_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  ADD CONSTRAINT `fertilizer_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  ADD CONSTRAINT `fertilizer_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `fertilizer_orders` (`order_id`),
  ADD CONSTRAINT `fertilizer_order_items_ibfk_2` FOREIGN KEY (`fertilizer_type_id`) REFERENCES `fertilizer_types` (`type_id`);

--
-- Constraints for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  ADD CONSTRAINT `inventory_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `land_details`
--
ALTER TABLE `land_details`
  ADD CONSTRAINT `land_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `land_inspections`
--
ALTER TABLE `land_inspections`
  ADD CONSTRAINT `land_inspections_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`),
  ADD CONSTRAINT `land_inspections_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `machine_usage`
--
ALTER TABLE `machine_usage`
  ADD CONSTRAINT `machine_usage_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`),
  ADD CONSTRAINT `machine_usage_ibfk_2` FOREIGN KEY (`operator_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `operators`
--
ALTER TABLE `operators`
  ADD CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `customer_orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tea_products` (`product_id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tea_products` (`product_id`),
  ADD CONSTRAINT `product_inventory_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `refresh_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`);

--
-- Constraints for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  ADD CONSTRAINT `supplier_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  ADD CONSTRAINT `supplier_bank_info_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`),
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`partner_id`) REFERENCES `driving_partners` (`partner_id`),
  ADD CONSTRAINT `teams_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD CONSTRAINT `team_progress_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_progress_ibfk_2` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE CASCADE;

--
-- Constraints for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  ADD CONSTRAINT `tea_leaves_inventory_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD CONSTRAINT `user_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD CONSTRAINT `user_photos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  ADD CONSTRAINT `vehicle_documents_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  ADD CONSTRAINT `vehicle_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  ADD CONSTRAINT `vehicle_owner_history_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_owner_history_ibfk_2` FOREIGN KEY (`vehicle_document_id`) REFERENCES `vehicle_documents` (`vehicle_document_id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  ADD CONSTRAINT `vehicle_service_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_service_records_ibfk_2` FOREIGN KEY (`vehicle_document_id`) REFERENCES `vehicle_documents` (`vehicle_document_id`) ON DELETE SET NULL;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `inventory_managers` (`inv_manager_id`);

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2024 at 07:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tfms`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_addresses`
--

CREATE TABLE `application_addresses` (
  `address_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_addresses`
--

INSERT INTO `application_addresses` (`address_id`, `application_id`, `line1`, `line2`, `city`, `district`, `postal_code`, `latitude`, `longitude`) VALUES
(12, 25, 'mongo', NULL, 'ff', 'Other', '500', 6.89086094, 79.86760449),
(13, 26, 'mongo', NULL, 'ff', 'Kandy', '500', 7.54810667, 80.52557314);

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `document_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` enum('nic','ownership_proof','tax_receipts','bank_passbook','grama_cert') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`document_id`, `application_id`, `document_type`, `file_path`, `uploaded_at`) VALUES
(31, 25, 'nic', 'uploads/supplier_documents/1731139038_nic_672f15deabd16.png', '2024-11-09 07:57:18'),
(32, 25, 'ownership_proof', 'uploads/supplier_documents/1731139038_ownership_proof_672f15deac28a.pdf', '2024-11-09 07:57:18'),
(33, 25, 'tax_receipts', 'uploads/supplier_documents/1731139038_tax_receipts_672f15deac70d.png', '2024-11-09 07:57:18'),
(34, 25, 'bank_passbook', 'uploads/supplier_documents/1731139038_bank_passbook_672f15dead06e.png', '2024-11-09 07:57:18'),
(35, 25, 'grama_cert', 'uploads/supplier_documents/1731139038_grama_cert_672f15dead514.png', '2024-11-09 07:57:18'),
(36, 26, 'nic', 'uploads/supplier_documents/1731155902_nic_672f57be8489d.png', '2024-11-09 12:38:22'),
(37, 26, 'ownership_proof', 'uploads/supplier_documents/1731155902_ownership_proof_672f57be84f5d.pdf', '2024-11-09 12:38:22'),
(38, 26, 'tax_receipts', 'uploads/supplier_documents/1731155902_tax_receipts_672f57be851fb.png', '2024-11-09 12:38:22'),
(39, 26, 'bank_passbook', 'uploads/supplier_documents/1731155902_bank_passbook_672f57be856a3.png', '2024-11-09 12:38:22'),
(40, 26, 'grama_cert', 'uploads/supplier_documents/1731155902_grama_cert_672f57be86f2b.png', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_infrastructure`
--

CREATE TABLE `application_infrastructure` (
  `infrastructure_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `access_road` enum('Paved Road','Gravel Road','Estate Road','Footpath Only') NOT NULL,
  `vehicle_access` enum('All Weather Access','Fair Weather Only','Limited Access','No Vehicle Access') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_infrastructure`
--

INSERT INTO `application_infrastructure` (`infrastructure_id`, `application_id`, `access_road`, `vehicle_access`, `created_at`) VALUES
(3, 25, 'Paved Road', 'All Weather Access', '2024-11-09 07:57:18'),
(4, 26, 'Gravel Road', 'All Weather Access', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_ownership_details`
--

CREATE TABLE `application_ownership_details` (
  `ownership_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `ownership_type` varchar(50) NOT NULL,
  `ownership_duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_ownership_details`
--

INSERT INTO `application_ownership_details` (`ownership_id`, `application_id`, `ownership_type`, `ownership_duration`) VALUES
(5, 25, 'Private Owner', 2),
(6, 26, 'Private Owner', 2);

-- --------------------------------------------------------

--
-- Table structure for table `application_property_details`
--

CREATE TABLE `application_property_details` (
  `property_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `total_land_area` decimal(10,2) NOT NULL,
  `tea_cultivation_area` decimal(10,2) NOT NULL,
  `elevation` int(11) NOT NULL,
  `slope` enum('Flat','Gentle','Moderate','Steep') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_property_details`
--

INSERT INTO `application_property_details` (`property_id`, `application_id`, `total_land_area`, `tea_cultivation_area`, `elevation`, `slope`, `created_at`) VALUES
(3, 25, 2.00, 2.00, 0, 'Flat', '2024-11-09 07:57:18'),
(4, 26, 2.00, 3.00, 2, 'Flat', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `application_structures`
--

CREATE TABLE `application_structures` (
  `structure_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `structure_type` enum('Storage Facility','Worker Rest Area','Equipment Storage','Living Quarters','None') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_structures`
--

INSERT INTO `application_structures` (`structure_id`, `application_id`, `structure_type`) VALUES
(3, 25, 'Storage Facility'),
(4, 26, 'Storage Facility'),
(5, 26, 'Worker Rest Area');

-- --------------------------------------------------------

--
-- Table structure for table `application_tea_details`
--

CREATE TABLE `application_tea_details` (
  `tea_detail_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `plant_age` int(11) NOT NULL,
  `monthly_production` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_tea_details`
--

INSERT INTO `application_tea_details` (`tea_detail_id`, `application_id`, `plant_age`, `monthly_production`) VALUES
(4, 25, 2, 400),
(5, 26, 2, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `application_tea_varieties`
--

CREATE TABLE `application_tea_varieties` (
  `variety_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `variety_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_tea_varieties`
--

INSERT INTO `application_tea_varieties` (`variety_id`, `application_id`, `variety_name`) VALUES
(11, 25, 'TRI 2023'),
(12, 25, 'TRI 2025'),
(13, 26, 'TRI 2023');

-- --------------------------------------------------------

--
-- Table structure for table `application_water_sources`
--

CREATE TABLE `application_water_sources` (
  `water_source_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `source_type` enum('Natural Spring','Well','Stream/River','Rain Water','Public Water Supply') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_water_sources`
--

INSERT INTO `application_water_sources` (`water_source_id`, `application_id`, `source_type`) VALUES
(5, 25, 'Rain Water'),
(6, 25, 'Public Water Supply'),
(7, 26, 'Well'),
(8, 26, 'Public Water Supply');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Half-day','Leave') NOT NULL,
  `working_hours` decimal(4,2) DEFAULT NULL,
  `check_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `skeleton_id` int(11) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_quantity` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `team_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_skeletons`
--

CREATE TABLE `collection_skeletons` (
  `skeleton_id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_skeletons`
--

INSERT INTO `collection_skeletons` (`skeleton_id`, `route_id`, `team_id`, `vehicle_id`, `shift_id`, `created_at`, `is_active`) VALUES
(8, 1, 4, 1, 1, '2024-11-09 19:53:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crates`
--

CREATE TABLE `crates` (
  `crate_id` varchar(20) NOT NULL,
  `status` enum('Available','In Use','Maintenance','Retired') DEFAULT 'Available',
  `crate_size` enum('Small','Medium','Large') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crates`
--

INSERT INTO `crates` (`crate_id`, `status`, `crate_size`) VALUES
('CRATE001', 'Available', 'Medium'),
('CRATE002', 'In Use', 'Large'),
('CRATE003', 'Available', 'Small');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_fee` decimal(6,2) DEFAULT NULL,
  `tracking_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_metrics`
--

CREATE TABLE `daily_metrics` (
  `metrics_id` int(11) NOT NULL,
  `collection_date` date NOT NULL,
  `total_leaves` decimal(10,2) DEFAULT 0.00,
  `total_weight` decimal(10,2) DEFAULT 0.00,
  `total_crates` int(11) DEFAULT 0,
  `average_quality` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_metrics`
--

INSERT INTO `daily_metrics` (`metrics_id`, `collection_date`, `total_leaves`, `total_weight`, `total_crates`, `average_quality`, `created_at`) VALUES
(1, '2023-10-10', 1000.00, 91.25, 2, 4.50, '2024-11-02 07:14:30'),
(2, '2023-10-11', 1200.00, 80.00, 1, 4.00, '2024-11-02 07:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('NIC Copy','Photo','Ownership Proof','Survey Plan','Tax Receipt','Bank Statement','Passbook','Grama Certificate','Agrarian Certificate') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `user_id`, `document_type`, `file_path`, `upload_date`) VALUES
(1, 1, 'Photo', 'https://i.ytimg.com/vi/rHia1Kiwa0k/sddefault.jpg', '2024-11-04 15:10:04'),
(2, 3, 'Photo', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQK7qjttX5ka75LeZWFXC984Cm4XQ0NpZT4jg&s', '2024-11-04 16:04:57'),
(3, 17, 'Photo', 'https://i.ytimg.com/vi/D9YZw_X5UzQ/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCyeyOBvIr-5hwa2KhAa07uagHaJw', '2024-11-05 08:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `license_no` varchar(50) NOT NULL,
  `status` enum('Available','On Route','Off Duty') DEFAULT 'Available',
  `experience_years` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driver_id`, `employee_id`, `license_no`, `status`, `experience_years`) VALUES
(1, 5, 'DL123456', 'Available', 5),
(2, 6, 'DL123457', 'Available', 3),
(3, 13, '12313213213', 'Available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `driving_partners`
--

CREATE TABLE `driving_partners` (
  `partner_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('Available','On Route','Off Duty') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driving_partners`
--

INSERT INTO `driving_partners` (`partner_id`, `employee_id`, `status`) VALUES
(1, 7, 'Available'),
(2, 8, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('M','F','Other') DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `status` enum('Active','Inactive','On Leave') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `user_id`, `salary`, `hire_date`, `dob`, `gender`, `shift_id`, `contact_number`, `emergency_contact`, `status`) VALUES
(3, 3, 50000.00, '2021-06-10', '1990-03-05', 'F', 1, '0771234567', '0779876543', 'Active'),
(4, 4, 60000.00, '2020-10-20', '1988-07-11', 'M', 2, '0761234567', '0769876543', 'On Leave'),
(5, 1, 30000.00, '2022-01-15', '1985-06-20', 'M', 1, '0771234567', '0777654321', 'Active'),
(6, 2, 28000.00, '2022-02-20', '1990-09-15', 'F', 1, '0772345678', '0778765432', 'Active'),
(7, 3, 32000.00, '2022-01-10', '1988-11-30', 'M', 2, '0773456789', '0779876543', 'Active'),
(8, 4, 31000.00, '2022-03-05', '1992-12-25', 'F', 2, '0774567890', '0770987654', 'Active'),
(9, 13, 60000.00, '2022-01-01', '1980-01-15', 'M', NULL, '1234567890', '0987654321', 'Active'),
(10, 14, 55000.00, '2022-02-01', '1985-05-20', 'F', NULL, '1234567891', '0987654322', 'Active'),
(11, 15, 50000.00, '2022-03-01', '1978-07-25', 'M', NULL, '1234567892', '0987654323', 'Inactive'),
(12, 16, 52000.00, '2022-04-01', '1990-10-10', 'F', NULL, '1234567893', '0987654324', 'Active'),
(13, 17, 123.00, '2024-11-05', '2024-11-11', 'M', 1, '0779998336', '0779998336', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `Fertilizer`
--

CREATE TABLE `Fertilizer` (
  `id` int(11) NOT NULL,
  `fertilizer_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_inventory`
--

CREATE TABLE `fertilizer_inventory` (
  `inventory_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_orders`
--

CREATE TABLE `fertilizer_orders` (
  `order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Delivered','Cancelled') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_order_items`
--

CREATE TABLE `fertilizer_order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `fertilizer_type_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_types`
--

CREATE TABLE `fertilizer_types` (
  `type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `recommended_usage` text DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `available_quantity` decimal(10,2) DEFAULT 0.00,
  `min_stock_level` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_managers`
--

CREATE TABLE `inventory_managers` (
  `inv_manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `land_details`
--

CREATE TABLE `land_details` (
  `land_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ownership_type` enum('Private Owner','Joint Owner','Lease Holder','Government Permit','Other') NOT NULL,
  `ownership_duration` int(11) NOT NULL,
  `total_land_area` decimal(5,2) NOT NULL,
  `tea_cultivation_area` decimal(5,2) NOT NULL,
  `elevation` decimal(5,2) NOT NULL,
  `slope` enum('Flat','Gentle','Moderate','Steep') NOT NULL,
  `plant_age` int(11) NOT NULL,
  `existing_production` decimal(10,2) NOT NULL,
  `water_sources` longtext NOT NULL,
  `access_road` enum('Paved Road','Gravel Road','Estate Road','Footpath Only') NOT NULL,
  `vehicle_access` enum('All Weather Access','Fair Weather Only','Limited Access','No Vehicle Access') NOT NULL,
  `structures` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land_details`
--

INSERT INTO `land_details` (`land_id`, `user_id`, `ownership_type`, `ownership_duration`, `total_land_area`, `tea_cultivation_area`, `elevation`, `slope`, `plant_age`, `existing_production`, `water_sources`, `access_road`, `vehicle_access`, `structures`) VALUES
(1, 5, 'Private Owner', 10, 50.00, 20.00, 600.00, 'Gentle', 5, 1500.00, 'Spring Water', 'Paved Road', 'All Weather Access', 'Tea Processing Shed');

-- --------------------------------------------------------

--
-- Table structure for table `land_inspections`
--

CREATE TABLE `land_inspections` (
  `inspection_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `inspection_date` date NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `type` enum('Supplier','Warehouse','Factory','Customer') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `machine_id` int(11) NOT NULL,
  `machine_type` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `status` enum('Operational','Maintenance','Broken') DEFAULT 'Operational',
  `next_maintenance_date` date DEFAULT NULL,
  `last_maintenance_date` date DEFAULT NULL,
  `installation_date` date NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_usage`
--

CREATE TABLE `machine_usage` (
  `usage_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `manager_type` enum('Vehicle Manager','Inventory Manager','Supplier Relations Manager','Employee Manager') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `employee_id`, `manager_type`, `status`) VALUES
(5, 9, 'Vehicle Manager', 'Active'),
(6, 10, 'Inventory Manager', 'Active'),
(7, 11, 'Supplier Relations Manager', 'Inactive'),
(8, 12, 'Employee Manager', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('Email','SMS','System') NOT NULL,
  `status` enum('Pending','Sent','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `operator_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `certification_level` varchar(50) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) DEFAULT NULL,
  `margin` decimal(5,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_name`, `location`, `details`, `code`, `price`, `profit`, `margin`, `quantity`, `unit`, `image_path`) VALUES
(5, 'red tea', 'warehouse-a', 'red', 'red', 12345.00, 23.00, 32.00, 12, 'item', '673a305a21a7b.png'),
(8, 'Tea', 'warehouse-b', 'super liyana', 'qwe', 2500.00, 500.00, 500.00, 20000, 'item', '673978432e964.svg'),
(9, 'asdfgh', 'warehouse-a', 'asdfgh', 'saas', 100.00, 300.00, 500.00, 20000, 'kg', '673a2f8ad49f9.png'),
(10, 'Lakshitha', 'warehouse-a', 'dfsdfs', 'sdfsdfs', 123456.00, 12.00, 12.00, 1999, 'item', '6739ab5919be0.png');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `batch_number` varchar(50) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_by_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(6, 'Driver'),
(8, 'Driving Partner'),
(3, 'Employee'),
(9, 'Employee Manager'),
(11, 'Inventory Manager'),
(4, 'Operator'),
(1, 'Super Admin'),
(5, 'Supplier'),
(2, 'Supplier Manager'),
(10, 'Vehicle Manager'),
(7, 'Website User');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) DEFAULT NULL,
  `start_location_lat` decimal(10,8) DEFAULT NULL,
  `start_location_long` decimal(11,8) DEFAULT NULL,
  `end_location_lat` decimal(10,8) DEFAULT NULL,
  `end_location_long` decimal(11,8) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `number_of_suppliers` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `route_name`, `start_location_lat`, `start_location_long`, `end_location_lat`, `end_location_long`, `date`, `number_of_suppliers`, `status`, `is_deleted`) VALUES
(8, 'murshid bawa', 6.21730370, 80.25386360, 6.21730370, 80.25386360, '2024-11-07', 2, 'Active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `shift_name`, `shift_start`, `shift_end`) VALUES
(1, 'Evening Shift', '16:00:00', '19:00:00'),
(2, 'Night Shift', '18:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_applications`
--

CREATE TABLE `supplier_applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `primary_phone` varchar(15) NOT NULL,
  `secondary_phone` varchar(15) DEFAULT NULL,
  `whatsapp_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_applications`
--

INSERT INTO `supplier_applications` (`application_id`, `user_id`, `status`, `primary_phone`, `secondary_phone`, `whatsapp_number`, `created_at`, `updated_at`) VALUES
(25, 21, 'pending', '0779998336', NULL, NULL, '2024-11-09 07:57:18', '2024-11-09 07:57:18'),
(26, 17, 'pending', '0779998336', NULL, NULL, '2024-11-09 12:38:22', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_bank_info`
--

CREATE TABLE `supplier_bank_info` (
  `bank_info_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `account_holder_name` varchar(255) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_type` enum('Savings','Current') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_bank_info`
--

INSERT INTO `supplier_bank_info` (`bank_info_id`, `application_id`, `account_holder_name`, `bank_name`, `branch_name`, `account_number`, `account_type`, `created_at`) VALUES
(3, 25, 'Simaak Niyaz', 'Sampath Bank', 'Thimbirigasyaya', '21313221312321', 'Current', '2024-11-09 07:57:18'),
(4, 26, '213213', 'Bank of Ceylon', '21321321', '2121321321321', 'Current', '2024-11-09 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `driver_id`, `partner_id`, `manager_id`, `status`, `created_at`, `is_visible`) VALUES
(3, 'Team Alpha', 1, 1, 5, 'Active', '2024-11-02 09:27:39', 1),
(4, 'Team Beta', 3, 2, 5, 'Active', '2024-11-02 09:27:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_progress`
--

CREATE TABLE `team_progress` (
  `progress_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tea_leaves_inventory`
--

CREATE TABLE `tea_leaves_inventory` (
  `inventory_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quality_grade` enum('A','B','C','D') NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `received_date` date NOT NULL,
  `processing_status` enum('Raw','Processing','Processed') DEFAULT 'Raw'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tea_products`
--

CREATE TABLE `tea_products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Black','Green','White','Oolong','Blend') NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `min_stock_level` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Available','Out of Stock','Discontinued') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` enum('Pending','Approved','Rejected','None') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `nic`, `date_of_birth`, `gender`, `role_id`, `created_at`, `approval_status`) VALUES
(1, 'superadmin@example.com', 'password_hash_1', 'Alice', 'Wright', '123456789V', '1985-04-20', 'Female', 1, '2024-11-02 05:32:54', 'Approved'),
(2, 'manager@example.com', 'password_hash_2', 'Bob', 'Green', '987654321V', '1990-07-15', 'Male', 2, '2024-11-02 05:32:54', 'Approved'),
(3, 'employee@example.com', 'password_hash_3', 'Clara', 'Stone', '543216789V', '1992-03-30', 'Female', 3, '2024-11-02 05:32:54', 'Approved'),
(4, 'operator@example.com', 'password_hash_4', 'Derek', 'Hall', '654321987V', '1988-12-01', 'Male', 4, '2024-11-02 05:32:54', 'Pending'),
(5, 'supplier@example.com', 'password_hash_5', 'John', 'Doe', '789456123V', '1980-11-11', 'Male', 5, '2024-11-02 05:32:54', 'Approved'),
(6, 'driver@example.com', 'password_hash_6', 'Peter', 'Black', '123987654V', '1995-06-25', 'Male', 6, '2024-11-02 05:32:54', 'Approved'),
(7, 'customer@example.com', 'password_hash_7', 'Liam', 'Lee', '456123789V', '1998-09-09', 'Male', 7, '2024-11-02 05:32:54', 'Pending'),
(8, 'drivingpartner@example.com', 'password_hash_8', 'Emma', 'Johnson', '321654987V', '1989-02-14', 'Female', 8, '2024-11-02 05:32:54', 'Approved'),
(9, 'driver1@example.com', 'hashed_password1', 'John', 'Doe', 'NIC001', '1985-06-20', 'Male', 2, '2024-11-02 08:03:55', 'Approved'),
(10, 'driver2@example.com', 'hashed_password2', 'Jane', 'Smith', 'NIC002', '1990-09-15', 'Female', 2, '2024-11-02 08:03:55', 'Approved'),
(11, 'partner1@example.com', 'hashed_password3', 'Michael', 'Johnson', 'NIC003', '1988-11-30', 'Male', 3, '2024-11-02 08:03:55', 'Approved'),
(12, 'partner2@example.com', 'hashed_password4', 'Emily', 'Davis', 'NIC004', '1992-12-25', 'Female', 3, '2024-11-02 08:03:55', 'Approved'),
(13, 'vehicle_manager@example.com', 'password123', 'John', 'Doe', 'NIC123456V', '1980-01-15', 'Male', 2, '2024-11-02 09:19:45', 'Approved'),
(14, 'inventory_manager@example.com', 'password123', 'Jane', 'Smith', 'NIC123457V', '1985-05-20', 'Female', 2, '2024-11-02 09:19:45', 'Approved'),
(15, 'supplier_manager@example.com', 'password123', 'Jim', 'Beam', 'NIC123458V', '1978-07-25', 'Male', 2, '2024-11-02 09:19:45', 'Approved'),
(16, 'employee_manager@example.com', 'password123', 'Sara', 'Connor', 'NIC123459V', '1990-10-10', 'Female', 2, '2024-11-02 09:19:45', 'Approved'),
(17, 'babydriver@gmail.com', '$2y$10$RYkHOo5LwYABMTU0RzB89.9AjqH8ENqFRp2YSvgPSputSCtJ1O3nm', 'Baby', 'Driver', '200206400133', '2002-03-04', 'Male', 6, '2024-11-05 08:03:43', 'Approved'),
(18, 'fortniteman@gmail.com', 'pog', 'fortnite', 'man', '200206400132', '2024-11-01', 'Male', 5, '2024-11-05 12:42:11', 'Approved'),
(20, 'birdy@gmail.com', '$2y$10$1gkBt1HCCIx2.Y7k329zReVlBLRrNtA63P6DaGE9HAZBvvU1svhH6', 'Birdy', 'Wings', '123213214', '2018-06-12', 'Female', 1, '2024-11-06 14:52:09', 'Approved'),
(21, 'becomesupplier@gmail.com', '$2y$10$SmrFz1JWLszj3uRH3qR3MOTDyH5n/FT6Q7zl/hMT6pxBoO9TdEYNe', 'Become', 'Supplier', '3143143131', '2016-01-04', 'Male', 7, '2024-11-07 19:49:36', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `district` enum('Kandy','Nuwara Eliya','Badulla','Ratnapura','Galle','Matara','Kalutara','Other') NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `gps_latitude` decimal(9,6) DEFAULT NULL,
  `gps_longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE `user_contacts` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `primary_phone` varchar(10) NOT NULL,
  `secondary_phone` varchar(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `whatsapp_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `file_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` (`photo_id`, `user_id`, `file_name`, `upload_date`, `is_active`, `file_type`) VALUES
(1, 4, '150', '2024-11-09 20:57:05', 1, 'jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `status` enum('Available','In Use','Maintenance') DEFAULT 'Available',
  `owner_name` varchar(100) DEFAULT NULL,
  `owner_contact` varchar(15) DEFAULT NULL,
  `capacity` decimal(8,2) DEFAULT NULL,
  `vehicle_type` enum('Truck','Van','Car','Bus','Three-Wheeler','Other') DEFAULT NULL,
  `insurance_expiry_date` date DEFAULT NULL,
  `road_tax_expiry_date` date DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `engine_number` varchar(50) DEFAULT NULL,
  `chassis_number` varchar(50) DEFAULT NULL,
  `seating_capacity` int(11) DEFAULT NULL,
  `condition` enum('New','Good','Fair','Poor') DEFAULT NULL,
  `last_serviced_date` date DEFAULT NULL,
  `last_maintenance` date DEFAULT NULL,
  `next_maintenance` date DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel_type` enum('Petrol','Diesel','Electric','Hybrid') DEFAULT 'Petrol',
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `license_plate`, `status`, `owner_name`, `owner_contact`, `capacity`, `vehicle_type`, `insurance_expiry_date`, `road_tax_expiry_date`, `color`, `engine_number`, `chassis_number`, `seating_capacity`, `condition`, `last_serviced_date`, `last_maintenance`, `next_maintenance`, `mileage`, `fuel_type`, `registration_date`) VALUES
(1, 'WP-1234', 'Available', 'John Silva', '0771234567', 1000.50, 'Truck', '2025-12-31', '2024-11-30', 'Blue', 'EN1234567890', 'CH1234567890', 2, 'Good', '2023-10-01', '2023-10-01', '2024-10-01', 15000, 'Diesel', '2022-01-15'),
(2, 'WP-5678', 'Available', 'Nimal Perera', '0719876543', 500.75, 'Van', '2024-06-30', '2023-12-31', 'White', 'EN0987654321', 'CH0987654321', 8, 'New', '2023-09-15', '2023-09-15', '2024-09-15', 5000, 'Petrol', '2021-05-20'),
(7, 'AB-1234', 'Available', 'John Silva', '0771234567', 213.00, 'Truck', '2024-11-06', '2024-10-29', 'Red', 'EN1234567890', 'CH1234567890', 2, 'Fair', '2024-10-29', '2024-11-08', '2024-11-05', 213, 'Diesel', '2024-11-28');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_documents`
--

CREATE TABLE `vehicle_documents` (
  `vehicle_document_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `document_type` enum('Service Record','Insurance','Road Tax','Maintenance Record','Image','Ownership Proof') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_documents`
--

INSERT INTO `vehicle_documents` (`vehicle_document_id`, `vehicle_id`, `document_type`, `file_path`, `upload_date`) VALUES
(1, 1, 'Image', 'https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg', '2024-11-03 14:13:09'),
(2, 2, 'Image', 'https://i.ikman-st.com/mazda-bongo-1997-for-sale-puttalam-2/cdd5b09e-ab3f-42c4-8642-575b1bc9072b/620/466/fitted.jpg', '2024-11-03 15:16:15'),
(3, 7, 'Image', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrLhTwmjDEshchAYXpCwmtkDEh4ywp6MOQrA&s', '2024-11-03 18:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_managers`
--

CREATE TABLE `vehicle_managers` (
  `vehicle_manager_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owner_history`
--

CREATE TABLE `vehicle_owner_history` (
  `history_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_contact` varchar(15) DEFAULT NULL,
  `ownership_start_date` date NOT NULL,
  `ownership_end_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `vehicle_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_service_records`
--

CREATE TABLE `vehicle_service_records` (
  `record_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `service_type` enum('Routine','Repair','Inspection','Upgrade') NOT NULL,
  `service_center` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `next_service_due` date DEFAULT NULL,
  `vehicle_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` decimal(10,2) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive','Maintenance') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `worker_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_addresses`
--
ALTER TABLE `application_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `application_addresses_ibfk_1` (`application_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `application_documents_ibfk_1` (`application_id`);

--
-- Indexes for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  ADD PRIMARY KEY (`infrastructure_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  ADD PRIMARY KEY (`ownership_id`),
  ADD KEY `application_ownership_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_property_details`
--
ALTER TABLE `application_property_details`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `application_property_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_structures`
--
ALTER TABLE `application_structures`
  ADD PRIMARY KEY (`structure_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  ADD PRIMARY KEY (`tea_detail_id`),
  ADD KEY `application_tea_details_ibfk_1` (`application_id`);

--
-- Indexes for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  ADD PRIMARY KEY (`variety_id`),
  ADD KEY `application_tea_varieties_ibfk_1` (`application_id`);

--
-- Indexes for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  ADD PRIMARY KEY (`water_source_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `skeleton_id` (`skeleton_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  ADD PRIMARY KEY (`skeleton_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `crates`
--
ALTER TABLE `crates`
  ADD PRIMARY KEY (`crate_id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `daily_metrics`
--
ALTER TABLE `daily_metrics`
  ADD PRIMARY KEY (`metrics_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `license_no` (`license_no`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `driving_partners`
--
ALTER TABLE `driving_partners`
  ADD PRIMARY KEY (`partner_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`verification_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `Fertilizer`
--
ALTER TABLE `Fertilizer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fertilizer_type_id` (`fertilizer_type_id`);

--
-- Indexes for table `fertilizer_types`
--
ALTER TABLE `fertilizer_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  ADD PRIMARY KEY (`inv_manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `land_details`
--
ALTER TABLE `land_details`
  ADD PRIMARY KEY (`land_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `land_inspections`
--
ALTER TABLE `land_inspections`
  ADD PRIMARY KEY (`inspection_id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`machine_id`);

--
-- Indexes for table `machine_usage`
--
ALTER TABLE `machine_usage`
  ADD PRIMARY KEY (`usage_id`),
  ADD KEY `machine_id` (`machine_id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`operator_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `supplier_applications_ibfk_1` (`user_id`);

--
-- Indexes for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  ADD PRIMARY KEY (`bank_info_id`),
  ADD UNIQUE KEY `unique_account_number` (`account_number`),
  ADD KEY `idx_application_id` (`application_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `partner_id` (`partner_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `tea_products`
--
ALTER TABLE `tea_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- Indexes for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  ADD PRIMARY KEY (`vehicle_document_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  ADD PRIMARY KEY (`vehicle_manager_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_document_id` (`vehicle_document_id`);

--
-- Indexes for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_document_id` (`vehicle_document_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_addresses`
--
ALTER TABLE `application_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  MODIFY `infrastructure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  MODIFY `ownership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `application_property_details`
--
ALTER TABLE `application_property_details`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_structures`
--
ALTER TABLE `application_structures`
  MODIFY `structure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  MODIFY `tea_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  MODIFY `variety_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  MODIFY `water_source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  MODIFY `skeleton_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_metrics`
--
ALTER TABLE `daily_metrics`
  MODIFY `metrics_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driving_partners`
--
ALTER TABLE `driving_partners`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Fertilizer`
--
ALTER TABLE `Fertilizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fertilizer_types`
--
ALTER TABLE `fertilizer_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  MODIFY `inv_manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `land_details`
--
ALTER TABLE `land_details`
  MODIFY `land_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `land_inspections`
--
ALTER TABLE `land_inspections`
  MODIFY `inspection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_usage`
--
ALTER TABLE `machine_usage`
  MODIFY `usage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `operator_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  MODIFY `bank_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team_progress`
--
ALTER TABLE `team_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tea_products`
--
ALTER TABLE `tea_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contacts`
--
ALTER TABLE `user_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  MODIFY `vehicle_document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  MODIFY `vehicle_manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application_addresses`
--
ALTER TABLE `application_addresses`
  ADD CONSTRAINT `application_addresses_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `application_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_infrastructure`
--
ALTER TABLE `application_infrastructure`
  ADD CONSTRAINT `application_infrastructure_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `application_ownership_details`
--
ALTER TABLE `application_ownership_details`
  ADD CONSTRAINT `application_ownership_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_property_details`
--
ALTER TABLE `application_property_details`
  ADD CONSTRAINT `application_property_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_structures`
--
ALTER TABLE `application_structures`
  ADD CONSTRAINT `application_structures_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `application_tea_details`
--
ALTER TABLE `application_tea_details`
  ADD CONSTRAINT `application_tea_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_tea_varieties`
--
ALTER TABLE `application_tea_varieties`
  ADD CONSTRAINT `application_tea_varieties_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_water_sources`
--
ALTER TABLE `application_water_sources`
  ADD CONSTRAINT `application_water_sources_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`skeleton_id`) REFERENCES `collection_skeletons` (`skeleton_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collections_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collections_ibfk_4` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `collection_skeletons`
--
ALTER TABLE `collection_skeletons`
  ADD CONSTRAINT `collection_skeletons_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collection_skeletons_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `collection_skeletons_ibfk_4` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `driving_partners`
--
ALTER TABLE `driving_partners`
  ADD CONSTRAINT `driving_partners_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD CONSTRAINT `email_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`);

--
-- Constraints for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD CONSTRAINT `fertilizer_inventory_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `fertilizer_types` (`type_id`),
  ADD CONSTRAINT `fertilizer_inventory_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `fertilizer_orders`
--
ALTER TABLE `fertilizer_orders`
  ADD CONSTRAINT `fertilizer_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `fertilizer_order_items`
--
ALTER TABLE `fertilizer_order_items`
  ADD CONSTRAINT `fertilizer_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `fertilizer_orders` (`order_id`),
  ADD CONSTRAINT `fertilizer_order_items_ibfk_2` FOREIGN KEY (`fertilizer_type_id`) REFERENCES `fertilizer_types` (`type_id`);

--
-- Constraints for table `inventory_managers`
--
ALTER TABLE `inventory_managers`
  ADD CONSTRAINT `inventory_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `land_details`
--
ALTER TABLE `land_details`
  ADD CONSTRAINT `land_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `land_inspections`
--
ALTER TABLE `land_inspections`
  ADD CONSTRAINT `land_inspections_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`),
  ADD CONSTRAINT `land_inspections_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `machine_usage`
--
ALTER TABLE `machine_usage`
  ADD CONSTRAINT `machine_usage_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`),
  ADD CONSTRAINT `machine_usage_ibfk_2` FOREIGN KEY (`operator_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `operators`
--
ALTER TABLE `operators`
  ADD CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `customer_orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tea_products` (`product_id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tea_products` (`product_id`),
  ADD CONSTRAINT `product_inventory_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `refresh_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`);

--
-- Constraints for table `supplier_applications`
--
ALTER TABLE `supplier_applications`
  ADD CONSTRAINT `supplier_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier_bank_info`
--
ALTER TABLE `supplier_bank_info`
  ADD CONSTRAINT `supplier_bank_info_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `supplier_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`),
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`partner_id`) REFERENCES `driving_partners` (`partner_id`),
  ADD CONSTRAINT `teams_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD CONSTRAINT `team_progress_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_progress_ibfk_2` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE CASCADE;

--
-- Constraints for table `tea_leaves_inventory`
--
ALTER TABLE `tea_leaves_inventory`
  ADD CONSTRAINT `tea_leaves_inventory_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD CONSTRAINT `user_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD CONSTRAINT `user_photos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `vehicle_documents`
--
ALTER TABLE `vehicle_documents`
  ADD CONSTRAINT `vehicle_documents_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_managers`
--
ALTER TABLE `vehicle_managers`
  ADD CONSTRAINT `vehicle_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `vehicle_owner_history`
--
ALTER TABLE `vehicle_owner_history`
  ADD CONSTRAINT `vehicle_owner_history_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_owner_history_ibfk_2` FOREIGN KEY (`vehicle_document_id`) REFERENCES `vehicle_documents` (`vehicle_document_id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_service_records`
--
ALTER TABLE `vehicle_service_records`
  ADD CONSTRAINT `vehicle_service_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_service_records_ibfk_2` FOREIGN KEY (`vehicle_document_id`) REFERENCES `vehicle_documents` (`vehicle_document_id`) ON DELETE SET NULL;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `inventory_managers` (`inv_manager_id`);

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
