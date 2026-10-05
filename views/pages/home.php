<?php
/**
 * Homepage View - Lahore Catering
 * Modern Light Culinary Design Preserving 100% Dutch Content
 */

$metaTitle = 'Lahore Catering Friesland | Authentieke Pakistaanse & Indiase Keuken';
$metaDesc = 'Lahore Catering Friesland - Vanavond Pakistaans? Heerlijke catering voor bruiloften, feesten en evenementen, buffetten en thuisbezorging in heel Friesland.';
$canonicalUrl = CANONICAL_DOMAIN . '/';
$ogImage = SITE_URL . '/uploads/2026/10/Pakistani-Feast-with-Frisian-Charm.png';

require_once VIEW_PATH . '/components/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <span class="section-badge">✨ Welkom bij Lahore Catering Friesland</span>
            <h1>Authentieke <span>Pakistaanse Keuken</span> in Friesland</h1>
            <p class="hero-lead">
                Vanavond Pakistaans? Wij bezorgen heerlijke maaltijden aan huis én verzorgen complete catering voor al uw feesten, bruiloften, bedrijfsjubilea en evenementen.
            </p>
            <div class="hero-actions">
                <a href="#reserveren" class="btn btn-primary btn-lg">
                    Catering Offerte Aanvragen
                </a>
                <a href="<?= e(ORDER_SIDES_URL) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-lg">
                    Direct Eten Bestellen
                </a>
                <a href="tel:<?= e(CONTACT_PHONE_RAW) ?>" class="btn btn-outline btn-lg">
                    📞 <?= e(CONTACT_PHONE) ?>
                </a>
            </div>
            <div class="hero-badges">
                <div class="badge-item">
                    <span style="color:#15803d; font-size:1.2rem;">✓</span> 100% Halal Gecertificeerd
                </div>
                <div class="badge-item">
                    <span style="color:#15803d; font-size:1.2rem;">✓</span> Levering in Heel Friesland
                </div>
                <div class="badge-item">
                    <span style="color:#15803d; font-size:1.2rem;">✓</span> Verse Authentieke Kruiden
                </div>
            </div>
        </div>

        <div class="hero-image-wrap">
            <div class="hero-image-card">
                <img src="/uploads/2026/10/A-Warm-Pakistani-Feast-Spread.png" alt="Pakistaanse buffet en catering gerechten" width="580" height="480">
            </div>
            <div class="hero-floating-card">
                <div class="floating-icon">⭐</div>
                <div>
                    <strong style="display:block; font-size:1.05rem; color:#1c1917;">De Molen Buffet</strong>
                    <span style="font-size:0.85rem; color:#57534e;">Vrouwenparochie & Heel Nederland</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Navigation / Portal Cards -->
