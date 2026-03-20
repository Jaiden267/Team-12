<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Email not found in database'); window.location.href='forgotpassword.php';</script>";
        exit();
    }

    $user = $result->fetch_assoc();
    $old_hashed_password = $user['password'];

    
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location.href='forgotpassword.php';</script>";
        exit();
    }

   
    $password_pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";

    if (!preg_match($password_pattern, $new_password)) {
        echo "<script>alert('Password must be at least 8 characters long and include uppercase, lowercase, number, and special character'); window.location.href='forgotpassword.php';</script>";
        exit();
    }

    
    if (password_verify($new_password, $old_hashed_password)) {
        echo "<script>alert('New password cannot be the same as your old password'); window.location.href='forgotpassword.php';</script>";
        exit();
    }

   
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    
    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $new_hashed_password, $email);

    if ($update->execute()) {
        echo "<script>alert('Password successfully updated'); window.location.href='signin.php';</script>";
    } else {
        echo "<script>alert('Error updating password'); window.location.href='forgotpassword.php';</script>";
    }
}
?>