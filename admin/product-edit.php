<?php
include 'header.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM products WHERE product_id = $id";
	$result = mysqli_query($conn, $sql);
	$product = mysqli_fetch_assoc($result);
	$categories = mysqli_query($conn, 'SELECT * FROM categories');
}

if (isset($_POST['edit_product'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$description = $_POST['description'];
	$price = $_POST['price'];
	$category = $_POST['category'];
	$image = $_FILES['image'];

	$description = mysqli_real_escape_string($conn, $description);
	$sql = "UPDATE products SET name = '$name', description = '$description', base_price = '$price', category_id = '$category' WHERE product_id = $id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		if ($image['size'] > 0) {
			$image_name = time() . '_' . $image['name'];
			$image_path = '../assets/' . $image_name;
			move_uploaded_file($image['tmp_name'], $image_path);
			$sql = "UPDATE product_images SET image_url = '$image_path' WHERE product_id = $id";
			$result = mysqli_query($conn, $sql);
			if (!$result) {
				echo "<script>alert('Failed to update product image'); window.location.href = 'product-edit.php?id=$id';</script>";
			}
		}
		echo "<script>alert('Product updated successfully'); window.location.href = 'product-vew.php';</script>";
	}
}
?>

<main>
	<h1>Edit Product</h1>
	<form action="product-edit.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
		<div>
			<label for="name" class="form-label">Name</label>
			<input type="text" id="name" name="name" required class="form-input" value="<?php echo $product['name']; ?>">
		</div>

		<div>
			<label for="description" class="form-label">Description</label>
			<textarea name="description" id="description" required class="form-input"><?php echo $product['description']; ?></textarea>
		</div>
		<div>
			<label for="price" class="form-label">Price</label>
			<input type="number" id="price" name="price" required class="form-input" value="<?php echo $product['base_price']; ?>">
		</div>
		<div>
			<label for="category" class="form-label">Category</label>
			<select name="category" id="category" required class="form-input">
				<option value="">Select Category</option>
				<?php while ($category = mysqli_fetch_assoc($categories)): ?>
					<option value="<?php echo $category['category_id']; ?>" <?php echo $product['category_id'] == $category['category_id'] ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div>
			<label for="image" class="form-label">Image</label>
			<input type="file" id="image" name="image"  class="form-image-input" accept="image/*">
		</div>
		<input type="submit" name="edit_product" value="Update Product" class="btn btn-primary" style="margin-top: 20px;">
	</form>
</main>


<?php include 'footer.php'; ?>
