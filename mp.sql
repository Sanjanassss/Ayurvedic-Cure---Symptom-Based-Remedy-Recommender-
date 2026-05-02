-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db
-- Generation Time: Nov 29, 2024 at 09:20 PM
-- Server version: 5.6.51
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(30) NOT NULL,
  `admin_password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `admin_password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `connect`
--

CREATE TABLE `connect` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `message` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `Doc_name` varchar(30) NOT NULL,
  `Doc_email` varchar(100) NOT NULL,
  `doc_contact` varchar(12) NOT NULL,
  `type` varchar(20) NOT NULL,
  `info` varchar(255) NOT NULL,
  `tags` varchar(800) NOT NULL,
  `doc_photo` varchar(100) NOT NULL,
  `city` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `Doc_name`, `Doc_email`, `doc_contact`, `type`, `info`, `tags`, `doc_photo`, `city`) VALUES
(1, 'Dr. Vinay', 'vinay@example.com', '9828090421', 'Ayurveda Physician', 'BAMS', '                        teeth/tooth       ', 'doc/Dr.Vinay.jpg', 'Bangalore'),
(2, 'Dr. Rashmi V', 'rashmi@example.com', '9828096264', 'Ayurvedic Pathology', 'BAMS', '    breathe    head head-headche    neck      smell things    taste     vomit   ', 'doc/Dr.Rashmi.jpg', 'Davangere'),
(3, 'Dr. Syed Shamshuddin', 'syed@exmaple.com', '8696666966', 'Ayurveda', 'BAMS', ' assaulted              passurine            vagina vomit  weak ', 'doc/Dr.Syed.jpg', 'Davangere'),
(4, 'Dr. Zeba Kauser', 'zebakauser@example.com', '8526987456', 'Ayurveda Physician', 'BAMS', 'abdomen assaulted back blindness breathe chest cold/fever fracture head head-headche in a traffic accident limb nausea neck nosebleed passurine remember shot sleep smell things sprain stabbed stomach taste teeth/tooth tired torn cartilage vagina vomit walk weak write', 'doc/Dr.Zeba.jpg', 'Davangere'),
(5, 'Dr. Poojitha J', 'poojitha@exmaple.com', '8947698524', 'Ayurveda', 'BAMS', ' assaulted              passurine            vagina vomit  weak ', 'doc/Dr.Poojitha.jpg', 'Mysore'),
(6, 'Dr. Chaithralaxmi.K', 'chaithra@example.com', '8565856587', 'Ayurveda', 'BAMS', 'abdomen assaulted back blindness breathe chest cold/fever fracture head head-headche in a traffic accident limb nausea neck nosebleed passurine remember shot sleep smell things sprain stabbed stomach taste teeth/tooth tired torn cartilage vagina vomit walk weak write', 'doc/Dr.Chaithra.jpg', 'Bangalore'),
(7, 'Dr. C.S.Anilkumar', 'anilkumar@example.com', '8745685265', 'Naturopathy & Yogic ', 'BAMS, MD', 'abdomen assaulted back   chest cold/fever fracture head  in a traffic accident limb nausea  nosebleed passurine  shot sleep smell things sprain stabbed stomach     vagina vomit  weak ', 'doc/Dr.Anil.jpg', 'Mysore'),
(8, 'Dr. V Vijetha Shetty', 'vijethashetty@exapmle.com', '7898525486', 'Ayurveda', 'BAMS', '        head head-headche       remember  sleep  sprain   taste      walk  write', 'doc/Dr.Vijetha.jpg', 'Davangere');

-- --------------------------------------------------------

--
-- Table structure for table `security`
--

CREATE TABLE `security` (
  `id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `browser` varchar(50) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `security`
--

INSERT INTO `security` (`id`, `ip`, `browser`, `time`) VALUES
(1, '127.0.0.1', 'unknown', '2024/11/29 20:51:09'),
(2, '127.0.0.1', 'unknown', '2024/11/29 21:08:40'),
(3, '127.0.0.1', 'unknown', '2024/11/29 21:08:46'),
(4, '127.0.0.1', 'unknown', '2024/11/29 21:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `age` int(3) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `sec_que` varchar(255) NOT NULL,
  `sec_ans` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `signup`
--

INSERT INTO `signup` (`id`, `username`, `password`, `name`, `email`, `age`, `sex`, `city`, `sec_que`, `sec_ans`, `photo`) VALUES
(1, 'ayush', 'admin', 'admin', 'admin@gmail.com', 19, 'male', 'bangalore', 'who is your favourite teacher ?', 'google', 'profiles/admin.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connect`
--
ALTER TABLE `connect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security`
--
ALTER TABLE `security`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `connect`
--
ALTER TABLE `connect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `security`
--
ALTER TABLE `security`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
