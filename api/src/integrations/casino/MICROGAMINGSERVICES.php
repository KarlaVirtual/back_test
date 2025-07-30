<?php

/**
 * Clase para la integración con los servicios de Microgaming.
 *
 * Proporciona métodos para interactuar con la API de Microgaming, incluyendo
 * la gestión de juegos, colas de transacciones y validaciones manuales.
 *
 * @category   Integración
 * @package    API
 * @subpackage Microgaming
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;
use \SoapClient;

/**
 * Clase que proporciona métodos para la integración con los servicios de Microgaming.
 */
class MICROGAMINGSERVICES
{
    /**
     * Método actual que se está ejecutando.
     *
     * @var string
     */
    private $method;

    /**
     * Nombre de usuario para la autenticación en la API de Microgaming.
     *
     * @var string
     */
    private $username = "PeasyUATcom";

    /**
     * Contraseña para la autenticación en la API de Microgaming.
     *
     * @var string
     */
    private $password = "p34sYu8TD0Tc0m";

    /**
     * URL de desarrollo para la integración con Microgaming.
     *
     * @var string
     */
    private $URLDEV = 'https://redirector32.valueactive.eu/Casino/Default.aspx?applicationid=4023&serverid=24030&variant=UAT';

    /**
     * URL SOAP de desarrollo para la integración con Microgaming.
     *
     * @var string
     */
    private $SOAPURLDEV = 'https://orionapi32.gameassists.co.uk/orion/vanguardadmin/SOAP2';

    /**
     * URL de producción para la integración con Microgaming.
     *
     * @var string
     */
    private $URL = 'https://redirector3.valueactive.eu/Casino/Default.aspx?applicationid=4023&serverid=25396&variant=MIT';

    /**
     * URL de producción para juegos EETII.
     *
     * @var string
     */
    private $URLETI = 'https://redirector3.valueactive.eu/Casino/Default.aspx?applicationid=7217&serverid=25396&variant=MIT';

    /**
     * URL SOAP de producción para la integración con Microgaming.
     *
     * @var string
     */
    private $SOAPURL = 'https://orionapi32.gameassists.co.uk/orion/vanguardadmin/SOAP2';

