<?php
require_once 'db_connect.php';
session_start();

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (!$email || !$password) {
    echo "<script>alert('Please fill in all fields.'); window.location.href='signin.php';</script>";
    exit();
}

$sql = "SELECT user_id, first_name, password_hash, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('No account found with that email.'); window.location.href='signin.php';</script>";
    exit();
}

$stmt->bind_result($user_id, $first_name, $hashed, $role);
$stmt->fetch();

if (!password_verify($password, $hashed)) {
    echo "<script>alert('Incorrect password.'); window.location.href='signin.php';</script>";
    exit();
}

// Login Successful
$_SESSION['user_id'] = $user_id;
$_SESSION['first_name'] = $first_name;
$_SESSION['role'] = $role;

echo "<script>alert('Login successful!'); window.location.href='index.php';</script>";
?>
