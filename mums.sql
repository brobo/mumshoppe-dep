-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Generation Time: Sep 04, 2014 at 05:59 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

--
-- Database: `mums`
--

-- --------------------------------------------------------

--
-- Table structure for table `accent_bow`
--

CREATE TABLE IF NOT EXISTS `accent_bow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `image` blob NOT NULL,
  `image_mime` varchar(31) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accent_bow_FI_1` (`grade_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `accessory`
--

CREATE TABLE IF NOT EXISTS `accessory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `underclassman` tinyint(1) NOT NULL DEFAULT '0',
  `junior` tinyint(1) NOT NULL DEFAULT '0',
  `senior` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` blob NOT NULL,
  `image_mime` varchar(31) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `accessory_category`
--

CREATE TABLE IF NOT EXISTS `accessory_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `backing`
--

CREATE TABLE IF NOT EXISTS `backing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `image` blob NOT NULL,
  `image_mime` varchar(31) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `backing_FI_1` (`size_id`),
  KEY `backing_FI_2` (`grade_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `bear`
--

CREATE TABLE IF NOT EXISTS `bear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `underclassman` tinyint(1) NOT NULL,
  `junior` tinyint(1) NOT NULL,
  `senior` tinyint(1) DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `image` blob NOT NULL,
  `image_mime` varchar(31) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  `phone` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE IF NOT EXISTS `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`id`, `name`) VALUES
(1, 'Underclassman'),
(2, 'Junior'),
(3, 'Senior');

-- --------------------------------------------------------

--
-- Table structure for table `letter`
--

CREATE TABLE IF NOT EXISTS `letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `mum`
--

CREATE TABLE IF NOT EXISTS `mum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_name` varchar(31) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `backing_id` int(11) DEFAULT NULL,
  `accent_bow_id` int(11) DEFAULT NULL,
  `letter1_id` int(11) DEFAULT NULL,
  `name_ribbon1` varchar(255) DEFAULT NULL,
  `letter2_id` int(11) DEFAULT NULL,
  `name_ribbon2` varchar(255) DEFAULT NULL,
  `status_id` int(11) DEFAULT '1',
  `paid` tinyint(1) DEFAULT NULL,
  `deposit_sale_id` varchar(127) NOT NULL,
  `paid_sale_id` varchar(127) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;
-- --------------------------------------------------------

--
-- Table structure for table `mum_accessory`
--

CREATE TABLE IF NOT EXISTS `mum_accessory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mum_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mum_trinket_FI_1` (`mum_id`),
  KEY `mum_trinket_FI_2` (`accessory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `mum_bear`
--

CREATE TABLE IF NOT EXISTS `mum_bear` (
  `mum_id` int(11) NOT NULL,
  `bear_id` int(11) NOT NULL,
  PRIMARY KEY (`mum_id`,`bear_id`),
  KEY `mum_bear_FI_2` (`bear_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `password_recovery`
--

CREATE TABLE IF NOT EXISTS `password_recovery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `keyword` varchar(15) NOT NULL,
  `expiration` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `password_recovery_FI_1` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`) VALUES
(1, 'Mum'),
(2, 'Garter');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE IF NOT EXISTS `size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `bear_limit` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` blob NOT NULL,
  `image_mime` varchar(31) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `size_FI_1` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Designing'),
(2, 'Ordered'),
(3, 'Name ribbons made'),
(4, 'Bagged'),
(5, 'Assembled'),
(6, 'Quality controlled'),
(7, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE IF NOT EXISTS `volunteer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `rights` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`id`, `email`, `password`, `name`, `phone`, `rights`) VALUES
(1, 'root@root.com', '$2y$10$gkDt.gpEuekue90a44KIYO7JVHqbEi2mQ3xWCIVQP9lJbG.wG.YmG', 'Root Volunteer', NULL, 511);

