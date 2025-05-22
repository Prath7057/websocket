<?php
$conn = new mysqli('localhost', 'root', '', 'websocket_demo');
if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}
$stmt = $conn->prepare("INSERT INTO chat_messages (sender, message) VALUES (?, ?)");

$sender = "TestUser";
$message = "Hello world";

$stmt->bind_param("ss", $sender, $message);
$stmt->execute();
