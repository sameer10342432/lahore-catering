<?php
/**
 * 404 Not Found Page View
 */

http_response_code(404);
$metaTitle = 'Pagina niet gevonden (404) | Lahore Catering Friesland';
$metaDesc = 'Helaas is de opgevraagde pagina niet gevonden. Bekijk onze catering opties, buffetten of neem contact met ons op.';
$robots = 'noindex, follow';

require_once VIEW_PATH . '/components/header.php';
?>

<section class="section" style="min-height:60vh; display:flex; align-items:center;">
    <div class="container" style="text-align:center; max-width:680px;">
        <span class="section-badge" style="background:#fee2e2; color:#b91c1c;">404 Foutmelding</span>
        <h1 style="font-size:clamp(2.5rem, 5vw, 4rem); margin:1rem 0;">Oeps! Pagina niet gevonden</h1>
        <p style="font-size:1.15rem; color:var(--text-muted); margin-bottom:2rem;">
            De pagina die u zoekt bestaat helaas niet meer of is verplaatst. Geen zorgen, onze keuken staat altijd voor u open!
        </p>

        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; margin-bottom:2.5rem;">
            <a href="/" class="btn btn-primary btn-lg">Naar de Homepage</a>
            <a href="/catering/" class="btn btn-secondary btn-lg">Catering Informatie</a>
            <a href="/blog/" class="btn btn-outline btn-lg">Onze Blog</a>
        </div>

        <div style="padding:1.5rem; background:#ffffff; border-radius:12px; border:1px solid var(--border-light); text-align:left;">
            <h4 style="font-size:1.05rem; margin-bottom:0.75rem;">Zoekt u iets specifieks?</h4>
            <ul style="margin-bottom:0; display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; list-style:none; padding-left:0;">
                <li>&bull; <a href="/buffet/">De Molen – Pakistaans Buffet</a></li>
                <li>&bull; <a href="/foodtruck/">Foodtruck Huren</a></li>
                <li>&bull; <a href="/halal-catering-friesland/">Halal Catering Friesland</a></li>
                <li>&bull; <a href="/pakistaans-restaurant-leeuwarden/">Pakistaans Restaurant Leeuwarden</a></li>
                <li>&bull; <a href="/eten-bestellen-stiens/">Eten Bestellen Stiens</a></li>
                <li>&bull; <a href="/contact/">Direct Contact Opnemen</a></li>
            </ul>
        </div>
    </div>
</section>

<?php require_once VIEW_PATH . '/components/footer.php'; ?>
