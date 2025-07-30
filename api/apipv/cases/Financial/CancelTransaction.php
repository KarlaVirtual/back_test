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
 * @param int $Id : Descripción: Identificador único de la transacción a cancelar.
 *
 * @Description Este recurso permite cancelar una transacción en el sistema, actualizando los registros correspondientes
 * y ajustando los saldos de los usuarios y puntos de venta involucrados.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 *
 * Objeto en caso de error
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'error';
 * $response['AlertMessage'] = 'An error occurred during the operation';
 * $response['ModelErrors'] = [];
 */


/* Obtención ID objetivo a cancelar*/
$Id = $params->Id;

if ($_SESSION['win_perfil'] == 'USUONLINE') throw new Exception('Inusual Detected', '11');


/* Obtención perfil del solicitante */
$UsuarioPerfil = new UsuarioPerfil($_SESSION['usuario']);

if (in_array($UsuarioPerfil->getPerfilId(), ['PUNTOVENTA', 'CAJERO', 'SA'])) {
    $type = 'S';
    $FlujoCajaQuery = new FlujoCaja($Id);

    $FlujoCajaQuery->setDevolucion('S');

    $CupoLog = new CupoLog($FlujoCajaQuery->getcupologId());

    $CupoLog->setUsuarioId($CupoLog->getUsuarioId());
    $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
    $CupoLog->setTipoId('E');
    $CupoLog->setValor(-$FlujoCajaQuery->getValor());
    $CupoLog->setUsucreaId($UsuarioPerfil->getUsuarioId());
    $CupoLog->setMandante(0);
    $CupoLog->setTipocupoId('A');
    $CupoLog->setObservacion('');

    $CupoLogMySqlDAO = new CupoLogMySqlDAO();
    $transaction = $CupoLogMySqlDAO->getTransaction();

    $cupoId = $CupoLogMySqlDAO->insert($CupoLog);

    $PuntoVenta = new PuntoVenta('', $FlujoCajaQuery->getUsucreaId());

    //if($PuntoVenta->getCreditosBase() < $FlujoCajaQuery->getValor()) throw new Exception('No tiene saldo para transferir', 111);

    //$PuntoVentaSuper = new PuntoVenta('', $UsuarioPerfil->getUsuarioId());

    //$amountPv = $PuntoVentaSuper->setBalanceCreditos($FlujoCajaQuery->getValor(), $transaction);
    $amountAg = $PuntoVenta->setBalanceCreditosBase($FlujoCajaQuery->getValor(), $transaction);

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

    $FlujoCaja = new FlujoCaja();

    $FlujoCaja->setFechaCrea(date('Y-m-d'));
    $FlujoCaja->setHoraCrea(date('H:i'));
    $FlujoCaja->setUsucreaId($CupoLog->getUsucreaId());
    $FlujoCaja->setTipomovId('E');
    $FlujoCaja->setValor($CupoLog->getValor());
    $FlujoCaja->setTicketId('');
    $FlujoCaja->setRecargaId(0);
    $FlujoCaja->setMandante(0);
    $FlujoCaja->setTraslado('N');
    $FlujoCaja->setFormapago1Id(1);
    $FlujoCaja->setCuentaId(0);
    $FlujoCaja->setDevolucion('S');

    $FlujoCaja->setFormapago2Id($CupoLog->getUsuarioId());
    $FlujoCaja->setValorForma1(0);
    $FlujoCaja->setValorForma2(0);
    $FlujoCaja->setCuentaId(0);
    $FlujoCaja->setPorcenIva(0);
    $FlujoCaja->setValorIva(0);

    $FlujoCaja->setcupologId($cupoId);

    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($transaction);

    $FlujoCajaMySqlDAO->insert($FlujoCaja);

    $FlujoCajaMySqlDAO->update($FlujoCajaQuery);

    $transaction->commit();
}


/* Código asigna un valor de éxito a una respuesta de operación sin errores. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = 'Operation has completed successfully';
$response['ModelErrors'] = [];
?>
