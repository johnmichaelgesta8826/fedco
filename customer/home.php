<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

// pinaka-huling order (para sa status banner)
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user['id']]);
$latest = $stmt->fetch();

$booking_error = $_SESSION['booking_error'] ?? '';
unset($_SESSION['booking_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Home - FEDCO Laundry Hub</title>
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

<div class="wrap">
  <div class="quick-actions">
    <button class="qa-btn" onclick="document.getElementById('booking-form').scrollIntoView({behavior:'smooth'})"><span class="emoji">📦</span>Book</button>
    <?php if ($latest): ?>
    <a href="tracking.php?id=<?= $latest['id'] ?>" style="text-decoration:none;flex:1;"><button class="qa-btn" style="width:100%;"><span class="emoji">🚚</span>Track</button></a>
    <a href="order.php?id=<?= $latest['id'] ?>" style="text-decoration:none;flex:1;"><button class="qa-btn" style="width:100%;"><span class="emoji">🧾</span>Receipt</button></a>
    <?php endif; ?>
    <a href="support.php" style="text-decoration:none;flex:1;"><button class="qa-btn" style="width:100%;"><span class="emoji">💬</span>Support</button></a>
  </div>

  <?php if ($latest): ?>
  <div class="card" style="background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:space-between;">
    <div>
      <div style="font-weight:700;font-size:13px;">Order #<?= h($latest['order_code']) ?> &middot; <span class="badge badge-<?= $latest['status'] ?>" style="margin-left:6px;"><?= ucfirst($latest['status']) ?></span></div>
      <div style="font-size:10.5px;opacity:.85;margin-top:2px;">Pick-up: <?= date('M j, Y', strtotime($latest['pickup_date'])) ?></div>
    </div>
    <a href="tracking.php?id=<?= $latest['id'] ?>"><button class="btn btn-light btn-sm">Track</button></a>
  </div>
  <?php endif; ?>

  <div class="card" id="booking-form">
    <div class="section-title" style="margin-top:0;">New Laundry Booking</div>
    <form method="POST" action="book_process.php">
      <label class="field-label">Your name</label>
      <input type="text" value="<?= h($user['full_name']) ?>" disabled>

      <div class="row2">
        <div>
          <label class="field-label">Weight (kg)</label>
          <select name="weight_kg" required>
            <option value="">select kilogram</option>
            <option>1-3 kg</option>
            <option>4-6 kg</option>
            <option>7-10 kg</option>
          </select>
        </div>
        <div>
          <label class="field-label">Payment</label>
          <select name="payment_method" required>
            <option value="">select payment</option>
            <option>Cash on Delivery</option>
            <option>GCash</option>
            <option>Maya</option>
          </select>
        </div>
      </div>

      <label class="field-label">Service type</label>
      <select name="service_type" required>
        <option>WASH w/ FABRIC</option>
        <option>WASH & DRY</option>
        <option>FULL SERVICE</option>
      </select>

      <label class="field-label">Pick-up date</label>
      <input type="date" name="pickup_date" required>

      <label class="field-label">Delivery address</label>
      <input type="text" name="delivery_address" placeholder="Street, Barangay, City" value="<?= h($user['email'] ? '' : '') ?>" required>

      <label class="field-label">Special instruction</label>
      <input type="text" name="special_instruction" placeholder="Suggestion (optional)">

      <div class="error-text"><?= h($booking_error) ?></div>
      <button type="submit" class="btn btn-navy btn-block">Submit Order</button>
    </form>
  </div>
</div>
</body>
</html>
