<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Get Started Modal - Lexora</title>
  <link rel="stylesheet" href="../../common/styles/global.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- ═══ Get Started Video Modal ═══════════════════════════════════════════ -->
  <div class="gs-modal" id="gs-modal" role="dialog" aria-modal="true" aria-label="Lexora Introduction Video">
    <div class="gs-modal-backdrop" id="gs-modal-backdrop"></div>
    <div class="gs-modal-container">

      <!-- Close button -->
      <button class="gs-modal-close" id="gs-modal-close" aria-label="Close video">
        <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>

      <!-- Video wrapper -->
      <div class="gs-video-wrap">
        <video id="gs-vid" playsinline>
          <source src="../../assets/videos/lexora-intro.mp4" type="video/mp4">
        </video>

        <!-- Control bar -->
        <div class="vf-bar" id="gs-bar">

          <button class="vf-btn" id="gs-play" aria-label="Play/Pause">
            <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
              <path id="gs-play-path" d="M8 5v14l11-7z"/>
            </svg>
          </button>

          <span class="vf-time" id="gs-cur">0:00</span>

          <div class="vf-seek-wrap">
            <div class="vf-track"><div class="vf-fill" id="gs-fill"></div></div>
            <input class="vf-seek" id="gs-seek" type="range" min="0" max="100" value="0" step="0.1" aria-label="Seek">
          </div>

          <span class="vf-time" id="gs-dur">0:00</span>

          <button class="vf-btn" id="gs-mute" aria-label="Mute/Unmute">
            <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
              <path id="gs-vol-path" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
            </svg>
          </button>
          <input class="vf-vol" id="gs-vol" type="range" min="0" max="1" step="0.01" value="0.7" aria-label="Volume">

          <button class="vf-btn" id="gs-fs" aria-label="Fullscreen">
            <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
              <path id="gs-fs-path" d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
            </svg>
          </button>

        </div>
      </div>

    </div>
  </div>

  <script src="../../common/scripts/global.js"></script>
  <script src="script.js"></script>
</body>
</html>
