-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2021 at 10:36 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `user_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `name`, `username`, `password`, `user_type`) VALUES
(1, 'Admin', 'admin', '123456', 'Admin'),
(1, 'Admin', 'admin', '1234567', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `auto`
--

CREATE TABLE `auto` (
  `auto_id` int(11) NOT NULL,
  `auto_start` int(11) NOT NULL,
  `auto_end` int(11) NOT NULL,
  `increment` int(11) NOT NULL,
  `description` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auto`
--

INSERT INTO `auto` (`auto_id`, `auto_start`, `auto_end`, `increment`, `description`) VALUES
(1, 100, 50, 1, 'BORROWED'),
(2, 100, 11, 1, 'BORROWED-TOOL');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_tools`
--

CREATE TABLE `borrowed_tools` (
  `borrow_id` int(11) NOT NULL,
  `tool_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `borrowed_date` varchar(30) NOT NULL,
  `borrowed_time` varchar(30) NOT NULL,
  `due_date` varchar(30) NOT NULL,
  `date_returned` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrowed_tools`
--

INSERT INTO `borrowed_tools` (`borrow_id`, `tool_id`, `qty`, `emp_id`, `borrowed_date`, `borrowed_time`, `due_date`, `date_returned`, `status`, `location_id`) VALUES
(5, 1, 5, 2, '2021-03-07', '20:15:52 PM', '2021-03-11', '2021-03-07', 'RETURNED', 2);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `age` int(3) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `name`, `age`, `address`, `contact_number`, `position`) VALUES
(1, 'ryan manaay', 23, 'Himamaylan city', '0912344547', 'welder'),
(2, 'juan dela cruz', 25, 'Binalbagan', '09123456789', 'carpenter'),
(3, 'given bariacto', 23, 'alimango', '0912345678', 'helper'),
(4, 'juday', 25, 'Himamaylan City', '0912345678', 'kargadorr'),
(5, 'Adrian Mercurio', 23, 'Himamaylan City', '0912345678', 'asf');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `equip_id` int(11) NOT NULL,
  `uniquec` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `category` varchar(30) NOT NULL,
  `quantity` int(11) NOT NULL,
  `available_quantity` int(11) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`equip_id`, `uniquec`, `name`, `category`, `quantity`, `available_quantity`, `status`) VALUES
(6, '112221', 'bachoe', 'HEAVY', 1, 1, 'UNAVAILABLE'),
(7, '111', 'tracktor', 'HEAVY', 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `equip_mapping`
--

CREATE TABLE `equip_mapping` (
  `map_id` int(11) NOT NULL,
  `equip_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `remaining_qty` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `date_borrowed` varchar(30) NOT NULL,
  `time_borrowed` varchar(30) NOT NULL,
  `due_date` varchar(30) NOT NULL,
  `date_returned` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `emp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equip_mapping`
--

