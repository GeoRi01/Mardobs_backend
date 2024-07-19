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

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Check if data is received
if (!isset($data['order_id']) || !isset($data['item_id']) || !isset($data['status'])) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    $conn->close();
    exit();
}

$order_id = $data['order_id'];
$item_id = $data['item_id'];
$status = $data['status'];

// Retrieve the current order_items JSON
$sql = "SELECT orders_items FROM orders WHERE orders_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Order not found"]);
    $stmt->close();
    $conn->close();
    exit();
}

$order = $result->fetch_assoc();
$items = json_decode($order['orders_items'], true);

// Update the item status in the array
$found = false;
foreach ($items as &$item) {
    if ($item['item_id'] == $item_id) {
        $item['item_status'] = $status;
        $found = true;
        break;
    }
}

// If item was found and updated
if ($found) {
    // Encode updated items back to JSON
    $updated_items = json_encode($items);

    // Update the orders table with new items JSON
    $sql = "UPDATE orders SET orders_items = ? WHERE orders_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $updated_items, $order_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Item status updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Item not found in order"]);
}

$stmt->close();
$conn->close();
?>
