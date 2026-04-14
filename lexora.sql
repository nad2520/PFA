-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 14 avr. 2026 à 20:41
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

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
-- Structure de la table `books`
--
-- Erreur de lecture de structure pour la table lexora.books : #1932 - Table 'lexora.books' doesn't exist in engine
-- Erreur de lecture des données pour la table lexora.books : #1064 - Erreur de syntaxe près de 'FROM `lexora`.`books`' à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `economy_logs`
--

CREATE TABLE `economy_logs` (
  `id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `coins_earned` int(11) DEFAULT 0,
  `coins_spent` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `flags`
--

CREATE TABLE `flags` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `severity` varchar(50) DEFAULT 'Medium',
  `suggested_action` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `flags`
--

INSERT INTO `flags` (`id`, `post_id`, `reporter_id`, `type`, `severity`, `suggested_action`, `status`, `created_at`) VALUES
(3, 9, NULL, 'Spoiler', 'High', 'Mark as Spoiler', 'Pending', '2026-04-09 20:45:45'),
(4, 12, NULL, 'Spam', 'High', 'Delete Post', 'Pending', '2026-04-09 20:45:45'),
(9, 9, NULL, 'Spam', 'Low', 'Hide Post', 'Pending', '2026-04-09 20:45:45'),
(10, 12, NULL, 'Offensive', 'High', 'Ban User', 'Pending', '2026-04-09 20:45:45');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `tag` varchar(50) DEFAULT 'discussion',
  `upvotes` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT 'Clean',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `author` varchar(255) NOT NULL DEFAULT 'Anonymous',
  `book` varchar(255) NOT NULL DEFAULT 'Unknown Book',
  `comments` int(11) DEFAULT 0,
  `bookId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `book_id`, `title`, `content`, `tag`, `upvotes`, `comments_count`, `status`, `created_at`, `author`, `book`, `comments`, `bookId`) VALUES
(6, 3, 1, 'The magic system is genius!', 'I love how shadow-bending works. It feels so grounded.', 'discussion', 142, 28, 'Clean', '2026-04-09 20:45:45', 'Anonymous', 'Unknown Book', 0, NULL),
(9, 3, 4, 'SPOILER ALERT: Who dies in ch 12', 'I can\'t believe she actually killed off my favorite character.', 'spoiler', 245, 110, 'Flagged by Lumo', '2026-04-09 20:45:45', 'Anonymous', 'Unknown Book', 0, NULL),
(12, 3, 7, 'Buy Cheap Coins at gold-lexora.tk', 'Visit our site for 90% off all coin purchases today!', 'discussion', 0, 1, 'Flagged by Lumo', '2026-04-09 20:45:45', 'Anonymous', 'Unknown Book', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `coins` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `birthdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password`, `role`, `coins`, `level`, `created_at`, `birthdate`) VALUES
(3, 'Admin Lexora', 'lexora25@gmail.com', 'e63b1cb938d0b156e3eb160828083e03', 'admin', 0, 1, '2026-04-09 19:49:56', NULL),
(5, 'mlk', 'mlk1@gmail.com', '11ad50c382f55f4f8708bcd00d2cc03e', 'user', 0, 1, '2026-04-09 20:54:40', NULL),
(6, 'mariem', 'mariem1@gmail.com', '1cb62d95ebd4d60df7681859aa7968de', 'user', 0, 1, '2026-04-09 21:00:02', NULL),
(7, 'aroua1', 'aroua1@gmail.com', '27ebeb8f55f7ae4f2a8c073a03df4385', 'User +18', 0, 1, '2026-04-12 12:39:51', '2005-11-11');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `economy_logs`
--
ALTER TABLE `economy_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `log_date` (`log_date`);

--
-- Index pour la table `flags`
--
ALTER TABLE `flags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `reporter_id` (`reporter_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `economy_logs`
--
ALTER TABLE `economy_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `flags`
--
ALTER TABLE `flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `flags`
--
ALTER TABLE `flags`
  ADD CONSTRAINT `flags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `flags_ibfk_2` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
