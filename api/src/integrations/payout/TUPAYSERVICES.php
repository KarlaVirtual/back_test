<?php

/**
 * Este archivo contiene la implementación de la clase `TUPAYSERVICES` para la integración
 * con el servicio de pagos TUPAY. Proporciona funcionalidades para procesar solicitudes
 * de retiro de fondos, confirmar transacciones, manejar reversión de créditos y registrar
 * logs detallados de transacciones.
 *
 * @category API
 * @package  Backend\integrations\payout
 * @author   Karla Ramírez <karla.ramirez@virtualsoft.tech>
 * @version  1.0
 * @since    2025-02-04
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
 * Clase para integración con el servicio de pagos TUPAY.
 *
 * Proporciona funcionalidades para:
 * - Procesar solicitudes de retiro de fondos (cashout).
 * - Confirmar transacciones con el proveedor TUPAY.
 * - Manejar reversión de créditos en caso de fallo.
 * - Registrar logs detallados de transacciones.
 * - Integrar con sistema de cuentas de cobro.
 * - Gestionar tipos de cuentas bancarias.
 * - Procesar notificaciones de estado de transacciones.
 */
class TUPAYSERVICES
{
    /**
     * Constructor de la clase TUPAYSERVICES.
     */
    public function __construct()
    {
    }

    /**
     * Procesa una solicitud de retiro de fondos (cashout).
     *
     * Flujo principal:
     * 1. Valida datos del usuario y cuenta bancaria.
     * 2. Crea registro de transacción.
     * 3. Obtiene credenciales del proveedor.
     * 4. Construye payload para la API de TUPAY.
     * 5. Envía solicitud al proveedor.
     * 6. Registra logs y actualiza estados.
     * 7. Confirma el estado de la transacción.
     *
     * @param CuentaCobro $CuentaCobro Objeto con datos de la cuenta de cobro que contiene:
     *                                 - $CuentaCobro->usuarioId Id del usuario.
     *                                 - $CuentaCobro->mediopagoId Id del medio de pago.
     * @param integer     $ProductoId  ID del producto asociado.
     *
     * @return void
     * @throws Exception Cuando falla el procesamiento de la transferencia.
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
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);

        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $order_id = $transproductoId;
        $amount = $CuentaCobro->getValor();
        $account_type = $UsuarioBanco->getTipoCuenta();

        switch ($account_type) {
            case "0":
                $account_type = "savings";
                break;
            case "1":
                $account_type = "current";
                break;
            case "Ahorros":
                $account_type = "savings";
                break;
            case "Corriente":
                $account_type = "current";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        $timestamp = date("Y/m/d");
        $dueDate = date("Y/m/d", strtotime($timestamp . ' +1 day'));

        $data = [
            "customId" => $Usuario->moneda,
            "amount" => $amount,
            "userName" => $Registro->nombre,
            "userPhone" => $Registro->celular,
            "userEmail" => $Registro->email,
            "userBank" => $Banco->descripcion,
            "userTypeAccount" => $account_type,
            "userNumAccount" => $UsuarioBanco->cuenta,
            "userIdentificationNumber" => $Registro->cedula,
            "dueDate" => $dueDate
        ];

        $Path = 'api/payout/register';
        $result = $this->withdrawal($credentials->URL . $Path, json_encode($data), $credentials->API_KEY);

        if ($_ENV['debug']) {
            print_r(json_encode($data));
            print_r(PHP_EOL);
            print_r($result);
        }

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
     * Confirma el estado de una transacción con TUPAY.
     *
     * @param mixed   $data          Datos de respuesta del proveedor.
     * @param integer $cuentaId      ID de la cuenta de cobro.
     * @param string  $id            ID de transacción externa.
     * @param integer $TransactionId ID de transacción interna.
     * @param string  $status        Estado de la transacción.
     * @param string  $comment       Comentario del proveedor.
     *
     * @return string 'success' cuando la confirmación es exitosa.
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
                        exec("php -f " . __DIR__ . "/../../../src/integrations/crm/AgregarCrm.php " . $CuentaCobro->usuarioId . " " . "RETIROPAGADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");
                    }
                }
            }
        }
        return ('success');
    }

    /**
     * Realiza una solicitud de retiro a la API de TUPAY.
     *
     * @param string $Url     Endpoint de la API.
     * @param string $data    Datos de la transacción en JSON.
     * @param string $Api_Key Clave de autenticación.
     *
     * @return string Respuesta del servidor.
     */
    public function withdrawal($Url, $data, $Api_Key)
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
                'x-api-key: ' . $Api_Key
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
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
