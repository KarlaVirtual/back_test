<?php

use Backend\dto\Usuario;


/**
 * Este script gestiona la obtención de usuarios según reglas específicas.
 * 
 * @param object $params Contiene los parámetros de entrada en formato JSON:
 * @param int $params->MaxRows Número máximo de filas a devolver (por defecto 1000).
 * @param int $params->OrderedItem Orden de los elementos en la consulta (por defecto 1).
 * @param int $params->SkeepRows Número de filas a omitir en la consulta (por defecto 0).
 * 
 * @return array $response Respuesta estructurada que incluye:
 * - HasError: Indica si ocurrió un error (boolean).
 * - AlertType: Tipo de alerta (string).
 * - AlertMessage: Mensaje de la operación (string).
 * - ModelErrors: Lista de errores de modelo (array).
 * - Data: Datos de los usuarios obtenidos, incluyendo objetos y conteo (array).
 */

/* Código PHP que crea un objeto Usuario y decodifica parámetros JSON de la entrada. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);

$MaxRows = $params->MaxRows;

/* Inicializa $SkeepRows a 0 si está vacío en la entrada de parámetros. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}

/* Código PHP para filtrar y obtener usuarios según reglas específicas en formato JSON. */
$json = '{"rules" : [{"field" : "usuario_perfil.perfil_id", "data" : "USUONLINE","op":"eq"}] ,"groupOp" : "AND"}';

$usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.* ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

$usuarios = json_decode($usuarios);

$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {


    /* Se crea un array asociativo con datos del usuario y su estado. */
    $array = [];

    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Ip"] = $value->{"ausuario.dir_ip"};
    $array["Login"] = $value->{"usuario.login"};
    $array["Estado"] = array($value->{"usuario.estado"});

    /* Asigna valores a un arreglo a partir de propiedades de un objeto. */
    $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
    $array["Idioma"] = $value->{"a.idioma"};
    $array["Nombre"] = $value->{"a.nombre"};
    $array["FirstName"] = $value->{"registro.nombre1"};
    $array["MiddleName"] = $value->{"registro.nombre2"};
    $array["LastName"] = $value->{"registro.apellido1"};

    /* asigna valores de un objeto a un array asociativo en PHP. */
    $array["Email"] = $value->{"registro.email"};
    $array["Address"] = $value->{"registro.direccion"};
    $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
    $array["Intentos"] = $value->{"usuario.intentos"};
    $array["Observaciones"] = $value->{"usuario.observ"};
    $array["Moneda"] = $value->{"usuario.moneda"};


    /* Asignación de valores a un arreglo con datos de usuario y estado de bloqueo. */
    $array["Pais"] = $value->{"usuario.pais_id"};
    $array["City"] = $value->{"g.ciudad_nom"};

    $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};

    $array["IsLocked"] = false;

    /* Extrae información de la variable `$value` y la asigna a un array asociativo. */
    $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
    $array["BirthDate"] = $value->{"c.fecha_nacim"};

    $array["BirthDepartment"] = $value->{"g.depto_id"};
    $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
    $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};


    /* Asigna valores a un array a partir de propiedades de un objeto. */
    $array["CurrencyId"] = $value->{"usuario.moneda"};
    $array["DocNumber"] = $value->{"registro.cedula"};
    $array["Gender"] = $value->{"registro.sexo"};
    $array["Language"] = $value->{"usuario.idioma"};
    $array["Phone"] = $value->{"registro.telefono"};
    $array["MobilePhone"] = $value->{"registro.celular"};

    /* Asignación de valores a un array a partir de propiedades de un objeto. */
    $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
    $array["Province"] = $value->{"registro.ciudad_id"};
    $array["RegionId"] = $value->{"usuario.pais_id"};
    $array["CountryName"] = $value->{"usuario.pais_id"};
    $array["ZipCode"] = $value->{"registro.codigo_postal"};
    $array["IsVerified"] = true;

    /* Agrega el contenido de `$array` al final del arreglo `$usuariosFinal`. */
    array_push($usuariosFinal, $array);

}


/* Se prepara una respuesta exitosa con datos de usuarios y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "Objects" => $usuariosFinal,
    "Count" => $usuarios->count[0]->{".count"},

);