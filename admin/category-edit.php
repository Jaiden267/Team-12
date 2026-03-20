<?php 
include 'header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE category_id = $id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
}

if (isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql = "UPDATE categories SET name = '$name' WHERE category_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Category updated successfully'); window.location.href = 'category-view.php';</script>";
    } else {
        echo "<script>alert('Failed to update category'); window.location.href = 'category-edit.php?id=$id';</script>";
    }
}
?>

<main>
	<h1>Edit Category</h1>
	<form action="category-edit.php" method="post">
		<div>
			<label for="name" class="form-label">Name</label>
			<input type="text" id="name" name="name" required class="form-input" value="<?php echo $category['name']; ?>">
		</div>
        <input type="hidden" name="id" value="<?php echo $category['category_id']; ?>">
    <input type="submit" name="edit_category" value="Update Category" class="btn btn-primary" style="margin-top: 20px;">
        </form>
    </main>

<?php include 'footer.php'; ?>