<section style="padding: 1.5rem 0 3.5rem;">
    <div class="container">
        <div class="portal-cards">
            <!-- 1. Lahore Pakistaanse Keuken -->
            <a href="<?= e(ORDER_SIDES_URL) ?>" target="_blank" rel="noopener" class="portal-card">
                <img src="/uploads/2026/02/Rectangle-2.png" alt="Lahore Pakistaanse Keuken" class="portal-thumb">
                <div class="portal-info">
                    <h3>Lahore Pakistaanse keuken</h3>
                    <p>Pakistaans bezorg- & afhaalrestaurant</p>
                    <span class="portal-loc">Stiens &bull; Leeuwarden &bull; St. Anna</span>
                </div>
            </a>

            <!-- 2. De Molen - Pakistaans Buffet -->
            <a href="/buffet/" class="portal-card">
                <img src="/uploads/2026/02/Rectangle-4.png" alt="De Molen Pakistaans Buffet" class="portal-thumb">
                <div class="portal-info">
                    <h3>De Molen – Pakistaans Buffet</h3>
                    <p>Sfeervol buffetrestaurant</p>
                    <span class="portal-loc">Vrouwbuurtstermolen 2, Vrouwenparochie</span>
                </div>
            </a>

            <!-- 3. Lahore Grill & Burger -->
            <a href="<?= e(ORDER_SIDES_URL) ?>" target="_blank" rel="noopener" class="portal-card">
                <img src="/uploads/2026/02/Rectangle-4-1.png" alt="Lahore Grill & Burger" class="portal-thumb">
                <div class="portal-info">
                    <h3>Lahore Grill & Burger</h3>
                    <p>Fast Food bezorg- & afhaalrestaurant</p>
                    <span class="portal-loc">St. Anna &bull; Stiens &bull; Leeuwarden</span>
                </div>
            </a>

            <!-- 4. Boek een foodtruck -->
            <a href="/foodtruck/" class="portal-card">
                <img src="/uploads/2026/02/Rectangle-4-2.png" alt="Boek een foodtruck" class="portal-thumb">
                <div class="portal-info">
                    <h3>Boek een foodtruck</h3>
                    <p>Pakistaanse foodtruck voor evenementen</p>
                    <span class="portal-loc">Heel Nederland</span>
                </div>
            </a>

            <!-- 5. Catering voor evenementen -->
            <a href="/catering/" class="portal-card">
                <img src="/uploads/2026/02/Rectangle-4-3.png" alt="Catering voor evenementen" class="portal-thumb">
                <div class="portal-info">
                    <h3>Catering voor evenementen</h3>
                    <p>Pakistaans catering voor feesten & meer</p>
                    <span class="portal-loc">Heel Nederland</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- About & Catering Presentation Section -->
<section class="section section-alt">
    <div class="container">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:3.5rem; align-items:center;">
            <div>
                <span class="section-badge">Pakistaanse Catering</span>
                <h2 style="font-size:2.4rem; margin-bottom:1.25rem;">Voor al uw feesten en evenementen in Friesland</h2>
                <p style="font-size:1.1rem; line-height:1.7;">
                    Wij bezorgen ook maaltijden thuis. Bestel direct online via de bestelknop in het menu.
                </p>
                <p style="line-height:1.7;">
                    <strong>Lahore Catering Friesland</strong> verzorgt uw speciale wensen bij allerlei evenementen, zoals braderieën, huwelijksfeesten, verjaardagen, bedrijfsjubileums, bedrijfsfeesten en conferenties.
                </p>
                <p style="line-height:1.7;">
                    Ook heel interessant zijn de <strong>workshops Pakistaanse maaltijden</strong> waarbij u op een ontspannen en leuke wijze kennis maakt met de geheimen en specerijen van de Pakistaanse keuken.
                </p>
                <div style="margin-top:2rem; display:flex; gap:1rem; align-items:center;">
                    <a href="#reserveren" class="btn btn-primary">Vraag Vrijblijvend Aan</a>
                    <a href="/catering/" class="btn btn-outline">Meer over Catering</a>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:1rem;">
                <div style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md);">
                    <img src="/uploads/2018/10/DSC_0200-1024x683.jpg" alt="Lahore Catering buffet presentatie" style="width:100%; height:220px; object-fit:cover;">
                </div>
                <div style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md);">
                    <img src="/uploads/2018/10/DSC_0763-1024x683.jpg" alt="Verse gerechten Lahore Catering" style="width:100%; height:220px; object-fit:cover;">
                </div>
                <div style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md);">
                    <img src="/uploads/2018/10/DSC_0759-1024x683.jpg" alt="Traditioneel bereide curry" style="width:100%; height:220px; object-fit:cover;">
                </div>
                <div style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md); background:#ffffff; display:flex; flex-direction:column; justify-content:center; align-items:center; padding:1.5rem; text-align:center;">
                    <img src="/uploads/2018/10/DIGITAL-Logo-Lahore-Catering-300x99.png" alt="Lahore Catering Logo" style="max-height:60px; width:auto; margin-bottom:0.75rem;">
                    <span style="font-size:0.85rem; font-weight:700; color:#b45309;">Sinds jaren uw vertrouwde cateraar</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Buffet Packages (Ons Aanbod) -->
