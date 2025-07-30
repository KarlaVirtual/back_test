<?php

/**
 * Clase `N1COSERVICES` para la integración con el sistema de pagos N1CO.
 *
 * Este archivo contiene métodos para gestionar pagos, tarjetas de crédito,
 * autenticación y solicitudes relacionadas con transacciones.
 * Proporciona funcionalidades como agregar tarjetas, crear solicitudes de pago,
 * realizar pagos y eliminar tarjetas.
 *
 * @category Integración
 * @package  Backend\integrations\payment
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
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
use Backend\dto\PaisMandante;
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
 * Clase `N1COSERVICES`.
 *
 * Esta clase proporciona métodos para la integración con el sistema de pagos N1CO,
 * incluyendo la gestión de tarjetas de crédito, solicitudes de pago y transacciones.
 */
class N1COSERVICES
{
    /**
     * Nombre de usuario utilizado para la autenticación.
     *
     * @var string
     */
    private $accestoken = "";

    /**
     * Método utilizado en las solicitudes HTTP.
     *
     * @var string
     */
    private $metodo = "";


    /**
     * Constructor de la clase `N1COSERVICES`.
     *
     * Configura las credenciales y URLs dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Agrega una tarjeta de crédito al sistema.
     *
     * @param Usuario  $Usuario      Objeto del usuario.
     * @param Producto $Producto     Objeto del producto.
     * @param string   $numTarjeta   Número de la tarjeta.
     * @param string   $holder_name  Nombre del titular de la tarjeta.
     * @param integer  $expiry_month Mes de expiración.
     * @param integer  $expiry_year  Año de expiración.
     * @param string   $cvv          Código de seguridad de la tarjeta.
     * @param integer  $ProveedorId  ID del proveedor.
     * @param float    $valor        Valor de la transacción.
     * @param boolean  $saveCard     Indica si la tarjeta debe ser guardada.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function addCard(Usuario $Usuario, Producto $Producto, $numTarjeta, $holder_name, $expiry_month, $expiry_year, $cvv, $ProveedorId, $valor, $saveCard)
    {
        $Pais = new Pais($Usuario->paisId);
        $mandante = $Usuario->mandante;
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $Proveedor = new Proveedor($ProveedorId);

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Credentials = $this->credentials($Usuario);

        $Registro = new Registro("", $Usuario->usuarioId);

        $ciudadNom = '';
        try {
            $Ciudad = new Ciudad($Registro->ciudadId);
            $ciudadNom = $Ciudad->ciudadNom;
        } catch (\Exception $e) {
        }

        if ($ciudadNom == '') {
            $ciudadNom = 'Tegucigalpa';
        }

        $clientId = $Credentials->CLIENT_ID;
        $clientSecret = $Credentials->CLIENT_SECRET;
        $URL = $Credentials->URL;

        $requestAute = array(
            "clientId" => $clientId,
            "clientSecret" => $clientSecret,
        );
        $this->metodo = "Token";
        $tokenAute = $this->Autenticacion($requestAute, $URL);
        $tokenAute = json_decode($tokenAute);
        $this->accestoken = $tokenAute->accessToken;
        $sessionID = time();
        $Ip = explode(",", $Usuario->dirIp);
        $num = 20;

        if ($saveCard === true) {
            $data = array(
                "customer" => array(
                    "id" => $Usuario->usuarioId,
                    "email" => $Registro->email,
                    "name" => $Registro->nombre,
                    "phoneNumber" => $Registro->celular
                ),
                "card" => array(
                    "number" => $numTarjeta,
                    "cardHolder" => "$Usuario->nombre",
                    "expirationMonth" => strval($expiry_month),
                    "expirationYear" => strval($num . $expiry_year),
                    "cvv" => $cvv,
                    "singleUse" => false,
                ),

            );

            $this->metodo = "PaymentMethods";

            $Result = $this->connection($data, $URL);

            $Result = json_decode($Result);

            if ($Result->success == true) {
                $UsuarioTarjetacredito = new UsuarioTarjetacredito();
                $numTarjeta = substr_replace($numTarjeta, '********', 4, 8);
                $UsuarioTarjetacredito->setUsuarioId($Usuario->usuarioId);
                $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
                $UsuarioTarjetacredito->setCuenta($numTarjeta);
                $UsuarioTarjetacredito->setCvv('');
                $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
                $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($Result->id));
                $UsuarioTarjetacredito->setEstado('A');
                $UsuarioTarjetacredito->setUsucreaId('0');
                $UsuarioTarjetacredito->setUsumodifId('0');
                $UsuarioTarjetacredito->setDescripcion("");

                $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
                $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
                $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();

                $log = "";
                $log = $log . "Respuesta N1CO" . time();

                $log = $log . "\r\n" . "-------------------------" . "\r\n";
                $log = $log . (json_encode($Result));

                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
            }
        } else {
            $data = array(
                "customer" => array(
                    "id" => $Usuario->usuarioId,
                    "email" => $Registro->email,
                    "name" => $Registro->nombre,
                    "phoneNumber" => $Registro->celular
                ),
                "card" => array(
                    "number" => $numTarjeta,
                    "cardHolder" => "$Usuario->nombre",
                    "expirationMonth" => strval($expiry_month),
                    "expirationYear" => strval($num . $expiry_year),
                    "cvv" => $cvv,
                    "singleUse" => true,
                ),

            );
            $this->metodo = "PaymentMethods";

            $Result = $this->connection($data, $URL);

            $Result = json_decode($Result);
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
        $Mandante = new Mandante($Usuario->mandante);
        $UsuarioTarjetacredito = new UsuarioTarjetacredito("", $Usuario->usuarioId, $Proveedor->proveedorId);

        if ($Usuario->mandante == '27' && $Usuario->paisId == '68') {
            $Mandante->baseUrl = 'https://ganaplay.sv/';
        }

        if ($Usuario->mandante == '27' && $Usuario->paisId == '94') {
            $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
        }

        $valorTax = round($valorTax, 2);

        $Secure = array(
            "orderInformation" => array(
                "code" => $transproductoId,
                "currency" => $Usuario->moneda,
                "totalAmount" => $valorTax
            ),
            "clientInformation" => array(
                "address" => $Registro->direccion,
                "locality" => $ciudadNom,
                "firstName" => $Registro->nombre1,
                "lastName" => $Registro->apellido1,
                "phoneNumber" => $Pais->prefijoCelular . "" . $Registro->celular,
                "email" => $Registro->email,
                "country" => $Pais->paisNom,
                "billState" => "HN",
                "billZip" => $Registro->codigoPostal,
                "shipToZip" => $Registro->codigoPostal,
            ),
            "card" => array(
                "cardId" => $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token)
            ),
            "returnUrl" => $Mandante->baseUrl . "/gestion/deposito/correcto"
        );

        $this->metodo = "Setup3ds";

        $Result = $this->connection($Secure, $URL);
        $Result = json_decode($Result);


        $authenticationId = explode("authentication/", $Result->authenticationUrl);

        $TransaccionProducto->setExternoId($authenticationId[1]);

        $TransaccionProductoMySqlDAO->update($TransaccionProducto);

        $Result->tokenHeader = $this->accestoken;


        if ($Result->status == "OK") {
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
            $data = array();
            $data["success"] = true;
            $data["Message"] = $Result->authenticationUrl;
            return json_decode(json_encode($data));
        } else {
            $data = array();
            $data["success"] = false;
            $data["Message"] = '';
            return json_decode(json_encode($data));
        }
    }

    /**
     * Crea una solicitud de pago con un enlace de checkout.
     *
     * @param Usuario  $Usuario    Objeto del usuario.
     * @param Producto $Producto   Objeto del producto.
     * @param float    $valor      Valor de la transacción.
     * @param string   $urlSuccess URL de éxito.
     * @param string   $urlFailed  URL de fallo.
     * @param string   $cancel_url URL de cancelación.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function createRequestPayment2(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $cancel_url)
    {
        $PaisID = $Usuario->paisId;
        $mandante = $Usuario->mandante;

        $Credentials = $this->credentials($Usuario);

        $TOKEN = $Credentials->TOKEN;
        $URL_CHECK = $Credentials->URL_CHECK;

        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($mandante);

        if ($Usuario->mandante == '27' && $Usuario->paisId == '68') {
            $Mandante->baseUrl = 'https://ganaplay.sv/';
        }

        $urlSuccess = $Mandante->baseUrl . 'gestion/deposito/correcto';
        $cancel_url = $Mandante->baseUrl . 'gestion/deposito/pendiente';

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $valor = $valor;

        $producto_id = $Producto->productoId;
        $descripcion = "Deposito";

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

        if ($Usuario->mandante == '27' && $Usuario->paisId == '94') {
            $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
        }

        $dataP = array(
            "orderReference" => $transproductoId,
            "orderName" => $Usuario->nombre,
            "orderDescription" => $descripcion,
            "amount" => $valorTax,
            "successUrl" => $urlSuccess,
            "cancelUrl" => $cancel_url,
            "metadata" => array(
                array(
                    "name" => $Usuario->nombre,
                    "value" => strval($valorTax),
                    "mandante" => $mandante
                )
            )
        );

        syslog(LOG_WARNING, "N1CO DATA: " . $Usuario->usuarioId . json_encode($dataP));

        $path = '/api/paymentlink/checkout';

        $Result = $this->connectionPay(json_encode($dataP), $path, $TOKEN, $URL_CHECK);

        syslog(LOG_WARNING, "N1CO RESPONSE: " . $Usuario->usuarioId . $Result);

        $Result = json_decode($Result);

        if ($Result != '' && $Result->paymentLinkUrl != '') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($Result->orderId);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = $Result->paymentLinkUrl;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza un pago utilizando la información proporcionada.
     *
     * @param string $status           Estado del pago (SUCCESS, FAILED, PENDING).
     * @param string $authenticationId ID de autenticación.
     * @param string $orderId          ID del pedido.
     * @param float  $orderAmount      Monto del pedido.
     *
     * @return array Resultado del pago.
     */
    public function makePayment($status, $authenticationId, $orderId, $orderAmount)
    {
        $transaccionID = explode("-", $orderId);

        $Proveedor = new Proveedor("", "N1CO");
        $Producto = new Producto("", "Tarjetas", $Proveedor->proveedorId);

        $TransaccionProducto = new TransaccionProducto($transaccionID[3]);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Credentials = $this->credentials($Usuario);

        $URL = $Credentials->URL;

        $rules = [];
        array_push($rules, array("field" => "transprod_log.transproducto_id", "data" => $TransaccionProducto->transproductoId, "op" => "eq"));

        $SkeepRows = 0;
        $MaxRows = 1;

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonproduct = json_encode($filtro);

        $TransprodLog = new TransprodLog();

        $transprod = $TransprodLog->getTransLogCustom("transprod_log.* ", "transprod_log.transproducto_id", "asc", $SkeepRows, $MaxRows, $jsonproduct, true);
        $transprod = json_decode($transprod);

        $final = json_decode($transprod->data[0]->t_value);

        $this->accestoken = $final->tokenHeader;

        if ($authenticationId != "") {
            if ($status == "SUCCESS") {
                $UsuarioTarjetacredito = new UsuarioTarjetacredito("", $Usuario->usuarioId, $Proveedor->proveedorId);

                $makepayment = array(
                    "customerId" => $Usuario->usuarioId,
                    "order" => array(
                        "id" => $TransaccionProducto->transproductoId,
                        "name" => "Deposito",
                        "amount" => $orderAmount,
                        "description" => "Pago de deposito",
                    ),
                    "cardId" => $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token),
                    "authenticationId" => $authenticationId,
                );
                $this->metodo = "Charges";

                $Result = $this->connectionChanges($makepayment, $URL);

                $Result = json_decode($Result);

                if ($Result->status == "SUCCEEDED") {
                    $invoice = $TransaccionProducto->transproductoId;
                    $documento_id = $Result->orderId;
                    $amount = $Result->amount;
                    $result = $Result->status;

                    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                    $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
                    $TransaccionProducto->setExternoId($documento_id);

                    $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                    $Transaction->commit();

                    $N1CO = new N1CO($invoice, $Usuario->usuarioId, $documento_id, $amount, "", $result);
                    $N1CO->confirmation(json_encode($Result));

                    $data = array();
                    $data["success"] = true;
                    $data["Message"] = "";

                    $log = "";
                    $log = $log . "Respuesta N1CO" . time();

                    $log = $log . "\r\n" . "-------------------------" . "\r\n";
                    $log = $log . ($Result);

                    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
                } else {
                    $data = array();
                    $data["success"] = false;
                    $data["Message"] = "Error de deposito";
                }

                return json_decode(json_encode($data));
            }
            if ($status == "FAILED") {
                $invoice = $TransaccionProducto->transproductoId;
                $documento_id = "";
                $amount = $TransaccionProducto->valor;
                $result = $status;
                $Result = array();
                $N1CO = new N1CO($invoice, $Usuario->usuarioId, $documento_id, $amount, "", $result);
                $N1CO->confirmation(json_encode($Result));
            }

            if ($status == "PENDING") {
                $invoice = $TransaccionProducto->transproductoId;
                $documento_id = "";
                $amount = $TransaccionProducto->valor;
                $result = $status;
                $Result = array();
                $N1CO = new N1CO($invoice, $Usuario->usuarioId, $documento_id, $amount, "", $result);
                $N1CO->confirmation(json_encode($Result));
            }
        }
    }

    /**
     * Crea una solicitud de checkout para un pago.
     *
     * @param Usuario  $Usuario    Objeto del usuario.
     * @param Producto $Producto   Objeto del producto.
     * @param float    $valor      Valor de la transacción.
     * @param string   $urlSuccess URL de éxito.
     * @param string   $urlFailed  URL de fallo.
     * @param string   $cancel_url URL de cancelación.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function createRequestCheckout(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $cancel_url)
    {
        $PaisID = $Usuario->paisId;
        $mandante = $Usuario->mandante;

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Credentials = $this->credentials($Usuario);

        $TOKEN_CHECKOUT = $Credentials->TOKEN_CHECKOUT;
        $URL_CHECK = $Credentials->URL_CHECK;

        $data["success"] = false;
        $data["error"] = 1;

        $Mandante = new Mandante($mandante);

        if ($Usuario->mandante == '27' && $Usuario->paisId == '68') {
            $Mandante->baseUrl = 'https://ganaplay.sv/';
        }
        $urlSuccess = $Mandante->baseUrl . 'gestion/deposito/correcto';
        $cancel_url = $Mandante->baseUrl . 'gestion/deposito/pendiente';

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $valor = $valor;

        $producto_id = $Producto->productoId;
        $descripcion = "Deposito";

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

        if ($Usuario->mandante == '27' && $Usuario->paisId == '94') {
            $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
        }

        $dataP = array(
            "orderReference" => $transproductoId,
            "orderName" => $Usuario->nombre,
            "orderDescription" => $descripcion,
            "amount" => $valorTax,
            "successUrl" => $urlSuccess,
            "cancelUrl" => $cancel_url,
            "metadata" => array(
                array(
                    "name" => $Usuario->nombre,
                    "value" => strval($valorTax),
                    "mandante" => $mandante
                )
            )
        );

        syslog(LOG_WARNING, "N1CO DATA: " . $Usuario->usuarioId . json_encode($dataP));

        $path = '/api/paymentlink/checkout';

        $Result = $this->connectionPay(json_encode($dataP), $path, $TOKEN_CHECKOUT, $URL_CHECK);

        syslog(LOG_WARNING, "N1CO RESPONSE: " . $Usuario->usuarioId . $Result);

        $Result = json_decode($Result);

        if ($Result != '' && $Result->paymentLinkUrl != '') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($Result->orderId);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = $Result->paymentLinkUrl;
        }


        return json_decode(json_encode($data));
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario  Objeto del usuario.
     * @param Producto $Producto Objeto del producto.
     * @param float    $valor    Valor de la transacción.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor)
    {
        $Proveedor = new Proveedor("", "N1CO");
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
        $valor = $valor . ".00";
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $Mandante = new Mandante;
        $descripcion = "Deposito";

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Credentials = $this->credentials($Usuario);

        $clientId = $Credentials->CLIENT_ID;
        $clientSecret = $Credentials->CLIENT_SECRET;
        $URL = $Credentials->URL;

        $ciudadNom = '';
        try {
            $Ciudad = new Ciudad($Registro->ciudadId);
            $ciudadNom = $Ciudad->ciudadNom;
        } catch (\Exception $e) {
        }

        if ($ciudadNom == '') {
            $ciudadNom = 'Tegucigalpa';
        }
        $requestAute = array(
            "clientId" => $clientId,
            "clientSecret" => $clientSecret,
        );

        $this->metodo = "Token";
        $tokenAute = $this->Autenticacion($requestAute, $URL);
        $tokenAute = json_decode($tokenAute);

        $this->accestoken = $tokenAute->accessToken;

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
        $Mandante = new Mandante($Usuario->mandante);

        $UsuarioTarjetacredito = new UsuarioTarjetacredito("", $Usuario->usuarioId, $Proveedor->proveedorId);

        if ($Usuario->mandante == '27' && $Usuario->paisId == '94') {
            $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
        }

        $Secure = array(
            "orderInformation" => array(
                "code" => $transproductoId,
                "currency" => $Usuario->moneda,
                "totalAmount" => $valorTax
            ),
            "clientInformation" => array(
                "address" => $Registro->direccion,
                "locality" => $ciudadNom,
                "firstName" => $Registro->nombre1,
                "lastName" => $Registro->apellido1,
                "phoneNumber" => $Pais->prefijoCelular . "" . $Registro->celular,
                "email" => $Registro->email,
                "country" => $Pais->paisNom,
                "billState" => "HN",
                "billZip" => $Registro->codigoPostal,
                "shipToZip" => $Registro->codigoPostal,
            ),
            "card" => array(
                "cardId" => $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token)
            ),
            "returnUrl" => $Mandante->baseUrl . "/gestion/deposito/correcto"
        );

        $this->metodo = "Setup3ds";

        $Result = $this->connection($Secure, $URL);
        $Result = json_decode($Result);

        $authenticationId = explode("authentication/", $Result->authenticationUrl);

        $TransaccionProducto->setExternoId($authenticationId[1]);

        $TransaccionProductoMySqlDAO->update($TransaccionProducto);

        if ($Result->status == "OK") {
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
            $data = array();
            $data["success"] = true;
            $data["Message"] = $Result->authenticationUrl;
            return json_decode(json_encode($data));
        } else {
            $data = array();
            $data["success"] = false;
            $data["Message"] = '';
            return json_decode(json_encode($data));
        }
    }

    /**
     * Elimina una tarjeta de crédito del sistema.
     *
     * @param Usuario $Usuario Objeto del usuario.
     * @param integer $id      ID de la tarjeta.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function deleteCard(Usuario $Usuario, $id)
    {
        $Pais = $Usuario->paisId;

        $mandante = $Usuario->mandante;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $UsuarioTarjetacredito = new UsuarioTarjetacredito($id);

        $UsuarioTarjetacredito->setEstado('I');

        $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
        $UsuarioTarjetacreditoMySqlDAO->update($UsuarioTarjetacredito);
        $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
        $data = array();
        $data["success"] = true;
        $data["Message"] = "Tarjeta Eliminada";

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una autenticación a la API de N1CO.
     *
     * @param array  $data Datos de autenticación.
     * @param string $URL  URL del endpoint de autenticación.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function Autenticacion($data, $URL)
    {
        $data = json_encode($data);

        $headers = array(
            'Content-type: application/json',
            'Accept: application/json'
        );

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

        return $result;
    }

    /**
     * Realiza una conexión a la API de N1CO para enviar datos.
     *
     * @param array  $data Datos a enviar en formato JSON.
     * @param string $URL  URL del endpoint de la API.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function connection($data, $URL)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization: Bearer ' . $this->accestoken,
            'Content-type: application/json',
            'Accept: application/json'
        );

        $time = time();
        syslog(LOG_WARNING, "N1CO DATA " . $time . ' ' . $URL . $this->metodo . ' ' . $data);

        $curl = new CurlWrapper($URL . $this->metodo);

        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        ));

        $result = $curl->execute();

        syslog(LOG_WARNING, "N1CO RESPONSE" . ' ' . $time . ' ' . $result);

        return $result;
    }

    /**
     * Realiza una conexión para registrar cambios en la cuenta.
     *
     * @param array  $data Datos a enviar en formato JSON.
     * @param string $URL  URL del servicio N1CO.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function connectionChanges($data, $URL)
    {
        $data = json_encode($data);

        $headers = array(
            'Authorization: Bearer ' . $this->accestoken,
            'Content-type: application/json',
            'Accept: application/json'
        );
        $time = time();
        syslog(LOG_WARNING, "N1CO DATA " . $time . ' ' . $URL . $this->metodo . ' ' . $data);

        $curl = new CurlWrapper($URL . $this->metodo);

        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        ));

        $result = $curl->execute();

        syslog(LOG_WARNING, "N1CO RESPONSE" . ' ' . $time . ' ' . $result);

        return $result;
    }

    /**
     * Realiza una conexión de pago utilizando cURL.
     *
     * @param string $data      Datos en formato JSON para la solicitud.
     * @param string $path      Ruta del endpoint de la API.
     * @param string $TOKEN     Token de autenticación Bearer.
     * @param string $URL_CHECK URL base del servicio de pagos.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function connectionPay($data, $path, $TOKEN, $URL_CHECK)
    {
        $curl = new CurlWrapper($URL_CHECK . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL_CHECK . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $TOKEN,
                'accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene las credenciales para el usuario y proveedor N1CO.
     *
     * @param mixed $Usuario Objeto Usuario con información relevante para obtener credenciales.
     *
     * @return object Retorna un objeto con las credenciales necesarias para la integración.
     */
    public function credentials($Usuario)
    {
        $Subproveedor = new Subproveedor("", "N1CO");
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        return $Credentials;
    }


}
