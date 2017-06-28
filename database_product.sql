-- phpMyAdmin SQL Dump
-- version 4.4.4
-- http://www.phpmyadmin.net
--
-- Počítač: innodb.endora.cz:3306
-- Vytvořeno: Stř 28. čen 2017, 21:59
-- Verze serveru: 5.6.28-76.1
-- Verze PHP: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `uctenkadb`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `idGroup` int(11) NOT NULL,
  `owner` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `type` enum('private','shared') COLLATE utf8_czech_ci NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Zástupná struktura pro pohled `groups_users`
--
CREATE TABLE IF NOT EXISTS `groups_users` (
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
-- Struktura tabulky `group_members`
--

CREATE TABLE IF NOT EXISTS `group_members` (
  `idGroupMember` int(11) NOT NULL,
  `idGroup` int(11) NOT NULL,
  `user` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Zástupná struktura pro pohled `group_payments`
--
CREATE TABLE IF NOT EXISTS `group_payments` (
`idPayment` int(11)
,`idGroup` int(11)
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
-- Struktura tabulky `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `idGroup` int(11) NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `login_log`
--

CREATE TABLE IF NOT EXISTS `login_log` (
  `id` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `ipAddress` varchar(12) COLLATE utf8_czech_ci NOT NULL,
  `loginDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `idPayment` int(11) NOT NULL,
  `idGroup` int(11) NOT NULL,
  `user` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `price` int(11) NOT NULL,
  `paymentsDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `access` enum('admin','user') COLLATE utf8_czech_ci NOT NULL DEFAULT 'user',
  `registrationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura pro pohled `groups_users`
--
DROP TABLE IF EXISTS `groups_users`;
-- právě se používá(#1142 - SHOW VIEW command denied to user 'vladimirantos'@'88.86.120.129' for table 'groups_users')

-- --------------------------------------------------------

--
-- Struktura pro pohled `group_payments`
--
DROP TABLE IF EXISTS `group_payments`;
-- právě se používá(#1142 - SHOW VIEW command denied to user 'vladimirantos'@'88.86.120.129' for table 'group_payments')

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`idGroup`),
  ADD KEY `owner` (`owner`);

--
-- Klíče pro tabulku `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`idGroupMember`),
  ADD KEY `idGroup` (`idGroup`),
  ADD KEY `user` (`user`);

--
-- Klíče pro tabulku `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender` (`sender`),
  ADD KEY `idGroup` (`idGroup`);

--
-- Klíče pro tabulku `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`idPayment`),
  ADD KEY `idGroup` (`idGroup`),
  ADD KEY `user` (`user`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `groups`
--
ALTER TABLE `groups`
  MODIFY `idGroup` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `group_members`
--
ALTER TABLE `group_members`
  MODIFY `idGroupMember` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `payments`
--
ALTER TABLE `payments`
  MODIFY `idPayment` int(11) NOT NULL AUTO_INCREMENT;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`idGroup`) REFERENCES `groups` (`idGroup`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `log_ibfk_2` FOREIGN KEY (`idGroup`) REFERENCES `groups` (`idGroup`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`idGroup`) REFERENCES `groups` (`idGroup`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
