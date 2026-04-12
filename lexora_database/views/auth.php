<section style="max-width:520px;margin:0 auto;padding:2rem;background:#191613;border:1px solid #3f3a2f;border-radius:1rem;">
    <h1 style="font-size:2rem;margin-bottom:0.75rem;color:#f5edd6;">Se connecter / S'inscrire</h1>
    <?php if (!empty($message)): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:0.75rem;background:#311f0b;color:#fdd7a0;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($baseUrl) ?>?page=home&action=auth" style="display:grid;gap:1rem;">
        <input type="hidden" name="mode" id="authMode" value="signin">

        <div style="display:grid;gap:0.5rem;">
            <label style="color:#f5edd6;">Nom d'utilisateur</label>
            <input type="text" name="username" required style="padding:0.85rem;border-radius:0.75rem;border:1px solid #6b5b3d;background:#111;color:#f5edd6;">
        </div>

        <div style="display:grid;gap:0.5rem;">
            <label style="color:#f5edd6;">Mot de passe</label>
            <input type="password" name="password" required style="padding:0.85rem;border-radius:0.75rem;border:1px solid #6b5b3d;background:#111;color:#f5edd6;">
        </div>

        <div id="emailField" style="display:none;grid-gap:0.5rem;">
            <label style="color:#f5edd6;">Email</label>
            <input type="email" name="email" style="padding:0.85rem;border-radius:0.75rem;border:1px solid #6b5b3d;background:#111;color:#f5edd6;">
        </div>

        <button type="submit" style="padding:0.95rem;border:none;border-radius:0.75rem;background:#d6b56f;color:#111;font-weight:700;">Valider</button>
    </form>

    <p style="margin-top:1rem;color:#bdb1a0;">Vous n'avez pas de compte ? <button id="toggleAuth" style="background:none;border:none;color:#d6b56f;cursor:pointer;">Créer un compte</button></p>
</section>

<script>
    const toggleButton = document.getElementById('toggleAuth');
    const authModeInput = document.getElementById('authMode');
    const emailField = document.getElementById('emailField');
    let register = false;
    toggleButton.addEventListener('click', () => {
        register = !register;
        authModeInput.value = register ? 'register' : 'signin';
        emailField.style.display = register ? 'grid' : 'none';
        toggleButton.textContent = register ? 'Se connecter' : 'Créer un compte';
    });
</script>
