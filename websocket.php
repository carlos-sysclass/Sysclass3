<?php
use Ratchet\Server\IoServer,
	Ratchet\Http\HttpServer,
	Ratchet\WebSocket\WsServer,
	Ratchet\Wamp\WampServer,
	Sysclass\Websockets\Chat;

ini_set("display_errors", "1");
//error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);

define("REAL_PATH", realpath(__DIR__ . "/"));

define("PLICOLIB_PATH", __DIR__ . "/../../../plicolib/current/");

define("APP_TYPE", "WEBSOCKET");

try {
	// REGISTER THE COMPOSER AUTOLOADER
	require __DIR__ . '/vendor/autoload.php';

    require_once("app/bootstrap/bootstrap.php");


    $app = new Chat();
	$app->setDI($di);

	$loop   = React\EventLoop\Factory::create();
    // Listen for the web server to make a ZeroMQ push after an ajax request
    /*
    $context = new React\ZMQ\Context($loop);
    $pull = $context->getSocket(ZMQ::SOCKET_PULL);
    $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
    $pull->on('message', array($pusher, 'onBlogEntry'));
	*/

    // Set up our WebSocket server for clients wanting real-time updates
    $webSock = new React\Socket\Server($loop);
    $webSock->listen(8080, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
    $webServer = new IoServer(
        new HttpServer(
            new WsServer(
                new WampServer(
                    $app
                )
            )
        ),
        $webSock
    );

    $loop->run();

} catch (\Exception $e) {
    var_dump($e);
    exit;
}
exit;