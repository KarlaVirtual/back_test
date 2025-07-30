<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\UsuarioPerfil;

/**
 * Obtiene los roles de socios y sus permisos asociados.
 *
 * Este script procesa la solicitud para obtener perfiles de socios, incluyendo el conteo
 * de usuarios asociados y permisos de submenús.
 *
 * @param object $params
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param string $params->OrderedItem Campo por el cual ordenar los resultados.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response
 *   - HasError: boolean, indica si ocurrió un error.
 *   - AlertType: string, tipo de alerta (e.g., "success", "error").
 *   - AlertMessage: string, mensaje de alerta.
 *   - ModelErrors: array, lista de errores del modelo.
 *   - Data: array, lista de roles con los siguientes campos:
 *       - Id: int, identificador del perfil.
 *       - Name: string, descripción del perfil.
 *       - UserCount: int, número de usuarios asociados al perfil.
 *       - PermissionCount: int, número de permisos asociados al perfil.
 */

/* Se inicializa un objeto y se configuran parámetros relacionados con filas. */
$Perfil = new Perfil();

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores por defecto a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000;
}


/* obtien perfiles personalizados y los decodifica de formato JSON. */
$json = '{"rules" : [] ,"groupOp" : "AND"}';

$perfiles = $Perfil->getPerfilesCustom(" perfil.* ", "perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json, false);
$perfiles = json_decode($perfiles);

$perfilesfinal = [];

foreach ($perfiles->data as $key => $value) {


    /* Crea un array y un JSON para filtrar usuarios por perfil. */
    $array = [];

    $array["Id"] = $value->{"perfil.perfil_id"};
    $array["Name"] = $value->{"perfil.descripcion"};

    $json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data" : "' . $array["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';


    /* Se obtiene el conteo de perfiles de usuario y se decodifica en formato JSON. */
    $UsuarioPerfil = new UsuarioPerfil();
    $usuarioperfiles = $UsuarioPerfil->getUsuarioPerfilesCustom(" count(*) count ", "usuario_perfil.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $usuarioperfiles = json_decode($usuarioperfiles);
    $array["UserCount"] = $usuarioperfiles->count[0]->{".count"};

    $PerfilSubmenu = new PerfilSubmenu();

    /* Genera una consulta JSON para obtener submenús basados en permisos de perfil. */
    $json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $array["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';
    $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" COUNT(perfil_submenu.perfil_id) count ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $menus = json_decode($menus);
    $array["PermissionCount"] = $menus->count[0]->{".count"};

    array_push($perfilesfinal, $array);
}


/* establece una respuesta sin errores y contiene datos relevantes sobre perfiles. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $perfilesfinal;