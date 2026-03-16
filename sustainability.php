<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Lunare Clothing — Returns</title>

<link rel="stylesheet" href="styles.css">
</head>

<body>

<div class="utility-strip">
  <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
    <span>FREE DELIVERY & RETURNS</span>

    <div style="display:flex; gap:15px; align-items:center;">
      <a href="contact.php" class="link">Contact Us</a>

      <?php if(isset($_SESSION['user_id'])): ?>
        <span class="link">Hello, <?= htmlspecialchars($_SESSION['first_name']); ?></span>
        <a href="logout.php" class="link">Logout</a>
      <?php else: ?>
        <a href="register.php" class="link">Register</a>
        <a href="signin.php" class="link">Sign In</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<header class="site-header">
  <div class="container header-inner">

    <a href="index.php" class="brand">
      <img src="assets/lunare_logo.png" alt="Lunare Clothing logo" class="brand-img">
      <span class="wordmark">LUNARE CLOTHING</span>
    </a>

    <nav class="primary-nav">
      <ul class="menu">
        <li><a href="#" class="nav-link">New</a></li>
        <li><a href="#" class="nav-link">Men</a></li>
        <li><a href="#" class="nav-link">Women</a></li>
        <li><a href="#" class="nav-link">Kids</a></li>
        <li><a href="#" class="nav-link sale">Sale</a></li>
      </ul>
    </nav>

  </div>
</header>


<main>
<section class="aboutusmainbuild">
  <div class="aboutusmaintext">
    <div class="aboutusheader">
      <h1>Sustainability</h1>
     <p class="subtitle">Creating clothing responsibly for the future</p>


     <img src="https://static.vecteezy.com/system/resources/previews/017/306/579/original/esg-sustainability-concept-illustration-vector.jpg" 
alt="Sustainability illustration"
style="width:100%; max-width:900px; border-radius:12px; margin:25px 0;">
    </div>

    <div class="ourjourneycontent">
      <h2>Our Commitment</h2>
      <p>
        At Lunare Clothing, sustainability plays an important role in how we approach fashion and ecommerce. 
        As a brand created through a university project, we recognise the responsibility that clothing companies 
        have in reducing environmental impact and promoting responsible production methods.
      </p>

      <p>
        Our goal is to explore ways clothing brands can operate more sustainably while still delivering 
        high-quality products and modern designs. Through this project we focus on learning how ethical 
        sourcing, responsible production, and efficient distribution can contribute to a better future 
        for the fashion industry.
      </p>
    </div>

    <div class="ourjourneycontent">
      <h2>Looking Forward</h2>
      <p>
        Lunare Clothing aims to demonstrate how modern ecommerce brands can integrate sustainability into 
        their long-term vision. While this project is currently part of a university course, it represents 
        the values we believe future clothing brands should prioritise responsibility, transparency, 
        and innovation.
      </p>

      <p>
        By researching sustainable materials, responsible manufacturing practices, and environmentally 
        conscious logistics, we aim to understand how clothing brands can evolve to meet the expectations 
        of modern consumers while protecting the planet.
      </p>

      

  </div>
</section>
</main>


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