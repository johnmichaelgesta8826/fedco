<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$order_id = (int)($_POST['order_id'] ?? 0);
$new_date = trim($_POST['new_date'] ?? '');
$new_time = trim($_POST['new_time'] ?? '');
$reason = trim($_POST['reason'] ?? '');

if (!$order_id || !$new_date || !$new_time) {
    $_SESSION['resch_error'] = 'Please select a new pick-up date and time.';
    header("Location: reschedule.php");
    exit;
}

// tiyakin sa atin talaga ang order na ito
$check = $pdo->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
$check->execute([$order_id, $user['id']]);
if (!$check->fetch()) { header("Location: support.php"); exit; }

$stmt = $pdo->prepare("INSERT INTO reschedules (order_id, user_id, new_date, new_time, reason) VALUES (?,?,?,?,?)");
$stmt->execute([$order_id, $user['id'], $new_date, $new_time, $reason]);

header("Location: support.php?msg=reschedule_sent");
exit;
