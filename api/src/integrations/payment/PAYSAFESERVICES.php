<?php

/**
 * Clase para gestionar servicios de integración con Paysafe.
 *
 * Este archivo contiene la implementación de la clase `PAYSAFESERVICES`, que permite
 * realizar solicitudes de pago a través de diferentes proveedores como Paysafe, Skrill
 * y PagoEfectivo. Incluye métodos para configurar el entorno, realizar solicitudes HTTP
 * y manejar transacciones de pago.
 *
 * @category Integración
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\proveedor;
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
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase principal para gestionar los servicios de pago de Paysafe.
 */
class PAYSAFESERVICES
{
    /**
     * URL base para las solicitudes de Paysafe.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de desarrollo para las solicitudes de Paysafe.
     *
     * @var string
     */
    private $URLDEV = 'https://api.test.paysafe.com/paymenthub/v1';

    /**
     * URL de producción para las solicitudes de Paysafe.
     *
     * @var string
     */
    private $URLPROD = 'https://api.paysafe.com/paymenthub/v1';

    /**
     * URL base para las solicitudes de Skrill.
     *
     * @var string
     */
    private $URL_S = "";

    /**
     * URL de desarrollo para las solicitudes de Skrill.
     *
     * @var string
     */
    private $URLSDEV = 'https://pay.skrill.com';

    /**
     * URL de producción para las solicitudes de Skrill.
     *
     * @var string
     */
    private $URLSPROD = 'https://pay.skrill.com';

    /**
     * URL base para las solicitudes de PagoEfectivo.
     *
     * @var string
     */
    private $URL_PE = "";

    /**
     * URL de desarrollo para las solicitudes de PagoEfectivo.
     *
     * @var string
     */
    private $URLPEDEV = 'https://pre1a.services.pagoefectivo.pe/v1/';

    /**
     * URL de producción para las solicitudes de PagoEfectivo.
     *
     * @var string
     */
    private $URLPEPROD = 'https://services.pagoefectivo.pe/v1/';


    /**
     * URL de callback base para las confirmaciones de Paysafe.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en entorno de desarrollo para las confirmaciones de Paysafe.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/paysafe/confirm/";

    /**
     * URL de callback en entorno de producción para las confirmaciones de Paysafe.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/paysafe/confirm/";

    /**
     * URL base para la gestión de depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL de gestión de depósitos en entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/gestion/deposito";

    /**
     * URL de gestión de depósitos en entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";


    /**
     * Metodo HTTP utilizado en las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * Ruta del endpoint para las solicitudes.
     *
     * @var string
     */
    private $path = "";

    /**
     * Usuario para la autenticación en las solicitudes.
     *
     * @var string
     */
    private $user = "";

    /**
     * Clave para la autenticación en las solicitudes.
     *
     * @var string
     */
    private $key = "";

    /**
     * Identificador del comerciante.
     *
     * @var string
     */
    private $merchant = "";

    /**
     * Identificador del comerciante en entorno de desarrollo.
     *
     * @var string
     */
    private $merchant_DEV = "198428459";

    /**
     * Identificador del comerciante en entorno de producción.
     *
     * @var string
     */
    private $merchant_PROD = "";

    /**
     * Usuario para Paysafe en entorno de desarrollo.
     *
     * @var string
     */
    private $userN_DEV = "pmle-1015090";

    /**
     * Usuario para Paysafe en entorno de producción.
     *
     * @var string
     */
    private $userN_PROD = "pmle-317662";

    /**
     * Clave de seguridad para Paysafe en entorno de desarrollo.
     *
     * @var string
     */
    private $security_keyN_DEV = "B-qa2-0-63a31674-0-302c021417b3c716c803415dccb9cf4a593e36c514e019e402141c6b3e34531ff0bf245533cc565aa06e1d21edfe";

    /**
     * Clave de seguridad para Paysafe en entorno de producción.
     *
     * @var string
     */
    private $security_keyN_PROD = "B-p1-0-637d0931-0-302c021472a02dd42a40e6cac28c23d2bd4317ff1392699302142f16ac65ad546a3b3d1c3b9db0345be592464f3a";

