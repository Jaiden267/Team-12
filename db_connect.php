<?php
$servername = "localhost";
$username   = "cs2team12";
$password   = "syAr3YckECMRCXZsK1wKPAJNh"; 
$database   = "cs2team12_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>