-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2014 at 11:05 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `pvcloud`
--
CREATE DATABASE IF NOT EXISTS `pvcloud` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `pvcloud`;

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
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_id` (`account_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PV Cloud User Accounts' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `nickname`, `pwd_hash`, `confirmed`, `confirmation_guid`, `created_datetime`, `modified_datetime`, `deleted_datetime`) VALUES
(1, 'jose.a.nunez@gmail.com', '', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, '5477970556927', '2014-11-27 15:26:29', '2014-12-02 01:08:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `device_registry`
--

DROP TABLE IF EXISTS `device_registry`;
CREATE TABLE IF NOT EXISTS `device_registry` (
  `device_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `device_nickname` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(1000) COLLATE utf8_bin NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_datetime` datetime DEFAULT NULL,
  `last_connected_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`device_id`),
  KEY `device_nickname` (`device_nickname`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `device_registry`
--

INSERT INTO `device_registry` (`device_id`, `account_id`, `device_nickname`, `description`, `created_datetime`, `modified_datetime`, `deleted_datetime`, `last_connected_datetime`) VALUES
(1, 1, 'JOSE''S FIRST GALILEO GEN 1', 'FIRST GALILEO GEN 1 I HAD', '2014-12-06 16:04:21', '2014-12-06 22:04:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `email` varchar(200) COLLATE utf8_bin NOT NULL,
  `expiration_datetime` datetime NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `token` (`token`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Client Sessions' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `token`, `email`, `expiration_datetime`, `created_datetime`, `modified_datetime`) VALUES
(13, 'b6ab4d6426a67d22dd4fc657226794a4b7eba848', 'jose.a.nunez@gmail.com', '2014-12-06 16:50:49', '2014-12-06 15:10:25', '2014-12-06 21:50:49');

