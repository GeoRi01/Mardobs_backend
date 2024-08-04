<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

date_default_timezone_set("Asia/Manila"); 

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['tables_name']) || !isset($data['items']) || !isset($data['items_total'])) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    $conn->close();
    exit();
}

$tables_name = $data['tables_name'];
$new_items = $data['items'];
$new_items_total = $data['items_total'];

$response = array();
$orders_code = uniqid();
$orders_date = date("Y-m-d H:i:s");

$sql = "SELECT orders_id, orders_items, orders_total FROM orders WHERE orders_table = ? AND orders_status != 'Completed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tables_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $existing_order = $result->fetch_assoc();
    $existing_items = json_decode($existing_order['orders_items'], true);
    $existing_items_total = $existing_order['orders_total'];

    $combined_items = array_merge($existing_items, $new_items);
    $combined_items_total = $existing_items_total + $new_items_total;
    $updated_items_json = json_encode($combined_items);

    $update_sql = "UPDATE orders SET orders_items = ?, orders_total = ?, orders_status = 'Pending' WHERE orders_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sdi", $updated_items_json, $combined_items_total, $existing_order['orders_id']);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Order updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update order';
    }
} else {
    $new_items_json = json_encode($new_items);

    $insert_sql = "INSERT INTO orders (orders_code, orders_table, orders_items, orders_total, orders_date, orders_status) VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssis", $orders_code, $tables_name, $new_items_json, $new_items_total, $orders_date);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Order placed successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to place order';
    }
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
