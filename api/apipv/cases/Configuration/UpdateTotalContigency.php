<?php

use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\mysql\MandanteDetalleMySqlDAO;


/**
 * Actualiza el estado de una contingencia total en función de los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Action Acción que determina el tipo de contingencia a procesar.
 * @param string $params->IsActivate Estado de activación ('A' para activar, otro valor para desactivar).
 *
 * @return array $response Arreglo que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' o 'error').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo, si los hay.
 *
 * @throws Exception Si ocurre un error durante la actualización o inserción de datos.
 */

/* Se define una función para obtener diferentes tipos de contingencias según un valor. */
$Action = $params->Action;
$IsActivate = $params->IsActivate;

function getContingency($value)
{
    $data = [
        'Contingency' => 'TOTALCONTINGENCE',
        'ContingencySports' => 'TOTALCONTINGENCESPORT',
        'ContingencyWithdrawal' => 'TOTALCONTINGENCEWITHDRAWAL',
        'ContingencyDeposit' => 'TOTALCONTINGENCEDEPOSIT',
        'ContingencyCasino' => 'TOTALCONTINGENCECASINO',
        'ContingencyCasinoVivo' => 'TOTALCONTINGENCECASINOLIVE',
        'ContingencyVirtuales' => 'TOTALCONTINGENCEVIRTUAL',
        'ContingencyPoker' => 'TOTALCONTINGENCEPOKER'
    ];

        /* asigna valores y evalúa condiciones basadas en la variable $IsActivate. */
    return $data[$value] ?: '';
}

$type = getContingency($Action);
$isError = false;
$IsActivate = $IsActivate === 'A' ? 1 : 0;


/* gestiona un clasificador y actualiza un estado basado en condiciones. */
try {
    $Clasificador = new Clasificador('', $type);
    $MandanteDetalle = new MandanteDetalle('', -1, $Clasificador->clasificadorId, 0, 'A');

    if ($MandanteDetalle->getValor() != $IsActivate) {
        $MandanteDetalle->setEstado('I');

        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
        $MandanteDetalleMySqlDAO->update($MandanteDetalle);
        $MandanteDetalleMySqlDAO->getTransaction()->commit();

        throw new Exception('New item', 34);
    }
} catch (Exception $ex) {
    /* Manejo de errores según el código del mismo */

    if ($ex->getCode() == 34) {
        $MandanteDetalle = new MandanteDetalle();
        $MandanteDetalle->setMandante(-1);
        $MandanteDetalle->setTipo($Clasificador->clasificadorId);
        $MandanteDetalle->setValor($IsActivate);
        $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
        $MandanteDetalle->setUsumodifId(0);
        $MandanteDetalle->setPaisId(0);
        $MandanteDetalle->setEstado('A');

        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
        $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        $MandanteDetalleMySqlDAO->getTransaction()->commit();

    } else $isError = true;
}

/*Formato de respuesta*/
$response['HasError'] = $isError ? true : false;
$response['AlertType'] = $isError ? 'error' : 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
?>