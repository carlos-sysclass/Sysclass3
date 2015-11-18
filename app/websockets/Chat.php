<?php
namespace Sysclass\Websockets;

use Phalcon\Mvc\User\Component,
    Ratchet\Wamp\WampServerInterface,
    Ratchet\ConnectionInterface;

class Chat extends Component implements WampServerInterface
{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
    
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        var_dump($id, $topic);
        //$conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {

        var_dump($conn, $topic, $event, $exclude, $eligible);

        $topic->broadcast($event, $exclude, $eligible);
        // In this application if clients send data it's because the user hacked around in console
        //$conn->close();
    }
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        var_dump("SUBSCRIBE", $conn, $topic, $event, $exclude, $eligible);
        $this->subscribedTopics[$topic->getId()] = $topic;

    }
    public function onUnsubscribe(ConnectionInterface $conn, $topic) {
        //$this->subscribedTopics[$topic->getId()] = $topic;
    }
}
