<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Help</title>
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
      <button id="searchToggle" class="icon-btn">
        🔍
      </button>

      <a href="favourites.php" class="icon-btn">
        ❤️
      </a>
      <span id="favCount" class="muted"></span>

      <button id="cartButton" class="icon-btn">
        🛒
      </button>
      <span id="cartCount" class="muted"></span>
    </div>
  </div>
</header>

<!-- ✅ HELP PAGE CONTENT -->
<main>
<section class="help-page">
  <div class="container">

    <div class="help-header">
      <h1>Help & Support</h1>
      <p class="subtitle">We're here to help you with anything you need</p>
    </div>

    <div class="help-search">
      <input type="text" placeholder="Search for help..." class="help-search-input">
    </div>

    <div class="help-grid">

      <div class="help-card">
        <h3>Orders</h3>
        <p>Track your order or get help with purchases.</p>
        <a href="#" class="link">View Orders Help</a>
      </div>

      <div class="help-card">
        <h3>Delivery</h3>
        <p>Find delivery options and times.</p>
        <a href="delivery.php" class="link">Delivery Info</a>
      </div>

      <div class="help-card">
        <h3>Returns</h3>
        <p>Return items or request refunds.</p>
        <a href="returns.php" class="link">Returns Policy</a>
      </div>

      <div class="help-card">
        <h3>Account</h3>
        <p>Manage your account and details.</p>
        <a href="signin.php" class="link">Account Help</a>
      </div>

    </div>

    <!-- FAQ -->
    <div class="faq-section">
      <h2>Frequently Asked Questions</h2>

      <div class="faq-item">
        <button class="faq-question">How do I track my order?</button>
        <div class="faq-answer">Go to your account and check your orders.</div>
      </div>

      <div class="faq-item">
        <button class="faq-question">How long is delivery?</button>
        <div class="faq-answer">2–5 working days standard delivery.</div>
      </div>

      <div class="faq-item">
        <button class="faq-question">Can I return items?</button>
        <div class="faq-answer">Yes, within 14 days.</div>
      </div>

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

<script>
// FAQ toggle
document.querySelectorAll('.faq-question').forEach(btn => {
  btn.addEventListener('click', () => {
    const ans = btn.nextElementSibling;
    ans.style.display = ans.style.display === 'block' ? 'none' : 'block';
  });
});
</script>

</body>
</html>