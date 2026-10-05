<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/FormSubmission.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode niet toegestaan.']);
    exit;
}

// CSRF check
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Ongeldige sessiebeveiliging. Vernieuw de pagina en probeer het opnieuw.']);
    exit;
}

// Input validation
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$date = trim($_POST['date'] ?? '');
$time = trim($_POST['time'] ?? '');
$guests = (int)($_POST['guests'] ?? 0);
$buffetType = trim($_POST['buffet_type'] ?? '');
$notes = trim($_POST['notes'] ?? '');

$errors = [];
if (empty($name)) $errors[] = 'Vul uw naam in.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Vul een geldig e-mailadres in.';
if (empty($phone)) $errors[] = 'Vul een telefoonnummer in.';
if (empty($date)) $errors[] = 'Kies een gewenste datum.';
if ($guests < 1) $errors[] = 'Vul een geldig aantal personen in.';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$submissionData = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'date' => $date,
    'time' => $time,
    'guests' => $guests,
    'buffet_type' => $buffetType,
    'notes' => $notes
];

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

$id = FormSubmission::create('reservation_form', $submissionData, $ip, $ua, 1);

echo json_encode([
    'success' => true,
    'message' => 'Hartelijk dank voor uw aanvraag! We hebben uw gegevens ontvangen en nemen spoedig contact met u op.',
    'id' => $id
]);
