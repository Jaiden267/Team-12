<?php
session_start();
require_once 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Forgot Password</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

<section class="contact">
  <div class="container signin-container">
    <h2>Forgot Password</h2>

    <div class="contact-form">
      <h3>Reset Your Password</h3>

      <form method="POST" action="forgotpassword_process.php">
        <div class="form-group">
          <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <button type="submit" class="submitbtn">Send Reset Link</button>
      </form>
    </div>
  </div>
</section>

<script src="app.js"></script>
  <script src="//code.tidio.co/t2metx8c6fo4wq7w8lvxrczj0m32nwmk.js" async></script>
</body>
</html>

