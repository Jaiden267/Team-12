<?php 
include 'header.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM product_variants WHERE product_id = $id";
	$result = mysqli_query($conn, $sql);
	$variant = mysqli_fetch_assoc($result);
}

if (isset($_POST['add_variant'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $attribute_value = mysqli_real_escape_string($conn, $_POST['attribute_value']);
    $additional_price = $_POST['additional_price'];
    $attr_sql = "SELECT attribute_id FROM product_variants WHERE product_id = '$id' LIMIT 1";
    $attr_result = mysqli_query($conn, $attr_sql);
    
    if (mysqli_num_rows($attr_result) > 0) {
        $attr_row = mysqli_fetch_assoc($attr_result);
        $attribute_id = $attr_row['attribute_id'];
    } else {
        mysqli_query($conn, "INSERT INTO product_attributes (attribute_name) VALUES ('Variants for Product $id')");
        $attribute_id = mysqli_insert_id($conn);
    }
    $sql = "INSERT INTO product_variants (product_id, attribute_value, additional_price, attribute_id) 
            VALUES ('$id', '$attribute_value', '$additional_price', '$attribute_id')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $variant_id = mysqli_insert_id($conn);
        mysqli_query($conn, "INSERT INTO stock (variant_id, quantity) VALUES ('$variant_id', 0)");
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
			<input type="number" id="additional_price" name="additional_price" required class="form-input" step="0.01">
		</div>
		<input type="submit" name="add_variant" value="Add Variant" class="btn btn-primary" style="margin-top: 20px;">
	</form>
</main>
<?php include 'footer.php'; ?>
