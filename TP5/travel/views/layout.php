<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TP PHP Testing</title>
</head>
<body>

<nav>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="/dashboard">Dashboard</a> |
        <a href="/products">Products</a> |
        <a href="/logout">Logout</a>
    <?php else: ?>
        <a href="/login">Login</a> |
        <a href="/register">Register</a>
    <?php endif; ?>
</nav>

<hr>

<?= $content ?>

</body>
</html>