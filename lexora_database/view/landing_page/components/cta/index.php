<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTA Fragment - Lexora</title>
    <link rel="stylesheet" href="../../common/styles/global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section id="cta" class="cta-section">
      <div class="cta-glow">
        <div></div>
      </div>
      <div class="cta-inner">
        <img
          src="../../assets/img_28.png"
          alt="Lumo" class="cta-emoji reveal animate-float"
          style="width:96px;height:96px;object-fit:contain;margin:0 auto 1.5rem;display:block;">
        <h2 class="cta-title reveal">
          Your Story <span class="text-primary text-glow-gold">Begins Now</span>
        </h2>
        <p class="cta-desc reveal reveal-delay-1">
          Join thousands of readers in the Reading Kingdom. Sign up free, earn your first coins, and let Lumo guide you.
        </p>
        <div class="cta-btns reveal reveal-delay-2">
          <button class="btn btn-hero btn-xl" onclick="openAuthModal('signup')">✨ Create Your Account</button>
          <button class="btn btn-portal btn-lg" onclick="openAuthModal('login')">Log In</button>
        </div>
      </div>
    </section>

    <script src="../../common/scripts/global.js"></script>
</body>
</html>
