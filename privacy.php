<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Privacy Policy</title>
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
      <button class="icon-btn">🔍</button>
      <a href="favourites.php" class="icon-btn">❤️</a>
      <span id="favCount" class="muted"></span>
      <button class="icon-btn">🛒</button>
      <span id="cartCount" class="muted"></span>
    </div>
  </div>
</header>

<!-- ✅ PRIVACY CONTENT -->
<main>
<section class="privacy-page">
  <div class="container">

    <div class="privacy-header">
      <h1>Privacy Policy</h1>
      <p class="subtitle">Your privacy is important to us</p>
    </div>

    <div class="privacy-section">
      <h2>1. Information We Collect</h2>
      <p>
        We may collect personal information such as your name, email address, shipping address,
        and payment details when you create an account or place an order.
      </p>
    </div>

    <div class="privacy-section">
      <h2>2. How We Use Your Information</h2>
      <p>
        Your information is used to process orders, provide customer support, improve our services,
        and communicate updates related to your account or purchases.
      </p>
    </div>

    <div class="privacy-section">
      <h2>3. Data Protection</h2>
      <p>
        We take appropriate security measures to protect your personal data. Sensitive information
        such as passwords is stored securely and encrypted where applicable.
      </p>
    </div>

    <div class="privacy-section">
      <h2>4. Sharing Your Information</h2>
      <p>
        We do not sell your personal data. We may share information with trusted third parties such
        as payment providers and delivery services to fulfil your orders.
      </p>
    </div>

    <div class="privacy-section">
      <h2>5. Cookies</h2>
      <p>
        Our website uses cookies to improve your browsing experience and analyse site traffic.
        You can manage your cookie preferences through your browser settings.
      </p>
    </div>

    <div class="privacy-section">
      <h2>6. Your Rights</h2>
      <p>
        You have the right to access, update, or delete your personal data. If you wish to do so,
        please contact us via our contact page.
      </p>
    </div>

    <div class="privacy-section">
      <h2>7. Changes to This Policy</h2>
      <p>
        We may update this Privacy Policy from time to time. Any changes will be posted on this page.
      </p>
    </div>

    <div class="privacy-section">
      <h2>8. Contact Us</h2>
      <p>
        If you have any questions regarding this Privacy Policy, please contact us through our contact page.
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