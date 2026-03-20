<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Find user by token
    $stmt = $conn->prepare("SELECT password, token_expiry FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid token");
    }

    $user = $result->fetch_assoc();

    // Check expiry
    if (strtotime($user['token_expiry']) < time()) {
        die("Token expired");
    }

    // Check match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match");
    }

    // Password strength
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";

    if (!preg_match($pattern, $new_password)) {
        die("Weak password");
    }

    // Check not same as old
    if (password_verify($new_password, $user['password'])) {
        die("Cannot reuse old password");
    }

    // Update password
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?");
    $update->bind_param("ss", $hashed, $token);
    $update->execute();

    echo "Password successfully reset. <a href='signin.php'>Login</a>";
}
?>