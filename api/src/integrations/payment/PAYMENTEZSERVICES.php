<?php

/**
 * Clase PAYMENTEZSERVICES
 *
 * Esta clase proporciona servicios de integración con la API de Paymentez para realizar operaciones
 * relacionadas con pagos, tarjetas de crédito y transacciones. Incluye métodos para crear solicitudes
 * de pago, agregar y eliminar tarjetas, listar tarjetas, realizar reembolsos y manejar conexiones con
 * la API de Paymentez.
 *
 * @category Integración
 * @package  Backend\integrations\payment
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Ciudad;
use Backend\dto\Departamento;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use DateTime;
use Exception;

/**
 * Clase que proporciona servicios de integración con la API de Paymentez.
 *
 * Esta clase incluye métodos para realizar operaciones relacionadas con pagos,
 * tarjetas de crédito, transacciones y más.
 */
class PAYMENTEZSERVICES
{
    /**
     * Clave API del servidor.
     *
     * @var string
     */
    private $API_KEYSERVER = "";

    /**
     * Clave API del servidor en entorno de desarrollo.
     *
     * @var string
     */
    private $API_KEYSERVER_DEV = "86fS13mpzBv0COdndaR3wAnzWRW4lu";

    /**
     * Clave API del servidor en entorno de producción.
     *
     * @var string
     */
    private $API_KEYSERVER_PROD = "86fS13mpzBv0COdndaR3wAnzWRW4lu";

    /**
     * Login API del servidor.
     *
     * @var string
     */
    private $API_LOGINSERVER = "";

    /**
     * Login API del servidor en entorno de desarrollo.
     *
     * @var string
     */
    private $API_LOGINSERVER_DEV = "PMTZ-LINKTOPAY-PEN-SERVER";

    /**
     * Login API del servidor en entorno de producción.
     *
     * @var string
     */
    private $API_LOGINSERVER_PROD = "";

    /**
     * Login API del cliente.
     *
     * @var string
     */
    private $API_LOGINCLIENT = "";

    /**
     * Login API del cliente en entorno de desarrollo.
     *
     * @var string
     */
    private $API_LOGINCLIENT_DEV = "";

    /**
     * Login API del cliente en entorno de producción.
     *
     * @var string
     */
    private $API_LOGINCLIENT_PROD = "";

    /**
     * Clave API del cliente.
     *
     * @var string
     */
    private $API_KEYCLIENT = "";

    /**
     * Clave API del cliente en entorno de desarrollo.
     *
     * @var string
     */
    private $API_KEYCLIENT_DEV = "";

    /**
     * Clave API del cliente en entorno de producción.
     *
     * @var string
     */
    private $API_KEYCLIENT_PROD = "";

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base en entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://noccapi-stg.paymentez.com';

    /**
     * URL base en entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://noccapi.paymentez.com';

    /**
     * URL para operaciones con tarjetas.
     *
     * @var string
     */
    private $URLCards = "";

    /**
     * URL para operaciones con tarjetas en entorno de desarrollo.
     *
     * @var string
     */
    private $URLCardsDEV = 'https://ccapi-stg.paymentez.com';

    /**
     * URL para operaciones con tarjetas en entorno de producción.
     *
     * @var string
     */
    private $URLCardsPROD = 'https://ccapi.paymentez.com';

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
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/paymentez/confirm/";

    /**
     * URL de callback en entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/paymentez/confirm/";

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * Token de autenticación del servidor.
     *
     * @var string
     */
    private $tokenSERVER = "";

    /**
     * Token de autenticación del cliente.
     *
     * @var string
     */
    private $tokenCLIENT = "";

    /**
     * Tipo de operación a realizar.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Tipo de transacción.
     *
     * @var string
     */
    private $transactiontype = "";

