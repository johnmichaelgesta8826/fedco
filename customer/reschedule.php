<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status IN ('pending','processing') ORDER BY id DESC");
$orders->execute([$user['id']]);
$orders = $orders->fetchAll();
$error = $_SESSION['resch_error'] ?? '';
unset($_SESSION['resch_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reschedule Pickup - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="brand"><div class="logo-circle">FEDCO<br>LAUNDRY<br>HUB</div> FEDCO Laundry Hub</div>
  <nav><a href="support.php">&larr; Back to Support</a><a href="../logout.php" class="logout-btn" style="margin-left:16px;">Log Out</a></nav>
</div>
<div class="wrap" style="max-width:480px;">
  <h1 class="page-title">Reschedule Pickup</h1>
  <p class="page-sub">Pick a new date and time for your laundry pickup</p>
  <div class="card">
    <?php if (!$orders): ?>
      <p class="empty-note">Walang order na puwedeng i-reschedule.</p>
    <?php else: ?>
    <form method="POST" action="reschedule_process.php">
      <label class="field-label">Select order</label>
      <select name="order_id" required>
        <?php foreach ($orders as $o): ?>
          <option value="<?= $o['id'] ?>">Order #<?= h($o['order_code']) ?> &middot; <?= h($o['service_type']) ?> &middot; <?= h($o['weight_kg']) ?></option>
        <?php endforeach; ?>
      </select>
      <label class="field-label">New pick-up date</label>
      <input type="date" name="new_date" required>
      <label class="field-label">New pick-up time</label>
      <input type="time" name="new_time" required>
      <label class="field-label">Reason (optional)</label>
      <textarea name="reason" rows="3" placeholder="Let us know why you're rescheduling"></textarea>
      <div class="error-text"><?= h($error) ?></div>
      <button type="submit" class="btn btn-navy btn-block">Submit Reschedule Request</button>
    </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
