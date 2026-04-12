<section class="hero-section" style="background:#110f0c;padding:3rem;border-radius:1rem;color:#f5edd6;margin-bottom:2rem;">
    <h1 style="font-family:'Playfair Display',serif;font-size:3rem;margin-bottom:0.75rem;">Lexora</h1>
    <p style="max-width:42rem;line-height:1.6;">Découvrez votre royaume de lecture : catalogue de livres, recommandations et lectures personnalisées.</p>
</section>

<section style="margin-bottom:2rem;">
    <form method="get" action="<?= htmlspecialchars($baseUrl) ?>" style="display:flex;gap:0.5rem;flex-wrap:wrap;">
        <input type="hidden" name="page" value="home">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher un titre ou un auteur..." style="flex:1;padding:0.85rem;border:1px solid #6b5b3d;border-radius:0.5rem;background:#1f1b16;color:#f5edd6;">
        <button type="submit" style="padding:0.85rem 1.5rem;border:none;border-radius:0.5rem;background:#d6b56f;color:#111;font-weight:700;">Rechercher</button>
    </form>
</section>

<?php if (!empty($trending)): ?>
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.5rem;margin-bottom:1rem;">Tendances</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
            <?php foreach ($trending as $book): ?>
                <article style="background:#191613;border:1px solid #3f3a2f;border-radius:1rem;padding:1rem;">
                    <h3 style="margin-top:0;color:#f0d9a5;"><?= htmlspecialchars($book['title']) ?></h3>
                    <p style="font-size:0.95rem;color:#ccc;margin:0.5rem 0;"><?= htmlspecialchars($book['description'] ?? '') ?></p>
                    <a href="<?= htmlspecialchars($baseUrl) ?>?page=home&action=bookDetail&id=<?= urlencode($book['id']) ?>" style="color:#d6b56f;text-decoration:none;font-weight:700;">Voir le livre →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section>
    <h2 style="font-size:1.5rem;margin-bottom:1rem;">Catalogue</h2>
    <?php if (empty($books)): ?>
        <p>Aucun livre trouvé pour votre recherche.</p>
    <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
            <?php foreach ($books as $book): ?>
                <article style="background:#191613;border:1px solid #3f3a2f;border-radius:1rem;padding:1rem;display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <h3 style="margin-top:0;color:#f5edd6;"><?= htmlspecialchars($book['title']) ?></h3>
                        <p style="color:#bdb1a0;font-size:0.95rem;"><?= htmlspecialchars($book['description'] ?? '') ?></p>
                    </div>
                    <div style="margin-top:1rem;display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:#d6b56f;font-weight:700;"><?= (int)($book['coinCost'] ?? 0) ?> coins</span>
                        <a href="<?= htmlspecialchars($baseUrl) ?>?page=home&action=bookDetail&id=<?= urlencode($book['id']) ?>" style="color:#d6b56f;text-decoration:none;">Détails</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
