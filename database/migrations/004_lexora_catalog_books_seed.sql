-- Lexora: seed `books` rows so ids 1–16 match `public/assets/js/models/user_data.js` (catalog + prices).
-- Fixes read-book.php / purchase API when the DB was created empty or books were never inserted.
-- Safe to re-run: upserts by primary key `id`.
-- If phpMyAdmin reports #1054 unknown column `description`, the block below adds it (then re-run or continue).

SET NAMES utf8mb4;

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
(1, 'The Shadow''s Edge', 'Elena Blackwood', 2018, 'Fantasy', '📖', 200, 150, 300, 'All', 1,
 'In a world where shadows hold ancient power, young mage Kael discovers he can bend darkness itself. When the Shadow King rises to consume the realm, Kael must master forbidden arts and forge unlikely alliances to protect everything he loves — even if it means becoming the very thing he fears.'),
(2, 'Whispers in the Dark', 'Marcus Holloway', 2020, 'Horror', '📖', 250, 120, 250, 'All', 1,
 'After inheriting a decaying Victorian manor, journalist Lena Cole begins hearing voices that shouldn''t exist. Each whisper reveals a fragment of a terrible truth buried beneath the floorboards — a truth that the house will kill to keep hidden.'),
(3, 'The Vanishing Hour', 'Claire Ashford', 2019, 'Mystery', '📖', 200, 100, 200, 'All', 0,
 'Every night at exactly 3:17 AM, someone in the coastal town of Mirren Bay disappears without a trace. Detective Iris Thorne has forty-eight hours to unravel a pattern that spans decades before she becomes the next name on the list.'),
(4, 'Blood & Amber', 'Dominic Vance', 2021, 'Crime', '📖', 250, 140, 280, 'All', 1,
 'When a priceless amber artifact surfaces in the criminal underworld of Prague, retired thief Marco Sorel is drawn back into the dangerous game he swore to leave behind. Betrayal, revenge, and a web of lies await at every corner.'),
(5, 'Letters to Autumn', 'Sophia Moreau', 2017, 'Romance', '📖', 200, 80, 150, 'All', 0,
 'A collection of unsent letters leads bookshop owner Autumn Leclair to the doorstep of a reclusive poet living on the French coast. As autumn turns to winter, their guarded hearts begin to thaw in ways neither expected.'),
(6, 'The Glass Curtain', 'Julian Cross', 2016, 'Drama', '📖', 250, 90, 180, 'All', 1,
 'Behind the glittering facade of a prestigious theater company, director Maren Hale fights to stage one final production. As opening night approaches, buried secrets among the cast threaten to bring the curtain down — permanently.'),
(7, 'Empire of Dust', 'Helena Wren', 2015, 'Historical Fiction', '📖', 200, 160, 320, 'All', 1,
 'In the crumbling twilight of the Ottoman Empire, a young cartographer is tasked with mapping lands that powerful men would prefer stay hidden. Her journey through deserts and courts will rewrite history — if she survives.'),
(8, 'The Fae Accord', 'Rowan Ashby', 2022, 'Fantasy', '📖', 250, 110, 220, 'All', 0,
 'When the ancient treaty between humans and fae shatters, half-fae diplomat Elara is the only one who can broker a new peace. But both courts have secrets, and Elara''s own bloodline holds the most dangerous one of all.'),
(9, 'Cellar Door', 'Isaac Thorne', 2020, 'Horror', '📖', 200, 130, 260, 'All', 1,
 'Linguist David Harker always believed ''cellar door'' was the most beautiful phrase in English. Then he found the actual door — hidden in the basement of his childhood home — and what waits behind it is anything but beautiful.'),
(10, 'The Clockwork Witness', 'Ada Pemberton', 2019, 'Mystery', '📖', 250, 95, 190, 'All', 0,
 'In an alternate Victorian London powered by clockwork, a mechanical automaton is the sole witness to a murder in high society. Inspector Wren must decode the machine''s fragmented memories before the killer strikes again.'),
(11, 'Scarlet Alibi', 'Nora Briggs', 2018, 'Crime', '📖', 200, 115, 230, 'All', 0,
 'Defense attorney Cassandra Hale knows her client is guilty. But when she discovers the real crime is far worse than anyone imagined, she must decide: protect the system, or burn it down to find the truth.'),
(12, 'Moonlit Promises', 'Camille Duval', 2023, 'Romance', '📖', 350, 70, 140, 'All', 1,
 'Two rival florists in a small Provençal village are forced to collaborate on a grand wedding. Between midnight flower markets and rain-soaked deliveries, old grudges blossom into something unexpected and tender.'),
(13, 'The Understudy', 'Felix Harlow', 2017, 'Drama', '📖', 350, 85, 170, 'All', 0,
 'Always in someone else''s shadow, understudy Nadia finally gets her chance when the leading actress vanishes on opening night. But the spotlight reveals more than talent — it exposes the lies Nadia told to get there.'),
(14, 'The Cartographer''s Lie', 'Sebastian Cole', 2014, 'Historical Fiction', '📖', 400, 155, 310, 'All', 1,
 'A 16th-century mapmaker discovers that the world''s most trusted atlas contains deliberate falsehoods — placed there by someone willing to kill to control what humanity believes about the shape of the world.'),
(15, 'Thornfield Rising', 'Ivy Blackthorn', 2021, 'Fantasy', '📖', 400, 125, 240, 'All', 0,
 'The ancient fortress of Thornfield has stood empty for centuries. When a young herbalist takes shelter within its walls, the castle awakens — and with it, a curse that binds her fate to a ghostly lord trapped between worlds.'),
(16, 'The Bone Garden', 'Livia Crane', 2022, 'Horror', '📖', 450, 145, 290, 'All', 0,
 'Archaeologist Dr. Sable Voss unearths a garden of human bones arranged in impossible patterns beneath a quiet English village. The bones are growing. And the village remembers nothing — or claims to.')
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
