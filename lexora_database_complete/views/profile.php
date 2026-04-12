<section style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.5rem;min-height:60vh;">
    <div style="background:#181513;border:1px solid #3f3a2f;border-radius:1rem;padding:1.5rem;">
        <h2 style="color:#f5edd6;">Bonjour, <?= htmlspecialchars($profile['username']) ?></h2>
        <p style="color:#bdb1a0;">Niveau <?= htmlspecialchars($profile['level'] ?? 1) ?> · <?= htmlspecialchars($profile['currentCoins'] ?? 0) ?> coins</p>
        <div style="margin-top:1.5rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            <div style="background:#15120f;padding:1rem;border-radius:1rem;text-align:center;">
                <div style="font-size:1.2rem;font-weight:700;color:#d6b56f;"><?= htmlspecialchars($profile['currentCoins'] ?? 0) ?></div>
                <div style="color:#bdb1a0;font-size:0.85rem;">Coins</div>
            </div>
            <div style="background:#15120f;padding:1rem;border-radius:1rem;text-align:center;">
                <div style="font-size:1.2rem;font-weight:700;color:#d6b56f;">??</div>
                <div style="color:#bdb1a0;font-size:0.85rem;">Rune magique</div>
            </div>
            <div style="background:#15120f;padding:1rem;border-radius:1rem;text-align:center;">
                <div style="font-size:1.2rem;font-weight:700;color:#d6b56f;">??</div>
                <div style="color:#bdb1a0;font-size:0.85rem;">Progression</div>
            </div>
        </div>
    </div>

    <div style="background:#181513;border:1px solid #3f3a2f;border-radius:1rem;padding:1.5rem;">
        <h2 style="color:#f5edd6;">Ma bibliothèque</h2>
        <?php if (empty($library)): ?>
            <p style="color:#bdb1a0;">Aucun livre en lecture pour le moment.</p>
        <?php else: ?>
            <ul style="list-style:none;padding:0;margin:0;display:grid;gap:0.75rem;">
                <?php foreach ($library as $item): ?>
                    <li style="background:#111009;padding:1rem;border-radius:0.85rem;">
                        <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                        <small style="color:#bdb1a0;">Statut: <?= htmlspecialchars($item['status']) ?> · <?= (int)($item['progressPercent'] ?? 0) ?>%</small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>

<section style="margin-top:1.5rem;">
    <h2 style="color:#f5edd6;">Ma liste de souhaits</h2>
    <?php if (empty($wishlist)): ?>
        <p style="color:#bdb1a0;">Votre liste de souhaits est vide.</p>
    <?php else: ?>
        <div style="display:grid;gap:1rem;">
            <?php foreach ($wishlist as $item): ?>
                <div style="background:#111009;padding:1rem;border-radius:0.85rem;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                        <small style="color:#bdb1a0;">Coût <?= (int)($item['coinCost'] ?? 0) ?> coins</small>
                    </div>
                    <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&action=buy&id=<?= urlencode($item['bookId']) ?>" style="color:#d6b56f;text-decoration:none;">Acheter</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
