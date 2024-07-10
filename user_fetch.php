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

$postData = json_decode(file_get_contents("php://input"), true);
$username = $postData['username'];
$password = $postData['password'];

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "SELECT accounts_id, accounts_name, accounts_username, accounts_password, accounts_type FROM app_accounts WHERE accounts_username = ? AND accounts_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user = array(
            'accounts_id' => $row['accounts_id'],
            'accounts_name' => $row['accounts_name'],
            'accounts_username' => $row['accounts_username'],
            'accounts_password' => $row['accounts_password'],
            'accounts_type' => $row['accounts_type']
        );

        $response['status'] = 'success';
        $response['message'] = 'Login successful';
        $response['user'] = $user;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid username or password';
    }

    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request';
}

echo json_encode($response);

$conn->close();
?>
