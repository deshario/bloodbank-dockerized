-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2018 at 09:35 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloodbank`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `requester_id` int(11) NOT NULL COMMENT 'Requester ID',
  `blood_group` int(11) NOT NULL COMMENT 'Blood Types ID',
  `blood_amount` int(11) NOT NULL COMMENT 'Required Blood Units',
  `paid_amount` int(11) DEFAULT '0' COMMENT 'Paid Blood Units',
  `lat_long` varchar(50) NOT NULL COMMENT 'LatLong',
  `location_name` varchar(50) NOT NULL,
  `full_address` text COMMENT 'Full Address',
  `reason` varchar(255) DEFAULT NULL COMMENT 'Reason',
  `postal_code` varchar(10) DEFAULT NULL COMMENT 'Postal',
  `created` datetime NOT NULL COMMENT 'Created',
  `req_key` varchar(255) NOT NULL COMMENT 'Request Key',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT 'Status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `requester_id`, `blood_group`, `blood_amount`, `paid_amount`, `lat_long`, `location_name`, `full_address`, `reason`, `postal_code`, `created`, `req_key`, `status`) VALUES
(9, 2, 4, 4, 2, '18.794559, 100.788720', 'PhuPhiang Hospital', 'Worawichai, Tambon Nai Wiang, Amphoe Mueang Nan, C...', '', '55000', '2018-06-10 12:55:41', 'liufdlk6xhqmJ', 1),
(10, 3, 6, 4, 2, '18.794559, 100.788720', 'Nan Hospital', NULL, 'blood transfusion', '55000', '2018-06-20 12:11:25', 'e0tY6xhqmJ', 2),
(20, 1, 4, 8, 5, '18.798029148367117,100.8004218889771', 'โรงพยาบาลภูเพียง', NULL, '', '55000', '2018-06-18 19:26:58', 'qfx1wbjZpGtjo4Q', 1),
(21, 1, 1, 0, 0, '18.794372745745633,100.7683641268311', 'โรงพยาบาลเชียงใหม่ราม', NULL, '', '55000', '2018-06-18 22:00:35', 'K3mLIdu2LT6kNte', 0),
(22, 3, 2, 4, 2, '40.7651472,-73.9548601', 'รพ ท้องถิ่น', NULL, '', '55000', '2018-06-18 22:02:48', 'w5Fk114ESvdepKQ', 1),
(23, 3, 6, 4, 3, '18.7944374,100.7886979', 'wwwwwwwwww', 'dsdsf', '', NULL, '2018-07-30 03:29:50', 'YHQCCpxPuJlij51', 0),
(25, 1, 8, 11, 8, '18.7944374,100.7886979', 'Nan Hospital', '1 Worawichai Tambon Nai Wiang, อำเภอ เมืองน่าน Chang Wat Nan 55000, Thailand', 'ww', NULL, '2018-07-30 19:35:43', 'Ukwc_tsLJGZgJ-0', 0),
(26, 1, 6, 18, 12, '18.7780058,99.0145297', 'Hospital Kawila', 'ค่ายทหารกาวิละ 285 ซอย หน้าวัดเกตุ ตำบล วัดเกตุ อำเภอ เมืองเชียงใหม่ Chang Wat Chiang Mai 50000, Thailand', '', NULL, '2018-08-01 19:52:29', 'dDbVrjk4fwMZ4ZQ', 2);

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests_verification`
--

