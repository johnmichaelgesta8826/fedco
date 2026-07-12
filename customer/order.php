<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details - FEDCO Laundry Hub</title>
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
  <div class="card">
    <div style="text-align:center;">
      <span class="badge badge-<?= $order['status'] ?>">Order #<?= h($order['order_code']) ?> &middot; <?= ucfirst($order['status']) ?></span>
    </div>
    <p style="text-align:center;color:var(--text-muted);font-size:11.5px;"><?= date('F j, Y \\· g:i A', strtotime($order['created_at'])) ?></p>
    <hr style="border:none;border-top:1px dashed #ccc;margin:14px 0;">
    <table style="width:100%;font-size:12.5px;">
      <tr><td style="color:var(--text-muted);padding:6px 0;">Customer</td><td style="text-align:right;font-weight:700;"><?= h($user['full_name']) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;">Service Type</td><td style="text-align:right;font-weight:700;"><?= h($order['service_type']) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;">Weight</td><td style="text-align:right;font-weight:700;"><?= h($order['weight_kg']) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;">Pick-up Date</td><td style="text-align:right;font-weight:700;"><?= date('M j, Y', strtotime($order['pickup_date'])) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;">Delivery Address</td><td style="text-align:right;font-weight:700;"><?= h($order['delivery_address']) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;">Special Instructions</td><td style="text-align:right;font-weight:700;"><?= h($order['special_instruction']) ?: '—' ?></td></tr>
    </table>
    <hr style="border:none;border-top:1px dashed #ccc;margin:14px 0;">
    <table style="width:100%;font-size:12.5px;">
      <tr><td style="color:var(--text-muted);padding:6px 0;">Base price</td><td style="text-align:right;font-weight:700;">₱<?= number_format($order['base_price'],2) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;"><?= h($order['service_type']) ?></td><td style="text-align:right;font-weight:700;">₱<?= number_format($order['service_fee'],2) ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0;">Delivery fee</td><td style="text-align:right;font-weight:700;">₱<?= number_format($order['delivery_fee'],2) ?></td></tr>
    </table>
    <hr style="border:none;border-top:1px dashed #ccc;margin:14px 0;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <span style="font-weight:800;font-size:15px;">TOTAL</span>
      <span style="font-weight:800;font-size:18px;color:var(--link-blue);">₱<?= number_format($order['total_amount'],2) ?></span>
    </div>
    <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">Payment Method: <?= h($order['payment_method']) ?></p>

    <a href="tracking.php?id=<?= $order['id'] ?>"><button class="btn btn-navy btn-block">Track Order</button></a>
    <a href="home.php"><button class="btn btn-light btn-block">Back to Home</button></a>
  </div>
</div>
</body>
</html>
