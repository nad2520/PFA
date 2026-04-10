<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Footer Fragment - Lexora</title>
    <link rel="stylesheet" href="../../common/styles/global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="vf-section">
      <video id="footer-vid" autoplay muted loop playsinline>
        <source src="../../assets/videos/hero-bg.mp4" type="video/mp4">
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
            <path id="vf-vol-path" d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
          </svg>
        </button>
        <input class="vf-vol" id="vf-vol" type="range" min="0" max="1" step="0.01" value="0.7" aria-label="Volume">

        <!-- Fullscreen -->
        <button class="vf-btn" id="vf-fs" aria-label="Fullscreen" title="Fullscreen">
          <svg id="vf-fs-svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
            <path id="vf-fs-path" d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z" />
          </svg>
        </button>
      </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
