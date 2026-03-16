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


<main class="container" style="padding:60px 0; max-width:900px;">

<h1 style="margin-bottom:20px;">Returns & Refunds</h1>

<p style="margin-bottom:25px; color:#555;">
At Lunare Clothing we want you to be fully satisfied with your purchase. 
If something isn’t quite right, you can return your item following the policy below.
</p>


<h2 style="margin-top:30px;">Return Policy</h2>

<ul style="margin-top:10px; color:#555; line-height:1.6;">
<li>Items can be returned within <strong>30 days</strong> of delivery.</li>
<li>Products must be <strong>unused, unworn, and in original condition</strong>.</li>
<li>All original tags and packaging must be included.</li>
<li>Some items such as underwear or personalised products may not be eligible for return.</li>
</ul>


<h2 style="margin-top:30px;">How To Return An Item</h2>

<ol style="margin-top:10px; color:#555; line-height:1.6;">
<li>Log into your Lunare account.</li>
<li>Go to your order history.</li>
<li>Select the item you wish to return.</li>
<li>Follow the return instructions provided.</li>
</ol>


<h2 style="margin-top:30px;">Refunds</h2>

<p style="margin-top:10px; color:#555; line-height:1.6;">
Once we receive and inspect your returned item, we will process your refund. 
Refunds are typically issued to your original payment method within 
<strong>5–7 business days</strong>.
</p>


<h2 style="margin-top:30px;">Need Help?</h2>

<p style="margin-top:10px; color:#555;">
If you have any questions regarding returns, please contact our support team.
</p>

<a href="contact.php" class="btn" style="margin-top:15px;">Contact Support</a>

</main>


<footer class="site-footer">
  <div class="container footer-grid">

    <div>
      <h5>Support</h5>
      <a href="#">Help</a>
      <a href="#">Delivery</a>
      <a href="returns.php">Returns</a>
      <a href="contact.php">Contact Us</a>
    </div>

    <div>
      <h5>About</h5>
      <a href="aboutus.php">About Us</a>
      <a href="#">Company</a>
      <a href="#">Sustainability</a>
      <a href="#">Careers</a>
    </div>

    <div>
      <h5>Legal</h5>
      <a href="#">Terms</a>
      <a href="#">Privacy</a>
      <a href="#">Cookies</a>
    </div>

  </div>

  <div class="container footer-bottom">
    <span>© <span id="year"></span> Lunare Clothing</span>
  </div>
</footer>

<script src="app.js"></script>

</body>
</html>