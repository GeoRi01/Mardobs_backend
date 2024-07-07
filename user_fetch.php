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

// Get POST data
$postData = json_decode(file_get_contents("php://input"), true);
$email = $postData['email'];
$password = $postData['password'];

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "SELECT id, email, username, password, type FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user = array(
            'id' => $row['id'],
            'email' => $row['email'],
            'username' => $row['username'],
            'password' => $row['password'],
            'type' => $row['type']
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
