<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $db;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->db = new mysqli('localhost', 'root', '', 'websocket_demo');
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        if (strpos($msg, ": ") !== false) {
            list($sender, $message) = explode(": ", $msg, 2);
            $stmt = $this->db->prepare("INSERT INTO chat_messages (sender, message) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $sender, $message);
                $stmt->execute();
                $stmt->close();
            }
        }

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8888,
    '0.0.0.0'
);

$server->run();
