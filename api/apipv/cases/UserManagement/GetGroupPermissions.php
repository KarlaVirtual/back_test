<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * UserManagement/GetGroupPermissions
 *
 * Este script obtiene los permisos de un grupo basado en los parámetros de entrada.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * - int $MaxRows Número máximo de filas a recuperar.
 * - string $OrderedItem Elemento ordenado.
 * - string $SkeepRows Número de filas omitidas.
 *
 * @param string $roleId Identificador del rol (obtenido de $_GET).
 * @param string $UserId Identificador del usuario (obtenido de $_GET).
 *
 * @return array $response Respuesta en formato JSON con las siguientes claves:
 * - bool $HasError Indica si ocurrió un error.
 * - string $AlertType Tipo de alerta (e.g., "success").
 * - string $AlertMessage Mensaje de alerta o error.
 * - array $ModelErrors Lista de errores de modelo.
 * - array $Data Datos procesados con las siguientes claves:
 *   - array $IncludedPermission Permisos incluidos.
 *   - string $IncludedPermissionList Lista de permisos incluidos.
 *   - array $ExcludedPermissions Permisos excluidos combinados.
 *   - array $ExcludedPermissions2 Permisos excluidos detallados.
 */


/* Código PHP que inicializa un objeto y obtiene parámetros de la URL. */
$PerfilSubmenu = new PerfilSubmenu();

$Perfil_id = $_GET["roleId"];
$UserId = $_GET["UserId"];
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* Asigna cero a $SkeepRows si está vacío, de lo contrario, mantiene su valor. */
$SkeepRows = $params->SkeepRows;


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000;
}


/* inicializa una variable y obtiene el perfil de un usuario si existe. */
$mismenus = "0";

if ($UserId != "") {
    $UsuarioPerfil = new UsuarioPerfil($UserId);

    $Perfil_id = $UsuarioPerfil->getPerfilId();

}


/* Define reglas de validación para menús y perfiles en un sistema. */
$rules = [];
array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id ", "op" => "eq"));
array_push($rules, array("field" => "menu.menu_id", "data" => "18", "op" => "eq"));

if ($Perfil_id != 'CUSTOM') {
    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => "-1", "op" => "eq"));
}


/* Condicional que agrega reglas basadas en la existencia de un ID de usuario. */
if ($UserId != "") {
    $Usuario = new Usuario($UserId);
    array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$UserId", "op" => "eq"));
    //array_push($rules, array("field" => "perfil_submenu.mandante", "data" => "$Usuario->mandante ", "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => "-1 ", "op" => "eq"));

}


/* Se crea un filtro JSON para recuperar submenús filtrados y ordenados. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

$menus = json_decode($menus);


/* Se inicializan tres arrays vacíos para almacenar menús y submenús. */
$menus3 = [];
$arrayf = [];
$submenus = [];


foreach ($menus->data as $key => $value) {


    /* Código que estructura datos en un arreglo asociativo para menús y submenús. */
    $m = [];
    $m["id"] = $value->{"menu.menu_id"};
    $m["value"] = $value->{"menu.descripcion"};

    $array = [];

    $array["id"] = $value->{"submenu.submenu_id"};

    /* gestiona menús y permisos en una estructura de datos. */
    $array["value"] = $value->{"submenu.descripcion"};

    $mismenus = $mismenus . "," . $array["id"];

    if ($arrayf["id"] != "" && $m["id"] != $arrayf["id"]) {
        $arrayf["Permissions"] = $submenus;
        array_push($menus3, $arrayf);
        // $submenus = [];
    }


    /* asigna valores a un array y lo añade a otro array. */
    $arrayf["id"] = $value->{"menu.menu_id"};
    $arrayf["value"] = $value->{"menu.descripcion"};

    array_push($submenus, $array);
}


