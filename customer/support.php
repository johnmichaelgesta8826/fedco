<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$orders->execute([$user['id']]);
$orders = $orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Support - FEDCO Laundry Hub</title>
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
  <?php
  $msgs = [
    'reschedule_sent' => 'Reschedule request submitted! Our team will confirm shortly.',
    'report_sent' => 'Issue reported! Our support team will get back to you within 24 hours.',
    'order_cancelled' => 'Order cancelled successfully.',
    'feedback_sent' => 'Thank you for your feedback!',
  ];
  $msgKey = $_GET['msg'] ?? '';
  if (isset($msgs[$msgKey])):
  ?>
  <div class="card" style="background:var(--green-bg);color:var(--green);font-weight:700;text-align:center;"><?= h($msgs[$msgKey]) ?></div>
  <?php endif; ?>

  <div class="card" style="background:linear-gradient(135deg,var(--navy-2),var(--navy));color:#fff;">
    <strong style="font-size:15px;">Need Help?</strong>
    <p style="font-size:11.5px;opacity:.9;">Our support team is ready to assist you with your laundry orders, delivery issues, or any questions.</p>
    <div style="display:flex;gap:8px;">
      <span style="background:rgba(255,255,255,.15);padding:6px 12px;border-radius:12px;font-size:11px;">📞 09-XXXXXXXXX</span>
      <span style="background:rgba(255,255,255,.15);padding:6px 12px;border-radius:12px;font-size:11px;">✉ fedco@gmail.com</span>
    </div>
  </div>

  <?php if (!$orders): ?>
    <div class="card"><p class="empty-note">Wala ka pang order. Gumawa muna ng booking sa Home para magamit ang Reschedule, Report Issue, o Cancel Order.</p></div>
  <?php else: ?>
  <div class="quick-actions">
    <a href="reschedule.php" style="text-decoration:none;flex:1;"><button class="qa-btn" style="width:100%;"><span class="emoji">📅</span>Reschedule</button></a>
    <a href="report_issue.php" style="text-decoration:none;flex:1;"><button class="qa-btn" style="width:100%;"><span class="emoji">⚠️</span>Report Issue</button></a>
    <a href="cancel.php" style="text-decoration:none;flex:1;"><button class="qa-btn" style="width:100%;"><span class="emoji">❌</span>Cancel Order</button></a>
  </div>
  <?php endif; ?>

  <div class="section-title">Customer Feedback &amp; Suggestions</div>
  <div class="card" style="text-align:center;">
    <p style="font-size:12.5px;color:var(--text-muted);margin:0 0 4px;">We'd love to hear from you! Tell us how we're doing.</p>
    <a href="feedback.php"><button class="btn btn-navy">Give Feedback</button></a>
  </div>
</div>
</body>
</html>
