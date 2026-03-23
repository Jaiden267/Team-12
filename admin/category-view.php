<?php
include 'header.php';

$categories = mysqli_query($conn, 'SELECT * FROM categories');
?>

<main>
	<div style="display: flex; justify-content: space-between; align-items: center;">
		<h1>Categories</h1>
		<a href="category-add.php" class="btn btn-primary">Add Category</a>
	</div>
	<div class="table-data">
		<div class="order">
			<table id="categoriesTable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php while ($category = mysqli_fetch_assoc($categories)): ?>
				<tr>
					<td><?php echo $category['category_id']; ?></td>
					<td><?php echo $category['name']; ?></td>
					<td>
						<a href="category-edit.php?id=<?php echo $category['category_id']; ?>" class="btn btn-primary">Edit</a>
							<a href="category-delete.php?id=<?php echo $category['category_id']; ?>" class="btn btn-danger">Delete</a>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</main>

<script>
	$(document).ready(function() {
		new DataTable('#categoriesTable');
	});
</script>

<?php include 'footer.php'; ?>