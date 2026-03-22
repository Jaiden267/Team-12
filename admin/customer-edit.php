<?php
include 'header.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id = $id";
    $result = mysqli_query($conn, $sql);
    $customer = mysqli_fetch_assoc($result);
}

if (isset($_POST['edit_customer'])) {
    $id = intval($_POST['id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone' WHERE user_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Customer details updated successfully'); window.location.href = 'customer-view.php';</script>";
    }
}
if (isset($_POST['delete_customer'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM addresses WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM basket_items WHERE basket_id IN (SELECT basket_id FROM baskets WHERE user_id = $id)");
    mysqli_query($conn, "DELETE FROM baskets WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM reviews WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM staff_profiles WHERE user_id = $id");
    $sql = "DELETE FROM users WHERE user_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Customer deleted successfully'); window.location.href = 'customer-view.php';</script>";
    }
}
?>

<main>
    <h1>Edit Customer</h1>
    <form action="customer-edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $customer['user_id']; ?>">
        <div>
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" required class="form-input" value="<?php echo $customer['first_name']; ?>">
        </div>
        <div>
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" required class="form-input" value="<?php echo $customer['last_name']; ?>">
        </div>
        <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" readonly required class="form-input" value="<?php echo $customer['email']; ?>">
        </div>
        <div>
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" id="phone" name="phone" required class="form-input" value="<?php echo $customer['phone']; ?>">
        </div>
        <input type="submit" name="edit_customer" value="Update Customer" class="btn btn-primary" style="margin-top: 20px;">
        <input type="submit" name="delete_customer" value="Delete Customer" class="btn btn-primary" style="margin-top: 20px;" formnovalidate>
    </form>
</main>

<?php include 'footer.php'; ?>