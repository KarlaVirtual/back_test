<?php

/**
 * Clase `GREENPAYSERVICES` para la integración con el sistema de pagos GreenPay.
 *
 * Este archivo contiene la implementación de métodos para manejar transacciones,
 * validaciones y operaciones relacionadas con pagos a través de la plataforma GreenPay.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use phpDocumentor\Reflection\Types\This;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransprodLogMySqlExtDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 * Clase principal para manejar servicios de GreenPay.
 *
 * Contiene métodos para crear solicitudes de pago, validar transacciones,
 * registrar tarjetas, y realizar operaciones relacionadas con 3DS.
 */
class GREENPAYSERVICES
{

    /**
     * Ruta para las solicitudes.
     *
     * @var string
     */
    private $path = "";

    /**
     * Constructor de la clase.
     *
     * Configura las variables de entorno dependiendo del ambiente (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza la transacción.
     * @param Producto $Producto   Objeto del producto asociado a la transacción.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $urlCancel  URL de redirección en caso de cancelación.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $externo = $Producto->externoId;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $Credentials = $this->Credentials($Usuario, $Producto);

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
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        date_default_timezone_set("UTC");
        $date = gmdate("Y-m-d\TH:i:s\Z");
        $mod_date = strtotime($date . "+ 2 days");
        $date_at = date('Y-m-d\TH:i:s', $mod_date);

        date_default_timezone_set('America/Bogota');

        $direccion = $Registro->direccion;

        if ($Registro->direccion == "") {
            $direccion = 'Direccion';
        }

        $data = array();
        $data['monto'] = $valorTax;
        $data['nombre'] = $Usuario->nombre;
        $data['email'] = $Usuario->login;
        $data['comprador_id'] = intval($Usuario->usuarioId);
        $data['direccion'] = $direccion;
        $data['referencia'] = $transproductoId . $Usuario->usuarioId;
        $data['orden_id'] = intval($transproductoId);
        $data['moneda'] = $Usuario->moneda;

        syslog(LOG_WARNING, "GREENPAY DATA: " . json_encode($data));

        $path = "/api/orden/store";

        $Result = $this->connectionPOST($data, $path, $Credentials);

        syslog(LOG_WARNING, "GREENPAY RESPONSE: " . $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result->url) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $Transaction->commit();
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);

            $ismobile = '';

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match(
                    '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)
                )) {
                $ismobile = '1';
            }

            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }

            $data_ = array();
            $data_["success"] = true;
            $data_["url"] = $Result->url;
        }
        return json_decode(json_encode($data_));
    }


    /**
     * Realiza una conexión POST con los datos proporcionados.
     *
     * @param array  $data Datos a enviar en la solicitud.
     * @param string $path Ruta del endpoint.
     *
     * @return string Respuesta del servidor.
     */
    public function connectionPOST($data, $path, $Credentials)
    {
        $data = json_encode($data);
        $url = $Credentials->URL . $path;

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
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'token: ' . $Credentials->KEY
            ),
        ]);

        $response = $curl->execute();

        return $response;
    }


    /**
     * Agrega autenticación para una transacción.
     *
     * @param Usuario  $Usuario     Objeto del usuario.
     * @param Producto $Producto    Objeto del producto.
     * @param string   $holder_name Nombre del titular de la tarjeta.
     * @param object   $datos       Datos adicionales de la transacción.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function AddAutentica(Usuario $Usuario, Producto $Producto, $holder_name, $datos)
    {
        $ed = $datos->data;
        $valor = $datos->amount;

        $Pais = new Pais($Usuario->paisId);
        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($mandante);
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $Credentials = $this->Credentials($Usuario, $Producto);

        $sessionID = time();
        $num = 20;

        $Ip = explode(',', $this->get_client_ip());
        $Ip = $Ip[0];

        $ServerIp = explode(",", $_SERVER["SERVER_ADDR"]);

        $Registro = new Registro("", $Usuario->usuarioId);

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

        $Email = $Registro->email;
        $Currency = $Usuario->moneda;
        $Cedula = $Registro->cedula;
        $codigoPostal = $Registro->codigoPostal;
        $Pais = $Pais->iso;
        $street1 = $Registro->direccion;

        $ciudadNom = '';
        try {
            $Ciudad = new Ciudad($Registro->ciudadId);
            $ciudadNom = $Ciudad->ciudadNom;
        } catch (\Exception $e) {
        }

        if ($mandante == '23') {
            $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
        }

        $ciudadNom = $ciudadNom ?: "Tegucigalpa";
        $Cedula = $Cedula ?: "0801123456785";
        $street1 = $street1 ?: "Avenida proceres";
        $codigoPostal = $codigoPostal ?: "11101";

        $data = [
            "secret" => $Credentials->KEY_3DS,
            "merchantId" => $Credentials->MERCHANT_KEY_3DS,
            "terminal" => $Credentials->TERMINAL,
            "amount" => intval($valorTax),
            "currency" => 'USD',
            "description" => "Deposito con tarjeta",
            "orderReference" => $transproductoId,
            "additional" => [
                "customer" => [
                    "name" => $holder_name,
                    "email" => $Email,
                    "identification" => $Cedula,
                    "billingAddress" => [
                        "country" => $Pais,
                        "province" => "Francisco Morazan",
                        "city" => $ciudadNom,
                        "street1" => $street1,
                        "street2" => "Calle 2",
                        "zip" => $codigoPostal
                    ],
                    "shippingAddress" => [
                        "country" => $Pais,
                        "province" => "Francisco Morazan",
                        "city" => $ciudadNom,
                        "street1" => $street1,
                        "street2" => "Calle 2",
                        "zip" => $codigoPostal
                    ]
                ],
                "products" => [
                    [
                        "description" => "Compra USD",
                        "skuId" => "591",
                        "quantity" => 1,
                        "price" => 662,
                        "type" => "TD"
                    ]
                ]
            ]
        ];

        $time = time();

        syslog(LOG_WARNING, "DATA GREENPAYCARD CREATEORDER : " . $time . ' ' . json_encode($data));

        $this->path = "/createOrder";
        $Result = $this->connectionCreateOrder($data, $this->path, $Credentials);

        syslog(LOG_WARNING, "RESPONSE GREENPAYCARD CREATEORDER : " . $time . ' ' . $Result);

        $Result = json_decode($Result);

        $this->path = "/3ds/setup";
        $sessionID = $Result->session;
        $sessionTokenOrder = $Result->token;

        $data = [
            "cardData" => [
                "session" => $sessionID,
                "ed" => $ed
            ]
        ];

        syslog(LOG_WARNING, "DATA GREENPAYCARD SETUP : " . $time . ' ' . json_encode($data));

        $verification3DS = $this->Verificacion3DS($data, $sessionTokenOrder, $sessionID, $this->path, $Credentials);

        syslog(LOG_WARNING, "RESPONSE GREENPAYCARD SETUP : " . $time . ' ' . $verification3DS);

        $result = json_decode($verification3DS);

        if ($result->statusCode == 200) {
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

            $Transaction->commit();

            $data_ = array();
            $data_["code"] = 0;
            $data_["success"] = true;
            $data_["result"] = $result->result;
            $data_["result"]->orderReference = $transproductoId;
        } else {
            $data_ = array();
            $data_["success"] = false;
            $data_["Message"] = "Error de deposito";
            $data_["code"] = 1;
        }

        return json_decode(json_encode($data_));
    }

    /**
     * Realiza el proceso de inscripción 3DS.
     *
     * @param Usuario  $Usuario     Objeto del usuario.
     * @param Producto $Producto    Objeto del producto.
     * @param string   $holder_name Nombre del titular de la tarjeta.
     * @param object   $datos       Datos adicionales de la transacción.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function Enrollment(Usuario $Usuario, Producto $Producto, $holder_name, $datos)
    {
        $time = time();

        $mandante = $Usuario->mandante;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URLREDIRECTION = 'https://sp-vsft-9213.virtualsoft.bet/';
        } else {
            $Mandante = new Mandante($mandante);
            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "gestion/deposito";
            }
        }

        $Credentials = $this->Credentials($Usuario, $Producto);

        $transaction = $datos->code;
        $cardData = $datos->cardData;
        $orderReference = $datos->orderReference;
        $referenceId = $datos->referenceId;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransprodLog = new TransprodLog("", $orderReference, 'Deposito');

        $this->path = "/3ds/enrollment";

        $tValue = $TransprodLog->tValue;
        $tValueArray = json_decode($tValue, true);

        $sessionID = $tValueArray['session'];
        $sessionTokenOrder = $tValueArray['token'];

        $card = [
            "session" => $sessionID,
            "ed" => $cardData
        ];

        $data = [
            "cardData" => $card,
            "referenceId" => $referenceId,
            "transactionId" => $transaction,
            "redirectURL" => $this->URLREDIRECTION
        ];

        syslog(LOG_WARNING, "DATA GREENPAYCARD ENROLLMENT : " . $time . ' ' . json_encode($data));

        $enrollment = $this->Verificacion3DS($data, $sessionTokenOrder, $sessionID, $this->path, $Credentials);

        syslog(LOG_WARNING, "RESPONSE GREENPAYCARD ENROLLMENT : " . $time . ' ' . $enrollment);

        $result = json_decode($enrollment);

        if ($result->result->transaction->status == 'SUCCESSFUL') {
            $status = $result->result->transaction->status;
            $this->confirm($orderReference, $status, $result);

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($orderReference);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Deposito');
            $TransprodLog->setTValue($result->id);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $Transaction->commit();

            $data_ = array();
            $data_["code"] = 0;
            $data_["success"] = true;
            $data_["result"] = $result->result;
            $data_["result"]->orderReference = $orderReference;
        } elseif ($result->result->status == 'PENDING_AUTHENTICATION') {
            $data_ = array();
            $data_["code"] = 0;
            $data_["success"] = true;
            $data_["result"] = $result->result;
            $data_["result"]->orderReference = $orderReference;
        } else {
            $data_ = array();
            $data_["success"] = false;
            $data_["Message"] = "Error en validacion Enrollment";
            $data_["code"] = 1;
        }

        return json_decode(json_encode($data_));
    }

    /**
     * Valida una transacción 3DS.
     *
     * @param Usuario  $Usuario     Objeto del usuario.
     * @param Producto $Producto    Objeto del producto.
     * @param string   $holder_name Nombre del titular de la tarjeta.
     * @param object   $datos       Datos adicionales de la transacción.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function validate(Usuario $Usuario, Producto $Producto, $holder_name, $datos)
    {
        $time = time();

        $mandante = $Usuario->mandante;

        $Mandante = new Mandante($mandante);
        if ($Mandante->baseUrl != '') {
            $this->URLREDIRECTION = $Mandante->baseUrl . "gestion/deposito";
        }

        $Credentials = $this->Credentials($Usuario, $Producto);

        $transaction = $datos->transactionId;
        $cardData = $datos->cardData;
        $orderReference = $datos->orderReference;
        $authenticationTransactionId = $datos->authenticationTransactionId;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransprodLog = new TransprodLog("", $orderReference, 'Deposito');

        $this->path = "/3ds/validate";

        $tValue = $TransprodLog->tValue;
        $tValueArray = json_decode($tValue, true);

        $sessionID = $tValueArray['session'];
        $sessionTokenOrder = $tValueArray['token'];

        $card = [
            "session" => $sessionID,
            "ed" => $cardData
        ];

        $data = [
            "cardData" => $card,
            "authenticationTransactionId" => $authenticationTransactionId,
            "transactionId" => $transaction,
            "additional" => [
                "channelData" => [
                    "channel" => '01',
                    "source" => 'API_CARD'
                ],
            ],
        ];

        syslog(LOG_WARNING, "DATA GREENPAYCARD VALIDATE : " . $time . ' ' . json_encode($data));

        $validate = $this->Verificacion3DS($data, $sessionTokenOrder, $sessionID, $this->path, $Credentials);

        syslog(LOG_WARNING, "RESPONSE GREENPAYCARD VALIDATE : " . $time . ' ' . $validate);

        $result = json_decode($validate);

        if ($result->result->transaction->status == 'SUCCESSFUL') {
            $status = $result->result->transaction->status;
            $this->confirm($orderReference, $status, $result);

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($orderReference);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Deposito');
            $TransprodLog->setTValue($result->id);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $Transaction->commit();

            $data_ = array();
            $data_["code"] = 0;
            $data_["success"] = true;
            $data_["result"] = $result->result;
            $data_["result"]->orderReference = $orderReference;
        } else {
            $data_ = array();
            $data_["success"] = false;
            $data_["Message"] = "Error en validate";
            $data_["code"] = 1;
        }

        return json_decode(json_encode($data_));
    }

    /**
     * Agrega una tarjeta de crédito al usuario.
     *
     * @param Usuario  $Usuario     Objeto del usuario.
     * @param Producto $Producto    Objeto del producto.
     * @param string   $holder_name Nombre del titular de la tarjeta.
     * @param integer  $ProveedorId ID del proveedor.
     * @param boolean  $saveCard    Indica si se debe guardar la tarjeta.
     * @param string   $requestId   ID de la solicitud.
     * @param string   $referenceId ID de referencia.
     * @param float    $valor       Monto de la transacción.
     * @param object   $datos       Datos adicionales de la transacción.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function addCard(Usuario $Usuario, Producto $Producto, $holder_name, $ProveedorId, $saveCard, $requestId, $referenceId, $valor, $datos)
    {
        $ed = $datos->data;

        $Pais = new Pais($Usuario->paisId);
        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($mandante);
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $Proveedor = new Proveedor($ProveedorId);

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Credentials = $this->Credentials($Usuario, $Producto);

        $sessionID = time();
        $num = 20;

        $Ip = explode(',', $this->get_client_ip());
        $Ip = $Ip[0];

        $ServerIp = explode(",", $_SERVER["SERVER_ADDR"]);

        $Registro = new Registro("", $Usuario->usuarioId);

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

        $firstName = str_replace('ñ', 'n', $Registro->nombre1);
        $lastName = str_replace('ñ', 'n', $Registro->apellido1);
        $Celular = $Registro->celular;
        $Email = $Registro->email;
        $Currency = $Usuario->moneda;
        $Cedula = $Registro->cedula;
        $codigoPostal = $Registro->codigoPostal;
        $Pais = $Pais->iso;

        $ciudadNom = '';
        try {
            $Ciudad = new Ciudad($Registro->ciudadId);
            $ciudadNom = $Ciudad->ciudadNom;
        } catch (\Exception $e) {
        }

        //Data Crear Orden
        if ($saveCard == true) {
            $data = [
                "secret" => $Credentials->KEY_3DS,
                "merchantId" => $Credentials->MERCHANT_KEY_3DS,
                "terminal" => $Credentials->TERMINAL,
                "amount" => intval($valorTax),
                "currency" => $Currency,
                "description" => "Deposito con tarjeta",
                "orderReference" => $transproductoId,
                "additional" => [
                    "customer" => [
                        "name" => $holder_name,
                        "email" => $Email,
                        "identification" => $Cedula,
                        "billingAddress" => [
                            "country" => $Pais,
                            "province" => "Nombre de la provincia",
                            "city" => $ciudadNom,
                            "street1" => $Registro->direccion,
                            "street2" => "",
                            "zip" => $codigoPostal
                        ],
                        "shippingAddress" => [
                            "country" => $Pais,
                            "province" => "Nombre de la provincia",
                            "city" => $ciudadNom,
                            "street1" => $Registro->direccion,
                            "street2" => "Dirección Calle 2",
                            "zip" => $codigoPostal
                        ]
                    ],
                    "products" => [
                        [
                            "description" => "Compra de prueba-USD",
                            "skuId" => "591",
                            "quantity" => 1,
                            "price" => 662,
                            "type" => "TD"
                        ]
                    ]
                ]
            ];
        } else {
            $data = [
                "secret" => $Credentials->KEY_3DS,
                "merchantId" => $Credentials->MERCHANT_KEY_3DS,
                "terminal" => $Credentials->TERMINAL,
                "amount" => intval($valorTax),
                "currency" => $Currency,
                "description" => "Deposito con tarjeta",
                "orderReference" => $transproductoId,
                "additional" => [
                    "customer" => [
                        "name" => $holder_name,
                        "email" => $Email,
                        "identification" => $Cedula,
                        "billingAddress" => [
                            "country" => $Pais,
                            "province" => "Nombre de la provincia",
                            "city" => $ciudadNom,
                            "street1" => $Registro->direccion,
                            "street2" => "",
                            "zip" => $codigoPostal
                        ],
                        "shippingAddress" => [
                            "country" => $Pais,
                            "province" => "Nombre de la provincia",
                            "city" => $ciudadNom,
                            "street1" => $Registro->direccion,
                            "street2" => "Dirección Calle 2",
                            "zip" => $codigoPostal
                        ]
                    ],
                    "products" => [
                        [
                            "description" => "Compra de prueba-USD",
                            "skuId" => "591",
                            "quantity" => 1,
                            "price" => 662,
                            "type" => "TD"
                        ]
                    ]
                ]
            ];
        }

        $time = time();

        syslog(LOG_WARNING, "DATA GREENPAYCARD CREATEORDER : " . $time . ' ' . json_encode($data));

        $this->path = "/createOrder";
        $Result = $this->connectionCreateOrder($data, $this->path, $Credentials);

        syslog(LOG_WARNING, "RESPONSE GREENPAYCARD CREATEORDER : " . $time . ' ' . $Result);

        $Result = json_decode($Result);

        $sessionID = $Result->session;
        $sessionTokenOrder = $Result->token;

        $verification3DS = $this->Verificacion3DS($ed, $sessionTokenOrder, $sessionID, "", $Credentials);

        $result = json_decode($verification3DS);

        if ($result->statusCode == 200) {
            $data = array();
            $data["code"] = 0;
            $data["jwt"] = $result->jwt;
            $data["deviceDataCollectionUrl"] = $result->deviceDataCollectionUrl;
        }


        if ($saveCard == true) {
            $UsuarioTarjetacredito = new UsuarioTarjetacredito();
            $numTarjeta = substr_replace($numTarjeta, '********', 4, 8);
            $UsuarioTarjetacredito->setUsuarioId($Usuario->usuarioId);
            $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
            $UsuarioTarjetacredito->setCuenta($numTarjeta);
            $UsuarioTarjetacredito->setCvv('');
            $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
            $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($Result->result->cardID . '_' . $Result->information->customerID)); //pendiente
            $UsuarioTarjetacredito->setEstado('A');
            $UsuarioTarjetacredito->setUsucreaId('0');
            $UsuarioTarjetacredito->setUsumodifId('0');
            $UsuarioTarjetacredito->setDescripcion("");

            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
            $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
        }

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario('Deposito');
        $TransprodLog->setTValue($Result->_id);
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
        $TransprodLogMySqlDAO->insert($TransprodLog);
        $Transaction->commit();

        if ($Result->_id != "") {
            $invoice = $Result->description;
            $documento_id = $Result->_id;
            $amount = $Result->amount;

            if ($Result->status == "APPROVED") {
                $status = "APPROVED";
            } else {
                $status = "CANCELED";
            }

            $Paygate = new Paygate($invoice, $Usuario->usuarioId, $documento_id, $amount, "", $status);
            $Paygate->confirmation($Result);

            if ($Result->status == "APPROVED") {
                $data = array();
                $data["success"] = true;
                $data["Message"] = "";
                $data["Id"] = "";
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error de deposito";
            }
        } else {
            $data = array();
            $data["success"] = false;
            $data["Message"] = "Error de deposito";
        }

        return json_decode(json_encode($data));
    }

    /**
     * Confirma una transacción.
     *
     * @param string $orderReference Referencia de la orden.
     * @param string $status         Estado de la transacción.
     * @param object $result         Resultado de la transacción.
     *
     * @return void
     */
    public function confirm($orderReference, $status, $result)
    {
        if ($status == "SUCCESSFUL") {
            $status = "SUCCESS";
        } else {
            $status = "CANCEL";
        }

        $externo_Id = $result->id;

        $GreenPay = new Greenpay($orderReference, $status, $externo_Id);
        $GreenPay->confirmation($result);
    }

    /**
     * Realiza la verificación 3DS.
     *
     * @param array  $data         Datos de la solicitud.
     * @param string $sessionToken Token de sesión.
     * @param string $sessionID    ID de la sesión.
     * @param string $path         Ruta del endpoint.
     *
     * @return string Respuesta del servidor.
     */
    public function Verificacion3DS($data, $sessionToken, $sessionID, $path, $Credentials)
    {
        $url = $Credentials->BASE_URL_3DS . $this->path;

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
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'session-token: ' . $sessionToken
            ],
        ]);

        //Ejecutarlasolicitud
        $response = $curl->execute();

        return $response;
    }


    /**
     * Crea una orden a través de la conexión 3DS.
     *
     * @param array  $data Datos de la orden.
     * @param string $path Ruta del endpoint.
     *
     * @return string Respuesta del servidor.
     */
    public function connectionCreateOrder($data, $path, $Credentials)
    {
        $data = json_encode($data);
        $url = $Credentials->BASE_URL_3DS . $path;

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
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene las credenciales del subproveedor para el usuario y producto especificados.
     *
     * @param Usuario  $Usuario  Objeto del usuario que realiza la operación.
     * @param Producto $Producto Objeto del producto asociado al pago.
     *
     * @return object Credenciales del subproveedor.
     */
    public function Credentials($Usuario, $Producto)
    {
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        return json_decode($SubproveedorMandantePais->getCredentials());
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    /**
     * Elimina tildes de una cadena de texto.
     *
     * @param string $cadena Cadena de texto a procesar.
     *
     * @return string Cadena de texto sin tildes.
     */
    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

}


