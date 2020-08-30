-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2020 at 10:37 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `concierge_m`
--

-- --------------------------------------------------------

--
-- Table structure for table `trip_images`
--

CREATE TABLE `trip_images` (
  `id` int(11) NOT NULL,
  `trip_id_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_images`
--

INSERT INTO `trip_images` (`id`, `trip_id_id`, `image_url`) VALUES
(1, 1, 'Trip10.jpeg'),
(2, 1, 'Trip11.jpeg'),
(3, 1, 'Trip12.jpeg'),
(4, 1, 'Trip13.jpeg'),
(5, 1, 'Trip14.jpeg'),
(6, 1, 'Trip15.jpeg'),
(7, 2, 'Trip20.jpeg'),
(8, 2, 'Trip21.jpeg'),
(9, 2, 'Trip22.jpeg'),
(10, 2, 'Trip23.jpeg'),
(11, 2, 'Trip24.jpeg'),
(12, 2, 'Trip25.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `trip_images`
--
ALTER TABLE `trip_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F10E5C0A50F1E14` (`trip_id_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `trip_images`
--
ALTER TABLE `trip_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `trip_images`
--
ALTER TABLE `trip_images`
  ADD CONSTRAINT `FK_F10E5C0A50F1E14` FOREIGN KEY (`trip_id_id`) REFERENCES `viptrips` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
