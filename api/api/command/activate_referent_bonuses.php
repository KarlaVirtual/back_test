<?php

use Backend\sql\Transaction;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\PaisMandante;
use Backend\dto\BonoInterno;
use Backend\dto\BonoDetalle;
use Backend\dto\LogroReferido;
use Backend\mysql\LogroReferidoMySqlDAO;

/**
 * Activa los bonos de referidos para un usuario.
 *
 * @param object $json Objeto JSON que contiene los siguientes parámetros:
 * @param int ReferredId ID del usuario referido.
 * @param int Award ID del premio.
 * @param int ChoicedBonus ID del bono elegido.
 * @param object $session Objeto de sesión que contiene información del usuario en sesión.
 *                      - usuario (int): ID del usuario en sesión.
 * 
 * @return array $response Respuesta con los siguientes valores:
 *                         - code (int): Código de éxito o error.
 *                         - data (array): Contiene:
 *                           - AlertMessage (string): Mensaje de alerta.
 *                           - IdBonus (int): ID del bono redimido.
 * 
 * @throws Exception Si ocurre un error en la validación o redención del bono:
 *                   - Código 4018: Error al redimir el bono.
 */

$params = $json->params; // Se obtienen los parámetros del JSON
$referredId = $params->ReferredId; // Se obtiene el ID del referente
$awardId = $params->Award; // Se obtiene el ID del premio
$bonoId = $params->ChoicedBonus; // Se obtiene el ID del bono elegido

$Transaction = new Transaction(); // Se instancia la transacción
$BonoInterno = new BonoInterno(); // Se instancia el bono interno
// Instanciando usuario referente
$UsuarioMandante = new UsuarioMandante($json->session->usuario); // Se instancia el usuario mandante
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante()); // Se obtiene el usuario relacionado con el mandante
$UsuarioOtraInfo = new UsuarioOtraInfo($UsuarioMandante->getUsuarioMandante()); // Se instancia información adicional del usuario

// Validando programa de referidos activo
$PaisMandate = new PaisMandante('', $UsuarioMandante->getMandante(), $UsuarioMandante->getPaisId()); // Se instancia el país del mandante
$PaisMandate->progReferidosDisponible(); // Se verifica si el programa de referidos está disponible

// Validando que el referente que solicita la redención del bono esté avalado
$UsuarioOtraInfo->validarReferenteAvalado(); // Se valida que el referente esté avalado

// Verificando y actualizando logros utilizados para la solicitud
$LogroReferido = new LogroReferido(); // Se instancia el logro referido
$LogroReferido->redimirLogros($Transaction, $awardId, $referredId, $bonoId); // Se redimen los logros

// Verificando validez del bono utilizado
$rules = []; // Se inicializa el arreglo de reglas
$select = 'bono_interno.bono_id, bono_interno.estado, bono_interno.tipo, bono_detalle.tipo, bono_detalle.valor'; // Se definen los campos a seleccionar
array_push($rules, ['field' => 'bono_interno.bono_id', 'data' => $bonoId, 'op' => 'eq']); // Se agrega regla para el ID del bono
array_push($rules, ['field' => 'bono_interno.estado', 'data' => 'A', 'op' => 'eq']); // Se agrega regla para estado activo del bono
array_push($rules, ['field' => 'bono_detalle.tipo', 'data' => 'BONOREFERENTE', 'op' => 'eq']); // Se agrega regla para tipo de bono
array_push($rules, ['field' => 'bono_detalle.valor', 'data' => 1, 'op' => 'eq']); // Se agrega regla para valor del bono
$filters = ['rules' => $rules, 'groupOp' => 'AND']; // Se agrupan las reglas
$BonoDetalle = new BonoDetalle(); // Se instancia el detalle del bono
$choicedBonusData = $BonoDetalle->getBonoDetallesCustom($select, 'bono_interno.bono_id', 'ASC', 0, 1, json_encode($filters), true); // Se obtienen los detalles del bono elegido
$choicedBonusData = json_decode($choicedBonusData)->data[0]; // Se decodifica el resultado a formato objeto

/** Redimiendo bono */
$allowedBonusTypes = [6,8];
if (in_array($choicedBonusData->{'bono_interno.tipo'}, $allowedBonusTypes)) {
//Recuperando ubicación del usuario
    $sql = "select ciudad.ciudad_id, ciudad.depto_id from usuario inner join registro on (usuario.usuario_id = registro.usuario_id) inner join ciudad on (registro.ciudad_id = ciudad.ciudad_id) where usuario.usuario_id = " . $Usuario->getUsuarioId();
    $userLocationInfo = $BonoInterno->execQuery($Transaction, $sql);

//Asignando bono
    $detalles = (object)[];
    $detalles->Depositos = 0; // Cantidad de depósitos realizados
    $detalles->DepositoEfectivo = false; //Si se hizo un pago en efectivo
    $detalles->MetodoPago = false; //Depósito fue mediante una pasarela de pago
    $detalles->ValorDeposito = 0; //Máximo valor de depósito aceptado
    $detalles->PaisPV = null; //El país del punto de venta
    $detalles->DepartamentoPV = null; //El departamento del punto de venta
    $detalles->CiudadPV = null; //La ciudad del punto de venta
    $detalles->PaisUSER = $Usuario->paisId; //El país del usuario
    $detalles->DepartamentoUSER = $userLocationInfo[0]->{'ciudad.depto_id'} ?? null; //El departamento del usuario
    $detalles->CiudadUSER = $userLocationInfo[0]->{'ciudad.ciudad_id'} ?? null; //La ciudad del usuario
    $detalles->MonedaUSER = $Usuario->moneda; //La moneda del usuario
    $detalles->PuntoVenta = null; //El ID del punto de venta
    $detalles->ReferidoId = $referredId; //El ID del referido beneficiario

    // Llama a la función que agrega el bono
    $redemptionResult = $BonoInterno->agregarBonoFree($choicedBonusData->{'bono_interno.bono_id'}, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, '', $Transaction);

    // Revisando resultado de la asignación
    if (!$redemptionResult->WinBonus) {
        // Bono NO redimido
        // $Transaction->rollback();
        if ($_GET['test'] == 1) print_r($redemptionResult);
        throw new Exception('Error, por favor comuniquese con soporte', 4018);
    }

    $Transaction->commit();

    $response["code"] = 0; // Código de éxito
    $response["data"]["AlertMessage"] = "Bono redimido exitosamente"; // Mensaje de alerta
    $response["data"]["IdBonus"] = $redemptionResult->Bono; // ID del bono redimido
}
?>
