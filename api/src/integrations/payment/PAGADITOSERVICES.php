<?php

/**
 * Clase `PAGADITOSERVICES` para gestionar integraciones de pagos con el proveedor PAGADITO.
 *
 * Este archivo contiene métodos para realizar diversas operaciones relacionadas con pagos,
 * como agregar tarjetas, procesar pagos, reembolsos, y obtener tasas de cambio.
 * También incluye configuraciones específicas para manejar IPs y conexiones con el servicio.
 *
 * @category Integración
 * @package  Backend\integrations\payment
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
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
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 * Clase `PAGADITOSERVICES` para gestionar las integraciones de pagos con el proveedor PAGADITO.
 *
 * Esta clase contiene métodos para realizar operaciones como agregar tarjetas, procesar pagos,
 * realizar reembolsos y obtener tasas de cambio. También maneja configuraciones específicas
 * relacionadas con IPs y conexiones al servicio.
 */
class PAGADITOSERVICES
{
    /**
     * Método privado para definir el método de la API a utilizar.
     *
     * @var string
     */
    private $metodo = "";

    /**
     * URL de redirección para las operaciones de pago.
     *
     * @var string
     */
    private $URLREDIRECTION = "";

    /**
     * Constructor de la clase.
     * Configura el entorno de ejecución dependiendo de si es desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Elimina tildes y caracteres especiales de una cadena.
     *
     * @param string $cadena Cadena de texto a procesar.
     *
     * @return string Cadena sin tildes ni caracteres especiales.
     */
    public function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    /**
     * Agrega autenticación para un usuario con los datos de su tarjeta.
     *
     * @param Usuario $Usuario      Objeto del usuario.
     * @param string  $numTarjeta   Número de la tarjeta.
     * @param string  $expiry_month Mes de expiración de la tarjeta.
     * @param string  $expiry_year  Año de expiración de la tarjeta.
     * @param string  $cvv          Código de seguridad de la tarjeta.
     *
     * @return object Resultado de la operación en formato JSON.
     */
    public function AddAutentica(Usuario $Usuario, $numTarjeta, $expiry_month, $expiry_year, $cvv)
    {
        try {
            $Registro = new Registro("", $Usuario->usuarioId);

            $Subproveedor = new Subproveedor('', 'PAGADITO');
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $num = 20;
            $nombre = $Registro->nombre1 . ' ' . str_replace(['ñ', 'Ñ'], ['n', 'N'], $Registro->apellido1);

            if (strlen($nombre) > 25) {
                $nombre = substr($nombre, 0, 24);
            }

            if (strlen($nombre) < 5) {
                $nombre = 'Usuario' . ' ' . $nombre;
            }

            $data = array(
                "card" => array(
                    "number" => $numTarjeta,
                    "expirationDate" => strval($expiry_month . "/" . $num . $expiry_year),
                    "cvv" => $cvv,
                    "cardHolderName" => $this->quitar_tildes($nombre),
                )
            );

            $this->metodo = "setup-payer/";
            $Result = $this->connection($data, $Credentials->URL, $Credentials->USERNAME, $Credentials->PASSWORD);

            syslog(LOG_WARNING, "DATA PAGADITO AUTENTICA: " . $Usuario->usuarioId . " " . json_encode($data) . " RESPONSE: " . $Result);

            $Result = json_decode($Result);
            if ($Result->response_code == "PG200-00") {
                $data = array();
                $data["success"] = true;
                $data["token"] = $Result->accessToken;
                $data["requestId"] = strval($Result->request_id);
                $data["referenceId"] = strval($Result->referenceId);
                $data["deviceDataCollectionUrl"] = strval($Result->deviceDataCollectionUrl);
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error de deposito";
                $data["code"] = "";
            }

            return json_decode(json_encode($data));
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO AUTENTICA ERROR: " . $e->getMessage());
        }
    }

