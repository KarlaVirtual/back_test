<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;

/**
 * Este script maneja la obtención de usuarios con paginación y ordenamiento.
 * 
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param int $params->OrderedItem Elemento por el cual se ordenarán los resultados.
 * @param int $params->SkeepRows Número de filas a omitir.
 * 
 * @return array $response Arreglo que contiene:
 * - HasError: Indica si ocurrió un error (true/false).
 * - AlertType: Tipo de alerta (success/danger).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Lista de errores del modelo.
 * - Data: Datos de usuarios obtenidos, incluyendo objetos y conteo.
 */

/* crea un objeto Usuario y decodifica parámetros JSON de una solicitud. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);

$MaxRows = $params->MaxRows;

/* asigna valores de parámetros y establece un valor predeterminado si es vacío. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías: $OrderedItem y $MaxRows. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}


/* Se obtienen usuarios, se decodifican en JSON y se preparan para uso. */
$usuarios = $Usuario->getUsuarios("A", "", "a.usuario_id", "asc", $SkeepRows, $MaxRows);

$usuarios = json_decode($usuarios);

$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {


    /* crea un arreglo con datos de usuario y su estado. */
    $array = [];

    $array["Id"] = $value->{"a.usuario_id"};
    $array["Ip"] = $value->{"a.dir_ip"};
    $array["Login"] = $value->{"a.login"};
    $array["Estado"] = array($value->{"a.estado"});

    /* Asigna valores de un objeto a un array asociativo en PHP. */
    $array["EstadoEspecial"] = $value->{"a.estado_esp"};
    $array["PermiteRecargas"] = $value->{".permite_recarga"};
    $array["ImprimeRecibo"] = $value->{".recibo_caja"};
    $array["Pais"] = $value->{"a.pais_id"};
    $array["Idioma"] = $value->{"a.idioma"};
    $array["Nombre"] = $value->{"a.nombre"};

    /* Asigna valores a un array asociativo a partir de un objeto. */
    $array["FirstName"] = $value->{"a.nombre"};
    $array["TipoUsuario"] = $value->{"e.perfil_id"};
    $array["Intentos"] = $value->{"a.intentos"};
    $array["Observaciones"] = $value->{"a.observ"};
    $array["PinAgent"] = $value->{".pinagent"};
    $array["BloqueoVentas"] = $value->{"a.bloqueo_ventas"};

    /* Asigna valores a un array usando datos provenientes de un objeto. */
    $array["Moneda"] = $value->{"a.moneda"};
    $array["ActivarRecarga"] = $value->{"a.permite_activareg"};
    $array["City"] = $value->{"g.ciudad_nom"};
    $array["Phone"] = $value->{"f.telefono"};
    $array["FechaCrea"] = $value->{"a.fecha_crea"};
    $array["LastLoginLocalDate"] = $value->{"a.fecha_crea"};

    /* asigna valores a un array y lo agrega a una lista. */
    $array["FechaCrea"] = $value->{".fecha_ult"};
    $array["IsLocked"] = false;

    array_push($usuariosFinal, $array);

}


/* configura una respuesta JSON sin errores, incluyendo datos de usuarios. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "Objects" => $usuariosFinal,
    "Count" => $usuarios->count[0]->{".count"},

);