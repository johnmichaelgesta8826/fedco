<?php
// =========================================================
// PAGAMITAN: Buksan ito ISANG BESES lang sa browser
// (hal. http://localhost/fedco_laundry_hub/seed_admin.php)
// pagkatapos mong i-import ang database.sql, para gumawa ng
// Admin account at 1 sample Customer account.
//
// PAALALA: I-DELETE o i-rename ang file na ito pagkatapos gamitin
// para hindi ito ma-abuse ng iba.
// =========================================================
require_once __DIR__ . '/config/db.php';

$accounts = [
    [
        'full_name' => 'FEDCO Admin',
        'email'     => 'admin@fedco.com',
        'phone'     => '09171234567',
        'address'   => 'FEDCO Laundry Hub Main Office',
        'password'  => 'admin123',
        'role'      => 'admin',
    ],
    [
        'full_name' => 'Juan Dela Cruz',
        'email'     => 'juan@example.com',
        'phone'     => '09981234567',
        'address'   => '250 Silangan, Halayhay, Tanza, Cavite',
        'password'  => 'customer123',
        'role'      => 'customer',
    ],
];

$created = [];
$skipped = [];

foreach ($accounts as $acc) {
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$acc['email']]);
    if ($check->fetch()) {
        $skipped[] = $acc['email'];
        continue;
    }

    $hash = password_hash($acc['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, address, password, role) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$acc['full_name'], $acc['email'], $acc['phone'], $acc['address'], $hash, $acc['role']]);
    $created[] = $acc['email'] . " (password: {$acc['password']})";
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Seed Accounts - FEDCO Laundry Hub</title>
<style>body{font-family:Arial,sans-serif;max-width:600px;margin:60px auto;line-height:1.7;color:#222;}
code{background:#eee;padding:2px 6px;border-radius:4px;}
.box{background:#fff;border:1px solid #ddd;border-radius:10px;padding:20px 24px;box-shadow:0 4px 12px rgba(0,0,0,.06);}
h1{color:#1f2a8c;font-size:20px;}
</style></head>
<body>
<div class="box">
<h1>FEDCO Laundry Hub &mdash; Account Seeder</h1>
<?php if ($created): ?>
  <p><strong>Nagawa ang mga sumusunod na account:</strong></p>
  <ul><?php foreach ($created as $c) echo "<li>" . htmlspecialchars($c) . "</li>"; ?></ul>
<?php endif; ?>
<?php if ($skipped): ?>
  <p><strong>Meron na (hindi na ginalaw):</strong></p>
  <ul><?php foreach ($skipped as $s) echo "<li>" . htmlspecialchars($s) . "</li>"; ?></ul>
<?php endif; ?>
<p>Puwede ka nang pumunta sa <a href="login.php">login page</a>.</p>
<p style="color:#c00;"><strong>Paalala:</strong> I-delete o i-rename ang <code>seed_admin.php</code> ngayon para hindi na ito ma-access ng iba.</p>
</div>
</body>
</html>
