<?php ob_start(); ?>

<h2>Login</h2>

<?php if (!empty($error)) : ?>
    <p style="color:red;">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

<p><a href="/register.php">Register</a></p>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>