<?php
include("config/database.php");

$sql = file_get_contents("lexora_schema.sql");
$cnx->exec($sql);
echo "Schema built!\n";


$books = [
    ["Throne of Midnight", "E. Blackwood", "Fantasy", "🏰", 150, 200, 50, "All", 1, "An epic tale of power and darkness."],
    ["Crimson Desires", "V. Morel", "Romance", "🌹", 200, 180, 60, "+18 Only", 0, "A passionate romance for mature readers."],
    ["The Whispering Stars", "A. Celeste", "Sci-Fi", "⭐", 120, 160, 40, "All", 1, "Explore the cosmos through young eyes."],
    ["Blood & Gold", "M. Thorne", "Historical", "⚔️", 180, 220, 70, "+18 Only", 0, "War, betrayal, and gold in ancient times."],
    ["Dragon's Lullaby", "S. Ember", "Fantasy", "🐉", 100, 140, 35, "All", 1, "A young dragon finds her destiny."],
    ["Neon Labyrinth", "K. Raven", "Thriller", "🔮", 160, 190, 55, "All", 0, "A cyberpunk mystery unfolds in neon rain."]
];

foreach ($books as $b) {
    // Check if exists
    $stmt = $cnx->prepare("SELECT id FROM books WHERE title = ?");
    $stmt->execute([$b[0]]);
    if ($stmt->rowCount() == 0) {
        $stmt = $cnx->prepare("INSERT INTO books (title, author, genre, cover_emoji, coin_cost, xp_reward, coin_reward, audience, trending, description) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute($b);
    }
}

// Since users might not map exactly or have different IDs in the posts, we will just create a "System" user if no user exists, and assign posts to them.
$stmt = $cnx->query("SELECT id FROM users LIMIT 1");
$first_user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$first_user) {
    $cnx->exec("INSERT INTO users (nom, email, password, role) VALUES ('System', 'system@lexora.test', '123456', 'Admin')");
    $user_id = $cnx->lastInsertId();
} else {
    $user_id = $first_user['id'];
}

$posts = [
    [$user_id, 1, "The ending of Throne of Midnight was...", "Wow just amazing.", "spoiler", 143, 28, "Flagged by Lumo"],
    [$user_id, NULL, "Best fantasy reads of the decade?", "What are your favorites?", "discussion", 89, 42, "Clean"],
    [$user_id, 5, "Dragon's Lullaby - my full review", "I loved the dragon part.", "review", 56, 14, "Clean"],
    [$user_id, 6, "Is the Neon Labyrinth protagonist actually...", "It is a clone.", "theory", 210, 67, "Pending Admin Review"],
    [$user_id, NULL, "BUY COINS CHEAP HERE!!! LIMITED TIME", "Scam link.", "discussion", 0, 2, "Flagged by Lumo"],
    [$user_id, 2, "Crimson Desires: Chapter 7 analysis", "Great character development.", "review", 34, 8, "Reviewed"]
];

foreach ($posts as $p) {
    $stmt = $cnx->prepare("SELECT id FROM posts WHERE title = ?");
    $stmt->execute([$p[2]]);
    if ($stmt->rowCount() == 0) {
        $stmt = $cnx->prepare("INSERT INTO posts (user_id, book_id, title, content, tag, upvotes, comments_count, status) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute($p);
    }
}

$flags = [
    [1, $user_id, "Spoiler", "High", "Mark as Spoiler", "Pending"],
    [5, $user_id, "Spam", "High", "Hide Post", "Pending"],
    [6, $user_id, "Offensive", "Medium", "Warn User", "Approved"], // fake link to 6 just for simulation
    [6, $user_id, "Age-Sensitive", "Medium", "Escalate to Admin Review", "Pending"],
    [4, $user_id, "Spoiler", "Low", "Mark as Spoiler", "Rejected"],
    [2, $user_id, "Spam", "Low", "Warn User", "Approved"]
];

foreach ($flags as $f) {
    $stmt = $cnx->prepare("SELECT id FROM flags WHERE post_id = ? AND type = ? AND status = ?");
    $stmt->execute([$f[0], $f[2], $f[5]]);
    if ($stmt->rowCount() == 0) {
        $stmt = $cnx->prepare("INSERT INTO flags (post_id, reporter_id, type, severity, suggested_action, status) VALUES (?,?,?,?,?,?)");
        $stmt->execute($f);
    }
}

echo "Database hydrated with dummy data!\n";
?>
