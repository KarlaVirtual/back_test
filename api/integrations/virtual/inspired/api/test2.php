<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $subid                   Variable que almacena un identificador relacionado con un subproceso o subevento.
 * @var mixed $subidsum                Variable que almacena la suma de identificadores relacionados con un subproceso.
 * @var mixed $objfin                  Variable que almacena el objeto final relacionado con un proceso o evento.
 * @var mixed $objfirst                Variable que almacena el primer objeto de un conjunto o proceso.
 * @var mixed $objinicio               Variable que almacena el objeto inicial relacionado con un proceso o evento.
 * @var mixed $response                Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $json                    Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $what                    Variable que almacena una pregunta o consulta sobre algo en el sistema.
 * @var mixed $where                   Variable que almacena una consulta sobre la ubicación o contexto de algo en el sistema.
 * @var mixed $result_array_final      Variable que almacena el arreglo final de resultados de un evento o proceso.
 * @var mixed $result_array            Variable que almacena un arreglo de resultados de un proceso o evento.
 * @var mixed $campos                  Variable que almacena campos o atributos asociados a un proceso o entidad.
 * @var mixed $cont                    Variable que almacena un contador o valor numérico relacionado con un proceso.
 * @var mixed $rules                   Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $key                     Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                   Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $field                   Variable que almacena un campo o área de datos en una base de datos o formulario.
 * @var mixed $op                      Variable que almacena una operación o acción a realizar en un proceso.
 * @var mixed $data                    Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $data_array              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $item                    Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $filtro                  Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonfiltro              Variable que almacena un filtro en formato JSON.
 * @var mixed $IntEventoApuestaDetalle Variable que almacena información detallada sobre un evento de apuesta.
 * @var mixed $apuestas                Variable que almacena un conjunto de apuestas realizadas.
 * @var mixed $final                   Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $apuesta                 Variable que almacena una apuesta realizada en un juego o evento.
 * @var mixed $array                   Variable que almacena una lista o conjunto de datos.
 * @var mixed $arrayd                  Variable que almacena un arreglo de datos relacionados con un proceso.
 * @var mixed $campo                   Variable que almacena un campo específico dentro de un conjunto de datos o formulario.
 * @var mixed $competencia             Variable que almacena información sobre una competencia o evento deportivo.
 * @var mixed $IntEventoApuesta        Variable que almacena información detallada sobre un evento de apuesta.
 * @var mixed $seguir                  Variable que indica si se debe continuar con una operación o proceso.
 * @var mixed $IntEventoDetalle        Variable que almacena detalles sobre un evento relacionado con una apuesta.
 * @var mixed $eventos                 Variable que almacena una lista o conjunto de eventos.
 * @var mixed $eventoid                Variable que almacena el identificador único de un evento.
 * @var mixed $evento                  Variable que almacena información sobre un evento específico.
 * @var mixed $eventoA                 Variable que almacena un evento específico A dentro de un proceso o sistema.
 * @var mixed $is_blocked              Variable que indica si una entidad o proceso está bloqueado o no.
 * @var mixed $item1                   Variable que almacena un elemento genérico.
 * @var mixed $key2                    Variable que almacena una segunda clave.
 * @var mixed $item2                   Variable que almacena un segundo elemento en una lista o estructura de datos.
 * @var mixed $IntCompetencia          Variable que almacena información detallada sobre una competencia.
 * @var mixed $competencias            Variable que almacena una lista de competencias o eventos deportivos.
 * @var mixed $IntRegion               Variable que almacena información detallada sobre una región geográfica o administrativa.
 * @var mixed $regiones                Variable que almacena una lista de regiones geográficas o administrativas.
 * @var mixed $region                  Variable que almacena información sobre una región geográfica.
 * @var mixed $IntDeporte              Variable que almacena información detallada sobre un deporte específico.
 * @var mixed $sports                  Variable que almacena información sobre deportes disponibles o involucrados en un sistema.
 * @var mixed $sport                   Variable que almacena información sobre un deporte.
 * @var mixed $eventarray              Variable que almacena un arreglo de eventos asociados a un proceso o sistema.
 * @var mixed $gamearray               Variable que almacena un arreglo de juegos disponibles o en proceso.
 * @var mixed $array_final             Variable que almacena el arreglo final de elementos en un proceso o conjunto de datos.
 * @var mixed $responseW               Variable que almacena la respuesta de un sistema o proceso relacionado con el usuario.
 * @var mixed $WebsocketUsuario        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $e                       Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $mercado                 Variable que almacena información sobre el mercado de apuestas o el mercado de un producto.
 * @var mixed $idioma                  Variable que almacena el idioma seleccionado.
 */

