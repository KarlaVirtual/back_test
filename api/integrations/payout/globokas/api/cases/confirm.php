<?php

/**
 * Este archivo contiene un script para procesar confirmaciones de pagos y actualizar el estado de transacciones
 * en el sistema. Incluye la interacción con varias capas de acceso a datos y la gestión de estados de transacciones
 * y cuentas de cobro.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payout\Globokas
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log                         Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $data                        Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $ConfigurationEnvironment    Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                     Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $clave                       Esta variable guarda la clave o contraseña para autenticación y acceso seguro al sistema (generalmente encriptada).
 * @var mixed $transactionid               Variable que almacena el identificador único de una transacción.
 * @var mixed $paymentOrderLogId           Variable que almacena el identificador del registro de la orden de pago.
 * @var mixed $status                      Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $responseBank                Variable que almacena la respuesta del banco ante una transacción.
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
 * @var mixed $response                    Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;


/* Obtenemos Variables que nos llegan */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$data = json_decode((file_get_contents('php://input')));


$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'VirtualSoft';
    $clave = 'cDMycmuNvIC%';
} else {
    $usuario = 'VirtualSoft';
    $clave = 'ODHoEcG%SMSF';
}

if (isset($data)) {
    $transactionid = $data->invoiceNumber;
    $paymentOrderLogId = $data->paymentOrderLogId;
    $status = $data->status;
    $responseBank = $data->description;


    $estado = 'P';

    switch ($status) {
        case "Confirmado":
            $estado = 'A';
            break;
    }

    if ($estado != "P") {
        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

        $TransaccionProducto = new TransaccionProducto($transactionid);
        $Transaction = $TransprodLogMysqlDAO->getTransaction();

        $TransaccionProducto->setEstado("I");
        $TransaccionProducto->setEstadoProducto($estado);
        $TransaccionProducto->setExternoId($paymentOrderLogId);
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transactionid);
        $TransprodLog->setEstado($estado);
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario($responseBank);
        $TransprodLog->setTValue(json_encode($data));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

        $rowsUpdate = 0;
        $CuentaCobro = new CuentaCobro("", $transactionid);

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
        }
        $response = array(
            "success" => true,
            "data" => null,
            "message" => "OK",

        );
    }
}
