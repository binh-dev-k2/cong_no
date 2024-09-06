-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 03, 2024 at 09:13 PM
-- Server version: 10.6.18-MariaDB-cll-lve-log
-- PHP Version: 8.3.8

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

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bin` int(11) DEFAULT NULL,
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
(63, 'VBSP', 999888, 'VBSP', 'https://api.vietqr.io/img/VBSP.png', NULL),
(64, 'FECREDIT', NULL, 'FE Credit', 'https://brademar.com/wp-content/uploads/2022/09/FE-CREDIT-Logo-PNG-2.png', NULL),
(65, 'HOMECREDIT', NULL, 'Home Credit', 'https://trustingsocial.com/uploads/vn-homecredit.png', NULL),
(66, 'MCREDIT', NULL, 'Mcredit', 'https://trustingsocial.com/uploads/vn-mcredit.png', NULL),
(67, 'VIETCREDIT', NULL, 'VietCredit', 'https://trustingsocial.com/uploads/vn-viet-credit.png', NULL),
(68, 'SHINHANFINANCE', NULL, 'Shinhan Finance', 'https://static.ybox.vn/2023/6/3/1686103724869-logo-ngang-ch%E1%BB%AF-xanh-kh%C3%B4ng-n%E1%BB%81n-_website.png', NULL),
(69, 'MIRAEASSET', NULL, 'Mirae Asset', 'https://cdn.tuoitrethudo.vn/stores/news_dataimages/2023/092023/15/11/croped/thumbnail/294873410-464540765674556-7494395062025773479-n20230915111446.png?230915021405', NULL),
(70, 'LOTTEFINANCE', NULL, 'LOTTE Finance', 'https://i.pinimg.com/originals/6a/4d/0f/6a4d0fcecee7b9f0f50be9e17a9fd7b7.png', NULL),
(71, 'SHBFINANCE', NULL, 'SHB Finance', 'https://assets-global.website-files.com/6107d6546b656b27d809cd54/6107d6546b656b31df09d091_shb.svg', NULL),
(72, 'JACCS', NULL, 'JACCS', 'https://monfin.vn/images/source/Congty/logo%20jaccs.png', NULL),
(73, 'VIETCAPITAL', NULL, 'Bản Việt', 'https://wikiland.vn/wp-content/uploads/logo-vietcapital.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
