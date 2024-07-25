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

$postData = json_decode(file_get_contents("php://input"), true);
$username = $postData['username'];
$password = $postData['password'];

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare and execute the SQL query to get the hashed password
    $sql = "SELECT account_id, account_name, account_username, account_password, account_type, account_email FROM account WHERE account_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['account_password'])) {
            // Password is correct, prepare user data
            $user = array(
                'account_id' => $row['account_id'],
                'account_name' => $row['account_name'],
                'account_username' => $row['account_username'],
                'account_type' => $row['account_type'],
                'account_email' => $row['account_email']
            );

            $response['status'] = 'success';
            $response['message'] = 'Login successful';
            $response['user'] = $user;
        } else {
            // Password is incorrect
            $response['status'] = 'error';
            $response['message'] = 'Invalid username or password';
        }
    } else {
        // No account found with that username
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
