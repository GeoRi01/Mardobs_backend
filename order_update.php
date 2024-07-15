<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "34.143.244.112";
$username = "dobal";
$password = "dobal2024";
$dbname = "mardobs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $orders_id = isset($input['order_id']) ? intval($input['order_id']) : 0;
    $orders_status = isset($input['status']) ? $input['status'] : '';

    if ($orders_id > 0 && !empty($orders_status)) {
        $sql = "UPDATE orders SET orders_status = ? WHERE orders_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $orders_status, $orders_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Order status updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update order status."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid order ID or status."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
