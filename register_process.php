<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($first) || empty($last) || empty($email) || empty($password) || empty($confirm)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('Please enter a valid email address.');
            window.history.back();
          </script>";
    exit;
}

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain at least one number.'); window.history.back();</script>";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first, $last, $email, $password_hash);

    if ($stmt->execute()) {

        echo "<script>
                alert('Registration successful! Welcome to Lunare Clothing.');
                window.location.href = 'index.php';
              </script>";
        exit;

    } else {

        echo "<script>
                alert('Error: Email already registered.');
                window.history.back();
              </script>";
        exit;
    }
}
?>

