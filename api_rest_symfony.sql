-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2019 at 09:15 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api_rest_symfony`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(150) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Alvaro', 'Torrente Perez', 'alvaro@alvaro.com', 'ee7d81103f122bb171ce1eb2b8da9b44403f2b2da7924b48b3fafe0ba36b5a81', 'ROLE_USER', '2019-10-30 18:15:21'),
(2, 'altopetin', 'Torrente', 'altope@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'ROLE_USER', '2019-10-24 19:46:08'),
(3, 'blas', 'lel', 'blas@gmail.com', '85c76dcae2299d2235c793fe3425bd88376f5c4721cfddc1c9a9c452c20c5d33', 'ROLE_USER', '2019-10-27 19:58:16'),
(4, 'franja', 'lloron', 'franjarrentemotorsport@gmail.com', 'b450ca88e9e2e0dbfab0dad6f8b7eaa883d9f51040de5cff2c39f9323a1a8bbc', 'ROLE_USER', '2019-10-27 20:04:28'),
(5, 'AlvaroSuper', 'Torrente', 'alvaro@gmail.com', 'ee7d81103f122bb171ce1eb2b8da9b44403f2b2da7924b48b3fafe0ba36b5a81', 'ROLE_USER', '2019-10-28 20:53:17'),
(6, 'LuisTontin', 'Perez', 'tontini@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'ROLE_USER', '2019-10-29 08:51:07'),
(8, 'dvsv', 'bgbfgb', 'vds@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'ROLE_USER', '2019-11-11 20:17:40'),
(9, 'asdas', 'frerfr', 'ewfef@gmail.com', 'd7375ff537a24b1cc6b63b6fd4942a9c1483841357e34ae57969b4e04f0be109', 'ROLE_USER', '2019-11-11 20:53:35'),
(10, 'ascs', 'fdvfdb', 'thrthtrh@gmail.com', 'fb2cca58dae932e1d6f658b2844ef507a1178669e87fc795039f664abb6a0496', 'ROLE_USER', '2019-11-11 20:56:05');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `user_id`, `title`, `description`, `url`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, 'Aprende PHP', 'Este es un breve tutorial', 'https://www.youtube.com/watch?v=OK_JCtrrv-c', 'normal', '2019-10-30 18:16:39', '2019-10-30 18:45:05'),
(5, 1, 'Evolution IX', 'Evo Ix', 'https://www.youtube.com/watch?v=K_pVtxrWydE', 'normal', '2019-10-30 18:17:32', '2019-10-30 18:17:32'),
(6, 1, 'Mitsubishi eclipse', 'Linea de escape', 'https://www.youtube.com/watch?v=bIWLbDZZz-c', 'normal', '2019-11-12 14:08:27', '2019-11-12 20:08:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_video_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `fk_video_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
