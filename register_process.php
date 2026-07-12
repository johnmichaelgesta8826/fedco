<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$full_name = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$agree = isset($_POST['agree']);

$_SESSION['signup_old'] = compact('full_name', 'phone', 'email', 'address');

function fail($msg) {
    $_SESSION['signup_error'] = $msg;
    header("Location: register.php");
    exit;
}

if (!$full_name || !$phone || !$email || !$address || !$password || !$confirm) {
    fail('Please fill out all fields.');
}
if (!$agree) {
    fail('Please agree to the Terms of Service and Privacy Policy.');
}
if ($password !== $confirm) {
    fail('Passwords do not match.');
}
if (strlen($password) < 6) {
    fail('Password must be at least 6 characters.');
}

$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);
if ($check->fetch()) {
    fail('Email is already registered. Please log in instead.');
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, address, password, role) VALUES (?,?,?,?,?,'customer')");
$stmt->execute([$full_name, $email, $phone, $address, $hash]);

unset($_SESSION['signup_old']);
$_SESSION['login_error'] = 'Account created! You can now log in.';
header("Location: login.php");
exit;
