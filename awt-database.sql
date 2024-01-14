-- phpMyAdmin SQL Dump
-- version 5.2.1deb1ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2024 at 07:28 PM
-- Server version: 8.0.35-0ubuntu0.23.10.1
-- PHP Version: 8.2.10-2ubuntu1

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
-- Table structure for table `awt_access_authorization`
--

CREATE TABLE `awt_access_authorization` (
  `id` int NOT NULL,
  `fileName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fileHash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uniqueKey` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_admin`
--

CREATE TABLE `awt_admin` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_logged_ip` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `permission_level` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_albums`
--

CREATE TABLE `awt_albums` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_cron`
--

CREATE TABLE `awt_cron` (
  `id` int NOT NULL,
  `interval` int NOT NULL,
  `last_run` int NOT NULL,
  `caller` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_mail`
--

CREATE TABLE `awt_mail` (
  `id` int NOT NULL,
  `sender` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` text COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_media`
--

CREATE TABLE `awt_media` (
  `id` int NOT NULL,
  `album_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_menus`
--

CREATE TABLE `awt_menus` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `items` text COLLATE utf8mb4_general_ci NOT NULL,
  `active` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_notifications`
--

CREATE TABLE `awt_notifications` (
  `id` int NOT NULL,
  `caller` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `importance` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_paging`
--

CREATE TABLE `awt_paging` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content_1` mediumtext COLLATE utf8mb4_general_ci,
  `content_2` mediumtext COLLATE utf8mb4_general_ci,
  `status` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `override` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_password_reset`
--

CREATE TABLE `awt_password_reset` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `code` int NOT NULL,
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_plugins`
--

CREATE TABLE `awt_plugins` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_settings`
--

CREATE TABLE `awt_settings` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `required_permission_level` int NOT NULL DEFAULT '0',
  `category` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Miscellaneous'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(13, 'PHP Error reporting', '1', 1, 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `awt_themes`
--

CREATE TABLE `awt_themes` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `placeholder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awt_themes`
--

INSERT INTO `awt_themes` (`id`, `name`, `description`, `version`, `placeholder`, `active`) VALUES
(1, 'Twenty-Twenty-Three', 'This is a sleeek and modern theme for your website', '0.0.1', 'placeholder.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `awt_theme_page`
--

CREATE TABLE `awt_theme_page` (
  `id` int NOT NULL,
  `theme_id` int NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_theme_settings`
--

CREATE TABLE `awt_theme_settings` (
  `id` int NOT NULL,
  `theme_id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `awt_access_authorization`
--
ALTER TABLE `awt_access_authorization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_admin`
--
ALTER TABLE `awt_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_albums`
--
ALTER TABLE `awt_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_cron`
--
ALTER TABLE `awt_cron`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_mail`
--
ALTER TABLE `awt_mail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_media`
--
ALTER TABLE `awt_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_menus`
--
ALTER TABLE `awt_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_notifications`
--
ALTER TABLE `awt_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_paging`
--
ALTER TABLE `awt_paging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_password_reset`
--
ALTER TABLE `awt_password_reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_plugins`
--
ALTER TABLE `awt_plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_settings`
--
ALTER TABLE `awt_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_themes`
--
ALTER TABLE `awt_themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_theme_page`
--
ALTER TABLE `awt_theme_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awt_theme_settings`
--
ALTER TABLE `awt_theme_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awt_access_authorization`
--
ALTER TABLE `awt_access_authorization`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_admin`
--
ALTER TABLE `awt_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_albums`
--
ALTER TABLE `awt_albums`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_cron`
--
ALTER TABLE `awt_cron`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_mail`
--
ALTER TABLE `awt_mail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_media`
--
ALTER TABLE `awt_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_menus`
--
ALTER TABLE `awt_menus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_notifications`
--
ALTER TABLE `awt_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_paging`
--
ALTER TABLE `awt_paging`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_password_reset`
--
ALTER TABLE `awt_password_reset`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_plugins`
--
ALTER TABLE `awt_plugins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_settings`
--
ALTER TABLE `awt_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `awt_themes`
--
ALTER TABLE `awt_themes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `awt_theme_page`
--
ALTER TABLE `awt_theme_page`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awt_theme_settings`
--

--
ALTER TABLE `awt_paging`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `awt_theme_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
