<?php 
include 'header.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM product_variants WHERE variant_id = $id";
	$result = mysqli_query($conn, $sql);
	$variant = mysqli_fetch_assoc($result);
}

if (isset($_POST['edit_variant'])) {
	$id = $_POST['id'];
	$attribute_value = $_POST['attribute_value'];
	$additional_price = $_POST['additional_price'];
	$sql = "UPDATE product_variants SET attribute_value = '$attribute_value', additional_price = '$additional_price' WHERE variant_id = $id";
	$result = mysqli_query($conn, $sql);    
	if ($result) {
		echo "<script>alert('Variant updated successfully'); window.location.href = 'product-variant.php?id=$id';</script>";
	} else {
		echo "<script>alert('Failed to update variant'); window.location.href = 'product-variant-edit.php?id=$id';</script>";
	}
}
?>

<main>
	<div style="display: flex; justify-content: space-between; align-items: center;">
		<h1>Edit Variant</h1>
		<a href="product-variant.php?id=<?php echo $variant['product_id']; ?>" class="btn btn-primary">Back to Variants</a>
	</div>
	<form action="product-variant-edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $variant['variant_id']; ?>">
        <div>
            <label for="attribute_value" class="form-label">Attribute Value</label>
            <input type="text" id="attribute_value" name="attribute_value" required class="form-input" value="<?php echo $variant['attribute_value']; ?>">
        </div>
        <div>
            <label for="additional_price" class="form-label">Additional Price</label>
            <input type="number" id="additional_price" name="additional_price" required class="form-input" value="<?php echo $variant['additional_price']; ?>">
        </div>
        <input type="submit" name="edit_variant" value="Update Variant" class="btn btn-primary" style="margin-top: 20px;">
    </form>
</main>