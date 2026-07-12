<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$order_id = (int)($_POST['order_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

$check = $pdo->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ? AND status IN ('pending','processing')");
$check->execute([$order_id, $user['id']]);
if (!$check->fetch()) { header("Location: support.php"); exit; }

$pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?")->execute([$order_id]);
$pdo->prepare("INSERT INTO cancellations (order_id, user_id, reason) VALUES (?,?,?)")->execute([$order_id, $user['id'], $reason]);

header("Location: support.php?msg=order_cancelled");
exit;
