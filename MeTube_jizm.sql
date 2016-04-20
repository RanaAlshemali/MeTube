-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: mysql1.cs.clemson.edu
-- Generation Time: Apr 19, 2016 at 10:30 PM
-- Server version: 5.5.47-0ubuntu0.12.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `MeTube_jizm`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `username` varchar(30) NOT NULL,
  `password` varchar(30) DEFAULT NULL,
  `FristName` varchar(80) DEFAULT NULL,
  `LastName` varchar(80) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Gender` char(1) DEFAULT NULL,
  `BirthDate` date NOT NULL,
  `ID` bigint(120) NOT NULL AUTO_INCREMENT,
  `permissions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `username_3` (`username`),
  UNIQUE KEY `username_4` (`username`),
  UNIQUE KEY `Email` (`Email`),
  KEY `username_2` (`username`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `FristName`, `LastName`, `Email`, `Gender`, `BirthDate`, `ID`, `permissions`) VALUES
('asdf', 'asdfasdf', 'sadf', 'asdf', 'asdfd@gmail.com', 'f', '1999-12-12', 2, 0),
('asdfasdf', 'asdfasdf', 'asdfasdf', 'asdfasdf', 'asdfasdf', 'a', '1980-12-12', 4, 0),
('asdfasdfa', 'a', 'a', 'asdf', 'asadfd@gmail.com', 'a', '1902-12-12', 3, 0),
('kevin', 'kevin', 'James', 'Wang', 'asdf@gmail.com', 'M', '1999-12-12', 1, -1),
('Rana', '123456', NULL, NULL, NULL, NULL, '1990-11-19', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `blockList`
--

CREATE TABLE IF NOT EXISTS `blockList` (
  `blacklist_id` int(11) NOT NULL AUTO_INCREMENT,
  `blocklister` varchar(30) NOT NULL,
  `blocklistee` varchar(30) NOT NULL,
  PRIMARY KEY (`blacklist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `blockList`
--

INSERT INTO `blockList` (`blacklist_id`, `blocklister`, `blocklistee`) VALUES
(5, 'kevin', 'asdf');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `username` varchar(30) NOT NULL,
  `mediaid` int(11) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentID`, `content`, `username`, `mediaid`, `dateCreated`) VALUES
(1, 'sdfg', 'sdfg', 2323, '2016-04-14 00:02:56'),
(2, 'asdf', 'kevin', 123, '2016-04-16 22:41:13'),
(6, 'comment test', 'kevin', 9, '2016-04-16 23:04:03'),
(11, 'wow', 'kevin', 7, '2016-04-18 22:17:44'),
(19, 'you suck', 'kevin', 7, '2016-04-19 20:52:35');

-- --------------------------------------------------------

--
-- Table structure for table `favList`
--

CREATE TABLE IF NOT EXISTS `favList` (
  `favid` int(11) NOT NULL AUTO_INCREMENT,
  `mediaid` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  PRIMARY KEY (`favid`),
  UNIQUE KEY `mediaid` (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=357 ;

--
-- Dumping data for table `favList`
--

INSERT INTO `favList` (`favid`, `mediaid`, `username`) VALUES
(315, 12, 'kevin'),
(340, 30, 'kevin'),
(346, 25, 'kevin'),
(348, 0, 'kevin'),
(355, 33, 'kevin');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `filename` varchar(40) DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `mediaid` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) DEFAULT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(100) NOT NULL,
  `keywords` varchar(20) NOT NULL,
  `duration` varchar(5) NOT NULL DEFAULT '00:00',
  `privacy` varchar(10) NOT NULL DEFAULT 'public',
  `catagory` varchar(50) NOT NULL DEFAULT 'People & Blogs' COMMENT 'Film & Animation Autos & Vehicles Music Pets & Animals Sports Travel & Events  Gaming People & Blogs Comedy Entertainment News & Politics Howto & Style Education Science & Technology Nonprofits & Activism',
  `allowComments` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`filename`, `username`, `type`, `mediaid`, `path`, `dateCreated`, `description`, `keywords`, `duration`, `privacy`, `catagory`, `allowComments`) VALUES
('Work+-+Rihanna+ft.+Drake+.mp3', 'rana', 'audio/mp3', 7, 'uploads/rana/Work+-+Rihanna+ft.+Drake+.mp3', '0000-00-00 00:00:00', '', '', '00:00', 'public', 'Entertainment', 1),
('Motivational+short+video+-+How+to+succee', 'rana', 'video/mp4', 8, 'uploads/rana/Motivational+short+video+-+How+to+succeed+-+cartoon.mp4', '0000-00-00 00:00:00', '', '', '00:00', 'public', 'People & Blogs', 1),
('Child4.jpg', 'rana', 'image/jpeg', 9, 'uploads/rana/Child4.jpg', '0000-00-00 00:00:00', '', '', '00:00', 'public', 'People & Blogs', 0),
('song1.mp3', 'ralshem', 'audio/mp3', 11, 'uploads/ralshem/song1.mp3', '0000-00-00 00:00:00', '', '', '00:00', 'public', 'People & Blogs', 1),
('Work+-+Rihanna+ft.+Drake+.mp3', 'ralshem', 'audio/mp3', 12, 'uploads/ralshem/Work+-+Rihanna+ft.+Drake+.mp3', '2016-04-03 05:44:02', '', '', '00:00', 'public', 'People & Blogs', 1),
('screenshot.png', 'ralshem', 'image/png', 13, 'uploads/ralshem/screenshot.png', '2016-04-03 06:21:36', '', '', '00:00', 'public', 'People & Blogs', 1),
('header.jpg', 'ralshem', 'image/jpeg', 14, 'uploads/ralshem/header.jpg', '2016-04-03 07:54:18', '', '', '00:00', 'public', 'People & Blogs', 1),
('My+Movie.mp4', 'ralshem', 'video/mp4', 15, 'uploads/ralshem/My+Movie.mp4', '2016-04-03 08:04:24', '', '', '00:00', 'public', 'People & Blogs', 1),
('123.jpg', 'ralshem', 'image/jpeg', 16, 'uploads/ralshem/123.jpg', '2016-04-04 03:47:03', '', '', '00:00', 'public', 'People & Blogs', 1),
('wheel.png', 'rana', 'image/png', 17, 'uploads/rana/wheel.png', '2016-04-04 04:46:40', '', '', '00:00', 'public', 'People & Blogs', 1),
('Screenshot+2016-04-06+02.34.01.png', 'ralshem', 'image/png', 24, 'uploads/ralshem/Screenshot+2016-04-06+02.34.01.png', '2016-04-08 05:55:47', '', '', '00:00', 'public', 'People & Blogs', 1),
('Screenshot+2016-03-27+23.38.43.png', 'ralshem', 'image/png', 25, 'uploads/ralshem/Screenshot+2016-03-27+23.38.43.png', '2016-04-08 05:59:48', '', '', '00:00', 'public', 'People & Blogs', 1),
('Stat Anal', 'ralshem', 'image/png', 29, 'uploads/ralshem/Num+of+sales.png', '2016-04-08 06:13:13', '', '', '00:00', 'public', 'People & Blogs', 1),
('Screen Shot', 'ralshem', 'image/png', 30, 'uploads/ralshem/Screenshot+2015-12-14+11.16.59.png', '2016-04-08 06:39:39', 'a new screen shot', 'ScreenShot', '00:00', 'public', 'People & Blogs', 1),
('Add fav', 'ralshem', 'image/png', 31, 'uploads/ralshem/Star-Full.png', '2016-04-10 05:14:30', '1', 'star', '00:00', 'public', 'People & Blogs', 1),
('graph', 'kevin', 'image/png', 33, 'uploads/kevin/3.png', '2016-04-17 02:53:33', 'graph', 'graph', '00:00', 'public', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `playList`
--

CREATE TABLE IF NOT EXISTS `playList` (
  `playlistname` varchar(15) NOT NULL,
  `username` varchar(30) NOT NULL,
  `mediaid` int(11) NOT NULL,
  PRIMARY KEY (`playlistname`,`username`,`mediaid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `playList`
--

INSERT INTO `playList` (`playlistname`, `username`, `mediaid`) VALUES
('addtoplaylist', 'kevin', 16),
('Icons', 'kevin', 0),
('Icons', 'kevin', 9),
('Icons', 'kevin', 16),
('Icons', 'kevin', 30),
('Riri', 'kevin', 0),
('Riri', 'kevin', 7);

-- --------------------------------------------------------

--
-- Table structure for table `PMs`
--

CREATE TABLE IF NOT EXISTS `PMs` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `usrfrom` varchar(30) NOT NULL,
  `usrto` varchar(30) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(120) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `PMs`
--

INSERT INTO `PMs` (`ID`, `usrfrom`, `usrto`, `date`, `title`, `body`) VALUES
(1, 'kevin', 'asdf', '2016-04-14 00:28:21', 'asdf', 'asdf'),
(2, 'kevin', 'kevin', '2016-04-14 00:28:27', 'asdf', 'asdf'),
(3, 'kevin', 'xz', '2016-04-14 00:53:22', 'dfssa', 'sdf'),
(4, 'kevin', 'sadf', '2016-04-14 00:56:29', 'sadf', 'sdf'),
(5, 'kevin', 'Rana', '2016-04-14 01:11:17', 'Welcome', 'welcome to MeTube!'),
(7, 'kevin', 'asdf', '2016-04-14 03:22:37', 'asdf', 'asdf'),
(8, 'kevin', 'as', '2016-04-14 03:22:47', 'as', 'as'),
(9, 'kevin', 'asdfd', '2016-04-14 03:27:29', 'asdf', 'd'),
(10, 'asdf', 'asdf', '2016-04-14 15:04:20', 'asdf', 'asdf'),
(11, 'Rana', 'kevin', '2016-04-14 15:32:24', 'hello', 'hello'),
(12, 'kevin', 'Rana', '2016-04-14 21:07:16', 'asdfsdafasdf', 'sdafasdfsafds'),
(14, 'asdf', 'kevin', '2016-04-19 19:38:46', '%$^# you!!!!', '#@!# &^%% 2@#$@@!Y ^%&$^&!!!'),
(15, 'kevin', 'Rana', '2016-04-19 21:01:43', 'hello', 'asdf');

-- --------------------------------------------------------

--
-- Table structure for table `userlevelpermissions`
--

CREATE TABLE IF NOT EXISTS `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevelpermissions`
--

INSERT INTO `userlevelpermissions` (`userlevelid`, `tablename`, `permission`) VALUES
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}account', 37),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}comments', 32),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}favList', 32),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}hello.php', 0),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}media', 32),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}pm', 32),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}userlevelpermissions', 0),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}userlevels', 0),
(-2, '{7332429A-DA27-4727-A674-641D4F5156F6}users', 32),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}account', 37),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}comments', 32),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}favList', 32),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}hello.php', 0),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}media', 32),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}pm', 32),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}userlevelpermissions', 0),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}userlevels', 0),
(0, '{7332429A-DA27-4727-A674-641D4F5156F6}users', 32);

-- --------------------------------------------------------

--
-- Table structure for table `userlevels`
--

CREATE TABLE IF NOT EXISTS `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  PRIMARY KEY (`userlevelid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevels`
--

INSERT INTO `userlevels` (`userlevelid`, `userlevelname`) VALUES
(-2, 'Anonymous'),
(-1, 'Administrator'),
(0, 'Default');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
