<?php


use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Pais;
use Backend\dto\UsuarioTorneo;


/**
 * Este script obtiene los premios de un torneo y los organiza en un formato específico.
 *
 * @param object $params Objeto JSON decodificado con los parámetros de entrada.
 * @param int $params->Id ID del torneo.
 * @param int $params->SkeepRows Número de filas a omitir en la consulta.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 *
 * @return array $response Respuesta estructurada con los datos del torneo.
 * - HasError: Indica si hubo un error (false si no hay errores).
 * - AlertType: Tipo de alerta (success en caso de éxito).
 * - AlertMessage: Mensaje de alerta (vacío en caso de éxito).
 * - ModelErrors: Lista de errores del modelo (vacío en caso de éxito).
 * - Data: Lista de usuarios del torneo con sus premios clasificados.
 * - Count: Número total de elementos en la respuesta.
 *
 * @throws Exception Si ocurre un error en la obtención de datos.
 */


/* obtiene y decodifica datos JSON de una solicitud HTTP en PHP. */

/* Captura y decodifica datos JSON de entrada, asignando el ID a una variable. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $params->Id;

$TorneoInterno = new TorneoInterno();

/* Se crea una nueva instancia de la clase TorneoDetalle. */

/* Se crea una nueva instancia de la clase TorneoDetalle en PHP. */
$TorneoDetalle = new TorneoDetalle();


