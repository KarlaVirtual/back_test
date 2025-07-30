<?php

use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\UsuarioPerfil;

/**
 * Este script procesa una solicitud HTTP para obtener información de un rol por su ID.
 * 
 * @param object $params Objeto JSON decodificado que contiene:
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param string $params->OrderedItem Campo por el cual ordenar los resultados.
 * @param int $params->SkeepRows Número de filas a omitir.
 * 
 * @return array $response Respuesta estructurada que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ("success", "error", etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de modelo.
 *  - Data (array): Información del rol, incluyendo:
 *      - Id (int): ID del rol.
 *      - Name (string): Nombre del rol.
 *      - UserCount (int): Número de usuarios asociados al rol.
 *      - PermissionCount (int): Número de permisos asociados al rol.
 */



/* crea un objeto "Perfil" y obtiene parámetros de consulta y configuración. */
$Perfil = new Perfil();

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$Perfil_id = $_GET["id"];


/* inicializa variables a valores predeterminados si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado y obtiene perfiles según condiciones específicas en formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 100000;
}

$json = '{"rules" : [{"field" : "perfil.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';

$perfiles = $Perfil->getPerfilesCustom(" perfil.* ", "perfil.perfil_id", "asc", $SkeepRows, $MaxRows, $json, true);

/* decodifica JSON, extrae el primer perfil y lo almacena en un nuevo array. */
$perfiles = json_decode($perfiles);
$perfiles = $perfiles->data[0];

$perfilesfinal = [];

$perfilesfinal["Id"] = $perfiles->{"perfil.perfil_id"};

/* Se asigna una descripción de perfil y se consultan perfiles de usuario. */
$perfilesfinal["Name"] = $perfiles->{"perfil.descripcion"};

$json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data" : "' . $perfilesfinal["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';

$UsuarioPerfil = new UsuarioPerfil();
$usuarioperfiles = $UsuarioPerfil->getUsuarioPerfilesCustom(" count(*) count ", "usuario_perfil.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

/* procesa datos JSON para contar elementos de submenús de perfil. */
$usuarioperfiles = json_decode($usuarioperfiles);
$perfilesfinal["UserCount"] = $usuarioperfiles->count[0]->{".count"};

$PerfilSubmenu = new PerfilSubmenu();
$json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $perfilesfinal["Id"] . '","op":"eq"}] ,"groupOp" : "AND"}';
$menus = $PerfilSubmenu->getPerfilSubmenusCustom(" COUNT(perfil_submenu.perfil_id) count ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

/* Se decodifica un JSON y se establece un conteo de permisos en $response. */
$menus = json_decode($menus);
$perfilesfinal["PermissionCount"] = $menus->count[0]->{".count"};

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Se inicializa un arreglo de errores y se asignan datos a la respuesta. */
$response["ModelErrors"] = [];

$response["Data"] = $perfilesfinal;