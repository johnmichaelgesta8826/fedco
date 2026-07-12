<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user['id']]);
$order = $stmt->fetch();
if (!$order) { header("Location: home.php"); exit; }

$steps = ['pending' => 1, 'processing' => 2, 'done' => 3, 'cancelled' => -1];
$current = $steps[$order['status']] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tracking - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="brand"><div class="logo-circle">FEDCO<br>LAUNDRY<br>HUB</div> FEDCO Laundry Hub</div>
  <nav>
    <a href="home.php">Home</a>
    <a href="support.php">Support</a>
    <a href="profile.php">Profile</a>
    <a href="../logout.php" class="logout-btn" style="margin-left:16px;">Log Out</a>
  </nav>
</div>

<div class="wrap" style="max-width:520px;">
  <div class="card" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
      <strong>Order #<?= h($order['order_code']) ?></strong><br>
      <span style="font-size:11px;color:var(--text-muted);"><?= h($order['weight_kg']) ?> &middot; <?= h($order['service_type']) ?></span>
    </div>
    <span class="badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
  </div>

  <?php if ($order['status'] === 'cancelled'): ?>
    <div class="card" style="text-align:center;color:var(--red);font-weight:700;">This order has been cancelled.</div>
  <?php else: ?>
  <div class="card">
    <div class="section-title" style="margin-top:0;">📍 Order Timeline</div>
    <?php
    $tl = [
      1 => ['Order Received', 'Your order has been confirmed'],
      2 => ['Washing in Progress', 'Your clothes are being washed & dried'],
      3 => ['Delivered', 'Delivered to your address'],
    ];
    foreach ($tl as $stepNum => [$title, $desc]):
      $status = $stepNum < $current ? 'done' : ($stepNum === $current ? 'active' : 'upcoming');
    ?>
      <div style="display:flex;gap:12px;padding-bottom:18px;">
        <div style="width:24px;height:24px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;font-weight:700;
          background:<?= $status==='done' ? 'var(--navy)' : ($status==='active' ? 'var(--orange)' : '#ccc') ?>;">
          <?= $status==='done' ? '&#10003;' : ($status==='active' ? '&#9679;' : '&#8801;') ?>
        </div>
        <div>
          <strong style="font-size:12.5px;<?= $status==='active' ? 'color:var(--orange);' : '' ?>"><?= $title ?></strong>
          <div style="font-size:11px;color:var(--text-muted);"><?= $desc ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <a href="home.php"><button class="btn btn-light btn-block">Back to Home</button></a>
</div>
</body>
</html>
