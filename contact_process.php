<?php
require_once 'db_connect.php';

$name = trim($_POST['name']);
$email = trim($_POST["email"]);
$message = trim($_POST["message"]);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

if (strlen($name) < 2) {
    die("Name must be at least 2 characters long");
}

if (strlen($message) < 5) {
    die("Message must be at least 10 characters long");
}

$sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    echo "Message sent successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>