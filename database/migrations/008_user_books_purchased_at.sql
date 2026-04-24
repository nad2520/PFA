-- Idempotent book purchase: record when coins were spent so concurrent / repeated
-- Start Reading requests cannot double-charge (see UserApiController::purchaseBook).
-- Safe to re-run on MariaDB 10.5+ / MySQL 8+.

SET NAMES utf8mb4;

SET @col_purchased := (
  SELECT COUNT(1) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user_books' AND COLUMN_NAME = 'purchased_at'
);
SET @q_add := IF(
  @col_purchased = 0,
  'ALTER TABLE `user_books` ADD COLUMN `purchased_at` datetime DEFAULT NULL AFTER `started_at`',
  'SELECT 1'
);
PREPARE stmt_purchased FROM @q_add;
EXECUTE stmt_purchased;
DEALLOCATE PREPARE stmt_purchased;

-- Existing unlocked rows count as already paid for (no retroactive charge).
UPDATE `user_books`
SET `purchased_at` = COALESCE(`started_at`, `completed_at`, NOW())
WHERE `status` IN ('reading', 'completed')
  AND (`purchased_at` IS NULL OR `purchased_at` = '0000-00-00 00:00:00');
