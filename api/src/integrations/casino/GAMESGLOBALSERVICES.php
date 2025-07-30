<?php

/**
 * Clase GAMESGLOBALSERVICES
 *
 * Este archivo contiene la implementación de la clase `GAMESGLOBALSERVICES`, que proporciona
 * servicios relacionados con la integración de juegos globales. Incluye métodos para gestionar
 * juegos, realizar solicitudes SOAP, manejar tokens de usuario y configurar entornos de desarrollo
 * y producción.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\integrations\casino;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
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
use Firebase\JWT\JWT;
use \SoapClient;

/**
 * Clase que proporciona servicios relacionados con la integración de juegos globales.
 * Incluye métodos para gestionar juegos, realizar solicitudes SOAP, manejar tokens de usuario
 * y configurar entornos de desarrollo y producción.
 */
class GAMESGLOBALSERVICES
{
    /**
     * Método utilizado en las solicitudes SOAP.
     *
     * @var string
     */
    private $method;

    /**
     * Nombre de usuario para la autenticación básica.
     *
     * @var string
     */
    private $username = "PeasyUATcom";

    /**
     * Contraseña para la autenticación básica.
     *
     * @var string
     */
    private $password = "p34sYu8TD0Tc0m";

    /**
     * URL del entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://redirector32.valueactive.eu/Casino/Default.aspx?applicationid=4123&serverid=24030';

    /**
     * URL del entorno de producción.
     *
     * @var string
     */
    private $URLPROD = '';

    /**
     * Clave del nombre utilizada en las solicitudes.
     *
     * @var string
     */
    private $NameKey = '';

    /**
     * Clave del nombre para el entorno de desarrollo.
     *
     * @var string
     */
    private $NameKeyDev = 'VirtualSoftDEV';

    /**
     * Clave del nombre para el entorno de producción.
     *
     * @var string
     */
    private $NameKeyProd = '';

    /**
     * Clave API utilizada en las solicitudes.
     *
     * @var string
     */
    private $ApiKey = '';

    /**
     * Clave API para el entorno de desarrollo.
     *
     * @var string
     */
    private $ApiKeyDev = 'ae2025a9-3c2f-4c14-92bb-952daa2b598d';

    /**
     * Clave API para el entorno de producción.
     *
     * @var string
     */
    private $ApiKeyProd = '';

    /**
     * URL del token en uso.
     *
     * @var string
     */
    private $URLTOKEN = '';

    /**
     * URL del token para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLTOKENDEV = 'https://playcheck32.gameassists.co.uk/PlayerImpersonation/v1';

    /**
     * URL del token para el entorno de producción.
     *
     * @var string
     */
    private $URLTOKENPROD = '';

    /**
     * URL SOAP para el entorno de desarrollo.
     *
     * @var string
     */
    private $SOAPURLDEV = 'https://orionapi32.gameassists.co.uk/orion/vanguardadmin/SOAP2';

    /**
     * URL principal utilizada en las solicitudes.
     *
     * @var string
     */
    private $URL = 'https://redirector3.valueactive.eu/Casino/Default.aspx?applicationid=4123&serverid=25396';

    /**
     * URL de la API en uso.
     *
     * @var string
     */
    private $URL_API = '';

    /**
     * URL de la API para el entorno de desarrollo.
     *
     * @var string
     */
    private $URL_APIDEV = 'https://api32.api.valueactive.eu/Casino/FreeGames/v1';

    /**
     * URL de la API para el entorno de producción.
     *
     * @var string
     */
    private $URL_APIPROD = '';

    /**
     * URL específica para EETI.
     *
     * @var string
     */
    private $URLETI = 'https://redirector3.valueactive.eu/Casino/Default.aspx?applicationid=7217&serverid=25396&variant=MIT';

    /**
     * URL SOAP en uso.
     *
     * @var string
     */
    private $SOAPURL = 'https://orionapi32.gameassists.co.uk/orion/vanguardadmin/SOAP2';

