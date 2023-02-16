-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2020 at 11:38 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webappointmentsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `comments` text NOT NULL,
  `patient_first_name` varchar(50) NOT NULL,
  `patient_last_name` varchar(50) NOT NULL,
  `patient_email` text NOT NULL,
  `patient_mobile` varchar(11) NOT NULL,
  `patient_dob` datetime NOT NULL,
  `patient_gender` varchar(50) NOT NULL,
  `appointment_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `system_logs_id` int(11) NOT NULL,
  `appointment_status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `comments`, `patient_first_name`, `patient_last_name`, `patient_email`, `patient_mobile`, `patient_dob`, `patient_gender`, `appointment_datetime`, `system_logs_id`, `appointment_status`) VALUES
(8, 66, 58, 'hello', 'Harsh', 'Thakkar', '1234567890', 'test@test.c', '0000-00-00 00:00:00', 'Female', '2020-04-22 10:27:32', 1, 0),
(9, 66, 58, '3232', 'Harsh', 'Thakkar', '1234567890', 'test@test.c', '0000-00-00 00:00:00', 'Female', '2020-05-13 15:00:00', 1, 0),
(10, 66, 58, 'huhu', 'Harsh', 'Thakkar', '1234567890', 'test@test.c', '0000-00-00 00:00:00', 'Female', '2020-04-09 17:00:00', 1, 0),
(11, 66, 58, 'hello', 'Haarsh', 'Thakkar', '1234567890', 'test@test.c', '0000-00-00 00:00:00', 'Female', '2020-04-12 19:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_doctor_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `ratings` int(11) NOT NULL,
  `feedback_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `from_user_id`, `to_doctor_id`, `feedback`, `ratings`, `feedback_datetime`) VALUES
(1, 55, 58, 'Nice', 4, '2020-03-31 06:13:55'),
(2, 55, 58, 'Nice', 3, '2020-03-31 06:14:00'),
(3, 55, 58, 'Nice', 2, '2020-03-31 06:14:04'),
(4, 55, 58, 'Nice', 5, '2020-03-31 06:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` text NOT NULL,
  `amount` int(11) NOT NULL,
  `transaction_id` text NOT NULL,
  `status` text NOT NULL,
  `payment_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `appointment_id`, `user_id`, `session_id`, `amount`, `transaction_id`, `status`, `payment_datetime`) VALUES
(3, 1, 1, '93p5aa7qf21l1m5io5o0j98gm5', 500, 'ch_1GTKy6AyYKpnWlmbdV7LlQFw', 'succeeded', '2020-04-02 04:47:57'),
(4, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTcRLAyYKpnWlmbnBUks44i', 'succeeded', '2020-04-02 23:27:16'),
(5, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTcVND76Xqpq580EDsBUPvg', 'succeeded', '2020-04-02 23:31:26'),
(6, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTchPD76Xqpq580dCkd561u', 'succeeded', '2020-04-02 23:43:52'),
(7, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTdETD76Xqpq580t0FQU0PJ', 'succeeded', '2020-04-03 00:18:02'),
(8, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTdFaD76Xqpq580ZUQBws9d', 'succeeded', '2020-04-03 00:19:11'),
(9, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTdZvD76Xqpq580xus2EW92', 'succeeded', '2020-04-03 00:40:11'),
(10, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTe3WD76Xqpq580Yd35i05s', 'succeeded', '2020-04-03 01:10:47'),
(11, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTe67D76Xqpq580WqGnKeMB', 'succeeded', '2020-04-03 01:13:28'),
(12, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTeLkD76Xqpq580fCqnFm0a', 'succeeded', '2020-04-03 01:29:38'),
(13, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTeMDD76Xqpq580KZV9rtpc', 'succeeded', '2020-04-03 01:30:06'),
(14, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTeTkD76Xqpq5808d9leYmj', 'succeeded', '2020-04-03 01:37:53'),
(15, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTel2D76Xqpq580nMdPwvJH', 'succeeded', '2020-04-03 01:55:45'),
(16, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTeoiD76Xqpq580iYadrueF', 'succeeded', '2020-04-03 01:59:33'),
(17, 1, 1, 'i892moh7jqg381jt12ueu5d935', 500, 'ch_1GTfT1D76Xqpq580sMuCvEga', 'succeeded', '2020-04-03 02:41:12'),
(18, 1, 1, 'tn441ok4u8f72s2l73j00ilhb5', 500, 'ch_1GU1a9D76Xqpq580JoQtser1', 'succeeded', '2020-04-04 02:18:03'),
(19, 1, 1, '43e4usuqd95pcrjm2mh0b25273', 500, 'ch_1GURhkD76Xqpq580Jgs2Bxhv', 'succeeded', '2020-04-05 06:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `system_logs_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` text,
  `ip` text,
  `user_agent` text,
  `reference_url` text,
  `current_url` text,
  `isp` text,
  `org` text,
  `as_info` text,
  `city` text,
  `state` text,
  `country` text,
  `zip` text,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`system_logs_id`, `user_id`, `session_id`, `ip`, `user_agent`, `reference_url`, `current_url`, `isp`, `org`, `as_info`, `city`, `state`, `country`, `zip`, `datetime`) VALUES
(1, 66, 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', '2020-04-03 01:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `user_profile_id` int(11) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` text NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `profile_url` varchar(9999) DEFAULT 'assets/img/user.png',
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type_id`, `user_profile_id`, `first_name`, `last_name`, `email`, `password`, `mobile`, `address`, `gender`, `dob`, `profile_url`, `city`, `state`, `country`, `status`, `registration_date`) VALUES
(55, 2, NULL, 'Harsh', 'Thakkar', 'harsh.thakkar1235@gmail.com', '7ca1184331c0bcd56c9d3b09a2f20859', '1234567890', 'HarshHarsh', 'Male', '2020-03-10', '', 'montreal', 'quebec', 'canada', 1, '2020-04-04 06:56:40'),
(56, 2, NULL, 'Harsh', 'Thakkar', 'harsh.thakkHarshar1235@gmail.com', '431a434536c66c0ec88251d577a095db', '1234578902', 'harsh', 'Male', '1998-12-31', '', 'toronto', 'ontario', 'canada', 1, '2020-03-29 23:19:32'),
(57, 1, NULL, 'Harsh', 'Thakkar', 'tech@tech.com', '431a434536c66c0ec88251d577a095db', '1234567890', '821 Sainte Croix Ave, Saint-Laurent,, , Quebec', 'Male', '1998-12-31', '', '', '', '', 1, '2020-03-29 23:19:17'),
(58, 2, 1, 'Harsh', 'Thakkar', 'd@d.com', '7ca1184331c0bcd56c9d3b09a2f20859', '1234567890', '821 Sainte Croix Ave, Saint-Laurent, Quebec H4L 3X9', 'Male', '2020-03-10', './assets/img/doctors/43e4usuqd95pcrjm2mh0b2527320-04-05-04-34-47doctor-thumb-04.jpg', 'montreal', 'quebec', 'canada', 1, '2020-04-05 08:34:47'),
(59, 2, NULL, 'DrHarsh', 'Thakkar', 'dd@ddd.copmmm', '7ca1184331c0bcd56c9d3b09a2f20859', '1234578902', 'harsh', 'Male', '1998-12-31', 'http://localhost/WebAppointmentSystem/web/assets/img/doctors/doctor-thumb-01.jpg', 'toronto', 'ontario', 'canada', 1, '2020-03-30 06:03:01'),
(61, 1, NULL, 'harsh', 'Thakkar', 'harsh.thakkar@gmail.com', '7ca1184331c0bcd56c9d3b09a2f20859', '1234567890', 'montreal', 'Male', '0000-00-00', '', 'mon', NULL, NULL, 1, '2020-04-04 05:50:27'),
(64, 2, 1, 'Dr . Spock', 'Thakkar', 'd@dda.com', '7ca1184331c0bcd56c9d3b09a2f20859', '1234567890', '821 Sainte Croix Ave, Saint-Laurent, Quebec H4L 3X9', 'Male', '2020-03-10', 'http://localhost/WebAppointmentSystem/web/assets/img/doctors/doctor-thumb-01.jpg', 'montreal', 'quebec', 'canada', 1, '2020-04-04 06:56:37'),
(65, 2, 2, 'Dr Cooper', 'Sheldon', 'dd@ddd.copmmmmm', '7ca1184331c0bcd56c9d3b09a2f20859', '1234578902', 'harsh', 'Male', '1998-12-31', 'http://localhost/WebAppointmentSystem/web/assets/img/doctors/doctor-thumb-01.jpg', 'montreal', 'quebec', 'canada', 1, '2020-03-30 06:25:13'),
(66, 1, NULL, 'Haarsh', 'Thakkar', 'test@test.com', '7ca1184331c0bcd56c9d3b09a2f20859', '1234567890', '821 Sainte Croix Ave, Saint-Laurent,, , Quebec', 'Female', '1998-12-31', './assets/img/patients/43e4usuqd95pcrjm2mh0b2527320-04-05-05-04-17patient2.jpg', 'Montreal', 'Quebec', 'Canada', 1, '2020-04-05 09:04:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `user_profile_id` int(11) NOT NULL,
  `title` text,
  `description` text,
  `specialities` text,
  `business_hours` text,
  `cost` text,
  `feedback_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`user_profile_id`, `title`, `description`, `specialities`, `business_hours`, `cost`, `feedback_id`) VALUES
