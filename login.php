<?php
require_once __DIR__ . '/includes/auth.php';
if (current_user()) {
    header("Location: " . (current_user()['role'] === 'admin' ? 'admin/dashboard.php' : 'customer/home.php'));
    exit;
}
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
$posted_role = $_SESSION['login_role'] ?? 'customer';
unset($_SESSION['login_role']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="auth-wrap">
  <div style="text-align:center;margin-top:30px;">
    <div style="width:64px;height:64px;border-radius:50%;background:#1f2a8c;color:#fff;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;margin:0 auto 14px;line-height:1.1;">FEDCO<br>LAUNDRY<br>HUB</div>
  </div>
  <h1 class="page-title">Welcome to FEDCO</h1>
  <p class="page-sub">Mag-login bilang Customer o Admin</p>

  <div class="card">
    <form method="POST" action="login_process.php">
      <div class="role-tabs">
        <input type="radio" id="role-customer" name="role" value="customer" <?= $posted_role==='customer'?'checked':'' ?>>
        <label for="role-customer">Customer</label>
        <input type="radio" id="role-admin" name="role" value="admin" <?= $posted_role==='admin'?'checked':'' ?>>
        <label for="role-admin">Admin</label>
      </div>

      <label class="field-label">Email address</label>
      <input type="email" name="email" placeholder="Enter email" required>

      <label class="field-label">Password</label>
      <input type="password" name="password" placeholder="Enter password" required>

      <div class="error-text"><?= h($error) ?></div>

      <button type="submit" class="btn btn-navy btn-block">LOG IN</button>
    </form>
    <a href="register.php"><button type="button" class="btn btn-light btn-block">Don't have an account? SIGN UP</button></a>
    <p class="muted-note">Admin login lang ang pumupunta sa Admin Dashboard.</p>
  </div>
</div>
</body>
</html>
