<?php
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$updateSuccess = true;

$query = "UPDATE products SET prod_stocks = prod_stocks - ? WHERE prod_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed']);
    exit;
}

foreach ($input as $item) {
    $prod_id = $item['prod_id'];
    $quantity = $item['quantity'];

    $stmt->bind_param('ii', $quantity, $prod_id);

    if (!$stmt->execute()) {
        $updateSuccess = false;
        break;
    }
}

if ($updateSuccess) {
    echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update stock']);
}

$stmt->close();
$conn->close();
?>
