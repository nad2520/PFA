<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading ? Lexora</title>
    <meta name="description" content="Continue reading your book on Lexora.">
    <link rel="stylesheet" href="view/user_scss/main.css">
</head>

<body class="read-book-page">

    <div class="read-layout">
        <header class="read-topbar">
            <div class="read-topbar-inner">
                <button type="button" class="read-back" id="readBack" aria-label="Back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    <span class="read-back-text">Back</span>
                </button>
                <div class="read-title-block">
                    <p class="read-book-title font-display" id="readBookTitle">?</p>
                    <p class="read-page-meta font-body" id="readPageMeta">Page 1 of 1</p>
                </div>
                <div class="read-progress-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                    </svg>
                    <span id="readPct" class="read-pct-pixel">0%</span>
                </div>
            </div>
            <div class="read-progress-track">
                <div class="read-progress-fill" id="readProgressFill"></div>
            </div>
        </header>

        <main class="read-main">
            <div class="read-page-card">
                <p class="read-page-kicker" id="readPageKicker">? PAGE 1 ?</p>
                <div class="read-page-body font-body" id="readPageBody"></div>
            </div>
        </main>

        <footer class="read-footer">
            <div class="read-footer-inner">
                <button type="button" class="btn-read-nav" id="readPrev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    PREV
                </button>
                <div class="read-page-pills" id="readPagePills"></div>
                <button type="button" class="btn-read-nav" id="readNext">
                    NEXT
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </footer>
    </div>

    <div id="lumo-chatbot-root" data-lumo-greeting="Hi there! I'm Lumo ? enjoy your reading session!"></div>

    <script src="model/user_data.js"></script>
    <script src="model/lexora-state.js"></script>
    <script src="controller/lumo-chatbot.js"></script>
    <script src="controller/user_app.js"></script>
</body>

</html>

