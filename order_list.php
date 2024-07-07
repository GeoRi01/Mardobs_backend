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
$tableName = $orderData['tableName'];
$products = $orderData['products'];
$orderItemsJson = json_encode($products);
$totalAmount = $orderData['totalAmount'];
$orderDate = date("Y-m-d H:i:s");
$orderStatus = $orderData['orderStatus'];


$sql = "INSERT INTO orders (order_id, table_name, order_items, total_amount, order_date, order_status) VALUES ( '$orderID', '$tableName', '$orderItemsJson','$totalAmount', '$orderDate', '$orderStatus')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
