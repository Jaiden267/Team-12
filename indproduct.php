<?php
session_start();
require_once 'db_connect.php';
if (isset($_GET['id'])) 
    $product_id = intval($_GET['id']);
    $plswork = $conn->prepare("SELECT products.*, image_url FROM products LEFT JOIN product_images ON products.product_id = product_images.product_id WHERE products.product_id = ?");
    $plswork->bind_param("i", $product_id);
    $plswork->execute();
    $result = $plswork->get_result();
    $product = $result->fetch_assoc();
    $plswork2 = $conn->prepare("SELECT variant_id, attribute_value, additional_price FROM product_variants WHERE product_id = ? ORDER BY variant_id ASC");
    $plswork2->bind_param("i", $product_id);
    $plswork2->execute();
    $var_result = $plswork2->get_result();
    
    $variants = [];
    while ($row = $var_result->fetch_assoc()) {
        $variants[] = $row;
    }
    $start_price = !empty($variants) ? $variants[0]['additional_price'] : $product['base_price'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
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
                <a>New in Men</a>
                <a>Bestseller</a>
              </div>
              <div class="mega-col">
                <h4>Shoes</h4>
                <a>All Shoes</a>
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
                <a>New in Women</a>
                <a>Bestseller</a>
                
              </div>
              <div class="mega-col">
                <h4>Shoes</h4>
                <a>All Shoes</a>

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
                <a> New for Kids</a>
                <a>Bestseller</a>
              </div>
              <div class="mega-col">
                <h4>Kids</h4>
                <a href="kidstshirts.php">T-Shirts</a>
                <a>Clothing</a>
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
  <div class="page-header"><div class="container"><h1><?= htmlspecialchars($product['name']); ?></h1></div></div>
<div id="single-product-container">
  <div id="single-product-box">
    <div id="single-product-box-image"><img src="<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" width="100%" height="100%" style="object-fit: contain;"></div>
    <div id="single-product-box-content">
      <h1 id="single-product-title"><?= htmlspecialchars($product['name']); ?></h1>
      <p id="single-product-price">£<?= number_format($start_price, 2); ?></p>
      <p class="single-product-text"><?= !empty($product['description']) ? nl2br(htmlspecialchars($product['description'])) : 'No description available.'; ?></p>
<?php if(!empty($variants)): ?>
      <div style="margin-top: 20px;">
          <p class="single-product-size" style="margin-bottom:5px;">Select Size:</p>
          <select id="sizeSelect" class="product-select">
              <?php foreach($variants as $var): ?>
                  <option value="<?= $var['variant_id']; ?>" data-price="<?= $var['additional_price']; ?>">
                      <?= htmlspecialchars($var['attribute_value']); ?>
                  </option>
              <?php endforeach; ?>
          </select>
      </div>
      <?php endif; ?>
          <div id="update-cart-container">
    <div>
        <p class="single-product-size" style="margin-bottom:5px;">Quantity:</p>
        <select id="qtySelect" class="product-select" style="padding:10px; width:70px; font-size:16px;">
            <?php for($i=1; $i<=10; $i++): ?>
                <option value="<?= $i; ?>"><?= $i; ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div style="width: 100%;">
        <button class="single-product-cart-btn">Add to Cart</button>
            </div>
    </div>
</div>
  </div>
</div>
<script>
  const sizeDropdown = document.getElementById('sizeSelect');
  const priceText = document.getElementById('single-product-price');
  if(sizeDropdown && priceText){
    sizeDropdown.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const newPrice = parseFloat(selectedOption.getAttribute('data-price'));
        priceText.textContent = '£' + newPrice.toFixed(2);
    });
  }
  </script>
  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <h5>Support</h5>
        <a href="#">Help</a>
        <a href="#">Delivery</a>
        <a href="#">Returns</a>
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
  <script>

  document.addEventListener("DOMContentLoaded", () => {
    const addBtn = document.querySelector(".single-product-cart-btn");
    if (!addBtn) return;

    addBtn.addEventListener("click", function(e) {
        e.preventDefault();
        const sizeDropdown = document.getElementById("sizeSelect");
        const sizeId = sizeDropdown.value;
        const sizeName = sizeDropdown.options[sizeDropdown.selectedIndex].text.trim();
        const qty = Number(document.getElementById("qtySelect")?.value || 1);
        const price = Number(
            sizeDropdown.selectedOptions[0]?.dataset.price
        );

        const item = {
            sku: "<?= $product['product_id']; ?>",
            name: "<?= htmlspecialchars($product['name']); ?>",
            price: price,
            image: "<?= htmlspecialchars($product['image_url']); ?>",
            color: "default",
            size: sizeName,
            qty: qty
        };

        if (!sizeId) {
            alert("Please select a size before adding to cart.");
            return;
        }

        const cart = loadCart();
        const key = (i) => `${i.sku}-${i.size}`;

        const existingIndex = cart.findIndex(i => key(i) === key(item));

        if (existingIndex >= 0) {
            cart[existingIndex].qty += qty;
        } else {
            cart.push(item);
        }

        saveCart(cart);
        alert(`Added ${qty} × <?= htmlspecialchars($product['name']); ?> (Size: ${sizeName}) to cart.`);
    });
});
</script>
</body>
</html>