use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\websocket\WebsocketUsuario;

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');


/*                        {"code":0,"rid":"15183107607554","data":{"subid":"-8252782767092495715","data":{"sport":{"54":{"id":54,"name":"Carrera Virtual de Caballos","alias":"VirtualHorseRacing","order":176,"game":8},"55":{"id":55,"name":"Carrera de Galgos","alias":"VirtualGreyhoundRacing","order":175,"game":9},"56":{"id":56,"name":"Tenis Virtual","alias":"VirtualTennis","order":174,"game":5},"57":{"id":57,"name":"Fútbol Virtual","alias":"VirtualFootball","order":173,"game":5},"118":{"id":118,"name":"Carrera Virtual de Carros","alias":"VirtualCarRacing","order":177,"game":4},"150":{"id":150,"name":"Virtual Bicycle","alias":"VirtualBicycle","order":178,"game":5},"174":{"id":174,"name":"The Penalty Kicks","alias":"ThePenaltyKicks","order":128,"game":5}}}}}*/


$subid = "-";
$subidsum = 555555;

$objfin = "";
$objfirst = "";
$objinicio = array();

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array();

$what = array(
    "sport" => array(
        "id",
        "alias"
    ),
    "competition" => array(
        "id",
        "alias"
    ),
    "region" => array(
        "id",
        "alias"
    ),
    "game" => array(),
    "market" => array(),
    "event" => array()
);
$where = array(
    "game" => array(
        "id" => "17442"
    )
);
$what = json_decode(json_encode($what));

$where = json_decode(json_encode($where));

$result_array_final = array();


if ($what->event != "" && $what->event != undefined) {
    $result_array = array();

    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->event != "" && $where->event != undefined) {
        foreach ($where->event as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_evento_apuesta_detalle.eventapudetalle_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }

    if ($where->game != "" && $where->game != undefined) {
        foreach ($where->game as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_evento.evento_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }
    array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
    $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
    $apuestas = json_decode($apuestas);


    $final = array();

    foreach ($apuestas->data as $apuesta) {
        $array = array();
        $arrayd = array();

        foreach ($what->event as $campo) {
            switch ($campo) {
                case "id":
                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                    break;

                case "externo_id":
                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalleproveedor_id"});

                    break;

                case "name":
                    $arrayd[$campo] = traduccionMercado($apuesta->{"int_apuesta_detalle.opcion"});

                    break;

                case "type":
                    $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                    break;

                case "type_1":
                    $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                    break;
                case "price":
                    $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                    break;
            }
        }

        if (oldCount($what->event) == 0) {
            $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
            $arrayd["name"] = ucwords(strtolower(traduccionMercado($apuesta->{"int_apuesta_detalle.opcion"})));
            $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
            $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
            $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
            $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
            $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};

            if (strpos($arrayd["name"], 'Under') !== false) {
                $arrayd["base"] = str_replace("Under ", "", $arrayd["name"]);

                $arrayd["name"] = traduccionMercado("Under");
                $arrayd["type"] = traduccionMercado("Under ({h})");
            }

            if (strpos($arrayd["name"], 'Over') !== false) {
                $arrayd["base"] = str_replace("Over ", "", $arrayd["name"]);

                $arrayd["name"] = traduccionMercado("Over");
                $arrayd["type"] = traduccionMercado("Over ({h})");
            }
            // $arrayd["name"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
            // $arrayd["name"] = "Francia";
            //  $arrayd["type"] = "{t1} ({-h})";
            // $arrayd["type_1"] = "Home";
            //$arrayd["type_id"] = 0;
            //$arrayd["base"] = 1;
            // $arrayd["order"] = 0;


        }
        array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
        $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
        $objfirst = "event";

        if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
            $arrayd["price"] = "1";
        }

        //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


        if (is_array($what->market)) {
            $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
            $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
        } else {
            $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
        }

        $objfin = "event";
    }

    $result_array_final = $result_array;
}


