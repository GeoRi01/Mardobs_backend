<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$sql = "SELECT orders_table, orders_status FROM orders WHERE orders_status != 'Completed'";

$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>
