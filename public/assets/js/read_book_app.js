'use strict';

/**
 * read_book_app.js
 * Book reading page: progress API, reading-session logs, 100% completion flow (confirm → rating → rewards).
 */

(function () {

const SESSION = window.LX_SESSION     || {};
const BOOK    = window.LX_CURRENT_BOOK || {};
const CSRF    = SESSION.csrfToken     || '';

if (!BOOK.id) {
    console.error('LX_CURRENT_BOOK not set');
    return;
}

// ── API Helper ────────────────────────────────────────────────────────────────
async function api(path, method = 'GET', body = null) {
    const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF },
        credentials: 'same-origin',
    };
    if (body) opts.body = JSON.stringify(body);
    try {
        const res  = await fetch('/PFA' + path, opts);
        const json = await res.json().catch(() => ({ success: false }));
        if (!res.ok) {
            json._httpStatus = res.status;
        }
        return json;
    } catch (e) {
        console.warn('API call failed:', path, e.message);
        return { success: false };
    }
}

// ── Book pages ───────────────────────────────────────────────────────────────
const TOTAL_PAGES = Number(BOOK.totalPages) || 24;
const pages       = window.LexoraState
    ? window.LexoraState.getBookPages(BOOK.id, TOTAL_PAGES)
    : Array.from({ length: TOTAL_PAGES }, (_, i) =>
        `Page ${i + 1} content for "${BOOK.title}". Open lexora-state.js to generate full content.`
    );

function getProgressPercent(zeroBasedPage) {
    const total = pages.length;
    if (total <= 0) return 0;
    return Math.round(((zeroBasedPage + 1) / total) * 100);
}

// ── Reading State ─────────────────────────────────────────────────────────────
let currentPage = 0;
let finishAutoOffered = false;
let completionSubmitting = false;
let profileRedirectTimer = null;

function clearProfileRedirectTimer() {
    if (profileRedirectTimer) {
        clearTimeout(profileRedirectTimer);
        profileRedirectTimer = null;
    }
}

function goToProfilePage() {
    clearProfileRedirectTimer();
    window.location.href = 'index.php?view=profile';
}

function scheduleProfileRedirect() {
    clearProfileRedirectTimer();
    const hint = document.getElementById('finishRedirectHint');
    if (hint) hint.style.display = 'block';
    profileRedirectTimer = setTimeout(goToProfilePage, 2600);
}

// ── DOM ─────────────────────────────────────────────────────────────────────
const elTitle     = document.getElementById('readBookTitle');
const elMeta      = document.getElementById('readPageMeta');
const elKicker    = document.getElementById('readPageKicker');
const elBody      = document.getElementById('readPageBody');
const elFill      = document.getElementById('readProgressFill');
const elPct       = document.getElementById('readPct');
const elPills     = document.getElementById('readPagePills');
const btnPrev     = document.getElementById('readPrev');
const btnNext     = document.getElementById('readNext');
const btnBack     = document.getElementById('readBack');
const readNextLabel = document.getElementById('readNextLabel');

// ── Render current page ───────────────────────────────────────────────────────
function renderPage() {
    const p     = currentPage;
    const total = pages.length;
    const pct   = getProgressPercent(p);

    if (elTitle)  elTitle.textContent  = BOOK.title;
    if (elMeta)   elMeta.textContent   = `Page ${p + 1} of ${total}`;
    if (elKicker) elKicker.textContent = `✦ PAGE ${p + 1} ✦`;

    if (elBody) {
        elBody.innerHTML = pages[p]
            .split('\n\n')
            .map(par => `<p>${par}</p>`)
            .join('');
    }

    if (elFill) elFill.style.width = pct + '%';
    if (elPct)  elPct.textContent  = pct + '%';

    if (elPills) {
        elPills.innerHTML = Array.from({ length: total }, (_, i) =>
            `<span class="pill${i === p ? ' pill-active' : ''}"></span>`
        ).join('');
    }

    if (btnPrev) btnPrev.disabled = p === 0;
    if (btnNext) btnNext.disabled = false;

    if (readNextLabel) {
        const onLast = p === total - 1;
        readNextLabel.textContent = onLast && BOOK.alreadyCompleted ? 'READ AGAIN' : 'NEXT';
    }
}

// ── Navigation ────────────────────────────────────────────────────────────────
let pageEnteredAt = Date.now();

