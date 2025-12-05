<?php
require_once 'db_connect.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form data
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validate required fields
    if (empty($first) || empty($last) || empty($email) || empty($password) || empty($confirm)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Validate email format (same idea as contact form)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('Please enter a valid email address.');
            window.history.back();
          </script>";
    exit;
}


    // Check password match
    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // Password policy (example: 8 chars, 1 number, 1 letter)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain at least one number.'); window.history.back();</script>";
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first, $last, $email, $password_hash);

    if ($stmt->execute()) {

        // SUCCESS — redirect home
        echo "<script>
                alert('Registration successful! Welcome to Lunare Clothing.');
                window.location.href = 'index.php';
              </script>";
        exit;

    } else {

        // ERROR — likely email already exists
        echo "<script>
                alert('Error: Email already registered.');
                window.history.back();
              </script>";
        exit;
    }
}
?>