if ($what->market != "" && $what->market != undefined) {
    $result_array = array();

    $campos = "";
    $cont = 0;

    $rules = [];
    array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntEventoApuesta = new IntEventoApuesta();
    $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);
    $apuestas = json_decode($apuestas);


    $final = array();

    foreach ($apuestas->data as $apuesta) {
        $array = array();
        $arrayd = array();

        foreach ($what->market as $campo) {
            switch ($campo) {
                case "id":
                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                    break;

                case "name":
                    $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                    break;

                case "alias":
                    $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                    break;

                case "order":
                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                    break;

                case "type":
                    $arrayd[$campo] = ($apuesta->{"int_apuesta.abreviado"});


                    break;
            }
        }

        if (oldCount($what->market) == 0) {
            $arrayd["id"] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});
            $arrayd["market_type"] = $apuesta->{"int_apuesta.abreviado"};
            $arrayd["name"] = $apuesta->{"int_apuesta.nombre"};
            $arrayd["name_template"] = $apuesta->{"int_apuesta.nombre"};
            $arrayd["optimal"] = false;
            $arrayd["order"] = 1000;
            $arrayd["point_sequence"] = 0;
            $arrayd["sequence"] = 0;
            $arrayd["cashout"] = 0;
        }

        //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;
        $seguir = true;
        if (is_array($what->event)) {
            $arrayd["event"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"];
            $arrayd["col_count"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["col_count"];
            $arrayd["type"] = $apuesta->{"int_apuesta.abreviado"};
            if ($result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"] == "") {
                $seguir = true;
            }
        }
        if ($seguir) {
            if (oldCount($objinicio) == 0) {
                array_push($objinicio, intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"}));

                $objfirst = "market";
            }
            if (is_array($what->game)) {
                $result_array["game"][intval($apuesta->{"int_evento_apuesta.evento_id"})]["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
            } else {
                $result_array["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
            }
        }
    }


    $result_array_final = $result_array;
    $objfin = "market";
}

if (is_array($what->game)) {
    if ($objfirst == "") {
        $objfirst = "game";
    }
    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->competition != "" && $where->competition != undefined) {
        foreach ($where->competition as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_competencia.competencia_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }


            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }
    if ($where->sport != "" && $where->sport != undefined) {
        foreach ($where->sport as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_deporte.deporte_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }
    if ($where->game != "" && $where->game != undefined) {
        foreach ($where->game as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_evento.evento_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;

                case "promoted":
                    $field = "int_evento.promocionado";
                    $op = "eq";
                    $data = "S";

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }
    if ($where->game != "" && $where->game != undefined) {
        foreach ($where->game as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_evento.evento_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }
    array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntEventoDetalle = new IntEventoDetalle();
    $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
    $eventos = json_decode($eventos);


    $final = array();
    $arrayd = array();
    $eventoid = "";
    $arrayd["info"]["virtual"] = array();

    foreach ($eventos->data as $evento) {
        $array = array();
        //$arrayd["info"]["virtual"] = $evento;

        if ($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid != "") {
            $arrayd["game_number"] = $eventoid;
            $arrayd["id"] = $eventoid;
            $arrayd["start_ts"] = strtotime($eventoA->{"int_evento.fecha"});
            $arrayd["type"] = 0;
            $arrayd["tv_type"] = 29;
            $arrayd["video_id"] = $eventoid;
            $arrayd["type"] = 0;
            $arrayd["markets_count"] = 63;

            $is_blocked = 0;

            if ($eventoA->{"int_evento.estado"} != "A") {
                $is_blocked = true;
            }

            $arrayd["is_blocked"] = $is_blocked;

            if (is_array($what->market)) {
                $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];
            }


            if (is_array($what->competition)) {
                $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
            } else {
                $result_array["game"][$eventoid] = $arrayd;
            }

            if ($objfirst == "game") {
                array_push($objinicio, intval($eventoA->{"int_evento.evento_id"}));
            }

            $arrayd = array();
        }

        foreach ($what->game as $campo) {
            switch ($campo) {
                case "team1_name":

                    if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                    }


                    break;

                case "team2_name":
                    if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                    }
                    break;

                case "text_info":
                    if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                        // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                    }
                    break;

                case "externo_id":
                    $arrayd[$campo] = intval($apuesta->{"int_evento.eventoproveedor_id "});

                    break;
            }
        }
        if (oldCount($what->game) == 0) {
            switch ($evento->{"int_evento_detalle.tipo"}) {
                case "TEAM1":

                    $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                    $arrayd["info"]["virtual"][0] = array(
                        "AnimalName" => "",
                        "Number" => 1,
                        "PlayerName" => $evento->{"int_evento_detalle.valor"}
                    );

                    foreach ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"] as $key => $item1) {
                        foreach ($item1["event"] as $key2 => $item2) {
                            if (strtolower($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"]) == "win") {
                                $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"] = $evento->{"int_evento_detalle.valor"};
                                $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] = $evento->{"int_evento_detalle.valor"};
                            }
                        }
                    }

                    break;

                case "TEAM2":
                    $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                    $arrayd["info"]["virtual"][1] = array(
                        "AnimalName" => "",
                        "Number" => 2,
                        "PlayerName" => $evento->{"int_evento_detalle.valor"}
                    );

                    foreach ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"] as $key => $item1) {
                        foreach ($item1["event"] as $key2 => $item2) {
                            if (strtolower($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"]) == "lose") {
                                $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"] = $evento->{"int_evento_detalle.valor"};
                                $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] = $evento->{"int_evento_detalle.valor"};
                            }
                        }
                    }

                    break;

                case "RACER":
                    /*
                     * AnimalName

                                                                "Monumentous"
                                                                HumanTextureID
                                                                :
                                                                "0"
                                                                Number
                                                                :
                                                                1
                                                                PlayerName
                                                                :
                                                                "Tom Kunkle"
                                                                RacerTextureID
                                                                :
                                                                "2"

                     */
                    $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                    array_push($arrayd["info"]["virtual"], array(
                        "AnimalName" => str_replace("Racer", "", $evento->{"int_evento_detalle.valor"}),
                        "Number" => 1,
                        "PlayerName" => str_replace("Racer", "", $evento->{"int_evento_detalle.valor"}),
                        "RacerTextureID" => str_replace("Racer", "", $evento->{"int_evento_detalle.id"}),
                        "HumanTextureID" => str_replace("Racer", "", $evento->{"int_evento_detalle.id"})
                    ));
                    foreach ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"] as $key => $item1) {
                        foreach ($item1["event"] as $key2 => $item2) {
                            if ($result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] == str_replace("Racer", "", $evento->{"int_evento_detalle.id"})) {
                                $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["name"] = $evento->{"int_evento_detalle.valor"};
                                $result_array_final["game"][intval($evento->{"int_evento.evento_id"})]["market"][$key]["event"][$key2]["type"] = $evento->{"int_evento_detalle.valor"};
                            }
                        }
                    }

                    break;

                case "externo_id":
                    $arrayd[$campo] = intval($apuesta->{"int_evento.eventoproveedor_id "});

                    break;
            }
        }


        $eventoid = intval($evento->{"int_evento.evento_id"});
        $eventoA = $evento;
        //array_push($final, $array);

    }

    $arrayd["game_number"] = $eventoid;
    $arrayd["id"] = $eventoid;
    $arrayd["start_ts"] = strtotime($eventoA->{"int_evento.fecha"});
    $arrayd["tv_type"] = 29;
    $arrayd["video_id"] = $eventoid;
    $arrayd["type"] = 0;
    $arrayd["externo_id"] = ($eventoA->{"int_evento.eventoproveedor_id"});

    $is_blocked = 0;

    if ($evento->{"int_evento.estado"} != "A") {
        $is_blocked = true;
    }

    $arrayd["is_blocked"] = $is_blocked;


    if (is_array($what->market)) {
        $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];
    }

    if (is_array($what->competition)) {
        $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
        if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
            //$subid=$subid."501".$evento->{"int_evento.evento_id"};

        }
    } else {
        $result_array["game"][$eventoid] = $arrayd;

        if (oldCount($result_array["game"]) == 1) {
            //$subid=$subid."501".$evento->{"int_evento.evento_id"};

        }
    }
    if ($objfirst == "game") {
        array_push($objinicio, intval($evento->{"int_evento.evento_id"}));
    }

    $objfin = "game";

    $result_array_final = $result_array;
}

