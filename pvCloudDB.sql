-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2014 at 07:22 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET time_zone = "+00:00";

--
-- Database: `pvcloud`
--
CREATE DATABASE IF NOT EXISTS `pvcloud` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE pvcloud;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(50) COLLATE utf8_bin NOT NULL,
  `pwd_hash` varchar(256) COLLATE utf8_bin NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `confirmation_guid` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `created_datetime` datetime NOT NULL,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_id` (`account_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PV Cloud User Accounts' AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `accounts`
--

TRUNCATE TABLE `accounts`;
--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `nickname`, `pwd_hash`, `confirmed`, `confirmation_guid`, `created_datetime`, `modified_datetime`, `deleted_datetime`) VALUES
(1, 'jose.a.nunez@gmail.com', '', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, '5477970556927', '2014-11-27 15:26:29', '2014-12-02 01:08:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_registry`
--

DROP TABLE IF EXISTS `app_registry`;
CREATE TABLE IF NOT EXISTS `app_registry` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `app_nickname` varchar(50) COLLATE utf8_bin NOT NULL,
  `app_description` varchar(1000) COLLATE utf8_bin NOT NULL,
  `api_key` varchar(200) COLLATE utf8_bin NOT NULL,
  `created_datetime` datetime NOT NULL,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_datetime` datetime DEFAULT NULL,
  `last_connected_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`app_id`),
  KEY `app_nickname` (`app_nickname`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Truncate table before insert `app_registry`
--

TRUNCATE TABLE `app_registry`;
--
-- Dumping data for table `app_registry`
--

INSERT INTO `app_registry` (`app_id`, `account_id`, `app_nickname`, `app_description`, `api_key`, `created_datetime`, `modified_datetime`, `deleted_datetime`, `last_connected_datetime`) VALUES
(1, 1, 'JOSE''S FIRST GALILEO GEN 1', 'FIRST GALILEO GEN 1 I HAD', 'c55452a9bdacdc0dc15919cdfe8d8f7d4c05ac5e', '2014-12-06 16:04:21', '2014-12-17 05:57:10', NULL, NULL),
(2, 1, 'ALARMA LSM PR01', 'Laser Tripwire Alarm', '32c50daa34760183d9ec217ed775c60d155ac81c', '2014-12-16 23:56:35', '2014-12-17 06:00:58', NULL, NULL),
(3, 1, 'WATER GRAY SYSTEM', 'Aguas Jabonosas Distribuidor', '61d39e42ea3b1244fb12db616daac0d7ff88bfc4', '2014-12-16 23:58:46', '2014-12-17 05:58:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `expiration_datetime` datetime NOT NULL,
  `created_datetime` datetime NOT NULL,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `token` (`token`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Client Sessions' AUTO_INCREMENT=15 ;

--
-- Truncate table before insert `sessions`
--

TRUNCATE TABLE `sessions`;
--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `account_id`, `token`, `expiration_datetime`, `created_datetime`, `modified_datetime`) VALUES
(14, 1, '99aa3867e0d35758252f456b80d89c2b1bce167c', '2014-12-17 01:00:58', '2014-12-16 23:54:05', '2014-12-17 06:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `vse_data`
--

DROP TABLE IF EXISTS `vse_data`;
CREATE TABLE IF NOT EXISTS `vse_data` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `vse_label` varchar(50) COLLATE utf8_bin NOT NULL,
  `vse_value` varchar(200) COLLATE utf8_bin NOT NULL,
  `vse_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `vse_annotations` text COLLATE utf8_bin,
  `captured_datetime` datetime NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5530 ;

--
-- Truncate table before insert `vse_data`
--

TRUNCATE TABLE `vse_data`;
--
-- Dumping data for table `vse_data`
--

INSERT INTO `vse_data` (`entry_id`, `app_id`, `vse_label`, `vse_value`, `vse_type`, `vse_annotations`, `captured_datetime`, `created_datetime`) VALUES
(5522, 1, 'DIRECT pvCloud TEST', '12345', 'numerico', NULL, '2014-12-09 21:01:00', '2014-12-10 00:28:57');
