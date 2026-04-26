// ────────────────────────────────────────────────────────────────────────────
//  app.js  — Lexora Template: all interactive logic
// ────────────────────────────────────────────────────────────────────────────
// Requires: user_data.js (window.__lexora bootstrap), lexora-state.js (window.LexoraState)
// Catalog `books` / `bookPrices` are replaced from GET /api/catalog/books when the DB responds.

const LX = window.__lexora || {};
const {
    genreCovers,
    genreColors,
    genres,
    communityPosts,
} = LX;

/** @type {Array<{id:number,title:string,author:string,publicationYear?:number,genre:string,genres?:string[],trending:boolean,description:string,audience:string,cover:string}>} */
let books = Array.isArray(LX.books) ? LX.books.slice() : [];
/** @type {Record<number, {cost:number,xpReward:number,coinReward:number}>} */
let bookPrices = { ...(LX.bookPrices || {}) };

const LX_API_BASE = '/PFA';
const LX_SESSION = (typeof window !== 'undefined' && window.LX_SESSION) ? window.LX_SESSION : {};
const lxProfileRuntime = {
    coins: Number.isFinite(Number(LX_SESSION.userCoins)) ? Number(LX_SESSION.userCoins) : null,
    level: Number.isFinite(Number(LX_SESSION.userLevel)) ? Number(LX_SESSION.userLevel) : null,
};

function lxMapCatalogApiRow(row) {
    const rowGenres = Array.isArray(row.genres)
        ? row.genres.map((g) => String(g || '').trim()).filter(Boolean)
        : [];
    const legacyGenre = String(row.genre || '').trim();
    const genres = rowGenres.length > 0
        ? rowGenres
        : (legacyGenre ? [legacyGenre] : []);

    return {
        id: Number(row.id),
        title: String(row.title || ''),
        author: String(row.author || ''),
        publicationYear: Number(row.publicationYear || row.publication_year || 0),
        genre: legacyGenre,
        genres,
        trending: !!row.trending,
        description: String(row.description || ''),
        audience: String(row.audience || 'All'),
        cover: String(row.cover || '📖'),
    };
}

function lxBookPricesFromApiRows(rows) {
    const o = {};
    rows.forEach((r) => {
        const id = Number(r.id);
        if (!id) return;
        o[id] = {
            cost: Number(r.coinCost) || 0,
            xpReward: Number(r.xpReward) || 0,
            coinReward: Number(r.coinReward) || 0,
        };
    });
    return o;
}

/**
 * Loads catalog from MySQL (`books` table). On failure keeps `user_data.js` bootstrap.
 * Updates window.__lexora so LexoraState merges stay consistent.
 */
async function syncLexoraCatalogFromApi() {
    const L = window.__lexora || {};
    const fallbackBooks = Array.isArray(L.books) ? L.books.slice() : [];
    const fallbackPrices = { ...(L.bookPrices || {}) };

    try {
        const res = await fetch(`${LX_API_BASE}/api/catalog/books`, { credentials: 'same-origin' });
        const j = await res.json().catch(() => ({}));
        if (!res.ok || !j.success || !Array.isArray(j.data)) {
            throw new Error('catalog_unavailable');
        }
        const mapped = j.data.map(lxMapCatalogApiRow).filter((b) => b.id > 0);
        books = mapped;
        bookPrices = lxBookPricesFromApiRows(j.data);
        window.__lexora = window.__lexora || {};
        window.__lexora.books = mapped;
        window.__lexora.bookPrices = { ...bookPrices };
    } catch (_) {
        books = fallbackBooks;
        bookPrices = fallbackPrices;
        window.__lexora = window.__lexora || {};
        window.__lexora.books = fallbackBooks.slice();
        window.__lexora.bookPrices = { ...fallbackPrices };
    }
}

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
    pencil: `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`,
    trash: `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>`,
    check: `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`,
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

// ═══════════════════════════════════════════════════════════════════════════════
//  COMMUNITY STORE  — localStorage-backed, mirrors CommunityContext.tsx
// ═══════════════════════════════════════════════════════════════════════════════
const communityStore = (() => {
    const STORAGE_KEY = 'lexora-community';

    function _initials(name) {
        return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
    }

    function _save(posts) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(posts)); } catch (_) { }
    }

    function _load() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (raw) return JSON.parse(raw);
        } catch (_) { }
        // Seed from static data on first visit
        const seed = (window.__lexora?.communityPosts || []).map(p => ({
            id: p.id,
            bookId: p.bookId,
            author: p.author,
            avatarInitials: p.avatarInitials,
            title: p.title,
            content: p.preview,
            createdAt: Date.now() - Math.floor(Math.random() * 86400000 * 3),
            upvotes: p.upvotes,
            upvotedBy: [],
            tag: p.tag,
            comments: [],
        }));
        _save(seed);
        return seed;
    }

    let _posts = _load();

    function getPosts(bookId) {
        return _posts.filter(p => p.bookId === bookId);
    }

    function addPost(bookId, title, content, tag) {
        const currentUser = lxResolvedUserName();
        const post = {
            id: 'user-' + Date.now(),
            bookId,
            author: currentUser,
            avatarInitials: _initials(currentUser),
            title,
            content,
            createdAt: Date.now(),
            upvotes: 0,
            upvotedBy: [],
            tag: tag || null,
            comments: [],
        };
        _posts = [post, ..._posts];
        _save(_posts);
    }

    function editPost(postId, title, content) {
        const currentUser = lxResolvedUserName();
        _posts = _posts.map(p =>
            p.id === postId && p.author === currentUser ? { ...p, title, content } : p
        );
        _save(_posts);
    }

    function deletePost(postId) {
        const currentUser = lxResolvedUserName();
        _posts = _posts.filter(p => !(p.id === postId && p.author === currentUser));
        _save(_posts);
    }

    function togglePostUpvote(postId) {
        const currentUser = lxResolvedUserName();
        _posts = _posts.map(p => {
            if (p.id !== postId) return p;
            const liked = p.upvotedBy.includes(currentUser);
            return {
                ...p,
                upvotes: liked ? p.upvotes - 1 : p.upvotes + 1,
                upvotedBy: liked
                    ? p.upvotedBy.filter(u => u !== currentUser)
                    : [...p.upvotedBy, currentUser],
            };
        });
        _save(_posts);
    }

    function addComment(postId, content) {
        const currentUser = lxResolvedUserName();
        const comment = {
            id: 'c-' + Date.now(),
            postId,
            author: currentUser,
            avatarInitials: _initials(currentUser),
            content,
            createdAt: Date.now(),
            upvotes: 0,
            upvotedBy: [],
        };
        _posts = _posts.map(p =>
            p.id === postId ? { ...p, comments: [...p.comments, comment] } : p
        );
        _save(_posts);
    }

    function editComment(postId, commentId, content) {
        const currentUser = lxResolvedUserName();
        _posts = _posts.map(p => p.id === postId ? {
            ...p,
            comments: p.comments.map(c =>
                c.id === commentId && c.author === currentUser ? { ...c, content } : c
            ),
        } : p);
        _save(_posts);
    }

    function deleteComment(postId, commentId) {
        const currentUser = lxResolvedUserName();
        _posts = _posts.map(p => p.id === postId ? {
            ...p,
            comments: p.comments.filter(c => !(c.id === commentId && c.author === currentUser)),
        } : p);
        _save(_posts);
    }

    function toggleCommentUpvote(postId, commentId) {
        const currentUser = lxResolvedUserName();
        _posts = _posts.map(p => p.id === postId ? {
            ...p,
            comments: p.comments.map(c => {
                if (c.id !== commentId) return c;
                const liked = c.upvotedBy.includes(currentUser);
                return {
                    ...c,
                    upvotes: liked ? c.upvotes - 1 : c.upvotes + 1,
                    upvotedBy: liked
                        ? c.upvotedBy.filter(u => u !== currentUser)
                        : [...c.upvotedBy, currentUser],
                };
            }),
        } : p);
        _save(_posts);
    }

    return { getPosts, addPost, editPost, deletePost, togglePostUpvote, addComment, editComment, deleteComment, toggleCommentUpvote };
})();

// ─── Helper: make genre tag HTML ─────────────────────────────────────────────
function genreTagHTML(genre) {
    const c = genreColors[genre] || { css: 'background:var(--secondary);color:var(--secondary-foreground)' };
    return `<span class="genre-tag" style="${c.css}">${genre}</span>`;
}

function normalizeBookGenres(book) {
    if (!book || typeof book !== 'object') return [];
    const source = Array.isArray(book.genres) && book.genres.length > 0
        ? book.genres
        : [book.genre];
    const seen = new Set();
    const out = [];
    source.forEach((g) => {
        const genre = String(g || '').trim();
        if (!genre) return;
        const key = genre.toLowerCase();
        if (seen.has(key)) return;
        seen.add(key);
        out.push(genre);
    });
    return out;
}

function genreTagsHTML(book) {
    const genres = normalizeBookGenres(book);
    if (genres.length === 0) return '';
    return `<div class="genre-tags-wrap">${genres.map((g) => genreTagHTML(g)).join('')}</div>`;
}

function getPublicationYear(book) {
    const y = Number(book?.publicationYear ?? book?.publication_year ?? 0);
    return Number.isFinite(y) && y > 0 ? Math.floor(y) : null;
}

function publicationYearHTML(book) {
    const y = getPublicationYear(book);
    if (!y) return '';
    return `<p class="book-pub-year">Published: ${y}</p>`;
}

// ─── Navigate helper ─────────────────────────────────────────────────────────
function nav(url) { window.location.href = url; }

/** Display name: server bootstrap → profile API cache → mock fallback. */
function lxResolvedUserName() {
    const b = (typeof window !== 'undefined' && window.__lxBootstrapUser) || null;
    if (b && typeof b.name === 'string' && b.name.trim()) return b.name.trim();
    if (typeof window !== 'undefined' && window.__lxProfileName) return String(window.__lxProfileName).trim();
    return (window.__lexora && window.__lexora.mockUser && window.__lexora.mockUser.name) || 'Reader';
}

/** Read at request time so pages that set `window.LX_SESSION` in `<head>` always send CSRF. */
async function lxApi(path, method = 'GET', body = null) {
    const headers = { 'Content-Type': 'application/json' };
    const token = (typeof window !== 'undefined' && window.LX_SESSION && window.LX_SESSION.csrfToken) || '';
    if (token) headers['X-CSRF-Token'] = token;
    const res = await fetch('/PFA' + path, {
        method,
        headers,
        credentials: 'same-origin',
        cache: method === 'GET' ? 'no-store' : 'default',
        body: body ? JSON.stringify(body) : undefined,
    });
    const text = await res.text();
    let json;
    try {
        json = JSON.parse(text);
    } catch (_) {
        const hint = text.trimStart().startsWith('<')
            ? 'Server returned HTML (often a PHP error). Check the Network tab response body and your PHP error log.'
            : 'Invalid JSON from server.';
        throw new Error(hint);
    }
    if (!res.ok || !json.success) {
        throw new Error(json.message || 'Request failed.');
    }
    return json;
}

// ═══════════════════════════════════════════════════════════════════════════════
//  GLOBAL HEADER
// ═══════════════════════════════════════════════════════════════════════════════
function initHeaderNavLinks() {
    const el = document.getElementById('navBackLecture');
    if (!el) return;
    if (document.getElementById('bookDetailMain')) return;
    lxApi('/api/user/back-to-lecture', 'GET')
        .then(({ data }) => {
            if (data?.book_id) {
                el.href = `${LX_API_BASE}/read-book?id=${data.book_id}`;
                el.style.display = '';
                return;
            }
            const last = window.LexoraState?.getLastBookReadId?.();
            if (last) {
                el.href = `${LX_API_BASE}/read-book?id=${last}`;
                el.style.display = '';
            }
        })
        .catch(() => {
            const last = window.LexoraState?.getLastBookReadId?.();
            if (last) {
                el.href = `${LX_API_BASE}/read-book?id=${last}`;
                el.style.display = '';
            }
        });
}

