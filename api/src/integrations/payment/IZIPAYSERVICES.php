<?php

/**
 * Este archivo contiene la clase `IZIPAYSERVICES` que gestiona la integración con el servicio de pagos Izipay.
 * Proporciona métodos para crear solicitudes de pago, generar tokens y confirmar pagos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\integrations\payment\Izipay;
use \CurlWrapper;

/**
 * Clase `IZIPAYSERVICES`
 *
 * Esta clase gestiona la integración con el servicio de pagos Izipay.
 * Proporciona métodos para crear solicitudes de pago, generar tokens y confirmar pagos.
 */
class IZIPAYSERVICES
{
    /**
     * URL de callback utilizada en el entorno actual (desarrollo o producción).
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payment/izipay/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/izipay/confirm/";

    /**
     * Constructor de la clase `IZIPAYSERVICES`.
     *
     * Inicializa la URL de callback según el entorno actual (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
        }
    }

    /**
     * Crea una solicitud de pago utilizando los datos del usuario, producto y otros parámetros.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza la transacción.
     * @param Producto $Producto   Objeto que representa el producto asociado a la transacción.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     * @param string   $urlCancel  URL a la que se redirige en caso de cancelación.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     *         - success: Indica si la operación fue exitosa.
     *         - Message: Mensaje de respuesta del servicio.
     *         - isIzipay: Indica si la operación es de tipo Izipay.
     *         - dataIzipay: Configuración y datos relacionados con Izipay.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $Usuario = new Usuario($Usuario->usuarioId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Pais = new Pais($Registro->paisnacimId, "");

        $Proveedor = new Proveedor("", "IZIPAY");
        $Producto = new Producto($Producto->productoId, "", $Proveedor->getProveedorId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $valor = number_format($valor, 2, '.', '');

        $url = $Credentials->URL_GENERATE_TOKEN;

        $body = json_encode([
            'requestSource' => 'ECOMMERCE',
            'merchantCode' => $Credentials->MERCHANT_CODE,
            'orderNumber' => $transproductoId,
            'publicKey' => $Credentials->PUBLIC_KEY,
            'amount' => (string)$valor
        ]);

        $headers = [
            'Content-Type: application/json',
            'transactionId: ' . $transproductoId
        ];

        $respuesta = $this->generateToken($url, $body, $headers);
        $tokenT = $respuesta->response->token;

        $dateTimeTransaction = round(microtime(true) * 1000) . rand(100, 999);
        
        if ($Producto->externoId == "cardIzipay") {
            $config = [
                "transactionId" => $transproductoId,
                "action" => "pay",
                "merchantCode" => $Credentials->MERCHANT_CODE,
                "order" => [
                    "orderNumber" => $transproductoId,
                    "showAmount" => true,
                    "currency" => $UsuarioMandante->moneda,
                    "amount" => (string)$valor,
                    "payMethod" => "CARD",
                    "processType" => "AT",
                    "merchantBuyerId" => $UsuarioMandante->usuarioMandante,
                    "dateTimeTransaction" => $dateTimeTransaction
                ],
                "billing" => [
                    "firstName" => $Usuario->nombre,
                    "lastName" => $Registro->apellido1,
                    "email" => $Registro->email,
                    "phoneNumber" => $Registro->celular,
                    "street" => $Registro->direccion,
                    "city" => $Registro->ciudad,
                    "state" => $Registro->ciudad,
                    "country" => $Pais->iso,
                    "postalCode" => $Registro->codigoPostal,
                    "documentType" => "CE",
                    "document" => $Registro->cedula,
                ],
                "render" => [
                    "typeForm" => "pop-up",
                ],
                "urlIPN" => $this->callback_url,
            ];
        } elseif ($Producto->externoId == "qrIzipay") {
            $config = [
                "transactionId" => $transproductoId,
                "action" => "pay",
                "merchantCode" => $Credentials->MERCHANT_CODE,
                "order" => [
                    "orderNumber" => $transproductoId,
                    "showAmount" => true,
                    "currency" => $UsuarioMandante->moneda,
                    "amount" => (string)$valor,
                    "payMethod" => "QR",
                    "processType" => "AT",
                    "merchantBuyerId" => $UsuarioMandante->usuarioMandante,
                    "dateTimeTransaction" => $dateTimeTransaction
                ],
                "billing" => [
                    "firstName" => $Usuario->nombre,
                    "lastName" => $Registro->apellido1,
                    "email" => $Registro->email,
                    "phoneNumber" => $Registro->celular,
                    "street" => $Registro->direccion,
                    "city" => $Registro->ciudad,
                    "state" => $Registro->ciudad,
                    "country" => $Pais->iso,
                    "postalCode" => $Registro->codigoPostal,
                    "documentType" => "CE",
                    "document" => $Registro->cedula,
                ],
                "render" => [
                    "typeForm" => "pop-up",
                ],
                "urlIPN" => $this->callback_url,
            ];
        } elseif ($Producto->externoId == "applePayIzipay") {
            $config = [
                "transactionId" => $transproductoId,
                "action" => "pay",
                "merchantCode" => $Credentials->MERCHANT_CODE,
                "order" => [
                    "orderNumber" => $transproductoId,
                    "showAmount" => true,
                    "currency" => $UsuarioMandante->moneda,
                    "amount" => (string)$valor,
                    "payMethod" => "APPLE_PAY",
                    "processType" => "AT",
                    "merchantBuyerId" => $UsuarioMandante->usuarioMandante,
                    "dateTimeTransaction" => $dateTimeTransaction
                ],
                "billing" => [
                    "firstName" => $Usuario->nombre,
                    "lastName" => $Registro->apellido1,
                    "email" => $Registro->email,
                    "phoneNumber" => $Registro->celular,
                    "street" => $Registro->direccion,
                    "city" => $Registro->ciudad,
                    "state" => $Registro->ciudad,
                    "country" => $Pais->iso,
                    "postalCode" => $Registro->codigoPostal,
                    "documentType" => "CE",
                    "document" => $Registro->cedula,
                ],
                "render" => [
                    "typeForm" => "pop-up",
                ],
                "urlIPN" => $this->callback_url,
            ];
        } elseif ($Producto->externoId == "yapeIzipay") {
            $config = [
                "transactionId" => $transproductoId,
                "action" => "pay",
                "merchantCode" => $Credentials->MERCHANT_CODE,
                "order" => [
                    "orderNumber" => $transproductoId,
                    "showAmount" => true,
                    "currency" => $UsuarioMandante->moneda,
                    "amount" => (string)$valor,
                    "payMethod" => "YAPE_CODE",
                    "processType" => "AT",
                    "merchantBuyerId" => $UsuarioMandante->usuarioMandante,
                    "dateTimeTransaction" => $dateTimeTransaction
                ],
                "billing" => [
                    "firstName" => $Usuario->nombre,
                    "lastName" => $Registro->apellido1,
                    "email" => $Registro->email,
                    "phoneNumber" => $Registro->celular,
                    "street" => $Registro->direccion,
                    "city" => $Registro->ciudad,
                    "state" => $Registro->ciudad,
                    "country" => $Pais->iso,
                    "postalCode" => $Registro->codigoPostal,
                    "documentType" => "CE",
                    "document" => $Registro->cedula,
                ],
                "render" => [
                    "typeForm" => "pop-up",
                ],
                "urlIPN" => $this->callback_url,
            ];
        } elseif ($Producto->externoId == "pagoPushIzipay") {
            $config = [
                "transactionId" => $transproductoId,
                "action" => "pay",
                "merchantCode" => $Credentials->MERCHANT_CODE,
                "order" => [
                    "orderNumber" => $transproductoId,
                    "showAmount" => true,
                    "currency" => $UsuarioMandante->moneda,
                    "amount" => (string)$valor,
                    "payMethod" => "PAGO_PUSH",
                    "processType" => "AT",
                    "merchantBuyerId" => $UsuarioMandante->usuarioMandante,
                    "dateTimeTransaction" => $dateTimeTransaction
                ],
                "billing" => [
                    "firstName" => $Usuario->nombre,
                    "lastName" => $Registro->apellido1,
                    "email" => $Registro->email,
                    "phoneNumber" => $Registro->celular,
                    "street" => $Registro->direccion,
                    "city" => $Registro->ciudad,
                    "state" => $Registro->ciudad,
                    "country" => $Pais->iso,
                    "postalCode" => $Registro->codigoPostal,
                    "documentType" => "CE",
                    "document" => $Registro->cedula,
                ],
                "render" => [
                    "typeForm" => "pop-up",
                ],
                "urlIPN" => $this->callback_url,
            ];
        }


        if ($respuesta->message == "OK") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($respuesta));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $dataIzipay = [
                'authorization' => $tokenT,
                'keyRSA' => 'RSA',
                'callbackResponse' => $this->callback_url,
                'config' => $config,
            ];

            $Transaction->commit();
            $data = array();
            $data["success"] = true;
            $data["Message"] = $respuesta;
            $data["isIzipay"] = true;
            $data["dataIzipay"] = $dataIzipay;
            return json_decode(json_encode($data));
        } else {
            $data = array();
            $data["success"] = false;
            $data["Message"] = '';
            return json_decode(json_encode($data));
        }
    }

    /**
     * Genera un token utilizando una solicitud HTTP POST.
     *
     * @param string $url     URL del servicio al que se enviará la solicitud.
     * @param string $body    Cuerpo de la solicitud en formato JSON.
     * @param array  $headers Encabezados HTTP para la solicitud.
     *
     * @return object|null Respuesta decodificada en formato JSON o null en caso de error.
     */
    public function generateToken($url, $body, $headers)
    {
        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($url);

        $curl->setOptionsArray([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headers
        ]);


        // Ejecutar la solicitud y manejar errores
        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Confirma un pago procesando los datos de respuesta proporcionados.
     *
     * @param string $response_data Datos de respuesta en formato JSON proporcionados por el servicio de pago.
     *
     * @return object Respuesta en formato JSON con el resultado de la confirmación.
     *         - success: Indica si la operación fue exitosa.
     *         - Message: Mensaje de respuesta del servicio.
     */
    public function confirmPayment($response_data = "")
    {
        $confirm = json_decode($response_data);

        $TransactionId = $confirm->response->order[0]->orderNumber;
        $externoId = $confirm->response->order[0]->uniqueId;
        $value = $confirm->response->order[0]->amount;
        $status = $confirm->response->order[0]->stateMessage;

        if ($status == 'Autorizado') {
            $status = "APPROVED";
        } else {
            $status = "CANCELED";
        }

        /* Procesamos */
        $Izipay = new Izipay($externoId, $TransactionId, $value, $status);
        $return = $Izipay->confirmation($confirm);

        $data = array();
        $data["success"] = true;
        $data["Message"] = $return;
        return json_decode(json_encode($data));
    }
}
