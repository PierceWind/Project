-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2022 at 10:51 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `prog` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_code`, `course_name`, `prog`) VALUES
(1, 'IS104', 'System Analysis and Design', 'Bachelor of Science in Information System (BSIS)'),
(2, 'ITE102', 'Computer Programming 2', 'Bachelor of Science in Information System (BSIS)'),
(8, 'ADV04', 'Data Communication and Networking', 'Bachelor of Science in Information System (BSIS)');

-- --------------------------------------------------------

--
-- Table structure for table `entrance_log`
--

CREATE TABLE `entrance_log` (
  `stud_id` int(11) NOT NULL,
  `logdate` date DEFAULT NULL,
  `logtime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `entrance_log`
--

INSERT INTO `entrance_log` (`stud_id`, `logdate`, `logtime`) VALUES
(202000056, '2022-12-09', '01:12:52'),
(202000068, '2022-12-09', '01:16:47');

-- --------------------------------------------------------

--
-- Table structure for table `loginlogs`
--

CREATE TABLE `loginlogs` (
  `id` int(11) NOT NULL,
  `IpAddress` varbinary(16) NOT NULL,
  `TryTime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `prog_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `prog_name`) VALUES
(1, 'Bachelor of Science in Information System (BSIS)'),
(2, 'Bachelor of Science in Computer Engineering (BSCpE)');

-- --------------------------------------------------------

--
-- Table structure for table `qrcode`
--

CREATE TABLE `qrcode` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `qrimage` blob NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `qrcode`
--

INSERT INTO `qrcode` (`id`, `stud_id`, `qrimage`, `date_created`) VALUES
(7, 202000068, 0x313636393930353636362e706e67, '2022-12-01 22:41:06'),
(8, 202000049, 0x313636393930353736302e706e67, '2022-12-01 22:42:40'),
(9, 202000056, 0x313636393930353839392e706e67, '2022-12-01 22:44:59'),
(11, 202000043, 0x313637303636353736312e706e67, '2022-12-10 17:49:21');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `teacher_id` int(50) NOT NULL,
  `assigned_program` varchar(100) NOT NULL,
  `assigned_course` varchar(100) NOT NULL,
  `assigned_section` varchar(100) NOT NULL,
  `assigned_sched` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`teacher_id`, `assigned_program`, `assigned_course`, `assigned_section`, `assigned_sched`) VALUES
(1234567890, 'Bachelor of Science in Information System (BSIS)', 'System Analysis and Design', '02', '12:30:00'),
(1234567898, 'Bachelor of Science in Information System (BSIS)', 'Data Communication and Networking', '03', '07:30:00'),
(1234567899, 'Bachelor of Science in Information System (BSIS)', 'Computer Programming 2', '02', '08:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `stud_id` int(11) NOT NULL,
  `course` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `log_date` date DEFAULT NULL,
  `log_time` time DEFAULT NULL,
  `stat` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Cutting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `stud_fname` varchar(100) NOT NULL,
  `stud_mname` varchar(100) NOT NULL,
  `stud_lname` varchar(100) NOT NULL,
  `stud_DOB` date NOT NULL,
  `stud_date_enrolled` date NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `stud_id`, `stud_fname`, `stud_mname`, `stud_lname`, `stud_DOB`, `stud_date_enrolled`, `date_created`, `date_modified`) VALUES
