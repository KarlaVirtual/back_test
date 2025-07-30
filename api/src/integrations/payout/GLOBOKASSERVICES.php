<?php

/**
 * Clase que proporciona servicios de integración con GLOBOKAS para la gestión de pagos y retiros.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Exception;

/**
 * Clase que proporciona servicios de integración con GLOBOKAS para la gestión de pagos y retiros.
 */
class GLOBOKASSERVICES
{

    /**
     * Credenciales de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_loginDEV = "";

    /**
     * Contraseña de inicio de sesión para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_passwordDEV = "";

    /**
     * Credenciales de inicio de sesión para el entorno de producción.
     *
     * @var string
     */
    private $api_login = "";

    /**
     * Contraseña de inicio de sesión para el entorno de producción.
     *
     * @var string
     */
    private $api_password = "";

    /**
     * Metodo HTTP utilizado en las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * Nombre de usuario actual.
     *
     * @var string
     */
    private $username = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "434027f4732ec468840a36b15cbcd83715559738";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "";

    /**
     * Token de autorización para las solicitudes.
     *
     * @var string
     */
    private $authorization = "";

    /**
     * URL del servicio de tokens en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEVTOKEN = 'https://qasecurityservice-aks.globokas.tech';

    /**
     * URL del servicio principal en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://qaodepa-aks.globokas.tech';

    /**
     * URL del servicio principal en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = "https://odepa.agentekasnet.com";

    /**
     * URL del servicio de tokens en el entorno de producción.
     *
     * @var string
     */
    private $URLPRODTOKEN = 'https://securityservice.agentekasnet.com';

    /**
     * URL actual del servicio principal.
     *
     * @var string
     */
    private $URL = '';

    /**
     * URL actual del servicio de tokens.
     *
     * @var string
     */
    private $URLTOKEN = '';

    /**
     * Constructor de la clase.
     *
     * Configura las URLs según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->URLTOKEN = $this->URLDEVTOKEN;
        } else {
            $this->URL = $this->URLPROD;
            $this->URLTOKEN = $this->URLPRODTOKEN;
        }
    }

    /**
     * Realiza un retiro de efectivo.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene los datos de la cuenta de cobro.
     *
     * @return void
     * @throws Exception Si no se puede realizar la transacción.
     */
    public function cashOut(CuentaCobro $CuentaCobro)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Mandante = $Usuario->mandante;

        $Proveedor = new Proveedor('', 'GLOBOKASRETIROS');
        $Subproveedor = new Subproveedor('', 'GLOBOKASRETIROS');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
        $Producto = new Producto($CuentaCobro->productoPagoId);

