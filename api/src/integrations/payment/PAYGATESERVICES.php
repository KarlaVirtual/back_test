<?php

/**
 * Clase `PAYGATESERVICES` para la integración con el servicio de pagos PayGate.
 *
 * Este archivo contiene la implementación de métodos para realizar operaciones
 * relacionadas con pagos, como agregar tarjetas, crear solicitudes de pago,
 * y manejar conexiones con la API de PayGate.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-25
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
 * Clase `PAYGATESERVICES` para manejar la integración con el servicio de pagos PayGate.
 *
 * Proporciona métodos para realizar operaciones relacionadas con pagos, como agregar tarjetas,
 * crear solicitudes de pago y manejar conexiones con la API de PayGate.
 */
class PAYGATESERVICES
{
    /**
     * Esta propiedad almacena la ruta base para las solicitudes a la API de PayGate.
     *
     * @var string $path Ruta de la API de PayGate
     */
    private $path = '';

    /**
     * Constructor de la clase `PAYGATESERVICES`.
     *
     * Inicializa las propiedades de la clase dependiendo del entorno de ejecución
     * (desarrollo o producción). Configura las URLs, tokens y callback\_urls
     * correspondientes según el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Agrega una tarjeta de crédito al sistema y realiza un pago.
     *
     * @param Usuario  $Usuario      Objeto del usuario que realiza la operación.
     * @param Producto $Producto     Objeto del producto asociado al pago.
     * @param string   $numTarjeta   Número de la tarjeta de crédito.
     * @param string   $holder_name  Nombre del titular de la tarjeta.
     * @param integer  $expiry_month Mes de expiración de la tarjeta.
     * @param integer  $expiry_year  Año de expiración de la tarjeta.
     * @param string   $cvv          Código de seguridad de la tarjeta.
     * @param integer  $ProveedorId  ID del proveedor asociado.
     * @param float    $valor        Monto de la transacción.
     * @param boolean  $saveCard     Indica si se debe guardar la tarjeta.
     * @param string   $requestId    ID de la solicitud.
     * @param string   $referenceId  ID de referencia.
     *
     * @return object Respuesta de la API de PayGate.
     */
    public function addCard(Usuario $Usuario, Producto $Producto, $numTarjeta, $holder_name, $expiry_month, $expiry_year, $cvv, $ProveedorId, $valor, $saveCard, $requestId, $referenceId)
    {
        $Pais = new Pais($Usuario->paisId);

        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($mandante);
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $Proveedor = new Proveedor($ProveedorId);

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

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $sessionID = time();
        $num = 20;

        $Ip = explode(',', $this->get_client_ip());
        $Ip = $Ip[0];

        $ServerIp = explode(",", $_SERVER["SERVER_ADDR"]);

        $firstName = str_replace('ñ', 'n', $Registro->nombre1);
        $lastName = str_replace('ñ', 'n', $Registro->apellido1);
        $Celular = $Registro->celular;
        $Email = $Registro->email;
        $Currency = $Usuario->moneda;
        $Cedula = $Registro->cedula;
        $codigoPostal = $Registro->codigoPostal;
        $Pais = $Pais->iso;

        $line1 = 'Direccion';
        if ($Registro->direccion != '') {
            $line1 = $Registro->direccion;
        }

        $Mandante = new Mandante($mandante);

        if ($Mandante->baseUrl != '') {
            $URLREDIRECTION = $Mandante->baseUrl . "gestion/deposito";
        }

        $Credentials = $this->Credentials($Usuario, $Producto);

        $CardType = $this->validateCreditCard($numTarjeta);
        $OrigNumTarjeta = $numTarjeta;

        if ($saveCard == true) {
            $data = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "validThru" => $expiry_month . '/' . $expiry_year,
                "safeIdentifier" => $OrigNumTarjeta,
                "cvv" => $cvv,
                "amount" => $valorTax,
                "tax" => 0,
                "description" => $transproductoId,
                "currency" => "HNL",
                "idNumber" => "1007757723",
                "saveCard" => "true",
                "email" => $Registro->email,
                "b2cc" => "HN",
                "billingState" => "",
                "billingCity" => "",
                "billingPostCode" => "",
                "billingAddress1" => ""
            );
        } else {
            $data = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "validThru" => $expiry_month . '/' . $expiry_year,
                "safeIdentifier" => $OrigNumTarjeta,
                "cvv" => $cvv,
                "amount" => $valorTax,
                "tax" => 0,
                "description" => $transproductoId,
                "currency" => "HNL",
                "email" => $Registro->email,
                "b2cc" => "HN",
                "billingState" => "",
                "billingCity" => "",
                "billingPostCode" => "",
                "billingAddress1" => ""
            );
        }

        $time = time();

        syslog(LOG_WARNING, "DATA PAYGATE : " . $time . ' ' . json_encode($data));

        $this->path = "/payments";

        $Result = $this->connection($data, $Credentials);

        syslog(LOG_WARNING, "RESPONSE PAYGATE: " . $time . ' ' . $Result);

        $Result = json_decode($Result);

        if ($saveCard == true && $Result->status == "APPROVED") {
            $UsuarioTarjetacredito = new UsuarioTarjetacredito();
            $numTarjeta = substr_replace($numTarjeta, '********', 4, 8);
            $UsuarioTarjetacredito->setUsuarioId($Usuario->usuarioId);
            $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
            $UsuarioTarjetacredito->setCuenta($numTarjeta);
            $UsuarioTarjetacredito->setCvv('');
            $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
            $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($Result->information->cardID . '_' . $Result->information->customerID));
            $UsuarioTarjetacredito->setEstado('A');
            $UsuarioTarjetacredito->setUsucreaId('0');
            $UsuarioTarjetacredito->setUsumodifId('0');
            $UsuarioTarjetacredito->setDescripcion("");

            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
            $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
        }

        $transProduct = $this->agregarCerosAlPrincipio($transproductoId);

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
     * Crea una solicitud de pago utilizando una tarjeta guardada.
     *
     * @param Usuario               $Usuario               Objeto del usuario que realiza la operación.
     * @param Producto              $Producto              Objeto del producto asociado al pago.
     * @param float                 $valor                 Monto de la transacción.
     * @param UsuarioTarjetacredito $UsuarioTarjetacredito Objeto de la tarjeta guardada.
     * @param string                $requestId             ID de la solicitud.
     * @param string                $referenceId           ID de referencia.
     * @param string                $cvv                   Código de seguridad de la tarjeta.
     *
     * @return object Respuesta de la API de PayGate.
     */
    public function createRequestPayment($Usuario, $Producto, $valor, $UsuarioTarjetacredito, $requestId, $referenceId, $cvv)
    {
        $Proveedor = new Proveedor("", "PAYGATE");
        $Registro = new Registro("", $Usuario->usuarioId);
        //$UsuarioTarjetacredito = new UsuarioTarjetacredito("",$Usuario->usuarioId,$Proveedor->proveedorId);
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
        $firstName = $Usuario->nombre;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $Celular = $Registro->celular;
        $Email = $Registro->email;
        $descripcion = "Deposito";
        $Pais = $Pais->iso;

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

        $transProduct = $this->agregarCerosAlPrincipio($transproductoId);

        $tokenCard = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token);
        $tokenCard = explode("_", $tokenCard);
        $cardID = $tokenCard[0];
        $customerID = $tokenCard[1];

        $Ip = explode(',', $this->get_client_ip());
        $Ip = $Ip[0];
        $ServerIp = explode(",", $_SERVER["SERVER_ADDR"]);

        $data = array(
            "firstName" => $firstName,
            "amount" => $valorTax,
            "tax" => 0,
            "description" => $transproductoId,
            "payWithHSM" => true,
            "cardID" => $cardID,
            "customerID" => $customerID
        );

        $time = time();

        syslog(LOG_WARNING, "DATA PAYGATE : " . $time . ' ' . json_encode($data));

        $this->path = "/payments";

        $Result = $this->connection($data, $Credentials);

        syslog(LOG_WARNING, "RESPONSE PAYGATE: " . $time . ' ' . $Result);

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = $Result;

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result = json_decode($Result);

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
     * Agrega ceros al principio de un número para que tenga una longitud mínima de 6 caracteres.
     *
     * @param integer $numero Número a procesar.
     *
     * @return string Número con ceros agregados al principio.
     */
    public function agregarCerosAlPrincipio($numero)
    {
        $numeroStr = (string)$numero;
        if (strlen($numeroStr) >= 6) {
            $numeroStr = substr($numeroStr, 0, 6);
        } else {
            $cerosAAgregar = 6 - strlen($numeroStr);
            if ($cerosAAgregar > 0) {
                $numeroStr = str_repeat('0', $cerosAAgregar) . $numeroStr;
            }
        }
        return $numeroStr;
    }

    /**
     * Valida el tipo de tarjeta de crédito según su número.
     *
     * @param string $cardNumber Número de la tarjeta de crédito.
     *
     * @return string Código del tipo de tarjeta (Visa, MasterCard, etc.) o mensaje de error.
     */
    public function validateCreditCard($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        $visaPrefixes = array('4');
        $mastercardPrefixes = array('51', '52', '53', '54', '55', '22');

        $cardLength = strlen($cardNumber);
        if ($cardLength < 13 || $cardLength > 19) {
            return "Longitud de tarjeta inválida";
        }

        if (in_array(substr($cardNumber, 0, 1), $visaPrefixes)) {
            return "001"; //Visa
        }

        if (in_array(substr($cardNumber, 0, 2), $mastercardPrefixes)) {
            return "002"; //MasterCard
        }

        return "Tipo de tarjeta desconocido";
    }

    /**
     * Encripta los datos proporcionados.
     *
     * @param mixed $data Datos a encriptar.
     *
     * @return mixed Datos encriptados.
     */
    public function Encrypta($data)
    {
    }

    /**
     * Realiza una conexión HTTP POST con la API de PayGate.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la API.
     */
    public function connection($data, $Credentials)
    {
        $curl = new CurlWrapper($Credentials->URL . $this->path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $Credentials->URL . $this->path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $Credentials->TOKEN
            )
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente que realiza la solicitud.
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


}
