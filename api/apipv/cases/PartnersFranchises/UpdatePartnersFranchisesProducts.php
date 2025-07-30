<?php


use Backend\dto\FranquiciaProducto;
use Backend\mysql\FranquiciaProductoMySqlDAO;

/**
 * Actualizar el estado de un producto asociado a una Franquicia.
 *
 * Este script permite actualizar el estado de un producto asociado a una Franquicia
 * en una transacción SQL, asegurando la integridad de los datos.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params->Id ID del producto asociado al Franquicia.
 * @param string $params->IsActivate Estado del producto (A: Activo, I: Inactivo).
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *     - bool $response["HasError"] Indica si hubo un error.
 *     - string $response["AlertType"] Tipo de alerta (success o mensaje de error).
 *     - string $response["AlertMessage"] Mensaje de alerta.
 *     - array $response["ModelErrors"] Errores del modelo (vacío si no hay errores).
 */

/* Asignación del valor de 'Id' desde el objeto '$params' a la variable '$Id'. */
$Id = $params->Id;
$abreviado = $params->Abreviado;
$Imagen = $params->Imagen;

try {


    /* actualiza el estado de un objeto FranquiciaProducto en una transacción SQL. */
    if ($Id != "") {
        $FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO();
        $Transaction = $FranquiciaProductoMySqlDAO->getTransaction();

        $IsActivate = ($params->IsActivate != "A" && $params->IsActivate != "I") ? "" : $params->IsActivate;


        $FranquiciaProducto = new FranquiciaProducto($Id);

        if ($IsActivate != "") {
            $FranquiciaProducto->setEstado($IsActivate);
        }
        if ($abreviado != ""){
            $FranquiciaProducto->abreviado = $abreviado;
        }
        if ($Imagen != ""){
            $FranquiciaProducto->imagen = $Imagen;
        }

        $FranquiciaProducto->setUsumodifId($_SESSION['usuario2']);
        $FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO($Transaction);
        $FranquiciaProductoMySqlDAO->update($FranquiciaProducto);

        $Transaction->commit();
    }
} catch (Exception $e) {
    /* Captura excepciones en PHP sin mostrar su contenido. */

    //print_r($e);
}


/* Establece una respuesta sin errores, indicando éxito y sin mensajes de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

