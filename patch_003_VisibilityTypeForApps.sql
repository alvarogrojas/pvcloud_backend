-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2015 at 12:34 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET time_zone = "+00:00";

--
-- Database: `pvcloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_registry`
--

CREATE TABLE IF NOT EXISTS `app_registry` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `app_nickname` varchar(50) COLLATE utf8_bin NOT NULL,
  `app_description` varchar(1000) COLLATE utf8_bin NOT NULL,
  `api_key` varchar(200) COLLATE utf8_bin NOT NULL,
  `visibility_type_id` int(11) NOT NULL DEFAULT '1',
  `created_datetime` datetime NOT NULL,
  `modified_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_datetime` datetime DEFAULT NULL,
  `last_connected_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`app_id`),
  KEY `app_nickname` (`app_nickname`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_visibility_type`
--

CREATE TABLE IF NOT EXISTS `app_visibility_type` (
  `visibility_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `visibility_type_name` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`visibility_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Types of Visibility' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `app_visibility_type`
--

INSERT INTO `app_visibility_type` (`visibility_type_id`, `visibility_type_name`) VALUES
(1, 'Privado'),
(2, 'Compartido'),
(3, 'Público');
