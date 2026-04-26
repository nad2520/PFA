-- Lexora: relational multi-genre support for books
-- Safe to re-run.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `book_genres` (
  `book_id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`book_id`, `genre_name`),
  KEY `idx_book_genres_genre` (`genre_name`),
  CONSTRAINT `book_genres_book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

