<?php

namespace Ratchet\Server;

use Backend\dto\ConfigurationEnvironment;
use DateTime;
use Psr\Http\Message\RequestInterface;
use Ratchet\Http\HttpServerInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Exception;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * A simple Ratchet application that will reply to all messages with the message it received
 */
class EchoServer2 implements MessageComponentInterface
{
    protected $clients = array();
    protected $subscribedTopics = array();
    protected $subscribedBetting = array();

    private $typeC="";

    public function __construct($type="")
    {
        $this->typeC=$type;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {

        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null)
    {
        $conn->Session->set('time_connect', time());


        $this->clients[$conn->resourceId] = $conn;

        print_r("onOpen2 " . $conn->resourceId);
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . '*MACHINE* ' . $conn->resourceId."' '#events-machine' > /dev/null & ");

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

        print_r(" ENTROO " );
        print_r("onMessage2 " . $msg);
    $msg = $this->decryptWithTSValidation($msg);
        print_r("onMessage3 " . $msg);

    if ($msg == "") {
        $msg = "{}";
    }
        syslog(LOG_WARNING, " MACHINE :" . ' - '." REQUEST :" .  $msg);

        print_r($msg);
    require_once(__DIR__ . '/../../../../api/api.php');

    $object = json_decode($msg, true);

    $object ['session'] = array();

    $object['session']['sid'] = $from->resourceId;
    $object['session']['usuario'] = $from->Session->get('usuario');
    $object['session']['logueado'] = $from->Session->get('logueado');
    $object['session']['mandante'] = $from->Session->get('mandante');
    $object['session']['typeC'] = $this->typeC;
    $object['session']['usuarioip'] = explode(",", $from->httpRequest->getHeader('X-Forwarded-For')[0])[0];

    $object = json_encode($object);

    $object = json_decode($object);


    /*$command = $object->command;

    switch ($command) {

        case "request_session":
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $object->rid;
            $response["data"] = array(
                "sid" => "12321",
                "ip" => "181.139.215.233",
                "skin" => "test",
                "data_source" => 5
            );
            break;
    }*/
    try {

        $response = resolverAPI($object);
        $cookies = $from->httpRequest->getHeader('Cookie');

    } catch (Exception $e) {
        print_r($e);
        throw $e;
    }


    //print_r("response ".$response );


    if ($response->code == 0) {


        switch ($object->command) {

            case "restore_login":

                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('inapp', json_decode($response)->data->in_app);
                $from->Session->set('typeC', $this->typeC);
                //print_r("restore");

                break;

            case "betshop-livecasino-login":

                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('logueado', true);
                $from->Session->set('inapp', json_decode($response)->data->in_app);
                $from->Session->set('typeC', $this->typeC);
                //print_r("restore");
                break;

            case "restore_login_site":

                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('logueado', true);
                $from->Session->set('inapp', json_decode($response)->data->in_app);
                $from->Session->set('typeC', $this->typeC);

                break;

            case "login":
                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('logueado', true);
                $from->Session->set('inapp', json_decode($response)->data->in_app);
                $from->Session->set('typeC', $this->typeC);

                break;

            case "request_session":

                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('mandante', $object->params->site_id);
                $from->Session->set('inapp', json_decode($response)->data->in_app);
                $from->Session->set('typeC', $this->typeC);

                break;


            case "facebook_login":

                $from->Session->set('usuario', json_decode($response)->data->user_id);
                $from->Session->set('logueado', true);
                $from->Session->set('inapp', json_decode($response)->data->in_app);
                $from->Session->set('typeC', $this->typeC);
                //print_r("restore");

                break;

            case "get":

                switch ($object->params->source) {

                    case "user":

                        break;

                    case "betting":
                        $encontro = false;
                        $cont = 0;
                        foreach ($this->subscribedBetting as $item) {

                            if ($item["subid"] == json_decode($response)->data->dataSub->subid) {
                                $encontro = true;
                                $item["t2"] = 2;

                                array_push($this->subscribedBetting[$cont]["clients"], $from->resourceId);


                            }
                            $cont = $cont + 1;
                        }

                        if (!$encontro) {
                            array_push($this->subscribedBetting, array(
                                "subid" => json_decode($response)->data->dataSub->subid,
                                "first" => json_decode($response)->data->dataSub->first,
                                "end" => json_decode($response)->data->dataSub->end,
                                "id" => json_decode($response)->data->dataSub->id,
                                "t" => 2,
                                "clients" => array($from->resourceId),
                                "time" => time()

                            ));
                        }

                        break;


                }
                break;

            case "unsubscribe":

                foreach ($this->subscribedBetting as $item) {

                    if ($item["subid"] == json_decode($response)->params->subid) {
                        $index = array_search($from->resourceId, $item["clients"]);
                        unset($item["clients"][$index]);

                    }

                }

                break;

        }

        $response = $this->encryptWithTSValidation($response);

        $from->send(($response));

    } else {
        $response = $this->encryptWithTSValidation($response);

        $from->send(($response));

    }


    }