    /**
     * Constructor de la clase.
     *
     * Configura las URLs de desarrollo o producción según el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->SOAPURL = $this->SOAPURL;
        } else {
        }
    }

    /**
     * Obtiene la URL de un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si el juego es en modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta con la URL del juego.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $usumandanteId = "")
    {
        try {
            if ($play_for_fun) {
                $array = array(
                    "error" => false,
                    "response" => $this->URL . "&authtoken=FUNFUNFUN&gameid=" . $gameid . "&ul=" . strtolower($lang),
                );

                return json_decode(json_encode($array));
            } else {
                $Proveedor = new Proveedor("", "MGMG");

                if ($usumandanteId == "") {
                    $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');

                    $usumandanteId = $UsuarioTokenSite->getUsuarioId();
                }

                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                    $UsuarioToken->setToken($UsuarioToken->createToken());
                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->update($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } catch (Exception $e) {
                    if ($e->getCode() == 21) {
                        $UsuarioToken = new UsuarioToken();
                        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                        $UsuarioToken->setCookie('0');
                        $UsuarioToken->setRequestId('0');
                        $UsuarioToken->setUsucreaId(0);
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsuarioId($usumandanteId);
                        $UsuarioToken->setToken($UsuarioToken->createToken());
                        $UsuarioToken->setSaldo(0);
                        $UsuarioToken->setProductoId(0);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }


                if (strpos($gameid, "EETII") !== false) {
                    $array = array(
                        "error" => false,
                        "response" => $this->URLETI
                            . "&authtoken=" . $UsuarioToken->getToken() . '&' . http_build_query(json_decode((str_replace("EETII", "", $gameid)), true)) . "&playmode=real&ul=" . strtolower($lang) . "",
                    );
                } else {
                    $array = array(
                        "error" => false,
                        "response" => $this->URL . "&authtoken=" . $UsuarioToken->getToken() . "&gameid=" . str_replace("MGS_", "", $gameid) . "&ul=" . strtolower($lang),
                    );
                }


                return json_decode(json_encode($array));
            }
        } catch (Exception $e) {
        }
    }


    /**
     * Obtiene la cola de transacciones pendientes de reversión.
     *
     * @return mixed Respuesta de la validación manual.
     */
    public function getQueueRollback()
    {
        $this->method = "GetRollbackQueueData";

        $array = array();

        $response = $this->MICROGAMINGRequest($array, 1);

        print_r($response);

        $data = simplexml_load_string(str_replace("a:", "", str_replace("s:", "", $response)));

        $string = "";

        foreach ($data->Body->GetRollbackQueueDataResponse->GetRollbackQueueDataResult->QueueDataResponse as $item) {
            $amount = (($item->ChangeAmount) / 100);
            $gamereference = $item->GameName;
            $gameid = $item->TransactionNumber;
            $actionid = $item->MgsReferenceNumber;
            $token = (string)$item->LoginName;


            $datos = array(
                "seq" => "",
                "playtype" => "",
                "token" => $token,
                "gameid" => $gameid,
                "gamereference" => $gamereference,
                "actionid" => $actionid,
                "actiondesc" => "",
                "amount" => $amount,
                "start" => false,
                "finish" => false,
                "offline" => "true"
            );
            $Microgaming = new Microgaming("", $token);

            $response = ($Microgaming->RollbackOrionDebit($gamereference, $gameid, "", $amount, $actionid, json_encode($datos)));

            $string = $string . "
            <ori:ValidteBetRequest>
               <ori:ExternalReference>R$actionid</ori:ExternalReference> <!-- The reference assigned by your system for use in audits, where applicable -->
               <ori:RowId>$item->RowId</ori:RowId> <!-- The unique identifier for the transaction being reconciled. Returned from GetRollbackQueueData or GetCommitQueueData -->
               <ori:ServerId>24030</ori:ServerId> <!-- The unique identifier of the brand in the Microgaming system -->
               <ori:UnlockType>RollbackQueue</ori:UnlockType> <!-- Either RollbackQueue or CommitQueue depending on what is being cleared -->
               <ori:UserId>$item->UserId</ori:UserId> <!-- The userid for the transaction being reconciled. Returned from GetRollbackQueueData or GetCommitQueueData -->
            </ori:ValidteBetRequest>";

            print_r($response);
        }

        $responsevalidate = $this->setManuallyValidateBet($string);

        return ($responsevalidate);
    }

    /**
     * Obtiene la cola de transacciones pendientes de confirmación.
     *
     * @return mixed Respuesta de la validación manual.
     */
    public function getQueueCommit()
    {
        $this->method = "GetCommitQueueData";

        $array = array();

        $response = $this->MICROGAMINGRequest($array, 1);
        print_r($response);

        $data = simplexml_load_string(str_replace("a:", "", str_replace("s:", "", $response)));


        $string = "";

        foreach ($data->Body->GetCommitQueueDataResponse->GetCommitQueueDataResult->QueueDataResponse as $item) {
            $amount = (($item->ChangeAmount) / 100);
            $gamereference = $item->GameName;
            $gameid = $item->TransactionNumber;
            $actionid = $item->MgsReferenceNumber;
            $token = (string)$item->LoginName;


            $datos = array(
                "seq" => "",
                "playtype" => "",
                "token" => $token,
                "gameid" => $gameid,
                "gamereference" => $gamereference,
                "actionid" => $actionid,
                "actiondesc" => "",
                "amount" => $amount,
                "start" => false,
                "finish" => false,
                "offline" => true
            );
            $Microgaming = new Microgaming("", $token, "rgil", "rgip");

            $response = ($Microgaming->CreditOrion($gamereference, $gameid, "", $amount, $actionid, true, json_encode($datos)));


            $string = $string . "
            <ori:ValidteBetRequest>
               <ori:ExternalReference>R$actionid</ori:ExternalReference> <!-- The reference assigned by your system for use in audits, where applicable -->
               <ori:RowId>$item->RowId</ori:RowId> <!-- The unique identifier for the transaction being reconciled. Returned from GetRollbackQueueData or GetCommitQueueData -->
               <ori:ServerId>24030</ori:ServerId> <!-- The unique identifier of the brand in the Microgaming system -->
               <ori:UnlockType>CommitQueue</ori:UnlockType> <!-- Either RollbackQueue or CommitQueue depending on what is being cleared -->
               <ori:UserId>$item->UserId</ori:UserId> <!-- The userid for the transaction being reconciled. Returned from GetRollbackQueueData or GetCommitQueueData -->
            </ori:ValidteBetRequest>";


            print_r($response);
        }

        $responsevalidate = $this->setManuallyValidateBet($string);


        return ($responsevalidate);
    }

