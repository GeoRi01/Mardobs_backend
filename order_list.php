<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mardobs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderData = json_decode(file_get_contents("php://input"), true);
$orderID = uniqid();
$orderDate = date("Y-m-d H:i:s");
$products = $orderData['products'];
$totalAmount = $orderData['totalAmount'];
$tableName = $orderData['tableName'];
$orderItemsJson = json_encode($products);

$sql = "INSERT INTO orders (table_name, order_id, order_date, total_amount, order_items) VALUES ('$tableName', '$orderID', '$orderDate', '$totalAmount', '$orderItemsJson')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
