<?php
$users = $users ?? [];
$search = $search ?? '';
$role = $role ?? 'All';
$message = $message ?? null;
$error = $error ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexora Admin - Manage Users</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>admin_page/scss/main.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL) ?>admin_page/scss/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="admin-page">
    <main class="admin-main" style="padding:2rem;">
        <header class="admin-header" style="margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;">
            <div>
                <h1>User Management</h1>
                <p>Search, add, and remove users directly from the database.</p>
            </div>
            <nav style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                <a href="<?= htmlspecialchars(BASE_URL) ?>admin" class="admin-btn secondary">Dashboard</a>
                <a href="<?= htmlspecialchars(BASE_URL) ?>admin/books" class="admin-btn secondary">Manage Books</a>
            </nav>
        </header>

        <?php if ($message): ?>
            <div class="alert success" style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#e6ffed;color:#064e3b;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert danger" style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#fee2e2;color:#991b1b;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section style="display:grid;grid-template-columns:1fr auto;gap:1rem;margin-bottom:1.5rem;">
            <form method="get" action="<?= htmlspecialchars(BASE_URL) ?>admin/users" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
                <div style="flex:1;min-width:200px;">
                    <label>Search users</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="admin-input full" placeholder="Name or email">
                </div>
                <div style="flex:0 0 180px;min-width:160px;">
                    <label>Role</label>
                    <select name="role" class="admin-input full">
                        <option <?= $role === 'All' ? 'selected' : '' ?>>All</option>
                        <option <?= $role === 'user' ? 'selected' : '' ?>>user</option>
                        <option <?= $role === 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option <?= $role === 'User +18' ? 'selected' : '' ?>>User +18</option>
                        <option <?= $role === 'User -18' ? 'selected' : '' ?>>User -18</option>
                    </select>
                </div>
                <button type="submit" class="admin-btn primary">Search</button>
            </form>

            <form method="post" action="<?= htmlspecialchars(BASE_URL) ?>admin/users" style="border:1px solid rgba(255,255,255,0.08);padding:1rem;border-radius:0.75rem;min-width:280px;">
                <h2 style="margin-top:0;">Add New User</h2>
                <input type="hidden" name="action" value="add">
                <label>Name</label>
                <input type="text" name="nom" class="admin-input full" required>
                <label>Email</label>
                <input type="email" name="email" class="admin-input full" required>
                <label>Password</label>
                <input type="password" name="password" class="admin-input full" required>
                <label>Role</label>
                <select name="role" class="admin-input full">
                    <option value="user">user</option>
                    <option value="Admin">Admin</option>
                    <option value="User +18">User +18</option>
                    <option value="User -18">User -18</option>
                </select>
                <label>Coins</label>
                <input type="number" name="coins" class="admin-input full" value="0" min="0">
                <label>Level</label>
                <input type="number" name="level" class="admin-input full" value="1" min="1">
                <label>Birthdate</label>
                <input type="date" name="birthdate" class="admin-input full">
                <button type="submit" class="admin-btn primary" style="margin-top:1rem;">Add User</button>
            </form>
        </section>

        <section class="admin-card" style="padding:1rem;">
            <div class="admin-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Level</th>
                            <th>Coins</th>
                            <th>Birthdate</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['nom']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td><?= htmlspecialchars($user['level']) ?></td>
                                <td><?= htmlspecialchars($user['coins']) ?></td>
                                <td><?= htmlspecialchars($user['birthdate']) ?></td>
                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                <td>
                                    <form method="post" action="<?= htmlspecialchars(BASE_URL) ?>admin/users" style="display:inline-block;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                        <button type="submit" class="admin-btn danger" onclick="return confirm('Delete this user?');">Delete</button>
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