function updateBookDetailHeaderNav(bookId, showLecture) {
    const navL = document.getElementById('navBackLecture');
    if (!navL) return;
    if (showLecture) {
        navL.href = `${LX_API_BASE}/read-book?id=${bookId}`;
        navL.style.display = '';
    } else {
        navL.style.display = 'none';
    }
}

/** Profile page only — Lamp of Knowledge card (matches React LampOfKnowledge.tsx) */
function initLampOfKnowledgeSection() {
    const slider = document.getElementById('lampSlider');
    const hourLabel = document.getElementById('lampHours');
    const lampDot = document.getElementById('lampDot');
    const statusEl = document.getElementById('lampStatus');
    const lumoThumb = document.getElementById('lumoThumbLamp');
    if (!slider) return;

    const baseCoins = lxProfileRuntime.coins != null ? lxProfileRuntime.coins : 0;
    let penaltyApplied = false;

    function update() {
        const h = +slider.value;
        if (hourLabel) hourLabel.textContent = h + 'h';

        let state, opacity;
        if (h < 18) { state = 'bright'; opacity = 1; }
        else if (h < 22) { state = 'fading'; opacity = 1 - ((h - 18) / 4) * 0.6; }
        else if (h < 24) { state = 'flickering'; opacity = 0.3; }
        else { state = 'dark'; opacity = 0.05; }

        if (lampDot) {
            lampDot.style.opacity = opacity;
            lampDot.style.boxShadow = `0 0 ${opacity * 20}px hsl(38 90% 60% / ${opacity})`;
            lampDot.textContent = state === 'dark' ? '💀' : '🔥';
            lampDot.classList.remove('animate-lamp-glow', 'animate-lamp-flicker');
            if (state === 'bright') lampDot.classList.add('animate-lamp-glow');
            if (state === 'flickering') lampDot.classList.add('animate-lamp-flicker');
        }

        const isWorried = h >= 18;
        const level = lxProfileRuntime.level != null ? lxProfileRuntime.level : 1;
        const penPct = level <= 5 ? 10 : level <= 15 ? 30 : level <= 25 ? 50 : 70;

        let lostThisStep = 0;
        if (state === 'dark' && !penaltyApplied) {
            lostThisStep = Math.floor(baseCoins * (penPct / 100));
            penaltyApplied = true;
        }
        if (state !== 'dark' && penaltyApplied) penaltyApplied = false;

        if (statusEl) {
            if (state === 'dark') {
                statusEl.innerHTML = `<div class="status-lost pixel-dialogue-inner">${SVG.coins} -${penPct}% COINS LOST! (${lostThisStep.toLocaleString()} coins)</div>`;
            } else if (isWorried) {
                statusEl.innerHTML = `<p class="status-warn pixel-dialogue-inner">"You have to read at least a short novel before I sleep, otherwise you will lose your coins!"</p>`;
            } else {
                statusEl.innerHTML = `<p class="status-lit pixel-dialogue-inner">Lumo is happy! Keep reading to maintain your streak.</p>`;
            }
        }

        if (lumoThumb) {
            lumoThumb.src = isWorried ? 'public/assets/images/lumo-worried.png' : 'public/assets/images/lumo-happy.png';
        }
    }

    slider.addEventListener('input', update);
    update();
}

function initHeaderWalletFromApi() {
    const has = document.getElementById('coinCount')
        || document.getElementById('hoverCardUserName')
        || document.getElementById('headerUserName');
    if (!has) return;
    fetch('/PFA/api/user/profile', { credentials: 'same-origin', cache: 'no-store' })
        .then(r => r.json())
        .then(j => {
            if (j.success && j.data && window.LX_applyProfileStats) {
                window.LX_applyProfileStats({
                    name: j.data.name,
                    coins: j.data.coins,
                    level: j.data.level,
                    booksRead: j.data.booksRead,
                });
            }
            // Do not call applyLibraryFromServer here — initProfile() owns shelf sync to avoid a second
            // profile response (possibly stale / reordered) overwriting an empty library after fetch.
        })
        .catch(() => { /* guest or offline */ });
}

function initGlobalHeader() {
    initHeaderNavLinks();
    initLampOfKnowledgeSection();
    initHeaderWalletFromApi();
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
const LUMO_CHAT_REC_STATE = {
    seenIds: new Set(),
    lastGenreHint: '',
};

function parseGenreFromInput(input) {
    const normalized = String(input || '').toLowerCase();
    const knownGenres = Array.isArray(genres) ? genres : [];
    for (const genre of knownGenres) {
        const label = String(genre || '').trim();
        if (!label) continue;
        const escaped = label.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`\\b${escaped.toLowerCase()}\\b`, 'i');
        if (regex.test(normalized)) {
            return label;
        }
    }
    return '';
}

async function fetchPersonalizedChatRecommendations(genreHint) {
    const params = new URLSearchParams();
    params.set('limit', '3');
    if (genreHint) params.set('genre', genreHint);
    if (LUMO_CHAT_REC_STATE.seenIds.size > 0) {
        params.set('exclude_ids', Array.from(LUMO_CHAT_REC_STATE.seenIds).join(','));
    }
    const url = `${LX_API_BASE}/api/user/recommendations/chatbot?${params.toString()}`;
    const response = await fetch(url, { credentials: 'same-origin' });
    const data = await response.json().catch(() => ({}));
    if (!response.ok || !data.success || !Array.isArray(data.data)) {
        return [];
    }
    data.data.forEach((b) => {
        const id = Number(b?.id || 0);
        if (id > 0) LUMO_CHAT_REC_STATE.seenIds.add(id);
    });
    return data.data;
}

async function getLumoReply(input) {
    const l = input.toLowerCase();
    const asksForMore = /\bmore\b|\banother\b|\bnext\b|\bencore\b|\bautre\b/.test(l);
    const asksForBooks = /book|books|genre|category|what should i read|something to read|i want to read/.test(l);
    const wantsRecommendations = /recommend|suggest|pick|read next|i want to read|book from|genre|category|like/.test(l) || asksForMore || asksForBooks;
    if (wantsRecommendations) {
        const detectedGenre = parseGenreFromInput(l);
        const genreHint = detectedGenre || (asksForMore ? LUMO_CHAT_REC_STATE.lastGenreHint : '');
        if (detectedGenre) {
            LUMO_CHAT_REC_STATE.lastGenreHint = detectedGenre;
        }
        try {
            const recs = await fetchPersonalizedChatRecommendations(genreHint);
            if (recs.length >= 3) {
                const titles = recs
                    .map((b) => String(b.title || '').trim())
                    .filter(Boolean)
                    .slice(0, 3);
                if (titles.length > 0) {
                    const prefix = asksForMore
                        ? 'Here are more personalized recommendations for you'
                        : 'Based on your reading history and preferences, here are some recommendations';
                    const genrePart = genreHint ? ` in ${genreHint}` : '';
                    return `${prefix}${genrePart}: 📚 ${titles[0]} 📚 ${titles[1]} 📚 ${titles[2]}`;
                }
            }
            if (genreHint) {
                return `I could not find 3 fresh ${genreHint} picks yet. Try rating a few books so I can personalize better, then ask again for more recommendations.`;
            }
            return 'I need a little more reading history to generate 3 fresh personalized recommendations. Rate completed books and set favorite categories, then ask again.';
        } catch (_) {
            return "I couldn't load personalized recommendations right now. Please try again in a moment.";
        }
    }

    if (/hello|hi|hey|greet/.test(l)) return LUMO_RESPONSES.hello;
    if (/coin|money|currency|cost|price/.test(l)) return LUMO_RESPONSES.coins;
    if (/book|read|catalog|genre/.test(l)) return "Tell me what mood or genre you want, and I'll give you personalized book picks.";
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
        if (!isUser) div.innerHTML = `<img src="public/assets/images/lumo-happy.png" alt="">`;
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
        setTimeout(async () => {
            const reply = await getLumoReply(val);
            appendMsg('lumo', reply);
            sendBtn.disabled = false;
        }, 600);
    }

    sendBtn.addEventListener('click', handleSend);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') handleSend(); });
    input.addEventListener('input', () => { sendBtn.disabled = !input.value.trim(); });
}

// ═══════════════════════════════════════════════════════════════════════════════
//  BOOK CATALOG  (index.php)
// ═══════════════════════════════════════════════════════════════════════════════
/** Flat catalog card — parity with story-shelf-retreat/src/components/BookCard.tsx */
function buildCatalogBookCardHTML(book, index) {
    const cover = genreCovers[book.genre];
    const price = bookPrices[book.id];
    const tags = genreTagsHTML(book);
    const year = publicationYearHTML(book);
    const costStr = price ? price.cost.toLocaleString() : 'FREE';
    const delay = index * 0.05;
    return `
    <div class="catalog-book-card" style="animation-delay:${delay}s" onclick="nav('${LX_API_BASE}/book-detail?id=${book.id}')">
      <div class="catalog-book-card__cover">
        <img src="${cover}" alt="${book.title}" loading="lazy">
        <div class="catalog-book-card__cover-fade"></div>
        ${book.trending ? `<span class="catalog-book-card__hot">★ HOT</span>` : ''}
      </div>
      <div class="catalog-book-card__body">
        <h3 class="line-clamp-1">${book.title}</h3>
        <p class="line-clamp-1 catalog-book-card__author">${book.author}</p>
        ${year}
        <div class="catalog-book-card__footer">
          ${tags}
          <div class="catalog-book-card__coins">
            ${SVG.coins}
            <span class="catalog-book-card__cost">${costStr}</span>
          </div>
        </div>
      </div>
    </div>`;
}

