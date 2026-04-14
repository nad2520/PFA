// ────────────────────────────────────────────────────────────────────────────
//  app.js  — Lexora Template: all interactive logic
// ────────────────────────────────────────────────────────────────────────────

// ─── Inline SVG Icons ────────────────────────────────────────────────────────
const SVG = {
    coins: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>`,
    star: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
    bookOpen: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
    search: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>`,
    arrowLeft: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>`,
    arrowUp: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>`,
    clock: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
    trending: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>`,
    msgSquare: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>`,
    send: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>`,
    x: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
    logout: `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>`,
    creditCard: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>`,
    shield: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>`,
    flame: `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/></svg>`,
    scroll: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>`,
    zap: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>`,
    keyRound: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>`,
    login: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>`,
    userPlus: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>`,
    bookOpenLg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
    coinsLg: `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>`,
    bookColl: `<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
    refresh: `<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>`,
    users: `<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
    sparkles: `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>`,
    crown: `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>`,
    gem: `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="6 3 18 3 22 9 12 22 2 9"/><polyline points="2 9 12 14 22 9"/><line x1="12" y1="22" x2="12" y2="14"/><line x1="12" y1="14" x2="6" y2="3"/><line x1="12" y1="14" x2="18" y2="3"/></svg>`,
    map: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>`,
};

// ─── Helper: make genre tag HTML ─────────────────────────────────────────────
function genreTagHTML(genre) {
    const c = genreColors[genre] || { css: 'background:var(--secondary);color:var(--secondary-foreground)' };
    return `<span class="genre-tag" style="${c.css}">${genre}</span>`;
}

// ─── Navigate helper ─────────────────────────────────────────────────────────
function nav(url) { window.location.href = url; }

// ═══════════════════════════════════════════════════════════════════════════════
//  GLOBAL HEADER
// ═══════════════════════════════════════════════════════════════════════════════
function initGlobalHeader() {
    const slider = document.getElementById('lampSlider');
    const hourLabel = document.getElementById('lampHours');
    const lampDot = document.getElementById('lampDot');
    const statusEl = document.getElementById('lampStatus');
    const lumoThumb = document.getElementById('lumoThumb');
    const coinEl = document.getElementById('coinCount');

    if (!slider) return;

    let coins = mockUser.coins;
    let penaltyApplied = false;

    function update() {
        const h = +slider.value;
        if (hourLabel) hourLabel.textContent = h + 'h';

        // lamp state
        let state, opacity;
        if (h < 18) { state = 'bright'; opacity = 1; }
        else if (h < 22) { state = 'fading'; opacity = 1 - ((h - 18) / 4) * 0.6; }
        else if (h < 24) { state = 'flickering'; opacity = 0.3; }
        else { state = 'dark'; opacity = 0.05; }

        if (lampDot) {
            lampDot.style.opacity = opacity;
            lampDot.style.boxShadow = `0 0 ${opacity * 14}px hsl(38 90% 60% / ${opacity})`;
            lampDot.textContent = state === 'dark' ? '💀' : '🔥';
            lampDot.classList.remove('animate-lamp-glow', 'animate-lamp-flicker');
            if (state === 'bright') lampDot.classList.add('animate-lamp-glow');
            if (state === 'flickering') lampDot.classList.add('animate-lamp-flicker');
        }

        const isWorried = h >= 18;
        const level = mockUser.level;
        const penPct = level <= 5 ? 10 : level <= 15 ? 30 : level <= 25 ? 50 : 70;

        // penalty
        if (state === 'dark' && !penaltyApplied) {
            coins = Math.max(0, coins - Math.floor(coins * penPct / 100));
            penaltyApplied = true;
        }
        if (state !== 'dark' && penaltyApplied) penaltyApplied = false;

        if (coinEl) coinEl.textContent = coins.toLocaleString();

        if (statusEl) {
            if (state === 'dark') {
                statusEl.innerHTML = `<div class="status-lost">${SVG.coins} -${penPct}% COINS LOST!</div>`;
            } else if (isWorried) {
                statusEl.innerHTML = `<p class="status-warn" style="font-family:'Press Start 2P';font-size:.45rem;letter-spacing:.05em">"Read before I sleep or lose your coins!"</p>`;
            } else {
                statusEl.innerHTML = `<p class="status-lit" style="font-family:'Press Start 2P';font-size:.45rem;letter-spacing:.05em">🪔 LAMP LIT — Keep reading!</p>`;
            }
        }

        if (lumoThumb) {
            lumoThumb.src = isWorried ? 'assets/lumo-worried.png' : 'assets/lumo-happy.png';
        }
        const avatarBtn = document.getElementById('avatarImg');
        if (avatarBtn) avatarBtn.src = isWorried ? 'assets/lumo-worried.png' : 'assets/lumo-happy.png';
    }

    slider.addEventListener('input', update);
    update();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  LUMO CHATBOT
// ═══════════════════════════════════════════════════════════════════════════════
const LUMO_RESPONSES = {
    hello: "Hey there, fellow reader! 📚 I'm Lumo, your cozy reading companion. How can I help you today?",
    coins: "Coins are the currency of the Reading Kingdom! You earn them by finishing books and completing daily reading goals. 🪙",
    books: "Looking for a great read? Check out the Book Catalog on the home page! Filter by genre — Fantasy, Horror, Mystery, Romance, and more. ✨",
    progress: "You can track your reading progress on your Profile page! Keep reading to level up! 📖",
    map: "The Reading Kingdom Map is your personalized fantasy world! Each region represents a genre you love. 🗺️",
    recommend: "I'd recommend 'The Shadow's Edge' for epic fantasy, or 'Moonlit Promises' for romance! Both are trending. 🌟",
    store: "The Store is where you can spend hard-earned coins on exclusive items and book bundles. 🏪",
    help: "I can help you with:\n• 📚 Book recommendations\n• 🪙 How coins & XP work\n• 📖 Reading progress\n• 🗺️ The Reading Kingdom Map\n• 🏪 The Store\n\nJust ask me anything!",
    default: "That's a wonderful question! Ask me about coins, books, the map, or recommendations! 🐻",
};

function getLumoReply(input) {
    const l = input.toLowerCase();
    if (/hello|hi|hey|greet/.test(l)) return LUMO_RESPONSES.hello;
    if (/coin|money|currency|cost|price/.test(l)) return LUMO_RESPONSES.coins;
    if (/book|read|catalog|genre/.test(l)) return LUMO_RESPONSES.books;
    if (/progress|level|xp|streak/.test(l)) return LUMO_RESPONSES.progress;
    if (/map|kingdom|region|world/.test(l)) return LUMO_RESPONSES.map;
    if (/recommend|suggest|pick/.test(l)) return LUMO_RESPONSES.recommend;
    if (/store|shop|buy|purchase/.test(l)) return LUMO_RESPONSES.store;
    if (/help|what can you|feature/.test(l)) return LUMO_RESPONSES.help;
    return LUMO_RESPONSES.default;
}

function initChatbot() {
    const fab = document.getElementById('lumo-fab');
    const chat = document.getElementById('lumo-chat');
    const messages = document.getElementById('chatMessages');
    const input = document.getElementById('chatInput');
    const sendBtn = document.getElementById('chatSend');
    const closeBtn = document.getElementById('chatClose');

    if (!fab) return;

    function openChat() { fab.style.display = 'none'; chat.classList.remove('hidden'); }
    function closeChat() { chat.classList.add('hidden'); fab.style.display = ''; }

    fab.querySelector('button').addEventListener('click', openChat);
    if (closeBtn) closeBtn.addEventListener('click', closeChat);

    function appendMsg(role, text) {
        const isUser = role === 'user';
        const div = document.createElement('div');
        div.className = `msg ${isUser ? 'user-msg' : 'lumo-msg'}`;
        if (!isUser) div.innerHTML = `<img src="assets/lumo-happy.png" alt="">`;
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        bubble.textContent = text;
        div.appendChild(bubble);
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function handleSend() {
        const val = input.value.trim();
        if (!val) return;
        appendMsg('user', val);
        input.value = '';
        sendBtn.disabled = true;
        setTimeout(() => {
            appendMsg('lumo', getLumoReply(val));
            sendBtn.disabled = false;
        }, 600);
    }

    sendBtn.addEventListener('click', handleSend);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') handleSend(); });
    input.addEventListener('input', () => { sendBtn.disabled = !input.value.trim(); });
}

// ═══════════════════════════════════════════════════════════════════════════════
//  BOOK CATALOG  (index.html)
// ═══════════════════════════════════════════════════════════════════════════════
function buildBookCardHTML(book, index, flip = true) {
    const cover = genreCovers[book.genre];
    const price = bookPrices[book.id];
    const tag = genreTagHTML(book.genre);
    const priceStr = price ? price.cost.toLocaleString() + ' COINS' : 'FREE';
    const rewardStr = `+${price?.xpReward ?? 50} XP & +${price?.coinReward ?? 100} COINS`;

    if (flip) {
        return `
    <div class="book-card-container animate-float-up" style="animation-delay:${index * 0.05}s" onclick="nav('book-detail.html?id=${book.id}')">
      <div class="book-card-inner" style="min-height:320px">
        <div class="book-card-front">
          <div class="cover-wrap">
            <img src="${cover}" alt="${book.title}" loading="lazy">
            <div class="cover-fade"></div>
            ${book.trending ? `<span class="hot-badge">★ HOT</span>` : ''}
          </div>
          <div class="card-body">
            <h3 class="line-clamp-1">${book.title}</h3>
            <p class="line-clamp-1">${book.author}</p>
            ${tag}
          </div>
        </div>
        <div class="book-card-back">
          <h3>${book.title}</h3>
          <p>${book.author}</p>
          <div class="divider"></div>
          <div class="reward-row">${SVG.coins}<span>${priceStr}</span></div>
          <div class="reward-row">${SVG.star}<span>${rewardStr}</span></div>
          <button class="btn-pixel">ADD TO LIST</button>
        </div>
      </div>
    </div>`;
    } else {
        // Static card (profile/library)
        return `
    <div class="book-card-static">
      <div class="cover-wrap">
        <img src="${cover}" alt="${book.title}" loading="lazy">
        <div class="cover-fade"></div>
      </div>
      <div class="card-body">
        <h3 class="line-clamp-1">${book.title}</h3>
        <p class="line-clamp-1">${book.author}</p>
        ${tag}
      </div>
    </div>`;
    }
}

function initCatalog() {
    const grid = document.getElementById('bookGrid');
    const forYouSection = document.getElementById('forYouSection');
    const forYouGrid = document.getElementById('forYouGrid');
    const searchInput = document.getElementById('bookSearch');
    const filterRow = document.getElementById('filterRow');
    const exploreBtn = document.getElementById('exploreMore');
    const divider = document.getElementById('catalogDivider');

    if (!grid) return;

    const ITEMS = 8;
    let activeFilter = 'trending';
    let searchQuery = '';
    let visibleCount = ITEMS;

    // Build filter tabs
    if (filterRow) {
        const allFilters = ['trending', ...genres];
        filterRow.innerHTML = allFilters.map(f =>
            `<button data-filter="${f}" class="${f === activeFilter ? 'active' : ''}">${f === 'trending' ? '🔥 Trending' : f}</button>`
        ).join('');
        filterRow.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                activeFilter = btn.dataset.filter;
                visibleCount = ITEMS;
                filterRow.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                render();
            });
        });
    }

    // For You section
    const readGenres = mockUserBooks.filter(u => u.status === 'reading' || u.status === 'completed').map(u => u.book.genre);
    const genreCount = {};
    readGenres.forEach(g => { genreCount[g] = (genreCount[g] || 0) + 1; });
    const topGenres = Object.entries(genreCount).sort((a, b) => b[1] - a[1]).map(([g]) => g);
    const userIds = new Set(mockUserBooks.map(u => u.book.id));
    const forYouBooks = books.filter(b => topGenres.includes(b.genre) && !userIds.has(b.id)).slice(0, 4);

    if (searchInput) {
        searchInput.addEventListener('input', () => { searchQuery = searchInput.value; visibleCount = ITEMS; render(); });
    }

    if (exploreBtn) {
        exploreBtn.addEventListener('click', () => { visibleCount += ITEMS; render(); });
    }

    function render() {
        let result = books;
        if (searchQuery.trim()) {
            const q = searchQuery.toLowerCase();
            result = result.filter(b => b.title.toLowerCase().includes(q) || b.author.toLowerCase().includes(q));
        }
        if (activeFilter === 'trending') result = result.filter(b => b.trending);
        else result = result.filter(b => b.genre === activeFilter);

        const visible = result.slice(0, visibleCount);
        grid.innerHTML = visible.map((b, i) => buildBookCardHTML(b, i, true)).join('');

        if (exploreBtn) exploreBtn.style.display = visibleCount < result.length ? '' : 'none';

        // For You
        if (forYouSection) {
            forYouSection.style.display = activeFilter === 'trending' ? '' : 'none';
            if (forYouGrid) {
                if (forYouBooks.length > 0) {
                    forYouGrid.innerHTML = `<div class="for-you-grid">${forYouBooks.map((b, i) => buildBookCardHTML(b, i, true)).join('')}</div>`;
                } else {
                    forYouGrid.innerHTML = `<div style="border:1px dashed var(--border);border-radius:.75rem;background:hsl(24,20%,14%,.5);padding:2rem;text-align:center"><p style="font-family:'Press Start 2P';font-size:.56rem;color:var(--muted-foreground)">📖 Read a few books to get personalized picks!</p></div>`;
                }
            }
        }
        if (divider) divider.style.display = (activeFilter === 'trending' && forYouBooks.length > 0) ? '' : 'none';
    }

    render();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  READING KINGDOM MAP MODAL  (index.html)
// ═══════════════════════════════════════════════════════════════════════════════
function initMapModal() {
    const mapBtn = document.getElementById('mapBtn');
    const mapOverlay = document.getElementById('mapOverlay');
    const mapClose = document.getElementById('mapClose');
    if (!mapBtn || !mapOverlay) return;

    const genrePositions = {
        "Fantasy": { left: "22%", top: "32%" },
        "Horror": { left: "70%", top: "22%" },
        "Mystery": { left: "38%", top: "58%" },
        "Crime": { left: "55%", top: "68%" },
        "Romance": { left: "15%", top: "60%" },
        "Drama": { left: "80%", top: "55%" },
        "Historical Fiction": { left: "60%", top: "38%" },
    };
    const genreEmojis = { "Fantasy": "🏰", "Horror": "💀", "Mystery": "🔍", "Crime": "🗡️", "Romance": "🌹", "Drama": "🎭", "Historical Fiction": "📜" };

    // Build genre overlay buttons
    const genreOverlay = document.getElementById('genreOverlay');
    if (genreOverlay) {
        genreOverlay.innerHTML = genres.map(g => {
            const pos = genrePositions[g] || { left: '50%', top: '50%' };
            return `<button class="genre-btn" style="left:${pos.left};top:${pos.top}" data-genre="${g}">
        <span class="genre-icon">${genreEmojis[g] || '📖'}</span>${g}
      </button>`;
        }).join('');
        genreOverlay.querySelectorAll('.genre-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                mapOverlay.classList.add('hidden');
                // Signal catalog
                const event = new CustomEvent('genreSelected', { detail: btn.dataset.genre });
                document.dispatchEvent(event);
            });
        });
    }

    mapBtn.addEventListener('click', () => mapOverlay.classList.remove('hidden'));
    if (mapClose) mapClose.addEventListener('click', () => mapOverlay.classList.add('hidden'));
    mapOverlay.addEventListener('click', e => { if (e.target === mapOverlay) mapOverlay.classList.add('hidden'); });

    document.addEventListener('genreSelected', e => {
        const filter = e.detail;
        const btn = document.querySelector(`#filterRow button[data-filter="${filter}"]`);
        if (btn) btn.click();
        document.getElementById('catalog')?.scrollIntoView({ behavior: 'smooth' });
    });
}