INSERT INTO `equip_mapping` (`map_id`, `equip_id`, `qty`, `remaining_qty`, `location_id`, `date_borrowed`, `time_borrowed`, `due_date`, `date_returned`, `status`, `emp_id`) VALUES
(66, 6, 1, 0, 1, '2021-02-08', '23:01:53 PM', '2021-02-10', '', 'TRANSFERED', 1),
(67, 7, 1, 0, 1, '2021-03-07', '19:52:03 PM', '2021-03-20', '2021-03-07', 'RETURNED', 3);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `location_id` int(11) NOT NULL,
  `location_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `location_address`) VALUES
(1, 'Himamaylan City'),
(2, 'Kabankalan City'),
(3, 'Hinigaran'),
(4, 'Bago City\r\n'),
(5, 'korea'),
(6, 'Sipalay City');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `action`, `date_time`) VALUES
(1, 'user Admin was login', '2021-02-13 16:50:23'),
(2, 'user Admin was logout', '2021-02-13 16:50:46'),
(3, 'user Admin was login', '2021-02-13 16:51:55'),
(4, 'employee judayy was Added', '2021-02-13 16:58:00'),
(5, 'employee juday was updated', '2021-02-13 16:59:02'),
(6, 'employee juday was updated', '2021-02-13 16:59:02'),
(7, 'employee juday was updated', '2021-02-13 16:59:02'),
(8, 'employee juday was updated', '2021-02-13 16:59:02'),
(9, 'employee juday was updated', '2021-02-13 17:00:30'),
(10, 'employee juday was updated', '2021-02-13 17:00:30'),
(11, 'employee juday was updated', '2021-02-13 17:00:30'),
(12, 'employee juday was updated', '2021-02-13 17:00:30'),
(13, 'user Admin was logout', '2021-02-13 17:01:05'),
(14, 'user Admin was login', '2021-02-13 17:01:13'),
(15, 'location Bagoo City\r\n was updated', '2021-02-13 17:02:00'),
(16, 'location Bagoo City\r\n was updated', '2021-02-13 17:02:00'),
(17, 'location Bagoo City\r\n was updated', '2021-02-13 17:02:00'),
(18, 'location Bagoo City\r\n was updated', '2021-02-13 17:02:00'),
(19, 'location Bagoo City\r\n was updated', '2021-02-13 17:02:00'),
(20, 'location Bagoo City\r\n was updated', '2021-02-13 17:02:00'),
(21, 'user Admin was logout', '2021-02-13 17:06:43'),
(22, 'user Admin was login', '2021-02-13 17:06:49'),
(23, 'user Admin was login', '2021-02-14 00:49:05'),
(24, 'user Admin was logout', '2021-02-14 00:49:19'),
(25, 'user Admin was login', '2021-02-14 00:49:24'),
(26, 'employee Adrian Mercurio was Added', '2021-02-14 00:49:56'),
(27, 'user Admin was logout', '2021-02-14 00:50:22'),
(28, 'user Admin was login', '2021-02-14 00:53:20'),
(29, 'user Admin was login', '2021-03-05 02:52:31'),
(30, 'user Admin was login', '2021-03-06 14:22:29'),
(31, 'user Admin was login', '2021-03-08 06:44:16'),
(32, 'user John Smith was logout', '2021-03-08 08:10:21'),
(33, 'user Admin was login', '2021-03-08 08:10:27'),
(34, 'user Admin was login', '2021-03-08 11:49:08'),
(35, 'equipment tracktor was Added', '2021-03-08 11:51:48'),
(36, 'user Admin was login', '2021-03-08 12:40:59'),
(37, 'transfered equipment id 6 from 1 to 6 by employee 1', '2021-03-08 12:45:14'),
(38, 'user Admin was login', '2021-03-08 14:28:48'),
(39, 'returned equipment id 7', '2021-03-08 14:33:27'),
(40, 'returned tool id 1', '2021-03-08 14:53:24'),
(41, 'location Bago City\r\n was updated', '2021-03-08 15:13:32'),
(42, 'location Bago City\r\n was updated', '2021-03-08 15:13:32'),
(43, 'location Bago City\r\n was updated', '2021-03-08 15:13:32'),
(44, 'location Bago City\r\n was updated', '2021-03-08 15:13:32'),
(45, 'location Bago City\r\n was updated', '2021-03-08 15:13:32'),
(46, 'location Bago City\r\n was updated', '2021-03-08 15:13:32'),
(47, 'user Admin was login', '2021-03-11 04:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `outsourcing`
--

CREATE TABLE `outsourcing` (
  `source_id` int(11) NOT NULL,
  `company_name` varchar(30) NOT NULL,
  `equip_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_outsourced` varchar(30) NOT NULL,
  `source_code` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `outsourcing_tools`
--

CREATE TABLE `outsourcing_tools` (
  `source_id` int(11) NOT NULL,
  `company_name` varchar(30) NOT NULL,
  `tool_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_outsourced` varchar(30) NOT NULL,
  `source_code` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `temp_id` int(11) NOT NULL,
  `equip_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `unique_id` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `company_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `temp1`
--

CREATE TABLE `temp1` (
  `temp1_id` int(11) NOT NULL,
  `tool_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `unique_id` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `company_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `tool_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `quantity` int(11) NOT NULL,
  `available_quantity` int(11) NOT NULL,
  `tool_type` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`tool_id`, `name`, `quantity`, `available_quantity`, `tool_type`, `status`) VALUES
(1, 'Helmet', 20, 15, 'NON CONSUMABLE', ''),
(2, 'Welding Rod', 100, 100, 'CONSUMABLE', '');

-- --------------------------------------------------------

--
-- Table structure for table `transfered_equipment`
--

CREATE TABLE `transfered_equipment` (
  `transfer_id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL,
  `equip_id` int(11) NOT NULL,
  `emp_id_from` int(11) NOT NULL,
  `emp_id_to` int(11) NOT NULL,
  `date_transfered` varchar(30) NOT NULL,
  `time_transfered` varchar(30) NOT NULL,
  `location_id_from` int(11) NOT NULL,
  `location_id_to` int(11) NOT NULL,
  `date_returned` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transfered_equipment`
--

INSERT INTO `transfered_equipment` (`transfer_id`, `map_id`, `equip_id`, `emp_id_from`, `emp_id_to`, `date_transfered`, `time_transfered`, `location_id_from`, `location_id_to`, `date_returned`, `status`) VALUES
(14, 66, 6, 1, 4, '2021-03-07', '20:45:14 PM', 1, 6, '', 'TRANSFERED');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `auto`
--
ALTER TABLE `auto`
  ADD PRIMARY KEY (`auto_id`);

--
-- Indexes for table `borrowed_tools`
--
ALTER TABLE `borrowed_tools`
  ADD PRIMARY KEY (`borrow_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`equip_id`);

--
-- Indexes for table `equip_mapping`
--
ALTER TABLE `equip_mapping`
  ADD PRIMARY KEY (`map_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `outsourcing`
--
ALTER TABLE `outsourcing`
  ADD PRIMARY KEY (`source_id`);

--
-- Indexes for table `outsourcing_tools`
--
ALTER TABLE `outsourcing_tools`
  ADD PRIMARY KEY (`source_id`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indexes for table `temp1`
--
ALTER TABLE `temp1`
  ADD PRIMARY KEY (`temp1_id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`tool_id`);

--
-- Indexes for table `transfered_equipment`
--
ALTER TABLE `transfered_equipment`
  ADD PRIMARY KEY (`transfer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auto`
--
ALTER TABLE `auto`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `borrowed_tools`
--
ALTER TABLE `borrowed_tools`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `equip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `equip_mapping`
--
ALTER TABLE `equip_mapping`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `outsourcing`
--
ALTER TABLE `outsourcing`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `outsourcing_tools`
--
ALTER TABLE `outsourcing_tools`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `temp1`
--
ALTER TABLE `temp1`
  MODIFY `temp1_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `tool_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transfered_equipment`
--
ALTER TABLE `transfered_equipment`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
