<?php 
include 'header.php'; 

$products = mysqli_query($conn, "SELECT products.*, categories.name as category_name, product_images.image_url FROM products LEFT JOIN categories ON products.category_id = categories.category_id LEFT JOIN product_images ON products.product_id = product_images.product_id");
?>

<main>
	<div style="display: flex; justify-content: space-between; align-items: center;">
		<h1>Products</h1>
		<a href="product-add.php" class="btn btn-primary">Add Product</a>
	</div>
    <div class="table-data">
				<div class="order">
					<table id="productsTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Category</th>
								<th>Name</th>
								<th>Price</th>
								<th>Image</th>
								<th>Description</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php while($row = mysqli_fetch_assoc($products)): ?>
								<tr>
									<td><?php echo $row['product_id']; ?></td>
									<td><?php echo $row['category_name']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['base_price']; ?></td>
									<td><img src="../<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" style="width: 100px; height: 100px;"></td>
									<td><?php echo substr($row['description'], 0, 50); ?>...</td>
									<td>
										<a href="product-variant.php?id=<?php echo $row['product_id']; ?>" class="btn btn-primary">Variants</a>
										<a href="product-edit.php?id=<?php echo $row['product_id']; ?>" class="btn btn-primary">Edit</a>
										<a href="product-delete.php?id=<?php echo $row['product_id']; ?>" class="btn btn-danger">Delete</a>

									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
				
			</div>
</main>

<script>
	$(document).ready(function() {
		new DataTable('#productsTable');
	});
</script>

<?php include 'footer.php'; ?>