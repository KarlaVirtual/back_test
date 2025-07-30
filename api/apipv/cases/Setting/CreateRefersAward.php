<?php

use Backend\sql\Transaction;
use Backend\dto\Usuario;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMandante;


/**
 * Crea un nuevo premio de referidos y valida los parámetros de entrada.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->Partner Socio de referencia.
 * @param string $params->Country País asociado al socio.
 * 
 * 
 * @return void Modifica el array $response con los siguientes valores:
 *              - HasError: boolean, indica si ocurrió un error.
 *              - AlertType: string, tipo de alerta ('error', 'danger', 'success').
 *              - AlertMessage: string, mensaje de alerta.
 *              - ModelErrors: array, lista de errores del modelo.
 * @throws Exception Si ocurre un error durante la transacción.
 */

/* Valida que los parámetros "partner" y "country" no estén vacíos antes de continuar. */
$partner = $params->Partner;
$country = $params->Country;

if (empty($country) || $partner == '' || $partner == null) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "Seleccione un pais y partner de referencia";
    $response["ModelErrors"] = [];
    return;
}

//Verificando existencia y estado del PaisMandate

/* verifica si un socio está inactivo en un país específico. */
$PaisMandante = new PaisMandante('', $partner, $country);
if ($PaisMandante->estado == 'I') {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Partner inactivo en el país seleccionado";
    $response["ModelErrors"] = [];
}

//Creando nuevo premio (Y programa de referidos en su defecto)

/* Se crea un nuevo premio para referidos y se confirma la transacción. */
$Transaction = new Transaction();
$Usuario = new Usuario($_SESSION['usuario']);
$MandanteDetalle = new MandanteDetalle();

if ($MandanteDetalle->createNuevoPremioReferidos($Transaction, $partner, $country, $Usuario)) {
    $Transaction->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}



