<?php
session_start();
require_once 'db_connect.php';


if (!isset($_SESSION['reset_attempts'])) {
    $_SESSION['reset_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

if (time() - $_SESSION['last_attempt'] > 300) {
    $_SESSION['reset_attempts'] = 0;
}

$_SESSION['reset_attempts']++;
$_SESSION['last_attempt'] = time();

if ($_SESSION['reset_attempts'] > 5) {
    die("Too many attempts. Try again later.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Email not found'); window.location.href='forgotpassword.php';</script>";
        exit();
    }

   
    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $update = $conn->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE email=?");
    $update->bind_param("sss", $token, $expiry, $email);
    $update->execute();


    $resetLink = "http://cs2team12.cs2410-web01pvm.aston.ac.uk/resetpassword.php?token=" . $token;

    echo "<script>alert('Reset link (demo): $resetLink'); window.location.href='signin.php';</script>";
}
?>