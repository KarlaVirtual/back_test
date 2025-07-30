<?php

/**
 * Clase para gestionar servicios de integración con Conekta.
 *
 * Este archivo contiene la implementación de la clase `CONEKTASERVICES`,
 * que permite realizar operaciones relacionadas con pagos a través de la API de Conekta.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use \CurlWrapper;
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
use Backend\dto\UsuarioToken;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use Backend\Integrations\payment\CLUBPAGOSERVICES;

/**
 * Clase para gestionar servicios de integración con Conekta.
 *
 * Esta clase proporciona métodos para crear solicitudes de pago y manejar la autenticación
 * con la API de Conekta.
 */
class CONEKTASERVICES
{
    /**
     * Metodo de la API a utilizar.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * Constructor de la clase CONEKTASERVICES.
     *
     * Inicializa las propiedades del usuario y configura el proveedor y el entorno.
     * 
     * @param bool  $ConfigurationEnvironment  Indica si es modo desarrollo o producción.
     *
     */

    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

     /**
     * Crea una solicitud de pago utilizando la API de Conekta.
     *
     * Este metodo genera una solicitud de pago para un usuario y un producto específicos,
     * configurando los datos necesarios para la transacción, como el cliente, el producto,
     * los impuestos y las URLs de éxito y fallo. También maneja la conexión con la API de Conekta
     * para crear clientes y órdenes, y registra los detalles de la transacción en la base de datos.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con los resultados de la operación, incluyendo éxito y URL de checkout.
     */
    public function  createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {

        $Proveedor = new Proveedor("", "CONEKTA");
        $Subproveedor = new Subproveedor("", "CONEKTA");

        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
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
     * Realiza una solicitud HTTP POST a la API de Conekta utilizando cURL.
     * 
     * Esta función toma los datos de la solicitud, la URL y la clave privada, y realiza una petición POST a la API de Conekta.
     * Además, registra en el syslog las solicitudes y respuestas de la API para su monitoreo y depuración.
     * 
     * @param mixed  $data        Los datos que se envían en el cuerpo de la solicitud, que se codifican en formato JSON.
     * @param string $URL         La URL base a la que se enviará la solicitud.
     * @param string $PRIVATE_KEY La clave privada utilizada para la autorización en la API de Conekta.
     * 
     * @return string  Retorna la respuesta de la API de Conekta en formato JSON (si la solicitud fue exitosa),
     *                 o un mensaje de error (si hubo problemas con la solicitud).
     * 
     * @throws Exception Lanza una excepción si la solicitud cURL falla.
     */

    public function connection($data, $URL, $PRIVATE_KEY)
    {

        $data = json_encode($data);

        $headers = array(
            'Authorization: Basic ' . base64_encode($PRIVATE_KEY),
            'Content-type: application/json ',
            'Accept: application/vnd.conekta-v2.0.0+json'
        );

        syslog(LOG_WARNING, " CONEKTASERVICES  " . $data);

        $curl = new CurlWrapper($URL . $this->metodo);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $this->metodo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $response = $curl->execute();
        return $response;
    }
}
