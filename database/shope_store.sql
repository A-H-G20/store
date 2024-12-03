-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 03, 2024 at 08:17 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shope_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(7, 5, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `status`, `order_date`, `full_name`, `address`, `city`, `postal_code`, `country`) VALUES
(2, 5, 8, 3, 'delivered', '2024-12-03 08:10:56', 'ali', 'Rua Caio Jos de Miranda 542\r\nAa', 'Bierut', '0000', 'Lebanon');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `description`, `image1`, `image2`, `created_at`) VALUES
(7, 'iphone 16', 1500.00, 'Built for Apple Intelligence. Camera Control. 48MP Fusion camera. Five vibrant colors. A18 chip.', '6734df95cb694_3.jpg', '6734df95cbb29_4.jpg', '2024-11-13 17:19:17'),
(6, 'camera canon', 23.00, 'Speedlite Transmitter ST-E3-RT (Ver.3) High-Performance', '6734deef6cb66_1.jpg', '6734deef6ce4c_2.jpg', '2024-11-13 17:16:31'),
(8, 'Hp Laptop', 399.00, 'POWER TO DO WHAT MATTERS MOST - Let your PC do even more with Intel Core i5 processors. The processing power of a 13th Generation Intel Core i5-1334Upaired with ample storage and powerful graphics give you the power and capacity to do more.', '6734e0187770a_5.webp', '6734e01877a41_6.jpg', '2024-11-13 17:21:28'),
(9, 'Fitbit Smartwatch', 199.00, 'Get inspired and stay accountable with Versa 4 + Premium - learn when to work out or recover, see real-time stats during exercise and find new ways to keep your routine fresh and fun.Operating temperature: -14° to 113°F.', '6734e08f38ef3_7.webp', '6734e08f39120_8.jpg', '2024-11-13 17:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `gender`, `address`, `phone_number`, `password`) VALUES
(6, 'admin', 'admin540', 'admin@gmail.com', 'admin', 'Male', 'lebanon', '03123654', '$2y$10$NYVkwL3u6gKQNac3NS2KLeQQ0y3CXWjd.NOf/PFyy709KGjKDsvYK'),
(7, '', 'ahmad12', 'admin1@gmail.com', 'admin', NULL, NULL, NULL, '$2y$10$VcRRivzjCsWzPQ0xq.a1cOwlGpG9sGf9hYubirWpzkhbPpv8FAIPW'),
(5, 'user', 'user537', 'user@gmail.com', 'user', 'Male', 'lebanon', '03147852', '$2y$10$YKFj24W/PLNkZROjmC3eou1NY/QtvQvzAbUxt5dOHUsjtSY1f2zT2');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
