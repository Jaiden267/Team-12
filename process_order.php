<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$billing_name  = $_POST['billing_name'];
$billing_email = $_POST['billing_email'];
$billing_phone = $_POST['billing_phone'];
$address1      = $_POST['address1'];
$address2      = $_POST['address2'];
$city          = $_POST['city'];
$postcode      = $_POST['postcode'];
$country       = $_POST['country'];

$cart = json_decode($_POST['cart'], true);

$user_id = $_SESSION['user_id'] ?? NULL;

$stmt = $conn->prepare("
    INSERT INTO orders (user_id, full_name, email, phone, address1, address2, city, postcode, country, order_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
");

$stmt->bind_param(
    "issssssss",
    $user_id,
    $billing_name,
    $billing_email,
    $billing_phone,
    $address1,
    $address2,
    $city,
    $postcode,
    $country
);

$stmt->execute();
$order_id = $stmt->insert_id;

$itemStmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_sku, product_name, price, quantity)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($cart as $item) {
    $itemStmt->bind_param(
        "issdi",
        $order_id,
        $item['sku'],
        $item['name'],
        $item['price'],
        $item['qty']
    );
    $itemStmt->execute();
}

$to = "lunare.clothing@mail.com";
$subject = "New Order #$order_id Received";

$message = "A new order has been placed.\n\n";
$message .= "Order ID: $order_id\n";
$message .= "Customer: $billing_name\n";
$message .= "Email: $billing_email\n";
$message .= "Phone: $billing_phone\n\n";
$message .= "Address:\n$address1\n";
if ($address2) $message .= "$address2\n";
$message .= "$city\n$postcode\n$country\n\n";

$message .= "Items:\n";
foreach ($cart as $item) {
    $message .= "- {$item['name']} x {$item['qty']} @ £{$item['price']}\n";
}

$headers = "From: orders@lunareclothing.com";

mail($to, $subject, $message, $headers);


header("Location: success.php");
exit;
?>
