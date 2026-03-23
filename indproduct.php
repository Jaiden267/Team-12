<?php
session_start();
require_once 'db_connect.php';
if (isset($_GET['id'])){
    $product_id = intval($_GET['id']);
    $plswork = $conn->prepare("SELECT products.*, image_url FROM products LEFT JOIN product_images ON products.product_id = product_images.product_id WHERE products.product_id = ?");
    $plswork->bind_param("i", $product_id);
    $plswork->execute();
    $result = $plswork->get_result();
    $product = $result->fetch_assoc();
    $plswork2 = $conn->prepare("SELECT pv.variant_id, pv.attribute_value, pv.additional_price, s.quantity FROM product_variants pv LEFT JOIN stock s ON pv.variant_id = s.variant_id WHERE pv.product_id = ? ORDER BY CAST(pv.attribute_value AS UNSIGNED) ASC");
    $plswork2->bind_param("i", $product_id);
    $plswork2->execute();
    $var_result = $plswork2->get_result();
    $variants = [];
    while ($row = $var_result->fetch_assoc()) {
        $variants[] = $row;
    }
    $start_price = !empty($variants) ? $variants[0]['additional_price'] : $product['base_price'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
      if (isset($_SESSION['user_id'])) {
        $u_id = $_SESSION['user_id'];
        $rating = intval($_POST['rating']);
        $comment = trim($_POST['comment']);
        $review_stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
        $review_stmt->bind_param("iiis", $u_id, $product_id, $rating, $comment);
        if ($review_stmt->execute()) {
          echo "<script>alert('Review submitted!'); window.location.href='indproduct.php?id=$product_id';</script>";
        }
        }
    }
        $get_reviews = $conn->prepare("SELECT r.*, u.first_name FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $get_reviews->bind_param("i", $product_id);
        $get_reviews->execute();
        $reviews_res = $get_reviews->get_result();
        $reviews = $reviews_res->fetch_all(MYSQLI_ASSOC);
        $avg_rating = 0;
        if (count($reviews) > 0) {
          $avg_rating = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
          }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <title>Lunare Clothing — Product</title>

  <link rel="stylesheet" href="styles.css" />
</head>
<body>
 <div class="utility-strip">
    <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
        
        
        <span>FREE DELIVERY & RETURNS</span>

        
        <div style="display:flex; gap:15px; align-items:center;">
            <a href="contact.php" class="link">Contact Us</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                 <a href="accounts.php"><span class="link">Hello <?= htmlspecialchars($_SESSION['first_name']); ?></span></a>
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
          <li><a href="allproducts.php" class="nav-link">All Products</a></li>
          <li class="has-mega">
            <button class="nav-link" data-menu="men" aria-expanded="false">Men</button>
            <div class="mega" id="mega-men" role="dialog" aria-label="Men menu">
              <div class="mega-col">
                <h4>Highlights</h4>
                <a href="menstrousers.php">New in Men</a>
                <a href="menssocks.php">Bestseller</a>
              </div>
              <div class="mega-col">
                <h4>Shoes</h4>
                <a href="menshoes.php">All Shoes</a>
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
                <a href="womensshirts.php">New in Women</a>
                <a href="womensknitwear.php">Bestseller</a>
                
              </div>
              <div class="mega-col">
                <h4>Activewear</h4>
                <a href="womanactivewear.php">All Activewear</a>
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
                <a href="kidstshirts.php">New for Kids</a>
                <a href="kidstshirts.php">Bestseller</a>
              </div>
              <div class="mega-col">
                <h4>Kids</h4>
                <a href="kidstshirts.php">T-Shirts</a>
                <a href="kidsclothing.php">Clothing</a>
              </div>
            </div>
          </li>
        </ul>
      </nav>
      <div class="actions">
        <button id="searchToggle" class="icon-btn" aria-expanded="false" aria-controls="searchBar" title="Search">
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2"/><line x1="16.65" y1="16.65" x2="21" y2="21" stroke="currentColor" stroke-width="2"/></svg>
        </button>
        <a href="favourites.php" class="icon-btn" title="Favourites">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-4.5-9-8.5S5 2 8.5 5.5L12 9l3.5-3.5C19 2 25 7 21 12.5S12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
        </a>
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
        <p class="search-hint">Try “Shirts” or “Trousers”.</p>
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
      <div style="margin-top: -9px; margin-bottom: 13px;">
        <div style="margin-bottom: 15px;">
          <button id="favbttn" class="icon-btn" style="display: inline-flex; align-items: center; gap: 9px; border: 1px solid var(--line); padding: 7px 18px; border-radius: 1000px; font-size: 15px; font-weight: 610;">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-4.5-9-8.5S5 2 8.5 5.5L12 9l3.5-3.5C19 2 25 7 21 12.5S12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2"/></svg></button>
            </div>
        <span style="color: #f39c12; font-size: 1.2rem;">
          <?= str_repeat('★', floor($avg_rating)) . str_repeat('☆', 5 - floor($avg_rating)); ?>
          </span>
          <span style="font-size: 0.9rem; color: #777; margin-left: 5px;">(<?= count($reviews) ?> reviews)</span>
          </div>
      <p class="single-product-text"><?= !empty($product['description']) ? nl2br(htmlspecialchars($product['description'])) : 'No description available.'; ?></p>
<?php if(!empty($variants)): ?>
      <div style="margin-top: 20px;">
          <p class="single-product-size" style="margin-bottom:5px;">Select Size:</p>
          <select id="sizeSelect" class="product-select">
              <?php foreach($variants as $var): ?>
                  <option value="<?= $var['variant_id']; ?>" data-price="<?= $var['additional_price']; ?>"
                    data-stock="<?= $var['quantity'] ?? 0; ?>">
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
    <div style="margin-top: 10px;">
        <span id="stockDisplay" style="font-weight: bold;">
          <?php
          $initial_qty = !empty($variants) ? ($variants[0]['quantity'] ?? 0) : 0;
          if ($initial_qty == 0) {
            echo "Out of Stock: " . $initial_qty;
            } elseif ($initial_qty < 10) {
              echo "Low Stock: " . $initial_qty;
              } else {
                echo "In Stock: " . $initial_qty;
                }
          ?>
        </span>
        </div>
    <div style="width: 100%;">
        <button id="addToCartBtn" class="single-product-cart-btn"
        <?php if($initial_qty == 0) echo 'disabled style="background-color: #ccc; cursor: not-allowed;"'; ?>>
        <?php echo ($initial_qty == 0) ? 'Out of Stock' : 'Add to Cart'; ?></button>
            </div>
    </div>
</div>
  </div>
</div>
<div class="container" style="margin-top: 55px; padding-top: 38px; border-top: 1px solid #eee; margin-bottom: 75px;">
  <div class="reviewb">
    <?php if(isset($_SESSION['user_id'])): ?>
      <h3 style="margin-bottom: 25px; font-size: 1.4rem;">Review this Product&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;</h3>
      <form method="POST">
        <label style="display:block; margin-bottom: 8px; font-weight: 600;">Rating:</label>
        <div class="starf">
          <input type="radio" id="star5" name="rating" value="5" required /><label for="star5"></label>
          <input type="radio" id="star4" name="rating" value="4" /><label for="star4"></label>
          <input type="radio" id="star3" name="rating" value="3" /><label for="star3"></label>
          <input type="radio" id="star2" name="rating" value="2" /><label for="star2"></label>
          <input type="radio" id="star1" name="rating" value="1" /><label for="star1"></label>
          </div>
          <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom: 8px; font-weight: 600;">Review:</label>
            <textarea name="comment" class="tspace" required placeholder="What did you think of this product?"></textarea>
            </div>
            <button type="submit" name="submit_review" class="single-product-cart-btn" style="width: auto; padding: 12px 40px;">Post Review</button></form>
            <?php else: ?><p style="text-align: center; color: #666;">You must be logged in to review.&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;&#12288;</p>
              <?php endif; ?>
              </div>
              <div class="reviews-display-area">
                <p style="font-size: 19px; margin-bottom: 25px; font-weight: 600;">
                  Customer Reviews: </p>
                  <?php if(count($reviews) > 0): ?>
                    <div class="reviews-list">
                      <?php foreach($reviews as $r): ?>
                        <div style="padding: 25px 0; border-bottom: 1px solid #f0f0f0;">
                          <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                              <span style="font-weight: 700; font-size: 19px; display: block;"><?= htmlspecialchars($r['first_name']) ?></span>
                              <span style="font-size: 0.85rem; color: #999;"><?= date('F j, Y', strtotime($r['created_at'])) ?></span>
                              </div>
                              <span style="color: #f39c12; font-size: 19px;">
                                <?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?>
                                </span>
                                </div>
                                <p style="margin-top: 14px; line-height: 1.7; color: #444;"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                                </div>
                                <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                  <p style="color: #999; font-style: italic;">No reviews for this product yet.</p>
                                  <?php endif; ?>
                                  </div>
                                  </div>
<script>
  const sizeDropdown = document.getElementById('sizeSelect');
  const priceText = document.getElementById('single-product-price');
  const stockText = document.getElementById('stockDisplay');
  const addToCartBtn = document.getElementById('addToCartBtn');
  if(sizeDropdown && priceText && stockText){
    sizeDropdown.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const newPrice = parseFloat(selectedOption.getAttribute('data-price'));
        priceText.textContent = '£' + newPrice.toFixed(2);
        const qty = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        let label = "";
        if (qty === 0) {
          label = "Out of Stock: ";
          if(addToCartBtn) {
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = "Out of Stock";
            addToCartBtn.style.backgroundColor = "#ccc";
            addToCartBtn.style.cursor = "not-allowed";}
                                }
          else { if (qty < 10) {
            label = "Low Stock: ";
            } else {
              label = "In Stock: ";
              }
              if(addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = "Add to Cart";
                addToCartBtn.style.backgroundColor = "black";
              stockText.textContent = label + qty;
              addToCartBtn.style.cursor = "pointer"; } }
              stockText.textContent = label + qty;
    });
  }
  </script>
  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <h5>Support</h5>
        <a href="delivery.php">Delivery</a>
        <a href="contact.php">Let Us Know How We Did</a>
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
        alert(`Added ${qty} × <?= htmlspecialchars($product['name']); ?> (Size: ${sizeName}) to cart.`); });

        const favbttn = document.getElementById("favbttn");
        if (favbttn) {
          favbttn.addEventListener("click", function(e) {
            e.preventDefault();
            const sizeDropdown = document.getElementById("sizeSelect");
            const variantId = sizeDropdown.value;
            fetch('favprocess.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `variant_id=${variantId}`})
              .then(response => response.json())
              .then(data => {
                if (data.status === 'success') {
                  favbttn.querySelector('svg path').setAttribute('fill', 'currentColor');
                  alert("Favourited Item");
                  } else if (data.status === 'unauthorized') {
                    alert("Please log in to save a Favourite.");
                    } else if (data.status === 'exists') {
                      alert("This product is already in your Favourites");}})
                      });
                      }
                      });
</script>
 <script src="//code.tidio.co/t2metx8c6fo4wq7w8lvxrczj0m32nwmk.js" async></script>
</body>
</html>