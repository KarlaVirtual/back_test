<?php

/**
 * Clase DIGITALFEMSASERVICES
 *
 * Esta clase proporciona servicios de integración con la API de Digital FEMSA para la gestión de pagos.
 * Incluye métodos para crear solicitudes de pago, autenticación y conexión con la API.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-23
 * @author     Desconocido
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 * Clase DIGITALFEMSASERVICES
 *
 * Esta clase proporciona servicios de integración con la API de Digital FEMSA para la gestión de pagos.
 * Incluye métodos para crear solicitudes de pago, autenticación y conexión con la API.
 */
class DIGITALFEMSASERVICES
{
    /**
     * Metodo de la API que se está utilizando.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * Constructor de la clase DIGITALFEMSASERVICES.
     *
     * Inicializa los valores de configuración según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y un producto específicos.
     *
     * Este método realiza las siguientes acciones:
     * - Configura las claves de autenticación según el entorno y el mandante.
     * - Genera una transacción de producto con los datos proporcionados.
     * - Calcula los impuestos aplicables al depósito.
     * - Realiza una conexión con la API para registrar al cliente y crear una orden de pago.
     * - Registra los detalles de la transacción y el resultado en la base de datos.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con los resultados de la operación, incluyendo éxito y URL de redirección.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Proveedor = new Proveedor("", "DIGITALFEMSA");
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        $Registro = new Registro("", $Usuario->usuarioId);

        $this->metodo = "customers";
        $array = array(
            "name" => $nombre,
            "email" => $email
        );

        if ($Registro->codigoPostal == '') {
            $Registro->codigoPostal = '00810';
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $credentials->URL;
        $PRIVATE_KEY = $credentials->PRIVATE_KEY;

        $Response = $this->connection($array, $URL, $PRIVATE_KEY);

        $Response = json_decode($Response);
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
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);
        $this->metodo = "orders";
        $data = array(
            "currency" => $Usuario->moneda,
            "metadata" => array(
                "transactionId" => $transproductoId
            ),
            "customer_info" => array(
                "customer_id" => $Response->id
            ),

            "line_items" => array(
                array(
                    "name" => $descripcion,
                    "unit_price" => $valorTax * 100,
                    "quantity" => 1,

                ),
            ),

            "checkout" => array(
                "allowed_payment_methods" => array(
                    "cash",
                    "card",
                    "bank_transfer"
                ),
                "type" => "HostedPayment",
                "success_url" => $urlSuccess,
                "failure_url" => $urlFailed,
                "monthly_installments_enabled" => true,

                "monthly_installments_options" => array(
                    3,
                    6,
                    9,
                    12
                ),
                "redirection_time" => 20,
            ),
        );


        $Result = $this->connection($data, $URL, $PRIVATE_KEY);

        $Result = json_decode($Result);
        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        if ($Result->checkout->status == "Issued") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransaccionProducto->setExternoId($Result->id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();


            $data = array();
            $data["success"] = true;
            $data["url"] = $Result->checkout->url;
        } else {
            $data = array();
            $data["success"] = false;
            $data["Message"] = "Error de deposito";
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una conexión con la API de Digital FEMSA para enviar datos.
     *
     * Este método envía una solicitud POST a la URL especificada con los datos proporcionados.
     * Utiliza autenticación básica y maneja los encabezados necesarios para la comunicación con la API.
     *
     * @param array  $data        Datos a enviar en formato JSON.
     * @param string $URL         URL de la API a la que se enviarán los datos.
     * @param string $PRIVATE_KEY Clave privada para autenticación.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function connection($data, $URL, $PRIVATE_KEY)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization: Basic ' . base64_encode($PRIVATE_KEY),
            'Content-type: application/json ',
            'Accept: application/vnd.conekta-v2.0.0+json'
        );

        syslog(LOG_WARNING, " DIGITALFEMSASERVICES  " . $data);

        $curl = new CurlWrapper($URL . $this->metodo);

        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $result = $curl->execute();

        syslog(LOG_WARNING, " DIGITALFEMSASERVICES RESPUESTA  " . $result);

        return $result;
    }
}
