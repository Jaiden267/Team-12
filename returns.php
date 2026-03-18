<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Returns</title>
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
    <a href="index.php" class="brand" aria-label="Lunare Clothing Home">
      <img src="assets/lunare_logo.png" alt="Lunare Clothing logo" class="brand-img">
      <span class="wordmark">LUNARE CLOTHING</span>
    </a>

    <nav class="primary-nav" aria-label="Primary">
      <button class="hamburger" id="hamburger" aria-expanded="false" aria-controls="mobileMenu">
        <span></span><span></span><span></span>
        <span class="sr-only">Toggle menu</span>
      </button>

      <ul class="menu">
        <li><a href="#" class="nav-link">New</a></li>

        <li class="has-mega">
          <button class="nav-link" data-menu="men" aria-expanded="false">Men</button>
          <div class="mega" id="mega-men" role="dialog" aria-label="Men menu">
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
              <a href="menssocks.php">Socks</a>
            </div>
          </div>
        </li>

        <li class="has-mega">
          <button class="nav-link" data-menu="women" aria-expanded="false">Women</button>
          <div class="mega" id="mega-women" role="dialog" aria-label="Women menu">
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
              <a href="womensknitwear.php">Knitwear</a>
              <a href="womenactivewear.php">Activewear</a>
            </div>
          </div>
        </li>

        <li class="has-mega">
          <button class="nav-link" data-menu="kids" aria-expanded="false">Kids</button>
          <div class="mega" id="mega-kids" role="dialog" aria-label="Kids menu">
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
      <button id="searchToggle" class="icon-btn" aria-expanded="false" aria-controls="searchBar" title="Search">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2"/>
          <line x1="16.65" y1="16.65" x2="21" y2="21" stroke="currentColor" stroke-width="2"/>
        </svg>
      </button>

      <a href="favourites.php" class="icon-btn" title="Favourites" aria-label="Favourites">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 21s-7-4.5-9-8.5S5 2 8.5 5.5L12 9l3.5-3.5C19 2 25 7 21 12.5S12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
      </a>

      <span id="favCount" class="muted"></span>

      <button id="cartButton" class="icon-btn" title="Bag">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M6 7h12l-1 13H7L6 7z" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="M9 7V5a3 3 0 1 1 6 0v2" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
      </button>

      <span id="cartCount" class="muted"></span>

      <div id="cartPreview" class="cart-preview">
        <div id="cartPreviewItems"></div>
        <div class="cart-preview-total">
          Total: <span id="cartPreviewTotal">£0.00</span>
        </div>
        <a href="cart.php" class="btn">View Basket</a>
      </div>
    </div>
  </div>

  <div id="searchBar" class="searchbar" hidden>
    <div class="container">
      <form id="searchForm" role="search" aria-label="Site search">
        <input type="search" id="q" placeholder="Search" aria-label="Search" />
        <button type="submit" class="btn">Search</button>
      </form>
      <p class="search-hint">Try “Joggers” or “Tracksuits”.</p>
    </div>
  </div>
</header>

<main>
  <section class="returns-page">
    <div class="container">
      <div class="returns-header">
        <h1>Returns & Refunds</h1>
        <p class="subtitle">Everything you need to know about returning your Lunare order</p>
      </div>

      <div class="returns-grid">
        <div class="returns-card">
          <h3>Easy Returns</h3>
          <p>You can return unwanted items within 14 days of receiving your order.</p>
        </div>

        <div class="returns-card">
          <h3>Refund Process</h3>
          <p>Once your return is received and checked, your refund will be processed back to your original payment method.</p>
        </div>

        <div class="returns-card">
          <h3>Return Conditions</h3>
          <p>Items must be unused, in original condition, and returned with any tags and packaging where possible.</p>
        </div>
      </div>

      <div class="returns-steps">
        <h2>How to Return an Item</h2>
        <ol>
          <li>Package your item securely.</li>
          <li>Include your order details or proof of purchase.</li>
          <li>Send your parcel back using your preferred delivery service.</li>
          <li>Wait for your return to be received and processed.</li>
        </ol>
      </div>

      <div class="returns-info">
        <h2>Important Information</h2>
        <p>Refunds are usually processed within 5–10 working days after your return reaches us.</p>
        <p>Delivery charges are non-refundable unless your item is faulty or the wrong item was sent.</p>
        <p>For hygiene reasons, some items may not be eligible for return unless faulty.</p>
      </div>

      <div class="returns-faq">
        <h2>Returns FAQs</h2>

        <div class="faq-item">
          <button class="faq-question">How long do I have to return an item?</button>
          <div class="faq-answer">
            <p>You can return items within 14 days of receiving your order.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question">When will I receive my refund?</button>
          <div class="faq-answer">
            <p>Refunds are usually processed within 5–10 working days after the returned item is received.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question">Can I exchange an item?</button>
          <div class="faq-answer">
            <p>At the moment we recommend returning the item and placing a new order for the replacement you want.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question">What if my item is faulty?</button>
          <div class="faq-answer">
            <p>Please contact us as soon as possible through the contact page and we will help resolve the issue.</p>
          </div>
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
document.querySelectorAll('.faq-question').forEach(btn => {
  btn.addEventListener('click', () => {
    const answer = btn.nextElementSibling;
    answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
  });
});
</script>

</body>
</html>