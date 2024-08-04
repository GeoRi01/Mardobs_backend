<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

$orders = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_items = json_decode($row['orders_items'], true);
        $row['item_quantity'] = count($order_items);
        $row['orders_items'] = $order_items;
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>
