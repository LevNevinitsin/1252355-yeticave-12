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
  PRIMARY KEY (`bid_id`),
  KEY `bid_sort` (`item_id`,`bid_price`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.bids: ~7 rows (approximately)
/*!40000 ALTER TABLE `bids` DISABLE KEYS */;
INSERT INTO `bids` (`bid_id`, `bid_price`, `user_id`, `item_id`, `bid_date_created`) VALUES
	(1, 14000.33, 12, 1, '2021-10-27 19:07:21'),
	(2, 13000.60, 6, 1, '2021-10-27 18:07:21'),
	(3, 21000.00, 7, 4, '2021-10-29 18:07:21'),
	(4, 17000.25, 6, 4, '2021-10-27 18:07:21'),
	(6, 5500.00, 6, 7, '2021-11-21 11:46:17'),
	(7, 1000.00, 6, 29, '2021-11-22 11:21:41'),
	(8, 18000.00, 6, 1, '2021-11-23 11:21:11'),
	(10, 110.00, 6, 44, '2021-11-29 15:06:18'),
	(11, 6000.00, 14, 7, '2021-12-02 12:10:05');
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
  KEY `category_id` (`category_id`),
  KEY `winnersIndex` (`item_date_expire`,`winner_id`),
  FULLTEXT KEY `item_ft_search` (`item_name`,`item_description`),
  CONSTRAINT `fk_item_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.items: ~52 rows (approximately)
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` (`item_id`, `item_name`, `item_description`, `item_image`, `item_initial_price`, `item_bid_step`, `seller_id`, `winner_id`, `category_id`, `item_date_expire`, `item_date_added`) VALUES
	(1, '2014 Rossignol District Snowboard', NULL, '/img/lot-1.jpg', 10999.00, 10.00, 7, NULL, 1, '2021-12-08 17:12:03', '2021-10-27 18:07:21'),
	(2, 'DC Ply Mens 2016/2017 Snowboard', NULL, '/img/lot-2.jpg', 159999.00, 10.00, 6, NULL, 1, '2021-12-07 14:47:11', '2021-10-27 18:07:21'),
	(3, 'Крепления Union Contact Pro 2015 года размер L/XL', NULL, '/img/lot-3.jpg', 8000.00, 10.00, 7, NULL, 2, '2021-12-06 11:03:14', '2021-10-27 18:07:21'),
	(4, 'Ботинки для сноуборда DC Mutiny Charocal', NULL, '/img/lot-4.jpg', 10999.00, 10.00, 12, NULL, 3, '2021-12-06 16:39:22', '2021-10-27 18:07:21'),
	(5, 'Куртка для сноуборда DC Mutiny Charocal', NULL, '/img/lot-5.jpg', 7500.00, 10.00, 7, NULL, 4, '2021-12-06 11:03:14', '2021-10-27 18:07:21'),
	(6, 'Маска Oakley Canopy', NULL, '/img/lot-6.jpg', 5400.00, 10.00, 6, NULL, 6, '2021-12-05 14:53:29', '2021-10-27 18:07:21'),
	(7, 'Сумка для ботинок Salomon Extend Max Gearbag Goji Berry', 'Сумка Extend Max Gearbag предназначена для переноски лыжных ботинок, шлема и аксессуаров. Надежная и вентилируемая конструкция поможет вам более комфортно донести экипировку до склона. Внутри имеется специальный коврик, который поможет сохранить ноги сухими и чистыми во время переобувания мокрых или покрытых снегом ботинок.', '/img/lot-7.jpg', 5390.00, 10.00, 7, NULL, 7, '2021-12-08 16:39:22', '2021-10-18 18:55:37'),
	(9, '123123', '12123', '/uploads/6193959a9311c.png', 12.00, 12.00, 6, NULL, 3, '2021-12-07 11:03:14', '2021-11-16 14:27:22'),
	(11, '123', '123', '/uploads/6193c9345b2cb.png', 12.00, 12.00, 6, NULL, 3, '2021-12-05 11:03:14', '2021-11-16 18:07:32'),
	(12, '1', '123', '/uploads/6194e682be5eb.png', 1.00, 1.00, 6, NULL, 1, '2021-12-06 11:03:14', '2021-11-17 14:24:50'),
	(13, '2', '123', '/uploads/6194e68f6ffa6.png', 2.00, 2.00, 6, NULL, 1, '2021-12-05 11:03:14', '2021-11-17 14:25:03'),
	(14, '1231', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:16:32', '2021-11-17 14:31:07'),
	(15, '1232', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:03:14', '2021-11-17 14:31:07'),
	(16, '1233', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 16:39:22', '2021-11-17 14:31:07'),
	(17, '1234', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 14:53:29', '2021-11-17 14:31:07'),
	(18, '1235', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:16:32', '2021-11-17 14:31:07'),
	(19, '1236', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:03:14', '2021-11-17 14:31:07'),
	(20, '1237', '123', '/uploads/test.png', 100.00, 10.00, 12, NULL, 7, '2021-12-08 14:53:29', '2021-11-17 14:31:07'),
	(21, '1238', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 14:48:29', '2021-11-17 14:31:07'),
	(22, '1239', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(23, '1240', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 14:53:29', '2021-11-17 14:31:07'),
	(24, '1241', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(25, '1242', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 14:53:29', '2021-11-17 14:31:07'),
	(26, '1243', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-08 14:53:29', '2021-11-17 14:31:07'),
	(27, '1244', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 14:49:16', '2021-11-17 14:31:07'),
	(28, '1245', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-09 14:53:29', '2021-11-17 14:31:07'),
	(29, '1246', '123', '/uploads/test.png', 100.00, 10.00, 12, NULL, 7, '2021-12-05 16:39:22', '2021-11-17 14:31:07'),
	(30, '1247', '123', '/uploads/test.png', 100.00, 10.00, 12, NULL, 7, '2021-12-09 14:53:29', '2021-11-17 14:31:07'),
	(31, '1248', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:03:14', '2021-11-17 14:31:07'),
	(32, '1249', '123', '/uploads/test.png', 100.00, 10.00, 12, NULL, 7, '2021-12-07 11:16:32', '2021-11-17 14:31:07'),
	(33, '1250', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(34, '1251', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 14:53:29', '2021-11-17 14:31:07'),
	(35, '1252', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-09 14:53:29', '2021-11-17 14:31:07'),
	(36, '1253', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 11:16:32', '2021-11-17 14:31:07'),
	(37, '1254', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(38, '1255', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(39, '1256', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(40, '1257', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:16:32', '2021-11-17 14:31:07'),
	(41, '1258', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 14:48:29', '2021-11-17 14:31:07'),
	(42, '1259', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 11:03:14', '2021-11-17 14:31:07'),
	(43, '1260', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 14:48:29', '2021-11-17 14:31:07'),
	(44, '1261', '123', '/uploads/test.png', 100.00, 10.00, 7, NULL, 7, '2021-12-07 11:16:32', '2021-11-17 14:31:07'),
	(45, '1262', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 14:33:44', '2021-11-17 14:31:07'),
	(46, '1263', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:16:32', '2021-11-17 14:31:07'),
	(47, '1264', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 13:06:47', '2021-11-17 14:31:07'),
	(48, '1265', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-06 14:49:16', '2021-11-17 14:31:07'),
	(49, '1266', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 11:03:14', '2021-11-17 14:31:07'),
	(50, '1267', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-08 14:53:29', '2021-11-17 14:31:07'),
	(51, '1268', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 11:03:14', '2021-11-17 14:31:07'),
	(52, '1269', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-05 11:03:14', '2021-11-17 14:31:07'),
	(53, '1270', '123', '/uploads/test.png', 100.00, 10.00, 6, NULL, 7, '2021-12-07 11:16:32', '2021-11-17 14:31:07'),
	(54, '123123', 'qdqfqef', '/uploads/61a8b046b5eac.png', 123123.00, 12.00, 14, NULL, 1, '2021-12-07 14:53:29', '2021-12-02 14:38:46');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table yeticave.users: ~7 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `user_email`, `user_name`, `user_password`, `user_contact_info`, `user_date_registered`) VALUES
	(6, 'levnevinitsin@gmail.com', 'Lev', '$2y$10$bdp9Sz9S2RqB8w1Sc89WgOg85zfu8VJjouRmMeTHIiPb07DkRxdAO', 'Телеграм: @LevNevinitsin', '2021-11-15 14:45:14'),
	(7, 'ivanivanov@gmail.com', 'Ivan', '$2y$10$MAWF4tO3EhbXFLHq7qQbRuihInu1Id5elAA9GnBDN2ihQWxp4m6vu', 'Телеграм: @IvanIvanov', '2021-11-15 14:45:46'),
	(10, 'asd@asd.ru', '1231', '$2y$10$um4CHYF5FnVzGt5.79K5tOPGTIbGf1r5oh1ovcDxAkxpPZsL5GuuK', '123', '2021-11-15 17:50:59'),
	(11, 'asd1@asd.ru', '123', '$2y$10$aBPsh8I1zlNnirbJbrzUE.9pZzBO4vU1xZ3gruWpMCXhqfkH/Asa6', '123', '2021-11-16 17:34:12'),
	(12, 'alex@mail.ru', 'Alex', '$2y$10$DcSTC8ec40yetff9LxqueewaQmtBrxonc8mD.uBuQ27p/EGltLtyS', 'Telegram: @AlexPetrov', '2021-11-21 18:18:19'),
	(13, 'test@test.ru', 'Test Testov', '$2y$10$Kq0aWVThOkNas3V9C7ZneeNYq2okumjoofW85iyu27dxropp/ybLa', 'Telegram: @TestTestov', '2021-12-02 11:53:34'),
	(14, 'test123@test.ru', 'Test123', '$2y$10$MXljF1v5p4tKVNHlCCIgBen95nf6W4/SOr4gAG7HVvajxP56t.l6e', 'Telegram: @Test123', '2021-12-02 11:54:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
