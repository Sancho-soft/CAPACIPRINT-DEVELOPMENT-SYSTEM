-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 16, 2026 at 02:21 AM
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
-- Database: `capacity_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `event`, `module`, `ip_address`, `description`, `old_values`, `new_values`, `created_at`, `updated_at`) VALUES
(1, 7, 'User Account Archived', 'User Management', '127.0.0.1', 'Archived user account Director Morningstar (owner@capaciprint.com)', NULL, '{\"is_archived\":true}', '2026-08-30 06:41:28', '2026-08-30 06:41:28'),
(2, 7, 'User Account Restored', 'User Management', '127.0.0.1', 'Restored user account Director Morningstar (owner@capaciprint.com)', NULL, '{\"is_archived\":false}', '2026-08-30 06:41:39', '2026-08-30 06:41:39'),
(3, 7, 'Database Backup Initiated', 'Settings', '127.0.0.1', 'System administrator generated a database backup', NULL, NULL, '2026-09-03 17:31:42', '2026-09-03 17:31:42'),
(4, 7, 'User Account Archived', 'User Management', '127.0.0.1', 'Archived user account Director Morningstar (owner@capaciprint.com)', NULL, '{\"is_archived\":true}', '2026-09-03 18:26:56', '2026-09-03 18:26:56'),
(5, 7, 'User Account Restored', 'User Management', '127.0.0.1', 'Restored user account Director Morningstar (owner@capaciprint.com)', NULL, '{\"is_archived\":false}', '2026-09-03 18:27:15', '2026-09-03 18:27:15'),
(6, 7, 'Database Backup Initiated', 'Settings', '127.0.0.1', 'System administrator generated a database backup', NULL, NULL, '2026-09-05 22:17:11', '2026-09-05 22:17:11'),
(7, 4, 'Order Updated', 'Order Management', '127.0.0.1', 'Order #1 was Updated by Pedro Operator', '{\"status\":\"claimed\",\"updated_at\":\"2026-08-28T14:25:34.000000Z\"}', '{\"status\":\"ready_for_pickup\",\"updated_at\":\"2026-09-10 13:09:13\"}', '2026-09-10 05:09:13', '2026-09-10 05:09:13'),
(8, 4, 'ProductionJob Updated', 'Production Management', '127.0.0.1', 'ProductionJob #1 was Updated by Pedro Operator', '{\"status\":\"in_production\",\"completed_at\":null,\"updated_at\":\"2026-08-20T12:29:57.000000Z\"}', '{\"status\":\"completed\",\"completed_at\":\"2026-09-10 13:09:13\",\"updated_at\":\"2026-09-10 13:09:13\"}', '2026-09-10 05:09:13', '2026-09-10 05:09:13'),
(9, 4, 'Job Status Updated', 'Production Queue', '127.0.0.1', 'Job #JOB-SEED001 status updated to completed', '{\"status\":\"completed\"}', '{\"status\":\"completed\"}', '2026-09-10 05:09:13', '2026-09-10 05:09:13'),
(10, 7, 'user.login', 'Authentication', '127.0.0.1', 'User Justine Bieber (management) logged in from internal network.', NULL, NULL, '2026-09-12 20:02:13', '2026-09-12 22:28:13'),
(11, 7, 'order.routed', 'Capacity Routing', '127.0.0.1', 'Automated routing algorithm assigned Job #JOB-2026-121 to Branch A.', NULL, NULL, '2026-09-12 20:58:13', '2026-09-12 22:28:13'),
(12, 7, 'quotation.confirmed', 'Sales', '127.0.0.1', 'Customer Aldren Reyes accepted and signed quotation QT-HIST-2026-134.', NULL, NULL, '2026-09-12 21:58:13', '2026-09-12 22:28:13'),
(13, 7, 'inventory.low_stock', 'Inventory Buffer', '127.0.0.1', 'Stock warning triggered: CMYK Cyan Toner Cartridge below threshold at Branch B.', NULL, NULL, '2026-09-12 18:10:13', '2026-09-12 22:28:13'),
(14, 7, 'payment.verified', 'Finance', '127.0.0.1', 'Payment ₱12,500 verified via GCash gateway reference PAY-2026-GH921.', NULL, NULL, '2026-09-12 21:53:13', '2026-09-12 22:28:13'),
(15, 7, 'Executive Account Updated', 'User Management', '127.0.0.1', 'Updated account details for Hearty Lupogan', '{\"id\":11,\"name\":\"Hearty Lupogan\",\"email\":\"owner@capaciprint.com\",\"google_id\":null,\"avatar\":null,\"role\":\"owner\",\"phone\":\"+63 922 678 9012\",\"address\":\"Executive Headquarters\",\"branch_id\":null,\"email_verified_at\":null,\"created_at\":\"2026-08-23T12:40:54.000000Z\",\"updated_at\":\"2026-09-12T13:58:04.000000Z\",\"is_archived\":false}', '{\"id\":11,\"name\":\"Hearty Lupogan\",\"email\":\"owner@capaciprint.com\",\"google_id\":null,\"avatar\":null,\"role\":\"owner\",\"phone\":\"+63 922 678 9012\",\"address\":\"Executive Headquarters\",\"branch_id\":null,\"email_verified_at\":null,\"created_at\":\"2026-08-23T12:40:54.000000Z\",\"updated_at\":\"2026-09-12T13:58:04.000000Z\",\"is_archived\":false}', '2026-10-15 05:59:31', '2026-10-15 05:59:31'),
(16, 7, 'Executive Account Updated', 'User Management', '127.0.0.1', 'Updated account details for Hearty MacLup', '{\"id\":11,\"name\":\"Hearty Lupogan\",\"email\":\"owner@capaciprint.com\",\"google_id\":null,\"avatar\":null,\"role\":\"owner\",\"phone\":\"+63 922 678 9012\",\"address\":\"Executive Headquarters\",\"branch_id\":null,\"email_verified_at\":null,\"created_at\":\"2026-08-23T12:40:54.000000Z\",\"updated_at\":\"2026-09-12T13:58:04.000000Z\",\"is_archived\":false}', '{\"id\":11,\"name\":\"Hearty MacLup\",\"email\":\"owner@capaciprint.com\",\"google_id\":null,\"avatar\":null,\"role\":\"owner\",\"phone\":\"+63 922 678 9012\",\"address\":\"Executive Headquarters\",\"branch_id\":null,\"email_verified_at\":null,\"created_at\":\"2026-08-23T12:40:54.000000Z\",\"updated_at\":\"2026-10-15T13:59:54.000000Z\",\"is_archived\":false}', '2026-10-15 05:59:54', '2026-10-15 05:59:54'),
(17, 7, 'Executive Account Updated', 'User Management', '127.0.0.1', 'Updated account details for Hearty Lupogan', '{\"id\":11,\"name\":\"Hearty MacLup\",\"email\":\"owner@capaciprint.com\",\"google_id\":null,\"avatar\":null,\"role\":\"owner\",\"phone\":\"+63 922 678 9012\",\"address\":\"Executive Headquarters\",\"branch_id\":null,\"email_verified_at\":null,\"created_at\":\"2026-08-23T12:40:54.000000Z\",\"updated_at\":\"2026-10-15T13:59:54.000000Z\",\"is_archived\":false}', '{\"id\":11,\"name\":\"Hearty Lupogan\",\"email\":\"owner@capaciprint.com\",\"google_id\":null,\"avatar\":null,\"role\":\"owner\",\"phone\":\"+63 922 678 9012\",\"address\":\"Executive Headquarters\",\"branch_id\":null,\"email_verified_at\":null,\"created_at\":\"2026-08-23T12:40:54.000000Z\",\"updated_at\":\"2026-10-15T14:10:20.000000Z\",\"is_archived\":false}', '2026-10-15 06:10:20', '2026-10-15 06:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `max_daily_jobs` int(11) NOT NULL DEFAULT 20,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `address`, `phone`, `manager_name`, `status`, `max_daily_jobs`, `created_at`, `updated_at`) VALUES
(1, 'Morning Star Printing Press', 'Branch A', 'Central Press Complex, Hub 1, Metro Operations', '+63 917 111 2233', 'Niere Lupogan', 'active', 85, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(2, 'Morning Star Printing Network', 'Branch B', 'North Commercial Corridor, Suite 402, North Hub', '+63 917 222 3344', 'Niere Lupogan', 'active', 60, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(3, 'Green Heart Printing Hub', 'Branch C', 'South Tech District, Building B, South Node', '+63 917 333 4455', 'Niere Lupogan', 'active', 50, '2026-08-20 04:29:57', '2026-09-12 06:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `branch_inventory`
--

CREATE TABLE `branch_inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `minimum_stock` decimal(10,2) NOT NULL DEFAULT 100.00,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `last_updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_inventory`
--

