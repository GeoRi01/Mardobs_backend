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

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
    
$orders = array();

if ($result->num_rows > 0) {
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
