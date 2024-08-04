<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'];

$sql = "SELECT orders_items FROM orders WHERE orders_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$order_items = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $order_items_json = $row['orders_items'];
    $order_items = json_decode($order_items_json, true);
}

$conn->close();

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