INSERT INTO `branch_inventory` (`id`, `branch_id`, `material_id`, `quantity`, `minimum_stock`, `status`, `last_updated`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 130.00, 50.00, 'available', '2026-09-17 04:58:59', '2026-08-20 04:29:57', '2026-09-17 04:58:59'),
(2, 1, 2, 190.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(3, 1, 3, 209.00, 25.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(4, 1, 4, 237.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(5, 2, 1, 190.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(6, 2, 2, 193.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(7, 2, 3, 12.00, 25.00, 'low_stock', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(8, 2, 4, 191.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(9, 3, 1, 228.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(10, 3, 2, 187.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(11, 3, 3, 234.00, 25.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(12, 3, 4, 190.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-08-20 04:29:57', '2026-09-12 22:28:11'),
(25, 1, 5, 93.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(26, 1, 6, 98.00, 25.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(27, 1, 7, 86.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(28, 1, 8, 8.00, 25.00, 'low_stock', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(29, 2, 5, 149.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(30, 2, 6, 128.00, 25.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(31, 2, 7, 224.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(32, 2, 8, 205.00, 25.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(33, 3, 5, 169.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(34, 3, 6, 0.00, 25.00, 'out_of_stock', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(35, 3, 7, 165.00, 50.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11'),
(36, 3, 8, 240.00, 25.00, 'available', '2026-09-12 22:28:11', '2026-09-12 06:23:46', '2026-09-12 22:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `branch_recommendations`
--

CREATE TABLE `branch_recommendations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `print_request_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recommended_branch_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `recommendation_score` int(11) NOT NULL DEFAULT 0,
  `reason` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `override_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_recommendations`
--

INSERT INTO `branch_recommendations` (`id`, `print_request_id`, `order_id`, `recommended_branch_id`, `created_by`, `recommendation_score`, `reason`, `status`, `override_reason`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 9, 91, 'Branch A has lowest current queue and active offset Heidelberg press.', 'confirmed', NULL, '2026-09-12 06:24:31', '2026-09-12 06:24:31');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `capacity_evaluations`
--

CREATE TABLE `capacity_evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `print_request_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `evaluated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `machine_score` int(11) NOT NULL DEFAULT 0,
  `material_score` int(11) NOT NULL DEFAULT 0,
  `employee_score` int(11) NOT NULL DEFAULT 0,
  `workload_score` int(11) NOT NULL DEFAULT 0,
  `deadline_score` int(11) NOT NULL DEFAULT 0,
  `total_score` int(11) NOT NULL DEFAULT 0,
  `capacity_status` varchar(255) NOT NULL DEFAULT 'not_qualified',
  `available_machines` int(11) NOT NULL DEFAULT 0,
  `current_workload_pct` decimal(5,2) NOT NULL DEFAULT 0.00,
  `estimated_completion` date DEFAULT NULL,
  `deadline_feasible` tinyint(1) NOT NULL DEFAULT 0,
  `evaluation_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `capacity_evaluations`
--

INSERT INTO `capacity_evaluations` (`id`, `print_request_id`, `branch_id`, `evaluated_by`, `machine_score`, `material_score`, `employee_score`, `workload_score`, `deadline_score`, `total_score`, `capacity_status`, `available_machines`, `current_workload_pct`, `estimated_completion`, `deadline_feasible`, `evaluation_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 9, 95, 90, 92, 85, 94, 91, 'qualified', 2, 58.50, '2026-09-15', 1, 'Optimal offset machine throughput with available plate stock.', '2026-09-12 06:24:31', '2026-09-12 22:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `claim_references`
--

CREATE TABLE `claim_references` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `claim_code` varchar(255) NOT NULL,
  `pickup_branch` varchar(255) DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `is_claimed` tinyint(1) NOT NULL DEFAULT 0,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `claim_references`
--

INSERT INTO `claim_references` (`id`, `order_id`, `user_id`, `claim_code`, `pickup_branch`, `completion_date`, `is_claimed`, `claimed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'CLM-2B36ZIAQ', 'Morning Star Printing Press', '2026-08-28', 0, '2026-08-28 06:25:34', '2026-08-28 06:25:34', '2026-08-28 06:25:34'),
(2, 132, 16, 'CLM-8824-DAVE', 'Morning Star Printing Press', '2026-10-15', 0, NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(3, 133, 17, 'CLM-9102-SARA', 'Morning Star Printing Network', '2026-10-13', 1, '2026-10-14 06:37:17', '2026-10-15 06:37:17', '2026-10-15 06:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `customer_notifications`
--

CREATE TABLE `customer_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_notifications`
--

INSERT INTO `customer_notifications` (`id`, `user_id`, `order_id`, `title`, `body`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Order Claimed Successfully', 'Order #ORD-SEED001 was claimed at the branch. Thank you for choosing Morning Star Printing Press!', 'order', 1, '2026-08-28 06:25:34', '2026-09-04 05:02:55'),
(2, 1, 1, 'Your Order is Ready for Pickup!', 'Order #ORD-SEED001 has been completed and is ready for collection at Morning Star Printing Press.', 'pickup', 0, '2026-09-10 05:09:13', '2026-09-10 05:09:13');

-- --------------------------------------------------------

--
-- Table structure for table `design_proofs`
--

CREATE TABLE `design_proofs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `print_request_id` bigint(20) UNSIGNED NOT NULL,
  `designer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `proof_file_path` varchar(255) NOT NULL,
  `proof_file_name` varchar(255) NOT NULL,
  `proof_file_size` varchar(255) DEFAULT NULL,
  `production_file_path` varchar(255) DEFAULT NULL,
  `production_file_name` varchar(255) DEFAULT NULL,
  `designer_notes` text DEFAULT NULL,
  `customer_feedback` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending_review',
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `design_proofs`
--

INSERT INTO `design_proofs` (`id`, `print_request_id`, `designer_id`, `version`, `proof_file_path`, `proof_file_name`, `proof_file_size`, `production_file_path`, `production_file_name`, `designer_notes`, `customer_feedback`, `status`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 1, 'proofs/flyer_campaign_proof_v1.pdf', 'flyer_campaign_proof_v1.pdf', NULL, NULL, 'flyer_campaign_production_cmyk.pdf', 'Colors separated for 4-color offset run. High-res raster verified at 300 DPI.', 'Approved as submitted. Ready for press plate creation.', 'approved', '2026-09-11 22:28:12', '2026-09-12 06:23:46', '2026-09-12 22:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `availability_status` varchar(255) NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `branch_id`, `name`, `position`, `availability_status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'Kim Lupogan', 'Senior Press Operator', 'available', '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(2, NULL, 1, 'Staff Member A2', 'Press Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(3, NULL, 1, 'Staff Member A3', 'Press Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(4, NULL, 1, 'Staff Member A4', 'Press Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(5, NULL, 1, 'Staff Member A5', 'Press Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(6, NULL, 1, 'Staff Member A6', 'Press Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(7, NULL, 1, 'Staff Member A7', 'Press Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(8, NULL, 2, 'Staff Member B1', 'Digital Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(9, NULL, 2, 'Staff Member B2', 'Digital Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(10, NULL, 2, 'Staff Member B3', 'Digital Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(11, NULL, 2, 'Staff Member B4', 'Digital Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(12, NULL, 2, 'Staff Member B5', 'Digital Operator', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(13, NULL, 3, 'Staff Member C1', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(14, NULL, 3, 'Staff Member C2', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(15, NULL, 3, 'Staff Member C3', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(16, NULL, 3, 'Staff Member C4', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(17, NULL, 3, 'Staff Member C5', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(18, NULL, 3, 'Staff Member C6', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(19, NULL, 3, 'Staff Member C7', 'Finishing Specialist', 'available', '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(20, 9, 1, 'Isagani Canal', 'Lead Production Planner', 'available', '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(21, 10, 1, 'Clarence Gotas', 'Pre-Press Graphic Specialist', 'available', '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(22, 5, 1, 'Mike Kosa', 'Materials & Logistics Officer', 'available', '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(23, 2, 1, 'Alex Beniga', 'Customer Relations Specialist', 'available', '2026-09-12 06:23:46', '2026-09-12 06:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internal_notifications`
--

CREATE TABLE `internal_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `jobs_per_day_capacity` int(11) NOT NULL DEFAULT 5,
  `notes` text DEFAULT NULL,
  `last_maintenance_date` date DEFAULT NULL,
  `next_maintenance_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `branch_id`, `name`, `type`, `model`, `status`, `jobs_per_day_capacity`, `notes`, `last_maintenance_date`, `next_maintenance_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Heidelberg Speedmaster XL 106', 'Offset Press', 'XL 106-6+L', 'available', 18, NULL, NULL, NULL, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(2, 1, 'HP Indigo 12000 Digital Press', 'Digital Press', 'Indigo 12000', 'available', 15, NULL, NULL, NULL, '2026-08-20 04:29:57', '2026-08-20 04:29:57'),
(3, 2, 'Roland Soljet Pro 4 XR-640', 'Large Format', 'XR-640 Eco-Solvent', 'available', 10, NULL, NULL, NULL, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(4, 3, 'Canon ImagePRESS C10010VP', 'Digital Press', 'C10010VP', 'available', 14, NULL, NULL, NULL, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(5, 1, 'Heidelberg Speedmaster XL 106', 'Offset Press', 'XL 106-6+L', 'available', 12, NULL, NULL, NULL, '2026-08-28 06:48:57', '2026-08-28 06:48:57'),
(6, 1, 'HP Indigo 12000 Digital Press', 'Digital Press', 'Indigo 12000', 'available', 15, NULL, NULL, NULL, '2026-08-28 06:48:57', '2026-08-28 06:48:57'),
(7, 2, 'Roland Soljet Pro 4 XR-640', 'Large Format', 'XR-640', 'available', 8, NULL, NULL, NULL, '2026-08-28 06:48:57', '2026-08-28 06:48:57'),
(8, 3, 'Canon ImagePRESS C10010VP', 'Digital Press', 'C10010VP', 'available', 10, NULL, NULL, NULL, '2026-08-28 06:48:57', '2026-08-28 06:48:57'),
(9, 1, 'Press Machine A5', 'Offset Press', 'Speedmaster-XL', 'available', 15, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(10, 2, 'Network Machine B3', 'Digital Press', 'Pro-XR', 'available', 12, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(11, 2, 'Network Machine B4', 'Digital Press', 'Pro-XR', 'available', 12, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(12, 3, 'Hub Machine C3', 'Large Format Press', 'Canon-C100', 'available', 10, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(13, 3, 'Hub Machine C4', 'Large Format Press', 'Canon-C100', 'available', 10, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(14, 3, 'Hub Machine C5', 'Large Format Press', 'Canon-C100', 'available', 10, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(15, 3, 'Hub Machine C6', 'Large Format Press', 'Canon-C100', 'available', 10, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(16, 3, 'Hub Machine C7', 'Large Format Press', 'Canon-C100', 'available', 10, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(17, 3, 'Hub Machine C8', 'Large Format Press', 'Canon-C100', 'available', 10, NULL, NULL, NULL, '2026-09-03 05:50:08', '2026-09-03 05:50:08'),
(18, 2, 'Epson SureColor S80600', 'Signage & Vinyl', 'SC-S80600', 'available', 12, NULL, NULL, NULL, '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(19, 3, 'Konica Minolta AccurioPress C4080', 'Color Digital', 'C4080', 'available', 10, NULL, NULL, NULL, '2026-09-12 06:23:46', '2026-09-12 06:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `machine_logs`
--

CREATE TABLE `machine_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `machine_id` bigint(20) UNSIGNED NOT NULL,
  `reported_by` bigint(20) UNSIGNED NOT NULL,
  `log_type` enum('breakdown','maintenance','inspection','status_change') NOT NULL DEFAULT 'breakdown',
  `issue_description` text NOT NULL,
  `status` enum('open','in_progress','resolved') NOT NULL DEFAULT 'open',
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `type`, `unit`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Glossy Paper 150gsm (A4/A3)', 'paper', 'reams', 'Premium high-gloss coated art paper for flyers', 1, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(2, 'Matte Cardstock 220gsm', 'paper', 'sheets', 'Rigid matte substrate for calling cards and booklet covers', 1, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(3, 'CMYK Cyan Toner Cartridge', 'ink', 'cartridges', 'High-capacity Cyan micro-toner for digital press fleet', 1, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(4, 'Flex Tarpaulin Banner Media 13oz', 'paper', 'rolls', 'Weatherproof heavy outdoor tarpaulin fabric', 1, '2026-08-20 04:29:57', '2026-09-12 06:23:46'),
(5, 'Premium Bond Paper 80gsm', 'paper', 'reams', 'High-opacity office documentation and legal print paper', 1, '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(6, 'CMYK Black Toner High Yield', 'ink', 'cartridges', 'Ultra-dense black toner formulation for crisp text and imagery', 1, '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(7, 'Gloss Vinyl Waterproof Sticker Roll', 'paper', 'rolls', 'Permanent adhesive tear-proof vinyl substrate', 1, '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(8, 'Thermal Gloss Lamination Film 30mic', 'lamination', 'rolls', 'Anti-scratch protective thermal sealing lamination film', 1, '2026-09-12 06:23:46', '2026-09-12 06:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_20_055516_add_role_to_users_table', 1),
(5, '2026_08_20_060000_create_print_requests_table', 1),
(6, '2026_08_20_060001_create_quotations_table', 1),
(7, '2026_08_20_060002_create_orders_table', 1),
(8, '2026_08_20_060003_create_payments_table', 1),
(9, '2026_08_20_060004_create_notifications_table', 1),
(10, '2026_08_20_060005_create_claim_references_table', 1),
(11, '2026_08_20_070000_create_branches_table', 1),
(12, '2026_08_20_070001_create_machines_table', 1),
(13, '2026_08_20_070002_create_employees_table', 1),
(14, '2026_08_20_070003_create_materials_table', 1),
(15, '2026_08_20_070004_create_branch_inventory_table', 1),
(16, '2026_08_20_070005_create_stock_movements_table', 1),
(17, '2026_08_20_070006_create_production_jobs_table', 1),
(18, '2026_08_20_070007_create_capacity_evaluations_table', 1),
(19, '2026_08_20_070008_create_branch_recommendations_table', 1),
(20, '2026_08_20_070009_create_internal_notifications_table', 1),
(21, '2026_08_20_070010_create_pricing_rules_table', 1),
(22, '2026_08_20_080000_add_proof_fields_to_print_requests_table', 2),
(23, '2026_08_20_080001_create_purchase_requests_table', 2),
(24, '2026_08_20_080002_create_machine_logs_table', 2),
(25, '2026_08_20_090000_create_audit_logs_table', 2),
(26, '2026_08_22_164915_create_design_proofs_table', 3),
(27, '2026_08_30_200000_add_is_archived_to_users_table', 4),
(28, '2026_09_04_000001_add_payment_method_to_payments_table', 5),
(29, '2026_09_06_000001_remove_super_admin_role_and_user', 6),
(30, '2026_09_10_000001_add_maintenance_dates_to_machines_table', 7),
(31, '2026_09_15_135423_add_google_id_to_users_table', 8),
(32, '2026_10_15_000001_add_branch_id_to_users_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `print_request_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_branch` varchar(255) DEFAULT NULL,
  `estimated_completion` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'submitted',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `print_request_id`, `quotation_id`, `assigned_branch`, `estimated_completion`, `status`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 'ORD-001', 1, 1, 1, 'Morning Star Printing Press', '2026-08-23', 'ready_for_pickup', 'confirmed', '2026-08-20 04:29:57', '2026-09-10 05:09:13'),
(2, 'ORD-002', 1, 8, 2, 'Morning Star Printing Press', '2026-04-05', 'completed', 'confirmed', '2026-04-02 03:38:00', '2026-04-02 03:38:00'),
(3, 'ORD-003', 1, 9, 3, 'Morning Star Printing Press', '2026-04-08', 'completed', 'confirmed', '2026-04-05 02:38:00', '2026-04-05 02:38:00'),
(4, 'ORD-004', 1, 10, 4, 'Morning Star Printing Press', '2026-04-11', 'completed', 'confirmed', '2026-04-08 00:25:00', '2026-04-08 00:25:00'),
(5, 'ORD-005', 1, 11, 5, 'Morning Star Printing Press', '2026-04-14', 'completed', 'confirmed', '2026-04-11 06:35:00', '2026-04-11 06:35:00'),
(6, 'ORD-006', 1, 12, 6, 'Morning Star Printing Press', '2026-04-17', 'completed', 'confirmed', '2026-04-14 07:12:00', '2026-04-14 07:12:00'),
(7, 'ORD-007', 1, 13, 7, 'Morning Star Printing Press', '2026-04-20', 'completed', 'confirmed', '2026-04-17 08:36:00', '2026-04-17 08:36:00'),
(8, 'ORD-008', 1, 14, 8, 'Morning Star Printing Press', '2026-04-23', 'completed', 'confirmed', '2026-04-20 05:12:00', '2026-04-20 05:12:00'),
(9, 'ORD-009', 1, 15, 9, 'Morning Star Printing Network', '2026-04-05', 'completed', 'confirmed', '2026-04-02 02:50:00', '2026-04-02 02:50:00'),
(10, 'ORD-010', 1, 16, 10, 'Morning Star Printing Network', '2026-04-08', 'completed', 'confirmed', '2026-04-05 01:13:00', '2026-04-05 01:13:00'),
(11, 'ORD-011', 1, 17, 11, 'Morning Star Printing Network', '2026-04-11', 'completed', 'confirmed', '2026-04-08 09:28:00', '2026-04-08 09:28:00'),
(12, 'ORD-012', 1, 18, 12, 'Morning Star Printing Network', '2026-04-14', 'completed', 'confirmed', '2026-04-11 05:17:00', '2026-04-11 05:17:00'),
(13, 'ORD-013', 1, 19, 13, 'Green Heart Printing Hub', '2026-04-05', 'completed', 'confirmed', '2026-04-02 05:42:00', '2026-04-02 05:42:00'),
(14, 'ORD-014', 1, 20, 14, 'Green Heart Printing Hub', '2026-04-08', 'completed', 'confirmed', '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(15, 'ORD-015', 1, 21, 15, 'Green Heart Printing Hub', '2026-04-11', 'completed', 'confirmed', '2026-04-08 04:10:00', '2026-04-08 04:10:00'),
(16, 'ORD-016', 1, 22, 16, 'Morning Star Printing Press', '2026-05-05', 'completed', 'confirmed', '2026-05-02 01:22:00', '2026-05-02 01:22:00'),
(17, 'ORD-017', 1, 23, 17, 'Morning Star Printing Press', '2026-05-08', 'completed', 'confirmed', '2026-05-05 02:46:00', '2026-05-05 02:46:00'),
(18, 'ORD-018', 1, 24, 18, 'Morning Star Printing Press', '2026-05-11', 'completed', 'confirmed', '2026-05-08 06:27:00', '2026-05-08 06:27:00'),
(19, 'ORD-019', 1, 25, 19, 'Morning Star Printing Press', '2026-05-14', 'completed', 'confirmed', '2026-05-11 04:12:00', '2026-05-11 04:12:00'),
(20, 'ORD-020', 1, 26, 20, 'Morning Star Printing Press', '2026-05-17', 'completed', 'confirmed', '2026-05-14 02:26:00', '2026-05-14 02:26:00'),
(21, 'ORD-021', 1, 27, 21, 'Morning Star Printing Press', '2026-05-20', 'completed', 'confirmed', '2026-05-17 09:31:00', '2026-05-17 09:31:00'),
(22, 'ORD-022', 1, 28, 22, 'Morning Star Printing Press', '2026-05-23', 'completed', 'confirmed', '2026-05-20 00:42:00', '2026-05-20 00:42:00'),
(23, 'ORD-023', 1, 29, 23, 'Morning Star Printing Press', '2026-05-26', 'completed', 'confirmed', '2026-05-23 08:40:00', '2026-05-23 08:40:00'),
(24, 'ORD-024', 1, 30, 24, 'Morning Star Printing Press', '2026-05-29', 'completed', 'confirmed', '2026-05-26 07:18:00', '2026-05-26 07:18:00'),
(25, 'ORD-025', 1, 31, 25, 'Morning Star Printing Network', '2026-05-05', 'completed', 'confirmed', '2026-05-02 03:15:00', '2026-05-02 03:15:00'),
(26, 'ORD-026', 1, 32, 26, 'Morning Star Printing Network', '2026-05-08', 'completed', 'confirmed', '2026-05-05 02:13:00', '2026-05-05 02:13:00'),
(27, 'ORD-027', 1, 33, 27, 'Morning Star Printing Network', '2026-05-11', 'completed', 'confirmed', '2026-05-08 00:38:00', '2026-05-08 00:38:00'),
(28, 'ORD-028', 1, 34, 28, 'Morning Star Printing Network', '2026-05-14', 'completed', 'confirmed', '2026-05-11 05:15:00', '2026-05-11 05:15:00'),
(29, 'ORD-029', 1, 35, 29, 'Morning Star Printing Network', '2026-05-17', 'completed', 'confirmed', '2026-05-14 09:14:00', '2026-05-14 09:14:00'),
(30, 'ORD-030', 1, 36, 30, 'Morning Star Printing Network', '2026-05-20', 'completed', 'confirmed', '2026-05-17 03:30:00', '2026-05-17 03:30:00'),
(31, 'ORD-031', 1, 37, 31, 'Green Heart Printing Hub', '2026-05-05', 'completed', 'confirmed', '2026-05-02 01:23:00', '2026-05-02 01:23:00'),
(32, 'ORD-032', 1, 38, 32, 'Green Heart Printing Hub', '2026-05-08', 'completed', 'confirmed', '2026-05-05 06:11:00', '2026-05-05 06:11:00'),
(33, 'ORD-033', 1, 39, 33, 'Green Heart Printing Hub', '2026-05-11', 'completed', 'confirmed', '2026-05-08 03:47:00', '2026-05-08 03:47:00'),
(34, 'ORD-034', 1, 40, 34, 'Green Heart Printing Hub', '2026-05-14', 'completed', 'confirmed', '2026-05-11 03:22:00', '2026-05-11 03:22:00'),
(35, 'ORD-035', 1, 41, 35, 'Morning Star Printing Press', '2026-06-05', 'completed', 'confirmed', '2026-06-02 09:10:00', '2026-06-02 09:10:00'),
(36, 'ORD-036', 1, 42, 36, 'Morning Star Printing Press', '2026-06-08', 'completed', 'confirmed', '2026-06-05 08:49:00', '2026-06-05 08:49:00'),
(37, 'ORD-037', 1, 43, 37, 'Morning Star Printing Press', '2026-06-11', 'completed', 'confirmed', '2026-06-08 05:33:00', '2026-06-08 05:33:00'),
(38, 'ORD-038', 1, 44, 38, 'Morning Star Printing Press', '2026-06-14', 'completed', 'confirmed', '2026-06-11 04:16:00', '2026-06-11 04:16:00'),
(39, 'ORD-039', 1, 45, 39, 'Morning Star Printing Press', '2026-06-17', 'completed', 'confirmed', '2026-06-14 09:19:00', '2026-06-14 09:19:00'),
(40, 'ORD-040', 1, 46, 40, 'Morning Star Printing Press', '2026-06-20', 'completed', 'confirmed', '2026-06-17 00:40:00', '2026-06-17 00:40:00'),
(41, 'ORD-041', 1, 47, 41, 'Morning Star Printing Press', '2026-06-23', 'completed', 'confirmed', '2026-06-20 09:46:00', '2026-06-20 09:46:00'),
(42, 'ORD-042', 1, 48, 42, 'Morning Star Printing Press', '2026-06-26', 'completed', 'confirmed', '2026-06-23 01:30:00', '2026-06-23 01:30:00'),
(43, 'ORD-043', 1, 49, 43, 'Morning Star Printing Press', '2026-06-29', 'completed', 'confirmed', '2026-06-26 08:39:00', '2026-06-26 08:39:00'),
(44, 'ORD-044', 1, 50, 44, 'Morning Star Printing Press', '2026-07-01', 'completed', 'confirmed', '2026-06-28 08:43:00', '2026-06-28 08:43:00'),
(45, 'ORD-045', 1, 51, 45, 'Morning Star Printing Press', '2026-07-01', 'completed', 'confirmed', '2026-06-28 00:21:00', '2026-06-28 00:21:00'),
(46, 'ORD-046', 1, 52, 46, 'Morning Star Printing Press', '2026-07-01', 'completed', 'confirmed', '2026-06-28 08:25:00', '2026-06-28 08:25:00'),
(47, 'ORD-047', 1, 53, 47, 'Morning Star Printing Network', '2026-06-05', 'completed', 'confirmed', '2026-06-02 01:48:00', '2026-06-02 01:48:00'),
(48, 'ORD-048', 1, 54, 48, 'Morning Star Printing Network', '2026-06-08', 'completed', 'confirmed', '2026-06-05 02:24:00', '2026-06-05 02:24:00'),
(49, 'ORD-049', 1, 55, 49, 'Morning Star Printing Network', '2026-06-11', 'completed', 'confirmed', '2026-06-08 00:42:00', '2026-06-08 00:42:00'),
(50, 'ORD-050', 1, 56, 50, 'Morning Star Printing Network', '2026-06-14', 'completed', 'confirmed', '2026-06-11 08:46:00', '2026-06-11 08:46:00'),
(51, 'ORD-051', 1, 57, 51, 'Morning Star Printing Network', '2026-06-17', 'completed', 'confirmed', '2026-06-14 05:41:00', '2026-06-14 05:41:00'),
(52, 'ORD-052', 1, 58, 52, 'Morning Star Printing Network', '2026-06-20', 'completed', 'confirmed', '2026-06-17 07:33:00', '2026-06-17 07:33:00'),
(53, 'ORD-053', 1, 59, 53, 'Morning Star Printing Network', '2026-06-23', 'completed', 'confirmed', '2026-06-20 07:10:00', '2026-06-20 07:10:00'),
(54, 'ORD-054', 1, 60, 54, 'Morning Star Printing Network', '2026-06-26', 'completed', 'confirmed', '2026-06-23 03:41:00', '2026-06-23 03:41:00'),
(55, 'ORD-055', 1, 61, 55, 'Green Heart Printing Hub', '2026-06-05', 'completed', 'confirmed', '2026-06-02 06:21:00', '2026-06-02 06:21:00'),
(56, 'ORD-056', 1, 62, 56, 'Green Heart Printing Hub', '2026-06-08', 'completed', 'confirmed', '2026-06-05 01:37:00', '2026-06-05 01:37:00'),
(57, 'ORD-057', 1, 63, 57, 'Green Heart Printing Hub', '2026-06-11', 'completed', 'confirmed', '2026-06-08 01:43:00', '2026-06-08 01:43:00'),
(58, 'ORD-058', 1, 64, 58, 'Green Heart Printing Hub', '2026-06-14', 'completed', 'confirmed', '2026-06-11 01:36:00', '2026-06-11 01:36:00'),
(59, 'ORD-059', 1, 65, 59, 'Green Heart Printing Hub', '2026-06-17', 'completed', 'confirmed', '2026-06-14 09:14:00', '2026-06-14 09:14:00'),
(60, 'ORD-060', 1, 66, 60, 'Morning Star Printing Press', '2026-07-05', 'completed', 'confirmed', '2026-07-02 02:41:00', '2026-07-02 02:41:00'),
(61, 'ORD-061', 1, 67, 61, 'Morning Star Printing Press', '2026-07-08', 'completed', 'confirmed', '2026-07-05 08:16:00', '2026-07-05 08:16:00'),
(62, 'ORD-062', 1, 68, 62, 'Morning Star Printing Press', '2026-07-11', 'completed', 'confirmed', '2026-07-08 02:28:00', '2026-07-08 02:28:00'),
(63, 'ORD-063', 1, 69, 63, 'Morning Star Printing Press', '2026-07-14', 'completed', 'confirmed', '2026-07-11 03:18:00', '2026-07-11 03:18:00'),
(64, 'ORD-064', 1, 70, 64, 'Morning Star Printing Press', '2026-07-17', 'completed', 'confirmed', '2026-07-14 01:12:00', '2026-07-14 01:12:00'),
(65, 'ORD-065', 1, 71, 65, 'Morning Star Printing Press', '2026-07-20', 'completed', 'confirmed', '2026-07-17 05:17:00', '2026-07-17 05:17:00'),
(66, 'ORD-066', 1, 72, 66, 'Morning Star Printing Press', '2026-07-23', 'completed', 'confirmed', '2026-07-20 09:31:00', '2026-07-20 09:31:00'),
(67, 'ORD-067', 1, 73, 67, 'Morning Star Printing Press', '2026-07-26', 'completed', 'confirmed', '2026-07-23 01:11:00', '2026-07-23 01:11:00'),
(68, 'ORD-068', 1, 74, 68, 'Morning Star Printing Press', '2026-07-29', 'completed', 'confirmed', '2026-07-26 06:42:00', '2026-07-26 06:42:00'),
(69, 'ORD-069', 1, 75, 69, 'Morning Star Printing Press', '2026-07-31', 'completed', 'confirmed', '2026-07-28 08:45:00', '2026-07-28 08:45:00'),
(70, 'ORD-070', 1, 76, 70, 'Morning Star Printing Network', '2026-07-05', 'completed', 'confirmed', '2026-07-02 05:31:00', '2026-07-02 05:31:00'),
(71, 'ORD-071', 1, 77, 71, 'Morning Star Printing Network', '2026-07-08', 'completed', 'confirmed', '2026-07-05 06:43:00', '2026-07-05 06:43:00'),
(72, 'ORD-072', 1, 78, 72, 'Morning Star Printing Network', '2026-07-11', 'completed', 'confirmed', '2026-07-08 00:32:00', '2026-07-08 00:32:00'),
(73, 'ORD-073', 1, 79, 73, 'Morning Star Printing Network', '2026-07-14', 'completed', 'confirmed', '2026-07-11 09:50:00', '2026-07-11 09:50:00'),
(74, 'ORD-074', 1, 80, 74, 'Morning Star Printing Network', '2026-07-17', 'completed', 'confirmed', '2026-07-14 05:24:00', '2026-07-14 05:24:00'),
(75, 'ORD-075', 1, 81, 75, 'Morning Star Printing Network', '2026-07-20', 'completed', 'confirmed', '2026-07-17 09:36:00', '2026-07-17 09:36:00'),
(76, 'ORD-076', 1, 82, 76, 'Morning Star Printing Network', '2026-07-23', 'completed', 'confirmed', '2026-07-20 02:40:00', '2026-07-20 02:40:00'),
(77, 'ORD-077', 1, 83, 77, 'Morning Star Printing Network', '2026-07-26', 'completed', 'confirmed', '2026-07-23 02:29:00', '2026-07-23 02:29:00'),
(78, 'ORD-078', 1, 84, 78, 'Green Heart Printing Hub', '2026-07-05', 'completed', 'confirmed', '2026-07-02 06:20:00', '2026-07-02 06:20:00'),
(79, 'ORD-079', 1, 85, 79, 'Green Heart Printing Hub', '2026-07-08', 'completed', 'confirmed', '2026-07-05 01:34:00', '2026-07-05 01:34:00'),
(80, 'ORD-080', 1, 86, 80, 'Green Heart Printing Hub', '2026-07-11', 'completed', 'confirmed', '2026-07-08 05:33:00', '2026-07-08 05:33:00'),
(81, 'ORD-081', 1, 87, 81, 'Green Heart Printing Hub', '2026-07-14', 'completed', 'confirmed', '2026-07-11 05:46:00', '2026-07-11 05:46:00'),
(82, 'ORD-082', 1, 88, 82, 'Green Heart Printing Hub', '2026-07-17', 'completed', 'confirmed', '2026-07-14 05:36:00', '2026-07-14 05:36:00'),
(83, 'ORD-083', 1, 89, 83, 'Morning Star Printing Press', '2026-08-05', 'completed', 'confirmed', '2026-08-02 01:45:00', '2026-08-02 01:45:00'),
(84, 'ORD-084', 1, 90, 84, 'Morning Star Printing Press', '2026-08-08', 'completed', 'confirmed', '2026-08-05 09:37:00', '2026-08-05 09:37:00'),
(85, 'ORD-085', 1, 91, 85, 'Morning Star Printing Press', '2026-08-11', 'completed', 'confirmed', '2026-08-08 08:44:00', '2026-08-08 08:44:00'),
(86, 'ORD-086', 1, 92, 86, 'Morning Star Printing Press', '2026-08-14', 'completed', 'confirmed', '2026-08-11 00:43:00', '2026-08-11 00:43:00'),
(87, 'ORD-087', 1, 93, 87, 'Morning Star Printing Press', '2026-08-17', 'completed', 'confirmed', '2026-08-14 04:17:00', '2026-08-14 04:17:00'),
(88, 'ORD-088', 1, 94, 88, 'Morning Star Printing Press', '2026-08-20', 'completed', 'confirmed', '2026-08-17 09:37:00', '2026-08-17 09:37:00'),
(89, 'ORD-089', 1, 95, 89, 'Morning Star Printing Press', '2026-08-23', 'completed', 'confirmed', '2026-08-20 06:14:00', '2026-08-20 06:14:00'),
(90, 'ORD-090', 1, 96, 90, 'Morning Star Printing Press', '2026-08-26', 'completed', 'confirmed', '2026-08-23 03:17:00', '2026-08-23 03:17:00'),
(91, 'ORD-091', 1, 97, 91, 'Morning Star Printing Press', '2026-08-29', 'completed', 'confirmed', '2026-08-26 05:46:00', '2026-08-26 05:46:00'),
(92, 'ORD-092', 1, 98, 92, 'Morning Star Printing Press', '2026-08-31', 'completed', 'confirmed', '2026-08-28 01:37:00', '2026-08-28 01:37:00'),
(93, 'ORD-093', 1, 99, 93, 'Morning Star Printing Press', '2026-08-31', 'completed', 'confirmed', '2026-08-28 07:38:00', '2026-08-28 07:38:00'),
(94, 'ORD-094', 1, 100, 94, 'Morning Star Printing Press', '2026-08-31', 'completed', 'confirmed', '2026-08-28 08:44:00', '2026-08-28 08:44:00'),
(95, 'ORD-095', 1, 101, 95, 'Morning Star Printing Press', '2026-08-31', 'completed', 'confirmed', '2026-08-28 03:48:00', '2026-08-28 03:48:00'),
(96, 'ORD-096', 1, 102, 96, 'Morning Star Printing Press', '2026-08-31', 'completed', 'confirmed', '2026-08-28 05:36:00', '2026-08-28 05:36:00'),
(97, 'ORD-097', 1, 103, 97, 'Morning Star Printing Press', '2026-08-31', 'completed', 'confirmed', '2026-08-28 07:16:00', '2026-08-28 07:16:00'),
(98, 'ORD-098', 1, 104, 98, 'Morning Star Printing Network', '2026-08-05', 'completed', 'confirmed', '2026-08-02 04:31:00', '2026-08-02 04:31:00'),
(99, 'ORD-099', 1, 105, 99, 'Morning Star Printing Network', '2026-08-08', 'completed', 'confirmed', '2026-08-05 07:12:00', '2026-08-05 07:12:00'),
(100, 'ORD-100', 1, 106, 100, 'Morning Star Printing Network', '2026-08-11', 'completed', 'confirmed', '2026-08-08 05:37:00', '2026-08-08 05:37:00'),
(101, 'ORD-101', 1, 107, 101, 'Morning Star Printing Network', '2026-08-14', 'completed', 'confirmed', '2026-08-11 09:47:00', '2026-08-11 09:47:00'),
(102, 'ORD-102', 1, 108, 102, 'Morning Star Printing Network', '2026-08-17', 'completed', 'confirmed', '2026-08-14 01:44:00', '2026-08-14 01:44:00'),
(103, 'ORD-103', 1, 109, 103, 'Morning Star Printing Network', '2026-08-20', 'completed', 'confirmed', '2026-08-17 07:50:00', '2026-08-17 07:50:00'),
(104, 'ORD-104', 1, 110, 104, 'Morning Star Printing Network', '2026-08-23', 'completed', 'confirmed', '2026-08-20 03:49:00', '2026-08-20 03:49:00'),
(105, 'ORD-105', 1, 111, 105, 'Morning Star Printing Network', '2026-08-26', 'completed', 'confirmed', '2026-08-23 06:49:00', '2026-08-23 06:49:00'),
(106, 'ORD-106', 1, 112, 106, 'Morning Star Printing Network', '2026-08-29', 'completed', 'confirmed', '2026-08-26 08:19:00', '2026-08-26 08:19:00'),
(107, 'ORD-107', 1, 113, 107, 'Green Heart Printing Hub', '2026-08-05', 'completed', 'confirmed', '2026-08-02 08:18:00', '2026-08-02 08:18:00'),
(108, 'ORD-108', 1, 114, 108, 'Green Heart Printing Hub', '2026-08-08', 'completed', 'confirmed', '2026-08-05 03:39:00', '2026-08-05 03:39:00'),
(109, 'ORD-109', 1, 115, 109, 'Green Heart Printing Hub', '2026-08-11', 'completed', 'confirmed', '2026-08-08 07:23:00', '2026-08-08 07:23:00'),
(110, 'ORD-110', 1, 116, 110, 'Green Heart Printing Hub', '2026-08-14', 'completed', 'confirmed', '2026-08-11 06:23:00', '2026-08-11 06:23:00'),
(111, 'ORD-111', 1, 117, 111, 'Green Heart Printing Hub', '2026-08-17', 'completed', 'confirmed', '2026-08-14 03:10:00', '2026-08-14 03:10:00'),
(112, 'ORD-112', 1, 118, 112, 'Green Heart Printing Hub', '2026-08-20', 'completed', 'confirmed', '2026-08-17 00:19:00', '2026-08-17 00:19:00'),
(113, 'ORD-113', 1, 119, 113, 'Green Heart Printing Hub', '2026-08-23', 'completed', 'confirmed', '2026-08-20 02:35:00', '2026-08-20 02:35:00'),
(114, 'ORD-114', 1, 120, 114, 'Morning Star Printing Press', '2026-09-05', 'completed', 'confirmed', '2026-09-02 05:35:00', '2026-09-02 05:35:00'),
(115, 'ORD-115', 1, 121, 115, 'Morning Star Printing Press', '2026-09-08', 'completed', 'confirmed', '2026-09-05 01:20:00', '2026-09-05 01:20:00'),
(116, 'ORD-116', 1, 122, 116, 'Morning Star Printing Press', '2026-09-11', 'production', 'confirmed', '2026-09-08 07:23:00', '2026-09-08 07:23:00'),
(117, 'ORD-117', 1, 123, 117, 'Morning Star Printing Press', '2026-09-14', 'production', 'confirmed', '2026-09-11 01:35:00', '2026-09-11 01:35:00'),
(118, 'ORD-118', 1, 124, 118, 'Morning Star Printing Press', '2026-09-17', 'production', 'confirmed', '2026-09-14 08:49:00', '2026-09-14 08:49:00'),
(119, 'ORD-119', 1, 125, 119, 'Morning Star Printing Press', '2026-09-20', 'production', 'confirmed', '2026-09-17 09:31:00', '2026-09-17 09:31:00'),
(120, 'ORD-120', 1, 126, 120, 'Morning Star Printing Press', '2026-09-23', 'production', 'confirmed', '2026-09-20 09:17:00', '2026-09-20 09:17:00'),
(121, 'ORD-121', 1, 127, 121, 'Morning Star Printing Press', '2026-09-26', 'production', 'confirmed', '2026-09-23 00:21:00', '2026-09-23 00:21:00'),
(122, 'ORD-122', 1, 128, 122, 'Morning Star Printing Network', '2026-09-05', 'completed', 'confirmed', '2026-09-02 06:23:00', '2026-09-02 06:23:00'),
(123, 'ORD-123', 1, 129, 123, 'Morning Star Printing Network', '2026-09-08', 'completed', 'confirmed', '2026-09-05 02:26:00', '2026-09-05 02:26:00'),
(124, 'ORD-124', 1, 130, 124, 'Morning Star Printing Network', '2026-09-11', 'production', 'confirmed', '2026-09-08 06:49:00', '2026-09-08 06:49:00'),
(125, 'ORD-125', 1, 131, 125, 'Morning Star Printing Network', '2026-09-14', 'production', 'confirmed', '2026-09-11 00:23:00', '2026-09-11 00:23:00'),
(126, 'ORD-126', 1, 132, 126, 'Morning Star Printing Network', '2026-09-17', 'production', 'confirmed', '2026-09-14 05:33:00', '2026-09-14 05:33:00'),
(127, 'ORD-127', 1, 133, 127, 'Morning Star Printing Network', '2026-09-20', 'production', 'confirmed', '2026-09-17 01:17:00', '2026-09-17 01:17:00'),
(128, 'ORD-128', 1, 134, 128, 'Green Heart Printing Hub', '2026-09-05', 'completed', 'confirmed', '2026-09-02 05:47:00', '2026-09-02 05:47:00'),
(129, 'ORD-129', 1, 135, 129, 'Green Heart Printing Hub', '2026-09-08', 'completed', 'confirmed', '2026-09-05 01:35:00', '2026-09-05 01:35:00'),
(130, 'ORD-130', 1, 136, 130, 'Green Heart Printing Hub', '2026-09-11', 'production', 'confirmed', '2026-09-08 00:48:00', '2026-09-08 00:48:00'),
(131, 'ORD-2026-201', 15, 139, 132, 'Morning Star Printing Press', '2026-10-17', 'production', 'confirmed', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(132, 'ORD-2026-202', 16, 140, 133, 'Morning Star Printing Press', '2026-10-15', 'ready_for_pickup', 'confirmed', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(133, 'ORD-2026-203', 17, 141, 134, 'Morning Star Printing Network', '2026-10-13', 'claimed', 'confirmed', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(134, 'ORD-2026-204', 18, 142, 135, 'Green Heart Printing Hub', '2026-10-20', 'payment', 'submitted', '2026-10-15 06:37:17', '2026-10-15 06:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Cash on Pickup',
  `amount` decimal(10,2) NOT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `user_id`, `payment_method`, `amount`, `payment_reference`, `status`, `paid_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Bank Transfer', 8500.00, 'PAY-SEED001', 'confirmed', '2026-09-03 05:07:42', NULL, '2026-09-04 05:07:42', '2026-09-04 05:07:42'),
(2, 2, 1, 'GCash', 3135.00, 'PAY-2026-ZGRYDZ', 'confirmed', '2026-04-02 05:38:00', NULL, '2026-04-02 03:38:00', '2026-04-02 03:38:00'),
(3, 3, 1, 'Bank Transfer', 2722.50, 'PAY-2026-PMVZMP', 'confirmed', '2026-04-05 04:38:00', NULL, '2026-04-05 02:38:00', '2026-04-05 02:38:00'),
(4, 4, 1, 'GCash', 2502.50, 'PAY-2026-L1P3YO', 'confirmed', '2026-04-08 02:25:00', NULL, '2026-04-08 00:25:00', '2026-04-08 00:25:00'),
(5, 5, 1, 'Bank Transfer', 2750.00, 'PAY-2026-B4QDWH', 'confirmed', '2026-04-11 08:35:00', NULL, '2026-04-11 06:35:00', '2026-04-11 06:35:00'),
(6, 6, 1, 'GCash', 3162.50, 'PAY-2026-GZZTUN', 'confirmed', '2026-04-14 09:12:00', NULL, '2026-04-14 07:12:00', '2026-04-14 07:12:00'),
(7, 7, 1, 'Cash', 2777.50, 'OTC-CASH-0007', 'confirmed', '2026-04-17 10:36:00', NULL, '2026-04-17 08:36:00', '2026-04-17 08:36:00'),
(8, 8, 1, 'GCash', 2447.50, 'PAY-2026-VQU1XJ', 'confirmed', '2026-04-20 07:12:00', NULL, '2026-04-20 05:12:00', '2026-04-20 05:12:00'),
(9, 9, 1, 'Bank Transfer', 2887.50, 'PAY-2026-BNIRMM', 'confirmed', '2026-04-02 04:50:00', NULL, '2026-04-02 02:50:00', '2026-04-02 02:50:00'),
(10, 10, 1, 'GCash', 2557.50, 'PAY-2026-8RBIOE', 'confirmed', '2026-04-05 03:13:00', NULL, '2026-04-05 01:13:00', '2026-04-05 01:13:00'),
(11, 11, 1, 'Bank Transfer', 2667.50, 'PAY-2026-QISIVQ', 'confirmed', '2026-04-08 11:28:00', NULL, '2026-04-08 09:28:00', '2026-04-08 09:28:00'),
(12, 12, 1, 'GCash', 2502.50, 'PAY-2026-IRAQA7', 'confirmed', '2026-04-11 07:17:00', NULL, '2026-04-11 05:17:00', '2026-04-11 05:17:00'),
(13, 13, 1, 'Bank Transfer', 2447.50, 'PAY-2026-4BORVD', 'confirmed', '2026-04-02 07:42:00', NULL, '2026-04-02 05:42:00', '2026-04-02 05:42:00'),
(14, 14, 1, 'Cash', 2640.00, 'OTC-CASH-0014', 'confirmed', '2026-04-05 03:15:00', NULL, '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(15, 15, 1, 'Bank Transfer', 2777.50, 'PAY-2026-SHPVWY', 'confirmed', '2026-04-08 06:10:00', NULL, '2026-04-08 04:10:00', '2026-04-08 04:10:00'),
(16, 16, 1, 'GCash', 2408.42, 'PAY-2026-C993ZK', 'confirmed', '2026-05-02 03:22:00', NULL, '2026-05-02 01:22:00', '2026-05-02 01:22:00'),
(17, 17, 1, 'Bank Transfer', 2764.21, 'PAY-2026-XQUJQU', 'confirmed', '2026-05-05 04:46:00', NULL, '2026-05-05 02:46:00', '2026-05-05 02:46:00'),
(18, 18, 1, 'GCash', 2764.21, 'PAY-2026-XSLYKC', 'confirmed', '2026-05-08 08:27:00', NULL, '2026-05-08 06:27:00', '2026-05-08 06:27:00'),
(19, 19, 1, 'Bank Transfer', 2901.05, 'PAY-2026-A7V8UK', 'confirmed', '2026-05-11 06:12:00', NULL, '2026-05-11 04:12:00', '2026-05-11 04:12:00'),
(20, 20, 1, 'GCash', 2408.42, 'PAY-2026-FD9Q50', 'confirmed', '2026-05-14 04:26:00', NULL, '2026-05-14 02:26:00', '2026-05-14 02:26:00'),
(21, 21, 1, 'Cash', 2709.47, 'OTC-CASH-0021', 'confirmed', '2026-05-17 11:31:00', NULL, '2026-05-17 09:31:00', '2026-05-17 09:31:00'),
(22, 22, 1, 'GCash', 2435.79, 'PAY-2026-SEJGGZ', 'confirmed', '2026-05-20 02:42:00', NULL, '2026-05-20 00:42:00', '2026-05-20 00:42:00'),
(23, 23, 1, 'Bank Transfer', 3037.89, 'PAY-2026-FWXE3F', 'confirmed', '2026-05-23 10:40:00', NULL, '2026-05-23 08:40:00', '2026-05-23 08:40:00'),
(24, 24, 1, 'GCash', 2435.79, 'PAY-2026-EPVVWG', 'confirmed', '2026-05-26 09:18:00', NULL, '2026-05-26 07:18:00', '2026-05-26 07:18:00'),
(25, 25, 1, 'Bank Transfer', 2955.79, 'PAY-2026-Y6IXGX', 'confirmed', '2026-05-02 05:15:00', NULL, '2026-05-02 03:15:00', '2026-05-02 03:15:00'),
(26, 26, 1, 'GCash', 2408.42, 'PAY-2026-CIN9ZJ', 'confirmed', '2026-05-05 04:13:00', NULL, '2026-05-05 02:13:00', '2026-05-05 02:13:00'),
(27, 27, 1, 'Bank Transfer', 3037.89, 'PAY-2026-BUXBT4', 'confirmed', '2026-05-08 02:38:00', NULL, '2026-05-08 00:38:00', '2026-05-08 00:38:00'),
(28, 28, 1, 'Cash', 2600.00, 'OTC-CASH-0028', 'confirmed', '2026-05-11 07:15:00', NULL, '2026-05-11 05:15:00', '2026-05-11 05:15:00'),
(29, 29, 1, 'Bank Transfer', 2600.00, 'PAY-2026-6A364E', 'confirmed', '2026-05-14 11:14:00', NULL, '2026-05-14 09:14:00', '2026-05-14 09:14:00'),
(30, 30, 1, 'GCash', 2901.05, 'PAY-2026-N0ZAYX', 'confirmed', '2026-05-17 05:30:00', NULL, '2026-05-17 03:30:00', '2026-05-17 03:30:00'),
(31, 31, 1, 'Bank Transfer', 2791.58, 'PAY-2026-GVQN2O', 'confirmed', '2026-05-02 03:23:00', NULL, '2026-05-02 01:23:00', '2026-05-02 01:23:00'),
(32, 32, 1, 'GCash', 2791.58, 'PAY-2026-QPINE7', 'confirmed', '2026-05-05 08:11:00', NULL, '2026-05-05 06:11:00', '2026-05-05 06:11:00'),
(33, 33, 1, 'Bank Transfer', 2764.21, 'PAY-2026-GUUH2S', 'confirmed', '2026-05-08 05:47:00', NULL, '2026-05-08 03:47:00', '2026-05-08 03:47:00'),
(34, 34, 1, 'GCash', 2600.00, 'PAY-2026-HBWO04', 'confirmed', '2026-05-11 05:22:00', NULL, '2026-05-11 03:22:00', '2026-05-11 03:22:00'),
(35, 35, 1, 'Cash', 2578.15, 'OTC-CASH-0035', 'confirmed', '2026-06-02 11:10:00', NULL, '2026-06-02 09:10:00', '2026-06-02 09:10:00'),
(36, 36, 1, 'GCash', 2788.62, 'PAY-2026-2ECYTY', 'confirmed', '2026-06-05 10:49:00', NULL, '2026-06-05 08:49:00', '2026-06-05 08:49:00'),
(37, 37, 1, 'Bank Transfer', 2446.62, 'PAY-2026-3BYNC3', 'confirmed', '2026-06-08 07:33:00', NULL, '2026-06-08 05:33:00', '2026-06-08 05:33:00'),
(38, 38, 1, 'GCash', 2762.31, 'PAY-2026-E5N9HW', 'confirmed', '2026-06-11 06:16:00', NULL, '2026-06-11 04:16:00', '2026-06-11 04:16:00'),
(39, 39, 1, 'Bank Transfer', 2920.15, 'PAY-2026-WVKGKA', 'confirmed', '2026-06-14 11:19:00', NULL, '2026-06-14 09:19:00', '2026-06-14 09:19:00'),
(40, 40, 1, 'GCash', 2525.54, 'PAY-2026-UAHVUM', 'confirmed', '2026-06-17 02:40:00', NULL, '2026-06-17 00:40:00', '2026-06-17 00:40:00'),
(41, 41, 1, 'Bank Transfer', 2367.69, 'PAY-2026-I5MXYB', 'confirmed', '2026-06-20 11:46:00', NULL, '2026-06-20 09:46:00', '2026-06-20 09:46:00'),
(42, 42, 1, 'Cash', 2683.38, 'OTC-CASH-0042', 'confirmed', '2026-06-23 03:30:00', NULL, '2026-06-23 01:30:00', '2026-06-23 01:30:00'),
(43, 43, 1, 'Bank Transfer', 2841.23, 'PAY-2026-V3RBGX', 'confirmed', '2026-06-26 10:39:00', NULL, '2026-06-26 08:39:00', '2026-06-26 08:39:00'),
(44, 44, 1, 'GCash', 2394.00, 'PAY-2026-2QO3X2', 'confirmed', '2026-06-28 10:43:00', NULL, '2026-06-28 08:43:00', '2026-06-28 08:43:00'),
(45, 45, 1, 'Bank Transfer', 2236.15, 'PAY-2026-YXTRG8', 'confirmed', '2026-06-28 02:21:00', NULL, '2026-06-28 00:21:00', '2026-06-28 00:21:00'),
(46, 46, 1, 'GCash', 2472.92, 'PAY-2026-YZNOWK', 'confirmed', '2026-06-28 10:25:00', NULL, '2026-06-28 08:25:00', '2026-06-28 08:25:00'),
(47, 47, 1, 'Bank Transfer', 2946.46, 'PAY-2026-LKOI39', 'confirmed', '2026-06-02 03:48:00', NULL, '2026-06-02 01:48:00', '2026-06-02 01:48:00'),
(48, 48, 1, 'GCash', 2736.00, 'PAY-2026-ICQM3N', 'confirmed', '2026-06-05 04:24:00', NULL, '2026-06-05 02:24:00', '2026-06-05 02:24:00'),
(49, 49, 1, 'Cash', 2236.15, 'OTC-CASH-0049', 'confirmed', '2026-06-08 02:42:00', NULL, '2026-06-08 00:42:00', '2026-06-08 00:42:00'),
(50, 50, 1, 'GCash', 2525.54, 'PAY-2026-FTUBU6', 'confirmed', '2026-06-11 10:46:00', NULL, '2026-06-11 08:46:00', '2026-06-11 08:46:00'),
(51, 51, 1, 'Bank Transfer', 2657.08, 'PAY-2026-LXPETI', 'confirmed', '2026-06-14 07:41:00', NULL, '2026-06-14 05:41:00', '2026-06-14 05:41:00'),
(52, 52, 1, 'GCash', 2472.92, 'PAY-2026-EYBZ86', 'confirmed', '2026-06-17 09:33:00', NULL, '2026-06-17 07:33:00', '2026-06-17 07:33:00'),
(53, 53, 1, 'Bank Transfer', 2578.15, 'PAY-2026-IJD6WQ', 'confirmed', '2026-06-20 09:10:00', NULL, '2026-06-20 07:10:00', '2026-06-20 07:10:00'),
(54, 54, 1, 'GCash', 2630.77, 'PAY-2026-AC3Z2Z', 'confirmed', '2026-06-23 05:41:00', NULL, '2026-06-23 03:41:00', '2026-06-23 03:41:00'),
(55, 55, 1, 'Bank Transfer', 2604.46, 'PAY-2026-HXTIZ2', 'confirmed', '2026-06-02 08:21:00', NULL, '2026-06-02 06:21:00', '2026-06-02 06:21:00'),
(56, 56, 1, 'Cash', 2893.85, 'OTC-CASH-0056', 'confirmed', '2026-06-05 03:37:00', NULL, '2026-06-05 01:37:00', '2026-06-05 01:37:00'),
(57, 57, 1, 'Bank Transfer', 2841.23, 'PAY-2026-0N5MFF', 'confirmed', '2026-06-08 03:43:00', NULL, '2026-06-08 01:43:00', '2026-06-08 01:43:00'),
(58, 58, 1, 'GCash', 2893.85, 'PAY-2026-ZVWZ5Y', 'confirmed', '2026-06-11 03:36:00', NULL, '2026-06-11 01:36:00', '2026-06-11 01:36:00'),
(59, 59, 1, 'Bank Transfer', 2315.08, 'PAY-2026-OXSRTP', 'confirmed', '2026-06-14 11:14:00', NULL, '2026-06-14 09:14:00', '2026-06-14 09:14:00'),
(60, 60, 1, 'GCash', 2714.09, 'PAY-2026-V6ZGZD', 'confirmed', '2026-07-02 04:41:00', NULL, '2026-07-02 02:41:00', '2026-07-02 02:41:00'),
(61, 61, 1, 'Bank Transfer', 2900.35, 'PAY-2026-PKY4Q3', 'confirmed', '2026-07-05 10:16:00', NULL, '2026-07-05 08:16:00', '2026-07-05 08:16:00'),
(62, 62, 1, 'GCash', 2740.70, 'PAY-2026-OJIJJC', 'confirmed', '2026-07-08 04:28:00', NULL, '2026-07-08 02:28:00', '2026-07-08 02:28:00'),
(63, 63, 1, 'Cash', 2341.57, 'OTC-CASH-0063', 'confirmed', '2026-07-11 05:18:00', NULL, '2026-07-11 03:18:00', '2026-07-11 03:18:00'),
(64, 64, 1, 'GCash', 2394.78, 'PAY-2026-SR1HSW', 'confirmed', '2026-07-14 03:12:00', NULL, '2026-07-14 01:12:00', '2026-07-14 01:12:00'),
(65, 65, 1, 'Bank Transfer', 2501.22, 'PAY-2026-BRX7IU', 'confirmed', '2026-07-17 07:17:00', NULL, '2026-07-17 05:17:00', '2026-07-17 05:17:00'),
(66, 66, 1, 'GCash', 2288.35, 'PAY-2026-WPGQ3R', 'confirmed', '2026-07-20 11:31:00', NULL, '2026-07-20 09:31:00', '2026-07-20 09:31:00'),
(67, 67, 1, 'Bank Transfer', 2421.39, 'PAY-2026-KTLIBS', 'confirmed', '2026-07-23 03:11:00', NULL, '2026-07-23 01:11:00', '2026-07-23 01:11:00'),
(68, 68, 1, 'GCash', 2448.00, 'PAY-2026-VFBYAX', 'confirmed', '2026-07-26 08:42:00', NULL, '2026-07-26 06:42:00', '2026-07-26 06:42:00'),
(69, 69, 1, 'Bank Transfer', 2527.83, 'PAY-2026-UO39CP', 'confirmed', '2026-07-28 10:45:00', NULL, '2026-07-28 08:45:00', '2026-07-28 08:45:00'),
(70, 70, 1, 'Cash', 2554.43, 'OTC-CASH-0070', 'confirmed', '2026-07-02 07:31:00', NULL, '2026-07-02 05:31:00', '2026-07-02 05:31:00'),
(71, 71, 1, 'Bank Transfer', 2607.65, 'PAY-2026-FSTGCD', 'confirmed', '2026-07-05 08:43:00', NULL, '2026-07-05 06:43:00', '2026-07-05 06:43:00'),
(72, 72, 1, 'GCash', 2581.04, 'PAY-2026-DNDXLI', 'confirmed', '2026-07-08 02:32:00', NULL, '2026-07-08 00:32:00', '2026-07-08 00:32:00'),
(73, 73, 1, 'Bank Transfer', 2900.35, 'PAY-2026-RUJOIO', 'confirmed', '2026-07-11 11:50:00', NULL, '2026-07-11 09:50:00', '2026-07-11 09:50:00'),
(74, 74, 1, 'GCash', 2847.13, 'PAY-2026-DRGSYE', 'confirmed', '2026-07-14 07:24:00', NULL, '2026-07-14 05:24:00', '2026-07-14 05:24:00'),
(75, 75, 1, 'Bank Transfer', 2740.70, 'PAY-2026-LW3LSH', 'confirmed', '2026-07-17 11:36:00', NULL, '2026-07-17 09:36:00', '2026-07-17 09:36:00'),
(76, 76, 1, 'GCash', 2448.00, 'PAY-2026-6G219O', 'confirmed', '2026-07-20 04:40:00', NULL, '2026-07-20 02:40:00', '2026-07-20 02:40:00'),
(77, 77, 1, 'Cash', 2714.09, 'OTC-CASH-0077', 'confirmed', '2026-07-23 04:29:00', NULL, '2026-07-23 02:29:00', '2026-07-23 02:29:00'),
(78, 78, 1, 'GCash', 2394.78, 'PAY-2026-XUNH5O', 'confirmed', '2026-07-02 08:20:00', NULL, '2026-07-02 06:20:00', '2026-07-02 06:20:00'),
(79, 79, 1, 'Bank Transfer', 2554.43, 'PAY-2026-QZF896', 'confirmed', '2026-07-05 03:34:00', NULL, '2026-07-05 01:34:00', '2026-07-05 01:34:00'),
(80, 80, 1, 'GCash', 2448.00, 'PAY-2026-VNWEUS', 'confirmed', '2026-07-08 07:33:00', NULL, '2026-07-08 05:33:00', '2026-07-08 05:33:00'),
(81, 81, 1, 'Bank Transfer', 2554.43, 'PAY-2026-LIUYVD', 'confirmed', '2026-07-11 07:46:00', NULL, '2026-07-11 05:46:00', '2026-07-11 05:46:00'),
(82, 82, 1, 'GCash', 2288.35, 'PAY-2026-DVVJH7', 'confirmed', '2026-07-14 07:36:00', NULL, '2026-07-14 05:36:00', '2026-07-14 05:36:00'),
(83, 83, 1, 'Bank Transfer', 2807.58, 'PAY-2026-CHUJ7J', 'confirmed', '2026-08-02 03:45:00', NULL, '2026-08-02 01:45:00', '2026-08-02 01:45:00'),
(84, 84, 1, 'Cash', 2998.39, 'OTC-CASH-0084', 'confirmed', '2026-08-05 11:37:00', NULL, '2026-08-05 09:37:00', '2026-08-05 09:37:00'),
(85, 85, 1, 'Bank Transfer', 3107.42, 'PAY-2026-AITWVI', 'confirmed', '2026-08-08 10:44:00', NULL, '2026-08-08 08:44:00', '2026-08-08 08:44:00'),
(86, 86, 1, 'GCash', 2316.94, 'PAY-2026-X4DISH', 'confirmed', '2026-08-11 02:43:00', NULL, '2026-08-11 00:43:00', '2026-08-11 00:43:00'),
(87, 87, 1, 'Bank Transfer', 2943.87, 'PAY-2026-J6IIQ9', 'confirmed', '2026-08-14 06:17:00', NULL, '2026-08-14 04:17:00', '2026-08-14 04:17:00'),
(88, 88, 1, 'GCash', 2507.74, 'PAY-2026-TTHXIJ', 'confirmed', '2026-08-17 11:37:00', NULL, '2026-08-17 09:37:00', '2026-08-17 09:37:00'),
(89, 89, 1, 'Bank Transfer', 2834.84, 'PAY-2026-QZTKFS', 'confirmed', '2026-08-20 08:14:00', NULL, '2026-08-20 06:14:00', '2026-08-20 06:14:00'),
(90, 90, 1, 'GCash', 3025.65, 'PAY-2026-T7PQZ4', 'confirmed', '2026-08-23 05:17:00', NULL, '2026-08-23 03:17:00', '2026-08-23 03:17:00'),
(91, 91, 1, 'Cash', 2589.52, 'OTC-CASH-0091', 'confirmed', '2026-08-26 07:46:00', NULL, '2026-08-26 05:46:00', '2026-08-26 05:46:00'),
(92, 92, 1, 'GCash', 3080.16, 'PAY-2026-AXH6FX', 'confirmed', '2026-08-28 03:37:00', NULL, '2026-08-28 01:37:00', '2026-08-28 01:37:00'),
(93, 93, 1, 'Bank Transfer', 2971.13, 'PAY-2026-LZ88XS', 'confirmed', '2026-08-28 09:38:00', NULL, '2026-08-28 07:38:00', '2026-08-28 07:38:00'),
(94, 94, 1, 'GCash', 3052.90, 'PAY-2026-YMTHYN', 'confirmed', '2026-08-28 10:44:00', NULL, '2026-08-28 08:44:00', '2026-08-28 08:44:00'),
(95, 95, 1, 'Bank Transfer', 3134.68, 'PAY-2026-FY465Q', 'confirmed', '2026-08-28 05:48:00', NULL, '2026-08-28 03:48:00', '2026-08-28 03:48:00'),
(96, 96, 1, 'GCash', 2316.94, 'PAY-2026-D8ELNX', 'confirmed', '2026-08-28 07:36:00', NULL, '2026-08-28 05:36:00', '2026-08-28 05:36:00'),
(97, 97, 1, 'Bank Transfer', 2807.58, 'PAY-2026-3DMTRT', 'confirmed', '2026-08-28 09:16:00', NULL, '2026-08-28 07:16:00', '2026-08-28 07:16:00'),
(98, 98, 1, 'Cash', 2562.26, 'OTC-CASH-0098', 'confirmed', '2026-08-02 06:31:00', NULL, '2026-08-02 04:31:00', '2026-08-02 04:31:00'),
(99, 99, 1, 'Bank Transfer', 2644.03, 'PAY-2026-NTASLR', 'confirmed', '2026-08-05 09:12:00', NULL, '2026-08-05 07:12:00', '2026-08-05 07:12:00'),
(100, 100, 1, 'GCash', 2971.13, 'PAY-2026-BOWPFE', 'confirmed', '2026-08-08 07:37:00', NULL, '2026-08-08 05:37:00', '2026-08-08 05:37:00'),
(101, 101, 1, 'Bank Transfer', 2943.87, 'PAY-2026-VXYN7T', 'confirmed', '2026-08-11 11:47:00', NULL, '2026-08-11 09:47:00', '2026-08-11 09:47:00'),
(102, 102, 1, 'GCash', 2671.29, 'PAY-2026-3O7MUS', 'confirmed', '2026-08-14 03:44:00', NULL, '2026-08-14 01:44:00', '2026-08-14 01:44:00'),
(103, 103, 1, 'Bank Transfer', 3080.16, 'PAY-2026-WYAZEU', 'confirmed', '2026-08-17 09:50:00', NULL, '2026-08-17 07:50:00', '2026-08-17 07:50:00'),
(104, 104, 1, 'GCash', 2344.19, 'PAY-2026-RYCLI8', 'confirmed', '2026-08-20 05:49:00', NULL, '2026-08-20 03:49:00', '2026-08-20 03:49:00'),
(105, 105, 1, 'Cash', 2562.26, 'OTC-CASH-0105', 'confirmed', '2026-08-23 08:49:00', NULL, '2026-08-23 06:49:00', '2026-08-23 06:49:00'),
(106, 106, 1, 'GCash', 2398.71, 'PAY-2026-V4NVPU', 'confirmed', '2026-08-26 10:19:00', NULL, '2026-08-26 08:19:00', '2026-08-26 08:19:00'),
(107, 107, 1, 'Bank Transfer', 2398.71, 'PAY-2026-OXEVUE', 'confirmed', '2026-08-02 10:18:00', NULL, '2026-08-02 08:18:00', '2026-08-02 08:18:00'),
(108, 108, 1, 'GCash', 2616.77, 'PAY-2026-COFBRP', 'confirmed', '2026-08-05 05:39:00', NULL, '2026-08-05 03:39:00', '2026-08-05 03:39:00'),
(109, 109, 1, 'Bank Transfer', 3107.42, 'PAY-2026-X5UP9Q', 'confirmed', '2026-08-08 09:23:00', NULL, '2026-08-08 07:23:00', '2026-08-08 07:23:00'),
(110, 110, 1, 'GCash', 2698.55, 'PAY-2026-QQB2VT', 'confirmed', '2026-08-11 08:23:00', NULL, '2026-08-11 06:23:00', '2026-08-11 06:23:00'),
(111, 111, 1, 'Bank Transfer', 2589.52, 'PAY-2026-W5WZ54', 'confirmed', '2026-08-14 05:10:00', NULL, '2026-08-14 03:10:00', '2026-08-14 03:10:00'),
(112, 112, 1, 'Cash', 2562.26, 'OTC-CASH-0112', 'confirmed', '2026-08-17 02:19:00', NULL, '2026-08-17 00:19:00', '2026-08-17 00:19:00'),
(113, 113, 1, 'Bank Transfer', 2753.06, 'PAY-2026-HUPOTB', 'confirmed', '2026-08-20 04:35:00', NULL, '2026-08-20 02:35:00', '2026-08-20 02:35:00'),
(114, 114, 1, 'GCash', 2752.94, 'PAY-2026-2I2YT3', 'confirmed', '2026-09-02 07:35:00', NULL, '2026-09-02 05:35:00', '2026-09-02 05:35:00'),
(115, 115, 1, 'Bank Transfer', 2918.12, 'PAY-2026-TUYSMA', 'confirmed', '2026-09-05 03:20:00', NULL, '2026-09-05 01:20:00', '2026-09-05 01:20:00'),
(116, 116, 1, 'GCash', 2367.53, 'PAY-2026-QWSSUC', 'confirmed', '2026-09-08 09:23:00', NULL, '2026-09-08 07:23:00', '2026-09-08 07:23:00'),
(117, 117, 1, 'Bank Transfer', 2697.88, 'PAY-2026-CMMJVD', 'confirmed', '2026-09-11 03:35:00', NULL, '2026-09-11 01:35:00', '2026-09-11 01:35:00'),
(118, 118, 1, 'GCash', 2945.65, 'PAY-2026-GZX0TW', 'confirmed', '2026-09-14 10:49:00', NULL, '2026-09-14 08:49:00', '2026-09-14 08:49:00'),
(119, 119, 1, 'Cash', 2560.24, 'OTC-CASH-0119', 'confirmed', '2026-09-17 11:31:00', NULL, '2026-09-17 09:31:00', '2026-09-17 09:31:00'),
(120, 120, 1, 'GCash', 3110.82, 'PAY-2026-MWZJYZ', 'confirmed', '2026-09-20 11:17:00', NULL, '2026-09-20 09:17:00', '2026-09-20 09:17:00'),
(121, 121, 1, 'Bank Transfer', 2973.18, 'PAY-2026-WTFDIT', 'confirmed', '2026-09-23 02:21:00', NULL, '2026-09-23 00:21:00', '2026-09-23 00:21:00'),
(122, 122, 1, 'GCash', 2587.76, 'PAY-2026-XY0TZM', 'confirmed', '2026-09-02 08:23:00', NULL, '2026-09-02 06:23:00', '2026-09-02 06:23:00'),
(123, 123, 1, 'Bank Transfer', 2670.35, 'PAY-2026-YGI3FK', 'confirmed', '2026-09-05 04:26:00', NULL, '2026-09-05 02:26:00', '2026-09-05 02:26:00'),
(124, 124, 1, 'GCash', 2450.12, 'PAY-2026-8OJNLA', 'confirmed', '2026-09-08 08:49:00', NULL, '2026-09-08 06:49:00', '2026-09-08 06:49:00'),
(125, 125, 1, 'Bank Transfer', 2340.00, 'PAY-2026-04NK5G', 'confirmed', '2026-09-11 02:23:00', NULL, '2026-09-11 00:23:00', '2026-09-11 00:23:00'),
(126, 126, 1, 'Cash', 2505.18, 'OTC-CASH-0126', 'confirmed', '2026-09-14 07:33:00', NULL, '2026-09-14 05:33:00', '2026-09-14 05:33:00'),
(127, 127, 1, 'Bank Transfer', 2367.53, 'PAY-2026-MROIET', 'confirmed', '2026-09-17 03:17:00', NULL, '2026-09-17 01:17:00', '2026-09-17 01:17:00'),
(128, 128, 1, 'GCash', 2532.71, 'PAY-2026-ABTTSM', 'confirmed', '2026-09-02 07:47:00', NULL, '2026-09-02 05:47:00', '2026-09-02 05:47:00'),
(129, 129, 1, 'Bank Transfer', 2945.65, 'PAY-2026-BHZXTU', 'confirmed', '2026-09-05 03:35:00', NULL, '2026-09-05 01:35:00', '2026-09-05 01:35:00'),
(130, 130, 1, 'GCash', 2697.88, 'PAY-2026-XKK6FH', 'confirmed', '2026-09-08 02:48:00', NULL, '2026-09-08 00:48:00', '2026-09-08 00:48:00'),
(131, 131, 15, 'GCash', 2350.00, 'PAY-GCASH-98214', 'confirmed', '2026-10-14 06:37:17', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(132, 132, 16, 'Bank Transfer', 3150.00, 'PAY-BDO-55192', 'confirmed', '2026-10-13 06:37:17', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(133, 133, 17, 'Bank Transfer', 4200.00, 'PAY-CC-77189', 'confirmed', '2026-10-12 06:37:17', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(134, 134, 18, 'Bank Transfer', 2800.00, 'PAY-BPI-44219', 'submitted', '2026-10-15 02:37:17', 'Transferred via BPI Mobile Banking. Awaiting staff receipt verification.', '2026-10-15 06:37:17', '2026-10-15 06:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `pricing_rules`
--

CREATE TABLE `pricing_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `base_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `material_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `finishing_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricing_rules`
--

INSERT INTO `pricing_rules` (`id`, `service`, `size`, `base_rate`, `material_rate`, `finishing_rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Document Printing', 'A4', 3.50, 1.50, 0.50, 1, '2026-08-20 04:29:57', '2026-09-06 07:34:19'),
(2, 'Flyers & Brochures', 'A4', 5.00, 2.50, 1.00, 1, '2026-08-20 04:29:57', '2026-08-20 04:29:57'),
(3, 'Tarpaulin Banner', '3x4 ft', 25.00, 15.00, 5.00, 1, '2026-08-20 04:29:57', '2026-08-20 04:29:57'),
(4, 'Calling Cards', 'Standard Box', 150.00, 50.00, 25.00, 1, '2026-08-20 04:29:57', '2026-08-20 04:29:57'),
(5, 'Stickers & Labels', 'A4 Sheet', 45.00, 20.00, 10.00, 1, '2026-09-12 06:23:46', '2026-09-12 06:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `print_requests`
--

CREATE TABLE `print_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service` varchar(255) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `size` varchar(255) NOT NULL,
  `material` varchar(255) NOT NULL,
  `finishing` varchar(255) NOT NULL DEFAULT 'None',
  `deadline` date DEFAULT NULL,
  `preferred_branch` varchar(255) DEFAULT NULL,
  `additional_instructions` text DEFAULT NULL,
  `design_file_path` varchar(255) DEFAULT NULL,
  `proof_file_path` varchar(255) DEFAULT NULL,
  `proof_status` enum('pending_proof','proof_uploaded','proof_approved','revision_requested') NOT NULL DEFAULT 'pending_proof',
  `proof_notes` text DEFAULT NULL,
  `design_file_name` varchar(255) DEFAULT NULL,
  `design_file_size` varchar(255) DEFAULT NULL,
  `collection_mode` varchar(255) NOT NULL DEFAULT 'pickup',
  `status` varchar(255) NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `print_requests`
--

INSERT INTO `print_requests` (`id`, `user_id`, `service`, `quantity`, `size`, `material`, `finishing`, `deadline`, `preferred_branch`, `additional_instructions`, `design_file_path`, `proof_file_path`, `proof_status`, `proof_notes`, `design_file_name`, `design_file_size`, `collection_mode`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Flyers & Brochures', 1500, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Gloss Lamination', '2026-09-17', 'Morning Star Printing Press', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-08-20 04:29:57', '2026-09-12 22:28:12'),
(2, 1, 'Tarpaulin Banner', 8, '3x4 ft', 'Flex Tarpaulin Banner Media 13oz', 'Eyelets & Hemming', '2026-09-15', 'Morning Star Printing Network', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'ready_for_pickup', '2026-08-20 04:29:57', '2026-09-12 22:28:12'),
(3, 1, 'Tarpaulin Printing', 1, '2 × 3 FT (6.00 sq ft)', '15oz Blockout Premium Tarpaulin', 'With Eyelets Only', '2026-09-06', 'Branch 1 – Main', NULL, 'design-files/1/hAkTVwH8ENqIlSidltgpOen5JprqxT7ZyRzVFHeV.png', NULL, 'pending_proof', NULL, 'Screenshot 2026-09-04 214345.png', '57 KB', 'shipping', 'cancelled', '2026-09-04 05:51:49', '2026-09-04 05:52:29'),
(4, 1, 'Tarpaulin Printing', 2, '2x3', '10oz Standard Outdoor Tarpaulin', 'Allowance with Eyelets', '2026-09-10', 'Branch 2 – North', 'NA.', 'design-files/1/UVktyU6qXP2j0E6CKforEPC6QJlorEu1KlCNUnmV.png', NULL, 'pending_proof', NULL, 'Screenshot 2026-09-07 093341.png', '260 KB', 'pickup', 'submitted', '2026-09-06 23:46:44', '2026-09-06 23:46:44'),
(5, 1, 'Calling Cards', 10, 'Standard Box', 'Matte Cardstock 220gsm', 'Matte Coating', '2026-09-16', 'Green Heart Printing Hub', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-12 06:24:31', '2026-09-12 22:28:12'),
(6, 1, 'Stickers & Labels', 500, 'A4 Sheet', 'Gloss Vinyl Waterproof Sticker Roll', 'Kiss Cut', '2026-09-18', 'Morning Star Printing Press', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'submitted', '2026-09-12 06:24:31', '2026-09-12 22:28:12'),
(7, 1, 'Document Printing', 300, 'A4', 'Premium Bond Paper 80gsm', 'Wire-O Binding', '2026-09-11', 'Morning Star Printing Network', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-12 06:24:31', '2026-09-12 22:28:12'),
(8, 1, 'Tarpaulin Banner', 864, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-07', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-101', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-02 03:38:00', '2026-04-02 03:38:00'),
(9, 1, 'Document Printing', 914, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-10', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-102', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-05 02:38:00', '2026-04-05 02:38:00'),
(10, 1, 'Flyers & Brochures', 592, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-13', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-103', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-08 00:25:00', '2026-04-08 00:25:00'),
(11, 1, 'Calling Cards', 691, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-16', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-104', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-11 06:35:00', '2026-04-11 06:35:00'),
(12, 1, 'Stickers & Labels', 100, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-19', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-105', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-14 07:12:00', '2026-04-14 07:12:00'),
(13, 1, 'Tarpaulin Banner', 688, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-22', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-106', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-17 08:36:00', '2026-04-17 08:36:00'),
(14, 1, 'Document Printing', 357, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-25', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-107', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-20 05:12:00', '2026-04-20 05:12:00'),
(15, 1, 'Document Printing', 222, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-07', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-108', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-02 02:50:00', '2026-04-02 02:50:00'),
(16, 1, 'Flyers & Brochures', 917, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-10', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-109', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-05 01:13:00', '2026-04-05 01:13:00'),
(17, 1, 'Calling Cards', 718, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-13', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-110', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-08 09:28:00', '2026-04-08 09:28:00'),
(18, 1, 'Stickers & Labels', 601, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-16', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-111', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-11 05:17:00', '2026-04-11 05:17:00'),
(19, 1, 'Calling Cards', 498, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-07', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-112', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-02 05:42:00', '2026-04-02 05:42:00'),
(20, 1, 'Stickers & Labels', 882, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-10', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-113', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(21, 1, 'Tarpaulin Banner', 114, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-04-13', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-114', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-04-08 04:10:00', '2026-04-08 04:10:00'),
(22, 1, 'Flyers & Brochures', 262, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-07', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-115', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-02 01:22:00', '2026-05-02 01:22:00'),
(23, 1, 'Calling Cards', 933, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-10', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-116', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-05 02:46:00', '2026-05-05 02:46:00'),
(24, 1, 'Stickers & Labels', 849, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-13', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-117', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-08 06:27:00', '2026-05-08 06:27:00'),
(25, 1, 'Tarpaulin Banner', 172, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-16', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-118', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-11 04:12:00', '2026-05-11 04:12:00'),
(26, 1, 'Document Printing', 779, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-19', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-119', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-14 02:26:00', '2026-05-14 02:26:00'),
(27, 1, 'Flyers & Brochures', 576, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-22', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-120', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-17 09:31:00', '2026-05-17 09:31:00'),
(28, 1, 'Calling Cards', 507, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-25', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-121', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-20 00:42:00', '2026-05-20 00:42:00'),
(29, 1, 'Stickers & Labels', 987, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-28', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-122', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-23 08:40:00', '2026-05-23 08:40:00'),
(30, 1, 'Tarpaulin Banner', 722, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-31', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-123', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-26 07:18:00', '2026-05-26 07:18:00'),
(31, 1, 'Stickers & Labels', 172, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-07', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-124', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-02 03:15:00', '2026-05-02 03:15:00'),
(32, 1, 'Tarpaulin Banner', 569, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-10', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-125', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-05 02:13:00', '2026-05-05 02:13:00'),
(33, 1, 'Document Printing', 158, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-13', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-126', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-08 00:38:00', '2026-05-08 00:38:00'),
(34, 1, 'Flyers & Brochures', 491, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-16', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-127', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-11 05:15:00', '2026-05-11 05:15:00'),
(35, 1, 'Calling Cards', 960, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-19', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-128', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-14 09:14:00', '2026-05-14 09:14:00'),
(36, 1, 'Stickers & Labels', 906, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-22', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-129', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-17 03:30:00', '2026-05-17 03:30:00'),
(37, 1, 'Flyers & Brochures', 522, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-07', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-130', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-02 01:23:00', '2026-05-02 01:23:00'),
(38, 1, 'Calling Cards', 153, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-10', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-131', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-05 06:11:00', '2026-05-05 06:11:00'),
(39, 1, 'Stickers & Labels', 914, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-13', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-132', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-08 03:47:00', '2026-05-08 03:47:00'),
(40, 1, 'Tarpaulin Banner', 405, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-05-16', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-133', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-05-11 03:22:00', '2026-05-11 03:22:00'),
(41, 1, 'Stickers & Labels', 367, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-07', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-134', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-02 09:10:00', '2026-06-02 09:10:00'),
(42, 1, 'Tarpaulin Banner', 566, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-10', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-135', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-05 08:49:00', '2026-06-05 08:49:00'),
(43, 1, 'Document Printing', 279, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-13', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-136', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-08 05:33:00', '2026-06-08 05:33:00'),
(44, 1, 'Flyers & Brochures', 551, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-16', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-137', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-11 04:16:00', '2026-06-11 04:16:00'),
(45, 1, 'Calling Cards', 599, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-19', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-138', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-14 09:19:00', '2026-06-14 09:19:00'),
(46, 1, 'Stickers & Labels', 420, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-22', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-139', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-17 00:40:00', '2026-06-17 00:40:00'),
(47, 1, 'Tarpaulin Banner', 525, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-25', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-140', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-20 09:46:00', '2026-06-20 09:46:00'),
(48, 1, 'Document Printing', 809, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-28', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-141', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-23 01:30:00', '2026-06-23 01:30:00'),
(49, 1, 'Flyers & Brochures', 792, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-01', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-142', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-26 08:39:00', '2026-06-26 08:39:00'),
(50, 1, 'Calling Cards', 725, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-03', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-143', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-28 08:43:00', '2026-06-28 08:43:00'),
(51, 1, 'Stickers & Labels', 548, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-03', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-144', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-28 00:21:00', '2026-06-28 00:21:00'),
(52, 1, 'Tarpaulin Banner', 713, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-03', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-145', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-28 08:25:00', '2026-06-28 08:25:00'),
(53, 1, 'Tarpaulin Banner', 921, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-07', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-146', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-02 01:48:00', '2026-06-02 01:48:00'),
(54, 1, 'Document Printing', 866, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-10', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-147', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-05 02:24:00', '2026-06-05 02:24:00'),
(55, 1, 'Flyers & Brochures', 262, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-13', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-148', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-08 00:42:00', '2026-06-08 00:42:00'),
(56, 1, 'Calling Cards', 195, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-16', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-149', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-11 08:46:00', '2026-06-11 08:46:00'),
(57, 1, 'Stickers & Labels', 269, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-19', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-150', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-14 05:41:00', '2026-06-14 05:41:00'),
(58, 1, 'Tarpaulin Banner', 847, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-22', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-151', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-17 07:33:00', '2026-06-17 07:33:00'),
(59, 1, 'Document Printing', 527, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-25', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-152', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-20 07:10:00', '2026-06-20 07:10:00'),
(60, 1, 'Flyers & Brochures', 848, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-28', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-153', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-23 03:41:00', '2026-06-23 03:41:00'),
(61, 1, 'Stickers & Labels', 642, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-07', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-154', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-02 06:21:00', '2026-06-02 06:21:00'),
(62, 1, 'Tarpaulin Banner', 473, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-10', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-155', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-05 01:37:00', '2026-06-05 01:37:00'),
(63, 1, 'Document Printing', 189, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-13', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-156', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-08 01:43:00', '2026-06-08 01:43:00'),
(64, 1, 'Flyers & Brochures', 476, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-16', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-157', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-11 01:36:00', '2026-06-11 01:36:00'),
(65, 1, 'Calling Cards', 336, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-06-19', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-158', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-06-14 09:14:00', '2026-06-14 09:14:00'),
(66, 1, 'Stickers & Labels', 684, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-07', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-159', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-02 02:41:00', '2026-07-02 02:41:00'),
(67, 1, 'Tarpaulin Banner', 476, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-10', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-160', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-05 08:16:00', '2026-07-05 08:16:00'),
(68, 1, 'Document Printing', 607, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-13', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-161', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-08 02:28:00', '2026-07-08 02:28:00'),
(69, 1, 'Flyers & Brochures', 346, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-16', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-162', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-11 03:18:00', '2026-07-11 03:18:00'),
(70, 1, 'Calling Cards', 765, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-19', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-163', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-14 01:12:00', '2026-07-14 01:12:00'),
(71, 1, 'Stickers & Labels', 432, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-22', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-164', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-17 05:17:00', '2026-07-17 05:17:00'),
(72, 1, 'Tarpaulin Banner', 361, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-25', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-165', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-20 09:31:00', '2026-07-20 09:31:00'),
(73, 1, 'Document Printing', 971, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-28', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-166', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-23 01:11:00', '2026-07-23 01:11:00'),
(74, 1, 'Flyers & Brochures', 288, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-31', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-167', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-26 06:42:00', '2026-07-26 06:42:00'),
(75, 1, 'Calling Cards', 724, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-168', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-28 08:45:00', '2026-07-28 08:45:00'),
(76, 1, 'Stickers & Labels', 247, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-07', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-169', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-02 05:31:00', '2026-07-02 05:31:00'),
(77, 1, 'Tarpaulin Banner', 922, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-10', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-170', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-05 06:43:00', '2026-07-05 06:43:00'),
(78, 1, 'Document Printing', 728, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-13', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-171', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-08 00:32:00', '2026-07-08 00:32:00'),
(79, 1, 'Flyers & Brochures', 442, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-16', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-172', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-11 09:50:00', '2026-07-11 09:50:00'),
(80, 1, 'Calling Cards', 906, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-19', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-173', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-14 05:24:00', '2026-07-14 05:24:00'),
(81, 1, 'Stickers & Labels', 930, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-22', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-174', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-17 09:36:00', '2026-07-17 09:36:00'),
(82, 1, 'Tarpaulin Banner', 690, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-25', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-175', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-20 02:40:00', '2026-07-20 02:40:00'),
(83, 1, 'Document Printing', 942, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-28', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-176', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-23 02:29:00', '2026-07-23 02:29:00'),
(84, 1, 'Calling Cards', 599, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-07', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-177', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-02 06:20:00', '2026-07-02 06:20:00'),
(85, 1, 'Stickers & Labels', 987, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-10', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-178', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-05 01:34:00', '2026-07-05 01:34:00'),
(86, 1, 'Tarpaulin Banner', 253, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-13', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-179', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-08 05:33:00', '2026-07-08 05:33:00'),
(87, 1, 'Document Printing', 541, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-16', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-180', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-11 05:46:00', '2026-07-11 05:46:00'),
(88, 1, 'Flyers & Brochures', 906, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-07-19', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-181', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-07-14 05:36:00', '2026-07-14 05:36:00'),
(89, 1, 'Calling Cards', 599, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-07', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-182', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-02 01:45:00', '2026-08-02 01:45:00'),
(90, 1, 'Stickers & Labels', 223, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-10', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-183', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-05 09:37:00', '2026-08-05 09:37:00'),
(91, 1, 'Tarpaulin Banner', 687, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-13', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-184', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-08 08:44:00', '2026-08-08 08:44:00'),
(92, 1, 'Document Printing', 334, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-16', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-185', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-11 00:43:00', '2026-08-11 00:43:00'),
(93, 1, 'Flyers & Brochures', 972, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-19', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-186', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-14 04:17:00', '2026-08-14 04:17:00'),
(94, 1, 'Calling Cards', 483, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-22', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-187', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-17 09:37:00', '2026-08-17 09:37:00'),
(95, 1, 'Stickers & Labels', 547, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-25', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-188', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-20 06:14:00', '2026-08-20 06:14:00'),
(96, 1, 'Tarpaulin Banner', 483, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-28', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-189', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-23 03:17:00', '2026-08-23 03:17:00'),
(97, 1, 'Document Printing', 427, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-31', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-190', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-26 05:46:00', '2026-08-26 05:46:00'),
(98, 1, 'Flyers & Brochures', 493, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-191', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-28 01:37:00', '2026-08-28 01:37:00'),
(99, 1, 'Calling Cards', 137, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-192', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-28 07:38:00', '2026-08-28 07:38:00'),
(100, 1, 'Stickers & Labels', 872, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-193', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-28 08:44:00', '2026-08-28 08:44:00'),
(101, 1, 'Tarpaulin Banner', 414, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-194', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-28 03:48:00', '2026-08-28 03:48:00'),
(102, 1, 'Document Printing', 134, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-195', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-28 05:36:00', '2026-08-28 05:36:00'),
(103, 1, 'Flyers & Brochures', 152, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-02', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-196', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-28 07:16:00', '2026-08-28 07:16:00'),
(104, 1, 'Calling Cards', 890, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-07', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-197', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-02 04:31:00', '2026-08-02 04:31:00'),
(105, 1, 'Stickers & Labels', 299, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-10', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-198', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-05 07:12:00', '2026-08-05 07:12:00'),
(106, 1, 'Tarpaulin Banner', 267, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-13', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-199', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-08 05:37:00', '2026-08-08 05:37:00'),
(107, 1, 'Document Printing', 301, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-16', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-200', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-11 09:47:00', '2026-08-11 09:47:00'),
(108, 1, 'Flyers & Brochures', 618, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-19', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-201', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-14 01:44:00', '2026-08-14 01:44:00'),
(109, 1, 'Calling Cards', 806, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-22', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-202', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-17 07:50:00', '2026-08-17 07:50:00'),
(110, 1, 'Stickers & Labels', 770, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-25', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-203', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-20 03:49:00', '2026-08-20 03:49:00'),
(111, 1, 'Tarpaulin Banner', 555, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-28', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-204', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-23 06:49:00', '2026-08-23 06:49:00'),
(112, 1, 'Document Printing', 756, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-31', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-205', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-26 08:19:00', '2026-08-26 08:19:00'),
(113, 1, 'Tarpaulin Banner', 791, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-07', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-206', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-02 08:18:00', '2026-08-02 08:18:00'),
(114, 1, 'Document Printing', 454, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-10', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-207', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-05 03:39:00', '2026-08-05 03:39:00'),
(115, 1, 'Flyers & Brochures', 848, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-13', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-208', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-08 07:23:00', '2026-08-08 07:23:00'),
(116, 1, 'Calling Cards', 770, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-16', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-209', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-11 06:23:00', '2026-08-11 06:23:00'),
(117, 1, 'Stickers & Labels', 277, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-19', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-210', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-14 03:10:00', '2026-08-14 03:10:00'),
(118, 1, 'Tarpaulin Banner', 273, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-22', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-211', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-17 00:19:00', '2026-08-17 00:19:00'),
(119, 1, 'Document Printing', 145, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-08-25', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-212', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-08-20 02:35:00', '2026-08-20 02:35:00'),
(120, 1, 'Document Printing', 819, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-07', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-213', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-02 05:35:00', '2026-09-02 05:35:00'),
(121, 1, 'Flyers & Brochures', 147, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-10', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-214', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-05 01:20:00', '2026-09-05 01:20:00'),
(122, 1, 'Calling Cards', 589, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-13', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-215', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-08 07:23:00', '2026-09-08 07:23:00'),
(123, 1, 'Stickers & Labels', 847, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-16', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-216', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-11 01:35:00', '2026-09-11 01:35:00'),
(124, 1, 'Tarpaulin Banner', 589, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-19', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-217', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-14 08:49:00', '2026-09-14 08:49:00'),
(125, 1, 'Document Printing', 533, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-22', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-218', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-17 09:31:00', '2026-09-17 09:31:00'),
(126, 1, 'Flyers & Brochures', 323, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-25', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-219', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-20 09:17:00', '2026-09-20 09:17:00'),
(127, 1, 'Calling Cards', 620, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-28', 'Morning Star Printing Press', 'Historical System Spec Ref #ORD-HIST-2026-220', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-23 00:21:00', '2026-09-23 00:21:00'),
(128, 1, 'Tarpaulin Banner', 473, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-07', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-221', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-02 06:23:00', '2026-09-02 06:23:00'),
(129, 1, 'Document Printing', 661, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-10', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-222', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-05 02:26:00', '2026-09-05 02:26:00'),
(130, 1, 'Flyers & Brochures', 932, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-13', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-223', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-08 06:49:00', '2026-09-08 06:49:00'),
(131, 1, 'Calling Cards', 201, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-16', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-224', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-11 00:23:00', '2026-09-11 00:23:00'),
(132, 1, 'Stickers & Labels', 994, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-19', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-225', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-14 05:33:00', '2026-09-14 05:33:00'),
(133, 1, 'Tarpaulin Banner', 242, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-22', 'Morning Star Printing Network', 'Historical System Spec Ref #ORD-HIST-2026-226', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-17 01:17:00', '2026-09-17 01:17:00'),
(134, 1, 'Calling Cards', 286, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-07', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-227', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-02 05:47:00', '2026-09-02 05:47:00'),
(135, 1, 'Stickers & Labels', 137, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-10', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-228', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-09-05 01:35:00', '2026-09-05 01:35:00'),
(136, 1, 'Tarpaulin Banner', 682, 'A4', 'Glossy Paper 150gsm (A4/A3)', 'Lamination', '2026-09-13', 'Green Heart Printing Hub', 'Historical System Spec Ref #ORD-HIST-2026-229', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-09-08 00:48:00', '2026-09-08 00:48:00'),
(137, 13, 'Flyers & Brochures', 1000, 'A4 Tri-fold', 'Glossy Paper 150gsm', 'Tri-fold & Gloss Lamination', '2026-10-19', 'Morning Star Printing Press', 'Please ensure fold lines are sharp and colors match CMYK sample.', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'submitted', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(138, 14, 'Calling Cards', 250, '5x7 inches', 'Matte Cardstock 220gsm', 'Gold Foil Stamping', '2026-10-20', 'Morning Star Printing Press', 'Special wedding invitation cards. Foil stamping on bride & groom names.', NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'quotation', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(139, 15, 'Stickers & Labels', 500, '2.5x2.5 in Die-cut', 'Gloss Vinyl Waterproof Sticker Roll', 'Kiss Cut & Waterproof Coating', '2026-10-18', 'Morning Star Printing Press', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'production', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(140, 16, 'Tarpaulin Banner', 4, '4x8 ft', 'Flex Tarpaulin Banner Media 13oz', 'Eyelets & Reinforced Hemming', '2026-10-16', 'Morning Star Printing Press', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'ready_for_pickup', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(141, 17, 'Document Printing', 150, 'A4 Booklet', 'Glossy Paper 150gsm', 'Saddle Stitch Binding', '2026-10-13', 'Morning Star Printing Network', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'completed', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(142, 18, 'Document Printing', 50, 'A1 Large Format', 'Standard Material', 'Blueprint Roll Binding', '2026-10-21', 'Green Heart Printing Hub', NULL, NULL, NULL, 'pending_proof', NULL, NULL, NULL, 'pickup', 'payment', '2026-10-15 06:37:17', '2026-10-15 06:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `production_jobs`
--

CREATE TABLE `production_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_number` varchar(255) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `machine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'assigned',
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `estimated_hours` int(11) DEFAULT NULL,
  `delay_reason` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_jobs`
--

INSERT INTO `production_jobs` (`id`, `job_number`, `order_id`, `branch_id`, `machine_id`, `assigned_to`, `status`, `priority`, `estimated_hours`, `delay_reason`, `remarks`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'JOB-SEED001', 1, 1, 1, 4, 'completed', 'rush', 6, NULL, NULL, '2026-08-20 03:29:57', '2026-09-10 05:09:13', '2026-08-20 04:29:57', '2026-09-10 05:09:13'),
(2, 'JOB-2026-213', 114, 1, 1, 4, 'completed', 'rush', 6, NULL, NULL, '2026-09-02 08:35:00', NULL, '2026-09-02 05:35:00', '2026-09-12 22:28:13'),
(3, 'JOB-2026-214', 115, 1, 1, 4, 'quality_checking', 'standard', 5, NULL, NULL, '2026-09-05 04:20:00', NULL, '2026-09-05 01:20:00', '2026-09-12 22:28:13'),
(4, 'JOB-2026-215', 116, 1, 1, 4, 'delayed', 'standard', 10, 'Substrate feeder roller calibration & media realignment', NULL, '2026-09-08 10:23:00', NULL, '2026-09-08 07:23:00', '2026-09-12 22:28:13'),
(5, 'JOB-2026-216', 117, 1, 1, 4, 'preparing', 'rush', 5, NULL, NULL, '2026-09-11 04:35:00', NULL, '2026-09-11 01:35:00', '2026-09-12 22:28:13'),
(6, 'JOB-2026-217', 118, 1, 1, 4, 'completed', 'standard', 9, NULL, NULL, '2026-09-14 11:49:00', NULL, '2026-09-14 08:49:00', '2026-09-12 22:28:13'),
(7, 'JOB-2026-218', 119, 1, 1, 4, 'quality_checking', 'standard', 10, NULL, NULL, '2026-09-17 12:31:00', NULL, '2026-09-17 09:31:00', '2026-09-12 22:28:13'),
(8, 'JOB-2026-219', 120, 1, 1, 4, 'in_production', 'rush', 8, NULL, NULL, '2026-09-20 12:17:00', NULL, '2026-09-20 09:17:00', '2026-09-12 22:28:13'),
(9, 'JOB-2026-220', 121, 1, 1, 4, 'preparing', 'standard', 6, NULL, NULL, '2026-09-23 03:21:00', NULL, '2026-09-23 00:21:00', '2026-09-12 22:28:13'),
(10, 'JOB-2026-221', 122, 2, 3, 4, 'completed', 'rush', 7, NULL, NULL, '2026-09-02 09:23:00', NULL, '2026-09-02 06:23:00', '2026-09-12 22:28:13'),
(11, 'JOB-2026-222', 123, 2, 3, 4, 'quality_checking', 'standard', 9, NULL, NULL, '2026-09-05 05:26:00', NULL, '2026-09-05 02:26:00', '2026-09-12 22:28:13'),
(12, 'JOB-2026-223', 124, 2, 3, 4, 'in_production', 'standard', 5, NULL, NULL, '2026-09-08 09:49:00', NULL, '2026-09-08 06:49:00', '2026-09-12 22:28:13'),
(13, 'JOB-2026-224', 125, 2, 3, 4, 'preparing', 'rush', 4, NULL, NULL, '2026-09-11 03:23:00', NULL, '2026-09-11 00:23:00', '2026-09-12 22:28:13'),
(14, 'JOB-2026-225', 126, 2, 3, 4, 'completed', 'standard', 5, NULL, NULL, '2026-09-14 08:33:00', NULL, '2026-09-14 05:33:00', '2026-09-12 22:28:13'),
(15, 'JOB-2026-226', 127, 2, 3, 4, 'quality_checking', 'standard', 10, NULL, NULL, '2026-09-17 04:17:00', NULL, '2026-09-17 01:17:00', '2026-09-12 22:28:13'),
(16, 'JOB-2026-227', 128, 3, 4, 4, 'completed', 'rush', 8, NULL, NULL, '2026-09-02 08:47:00', NULL, '2026-09-02 05:47:00', '2026-09-12 22:28:13'),
(17, 'JOB-2026-228', 129, 3, 4, 4, 'quality_checking', 'standard', 4, NULL, NULL, '2026-09-05 04:35:00', NULL, '2026-09-05 01:35:00', '2026-09-12 22:28:13'),
(18, 'JOB-2026-229', 130, 3, 4, 4, 'in_production', 'standard', 6, NULL, NULL, '2026-09-08 03:48:00', NULL, '2026-09-08 00:48:00', '2026-09-12 22:28:13'),
(19, 'JOB-2026-001', 131, 1, 1, 4, 'in_production', 'normal', 3, NULL, NULL, '2026-10-15 04:37:17', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(20, 'JOB-2026-002', 132, 1, 1, 4, 'completed', 'normal', 2, NULL, NULL, '2026-10-15 00:37:17', '2026-10-15 04:37:17', '2026-10-15 06:37:17', '2026-10-15 06:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','approved','rejected','received') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `branch_id`, `requested_by`, `material_id`, `quantity`, `unit_cost`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 5, 6, 15, 2100.00, 31500.00, 'pending', 'Urgent replenishment for depleted Branch C toner stocks', '2026-09-12 14:28:11', '2026-09-12 22:28:11'),
(2, 1, 5, 8, 20, 1450.00, 29000.00, 'pending', 'Restocking thermal lamination rolls for Branch A high-volume production', '2026-09-11 22:28:11', '2026-09-12 22:28:11'),
(3, 2, 5, 1, 40, 620.00, 24800.00, 'approved', 'Glossy substrate scheduled quarterly restock for Branch B', '2026-09-08 22:28:11', '2026-09-12 22:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_number` varchar(255) NOT NULL,
  `print_request_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `base_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `material_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `finishing_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valid_until` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `quotation_number`, `print_request_id`, `user_id`, `base_cost`, `material_cost`, `finishing_cost`, `total_price`, `valid_until`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'QT-SEED001', 1, 1, 5000.00, 2500.00, 1000.00, 8500.00, '2026-09-03', 'confirmed', NULL, '2026-08-20 04:29:57', '2026-08-20 04:29:57'),
(2, 'QT-HIST-2026-101', 8, 1, 1881.00, 783.75, 470.25, 3135.00, '2026-04-16', 'confirmed', NULL, '2026-04-02 03:38:00', '2026-04-02 03:38:00'),
(3, 'QT-HIST-2026-102', 9, 1, 1633.50, 680.63, 408.38, 2722.50, '2026-04-19', 'confirmed', NULL, '2026-04-05 02:38:00', '2026-04-05 02:38:00'),
(4, 'QT-HIST-2026-103', 10, 1, 1501.50, 625.63, 375.38, 2502.50, '2026-04-22', 'confirmed', NULL, '2026-04-08 00:25:00', '2026-04-08 00:25:00'),
(5, 'QT-HIST-2026-104', 11, 1, 1650.00, 687.50, 412.50, 2750.00, '2026-04-25', 'confirmed', NULL, '2026-04-11 06:35:00', '2026-04-11 06:35:00'),
(6, 'QT-HIST-2026-105', 12, 1, 1897.50, 790.63, 474.38, 3162.50, '2026-04-28', 'confirmed', NULL, '2026-04-14 07:12:00', '2026-04-14 07:12:00'),
(7, 'QT-HIST-2026-106', 13, 1, 1666.50, 694.38, 416.63, 2777.50, '2026-05-01', 'confirmed', NULL, '2026-04-17 08:36:00', '2026-04-17 08:36:00'),
(8, 'QT-HIST-2026-107', 14, 1, 1468.50, 611.88, 367.13, 2447.50, '2026-05-04', 'confirmed', NULL, '2026-04-20 05:12:00', '2026-04-20 05:12:00'),
(9, 'QT-HIST-2026-108', 15, 1, 1732.50, 721.88, 433.13, 2887.50, '2026-04-16', 'confirmed', NULL, '2026-04-02 02:50:00', '2026-04-02 02:50:00'),
(10, 'QT-HIST-2026-109', 16, 1, 1534.50, 639.38, 383.63, 2557.50, '2026-04-19', 'confirmed', NULL, '2026-04-05 01:13:00', '2026-04-05 01:13:00'),
(11, 'QT-HIST-2026-110', 17, 1, 1600.50, 666.88, 400.13, 2667.50, '2026-04-22', 'confirmed', NULL, '2026-04-08 09:28:00', '2026-04-08 09:28:00'),
(12, 'QT-HIST-2026-111', 18, 1, 1501.50, 625.63, 375.38, 2502.50, '2026-04-25', 'confirmed', NULL, '2026-04-11 05:17:00', '2026-04-11 05:17:00'),
(13, 'QT-HIST-2026-112', 19, 1, 1468.50, 611.88, 367.13, 2447.50, '2026-04-16', 'confirmed', NULL, '2026-04-02 05:42:00', '2026-04-02 05:42:00'),
(14, 'QT-HIST-2026-113', 20, 1, 1584.00, 660.00, 396.00, 2640.00, '2026-04-19', 'confirmed', NULL, '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(15, 'QT-HIST-2026-114', 21, 1, 1666.50, 694.38, 416.63, 2777.50, '2026-04-22', 'confirmed', NULL, '2026-04-08 04:10:00', '2026-04-08 04:10:00'),
(16, 'QT-HIST-2026-115', 22, 1, 1445.05, 602.11, 361.26, 2408.42, '2026-05-16', 'confirmed', NULL, '2026-05-02 01:22:00', '2026-05-02 01:22:00'),
(17, 'QT-HIST-2026-116', 23, 1, 1658.53, 691.05, 414.63, 2764.21, '2026-05-19', 'confirmed', NULL, '2026-05-05 02:46:00', '2026-05-05 02:46:00'),
(18, 'QT-HIST-2026-117', 24, 1, 1658.53, 691.05, 414.63, 2764.21, '2026-05-22', 'confirmed', NULL, '2026-05-08 06:27:00', '2026-05-08 06:27:00'),
(19, 'QT-HIST-2026-118', 25, 1, 1740.63, 725.26, 435.16, 2901.05, '2026-05-25', 'confirmed', NULL, '2026-05-11 04:12:00', '2026-05-11 04:12:00'),
(20, 'QT-HIST-2026-119', 26, 1, 1445.05, 602.11, 361.26, 2408.42, '2026-05-28', 'confirmed', NULL, '2026-05-14 02:26:00', '2026-05-14 02:26:00'),
(21, 'QT-HIST-2026-120', 27, 1, 1625.68, 677.37, 406.42, 2709.47, '2026-05-31', 'confirmed', NULL, '2026-05-17 09:31:00', '2026-05-17 09:31:00'),
(22, 'QT-HIST-2026-121', 28, 1, 1461.47, 608.95, 365.37, 2435.79, '2026-06-03', 'confirmed', NULL, '2026-05-20 00:42:00', '2026-05-20 00:42:00'),
(23, 'QT-HIST-2026-122', 29, 1, 1822.73, 759.47, 455.68, 3037.89, '2026-06-06', 'confirmed', NULL, '2026-05-23 08:40:00', '2026-05-23 08:40:00'),
(24, 'QT-HIST-2026-123', 30, 1, 1461.47, 608.95, 365.37, 2435.79, '2026-06-09', 'confirmed', NULL, '2026-05-26 07:18:00', '2026-05-26 07:18:00'),
(25, 'QT-HIST-2026-124', 31, 1, 1773.47, 738.95, 443.37, 2955.79, '2026-05-16', 'confirmed', NULL, '2026-05-02 03:15:00', '2026-05-02 03:15:00'),
(26, 'QT-HIST-2026-125', 32, 1, 1445.05, 602.11, 361.26, 2408.42, '2026-05-19', 'confirmed', NULL, '2026-05-05 02:13:00', '2026-05-05 02:13:00'),
(27, 'QT-HIST-2026-126', 33, 1, 1822.73, 759.47, 455.68, 3037.89, '2026-05-22', 'confirmed', NULL, '2026-05-08 00:38:00', '2026-05-08 00:38:00'),
(28, 'QT-HIST-2026-127', 34, 1, 1560.00, 650.00, 390.00, 2600.00, '2026-05-25', 'confirmed', NULL, '2026-05-11 05:15:00', '2026-05-11 05:15:00'),
(29, 'QT-HIST-2026-128', 35, 1, 1560.00, 650.00, 390.00, 2600.00, '2026-05-28', 'confirmed', NULL, '2026-05-14 09:14:00', '2026-05-14 09:14:00'),
(30, 'QT-HIST-2026-129', 36, 1, 1740.63, 725.26, 435.16, 2901.05, '2026-05-31', 'confirmed', NULL, '2026-05-17 03:30:00', '2026-05-17 03:30:00'),
(31, 'QT-HIST-2026-130', 37, 1, 1674.95, 697.90, 418.74, 2791.58, '2026-05-16', 'confirmed', NULL, '2026-05-02 01:23:00', '2026-05-02 01:23:00'),
(32, 'QT-HIST-2026-131', 38, 1, 1674.95, 697.90, 418.74, 2791.58, '2026-05-19', 'confirmed', NULL, '2026-05-05 06:11:00', '2026-05-05 06:11:00'),
(33, 'QT-HIST-2026-132', 39, 1, 1658.53, 691.05, 414.63, 2764.21, '2026-05-22', 'confirmed', NULL, '2026-05-08 03:47:00', '2026-05-08 03:47:00'),
(34, 'QT-HIST-2026-133', 40, 1, 1560.00, 650.00, 390.00, 2600.00, '2026-05-25', 'confirmed', NULL, '2026-05-11 03:22:00', '2026-05-11 03:22:00'),
(35, 'QT-HIST-2026-134', 41, 1, 1546.89, 644.54, 386.72, 2578.15, '2026-06-16', 'confirmed', NULL, '2026-06-02 09:10:00', '2026-06-02 09:10:00'),
(36, 'QT-HIST-2026-135', 42, 1, 1673.17, 697.16, 418.29, 2788.62, '2026-06-19', 'confirmed', NULL, '2026-06-05 08:49:00', '2026-06-05 08:49:00'),
(37, 'QT-HIST-2026-136', 43, 1, 1467.97, 611.66, 366.99, 2446.62, '2026-06-22', 'confirmed', NULL, '2026-06-08 05:33:00', '2026-06-08 05:33:00'),
(38, 'QT-HIST-2026-137', 44, 1, 1657.39, 690.58, 414.35, 2762.31, '2026-06-25', 'confirmed', NULL, '2026-06-11 04:16:00', '2026-06-11 04:16:00'),
(39, 'QT-HIST-2026-138', 45, 1, 1752.09, 730.04, 438.02, 2920.15, '2026-06-28', 'confirmed', NULL, '2026-06-14 09:19:00', '2026-06-14 09:19:00'),
(40, 'QT-HIST-2026-139', 46, 1, 1515.32, 631.39, 378.83, 2525.54, '2026-07-01', 'confirmed', NULL, '2026-06-17 00:40:00', '2026-06-17 00:40:00'),
(41, 'QT-HIST-2026-140', 47, 1, 1420.61, 591.92, 355.15, 2367.69, '2026-07-04', 'confirmed', NULL, '2026-06-20 09:46:00', '2026-06-20 09:46:00'),
(42, 'QT-HIST-2026-141', 48, 1, 1610.03, 670.85, 402.51, 2683.38, '2026-07-07', 'confirmed', NULL, '2026-06-23 01:30:00', '2026-06-23 01:30:00'),
(43, 'QT-HIST-2026-142', 49, 1, 1704.74, 710.31, 426.18, 2841.23, '2026-07-10', 'confirmed', NULL, '2026-06-26 08:39:00', '2026-06-26 08:39:00'),
(44, 'QT-HIST-2026-143', 50, 1, 1436.40, 598.50, 359.10, 2394.00, '2026-07-12', 'confirmed', NULL, '2026-06-28 08:43:00', '2026-06-28 08:43:00'),
(45, 'QT-HIST-2026-144', 51, 1, 1341.69, 559.04, 335.42, 2236.15, '2026-07-12', 'confirmed', NULL, '2026-06-28 00:21:00', '2026-06-28 00:21:00'),
(46, 'QT-HIST-2026-145', 52, 1, 1483.75, 618.23, 370.94, 2472.92, '2026-07-12', 'confirmed', NULL, '2026-06-28 08:25:00', '2026-06-28 08:25:00'),
(47, 'QT-HIST-2026-146', 53, 1, 1767.88, 736.62, 441.97, 2946.46, '2026-06-16', 'confirmed', NULL, '2026-06-02 01:48:00', '2026-06-02 01:48:00'),
(48, 'QT-HIST-2026-147', 54, 1, 1641.60, 684.00, 410.40, 2736.00, '2026-06-19', 'confirmed', NULL, '2026-06-05 02:24:00', '2026-06-05 02:24:00'),
(49, 'QT-HIST-2026-148', 55, 1, 1341.69, 559.04, 335.42, 2236.15, '2026-06-22', 'confirmed', NULL, '2026-06-08 00:42:00', '2026-06-08 00:42:00'),
(50, 'QT-HIST-2026-149', 56, 1, 1515.32, 631.39, 378.83, 2525.54, '2026-06-25', 'confirmed', NULL, '2026-06-11 08:46:00', '2026-06-11 08:46:00'),
(51, 'QT-HIST-2026-150', 57, 1, 1594.25, 664.27, 398.56, 2657.08, '2026-06-28', 'confirmed', NULL, '2026-06-14 05:41:00', '2026-06-14 05:41:00'),
(52, 'QT-HIST-2026-151', 58, 1, 1483.75, 618.23, 370.94, 2472.92, '2026-07-01', 'confirmed', NULL, '2026-06-17 07:33:00', '2026-06-17 07:33:00'),
(53, 'QT-HIST-2026-152', 59, 1, 1546.89, 644.54, 386.72, 2578.15, '2026-07-04', 'confirmed', NULL, '2026-06-20 07:10:00', '2026-06-20 07:10:00'),
(54, 'QT-HIST-2026-153', 60, 1, 1578.46, 657.69, 394.62, 2630.77, '2026-07-07', 'confirmed', NULL, '2026-06-23 03:41:00', '2026-06-23 03:41:00'),
(55, 'QT-HIST-2026-154', 61, 1, 1562.68, 651.12, 390.67, 2604.46, '2026-06-16', 'confirmed', NULL, '2026-06-02 06:21:00', '2026-06-02 06:21:00'),
(56, 'QT-HIST-2026-155', 62, 1, 1736.31, 723.46, 434.08, 2893.85, '2026-06-19', 'confirmed', NULL, '2026-06-05 01:37:00', '2026-06-05 01:37:00'),
(57, 'QT-HIST-2026-156', 63, 1, 1704.74, 710.31, 426.18, 2841.23, '2026-06-22', 'confirmed', NULL, '2026-06-08 01:43:00', '2026-06-08 01:43:00'),
(58, 'QT-HIST-2026-157', 64, 1, 1736.31, 723.46, 434.08, 2893.85, '2026-06-25', 'confirmed', NULL, '2026-06-11 01:36:00', '2026-06-11 01:36:00'),
(59, 'QT-HIST-2026-158', 65, 1, 1389.05, 578.77, 347.26, 2315.08, '2026-06-28', 'confirmed', NULL, '2026-06-14 09:14:00', '2026-06-14 09:14:00'),
(60, 'QT-HIST-2026-159', 66, 1, 1628.45, 678.52, 407.11, 2714.09, '2026-07-16', 'confirmed', NULL, '2026-07-02 02:41:00', '2026-07-02 02:41:00'),
(61, 'QT-HIST-2026-160', 67, 1, 1740.21, 725.09, 435.05, 2900.35, '2026-07-19', 'confirmed', NULL, '2026-07-05 08:16:00', '2026-07-05 08:16:00'),
(62, 'QT-HIST-2026-161', 68, 1, 1644.42, 685.18, 411.11, 2740.70, '2026-07-22', 'confirmed', NULL, '2026-07-08 02:28:00', '2026-07-08 02:28:00'),
(63, 'QT-HIST-2026-162', 69, 1, 1404.94, 585.39, 351.24, 2341.57, '2026-07-25', 'confirmed', NULL, '2026-07-11 03:18:00', '2026-07-11 03:18:00'),
(64, 'QT-HIST-2026-163', 70, 1, 1436.87, 598.70, 359.22, 2394.78, '2026-07-28', 'confirmed', NULL, '2026-07-14 01:12:00', '2026-07-14 01:12:00'),
(65, 'QT-HIST-2026-164', 71, 1, 1500.73, 625.31, 375.18, 2501.22, '2026-07-31', 'confirmed', NULL, '2026-07-17 05:17:00', '2026-07-17 05:17:00'),
(66, 'QT-HIST-2026-165', 72, 1, 1373.01, 572.09, 343.25, 2288.35, '2026-08-03', 'confirmed', NULL, '2026-07-20 09:31:00', '2026-07-20 09:31:00'),
(67, 'QT-HIST-2026-166', 73, 1, 1452.83, 605.35, 363.21, 2421.39, '2026-08-06', 'confirmed', NULL, '2026-07-23 01:11:00', '2026-07-23 01:11:00'),
(68, 'QT-HIST-2026-167', 74, 1, 1468.80, 612.00, 367.20, 2448.00, '2026-08-09', 'confirmed', NULL, '2026-07-26 06:42:00', '2026-07-26 06:42:00'),
(69, 'QT-HIST-2026-168', 75, 1, 1516.70, 631.96, 379.17, 2527.83, '2026-08-11', 'confirmed', NULL, '2026-07-28 08:45:00', '2026-07-28 08:45:00'),
(70, 'QT-HIST-2026-169', 76, 1, 1532.66, 638.61, 383.16, 2554.43, '2026-07-16', 'confirmed', NULL, '2026-07-02 05:31:00', '2026-07-02 05:31:00'),
(71, 'QT-HIST-2026-170', 77, 1, 1564.59, 651.91, 391.15, 2607.65, '2026-07-19', 'confirmed', NULL, '2026-07-05 06:43:00', '2026-07-05 06:43:00'),
(72, 'QT-HIST-2026-171', 78, 1, 1548.62, 645.26, 387.16, 2581.04, '2026-07-22', 'confirmed', NULL, '2026-07-08 00:32:00', '2026-07-08 00:32:00'),
(73, 'QT-HIST-2026-172', 79, 1, 1740.21, 725.09, 435.05, 2900.35, '2026-07-25', 'confirmed', NULL, '2026-07-11 09:50:00', '2026-07-11 09:50:00'),
(74, 'QT-HIST-2026-173', 80, 1, 1708.28, 711.78, 427.07, 2847.13, '2026-07-28', 'confirmed', NULL, '2026-07-14 05:24:00', '2026-07-14 05:24:00'),
(75, 'QT-HIST-2026-174', 81, 1, 1644.42, 685.18, 411.11, 2740.70, '2026-07-31', 'confirmed', NULL, '2026-07-17 09:36:00', '2026-07-17 09:36:00'),
(76, 'QT-HIST-2026-175', 82, 1, 1468.80, 612.00, 367.20, 2448.00, '2026-08-03', 'confirmed', NULL, '2026-07-20 02:40:00', '2026-07-20 02:40:00'),
(77, 'QT-HIST-2026-176', 83, 1, 1628.45, 678.52, 407.11, 2714.09, '2026-08-06', 'confirmed', NULL, '2026-07-23 02:29:00', '2026-07-23 02:29:00'),
(78, 'QT-HIST-2026-177', 84, 1, 1436.87, 598.70, 359.22, 2394.78, '2026-07-16', 'confirmed', NULL, '2026-07-02 06:20:00', '2026-07-02 06:20:00'),
(79, 'QT-HIST-2026-178', 85, 1, 1532.66, 638.61, 383.16, 2554.43, '2026-07-19', 'confirmed', NULL, '2026-07-05 01:34:00', '2026-07-05 01:34:00'),
(80, 'QT-HIST-2026-179', 86, 1, 1468.80, 612.00, 367.20, 2448.00, '2026-07-22', 'confirmed', NULL, '2026-07-08 05:33:00', '2026-07-08 05:33:00'),
(81, 'QT-HIST-2026-180', 87, 1, 1532.66, 638.61, 383.16, 2554.43, '2026-07-25', 'confirmed', NULL, '2026-07-11 05:46:00', '2026-07-11 05:46:00'),
(82, 'QT-HIST-2026-181', 88, 1, 1373.01, 572.09, 343.25, 2288.35, '2026-07-28', 'confirmed', NULL, '2026-07-14 05:36:00', '2026-07-14 05:36:00'),
(83, 'QT-HIST-2026-182', 89, 1, 1684.55, 701.90, 421.14, 2807.58, '2026-08-16', 'confirmed', NULL, '2026-08-02 01:45:00', '2026-08-02 01:45:00'),
(84, 'QT-HIST-2026-183', 90, 1, 1799.03, 749.60, 449.76, 2998.39, '2026-08-19', 'confirmed', NULL, '2026-08-05 09:37:00', '2026-08-05 09:37:00'),
(85, 'QT-HIST-2026-184', 91, 1, 1864.45, 776.86, 466.11, 3107.42, '2026-08-22', 'confirmed', NULL, '2026-08-08 08:44:00', '2026-08-08 08:44:00'),
(86, 'QT-HIST-2026-185', 92, 1, 1390.16, 579.24, 347.54, 2316.94, '2026-08-25', 'confirmed', NULL, '2026-08-11 00:43:00', '2026-08-11 00:43:00'),
(87, 'QT-HIST-2026-186', 93, 1, 1766.32, 735.97, 441.58, 2943.87, '2026-08-28', 'confirmed', NULL, '2026-08-14 04:17:00', '2026-08-14 04:17:00'),
(88, 'QT-HIST-2026-187', 94, 1, 1504.64, 626.94, 376.16, 2507.74, '2026-08-31', 'confirmed', NULL, '2026-08-17 09:37:00', '2026-08-17 09:37:00'),
(89, 'QT-HIST-2026-188', 95, 1, 1700.90, 708.71, 425.23, 2834.84, '2026-09-03', 'confirmed', NULL, '2026-08-20 06:14:00', '2026-08-20 06:14:00'),
(90, 'QT-HIST-2026-189', 96, 1, 1815.39, 756.41, 453.85, 3025.65, '2026-09-06', 'confirmed', NULL, '2026-08-23 03:17:00', '2026-08-23 03:17:00'),
(91, 'QT-HIST-2026-190', 97, 1, 1553.71, 647.38, 388.43, 2589.52, '2026-09-09', 'confirmed', NULL, '2026-08-26 05:46:00', '2026-08-26 05:46:00'),
(92, 'QT-HIST-2026-191', 98, 1, 1848.10, 770.04, 462.02, 3080.16, '2026-09-11', 'confirmed', NULL, '2026-08-28 01:37:00', '2026-08-28 01:37:00'),
(93, 'QT-HIST-2026-192', 99, 1, 1782.68, 742.78, 445.67, 2971.13, '2026-09-11', 'confirmed', NULL, '2026-08-28 07:38:00', '2026-08-28 07:38:00'),
(94, 'QT-HIST-2026-193', 100, 1, 1831.74, 763.23, 457.94, 3052.90, '2026-09-11', 'confirmed', NULL, '2026-08-28 08:44:00', '2026-08-28 08:44:00'),
(95, 'QT-HIST-2026-194', 101, 1, 1880.81, 783.67, 470.20, 3134.68, '2026-09-11', 'confirmed', NULL, '2026-08-28 03:48:00', '2026-08-28 03:48:00'),
(96, 'QT-HIST-2026-195', 102, 1, 1390.16, 579.24, 347.54, 2316.94, '2026-09-11', 'confirmed', NULL, '2026-08-28 05:36:00', '2026-08-28 05:36:00'),
(97, 'QT-HIST-2026-196', 103, 1, 1684.55, 701.90, 421.14, 2807.58, '2026-09-11', 'confirmed', NULL, '2026-08-28 07:16:00', '2026-08-28 07:16:00'),
(98, 'QT-HIST-2026-197', 104, 1, 1537.36, 640.57, 384.34, 2562.26, '2026-08-16', 'confirmed', NULL, '2026-08-02 04:31:00', '2026-08-02 04:31:00'),
(99, 'QT-HIST-2026-198', 105, 1, 1586.42, 661.01, 396.60, 2644.03, '2026-08-19', 'confirmed', NULL, '2026-08-05 07:12:00', '2026-08-05 07:12:00'),
(100, 'QT-HIST-2026-199', 106, 1, 1782.68, 742.78, 445.67, 2971.13, '2026-08-22', 'confirmed', NULL, '2026-08-08 05:37:00', '2026-08-08 05:37:00'),
(101, 'QT-HIST-2026-200', 107, 1, 1766.32, 735.97, 441.58, 2943.87, '2026-08-25', 'confirmed', NULL, '2026-08-11 09:47:00', '2026-08-11 09:47:00'),
(102, 'QT-HIST-2026-201', 108, 1, 1602.77, 667.82, 400.69, 2671.29, '2026-08-28', 'confirmed', NULL, '2026-08-14 01:44:00', '2026-08-14 01:44:00'),
(103, 'QT-HIST-2026-202', 109, 1, 1848.10, 770.04, 462.02, 3080.16, '2026-08-31', 'confirmed', NULL, '2026-08-17 07:50:00', '2026-08-17 07:50:00'),
(104, 'QT-HIST-2026-203', 110, 1, 1406.51, 586.05, 351.63, 2344.19, '2026-09-03', 'confirmed', NULL, '2026-08-20 03:49:00', '2026-08-20 03:49:00'),
(105, 'QT-HIST-2026-204', 111, 1, 1537.36, 640.57, 384.34, 2562.26, '2026-09-06', 'confirmed', NULL, '2026-08-23 06:49:00', '2026-08-23 06:49:00'),
(106, 'QT-HIST-2026-205', 112, 1, 1439.23, 599.68, 359.81, 2398.71, '2026-09-09', 'confirmed', NULL, '2026-08-26 08:19:00', '2026-08-26 08:19:00'),
(107, 'QT-HIST-2026-206', 113, 1, 1439.23, 599.68, 359.81, 2398.71, '2026-08-16', 'confirmed', NULL, '2026-08-02 08:18:00', '2026-08-02 08:18:00'),
(108, 'QT-HIST-2026-207', 114, 1, 1570.06, 654.19, 392.52, 2616.77, '2026-08-19', 'confirmed', NULL, '2026-08-05 03:39:00', '2026-08-05 03:39:00'),
(109, 'QT-HIST-2026-208', 115, 1, 1864.45, 776.86, 466.11, 3107.42, '2026-08-22', 'confirmed', NULL, '2026-08-08 07:23:00', '2026-08-08 07:23:00'),
(110, 'QT-HIST-2026-209', 116, 1, 1619.13, 674.64, 404.78, 2698.55, '2026-08-25', 'confirmed', NULL, '2026-08-11 06:23:00', '2026-08-11 06:23:00'),
(111, 'QT-HIST-2026-210', 117, 1, 1553.71, 647.38, 388.43, 2589.52, '2026-08-28', 'confirmed', NULL, '2026-08-14 03:10:00', '2026-08-14 03:10:00'),
(112, 'QT-HIST-2026-211', 118, 1, 1537.36, 640.57, 384.34, 2562.26, '2026-08-31', 'confirmed', NULL, '2026-08-17 00:19:00', '2026-08-17 00:19:00'),
(113, 'QT-HIST-2026-212', 119, 1, 1651.84, 688.27, 412.96, 2753.06, '2026-09-03', 'confirmed', NULL, '2026-08-20 02:35:00', '2026-08-20 02:35:00'),
(114, 'QT-HIST-2026-213', 120, 1, 1651.76, 688.24, 412.94, 2752.94, '2026-09-16', 'confirmed', NULL, '2026-09-02 05:35:00', '2026-09-02 05:35:00'),
(115, 'QT-HIST-2026-214', 121, 1, 1750.87, 729.53, 437.72, 2918.12, '2026-09-19', 'confirmed', NULL, '2026-09-05 01:20:00', '2026-09-05 01:20:00'),
(116, 'QT-HIST-2026-215', 122, 1, 1420.52, 591.88, 355.13, 2367.53, '2026-09-22', 'confirmed', NULL, '2026-09-08 07:23:00', '2026-09-08 07:23:00'),
(117, 'QT-HIST-2026-216', 123, 1, 1618.73, 674.47, 404.68, 2697.88, '2026-09-25', 'confirmed', NULL, '2026-09-11 01:35:00', '2026-09-11 01:35:00'),
(118, 'QT-HIST-2026-217', 124, 1, 1767.39, 736.41, 441.85, 2945.65, '2026-09-28', 'confirmed', NULL, '2026-09-14 08:49:00', '2026-09-14 08:49:00'),
(119, 'QT-HIST-2026-218', 125, 1, 1536.14, 640.06, 384.04, 2560.24, '2026-10-01', 'confirmed', NULL, '2026-09-17 09:31:00', '2026-09-17 09:31:00'),
(120, 'QT-HIST-2026-219', 126, 1, 1866.49, 777.71, 466.62, 3110.82, '2026-10-04', 'confirmed', NULL, '2026-09-20 09:17:00', '2026-09-20 09:17:00'),
(121, 'QT-HIST-2026-220', 127, 1, 1783.91, 743.30, 445.98, 2973.18, '2026-10-07', 'confirmed', NULL, '2026-09-23 00:21:00', '2026-09-23 00:21:00'),
(122, 'QT-HIST-2026-221', 128, 1, 1552.66, 646.94, 388.16, 2587.76, '2026-09-16', 'confirmed', NULL, '2026-09-02 06:23:00', '2026-09-02 06:23:00'),
(123, 'QT-HIST-2026-222', 129, 1, 1602.21, 667.59, 400.55, 2670.35, '2026-09-19', 'confirmed', NULL, '2026-09-05 02:26:00', '2026-09-05 02:26:00'),
(124, 'QT-HIST-2026-223', 130, 1, 1470.07, 612.53, 367.52, 2450.12, '2026-09-22', 'confirmed', NULL, '2026-09-08 06:49:00', '2026-09-08 06:49:00'),
(125, 'QT-HIST-2026-224', 131, 1, 1404.00, 585.00, 351.00, 2340.00, '2026-09-25', 'confirmed', NULL, '2026-09-11 00:23:00', '2026-09-11 00:23:00'),
(126, 'QT-HIST-2026-225', 132, 1, 1503.11, 626.30, 375.78, 2505.18, '2026-09-28', 'confirmed', NULL, '2026-09-14 05:33:00', '2026-09-14 05:33:00'),
(127, 'QT-HIST-2026-226', 133, 1, 1420.52, 591.88, 355.13, 2367.53, '2026-10-01', 'confirmed', NULL, '2026-09-17 01:17:00', '2026-09-17 01:17:00'),
(128, 'QT-HIST-2026-227', 134, 1, 1519.63, 633.18, 379.91, 2532.71, '2026-09-16', 'confirmed', NULL, '2026-09-02 05:47:00', '2026-09-02 05:47:00'),
(129, 'QT-HIST-2026-228', 135, 1, 1767.39, 736.41, 441.85, 2945.65, '2026-09-19', 'confirmed', NULL, '2026-09-05 01:35:00', '2026-09-05 01:35:00'),
(130, 'QT-HIST-2026-229', 136, 1, 1618.73, 674.47, 404.68, 2697.88, '2026-09-22', 'confirmed', NULL, '2026-09-08 00:48:00', '2026-09-08 00:48:00'),
(131, 'QT-2026-301', 138, 14, 3200.00, 1100.00, 550.00, 4850.00, '2026-10-22', 'pending', 'Includes premium cardstock and custom hot foil plate stamping setup.', '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(132, 'QT-2026-302', 139, 15, 1600.00, 500.00, 250.00, 2350.00, '2026-10-29', 'confirmed', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(133, 'QT-2026-303', 140, 16, 2000.00, 850.00, 300.00, 3150.00, '2026-10-25', 'confirmed', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(134, 'QT-2026-304', 141, 17, 2800.00, 900.00, 500.00, 4200.00, '2026-10-20', 'confirmed', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17'),
(135, 'QT-2026-305', 142, 18, 2000.00, 500.00, 300.00, 2800.00, '2026-10-27', 'confirmed', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1Y8eSdA5VSxaDu8FwjlUALxDdJN30SrB8qZp5LDs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.141.1 Chrome/150.0.7871.250 Electron/43.7.7 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibWllU3JvUk5SYjFyWDg0U2xnanFhVXF5Zm43MG5aMnFWOTlNSWhhUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1792069602),
('IZ6vcvvfX3iWRnUJchCwV3CF0WOITgxgp53dS1x6', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36 Edg/155.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFdQOWNvWE8yMW9FdW43cEN6aExTbXE1aUIzVVh1eGwxakdUdDlwZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==', 1792076730),
('JueQ86QRHLk1NLIkbt9slBQqDPiAGjN2oeo2L5Ax', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36 Edg/155.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibFJtSEhldFIwUnZOc240azJPOFp0MVdTWE1NMExERE85bWNEVnEydCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9fQ==', 1792109157),
('NSnqntWfRbzh2ghktTVuSUqGxZ8BSH7nYlHBJGv7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.141.1 Chrome/150.0.7871.250 Electron/43.7.7 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidm5QRGdPRGhtcGxmSzRkdGk1aHp2SHVLMkRkbHdIbWtlYTVMU1ZzaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1792094899),
('TkIhTBANTXiQYkejZcR0DIKgSlPuoLku8K4qFV7t', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36 Edg/155.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiN0h0Q3RPZkRFeUg1aUlQYkc4WE14Y29BZUZlNjJwVHI2eDVDQnRaWiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvY3VzdG9tZXIvY2xhaW1pbmciO3M6NToicm91dGUiO3M6MjM6ImN1c3RvbWVyLmNsYWltaW5nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1OiJzdGF0ZSI7czo0MDoiaWUxRWt5S1JCOVhzTERJbjVRRGp4QkVtWFJ5T1AxbXlXbU1CUDVqNyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTg7fQ==', 1792095288),
('ymuCjpNQyGoRB0tV4UL6DZpcddKSBfsVvsSxuMmo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.142.0 Chrome/150.0.7871.250 Electron/43.7.7 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHhYVm1WVGtIa2c3Sk9IR2pPM1JjQXFoVzlYbTVBcDZja0JTQ1hsViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1792109145);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `movement_type` varchar(255) NOT NULL,
  `movement_date` date NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `branch_id`, `material_id`, `user_id`, `quantity`, `movement_type`, `movement_date`, `reference`, `reason`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 50.00, 'stock_in', '2026-09-10', 'SM-RESTOCK-001', 'Supplier Bulk Delivery PO-8821', 'Passed humidity inspection and stored in Zone 1.', '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(2, 2, 4, 5, 15.00, 'stock_in', '2026-09-09', 'SM-RESTOCK-002', 'Restock for outdoor election campaign banners', 'Verified roll length and thickness.', '2026-09-12 06:23:46', '2026-09-12 06:23:46'),
(3, 1, 2, 5, 120.00, 'stock_out', '2026-09-11', 'SM-DEDUCT-001', 'Press Floor Allocation for ORD-SEED001', 'Dispatched to Heidelberg XL 106 press operator.', '2026-09-12 06:23:46', '2026-09-12 06:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `avatar`, `role`, `phone`, `address`, `branch_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_archived`) VALUES
(1, 'Aldren Reyes', 'customer@capaciprint.com', NULL, NULL, 'customer', '+63 912 345 6789', 'Client Address', NULL, NULL, '$2y$12$8M7X2Y/AFywU0YQiLY0gqO4ghB.2KgGgaTiEEqwN1UnFh7SBOEPnK', NULL, '2026-08-20 04:29:54', '2026-09-12 05:58:08', 0),
(2, 'Alex Beniga', 'staff@capaciprint.com', NULL, NULL, 'staff', '+63 918 234 5678', 'Main Customer Desk', NULL, NULL, '$2y$12$npjSJWAA0OllaGNyv4dAguUeA79KYRiJaf7kg3RopLhY1Q6/xei6K', NULL, '2026-08-20 04:29:54', '2026-09-12 05:58:06', 0),
(3, 'Niere Lupogan', 'manager@capaciprint.com', NULL, NULL, 'manager', '+63 919 345 6789', 'Morning Star Printing Press', NULL, NULL, '$2y$12$qqQjDc1iKxs4LIk0y5t.P.PhCr0PFOLpM6VGJHfqqgkhpKh6sDG2O', NULL, '2026-08-20 04:29:54', '2026-09-12 05:58:05', 0),
(4, 'Kim Lupogan', 'production@capaciprint.com', NULL, NULL, 'production', '+63 920 456 7890', 'Main Hub Production Floor', NULL, NULL, '$2y$12$fn5BOuNlw4plSq5/RS6dxO9ervWUxKf3ZzmETt9F.qY537upKmcbS', NULL, '2026-08-20 04:29:55', '2026-09-12 05:58:07', 0),
(5, 'Mike Kosa', 'inventory@capaciprint.com', NULL, NULL, 'inventory', '+63 921 777 8888', 'Central Materials Storage', NULL, NULL, '$2y$12$zDJinZCiNYnVjTPZ5r//WOuyydleyfbgFhYhWqYdxGkwk7XhwvJtS', NULL, '2026-08-20 04:29:56', '2026-09-12 05:58:07', 0),
(6, 'Justine Bieber', 'management@capaciprint.com', NULL, NULL, 'owner', '+63 922 678 9012', 'Executive Headquarters', NULL, NULL, '$2y$12$blYZSPiDYyuDzEUthTsK/ucvXK1OhUEwBsTQ4i.GDnoyNt7WoJxHO', NULL, '2026-08-20 04:29:56', '2026-09-12 05:58:04', 0),
(7, 'Jessie Revalde', 'admin@capaciprint.com', NULL, NULL, 'admin', '+63 900 000 0002', 'System Operations HQ', NULL, NULL, '$2y$12$EJVUF.yphKReopG8zUbq0emVNsG1ym9FT79nQgsRafSTI4buSgZ8y', '7ZF6eI1TbhkvyFWkPB5H1jj0AHCFKQOdFcJ4f4Y3EwvsSbCXCg700i1Y4Ims', '2026-08-20 04:29:57', '2026-09-12 05:58:05', 0),
(9, 'Isagani Canal', 'officer@capaciprint.com', NULL, NULL, 'production_officer', '+63 919 444 5555', 'Operations Planning Hub', NULL, NULL, '$2y$12$CxDDMLynK1oMOUe4b2J2XusceN8sVWNRpDv3nHSAsnHBQKVrBxari', NULL, '2026-08-23 02:15:29', '2026-09-12 05:58:05', 0),
(10, 'Clarence Gotas', 'designer@capaciprint.com', NULL, NULL, 'staff', '+63 917 888 9999', 'Pre-Press Studio', NULL, NULL, '$2y$12$KcjREnhmohHhIfGUCPQ9n.sRV26mFu4oRU2RBNHFYqnFzHzcdvhhi', NULL, '2026-08-23 02:15:29', '2026-10-02 06:57:41', 0),
(11, 'Hearty Lupogan', 'owner@capaciprint.com', NULL, NULL, 'owner', '+63 922 678 9012', 'Executive Headquarters', NULL, NULL, '$2y$12$j1U3H0QgkR0pzr/RCB8y2.gkwdxruQjHD4oxIgPvIEvuprRQ2aNLK', NULL, '2026-08-23 04:40:54', '2026-10-15 06:10:20', 0),
(12, 'Juan Dela Cruz', 'juan.delacruz@gmail.com', 'goog_50acfb3a43d6cee3a4b4992b2b1fcd3a', NULL, 'customer', NULL, NULL, NULL, NULL, '$2y$12$QkrhZW81FRbXUhEYgoc9uejX0WdUrXMzxMJiWD0L8jdNv6LvyyNKm', 'Ic9WijAmpRsYZOR2tQGB3kTFbDPW3j3CbFpACr4MhulU2mYKdgNuonKzlnA2', '2026-09-15 06:29:46', '2026-09-15 06:29:46', 0),
(13, 'Maria Santos', 'maria.santos@gmail.com', NULL, NULL, 'customer', '+63 917 123 4501', 'Apex Creative Marketing, Suite 501, Ayala Ave, Makati City', NULL, NULL, '$2y$12$5Tl1bNv8q9O4XUKREIqM6.t2zwHa78mNZFlujTOusWQtZlDXu48nG', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17', 0),
(14, 'Carlo Mendez', 'carlo.mendez@gmail.com', NULL, NULL, 'customer', '+63 918 234 5602', 'Eleganza Event Stylings, Emerald Ave, Ortigas, Pasig City', NULL, NULL, '$2y$12$5Tl1bNv8q9O4XUKREIqM6.t2zwHa78mNZFlujTOusWQtZlDXu48nG', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17', 0),
(15, 'Beatrice Aquino', 'beatrice.aquino@gmail.com', NULL, NULL, 'customer', '+63 919 345 6703', 'University Student Council, University Belt, Manila', NULL, NULL, '$2y$12$5Tl1bNv8q9O4XUKREIqM6.t2zwHa78mNZFlujTOusWQtZlDXu48nG', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17', 0),
(16, 'Dave Villanueva', 'dave.villanueva@gmail.com', NULL, NULL, 'customer', '+63 920 456 7804', 'Ka-Kape Artisan Cafe, Tomas Morato, Quezon City', NULL, NULL, '$2y$12$5Tl1bNv8q9O4XUKREIqM6.t2zwHa78mNZFlujTOusWQtZlDXu48nG', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17', 0),
(17, 'Sarah Geronimo', 'sarah.geronimo@gmail.com', NULL, NULL, 'customer', '+63 921 567 8905', 'Bloom Fashion & Retail, BGC High Street, Taguig City', NULL, NULL, '$2y$12$5Tl1bNv8q9O4XUKREIqM6.t2zwHa78mNZFlujTOusWQtZlDXu48nG', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17', 0),
(18, 'Engr. Ramon Bautista', 'ramon.bautista@gmail.com', NULL, NULL, 'customer', '+63 922 678 9006', 'Bautista Builders & Engineering, Roxas Blvd, Pasay City', NULL, NULL, '$2y$12$5Tl1bNv8q9O4XUKREIqM6.t2zwHa78mNZFlujTOusWQtZlDXu48nG', NULL, '2026-10-15 06:37:17', '2026-10-15 06:37:17', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_inventory_branch_id_material_id_unique` (`branch_id`,`material_id`),
  ADD KEY `branch_inventory_material_id_foreign` (`material_id`);

--
-- Indexes for table `branch_recommendations`
--
ALTER TABLE `branch_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_recommendations_print_request_id_foreign` (`print_request_id`),
  ADD KEY `branch_recommendations_order_id_foreign` (`order_id`),
  ADD KEY `branch_recommendations_recommended_branch_id_foreign` (`recommended_branch_id`),
  ADD KEY `branch_recommendations_created_by_foreign` (`created_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `capacity_evaluations`
--
ALTER TABLE `capacity_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `capacity_evaluations_print_request_id_foreign` (`print_request_id`),
  ADD KEY `capacity_evaluations_branch_id_foreign` (`branch_id`),
  ADD KEY `capacity_evaluations_evaluated_by_foreign` (`evaluated_by`);

--
-- Indexes for table `claim_references`
--
ALTER TABLE `claim_references`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `claim_references_claim_code_unique` (`claim_code`),
  ADD KEY `claim_references_order_id_foreign` (`order_id`),
  ADD KEY `claim_references_user_id_foreign` (`user_id`);

--
-- Indexes for table `customer_notifications`
--
ALTER TABLE `customer_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_notifications_user_id_foreign` (`user_id`),
  ADD KEY `customer_notifications_order_id_foreign` (`order_id`);

--
-- Indexes for table `design_proofs`
--
ALTER TABLE `design_proofs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `design_proofs_print_request_id_foreign` (`print_request_id`),
  ADD KEY `design_proofs_designer_id_foreign` (`designer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `internal_notifications`
--
ALTER TABLE `internal_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `internal_notifications_user_id_foreign` (`user_id`),
  ADD KEY `internal_notifications_order_id_foreign` (`order_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `machines_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `machine_logs`
--
ALTER TABLE `machine_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `machine_logs_machine_id_foreign` (`machine_id`),
  ADD KEY `machine_logs_reported_by_foreign` (`reported_by`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_print_request_id_foreign` (`print_request_id`),
  ADD KEY `orders_quotation_id_foreign` (`quotation_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `print_requests`
--
ALTER TABLE `print_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `print_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `production_jobs`
--
ALTER TABLE `production_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `production_jobs_job_number_unique` (`job_number`),
  ADD KEY `production_jobs_order_id_foreign` (`order_id`),
  ADD KEY `production_jobs_branch_id_foreign` (`branch_id`),
  ADD KEY `production_jobs_machine_id_foreign` (`machine_id`),
  ADD KEY `production_jobs_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_requests_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `purchase_requests_material_id_foreign` (`material_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
  ADD KEY `quotations_print_request_id_foreign` (`print_request_id`),
  ADD KEY `quotations_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_movements_material_id_foreign` (`material_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `branch_recommendations`
--
ALTER TABLE `branch_recommendations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `capacity_evaluations`
--
ALTER TABLE `capacity_evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `claim_references`
--
ALTER TABLE `claim_references`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_notifications`
--
ALTER TABLE `customer_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `design_proofs`
--
ALTER TABLE `design_proofs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internal_notifications`
--
ALTER TABLE `internal_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `machine_logs`
--
ALTER TABLE `machine_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `print_requests`
--
ALTER TABLE `print_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `production_jobs`
--
ALTER TABLE `production_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  ADD CONSTRAINT `branch_inventory_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_inventory_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_recommendations`
--
ALTER TABLE `branch_recommendations`
  ADD CONSTRAINT `branch_recommendations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_recommendations_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_recommendations_print_request_id_foreign` FOREIGN KEY (`print_request_id`) REFERENCES `print_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_recommendations_recommended_branch_id_foreign` FOREIGN KEY (`recommended_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `capacity_evaluations`
--
ALTER TABLE `capacity_evaluations`
  ADD CONSTRAINT `capacity_evaluations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `capacity_evaluations_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `capacity_evaluations_print_request_id_foreign` FOREIGN KEY (`print_request_id`) REFERENCES `print_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `claim_references`
--
ALTER TABLE `claim_references`
  ADD CONSTRAINT `claim_references_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `claim_references_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_notifications`
--
ALTER TABLE `customer_notifications`
  ADD CONSTRAINT `customer_notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `design_proofs`
--
ALTER TABLE `design_proofs`
  ADD CONSTRAINT `design_proofs_designer_id_foreign` FOREIGN KEY (`designer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `design_proofs_print_request_id_foreign` FOREIGN KEY (`print_request_id`) REFERENCES `print_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `internal_notifications`
--
ALTER TABLE `internal_notifications`
  ADD CONSTRAINT `internal_notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `internal_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `machines`
--
ALTER TABLE `machines`
  ADD CONSTRAINT `machines_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `machine_logs`
--
ALTER TABLE `machine_logs`
  ADD CONSTRAINT `machine_logs_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `machine_logs_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_print_request_id_foreign` FOREIGN KEY (`print_request_id`) REFERENCES `print_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `print_requests`
--
ALTER TABLE `print_requests`
  ADD CONSTRAINT `print_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_jobs`
--
ALTER TABLE `production_jobs`
  ADD CONSTRAINT `production_jobs_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `production_jobs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_jobs_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `production_jobs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `purchase_requests_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_requests_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_print_request_id_foreign` FOREIGN KEY (`print_request_id`) REFERENCES `print_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
