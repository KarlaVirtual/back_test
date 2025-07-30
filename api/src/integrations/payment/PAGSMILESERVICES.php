<?php

/**
 * Clase para gestionar servicios de integración con PAGSMILE.
 *
 * Este archivo contiene la implementación de la clase `PAGSMILESERVICES`,
 * que permite realizar solicitudes de pago y manejar configuraciones
 * específicas para entornos de desarrollo y producción.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

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
 * Clase `PAGSMILESERVICES`
 *
 * Esta clase gestiona la integración con los servicios de PAGSMILE,
 * permitiendo la creación de solicitudes de pago, manejo de configuraciones
 * de entorno y comunicación con la API de PAGSMILE.
 */
class PAGSMILESERVICES
{


    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://dev-gateway.luxpag.com';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://gateway.luxpag.com';

    /**
     * URL de callback para las notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/pagsmile/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/pagsmile/confirm/";


    /**
     * URL para la gestión de depósitos en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/gestion/deposito";

    /**
     * URL para la gestión de depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";

    /**
     * Ruta para las solicitudes específicas.
     *
     * @var string
     */
    private $path = "";

    /**
     * Datos enviados en las solicitudes.
     *
     * @var string
     */
    private $data = "";

    /**
     * ID de la aplicación.
     *
     * @var string
     */
    private $app_id = "";

    /**
     * ID de la aplicación para el entorno de desarrollo.
     *
     * @var string
     */
    private $app_id_DEV = "16565698033877266";

    /**
     * ID de la aplicación para el entorno de producción.
     *
     * @var string
     */
    private $app_id_PROD = "16566581488175550";

    /**
     * Clave de seguridad para las solicitudes.
     *
     * @var string
     */
    private $security_key = "";

    /**
     * Clave de seguridad para el entorno de desarrollo.
     *
     * @var string
     */
    private $security_key_DEV = "Luxpag_sk_fd375d88093cea744d89213c3eb3473aa86b634d4a81ee87094f0907f78fefe2";

    /**
     * Clave de seguridad para el entorno de producción.
     *
     * @var string
     */
    private $security_key_PROD = "Luxpag_sk_5a40099b5098bedc6fe44c94a7519bc134fa186678edd80a0d0f45dd3c694fd8";


    /**
     * Constructor de la clase.
     *
     * Inicializa las configuraciones de URL, claves de seguridad y otros
     * parámetros dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->app_id = $this->app_id_DEV;
            $this->security_key = $this->security_key_DEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->app_id = $this->app_id_PROD;
            $this->security_key = $this->security_key_PROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }


    /**
     * Crea una solicitud de pago.
     *
     * Este metodo genera una solicitud de pago para un usuario y un producto
     * específico, calculando impuestos y configurando los datos necesarios
     * para la integración con PAGSMILE.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario.
     * @param Producto $Producto   Objeto que representa al producto.
     * @param float    $valor      Valor del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $urlCancel  URL de redirección en caso de cancelación.
     *
     * @return object Respuesta de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);


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

        $app_id = $this->app_id;
        $security_key = $this->security_key;
        switch ($Pais->iso) {
            case "BR":
                $app_id = "16600164141230352";
                $security_key = "Luxpag_sk_8fd1275670a6433bb3fe8942adba8902a0cebb281793700bc88d2dd2d1474a04";
                $moneda = $Usuario->moneda;
                $URLDEPOSIT = "https://lotosports.bet/gestion/deposito";
                break;
            default:
                $app_id = $app_id;
                $security_key = $security_key;
                $moneda = $Usuario->moneda;
                $URLDEPOSIT = $this->URLDEPOSIT;
                break;
        }

        $data = array();

        $data['charset'] = 'UTF-8'; //OK
        $data['app_id'] = $app_id; //OK
        $data['out_trade_no'] = $transproductoId;//OK
        $data['order_currency'] = $moneda;//OK
        $data['order_amount'] = $valorTax;//OK
        $data['subject'] = 'Deposito';//OK
        $data['content'] = 'Solicitud de Deposito';//OK
        $data['trade_type'] = 'WEB'; //OK
        $data['timeout_express'] = "1d"; //OK
        $data['timestamp'] = date("Y-m-d H:i:s");//OK
        $data['notify_url'] = $this->callback_url;
        $data['return_url'] = $URLDEPOSIT;
        $data['buyer_id'] = $Usuario->usuarioId;//OK
        $data['language_code'] = $Pais->idioma;//OK
        $data['version'] = '2.0';//OK
        $data['customer'] = [
            'identification' => [
                "type" => $Registro->tipoDoc,//OK
                "number" => '5028441472726'//$Registro->cedula//OK
            ],
            "name" => $Usuario->nombre,//OK
            "email" => $Usuario->login,//OK
            "phone" => $Usuario->celular,//OK
            "buyer_id" => $Usuario->usuarioId,//OK
            "ip" => $this->get_client_ip()//OK
        ];

        //$data['user_ip'] = $this->get_client_ip();

        syslog(LOG_WARNING, "PAGSMILE DATA" . json_encode($data));

        $path = "/trade/create";

        $Result = $this->connectionPOST($data, $app_id, $security_key, $path);

        syslog(LOG_WARNING, "PAGSMILE RESPONSE" . $Result);

        $Result = json_decode($Result);

        if ($Result != " " && $Result->msg == "Success") {
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
            $data["url"] = $Result->web_url;
        }
        return json_decode(json_encode($data));
    }


    /**
     * Realiza una conexión POST con PAGSMILE.
     *
     * Este metodo envía datos a la API de PAGSMILE utilizando una solicitud
     * HTTP POST y devuelve la respuesta.
     *
     * @param array  $data         Datos a enviar en la solicitud.
     * @param string $app_id       ID de la aplicación.
     * @param string $security_key Clave de seguridad.
     * @param string $path         Ruta del endpoint de la API.
     *
     * @return string Respuesta de la API.
     */
    public function connectionPOST($data, $app_id, $security_key, $path)
    {
        $data = json_encode($data);

        $header = array(
            "Content-Type: application/json",
            'Authorization: Basic ' . base64_encode($app_id . ':' . $security_key)
        );

        $url = $this->URL . $path;

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
     * Obtiene la dirección IP del cliente.
     *
     * Este metodo detecta y devuelve la dirección IP del cliente que realiza
     * la solicitud.
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


