<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('admin', '../login.php');
$admin = current_user();

$tab = $_GET['tab'] ?? 'reports';
$valid_tabs = ['reports','reschedules','cancels','feedback'];
if (!in_array($tab, $valid_tabs)) $tab = 'reports';

if ($tab === 'reports') {
    $rows = $pdo->query("SELECT r.*, u.full_name, u.phone, o.order_code FROM reports r
        JOIN users u ON u.id = r.user_id JOIN orders o ON o.id = r.order_id ORDER BY r.id DESC")->fetchAll();
} elseif ($tab === 'reschedules') {
    $rows = $pdo->query("SELECT r.*, u.full_name, u.phone, o.order_code FROM reschedules r
        JOIN users u ON u.id = r.user_id JOIN orders o ON o.id = r.order_id ORDER BY r.id DESC")->fetchAll();
} elseif ($tab === 'cancels') {
    $rows = $pdo->query("SELECT c.*, u.full_name, u.phone, o.order_code, o.service_type FROM cancellations c
        JOIN users u ON u.id = c.user_id JOIN orders o ON o.id = c.order_id ORDER BY c.id DESC")->fetchAll();
} else {
    $rows = $pdo->query("SELECT f.*, u.full_name, u.phone FROM feedback f
        JOIN users u ON u.id = f.user_id ORDER BY f.id DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Service - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="brand"><div class="logo-circle">FEDCO<br>LAUNDRY<br>HUB</div> FEDCO Admin Panel</div>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="customer_service.php">Customer Service</a>
    <a href="../logout.php" class="logout-btn" style="margin-left:16px;">Log Out</a>
  </nav>
</div>

<div class="wrap">
  <h1 class="page-title" style="text-align:left;">Customer Service</h1>
  <p class="page-sub" style="text-align:left;">Reports, Reschedule requests, Cancellations, at Feedback ng customers.</p>

  <div class="tabs">
    <a href="customer_service.php?tab=reports"><button class="tab-btn <?= $tab==='reports'?'active':'' ?>">Reports (<?= $tab==='reports'?count($rows):$pdo->query("SELECT COUNT(*) c FROM reports")->fetch()['c'] ?>)</button></a>
    <a href="customer_service.php?tab=reschedules"><button class="tab-btn <?= $tab==='reschedules'?'active':'' ?>">Reschedule</button></a>
    <a href="customer_service.php?tab=cancels"><button class="tab-btn <?= $tab==='cancels'?'active':'' ?>">Cancels</button></a>
    <a href="customer_service.php?tab=feedback"><button class="tab-btn <?= $tab==='feedback'?'active':'' ?>">Feedback</button></a>
  </div>

  <?php if (!$rows): ?>
    <div class="card"><p class="empty-note">Wala pang data dito.</p></div>
  <?php elseif ($tab === 'reports'): ?>
    <div class="card" style="padding:0;overflow-x:auto;">
      <table class="data-table">
        <tr><th>Order #</th><th>Customer</th><th>Issue Type</th><th>Description</th><th>Status</th><th>Date</th></tr>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= h($r['order_code']) ?></td>
          <td><?= h($r['full_name']) ?><br><span style="color:var(--text-muted);font-size:11px;"><?= h($r['phone']) ?></span></td>
          <td><?= h($r['issue_type']) ?></td>
          <td style="max-width:260px;"><?= h($r['description']) ?></td>
          <td><span class="badge <?= $r['status']==='open'?'badge-pending':'badge-done' ?>"><?= ucfirst($r['status']) ?></span></td>
          <td><?= date('M j, Y g:i A', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  <?php elseif ($tab === 'reschedules'): ?>
    <div class="card" style="padding:0;overflow-x:auto;">
      <table class="data-table">
        <tr><th>Order #</th><th>Customer</th><th>New Date</th><th>New Time</th><th>Reason</th><th>Requested</th></tr>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= h($r['order_code']) ?></td>
          <td><?= h($r['full_name']) ?><br><span style="color:var(--text-muted);font-size:11px;"><?= h($r['phone']) ?></span></td>
          <td><?= date('M j, Y', strtotime($r['new_date'])) ?></td>
          <td><?= date('g:i A', strtotime($r['new_time'])) ?></td>
          <td style="max-width:220px;"><?= h($r['reason']) ?: '—' ?></td>
          <td><?= date('M j, Y g:i A', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  <?php elseif ($tab === 'cancels'): ?>
    <div class="card" style="padding:0;overflow-x:auto;">
      <table class="data-table">
        <tr><th>Order #</th><th>Customer</th><th>Service</th><th>Reason</th><th>Cancelled On</th></tr>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= h($r['order_code']) ?></td>
          <td><?= h($r['full_name']) ?><br><span style="color:var(--text-muted);font-size:11px;"><?= h($r['phone']) ?></span></td>
          <td><?= h($r['service_type']) ?></td>
          <td style="max-width:220px;"><?= h($r['reason']) ?: '—' ?></td>
          <td><?= date('M j, Y g:i A', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  <?php else: ?>
    <div class="card" style="padding:0;overflow-x:auto;">
      <table class="data-table">
        <tr><th>Customer</th><th>Rating</th><th>Comments</th><th>Date</th></tr>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= h($r['full_name']) ?><br><span style="color:var(--text-muted);font-size:11px;"><?= h($r['phone']) ?></span></td>
          <td><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5-(int)$r['rating']) ?></td>
          <td style="max-width:280px;"><?= h($r['comments']) ?: '—' ?></td>
          <td><?= date('M j, Y g:i A', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
