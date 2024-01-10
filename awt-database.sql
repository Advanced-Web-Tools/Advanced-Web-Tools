-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 09, 2024 at 11:35 PM
-- Server version: 10.6.16-MariaDB-1:10.6.16+maria~deb11
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `awt_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `awt_access_authorization`
--

CREATE TABLE IF NOT EXISTS `awt_access_authorization` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(255) NOT NULL,
  `fileHash` varchar(255) NOT NULL,
  `uniqueKey` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_admin`
--

CREATE TABLE IF NOT EXISTS `awt_admin` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `last_logged_ip` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `permission_level` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_albums`
--

CREATE TABLE IF NOT EXISTS `awt_albums` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_mail`
--

CREATE TABLE IF NOT EXISTS `awt_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_media`
--

CREATE TABLE IF NOT EXISTS `awt_media` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `album_id` int(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `file_type` varchar(20) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_menus`
--

CREATE TABLE IF NOT EXISTS `awt_menus` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `items` text NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_notifications`
--

CREATE TABLE IF NOT EXISTS `awt_notifications` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `caller` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `importance` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_paging`
--

CREATE TABLE IF NOT EXISTS `awt_paging` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content_1` mediumtext DEFAULT NULL,
  `content_2` mediumtext DEFAULT NULL,
  `status` varchar(7) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `override` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_password_reset`
--

CREATE TABLE IF NOT EXISTS `awt_password_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `code` int(255) NOT NULL,
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_plugins`
--

CREATE TABLE IF NOT EXISTS `awt_plugins` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_settings`
--

CREATE TABLE IF NOT EXISTS `awt_settings` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `required_permission_level` int(1) NOT NULL DEFAULT 0,
  `category` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awt_settings`
--

INSERT INTO `awt_settings` (`id`, `name`, `value`, `required_permission_level`, `category`) VALUES
(1, 'enable_caching', 'false', 0, 'General'),
(2, 'page_caching_time', '150', 0, 'General'),
(3, 'cache_in_session_time', '300', 0, 'General'),
(4, 'whitelist', 'false', 0, 'Security'),
(5, 'whitelist_list', '127.0.0.1 ::1 localhost', 0, 'Security'),
(6, 'use_plugins', 'true', 0, 'General'),
(7, 'hostname_path', '/', 0, 'General'),
(10, 'Enable API', 'true', 0, 'Security'),
(11, 'API request whitelist', '*', 0, 'Security'),
(13, 'PHP Error reporting', '1', 0, 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `awt_themes`
--

CREATE TABLE IF NOT EXISTS `awt_themes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awt_themes`
--

INSERT INTO `awt_themes` (`id`, `name`, `description`, `version`, `placeholder`, `active`) VALUES
(1, 'Twenty-Twenty-Three', 'This is a sleeek and modern theme for your website', '0.0.1', 'placeholder.png', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