        $Detalle = $Subproveedor->detalle;
        $Detalle = json_decode($Detalle);
        $this->username = $Detalle->username;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = doubleval($CuentaCobro->getValorAPagar());
        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valorFinal);
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $CuentaCobro->setTransproductoId($transproductoId);


        $order_id = $transproductoId;
        $credit_note = $CuentaCobro->getCuentaId();
        $account_type = "Cuenta de Ahorros";
        $vat_id = $Registro->cedula;
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $LastName = $Registro->apellido1;
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $channel = 1;
        $user_email = $Registro->email;
        $phone_number = $Registro->celular;

        $pais = new Pais($Usuario->paisId);

        if (strtoupper($pais->iso) == "PE") {
            $pais->iso = "PER";
            $currency = "604";
        }
        if (strtoupper($pais->iso) == "EC") {
            $pais->iso = "ECU";
            $currency = "608";
        }
        $tipoDoc = "";

        switch ($Registro->getTipoDoc()) {
            case "E":
                $tipoDoc = "CE";
                break;
            case "P":
                $tipoDoc = "PASAPORTE";
                break;
            default:
                $tipoDoc = "DNI";
                break;
        }

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $client_id = "INTERPLAY WORD SAC";
            $client_secret = "Js9#Hn6/Gq6?";
            $grant_type = "password";
            $username = "it@virtualsoft.tech";
            $password = "Ww0)We7;Um2¡";
            $scope = "PaymentOrder";
        } else {
            $client_id = "Interplay Word SAC";
            $client_secret = "Ds0,Bt3(Kq3%";
            $grant_type = "password";
            $username = "it@virtualsoft.tech";
            $password = "Nb5-Zm7#Wn5$";
            $scope = "PaymentOrder";
        }


        $fecha = date("Y-m-d");
        $fechafinal = date("Y-m-d", strtotime($fecha . "+1 week"));

        $dataToken = "client_id=" . $client_id . "&client_secret=" . $client_secret . "&grant_type=" . $grant_type . "&username=" . $username . "&password=" . $password . "&scope=" . $scope;

        $respuesta = $this->requestFormData(
            "/connect/token",
            $dataToken,
            array("Content-Type: application/x-www-form-urlencoded")
        );

        $respuestaToken = json_decode($respuesta);
        $feeAmount = 0.00;
        $itfAmount = 0.00;
        $accessToken = $respuestaToken->access_token;
        $this->authorization = $accessToken; // Prepare the authorisation token
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $data = array(
            "guid" => $guid,
            //Identificador o trace enviado por el Cliente.
            "identifier" => strval($Registro->getCedula()),
            //Número de documento del beneficiario el cual es el identificador para consultar y cobrar la orden de pago.
            "identifierType" => $tipoDoc,
            // Tipo del documento.
            "description" => 'RETIRO DE ORDEN DE PAGO',
            //Descripción del pago.
            "invoiceNumber" => $transproductoId,
            //Número de recibo. Identificador único de la orden de pago que no debe repetirse en la carga.
            "finalAmountDue" => $amount - $feeAmount,
            //Monto total final de la orden de pago (Redondeado: Céntimos múltiplo de 10).
            "amountDue" => round($valorFinal, 2),
            //Monto total final de la orden de pago
            "itfAmount" => $itfAmount,
            //ITF (Redondeado: Céntimos múltiplo de 10). Ejemplo: 0.0
            "feeAmount" => $feeAmount,
            // Comisión Empresa (Redondeado: Céntimos múltiplo de 10).
            "exchangeRate" => 1,
            //Tipo de cambio. Valor por defecto = 1
            "currencyCode" => $currency,
            "clientName" => $Registro->nombre1,
            "clientEmail" => $user_email,
            "clientCellPhone" => $phone_number,
            "order" => intval($transproductoId),
            //Orden de prioridad si hubiera más de una orden para un mismo beneficiario (número de documento), por ejemplo: 1, 2, 3, 4….., 100000
            "emissionDate" => $fecha,
            //Fecha de inicio de vigencia de la orden. Formato: AAAA-MM-DD
            "dueDate" => $fechafinal,
            //Fecha de fin de vigencia de la orden. Formato: AAAA-MM-DD
            "marketingCampaign" => "ORDEN DE PAGO"
        );

        $respuesta = $this->request("/v1.0/ApiPaymentOrders/PaymentOrder", $data);


        print_r($respuesta);
        $respuesta = json_decode($respuesta);

        if ($respuesta->success == true) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($respuesta));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($respuesta->result);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Obtiene un token de autorización.
     *
     * @return string Token de autorización.
     * @throws Exception Si no se puede obtener el token.
     */
    public function Token()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $client_id = "INTERPLAY WORD SAC";
            $client_secret = "Js9#Hn6/Gq6?";
            $grant_type = "password";
            $username = "it@virtualsoft.tech";
            $password = "Ww0)We7;Um2¡";
            $scope = "PaymentOrder";
        } else {
            $client_id = "Interplay Word SAC";
            $client_secret = "Ds0,Bt3(Kq3%";
            $grant_type = "password";
            $username = "it@virtualsoft.tech";
            $password = "Nb5-Zm7#Wn5$";
            $scope = "PaymentOrder";
        }

        $dataToken = "client_id=" . $client_id . "&client_secret=" . $client_secret . "&grant_type=" . $grant_type . "&username=" . $username . "&password=" . $password . "&scope=" . $scope;

        $respuesta = $this->requestFormData(
            "/connect/token",
            $dataToken,
            array("Content-Type: application/x-www-form-urlencoded")
        );

        $respuestaToken = json_decode($respuesta);
        $accessToken = $respuestaToken->access_token;
        $this->authorization = $accessToken;
        return $this->authorization;
    }

    /**
     * Lista las órdenes procesadas.
     *
     * @param string $identifier Identificador del usuario.
     * @param string $startDate  Fecha de inicio del rango.
     * @param string $endDate    Fecha de fin del rango.
     * @param int    $pageNumber Número de página.
     * @param int    $pageSize   Tamaño de la página.
     *
     * @return object Respuesta del servicio con las órdenes procesadas.
     */
    public function ListProcessed($identifier, $startDate, $endDate, $pageNumber, $pageSize)
    {
        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $data = array(
            "guid" => $guid,
            "identifier" => $identifier,
            "pageNumber" => $pageNumber,
            "pageSize" => $pageSize,
            "startDate" => $startDate,
            "endDate" => $endDate
        );


        $respuesta = $this->request("/v1.0/ApiPaymentOrders/ListProcessed", $data);

        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Lista las órdenes pendientes.
     *
     * @param string $identifier Identificador del usuario.
     * @param string $startDate  Fecha de inicio del rango.
     * @param string $endDate    Fecha de fin del rango.
     * @param int    $pageNumber Número de página.
     * @param int    $pageSize   Tamaño de la página.
     *
     * @return object Respuesta del servicio con las órdenes pendientes.
     */
    public function ListPending($identifier, $startDate, $endDate, $pageNumber, $pageSize)
    {
        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        $data = array(
            "guid" => $guid,
            "identifier" => $identifier,
            "pageNumber" => $pageNumber,
            "pageSize" => $pageSize,
            "startDate" => $startDate,
            "endDate" => $endDate
        );

        $respuesta = $this->request("/v1.0/ApiPaymentOrders/ListPending", $data);

        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Elimina una orden de pago.
     *
     * @param string $identifier Identificador del usuario.
     * @param string $tipoDoc    Tipo de documento del usuario.
     * @param int    $order      Número de la orden a eliminar.
     *
     * @return void
     * @throws Exception Si no se puede eliminar la orden.
     */
    public function Delete2($identifier, $tipoDoc, $order)
    {
        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $data = array(
            "guid" => $guid,
            "identifier" => $identifier,
            "identifierType" => $tipoDoc,
            "order" => intval($order)
        );

        $respuesta = $this->requestDELETE("/v1.0/ApiPaymentOrders/PaymentOrder", $data);
        $respuesta = json_decode($respuesta);
    }

    /**
     * Elimina una orden de pago asociada a una cuenta de cobro.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene los datos de la cuenta de cobro.
     *
     * @return void
     * @throws Exception Si no se puede eliminar la orden.
     */
    public function Delete($CuentaCobro)
    {
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        switch ($Registro->getTipoDoc()) {
            case "E":
                $tipoDoc = "CE";
                break;
            case "P":
                $tipoDoc = "PASAPORTE";
                break;
            default:
                $tipoDoc = "DNI";
                break;
        }
        $TransaccionProducto = new TransaccionProducto($CuentaCobro->transproductoId);
        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $data = array(
            "guid" => $guid,
            "identifier" => $Registro->getCedula(),
            "identifierType" => $tipoDoc,
            "order" => intval($TransaccionProducto->transproductoId)
        );

        $respuesta = $this->requestDELETE("/v1.0/ApiPaymentOrders/PaymentOrder", $data);
        $respuesta = json_decode($respuesta);

    }

    /**
     * Reenvía el OTP para una orden de pago.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene los datos de la cuenta de cobro.
     *
     * @return object Respuesta del servicio con el resultado del reenvío.
     */
    public function ResendOtp($CuentaCobro)
    {
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        switch ($Registro->getTipoDoc()) {
            case "E":
                $tipoDoc = "CE";
                break;
            case "P":
                $tipoDoc = "PASAPORTE";
                break;
            default:
                $tipoDoc = "DNI";
                break;
        }
        $TransaccionProducto = new TransaccionProducto($CuentaCobro->transproductoId);

        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $data = array(
            "guid" => $guid,
            "identifier" => $Registro->getCedula(),
            "identifierType" => $tipoDoc,
            "order" => intval($TransaccionProducto->transproductoId)
        );

        $respuesta = $this->request("/v1.0/ApiPaymentOrders/PaymentOrder/ResendOtp", $data);


        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Edita el valor de una orden de pago.
     *
     * @param int   $Id    Identificador de la orden.
     * @param float $valor Nuevo valor de la orden.
     *
     * @return object Respuesta del servicio con el resultado de la edición.
     * @throws Exception Si no se puede realizar la transacción.
     */
    public function EditOrderPage($Id, $valor)
    {
        $CuentaCobro = new CuentaCobro($Id);

        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Mandante = $Usuario->mandante;

        $Subproveedor = new Subproveedor('', 'GLOBOKASRETIROS');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
        $Detalle = $Subproveedor->detalle;
        $Detalle = json_decode($Detalle);
        $this->username = $Detalle->username;


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto($CuentaCobro->transproductoId);

        $name = $Usuario->nombre;

        $user_email = $Registro->email;
        $phone_number = $Registro->celular;


        $pais = new Pais($Usuario->paisId);

        if (strtoupper($pais->iso) == "PE") {
            $pais->iso = "PER";
            $currency = "604";
        }
        if (strtoupper($pais->iso) == "EC") {
            $pais->iso = "ECU";
            $currency = "608";
        }
        $tipoDoc = "";

        switch ($Registro->getTipoDoc()) {
            case "E":
                $tipoDoc = "CE";
                break;
            case "P":
                $tipoDoc = "PASAPORTE";
                break;
            default:
                $tipoDoc = "DNI";
                break;
        }
        $feeAmount = 0.00;
        $itfAmount = 0.00;
        $fecha = date("Y-m-d");
        $fechafinal = date("Y-m-d", strtotime($fecha . "+1 week"));
        $data = array(
            "guid" => $guid,
            //Identificador o trace enviado por el Cliente.
            "identifier" => $Registro->getCedula(),
            //Número de documento del beneficiario el cual es el identificador para consultar y cobrar la orden de pago.
            "identifierType" => $tipoDoc,
            // Tipo del documento.
            "description" => 'RETIRO DE ORDEN DE PAGO',
            //Descripción del pago.
            "invoiceNumber" => $TransaccionProducto->transproductoId,
            //Número de recibo. Identificador único de la orden de pago que no debe repetirse en la carga.
            "finalAmountDue" => $valor - $feeAmount,
            //Monto total final de la orden de pago (Redondeado: Céntimos múltiplo de 10).
            "amountDue" => round($valor, 2),
            //Monto total final de la orden de pago
            "itfAmount" => $itfAmount,
            //ITF (Redondeado: Céntimos múltiplo de 10). Ejemplo: 0.0
            "feeAmount" => $feeAmount,
            // Comisión Empresa (Redondeado: Céntimos múltiplo de 10).
            "exchangeRate" => 1,
            //Tipo de cambio. Valor por defecto = 1
            "currencyCode" => $currency,
            "clientName" => $name,
            "clientEmail" => $user_email,
            "clientCellPhone" => $phone_number,
            "order" => intval($TransaccionProducto->transproductoId),
            //Orden de prioridad si hubiera más de una orden para un mismo beneficiario (número de documento), por ejemplo: 1, 2, 3, 4….., 100000
            "emissionDate" => $fecha,
            //Fecha de inicio de vigencia de la orden. Formato: AAAA-MM-DD
            "dueDate" => $fechafinal,
            //Fecha de fin de vigencia de la orden. Formato: AAAA-MM-DD
            "marketingCampaign" => "CAMBIO DEL VALOR EN ORDEN DE PAGO"
        );

        $respuesta = $this->requestPUT("/v1.0/ApiPaymentOrders/PaymentOrder", $data);

        $respuesta = json_decode($respuesta);


        if ($respuesta->success == true) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($TransaccionProducto->transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($respuesta));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($respuesta->result);
            $TransaccionProducto->setValor($valor);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $CuentaCobro->setValor($valor);
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
            $CuentaCobroMySqlDAO->update($CuentaCobro);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
            return $respuesta;
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Obtiene el balance actual.
     *
     * @return object Respuesta del servicio con el balance.
     */
    public function GetBalance()
    {
        $this->authorization = $this->Token();
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        $data = "guid=" . $guid;
        $respuesta = $this->requestGET("/v1.0/ApiPaymentOrders/GetBalance", $data);

        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Realiza una solicitud HTTP genérica.
     *
     * @param string $path      Ruta del endpoint.
     * @param array  $array_tmp Datos a enviar en la solicitud.
     * @param string $method    Metodo HTTP (por defecto "POST").
     *
     * @return string Respuesta del servicio.
     */
    public function request($path, $array_tmp, $method = "POST")
    {
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer $this->authorization"
        );
        $data = json_encode($array_tmp);
        print_r($data);
        print_r($this->URL . $path);

        syslog(LOG_WARNING, "GLOBOKASPAYOUT DATA PAYOUT " . ($data));

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = (curl_exec($ch));
        syslog(LOG_WARNING, "GLOBOKASPAYOUT DATA RESPONSE " . $result);
        print_r('result');
        print_r($result);

        return ($result);
    }

    /**
     * Realiza una solicitud HTTP DELETE.
     *
     * @param string $path      Ruta del endpoint.
     * @param array  $array_tmp Datos a enviar en la solicitud.
     * @param string $method    Metodo HTTP (por defecto "DELETE").
     *
     * @return string Respuesta del servicio.
     */
    public function requestDELETE($path, $array_tmp, $method = "DELETE")
    {
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer $this->authorization"
        );
        $data = json_encode($array_tmp);
        print_r($data);

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = (curl_exec($ch));
        print_r($result);

        return ($result);
    }

    /**
     * Realiza una solicitud HTTP PUT.
     *
     * @param string $path      Ruta del endpoint.
     * @param array  $array_tmp Datos a enviar en la solicitud.
     * @param string $method    Metodo HTTP (por defecto "PUT").
     *
     * @return string Respuesta del servicio.
     */
    public function requestPUT($path, $array_tmp, $method = "PUT")
    {
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer $this->authorization"
        );
        $data = json_encode($array_tmp);

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Realiza una solicitud HTTP GET.
     *
     * @param string $path      Ruta del endpoint.
     * @param array  $array_tmp Datos a enviar en la solicitud.
     * @param string $method    Metodo HTTP (por defecto "GET").
     *
     * @return string Respuesta del servicio.
     */
    public function requestGET($path, $array_tmp, $method = "GET")
    {
        $data = json_encode($array_tmp);
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer $this->authorization"
        );

        $data = str_replace('"', "", $data);

        $ch = curl_init($this->URL . $path . "?" . $data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Realiza una solicitud HTTP con datos en formato de formulario.
     *
     * @param string $path      Ruta del endpoint.
     * @param string $array_tmp Datos a enviar en la solicitud.
     * @param array  $header    Encabezados HTTP adicionales.
     * @param string $method    Metodo HTTP (por defecto "POST").
     *
     * @return string Respuesta del servicio.
     */
    public function requestFormData($path, $array_tmp, $header = array(), $method = "POST")
    {
        $ch = curl_init($this->URLTOKEN . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array_tmp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = (curl_exec($ch));

        return ($result);
    }
}