    /**
     * Constructor de la clase.
     * Configura las URLs y el callback según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->URLCards = $this->URLCardsDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->URLCards = $this->URLCardsPROD;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza el pago.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Pais = $Usuario->paisId;

        $mandante = $Usuario->mandante;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = 'PMTZ-LINKTOPAY-PEN-CLIENT';
                $this->API_KEYCLIENT = 'a0P6K2XVtRGhu8bAyE0ZIdqKyTyOOI';

                $this->API_LOGINSERVER = 'PMTZ-LINKTOPAY-PEN-SERVER';
                $this->API_KEYSERVER = '86fS13mpzBv0COdndaR3wAnzWRW4lu';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = 'LINKTOPAY01-EC-CLIENT';
                $this->API_KEYCLIENT = 'METbb1aqKdsN4gFrQELBTicscckHgg';

                $this->API_LOGINSERVER = 'LINKTOPAY01-EC-SERVER';
                $this->API_KEYSERVER = 'G8vwvaASAZHQgoVuF2eKZyZF5hJmvx';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEI-ELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'rytqnr2AUqsHN4VAW2tSAzJUaNNvXN';

                $this->API_LOGINSERVER = 'NVEI-ELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'OWd2xIfkFNKRKtOMNyG9UiyBVuVlTr';
            }
        } else {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEIELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'ggiCStnqxtNnkrq6GzjPjJzVhY1csE';

                $this->API_LOGINSERVER = 'NVEIELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'q3iaarEKWoSMOgIAqS8rzcb62zqV2B';
            }
        }


        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $Registro = new Registro("", $usuario_id);
        $pais = new Pais($Pais);
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

        $data = array(
            "carrier" => array(
                "id" => $Producto->getExternoId(),
                "extra_params" => array(
                    "user" => array(
                        "name" => $Registro->getNombre1(),
                        "last_name" => $Registro->getApellido1(),
                        "phone" => $Usuario->celular,
                    ),
                ),
            ),
            "user" => array(
                "id" => $Usuario->usuarioId,
                "email" => $email
            ),
            "order" => array(
                "dev_reference" => $transproductoId,
                "amount" => $valorTax,
                "description" => $descripcion,
                "country" => $CountryCode,
                "currency" => $moneda,
            ),
        );


        $this->token = $this->tokenServer();
        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $this->tipo = "/order/";
        $Result = $this->connection($data);

        if ($Result != '') {
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

            //$response = json_decode($Result);

            $response = json_decode($Result);

            $data = array();
            $data["success"] = true;
            $data["url"] = $response->transaction->url_reference;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Crea una solicitud de pago con token de tarjeta.
     *
     * @param Usuario  $Usuario  Objeto del usuario que realiza el pago.
     * @param Producto $Producto Objeto del producto asociado al pago.
     * @param float    $valor    Monto del pago.
     * @param integer  $id       ID de la tarjeta de crédito.
     * @param string   $cvv      Código de seguridad de la tarjeta.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function createRequestPayment2(Usuario $Usuario, Producto $Producto, $valor, $id, $cvv)
    {
        $Pais = $Usuario->paisId;

        $mandante = $Usuario->mandante;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = 'PMTZ-LINKTOPAY-PEN-CLIENT';
                $this->API_KEYCLIENT = 'a0P6K2XVtRGhu8bAyE0ZIdqKyTyOOI';

                $this->API_LOGINSERVER = 'PMTZ-LINKTOPAY-PEN-SERVER';
                $this->API_KEYSERVER = '86fS13mpzBv0COdndaR3wAnzWRW4lu';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = 'LINKTOPAY01-EC-CLIENT';
                $this->API_KEYCLIENT = 'METbb1aqKdsN4gFrQELBTicscckHgg';

                $this->API_LOGINSERVER = 'LINKTOPAY01-EC-SERVER';
                $this->API_KEYSERVER = 'G8vwvaASAZHQgoVuF2eKZyZF5hJmvx';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEI-ELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'rytqnr2AUqsHN4VAW2tSAzJUaNNvXN';

                $this->API_LOGINSERVER = 'NVEI-ELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'OWd2xIfkFNKRKtOMNyG9UiyBVuVlTr';
            }
        } else {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEIELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'ggiCStnqxtNnkrq6GzjPjJzVhY1csE';

                $this->API_LOGINSERVER = 'NVEIELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'q3iaarEKWoSMOgIAqS8rzcb62zqV2B';
            }
        }

        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioTarjetacredito = new UsuarioTarjetacredito($id);

        $token = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token);
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Debito con token";
        $Registro = new Registro("", $usuario_id);

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

        $data = array(
            "order" => array(
                "amount" => $valorTax,
                "description" => $descripcion,
                "dev_reference" => $transproductoId,
                "vat" => 0,  //Importe del impuesto sobre las ventas, incluido en el costo del producto. Preguntar
                "tax_percentage" => 0  //Solo disponible para Ecuador. La tasa de impuestos que se aplicará a este pedido. Debe ser 0 o 12.. Preguntar
            ),
            "user" => array(
                "id" => $Usuario->usuarioId,
                "email" => $email
            ),
            "card" => array(
                "token" => $token,
                "cvc" => $cvv

            )
        );


        $this->token = $this->tokenServer();
        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $this->tipo = "/v2/transaction/debit/";
        $Result = $this->connectioncards($data);


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
        if ($response->transaction->status == "success") {
            $data["success"] = true;
            $data["Response"] = "Deposito Realizado";
        }
        if ($response->transaction->status == "Rejected") {
            $data = array();
            $data["success"] = false;
            $data["Response"] = "Error en la transacción";
        }


        return json_decode(json_encode($data));
    }

    /**
     * Genera un token de autenticación para el cliente.
     *
     * @return string Token de autenticación en formato base64.
     */
    public function tokenClient()
    {
        $date = new DateTime();
        $unix_timestamp = $date->getTimestamp();

        $uniq_token_string = $this->API_KEYCLIENT . $unix_timestamp;
        $uniq_token_hash = hash('sha256', $uniq_token_string);
        $auth_token = base64_encode($this->API_LOGINCLIENT . ";" . $unix_timestamp . ";" . $uniq_token_hash);

        return $auth_token;
    }

