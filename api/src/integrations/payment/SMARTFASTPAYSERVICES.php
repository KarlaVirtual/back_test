<?php

/**
 * Clase para gestionar la integración con el servicio de pagos SMARTFASTPAY.
 *
 * Este archivo contiene la implementación de la clase `SMARTFASTPAYSERVICES`,
 * que permite realizar solicitudes de pago, obtener tokens de autenticación
 * y manejar configuraciones específicas para entornos de desarrollo y producción.
 *
 * @category Integración
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use Exception;
use \CurlWrapper;
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
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase principal para la integración con SMARTFASTPAY.
 */
class SMARTFASTPAYSERVICES
{

    /**
     * URL de callback para notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payment/smartfastpay/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/smartfastpay/confirm/";

    /**
     * URL para la gestión de depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL para la gestión de depósitos en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/ecuabet/gestion/deposito";

    /**
     * URL para la gestión de depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://ecuabet.com/gestion/deposito";

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
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }


    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return array Respuesta con el estado de la solicitud y la URL de redirección.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $externo = $Producto->externoId;
        $mandante = $Usuario->mandante;
        $pais = $Usuario->paisId;

        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $mandante);
        $Mandante = new Mandante($UsuarioMandante->mandante);
        $country = $UsuarioMandante->paisId;

        $SuvproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SuvproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;
        $USERNAME = $Credentials->USERNAME;
        $PASSWORD = $Credentials->PASSWORD;

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

        //Obtener Token
        $path = "oauth2/token";
        $result = $this->getToken($path, $URL, $PASSWORD, $USERNAME);

        $result = json_decode($result);
        $token = $result->data->access_token;

        switch ($country) {
            case 66;
                $country = "ECU";
                break;
        }

        $tipoDocumento = $Registro->tipoDoc;
        switch ($tipoDocumento) {
            case "C":
                $tipoDocumento = "CC";
                $name = "Cedula Ciudadania";
                break;
        }
        $countryCode = $Pais->prefijoCelular;
        $phoneNumber = $this->formatToE164($Registro->celular, $countryCode);

        $name = $Registro->nombre1 . " " . $Registro->apellido1;

        //Definicion de la marca
        $branch = match ($UsuarioMandante->mandante) {
            "0" => 'Doradobet',
            "8" => 'Ecuabet',
        };

        $data = [];
        $data['customer'] = [
            "id" => $Usuario->usuarioId,
            "name" => $name,
            "email" => $Registro->email,
            "phone" => $phoneNumber,
            "document" => [
                "number" => $Registro->cedula,
                "type" => $tipoDocumento
            ],
        ];
        $data['transaction'] = [
            "id" => $transproductoId,
            "currency" => $Usuario->moneda,
            "amount" => $valorTax
        ];
        $data['branch'] = $branch;
        $data['country'] = $country;
        $data['notification_url'] = $this->callback_url;
        $data['redirect_url'] = $this->URLDEPOSIT;

        syslog(LOG_WARNING, "SMARTFASTPAY DATA: " . $Usuario->usuarioId . json_encode($data));

        $path = "v2/transaction/checkout";

        $Result = $this->connectionPOST($data, $path, $token, $URL);

        syslog(LOG_WARNING, "SMARTFASTPAY RESPONSE: " . $Usuario->usuarioId . $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result->data->url) {
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

            $data_ = array();
            $data_["success"] = true;
            $data_["url"] = $Result->data->url;
        }
        return json_decode(json_encode($data_));
    }

    /**
     * Formatea un número de teléfono al formato E.164.
     *
     * @param string $phoneNumber Número de teléfono a formatear.
     * @param string $countryCode Código de país del número de teléfono.
     *
     * @return string Número de teléfono en formato E.164.
     * @throws Exception Si el número excede el formato permitido.
     */
    function formatToE164($phoneNumber, $countryCode)
    {
        // Quitar cualquier carácter no numérico del número
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Verificar si ya tiene el prefijo de país (en caso que empiece con 00 o similar)
        if (substr($phoneNumber, 0, strlen($countryCode)) !== $countryCode) {
            // Si no lo tiene, agregamos el código de país
            $phoneNumber = $countryCode . $phoneNumber;
        }
        // Agregar el signo "+" al inicio para el formato E.164
        $formattedPhoneNumber = '+' . $phoneNumber;

        // Verificar que el número esté dentro del rango permitido de E.164 (máximo 15 dígitos)
        if (strlen($formattedPhoneNumber) > 16) {
            throw new Exception('El número de teléfono excede el formato E.164 permitido.');
        }

        return $formattedPhoneNumber;
    }


    /**
     * Realiza una solicitud POST a la API de SMARTFASTPAY.
     *
     * @param array  $data  Datos a enviar en la solicitud.
     * @param string $path  Ruta del endpoint de la API.
     * @param string $token Token de autenticación.
     *
     * @return string Respuesta de la API.
     */
    public function connectionPOST($data, $path, $token, $URL)
    {
        $curl = new CurlWrapper($URL. $path);

        $curl->setOptionsArray([
            CURLOPT_URL => $URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'authorization: Bearer ' . $token,
                'content-type: application/json'
            ],
        ]);

        //Ejecutarlasolicitud
        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene un token de autenticación desde la API de SMARTFASTPAY.
     *
     * @param string $path Ruta del endpoint para obtener el token.
     *
     * @return string Token de autenticación en formato JSON.
     */
    public function getToken($path, $URL, $PASSWORD, $USERNAME)
    {
        $curl = new CurlWrapper($URL . $path);

        $curl->setOptionsArray([
            CURLOPT_URL => $URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($USERNAME . ':' . $PASSWORD),
                'Content-Length: 0'
            ],
        ]);

        //Ejecutarlasolicitud
        $response = $curl->execute();

        return $response;
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


