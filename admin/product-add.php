<?php
include 'header.php';
$categories = mysqli_query($conn, 'SELECT * FROM categories');
?>

<main>
	<h1>Add Product</h1>
	<form action="product-add.php" method="post" enctype="multipart/form-data"  >
		<div>
			<label for="name" class="form-label">Name</label>
			<input type="text" id="name" name="name" required class="form-input">
		</div>
		<div>
			<label for="description" class="form-label">Description</label>
			<textarea name="description" id="description" required class="form-input"></textarea>
		</div>
		<div>
			<label for="price" class="form-label">Price</label>
			<input type="number" id="price" name="price" required class="form-input" step="0.01">
		</div>
		<div>
			<label for="category" class="form-label">Category</label>
			<select name="category" id="category" required class="form-input">
                <option value="">Select Category</option>
				<?php while ($category = mysqli_fetch_assoc($categories)): ?>
					<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div>
			<label for="image" class="form-label">Image</label>
			<input type="file" id="image" name="image" required class="form-image-input" accept="image/*" >
		</div>
		<input type="submit" name="add_product" value="Add Product" class="btn btn-primary" style="margin-top: 20px;">
	</form>
</main>

<?php include 'footer.php'; ?>

<?php

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    $sql = "INSERT INTO products (name, description, base_price, category_id) VALUES ('$name', '$description', '$price', '$category')";
    $result = mysqli_query($conn, $sql);
    $product_id = mysqli_insert_id($conn);
    if ($result) {
        if ($image['size'] > 0) {
            $image_name = time() . '_' . $image['name'];
            $image_path = '../assets/' . $image_name;
            move_uploaded_file($image['tmp_name'], $image_path);
            $sql = "INSERT INTO product_images (product_id, image_url) VALUES ('$product_id', '$image_path')";
            $result = mysqli_query($conn, $sql);
        }
        if ($result) {
            echo "<script>alert('Product added successfully'); window.location.href = 'product-vew.php';</script>";
        } else {
            echo "<script>alert('Failed to add product image'); window.location.href = 'product-add.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to add product'); window.location.href = 'product-add.php';</script>";
    }
}
?>