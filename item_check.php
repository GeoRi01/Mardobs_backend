<?php
header("Content-Type: application/json; charset=UTF-8");

// Database connection
$servername = "34.143.244.112";
$username = "dobal";
$password = "dobal2024";
$dbname = "mardobs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get order ID from POST request
$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'];

// Query to get the order's items
$sql = "SELECT orders_items FROM orders WHERE orders_id = '$order_id'";
$result = $conn->query($sql);

$order_items = [];
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $order_items_json = $row['orders_items'];
  $order_items = json_decode($order_items_json, true);
}

$conn->close();

// Format response with item statuses
$response = [];
if (!empty($order_items)) {
  foreach ($order_items as $item) {
    $response[] = [
      "item_id" => $item['item_id'],
      "item_status" => $item['item_status']
    ];
  }
}

echo json_encode(["order_items" => $response]);
?>
