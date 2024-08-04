<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$sql = "SELECT * FROM tables";
$result = $conn->query($sql);

$tableList = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tableList[] = $row;
    }
}

echo json_encode($tableList);

$conn->close();
?>
