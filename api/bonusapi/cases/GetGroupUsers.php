<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioPerfil;

/**
 * Este script obtiene usuarios agrupados por roles.
 * 
 * @param int $roleId ID del rol recibido a través de $_GET.
 * @param int $MaxRows Número máximo de filas a devolver.
 * @param int $OrderedItem Elemento por el cual ordenar.
 * @param int $SkeepRows Número de filas a omitir.
 * 
 * @return array $response Respuesta estructurada que incluye:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (e.g., "success").
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo, si los hay.
 *  - array $Data Lista de usuarios con:
 *    - int $Id ID del usuario.
 *    - string $Name Nombre del usuario.
 *    - int $Role ID del rol del usuario.
 *    - bool $IsGiven Indica si el rol está asignado.
 */

/* Se crea un nuevo perfil de usuario y se obtienen parámetros de la solicitud. */
$UsuarioPerfil = new UsuarioPerfil();

$Perfil_id = $_GET["roleId"];
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


/* inicializa variables si están vacías, asignando valores por defecto. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor por defecto y crea un objeto JSON vacío. */
if ($MaxRows == "") {
    $MaxRows = 100000000;
}

$mismenus = "0";

$json = '{"rules" : [] ,"groupOp" : "AND"}';


/* obtiene perfiles de usuario, procesando datos y verificando roles específicos. */
$usuarios = $UsuarioPerfil->getUsuarioPerfilesCustom(" usuario.usuario_id,usuario.nombre,usuario_perfil.perfil_id ", "usuario_perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json, false);

$usuarios = json_decode($usuarios);
$arrayf = [];

foreach ($usuarios->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Name"] = $value->{"usuario.nombre"};
    $array["Role"] = $value->{"usuario_perfil.perfil_id"};

    if ($array["Role"] === $Perfil_id) {
        $array["IsGiven"] = true;

    } else {
        $array["IsGiven"] = false;

    }

    array_push($arrayf, $array);
}


/* define una respuesta sin errores, con tipo y mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $arrayf;