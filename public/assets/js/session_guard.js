(function () {
  'use strict';

  var APP_BASE = '/PFA';
  var requiredRole = String(window.LX_REQUIRED_ROLE || 'user').toLowerCase();
  var checking = false;
  var redirecting = false;

  function revealPage() {
    if (document && document.documentElement) {
      document.documentElement.style.visibility = 'visible';
    }
  }

  function redirectToLanding() {
    if (redirecting) return;
    redirecting = true;
    window.location.replace(APP_BASE + '/');
  }

  async function verifySession() {
    if (checking || redirecting) return;
    checking = true;

    try {
      var res = await fetch(APP_BASE + '/api/auth/session', {
        method: 'GET',
        credentials: 'same-origin',
        cache: 'no-store',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      });
      var data = await res.json().catch(function () { return null; });

      if (!res.ok || !data || !data.authenticated) {
        redirectToLanding();
        return;
      }

      var role = String(data.role || '').toLowerCase();
      if (requiredRole === 'admin' && role !== 'admin') {
        redirectToLanding();
        return;
      }

      revealPage();
    } catch (err) {
      redirectToLanding();
      return;
    } finally {
      checking = false;
    }
  }

  verifySession();
  window.addEventListener('pageshow', verifySession);
  window.addEventListener('popstate', verifySession);
  window.addEventListener('focus', verifySession);
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible') {
      verifySession();
    }
  });
})();
