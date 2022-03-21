-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 24, 2021 at 07:31 AM
-- Server version: 5.7.30
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `white_shop`
--
CREATE DATABASE IF NOT EXISTS `white_shop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `white_shop`;

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `attribute_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`attribute_id`, `name`, `description`) VALUES
(1, 'Material', 'Prperties cloths or shoes are made in'),
(2, 'Color', 'Black'),
(3, 'Size', 'Length');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `attribute_value_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`attribute_value_id`, `attribute_id`, `value`) VALUES
(1, 2, 'Black'),
(2, 2, 'Maroon'),
(3, 2, 'White'),
(4, 3, 'S'),
(5, 3, 'M'),
(6, 3, 'Xl'),
(7, 3, 'XXL');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `product_type_id`, `category`, `status`) VALUES
(1, 1, 'Babies', 1),
(2, 2, 'Girls', 1),
(3, 2, 'Men', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_gender`
--

CREATE TABLE `category_gender` (
  `category_gender_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `gender_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_gender`
--

INSERT INTO `category_gender` (`category_gender_id`, `category_id`, `gender_id`, `status`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_size`
--

CREATE TABLE `category_size` (
  `category_size_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `size` varchar(20) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_size`
--

INSERT INTO `category_size` (`category_size_id`, `category_id`, `size`, `status`) VALUES
(1, 1, '25-36', 1),
(2, 2, '37-41', 1),
(3, 3, '40-45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`collection_id`, `name`) VALUES
(1, 'Summaer collection'),
(2, 'Winte collection');

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `gender_id` int(11) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`gender_id`, `gender`, `status`) VALUES
(1, 'Male', 1),
(2, 'Women', 1);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` varchar(255) NOT NULL,
  `trader_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_img` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `trader_id`, `category_id`, `product_name`, `product_img`, `reg_date`, `status`) VALUES
('10fad37c-5d25-4cd3-bd6e-0cdc3e1e5825', 1, 1, 'rtyiuoio', '1618118979blaptopbag2.jpg', '2021-04-11 07:29:39', 0),
('222e4fa8-77e6-4584-a348-d1f632d7da1a', 1, 2, 'Emma shoes man', '1618079589Shoes-Kids-Boys.webp', '2021-04-10 20:33:09', 0),
('4422c9df-3950-48f8-8f32-6dfc9124dde6', 1, 2, 'Phone', '1618337479blaptopbag2.jpg', '2021-04-13 20:11:20', 0),
('4a13ba28-f76b-4968-bb12-d610002893ed', 1, 2, 'gjhbkj', '1618169901comeka.webp', '2021-04-11 21:38:21', 0),
('4f60ce5b-b57f-4f9e-9195-153009d2f6c4', 1, 2, 'Car', '1618337725blaptopbag2.jpg', '2021-04-13 20:15:25', 0),
('5c73c069-06ae-49a7-a5ac-dff24183794e', 1, 2, 'Car', '1618479369decor-3.JPG', '2021-04-15 11:36:09', 0),
('77b8a8f7-ca3b-4f11-90e8-425cd028d4c6', 1, 2, 'Jshop sneakers for men', '1618076783light-shoes.jpeg', '2021-04-10 19:46:24', 0),
('81949c52-57fe-4555-82dd-76b255ff1e6d', 1, 1, 'Polo', '1618119962Kids-Boys-Sandals.webp', '2021-04-11 07:46:02', 0),
('a4478f93-ff18-41af-a6a4-82609b1dd423', 1, 2, 'Best snacks shoes', '1618759642jakob-owens-JzJSybPFb3s-unsplash.jpg', '2021-04-18 17:27:22', 1),
('b85b0e7a-c6cf-4059-b09d-fd8d430cded6', 1, 2, 'Canvas', '1618119563comeka.webp', '2021-04-11 07:39:23', 0),
('ccb14959-5a55-4bd0-b75e-aab8468c8026', 1, 2, 'sneakers', '1618081152Shoes-Kids-Boys.webp', '2021-04-10 20:59:12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `pavalue_id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `attribute_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`pavalue_id`, `product_id`, `attribute_value_id`) VALUES
(1, '77b8a8f7-ca3b-4f11-90e8-425cd028d4c6', 1),
(2, '77b8a8f7-ca3b-4f11-90e8-425cd028d4c6', 2),
(3, '77b8a8f7-ca3b-4f11-90e8-425cd028d4c6', 3),
(4, '222e4fa8-77e6-4584-a348-d1f632d7da1a', 1),
(5, '222e4fa8-77e6-4584-a348-d1f632d7da1a', 2),
(6, '222e4fa8-77e6-4584-a348-d1f632d7da1a', 3),
(7, 'ccb14959-5a55-4bd0-b75e-aab8468c8026', 2),
(8, 'ccb14959-5a55-4bd0-b75e-aab8468c8026', 4),
(9, '4422c9df-3950-48f8-8f32-6dfc9124dde6', 1),
(10, '4422c9df-3950-48f8-8f32-6dfc9124dde6', 3),
(11, '4422c9df-3950-48f8-8f32-6dfc9124dde6', 6),
(12, '4f60ce5b-b57f-4f9e-9195-153009d2f6c4', 2),
(13, '4f60ce5b-b57f-4f9e-9195-153009d2f6c4', 7),
(14, '5c73c069-06ae-49a7-a5ac-dff24183794e', 1),
(15, '5c73c069-06ae-49a7-a5ac-dff24183794e', 5);

-- --------------------------------------------------------

--
-- Table structure for table `product_price`
--

CREATE TABLE `product_price` (
  `product_price_id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_price` double NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_price`
--

INSERT INTO `product_price` (`product_price_id`, `product_id`, `product_price`, `reg_date`, `status`) VALUES
(1, '77b8a8f7-ca3b-4f11-90e8-425cd028d4c6', 5000, '2021-04-10 19:46:24', 1),
(2, '222e4fa8-77e6-4584-a348-d1f632d7da1a', 10000, '2021-04-10 20:33:09', 1),
(3, 'ccb14959-5a55-4bd0-b75e-aab8468c8026', 5002, '2021-04-10 20:59:12', 1),
(6, '10fad37c-5d25-4cd3-bd6e-0cdc3e1e5825', 12345, '2021-04-11 07:29:39', 1),
(7, 'b85b0e7a-c6cf-4059-b09d-fd8d430cded6', 5000, '2021-04-11 07:39:23', 1),
(8, '81949c52-57fe-4555-82dd-76b255ff1e6d', 500, '2021-04-11 07:46:02', 1),
(9, '4a13ba28-f76b-4968-bb12-d610002893ed', 5000, '2021-04-11 21:38:22', 1),
(10, '4422c9df-3950-48f8-8f32-6dfc9124dde6', 20000, '2021-04-13 20:11:20', 1),
(11, '4f60ce5b-b57f-4f9e-9195-153009d2f6c4', 5000, '2021-04-13 20:15:25', 1),
(12, '5c73c069-06ae-49a7-a5ac-dff24183794e', 400, '2021-04-15 11:36:10', 1),
(13, 'a4478f93-ff18-41af-a6a4-82609b1dd423', 20000, '2021-04-18 17:27:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `product_type_id` int(11) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `status` int(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`product_type_id`, `product_type`, `status`) VALUES
(1, 'Clothing', 1),
(2, 'Shoes', 1),
(3, 'Bages', 1);

-- --------------------------------------------------------

--
-- Table structure for table `traders`
--

CREATE TABLE `traders` (
  `trader_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `gender` varchar(8) NOT NULL,
  `location` int(11) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `possword` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE `versions` (
  `versions` int(11) NOT NULL,
  `version` varchar(255) NOT NULL,
  `status` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`attribute_id`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`attribute_value_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `category_gender`
--
ALTER TABLE `category_gender`
  ADD PRIMARY KEY (`category_gender_id`);

--
-- Indexes for table `category_size`
--
ALTER TABLE `category_size`
  ADD PRIMARY KEY (`category_size_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`gender_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`pavalue_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_price`
--
ALTER TABLE `product_price`
  ADD PRIMARY KEY (`product_price_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`product_type_id`);

--
-- Indexes for table `traders`
--
ALTER TABLE `traders`
  ADD PRIMARY KEY (`trader_id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`versions`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `attribute_value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `category_gender`
--
ALTER TABLE `category_gender`
  MODIFY `category_gender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category_size`
--
ALTER TABLE `category_size`
  MODIFY `category_size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `gender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `pavalue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_price`
--
ALTER TABLE `product_price`
  MODIFY `product_price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `product_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE `traders`
  MODIFY `trader_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `versions` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_price`
--
ALTER TABLE `product_price`
  ADD CONSTRAINT `product_price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
