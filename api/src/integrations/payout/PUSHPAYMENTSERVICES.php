<?php

/**
 * Esta clase se encarga de gestionar las integraciones con el servicio de pagos WEPAY4U.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use DateTime;
use Exception;

/**
 * Clase PUSHPAYMENTSERVICES
 *
 * Esta clase gestiona las integraciones con el servicio de pagos WEPAY4U,
 * proporcionando métodos para realizar operaciones como retiros, autenticación,
 * y manejo de tarjetas de usuario.
 */
class PUSHPAYMENTSERVICES
{
    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payout/pushpayment/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/pushpayment/confirm/";

    /**
     * Constructor de la clase PUSHPAYMENTSERVICES.
     *
     * Inicializa la URL de callback dependiendo del entorno (desarrollo o producción).
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
     * Realiza un retiro de efectivo para un usuario específico.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param int         $ProductoId  Identificador del producto asociado al retiro.
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso de retiro.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $ProductoId)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $Producto = new Producto($ProductoId);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($ProductoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($CuentaCobro->getValor());
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $CuentaCobro->setTransproductoId($transproductoId);
        $Pais = new Pais($Usuario->paisId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $token = $this->GenerateToken($credentials->UrlTokenAccess, $credentials->User, $credentials->Pass);
        $order_id = $transproductoId;
        $amount = $CuentaCobro->getValor();
        $saldo = str_replace(',', '', number_format(round($amount, 2), 2, '.', null));
        $OrderNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $Iso = $Pais->iso;

        switch ($Pais->iso) {
            case "CL":
                $Pais->iso = "CHL";
                break;
            case "MX":
                $Pais->iso = "MEX";
                break;
            case "EC":
                $Pais->iso = "ECU";
                break;
            case "PE":
                $Pais->iso = "PER";
                break;
            case "SV":
                $Pais->iso = "SLV";
                break;
            case "GT":
                $Pais->iso = "GTM";
                break;
            case "BR":
                $Pais->iso = "BRA";
                break;
            case "HN":
                $Pais->iso = "HND";
                break;
        }

        $data = [
            "channel" => 'mobile',
            "terminalId" => '3',
            "order" => [
                "purchaseNumber" => $OrderNumber,
                "amount" => $saldo,
                "businessApplicationId" => 'OG',
                "currency" => $Usuario->moneda,
                "externalTransactionId" => $order_id,
            ],
            "merchant" => [
                "name" => 'Doradobet',
                "address" => [
                    "country" => $Pais->iso,
                ]
            ],
            "recipient" => [
                "tokenId" => $UsuarioBanco->token,
                "email" => $Registro->email,
                "firstName" => $Registro->nombre1,
                "lastName" => $Registro->apellido1,
                "address" => $Registro->direccion,
                "city" => $Registro->ciudad,
                "countryCode" => $Pais->iso
            ],
        ];

        $result = $this->withdrawal($credentials->UrlWithdrawal . $credentials->MerchantId, $token, json_encode($data));

        if ($_ENV['debug']) {
            print_r(json_encode($data));
            print_r(PHP_EOL);
            print_r($result);
        }

        syslog(LOG_WARNING, "PUSHPAYMENT PAYOUT DATA: " . json_encode($data) . "RESPONSE: " . $result);
        $Result = json_decode($result);

        if ($Result->responseCode == 200) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result[0]->id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            if ($Result->responseCode == "200") {
                $CuentaCobro->setEstado("I");
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
            }
            if ($Result->responseCode == "400") {
                $CuentaCobro->setEstado("R");
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));
            }

            $Transaction->commit();

            $confirm = $this->confirm($Result, $CuentaCobro->cuentaId, $Result->order->externalTransactionId, $order_id, $Result->responseCode, $Result->responseMessage);
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Confirma el estado de una transacción y actualiza los registros correspondientes.
     *
     * @param mixed  $data          Datos de la respuesta de la transacción.
     * @param int    $cuentaId      Identificador de la cuenta asociada.
     * @param int    $id            Identificador externo de la transacción.
     * @param int    $TransactionId Identificador interno de la transacción.
     * @param string $status        Estado de la transacción (por ejemplo, "200" para aprobado, "400" para rechazado).
     * @param string $comment       Comentario adicional sobre el estado de la transacción.
     *
     * @return string Retorna 'success' si la operación se realiza correctamente.
     */
    public function confirm($data, $cuentaId, $id, $TransactionId, $status, $comment)
    {
        if (isset($TransactionId)) {
            $estado = 'P';
            switch ($status) {
                case "200":
                    $estado = 'A';
                    break;
                case "400":
                    $estado = 'R';
                    break;
            }

            if ($estado != "P") {
                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $TransaccionProducto = new TransaccionProducto($TransactionId);

                if ($TransaccionProducto->getEstado() == 'I') {
                    return ('success');
                }

                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $TransaccionProducto->setEstado("I");
                $TransaccionProducto->setEstadoProducto($estado);
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($TransactionId);

                if ($comment != "" && $estado == "R") {
                    $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
                } elseif ($estado == "A") {
                    //$TransprodLog->setComentario(json_encode($respuesta));
                } else {
                    $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
                }

                $TransprodLog->setEstado($estado);
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setTValue(json_encode($data));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                $rowsUpdate = 0;
                $CuentaCobro = new CuentaCobro($cuentaId);

                if ($CuentaCobro->getEstado() == "P") {
                    try {
                        if ($estado == "R" && $rowsUpdate > 0) {
                            $Usuario = new Usuario($TransaccionProducto->usuarioId);
                            $Usuario->creditWin(floatval($CuentaCobro->getValor()) + floatval($CuentaCobro->getImpuesto()), $Transaction);

                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(40);
                            $UsuarioHistorial->setValor($TransaccionProducto->getValor());
                            $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                        }

                        $Transaction->commit();
                    } catch (Exception $e) {
                    }

                    if ($estado == "A") {
                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                        $jsonServer = json_encode($_SERVER);
                        $serverCodif = base64_encode($jsonServer);

                        $ismobile = '';
                        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
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
                        exec(
                            "php -f " . __DIR__ . "/../../../src/integrations/crm/AgregarCrm.php " . $CuentaCobro->usuarioId . " " . "RETIROPAGADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &"
                        );
                    }
                }
            }
        }
        return ('success');
    }

