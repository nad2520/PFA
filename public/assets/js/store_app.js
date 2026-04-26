'use strict';

/**
 * store_app.js
 * Handles the Imperial Treasury (store) page:
 *  - Renders book grid from window.LX_BOOKS (preloaded from DB by PHP)
 *  - Handles book purchase via /api/user/book/purchase
 *  - Filters by genre, search
 *  - No localStorage for purchase state
 */

(function () {

const SESSION   = window.LX_SESSION  || {};
const ALL_BOOKS = window.LX_BOOKS    || [];
const CSRF      = SESSION.csrfToken  || '';
const USER_ROLE = SESSION.userRole   || '';

// Coin display
let currentCoins = SESSION.userCoins || 0;

// ── API Helper ────────────────────────────────────────────────────────────────
async function api(path, method = 'GET', body = null) {
    const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF },
    };
    if (body) opts.body = JSON.stringify(body);
    const res  = await fetch('/PFA' + path, opts);
    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'Request failed');
    return json;
}

// ── Genre Colors ──────────────────────────────────────────────────────────────
const GENRE_CSS = {
    'Fantasy':           'background:hsl(150,30%,25%);color:hsl(38,50%,90%)',
    'Horror':            'background:hsl(0,62%,50%,.2);color:hsl(0,62%,50%)',
    'Mystery':           'background:hsl(24,25%,20%);color:hsl(38,50%,90%)',
    'Crime':             'background:hsl(24,15%,18%);color:hsl(30,20%,55%)',
    'Romance':           'background:hsl(345,45%,30%,.3);color:hsl(38,50%,90%)',
    'Drama':             'background:hsl(38,75%,55%,.2);color:hsl(38,75%,55%)',
    'Historical Fiction':'background:hsl(12,40%,18%,.4);color:hsl(38,50%,90%)',
};
const GENRE_COVERS = {
    'Fantasy':           'public/assets/images/cover-fantasy.png',
    'Horror':            'public/assets/images/cover-horror.png',
    'Mystery':           'public/assets/images/cover-mystery.png',
    'Crime':             'public/assets/images/cover-crime.png',
    'Romance':           'public/assets/images/cover-romance.png',
    'Drama':             'public/assets/images/cover-drama.png',
    'Historical Fiction':'public/assets/images/cover-historical.png',
};

// ── State ─────────────────────────────────────────────────────────────────────
let activeGenre   = 'All';
let searchQuery   = '';
let pendingBookId = null;

function publicationYearValue(book) {
    const y = Number(book?.publicationYear ?? book?.publication_year ?? 0);
    return Number.isFinite(y) && y > 0 ? Math.floor(y) : null;
}

// ── Filter & render ───────────────────────────────────────────────────────────
function visibleBooks() {
    return ALL_BOOKS.filter(b => {
        const matchGenre = activeGenre === 'All' || b.genre === activeGenre;
        const q          = searchQuery.toLowerCase();
        const matchSearch = q === ''
            || b.title.toLowerCase().includes(q)
            || (b.author || '').toLowerCase().includes(q)
            || String(publicationYearValue(b) || '').includes(q);
        // Age-gate
        const matchAge = b.audience === 'All'
            || b.audience === USER_ROLE
            || USER_ROLE.includes('18') && b.audience === 'User +18'
            || !USER_ROLE.includes('+18') && b.audience === 'User -18';
        return matchGenre && matchSearch;
    });
}

