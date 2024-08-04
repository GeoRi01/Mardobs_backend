<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Manila');

include 'db_connection.php';

$orderData = json_decode(file_get_contents("php://input"), true);

if (!isset($orderData['tables_name']) || !isset($orderData['items']) || !isset($orderData['items_total']) || !isset($orderData['orders_status'])) {
    echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
    $conn->close();
    exit();
}

$orders_code = uniqid();
$orders_table = $orderData['tables_name'];
$items = $orderData['items'];
$orders_items = json_encode($items);
$orders_total = $orderData['items_total'];
$orders_date = date("Y-m-d H:i:s");
$orders_status = $orderData['orders_status'];

$sql = "INSERT INTO orders (orders_code, orders_table, orders_items, orders_total, orders_date, orders_status) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiss", $orders_code, $orders_table, $orders_items, $orders_total, $orders_date, $orders_status);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
