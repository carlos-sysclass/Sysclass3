<?php
namespace Sysclass\Sockets;

use Phalcon\Mvc\User\Component,
    Ratchet\Wamp\WampServerInterface,
    Ratchet\ConnectionInterface,
    Sysclass\Models\Chat\Chat,
    Sysclass\Models\Chat\Message,
    Sysclass\Models\Users\UserTimes;

class Queue extends Component implements WampServerInterface
{
    protected $clients;
    protected $token;
    protected $usersIds;
    protected $users;

    protected $subscribedTopics = array();

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = array();
        $this->usersIds = array(); 
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        //$this->users[$conn->wrappedConn->WAMP->sessionId] = true;

        echo "New connection! (#{$conn->resourceId})\n";
    }

    protected function createTaskEvent($data, $type, $result = null) {
        return array(
            'event' => $type . "ServiceExecution",
            'channel' => $data['channel'],
            'service' => $data['data']['service'],
            'method' => $data['data']['method'],
            'result' => $result
        );
    }

    public function onEvent($data) {
        $info = json_decode($data, true);
        if (array_key_exists("channel", $info)) {
            switch($info['channel']) {
                case "task" : {
                    $topic = false;

                    if (array_key_exists("system-events", $this->subscribedTopics)) {
                        $topic = $this->subscribedTopics["system-events"];
                    }

                    if ($topic) {
                        $topic->broadcast($this->createTaskEvent($info, "before"));
                    }

                    $result = $this->callTask($info['data']);

                    if ($topic) {
                        $topic->broadcast($this->createTaskEvent($info, "after", $result));
                    }
                    

                    break;
                }
            }
        } else {
            echo "NO CHANNEL FOUND";
        }
        // RECEIVE A JSON ENCODED STRING, 

    }

    public function callTask($metadata) {
        $di = \Phalcon\DI::getDefault();

        if ($di->has($metadata['service'])) {
            $service = $di->get($metadata['service']);
            
            $result = call_user_func_array(array(
                $service, $metadata['method']
            ), $metadata['args']);

            // MAKE A WAY TO SEND THE RESULST TO THE WEBSOCKET

            return $result;
        } else {
        }
    }




    /* WEBSOCKET CHAT FUNCTIONS */
    /*
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
    */
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        if (array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
            $user = $this->users[$conn->wrappedConn->WAMP->sessionId];
            unset($this->users[$conn->wrappedConn->WAMP->sessionId]);
            unset($this->usersIds[$user['id']]);
        }

        $this->clients->detach($conn);

        echo "Connection Closed: #{$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
    
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        echo "FUNCTION CALL: {$topic->getId()} (#{$conn->resourceId})\n";
        switch($topic->getId()) {
            case "authentication" : {
                if ($userTimes = UserTimes::findFirst([
                        'conditions' => 'session_id = ?0 AND expired = 0',
                        'bind' => [$params[1]]
                    ])) {
                    $user = $userTimes->getUser();
                    if ($user && !is_null($user->websocket_key) && $user->websocket_key == $params[0]) {
                        //var_dump($userTimes->toArray());

                        $lastPing = UserTimes:: maximum([
                            'column' => 'started',
                            'conditions' => 'user_id = ?0 AND id <> ?1',
                            'bind' => [$user->id, $userTimes->id]
                        ]);


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
                            'surname' => $user->surname,
                            'last_ping' => $lastPing,
                        );
                        $this->usersIds[$user->id] = $conn->wrappedConn->WAMP->sessionId;

                        /*
                        $lastQueue = Queue::findFirst(array(
                            'conditions' => 'requester_id = ?0 AND closed = 0',
                            'bind' => array($user->id),
                            'order' => 'ping DESC'
                        ));
                        if ($lastQueue) {
                           var_dump($lastQueue->toArray());
                        }
                        */
                        //$this->token = $conn->wrappedConn->WAMP->sessionId;
                        $userTimes->websocket_token = $conn->wrappedConn->WAMP->sessionId;
                        //$userTimes->websocket_started = time();
                        //$userTimes->websocket_token = $conn->wrappedConn->WAMP->sessionId;
                        $userTimes->save();

                        $conn->callResult($id, array(
                            'token' => $userTimes->websocket_token,
                            'started' => $userTimes->started
                        ));

                        return true;
                    }
                }

                $conn->callError($id, $topic, "User not logged In");
                return true;
                break;
            }
            /*
            case "getMyQueues" : {

                // ALLOWS THE USER TO VIEW AND PROBABLY RECOVER IS QUEUES
                // CAN BE CALLED AFTER THE CONNECTION, 
                if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
                    $conn->close();
                    return true;
                }
                $user = $this->users[$conn->wrappedConn->WAMP->sessionId];

                $lastQueue = Chat::findFirst(array(
                    'conditions' => "requester_id = ?0 AND closed = 0 AND type = ?1",
                    'bind' => array($user['id'], 'queue'),
                    'order' => 'ping DESC'
                ));

                if ($lastQueue) {
                   //var_dump($lastQueue->toArray());
                }
                break;
            }
            */
            case "getQueues" : {
                // ALLOWS THE SUPPORT USER TO LIST ALL UNANSWERED QUEUES
                if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
                    $conn->close();
                    return true;
                }
                $user = $this->users[$conn->wrappedConn->WAMP->sessionId];

                if ($this->acl->isUserAllowed($user, "Chat", "View")) {
                    $chatList = Chat::find(array(
                        //'columns' => "closed, id, ping, requester_id, started, subject, topic, type",
                        //'conditions' => "",
                        //'bind' => array('queue'),
                        'order' => 'ping ASC'
                    ));

                    $result = array();

                    foreach($chatList as $chat) {
                        $item = $chat->toArray();
                        unset($item['websocket_token']);
                        $requester = $chat->getRequester();
                        $item['requester'] = $requester->toArray();

                        if (array_key_exists($requester->id, $this->usersIds)) {
                            $item['online'] = true;
                            $item['session_id'] = $this->usersIds[$requester->id];
                        } else {
                            $item['online'] = false;
                        }
                        var_dump($user['last_ping']);
                        
                        $chat_messages = $chat->getChatMessages([
                            'conditions' => 'sent > ?0',
                            'bind' => [$user['last_ping']],
                            'order' => 'sent DESC'
                        ]);
                        $item['new_count'] = 0;
                        foreach($chat_messages as $message) {
                            if ($message->user_id == $user['id']) {
                                break;
                            }
                            $item['new_count']++;
                        }

                        //$chat_messages

                        $result[] = $item;
                    }


                    $conn->callResult($id, $result);
                } else {
                    $conn->callError($id, $topic, "401: Unauthorized");
                }

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

                $user = $this->users[$conn->wrappedConn->WAMP->sessionId];
                $queue = $params[0];
                $started = time();
                $subject = $this->translate->translate($params[1]);

                // CHECK IF EXISTS A UNCLOSED QUEUE
                $queueModel = Chat::findFirst(array(
                    'conditions' => 'subject = ?0 AND requester_id = ?1 
                        AND topic LIKE ?2 AND closed = 0',
                    'bind' => array($subject, $user['id'], $queue . '%'),
                    'order' => 'ping DESC'
                ));

                if ($queueModel) {
                    $queueModel->ping = $started;
                    $queueModel->save();

                    $new_topic = $queueModel->topic;
                } else {
                   
                    $new_topic = $queue . "-" . \Phalcon\Text::random(RANDOM_HEXDEC, 16);

                    $queueModel = new Chat();
                    $queueModel->websocket_token = $conn->wrappedConn->WAMP->sessionId;
                    $queueModel->type = "queue";
                    $queueModel->subject = $subject;
                    $queueModel->topic = $new_topic;
                    $queueModel->requester_id = $user['id'];
                    $queueModel->started = $started;
                    $queueModel->ping = $started;

                    $queueModel->save();

                }

                echo "startQueue Topic: {$new_topic}\n";

                $conn->callResult($id, $queueModel->toArray());
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

        $event = $this->createResponse($conn, $topic, $event);
        //$event['type'] = "message";
        
        $user = $this->users[$conn->wrappedConn->WAMP->sessionId];
        /*
        $event['origin'] = $conn->wrappedConn->WAMP->sessionId;
        $event['from'] = $user;
        */
        $queueModel = Chat::findFirst(array(
            'conditions' => 'topic = ?0 AND closed = 0',
            'bind' => array($topic->getId())
        ));

        if (!$queueModel) {
            $conn->close();
            return true;
        }
        $queueModel->ping = time();
        $queueModel->save();

        // SAVE THE MESSAGE ON QUEUE
        $messageModel = new Message();
        $messageModel->chat_id = $queueModel->id;
        $messageModel->message = $event['message'];
        $messageModel->sent = $event['sent'];
        $messageModel->user_id = $user['id'];
        $messageModel->save();

        //var_dump($messageModel->getMessages());

        $topic->broadcast($event, $exclude, $eligible);
    }
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        if (!array_key_exists($conn->wrappedConn->WAMP->sessionId, $this->users)) {
            $conn->close();
            return true;
        }
        $user = $this->users[$conn->wrappedConn->WAMP->sessionId];

        $this->subscribedTopics[$topic->getId()] = $topic;

        $event = $this->createResponse($conn, $topic);
        $event['type'] = "info";
        $event['message'] = $this->translate->translate("You entered the chat");

        $topic->broadcast($event, array(), array($conn->wrappedConn->WAMP->sessionId));
    }

    public function onUnsubscribe(ConnectionInterface $conn, $topic) {
        unset($this->subscribedTopics[$topic->getId()]);
    }

    protected function createResponse(ConnectionInterface $conn, $topic, array $event = null) {
        if (is_null($event)) {
            $event = array();
        }

        $user = $this->users[$conn->wrappedConn->WAMP->sessionId];
        $event['origin'] = $conn->wrappedConn->WAMP->sessionId;
        $event['from'] = $user;
        $event['topic'] = $topic->getId();
        $event['sent'] = time();

        return $event;

    }
}