async function goToPage(newPage) {
    const minutesOnPage = Math.round((Date.now() - pageEnteredAt) / 60000);
    pageEnteredAt = Date.now();

    if (newPage < pages.length - 1) {
        finishAutoOffered = false;
    }

    currentPage = newPage;
    renderPage();
    window.scrollTo({ top: 0, behavior: 'smooth' });

    const progressResp = await api('/api/user/book/progress', 'POST', {
        book_id: BOOK.id,
        page:    newPage,
    });
    if (progressResp?._httpStatus === 403 && progressResp?.error === 'NOT_ENOUGH_COINS') {
        alert("You don't have enough coins");
        window.location.href = 'index.php?view=store';
        return;
    }

    if (minutesOnPage > 0 || newPage > 0) {
        api('/api/user/reading-session', 'POST', {
            book_id:      BOOK.id,
            pages_read:   1,
            minutes_read: Math.max(minutesOnPage, 1),
        }).then((resp) => {
            if (resp?.success && window.LX_applyProfileStats && typeof resp.newCoins === 'number') {
                window.LX_applyProfileStats({ newCoins: resp.newCoins });
            }
        });
    }

    if (getProgressPercent(newPage) === 100 && !BOOK.alreadyCompleted && !finishAutoOffered) {
        finishAutoOffered = true;
        setTimeout(openFinishModal, 800);
    }
}

btnPrev?.addEventListener('click', () => {
    if (currentPage > 0) goToPage(currentPage - 1);
});

btnNext?.addEventListener('click', () => {
    if (currentPage < pages.length - 1) {
        goToPage(currentPage + 1);
    } else if (BOOK.alreadyCompleted) {
        goToPage(0);
    } else {
        openFinishModal();
    }
});

btnBack?.addEventListener('click', () => {
    const minutesOnPage = Math.round((Date.now() - pageEnteredAt) / 60000);
    if (minutesOnPage > 0) {
        navigator.sendBeacon('/PFA/api/user/reading-session',
            new Blob([JSON.stringify({ book_id: BOOK.id, pages_read: 0, minutes_read: minutesOnPage })], { type: 'application/json' })
        );
    }
    history.back();
});

// ── Finish / Completion Modal ─────────────────────────────────────────────────
const finishModal    = document.getElementById('finishModal');
const finishStep1    = document.getElementById('finishStep1');
const finishStep2    = document.getElementById('finishStep2');
const finishStep3    = document.getElementById('finishStep3');
const btnYes         = document.getElementById('btnFinishYes');
const btnNo          = document.getElementById('btnFinishNo');
const btnSave        = document.getElementById('btnFinishSave');
const btnFinishClose = document.getElementById('btnFinishClose');
const finishRatingError = document.getElementById('finishRatingError');

let selectedRating = 0;

function setRatingError(visible) {
    if (finishRatingError) finishRatingError.style.display = visible ? 'block' : 'none';
}

function openFinishModal() {
    if (!finishModal || completionSubmitting) return;
    if (finishModal.style.display === 'flex') return;

    const nameEl = document.getElementById('finishBookName');
    if (nameEl) nameEl.textContent = `"${BOOK.title}"`;
    finishStep1.style.display = 'block';
    finishStep2.style.display = 'none';
    finishStep3.style.display = 'none';
    const hintEl = document.getElementById('finishRedirectHint');
    if (hintEl) hintEl.style.display = 'none';
    selectedRating = 0;
    setRatingError(false);
    resetStars();
    if (btnSave) {
        btnSave.disabled = true;
        btnSave.textContent = 'Confirm rating';
    }
    finishModal.style.display = 'flex';
}

function closeFinishModal() {
    if (!finishModal) return;
    const onSuccess = finishStep3 && finishStep3.style.display !== 'none';
    finishModal.style.display = 'none';
    if (onSuccess) goToProfilePage();
}

function resetStars() {
    document.querySelectorAll('.fstar').forEach(s => { s.textContent = '☆'; });
}

btnYes?.addEventListener('click', () => {
    finishStep1.style.display = 'none';
    finishStep2.style.display = 'block';
    if (btnSave) btnSave.disabled = true;
    selectedRating = 0;
    resetStars();
    setRatingError(false);
});

btnNo?.addEventListener('click', () => {
    closeFinishModal();
});

btnFinishClose?.addEventListener('click', () => {
    clearProfileRedirectTimer();
    goToProfilePage();
});

document.querySelectorAll('.fstar').forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = parseInt(star.dataset.v, 10);
        document.querySelectorAll('.fstar').forEach((s, i) => {
            s.textContent = i < selectedRating ? '★' : '☆';
        });
        setRatingError(false);
        if (btnSave) btnSave.disabled = selectedRating < 1;
    });
    star.addEventListener('mouseenter', () => {
        const v = parseInt(star.dataset.v, 10);
        document.querySelectorAll('.fstar').forEach((s, i) => {
            s.textContent = i < v ? '★' : '☆';
        });
    });
});
document.getElementById('finishStarRating')?.addEventListener('mouseleave', () => {
    document.querySelectorAll('.fstar').forEach((s, i) => {
        s.textContent = i < selectedRating ? '★' : '☆';
    });
});

