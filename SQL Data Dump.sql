-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 10, 2021 at 10:49 PM
-- Server version: 10.1.48-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stu33001_2021_group_3_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `broken_returns`
--

CREATE TABLE `broken_returns` (
  `equipment_id` char(20) DEFAULT NULL,
  `event_id` char(20) DEFAULT NULL,
  `quantity` char(20) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `broken_returns`
--

INSERT INTO `broken_returns` (`equipment_id`, `event_id`, `quantity`, `verified`) VALUES
('1', '1', '1', 0),
('16', '1', '12', 0),
('30', '1', '3', 0),
('10', '58', '3', 0),
('20', '58', '23', 0),
('20', '1', '40', 0),
('15', '8', '2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `eircode` varchar(7) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `email` text,
  `is_business` tinyint(1) DEFAULT NULL,
  `user_password` char(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `eircode`, `phone`, `email`, `is_business`, `user_password`) VALUES
(1, 'Hugo Bolger', '10 Penny Lane', 'D02Y230', '014738293', 'hugo@bolger.com', 1, 'Aqw21'),
(801, 'Party Professionals', '123 Camden Street Lower, Dublin 2', 'DO2YR62', '014566644', 'carl@partyprofessionals.ie', 1, '123!pSt'),
(802, 'Tasty Catering', '41 Pearse Street, DUblin 2', 'DO2H308', '014321234', 'jan@tasty.ie', 1, '422?aQr'),
(803, 'The Events Company', 'The Exchange, George\'s Dock', 'DO1P2V6', '087333111', 'info@eventscompany.ie', 1, '931?rNb'),
(804, 'Dublin Hotel Group', '54 Bolton St, Dublin 1,', 'DO1TH63', '018211122', 'eventsmanager@dhg.ie', 1, '856[wXz'),
(806, 'Rosie Connolly', 'Trinity College,\r\nDublin', 'AWRYT67', '0871234567', 'connolr3@tcd.ie', 0, 'CAPlow12345'),
(808, 'Pae Bou', '248 Stop Start', 'W23RTS3', '019283726', 'asdf@gma.ie', 0, '1234asdf!'),
(810, 'Sam DeBurca Monty', 'The house at the end of the road, nantuckett', 'A75YH34', '0982347261', 'sam@blanchett.com', 0, 'iamdeburca'),
(817, 'Gerard Manly', '14 Longbeach Island, Merrion Walk, Co. Down', 'AWRYT67', '0862349193', 'manly.seecrest@gmail.com', 0, 'CAPlow123'),
(819, 'qwerty', '4261 Musqueam Drive', 'W23RTS3', '7785130279', 'hugobolgeris@gmail.com', 0, '1234qweR'),
(820, 'Rose Connolly', 'Milltown rd', 'ART4657', '0876061794', 'rosie.connolly@live.ie', 0, 'CAPlow1234'),
(830, 'Rose Connollytest', '15 Elton Grove,\r\nMillfarm', 'A34TR67', '0876061794', 'rosie.connolly99@gmail.com', 0, 'HEYthisworks12'),
(832, 'Laura Murphy', 'The Red Keep, Milltown', 'A65RTWT', '09891254', 'laura.murphy@gmail.com', 0, 'CAPlow1234567');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_charges`
--

CREATE TABLE `delivery_charges` (
  `chargetype` varchar(20) DEFAULT NULL,
  `county` varchar(255) NOT NULL,
  `dist_from_dub_km` int(11) DEFAULT NULL,
  `flat_rate` int(11) DEFAULT NULL,
  `rate_per_km` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_charges`
--

INSERT INTO `delivery_charges` (`chargetype`, `county`, `dist_from_dub_km`, `flat_rate`, `rate_per_km`) VALUES
('delivery', 'Carlow', 83, 75, '0.75'),
('delivery', 'Cavan', 119, 75, '0.75'),
('delivery', 'Clare', 103, 75, '0.75'),
('delivery', 'Cork', 252, 75, '0.75'),
('delivery', 'Donegal', 223, 75, '0.75'),
('delivery', 'Dublin', 0, 75, '0.75'),
('delivery', 'Galway', 207, 75, '0.75'),
('delivery', 'Kerry', 303, 75, '0.75'),
('delivery', 'Kildare', 59, 75, '0.75'),
('delivery', 'Kilkenny', 123, 75, '0.75'),
('delivery', 'Laois', 88, 75, '0.75'),
('delivery', 'Leitrim', 56, 75, '0.75'),
('delivery', 'Limerick', 195, 75, '0.75'),
('delivery', 'Longford', 145, 75, '0.75'),
('delivery', 'Louth', 53, 75, '0.75'),
('delivery', 'Mayo', 96, 75, '0.75'),
('delivery', 'Meath', 55, 75, '0.75'),
('delivery', 'Monaghan', 131, 75, '0.75'),
('delivery', 'Offaly', 101, 75, '0.75'),
('delivery', 'Roscommon', 155, 75, '0.75'),
('delivery', 'Sligo', 207, 75, '0.75'),
('delivery', 'Tipperary', 183, 75, '0.75'),
('VAT', 'VAT', 0, 0, '0.21'),
('delivery', 'Waterford', 164, 75, '0.75'),
('delivery', 'Westmeath', 123, 75, '0.75'),
('delivery', 'Wexford', 137, 75, '0.75'),
('delivery', 'Wicklow', 56, 75, '0.75');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `product_name` text NOT NULL,
  `rental_price_excl_vat` decimal(5,2) DEFAULT NULL,
  `setup_price_excl_vat` decimal(5,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `setup` tinyint(1) DEFAULT NULL,
  `sale` decimal(2,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `category`, `product_name`, `rental_price_excl_vat`, `setup_price_excl_vat`, `quantity`, `setup`, `sale`) VALUES
(1, 'Furniture', 'Table Rectangular 2ft x 2ft', '6.50', '2.50', 15, 1, '0.50'),
(2, 'Furniture', 'Table Rectangular 4ft x 2ft ', '8.50', '2.50', 18, 1, '0.00'),
(3, 'Furniture', 'Table Rectangular 6ft x 2ft ', '10.50', '2.50', 35, 1, '0.00'),
(4, 'Furniture', 'Table Rectangular 8ft x 2ft ', '12.50', '2.50', 19, 1, '0.00'),
(5, 'Furniture', 'Table Round 3ft', '9.00', '2.50', 20, 1, '0.00'),
(6, 'Furniture', 'Table Round 4ft', '11.00', '2.50', 30, 1, '0.00'),
(7, 'Furniture', 'Table Round 5ft', '15.00', '2.50', 45, 1, '0.00'),
(8, 'Furniture', 'Table Round 6ft', '18.00', '2.50', 54, 1, '0.00'),
(9, 'Furniture', 'Table Round 8ft', '45.00', '10.00', 60, 1, '0.00'),
(10, 'Furniture', 'Folding Chair Plastic', '2.00', '0.50', 800, 1, '0.00'),
(11, 'Furniture', 'Folding Chair Wooden', '5.00', '0.50', 700, 1, '0.00'),
(12, 'Furniture', 'Banquet Dining Chair', '8.00', '0.50', 110, 1, '0.00'),
(13, 'Glassware', 'Water Glass', '0.80', '0.00', 400, 0, '0.00'),
(14, 'Glassware', 'Wine Glass', '1.50', '0.00', 1550, 0, '0.00'),
(15, 'Glassware', 'Pint Glass', '0.80', '0.00', 1494, 0, '0.70'),
(16, 'Cutlery Set', 'Standard', '1.25', '0.00', 1974, 0, '0.00'),
(17, 'Cutlery Set', 'Fine', '2.60', '0.00', 1800, 0, '0.00'),
(18, 'Crockery', 'Cup', '0.50', '0.00', 0, 0, '0.00'),
(19, 'Crockery', 'Saucer', '0.50', '0.00', 2000, 0, '0.00'),
(20, 'Crockery', 'Plate 6 inch', '0.85', '0.00', 1960, 0, '0.00'),
(21, 'Crockery', 'Plate 10.5 inch', '1.15', '0.00', 2000, 0, '0.00'),
(22, 'Crockery', 'Plate 12 inch', '1.35', '0.00', 2000, 0, '0.00'),
(23, 'Crockery', 'Soup Bowl', '0.85', '0.00', 2000, 0, '0.00'),
(24, 'Linen', 'White Linen Rectangular 2ft x 2ft ', '6.00', '0.00', 2000, 0, '0.00'),
(25, 'Linen', 'White Linen Rectangular 4ft x 2ft ', '7.00', '0.00', 2000, 0, '0.00'),
(26, 'Linen', 'White Linen Rectangular 6ft x 2ft ', '8.50', '0.00', 2000, 0, '0.00'),
(27, 'Linen', 'White Linen Rectangular 8ft x 2ft ', '9.50', '0.00', 2000, 0, '0.00'),
(28, 'Linen', 'White Linen Round 3ft', '10.00', '0.00', 1800, 0, '0.10'),
(29, 'Linen', 'White Linen Round 4ft', '12.00', '0.00', 1800, 0, '0.00'),
(30, 'Linen', 'White Linen Round 5ft', '16.00', '0.00', 1794, 0, '0.00'),
(31, 'Linen', 'White Linen Round 6ft', '18.00', '0.00', 1800, 0, '0.00'),
(32, 'Linen', 'White Linen Round 8ft', '25.00', '0.00', 1800, 0, '0.00'),
(33, 'Marquees and Gazebos', 'Marquee 3 Metre Wide', '125.00', '40.00', 10, 1, '0.00'),
(34, 'Marquees and Gazebos', 'Marquee 4 Metre Wide', '150.00', '45.00', 15, 1, '0.00'),
(35, 'Marquees and Gazebos', 'Marquee 6 Metre Wide', '175.00', '50.00', 20, 1, '0.00'),
(36, 'Marquees and Gazebos', 'Marquee 9 Metre Wide', '200.00', '50.00', 10, 1, '0.00'),
(37, 'Marquees and Gazebos', 'Marquee 12 Metre Wide', '250.00', '70.00', 3, 1, '0.00'),
(38, 'Marquees and Gazebos', 'Gazebo 3x3M', '145.00', '40.00', 5, 1, '0.00'),
(39, 'Marquees and Gazebos', 'Gazebo 3x6M', '160.00', '50.00', 4, 1, '0.00'),
(40, 'Catering Equipment', 'Beer Cooler/Fridge Unit', '110.00', '20.00', 8, 1, '0.00'),
(41, 'Catering Equipment', 'Medium BBQ (Gas)', '80.00', '20.00', 12, 1, '0.00'),
(42, 'Catering Equipment', 'Large BBQ (Gas)', '100.00', '20.00', 3, 1, '0.00'),
(60, 'Furniture', 'BB', '123.00', '1.20', 30, 1, NULL),
(61, 'Furniture', 'BB', '123.00', '1.20', 30, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipment_rented`
--

CREATE TABLE `equipment_rented` (
  `equipment_id` int(4) NOT NULL,
  `events_id` int(6) NOT NULL,
  `quantity` int(3) NOT NULL,
  `setup` tinyint(1) DEFAULT NULL,
  `sale` decimal(5,2) DEFAULT NULL,
  `rental_price` decimal(5,2) DEFAULT NULL,
  `setup_price` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipment_rented`
--

INSERT INTO `equipment_rented` (`equipment_id`, `events_id`, `quantity`, `setup`, `sale`, `rental_price`, `setup_price`) VALUES
(1, 11, 1, 1, '0.00', '6.50', '2.50'),
(10, 11, 5, 1, '0.00', '2.00', '0.50'),
(41, 11, 2, 0, '0.00', '80.00', '20.00'),
(16, 11, 50, 0, '0.00', '1.25', '0.00'),
(20, 11, 50, 0, '0.00', '0.85', '0.00'),
(30, 11, 3, 0, '0.00', '16.00', '0.00'),
(1, 1, 1, 0, '0.00', '6.50', '2.50'),
(10, 1, 5, 0, '0.00', '2.00', '0.50'),
(41, 1, 2, 0, '0.00', '80.00', '20.00'),
(16, 1, 50, 0, '0.00', '1.25', '0.00'),
(20, 1, 50, 0, '0.00', '0.85', '0.00'),
(30, 1, 3, 0, '0.00', '16.00', '0.00'),
(1, 58, 1, 1, '0.00', '6.50', '2.50'),
(10, 58, 5, 1, '0.00', '2.00', '0.50'),
(41, 58, 2, 0, '0.00', '80.00', '20.00'),
(16, 58, 50, 0, '0.00', '1.25', '0.00'),
(20, 58, 50, 0, '0.00', '0.85', '0.00'),
(30, 58, 3, 0, '0.00', '16.00', '0.00'),
(1, 14, 1, 1, '0.00', '6.50', '2.50'),
(10, 14, 5, 1, '0.00', '2.00', '0.50'),
(41, 14, 2, 0, '0.00', '80.00', '20.00'),
(16, 14, 50, 0, '0.00', '1.25', '0.00'),
(20, 14, 50, 0, '0.00', '0.85', '0.00'),
(30, 14, 3, 0, '0.00', '16.00', '0.00'),
(13, 67, 100, 0, '0.00', '0.75', '0.00'),
(26, 67, 1, 0, '0.00', '8.50', '0.00'),
(1, 1, 1, 0, '0.00', '6.50', '2.50'),
(1, 1, 1, 0, '0.00', '6.50', '2.50'),
(1, 1, 1, 0, '0.00', '6.50', '2.50'),
(10, 1, 4, 0, '0.00', '2.00', '0.50'),
(10, 1, 4, 0, '0.00', '2.00', '0.50'),
(1, 134, 4, 0, '0.00', '6.50', '2.50'),
(13, 134, 13, 0, '0.00', '0.75', '0.00'),
(1, 138, 3, 0, '0.00', '6.50', '2.50'),
(16, 138, 100, 0, '0.00', '1.25', '0.00'),
(17, 138, 25, 0, '0.00', '2.50', '0.00'),
(24, 138, 2, 0, '0.00', '6.00', '0.00'),
(15, 138, 100, 0, '0.70', '0.80', '0.00'),
(37, 138, 1, 1, '0.00', '250.00', '70.00'),
(1, 139, 1, 0, '0.00', '6.50', '2.50'),
(23, 139, 100, 0, '0.00', '0.85', '0.00'),
(17, 139, 100, 0, '0.00', '2.50', '0.00'),
(27, 139, 40, 0, '0.00', '9.50', '0.00'),
(40, 170, 1, 0, '0.00', '110.00', '20.00'),
(1, 171, 7, 0, '0.00', '6.50', '2.50'),
(16, 171, 250, 0, '0.00', '1.25', '0.00'),
(15, 8, 2, 0, '0.00', '0.80', '0.00'),
(15, 24, 2, 0, '0.00', '0.80', '0.00'),
(8, 15, 2, 0, '0.00', '18.00', '2.50'),
(24, 15, 2, 0, '0.00', '6.00', '0.00'),
(10, 10, 2, 0, '0.00', '2.00', '0.50'),
(10, 10, 2, 0, '0.00', '2.00', '0.50'),
(40, 10, 2, 0, '0.20', '110.00', '20.00'),
(19, 10, 100, 0, '0.00', '0.50', '0.00'),
(10, 12, 5, 0, '0.00', '2.00', '0.50'),
(19, 12, 50, 0, '0.00', '0.50', '0.00'),
(24, 12, 100, 0, '0.20', '6.00', '0.00'),
(18, 12, 1, 0, '0.10', '0.50', '0.00'),
(14, 7, 50, 0, '0.00', '0.80', '0.00'),
(3, 7, 20, 1, '0.00', '10.50', '2.50'),
(38, 7, 2, 1, '0.20', '145.00', '40.00'),
(39, 7, 1, 1, '0.00', '160.00', '50.00'),
(16, 176, 10, 0, '0.00', '1.25', '0.00'),
(36, 176, 1, 1, '0.00', '200.00', '50.00'),
(7, 177, 10, 1, '0.00', '15.00', '2.50'),
(30, 177, 10, 0, '0.00', '16.00', '0.00'),
(21, 177, 10, 0, '0.00', '1.15', '0.00'),
(17, 177, 10, 0, '0.00', '2.50', '0.00'),
(13, 177, 10, 0, '0.00', '0.75', '0.00'),
(14, 177, 10, 0, '0.00', '0.80', '0.00'),
(23, 177, 10, 0, '0.50', '0.85', '0.00'),
(33, 178, 1, 1, '0.00', '125.00', '40.00'),
(40, 178, 3, 0, '0.00', '110.00', '20.00'),
(42, 178, 1, 0, '0.00', '100.00', '20.00'),
(41, 178, 2, 0, '0.00', '80.00', '20.00'),
(13, 179, 35, 0, '0.00', '0.75', '0.00'),
(33, 179, 2, 1, '0.00', '125.00', '40.00'),
(42, 179, 2, 1, '0.00', '100.00', '20.00'),
(14, 180, 100, 0, '0.00', '0.80', '0.00'),
(17, 180, 100, 0, '0.00', '2.50', '0.00'),
(19, 180, 100, 0, '0.00', '0.50', '0.00'),
(22, 180, 100, 0, '0.00', '1.35', '0.00'),
(1, 181, 2, 0, '0.00', '6.50', '2.50'),
(26, 181, 12, 0, '0.00', '8.50', '0.00'),
(39, 184, 1, 0, '0.30', '160.00', '50.00'),
(13, 184, 10, 0, '0.00', '0.75', '0.00'),
(35, 186, 1, 0, '0.00', '175.00', '50.00'),
(16, 187, 1, 0, '0.00', '1.25', '0.00'),
(34, 188, 1, 0, '0.00', '150.00', '45.00'),
(33, 202, 1, 1, '0.00', '125.00', '40.00'),
(14, 202, 12, 0, '0.00', '1.50', '0.00'),
(1, 202, 1, 1, '0.50', '6.50', '2.50'),
(33, 203, 1, 0, '0.00', '125.00', '40.00'),
(16, 203, 12, 0, '0.00', '1.25', '0.00'),
(40, 203, 3, 1, '0.00', '110.00', '20.00'),
(13, 203, 100, 0, '0.00', '0.75', '0.00'),
(39, 204, 1, 0, '0.00', '160.00', '50.00'),
(8, 204, 5, 0, '0.00', '18.00', '2.50'),
(31, 204, 5, 0, '0.00', '18.00', '0.00'),
(17, 204, 25, 0, '0.00', '2.60', '0.00'),
(22, 204, 25, 0, '0.00', '1.35', '0.00'),
(15, 204, 25, 0, '0.70', '0.80', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(40) NOT NULL,
  `cust_email` varchar(60) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `county` char(20) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `delivery_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `cust_email`, `location`, `county`, `start_date`, `end_date`, `start_time`, `end_time`, `delivery_status`) VALUES
(1, 'Triton Work Party', 'carl@partyprofessionals.ie', '1569 Wescam Court, Dublin', 'dublin', '2021-03-16', '2021-03-16', '19:00:00', '00:00:00', 0),
(2, 'Westcourt Bake Off', 'jan@tasty.ie', '1252 Parrill Court, Westcourt, Dublin', 'dublin', '2021-03-27', '2021-03-27', '14:00:00', '18:00:00', 0),
(4, 'Birthday Party', 'carl@partyprofessionals.ie', 'Halsworthy Barn, Ashburton, Dublin', 'dublin', '2021-03-11', '2021-03-11', '15:00:00', '18:00:00', 0),
(6, 'Tech Release Brunch', 'events_manager@dhg.ie', '11 Beech Drive, Syston, Dublin', 'dublin', '2021-04-15', '2021-04-15', '12:00:00', '14:00:00', 1),
(7, 'Quinceanera', 'info@eventscompany.ie', 'Holly Cottage, Bringsty Common, Bringsty, Dublin', 'dublin', '2021-03-17', '2021-03-17', '15:00:00', '22:00:00', 1),
(8, 'Wedding Reception', 'events_manager@dhg.ie', 'Upper Flat, 20 Cadets Walk, East Cowes, Dublin', 'dublin', '2021-03-10', '2021-03-10', '15:00:00', '18:00:00', 1),
(9, 'Food Exhibition', 'jan@tasty.ie', '3 Dalemain Mews, Dublin', 'dublin', '2021-04-08', '2021-04-08', '12:00:00', '19:00:00', 1),
(10, 'Big Birthday Bash', 'info@eventscompany.ie', '22 Anson Road, Locking, Dublin', 'dublin', '2021-03-16', '2021-03-16', '18:00:00', '23:00:00', 1),
(11, 'Hen Party', 'carl@partyprofessionals.ie', '3 Wood Road, Abercynon, Dublin', 'dublin', '2021-02-17', '2021-02-17', '17:00:00', '00:00:00', 1),
(12, 'Book Store Opening', 'info@eventscompany.ie', '8 Priory Road, Knowle, Dublin', 'dublin', '2021-02-25', '2021-02-25', '16:00:00', '17:00:00', 1),
(14, 'Triton Work Party', 'carl@partyprofessionals.ie', '1569 Wescam Court, Dublin', 'dublin', '2021-03-16', '2021-03-16', '19:00:00', '00:00:00', 1),
(15, 'Karl\'s Retirement', 'info@eventscompany.ie', '74 Newhall Court, Dublin', 'dublin', '2021-04-14', '2021-04-14', '16:00:00', '20:00:00', 1),
(53, 'Party in the USA', 'connolr3@tcd.ie', 'USA', 'dublin', '2021-04-23', '2021-04-23', '09:00:00', '15:30:00', 1),
(54, 'Retirement Party', 'carl@partyprofessionals.ie', 'The Marrion Hotel, Dublin 3', 'dublin', '2021-04-28', '2021-04-28', '15:00:00', '18:00:00', 1),
(58, 'Kevin\'s 45th Birthday Party', 'carl@partyprofessionals.ie', 'Avoca', 'dublin', '2021-02-26', '2021-02-26', '14:30:00', '15:30:00', 1),
(59, 'Sophie\'s 100th Birthday', 'carl@partyprofessionals.ie', 'The Marrion Hotel, Dublin 3', 'dublin', '2021-03-24', '2021-03-24', '15:25:00', '18:25:00', 1),
(60, '40th Birthday', 'carl@partyprofessionals.ie', 'blanchardstown', 'dublin', '2021-03-04', '2021-03-04', '15:00:00', '20:00:00', 1),
(67, 'Anniversary Dinner', 'carl@partyprofessionals.ie', 'Carlow Hotel Group', 'dublin', '2021-05-27', '2021-05-27', '16:00:00', '18:00:00', 1),
(73, 'Birthday Party', 'carl@partyprofessionals.ie', 'blanchardstown', 'dublin', '2021-02-26', '2021-02-26', '22:00:00', '23:00:00', 1),
(134, '5th Birthday Party', 'connolr3@tcd.ie', 'The Clarion Hotel', 'Carlow', '2021-03-11', '2021-03-12', '17:30:00', '00:00:00', 1),
(138, 'Sam\'s Quinceanera', 'sam@blanchett.com', 'The Greenwich, Port Street', 'Carlow', '2021-03-04', '2021-03-05', '13:00:00', '17:00:00', 1),
(139, 'Half Birthday Party', 'manly.seecrest@gmail.com', 'The Greenwich, Sandy Street', 'Carlow', '2021-04-08', '2021-04-09', '18:30:00', '00:30:00', 1),
(170, '100th Anniversary', 'connolr3@tcd.ie', 'Milltown Road, Jame\'s Street', 'Offaly', '2021-03-18', '2021-03-18', '10:30:00', '10:33:00', 0),
(171, 'klk', 'hugobolgeris@gmail.com', 'asda', 'Carlow', '2021-08-12', '2021-08-12', '12:00:00', '01:00:00', 1),
(176, 'Birthday Party', 'rosie.connolly@live.ie', 'Milltown rd.', 'Carlow', '2021-03-03', '2021-03-03', '13:00:00', '13:00:00', 1),
(177, 'Charity Ball', 'eventsmanager@dhg.ie', 'The Plunkett Hotel', 'Carlow', '2021-03-09', '2021-03-09', '12:00:00', '21:00:00', 1),
(178, 'Retirement Do', 'eventsmanager@dhg.ie', 'Carton Hotel Group', 'Meath', '2021-03-09', '2021-03-09', '14:00:00', '20:00:00', 1),
(179, 'Fundraiser', 'eventsmanager@dhg.ie', 'The Shelbourne', 'Dublin', '2021-05-11', '2021-05-11', '13:00:00', '13:00:00', 1),
(180, 'Birthday Party', 'eventsmanager@dhg.ie', 'The Marrion Hotel, Kings Road', 'Roscommon', '2021-10-09', '2021-10-09', '13:00:00', '17:00:00', 1),
(184, 'Mary\'s Birthday', 'connolr3@tcd.ie', 'The mill Street', 'Meath', '2021-04-10', '2021-04-10', '13:00:00', '13:00:00', 0),
(202, 'Birthday Party', 'laura.murphy@gmail.com', 'The old inn', 'Leitrim', '2021-07-03', '2021-07-03', '13:00:00', '14:00:00', 1),
(203, 'Bill\'s Retirement', 'connolr3@tcd.ie', 'The Old Inn, Derrynane', 'Leitrim', '2021-08-09', '2021-08-09', '13:00:00', '14:00:00', 1),
(204, 'Billy\'s 60th Birthday', 'carl@partyprofessionals.ie', 'Botanical Gardens', 'Kildare', '2021-04-04', '2021-04-04', '13:00:00', '13:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_hours`
--

CREATE TABLE `event_hours` (
  `event_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `vans_loading` time DEFAULT NULL,
  `van_enroute` time DEFAULT NULL,
  `registration` char(9) DEFAULT NULL,
  `dropoff_pickup` char(10) DEFAULT NULL,
  `shift_id` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_hours`
--

INSERT INTO `event_hours` (`event_id`, `staff_id`, `vans_loading`, `van_enroute`, `registration`, `dropoff_pickup`, `shift_id`) VALUES
(1, 1, '23:40:11', '17:13:31', '171-D-234', 'Drop-Off', '2'),
(7, 4, '14:19:03', '14:19:10', '152WM4762', 'Pick-Up', '7'),
(3, 2, NULL, NULL, '124KY6725', 'Drop-Off', '6'),
(7, 1, '14:28:06', '14:28:10', '143MH9863', 'Pick-Up', '3'),
(7, 1, '21:08:39', '21:09:41', '152WM4762', 'Drop-Off', '3'),
(67, 1, NULL, '17:13:31', '143MH9863', 'Pick-Up', '2'),
(9, 1, '08:31:19', '17:13:31', '124KY6725', 'Pick-Up', '2'),
(6, 1, '09:09:45', '17:13:31', '152WM4762', 'Drop-Off', '2'),
(7, 4, NULL, NULL, '143MH9863', 'Pick-Up', '7'),
(59, 1, '20:18:49', '20:20:28', '152WM4762', 'Drop-Off', '11'),
(60, 1, '20:18:49', '20:20:28', '152WM4762', 'Pick-Up', '11'),
(170, 3, NULL, NULL, 'Not Requi', 'Pick-Up', '25'),
(59, 5, NULL, NULL, '143MH9863', 'Pick-Up', '26'),
(178, 2, '19:08:42', '19:08:46', '143MH9863', 'Drop-Off', '28'),
(134, 5, NULL, NULL, '143MH9863', 'Drop-Off', '30'),
(14, 3, NULL, NULL, '143MH9863', 'Drop-Off', '31'),
(10, 3, NULL, NULL, '143MH9863', 'Pick-Up', '31'),
(1, 3, NULL, NULL, 'Not Requi', 'Pick-Up', '31'),
(2, 4, NULL, NULL, 'Not Requi', 'Drop-Off', '32'),
(2, 4, NULL, NULL, 'Not Requi', 'Pick-Up', '32'),
(2, 3, NULL, NULL, 'Not Requi', 'Pick-Up', '34'),
(8, 4, NULL, NULL, '143MH9863', 'Drop-Off', '36'),
(134, 5, NULL, NULL, '124KY6725', 'Pick-Up', '37'),
(4, 5, NULL, NULL, 'Not Requi', 'Drop-Off', '37'),
(8, 20, NULL, NULL, '124KY6725', 'Drop-Off', '40'),
(8, 20, NULL, NULL, '124KY6725', 'Pick-Up', '40'),
(10, 1, NULL, NULL, '124KY6725', 'Drop-Off', '22'),
(14, 1, NULL, NULL, '124KY6725', 'Pick-Up', '22'),
(10, 1, NULL, NULL, '124KY6725', 'Pick-Up', '22'),
(8, 2, '17:36:30', '17:36:41', '124KY6725', 'Drop-Off', '16'),
(8, 2, '00:00:00', '17:36:41', '124KY6725', 'Pick-Up', '16'),
(0, 1, NULL, NULL, 'Not Requi', '', '20'),
(0, 5, NULL, NULL, 'Not Requi', '', '37'),
(10, 2, NULL, NULL, '124KY6725', 'Drop-Off', '27'),
(10, 2, NULL, NULL, '124KY6725', 'Pick-Up', '27'),
(14, 2, NULL, NULL, '124KY6725', 'Pick-Up', '27'),
(1, 2, NULL, NULL, 'Not Requi', 'Drop-Off', '27');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `event_id` int(11) NOT NULL,
  `event_cost` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `vat_charged` decimal(5,2) DEFAULT NULL,
  `delivery_charged` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`event_id`, `event_cost`, `purchase_date`, `vat_charged`, `delivery_charged`) VALUES
(134, '192.36', '2021-03-07', '0.21', '137.25'),
(138, '827.31', '2021-03-07', '0.21', '137.25'),
(139, '876.04', '2021-03-07', '0.21', '137.25'),
(170, '157.30', '2021-03-08', '0.21', '150.75'),
(171, '591.36', '2021-03-08', '0.21', '137.25'),
(15, '53.00', '2020-12-12', '0.21', '75.00'),
(10, '268.00', '2020-12-12', '0.21', '75.00'),
(12, '517.95', '2020-12-12', '0.21', '75.00'),
(176, '454.88', '2021-03-08', '0.21', '137.25'),
(177, '610.66', '2021-03-09', '0.21', '137.25'),
(178, '1175.00', '2021-03-09', '0.21', '116.25'),
(179, '796.46', '2021-03-09', '0.21', '75.00'),
(180, '814.40', '2021-03-09', '0.21', '191.25'),
(181, '146.40', '2021-03-09', '0.22', '0.00'),
(184, '186.95', '2021-03-09', '0.21', '0.00'),
(186, '272.25', '2021-03-10', '0.21', '0.00'),
(187, '1.51', '2021-03-10', '0.21', '0.00'),
(202, '343.88', '2021-03-10', '0.21', '117.00'),
(203, '897.45', '2021-03-10', '0.21', '117.00'),
(204, '613.77', '2021-03-10', '0.21', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `employee_id` int(11) NOT NULL,
  `full_name` varchar(60) NOT NULL,
  `phone_number` char(10) NOT NULL,
  `position` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `on_shift` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`employee_id`, `full_name`, `phone_number`, `position`, `password`, `on_shift`) VALUES
(101, 'Duncan D\'Oghnots', '0876423172', 'driver', 'Duncan1D', 0),
(102, 'Ronald McDonald', '0875452375', 'driver', 'Ronald3M', 0),
(103, 'Chuck Fila', '0895241846', 'driver', 'Chucky3f', 0),
(104, 'Tack Obel', '0865432187', 'driver', 'Obellaciao7', 0),
(105, 'Clement Pekoe', '0851427852', 'driver', 'Hotcoco4eva', 0),
(106, 'Aidan MacKeaney', '0862741632', 'manager', 'f12TsD', 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff_shifts`
--

CREATE TABLE `staff_shifts` (
  `shift_id` int(11) NOT NULL,
  `staff_id` char(10) DEFAULT NULL,
  `shift_date` date DEFAULT NULL,
  `clock_in_time` time DEFAULT NULL,
  `clock_out_time` time DEFAULT NULL,
  `set_clock_in` time DEFAULT NULL,
  `set_clock_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_shifts`
--

INSERT INTO `staff_shifts` (`shift_id`, `staff_id`, `shift_date`, `clock_in_time`, `clock_out_time`, `set_clock_in`, `set_clock_out`) VALUES
(2, '1', '2021-03-09', '14:30:00', '21:30:00', '14:00:00', '21:00:00'),
(3, '1', '2021-03-05', NULL, NULL, '09:30:00', '14:45:00'),
(5, '1', '2021-03-25', NULL, NULL, '13:00:00', '15:30:00'),
(6, '2', '2021-03-23', NULL, NULL, '12:00:00', '15:30:00'),
(7, '4', '2021-03-31', NULL, NULL, '16:50:00', '19:45:00'),
(8, '5', '2021-04-12', NULL, NULL, '08:00:00', '14:15:00'),
(9, '1', '2021-05-01', NULL, NULL, '18:00:00', '23:45:00'),
(11, '1', '2021-03-04', NULL, '23:30:00', '20:30:00', '23:00:00'),
(15, '4', '2021-02-17', '10:45:00', NULL, '10:45:00', '15:00:00'),
(16, '2', '2021-03-10', '17:35:55', '17:40:16', '16:00:00', '20:00:00'),
(18, '1', '2021-03-10', '15:33:12', '15:38:36', '12:30:00', '14:00:00'),
(20, '1', '2021-03-11', NULL, NULL, '15:00:00', '20:00:00'),
(21, '5', '2021-03-16', NULL, NULL, '17:00:00', '19:30:00'),
(22, '1', '2021-03-16', NULL, NULL, '10:00:00', '12:30:00'),
(25, '3', '2021-03-18', NULL, NULL, '13:05:00', '16:25:00'),
(26, '5', '2021-03-24', NULL, NULL, '18:00:00', '19:30:00'),
(27, '2', '2021-03-16', NULL, NULL, '12:30:00', '14:25:00'),
(28, '2', '2021-03-09', '18:30:00', '21:30:00', '10:30:00', '14:00:00'),
(30, '5', '2021-03-12', NULL, NULL, '12:00:00', '13:00:00'),
(31, '3', '2021-03-16', NULL, NULL, '18:00:00', '21:00:00'),
(32, '4', '2021-03-27', NULL, NULL, '13:00:00', '14:00:00'),
(33, '4', '2021-03-27', NULL, NULL, '17:30:00', '19:00:00'),
(34, '3', '2021-03-27', NULL, NULL, '17:30:00', '19:00:00'),
(37, '5', '2021-03-11', NULL, NULL, '13:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vans`
--

CREATE TABLE `vans` (
  `registration_no` char(9) NOT NULL,
  `capacity` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vans`
--

INSERT INTO `vans` (`registration_no`, `capacity`) VALUES
('124KY6725', '4.2'),
('143MH9863', '4.2'),
('152WM4762', '4.2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_charges`
--
ALTER TABLE `delivery_charges`
  ADD PRIMARY KEY (`county`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `staff_shifts`
--
ALTER TABLE `staff_shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `vans`
--
ALTER TABLE `vans`
  ADD PRIMARY KEY (`registration_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=833;
--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `staff_shifts`
--
ALTER TABLE `staff_shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
