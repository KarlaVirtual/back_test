<?php

use Backend\dto\PaisMandante;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioOtrainfo;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\dto\UsuarioMandante;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioRecarga;
use Backend\dto\Usuario;

/**
 * command/validate_referent
 *
 * Validación de condiciones para referente avalado
 *
 * Este recurso verifica si un usuario cumple con las condiciones para ser considerado un "referente avalado",
 * basándose en ciertos requisitos predefinidos. Las condiciones incluyen un depósito mínimo y una verificación de la cuenta.
 * Si el usuario cumple con todas las condiciones, se actualiza su estado como "referente avalado" en la base de datos.
 * Si no cumple con alguna condición, se devuelve un mensaje con las condiciones pendientes y su progreso.
 *
 * @param object $json : Objeto JSON recibido con los parámetros de la solicitud.
 * @param object $json ->session->usuario : Usuario que realiza la solicitud.
 * @param string $json ->params->code : Código de verificación del usuario.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de éxito o error de la operación.
 *  - *data* (array): Datos adicionales de la respuesta.
 *    - *AlertMessage* (string): Mensaje que indica si el usuario ha sido verificado o no.
 *    - *ReferentConditions* (array): Lista de condiciones que el usuario debe cumplir para ser un referente avalado.
 *    - *ProgressPercentage* (int): Porcentaje de avance hacia el cumplimiento del depósito mínimo.
 *    - *IsVerified* (int): 1 si el usuario está verificado, 0 si no lo está.
 *
 * @throws Exception Si las condiciones de referente no se encuentran o si ocurre un error inesperado.
 * @throws Exception Si el tipo/abreviado NO existe en mandante_detalle
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Valida el usuario referente y obtiene información relacionada a su país y mandante. */
$params = $json->params;

/** Validando si usuario es un referente avalado */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioOtrainfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());
$PaisMandante = new PaisMandante('', $UsuarioMandante->getMandante(), $UsuarioMandante->getPaisId());

/* Consulta la disponibilidad de programas y verifica si el usuario está avalado. */
$PaisMandante->progReferidosDisponible();
if ($UsuarioOtrainfo->getReferenteAvalado()) {
    $response["code"] = 0;
    $response["data"]["AlertMessage"] = 'Usuario verificado';
    $response["data"]['ReferentConditions'] = [];
    return;
}

//Conjunto de condiciones que debe cumplir el referente donde cada llave es el abreviado correspondiente y el valor por defecto es nulo

/* Definición de condiciones de referencia y estructura para almacenar valores correspondientes. */
$referentConditions = [
    'CONDMINDEPOSITREFERENT' => null,
    'CONDVERIFIEDREFERENT' => null
];
//Array llave => valor de cada condición(Abreviado) y su valor en mandante_detalle
$targetValues = [];


/** Solicitando las condiciones que rigen en el país mandante */
foreach ($referentConditions as $condition => $status) {
    $Clasificador = new Clasificador('', $condition);

    try {
        $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
        $targetValues[$condition] = $MandanteDetalle->valor;
        //Cada condición en referentConditions es falsa hasta que se demuestre su cumplimiento, nula si NO aplica para dicho programa de referidos y true una vez se verifique su cumplimiento
        $referentConditions[$condition] = false;
    } catch (Exception $e) {
        //Si el tipo/abreviado NO existe en mandante_detalle para el programa de referidos evaluado la excepción se controla, en caso de ser otro tipo de excepción esta es lanzada
        if ($e->getCode() != 34) throw $e;
    }
}

/*Verificación condiciones del referente requeridas*/
if (count($targetValues) == 0) throw new Exception('Condiciones de referente no encontradas', 4010);


/** Validando, cada condición por cumplir corresponde a un bloque if*/
$minDepositPercentageAdvance = 0;

