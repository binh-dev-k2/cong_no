-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2024 at 01:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cong_no`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bin` int NOT NULL,
  `shortName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `swift_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `code`, `bin`, `shortName`, `logo`, `swift_code`) VALUES
(1, 'ICB', 970415, 'VietinBank', 'https://api.vietqr.io/img/ICB.png', 'ICBVVNVX'),
(2, 'VCB', 970436, 'Vietcombank', 'https://api.vietqr.io/img/VCB.png', 'BFTVVNVX'),
(3, 'BIDV', 970418, 'BIDV', 'https://api.vietqr.io/img/BIDV.png', 'BIDVVNVX'),
(4, 'VBA', 970405, 'Agribank', 'https://api.vietqr.io/img/VBA.png', 'VBAAVNVX'),
(5, 'OCB', 970448, 'OCB', 'https://api.vietqr.io/img/OCB.png', 'ORCOVNVX'),
(6, 'MB', 970422, 'MBBank', 'https://api.vietqr.io/img/MB.png', 'MSCBVNVX'),
(7, 'TCB', 970407, 'Techcombank', 'https://api.vietqr.io/img/TCB.png', 'VTCBVNVX'),
(8, 'ACB', 970416, 'ACB', 'https://api.vietqr.io/img/ACB.png', 'ASCBVNVX'),
(9, 'VPB', 970432, 'VPBank', 'https://api.vietqr.io/img/VPB.png', 'VPBKVNVX'),
(10, 'TPB', 970423, 'TPBank', 'https://api.vietqr.io/img/TPB.png', 'TPBVVNVX'),
(11, 'STB', 970403, 'Sacombank', 'https://api.vietqr.io/img/STB.png', 'SGTTVNVX'),
(12, 'HDB', 970437, 'HDBank', 'https://api.vietqr.io/img/HDB.png', 'HDBCVNVX'),
(13, 'VCCB', 970454, 'VietCapitalBank', 'https://api.vietqr.io/img/VCCB.png', 'VCBCVNVX'),
(14, 'SCB', 970429, 'SCB', 'https://api.vietqr.io/img/SCB.png', 'SACLVNVX'),
(15, 'VIB', 970441, 'VIB', 'https://api.vietqr.io/img/VIB.png', 'VNIBVNVX'),
(16, 'SHB', 970443, 'SHB', 'https://api.vietqr.io/img/SHB.png', 'SHBAVNVX'),
(17, 'EIB', 970431, 'Eximbank', 'https://api.vietqr.io/img/EIB.png', 'EBVIVNVX'),
(18, 'MSB', 970426, 'MSB', 'https://api.vietqr.io/img/MSB.png', 'MCOBVNVX'),
(19, 'CAKE', 546034, 'CAKE', 'https://api.vietqr.io/img/CAKE.png', NULL),
(20, 'Ubank', 546035, 'Ubank', 'https://api.vietqr.io/img/UBANK.png', NULL),
(21, 'TIMO', 963388, 'Timo', 'https://vietqr.net/portal-service/resources/icons/TIMO.png', NULL),
(22, 'VTLMONEY', 971005, 'ViettelMoney', 'https://api.vietqr.io/img/VIETTELMONEY.png', NULL),
(23, 'VNPTMONEY', 971011, 'VNPTMoney', 'https://api.vietqr.io/img/VNPTMONEY.png', NULL),
(24, 'SGICB', 970400, 'SaigonBank', 'https://api.vietqr.io/img/SGICB.png', 'SBITVNVX'),
(25, 'BAB', 970409, 'BacABank', 'https://api.vietqr.io/img/BAB.png', 'NASCVNVX'),
(26, 'PVCB', 970412, 'PVcomBank', 'https://api.vietqr.io/img/PVCB.png', 'WBVNVNVX'),
(27, 'Oceanbank', 970414, 'Oceanbank', 'https://api.vietqr.io/img/OCEANBANK.png', 'OCBKUS3M'),
(28, 'NCB', 970419, 'NCB', 'https://api.vietqr.io/img/NCB.png', 'NVBAVNVX'),
(29, 'SHBVN', 970424, 'ShinhanBank', 'https://api.vietqr.io/img/SHBVN.png', 'SHBKVNVX'),
(30, 'ABB', 970425, 'ABBANK', 'https://api.vietqr.io/img/ABB.png', 'ABBKVNVX'),
(31, 'VAB', 970427, 'VietABank', 'https://api.vietqr.io/img/VAB.png', 'VNACVNVX'),
(32, 'NAB', 970428, 'NamABank', 'https://api.vietqr.io/img/NAB.png', 'NAMAVNVX'),
(33, 'PGB', 970430, 'PGBank', 'https://api.vietqr.io/img/PGB.png', 'PGBLVNVX'),
(34, 'VIETBANK', 970433, 'VietBank', 'https://api.vietqr.io/img/VIETBANK.png', 'VNTTVNVX'),
(35, 'BVB', 970438, 'BaoVietBank', 'https://api.vietqr.io/img/BVB.png', 'BVBVVNVX'),
(36, 'SEAB', 970440, 'SeABank', 'https://api.vietqr.io/img/SEAB.png', 'SEAVVNVX'),
(37, 'COOPBANK', 970446, 'COOPBANK', 'https://api.vietqr.io/img/COOPBANK.png', NULL),
(38, 'LPB', 970449, 'LienVietPostBank', 'https://api.vietqr.io/img/LPB.png', 'LVBKVNVX'),
(39, 'KLB', 970452, 'KienLongBank', 'https://api.vietqr.io/img/KLB.png', 'KLBKVNVX'),
(40, 'KBank', 668888, 'KBank', 'https://api.vietqr.io/img/KBANK.png', 'KASIVNVX'),
(41, 'UOB', 970458, 'UnitedOverseas', 'https://api.vietqr.io/img/UOB.png', NULL),
(42, 'SCVN', 970410, 'StandardChartered', 'https://api.vietqr.io/img/SCVN.png', 'SCBLVNVX'),
(43, 'PBVN', 970439, 'PublicBank', 'https://api.vietqr.io/img/PBVN.png', 'VIDPVNVX'),
(44, 'NHB HN', 801011, 'Nonghyup', 'https://api.vietqr.io/img/NHB.png', NULL),
(45, 'IVB', 970434, 'IndovinaBank', 'https://api.vietqr.io/img/IVB.png', NULL),
(46, 'IBK - HCM', 970456, 'IBKHCM', 'https://api.vietqr.io/img/IBK.png', NULL),
(47, 'IBK - HN', 970455, 'IBKHN', 'https://api.vietqr.io/img/IBK.png', NULL),
(48, 'VRB', 970421, 'VRB', 'https://api.vietqr.io/img/VRB.png', NULL),
(49, 'WVN', 970457, 'Woori', 'https://api.vietqr.io/img/WVN.png', NULL),
(50, 'KBHN', 970462, 'KookminHN', 'https://api.vietqr.io/img/KBHN.png', NULL),
(51, 'KBHCM', 970463, 'KookminHCM', 'https://api.vietqr.io/img/KBHCM.png', NULL),
(52, 'HSBC', 458761, 'HSBC', 'https://api.vietqr.io/img/HSBC.png', 'HSBCVNVX'),
(53, 'HLBVN', 970442, 'HongLeong', 'https://api.vietqr.io/img/HLBVN.png', 'HLBBVNVX'),
(54, 'GPB', 970408, 'GPBank', 'https://api.vietqr.io/img/GPB.png', 'GBNKVNVX'),
(55, 'DOB', 970406, 'DongABank', 'https://api.vietqr.io/img/DOB.png', 'EACBVNVX'),
(56, 'DBS', 796500, 'DBSBank', 'https://api.vietqr.io/img/DBS.png', 'DBSSVNVX'),
(57, 'CIMB', 422589, 'CIMB', 'https://api.vietqr.io/img/CIMB.png', 'CIBBVNVN'),
(58, 'CBB', 970444, 'CBBank', 'https://api.vietqr.io/img/CBB.png', 'GTBAVNVX'),
(59, 'CITIBANK', 533948, 'Citibank', 'https://api.vietqr.io/img/CITIBANK.png', NULL),
(60, 'KEBHANAHCM', 970466, 'KEBHanaHCM', 'https://api.vietqr.io/img/KEBHANAHCM.png', NULL),
(61, 'KEBHANAHN', 970467, 'KEBHanaHN', 'https://api.vietqr.io/img/KEBHANAHN.png', NULL),
(62, 'MAFC', 977777, 'MAFC', 'https://api.vietqr.io/img/MAFC.png', NULL),
(63, 'VBSP', 999888, 'VBSP', 'https://api.vietqr.io/img/VBSP.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_percent` int NOT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_money` bigint NOT NULL,
  `formality` enum('R','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` bigint NOT NULL,
  `pay_extra` bigint DEFAULT NULL,
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_money`
--

CREATE TABLE `business_money` (
  `id` bigint UNSIGNED NOT NULL,
  `business_id` bigint UNSIGNED NOT NULL,
  `money` bigint NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_due` smallint DEFAULT NULL,
  `date_return` date DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_histories`
--

CREATE TABLE `card_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `card_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_percent` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

CREATE TABLE `debts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `formality` enum('R','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` bigint NOT NULL,
  `pay_extra` bigint DEFAULT NULL,
  `total_amount` bigint DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_05_09_173215_create_customers_table', 1),
(6, '2024_05_09_173234_create_cards_table', 1),
(7, '2024_05_09_174611_create_card_histories_table', 1),
(8, '2024_05_15_021232_create_businesses_table', 1),
(9, '2024_05_15_021248_create_business_money_table', 1),
(10, '2024_05_15_021250_create_debts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', '2024-05-15 18:05:16', '$2y$10$77iBvFf5Q.U3MMMWjYc.cOfwz8.l0rtnvletA6BSDazl4qWihpdOq', 'SVSzDi39ObQKbUGiKzVArNFNQms040Pti9bjbhTIra1KZaVQ0O7XKI7L0ZIQ', '2024-05-15 18:05:17', '2024-05-15 18:05:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_money`
--
ALTER TABLE `business_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `card_histories`
--
ALTER TABLE `card_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_phone_index` (`phone`);

--
-- Indexes for table `debts`
--
ALTER TABLE `debts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_money`
--
ALTER TABLE `business_money`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card_histories`
--
ALTER TABLE `card_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debts`
--
ALTER TABLE `debts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
