<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/FormSubmission.php';

$token = generate_csrf_token();

$data = [
    'csrf_token' => $token,
    'name' => 'Jan de Vries',
    'email' => 'jan.devries@example.nl',
    'phone' => '0612345678',
    'date' => '2026-11-20',
    'time' => '18:30',
    'guests' => 35,
    'buffet_type' => 'Chef Special Buffet (€22,50 p.p.)',
    'notes' => 'Graag inclusief 5 vegetarische maaltijden.'
];

$_POST = $data;
$_SERVER['REQUEST_METHOD'] = 'POST';

ob_start();
require __DIR__ . '/../api/reserve.php';
$output = ob_get_clean();

echo "API Response: $output\n";

$latest = Database::fetch("SELECT * FROM form_submissions ORDER BY id DESC LIMIT 1");
echo "Inserted submission in DB:\n";
print_r($latest);
