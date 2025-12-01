<?php
require_once 'db_connect.php';

$first = trim($_POST['first_name']);
$last = trim($_POST['last_name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm = trim($_POST['confirm_password']);

// Validation
if (!$first || !$last || !$email || !$password || !$confirm) {
    echo "<script>alert('All fields are required.'); window.location.href='register.php';</script>";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.location.href='register.php';</script>";
    exit();
}

if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match.'); window.location.href='register.php';</script>";
    exit();
}

// Password Policy: 8+ chars, 1 uppercase, 1 number
if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    echo "<script>alert('Password must be at least 8 characters, include 1 uppercase letter and 1 number.'); window.location.href='register.php';</script>";
    exit();
}

// Hash Password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert into Database
$sql = "INSERT INTO users (first_name, last_name, email, password_hash, role)
        VALUES (?, ?, ?, ?, 'customer')";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("ssss", $first, $last, $email, $hashed);

if (!$stmt->execute()) {
    echo "<script>alert('Email already registered.'); window.location.href='register.php';</script>";
    exit();
}

echo "<script>alert('Account created successfully!'); window.location.href='signin.php';</script>";
?>
