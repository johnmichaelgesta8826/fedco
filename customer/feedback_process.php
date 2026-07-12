<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$rating = (int)($_POST['rating'] ?? 0);
$comments = trim($_POST['comments'] ?? '');

if ($rating < 1 || $rating > 5) {
    $_SESSION['fb_error'] = 'Please select a star rating before submitting.';
    header("Location: feedback.php");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO feedback (user_id, rating, comments) VALUES (?,?,?)");
$stmt->execute([$user['id'], $rating, $comments]);

header("Location: support.php?msg=feedback_sent");
exit;
