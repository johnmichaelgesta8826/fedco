<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$total = $pdo->prepare("SELECT COUNT(*) c FROM orders WHERE user_id = ?");
$total->execute([$user['id']]); $total = $total->fetch()['c'];

$inprog = $pdo->prepare("SELECT COUNT(*) c FROM orders WHERE user_id = ? AND status IN ('pending','processing')");
$inprog->execute([$user['id']]); $inprog = $inprog->fetch()['c'];

$done = $pdo->prepare("SELECT COUNT(*) c FROM orders WHERE user_id = ? AND status = 'done'");
$done->execute([$user['id']]); $done = $done->fetch()['c'];

$initials = strtoupper(substr($user['full_name'],0,1) . substr(strrchr($user['full_name'],' ') ?: '',1,1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile - FEDCO Laundry Hub</title>
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

<div class="wrap" style="max-width:480px;">
  <div class="card" style="background:linear-gradient(135deg,var(--navy-2),var(--navy));color:#fff;display:flex;align-items:center;gap:14px;">
    <div style="width:58px;height:58px;border-radius:50%;background:#fff;color:var(--navy);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;flex-shrink:0;"><?= h($initials) ?></div>
    <div>
      <p style="font-weight:700;margin:0 0 2px;"><?= h($user['full_name']) ?></p>
      <p style="font-size:11px;opacity:.85;margin:0;"><?= h($user['email']) ?></p>
    </div>
  </div>

  <div class="stat-row">
    <div class="stat-box" style="text-align:center;"><strong><?= $total ?></strong><span>Total Orders</span></div>
    <div class="stat-box" style="text-align:center;"><strong><?= $inprog ?></strong><span>In Progress</span></div>
    <div class="stat-box" style="text-align:center;"><strong><?= $done ?></strong><span>Completed</span></div>
  </div>

  <div class="section-title" style="margin-top:0;">Orders &amp; Support</div>
  <div class="card">
    <a href="support.php" style="display:block;padding:10px 0;border-bottom:1px solid #f0f0f0;">📋 My Orders &amp; Support</a>
    <a href="support.php" style="display:block;padding:10px 0;">💬 Help &amp; Support</a>
  </div>

  <a href="../logout.php"><button class="btn btn-danger btn-block">Log Out</button></a>
</div>
</body>
</html>