    /**
     * Obtiene la cola de juegos fallidos pendientes de finalización.
     *
     * @return mixed Respuesta de la validación manual.
     */
    public function getQueueEndGame()
    {
        $this->method = "GetFailedEndGameQueue";

        $array = array();

        $response = $this->MICROGAMINGRequest($array, 1);

        print_r($response);

        $data = simplexml_load_string(str_replace("a:", "", str_replace("s:", "", $response)));


        $string = "";

        foreach ($data->Body->GetFailedEndGameQueueResponse->GetFailedEndGameQueueResult->GetFailedGamesResponse as $item) {
            $string = $string . "
            <ori:CompleteGameRequest>
               <ori:RowId>$item->RowId</ori:RowId> <!-- The unique identifier for the transaction being reconciled.  -->
               <ori:ServerId>24030</ori:ServerId> <!-- The unique identifier of the brand in the Microgaming system -->
            </ori:CompleteGameRequest>";


            print_r($response);
        }

        $responsevalidate = $this->setManuallyCompleteGame($string);


        return ($responsevalidate);

        return ($response);
    }


    /**
     * Valida manualmente una apuesta.
     *
     * @param string $string Datos de la apuesta a validar.
     *
     * @return mixed Respuesta de la validación.
     */
    public function setManuallyValidateBet($string)
    {
        $this->method = "ManuallyValidateBet";

        $array = array();

        $response = $this->MICROGAMINGRequest($array, 1, $string);

        return ($response);
    }

    /**
     * Completa manualmente un juego.
     *
     * @param string $string Datos del juego a completar.
     *
     * @return mixed Respuesta de la operación.
     */
    public function setManuallyCompleteGame($string)
    {
        $this->method = "ManuallyCompleteGame";

        $array = array();

        $response = $this->MICROGAMINGRequest($array, 1, $string);

        return ($response);
    }

    /**
     * Asigna juegos gratuitos a un jugador.
     *
     * @return mixed Respuesta de la operación.
     */
    public function setFreeGames()
    {
        $this->method = "AddPlayersToFreegame";

        $array = array();


        $response = $this->MICROGAMINGRequest($array, 1);

        return ($response);
    }

    /**
     * Genera una clave única para un jugador.
     *
     * @param string $player Identificador del jugador.
     *
     * @return string Clave generada.
     */
    function generateKey($player)
    {
        $hash = md5($player . md5("TMP" . $player));
        $hash = substr($hash, 0, 12);
        return ($hash);
    }

