/* ── Get Started modal video controls ───────────────────────────────────── */
(function () {
  'use strict';

  var D = {
    play: 'M8 5v14l11-7z',
    pause: 'M6 19h4V5H6v14zm8-14v14h4V5h-4z',
    volOn: 'M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM18.5 12c0 2.96-1.74 5.52-4.5 6.71v2.06c3.87-1.27 6.5-4.94 6.5-8.77s-2.63-7.5-6.5-8.77v2.06C16.76 6.48 18.5 9.04 18.5 12z',
    volOff: 'M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z',
    fsEnter: 'M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z',
    fsExit: 'M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z'
  };

  function sp(el, d) { if (el) el.setAttribute('d', d); }
  function fmt(s) { s = Math.floor(s || 0); return Math.floor(s / 60) + ':' + (s % 60 < 10 ? '0' : '') + (s % 60); }

  var modal = document.getElementById('gs-modal');
  var openBtn = document.getElementById('btn-get-started');
  var closeBtn = document.getElementById('gs-modal-close');
  var backdrop = document.getElementById('gs-modal-backdrop');
  var vid = document.getElementById('gs-vid');

  if (!modal || !openBtn || !vid) return;

  /* ── Open modal ── */
  function openModal() {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    vid.currentTime = 0;
    vid.volume = 0.7;
    vid.muted = false;
    vid.play().catch(function () { vid.muted = true; vid.play(); });
    syncPlay(); syncVol();
  }

  /* ── Close modal ── */
  function closeModal() {
    modal.classList.remove('open');
    document.body.style.overflow = '';
    vid.pause();
    vid.currentTime = 0;
  }

  openBtn.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
  });

  /* ── Controls ── */
  var playBtn = document.getElementById('gs-play');
  var playPath = document.getElementById('gs-play-path');
  var seek = document.getElementById('gs-seek');
  var fill = document.getElementById('gs-fill');
  var curEl = document.getElementById('gs-cur');
  var durEl = document.getElementById('gs-dur');
  var muteBtn = document.getElementById('gs-mute');
  var volPath = document.getElementById('gs-vol-path');
  var volSldr = document.getElementById('gs-vol');
  var fsBtn = document.getElementById('gs-fs');
  var fsPth = document.getElementById('gs-fs-path');
  var wrap = document.querySelector('.gs-video-wrap');

  function syncPlay() { sp(playPath, vid.paused ? D.play : D.pause); }
  function syncVol() { sp(volPath, (vid.muted || vid.volume === 0) ? D.volOff : D.volOn); }

  playBtn.addEventListener('click', function () { vid.paused ? vid.play() : vid.pause(); });
  vid.addEventListener('play', syncPlay);
  vid.addEventListener('pause', syncPlay);

  vid.addEventListener('loadedmetadata', function () {
    seek.max = String(vid.duration);
    durEl.textContent = fmt(vid.duration);
  });
  vid.addEventListener('timeupdate', function () {
    if (!vid.duration) return;
    var pct = (vid.currentTime / vid.duration) * 100;
    fill.style.width = pct + '%';
    seek.value = String(vid.currentTime);
    curEl.textContent = fmt(vid.currentTime);
  });
  seek.addEventListener('input', function () { vid.currentTime = parseFloat(seek.value); });

  volSldr.value = '0.7';
  muteBtn.addEventListener('click', function () {
    vid.muted = !vid.muted;
    if (!vid.muted) { vid.volume = parseFloat(volSldr.value) || 0.7; }
    syncVol();
  });
  volSldr.addEventListener('input', function () {
    vid.volume = parseFloat(volSldr.value);
    vid.muted = (vid.volume === 0);
    syncVol();
  });

  fsBtn.addEventListener('click', function () {
    var inFS = document.fullscreenElement || document.webkitFullscreenElement;
    if (!inFS) {
      if (wrap.requestFullscreen) wrap.requestFullscreen();
      else if (wrap.webkitRequestFullscreen) wrap.webkitRequestFullscreen();
    } else {
      if (document.exitFullscreen) document.exitFullscreen();
      else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
    }
  });
  document.addEventListener('fullscreenchange', function () {
    sp(fsPth, document.fullscreenElement ? D.fsExit : D.fsEnter);
  });
  document.addEventListener('webkitfullscreenchange', function () {
    sp(fsPth, document.webkitFullscreenElement ? D.fsExit : D.fsEnter);
  });

})();
