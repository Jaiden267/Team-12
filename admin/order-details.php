<?php
include 'header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE order_id = $id";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);
}

$order_items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $id");
?>

<main>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Order Details for Order ID: <?php echo $order['order_id']; ?></h1>
        <a href="order-view.php" class="btn btn-primary">Back to Orders</a>
    </div>
    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address 1</th>
                        <th>Address 2</th>
                        <th>City</th>
                        <th>Postcode</th>
                        <th>Country</th>
                        <th>Sub Total</th>
                        <th>Payment Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['full_name']; ?></td>
                        <td><?php echo $order['email']; ?></td>
                        <td><?php echo $order['phone']; ?></td>
                        <td><?php echo $order['address1']; ?></td>
                        <td><?php echo $order['address2'] ?? 'N/A'; ?></td>
                        <td><?php echo $order['city']; ?></td>
                        <td><?php echo $order['postcode'] ?? 'N/A'; ?></td>
                        <td><?php echo $order['country'] ?? 'N/A'; ?></td>
                        <td><?php echo $order['total'] ?? 'N/A'; ?></td>
                        <td><?php echo $order['payment_status'] ?? 'N/A'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['created_at'])) ?? 'N/A'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product SKU</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order_item = mysqli_fetch_assoc($order_items)): ?>
                        <tr>
                            <td><?php echo $order_item['product_name']; ?></td>
                            <td><?php echo $order_item['product_sku'] ?? 'N/A'; ?></td>
                            <td><?php echo $order_item['quantity'] ?? 'N/A'; ?></td>
                            <td><?php echo $order_item['price'] ?? 'N/A'; ?></td>
                            <td><?php echo $order_item['subtotal'] ?? 'N/A'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>



<?php include 'footer.php'; ?>