<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/*                        {"code":0,"rid":"15183107607554","data":{"subid":"-8252782767092495715","data":{"sport":{"54":{"id":54,"name":"Carrera Virtual de Caballos","alias":"VirtualHorseRacing","order":176,"game":8},"55":{"id":55,"name":"Carrera de Galgos","alias":"VirtualGreyhoundRacing","order":175,"game":9},"56":{"id":56,"name":"Tenis Virtual","alias":"VirtualTennis","order":174,"game":5},"57":{"id":57,"name":"Fútbol Virtual","alias":"VirtualFootball","order":173,"game":5},"118":{"id":118,"name":"Carrera Virtual de Carros","alias":"VirtualCarRacing","order":177,"game":4},"150":{"id":150,"name":"Virtual Bicycle","alias":"VirtualBicycle","order":178,"game":5},"174":{"id":174,"name":"The Penalty Kicks","alias":"ThePenaltyKicks","order":128,"game":5}}}}}*/

/**
 * Este script procesa datos relacionados con eventos deportivos, mercados, juegos, competencias, regiones y deportes.
 * Utiliza filtros y reglas para obtener información personalizada desde la base de datos.
 *
 * @param object $params Objeto JSON que contiene los siguientes parámetros:
 * @param object $params->what Objeto que define qué datos se desean obtener.
 *    Ejemplo:
 *    {
 *      "event": ["id", "name", "price"],
 *      "market": ["id", "name"],
 *      "game": ["id", "team1_name", "team2_name"],
 *      "competition": ["id", "name"],
 *      "region": ["id", "name"],
 *      "sport": ["id", "name"]
 *    }
 * @param object $params->where Objeto que define las condiciones de filtrado para los datos solicitados.
 *    Ejemplo:
 *    {
 *      "event": {"id": {"@in": [1, 2, 3]}},
 *      "market": {"id": {"@in": [10, 20]}},
 *      "game": {"id": {"@in": [100, 200]}},
 *      "competition": {"id": {"@in": [1000]}},
 *      "region": {"id": {"@in": [500]}},
 *      "sport": {"id": {"@in": [50]}}
 *    }
 *
 * @return array $response Respuesta estructurada con los siguientes elementos:
 *  - code: Código de estado de la operación (0 para éxito).
 *  - rid: Identificador único de la solicitud.
 *  - data: Contiene los datos solicitados organizados según el parámetro "what".
 *    Ejemplo:
 *    {
 *      "subid": "629555555151",
 *      "data": {
 *        "sport": {
 *          "50": {"id": 50, "name": "Fútbol", "alias": "Football"}
 *        }
 *      },
 *      "dataSub": {
 *        "subid": "629555555151",
 *        "first": "sport",
 *        "end": "game",
 *        "id": [50]
 *      }
 *    }
 */

exit();
$subid = "-";
$subidsum = 555555;

$objfin = "";
$objfirst = "";
$objinicio = array();

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array();

$what = $json->params->what;
$where = $json->params->where;
$result_array_final = array();


/*  if (false && is_array($what->event)) {
  $campos = "";
  $cont = 0;

  $rules = [];


  $filtro = array("rules" => $rules, "groupOp" => "AND");
  $jsonfiltro = json_encode($filtro);


  $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
  $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta.*,int_apuesta_detalle.*,int_evento_apuesta.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
  $apuestas = json_decode($apuestas);


  $final = array();
  $arrayd = array();
  $apuestaid = "";

  foreach ($apuestas->data as $apuesta) {

      $array = array();

      foreach ($what->market as $campo) {


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
      if (oldCount($what->market) == 0) {

          switch ($apuesta->{"int_evento_detalle.tipo"}) {

              case "TEAM1":

                  $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};


                  break;

              case "TEAM2":
                  $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                  break;

          }
      }

      if ($apuestaid != intval($evento->{"int_apuesta.apuesta_id"}) && $apuestaid != "") {
          $arrayd["id"] = $apuestaid;
          $arrayd["market_type"] = $evento->{"int_apuesta.abreviado"};
          $arrayd["name"] = $evento->{"int_apuesta.nombre"};
          $arrayd["name_template"] = $evento->{"int_apuesta.nombre"};
          $arrayd["optimal"] = false;
          $arrayd["order"] = 1000;
          $arrayd["point_sequence"] = 0;
          $arrayd["sequence"] = 0;
          $arrayd["cashout"] = 0;
          $arrayd["col_count"] = 2;

          if (is_array($what->competition)) {

              $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
          } else {
              $result_array["market"][$eventoid] = $arrayd;
          }
          $arrayd = array();
      }
      $eventoid = intval($evento->{"int_evento.evento_id"});


      //array_push($final, $array);

  }

  $arrayd["game_number"] = $eventoid;
  $arrayd["id"] = $eventoid;
  $arrayd["start_ts"] = $evento->{"int_evento.fecha"};


  if (is_array($what->competition)) {

      $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
  } else {
      $result_array["game"][$eventoid] = $arrayd;
  }

  $result_array_final = $result_array;

}*/

