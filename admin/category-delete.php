<?php 
include '../db_connect.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM categories WHERE category_id = $id";
    $result = mysqli_query($conn, $sql);
    if($result){
        echo "<script>alert('Category deleted successfully'); window.location.href = 'category-view.php';</script>";
    }
}
?>