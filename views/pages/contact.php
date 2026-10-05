<?php
/**
 * Contact Page View
 */

$metaTitle = 'Contact & Openingstijden | Lahore Catering Friesland';
$metaDesc = 'Neem contact op met Lahore Catering in Stiens. Bel direct 06 331 667 30 of stuur een bericht voor vragen over catering, reserveringen en maaltijden.';
$canonicalUrl = CANONICAL_DOMAIN . '/contact/';

require_once VIEW_PATH . '/components/header.php';
$csrfToken = generate_csrf_token();
?>

<div style="background:var(--bg-alt); padding:3rem 0; border-bottom:1px solid var(--border-light);">
    <div class="container">
        <?php
        $crumbs = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Contact']
        ];
        require VIEW_PATH . '/components/breadcrumbs.php';
        ?>
        <h1 style="font-size:clamp(2.2rem, 4vw, 3rem); margin-bottom:0.5rem;">Contact & Openingstijden</h1>
        <p style="font-size:1.1rem; color:var(--text-light); max-width:650px;">
            Heeft u vragen over onze catering, buffetten of maaltijdbezorging? Neem gerust contact met ons op!
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:3.5rem;">
            <!-- Contact Info & Opening Hours -->
            <div>
                <span class="section-badge">Direct Bereikbaar</span>
                <h2 style="font-size:2rem; margin-bottom:1.5rem;">Wij staan voor u klaar</h2>
                
                <div style="display:flex; flex-direction:column; gap:1.5rem; margin-bottom:2.5rem;">
                    <div style="display:flex; gap:1rem; align-items:flex-start;">
                        <div style="width:46px; height:46px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:1.25rem; flex-shrink:0;">
                            📍
                        </div>
                        <div>
                            <strong style="display:block; font-size:1.05rem; margin-bottom:0.25rem;">Ons Adres</strong>
                            <p style="margin-bottom:0; color:var(--text-muted);"><?= e(CONTACT_ADDRESS) ?></p>
                        </div>
                    </div>

                    <div style="display:flex; gap:1rem; align-items:flex-start;">
                        <div style="width:46px; height:46px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:1.25rem; flex-shrink:0;">
                            📞
                        </div>
                        <div>
                            <strong style="display:block; font-size:1.05rem; margin-bottom:0.25rem;">Telefoonnummer</strong>
                            <p style="margin-bottom:0;"><a href="tel:<?= e(CONTACT_PHONE_RAW) ?>" style="font-size:1.1rem; font-weight:700; color:var(--primary);"><?= e(CONTACT_PHONE) ?></a></p>
                            <span style="font-size:0.85rem; color:var(--text-light);">Bereikbaar voor bestellingen en cateringaanvragen</span>
                        </div>
                    </div>

                    <div style="display:flex; gap:1rem; align-items:flex-start;">
                        <div style="width:46px; height:46px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:1.25rem; flex-shrink:0;">
                            ✉️
                        </div>
                        <div>
                            <strong style="display:block; font-size:1.05rem; margin-bottom:0.25rem;">E-mailadres</strong>
                            <p style="margin-bottom:0;"><a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></p>
                        </div>
                    </div>
                </div>

                <div style="background:#ffffff; border-radius:12px; padding:2rem; border:1px solid var(--border-light); box-shadow:var(--shadow-sm);">
                    <h3 style="font-size:1.3rem; margin-bottom:1rem; color:var(--text-main);">Openingstijden Restaurant & Bezorging</h3>
                    <div style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid var(--border-subtle);">
                        <span>Maandag t/m Donderdag:</span>
                        <strong>16:00 – 20:00</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:0.5rem 0;">
                        <span>Vrijdag t/m Zondag:</span>
                        <strong>16:00 – 21:00</strong>
                    </div>
                    <div style="margin-top:1rem; padding:0.75rem 1rem; background:var(--bg-alt); border-radius:8px; font-size:0.85rem; color:var(--text-muted);">
                        ⭐ <em>Catering op locatie voor groepen is 7 dagen per week op elk gewenst tijdstip mogelijk op afspraak.</em>
                    </div>
                </div>
            </div>

            <!-- Contact Message Form -->
            <div style="background:#ffffff; border-radius:16px; padding:2.5rem; border:1px solid var(--border-light); box-shadow:var(--shadow-md);">
                <h3 style="font-size:1.6rem; margin-bottom:0.5rem;">Stuur ons een bericht</h3>
                <p style="color:var(--text-muted); margin-bottom:1.75rem;">Vul onderstaand formulier in en wij reageren zo spoedig mogelijk.</p>

                <form id="contactForm" method="post" action="/api/contact.php">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label class="form-label" style="color:var(--text-main);" for="c_name">Uw Naam *</label>
                        <input type="text" class="form-control" id="c_name" name="name" required style="background:#ffffff; color:#1c1917; border-color:#d6d3d1;">
                    </div>

                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label class="form-label" style="color:var(--text-main);" for="c_email">E-mailadres *</label>
                        <input type="email" class="form-control" id="c_email" name="email" required style="background:#ffffff; color:#1c1917; border-color:#d6d3d1;">
                    </div>

                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label class="form-label" style="color:var(--text-main);" for="c_phone">Telefoonnummer</label>
                        <input type="tel" class="form-control" id="c_phone" name="phone" style="background:#ffffff; color:#1c1917; border-color:#d6d3d1;">
                    </div>

                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label class="form-label" style="color:var(--text-main);" for="c_message">Uw Bericht *</label>
                        <textarea class="form-control" id="c_message" name="message" rows="5" required style="background:#ffffff; color:#1c1917; border-color:#d6d3d1;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
                        Bericht Versturen
                    </button>
                </form>
                <div id="contactFeedback"></div>
            </div>
        </div>
    </div>
</section>

<?php require_once VIEW_PATH . '/components/footer.php'; ?>