<section class="section" id="buffetten">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Ons Aanbod</span>
            <h2 class="section-title">Welk menu biedt u uw gasten aan?</h2>
            <p class="section-subtitle">
                Kies uit onze met zorg samengestelde buffetten voor groepen en feesten. Elk buffet wordt compleet warm geleverd inclusief sauzen en bijgerechten.
            </p>
        </div>

        <div class="buffet-grid">
            <!-- 1. Budget Buffet -->
            <div class="buffet-card">
                <div class="buffet-image">
                    <img src="/uploads/2023/11/chicken-tikka-masala-2023-11-27-04-59-40-utc-scaled.jpg" alt="Budget Buffet" loading="lazy">
                    <span class="buffet-badge">€18,50 p.p.</span>
                </div>
                <div class="buffet-body">
                    <h3 class="buffet-title">Budget Buffet</h3>
                    <p class="buffet-tagline">Ideaal voor grote groepen en evenementen</p>
                    <p class="buffet-items">
                        Samosa’s, Chicken Korma, Kofta Curry, Tandoori Chicken, Dal Makhni, Basmati Rice, Naan, Saus
                    </p>
                    <a href="#reserveren" class="btn btn-primary" style="margin-top:auto;">
                        Offerte Aanvragen &rarr;
                    </a>
                </div>
            </div>

            <!-- 2. Chef's Special Buffet -->
            <div class="buffet-card" style="border-color:var(--primary); box-shadow:var(--shadow-hover);">
                <div class="buffet-image">
                    <img src="/uploads/2023/11/butter-chicken-2021-08-28-03-09-17-utc-scaled.jpg" alt="Chef Special Buffet" loading="lazy">
                    <span class="buffet-badge" style="background:#d97706; color:#ffffff;">Populair &bull; €22,50 p.p.</span>
                </div>
                <div class="buffet-body">
                    <h3 class="buffet-title">Chef's Special Buffet</h3>
                    <p class="buffet-tagline">De favoriet van onze chef-kok</p>
                    <p class="buffet-items">
                        Samosa’s, Chicken Curry, Lamb Baryani, Butter Chicken, Tandoori Fish, Mixed Vegetables, Basmati Rice, Naan, Saus
                    </p>
                    <a href="#reserveren" class="btn btn-primary" style="margin-top:auto;">
                        Offerte Aanvragen &rarr;
                    </a>
                </div>
            </div>

            <!-- 3. Vegetarisch Buffet -->
            <div class="buffet-card">
                <div class="buffet-image">
                    <img src="/uploads/2023/11/indian-channa-masala-with-chickpeas-and-naan-bread-2023-11-27-05-02-54-utc-scaled.jpg" alt="Vegetarisch Buffet" loading="lazy">
                    <span class="buffet-badge">€15,50 p.p.</span>
                </div>
                <div class="buffet-body">
                    <h3 class="buffet-title">Vegetarisch Buffet</h3>
                    <p class="buffet-tagline">Heerlijk en puur vegetarisch genieten</p>
                    <p class="buffet-items">
                        Samosa’s, Aloo Palak, Mix Vegetarian, Channa Masala, Vegetarian Baryani, Rice, Naan, Saus
                    </p>
                    <a href="#reserveren" class="btn btn-primary" style="margin-top:auto;">
                        Offerte Aanvragen &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reservation Form Section -->
<section class="section" style="padding-top:0;">
    <div class="container">
        <?php require VIEW_PATH . '/components/contact-form.php'; ?>
    </div>
</section>

