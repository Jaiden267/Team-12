<?php
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Sign In</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

  <!-- Top bar with links -->
  <div class="utility-strip">
    <div class="container">
      <span>FREE DELIVERY & RETURNS</span>
      <div>
        <a href="contact.php" class="link">Contact Us</a>
        <a href="register.php" class="link">Register</a>
        <a href="signin.php" class="link">Sign In</a>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="site-header">
    <div class="container header-inner">

      <!-- Logo -->
      <a href="index.php" class="brand">
        <img src="assets/lunare_logo.png" alt="Lunare Clothing logo" class="brand-img">
        <span class="wordmark">LUNARE CLOTHING</span>
      </a>

      <!-- RESTORED FULL NAVIGATION -->
      <nav class="primary-nav" aria-label="Primary">

        <button class="hamburger" id="hamburger" aria-expanded="false" aria-controls="mobileMenu">
          <span></span><span></span><span></span>
          <span class="sr-only">Toggle menu</span>
        </button>

        <ul class="menu">
          <li><a href="#" class="nav-link">New</a></li>

          <!-- MEN -->
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
                <a href="#">All Clothing</a>
                <a href="#">Tops & T-Shirts</a>
                <a href="#">Hoodies & Sweatshirts</a>
                <a href="#">Shorts</a>
                <a href="#">Tracksuits</a>
                <a href="#">Trousers & Tights</a>
                <a href="#">Jackets</a>
                <a href="#">Accessories</a>
              </div>
            </div>
          </li>

          <!-- WOMEN  -->
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
                <a href="#">All Clothing</a>
                <a href="#">Tops & T-Shirts</a>
                <a href="#">Hoodies & Sweatshirts</a>
                <a href="#">Leggings & Tights</a>
                <a href="#">Jackets</a>
              </div>
            </div>
          </li>

          <!-- KIDS -->
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
                <a href="#">Shoes</a>
                <a href="#">Clothing</a>
              </diQAFRWFRVv>
            </div>
          </li>

          <li><a href="#" class="nav-link sale">Sale</a></li>
        </ul>
      </nav>

      <!-- ACTION ICONS  -->
      <div class="actions">
        <button id="searchToggle" class="icon-btn" aria-expanded="false" aria-controls="searchBar" title="Search">
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2"/><line x1="16.65" y1="16.65" x2="21" y2="21" stroke="currentColor" stroke-width="2"/></svg>
        </button>

        <button class="icon-btn" title="Favourites">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-4.5-9-8.5S5 2 8.5 5.5L12 9l3.5-3.5C19 2 25 7 21 12.5S12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        

        



  <!-- Basket image icon ) -->
  <a href="cart.php" title="Basket" class="icon-btn">
    <img
      src="https://www.bing.com/th/id/OIP.w8yXK9rQeK3LBPjapoesVwHaHa?w=195&h=211&c=8&rs=1&qlt=90&o=6&pid=3.1&rm=2
      alt="Basket"
      style="width:26px; height:29px;"
    />
  </a>

</div>

      </div>

    </div>

    <!-- SEARCH BAR FOR SEARCHING -->
    <div id="searchBar" class="searchbar" hidden>
      <div class="container">
        <form role="search" aria-label="Site search">
          <input type="search" placeholder="Search" />
          <button type="submit" class="btn">Search</button>
        </form>
        <p class="search-hint">Try “Joggers” or “Tracksuits”.</p>
      </div>
    </div>

  </header>

  <!-- Sign In Section, email,password,forgot password, sign up link -->
  <section class="contact">
    <div class="container signin-container">

      <h2>Sign In</h2>

      <div class="contact-form">
        <h3>Welcome Back</h3>

        <form>
          <div class="form-group">
            <input type="email" placeholder="Email Address" required>
          </div>

          <div class="form-group">
            <input type="password" placeholder="Password" required>
          </div>

          <a class="forgot-password" href="#">Forgot your password?</a>
          <button type="submit" class="submitbtn">Sign In</button>

          <p class="signup-text">Don't have an account?
            <a href="register.php">Sign up</a>
          </p>
        </form>
      </div>

    </div>
  </section>

  <!-- bottom Footer -->
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
</body>
</html>
