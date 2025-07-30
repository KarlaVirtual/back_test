<?php

/**
 * Clase `DIRECTA24SERVICES` para gestionar integraciones de pagos con Directa24.
 *
 * Este archivo contiene métodos para crear solicitudes de pago, obtener el estado de pagos
 * y realizar solicitudes HTTP relacionadas con la integración de Directa24.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\Integrations\payment;

use DateTime;
use Exception;
use DateTimeZone;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Directa24Streamline;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\PuntoVenta;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\ProveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase `DIRECTA24SERVICES`
 *
 * Esta clase representa la integración con el proveedor de pagos Directa24.
 * Permite gestionar transacciones y realizar confirmaciones de estado.
 */
class DIRECTA24SERVICES
{

    /**
     * Constructor de la clase `DIRECTA24SERVICES`.
     *
     * Inicializa el entorno de configuración y establece las variables necesarias.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y un producto específicos.
     *
     * Este metodo gestiona la creación de una transacción de pago, calcula impuestos,
     * y genera una URL de redirección para completar el pago a través de Directa24.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto Objeto que representa el producto asociado al pago.
     * @param float    $valor    Monto del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Objeto con el estado de la operación y la URL de redirección (si aplica).
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {
            // Configuración inicial para usuarios con punto de venta asociado.
            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
            $Pais = new Pais($Usuario->paisId);

            $ProveedorMandante = new ProveedorMandante($Producto->proveedorId, $Usuario->mandante);
            $detalleProveedorMandante = json_decode(($ProveedorMandante->detalle));

            // Configuración de reglas de filtro para el producto.
            $rules = [];
            array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            // Inicialización de variables de transacción.
            $data = array();
            $data["success"] = false;
            $data["error"] = 1;

            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';

            $banco = $Producto->externoId;
            $pais = $Usuario->paisId;
            $usuario_id = $Usuario->usuarioId;
            $cedula = $PuntoVenta->cedula;
            $nombre = $Usuario->nombre;
            $email = $PuntoVenta->email;
            $valor = $valor;
            $producto_id = $Producto->productoId;
            $moneda = $Usuario->moneda;
            $mandante = $Usuario->mandante;
        } else {
            // Configuración inicial para usuarios sin punto de venta asociado.
            $Registro = new Registro("", $Usuario->usuarioId);
            $Pais = new Pais($Usuario->paisId);

            $ProveedorMandante = new ProveedorMandante($Producto->proveedorId, $Usuario->mandante);
            $detalleProveedorMandante = json_decode(($ProveedorMandante->detalle));

            // Creación de un objeto DateTime en UTC.
            $date = new DateTime('now', new DateTimeZone('UTC'));
            $formattedDate = $date->format('Y-m-d\TH:i:s\Z');

            // Configuración de reglas de filtro para el producto.
            $rules = [];
            array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);

            // Inicialización de variables de transacción.
            $data = array();
            $data["success"] = false;
            $data["error"] = 1;

            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';

            $banco = $Producto->externoId;
            $usuario_id = $Usuario->usuarioId;
            $cedula = $Registro->cedula;
            $nombre = $Usuario->nombre;
            $email = $Usuario->login;
            $valor = $valor;
            $producto_id = $Producto->productoId;
            $moneda = $Usuario->moneda;
            $mandante = $Usuario->mandante;
            $first_name = $Registro->nombre;
            $last_name = $Registro->apellido1;
        }

        // Creación de la transacción en la base de datos.
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        // Cálculo de impuestos sobre el depósito.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        // Configuración de la transacción del producto.
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

        require_once 'directa24/Directa24Streamline.class.php';

        // Configuración de URLs de confirmación y retorno.
        if ($ConfigurationEnvironment->isDevelopment()) {
            $confirmUrl = 'https://apidevintegrations.virtualsoft.tech/integrations/payment/directa24/confirm/';
        } else {
            $confirmUrl = 'https://integrations.virtualsoft.tech/payment/directa24/confirm/';
        }

        $Mandante = new Mandante($Usuario->mandante);
        $returnUrl = $Mandante->baseUrl;

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $Url = $Credentials->URL;
        $urlTupay = $Credentials->URL_TUPAY;

        // Configuración para TuPay Directa24.
        if (($Usuario->mandante == 18) && ($Producto->externoId == "XA" || $Producto->externoId == "XAQR")) {
            $Keys = json_decode(base64_decode($Credentials->KEYS_TUPAY));

            $x_login = $Keys->X_LOGIN;
            $x_trans_key = $Keys->X_TRANS_KEY;
            $x_login_for_webpaystatus = $Keys->X_LOGIN_FOR_WEBPAYSTATUS;
            $x_trans_key_for_webpaystatus = $Keys->X_TRANS_KEY_FOR_WEBPAYSTATUS;
            $secret_key = $Keys->SECRET_KEY;

            if (strlen($cedula) < 8) {
                $cedula = str_pad($cedula, strlen($cedula) - 8, "0", STR_PAD_LEFT);
            }

            $aps = new Directa24Streamline($Url, $urlTupay, $x_login, $x_trans_key, $x_login_for_webpaystatus, $x_trans_key_for_webpaystatus, $secret_key, $keyTuPay = true);
            $response = $aps->newinvoiceTuPay($transproductoId, $valorTax, $banco, $Pais->iso, $usuario_id, $cedula, $nombre, $email, $moneda, '', '', '', '', '', '', $returnUrl, $confirmUrl, $first_name, $last_name, $formattedDate);

            syslog(LOG_WARNING, "RESPUESTA TUPAY: " . $usuario_id . ($response));

            $decoded_response = json_decode($response);

            $redirectUrl = $decoded_response->redirect_url;
        } else {
            // Configuración para otros métodos de pago.
            if ($Credentials != "") {
                if ($Producto->externoId == 'PPL') {
                    $Keys = json_decode(base64_decode($Credentials->KEYS_PPL));
                } else {
                    $Keys = json_decode(base64_decode($Credentials->KEYS));
                }

                $x_login = $Keys->X_LOGIN;
                $x_trans_key = $Keys->X_TRANS_KEY;
                $x_login_for_webpaystatus = $Keys->X_LOGIN_FOR_WEBPAYSTATUS;
                $x_trans_key_for_webpaystatus = $Keys->X_TRANS_KEY_FOR_WEBPAYSTATUS;
                $secret_key = $Keys->SECRET_KEY;
            } else {
                if ($detalleProveedorMandante != '') {
                    if ($detalleProveedorMandante->x_login != '') {
                        $x_login = $detalleProveedorMandante->x_login;
                    }
                    if ($detalleProveedorMandante->x_trans_key != '') {
                        $x_trans_key = $detalleProveedorMandante->x_trans_key;
                    }
                    if ($detalleProveedorMandante->x_login_for_webpaystatus != '') {
                        $x_login_for_webpaystatus = $detalleProveedorMandante->x_login_for_webpaystatus;
                    }
                    if ($detalleProveedorMandante->x_trans_key_for_webpaystatus != '') {
                        $x_trans_key_for_webpaystatus = $detalleProveedorMandante->x_trans_key_for_webpaystatus;
                    }
                    if ($detalleProveedorMandante->secret_key != '') {
                        $secret_key = $detalleProveedorMandante->secret_key;
                    }
                }
            }

            if (strlen($cedula) < 8) {
                $cedula = str_pad($cedula, strlen($cedula) - 8, "0", STR_PAD_LEFT);
            }

            $aps = new Directa24Streamline($Url, $urlTupay, $x_login, $x_trans_key, $x_login_for_webpaystatus, $x_trans_key_for_webpaystatus, $secret_key);

            if (($Usuario->mandante == 0 && $Pais->iso == 'CL') || ($Usuario->mandante == 13) || ($Usuario->mandante == 18) || ($Usuario->mandante == 25 && $Pais->iso == 'MX')) {
                $response = $aps->newinvoice($transproductoId, $valorTax, $banco, $Pais->iso, $usuario_id, $cedula, $nombre, $email, $moneda, '', '', '', '', '', '', $returnUrl, $confirmUrl);
            } else {
                $response = $aps->newinvoice($transproductoId, $valorTax, $banco, $Pais->iso, $usuario_id, $cedula, $nombre, $email, $moneda);
            }

            syslog(LOG_WARNING, "RESPUESTA DIRECTA24: " . $usuario_id . ($response));

            if ($_ENV['debug']) {
                print_r($response);
            }

            $decoded_response = json_decode($response);

            $redirectUrl = $decoded_response->link;
        }

        // Validación de la URL de redirección y registro de la transacción.
        if ($redirectUrl != "") {
            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = $response;

            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $t_value = json_encode(array());

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($t_value);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = $redirectUrl;
        } else {
            $data = array();
            $data["success"] = false;
        }

        return json_decode(json_encode($data));
    }


    /**
     * Obtiene el estado de un pago en TuPay.
     *
     * Este metodo realiza una solicitud HTTP para consultar el estado de un depósito
     * en la plataforma TuPay de Directa24. Utiliza credenciales específicas para
     * autenticar la solicitud y genera un hash HMAC para la autorización.
     *
     * @param string $cashout_id ID del depósito a consultar.
     *
     * @return mixed Respuesta de la API de TuPay.
     */
    public function paymentStatusGetTupay($cashout_id)
    {
        // Crear un objeto DateTime con la zona horaria UTC
        $date = new DateTime('now', new DateTimeZone('UTC'));
        // Formatear la fecha a 'yyyy-MM-ddTHH:mm:ssZ'
        $formattedDate = $date->format('Y-m-d\TH:i:s\Z');

        // Definir los encabezados y el payload
        $xDate = $formattedDate;

        $Subproveedor = new Subproveedor("", "DIRECTA24");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, 0, 173);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $urlTupay = $Credentials->URL_TUPAY;
        $Keys = json_decode(base64_decode($Credentials->KEYS_TUPAY));

        $xLogin = $Keys->X_LOGIN;
        $secretKey = $Keys->SECRET_KEY;
        $path = $urlTupay . '/v3/deposits/' . $cashout_id;

        $data = $xDate . $xLogin;

        // Generar el hash HMAC utilizando SHA-256
        $hash = hash_hmac('sha256', $data, $secretKey);

        $response = $this->curlTuPay($path, $xDate, $hash, $xLogin);

        return $response;
    }

    /**
     * Realiza una solicitud HTTP a la API de TuPay.
     *
     * Este metodo utiliza cURL para enviar una solicitud GET a la URL especificada
     * y devuelve la respuesta de la API.
     *
     * @param string $url    URL de la API de TuPay.
     * @param string $xDate  Fecha y hora en formato UTC.
     * @param string $hash   Hash HMAC para autorización.
     * @param string $xLogin Nombre de usuario para autenticación.
     *
     * @return mixed Respuesta de la API de TuPay.
     */
    private function curlTuPay($url, $xDate, $hash, $xLogin)
    {
        $header = [
            'X-Login: ' . $xLogin,
            'X-Date: ' . $xDate,
            'Authorization: D24 ' . $hash
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
