<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventplan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT COUNT(*) AS total_items FROM `items`";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $total_items = $row['total_items'];
} else {
    echo "Error: " . $conn->error;
}
$conn->close();