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

  <section class = "payment-section">
    <div class = "payment-container">
      <h1>Checkout</h1>
 
      <form id="checkoutForm" noValidate>
        
      <h2 class ="checkout-heading">Billing Details</h2>
        
      <div class="form-row">
        <label for="billing_name">Full Name</label>
        <input type="text" id="billing_name" name="billing_name" required>
      </div>

      <div class="form-row">
        <label for="address1">Address Line 1</label>
        <input type="text" id="address1" name="address1" required>
      </div>

      <div class="form-row">
        <label for="address2">Address Line 2</label>
        <input type="text" id="address2" name="address2">
      </div>

      <div class="form-row">
        <label for="postcode">Postcode</label>
        <input type="text" id="postcode" name="postcode" placeholder="e.g. SW1A 1AA" required>
      </div>

      <div class="form-row">
        <label for="country">Country</label>
        <select id="country" name="country" required>
          <option value="">Select Country</option>
          <option value="United Kingdom">United Kingdom</option>
          <option value="Ireland">Ireland</option>
          </select>
      </div>

      <h2 class ="checkout-heading">Payment Details</h2>

      <div class="form-row">
        <label for="card_name">Card Holder's Name</label>
        <input type="text" id="card_name" name="card_name" required>
      </div>

      <div class="form-row">
        <label for="card_number">Card Number</label>
        <input type="text" id="card_number" name="card_number" inputmode="numeric" autocomplete="cc-number" placeholder="1234 5678 9012 3456" required>
      </div>

      <div class="form-row" style="display: flex; gap: 10px;">
        <div style="flex: 1;">
          <label for="exp_month">Expiry(MM) </label>
          <input type="text" id="exp_month" name="exp_month" input="numeric" placeholder="MM" maxlength="2" required>
        </div>

        <div style="flex: 1;">
          <label for="exp_year">Expiry(YY) </label>
          <input type="text" id="exp_year" name="exp_year" input="numeric" placeholder="YY" maxlength="2" required>
        </div>

        <div style="flex: 1;">
          <label for="cvv">CVV</label>
          <input type="password" id="cvv" name="cvv" input="numeric" placeholder="123" maxlength="3" required>
        </div>
      </div>

      <div class="checkout-summary">
        <h2 class ="checkout-heading">Order Summary</h2>

        <div id = "checkoutItems"></div>

        <div class = "row">
          <span>Subtotal</span>
          <span id="checkoutSubtotal" class = "price">£0.00</span>
        </div>
        <div class = "row">
          <span>Delivery</span>
          <span class = "muted">Free (UK Standard)</span>
        </div>
        <div class = "row total">
          <span>Total</span>
          <span id="checkoutTotal" class = "price">£0.00</span>
        </div>
      </div>

      <button type="submit" class="pay-btn">Pay Now</button>
      </form>
    </div>
  </section>
        

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
    function renderCheckoutSummary() {
      const items = loadCart();
      const list = document.getElementById('checkoutItems');
      const subEl = document.getElementById('checkoutSubtotal');
      const totalEl = document.getElementById('checkoutTotal');

      if (!list || !subEl || !totalEl) return;

      list.innerHTML = '';

      if (!items.length) {
        list.innerHTML = '<p class = "muted">Your cart is empty. <a href="cart.php"> Go back to your cart.</p>';
        subEl.textContent = '£0.00';
        totalEl.textContent = '£0.00';
        return;
      }

      let subtotal = 0;

      items.forEach(it => {
        const line = (it.price || 0) * (it.qty || 0);
        subtotal += line;

        const div = document.createElement('div');
    div.className = 'checkout-item';
    div.innerHTML = `
      <div>
        <strong>${it.name || ''}</strong>
        <div class="muted">
          ${String(it.color || '').toUpperCase()} • ${it.size || ''} • Qty: ${it.qty || 1}
        </div>
      </div>
      <div class="price">${fmtPrice(line)}</div>
    `;
    list.appendChild(div);
  });
  subEl.textContent = fmtPrice(subtotal);
  totalEl.textContent = fmtPrice(subtotal); 
}

document.addEventListener('DOMContentLoaded', () => {
  renderCheckoutSummary();

  const form = document.getElementById('checkoutForm');
  if (!form) return;

  form.addEventListener('submit', function(e){
    e.preventDefault();

    const errors = [];

    const name      = form.billing_name.value.trim();
    const address1  = form.address1.value.trim();
    const town      = form.town.value.trim();
    const postcode  = form.postcode.value.trim();
    const country   = form.country.value.trim();

    const cardName  = form.card_name.value.trim();
    const cardNumberRaw = form.card_number.value.replace(/\s+/g, '');
    const expMonth  = form.exp_month.value.trim();
    const expYear   = form.exp_year.value.trim();
    const cvv       = form.cvv.value.trim();

    
    form.querySelectorAll('input, select').forEach(el => el.style.border = '');

    
    const ukPostcodeRegex = /^[A-Z]{1,2}\d[A-Z\d]?\s*\d[A-Z]{2}$/i;

    
    if (!name){
      errors.push('Enter your full name');
      form.billing_name.style.border = '2px solid red';
    }
    if (!address1){
      errors.push('Enter your billing address');
      form.address1.style.border = '2px solid red';
    }
    if (!town){
      errors.push('Enter your town or city');
      form.town.style.border = '2px solid red';
    }
    if (!ukPostcodeRegex.test(postcode)){
      errors.push('Enter a valid UK postcode (e.g. SW1A 1AA)');
      form.postcode.style.border = '2px solid red';
    }
    if (!country){
      errors.push('Select your country/region');
      form.country.style.border = '2px solid red';
    }

    
    if (!cardName){
      errors.push('Enter the name on the card');
      form.card_name.style.border = '2px solid red';
    }

    if (!/^\d{16}$/.test(cardNumberRaw)){
      errors.push('Card number must be 16 digits');
      form.card_number.style.border = '2px solid red';
    }

    const monthNum = Number(expMonth);
    if (!/^\d{2}$/.test(expMonth) || monthNum < 1 || monthNum > 12){
      errors.push('Enter a valid expiry month (01–12)');
      form.exp_month.style.border = '2px solid red';
    }

    const now = new Date();
    const currentYearTwo = now.getFullYear() % 100; 
    const yearNum = Number(expYear);
    if (!/^\d{2}$/.test(expYear) || yearNum < currentYearTwo){
      errors.push('Enter a valid expiry year (not in the past)');
      form.exp_year.style.border = '2px solid red';
    }

    if (!/^\d{3}$/.test(cvv)){
      errors.push('CVV must be 3 digits');
      form.cvv.style.border = '2px solid red';
    }

    if (!loadCart().length){
      errors.push('Your basket is empty – add items before checking out.');
    }

    if (errors.length){
      alert('Please fix the following:\n\n' + errors.join('\n'));
      return;
    }

   
    alert('Payment authorised (dummy checkout).\n\nThank you for your order with Lunare Clothing!');
    saveCart([]); 
    window.location.href = 'index.php';
  });
});
</script>


</body>
</html>