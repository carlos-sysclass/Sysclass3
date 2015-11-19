<?php
namespace Sysclass\Websockets;

use Phalcon\Mvc\User\Component,
    Ratchet\Wamp\WampServerInterface,
    Ratchet\ConnectionInterface,
    Sysclass\Models\Chat\Queue,
    Sysclass\Models\Users\UserTimes;

class Chat extends Component implements WampServerInterface
{
    protected $clients;
    protected $token;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        //$this->users[$conn->wrappedConn->WAMP->sessionId] = true;

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
        switch($topic->getId()) {
            case "authentication" : {
                if ($userTimes = UserTimes::findFirstBySessionId($params[1])) {
                    $user = $userTimes->getUser();
                    if ($user && !is_null($user->websocket_key) && $user->websocket_key == $params[0]) {
                        // RETURN ERROR
                        //var_dump('SUCCESS');
                        // CREATE THE TOKEN FOR SESSION
                        /*
                        $this->token = $this->security->computeHmac(
                            $user->login . "-" . microtime(true),
                            $user->websocket_key . $this->environment->websocket->hash,
                            'sha256'
                        );
                        */
                        
                        $this->users[$conn->wrappedConn->WAMP->sessionId] = array(
                            'id' => $user->id,
                            'login' => $user->login,
                            'name' => $user->name,
                            'surname' => $user->surname
                        );

                        $lastQueue = Queue::findFirst(array(
                            'conditions' => 'requester_id = ?0 AND closed = 0',
                            'bind' => array($user->id),
                            'order' => 'ping DESC'
                        ));
                        if ($lastQueue) {
                           var_dump($lastQueue->toArray());
                        }
                        //$this->token = $conn->wrappedConn->WAMP->sessionId;
                        $userTimes->websocket_token = $conn->wrappedConn->WAMP->sessionId;
                        //$userTimes->websocket_token = $conn->wrappedConn->WAMP->sessionId;
                        $userTimes->save();

                        $conn->callResult($id, array(
                            'token' => $userTimes->websocket_token
                        ));
                        return true;
                    }
                }

                $conn->callError($id, $topic, "User not logged In");
                return true;
                break;
            }
            case "getMyQueues" : {
                // ALLOWS THE USER TO VIEW AND PROBABLY RECOVER IS QUEUES
                // CAN BE CALLED AFTER THE CONNECTION, 
                if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
                    $conn->close();
                    return true;
                }
                $lastQueue = Queue::findFirst(array(
                    'conditions' => 'requester_id = ?0 AND closed = 0',
                    'bind' => array($user->id),
                    'order' => 'ping DESC'
                ));
                if ($lastQueue) {
                   var_dump($lastQueue->toArray());
                }
                break;
            }
            case "getUnassignedQueues" : {
                // ALLOWS THE SUPPORT USER TO LIST ALL UNANSWERED QUEUES
                break;
            }
            case "assignQueue" : {
                // ALLOWS THE SUPPORT USER TO CATCH A QUEUE TO HIM, OR ANOTHER USER
            }
            case "startQueue" : {
                if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
                    $conn->close();
                    return true;
                }
                $queue = $params[0];
                $new_topic = $queue . "-" . \Phalcon\Text::random(RANDOM_HEXDEC, 16);

                $started = time();
                $subject = $this->translate->translate($params[1]);

                $queueModel = new Queue();

                $user = $this->users[$conn->wrappedConn->WAMP->sessionId];

                $queueModel->websocket_token = $conn->wrappedConn->WAMP->sessionId;
                $queueModel->subject = $subject;
                $queueModel->topic = $new_topic;
                $queueModel->requester_id = $user['id'];
                $queueModel->started = $started;
                $queueModel->ping = $started;

                $queueModel->save();

                var_dump($queueModel->getMessages());

                //$new_topic = "GLOBALCHAT";

                $conn->callResult($id, array(
                    'topic' => $new_topic,
                    'title' => $subject
                ));
                return true;
                break;
            }
        }
        //var_dump("CALL", $id, $topic->getId(), $params);
        $conn->callError($id, $topic, 'Please especify a valid procedure method');
        return true;
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
            $conn->close();
            return true;
        }
        $event['origin'] = $conn->wrappedConn->WAMP->sessionId;
        $event['from'] = $this->users[$conn->wrappedConn->WAMP->sessionId];

        $queueModel = Queue::findFirstByWebsocketToken($conn->wrappedConn->WAMP->sessionId);
        $queueModel->ping = time();
        $queueModel->save();

        // SAVE THE MESSAGE ON QUEUE

        $topic->broadcast($event, $exclude, $eligible);
    }
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
            $conn->close();
            return true;
        }
        $this->subscribedTopics[$topic->getId()] = $topic;

    }
    public function onUnsubscribe(ConnectionInterface $conn, $topic) {
        //$this->subscribedTopics[$topic->getId()] = $topic;
    }
}
