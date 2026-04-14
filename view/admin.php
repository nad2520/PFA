<?php
$usersCount = $usersCount ?? 0;
$booksCount = $booksCount ?? 0;
$message = $message ?? null;
$error = $error ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexora Admin Dashboard</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>admin_page/scss/main.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>admin_page/scss/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="admin-page">
    <main class="admin-main" style="padding:2rem;">
        <header class="admin-header" style="margin-bottom:2rem;">
            <div class="header-left">
                <h1>Lexora Admin Dashboard</h1>
                <p>Current database state for users and books.</p>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="alert success" style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#e6ffed;color:#064e3b;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert danger" style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#fee2e2;color:#991b1b;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:2rem;">
            <div class="admin-card" style="padding:1.5rem;">
                <h2>Total Users</h2>
                <p style="font-size:2rem;font-weight:700;"><?= htmlspecialchars($usersCount) ?></p>
            </div>
            <div class="admin-card" style="padding:1.5rem;">
                <h2>Total Books</h2>
                <p style="font-size:2rem;font-weight:700;"><?= htmlspecialchars($booksCount) ?></p>
            </div>
        </div>

        <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
            <a href="<?= htmlspecialchars(BASE_URL) ?>admin/users" class="admin-btn primary">Manage Users</a>
            <a href="<?= htmlspecialchars(BASE_URL) ?>admin/books" class="admin-btn secondary">Manage Books</a>
        </div>

        <section class="admin-card" style="padding:1.5rem;">
            <h3>Quick Actions</h3>
            <p>Use the links above to add, search, and delete users or books. Every action reflects the current database state.</p>
        </section>
    </main>
</body>
</html>