function buildBookCardHTML(book, index, flip = true) {
    const cover = genreCovers[book.genre];
    const price = bookPrices[book.id];
    const tags = genreTagsHTML(book);
    const year = publicationYearHTML(book);
    const priceStr = price ? price.cost.toLocaleString() + ' COINS' : 'FREE';
    const rewardStr = `+${price?.xpReward ?? 50} XP & +${price?.coinReward ?? 100} COINS`;

    if (flip) {
        return `
    <div class="book-card-container animate-float-up" style="animation-delay:${index * 0.05}s" onclick="nav('${LX_API_BASE}/book-detail?id=${book.id}')">
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
            ${year}
            ${tags}
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
        ${year}
        ${tags}
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

    // For You section (DB-backed category preferences + carousel)
    const prefOverlay = document.getElementById('forYouPrefsOverlay');
    const prefChoices = document.getElementById('forYouPrefsChoices');
    const prefMsg = document.getElementById('forYouPrefsMsg');
    const prefSaveBtn = document.getElementById('forYouPrefsSave');
    const prefCloseBtn = document.getElementById('forYouPrefsClose');
    const prefSkipBtn = document.getElementById('forYouPrefsSkip');
    const prefEditBtn = document.getElementById('editForYouPrefsBtn');
    let availableGenres = [];
    let selectedGenres = [];
    let forYouBooks = [];

    function setPrefMsg(msg, isError = false) {
        if (!prefMsg) return;
        prefMsg.textContent = msg || '';
        prefMsg.style.color = isError ? 'var(--destructive)' : 'var(--muted-foreground)';
    }

    function renderPrefChoices() {
        if (!prefChoices) return;
        if (!Array.isArray(availableGenres) || availableGenres.length === 0) {
            prefChoices.innerHTML = '<p style="color:var(--muted-foreground);font-size:.9rem">No categories available.</p>';
            return;
        }
        prefChoices.innerHTML = availableGenres.map((g) => {
            const active = selectedGenres.includes(g);
            return `<button type="button" data-genre="${g}" class="btn-outline lx-pref-chip${active ? ' active' : ''}" style="padding:.65rem .7rem;font-size:.75rem;${active ? 'background:var(--primary);border-color:var(--primary);color:var(--primary-foreground);' : ''}">${g}</button>`;
        }).join('');
        prefChoices.querySelectorAll('button[data-genre]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const g = btn.getAttribute('data-genre') || '';
                if (!g) return;
                if (selectedGenres.includes(g)) {
                    selectedGenres = selectedGenres.filter(x => x !== g);
                } else {
                    selectedGenres.push(g);
                }
                renderPrefChoices();
            });
        });
    }

    function togglePrefModal(show) {
        if (!prefOverlay) return;
        prefOverlay.classList.toggle('hidden', !show);
        prefOverlay.setAttribute('aria-hidden', show ? 'false' : 'true');
    }

    async function loadPreferenceBootstrap() {
        try {
            const resp = await lxApi('/api/user/preferences/categories', 'GET');
            if (resp?.success && resp.data) {
                availableGenres = Array.isArray(resp.data.available) ? resp.data.available : [];
                selectedGenres = Array.isArray(resp.data.selected) ? resp.data.selected : [];
                renderPrefChoices();
                if (resp.data.schemaReady === false) {
                    setPrefMsg('Preferences storage is not ready. Ask admin to run migration 010_user_category_preferences.sql.', true);
                    return;
                }
                if (!resp.data.hasPreferences) {
                    togglePrefModal(true);
                    setPrefMsg('Choose at least one category to start.');
                }
            }
        } catch (_) { /* ignore */ }
    }

    async function loadForYouBooks() {
        try {
            const resp = await lxApi('/api/user/recommendations/for-you?limit=14', 'GET');
            forYouBooks = (resp?.success && Array.isArray(resp.data))
                ? resp.data.map(lxMapCatalogApiRow).filter((b) => b.id > 0)
                : [];
            if (window.LX_USER_ROLE === 'User -18') {
                forYouBooks = forYouBooks.filter(b => b.audience !== '+18 Only');
            }
        } catch (_) {
            forYouBooks = [];
        }
    }

    if (prefEditBtn) {
        prefEditBtn.addEventListener('click', () => {
            renderPrefChoices();
            togglePrefModal(true);
        });
    }
    if (prefCloseBtn) prefCloseBtn.addEventListener('click', () => togglePrefModal(false));
    if (prefSkipBtn) prefSkipBtn.addEventListener('click', () => togglePrefModal(false));
    if (prefOverlay) {
        prefOverlay.addEventListener('click', (ev) => {
            if (ev.target === prefOverlay) togglePrefModal(false);
        });
    }
    if (prefSaveBtn) {
        prefSaveBtn.addEventListener('click', async () => {
            if (!Array.isArray(selectedGenres) || selectedGenres.length === 0) {
                setPrefMsg('Select at least one category.', true);
                return;
            }
            const prev = prefSaveBtn.textContent;
            prefSaveBtn.disabled = true;
            prefSaveBtn.textContent = 'Saving...';
            setPrefMsg('Saving your preferences...');
            try {
                const resp = await lxApi('/api/user/preferences/categories', 'POST', { genres: selectedGenres });
                if (!resp?.success) {
                    throw new Error(resp?.message || 'Could not save preferences.');
                }
                setPrefMsg('Preferences saved.');
                await loadForYouBooks();
                render();
                togglePrefModal(false);
            } catch (e) {
                setPrefMsg((e && e.message) ? String(e.message) : 'Could not save preferences.', true);
            } finally {
                prefSaveBtn.disabled = false;
                prefSaveBtn.textContent = prev || 'Save preferences';
            }
        });
    }

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
            result = result.filter((b) => {
                const yearText = String(b.publicationYear || '').toLowerCase();
                return b.title.toLowerCase().includes(q)
                    || b.author.toLowerCase().includes(q)
                    || yearText.includes(q);
            });
        }
        if (activeFilter === 'trending') result = result.filter(b => b.trending);
        else {
            result = result.filter((b) => {
                const genreList = Array.isArray(b.genres) && b.genres.length > 0
                    ? b.genres
                    : (b.genre ? [b.genre] : []);
                return genreList.includes(activeFilter);
            });
        }

        if (window.LX_USER_ROLE === 'User -18') {
            result = result.filter(b => b.audience !== '+18 Only');
        }

        const visible = result.slice(0, visibleCount);
        grid.innerHTML = visible.map((b, i) => buildCatalogBookCardHTML(b, i)).join('');

        if (exploreBtn) exploreBtn.style.display = visibleCount < result.length ? '' : 'none';

        // For You
        if (forYouSection) {
            forYouSection.style.display = activeFilter === 'trending' ? '' : 'none';
            if (forYouGrid) {
                if (forYouBooks.length > 0) {
                    forYouGrid.innerHTML = `
                    <div class="for-you-carousel-wrap">
                      <button type="button" class="for-you-nav for-you-nav--prev" id="forYouPrev" aria-label="Scroll left">‹</button>
                      <div class="for-you-carousel" id="forYouCarousel">
                        ${forYouBooks.map((b, i) => `<div class="for-you-item">${buildCatalogBookCardHTML(b, i)}</div>`).join('')}
                      </div>
                      <button type="button" class="for-you-nav for-you-nav--next" id="forYouNext" aria-label="Scroll right">›</button>
                    </div>`;
                    const rail = document.getElementById('forYouCarousel');
                    const prev = document.getElementById('forYouPrev');
                    const next = document.getElementById('forYouNext');
                    const dx = 360;
                    prev?.addEventListener('click', () => rail?.scrollBy({ left: -dx, behavior: 'smooth' }));
                    next?.addEventListener('click', () => rail?.scrollBy({ left: dx, behavior: 'smooth' }));
                } else {
                    forYouGrid.innerHTML = `<div style="border:1px dashed var(--border);border-radius:.75rem;background:hsl(24,20%,14%,.5);padding:2rem;text-align:center">
                    <p style="font-family:'Press Start 2P';font-size:.56rem;color:var(--muted-foreground);margin-bottom:.8rem">No personalized picks yet.</p>
                    <button type="button" id="forYouEmptyCta" class="btn-primary" style="padding:.65rem 1rem">Choose categories</button>
                    </div>`;
                    const cta = document.getElementById('forYouEmptyCta');
                    cta?.addEventListener('click', () => togglePrefModal(true));
                }
            }
        }
        if (divider) divider.style.display = (activeFilter === 'trending' && forYouBooks.length > 0) ? '' : 'none';
    }
    (async function bootForYou() {
        await loadPreferenceBootstrap();
        await loadForYouBooks();
        render();
    })();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  READING KINGDOM MAP MODAL  (index.php)
//  Region layout mirrors story-shelf-retreat/src/components/ReadingKingdomMap.tsx (mapRegions)
// ═══════════════════════════════════════════════════════════════════════════════
/** @see story-shelf-retreat/src/components/ReadingKingdomMap.tsx — mapRegions array */
const mapRegions = [
    { id: 'romance', label: '💐 Romance Valley', genre: 'Romance', top: '35%', left: '3%', width: '22%', height: '30%' },
    { id: 'fantasy', label: '✨ Fantasy Peaks', genre: 'Fantasy', top: '5%', left: '15%', width: '30%', height: '32%' },
    { id: 'drama', label: '🏰 Drama Kingdom', genre: 'Drama', top: '30%', left: '30%', width: '22%', height: '30%' },
    { id: 'horror', label: '🌑 Horror Marsh', genre: 'Horror', top: '10%', left: '52%', width: '25%', height: '35%' },
    { id: 'mystery', label: '🔍 Mystery Coast', genre: 'Mystery', top: '45%', left: '55%', width: '22%', height: '30%' },
    { id: 'historical', label: '🏛️ Ancient Ruins', genre: 'Historical Fiction', top: '40%', left: '68%', width: '20%', height: '30%' },
    { id: 'crime', label: '⚓ Adventure Seas', genre: 'Crime', top: '65%', left: '60%', width: '30%', height: '28%' },
    { id: 'contemporary', label: '🏡 Cozy Village', genre: 'Drama', top: '62%', left: '15%', width: '30%', height: '28%' },
];

function initMapModal() {
    const mapBtn = document.getElementById('mapBtn');
    const mapOverlay = document.getElementById('mapOverlay');
    const mapClose = document.getElementById('mapClose');
    if (!mapBtn || !mapOverlay) return;

    const genreOverlay = document.getElementById('genreOverlay');
    if (genreOverlay) {
        genreOverlay.innerHTML = mapRegions.map(r => `
      <button type="button" class="map-region-btn" data-genre="${r.genre}"
        style="top:${r.top};left:${r.left};width:${r.width};height:${r.height}"
        aria-label="Explore ${r.label}">
        <span class="map-region-tooltip">${r.label}</span>
      </button>`).join('');
        genreOverlay.querySelectorAll('.map-region-btn').forEach(btn => {
            btn.addEventListener('mouseenter', () => btn.classList.add('is-hovered'));
            btn.addEventListener('mouseleave', () => btn.classList.remove('is-hovered'));
            btn.addEventListener('click', () => {
                mapOverlay.classList.add('hidden');
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
//  LUMO WELCOME MODAL  (index.php hero)
// ═══════════════════════════════════════════════════════════════════════════════
function initLumoWelcomeModal() {
    const getStartedBtn = document.getElementById('getStartedBtn');
    const lumoOverlay = document.getElementById('lumoOverlay');
    const lumoClose = document.getElementById('lumoClose');
    const acceptBtn = document.getElementById('acceptBounties');
    const bountyList = document.getElementById('bountyList');
    if (!getStartedBtn || !lumoOverlay) return;

    const esc = (s) => String(s ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/"/g, '&quot;');

    async function loadBounties() {
        if (!bountyList) return;
        bountyList.innerHTML = `<div class="bounty-item"><div><h3>Loading quests...</h3><p>Please wait while Lumo fetches the latest bounties.</p></div></div>`;
        try {
            const res = await lxApi('/api/user/quests', 'GET');
            const allRows = Array.isArray(res?.data) ? res.data : [];
            const rows = allRows.slice(0, 3);
            if (rows.length === 0) {
                bountyList.innerHTML = `<div class="bounty-item"><div><h3>No active quests</h3><p>There are no active bounties right now. Check back later.</p></div></div>`;
                return;
            }
            bountyList.innerHTML = rows.map((q) => `
              <div class="bounty-item${q.is_completed ? ' is-complete' : ''}" style="${q.is_completed ? 'opacity:.72;' : ''}">
                ${SVG.scroll}
                <div>
                  <h3>${esc(q.title || q.quest_key || 'Quest')}</h3>
                  <p>${esc(q.description || 'Complete this quest to earn rewards.')}</p>
                  <p style="margin-top:4px;color:#d7b56d;font-size:12px">
                    Progress: ${Math.max(0, Number(q.progress_value) || 0)} / ${Math.max(1, Number(q.target_value) || 1)}
                  </p>
                  <span class="reward">+${Number(q.xp_reward) || 0} XP, +${Number(q.coins_reward) || 0} Coins${q.is_completed ? ' • Completed' : ''}</span>
                </div>
              </div>
            `).join('');
        } catch (_) {
            bountyList.innerHTML = `<div class="bounty-item"><div><h3>Could not load quests</h3><p>Please try again in a moment.</p></div></div>`;
        }
    }

    getStartedBtn.addEventListener('click', async () => {
        lumoOverlay.classList.remove('hidden');
        await loadBounties();
    });
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
    form.addEventListener('submit', e => { e.preventDefault(); nav('index.php'); });
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

// ─── Scholar's Map Renderer ──────────────────────────────────────────────────
function initScholarsMap(currentLevel) {
    const scrollContainer = document.getElementById('scholarsMapScroll');
    const canvas = document.getElementById('scholarsMapCanvas');
    if (!scrollContainer || !canvas) return;

    const mapNodesCount = 100;
    const xSpacing = 220;
    const mapHeight = 420;
    const totalWidth = 120 + (mapNodesCount - 1) * xSpacing + 120;
    const bgTileWidth = 700;
    const bgTileCount = Math.ceil(totalWidth / bgTileWidth);

    canvas.style.width = totalWidth + 'px';
    canvas.style.height = mapHeight + 'px';

    const getNodePosition = (index) => {
        const x = 120 + index * xSpacing;
        const amplitude = 15;
        const baseY = 240;
        const y = baseY + (index % 2 === 0 ? -amplitude : amplitude);
        return { x, y };
    };

    // 1. Render Background Tiles
    let bgHTML = `<div class="sm-bg-container" style="position:absolute;inset:0;background:#1c241d;overflow:hidden;pointer-events:none;">`;
    for (let i = 0; i < bgTileCount; i++) {
        const isMirrored = i % 2 === 1;
        bgHTML += `
            <div class="sm-bg-tile" style="position:absolute;top:0;left:${i * bgTileWidth}px;width:${bgTileWidth}px;height:${mapHeight}px;">
                <img src="public/assets/images/scholarmap2.png" alt="" draggable="false" style="width:100%;height:100%;object-fit:cover;opacity:0.9;transform:${isMirrored ? 'scaleX(-1)' : 'none'};filter:saturate(0.95) brightness(0.85);">
                <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(28,36,29,0.4),transparent 10%,transparent 90%,rgba(28,36,29,0.4));"></div>
            </div>`;
    }
    bgHTML += `</div>`;

    // 2. Render Overlays & Mist
    bgHTML += `
        <div style="position:absolute;inset:0;background:rgba(0,0,0,0.2);pointer-events:none;"></div>
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.2),transparent,rgba(0,0,0,0.25));pointer-events:none;"></div>
        <div style="position:absolute;inset:x-0;top:0;height:6rem;background:linear-gradient(to bottom,rgba(0,0,0,0.35),transparent);pointer-events:none;"></div>
        <div style="position:absolute;inset:x-0;bottom:0;height:7rem;background:linear-gradient(to top,rgba(0,0,0,0.3),transparent);pointer-events:none;"></div>
    `;

    // 3. Build Trail Path
    const points = [];
    for (let i = 0; i < mapNodesCount; i++) points.push(getNodePosition(i));

    let pathD = `M ${points[0].x} ${points[0].y}`;
    for (let i = 1; i < points.length; i++) {
        const prev = points[i - 1];
        const curr = points[i];
        const midX = prev.x + (curr.x - prev.x) * 0.5;
        pathD += ` C ${midX} ${prev.y}, ${midX} ${curr.y}, ${curr.x} ${curr.y}`;
    }

    const svgHTML = `
        <svg style="position:absolute;inset:0;pointer-events:none;" width="${totalWidth}" height="${mapHeight}" viewBox="0 0 ${totalWidth} ${mapHeight}">
            <path d="${pathD}" fill="none" stroke="rgba(20, 15, 5, 0.45)" stroke-width="24" stroke-linecap="round" stroke-linejoin="round" />
            <path d="${pathD}" fill="none" stroke="rgba(80, 50, 30, 0.75)" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" />
            <path d="${pathD}" fill="none" stroke="rgba(160, 110, 40, 0.9)" stroke-width="13" stroke-linecap="round" stroke-linejoin="round" />
            <path d="${pathD}" fill="none" stroke="rgba(255, 215, 100, 0.45)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="7 12" />
        </svg>
    `;

    // 4. Render Nodes
    const activeIndex = Math.max(0, currentLevel - 1);
    const heroIndex = Math.min(activeIndex, mapNodesCount - 1);

    let nodesHTML = '';
    for (let i = 0; i < mapNodesCount; i++) {
        const { x, y } = getNodePosition(i);
        const level = i + 1;
        const unlocked = currentLevel >= level;
        const isHero = i === heroIndex;
        const isCompleted = unlocked && !isHero;

        nodesHTML += `
            <div class="sm-node-wrap" style="position:absolute;left:${x}px;top:${y}px;transform:translate(-50%,-50%);z-index:${isHero ? 20 : 10};">
                ${isHero ? `
                <div class="sm-hero-indicator" style="margin-bottom:0.25rem;position:relative;display:flex;justify-content:center;">
                    <div class="sm-ping-ring"></div>
                    <div class="sm-hero-icon">📚</div>
                </div>` : ''}
                <div class="sm-node-circle" data-state="${isHero ? 'hero' : isCompleted ? 'completed' : 'locked'}">
                    ${isCompleted ? '✓' : unlocked || isHero ? '⚔️' : '🔒'}
                </div>
                <div class="sm-node-label" data-state="${isHero ? 'hero' : unlocked ? 'unlocked' : 'locked'}">
                    Lv${level}
                </div>
            </div>`;
    }

    // 5. Render Particles
    let particlesHTML = '';
    for (let i = 0; i < 18; i++) {
        particlesHTML += `
            <div class="sm-particle" style="
                width:${4 + (i % 3)}px; height:${4 + (i % 3)}px;
                left:${4 + i * 5}%; top:${18 + (i % 4) * 16}%;
                animation-duration:${2.5 + (i % 3)}s; animation-delay:${i * 0.25}s;
            "></div>`;
    }

    canvas.innerHTML = bgHTML + svgHTML + nodesHTML + particlesHTML;

    // 6. Interaction: Drag to Scroll
    let isDragging = false;
    let startX, scrollLeft;

    scrollContainer.addEventListener('mousedown', (e) => {
        isDragging = true;
        scrollContainer.classList.add('is-grabbing');
        startX = e.pageX - scrollContainer.offsetLeft;
        scrollLeft = scrollContainer.scrollLeft;
    });

    scrollContainer.addEventListener('mouseleave', () => {
        isDragging = false;
        scrollContainer.classList.remove('is-grabbing');
    });

    scrollContainer.addEventListener('mouseup', () => {
        isDragging = false;
        scrollContainer.classList.remove('is-grabbing');
    });

    scrollContainer.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - scrollContainer.offsetLeft;
        const walk = (x - startX) * 1.5;
        scrollContainer.scrollLeft = scrollLeft - walk;
    });

    // 7. Initial Scroll to Hero
    setTimeout(() => {
        const { x } = getNodePosition(heroIndex);
        scrollContainer.scrollTo({
            left: x - scrollContainer.clientWidth / 2,
            behavior: 'smooth'
        });
    }, 100);
}