<!-- Extra Features: Maatwerk, Workshops, Entertainment -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Extra Mogelijkheden</span>
            <h2 class="section-title">Ook heel interessant</h2>
            <p class="section-subtitle">Maak uw evenement compleet met entertainment, maatwerk of kookworkshops.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:2rem;">
            <!-- Maatwerk -->
            <div style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); border:1px solid var(--border-light); padding:2rem;">
                <div style="height:180px; margin:-2rem -2rem 1.5rem; overflow:hidden;">
                    <img src="/uploads/2018/10/maatwerk.jpg" alt="Maatwerk catering" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <h3 style="font-size:1.3rem; margin-bottom:0.75rem;">Maatwerk</h3>
                <p style="color:var(--text-muted); line-height:1.6;">
                    Op zoek naar iets anders of twijfelt u of onze selectie buffetten bij uw gelegenheid passen? Wij denken graag met u mee; maatwerk is altijd mogelijk!
                </p>
                <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="btn btn-outline btn-sm" style="margin-top:1rem;">
                    Informeer naar Maatwerk &rarr;
                </a>
            </div>

            <!-- Workshops -->
            <div style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); border:1px solid var(--border-light); padding:2rem;">
                <div style="height:180px; margin:-2rem -2rem 1.5rem; overflow:hidden;">
                    <img src="/uploads/2018/10/workshops.jpg" alt="Pakistaanse kookworkshops" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <h3 style="font-size:1.3rem; margin-bottom:0.75rem;">Workshops</h3>
                <p style="color:var(--text-muted); line-height:1.6;">
                    Ook heel interessant zijn onze workshops Pakistaanse maaltijden, waarbij u op een leuke, interactieve wijze kennis maakt met de bereiding en geheimen van de Pakistaanse keuken.
                </p>
                <a href="/contact/" class="btn btn-outline btn-sm" style="margin-top:1rem;">
                    Workshop Boeken &rarr;
                </a>
            </div>

            <!-- Entertainment -->
            <div style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); border:1px solid var(--border-light); padding:2rem;">
                <div style="height:180px; margin:-2rem -2rem 1.5rem; overflow:hidden;">
                    <img src="/uploads/2018/10/davidsokoloff.jpg" alt="Pakistaanse livemuziek en entertainment" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <h3 style="font-size:1.3rem; margin-bottom:0.75rem;">Entertainment</h3>
                <p style="color:var(--text-muted); line-height:1.6;">
                    Als entertainment voor uw gelegenheid kunnen we ook Pakistaanse livemuziek organiseren met traditionele instrumenten en/of een professionele goochelaar voor een onvergetelijke beleving.
                </p>
                <a href="/contact/" class="btn btn-outline btn-sm" style="margin-top:1rem;">
                    Vraag Entertainment Aan &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Popular Dishes from Menu -->
<?php
$featuredDishes = Database::fetchAll("SELECT * FROM food_items WHERE image_url IS NOT NULL ORDER BY id ASC LIMIT 8");
if (!empty($featuredDishes)):
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Onze Keuken</span>
            <h2 class="section-title">Populaire Pakistaanse Gerechten</h2>
            <p class="section-subtitle">Bereid volgens authentieke familierecepten met verse specerijen en kwaliteitsingrediënten.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:1.5rem;">
            <?php foreach ($featuredDishes as $dish): ?>
            <div style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); border:1px solid var(--border-light); transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
                <div style="height:170px; overflow:hidden;">
                    <img src="<?= e($dish['image_url']) ?>" alt="<?= e($dish['image_alt'] ?: $dish['name']) ?>" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                </div>
                <div style="padding:1.25rem;">
                    <span style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:#b45309; letter-spacing:0.05em;"><?= e($dish['category']) ?></span>
                    <h4 style="font-size:1.1rem; margin:0.35rem 0 0.5rem;"><?= e($dish['name']) ?></h4>
                    <p style="font-size:0.85rem; color:var(--text-muted); line-height:1.5; margin-bottom:0.75rem;"><?= e($dish['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent Blog Articles Section -->
<?php
$recentPosts = Database::fetchAll("SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
if (!empty($recentPosts)):
?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Culinaire Blogs & Tips</span>
            <h2 class="section-title">Laatste Nieuws & Artikelen</h2>
            <p class="section-subtitle">Lees alles over de Pakistaanse keuken, catering tips en evenementen in Friesland.</p>
        </div>

        <div class="blog-grid">
            <?php foreach ($recentPosts as $post): ?>
                <?php require VIEW_PATH . '/components/blog-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center; margin-top:3rem;">
            <a href="/blog/" class="btn btn-outline btn-lg">
                Bekijk Alle 258 Artikelen in Onze Blog &rarr;
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once VIEW_PATH . '/components/footer.php'; ?>
