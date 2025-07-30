<?php

/**
 * Clase OMNISERVICES
 *
 * Esta clase proporciona métodos para la integración con servicios de pago.
 * Incluye funcionalidades para la creación de solicitudes de pago, generación de sesiones,
 * manejo de tokens y conexiones con APIs externas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\Departamento;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase OMNISERVICES
 *
 * Proporciona métodos para la integración con servicios de pago, incluyendo
 * la creación de solicitudes de pago, generación de sesiones, manejo de tokens
 * y conexiones con APIs externas.
 */
class OMNISERVICES
{
    /**
     * Nombre de usuario para la autenticación.
     *
     * @var string
     */
    private $username = "";

    /**
     * Contraseña para la autenticación.
     *
     * @var string
     */
    private $password = "";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $passwordDev = "zM60o)sdfBh6E1!f6z7a";

    /**
     * Contraseña para el entorno de producción.
     *
     * @var string
     */
    private $passwordProd = "jd!hcd+65Djfpw*dg-dS";

    /**
     * Dominio de inicio de sesión.
     *
     * @var string
     */
    private $loginDomain = "";

    /**
     * Dominio de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $loginDomainDev = "INMOStaging";

    /**
     * Dominio de inicio de sesión para el entorno de producción.
     *
     * @var string
     */
    private $loginDomainProd = "INMOVarious";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "Cashier";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "Cashier";

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL secundaria para las solicitudes.
     *
     * @var string
     */
    private $URL2 = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'xomstaging.com/';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'xomvariousmerc.com/';

    /**
     * URL de callback para las respuestas.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://admincert.virtualsoft.tech/api/api/integrations/payment/omni/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/omni/confirm/";

    /**
     * Clave secreta para la autenticación.
     *
     * @var string
     */
    private $SecretKey = "";

    /**
     * Clave secreta para el entorno de desarrollo.
     *
     * @var string
     */
    private $SecretKeyDev = "f3LqJZ7rVHfYcarmBT7f*";

    /**
     * Clave secreta para el entorno de producción.
     *
     * @var string
     */
    private $SecretKeyProd = "MN8aff3pCQQJ7mFQdhGAL7cXMAux8U";

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * Tipo de transacción o solicitud.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Tipo de transacción específica.
     *
     * @var string
     */
    private $transactiontype = "";

    /**
     * Constructor de la clase OMNISERVICES.
     *
     * Inicializa las credenciales y configuraciones dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->loginDomain = $this->loginDomainDev;
            $this->password = $this->passwordDev;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->SecretKey = $this->SecretKeyDev;
        } else {
            $this->username = $this->usernamePROD;
            $this->loginDomain = $this->loginDomainProd;
            $this->password = $this->passwordProd;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->SecretKey = $this->SecretKeyProd;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto que contiene información del usuario.
     * @param Producto $Producto   Objeto que contiene información del producto.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta con el estado de la solicitud y la URL de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $data = array(
            "transaction" => array(
                "transactionType" => $transproductoId,
                "methodOption" => "Depositar",
                "currency" => $moneda,
                "amount" => $valorTax,
                "methodInfo" => array(
                    "creditcardNumber" => "",
                    "cardHolderNameFirst" => "",
                    "cardHolderNameLast" => "",
                    "securityNumber" => "",
                    "creditcardId" => "",
                    "expirationMonth" => "",
                    "expirationYear" => "",
                    "fullname" => "",
                    "billingAddress" => "",
                    "phone" => "",
                    "email" => "",
                    "bankNameBwNonUsCanada" => "",
                    "bankAddress" => "",
                    "swiftCode" => "",
                    "routingNumber" => "",
                    "accountNumber" => "",
                    "transitNumber" => "",
                    "institutionNumber" => "",
                    "bankNameBwNonUsCountry" => "",
                    "ibanNumber" => "",
                    "bankNameBwNonUsGeneral" => "",
                    "bankNameBwUs" => "",
                    "bankNameCheck" => "",
                    "address" => "",
                    "cryptoAmount" => "",
                    "cryptoCurrency" => ""
                )
            )
        );

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $this->tipo = "";
        $Result = $this->connection($data);

        if ($Result != '') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($Result);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $response = json_decode($Result);

            $data = array();
            $data["success"] = true;
            $data["url"] = $response->payment_url;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Genera un token de autenticación.
     *
     * @return object Respuesta con el token generado.
     */
    public function token()
    {
        $data = array(
            "loginDomain" => $this->loginDomain,
            "username" => $this->username,
            "password" => $this->password
        );

        $data = json_encode($data);
        $response = $this->connectiontoken($data);


        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_decode(json_encode($array));
    }

    /**
     * Genera una sesión para el usuario.
     *
     * @param Usuario $Usuario Objeto que contiene información del usuario.
     * @param string  $token   Token de autenticación.
     *
     * @return object Respuesta con el estado de la sesión y la URL generada.
     * @throws Exception Si ocurre un error durante la generación de la sesión.
     */
    public function generate_session($Usuario, $token)
    {
        $this->tipo = "/generate_session";
        $this->token = $token;

        $Registro = new Registro("", $Usuario->usuarioId);

        $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);

        $mandante = new Mandante($Usuario->mandante);
        $Pais = new Pais($Usuario->paisId);

        if ($Registro->getCiudadId() != '' && $Registro->getCiudadId() != '0') {
            $ciudad = new Ciudad($Registro->getCiudadId());
        } else {
            $ciudad = new Ciudad('64943');
        }
        switch ($Pais->iso) {
            case 'CR':
                $iso = "CRI";
                break;
        }

