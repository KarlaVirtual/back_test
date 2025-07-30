<?php
/**
 * Clase Quisk
 *
 * Esta clase proporciona métodos para interactuar con la API de Quisk.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Backend\integrations\wallet;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;
use SimpleXMLElement;

/**
 * Clase Quisk
 *
 * Esta clase proporciona métodos para interactuar con la API de Quisk.
 * Incluye funcionalidades como agregar cuentas, actualizar cuentas, autorizar apuestas,
 * anular apuestas y obtener resultados.
 */
class Quisk
{

    /**
     * Identificador del usuario del proveedor.
     *
     * Este valor se utiliza para autenticar las solicitudes con el proveedor.
     *
     * @var string
     */
    private $providerUserId = "";

    /**
     * Contraseña del usuario del proveedor.
     *
     * Este valor se utiliza para autenticar las solicitudes con el proveedor.
     *
     * @var string
     */
    private $providerPassword = "";

    /**
     * Identificador del usuario del proveedor en el entorno de desarrollo.
     *
     * @var string
     */
    private $providerUserIdDEV = "Bet001";

    /**
     * Contraseña del usuario del proveedor en el entorno de desarrollo.
     *
     * @var string
     */
    private $providerPasswordDEV = "sv!b3t1ng";

    /**
     * Identificador del usuario del proveedor en el entorno de producción.
     *
     * @var string
     */
    private $providerUserIdPROD = "Bet001";

    /**
     * Contraseña del usuario del proveedor en el entorno de producción.
     *
     * @var string
     */
    private $providerPasswordPROD = "sv!b3t1ng";

    /**
     * Identificador del comerciante.
     *
     * Este valor se utiliza para identificar al comerciante en las solicitudes.
     *
     * @var string
     */
    private $merchantId = "";

    /**
     * Identificador del terminal.
     *
     * Este valor se utiliza para identificar el terminal en las solicitudes.
     *
     * @var string
     */
    private $terminalId = "";

    /**
     * Identificador del comerciante en el entorno de desarrollo.
     *
     * @var string
     */
    private $merchantIdDEV = "601100133135418";

    /**
     * Identificador del terminal en el entorno de desarrollo.
     *
     * @var string
     */
    private $terminalIdDEV = "";

    /**
     * Identificador del comerciante en el entorno de producción.
     *
     * @var string
     */
    private $merchantIdPROD = "601100133135418";

    /**
     * Identificador del terminal en el entorno de producción.
     *
     * @var string
     */
    private $terminalIdPROD = "";

    /**
     * URL base para las solicitudes a la API.
     *
     * @var string
     */
    private $URL = "https://aisConnect-qa.aiswebnet.com/betApp/";

    /**
     * URL base para las solicitudes a la API en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = "https://aisConnect-qa.aiswebnet.com/betApp/";

    /**
     * URL base para las solicitudes a la API en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = "https://aisConnect.aiswebnet.com/AISConnect/Betting/";

    /**
     * Método actual que se está utilizando en la solicitud.
     *
     * @var string
     */
    private $method;

    /**
     * Clave secreta de Quisk para firmar las solicitudes.
     *
     * @var string
     */
    private $QuiskSecretKey;

    /**
     * Clave de API de Quisk para autenticar las solicitudes.
     *
     * @var string
     */
    private $QuiskAPIKey;

    /**
     * Clave de API de Quisk en el entorno de desarrollo.
     *
     * @var string
     */
    private $QuiskAPIKeyDEV = '';

    /**
     * Clave de API de Quisk en el entorno de producción.
     *
     * @var string
     */
    private $QuiskAPIKeyPROD = '853455266743236';

    /**
     * Clave secreta de Quisk en el entorno de desarrollo.
     *
     * @var string
     */
    private $QuiskSecretKeyDEV = "uwagjrjewks52g219";

    /**
     * Clave secreta de Quisk en el entorno de producción.
     *
     * @var string
     */
    private $QuiskSecretKeyPROD = "uwagjrjewks52g219";