function renderGrid() {
    const grid = document.getElementById('storeBookGrid');
    const noRes = document.getElementById('storeNoResults');
    const books = visibleBooks();

    if (!grid) return;
    if (books.length === 0) {
        grid.innerHTML = '';
        if (noRes) noRes.style.display = 'block';
        return;
    }
    if (noRes) noRes.style.display = 'none';

    grid.innerHTML = books.map(b => {
        const affordable = true;
        const coverSrc   = GENRE_COVERS[b.genre] || '';
        const genreStyle = GENRE_CSS[b.genre] || '';
        const pubYear    = publicationYearValue(b);
        return `
        <div class="book-card" data-book-id="${b.id}">
            <div class="book-cover-wrap" style="position:relative;overflow:hidden;border-radius:.5rem .5rem 0 0">
                ${coverSrc
                    ? `<img src="${coverSrc}" alt="${b.genre}" style="width:100%;height:9rem;object-fit:cover">`
                    : `<div style="width:100%;height:9rem;display:flex;align-items:center;justify-content:center;
                                   font-size:3rem;background:var(--secondary)">${b.cover || '📖'}</div>`
                }
                ${b.trending ? `<span style="position:absolute;top:.5rem;right:.5rem;font-size:.6rem;
                    font-family:'Press Start 2P';padding:.2rem .4rem;background:var(--primary);
                    color:#000;border-radius:.25rem">TRENDING</span>` : ''}
            </div>
            <div style="padding:.75rem">
                <span style="font-size:.65rem;padding:.15rem .4rem;border-radius:.25rem;${genreStyle}">
                    ${b.genre || 'Unknown'}
                </span>
                <p style="font-weight:700;font-size:.9rem;margin:.4rem 0 .2rem">${escHtml(b.title)}</p>
                <p style="font-size:.75rem;color:var(--muted-foreground);margin-bottom:.75rem">
                    ${escHtml(b.author)}
                </p>
                ${pubYear ? `<p style="font-size:.72rem;color:var(--muted-foreground);margin:-.35rem 0 .75rem">Published: ${pubYear}</p>` : ''}
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="font-family:'Press Start 2P';font-size:.6rem;color:var(--primary)">
                        ${b.coinCost} COINS
                    </span>
                    <button class="btn-buy-book"
                            data-id="${b.id}"
                            data-title="${escAttr(b.title)}"
                            data-cost="${b.coinCost}"
                            ${affordable ? '' : 'disabled title="Not enough coins"'}
                            style="font-size:.7rem;padding:.3rem .75rem;
                                   ${affordable ? '' : 'opacity:.4;cursor:not-allowed;'}">
                        Buy
                    </button>
                </div>
                <p style="font-size:.7rem;color:var(--muted-foreground);margin-top:.4rem">
                    +${b.xpReward} XP on completion
                </p>
            </div>
        </div>`;
    }).join('');

    // Attach purchase listeners
    grid.querySelectorAll('.btn-buy-book:not([disabled])').forEach(btn => {
        btn.addEventListener('click', () => openPurchaseModal(
            parseInt(btn.dataset.id, 10),
            btn.dataset.title,
            parseInt(btn.dataset.cost, 10)
        ));
    });
}

// ── Genre Filters ─────────────────────────────────────────────────────────────
function renderFilters() {
    const row = document.getElementById('storeFilterRow');
    if (!row) return;
    const genres = ['All', ...new Set(ALL_BOOKS.map(b => b.genre).filter(Boolean))];
    row.innerHTML = genres.map(g => `
        <button class="genre-pill${g === activeGenre ? ' genre-pill-active' : ''}"
                data-genre="${escAttr(g)}">${g}</button>
    `).join('');
    row.querySelectorAll('.genre-pill').forEach(btn => {
        btn.addEventListener('click', () => {
            activeGenre = btn.dataset.genre;
            row.querySelectorAll('.genre-pill').forEach(b => b.classList.remove('genre-pill-active'));
            btn.classList.add('genre-pill-active');
            renderGrid();
        });
    });
}

// ── Search ────────────────────────────────────────────────────────────────────
const searchInput = document.getElementById('storeSearch');
if (searchInput) {
    searchInput.addEventListener('input', () => {
        searchQuery = searchInput.value;
        renderGrid();
    });
}

// ── Purchase Modal ────────────────────────────────────────────────────────────
const modal       = document.getElementById('purchaseModal');
const btnConfirm  = document.getElementById('btnConfirmPurchase');
const btnCancel   = document.getElementById('btnCancelPurchase');

function openPurchaseModal(bookId, title, cost) {
    pendingBookId = bookId;
    document.getElementById('purchaseBookTitle').textContent = title;
    document.getElementById('purchaseBookCost').textContent  = `${cost} Coins`;
    document.getElementById('purchaseLoading').style.display  = 'none';
    document.getElementById('purchaseError').style.display    = 'none';
    document.getElementById('purchaseActions').style.display  = 'flex';
    document.getElementById('purchaseSuccess').style.display  = 'none';
    document.getElementById('purchaseActions').style.justifyContent = 'center';
    modal.style.display = 'flex';
}

btnConfirm?.addEventListener('click', async () => {
    if (!pendingBookId) return;
    document.getElementById('purchaseLoading').style.display = 'block';
    document.getElementById('purchaseActions').style.display = 'none';
    document.getElementById('purchaseError').style.display   = 'none';

    try {
        const data = await api('/api/user/book/purchase', 'POST', { book_id: pendingBookId });
        currentCoins = Number(data.newCoins ?? (currentCoins - (data.coinsSpent || 0)));

        // Update displayed coin count
        document.getElementById('coinCount') && (document.getElementById('coinCount').textContent = currentCoins.toLocaleString());
        document.getElementById('storeCoins') && (document.getElementById('storeCoins').textContent = currentCoins.toLocaleString());

        document.getElementById('purchaseLoading').style.display  = 'none';
        document.getElementById('purchaseSuccessMsg').textContent =
            `"${document.getElementById('purchaseBookTitle').textContent}" is now in your library!`;
        document.getElementById('purchaseSuccess').style.display = 'block';

        // Refresh grid (button should now show disabled/owned state)
        renderGrid();
    } catch (err) {
        document.getElementById('purchaseLoading').style.display = 'none';
        document.getElementById('purchaseError').textContent     = err.message;
        document.getElementById('purchaseError').style.display   = 'block';
        document.getElementById('purchaseActions').style.display = 'flex';
    }
});

btnCancel?.addEventListener('click', () => { modal.style.display = 'none'; });
modal?.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

// ── Utils ─────────────────────────────────────────────────────────────────────
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escAttr(s) {
    return String(s).replace(/"/g, '&quot;');
}

// ── Init ──────────────────────────────────────────────────────────────────────
renderFilters();
renderGrid();

})();