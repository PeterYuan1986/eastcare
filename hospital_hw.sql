-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2020 at 12:30 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_hw`
--

-- --------------------------------------------------------

--
-- Table structure for table `disease`
--

CREATE TABLE `disease` (
  `disease_id` int(11) NOT NULL,
  `disease_info` char(40) DEFAULT NULL,
  `disease_note` char(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disease`
--

INSERT INTO `disease` (`disease_id`, `disease_info`, `disease_note`) VALUES
(1, 'Field_a', ''),
(2, 'Field_b', ''),
(3, 'Field_c', ''),
(4, 'Field_d', ''),
(5, 'Field_f', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `profession` int(11) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `city` varchar(10) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `name`, `profession`, `address`, `address2`, `city`, `state`, `zipcode`, `gender`, `birthday`, `phone`) VALUES
(1, 'PP', 1, '7950 N stadium dr', 'Apt 158', 'houston', 'TX', '77030', 'Male', '2020-11-02', 'test@uncg.edu'),
(2, 'YY', 2, '5640 West Market Street', 'Apt C', 'Greensboro', 'NC', '27409', 'Male', '2020-11-04', 'unihornstore@gmail.com'),
(3, 'CC', 4, '5640 West Market Street', 'Apt C', 'Greensboro', 'NC', '27409', 'Male', '2020-11-18', 'test@uncg.edu');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `username` varchar(12) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(10) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL,
  `firstname` varchar(8) NOT NULL,
  `lastname` varchar(8) NOT NULL,
  `office` varchar(8) NOT NULL DEFAULT 'sh',
  `level` int(11) NOT NULL DEFAULT 2,
  `cmpid` int(11) NOT NULL,
  `childid` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`username`, `email`, `password`, `approved`, `time`, `firstname`, `lastname`, `office`, `level`, `cmpid`, `childid`) VALUES
('test', 'unihornstore@gmail.com', '1234', 1, '2019-12-18 19:48:54', 'East', 'Care', 'admin', 0, 1, '[1]');

-- --------------------------------------------------------

--
-- Table structure for table `hospitalizationrecord`
--

