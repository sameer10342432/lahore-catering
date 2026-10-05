</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand & About -->
            <div class="footer-widget">
                <a href="/" style="display:inline-block; margin-bottom:1rem;" title="<?= e(APP_NAME) ?>">
                    <img src="/assets/images/logo.png" alt="<?= e(APP_NAME) ?>" style="height:46px; width:auto; max-width:180px; object-fit:contain; background:#ffffff; padding:4px 8px; border-radius:6px;">
                </a>
                <p>Lahore Catering levert in heel Friesland en Nederland authentieke en smaakvolle Pakistaanse en Indiase gerechten. Van bruiloft catering en buffetten tot snelle maaltijdbezorging en foodtrucks.</p>
                <div style="margin-top: 1.25rem;">
                    <a href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener" style="color:#f59e0b; font-weight:600; display:inline-flex; align-items:center; gap:0.5rem;">
                        <span>Volg ons op Facebook &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Openingstijden -->
            <div class="footer-widget">
                <h3>Openingstijden</h3>
                <p><strong>Maandag t/m Donderdag:</strong><br>16:00 tot 20:00</p>
                <p style="margin-top: 0.75rem;"><strong>Vrijdag t/m Zondag:</strong><br>16:00 tot 21:00</p>
                <p style="margin-top: 0.75rem; font-size:0.85rem; color:#fde68a;">Catering op locatie is 7 dagen per week mogelijk op afspraak!</p>
            </div>

            <!-- Populaire Diensten & Pagina's -->
            <div class="footer-widget">
                <h3>Diensten</h3>
                <ul class="footer-links">
                    <?php
                    $footerItems = Menu::getByLocation('footer');
                    foreach ($footerItems as $fItem):
                    ?>
                    <li><a href="<?= e($fItem['url']) ?>"><?= e($fItem['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-widget">
                <h3>Contact</h3>
                <p><strong>Adres:</strong><br><?= e(CONTACT_ADDRESS) ?></p>
                <p style="margin-top: 0.5rem;"><strong>Telefoon:</strong><br><a href="tel:<?= e(CONTACT_PHONE_RAW) ?>" style="color:#ffffff;"><?= e(CONTACT_PHONE) ?></a></p>
                <p style="margin-top: 0.5rem;"><strong>E-mail:</strong><br><a href="mailto:<?= e(CONTACT_EMAIL) ?>" style="color:#ffffff;"><?= e(CONTACT_EMAIL) ?></a></p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Lahore Catering. Alle rechten voorbehouden. Authentieke Pakistaanse keuken in Friesland.</p>
            <p><a href="/contact/" style="color:#a8a29e;">Contact</a> &bull; <a href="/admin/login.php" style="color:#78716c;" rel="nofollow">Beheer</a></p>
        </div>
    </div>
</footer>

<!-- Cookie Banner -->
<div id="cookieBanner" class="cookie-banner" style="display:none;">
    <div>
        <h4 style="font-size:1.05rem; margin-bottom:0.25rem;">Wij waarderen uw privacy</h4>
        <p style="font-size:0.85rem; margin-bottom:0; color:#57534e;">Lahore Catering gebruikt functionele cookies om de website goed te laten werken en uw bezoekerservaring te verbeteren.</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <button id="cookieAcceptBtn" class="btn btn-primary btn-sm" style="flex:1;">Akkoord</button>
    </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>
