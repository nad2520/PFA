<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lexora — Reading Adventure</title>
  <meta name="description"
    content="The most fun and immersive way to read. Track your journey, earn rewards, and explore infinite worlds." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Quicksand:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="landing/common/styles/lexora-bundle.css">
  <link rel="stylesheet" href="landing/components/footer/style.css">
</head>

<body>

  <!-- ═══════════════════════════════════════
     SPLASH PAGE
═══════════════════════════════════════ -->
  <div id="splash-page">
    <div class="ambient"></div>

    <!-- Light orbs -->
    <div class="orb"
      style="top:15%;left:10%;width:128px;height:128px;background:hsl(38 75% 55% / 0.1);animation-delay:0s;"></div>
    <div class="orb"
      style="top:25%;right:15%;width:160px;height:160px;background:hsl(175 70% 45% / 0.1);filter:blur(90px);animation-delay:1s;">
    </div>
    <div class="orb"
      style="bottom:20%;left:20%;width:144px;height:144px;background:hsl(270 50% 50% / 0.1);filter:blur(70px);animation-delay:2s;">
    </div>
    <div class="orb"
      style="bottom:30%;right:10%;width:112px;height:112px;background:hsl(38 75% 55% / 0.08);filter:blur(60px);animation-delay:0.5s;">
    </div>

    <!-- Particles -->
    <div class="particles" id="splash-particles"></div>

    <!-- Splash image -->
    <div class="splash-image-wrap" id="splash-img">
      <img src="../assets/img_1.png" alt="Lexora — Enter the World of Reading Adventure"
        style="width:100%;height:auto;border-radius:1rem;box-shadow:0 30px 80px rgba(0,0,0,0.5);" />
      <div class="splash-glow-border"></div>
      <div class="splash-bottom-glow"></div>
    </div>

    <!-- CTA text -->
    <div class="splash-cta" id="splash-cta">
      <h2>Enter the World of Lexora</h2>
      <p>✦ Click anywhere to begin your adventure ✦</p>
    </div>

    <!-- Scanlines -->
    <div class="scanlines"></div>
  </div>


  <!-- ═══════════════════════════════════════
     MAIN PAGE
