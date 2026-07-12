<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status IN ('pending','processing') ORDER BY id DESC");
$orders->execute([$user['id']]);
$orders = $orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cancel Order - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="brand"><div class="logo-circle">FEDCO<br>LAUNDRY<br>HUB</div> FEDCO Laundry Hub</div>
  <nav><a href="support.php">&larr; Back to Support</a><a href="../logout.php" class="logout-btn" style="margin-left:16px;">Log Out</a></nav>
</div>
<div class="wrap" style="max-width:480px;">
  <h1 class="page-title">Cancel Order</h1>
  <div class="card" style="text-align:center;">
    <?php if (!$orders): ?>
      <p class="empty-note">Walang order na puwedeng kanselahin.</p>
    <?php else: ?>
    <form method="POST" action="cancel_process.php">
      <label class="field-label" style="text-align:left;">Select order to cancel</label>
      <select name="order_id" required>
        <?php foreach ($orders as $o): ?>
          <option value="<?= $o['id'] ?>">Order #<?= h($o['order_code']) ?> &middot; <?= h($o['service_type']) ?></option>
        <?php endforeach; ?>
      </select>
      <label class="field-label" style="text-align:left;">Reason (optional)</label>
      <textarea name="reason" rows="3" placeholder="Bakit mo gustong kanselahin?"></textarea>
      <p style="font-size:14px;font-weight:700;margin:20px 0;">Are you sure you want to cancel your order?</p>
      <div class="form-actions">
        <button type="submit" class="btn btn-navy">Confirm Cancel</button>
        <a href="support.php" style="flex:1;"><button type="button" class="btn btn-outline" style="width:100%;">Never mind</button></a>
      </div>
    </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
