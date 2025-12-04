<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Basket</title>

  <link rel="stylesheet" href="styles.css" />
</head>

<body>

<!-- Utility Strip -->
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

<!-- Header -->
<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="brand">
            <img src="assets/lunare_logo.png" alt="Lunare Clothing logo" class="brand-img">
            <span class="wordmark">LUNARE CLOTHING</span>
        </a>

        <!-- Navigation -->
        <nav class="primary-nav" aria-label="Primary">
            <button class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </button>

            <ul class="menu">
                <li><a href="#" class="nav-link">New</a></li>

                <li class="has-mega">
                    <button class="nav-link" data-menu="men">Men</button>
                    <div class="mega" id="mega-men">
                        <div class="mega-col">
                            <h4>Highlights</h4>
                            <a href="#">New in Men</a>
                            <a href="#">Bestseller</a>
                        </div>
                        <div class="mega-col">
                            <h4>Shoes</h4>
                            <a href="#">All Shoes</a>
                        </div>
                        <div class="mega-col">
                            <h4>Clothing</h4>
                            <a href="menstrousers.php">Trousers</a>
                            <a href="mensshorts.php">Shorts</a>
                        </div>
                    </div>
                </li>

                <li class="has-mega">
                    <button class="nav-link" data-menu="women">Women</button>
                    <div class="mega" id="mega-women">
                        <div class="mega-col">
                            <h4>Highlights</h4>
                            <a href="#">New in Women</a>
                            <a href="#">Bestseller</a>
                        </div>
                        <div class="mega-col">
                            <h4>Shoes</h4>
                            <a href="#">All Shoes</a>
                        </div>
                        <div class="mega-col">
                            <h4>Clothing</h4>
                            <a href="womenscoats.php">Coats</a>
                            <a href="womensshirts.php">Shirts</a>
                        </div>
                    </div>
                </li>

                <li class="has-mega">
                    <button class="nav-link" data-menu="kids">Kids</button>
                    <div class="mega" id="mega-kids">
                        <div class="mega-col">
                            <h4>Highlights</h4>
                            <a href="#">New for Kids</a>
                            <a href="#">Bestseller</a>
                        </div>
                        <div class="mega-col">
                            <h4>Kids</h4>
                            <a href="kidstshirts.php">T-Shirts</a>
                            <a href="#">Clothing</a>
                        </div>
                    </div>
                </li>

                <li><a href="#" class="nav-link sale">Sale</a></li>
            </ul>
        </nav>

        <div class="actions">
            <button id="searchToggle" class="icon-btn" aria-expanded="false">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="16.65" y1="16.65" x2="21" y2="21"/></svg>
            </button>

            <button class="icon-btn" title="Bag" id="cartButton">
                <svg viewBox="0 0 24 24"><path d="M6 7h12l-1 13H7z"/></svg>
                <span id="cartCount"></span>
            </button>

            <!-- Mini Cart Preview -->
            <div id="cartPreview" class="cart-preview">
                <div id="cartPreviewItems"></div>
                <div class="cart-preview-footer">
                    <span>Total:</span>
                    <span id="cartPreviewTotal"></span>
                    <a href="cart.php" class="btn small">View Cart</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Search bar -->
    <div id="searchBar" class="searchbar" hidden>
        <div class="container">
            <form id="searchForm" role="search">
                <input type="search" id="q" placeholder="Search" />
                <button type="submit" class="btn">Search</button>
            </form>
            <p class="search-hint">Try “Joggers” or “Tracksuits”.</p>
        </div>
    </div>
</header>

<!-- Page Title -->
<div class="page-header">
    <div class="container">
        <h1>Your Basket</h1>
    </div>
</div>

<!-- Cart Layout -->
<main class="cart-wrap">
    <div class="container cart-grid">

        <!-- Items Section -->
        <section class="card">
            <h2>Items</h2>

            <div id="cartEmpty" class="cart-empty" hidden>Your basket is empty.</div>

            <div id="cartTableWrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Options</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-right">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody"></tbody>
                </table>
            </div>
        </section>

        <!-- Summary -->
        <aside class="card cart-summary">
            <h2>Summary</h2>
            <div class="row"><span>Subtotal</span><span id="subtotal" class="price">£0.00</span></div>
            <div class="row"><span>Delivery</span><span class="muted">Free</span></div>
            <div class="row total"><span>Total</span><span id="grandTotal" class="price">£0.00</span></div>

            <a href="payment.php" class="btn primary" style="margin-top:14px;">Checkout</a>
        </aside>

    </div>
</main>

<!-- Footer -->
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
// This script EXACTLY matches the logic from basket.html (your friend's file).
// I will give you the cleaned version inside app.js next.
</script>

</body>
</html>
