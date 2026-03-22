<?php
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;}
    if (isset($_POST['product_id'])) {
        $user_id = $_SESSION['user_id'];
        $product_id = intval($_POST['product_id']);
        $stmt = $conn->prepare("DELETE f FROM favourites f JOIN product_variants pv ON f.variant_id = pv.variant_id WHERE f.user_id = ? AND pv.product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);}}?>