if ($referentConditions['CONDMINDEPOSITREFERENT'] === false) {
    //Validando que referente cumpla el mínimo de depósito
    $UsuarioRecargaResumen = new UsuarioRecargaResumen();
    $UsuarioRecarga = new UsuarioRecarga();
    $totalDeposit = 0;

    //Consultando depósitos del día en vigencia
    $currentDateStart = date('Y-m-d 00:00:01');
    $currentDateEnd = date('Y-m-d 23:59:59');

    $rules = [];
    array_push($rules, ['field' => 'usuario_recarga.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_recarga.usuario_id', 'data' => $UsuarioMandante->getUsuarioMandante(), 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_recarga.fecha_crea', 'data' => $currentDateStart, 'op' => 'ge']);
    array_push($rules, ['field' => 'usuario_recarga.fecha_crea', 'data' => $currentDateEnd, 'op' => 'le']);

    $groupBy = 'usuario_recarga.usuario_id';
    $select = 'usuario_recarga.usuario_id, sum(usuario_recarga.valor) as depositado';
    $filters = ['rules' => $rules, 'groupOp' => 'AND'];

    $currentDeposit = $UsuarioRecarga->getUsuarioRecargasCustom($select, 'usuario_recarga.usuario_id', 'DESC', 0, 1, json_encode($filters), true, $groupBy);
    $currentDeposit = json_decode($currentDeposit)->data[0];

    $totalDeposit += (int)$currentDeposit->{'.depositado'};

    for ($limit = 20; $limit <= 500; $limit += 20) {
        /*Obtención depoósitos del referente*/
        $rules = [];
        array_push($rules, ['field' => 'usuario_recarga_resumen.usuario_id', 'data' => $UsuarioMandante->getUsuarioMandante(), 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_recarga_resumen.estado', 'data' => 'A', 'op' => 'eq']);
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $depositHistory = $UsuarioRecargaResumen->getUsuarioRecargaResumenCustom('usuario_recarga_resumen.valor', 'usuario_recarga_resumen.valor', 'desc', ($limit - 20), 20, json_encode($filters), true);
        $depositHistory = json_decode($depositHistory);

        $totalDeposit += array_reduce($depositHistory->data, function ($carry, $item) {
            return $carry += $item->{'usuario_recarga_resumen.valor'};
        }, 0);

        //Definiendo porcentaje de progreso
        $minDepositPercentageAdvance = round(($totalDeposit * 100) / $targetValues['CONDMINDEPOSITREFERENT'], 0);
        $minDepositPercentageAdvance = $minDepositPercentageAdvance >= 100 ? 100 : $minDepositPercentageAdvance;

        if ($totalDeposit >= $targetValues['CONDMINDEPOSITREFERENT'] && $targetValues['CONDMINDEPOSITREFERENT'] > 0) {
            $referentConditions['CONDMINDEPOSITREFERENT'] = true;
            break;
        } elseif ($limit > $depositHistory->count[0]->{'0'}) {
            $referentConditions['CONDMINDEPOSITREFERENT'] = false;
            break;
        }
    }
}


/* Validación de las condiciones del referente */
if ($referentConditions['CONDVERIFIEDREFERENT'] === false) {
    //Validando que referente cumpla cuenta verificada
    $Clasificador = new Clasificador('', 'CONDVERIFIEDREFERENT');
    $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');

    if (!$MandanteDetalle->getValor()) $referentConditions['CONDVERIFIEDREFERENT'] = null;
    else {
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        if ($Usuario->verifcedulaAnt == 'S' && $Usuario->verifcedulaPost == 'S') $referentConditions['CONDVERIFIEDREFERENT'] = true;
    }
}


/** Verificación final referentConditions, si alguna llave tiene valor false NO se otorga el estado de referente_avalado */

/* se refiere a la fecha límite de entrenamiento de un modelo. */
$failuredConditions = array_filter($referentConditions, function ($condition) {
    return $condition === false;
});


/* Conteo de condiciones fallidas*/
if (count($failuredConditions) == 0) {
    //Avalando usuario
    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();
    $UsuarioOtrainfo->setReferenteAvalado(1);
    $UsuarioOtrainfoMySqlDAO->update($UsuarioOtrainfo);
    $UsuarioOtrainfoMySqlDAO->getTransaction()->commit();
}


/*Generación formato de referente avalado y registro en base de datos*/
try {
    $UsuarioOtrainfo->validarReferenteAvalado();
    $response["code"] = 0;
    $response["data"]["AlertMessage"] = 'Usuario verificado';
    $response["data"]['ReferentConditions'] = [];
} catch (Exception $e) {
    /*Manejo de errores*/

    if ($e->getCode() != 4009) throw $e;
    $conditions = [];
    //Listando y enviando condiciones para ser un referente avalado
    if ($failuredConditions['CONDMINDEPOSITREFERENT'] !== null || count($failuredConditions) > 0) {
        array_push($conditions, ['Condition' => 'MinDeposit', 'TargetValue' => $targetValues['CONDMINDEPOSITREFERENT'] . ' ' . $UsuarioMandante->moneda, 'Description' => 'Meta minima de deposito']);
    }


    $response["code"] = 1;
    $response["data"]["AlertMessage"] = 'Usuario no avalado';
    $response["data"]["ReferentConditions"] = $conditions;
    $response["data"]["ProgressPercentage"] = $minDepositPercentageAdvance;
    if ($referentConditions['CONDVERIFIEDREFERENT'] !== null) $response["data"]["IsVerified"] = (int)!$referentConditions['CONDVERIFIEDREFERENT'];
}
?>