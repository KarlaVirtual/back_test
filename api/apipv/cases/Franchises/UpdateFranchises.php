<?php

use Backend\dto\Franquicia;
use Backend\mysql\FranquiciaMySqlDAO;

/**
 * Actualiza la información de un Franquicia basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params->Id Identificador del Franquicia a actualizar.
 * @param string $params->State Estado del Franquicia ('A' para activo, 'I' para inactivo).
 * @param int $params->imagen Link de la imagen asociada a la Franquicia.
 * @param string $params->Name Descripción de la Franquicia
 * @param string $params->abreviado Abreviado de la Franquicia
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 */

/* Se reciben parámetros para crear un objeto Franquicia con ID y detalles asociados. */
$Id = $params->Id; // se recibe el id del Franquicia

$State = $params->State; // se recibe el estado del Franquicia
$Imagen = $params->Imagen; // se recibe el link de la imagen
$Name = $params->Name; // se recibe el link de la imagen
$abreviado = $params->Abreviado; // se recibe el link de la imagen

if ($Id !=""){
    $Franquicia = new Franquicia($Id); // se realiza la isntancia a la tabla Franquicia y se le asignan las propiedades

    /* asigna valores a un objeto y obtiene una transacción de la base de datos. */
    if ($State != ""){
        $Franquicia->estado = $State;
    }
    if ($Imagen != ""){
        $Franquicia->imagen = $Imagen;
    }
    if ($Name != ""){
        $Franquicia->descripcion = $Name;
    }
    if ($abreviado != ""){
        $Franquicia->abreviado = $abreviado;
    }
    $Franquicia->setUsumodifId($_SESSION['usuario2']);

    $FranquiciaMySqlDAO = new FranquiciaMySqlDAO();
    $Transaction = $FranquiciaMySqlDAO->getTransaction();

    /* Actualiza información del Franquicia, confirma transacción y prepara respuesta exitosa. */
    $FranquiciaMySqlDAO->update($Franquicia);
    $FranquiciaMySqlDAO->getTransaction()->commit(); // se deja la transaccion y se hace un commit para guardar la nueva informacion del Franquicia
}


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Inicializa un arreglo vacío para almacenar errores del modelo en la respuesta. */
$response["ModelErrors"] = [];