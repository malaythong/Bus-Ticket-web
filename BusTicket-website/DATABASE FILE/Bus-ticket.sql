-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 30, 2022 at 04:00 PM
-- Server version: 8.0.28
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `train`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `customer_route` varchar(200) NOT NULL,
  `booked_amount` int NOT NULL,
  `booked_seat` varchar(100) NOT NULL,
  `booking_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`) VALUES
(68, 'EEOTMIE', 'CUST-8996235', 'RT-ນວຫຼບ02082022', 'ນະຄອນຫຼວງວຽງຈັນ,ຫຼວງພະບາງ', 200000, '3', '2021-10-16 22:15:13'),
(69, 'QK0MT61', 'CUST-2017936', 'RT-ນວຫຼບ02082022', 'ນະຄອນຫຼວງວຽງຈັນ,ຫຼວງພະບາງ', 200000, '15', '2021-10-17 22:36:10'),
(70, 'QLOE167', 'CUST-9997540', 'RT-ນວວຈ02082022', 'ນະຄອນຫຼວງວຽງຈັນ,ວຽງຈັນ', 100000, '12', '2021-10-18 09:41:01');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

DROP TABLE IF EXISTS `buses`;
CREATE TABLE IF NOT EXISTS `buses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bus_no` varchar(255) NOT NULL,
  `bus_assigned` tinyint(1) NOT NULL DEFAULT '0',
  `bus_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bus_no`, `bus_assigned`, `bus_created`) VALUES
(10, 'ທນ 2568', 0, '2022-07-30 12:48:56'),
(11, 'ທນ 5588', 0, '2022-07-30 12:56:03'),
(12, 'ກກ 1122', 0, '2022-07-30 12:56:28'),
(13, 'ມວ 6647', 0, '2022-07-30 12:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(255) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `customer_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `customer_name`, `customer_phone`, `customer_created`) VALUES
(35, 'CUST-8996235', 'ຈັນດີ', '02055667785', '2021-10-17 22:30:23'),
(36, 'CUST-2017936', 'ລາວຄຳ', '02055789156', '2021-10-17 22:30:53'),
(40, 'CUST-9997540', 'ວຽງ', '02055997586', '2021-10-18 09:39:10'),
(41, 'CUST-1122334', 'ບຸນມີ ຄຳໃສ', '02055443688', '2022-10-16 22:09:12'),
(42, 'CUST-1122335', 'ທອງຄຳ ແກ້ວດີ', '02077841689', '2022-10-17 22:30:23'),
(43, 'CUST-1122336', 'ດອກຄູນ ມາສີ', '02022556874', '2022-10-17 22:30:53'),
(44, 'CUST-1122337', 'ຈັນດີ ສີສັນ', '02055478955', '2022-10-17 22:31:20'),
(45, 'CUST-1122338', 'ລາວຄຳ ພາສີ', '02066587233', '2022-10-18 09:32:02'),
(46, 'CUST-1122339', 'ເຈ້ ທິບ', '02022647859', '2022-10-18 09:33:08'),
(47, 'CUST-1122340', 'ສາວພອນ', '02099845716', '2022-10-18 09:39:10');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `route_id` varchar(255) NOT NULL,
  `bus_no` varchar(155) NOT NULL,
  `route_cities` varchar(255) NOT NULL,
  `route_dep_date` date NOT NULL,
  `route_dep_time` time NOT NULL,
  `route_step_cost` int NOT NULL,
  `route_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route_id`, `bus_no`, `route_cities`, `route_dep_date`, `route_dep_time`, `route_step_cost`, `route_created`) VALUES
(1, 'RT-ປຊນວ01082022', 'ມວ 6647', 'ປາກເຊ-ນະຄອນຫຼວງວຽງຈັນ', '2022-08-01', '08:05:00', 170000, '2022-08-16 22:05:42'),
(2, 'RT-ນວວຈ02082022', 'ທນ 5588', 'ນະຄອນຫຼວງວຽງຈັນ,ວຽງຈັນ', '2022-08-02', '10:00:00', 100000, '2022-08-16 22:12:32'),
(3, 'RT-ນວຫຼບ02082022', 'ທນ 2568', 'ນະຄອນຫຼວງວຽງຈັນ,ຫຼວງພະບາງ', '2022-08-02', '12:30:00', 200000, '2022-08-17 22:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
CREATE TABLE IF NOT EXISTS `seats` (
  `bus_no` varchar(155) NOT NULL,
  `seat_booked` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bus_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`bus_no`, `seat_booked`) VALUES
('ກກ 1122', NULL),
('ທນ 2568', '15,3'),
('ທນ 5588', '12'),
('ມວ 6647', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(100) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fullname`, `user_name`, `user_password`, `user_created`) VALUES
(3, 'malaythong keophongsay', 'nangnoy', '$2y$10$TXq6PQcNWEz5GKIv8/OHeOOpMvYi7scdq/Rv1eTka2V8Iph/J/J52', '2022-07-30 10:18:04'),
(4, 'vieng thepphavong', 'vieng', '$2y$10$FI7GCoivHN0x68HDqdCEi..TmCMkLvWCe9HMsQgRgkKgr5gH9L7X.', '2022-07-30 22:48:54'),
(6, 'admin 3CS1', 'admin', '$2y$10$Io4D0cawENq.mhmfx8N2lendaOSkYxf3jnHVb64us88f9uSa2lfbq', '2022-07-30 22:58:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
