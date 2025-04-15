<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'websocket_demo');
if ($conn->connect_error) {
    die(json_encode(['error' => $conn->connect_error]));
}

$sql = "SELECT sender, message, created_at FROM chat_messages ORDER BY id DESC LIMIT 30";
$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(array_reverse($messages));
