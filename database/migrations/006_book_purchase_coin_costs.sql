-- Align `books.coinCost` with `public/assets/js/models/user_data.js` bookPrices.cost.
-- Run once if you already imported 004 with the old high prices. Safe to re-run.

SET NAMES utf8mb4;

UPDATE `books` SET `coinCost` = 200 WHERE `id` IN (1, 3, 5, 7, 9, 11);
UPDATE `books` SET `coinCost` = 250 WHERE `id` IN (2, 4, 6, 8, 10);
UPDATE `books` SET `coinCost` = 350 WHERE `id` IN (12, 13);
UPDATE `books` SET `coinCost` = 400 WHERE `id` IN (14, 15);
UPDATE `books` SET `coinCost` = 450 WHERE `id` = 16;