    /**
     * Usuario para Skrill en entorno actual.
     *
     * @var string
     */
    private $userS = "";

    /**
     * Usuario para Skrill en entorno de desarrollo.
     *
     * @var string
     */
    private $userS_DEV = "";

    /**
     * Usuario para Skrill en entorno de producción.
     *
     * @var string
     */
    private $userS_PROD = "";

    /**
     * Clave de seguridad para Skrill en entorno actual.
     *
     * @var string
     */
    private $security_keyS = "";

    /**
     * Clave de seguridad para Skrill en entorno de desarrollo.
     *
     * @var string
     */
    private $security_keyS_DEV = "VS-latam22.";

    /**
     * Clave de seguridad para Skrill en entorno de producción.
     *
     * @var string
     */
    private $security_keyS_PROD = "";

    /**
     * Identificador del servicio de PagoEfectivo en entorno actual.
     *
     * @var string
     */
    private $idServicePE = "";

    /**
     * Identificador del servicio de PagoEfectivo en entorno de desarrollo.
     *
     * @var string
     */
    private $idServiceDEVPE = "1973";

    /**
     * Identificador del servicio de PagoEfectivo en entorno de producción.
     *
     * @var string
     */
    private $idServicePRODPE = "20329";

    /**
     * Clave de acceso para PagoEfectivo en entorno actual.
     *
     * @var string
     */
    private $accessKeyPE = "";

    /**
     * Clave de acceso para PagoEfectivo en entorno de desarrollo.
     *
     * @var string
     */
    private $accessKeyDEVPE = "NDFjZjk2MDkyOWRiMDI0";

    /**
     * Clave de acceso para PagoEfectivo en entorno de producción.
     *
     * @var string
     */
    private $accessKeyPRODPE = "OTcxZTE0NmFhYTc2OWZk";

    /**
     * Clave secreta para PagoEfectivo en entorno actual.
     *
     * @var string
     */
    private $secretKeyPE = "";

    /**
     * Clave secreta para PagoEfectivo en entorno de desarrollo.
     *
     * @var string
     */
    private $secretKeyDEVPE = "5c/xalIjgWmWAz8LKBqymn12ZLgdD0d7RzuE1sJO";

    /**
     * Clave secreta para PagoEfectivo en entorno de producción.
     *
     * @var string
     */
    private $secretKeyPRODPE = "Ei+EJ6yocCslbLt4+uz7JCX61UhlFswYrENnjouq";


