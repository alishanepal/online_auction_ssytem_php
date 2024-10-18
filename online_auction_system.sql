-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2024 at 05:50 AM
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
-- Database: `online_auction_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `antiques`
--

CREATE TABLE `antiques` (
  `antique_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `historical_period` varchar(100) DEFAULT NULL,
  `conditionn` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antiques`
--

INSERT INTO `antiques` (`antique_id`, `product_id`, `origin`, `historical_period`, `conditionn`) VALUES
(3, 15, 'russia', 'victorian', 'old vase'),
(4, 17, 'england', 'victorian', 'relatively okay'),
(5, 18, 'england', 'victorian', 'relatively okay');

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `auction_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`auction_id`, `product_id`, `start_date`, `end_date`, `status`) VALUES
(6, 12, '2022-12-20 08:20:00', '2023-07-20 06:10:00', 'closed'),
(7, 13, '2024-09-25 17:20:00', '2024-12-20 04:15:00', 'upcoming'),
(8, 14, '2024-09-25 11:10:00', '2024-11-20 17:45:00', 'live'),
(9, 15, '2024-09-20 22:50:00', '2024-09-30 05:00:00', 'live'),
(10, 16, '2024-09-20 12:51:00', '2024-09-21 03:00:00', 'upcoming'),
(11, 17, '2024-09-20 12:51:00', '2024-09-21 03:00:00', 'closed'),
(12, 18, '2024-09-20 12:51:00', '2024-09-21 03:00:00', 'live'),
(15, 21, '2024-09-21 10:16:00', '2024-09-21 15:00:00', 'live'),
(16, 22, '2024-09-22 13:45:00', '2024-09-22 14:20:00', 'upcoming');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(3, 'Antiques'),
(2, 'Jewelry'),
(1, 'Paintings');

-- --------------------------------------------------------

--
-- Table structure for table `jewelry`
--

CREATE TABLE `jewelry` (
  `jewelry_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `gemstones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jewelry`
--

INSERT INTO `jewelry` (`jewelry_id`, `product_id`, `material`, `weight`, `gemstones`) VALUES
(2, 14, 'diamond', 1000.00, 'daimond');

-- --------------------------------------------------------

--
-- Table structure for table `paintings`
--

CREATE TABLE `paintings` (
  `painting_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `artist` varchar(255) DEFAULT NULL,
  `technique` varchar(100) DEFAULT NULL,
  `year_created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paintings`
--

INSERT INTO `paintings` (`painting_id`, `product_id`, `artist`, `technique`, `year_created`) VALUES
(3, 12, 'Donatello', 'watercolor', 2001),
(4, 13, 'Salvador Dali', 'oil, watercolor', 2003),
(5, 16, 'Leonardo Da Vinci', 'Watercolor', 1999),
(8, 21, 'aliha', 'watercolor', 1999),
(9, 22, 'nalina', 'watercolor', 1999);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `starting_bid` decimal(10,2) NOT NULL,
  `reserve_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `minimum_price_interval` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `category_id`, `starting_bid`, `reserve_price`, `description`, `keywords`, `minimum_price_interval`) VALUES
(12, 'Girl with pearl earring', 1, 1000.00, 1200.00, 'This is a painting', 'picture,frame', 800.00),
(13, 'life in provence', 1, 1500.00, 1600.00, 'This is a painting', 'frame ', 800.00),
(14, 'L\'Incompara Diamond', 2, 2000.00, 2500.00, 'This is a jewelry', 'jewels', 1500.00),
(15, 'pocket watch', 3, 900.00, 1200.00, 'This is a antique piece', 'antique', 500.00),
(16, 'Monalisa', 1, 2000.00, 2200.00, 'This is a painting', 'painting', 1000.00),
(17, 'victorian lamp', 3, 2000.00, 2200.00, 'This is a antique', 'antique', 1000.00),
(18, 'vase', 3, 2000.00, 2200.00, 'This is a antique', 'antique', 1000.00),
(21, 'Cat', 1, 1400.00, 2000.00, 'thi is a cat painting', 'cat,painting', 100.00),
(22, 'painting', 1, 1500.00, 2000.00, 'thi ijkdbhjfd', 'painting', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `auction_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`, `auction_id`) VALUES
(6, 12, '../public/uploads/girl with perl earring.webp', NULL),
(7, 13, '../public/uploads/life in provence.jpg', NULL),
(8, 14, '../public/uploads/L\'Incomparable Diamond.png', NULL),
(9, 15, '../public/uploads/pocket_watch.jpg', NULL),
(10, 16, '../public/uploads/mona_lisa.jpg', NULL),
(11, 17, '../public/uploads/victorian lamp.jpg', NULL),
(12, 18, '../public/uploads/vase.jpg', NULL),
(13, 21, '../public/uploads/cat.jpg', NULL),
(14, 22, '../public/uploads/Screenshot 2024-09-22 093208.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcategory_id` int(11) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`subcategory_id`, `subcategory_name`, `product_id`, `category_id`) VALUES
(4, 'frame', NULL, 1),
(5, 'frame', NULL, 1),
(6, 'picture', NULL, 1),
(7, 'picture', NULL, 1),
(8, 'jewel', NULL, 2),
(9, 'old vase', NULL, 3),
(10, 'frame', NULL, 1),
(11, 'antique', NULL, 3),
(12, 'antique', NULL, 3),
(13, 'painting', 21, 1),
(14, 'painting', 22, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(1, 'alisha', 'alishanepal95@gmail.com', '$2y$10$i9O9xVLWspFCnRYJmUz9s.eWwkx2puu56bXGLwXoHUHjmPFiG4MTS', 'user'),
(2, 'nalina', 'alishanepal53@gmail.com', '$2y$10$2.QjDsMrLBaanJXZZV7Cc.x77/88MM51yr2wvhJUY3aSKCzMwOS6C', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antiques`
--
ALTER TABLE `antiques`
  ADD PRIMARY KEY (`antique_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `jewelry`
--
ALTER TABLE `jewelry`
  ADD PRIMARY KEY (`jewelry_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `paintings`
--
ALTER TABLE `paintings`
  ADD PRIMARY KEY (`painting_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_auction_id` (`auction_id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`subcategory_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antiques`
--
ALTER TABLE `antiques`
  MODIFY `antique_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jewelry`
--
ALTER TABLE `jewelry`
  MODIFY `jewelry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `paintings`
--
ALTER TABLE `paintings`
  MODIFY `painting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antiques`
--
ALTER TABLE `antiques`
  ADD CONSTRAINT `antiques_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `jewelry`
--
ALTER TABLE `jewelry`
  ADD CONSTRAINT `jewelry_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `paintings`
--
ALTER TABLE `paintings`
  ADD CONSTRAINT `paintings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_auction_id` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`),
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subcategory_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
