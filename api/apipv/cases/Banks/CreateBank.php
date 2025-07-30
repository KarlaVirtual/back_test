<?php

use Backend\dto\Banco;
use Backend\mysql\BancoMySqlDAO;
/**
 * Crea un nuevo banco basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param string $params->Name Nombre del banco.
 * @param string|null $params->State Estado del banco ('A' para activo, 'I' para inactivo). Por defecto, 'A'.
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 */

/* asigna valores de parámetros y establece un estado predeterminado. */
$Name = $params->Name;  // se recibe el nombre del banco
$State = $params->State; //se recibe el estado del banco


if ($State == '') {
    $State = 'A';  //por defecto se establece como activo si no se especifica
}


/* Se crea un objeto 'Banco' y se asignan propiedades específicas a él. */
$Banco = new Banco(); // se instacia la clase banco y se le asignan los valores

$Banco->descripcion = $Name;
$Banco->paisId = 0;
$Banco->estado = $State;
$Banco->productoPago = 0;


/* Se inserta un banco en MySQL y se confirma la transacción con éxito. */
$BancoMySqlDAO = new BancoMySqlDAO();
$BancoMySqlDAO->insert($Banco);
$BancoMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";

/* Se inicializan variables para mensajes de alerta y posibles errores del modelo. */
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
