<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Fragment - Lexora</title>
    <link rel="stylesheet" href="view/landing/common/styles/global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="hero-section">
      <div class="hero-video-wrap">
        <div class="hero-fallback"></div>
        <video autoplay muted loop playsinline
          style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;" id="hero-video">
          <source src="assets/videos/hero-bg.mp4" type="video/mp4" />
        </video>
        <div class="hero-gradient-bottom"></div>
        <div class="hero-gradient-top"></div>
        <div class="hero-vignette"></div>
      </div>

      <div class="particles" id="hero-particles"></div>

      <div class="hero-content">
        <p class="hero-eyebrow">Open the Book &amp; Enter</p>
        <h1 class="hero-title">
          Reading
          <span>Adventure</span>
        </h1>
        <p class="hero-desc">
          The most fun and immersive way to read. Track your journey, earn rewards, and explore infinite worlds. ⋆˙⟡
        </p>
        <div class="hero-btn-wrap">
          <button class="btn btn-hero btn-xl" id="btn-get-started">✨ Get Started</button>
        </div>
      </div>

      <div class="hero-glow">
        <div class="hero-glow-inner"></div>
      </div>

      <div class="scroll-indicator">
        <div class="scroll-indicator-track">
          <div class="scroll-dot"></div>
        </div>
      </div>
    </section>

    <script src="view/landing/common/scripts/global.js"></script>
    <script src="script.js"></script>
</body>
</html>
