'use strict';

/**
 * profile_app.js
 * Manages the profile page interactions:
 *  - Book completion popup flow (confirm → star rating → save)
 *  - Lumo lamp state display
 *  - Leaderboard rendering from server data
 *  - All API calls go to the real PHP backend (no more localStorage for state)
 */

(function () {

const SESSION = window.LX_SESSION || {};
const CSRF    = SESSION.csrfToken || '';

// ── API Helper ────────────────────────────────────────────────────────────────
async function api(path, method = 'GET', body = null) {
    const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF },
    };
    if (body) opts.body = JSON.stringify(body);
    const res  = await fetch('/PFA' + path, opts);
    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'API error');
    return json;
}

// ── Book Completion Modal ─────────────────────────────────────────────────────
const modal        = document.getElementById('completionModal');
const step1        = document.getElementById('completionStep1');
const step2        = document.getElementById('completionStep2');
const step3        = document.getElementById('completionStep3');
const btnCompleted = document.getElementById('btnCompleted');
const btnNotDone   = document.getElementById('btnNotFinished');
const btnSaveRating = document.getElementById('btnSaveRating');
const rewardMsg    = document.getElementById('completionRewardMsg');

let activeBookId   = null;
let selectedRating = 0;

/** Open the completion modal for a given book */
function openCompletionModal(bookId, bookTitle) {
    activeBookId = bookId;
    selectedRating = 0;
    document.getElementById('completionBookTitle').textContent = bookTitle || '';
    resetModalSteps();
    modal.style.display = 'flex';
}

function resetModalSteps() {
    step1.style.display = 'block';
    step2.style.display = 'none';
    step3.style.display = 'none';
    // Reset stars
    document.querySelectorAll('#starRating .star').forEach(s => s.textContent = '☆');
}

// "Completed!" button → show rating step
btnCompleted?.addEventListener('click', () => {
    step1.style.display = 'none';
    step2.style.display = 'block';
});

// "Not yet" button → close modal
btnNotDone?.addEventListener('click', () => {
    modal.style.display = 'none';
    activeBookId = null;
});

// Star rating interaction
document.querySelectorAll('#starRating .star').forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = parseInt(star.dataset.v, 10);
        document.querySelectorAll('#starRating .star').forEach((s, i) => {
            s.textContent = i < selectedRating ? '★' : '☆';
        });
    });
    star.addEventListener('mouseenter', () => {
        const v = parseInt(star.dataset.v, 10);
        document.querySelectorAll('#starRating .star').forEach((s, i) => {
            s.textContent = i < v ? '★' : '☆';
        });
    });
});

document.getElementById('starRating')?.addEventListener('mouseleave', () => {
    document.querySelectorAll('#starRating .star').forEach((s, i) => {
        s.textContent = i < selectedRating ? '★' : '☆';
    });
});

// "Save Rating" button → POST to API
btnSaveRating?.addEventListener('click', async () => {
    if (!activeBookId) return;
    try {
        const data = await api('/api/user/book/complete', 'POST', {
            book_id: activeBookId,
            rating:  selectedRating || null,
        });

        rewardMsg.textContent =
            `🎉 +${data.xpEarned} XP  +${data.coinsEarned} Coins earned!  ` +
            `You are now Level ${data.newLevel}.`;

        step2.style.display = 'none';
        step3.style.display = 'block';

        // Update displayed coins/level without a full page reload
        updateHeaderStats(data.newCoins, data.newLevel);

        // Mark card as completed in the DOM
        markBookCardCompleted(activeBookId, selectedRating);

    } catch (err) {
        alert('Error: ' + err.message);
    }
});

function updateHeaderStats(newCoins, newLevel) {
    const coinEl = document.getElementById('coinCount');
    if (coinEl) coinEl.textContent = newCoins.toLocaleString();
    // Level badge in hover card
    document.querySelectorAll('[data-level-display]').forEach(el => {
        el.textContent = 'LVL ' + newLevel;
    });
}

function markBookCardCompleted(bookId, rating) {
    const card = document.querySelector(`.book-card[data-book-id="${bookId}"]`);
    if (!card) return;
    const actions = card.querySelector('.book-info > div');
    if (actions) {
        actions.innerHTML =
            `<span style="font-size:.7rem;color:hsl(140,60%,60%);font-weight:600">✓ Completed</span>` +
            (rating ? `<span style="font-size:.75rem">${'★'.repeat(rating)}${'☆'.repeat(5-rating)}</span>` : '');
    }
}

// ── Trigger completion modal from "Continue" buttons ─────────────────────────
// When a user clicks "Continue" on a book that is at the last page,
// user_app.js fires a custom event 'lexora:bookFinished'
document.addEventListener('lexora:bookFinished', (e) => {
    const { bookId, bookTitle } = e.detail || {};
    if (bookId) openCompletionModal(bookId, bookTitle);
});

// Allow library cards to trigger the modal directly (for "mark as done" button if added)
document.querySelectorAll('.btn-mark-complete').forEach(btn => {
    btn.addEventListener('click', () => {
        const card  = btn.closest('.book-card');
        const id    = parseInt(card?.dataset.bookId, 10);
        const title = card?.querySelector('p')?.textContent || '';
        openCompletionModal(id, title);
    });
});

// Close modal on backdrop click
modal?.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
        activeBookId = null;
    }
});

// ── Lumo Lamp ─────────────────────────────────────────────────────────────────
(function initLumo() {
    const state = SESSION.lumoState || 'worried';
    const dot   = document.getElementById('lampDot');
    if (!dot) return;

    dot.classList.remove('animate-lamp-glow');
    if (state === 'happy') {
        dot.style.background = 'hsl(140,70%,50%)';
        dot.classList.add('animate-lamp-glow');
    } else if (state === 'dim') {
        dot.style.background = 'hsl(38,80%,50%)';
    } else {
        dot.style.background = 'hsl(0,60%,50%)';
    }
})();

// ── Reading session logging ───────────────────────────────────────────────────
// Called by read-book page when a page is turned
window.LX_logReadingPage = async function (bookId, pagesRead, minutesRead) {
    try {
        await api('/api/user/reading-session', 'POST', {
            book_id:      bookId,
            pages_read:   pagesRead,
            minutes_read: minutesRead,
        });
    } catch (e) {
        console.warn('Could not log reading session:', e.message);
    }
};

// ── Reading progress persistence ──────────────────────────────────────────────
window.LX_saveProgress = async function (bookId, page) {
    try {
        await api('/api/user/book/progress', 'POST', { book_id: bookId, page });
    } catch (e) {
        console.warn('Could not save progress:', e.message);
    }
};

})();