        switch ($ciudad->deptoId) {
            case '851':
                $CodigoEstado = "CR-A";
                break;
            case '852':
                $CodigoEstado = "CR-C";
                break;
            case '853':
                $CodigoEstado = "CR-G";
                break;
            case '854':
                $CodigoEstado = "CR-H";
                break;
            case '855':
                $CodigoEstado = "CR-L";
                break;
            case '856':
                $CodigoEstado = "CR-P";
                break;
            case '857':
                $CodigoEstado = "CR-SJ";
                break;
        }

        // $iso = "CRI";
        $UserIp = explode(",", $Usuario->dirIp);
        $UserIp = $UserIp[0];

        $data = array(
            "session" => array(
                "bonuses" => array(
                    array(
                        "method" => "",
                        "code" => "",
                        "description" => "",
                        "terms" => ""
                    )
                ),
                "customer" => array(
                    "id" => "Usuario" . $Usuario->usuarioId,
                    "account" => $Usuario->login,
                    "password" => "LUSuRFt6URz9",
                    "creationDate" => $Usuario->fechaCrea,
                    "creationIp" => $UserIp,
                    "website" => "Doradobet",
                    "firstName" => $Registro->nombre1,
                    "middleName" => $Registro->nombre2,
                    "lastName" => $Registro->apellido1,
                    "lastNameSecond" => $Registro->apellido2,
                    "dateOfBirth" => $UsuarioOtrainfo->getFechaNacim(),
                    "addresses" => array(
                        array(
                            "isPrimary" => "true",
                            "countryCode" => $iso,
                            "addressType" => "billing",
                            "lastModifiedDate" => $Usuario->fechaActualizacion,
                            "streetAddress" => $UsuarioOtrainfo->getDireccion(),
                            "cityName" => $ciudad->ciudadNom,
                            "zipPostalCode" => "99999",
                            "stateIsoCode" => $CodigoEstado
                        )
                    ),
                    "phoneNumbers" => array(
                        array(
                            "isPrimary" => "true",
                            "countryCode" => $iso,
                            "areaCode" => $Pais->prefijoCelular,
                            "number" => $Registro->celular
                        ),

                    ),
                    "emailAddresses" => array(
                        array(
                            "isPrimary" => "true",
                            "emailAddress" => $Registro->email
                        ),

                    )
                ),
                "currentSessionData" => array(
                    "customerIp" => $UserIp,
                    "userAgent" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36",
                    "httpReferer" => $mandante->baseUrl,
                    "interface" => "TAG Desktop Cashier"
                ),
                "customerHistory" => array(
                    "overallWithdrawalHistory" => array(
                        "dateOfFirstWithdrawal" => "2021-01-01 00:00:00",
                        "dateOfLastWithdrawal" => "2021-01-01 23:59:59",
                        "totalWithdrawals" => 0,
                        "avgWithdrawalSize" => 0,
                        "modeWithdrawalSize" => 0,
                    ),
                    "overallDepositHistory" => array(
                        "dateOfFirstDeposit" => "2021-01-01 00:00:00",
                        "dateOfLastDeposit" => "2021-01-01 23:59:59",
                        "totalDeposits" => 0,
                        "avgDepositSize" => 0,
                        "modeDepositSize" => 0
                    ),
                    "bankwireDepositHistory" => array(
                        "dateOfFirstDeposit" => "2021-01-01 00:00:00",
                        "dateOfLastDeposit" => "2021-01-01 23:59:59",
                        "totalDeposits" => 0,
                        "avgDepositSize" => 0,
                        "modeDepositSize" => 0
                    ),
                    "moneytransferDepositHistory" => array(
                        "dateOfFirstDeposit" => "2021-01-01 00:00:00",
                        "dateOfLastDeposit" => "2021-01-01 23:59:59",
                        "totalDeposits" => 0,
                        "avgDepositSize" => 0,
                        "modeDepositSize" => 0
                    )
                ),
                "customerLanguage" => "es",
                "merchantExtraData" => array()
            )
        );

        syslog(10, 'DATA-OMNI ' . json_encode($data) . time());

        $response = $this->connection($data);
        syslog(10, 'RESPONSE-OMNI ' . json_encode($response) . time());

        //como organizar el metodo
        $response = json_decode($response);


        if ($response->message->code == 100) {
            $token = $response->data->middleToken;

            $URL = "https://cashier." . $this->URL . "cashier/" . $token . "/iframe/deposits";   //$this->transactiontype;
        } else {
            throw new Exception("Error encontrado", $response->message->code);
        }


        $array = array(
            "success" => true,
            "url" => $URL
        );
        return json_decode(json_encode($array));
    }

    /**
     * Verifica el estado de una sesión.
     *
     * @return object Respuesta con el estado de la sesión.
     */
    public function check_session()
    {
        $data = array(
            "message" => array(
                "code" => ""
            )
        );
        $response = $this->connection($data);


        $array = array(
            "error" => false,
            "response" => $response
        );
        return json_decode(json_encode($array));
    }


    /**
     * Realiza una conexión con la API externa.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la API.
     */
    public function connection($data)
    {
        $data = json_encode($data);

        $curl = curl_init("https://capi." . $this->URL . "api" . $this->tipo);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer' . " " . $this->token]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * Realiza una conexión para obtener un token.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la API con el token.
     */
    public function connectiontoken($data)
    {
        ;
        $curl = curl_init("https://api." . $this->URL . "api/v1/user/token");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