(1, 'MDS - Periodontology and Oral Implantology, BDS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'cardiologist, gynecologist,', '05:00 AM - 03:00 PM, 09:00 AM - 01:00 PM, 07:00 AM - 09:00 PM, 07:00 AM - 09:00 PM, 06:00 AM - 02:00 PM, 06:00 AM - 04:00 PM, 09:00 AM - 07:00 PM,', '$300 - $1000', 1),
(2, 'Oral Implantology, BDS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'orthopedic', '07:00 AM - 09:00 PM, 07:00 AM - 09:00 PM, 07:00 AM - 09:00 PM, 07:00 AM - 09:00 PM, 07:00 AM - 09:00 PM, 07:00 AM - 09:00 PM,Closed,', '$100 - $800', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `user_types_id` int(11) NOT NULL,
  `user_type_name` text NOT NULL,
  `access_priviledges` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`user_types_id`, `user_type_name`, `access_priviledges`) VALUES
(1, 'patient', 'PATIENTPANEL'),
(2, 'doctor', 'DOCTORPANEL');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `references_users` (`patient_id`),
  ADD KEY `references_system_logs` (`system_logs_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_doctor_id` (`to_doctor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `reference_messages` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`system_logs_id`),
  ADD KEY `reference_system_log` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `reference` (`user_type_id`),
  ADD KEY `user_profile_id` (`user_profile_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_profile_id`),
  ADD KEY `feedback_id` (`feedback_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`user_types_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `system_logs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `user_profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `user_types_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `references_system_logs` FOREIGN KEY (`system_logs_id`) REFERENCES `system_logs` (`system_logs_id`),
  ADD CONSTRAINT `references_users` FOREIGN KEY (`patient_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`to_doctor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `reference_messages` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `reference_system_log` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `reference` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`user_types_id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_profile_id`) REFERENCES `user_profile` (`user_profile_id`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`feedback_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