    /**
     * Constructor de la clase Quisk
     *
     * Inicializa las variables de configuración dependiendo del entorno (desarrollo o producción).
     *
     * @throws Exception Si el token está vacío.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();


        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->providerUserId = $this->providerUserIdDEV;
            $this->providerPassword = sha1($this->providerPasswordDEV);
            $this->merchantId = $this->merchantIdDEV;
            $this->terminalId = $this->terminalIdDEV;
            $this->QuiskSecretKey = $this->QuiskSecretKeyDEV;
            $this->QuiskAPIKey = $this->QuiskAPIKeyDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->providerUserId = $this->providerUserIdPROD;
            $this->providerPassword = sha1($this->providerPasswordPROD);
            $this->merchantId = $this->merchantIdPROD;
            $this->terminalId = $this->terminalIdPROD;
            $this->QuiskSecretKey = $this->QuiskSecretKeyPROD;
            $this->QuiskAPIKey = $this->QuiskAPIKeyPROD;
        }
    }

    /**
     * Firma una solicitud
     *
     * Genera una firma HMAC-SHA1 para la solicitud dada.
     *
     * @param string $string Cadena a firmar.
     *
     * @return string Firma generada en base64.
     */
    public function signRequest($string)
    {
        $key = $this->QuiskSecretKey;
        $signature = (base64_encode(hash_hmac('sha1', $string, $key, $raw_output = true)));

        return $signature;
    }

