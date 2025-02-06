-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 05:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movies_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `poster_url` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `summary` varchar(1000) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `year`, `poster_url`, `video_url`, `summary`, `slug`) VALUES
(1, 'Den of Thieves 2: Pantera', 2025, 'Den of Thieves 2 Pantera.jpg', 'https://www.youtube.com/embed/rWsnLS0Q7G0', 'Kaluna, a middle-class worker living with her parents and married siblings, dreams of owning her own house. However, supporting her extended family on a minimal income leaves her feeling out of place at home.', 'Den-of-Thieves-2-Pantera'),
(3, 'Grafted', 2024, 'Grafted.jpg', 'https://www.youtube.com/embed/rWsnLS0Q7G0', 'Kaluna, a middle-class worker living with her parents and married siblings, dreams of owning her own house. However, supporting her extended family on a minimal income leaves her feeling out of place at home.', 'Grafted'),
(4, 'American Graffiti', 2025, 'American Graffiti.jpg', 'https://www.youtube.com/embed/rWsnLS0Q7G0', 'Kaluna, a middle-class worker living with her parents and married siblings, dreams of owning her own house. However, supporting her extended family on a minimal income leaves her feeling out of place at home.', 'American-Graffiti'),
(5, 'Star Trek: Section 31', 2025, 'Star Trek Section 31.jpg', 'https://www.youtube.com/embed/rWsnLS0Q7G0', 'Kaluna, a middle-class worker living with her parents and married siblings, dreams of owning her own house. However, supporting her extended family on a minimal income leaves her feeling out of place at home.', 'Star-Trek-Section-31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES
(36, 'admin', 'ilhamoktavian74@gmail.com', '$2y$10$zyi.OXT0Uvb..mB4hsnLQ.lMeM08KtfRd9Zt9my/L5MaJoJW/ZK/G'),
(37, 'ilhamoktavian74@gmail.com', 'ilhamoktavian741@gmail.com', '$2y$10$1F.4328ufaJMCotsBiXaEuZG3SYBlHsSIquuxRZYwqyXPxVM1OcEm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
