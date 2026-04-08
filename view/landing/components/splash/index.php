<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Fragment - Lexora</title>
    <link rel="stylesheet" href="../../common/styles/global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="splash-page">
        <div class="ambient"></div>

        <!-- Light orbs -->
        <div class="orb"
            style="top:15%;left:10%;width:128px;height:128px;background:hsl(38 75% 55% / 0.1);animation-delay:0s;"></div>
        <div class="orb"
            style="top:25%;right:15%;width:160px;height:160px;background:hsl(175 70% 45% / 0.1);filter:blur(90px);animation-delay:1s;">
        </div>
        <div class="orb"
            style="bottom:20%;left:20%;width:144px;height:144px;background:hsl(270 50% 50% / 0.1);filter:blur(70px);animation-delay:2s;">
        </div>
        <div class="orb"
            style="bottom:30%;right:10%;width:112px;height:112px;background:hsl(38 75% 55% / 0.08);filter:blur(60px);animation-delay:0.5s;">
        </div>

        <!-- Particles -->
        <div class="particles" id="splash-particles"></div>

        <!-- Splash image -->
        <div class="splash-image-wrap" id="splash-img">
            <img
                src="../../assets/img_1.png"
                alt="Lexora — Enter the World of Reading Adventure"
                style="width:100%;height:auto;border-radius:1rem;box-shadow:0 30px 80px rgba(0,0,0,0.5);" />
            <div class="splash-glow-border"></div>
            <div class="splash-bottom-glow"></div>
        </div>

        <!-- CTA text -->
        <div class="splash-cta" id="splash-cta">
            <h2>Enter the World of Lexora</h2>
            <p>✦ Click anywhere to begin your adventure ✦</p>
        </div>

        <!-- Scanlines -->
        <div class="scanlines"></div>
    </div>

    <script src="../../common/scripts/global.js"></script>
    <script src="script.js"></script>
</body>
</html>
