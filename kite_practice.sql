-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2022 at 07:46 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kite_practice`
--

-- --------------------------------------------------------

--
-- Table structure for table `tradebook`
--

CREATE TABLE `tradebook` (
  `entity_id` int(10) UNSIGNED NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `isin` varchar(255) NOT NULL,
  `trade_date` date NOT NULL,
  `exchange` enum('NSE') NOT NULL DEFAULT 'NSE',
  `segment` enum('EQ') NOT NULL DEFAULT 'EQ',
  `series` enum('EQ') NOT NULL DEFAULT 'EQ',
  `trade_type` enum('buy','sell') NOT NULL DEFAULT 'buy',
  `quantity` int(11) NOT NULL,
  `price` decimal(10,4) NOT NULL,
  `trade_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `order_execution_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tradebook`
--

INSERT INTO `tradebook` (`entity_id`, `symbol`, `isin`, `trade_date`, `exchange`, `segment`, `series`, `trade_type`, `quantity`, `price`, `trade_id`, `order_id`, `order_execution_time`) VALUES
(1, 'TCS', 'INE467B01029', '2022-05-23', 'NSE', 'EQ', 'EQ', 'sell', 20, '3272.9500', '75381613', '1300000001225190.00', '2022-05-23 09:20:52'),
(2, 'TCS', 'INE467B01029', '2022-05-23', 'NSE', 'EQ', 'EQ', 'buy', 20, '3282.0000', '75429740', '1300000001336880.00', '2022-05-23 09:22:11'),
(3, 'RELIANCE', 'INE002A01018', '2022-05-23', 'NSE', 'EQ', 'EQ', 'buy', 20, '2632.0000', '78786954', '1300000016090590.00', '2022-05-23 13:35:01'),
(4, 'RELIANCE', 'INE002A01018', '2022-05-23', 'NSE', 'EQ', 'EQ', 'sell', 13, '2625.0000', '79040119', '1300000016111780.00', '2022-05-23 13:58:40'),
(5, 'RELIANCE', 'INE002A01018', '2022-05-23', 'NSE', 'EQ', 'EQ', 'sell', 2, '2625.0000', '79040117', '1300000016111780.00', '2022-05-23 13:58:40'),
(6, 'RELIANCE', 'INE002A01018', '2022-05-23', 'NSE', 'EQ', 'EQ', 'sell', 5, '2625.0000', '79040118', '1300000016111780.00', '2022-05-23 13:58:40'),
(7, 'TCS', 'INE467B01029', '2022-05-24', 'NSE', 'EQ', 'EQ', 'sell', 19, '3282.0000', '76965622', '1300000008646710.00', '2022-05-24 11:01:23'),
(8, 'TCS', 'INE467B01029', '2022-05-24', 'NSE', 'EQ', 'EQ', 'sell', 1, '3282.0000', '76965642', '1300000008646710.00', '2022-05-24 11:01:23'),
(9, 'TCS', 'INE467B01029', '2022-05-24', 'NSE', 'EQ', 'EQ', 'buy', 20, '3292.0000', '77277379', '1300000008681500.00', '2022-05-24 11:33:48'),
(10, 'TATASTEEL', 'INE081A01012', '2022-05-24', 'NSE', 'EQ', 'EQ', 'sell', 20, '1015.2500', '77655054', '1300000012226710.00', '2022-05-24 12:10:24'),
(11, 'TATASTEEL', 'INE081A01012', '2022-05-24', 'NSE', 'EQ', 'EQ', 'buy', 20, '1007.2000', '80000265', '1300000022299820.00', '2022-05-24 15:18:07'),
(12, 'TATASTEEL', 'INE081A01012', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', 25, '995.0000', '77978217', '1300000013650710.00', '2022-05-25 12:43:44'),
(13, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', 1, '3187.0000', '78125911', '1300000014353100.00', '2022-05-25 12:56:39'),
(14, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', 2, '3187.0000', '78125931', '1300000014353100.00', '2022-05-25 12:56:39'),
(15, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', 10, '3187.0000', '78125944', '1300000014353100.00', '2022-05-25 12:56:39'),
(16, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', 7, '3187.0000', '78126312', '1300000014353100.00', '2022-05-25 12:56:40'),
(17, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', 2, '3187.0000', '78126267', '1300000014353100.00', '2022-05-25 12:56:40'),
(18, 'TATASTEEL', 'INE081A01012', '2022-05-25', 'NSE', 'EQ', 'EQ', 'buy', 25, '1003.1500', '78320664', '1300000013684580.00', '2022-05-25 13:15:06'),
(19, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'buy', 8, '3180.0000', '78931014', '1300000014395950.00', '2022-05-25 14:10:59'),
(20, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'buy', 14, '3180.0000', '78931015', '1300000014395950.00', '2022-05-25 14:10:59'),
(21, 'RELIANCE', 'INE002A01018', '2022-05-26', 'NSE', 'EQ', 'EQ', 'sell', 20, '2576.0000', '76603228', '1300000007221450.00', '2022-05-26 10:30:23'),
(22, 'RELIANCE', 'INE002A01018', '2022-05-26', 'NSE', 'EQ', 'EQ', 'buy', 20, '2556.0000', '77389322', '1300000007373900.00', '2022-05-26 11:23:19'),
(23, 'HDFC', 'INE001A01036', '2022-05-26', 'NSE', 'EQ', 'EQ', 'buy', 25, '2280.0000', '28952785', '1100000018095260.00', '2022-05-26 13:15:10'),
(24, 'HDFC', 'INE001A01036', '2022-05-26', 'NSE', 'EQ', 'EQ', 'sell', 25, '2296.0000', '29221684', '1100000018935560.00', '2022-05-26 13:31:49'),
(25, 'RELIANCE', 'INE002A01018', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', 20, '2570.0000', '76059867', '1300000004911580.00', '2022-05-27 10:05:06'),
(26, 'RELIANCE', 'INE002A01018', '2022-05-27', 'NSE', 'EQ', 'EQ', 'buy', 20, '2550.0000', '76615201', '1300000004985100.00', '2022-05-27 10:56:03'),
(27, 'TATASTEEL', 'INE081A01012', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', 10, '1045.0000', '76618468', '1300000006038300.00', '2022-05-27 10:56:14'),
(28, 'TATASTEEL', 'INE081A01012', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', 1, '1045.0000', '76618469', '1300000006038300.00', '2022-05-27 10:56:14'),
(29, 'TATASTEEL', 'INE081A01012', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', 1, '1045.0000', '76618470', '1300000006038300.00', '2022-05-27 10:56:14'),
(30, 'HDFC', 'INE001A01036', '2022-05-27', 'NSE', 'EQ', 'EQ', 'buy', 20, '2326.0000', '27725219', '1100000009130880.00', '2022-05-27 12:38:56'),
(31, 'HDFC', 'INE001A01036', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', 20, '2330.2500', '29571380', '1100000020094860.00', '2022-05-27 15:18:47'),
(32, 'TATASTEEL', 'INE081A01012', '2022-05-27', 'NSE', 'EQ', 'EQ', 'buy', 12, '1043.4500', '79093856', '1300000019966960.00', '2022-05-27 15:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `tradebook_real`
--

CREATE TABLE `tradebook_real` (
  `entity_id` int(10) UNSIGNED NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `isin` varchar(255) NOT NULL,
  `trade_date` date NOT NULL,
  `exchange` enum('NSE') NOT NULL DEFAULT 'NSE',
  `segment` enum('EQ') NOT NULL DEFAULT 'EQ',
  `series` enum('EQ') NOT NULL DEFAULT 'EQ',
  `trade_type` enum('buy','sell') NOT NULL DEFAULT 'buy',
  `quantity` decimal(32,0) DEFAULT NULL,
  `price` decimal(36,4) DEFAULT NULL,
  `trade_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `order_execution_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tradebook_real`
--

INSERT INTO `tradebook_real` (`entity_id`, `symbol`, `isin`, `trade_date`, `exchange`, `segment`, `series`, `trade_type`, `quantity`, `price`, `trade_id`, `order_id`, `order_execution_time`) VALUES
(1, 'TCS', 'INE467B01029', '2022-05-23', 'NSE', 'EQ', 'EQ', 'sell', '20', '3272.9500', '75381613', '1300000001225190.00', '2022-05-23 09:20:52'),
(2, 'TCS', 'INE467B01029', '2022-05-23', 'NSE', 'EQ', 'EQ', 'buy', '20', '3282.0000', '75429740', '1300000001336880.00', '2022-05-23 09:22:11'),
(3, 'RELIANCE', 'INE002A01018', '2022-05-23', 'NSE', 'EQ', 'EQ', 'buy', '20', '2632.0000', '78786954', '1300000016090590.00', '2022-05-23 13:35:01'),
(4, 'RELIANCE', 'INE002A01018', '2022-05-23', 'NSE', 'EQ', 'EQ', 'sell', '20', '2625.0000', '79040119', '1300000016111780.00', '2022-05-23 13:58:40'),
(7, 'TCS', 'INE467B01029', '2022-05-24', 'NSE', 'EQ', 'EQ', 'sell', '20', '3282.0000', '76965622', '1300000008646710.00', '2022-05-24 11:01:23'),
(9, 'TCS', 'INE467B01029', '2022-05-24', 'NSE', 'EQ', 'EQ', 'buy', '20', '3292.0000', '77277379', '1300000008681500.00', '2022-05-24 11:33:48'),
(10, 'TATASTEEL', 'INE081A01012', '2022-05-24', 'NSE', 'EQ', 'EQ', 'sell', '20', '1015.2500', '77655054', '1300000012226710.00', '2022-05-24 12:10:24'),
(11, 'TATASTEEL', 'INE081A01012', '2022-05-24', 'NSE', 'EQ', 'EQ', 'buy', '20', '1007.2000', '80000265', '1300000022299820.00', '2022-05-24 15:18:07'),
(12, 'TATASTEEL', 'INE081A01012', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', '25', '995.0000', '77978217', '1300000013650710.00', '2022-05-25 12:43:44'),
(13, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'sell', '22', '3187.0000', '78125911', '1300000014353100.00', '2022-05-25 12:56:39'),
(18, 'TATASTEEL', 'INE081A01012', '2022-05-25', 'NSE', 'EQ', 'EQ', 'buy', '25', '1003.1500', '78320664', '1300000013684580.00', '2022-05-25 13:15:06'),
(19, 'TCS', 'INE467B01029', '2022-05-25', 'NSE', 'EQ', 'EQ', 'buy', '22', '3180.0000', '78931014', '1300000014395950.00', '2022-05-25 14:10:59'),
(21, 'RELIANCE', 'INE002A01018', '2022-05-26', 'NSE', 'EQ', 'EQ', 'sell', '20', '2576.0000', '76603228', '1300000007221450.00', '2022-05-26 10:30:23'),
(22, 'RELIANCE', 'INE002A01018', '2022-05-26', 'NSE', 'EQ', 'EQ', 'buy', '20', '2556.0000', '77389322', '1300000007373900.00', '2022-05-26 11:23:19'),
(23, 'HDFC', 'INE001A01036', '2022-05-26', 'NSE', 'EQ', 'EQ', 'buy', '25', '2280.0000', '28952785', '1100000018095260.00', '2022-05-26 13:15:10'),
(24, 'HDFC', 'INE001A01036', '2022-05-26', 'NSE', 'EQ', 'EQ', 'sell', '25', '2296.0000', '29221684', '1100000018935560.00', '2022-05-26 13:31:49'),
(25, 'RELIANCE', 'INE002A01018', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', '20', '2570.0000', '76059867', '1300000004911580.00', '2022-05-27 10:05:06'),
(26, 'RELIANCE', 'INE002A01018', '2022-05-27', 'NSE', 'EQ', 'EQ', 'buy', '20', '2550.0000', '76615201', '1300000004985100.00', '2022-05-27 10:56:03'),
(27, 'TATASTEEL', 'INE081A01012', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', '12', '1045.0000', '76618468', '1300000006038300.00', '2022-05-27 10:56:14'),
(30, 'HDFC', 'INE001A01036', '2022-05-27', 'NSE', 'EQ', 'EQ', 'buy', '20', '2326.0000', '27725219', '1100000009130880.00', '2022-05-27 12:38:56'),
(31, 'HDFC', 'INE001A01036', '2022-05-27', 'NSE', 'EQ', 'EQ', 'sell', '20', '2330.2500', '29571380', '1100000020094860.00', '2022-05-27 15:18:47'),
(32, 'TATASTEEL', 'INE081A01012', '2022-05-27', 'NSE', 'EQ', 'EQ', 'buy', '12', '1043.4500', '79093856', '1300000019966960.00', '2022-05-27 15:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `trading_journal`
--

CREATE TABLE `trading_journal` (
  `trade_date` date NOT NULL,
  `order_execution_time` datetime NOT NULL DEFAULT current_timestamp(),
  `symbol` varchar(255) NOT NULL,
  `qty` decimal(36,4) DEFAULT NULL,
  `buy` decimal(36,4) DEFAULT NULL,
  `sell` decimal(36,4) DEFAULT NULL,
  `PNL` decimal(60,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tradebook`
--
ALTER TABLE `tradebook`
  ADD PRIMARY KEY (`entity_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
