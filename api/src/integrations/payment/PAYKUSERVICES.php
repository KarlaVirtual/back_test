<?php

/**
 * Clase para gestionar la integración con el servicio de pagos Payku.
 *
 * Este archivo contiene la implementación de la clase `PAYKUSERVICES`, que permite
 * realizar solicitudes de pago y manejar la configuración del entorno (desarrollo o producción).
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
 * Clase `PAYKUSERVICES`
 *
 * Esta clase gestiona la integración con el servicio de pagos Payku,
 * permitiendo realizar solicitudes de pago y manejar la configuración
 * del entorno (desarrollo o producción).
 */
class PAYKUSERVICES
{
    /**
     * URL base para las solicitudes al servicio Payku.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL del entorno de desarrollo para las solicitudes al servicio Payku.
     *
     * @var string
     */
    private $URLDEV = 'https://des.payku.cl/api';

    /**
     * URL del entorno de producción para las solicitudes al servicio Payku.
     *
     * @var string
     */
    private $URLPROD = 'https://app.payku.cl/api';

    /**
     * URL de callback configurada dinámicamente según el entorno.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/payku/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/payku/confirm/";

    /**
     * URL de depósito configurada dinámicamente según el entorno.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL de depósito para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/gestion/deposito";

    /**
     * URL de depósito para el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";

    /**
     * Tipo de transacción o solicitud.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Token público configurado dinámicamente según el entorno.
     *
     * @var string
     */
    private $tokenPUB = " ";

    /**
     * Token público para el entorno de desarrollo.
     *
     * @var string
     */
    private $tokenPUB_DEV = "tkpu9987f704786dfc8150e30fbbc941";

    /**
     * Token público para el entorno de producción.
     *
     * @var string
     */
    private $tokenPUB_PROD = "tkpu2946b40b3a0792326fd240e511a0";

    /**
     * Token privado configurado dinámicamente según el entorno.
     *
     * @var string
     */
    private $tokenPRIV = " ";

    /**
     * Token privado para el entorno de desarrollo.
     *
     * @var string
     */
    private $tokenPRIV_DEV = "tkpi842d23f7538f13329c22afbf357c";

    /**
     * Token privado para el entorno de producción.
     *
     * @var string
     */
    private $tokenPRIV_PROD = "tkpib5154a95bcb4355df52203b7b814";


    /**
     * Constructor de la clase.
     *
     * Configura las URLs, tokens y otros parámetros dependiendo del entorno
     * (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->tokenPUB = $this->tokenPUB_DEV;
            $this->tokenPRIV = $this->tokenPRIV_DEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->tokenPUB = $this->tokenPUB_PROD;
            $this->tokenPRIV = $this->tokenPRIV_PROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }


    /**
     * Crea una solicitud de pago en el sistema Payku.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     * @param string   $urlCancel  URL a la que se redirige en caso de cancelación.
     *
     * @return object Respuesta del sistema Payku con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $data = array();

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        //$valor2=number_format($valor, 2);
        $mandante = $Usuario->mandante;
        $notificationUrl = $this->callback_url;

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

        $data['email'] = $email;
        $data['order'] = $transproductoId;
        $data['subject'] = 'Solicitud de Deposito';
        $data['amount'] = $valorTax;
        $data['payment'] = $Producto->externoId;
        $data['urlreturn'] = $urlSuccess;
        $data['urlnotify'] = $notificationUrl;

        syslog(LOG_WARNING, "PAYKU DATA" . json_encode($data));

        $this->tipo = "/transaction";

        $Result = $this->connectionPOST($data);


        syslog(LOG_WARNING, "PAYKU RESPONSE" . $Result);

        $Result = json_decode($Result);

        if ($Result != " " && $Result->status != "failed") {
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
            $data["url"] = $Result->url;
        }

        return json_decode(json_encode($data));
    }


    /**
     * Realiza una solicitud HTTP POST al servicio Payku.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta del servicio Payku.
     */
    public function connectionPOST($data)
    {
        $data = json_encode($data);

        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $this->tokenPUB,
            'Cookie: PHPSESSID=lqkitgbfkksk9sq7rkidoq43on'
        );

        $curl = curl_init();

        $url = $this->URL . $this->tipo;


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

}


