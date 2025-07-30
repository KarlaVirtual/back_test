<?php


use Backend\dto\Franquicia;
use Backend\mysql\FranquiciaMySqlDAO;

/**
 * Crea un nueva franquicia basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param string $params->Name Nombre de la franquicia
 * @param string|null $params->estado Estado del Franquicia ('A' para activo, 'I' para inactivo). Por defecto, 'A'.
 * @param string|null $params->tipo Tipo de la franquicia.
 * @param string|null $params->abreviado Nombre abreviado de la franquicia.
 * @param string|null $params->imagen url de la imagen de la franquicia.
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 */

/* asigna valores de parámetros y establece un estado predeterminado. */
$Name = $params->Name;
$State = $params->estado;
$Type = $params->tipo22; //Bloqueado hasta generación de un nuevo tipo
$Abreviado = $params->abreviado;
$Imagen = $params->imagen;

if ($State == '') {
    $State = 'A';  //por defecto se establece como activo si no se especifica
}

if (empty($Type)){
    $Type = 'PAYMENT'; //por defecto se establece como tipo pago si no se especifica
}


/* Se crea un objeto 'Franquicia' y se asignan propiedades específicas a él. */
$Franquicia = new Franquicia(); // se instacia la clase Franquicia y se le asignan los valores

$Franquicia->descripcion = $Name;
$Franquicia->estado = $State;
$Franquicia->tipo = $Type;
$Franquicia->abreviado = $Abreviado;
$Franquicia->imagen = $Imagen;
$Franquicia->usucreaId = $_SESSION['usuario'];
$Franquicia->usumodifId = 0;
$Franquicia->verifica = 'I';


/* Se inserta un Franquicia en MySQL y se confirma la transacción con éxito. */
$FranquiciaMySqlDAO = new FranquiciaMySqlDAO();
$FranquiciaMySqlDAO->insert($Franquicia);
$FranquiciaMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";

/* Se inicializan variables para mensajes de alerta y posibles errores del modelo. */
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
