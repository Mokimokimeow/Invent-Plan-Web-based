<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventplan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}
function parseQuantity($input) {
    $unitRegex = '/(\d+(\.\d+)?)([a-zA-Z]+)/';
    if (preg_match($unitRegex, $input, $matches)) {
        $value = floatval($matches[1]);
        $unit = strtolower($matches[3]);

        switch ($unit) {
            case 'kilo':
            case 'kg':
                return $value * 1000;
            case 'ml':
                return $value;
            default:
                return $value;
        }
    } else {
        return floatval($input);
    }
}
$itemCode = $_POST['editItemCode'];
$itemName = $_POST['editItemName'];
$itemExpiration = $_POST['editItemExpiration'];
$itemQuantity = parseQuantity($_POST['editItemQuantity']);
$itemPrice = floatval($_POST['editItemPrice']);

$sql_check = "SELECT * FROM `items` WHERE code = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $itemCode);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Item code not found."]);
    $stmt_check->close();
    $conn->close();
    exit();
}
$stmt_check->close();

$sql = "UPDATE `items` SET name=?, expiration=?, quantity=?, price=? WHERE code=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdss", $itemName, $itemExpiration, $itemQuantity, $itemPrice, $itemCode);

$response = [];
if ($stmt->execute()) {
    $response = ["success" => true, "message" => "Record updated successfully"];
} else {
    $response = ["success" => false, "message" => "Error updating record: " . $stmt->error];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);