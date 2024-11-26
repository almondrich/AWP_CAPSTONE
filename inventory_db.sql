-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2023 at 05:38 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cid` int(11) NOT NULL,
  `categoryDesc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cid`, `categoryDesc`) VALUES
(15, 'School Supplies'),
(21, 'School Equipment');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `eid` int(11) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `Contact` varchar(100) NOT NULL,
  `Address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`eid`, `Fullname`, `Contact`, `Address`) VALUES
(1, 'testing', 'sample', 'sample');

-- --------------------------------------------------------

--
-- Table structure for table `issuance`
--

CREATE TABLE `issuance` (
  `issID` int(11) NOT NULL,
  `transcode` varchar(100) NOT NULL,
  `iid` int(100) NOT NULL,
  `eid` int(100) NOT NULL,
  `qty` int(10) NOT NULL,
  `xDate` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issuance`
--

INSERT INTO `issuance` (`issID`, `transcode`, `iid`, `eid`, `qty`, `xDate`) VALUES
(5, 'IssueNo-99324', 1, 1, 12, '2023-11-16'),
(6, 'IssueNo-99324', 5, 1, 23, '2023-11-16'),
(7, 'IssueNo-98823', 1, 1, 10, '2023-11-15'),
(8, 'IssueNo-87043', 1, 1, 150, '2023-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `iid` int(11) NOT NULL,
  `itemDesc` varchar(100) NOT NULL,
  `cid` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `qty` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`iid`, `itemDesc`, `cid`, `uid`, `qty`) VALUES
(1, 'Mongol ', 15, 2, 60),
(2, 'sample', 15, 2, 196),
(5, 'TEST', 15, 4, 100),
(6, 'laptop', 19, 2, 100);

-- --------------------------------------------------------

--
-- Table structure for table `security`
--

CREATE TABLE `security` (
  `user` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `acc` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security`
--

INSERT INTO `security` (`user`, `pass`, `acc`, `fullname`) VALUES
('admin@gmail.com', 'admin', 'Administrator', 'Marissa G. Cabaruan'),
('user1@gmail.com', 'admin', 'User', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `stockin`
--

CREATE TABLE `stockin` (
  `sid` int(11) NOT NULL,
  `transcode` varchar(100) NOT NULL,
  `iid` int(100) NOT NULL,
  `supid` int(100) NOT NULL,
  `qty` int(100) NOT NULL,
  `xdate` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stockin`
--

INSERT INTO `stockin` (`sid`, `transcode`, `iid`, `supid`, `qty`, `xdate`) VALUES
(15, 'StockNo-29417', 1, 2, 19, '2023-11-16'),
(16, 'StockNo-34322', 1, 2, 99, '2023-11-19'),
(17, 'StockNo-34322', 2, 2, 96, '2023-11-19'),
(18, 'StockNo-42532', 1, 2, 100, '2023-11-20'),
(19, 'StockNo-35017', 1, 0, 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supid` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supid`, `fullname`, `contact`, `address`) VALUES
(2, 'James', '982738913', 'Nothing St. Somewhere CIty'),
(3, 'Luke', '', ''),
(5, 'John', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tempstockin`
--

CREATE TABLE `tempstockin` (
  `tempid` int(11) NOT NULL,
  `iid` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `qty` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tempstockin`
--

INSERT INTO `tempstockin` (`tempid`, `iid`, `uid`, `qty`) VALUES
(42, 1, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `uid` int(11) NOT NULL,
  `unitDesc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`uid`, `unitDesc`) VALUES
(2, 'pieces'),
(4, 'box/es');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vissuance`
-- (See below for the actual view)
--
CREATE TABLE `vissuance` (
`transcode` varchar(100)
,`itemDesc` varchar(100)
,`Fullname` varchar(100)
,`qty` int(10)
,`xDate` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vitems`
-- (See below for the actual view)
--
CREATE TABLE `vitems` (
`iid` int(11)
,`itemDesc` varchar(100)
,`categoryDesc` varchar(100)
,`unitDesc` varchar(100)
,`qty` int(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vstockin`
-- (See below for the actual view)
--
CREATE TABLE `vstockin` (
`transcode` varchar(100)
,`fullname` varchar(100)
,`itemDesc` varchar(100)
,`qty` int(100)
,`xdate` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vtempstockin`
-- (See below for the actual view)
--
CREATE TABLE `vtempstockin` (
`tempid` int(11)
,`itemDesc` varchar(100)
,`unitdesc` varchar(100)
,`qty` int(100)
);

-- --------------------------------------------------------

--
-- Structure for view `vissuance`
--
DROP TABLE IF EXISTS `vissuance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vissuance`  AS SELECT `issuance`.`transcode` AS `transcode`, `items`.`itemDesc` AS `itemDesc`, `employees`.`Fullname` AS `Fullname`, `issuance`.`qty` AS `qty`, `issuance`.`xDate` AS `xDate` FROM ((`issuance` join `employees` on(`issuance`.`eid` = `employees`.`eid`)) join `items` on(`issuance`.`iid` = `items`.`iid`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vitems`
--
DROP TABLE IF EXISTS `vitems`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vitems`  AS SELECT `items`.`iid` AS `iid`, `items`.`itemDesc` AS `itemDesc`, `category`.`categoryDesc` AS `categoryDesc`, `unit`.`unitDesc` AS `unitDesc`, `items`.`qty` AS `qty` FROM ((`items` join `category` on(`items`.`cid` = `category`.`cid`)) join `unit` on(`items`.`uid` = `unit`.`uid`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vstockin`
--
DROP TABLE IF EXISTS `vstockin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vstockin`  AS SELECT `stockin`.`transcode` AS `transcode`, `suppliers`.`fullname` AS `fullname`, `items`.`itemDesc` AS `itemDesc`, `stockin`.`qty` AS `qty`, `stockin`.`xdate` AS `xdate` FROM ((`stockin` join `suppliers` on(`stockin`.`supid` = `suppliers`.`supid`)) join `items` on(`stockin`.`iid` = `items`.`iid`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vtempstockin`
--
DROP TABLE IF EXISTS `vtempstockin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtempstockin`  AS SELECT `tempstockin`.`tempid` AS `tempid`, `items`.`itemDesc` AS `itemDesc`, `unit`.`unitDesc` AS `unitdesc`, `tempstockin`.`qty` AS `qty` FROM ((`tempstockin` join `items` on(`tempstockin`.`iid` = `items`.`iid`)) join `unit` on(`items`.`uid` = `unit`.`uid`)) ORDER BY `tempstockin`.`tempid` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `issuance`
--
ALTER TABLE `issuance`
  ADD PRIMARY KEY (`issID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`iid`);

--
-- Indexes for table `security`
--
ALTER TABLE `security`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `stockin`
--
ALTER TABLE `stockin`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supid`);

--
-- Indexes for table `tempstockin`
--
ALTER TABLE `tempstockin`
  ADD PRIMARY KEY (`tempid`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `issuance`
--
ALTER TABLE `issuance`
  MODIFY `issID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stockin`
--
ALTER TABLE `stockin`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tempstockin`
--
ALTER TABLE `tempstockin`
  MODIFY `tempid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
