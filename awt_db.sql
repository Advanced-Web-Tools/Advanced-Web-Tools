-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 12:22 AM
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
-- Database: `awt_development`
--

-- --------------------------------------------------------

--
-- Table structure for table `awt_admin`
--

CREATE TABLE IF NOT EXISTS `awt_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'awt_data/media/icons/circle-user-regular.svg',
  `last_logged_ip` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `permission_level` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_data`
--

CREATE TABLE IF NOT EXISTS `awt_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerType` varchar(255) NOT NULL,
  `ownerName` varchar(255) DEFAULT NULL,
  `ownerId` int(11) DEFAULT NULL,
  `dataType` varchar(255) NOT NULL,
  `dataName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerId` (`ownerId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `awt_data`
--

TRUNCATE TABLE `awt_data`;
--
-- Dumping data for table `awt_data`
--

INSERT INTO `awt_data` (`id`, `ownerType`, `ownerName`, `ownerId`, `dataType`, `dataName`) VALUES
(5, 'Package', 'Dashboard', 2, 'icon', 'dashboard.png'),
(6, 'Package', 'Quil', 3, 'icon', 'quil.png'),
(7, 'Package', 'MediaCenter', 4, 'icon', 'media.png'),
(8, 'Package', 'PackageManager', 5, 'icon', 'packages.png'),
(10, 'Package', 'AWTRespond', 6, 'icon', 'AWTRespond.png'),
(45, 'System', 'Package Manager', 5, 'temp', 'a5262f1604.0'),
(47, 'Package', 'Account Manager', 7, 'icon', 'accounts.png'),
(51, 'User', 'AWT', 1, 'image', 'logo.png.png'),
(54, 'User', 'AWT', 1, 'image', 'settings.png');

-- --------------------------------------------------------

--
-- Table structure for table `awt_package`
--

CREATE TABLE IF NOT EXISTS `awt_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `installed_by` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `preview_image` text DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `license_url` varchar(255) DEFAULT NULL,
  `author` varchar(128) DEFAULT NULL,
  `version` varchar(128) NOT NULL,
  `minimum_awt_version` varchar(128) NOT NULL,
  `maximum_awt_version` varchar(128) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `system_package` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `installation_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `store_id` (`store_id`),
  KEY `installed_by` (`installed_by`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `awt_package`
--

TRUNCATE TABLE `awt_package`;
--
-- Dumping data for table `awt_package`
--

INSERT INTO `awt_package` (`id`, `store_id`, `installed_by`, `name`, `description`, `icon`, `preview_image`, `license`, `license_url`, `author`, `version`, `minimum_awt_version`, `maximum_awt_version`, `type`, `system_package`, `status`, `installation_date`) VALUES
(1, 1, 1, 'AWT', 'Advanced Web Tools - Framework', NULL, NULL, NULL, NULL, 'ElStefanos', '24.9.1', '24.9.1', '24.9.1', 0, 1, 1, '2024-08-22 13:43:20'),
(2, 2, 1, 'Dashboard', 'Advanced Web Tool - Dashboard package', '/awt_data/media/packages/icon/Dashboard/dashboard.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 1, 1, 1, '2024-09-09 11:48:39'),
(3, 3, 1, 'Quil', 'Quil - Page builder', '/awt_data/media/packages/icon/Quil/quil.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 1, 1, 1, '2024-09-09 11:48:39'),
(4, 4, 1, 'MediaCenter', 'Media Center - Control all your media', '/awt_data/media/packages/icon/MediaCenter/media.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 1, 1, 1, '2024-09-09 11:48:39'),
(5, 5, 1, 'Package Manager', 'Default package manager', '/awt_data/media/packages/icon/PackageManager/packages.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 1, 1, 1, '2024-09-23 11:25:12'),
(6, 6, 1, 'AWTRespond', 'AWTRespond is a package library to allow other packages easy API handling.', '/awt_data/media/packages/icon/AWTRespond/AWTRespond.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 1, 1, 1, '2024-10-24 19:37:09'),
(7, 7, 1, 'AccountManager', 'Manage admin accounts.', '/awt_data/media/packages/icon/Account Manager/accounts.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 1, 1, 1, '2024-12-24 14:50:28'),
(8, 8, 1, 'Settings', 'Change settings in AWT.', '/awt_data/media/packages/icon/Settings/settings.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.0', NULL, 1, 1, 1, '2024-12-24 14:50:28'),
(9, 9, 1, 'Theming', 'Theme your website!', '/awt_data/media/packages/icon/Theming/theme.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.0', NULL, 1, 1, 1, '2024-12-24 14:50:28'),
(10, 10, 1, 'TwentyTwentyFive', 'AdvancedWebTools - Default Theme', '/awt_data/media/packages/icon/TwentyTwentyFive/theme.png', NULL, NULL, NULL, 'ElStefanos', '1.0.0', '24.9.1', NULL, 2, 0, 1, '2024-12-24 14:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `awt_setting`
--

CREATE TABLE IF NOT EXISTS `awt_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value_type` varchar(16) NOT NULL DEFAULT 'text',
  `value` varchar(255) NOT NULL,
  `required_permission_level` int(11) NOT NULL DEFAULT 0,
  `category` varchar(32) DEFAULT 'Miscellaneous',
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `awt_setting`
--

TRUNCATE TABLE `awt_setting`;
--
-- Dumping data for table `awt_setting`
--

INSERT INTO `awt_setting` (`id`, `package_id`, `name`, `value_type`, `value`, `required_permission_level`, `category`) VALUES
(1, 1, 'Website Name', 'text', 'Hello World!', 0, 'General'),
(2, 1, 'Hostname Path', 'text', '', 0, 'General'),
(3, 1, 'Use Packages', 'boolean', 'true', 0, 'General'),
(4, 1, 'Session HTTPS Only', 'boolean', 'false', 0, 'Session'),
(5, 1, 'Session HTTP Only', 'boolean', 'true', 0, 'Session'),
(6, 1, 'Session ID Regeneration Time', 'number', '900', 0, 'Session'),
(7, 1, 'Session SameSite', 'boolean', 'true', 0, 'Session'),
(8, 1, 'Contact Email', 'text', 'test@test.com', 0, 'General'),
(9, 1, 'Phone Number', 'text', '00381 691234567', 0, 'General'),
(10, 1, 'Address', 'text', 'Djevdjelijska 13', 0, 'General');

-- --------------------------------------------------------

--
-- Table structure for table `awt_table`
--

CREATE TABLE IF NOT EXISTS `awt_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `creator` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `creator` (`creator`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `awt_table`
--

TRUNCATE TABLE `awt_table`;
--
-- Dumping data for table `awt_table`
--

INSERT INTO `awt_table` (`id`, `name`, `creation_date`, `creator`) VALUES
(1, 'awt_admin', '2024-12-24 21:42:51', 1),
(2, 'awt_data', '2024-12-24 21:42:51', 1),
(3, 'awt_package', '2024-12-24 21:42:51', 1),
(4, 'awt_setting', '2024-12-24 21:42:51', 1),
(5, 'media_center_album', '2024-12-24 21:42:51', 4),
(6, 'media_center_album_content', '2024-12-24 21:42:51', 4),
(7, 'media_center_content', '2024-12-24 21:42:51', 4),
(8, 'quil_page', '2024-12-24 21:42:51', 3),
(9, 'quil_page_content', '2024-12-24 21:42:51', 3),
(10, 'quil_page_data_source', '2024-12-24 21:42:51', 3),
(11, 'awt_table', '2024-12-24 21:47:27', 1),
(12, 'awt_table_structure', '2024-12-24 21:47:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `awt_table_structure`
--

CREATE TABLE IF NOT EXISTS `awt_table_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `column_type` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `table_id` (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `awt_table_structure`
--

TRUNCATE TABLE `awt_table_structure`;
--
-- Dumping data for table `awt_table_structure`
--

INSERT INTO `awt_table_structure` (`id`, `table_id`, `column_name`, `column_type`) VALUES
(163, 1, 'id', 'int'),
(164, 1, 'email', 'varchar(255)'),
(165, 1, 'username', 'varchar(255)'),
(166, 1, 'firstname', 'varchar(255)'),
(167, 1, 'lastname', 'varchar(255)'),
(168, 1, 'profile_picture', 'varchar(255)'),
(169, 1, 'last_logged_ip', 'varchar(255)'),
(170, 1, 'password', 'varchar(255)'),
(171, 1, 'token', 'varchar(255)'),
(172, 1, 'permission_level', 'int'),
(173, 2, 'id', 'int'),
(174, 2, 'ownerType', 'varchar(255)'),
(175, 2, 'ownerName', 'varchar(255)'),
(176, 2, 'ownerId', 'int'),
(177, 2, 'dataType', 'varchar(255)'),
(178, 2, 'dataName', 'varchar(255)'),
(179, 3, 'id', 'int'),
(180, 3, 'store_id', 'int'),
(181, 3, 'installed_by', 'int'),
(182, 3, 'name', 'varchar(255)'),
(183, 3, 'description', 'text'),
(184, 3, 'icon', 'text'),
(185, 3, 'preview_image', 'text'),
(186, 3, 'license', 'varchar(255)'),
(187, 3, 'license_url', 'varchar(255)'),
(188, 3, 'author', 'varchar(128)'),
(189, 3, 'version', 'varchar(128)'),
(190, 3, 'minimum_awt_version', 'varchar(128)'),
(191, 3, 'maximum_awt_version', 'varchar(128)'),
(192, 3, 'type', 'tinyint'),
(193, 3, 'system_package', 'tinyint'),
(194, 3, 'status', 'tinyint'),
(195, 3, 'installation_date', 'datetime'),
(196, 4, 'id', 'int'),
(197, 4, 'package_id', 'int'),
(198, 4, 'name', 'varchar(255)'),
(199, 4, 'value_type', 'varchar(16)'),
(200, 4, 'value', 'varchar(255)'),
(201, 4, 'required_permission_level', 'int'),
(202, 4, 'category', 'varchar(32)'),
(203, 5, 'id', 'int'),
(204, 5, 'name', 'varchar(255)'),
(205, 6, 'id', 'int'),
(206, 6, 'album_id', 'int'),
(207, 6, 'content_id', 'int'),
(208, 7, 'media_id', 'int'),
(209, 7, 'data_id', 'int'),
(210, 7, 'name', 'varchar(255)'),
(211, 8, 'id', 'int'),
(212, 8, 'created_by', 'int'),
(213, 8, 'route_id', 'int'),
(214, 8, 'name', 'varchar(255)'),
(215, 8, 'description', 'varchar(255)'),
(216, 8, 'creation_date', 'timestamp'),
(217, 8, 'last_update', 'timestamp'),
(218, 9, 'id', 'int'),
(219, 9, 'page_id', 'int'),
(220, 9, 'content', 'longtext'),
(221, 10, 'id', 'int'),
(222, 10, 'data_source_type', 'varchar(255)'),
(223, 10, 'page_id', 'int'),
(224, 10, 'data_source_name', 'varchar(255)'),
(225, 12, 'column_name', 'varchar(255)'),
(226, 12, 'column_type', 'varchar(32)'),
(227, 11, 'id', 'int'),
(228, 11, 'name', 'varchar(255)'),
(229, 11, 'creator', 'int'),
(230, 11, 'creation_date', 'timestamp'),
(231, 12, 'id', 'int');

-- --------------------------------------------------------

--
-- Table structure for table `media_center_album`
--

CREATE TABLE IF NOT EXISTS `media_center_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_center_album_content`
--

CREATE TABLE IF NOT EXISTS `media_center_album_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `album_id` (`album_id`),
  KEY `content_id` (`content_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_center_content`
--

CREATE TABLE IF NOT EXISTS `media_center_content` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`media_id`),
  KEY `data_id` (`data_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quil_page`
--

CREATE TABLE IF NOT EXISTS `quil_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_update` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `route` (`route_id`) USING BTREE,
  KEY `user` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quil_page_content`
--

CREATE TABLE IF NOT EXISTS `quil_page_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quil_page_data_source`
--

CREATE TABLE IF NOT EXISTS `quil_page_data_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `column_selector` varchar(255) NOT NULL DEFAULT 'id',
  `bind_param_url` varchar(255) DEFAULT NULL,
  `default_param_value` varchar(255) DEFAULT '1',
  `source_name` varchar(255) NOT NULL DEFAULT 'source',
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_source` (`page_id`,`source_name`) USING BTREE,
  KEY `page_id` (`page_id`),
  KEY `table` (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quil_page_route`
--

CREATE TABLE IF NOT EXISTS `quil_page_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `route` (`route`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theming_custom_page`
--

CREATE TABLE IF NOT EXISTS `theming_custom_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `page_content_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `theme_id` (`theme_id`),
  KEY `page_content_id` (`page_content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theming_menu`
--

CREATE TABLE IF NOT EXISTS `theming_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `theming_menu`
--

TRUNCATE TABLE `theming_menu`;
--
-- Dumping data for table `theming_menu`
--

INSERT INTO `theming_menu` (`id`, `name`, `active`) VALUES
(1, 'Default', 1);

-- --------------------------------------------------------

--
-- Table structure for table `theming_menu_item`
--

CREATE TABLE IF NOT EXISTS `theming_menu_item` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `target` varchar(10) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `parent_item` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `parent` (`parent_item`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `theming_menu_item`
--

TRUNCATE TABLE `theming_menu_item`;
--
-- Dumping data for table `theming_menu_item`
--

INSERT INTO `theming_menu_item` (`id`, `menu_id`, `name`, `link`, `target`, `position`, `parent_item`) VALUES
(1, 1, 'About', '/about', '_self', 2, NULL),
(11, 1, 'Home', '/', '_self', 0, NULL),
(12, 1, 'Contact', '/contact', '_self', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `theming_settings`
--

CREATE TABLE IF NOT EXISTS `theming_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'TEXT',
  PRIMARY KEY (`id`),
  KEY `theme_id` (`theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `theming_settings`
--

TRUNCATE TABLE `theming_settings`;
--
-- Dumping data for table `theming_settings`
--

INSERT INTO `theming_settings` (`id`, `theme_id`, `name`, `value`, `type`) VALUES
(1, 1, 'Title', '|', 'TEXT'),
(2, 1, 'primary_accent', '#007BFF', 'COLOR'),
(3, 1, 'secondary_accent', '#1d4ed8', 'COLOR'),
(4, 1, 'primary_background', '#FFFFFF', 'COLOR'),
(5, 1, 'secondary_background', '#F8F9FA', 'COLOR'),
(6, 1, 'primary_text', '#212529', 'COLOR'),
(7, 1, 'secondary_text', '#FFFFFF', 'COLOR'),
(8, 1, 'font_family', '\'Poppins\', sans-serif', 'TEXT'),
(9, 1, 'action_positive_color', '#28A745', 'COLOR'),
(10, 1, 'action_negative_color', '#DC3545', 'COLOR'),
(13, 1, 'footer_background', '#111827', 'COLOR');

-- --------------------------------------------------------

--
-- Table structure for table `theming_theme`
--

CREATE TABLE IF NOT EXISTS `theming_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_id` (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `theming_theme`
--

TRUNCATE TABLE `theming_theme`;
--
-- Dumping data for table `theming_theme`
--

INSERT INTO `theming_theme` (`id`, `package_id`, `status`) VALUES
(1, 10, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `awt_data`
--
ALTER TABLE `awt_data`
  ADD CONSTRAINT `awt_data_ibfk_1` FOREIGN KEY (`ownerId`) REFERENCES `awt_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `awt_package`
--
ALTER TABLE `awt_package`
  ADD CONSTRAINT `awt_package_ibfk_1` FOREIGN KEY (`installed_by`) REFERENCES `awt_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `awt_setting`
--
ALTER TABLE `awt_setting`
  ADD CONSTRAINT `awt_setting_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `awt_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `awt_table`
--
ALTER TABLE `awt_table`
  ADD CONSTRAINT `awt_table_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `awt_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `awt_table_structure`
--
ALTER TABLE `awt_table_structure`
  ADD CONSTRAINT `awt_table_structure_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `awt_table` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media_center_album_content`
--
ALTER TABLE `media_center_album_content`
  ADD CONSTRAINT `media_center_album_content_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `media_center_album` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_center_album_content_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `media_center_content` (`media_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media_center_content`
--
ALTER TABLE `media_center_content`
  ADD CONSTRAINT `media_center_content_ibfk_1` FOREIGN KEY (`data_id`) REFERENCES `awt_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quil_page`
--
ALTER TABLE `quil_page`
  ADD CONSTRAINT `quil_page_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `awt_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `quil_page_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `quil_page_route` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quil_page_content`
--
ALTER TABLE `quil_page_content`
  ADD CONSTRAINT `quil_page_content_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `quil_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quil_page_data_source`
--
ALTER TABLE `quil_page_data_source`
  ADD CONSTRAINT `page` FOREIGN KEY (`page_id`) REFERENCES `quil_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `table` FOREIGN KEY (`table_id`) REFERENCES `awt_table` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quil_page_route`
--
ALTER TABLE `quil_page_route`
  ADD CONSTRAINT `quil_page_route_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `awt_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `theming_custom_page`
--
ALTER TABLE `theming_custom_page`
  ADD CONSTRAINT `theming_custom_page_ibfk_1` FOREIGN KEY (`page_content_id`) REFERENCES `quil_page_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `theming_custom_page_ibfk_2` FOREIGN KEY (`theme_id`) REFERENCES `theming_theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `theming_menu_item`
--
ALTER TABLE `theming_menu_item`
  ADD CONSTRAINT `theming_menu_item_ibfk_1` FOREIGN KEY (`parent_item`) REFERENCES `theming_menu_item` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `theming_menu_item_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `theming_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `theming_settings`
--
ALTER TABLE `theming_settings`
  ADD CONSTRAINT `theming_settings_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `theming_theme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `theming_theme`
--
ALTER TABLE `theming_theme`
  ADD CONSTRAINT `theming_theme_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `awt_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
