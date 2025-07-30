<?php

/**
 * Clase COINPAYSERVICES
 *
 * Esta clase proporciona servicios de integración con CoinPay, incluyendo la creación de solicitudes de pago,
 * manejo de transacciones y conexión con la API de CoinPay.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase COINPAYSERVICES
 *
 * Proporciona métodos para la integración con CoinPay, incluyendo la creación de solicitudes de pago,
 * manejo de transacciones y conexión con la API de CoinPay.
 */
class COINPAYSERVICES
{
    /**
     * ID de usuario para la integración con CoinPay.
     *
     * @var string
     */
    private $userId = "";

    /**
     * Nombre de usuario para la integración con CoinPay.
     *
     * @var string
     */
    private $user = "";

    /**
     * Contraseña para la integración con CoinPay.
     *
     * @var string
     */
    private $password = "";

    /**
     * ID de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $userIdDEV = 31201;

    /**
     * ID de usuario para el entorno de producción.
     *
     * @var string
     */
    private $userIdPROD = 31201;

    /**
     * URL de la API de CoinPay.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de la API de CoinPay para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://coinpay.cr/WebApiV2/';

    /**
     * URL de la API de CoinPay para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://coinpay.cr/WebApiV2/';

    /**
     * URL de callback utilizada para recibir notificaciones de CoinPay.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback utilizada para recibir notificaciones de CoinPay en el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/coinpay/confirm/";

    /**
     * URL de callback utilizada para recibir notificaciones de CoinPay en el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/coinpay/confirm/";

    /**
     * Clave de API utilizada para la autenticación con CoinPay.
     *
     * @var string
     */
    private $ApiKey = "";

    /**
     * Clave de API utilizada para la autenticación con CoinPay en el entorno de desarrollo.
     *
     * @var string
     */
    private $ApiKeyDEV = "CR$7C00dfdfDDFFV5012021E2330728F64E38C91B07Dc9o1Di8nCpFaDyA904BE4c321321C00dfdfDDFFV5012026851on2021";

    /**
     * Clave de API utilizada para la autenticación con CoinPay en el entorno de producción.
     *
     * @var string
     */
    private $ApiKeyPROD = "CR$7C00dfdfDDFFV5012021E2330728F64E38C91B07Dc9o1Di8nCpFaDyA904BE4c321321C00dfdfDDFFV5012026851on2021";

    /**
     * Tipo de operación o endpoint a utilizar en la conexión con la API de CoinPay.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Clave compartida utilizada para la autenticación con CoinPay.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * URL del botón utilizado para redirigir a la página de pago.
     *
     * @var string
     */
    private $URLButton = "";

    /**
     * URL del botón para el entorno de desarrollo utilizado para redirigir a la página de pago.
     *
     * @var string
     */
    private $URLButtonDEV = "https://coinpay.cr/ep?d=MzEyMDE6MDoxNDpHYW5hOTU%3D";

    /**
     * URL del botón para el entorno de producción utilizado para redirigir a la página de pago.
     *
     * @var string
     */
    private $URLButtonPROD = "https://coinpay.cr/ep?d=MzEyMDE6MDoxNDpHYW5hOTU%3D";

    /**
     * Constructor de la clase COINPAYSERVICES.
     *
     * Inicializa las propiedades de la clase dependiendo del entorno de ejecución
     * (desarrollo o producción). Configura los valores de `userId`, `callback_url`,
     * `URL`, `URLButton` y `ApiKey` según el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->userId = $this->userIdDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLButton = $this->URLButtonDEV;
            $this->ApiKey = $this->ApiKeyDEV;
        } else {
            $this->userId = $this->userIdPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLButton = $this->URLButtonPROD;
            $this->ApiKey = $this->ApiKeyPROD;
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y producto específicos.
     *
     * Este método realiza los siguientes pasos:
     * - Calcula impuestos sobre el valor del pago.
     * - Registra la transacción en la base de datos.
     * - Genera un objeto de datos para la solicitud de integración con CoinPay.
     * - Envía la solicitud a la API de CoinPay.
     * - Retorna una URL para redirigir al usuario a la página de pago.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con el estado de éxito y la URL de redirección.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();

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

        $Registro = new Registro("", $usuario_id);

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

        $this->Encrypta();

        $this->tipo = "api/Integration/CreateChannel";
        //instanciar objeto o parametros
        $data['IdCurrency'] = intval($Producto->getExternoId());
        $data['IdExternalIdentification'] = $transproductoId;
        $data['TagName'] = $email;

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result = $this->connection($data);

        $Result = json_decode($Result);

        if ($Result != '' || $Result->Message != "Success") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue("");
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            switch ($Producto->externoId) {
                case 1:
                    $Currency = 'BTC';
                    break;
                case 2:
                    $Currency = 'ETH';
                    break;
                case 6:
                    $Currency = 'USD';
                    break;
                case 7:
                    $Currency = 'LTC';
                    break;
                case 8:
                    $Currency = 'DASH';
                    break;

                case 13:
                    $Currency = 'BCH';
                    break;
            }

            $CurrencyLocal = 15;
            $objec = array(
                "Currency" => $Currency,
                "Address" => $Result->Data->Address,
                "NotAllowModify" => true,
                "AmountUsd" => $valorTax,
                "IdCurrencyPay" => $CurrencyLocal
            );


            $objec = json_encode($objec);

            $datos = base64_encode($objec);
            $Url = $this->URLButton . "&r=" . $transproductoId . "&a=" . $datos;

            $data = array();
            $data["success"] = true;
            $data["url"] = $Url;


            return json_decode(json_encode($data));
        }
    }

    /**
     * Genera un token de autenticación en formato Base64.
     *
     * Este método combina el `userId` y la `ApiKey` de la clase, los codifica en Base64
     * y los asigna a la propiedad `$Auth`. Este token se utiliza para autenticar las solicitudes
     * realizadas a la API de CoinPay.
     *
     * @return void
     */
    public function Encrypta()
    {
        $this->Auth = base64_encode($this->userId . ":" . $this->ApiKey);
    }

    /**
     * Realiza una conexión HTTP utilizando cURL para enviar datos a la API de CoinPay.
     *
     * Este método configura y ejecuta una solicitud HTTP POST a la URL especificada en las propiedades
     * de la clase (`$URL` y `$tipo`). Los datos se envían en formato JSON, y se incluye un encabezado
     * de autorización con un token generado previamente.
     *
     * @param array $data Datos a enviar en la solicitud HTTP.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function connection($data)
    {
        $data = json_encode($data);


        $curl = curl_init($this->URL . $this->tipo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization:' . "Bearer" . " " . $this->Auth]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($curl);
        return $result;
    }

}