CREATE TABLE `blood_requests_verification` (
  `donate_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL COMMENT 'Blood Requests ID',
  `donated_by` int(11) NOT NULL COMMENT 'Donater',
  `donated_to` int(11) NOT NULL COMMENT 'Requester',
  `manager_id` int(11) DEFAULT NULL COMMENT 'Verified By',
  `paid_amount` int(11) NOT NULL COMMENT 'Donated Amount',
  `verified` int(5) DEFAULT '0' COMMENT 'Verified Status',
  `donated_date` datetime NOT NULL COMMENT 'Donated Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blood_requests_verification`
--

INSERT INTO `blood_requests_verification` (`donate_id`, `request_id`, `donated_by`, `donated_to`, `manager_id`, `paid_amount`, `verified`, `donated_date`) VALUES
(1, 26, 1, 2, 3, 1, 0, '2018-08-12 19:49:34'),
(6, 20, 2, 1, 2, 2, 0, '2018-08-22 19:36:30'),
(10, 22, 1, 3, NULL, 1, 0, '2018-08-22 20:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `blood_types`
--

CREATE TABLE `blood_types` (
  `blood_id` int(11) NOT NULL COMMENT 'ID',
  `blood_name` varchar(45) NOT NULL COMMENT 'Bloodgroup'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blood_types`
--

INSERT INTO `blood_types` (`blood_id`, `blood_name`) VALUES
(1, 'A+'),
(2, 'A-'),
(5, 'AB+'),
(6, 'AB-'),
(3, 'B+'),
(4, 'B-'),
(7, 'O+'),
(8, 'O-');

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL COMMENT 'Branch Name',
  `branch_lat_long` varchar(50) NOT NULL COMMENT 'Branch Latlong',
  `branch_address` text NOT NULL COMMENT 'Branch Address',
  `branch_code` varchar(45) NOT NULL COMMENT 'Branch Code',
  `branch_created` datetime NOT NULL COMMENT 'Branch Created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branch_id`, `branch_name`, `branch_lat_long`, `branch_address`, `branch_code`, `branch_created`) VALUES
(1, 'Nan Hospital', '18.794445,100.788695', '1 Worawichai Tambon Nai Wiang, อำเภอ เมืองน่าน Chang Wat Nan 55000', 'NA2018512', '2018-06-22 02:07:30'),
(3, 'Chiangmai Hospital ', '18.8106154,98.98138549999999', '80/137 Hwy Chiang Mai-Lampang Frontage Rd, Tambon Chang Phueak, Amphoe Mueang Chiang Mai, Chang Wat Chiang Mai 50300, Thailand', 'CHI24061858', '2018-06-24 18:58:28'),
(4, 'Phrae Hospital', '18.134112,100.153966', '80/137dwadwadaw-Lampang Frontage Rd, Tambon Chandwadwada', 'CHe561858', '2018-06-24 18:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `branch_requests`
--

CREATE TABLE `branch_requests` (
  `req_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL COMMENT 'Requested By Branch',
  `blood_group` int(11) NOT NULL COMMENT 'BloodGroup',
  `blood_amount` int(11) NOT NULL COMMENT 'Required Blood Units',
  `paid_amount` int(11) DEFAULT NULL COMMENT 'Paid Blood Units',
  `created` datetime NOT NULL COMMENT 'Requested Date',
  `req_key` varchar(255) NOT NULL COMMENT 'Request Key',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT 'Status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch_requests`
--

INSERT INTO `branch_requests` (`req_id`, `branch_id`, `blood_group`, `blood_amount`, `paid_amount`, `created`, `req_key`, `status`) VALUES
(5, 1, 2, 12, 10, '2018-08-04 17:02:24', 'EA5qb8J-ZIqezVL', 1),
(7, 1, 5, 10, 8, '2018-08-12 12:02:31', 'auukjIyPvLcK99J', 1),
(8, 3, 4, 20, 17, '2018-08-12 13:18:06', 'bmShk0OqryFBrvn', 1);

-- --------------------------------------------------------

--
-- Table structure for table `branch_requests_verification`
--

CREATE TABLE `branch_requests_verification` (
  `donate_id` int(11) NOT NULL,
  `branch_requests_id` int(11) NOT NULL COMMENT 'Branch Request ID',
  `donor_id` int(11) NOT NULL COMMENT 'Donated By',
  `paid_amount` int(11) NOT NULL COMMENT 'Donated Amount',
  `verified` int(11) DEFAULT '0' COMMENT 'Verified Status',
  `donated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch_requests_verification`
--

INSERT INTO `branch_requests_verification` (`donate_id`, `branch_requests_id`, `donor_id`, `paid_amount`, `verified`, `donated_date`) VALUES
(19, 8, 2, 2, 0, '2018-08-22 19:46:34'),
(22, 7, 1, 2, 0, '2018-08-23 00:47:12');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` int(11) NOT NULL,
  `campaign_name` varchar(100) NOT NULL COMMENT 'Campaign Name',
  `campaign_desc` varchar(255) NOT NULL COMMENT 'Campaign Description',
  `campaign_img` varchar(100) NOT NULL COMMENT 'Campaign Img',
  `campaign_created` datetime NOT NULL COMMENT 'Created At',
  `campaign_coordinates` varchar(50) NOT NULL COMMENT 'Campaign Latlong',
  `campaign_address` text NOT NULL COMMENT 'Campaign Address',
  `campaign_key` varchar(255) NOT NULL COMMENT 'Campaign Key',
  `campaign_creator` int(11) NOT NULL COMMENT 'Campaign Created By',
  `campaign_status` int(1) NOT NULL DEFAULT '1' COMMENT 'Available Status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_id`, `campaign_name`, `campaign_desc`, `campaign_img`, `campaign_created`, `campaign_coordinates`, `campaign_address`, `campaign_key`, `campaign_creator`, `campaign_status`) VALUES
(1, 'Campaign 1', 'An advertising campaign is a series of advertisement messages that share a single idea and theme which make up an integrated marketing communicatio', '8647_1530964732_07071858.jpg', '2018-07-12 00:00:00', '18.798029148367117,100.8004218889771', '80/137 Hwy Chiang Mai-Lampang Frontage Rd, Tambon Chang Phueak, Amphoe Mueang Chiang Mai, Chang Wat Chiang Mai 50300, Thailand', 'dsad', 2, 1),
(2, 'Campaign 2', 'การบริจาคเลือดนั้นรวมไปถึงการบริจาคเม็ดเลือดแดง เกล็ดเลือด และพลาสม่า ซึ่งเป็นส่วนประกอบสำคัญของเลือด โดยก่อนที่จะบริจาคเลือดได้นั้นต้องผ่านการทดสอบความเข้มข้นของเลือดและการซักประวัติก่อนเพื่อให้แน่ใจว่าสามารถบริจาคเลือดได้ ส่วนเลือดที่ได้รับการบริจาคมานั', 'bloodie.jpg', '2018-07-17 00:00:00', '18.798029148367117,100.8004218889771', 'sadasddsd', 'ryydfg', 3, 1),
(3, 'Campaign 3', 'Campaign 3 Desc', '8647_1530964732_07071858.jpg', '2018-07-18 00:00:00', '18.798029148367117,100.8004218889771', 'dfgfhgfh', 'dsad2', 5, 0),
(4, 'Campaign 3', 'Campaign 3 Desc', '8647_1530964732_07071858.jpg', '2018-07-18 00:00:00', '18.798029148367117,100.8004218889771', 'dfgfhgfh', 'fghfhasd', 5, 0),
(5, 'Campaign 5', 'Campaign 5 Desc', '8647_1530964732_07071858.jpg', '2018-07-18 00:00:00', '54654564,46546', 'dfgfhgfh', 'fghfh', 5, 1),
(6, 'Mero Campaign', 'Mero Desc', '8647_1530964732_07071858.jpg', '2018-07-07 18:58:52', '18.7944374,100.78869789999999', 'Worawichai, Tambon Nai Wiang, Amphoe Mueang Nan, Chang Wat Nan 55000, Thailand', 'ADM07071858.lSxSfRzoVR2OfKy', 3, 1),
(9, 'My Campaign', 'd', '4104_1536314743_07091705.jpg', '2018-09-07 17:05:43', '40.73028556187854,-73.84497798956977', '67-01 110th St, Forest Hills, NY 11375, USA', 'HOC07091705.cTBhoT6xRyhbFIr', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_subscribed`
--

CREATE TABLE `campaigns_subscribed` (
  `subscribe_id` int(11) NOT NULL,
  `subscribed_campaign` int(11) NOT NULL COMMENT 'Campaign ID',
  `subscribed_by` int(11) NOT NULL COMMENT 'User ID',
  `subscribed_date` datetime NOT NULL COMMENT 'Subscibed Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `campaigns_subscribed`
--

INSERT INTO `campaigns_subscribed` (`subscribe_id`, `subscribed_campaign`, `subscribed_by`, `subscribed_date`) VALUES
(1, 1, 1, '2018-09-11 00:00:00'),
(2, 1, 3, '2018-09-13 00:00:00'),
(3, 6, 3, '2018-09-13 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `donation_day_reservation`
--

CREATE TABLE `donation_day_reservation` (
  `reserved_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Reserved By',
  `branch_id` int(11) NOT NULL COMMENT 'Reserved Branch',
  `user_notes` varchar(100) DEFAULT NULL COMMENT 'User Notes',
  `reservation_key` varchar(255) NOT NULL COMMENT 'Reservation Key',
  `reserved_date` datetime NOT NULL COMMENT 'Reserved Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `donation_day_reservation`
--

INSERT INTO `donation_day_reservation` (`reserved_id`, `user_id`, `branch_id`, `user_notes`, `reservation_key`, `reserved_date`) VALUES
(1, 1, 3, '', 'RD-0408-234959-eebx', '2018-08-11 11:35:34'),
(4, 1, 4, 'haloinnote', 'RD-1108-223815-0kmf', '2018-08-14 06:17:19'),
(5, 1, 3, 'ff', 'RD-1108-224033-22v2', '2018-08-23 16:40:00'),
(6, 1, 1, 'v', 'RD-1908-203205-883i', '2018-08-29 17:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Authentication Key',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password Hash',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Password Reset Token',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT 'Status',
  `roles` int(11) NOT NULL COMMENT 'Roles',
  `created_at` int(11) NOT NULL COMMENT 'Created At',
  `updated_at` int(11) NOT NULL COMMENT 'Updated At',
  `manager_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `worked_at` int(11) DEFAULT NULL COMMENT 'Work At Branch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `roles`, `created_at`, `updated_at`, `manager_key`, `worked_at`) VALUES
(2, 'hocker', 'h4bAo4ywowkVEMizDcxOicdQSw-kspVx', '$2y$13$hvUypnZcRhFH3VaL952Or.6aQj9i8m1WOb1l.c876qGO0jUju/e3C', NULL, 'deshario9@gmail.com', 10, 20, 1529327156, 1529327156, 'Hr52TEfoA-', 3),
(3, 'admin', 'g4U_MVBGHUU4X9ui59ZPebmRmOBQ0EWM', '$2y$13$hvUypnZcRhFH3VaL952Or.6aQj9i8m1WOb1l.c876qGO0jUju/e3C', NULL, 'admin@email.com', 10, 30, 1530981836, 1529332593, 'Zl415pbkY8', 1),
(5, 'halow', '9ggfx9aH1VtF5EeSO5jclH4gpn3W5PUq', '$2y$13$rYeYTMjKFnFw24wuL2cxS.OeAZB9TZ2CZLEuHSGzQc2fq/nsEoMka', NULL, 'halo@admin.com', 10, 20, 1529329299, 1529332812, '1MJzV7pZ1C', 1),
(6, 'linux', 'tRNT5uaijFJsU8atwfTyROEZX5lR8AR6', '$2y$13$W9NRPFP9SIJ1e2mV0ohzHuXC1N8NymB/1rQIcs3hzs3hL073HV72C', NULL, 'linux@gmail.com', 0, 20, 1533213451, 1533213451, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `saved_blood_requests`
--

CREATE TABLE `saved_blood_requests` (
  `saved_id` int(11) NOT NULL COMMENT 'ID',
  `request_id` int(11) NOT NULL COMMENT 'BloodRequest ID',
  `saved_by` int(11) NOT NULL COMMENT 'Donor ID',
  `saved_date` datetime NOT NULL COMMENT 'Saved Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `saved_blood_requests`
--

INSERT INTO `saved_blood_requests` (`saved_id`, `request_id`, `saved_by`, `saved_date`) VALUES
(1, 20, 2, '2018-08-13 16:49:12'),
(2, 22, 1, '2018-06-22 16:51:05'),
(3, 26, 3, '2018-08-13 16:51:48'),
(4, 21, 3, '2018-08-13 16:53:59'),
(5, 25, 2, '2018-08-13 16:55:07'),
(6, 9, 1, '2018-08-13 20:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `saved_branch_requests`
--

CREATE TABLE `saved_branch_requests` (
  `saved_id` int(11) NOT NULL,
  `requests_id` int(11) NOT NULL COMMENT 'BranchRequest ID',
  `saved_by` int(11) NOT NULL COMMENT 'Donor ID',
  `saved_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `saved_branch_requests`
--

INSERT INTO `saved_branch_requests` (`saved_id`, `requests_id`, `saved_by`, `saved_date`) VALUES
(1, 8, 1, '2018-08-16 00:00:00'),
(2, 7, 1, '2018-08-13 17:47:54'),
(3, 8, 3, '2018-08-13 17:48:08'),
(4, 5, 3, '2018-08-13 20:50:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL COMMENT 'Username',
  `blood_group` int(11) NOT NULL COMMENT 'Blood Types ID',
  `phone` varchar(10) DEFAULT NULL COMMENT 'Phone no',
  `profile_salt` varchar(10) DEFAULT NULL COMMENT 'Salt',
  `profile_password` varchar(100) DEFAULT NULL COMMENT 'Encrypyted Password',
  `created` datetime NOT NULL COMMENT 'Registered Date',
  `facebook_id` varchar(45) DEFAULT NULL COMMENT 'Facebook ID (Optional)',
  `profile_token` text COMMENT 'Firebase Token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `username`, `blood_group`, `phone`, `profile_salt`, `profile_password`, `created`, `facebook_id`, `profile_token`) VALUES
(1, 'deshario', 3, '0856183174', 'd4c3e0e5a8', 'OK/8pzeCtejhayIsfdfja+AlV2hkNGMzZTBlNWE4', '2018-08-12 18:20:37', NULL, 'wwwd'),
(2, 'messi', 5, '9845685658', 'c2548064ea', 'bSufp7U04kmfLn/le0pXCuNtlO1jMjU0ODA2NGVh', '2018-08-12 18:21:11', NULL, 'bb'),
(3, 'neymar', 7, '123465789', 'd4c3e0e5a8', 'OK/8pzeCtejhayIsfdfja+AlV2hkNGMzZTBlNWE4', '2018-08-12 18:38:20', NULL, 'eN-ovv30CN0:APA91bGtSH14MNlmjIUiuPk13-0ruokyWMF3tv6anbCtRbqLlnIxDxkuG9--u_MTWQMDPEisiHtiJW0il7hG9tKpzRmuLw6DovCTpxLFDJrSQsobAtVJ9Bvr4tw51-l8XcrygtMgwiLR');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_blood_requests`
-- (See below for the actual view)
--
CREATE TABLE `v_blood_requests` (
`id` int(11)
,`requester_id` int(11)
,`requester_name` varchar(50)
,`blood_group` varchar(45)
,`blood_amount` decimal(32,0)
,`paid_amount` decimal(32,0)
,`lat_long` varchar(50)
,`location_name` varchar(50)
,`full_address` text
,`reason` varchar(255)
,`postal_code` varchar(10)
,`created` datetime
,`req_key` varchar(255)
,`num_donors` bigint(21)
,`status` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_blood_req_verification`
-- (See below for the actual view)
--
CREATE TABLE `v_blood_req_verification` (
`donor` varchar(50)
,`donor_id` int(11)
,`receiver` varchar(50)
,`receiver_id` int(11)
,`blood_group` varchar(45)
,`blood_amount` int(11)
,`paid_amount` int(11)
,`lat_long` varchar(50)
,`location_name` varchar(50)
,`full_address` text
,`reason` varchar(255)
,`postal_code` varchar(10)
,`request_key` varchar(255)
,`verified_by` varchar(255)
,`donated_date` datetime
,`donation_status` int(5)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_branch_requests`
-- (See below for the actual view)
--
CREATE TABLE `v_branch_requests` (
`branch_id` int(11)
,`branchrequest_id` int(11)
,`branch_name` varchar(100)
,`branch_address` text
,`branch_code` varchar(45)
,`branch_lat_long` varchar(50)
,`blood_group` varchar(45)
,`blood_amount` decimal(32,0)
,`paid_amount` decimal(32,0)
,`created` datetime
,`status` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_branch_req_verification`
-- (See below for the actual view)
--
CREATE TABLE `v_branch_req_verification` (
`donor_id` int(11)
,`donate_by` varchar(50)
,`branch_id` int(11)
,`branch_name` varchar(100)
,`blood_group` varchar(45)
,`blood_amount` int(11)
,`paid_amount` int(11)
,`latlong` varchar(50)
,`full_address` text
,`req_key` varchar(255)
,`donated_date` datetime
,`verified` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_campaigns`
-- (See below for the actual view)
--
CREATE TABLE `v_campaigns` (
`campaign_id` int(11)
,`campaign_name` varchar(100)
,`campaign_desc` varchar(255)
,`campaign_img` varchar(100)
,`campaign_created` datetime
,`campaign_coordinates` varchar(50)
,`campaign_address` text
,`campaign_key` varchar(255)
,`campaign_creator` varchar(255)
,`campaign_joined` bigint(21)
,`campaign_status` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_campaigns_subscribed`
-- (See below for the actual view)
--
CREATE TABLE `v_campaigns_subscribed` (
`u_id` int(11)
,`username` varchar(50)
,`profile_token` text
,`campaign_id` int(11)
,`campaign_name` varchar(100)
,`campaign_key` varchar(255)
,`subscribed_date` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_donation_reservation`
-- (See below for the actual view)
--
CREATE TABLE `v_donation_reservation` (
`reserved_id` int(11)
,`user_id` int(11)
,`username` varchar(50)
,`branch_id` int(11)
,`branch_name` varchar(100)
,`user_notes` varchar(100)
,`reservation_key` varchar(255)
,`reserved_date` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_requested_bloodgroup_analysis`
-- (See below for the actual view)
--
CREATE TABLE `v_requested_bloodgroup_analysis` (
`blood_id` int(11)
,`blood_name` varchar(45)
,`blood_amount` decimal(32,0)
,`paid_amount` decimal(32,0)
,`requested_times` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_request_status_analysis`
-- (See below for the actual view)
--
CREATE TABLE `v_request_status_analysis` (
`status_alias` varchar(9)
,`status_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_saved_bloodreq`
-- (See below for the actual view)
--
CREATE TABLE `v_saved_bloodreq` (
`donor_id` int(11)
,`donor_name` varchar(50)
,`requester_id` int(11)
,`requester_name` varchar(50)
,`bloodreq_id` int(11)
,`blood_name` varchar(45)
,`blood_amount` int(11)
,`paid_amount` int(11)
,`location_name` varchar(50)
,`lat_long` varchar(50)
,`full_address` text
,`saved_date` datetime
,`req_key` varchar(255)
,`req_status` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_saved_branchreq`
-- (See below for the actual view)
--
CREATE TABLE `v_saved_branchreq` (
`user_id` int(11)
,`user_name` varchar(50)
,`blood_name` varchar(45)
,`blood_amount` int(11)
,`paid_amount` int(11)
,`branchreq_id` int(11)
,`branch_name` varchar(100)
,`branch_code` varchar(45)
,`branch_lat_long` varchar(50)
,`branch_address` text
,`saved_date` datetime
,`req_key` varchar(255)
,`req_status` int(1)
);

-- --------------------------------------------------------

--
-- Structure for view `v_blood_requests`
--
DROP TABLE IF EXISTS `v_blood_requests`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_blood_requests`  AS  select `blood_requests`.`id` AS `id`,`blood_requests`.`requester_id` AS `requester_id`,`users`.`username` AS `requester_name`,`blood_types`.`blood_name` AS `blood_group`,sum(`blood_requests`.`blood_amount`) AS `blood_amount`,sum(`blood_requests`.`paid_amount`) AS `paid_amount`,`blood_requests`.`lat_long` AS `lat_long`,`blood_requests`.`location_name` AS `location_name`,`blood_requests`.`full_address` AS `full_address`,`blood_requests`.`reason` AS `reason`,`blood_requests`.`postal_code` AS `postal_code`,`blood_requests`.`created` AS `created`,`blood_requests`.`req_key` AS `req_key`,(select count(0) from `blood_requests_verification` where ((`blood_requests_verification`.`request_id` = `blood_requests`.`id`) and (`blood_requests_verification`.`verified` = 1))) AS `num_donors`,`blood_requests`.`status` AS `status` from ((`blood_requests` join `users` on((`users`.`u_id` = `blood_requests`.`requester_id`))) join `blood_types` on((`blood_types`.`blood_id` = `blood_requests`.`blood_group`))) group by `blood_requests`.`created` ;

-- --------------------------------------------------------

--
-- Structure for view `v_blood_req_verification`
--
DROP TABLE IF EXISTS `v_blood_req_verification`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_blood_req_verification`  AS  select `donor`.`username` AS `donor`,`donor`.`u_id` AS `donor_id`,`requester`.`username` AS `receiver`,`requester`.`u_id` AS `receiver_id`,`blood_types`.`blood_name` AS `blood_group`,`request`.`blood_amount` AS `blood_amount`,`request`.`paid_amount` AS `paid_amount`,`request`.`lat_long` AS `lat_long`,`request`.`location_name` AS `location_name`,`request`.`full_address` AS `full_address`,`request`.`reason` AS `reason`,`request`.`postal_code` AS `postal_code`,`request`.`req_key` AS `request_key`,`managers`.`username` AS `verified_by`,`blood_requests_verification`.`donated_date` AS `donated_date`,`blood_requests_verification`.`verified` AS `donation_status` from (((((`blood_requests_verification` join `users` `donor` on((`donor`.`u_id` = `blood_requests_verification`.`donated_by`))) join `users` `requester` on((`requester`.`u_id` = `blood_requests_verification`.`donated_to`))) join `blood_requests` `request` on((`request`.`id` = `blood_requests_verification`.`request_id`))) join `blood_types` on((`blood_types`.`blood_id` = `request`.`blood_group`))) left join `managers` on((`managers`.`id` = `blood_requests_verification`.`manager_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_branch_requests`
--
DROP TABLE IF EXISTS `v_branch_requests`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branch_requests`  AS  select `branch_requests`.`branch_id` AS `branch_id`,`branch_requests`.`req_id` AS `branchrequest_id`,`branch`.`branch_name` AS `branch_name`,`branch`.`branch_address` AS `branch_address`,`branch`.`branch_code` AS `branch_code`,`branch`.`branch_lat_long` AS `branch_lat_long`,`blood_types`.`blood_name` AS `blood_group`,sum(`branch_requests`.`blood_amount`) AS `blood_amount`,sum(`branch_requests`.`paid_amount`) AS `paid_amount`,`branch_requests`.`created` AS `created`,`branch_requests`.`status` AS `status` from ((`branch_requests` join `branch` on((`branch`.`branch_id` = `branch_requests`.`branch_id`))) join `blood_types` on((`blood_types`.`blood_id` = `branch_requests`.`blood_group`))) where (`branch_requests`.`paid_amount` < `branch_requests`.`blood_amount`) group by `branch_requests`.`branch_id`,`branch_requests`.`created` ;

-- --------------------------------------------------------

--
-- Structure for view `v_branch_req_verification`
--
DROP TABLE IF EXISTS `v_branch_req_verification`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branch_req_verification`  AS  select `branch_requests_verification`.`donor_id` AS `donor_id`,`users`.`username` AS `donate_by`,`branch`.`branch_id` AS `branch_id`,`branch`.`branch_name` AS `branch_name`,`blood_types`.`blood_name` AS `blood_group`,`branch_requests`.`blood_amount` AS `blood_amount`,`branch_requests`.`paid_amount` AS `paid_amount`,`branch`.`branch_lat_long` AS `latlong`,`branch`.`branch_address` AS `full_address`,`branch_requests`.`req_key` AS `req_key`,`branch_requests_verification`.`donated_date` AS `donated_date`,`branch_requests_verification`.`verified` AS `verified` from ((((`branch_requests_verification` join `branch_requests` on((`branch_requests`.`req_id` = `branch_requests_verification`.`branch_requests_id`))) join `users` on((`users`.`u_id` = `branch_requests_verification`.`donor_id`))) join `branch` on((`branch`.`branch_id` = `branch_requests`.`branch_id`))) join `blood_types` on((`blood_types`.`blood_id` = `branch_requests`.`blood_group`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_campaigns`
--
DROP TABLE IF EXISTS `v_campaigns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_campaigns`  AS  select `campaigns`.`campaign_id` AS `campaign_id`,`campaigns`.`campaign_name` AS `campaign_name`,`campaigns`.`campaign_desc` AS `campaign_desc`,`campaigns`.`campaign_img` AS `campaign_img`,`campaigns`.`campaign_created` AS `campaign_created`,`campaigns`.`campaign_coordinates` AS `campaign_coordinates`,`campaigns`.`campaign_address` AS `campaign_address`,`campaigns`.`campaign_key` AS `campaign_key`,`managers`.`username` AS `campaign_creator`,(select count(0) from `campaigns_subscribed` where (`campaigns_subscribed`.`subscribed_campaign` = `campaigns`.`campaign_id`)) AS `campaign_joined`,`campaigns`.`campaign_status` AS `campaign_status` from (`campaigns` join `managers` on((`campaigns`.`campaign_creator` = `managers`.`id`))) where (`campaigns`.`campaign_status` = 1) ;

-- --------------------------------------------------------

--
-- Structure for view `v_campaigns_subscribed`
--
DROP TABLE IF EXISTS `v_campaigns_subscribed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_campaigns_subscribed`  AS  select `users`.`u_id` AS `u_id`,`users`.`username` AS `username`,`users`.`profile_token` AS `profile_token`,`campaigns`.`campaign_id` AS `campaign_id`,`campaigns`.`campaign_name` AS `campaign_name`,`campaigns`.`campaign_key` AS `campaign_key`,`campaigns_subscribed`.`subscribed_date` AS `subscribed_date` from ((`campaigns_subscribed` join `campaigns` on((`campaigns`.`campaign_id` = `campaigns_subscribed`.`subscribed_campaign`))) join `users` on((`users`.`u_id` = `campaigns_subscribed`.`subscribed_by`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_donation_reservation`
--
DROP TABLE IF EXISTS `v_donation_reservation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_donation_reservation`  AS  select `donation_day_reservation`.`reserved_id` AS `reserved_id`,`donation_day_reservation`.`user_id` AS `user_id`,`users`.`username` AS `username`,`donation_day_reservation`.`branch_id` AS `branch_id`,`branch`.`branch_name` AS `branch_name`,`donation_day_reservation`.`user_notes` AS `user_notes`,`donation_day_reservation`.`reservation_key` AS `reservation_key`,`donation_day_reservation`.`reserved_date` AS `reserved_date` from ((`donation_day_reservation` join `branch` on((`branch`.`branch_id` = `donation_day_reservation`.`branch_id`))) join `users` on((`users`.`u_id` = `donation_day_reservation`.`user_id`))) order by `donation_day_reservation`.`reserved_date` desc ;

-- --------------------------------------------------------

--
-- Structure for view `v_requested_bloodgroup_analysis`
--
DROP TABLE IF EXISTS `v_requested_bloodgroup_analysis`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_requested_bloodgroup_analysis`  AS  select `blood_types`.`blood_id` AS `blood_id`,`blood_types`.`blood_name` AS `blood_name`,sum(`blood_requests`.`blood_amount`) AS `blood_amount`,sum(`blood_requests`.`paid_amount`) AS `paid_amount`,count(`blood_requests`.`blood_group`) AS `requested_times` from (`blood_requests` join `blood_types` on((`blood_types`.`blood_id` = `blood_requests`.`blood_group`))) where (`blood_requests`.`status` between 1 and 2) group by `blood_requests`.`blood_group` ;

-- --------------------------------------------------------

--
-- Structure for view `v_request_status_analysis`
--
DROP TABLE IF EXISTS `v_request_status_analysis`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_request_status_analysis`  AS  select (case when (`blood_requests`.`status` = 0) then 'REQUESTED' when (`blood_requests`.`status` = 1) then 'APPROVED' when (`blood_requests`.`status` = 2) then 'RECEIVED' else 'UNKNOWN' end) AS `status_alias`,count(`blood_requests`.`status`) AS `status_count` from `blood_requests` group by `blood_requests`.`status` ;

-- --------------------------------------------------------

--
-- Structure for view `v_saved_bloodreq`
--
DROP TABLE IF EXISTS `v_saved_bloodreq`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_saved_bloodreq`  AS  select `users`.`u_id` AS `donor_id`,`users`.`username` AS `donor_name`,`blood_requests`.`requester_id` AS `requester_id`,`requester`.`username` AS `requester_name`,`blood_requests`.`id` AS `bloodreq_id`,`blood_types`.`blood_name` AS `blood_name`,`blood_requests`.`blood_amount` AS `blood_amount`,`blood_requests`.`paid_amount` AS `paid_amount`,`blood_requests`.`location_name` AS `location_name`,`blood_requests`.`lat_long` AS `lat_long`,`blood_requests`.`full_address` AS `full_address`,`saved_blood_requests`.`saved_date` AS `saved_date`,`blood_requests`.`req_key` AS `req_key`,`blood_requests`.`status` AS `req_status` from ((((`saved_blood_requests` join `blood_requests` on((`blood_requests`.`id` = `saved_blood_requests`.`request_id`))) join `users` on((`users`.`u_id` = `saved_blood_requests`.`saved_by`))) join `users` `requester` on((`requester`.`u_id` = `blood_requests`.`requester_id`))) join `blood_types` on((`blood_types`.`blood_id` = `blood_requests`.`blood_group`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_saved_branchreq`
--
DROP TABLE IF EXISTS `v_saved_branchreq`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_saved_branchreq`  AS  select `users`.`u_id` AS `user_id`,`users`.`username` AS `user_name`,`blood_types`.`blood_name` AS `blood_name`,`branch_requests`.`blood_amount` AS `blood_amount`,`branch_requests`.`paid_amount` AS `paid_amount`,`branch_requests`.`req_id` AS `branchreq_id`,`branch`.`branch_name` AS `branch_name`,`branch`.`branch_code` AS `branch_code`,`branch`.`branch_lat_long` AS `branch_lat_long`,`branch`.`branch_address` AS `branch_address`,`saved_branch_requests`.`saved_date` AS `saved_date`,`branch_requests`.`req_key` AS `req_key`,`branch_requests`.`status` AS `req_status` from ((((`saved_branch_requests` join `branch_requests` on((`branch_requests`.`req_id` = `saved_branch_requests`.`requests_id`))) join `branch` on((`branch`.`branch_id` = `branch_requests`.`branch_id`))) join `blood_types` on((`blood_types`.`blood_id` = `branch_requests`.`blood_group`))) join `users` on((`users`.`u_id` = `saved_branch_requests`.`saved_by`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `req_key_UNIQUE` (`req_key`),
  ADD KEY `requester_id` (`requester_id`),
  ADD KEY `fk_blood_requests_blood_types2_idx` (`blood_group`);

--
-- Indexes for table `blood_requests_verification`
--
ALTER TABLE `blood_requests_verification`
  ADD PRIMARY KEY (`donate_id`),
  ADD KEY `fk_donated_by_idx` (`donated_by`),
  ADD KEY `fk_verified_by_idx` (`manager_id`),
  ADD KEY `fk_donated_to_idx` (`donated_to`),
  ADD KEY `fk_donation_verified_blood_requests1_idx` (`request_id`);

--
-- Indexes for table `blood_types`
--
ALTER TABLE `blood_types`
  ADD PRIMARY KEY (`blood_id`),
  ADD UNIQUE KEY `blood_name_UNIQUE` (`blood_name`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branch_id`),
  ADD UNIQUE KEY `branch_key_UNIQUE` (`branch_code`);

--
-- Indexes for table `branch_requests`
--
ALTER TABLE `branch_requests`
  ADD PRIMARY KEY (`req_id`),
  ADD UNIQUE KEY `req_key_UNIQUE` (`req_key`),
  ADD KEY `fk_branch_requests_branch1_idx` (`branch_id`),
  ADD KEY `fk_branch_requests_blood_types1_idx` (`blood_group`);

--
-- Indexes for table `branch_requests_verification`
--
ALTER TABLE `branch_requests_verification`
  ADD PRIMARY KEY (`donate_id`),
  ADD KEY `fk_branch_requests_verification_branch_requests1_idx` (`branch_requests_id`),
  ADD KEY `fk_branch_requests_verification_users1_idx` (`donor_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`),
  ADD UNIQUE KEY `campaign_key_UNIQUE` (`campaign_key`),
  ADD KEY `fk_campaigns_staff_idx` (`campaign_creator`);

--
-- Indexes for table `campaigns_subscribed`
--
ALTER TABLE `campaigns_subscribed`
  ADD PRIMARY KEY (`subscribe_id`),
  ADD KEY `fk_campaigns_subscribed_campaigns1_idx` (`subscribed_campaign`),
  ADD KEY `fk_campaigns_subscribed_users1_idx` (`subscribed_by`);

--
-- Indexes for table `donation_day_reservation`
--
ALTER TABLE `donation_day_reservation`
  ADD PRIMARY KEY (`reserved_id`),
  ADD UNIQUE KEY `reservation_key_UNIQUE` (`reservation_key`),
  ADD KEY `fk_day_reservation_users1_idx` (`user_id`),
  ADD KEY `fk_donation_day_reservation_branch1_idx` (`branch_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `manager_key_UNIQUE` (`manager_key`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `fk_staff_branch1_idx` (`worked_at`);

--
-- Indexes for table `saved_blood_requests`
--
ALTER TABLE `saved_blood_requests`
  ADD PRIMARY KEY (`saved_id`),
  ADD KEY `saved_req_idx` (`request_id`),
  ADD KEY `saved_by_idx` (`saved_by`);

--
-- Indexes for table `saved_branch_requests`
--
ALTER TABLE `saved_branch_requests`
  ADD PRIMARY KEY (`saved_id`),
  ADD KEY `fk_saved_branch_requests_branch_requests1_idx` (`requests_id`),
  ADD KEY `fk_saved_branch_requests_users1_idx` (`saved_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `profile_phone_UNIQUE` (`phone`),
  ADD KEY `fk_users_blood_types1_idx` (`blood_group`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `blood_requests_verification`
--
ALTER TABLE `blood_requests_verification`
  MODIFY `donate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blood_types`
--
ALTER TABLE `blood_types`
  MODIFY `blood_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branch_requests`
--
ALTER TABLE `branch_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `branch_requests_verification`
--
ALTER TABLE `branch_requests_verification`
  MODIFY `donate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `campaigns_subscribed`
--
ALTER TABLE `campaigns_subscribed`
  MODIFY `subscribe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donation_day_reservation`
--
ALTER TABLE `donation_day_reservation`
  MODIFY `reserved_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `saved_blood_requests`
--
ALTER TABLE `saved_blood_requests`
  MODIFY `saved_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `saved_branch_requests`
--
ALTER TABLE `saved_branch_requests`
  MODIFY `saved_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `fk_blood_requests_blood_types2` FOREIGN KEY (`blood_group`) REFERENCES `blood_types` (`blood_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_requester` FOREIGN KEY (`requester_id`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `blood_requests_verification`
--
ALTER TABLE `blood_requests_verification`
  ADD CONSTRAINT `fk_donated_by` FOREIGN KEY (`donated_by`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_donated_to` FOREIGN KEY (`donated_to`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_donation_verified_blood_requests1` FOREIGN KEY (`request_id`) REFERENCES `blood_requests` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_verified_by` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `branch_requests`
--
ALTER TABLE `branch_requests`
  ADD CONSTRAINT `fk_branch_requests_blood_types1` FOREIGN KEY (`blood_group`) REFERENCES `blood_types` (`blood_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_branch_requests_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `branch_requests_verification`
--
ALTER TABLE `branch_requests_verification`
  ADD CONSTRAINT `fk_branch_requests_verification_branch_requests1` FOREIGN KEY (`branch_requests_id`) REFERENCES `branch_requests` (`req_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_branch_requests_verification_users1` FOREIGN KEY (`donor_id`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `fk_campaigns_staff` FOREIGN KEY (`campaign_creator`) REFERENCES `managers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `campaigns_subscribed`
--
ALTER TABLE `campaigns_subscribed`
  ADD CONSTRAINT `fk_campaigns_subscribed_campaigns1` FOREIGN KEY (`subscribed_campaign`) REFERENCES `campaigns` (`campaign_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_campaigns_subscribed_users1` FOREIGN KEY (`subscribed_by`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `donation_day_reservation`
--
ALTER TABLE `donation_day_reservation`
  ADD CONSTRAINT `fk_day_reservation_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_donation_day_reservation_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `fk_staff_branch1` FOREIGN KEY (`worked_at`) REFERENCES `branch` (`branch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `saved_blood_requests`
--
ALTER TABLE `saved_blood_requests`
  ADD CONSTRAINT `saved_by` FOREIGN KEY (`saved_by`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `saved_req` FOREIGN KEY (`request_id`) REFERENCES `blood_requests` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `saved_branch_requests`
--
ALTER TABLE `saved_branch_requests`
  ADD CONSTRAINT `fk_saved_branch_requests_branch_requests1` FOREIGN KEY (`requests_id`) REFERENCES `branch_requests` (`req_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_saved_branch_requests_users1` FOREIGN KEY (`saved_by`) REFERENCES `users` (`u_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_blood_types1` FOREIGN KEY (`blood_group`) REFERENCES `blood_types` (`blood_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
