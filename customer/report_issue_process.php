<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$order_id = (int)($_POST['order_id'] ?? 0);
$issue_type = trim($_POST['issue_type'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!$order_id || !$issue_type || !$description) {
    $_SESSION['issue_error'] = 'Please select an issue type and describe the problem.';
    header("Location: report_issue.php");
    exit;
}

$check = $pdo->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
$check->execute([$order_id, $user['id']]);
if (!$check->fetch()) { header("Location: support.php"); exit; }

$stmt = $pdo->prepare("INSERT INTO reports (order_id, user_id, issue_type, description) VALUES (?,?,?,?)");
$stmt->execute([$order_id, $user['id'], $issue_type, $description]);

header("Location: support.php?msg=report_sent");
exit;
