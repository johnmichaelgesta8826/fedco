<?php
require_once __DIR__ . '/includes/auth.php';
$error = $_SESSION['signup_error'] ?? '';
unset($_SESSION['signup_error']);
$old = $_SESSION['signup_old'] ?? [];
unset($_SESSION['signup_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up - FEDCO Laundry Hub</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="auth-wrap">
  <h1 class="page-title" style="margin-top:30px;">Create Your Account</h1>
  <p class="page-sub">Sign up to book and track your laundry orders easily</p>

  <div class="card">
    <form method="POST" action="register_process.php">
      <label class="field-label">Full name</label>
      <input type="text" name="full_name" placeholder="Enter your full name" value="<?= h($old['full_name'] ?? '') ?>" required>

      <label class="field-label">Phone number</label>
      <input type="text" name="phone" placeholder="09XX XXX XXXX" value="<?= h($old['phone'] ?? '') ?>" required>

      <label class="field-label">Email address</label>
      <input type="email" name="email" placeholder="Enter your email" value="<?= h($old['email'] ?? '') ?>" required>

      <label class="field-label">Home address</label>
      <input type="text" name="address" placeholder="Street, Barangay, City" value="<?= h($old['address'] ?? '') ?>" required>

      <label class="field-label">Password</label>
      <input type="password" name="password" placeholder="Enter password" required>

      <label class="field-label">Confirm password</label>
      <input type="password" name="confirm_password" placeholder="Re-enter password" required>

      <div style="display:flex;align-items:flex-start;gap:8px;font-size:11.5px;color:var(--text-muted);margin:16px 4px 4px;">
        <input type="checkbox" name="agree" style="width:16px;height:16px;flex-shrink:0;" required>
        <label>I agree to the Terms of Service and Privacy Policy</label>
      </div>

      <div class="error-text"><?= h($error) ?></div>
      <button type="submit" class="btn btn-navy btn-block">SIGN UP</button>
    </form>
    <a href="login.php"><button type="button" class="btn btn-light btn-block">Already have an account? LOG IN</button></a>
  </div>
</div>
</body>
</html>