═══════════════════════════════════════ -->
  <div id="main-page">

    <!-- ── Navbar ── -->
    <nav class="navbar">
      <div class="navbar-inner">
        <div style="display:flex;align-items:center;gap:1rem;">
          <a href="#" class="navbar-logo">
            <img src="../assets/img_2.jpeg" alt="Lexora" class="navbar-logo-bear-img"
              style="width:36px;height:36px;border-radius:50%;border:2px solid hsl(38 75% 55% / 0.5);object-fit:cover;">
            <span class="navbar-logo-text">LEXORA</span>
          </a>
          <div class="navbar-coins">
            <img src="../assets/img_3.png" alt="coins" style="width:20px;height:20px;object-fit:contain;">
            <span>1,250</span>
          </div>
        </div>

        <div class="navbar-links">
          <a href="#explore" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
            </svg>
            Explore
          </a>
          <a href="#stats" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon points="3 11 22 2 13 21 11 13 3 11" />
            </svg>
            Map
          </a>
          <a href="#how-it-works" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
              <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
              <path d="M4 22h16" />
              <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
              <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
              <path d="M18 2H6v7a6 6 0 0 0 12 0V2z" />
            </svg>
            Quests
          </a>
          <a href="#stats" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Community
          </a>
        </div>

        <div class="navbar-auth">
          <button class="btn btn-ghost btn-sm" onclick="openAuthModal('login')">Log In</button>
          <button class="btn btn-hero btn-sm" onclick="openAuthModal('signup')">Sign Up ✦</button>
        </div>

        <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle menu">
          <svg id="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6" />
            <line x1="4" y1="12" x2="20" y2="12" />
            <line x1="4" y1="18" x2="20" y2="18" />
          </svg>
          <svg id="close-icon" style="display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
      </div>

      <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-menu-coins">
          <img src="../assets/img_3.png" alt="coins" style="width:20px;height:20px;object-fit:contain;"> 1,250
          Coins
        </div>
        <a href="#explore" class="navbar-link" onclick="closeMobileMenu()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
          </svg>
          Explore
        </a>
        <a href="#how-it-works" class="navbar-link" onclick="closeMobileMenu()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
            <path d="M4 22h16" />
            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2z" />
          </svg>
          Quests
        </a>
        <a href="#stats" class="navbar-link" onclick="closeMobileMenu()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
          Community
        </a>
        <div class="mobile-menu-auth">
          <button class="btn btn-ghost btn-sm" onclick="openAuthModal('login')">Log In</button>
          <button class="btn btn-hero btn-sm" onclick="openAuthModal('signup')">Sign Up ✦</button>
        </div>
      </div>
    </nav>

    <!-- ── Hero ── -->
    <section class="hero-section">
      <div class="hero-video-wrap">
        <div class="hero-fallback"></div>
        <video autoplay muted loop playsinline
          style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;" id="hero-video">
          <source src="../assets/hero-bg.mp4" type="video/mp4" />
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

    <!-- ── How It Works ── -->
    <section id="how-it-works">
      <div class="section-inner">
        <div class="section-title-wrap">
          <h2 class="section-title reveal">
            How <span class="text-primary text-glow-gold">Lexora</span> Works
          </h2>
          <p class="section-subtitle reveal reveal-delay-1">Your reading journey in three simple steps</p>
        </div>

        <div class="steps-grid">
          <div class="card step-card reveal reveal-delay-1">
            <div class="card-hover-overlay"></div>
            <div class="step-number">01</div>
            <div class="step-img-wrap">
              <img src="../assets/img_5.png" alt="Browse" class="step-img"
                style="width:64px;height:64px;object-fit:contain;">
            </div>
            <h3 class="step-title">Browse &amp; Discover</h3>
            <p class="step-desc">Explore our enchanted library of free and premium ebooks. Use AI-powered
              recommendations tailored to your reading history and mood.</p>
            <p class="step-detail">Free books can be borrowed instantly. Premium titles are available for purchase.</p>
          </div>

          <div class="card step-card reveal reveal-delay-2">
            <div class="card-hover-overlay"></div>
            <div class="step-number">02</div>
            <div class="step-img-wrap">
              <img src="../assets/img_6.png" alt="Buy" class="step-img"
                style="width:64px;height:64px;object-fit:contain;">
            </div>
            <h3 class="step-title">Borrow or Buy</h3>
            <p class="step-desc">Add books to your cart, review your selections, and checkout securely. Free books go
              straight to your library — no coins needed.</p>
            <p class="step-detail">Purchased books stay in your library forever. Track all transactions in your history.
            </p>
          </div>

          <div class="card step-card reveal reveal-delay-3">
            <div class="card-hover-overlay"></div>
            <div class="step-number">03</div>
            <div class="step-img-wrap">
              <img src="../assets/img_7.png" alt="Earn" class="step-img"
                style="width:64px;height:64px;object-fit:contain;">
            </div>
            <h3 class="step-title">Read &amp; Earn Coins</h3>
            <p class="step-desc">Every page you read earns you coins and XP. Complete quests, maintain your streak, and
              level up your reader profile.</p>
            <p class="step-detail">Use coins to unlock premium books, cosmetics for your avatar, and exclusive features.
            </p>
          </div>
        </div>

        <!-- Coin explainer -->
        <div class="coin-box reveal">
          <div class="coin-img-wrap">
            <img src="../assets/img_3.png" alt="Lexora Coin" style="width:96px;height:96px;object-fit:contain;"
              class="animate-float">
            <div class="coin-glow"></div>
          </div>
          <div>
            <h3>The Coin System</h3>
            <p>Coins are the currency of the Reading Kingdom. Earn them by reading pages, completing quests, and
              maintaining your daily streak. Spend them on premium books, avatar cosmetics, and exclusive features.</p>
            <div class="coin-badges">
              <span class="coin-badge">📖 Read 10 pages → <span class="amount">+20 coins</span></span>
              <span class="coin-badge">🔥 Daily streak → <span class="amount">+50 coins</span></span>
              <span class="coin-badge">⚔️ Quest complete → <span class="amount">+200 coins</span></span>
            </div>
          </div>
        </div>

        <!-- Lumo guide -->
        <div class="lumo-box reveal">
          <img src="../assets/img_9.png" alt="Lumo the Bear" style="width:112px;height:112px;object-fit:contain;"
            class="animate-float">
          <div>
            <h3>Meet Lumo, Your Guide</h3>
            <p>Lumo is your magical bear companion who tracks your reading progress. He celebrates your wins, nudges you
              when you've been away, and helps you discover your next favorite book. Keep reading to keep Lumo's lamp
              bright! 🔥</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Services ── -->
    <section id="explore">
      <div class="section-inner" style="position:relative;z-index:1;">
        <div class="section-title-wrap">
          <h2 class="section-title reveal">
            Discover Our <span class="text-accent text-glow-teal">Services</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-1">Everything you need for the ultimate reading adventure</p>
        </div>

        <div class="services-grid">
          <div class="card service-card reveal reveal-delay-1">
            <div class="card-hover-overlay"></div>
            <div class="service-header">
              <img src="../assets/img_10.png" alt="Discovery" style="width:56px;height:56px;object-fit:contain;">
              <span class="service-tag">AI-Powered</span>
            </div>
            <h3 class="service-title">Smart Book Discovery</h3>
            <p class="service-desc">Advanced search, thematic browsing, and AI-driven recommendations based on your
              behavior and preferences.</p>
          </div>

          <div class="card service-card reveal reveal-delay-2">
            <div class="card-hover-overlay"></div>
            <div class="service-header">
              <img src="../assets/img_11.png" alt="Reading" style="width:56px;height:56px;object-fit:contain;">
              <span class="service-tag">Flexible</span>
            </div>
            <h3 class="service-title">Free &amp; Premium Access</h3>
            <p class="service-desc">Borrow free ebooks instantly or purchase premium titles. Your library grows with
              every adventure.</p>
          </div>

          <div class="card service-card reveal reveal-delay-3">
            <div class="card-hover-overlay"></div>
            <div class="service-header">
              <img src="../assets/img_12.png" alt="Cart" style="width:56px;height:56px;object-fit:contain;">
              <span class="service-tag">Seamless</span>
            </div>
            <h3 class="service-title">Cart &amp; Checkout</h3>
            <p class="service-desc">Add books to cart, review price and quantity, and check out securely. Access
              purchased books permanently.</p>
          </div>

          <div class="card service-card reveal reveal-delay-1">
            <div class="card-hover-overlay"></div>
            <div class="service-header">
              <img src="../assets/img_13.png" alt="AI" style="width:56px;height:56px;object-fit:contain;">
              <span class="service-tag">Smart</span>
            </div>
            <h3 class="service-title">AI Recommendations</h3>
            <p class="service-desc">Get personalized suggestions based on browsing history, genres, and previously read
              books — both free and paid.</p>
          </div>

          <div class="card service-card reveal reveal-delay-2">
            <div class="card-hover-overlay"></div>
            <div class="service-header">
              <img src="../assets/img_14.png" alt="Mood" style="width:56px;height:56px;object-fit:contain;">
              <span class="service-tag">Personal</span>
            </div>
            <h3 class="service-title">Reading Moods</h3>
            <p class="service-desc">Tell us how you feel and get book suggestions that match your mood. Budget-aware
              picks included.</p>
          </div>

          <div class="card service-card reveal reveal-delay-3">
            <div class="card-hover-overlay"></div>
            <div class="service-header">
              <img src="../assets/img_15.png" alt="Gamification" style="width:56px;height:56px;object-fit:contain;">
              <span class="service-tag">Rewarding</span>
            </div>
            <h3 class="service-title">Gamified Progression</h3>
            <p class="service-desc">Earn XP and coins as you read. Complete quests, unlock achievements, and watch your
              level grow.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Features ── -->
    <section id="features">
      <div class="section-inner">
        <div class="section-title-wrap">
          <h2 class="section-title reveal">
            Your Adventure <span class="text-primary text-glow-gold">Awaits</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-1">Four pillars power the Lexora experience</p>
        </div>

        <div class="features-grid">
          <div class="card feature-card reveal reveal-delay-1">
            <div class="card-hover-overlay"></div>
            <div class="feature-icon-wrap"><img src="../assets/img_16.png" alt="Library"
                style="width:64px;height:64px;object-fit:contain;"></div>
            <h3 class="feature-title">Enchanted Library</h3>
            <p class="feature-desc">Discover thousands of books across every genre. Your personal library grows as you
              read — watch it come alive.</p>
          </div>

          <div class="card feature-card reveal reveal-delay-2">
            <div class="card-hover-overlay"></div>
            <div class="feature-icon-wrap"><img src="../assets/img_17.png" alt="Quests"
                style="width:64px;height:64px;object-fit:contain;"></div>
            <h3 class="feature-title">Quests &amp; Rewards</h3>
            <p class="feature-desc">Complete reading quests to earn coins and XP. Level up your avatar, unlock
              achievements, and maintain your streak.</p>
          </div>

          <div class="card feature-card reveal reveal-delay-3">
            <div class="card-hover-overlay"></div>
            <div class="feature-icon-wrap"><img src="../assets/img_18.png" alt="Map"
                style="width:64px;height:64px;object-fit:contain;"></div>
            <h3 class="feature-title">Reading Kingdom Map</h3>
            <p class="feature-desc">Explore genre regions on an interactive fantasy map. Each territory unlocks as you
              venture deeper into new stories.</p>
          </div>

          <div class="card feature-card reveal reveal-delay-4">
            <div class="card-hover-overlay"></div>
            <div class="feature-icon-wrap"><img src="../assets/img_19.png" alt="Guild"
                style="width:64px;height:64px;object-fit:contain;"></div>
            <h3 class="feature-title">Guild Community</h3>
            <p class="feature-desc">Join book guilds, share reviews, and discuss stories with fellow adventurers.
              Reading is better together.</p>
          </div>
        </div>
      </div>
    </section>


    <!-- ── Portals ── -->
    <section id="portals" style="position:relative;padding:8rem 1.5rem;">
      <div
        style="position:absolute;inset:0;background:radial-gradient(ellipse at center, hsl(42 90% 60% / 0.12) 0%, transparent 70%);pointer-events:none;">
      </div>
      <div class="section-inner" style="position:relative;z-index:1;">
        <div class="section-title-wrap">
          <h2 class="section-title reveal">
            Step Through the <span class="text-accent text-glow-teal">Portals</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-1">Each portal leads to a new genre universe waiting to be
            explored</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">

          <div class="portal-card reveal reveal-delay-1"
            style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;transition:transform 0.3s;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../assets/img_20.jpeg" alt="Fantasy Peaks"
                style="width:100%;height:100%;object-fit:cover;transition:transform 0.7s;" class="portal-img"
                loading="lazy">
            </div>
            <div
              style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, hsl(24 15% 10% / 0.3) 50%, transparent 100%);opacity:0.85;transition:opacity 0.3s;">
            </div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Fantasy
                Peaks</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Epic tales of magic and destiny</p>
            </div>
            <div
              style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;transition:border-color 0.3s;pointer-events:none;"
              class="portal-border"></div>
          </div>
          <div class="portal-card reveal reveal-delay-2"
            style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;transition:transform 0.3s;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../assets/img_21.jpeg" alt="Mystery Woods"
                style="width:100%;height:100%;object-fit:cover;transition:transform 0.7s;" class="portal-img"
                loading="lazy">
            </div>
            <div
              style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, hsl(24 15% 10% / 0.3) 50%, transparent 100%);opacity:0.85;transition:opacity 0.3s;">
            </div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Mystery
                Woods</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Dark forests of suspense and wonder</p>
            </div>
            <div
              style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;transition:border-color 0.3s;pointer-events:none;"
              class="portal-border"></div>
          </div>
          <div class="portal-card reveal reveal-delay-3"
            style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;transition:transform 0.3s;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../assets/img_22.jpeg" alt="Scholar's Archive"
                style="width:100%;height:100%;object-fit:cover;transition:transform 0.7s;" class="portal-img"
                loading="lazy">
            </div>
            <div
              style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, hsl(24 15% 10% / 0.3) 50%, transparent 100%);opacity:0.85;transition:opacity 0.3s;">
            </div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Scholar's
                Archive</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Ancient wisdom and discovery</p>
            </div>
            <div
              style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;transition:border-color 0.3s;pointer-events:none;"
              class="portal-border"></div>
          </div>
          <div class="portal-card reveal reveal-delay-4"
            style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;transition:transform 0.3s;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../assets/img_23.jpeg" alt="Traveler's Tavern"
                style="width:100%;height:100%;object-fit:cover;transition:transform 0.7s;" class="portal-img"
                loading="lazy">
            </div>
            <div
              style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, hsl(24 15% 10% / 0.3) 50%, transparent 100%);opacity:0.85;transition:opacity 0.3s;">
            </div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Traveler's
                Tavern</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Where readers gather and share stories</p>
            </div>
            <div
              style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;transition:border-color 0.3s;pointer-events:none;"
              class="portal-border"></div>
          </div>
        </div>
      </div>
    </section>
    <!-- ── Stats & Leaderboard ── -->
    <section id="stats">
      <div class="section-inner" style="position:relative;z-index:1;">
        <div class="section-title-wrap">
          <h2 class="section-title reveal">
            The Reading <span class="text-primary text-glow-gold">Kingdom</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-1">Explore our growing world of readers, books, and adventures
          </p>
        </div>

        <!-- Stats grid -->
        <div class="stats-grid">
          <div class="card stat-card reveal reveal-delay-1">
            <img src="../assets/img_16.png" alt=""
              style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value text-primary">12,450+</div>
            <div class="stat-label">Books Available</div>
          </div>
          <div class="card stat-card reveal reveal-delay-2">
            <img src="../assets/img_25.png" alt=""
              style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value text-accent">38,200+</div>
            <div class="stat-label">Active Readers</div>
          </div>
          <div class="card stat-card reveal reveal-delay-3">
            <img src="../assets/img_25.png" alt=""
              style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value" style="color:var(--glow-purple)">4,800+</div>
            <div class="stat-label">Children (6-12)</div>
          </div>
          <div class="card stat-card reveal reveal-delay-4">
            <img src="../assets/img_25.png" alt=""
              style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value text-accent">33,400+</div>
            <div class="stat-label">Adults (13+)</div>
          </div>
        </div>

        <!-- Age categories -->
        <h3 class="section-title reveal" style="font-size:1.75rem;margin-bottom:2rem;text-align:center;">
          Categories by <span class="text-accent text-glow-teal">Age</span>
        </h3>
        <div class="age-grid">
          <div class="card age-card reveal reveal-delay-1">
            <div class="age-emoji">📚</div>
            <div class="age-name">Children (6-12 ans)</div>
            <div class="age-count">4,200 books</div>
            <div class="age-badges">
              <span class="age-badge">Age: 6-12</span>
              <span class="age-badge">Parental consent required</span>
            </div>
          </div>
          <div class="card age-card reveal reveal-delay-2">
            <div class="age-emoji">🎮</div>
            <div class="age-name">Teens (13-17 ans)</div>
            <div class="age-count">3,650 books</div>
            <div class="age-badges">
              <span class="age-badge">Age: 13-17</span>
              <span class="age-badge">Age verification</span>
            </div>
          </div>
          <div class="card age-card reveal reveal-delay-3">
            <div class="age-emoji">📖</div>
            <div class="age-name">Adults (18+)</div>
            <div class="age-count">4,600 books</div>
            <div class="age-badges">
              <span class="age-badge">Age: 18+</span>
              <span class="age-badge">ID verification</span>
            </div>
          </div>
        </div>

        <!-- Leaderboard -->
        <h3 class="section-title reveal" style="font-size:1.75rem;margin-bottom:2rem;text-align:center;">
          Top <span class="text-primary text-glow-gold">Readers</span> Leaderboard
        </h3>
        <div class="leaderboard-wrap reveal">
          <div class="lb-header">
            <span>Rank</span>
            <span>Reader</span>
            <span style="text-align:right;">Score</span>
            <span class="lb-books-col" style="text-align:right;">Books</span>
            <span style="text-align:right;">Level</span>
          </div>

          <div class="lb-row top3 reveal-x">
            <div class="lb-rank"><span class="lb-rank-icon" style="color:var(--primary)">👑</span></div>
            <div class="lb-reader"><span class="lb-avatar">🧝‍♀️</span><span class="lb-name">Luna Starweaver</span>
            </div>
            <div class="lb-score"><span class="lb-score-val">98,750</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">342</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.85</span></div>
          </div>

          <div class="lb-row top3 reveal-x" style="transition-delay:80ms">
            <div class="lb-rank"><span class="lb-rank-icon" style="color:hsl(0,0%,60%)">🥈</span></div>
            <div class="lb-reader"><span class="lb-avatar">🧙‍♂️</span><span class="lb-name">Atlas Bookwright</span>
            </div>
            <div class="lb-score"><span class="lb-score-val">87,200</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">298</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.79</span></div>
          </div>

          <div class="lb-row top3 reveal-x" style="transition-delay:160ms">
            <div class="lb-rank"><span class="lb-rank-icon" style="color:hsl(25,70%,50%)">🥉</span></div>
            <div class="lb-reader"><span class="lb-avatar">🦊</span><span class="lb-name">Ember Foxley</span></div>
            <div class="lb-score"><span class="lb-score-val">76,430</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">267</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.72</span></div>
          </div>

          <div class="lb-row reveal-x" style="transition-delay:240ms">
            <div class="lb-rank"><span class="lb-rank-num">#4</span></div>
            <div class="lb-reader"><span class="lb-avatar">⭐</span><span class="lb-name">Nova Pagebinder</span></div>
            <div class="lb-score"><span class="lb-score-val">65,100</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">231</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.65</span></div>
          </div>

          <div class="lb-row reveal-x" style="transition-delay:320ms">
            <div class="lb-rank"><span class="lb-rank-num">#5</span></div>
            <div class="lb-reader"><span class="lb-avatar">🌿</span><span class="lb-name">Sage Willowmere</span></div>
            <div class="lb-score"><span class="lb-score-val">58,800</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">204</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.60</span></div>
          </div>

          <div class="lb-row reveal-x" style="transition-delay:400ms">
            <div class="lb-rank"><span class="lb-rank-num">#6</span></div>
            <div class="lb-reader"><span class="lb-avatar">✨</span><span class="lb-name">Cleo Brightword</span></div>
            <div class="lb-score"><span class="lb-score-val">52,340</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">189</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.56</span></div>
          </div>

          <div class="lb-row reveal-x" style="transition-delay:480ms">
            <div class="lb-rank"><span class="lb-rank-num">#7</span></div>
            <div class="lb-reader"><span class="lb-avatar">🐱</span><span class="lb-name">Felix Inkwell</span></div>
            <div class="lb-score"><span class="lb-score-val">47,200</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">172</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.51</span></div>
          </div>

          <div class="lb-row reveal-x" style="transition-delay:560ms">
            <div class="lb-rank"><span class="lb-rank-num">#8</span></div>
            <div class="lb-reader"><span class="lb-avatar">🌸</span><span class="lb-name">Iris Storyhelm</span></div>
            <div class="lb-score"><span class="lb-score-val">43,900</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">158</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.48</span></div>
          </div>
        </div>
      </div>
    </section>



    <!-- ── CTA ── -->
    <section id="cta" class="cta-section">
      <div class="cta-glow">
        <div></div>
      </div>
      <div class="cta-inner">
        <img src="../assets/img_28.png" alt="Lumo" class="cta-emoji reveal animate-float"
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


    <!-- ── video footer ── -->
    <section class="vf-section">
      <video id="footer-vid" autoplay muted loop playsinline>
        <source src="../assets/lexora-intro.mp4" type="video/mp4">
      </video>

      <div class="vf-bar" id="vf-bar">

        <!-- Play/Pause -->
        <button class="vf-btn" id="vf-play" aria-label="Play/Pause" title="Play / Pause">
          <svg id="vf-play-svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
            <path id="vf-play-path" d="M8 5v14l11-7z" />
          </svg>
        </button>

        <!-- Current time -->
        <span class="vf-time" id="vf-cur">0:00</span>

        <!-- Seek bar -->
        <div class="vf-seek-wrap">
          <div class="vf-track">
            <div class="vf-fill" id="vf-fill"></div>
          </div>
          <input class="vf-seek" id="vf-seek" type="range" min="0" max="100" value="0" step="0.1" aria-label="Seek">
        </div>

        <!-- Duration -->
        <span class="vf-time" id="vf-dur">0:00</span>

        <!-- Mute / Volume -->
        <button class="vf-btn" id="vf-mute" aria-label="Mute/Unmute" title="Mute / Unmute">
          <svg id="vf-vol-svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
            <path id="vf-vol-path"
              d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
          </svg>
        </button>
        <input class="vf-vol" id="vf-vol" type="range" min="0" max="1" step="0.01" value="0" aria-label="Volume">

        <!-- Fullscreen -->
        <button class="vf-btn" id="vf-fs" aria-label="Fullscreen" title="Fullscreen">
          <svg id="vf-fs-svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
            <path id="vf-fs-path" d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z" />
          </svg>
        </button>

      </div>
    </section>

    <!-- footer -->

    <footer>
      <div class="footer-inner">
        <div class="footer-top">
          <div class="footer-logo">
            <img
              src="data:image/jpeg;base64,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"
              alt="Lexora" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
            <span class="footer-logo-text">Lexora</span>
          </div>
          <p class="footer-tagline">Made with <span class="text-primary">✦</span> for readers everywhere</p>
        </div>

        <div class="footer-links-grid">
          <div class="footer-col">
            <h4>Company</h4>
            <ul>
              <li><a href="#">About</a></li>
              <li><a href="#">Blog</a></li>
              <li><a href="#">Shop</a></li>
              <li><a href="#">Community</a></li>
              <li><a href="#">Help Center</a></li>
              <li><a href="#">Pricing</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Discover</h4>
            <ul>
              <li><a href="#">New Releases</a></li>
              <li><a href="#">Best Sellers</a></li>
              <li><a href="#">Reading Quests</a></li>
              <li><a href="#">Daily Challenge</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Categories</h4>
            <ul>
              <li><a href="#">All Books</a></li>
              <li><a href="#">Fantasy</a></li>
              <li><a href="#">Adventure</a></li>
              <li><a href="#">Mystery</a></li>
              <li><a href="#">Science Fiction</a></li>
              <li><a href="#">History</a></li>
              <li><a href="#">Biography</a></li>
              <li><a href="#">Poetry</a></li>
              <li><a href="#">Comics</a></li>
              <li><a href="#">Educational</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>&nbsp;</h4>
            <ul>
              <li><a href="#">Children (6-12)</a></li>
              <li><a href="#">Teens (13-17)</a></li>
              <li><a href="#">Young Adults</a></li>
              <li><a href="#">Adults</a></li>
              <li><a href="#">Non-Fiction</a></li>
              <li><a href="#">Romance</a></li>
              <li><a href="#">Horror</a></li>
              <li><a href="#">Self-Help</a></li>
              <li><a href="#">Classics</a></li>
              <li><a href="#">Philosophy</a></li>
            </ul>
          </div>
        </div>

        <div class="footer-bottom">
          <div class="footer-legal">
            <span>© 2026 Lexora, Inc.</span>
            <a href="#">Terms</a>
            <a href="#">Privacy Policy</a>
          </div>
          <div class="footer-social">
            <a href="#" class="social-btn" aria-label="Facebook">f</a>
            <a href="#" class="social-btn" aria-label="Instagram">📷</a>
            <a href="#" class="social-btn" aria-label="X">𝕏</a>
            <a href="#" class="social-btn" aria-label="Email">✉</a>
          </div>
        </div>
      </div>
    </footer>

  </div>






  <!-- ═══ Get Started Video Modal ═══════════════════════════════════════════ -->
  <div class="gs-modal" id="gs-modal" role="dialog" aria-modal="true" aria-label="Lexora Introduction Video">
    <div class="gs-modal-backdrop" id="gs-modal-backdrop"></div>
    <div class="gs-modal-container">

      <!-- Close button -->
      <button class="gs-modal-close" id="gs-modal-close" aria-label="Close video">
        <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
          <path
            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
        </svg>
      </button>

      <!-- Video wrapper -->
      <div class="gs-video-wrap">
        <video id="gs-vid" playsinline>
          <source src="../assets/lexora-intro.mp4" type="video/mp4">
        </video>

        <!-- Control bar -->
        <div class="vf-bar" id="gs-bar">

          <button class="vf-btn" id="gs-play" aria-label="Play/Pause">
            <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
              <path id="gs-play-path" d="M8 5v14l11-7z" />
            </svg>
          </button>

          <span class="vf-time" id="gs-cur">0:00</span>

          <div class="vf-seek-wrap">
            <div class="vf-track">
              <div class="vf-fill" id="gs-fill"></div>
            </div>
            <input class="vf-seek" id="gs-seek" type="range" min="0" max="100" value="0" step="0.1" aria-label="Seek">
          </div>

          <span class="vf-time" id="gs-dur">0:00</span>

          <button class="vf-btn" id="gs-mute" aria-label="Mute/Unmute">
            <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
              <path id="gs-vol-path"
                d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
            </svg>
          </button>
          <input class="vf-vol" id="gs-vol" type="range" min="0" max="1" step="0.01" value="0.7" aria-label="Volume">

          <button class="vf-btn" id="gs-fs" aria-label="Fullscreen">
            <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
              <path id="gs-fs-path"
                d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z" />
            </svg>
          </button>

        </div>
      </div>

    </div>
  </div>



  <!-- Authentication Modal -->
  <div id="auth-modal" class="auth-overlay">
    <!-- Particles -->
    <div class="particles-container" id="auth-particles"></div>

    <div class="auth-modal-content">
      <!-- Close Button -->
      <button class="modal-close-btn" onclick="closeAuthModal()" aria-label="Close Modal">
        <i data-lucide="x"></i>
      </button>

      <!-- Auth Card -->
      <div class="auth-card">
        <!-- Tab Switcher -->
        <div class="tab-switcher">
          <button id="tab-login" class="tab-btn active">Log In</button>
          <button id="tab-signup" class="tab-btn inactive">Sign Up</button>
        </div>

        <!-- Title -->
        <div class="auth-header">
          <h1 id="auth-title" class="auth-title text-gold-gradient">Welcome Back, Adventurer</h1>
          <p id="auth-subtitle" class="auth-subtitle">Enter the library and continue your quest</p>
        </div>

        <!-- Form -->
        <form id="auth-form" class="auth-form" method="POST" action="../controller/auth_controller.php">
          <input type="hidden" name="action" id="auth-action" value="login" />
          <div id="username-field" class="form-group hidden">
            <i data-lucide="user" class="input-icon"></i>
            <input type="text" id="username" name="username" placeholder="Choose your adventurer name" class="form-input" />
          </div>

          <div class="form-group">
            <i data-lucide="mail" class="input-icon"></i>
            <input type="email" id="email" name="email" placeholder="Your email address" class="form-input" required />
          </div>

          <div id="birthdate-field" class="form-group hidden">
            <i data-lucide="calendar" class="input-icon"></i>
            <input type="date" id="birthdate" name="birthdate" class="form-input" />
          </div>

          <div class="form-group">
            <i data-lucide="lock" class="input-icon"></i>
            <input type="password" id="password" name="password" placeholder="Your secret passphrase" class="form-input password-input"
              required />
            <button type="button" id="toggle-password" class="toggle-password-btn">
              <i data-lucide="eye" id="eye-icon" class="icon-sm"></i>
            </button>
          </div>

          <div id="forgot-password-link" class="forgot-password-wrapper flex">
            <button type="button" class="forgot-password-link">Forgot your passphrase?</button>
          </div>

          <?php if (isset($_SESSION['auth_error'])): ?>
            <div id="auth-message" class="auth-message error-text"><?= $_SESSION['auth_error'] ?></div>
            <?php unset($_SESSION['auth_error']); ?>
          <?php elseif (isset($_SESSION['auth_success'])): ?>
            <div id="auth-message" class="auth-message success-text"><?= $_SESSION['auth_success'] ?></div>
            <?php unset($_SESSION['auth_success']); ?>
          <?php else: ?>
            <div id="auth-message" class="auth-message hidden"></div>
          <?php endif; ?>

          <!-- Submit Button -->
          <button type="submit" class="btn-auth btn-auth-primary btn-auth-full">
            <i data-lucide="sparkles" class="icon-sm"></i>
            <span id="submit-text">Enter the Library</span>
          </button>
        </form>

        <!-- Divider -->
        <div class="divider">
          <div class="divider-line"></div>
          <span class="divider-text">OR</span>
          <div class="divider-line"></div>
        </div>

        <!-- Social Auth -->
        <button class="btn-auth btn-auth-secondary btn-auth-full">
          <i data-lucide="book-open" class="icon-sm"></i>
          Continue with Google
        </button>
      </div>

      <!-- Footer -->
      <p class="auth-footer">
        By continuing, you agree to our
        <span class="footer-link">Terms of Quest</span>
        and
        <span class="footer-link">Privacy Scroll</span>
      </p>
    </div>
  </div>
  <!-- Modular Scripts -->
  <script src="landing/components/splash/script.js"></script>
  <script src="landing/components/navbar/script.js"></script>
  <script src="landing/components/hero/script.js"></script>
  <script src="landing/components/video-footer/script.js"></script>
  <script src="landing/components/get-started-modal/script.js"></script>
  <script src="landing/components/auth-modal/script.js"></script>
  <script src="landing/components/footer/script.js"></script>
  <script src="landing/common/scripts/global.js"></script>
</body>

</html>