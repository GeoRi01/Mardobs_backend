<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "34.143.244.112";
$username = "dobal";
$password = "dobal2024";
$dbname = "mardobs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM category";
$result = $conn->query($sql);

$categoryList = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categoryList[] = $row;
    }
}

echo json_encode($categoryList);

$conn->close();
?>
