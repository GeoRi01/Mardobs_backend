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

$sql = "SELECT * FROM app_tables";
$result = $conn->query($sql);

$tableList = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tableList[] = $row;
    }
}

echo json_encode($tableList);

$conn->close();
?>
