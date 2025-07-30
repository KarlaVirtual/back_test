<?php

use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');

/**
 * Función para obtener las comisiones de cuentas bancarias de los usuarios.
 *
 * @param int $UserId : Descripción: Identificador único del usuario.
 * @param string $Account : Descripción: Número de cuenta bancaria.
 * @param int $Bank : Descripción: Identificador del banco.
 * @param string $InterbankCode : Descripción: Código interbancario.
 * @param int $TypeAccount : Descripción: Tipo de cuenta (0 para Ahorros, 1 para Corriente).
 * @param int $State : Descripción: Estado de la cuenta (0 para Activo, 1 para Inactivo).
 * @param int $OrderedItem : Descripción: Ítem ordenado.
 * @param int $SkeepRows : Descripción: Número de filas a omitir en la consulta.
 * @param int $MaxRows : Descripción: Número máximo de filas a devolver en la consulta.
 *
 * @Description Este recurso permite obtener las comisiones de cuentas bancarias de los usuarios en el sistema, filtrando por diferentes criterios como identificador de usuario, número de cuenta, banco, código interbancario, tipo de cuenta y estado de la cuenta.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 * - *pos* (int): Posición de inicio de los datos devueltos.
 * - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 * - *data* (array): Datos de las comisiones de cuentas bancarias.
 *
 * Objeto en caso de error:
 *
 * $response['HasError'] = true;
 * $response['AlertType'] = 'danger';
 * $response['AlertMessage'] = 'Invalid';
 * $response['ModelErrors'] = [];
 *
 * @throws Exception Permiso denegado
 * @throws Exception Inusual Detected
 * @throws Exception No tiene saldo para transferir
 *
 */
/**
 * Obtiene los parámetros de la petición y los configura para su uso.
 *
 * Se obtienen datos de entrada mediante 'php://input' y se decodifican en formato JSON.
 * También se recopilan parámetros de la solicitud, se gestionan las sesiones y se
 * inicializan objetos relacionados con el usuario.
 */


/* recibe datos JSON y extrae parámetros de solicitud en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$UserId = $_REQUEST["UserId"];
$Account = $_REQUEST["Account"];

/* obtiene datos de una solicitud HTTP para asignarlos a variables. */
$Bank = $_REQUEST["Bank"];
$InterbankCode = $_REQUEST["InterbankCode"];
$TypeAccount = $_REQUEST["TypeAccount"];
$State = $_REQUEST["State"];
$OrderedItem = $_REQUEST["OrderedItem"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* obtiene el conteo de filas y crea objetos de usuario si hay un ID. */
$MaxRows = $_REQUEST["count"];


if ($UserId != "") {

    $UsuarioMandante = new UsuarioMandante("", $UserId, $_SESSION["mandante"]);
    $Usuario = new Usuario ($UserId);


} elseif ($_SESSION["win_perfil"] == "ADMIN" || $_SESSION["win_perfil"] == "ADMIN2" || $_SESSION["win_perfil"] == "SA") {
    /* Verifica si el perfil de usuario es ADMIN, ADMIN2 o SA para ejecutar código. */


} else {
    /* Crea un objeto UsuarioMandante y luego un objeto Usuario a partir de él. */

    $UsuarioMandante = new UsuarioMandante("", $_SESSION["usuario"], $_SESSION["mandante"]);
    $Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

}


/* asigna un ID de usuario y establece un valor predeterminado para SkeepRows. */
$agentId = $Usuario->usuarioId;


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías, asignando valores por defecto. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Construye reglas para validar usuarios según su perfil y agente. */
$rules = [];

/*if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
}*/

/*if ($UserId != "") {

    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UserId, "op" => "eq"));

}elseif ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "ADMIN" || $_SESSION["win_perfil2"] == "ADMIN2") {

    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
}*/

if ($agentId != "") {

    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $agentId, "op" => "eq"));

}


/* Agrega reglas a un arreglo basado en condiciones de cuenta y banco. */
if ($Account != "") {
    array_push($rules, array("field" => "usuario_banco.cuenta", "data" => $Account, "op" => "eq"));
}

if ($Bank != "") {
    array_push($rules, array("field" => "banco.banco_id", "data" => $Bank, "op" => "eq"));
}


/* Agrega reglas para validar código y tipo de cuenta si no están vacíos. */
if ($InterbankCode != "") {
    array_push($rules, array("field" => "usuario_banco.codigo", "data" => $InterbankCode, "op" => "eq"));

}

if ($TypeAccount != "") {
    array_push($rules, array("field" => "usuario_banco.tipo_cuenta", "data" => $TypeAccount, "op" => "eq"));
}


/* Agrega condiciones a un arreglo según el estado del usuario bancario. */
if ($State == '0') {
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
}

if ($State == '1') {
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "I", "op" => "eq"));
}


/* crea un filtro JSON para recuperar datos específicos de usuarios y bancos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$UsuarioBanco = new UsuarioBanco();

$configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario.nombre,usuario_banco.*,banco.* ", "usuario_banco.estado", "asc", $SkeepRows, $MaxRows, $json2, true);


/* convierte una cadena JSON en un objeto PHP y inicializa un array vacío. */
$configuraciones = json_decode($configuraciones);

$configuracionesData = array();

foreach ($configuraciones->data as $key => $value) {


    /* Se crea un array asociativo con datos de usuario y banco desde un objeto. */
    $arraybanco = array();
    $arraybanco["UserName"] = ($value->{"usuario.nombre"});
    $arraybanco["Id"] = ($value->{"usuario_banco.usubanco_id"});
    $arraybanco["Account"] = ($value->{"usuario_banco.cuenta"});
    $arraybanco["InterbankCode"] = ($value->{"usuario_banco.codigo"});
    $arraybanco["TypeAccount"] = $value->{"usuario_banco.tipo_cuenta"};

    /* Asigna descripciones de banco y tipos de cuenta en un arreglo. */
    $arraybanco["Bank"] = $value->{"banco.descripcion"};

    switch ($arraybanco["TypeAccount"]) {
        case "0":
            $arraybanco["TypeAccount"] = "Ahorros";
            break;

        case "1":
            $arraybanco["TypeAccount"] = "Corriente";

            break;
    }


    /* asigna un tipo de cliente basado en un valor específico. */
    $arraybanco["ClientType"] = $value->{"usuario_banco.tipo_cliente"};

    switch ($arraybanco["ClientType"]) {
        case "1":
            $arraybanco["ClientType"] = "Person";
            break;

        case "0":
            $arraybanco["ClientType"] = "Current";

            break;
    }


    /* Asignación de estado y moneda a un arreglo según condición de estado del usuario. */
    $arraybanco["State"] = ($value->{"usuario_banco.estado"} == "A") ? 0 : 1;
    $arraybanco["Coin"] = 'PEN';

    if ($arraybanco["State"] == "A") {
        // $arraybanco["state"] = '1';

    } elseif ($arraybanco["State"] == "I") {
        /* cambia el estado de "I" a "C" en el arreglo $arraybanco. */

        $arraybanco["State"] = 'C';

    }

    /* Agrega el contenido de $arraybanco al final de $configuracionesData. */
    array_push($configuracionesData, $arraybanco);


}


/* verifica si un valor es numérico y lo asigna a `$count`. */
$count = 0;

if (is_numeric($configuraciones->count[0]->{'.count'})) {
    $count = $configuraciones->count[0]->{'.count'};
}

$response["HasError"] = false;

/* configura una respuesta con información sobre el estado y datos procesados. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["pos"] = $SkeepRows;
$response["total_count"] = $count;
$response["data"] = $configuracionesData;


?>
