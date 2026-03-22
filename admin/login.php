<?php
session_start();
include '../db_connect.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        echo "<script>alert('Please fill in all fields'); window.location.href='login.php';</script>";
        exit();
    }

    $sql = "SELECT user_id, role, first_name, password_hash FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password_hash'])) {
        if ($row['role'] === 'admin') {
            $_SESSION['admin_id'] = $row['user_id'];
            $_SESSION['first_name'] = $row['first_name'] ?? '';
            header('Location: dashboard.php');
            exit();
        }

        echo "<script>alert('You are not authorized to access this page'); window.location.href='login.php';</script>";
        exit();
    }

    echo "<script>alert('Invalid email or password'); window.location.href='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div style="display:flex; justify-content:center; align-items:center; height:100vh;">
        <div style="display:flex; flex-direction:column; gap:20px; width:500px; padding:20px; border:1px solid #ddd; border-radius:10px;">
            <h1 style="text-align:center;">Admin Login</h1>
            <form action="login.php" method="post">
                <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" placeholder="Email" class="form-input">
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" placeholder="Password" class="form-input">
                </div>
                <button type="submit" name="login" class="btn " style="margin-top:20px;">Login</button>
            </form>
        </div>
    </div>
</body>

</html>