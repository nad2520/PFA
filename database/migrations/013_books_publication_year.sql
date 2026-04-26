-- Lexora: add publication year support for catalog search
-- Safe to re-run.

SET NAMES utf8mb4;

SET @col_books_publication_year := (
  SELECT COUNT(1)
  FROM information_schema.COLUMNS
  WHERE table_schema = DATABASE()
    AND table_name = 'books'
    AND column_name = 'publication_year'
);
SET @q_books_publication_year := IF(
  @col_books_publication_year = 0,
  'ALTER TABLE `books` ADD COLUMN `publication_year` INT(4) NULL DEFAULT NULL AFTER `author`',
  'SELECT 1'
);
PREPARE stmt_books_publication_year FROM @q_books_publication_year;
EXECUTE stmt_books_publication_year;
DEALLOCATE PREPARE stmt_books_publication_year;

UPDATE `books`
SET `publication_year` = CASE `id`
  WHEN 1 THEN 2018
  WHEN 2 THEN 2020
  WHEN 3 THEN 2019
  WHEN 4 THEN 2021
  WHEN 5 THEN 2017
  WHEN 6 THEN 2016
  WHEN 7 THEN 2015
  WHEN 8 THEN 2022
  WHEN 9 THEN 2020
  WHEN 10 THEN 2019
  WHEN 11 THEN 2018
  WHEN 12 THEN 2023
  WHEN 13 THEN 2017
  WHEN 14 THEN 2014
  WHEN 15 THEN 2021
  WHEN 16 THEN 2022
  WHEN 17 THEN 2024
  WHEN 18 THEN 2021
  WHEN 19 THEN 2020
  WHEN 20 THEN 2023
  WHEN 21 THEN 2016
  WHEN 22 THEN 2019
  WHEN 23 THEN 2022
  WHEN 24 THEN 2015
  WHEN 25 THEN 2024
  WHEN 26 THEN 2018
  ELSE `publication_year`
END
WHERE `publication_year` IS NULL OR `publication_year` = 0;

