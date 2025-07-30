<?php


use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\JackpotInterno;
use Backend\dto\JackpotDetalle;
use Backend\dto\UsuarioJackpot;
use Backend\dto\UsuarioMandante;
use Backend\dto\Pais;
use Backend\dto\Moneda;
use Backend\mysql\JackpotInternoMySqlDAO;
use Backend\mysql\MonedaMySqlDAO;
use Backend\mysql\JackpotDetalleMySqlDAO;
use Backend\mysql\UsuarioJackpotMySqlDAO;

/**
 * Recurso que obtiene los detalles de los jackpots.
 *
 * @param string $json->params->StartTimeLocal Fecha y hora de inicio local.
 * @param string $json->params->EndTimeLocal Fecha y hora de fin local.
 * @param int $json->params->site_id ID del sitio.
 * @param int $json->params->Limit Número máximo de filas.
 * @param int $json->params->OrderedItem Ítem ordenado.
 * @param int $json->params->Offset Número de filas a omitir.
 * @param string $json->params->State Estado del jackpot (I: Inactivo, A: Activo).
 * @param string $json->params->country País.
 *
 * @return array Respuesta en formato JSON:
 * - code (int) Código de respuesta.
 * - rid (int) ID de la transacción.
 * - data (array) Datos de los jackpots:
 *   - id (int) ID del jackpot.
 *   - name (string) Nombre del jackpot.
 *   - description (string) Descripción del jackpot.
 *   - currentValue (int) Valor actual del jackpot.
 *   - image (string) URL de la imagen del jackpot.
 *   - image2 (string) URL de la segunda imagen del jackpot.
 *   - gif (string) URL del gif del jackpot.
 *   - startDate (string) Fecha de inicio del jackpot.
 *   - endDate (string) Fecha de fin del jackpot.
 *   - information (string) Información adicional del jackpot.
 *   - counterStyle (int) Estilo del contador del jackpot.
 *   - currency (string) Símbolo de la moneda del jackpot.
 *
 * @throws Exception "No se encontraron jackpots" con código "12".
 */

$params=$json->params;
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$Mandante = $params->site_id;


$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

// Asignar el número máximo de filas, el ítem ordenado y el número de filas a omitir
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

// Asignar el tipo de estado y el estado
$StateType = $params->StateType;
$State = ($params->State == "I")? 'I':'A';

// Asignar el país
$Country = $params->country;

// Instanciar un objeto de la clase Pais con el país dado
$Pais = new Pais("", $Country);

// Instanciar un objeto de la clase JackpotInterno
$JackpotInterno = new JackpotInterno();
$rules = [];

// Agregar las reglas de filtrado para la consulta
array_push($rules, array("field" => "jackpot_interno.mandante", "data" => "$Mandante", "op" => "eq"));
array_push($rules, array("field" => "jackpot_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
array_push($rules, array("field" => "jackpot_detalle.valor", "data" => "$Pais->paisId", "op" => "eq"));
array_push($rules, array("field" => "jackpot_interno.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "jackpot_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));

// Crear el filtro en formato JSON para la consulta
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

// Obtener los jackpots utilizando el método getJackpotCustom de la clase JackpotInterno
$Jackpots = $JackpotInterno->getJackpotCustom("jackpot_interno.*", "jackpot_interno.jackpot_id", "asc", '0', '1000', $json2, true);

// Decodificar el JSON de la respuesta a un objeto PHP
$Jackpots = json_decode($Jackpots);
$final = array();
foreach ($Jackpots->data as $value) {

    //Asignación de valores a los parámetros de los objetos de respuesta
    $array = [];
    $array["id"] = $value->{"jackpot_interno.jackpot_id"};
    $array["name"] = $value->{"jackpot_interno.nombre"};
    $array["description"] = $value->{"jackpot_interno.descripcion"};
    $array["currentValue"] = intval($value->{"jackpot_interno.valor_actual"});
    $array["image"] = $value->{"jackpot_interno.imagen"};
    $array["image2"] =$value->{"jackpot_interno.imagen2"};
    $array["gif"] =$value->{"jackpot_interno.gif"};
    $array["startDate"] =$value->{"jackpot_interno.fecha_inicio"};
    $array["endDate"] =$value->{"jackpot_interno.fecha_fin"};
    $array["information"] =$value->{"jackpot_interno.informacion"};

    /** Verificacion para definir contador en enteros o en decimales*/
    $JackpotDetalle = new JackpotDetalle();
    // Carga el estilo del contador del jackpot
    $jackpotCurrency = $JackpotDetalle->cargarDetallesJackpot($value->{"jackpot_interno.jackpot_id"}, 'COUNTERSTYLE');
    $array["counterStyle"] = intval($jackpotCurrency[0]->valor);

    /** Verificacion para agregar simbolo de moneda */
    $JackpotDetalle = new JackpotDetalle();
    $jackpotCurrency = $JackpotDetalle->cargarDetallesJackpot($value->{"jackpot_interno.jackpot_id"}, 'SHOWCURRENCYSIGN');
    $array["currency"] = null;
    if ($jackpotCurrency[0]->valor == "1"){ //Si el usuario eligió que se mostrara el simbolo de la moneda
        $Moneda= new Moneda($jackpotCurrency[0]->moneda);
        $array["currency"] = $Moneda->{"symbol"} . " "; // Devolvemos simbolo de moneda
    };

    array_push($final, $array);
}






$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;
