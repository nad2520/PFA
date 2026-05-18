<?php ob_start(); ?>

<h2>Products</h2>

<?php if (empty($products)) : ?>
    <p>No products available.</p>
<?php else: ?>

<ul>
<?php foreach ($products as $product): ?>
    <li>
        <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?> -
        <?= number_format((float)$product['price'], 2) ?> DT -
        Stock: <?= (int)$product['stock'] ?>
    </li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>