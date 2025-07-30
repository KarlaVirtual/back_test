<?php

use Backend\dto\Etiqueta;
use Backend\dto\EtiquetaProducto;
use Backend\mysql\EtiquetaProductoMySqlDAO;
use Backend\mysql\EtiquetaMySqlDAO;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\mysql\TagoMandanteMySqlDAO;


/**
 * Maneja la actualización o inserción de etiquetas de productos en la base de datos.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->ProductId ID del producto.
 * @param string $params->TagId ID de la etiqueta.
 * @param string $params->ExcludedTagList Lista de etiquetas excluidas (separadas por comas).
 * @param string $params->IncludedTagList Lista de etiquetas incluidas (separadas por comas).
 * 
 * @return array $response Respuesta JSON con los siguientes valores:
 *                         - hasError: Indica si hubo un error (true/false).
 *                         - AlertType: Tipo de alerta (success/error).
 *                         - AlertMessage: Mensaje de alerta (puede ser null).
 *                         - ModelErrors: Lista de errores del modelo (puede ser vacía).
 *                         - Data: Datos adicionales (puede ser vacío).
 * @throws Exception Si ocurre un error al actualizar o insertar etiquetas.
 */

/*Inicializa variables para gestionar productos y etiquetas, incluyendo listas de etiquetas excluidas e incluidas.*/
$insertOrUpdate = false;
$Product = $params->ProductId;
$TagId = $params->TagId;
$ExcludedTagList = ($params->ExcludedTagList != "") ? explode(",", $params->ExcludedTagList) : array();
$IncludedTagList = ($params->IncludedTagList != "") ? explode(",", $params->IncludedTagList) : array();

if ($Product !== '') {

    /*Maneja etiquetas excluidas, actualiza su estado y confirma la transacción si es necesario.*/
    if (count($ExcludedTagList) > 0) {
        $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
        $Transaction = $EtiquetaProductoMySqlDAO->getTransaction();

        foreach ($ExcludedTagList as $key => $value) {

            try {
                $EtiquetaProducto = new EtiquetaProducto($value, $Product);
                $EtiquetaProducto->estado = "I";
                $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
                $EtiquetaProductoMySqlDAO->update($EtiquetaProducto);
                $insertOrUpdate = true;
            } catch (Exception $e) {
            }
        }

        $Transaction->commit();
    }


    if (count($IncludedTagList) > 0) {
        $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
        $Transaction = $EtiquetaProductoMySqlDAO->getTransaction();

        foreach ($IncludedTagList as $value) {
            /*Intenta actualizar la etiqueta del producto, si falla, maneja la excepción.*/
            try {
                $EtiquetaProducto = new EtiquetaProducto($value, $Product);
                // deberia aca de no entrar y asi irse por el else
                $EtiquetaProducto->estado = "A";

                $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
                $EtiquetaProductoMySqlDAO->update($EtiquetaProducto);
                $insertOrUpdate = true;
            } catch (Exception $ex) {
                /*Maneja excepciones al actualizar o insertar etiquetas de productos en la base de datos.*/
                if ($ex->getCode() == "27") {
                    // por este lado deberia entrar al estar sacando la excepcion del controlador 
                    $EtiquetaProducto = new EtiquetaProducto();
                    $EtiquetaProducto->etiquetaId = $value;
                    $EtiquetaProducto->productoId = $Product;
                    $EtiquetaProducto->estado = "A";
                    $EtiquetaProducto->usucreaId = $_SESSION['usuario'];
                    $EtiquetaProducto->usumodifId = $_SESSION['usuario'];
                    $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
                    $EtiquetaProductoMySqlDAO->insert($EtiquetaProducto);
                    $EtiquetaProductoMySqlDAO->getTransaction()->commit();
                    $insertOrUpdate = true;
                }
            }
        }

        if ($insertOrUpdate) $Transaction->commit();
    }


    /*Maneja la respuesta JSON según el estado de la operación de actualización o inserción.*/
    if ($insertOrUpdate) {
        $response["hasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = null;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        $response["HasError"] = true;
        $reponse["AlertType"] = "success";
        $response["alertMessage"] = null;
        $response["ModelsError"] = [];
    }
}
