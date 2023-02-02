-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2023 at 10:55 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trace_waste`
--

-- --------------------------------------------------------

--
-- Table structure for table `tw_mixwaste_manual_entry`
--

CREATE TABLE `tw_mixwaste_manual_entry` (
  `id` int(11) NOT NULL,
  `mix_waste_lot_id` int(255) NOT NULL,
  `entry_date` date NOT NULL,
  `waste_type` int(255) NOT NULL,
  `quantity` decimal(30,2) NOT NULL,
  `status` int(10) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `ward` int(255) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_ip` varchar(50) NOT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_ip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tw_mixwaste_manual_entry`
--

INSERT INTO `tw_mixwaste_manual_entry` (`id`, `mix_waste_lot_id`, `entry_date`, `waste_type`, `quantity`, `status`, `name`, `ward`, `created_by`, `created_on`, `created_ip`, `modified_by`, `modified_on`, `modified_ip`) VALUES
(1, 0, '2022-12-01', 1, '12.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(2, 0, '2022-12-01', 2, '4.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(3, 0, '2022-12-01', 3, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(4, 0, '2022-12-01', 4, '23.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(5, 0, '2022-12-01', 5, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(6, 0, '2022-12-01', 6, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(7, 0, '2022-12-01', 7, '121.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(8, 0, '2022-12-01', 8, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(9, 0, '2022-12-01', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(10, 0, '2022-12-01', 10, '21.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(11, 0, '2022-12-01', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(12, 0, '2022-12-01', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(13, 0, '2022-12-01', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(14, 0, '2022-12-01', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(15, 0, '2022-12-01', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(16, 0, '2022-12-01', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(17, 0, '2022-12-01', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(18, 0, '2022-12-01', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(19, 0, '2022-12-01', 19, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(20, 0, '2022-12-01', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(21, 0, '2022-12-01', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:26:51', '::1', '', '0000-00-00 00:00:00', ''),
(22, 0, '2022-12-05', 1, '12.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(23, 0, '2022-12-05', 2, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(24, 0, '2022-12-05', 3, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(25, 0, '2022-12-05', 4, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(26, 0, '2022-12-05', 5, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(27, 0, '2022-12-05', 6, '123.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(28, 0, '2022-12-05', 7, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(29, 0, '2022-12-05', 8, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(30, 0, '2022-12-05', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(31, 0, '2022-12-05', 10, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(32, 0, '2022-12-05', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(33, 0, '2022-12-05', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(34, 0, '2022-12-05', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(35, 0, '2022-12-05', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(36, 0, '2022-12-05', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(37, 0, '2022-12-05', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(38, 0, '2022-12-05', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(39, 0, '2022-12-05', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(40, 0, '2022-12-05', 19, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(41, 0, '2022-12-05', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(42, 0, '2022-12-05', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:10', '::1', '', '0000-00-00 00:00:00', ''),
(43, 0, '2022-12-07', 1, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(44, 0, '2022-12-07', 2, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(45, 0, '2022-12-07', 3, '22.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(46, 0, '2022-12-07', 4, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(47, 0, '2022-12-07', 5, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(48, 0, '2022-12-07', 6, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(49, 0, '2022-12-07', 7, '765.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(50, 0, '2022-12-07', 8, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(51, 0, '2022-12-07', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(52, 0, '2022-12-07', 10, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(53, 0, '2022-12-07', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(54, 0, '2022-12-07', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(55, 0, '2022-12-07', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(56, 0, '2022-12-07', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(57, 0, '2022-12-07', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(58, 0, '2022-12-07', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(59, 0, '2022-12-07', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(60, 0, '2022-12-07', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(61, 0, '2022-12-07', 19, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(62, 0, '2022-12-07', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(63, 0, '2022-12-07', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:29', '::1', '', '0000-00-00 00:00:00', ''),
(64, 0, '2022-12-13', 1, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(65, 0, '2022-12-13', 2, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(66, 0, '2022-12-13', 3, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(67, 0, '2022-12-13', 4, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(68, 0, '2022-12-13', 5, '221.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(69, 0, '2022-12-13', 6, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(70, 0, '2022-12-13', 7, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(71, 0, '2022-12-13', 8, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(72, 0, '2022-12-13', 9, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(73, 0, '2022-12-13', 10, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(74, 0, '2022-12-13', 11, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(75, 0, '2022-12-13', 12, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(76, 0, '2022-12-13', 13, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(77, 0, '2022-12-13', 14, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(78, 0, '2022-12-13', 15, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(79, 0, '2022-12-13', 16, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(80, 0, '2022-12-13', 17, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(81, 0, '2022-12-13', 18, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(82, 0, '2022-12-13', 19, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(83, 0, '2022-12-13', 20, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(84, 0, '2022-12-13', 21, '0.00', 1, 'Swati', 0, '7', '2022-12-20 12:28:45', '::1', '', '0000-00-00 00:00:00', ''),
(85, 0, '2022-12-15', 1, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(86, 0, '2022-12-15', 2, '21.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(87, 0, '2022-12-15', 3, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(88, 0, '2022-12-15', 4, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(89, 0, '2022-12-15', 5, '232.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(90, 0, '2022-12-15', 6, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(91, 0, '2022-12-15', 7, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(92, 0, '2022-12-15', 8, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(93, 0, '2022-12-15', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(94, 0, '2022-12-15', 10, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(95, 0, '2022-12-15', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(96, 0, '2022-12-15', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(97, 0, '2022-12-15', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(98, 0, '2022-12-15', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(99, 0, '2022-12-15', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(100, 0, '2022-12-15', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(101, 0, '2022-12-15', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(102, 0, '2022-12-15', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(103, 0, '2022-12-15', 19, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(104, 0, '2022-12-15', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(105, 0, '2022-12-15', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:28:59', '::1', '', '0000-00-00 00:00:00', ''),
(106, 0, '2022-12-30', 1, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(107, 0, '2022-12-30', 2, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(108, 0, '2022-12-30', 3, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(109, 0, '2022-12-30', 4, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(110, 0, '2022-12-30', 5, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(111, 0, '2022-12-30', 6, '232.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(112, 0, '2022-12-30', 7, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(113, 0, '2022-12-30', 8, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(114, 0, '2022-12-30', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(115, 0, '2022-12-30', 10, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(116, 0, '2022-12-30', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(117, 0, '2022-12-30', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(118, 0, '2022-12-30', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(119, 0, '2022-12-30', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(120, 0, '2022-12-30', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(121, 0, '2022-12-30', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(122, 0, '2022-12-30', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(123, 0, '2022-12-30', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(124, 0, '2022-12-30', 19, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(125, 0, '2022-12-30', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(126, 0, '2022-12-30', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:33:05', '::1', '', '0000-00-00 00:00:00', ''),
(127, 0, '2022-12-20', 1, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(128, 0, '2022-12-20', 2, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(129, 0, '2022-12-20', 3, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(130, 0, '2022-12-20', 4, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(131, 0, '2022-12-20', 5, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(132, 0, '2022-12-20', 6, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(133, 0, '2022-12-20', 7, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(134, 0, '2022-12-20', 8, '564.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(135, 0, '2022-12-20', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(136, 0, '2022-12-20', 10, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(137, 0, '2022-12-20', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(138, 0, '2022-12-20', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(139, 0, '2022-12-20', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(140, 0, '2022-12-20', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(141, 0, '2022-12-20', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(142, 0, '2022-12-20', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(143, 0, '2022-12-20', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(144, 0, '2022-12-20', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(145, 0, '2022-12-20', 19, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(146, 0, '2022-12-20', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(147, 0, '2022-12-20', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:25', '::1', '', '0000-00-00 00:00:00', ''),
(148, 0, '2022-12-18', 1, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(149, 0, '2022-12-18', 2, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(150, 0, '2022-12-18', 3, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(151, 0, '2022-12-18', 4, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(152, 0, '2022-12-18', 5, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(153, 0, '2022-12-18', 6, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(154, 0, '2022-12-18', 7, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(155, 0, '2022-12-18', 8, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(156, 0, '2022-12-18', 9, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(157, 0, '2022-12-18', 10, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(158, 0, '2022-12-18', 11, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(159, 0, '2022-12-18', 12, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(160, 0, '2022-12-18', 13, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(161, 0, '2022-12-18', 14, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(162, 0, '2022-12-18', 15, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(163, 0, '2022-12-18', 16, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(164, 0, '2022-12-18', 17, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(165, 0, '2022-12-18', 18, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(166, 0, '2022-12-18', 19, '68.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(167, 0, '2022-12-18', 20, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(168, 0, '2022-12-18', 21, '0.00', 1, 'Dhiraj', 0, '7', '2022-12-20 12:38:59', '::1', '', '0000-00-00 00:00:00', ''),
(169, 0, '2023-01-11', 1, '12.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(170, 0, '2023-01-11', 2, '221.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(171, 0, '2023-01-11', 3, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(172, 0, '2023-01-11', 4, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(173, 0, '2023-01-11', 5, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(174, 0, '2023-01-11', 6, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(175, 0, '2023-01-11', 7, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(176, 0, '2023-01-11', 8, '21.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(177, 0, '2023-01-11', 9, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(178, 0, '2023-01-11', 10, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(179, 0, '2023-01-11', 11, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(180, 0, '2023-01-11', 12, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(181, 0, '2023-01-11', 13, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(182, 0, '2023-01-11', 14, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(183, 0, '2023-01-11', 15, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:23:34', '::1', '', '0000-00-00 00:00:00', ''),
(184, 0, '2023-04-15', 1, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(185, 0, '2023-04-15', 2, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(186, 0, '2023-04-15', 3, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(187, 0, '2023-04-15', 4, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(188, 0, '2023-04-15', 5, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(189, 0, '2023-04-15', 6, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(190, 0, '2023-04-15', 7, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(191, 0, '2023-04-15', 8, '100.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(192, 0, '2023-04-15', 9, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(193, 0, '2023-04-15', 10, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(194, 0, '2023-04-15', 11, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(195, 0, '2023-04-15', 12, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(196, 0, '2023-04-15', 13, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(197, 0, '2023-04-15', 14, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(198, 0, '2023-04-15', 15, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 10:26:55', '::1', '', '0000-00-00 00:00:00', ''),
(199, 0, '2023-01-10', 1, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(200, 0, '2023-01-10', 2, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(201, 0, '2023-01-10', 3, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(202, 0, '2023-01-10', 4, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(203, 0, '2023-01-10', 5, '21.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(204, 0, '2023-01-10', 6, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(205, 0, '2023-01-10', 7, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(206, 0, '2023-01-10', 8, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(207, 0, '2023-01-10', 9, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(208, 0, '2023-01-10', 10, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(209, 0, '2023-01-10', 11, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(210, 0, '2023-01-10', 12, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(211, 0, '2023-01-10', 13, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(212, 0, '2023-01-10', 14, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(213, 0, '2023-01-10', 15, '0.00', 1, 'Dhiraj', 0, '7', '2023-01-11 11:42:05', '::1', '', '0000-00-00 00:00:00', ''),
(214, 0, '2023-01-13', 1, '12.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(215, 0, '2023-01-13', 2, '21.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(216, 0, '2023-01-13', 3, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(217, 0, '2023-01-13', 4, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(218, 0, '2023-01-13', 5, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(219, 0, '2023-01-13', 6, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(220, 0, '2023-01-13', 7, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(221, 0, '2023-01-13', 8, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(222, 0, '2023-01-13', 9, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(223, 0, '2023-01-13', 10, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(224, 0, '2023-01-13', 11, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(225, 0, '2023-01-13', 12, '210.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(226, 0, '2023-01-13', 13, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(227, 0, '2023-01-13', 14, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1'),
(228, 0, '2023-01-13', 15, '0.00', 1, 'Dhiraj', 1, '7', '2023-01-11 12:46:00', '::1', '7', '2023-01-11 12:52:26', '::1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tw_mixwaste_manual_entry`
--
ALTER TABLE `tw_mixwaste_manual_entry`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tw_mixwaste_manual_entry`
--
ALTER TABLE `tw_mixwaste_manual_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
