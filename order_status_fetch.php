<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "mardobs-dobal.h.aivencloud.com";
$port = "11535";
$username = "avnadmin";
$password = "AVNS_Hjm0TT6t3h_VzkzIKO-";
$dbname = "mardobs";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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
