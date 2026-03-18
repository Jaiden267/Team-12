<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Terms & Conditions</title>
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

<!-- ✅ TERMS CONTENT -->
<main>
<section class="terms-page">
  <div class="container">

    <div class="terms-header">
      <h1>Terms & Conditions</h1>
      <p class="subtitle">Please read these terms carefully before using our website</p>
    </div>

    <div class="terms-section">
      <h2>1. Introduction</h2>
      <p>
        These Terms & Conditions govern your use of the Lunare Clothing website.
        By accessing and using our site, you agree to comply with these terms.
      </p>
    </div>

    <div class="terms-section">
      <h2>2. Products & Orders</h2>
      <p>
        All products are subject to availability. We reserve the right to modify or
        discontinue products without notice. Once an order is placed, you will receive
        confirmation via email.
      </p>
    </div>

    <div class="terms-section">
      <h2>3. Pricing</h2>
      <p>
        All prices are listed in GBP (£) and include applicable taxes. We reserve the
        right to change prices at any time without prior notice.
      </p>
    </div>

    <div class="terms-section">
      <h2>4. Delivery</h2>
      <p>
        Delivery times are estimates and may vary depending on location and external
        factors. For more information, please visit our Delivery page.
      </p>
    </div>

    <div class="terms-section">
      <h2>5. Returns & Refunds</h2>
      <p>
        Items can be returned within 14 days of receipt, provided they meet our return
        conditions. Refunds are processed after inspection of returned goods.
      </p>
    </div>

    <div class="terms-section">
      <h2>6. User Accounts</h2>
      <p>
        You are responsible for maintaining the confidentiality of your account details.
        Lunare is not liable for any loss arising from unauthorised use of your account.
      </p>
    </div>

    <div class="terms-section">
      <h2>7. Liability</h2>
      <p>
        Lunare Clothing is not liable for indirect, incidental, or consequential damages
        arising from the use of our website or products.
      </p>
    </div>

    <div class="terms-section">
      <h2>8. Changes to Terms</h2>
      <p>
        We reserve the right to update these Terms & Conditions at any time. Continued
        use of the website constitutes acceptance of the updated terms.
      </p>
    </div>

    <div class="terms-section">
      <h2>9. Contact</h2>
      <p>
        If you have any questions about these Terms, please contact us via the contact page.
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