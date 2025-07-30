<?php

/**
 * Clase para gestionar la integración con los servicios de Paybrokers.
 *
 * Este archivo contiene métodos para realizar solicitudes de pago, obtener tokens de autenticación,
 * verificar información de clientes y manejar pagos PIX a través de la API de Paybrokers.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

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
 * Clase PAYBROKERSSERVICES
 *
 * Esta clase gestiona la integración con los servicios de Paybrokers,
 * incluyendo la creación de solicitudes de pago, manejo de pagos PIX,
 * y verificación de información de clientes.
 */
class PAYBROKERSSERVICES
{

    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo (general).
     *
     * @var string
     */
    private $callback_urlG = "";

    /**
     * URL de callback para el entorno de desarrollo (milbets).
     *
     * @var string
     */
    private $callback_urlM = "";

    /**
     * URL de callback para el entorno de desarrollo (general).
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/paybrokers/confirm/";

    /**
     * URL de callback para el entorno de desarrollo (gangabet).
     *
     * @var string
     */
    private $callback_urlGDEV = "https://apidev.virtualsoft.tech/integrations/payment/paybrokersgangabet/confirm/";

    /**
     * URL de callback para el entorno de desarrollo (milbets).
     *
     * @var string
     */
    private $callback_urlMDEV = "https://apidev.virtualsoft.tech/integrations/payment/paybrokersmilbets/confirm/";

    /**
     * URL de callback para el entorno de producción (general).
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/paybrokers/confirm/";

    /**
     * URL de callback para el entorno de producción (gangabet).
     *
     * @var string
     */
    private $callback_urlGPROD = "https://integrations.virtualsoft.tech/payment/paybrokersgangabet/confirm/";

    /**
     * URL de callback para el entorno de producción (milbets).
     *
     * @var string
     */
    private $callback_urlMPROD = "https://integrations.virtualsoft.tech/payment/paybrokersmilbets/confirm/";


    private $tipo = "";



    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de entorno dependiendo del ambiente (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
            $this->callback_urlG = $this->callback_urlGDEV;
            $this->callback_urlM = $this->callback_urlMDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
            $this->callback_urlG = $this->callback_urlGPROD;
            $this->callback_urlM = $this->callback_urlMPROD;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto que contiene información del usuario.
     * @param Producto $Producto   Objeto que contiene información del producto.
     * @param float    $valor      Valor del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta en formato JSON con los datos del pago.
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
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;

        //Validación que CPF tenga 11 Digitos, en caso que no agregar ceros al inicio
        $cedula_Valida = str_pad($cedula, "11", "0", STR_PAD_LEFT);

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

        $Credentials = $this->Credentials($Usuario);

        $URL = $Credentials->URL;

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_url;
        } else {
            if ($mandante == '14') {
                $this->callback_url = $this->callback_url;
            }
            if ($mandante == '18') {
                $this->callback_url = $this->callback_urlG;
            }
            if ($mandante == '17') {
                $this->callback_url = $this->callback_urlM;
            }
        }

        $data = array();
        $data['value'] = $valorTax;
        $data['description'] = $transproductoId;
        $data['reference_id'] = $transproductoId;
        $data['webhook_url'] = $this->callback_url;
        $data['buyer'] = [
            "cpf" => $cedula_Valida,
            "name" => $nombre
        ];

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $header = $this->buildAuthorizationHeader($mandante, $Usuario);

        $Respueta = $this->ConnectionToken($header, $mandante, $Usuario);

        $token = $Respueta->token;

        syslog(LOG_WARNING, "PAYBROKERS DATA PAYMENT" . json_encode($data));

        $Result = $this->conecctionCreatePIX($data, $token, $URL);

        syslog(LOG_WARNING, "PAYBROKERS RESPONSE PAYMENT" . json_encode($Result));

        if ($Result != '' && $Result->qr_code != '') {
            $TransaccionProducto->setExternoId($Result->id);
            $transproductoId = $TransaccionProductoMySqlDAO->update($TransaccionProducto);

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

            $response = ($Result);
            $data = array();
            $data["success"] = true;
            $data["dataText"] = $response->qr_code . '<br><p style="
            background: -webkit-linear-gradient(90deg,#4bba59 1%,#0e8507 48%,#51c752)!important;
            color: white;
            border: 0;
            box-shadow: none;
            font-size: 17px;
            font-weight: 500;
            border-radius: 5px;
            padding: 10px 32px;
            margin: 26px 5px 0 5px;
            cursor: pointer;" 
            class="confirm" tabindex="1" onclick="copyStringToClipboard(\'' . $response->qr_code . '\');">Copiar código</p>';
            $data["dataImg"] = $response->qr_code_image;
        }

        try {
            if ($Usuario->mandante == 17) {
                try {
                    $array = array(
                        'tags' => array("generated"),
                        'name' => $Usuario->nombre,
                        "email" => $Usuario->login,
                        "phone" => $Registro->celular,
                        "aff" => $Registro->afiliadorId,
                        "transaction_id" => $TransaccionProducto->transproductoId,
                        "transaction_value" => $valorTax,
                        "creation_date" => date('Y-m-d H:i:s')
                    );

                    $payload = json_encode($array);
                    $curl = new CurlWrapper('https://n8n.casamilbets.com/webhook/mkt_casamilbets_com_depositos');

                    $curl->setOptionsArray([
                        CURLOPT_RETURNTRANSFER => true,
                        CURLINFO_HEADER_OUT => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTPHEADER => [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($payload),
                        ],
                    ]);

                    $curl->execute();

                } catch (Exception $e) {
                }
            }
        } catch (Exception $e) {
        }

        return json_decode(json_encode($data));
    }

