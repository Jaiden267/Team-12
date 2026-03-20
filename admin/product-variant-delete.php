<?php 
include '../db_connect.php';
if (isset($_GET['id'])) {
	$id = $_GET['id'];
    $product_id = $_GET['product_id'];
	$sql = "DELETE FROM product_variants WHERE variant_id = $id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		echo "<script>alert('Variant deleted successfully'); window.location.href = 'product-variant.php?id=$product_id';</script>";
	} else {
		echo "<script>alert('Failed to delete variant'); window.location.href = 'product-variant.php?id=$product_id';</script>";
	}
}

?>