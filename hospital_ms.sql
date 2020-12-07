-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 07, 2020 at 03:16 PM
-- Server version: 10.5.4-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_ms`
--
CREATE DATABASE IF NOT EXISTS `hospital_ms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `hospital_ms`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `appointment_status` enum('PENDING','ACCEPTED','COMPLETED','REJECTED','CANCELLED','PAID') NOT NULL DEFAULT 'PENDING',
  `comments` text DEFAULT NULL,
  PRIMARY KEY (`appointment_id`),
  KEY `fk_patient_id` (`patient_id`),
  KEY `fk_doctor_id` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `description`, `date`, `time`, `appointment_status`, `comments`) VALUES
(23, 21, 18, 'This is a test appointment', NULL, NULL, 'CANCELLED', NULL),
(24, 21, 18, 'This is another test', '2020-12-10', '10:50:00', 'COMPLETED', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lab_reports`
--

DROP TABLE IF EXISTS `lab_reports`;
CREATE TABLE IF NOT EXISTS `lab_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_test_id` int(11) NOT NULL,
  `file_location` varchar(200) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `fk_lab_test_id_reports` (`lab_test_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab_reports`
--

INSERT INTO `lab_reports` (`report_id`, `lab_test_id`, `file_location`) VALUES
(1, 7, '995928425.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

DROP TABLE IF EXISTS `lab_tests`;
CREATE TABLE IF NOT EXISTS `lab_tests` (
  `test_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `date` date DEFAULT NULL,
  `test_status` enum('ACCEPTED','COMPLETED','CANCELLED','PAID') NOT NULL,
  PRIMARY KEY (`test_id`),
  KEY `fk_user_id_tests` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`test_id`, `patient_id`, `details`, `date`, `test_status`) VALUES
(7, 21, 'This is the first lab test', '2020-12-06', 'COMPLETED');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `payment_for` enum('APPOINTMENT','LAB_TEST') NOT NULL,
  `paid_amount` varchar(255) NOT NULL,
  `stripe_customer_id` varchar(255) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `fk_patient_id_payments` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `patient_id`, `payment_for`, `paid_amount`, `stripe_customer_id`, `payment_date`) VALUES
(1, 21, 'APPOINTMENT', '5000', 'cus_IWotWVOIiNGvoJ', '2020-12-07 20:33:38'),
(2, 21, 'LAB_TEST', '7000', 'cus_IWovzk0hhD4UIK', '2020-12-07 20:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
CREATE TABLE IF NOT EXISTS `prescriptions` (
  `prescription_id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `prescription` text NOT NULL,
  `prescription_status` enum('PENDING','SHIPPED','RECEIVED','COMPLETED') NOT NULL,
  `prescription_location` text DEFAULT NULL,
  PRIMARY KEY (`prescription_id`),
  KEY `fk_doctor_id_prescriptions` (`doctor_id`),
  KEY `fk_patient_id_prescriptions` (`patient_id`),
  KEY `fk_appointment_id_prescriptions` (`appointment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`prescription_id`, `doctor_id`, `patient_id`, `appointment_id`, `prescription`, `prescription_status`, `prescription_location`) VALUES
(1, 18, 21, 24, 'This is the prescription', 'COMPLETED', 'Updated location');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `user_type` enum('ADMIN','DOCTOR','NURSE','STAFF','PATIENT') NOT NULL,
  `user_status` enum('ACTIVE','BLOCKED') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `contact`, `address`, `password`, `user_type`, `user_status`) VALUES
(1, 'Admin Account', 'admin', 'admin@gmail.com', '0774235521', '', '202cb962ac59075b964b07152d234b70', 'ADMIN', 'ACTIVE'),
(18, 'Test Doctor 1', 'doctor1', 'doctor1@gmail.com', '0548184254', NULL, '202cb962ac59075b964b07152d234b70', 'DOCTOR', 'ACTIVE'),
(19, 'Test Nurse 1', 'nurse1', 'nurse1@gmail.com', '0548184254', NULL, '202cb962ac59075b964b07152d234b70', 'NURSE', 'ACTIVE'),
(20, 'Test Staff 1', 'staff1', 'staff1@gmail.com', '0548184254', NULL, '202cb962ac59075b964b07152d234b70', 'STAFF', 'ACTIVE'),
(21, 'Test Patient 1', 'patient1', 'patient1@gmail.com', '0548184254', 'Test address lane, Test address, Colombo', '202cb962ac59075b964b07152d234b70', 'PATIENT', 'ACTIVE');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `lab_reports`
--
ALTER TABLE `lab_reports`
  ADD CONSTRAINT `fk_lab_test_id_reports` FOREIGN KEY (`lab_test_id`) REFERENCES `lab_tests` (`test_id`);

--
-- Constraints for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD CONSTRAINT `fk_user_id_tests` FOREIGN KEY (`patient_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_patient_id_payments` FOREIGN KEY (`patient_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `fk_appointment_id_prescriptions` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`),
  ADD CONSTRAINT `fk_doctor_id_prescriptions` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_patient_id_prescriptions` FOREIGN KEY (`patient_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
