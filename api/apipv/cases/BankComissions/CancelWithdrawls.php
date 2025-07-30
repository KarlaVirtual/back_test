<?php

use Backend\dto\CuentaCobro;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\dto\PuntoVenta;
use Backend\dto\CupoLog;

/**
 * Procesa la cancelación de retiros basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param $params ->Id (int): Identificador del retiro a cancelar.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - data (array): Datos adicionales de la respuesta.
 *
 * @throws Exception Si el parámetro $params->Id está vacío o es nulo.
 *                   Código: '21000', Mensaje: 'No se encontro la cuenta de cobro'.
 * @throws Exception Si el estado de la cuenta de cobro no permite la cancelación.
 *                   Código: '21001', Mensaje: 'No se puede realizar la cancelacion'.
 * @throws Exception Si no se puede actualizar la cuenta de cobro en la base de datos.
 *                   Código: '21001', Mensaje: 'No se puede realizar la cancelacion'.
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


/* lee y decodifica datos JSON de una solicitud, extrayendo un ID. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$ConfigurationEnvironment = new ConfigurationEnvironment();


$withdrawlId = $params->Id;


/* Verificación existencia de cuenta cobro */
if ($withdrawlId == "" || $withdrawlId == null) {
    throw new Exception('No se encontro la cuenta de cobro', '21000');
} else {
    $CuentaCobro = new CuentaCobro($withdrawlId);
    if ($CuentaCobro->getEstado() !== 'A') {
        throw new Exception('No se puede realizar la cancelacion', '21001');
    }

    $version = $CuentaCobro->version;

    if ($version == 3) {

        /**
         * Se crea una nueva instancia de Usuario utilizando el ID del usuario asociado a la cuenta de cobro.
         * Se establece el estado de la cuenta de cobro a 'E' (estado de eliminación o cancelación).
         * Se inicializan los IDs de usuario relacionados con el cambio, rechazo y pago a 0.
         * Se verifica si el ID de usuario de pago está vacío y, de ser así, se establece en 0.
         * Se verifica si la fecha de acción está vacía, y si lo está, se establece a la fecha y hora actual.
         * Se verifica si la fecha de cambio está vacía, y si lo está, se establece a la fecha y hora actual.
         * Se establece la fecha de eliminación de la cuenta de cobro igual a la fecha de creación.
         * Se crea una nueva instancia del DAO de CuentaCobro para realizar operaciones en la base de datos.
         * Se intenta actualizar la cuenta de cobro en la base de datos con los cambios realizados.
         * Si no se actualizan filas (es decir, la actualización falla), se lanza una excepción.
         */

        $Usuario = new Usuario($CuentaCobro->getUsuarioId());
        $CuentaCobro->setEstado('E');

        $CuentaCobro->setUsucambioId(0);
        $CuentaCobro->setUsurechazaId(0);
        $CuentaCobro->setUsupagoId(0);

        if ($CuentaCobro->getUsupagoId() == "") {
            $CuentaCobro->setUsupagoId(0);
        }

        if ($CuentaCobro->getFechaAccion() == "") {
            $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
        }

        if ($CuentaCobro->getFechaCambio() == "") {
            $CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
        }
        $CuentaCobro->setFechaEliminacion($CuentaCobro->getFechaCrea());

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado='A' OR  estado='M')");

        if ($rowsUpdate <= 0) throw new Exception('No se puede realizar la cancelacion', '21001');

        $Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
        $UsuarioMySqlDAO->update($Usuario);

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(40);
        $UsuarioHistorial->setValor($CuentaCobro->valor);
        $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

        $CuentaCobroMySqlDAO->getTransaction()->commit();
    } elseif ($version == 4) {

        $Usuario = new Usuario($CuentaCobro->getUsuarioId());
        $CuentaCobro->setEstado('E');


        if ($CuentaCobro->getUsupagoId() == "") {
            $CuentaCobro->setUsupagoId(0);
        }

        if ($CuentaCobro->getFechaAccion() == "") {
            $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
        }

        if ($CuentaCobro->getFechaCambio() == "") {
            $CuentaCobro->setFechaCambio(date("Y-m-d H:i:s"));
        }
        $CuentaCobro->setFechaEliminacion($CuentaCobro->getFechaCrea());


        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $Transaction = $CuentaCobroMySqlDAO->getTransaction();
        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado='A' OR  estado='M')");

        //$CuentaCobroMySqlDAO->getTransaction()->commit();

        if ($rowsUpdate <= 0) throw new Exception('No se puede realizar la cancelacion', '21001');
        /**
         * Se obtienen los valores iniciales para el registro de un cupo.
         */
        $Amount = $CuentaCobro->valor;

        $tipo = 'E'; //Tipo Entrada
        $ClientId = $Usuario->usuarioId;
        $Note = " ";
        $tipoCupo = 'A'; //Apuesta
        $Type = 1;

        /*Registro log vinculado a cupo del usuario*/
        $CupoLog = new CupoLog();
        $CupoLog->setUsuarioId($ClientId);
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId($tipo);
        $CupoLog->setValor($Amount);
        $CupoLog->setUsucreaId(0);
        $CupoLog->setMandante($_SESSION['mandante']);
        $CupoLog->setTipocupoId($tipoCupo);
        $CupoLog->setObservacion($Note);

        $CupoLogMySqlDAO = new CupoLogMySqlDAO($Transaction);

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

        $CupoLogMySqlDAO->insert($CupoLog);

        $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);
        //$cant = $PuntoVenta->setBalanceCupoRecarga($Amount, $Transaction);


        $cant2 = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);
        //$PuntoVentaMySqlDAO->getTransaction()->commit();

        if ($cant2 > 0) {
            /*Seguimiento en usuario historial*/
            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(60);
            $UsuarioHistorial->setValor($CupoLog->getValor());
            $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

            $Transaction->commit();
        }
    }


    $respuestafinal = "";

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $respuestafinal;
    $response["ModelErrors"] = [];
    $response["data"] = [];
}
