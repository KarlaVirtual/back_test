<?php

/**
 * Clase ASTROPAYSERVICES
 *
 * Esta clase proporciona servicios de integración con AstroPay.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\Integrations\payment;

use Exception;
use AstroPayStreamline;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase ASTROPAYSERVICES
 *
 * Esta clase proporciona servicios de integración con AstroPay, incluyendo la creación de solicitudes de pago
 * y la realización de peticiones HTTP. Contiene métodos para manejar transacciones y configurar el entorno
 * según el ambiente de desarrollo o producción.
 */
class ASTROPAYSERVICES
{
    /**
     * Nombre de usuario para autenticación con AstroPay.
     *
     * @var string
     */
    private $api_login = "wplaytestapi";

    /**
     * Contraseña para autenticación con AstroPay.
     *
     * @var string
     */
    private $api_password = "yp9t4bxv9qpg34zglxq1rrr";

    /**
     * Método utilizado en las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * URL del servicio SOAP de AstroPay.
     *
     * @var string
     */
    private $URL = 'http://api.bcwgames.com/soap/gameprovider/v2?wsdl';

    /**
     * URL del servicio de depósito en el entorno de desarrollo.
     *
     * @var string
     */
    private $serviceUrlDepositDEV = "http://justpay.apps-lapaymentgroup.com/justpay/check-out/SecurePayment";

    /**
     * URL del servicio de depósito en el entorno actual.
     *
     * @var string
     */
    private $serviceUrlDeposit = "";

    /**
     * Clave pública para autenticación.
     *
     * @var string
     */
    private $public_key = '';

    /**
     * Clave secreta para autenticación.
     *
     * @var string
     */
    private $secret_key = '';

    /**
     * Clave pública para el entorno de desarrollo.
     *
     * @var string
     */
    private $public_keyDEV = '240586434a34e73bde823e49ff1e2717';

    /**
     * Clave secreta para el entorno de desarrollo.
     *
     * @var string
     */
    private $secret_keyDEV = '71ff095cb544aea948db3795d6009220';

    /**
     * Constructor de la clase.
     *
     * Configura las URLs y claves según el entorno (desarrollo o producción).
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
     * Este método genera una transacción de pago utilizando los datos del usuario y producto proporcionados.
     * También calcula impuestos y registra la transacción en la base de datos.
     *
     * @param Usuario  $Usuario  Objeto que contiene los datos del usuario.
     * @param Producto $Producto Objeto que contiene los datos del producto.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        require_once 'astropay/AstroPayStreamline.class.php';

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

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        // Impuesto a los depósitos.
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

        $aps = new AstroPayStreamline();
        $response = $aps->newinvoice($transproductoId, $valorTax, $banco, $pais, $usuario_id, $cedula, $nombre, $email, $moneda);
        $decoded_response = json_decode($response);

        if ($decoded_response->status == 0) {
            $externoId = $decoded_response->x_document;
            $t_value = $response;

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($decoded_response);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = $decoded_response->link;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud HTTP GET.
     *
     * Este método utiliza cURL para realizar una solicitud GET a una URL específica.
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