if ($what->competition != "" && $what->competition != undefined) {
    $result_array = array();

    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->competition != "" && $where->competition != undefined) {
        foreach ($where->competition as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_competencia.competencia_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;

                case "promoted":
                    $field = "int_competencia.promocionado";
                    $op = "eq";
                    $data = "S";

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }
    if ($where->sport != "" && $where->sport != undefined) {
        foreach ($where->sport as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_deporte.deporte_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }

            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntCompetencia = new IntCompetencia();
    $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);
    $competencias = json_decode($competencias);


    $final = array();

    foreach ($competencias->data as $competencia) {
        $array = array();
        $arrayd = array();

        foreach ($what->competition as $campo) {
            switch ($campo) {
                case "id":
                    $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                    break;

                case "name":
                    $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                    break;

                case "alias":
                    $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                    break;

                case "order":
                    $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                    break;
            }
        }

        //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;
        $seguir = true;

        if (is_array($what->game)) {
            $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];
            if ($arrayd["game"] == null) {
                $seguir = false;
            }
        }
        if ($seguir) {
            if (is_array($what->region)) {
                $result_array["region"][intval($competencia->{"int_competencia.region_id"})]["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
            } else {
                $result_array["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
            }
            if (oldCount($objinicio) == 0) {
                array_push($objinicio, intval($competencia->{"int_competencia.competencia_id"}));

                $objfirst = "competition";
            }
        }
    }

    if (oldCount($competencias->data) == 1) {
        //$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

    }

    $objfin = "competition";

    $result_array_final = $result_array;
}

if ($what->region != "" && $what->region != undefined) {
    $result_array = array();
    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->region != "" && $where->region != undefined) {
        foreach ($where->competition as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_region.region_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntRegion = new IntRegion();
    $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);
    $regiones = json_decode($regiones);


    $final = array();

    foreach ($regiones->data as $region) {
        $array = array();
        $arrayd = array();

        foreach ($what->region as $campo) {
            switch ($campo) {
                case "id":
                    $arrayd[$campo] = intval($region->{"int_region.region_id"});

                    break;

                case "name":
                    $arrayd[$campo] = $region->{"int_region.nombre"};

                    break;

                case "alias":
                    $arrayd[$campo] = $region->{"int_region.abreviado"};

                    break;

                case "order":
                    $arrayd[$campo] = intval($region->{"int_region.region_id"});

                    break;
            }
        }
        $seguir = true;


        if (is_array($what->competition)) {
            $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];
            if ($arrayd["competition"] == null) {
                $seguir = false;
            }
        }


        if ($seguir) {
            if (is_array($what->sport)) {
                $result_array["sport"][intval($region->{"int_region.deporte_id"})]["region"][intval($region->{"int_region.region_id"})] = $arrayd;
            } else {
                $result_array["region"][intval($region->{"int_region.region_id"})] = $arrayd;
            }
            if (oldCount($objinicio) == 0) {
                array_push($objinicio, intval($region->{"int_region.region_id"}));

                $objfirst = "region";
            }
        }
    }

    if (oldCount($regiones->data) == 1) {
        //$subid=$subid."301".$region->{"int_region.region_id"};

    }

    $objfin = "region";

    $result_array_final = $result_array;
}

