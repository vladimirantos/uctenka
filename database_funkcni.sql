-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2016 at 07:23 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finance_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `idGroup` int(11) NOT NULL,
  `owner` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `type` enum('private','shared') COLLATE utf8_czech_ci NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Triggers `groups`
--
DELIMITER $$
CREATE TRIGGER `groupMember` AFTER INSERT ON `groups` FOR EACH ROW begin
    insert into group_members(idGroup, user, current) values(NEW.idGroup, NEW.owner, true);
  end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `groups_users`
--
CREATE TABLE `groups_users` (
`idGroup` int(11)
,`groupName` varchar(50)
,`type` enum('private','shared')
,`owner` varchar(50)
,`creationDate` timestamp
,`email` varchar(50)
,`userName` varchar(80)
,`registrationDate` timestamp
,`current` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `idGroupMember` int(11) NOT NULL,
  `idGroup` int(11) NOT NULL,
  `user` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `group_payments`
--
CREATE TABLE `group_payments` (
`idGroup` int(11)
,`groupName` varchar(50)
,`owner` varchar(50)
,`email` varchar(50)
,`userName` varchar(80)
,`description` text
,`price` int(11)
,`paymentsDate` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `idGroup` int(11) NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `idPayment` int(11) NOT NULL,
  `idGroup` int(11) NOT NULL,
  `user` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `price` int(11) NOT NULL,
  `paymentsDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `access` enum('admin','user') COLLATE utf8_czech_ci NOT NULL DEFAULT 'user',
  `registrationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `registerUser` AFTER INSERT ON `users` FOR EACH ROW begin
    insert into groups(owner, name, type) values(NEW.email, "Moje skupina", "private");
  end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `groups_users`
--
DROP TABLE IF EXISTS `groups_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `groups_users`  AS  select `g`.`idGroup` AS `idGroup`,`g`.`name` AS `groupName`,`g`.`type` AS `type`,`g`.`owner` AS `owner`,`g`.`creationDate` AS `creationDate`,`u`.`email` AS `email`,`u`.`name` AS `userName`,`u`.`registrationDate` AS `registrationDate`,`gm`.`current` AS `current` from ((`group_members` `gm` join `groups` `g` on((`g`.`idGroup` = `gm`.`idGroup`))) join `users` `u` on((`u`.`email` = `gm`.`user`))) ;

-- --------------------------------------------------------

--
-- Structure for view `group_payments`
--
DROP TABLE IF EXISTS `group_payments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `group_payments`  AS  select `g`.`idGroup` AS `idGroup`,`g`.`name` AS `groupName`,`g`.`owner` AS `owner`,`u`.`email` AS `email`,`u`.`name` AS `userName`,`p`.`description` AS `description`,`p`.`price` AS `price`,`p`.`paymentsDate` AS `paymentsDate` from ((`payments` `p` join `groups` `g` on((`g`.`idGroup` = `p`.`idGroup`))) join `users` `u` on((`u`.`email` = `p`.`user`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`idGroup`),
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`idGroupMember`),
  ADD KEY `idGroup` (`idGroup`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender` (`sender`),
  ADD KEY `idGroup` (`idGroup`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`idPayment`),
  ADD KEY `idGroup` (`idGroup`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `idGroup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `idGroupMember` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `idPayment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`idGroup`) REFERENCES `groups` (`idGroup`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `log_ibfk_2` FOREIGN KEY (`idGroup`) REFERENCES `groups` (`idGroup`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`idGroup`) REFERENCES `groups` (`idGroup`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
