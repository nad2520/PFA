<section style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">
    <div style="background:#191613;border:1px solid #3f3a2f;border-radius:1rem;padding:1.5rem;">
        <h1 style="margin-top:0;color:#f5edd6;"><?= htmlspecialchars($book['title']) ?></h1>
        <p style="color:#bdb1a0;margin:0.75rem 0;">Auteur: <?= htmlspecialchars($book['isbn'] ?: 'N/A') ?></p>
        <p style="color:#ccc;line-height:1.6;"><?= nl2br(htmlspecialchars($book['description'] ?? 'Aucune description disponible.')) ?></p>
        <p style="margin-top:1rem;color:#d6b56f;font-weight:700;">Coût: <?= (int)($book['coinCost'] ?? 0) ?> coins</p>
        <?php if (!empty($profile)): ?>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;">
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&action=buy&id=<?= urlencode($book['id']) ?>" style="padding:0.8rem 1rem;background:#d6b56f;color:#111;border-radius:0.75rem;text-decoration:none;">Acheter</a>
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&action=wishlist&id=<?= urlencode($book['id']) ?>" style="padding:0.8rem 1rem;background:#3f3a2f;color:#f5edd6;border-radius:0.75rem;text-decoration:none;">Ajouter à la liste</a>
            </div>
        <?php else: ?>
            <p style="margin-top:1rem;color:#bdb1a0;">Connectez-vous pour acheter ou ajouter à votre liste.</p>
        <?php endif; ?>
    </div>
    <aside style="background:#181513;border:1px solid #3f3a2f;border-radius:1rem;padding:1.5rem;">
        <h2 style="color:#f5edd6;">Détails du livre</h2>
        <dl style="color:#ccc;line-height:1.8;">
            <dt>Pages</dt>
            <dd><?= htmlspecialchars($book['pageCount'] ?? 'N/A') ?></dd>
            <dt>Langue</dt>
            <dd><?= htmlspecialchars($book['language'] ?? 'Français') ?></dd>
            <dt>Public</dt>
            <dd><?= $book['isAdulte'] ? 'Adulte' : 'Tout public' ?></dd>
            <dt>Popularité</dt>
            <dd><?= $book['isTrending'] ? 'Tendance' : 'Classique' ?></dd>
        </dl>
        <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&view=read-book&id=<?= urlencode($book['id']) ?>" style="display:inline-block;margin-top:1rem;padding:0.85rem 1rem;background:#d6b56f;color:#111;border-radius:0.75rem;text-decoration:none;">Lire maintenant</a>
    </aside>
</section>
