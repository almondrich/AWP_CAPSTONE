-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 08:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `category_id` int(11) NOT NULL,
  `category_desc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_desc`) VALUES
(1, 'School Supplies'),
(2, 'Electronics'),
(3, 'TOILET'),
(4, 'BOWL'),
(5, 'CR'),
(6, 'TOILETSS');

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
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_desc` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_desc`, `category_id`, `unit_id`, `qty`) VALUES
(1, 'Mongol Pencil', 'Standard pencil for writing', 1, 1, 100),
(2, 'Notebook', 'A4 size notebook', 1, 2, 50),
(3, 'Laptop', 'Standard office laptop', 2, 1, 20),
(4, 'EPSOM PRINTER', 'black ballpen', 2, 2, 9),
(5, 'EPSOM PRINTER', 'JYTYU', 1, 1, 89),
(6, 'hbw', 'JYTYU', 2, 1, 89);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_no` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `request_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_no`, `user_id`, `item_id`, `quantity`, `status`, `request_date`) VALUES
(1, 1, 1, 10, 0, '2024-11-01'),
(2, 2, 2, 5, 1, '2024-11-05');

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
(19, 'StockNo-35017', 1, 0, 10, ''),
(20, 'StockNo-59152', 1, 0, 39, ''),
(21, 'StockNo-59152', 2, 0, 50, ''),
(22, 'StockNo-59152', 5, 0, 90, '');

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
(45, 2, 2, 30),
(46, 9, 4, 56);

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
(4, 'box/es'),
(6, 'RIM');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_desc`) VALUES
(1, 'pieces'),
(2, 'box/es');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'user1', 'password123', 'user1@example.com', '2024-11-08 06:36:09'),
(2, 'admin', 'adminpassword', 'admin@example.com', '2024-11-08 06:36:09');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vissuance`
-- (See below for the actual view)
--
CREATE TABLE `vissuance` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vitems`
-- (See below for the actual view)
--
CREATE TABLE `vitems` (
);

-- --------------------------------------------------------

--
-- Table structure for table `vrequest`
--

CREATE TABLE `vrequest` (
  `request_no` int(11) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `unit_desc` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `request_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vstockin`
-- (See below for the actual view)
--
CREATE TABLE `vstockin` (
);

-- --------------------------------------------------------

--
-- Table structure for table `vtemprequest`
--

CREATE TABLE `vtemprequest` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vtempstockin`
-- (See below for the actual view)
--
CREATE TABLE `vtempstockin` (
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
  ADD PRIMARY KEY (`category_id`);

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
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_no`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

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
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vrequest`
--
ALTER TABLE `vrequest`
  ADD PRIMARY KEY (`request_no`);

--
-- Indexes for table `vtemprequest`
--
ALTER TABLE `vtemprequest`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stockin`
--
ALTER TABLE `stockin`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tempstockin`
--
ALTER TABLE `tempstockin`
  MODIFY `tempid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vtemprequest`
--
ALTER TABLE `vtemprequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
