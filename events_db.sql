-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 27, 2024 at 10:53 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `events_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `event_id` int(255) NOT NULL,
  `event_date` date NOT NULL,
  `services` text NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_name`, `event_id`, `event_date`, `services`, `payment_method`, `created_at`) VALUES
(1, 'ast', 1, '2024-03-30', 'Full Services, FoodAndDrinks', 'credit-card', '2024-03-27 06:47:16'),
(2, 'ast', 1, '2024-03-30', 'Full Services, FoodAndDrinks', 'credit-card', '2024-03-27 06:47:57'),
(3, 'ast', 1, '2024-03-30', 'Full Services, FoodAndDrinks', 'credit-card', '2024-03-27 06:49:58'),
(4, 'ast', 1, '2024-03-30', 'Full Services, FoodAndDrinks', 'credit-card', '2024-03-27 06:50:14'),
(5, '123', 1, '2024-03-30', 'Decorations, InvitationCard', 'credit-card', '2024-03-27 06:50:51'),
(6, '123', 1, '2024-03-30', 'Decorations, InvitationCard', 'credit-card', '2024-03-27 06:53:24'),
(7, '123', 1, '2024-03-30', 'Decorations, InvitationCard', 'credit-card', '2024-03-27 06:53:52'),
(8, '123', 1, '2024-03-30', 'Decorations, InvitationCard', 'credit-card', '2024-03-27 06:53:52'),
(9, '123', 4, '2024-03-30', 'InvitationCard', 'bank-transfer', '2024-03-27 06:54:04'),
(10, '123', 4, '2024-03-30', 'InvitationCard', 'bank-transfer', '2024-03-27 06:54:04'),
(11, 'ast', 4, '2024-03-30', 'Decorations', 'credit-card', '2024-03-27 06:58:52'),
(12, 'ast', 3, '2024-03-30', 'Decorations, MusicAndPhotos, InvitationCard', 'bank-transfer', '2024-03-27 07:01:41'),
(13, '123', 1, '2024-03-30', 'FoodAndDrinks, InvitationCard', 'bank-transfer', '2024-03-27 07:42:38'),
(14, '123', 1, '2024-03-30', '', 'credit-card', '2024-03-27 07:50:29'),
(15, 'user', 3, '2024-03-30', 'Full Services, MusicAndPhotos, InvitationCard', 'bank-transfer', '2024-03-27 07:55:31'),
(16, 'ghhfhg', 1, '2024-07-26', 'Decorations, MusicAndPhotos', 'bank-transfer', '2024-03-27 16:06:19'),
(17, 'astpreet', 4, '2024-03-30', 'MusicAndPhotos, FoodAndDrinks', 'paypal', '2024-03-27 20:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `services` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `price`, `services`) VALUES
(1, 'party', 600.00, 'Food And Drinks, Invitation Card'),
(2, 'birthday', 299.00, 'Food And Drinks, Invitation Card'),
(3, 'wedding', 499.00, 'Music And Photos, Food And Drinks, Invitation Card'),
(4, 'Concert', 699.00, 'Food And Drinks, Invitation Card'),
(5, 'baby shower', 555.00, 'Food And Drinks'),
(6, ' Singing shows', 4000.00, 'Music And Photos, Food And Drinks, Invitation Card');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, '123', '123@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'user'),
(2, 'ast', 'ast@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'user'),
(3, 'ast', 'astpreet8522@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'admin'),
(4, 'mandeep', 'mandeepgill5266@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'user'),
(5, 'user', 'user@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'user'),
(6, 'admin', 'admin@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'user'),
(7, 'ghhfhg', 'g@g.com', 'e10adc3949ba59abbe56e057f20f883e', 'user'),
(8, 'astpreet', 'ast1@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
