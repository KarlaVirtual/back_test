<?php


/**
 * Report/GetAgentList
 *
 * Obtener la lista de agentes.
 *
 * Este recurso permite recuperar la lista de agentes registrados en el sistema.
 * La respuesta incluye información detallada sobre los agentes y el estado de la consulta.
 *
 * @param no No requiere parámetros de entrada.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío en caso de éxito.
 *  - *pos* (int): Posición actual de la lista de agentes.
 *  - *total_count* (int): Número total de agentes en la lista.
 *  - *data* (array): Contiene la información de los agentes en la lista.
 *  - *Data* (array): Información adicional relacionada con los agentes.
 *
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* establece una respuesta sin errores y con un mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success2";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = 0;

/* Se inicializan variables en un arreglo para almacenar conteos y datos. */
$response["total_count"] = 0;
$response["data"] = array();
$response["Data"] = array();