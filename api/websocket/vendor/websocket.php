<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Session\SessionProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Ratchet\App;
use Symfony\Component\HttpFoundation\Session\Session;
use Ratchet\Wamp\WampServerInterface;
use Ratchet\Server\EchoServer;


// Make sure composer dependencies have been installed
require __DIR__ . '/vendor/autoload.php';

/**
 * chat.php
 * Send any incoming messages to all connected clients (except sender)
 */

$loop   = React\EventLoop\Factory::create();

$EchoServer = new EchoServer();

$memcache = new Memcache;
$memcache->connect('0.0.0.0', 11211);

$session = new SessionProvider(
    new Ratchet\WebSocket\WsServer(($EchoServer))
    , new Handler\MemcacheSessionHandler($memcache)
);

$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PUSH);
$pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
$pull->on('message', array($EchoServer, 'onMensajeUsuario'));

$pull2 = $context->getSocket(ZMQ::SOCKET_REP);
$pull2->bind('tcp://127.0.0.1:5552');
$pull2->on(
    'message',array($EchoServer, 'onMensajeUsuario2'));

//$pull2->recv();
$pull2->send('back');


//Run the server application through the WebSocket protocol on port 8080
//$app = new App('0.0.0.0', 8888,"0.0.0.0");

// Set up our WebSocket server for clients wanting real-time updates
$webSock = new React\Socket\Server('0.0.0.0:8888',$loop);
// Binding to 0.0.0.0 means remotes can connect
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        $session
    ),
    $webSock
);

$loop->run();


//$app->route('/', new Ratchet\Server\EchoServer,array('*'));
//$ioServer = \Ratchet\Server\IoServer::factory(new \Ratchet\Http\HttpServer($session), 8888);

//$app->route('/', $session);
//print_r($ioServer->run());