if ($what->event != "" && $what->event != undefined) {

    /* Se inicializan un arreglo y variables para almacenar campos y reglas. */
    $result_array = array();

    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->event != "" && $where->event != undefined) {

        foreach ($where->event as $key => $value) {


            /* asigna valores a la variable $field según el valor de $key. */
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

            /* verifica y concatena elementos de un array si está definido y no vacío. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            /* Verifica si $value es numérico y lo agrega a $rules si $field no está vacío. */
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


            /* Código que asigna valores a la variable $field según el valor de $key. */
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

            /* verifica y concatena elementos de un array en una cadena. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            /* verifica si un valor es numérico y lo agrega a reglas. */
            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

            }

        }
    }

    /* Se configuran reglas de filtro en un arreglo y se convierten a JSON. */
    array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();

    /* Se obtienen y decodifican datos de apuestas en formato JSON para su procesamiento. */
    $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
    $apuestas = json_decode($apuestas);


    $final = array();

    foreach ($apuestas->data as $apuesta) {


        /* Se crean dos arrays vacíos en PHP: `$array` y `$arrayd`. */
        $array = array();
        $arrayd = array();

        foreach ($what->event as $campo) {
            switch ($campo) {
                case "id":
                    /* Asigna un valor entero a un elemento de un array basado en una apuesta. */

                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                    break;

                case "externo_id":
                    /* Asigna un ID de proveedor a un array, convertido a entero. */

                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalleproveedor_id"});

                    break;

                case "name":
                    /* Asigna una traducción a un campo de un array basado en una opción de apuesta. */

                    $arrayd[$campo] = traduccionMercado($apuesta->{"int_apuesta_detalle.opcion"});

                    break;

                case "type":
                    /* Asigna el valor de opción_id a un elemento del array según un caso específico. */

                    $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                    break;

                case "type_1":
                    /* Asigna un valor a un elemento del array basado en una opción de apuesta. */

                    $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                    break;
                case "price":
                    /* Asignación de valor a un elemento en un arreglo basado en una condición específica. */

                    $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                    break;

            }

        }

        if (oldCount($what->event) == 0) {

            /* Se asignan valores a un arreglo asociativo basado en propiedades de un objeto "apuesta". */
            $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
            $arrayd["name"] = ucwords(strtolower(traduccionMercado($apuesta->{"int_apuesta_detalle.opcion"})));
            $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
            $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
            $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
            $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};

            /* Asigna valores a un arreglo basado en condiciones sobre la variable "name". */
            $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};

            if (strpos($arrayd["name"], 'Under') !== false) {
                $arrayd["base"] = str_replace("Under ", "", $arrayd["name"]);

                $arrayd["name"] = traduccionMercado("Under");
                $arrayd["type"] = traduccionMercado("Under ({h})");

            }


            /* Reemplaza "Over" en un nombre y actualiza propiedades en un arreglo. */
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

        /* agrega un ID de apuesta a un array y establece un precio condicional. */
        array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
        $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
        $objfirst = "event";

        if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
            $arrayd["price"] = "1";
        }

