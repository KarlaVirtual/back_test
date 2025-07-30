<?php

/**
 * Clase PRONTOPAGASERVICES
 *
 * Esta clase proporciona servicios de integración con la plataforma de pagos ProntoPaga.
 * Incluye métodos para crear solicitudes de pago, realizar conexiones HTTP y generar firmas
 * de seguridad para las transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase PRONTOPAGASERVICES
 *
 * Proporciona servicios para la integración con la plataforma de pagos ProntoPaga.
 * Incluye métodos para crear solicitudes de pago, realizar conexiones HTTP y generar firmas de seguridad.
 */
class PRONTOPAGASERVICES
{

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
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payment/prontopaga/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/prontopaga/confirm/";

    /**
     * Metodo de la solicitud HTTP.
     *
     * @var string
     */
    private $metod = "";

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de configuración dependiendo del entorno (desarrollo o producción).
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
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta de la solicitud de pago en formato JSON.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
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

        $Pais = new Pais($Usuario->paisId);
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Mandante = new Mandante($mandante);
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $SubproveedorMandantePais = new SubproveedormandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;
        $SECRET_KEY = $Credentials->SECRET_KEY;
        $AUTH = $Credentials->AUTH;

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
            "currency" => $Usuario->moneda,
            "country" => $Pais->iso,
            "amount" => $valorTax,
            "clientName" => $nombre,
            "clientEmail" => $email,
            "clientPhone" => $Registro->celular,
            "clientDocument" => $Registro->cedula,
            "paymentMethod" => $Producto->externoId,
            "urlConfirmation" => $this->callback_url,
            "urlFinal" => $Mandante->baseUrl . "gestion/deposito/",
            "order" => $transproductoId
        );

        ksort($data);
        foreach ($data as $key => $val) {
            if ($cont == 0) {
                $requestOrder .= "$key$val";
            } else {
                $requestOrder .= "$key$val";
            }
            $cont++;
        }

        $sign = $this->Encrypta($requestOrder, $SECRET_KEY);

        $data['sign'] = $sign;

        $this->metod = "/api/payment/new";
        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        syslog(LOG_WARNING, "DATA PRONTOPAGA: " . json_encode($data));

        $Result = $this->connection($data, $URL, $AUTH);
        syslog(LOG_WARNING, "RESPUESTA PRONTOPAGA: " . $Result);

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

            $response = json_decode($Result);

            $data = array();
            $data["success"] = true;
            $data["url"] = $response->urlPay;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Genera una firma de seguridad para los datos proporcionados.
     *
     * @param string $data Datos a firmar.
     *
     * @return string Firma generada utilizando HMAC-SHA256.
     */
    public function Encrypta($data, $SECRET_KEY)
    {
        $sign = hash_hmac('sha256', $data, $SECRET_KEY);
        return $sign;
    }

    /**
     * Realiza una conexión HTTP POST con los datos proporcionados.
     *
     * @param array $data Datos a enviar en la solicitud.
     *
     * @return string Respuesta de la conexión.
     */
    public function connection($data, $URL, $AUTH)
    {
        $data = json_encode($data);

        $curl = new CurlWrapper($URL . $this->metod);

        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $AUTH
            ],
        ]);

        $result = $curl->execute();

        return $result;
    }

}
