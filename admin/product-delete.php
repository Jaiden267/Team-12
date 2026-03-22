<?php
include '../db_connect.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM stock WHERE variant_id IN (SELECT variant_id FROM product_variants WHERE product_id = $id)");
    mysqli_query($conn, "DELETE FROM favourites WHERE variant_id IN (SELECT variant_id FROM product_variants WHERE product_id = $id)");
    mysqli_query($conn, "DELETE FROM basket_items WHERE variant_id IN (SELECT variant_id FROM product_variants WHERE product_id = $id)");
    mysqli_query($conn, "DELETE FROM product_variants WHERE product_id = $id");
    mysqli_query($conn, "DELETE FROM reviews WHERE product_id = $id");
    mysqli_query($conn, "DELETE FROM product_images WHERE product_id = $id");
    $sql = "DELETE FROM products WHERE product_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Product deleted successfully'); window.location.href = 'product-vew.php';</script>";
    } else {
        echo "<script>alert('Failed to delete product'); window.location.href = 'product-vew.php';</script>";
    }
}
?>