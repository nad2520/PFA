-- Lexora coin system support
-- Run once on the lexora database.

SET NAMES utf8mb4;

-- Track 10-page reward steps per owned book
ALTER TABLE `user_books`
  ADD COLUMN `page_reward_steps` int(11) NOT NULL DEFAULT 0;

-- Track one-time quest claims to prevent duplication
CREATE TABLE IF NOT EXISTS `user_quest_rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `quest_key` varchar(100) NOT NULL,
  `coins_rewarded` int(11) NOT NULL DEFAULT 200,
  `claimed_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_quest` (`user_id`, `quest_key`),
  KEY `idx_user_quest_user` (`user_id`),
  CONSTRAINT `fk_user_quest_rewards_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Keep balances safe
UPDATE `users` SET `coins` = 0 WHERE `coins` < 0;
ALTER TABLE `users` ALTER COLUMN `coins` SET DEFAULT 1000;
