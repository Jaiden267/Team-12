<?php
include 'header.php';
?>

<main>
	<h1>Add Category</h1>
	<form action="category-add.php" method="post">
		<div>
			<label for="name" class="form-label">Name</label>
			<input type="text" id="name" name="name" required class="form-input">
		</div>  
    <input type="submit" name="add_category" value="Add Category" class="btn btn-primary" style="margin-top: 20px;">
    </form>
</main>

<?php include 'footer.php'; ?>

<?php
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $sql = "INSERT INTO categories (name) VALUES ('$name')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Category added successfully'); window.location.href = 'category-view.php';</script>";
    } else {
        echo "<script>alert('Failed to add category'); window.location.href = 'category-add.php';</script>";
    }
}
?>