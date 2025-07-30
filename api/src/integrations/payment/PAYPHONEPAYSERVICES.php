<?php

/**
 * Clase `PAYPHONEPAYSERVICES` para gestionar integraciones de pagos con Payphone.
 *
 * Este archivo contiene la implementación de la clase que permite realizar solicitudes
 * de pago, manejar conexiones HTTP (POST y GET), y calcular impuestos y comisiones
 * para diferentes mandantes y países. También incluye métodos auxiliares para obtener
 * la IP del cliente.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `PAYPHONEPAYSERVICES`.
 *
 * Esta clase gestiona las integraciones de pagos con Payphone, incluyendo la configuración
 * de entornos, manejo de tokens, y métodos para realizar solicitudes de pago y conexiones HTTP.
 */
class PAYPHONEPAYSERVICES
{

    /**
     * Constructor de la clase `PAYPHONEPAYSERVICES`.
     *
     * Este constructor inicializa las URLs de callback y depósito según el entorno
     * (desarrollo o producción) utilizando la clase `ConfigurationEnvironment`.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
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
     * @param string   $urlCancel  URL de redirección en caso de cancelación.
     *
     * @return array Respuesta con los datos de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $Registro = new Registro("", $Usuario->usuarioId);
        $mandante = $Usuario->mandante;

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $externo = $Producto->externoId;

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

        //Comision a los depositos por producto.
        $commission = 0;
        try {
            $ProductoMandante = new ProductoMandante($Producto->productoId, $mandante);
            if ($ProductoMandante->valor != "") {
                $commission = $ProductoMandante->valor;
            }
        } catch (Exception $e) {
            $commission = 0;
        }

        $totalCommission = $valorTax * ($commission / 100);
        $valorTax = $valorTax * (1 + $commission / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setComision($totalCommission);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        date_default_timezone_set("UTC");
        $date = gmdate("Y-m-d\TH:i:s\Z");
        $mod_date = strtotime($date . "+ 2 days");
        $date_at = date('Y-m-d\TH:i:s', $mod_date);

        date_default_timezone_set('America/Bogota');

        $TOKEN = $Credentials->TOKEN_PAY;
        $STORE_ID = $Credentials->STORE_ID;
        $STORE_ID2 = $Credentials->STORE_ID2;
        $IDENTIFICADOR_PAY = $Credentials->IDENTIFICADOR_PAY;

        if ($mandante == '8' && $externo == 'StoreIdECD') {
            $STORE_ID = $STORE_ID2;
        }

        if ($Usuario->moneda != 'USD') {
            $PaisMandante = new PaisMandante("", $mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
        }

        $valorTax = round($valorTax * 100, 0);

        $data = array();
        $data['amount'] = $valorTax;
        $data['currency'] = $Usuario->moneda;
        $data['clientTransactionId'] = $transproductoId;
        $data['storeId'] = $STORE_ID;
        $data['reference'] = $transproductoId . '_' . $Usuario->usuarioId;
        $data['phoneNumber'] = $Registro->celular;
        $data['email'] = $Registro->email;
        $data['documentId'] = $Registro->cedula;

        $reference = $transproductoId . '&' . $Usuario->usuarioId;

        $param = $IDENTIFICADOR_PAY;
        $param = $param . '##' . $TOKEN;
        $param = $param . '##' . $valorTax;
        $param = $param . '##' . $STORE_ID;
        $param = $param . '##' . $reference;
        $param = $param . '##' . 'USD';
        $param = $param . '##' . $transproductoId;
        $param = $param . '##' . 'es';

        $dataPayphone = array();
        $dataPayphone['token'] = $TOKEN;
        $dataPayphone['amountWithoutTax'] = $valorTax;
        $dataPayphone['amount'] = $valorTax;
        $dataPayphone['storeId'] = $STORE_ID;
        $dataPayphone['reference'] = $reference;
        $dataPayphone['currency'] = 'USD';
        $dataPayphone['clientTransactionId'] = $transproductoId;
        $dataPayphone['lang'] = 'es';
        $dataPayphone['amountWithTax'] = 0;
        $dataPayphone['tax'] = 0;
        $dataPayphone['service'] = 0;
        $dataPayphone['tip'] = 0;
        $dataPayphone['defaultMethod'] = "card";
        $dataPayphone['showPaymentMethodSelector'] = true;
        $dataPayphone['showCardPayment'] = true;
        $dataPayphone['showPayphonePayment'] = true;
        $dataPayphone['showMainButton'] = true;

        $dataPayphoneInfo = [];
        if ($externo == 'StoreIdECD') {
            $dataPayphoneInfo['title'] = '¡Tu depósito tiene un impacto positivo en la Salud de los Ecuatorianos!';
            $dataPayphoneInfo['text'] = 'Este depósito se añadirá automáticamente a tu cuenta y estará disponible para que juegues. Comprometidos con acciones sociales: Ecuabet donará el valor depositado a la Fundación Prosperar Salud.';
        }

        $params = base64_encode($param);
        $path = "/api/sale";

        $Result = json_encode($data);

        if ($Result != "") {
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

            if ($ConfigurationEnvironment->isDevelopment()) {
                $baseUrl = "https://apidev.virtualsoft.tech/integrations/payment/payphonepay/api?id=";
            } else {
                $baseUrl = "https://integrations.virtualsoft.tech/payment/payphonepay/api?id=";
            }

            $data_ = array();
            $data_["success"] = true;
            $data_["url"] = $baseUrl . $params;
            $data_["isPayphone"] = true;
            $data_["dataPayphone"] = $dataPayphone;
            $data_["dataPayphoneInfo"] = $dataPayphoneInfo;
        }
        return json_decode(json_encode($data_));
    }


    /**
     * Realiza una conexión HTTP POST.
     *
     * @param array  $data     Datos a enviar en la solicitud.
     * @param string $path     Ruta del endpoint.
     * @param string $mandante Mandante asociado a la solicitud.
     * @param string $pais     País asociado a la solicitud.
     *
     * @return string Respuesta del servidor.
     */
    public function connectionPOST($data, $path, $mandante = '0', $pais = '173')
    {
        $Subproveedor = new Subproveedor("", "PAYPHONE");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $pais);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $TOKEN = $credentials->TOKEN_PAY;
        $URL = $credentials->URL_PAY;

        $data = json_encode($data);
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
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $TOKEN
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Realiza una solicitud HTTP GET a un endpoint de Payphone utilizando cURL.
     *
     * @param string       $mandante         Mandante asociado a la solicitud.
     * @param string       $transproducto_id ID de la transacción a consultar.
     * @param string       $path             Ruta del endpoint a consultar.
     * @param Usuario|null $Usuario          Opcional Objeto Usuario para obtener credenciales específicas.
     *
     * @return mixed      Respuesta del servidor Payphone.
     */
    public function connectionGET($mandante, $transproducto_id, $path, $Usuario = null)
    {
        $Credentials = $this->Credentials($Usuario);

        $TOKEN = $Credentials->TOKEN_PAY;
        $URL = $Credentials->URL_PAY;

        $curl = new CurlWrapper($URL . $path . $transproducto_id);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $path . $transproducto_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $TOKEN,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene las credenciales de Payphone para un usuario específico.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene información del mandante y país.
     *
     * @return object Objeto con las credenciales de Payphone.
     */
    public function Credentials($Usuario)
    {
        $Subproveedor = new Subproveedor("", "PAYPHONE");
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        return $Credentials;
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
