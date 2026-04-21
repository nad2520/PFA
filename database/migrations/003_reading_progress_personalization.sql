-- Lexora personalization: reading progress persistence + leaderboard/list query indexes
-- Run once on the lexora database.

SET NAMES utf8mb4;

-- ── reading_progress (per-user, per-book resume state) ──────────────────────
CREATE TABLE IF NOT EXISTS `reading_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `last_page` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_reading_progress_user_book` (`user_id`, `book_id`),
  KEY `idx_reading_progress_user_updated` (`user_id`, `updated_at`),
  KEY `idx_reading_progress_book` (`book_id`),
  CONSTRAINT `fk_reading_progress_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reading_progress_book`
    FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── user_books indexes for status/library/list reads ─────────────────────────
SET @idx_user_books_status := (
  SELECT COUNT(1)
  FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'user_books'
    AND index_name = 'idx_user_books_user_status'
);
SET @q_user_books_status := IF(
  @idx_user_books_status = 0,
  'CREATE INDEX `idx_user_books_user_status` ON `user_books` (`user_id`, `status`)',
  'SELECT 1'
);
PREPARE stmt_user_books_status FROM @q_user_books_status;
EXECUTE stmt_user_books_status;
DEALLOCATE PREPARE stmt_user_books_status;

