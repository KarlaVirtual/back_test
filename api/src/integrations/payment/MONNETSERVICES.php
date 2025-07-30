<?php

/**
 * Clase `MONNETSERVICES` para gestionar integraciones de pagos y retiros con Monnet.
 *
 * Este archivo contiene la implementación de la clase `MONNETSERVICES`, que incluye métodos
 * para realizar solicitudes de pago, retiros, consultas de nóminas y otras operaciones relacionadas
 * con la integración de Monnet. La clase utiliza diferentes configuraciones según el entorno
 * (desarrollo o producción) y soporta múltiples países y monedas.
 *
 * @category Integraciones
 * @package  Backend\integrations\payment
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\PuntoVenta;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioPerfil;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `MONNETSERVICES`.
 *
 * Esta clase gestiona las integraciones de pagos y retiros con Monnet,
 * proporcionando métodos para realizar solicitudes de pago, retiros,
 * consultas de nóminas y otras operaciones relacionadas.
 */
class MONNETSERVICES
{
    /**
     * tipo de patch para las peticiones.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * URL de callback para notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/monnet/confirm/";

    /**
     * URL de callback en entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/monnet/confirm/";


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
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza la solicitud.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $urlCancel  URL de redirección en caso de cancelación.
     *
     * @return object Respuesta de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $data = array();

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $Pais = new Pais($Usuario->paisId);

        if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {
            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

            $tipoDocumento = 'C';
            $nombre = $PuntoVenta->nombreContacto;
            $apellido = $PuntoVenta->nombreContacto;
            $documento = $PuntoVenta->cedula;
            $telefono = $PuntoVenta->telefono;
            $direccion = $PuntoVenta->direccion;
            $Region = $PuntoVenta->ciudadId;
            $City = $PuntoVenta->ciudadId;
            $ZipCode = '';
        } else {
            $Registro = new Registro("", $Usuario->usuarioId);

            $tipoDocumento = $Registro->tipoDoc;
            $nombre = $Registro->nombre1;
            $apellido = $Registro->apellido1;
            $documento = $Registro->cedula;
            $telefono = $Registro->telefono;
            $direccion = $Registro->direccion;
            $Region = $Registro->ciudad;
            $City = $Registro->ciudad;
            $ZipCode = $Registro->codigoPostal;
        }

        $email = $Usuario->login;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $producto_id = $Producto->productoId;
        $country = $Pais->iso;

        if ($country == 'MX') {
            $Region = 'Ciudad de México';
            $City = 'Ciudad de México';
            $telefono = '0000';
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

        $valor2 = number_format($valorTax, 2, '.', '');

        $PAYIN_MERCHANT_ID = $Credentials->PAYIN_MERCHANT_ID;
        $KEY_MONNET = $Credentials->KEY_MONNET;
        $URL = $Credentials->URL;
        $URL_BODY = $Credentials->URL_BODY;

        $data['payinMerchantID'] = $PAYIN_MERCHANT_ID;
        $data['payinAmount'] = $valor2;
        $data['payinCurrency'] = $moneda;
        $data['payinMerchantOperationNumber'] = $TransaccionProducto->transproductoId;
        $data['payinMethod'] = $Producto->externoId;

        $String = $PAYIN_MERCHANT_ID . $TransaccionProducto->transproductoId . $valor2 . $moneda . $KEY_MONNET;

        $data['payinVerification'] = $this->verificacion($String);
        $data['payinTransactionOKURL'] = $urlSuccess;
        $data['payinTransactionErrorURL'] = $urlFailed;
        $data['payinExpirationTime'] = 120;
        $data['payinLanguage'] = $Pais->idioma;
        $data['payinCustomerEmail'] = $email;
        $data['payinCustomerName'] = $nombre;
        $data['payinCustomerLastName'] = $apellido;

        $data['payinProductSku'] = "0000";
        $data['payinProductQuantity'] = "0000";
        $data['payinRegularCustomer'] = "0000";
        $data['payinCustomerID'] = "0000";
        $data['payinDiscountCoupon'] = "0000";
        $data['payinDateTime'] = "0000";
        $data['payinFilterBy'] = "0000";


        switch ($tipoDocumento) {
            case "E":
                if ($Pais->iso == "EC") {
                    $tipoDocumento = "CI";
                } elseif ($Pais->iso == "CL") {
                    $tipoDocumento = "RUT";
                }
                break;
            case "P":
                if ($Pais->iso == "EC") {
                    $tipoDocumento = "PP";
                } elseif ($Pais->iso == "CL") {
                    $tipoDocumento = "PP";
                }
                break;
            case "C":
                if ($Pais->iso == "EC") {
                    $tipoDocumento = "CI";
                } elseif ($Pais->iso == "CL") {
                    $tipoDocumento = "RUT";
                } elseif ($Pais->iso == "PE") {
                    $tipoDocumento = "DNI";
                } elseif ($Pais->iso == "MX") {
                    $tipoDocumento = "RFC";
                }
                break;
            default:
                if ($Pais->iso == "EC") {
                    $tipoDocumento = "CI";
                } elseif ($Pais->iso == "CL") {
                    $tipoDocumento = "RUT";
                }
                break;
        }

        $data['payinCustomerTypeDocument'] = $tipoDocumento;
        $data['payinCustomerDocument'] = $documento;
        $data['payinCustomerPhone'] = $telefono;
        $data['payinCustomerAddress'] = $direccion;
        $data['payinCustomerCity'] = $City;
        $data['payinCustomerRegion'] = $Region;
        $data['payinCustomerCountry'] = $country;
        $data['payinCustomerZipCode'] = $ZipCode;

        $data['payinCustomerShippingName'] = $nombre;
        $data['payinCustomerShippingPhone'] = $telefono;
        $data['payinCustomerShippingAddress'] = $direccion;
        $data['payinCustomerShippingCity'] = $City;
        $data['payinCustomerShippingRegion'] = $Region;
        $data['payinCustomerShippingCountry'] = $country;
        $data['payinCustomerShippingZipCode'] = $ZipCode;

        $data['payinProductID'] = $Producto->productoId;
        $data['payinProductDescription'] = $Producto->descripcion;
        $data['payinProductAmount'] = $valor2;
        $data['URLMonnet'] = $URL_BODY;
        $data['typePost'] = "json";

        $this->tipo = 'v3/online-payments';

        $Result = $this->connectionPOST($data, $URL);

        syslog(LOG_WARNING, "MONNET DATA: " . $URL . $this->tipo . json_encode($data) . " RESPONSE " . $Result);

        $response = json_decode($Result);

        if ($Result != " " && ($response->payinErrorCode == null || $response->payinErrorCode == '0000')) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue("");
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $Transaction->commit();

            $response = json_decode($Result);
            $data = array();
            $data["success"] = true;
            $data["url"] = $response->url;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud POST a la URL especificada con los datos proporcionados.
     *
     * @param array  $data Datos a enviar en la solicitud POST.
     * @param string $URL  URL a la que se enviará la solicitud.
     *
     * @return string Resultado de la solicitud POST.
     */
    public function connectionPOST($data, $URL)
    {
        $data = json_encode($data);

        $header = array(
            "Content-Type: application/json"
        );

        $curl = new CurlWrapper($URL . $this->tipo);
        $curl->setOptionsArray(array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $result = $curl->execute();
        return $result;
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
     * Genera un hash SHA-512 para verificación.
     *
     * @param string $String Cadena a hashear.
     *
     * @return string Hash generado.
     */
    function verificacion($String)
    {
        return hash('sha512', $String, false);
    }

    /**
     * Genera un hash SHA-256 para verificación.
     *
     * @param string $String Cadena a hashear.
     *
     * @return string Hash generado.
     */
    function verificacion256($String)
    {
        return hash('sha256', $String, false);
    }

    /**
     * Calcula un HMAC SHA-256.
     *
     * @param string $message           Mensaje a firmar.
     * @param string $secret_passphrase Clave secreta para la firma.
     *
     * @return string Firma HMAC en formato hexadecimal.
     */
    function calculate_hmac_sha256($message, $secret_passphrase)
    {
        $message = utf8_encode($message);
        $secret_passphrase = utf8_encode($secret_passphrase);

        $signature = hash_hmac('sha256', $message, $secret_passphrase, true);

        $signature_hex = bin2hex($signature);

        return $signature_hex;
    }

    /**
     * Realiza un retiro (cash out).
     *
     * @param CuentaCobro              $CuentaCobro         Objeto de la cuenta de cobro.
     * @param string                   $ProductoId          ID del producto (opcional).
     * @param TransaccionProducto|null $TransaccionProducto Objeto de la transacción (opcional).
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $ProductoId = '', TransaccionProducto $TransaccionProducto = null)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Mandante = $Usuario->mandante;

        $Subproveedor = new Subproveedor('', 'MONNETPAY');
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->getSubproveedorId(), $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        if ($ProductoId == "") {
            $Producto = new Producto($Banco->productoPago);
        } else {
            if ($ProductoId != '') {
                $Producto = new Producto($ProductoId);
            } elseif ($Banco->productoPago != '') {
                $Producto = new Producto($Banco->productoPago);
            } else {
                $Producto = new Producto($ProductoId);
            }
        }

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = $CuentaCobro->getValorAPagar();

        if ($TransaccionProducto == null) {
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
            $TransaccionProducto->transproductoId = $transproductoId;
        }

        $transproductoId = $TransaccionProducto->transproductoId;

        $CuentaCobro->setTransproductoId($transproductoId);

        $pais = new Pais($Usuario->paisId);

        //if para iso
        if (strtoupper($pais->iso) == "CL") {
            $pais->iso = "CHL";
        } elseif (strtoupper($pais->iso) == "EC") {
            $pais->iso = "ECU";
            $valorFinal = number_format($valorFinal, 2, '.', '');
        } elseif (strtoupper($pais->iso) == "PE") {
            $pais->iso = "PER";
            $amount = $CuentaCobro->getValor();
        } elseif (strtoupper($pais->iso) == "MX") {
            $pais->iso = "MEX";
        } elseif (strtoupper($pais->iso) == "HN") {
            $pais->iso = "HND";
        }

        $PAYOUT_MERCHANT_ID = $Credentials->PAYOUT_MERCHANT_ID;
        $KEY_MONNET_PAYOUT = $Credentials->KEY_MONNET_PAYOUT;
        $API_SECRET_PAYOUT = $Credentials->API_SECRET_PAYOUT;
        $URLPAYOUT = $Credentials->URLPAYOUT;
        $URLPAYOUTMEX = $Credentials->URLPAYOUTMEX;

        $order_id = $transproductoId;
        $account_id = $UsuarioBanco->cuenta;
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $customerId = $Registro->cedula;
        $name = $Usuario->nombre;
        $LastName = $Registro->apellido1;
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;

        if ($pais->iso == "ECU") {
            $customerId = $this->agregarCerosAlPrincipio($customerId);
            $vat_id = $customerId;
        } else {
            $vat_id = $customerId;
        }

        $tipoDoc = "";

        switch ($Registro->getTipoDoc()) {
            case "E":
                if ($pais->iso == "CHL") {
                    $tipoDoc = "4";
                } elseif ($pais->iso == "ECU") {
                    $tipoDoc = "1";
                } elseif ($pais->iso == "HND") {
                    $tipoDoc = "3";
                }
                break;
            case "P":
                if ($pais->iso == "CHL") {
                    $tipoDoc = "3";
                } elseif ($pais->iso == "ECU") {
                    $tipoDoc = "2";
                    $vat_id = $vat_id . '001';
                } elseif ($pais->iso == "HND") {
                    $tipoDoc = "2";
                }
                break;
            case "C":
                if ($pais->iso == "MEX") {
                    $tipoDoc = "2"; // VALOR ASIGNADO PARA TIPO CURP
                } else {
                    $tipoDoc = "1";
                }
                break;
            default:
                $tipoDoc = "";
                break;
        }

        switch ($typeAccount) {
            case "0":
                $typeAccount = "1";
                break;
            case "1":
                $typeAccount = "0";
                break;
            case "Ahorros":
                if ($pais->iso == 'MEX' && $Mandante == 18) {
                    $typeAccount = "2";
                } elseif ($pais->iso == "HND") {
                    $typeAccount = "2";
                } else {
                    $typeAccount = "1";
                }
                break;
            case "Corriente":
                if ($pais->iso == 'MEX' && $Mandante == 18) {
                    $typeAccount = "1";
                } elseif ($pais->iso == "HND") {
                    $typeAccount = "1";
                } else {
                    $typeAccount = "0";
                }
                break;
            case "Vista":
                $typeAccount = "2";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        $identificacion = " ";

        if ($Usuario->test == "N") {
            $identificacion = "REAL";
        } elseif ($Usuario->test == "S") {
            $identificacion = "TEST";
        }

        $amountUsd = '';
        $department = '';

        if ($Usuario->moneda == 'PEN') {
            $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
            $amountUsd = ($valorFinal * $PaisMandante->trmUsd);
            $amountUsd = number_format($amountUsd, 2);


            $json = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $Registro->ciudadId . '","op":"eq"}] ,"groupOp" : "AND"}';
            $Pais = new Pais();
            $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id,departamento.depto_nom", "asc", 0, 1, $json, true, $Usuario->mandante);
            $paises = json_decode($paises);

            $department = $paises->data['0']->{"departamento.depto_nom"};
        }

        // Interbank , Banco de credito, Banco continental, Scotiabank
        if ($Usuario->paisId == 173 && ! in_array($Producto->externoId, array('Interbank', 'Banco de Credito', 'Banco Continental', 'Scotiabank'))) {
            $data = array(
                "name" => $identificacion,
                "country" => $pais->iso,
                "currency" => $Usuario->moneda,
                "detail" => [
                    array(
                        "index" => "1",
                        "transactionId" => $order_id,
                        "date" => date("m-d-Y"),
                        "userName" => $name,
                        "accountNumber" => $Usuario->usuarioId,
                        "amount" => $valorFinal,
                        "currency" => $Usuario->moneda,
                        "amountUsd" => $amountUsd,
                        "merchant" => " ",
                        "bank" => $Producto->externoId,
                        "customerName" => $name,
                        "accountType" => $typeAccount,
                        "paymentAccount" => trim($UsuarioBanco->getCodigo()),
                        "customerIdType" => $tipoDoc,
                        "customerId" => $vat_id,
                        "department" => $department,
                        "cciNumber" => trim($UsuarioBanco->getCodigo()),
                        "reference" => " ",
                        "kycyn" => " ",
                        "customerEmail" => $user_email,
                        "customerCellphone" => $phone_number,
                    )
                ]
            );
        } elseif (($pais->iso == 'MEX' && $Mandante == 18) || ($pais->iso == "HND")) {
            $unique = uniqid();
            $currentDateTime = date("Y-m-d_H-i-s");
            $uniqueId = md5($currentDateTime . '_' . $unique);
            $data = array(
                "country" => $pais->iso,
                "amount" => $valorFinal * 100,
                "currency" => $Usuario->moneda,
                "orderId" => base64_encode($order_id . '_' . $uniqueId),
                "beneficiary" => array(
                    "customerId" => $Usuario->usuarioId,
                    "name" => $name,
                    "lastName" => $LastName,
                    "email" => $Registro->email,
                    "document" => array(
                        "type" => $tipoDoc,
                        "number" => $vat_id,
                    ),
                ),
                "destination" => array(
                    "bankAccount" => array(
                        "bankCode" => $Producto->externoId,
                        "accountType" => $typeAccount,
                        "clabe" => $UsuarioBanco->cuenta,
                    ),
                )
            );
            
            //Data para procesar solicitudes de retiro en Honduras.
            if ($pais->iso == "HND") {
                unset($data['destination']['bankAccount']['clabe']);
                $data['destination']['bankAccount']['accountNumber'] = $UsuarioBanco->cuenta;
            }

        } else {
            $data = array(
                "name" => $identificacion,
                "country" => $pais->iso,
                "currency" => $Usuario->moneda,
                "detail" => [
                    array(
                        "index" => "1",
                        "transactionId" => $order_id,
                        "date" => date("m-d-Y"),
                        "userName" => $name,
                        "accountNumber" => $Usuario->usuarioId,
                        "amount" => $valorFinal,
                        "currency" => $Usuario->moneda,
                        "amountUsd" => $amountUsd,
                        "merchant" => " ",
                        "bank" => $Producto->externoId,
                        "customerName" => $name,
                        "accountType" => $typeAccount,
                        "paymentAccount" => trim($account_id),
                        "customerIdType" => $tipoDoc,
                        "customerId" => $vat_id,
                        "department" => $department,
                        "cciNumber" => $UsuarioBanco->getCodigo(),
                        "reference" => " ",
                        "kycyn" => " ",
                        "customerEmail" => $user_email,
                        "customerCellphone" => $phone_number,
                    )
                ]
            );
        }

        //Request para procesar solicitudes de retiro en Mexico y Honduras.
        if (($pais->iso == 'MEX' && $Mandante == 18) || ($pais->iso == "HND")) {
            $this->tipo = "/payouts";
            $result = $this->createTransactionMEX($data, $PAYOUT_MERCHANT_ID, $API_SECRET_PAYOUT, $KEY_MONNET_PAYOUT, $URLPAYOUTMEX);
        } else {
            $this->tipo = "/payroll";
            $result = $this->createTransactionPOST($data, $URLPAYOUT, $PAYOUT_MERCHANT_ID, $KEY_MONNET_PAYOUT);
        }

        try {
            syslog(LOG_WARNING, 'MONNET DATA PAYOUT: ' . json_encode($data) . " RESPONSE: " . $result);
        } catch (Exception $e) {
        }

        $result = json_decode($result);

        if ($result != "" && $result != null && $result->numberRejected === 1) {
            $idPayroll = $result->idPayroll;

            sleep(1);
            $resultConsult = $this->consultPayrollGET($idPayroll, $URLPAYOUT, $PAYOUT_MERCHANT_ID, $KEY_MONNET_PAYOUT);
            $resultConsult = json_decode($resultConsult);
            $detail = $resultConsult->detail[0];

            $errorMsg = $detail->errorMsg;

            if (strpos($errorMsg, "ya ha sido procesado") !== false || strpos($errorMsg, "DUPLICADO") !== false) {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago');
                $TransprodLog->setTValue(json_encode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                // $result=json_decode($result);

                $TransaccionProducto->setExternoId($idPayroll);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);


                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();
            } else {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago Monnet');
                //$TransprodLog->setTValue(json_encode(array_merge($final, (array)$result)));
                $TransprodLog->setTValue(json_encode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);


                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $TransprodLogMysqlDAO->insert($TransprodLog);

                $Transaction->commit();


                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('R');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Rechazo Solicitud de pago por Monnet');
                //$TransprodLog->setTValue(json_encode(array_merge($final, (array)$result)));
                $TransprodLog->setTValue(json_encode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);


                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $TransaccionProducto = new TransaccionProducto($transproductoId);
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                $TransaccionProducto->setEstado('R');
                $TransaccionProducto->setExternoId(0);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                $TransprodLogMysqlDAO->insert($TransprodLog);


                if (($CuentaCobro->getEstado() != "I" || ($CuentaCobro->getEstado() == "I" && $CuentaCobro->getPuntoventaId() == "0")) && $CuentaCobro->getEstado() != "R" && $CuentaCobro->getEstado() != "E") {
                    if ($CuentaCobro->getEstado() == "I") {
                        $CuentaCobro->setEstado('D');
                    } else {
                        $CuentaCobro->setEstado('R');
                    }
                    $CuentaCobro->setUsurechazaId(0);
                    $CuentaCobro->setMensajeUsuario('Rechazo por proveedor');
                    $CuentaCobro->setObservacion('Rechazo por proveedor. ' . $errorMsg);
                    $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));

                    if ($CuentaCobro->getUsupagoId() == "") {
                        $CuentaCobro->setUsupagoId(0);
                    }

                    if ($CuentaCobro->getFechaAccion() == "") {
                        $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                    }

                    if ($CuentaCobro->getFechaCambio() == "") {
                        $CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
                    }


                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado!='R' AND estado!='E')");
                    if ($rowsUpdate <= 0) {
                        throw new Exception('No se puede realizar la cancelacion', '21001');
                    }

                    $Usuario = new Usuario($CuentaCobro->getUsuarioId());
                    $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());

                    if ($UsuarioPerfil->getPerfilId() == "USUONLINE") {
                        $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroMySqlDAO->getTransaction());

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(40);
                        $UsuarioHistorial->setValor($CuentaCobro->getValor());
                        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    } else {
                        if ($CuentaCobro->version == '3') {
                            $Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();
                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);
                        }
                    }
                }


                $Transaction->commit();
                throw new Exception("No se pudo realizar la transaccion", "100000");
            }
        } elseif ($result != "" && $result != null && $result->numberRejected === 0) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->idPayroll);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } elseif ($result != "" && $result != null && $result->output->status == 'CREATED') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->idPayroll);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Crea una transacción en el servicio MEX de Monnet.
     *
     * @param array  $data               Datos de la transacción.
     * @param string $PAYOUT_MERCHANT_ID ID del comerciante de pago.
     * @param string $API_SECRET_PAYOUT  Clave secreta de la API de pago.
     * @param string $KEY_MONNET_PAYOUT  Clave de Monnet para pagos.
     * @param string $URLPAYOUTMEX       URL del servicio MEX de Monnet.
     *
     * @return string Respuesta del servicio.
     */
    function createTransactionMEX($data, $PAYOUT_MERCHANT_ID, $API_SECRET_PAYOUT, $KEY_MONNET_PAYOUT, $URLPAYOUTMEX)
    {
        $timestamp = time();

        $hashedBody = $this->verificacion256(json_encode($data));

        $resourcePath = '/api/v1/' . $PAYOUT_MERCHANT_ID . $this->tipo;

        $concat = 'POST:' . $resourcePath . '?timestamp=' . $timestamp . ':' . $hashedBody;

        $signature = $this->calculate_hmac_sha256($concat, $API_SECRET_PAYOUT);

        $url = $URLPAYOUTMEX . '/api/v1/' . $PAYOUT_MERCHANT_ID . $this->tipo . '?timestamp=' . $timestamp . '&signature=' . $signature;

        $header = array(
            'monnet-api-key: ' . $KEY_MONNET_PAYOUT,
            'Content-Type: application/json'
        );

        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();
        if ($response == '' || $response == null) {
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'EMPTY MONNET' . json_encode($data) . "' '#alertas-integraciones' > /dev/null & ");
        }
        return $response;
    }

    /**
     * Crea una transacción POST en el servicio Monnet.
     *
     * @param array  $data               Datos de la transacción.
     * @param string $URLPAYOUT          URL del servicio de pago.
     * @param string $PAYOUT_MERCHANT_ID ID del comerciante de pago.
     * @param string $KEY_MONNET_PAYOUT  Clave de Monnet para pagos.
     *
     * @return string Respuesta del servicio.
     */
    function createTransactionPOST($data, $URLPAYOUT, $PAYOUT_MERCHANT_ID, $KEY_MONNET_PAYOUT)
    {
        $url = $URLPAYOUT . '/ms-payroll-trx/commerce/' . $PAYOUT_MERCHANT_ID . $this->tipo;

        $String = $PAYOUT_MERCHANT_ID . $KEY_MONNET_PAYOUT;

        $verfication = $this->verificacion256($String);

        $header = array(
            'verification: ' . $verfication,
            'Content-Type: application/json'
        );

        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();
        if ($response == '' || $response == null) {
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'EMPTY MONNET' . json_encode($data) . "' '#alertas-integraciones' > /dev/null & ");
        }
        return $response;
    }

