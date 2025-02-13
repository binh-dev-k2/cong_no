-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 10, 2025 at 02:00 PM
-- Server version: 10.6.20-MariaDB-cll-lve-log
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zarcrcgj_cong_no`
--



--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(76, 'dashboard', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(77, 'customer-view', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(78, 'customer-create', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(79, 'customer-update', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(80, 'customer-delete', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(81, 'business-view', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(82, 'business-create', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(83, 'business-update', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(85, 'debit-view', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(87, 'debit-update', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(89, 'user-view', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(90, 'user-create', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(91, 'user-update', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(92, 'user-delete', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(93, 'role-view', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(94, 'role-create', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(95, 'role-update', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(96, 'role-delete', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(97, 'machine-view', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(98, 'machine-create', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(99, 'machine-update', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47'),
(100, 'machine-delete', 'web', '2025-02-07 02:23:47', '2025-02-07 02:23:47');

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(7, 'Admin', 'web', '2025-02-08 08:49:18', '2025-02-08 08:49:21');

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(76, 7),
(77, 7),
(78, 7),
(79, 7),
(80, 7),
(81, 7),
(82, 7),
(83, 7),
(85, 7),
(87, 7),
(89, 7),
(90, 7),
(91, 7),
(92, 7),
(93, 7),
(94, 7),
(95, 7),
(96, 7),
(97, 7),
(98, 7),
(99, 7),
(100, 7);
--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(7, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 8);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
