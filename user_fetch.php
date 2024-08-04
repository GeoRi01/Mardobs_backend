<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postData = json_decode(file_get_contents("php://input"), true);

    if (isset($postData['username']) && isset($postData['password'])) {
        $username = $postData['username'];
        $password = $postData['password'];

        $sql = "SELECT account_id, account_name, account_username, account_password, account_type, account_email FROM account WHERE account_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['account_password'])) {
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
                $response['status'] = 'error';
                $response['message'] = 'Invalid username or password';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid username or password';
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Username or password not provided';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request';
}

echo json_encode($response);

$conn->close();
?>
