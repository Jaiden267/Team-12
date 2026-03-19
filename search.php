<?php
session_start();
require_once 'db_connect.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    header("Location: index.php");
    exit();
}

// 1. Fetch products based on search
$stmt = $conn->prepare("SELECT p.*, pi.image_url FROM products p LEFT JOIN product_images pi ON p.product_id = pi.product_id WHERE p.name LIKE ? AND (pi.is_main = 1 OR pi.is_main IS NULL)");
$search = "%$q%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

// 2. Prepare the variant query (using numerical sort fix)
$variant_stmt = $conn->prepare("SELECT variant_id, attribute_value, additional_price FROM product_variants WHERE product_id = ? ORDER BY CAST(attribute_value AS UNSIGNED) ASC");

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    header("Location: indproduct.php?id=" . $row['product_id']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Search Results — Lunare Clothing</title>
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
        </button>
        <ul class="menu">
          <li><a href="allproducts.php" class="nav-link">All Products</a></li>
          <li class="has-mega">
            <button class="nav-link" data-menu="men">Men</button>
            <div class="mega" id="mega-men">
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
                <h4>Kids</h4>
                <a href="kidstshirts.php">T-Shirts</a>
              </div>
            </div>
          </li>
          <li><a href="#" class="nav-link sale">Sale</a></li>
        </ul>
      </nav>

      <div class="actions">
        <button id="searchToggle" class="icon-btn" title="Search">
            <svg viewBox="0 0 24 24" aria-hidden="true" style="width:22px; height:22px;"><circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2"/><line x1="16.65" y1="16.65" x2="21" y2="21" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        <button class="icon-btn" title="Favourites">
            <svg viewBox="0 0 24 24" aria-hidden="true" style="width:22px; height:22px;"><path d="M12 21s-7-4.5-9-8.5S5 2 8.5 5.5L12 9l3.5-3.5C19 2 25 7 21 12.5S12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        <button id="cartButton" class="icon-btn" title="Bag">
            <svg viewBox="0 0 24 24" aria-hidden="true" style="width:22px; height:22px;"><path d="M6 7h12l-1 13H7L6 7z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M9 7V5a3 3 0 1 1 6 0v2" fill="none" stroke="currentColor" stroke-width="2"/></svg>
        </button>
      </div>
    </div>
</header>

<main>
    <div class="container">
        <h1 style="margin: 40px 0 20px 0;">Search results for: "<?= htmlspecialchars($q) ?>"</h1>

        <?php if ($result->num_rows === 0): ?>
            <p>No products found matching your search.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php while ($p = $result->fetch_assoc()): 
                    // Fetch variants for this product
                    $variant_stmt->bind_param("i", $p['product_id']);
                    $variant_stmt->execute();
                    $var_res = $variant_stmt->get_result();
                    $variants = $var_res->fetch_all(MYSQLI_ASSOC);
                    $initial_price = !empty($variants) ? $variants[0]['additional_price'] : $p['base_price'];
                ?>
                    <article class="product-card">
                        <a href="indproduct.php?id=<?= $p['product_id'] ?>">
                            <img src="<?= htmlspecialchars($p['image_url']) ?>" class="product-img">
                        </a>
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        
                        <p id="price-display-<?= $p['product_id'] ?>" class="price">
                            £<?= number_format($initial_price, 2) ?>
                        </p>

                        <?php if(!empty($variants)): ?>
                            <select class="grid-size-select select" style="width:100%; margin-bottom:10px;" id="size-<?= $p['product_id'] ?>" data-product-id="<?= $p['product_id'] ?>">
                                <?php foreach($variants as $v): ?>
                                    <option value="<?= $v['variant_id'] ?>" data-price="<?= $v['additional_price'] ?>">
                                        <?= htmlspecialchars($v['attribute_value']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>

                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                            <label style="font-size:12px;">Qty:</label>
                            <input type="number" id="qty-<?= $p['product_id'] ?>" value="1" min="1" class="qty" style="width:60px;">
                        </div>

                        <button class="add-to-cart-grid-btn btn primary" style="width:100%;"
                                data-id="<?= $p['product_id'] ?>" 
                                data-name="<?= htmlspecialchars($p['name']) ?>" 
                                data-image="<?= htmlspecialchars($p['image_url']) ?>">
                            Add to Cart
                        </button>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
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
document.addEventListener("DOMContentLoaded", () => {
    // 1. Logic to change price based on size
    document.querySelectorAll('.grid-size-select').forEach(select => {
        select.addEventListener('change', function() {
            const productID = this.getAttribute('data-product-id');
            const price = this.options[this.selectedIndex].getAttribute('data-price');
            const priceDisplay = document.getElementById('price-display-' + productID);
            if (priceDisplay) {
                priceDisplay.innerText = '£' + parseFloat(price).toFixed(2);
            }
        });
    });

    // 2. Add to Cart logic
    document.querySelectorAll(".add-to-cart-grid-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const pid = this.dataset.id;
            const sizeDropdown = document.getElementById("size-" + pid);
            const qtyInput = document.getElementById("qty-" + pid);

            const sizeName = sizeDropdown.options[sizeDropdown.selectedIndex].text.trim();
            const price = Number(sizeDropdown.selectedOptions[0].dataset.price);
            const qty = Number(qtyInput.value || 1);

            const item = {
                sku: pid,
                name: this.dataset.name,
                price: price,
                image: this.dataset.image,
                size: sizeName,
                qty: qty
            };

            const cart = loadCart(); // uses your app.js cart system
            const existing = cart.find(i => i.sku === item.sku && i.size === item.size);
            if (existing) existing.qty += qty; else cart.push(item);
            
            saveCart(cart);
            alert(`Added ${qty} × ${item.name} (Size: ${sizeName}) to cart.`);
        });
    });

    // Dynamic year script
    const yr = document.getElementById("year");
    if(yr) yr.textContent = new Date().getFullYear();
});
</script>
</body>
</html>