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

$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Ongeldige sessiebeveiliging. Vernieuw de pagina en probeer het opnieuw.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if (empty($name)) $errors[] = 'Vul uw naam in.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Vul een geldig e-mailadres in.';
if (empty($message)) $errors[] = 'Vul een bericht in.';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$submissionData = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message
];

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

$id = FormSubmission::create('contact_form', $submissionData, $ip, $ua, 2);

echo json_encode([
    'success' => true,
    'message' => 'Bedankt voor uw bericht! We reageren zo snel mogelijk.',
    'id' => $id
]);
