<?php
include 'header.php';

if (isset($_GET['id']) && isset($_GET['variant']) && isset($_GET['product'])) {
    $stock_id = $_GET['id'];
    $variant = $_GET['variant'];
    $product = $_GET['product'];
    $stock = mysqli_query($conn, "SELECT * FROM stock WHERE stock_id = $stock_id");
    $stock = mysqli_fetch_assoc($stock);
}

if (isset($_POST['edit_stock'])) {
    $quantity = $_POST['quantity'];
    $stock_id = $_POST['stock_id'];
    $sql = "UPDATE stock SET quantity = $quantity WHERE stock_id = $stock_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Stock updated successfully'); window.location.href = 'stock-view.php';</script>";
    }
}
?>

<main>
	<h1>Edit Stock for <?php echo $product; ?> - <?php echo $variant; ?></h1>
	<form action="stock-edit.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="stock_id" value="<?php echo $stock_id; ?>">
		<div>
			<label for="quantity" class="form-label">Quantity</label>
			<input type="number" name="quantity" id="quantity" class="form-input" value="<?php echo $stock['quantity']; ?>">   
		</div>
		<input type="submit" name="edit_stock" value="Update Stock" class="btn" style="margin-top: 20px;">
		
	</form>
</main>


<?php include 'footer.php'; ?>