    public function onClose(ConnectionInterface $conn)
    {
        require_once(__DIR__ . '/../../../../api/api.php');

        print_r("onClose " . $conn->resourceId);

        $object = array();

        $object['command'] = 'close-request';
        $object['resourceId'] = $conn->resourceId;

        $object = json_encode($object);

        $object = json_decode($object);

        $response = resolverAPI($object);

    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        print_r("Error" . $e->getMessage());
        $conn->close();
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onMensajeUsuario($msg)
    {
        $msg = $this->decryptWithTSValidation($msg);

        if($msg == ""){
            $msg="{}";
        }

        $object = json_decode($msg);

                // msg :
        /*        $object = json_decode($msg);

                foreach ($this->clients as $client) {
                    $client->send(json_encode($object));

                }*/


        // re-send the data to all the clients subscribed to that category
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r(PHP_EOL);
        print_r('object->sid');
        print_r($object->sid);

        if (!array_key_exists($object->sid, $this->clients)) {

            if ($object->sid == 0) {
                $first=$object->data->first;
                $end=$object->data->end;
                $ids=$object->data->ids;

/*                foreach ($this->clients as $client) {

                    if ($this->clients[0] !== $client) {*/
                        // The sender is not the receiver, send to each client connected


                            foreach ($this->subscribedBetting as $itemS) {
                                $paso=false;
                                $first2=$first;
                                $end2=$end;


                                if($itemS["end"] == $end && $itemS["first"] == $first) {
                                    $paso=true;
                                    $first2=$first;

                                }

                                if($first == "sport"){
                                    if($itemS["end"] == $end) {
                                        $first2=$itemS["first"];
                                        $paso=true;
                                    }
                                }

                                if($first == "region" && $itemS["first"] != "sport"){
                                    if($itemS["end"] == $end) {
                                        $first2=$itemS["first"];
                                        $paso=true;
                                    }
                                }

                                if($first == "competition" && $itemS["first"] != "sport" && $itemS["first"] != "region"){
                                    if($itemS["end"] == $end) {
                                        $first2=$itemS["first"];
                                        $paso=true;
                                    }
                                }

                                if($first == "game" && $itemS["first"] != "sport" && $itemS["first"] != "region" && $itemS["first"] != "competition"){
                                    if($itemS["end"] == $end) {
                                        $first2=$itemS["first"];
                                        $paso=true;
                                    }
                                }

                                if($first == "market" && $itemS["first"] != "sport" && $itemS["first"] != "region" && $itemS["first"] != "competition" && $itemS["first"] != "game"){
                                    if($itemS["end"] == $end) {
                                        $first2=$itemS["first"];
                                        $paso=true;
                                    }
                                }


                                if($paso) {

                                    $existe = false;

                                    foreach ($itemS["id"] as $item) {
                                        foreach ($ids as $id) {
                                            if ($id == $item) {
                                                $existe = true;
                                            }
                                        }
                                    }
                                    if ($existe) {

                                        $object2=$object->data->data;
                                        if($object2->sport != "" && $object2->sport != null && $object2->sport != undefined){
                                            if($first2 != "sport"){
                                                $object2=$object2->sport->{key($object2->sport)};
                                            }
                                        }


                                        if($object2->region != "" && $object2->region != null && $object2->region != undefined){
                                            if($first2 != "region"){
                                                $object2=$object2->region->{key($object2->region)};
                                            }
                                        }

                                        if($object2->competition != "" && $object2->competition != null && $object2->competition != undefined){
                                            if($first2 != "competition"){
                                                $object2=$object2->competition->{key($object2->competition)};
                                            }
                                        }

                                        if($object2->game != "" && $object2->game != null && $object2->game != undefined){
                                            if($first2 != "game"){
                                                $object2=$object2->game->{key($object2->game)};
                                            }
                                        }

                                        if($object2->market != "" && $object2->market != null && $object2->market != undefined){
                                            if($first2 != "market"){
                                                $object2=$object2->market->{key($object2->market)};
                                            }
                                        }

                                      /*


                                        if($object2->region != "" && $object2->region != null && $object2->region != undefined){
                                            if($first2 != "region"){
                                                $object2=$object2->region[0];
                                            }
                                        }

                                        if($object2->competition != "" && $object2->competition != null && $object2->competition != undefined){
                                            if($first2 != "competition"){
                                                $object2=$object2->competition[0];
                                            }
                                        }

                                        if($object2->game != "" && $object2->game != null && $object2->game != undefined){
                                            if($first2 != "game"){
                                                $object2=$object2->game[0];
                                            }
                                        }

                                        if($object2->market != "" && $object2->market != null && $object2->market != undefined){
                                            if($first2 != "market"){
                                                $object2=$object2->market[0];
                                            }
                                        }*/


                                        foreach ($itemS["clients"] as $itemS2) {
                                            //$client->send(json_encode($object));
                                            //$client->send(json_encode($itemS));


                                            $this->clients[$itemS2]->send($this->encryptWithTSValidation(json_encode(array(
                                                    "code" => 0,
                                                    "sid" => 0,
                                                    "rid" => 0,
                                                    "data" => array(
                                                        $itemS["subid"] => $object2
                                                    ))
                                            )));
                                        }
                                    }

                                }

                            }




                     /*   $terminar=true;
                        while($terminar){
                            $object2=$object->data->data->{$first};
                            foreach ($object2 as $message) {

                                foreach ($this->subscribedBetting as $itemS) {

                                    if($itemS["end"] == $end && $itemS["first"] == $first){
                                        foreach ($itemS["clients"] as $itemS2) {
                                            $itemS2->send(json_encode($object));
                                        }
                                    }

                                }

                            }
                            $terminar=false;
                            $client->send(json_encode($first));
                            $client->send(json_encode($object));
                            $client->send(json_encode($object2));

                        }

*/


            }

            return;
        }
        syslog(LOG_WARNING, " MACHINE :" . ' - '." REQUEST :" .  $msg);
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . '*MACHINE* ' . $msg."' '#events-machine' > /dev/null & ");

        return $this->clients[$object->sid]->send($this->encryptWithTSValidation(json_encode($object)));


    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onMensajeUsuario2($msg)
    {
       // exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . '*MACHINE2* ' . 'prueba'."' '#events-machine' > /dev/null & ");
        syslog(LOG_WARNING, " MACHINE :" . ' - '." REQUEST :" .  $msg);

        $object = json_decode($msg);

       // $this->clients[$object->sid]->send(json_encode($object));
        if (array_key_exists($object->sid, $this->clients)) {

             $this->clients[$object->sid]->send($this->encryptWithTSValidation(json_encode($object)));
            $msg = "OK";
        }else{
            $msg = "ERROR";

        }
        return $msg;
    }


    public function encrypt ($message, $method, $secret="vOVH6sdmpNWjTRIqCc1rdPs01lwHzfr3", &$hmac) {
        //$iv = substr(bin2hex(openssl_random_pseudo_bytes(16)),0,16);    //use this in production
        $iv = substr($secret, 0, 16);        //using this for testing purposes (to have the same encryption IV in PHP and Node encryptors)
        $encrypted = base64_encode($iv) . openssl_encrypt($message, $method, $secret, 0, $iv);
        $hmac = hash_hmac('md5', $encrypted, $secret);
        return $encrypted;
    }

    public function decrypt ($encrypted, $method, $secret="vOVH6sdmpNWjTRIqCc1rdPs01lwHzfr3", $hmac) {
        if (hash_hmac('md5', $encrypted, $secret) == $hmac) {
            $iv = base64_decode(substr($encrypted, 0, 24));
            return openssl_decrypt(substr($encrypted, 24), $method, $secret, 0, $iv);
        }
    }

    public function encryptWithTSValidation ($message, $method='AES-256-CBC', $secret="vOVH6sdmpNWjTRIqCc1rdPs01lwHzfr3", &$hmac="") {
       // date_default_timezone_set('America/Bogota');
        $message = substr(date('c'),0,19) . "$message";
        return $this->encrypt($message, $method, $secret, $hmac);
    }

    public function decryptWithTSValidation($encrypted, $method='AES-256-CBC', $secret="vOVH6sdmpNWjTRIqCc1rdPs01lwHzfr3", $hmac="") {
        $intervalThreshold=60*60*1;
        $hmac = hash_hmac('md5', $encrypted, $secret);
        $decrypted = $this->decrypt($encrypted, $method, $secret, $hmac);
        $now = new DateTime();
        $msgDate = new DateTime(str_replace("T"," ",substr($decrypted,0,19)));
        if (($now->getTimestamp() - $msgDate->getTimestamp()) <= $intervalThreshold) {
            return substr($decrypted,19);
        }
    }
}
