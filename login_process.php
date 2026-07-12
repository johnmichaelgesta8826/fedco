<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'customer';

$_SESSION['login_role'] = $role;

if (!$email || !$password) {
    $_SESSION['login_error'] = 'Please enter both email and password.';
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ? LIMIT 1");
$stmt->execute([$email, $role]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Invalid email, password, or account type.';
    header("Location: login.php");
    exit;
}

// Successful login
$_SESSION['user'] = [
    'id'        => $user['id'],
    'full_name' => $user['full_name'],
    'email'     => $user['email'],
    'role'      => $user['role'],
];

if ($user['role'] === 'admin') {
    header("Location: admin/dashboard.php");
} else {
    header("Location: customer/home.php");
}
exit;
