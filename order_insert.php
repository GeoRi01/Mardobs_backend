<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Manila');

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

$orderData = json_decode(file_get_contents("php://input"), true);

$orders_code = uniqid();
$orders_table = $orderData['tables_name'];
$items = $orderData['items'];
$orders_items = json_encode($items);
$orders_total = $orderData['items_total'];
$orders_date = date("Y-m-d H:i:s");
$orders_status = $orderData['orders_status'];

$sql = "INSERT INTO orders (orders_code, orders_table, orders_items, orders_total, orders_date, orders_status) VALUES ( '$orders_code', '$orders_table', '$orders_items','$orders_total', '$orders_date', '$orders_status')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
