<?php

/**
 * Este archivo contiene un script para procesar notificaciones de cambio de estado de transacciones
 * de payout con el proveedor Tupay, actualizando estados en la base de datos y registrando logs.
 *
 * @category   API
 * @package    integrations\payout
 * @subpackage Tupay
 * @author     Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @version    1.0
 * @since      2025-03-04
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                 Variable superglobal que contiene variables de entorno.
 * @var mixed $data                 Contiene los datos JSON recibidos en la solicitud.
 * @var mixed $URI                  URI de la solicitud actual.
 * @var mixed $confirm              Objeto decodificado que contiene los datos de la solicitud.
 * @var mixed $id                   Identificador de la transacción en el sistema.
 * @var mixed $TransactionId        Identificador externo de la transacción.
 * @var mixed $status               Estado de la transacción recibido en la solicitud.
 * @var mixed $comment              Comentario asociado al estado de la transacción.
 * @var mixed $estado               Estado mapeado de la transacción en el sistema.
 * @var mixed $TransprodLogMysqlDAO Objeto para manejar operaciones de logs de transacciones en MySQL.
 * @var mixed $TransaccionProducto  Objeto que representa una transacción de producto.
 * @var mixed $Transaction          Objeto de transacción para manejar operaciones en la base de datos.
 * @var mixed $TransprodLog         Objeto que representa un log de transacción.
 * @var mixed $CuentaCobro          Objeto que representa una cuenta de cobro asociada a la transacción.
 * @var mixed $rowsUpdate           Número de filas actualizadas en la base de datos.
 * @var mixed $Usuario              Objeto que representa un usuario asociado a la transacción.
 * @var mixed $UsuarioHistorial     Objeto que representa el historial de un usuario.
 * @var mixed $useragent            Cadena que contiene el agente de usuario de la solicitud.
 * @var mixed $jsonServer           JSON codificado con información del servidor.
 * @var mixed $serverCodif          Información del servidor codificada en base64.
 * @var mixed $ismobile             Indicador de si el dispositivo es móvil.
 * @var mixed $iPod                 , $iPhone, $iPad, $Android, $webOS Variables para detectar dispositivos específicos.
 */

// Habilitar modo debug con clave de seguridad
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
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

// Captura y registro de datos de entrada
$data = file_get_contents('php://input');
$URI = $_SERVER['REQUEST_URI'];

$data = json_decode($data);

if (isset($data)) {
    $confirm = ($data);

    // Extracción de parámetros estándar
    $id = $confirm->requestBody->transactionId;
    $TransactionId = $confirm->requestBody->external_id;
    $status = $confirm->requestBody->statusCode->description;
    $comment = $confirm->requestBody->statusCode->description;

    // Mapeo de estados
    $estado = 'P';

    switch ($status) {
        case "Confirmed":
            $estado = 'A';
            break;
        case "Cancelled":
            $estado = 'R';
            break;
        case "Reversed":
            $estado = 'R';
            break;
        case "Error":
            $estado = 'R';
            break;
    }

    if ($estado != "P") {
        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

        $TransaccionProducto = new TransaccionProducto($TransactionId);

        // Validar si ya fue procesada
        if ($TransaccionProducto->getEstado() == 'I') {
            print_r('OK');
            exit();
        }

        // Iniciar transacción
        $Transaction = $TransprodLogMysqlDAO->getTransaction();

        // Actualizar estado de la transacción
        $TransaccionProducto->setEstado("I");
        $TransaccionProducto->setEstadoProducto($estado);
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);

        // Registrar log de transacción
        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($TransactionId);

        if ($comment != "" && $estado == "R") {
            $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
        } elseif ($estado == "A") {
        } else {
            $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
        }

        $TransprodLog->setEstado($estado);
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setTValue(json_encode($data));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

        // Procesar cuenta de cobro asociada
        $rowsUpdate = 0;
        $CuentaCobro = new CuentaCobro("", $TransactionId);

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

                // Registrar historial
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

                // Detección de dispositivo móvil
                $ismobile = '';

                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                    $ismobile = '1';
                }

                //Detectar dispositivos con condiciones especiales
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

        print_r("OK");
    }
}
