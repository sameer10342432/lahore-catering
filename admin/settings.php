<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Setting.php';

$pageTitle = 'Website Instellingen';
$activePage = 'settings';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token)) {
        foreach ($_POST['settings'] as $key => $val) {
            Setting::set($key, trim($val));
        }
        $message = 'Instellingen succesvol opgeslagen!';
    }
}

$settings = Setting::all();
$csrfToken = generate_csrf_token();
require_once __DIR__ . '/includes/header.php';
?>

<div style="max-width:800px;">
    <h2 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;">Algemene Instellingen & Contactgegevens</h2>

    <?php if ($message): ?>
        <div style="background:#dcfce7; color:#15803d; padding:0.85rem 1.25rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;"><?= e($message) ?></div>
    <?php endif; ?>

    <div class="admin-panel">
        <div class="panel-body">
            <form method="post" action="">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <h4 style="font-size:1.1rem; margin-bottom:1rem; color:var(--admin-primary);">Bedrijfs- & Contactgegevens</h4>

                <div>
                    <label class="form-label" for="site_name">Website Naam</label>
                    <input type="text" class="form-input" id="site_name" name="settings[site_name]" value="<?= e($settings['site_name'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="contact_phone">Telefoonnummer (Weergave)</label>
                    <input type="text" class="form-input" id="contact_phone" name="settings[contact_phone]" value="<?= e($settings['contact_phone'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="contact_email">E-mailadres</label>
                    <input type="email" class="form-input" id="contact_email" name="settings[contact_email]" value="<?= e($settings['contact_email'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="contact_address">Adres</label>
                    <input type="text" class="form-input" id="contact_address" name="settings[contact_address]" value="<?= e($settings['contact_address'] ?? '') ?>">
                </div>

                <h4 style="font-size:1.1rem; margin:1.5rem 0 1rem; color:var(--admin-primary);">Openingstijden & Externe Links</h4>

                <div>
                    <label class="form-label" for="opening_hours_weekday">Openingstijden Maandag - Donderdag</label>
                    <input type="text" class="form-input" id="opening_hours_weekday" name="settings[opening_hours_weekday]" value="<?= e($settings['opening_hours_weekday'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="opening_hours_weekend">Openingstijden Vrijdag - Zondag</label>
                    <input type="text" class="form-input" id="opening_hours_weekend" name="settings[opening_hours_weekend]" value="<?= e($settings['opening_hours_weekend'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="order_online_url">Online Bestellen URL (Sides / Webshop)</label>
                    <input type="url" class="form-input" id="order_online_url" name="settings[order_online_url]" value="<?= e($settings['order_online_url'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="facebook_url">Facebook Pagina URL</label>
                    <input type="url" class="form-input" id="facebook_url" name="settings[facebook_url]" value="<?= e($settings['facebook_url'] ?? '') ?>">
                </div>

                <div style="margin-top:1.5rem;">
                    <button type="submit" class="btn-adm btn-adm-primary" style="padding:0.85rem 2rem; font-size:1rem;">
                        Instellingen Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