btnSave?.addEventListener('click', async () => {
    if (selectedRating < 1 || selectedRating > 5) {
        setRatingError(true);
        return;
    }
    if (completionSubmitting) return;
    completionSubmitting = true;
    btnSave.disabled = true;
    btnSave.textContent = 'Saving…';

    const data = await api('/api/user/book/complete', 'POST', {
        book_id: BOOK.id,
        rating:  selectedRating,
    });

    completionSubmitting = false;
    btnSave.disabled = false;
    btnSave.textContent = 'Confirm rating';

    if (data.success) {
        if (!data.alreadyCompleted) {
            BOOK.alreadyCompleted = true;
        }
        const xp    = Number(data.xpEarned) || 0;
        const coins = Number(data.coinsEarned) || 0;
        const lvl   = Number(data.newLevel) || 1;
        const msg   = document.getElementById('finishRewardMsg');
        const hint  = document.getElementById('finishRedirectHint');
        if (hint) hint.style.display = 'none';
        if (msg) {
            if (data.alreadyCompleted) {
                msg.textContent =
                    'This book was already completed. Your rating was updated if you chose one.';
            } else {
                msg.textContent =
                    `You earned +${coins} coins and +${xp} XP. You are now level ${lvl}.`;
            }
        }
        finishStep2.style.display = 'none';
        finishStep3.style.display = 'block';
        renderPage();

        const prof = await api('/api/user/profile', 'GET');
        if (
            prof.success &&
            prof.data?.library &&
            window.LexoraState?.applyLibraryFromServer
        ) {
            window.LexoraState.applyLibraryFromServer(prof.data.library);
        }

        window.dispatchEvent(new CustomEvent('lexora:bookCompleted', {
            detail: {
                bookId: BOOK.id,
                xpEarned: xp,
                coinsEarned: coins,
                newCoins: Number(data.newCoins) || 0,
                newXp: Number(data.newXp) || 0,
                newLevel: lvl,
                booksRead: Number(data.booksRead) || 0,
                alreadyCompleted: !!data.alreadyCompleted,
            },
        }));

        scheduleProfileRedirect();
    } else {
        alert(data.message || 'Could not save completion. Please try again.');
    }
});

finishModal?.addEventListener('click', e => {
    if (e.target !== finishModal) return;
    if (finishStep3 && finishStep3.style.display !== 'none') {
        clearProfileRedirectTimer();
        goToProfilePage();
    } else {
        closeFinishModal();
    }
});

// ── Keyboard navigation ───────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (finishModal && finishModal.style.display === 'flex') return;
    if (e.key === 'ArrowRight' && currentPage < pages.length - 1) goToPage(currentPage + 1);
    if (e.key === 'ArrowLeft'  && currentPage > 0)                goToPage(currentPage - 1);
});

// ── Restore progress & completion flag from server ───────────────────────────
(async function syncFromServer() {
    const data = await api('/api/user/profile', 'GET');
    if (!data.success || !data.data || !Array.isArray(data.data.library)) return;

    if (window.LexoraState?.applyLibraryFromServer) {
        window.LexoraState.applyLibraryFromServer(data.data.library);
    }

    const entry = data.data.library.find(l => Number(l.book_id) === Number(BOOK.id));
    if (!entry) return;

    if (entry.status === 'completed') {
        BOOK.alreadyCompleted = true;
    }
    const pp = entry.progress_page;
    if (typeof pp === 'number' && pp >= 0 && pages.length > 0) {
        currentPage = Math.min(pp, pages.length - 1);
    }
    renderPage();
    const resumeResp = await api('/api/user/book/progress', 'POST', {
        book_id: BOOK.id,
        page: currentPage,
    });
    if (resumeResp?._httpStatus === 403 && resumeResp?.error === 'NOT_ENOUGH_COINS') {
        alert("You don't have enough coins");
        window.location.href = 'index.php?view=store';
        return;
    }
    if (getProgressPercent(currentPage) === 100 && !BOOK.alreadyCompleted && !finishAutoOffered) {
        finishAutoOffered = true;
        setTimeout(openFinishModal, 800);
    }
})();

// ── Init ──────────────────────────────────────────────────────────────────────
renderPage();
api('/api/user/book/progress', 'POST', {
    book_id: BOOK.id,
    page: currentPage,
}).then((firstResp) => {
    if (firstResp?._httpStatus === 403 && firstResp?.error === 'NOT_ENOUGH_COINS') {
        alert("You don't have enough coins");
        window.location.href = 'index.php?view=store';
    }
});

})();