//                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;



        /* actualiza estructuras de datos basadas en apuestas y mercados. */
        if (is_array($what->market)) {

            $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
            $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
        } else {
            $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
        }


        /* Se asigna la cadena "event" a la variable $objfin en PHP. */
        $objfin = "event";

    }

    $result_array_final = $result_array;

}


if ($what->market != "" && $what->market != undefined) {

    /* Se inicializan variables y un array para almacenar reglas y campos en PHP. */
    $result_array = array();

    $campos = "";
    $cont = 0;

    $rules = [];

    /* Se define un filtro JSON y se inicializa un objeto de evento de apuesta. */
    array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntEventoApuesta = new IntEventoApuesta();

    /* obtiene apuestas de un evento y las decodifica en formato JSON. */
    $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);
    $apuestas = json_decode($apuestas);


    $final = array();

    foreach ($apuestas->data as $apuesta) {


        /* Se crean dos arreglos vacíos en PHP: `$array` y `$arrayd`. */
        $array = array();
        $arrayd = array();

        foreach ($what->market as $campo) {
            switch ($campo) {
                case "id":
                    /* Asigna el ID del evento de apuesta convertido a entero en un arreglo. */

                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                    break;

                case "name":
                    /* Asigna el valor de "int_apuesta.nombre" al índice $campo en $arrayd. */

                    $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                    break;

                case "alias":
                    /* Asigna un valor del objeto a un arreglo basado en una clave específica. */

                    $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                    break;

                case "order":
                    /* Asigna un valor entero a un arreglo utilizando datos de una apuesta específica. */

                    $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                    break;

                case "type":
                    /* asigna un valor a un arreglo basado en una condición específica. */

                    $arrayd[$campo] = ($apuesta->{"int_apuesta.abreviado"});


                    break;

            }

        }


        /* inicializa un arreglo cuando no hay recuento en el mercado indicado. */
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

        /* Verifica si 'event' es un array y procesa datos según su contenido. */
        $seguir = true;
        if (is_array($what->event)) {

            $arrayd["event"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"];
            $arrayd["col_count"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["col_count"];
            $arrayd["type"] = $apuesta->{"int_apuesta.abreviado"};
            if ($result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"] == "") {
                $seguir = true;

            }
        }

        /* gestiona apuestas y organiza datos en un arreglo según condiciones. */
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



    /* Asignación de un arreglo a otra variable y definición de un objeto como "market". */
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


            /* asigna valores a la variable $field según el valor de $key. */
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

            /* verifica y concatena elementos de un array en una cadena. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            /* Valida si el valor es numérico y lo agrega a las reglas si campo no está vacío. */
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


            /* Asigna valores a la variable $field según el valor de $key. */
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

            /* Verifica si '@in' está definido y no vacío, luego concatena sus valores. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            /* Se verifica si un valor es numérico y se agrega a reglas si se proporciona un campo. */
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


            /* Variables inicializadas como cadenas vacías para almacenamiento de datos y operaciones. */
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    /* Asigna el campo evento_id a la variable $field si el caso es "id". */

                    $field = "int_evento.evento_id";
                    break;

                case "name":
                    /* Estructura de control "case" que no ejecuta acción y finaliza. */


                    break;

                case "alias":
                    /* muestra un caso en un switch que no realiza ninguna acción. */


                    break;

                case "order":
                    /* muestra un bloque de un switch que maneja el caso "order". */


                    break;

                case "promoted":
                    /* asigna valores para comparar eventos promocionados en una base de datos. */

                    $field = "int_evento.promocionado";
                    $op = "eq";
                    $data = "S";

                    break;

            }

            /* Verifica si el valor '@in' existe y concatena sus elementos en una cadena. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            /* verifica si un valor es numérico y lo agrega a reglas. */
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


    /* Se añaden reglas de filtrado y se convierten a formato JSON. */
    array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntEventoDetalle = new IntEventoDetalle();

    /* Se obtienen detalles de eventos y se convierten a formato JSON. */
    $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
    $eventos = json_decode($eventos);


    $final = array();
    $arrayd = array();

    /* inicializa una variable vacía y un array asociativo en PHP. */
    $eventoid = "";
    $arrayd["info"]["virtual"] = array();

    foreach ($eventos->data as $evento) {


        /* Se declara un arreglo vacío en PHP para almacenar elementos posteriormente. */
        $array = array();
//$arrayd["info"]["virtual"] = $evento;

        if ($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid != "") {

            /* asigna valores a un array asociativo relacionado con eventos deportivos. */
            $arrayd["game_number"] = $eventoid;
            $arrayd["id"] = $eventoid;
            $arrayd["start_ts"] = strtotime($eventoA->{"int_evento.fecha"});
            $arrayd["type"] = 0;
            $arrayd["tv_type"] = 29;
            $arrayd["video_id"] = $eventoid;

            /* Asignación de valores y verificación de estado de un evento en PHP. */
            $arrayd["type"] = 0;
            $arrayd["markets_count"] = 63;

            $is_blocked = 0;

            if ($eventoA->{"int_evento.estado"} != "A") {
                $is_blocked = true;
            }


            /* Asigna valores a un array basado en condiciones de acuerdo a un objeto y su propiedad. */
            $arrayd["is_blocked"] = $is_blocked;

            if (is_array($what->market)) {

                $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

            }



            /* Condicional que organiza datos en un arreglo según si "competition" es un array. */
            if (is_array($what->competition)) {

                $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
            } else {
                $result_array["game"][$eventoid] = $arrayd;


            }


            /* verifica si `$objfirst` es "game" y agrega un ID a `$objinicio`. */
            if ($objfirst == "game") {
                array_push($objinicio, intval($eventoA->{"int_evento.evento_id"}));

            }

            $arrayd = array();
        }

        foreach ($what->game as $campo) {


            switch ($campo) {

                case "team1_name":
                    /* Asigna valor a un arreglo si el tipo de evento es "TEAM1". */


                    if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                        $arrayd["team1_id"] = 1;

                    }


                    break;

                case "team2_name":
                    /* Asigna el valor de TEAM2 al array si se cumple la condición. */

                    if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                        $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                        $arrayd["team2_id"] = 2;

                    }

                    break;

                case "text_info":
                    /* evalúa el tipo de evento y asigna un valor a un arreglo. */

                    if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
// $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                    }
                    break;

                case "externo_id":
                    /* Asigna un valor entero de un objeto a un elemento de un arreglo. */

                    $arrayd[$campo] = intval($apuesta->{"int_evento.eventoproveedor_id "});

                    break;

            }


        }
        if (oldCount($what->game) == 0) {
            switch ($evento->{"int_evento_detalle.tipo"}) {

                case "TEAM1":


                    /* Asigna valores de un evento a un array para usar en un formato estructurado. */
                    $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                    $arrayd["team1_id"] = 1;

                    $arrayd["info"]["virtual"][0] = array(
                        "AnimalName" => "",
                        "Number" => 1,
                        "PlayerName" => $evento->{"int_evento_detalle.valor"}
                    );


                    /* Reemplaza valores de eventos en un array basado en ciertas condiciones. */
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

                    /* Asignación de valores de un evento a un arreglo en PHP. */
                    $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                    $arrayd["team2_id"] = 2;

                    $arrayd["info"]["virtual"][1] = array(
                        "AnimalName" => "",
                        "Number" => 2,
                        "PlayerName" => $evento->{"int_evento_detalle.valor"}
                    );


                    /* Cambia nombres y tipos de eventos a "valor" si son "lose". */
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

                    /* asigna valores de un evento a un arreglo, eliminando "Racer" de algunas cadenas. */
                    $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                    array_push($arrayd["info"]["virtual"], array(
                        "AnimalName" => str_replace("Racer", "", $evento->{"int_evento_detalle.valor"}),
                        "Number" => 1,
                        "PlayerName" => str_replace("Racer", "", $evento->{"int_evento_detalle.valor"}),
                        "RacerTextureID" => str_replace("Racer", "", $evento->{"int_evento_detalle.id"}),
                        "HumanTextureID" => str_replace("Racer", "", $evento->{"int_evento_detalle.id"})
                    ));

                    /* Itera sobre eventos y actualiza nombres y tipos según condiciones específicas. */
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
                    /* Convierte el valor de evento proveedor a entero y lo asigna al array. */

                    $arrayd[$campo] = intval($apuesta->{"int_evento.eventoproveedor_id "});

                    break;

            }
        }



        /* Convierte el ID del evento a entero y lo asigna a una variable. */
        $eventoid = intval($evento->{"int_evento.evento_id"});
        $eventoA = $evento;

//array_push($final, $array);

    }


    /* Se asignan valores a un arreglo asociativo para un evento específico. */
    $arrayd["game_number"] = $eventoid;
    $arrayd["id"] = $eventoid;
    $arrayd["start_ts"] = strtotime($eventoA->{"int_evento.fecha"});
    $arrayd["tv_type"] = 29;
    $arrayd["video_id"] = $eventoid;
    $arrayd["type"] = 0;

    /* Se asigna un ID y se verifica si el evento está bloqueado según su estado. */
    $arrayd["externo_id"] = ($eventoA->{"int_evento.eventoproveedor_id"});

    $is_blocked = 0;

    if ($evento->{"int_evento.estado"} != "A") {
        $is_blocked = true;
    }


    /* Se asigna un valor a "is_blocked" y se verifica si "market" es un array. */
    $arrayd["is_blocked"] = $is_blocked;


    if (is_array($what->market)) {

        $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

    }


    /* Verifica si "competition" es un arreglo y asigna datos basados en condiciones. */
    if (is_array($what->competition)) {

        $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
        if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
//$subid=$subid."501".$evento->{"int_evento.evento_id"};

        }
    } else {
        /* asigna datos a un arreglo y verifica la cantidad de juegos. */

        $result_array["game"][$eventoid] = $arrayd;

        if (oldCount($result_array["game"]) == 1) {
//$subid=$subid."501".$evento->{"int_evento.evento_id"};

        }
    }

    /* agrega un ID de evento a un array si es un juego. */
    if ($objfirst == "game") {
        array_push($objinicio, intval($evento->{"int_evento.evento_id"}));

    }

    $objfin = "game";


    /* Se asigna el contenido de `$result_array` a `$result_array_final`. */
    $result_array_final = $result_array;

}

