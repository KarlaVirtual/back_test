<?php

use Backend\dto\SorteoInterno2;
use Backend\mysql\SorteoInterno2MySqlDAO;

/**
 * bonusapi/cases/ChangeStateLotteryBetshop
 *
 * Actualizar estado de un SorteoInterno2
 *
 * Este recurso permite actualizar el estado de un SorteoInterno2 utilizando su identificador y el nuevo estado.
 *
 * @param object $params : Objeto que contiene los parámetros de entrada.
 *   - *Id* (int): Identificador del SorteoInterno2 a actualizar.
 *   - *State* (string): Nuevo estado del SorteoInterno2.
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
 * @throws Exception Si ocurre un error en la actualización del estado del SorteoInterno2.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* recibe y decodifica datos JSON desde la entrada estándar en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$id = $params->Id;
$state = $params->State;


/* Se actualiza el estado de SorteoInterno2 y se gestiona la transacción en MySQL. */
try {

    $sorteoInterno2 = new SorteoInterno2($id);

    $sorteoInterno2->setState($state);

    $sorteoInterno2MySqlDAO = new SorteoInterno2MySqlDAO();
    $transaccion = $sorteoInterno2MySqlDAO->getTransaction();
    $sorteoInterno2MySqlDAO->update($sorteoInterno2);

    $sorteoInterno2MySqlDAO->getTransaction()->commit();


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["data"] = [];

} catch (\Exception $e) {
    /* Manejo de excepciones que asigna valores a la respuesta en caso de error. */

    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["data"] = [];
}   

