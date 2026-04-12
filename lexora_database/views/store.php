<section style="margin-bottom:2rem;">
    <h1 style="color:#f5edd6;">Boutique Lexora</h1>
    <p style="color:#bdb1a0;">Achetez des livres avec vos coins et ajoutez-les à votre bibliothèque.</p>
</section>

<section style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
    <?php foreach ($books as $book): ?>
        <article style="background:#191613;border:1px solid #3f3a2f;border-radius:1rem;padding:1rem;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <h3 style="margin-top:0;color:#f5edd6;"><?= htmlspecialchars($book['title']) ?></h3>
                <p style="color:#ccc;font-size:0.95rem;min-height:4rem;"><?= htmlspecialchars($book['description'] ?? '') ?></p>
            </div>
            <div style="margin-top:1rem;display:flex;align-items:center;justify-content:space-between;">
                <span style="color:#d6b56f;font-weight:700;"><?= (int)($book['coinCost'] ?? 0) ?> coins</span>
                <div style="display:flex;gap:0.5rem;">
                    <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&action=buy&id=<?= urlencode($book['id']) ?>" style="padding:0.6rem 0.9rem;background:#d6b56f;color:#111;border-radius:0.75rem;text-decoration:none;">Acheter</a>
                    <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&action=wishlist&id=<?= urlencode($book['id']) ?>" style="padding:0.6rem 0.9rem;background:#3f3a2f;color:#f5edd6;border-radius:0.75rem;text-decoration:none;">Liste</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>