// ═══════════════════════════════════════════════════════════════════════════════
//  LUMO WELCOME MODAL  (index.html hero)
// ═══════════════════════════════════════════════════════════════════════════════
function initLumoWelcomeModal() {
    const getStartedBtn = document.getElementById('getStartedBtn');
    const lumoOverlay = document.getElementById('lumoOverlay');
    const lumoClose = document.getElementById('lumoClose');
    const acceptBtn = document.getElementById('acceptBounties');
    if (!getStartedBtn || !lumoOverlay) return;

    getStartedBtn.addEventListener('click', () => lumoOverlay.classList.remove('hidden'));
    if (lumoClose) lumoClose.addEventListener('click', () => lumoOverlay.classList.add('hidden'));
    if (acceptBtn) acceptBtn.addEventListener('click', () => lumoOverlay.classList.add('hidden'));
    lumoOverlay.addEventListener('click', e => { if (e.target === lumoOverlay) lumoOverlay.classList.add('hidden'); });
}

// ═══════════════════════════════════════════════════════════════════════════════
//  AUTH PAGE
// ═══════════════════════════════════════════════════════════════════════════════
function initAuth() {
    const form = document.getElementById('authForm');
    const toggleBtn = document.getElementById('authToggle');
    const formTitle = document.getElementById('authFormTitle');
    const submitBtn = document.getElementById('authSubmit');
    const submitIcon = document.getElementById('authSubmitIcon');
    const submitLabel = document.getElementById('authSubmitLabel');
    const confirmWrap = document.getElementById('confirmWrap');
    const forgotLink = document.getElementById('forgotLink');
    if (!form) return;

    let isSignUp = false;

    function updateUI() {
        if (formTitle) formTitle.textContent = isSignUp ? 'Create Account' : 'Sign In';
        if (submitIcon) submitIcon.innerHTML = isSignUp ? SVG.userPlus : SVG.login;
        if (submitLabel) submitLabel.textContent = isSignUp ? 'Register' : 'Sign In';
        if (confirmWrap) confirmWrap.style.display = isSignUp ? '' : 'none';
        if (forgotLink) forgotLink.style.display = isSignUp ? 'none' : '';
        if (toggleBtn) toggleBtn.innerHTML = isSignUp
            ? '✦ Already have an account? Sign In'
            : '✦ Register';
    }

    toggleBtn?.addEventListener('click', () => { isSignUp = !isSignUp; updateUI(); });
    form.addEventListener('submit', e => { e.preventDefault(); nav('index.html'); });
}