(24, 202000043, 'Jerusa Mae', 'Peñarubia', 'Abalos', '2002-05-27', '2022-08-10', '2022-12-10 17:49:21', '0000-00-00 00:00:00'),
(21, 202000049, 'Kevin', 'Samuya', 'Brizuela', '2001-08-01', '2022-12-05', '2022-12-01 22:42:38', '0000-00-00 00:00:00'),
(22, 202000056, 'Reyster', 'Gercin', 'Bagobe', '2000-07-31', '2022-12-07', '2022-12-01 22:44:59', '0000-00-00 00:00:00'),
(20, 202000068, 'Jennylyn', '', 'Villarosa', '2001-10-17', '2022-12-03', '2022-12-01 22:41:04', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `stud_address`
--

CREATE TABLE `stud_address` (
  `stud_id` int(11) NOT NULL,
  `stud_house_num` varchar(100) NOT NULL,
  `stud_house_st` varchar(100) NOT NULL,
  `stud_house_brgy` varchar(100) NOT NULL,
  `stud_house_city` varchar(100) NOT NULL,
  `stud_house_zip` int(5) NOT NULL,
  `stud_house_prov` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stud_class`
--

CREATE TABLE `stud_class` (
  `class_id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `stud_prog` varchar(100) NOT NULL,
  `stud_course` varchar(100) NOT NULL,
  `stud_sec` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stud_class`
--

INSERT INTO `stud_class` (`class_id`, `stud_id`, `stud_prog`, `stud_course`, `stud_sec`) VALUES
(32, 202000068, 'Bachelor of Science in Information System (BSIS)', 'IS104', '02'),
(33, 202000049, 'Bachelor of Science in Information System (BSIS)', 'ADV04', '02'),
(34, 202000056, 'Bachelor of Science in Information System (BSIS)', 'ADV04', '03'),
(36, 202000043, 'Bachelor of Science in Information System (BSIS)', 'IS104', '02');

-- --------------------------------------------------------

--
-- Table structure for table `stud_image`
--

CREATE TABLE `stud_image` (
  `stud_id` int(11) NOT NULL,
  `stud_img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stud_image`
--

INSERT INTO `stud_image` (`stud_id`, `stud_img`, `created`, `status`) VALUES
(202000043, '../studimg/1670665761.jpg', '2022-12-10 17:49:21', '1'),
(202000049, '../studimg/1669905759.jpg', '2022-12-01 22:42:40', '1'),
(202000056, '../studimg/1669905899.jpg', '2022-12-01 22:44:59', '1'),
(202000068, '../studimg/1669905666.jpg', '2022-12-01 22:41:06', '1');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(50) NOT NULL,
  `teacher_fname` varchar(100) NOT NULL,
  `teacher_mname` varchar(100) DEFAULT NULL,
  `teacher_lname` varchar(100) NOT NULL,
  `assigned_program` varchar(100) NOT NULL,
  `assigned_course` varchar(100) NOT NULL,
  `assigned_section` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `teacher_fname`, `teacher_mname`, `teacher_lname`, `assigned_program`, `assigned_course`, `assigned_section`, `date_created`) VALUES
(0, 'Geneses', 'Salinas', 'Cabradilla', 'NA', 'NA', 'NA', '2022-12-01 21:13:10'),
(1234567890, 'Richie', 'Agapito', 'Manreal', 'Bachelor of Science in Information System (BSIS)', 'IS104', '02', '2022-12-01 21:26:50'),
(1234567898, 'Riyadh', 'G', 'Cervantes', 'Bachelor of Science in Information System (BSIS)', 'ADV04', '03', '2022-12-01 22:06:34'),
(1234567899, 'Johanna', '', 'Ave', 'Bachelor of Science in Information System (BSIS)', 'ITE102', '02', '2022-12-01 22:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `timein`
--

CREATE TABLE `timein` (
  `stud_id` int(11) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `log_date` date NOT NULL DEFAULT current_timestamp(),
  `log_time` time NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timein`
--

INSERT INTO `timein` (`stud_id`, `course_code`, `log_date`, `log_time`, `status`) VALUES
(202000068, 'IS104', '2022-12-09', '02:54:02', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `timeout`
--

CREATE TABLE `timeout` (
  `stud_id` int(11) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `log_date` date DEFAULT NULL,
  `log_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timeout`
--

INSERT INTO `timeout` (`stud_id`, `course_code`, `log_date`, `log_time`) VALUES
(202000068, 'IS104', '2022-12-09', '02:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `teacher_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `usertype` varchar(100) NOT NULL DEFAULT 'user',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`teacher_id`, `username`, `email`, `password`, `usertype`, `date_created`) VALUES
(0, 'admin', 'genesescabradilla@gmail.com', '34819d7beeabb9260a5c854bc85b3e44', 'admin', '2022-12-01 21:15:45'),
(1234567890, 'richman', 'manreal.richieagapito@dfcamclp.edu.ph', '9675b8cc96289dff2b097e5c43d9f1eb', 'user', '2022-12-01 21:30:33'),
(1234567899, 'jave', 'ave.johanna@dfcamclp.edu.ph', '9675b8cc96289dff2b097e5c43d9f1eb', 'user', '2022-12-02 06:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `yandsec`
--

CREATE TABLE `yandsec` (
  `id` int(11) NOT NULL,
  `program` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `yandsec`
--

INSERT INTO `yandsec` (`id`, `program`, `course`, `year`, `section`, `date_created`) VALUES
(1, 'Bachelor of Science in Information System (BSIS)', 'IS104', '3rd Year', '01', '2022-12-01 21:44:13'),
(2, 'Bachelor of Science in Information System (BSIS)', 'ITE102', '1st Year', '02', '2022-12-01 21:44:13'),
(9, 'Bachelor of Science in Information System (BSIS)', 'ADV04', '2nd Year', '03', '2022-12-01 21:44:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entrance_log`
--
ALTER TABLE `entrance_log`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qrcode`
--
ALTER TABLE `qrcode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode_ibfk_1` (`stud_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stud_address`
--
ALTER TABLE `stud_address`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `stud_class`
--
ALTER TABLE `stud_class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `stud_class_ibfk_1` (`stud_id`);

--
-- Indexes for table `stud_image`
--
ALTER TABLE `stud_image`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `timein`
--
ALTER TABLE `timein`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `timeout`
--
ALTER TABLE `timeout`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `yandsec`
--
ALTER TABLE `yandsec`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qrcode`
--
ALTER TABLE `qrcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stud_class`
--
ALTER TABLE `stud_class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `yandsec`
--
ALTER TABLE `yandsec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `qrcode`
--
ALTER TABLE `qrcode`
  ADD CONSTRAINT `qrcode_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `status_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `stud_address`
--
ALTER TABLE `stud_address`
  ADD CONSTRAINT `stud_address_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `stud_class`
--
ALTER TABLE `stud_class`
  ADD CONSTRAINT `stud_class_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stud_image`
--
ALTER TABLE `stud_image`
  ADD CONSTRAINT `stud_image_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timein`
--
ALTER TABLE `timein`
  ADD CONSTRAINT `timein_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `timeout`
--
ALTER TABLE `timeout`
  ADD CONSTRAINT `timeout_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
