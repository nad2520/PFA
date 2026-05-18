<?php ob_start(); ?>

<h2>Dashboard</h2>

<p>
    Welcome 
    <?= htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?>
</p>

<p><a href="/products.php">View Products</a></p>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>