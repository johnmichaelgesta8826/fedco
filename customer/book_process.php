<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_role('customer', '../login.php');
$user = current_user();

$service_type = trim($_POST['service_type'] ?? '');
$weight_kg = trim($_POST['weight_kg'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? '');
$pickup_date = trim($_POST['pickup_date'] ?? '');
$delivery_address = trim($_POST['delivery_address'] ?? '');
$special_instruction = trim($_POST['special_instruction'] ?? '');

if (!$service_type || !$weight_kg || !$payment_method || !$pickup_date || !$delivery_address) {
    $_SESSION['booking_error'] = 'Please fill out all required fields.';
    header("Location: home.php");
    exit;
}

// simpleng pricing logic
$base_rates = ['1-3 kg' => 16.5, '4-6 kg' => 22.5, '7-10 kg' => 28.5];
$service_fees = ['WASH w/ FABRIC' => 50, 'WASH & DRY' => 75, 'FULL SERVICE' => 120];

$per_kg = $base_rates[$weight_kg] ?? 20;
$avg_kg = 4; // pinasimple lang na multiplier
$base_price = round($per_kg * $avg_kg, 2);
$service_fee = $service_fees[$service_type] ?? 50;
$delivery_fee = 50;
$total = $base_price + $service_fee + $delivery_fee;

$order_code = 'FLH-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);

$stmt = $pdo->prepare("INSERT INTO orders
  (order_code, user_id, service_type, weight_kg, payment_method, pickup_date, delivery_address, special_instruction, base_price, service_fee, delivery_fee, total_amount, status)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?, 'pending')");
$stmt->execute([
    $order_code, $user['id'], $service_type, $weight_kg, $payment_method,
    $pickup_date, $delivery_address, $special_instruction,
    $base_price, $service_fee, $delivery_fee, $total
]);

$order_id = $pdo->lastInsertId();
header("Location: order.php?id=$order_id");
exit;
