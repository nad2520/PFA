/* ─── Splash logic ──────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
    // Shared particle factory (from common/global.js)
    if (typeof createParticles === 'function') {
        createParticles('splash-particles', 25);
    }

    const splashPage = document.getElementById('splash-page');
    const mainPage = document.getElementById('main-page');
    const splashImg = document.getElementById('splash-img');
    const splashCta = document.getElementById('splash-cta');

    if (!splashPage) return;

    function enterMain(e) {
        // Magic burst
        const burst = document.createElement('div');
        burst.className = 'magic-burst';
        const rect = splashPage.getBoundingClientRect();
        burst.style.left = (e.clientX - rect.left) + 'px';
        burst.style.top = (e.clientY - rect.top) + 'px';
        burst.innerHTML = `
            <div class="burst-core"></div>
            <div class="burst-inner"><div></div></div>
            ${Array.from({ length: 8 }, (_, i) => `<div class="sparkle-ray" style="transform:translate(-50%,-50%) rotate(${i * 45}deg);animation-delay:${i * 0.05}s;"></div>`).join('')}
        `;
        splashPage.appendChild(burst);

        splashImg.classList.add('exiting');
        splashCta.classList.add('exiting');
        splashPage.classList.add('exiting');

        setTimeout(() => {
            splashPage.style.display = 'none';
            if (mainPage) mainPage.classList.add('active');
            
            // Global particle factory for hero (linked here for flow)
            if (typeof createParticles === 'function') {
                createParticles('hero-particles', 25);
            }
            
            // Dispatch event for other modules
            window.dispatchEvent(new CustomEvent('lexora:entered'));
        }, 900);
    }

    splashPage.addEventListener('click', enterMain);
});
