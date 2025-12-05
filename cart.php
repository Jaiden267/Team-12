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
            <form id="searchForm" role="search">
                <input type="search" id="q" placeholder="Search" />
                <button type="submit" class="btn">Search</button>
            </form>
            <p class="search-hint">Try “Joggers” or “Tracksuits”.</p>
        </div>
    </div>
</header>

<div class="page-header">
    <div class="container">
        <h1>Your Basket</h1>
    </div>
</div>

<main class="cart-wrap">
    <div class="container cart-grid">

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
        <aside class="card cart-summary">
            <h2>Summary</h2>
            <div class="row"><span>Subtotal</span><span id="subtotal" class="price">£0.00</span></div>
            <div class="row"><span>Delivery</span><span class="muted">Free</span></div>
            <div class="row total"><span>Total</span><span id="grandTotal" class="price">£0.00</span></div>

            <a href="payment.php" class="btn primary" style="margin-top:14px;">Checkout</a>
        </aside>

    </div>
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
const bodyEl = document.getElementById('cartBody');
const emptyEl = document.getElementById('cartEmpty');
const tableWrap = document.getElementById('cartTableWrap');
const subtotalEl = document.getElementById('subtotal');
const grandEl = document.getElementById('grandTotal');

function fmt(n){ return '£' + (Number(n||0)).toFixed(2); }

function renderCart(){
  const items = (typeof loadCart === 'function') ? loadCart() : [];
  bodyEl.innerHTML = '';

  if (!items.length){
    emptyEl.hidden = false;
    tableWrap.hidden = true;
    subtotalEl.textContent = fmt(0);
    grandEl.textContent = fmt(0);
    return;
  }

  emptyEl.hidden = true;
  tableWrap.hidden = false;

  let subtotal = 0;

  items.forEach((it, idx) => {
    const line = (it.price || 0) * (it.qty || 0);
    subtotal += line;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <div class="cart-item">
          <img class="cart-img" src="${it.image || ''}" alt="">
          <div>
            <div style="font-weight:700">${it.name || ''}</div>
          </div>
        </div>
      </td>
      <td>
        <div><span class="muted">Size:</span> ${it.attribute_value || it.size || ''}</div>
      </td>
      <td>${fmt(it.price)}</td>
      <td>
        <div class="qty-ctrl" data-idx="${idx}">
          <button type="button" class="minus" aria-label="Decrease quantity">−</button>
          <input type="number" class="qty-input" min="1" value="${it.qty || 1}" aria-label="Quantity">
          <button type="button" class="plus" aria-label="Increase quantity">+</button>
        </div>
      </td>
      <td class="text-right price">${fmt(line)}</td>
      <td class="text-right">
        <button class="link-btn remove" data-idx="${idx}" aria-label="Remove item">Remove</button>
      </td>
    `;
    bodyEl.appendChild(tr);
  });

  subtotalEl.textContent = fmt(subtotal);
  grandEl.textContent = fmt(subtotal);


  bodyEl.querySelectorAll('.qty-ctrl').forEach(ctrl => {
    const idx = Number(ctrl.dataset.idx);
    const input = ctrl.querySelector('.qty-input');

    ctrl.querySelector('.minus').addEventListener('click', () => {
      const items = loadCart();
      items[idx].qty = Math.max(1, Number(items[idx].qty||1) - 1);
      saveCart(items);
      renderCart();
    });

    ctrl.querySelector('.plus').addEventListener('click', () => {
      const items = loadCart();
      items[idx].qty = Number(items[idx].qty||1) + 1;
      saveCart(items);
      renderCart();
    });

    input.addEventListener('change', () => {
      const items = loadCart();
      let val = Number(input.value);
      if (!Number.isFinite(val) || val < 1) val = 1;
      items[idx].qty = val;
      saveCart(items);
      renderCart();
    });
  });


  bodyEl.querySelectorAll('.remove').forEach(btn => {
    btn.addEventListener('click', () => {
      const idx = Number(btn.dataset.idx);
      const items = loadCart();
      items.splice(idx, 1);
      saveCart(items);
      renderCart();
    });
  });
}

renderCart();
</script>

</body>
</html>
