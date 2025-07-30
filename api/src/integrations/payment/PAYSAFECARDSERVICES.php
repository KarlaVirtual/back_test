<?php

/**
 * Clase `PAYSAFECARDSERVICES` para gestionar integraciones de pagos con Paysafecard.
 *
 * Este archivo contiene la implementación de la clase que maneja las solicitudes
 * de pago y otras operaciones relacionadas con Paysafecard. Incluye métodos para
 * configurar el entorno, crear solicitudes de pago y realizar peticiones HTTP.
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
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\Integrations\payment\safetypaydev\SafetyPayProxy;

/**
 * Clase `PAYSAFECARDSERVICES`.
 *
 * Esta clase gestiona las integraciones de pagos con Paysafecard, proporcionando
 * métodos para configurar el entorno, crear solicitudes de pago y realizar peticiones HTTP.
 */
class PAYSAFECARDSERVICES
{
    /**
     * Credenciales de inicio de sesión para la API.
     *
     * @var string
     */
    private $api_login = "wplaytestapi";

    /**
     * Contraseña para la API.
     *
     * @var string
     */
    private $api_password = "yp9t4bxv9qpg34zglxq1rrr";

    /**
     * Metodo utilizado en las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * URL del servicio SOAP del proveedor de juegos.
     *
     * @var string
     */
    private $URL = 'http://api.bcwgames.com/soap/gameprovider/v2?wsdl';

    /**
     * URL del servicio de depósito en el entorno de desarrollo.
     *
     * @var string
     */
    private $serviceUrlDepositDEV = "https://devadmin.doradobet.com/api/api/integrations/payment/paysafecard/confirm/?pn=1&mtid=";

    /**
     * URL del servicio de depósito en el entorno de producción.
     *
     * @var string
     */
    private $serviceUrlDeposit = "";

    /**
     * ID del cliente del comerciante en el entorno de producción.
     *
     * @var string
     */
    private $merchantClientId = '1090006667';

    /**
     * Tipo de ambiente actual (producción o desarrollo).
     *
     * @var string
     */
    private $tipoAmbiente = 'prod';

    /**
     * ID del cliente del comerciante en el entorno de desarrollo.
     *
     * @var string
     */
    private $merchantClientIdDEV = '1000005388';


    /**
     * Constructor de la clase.
     *
     * Configura las URLs y credenciales dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->serviceUrlDeposit = $this->serviceUrlDepositDEV;
            $this->merchantClientId = $this->merchantClientIdDEV;
            $this->tipoAmbiente = 'test';
        } else {
            $this->serviceUrlDeposit = $this->serviceUrlDeposit;
            $this->merchantClientId = $this->merchantClientId;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * Este metodo genera una solicitud de pago utilizando los datos del usuario y el producto.
     * También calcula impuestos y registra la transacción en la base de datos.
     *
     * @param Usuario  $Usuario  Objeto con los datos del usuario.
     * @param Producto $Producto Objeto con los datos del producto.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            include_once('paysafecard/src/loader.php');
        } else {
            include_once('paysafecard/src/loader.php');
        }

        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $banco = 0;
        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "66":
                $CountryCode = "ECU";
                break;
            case "2":
                $CountryCode = "NIC";
                break;
        }

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

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $amount = number_format((float)$valorTax, 2, '.', '');
        $currency = $moneda;
        $mtid = $transproductoId;
        $tipoambiente = $this->tipoAmbiente;
        $apiPaysafecard = $this->serviceUrlDeposit;
        $merchantClientId = $this->merchantClientId;

        $payment = new payment($tipoambiente);

        $transaction = array(
            'amount' => $amount,
            'currency' => $currency,
            'mtid' => $mtid,
            'merchantClientId' => $merchantClientId,

            'okUrl' => $urlOK,
            'nokUrl' => $urlERROR,
            'pnUrl' => $apiPaysafecard
        );
        $result = $payment->newPayment($transaction);


        if ( ! $result) {
            $t_value = json_encode(array());

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($t_value);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = $result;
        }

        return json_decode(json_encode($data));
    }


    /**
     * Realiza una solicitud HTTP.
     *
     * Este metodo utiliza cURL para realizar una solicitud GET a una URL específica.
     *
     * @param string $text Parámetros adicionales para la URL.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function request($text)
    {
        $ch = curl_init($this->URL2 . $this->productname . "/Main.ashx" . $text);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = (curl_exec($ch));

        return ($result);
    }
}
