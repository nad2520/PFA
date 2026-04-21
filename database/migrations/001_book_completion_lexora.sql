-- Lexora: books, user_books, reading_sessions, user XP/streak, economy_logs columns
-- Run against your `lexora` database (phpMyAdmin or mysql CLI). Safe to re-run on MariaDB 10.5+ where supported.

SET NAMES utf8mb4;

-- ── books ───────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL DEFAULT '',
  `genre` varchar(100) NOT NULL DEFAULT '',
  `cover` varchar(32) NOT NULL DEFAULT '📖',
  `coinCost` int(11) NOT NULL DEFAULT 0,
  `xpReward` int(11) NOT NULL DEFAULT 0,
  `coinReward` int(11) NOT NULL DEFAULT 0,
  `audience` varchar(50) NOT NULL DEFAULT 'All',
  `trending` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── user_books ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `user_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'plan_to_read',
  `progress_page` int(11) NOT NULL DEFAULT 0,
  `rating` tinyint(4) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_book` (`user_id`,`book_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `user_books_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_books_book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── reading_sessions (daily aggregate per user per book) ───────────────────
CREATE TABLE IF NOT EXISTS `reading_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `pages_read` int(11) NOT NULL DEFAULT 0,
  `minutes_read` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_book_day` (`user_id`,`book_id`,`session_date`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reading_sessions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reading_sessions_book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── users: XP / streak / last read (ignore "Duplicate column" if re-run) ───
ALTER TABLE `users` ADD COLUMN `xp` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `users` ADD COLUMN `streak_days` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `users` ADD COLUMN `last_read_at` datetime DEFAULT NULL;

-- ── economy_logs: per-user events ──────────────────────────────────────────
ALTER TABLE `economy_logs` ADD COLUMN `user_id` int(11) DEFAULT NULL AFTER `id`;
ALTER TABLE `economy_logs` ADD COLUMN `event_type` varchar(64) DEFAULT NULL;

-- Remove UNIQUE(log_date) so multiple rows per calendar day are allowed
SET @idx := (
  SELECT COUNT(1) FROM information_schema.statistics
  WHERE table_schema = DATABASE() AND table_name = 'economy_logs' AND index_name = 'log_date' AND non_unique = 0
);
SET @q := IF(@idx > 0, 'ALTER TABLE `economy_logs` DROP INDEX `log_date`', 'SELECT 1');
PREPARE stmt1 FROM @q;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;

-- Helpful non-unique index for reporting
SET @idx2 := (
  SELECT COUNT(1) FROM information_schema.statistics
  WHERE table_schema = DATABASE() AND table_name = 'economy_logs' AND index_name = 'idx_economy_user_date'
);
SET @q2 := IF(@idx2 = 0, 'CREATE INDEX `idx_economy_user_date` ON `economy_logs` (`user_id`, `log_date`)', 'SELECT 1');
PREPARE stmt2 FROM @q2;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;
