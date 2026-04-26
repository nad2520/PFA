-- Lexora: seed 10 multi-genre books and mappings
-- Safe to re-run (upsert by id, insert-ignore for mappings).

SET NAMES utf8mb4;

-- Keep compatibility if `description` was not added yet.
SET @col_books_description := (
  SELECT COUNT(1)
  FROM information_schema.COLUMNS
  WHERE table_schema = DATABASE()
    AND table_name = 'books'
    AND column_name = 'description'
);
SET @q_books_description := IF(
  @col_books_description = 0,
  'ALTER TABLE `books` ADD COLUMN `description` TEXT NULL DEFAULT NULL',
  'SELECT 1'
);
PREPARE stmt_books_description FROM @q_books_description;
EXECUTE stmt_books_description;
DEALLOCATE PREPARE stmt_books_description;

INSERT INTO `books` (`id`, `title`, `author`, `publication_year`, `genre`, `cover`, `coinCost`, `xpReward`, `coinReward`, `audience`, `trending`, `description`) VALUES
(17, 'Velvet Graves', 'Mara Ellison', 2024, 'Horror', '📖', 280, 130, 250, 'All', 1,
 'A grieving violinist receives love letters from someone buried decades ago. Following the letters leads her into a manor where every romantic promise hides a brutal truth.'),
(18, 'Ashes of June', 'Tobias Hale', 2021, 'Romance', '📖', 260, 110, 220, 'All', 0,
 'Two rival journalists reunite while covering a chain of suspicious fires. Their chemistry reignites as clues reveal a conspiracy larger than either story.'),
(19, 'Lanterns Under Blackwater', 'Irene Voss', 2020, 'Mystery', '📖', 300, 140, 270, 'All', 1,
 'In a flooded old quarter, a mapmaker and a detective chase a killer who stages crimes as theatrical scenes.'),
(20, 'Kingdom of Quiet Knives', 'Rhett Callow', 2023, 'Fantasy', '📖', 320, 150, 300, 'All', 1,
 'A royal archivist uncovers forbidden prophecies as assassins rewrite history one witness at a time.'),
(21, 'The Last Debutante Trial', 'Camille Renaud', 2016, 'Historical Fiction', '📖', 310, 145, 290, 'All', 0,
 'In 1892, a noblewoman accused of murder must decode coded love notes to clear her name before the public trial destroys her family.'),
(22, 'How to Bury a Crown', 'Nadia Sterling', 2019, 'Drama', '📖', 270, 105, 210, 'All', 0,
 'A disgraced princess returns to a fractured court where every reconciliation has a price and every confession is evidence.'),
(23, 'Moonfall District', 'Cyrus Vale', 2022, 'Crime', '📖', 290, 135, 260, 'All', 1,
 'A detective and a con artist form a dangerous alliance to stop a ritual murder ring operating through the city black market.'),
(24, 'Salt in the Chapel Walls', 'Beatrice North', 2015, 'Horror', '📖', 300, 150, 280, 'All', 0,
 'A coastal town chapel begins to weep salt each night. A historian discovers the phenomenon is tied to a century-old wedding massacre.'),
(25, 'Petals for the Widowmaker', 'Jules Maren', 2024, 'Romance', '📖', 255, 100, 200, 'All', 1,
 'A florist who secretly forges alibis for criminals falls for the prosecutor assigned to dismantle her network.'),
(26, 'The Atlas of Drowned Hearts', 'Lena Corbett', 2018, 'Historical Fiction', '📖', 330, 160, 320, 'All', 1,
 'During a polar expedition, a cartographer records impossible coastlines while falling for a rival scholar who may be sabotaging the mission.')
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `author` = VALUES(`author`),
  `publication_year` = VALUES(`publication_year`),
  `genre` = VALUES(`genre`),
  `cover` = VALUES(`cover`),
  `coinCost` = VALUES(`coinCost`),
  `xpReward` = VALUES(`xpReward`),
  `coinReward` = VALUES(`coinReward`),
  `audience` = VALUES(`audience`),
  `trending` = VALUES(`trending`),
  `description` = VALUES(`description`);

INSERT IGNORE INTO `book_genres` (`book_id`, `genre_name`) VALUES
-- 17
(17, 'Horror'),
(17, 'Romance'),
(17, 'Mystery'),
-- 18
(18, 'Romance'),
(18, 'Crime'),
(18, 'Drama'),
-- 19
(19, 'Mystery'),
(19, 'Horror'),
(19, 'Fantasy'),
-- 20
(20, 'Fantasy'),
(20, 'Crime'),
(20, 'Drama'),
-- 21
(21, 'Historical Fiction'),
(21, 'Mystery'),
(21, 'Romance'),
-- 22
(22, 'Drama'),
(22, 'Historical Fiction'),
(22, 'Fantasy'),
-- 23
(23, 'Crime'),
(23, 'Horror'),
(23, 'Mystery'),
-- 24
(24, 'Horror'),
(24, 'Historical Fiction'),
(24, 'Drama'),
-- 25
(25, 'Romance'),
(25, 'Crime'),
(25, 'Drama'),
-- 26
(26, 'Historical Fiction'),
(26, 'Romance'),
(26, 'Fantasy');

