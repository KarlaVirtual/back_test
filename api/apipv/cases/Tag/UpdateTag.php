<?php


use Backend\dto\CategoriaProducto;

use Backend\dto\Etiqueta;
use Backend\dto\GeneralLog;

use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;

use Backend\dto\Proveedor;

use Backend\dto\ProductoMandante;

use Backend\dto\Subproveedor;

use Backend\mysql\CategoriaProductoMySqlDAO;

use Backend\mysql\EtiquetaMySqlDAO;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\ProductoDetalleMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;

/**
 * Actualizar detalles de una etiqueta.
 *
 * Este script permite modificar los datos de una etiqueta existente en la base de datos.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params->Id Identificador de la etiqueta.
 * @param string $params->Name Nombre de la etiqueta.
 * @param string $params->State Estado de la etiqueta ('A' para activo, 'I' para inactivo).
 * @param string $params->Description Descripción de la etiqueta.
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success' o 'error').
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error durante la actualización o la transacción.
 */

try {


    /* Se asignan parámetros a variables y se crea un objeto de la clase Etiqueta. */
    $Id = $params->Id;
    $Name = $params->Name;
    $State = $params->State;;
    $Description = $params->Description;


    $Etiqueta = new Etiqueta($Id);


    /* Configura propiedades de un objeto "Etiqueta" y crea una instancia de "EtiquetaMySqlDAO". */
    $Etiqueta->setNombre($Name);
    $Etiqueta->setUsumodifId($_SESSION['usuario2']);
    $Etiqueta->setUsucreaId($Etiqueta->usucreaId);
    $Etiqueta->setEstado($State);
    $Etiqueta->setDescripcion($Description);


    $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();


    /* Se actualiza una etiqueta en la base de datos y se confirma la transacción. */
    $Etiqueta_id = $EtiquetaMySqlDAO->update($Etiqueta);
    $EtiquetaMySqlDAO->getTransaction()->commit();


    $response = [];
    $response['HasError'] = false;

    /* inicializa una respuesta con éxito, sin mensajes ni errores. */
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];


} catch (Exception $e) {
    /* Maneja excepciones, generando un respuesta de error en formato estructurado. */


    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

}

