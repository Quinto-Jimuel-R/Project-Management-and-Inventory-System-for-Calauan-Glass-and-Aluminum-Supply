-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table project_management.inventory
CREATE TABLE IF NOT EXISTS `inventory` (
  `item_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dimension` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foot` int DEFAULT NULL,
  `sqft` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` int NOT NULL,
  `supplier_id` bigint NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.inventory: ~6 rows (approximately)
INSERT INTO `inventory` (`item_id`, `item_name`, `dimension`, `foot`, `sqft`, `color`, `price`, `stock`, `category`, `active`, `supplier_id`) VALUES
	(1, 'Head', '1 x 4', 21, NULL, 'HA', 10, 5, 'Aluminum', 1, 1),
	(2, 'Tempered', '1 x 4', NULL, '20', 'Tempered', 60, 12, 'Glass', 1, 1),
	(3, 'Sill', '1 x 4', 21, NULL, 'HA', 5, 5, 'Aluminum', 1, 1),
	(4, 'Rails', '1 x 4', 21, NULL, 'HA', 5, 8, 'Aluminum', 1, 1),
	(5, 'Jamb', '1 x 4', 21, NULL, 'HA', 5, 7, 'Aluminum', 1, 1),
	(6, 'Stiles', '1 x 4', 21, NULL, 'HA', 5, 8, 'Aluminum', 1, 1),
	(7, 'Interlocker', '1 x 4', 21, NULL, 'A', 5, 8, 'Aluminum', 1, 1);

-- Dumping structure for table project_management.inventory_excess
CREATE TABLE IF NOT EXISTS `inventory_excess` (
  `exc_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `exc_foot` int DEFAULT NULL,
  `dimension` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`exc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.inventory_excess: ~6 rows (approximately)
INSERT INTO `inventory_excess` (`exc_id`, `item_name`, `exc_foot`, `dimension`, `color`) VALUES
	(1, 'Head', 19, '1 x 4', 'HA'),
	(2, 'Sill', 19, '1 x 4', 'HA'),
	(3, 'Rails', 17, '1 x 4', 'HA'),
	(4, 'Jamb', 17, '1 x 4', 'HA'),
	(5, 'Stiles', 17, '1 x 4', 'HA'),
	(6, 'Interlocker', 17, '1 x 4', 'HA');

-- Dumping structure for table project_management.inv_mon
CREATE TABLE IF NOT EXISTS `inv_mon` (
  `inv_mon_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  PRIMARY KEY (`inv_mon_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.inv_mon: ~15 rows (approximately)
INSERT INTO `inv_mon` (`inv_mon_id`, `item_id`, `date_created`) VALUES
	(1, 1, '2017-01-27'),
	(2, 1, '2024-01-28'),
	(3, 1, '2024-01-28'),
	(4, 2, '2024-01-29'),
	(5, 2, '2024-01-29'),
	(6, 2, '2024-01-29'),
	(7, 2, '2024-01-01'),
	(8, 2, '2024-01-02'),
	(9, 2, '2024-01-02'),
	(10, 2, '2024-01-02'),
	(11, 1, '2025-02-01'),
	(12, 3, '2024-03-01'),
	(13, 3, '2024-03-01'),
	(14, 3, '2024-03-01'),
	(15, 3, '2024-04-01'),
	(16, 3, '2025-12-01');

-- Dumping structure for table project_management.log
CREATE TABLE IF NOT EXISTS `log` (
  `log_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.log: ~112 rows (approximately)
INSERT INTO `log` (`log_id`, `description`, `date`, `time`, `user_id`) VALUES
	(1, 'Login', '2024-12-12', '09:29:20', 4),
	(2, 'Login', '2024-12-12', '09:30:05', 1),
	(3, 'Viewed archive', '2024-12-12', '09:33:26', 1),
	(4, 'Viewed employee', '2024-12-12', '09:33:31', 1),
	(5, 'Viewed supplier', '2024-12-12', '09:33:33', 1),
	(6, 'Viewed customer', '2024-12-12', '09:33:34', 1),
	(7, 'Logout', '2024-12-12', '09:33:40', 1),
	(8, 'Login', '2024-12-12', '09:34:31', 4),
	(9, 'Login', '2024-12-15', '22:20:28', 1),
	(10, 'Viewed project', '2024-12-15', '22:20:37', 1),
	(11, 'Viewed inventory', '2024-12-15', '22:20:38', 1),
	(12, 'Viewed project', '2024-12-15', '22:20:41', 1),
	(13, 'Viewed inventory', '2024-12-15', '23:12:33', 1),
	(14, 'Viewed project', '2024-12-15', '23:12:39', 1),
	(15, 'Viewed dashboard', '2024-12-15', '23:17:38', 1),
	(16, 'Viewed project', '2024-12-15', '23:17:54', 1),
	(17, 'Viewed inventory', '2024-12-15', '23:17:57', 1),
	(18, 'Viewed customer', '2024-12-15', '23:17:58', 1),
	(19, 'Viewed inventory', '2024-12-15', '23:18:00', 1),
	(20, 'Viewed project', '2024-12-15', '23:25:04', 1),
	(21, 'Viewed project', '2024-12-15', '23:29:05', 1),
	(22, 'Logout', '2024-12-15', '23:38:29', 1),
	(23, 'Login', '2024-12-16', '00:10:45', 2),
	(24, 'Viewed project', '2024-12-16', '00:10:48', 2),
	(25, 'Login', '2025-01-20', '00:49:18', 2),
	(26, 'Viewed project', '2025-01-20', '00:49:22', 2),
	(27, 'Viewed project', '2025-01-20', '00:49:24', 2),
	(28, 'Viewed dashboard', '2025-01-20', '01:00:49', 2),
	(29, 'Viewed project', '2025-01-20', '01:07:11', 2),
	(30, 'Viewed dashboard', '2025-01-20', '01:07:14', 2),
	(31, 'Viewed project', '2025-01-20', '01:19:33', 2),
	(32, 'Viewed dashboard', '2025-01-20', '01:20:34', 2),
	(33, 'Viewed project', '2025-01-20', '02:38:23', 2),
	(34, 'Viewed dashboard', '2025-01-20', '02:38:29', 2),
	(35, 'Viewed project', '2025-01-20', '03:01:30', 2),
	(36, 'Viewed dashboard', '2025-01-20', '03:01:31', 2),
	(37, 'Viewed project', '2025-01-20', '03:07:54', 2),
	(38, 'Viewed dashboard', '2025-01-20', '03:13:21', 2),
	(39, 'Viewed dashboard', '2025-01-20', '03:35:22', 2),
	(40, 'Viewed project', '2025-01-20', '03:45:38', 2),
	(41, 'Viewed dashboard', '2025-01-20', '03:45:44', 2),
	(42, 'Viewed project', '2025-01-20', '03:46:03', 2),
	(43, 'Viewed dashboard', '2025-01-20', '03:46:45', 2),
	(44, 'Viewed project', '2025-01-20', '03:47:02', 2),
	(45, 'Viewed dashboard', '2025-01-20', '03:47:38', 2),
	(46, 'Viewed project', '2025-01-20', '03:54:49', 2),
	(47, 'Viewed dashboard', '2025-01-20', '03:54:50', 2),
	(48, 'Viewed project', '2025-01-20', '03:54:56', 2),
	(49, 'Viewed dashboard', '2025-01-20', '03:55:15', 2),
	(50, 'Viewed project', '2025-01-20', '10:38:24', 2),
	(51, 'Viewed dashboard', '2025-01-20', '10:38:37', 2),
	(52, 'Viewed project', '2025-01-20', '10:40:43', 2),
	(53, 'Viewed dashboard', '2025-01-20', '10:40:45', 2),
	(54, 'Viewed project', '2025-01-20', '10:44:08', 2),
	(55, 'Viewed dashboard', '2025-01-20', '10:44:27', 2),
	(56, 'Viewed project', '2025-01-20', '10:44:45', 2),
	(57, 'Viewed dashboard', '2025-01-20', '10:44:48', 2),
	(58, 'Viewed project', '2025-01-20', '10:44:51', 2),
	(59, 'Viewed dashboard', '2025-01-20', '10:44:52', 2),
	(60, 'Viewed project', '2025-01-20', '10:46:38', 2),
	(61, 'Viewed dashboard', '2025-01-20', '10:46:46', 2),
	(62, 'Viewed project', '2025-01-20', '10:47:19', 2),
	(63, 'Viewed dashboard', '2025-01-20', '10:47:40', 2),
	(64, 'Viewed project', '2025-01-20', '10:47:42', 2),
	(65, 'Viewed dashboard', '2025-01-20', '10:47:43', 2),
	(66, 'Viewed project', '2025-01-20', '10:48:14', 2),
	(67, 'Viewed dashboard', '2025-01-20', '10:48:15', 2),
	(68, 'Viewed project', '2025-01-20', '11:06:49', 2),
	(69, 'Viewed dashboard', '2025-01-20', '11:06:53', 2),
	(70, 'Viewed manage account', '2025-01-20', '11:07:11', 2),
	(71, 'Viewed dashboard', '2025-01-20', '11:07:14', 2),
	(72, 'Login', '2025-01-20', '13:11:24', 2),
	(73, 'Viewed project', '2025-01-21', '01:27:38', 2),
	(74, 'Viewed project', '2025-01-21', '01:27:44', 2),
	(75, 'Viewed dashboard', '2025-01-21', '01:27:45', 2),
	(76, 'Logout', '2025-01-21', '01:34:35', 2),
	(77, 'Login', '2025-01-21', '01:34:44', 1),
	(78, 'Viewed project', '2025-01-21', '01:35:11', 1),
	(79, 'Viewed project', '2025-01-21', '02:52:32', 1),
	(80, 'Viewed project', '2025-01-21', '04:18:37', 1),
	(81, 'Viewed project', '2025-01-21', '04:34:00', 1),
	(82, 'Viewed dashboard', '2025-01-21', '22:49:45', 1),
	(83, 'Viewed project', '2025-01-21', '22:49:56', 1),
	(84, 'Viewed inventory', '2025-01-21', '22:50:09', 1),
	(85, 'Viewed project', '2025-01-21', '22:50:11', 1),
	(86, 'Viewed dashboard', '2025-01-21', '23:04:56', 1),
	(87, 'Viewed project', '2025-01-21', '23:04:59', 1),
	(88, 'Viewed project', '2025-01-21', '23:05:10', 1),
	(89, 'Viewed project', '2025-01-21', '23:09:10', 1),
	(90, 'Login', '2025-01-22', '21:46:46', 2),
	(91, 'Viewed project', '2025-01-22', '21:47:03', 2),
	(92, 'Viewed dashboard', '2025-01-22', '21:47:03', 2),
	(93, 'Logout', '2025-01-23', '08:25:05', 2),
	(94, 'Login', '2025-01-23', '08:25:12', 1),
	(95, 'Viewed project', '2025-01-23', '08:25:15', 1),
	(96, 'Added new project \'21\'', '2025-01-23', '08:29:47', 1),
	(97, 'Login', '2025-01-27', '07:40:27', 1),
	(98, 'Viewed project', '2025-01-27', '07:40:33', 1),
	(99, 'Viewed inventory', '2025-01-27', '07:40:35', 1),
	(100, 'Viewed dashboard', '2025-01-27', '07:50:17', 1),
	(101, 'Viewed inventory', '2025-01-27', '07:55:08', 1),
	(102, 'Viewed inventory', '2025-01-28', '11:55:00', 1),
	(103, 'Viewed customer', '2025-01-28', '11:55:43', 1),
	(104, 'Viewed project', '2025-01-28', '11:55:47', 1),
	(105, 'Viewed dashboard', '2025-01-28', '11:55:53', 1),
	(106, 'Viewed project', '2025-01-28', '11:55:58', 1),
	(107, 'Viewed dashboard', '2025-01-28', '12:00:34', 1),
	(108, 'Viewed inventory', '2025-01-28', '12:09:49', 1),
	(109, 'Viewed dashboard', '2025-01-28', '12:10:04', 1),
	(110, 'Viewed inventory', '2025-01-28', '12:10:11', 1),
	(111, 'Viewed dashboard', '2025-01-28', '12:21:50', 1),
	(112, 'Viewed project', '2025-01-28', '21:09:50', 1),
	(113, 'Viewed inventory', '2025-01-28', '21:09:51', 1),
	(114, 'Viewed inventory', '2025-01-28', '21:09:53', 1),
	(115, 'Viewed dashboard', '2025-01-29', '00:23:48', 1),
	(116, 'Login', '2025-01-29', '17:36:09', 1),
	(117, 'Viewed inventory', '2025-01-29', '22:07:37', 1),
	(118, 'Viewed dashboard', '2025-01-29', '22:54:40', 1),
	(119, 'Logout', '2025-01-30', '02:52:42', 1),
	(120, 'Login', '2025-01-30', '02:52:58', 2),
	(121, 'Logout', '2025-01-30', '03:06:40', 2),
	(122, 'Login', '2025-01-30', '03:06:49', 1),
	(123, 'Viewed project', '2025-01-30', '03:06:51', 1),
	(124, 'Viewed dashboard', '2025-01-30', '03:21:24', 1),
	(125, 'Login', '2025-01-30', '09:20:06', 1),
	(126, 'Viewed project', '2025-01-30', '09:20:12', 1),
	(127, 'Viewed project', '2025-01-30', '09:20:12', 1),
	(128, 'Login', '2025-01-31', '00:14:19', 1),
	(129, 'Login', '2025-01-31', '00:36:02', 1),
	(130, 'Login', '2025-01-31', '00:37:46', 1),
	(131, 'Login', '2025-01-31', '07:58:02', 1),
	(132, 'Viewed project', '2025-01-31', '07:58:06', 1),
	(133, 'Viewed dashboard', '2025-01-31', '07:58:06', 1),
	(134, 'Viewed project', '2025-01-31', '07:58:06', 1),
	(135, 'Viewed dashboard', '2025-01-31', '07:58:08', 1),
	(136, 'Login', '2025-02-05', '00:23:27', 1),
	(137, 'Viewed project', '2025-02-05', '00:23:38', 1),
	(138, 'Viewed dashboard', '2025-02-05', '00:23:42', 1),
	(139, 'Login', '2025-02-05', '00:23:51', 1);

-- Dumping structure for table project_management.message
CREATE TABLE IF NOT EXISTS `message` (
  `message_id` bigint NOT NULL AUTO_INCREMENT,
  `message` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `from` bigint DEFAULT NULL,
  `to` bigint DEFAULT NULL,
  `project_id` bigint DEFAULT NULL,
  `sent_date` timestamp NULL DEFAULT NULL,
  `is_read` int DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.message: ~9 rows (approximately)
INSERT INTO `message` (`message_id`, `message`, `from`, `to`, `project_id`, `sent_date`, `is_read`) VALUES
	(1, 'Dear customer your project is COMPLETED. Thank you for trusting Calauan Glass and Aluminum Supply and Services. God Bless! 😇', 2, 3, 1, '2024-11-11 02:59:16', 1),
	(2, '🤭', 3, 2, 1, '2024-11-11 03:02:47', 1),
	(3, 'Dear customer your project is COMPLETED. Thank you for trusting Calauan Glass and Aluminum Supply and Services. God Bless! 😇', 2, 3, 1, '2024-11-21 07:45:33', 1),
	(4, 'mwah', 2, 3, 1, '2024-12-06 06:08:49', 1),
	(5, 'hello', 3, 2, 1, '2024-12-06 06:10:26', 0),
	(6, 'Dear customer your project is currently in DELIVERED. Expect the installation today and please prepare the exact amount of your remaining balance.', 2, 3, 6, '2024-12-06 08:20:45', 1),
	(7, 'Dear customer your project is currently in IN PROGRESS.', 2, 3, 9, '2024-12-06 08:21:12', 1),
	(8, 'Dear customer your order is being prepared for delivery.', 2, 3, 9, '2024-12-06 08:22:08', 1),
	(9, 'Dear customer your project is currently in DELIVERED. Expect the installation today and please prepare the exact amount of your remaining balance.', 2, 3, 9, '2024-12-06 08:23:49', 1),
	(10, '👍️', 3, 2, 9, '2024-12-06 08:25:52', 1);

-- Dumping structure for table project_management.payment
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint NOT NULL,
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `down_payment` decimal(10,2) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.payment: ~11 rows (approximately)
INSERT INTO `payment` (`payment_id`, `project_id`, `payment_status`, `down_payment`, `payment`) VALUES
	(1, 1, 'PAID', NULL, NULL),
	(2, 2, 'PAID', NULL, NULL),
	(3, 3, 'NOT PAID', NULL, NULL),
	(4, 4, 'NOT PAID', NULL, NULL),
	(5, 5, 'PAID', NULL, NULL),
	(6, 6, 'NOT PAID', NULL, NULL),
	(7, 7, 'NOT PAID', NULL, NULL),
	(8, 8, 'NOT PAID', NULL, NULL),
	(9, 9, 'NOT PAID', NULL, NULL),
	(10, 10, 'NOT PAID', NULL, NULL),
	(11, 11, 'NOT PAID', NULL, NULL),
	(12, 16, NULL, NULL, NULL);

-- Dumping structure for table project_management.project
CREATE TABLE IF NOT EXISTS `project` (
  `project_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `end_date` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_id` bigint NOT NULL,
  `employee_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` int NOT NULL,
  `date_created` date DEFAULT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.project: ~15 rows (approximately)
INSERT INTO `project` (`project_id`, `project_name`, `location`, `start_date`, `end_date`, `status`, `customer_id`, `employee_name`, `active`, `date_created`) VALUES
	(1, 'Calimutan House', 'Sto Tomas Calauan Laguna', '2019-10-31', '2019-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2020-10-31'),
	(2, 'Abrena House', 'Sto Tomas Calauan Laguna', '2019-10-31', '2019-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2020-10-31'),
	(3, 'Borja House', 'Sto Tomas Calauan Laguna', '2020-10-31', '2020-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2020-10-31'),
	(4, 'Rocero House', 'Sto Tomas Calauan Laguna', '2020-10-31', '2020-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2020-10-31'),
	(5, 'Lubbui House', 'Sto Tomas Calauan Laguna', '2020-10-31', '2020-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2020-10-31'),
	(6, 'Suarez House', 'Sto Tomas Calauan Laguna', '2021-10-31', '2021-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2020-10-31'),
	(7, 'Marasigan House', 'Sto Tomas Calauan Laguna', '2021-10-31', '2021-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2021-10-31'),
	(8, 'Dalit House', 'Sto Tomas Calauan Laguna', '2022-10-31', '2022-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2022-10-31'),
	(9, 'Magbag House', 'Sto Tomas Calauan Laguna', '2022-10-31', '2022-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2022-10-31'),
	(10, 'Amoranto House', 'Sto Tomas Calauan Laguna', '2023-10-31', '2023-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2023-10-31'),
	(11, 'Gonzales House', 'Sto Tomas Calauan Laguna', '2024-10-31', '2024-11-07', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2023-10-31'),
	(12, 'Andalio House', 'Sto Tomas Calauan Laguna', '2024-10-31', '2024-11-07', 'TO DO', 3, 'Jimuel Quinto', 1, '2024-10-31'),
	(13, 'Cruz House', 'Sto Tomas Calauan Laguna', '2024-12-19', '2025-01-04', 'COMPLETED', 3, 'Jimuel Quinto', 1, '2024-10-31'),
	(14, 'Aligado House', 'Sto Tomas Calauan Laguna', '2025-01-18', '2025-01-25', 'IN PROGRESS', 3, 'Jimuel Quinto', 1, '2024-10-31'),
	(15, 'Andres House', 'Sto Tomas Calauan Laguna', '2025-01-26', '2025-01-31', 'TO DO', 3, 'Jimuel Quinto', 1, '2024-10-31'),
	(16, '21', '32', '2025-01-23', '2025-01-24', 'TO DO', 6, 'Jonathan Rocero', 1, '2025-01-23');

-- Dumping structure for table project_management.project_history
CREATE TABLE IF NOT EXISTS `project_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  `time_updated` time DEFAULT NULL,
  `day_updated` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.project_history: ~4 rows (approximately)
INSERT INTO `project_history` (`id`, `project_id`, `status`, `date_updated`, `time_updated`, `day_updated`) VALUES
	(1, 1, 'COMPLETED', '2024-11-11', '10:59:16', 'Monday'),
	(2, 1, 'COMPLETED', '2024-11-21', '15:45:33', 'Thursday'),
	(3, 6, 'DELIVERED', '2024-12-06', '16:20:45', 'Friday'),
	(4, 9, 'IN PROGRESS', '2024-12-06', '16:21:12', 'Friday'),
	(5, 9, 'DELIVERED', '2024-12-06', '16:23:49', 'Friday');

-- Dumping structure for table project_management.status
CREATE TABLE IF NOT EXISTS `status` (
  `status_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.status: ~5 rows (approximately)
INSERT INTO `status` (`status_id`, `status_name`, `description`, `color`) VALUES
	(1, 'TO DO', NULL, '#9c9c9c'),
	(2, 'IN PROGRESS', NULL, '#f4f739'),
	(3, 'DELIVERED', NULL, '#f28f1d'),
	(4, 'INSTALLATION', NULL, '#9f05ff'),
	(5, 'COMPLETED', NULL, '#33ff05');

-- Dumping structure for table project_management.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `supplier_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_person` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` bigint NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.supplier: ~0 rows (approximately)
INSERT INTO `supplier` (`supplier_id`, `company_name`, `contact_person`, `address`, `phone_number`, `active`) VALUES
	(1, 'Frozen Company', 'Michael', 'Los Banos Laguna', '09917530641', 1);

-- Dumping structure for table project_management.task
CREATE TABLE IF NOT EXISTS `task` (
  `task_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `length` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `items` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `project_id` bigint NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.task: ~15 rows (approximately)
INSERT INTO `task` (`task_id`, `description`, `length`, `height`, `status`, `items`, `total_price`, `project_id`) VALUES
	(1, 'Awning', 22, 33, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 2100.00, 1),
	(2, 'Awning', 22, 33, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 2100.00, 1),
	(3, 'Fixed', 15, 33, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 7100.00, 2),
	(4, 'Casement', 15, 33, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 4100.00, 5),
	(8, 'Fixed', 15, 33, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 4100.00, 8),
	(9, 'Casement', 32, 32, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 1215.00, 7),
	(10, 'Awning', 32, 32, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Jamb - 6 feet of Jamb|1 x 4|HA', 9215.00, 11),
	(11, 'Two Panel Slider', 33, 22, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 9),
	(12, 'Awning', 33, 22, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 3),
	(13, 'Two Panel Slider', 33, 22, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 4),
	(14, 'Two Panel Slider', 33, 22, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 6),
	(15, 'Awning', 33, 22, 'COMPLETED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 14),
	(16, 'Two Panel Slider', 33, 22, 'NOT STARTED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 12),
	(17, 'Two Panel Slider', 33, 22, 'NOT STARTED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 13),
	(18, 'Two Panel Slider', 33, 22, 'NOT STARTED', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 14),
	(19, 'Two Panel Slider', 33, 40, 'ONGOING', 'Head - 3 feet of Head|1 x 4|HA, Sill - 3 feet of Sill|1 x 4|HA, Rails - 6 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 1170.00, 14),
	(20, 'Two Panel Slider', 23, 23, 'NOT STARTED', 'Head - 2 feet of Head|1 x 4|HA, Sill - 2 feet of Sill|1 x 4|HA, Rails - 4 feet of Rails|1 x 4|HA, Jamb - 4 feet of Jamb|1 x 4|HA, Stiles - 4 feet of Stiles|1 x 4|HA, Interlocker - 4 feet of Interlocker|1 x 4|HA', 680.00, 16);

-- Dumping structure for table project_management.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `otp` int DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table project_management.user: ~2 rows (approximately)
INSERT INTO `user` (`user_id`, `name`, `address`, `phone_number`, `email`, `password`, `image`, `user_type`, `otp`) VALUES
	(1, 'admin', 'admin', '09917530641', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'dark.jpg', 'admin', 6799),
	(2, 'Jimuel Quinto', 'Brgy Bangyas Calauan', '09917530641', 'jimuelquinto1@gmail.com', '8a6ec0ea3a19e75020d79132e5d7560d', NULL, 'employee', NULL),
	(3, 'Trixie Lou Calimutan', 'Sto Tomas Calauan Laguna', '09917530641', 'sisi@gmail.com', '28a296a840913b08fb56ab36a3cc7006', NULL, 'customer', 6799),
	(4, 'Krizzia', 'Masapang', '09545456546', 'krizzia@gmail.com', 'b77eeb862a66962b01e8ccdd6e51c315', NULL, 'cashier', NULL),
	(5, 'Jonathan Rocero', 'Brgy Bangyas Calauan', '09437851654', 'jonathan4@gmail.com', '88dafffd9a2dbc188828457d58170117', NULL, 'employee', NULL),
	(6, 'haha', 'haha', '34243254656', 'ha@gmail.com', '6b7661bf1b2f463e984927960210d2e9', NULL, 'customer', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
