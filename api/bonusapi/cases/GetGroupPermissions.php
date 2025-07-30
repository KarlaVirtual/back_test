<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\PerfilSubmenu;
use Backend\dto\Submenu;

/**
 * Este script obtiene permisos agrupados por roles.
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
 *  - array $Data Datos procesados que incluyen:
 *    - array $IncludedPermission Permisos incluidos.
 *    - array $ExcludedPermissions Permisos excluidos.
 */

/* Se crea un objeto y se obtienen parámetros de una solicitud GET. */
$PerfilSubmenu = new PerfilSubmenu();

$Perfil_id = $_GET["roleId"];
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


/* inicializa variables si están vacías, estableciendo valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y crea una consulta JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000;
}

$mismenus = "0";

$json = '{"rules" : [{"field" : "menu.version", "data" : "2","op":"eq"},{"field" : "perfil_submenu.perfil_id", "data" : "' . $Perfil_id . '","op":"eq"}] ,"groupOp" : "AND"}';


/* Obtiene y decodifica submenús personalizados en formato JSON, almacenándolos en un array. */
$menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

$menus = json_decode($menus);

$menus3 = [];
$arrayf = [];

/* Se inicializa un array vacío llamado $submenus para almacenar submenús. */
$submenus = [];

foreach ($menus->data as $key => $value) {


    /* asigna valores de un objeto a arreglos asociativos en PHP. */
    $m = [];
    $m["Id"] = $value->{"menu.menu_id"};
    $m["Name"] = $value->{"menu.descripcion"};

    $array = [];

    $array["Id"] = $value->{"submenu.submenu_id"};

    /* asigna valores a un array y gestiona permisos de submenús. */
    $array["Name"] = $value->{"submenu.descripcion"};
    $array["IsGiven"] = true;
    $array["Action"] = "view";

    $mismenus = $mismenus . "," . $array["Id"];

    if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
        $arrayf["Permissions"] = $submenus;
        array_push($menus3, $arrayf);
        // $submenus = [];
    }


    /* Se asignan valores de un objeto a un arreglo y se añade a una lista. */
    $arrayf["Id"] = $value->{"menu.menu_id"};
    $arrayf["Name"] = $value->{"menu.descripcion"};

    array_push($submenus, $array);
}


/* Se añade un array de permisos a un menú y se instancia un objeto Submenu. */
$arrayf["Permissions"] = $submenus;
array_push($menus3, $arrayf);

$IncludedPermission = $submenus;

$Submenu = new Submenu();


/* Asigna valores a variables y asegura que SkeepRows tenga un valor predeterminado. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Inicializa $OrderedItem y $MaxRows si están vacíos con valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000;
}


/* procesa reglas de filtrado JSON para obtener submenús personalizados desde la base de datos. */
$json = '{"rules" : [{"field" : "submenu.version", "data" : "2","op":"eq"}] ,"groupOp" : "AND"}';

$menus = $Submenu->getSubMenusCustom(" menu.*,submenu.*, CASE WHEN submenu.submenu_id IN (" . $mismenus . ") THEN false ELSE true END mostrar", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

$menus = json_decode($menus);

$menus2 = [];

/* Se inicializan tres arreglos vacíos en PHP para almacenar datos posteriormente. */
$arrayf = [];
$submenus = [];
$children_final = [];

foreach ($menus->data as $key => $value) {


    /* asigna valores de un objeto a un arreglo multidimensional. */
    $m = [];
    $m["Id"] = $value->{"menu.menu_id"};
    $m["Name"] = $value->{"menu.descripcion"};

    $array = [];
    $children = [];


    /* agrega permisos y inicializa hijos si se cumple una condición específica. */
    if ($arrayf["Id"] != "" && $m["Id"] != $arrayf["Id"]) {
        $arrayf["Permissions"] = $submenus;
        $arrayf["Children"] = [];

        array_push($menus2, $arrayf);
        $submenus = [];
        $children_final = [];
    }


    /* asigna valores de menú y submenu a un array condicionalmente. */
    $arrayf["Id"] = $value->{"menu.menu_id"};
    $arrayf["Name"] = $value->{"menu.descripcion"};

    if ($value->{".mostrar"}) {
        $array["Id"] = $value->{"submenu.submenu_id"};
        $array["Name"] = $value->{"submenu.descripcion"};
        $array["IsGiven"] = true;
        $array["Action"] = "view";
        array_push($submenus, $array);
    }

    /* Asignación de valores a un array y adición a una colección final en PHP. */
    $children["Id"] = $value->{"submenu.submenu_id"};
    $children["Name"] = $value->{"submenu.descripcion"};
    $children["IsGiven"] = true;
    $children["Action"] = "view";
    array_push($children_final, $children);
}


/* asigna permisos, hijos y retorna un estado sin errores. */
$arrayf["Permissions"] = $submenus;
$arrayf["Children"] = [];
$children_final = [];

array_push($menus2, $arrayf);

$response["HasError"] = false;

/* Código que configura respuesta JSON con éxito, mensajes y permisos incluidos. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array();

$response["Data"]["IncludedPermission"] = $IncludedPermission;

/* Asigna las exclusiones de permisos del menú a la respuesta en un arreglo. */
$response["Data"]["ExcludedPermissions"] = $menus2;