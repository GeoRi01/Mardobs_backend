<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$sql = "SELECT * FROM category";
$result = $conn->query($sql);

$categoryList = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryList[] = $row;
    }
}

echo json_encode($categoryList);

$conn->close();
?>
