-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 06:57 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_desc`) VALUES
(8, 'OFFICE SUPPLIES');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `eid` int(11) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `Contact` varchar(100) NOT NULL,
  `Address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_desc`, `category_id`, `unit_id`, `qty`) VALUES
(7, 'Bond Paper-Legal Size', 'Long Bond Paper', 8, 2, 49),
(12, 'Bond Paper-Letter Size', 'Short Bond Paper', 8, 1, 89),
(13, 'Standard Staples', 'Staple Wires', 8, 2, 79),
(14, 'Log Book', 'Record Book', 8, 1, 69),
(15, 'Scissor', 'Scissors', 8, 1, 59),
(16, 'Flex Stick', 'Flex Stick Ballpen', 8, 1, 39),
(17, 'White Board Marker', 'HBW Marker', 8, 1, 29),
(18, 'Pilot Super Marker Color', 'Super Marker', 8, 1, 29),
(19, 'Hi-Tecpoint', 'Hi-Techpiont Ballpen (Black)', 8, 1, 19),
(20, 'Short Folder', 'White Short Folder', 8, 1, 99),
(21, 'Packing Tape', 'Masking Tape', 8, 1, 109),
(22, 'Scotch Tape', 'Scotch Tape', 8, 1, 119),
(23, 'Puncher', 'HBW Puncher', 8, 2, 129),
(24, 'Calculator', 'Office Calculator', 8, 1, 139),
(25, 'Expanded Green Long Folder', 'Colored Long Folder', 8, 1, 149),
(26, 'Epson Ink 003', 'Original Epson Ink', 8, 1, 159),
(27, 'Fastener', 'Plastic Paper Fastener', 8, 2, 169),
(28, 'Plastic Cover', 'Plastic Cover PVC', 8, 4, 179),
(29, 'Storage Box', 'Storage Box With Handle', 8, 1, 189),
(30, 'PVC Cover Clear', 'PVC  Cover', 8, 3, 199),
(31, 'Transparent Binding Cover (Green)', 'PVC Binding Cover Colored', 8, 3, 209),
(32, 'Chalk', 'Dustless Chalk', 8, 2, 219);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `requisition_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `read_status` enum('Unread','Read') DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `requisition_id`, `message`, `read_status`, `created_at`) VALUES
(25, 3, 101, 'Your requisition #101 has been updated to \'Not Ready: Stocks Unavailable\'.', 'Unread', '2024-12-03 03:46:47'),
(26, 3, 101, 'Your requisition #101 has been updated to \'Released\'.', 'Unread', '2024-12-03 03:58:09'),
(27, 3, 102, 'Your requisition #102 has been updated to \'Ready for Release\'.', 'Unread', '2024-12-03 05:20:53');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_no`, `user_id`, `item_id`, `quantity`, `status`, `request_date`) VALUES
(1, 1, 1, 10, 0, '2024-11-01'),
(2, 2, 2, 5, 1, '2024-11-05');

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `requisition_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `justification` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Partially Released','Released') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `department_dean_status` enum('Pending','Approved','Disapproved') DEFAULT 'Pending',
  `department_dean_remarks` text DEFAULT NULL,
  `college_dean_status` enum('Pending','Approved','Disapproved') DEFAULT 'Pending',
  `college_dean_remarks` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `released_quantity` int(11) DEFAULT 0,
  `remaining_quantity` int(11) GENERATED ALWAYS AS (`quantity` - `released_quantity`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requisitions`
--

INSERT INTO `requisitions` (`requisition_id`, `user_id`, `item_id`, `unit_id`, `quantity`, `justification`, `status`, `request_date`, `department_dean_status`, `department_dean_remarks`, `college_dean_status`, `college_dean_remarks`, `remarks`, `released_quantity`) VALUES
(101, 3, 32, 2, 5, 'poiuytrewq', 'Released', '2024-12-03 03:41:24', 'Pending', NULL, 'Pending', NULL, NULL, 5),
(102, 3, 12, 1, 20, 'JHJG', 'Released', '2024-12-03 05:20:26', 'Pending', NULL, 'Pending', NULL, NULL, 20);

-- --------------------------------------------------------

--
-- Table structure for table `requisition_requests`
--

CREATE TABLE `requisition_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `justification` text NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `r_users`
--

CREATE TABLE `r_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `security`
--

CREATE TABLE `security` (
  `user` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `acc` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`uid`, `unitDesc`) VALUES
(2, 'pieces'),
(4, 'BOX/ES'),
(6, 'Reams'),
(0, 'BUNDLE/S');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_desc`) VALUES
(1, 'Pieces'),
(2, 'Box/es'),
(3, 'Ream/s'),
(4, 'Roll/s'),
(5, 'Pack/s');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `department`, `position`, `created_at`) VALUES
(1, 'RICHMOND ROSETE', 'richmondrosete19@gmail.com', '$2y$10$/Js7BYfVdFMN6C6UuNuxY.xp5fkFy2xr0kc.eu8CFcjvLOWSNVMrC', 'cics', 'pop', '2024-11-26 13:00:18'),
(3, 'ALICIA BOO', 'jm@gmail.com', '$2y$10$m9HaltMgwt.6t6bnDckhCelqlrwb4UINNfzGLmAyFZosdV9Qn0Cw2', 'CRIM', 'pop', '2024-11-26 14:07:42');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'user1', 'password123', 'user1@example.com', '2024-11-08 06:36:09'),
(2, 'admin', 'adminpassword', 'admin@example.com', '2024-11-08 06:36:09');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `vtemprequest`
--

CREATE TABLE `vtemprequest` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  ADD UNIQUE KEY `item_name` (`item_name`,`category_id`,`unit_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `requisition_id` (`requisition_id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`requisition_id`);

--
-- Indexes for table `requisition_requests`
--
ALTER TABLE `requisition_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_fk` (`user_id`);

--
-- Indexes for table `r_users`
--
ALTER TABLE `r_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `requisition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `requisition_requests`
--
ALTER TABLE `requisition_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `r_users`
--
ALTER TABLE `r_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`requisition_id`) ON DELETE CASCADE;

--
-- Constraints for table `requisition_requests`
--
ALTER TABLE `requisition_requests`
  ADD CONSTRAINT `requisition_requests_user_fk` FOREIGN KEY (`user_id`) REFERENCES `r_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
