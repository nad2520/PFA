<section style="background:#191613;border:1px solid #3f3a2f;border-radius:1rem;padding:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
        <div>
            <h1 style="margin:0;color:#f5edd6;">Lecture : <?= htmlspecialchars($book['title']) ?></h1>
            <p style="color:#bdb1a0;">Page <?= $pageIndex + 1 ?> / <?= count($pages) ?></p>
        </div>
        <div>
            <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&view=book-detail&id=<?= urlencode($book['id']) ?>" style="padding:0.75rem 1rem;background:#3f3a2f;color:#f5edd6;border-radius:0.75rem;text-decoration:none;">Retour au détail</a>
        </div>
    </div>

    <article style="background:#111009;padding:1.5rem;border-radius:1rem;line-height:1.9;color:#ddd;min-height:320px;">
        <?= nl2br(htmlspecialchars($pages[$pageIndex])) ?>
    </article>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;gap:1rem;flex-wrap:wrap;">
        <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&view=read-book&id=<?= urlencode($book['id']) ?>&page=<?= max(0, $pageIndex - 1) ?>" style="padding:0.75rem 1rem;background:#3f3a2f;color:#f5edd6;border-radius:0.75rem;text-decoration:none;">Précédent</a>
        <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&view=read-book&id=<?= urlencode($book['id']) ?>&page=<?= min(count($pages) - 1, $pageIndex + 1) ?>" style="padding:0.75rem 1rem;background:#d6b56f;color:#111;border-radius:0.75rem;text-decoration:none;">Suivant</a>
    </div>
</section>