if ($Id != "") {


    /* Crea un filtro para validar condiciones sobre torneos en un array de reglas. */

/* Se crea un filtro con reglas para validar datos en un torneo específico. */
    $arrayfinal = [];
    $rules = [];


    array_push($rules, array("field" => "usuario_torneo.torneo_id", "data" => $Id, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* Se inicializan variables para controlar paginación y convertir un filtro a JSON. */

/* Inicializa variables para controlar la paginación y filtrar datos en formato JSON. */
    $SkeepRows = 0;

    $OrderedItem = 1;

    $MaxRows = 50;

    $jsonfiltrodetalles = json_encode($filtro);


    /* obtiene y decodifica datos de torneos de usuarios en formato JSON. */

/* Inicializa un array y obtiene usuarios de torneos en formato JSON, luego decodifica. */
    $final = [];
    $UsuarioTorneo = new UsuarioTorneo();
    $usuariosTorneos = $UsuarioTorneo->getUsuarioTorneosCustom("usuario_torneo.valor,torneo_interno.*,usuario_torneo.posicion,usuario_mandante.usumandante_id,usuario_mandante.usuario_mandante,usuario_mandante.nombres ", "usuario_torneo.valor", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE);

    $usuariosTorneos = json_decode($usuariosTorneos);

    $pos = 1;


    /* recorre usuarios, recopilando información y almacenándola en un array final. */
    
/* itera sobre usuarios, creando una lista con sus datos y posicionándolos. */
foreach ($usuariosTorneos->data as $key2 => $value2) {

        $lista_temp2 = [];
        $lista_temp2['Position'] = $pos;
        $lista_temp2['UserName'] = $value2->{'usuario_mandante.nombres'};
        $lista_temp2['UserId'] = $value2->{'usuario_mandante.usuario_mandante'};
        $lista_temp2['Points'] = $value2->{'usuario_torneo.valor'};
        $lista_temp2['Awards'] = array();
        $lista_temp2['CasinoId'] = $value2->{'usuario_mandante.usumandante_id'};

        array_push($final, ($lista_temp2));
        $pos++;

    }

    //array_push($final, $lista_temp2);


    /* Define reglas de validación para los campos de un torneo en un array. */

/* Define reglas para validar datos en un torneo, utilizando condiciones específicas. */
    $rules = [];


    array_push($rules, array("field" => "torneo_detalle.torneo_id", "data" => $Id, "op" => "eq"));
    array_push($rules, array("field" => "torneo_detalle.moneda", "data" => "", "op" => "nn"));
    array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "'RANKAWARD','RANKAWARDMAT','RANKAWARDBONUS'", "op" => "in"));

//array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));


    /* Configura un filtro y valores predeterminados para la paginación de datos. */

/* Configuración de filtros y valores predeterminados para procesamiento de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Asignación de valor y obtención de detalles de torneos con filtro en formato JSON. */
    
/* asigna un valor por defecto a $MaxRows y obtiene detalles de torneos. */
if ($MaxRows == "") {
    }
    $MaxRows = 10000;

    $jsonfiltro = json_encode($filtro);


    $torneos = $TorneoDetalle->getTorneoDetallesCustom("torneo_detalle.* ", "torneo_detalle.torneo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);


    /* Decodifica datos JSON de torneos y prepara un array auxiliar vacío para uso posterior. */

/* Decodifica un JSON de torneos y inicializa un arreglo auxiliar vacío. */
    $torneos = json_decode($torneos);

    $final2 = [];


    $arrayAux = array();
    foreach ($torneos->data as $key2 => $value2) {


        switch ($value2->{"torneo_detalle.tipo"}) {

            case "RANKAWARDMAT":
/* Procesa y almacena detalles de un torneo en un arreglo auxiliar. */

                /* procesa datos de un torneo y los almacena en un arreglo auxiliar. */


                $array2 = [];
                $array2['pos'] = $value2->{"torneo_detalle.valor"};
                $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                $array2['type'] = 0;
                //array_push($lista_temp2['premios'], ($array2));
                $arrayAux[] = $array2;

                break;


            case "RANKAWARD":
/* Crea un arreglo que almacena detalles de premios de un torneo específico. */

                /* Crea un array con detalles de premios de un torneo. */


                $array2 = [];
                $array2['pos'] = $value2->{"torneo_detalle.valor"};
                $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                $array2['type'] = 1;

                //array_push($lista_temp2['premios'], ($array2));
                $arrayAux[] = $array2;
                break;

            case "RANKAWARDBONUS":
/* Construye un arreglo para almacenar datos de clasificación en un torneo. */

                /* Construye un arreglo con datos de un torneo para clasificación de recompensas. */


                $array2 = [];
                $array2['pos'] = $value2->{"torneo_detalle.valor"};
                $array2['desc'] = $value2->{"torneo_detalle.valor3"};
                $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                $array2['type'] = 3;

                $arrayAux[] = $array2;
                break;

        }

    }


    /* agrupa elementos de `$arrayAux` por su posición en `$arrayAux2`. */

/* agrupa elementos de `$arrayAux` por su posición en `$arrayAux2`. */
    $arrayAux2 = array();
    foreach ($arrayAux as $key) {

        $arrayAux2[$key['pos']][] = $key;
    }

    $aFinal2 = array();
    foreach ($final as $key => $value) {


        /* Se crean tres arrays para almacenar diferentes tipos de premios: dinero, físico y bonos. */

/* Se crean tres arreglos para almacenar diferentes tipos de premios: dinero, físico y bonos. */
        $PremiosDinero = array();
        $PremiosFisico = array();
        $PremiosBonos = array();
        if (isset($arrayAux2[$value["Position"]])) {

            foreach ($arrayAux2[$value["Position"]] as $premio) {

                /* Clasifica premios físicos y monetarios según su tipo y descripción. */
                
/* Clasifica premios en físico y dinero según su tipo y descripción. */
if ($premio["type"] == 0) {
                    array_push($PremiosFisico, $premio["desc"]);
                }
                if ($premio["type"] == 1) {
                    if ($premio["desc"] == '0') {
                        $premio["desc"] = ' en Saldo Creditos';
                    }
                    if ($premio["desc"] == '1') {
                        $premio["desc"] = ' en Saldo Premios';
                    }
                    if ($premio["desc"] == '2') {
                        $premio["desc"] = ' en Saldo Bonos';
                    }
                    array_push($PremiosDinero, $premio["value"] . ' ' . $premio["desc"]);
                }

                /* Condicional que agrega una descripción de bono a un listado si cumple la condición. */
                
/* verifica un tipo de premio y guarda su descripción en un array. */
if ($premio["type"] == 3) {

                    $premio["desc"] = ' El Id del bono es:';
                    array_push($PremiosBonos, $premio["desc"] . ' ' . $premio["value"]);
                }
            }

            /* Asigna premios físicos, de dinero y bonos a un arreglo final. */

/* asigna premios a una estructura en función de posiciones y tipos de premios. */
            $final[$key]["Awards"] = $arrayAux2[$value["Position"]];
            $final[$key]["PhysicalAward"] = implode(',', $PremiosFisico);
            $final[$key]["MoneyAward"] = implode(',', $PremiosDinero);
            $final[$key]["BonusAward"] = implode(',', $PremiosBonos);
        }

    }

    /* elimina elementos sin premios de un array llamado $final. */
    
/* elimina elementos vacíos de un arreglo según condiciones específicas. */
foreach ($final as $key => $value) {

        if ($value['PhysicalAward'] == '' && $value['MoneyAward'] == '' && $value['BonusAward'] == '') {
            unset($final[$key]);
        }

    }

    /* copia elementos de un arreglo a otro y declara una respuesta vacía. */

/* copia elementos de un arreglo a otro y prepara una respuesta vacía. */
    $finaltemp = array();
    foreach ($final as $item) {
        array_push($finaltemp, $item);
    }

    $response = array();


    /* configura una respuesta exitosa sin errores y con datos finales. */

/* define una respuesta exitosa sin errores y con datos asociados. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["Data"] = $finaltemp;

    /* La línea asigna un conteo previo a una respuesta en formato de array. */

/* Asigna el resultado de oldCount(finaltemp) a la clave "Count" en $response. */
    $response["Count"] = oldCount($finaltemp);

} else {
/* inicializa una respuesta vacía indicando que hay errores. */

    /* inicializa una respuesta sin errores y sin datos para una solicitud. */

    $response = array();

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["Data"] = "";
    $response["Count"] = 0;

}
?>