if ($Perfil_id != "CUSTOM" && $UserId != "") {

    /* Se define un conjunto de reglas para validar condiciones en un menú. */
    $rules = [];
    array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "$Perfil_id ", "op" => "eq"));
    array_push($rules, array("field" => "menu.menu_id", "data" => "18", "op" => "eq"));

    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => "-1", "op" => "eq"));


    /* Se genera un filtro en JSON y se obtiene un menú personalizado de la base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $menus = json_decode($menus);


    /* Se inicializa un arreglo vacío llamado $arrayf en PHP. */
    $arrayf = [];


    foreach ($menus->data as $key => $value) {


        /* Se asignan valores de menú y submenu a un arreglo. */
        $m = [];
        $m["id"] = $value->{"menu.menu_id"};
        $m["value"] = $value->{"menu.descripcion"};

        $array = [];

        $array["id"] = $value->{"submenu.submenu_id"};

        /* agrega descripciones a menús y verifica permisos en arrays. */
        $array["value"] = $value->{"submenu.descripcion"};

        $mismenus = $mismenus . "," . $array["id"];

        if ($arrayf["id"] != "" && $m["id"] != $arrayf["id"]) {
            $arrayf["Permissions"] = $submenus;
            array_push($menus3, $arrayf);
            // $submenus = [];
        }


        /* Se asignan valores a un array y se agrega a la lista de submenús. */
        $arrayf["id"] = $value->{"menu.menu_id"};
        $arrayf["value"] = $value->{"menu.descripcion"};

        array_push($submenus, $array);
    }


    /* Define reglas de validación para campos específicos en un menú. */
    $rules = [];
    array_push($rules, array("field" => "menu.version", "data" => "2", "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => "CUSTOM ", "op" => "eq"));
    array_push($rules, array("field" => "menu.menu_id", "data" => "18", "op" => "eq"));

    array_push($rules, array("field" => "perfil_submenu.usuario_id", "data" => "$UserId", "op" => "eq"));


    /* Se filtran y obtienen submenús personalizados en formato JSON desde la base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $menus = $PerfilSubmenu->getPerfilSubmenusCustom(" menu.*,submenu.*,perfil_submenu.* ", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $menus = json_decode($menus);


    /* Se inicializa un array vacío llamado $arrayf en PHP. */
    $arrayf = [];


    foreach ($menus->data as $key => $value) {


        /* crea un array con identificadores y descripciones de menú y submenú. */
        $m = [];
        $m["id"] = $value->{"menu.menu_id"};
        $m["value"] = $value->{"menu.descripcion"};

        $array = [];

        $array["id"] = $value->{"submenu.submenu_id"};

        /* asigna descripciones y gestiona permisos de menús en un array. */
        $array["value"] = $value->{"submenu.descripcion"};

        $mismenus = $mismenus . "," . $array["id"];

        if ($arrayf["id"] != "" && $m["id"] != $arrayf["id"]) {
            $arrayf["Permissions"] = $submenus;
            array_push($menus3, $arrayf);
            // $submenus = [];
        }


        /* asigna valores a un array y lo agrega a $submenus. */
        $arrayf["id"] = $value->{"menu.menu_id"};
        $arrayf["value"] = $value->{"menu.descripcion"};

        array_push($submenus, $array);
    }
}


/* Configura permisos y agrega submenús a un arreglo dentro de un objeto 'Submenu'. */
$arrayf["Permissions"] = $submenus;
array_push($menus3, $arrayf);

$IncludedPermission = $submenus;

$Submenu = new Submenu();


/* asigna parámetros y maneja excepciones para filas omitidas. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asignación de valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 100000;
}


/* obtiene menús filtrando mediante reglas en formato JSON y los decodifica. */
$json = '{"rules" : [{"field" : "submenu.version", "data" : "2","op":"eq"},{"field" : "menu.menu_id", "data" : "18","op":"eq"}] ,"groupOp" : "AND"}';

$menus = $Submenu->getSubMenusCustom(" menu.*,submenu.*, CASE WHEN submenu.submenu_id IN (" . $mismenus . ") THEN false ELSE true END mostrar", "menu.menu_id", "asc", $SkeepRows, $MaxRows, $json, true);

$menus = json_decode($menus);

$menus2 = [];

/* Se inicializan cuatro arreglos vacíos para almacenar datos en futuras operaciones. */
$arrayf = [];
$submenus = [];
$children_final = [];
$children_final2 = [];

foreach ($menus->data as $key => $value) {


    /* Se crea un array asociativo con ID y descripción de un menú. */
    $m = [];
    $m["id"] = $value->{"menu.menu_id"};
    $m["value"] = $value->{"menu.descripcion"};

    $array = [];
    $children = [];


    /* Condicional que asigna permisos y agrega elementos a un array si se cumplen criterios. */
    if ($arrayf["id"] != "" && $m["id"] != $arrayf["id"]) {
        $arrayf["Permissions"] = $submenus;
        $arrayf["Children"] = [];

        array_push($menus2, $arrayf);
        $submenus = [];
        $children_final = [];
    }


    /* asigna valores de un menú y sus submenús a arrays. */
    $arrayf["id"] = $value->{"menu.menu_id"};
    $arrayf["value"] = $value->{"menu.descripcion"};

    if ($value->{".mostrar"}) {
        $array["id"] = $value->{"submenu.submenu_id"};
        $array["value"] = $value->{"submenu.descripcion"};
        array_push($submenus, $array);
    }

    /* Asigna valores de un objeto a arrays y los añade a listas finales. */
    $children["id"] = $value->{"submenu.submenu_id"};
    $children["value"] = $value->{"submenu.descripcion"};
    array_push($children_final, $children);
    array_push($children_final2, $children);
}


/* Se crea un arreglo con permisos y se agrega a un menú, sin errores. */
$arrayf["Permissions"] = $submenus;
$arrayf["Children"] = [];
$children_final = [];

array_push($menus2, $arrayf);

$response["HasError"] = false;

/* establece un mensaje de éxito y prepara datos y errores de modelo. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array();


/* asigna listas de permisos incluidos y excluidos a un array de respuesta. */
$response["Data"]["IncludedPermission"] = $IncludedPermission;
$response["Data"]["IncludedPermissionList"] = $mismenus;
$response["Data"]["ExcludedPermissions"] = array_merge($IncludedPermission, $children_final2);
$response["Data"]["ExcludedPermissions2"] = $menus2;
