<?php
session_start();
require_once 'db_connect.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;}
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['variant_id'])){
        $user_id = $_SESSION['user_id'];
        $variant_id = intval($_POST['variant_id']);
        $check_stmt = $conn->prepare("SELECT favourite_id FROM favourites WHERE user_id = ? AND variant_id = ?");
        $check_stmt->bind_param("ii", $user_id, $variant_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            echo json_encode(['status' => 'exists']);
            exit;}
            $insert_stmt = $conn->prepare("INSERT INTO favourites (user_id, variant_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $user_id, $variant_id);
            if ($insert_stmt->execute()) {
                echo json_encode(['status' => 'success']);
                }else{
                    echo json_encode(['status' => 'error']);}
                    }else{
                        echo json_encode(['status' => 'error']);}
                        ?>


