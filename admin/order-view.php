<?php 
include 'header.php';

$orders = mysqli_query($conn, 'SELECT * FROM orders');
?>

<main>
    <h1>Orders</h1>
    <div class="table-data">
        <div class="order">
            <table id="ordersTable">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Addres 1</th>
                <th>Total</th>
                <th>Payment Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['full_name']; ?></td>
                    <td><?php echo $order['email']; ?></td>
                    <td><?php echo $order['phone']; ?></td>
                    <td><?php echo $order['address1']; ?></td>
                    <td><?php echo $order['total']; ?></td>
                    <td><?php echo $order['payment_status']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($order['created_at'])) ?? 'N/A'; ?></td>
                    <td>
                        <a href="order-details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-primary">View</a>
                        <a href="order-edit.php?id=<?php echo $order['order_id']; ?>" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<script>
	$(document).ready(function() {
		new DataTable('#ordersTable');
	});
</script>

<?php include 'footer.php'; ?>