    /**
     * Realiza una solicitud a la API de Microgaming.
     *
     * @param array   $array_tmp Datos de la solicitud.
     * @param integer $requestid ID de la solicitud.
     * @param string  $string    Datos adicionales para la solicitud.
     *
     * @return mixed Respuesta de la API.
     */
    public function MICROGAMINGRequest($array_tmp, $requestid, $string)
    {
        $soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adm="http://mgsops.net/AdminAPI_Admin" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
   <soapenv:Header/>
   <soapenv:Body>
      <adm:' . $this->method . '>
         <adm:serverIds>
            <arr:int>24030</arr:int>
         </adm:serverIds>
      </adm:' . $this->method . '>
   </soapenv:Body>
</soapenv:Envelope>';

        if ($this->method == "ManuallyValidateBet") {
            $soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adm="http://mgsops.net/AdminAPI_Admin" xmlns:ori="http://schemas.datacontract.org/2004/07/Orion.Contracts.VanguardAdmin.DataStructures">
   <soapenv:Header/>
   <soapenv:Body>
      <adm:ManuallyValidateBet>
         <adm:validateRequests>
            ' . $string . '
         </adm:validateRequests>
      </adm:ManuallyValidateBet>
   </soapenv:Body>
</soapenv:Envelope>';
        }

        if ($this->method == "ManuallyCompleteGame") {
            $soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adm="http://mgsops.net/AdminAPI_Admin" xmlns:ori="http://schemas.datacontract.org/2004/07/Orion.Contracts.VanguardAdmin.DataStructures">
   <soapenv:Header/>
   <soapenv:Body>
      <adm:ManuallyCompleteGame>
         <adm:requests>
            ' . $string . '
         </adm:requests>
      </adm:ManuallyCompleteGame>
   </soapenv:Body>
</soapenv:Envelope>';

            print_r($soap_request);
        }
        $header = array(
            "Content-Type: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://mgsops.net/AdminAPI_Admin/IVanguardAdmin2/" . $this->method,
            "Content-length: " . strlen($soap_request),
            "Authorization: Basic " . base64_encode($this->username . ":" . $this->password) . "",
            "request-id: " . trim(getGUID(), '{}')


        );

        if ($this->method == "AddPlayersToFreegame") {
            $header[3] = "SOAPAction: http://mgsops.net/AdminAPI_Freegame/IFreegameAdmin/AddPlayersToFreegame";
            $this->SOAPURL = "https://orionapi32.gameassists.co.uk/Orion/freegameAdmin/SOAP";
            $soap_request = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:adm="http://mgsops.net/AdminAPI_Freegame" xmlns:ori="http://schemas.datacontract.org/2004/07/Orion.Contracts.FreegameAdmin.DataStructures">
   <soapenv:Header/>
   <soapenv:Body>
      <adm:AddPlayersToFreegame>
         <adm:request>
            <ori:PlayerActions>
               <!--Zero or more repetitions:-->
               <ori:PlayerAction>
                  <ori:ISOCurrencyCode>isocurrencycode</ori:ISOCurrencyCode> <!-- ISO Currency code of players account. -->
                  <ori:LoginName>Usuario175</ori:LoginName> <!-- Players login name. -->
                  <ori:PlayerStartDate>2018-03-23</ori:PlayerStartDate> <!-- This indicates when the player can start playing the free games. -->
                  <ori:InstanceId>1</ori:InstanceId> <!-- Identifies the instance of the offer for the player. -->
                  <ori:OfferId>8082</ori:OfferId> <!-- The unique identifier of the free games offer in the Microgaming system. -->
                  <ori:Sequence>9ebbb8fa-313e-4be4-9507-2e0b24a34731</ori:Sequence> <!-- The identifier of the request that is included in the response.  -->
               </ori:PlayerAction>
            </ori:PlayerActions>
            <ori:ServerId>24030</ori:ServerId> <!-- The unique identifier of the brand in the Microgaming system. -->
         </adm:request>
      </adm:AddPlayersToFreegame>
   </soapenv:Body>
</soapenv:Envelope>
            ';
            print_r($header);
            print_r($soap_request);
        }


        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $this->SOAPURL);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $soap_request);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($soap_do);
        $airings = array();
        $info = curl_getinfo($soap_do);

        return ($result);
    }


}

/**
 * Genera un identificador único global (GUID).
 *
 * @return string GUID generado.
 */
function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . chr(125);// "}"
        return $uuid;
    }
}
