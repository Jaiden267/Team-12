<?php 
include 'header.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM product_variants WHERE product_id = $id";
	$result = mysqli_query($conn, $sql);
	$variant = mysqli_fetch_assoc($result);
}

if (isset($_POST['add_variant'])) {
	$id = $_POST['id'];
	$attribute_value = $_POST['attribute_value'];
	$additional_price = $_POST['additional_price'];
	$sql = "INSERT INTO product_variants (product_id, attribute_value, additional_price, attribute_id) VALUES ('$id', '$attribute_value', '$additional_price', '$id')";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		echo "<script>alert('Variant added successfully'); window.location.href = 'product-variant.php?id=$id';</script>";
	} else {
		echo "<script>alert('Failed to add variant'); window.location.href = 'product-variant-add.php?id=$id';</script>";
	}
}
?>

<main>
	<h1>Add Variant</h1>
	<form action="product-variant-add.php" method="post">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<div>
			<label for="attribute_value" class="form-label">Attribute Value</label>
			<input type="text" id="attribute_value" name="attribute_value" required class="form-input">
		</div>
		<div>
			<label for="additional_price" class="form-label">Additional Price</label>
			<input type="number" id="additional_price" name="additional_price" required class="form-input">
		</div>
		<input type="submit" name="add_variant" value="Add Variant" class="btn btn-primary" style="margin-top: 20px;">
	</form>
</main>
<?php include 'footer.php'; ?>