// ═══════════════════════════════════════════════════════════════════════════════
//  PROFILE PAGE  (live stats via /api/user/profile when logged in)
// ═══════════════════════════════════════════════════════════════════════════════
window.LX_applyProfileStats = function (o) {
    if (!o) return;
    const coins = o.coins != null ? o.coins : o.newCoins;
    const level = o.level != null ? o.level : o.newLevel;
    if (coins != null && Number.isFinite(Number(coins))) {
        lxProfileRuntime.coins = Number(coins);
    }
    if (level != null && Number.isFinite(Number(level))) {
        lxProfileRuntime.level = Number(level);
    }
    if (o.name) {
        window.__lxProfileName = o.name;
        const nm = String(o.name);
        ['profileDisplayName', 'hoverCardUserName', 'headerUserName'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = nm;
        });
        const iniEl = document.getElementById('avatarInitials');
        if (iniEl) {
            const parts = nm.trim().split(/\s+/).filter(Boolean);
            const ini = parts.slice(0, 2).map(p => p[0].toUpperCase()).join('') || 'R';
            iniEl.textContent = ini;
        }
    }
    if (coins != null) {
        ['profileCoins', 'coinCount'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = Number(coins).toLocaleString();
        });
    }
    if (o.booksRead != null) {
        const el = document.getElementById('booksReadCount');
        if (el) el.textContent = String(o.booksRead);
    }
    if (level != null) {
        const hb = document.getElementById('hoverLevelBadge');
        if (hb) hb.textContent = 'LVL ' + level;
        if (document.getElementById('scholarsMapCanvas')) {
            initScholarsMap(Number(level));
        }
    }
};

/** Circular reading ring: total hours vs daily goal (from /api/user/profile). */
window.LX_applyReadingRing = function (apiData) {
    if (!apiData) return;
    const svgEl = document.getElementById('circularSvg');
    const label = document.getElementById('circularLabel');
    const cap = document.getElementById('readingGoalCaption');
    if (!svgEl) return;
    const goal = Number(apiData.dailyReadingGoalHours) || 4;
    const totalH = Number(apiData.totalReadingHours) || 0;
    const r = 40;
    const circ = 2 * Math.PI * r;
    const pct = goal > 0 ? Math.min((totalH / goal) * 100, 100) : 0;
    const offset = circ - (pct / 100) * circ;
    svgEl.innerHTML = `
      <circle cx="50" cy="50" r="40" fill="none" stroke="hsl(30,20%,22%)" stroke-width="6"/>
      <circle cx="50" cy="50" r="40" fill="none" stroke="hsl(38,75%,55%)" stroke-width="6"
        stroke-linecap="round" stroke-dasharray="${circ}" stroke-dashoffset="${offset}"
        class="transition-all" style="transition:stroke-dashoffset .7s"/>`;
    if (label) {
        const disp = totalH >= 10 ? totalH.toFixed(1) : (totalH % 1 === 0 ? String(totalH) : totalH.toFixed(1));
        label.textContent = disp + 'h';
    }
    if (cap) cap.textContent = 'of ' + goal + 'h goal';
};

/**
 * Re-renders My Library + My List from LexoraState (after profile fetch or Start Reading purchase).
 * Safe to call from book-detail: no-ops if #libraryGrid / #planGrid are absent.
 */
