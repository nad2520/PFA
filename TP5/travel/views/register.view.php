<?php ob_start(); ?>

<h2>Register</h2>

<?php if (!empty($error)) : ?>
    <p style="color:red;">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
</form>

<p><a href="/login.php">Login</a></p>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>