<?php

/**
 * Clase `PAYCASHSERVICES` para gestionar servicios de pago mediante la integración con PayCash.
 *
 * Este archivo contiene la implementación de métodos para realizar solicitudes de pago,
 * conexiones HTTP, generación de tokens y consultas GET a la API de PayCash.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Backend\dto\Ciudad;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuarioMandante;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\dto\UsuarioToken;
use Backend\dto\Departamento;
use \CurlWrapper;
use Exception;

/**
 * Clase `PAYCASHSERVICES`.
 *
 * Esta clase gestiona la integración con los servicios de pago de PayCash,
 * proporcionando métodos para realizar solicitudes de pago, generar tokens,
 * y realizar consultas a la API.
 */
class PAYCASHSERVICES
{
    /**
     * Constructor de la clase `PAYCASHSERVICES`.
     *
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Crea una solicitud de pago.
     *
     * Este metodo realiza una transacción de pago, calcula impuestos, genera un token
     * y envía la solicitud a la API de PayCash.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza el pago.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta de la API de PayCash.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioToken = new UsuarioToken($Usuario->usuarioId);
        $token = $UsuarioToken->token;

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $UsuarioMandante = new UsuarioMandante("", $usuario_id, $mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

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

        $pais = 'mexico';
        switch ($Usuario->paisId) {
            case "173":
                $pais = 'peru';
                date_default_timezone_set('America/Lima');
                break;
            case "146":
                $pais = 'mexico';
                date_default_timezone_set('America/Mexico_City');
                break;
            case "170":
                $pais = 'panama';
                date_default_timezone_set('America/Panama');
                break;
            case "94":
                $pais = 'guatemala';
                date_default_timezone_set('America/Guatemala');
                break;
            case "102":
                $pais = 'honduras';
                date_default_timezone_set('America/Tegucigalpa');
                break;
            case "66":
                $pais = 'ecuador';
                date_default_timezone_set('America/Ecuador');
                break;
        }

        $denom = '$';

        if ($mandante == 15) {
            $denom = 'HNL';

            $Stores = array(
                array(
                    'name' => 'Ficohsa Interbanca / APP',
                    'img' => 'https://images.virtualsoft.tech/m/msjT1694627322.png',
                ),
                array(
                    'name' => 'Ficohsa Ventanilla',
                    'img' => 'https://images.virtualsoft.tech/m/msjT1694623818.png',
                )
            );
        }

        $respuesta = $this->generateToken($credentials->URL . $pais . $credentials->URL_API, '/v1/authre?key=', $credentials->KEY);
        syslog(LOG_WARNING, "PAYCASH TOKEN RESPONSE: " . json_encode($respuesta));
        $tokenT = $respuesta->Authorization;

        $date_now = date('Y-m-d H:i:s');
        $date_future = strtotime('+2 day', strtotime($date_now));
        $date_future_formatted = date('Y-m-d', $date_future);

        date_default_timezone_set('America/Bogota');

        $data = array();
        $data['Amount'] = $valorTax;
        $data['ExpirationDate'] = $date_future_formatted;
        $data['Value'] = $transproductoId;
        $data['Type'] = true;

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        syslog(LOG_WARNING, "PAYCASH DATA: " . json_encode($data));

        $Result = $this->connection($data, $tokenT, $credentials->URL . $pais . $credentials->URL_API, '/v1/reference');

        syslog(LOG_WARNING, "PAYCASH RESPONSE: " . json_encode($Result));

        if ($Result != '' && $Result->ErrorCode == 0) {
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

            $TransaccionProducto->setExternoId($Result->Reference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
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

            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }

            $response = ($Result);
            $extID = $Producto->externoId;
            $Img = $Producto->imageUrl;
            $name = $Producto->descripcion;

            if ($extID == 'L01' && ($pais == 'guatemala' || $pais == 'honduras' || $pais == 'ecuador')) {
                $tokenEncrypted = base64_encode(sha1($credentials->KEY));
                $emisorEncrypted = base64_encode(sha1($credentials->EMISOR));
                $referenceEncrypted = base64_encode($Result->Reference);
                $Response = $credentials->URL_REDIRECTION . '/formato.php?emisor=' . $emisorEncrypted . '&token=' . $tokenEncrypted . '&referencia=' . $referenceEncrypted . '&interno==1==';

                $data = array();
                $data["success"] = true;
                $data["url"] = $Response;
                return json_decode(json_encode($data));
            } else {
                if ($mandante == 15) {
                    $textHeader = "<p style='font-weight: bold;'>$name</p> <br> Realiza tu pago en cualquiera de nuestros puntos físicos o en Interbanca. <br><br> Pasos Para Transaccionar:";
                } else {
                    $textHeader = "<p style='font-weight: bold;'>$name</p> <br> Realiza tu pago en cualquiera de nuestros puntos físicos. <br><br> Pasos Para Transaccionar:";
                    $textHeader_ = "<p style='font-weight: bold;'>$name</p> <br><br> Realiza tu pago en cualquiera de nuestros puntos o por banca electrónica. <br><br> Pasos Para Transaccionar:";
                }

                $comis = 0;
                $isComis = false;
                $pasos3 = "3. El pago se realiza con éxito y se entrega el comprobante de la operación (ticket).";

                if ($extID == 'P22') {
                    $comis = 0;
                    $pasos1 = "1. Solicita en ventanilla realizar un pago PayCash al Convenio 7755.";
                    $pasos2 = "2. Proporciona referencia monto fijo.";
                } elseif ($extID == 'P13') {
                    $textHeader = $textHeader_;
                    $comis = 0;
                    $pasos1 = "1. Solicita en ventanilla realizar un pago PayCash al Convenio 7292.";
                    $pasos2 = "2. Proporciona referencia y el monto a pagar.";
                    $_1 = "1. Ingresar a la banca electrónica";
                    $_2 = "2. Cuentas – operaciones mas usadas";
                    $_3 = "3. Servicios - PayCash";
                    $_4 = "4. Capturar REFERENCIA e Importe";
                    $pasos3 = "<p style='font-weight: bold;'> </p> " . $_1 . '<br>' . $_2 . '<br>' . $_3 . '<br>' . $_4;
                } elseif ($extID == 'P00' || $extID == 'P01' || $extID == 'P08' || $extID == 'P09') {
                    $comis = 10;
                    $pasos1 = "1. Solicita al cajero realizar un pago PayCash.";
                    $pasos2 = "2. Indica en caja la referencia y monto del pago.";
                } elseif ($extID == 'P17' || $extID == 'P18' || $extID == 'P19' || $extID == 'P20') {
                    $comis = 8;
                    $pasos1 = "1. Solicita al cajero realizar un pago PayCash al servicio 198.";
                    $pasos2 = "2. Indica en caja la referencia y monto del pago.";
                } elseif ($extID == 'L01') {
                    $div = "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;'>";
                    $stores = '';
                    foreach ($Stores as $Store) {
                        $stores .= "
                        <div class='card_paycash' style='display: flex; flex-direction: column; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 10px;'>
                        <img src='" . $Store['img'] . "' alt='Imagen 1' style='max-width: 100%; height: auto; border-radius: 5px 5px 0 0;'>
                        <div style='padding: 8px;'>
                            <div style='font-size: 12px; font-weight: bold; margin-bottom: 5px;'>" . $Store['name'] . "</div>
                            <div style='font-size: 9px;'>" . $Store['info'] . "</div>
                        </div>
                        </div>
                        ";
                    }
                    $comis = 0;
                    $isComis = true;
                    if ($mandante == 16) {
                        $pasos1 = "1. Busca el botón 'Paycash' en el quiosco de Punto Pago.";
                        $pasos2 = $div . $stores . "</div>";
                        $pasos2_1 = "2. Ingresa el numero de referencia";
                        $pasos3 = "3. Haz clic en la opción 'Aceptar' y se te proporcionará un recibo que confirma tu transacción.
                        <br><br> Min: $1 USD &nbsp; &nbsp; &nbsp; Max: $200 USD";
                        $info = "Los fondos serán acreditados de forma inmediata.";
                    } else {
                        $pasos1 = "1. Solicita un pago PayCash al cajero en uno de estos puntos o realizalo desde la Interbanca.";
                        $pasos2 = $div . $stores . "</div>";
                        $pasos2_1 = "2. Indica la referencia y monto del pago.";
                    }
                } else {
                    $comis = 8;
                    $pasos1 = "1. Solicita al cajero realizar un pago PayCash.";
                    $pasos2 = "2. Indica en caja la referencia y monto del pago.";
                }

                if ($isComis) {
                    $val = '&nbsp; &nbsp; &nbsp;' . 'Monto: ' . $denom . number_format($valorTax);
                    if ($mandante == 16) {
                        $text = $textHeader . '<br><br>' . $pasos1 . '<br><br>' . $pasos2 . '<br>' . $pasos2_1;
                    } else {
                        $text = $textHeader . '<br><br>' . $pasos1 . '<br><br>' . $pasos2 . '<br>' . $pasos2_1 . '<br><br>' . 'Ref: ' . $response->Reference . $val;
                    }
                    $valorTax = '';
                    $comis_ = '';
                    $total = '';
                } else {
                    $valorTax = intval($valorTax);
                    $total = $valorTax + $comis;
                    $text = $textHeader . '<br><br>' . $pasos1 . '<br><br>' . $pasos2 . '<br><br>' . 'Ref: ' . $response->Reference;
                    $valorTax = '<br><br>' . 'Monto: ' . '$' . number_format($valorTax);
                    $comis_ = '&nbsp; - &nbsp; Comision: ' . '$' . $comis;
                    $total = '<br><br>Total: ' . '$' . number_format($total);
                }

                $data = array();
                $data["success"] = true;
                $data["textHeader"] = $textHeader;
                $data["amount"] = $valorTax;
                if ($mandante == 16) {
                    $data["dataText"] = $text . $valorTax . $comis_ . $total . '<br><br>' . $pasos3 . '<br><br>' . $info . '<br><br>' . 'Ref: ' . $response->Reference . $val;
                } else {
                    $data["dataText"] = $text . $valorTax . $comis_ . $total . '<br><br>' . $pasos3;
                }
                $data["dataImg"] = 'noImg';
            }
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una conexión HTTP POST.
     *
     * Este metodo envía datos a una URL específica utilizando cURL.
     *
     * @param array  $data  Datos a enviar en la solicitud.
     * @param string $token Token de autorización.
     * @param string $url   URL base de la API.
     * @param string $path  Ruta específica del endpoint.
     *
     * @return object Respuesta de la API.
     */
    public function connection($data, $token, $url, $path)
    {
        $curl = new CurlWrapper($url . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return json_decode($response);
    }

    /**
     * Realiza una solicitud HTTP GET.
     *
     * Este metodo consulta datos de la API de PayCash utilizando cURL.
     *
     * @param string  $pais     Código del país.
     * @param string  $fecha    Fecha de la consulta.
     * @param string  $hora     Hora de la consulta.
     * @param string  $path     Ruta específica del endpoint.
     * @param integer $mandante Identificador del mandante.
     *
     * @return string Respuesta de la API.
     */
    public function requestGET($pais, $fecha, $hora, $path, $mandante)
    {
        $Subproveedor = new Subproveedor('', 'PAYCASH');
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $pais);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $respuesta = $this->generateToken($credentials->URL . $pais . $credentials->URL_API, '/v1/authre?key=', $credentials->KEY);
        $tokenT = $respuesta->Authorization;

        $curl = new CurlWrapper($credentials->URL . $pais . $credentials->URL_API . $path . 'Date=' . $fecha . '&Hour=' . $hora);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $credentials->URL . $pais . $credentials->URL_API . $path . 'Date=' . $fecha . '&Hour=' . $hora,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $tokenT
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Genera un token de autenticación.
     *
     * Este metodo obtiene un token de la API de PayCash para realizar solicitudes autenticadas.
     *
     * @param string $url  URL base de la API.
     * @param string $path Ruta específica del endpoint.
     * @param string $Key  Clave de autenticación.
     *
     * @return object Token generado por la API.
     */
    public function generateToken($url, $path, $Key)
    {
        $curl = new CurlWrapper($url . $path . $Key);

        syslog(LOG_WARNING, "PAYCASH TOKEN DATA: " . $url . $path . $Key);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path . $Key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = $curl->execute();

        return json_decode($response);
    }
}