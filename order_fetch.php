<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mardobs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        $order_items = json_decode($row['order_items'], true);
        $row['quantity'] = count($order_items);
        $row['order_items'] = $order_items; 
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>