if ($what->competition != "" && $what->competition != undefined) {

    /* Se inicializan un arreglo y variables para almacenar campos y reglas. */
    $result_array = array();

    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->competition != "" && $where->competition != undefined) {

        foreach ($where->competition as $key => $value) {


            /* Se declaran variables vacías para almacenar campo, operación y datos. */
            $field = "";
            $op = "";
            $data = "";

            switch ($key) {
                case "id":
                    /* Se asigna el campo "competencia_id" a la variable $field en un caso específico. */

                    $field = "int_competencia.competencia_id";
                    break;

                case "name":
                    /* pertenece a una estructura de switch, pero no realiza ninguna acción. */


                    break;

                case "alias":
                    /* es un fragmento de una estructura de selección para el caso "alias". */


                    break;

                case "order":
                    /* Estructura de un caso en un switch, sin acciones específicas para "order". */


                    break;

                case "promoted":
                    /* Define un campo de base de datos y establece condiciones para un caso específico. */

                    $field = "int_competencia.promocionado";
                    $op = "eq";
                    $data = "S";

                    break;

            }

            /* verifica y concatena elementos de un arreglo en una cadena delimitada por comas. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }



            /* Agrega reglas a un arreglo si el campo no está vacío. */
            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

            }

        }

    }
    if ($where->sport != "" && $where->sport != undefined) {

        foreach ($where->sport as $key => $value) {


            /* Código que asigna valores a variables según el valor de la variable $key. */
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

            /* Condición que verifica y concatena elementos de un array en una cadena. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }


            /* verifica si un valor es numérico y lo añade a reglas. */
            if (is_numeric($value)) {
                $op = "eq";
                $data = $value;
            }

            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

            }


        }
    }


    /* Se genera un filtro en JSON y se obtienen competencias personalizadas. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntCompetencia = new IntCompetencia();
    $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);

    /* convierte un JSON a un array en PHP. */
    $competencias = json_decode($competencias);


    $final = array();

    foreach ($competencias->data as $competencia) {


        /* Se crean dos arrays vacíos en PHP, llamados $array y $arrayd. */
        $array = array();
        $arrayd = array();

        foreach ($what->competition as $campo) {
            switch ($campo) {
                case "id":
                    /* Asigna un valor entero a un índice de array basado en un campo específico. */

                    $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                    break;

                case "name":
                    /* Asigna el nombre de "int_competencia" a un campo en el arreglo "$arrayd". */

                    $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                    break;

                case "alias":
                    /* Asigna un valor abreviado a un campo en el array según la competencia. */

                    $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                    break;

                case "order":
                    /* Asigna un valor entero a un array basado en una competencia específica. */

                    $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                    break;

            }

        }

//                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;

        /* Verifica si "game" en un array existe; de lo contrario, detiene el proceso. */
        $seguir = true;

        if (is_array($what->game)) {

            $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];
            if ($arrayd["game"] == null) {
                $seguir = false;
            }
        }

        /* Condicionales que agregan datos a un arreglo según ciertas condiciones de competencia. */
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


    /* Código verifica si hay una competencia y asigna un valor a $subid. */
    if (oldCount($competencias->data) == 1) {
//$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

    }

    $objfin = "competition";


    /* Se asigna el contenido de `$result_array` a `$result_array_final`. */
    $result_array_final = $result_array;

}

