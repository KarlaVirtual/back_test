<?php

/**
 * Clase para gestionar la integración con el servicio de pagos PayForFun.
 *
 * Este archivo contiene la implementación de la clase `PAYFORFUNSERVICES`,
 * que permite realizar solicitudes de pago y manejar la configuración
 * del entorno (desarrollo o producción) para la integración con PayForFun.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
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
 * Clase principal para la integración con PayForFun.
 */
class PAYFORFUNSERVICES
{


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
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/payforfun/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/payforfun/confirm/";

    /**
     * URL para depósitos configurada dinámicamente según el entorno.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL para depósitos en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/lotosports/gestion/deposito";

    /**
     * URL para depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://lotosports.bet/gestion/deposito";

    /**
     * Constructor de la clase.
     *
     * Configura las variables de entorno dependiendo de si el entorno es
     * de desarrollo o producción.
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
     * Crea una solicitud de pago para un producto específico.
     *
     * Este método realiza varias operaciones, incluyendo la creación de registros
     * de transacciones, el cálculo de impuestos, y la generación de URLs para la
     * confirmación del pago. También maneja configuraciones específicas para
     * entornos de desarrollo y producción.
     *
     * @param Usuario  $Usuario    Objeto que contiene los datos del usuario.
     * @param Producto $Producto   Objeto que contiene los datos del producto.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Resultado de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
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

        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($mandante);
        $img = $Mandante->logo;
        $extID = $Producto->externoId;

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;
        $MERCHANT_ID = $Credentials->MERCHANT_ID;

        if ($extID == 'Wallet') {
            $MERCHANT_KEY = $Credentials->MERCHANT_KEY_WALLET;
            $MERCHANT_SECRET = $Credentials->MERCHANT_SECRET_WALLET;
        } else {
            $MERCHANT_KEY = $Credentials->MERCHANT_KEY;
            $MERCHANT_SECRET = $Credentials->MERCHANT_SECRET;
        }

        if ($mandante == 17) {
            $this->URLDEPOSIT = $Mandante->baseUrl . '/gestion/deposito';
            $img = $Mandante->logo;
        }

        $valorTax = str_replace(',', '', number_format(round($valorTax, 2), 2, '.', null));
        $valor_ = $valorTax * 100;
        $data = array();

        $data['merchantInvoiceId'] = $transproductoId;//OK
        $data['amount'] = $valorTax;//OK
        $data['currency'] = $Usuario->moneda;//OK
        $data['okUrl'] = $this->URLDEPOSIT;//OK
        $data['notOkUrl'] = $this->URLDEPOSIT;//OK
        $data['confirmationUrl'] = $this->callback_url;//OK
        $data['language'] = $Usuario->idioma;//OK

        $data['p4fMainId'] = $Registro->cedula;//OK
        $data['fullName'] = $Registro->nombre . " " . $Registro->apellido1;//OK
        $data['email'] = $Registro->email;//OK
        $data['zipcode'] = $Registro->codigoPostal;//OK

        $data['PaymentMethod'] = $extID;//OK

        $data['merchantLogo'] = $img;//OK
        $data['layoutColor'] = "#838589";//OK

        if ($extID == "PIX") {
            $data['QrCodeOnline'] = true;
        }

        syslog(LOG_WARNING, "PAYFORFUN DATA: " . json_encode($data));

        $hash = hash_hmac('sha256', $MERCHANT_ID . $valor_ . $transproductoId . $MERCHANT_SECRET, $MERCHANT_KEY);

        if ($extID == 'Wallet') {
            $path = "/1.0/wallet/process/";
        } else {
            $path = "/1.0/go/process/";
        }

        $Result = $this->connectionPOST($data, $MERCHANT_ID, $hash, $path, $URL);

        syslog(LOG_WARNING, "PAYFORFUN RESPONSE: " . $Result);

        $Result = json_decode($Result);

        if ($Result != " " && $Result->code == 201) {
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

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match(
                    '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)
                )) {
                $ismobile = '1';
            }

            //Detect special conditions devices
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

            //do something with this information
            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }

            //Modal for pix method
            if ($extID == "PIX") {
                $width = 228;
                $height = 228;

                $urlencoded = urlencode($Result->url);
                if (isset($title)) {
                    $urlencoded .= urlencode('&issuer=' . urlencode($title));
                }

                $data = array();
                $data["success"] = true;
                $data["dataText"] = $Result->url;
                $data["dataImg"] = 'https://quickchart.io/chart?chs=' . $width . 'x' . $height . '&chld=M|0&cht=qr&chl=' . $urlencoded . '';
            } else {
                $data = array();
                $data["success"] = true;
                $data["url"] = $Result->url;
            }
        }
        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud POST a la API de PayForFun.
     *
     * Este método se encarga de enviar datos a la API de PayForFun utilizando
     * una solicitud POST, incluyendo los encabezados necesarios para la autenticación.
     *
     * @param array  $data       Datos a enviar en la solicitud POST.
     * @param string $merchantID ID del comerciante.
     * @param string $hash       Hash de autenticación.
     * @param string $path       Ruta del endpoint de la API.
     * @param string $URL        URL base de la API.
     *
     * @return mixed Respuesta de la API.
     */
    public function connectionPOST($data, $merchantID, $hash, $path, $URL)
    {
        $data = json_encode($data);

        $header = array(
            "merchantId: " . $merchantID,
            "hash: " . $hash,
            "Content-Type: application/json"
        );

        $url = $URL . $path;

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
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * Este método intenta obtener la dirección IP del cliente a través de
     * varias variables de entorno, devolviendo 'UNKNOWN' si no se encuentra.
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


