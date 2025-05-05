<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
        echo "New connection: {$conn->resourceId}\n";
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($from->httpRequest->getHeaders() as $header => $values) {
            echo "$header: " . implode(', ', $values) . "\n";
        }
        echo "Message: $msg\n";
        // foreach ($from->getAllConnections() as $client) {
        //     if ($client !== $from) {
        //         $client->send($msg);
        //     }
        // }
    }
    public function onClose(ConnectionInterface $conn) {
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8888
);
$server->run();
?>
