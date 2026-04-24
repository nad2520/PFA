-- Make `economy_logs` compatible with POST /api/user/book/purchase audit inserts.
-- Idempotent: safe to re-run. Fixes common legacy `lexora.sql` schema (no user_id, UNIQUE log_date).

SET NAMES utf8mb4;

-- user_id
SET @col_uid := (
  SELECT COUNT(1) FROM information_schema.COLUMNS
  WHERE table_schema = DATABASE() AND table_name = 'economy_logs' AND column_name = 'user_id'
);
SET @q_uid := IF(
  @col_uid = 0,
  'ALTER TABLE `economy_logs` ADD COLUMN `user_id` int(11) DEFAULT NULL AFTER `id`',
  'SELECT 1'
);
PREPARE stmt_uid FROM @q_uid;
EXECUTE stmt_uid;
DEALLOCATE PREPARE stmt_uid;

-- event_type
SET @col_et := (
  SELECT COUNT(1) FROM information_schema.COLUMNS
  WHERE table_schema = DATABASE() AND table_name = 'economy_logs' AND column_name = 'event_type'
);
SET @q_et := IF(
  @col_et = 0,
  'ALTER TABLE `economy_logs` ADD COLUMN `event_type` varchar(64) DEFAULT NULL',
  'SELECT 1'
);
PREPARE stmt_et FROM @q_et;
EXECUTE stmt_et;
DEALLOCATE PREPARE stmt_et;

-- Drop UNIQUE(log_date) so multiple purchases / events can share the same calendar day
SET @idx_logdate := (
  SELECT COUNT(1) FROM information_schema.statistics
  WHERE table_schema = DATABASE() AND table_name = 'economy_logs' AND index_name = 'log_date' AND non_unique = 0
);
SET @q_ld := IF(
  @idx_logdate > 0,
  'ALTER TABLE `economy_logs` DROP INDEX `log_date`',
  'SELECT 1'
);
PREPARE stmt_ld FROM @q_ld;
EXECUTE stmt_ld;
DEALLOCATE PREPARE stmt_ld;

-- Reporting index (same as 001 tail)
SET @idx_rep := (
  SELECT COUNT(1) FROM information_schema.statistics
  WHERE table_schema = DATABASE() AND table_name = 'economy_logs' AND index_name = 'idx_economy_user_date'
);
SET @q_rep := IF(
  @idx_rep = 0,
  'CREATE INDEX `idx_economy_user_date` ON `economy_logs` (`user_id`, `log_date`)',
  'SELECT 1'
);
PREPARE stmt_rep FROM @q_rep;
EXECUTE stmt_rep;
DEALLOCATE PREPARE stmt_rep;
