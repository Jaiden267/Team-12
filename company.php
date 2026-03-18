<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Company</title>
  <link rel="stylesheet" href="styles.css" />
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
      <img src="assets/lunare_logo.png" class="brand-img">
      <span class="wordmark">LUNARE CLOTHING</span>
    </a>

    <nav class="primary-nav">
      <ul class="menu">
        <li><a href="#" class="nav-link">New</a></li>
        <li><a href="menstrousers.php" class="nav-link">Men</a></li>
        <li><a href="womenscoats.php" class="nav-link">Women</a></li>
        <li><a href="kidstshirts.php" class="nav-link">Kids</a></li>
        <li><a href="#" class="nav-link sale">Sale</a></li>
      </ul>
    </nav>

    <div class="actions">
      <button id="searchToggle" class="icon-btn">🔍</button>

      <a href="favourites.php" class="icon-btn">❤️</a>
      <span id="favCount" class="muted"></span>

      <button id="cartButton" class="icon-btn">🛒</button>
      <span id="cartCount" class="muted"></span>
    </div>
  </div>
</header>

<!-- ✅ COMPANY CONTENT -->
<main>
<section class="company-page">
  <div class="container">

    <div class="company-header">
      <h1>Our Company</h1>
      <p class="subtitle">Built on passion, driven by innovation</p>
    </div>

    <div class="company-section">
      <h2>Who We Are</h2>
      <p>
        Lunare Clothing is a modern fashion brand focused on redefining everyday wear.
        Founded with a vision to combine comfort, style, and performance, we have grown
        into a fast-moving clothing company with a strong community behind us.
      </p>
      <p>
        Our team is made up of designers, developers, and creatives who are committed
        to pushing boundaries in the clothing industry.
      </p>
    </div>

    <div class="company-section">
      <h2>Our Mission</h2>
      <p>
        Our mission is simple — to create clothing that looks good, feels good, and performs.
        We aim to deliver high-quality products while maintaining affordability and accessibility.
      </p>
    </div>

    <div class="company-values">
      <div class="value-card">
        <h3>Innovation</h3>
        <p>We constantly evolve our designs to stay ahead of trends.</p>
      </div>

      <div class="value-card">
        <h3>Quality</h3>
        <p>Every product is crafted with premium materials and attention to detail.</p>
      </div>

      <div class="value-card">
        <h3>Community</h3>
        <p>We build connections with our customers and grow together.</p>
      </div>
    </div>

    <div class="company-section">
      <h2>Our Growth</h2>
      <p>
        Since launching, Lunare has expanded rapidly, reaching customers across the UK.
        We continue to scale our operations, improve our services, and expand our product lines.
      </p>
    </div>

    <div class="company-section">
      <h2>Looking Ahead</h2>
      <p>
        The future of Lunare Clothing is focused on global expansion, sustainability,
        and continued innovation in fashion. We aim to become a recognised name worldwide.
      </p>
    </div>

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