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
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $sql = " DELETE FROM `items` WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        }
        else {
            echo json_encode(array("success" => false, "error" => "Failed to delete item: " . $conn->error));
        }
        $stmt->close();
    }
    else {
        echo json_encode(array("success" => false, "error" => "ID parameter not provided"));
    }
} else {
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}
$conn->close();