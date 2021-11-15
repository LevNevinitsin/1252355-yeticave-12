-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for yeticave
CREATE DATABASE IF NOT EXISTS `yeticave` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `yeticave`;

-- Dumping structure for table yeticave.bids
CREATE TABLE IF NOT EXISTS `bids` (
  `bid_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid_price` decimal(10,2) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `bid_date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bid_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.bids: ~4 rows (approximately)
/*!40000 ALTER TABLE `bids` DISABLE KEYS */;
INSERT INTO `bids` (`bid_id`, `bid_price`, `user_id`, `item_id`, `bid_date_created`) VALUES
	(1, 12000.33, 1, 1, '2021-10-27 18:07:21'),
	(2, 13000.60, 2, 1, '2021-10-27 18:07:21'),
	(3, 15000.99, 1, 4, '2021-10-27 18:07:21'),
	(4, 17000.25, 2, 4, '2021-10-27 18:07:21');
/*!40000 ALTER TABLE `bids` ENABLE KEYS */;

-- Dumping structure for table yeticave.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `category_code` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`),
  UNIQUE KEY `category_code` (`category_code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.categories: ~6 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`category_id`, `category_name`, `category_code`) VALUES
	(1, 'Доски и лыжи', 'boards'),
	(2, 'Крепления', 'attachment'),
	(3, 'Ботинки', 'boots'),
	(4, 'Одежда', 'clothing'),
	(5, 'Инструменты', 'tools'),
	(6, 'Разное', 'other'),
	(7, 'Чехлы и сумки', 'bags');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table yeticave.items
CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `item_description` text,
  `item_image` varchar(100) DEFAULT NULL,
  `item_initial_price` decimal(10,2) unsigned NOT NULL,
  `item_bid_step` decimal(10,2) unsigned NOT NULL,
  `seller_id` int(11) unsigned NOT NULL,
  `winner_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(5) unsigned NOT NULL,
  `item_date_expire` datetime NOT NULL,
  `item_date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`),
  KEY `item_date_expire` (`item_date_expire`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_item_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.items: ~7 rows (approximately)
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` (`item_id`, `item_name`, `item_description`, `item_image`, `item_initial_price`, `item_bid_step`, `seller_id`, `winner_id`, `category_id`, `item_date_expire`, `item_date_added`) VALUES
	(1, '2014 Rossignol District Snowboard', NULL, '/img/lot-1.jpg', 10999.00, 10.00, 1, NULL, 1, '2021-11-16 00:00:00', '2021-10-27 18:07:21'),
	(2, 'DC Ply Mens 2016/2017 Snowboard', NULL, '/img/lot-2.jpg', 159999.00, 10.00, 2, NULL, 1, '2021-11-17 00:00:00', '2021-10-27 18:07:21'),
	(3, 'Крепления Union Contact Pro 2015 года размер L/XL', NULL, '/img/lot-3.jpg', 8000.00, 10.00, 1, NULL, 2, '2021-11-18 00:00:00', '2021-10-27 18:07:21'),
	(4, 'Ботинки для сноуборда DC Mutiny Charocal', NULL, '/img/lot-4.jpg', 10999.00, 10.00, 2, NULL, 3, '2021-11-19 00:00:00', '2021-10-27 18:07:21'),
	(5, 'Куртка для сноуборда DC Mutiny Charocal', NULL, '/img/lot-5.jpg', 7500.00, 10.00, 1, NULL, 4, '2021-11-20 00:00:00', '2021-10-27 18:07:21'),
	(6, 'Маска Oakley Canopy', NULL, '/img/lot-6.jpg', 5400.00, 10.00, 2, NULL, 6, '2021-11-21 00:00:00', '2021-10-27 18:07:21'),
	(7, 'Сумка для ботинок Salomon Extend Max Gearbag Goji Berry', 'Сумка Extend Max Gearbag предназначена для переноски лыжных ботинок, шлема и аксессуаров. Надежная и вентилируемая конструкция поможет вам более комфортно донести экипировку до склона. Внутри имеется специальный коврик, который поможет сохранить ноги сухими и чистыми во время переобувания мокрых или покрытых снегом ботинок.', '/img/lot-7.jpg', 5390.00, 10.00, 1, NULL, 7, '2021-11-21 00:00:00', '2021-10-28 18:55:37');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;

-- Dumping structure for table yeticave.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_email` varchar(50) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_contact_info` text,
  `user_date_registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.users: ~4 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `user_email`, `user_name`, `user_password`, `user_contact_info`, `user_date_registered`) VALUES
	(6, 'levnevinitsin@gmail.com', 'Lev', '$2y$10$bdp9Sz9S2RqB8w1Sc89WgOg85zfu8VJjouRmMeTHIiPb07DkRxdAO', 'Телеграм: @LevNevinitsin', '2021-11-15 14:45:14'),
	(7, 'ivanivanov@gmail.com', 'Ivan', '$2y$10$MAWF4tO3EhbXFLHq7qQbRuihInu1Id5elAA9GnBDN2ihQWxp4m6vu', 'Телеграм: @IvanIvanov', '2021-11-15 14:45:46');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
