SET NAMES utf8mb4;

ALTER TABLE `quests`
  ADD COLUMN `quest_type` varchar(50) NOT NULL DEFAULT 'read_pages_total' AFTER `description`,
  ADD COLUMN `target_value` int(11) NOT NULL DEFAULT 1 AFTER `quest_type`;

CREATE TABLE IF NOT EXISTS `user_quest_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `progress_value` int(11) NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `reward_granted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_quest_progress` (`user_id`, `quest_id`),
  KEY `idx_uqp_quest` (`quest_id`),
  CONSTRAINT `fk_uqp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_uqp_quest` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