if ($what->sport != "" && $what->sport != undefined) {
    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->sport != "" && $where->sport != undefined) {
        foreach ($where->sport as $key => $value) {
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    $field = "int_deporte.deporte_id";
                    break;

                case "name":

                    break;

                case "alias":

                    break;

                case "order":

                    break;
            }
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
            }
        }
    }

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntDeporte = new IntDeporte();
    $sports = $IntDeporte->getDeportesCustom("int_deporte.*", "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);
    $sports = json_decode($sports);


    $final = array();

    foreach ($sports->data as $sport) {
        $array = array();
        $arrayd = array();

        foreach ($what->sport as $campo) {
            switch ($campo) {
                case "id":
                    $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                    break;

                case "name":
                    $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                    break;

                case "alias":
                    $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                    break;

                case "order":
                    $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                    break;
            }
        }

        $final[$sport->{"int_deporte.deporte_id"}] = $arrayd;

        if (is_array($what->region)) {
            $arrayd["region"] = $result_array_final["sport"][intval($sport->{"int_deporte.deporte_id"})]["region"];
            if ($arrayd["region"] != null) {
                $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
            }
        } else {
            $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
        }

        if (oldCount($objinicio) == 0) {
            array_push($objinicio, intval($sport->{"int_deporte.deporte_id"}));

            $objfirst = "sport";
        }
        //array_push($final, $array);

    }

    if (oldCount($sports->data) == 1) {
        //$subid=$subid."201".$sport->{"int_deporte.deporte_id"};

    }

    $result_array_final = $result_array;

    $objfin = "sport";
}