    /**
     * Constructor de la clase.
     *
     * Configura las variables de entorno dependiendo de si el entorno es de desarrollo
     * o producción. Inicializa las URLs, credenciales y claves necesarias para las
     * integraciones de pago.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL_S = $this->URLSDEV;
            $this->URL = $this->URLDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->merchant = $this->merchant_DEV;
            $this->userN = $this->userN_DEV;
            $this->security_keyN = $this->security_keyN_DEV;
            $this->userS = $this->userS_DEV;
            $this->security_keyS = $this->security_keyS_DEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
            $this->URL_PE = $this->URLPEDEV;
            $this->idServicePE = $this->idServiceDEVPE;
            $this->accessKeyPE = $this->accessKeyDEVPE;
            $this->secretKeyPE = $this->secretKeyDEVPE;
        } else {
            $this->URL_S = $this->URLSPROD;
            $this->URL = $this->URLPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->merchant = $this->merchant_PROD;
            $this->userN = $this->userN_PROD;
            $this->security_keyN = $this->security_keyN_PROD;
            $this->userS = $this->userS_PROD;
            $this->security_keyS = $this->security_keyS_PROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
            $this->URL_PE = $this->URLPEPROD;
            $this->idServicePE = $this->idServicePRODPE;
            $this->accessKeyPE = $this->accessKeyPRODPE;
            $this->secretKeyPE = $this->secretKeyPRODPE;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * Este metodo permite generar una solicitud de pago para un usuario y un producto
     * específico. Dependiendo del proveedor externo configurado, se realiza la integración
     * correspondiente (Paysafe, Skrill, PagoEfectivo).
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $urlCancel  URL de redirección en caso de cancelación.
     *
     * @return object Respuesta en formato JSON con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        if ($Registro->ciudadId == '0') {
            $Registro->ciudadId = '';
        }
        $ciudad = new Ciudad($Registro->ciudadId);

        $data_ = array();
        $data_["success"] = false;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

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
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $ip_ = $this->get_client_ip();
        $ip_ = explode(',', $ip_);
        $ip_ = $ip_[0];

        if ($Producto->getExternoId() == "PAYSAFE_0") {
            $valorTax = $valorTax * 100;
            $this->user = $this->userS;
            $this->key = $this->security_keyS;

            $moneda = $Usuario->moneda;

            if ($moneda == 'GTQ' || $moneda == 'CRC') {
                $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                $valorTax = ($valorTax * $PaisMandante->trmUsd);
                $moneda = 'USD';
            }

            $email = 'pay_to_email=' . $Registro->email;
            $amount = '&amount=' . $valorTax;
            $currency = '&currency=' . $moneda;
            $trans_id = '&transaction_id=' . $transproductoId;
            $return_url = '&return_url=' . $this->URLDEPOSIT;
            $cancel_url = '&cancel_url=' . $this->URLDEPOSIT;
            $status_url = '&status_url=' . $this->callback_url;
            $ShopId = '&digitalShopId=' . $transproductoId;
            $lang = '&language=' . $Usuario->idioma;

            $path = $email . $amount . $currency . $trans_id . $return_url . $cancel_url . $status_url . $ShopId . $lang;

            $return = $this->URL_S . '/?' . $path;

            syslog(LOG_WARNING, "PSKRILL DATA" . $return);

            $Result = $this->connectionSKRILL($path);

            syslog(LOG_WARNING, "PSKRILL RESPONSE" . $Result);

            $Result = json_decode($Result);


            if (empty($Result->code != '')) {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Envio Solicitud de deposito');
                $TransprodLog->setTValue($Result);
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);
                $Transaction->commit();

                $data_ = array();
                $data_["success"] = true;
                $data_["url"] = $return;
            }
        } elseif ($Producto->getExternoId() == "PAYSAFE_1") {
            $codigoPostal = $Registro->codigoPostal;

            if ($Usuario->paisId == 66) {
                if ($codigoPostal == '') {
                    $codigoPostal = '010103';
                }

                if ($ciudad->ciudadNom == '') {
                    $ciudad->ciudadNom = 'Quito';
                }
            }


            $this->user = $this->userN;
            $this->key = $this->security_keyN;

            $valorTax = $valorTax * 100;
            $moneda = $Usuario->moneda;

            $codigoPostal = $Registro->codigoPostal;
            if ($codigoPostal == "") {
                $codigoPostal = '000000';
            }

            if ($moneda == 'GTQ' || $moneda == 'CRC') {
                $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                $valorTax = ($valorTax * $PaisMandante->trmUsd);
                $moneda = 'USD';
            }

            $data = array();
            $data['merchantRefNum'] = $transproductoId;   //OK
            $data['transactionType'] = "PAYMENT";         //OK
            $data['neteller'] = [
                "consumerId" => $Registro->email,         //OK
                "consumerIdLocked" => false,              //OK
                "detail1Description" => "Deposito",       //OK
                "detail1Text" => $transproductoId,        //OK
            ];
            $data['paymentType'] = "NETELLER";            //OK
            $data['amount'] = $valorTax;//OK
            $data['currencyCode'] = $moneda;     //OK
            $data['customerIp'] = $ip_; //OK
            $data['billingDetails'] = [
                "street" => "18",                         //OK
                "street2" => "simple",                    //OK
                "city" => $ciudad->ciudadNom,             //OK
                "zip" => $codigoPostal,         //OK
                "country" => $Pais->iso,                  //OK
            ];
            $data['returnLinks'] = [
                [
                    "rel" => "on_completed",               //OK
                    "href" => $this->URLDEPOSIT,           //OK
                    "method" => 'POST',                    //OK
                ],
                [
                    "rel" => "on_failed",                  //OK
                    "href" => $this->URLDEPOSIT,           //OK
                    "method" => 'POST',                    //OK
                ],
                [
                    "rel" => "default",                    //OK
                    "href" => $this->URLDEPOSIT,           //OK
                    "method" => 'POST',                    //OK
                ]
            ];

            syslog(LOG_WARNING, "PNETELLER DATA" . json_encode($data));

            $this->path = "/paymenthandles";

            $Result = $this->connectionPOST($data);

            syslog(LOG_WARNING, "PNETELLER RESPONSE" . $Result);

            $Result = json_decode($Result);

            if ($Result != "" && $Result->status == "INITIATED") {
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

                $data_ = array();
                $data_["success"] = true;
                $data_["url"] = $Result->links[0]->href;
            }
        } elseif ($Producto->getExternoId() == "PAYSAFE_2") {
            ini_set('display_errors', 'OFF');

            $data = array();
            $data["success"] = false;
            $data["error"] = 1;

            try {
                $Registro = new Registro("", $Usuario->usuarioId);
                $Proveedor = new proveedor("", "PAYSAFE");

                $estado = 'A';
                $estado_producto = 'E';
                $tipo = 'T';

                $banco = 0;
                $Mandante = new Mandante($Usuario->mandante);
                $Pais = new Pais($Usuario->paisId);
                $codigoPais = $Pais->prefijoCelular;
                $usuario_id = $Usuario->usuarioId;
                $cedula = $Registro->cedula;
                $nombre = $Registro->nombre1;
                $apellido = $Registro->apellido1;
                $celular = $Registro->celular;
                $email = $Usuario->login;
                $valor = $valor;
                $producto_id = $Producto->productoId;
                $moneda = $Usuario->moneda;
                $mandante = $Usuario->mandante;
                $descripcion = "Deposito";
                $lenguaje = $Usuario->idioma;

                if ($Usuario->paisId == '66') {
                    $codigoPais = '593';
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

                date_default_timezone_set('America/Lima');


                $UTC = str_replace(" ", "T", date("Y-m-d H:i:s", time()));
                $UTC = $UTC . "-05:00";
                $this->method = "authorizations";

                $hashString = hash('sha256', $this->idServicePE . "." . $this->accessKeyPE . "." . $this->secretKeyPE . "." . $UTC, false);

                $array = [
                    "accessKey" => $this->accessKeyPE,
                    "idservice" => $this->idServicePE,
                    "dateRequest" => $UTC,
                    "hashString" => $hashString,
                ];

                $return = $this->request($array, $this->method, array('Content-Type:application/json'));

                if ($_ENV['debug']) {
                    print_r("\r\n");
                    print_r('****DATA REQUEST AUTH CIP****');
                    print_r(json_encode($array));
                    print_r("\r\n");
                    print_r('****RESPONSE****');
                    print_r(json_encode(json_decode($return)));
                    print_r("\r\n");
                    print_r('****SECRETKEY AUTH****');
                    print_r($this->secretKeyPE);
                }

                $return = json_decode($return);

                $tipoDocumento = $Registro->tipoDoc;

                //convertir el tipo de documento a los requeridos por el proveedor
                switch ($tipoDocumento) {
                    case "E":

                        if ($Pais->iso == "PE") {
                            $tipoDocumento = "CE";
                        }
                        break;

                    case "P": //OK

                        if ($Pais->iso == "EC") {
                            $tipoDocumento = "PAS";
                        } elseif ($Pais->iso == "CL") {
                            $tipoDocumento = "PP";
                        } elseif ($Pais->iso == "PE") {
                            $tipoDocumento = "PAS";
                        }
                        break;

                    case "C": //OK

                        if ($Pais->iso == "CL") {
                            $tipoDocumento = "RUT";
                        } elseif ($Pais->iso == "PE") {
                            $tipoDocumento = "DNI";
                        } elseif ($Pais->iso == "EC") {
                            $tipoDocumento = "NAN";
                        }
                        break;
                    default:
                        $tipoDocumento = "DNI";
                        break;
                }

                $this->method = "cips";

                if ($return != "") {
                    $array = array(
                        "currency" => $moneda,
                        "amount" => number_format($valorTax, 2, '.', ''),
                        "transactionCode" => $transproductoId,
                        "dateExpiry" => str_replace(' ', 'T', date('Y-m-d H:i:s' . "-05:00", time() + ((int)5 * 60 * 60))),//Este valor debe ser dinamico
                        "paymentConcept" => $Proveedor->abreviado,
                        "additionalData" => $Mandante->descripcion,
                        "userEmail" => $email,
                        "userId" => $usuario_id,
                        "userName" => $nombre,
                        "userLastName" => $apellido,
                        "userDocumentType" => $tipoDocumento,
                        "userDocumentNumber" => $cedula,
                        "userPhone" => $celular,
                        "userCodeCountry" => "+" . $codigoPais,
                        "serviceId" => intval($this->idServicePE),
                    );
                } else {
                    throw new Exception("No se autorizo", "11101");
                }

                $return = $this->request($array, $this->method, array('Content-Type:application/json', 'Accept-Language:' . $lenguaje, 'Origin: web', 'Authorization:' . "Bearer" . " " . $return->data->token));

                if ($_ENV['debug']) {
                    print_r("\r\n");
                    print_r('****DATA REQUEST GENERAR CIP****');
                    print_r(json_encode($array));
                    print_r("\r\n");
                    print_r('****RESPONSE****');
                    print_r(json_encode(json_decode($return)));
                }

                $return = json_decode($return);

                if ($return->code == 100) {
                    $OrdenID = $return->data->cip;

                    $respuesta = array();
                    $respuesta["status"] = 201;
                    $respuesta["orden"] = $OrdenID;

                    $TransaccionProducto->setExternoId($OrdenID);
                    $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                } else {
                    $apierror = true;

                    // There was some error so check the message and log it
                    $respuesta = array();
                    $respuesta["status"] = 0;
                    //$respuesta["msg"] = json_decode($return);
                }

                if ( ! $apierror) {
                    $t_value = json_encode($respuesta);

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
                    $data["url"] = $return->data->cipUrl;
                }
            } catch (Exception $e) {
            }
        }

        return json_decode(json_encode($data));
    }


    /**
     * Realiza una solicitud HTTP POST.
     *
     * Este metodo envía datos en formato JSON a una URL configurada utilizando cURL.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function connectionPOST($data)
    {
        $data = json_encode($data);
        $header = array(
            "Content-Type: application/json",
            'Authorization: Basic ' . base64_encode($this->user . ':' . $this->key)
        );
        $url = $this->URL . $this->path;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


    /**
     * Realiza una conexión con Skrill.
     *
     * Este metodo envía datos codificados en formato `x-www-form-urlencoded` a Skrill
     * utilizando cURL.
     *
     * @param string $path Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function connectionSKRILL($path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL_S,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $path,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Realiza una solicitud HTTP personalizada.
     *
     * Este metodo permite enviar datos en formato JSON a una URL específica con
     * encabezados personalizados utilizando cURL.
     *
     * @param array  $data   Datos a enviar en la solicitud.
     * @param string $method Metodo de la API a invocar.
     * @param array  $header Encabezados HTTP adicionales.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function request($data, $method, $header = array())
    {
        if ($_ENV['debug']) {
            print_r("\r\n");
            print_r('****URL API****');
            print_r(json_encode(($this->URL_PE . $method)));
        }

        $data = json_encode($data);
        $ch = curl_init($this->URL_PE . $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este metodo detecta y devuelve la dirección IP del cliente desde las variables
     * de entorno disponibles.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
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

}


