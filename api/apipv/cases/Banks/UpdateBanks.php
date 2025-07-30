<?php

use Backend\dto\Banco;
use Backend\mysql\BancoMySqlDAO;

/**
 * Actualiza la información de un banco basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params->Id Identificador del banco a actualizar.
 * @param string $params->State Estado del banco ('A' para activo, 'I' para inactivo).
 * @param int $params->Country Identificador del país asociado al banco.
 * @param int $params->paidProduct Identificador del producto de pago asociado al banco.
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 */

/* Se reciben parámetros para crear un objeto Banco con ID y detalles asociados. */
$Id = $params->Id; // se recibe el id del banco

$State = $params->State; // se recibe el estado del banco
$Country = $params->Country; // se recibe el pais del banco
$PaidProduct = $params->paidProduct; // se recibe el productoId


$Banco = new Banco($Id); // se realiza la isntancia a la tabla banco y se le asignan las propiedades

/* asigna valores a un objeto y obtiene una transacción de la base de datos. */
$Banco->paisId = $Country;
$Banco->estado = $State;
$Banco->productoPago = $PaidProduct;

$BancoMySqlDAO = new BancoMySqlDAO();
$Transaction = $BancoMySqlDAO->getTransaction();

/* Actualiza información del banco, confirma transacción y prepara respuesta exitosa. */
$BancoMySqlDAO->update($Banco);
$BancoMySqlDAO->getTransaction()->commit(); // se deja la transaccion y se hace un commit para guardar la nueva informacion del banco

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Inicializa un arreglo vacío para almacenar errores del modelo en la respuesta. */
$response["ModelErrors"] = [];