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
    $sql = "SELECT account_id, account_name, account_username, account_password, account_type, account_email FROM account WHERE account_username = ? AND account_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user = array(
            'account_id' => $row['account_id'],
            'account_name' => $row['account_name'],
            'account_username' => $row['account_username'],
            'account_password' => $row['account_password'],
            'account_type' => $row['account_type'],
            'account_email' => $row['account_email']
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
