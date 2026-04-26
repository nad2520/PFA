SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quest_key` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `coins_reward` int(11) NOT NULL DEFAULT 200,
  `xp_reward` int(11) NOT NULL DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_quest_key` (`quest_key`),
  KEY `idx_quests_active_sort` (`is_active`, `sort_order`, `id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `quests` (`quest_key`, `title`, `description`, `coins_reward`, `xp_reward`, `is_active`, `sort_order`)
VALUES
  ('daily_reader_quest', 'Daily Reader', 'Complete your daily reading quest and claim your bounty.', 200, 120, 1, 1)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `description` = VALUES(`description`),
  `coins_reward` = VALUES(`coins_reward`),
  `xp_reward` = VALUES(`xp_reward`),
  `is_active` = VALUES(`is_active`),
  `sort_order` = VALUES(`sort_order`);