function renderProfileShelvesFromLexoraState() {
    const libGrid = document.getElementById('libraryGrid');
    const listGrid = document.getElementById('planGrid');
    const userBooksAll = window.LexoraState ? window.LexoraState.getUserBooks() : [];
    const library = userBooksAll.filter(u => u.status === 'reading' || u.status === 'completed');

    if (libGrid) {
        if (library.length === 0) {
            libGrid.innerHTML = `<div class="empty-library-card">
        <p class="empty-library-msg">No books yet. Start exploring and add some!</p>
        <p class="empty-library-hint" style="margin:.5rem 0 1rem;font-size:.9rem;color:var(--muted-foreground)">Books you purchase or unlock appear here.</p>
        <a href="${LX_API_BASE}/user#catalog" class="btn-primary empty-library-cta">Browse the catalog</a>
      </div>`;
        } else {
            libGrid.innerHTML = library.map(ub => {
                const cover = genreCovers[ub.book.genre] || ub.book.cover || '';
                const tags = genreTagsHTML(ub.book);
                const year = publicationYearHTML(ub.book);
                const badgeCls = ub.status === 'completed' ? 'completed' : 'reading';
                const badgeTxt = ub.status === 'completed' ? '✓ DONE' : 'READING';
                let progressPct = Number(ub.progress ?? 0) || 0;
                if (window.LexoraState && ub.status === 'reading') {
                    const total = window.LexoraState.getBookPages(ub.book.id).length;
                    const raw = window.LexoraState.getBookProgress(ub.book.id);
                    const savedPage = typeof raw === 'number' && Number.isFinite(raw)
                        ? Math.max(0, Math.min(Math.floor(raw), Math.max(0, total - 1)))
                        : 0;
                    const localPct = total > 0
                        ? Math.min(100, Math.round(((savedPage + 1) / total) * 100))
                        : (ub.progress ?? 0);
                    progressPct = Math.max(progressPct, Number(localPct) || 0);
                }
                const progress = (ub.status === 'reading' && progressPct > 0 && progressPct < 100) ? `
        <div class="progress-bar-wrap"><div class="progress-bar" style="width:${progressPct}%"></div></div>
        <p style="font-family:'Press Start 2P';font-size:.44rem;color:var(--muted-foreground);text-align:right">${progressPct}%</p>` : '';
                return `<div class="book-card-static" role="link" data-book-id="${ub.book.id}" style="cursor:pointer">
        <div class="cover-wrap">
          <img src="${cover}" alt="${ub.book.title}" loading="lazy">
          <div class="cover-fade"></div>
          <span class="status-badge ${badgeCls}">${badgeTxt}</span>
        </div>
        <div class="card-body">
          <h3 class="line-clamp-1">${ub.book.title}</h3>
          <p class="line-clamp-1">${ub.book.author}</p>
          ${year}
          ${progress}
          ${tags}
        </div>
      </div>`;
            }).join('');
            libGrid.querySelectorAll('.book-card-static[data-book-id]').forEach(el => {
                el.addEventListener('click', () => {
                    const bid = el.getAttribute('data-book-id');
                    if (bid) nav(`${LX_API_BASE}/book-detail?id=${bid}`);
                });
            });
        }
    }

    const planToRead = userBooksAll.filter(u => u.status === 'plan-to-read');
    if (listGrid) {
        if (planToRead.length === 0) {
            listGrid.innerHTML = `<div style="border:1px dashed var(--border);border-radius:.75rem;background:hsl(24,20%,14%/.5);padding:3rem;text-align:center">
        <p style="font-family:'Press Start 2P';font-size:.75rem;color:var(--muted-foreground);margin-bottom:.75rem">No books yet. Start exploring and add some!</p>
        <p style="font-size:.9rem;color:var(--muted-foreground);margin-bottom:1.25rem">Use &ldquo;Add to list&rdquo; on a book to save it here before you buy.</p>
        <a href="${LX_API_BASE}/user#catalog" class="btn-primary" style="display:inline-block;padding:.75rem 1.5rem">Browse the catalog</a>
      </div>`;
        } else {
            listGrid.innerHTML = planToRead.map(ub => {
                const cover = genreCovers[ub.book.genre] || ub.book.cover || '';
                const tags = genreTagsHTML(ub.book);
                const year = publicationYearHTML(ub.book);
                return `<div class="book-card-static" role="link" data-book-id="${ub.book.id}" style="cursor:pointer">
          <div class="cover-wrap">
            <img src="${cover}" alt="${ub.book.title}" loading="lazy">
            <div class="cover-fade"></div>
            <span class="status-badge plan">PLAN</span>
          </div>
          <div class="card-body">
            <h3 class="line-clamp-1">${ub.book.title}</h3>
            <p class="line-clamp-1">${ub.book.author}</p>
            ${year}
            ${tags}
          </div>
        </div>`;
            }).join('');
            listGrid.querySelectorAll('.book-card-static[data-book-id]').forEach(el => {
                el.addEventListener('click', () => {
                    const bid = el.getAttribute('data-book-id');
                    if (bid) nav(`${LX_API_BASE}/book-detail?id=${bid}`);
                });
            });
        }
    }
}

window.LX_renderProfileShelvesFromLexoraState = renderProfileShelvesFromLexoraState;

