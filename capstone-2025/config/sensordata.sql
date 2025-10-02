-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2025 at 08:49 AM
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
-- Database: `esp_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `sensordata`
--

CREATE TABLE `sensordata` (
  `id` int(6) UNSIGNED NOT NULL,
  `sensor` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL,
  `value1` varchar(10) DEFAULT NULL,
  `value2` varchar(10) DEFAULT NULL,
  `value3` varchar(10) DEFAULT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensordata`
--

INSERT INTO `sensordata` (`id`, `sensor`, `location`, `value1`, `value2`, `value3`, `reading_time`) VALUES
(1, 'DHT11', 'Lab', '29.20', '79.10', '0', '2025-09-30 08:26:47'),
(2, 'DHT11', 'Lab', '29.20', '78.90', '0', '2025-09-30 08:27:17'),
(3, 'DHT11', 'Lab', '29.20', '78.80', '0', '2025-09-30 08:27:47'),
(4, 'DHT11', 'Lab', '29.20', '78.70', '0', '2025-09-30 08:28:17'),
(5, 'DHT11', 'Lab', '30.40', '77.40', '0', '2025-09-30 08:28:48'),
(6, 'DHT11', 'Lab', '30.50', '74.40', '0', '2025-09-30 08:29:18'),
(7, 'DHT11', 'Lab', '30.00', '74.60', '0', '2025-09-30 08:29:50'),
(8, 'DHT11', 'Lab', '29.90', '75.50', '0', '2025-09-30 08:30:29'),
(9, 'DHT11', 'Lab', '29.70', '76.30', '0', '2025-09-30 08:30:34'),
(10, 'DHT11', 'Lab', '29.70', '76.40', '0', '2025-09-30 08:30:39'),
(11, 'DHT11', 'Lab', '29.90', '76.60', '0', '2025-09-30 08:30:45'),
(12, 'DHT11', 'Lab', '30.30', '76.80', '0', '2025-09-30 08:30:50'),
(13, 'DHT11', 'Lab', '30.90', '76.20', '0', '2025-09-30 08:30:55'),
(14, 'DHT11', 'Lab', '31.20', '75.50', '0', '2025-09-30 08:31:00'),
(15, 'DHT11', 'Lab', '31.30', '74.40', '0', '2025-09-30 08:31:05'),
(16, 'DHT11', 'Lab', '31.40', '73.50', '0', '2025-09-30 08:31:10'),
(17, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:09'),
(18, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:15'),
(19, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:20'),
(20, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:25'),
(21, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:30'),
(22, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:35'),
(23, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:41'),
(24, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:46'),
(25, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:51'),
(26, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:40:56'),
(27, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:41:38'),
(28, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:41:44'),
(29, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:41:49'),
(30, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:41:54'),
(31, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:41:59'),
(32, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:05'),
(33, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:10'),
(34, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:15'),
(35, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:21'),
(36, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:26'),
(37, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:32'),
(38, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:42:53'),
(39, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:47:54'),
(40, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:52:54'),
(41, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 08:57:55'),
(42, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 09:01:39'),
(43, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 09:02:56'),
(44, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 09:03:20'),
(45, 'DHT11', 'Lab', 'nan', 'nan', '0', '2025-09-30 10:15:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sensordata`
--
ALTER TABLE `sensordata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensordata`
--
ALTER TABLE `sensordata`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
