/**
 * Lumo Chatbot — shared markup for all Lexora pages.
 * Include a mount node before js/app.js, then load this script:
 *
 *   <div id="lumo-chatbot-root" data-lumo-greeting="Your first message..."></div>
 *   <script src="js/lumo-chatbot.js"></script>
 *
 * Optional: data-asset-base="assets/" (default) if assets live elsewhere.
 */
(function () {
    var DEFAULT_GREETING =
        "Hi there! I'm Lumo 🐻 your cozy reading companion. Ask me anything about books, coins, or your reading journey!";

    function mountLumoChatbot() {
        var root = document.getElementById('lumo-chatbot-root');
        if (!root) return;

        var greeting = root.getAttribute('data-lumo-greeting');
        if (!greeting || !String(greeting).trim()) greeting = DEFAULT_GREETING;

        var assetBase = root.getAttribute('data-asset-base') || 'assets/';
        if (assetBase.slice(-1) !== '/') assetBase += '/';
        var img = assetBase + 'lumo-happy.png';

        root.innerHTML =
            '<div id="lumo-fab" class="animate-lumo-float">' +
            '<button type="button" aria-label="Chat with Lumo">' +
            '<img src="' +
            img +
            '" alt="Lumo">' +
            '<div class="ping-ring"></div>' +
            '<div class="chat-hint"><span>CHAT WITH ME!</span></div>' +
            '</button></div>' +
            '<div id="lumo-chat" class="hidden">' +
            '<div class="chat-header">' +
            '<img src="' +
            img +
            '" alt="Lumo">' +
            '<div><h3>Lumo</h3><p>YOUR READING COMPANION</p></div>' +
            '<button type="button" class="chat-close-btn" id="chatClose">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
            '<line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>' +
            '</button></div>' +
            '<div class="chat-messages" id="chatMessages">' +
            '<div class="msg lumo-msg">' +
            '<img src="' +
            img +
            '" alt="">' +
            '<div class="bubble"></div></div></div>' +
            '<div class="chat-input-area">' +
            '<form onsubmit="return false">' +
            '<input id="chatInput" type="text" placeholder="Ask Lumo anything...">' +
            '<button type="button" id="chatSend" disabled>' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
            '<line x1="22" y1="2" x2="11" y2="13" /><polygon points="22 2 15 22 11 13 2 9 22 2" /></svg>' +
            '</button></form></div></div>';

        var bubble = root.querySelector('#chatMessages .bubble');
        if (bubble) bubble.textContent = greeting;
    }

    mountLumoChatbot();
    window.LexoRaMountLumoChatbot = mountLumoChatbot;
})();
