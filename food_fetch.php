<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$sql = "SELECT * FROM products WHERE prod_status != 'Not Available' AND prod_stocks > 0";
$result = $conn->query($sql);

$foodList = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foodList[] = $row;
    }
}

echo json_encode($foodList);

$conn->close();
?>
