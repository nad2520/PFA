(function () {
  'use strict';

  async function loadLeaderboard() {
    var root = document.getElementById('landingLeaderboardRows');
    if (!root) return;
    try {
      var res = await fetch('/PFA/api/leaderboard', { credentials: 'same-origin' });
      var json = await res.json();
      if (!res.ok || !json.success || !Array.isArray(json.data)) return;

      var rows = json.data.slice(0, 10);
      root.innerHTML = rows.map(function (row, i) {
        var rank = Number(row.rank || (i + 1));
        var score = Number(row.xp || 0).toLocaleString();
        var books = Number(row.books_read || 0);
        var level = Number(row.level || 1);
        var rowClass = rank <= 3 ? 'lb-row top3 reveal-x' : 'lb-row reveal-x';
        return (
          '<div class="' + rowClass + '">' +
            '<div class="lb-rank"><span class="lb-rank-num">#' + rank + '</span></div>' +
            '<div class="lb-reader"><span class="lb-avatar"></span><span class="lb-name">' + String(row.nom || 'Reader') + '</span></div>' +
            '<div class="lb-score"><span class="lb-score-val">' + score + '</span><span class="lb-score-label">xp</span></div>' +
            '<div class="lb-books">' + books + '</div>' +
            '<div class="lb-level"><span class="lb-level-badge">Lv.' + level + '</span></div>' +
          '</div>'
        );
      }).join('');
    } catch (_) {
      // keep static fallback rows
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadLeaderboard);
  } else {
    loadLeaderboard();
  }
})();