    /**
     * Agrega una tarjeta de crédito para un usuario.
     *
     * Este método permite registrar una tarjeta de crédito asociada a un usuario
     * y realizar operaciones relacionadas con el proveedor de pagos PAGADITO.
     *
     * @param Usuario  $Usuario      Objeto que representa al usuario.
     * @param Producto $Producto     Objeto que representa el producto asociado.
     * @param string   $numTarjeta   Número de la tarjeta de crédito.
     * @param string   $holder_name  Nombre del titular de la tarjeta.
     * @param string   $expiry_month Mes de expiración de la tarjeta.
     * @param string   $expiry_year  Año de expiración de la tarjeta.
     * @param string   $cvv          Código de seguridad de la tarjeta.
     * @param integer  $ProveedorId  ID del proveedor asociado.
     * @param float    $valor        Valor de la transacción.
     * @param boolean  $saveCard     Indica si la tarjeta debe ser guardada.
     * @param string   $requestId    ID de la solicitud de autenticación.
     * @param string   $referenceId  ID de referencia para la transacción.
     *
     * @return object Resultado de la operación en formato JSON.
     */
    public function addCard(Usuario $Usuario, Producto $Producto, $numTarjeta, $holder_name, $expiry_month, $expiry_year, $cvv, $ProveedorId, $valor, $saveCard, $requestId, $referenceId)
    {
        try {
            $Pais = new Pais($Usuario->paisId);

            $mandante = $Usuario->mandante;
            $Mandante = new Mandante($mandante);
            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';
            $Proveedor = new Proveedor($ProveedorId);
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Registro = new Registro("", $Usuario->usuarioId);

            $ciudadNom = '';
            try {
                $Ciudad = new Ciudad($Registro->ciudadId);
                $ciudadNom = $Ciudad->ciudadNom;
            } catch (\Exception $e) {
            }

            if ($ciudadNom == '') {
                if ($Usuario->mandante == 15 || $Usuario->mandante == 23) {
                    $ciudadNom = 'Tegucigalpa';
                } elseif ($Usuario->mandante == 16) {
                    $ciudadNom = 'Panamá';
                } elseif ($Usuario->mandante == 0) {
                    $ciudadNom = 'Managua';
                }
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

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Subproveedor = new Subproveedor('', 'PAGADITO');
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $sessionID = time();
            $num = 20;

            $Ip = $this->configIp($Usuario->dirIp, $Usuario->paisId, $Usuario->mandante, $Pais->paisId);

            switch ($Usuario->paisId) {
                case 2:
                    $cityCode = "558";
                    break;
                case 60:
                    $cityCode = "188";
                    break;
                case 68:
                    $cityCode = "222";
                    break;
                case 94:
                    $cityCode = "320";
                    break;
                case 102:
                    $cityCode = "340";
                    break;
                case 170:
                    $cityCode = "591";
                    break;
            }

            $nombre = $Registro->nombre1 . ' ' . str_replace(['ñ', 'Ñ'], ['n', 'N'], $Registro->apellido1);

            if (strlen($nombre) > 25) {
                $nombre = substr($nombre, 0, 24);
            }

            if (strlen($nombre) < 5) {
                $nombre = 'Usuario' . ' ' . $nombre;
            }

            try {
                $ProductoMandante = new ProductoMandante($Producto->productoId, $mandante);
            } catch (Exception $e) {
                $commission = 0;
            }

            if ($ProductoMandante->valor != "") {
                $commission = $ProductoMandante->valor;
            }

            $porcentaje_commission = $commission / 100;
            $valor_commission = $valorTax * $porcentaje_commission;
            $valorTax = $valorTax + $valor_commission;

            if ($Usuario->moneda != 'USD') {
                $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
                $valorTax = ($valorTax * $PaisMandante->trmUsd);
            }

            $line1 = 'Direccion';

            if ($Registro->direccion != '') {
                $line1 = $Registro->direccion;
            }

            $Mandante = new Mandante($mandante);
            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "gestion/deposito";
            }

            $trans_id = $mandante . $Pais->iso . $transproductoId;

            $email = $Registro->email;
            if ($mandante == 16) {
                $email = 'info@fluttersolution.live';
            }

            $data = array(
                "card" => array(
                    "number" => $numTarjeta,
                    "expirationDate" => strval($expiry_month . "/" . $num . $expiry_year),
                    "cvv" => $cvv,
                    "cardHolderName" => $this->quitar_tildes($nombre),
                    "firstName" => "$Registro->nombre1",
                    "lastName" => str_replace('ñ', 'n', $Registro->apellido1),
                    "billingAddress" => array(
                        "city" => trim($ciudadNom),
                        "state" => $Pais->paisNom,
                        "zip" => $Registro->codigoPostal,
                        "countryId" => $cityCode,
                        "line1" => trim(str_replace("/", "", $line1)),
                        "phone" => $Registro->celular,
                    ),
                    "email" => $email
                ),
                "transaction" => array(
                    "merchantTransactionId" => $trans_id,
                    "currencyId" => "USD",
                    "transactionDetails" => array(
                        array(
                            "quantity" => "1",
                            "description" => $Mandante->descripcion . ' - ' . $Pais->paisNom . " - Deposito con tarjeta",
                            "amount" => strval(round($valorTax, 2)),
                        )
                    ),
                ),
                "browserInfo" => array(
                    "deviceFingerprintID" => strval($sessionID),
                    "customerIp" => $Ip
                ),
                "consumerAuthenticationInformation" => array(
                    "setup_request_id" => strval($requestId),
                    "referenceId" => strval($referenceId),
                    "returnUrl" => $this->URLREDIRECTION
                ),
            );

            $this->metodo = "customer/";

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario("Deposito");
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $Result = $this->connection($data, $Credentials->URL, $Credentials->USERNAME, $Credentials->PASSWORD);

            syslog(LOG_WARNING, "DATA PAGADITO ADDCARD: " . $Usuario->usuarioId . " " . json_encode($data) . " RESPONSE: " . $Result);

            $Result = json_decode($Result);
            $providerResponse = $Result->response_message;


            $TransprodLog = new TransprodLog();
            $TransprodLog->setComentario("PAGADITO ADDCARD: " . $providerResponse);
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();
            $TransprodLogMySqlDAO->update($TransprodLog);

            $TransprodLogMySqlDAO->getTransaction()->commit();

            if ($saveCard == true) {
                $UsuarioTarjetacredito = new UsuarioTarjetacredito();
                $numTarjeta = substr_replace($numTarjeta, '********', 4, 8);
                $UsuarioTarjetacredito->setUsuarioId($Usuario->usuarioId);
                $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
                $UsuarioTarjetacredito->setCuenta($numTarjeta);
                $UsuarioTarjetacredito->setCvv('');
                $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
                $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($Result->customer_reply->payment_token));
                $UsuarioTarjetacredito->setEstado('A');
                $UsuarioTarjetacredito->setUsucreaId('0');
                $UsuarioTarjetacredito->setUsumodifId('0');
                $UsuarioTarjetacredito->setDescripcion("");
                $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO($Transaction);
                $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
            }

            if ($Result->response_code == "PG200-00") {
                $invoice = $transproductoId;
                $documento_id = $Result->customer_reply->authorization;
                $amount = $Result->customer_reply->totalAmount;
                $obj = base64_encode(json_encode($Result));

                exec("php -f " . __DIR__ . "/../../../integrations/payment/pagadito/api/approved.php " . $invoice . " " . $documento_id . " " . $amount . " " . $obj . " > /dev/null &");

                $data = array();
                $data["success"] = true;
                $data["code"] = "PG200-00";
                $data["Message"] = "";
                $data["Id"] = "";
            } elseif ($Result->response_code == "PG402-05") {
                $data = array();
                $data["success"] = true;
                $data["code"] = "PG402-05";
                $data["token"] = $Result->customer_reply->accessToken;
                $data["requestId"] = strval($Result->request_id);
                $data["referenceId"] = strval($referenceId);
                $data["stepUpUrl"] = strval($Result->customer_reply->stepUpUrl);
                $data["idTransaction"] = strval($Result->customer_reply->id_transaction);
                $data["transactionOriginal"] = strval($transproductoId);
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error de deposito";
                $data["code"] = "";
            }

            return json_decode(json_encode($data));
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO ADDCARD ERROR: " . $e->getMessage());
        }
    }

    /**
     * Realiza un desafío de pago con autenticación adicional.
     *
     * Este método procesa un pago con tarjeta de crédito, incluyendo validaciones
     * adicionales y autenticación del consumidor. Se utiliza para manejar transacciones
     * que requieren un paso adicional de verificación.
     *
     * @param Usuario  $Usuario         Objeto que representa al usuario.
     * @param Producto $Producto        Objeto que representa el producto asociado.
     * @param string   $numTarjeta      Número de la tarjeta de crédito.
     * @param string   $holder_name     Nombre del titular de la tarjeta.
     * @param string   $expiry_month    Mes de expiración de la tarjeta.
     * @param string   $expiry_year     Año de expiración de la tarjeta.
     * @param string   $cvv             Código de seguridad de la tarjeta.
     * @param integer  $ProveedorId     ID del proveedor asociado.
     * @param float    $valor           Valor de la transacción.
     * @param string   $requestId       ID de la solicitud de autenticación.
     * @param string   $referenceId     ID de referencia para la transacción.
     * @param string   $transactionId   ID de la transacción.
     * @param integer  $transproductoId ID del producto de la transacción.
     *
     * @return object Resultado de la operación en formato JSON.
     */
    public function PaymentChallenge(Usuario $Usuario, Producto $Producto, $numTarjeta, $holder_name, $expiry_month, $expiry_year, $cvv, $ProveedorId, $valor, $requestId, $referenceId, $transactionId, $transproductoId)
    {
        try {
            $Pais = new Pais($Usuario->paisId);

            $mandante = $Usuario->mandante;
            $Mandante = new Mandante($mandante);
            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';
            $Proveedor = new Proveedor($ProveedorId);
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Registro = new Registro("", $Usuario->usuarioId);

            $ciudadNom = '';
            try {
                $Ciudad = new Ciudad($Registro->ciudadId);
                $ciudadNom = $Ciudad->ciudadNom;
            } catch (\Exception $e) {
            }
            if ($ciudadNom == '') {
                if ($Usuario->mandante == 15 || $Usuario->mandante == 23) {
                    $ciudadNom = 'Tegucigalpa';
                } elseif ($Usuario->mandante == 16) {
                    $ciudadNom = 'Panamá';
                } elseif ($Usuario->mandante == 0) {
                    $ciudadNom = 'Managua';
                }
            }

            $TransaccionProducto = new TransaccionProducto($transproductoId);
            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

            $Subproveedor = new Subproveedor('', 'PAGADITO');
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $sessionID = time();
            $num = 20;

            $Ip = $this->configIp($Usuario->dirIp, $Usuario->paisId, $Usuario->mandante, $Pais->paisId);

            switch ($Usuario->paisId) {
                case 2:
                    $cityCode = "558";
                    break;
                case 60:
                    $cityCode = "188";
                    break;
                case 68:
                    $cityCode = "222";
                    break;
                case 94:
                    $cityCode = "320";
                    break;
                case 102:
                    $cityCode = "340";
                    break;
                case 170:
                    $cityCode = "591";
                    break;
            }

            $nombre = $Registro->nombre1 . ' ' . str_replace(['ñ', 'Ñ'], ['n', 'N'], $Registro->apellido1);

            if (strlen($nombre) > 25) {
                $nombre = substr($nombre, 0, 24);
            }

            if (strlen($nombre) < 5) {
                $nombre = 'Usuario' . ' ' . $nombre;
            }

            try {
                $ProductoMandante = new ProductoMandante($Producto->productoId, $mandante);
            } catch (Exception $e) {
                $commission = 0;
            }

            if ($ProductoMandante->valor != "") {
                $commission = $ProductoMandante->valor;
            }

            $porcentaje_commission = $commission / 100;
            $valor_commission = $valor * $porcentaje_commission;
            $valor = $valor + $valor_commission;

            if ($Usuario->moneda != 'USD') {
                $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
                $valor = ($valor * $PaisMandante->trmUsd);
            }

            $line1 = 'Direccion';

            if ($Registro->direccion != '') {
                $line1 = $Registro->direccion;
            }

            $Mandante = new Mandante($mandante);
            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "gestion/deposito";
            }

            $trans_id = $mandante . $Pais->iso . $TransaccionProducto->transproductoId;

            $email = $Registro->email;
            if ($mandante == 16) {
                $email = 'info@fluttersolution.live';
            }

            $data = array(
                "card" => array(
                    "number" => $numTarjeta,
                    "expirationDate" => strval($expiry_month . "/" . $num . $expiry_year),
                    "cvv" => $cvv,
                    "cardHolderName" => $this->quitar_tildes($nombre),
                    "firstName" => "$Registro->nombre1",
                    "lastName" => str_replace('ñ', 'n', $Registro->apellido1),
                    "billingAddress" => array(
                        "city" => trim($ciudadNom),
                        "state" => $Pais->paisNom,
                        "zip" => $Registro->codigoPostal,
                        "countryId" => $cityCode,
                        "line1" => trim(str_replace("/", "", $line1)),
                        "phone" => $Registro->celular,
                    ),
                    "email" => $email
                ),
                "transaction" => array(
                    "merchantTransactionId" => $trans_id,
                    "currencyId" => "USD",
                    "transactionDetails" => array(
                        array(
                            "quantity" => "1",
                            "description" => $Mandante->descripcion . ' - ' . $Pais->paisNom . " - Deposito con tarjeta",
                            "amount" => strval(round($valor, 2)),
                        )
                    ),
                ),
                "browserInfo" => array(
                    "deviceFingerprintID" => strval($sessionID),
                    "customerIp" => $Ip
                ),
                "consumerAuthenticationInformation" => array(
                    "setup_request_id" => strval($requestId),
                    "referenceId" => strval($referenceId),
                    "transactionId" => $transactionId
                ),
            );

            $this->metodo = "validate-process-card/";

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $Transaction->commit();

            $Result = $this->connection($data, $Credentials->URL, $Credentials->USERNAME, $Credentials->PASSWORD);

            syslog(LOG_WARNING, "DATA PAGADITO PAYMENT: " . $Usuario->usuarioId . " " . json_encode($data) . " RESPONSE: " . $Result);

            $Result = json_decode($Result);
            $providerResponse = $Result->response_message;

            $TransprodLog->setComentario("PAGADITO PAYMENT: " . $providerResponse);
            $TransprodLog->setTValue(json_encode($Result));

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();
            $TransprodLogMySqlDAO->update($TransprodLog);
            $TransprodLogMySqlDAO->getTransaction()->commit();

            if ($Result->response_code == "PG200-00") {
                $invoice = $TransaccionProducto->transproductoId;
                $documento_id = $Result->customer_reply->authorization;
                $amount = $Result->customer_reply->totalAmount;
                $obj = base64_encode(json_encode($Result));

                exec("php -f " . __DIR__ . "/../../../integrations/payment/pagadito/api/approved.php " . $invoice . " " . $documento_id . " " . $amount . " " . $obj . " > /dev/null &");

                $data = array();
                $data["success"] = true;
                $data["code"] = "PG200-00";
                $data["Message"] = "";
                $data["Id"] = "";
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error de deposito";
            }

            return json_decode(json_encode($data));
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO PAYMENT ERROR: " . $e->getMessage());
        }
    }

    /**
     * Realiza una autenticación de pago utilizando un token de tarjeta de crédito.
     *
     * Este método se encarga de autenticar un pago para un usuario utilizando un token
     * previamente almacenado de su tarjeta de crédito. Se conecta al servicio PAGADITO
     * para realizar la operación y devuelve el resultado en formato JSON.
     *
     * @param Usuario $Usuario               Objeto que representa al usuario.
     * @param mixed   $UsuarioTarjetacredito Objeto que contiene los datos de la tarjeta de crédito del usuario.
     *
     * @return object Resultado de la operación en formato JSON.
     */
    public function PaymentAutentica(Usuario $Usuario, $UsuarioTarjetacredito)
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Subproveedor = new Subproveedor('', 'PAGADITO');
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $token = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token);

            $data = array(
                "payment_token" => $token
            );

            $this->metodo = "setup-payer-by-token/";

            $Result = $this->connection($data, $Credentials->URL, $Credentials->USERNAME, $Credentials->PASSWORD);

            syslog(LOG_WARNING, "DATA PAGADITO PAYMENTAUTH: " . $Usuario->usuarioId . " " . json_encode($data) . " RESPONSE: " . $Result);

            $Result = json_decode($Result);

            if ($Result->response_code == "PG200-00") {
                $data = array();
                $data["success"] = false;
                $data["token"] = $Result->accessToken;
                $data["requestId"] = strval($Result->request_id);
                $data["referenceId"] = strval($Result->referenceId);
                $data["deviceDataCollectionUrl"] = strval($Result->deviceDataCollectionUrl);
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error de deposito";
            }
            return json_decode(json_encode($data));
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO PAYMENTAUTH ERROR: " . $e->getMessage());
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y un producto.
     *
     * Este método genera una transacción de pago con los datos proporcionados,
     * incluyendo el cálculo de impuestos, la configuración de la transacción,
     * y la comunicación con el servicio de pagos PAGADITO.
     *
     * @param Usuario  $Usuario               Objeto que representa al usuario.
     * @param Producto $Producto              Objeto que representa el producto asociado.
     * @param float    $valor                 Valor de la transacción.
     * @param object   $UsuarioTarjetacredito Objeto que contiene los datos de la tarjeta del usuario.
     * @param string   $requestId             ID de la solicitud de autenticación.
     * @param string   $referenceId           ID de referencia para la transacción.
     *
     * @return object Resultado de la operación en formato JSON.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $UsuarioTarjetacredito, $requestId, $referenceId)
    {
        try {
            // Inicialización de variables y configuración del entorno
            $Pais = new Pais($Usuario->paisId);
            $data = array();
            $data["success"] = false;
            $data["error"] = 1;
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';
            $usuario_id = $Usuario->usuarioId;
            $valor = $valor . ".00";
            $producto_id = $Producto->productoId;
            $mandante = $Usuario->mandante;

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            // Obtención de credenciales del subproveedor
            $Subproveedor = new Subproveedor('', 'PAGADITO');
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            // Configuración de la URL de redirección
            $Mandante = new Mandante($mandante);
            if ($Mandante->baseUrl != '') {
                $this->URLREDIRECTION = $Mandante->baseUrl . "gestion/deposito";
            }

            // Desencriptar el token de la tarjeta del usuario
            $token = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token);

            // Creación de la transacción en la base de datos
            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

            // Cálculo de impuestos
            try {
                $Clasificador = new Clasificador("", "TAXDEPOSIT");
                $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $taxedValue = $MandanteDetalle->valor;
            } catch (Exception $e) {
                $taxedValue = 0;
            }

            $totalTax = $valor * ($taxedValue / 100);
            $valorTax = $valor * (1 + $taxedValue / 100);

            // Configuración de los datos de la transacción
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

            $this->metodo = "payment/";
            $sessionID = time();

            // Configuración de la IP del cliente
            $Ip = $this->configIp($Usuario->dirIp, $Usuario->paisId, $Usuario->mandante, $Pais->paisId);

            // Ajuste del valor de la transacción según el mandante y el país
            if ($Usuario->moneda != 'USD') {
                $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
                $valorTax = ($valorTax * $PaisMandante->trmUsd);
            }

            $trans_id = $mandante . $Pais->iso . $transproductoId;

            // Registro de detalles de la transacción
            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode(array());
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            // Registro de logs de la transacción
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Deposito');
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            // Preparación de los datos para la solicitud de pago
            $datos = array(
                "payment_token" => $token,
                "transaction" => array(
                    "merchantTransactionId" => $trans_id,
                    "currencyId" => "USD",
                    "transactionDetails" => array(
                        array(
                            "quantity" => "1",
                            "description" => "Deposito " . $Pais->paisNom,
                            "amount" => strval(round($valorTax, 2))
                        )
                    ),
                ),
                "browserInfo" => array(
                    "deviceFingerprintID" => strval($sessionID),
                    "customerIp" => $Ip
                ),
                "consumerAuthenticationInformation" => array(
                    "setup_request_id" => strval($requestId),
                    "referenceId" => strval($referenceId),
                    "returnUrl" => $this->URLREDIRECTION
                ),
            );

            // Envío de la solicitud de pago al servicio PAGADITO
            $Result = $this->connection($datos, $Credentials->URL, $Credentials->USERNAME, $Credentials->PASSWORD);

            syslog(LOG_WARNING, "DATA PAGADITO CREATE: " . $Usuario->usuarioId . " " . json_encode($datos) . " RESPONSE: " . $Result);

            $Result = json_decode($Result);
            $providerResponse = $Result->response_message;

            // Actualización de logs con la respuesta del proveedor
            $TransprodLog = new TransprodLog();
            $TransprodLog->setComentario("PAGADITO CREATE: " . $providerResponse);
            $TransprodLog->setTValue(json_encode($Result));

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();
            $TransprodLogMySqlDAO->update($TransprodLog);

            $TransprodLogMySqlDAO->getTransaction()->commit();

            // Manejo de la respuesta del servicio
            if ($Result->response_code == "PG200-00") {
                $invoice = $transproductoId;
                $documento_id = $Result->customer_reply->authorization;
                $amount = $Result->customer_reply->totalAmount;
                $obj = base64_encode(json_encode($Result));

                exec("php -f " . __DIR__ . "/../../../integrations/payment/pagadito/api/approved.php " . $invoice . " " . $documento_id . " " . $amount . " " . $obj . " > /dev/null &");

                $data = array();
                $data["success"] = true;
                $data["Message"] = "";
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error de deposito";
            }

            return json_decode(json_encode($data));
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO CREATE ERROR: " . $e->getMessage());
        }
    }

    /**
     * Realiza un reembolso para un usuario y un producto.
     *
     * Este método gestiona la lógica para procesar un reembolso a través del proveedor de pagos PAGADITO.
     * Incluye la creación de registros en la base de datos, la comunicación con el servicio de pagos,
     * y el manejo de la respuesta del proveedor.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario que solicita el reembolso.
     * @param Producto $Producto Objeto que representa el producto asociado al reembolso.
     *
     * @return object|null Resultado de la operación en formato JSON o null en caso de error.
     */
    public function Rembolso(Usuario $Usuario, Producto $Producto)
    {
        try {
            $Proveedor = new Proveedor("", "PAGADITO");
            $UsuarioTarjetacredito = new UsuarioTarjetacredito("", $Usuario->usuarioId, $Proveedor->proveedorId);

            $data = array();
            $data["success"] = false;
            $data["error"] = 1;

            $Subproveedor = new Subproveedor('', 'PAGADITO');
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $TransaccionProducto = new TransaccionProducto("", "", "", $UsuarioTarjetacredito->usutarjetacreditoId);

            $valor = $TransaccionProducto->valor;
            $estado = $TransaccionProducto->estado;
            $tipo = $TransaccionProducto->tipo;
            $estadoProducto = $TransaccionProducto->estadoProducto;

            $autorizacion = $TransaccionProducto->getFinalId();

            $this->metodo = "refund";
            $datos = array(
                "authorization" => $autorizacion,
                "reason" => "Reversion de entidad"
            );

            $Result2 = $this->connection($datos, $Credentials->URL, $Credentials->USERNAME, $Credentials->PASSWORD);

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

            $TransaccionProducto = new TransaccionProducto();
            $TransaccionProducto->setProductoId($Producto->productoId);
            $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
            $TransaccionProducto->setValor($valor);
            $TransaccionProducto->setEstado($estado);
            $TransaccionProducto->setTipo($tipo);
            $TransaccionProducto->setEstadoProducto($estadoProducto);
            $TransaccionProducto->setMandante($Usuario->mandante);
            $TransaccionProducto->setFinalId(0);
            $TransaccionProducto->setExternoId(json_decode($Result2->request_id));
            $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($datos);

            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $Result = json_decode($Result2);
            $providerResponse = $Result->response_message;

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario("PAGADITO REEMBOLSO: " . $providerResponse);
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);
            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            if (json_decode($Result2->response_code) == "PG200-00") {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Solicitud de rembolso de deposito');
                $TransprodLog->setTValue($Result2);
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();

                $data2 = array();
                $data2["success"] = true;
                $data2["Reference"] = "Deposito Realizado";
            }

            return json_decode(json_encode($data2));
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO REEMBOLSO ERROR: " . $e->getMessage());
        }
    }

    /**
     * Realiza una conexión HTTP utilizando cURL.
     *
     * Este método envía una solicitud HTTP POST a una URL específica con los datos proporcionados,
     * utilizando autenticación básica y encabezados personalizados.
     *
     * @param array  $data     Datos a enviar en el cuerpo de la solicitud.
     * @param string $url      URL base del servicio al que se realizará la conexión.
     * @param string $username Nombre de usuario para la autenticación básica.
     * @param string $password Contraseña para la autenticación básica.
     *
     * @return string Respuesta del servidor en formato JSON.
     */
    public function connection($data, $url, $username, $password)
    {
        $headers = array(
            'Authorization: Basic ' . base64_encode($username . ':' . $password),
            'Content-type: application/json',
            'Accept: application/json'
        );

        $curl = new CurlWrapper($url . $this->metodo);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $this->metodo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Configura y valida la dirección IP del cliente.
     *
     * Este método procesa una lista de direcciones IP, valida su formato y rango,
     * y realiza ajustes según el país y el mandante del usuario.
     *
     * @param string  $ips      Lista de direcciones IP separadas por comas.
     * @param integer $pais     ID del país del usuario.
     * @param integer $mandante ID del mandante del usuario.
     * @param integer $paisId   ID del país asociado al mandante.
     *
     * @return string Dirección IP configurada y validada.
     */
    public function configIp($ips, $pais, $mandante, $paisId)
    {
        try {
            // Dividir las IPs en un array
            $IpArray = explode(",", $ips);

            // Si el país es 2, usar la segunda lista de IPs
            if ($pais == 2) {
                $IpArray = explode(",", $_SERVER["HTTP_TRUE_CLIENT_IP"]);
            }

            // Si el mandante es 16 y el país es 170, y la primera IP está vacía, asignar una IP por defecto
            if ($mandante == 16 && $paisId == 170) {
                if (empty($IpArray[0])) {
                    $IpArray[0] = "173.244.55.81";
                }
            }

            // Convertir la primera IP a su representación numérica
            $ipNumeric = ip2long(trim($IpArray[0]));

            // Definir el rango de IPs
            $startRange = ip2long("200.0.0.0");
            $endRange = ip2long("255.255.255.255");

            // Verificar si la IP está en el rango y es válida
            if ($ipNumeric !== false && $ipNumeric >= $startRange && $ipNumeric <= $endRange) {
                $IpArray[0] = "173.244.55.81";
            }

            $Ip = $IpArray[0];

            return $Ip;
        } catch (Exception $e) {
            syslog(LOG_WARNING, "DATA PAGADITO CONFIGIP ERROR: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la tasa de cambio para una moneda específica utilizando el servicio PAGADITO.
     *
     * Este método realiza una solicitud SOAP al servicio de PAGADITO para obtener la tasa de cambio
     * de una moneda específica. Utiliza un token de autenticación generado previamente.
     *
     * @param string $currency Código de la moneda para la cual se desea obtener la tasa de cambio.
     * @param string $urlc     URL base del servicio de PAGADITO.
     * @param string $username Nombre de usuario para la autenticación.
     * @param string $password Contraseña para la autenticación.
     *
     * @return float Valor de la tasa de cambio obtenida.
     *
     * @throws Exception Si ocurre un error durante la transacción o la respuesta no es válida.
     */
    public function charges($currency, $urlc, $username, $password)
    {
        $Token = $this->charges_token($urlc, $username, $password);

        $data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wspg="urn:wspg">
            <soapenv:Header/>
            <soapenv:Body>
                <wspg:get_exchange_rate>
                    <token>' . $Token . '</token>
                    <currency>' . $currency . '</currency>
                    <format_return>json</format_return>
                </wspg:get_exchange_rate>
            </soapenv:Body>
        </soapenv:Envelope>';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlc . '/wspg/charges.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'SOAPAction: urn:ws#connect'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        syslog(LOG_WARNING, "PAGADITO charges: " . $response);

        try {
            $startPos = strpos($response, '<return');
            $startPos = strpos($response, '>', $startPos) + 1;
            $endPos = strpos($response, '</return>', $startPos);
            $returnValue = substr($response, $startPos, $endPos - $startPos);
            $value = html_entity_decode($returnValue);
            $responseArray = json_decode($value, true);
            $finalValue = $responseArray['value'];

            syslog(LOG_WARNING, "URL PAGADITO charges: " . ' ' . $urlc . '/wspg/charges.php');

            return $finalValue;
        } catch (Exception $e) {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Obtiene un token de autenticación para realizar solicitudes SOAP al servicio PAGADITO.
     *
     * Este método envía una solicitud SOAP para autenticar al usuario y obtener un token
     * que se utilizará en futuras transacciones con el servicio PAGADITO.
     *
     * @param string $urlc     URL base del servicio de PAGADITO.
     * @param string $username Nombre de usuario para la autenticación.
     * @param string $password Contraseña para la autenticación.
     *
     * @return string|integer Token de autenticación obtenido o 0 en caso de error.
     */
    public function charges_token($urlc, $username, $password)
    {
        $data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wspg="urn:wspg">
            <soapenv:Header/>
            <soapenv:Body>
                <wspg:connect>
                    <uid>' . $username . '</uid>
                    <wsk>' . $password . '</wsk>
                    <format_return>json</format_return>
                </wspg:connect>
            </soapenv:Body>
        </soapenv:Envelope>';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlc . '/wspg/charges.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'SOAPAction: urn:ws#connect'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        syslog(LOG_WARNING, "PAGADITO token charges: " . $response);

        if ($response === false) {
            return 0;
        }

        try {
            // Verifica si la respuesta no está vacía
            if (empty($response)) {
                throw new Exception("La respuesta está vacía.");
            }

            // Busca la posición de '<return'
            $startPos = strpos($response, '<return');
            if ($startPos === false) {
                throw new Exception("No se encontró '<return' en la respuesta.");
            }

            // Busca la posición de '>' después de '<return'
            $startPos = strpos($response, '>', $startPos) + 1;
            if ($startPos === false) {
                throw new Exception("No se encontró '>' después de '<return'.");
            }

            // Busca la posición de '</return>'
            $endPos = strpos($response, '</return>', $startPos);
            if ($endPos === false) {
                throw new Exception("No se encontró '</return>' en la respuesta.");
            }

            $returnValue = substr($response, $startPos, $endPos - $startPos);
            $value = html_entity_decode($returnValue);
            $responseArray = json_decode($value, true);

            return $responseArray['value'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}
