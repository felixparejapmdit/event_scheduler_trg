-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 10:05 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduler_trg`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Birthdays'),
(2, 'Anniversary'),
(3, 'Buwanang Pulong'),
(4, 'TRG Activities'),
(5, 'Holidays'),
(6, 'VSWS'),
(7, 'Weekly Meeting Schedule'),
(8, 'Weekly Visitation'),
(9, 'WS Suguan Reminders');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(150) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `incharge` varchar(100) DEFAULT NULL,
  `contact_number` varchar(25) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `goals` text DEFAULT NULL,
  `daily_notes` varchar(250) DEFAULT NULL,
  `weeknumber` int(11) DEFAULT NULL,
  `prepared_by` varchar(50) DEFAULT NULL,
  `submitted_by` varchar(50) DEFAULT NULL,
  `event_type` int(2) DEFAULT NULL,
  `details` varchar(500) DEFAULT NULL,
  `is_display` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `title`, `incharge`, `contact_number`, `host`, `date`, `time`, `start_time`, `end_time`, `location`, `district`, `goals`, `daily_notes`, `weeknumber`, `prepared_by`, `submitted_by`, `event_type`, `details`, `is_display`) VALUES
(3, '4', 'Pamamahagi ng Pasugo at Polyeto', 'Medel Salise Jr', '@MedelSalise01', '', '2024-06-02', '17:00:00', NULL, NULL, 'Lokal ng NOVALICHES, QC', '', NULL, NULL, 0, '1', NULL, 1, '', 1),
(7, '1', 'BIRTHDAY CELEBRANT (TODAY)', 'Alvin David', 'https://t.me/alvinkyle', '', '2024-05-15', '07:00:00', NULL, NULL, '', '', NULL, NULL, 0, '1', NULL, 1, 'Belated Happy Birthday po!!!', 1),
(8, '4', 'HERITAGE TOUR', 'Gloverick Parungao', '@Gloverick', '', '2024-05-25', '07:00:00', NULL, NULL, 'MUSEUM, PUNTA STA ANA, TIPAS, PHIL ARENA', '', NULL, NULL, 0, '1', NULL, 1, '', 1),
(9, '4', 'Batarisang Paglilinis', 'Ka RTG', '@rondg314159', '', '2024-05-30', '08:00:00', NULL, NULL, 'TRG OFFICE', '', NULL, NULL, 0, '1', NULL, 1, '', 1),
(10, '4', 'PAMAMAHAYAG ', 'Medel Salise Jr.', '@MedelSalise', '', '2024-06-03', '20:00:00', NULL, NULL, 'Novaliches, QC', '', NULL, NULL, 0, '1', NULL, 1, '', 1),
(11, '7', 'Panata at Pulong', 'Ka RTG', '@rtg', '', '2024-05-17', '07:00:00', NULL, NULL, 'TRG OFFICE', '', NULL, NULL, 0, '1', NULL, 1, 'All Ministro at Mwa', 1),
(12, 'Others', 'Paalala po!!!', 'Ka RTG', 'https://t.me/rondg314159', '', '2024-05-17', '07:00:00', NULL, NULL, 'TRG OFFICE', '', NULL, NULL, 0, '1', NULL, 1, '1.Huwag po natin malimutan Lumagda sa Weekends Suguan at Weekly Visitation po\r\n\r\n2.Tiyakin po natin nakapag la log in-out tayo sa logbook pag ginagamit natin ang mga Sasakyan/Ibalik ang mga Susi sa lalagyan at sa naka assign na parking po\r\n                                                                ', 1),
(13, '1', 'Advanced Happy Birthday po!!!', 'Jester Niko NIcolas', 'https://t.me/jndnicolas', '', '2024-05-23', '12:00:00', NULL, NULL, '', '', NULL, NULL, 0, '1', NULL, 1, 'May 23, 2024', 1),
(14, 'Others', 'Reminders', 'Ka Ron de Guzman', '@rondg314159', '', '2024-05-20', '07:00:00', NULL, NULL, 'TRG Office', '', NULL, NULL, 0, NULL, NULL, 1, 'Weekly Meeting (all Mtro/Mwa) @01:30PM', 0),
(15, '7', 'Weekly Meeting', 'Ka Ron de Guzman', '@rondg314159', '', '2024-05-20', '13:30:00', NULL, NULL, '1', '', NULL, NULL, 0, '1', NULL, 1, 'All Mtro @ Mwa', 1),
(24, 'Others', 'Please Read!!!', 'HD', '@trg_hdAd', '', '2024-05-22', '07:00:00', NULL, NULL, 'TRG OFFICE', '', NULL, NULL, 0, '1', NULL, 1, '1. Paalala po mag log in/out po tayo pag ginagamit po natin ang ating mga Service Vehicles.\r\n2.Ibalik po natin o Iwan po natin ang mga Susi ng sasakyan..\r\n3. Ilagay po sa Tamang Parking.\r\n                                                Salamat po...', 1),
(25, 'Suguan', 'Felix Pareja', '', '', '', '2024-05-22', '19:30:00', NULL, NULL, 'Deparo', 'Caloocan North', NULL, NULL, 0, '1', NULL, 2, '3', 1),
(27, 'Suguan', 'Kim Amaro', '', '', NULL, '2024-05-23', '19:45:00', NULL, NULL, 'Centerville Extension', 'Central', NULL, NULL, 0, '1', NULL, 2, '3', 1),
(28, 'Suguan', 'Kim Amaro', NULL, NULL, NULL, '2024-05-26', '06:00:00', NULL, NULL, 'Bagbag Extension', 'Central', NULL, NULL, 0, '1', NULL, 2, '3', 1),
(29, 'Suguan', 'Felix Pareja', NULL, NULL, NULL, '2024-05-26', '10:00:00', NULL, NULL, 'Maligaya', 'Caloocan North', NULL, NULL, 0, '1', NULL, 2, '4', 1);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`) VALUES
(1, 'TRG Conference room'),
(2, 'ECD Office'),
(3, 'SFM - TRG Satellite Office');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'VIP'),
(3, 'ATG Office'),
(4, 'ATG Office SFM'),
(5, 'Graphics Enews'),
(6, 'VSS'),
(7, 'Archiving'),
(8, 'Music Organ'),
(9, 'Music Equipment'),
(10, 'INC Music'),
(11, 'Music - Original Music'),
(12, 'Building Admin/Utilities');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `contact` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(11) NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `name`, `profile_photo`, `email`, `email_verified_at`, `contact`, `password`, `role`, `department`, `section`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '', 'admin@gmail.com', NULL, 3923, 'M@sunur1n', 1, '', '1', NULL, '2023-10-09 23:31:43', '2023-10-31 07:00:44'),
(14, 'atg', 'Antonio de Guzman Jr.', '', 'atg@gmail.com', NULL, 0, '@tg', 3, '', '2', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(15, 'fmpareja', 'Felix Pareja', '', 'felixpareja_pmdit07@gmail.com', NULL, 3923, 'M@sunur1n', 1, '', '1', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(16, 'kjurada', 'Kyrt Jurada', '', 'kjurada@gmail.com', NULL, 3923, 'M@sunur1n', 1, '', '1', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(17, 'kamaro', 'Kim Amaro', '', 'kamaro@gmail.com', NULL, 3923, 'M@sunur1n', 1, '', '1', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(18, 'claudsarro', 'Claudio Arro Jr', '', 'claudsarro@gmail.com', NULL, 3971, 'k@tapatan', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(19, 'cmcervantes', 'Christian Marco Cervantes', '', 'cmcervantes@gmail.com', NULL, 3982, 'kat@patan', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(20, 'kvdematera', 'Karl Victor Dematera', '', 'kvdematera@gmail.com', NULL, 3981, 'inc1914', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(21, 'amagpile', 'Adriel Magpile', '', 'amagpile@gmail.com', NULL, 3986, 'katap@tan', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(22, 'rmmalabanan', 'Ryan Matthew Malabanan', '', 'rmmalabanan@gmail.com', NULL, 3983, 'katapat@n', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(23, 'mcenriquez', 'Michael Enriquez', '', 'menriquez@gmail.com', NULL, 123, 'k@tapatan1', 2, '', '4', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(24, 'pcdiaz', 'Philippe Christian Diaz', '', 'pcdiaz@gmail.com', NULL, 3961, 'kat@patan1', 2, '', '5', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(25, 'dvillamarzo', 'Donny Villamarzo', '', 'dvillamarzo@gmail.com', NULL, 3951, 'katap@tan1', 2, '', '6', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(26, 'jcabacungan', 'Jeffrey Cabacungan', '', 'jcabacungan@gmail.com', NULL, 3941, 'katapat@n1', 2, '', '7', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(28, 'jsarmiento', 'Jeffrey Sarmiento', '', 'jsarmiento@gmail.com', NULL, 3831, 'k@tapatan2', 2, '', '8', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(29, 'kdeleon', 'Karlo De Leon', '', 'kdeleon@gmail.com', NULL, 3821, 'kat@patan2', 2, '', '9', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(30, 'jalcantara', 'Jake Alcantara', '', 'jalcantara@gmail.com', NULL, 3841, 'katap@tan2', 2, '', '10', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(31, 'dpareña', 'Donald Pareña', '', 'dparena@gmail.com', NULL, 3861, 'katapat@n2', 2, '', '11', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(32, 'rporcado', 'Rodeson Porcado', '', 'rporcado@gmail.com', NULL, 3881, 'k@tapatan3', 2, '', '12', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(33, 'rdprestoza', 'Robert Darl Prestoza', '', 'rdprestoza@gmail.com', NULL, 0, 'kat@patan3', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44'),
(40, 'guest', 'guest', '', 'guest@gmail.com', NULL, 0, 'katapatan', 0, '', '1', NULL, NULL, NULL),
(43, 'atg1', 'ATG', '', 'atg@yahoo.com', NULL, 0, 'atg', 2, '', '3', NULL, '2023-12-27 18:34:44', '2023-12-27 18:34:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