    /**
     * Agrega una tarjeta para un usuario específico.
     *
     * @param Usuario $Usuario          Objeto que representa al usuario.
     * @param mixed   $Producto         Producto asociado a la tarjeta.
     * @param string  $numTarjeta       Número de la tarjeta.
     * @param string  $holder_name      Nombre del titular de la tarjeta.
     * @param string  $expiry_month     Mes de expiración de la tarjeta.
     * @param string  $expiry_year      Año de expiración de la tarjeta.
     * @param string  $cvc              Código de seguridad de la tarjeta.
     * @param int     $ProveedorId      Identificador del proveedor.
     * @param string  $transactionToken Token de la transacción.
     *
     * @return mixed Respuesta con el estado de la operación.
     */
    public function AddCard(Usuario $Usuario, $Producto, $numTarjeta, $holder_name, $expiry_month, $expiry_year, $cvc, $ProveedorId, $transactionToken)
    {
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $respuesta = $this->GenerateToken($credentials->UrlTokenAccess, $credentials->User, $credentials->Pass);

        $RequestToken = $this->RequestToken($credentials->UrlRequestToken . $credentials->MerchantId . '/' . $transactionToken, $respuesta);

        $Banco = new Banco('124');
        $RequestToken = json_decode($RequestToken);

        if ($RequestToken->errorMessage == "OK") {
            try {
                $UsuarioBanco = new UsuarioBanco('', $Usuario->usuarioId, 'CARD');
                foreach ($UsuarioBanco->UsuarioBancos as $tokens) {
                    if ($RequestToken->token->tokenId == $tokens->token) {
                        throw new Exception("Token de tarjeta ya agregado", "300089");
                    }
                }
            } catch (Exception $e) {
            }
            try {
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
                $UsuarioBanco = new UsuarioBanco();
                $UsuarioBanco->setUsuarioId($Usuario->usuarioId);
                $UsuarioBanco->setBancoId($Banco->bancoId);
                $UsuarioBanco->setCuenta($RequestToken->card->cardNumber);
                $UsuarioBanco->setTipoCuenta('CARD');
                $UsuarioBanco->setTipoCliente('');
                $UsuarioBanco->setEstado('A');
                $UsuarioBanco->setUsucreaId('0');
                $UsuarioBanco->setUsumodifId('0');
                $UsuarioBanco->setCodigo($RequestToken->order->actionCode);
                $UsuarioBanco->setToken($RequestToken->token->tokenId);
                $UsuarioBanco->setProductoId($Producto->productoId);

                $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO($Transaction); //
                $UsuarioBancoMySqlDAO->insert($UsuarioBanco);
                $Transaction->commit();
            } catch (Exception $e) {
            }
        }

        if ($RequestToken->errorCode != "0") {
            $data = array();
            $data["success"] = false;
            $data["Message"] = $RequestToken->errorMessage;
        } else {
            $data = array();
            $data["success"] = true;
            $data["Message"] = $RequestToken->errorMessage;
            $data["cardNumber"] = $RequestToken->card->cardNumber;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Autentica un usuario y genera un token de sesión para realizar operaciones.
     *
     * @param Usuario $Usuario  Objeto que representa al usuario.
     * @param mixed   $Producto Producto asociado a la autenticación.
     *
     * @return mixed Respuesta con el estado de la autenticación y datos adicionales.
     */
    public function Auth($Usuario, $Producto)
    {
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $Registro = new Registro("", $Usuario->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        $respuesta = $this->GenerateToken($credentials->UrlTokenAccess, $credentials->User, $credentials->Pass);
        $ClientIp = $this->get_client_ip();
        $iP = explode(',', $ClientIp);
        $FechaRegistro = $Usuario->fechaCrea;
        $FechaRegistroDT = new DateTime($FechaRegistro);
        $FechaActual = new DateTime();
        $Diferencia = $FechaRegistroDT->diff($FechaActual);

        $data = [
            "channel" => 'paycard',
            "amount" => '1.00',
            "antifraud" => [
                "clientIp" => $iP[0],
                "merchantDefineData" => [
                    "MDD4" => $Registro->email,
                    "MDD32" => $Registro->cedula,
                    "MDD75" => 'Registrado',
                    "MDD77" => strval($Diferencia->days)
                ],
            ],
        ];

        $Data = json_encode($data);
        $Response = $this->TokenSession($credentials->UrlSessionToken . $credentials->MerchantId, $respuesta, $Data);

        syslog(LOG_WARNING, "PUSHPAYMENT PAYOUT DATA: " . $Data . "RESPONSE: " . $Response);

        $Response = json_decode($Response);
        $OrderNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        if ($Response->errorCode = ! ' ') {
            $return = [
                "success" => false,
                "errorCode" => $Response->errorCode,
                "errorDescription" => $Response->errorMessage
            ];
        } else {
            $return = [
                "success" => true,
                "sessionKey" => $Response->sessionKey,
                "dataInfo" => [
                    "action" => 'https://sp-vsft-11871.virtualsoft.bet/gestion/cuentasbancarias',
                    "channel" => 'paycard',
                    "merchantid" => $credentials->MerchantId,
                    "purchasenumber" => $OrderNumber,
                    "amount" => '1.00',
                    "cardholdername" => $Registro->nombre1 . ' ' . $Registro->nombre2,
                    "cardholderlastname" => $Registro->apellido1 . ' ' . $Registro->apellido2,
                    "cardholderemail" => $Registro->email,
                    "usertoken" => $Usuario->usuarioId,
                    "expirationminutes" => '5',
                    "timeouturl" => 'https://sp-vsft-11871.virtualsoft.bet/gestion/cuentasbancarias',
                    "merchantname" => 'Doradobet'
                ]
            ];
        }

        return json_decode(json_encode($return));
    }

    /**
     * Genera un token de autenticación utilizando credenciales básicas.
     *
     * @param string $UrlToken URL del servicio para generar el token.
     * @param string $User     Nombre de usuario para la autenticación.
     * @param string $Password Contraseña para la autenticación.
     *
     * @return string Respuesta del servicio con el token generado.
     */
    public function GenerateToken($UrlToken, $User, $Password)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $UrlToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($User . ':' . $Password)
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Genera un token de sesión para realizar operaciones específicas.
     *
     * @param string $UrlSessionToken URL del servicio para generar el token de sesión.
     * @param string $token           Token de autenticación previamente generado.
     * @param string $data            Datos en formato JSON necesarios para la solicitud.
     *
     * @return string Respuesta del servicio con el token de sesión generado.
     */
    public function TokenSession($UrlSessionToken, $token, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $UrlSessionToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Solicita un token al servicio especificado.
     *
     * @param string $Url   URL del servicio para solicitar el token.
     * @param string $token Token de autenticación previamente generado.
     *
     * @return string Respuesta del servicio con el token solicitado.
     */
    public function RequestToken($Url, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Realiza una solicitud de retiro al servicio especificado.
     *
     * @param string $Url   URL del servicio para realizar el retiro.
     * @param string $token Token de autenticación previamente generado.
     * @param string $data  Datos en formato JSON necesarios para la solicitud.
     *
     * @return string Respuesta del servicio con el resultado del retiro.
     */
    public function withdrawal($Url, $token, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente que realiza la solicitud.
     *
     * Este método verifica varias variables de entorno para determinar
     * la dirección IP del cliente, devolviendo 'UNKNOWN' si no se encuentra ninguna.
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
