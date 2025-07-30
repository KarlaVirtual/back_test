<?php

use Backend\dto\UsuarioBanco;
use Backend\dto\SorteoInterno;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;

use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * bonusapi/cases/ChangeStateLottery
 *
 * Actualizar estado de un Sorteo
 *
 * Este recurso permite actualizar el estado de un Sorteo, modificando su estado según el valor proporcionado.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador del Sorteo a actualizar.
 *   - *State* (string): Nuevo estado del Sorteo.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generada ("success" o "error").
 *  - *AlertMessage* (string): Mensaje de alerta generado por el proceso.
 *  - *ModelErrors* (array): Retorna array vacío si no hay errores en el modelo.
 *  - *data* (array): Retorna un array vacío.
 *
 * Ejemplo de respuesta en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "error",
 *  - *AlertMessage* => "[Mensaje de error]",
 *  - *ModelErrors* => array(),
 *
 * @throws Exception Si ocurre un error en la actualización del estado del Sorteo.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* recibe datos JSON, los decodifica y crea un objeto SorteoInterno. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $params->Id;
$State = $params->State;


$SorteoInterno = new SorteoInterno($Id);

/* Actualiza el estado de SorteoInterno y confirma la transacción sin errores. */
$SorteoInterno->estado = $State;

$SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();
$SorteoInternoMySqlDAO->update($SorteoInterno);
$SorteoInternoMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;

/* Código PHP que inicializa una respuesta con tipo de alerta, mensaje y errores. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = [];

?>