<?php
$servername = "localhost";        
$username   = "root";               // Changed from cs2team12
$password   = "";                   // Changed to empty for XAMPP
$database   = "cs2team12_db";     

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>