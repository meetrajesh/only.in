-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 27, 2012 at 07:56 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.13

--
-- Database: `onlyin`
--

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `subin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `img_url` varchar(100) NOT NULL,
  `imgur_raw_json` text NOT NULL,
  `stamp` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `subin_id` (`subin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subins`
--

DROP TABLE IF EXISTS `subins`;
CREATE TABLE IF NOT EXISTS `subins` (
  `subin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`subin_id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `subins`
--

INSERT INTO `subins` (`subin_id`, `name`, `slug`, `user_id`) VALUES
(1, 'Acapulco', 'acapulco', 0),
(2, 'Aguascalientes', 'aguascalientes', 0),
(3, 'Austin', 'austin', 0),
(4, 'Calgary', 'calgary', 0),
(5, 'Charlotte', 'charlotte', 0),
(6, 'Chicago', 'chicago', 0),
(7, 'Chihuahua', 'chihuahua', 0),
(8, 'Ciudad Juárez', 'ciudad-juarez', 0),
(9, 'Columbus', 'columbus', 0),
(10, 'Culiacán', 'culiacan', 0),
(11, 'Dallas', 'dallas', 0),
(12, 'Detroit', 'detroit', 0),
(13, 'Ecatepec de Morelos', 'ecatepec-de-morelos', 0),
(14, 'Edmonton', 'edmonton', 0),
(15, 'El Paso', 'el-paso', 0),
(16, 'Fort Worth', 'fort-worth', 0),
(17, 'Guadalajara', 'guadalajara', 0),
(18, 'Guadalupe', 'guadalupe', 0),
(19, 'Guatemala City', 'guatemala-city', 0),
(20, 'Havana', 'havana', 0),
(21, 'Hermosillo', 'hermosillo', 0),
(22, 'Houston', 'houston', 0),
(23, 'Indianapolis', 'indianapolis', 0),
(24, 'Jacksonville', 'jacksonville', 0),
(25, 'Kingston', 'kingston', 0),
(26, 'León', 'leon', 0),
(27, 'Los Angeles', 'los-angeles', 0),
(28, 'Memphis', 'memphis', 0),
(29, 'Mérida', 'mérida', 0),
(30, 'Mexicali', 'mexicali', 0),
(31, 'Mexico City', 'mexico-city', 0),
(32, 'Mississauga', 'mississauga', 0),
(33, 'Monterrey', 'monterrey', 0),
(34, 'Montréal', 'montréal', 0),
(35, 'Naucalpan', 'naucalpan', 0),
(36, 'New York City', 'new-york-city', 0),
(37, 'Nezahualcóyotl', 'nezahualcoyotl', 0),
(38, 'Ottawa', 'ottawa', 0),
(39, 'Philadelphia', 'philadelphia', 0),
(40, 'Phoenix', 'phoenix', 0),
(41, 'Port-au-Prince', 'port-au-prince', 0),
(42, 'Puebla', 'puebla', 0),
(43, 'Saltillo', 'saltillo', 0),
(44, 'San Antonio', 'san-antonio', 0),
(45, 'San Diego', 'san-diego', 0),
(46, 'San Francisco', 'san-francisco', 0),
(47, 'San Jose', 'san-jose', 0),
(48, 'San Luis Potosí', 'san-luis-potosi', 0),
(49, 'Santiago', 'santiago', 0),
(50, 'Santo Domingo', 'santo-domingo', 0),
(51, 'Tegucigalpa', 'tegucigalpa', 0),
(52, 'Tijuana', 'tijuana', 0),
(53, 'Tlalnepantla de Baz', 'tlalnepantla-de-baz', 0),
(54, 'Toronto', 'toronto', 0),
(55, 'Winnipeg', 'winnipeg', 0),
(56, 'Zapopan', 'zapopan', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `stamp` int(11) NOT NULL,
  `is_fake` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