    /**
     * Agregar cuenta
     *
     * Crea una nueva cuenta en el sistema Quisk.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario mandante.
     *
     * @return array Resultado de la operación, incluyendo errores si los hay.
     * @throws Exception Si el usuario mandante está vacío.
     */
    public function AddAoldCount($UsuarioMandante)
    {
        $this->method = 'AddAccount';

        try {
            $Proveedor = new Proveedor("", "QUISK");

            if ($UsuarioMandante == "" || is_null($UsuarioMandante)) {
                throw new Exception("Usuario Mandante vacio", "50001");
            }

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Registro = new Registro('', $Usuario->usuarioId);
            $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);

            $PKT = new SimpleXMLElement("<AddAccountRequest></AddAccountRequest>");

            $PKT->addChild('ExternalAccountId', $Usuario->usuarioId);
            $PKT->addChild('MobileNumber', $Registro->getCelular());
            $PKT->addChild('TaxId', $Registro->getCedula());
            $PKT->addChild('DOB', str_replace("-", "", $UsuarioOtrainfo->getFechaNacim()));
            $PKT->addChild('ProviderUserId', $this->providerUserId);
            $PKT->addChild('ProviderPassword', $this->providerPassword);
            $PKT->addChild('MerchantId', $this->merchantId);
            /*            $PKT->addAttribute('TerminalId',$this->terminalId);*/


            $sXml = $PKT->asXML();
            $repuesta = $this->Request($sXml);

            $respuestajson = ($repuesta);

            if ($respuestajson->Status != "SUCCESS") {
                if ($Usuario->usuarioId == '438120') {
                    //$respuestajson->Status='USER_NOT_FLAGGED_BETTING';
                }
                $array = array(
                    "error" => true,
                    "response" => array(
                        "code" => strtoupper($respuestajson->Status),
                        "message" => strtoupper($respuestajson->Message)
                    ),
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => 'SUCCESS',
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Actualizar cuenta
     *
     * Actualiza la información de una cuenta existente en el sistema Quisk.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario mandante.
     *
     * @return array Resultado de la operación, incluyendo errores si los hay.
     * @throws Exception Si el usuario mandante está vacío.
     */
    public function UpdateAoldCount($UsuarioMandante)
    {
        $this->method = 'UpdateAcount';
        try {
            $Proveedor = new Proveedor("", "QUISK");

            if ($UsuarioMandante == "" || is_null($UsuarioMandante)) {
                throw new Exception("Usuario Mandante vacio", "50001");
            }
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $registro = new Registro($Usuario->usuarioId);
            $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);

            $PKT = new SimpleXMLElement("<UpdateAccountRequest></UpdateAccountRequest>");
            $PKT->addAttribute('ExternalAccountId', $Usuario->usuarioId);
            $PKT->addAttribute('MobileNumber', $registro->getCelular());
            $PKT->addAttribute('TaxId', $registro->getCedula());
            $PKT->addAttribute('DOB', str_replace("-", "", $UsuarioOtrainfo->getFechaNacim()));
            $PKT->addAttribute('ProviderUserId', $this->providerUserId);
            $PKT->addAttribute('ProviderPassword', $this->providerPassword);
            $PKT->addAttribute('MerchantId', $this->merchantId);
            /*            $PKT->addAttribute('TerminalId',$this->terminalId);*/


            $sXml = $PKT->asXML();
            $repuesta = $this->Request($sXml);

            $respuestajson = json_encode($repuesta);

            if ($respuestajson->AddAcountRespose->status != "SUCCESS") {
                $array = array(
                    "error" => true,
                    "response" => array(
                        "code" => $respuestajson->AddAcountRespose->status
                    ),
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => $respuestajson,
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autorizar apuesta
     *
     * Autoriza una apuesta en el sistema Quisk.
     *
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario mandante.
     * @param string          $token           Token de autorización.
     * @param string          $bettingNumber   Número de referencia de la apuesta.
     * @param float           $betAmount       Monto de la apuesta.
     * @param string          $notes           Notas adicionales.
     *
     * @return array Resultado de la operación, incluyendo errores si los hay.
     * @throws Exception Si algún parámetro requerido está vacío.
     */
    public function AuthoriseBet($UsuarioMandante, $token, $bettingNumber, $betAmount, $notes)
    {
        $this->method = 'AuthoriseBet';
        try {
            $Proveedor = new Proveedor("", "QUISK");

            if ($UsuarioMandante == "" || is_null($UsuarioMandante)) {
                throw new Exception("Usuario Mandante vacio", "50001");
            }
            if ($token == "" || is_null($token)) {
                throw new Exception("Token vacio", "50001");
            }
            if ($bettingNumber == "" || is_null($bettingNumber)) {
                throw new Exception("Numero de referencia vacia", "50001");
            }
            if ($betAmount == "" || is_null($betAmount)) {
                throw new Exception("Costo total vacio", "50001");
            }
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $registro = new Registro($Usuario->usuarioId);
            $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);
            $Pais = new Pais($Usuario->paisId);

            switch ($Pais->iso) {
                case "JM":
                    $Pais->iso = "JMD";
                    break;
                case "MX":
                    $Pais->iso = "MEX";
                    break;
                case "EC":
                    $Pais->iso = "ECU";
                    break;
                case "PE":
                    $Pais->iso = "PER";
                    break;
            }

            $PKT = new SimpleXMLElement("<AuthoriseBetRequest></AuthoriseBetRequest>");
            $PKT->addAttribute('ExternalAccountId', $Usuario->usuarioId);
            $PKT->addAttribute('MobileNumber', $registro->getCelular());
            $PKT->addAttribute('TaxId', $registro->getCedula());
            $PKT->addAttribute('DOB', str_replace("-", "", $UsuarioOtrainfo->getFechaNacim()));
            $PKT->addAttribute('ProviderUserId', $this->providerUserId);
            $PKT->addAttribute('ProviderPassword', $this->providerPassword);
            $PKT->addAttribute('MerchantId', $this->merchantId);
            $PKT->addAttribute('TerminalId', $this->terminalId);
            $PKT->addAttribute('TokenId', $token);
            $PKT->addAttribute('BettingNumber', $bettingNumber);
            $PKT->addAttribute('BetAmount', $betAmount);
            $PKT->addAttribute('CurrencyCode', $Pais->iso);
            $PKT->addAttribute('TransDateTime', date('Ymd H:i:s'));
            $PKT->addAttribute('Notes', $notes);


            $sXml = $PKT->asXML();
            $repuesta = $this->Request($sXml);

            $respuestajson = json_encode($repuesta);

            if ($respuestajson->AddAcountRespose->status != "SUCCESS") {
                $array = array(
                    "error" => true,
                    "response" => array(
                        "code" => $respuestajson->AddAcountRespose->status
                    ),
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => $respuestajson,
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Anular apuesta
     *
     * Anula una apuesta previamente autorizada en el sistema Quisk.
     *
     * @param UsuarioMandante $UsuarioMandante       Objeto que contiene información del usuario mandante.
     * @param string          $paymentTransReference Referencia de la transacción de pago.
     * @param string          $bettingNumber         Número de referencia de la apuesta.
     * @param float           $betAmount             Monto de la apuesta.
     * @param string          $notes                 Notas adicionales.
     *
     * @return array Resultado de la operación, incluyendo errores si los hay.
     * @throws Exception Si algún parámetro requerido está vacío.
     */
    public function VoidBet($UsuarioMandante, $paymentTransReference, $bettingNumber, $betAmount, $notes)
    {
        $this->method = 'VoidBet';
        try {
            $Proveedor = new Proveedor("", "QUISK");

            if ($UsuarioMandante == "" || is_null($UsuarioMandante)) {
                throw new Exception("Usuario Mandante vacio", "50001");
            }
            if ($paymentTransReference == "" || is_null($paymentTransReference)) {
                throw new Exception("ID de la transacción vacio", "50001");
            }
            if ($bettingNumber == "" || is_null($bettingNumber)) {
                throw new Exception("Numero de referencia vacia", "50001");
            }
            if ($betAmount == "" || is_null($betAmount)) {
                throw new Exception("Costo total vacio", "50001");
            }
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $registro = new Registro($Usuario->usuarioId);

            $PKT = new SimpleXMLElement("<VoidBetRequest></VoidBetRequest>");
            $PKT->addAttribute('ExternalAccountId', $Usuario->usuarioId);
            $PKT->addAttribute('ProviderUserId', $this->providerUserId);
            $PKT->addAttribute('ProviderPassword', $this->providerPassword);
            $PKT->addAttribute('MerchantId', $this->merchantId);
            $PKT->addAttribute('TerminalId', $this->terminalId);
            $PKT->addAttribute('MobileNumber', $registro->getCelular());
            $PKT->addAttribute('PaymentTransReference', $paymentTransReference);
            $PKT->addAttribute('BettingNumber', $bettingNumber);
            $PKT->addAttribute('BetAmount', $betAmount);
            $PKT->addAttribute('Notes', $notes);


            $sXml = $PKT->asXML();
            $repuesta = $this->Request($sXml);

            $respuestajson = json_encode($repuesta);

            if ($respuestajson->AddAcountRespose->status != "SUCCESS") {
                $array = array(
                    "error" => true,
                    "response" => array(
                        "code" => $respuestajson->AddAcountRespose->status
                    ),
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => $respuestajson,
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtener resultados
     *
     * Obtiene los resultados de una apuesta en el sistema Quisk.
     *
     * @param UsuarioMandante $UsuarioMandante         Objeto que contiene información del usuario mandante.
     * @param string          $paymentTransReference   Referencia de la transacción de pago.
     * @param string          $bettingNumber           Número de referencia de la apuesta.
     * @param float           $betAmount               Monto de la apuesta.
     * @param string          $bettingCategory         Categoría de la apuesta.
     * @param string          $bettingSubCategory      Subcategoría de la apuesta.
     * @param string          $bettingEventName        Nombre del evento de la apuesta.
     * @param string          $bettingEventID          ID del evento de la apuesta.
     * @param string          $bettingEventDescription Descripción del evento de la apuesta.
     * @param float           $winningAmount           Monto ganado.
     *
     * @return array Resultado de la operación, incluyendo errores si los hay.
     * @throws Exception Si algún parámetro requerido está vacío.
     */
    public function GetResults(
        $UsuarioMandante,
        $paymentTransReference,
        $bettingNumber,
        $betAmount,
        $bettingCategory,
        $bettingSubCategory,
        $bettingEventName,
        $bettingEventID,
        $bettingEventDescription,
        $winningAmount
    ) {
        $this->method = 'GetResults';
        try {
            $Proveedor = new Proveedor("", "QUISK");


            if ($UsuarioMandante == "" || is_null($UsuarioMandante)) {
                throw new Exception("Usuario Mandante vacio", "50001");
            }
            if ($paymentTransReference == "" || is_null($paymentTransReference)) {
                throw new Exception("ID de la transacción vacio", "50001");
            }
            if ($bettingNumber == "" || is_null($bettingNumber)) {
                throw new Exception("Numero de referencia vacia", "50001");
            }
            if ($betAmount == "" || is_null($betAmount)) {
                throw new Exception("Costo total vacio", "50001");
            }
            if ($bettingCategory == "" || is_null($bettingCategory)) {
                throw new Exception("ID de categoria vacia", "50001");
            }
            if ($winningAmount == "" || is_null($winningAmount)) {
                throw new Exception("Cantidad ganada vacia", "50001");
            }
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $registro = new Registro($Usuario->usuarioId);
            $Pais = new Pais($Usuario->paisId);
            switch ($Pais->iso) {
                case "JM":
                    $Pais->iso = "JMD";
                    break;
                case "MX":
                    $Pais->iso = "MEX";
                    break;
                case "EC":
                    $Pais->iso = "ECU";
                    break;
                case "PE":
                    $Pais->iso = "PER";
                    break;
            }

            $PKT = new SimpleXMLElement("<GetResultsRequest></GetResultsRequest>");
            $PKT->addAttribute('ExternalAccountId', $Usuario->usuarioId);
            $PKT->addAttribute('MobileNumber', $registro->getCelular());
            $PKT->addAttribute('ProviderUserId', $this->providerUserId);
            $PKT->addAttribute('ProviderPassword', $this->providerPassword);
            $PKT->addAttribute('MerchantId', $this->merchantId);
            $PKT->addAttribute('TerminalId', $this->terminalId);
            $PKT->addAttribute('PaymentTransReference', $paymentTransReference);
            $PKT->addAttribute('BettingNumber', $bettingNumber);
            $PKT->addAttribute('BettingCategory', $bettingCategory);
            $PKT->addAttribute('BettingSubCategory', $bettingSubCategory);
            $PKT->addAttribute('BettingEventName', $bettingEventName);
            $PKT->addAttribute('BettingEventID', $bettingEventID);
            $PKT->addAttribute('BettingEventDescription', $bettingEventDescription);
            $PKT->addAttribute('WinningAmount', $winningAmount);
            $PKT->addAttribute('BetAmount', $betAmount);
            $PKT->addAttribute('CurrencyCode', $Pais->iso);
            $PKT->addAttribute('TransDateTime', date('Ymd H:i:s'));


            $sXml = $PKT->asXML();
            $repuesta = $this->Request($sXml);

            $respuestajson = json_encode($repuesta);

            if ($respuestajson->AddAcountRespose->status != "SUCCESS") {
                $array = array(
                    "error" => true,
                    "response" => array(
                        "code" => $respuestajson->AddAcountRespose->status
                    ),
                );
            } else {
                $array = array(
                    "error" => false,
                    "response" => $respuestajson,
                );
            }
            return json_decode(json_encode($array));
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realizar solicitud
     *
     * Envía una solicitud a la API de Quisk utilizando cURL.
     *
     * @param string $sXml XML de la solicitud.
     *
     * @return SimpleXMLElement Respuesta de la API.
     */
    public function Request($sXml)
    {
        $ch = curl_init($this->URL . $this->method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sXml);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml;encoding: utf-8',
            'Date: ' . str_replace(
                " ",
                "T",
                date('Y-m-d H:i:s') . "112Z"
            ),
            'Authorisation: ' . $this->QuiskAPIKey . ':' . $this->signRequest($sXml),
            'Content-Length: ' . strlen($sXml)

        ));

        syslog(LOG_WARNING, "QUISKREQUEST :" . $sXml);

        $result = (curl_exec($ch));
        curl_close($ch);

        try {
            $log = "\r\n" . "-----------REQUEST--------------" . "\r\n";
            $log = $log . $sXml;

            $log = $log . "\r\n" . "-----------RESPONSE--------------" . "\r\n";
            $log = $log . $result;

            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        } catch (Exception $e) {
        }

        syslog(LOG_WARNING, "QUISKREQUEST R :" . $result);

        $result = simplexml_load_string($result);


        return ($result);
    }

    /**
     * Convertir error
     *
     * Convierte un error en un formato estándar.
     *
     * @param integer $code    Código del error.
     * @param string  $message Mensaje del error.
     *
     * @return array Error convertido en formato estándar.
     */
    public function convertError($code, $message)
    {
        $array = array(
            "error" => true,
            "response" => array(
                "code" => $code,
                "message" => $message
            ),
        );
        return json_decode(json_encode($array));
    }


}
