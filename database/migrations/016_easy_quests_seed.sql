SET NAMES utf8mb4;

INSERT INTO `quests`
(`quest_key`, `title`, `description`, `quest_type`, `target_value`, `coins_reward`, `xp_reward`, `is_active`, `sort_order`)
VALUES
  ('read_20_pages_total', 'Page Explorer', 'Read 20 pages in total to earn your bounty.', 'read_pages_total', 20, 120, 80, 1, 10),
  ('complete_1_book', 'First Finish', 'Complete 1 full book from your library.', 'complete_books_count', 1, 220, 140, 1, 20),
  ('add_3_books_to_list', 'Curator Starter', 'Add 3 books to My List.', 'add_to_list_count', 3, 90, 60, 1, 30)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `description` = VALUES(`description`),
  `quest_type` = VALUES(`quest_type`),
  `target_value` = VALUES(`target_value`),
  `coins_reward` = VALUES(`coins_reward`),
  `xp_reward` = VALUES(`xp_reward`),
  `is_active` = VALUES(`is_active`),
  `sort_order` = VALUES(`sort_order`);
