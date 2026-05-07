-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 06:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_estate_portal_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddOrUpdateUser` (IN `p_userId` INT, IN `p_userName` VARCHAR(50), IN `p_contactInfo` VARCHAR(200), IN `p_passwordHash` VARCHAR(255), IN `p_userType` ENUM('agent','buyer','renter'))   BEGIN
    IF p_userId IS NULL THEN
        INSERT INTO Users (userName, contactInfo, passwordHash, userType)
        VALUES (p_userName, p_contactInfo, p_passwordHash, p_userType);
    ELSE
        UPDATE Users
        SET userName = p_userName,
            contactInfo = p_contactInfo,
            passwordHash = p_passwordHash,
            userType = p_userType
        WHERE userId = p_userId;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProcessTransaction` (IN `p_propertyId` INT, IN `p_userId` INT, IN `p_transactionType` ENUM('sale','rental'), IN `p_amount` DECIMAL(12,2))   BEGIN
    INSERT INTO Transactions (propertyId, userId, transactionType, transactionDate, amount)
    VALUES (p_propertyId, p_userId, p_transactionType, NOW(), p_amount);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favoriteId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `propertyId` int(11) NOT NULL,
  `savedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favoriteId`, `userId`, `propertyId`, `savedDate`) VALUES
(1, 2, 1, '2026-05-06 22:51:51'),
(2, 2, 2, '2026-05-06 22:51:51'),
(3, 3, 3, '2026-05-06 22:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `propertyId` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `inquiryDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiryId`, `userId`, `propertyId`, `message`, `inquiryDate`) VALUES
(1, 2, 1, 'I am interested in this apartment.', '2026-05-06 22:51:51'),
(2, 3, 3, 'Is this studio still available?', '2026-05-06 22:51:51'),
(3, 2, 2, 'Can I schedule a showing?', '2026-05-06 22:51:51'),
(4, 7, 4, 'I’m interested in this property.', '2026-05-06 23:53:54'),
(5, 7, 4, 'Can I schedule a viewing?', '2026-05-06 23:56:17');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `propertyId` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `propertyType` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(100) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `status` enum('available','sold','rented') NOT NULL DEFAULT 'available',
  `agentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`propertyId`, `title`, `propertyType`, `address`, `city`, `price`, `status`, `agentId`) VALUES
(1, 'Modern Apartment', 'Apartment', '123 Main St', 'Bronx', 250000.00, 'sold', 1),
(2, 'Family House', 'House', '45 Park Ave', 'Yonkers', 550000.00, 'sold', 1),
(3, 'Studio Rental', 'Studio', '77 River Rd', 'Manhattan', 2200.00, 'rented', 1),
(4, 'Luxury Apartment', 'Apartment', '123 Main St', 'New York', 500000.00, 'available', 6);

-- --------------------------------------------------------

--
-- Stand-in structure for view `propertylistingview`
-- (See below for the actual view)
--
CREATE TABLE `propertylistingview` (
`title` varchar(100)
,`propertyType` varchar(50)
,`city` varchar(100)
,`price` decimal(12,2)
,`status` enum('available','sold','rented')
,`agentName` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactionId` int(11) NOT NULL,
  `propertyId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `transactionType` enum('sale','rental') NOT NULL,
  `transactionDate` datetime NOT NULL,
  `amount` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionId`, `propertyId`, `userId`, `transactionType`, `transactionDate`, `amount`) VALUES
(1, 1, 2, 'sale', '2026-05-06 22:51:51', 250000.00),
(2, 3, 3, 'rental', '2026-05-06 22:51:51', 2200.00),
(3, 2, 2, 'sale', '2026-05-06 22:51:51', 550000.00);

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `AfterTransactionInsert` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    IF NEW.transactionType = 'sale' THEN
        UPDATE Properties SET status = 'sold'
        WHERE propertyId = NEW.propertyId;
    ELSEIF NEW.transactionType = 'rental' THEN
        UPDATE Properties SET status = 'rented'
        WHERE propertyId = NEW.propertyId;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `contactInfo` varchar(200) DEFAULT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `userType` enum('agent','buyer','renter') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `contactInfo`, `passwordHash`, `userType`) VALUES
(1, 'agent1', 'agent1@email.com', '$2y$10$examplehashagent1', 'agent'),
(2, 'buyer1', 'buyer1@email.com', '$2y$10$examplehashbuyer1', 'buyer'),
(3, 'renter1', 'renter1@email.com', '$2y$10$examplehashrenter1', 'renter'),
(6, 'testagent', 'test@email.com', '$2y$10$SJEZFfaVLvT/bJz31AssOubaRUaB5UAvs.W.xqRpZnjEj6pFxpKQi', 'agent'),
(7, 'mkonadu', 'mkonadu@gmail.com', '$2y$10$XKfwpmJx/6mrYakxwpvkAeHf3.J8VXDL7WuvQQ53HrBh/KEszTx8y', 'buyer');

-- --------------------------------------------------------

--
-- Structure for view `propertylistingview`
--
DROP TABLE IF EXISTS `propertylistingview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `propertylistingview`  AS SELECT `p`.`title` AS `title`, `p`.`propertyType` AS `propertyType`, `p`.`city` AS `city`, `p`.`price` AS `price`, `p`.`status` AS `status`, `u`.`userName` AS `agentName` FROM (`properties` `p` join `users` `u` on(`p`.`agentId` = `u`.`userId`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favoriteId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `propertyId` (`propertyId`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiryId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `propertyId` (`propertyId`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`propertyId`),
  ADD KEY `agentId` (`agentId`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionId`),
  ADD KEY `propertyId` (`propertyId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favoriteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `propertyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`propertyId`) REFERENCES `properties` (`propertyId`);

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `inquiries_ibfk_2` FOREIGN KEY (`propertyId`) REFERENCES `properties` (`propertyId`);

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`agentId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`propertyId`) REFERENCES `properties` (`propertyId`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
