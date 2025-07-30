<?php

/**
 * Clase para gestionar servicios de integración con PayPal.
 *
 * Este archivo contiene la implementación de la clase `PAYPALSERVICES`, que permite
 * realizar solicitudes de pago a través de la API de PayPal, incluyendo la creación
 * de órdenes de pago y la gestión de transacciones relacionadas.
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
use AstroPayStreamline;
use Backend\dto\Usuario;
use Sample\PayPalClient;
use Backend\dto\Mandante;
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

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use Backend\mysql\TransaccionProductoMySqlDAO;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use Backend\mysql\TransproductoDetalleMySqlDAO;

use Guzzle\Common\Exception\ExceptionCollection;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

/**
 * Clase `PAYPALSERVICES`
 *
 * Esta clase gestiona la integración con los servicios de PayPal, permitiendo
 * la creación de órdenes de pago y la gestión de transacciones relacionadas.
 */
class PAYPALSERVICES
{

    /**
     * Identificador del cliente utilizado para la integración con PayPal.
     *
     * @var string
     */
    private $clientId;

    /**
     * Identificador del cliente para el entorno de desarrollo 2.
     *
     * @var string
     */
    private $clientIdDEV2 = 'ATg5EN5MgRPaJq6Bd33VP5oa1gGQyYg9j6lIPbTJDpdnOO6FJvCboPAml-5o15p24lRgNuW-BPw6N3Ue';

    /**
     * Identificador del cliente para el entorno de desarrollo.
     *
     * @var string
     */
    private $clientIdDEV = 'AbTD6PjHzz8L8kzeD9JlHd0S3vMGTRXC86IA22Qw81ktwLtBiLfAgHJyVoxGxTJ5lilNqycyW_UeTEcW';

    /**
     * Identificador del cliente para el entorno de producción.
     *
     * @var string
     */
    private $clientIdPROD = '';

    /**
     * Secreto del cliente utilizado para la integración con PayPal.
     *
     * @var string
     */
    private $clientSecretId;

    /**
     * Secreto del cliente para el entorno de desarrollo 2.
     *
     * @var string
     */
    private $clientSecretIdDEV2 = 'EGmpRNJcFGbGTJDzgXCBey_Yt6pi21znRC3V8DUHmIPGnW6O4983P30r32LHpVDS4i9AzeV09RxtFLPn';

    /**
     * Secreto del cliente para el entorno de desarrollo.
     *
     * @var string
     */
    private $clientSecretIdDEV = 'EPWQf0WGjpBCRh1d-JPMFSS_p1_J4Nt6l4FnH4i66-LaH6GSqGn83FPnP5y-dFy0amWsTaIc_V_z14A5';

    /**
     * Secreto del cliente para el entorno de producción.
     *
     * @var string
     */
    private $clientSecretIdPROD = '';

    /**
     * Constructor de la clase PAYPALSERVICES.
     *
     * Inicializa las credenciales de cliente y secreto según el entorno
     * de configuración (desarrollo o producción).
     */
    public function __construct()
    {
        {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $this->clientId = $this->clientIdDEV;
                $this->clientSecretId = $this->clientSecretIdDEV;
            } else {
                $this->clientId = $this->clientIdPROD;
                $this->clientSecretId = $this->clientSecretIdPROD;
            }
        }
    }

    /**
     * Crea una solicitud de pago a través de PayPal.
     *
     * @param Usuario  $Usuario  Objeto del usuario que realiza la transacción.
     * @param Producto $Producto Objeto del producto asociado a la transacción.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta de la solicitud de pago en formato JSON.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        try {
            $Subproveedor = new Subproveedor('', 'PAYPAL');

            $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Usuario->mandante, '');
            $Detalle = $Subproveedor->detalle;
            $Detalle = json_decode($Detalle);

            $this->clientId = $Detalle->clientId;
            $this->clientSecretId = $Detalle->clientSecretId;

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $environment = new SandboxEnvironment($this->clientId, $this->clientSecretId);
            } else {
                $environment = new ProductionEnvironment($this->clientId, $this->clientSecretId);
            }
            $client = new PayPalHttpClient($environment);


            $Registro = new Registro("", $Usuario->usuarioId);

            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';

            $banco = 0;
            $pais = $Usuario->paisId;
            $usuario_id = $Usuario->usuarioId;
            $cedula = $Registro->cedula;
            $nombre = $Usuario->nombre;
            $email = $Usuario->login;

            $producto_id = $Producto->productoId;
            $moneda = $Usuario->moneda;
            $mandante = $Usuario->mandante;
            $descripcion = "Deposito";

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

            $valortotal = $valorTax;

            if ($moneda == 'CRC') {
                $valortotal = $valortotal * (0.0016);
                $moneda = 'USD';
            }


            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

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


            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = array(
                "intent" => "CAPTURE",
                "purchase_units" => array(
                    array(
                        "reference_id" => $transproductoId,
                        "amount" => array(
                            "value" => $valortotal,
                            "currency_code" => $moneda
                        )
                    )
                ),
                "application_context" => array(
                    "cancel_url" => $urlERROR,
                    "return_url" => $urlOK
                )
            );

            $response = $client->execute($request);


            if ($response->statusCode == 201) {
                $OrdenID = $response->result->id;

                $respuesta = array();
                $respuesta["status"] = 1;
                $respuesta["orden"] = $OrdenID;
            } else {
                $apierror = true;

                $respuesta = array();
                $respuesta["status"] = 0;
                $respuesta["msg"] = json_decode($response);
            }


            if ( ! $apierror) {
                $TransaccionProducto->externoId = $OrdenID;
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

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
                $data["url"] = $response->result->links[1]->href;
            }
        } catch (Exception $e) {
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud HTTP utilizando cURL.
     *
     * @param array  $data   Datos a enviar en la solicitud.
     * @param string $method Método HTTP a utilizar (POST, GET, etc.).
     * @param array  $header Encabezados HTTP adicionales.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function request($data, $method, $header = array())
    {
        $data = json_encode($data);
        $ch = curl_init($this->URL . $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = curl_exec($ch);

        return ($result);
    }
}