switch ($objfin) {
    case "event":
        $subid = $subid . "324" . $subidsum . "517";
        break;

    case "market":
        $subid = $subid . "435" . $subidsum . "423";
        break;

    case "game":
        $subid = $subid . "614" . $subidsum . "421";
        break;

    case "competition":
        $subid = $subid . "241" . $subidsum . "172";
        break;

    case "region":
        $subid = $subid . "843" . $subidsum . "495";
        break;

    case "sport":
        $subid = $subid . "629" . $subidsum . "151";
        break;
}

$response["data"][$subid] = $result_array_final;
$response["data"]["dataSub"] = array(
    "subid" => $subid,
    "first" => $objfin,
    "end" => $objfirst,
    "id" => $objinicio
);


if (true) {
    try {
        $eventarray = array("18596");
        $gamearray = array("18596");

        $result_array_final = array();
        $subid = "-";

        $objfin = "";
        $objfirst = "";
        $objinicio = array();


        $what = array(
            "event" => ["id", "price"],
            "market" => ["id"],
            "game" => ["id"],
            "competition" => ["id", "name"],
            "region" => ["id"],
            "sport" => ["id", "alias"]
        );

        $where = array(
            "game" => array(
                "id" => array(
                    "@in" => $gamearray
                )
            ),
            "event" => array(
                "id" => array(
                    "@in" => $eventarray
                )
            )
        );

        $what = array(
            "event" => ["id", "price"],
            "market" => ["id"],
            "game" => ["id"],
            "competition" => ["id", "name"],
            "region" => ["id"],
            "sport" => ["id", "alias"]
        );

        $where = array(
            "game" => array(
                "id" => array(
                    "@in" => $gamearray
                )
            )
        );

        $what = json_decode(json_encode($what));
        $where = json_decode(json_encode($where));

        $array_final = array();


        if ($what->event != "" && $what->event != undefined) {
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->event != "" && $where->event != undefined) {
                foreach ($where->event as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_evento_apuesta_detalle.eventapudetalle_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }

                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }

                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }

            if ($where->game != "" && $where->game != undefined) {
                foreach ($where->game as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_evento.evento_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }

                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }

                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
            $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
            $apuestas = json_decode($apuestas);


            $final = array();

            foreach ($apuestas->data as $apuesta) {
                $array = array();
                $arrayd = array();

                foreach ($what->event as $campo) {
                    switch ($campo) {
                        case "id":
                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                            break;

                        case "name":
                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion"};

                            break;

                        case "type":
                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                            break;

                        case "type_1":
                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                            break;
                        case "price":
                            $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                            break;
                    }
                }

                if (oldCount($what->event) == 0) {
                    $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                    $arrayd["name"] = $apuesta->{"int_apuesta_detalle.opcion"};
                    $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
                    $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
                    $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                }
                array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
                $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                $objfirst = "event";

                if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
                    $arrayd["price"] = "1";
                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                if (is_array($what->market)) {
                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
                } else {
                    $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                }


                $objfin = "event";
            }


            $result_array_final = $result_array;
        }


        if ($what->market != "" && $what->market != undefined) {
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];
            array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoApuesta = new IntEventoApuesta();
            $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);
            $apuestas = json_decode($apuestas);


            $final = array();

            foreach ($apuestas->data as $apuesta) {
                $array = array();
                $arrayd = array();

                foreach ($what->market as $campo) {
                    switch ($campo) {
                        case "id":
                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                            break;

                        case "name":
                            $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                            break;

                        case "alias":
                            $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                            break;

                        case "order":
                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                            break;
                    }
                }

                if (oldCount($what->market) == 0) {
                    $arrayd["id"] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});
                    $arrayd["market_type"] = $apuesta->{"int_apuesta.abreviado"};
                    $arrayd["name"] = $apuesta->{"int_apuesta.nombre"};
                    $arrayd["name_template"] = $apuesta->{"int_apuesta.nombre"};
                    $arrayd["optimal"] = false;
                    $arrayd["order"] = 1000;
                    $arrayd["point_sequence"] = 0;
                    $arrayd["sequence"] = 0;
                    $arrayd["cashout"] = 0;
                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;
                $seguir = true;
                if (is_array($what->event)) {
                    $arrayd["event"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"];
                    //$arrayd["col_count"]=$result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["col_count"];
                    if ($result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"] == "") {
                        $seguir = true;
                    }
                    if (oldCount($arrayd["event"]) <= 0) {
                        $seguir = false;
                    }
                }
                if ($seguir) {
                    if (oldCount($objinicio) == 0) {
                        array_push($objinicio, intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"}));

                        $objfirst = "market";
                    }
                    if (is_array($what->game)) {
                        $result_array["game"][intval($apuesta->{"int_evento_apuesta.evento_id"})]["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
                    } else {
                        $result_array["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
                    }
                }
            }


            $result_array_final = $result_array;
            $objfin = "market";
        }

        if (is_array($what->game)) {
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->competition != "" && $where->competition != undefined) {
                foreach ($where->competition as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_competencia.competencia_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }

                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }


                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }
            if ($where->sport != "" && $where->sport != undefined) {
                foreach ($where->sport as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_deporte.deporte_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }

                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }

                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }
            if ($where->game != "" && $where->game != undefined) {
                foreach ($where->game as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_evento.evento_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }

                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }

                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoDetalle = new IntEventoDetalle();
            $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
            $eventos = json_decode($eventos);


            $final = array();
            $arrayd = array();
            $eventoid = "";

            foreach ($eventos->data as $evento) {
                $array = array();

                foreach ($what->game as $campo) {
                    switch ($campo) {
                        case "team1_name":

                            if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }


                            break;

                        case "team2_name":
                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }
                            break;

                        case "text_info":
                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                                // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }
                            break;
                    }
                }
                if (oldCount($what->game) == 0) {
                    switch ($evento->{"int_evento_detalle.tipo"}) {
                        case "TEAM1":

                            $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                            $arrayd["info"]["virtual"][0] = array(
                                "AnimalName" => "",
                                "Number" => 1,
                                "PlayerName" => $evento->{"int_evento_detalle.valor"}
                            );

                            break;

                        case "TEAM2":
                            $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                            $arrayd["info"]["virtual"][1] = array(
                                "AnimalName" => "",
                                "Number" => 2,
                                "PlayerName" => $evento->{"int_evento_detalle.valor"}
                            );
                            break;
                    }
                }

                if ($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid != "") {
                    $arrayd["game_number"] = $eventoid;
                    $arrayd["id"] = $eventoid;
                    $arrayd["start_ts"] = $eventoA->{"int_evento.fecha"};
                    $arrayd["type"] = 0;

                    $is_blocked = 0;

                    if ($eventoA->{"int_evento.estado"} != "A") {
                        $is_blocked = 1;
                    }

                    $arrayd["is_blocked"] = $is_blocked;

                    if (is_array($what->market)) {
                        $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];
                    }


                    if (is_array($what->competition)) {
                        $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                    } else {
                        $result_array["game"][$eventoid] = $arrayd;
                    }
                    $arrayd = array();
                }
                $eventoid = intval($evento->{"int_evento.evento_id"});
                $eventoA = $evento;
                //array_push($final, $array);

            }

            $arrayd["game_number"] = $eventoid;
            $arrayd["id"] = $eventoid;
            $arrayd["start_ts"] = $evento->{"int_evento.fecha"};
            $arrayd["type"] = 0;
            $is_blocked = 0;

            if ($evento->{"int_evento.estado"} != "A") {
                $is_blocked = 1;
            }

            $arrayd["is_blocked"] = $is_blocked;


            if (is_array($what->market)) {
                $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];
            }

            if (is_array($what->competition)) {
                $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                }
            } else {
                $result_array["game"][$eventoid] = $arrayd;

                if (oldCount($result_array["game"]) == 1) {
                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                }
            }
            if (oldCount($objinicio) == 0) {
                array_push($objinicio, intval($evento->{"int_evento.evento_id"}));
                $objfirst = "game";
            }

            $objfin = "game";

            $result_array_final = $result_array;
        }

        if ($what->competition != "" && $what->competition != undefined) {
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->competition != "" && $where->competition != undefined) {
                foreach ($where->competition as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_competencia.competencia_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntCompetencia = new IntCompetencia();
            $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);
            $competencias = json_decode($competencias);


            $final = array();

            foreach ($competencias->data as $competencia) {
                $array = array();
                $arrayd = array();

                foreach ($what->competition as $campo) {
                    switch ($campo) {
                        case "id":
                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                            break;

                        case "name":
                            $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                            break;

                        case "alias":
                            $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                            break;

                        case "order":
                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                            break;
                    }
                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;

                if (is_array($what->game)) {
                    $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];
                }
                if (is_array($what->region)) {
                    $result_array["region"][intval($competencia->{"int_competencia.region_id"})]["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                } else {
                    $result_array["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                }
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($competencia->{"int_competencia.competencia_id"}));

                    $objfirst = "competition";
                }
            }

            if (oldCount($competencias->data) == 1) {
                //$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

            }

            $objfin = "competition";

            $result_array_final = $result_array;
        }

        if ($what->region != "" && $what->region != undefined) {
            $result_array = array();
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->region != "" && $where->region != undefined) {
                foreach ($where->competition as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_region.region_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntRegion = new IntRegion();
            $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);
            $regiones = json_decode($regiones);


            $final = array();

            foreach ($regiones->data as $region) {
                $array = array();
                $arrayd = array();

                foreach ($what->competition as $campo) {
                    switch ($campo) {
                        case "id":
                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                            break;

                        case "name":
                            $arrayd[$campo] = $region->{"int_region.nombre"};

                            break;

                        case "alias":
                            $arrayd[$campo] = $region->{"int_region.abreviado"};

                            break;

                        case "order":
                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                            break;
                    }
                }


                if (is_array($what->competition)) {
                    $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];
                }

                if (is_array($what->sport)) {
                    $result_array["sport"][intval($region->{"int_region.deporte_id"})]["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                } else {
                    $result_array["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                }
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($region->{"int_region.region_id"}));

                    $objfirst = "region";
                }
            }

            if (oldCount($regiones->data) == 1) {
                //$subid=$subid."301".$region->{"int_region.region_id"};

            }

            $objfin = "region";

            $result_array_final = $result_array;
        }

        if ($what->sport != "" && $what->sport != undefined) {
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->sport != "" && $where->sport != undefined) {
                foreach ($where->sport as $key => $value) {
                    $field = "";
                    $op = "";
                    $data = "";

                    switch ($key) {
                        case "id":
                            $field = "int_deporte.deporte_id";
                            break;

                        case "name":

                            break;

                        case "alias":

                            break;

                        case "order":

                            break;
                    }
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                    }
                }
            }

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntDeporte = new IntDeporte();
            $sports = $IntDeporte->getDeportesCustom("int_deporte.*", "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);
            $sports = json_decode($sports);


            $final = array();

            foreach ($sports->data as $sport) {
                $array = array();
                $arrayd = array();

                foreach ($what->sport as $campo) {
                    switch ($campo) {
                        case "id":
                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                            break;

                        case "name":
                            $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                            break;

                        case "alias":
                            $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                            break;

                        case "order":
                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                            break;
                    }
                }

                $final[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                if (is_array($what->region)) {
                    $arrayd["region"] = $result_array_final["sport"][intval($sport->{"int_deporte.deporte_id"})]["region"];

                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
                } else {
                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
                }

                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($sport->{"int_deporte.deporte_id"}));

                    $objfirst = "sport";
                }
                //array_push($final, $array);

            }

            if (oldCount($sports->data) == 1) {
                //$subid=$subid."201".$sport->{"int_deporte.deporte_id"};

            }

            $result_array_final = $result_array;

            $objfin = "sport";
        }

        $responseW = array();

        $responseW = array("end" => $objfirst, "first" => $objfin, "ids" => $objinicio, "data" => $result_array_final);
        print_r(json_encode($responseW));


        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        $WebsocketUsuario = new WebsocketUsuario(0, ($responseW));
        $WebsocketUsuario->sendWSMessage();


        $response["ErrorCode"] = 0;
        $response["ErrorDescription"] = "success";

        $response = $response;
    } catch (Exception $e) {
        $response["ErrorCode"] = $e->getCode();
        $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode() . $e->getMessage();
    }
}

print_r(json_encode($response));

/**
 * Traduce el nombre de un mercado al idioma especificado.
 *
 * @param string $mercado El nombre del mercado a traducir.
 * @param string $idioma  El idioma al que se traducirá el mercado (actualmente no utilizado en la lógica).
 *
 * @return string La traducción del mercado o el nombre original si no se encuentra una traducción.
 */
function traduccionMercado($mercado, $idioma)
{
    switch (strtolower($mercado)) {
        case "draw":

            return "Empate";

            break;

        case "hd":

            return "1X";

            break;

        case "ha":

            return "12";

            break;

        case "da":

            return "X2";

            break;

        default:
            if (strpos($mercado, 'Under') !== false) {
                return str_replace("Under ", "Menos ", $mercado);
            }

            if (strpos($mercado, 'Over') !== false) {
                return str_replace("Over ", "Mas ", $mercado);
            }

            return $mercado;
    }
}

