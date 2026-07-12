<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('admin', '../login.php');

$order_id = (int)($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? '';
$redirect_status = $_POST['redirect_status'] ?? 'all';
$valid = ['pending','processing','done','cancelled'];

if ($order_id && in_array($status, $valid)) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
}

$qs = in_array($redirect_status, $valid) ? "?status=$redirect_status" : "";
header("Location: dashboard.php$qs");
exit;
