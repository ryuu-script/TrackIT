<footer class="site-footer">

    <!-- Decorative top glow bar -->
    <div class="footer-glow-bar"></div>

    <!-- Geometric corner accents -->
    <div class="footer-corner footer-corner--left"></div>
    <div class="footer-corner footer-corner--right"></div>

    <div class="footer-content">

        <div class="footer-brand">
            <div class="footer-logo-ring">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <polygon points="10,2 18,7 18,13 10,18 2,13 2,7" stroke="rgba(185,100,255,0.9)" stroke-width="1.5" fill="none"/>
                    <polygon points="10,5.5 15,8.5 15,11.5 10,14.5 5,11.5 5,8.5" fill="rgba(185,100,255,0.2)"/>
                </svg>
            </div>
            <span class="footer-company">Sinoy Technologies.</span>
            <span class="footer-dev">Developed sa <strong>Mga Sit-in Sa Web Dev</strong></span>
        </div>

        <div class="footer-divider">
            <span class="footer-divider-dot"></span>
            <span class="footer-divider-line"></span>
            <span class="footer-divider-dot"></span>
        </div>

        <div class="footer-links">
            <a href="https://github.com/ryuu-script" target="_blank" rel="noopener noreferrer">
                <span class="link-icon"><i class='bx bxl-github'></i></span>
                <span>Christian Salang</span>
            </a>
            <a href="https://github.com/username2" target="_blank" rel="noopener noreferrer">
                <span class="link-icon"><i class='bx bxl-github'></i></span>
                <span>Mark Sinoy</span>
            </a>
            <a href="https://github.com/username3" target="_blank" rel="noopener noreferrer">
                <span class="link-icon"><i class='bx bxl-github'></i></span>
                <span>Giancarlo Albeos</span>
            </a>
            <a href="https://github.com/username4" target="_blank" rel="noopener noreferrer">
                <span class="link-icon"><i class='bx bxl-github'></i></span>
                <span>John Panugan</span>
            </a>
            <a href="https://github.com/username5" target="_blank" rel="noopener noreferrer">
                <span class="link-icon"><i class='bx bxl-github'></i></span>
                <span>Ramson Granada</span>
            </a>
        </div>

        <p class="footer-copy">
            <span class="copy-symbol">&copy;</span>
            <?= date('Y') ?> Sinoy Technologies.
            <span class="copy-sep">·</span>
            All rights reserved.
        </p>

    </div>
</footer>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600&family=DM+Sans:wght@300;400&display=swap');

.site-footer {
    width: 100%;
    box-sizing: border-box;

    padding: 18px 40px 14px;
    background: linear-gradient(180deg, rgba(10, 4, 22, 0.92) 0%, rgba(18, 6, 38, 0.97) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1px solid rgba(140, 60, 220, 0.25);
    position: fixed;
    bottom: 0;
    left: 0;
    z-index: 100;
    overflow: hidden;
    font-family: 'DM Sans', sans-serif;
}

    /* Animated top glow bar */
    .footer-glow-bar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg,
            transparent 0%,
            rgba(120, 40, 200, 0) 10%,
            rgba(185, 100, 255, 0.8) 30%,
            rgba(220, 160, 255, 1) 50%,
            rgba(185, 100, 255, 0.8) 70%,
            rgba(120, 40, 200, 0) 90%,
            transparent 100%
        );
        animation: glowSweep 4s ease-in-out infinite;
    }

    @keyframes glowSweep {
        0%, 100% { opacity: 0.5; transform: scaleX(0.7); }
        50% { opacity: 1; transform: scaleX(1); }
    }

    /* Geometric corner accents */
    .footer-corner {
        position: absolute;
        top: 0;
        width: 60px;
        height: 60px;
        opacity: 0.15;
    }

    .footer-corner--left {
        left: 0;
        border-top: 1px solid rgb(185, 100, 255);
        border-right: 1px solid rgb(185, 100, 255);
        clip-path: polygon(0 0, 100% 0, 0 100%);
    }

    .footer-corner--right {
        right: 0;
        border-top: 1px solid rgb(185, 100, 255);
        border-left: 1px solid rgb(185, 100, 255);
        clip-path: polygon(0 0, 100% 0, 100% 100%);
    }

    /* Subtle purple radial glow behind content */
    .footer-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .footer-content::before {
        content: '';
        position: absolute;
        inset: -30px;
        background: radial-gradient(ellipse 60% 100% at 50% 50%, rgba(150, 60, 255, 0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Brand block */
    .footer-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
    }

    .footer-logo-ring {
        margin-bottom: 2px;
        animation: spinSlow 12s linear infinite;
        filter: drop-shadow(0 0 6px rgba(185, 100, 255, 0.5));
    }

    @keyframes spinSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .footer-company {
        font-family: 'Rajdhani', sans-serif;
        color: rgb(220, 175, 255);
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        text-shadow: 0 0 20px rgba(185, 100, 255, 0.6);
    }

    .footer-dev {
        color: rgba(200, 170, 230, 0.45);
        font-size: 11px;
        font-weight: 300;
        letter-spacing: 0.5px;
    }

    .footer-dev strong {
        color: rgba(200, 170, 230, 0.75);
        font-weight: 400;
    }

    /* Stylized divider */
    .footer-divider {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-divider-line {
        width: 50px;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(185, 100, 255, 0.5), transparent);
    }

    .footer-divider-dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: rgba(185, 100, 255, 0.7);
        box-shadow: 0 0 6px rgba(185, 100, 255, 0.9);
    }

    /* GitHub links */
    .footer-links {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .footer-links a {
        color: rgba(200, 170, 230, 0.45);
        text-decoration: none;
        font-size: 12px;
        font-weight: 300;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .footer-links a::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        background: rgba(185, 100, 255, 0);
        transition: background 0.3s ease;
    }

    .footer-links a:hover {
        color: rgb(220, 175, 255);
        border-color: rgba(185, 100, 255, 0.35);
        text-shadow: 0 0 10px rgba(185, 100, 255, 0.5);
        transform: translateY(-1px);
    }

    .footer-links a:hover::before {
        background: rgba(185, 100, 255, 0.07);
    }

    .link-icon {
        display: flex;
        align-items: center;
        font-size: 14px;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .footer-links a:hover .link-icon {
        opacity: 1;
    }

    /* Copyright */
    .footer-copy {
        color: rgba(185, 100, 255, 0.22);
        font-size: 10px;
        letter-spacing: 1px;
        font-weight: 300;
        text-transform: uppercase;
    }

    .copy-symbol {
        color: rgba(185, 100, 255, 0.35);
    }

    .copy-sep {
        margin: 0 6px;
        color: rgba(185, 100, 255, 0.2);
    }
</style>