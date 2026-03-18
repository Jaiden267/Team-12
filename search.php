<?php
session_start();
require_once 'db_connect.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
$search = "%$q%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    header("Location: indproduct.php?id=" . $row['product_id']);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1 style="padding:20px;">Search results for: <?= htmlspecialchars($q) ?></h1>

<div class="container">
<?php if ($result->num_rows === 0): ?>
    <p>No products found.</p>

<?php else: ?>
    <div class="product-grid">
        <?php while ($p = $result->fetch_assoc()): ?>
            <article class="product-card">
                <a href="indproduct.php?id=<?= $p['product_id'] ?>">
                    <img src="<?= htmlspecialchars($p['image_url']) ?>" class="product-img">
                </a>
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p>£<?= number_format($p['base_price'], 2) ?></p>
            </article>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
</div>

</body>
</html>
