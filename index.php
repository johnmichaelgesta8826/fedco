<?php
require_once __DIR__ . '/includes/auth.php';
$user = current_user();
if ($user) {
    header("Location: " . ($user['role'] === 'admin' ? 'admin/dashboard.php' : 'customer/home.php'));
} else {
    header("Location: login.php");
}
exit;
