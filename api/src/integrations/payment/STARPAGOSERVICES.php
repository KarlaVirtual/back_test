<?php

/**
 * Clase para gestionar los servicios de integración con StarPago.
 *
 * Este archivo contiene la implementación de la clase `STARPAGOSERVICES`,
 * que permite realizar solicitudes de pago, manejar transacciones y
 * generar firmas para la comunicación con la API de StarPago.
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
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use phpDocumentor\Reflection\Types\This;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase `STARPAGOSERVICES`
 *
 * Esta clase gestiona la integración con los servicios de StarPago, permitiendo
 * realizar solicitudes de pago, manejar transacciones y generar firmas para la
 * comunicación con la API de StarPago.
 */
class STARPAGOSERVICES
{

    /**
     * URL de callback base.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payment/starpago/confirm/";

    /**
     * URL de callback en entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/starpago/confirm/";

    /**
     * URL para depósitos en el entorno actual.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * URL para depósitos en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/integrations/gestion/deposito";

    /**
     * URL para depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://gangabet.mx/gestion/deposito";

    /**
     * Constructor de la clase.
     * Configura las URLs y credenciales dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
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
     * @return array Respuesta con el estado de la solicitud y la URL generada.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $mandante = $Usuario->mandante;

        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $mandante);
        $Mandante = new Mandante($UsuarioMandante->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $APP_SECRET = $Credentials->APP_SECRET;
        $APP_ID = $Credentials->APP_ID;
        $URL = $Credentials->URL;

        if ($Mandante->baseUrl != '') {
            $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
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
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $data = array();
        $data['appId'] = $APP_ID;
        $data['merOrderNo'] = $transproductoId;
        $data['currency'] = $Usuario->moneda;
        $data['amount'] = $valorTax;
        $data['returnUrl'] = $this->URLDEPOSIT;
        $data['notifyUrl'] = $this->callback_url;

        $dataSign = $this->create($APP_SECRET, $data, $Credentials);

        $data['sign'] = $dataSign;

        syslog(LOG_WARNING, "STARPAGO DATA: " . $Usuario->usuarioId . json_encode($data));

        $path = "/api/v2/payment/order/create";

        $Result = $this->connectionPOST($data, $path, $URL);

        syslog(LOG_WARNING, "STARPAGO RESPONSE: " . $Usuario->usuarioId . $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result->data->params->url != "") {
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
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);

            $ismobile = '';
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $ismobile = '1';
            }

            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }

            $url_original = $Result->data->params->url;

            $url_base_original = 'https://h5.h.starpago.com';
            $url_base_nueva = 'https://br.h5.starpago.com';

            $urlFinal = str_replace($url_base_original, $url_base_nueva, $url_original);

            $data_ = array();
            $data_["success"] = true;
            $data_["url"] = $urlFinal;
        }
        return json_decode(json_encode($data_));
    }


    /**
     * Realiza una solicitud POST a la API.
     *
     * @param array  $data Datos a enviar en la solicitud.
     * @param string $path Ruta del endpoint de la API.
     *
     * @return string Respuesta de la API.
     */
    public function connectionPOST($data, $path, $URL)
    {
        $curl = new CurlWrapper($URL. $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));

        $response = $curl->execute();

        syslog(LOG_WARNING, "STARPAGO RESPONSE : " . $response);

        return $response;
    }

    /**
     * Genera una firma para los datos proporcionados.
     *
     * @param string $appSecret Clave secreta de la aplicación.
     * @param array  $map       Datos a firmar.
     *
     * @return string Firma generada.
     */
    public function create($appSecret, $map, $Credentials)
    {
        $signStr = $this->createSignStr($appSecret, $map, $Credentials);

        return hash('sha256', $signStr);
    }

    /**
     * Crea una cadena de firma a partir de los datos proporcionados.
     *
     * @param string $appSecret Clave secreta de la aplicación.
     * @param array  $map       Datos a procesar.
     *
     * @return string Cadena de firma generada.
     */
    public function createSignStr($appSecret, $map, $Credentials)
    {
        $signStr = $this->joinMap($map, $Credentials);
        $signStr .= '&' . $Credentials->KEY . '=' . $appSecret;

        return $signStr;
    }

    /**
     * Prepara un mapa de datos eliminando elementos no necesarios y ordenándolos.
     *
     * @param array $map Mapa de datos a procesar.
     *
     * @return array Mapa procesado.
     */
    private function prepareMap($map, $Credentials)
    {
        if ( ! is_array($map)) {
            return array();
        }

        if (array_key_exists($Credentials->SIGN, $map)) {
            unset($map[$Credentials->SIGN]);
        }
        ksort($map);
        reset($map);

        return $map;
    }

    /**
     * Une un mapa de datos en una cadena de consulta.
     *
     * @param array $map Mapa de datos a unir.
     *
     * @return string Cadena de consulta generada.
     */
    private function joinMap($map, $Credentials)
    {
        if ( ! is_array($map)) {
            return '';
        }

        $map = $this->prepareMap($map, $Credentials);
        $pair = array();
        foreach ($map as $key => $value) {
            if ($this->isIgnoredItem($key, $value, $Credentials)) {
                continue;
            }

            $tmp = $key . '=';
            if (0 === strcmp($Credentials->EXT, $key)) {
                $tmp .= $this->joinMap($value, $Credentials);
            } else {
                $tmp .= $value;
            }

            $pair[] = $tmp;
        }

        if (empty($pair)) {
            return '';
        }

        return join('&', $pair);
    }

    /**
     * Verifica si un elemento debe ser ignorado en el proceso de firma.
     *
     * @param string $key   Clave del elemento.
     * @param mixed  $value Valor del elemento.
     *
     * @return boolean `true` si el elemento debe ser ignorado, `false` en caso contrario.
     */
    private function isIgnoredItem($key, $value, $Credentials)
    {
        if (empty($key) || empty($value)) {
            return true;
        }

        if (0 === strcmp($Credentials->SIGN, $key)) {
            return true;
        }

        if (0 === strcmp($Credentials->EXT, $key)) {
            return false;
        }

        if (is_string($value)) {
            return false;
        }

        if (is_numeric($value)) {
            return false;
        }

        if (is_bool($value)) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
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