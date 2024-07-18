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

$data = json_decode(file_get_contents("php://input"), true);

$tables_name = $data['tables_name'];
$new_items = $data['items'];
$new_items_total = $data['items_total'];

$response = array();

// Check for existing order
$sql = "SELECT * FROM orders WHERE orders_table = '$tables_name' AND orders_status != 'Completed'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Existing order found, update it
    $existing_order = $result->fetch_assoc();
    $existing_items = json_decode($existing_order['orders_items'], true);
    $existing_items_total = $existing_order['orders_total'];

    // Combine old and new items
    $combined_items = array_merge($existing_items, $new_items);
    $combined_items_total = $existing_items_total + $new_items_total;

    $updated_items_json = json_encode($combined_items);

    $update_sql = "UPDATE orders SET orders_items = '$updated_items_json', orders_total = $combined_items_total WHERE orders_id = {$existing_order['orders_id']}";

    if ($conn->query($update_sql) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Order updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update order';
    }
} else {
    // No existing order, insert new one
    $new_items_json = json_encode($new_items);
    $insert_sql = "INSERT INTO orders (orders_table, orders_items, orders_total, orders_date, orders_status) VALUES ('$tables_name', '$new_items_json', $new_items_total, NOW(), 'Pending')";

    if ($conn->query($insert_sql) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Order placed successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to place order';
    }
}

echo json_encode($response);

$conn->close();
?>
