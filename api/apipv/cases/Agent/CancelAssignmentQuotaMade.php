<?php

use Backend\dto\CupoLog;
use Backend\dto\FlujoCaja;
use Backend\dto\PuntoVenta;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioPerfil;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;


/**
 * Cancelación de Quota asignada por el Agente.
 *
 * @param object $params Objeto que contiene los parámetros de entrada, incluyendo:
 * @param string $params->Description Descripción de la operación.
 * @param int $params->Id Identificador del cupo
 *
 *
 * @return void Modifica el parámetro $response para reflejar el resultado de la operación:
 *              - bool $response['HasError'] Indica si hubo un error.
 *              - string $response['AlertType'] Tipo de alerta (success, error, etc.).
 *              - string $response['AlertMessage'] Mensaje de alerta.
 *              - array $response['ModelErrors'] Errores del modelo.
 *
 * @throws Exception Si el perfil del usuario es 'USUONLINE'.
 * @throws Exception Si no tiene saldo para transferir.
 * @throws Exception Si se detecta una operación inusual.
 */

/* obtiene parámetros, valida perfil y crea un objeto de usuario. */
$Description = $params->Description;

// Se obtiene el ID de los parámetros
$Id = $params->Id;

if ($_SESSION['win_perfil'] == 'USUONLINE') throw new Exception('Inusual Detected', '11');

$UsuarioPerfil = new UsuarioPerfil($_SESSION['usuario']);


