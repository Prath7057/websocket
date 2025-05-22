<?php
// Connect to your database
$host = 'localhost';
$dbname = 'websocket_demo';
$user = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'];
    $message = $_POST['message'];

    // Save message to database
    $sql = "INSERT INTO chat_messages (sender, message) VALUES (:sender, :message)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(':sender', $sender);
    $stmt->bindParam(':message', $message);
    
    $stmt->execute();

    echo "Message sent";
}
?>
