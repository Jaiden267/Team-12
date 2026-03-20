<?php
include 'header.php';

$query = '
    SELECT 
        p.name AS product_name,
        p.base_price,
        pv.additional_price,
        pi.image_url AS image,
        pv.attribute_value AS variant,
        s.quantity AS stock,
        s.stock_id AS stock_id
    FROM products p
    JOIN product_variants pv 
        ON p.product_id = pv.product_id
    JOIN stock s 
        ON pv.variant_id = s.variant_id
    LEFT JOIN product_images pi 
        ON p.product_id = pi.product_id
    ORDER BY p.name, pv.attribute_value;
    ';
$stocks = mysqli_query($conn, $query);
if (!$stocks) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<main>
    <div class="table-data">
        <div class="order">
            <table id="stockTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Base Price</th>
                        <th>Additional Price</th>
                        <th>Image</th>
                        <th>Variant Name</th>
                        <th>Stock Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($stock = mysqli_fetch_assoc($stocks)): ?>
                        <tr>
                            <td><?php echo $stock['product_name']; ?></td>
                            <td><?php echo $stock['base_price']; ?></td>
                            <td><?php echo $stock['additional_price']; ?></td>
                            <td><img src="../<?php echo $stock['image']; ?>" alt="<?php echo $stock['product_name']; ?>" style="width: 60px; height: 50px;"></td>
                            <td><?php echo $stock['variant']; ?></td>
                            <td><?php echo $stock['stock']; ?></td>
                            <td>
                             <a href="stock-edit.php?id=<?php echo $stock['stock_id']; ?>&variant=<?php echo $stock['variant']; ?>&product=<?php echo $stock['product_name']; ?>" class="btn ">Edit</a>
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
		new DataTable('#stockTable');
	});
</script>

<?php include 'footer.php'; ?>