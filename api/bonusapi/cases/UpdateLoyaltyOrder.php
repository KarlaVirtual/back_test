<?php

use Backend\dto\LealtadInterna;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\sql\Transaction;


/**
 * Actualiza el orden de lealtad interna y confirma la transacción.
 * 
 * @param object $params Contiene los parámetros de entrada:
 * @param int $params->LoyaltyId ID de la lealtad a actualizar.
 * @param int $params->Order Nuevo orden asignado
 * 
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError: Indica si ocurrió un error.
 * - AlertType: Tipo de alerta (success o error).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Errores del modelo.
 */

/* crea una transacción utilizando un ID de lealtad y usuario actual. */
$loyaltyId = $params->LoyaltyId;
$order = $params->Order;

$Transaction = new Transaction();
$LealtadInterna = new LealtadInterna($loyaltyId);
$LealtadInterna->usumodifId = $_SESSION["usuario"];

/* Actualiza una orden de lealtad interna y confirma la transacción sin errores. */
$LealtadInterna->orden = $order;

$LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO($Transaction);
$LealtadInternaMySqlDAO->update($LealtadInterna);
$Transaction->commit();

$response["HasError"] = false;

/* inicializa una respuesta con tipo de alerta y mensajes vacíos. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
?>