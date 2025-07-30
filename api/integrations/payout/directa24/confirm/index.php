<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Directa24.
 * Procesa solicitudes HTTP entrantes, realiza validaciones, actualiza estados de transacciones
 * y registra información en la base de datos.
 *
 * @category   Integración
 * @package    API
 * @subpackage Payout
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST                    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                        Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $request                     Variable que representa la solicitud HTTP, conteniendo datos como parámetros y encabezados.
 * @var mixed $data                        Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $log                         Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $date                        Variable que almacena una fecha genérica.
 * @var mixed $bank_reference_id           Variable que almacena el identificador de referencia proporcionado por el banco.
 * @var mixed $comments                    Variable que almacena comentarios adicionales relacionados con una transacción.
 * @var mixed $external_id                 Variable que almacena un identificador externo asociado a una transacción.
 * @var mixed $control                     Variable que almacena un código de control para una operación.
 * @var mixed $cashout_id                  Variable que almacena el identificador de un retiro de fondos.
 * @var mixed $status_reason               Variable que almacena la razón del estado de la transacción.
 * @var mixed $DIRECTA24SERVICES           Variable que almacena información relacionada con Directa24 Services.
 * @var mixed $ResponseStatus              Variable que almacena el estado de una respuesta.
 * @var mixed $cashout_status              Variable que almacena el estado del retiro de fondos.
 * @var mixed $cashout_status_description  Variable que almacena una descripción detallada del estado del retiro de fondos.
 * @var mixed $rejection_code              Variable que almacena un código que indica el motivo de un rechazo de transacción.
 * @var mixed $rejection_reason            Variable que almacena la razón específica del rechazo de una transacción.
 * @var mixed $estado                      Variable que almacena el estado de un proceso o entidad.
 * @var mixed $TransprodLogMysqlDAO        Variable que representa la capa de acceso a datos MySQL para TransprodLog.
 * @var mixed $TransaccionProducto         Variable que almacena información sobre una transacción de producto.
 * @var mixed $Transaction                 Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $TransaccionProductoMySqlDAO Variable que representa la capa de acceso a datos MySQL para transacciones de productos.
 * @var mixed $TransprodLog                Variable que almacena registros de transacciones de productos.
 * @var mixed $TransprodLog_id             Variable que almacena el identificador de un registro en TransprodLog.
 * @var mixed $rowsUpdate                  Variable que almacena el número de filas actualizadas en una consulta.
 * @var mixed $CuentaCobro                 Variable que almacena información sobre una cuenta de cobro.
 * @var mixed $CuentaCobroMySqlDAO         Variable que representa la capa de acceso a datos MySQL para cuentas de cobro.
 * @var mixed $Usuario                     Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorial            Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorialMySqlDAO    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $useragent                   Variable que almacena información sobre el User-Agent de un usuario.
 * @var mixed $_SERVER                     Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $jsonServer                  Variable que almacena datos en formato JSON relacionados con un servidor.
 * @var mixed $serverCodif                 Variable que almacena información codificada de un servidor.
 * @var mixed $ismobile                    Variable que indica si el acceso se realiza desde un dispositivo móvil.
 * @var mixed $iPod                        Variable que almacena información sobre dispositivos iPod.
 * @var mixed $iPhone                      Variable que almacena información sobre dispositivos iPhone.
 * @var mixed $iPad                        Variable que almacena información sobre dispositivos iPad.
 * @var mixed $Android                     Variable que almacena información sobre dispositivos Android.
 * @var mixed $webOS                       Variable que almacena información sobre sistemas webOS.
 */

ini_set('display_errors', 'OFF');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payout\DIRECTA24SERVICES;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

$request = json_encode($_REQUEST);
$data = json_decode((file_get_contents('php://input'))) ?? json_decode($request);

/* Obtenemos Variables que nos llegan */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($data);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

if (isset($data)) {
    $date = $data->date;
    $bank_reference_id = $data->bank_reference_id;
    $comments = $data->comments;
    $external_id = $data->external_id;
    $control = $data->control;
    $cashout_id = $data->cashout_id;
    $status_reason = $data->status_reason;

    $DIRECTA24SERVICES = new DIRECTA24SERVICES();

    $ResponseStatus = $DIRECTA24SERVICES->CashoutStatusRequest($cashout_id, $external_id);

    if ($_ENV['debug']) {
        print_r("\r\n");
        print_r('****DATAUSER****');
        print_r($data);
        print_r("\r\n");
        print_r('****RESPONSE****');
        print_r(json_encode($ResponseStatus));
    }

    $ResponseStatus = json_decode($ResponseStatus);

    $cashout_status = $ResponseStatus->cashout_status;
    $cashout_status_description = $ResponseStatus->cashout_status_description;
    $rejection_code = $ResponseStatus->rejection_code;
    $rejection_reason = $ResponseStatus->rejection_reason;


    $estado = 'P';

    switch ($cashout_status) {
        case "1":
            $estado = 'A';
            break;
        case "2":
            $estado = 'R';
            break;
        case "3":
            $estado = 'R';
            break;
        case "4":
            $estado = 'P';
            break;
    }

    if ($estado != "P") {
        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

        $TransaccionProducto = new TransaccionProducto($external_id);
        $Transaction = $TransprodLogMysqlDAO->getTransaction();

        $TransaccionProducto->setEstado("I");
        $TransaccionProducto->setExternoId($cashout_id);
        $TransaccionProducto->setEstadoProducto($estado);
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($external_id);
        $TransprodLog->setEstado($estado);
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario(json_encode($ResponseStatus));
        $TransprodLog->setTValue(json_encode($data));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

        $rowsUpdate = 0;
        $CuentaCobro = new CuentaCobro("", $external_id);

        if ($CuentaCobro->getEstado() == "S") {
            if ($estado == "A") {
                $CuentaCobro->setEstado("I");
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
            }
            if ($estado == "R") {
                $CuentaCobro->setEstado("R");
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
            }

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

            if ($estado == "A") {
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
                }elseif($iPad){
                    $ismobile = '1';
                }elseif($Android){
                    $ismobile = '1';
                }
            }
        }
        print_r("OK");
    }
}
