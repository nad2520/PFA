-- Migration 009: Book purchase coin deduction support
-- Ensures economy_logs.coins_spent column exists (may already be present from lexora.sql base schema).
-- Adds index on user_books.purchased_at for fast idempotency checks.
-- Safe to re-run on MariaDB 10.5+ / MySQL 8+.

SET NAMES utf8mb4;

-- ── Ensure economy_logs.coins_spent exists ──────────────────────────────────
SET @col_spent := (
  SELECT COUNT(1) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'economy_logs'
    AND COLUMN_NAME  = 'coins_spent'
);
SET @q_spent := IF(
  @col_spent = 0,
  'ALTER TABLE `economy_logs` ADD COLUMN `coins_spent` int(11) NOT NULL DEFAULT 0 AFTER `coins_earned`',
  'SELECT 1'
);
PREPARE stmt_spent FROM @q_spent;
EXECUTE stmt_spent;
DEALLOCATE PREPARE stmt_spent;

-- ── Index on user_books.purchased_at for fast "already purchased?" checks ───
SET @idx_pa := (
  SELECT COUNT(1) FROM information_schema.statistics
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'user_books'
    AND INDEX_NAME   = 'idx_user_books_purchased_at'
);
SET @q_pa := IF(
  @idx_pa = 0,
  'CREATE INDEX `idx_user_books_purchased_at` ON `user_books` (`user_id`, `book_id`, `purchased_at`)',
  'SELECT 1'
);
PREPARE stmt_pa FROM @q_pa;
EXECUTE stmt_pa;
DEALLOCATE PREPARE stmt_pa;

-- ── Guard: ensure existing unlocked rows have purchased_at set (from 008) ───
UPDATE `user_books`
SET `purchased_at` = COALESCE(`started_at`, `completed_at`, NOW())
WHERE `status` IN ('reading', 'completed')
  AND (`purchased_at` IS NULL OR `purchased_at` = '0000-00-00 00:00:00');