if ($what->region != "" && $what->region != undefined) {

    /* Código inicializa un arreglo vacío y variables para almacenar reglas y contadores. */
    $result_array = array();
    $campos = "";
    $cont = 0;

    $rules = [];

    if ($where->region != "" && $where->region != undefined) {

        foreach ($where->competition as $key => $value) {


            /* establece variables y usa un switch para asignar valores basados en el "key". */
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

            /* verifica y concatena elementos de un array en una cadena. */
            if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                $op = "in";
                $data_array = $value->{'@in'};
                $data = "";

                foreach ($data_array as $item) {
                    $data = $data . $item . ",";
                }
                $data = trim($data, ",");
            }



            /* Agrega una regla al array si el campo no está vacío. */
            if ($field != "") {
                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

            }

        }
    }


    /* codifica un filtro en JSON y obtiene regiones personalizadas de una base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    $IntRegion = new IntRegion();
    $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);

    /* Decodifica datos JSON en PHP y crea un array vacío para almacenar resultados. */
    $regiones = json_decode($regiones);


    $final = array();

    foreach ($regiones->data as $region) {


        /* Se definen dos arreglos vacíos mediante la función `array()` en PHP. */
        $array = array();
        $arrayd = array();

        foreach ($what->region as $campo) {
            switch ($campo) {
                case "id":
                    /* Asigna un valor entero a un elemento de arreglo basado en un ID de región. */

                    $arrayd[$campo] = intval($region->{"int_region.region_id"});

                    break;

                case "name":
                    /* Asigna el nombre de la región al arreglo según el campo especificado. */

                    $arrayd[$campo] = $region->{"int_region.nombre"};

                    break;

                case "alias":
                    /* Asigna un valor abreviado de región a un campo en un arreglo. */

                    $arrayd[$campo] = $region->{"int_region.abreviado"};

                    break;

                case "order":
                    /* Convierte el valor de region_id a entero y lo asigna a un arreglo. */

                    $arrayd[$campo] = intval($region->{"int_region.region_id"});

                    break;

            }

        }

        /* Verifica si 'competition' existe en un array; si no, cambia 'seguir' a false. */
        $seguir = true;


        if (is_array($what->competition)) {

            $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];
            if ($arrayd["competition"] == null) {
                $seguir = false;
            }
        }



        /* gestiona datos sobre deportes y regiones en una estructura condicional. */
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


    /* Condicional que verifica si hay una región y asigna un valor a $subid. */
    if (oldCount($regiones->data) == 1) {
//$subid=$subid."301".$region->{"int_region.region_id"};

    }

    $objfin = "region";


    /* Se asigna el contenido de $result_array a $result_array_final. */
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
        /* Concatena valores a la variable $subid basado en la condición "event". */

        $subid = $subid . "324" . $subidsum . "517";
        break;

    case "market":
        /* Concatena valores a la variable $subid si el caso es "market". */

        $subid = $subid . "435" . $subidsum . "423";
        break;

    case "game":
        /* Concatenación de cadenas en una variable según el caso "game". */

        $subid = $subid . "614" . $subidsum . "421";
        break;

    case "competition":
        /* Concatenación de cadenas en PHP para el caso "competition" en un switch. */

        $subid = $subid . "241" . $subidsum . "172";
        break;

    case "region":
        /* concatena valores a la variable $subid si "region" es seleccionado. */

        $subid = $subid . "843" . $subidsum . "495";
        break;

    case "sport":
        /* Concatena valores a la variable $subid si el caso es "sport". */

        $subid = $subid . "629" . $subidsum . "151";
        break;
}


