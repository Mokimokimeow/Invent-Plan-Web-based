<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventplan";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM `admin` WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error executing query: ' . mysqli_error($conn)));
    }
}
mysqli_close($conn);