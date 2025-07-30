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
 * Tag/AddTag
 * Tag/AddTag
 *
 * Agregar una nueva etiqueta
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param string $params->Name Nombre de la etiqueta.
 * @param string $params->Status Estado de la etiqueta.
 * @param string $params->Description Descripción de la etiqueta.
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


    /* Se asignan parámetros a variables y se crea una nueva instancia de "Etiqueta". */
    $Name = $params->Name;
    $State = $params->Status;;
    $Description = $params->Description;


    //error_reporting(E_ALL);
    //ini_set('display_errors', 'ON');

    $Etiqueta = new Etiqueta();


    /* asigna valores a un objeto "Etiqueta" utilizando datos de sesión y otros parámetros. */
    $Etiqueta->setNombre($Name);
    $Etiqueta->setUsumodifId($_SESSION['usuario2']);
    $Etiqueta->setUsucreaId($_SESSION['usuario2']);
    $Etiqueta->setEstado($State);
    $Etiqueta->setDescripcion($Description);
    $Etiqueta->setFechaCrea(date('Y-m-d H:i:s'));

    /* Se actualiza la fecha de modificación y se inserta una nueva etiqueta en MySQL. */
    $Etiqueta->setFechaModif(date('Y-m-d H:i:s'));


    $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();
    $Etiqueta_id = $EtiquetaMySqlDAO->insert($Etiqueta);
    $EtiquetaMySqlDAO->getTransaction()->commit();


    /* Código inicializa una respuesta con atributos de éxito y sin errores. */
    $response = [];
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];


} catch (Exception $e) {
    /* Manejo de excepciones en PHP, configurando respuesta de error estrutura. */


    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

}

