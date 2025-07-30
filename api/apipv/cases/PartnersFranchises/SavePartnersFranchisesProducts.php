<?php


use Backend\dto\FranquiciaProducto;
use Backend\dto\Mandanteproducto;
use Backend\mysql\FranquiciaProductoMySqlDAO;


/**
 * Guardar un producto asociado a una Franquicia.
 *
 * Este script permite guardar un producto asociado a una Franquicia, verificando
 * que no existan duplicados en la base de datos y asegurando la integridad de los datos.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params->FranchiseId ID del Franquicia.
 * @param int $params->Product ID del producto.
 * @param int $params->CountrySelect ID del país asociado.
 * @param string $params->IsActivate Estado del producto (A: Activo, I: Inactivo).
 * @param int $params->Partner ID del partner asociado.
 * @param string $params->Imagen URL de la imagne de la franquicia
 * @param string $params->Abreviado nombre abreviado de la franquicia
 *
 * @return array $response Respuesta con los siguientes valores:
 *     - bool $response["HasError"] Indica si hubo un error.
 *     - string $response["AlertType"] Tipo de alerta (success o mensaje de error).
 *     - string $response["AlertMessage"] Mensaje de alerta.
 *     - array $response["ModelErrors"] Errores del modelo (vacío si no hay errores).
 */

// Parametros enviados desde Front

/* valida un socio y activa o desactiva parámetros según ciertas condiciones. */
$FranchiseId = $params->FranchiseId;
$Product = $params->Product;
$CountrySelect = $params->CountrySelect;
$IsActivate = ($params->IsActivate != "A" && $params->IsActivate != "I") ? "" : $params->IsActivate;
$Imagen = $params->Imagen;
$Abreviado = $params->Abreviado;
$Partner = $params->Partner;

if (!in_array($Partner, explode(',', $_SESSION["mandanteLista"]))) {
    throw new Exception("Inusual Detected", "11");
}

// Armamos el insert

/* crea un objeto FranquiciaProducto y establece sus propiedades con datos específicos. */
$FranquiciaProducto = new FranquiciaProducto ();
$FranquiciaProducto->setFranquiciaId($FranchiseId);
$FranquiciaProducto->setProductoId($Product);
$FranquiciaProducto->setUsucreaId($_SESSION['usuario2']);
$FranquiciaProducto->setUsumodifId($_SESSION['usuario2']);
$FranquiciaProducto->setEstado($IsActivate);
$FranquiciaProducto->setImagen($Imagen);
$FranquiciaProducto->setAbreviado($Abreviado);
/* Se establece el país y el socio en FranquiciaProducto y se obtienen transacciones. */
$FranquiciaProducto->setPaisId($CountrySelect);
$FranquiciaProducto->setMandante($Partner);


$FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO();
$Transaction = $FranquiciaProductoMySqlDAO->getTransaction();

//Verificamos que los datos no se dupliquen en la base de datos

$sqlFranquiciaExist = $FranquiciaProductoMySqlDAO->queryByFranquiciaProducto($FranquiciaProducto);

// Si los datos ya existen enviamos un mensaje de error
if (oldCount($sqlFranquiciaExist) > 0) {
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "Ya existe en la base de datos";
    $response["ModelErrors"] = [];
} // Si no existen procedemos a realizar el insert
else {
    /* Inserta datos en la base de datos y confirma la transacción sin errores. */

    $FranquiciaProductoMySqlDAO->insert($FranquiciaProducto);
    $FranquiciaProductoMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}





