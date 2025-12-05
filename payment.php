<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lunare Clothing — Checkout</title>
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
        <img src="assets/lunare_logo.png" alt="Lunare Clothing logo" class="brand-img">
        <span class="wordmark">LUNARE CLOTHING</span>
      </a>

      <nav class="primary-nav">
        <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
        <ul class="menu">
          <li><a href="#" class="nav-link">New</a></li>
          <li class="has-mega">
            <button class="nav-link" data-menu="men">Men</button>
          </li>
          <li class="has-mega">
            <button class="nav-link" data-menu="women">Women</button>
          </li>
          <li class="has-mega">
            <button class="nav-link" data-menu="kids">Kids</button>
          </li>
          <li><a href="#" class="nav-link sale">Sale</a></li>
        </ul>
      </nav>

      <div class="actions">
        <button id="searchToggle" class="icon-btn">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        <button class="icon-btn" title="Favourites"></button>
        <button id="cartButton" class="icon-btn" title="Bag"></button>
        <span id="cartCount" class="muted"></span>
      </div>
    </div>
</header>

<section class="payment-section">
    <div class="payment-container">
      <h1>Checkout</h1>

      <form id="checkoutForm" method="POST" action="process_order.php" noValidate>

        <h2 class="checkout-heading">Billing Details</h2>

        <div class="form-row">
            <label for="billing_name">Full Name</label>
            <input type="text" id="billing_name" name="billing_name" required>
        </div>

        <div class="form-row">
            <label for="billing_email">Email Address</label>
            <input type="email" id="billing_email" name="billing_email" required>
        </div>

        <div class="form-row">
            <label for="billing_phone">Phone Number</label>
            <input type="tel" id="billing_phone" name="billing_phone" pattern="[0-9]{11}" required>
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
            <label for="city">City</label>
            <input type="text" id="city" name="city" required>
        </div>

        <div class="form-row">
            <label for="postcode">Postcode</label>
            <input type="text" id="postcode" name="postcode" required>
        </div>

        <div class="form-row">
            <label for="country">Country</label>
            <select id="country" name="country" required>
                <option value="">Select Country</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ireland">Ireland</option>
            </select>
        </div>

        <h2 class="checkout-heading">Payment Details</h2>

        <div class="form-row">
            <label for="card_name">Card Holder's Name</label>
            <input type="text" id="card_name" name="card_name" required>
        </div>

        <div class="form-row">
            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" required>
        </div>

        <div class="form-row">
            <label for="expiry">Expiry Date (MM/YY)</label>
            <input type="text" id="expiry" name="expiry" maxlength="5" required>
        </div>

        <div class="form-row">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" maxlength="3" required>
        </div>

        <div class="checkout-summary">
          <h2 class="checkout-heading">Order Summary</h2>
          <div id="checkoutItems"></div>
          <div class="row"><span>Subtotal</span><span id="checkoutSubtotal" class="price">£0.00</span></div>
          <div class="row"><span>Delivery</span><span class="muted">Free (UK Standard)</span></div>
          <div class="row total"><span>Total</span><span id="checkoutTotal" class="price">£0.00</span></div>
        </div>

        <input type="hidden" id="cartData" name="cart">
        <button type="submit" class="pay-btn">Pay Now</button>
      </form>
    </div>
</section>

<footer class="site-footer">
    <div class="container footer-bottom">
      © <span id="year"></span> Lunare Clothing
    </div>
</footer>

<script src="app.js"></script>

<script>
function renderCheckoutSummary() {
    const items = loadCart();
    const list = document.getElementById('checkoutItems');
    const subEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotal');
    if (!items.length){
        list.innerHTML = '<p class="muted">Your cart is empty.</p>';
        subEl.textContent = '£0.00';
        totalEl.textContent = '£0.00';
        return;
    }
    let subtotal = 0;
    list.innerHTML = '';
    items.forEach(it => {
        const line = it.price * it.qty;
        subtotal += line;
        const div = document.createElement('div');
        div.className = 'checkout-item';
        div.innerHTML = `
            <div><strong>${it.name}</strong><div class="muted">${it.color.toUpperCase()} • ${it.size} • Qty: ${it.qty}</div></div>
            <div class="price">£${line.toFixed(2)}</div>
        `;
        list.appendChild(div);
    });
    subEl.textContent = `£${subtotal.toFixed(2)}`;
    totalEl.textContent = `£${subtotal.toFixed(2)}`;
}

function isValidEmail(email){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isExpiryValid(exp){
    if (!/^\d{2}\/\d{2}$/.test(exp)) return false;
    const [m, y] = exp.split('/').map(Number);
    if (m < 1 || m > 12) return false;
    const now = new Date();
    const currentYear = Number(String(now.getFullYear()).slice(2));
    const currentMonth = now.getMonth() + 1;
    if (y < currentYear) return false;
    if (y === currentYear && m < currentMonth) return false;
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    renderCheckoutSummary();
    const form = document.getElementById("checkoutForm");

    form.addEventListener("submit", function (e) {

        const email = document.getElementById("billing_email").value.trim();
        const expiry = document.getElementById("expiry").value.trim();

        if (!isValidEmail(email)) {
            e.preventDefault();
            alert("Please enter a valid email address.");
            return false;
        }

        if (!isExpiryValid(expiry)) {
            e.preventDefault();
            alert("Your card expiry date is invalid or in the past.");
            return false;
        }

        document.getElementById("cartData").value = JSON.stringify(loadCart());
        return true;
    });
});
</script>

</body>
</html>