function initProfile() {
    const bootstrapLibrary = Array.isArray(window.__lxLibrary) ? window.__lxLibrary : [];
    if (bootstrapLibrary.length > 0 && window.LexoraState?.applyLibraryFromServer) {
        window.LexoraState.applyLibraryFromServer(bootstrapLibrary);
    }

    if (window.LX_applyReadingRing) {
        window.LX_applyReadingRing({
            totalReadingHours: 0,
            dailyReadingGoalHours: 4,
        });
    }

    (async function loadProfileUi() {
        const libPre = document.getElementById('libraryGrid');
        const listPre = document.getElementById('planGrid');
        const profileDisplayNameEl = document.getElementById('profileDisplayName');
        if (libPre) libPre.innerHTML = '';
        if (listPre) listPre.innerHTML = '';

        let apiData = null;
        let profileLoadError = '';
        try {
            const j = await lxApi('/api/user/profile', 'GET');
            if (j && j.data) apiData = j.data;
        } catch (e) {
            profileLoadError = (e && e.message) ? String(e.message) : 'Could not load profile data.';
        }

        if (apiData) {
            if (profileDisplayNameEl) profileDisplayNameEl.removeAttribute('title');
            window.LX_applyProfileStats({
                name: apiData.name,
                coins: apiData.coins,
                level: apiData.level,
                booksRead: apiData.booksRead,
            });
            if (window.LX_applyReadingRing) window.LX_applyReadingRing(apiData);
            if (document.getElementById('scholarsMapCanvas')) {
                initScholarsMap(Number(apiData.level) || 1);
            }
            if (window.LexoraState?.applyLibraryFromServer) {
                window.LexoraState.applyLibraryFromServer(Array.isArray(apiData.library) ? apiData.library : []);
            }
            // Fallback: if API sent rows but strict mapper dropped them, keep a permissive mapping
            // so shelves are never silently empty.
            const rawLibraryRows = Array.isArray(apiData.library) ? apiData.library : [];
            const mappedAfterSync = window.LexoraState?.getUserBooks ? window.LexoraState.getUserBooks() : [];
            if (rawLibraryRows.length > 0 && mappedAfterSync.length === 0 && window.LexoraState?.saveUserBooks) {
                const fallbackBooks = rawLibraryRows.map((row) => {
                    const bid = Number(row?.book_id || row?.book?.id || 0);
                    if (!bid) return null;
                    const rawBook = row?.book || {};
                    const status = row?.status === 'plan_to_read' ? 'plan-to-read' : String(row?.status || '');
                    if (!['reading', 'completed', 'plan-to-read'].includes(status)) return null;
                    const book = {
                        id: bid,
                        title: String(rawBook.title || `Book #${bid}`),
                        author: String(rawBook.author || 'Unknown Author'),
                        genre: String(rawBook.genre || ''),
                        trending: !!rawBook.trending,
                        description: String(rawBook.description || ''),
                        audience: String(rawBook.audience || 'All'),
                        cover: String(rawBook.cover || '📖'),
                    };
                    const progressPage = Number(row?.progress_page || 0);
                    const progress = status === 'completed'
                        ? 100
                        : Math.max(0, Math.min(100, Number(row?.progress ?? (progressPage > 0 ? Math.min(99, progressPage * 4) : 0))));
                    return { book, status, progress };
                }).filter(Boolean);
                if (fallbackBooks.length > 0) {
                    window.LexoraState.saveUserBooks(fallbackBooks);
                }
            }
        } else {
            // Keep existing local shelf state instead of force-clearing on transient API failures.
            if (profileDisplayNameEl) {
                profileDisplayNameEl.title = 'Live profile sync unavailable. Showing local data.';
            }
            if (libPre && profileLoadError) {
                const err = profileLoadError.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                const html = `<div style="border:1px dashed var(--destructive);border-radius:.75rem;background:hsl(24,20%,14%/.5);padding:1.25rem;text-align:left">
        <p style="font-family:'Press Start 2P';font-size:.56rem;color:var(--destructive);margin-bottom:.5rem">PROFILE SYNC ERROR</p>
        <p style="font-size:.9rem;color:var(--foreground)">${err}</p>
      </div>`;
                libPre.innerHTML = html;
                if (listPre) listPre.innerHTML = html;
            }
            initScholarsMap(lxProfileRuntime.level != null ? lxProfileRuntime.level : 1);
            const coinEl = document.getElementById('profileCoins');
            const coinHead = document.getElementById('coinCount');
            const fallbackCoins = lxProfileRuntime.coins != null ? lxProfileRuntime.coins : 0;
            if (coinEl) coinEl.textContent = fallbackCoins.toLocaleString();
            if (coinHead) coinHead.textContent = fallbackCoins.toLocaleString();
        }

        if (document.getElementById('bookDetailMain')) {
            initBookDetail();
        }

        renderProfileShelvesFromLexoraState();
        // Last-resort rendering path: if API has rows but state rendering still looks empty,
        // render directly from API payload so shelves are visible.
        if (apiData && Array.isArray(apiData.library) && apiData.library.length > 0 && libPre && listPre) {
            const hasRenderedCards = !!document.querySelector('#libraryGrid .book-card-static, #planGrid .book-card-static');
            if (!hasRenderedCards) {
                const normalize = (s) => {
                    if (s === 'plan_to_read' || s === 'plan-to-read') return 'plan-to-read';
                    return s;
                };
                const rows = apiData.library.map((row) => {
                    const bid = Number(row?.book_id || row?.book?.id || 0);
                    if (!bid) return null;
                    const raw = row?.book || {};
                    return {
                        id: bid,
                        title: String(raw.title || `Book #${bid}`),
                        author: String(raw.author || 'Unknown Author'),
                        publicationYear: Number(raw.publicationYear || raw.publication_year || 0),
                        genre: String(raw.genre || ''),
                        genres: Array.isArray(raw.genres) ? raw.genres : [],
                        status: normalize(String(row?.status || '')),
                    };
                }).filter(Boolean);

                const libRows = rows.filter((r) => r.status === 'reading' || r.status === 'completed');
                const listRows = rows.filter((r) => r.status === 'plan-to-read');

                const cardHtml = (r, badgeHtml = '') => {
                    const cover = genreCovers[r.genre] || '';
                    const tags = genreTagsHTML(r);
                    const year = publicationYearHTML(r);
                    return `<div class="book-card-static" role="link" data-book-id="${r.id}" style="cursor:pointer">
          <div class="cover-wrap">
            <img src="${cover}" alt="${r.title}" loading="lazy">
            <div class="cover-fade"></div>
            ${badgeHtml}
          </div>
          <div class="card-body">
            <h3 class="line-clamp-1">${r.title}</h3>
            <p class="line-clamp-1">${r.author}</p>
            ${year}
            ${tags || `<span class="genre-tag" style="background:var(--secondary);color:var(--secondary-foreground)">General</span>`}
          </div>
        </div>`;
                };

                if (libRows.length > 0) {
                    libPre.innerHTML = libRows.map((r) => cardHtml(
                        r,
                        `<span class="status-badge ${r.status === 'completed' ? 'completed' : 'reading'}">${r.status === 'completed' ? '✓ DONE' : 'READING'}</span>`
                    )).join('');
                }
                if (listPre && listRows.length > 0) {
                    listPre.innerHTML = listRows.map((r) => cardHtml(
                        r,
                        `<span class="status-badge plan">PLAN</span>`
                    )).join('');
                }
                document.querySelectorAll('#libraryGrid .book-card-static[data-book-id], #planGrid .book-card-static[data-book-id]').forEach(el => {
                    el.addEventListener('click', () => {
                        const bid = el.getAttribute('data-book-id');
                        if (bid) nav(`${LX_API_BASE}/book-detail?id=${bid}`);
                    });
                });
            }
        }

        const booksReadEl = document.getElementById('booksReadCount');
        const userBooksAll = window.LexoraState ? window.LexoraState.getUserBooks() : [];
        const completedOnly = userBooksAll.filter(u => u.status === 'completed').length;
        if (booksReadEl && !apiData) booksReadEl.textContent = completedOnly;

        const questBtn = document.getElementById('claimQuestRewardBtn');
        const questMsg = document.getElementById('coinSystemMsg');
        if (questBtn) {
            questBtn.disabled = true;
            questBtn.textContent = 'AUTO REWARDS ENABLED';
            if (questMsg) questMsg.textContent = 'Quests are rewarded automatically when objectives are completed.';
        }

        const myLbRoot = document.getElementById('profileLeaderboardRows');
        const myLbRank = document.getElementById('profileLeaderboardRank');
        const myLbSearchInput = document.getElementById('leaderboardSearchInput');
        const myLbSearchBtn = document.getElementById('leaderboardSearchBtn');
        const myLbResetBtn = document.getElementById('leaderboardSearchResetBtn');
        const myLbSearchMsg = document.getElementById('leaderboardSearchMsg');
        if (myLbRoot) {
            const escLb = (s) => String(s ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/"/g, '&quot;');
            const renderLbRows = (rows) => {
                if (!Array.isArray(rows) || rows.length === 0) {
                    myLbRoot.innerHTML = `
                    <tr class="profile-lb-table__empty">
                      <td colspan="5">No leaderboard window yet. Keep reading to climb the ranks.</td>
                    </tr>`;
                    return;
                }
                myLbRoot.innerHTML = rows.map(r => `
                    <tr class="profile-lb-table__row${r.isCurrentUser ? ' profile-lb-table__row--you' : ''}">
                      <td class="profile-lb-table__cell--rank"><span class="profile-lb-table__rank">#${r.rank}</span></td>
                      <td><span class="profile-lb-table__name">${escLb(r.nom)}</span></td>
                      <td class="profile-lb-table__cell-num"><span class="profile-lb-table__score-val">${Number(r.xp || 0).toLocaleString()}</span><span class="profile-lb-table__score-suffix">xp</span></td>
                      <td class="profile-lb-table__cell-num">${Number(r.books_read || 0).toLocaleString()}</td>
                      <td class="profile-lb-table__cell-num"><span class="profile-lb-table__level">Lv.${Number(r.level || 1)}</span></td>
                    </tr>`).join('');
            };
            const setLbMsg = (text, isError = false) => {
                if (!myLbSearchMsg) return;
                myLbSearchMsg.textContent = text || '';
                myLbSearchMsg.style.color = isError ? 'var(--destructive)' : 'var(--muted-foreground)';
            };
            let myRankData = null;
            const loadMyLb = async () => {
                const lb = await lxApi('/api/leaderboard/me', 'GET');
                const data = lb.data || {};
                myRankData = data;
                const rk = data.rank != null && data.rank !== '' ? `#${data.rank}` : '—';
                if (myLbRank) myLbRank.textContent = rk;
                renderLbRows(Array.isArray(data.window) ? data.window : []);
            };
            try {
                await loadMyLb();
            } catch (_) {
                if (myLbRank) myLbRank.textContent = '—';
                myLbRoot.innerHTML = `
                    <tr class="profile-lb-table__empty">
                      <td colspan="5">Leaderboard could not be loaded.</td>
                    </tr>`;
            }

            const searchLeaderboardUser = async () => {
                const q = (myLbSearchInput?.value || '').trim();
                if (!q) {
                    setLbMsg('Enter a reader name to search.', true);
                    return;
                }
                try {
                    const lb = await lxApi(`/api/leaderboard/search?q=${encodeURIComponent(q)}`, 'GET');
                    const data = lb.data || {};
                    const rk = data.rank != null && data.rank !== '' ? `#${data.rank}` : '—';
                    if (myLbRank) myLbRank.textContent = rk;
                    renderLbRows(Array.isArray(data.window) ? data.window : []);
                    setLbMsg(`Showing ranking around "${q}".`);
                } catch (e) {
                    setLbMsg((e && e.message) ? String(e.message) : 'User not found in leaderboard.', true);
                }
            };

            if (myLbSearchBtn) {
                myLbSearchBtn.addEventListener('click', () => { searchLeaderboardUser(); });
            }
            if (myLbSearchInput) {
                myLbSearchInput.addEventListener('keydown', (ev) => {
                    if (ev.key === 'Enter') {
                        ev.preventDefault();
                        searchLeaderboardUser();
                    }
                });
            }
            if (myLbResetBtn) {
                myLbResetBtn.addEventListener('click', async () => {
                    if (myLbSearchInput) myLbSearchInput.value = '';
                    setLbMsg('');
                    if (myRankData) {
                        const rk = myRankData.rank != null && myRankData.rank !== '' ? `#${myRankData.rank}` : '—';
                        if (myLbRank) myLbRank.textContent = rk;
                        renderLbRows(Array.isArray(myRankData.window) ? myRankData.window : []);
                        return;
                    }
                    try {
                        await loadMyLb();
                    } catch (_) {
                        if (myLbRank) myLbRank.textContent = '—';
                    }
                });
            }
        }
    })();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  BOOK DETAIL PAGE
// ═══════════════════════════════════════════════════════════════════════════════
function showDetailToast(message) {
    let el = document.getElementById('detailToast');
    if (!el) {
        el = document.createElement('div');
        el.id = 'detailToast';
        el.className = 'detail-toast';
        document.body.appendChild(el);
    }
    el.textContent = message;
    el.classList.add('detail-toast--visible');
    clearTimeout(showDetailToast._t);
    showDetailToast._t = setTimeout(() => el.classList.remove('detail-toast--visible'), 2800);
}

function initBookDetail() {
    const mainEl = document.getElementById('bookDetailMain');
    if (!mainEl) return;

    const params = new URLSearchParams(window.location.search);
    if (params.get('access_denied') === '1' || params.get('access_error') === '1') {
        showDetailToast('You cannot buy this book. Try to fulfill your quests or buy coins.');
        try {
            const u = new URL(window.location.href);
            u.searchParams.delete('access_denied');
            u.searchParams.delete('access_error');
            window.history.replaceState({}, '', u.pathname + u.search);
        } catch (_) { /* ignore */ }
    }
    if (params.get('book_missing') === '1') {
        showDetailToast('This book is missing from the database. Run database/migrations/004_lexora_catalog_books_seed.sql (or ask an admin).');
        try {
            const u = new URL(window.location.href);
            u.searchParams.delete('book_missing');
            window.history.replaceState({}, '', u.pathname + u.search);
        } catch (_) { /* ignore */ }
    }

    const id = parseInt(params.get('id'));
    const book = books.find(b => b.id === id);

    if (!book) {
        mainEl.innerHTML = `<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;text-align:center">
      <h1 style="font-size:2rem">Book not found</h1>
      <a href="index.php" class="btn-outline">Return Home</a>
    </div>`;
        return;
    }

    const St = window.LexoraState;
    const cover = genreCovers[book.genre];
    const tags = genreTagsHTML(book);
    const pubYear = getPublicationYear(book);
    const price = bookPrices[book.id];
    const userBook = St ? St.getBookStatus(book.id) : null;
    const totalPages = St ? St.getBookPages(book.id).length : 24;
    const maxIdx = Math.max(0, totalPages - 1);
    const rawSaved = St ? St.getBookProgress(book.id) : 0;
    const savedPage0 = typeof rawSaved === 'number' && Number.isFinite(rawSaved)
        ? Math.max(0, Math.min(Math.floor(rawSaved), maxIdx))
        : 0;
    const hasStarted = savedPage0 > 0;
    const isCompleted = userBook?.status === 'completed';
    const isReading = hasStarted || userBook?.status === 'reading';
    const localReadingPercent = (userBook?.status === 'reading' || savedPage0 > 0) && totalPages > 0
        ? Math.min(100, Math.round(((savedPage0 + 1) / totalPages) * 100))
        : 0;
    const apiReadingPercent = Number(userBook?.progress ?? 0) || 0;
    const readingPercent = Math.max(localReadingPercent, apiReadingPercent);

    const progressBlock = isReading && readingPercent > 0 && !isCompleted ? `
    <div style="max-width:24rem" class="detail-reading-progress">
      <div style="display:flex;justify-content:space-between;margin-bottom:.5rem">
        <span style="color:var(--muted-foreground);font-size:.9rem">Reading Progress</span>
        <span style="color:var(--primary);font-weight:600">${readingPercent}% · Page ${savedPage0 + 1}/${totalPages}</span>
      </div>
      <div class="progress-bar-wrap"><div class="progress-bar" style="width:${readingPercent}%"></div></div>
    </div>` : '';
    const completedBadge = isCompleted ? `<span class="completed-badge">✓ COMPLETED</span>` : '';

    const bootstrapLibraryRows = Array.isArray(window.__lxLibrary) ? window.__lxLibrary : [];
    const inLibraryFromBootstrap = bootstrapLibraryRows.some((row) => {
        const bid = Number(row?.book_id || row?.book?.id || 0);
        const st = String(row?.status || '').toLowerCase();
        return bid === book.id && (st === 'reading' || st === 'completed');
    });
    const inLibrary = (St ? St.isInLibrary(book.id) : false)
        || inLibraryFromBootstrap
        || !!(userBook && (userBook.status === 'reading' || userBook.status === 'completed'));
    const inList = St ? St.isInList(book.id) : (userBook?.status === 'plan-to-read' || userBook?.status === 'plan_to_read');
    const ctaText = inLibrary
        ? (isCompleted ? 'READ AGAIN' : (hasStarted ? 'CONTINUE READING' : 'START READING'))
        : 'ADD TO LIBRARY';

    const listAction = inList ? 'remove-list' : 'add-list';
    const addListBtnLabel = inList ? 'REMOVE FROM LIST' : 'ADD TO LIST';
    const addListBtn = `<button type="button" class="btn-detail-secondary" data-action="${listAction}">${addListBtnLabel}</button>`;

    document.title = `${book.title} — Lexora`;

    mainEl.innerHTML = `
  <div id="detailToast" class="detail-toast" aria-live="polite"></div>
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
        ${tags}
        <h1 style="margin-top:.5rem">${book.title}</h1>
        <p class="byline">by ${book.author}</p>
        ${pubYear ? `<p class="book-pub-year">Published: ${pubYear}</p>` : ''}
      </div>
      <p class="description">${book.description}</p>
      <div class="reward-chips">
        <div class="chip">${SVG.coins}<div><p class="chip-label">COST</p><p class="chip-value">${price?.cost.toLocaleString() ?? 'FREE'}</p></div></div>
        <div class="chip">${SVG.star}<div><p class="chip-label">EARN</p><p class="chip-value">+${price?.xpReward ?? 50} XP · +${price?.coinReward ?? 100} Coins</p></div></div>
      </div>
      ${progressBlock}
      ${completedBadge}
      <div class="detail-actions">
        <button type="button" class="cta-btn" id="detailReadCta">${SVG.bookOpen} ${ctaText}</button>
        ${addListBtn}
      </div>
      <p id="detailActionStatus" style="min-height:1.2rem;color:var(--muted-foreground);font-size:.9rem;margin-top:.35rem"></p>
    </div>
  </section>

  <div style="border-top:1px solid var(--border)"></div>

  <section class="community-section">
    <div class="community-header">
      <div>
        <h2>Book Community</h2>
        <p class="community-sub">Join the discussion about <span>${book.title}</span></p>
      </div>
      <div class="sort-tabs" id="sortTabs">
        <button class="active" data-sort="top">${SVG.arrowUp} Top</button>
        <button data-sort="recent">${SVG.clock} Recent</button>
        <button data-sort="trending">${SVG.trending} Trending</button>
      </div>
    </div>
    <div id="createPostArea">
      <button class="create-post-trigger" id="createPostTrigger">
        <span class="avatar-pill cp-avatar">${(_initials(lxResolvedUserName()))}</span>
        <span class="cp-placeholder">Share your thoughts about this book…</span>
      </button>
      <div class="create-post-form hidden" id="createPostForm">
        <div class="cp-author-row">
          <span class="avatar-pill cp-avatar">${(_initials(lxResolvedUserName()))}</span>
          <span class="cp-author-name">${lxResolvedUserName()}</span>
        </div>
        <input id="cpTitle" class="post-composer-input" placeholder="Post title…" maxlength="120">
        <textarea id="cpContent" class="post-composer-textarea" placeholder="What are your thoughts?" rows="3" maxlength="2000"></textarea>
        <div class="cp-footer">
          <div class="cp-tags" id="cpTags">
            <span class="cp-tag-label">TAG:</span>
            <button class="cp-tag active" data-tag="">NONE</button>
            <button class="cp-tag" data-tag="discussion">DISCUSSION</button>
            <button class="cp-tag" data-tag="review">REVIEW</button>
            <button class="cp-tag" data-tag="theory">THEORY</button>
            <button class="cp-tag" data-tag="spoiler">SPOILER</button>
          </div>
          <div class="cp-btns">
            <button class="btn-outline cp-cancel" id="cpCancel">CANCEL</button>
            <button class="cta-btn cp-publish" id="cpPublish">PUBLISH</button>
          </div>
        </div>
      </div>
    </div>
    <div class="posts-list" id="postsList"></div>
  </section>`;

    const readCta = document.getElementById('detailReadCta');
    const detailActionStatus = document.getElementById('detailActionStatus');
    const INSUFFICIENT_MSG = 'You cannot buy this book. Try to fulfill your quests or buy coins.';
    let actionInFlight = false;
    function setActionStatus(message, isError = false) {
        if (!detailActionStatus) return;
        detailActionStatus.textContent = message || '';
        detailActionStatus.style.color = isError ? 'var(--destructive)' : 'var(--muted-foreground)';
    }

    async function syncProfileAfterBookAction() {
        const profile = await lxApi('/api/user/profile', 'GET');
        if (window.LexoraState?.applyLibraryFromServer) {
            window.LexoraState.applyLibraryFromServer(
                Array.isArray(profile?.data?.library) ? profile.data.library : []
            );
        }
        if (profile?.data && window.LX_applyProfileStats) {
            window.LX_applyProfileStats({
                name: profile.data.name,
                coins: profile.data.coins,
                level: profile.data.level,
                booksRead: profile.data.booksRead,
            });
        }
        renderProfileShelvesFromLexoraState();
    }

    updateBookDetailHeaderNav(book.id, !!isReading);

    mainEl.addEventListener('click', async (event) => {
        const targetBtn = event.target.closest('button');
        if (!targetBtn || !mainEl.contains(targetBtn) || actionInFlight) return;

        if (targetBtn.id === 'detailReadCta') {
            if (inLibrary) {
                nav(`${LX_API_BASE}/read-book?id=${book.id}`);
                return;
            }
            const prevHtml = targetBtn.innerHTML;
            targetBtn.disabled = true;
            actionInFlight = true;
            targetBtn.innerHTML = `${SVG.bookOpen} PROCESSING...`;
            setActionStatus('Processing purchase...');
            try {
                const purchase = await lxApi('/api/user/book/purchase', 'POST', { book_id: book.id });
                if (!purchase.already_in_library && window.LexoraState?.addToLibrary) {
                    window.LexoraState.addToLibrary(book.id);
                }
                if (typeof purchase.newCoins === 'number' && window.LX_applyProfileStats) {
                    window.LX_applyProfileStats({ coins: purchase.newCoins });
                }
                renderProfileShelvesFromLexoraState();
                try {
                    await syncProfileAfterBookAction();
                } catch (_) {
                    // Do not fail the user-visible success flow if profile refresh JSON is temporarily broken.
                }
                if (purchase?.already_in_library) {
                    showDetailToast('This book is already in My Library.');
                    setActionStatus('Already in your library.');
                } else {
                    const spent = Number(purchase.coinsSpent) || 0;
                    showDetailToast(
                        spent > 0
                            ? `"${book.title}" added to My Library (−${spent} coins).`
                            : `"${book.title}" is now in My Library.`
                    );
                    setActionStatus(
                        spent > 0
                            ? `Added to library. Spent ${spent} coins.`
                            : 'Added to library.'
                    );
                }
                initBookDetail();
            } catch (e) {
                const msg = (e && e.message) ? String(e.message) : '';
                showDetailToast(msg.trim() ? msg : INSUFFICIENT_MSG);
                setActionStatus(msg.trim() ? msg : INSUFFICIENT_MSG, true);
            } finally {
                actionInFlight = false;
                targetBtn.disabled = false;
                targetBtn.innerHTML = prevHtml;
            }
            return;
        }

        const act = targetBtn.getAttribute('data-action');
        if (act === 'add-list') {
            const prevText = targetBtn.textContent;
            targetBtn.disabled = true;
            actionInFlight = true;
            targetBtn.textContent = 'PROCESSING...';
            setActionStatus('Adding to your list...');
            try {
                const resp = await lxApi('/api/user/book/list/add', 'POST', { book_id: book.id });
                if (resp.already_in_list) {
                    if (window.LexoraState?.addToList) window.LexoraState.addToList(book.id);
                    showDetailToast(`"${book.title}" is already in your list.`);
                    setActionStatus('Already in your list.');
                } else if (resp.in_library) {
                    showDetailToast(resp.message || 'This book is already in your library.');
                    setActionStatus(resp.message || 'This book is already in your library.');
                } else {
                    if (window.LexoraState?.addToList) window.LexoraState.addToList(book.id);
                    showDetailToast(`"${book.title}" added to your reading list.`);
                    setActionStatus('Added to your list.');
                }
                try {
                    await syncProfileAfterBookAction();
                } catch (_) {
                    // Keep add-to-list success message even if background profile sync fails.
                }
                initBookDetail();
            } catch (e) {
                showDetailToast(e.message || 'Could not update your list.');
                setActionStatus(e.message || 'Could not update your list.', true);
            } finally {
                actionInFlight = false;
                targetBtn.disabled = false;
                targetBtn.textContent = prevText;
            }
            return;
        }

        if (act === 'remove-list') {
            const prevText = targetBtn.textContent;
            targetBtn.disabled = true;
            actionInFlight = true;
            targetBtn.textContent = 'PROCESSING...';
            setActionStatus('Removing from your list...');
            try {
                await lxApi('/api/user/book/list/remove', 'POST', { book_id: book.id });
                if (window.LexoraState?.removeFromList) window.LexoraState.removeFromList(book.id);
                showDetailToast(`"${book.title}" removed from your list.`);
                setActionStatus('Removed from your list.');
                try {
                    await syncProfileAfterBookAction();
                } catch (_) {
                    // Keep remove-from-list success message even if profile sync fails.
                }
                initBookDetail();
            } catch (e) {
                showDetailToast(e.message || 'Could not update your list.');
                setActionStatus(e.message || 'Could not update your list.', true);
            } finally {
                actionInFlight = false;
                targetBtn.disabled = false;
                targetBtn.textContent = prevText;
            }
        }
    });

    // ─── Community feed helpers ───────────────────────────────────────────────
    const currentUser = lxResolvedUserName();

    function _initials(name) {
        return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
    }

    function _timeAgo(ts) {
        const diff = Date.now() - ts;
        const mins = Math.floor(diff / 60000);
        if (mins < 1) return 'just now';
        if (mins < 60) return mins + 'm ago';
        const hrs = Math.floor(mins / 60);
        if (hrs < 24) return hrs + 'h ago';
        return Math.floor(hrs / 24) + 'd ago';
    }

    const TAG_COLORS = {
        discussion: 'background:hsl(150,30%,25%);color:hsl(38,50%,90%)',
        review: 'background:hsl(38,75%,55%,.2);color:hsl(38,75%,55%)',
        theory: 'background:hsl(345,45%,30%,.3);color:hsl(38,50%,90%)',
        spoiler: 'background:hsl(0,62%,50%,.2);color:hsl(0,62%,50%)',
    };

    // Track which posts have comments expanded
    const _expandedPosts = new Set();

    function _buildCommentHTML(c, postId) {
        const isOwner = c.author === currentUser;
        const liked = c.upvotedBy.includes(currentUser);
        return `<div class="comment-item" data-comment-id="${c.id}" data-post-id="${postId}">
          <span class="avatar-pill comment-avatar">${c.avatarInitials}</span>
          <div class="comment-body">
            <div class="comment-meta">
              <span class="comment-author">${c.author}</span>
              <span class="dot">·</span>
              <span class="comment-time">${_timeAgo(c.createdAt)}</span>
            </div>
            <div class="comment-content-wrap">
              <p class="comment-content">${c.content}</p>
              <div class="comment-edit-wrap hidden">
                <textarea class="post-composer-textarea comment-edit-ta" rows="2">${c.content}</textarea>
                <div class="comment-edit-btns">
                  <button class="cp-tag" data-action="save-comment" data-comment-id="${c.id}" data-post-id="${postId}">${SVG.check} SAVE</button>
                  <button class="cp-tag" data-action="cancel-edit-comment" data-comment-id="${c.id}">${SVG.x} CANCEL</button>
                </div>
              </div>
            </div>
            <div class="comment-actions">
              <button class="upvote-btn${liked ? ' upvote-btn--active' : ''}" data-action="upvote-comment" data-comment-id="${c.id}" data-post-id="${postId}">${SVG.arrowUp} <span>${c.upvotes}</span></button>
              ${isOwner ? `
                <button class="icon-btn" data-action="edit-comment" data-comment-id="${c.id}" data-post-id="${postId}" title="Edit">${SVG.pencil}</button>
                <button class="icon-btn icon-btn--danger" data-action="delete-comment" data-comment-id="${c.id}" data-post-id="${postId}" title="Delete">${SVG.trash}</button>
              ` : ''}
            </div>
          </div>
        </div>`;
    }

    function _buildPostHTML(p) {
        const isOwner = p.author === currentUser;
        const liked = p.upvotedBy.includes(currentUser);
        const expanded = _expandedPosts.has(p.id);
        const tagStyle = p.tag ? TAG_COLORS[p.tag] || '' : '';
        const commentsHTML = expanded ? `
          <div class="post-comments" data-post-id="${p.id}">
            ${p.comments.length > 0
                ? p.comments.map(c => _buildCommentHTML(c, p.id)).join('')
                : `<p class="no-comments-msg">No comments yet. Be the first to reply!</p>`
            }
            <div class="add-comment-row">
              <textarea class="post-composer-textarea add-comment-ta" rows="1" placeholder="Write a comment…" data-post-id="${p.id}"></textarea>
              <button class="cta-btn send-comment-btn" data-action="add-comment" data-post-id="${p.id}" title="Send">${SVG.send}</button>
            </div>
          </div>` : '';

        return `<div class="post-card" data-post-id="${p.id}">
          <div class="upvote-col">
            <button class="upvote-btn${liked ? ' upvote-btn--active' : ''}" data-action="upvote-post" data-post-id="${p.id}">${SVG.arrowUp}</button>
            <span class="upvote-count">${p.upvotes}</span>
          </div>
          <div class="post-body">
            <div class="post-meta">
              <div class="avatar-pill">${p.avatarInitials}</div>
              <span class="author">${p.author}</span>
              <span class="dot">·</span>
              <span class="time">${_timeAgo(p.createdAt)}</span>
              ${p.tag ? `<span class="tag-pill" style="${tagStyle}">${p.tag}</span>` : ''}
              ${isOwner ? `<div class="post-owner-actions">
                <button class="icon-btn" data-action="edit-post" data-post-id="${p.id}" title="Edit">${SVG.pencil}</button>
                <button class="icon-btn icon-btn--danger" data-action="delete-post" data-post-id="${p.id}" title="Delete">${SVG.trash}</button>
              </div>` : ''}
            </div>
            <div class="post-view-wrap">
              <h4>${p.title}</h4>
              <p class="line-clamp-2 post-preview">${p.content}</p>
            </div>
            <div class="post-edit-wrap hidden">
              <input class="post-composer-input post-edit-title-input" value="${p.title.replace(/"/g, '&quot;')}" maxlength="120">
              <textarea class="post-composer-textarea post-edit-content-ta" rows="3" maxlength="2000">${p.content}</textarea>
              <div class="comment-edit-btns">
                <button class="cp-tag" data-action="save-post" data-post-id="${p.id}">${SVG.check} SAVE</button>
                <button class="cp-tag" data-action="cancel-edit-post" data-post-id="${p.id}">${SVG.x} CANCEL</button>
              </div>
            </div>
            <div class="post-actions">
              <button class="toggle-comments-btn" data-action="toggle-comments" data-post-id="${p.id}">${SVG.msgSquare} ${p.comments.length} comment${p.comments.length !== 1 ? 's' : ''}</button>
            </div>
            ${commentsHTML}
          </div>
        </div>`;
    }

    // Sort state
    let sortMode = 'top';

    function renderCommunityFeed() {
        let posts = communityStore.getPosts(book.id);
        if (sortMode === 'top') posts = [...posts].sort((a, b) => b.upvotes - a.upvotes);
        if (sortMode === 'recent') posts = [...posts].sort((a, b) => b.createdAt - a.createdAt);
        if (sortMode === 'trending') posts = [...posts].sort((a, b) => b.comments.length - a.comments.length);

        const list = document.getElementById('postsList');
        if (!list) return;

        if (posts.length === 0) {
            list.innerHTML = `<div class="empty-feed">
              ${SVG.msgSquare}
              <p class="empty-feed-title">No posts yet</p>
              <p class="empty-feed-sub">Be the first to share something with the community!</p>
            </div>`;
            return;
        }
        list.innerHTML = posts.map(p => _buildPostHTML(p)).join('');

        // Re-attach send-on-Enter for comment textareas
        list.querySelectorAll('.add-comment-ta').forEach(ta => {
            ta.addEventListener('keydown', e => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const postId = ta.dataset.postId;
                    const text = ta.value.trim();
                    if (text) {
                        communityStore.addComment(postId, text);
                        _expandedPosts.add(postId);
                        renderCommunityFeed();
                    }
                }
            });
        });
    }

    // ─── Sort tabs ───────────────────────────────────────────────────────────
    document.querySelectorAll('#sortTabs button').forEach(btn => {
        btn.addEventListener('click', () => {
            sortMode = btn.dataset.sort;
            document.querySelectorAll('#sortTabs button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderCommunityFeed();
        });
    });

    // ─── Create-post composer ────────────────────────────────────────────────
    let selectedTag = '';
    const trigger = document.getElementById('createPostTrigger');
    const form = document.getElementById('createPostForm');
    const cpTitle = () => document.getElementById('cpTitle');
    const cpContent = () => document.getElementById('cpContent');

    trigger?.addEventListener('click', () => {
        trigger.classList.add('hidden');
        form.classList.remove('hidden');
        cpTitle()?.focus();
    });

    document.getElementById('cpCancel')?.addEventListener('click', () => {
        form.classList.add('hidden');
        trigger.classList.remove('hidden');
        if (cpTitle()) cpTitle().value = '';
        if (cpContent()) cpContent().value = '';
        selectedTag = '';
        document.querySelectorAll('#cpTags .cp-tag').forEach(b => b.classList.toggle('active', b.dataset.tag === ''));
    });

    document.getElementById('cpTags')?.addEventListener('click', e => {
        const btn = e.target.closest('.cp-tag');
        if (!btn) return;
        selectedTag = btn.dataset.tag;
        document.querySelectorAll('#cpTags .cp-tag').forEach(b => b.classList.toggle('active', b === btn));
    });

    document.getElementById('cpPublish')?.addEventListener('click', () => {
        const title = cpTitle()?.value.trim();
        const content = cpContent()?.value.trim();
        if (!title || !content) return;
        communityStore.addPost(book.id, title, content, selectedTag || null);
        if (cpTitle()) cpTitle().value = '';
        if (cpContent()) cpContent().value = '';
        selectedTag = '';
        document.querySelectorAll('#cpTags .cp-tag').forEach(b => b.classList.toggle('active', b.dataset.tag === ''));
        form.classList.add('hidden');
        trigger.classList.remove('hidden');
        sortMode = 'recent';
        document.querySelectorAll('#sortTabs button').forEach(b => b.classList.toggle('active', b.dataset.sort === 'recent'));
        renderCommunityFeed();
    });

    // ─── Event delegation on posts list ─────────────────────────────────────
    document.getElementById('postsList')?.addEventListener('click', e => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const action = btn.dataset.action;
        const postId = btn.dataset.postId;
        const commentId = btn.dataset.commentId;
        const postCard = btn.closest('.post-card');

        if (action === 'upvote-post') {
            communityStore.togglePostUpvote(postId);
            renderCommunityFeed();
            return;
        }

        if (action === 'toggle-comments') {
            _expandedPosts.has(postId) ? _expandedPosts.delete(postId) : _expandedPosts.add(postId);
            renderCommunityFeed();
            return;
        }

        if (action === 'delete-post') {
            communityStore.deletePost(postId);
            _expandedPosts.delete(postId);
            renderCommunityFeed();
            return;
        }

        if (action === 'edit-post') {
            postCard?.querySelector('.post-view-wrap')?.classList.add('hidden');
            postCard?.querySelector('.post-edit-wrap')?.classList.remove('hidden');
            postCard?.querySelectorAll('.icon-btn').forEach(b => b.classList.add('hidden'));
            return;
        }

        if (action === 'cancel-edit-post') {
            postCard?.querySelector('.post-view-wrap')?.classList.remove('hidden');
            postCard?.querySelector('.post-edit-wrap')?.classList.add('hidden');
            postCard?.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('hidden'));
            return;
        }

        if (action === 'save-post') {
            const title = postCard?.querySelector('.post-edit-title-input')?.value.trim();
            const content = postCard?.querySelector('.post-edit-content-ta')?.value.trim();
            if (title && content) {
                communityStore.editPost(postId, title, content);
                renderCommunityFeed();
            }
            return;
        }

        // Comment actions — find the comment item wrapper
        const commentItem = btn.closest('.comment-item');

        if (action === 'upvote-comment') {
            communityStore.toggleCommentUpvote(postId, commentId);
            renderCommunityFeed();
            return;
        }

        if (action === 'delete-comment') {
            communityStore.deleteComment(postId, commentId);
            renderCommunityFeed();
            return;
        }

        if (action === 'edit-comment') {
            commentItem?.querySelector('.comment-content')?.classList.add('hidden');
            commentItem?.querySelector('.comment-edit-wrap')?.classList.remove('hidden');
            commentItem?.querySelectorAll('.icon-btn').forEach(b => b.classList.add('hidden'));
            return;
        }

        if (action === 'cancel-edit-comment') {
            commentItem?.querySelector('.comment-content')?.classList.remove('hidden');
            commentItem?.querySelector('.comment-edit-wrap')?.classList.add('hidden');
            commentItem?.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('hidden'));
            return;
        }

        if (action === 'save-comment') {
            const newText = commentItem?.querySelector('.comment-edit-ta')?.value.trim();
            if (newText) {
                communityStore.editComment(postId, commentId, newText);
                renderCommunityFeed();
            }
            return;
        }

        if (action === 'add-comment') {
            const ta = postCard?.querySelector('.add-comment-ta');
            const text = ta?.value.trim();
            if (text) {
                communityStore.addComment(postId, text);
                _expandedPosts.add(postId);
                renderCommunityFeed();
            }
            return;
        }
    });

    renderCommunityFeed();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  Server-driven reader (read_book_app.js) sets data-lexora-read-app on body.