/* Verificación del perfil del solicitante */
if (!in_array($UsuarioPerfil->getPerfilId(), ['PUNTOVENTA', 'CAJERO']) || $UsuarioPerfil->getUsuarioId() == 7194605) {
    $type = 'S';

    /* Se crea una instancia de la clase UsuarioPerfil utilizando el id del usuario asociado al CupoLog.*/
    $CupoLogP = new CupoLog($Id);
    $UsuarioPerfil2 = new UsuarioPerfil($CupoLogP->usuarioId);

    if ($UsuarioPerfil->getPerfilId() == 'CONCESIONARIO') {


        /**
         * Maneja la creación de un registro de CupoLog y la actualización de balances en PuntoVenta.
         */
        $CupoLog = new CupoLog();

        $CupoLog->setUsuarioId($CupoLogP->getUsuarioId());
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId('E');
        $CupoLog->setValor(-$CupoLogP->getValor());
        $CupoLog->setUsucreaId($UsuarioPerfil->getUsuarioId());
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId('A');
        $CupoLog->setObservacion($Description);

        $CupoLogMySqlDAO = new CupoLogMySqlDAO();
        $transaction = $CupoLogMySqlDAO->getTransaction();

        $cupoId = $CupoLogMySqlDAO->insert($CupoLog);

        $PuntoVenta = new PuntoVenta('', $CupoLogP->getUsuarioId());

        //if($PuntoVenta->getCreditosBase() < $FlujoCajaQuery->getValor()) throw new Exception('No tiene saldo para transferir', 111);

        $PuntoVentaSuper = new PuntoVenta('', $UsuarioPerfil->getUsuarioId());

        $amountPv = $PuntoVentaSuper->setBalanceCreditosBase(-$CupoLog->getValor(), $transaction);
        $amountAg = $PuntoVenta->setBalanceCreditosBase($CupoLog->getValor(), $transaction);

        if ($amountAg == 0) throw new Exception('No tiene saldo para transferir', 111);
        // Crear una nueva instancia de UsuarioHistorial
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        // Crear una nueva instancia del DAO para interactuar con la base de datos
        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
        // Insertar el historial de usuario en la base de datos
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        // Crear otra instancia de UsuarioHistorial para un segundo registro
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPerfil->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor(-$CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        // Crear otra instancia del DAO para interactuar con la base de datos
        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
        // Insertar el nuevo historial de usuario en la base de datos
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        // Crear una instancia de CupoLogMySqlDAO para actualizar el registro de CupoLog
        $CupoLogMySqlDAO2 = new CupoLogMySqlDAO($transaction);

        $CupoLogP->setObservacion($CupoLogP->getObservacion() . ' Cancel');
        $CupoLogMySqlDAO2->update($CupoLogP);

    // Verificar si el perfil del usuario es 'PUNTOVENTA'
    } elseif ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
        // Comprobar si el usuario que está realizando la acción no es el creador del registro
        if ($UsuarioPerfil->usuarioId != $CupoLogP->usucreaId) {
            throw new Exception("Inusual Detected", "11");

        }


        /**
         * Se crea una instancia de la clase CupoLog para registrar un log de cupo.
         */
        $CupoLog = new CupoLog();

        $CupoLog->setUsuarioId($CupoLogP->getUsuarioId());
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId('E');
        $CupoLog->setValor(-$CupoLogP->getValor());
        $CupoLog->setUsucreaId($UsuarioPerfil->getUsuarioId());
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId('A');
        $CupoLog->setObservacion($Description);

        $CupoLogMySqlDAO = new CupoLogMySqlDAO();
        $transaction = $CupoLogMySqlDAO->getTransaction();

        $cupoId = $CupoLogMySqlDAO->insert($CupoLog);

        $PuntoVenta = new PuntoVenta('', $CupoLogP->getUsuarioId());

        //if($PuntoVenta->getCreditosBase() < $FlujoCajaQuery->getValor()) throw new Exception('No tiene saldo para transferir', 111);

        $PuntoVentaSuper = new PuntoVenta('', $UsuarioPerfil->getUsuarioId());

        $amountPv = $PuntoVentaSuper->setBalanceCreditosBase(-$CupoLog->getValor(), $transaction);
        $amountAg = $PuntoVenta->setBalanceCreditosBase($CupoLog->getValor(), $transaction);

        if ($amountAg == 0) throw new Exception('No tiene saldo para transferir', 111);

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        /**
         * Se crea otra instancia de UsuarioHistorial para el usuario del perfil,
         * se establecen sus propiedades y se insertan en la base de datos.
         */
        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($UsuarioPerfil->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor(-$CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

        /**
         * Se crea una instancia de CupoLogMySqlDAO, probablemente para manejar
         * operaciones relacionadas con el objeto $CupoLog.
         */
        $CupoLogMySqlDAO2 = new CupoLogMySqlDAO($transaction);

        $CupoLogP->setObservacion($CupoLogP->getObservacion() . ' Cancel');
        $CupoLogMySqlDAO2->update($CupoLogP);

    } else {

        $CupoLog = new CupoLog();

        // Establece el ID de usuario en el registro de CupoLog.
        $CupoLog->setUsuarioId($CupoLogP->getUsuarioId());
        $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
        $CupoLog->setTipoId('E');
        $CupoLog->setValor(-$CupoLogP->getValor());
        $CupoLog->setUsucreaId($UsuarioPerfil->getUsuarioId());
        $CupoLog->setMandante(0);
        $CupoLog->setTipocupoId('A');
        $CupoLog->setObservacion('');

        $CupoLogMySqlDAO = new CupoLogMySqlDAO();
        $transaction = $CupoLogMySqlDAO->getTransaction();

        // Inserta un nuevo registro de CupoLog en la base de datos.
        $cupoId = $CupoLogMySqlDAO->insert($CupoLog);

        // Crea una nueva instancia de PuntoVenta para el usuario correspondiente.
        $PuntoVenta = new PuntoVenta('', $CupoLogP->getUsuarioId());

        //if($PuntoVenta->getCreditosBase() < $FlujoCajaQuery->getValor()) throw new Exception('No tiene saldo para transferir', 111);

        //$PuntoVentaSuper = new PuntoVenta('', $UsuarioPerfil->getUsuarioId());

        //$amountPv = $PuntoVentaSuper->setBalanceCreditos($FlujoCajaQuery->getValor(), $transaction);
        // Ajusta el saldo de créditos base en la PuntoVenta correspondiente.
        $amountAg = $PuntoVenta->setBalanceCreditosBase($CupoLogP->getValor(), $transaction);

        if ($amountAg == 0) throw new Exception('No tiene saldo para transferir', 111);

        $UsuarioHistorial = new UsuarioHistorial();
        // Establece el ID de usuario en el historial.
        $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(60);
        $UsuarioHistorial->setValor($CupoLog->getValor());
        $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
        // Inserta el registro en el historial de usuario.
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial,'1');

        $CupoLogMySqlDAO2 = new CupoLogMySqlDAO($transaction);

        // Actualiza la observación en el registro de CupoLog.
        $CupoLogP->setObservacion($CupoLogP->getObservacion().' Cancel');
        // Actualiza el registro de CupoLog con la nueva observación.
        $CupoLogMySqlDAO2->update($CupoLogP);

    }
    $transaction->commit();
}


/* establece una respuesta sin errores y un mensaje de éxito en la operación. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = 'Operation has completed successfully';
$response['ModelErrors'] = [];
?>
