<?php

/**
 * Clase para gestionar servicios de integración de pagos con Nuvei.
 *
 * Este archivo contiene la implementación de la clase `NUVEISERVICES`, que permite
 * realizar solicitudes de pago, gestionar transacciones y realizar conexiones
 * con la API de Nuvei. Incluye métodos para calcular impuestos, generar URLs
 * de pago y realizar verificaciones de seguridad.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
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
 * Clase principal para gestionar los servicios de Nuvei.
 */
class NUVEISERVICES
{
    /**
     * URL de callback configurada dinámicamente según el entorno.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/nuvei/confirm/";

    /**
     * URL de callback en entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/nuvei/confirm/";

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de configuración dependiendo del entorno
     * (desarrollo o producción) utilizando la clase `ConfigurationEnvironment`.
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
     * Crea una solicitud de pago.
     *
     * Este método genera una solicitud de pago para un usuario y un producto
     * específico, calculando impuestos, generando un checksum y construyendo
     * la URL de pago para la integración con Nuvei.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza el pago.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Valor del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $cancel_url URL de redirección en caso de cancelación.
     *
     * @return object Respuesta con éxito y URL generada.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $cancel_url)
    {
        date_default_timezone_set('America/Bogota');

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $nombre = $Registro->nombre1;
        $apellido = $Registro->apellido1;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $tipoDocumento = $Registro->tipoDoc;

        $version = "4.0.0";
        $Descripcion = "Deposito";
        $documento = $Registro->cedula;
        $telefono = $Registro->telefono;
        $celular = $Registro->celular;
        $direccion = $Registro->direccion;
        $direccion = str_replace(" ", "", $direccion);
        $direccion = str_replace("#", "", $direccion);
        $country = $Pais->iso;
        $Region = $Registro->ciudad;
        $City = "test";//$Registro->ciudad;
        $ZipCode = $Registro->codigoPostal;
        $notificationUrl = $this->callback_url;
        $date = date("Y-m-d.H:i:s");

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

        $item_amount = intval($valorTax);
        $valor2 = number_format($valorTax, 2, '.', '');

        $item_open_amount_1 = "false";
        $item_min_amount_1 = 1;
        $item_max_amount_1 = 100;
        $item_discount_1 = 0;
        $numberofitems = 1;
        $total_tax = 0;
        $discount = 0;
        $encoding = "UTF-8";
        $payment_method = "cc_card";
        $payment_method_mode = "filter";
        $item_quantity = 1;
        $user_token = "auto";

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;
        $MERCHANT_ID = $Credentials->MERCHANT_ID;
        $MERCHANT_SITE_ID = $Credentials->MERCHANT_SITE_ID;
        $SECRET_KEY = $Credentials->SECRET_KEY;

        $String = $SECRET_KEY . $MERCHANT_ID . $MERCHANT_SITE_ID . $user_token . $usuario_id . "_" . $transproductoId . $usuario_id . "_" . $transproductoId . $item_open_amount_1 . $Descripcion . $item_amount . $item_quantity . $valor2 . $moneda . $version . $encoding . $nombre . $apellido . $direccion . $City . $ZipCode . $country . $celular . $email . $payment_method . $payment_method_mode . $urlSuccess . $urlFailed . $cancel_url . $this->callback_url . $date;

        $checksum = $this->verificacion256($String);

        $StringUrl = "?merchant_id=$MERCHANT_ID&merchant_site_id=$MERCHANT_SITE_ID&user_token=$user_token&user_token_id=$usuario_id" . "_" . "$transproductoId&userid=$usuario_id" . "_" . "$transproductoId&item_open_amount_1=$item_open_amount_1&item_name_1=$Descripcion&item_amount_1=$item_amount&item_quantity_1=$item_quantity&total_amount=$valor2&currency=$moneda&version=$version&encoding=$encoding&first_name=$nombre&last_name=$apellido&address1=$direccion&city=$City&zip=$ZipCode&country=$country&phone1=$celular&email=$email&payment_method=$payment_method&payment_method_mode=$payment_method_mode&success_url=$urlSuccess&pending_url=$urlFailed&error_url=$cancel_url&notify_url=$this->callback_url&time_stamp=$date&checksum=$checksum&";

        syslog(LOG_WARNING, "NUVEI DATA" . $StringUrl);

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

        $data = array();
        $data["success"] = true;
        $data["url"] = $URL . $StringUrl;


        return json_decode(json_encode($data));
    }

    /**
     * Crea una solicitud de pago con un método alternativo.
     *
     * Similar al método `createRequestPayment`, pero utiliza un método de pago
     * alternativo y puede incluir configuraciones específicas para ciertos
     * países o mandantes.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza el pago.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Valor del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $cancel_url URL de redirección en caso de cancelación.
     *
     * @return object Respuesta con éxito y URL generada.
     */
    public function createRequestPayment2(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $cancel_url)
    {
        date_default_timezone_set('America/Bogota');

        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $nombre = $Registro->nombre1;
        $apellido = $Registro->apellido1;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $tipoDocumento = $Registro->tipoDoc;

        $version = "4.0.0";
        $Descripcion = "Deposito";
        $documento = $Registro->cedula;
        $telefono = $Registro->telefono;
        $celular = $Registro->celular;
        $direccion = $Registro->direccion;
        $Ip = $Registro->dirIp;
        $direccion = str_replace(" ", "", $direccion);
        $direccion = str_replace("#", "", $direccion);
        $country = $Pais->iso;
        $Region = $Registro->ciudad;
        $City = "test";//$Registro->ciudad;
        $ZipCode = $Registro->codigoPostal;
        $notificationUrl = $this->callback_url;
        $date = date("Y-m-d.H:i:s");

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

        $item_amount = intval($valorTax);
        $valor2 = number_format($valorTax, 2, '.', '');

        $item_open_amount_1 = "false";
        $item_min_amount_1 = 1;
        $item_max_amount_1 = 100;
        $item_discount_1 = 0;
        $numberofitems = 1;
        $total_tax = 0;
        $discount = 0;
        $encoding = "UTF-8";
        $payment_method = "apmgw_STPmex";
        $payment_method_mode = "filter";

        $item_quantity = 1;
        $user_token = "auto";

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;
        $MERCHANT_ID = $Credentials->MERCHANT_ID;
        $MERCHANT_SITE_ID = $Credentials->MERCHANT_SITE_ID;
        $SECRET_KEY = $Credentials->SECRET_KEY;

        $String = $SECRET_KEY . $MERCHANT_ID . $MERCHANT_SITE_ID . $user_token . $usuario_id . "_" . $transproductoId . $usuario_id . "_" . $transproductoId . $item_open_amount_1 . $Descripcion . $item_amount . $item_quantity . $valor2 . $moneda . $version . $encoding . $nombre . $apellido . $direccion . $City . $ZipCode . $country . $celular . $email . $payment_method . $payment_method_mode . $urlSuccess . $urlFailed . $cancel_url . $this->callback_url . $date;

        $checksum = $this->verificacion256($String);

        $StringUrl = "?merchant_id=$MERCHANT_ID&merchant_site_id=$MERCHANT_SITE_ID&user_token=$user_token&user_token_id=$usuario_id" . "_" . "$transproductoId&userid=$usuario_id" . "_" . "$transproductoId&item_open_amount_1=$item_open_amount_1&item_name_1=$Descripcion&item_amount_1=$item_amount&item_quantity_1=$item_quantity&total_amount=$valor2&currency=$moneda&version=$version&encoding=$encoding&first_name=$nombre&last_name=$apellido&address1=$direccion&city=$City&zip=$ZipCode&country=$country&phone1=$celular&email=$email&payment_method=$payment_method&payment_method_mode=$payment_method_mode&success_url=$urlSuccess&pending_url=$urlFailed&error_url=$cancel_url&notify_url=$this->callback_url&time_stamp=$date&checksum=$checksum&";

        syslog(LOG_WARNING, "NUVEI DATA" . $StringUrl);

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

        $data = array();
        $data["success"] = true;
        $data["url"] = $URL . $StringUrl;

        return json_decode(json_encode($data));
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método verifica varias variables de entorno para determinar
     * la dirección IP del cliente que realiza la solicitud. Si no se encuentra
     * ninguna dirección IP válida, devuelve 'UNKNOWN'.
     *
     * @return string La dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
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
     * Genera un hash SHA-512 a partir de una cadena dada.
     *
     * @param string $String La cadena de entrada para generar el hash.
     *
     * @return string El hash SHA-512 generado.
     */
    function verificacion($String)
    {
        return hash('sha512', $String, false);
    }

    /**
     * Genera un hash SHA-256 a partir de una cadena dada.
     *
     * @param string $String La cadena de entrada para generar el hash.
     *
     * @return string El hash SHA-256 generado.
     */
    function verificacion256($String)
    {
        return hash('sha256', $String, false);
    }

}