CREATE TABLE `hospitalizationrecord` (
  `hospitalization_id` int(11) NOT NULL,
  `room_number` int(11) DEFAULT NULL,
  `bed_number` int(11) DEFAULT NULL,
  `patient_id` varchar(20) NOT NULL,
  `admission_date` date NOT NULL,
  `discharge_date` date DEFAULT NULL,
  `disease` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospitalizationrecord`
--

INSERT INTO `hospitalizationrecord` (`hospitalization_id`, `room_number`, `bed_number`, `patient_id`, `admission_date`, `discharge_date`, `disease`) VALUES
(1, 101, NULL, '1', '2020-09-02', NULL, ''),
(2, 101, NULL, '1', '2020-09-02', '2020-11-21', ''),
(3, 103, NULL, '2', '2020-07-06', '2020-11-22', ''),
(4, 101, NULL, '4', '2020-11-03', '2020-11-24', ''),
(5, 201, NULL, '1', '2020-11-02', NULL, ''),
(6, 106, NULL, '3', '2020-11-10', NULL, ''),
(7, 104, NULL, '4', '2020-11-04', NULL, '4');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `medicine_id` int(10) UNSIGNED NOT NULL,
  `medicine_info` char(40) DEFAULT NULL,
  `medicine_note` char(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`medicine_id`, `medicine_info`, `medicine_note`) VALUES
(1, 'medicine_a', ''),
(2, 'medicine_b', ''),
(3, 'medicine_c', ''),
(4, 'medicine_d', '');

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `date` datetime NOT NULL,
  `subject` varchar(1000) NOT NULL,
  `status` int(11) NOT NULL,
  `notes` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`date`, `subject`, `status`, `notes`) VALUES
('2020-11-22 16:15:27', 'Patient YY', 1, '2020-11-22 16:15:27>>>East Care: Please remind YY to take the medicine on 6pm<br>');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `name` char(10) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `insurance_info` char(10) DEFAULT NULL,
  `address` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `city` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `phone` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `name`, `birthday`, `gender`, `insurance_info`, `address`, `address2`, `city`, `state`, `zipcode`, `phone`) VALUES
(1, 'Peter', '2019-12-01', 'M', 'AMC', 'West Market Street', 'Apt C', 'Greensboro', 'NC', '27409', 'test@uncg.edu'),
(2, 'LindaPO210', '2020-07-23', 'M', 'DOC', '222 W CARROL ST', 'Apt C', 'KENTON', 'OH', '43326-1202', 'test@uncg.edu'),
(3, 'Tome', '1989-02-22', 'F', 'XYZ', '7950 N stadium dr', 'Apt 158', 'houston', 'TX', '77030', 'test@uncg.edu'),
(4, 'hongyu', '2020-11-03', 'M', 'AMC', '5640 West Market Street', 'Apt C', 'Greensboro', 'NC', '27409', 'unihornstore@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `procedur`
--

CREATE TABLE `procedur` (
  `procedure_id` int(11) NOT NULL,
  `procedure_name` char(20) DEFAULT NULL,
  `procedure_info` char(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `procedur`
--

INSERT INTO `procedur` (`procedure_id`, `procedure_name`, `procedure_info`) VALUES
(1, 'Procedure_a', ''),
(2, 'Procedure_b', ''),
(3, 'Procedure_c', ''),
(4, 'Procedure_d', 'Radio');

-- --------------------------------------------------------

--
-- Table structure for table `roomtype`
--

CREATE TABLE `roomtype` (
  `room_number` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `engaged` varchar(1) NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roomtype`
--

INSERT INTO `roomtype` (`room_number`, `type_id`, `engaged`) VALUES
(101, 1, 'N'),
(102, 2, 'N'),
(103, 2, 'N'),
(104, 1, 'Y'),
(105, 3, 'N'),
(106, 3, 'Y'),
(201, 1, 'Y'),
(202, 2, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `typed`
--

CREATE TABLE `typed` (
  `type_id` int(10) NOT NULL,
  `type_info` char(20) DEFAULT NULL,
  `type_price` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `typed`
--

INSERT INTO `typed` (`type_id`, `type_info`, `type_price`) VALUES
(1, 'Type_a', 1000),
(2, 'Type_b', 2000),
(3, 'Type_c', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `visitrecord`
--

CREATE TABLE `visitrecord` (
  `visitrecord_id` int(11) NOT NULL,
  `visit_date` date DEFAULT NULL,
  `visit_bill` float DEFAULT NULL,
  `procedure_id` int(11) NOT NULL,
  `medicine_id` int(10) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visitrecord`
--

INSERT INTO `visitrecord` (`visitrecord_id`, `visit_date`, `visit_bill`, `procedure_id`, `medicine_id`, `disease_id`, `doctor_id`, `patient_id`) VALUES
(1, '2020-11-03', 300, 1, 2, 1, 1, 1),
(2, '2020-11-03', 200, 3, 4, 3, 2, 2),
(3, '2020-11-12', 200, 3, 3, 2, 2, 3),
(4, '2020-10-12', 300, 2, 3, 2, 2, 2),
(5, '2020-10-12', 300, 2, 3, 2, 2, 2),
(6, '2020-10-12', 300, 2, 3, 2, 2, 2),
(7, '2020-10-12', 300, 2, 3, 2, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disease`
--
ALTER TABLE `disease`
  ADD PRIMARY KEY (`disease_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `hospitalizationrecord`
--
ALTER TABLE `hospitalizationrecord`
  ADD PRIMARY KEY (`hospitalization_id`) USING BTREE,
  ADD KEY `disease` (`disease`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `procedur`
--
ALTER TABLE `procedur`
  ADD PRIMARY KEY (`procedure_id`);

--
-- Indexes for table `roomtype`
--
ALTER TABLE `roomtype`
  ADD PRIMARY KEY (`room_number`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `typed`
--
ALTER TABLE `typed`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `visitrecord`
--
ALTER TABLE `visitrecord`
  ADD PRIMARY KEY (`visitrecord_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disease`
--
ALTER TABLE `disease`
  MODIFY `disease_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hospitalizationrecord`
--
ALTER TABLE `hospitalizationrecord`
  MODIFY `hospitalization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicine_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visitrecord`
--
ALTER TABLE `visitrecord`
  MODIFY `visitrecord_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
