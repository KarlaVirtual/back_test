<?php
/**
 * Clase LPGSERVICES
 *
 * Esta clase proporciona servicios relacionados con pagos y transacciones.
 * Incluye métodos para crear solicitudes de pago, realizar retiros y realizar solicitudes HTTP.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\payment;

use Exception;
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
 * Clase LPGSERVICES
 *
 * Esta clase proporciona métodos para interactuar con servicios de pago y transacciones.
 * Incluye funcionalidades para crear solicitudes de pago, realizar retiros y manejar solicitudes HTTP.
 */
class  LPGSERVICES
{


    /**
     * Nombre de usuario para autenticación en la API.
     *
     * @var string
     */
    private $api_login = "wplaytestapi";

    /**
     * Contraseña para autenticación en la API.
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
    private $serviceUrlDepositDEV = "http://justpaycert.apps-lapaymentgroup.com/justpay.cert/check-out/SecurePayment";

    /**
     * URL del servicio de depósito en el entorno de producción.
     *
     * @var string
     */
    private $serviceUrlDeposit = "http://justpay.apps-lapaymentgroup.com/justpay/check-out/SecurePayment";

    /**
     * Clave pública utilizada para la autenticación en el entorno de producción.
     *
     * @var string
     */
    private $public_key = '240586434a34e73bde823e49ff1e2717';

    /**
     * Clave secreta utilizada para la autenticación en el entorno de producción.
     *
     * @var string
     */
    private $secret_key = '71ff095cb544aea948db3795d6009220';

    /**
     * Clave pública utilizada para la autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $public_keyDEV = '7dba0945fa8491ba5e647f3b8cbf0ee9';

    /**
     * Clave secreta utilizada para la autenticación en el entorno de desarrollo.
     *
     * @var string
     */
    private $secret_keyDEV = '5f3c08e7ef54a0da69d724c4d4881823';

    /**
     * Constructor de la clase LPGSERVICES.
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
     * @param Usuario  $Usuario  Objeto que contiene información del usuario.
     * @param Producto $Producto Objeto que contiene información del producto.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;


        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $data["error"] = 1223;

        //$banco = $request->banco;
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

        switch (strtoupper($pais)) {
            case "PE":
                $pais = 348;
                break;
        }

        $data["error"] = 122;

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

        $data["error"] = 2;

        $data = array();

        $data['public_key'] = $this->public_key;
        $data['time'] = date("Y-m-dTHH:mm:ss");
        $data['channel'] = 1;
        $data['amount'] = (string)$valorTax;
        $data['currency'] = $moneda;
        $data['trans_id'] = $transproductoId;
        $data['time_expired'] = 120;
        $data['url_ok'] = $urlOK;
        $data['url_error'] = $urlERROR;
        $data['signature'] = hash('sha256', $this->public_key . $data['time'] . $data['amount'] . $data['currency'] . $data['trans_id'] . $data['time_expired'] . $data['url_ok'] . $data['url_error'] . $data['channel'] . $this->secret_key);

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        $curl = curl_init($this->serviceUrlDeposit);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $Result = (curl_exec($curl));

        curl_close($curl);

        $t_value = json_encode($Result);


        if ($Result != '') {
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
            $data["url"] = $Result;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza un retiro de fondos.
     *
     * @param object $data Datos necesarios para realizar el retiro.
     *
     * @return void
     */
    public function cashOut($data)
    {
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($data->ProductoId);
        $TransaccionProducto->setUsuarioId($data->UsuarioId);
        $TransaccionProducto->setValor($data->Valor);
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId('');
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($data->Mandante);
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $body = array(
            "order_id" => $transproductoId,
            "credit_note" => '',
            "account_id" => $data->UsuarioId,
            "account_type" => $data->UsuarioId,
            "vat_id" => $transproductoId,
            "name" => $transproductoId,
            "amount" => $transproductoId,
            "bank_detail" => $transproductoId,
            "channel" => $transproductoId,
            "user_email" => $transproductoId,
            "phone_number" => $transproductoId,
            "bank" => $transproductoId
        );


        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('M');
        $TransprodLog->setComentario('Envio Solicitud de pago');
        $TransprodLog->setTValue('{}');
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();
    }

    /**
     * Realiza una solicitud HTTP GET.
     *
     * @param string $text Parámetros de la solicitud.
     *
     * @return string Respuesta de la solicitud.
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
