<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mardobs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the orders table
$sql = "SELECT order_id, order_date, table_name, total_amount, order_items FROM orders";
$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $order_items = json_decode($row['order_items'], true);
        $row['quantity'] = count($order_items);
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>
