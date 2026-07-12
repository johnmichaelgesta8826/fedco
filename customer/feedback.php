<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('customer', '../login.php');
$error = $_SESSION['fb_error'] ?? '';
unset($_SESSION['fb_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="brand"><div class="logo-circle">FEDCO<br>LAUNDRY<br>HUB</div> FEDCO Laundry Hub</div>
  <nav><a href="support.php">&larr; Back to Support</a><a href="../logout.php" class="logout-btn" style="margin-left:16px;">Log Out</a></nav>
</div>
<div class="wrap" style="max-width:480px;">
  <h1 class="page-title">Feedback &amp; Suggestions</h1>
  <p class="page-sub">Rate your experience and share your thoughts with us</p>
  <div class="card">
    <form method="POST" action="feedback_process.php" id="fb-form">
      <label class="field-label" style="text-align:center;display:block;">How was your experience?</label>
      <div class="star-row" style="justify-content:center;margin:8px 0 18px;">
        <?php for ($i=1;$i<=5;$i++): ?>
          <button type="button" class="star-btn" data-val="<?= $i ?>" onclick="setRating(<?= $i ?>)">&#9733;</button>
        <?php endfor; ?>
      </div>
      <input type="hidden" name="rating" id="rating-input" value="0">
      <label class="field-label">Comments or suggestions</label>
      <textarea name="comments" rows="4" placeholder="What can we do better?"></textarea>
      <div class="error-text"><?= h($error) ?></div>
      <button type="submit" class="btn btn-navy btn-block">Submit Feedback</button>
    </form>
  </div>
</div>
<script>
function setRating(n){
  document.getElementById('rating-input').value = n;
  document.querySelectorAll('.star-btn').forEach(btn=>{
    btn.classList.toggle('selected', parseInt(btn.dataset.val,10) <= n);
  });
}
</script>
</body>
</html>
