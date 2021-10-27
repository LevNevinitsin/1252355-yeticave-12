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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table yeticave.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `category_code` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`),
  UNIQUE KEY `category_code` (`category_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table yeticave.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_email` varchar(50) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_password` varchar(128) NOT NULL,
  `user_contact_info` text,
  `user_date_registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
