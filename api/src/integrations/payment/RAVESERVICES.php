<?php

/**
 * Clase RAVESERVICES
 *
 * Este archivo contiene la implementación de la clase `RAVESERVICES`, que gestiona
 * la integración con servicios de pago. Proporciona métodos para crear solicitudes
 * de pago y realizar peticiones HTTP a servicios externos.
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
use Backend\Integrations\payment\safetypay\SafetyPayProxy;

/**
 * Clase RAVESERVICES
 *
 * Esta clase gestiona la integración con servicios de pago, proporcionando
 * métodos para crear solicitudes de pago y realizar peticiones HTTP a servicios externos.
 */
class RAVESERVICES
{
    /**
     * Nombre de usuario para autenticación con la API.
     *
     * @var string
     */
    private $api_login = "wplaytestapi";

    /**
     * Contraseña para autenticación con la API.
     *
     * @var string
     */
    private $api_password = "yp9t4bxv9qpg34zglxq1rrr";

    /**
     * Método utilizado para las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * URL del servicio SOAP para el proveedor de juegos.
     *
     * @var string
     */
    private $URL = 'http://api.bcwgames.com/soap/gameprovider/v2?wsdl';

    /**
     * URL del servicio de depósito en el entorno de desarrollo.
     *
     * @var string
     */
    private $serviceUrlDepositDEV = "https://admincert.virtualsoft.tech/api/api/partner_api/Payment/Rave?idd=";

    /**
     * URL del servicio de depósito en el entorno de producción.
     *
     * @var string
     */
    private $serviceUrlDeposit = "https://partnerapi.virtualsoft.tech/Payment/Rave?idd=";

    /**
     * Clave pública para autenticación en el entorno de producción.
     *
     * @var string
     */
    private $public_key = '';

    /**
     * Clave secreta para autenticación en el entorno de producción.
     *
     * @var string
     */
    private $secret_key = '';

    /**
     * Clave pública para autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $public_keyDEV = '';

    /**
     * Clave secreta para autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $secret_keyDEV = '';

    /**
     * Constructor de la clase.
     *
     * Inicializa las URLs y claves según el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->serviceUrlDeposit = $this->serviceUrlDepositDEV;
            $this->public_key = $this->public_keyDEV;
            $this->secret_key = $this->secret_keyDEV;
        } else {
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario  Objeto que contiene los datos del usuario.
     * @param Producto $Producto Objeto que contiene los datos del producto.
     * @param float    $valor    Monto del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta con el estado de la solicitud y la URL generada.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


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

        $baseUrl = $this->serviceUrlDeposit;
        $ConfigurationEnvironment = new ConfigurationEnvironment();


        $data = array();
        $data["success"] = true;
        $data["url"] = $baseUrl . $ConfigurationEnvironment->encrypt($transproductoId);


        return json_decode(json_encode($data));
    }

    /**
     * Realiza una petición HTTP GET.
     *
     * @param string $text Parámetros de la solicitud.
     *
     * @return string Respuesta del servicio externo.
     */
    public function request($text)
    {
        $ch = curl_init($this->URL2 . $this->productname . "/Main.ashx" . $text);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        //$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        return ($result);
    }
}
