<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Admin.php';

if (Admin::isLoggedIn()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';
$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

// Rate limiting check via session or storage
$rateKey = 'login_attempts_' . md5($ip);
$attempts = $_SESSION[$rateKey] ?? ['count' => 0, 'first_attempt' => time()];

if ($attempts['count'] >= 5 && (time() - $attempts['first_attempt']) < 900) {
    $remaining = 15 - ceil((time() - $attempts['first_attempt']) / 60);
    $error = "Te veel mislukte inlogpogingen. Probeer het over $remaining minuten opnieuw.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Ongeldige sessiebeveiliging. Vernieuw de pagina.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Vul zowel gebruikersnaam als wachtwoord in.';
        } else {
            $admin = Admin::authenticate($username, $password);
            if ($admin) {
                unset($_SESSION[$rateKey]);
                Admin::login($admin);
                header('Location: /admin/index.php');
                exit;
            } else {
                $attempts['count']++;
                $_SESSION[$rateKey] = $attempts;
                $error = 'Onjuiste gebruikersnaam of wachtwoord.';
            }
        }
    }
}

$csrfToken = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen | Lahore Catering Beheer</title>
    <link rel="stylesheet" href="/admin/assets/admin.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicon.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png?v=2">
    <link rel="shortcut icon" href="/favicon.ico?v=2">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 1.5rem;
        }
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div style="text-align:center; margin-bottom:2rem;">
        <a href="/" style="display:inline-block; margin-bottom:1rem;" title="Naar website">
            <img src="/assets/images/logo.png" alt="Lahore Catering" style="height:56px; width:auto; max-width:220px; object-fit:contain;">
        </a>
        <h2 style="font-size:1.35rem; color:#1e293b; margin-bottom:0.25rem;">Beheerderspaneel</h2>
        <span style="font-size:0.85rem; color:#64748b;">Log in met uw beheerdersaccount</span>
    </div>

    <?php if (!empty($error)): ?>
        <div style="background:#fee2e2; color:#b91c1c; padding:0.85rem 1rem; border-radius:6px; font-size:0.9rem; margin-bottom:1.5rem;">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/admin/login.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div>
            <label class="form-label" for="username">Gebruikersnaam of E-mail</label>
            <input type="text" class="form-input" id="username" name="username" required autofocus placeholder="admin">
        </div>

        <div>
            <label class="form-label" for="password">Wachtwoord</label>
            <input type="password" class="form-input" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-adm btn-adm-primary" style="width:100%; justify-content:center; padding:0.85rem; font-size:1rem; margin-top:0.5rem;">
            Veilig Inloggen
        </button>
    </form>

    <div style="text-align:center; margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e2e8f0;">
        <a href="/" style="color:#64748b; font-size:0.85rem; text-decoration:none;">&larr; Terug naar de website</a>
    </div>
</div>

</body>
</html>
