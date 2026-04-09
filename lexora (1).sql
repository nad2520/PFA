-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 avr. 2026 à 22:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lexora`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

CREATE TABLE `account` (
  `id` char(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` text NOT NULL,
  `status` enum('ACTIVE','SUSPENDED') DEFAULT 'ACTIVE',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastLoginAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `author`
--

CREATE TABLE `author` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `deathDate` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `photoUrl` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

CREATE TABLE `book` (
  `id` char(36) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `coverUrl` text DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `pageCount` int(11) DEFAULT NULL,
  `publishDate` date DEFAULT NULL,
  `averageRating` float DEFAULT 0,
  `coinCost` int(11) DEFAULT 0,
  `isAdulte` tinyint(1) DEFAULT 0,
  `isTrending` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bookauthor`
--

CREATE TABLE `bookauthor` (
  `bookId` char(36) NOT NULL,
  `authorId` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bookgenre`
--

CREATE TABLE `bookgenre` (
  `bookId` char(36) NOT NULL,
  `genreId` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `booktag`
--

CREATE TABLE `booktag` (
  `bookId` char(36) NOT NULL,
  `tagId` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `coinledger`
--

CREATE TABLE `coinledger` (
  `id` char(36) NOT NULL,
  `profileId` char(36) DEFAULT NULL,
  `delta` int(11) DEFAULT NULL,
  `type` enum('EARN','SPEND','PENALTY','PURCHASE') DEFAULT NULL,
  `referenceId` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` char(36) NOT NULL,
  `profileId` char(36) DEFAULT NULL,
  `postId` char(36) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `upvoteCount` int(11) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `id` char(36) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `mapMetadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`mapMetadata`)),
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` char(36) NOT NULL,
  `profileId` char(36) DEFAULT NULL,
  `bookId` char(36) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `type` enum('DISCUSSION','REVIEW','THEORY','SPOILER') DEFAULT NULL,
  `isModerated` tinyint(1) DEFAULT 0,
  `upvoteCount` int(11) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posttag`
--

CREATE TABLE `posttag` (
  `postId` char(36) NOT NULL,
  `tagId` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

CREATE TABLE `profile` (
  `id` char(36) NOT NULL,
  `accountId` char(36) NOT NULL,
  `username` varchar(100) NOT NULL,
  `avatarUrl` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `role` enum('USER','MODERATOR','ADMIN') DEFAULT 'USER',
  `level` int(11) DEFAULT 1,
  `currentXp` int(11) DEFAULT 0,
  `currentCoins` int(11) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quest`
--

CREATE TABLE `quest` (
  `id` char(36) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `criteria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`criteria`)),
  `xpReward` int(11) DEFAULT NULL,
  `coinReward` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `readingstreak`
--

CREATE TABLE `readingstreak` (
  `id` char(36) NOT NULL,
  `profileId` char(36) DEFAULT NULL,
  `lastReadAt` timestamp NULL DEFAULT NULL,
  `currentStreak` int(11) DEFAULT 0,
  `penaltyPending` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` char(36) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `colorCode` varchar(10) DEFAULT NULL,
  `category` enum('POST','BOOK','BOTH') DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `userbook`
--

CREATE TABLE `userbook` (
  `profileId` char(36) NOT NULL,
  `bookId` char(36) NOT NULL,
  `status` enum('WISHLIST','READING','FINISHED') DEFAULT NULL,
  `progressPercent` float DEFAULT 0,
  `isPurchased` tinyint(1) DEFAULT 0,
  `purchasedAt` timestamp NULL DEFAULT NULL,
  `addedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `userquest`
--

CREATE TABLE `userquest` (
  `profileId` char(36) NOT NULL,
  `questId` char(36) NOT NULL,
  `status` enum('IN_PROGRESS','COMPLETED') DEFAULT NULL,
  `startedAt` timestamp NULL DEFAULT NULL,
  `completedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `profileId` char(36) NOT NULL,
  `targetId` char(36) NOT NULL,
  `targetType` enum('POST','COMMENT') NOT NULL,
  `voteType` enum('UP','DOWN') DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `xpledger`
--

CREATE TABLE `xpledger` (
  `id` char(36) NOT NULL,
  `profileId` char(36) DEFAULT NULL,
  `delta` int(11) DEFAULT NULL,
  `source` enum('READING','QUEST','BONUS') DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bookauthor`
--
ALTER TABLE `bookauthor`
  ADD PRIMARY KEY (`bookId`,`authorId`),
  ADD KEY `authorId` (`authorId`);

--
-- Index pour la table `bookgenre`
--
ALTER TABLE `bookgenre`
  ADD PRIMARY KEY (`bookId`,`genreId`),
  ADD KEY `genreId` (`genreId`);

--
-- Index pour la table `booktag`
--
ALTER TABLE `booktag`
  ADD PRIMARY KEY (`bookId`,`tagId`),
  ADD KEY `tagId` (`tagId`);

--
-- Index pour la table `coinledger`
--
ALTER TABLE `coinledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profileId` (`profileId`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profileId` (`profileId`),
  ADD KEY `postId` (`postId`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profileId` (`profileId`),
  ADD KEY `bookId` (`bookId`);

--
-- Index pour la table `posttag`
--
ALTER TABLE `posttag`
  ADD PRIMARY KEY (`postId`,`tagId`),
  ADD KEY `tagId` (`tagId`);

--
-- Index pour la table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accountId` (`accountId`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `readingstreak`
--
ALTER TABLE `readingstreak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profileId` (`profileId`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `userbook`
--
ALTER TABLE `userbook`
  ADD PRIMARY KEY (`profileId`,`bookId`),
  ADD KEY `bookId` (`bookId`);

--
-- Index pour la table `userquest`
--
ALTER TABLE `userquest`
  ADD PRIMARY KEY (`profileId`,`questId`),
  ADD KEY `questId` (`questId`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`profileId`,`targetId`,`targetType`);

--
-- Index pour la table `xpledger`
--
ALTER TABLE `xpledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profileId` (`profileId`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bookauthor`
--
ALTER TABLE `bookauthor`
  ADD CONSTRAINT `bookauthor_ibfk_1` FOREIGN KEY (`bookId`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookauthor_ibfk_2` FOREIGN KEY (`authorId`) REFERENCES `author` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bookgenre`
--
ALTER TABLE `bookgenre`
  ADD CONSTRAINT `bookgenre_ibfk_1` FOREIGN KEY (`bookId`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookgenre_ibfk_2` FOREIGN KEY (`genreId`) REFERENCES `genre` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `booktag`
--
ALTER TABLE `booktag`
  ADD CONSTRAINT `booktag_ibfk_1` FOREIGN KEY (`bookId`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booktag_ibfk_2` FOREIGN KEY (`tagId`) REFERENCES `tag` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `coinledger`
--
ALTER TABLE `coinledger`
  ADD CONSTRAINT `coinledger_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`postId`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`bookId`) REFERENCES `book` (`id`);

--
-- Contraintes pour la table `posttag`
--
ALTER TABLE `posttag`
  ADD CONSTRAINT `posttag_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posttag_ibfk_2` FOREIGN KEY (`tagId`) REFERENCES `tag` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `readingstreak`
--
ALTER TABLE `readingstreak`
  ADD CONSTRAINT `readingstreak_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `userbook`
--
ALTER TABLE `userbook`
  ADD CONSTRAINT `userbook_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `userbook_ibfk_2` FOREIGN KEY (`bookId`) REFERENCES `book` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `userquest`
--
ALTER TABLE `userquest`
  ADD CONSTRAINT `userquest_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `userquest_ibfk_2` FOREIGN KEY (`questId`) REFERENCES `quest` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `xpledger`
--
ALTER TABLE `xpledger`
  ADD CONSTRAINT `xpledger_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `profile` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
