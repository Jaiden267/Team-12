<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "db_connect.php";

if (!isset($_POST['cart']) || empty($_POST['cart'])) {
    die("No cart data received.");
}

$cart = json_decode($_POST['cart'], true);

$billing_name  = $_POST['billing_name'];
$billing_email = $_POST['billing_email'];
$billing_phone = $_POST['billing_phone'];
$address1      = $_POST['address1'];
$address2      = $_POST['address2'];
$city          = $_POST['city'];
$postcode      = $_POST['postcode'];
$country       = $_POST['country'];

$user_id = $_SESSION['user_id'] ?? null;

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
$subject = "New Order #$order_id";

$message = "New order received:\n\n";
$message .= "Name: $billing_name\n";
$message .= "Email: $billing_email\n";
$message .= "Phone: $billing_phone\n";
$message .= "Address: $address1, $address2, $city, $postcode, $country\n\n";
$message .= "Order Items:\n";

foreach ($cart as $item) {
    $message .= "{$item['name']} x {$item['qty']} (£{$item['price']})\n";
}

$headers = "From: no-reply@lunareclothing.com";

mail($to, $subject, $message, $headers);

echo "<script>
    alert('Order Successful! Thank you for shopping with Lunare Clothing.');
    localStorage.removeItem('lc_cart_v1');
    window.location.href = 'index.php';
</script>";
?>

