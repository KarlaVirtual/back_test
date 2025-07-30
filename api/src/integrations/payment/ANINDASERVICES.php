<?php

/**
 * Clase para gestionar los servicios de integración con ANINDA.
 *
 * Este archivo contiene la implementación de la clase `ANINDASERVICES`, que permite
 * realizar operaciones relacionadas con pagos a través de la plataforma ANINDA.
 *
 * @category Red
 * @package  API
 * @author   Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase principal para gestionar los servicios de integración con ANINDA.
 *
 * Esta clase contiene métodos para realizar operaciones relacionadas con pagos,
 * incluyendo la creación de solicitudes de pago y la generación de tokens de autenticación.
 */
class ANINDASERVICES
{

    /**
     * URL base de redireccion.
     *
     * @var string $URLDEPOSIT URL base para el retorno a la plataforma
     */
    private $URLDEPOSIT = '';

    /**
     * Constructor de la clase ANINDASERVICES.
     *
     * Inicializa las propiedades de la clase según el entorno de ejecución
     * (desarrollo o producción). Configura las URLs base, claves secretas,
     * identificadores de cliente y URLs de callback correspondientes.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago para un producto específico.
     *
     * Este método genera una solicitud de pago utilizando la información del usuario,
     * el producto y el valor del pago. También maneja la configuración de URLs de retorno
     * y los impuestos aplicables.
     *
     * @param Usuario   $Usuario   Objeto Usuario que contiene la información del usuario.
     * @param Producto  $Producto  Objeto Producto que contiene la información del producto.
     * @param float     $valor     Valor del pago a realizar.
     * @param string    $urlSuccess URL de éxito para redirigir después del pago.
     * @param string    $urlFailed  URL de fallo para redirigir si el pago falla.
     * @param string    $urlCancel  URL de cancelación para redirigir si el pago es cancelado.
     *
     * @return array Resultado de la creación de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $dataR = array();
        $dataR["success"] = false;
        $dataR["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $Registro = new Registro('', $Usuario->usuarioId);
        $PaisMandante = new PaisMandante('', $Usuario->mandante, $Usuario->paisId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        if ($PaisMandante->baseUrl != '') {
            $this->URLDEPOSIT = $PaisMandante->baseUrl . "gestion/deposito";
        }

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

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

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

        // Usar explode para separar en partes el externoId
        $externoId = explode('##', $Producto->externoId);

        // Usar regex para insertar el texto entre los puntos
        $nuevaUrl = preg_replace('/\.\./', '.' . $externoId[1] . '.', $Credentials->URL, 1);

        $data = array();
        $data['Key'] = $Credentials->KEY;
        $data['PlayerID'] = $Usuario->usuarioId;
        $data['PlayerFullName'] = $Registro->nombre . ' ' . $Registro->apellido1;
        $data['PlayerUserName'] = $Registro->nombre1;
        $data['TraderTransactionID'] = $transproductoId;
        $data['PlayerDepositCount'] = 0;
        $data['PlayerWithdrawCount'] = 0;
        $data['PlayerRegisteredDate'] = $Usuario->fechaCrea;
        $data['PlayerIdentityNumber'] = $Registro->cedula;
        $data['PlayerPhoneNumber'] = $Registro->celular;
        $data['PlayerEmail'] = $Registro->email;
        $data['PaymentMethodID'] = $externoId[0];

        $path = "/trader/get-deposit-url";
        $Result = $this->connectionPOST($data, $nuevaUrl . $path);

        syslog(LOG_WARNING, "ANINDA DATA: " . $Usuario->usuarioId . json_encode($data) . " RESPONSE: " . $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result->HasError == false) {
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
                '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
                substr($useragent, 0, 4)
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

            $dataR = array();
            $dataR["success"] = true;
            $dataR["url"] = $Result->Data;
        }

        return json_decode(json_encode($dataR));
    }

    /**
     * Realiza una conexión HTTP POST para crear una solicitud en la API de ANINDA.
     *
     * Este método envía datos codificados en formato JSON a un endpoint específico
     * utilizando un uuid_data de autenticación.
     *
     * @param string $data      Datos codificados en formato JSON que se enviarán en la solicitud.
     * @param string $url       Url del endpoint de la API donde se enviará la solicitud.
     *
     * @return string $response Respuesta de la API en formato JSON.
     */
    public function connectionPOST($data, $url)
    {
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
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string $ipaddress Dirección IP del cliente.
     */
    public function get_client_ip()
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
