
-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2015 at 07:28 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET time_zone = "+00:00";

--
-- Database: `pvcloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_association`
--

DROP TABLE IF EXISTS `accounts_association`;
CREATE TABLE IF NOT EXISTS `accounts_association` (
  `account_id_host` int(11) NOT NULL,
  `account_id_guest` int(11) NOT NULL,
  `requested_date` datetime NOT NULL,
  `accepted_date` datetime DEFAULT NULL,
  `rejected_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

DROP TABLE IF EXISTS `invitations`;
CREATE TABLE IF NOT EXISTS `invitations` (
`invitation_id` int(11) NOT NULL,
  `host_email` varchar(200) COLLATE utf8_bin NOT NULL,
  `guest_email` varchar(200) COLLATE utf8_bin NOT NULL,
  `created_datetime` datetime NOT NULL,
  `expired_datetime` datetime DEFAULT NULL,
  `token` varchar(200) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_association`
--
ALTER TABLE `accounts_association`
 ADD PRIMARY KEY (`account_id_host`,`account_id_guest`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
 ADD PRIMARY KEY (`invitation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
MODIFY `invitation_id` int(11) NOT NULL AUTO_INCREMENT;