/* ─── Hero Logic ────────────────────────────────────── */
function initHeroVideo() {
  const video = document.getElementById('hero-video');
  if (!video) return;

  // Show video once it can play to avoid a black flash
  video.addEventListener('canplay', () => {
    video.style.opacity = '1';
    video.play().catch(error => {
      console.log("Autoplay was prevented:", error);
    });
  });

  // Ensure it's loading
  video.load();
}

// In a real modular setup, we might call this from a main file,
// but for the standalone component, we call it on DOMContentLoaded.
document.addEventListener('DOMContentLoaded', () => {
  initHeroVideo();
  
  // Initialize particles for hero if particleFactory exists
  if (window.particleFactory) {
    const container = document.getElementById('hero-particles');
    if (container) {
      // Hero particles are more subtle and slow
      window.particleFactory.create(container, {
          count: 30,
          minSize: 1,
          maxSize: 3,
          duration: 15,
          color: 'hsl(38, 75%, 55%)'
      });
    }
  }
});

// Export for main assembly
window.initHero = initHeroVideo;
