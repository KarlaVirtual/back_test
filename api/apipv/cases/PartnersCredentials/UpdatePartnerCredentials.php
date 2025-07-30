<?php

use Backend\dto\SubproveedorMandantePais;

/**
 * Actualizar credenciales de un subproveedor.
 *
 * Este script permite actualizar las credenciales de un subproveedor asociado a un mandante.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->Id ID del subproveedor.
 * @param string $params->Credentials Credenciales codificadas en base64.
 *
 * @return array $response Respuesta con los siguientes índices:
 *  - HasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error durante la actualización.
 */

/* establece credenciales y usuario para un objeto Subproveedor. */
$Id = $params->Id;
$credentials = !empty($params->Credentials) ? json_encode(json_decode(base64_decode($params->Credentials))) : null;

$subProveedor = new SubproveedorMandantePais($Id);
$subProveedor->setCredentials($credentials);
$subProveedor->setUsumodifId($_SESSION["usuario"]);

/* Actualiza las credenciales y establece una respuesta de éxito sin errores. */
$subProveedor->updateCredencialesSubproveedoresMandantePais();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
