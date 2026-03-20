<?php

include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM product_images WHERE product_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM products WHERE product_id = $id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Product deleted successfully'); window.location.href = 'product-vew.php';</script>";
        } else {
            echo "<script>alert('Failed to delete product'); window.location.href = 'product-vew.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to delete product image'); window.location.href = 'product-vew.php';</script>";
    }
}
?>