<?php

use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');

/**
 * @param int $UserId : Identificador único del usuario.
 *
 * @Description Este recurso permite obtener las comisiones de los bancos en el sistema, filtrando por el país del usuario y el estado del banco.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 * - *Data* (array): Datos de las comisiones de los bancos.
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
 * Se obtienen los parámetros de la solicitud en formato JSON y se decodifican.
 *
 * @var string $params Contiene los datos de entrada en formato JSON.
 * @var object $params Decodificado en un objeto PHP.
 * @var int $UserId Identificador del usuario extraído de los parámetros.
 * @var UsuarioMandante $UsuarioMandante Instancia de la clase UsuarioMandante.
 * @var Usuario $Usuario Instancia de la clase Usuario.
 * @var int $MaxRows Número máximo de filas a procesar.
 * @var int $SkeepRows Número de filas a omitir.
 * @var string $OrderedItem Ítem ordenado.
 */

/* obtiene y decodifica datos JSON de la entrada en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$UserId = $params->UserId;

$UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);

/* Código que crea instancias de usuario y país, estableciendo un límite de filas. */
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$Pais = new Pais($Usuario->paisId);


//$UsuarioMandante = new UsuarioMandante("", $UserId, 0);
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());


$MaxRows = 1000000;

/* Código inicializa $SkeepRows y verifica si está vacío para asignarle 0. */
$SkeepRows = 0;
$OrderedItem = "";


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


/* Se crean reglas de filtrado para validar condiciones específicas en un banco. */
$rules = [];
array_push($rules, array("field" => "banco.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));
array_push($rules, array("field" => "banco.estado", "data" => "A", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte datos a JSON, obtiene bancos y los decodifica. */
$json2 = json_encode($filtro);

$Banco = new Banco();

$Bancos = $Banco->getBancosCustom(" banco.* ", "banco.banco_id", "asc", $SkeepRows, $MaxRows, $json2, true);


$Bancos = json_decode($Bancos);


/* crea un array con datos de bancos a partir de un objeto. */
$BancosData = array();

foreach ($Bancos->data as $key => $value) {


    $arraybanco = array();
    $arraybanco["id"] = ($value->{"banco.banco_id"});
    $arraybanco["value"] = ($value->{"banco.descripcion"});

    array_push($BancosData, $arraybanco);


}


/* define una respuesta estructurada sin errores, con un mensaje de éxito. */
$respuestafinal = "";

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = $respuestafinal;
$response["ModelErrors"] = [];

/* Asigna el valor de $BancosData a la clave "Data" en el array $response. */
$response["Data"] = $BancosData;


?>
