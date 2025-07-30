<?php
namespace Backend\websocket;
use Backend\dto\ConfigurationEnvironment;

use Backend\dto\UsuarioMandante;
use CurlWrapper;
use DateTime;
use Ratchet\Wamp\Exception;
use \ZMQContext;
use \ZMQ;
use ZMQSocket;
/** 
* Clase 'WebsocketUsuario'
* 
* Esta clase provee la manera de crear transacciones mediante 
* un websocket del servidor al usuario y viceversa
* 
* Ejemplo de uso: 
* $WebsocketUsuario = new WebsocketUsuario();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 17.10.17
* 
*/
class WebsocketUsuario
{

    /**
    * Representación del session id
    *
    * @var int
    */
    private $sid;

    /**
    * Representación de la data
    *
    * @var array
    */
    private $data;

    /**
    * Representación del host
    *
    * @var string
    */
    private $host = "0.0.0.0";

    /**
    * Representación del puerto
    *
    * @var string
    */
    private $port = "5555";

    /**
    * Constructor de clase
    *
    *
    * @param int $sid session id
    * @param array $data data
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($sid, $data)
    {
        $this->sid = $sid;
        $this->data = $data;
    }


    /**
    * Método para enviar un mensaje WS
    *
    * @throws no
    *
    * @access public
    */
    public function sendWSMessage($typeC="")
    {
        $array = array(
            "sid" => $this->sid,
            "rid" => 0,
            "code" => 0,
            "data" => $this->data
        );
        $messageToSend=json_encode($array);

        if($typeC==2){
            $this->port="5551";
            $messageToSend=$this->encryptWithTSValidation($messageToSend);



            try {


                // This is our new stuff
                $context = new ZMQContext(  );
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin3.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }
        }else {


            try {
                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin1.local:" . $this->port);


                $socket->send($messageToSend);
            } catch (Exception $e) {

            }

            try {
                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin2.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }


            try {


                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin3.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }


            try {


                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin4.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }


            try {


                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin5.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }

            try {


                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin6.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }

            try {


                // This is our new stuff
                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 2000);

                $socket->connect("tcp://admin7.local:" . $this->port);


                $array = array(
                    "sid" => $this->sid,
                    "rid" => 0,
                    "code" => 0,
                    "data" => $this->data
                );

                $socket->send($messageToSend);
            } catch (Exception $e) {

            }
        }
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

    /**
    * Método para eniar un mensaje WS y recibir información desde el socket
    *
    *
    * @return String $ información del socket
    *
    * @access public
    */
    public function sendWSMessageWithReturn()
    {

        // This is our new stuff
        $socket = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REQ);
        $socket->connect("tcp://127.0.0.1:5552");
        $array = $this->data;
        $socket->send(json_encode($array));

        return  $socket->recv();

    }

    public function sendWSPieSocket(UsuarioMandante $usuarioMandante,$dataSend,$enabled=false)
    {
        if($enabled){

            $clusterId='';

            switch($usuarioMandante->mandante){
                case '0':
                    $clusterId='s1840.nyc1';
                    $post_fields = [
                        "key" => "PUBtntUm8g2tuVArPEeFPrBAUnJU35IvAUqnY8iu", //Demo key,  get yours at https://piesocket.com
                        "secret" => "v9tbcepmY5SRZAVUF1pZovN8LsnJ9TGu", //Demo secret, get yours at https://piesocket.com
                        "roomId" => $usuarioMandante->getUsumandanteId(),
                        "message" => json_encode(array('data'=>$dataSend))
                    ];
                    break;
                case '2':
                    $clusterId='s1841.nyc1';

                    $post_fields = [
                        "key" => "8J7tkzXUheE5pLj3otNGeZhXZn4Mv8BLHsullzwA", //Demo key,  get yours at https://piesocket.com
                        "secret" => "YiqZnnV7SlKsKuo6kLymgA6JIj1kK7ZY", //Demo secret, get yours at https://piesocket.com
                        "roomId" => $usuarioMandante->getUsumandanteId(),
                        "message" => json_encode(array('data'=>$dataSend))
                    ];
                    break;
                default:
                    $clusterId='s1422.nyc5';

                    $post_fields = [
                        "key" => "PCF6TrpUtvqcT3diPiauQ0pJ9quueDx0MxwSbTST", //Demo key,  get yours at https://piesocket.com
                        "secret" => "hNephLX4f5ujR3pc60CBGBeOdbk1CuS3", //Demo secret, get yours at https://piesocket.com
                        "roomId" => $usuarioMandante->getUsumandanteId(),
                        "message" => json_encode(array('data'=>$dataSend))
                    ];
                    break;
            }
            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper("https://".$clusterId.".piesocket.com/api/publish");

            // Configurar opciones
            $curl->setOptionsArray(array(
                CURLOPT_URL => "https://".$clusterId.".piesocket.com/api/publish",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($post_fields),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            // Ejecutar la solicitud
            $response2 = $curl->execute();

            if($usuarioMandante->usuarioMandante==886){


            }
            try{

                if($response2 != '' && json_decode($response2)->success != 'true'){
                }

            }catch (Exception $e){

            }

        }

    }

    /**
     * Canal de comunicación establecido por mandante - pais
     * @param int $mandante identificador del partner
     * @param string $paisISO identificador del iso para el pais
     * @param array <mixed> $datasend datos a enviar por WS
     * @return void
     */
    public function sendWSPieSocketMandantePais(int $mandante, string $paisISO, array $dataSend)
    {


        $ConfigurationEnviroment = new ConfigurationEnvironment();

        if($ConfigurationEnviroment->isDevelopment()){
            $clusterId ='s14303.nyc5';
            $post_fields = [
                "key" => "ARe8rxQzsSe33Z9uIrajjB51nRJV7vywabZmEUZN",
                "secret" => "q22QKcGycs4RCuM1ZFwBKazvxvDU6sdv"
            ];
        }else{
            $clusterId ='s14266.nyc5';
            $post_fields = [
                "key" => "WtqIfpD8lK9sjhaPqEdzG4YzWVyfBpXQo6rnrV4k",
                "secret" => "EMmu76I8eSha4SW3zjyRIK5qpmwX85eo"
            ];
        }

        $post_fields['roomId'] = $mandante.'_'.strtolower($paisISO);
        $post_fields['message'] = json_encode($dataSend);

        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper("https://".$clusterId.".piesocket.com/api/publish");

        // Configurar opciones
        $curl->setOptionsArray(array(
            CURLOPT_URL => "https://".$clusterId.".piesocket.com/api/publish",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($post_fields),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $curl->execute();
    }
}
