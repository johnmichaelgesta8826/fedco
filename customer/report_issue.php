<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$orders->execute([$user['id']]);
$orders = $orders->fetchAll();
$error = $_SESSION['issue_error'] ?? '';
unset($_SESSION['issue_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report an Issue - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="brand"><div class="logo-circle">FEDCO<br>LAUNDRY<br>HUB</div> FEDCO Laundry Hub</div>
  <nav><a href="support.php">&larr; Back to Support</a><a href="../logout.php" class="logout-btn" style="margin-left:16px;">Log Out</a></nav>
</div>
<div class="wrap" style="max-width:480px;">
  <h1 class="page-title">Report an Issue</h1>
  <p class="page-sub">Tell us what went wrong so we can make it right</p>
  <div class="card">
    <?php if (!$orders): ?>
      <p class="empty-note">Wala ka pang order na puwedeng i-report.</p>
    <?php else: ?>
    <form method="POST" action="report_issue_process.php">
      <label class="field-label">Select order</label>
      <select name="order_id" required>
        <?php foreach ($orders as $o): ?>
          <option value="<?= $o['id'] ?>">Order #<?= h($o['order_code']) ?> &middot; <?= h($o['service_type']) ?> &middot; <?= h($o['weight_kg']) ?></option>
        <?php endforeach; ?>
      </select>
      <label class="field-label">Issue type</label>
      <select name="issue_type" required>
        <option value="">Select issue type</option>
        <option>Damaged item(s)</option>
        <option>Missing item(s)</option>
        <option>Wrong order received</option>
        <option>Late delivery</option>
        <option>Other</option>
      </select>
      <label class="field-label">Describe the issue</label>
      <textarea name="description" rows="4" placeholder="Please give us more details" required></textarea>
      <div class="error-text"><?= h($error) ?></div>
      <button type="submit" class="btn btn-navy btn-block">Submit Report</button>
    </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
