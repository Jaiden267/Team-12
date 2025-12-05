<?php
session_start();
require_once 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <!-- Page Title -->
  <title>Lunare Clothing — Home</title>

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

  <!-- Header including brand, navigation and actions -->
  <header class="site-header">
    <!-- Brand logo that links to the home page -->
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

      <!-- Action buttons on the header: search toggle, favourites, bag -->
 <div class="actions">
        <button id="searchToggle" class="icon-btn" aria-expanded="false" aria-controls="searchBar" title="Search">
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2"/><line x1="16.65" y1="16.65" x2="21" y2="21" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        <button class="icon-btn" title="Favourites">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-4.5-9-8.5S5 2 8.5 5.5L12 9l3.5-3.5C19 2 25 7 21 12.5S12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        <button id="cartButton" class="icon-btn" title="Bag">

          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l-1 13H7L6 7z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M9 7V5a3 3 0 1 1 6 0v2" fill="none" stroke="currentColor" stroke-width="2"/></svg>
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


    <!-- Hidden search bar-->
    <div id="searchBar" class="searchbar" hidden>
      <div class="container">
        <form id="searchForm" role="search" aria-label="Site search">
          <input type="search" id="q" placeholder="Search" aria-label="Search" />
          <button type="submit" class="btn">Search</button>
        </form>
        <!-- A small hint to help users with example queries -->
        <p class="search-hint">Try “Joggers” or “Tracksuits”.</p>
      </div>
    </div>
  </header>

  <!-- Main content area: hero / promotional content -->
  <main>
    <section class="hero">
      <div class="container hero-inner">
        <div class="hero-copy">
          <h1>ALL NEW ARRIVALS</h1>
          <p>Fresh drops for the season. Shop the latest picks from Lunare Clothing.</p>
          <div class="cta">
            <!-- Primary calls-to-action -->
            <a class="btn primary" href="menstrousers.php">Shop Men's Trousers</a>
            <a class="btn" href="womenscoats.php">Shop Women's Coats</a>
            <a class="btn" href="kidstshirts.php">Shop Kids</a>
          </div>
        </div>
        <!-- Decorative artwork area for the hero -->
        <div class="hero-art" aria-hidden="true">
        <img src="assets/landingfamilyimage1.png" alt="familyimg1">
      </div>
    </section>
    <section class="container">
    <div class="promocard-row">
      <div class="promocard">
        <img src="assets/5WomensEffortlessTrenchCoat.png" alt = "mtr" >
        <h1>LC Effortless Trench Coat</h1>
        <p class="promoprice">£135.99</p>
        <p>The forever staple</p>
        <a href="/indproduct.php?id=5">
          <button>View Now</button></a>
      </div>
      <div class="promocard">
        <img src="assets/12KidsStatementGraphicTee.png" alt = "mtr" >
        <h1>LC Statment Graphic Tee</h1>
        <p class="promoprice">£35.99</p>
        <p>For little space explorers</p>
        <a href="/indproduct.php?id=12">
          <button>View Now</button></a>
      </div>
      <div class="promocard">
        <img src="assets/21WomensShirtsFoundationalCrispShirt.png" alt = "mtr" >
        <h1>LC Foundational Crisp Shirt</h1>
        <p class="promoprice">£79.99</p>
        <p>Polished, sharp, and confident</p>
        <a href="/indproduct.php?id=21">
          <button>View Now</button></a>
      </div>
    </div>
    </section>
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

  <!-- Link with javascript file -->
  <script src="app.js"></script>
</body>
</html>
