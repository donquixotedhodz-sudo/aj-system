-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 12:42 PM
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
-- Database: `san_rafael_cave_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_reference` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expedition_id` int(11) NOT NULL,
  `tour_date` date NOT NULL,
  `person1_name` varchar(100) NOT NULL,
  `person2_name` varchar(100) DEFAULT NULL,
  `person3_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `payment_proof` varchar(500) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_reference`, `user_id`, `expedition_id`, `tour_date`, `person1_name`, `person2_name`, `person3_name`, `contact_email`, `contact_phone`, `payment_proof`, `total_amount`, `booking_status`, `special_requests`, `created_at`, `updated_at`) VALUES
(1, 'SRC-202509-0001', 2, 1, '2025-09-10', 'Josh McDowell Trapal', 'Ann Marisse Cuya', 'AJ Nicole Salamente', 'joshmcdowelltrapal@gmail.com', '09958714112', 'uploads/payment_proofs/payment_68bbd94ec19c7_1757141326.jpg', 405.00, 'pending', NULL, '2025-09-06 06:48:46', '2025-09-06 07:56:06'),
(2, 'SRC-202509-0002', 5, 1, '2025-09-09', 'Angel lamadrid', 'AJ Nicole', 'Anica', 'joshmcdowelltrapal12@gmail.com', '09958714113', 'uploads/payment_proofs/payment_68bbdbea6280c_1757141994.jpg', 405.00, 'pending', NULL, '2025-09-06 06:59:54', '2025-09-06 07:56:06'),
(3, 'SRC-202509-0003', 5, 1, '2025-09-09', 'Josh McDowell Trapal', 'AJ Nicole', 'Angel Lamadrid', 'amcuya@gmail.com', '09958714115', 'uploads/payment_proofs/payment_68bbdd01d9ee6_1757142273.jpg', 405.00, 'pending', NULL, '2025-09-06 07:04:33', '2025-09-06 07:56:06'),
(4, 'SRC-202509-0004', 6, 1, '2025-09-24', 'AJ Nicole Salamente', 'Aica Shannara', 'Aelred Salamente', 'salamenteanicajane@gmail.com', '09958714113', 'uploads/payment_proofs/payment_68bea5b18d15b_1757324721.jpg', 405.00, 'pending', NULL, '2025-09-08 09:45:21', '2025-09-08 09:45:21');

-- --------------------------------------------------------

--
-- Table structure for table `cave_explorations`
--

CREATE TABLE `cave_explorations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cave_explorations`
--

INSERT INTO `cave_explorations` (`id`, `name`, `price`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Beginner Cave Tour', 300.00, 'assets/images/expedition-1.jpg', '2025-09-06 05:20:05', '2025-09-06 05:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `purpose` enum('signup','login','password_reset') NOT NULL DEFAULT 'signup',
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `email`, `otp_code`, `purpose`, `expires_at`, `is_used`, `created_at`) VALUES
(1, 'joshmcdowelltrapal@gmail.com', '497170', 'signup', '2025-09-06 06:53:40', 1, '2025-09-06 04:43:40'),
(2, 'ccisd2024@gmail.com', '572749', 'signup', '2025-09-06 06:58:49', 0, '2025-09-06 04:48:49'),
(3, 'joshmcdowelltrapal@gmail.com', '682315', 'signup', '2025-09-06 07:00:46', 0, '2025-09-06 04:50:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'AJ Nicole Salamente', 'ajnicolesalamente@gmail.com', '$2y$10$qd8YHNHHU13BGUsms5CytugcT/2Z3eTQ/jZXzHBtRkA9BpQM7f7DW', NULL, '2025-09-06 03:21:32', '2025-09-06 03:21:32'),
(2, 'Josh McDowell Trapal', 'joshmcdowelltrapal@gmail.com', '$2y$10$uA4zZsDayud.5kRCiTkVZOD0f0BY.t0NNyIPNRV7E1p33c80Zjf32', NULL, '2025-09-06 04:56:00', '2025-09-06 04:56:00'),
(4, 'Angel Lamadrid', 'angellamadrid@gmail.com', '$2y$10$3dxVtDsMBTwjhw719odm7esJWSyLZncaUJJP1OvOCR.Bet82tJobK', NULL, '2025-09-06 05:07:17', '2025-09-06 05:07:17'),
(5, 'Ann Marisse Cuya', 'annmar@gmail.com', '$2y$10$WuMpg97hglgrnim.5PnjGeuaRPbXyEJS51f2zNwuq/89Baqs3NW5K', '../uploads/profiles/profile_5_1757144787.jpg', '2025-09-06 05:08:03', '2025-09-06 07:51:03'),
(6, 'Anica Jane Salamente', 'salamenteanicajane@gmail.com', '$2y$10$7YcrVmKL5PN4eiB.0AkoAOB18nxWErBM1nni2C8SM747ZGTu67uQe', NULL, '2025-09-08 09:32:40', '2025-09-08 09:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `expedition_id` (`expedition_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_booking_reference` (`booking_reference`);

--
-- Indexes for table `cave_explorations`
--
ALTER TABLE `cave_explorations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cave_explorations`
--
ALTER TABLE `cave_explorations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`expedition_id`) REFERENCES `cave_explorations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
