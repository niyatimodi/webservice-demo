-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2015 at 12:41 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `service`
--

-- --------------------------------------------------------

--
-- Table structure for table `emergency`
--

CREATE TABLE IF NOT EXISTS `emergency` (
  `e_id` int(4) NOT NULL AUTO_INCREMENT,
  `police_Number` bigint(10) NOT NULL,
  `Hospital_Number` bigint(10) NOT NULL,
  `Fire_Number` bigint(10) NOT NULL,
  PRIMARY KEY (`e_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `emergency`
--

INSERT INTO `emergency` (`e_id`, `police_Number`, `Hospital_Number`, `Fire_Number`) VALUES
(1, 100, 108, 123);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE IF NOT EXISTS `maintenance` (
  `m_id` int(4) NOT NULL AUTO_INCREMENT,
  `House_Number` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `stetus` varchar(40) NOT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`m_id`, `House_Number`, `month`, `rate`, `stetus`) VALUES
(1, '105', 'aug', '600', 'pending'),
(2, '106', 'aug', '600', 'pending'),
(3, '105', 'sep', '600', 'Aprove'),
(4, '105', 'oct', '600', 'Aprove');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updatedate` datetime NOT NULL,
  `username` varchar(150) NOT NULL,
  `birthDate` date NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `email`, `password`, `updatedate`, `username`, `birthDate`) VALUES
(1, 'test', 'ets', 'set', 'trrer', '2015-08-21 13:53:58', 'tewst', '2015-08-28'),
(2, 'vishal', 'purohit', 'vishal.purohit@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25'),
(3, 'vishal', 'purohit', 'vishal.purohit@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25'),
(4, 'vishal', 'purohit', 'vishal.purohit@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25'),
(5, 'vishal', 'purohit', 'vishal.purohit@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25'),
(6, 'vishal', 'purohit', 'vishal.purohi1t@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25'),
(7, 'vishal', 'purohit', 'vishal1.purohit@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25'),
(8, 'vishal', 'purohit', 'vishal2.purohit@ifuturz.com', '123Test', '0000-00-00 00:00:00', 'vishal', '1987-10-25');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
