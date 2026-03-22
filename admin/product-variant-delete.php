<?php 
include '../db_connect.php';
if (isset($_GET['id']) && isset($_GET['product_id'])) {
    $id = intval($_GET['id']);
    $product_id = intval($_GET['product_id']);
    mysqli_query($conn, "DELETE FROM stock WHERE variant_id = $id");
    mysqli_query($conn, "DELETE FROM favourites WHERE variant_id = $id");
    mysqli_query($conn, "DELETE FROM basket_items WHERE variant_id = $id");
    $sql = "DELETE FROM product_variants WHERE variant_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Variant deleted successfully'); window.location.href = 'product-variant.php?id=$product_id';</script>";
    } else {
        echo "<script>alert('Failed to delete variant'); window.location.href = 'product-variant.php?id=$product_id';</script>";
    }
}
?>