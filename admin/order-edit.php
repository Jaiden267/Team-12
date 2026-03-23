<?php 
include 'header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE order_id = $id";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);
}

if (isset($_POST['edit_order'])) {
    $id = $_POST['id'];
    $payment_status = $_POST['payment_status'];
    $sql = "UPDATE orders SET payment_status = '$payment_status' WHERE order_id = $id";
    $result = mysqli_query($conn, $sql);    
        if ($result) {
        echo "<script>alert('Order updated successfully'); window.location.href = 'order-view.php';</script>";
    } else {
        echo "<script>alert('Failed to update order'); window.location.href = 'order-edit.php?id=$id';</script>";
    }
}
?>

<main>
    <h1>Edit Order</h1>
    <form action="order-edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $order['order_id']; ?>">
        <div>
            <label for="payment_status" class="form-label">Payment Status</label>
            <select name="payment_status" id="payment_status" required class="form-input">
                <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="paid" <?php echo $order['payment_status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="failed" <?php echo $order['payment_status'] == 'failed' ? 'selected' : ''; ?>>Failed</option>
                <option value="shipped" <?php echo $order['payment_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
            </select>
        </div>
        <input type="submit" name="edit_order" value="Update Order" class="btn btn-primary" style="margin-top: 20px;">
    </form>
</main>

<?php include 'footer.php'; ?>