    /**
     * URL para obtener el token del operador.
     *
     * @var string
     */
    private $OperatorToken = 'https://operatorsecurityuat.valueactive.eu/System/OperatorSecurity/v1/operatortokens';

    /**
     * ID del producto en uso.
     *
     * @var string
     */
    private $ProductID = '';

    /**
     * ID del producto para el entorno de desarrollo.
     *
     * @var string
     */
    private $ProductID_DEV = '24030';

    /**
     * ID del producto para el entorno de producción.
     *
     * @var string
     */
    private $ProductID_PROD = '';

    /**
     * URL de la API de cuentas en uso.
     *
     * @var string
     */
    private $URL_API_Account = '';

    /**
     * URL de la API de cuentas para el entorno de desarrollo.
     *
     * @var string
     */
    private $API_AccountDEV = 'https://api32.api.valueactive.eu/Account/v1';

    /**
     * URL de la API de cuentas para el entorno de producción.
     *
     * @var string
     */
    private $API_AccountPROD = '';


    /**
     * Constructor de la clase.
     * Configura las URLs y claves según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->URL_API = $this->URL_APIDEV;
            $this->NameKey = $this->NameKeyDev;
            $this->ApiKey = $this->ApiKeyDev;
            $this->URLTOKEN = $this->URLTOKENDEV;
            $this->ProductID = $this->ProductID_DEV;
            $this->URL_API_Account = $this->API_AccountDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->URL_API = $this->URL_APIPROD;
            $this->URLTOKEN = $this->URLTOKENDEV;
            $this->ProductID = $this->ProductID_PROD;
            $this->URL_API_Account = $this->API_AccountPROD;
        }
    }


    /**
     * Obtiene un juego específico.
     *
     * @param string  $gameid        ID del juego.
     * @param string  $lang          Idioma del juego.
     * @param boolean $play_for_fun  Indica si es modo diversión.
     * @param string  $usuarioToken  Token del usuario.
     * @param string  $LaunchId      ID de lanzamiento.
     * @param boolean $isMobile      Indica si es móvil (opcional).
     * @param string  $usumandanteId ID del usuario mandante (opcional).
     *
     * @return object Respuesta en formato JSON.
     */
    public function getGame($gameid, $lang, $play_for_fun, $usuarioToken, $LaunchId, $isMobile = false, $usumandanteId = "")
    {
        try {
            if ($usumandanteId == "") {
                $UsuarioTokenSite = new UsuarioToken($usuarioToken, '0');
                $usumandanteId = $UsuarioTokenSite->getUsuarioId();
            }

            $Proveedor = new Proveedor("", "GAMESGLOBAL");
            $Producto = new Producto("", $gameid, $Proveedor->getProveedorId());
            $Productodetalle = new ProductoDetalle("", $Producto->productoId, 'GAMEID');

            $gameid = $Productodetalle->pValue;

            try {
                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);

                $token = $UsuarioToken->createToken();
                $UsuarioToken->setToken($token);

                $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $usumandanteId);
                $UsuarioToken->setProductoId($Producto->productoId);
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
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId($Producto->productoId);


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
                    "response" => $this->URL . "&gameid=" . $gameid . "&ul=" . strtolower($lang) . "&authtoken=" . $UsuarioToken->getToken(),
                );
            }


            return json_decode(json_encode($array));
        } catch (Exception $e) {
        }
    }

    /**
     * Agrega giros gratis a un usuario.
     *
     * @param integer $bonoId              ID del bono.
     * @param string  $Name                Nombre de la oferta.
     * @param integer $roundsFree          Número de giros gratis.
     * @param float   $roundvalue          Valor por giro.
     * @param string  $StartDate           Fecha de inicio.
     * @param string  $EndDate             Fecha de fin.
     * @param array   $ids                 IDs de los usuarios.
     * @param array   $games               Juegos asociados.
     * @param integer $aditionalIdentifier Identificador adicional del freeSpin.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function AddFreespins($bonoId, $Name, $roundsFree, $roundvalue, $StartDate, $EndDate, $ids, $games, $aditionalIdentifier)
    {
        $Proveedor = new Proveedor("", "GAMESGLOBAL"); //OK
        $StartDate = date("Y-m-d H:i:s", strtotime($StartDate));
        $EndDate = date("Y-m-d H:i:s", strtotime($EndDate));

        $StartDate = str_replace(" ", "T", $StartDate);
        $EndDate = str_replace(" ", "T", $EndDate);
        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $Producto = new Producto("", $games[0], $Proveedor->proveedorId);
        $ProductoDetalle = new ProductoDetalle("", $Producto->productoId, "GAMEID");

        //Token de Operador
        $arraytokenOperator = array(
            "APIKey" => $this->ApiKeyDev
        );

        $response = $this->RequestTokenOperator($arraytokenOperator, $this->OperatorToken);
        $response = json_decode($response);

        $accessToken = $response->AccessToken;

        $arrayIds = array();

        foreach ($ids as $key => $value) {
            $Usuario = new Usuario($value);

            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            array_push($arrayIds, $UsuarioMandante->usumandanteId);
        }

        $roundsFree = 5;

        $Data = array(
            "idempotencyId" => $uuid,
            "balanceTypeId" => 1,
            "username" => "Usuario" . $UsuarioMandante->usumandanteId,
            "offerName" => $Name . $bonoId,
            "costPerBet" => floatval($roundvalue),
            "currencyIsoCode" => $Usuario->moneda,
            "defaultNumberOfGames" => intval($roundsFree),
            "games" => [
                array(
                    "gameName" => $ProductoDetalle->pValue,
                )
            ]
        );
        print_r(json_encode($Data));
        exit();

        $path = "/offers/product/$this->ProductID/costPerBet";

        $response = $this->RequestAccountApi($Data, $path, $accessToken);

        print_r(json_encode($response));
        exit();

        syslog(LOG_WARNING, " GAMESGLOBAL DATA: " . json_encode($Data) . " RESPONSE: " . $response);
        $responseCode = http_response_code();
        $response = json_decode($response);

        if ($responseCode == "" || $responseCode != 200 && $response->successCode != 'OK') {
            $return = array(
                "code" => 1,
                "response_code" => $responseCode,
                "response_message" => 'Error'
            );
        } else {
            $return = array(
                "code" => 0,
                "response_code" => $responseCode,
                "response_message" => 'OK',
                "bonusId" => $bonoId
            );
        }
        return $return;
    }

    /**
     * Realiza una solicitud a la API de cuentas.
     *
     * @param array  $array               Datos de la solicitud.
     * @param string $path                Ruta de la API.
     * @param string $responseAccessToken Token de acceso.
     *
     * @return string Respuesta de la API.
     */
    public function RequestAccountApi($array, $path, $responseAccessToken)
    {
        $curl = curl_init();
//
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL_API . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: bearer " . $responseAccessToken
            )
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Solicita un token de operador.
     *
     * @param array  $array Datos de la solicitud.
     * @param string $Path  URL del servicio.
     *
     * @return string Respuesta del servicio.
     */
    public function RequestTokenOperator($array, $Path)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $Path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
//        print_r($response);
        return $response;
    }

    /**
     * Valida manualmente una apuesta.
     *
     * @param string $string Datos de la apuesta.
     *
     * @return mixed Respuesta del servicio.
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
     * @param string $string Datos del juego.
     *
     * @return mixed Respuesta del servicio.
     */
    public function setManuallyCompleteGame($string)
    {
        $this->method = "ManuallyCompleteGame";

        $array = array();

        $response = $this->MICROGAMINGRequest($array, 1, $string);

        return ($response);
    }

    /**
     * Configura juegos gratis para un usuario.
     *
     * @return mixed Respuesta del servicio.
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
     * Realiza una solicitud SOAP a Microgaming.
     *
     * @param array   $array_tmp Datos de la solicitud.
     * @param integer $requestid ID de la solicitud.
     * @param string  $string    Datos adicionales.
     *
     * @return mixed Respuesta del servicio.
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
 * Genera un GUID único.
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