// ═══════════════════════════════════════════════════════════════════════════════
//  STORE PAGE
// ═══════════════════════════════════════════════════════════════════════════════
function initStore() {
    const grid = document.getElementById('tierGrid');
    if (!grid) return;
    const tiers = [
        { name: "Scribe's Penny", coins: 100, price: "$0.99", icon: SVG.sparkles, popular: false },
        { name: "Scholar's Purse", coins: 600, price: "$4.99", icon: SVG.crown, popular: true },
        { name: "Imperial Vault", coins: 2500, price: "$19.99", icon: SVG.gem, popular: false },
    ];
    grid.innerHTML = tiers.map(t => `
    <div class="tier-card${t.popular ? ' popular' : ''}">
      ${t.popular ? `<span class="popular-badge">MOST POPULAR</span>` : ''}
      <div class="tier-icon${t.popular ? ' popular-icon' : ''}">${t.icon}</div>
      <div>
        <h2>${t.name}</h2>
        <p class="coin-label">${t.coins.toLocaleString()} COINS</p>
      </div>
      <p class="tier-price">${t.price}</p>
      <button class="btn-buy">${SVG.creditCard} Buy Now</button>
    </div>`).join('');
}

// ═══════════════════════════════════════════════════════════════════════════════
//  PROFILE PAGE
// ═══════════════════════════════════════════════════════════════════════════════
function initProfile() {
    // Circular progress
    const svgEl = document.getElementById('circularSvg');
    if (svgEl) {
        const r = 40, circ = 2 * Math.PI * r;
        const pct = Math.min(mockUser.dailyReadingHours / mockUser.dailyReadingGoal * 100, 100);
        const offset = circ - (pct / 100) * circ;
        svgEl.innerHTML = `
      <circle cx="50" cy="50" r="40" fill="none" stroke="hsl(30,20%,22%)" stroke-width="6"/>
      <circle cx="50" cy="50" r="40" fill="none" stroke="hsl(38,75%,55%)" stroke-width="6"
        stroke-linecap="round" stroke-dasharray="${circ}" stroke-dashoffset="${offset}"
        class="transition-all" style="transition:stroke-dashoffset .7s"/>`;
        const label = document.getElementById('circularLabel');
        if (label) label.textContent = mockUser.dailyReadingHours + 'h';
    }

    // Scholar's Map
    const mapBody = document.getElementById('scholarsMapBody');
    if (mapBody) {
        const mapNodes = [
            { level: 1, label: "Starting Meadow", x: "12%", y: "82%" },
            { level: 5, label: "Whispering Woods", x: "18%", y: "55%" },
            { level: 10, label: "Crystal Ruins", x: "40%", y: "68%" },
            { level: 15, label: "Crystal Lake", x: "52%", y: "85%" },
            { level: 20, label: "Stone Pass", x: "62%", y: "60%" },
            { level: 25, label: "Dragon's Rest", x: "75%", y: "42%" },
            { level: 30, label: "Imperial Library", x: "90%", y: "25%" },
        ];
        const cur = mockUser.level;
        const hero = Math.max(0, mapNodes.findIndex(n => n.level > cur) - 1);
        const nodesHTML = mapNodes.map((n, i) => {
            const unlocked = cur >= n.level;
            return `<div class="map-node" style="left:${n.x};top:${n.y}">
        ${i === hero ? `<img src="assets/lumo-happy.png" class="hero-sprite animate-breathe" alt="You">` : ''}
        <div class="node-dot${unlocked ? ' unlocked' : ''}">${unlocked ? '⚔️' : '🔒'}</div>
        <span class="node-label${unlocked ? ' unlocked' : ''}">${!unlocked ? 'LV.' + n.level + ' ' : ''}${n.label}</span>
      </div>`;
        }).join('');
        mapBody.innerHTML = `<img src="assets/scholars-map.png" alt="Scholar's Map">${nodesHTML}`;
    }

    // Library grid
    const libGrid = document.getElementById('libraryGrid');
    const library = mockUserBooks.filter(u => u.status === 'reading' || u.status === 'completed');
    if (libGrid) {
        libGrid.innerHTML = library.map(ub => {
            const c = genreColors[ub.book.genre];
            const cover = genreCovers[ub.book.genre];
            const badgeCls = ub.status === 'completed' ? 'completed' : 'reading';
            const badgeTxt = ub.status === 'completed' ? '✓ DONE' : 'READING';
            const progress = (ub.status === 'reading' && ub.progress != null) ? `
        <div class="progress-bar-wrap"><div class="progress-bar" style="width:${ub.progress}%"></div></div>
        <p style="font-family:'Press Start 2P';font-size:.44rem;color:var(--muted-foreground);text-align:right">${ub.progress}%</p>` : '';
            return `<div class="book-card-static">
        <div class="cover-wrap">
          <img src="${cover}" alt="${ub.book.title}" loading="lazy">
          <div class="cover-fade"></div>
          <span class="status-badge ${badgeCls}">${badgeTxt}</span>
        </div>
        <div class="card-body">
          <h3 class="line-clamp-1">${ub.book.title}</h3>
          <p class="line-clamp-1">${ub.book.author}</p>
          ${progress}
          <span class="genre-tag" style="${c.css}">${ub.book.genre}</span>
        </div>
      </div>`;
        }).join('');
    }

    // My List grid
    const listGrid = document.getElementById('planGrid');
    const planToRead = mockUserBooks.filter(u => u.status === 'plan-to-read');
    if (listGrid) {
        if (planToRead.length === 0) {
            listGrid.innerHTML = `<div style="border:1px dashed var(--border);border-radius:.75rem;background:hsl(24,20%,14%/.5);padding:3rem;text-align:center">
        <p style="font-family:'Press Start 2P';font-size:.75rem;color:var(--muted-foreground);margin-bottom:1rem">Your list is empty!</p>
        <a href="index.html#catalog" class="btn-primary" style="display:inline-block;padding:.75rem 1.5rem">Find your first book! ✦</a>
      </div>`;
        } else {
            listGrid.innerHTML = `<div class="book-grid">${planToRead.map(ub => {
                const c = genreColors[ub.book.genre];
                const cover = genreCovers[ub.book.genre];
                return `<div class="book-card-static">
          <div class="cover-wrap">
            <img src="${cover}" alt="${ub.book.title}" loading="lazy">
            <div class="cover-fade"></div>
            <span class="status-badge plan">PLAN</span>
          </div>
          <div class="card-body">
            <h3 class="line-clamp-1">${ub.book.title}</h3>
            <p class="line-clamp-1">${ub.book.author}</p>
            <span class="genre-tag" style="${c.css}">${ub.book.genre}</span>
          </div>
        </div>`;
            }).join('')}</div>`;
        }
    }

    // Stats
    const booksReadEl = document.getElementById('booksReadCount');
    if (booksReadEl) booksReadEl.textContent = library.length;
    const coinEl = document.getElementById('profileCoins');
    if (coinEl) coinEl.textContent = mockUser.coins.toLocaleString();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  BOOK DETAIL PAGE
// ═══════════════════════════════════════════════════════════════════════════════
function initBookDetail() {
    const mainEl = document.getElementById('bookDetailMain');
    if (!mainEl) return;

    const params = new URLSearchParams(window.location.search);
    const id = parseInt(params.get('id'));
    const book = books.find(b => b.id === id);

    if (!book) {
        mainEl.innerHTML = `<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;text-align:center">
      <h1 style="font-size:2rem">Book not found</h1>
      <a href="index.html" class="btn-outline">Return Home</a>
    </div>`;
        return;
    }

    const cover = genreCovers[book.genre];
    const colors = genreColors[book.genre];
    const price = bookPrices[book.id];
    const userBook = mockUserBooks.find(u => u.book.id === book.id);
    const isReading = userBook?.status === 'reading';
    const isCompleted = userBook?.status === 'completed';
    const ctaText = isReading ? 'CONTINUE READING' : isCompleted ? 'READ AGAIN' : 'START READING';
    const progress = (isReading && userBook?.progress != null) ? `
    <div style="max-width:24rem">
      <div style="display:flex;justify-content:space-between;margin-bottom:.5rem">
        <span style="color:var(--muted-foreground);font-size:.9rem">Reading Progress</span>
        <span style="color:var(--primary);font-weight:600">${userBook.progress}%</span>
      </div>
      <div class="progress-bar-wrap"><div class="progress-bar" style="width:${userBook.progress}%"></div></div>
    </div>` : '';
    const completedBadge = isCompleted ? `<span class="completed-badge">✓ COMPLETED</span>` : '';

    document.title = `${book.title} — Lexora`;

    mainEl.innerHTML = `
  <section class="detail-hero">
    <div class="detail-cover">
      <div class="cover-inner">
        <img src="${cover}" alt="${book.title}">
        <div class="cover-grad"></div>
        ${book.trending ? `<span class="hot-badge">★ HOT</span>` : ''}
      </div>
    </div>
    <div class="detail-info">
      <div>
        <span class="genre-tag" style="${colors.css}">${book.genre}</span>
        <h1 style="margin-top:.5rem">${book.title}</h1>
        <p class="byline">by ${book.author}</p>
      </div>
      <p class="description">${book.description}</p>
      <div class="reward-chips">
        <div class="chip">${SVG.coins}<div><p class="chip-label">COST</p><p class="chip-value">${price?.cost.toLocaleString() ?? 'FREE'}</p></div></div>
        <div class="chip">${SVG.star}<div><p class="chip-label">EARN</p><p class="chip-value">+${price?.xpReward ?? 50} XP · +${price?.coinReward ?? 100} Coins</p></div></div>
      </div>
      ${progress}
      ${completedBadge}
      <button class="cta-btn">${SVG.bookOpen} ${ctaText}</button>
    </div>
  </section>

  <div style="border-top:1px solid var(--border)"></div>

  <section class="community-section">
    <h2>Book Community</h2>
    <p class="community-sub">Join the discussion about <span>${book.title}</span></p>
    <div class="sort-tabs" id="sortTabs">
      <button class="active" data-sort="top">${SVG.arrowUp} Top</button>
      <button data-sort="recent">${SVG.clock} Recent</button>
      <button data-sort="trending">${SVG.trending} Trending</button>
    </div>
    <div class="posts-list" id="postsList"></div>
  </section>`;

    // Render posts
    let sortMode = 'top';
    const tagColors = {
        discussion: 'background:hsl(150,30%,25%);color:hsl(38,50%,90%)',
        review: 'background:hsl(38,75%,55%,.2);color:hsl(38,75%,55%)',
        theory: 'background:hsl(345,45%,30%,.3);color:hsl(38,50%,90%)',
        spoiler: 'background:hsl(0,62%,50%,.2);color:hsl(0,62%,50%)',
    };

    function renderPosts() {
        let posts = communityPosts.filter(p => p.bookId === book.id);
        if (sortMode === 'top') posts = [...posts].sort((a, b) => b.upvotes - a.upvotes);
        if (sortMode === 'trending') posts = [...posts].sort((a, b) => b.comments - a.comments);

        const list = document.getElementById('postsList');
        if (!list) return;
        if (posts.length === 0) {
            list.innerHTML = `<div style="text-align:center;padding:4rem 1rem">
        ${SVG.msgSquare}
        <p style="font-size:1.1rem;color:var(--muted-foreground);margin-top:1rem">No discussions yet</p>
        <p style="font-size:.875rem;color:hsl(30,20%,55%/.6)">Be the first to start a conversation!</p>
      </div>`;
            return;
        }
        list.innerHTML = posts.map(p => `
      <div class="post-card">
        <div class="upvote-col">
          <button>${SVG.arrowUp}</button>
          <span>${p.upvotes}</span>
        </div>
        <div class="post-body">
          <div class="post-meta">
            <div class="avatar-pill">${p.avatarInitials}</div>
            <span class="author">${p.author}</span>
            <span class="dot">·</span>
            <span class="time">${p.timeAgo}</span>
            ${p.tag ? `<span class="tag-pill" style="${tagColors[p.tag] || ''}">${p.tag}</span>` : ''}
          </div>
          <h4>${p.title}</h4>
          <p class="line-clamp-2">${p.preview}</p>
          <div class="post-actions">
            <button>${SVG.msgSquare} ${p.comments} comments</button>
          </div>
        </div>
      </div>`).join('');
    }

    document.querySelectorAll('#sortTabs button').forEach(btn => {
        btn.addEventListener('click', () => {
            sortMode = btn.dataset.sort;
            document.querySelectorAll('#sortTabs button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderPosts();
        });
    });

    renderPosts();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  BOOT
// ═══════════════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    initGlobalHeader();
    initChatbot();
    initCatalog();
    initMapModal();
    initLumoWelcomeModal();
    initAuth();
    initStore();
    initProfile();
    initBookDetail();
});
