<?php
session_start();
require_once 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <title>Lunare Clothing — Admin Portal</title>

  <link rel="stylesheet" href="styles.css" />
</head>
<body>

<div class="utility-strip">
  <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
        
    <span>FREE DELIVERY & RETURNS</span>

    <div style="display:flex; gap:15px; align-items:center;">
      <a href="contact.php" class="link">Contact Us</a>
      <a href="register.php" class="link">Register</a>
      <a href="signin.php" class="link">Sign In</a>
    </div>

  </div>
</div>

<header class="site-header">
  <div class="container header-inner">
    <a href="index.php" class="brand" aria-label="Lunare Clothing Home"> 
      <img src="assets/lunare_logo.png" alt="Lunare Clothing logo" class="brand-img">
      <span class="wordmark">LUNARE CLOTHING</span>
    </a>

    <nav class="primary-nav" aria-label="Primary">
      
      <button class="hamburger" id="hamburger" aria-expanded="false">
        <span></span><span></span><span></span>
        <span class="sr-only">Toggle menu</span>
      </button>

      <ul class="menu">
        <li><a href="#" class="nav-link">New</a></li>
        <li><a href="#" class="nav-link">Men</a></li>
        <li><a href="#" class="nav-link">Women</a></li>
        <li><a href="#" class="nav-link">Kids</a></li>
        <li><a href="#" class="nav-link sale">Sale</a></li>
      </ul>
    </nav>

    <div class="actions">
      <button class="icon-btn" title="Search"></button>
      <button class="icon-btn" title="Favourites"></button>
      <button class="icon-btn" title="Bag"></button>
    </div>
  </div>
</header>

<section class="contact">
  <div class="container signin-container">

    <div class="contact-form">
      <h3>Admin Portal</h3>

      <form method="POST" action="admin_login_process.php">

        <div class="form-group">
            <input type="text" name="employee_id" placeholder="Employee ID" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="submitbtn">Sign In</button>

        <p class="signup-text">
          Customer account?
          <a href="signin.php">Customer Sign In</a>
        </p>

      </form>

    </div>

  </div>
</section>

<footer class="site-footer">
  <div class="container footer-grid">

    <div>
      <h5>Support</h5>
      <a href="help.php">Help</a>
      <a href="delivery.php">Delivery</a>
      <a href="returns.php">Returns</a>
      <a href="contact.php">Contact Us</a>
    </div>

    <div>
      <h5>About</h5>
      <a href="aboutus.php">About Us</a>
      <a href="company.php">Company</a>
      <a href="sustainability.php">Sustainability</a>
      <a href="careers.php">Careers</a>
    </div>

    <div>
      <h5>Legal</h5>
      <a href="terms.php">Terms</a>
      <a href="privacy.php">Privacy</a>
      <a href="cookies.php">Cookies</a>
    </div>

  </div>

  <div class="container footer-bottom">
    <span>© <span id="year"></span> Lunare Clothing</span>
  </div>
</footer>

<script src="app.js"></script>

</body>
</html>