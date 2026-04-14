<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats & Leaderboard - Lexora</title>
    <link rel="stylesheet" href="../../common/styles/global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- ── Portals ── -->
    <section id="portals">
      <div class="gradient-magical" style="position:absolute;inset:0;pointer-events:none;"></div>
      <div class="section-inner" style="position:relative;z-index:1;">
        <div class="section-title-wrap">
          <h2 class="section-title reveal">
            Step Through the <span class="text-accent text-glow-teal">Portals</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-1">Each portal leads to a new genre universe waiting to be explored</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
          <div class="portal-card reveal reveal-delay-1" style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../../assets/img_20.jpeg" alt="Fantasy Peaks" style="width:100%;height:100%;object-fit:cover;" class="portal-img">
            </div>
            <div style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, transparent 100%);opacity:0.85;"></div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Fantasy Peaks</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Epic tales of magic and destiny</p>
            </div>
            <div class="portal-border" style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;pointer-events:none;"></div>
          </div>

          <div class="portal-card reveal reveal-delay-2" style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../../assets/img_21.jpeg" alt="Mystery Woods" style="width:100%;height:100%;object-fit:cover;" class="portal-img">
            </div>
            <div style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, transparent 100%);opacity:0.85;"></div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Mystery Woods</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Dark forests of suspense and wonder</p>
            </div>
            <div class="portal-border" style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;pointer-events:none;"></div>
          </div>

          <div class="portal-card reveal reveal-delay-3" style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../../assets/img_22.jpeg" alt="Scholar's Archive" style="width:100%;height:100%;object-fit:cover;" class="portal-img">
            </div>
            <div style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, transparent 100%);opacity:0.85;"></div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Scholar's Archive</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Ancient wisdom and discovery</p>
            </div>
            <div class="portal-border" style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;pointer-events:none;"></div>
          </div>

          <div class="portal-card reveal reveal-delay-4" style="position:relative;border-radius:1rem;overflow:hidden;cursor:pointer;">
            <div style="aspect-ratio:1;overflow:hidden;">
              <img src="../../assets/img_23.jpeg" alt="Traveler's Tavern" style="width:100%;height:100%;object-fit:cover;" class="portal-img">
            </div>
            <div style="position:absolute;inset:0;background:linear-gradient(to top, var(--background) 0%, transparent 100%);opacity:0.85;"></div>
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1.25rem;">
              <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:4px;">Traveler's Tavern</h3>
              <p style="font-size:0.875rem;color:var(--muted-foreground);">Where readers gather and share stories</p>
            </div>
            <div class="portal-border" style="position:absolute;inset:0;border-radius:1rem;border:1px solid transparent;pointer-events:none;"></div>
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
          <p class="section-subtitle reveal reveal-delay-1">Explore our growing world of readers, books, and adventures</p>
        </div>

        <div class="stats-grid">
          <div class="card stat-card reveal reveal-delay-1">
            <img src="../../assets/img_16.png" alt="" style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value text-primary">12,450+</div>
            <div class="stat-label">Books Available</div>
          </div>
          <div class="card stat-card reveal reveal-delay-2">
            <img src="../../assets/img_25.png" alt="" style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value text-accent">38,200+</div>
            <div class="stat-label">Active Readers</div>
          </div>
          <div class="card stat-card reveal reveal-delay-3">
            <img src="../../assets/img_25.png" alt="" style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
            <div class="stat-value" style="color:var(--glow-purple)">4,800+</div>
            <div class="stat-label">Children (6-12)</div>
          </div>
          <div class="card stat-card reveal reveal-delay-4">
            <img src="../../assets/img_25.png" alt="" style="width:2rem;height:2rem;object-fit:contain;margin:0 auto 0.75rem;display:block;">
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
            <div class="lb-reader"><span class="lb-avatar">🧝‍♀️</span><span class="lb-name">Luna Starweaver</span></div>
            <div class="lb-score"><span class="lb-score-val">98,750</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">342</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.85</span></div>
          </div>

          <div class="lb-row top3 reveal-x" style="transition-delay:80ms">
            <div class="lb-rank"><span class="lb-rank-icon" style="color:hsl(0,0%,60%)">🥈</span></div>
            <div class="lb-reader"><span class="lb-avatar">🧙‍♂️</span><span class="lb-name">Atlas Bookwright</span></div>
            <div class="lb-score"><span class="lb-score-val">87,200</span><span class="lb-score-label">pts</span></div>
            <div class="lb-books">298</div>
            <div class="lb-level"><span class="lb-level-badge">Lv.79</span></div>
          </div>

          <div class="lb-row top3 reveal-x" style="transition-delay:160ms">
            <div class="lb-rank"><span class="lb-rank-icon" style="color:hsl(25,70%,50%)">🥉</span></div>
            <div class="lb-reader"><span class="lb-avatar"> foxes 🦊</span><span class="lb-name">Ember Foxley</span></div>
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
          <!-- Rows truncated for space in component demo -->
        </div>
      </div>
    </section>

    <script src="../../common/scripts/global.js"></script>
</body>
</html>
