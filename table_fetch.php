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

$sql = "SELECT * FROM tables";
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