/* asigna datos a un arreglo de respuesta estructurado en PHP. */
$response["data"]["subid"] = $subid;
$response["data"]["data"] = $result_array_final;
$response["data"]["dataSub"] = array(
    "subid" => $subid,
    "first" => $objfin,
    "end" => $objfirst,
    "id" => $objinicio
);


/*
                    $SQLCustom= new \Backend\mysql\IntEventoApuestaDetalleMySqlDAO();
                    $from="";
                    $select="";
                    $rules = [];


                    if (is_array($what->game)){

                        if($from == ""){
                            $select = "int_evento_detalle.*,int_evento.*";
                            $from = " int_evento_detalle INNER JOIN int_evento ON (int_evento_detalle.evento_id=int_evento.evento_id) ";
                        }else{
                            $select = $select . ",int_evento_detalle.*,int_evento.*";
                            $from =$from. " int_evento_detalle INNER JOIN int_evento ON (int_evento_detalle.evento_id=int_evento.evento_id) ";
                        }
                    }

                    if (is_array($what->competition)){

                        if($from == ""){
                            $select = "int_competencia.*";
                            $from = " int_competencia ";
                        }else{
                            $select = $select . ",int_competencia.*";
                            $from =$from. " INNER JOIN int_competencia ON (int_competencia.competencia_id=int_evento.competencia_id)";
                        }
                    }

                    if (is_array($what->region)){

                        if($from == ""){
                            $select = "int_region.*";
                            $from = " int_region ";
                        }else{
                            $select = $select . ",int_region.*";
                            $from =$from. " INNER JOIN int_region ON (int_competencia.region_id=int_region.region_id)";
                        }
                    }


                    if (is_array($what->sport)){
                        if($from == ""){
                            $select = "int_deporte.*";
                            $from = " int_deporte ";
                        }else{
                            $select = $select . ",int_region.*";
                            $from =$from. " INNER JOIN int_region ON (int_deporte.region_id=int_region.region_id)";
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

                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                        }
                    }



                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $jsonfiltro = json_encode($filtro);



                        $result = $SQLCustom->queryCustom($select,$from, "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);
                    $result = json_decode($result);
                    $result_array=array();

                    if (is_array($what->sport)) {
                        $sport=array();

                        foreach ($result->data as $result) {

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

                            $sport[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                        }

                        $result_array["sport"]=$sport;

                    }

                    if (is_array($what->game)){

                        if($from == ""){
                            $select = "int_evento_detalle.*,int_evento.*";
                            $from = " int_evento ";
                        }else{
                            $select = $select . ",int_evento.*";
                            $from =$from. " INNER JOIN int_competencia ON (int_competencia.region_id=int_region.region_id)";
                        }
                    }

                    if (is_array($what->market)) {
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

                                if(is_numeric($value)){
                                    $op = "eq";
                                    $data = $value;
                                }

                                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

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

                                if(is_numeric($value)){
                                    $op = "eq";
                                    $data = $value;
                                }

                                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

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

                                if(is_numeric($value)){
                                    $op = "eq";
                                    $data = $value;
                                }

                                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                            }
                        }

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $jsonfiltro = json_encode($filtro);


                        $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
                        $eventos = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*,int_apuesta.*,int_evento_apuesta.*,int_evento.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                        $eventos = json_decode($eventos);


                        $final = array();
                        $arrayd = array();
                        $eventoid="";

                        foreach ($eventos->data as $evento) {

                            $array = array();

                            foreach ($what->game as $campo) {



                                switch ($campo) {

                                    case "team1_name":

                                        if($evento->{"int_evento_detalle.tipo"}=== "TEAM1"){
                                            $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                                        }


                                        break;

                                    case "team2_name":
                                        if($evento->{"int_evento_detalle.tipo"}== "TEAM2"){
                                            $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                        }
                                        break;

                                    case "text_info":
                                        if($evento->{"int_evento_detalle.tipo"}== "TEAM1"){
                                            // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                        }
                                        break;

                                }


                            }
                            if($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid!=""){
                                $arrayd["game_number"] = $eventoid;
                                $arrayd["id"] = $eventoid;
                                $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                                $final[$eventoid] = $arrayd;
                                $arrayd = array();
                            }
                            $eventoid=intval($evento->{"int_evento.evento_id"});


                            //array_push($final, $array);

                        }

                        $arrayd["game_number"] = $eventoid;
                        $arrayd["id"] = $eventoid;
                        $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                        $final[$eventoid] = $arrayd;

                        $Data = array();
                        $Data["game"] = $final;


                        $response["data"]["data"] = $Data;

                    }



                    if ($what->game != "" && $what->game != undefined && is_array($what->game)) {
                        $campos = "";
                        $cont = 0;

                        foreach ($what->game as $campo) {

                            switch ($campo) {

                                case "game_number":
                                    $campo = "int_evento.evento_id";

                                    break;

                                case "team1_name":
                                    $campo = "int_evento_detalle.valor";

                                    break;

                                case "team2_name":
                                    $campo = "";

                                    break;

                                case "id":
                                    $campo = "int_evento.evento_id";

                                    break;

                                case "start_ts":
                                    $campo = "int_evento.fecha";

                                    break;

                                case "text_info":
                                    $campo = "int_competencia.nombre";

                                    break;

                            }

                                if ($cont == 0) {
                                    $campos = $campo;
                                    $cont = $cont + 1;
                                } else {
                                    $campos = $campos . "," . $campo;

                                }



                        }
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

                                if(is_numeric($value)){
                                    $op = "eq";
                                    $data = $value;
                                }

                                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

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

                                if(is_numeric($value)){
                                    $op = "eq";
                                    $data = $value;
                                }

                                array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                            }
                        }

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $jsonfiltro = json_encode($filtro);


                        $IntEventoDetalle = new IntEventoDetalle();
                        $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.eventodetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                        $eventos = json_decode($eventos);


                        $final = array();
                        $arrayd = array();
                        $eventoid="";

                        foreach ($eventos->data as $evento) {

                            $array = array();

                            foreach ($what->game as $campo) {



                                switch ($campo) {

                                    case "team1_name":

                                        if($evento->{"int_evento_detalle.tipo"}=== "TEAM1"){
                                            $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                                        }


                                        break;

                                    case "team2_name":
                                        if($evento->{"int_evento_detalle.tipo"}== "TEAM2"){
                                            $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                        }
                                        break;

                                    case "text_info":
                                        if($evento->{"int_evento_detalle.tipo"}== "TEAM1"){
                                           // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                        }
                                        break;

                                }


                            }
                            if($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid!=""){
                                $arrayd["game_number"] = $eventoid;
                                $arrayd["id"] = $eventoid;
                                $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                                $final[$eventoid] = $arrayd;
                                $arrayd = array();
                            }
                            $eventoid=intval($evento->{"int_evento.evento_id"});


                            //array_push($final, $array);

                        }

                        $arrayd["game_number"] = $eventoid;
                        $arrayd["id"] = $eventoid;
                        $arrayd["start_ts"] = $evento->{"int_evento.fecha"};

                        $final[$eventoid] = $arrayd;

                        $Data = array();
                        $Data["game"] = $final;


                        $response["data"]["data"] = $Data;

                    }*/