    /**
     * Genera un token de autenticación para el servidor.
     *
     * @return string Token de autenticación en formato base64.
     */
    public function tokenServer()
    {
        $date = new DateTime();
        $unix_timestamp = $date->getTimestamp();

        $uniq_token_string = $this->API_KEYSERVER . $unix_timestamp;
        $uniq_token_hash = hash('sha256', $uniq_token_string);
        $auth_token = base64_encode($this->API_LOGINSERVER . ";" . $unix_timestamp . ";" . $uniq_token_hash);

        return $auth_token;
    }

    /**
     * Agrega una tarjeta de crédito al usuario.
     *
     * @param Usuario $Usuario      Objeto del usuario.
     * @param string  $numTarjeta   Número de la tarjeta.
     * @param string  $holder_name  Nombre del titular de la tarjeta.
     * @param integer $expiry_month Mes de expiración de la tarjeta.
     * @param integer $expiry_year  Año de expiración de la tarjeta.
     * @param string  $cvc          Código de seguridad de la tarjeta.
     * @param integer $ProveedorId  ID del proveedor asociado.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function addCard(Usuario $Usuario, $numTarjeta, $holder_name, $expiry_month, $expiry_year, $cvc, $ProveedorId)
    {
        $Pais = $Usuario->paisId;

        $mandante = $Usuario->mandante;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = 'PMTZ-LINKTOPAY-PEN-CLIENT';
                $this->API_KEYCLIENT = 'a0P6K2XVtRGhu8bAyE0ZIdqKyTyOOI';

                $this->API_LOGINSERVER = 'PMTZ-LINKTOPAY-PEN-SERVER';
                $this->API_KEYSERVER = '86fS13mpzBv0COdndaR3wAnzWRW4lu';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = 'LINKTOPAY01-EC-CLIENT';
                $this->API_KEYCLIENT = 'METbb1aqKdsN4gFrQELBTicscckHgg';

                $this->API_LOGINSERVER = 'LINKTOPAY01-EC-SERVER';
                $this->API_KEYSERVER = 'G8vwvaASAZHQgoVuF2eKZyZF5hJmvx';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEI-ELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'rytqnr2AUqsHN4VAW2tSAzJUaNNvXN';

                $this->API_LOGINSERVER = 'NVEI-ELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'OWd2xIfkFNKRKtOMNyG9UiyBVuVlTr';
            }
        } else {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEIELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'ggiCStnqxtNnkrq6GzjPjJzVhY1csE';

                $this->API_LOGINSERVER = 'NVEIELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'q3iaarEKWoSMOgIAqS8rzcb62zqV2B';
            }
        }


        $Proveedor = new Proveedor($ProveedorId);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $this->token = $this->tokenClient();
        $num = 20;
        $data = array(
            "user" => array(
                "id" => $Usuario->usuarioId,
                "email" => $Usuario->login
            ),
            "card" => array(
                "number" => $numTarjeta,
                "holder_name" => $holder_name,
                "expiry_month" => intval($expiry_month),
                "expiry_year" => intval($num . intval($expiry_year)),
                "cvc" => $cvc,
            ),
        );

        $this->tipo = "/v2/card/add";
        $Result = $this->connectioncards($data);

        $Result = json_decode($Result);


        if ($Result->card->status == "valid") {
            $UsuarioTarjetacredito = new UsuarioTarjetacredito();
            $numTarjeta = substr_replace($numTarjeta, '********', 4, 8);
            $UsuarioTarjetacredito->setUsuarioId($Usuario->usuarioId);
            $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
            $UsuarioTarjetacredito->setCuenta($numTarjeta);
            $UsuarioTarjetacredito->setCvv('');
            $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
            $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($Result->card->token));
            $UsuarioTarjetacredito->setEstado('A');
            $UsuarioTarjetacredito->setUsucreaId('0');
            $UsuarioTarjetacredito->setUsumodifId('0');
            $UsuarioTarjetacredito->setDescripcion($Result->card->type);

            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
            $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
            $data = array();
            $data["success"] = true;
            $data["Message"] = "Guardado Correctamente";
        }

        if ($Result->error != "") {
            $data = array();
            $data["success"] = false;
            $data["Message"] = $Result->error->type;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Elimina una tarjeta de crédito del usuario.
     *
     * @param Usuario $Usuario Objeto del usuario.
     * @param integer $id      ID de la tarjeta de crédito.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function deleteCard(Usuario $Usuario, $id)
    {
        $Pais = $Usuario->paisId;

        $mandante = $Usuario->mandante;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = 'PMTZ-LINKTOPAY-PEN-CLIENT';
                $this->API_KEYCLIENT = 'a0P6K2XVtRGhu8bAyE0ZIdqKyTyOOI';

                $this->API_LOGINSERVER = 'PMTZ-LINKTOPAY-PEN-SERVER';
                $this->API_KEYSERVER = '86fS13mpzBv0COdndaR3wAnzWRW4lu';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = 'LINKTOPAY01-EC-CLIENT';
                $this->API_KEYCLIENT = 'METbb1aqKdsN4gFrQELBTicscckHgg';

                $this->API_LOGINSERVER = 'LINKTOPAY01-EC-SERVER';
                $this->API_KEYSERVER = 'G8vwvaASAZHQgoVuF2eKZyZF5hJmvx';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEI-ELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'rytqnr2AUqsHN4VAW2tSAzJUaNNvXN';

                $this->API_LOGINSERVER = 'NVEI-ELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'OWd2xIfkFNKRKtOMNyG9UiyBVuVlTr';
            }
        } else {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEIELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'ggiCStnqxtNnkrq6GzjPjJzVhY1csE';

                $this->API_LOGINSERVER = 'NVEIELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'q3iaarEKWoSMOgIAqS8rzcb62zqV2B';
            }
        }

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioTarjetacredito = new UsuarioTarjetacredito($id);

        $token = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token);

        $this->token = $this->tokenServer();
        $data = array(
            "card" => array(
                "token" => $token
            ),
            "user" => array(
                "id" => $UsuarioTarjetacredito->usuarioId
            )

        );

        $this->tipo = "/v2/card/delete/";
        $Result = $this->connectioncards($data);

        $Result = json_decode($Result);
        if ($Result->message == "card deleted") {
            $UsuarioTarjetacredito->setEstado('I');

            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
            $UsuarioTarjetacreditoMySqlDAO->update($UsuarioTarjetacredito);
            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
            $data = array();
            $data["success"] = true;
            $data["Message"] = $Result->message;
        }

        if ($Result->error != "") {
            $data = array();
            $data["success"] = false;
            $data["Message"] = $Result->error->type;
        }


        return json_decode(json_encode($data));
    }

    /**
     * Obtiene todas las tarjetas de crédito asociadas al usuario.
     *
     * @param Usuario $Usuario Objeto del usuario.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function GetAllCard(Usuario $Usuario)
    {
        $this->token = $this->tokenClient();
        $data = array(
            "uid" => $Usuario->usuarioId
        );

        $this->tipo = "/v2/card/list";
        $Result = $this->connectioncards($data);

        $Result = json_decode($Result);
        if ($Result != "") {
            $data = array();
            $data["success"] = true;
            $data["data"] = $Result;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza un reembolso de una transacción.
     *
     * @param Usuario $Usuario       Objeto del usuario.
     * @param integer $transaccionId ID de la transacción a reembolsar.
     * @param float   $valor         Monto a reembolsar.
     *
     * @return object Respuesta de la API en formato JSON.
     */
    public function refund(Usuario $Usuario, $transaccionId, $valor)
    {
        $Pais = $Usuario->paisId;

        $mandante = $Usuario->mandante;
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = 'PMTZ-LINKTOPAY-PEN-CLIENT';
                $this->API_KEYCLIENT = 'a0P6K2XVtRGhu8bAyE0ZIdqKyTyOOI';

                $this->API_LOGINSERVER = 'PMTZ-LINKTOPAY-PEN-SERVER';
                $this->API_KEYSERVER = '86fS13mpzBv0COdndaR3wAnzWRW4lu';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = 'LINKTOPAY01-EC-CLIENT';
                $this->API_KEYCLIENT = 'METbb1aqKdsN4gFrQELBTicscckHgg';

                $this->API_LOGINSERVER = 'LINKTOPAY01-EC-SERVER';
                $this->API_KEYSERVER = 'G8vwvaASAZHQgoVuF2eKZyZF5hJmvx';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEI-ELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'rytqnr2AUqsHN4VAW2tSAzJUaNNvXN';

                $this->API_LOGINSERVER = 'NVEI-ELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'OWd2xIfkFNKRKtOMNyG9UiyBVuVlTr';
            }
        } else {
            if ($Pais == "173" && $mandante == "0") {
                $CountryCode = "PER";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }
            if ($Pais == "66" && $mandante == "8") {
                $CountryCode = "ECU";
                $this->API_LOGINCLIENT = '';
                $this->API_KEYCLIENT = '';

                $this->API_LOGINSERVER = '';
                $this->API_KEYSERVER = '';
            }

            if ($Pais == "146" && $mandante == "13") {
                $CountryCode = "MEX";
                $this->API_LOGINCLIENT = 'NVEIELTRIBET-MXN-CLIENT';
                $this->API_KEYCLIENT = 'ggiCStnqxtNnkrq6GzjPjJzVhY1csE';

                $this->API_LOGINSERVER = 'NVEIELTRIBET-MXN-SERVER';
                $this->API_KEYSERVER = 'q3iaarEKWoSMOgIAqS8rzcb62zqV2B';
            }
        }

        $this->token = $this->tokenServer();
        $data = array(
            "transaction" => array(
                "id" => $transaccionId
            ),
            "order" => array(
                "amount" => intval($valor)
            ),
            "more_info" => false
        );

        $this->tipo = "/v2/transaction/refund/";
        $Result = $this->connectioncards($data);

        $Result = json_decode($Result);


        if ($Result->status == "success") {
            $data = array();
            $data["success"] = true;
            $data["Message"] = $Result;
        }

        if ($Result->status != "success") {
            $data = array();
            $data["success"] = false;
            $data["Message"] = $Result->error->type;
        }


        return json_decode(json_encode($data));
    }

    /**
     * Realiza una conexión con la API de Paymentez.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la API.
     */
    public function connection($data)
    {
        $data = json_encode($data);


        $curl = curl_init($this->URL . $this->tipo);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Auth-Token:' . $this->token]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        syslog(LOG_WARNING, " PAYMENTEZTARJRESP  " . $result);
        return $result;
    }

    /**
     * Realiza una conexión con la API de Paymentez para operaciones con tarjetas.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la API.
     */
    public function connectioncards($data)
    {
        $data = json_encode($data);
        syslog(LOG_WARNING, " PAYMENTEZTARJDATA  " . $data);

        $curl = curl_init($this->URLCards . $this->tipo);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Auth-Token:' . $this->token]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
