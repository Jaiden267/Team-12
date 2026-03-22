<?php
include 'header.php';

// fetch with product name and customer name
$ratings = mysqli_query($conn, 'SELECT reviews.*, products.name as product_name, users.first_name as customer_name FROM reviews LEFT JOIN products ON reviews.product_id = products.product_id LEFT JOIN users ON reviews.user_id = users.user_id');
?>

<main>
    <h1>Reviews</h1>
    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Product Name</th>
                        <th>Customer Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rating = mysqli_fetch_assoc($ratings)): ?>
                        <tr>
                            <td><?php echo $rating['rating']; ?></td>
                            <td><?php echo $rating['comment']; ?></td>
                            <td><?php echo $rating['product_name']; ?></td>
                            <td><?php echo $rating['customer_name']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>