<?php 
include 'header.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM product_variants WHERE product_id = $id";
	$variants = mysqli_query($conn, $sql);
}
?>

<main>
	<div style="display: flex; justify-content: space-between; align-items: center;">
		<h1>Product Variants</h1>
        <div style="display: flex; gap: 10px;">
            <a href="product-vew.php" class="btn btn-primary">Back to Products</a>
            <a href="product-variant-add.php?id=<?php echo $id; ?>" class="btn btn-primary">Add Variant</a>
        </div>
	</div>
<div class="table-data">
    <div class="order">
        <table>
            <thead>
                <tr>
                    <th>Variant ID</th>
                    <th>Attribute Value</th>
                    <th>Additional Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($variants)): ?>
                    <tr>
                        <td><?php echo $row['variant_id']; ?></td>
                        <td><?php echo $row['attribute_value']; ?></td>
                        <td><?php echo $row['additional_price']; ?></td>
                        <td>
                            <a href="product-variant-edit.php?id=<?php echo $row['variant_id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="product-variant-delete.php?id=<?php echo $row['variant_id']; ?>&product_id=<?php echo $id; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</main>
<?php include 'footer.php'; ?>