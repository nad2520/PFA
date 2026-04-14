<?php
$books = $books ?? [];
$search = $search ?? '';
$genre = $genre ?? 'All';
$audience = $audience ?? 'All';
$message = $message ?? null;
$error = $error ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexora Admin - Manage Books</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>admin_page/scss/main.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>admin_page/scss/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="admin-page">
    <main class="admin-main" style="padding:2rem;">
        <header class="admin-header" style="margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;">
            <div>
                <h1>Books Management</h1>
                <p>Search, add, and remove books from the current database.</p>
            </div>
            <nav style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                <a href="<?= htmlspecialchars(BASE_URL) ?>admin" class="admin-btn secondary">Dashboard</a>
                <a href="<?= htmlspecialchars(BASE_URL) ?>admin/users" class="admin-btn secondary">Manage Users</a>
            </nav>
        </header>

        <?php if ($message): ?>
            <div class="alert success" style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#e6ffed;color:#064e3b;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert danger" style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#fee2e2;color:#991b1b;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section style="display:grid;grid-template-columns:1fr auto;gap:1rem;margin-bottom:1.5rem;">
            <form method="get" action="<?= htmlspecialchars(BASE_URL) ?>admin/books" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
                <div style="flex:1;min-width:220px;">
                    <label>Search books</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="admin-input full" placeholder="Title, author or description">
                </div>
                <div style="flex:0 0 180px;min-width:160px;">
                    <label>Genre</label>
                    <select name="genre" class="admin-input full">
                        <option <?= $genre === 'All' ? 'selected' : '' ?>>All</option>
                        <option <?= $genre === 'Fantasy' ? 'selected' : '' ?>>Fantasy</option>
                        <option <?= $genre === 'Romance' ? 'selected' : '' ?>>Romance</option>
                        <option <?= $genre === 'Sci-Fi' ? 'selected' : '' ?>>Sci-Fi</option>
                        <option <?= $genre === 'Historical' ? 'selected' : '' ?>>Historical</option>
                        <option <?= $genre === 'Thriller' ? 'selected' : '' ?>>Thriller</option>
                    </select>
                </div>
                <div style="flex:0 0 180px;min-width:160px;">
                    <label>Audience</label>
                    <select name="audience" class="admin-input full">
                        <option <?= $audience === 'All' ? 'selected' : '' ?>>All</option>
                        <option <?= $audience === 'All Age' ? 'selected' : '' ?>>All Age</option>
                        <option <?= $audience === '+18 Only' ? 'selected' : '' ?>>+18 Only</option>
                    </select>
                </div>
                <button type="submit" class="admin-btn primary">Search</button>
            </form>

            <form method="post" action="<?= htmlspecialchars(BASE_URL) ?>admin/books" style="border:1px solid rgba(255,255,255,0.08);padding:1rem;border-radius:0.75rem;min-width:280px;">
                <h2 style="margin-top:0;">Add New Book</h2>
                <input type="hidden" name="action" value="add">
                <label>Title</label>
                <input type="text" name="title" class="admin-input full" required>
                <label>Author</label>
                <input type="text" name="author" class="admin-input full" required>
                <label>Genre</label>
                <input type="text" name="genre" class="admin-input full" required>
                <label>Audience</label>
                <input type="text" name="audience" class="admin-input full" value="All Age" required>
                <label>Cover Emoji</label>
                <input type="text" name="coverEmoji" class="admin-input full" value="?">
                <label>Description</label>
                <textarea name="description" class="admin-input full" rows="3"></textarea>
                <div class="grid-2" style="gap:0.75rem;margin-top:0.75rem;">
                    <div><label>Coin Cost</label><input type="number" name="coinCost" class="admin-input full" value="100" min="0"></div>
                    <div><label>XP Reward</label><input type="number" name="xpReward" class="admin-input full" value="150" min="0"></div>
                </div>
                <div class="grid-2" style="gap:0.75rem;">
                    <div><label>Coin Reward</label><input type="number" name="coinReward" class="admin-input full" value="40" min="0"></div>
                    <div><label style="display:block;margin-bottom:0.3rem;">Trending</label><input type="checkbox" name="trending" value="1"></div>
                </div>
                <button type="submit" class="admin-btn primary" style="margin-top:1rem;">Add Book</button>
            </form>
        </section>

        <section class="admin-card" style="padding:1rem;">
            <div class="admin-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Audience</th>
                            <th>Cost</th>
                            <th>Reward XP</th>
                            <th>Reward Coins</th>
                            <th>Trending</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['id']) ?></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><?= htmlspecialchars($book['genre']) ?></td>
                                <td><?= htmlspecialchars($book['audience']) ?></td>
                                <td><?= htmlspecialchars($book['coin_cost'] ?? $book['coinCost'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($book['xp_reward'] ?? $book['xpReward'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($book['coin_reward'] ?? $book['coinReward'] ?? 0) ?></td>
                                <td><?= htmlspecialchars(!empty($book['trending']) ? 'Yes' : 'No') ?></td>
                                <td>
                                    <form method="post" action="<?= htmlspecialchars(BASE_URL) ?>admin/books" style="display:inline-block;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($book['id']) ?>">
                                        <button type="submit" class="admin-btn danger" onclick="return confirm('Delete this book?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
