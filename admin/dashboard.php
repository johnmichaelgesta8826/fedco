<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('admin', '../login.php');
$admin = current_user();

$filter = $_GET['status'] ?? 'all';
$valid = ['pending','processing','done','cancelled'];

$counts = [];
foreach ($valid as $v) {
    $c = $pdo->prepare("SELECT COUNT(*) c FROM orders WHERE status = ?");
    $c->execute([$v]);
    $counts[$v] = $c->fetch()['c'];
}
$total_orders = array_sum($counts);

if (in_array($filter, $valid)) {
    $stmt = $pdo->prepare("SELECT o.*, u.full_name, u.phone FROM orders o JOIN users u ON u.id = o.user_id WHERE o.status = ? ORDER BY o.id DESC");
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query("SELECT o.*, u.full_name, u.phone FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.id DESC");
}
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - FEDCO Laundry Hub</title>
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
  <h1 class="page-title" style="text-align:left;">Orders Dashboard</h1>
  <p class="page-sub" style="text-align:left;">Kumusta, <?= h($admin['full_name']) ?>. Narito ang lahat ng orders ng customers.</p>

  <div class="stat-row">
    <div class="stat-box"><strong><?= $total_orders ?></strong><span>All Orders</span></div>
    <div class="stat-box"><strong><?= $counts['pending'] ?></strong><span>Pending</span></div>
    <div class="stat-box"><strong><?= $counts['processing'] ?></strong><span>Processing</span></div>
    <div class="stat-box"><strong><?= $counts['done'] ?></strong><span>Done</span></div>
    <div class="stat-box"><strong><?= $counts['cancelled'] ?></strong><span>Cancelled</span></div>
  </div>

  <div class="tabs">
    <a href="dashboard.php"><button class="tab-btn <?= $filter==='all'?'active':'' ?>">All</button></a>
    <a href="dashboard.php?status=pending"><button class="tab-btn <?= $filter==='pending'?'active':'' ?>">Pending</button></a>
    <a href="dashboard.php?status=processing"><button class="tab-btn <?= $filter==='processing'?'active':'' ?>">Processing</button></a>
    <a href="dashboard.php?status=done"><button class="tab-btn <?= $filter==='done'?'active':'' ?>">Done</button></a>
    <a href="dashboard.php?status=cancelled"><button class="tab-btn <?= $filter==='cancelled'?'active':'' ?>">Cancelled</button></a>
  </div>

  <?php if (!$orders): ?>
    <div class="card"><p class="empty-note">Walang orders sa kategoryang ito.</p></div>
  <?php else: ?>
  <div class="card" style="padding:0;overflow-x:auto;">
    <table class="data-table">
      <tr>
        <th>Order #</th><th>Customer</th><th>Service</th><th>Weight</th><th>Pick-up</th><th>Total</th><th>Status</th><th>Update</th>
      </tr>
      <?php foreach ($orders as $o): ?>
      <tr>
        <td><strong><?= h($o['order_code']) ?></strong></td>
        <td><?= h($o['full_name']) ?><br><span style="color:var(--text-muted);font-size:11px;"><?= h($o['phone']) ?></span></td>
        <td><?= h($o['service_type']) ?></td>
        <td><?= h($o['weight_kg']) ?></td>
        <td><?= date('M j, Y', strtotime($o['pickup_date'])) ?></td>
        <td>₱<?= number_format($o['total_amount'],2) ?></td>
        <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
        <td>
          <form method="POST" action="update_status.php" style="display:flex;gap:6px;">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
            <input type="hidden" name="redirect_status" value="<?= h($filter) ?>">
            <select name="status" style="padding:6px 8px;font-size:11px;border-radius:8px;">
              <?php foreach ($valid as $v): ?>
                <option value="<?= $v ?>" <?= $o['status']===$v?'selected':'' ?>><?= ucfirst($v) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-navy btn-sm">Save</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <?php endif; ?>
</div>
</body>
</html>