// ═══════════════════════════════════════════════════════════════════════════════
document.addEventListener('lexora:bookCompleted', (e) => {
    const d = e.detail;
    if (!d?.bookId || !window.LexoraState?.markBookCompleted) return;
    if (window.LX_applyProfileStats) {
        window.LX_applyProfileStats({
            newCoins: d.newCoins,
            newLevel: d.newLevel,
            booksRead: d.booksRead,
        });
    }
    window.LexoraState.markBookCompleted(d.bookId);
    if (document.getElementById('bookDetailMain')) initBookDetail();
    if (document.getElementById('libraryGrid')) initProfile();
});

// ═══════════════════════════════════════════════════════════════════════════════
//  READ BOOK PAGE  (read-book.html)
// ═══════════════════════════════════════════════════════════════════════════════
function initReadBook() {
    if (document.body?.dataset?.lexoraReadApp === '1') return;
    const layout = document.querySelector('.read-layout');
    if (!layout || !window.LexoraState) return;

    const params = new URLSearchParams(window.location.search);
    const bookId = parseInt(params.get('id'), 10);
    const book = books.find(b => b.id === bookId);

    if (!book) {
        document.body.innerHTML = `<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--background);color:var(--foreground);flex-direction:column;gap:1rem;padding:2rem;text-align:center">
      <p class="font-display" style="font-size:1.25rem">Book not found</p>
      <a href="index.php" class="btn-primary">Return Home</a>
    </div>`;
        return;
    }

    const St = window.LexoraState;
    const pages = St.getBookPages(bookId);
    const totalPages = pages.length;
    let currentPage = St.getBookProgress(bookId) || 1;

    St.setLastBookReadId(bookId);
    document.title = `${book.title} — Reading — Lexora`;

    const titleEl = document.getElementById('readBookTitle');
    const metaEl = document.getElementById('readPageMeta');
    const kickerEl = document.getElementById('readPageKicker');
    const bodyEl = document.getElementById('readPageBody');
    const pctEl = document.getElementById('readPct');
    const fillEl = document.getElementById('readProgressFill');
    const prevBtn = document.getElementById('readPrev');
    const nextBtn = document.getElementById('readNext');
    const pillsEl = document.getElementById('readPagePills');
    const backBtn = document.getElementById('readBack');

    function goTo(page) {
        const clamped = Math.max(1, Math.min(page, totalPages));
        currentPage = clamped;
        St.saveReadingPage(bookId, clamped);
        render();
    }

    function render() {
        const progress = Math.round((currentPage / totalPages) * 100);
        if (titleEl) titleEl.textContent = book.title;
        if (metaEl) metaEl.textContent = `Page ${currentPage} of ${totalPages}`;
        if (kickerEl) kickerEl.textContent = `— PAGE ${currentPage} —`;
        if (bodyEl) {
            bodyEl.textContent = '';
            bodyEl.style.whiteSpace = 'pre-line';
            bodyEl.textContent = pages[currentPage - 1];
        }
        if (pctEl) pctEl.textContent = progress + '%';
        if (fillEl) fillEl.style.width = progress + '%';
        if (prevBtn) prevBtn.disabled = currentPage <= 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

        if (pillsEl) {
            const maxPills = Math.min(totalPages, 7);
            const pillPages = [];
            for (let i = 0; i < maxPills; i++) {
                let p;
                if (totalPages <= 7) p = i + 1;
                else if (currentPage <= 4) p = i + 1;
                else if (currentPage >= totalPages - 3) p = totalPages - 6 + i;
                else p = currentPage - 3 + i;
                pillPages.push(p);
            }
            pillsEl.innerHTML = pillPages.map(p => `
        <button type="button" class="read-pill${p === currentPage ? ' active' : ''}" data-page="${p}">${p}</button>
      `).join('');
            pillsEl.querySelectorAll('.read-pill').forEach(btn => {
                btn.addEventListener('click', () => goTo(parseInt(btn.getAttribute('data-page'), 10)));
            });
        }
    }

    prevBtn?.addEventListener('click', () => goTo(currentPage - 1));
    nextBtn?.addEventListener('click', () => goTo(currentPage + 1));
    backBtn?.addEventListener('click', () => history.back());

    render();
}

// ═══════════════════════════════════════════════════════════════════════════════
//  BOOT
// ═══════════════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', async () => {
    initGlobalHeader();
    initChatbot();
    await syncLexoraCatalogFromApi();

    // Seed LexoraState with server-sent library if available, before UI renders
    if (window.__lxLibrary && window.LexoraState?.applyLibraryFromServer) {
        window.LexoraState.applyLibraryFromServer(window.__lxLibrary);
    }

    initCatalog();
    initMapModal();
    initLumoWelcomeModal();
    initAuth();
    initStore();
    initProfile();
    initBookDetail();
    initReadBook();
});

// Browser back/forward can restore protected pages from BFCache without hitting the server.
// Force a reload so the server-side session guard decides whether the page is still allowed.
window.addEventListener('pageshow', (ev) => {
    const navEntry = (performance.getEntriesByType && performance.getEntriesByType('navigation')[0]) || null;
    const isHistoryRestore = ev.persisted || (navEntry && navEntry.type === 'back_forward');

    if (isHistoryRestore) {
        window.location.reload();
        return;
    }

    if (document.getElementById('libraryGrid')) {
        initProfile();
    }
});
