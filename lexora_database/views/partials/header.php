<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Lexora') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($assetsUrl) ?>/scss/main.css">
    <?php if (!empty($useAdminStyles) && $useAdminStyles): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($this->config['admin_assets_url'] ?? '/PFA/admin_page') ?>/scss/admin.css">
    <?php endif; ?>
</head>
<body>
    <nav class="global-header" style="background:#111;color:#f5f0e1;padding:1rem;">
        <div class="header-inner" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
            <a href="<?= htmlspecialchars($baseUrl) ?>" class="logo" style="font-weight:700;font-size:1.2rem;color:#f5f0e1;text-decoration:none;">?? LEXORA</a>
            <div style="flex:1"></div>
            <?php if (!empty($_SESSION['profile'])): ?>
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&view=profile" class="header-nav-link">Mon Profil</a>
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&view=store" class="header-nav-link">Ma Boutique</a>
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=home&action=auth" class="header-nav-link">Accueil</a>
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=user&action=logout" class="header-nav-link">Déconnexion</a>
            <?php else: ?>
                <a href="<?= htmlspecialchars($baseUrl) ?>?page=home&action=auth" class="header-nav-link">Se connecter</a>
            <?php endif; ?>
        </div>
    </nav>
    <main class="page-content" style="padding:1.5rem;">
