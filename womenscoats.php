<?php
session_start();
require_once 'db_connect.php';
$result = $conn->query("SELECT products.*, image_url FROM products LEFT JOIN product_images ON products.product_id = product_images.product_id WHERE category_id = 1");
$plswork2 = $conn->prepare("SELECT variant_id, attribute_value, additional_price FROM product_variants WHERE product_id = ?");
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


  <div class="page-header"><div class="container"><h1>Mens — Shorts</h1></div></div>
  <section class="products">
    <div class="container">
<div class="product-grid">
  <?php while($row = $result->fetch_assoc()): 
$productid = $row['product_id'];
    $plswork2->bind_param("i", $productid);
    $plswork2->execute();
    $vresult = $plswork2->get_result();
    $variants = [];
    while($v = $vresult->fetch_assoc()) {
        $variants[] = $v;}
        $current_price = !empty($variants) ? $variants[0]['additional_price'] : $row['base_price']; ?>
        <article class="product-card">
          <a href="indproduct.php?id=<?= $row['product_id']; ?>">
            <img class="product-img" src="<?= htmlspecialchars($row['image_url']); ?>" alt="<?= htmlspecialchars($row['name']);?>"></a>
            <a href="indproduct.php?id=<?= $row['product_id']; ?>" style="text-decoration: none; color: inherit;">
              <h3 class="product-name"><?= htmlspecialchars($row['name']);?></h3></a>
              <div class="product-price" id="price-display-<?= $row['product_id']; ?>">£<?= number_format($current_price, 2); ?></div>
              <form class="opts add-to-cart-form"
              data-sku="<?= $row['product_id'];?>"
              data-name="<?= htmlspecialchars($row['name']);?>"
              data-price="<?= $current_price;?>"
              data-image="<?= htmlspecialchars($row['image_url']); ?>">
              <div class="row">
                <label for="size-<?=$row['product_id'];?>">Size:</label>
                <select id="size-<?=$row['product_id'];?>" class="select grid-size-select" name="size" data-product-id="<?= $row['product_id']; ?>">
                  <?php if(!empty($variants)): ?>
                    <?php foreach($variants as $var): ?>
                      <option value="<?= $var['variant_id']; ?>"
                      data-price="<?= $var['additional_price']; ?>">
                      <?= htmlspecialchars($var['attribute_value']); ?>
                      </option>
                      <?php endforeach; ?>
                      <?php endif; ?>
                      </select>
                      <label for="qty-<?= $row['product_id'];?>">Qty:</label>
                      <input id="qty-<?= $row['product_id'];?>" class="qty" type="number" name="qty" value="1" min="1" max="5" />
                      </div>
                      <button class="add-btn" type="submit">Add to Cart</button>
                      </form>
                      </article>
                      <?php endwhile; ?>
                      </div>
</div>
  </section>
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
<script>
const workWorks = document.querySelectorAll('.grid-size-select');
workWorks.forEach(select => {
  select.addEventListener('change', function() {
    const productID = this.getAttribute('data-product-id');
    const selectedOption = this.options[this.selectedIndex];
    const newPrice = selectedOption.getAttribute('data-price');
    const priceDisplay = document.getElementById('price-display-' + productID);
    if (priceDisplay) {
      priceDisplay.innerText = '£' + parseFloat(newPrice).toFixed(2);
    }
  })
})
</script>
<script src="app.js"></script>
</body>
</html>