    /**
     * Agrega ceros al principio de un número hasta que tenga 10 caracteres.
     *
     * @param integer|string $numero Número al que se le agregarán ceros.
     *
     * @return string Número con ceros al principio.
     */
    public function agregarCerosAlPrincipio($numero)
    {
        $numeroStr = (string)$numero;
        if (strlen($numeroStr) >= 10) {
            $numeroStr = substr($numeroStr, 0, 10);
        } else {
            $cerosAAgregar = 10 - strlen($numeroStr);
            if ($cerosAAgregar > 0) {
                $numeroStr = str_repeat('0', $cerosAAgregar) . $numeroStr;
            }
        }
        return $numeroStr;
    }

    /**
     * Consulta el estado de una nómina en el servicio Monnet.
     *
     * @param string $idPayroll          ID de la nómina a consultar.
     * @param string $URLPAYOUT          URL del servicio de pago.
     * @param string $PAYOUT_MERCHANT_ID ID del comerciante de pago.
     * @param string $KEY_MONNET_PAYOUT  Clave de Monnet para pagos.
     *
     * @return string Respuesta del servicio.
     */
    function consultPayrollGET($idPayroll, $URLPAYOUT, $PAYOUT_MERCHANT_ID, $KEY_MONNET_PAYOUT)
    {
        $url = $URLPAYOUT . '/ms-payroll-back/commerce/' . $PAYOUT_MERCHANT_ID . '/payroll/load/' . $idPayroll;

        $String = $PAYOUT_MERCHANT_ID . $idPayroll . $KEY_MONNET_PAYOUT;

        $verfication = $this->verificacion256($String);

        $header = array(
            'verification: ' . $verfication
        );

        $curl =  new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();
        return $response;
    }
}