    /**
     * Obtiene un token de conexión para autenticar las solicitudes a la API de Paybrokers.
     *
     * @param array   $header   Encabezados HTTP necesarios para la solicitud.
     * @param string  $mandante Identificador del mandante (por defecto '14').
     * @param Usuario $Usuario  Objeto que contiene información del usuario (opcional).
     *
     * @return object Respuesta en formato JSON con el token de autenticación.
     */
    public function ConnectionToken($header, $mandante = '14', $Usuario = null)
    {
        $maxAttempts = 3;
        $attempt = 0;
        $response = null;

        while ($attempt < $maxAttempts && ! $response) {
            $attempt++;

            $response = $this->makeTokenRequest($header, $mandante, $Usuario);

            if ($response && isset($response->token) && ! empty($response->token)) {
                return $response;
            } else {
                $response = null;
                sleep(2);
            }
        }

        return $response;
    }

    /**
     * Realiza una solicitud para obtener un token de autenticación.
     *
     * @param array   $header   Encabezados HTTP necesarios para la solicitud.
     * @param string  $mandante Identificador del mandante (por defecto '14').
     * @param Usuario $Usuario  Objeto que contiene información del usuario (opcional).
     *
     * @return object Respuesta en formato JSON con el token de autenticación.
     */
    private function makeTokenRequest($header, $mandante, $Usuario = null)
    {
        $Credentials = $this->Credentials($Usuario);

        $USERNAME = $Credentials->USERNAME;
        $PASSWORD = $Credentials->PASSWORD;
        $URL = $Credentials->URL;

        $this->tipo = "/v4/auth/token";
        $url = $URL . $this->tipo;

        syslog(LOG_WARNING, "PAYBROKERS DATA TOKEN " . $USERNAME . ':' . $PASSWORD);

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
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();

        syslog(LOG_WARNING, "PAYBROKERS RESPONSE TOKEN " . $response);

        $response = json_decode($response);

        if ($response->token == '') {
            $message = '*Paybrokers Token Vacio*: ' . json_encode($response);
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
        }

        return $response;
    }

    /**
     * Obtiene las credenciales de Paybrokers para un usuario específico.
     *
     * @param Usuario $Usuario Objeto que contiene información del usuario.
     *
     * @return object Objeto JSON con las credenciales de Paybrokers.
     */
    public function Credentials($Usuario)
    {
        $Subproveedor = new Subproveedor("", "PAYBROKERS");
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        return $Credentials;
    }

    /**
     * Realiza una solicitud para crear un pago PIX.
     *
     * @param array  $data  Datos del pago a crear.
     * @param string $token Token de autenticación.
     * @param string $URL   URL base de la API de Paybrokers.
     *
     * @return object Respuesta en formato JSON con los detalles del pago creado.
     */
    private function conecctionCreatePIX($data, $token, $URL)
    {
        $this->tipo = "/v4/payment/pix/cpf";
        $url = $URL . $this->tipo;

        $curl = new CurlWrapper($URL);

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
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return json_decode($response);
    }

    /**
     * Construye el encabezado de autorización para las solicitudes a la API de Paybrokers.
     *
     * @param string       $mandante Identificador del mandante (por defecto '14').
     * @param Usuario|null $Usuario  Objeto que contiene información del usuario (opcional).
     *
     * @return array Encabezado de autorización en formato array.
     */
    public function buildAuthorizationHeader($mandante = '14', $Usuario = null)
    {
        $Credentials = $this->Credentials($Usuario);

        $USERNAME = $Credentials->USERNAME;
        $PASSWORD = $Credentials->PASSWORD;

        $header = array(
            'Authorization: Basic ' . base64_encode($USERNAME . ':' . $PASSWORD)
        );
        return $header;
    }

    /**
     * Obtiene los detalles de un pago PIX específico.
     *
     * @param string $id       ID del pago PIX.
     * @param string $token    Token de autenticación.
     * @param string $mandante Identificador del mandante (por defecto '14').
     * @param string $URL      URL base de la API de Paybrokers.
     *
     * @return object Respuesta en formato JSON con los detalles del pago PIX.
     */
    public function GetPIXPayment($id, $token, $mandante = '14', $URL)
    {
        $this->tipo = "/v4/report/api/pix?pix_id=";
        $url = $URL . $this->tipo . $id;

        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = $curl->execute();

        $response = json_decode($response);

        if ($response->status == '') {
            $message = '*Paybrokers Status Vacio*: ' . json_encode($response);
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#alertas-integraciones' > /dev/null & ");
        }

        return $response;
    }
}
