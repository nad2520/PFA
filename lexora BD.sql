-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 12 avr. 2026 à 21:22
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

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `cover_emoji` varchar(20) DEFAULT '?',
  `coin_cost` int(11) DEFAULT 100,
  `xp_reward` int(11) DEFAULT 150,
  `coin_reward` int(11) DEFAULT 40,
  `audience` varchar(50) DEFAULT 'All Age',
  `trending` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `cover` varchar(10) DEFAULT 'ðŸ“–',
  `coinCost` int(11) DEFAULT 100,
  `xpReward` int(11) DEFAULT 150,
  `coinReward` int(11) DEFAULT 40
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `cover_emoji`, `coin_cost`, `xp_reward`, `coin_reward`, `audience`, `trending`, `description`, `created_at`, `cover`, `coinCost`, `xpReward`, `coinReward`) VALUES
(1, 'The Shadow\'s Edge', 'Elena Blackwood', 'Fantasy', '????', 2300, 150, 300, 'All', 1, 'In a world where shadows hold ancient power, young mage Kael discovers he can bend darkness itself.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(2, 'Whispers in the Dark', 'Marcus Holloway', 'Horror', '????', 1800, 120, 250, 'All', 1, 'After inheriting a decaying Victorian manor, journalist Lena Cole begins hearing voices that shouldn\'t exist.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(3, 'The Vanishing Hour', 'Claire Ashford', 'Mystery', '????', 1500, 100, 200, 'All', 0, 'Every night at exactly 3:17 AM, someone in the coastal town of Mirren Bay disappears without a trace.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(4, 'Blood & Amber', 'Dominic Vance', 'Crime', '???', 2100, 140, 280, 'All', 1, 'When a priceless amber artifact surfaces in the criminal underworld of Prague, retired thief Marco Sorel is drawn back.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(5, 'Letters to Autumn', 'Sophia Moreau', 'Romance', '????', 900, 80, 150, 'All', 0, 'A collection of unsent letters leads bookshop owner Autumn Leclair to the doorstep of a reclusive poet.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(6, 'The Glass Curtain', 'Julian Cross', 'Drama', '????', 1200, 90, 180, 'All', 1, 'Behind the glittering facade of a prestigious theater company, director Maren Hale fights to stage one final production.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(7, 'Empire of Dust', 'Helena Wren', 'Historical Fiction', '???????', 2500, 160, 320, 'All', 1, 'In the crumbling twilight of the Ottoman Empire, a young cartographer is tasked with mapping lands.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(8, 'The Fae Accord', 'Rowan Ashby', 'Fantasy', '???', 1600, 110, 220, 'All', 0, 'When the ancient treaty between humans and fae shatters, half-fae diplomat Elara is the only one who can broker peace.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(9, 'Cellar Door', 'Isaac Thorne', 'Horror', '????', 2000, 130, 260, 'All', 1, 'Linguist David Harker always believed \'cellar door\' was the most beautiful phrase. Then he found the actual door.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(10, 'The Clockwork Witness', 'Ada Pemberton', 'Mystery', '??????', 1400, 95, 190, 'All', 0, 'In an alternate Victorian London powered by clockwork, a mechanical automaton is the sole witness to a murder.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(11, 'Scarlet Alibi', 'Nora Briggs', 'Crime', '????', 1700, 115, 230, 'All', 0, 'Defense attorney Cassandra Hale knows her client is guilty. But the real crime is far worse than anyone imagined.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(12, 'Moonlit Promises', 'Camille Duval', 'Romance', '????', 800, 70, 140, 'All', 1, 'Two rival florists in a small Proven??al village are forced to collaborate on a grand wedding.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(13, 'The Understudy', 'Felix Harlow', 'Drama', '????', 1100, 85, 170, 'All', 0, 'Understudy Nadia finally gets her chance when the leading actress vanishes on opening night.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(14, 'The Cartographer\'s Lie', 'Sebastian Cole', 'Historical Fiction', '???????', 2400, 155, 310, 'All', 1, 'A 16th-century mapmaker discovers that the world\'s most trusted atlas contains deliberate falsehoods.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(15, 'Thornfield Rising', 'Ivy Blackthorn', 'Fantasy', '????', 1900, 125, 240, 'All', 0, 'The ancient fortress of Thornfield awakens for a young herbalist.', '2026-04-09 20:12:38', 'ðŸ“–', 100, 150, 40),
(17, 'aaaaaaaaaa', 'aaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaa', '?', 100, 150, 40, 'All', 0, NULL, '2026-04-12 14:59:10', 'ðŸ“–', 100, 150, 40);

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
  `comments` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `book_id`, `title`, `content`, `tag`, `upvotes`, `comments_count`, `status`, `created_at`, `author`, `book`, `comments`) VALUES
(6, 3, 1, 'The magic system is genius!', 'I love how shadow-bending works. It feels so grounded.', 'discussion', 142, 28, 'Clean', '2026-04-09 20:45:45', 'Anonymous', 'Unknown Book', 0),
(9, 3, 4, 'SPOILER ALERT: Who dies in ch 12', 'I can\'t believe she actually killed off my favorite character.', 'spoiler', 245, 110, 'Flagged by Lumo', '2026-04-09 20:45:45', 'Anonymous', 'Unknown Book', 0),
(12, 3, 7, 'Buy Cheap Coins at gold-lexora.tk', 'Visit our site for 90% off all coin purchases today!', 'discussion', 0, 1, 'Flagged by Lumo', '2026-04-09 20:45:45', 'Anonymous', 'Unknown Book', 0);

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
(7, 'aroua1', 'aroua1@gmail.com', '27ebeb8f55f7ae4f2a8c073a03df4385', 'User +18', 0, 1, '2026-04-12 12:39:51', '2005-11-11'),
(9, 'nad', 'nadinemahjoub464@gmail.com', '42b2ed1d5993b2c75bbd23ac84d4db7e', 'User -18', 0, 1, '2026-04-12 18:15:35', '2010-11-11');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
