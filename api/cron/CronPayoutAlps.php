<?php

/**
 * Este archivo maneja la confirmación de pagos a través de la integración con Alps.
 * Procesa las solicitudes entrantes, actualiza el estado de las transacciones y realiza
 * operaciones relacionadas con los registros de transacciones y cuentas de cobro.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payout\Alps
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales y sus descripciones:
 *
 * @var mixed $log                         Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $data                        Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $ConfigurationEnvironment    Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                     Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $clave                       Esta variable guarda la clave o contraseña para autenticación y acceso seguro al sistema (generalmente encriptada).
 * @var mixed $transactionid               Variable que almacena el identificador único de una transacción.
 * @var mixed $status                      Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $responseBank                Variable que almacena la respuesta del banco ante una transacción.
 * @var mixed $Proveedor                   Esta variable representa la información del proveedor, utilizada para operaciones comerciales o logísticas.
 * @var mixed $Producto                    Variable que almacena información del producto.
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
 * @var mixed $response                    Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'OFF');

require_once __DIR__ . '../../vendor/autoload.php';

use Backend\dto\Usuario;
use Backend\dto\CuentaCobro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\integrations\payout\ALPSSERVICES;
use Backend\mysql\TransaccionProductoMySqlDAO;

$SkeepRows = 0;
$MaxRows = 100000;

$Proveedor = new Backend\dto\Proveedor("", "ALPSPAYOUT");
$Subproveedor = new Subproveedor("", "ALPSPAYOUT");
$Producto = new Backend\dto\Producto("", 15, $Proveedor->proveedorId);
$CuentaCobro = new CuentaCobro();

$rules = [];

array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "S", "op" => "eq"));
array_push($rules, array("field" => "producto.proveedor_id", "data" => "$Proveedor->proveedorId", "op" => "neq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);
$select = "cuenta_cobro.cuenta_id, cuenta_cobro.transproducto_id,cuenta_cobro.mandante,producto.subproveedor_id,cuenta_cobro.usuario_id";
$grouping = "cuenta_cobro.cuenta_id";
$daydimensionFechaPorPago = false;
$cuentas = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, '', true, $daydimensionFechaPorPago);
$cuentas = json_decode($cuentas);
$CuentasCobro = $cuentas->data;

$ALPSSERVICES = new ALPSSERVICES();

foreach ($CuentasCobro as $key => $Id2) {

    $CuentaCobro = new CuentaCobro($CuentasCobro[$key]->{'cuenta_cobro.cuenta_id'});
    $TransId = $CuentasCobro[$key]->{'cuenta_cobro.transproducto_id'};
    $TransProducto = new TransaccionProducto($TransId);
    $mandante = $CuentasCobro[$key]->{'cuenta_cobro.mandante'};
    $Subproveedor = $CuentasCobro[$key]->{'producto.subproveedor_id'};
    $usuarioId = $CuentasCobro[$key]->{'cuenta_cobro.usuario_id'};

    $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
    $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor, $mandante, $UsuarioMandante->paisId);
    $credentials = json_decode($SubproveedorMandantePais->getCredentials());


    $path = 'login';
    $URL = $credentials->URL;
    $dataCredential = [
        'username' => $credentials->USERNAME,
        'password' => $credentials->PASSWORD
    ];

    $Token = $ALPSSERVICES->GetToken($URL . $path, json_encode($dataCredential));
    $Response = json_decode($Token);

    if ($TransProducto->getEstado() != 'I') {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $mandante = $Usuario->mandante;
        $pais = $Usuario->paisId;
        $id = $CuentaCobro->cuentaId;
        $path = 'transactions/?credit_note=' . $id; // Obtener nota cretido que es igual a cuenta cobro id
        $response = $ALPSSERVICES->GetTransaction($URL . $path, $Response->token);
        $Response = json_decode($response);
        $Respuesta = $Response->items[0];
        $transactionId = $Respuesta->id;
        $status = $Respuesta->transfer_status;
        $responseBank = $Respuesta->transfer_status_description;

        $estado = 'P';

        switch ($status) {
            case "1":
                $estado = 'P'; // in process
                break;
            case "2":
                $estado = 'P'; // approved
                break;
            case "3":
                $estado = 'R'; // rejected
                break;
            case "4":
                $estado = 'R'; // rejected by bank
                break;
            case "5":
                $estado = 'A'; // approved by bank
                break;
        }

        if ($estado != "P") {
            $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();
            $TransaccionProducto = new TransaccionProducto("", $Respuesta->id, $Producto->productoId);
            $Transaction = $TransprodLogMysqlDAO->getTransaction();
            $TransaccionProducto->setEstado("I");
            $TransaccionProducto->setEstadoProducto($estado);
            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($TransaccionProducto->transproductoId);
            $TransprodLog->setEstado($estado);
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario($responseBank);
            $TransprodLog->setTValue(json_encode($Response));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

            $rowsUpdate = 0;
            $CuentaCobro = new CuentaCobro("", $TransaccionProducto->transproductoId);

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
                    $Usuario->creditWin(floatval($CuentaCobro->getValor()), $Transaction);

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
                    } elseif ($iPad) {
                        $ismobile = '1';
                    } elseif ($Android) {
                        $ismobile = '1';
                    }
                }
            }
            $response = array(
                "success" => true,
                "data" => null,
                "message" => "OK",

            );
            $response = json_encode($response);
            return $response;
        }
    }
}
