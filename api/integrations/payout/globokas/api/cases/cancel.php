<?php

/**
 * Este archivo contiene la lógica para cancelar pagos en el sistema GLOBOKAS.
 * Realiza validaciones, actualizaciones en la base de datos y operaciones relacionadas con transacciones y cuentas de cobro.
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
 * @var mixed $externoId                   Variable que almacena un identificador externo en Internpay.
 * @var mixed $identifierType              Variable que almacena el tipo de identificador (por ejemplo, número de documento).
 * @var mixed $identifier                  Variable que almacena el identificador único asociado a un usuario o entidad.
 * @var mixed $responseBank                Variable que almacena la respuesta del banco ante una transacción.
 * @var mixed $estado                      Variable que almacena el estado de un proceso o entidad.
 * @var mixed $rules                       Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                      Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonfiltro                  Variable que almacena un filtro en formato JSON.
 * @var mixed $Registro                    Variable que almacena información sobre un registro.
 * @var mixed $datas                       Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $UserId                      Esta variable se utiliza para almacenar y manipular el identificador del usuario.
 * @var mixed $Proveedor                   Esta variable representa la información del proveedor, utilizada para operaciones comerciales o logísticas.
 * @var mixed $Producto                    Variable que almacena información del producto.
 * @var mixed $TransprodLogMysqlDAO        Variable que representa la capa de acceso a datos MySQL para TransprodLog.
 * @var mixed $TransaccionProducto         Variable que almacena información sobre una transacción de producto.
 * @var mixed $Transaction                 Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $transactionid               Variable que almacena el identificador único de una transacción.
 * @var mixed $TransaccionProductoMySqlDAO Variable que representa la capa de acceso a datos MySQL para transacciones de productos.
 * @var mixed $TransprodLog                Variable que almacena registros de transacciones de productos.
 * @var mixed $TransprodLog_id             Variable que almacena el identificador de un registro en TransprodLog.
 * @var mixed $rowsUpdate                  Variable que almacena el número de filas actualizadas en una consulta.
 * @var mixed $CuentaCobro                 Variable que almacena información sobre una cuenta de cobro.
 * @var mixed $GLOBOKAS                    Variable relacionada con el sistema de pagos o procesamiento GLOBOKAS.
 * @var mixed $respon                      Variable que almacena una respuesta de un proceso o servicio.
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
use Backend\dto\Registro;
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
    $externoId = intval($data->paymentOrderLogId);
    $identifierType = $data->identifierType;
    $identifier = $data->identifier;

    $responseBank = "Cancelación de pago";


    $estado = 'R';
    $rules = [];


    $rules = [];
    array_push($rules, array("field" => "registro.cedula", "data" => "$identifier", "op" => "eq"));
    array_push($rules, array("field" => "usuario.pais_id", "data" => "173", "op" => "eq"));
    array_push($rules, array("field" => "registro.estado", "data" => "A", "op" => "eq"));
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $jsonfiltro = json_encode($filtro);
    $Registro = new Registro();
    $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
    $datas = json_decode($datas);


    if ($datas->count[0]->{".count"} > 0) {
        $UserId = ($datas->data[0]->{"registro.usuario_id"});


        $rules = [];

        $Proveedor = new \Backend\dto\Proveedor('', 'GLOBOKASRETIROS');

        $Producto = new \Backend\dto\Producto("", "GlobokasRetiros", $Proveedor->proveedorId);

        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

        $TransaccionProducto = new TransaccionProducto("", $externoId, $Producto->productoId);
        $Transaction = $TransprodLogMysqlDAO->getTransaction();
        $transactionid = $TransaccionProducto->transproductoId;
        $TransaccionProducto->setEstado("I");
        $TransaccionProducto->setEstadoProducto($estado);

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

        $GLOBOKAS = new \Backend\integrations\payout\GLOBOKASSERVICES();

        $respon = $GLOBOKAS->Delete($CuentaCobro);

        if ($CuentaCobro->getEstado() == "I") {
            if ($estado == "R") {
                $CuentaCobro->setEstado('D');
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'I') ");
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
