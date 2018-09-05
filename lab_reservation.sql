-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2018 at 05:05 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `lab_request_form`
--

CREATE TABLE `lab_request_form` (
  `Name` varchar(25) NOT NULL,
  `Phone` varchar(11) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Room` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `Time_Start` time NOT NULL,
  `ampm_start` text NOT NULL,
  `Time_End` time NOT NULL,
  `ampm_end` text NOT NULL,
  `Event` text NOT NULL,
  `Purpose` text NOT NULL,
  `Course_Prefix` varchar(3) NOT NULL,
  `Course_Number` int(4) NOT NULL,
  `Section` int(4) NOT NULL,
  `Software` text NOT NULL,
  `Unique_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab_request_form`
--

INSERT INTO `lab_request_form` (`Name`, `Phone`, `Email`, `Room`, `Date`, `Time_Start`, `ampm_start`, `Time_End`, `ampm_end`, `Event`, `Purpose`, `Course_Prefix`, `Course_Number`, `Section`, `Software`, `Unique_ID`) VALUES
('Display_Name', '888-888-888', 'ryanbuchanan21@gmail.com', '1911-110', '2018-09-20', '01:00:00', 'AM', '01:00:00', 'PM', '', '', 'scs', 111, 111, '', 1),
('Display_Name', '828-897-166', 'ryanbuchanan21@gmail.com', '1911-110', '2018-09-12', '01:00:00', 'AM', '01:00:00', 'PM', 'Event Test', 'TestingTestingTesting', '', 0, 0, 'Software', 2),
('Display_Name', '828-897-166', 'ryanbuchanan21@gmail.com', '1911-110', '2018-09-06', '01:00:00', 'AM', '01:00:00', 'PM', '', '', 'csc', 111, 111, '', 3),
('Display_Name', '828-897-166', 'ryanbuchanan21@gmail.com', '1911-110', '2018-09-12', '04:30:00', 'PM', '05:30:00', 'PM', '', '', 'csc', 121, 121, 'testing the new variable declarations', 4),
('Display_Name', '828-897-166', 'ryanbuchanan21@gmail.com', '1911-110', '2018-09-05', '11:00:00', 'AM', '03:45:00', 'PM', 'Testing Event', 'Test', '', 0, 0, 'yes', 5),
('Display_Name', '828-897-166', 'ryanbuchanan21@gmail.com', '1911-110', '2018-09-06', '01:00:00', 'AM', '01:00:00', 'PM', 'Testing Event', 'Test', '', 0, 0, 'yes', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lab_request_form`
--
ALTER TABLE `lab_request_form`
  ADD PRIMARY KEY (`Unique_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lab_request_form`
--
ALTER TABLE `lab_request_form`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
