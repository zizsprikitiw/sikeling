-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2015 at 10:44 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eb_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_title` varchar(100) NOT NULL,
  `category_id` int(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `book_copies` int(11) NOT NULL,
  `book_pub` varchar(100) NOT NULL,
  `publisher_name` varchar(100) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `copyright_year` int(11) NOT NULL,
  `date_receive` varchar(20) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` varchar(30) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `book_title`, `category_id`, `author`, `book_copies`, `book_pub`, `publisher_name`, `isbn`, `copyright_year`, `date_receive`, `date_added`, `status`) VALUES
(15, 'Natural Resources', 8, 'Robin Kerrod', 2, 'Marshall Cavendish Corporation', 'Marshall', '07-1-0001', 1997, '', '2013-12-11 06:34:27', 'New'),
(16, 'Encyclopedia Americana', 5, 'Grolier', 20, 'Connecticut', 'Grolier Incorporation', '0-7172-0119-8', 1988, '', '2013-12-11 06:36:23', 'Archive'),
(17, 'Wind Effects On Building', 3, 'T.V. Lawson', 2, 'Pearson Education, Inc', 'Prentice Hall, New Jersey', '02-1-0001', 2004, '', '2013-12-11 06:39:17', 'Archive'),
(18, 'Pemprograman Animasi dan Game Profesional 2', 7, 'Agustinus Nalwan', 2, 'Indonesia', '..', '06-1-0001', 2013, '', '2013-12-11 06:41:53', 'New'),
(19, 'Science in our World', 4, 'Brian Knapp', 2, 'Regency Publishing Group', 'Prentice Hall, Inc', '03-1-0001', 1996, '', '2013-12-11 06:44:44', 'Lost'),
(20, 'Literature Modelling', 9, 'Greg Glowka', 2, 'Regency Publishing Group', 'Prentice Hall, Inc', '08-1-0001', 2001, '', '2013-12-11 06:47:44', 'Old'),
(21, 'Lexicon Universal Encyclopedia', 8, 'Lexicon', 2, 'Lexicon Publication', 'Pulication Inc., Lexicon', '07-1-0002', 1993, '', '2013-12-11 06:49:53', 'Old'),
(22, 'Science and Invention Encyclopedia', 8, 'Clarke Donald, Dartford Mark', 2, 'H.S. Stuttman inc. Publishing', 'Publisher , Westport Connecticut', '07-1-0003', 1992, '', '2013-12-11 06:52:58', 'New'),
(23, 'Integrated Science Textbook ', 4, 'Merde C. Tan', 2, 'Vibal Publishing House Inc.', '12536. Araneta Avenue Corner Ma. Clara St., Quezon City', '03-1-0002', 2009, '', '2013-12-11 06:55:27', 'New'),
(24, 'Aerodynamics Aeronatics and Flight Mechanics', 5, 'Barnes W. McCormik', 2, 'The McGrawHill Companies Inc.', 'McGrawhill', '04-1-0001', 2008, '', '2013-12-11 06:57:35', 'New'),
(25, 'Wiki at Panitikan ', 8, 'Lorenza P. Avera', 2, 'JGM & S Corporation', 'JGM & S Corporation', '07-1-0004', 2000, '', '2013-12-11 06:59:24', 'Damage'),
(26, 'English Expressways TextBook for 4th year', 1, 'Virginia Bermudez Ed. O. et al', 2, 'SD Publications, Inc.', 'Gregorio Araneta Avenue, Quezon City', '00-1-0001', 2007, '', '2013-12-11 07:01:25', 'Damage'),
(27, 'Asya Pag-usbong Ng Kabihasnan ', 8, 'Ricardo T. Jose, Ph . D.', 2, 'Vibal Publishing House Inc.', 'Araneta Avenue . Cor. Maria Clara St., Quezon City', '02-1-0002', 2008, '', '2013-12-11 07:02:56', 'New'),
(28, 'Literature (the readers choice)', 9, 'Glencoe McGraw Hill', 2, '..', 'the McGrawHill Companies Inc', '08-1-0002', 2001, '', '2013-12-11 07:05:25', 'Damage'),
(29, 'Beloved a Novel', 9, 'Toni Morrison', 2, '..', 'Alfred A. Knoff, Inc', '08-1-0003', 1987, '', '2013-12-11 07:07:02', 'Old'),
(30, 'Silver Burdett Engish', 2, 'Judy Brim', 2, 'Silver Burdett Company', 'Silver', '01-1-0001', 1985, '', '2013-12-11 09:22:50', 'Old'),
(31, 'The Corporate Warriors (Six Classic Cases in American Business)', 8, 'Douglas K. Ramsey', 2, 'Houghton Miffin Company', '..', '07-1-0005', 1987, '', '2013-12-11 09:25:32', 'Old'),
(32, 'Introduction to Information System', 9, 'Cristine Redoblo', 2, 'CHMSC', 'Brian INC', '08-1-0004', 2013, '', '2014-01-17 19:00:10', 'New'),
(33, 'abc', 2, 'nana', 1, 'kabin1', 'nini', '0-010-001', 2014, '', '2015-01-06 13:43:24', 'Archive'),
(34, 'matematika', 1, 'cccc', 2, 'gfgffh', 'ddddd', '00-1-0001', 0, '', '2015-01-21 15:16:07', 'New'),
(35, 'matematika', 1, 'tttt', 2, 'ggggggggg', 'ggggggggg', '0000003', 0, '', '2015-01-21 15:18:00', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE IF NOT EXISTS `borrow` (
  `borrow_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(50) NOT NULL,
  `date_borrow` varchar(100) NOT NULL,
  `due_date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`borrow_id`),
  KEY `borrowerid` (`member_id`),
  KEY `borrowid` (`borrow_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=495 ;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`borrow_id`, `member_id`, `date_borrow`, `due_date`) VALUES
(484, 55, '2014-03-20 23:50:27', '21/03/2014'),
(483, 55, '2014-03-20 23:49:34', '21/03/2014'),
(482, 52, '2014-03-20 23:38:22', '03/01/2014'),
(485, 53, '2015-01-06 13:36:56', '07/01/2015'),
(486, 59, '2015-01-06 13:38:47', '07/01/2015'),
(487, 53, '2015-01-06 13:40:12', '06/01/2015'),
(488, 54, '2015-01-06 13:57:52', '06/01/2015'),
(489, 54, '2015-01-06 13:58:32', '06/01/2015'),
(490, 53, '2015-01-06 22:48:58', '06/01/2015'),
(491, 63, '2015-01-06 22:51:48', '06/01/2015'),
(492, 65, '2015-01-07 13:49:28', '07/01/2015'),
(493, 66, '2015-01-21 15:11:01', '21/01/2015'),
(494, 66, '2015-01-21 15:12:04', '21/01/2015');

-- --------------------------------------------------------

--
-- Table structure for table `borrowdetails`
--

CREATE TABLE IF NOT EXISTS `borrowdetails` (
  `borrow_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `borrow_id` int(11) NOT NULL,
  `borrow_status` varchar(50) NOT NULL,
  `date_return` varchar(100) NOT NULL,
  PRIMARY KEY (`borrow_details_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=185 ;

--
-- Dumping data for table `borrowdetails`
--

INSERT INTO `borrowdetails` (`borrow_details_id`, `book_id`, `borrow_id`, `borrow_status`, `date_return`) VALUES
(164, 16, 484, 'returned', '2014-12-19 02:18:37'),
(162, 15, 482, 'returned', '2015-01-07 00:37:18'),
(163, 15, 483, 'returned', '2014-12-19 02:17:53'),
(165, 15, 485, 'pending', ''),
(166, 17, 485, 'pending', ''),
(167, 22, 486, 'pending', ''),
(168, 23, 486, 'pending', ''),
(169, 15, 487, 'pending', ''),
(170, 15, 488, 'returned', '2015-01-20 20:00:28'),
(171, 18, 488, 'returned', '2015-01-20 20:00:48'),
(172, 22, 489, 'returned', '2015-01-20 20:01:08'),
(173, 23, 489, 'returned', '2015-01-20 20:00:12'),
(174, 27, 489, 'returned', '2015-01-20 19:59:59'),
(175, 15, 490, 'pending', ''),
(176, 18, 490, 'pending', ''),
(177, 22, 490, 'pending', ''),
(178, 24, 491, 'pending', ''),
(179, 32, 491, 'pending', ''),
(180, 15, 492, 'pending', ''),
(181, 18, 492, 'pending', ''),
(182, 22, 492, 'pending', ''),
(183, 15, 493, 'returned', '2015-01-21 15:13:03'),
(184, 17, 494, 'returned', '2015-01-21 15:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_id` (`category_id`),
  KEY `classid` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=801 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `classname`) VALUES
(1, 'General'),
(2, 'General Journals'),
(3, 'Scientific Journals'),
(4, 'Scientific Books'),
(5, 'NASA Journals'),
(6, 'Scientific Magazines'),
(7, 'Audio Visual Books'),
(8, 'Encyclopedia'),
(9, 'References');

-- --------------------------------------------------------

--
-- Table structure for table `lost_book`
--

CREATE TABLE IF NOT EXISTS `lost_book` (
  `Book_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ISBN` int(11) NOT NULL,
  `Member_No` varchar(50) NOT NULL,
  `Date Lost` date NOT NULL,
  PRIMARY KEY (`Book_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `year_level` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `firstname`, `lastname`, `gender`, `address`, `contact`, `type`, `year_level`, `status`) VALUES
(52, 'Mark', 'Sanchez', 'Male', 'Talisay', '212010', 'Teacher', 'Faculty', 'Active'),
(53, 'April Joy', 'Aguilar', 'Female', 'STM Permatasari', '', 'Student', 'Second Year', 'Active'),
(54, 'Alfonso', 'Pancho', 'Male', 'STM Permatasari', '', 'Student', 'First Year', 'Active'),
(55, 'Jonathan ', 'Antanilla', 'Male', 'Lapan', '', 'Lapan - Pustekbang', 'Fourth Year', 'Active'),
(56, 'Renzo Bryan', 'Pedroso', 'Male', 'Silay City', '03030', 'Student', 'Third Year', 'Active'),
(57, 'Eleazar', 'Duterte', 'Male', 'Lapan', '', 'Lapan - Aerodinamika', 'Second Year', 'Active'),
(58, 'Ellen Mae', 'Espino', 'Female', 'Lapan', '', 'Lapan - Aerostuktur', 'First Year', 'Active'),
(59, 'Ruth', 'Magbanua', 'Female', 'Lapan', '', 'Lapan - Aerodinamika', 'Second Year', 'Active'),
(60, 'Shaina Marie', 'Gabino', 'Female', 'jakarta', '', 'Teacher', 'Third Year', 'Active'),
(62, 'Chairty Joy', 'Punzalan', 'Female', 'SMUN 32', '', 'Teacher', 'Faculty', 'Active'),
(63, 'Kristine May', 'Dela Rosa', 'Female', 'Silay City', '1321', 'Student', 'Second Year', 'Active'),
(64, 'Chinie marie', 'Laborosa', 'Female', 'Lapan', '', 'Lapan - Avionik', 'Second Year', 'Active'),
(65, 'Ruby', 'Morante', 'Female', 'Lapan', '', 'Lapan - Avionik', 'Faculty', 'Active'),
(66, 'aaaa', 'bbbbb', 'Male', 'cccc', '02190000000', 'Student', 'First Year', '');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `borrowertype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowertype` (`borrowertype`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `borrowertype`) VALUES
(2, 'Teacher'),
(20, 'Employee'),
(21, 'Non-Teaching'),
(22, 'Student'),
(32, 'Contruction');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `firstname`, `lastname`) VALUES
(2, 'admin', 'admin', 'renny', 'agustina');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
