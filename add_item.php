<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventplan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemCode = $_POST['code'];
    $itemName = $_POST['name'];
    $itemExpiration = $_POST['expiration'];
    $itemQuantity = $_POST['quantity'];
    $itemPrice = $_POST['price'];

    $checkQuery = "SELECT * FROM `items` WHERE code = '$itemCode'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $response = array("success" => false, "message" => "Item with the same code already exists.");
    } else {
        $sql = "INSERT INTO `items` (`code`, `name`, `expiration`, `quantity`, `price`)
        VALUES ('$itemCode', '$itemName', '$itemExpiration','$itemQuantity', '$itemPrice')";

        if ($conn->query($sql) === TRUE) {
            $response = array("success" => true, "message" => "Item added successfully.");
        } else {
            $response = array("success" => false, "message" => "Failed to add item. Please try again.");
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}