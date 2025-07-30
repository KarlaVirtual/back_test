<?php

use Backend\dto\Etiqueta;
use Backend\mysql\EtiquetaMySqlDAO;

/**
 * Tag/AddBulkTags
 *
 * Agregar múltiples etiquetas
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param string $params->Name Nombres de las etiquetas separados por comas.
 * @param string $params->Status Estado de las etiquetas.
 * @param string $params->Description Descripción de las etiquetas.
 * 
 *
 * @return array Respuesta con los siguientes atributos:
 *  - boolean $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success o error).
 *  - string $AlertMessage Mensaje de la alerta.
 *  - array $ModelErrors Errores relacionados con el modelo.
 *
 * @throws Exception Si ocurre un error durante la operación.
 */

try {

    /* Convierte nombres de un parámetro en un array y asigna otros valores a variables. */
    $Names = explode(',', $params->Name); // Convertimos el parámetro Name en un array separado por comas

    $State = $params->Status;
    $Description = $params->Description;


    $response = [];

    /* Se inicializa un array de respuesta sin errores y se crea un DAO. */
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

    $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();


    /* Crea múltiples objetos "Etiqueta" y los inserta en la base de datos. */
    foreach ($Names as $Name) {
        $Etiqueta = new Etiqueta();

        $Etiqueta->setNombre($Name);
        $Etiqueta->setUsumodifId($_SESSION['usuario2']);
        $Etiqueta->setUsucreaId($_SESSION['usuario2']);
        $Etiqueta->setEstado($State);
        $Etiqueta->setDescripcion($Description);
        $Etiqueta->setFechaCrea(date('Y-m-d H:i:s'));
        $Etiqueta->setFechaModif(date('Y-m-d H:i:s'));

        $Etiqueta_id = $EtiquetaMySqlDAO->insert($Etiqueta);
    }


    /* Confirma transacciones en la base de datos utilizando el objeto EtiquetaMySqlDAO. */
    $EtiquetaMySqlDAO->getTransaction()->commit();

} catch (Exception $e) {
    /* Manejo de excepciones que establece respuesta de error en un array. */

    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
