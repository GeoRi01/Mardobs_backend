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

$sql = "SELECT * FROM table_list";
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
