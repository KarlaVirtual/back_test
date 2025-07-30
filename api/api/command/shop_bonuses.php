<?php


use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\MandanteDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Pais;
use Backend\dto\UsuarioLealtad;

/**
 *
 * command/shop_bonuses
 *
 * Archivo de procesamiento de la tienda lealtad interna
 *
 * Este script maneja la recuperación y filtrado de información relacionada con
 * programas de lealtad interna basados en diversos criterios y parámetros de usuario.
 *
 * @param int $MaxRows Límite de filas a recuperar.
 * @param string $OrderedItem Columna a order para la consulta.
 * @param int $SkeepRows Desplazamiento para paginación.
 * @param string $StateType Tipo de estado a filtrar.
 * @param string $tate Estado de los registros ('I' o 'A').
 * @param int $site_id ID del sitio asociado al usuario.
 * @param string $Country País del usuario, si no se obtiene desde su sesión.
 *
 *
 * @return objeto $response Devuelve un conjunto de datos de lealtad interna con detalles organizados.
 *       - *code* (int):    Código de estado de la operación (0 = éxito, otro valor indica error).
 *       - *rid*  (string): Identificador único de la solicitud procesada.
 *       - *data* (mixed):  Datos procesados devueltos en la respuesta, puede variar según la operación.
 *
 * @throws Exception Si hay errores en la obtención de datos o en la manipulación de objetos.
 *
 *
 * @see no
 * @since no
 */



/* inicializa parámetros y objetos relacionados con usuarios y límites de memoria. */
$params = $json->params;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$MandanteDetalle = new MandanteDetalle();
ini_set('memory_limit', '-1');

$rules = [];

/* crea filtros en formato JSON para consultar detalles de mandantes. */
array_push($rules, array("field" => "mandante_detalle.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$mandanteDetalles = $MandanteDetalle->getMandanteDetallesCustom("mandante_detalle.valor as valor, clasificador.abreviado as abreviado ", "mandante_detalle.manddetalle_id", "asc", '0', '1000', $json2, true);


/* decodifica un JSON y crea un arreglo vacío para lealtad. */
$mandanteDetalles = json_decode($mandanteDetalles);

$loyalty = array();
foreach ($mandanteDetalles->data as $key => $value) {

    switch ($value->{'clasificador.abreviado'}) {
        case "POINTSLEVELONE":
/* asigna un valor a una variable según la condición "POINTSLEVELONE". */


            $ValorNivel1 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELTWO":
/* Asigna el valor del detalle del mandante a una variable para el nivel dos. */

            $ValorNivel2 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELTHREE":
/* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel3 en un caso específico. */

            $ValorNivel3 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELFOUR":
/* Asigna el valor de 'mandante_detalle.valor' a ValorNivel4 en un caso específico. */

            $ValorNivel4 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELFIVE":
/* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel5 si el caso es POINTSLEVELFIVE. */

            $ValorNivel5 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELSIX":
/* Asigna un valor a $ValorNivel6 desde un objeto según la clave "POINTSLEVELSIX". */

            $ValorNivel6 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELSEVEN":
/* Extrae el valor de 'mandante_detalle.valor' en el caso POINTSLEVELSEVEN. */

            $ValorNivel7 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELEIGHT":
/* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel8 para "POINTSLEVELEIGHT". */

            $ValorNivel8 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELNIVE":
/* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel9 en el caso específico. */

            $ValorNivel9 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELTEN":
/* Se asigna un valor a la variable según la clave "POINTSLEVELTEN". */

            $ValorNivel10 = $value->{'mandante_detalle.valor'};
            break;
    }

}



/* configura límites y estados para paginación de datos en una consulta. */
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;
$State = ($params->State == "I") ? 'I' : 'A';


/* asigna el país del usuario o del parámetro si está vacío. */
$site_id = $params->site_id;


$Country = $Usuario->paisId;

if ($Country == '') {
    $Country = $params->Country;

}



/* Se define un arreglo vacío y se crean instancias de clases específicas. */
$rules = [];

$jsonfiltro = json_encode($filtro);

$LealtadInterna = new LealtadInterna();
$LealtadDetalle = new LealtadDetalle();



/* Se crea un conjunto de reglas basado en un valor específico de usuario. */
$rules = [];

if ($UsuarioMandante->usuarioMandante == 73818) {
    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

} else {
/* Agrega una regla para la comparación del estado de lealtad interna. */

    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

}

/* Agrega reglas para filtrar fechas y mandante en un array. */
array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le")); //menor igual
array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge")); //mayor igual


array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $site_id, "op" => "eq"));
$monedaFinal = $Usuario->moneda;

if ($Country != "" && $Usuario->mandante != '8') {
    
/* Intenta crear un objeto 'Pais', manejando excepciones al fallar la inicialización. */
try {
        $Pais = new Pais($Country);

    } catch (Exception $e) {
        $Pais = new Pais('', $Country);

    }
    
/* Se añaden reglas de validación y se determina la moneda basada en el país. */
array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "lealtad_detalle.valor", "data" => $Pais->paisId, "op" => "eq"));

    if ($monedaFinal == '') {
        if ($Pais->paisId == 173) {
            $monedaFinal = 'PEN';
        }
        if ($Pais->paisId == 102) {
            $monedaFinal = 'HNL';
        }

    }
    
/* Imprime el valor de $monedaFinal si la variable de entorno 'debug' es verdadera. */
if ($_ENV['debug']) {
        print_r($monedaFinal);
    }
} else {
/* agrega una regla de comparación al arreglo si no se cumple una condición. */

    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

}


//array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));


/* agrega reglas de exclusión basadas en condiciones específicas del usuario. */
if ($UsuarioMandante->getUsuarioMandante() != 73818) {
    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "499", "op" => "ne"));
    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "511", "op" => "ne"));

}
//array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
//array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

if ($_ENV['debug']) {
    print_r($rules);
}

/* Se configura un filtro con reglas y opciones de paginación iniciales. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Si no hay un límite, establece $MaxRows en mil millones y codifica $filtro. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}


$jsonfiltro = json_encode($filtro);



/* Se recuperan y procesan detalles de lealtad en formato JSON. */
$lealtad = $LealtadDetalle->getLealtadDetallesCustom("lealtad_interna.*", "lealtad_interna.orden DESC,lealtad_interna.lealtad_id", "ASC", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);

$lealtad = json_decode($lealtad);

$final = [];

$cantidadRedimidos = 0;


/* permite acceder a información y realizar tareas basadas en datos previos a octubre 2023. */
$arrayRedimidos = array();

foreach ($lealtad->data as $key => $value) {

    $array = [];
    if ($value->{"lealtad_interna.bono_id"} == 0) {
        $array['category'] = 2;
    } else {
        try {

            $BonoInterno = new \Backend\dto\BonoInterno($value->{"lealtad_interna.bono_id"});
            switch ($BonoInterno->tipo) {
                case 5:
                case 8:
                    $array['category'] = 0;
                    break;
                case 6:
                case 4:
                    $array['category'] = 1;
                    break;
                case 2:
                case 3:
                    $array['category'] = 2;
                    break;
            }
        } catch (Exception $e) {

        }
    }

    try {
        /* Maneja las verticales para la sección de regalos y su lógica */
        $VERTICALREGALO = new \Backend\dto\LealtadDetalle('', $value->{"lealtad_interna.lealtad_id"}, 'VERTICALREGALO');
        // 0 casino
        // 1 deportivas
        // 2 premios fisco
        switch ($VERTICALREGALO->valor) {
            case 0:
                $array['category'] = 1;
                break;
            case 1:
            case 2:
            case 3:
                $array['category'] = 0;
                break;
            default:
                $array['category'] = 2;
                break;

        }
    } catch (Exception $e) {
    }

    /* Prepara salida de datos con los consultados por el recurso*/
    $array["id"] = $value->{"lealtad_interna.lealtad_id"};
    $array["title"] = $value->{"lealtad_interna.nombre"};
    $array["subTitle"] = $value->{"lealtad_interna.descripcion"};
    $array["Order"] = $value->{"lealtad_interna.orden"};
    $array["state"] = 1;
    /*switch ($value->{"lealtad_interna.tipo"}) {
       case "2":
           $array["Type"] = array(
               "Id" => $value->{"lealtad_interna.tipo"},
               "Name" => "Lealtad Deposito",
               "TypeId" => $value->{"lealtad_interna.tipo"}
           );

           break;

       case "3":
           $array["Type"] = array(
               "Id" => $value->{"lealtad_interna.tipo"},
               "Name" => "Lealtad No Deposito",
               "TypeId" => $value->{"lealtad_interna.tipo"}
           );

           break;

       case "4":
           $array["Type"] = array(
               "Id" => $value->{"lealtad_interna.tipo"},
               "Name" => "Lealtad Cash",
               "TypeId" => $value->{"lealtad_interna.tipo"}
           );

          break;


      case "6":
          $array["Type"] = array(
              "Id" => $value->{"lealtad_interna.tipo"},
              "Name" => "Freebet",
              "TypeId" => $value->{"lealtad_interna.tipo"}
          );

          break;


  }

*/
    $games = "";


    $rules = [];


    array_push($rules, array("field" => "lealtad_detalle.lealtad_id", "data" => $array["id"], "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;

    $OrderedItem = 1;

    $MaxRows = 1000000000;

    $jsonfiltrodetalles = json_encode($filtro);

    /* Validaciones de detalles del regalo */
    $lealtaddetalles = $LealtadDetalle->getLealtadDetallesCustom("lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");

    $lealtaddetalles = json_decode($lealtaddetalles);

    $needSubscribe = false;
    $userpais = false;
    $userpaisCont = 0;

    /* Se recorren los detalles inherentes al regalo para la validación de las condiciones a cumplir */
    foreach ($lealtaddetalles->data as $key2 => $value2) {


        switch ($value2->{"lealtad_detalle.tipo"}) {

            /* Si el usuario necesita subscribirse */
            case "USERSUBSCRIBE":

                if ($value2->{"lealtad_detalle.valor"} == 0) {

                } else {
                    $needSubscribe = true;
                }

                break;

            case "RANKAWARDMAT":

                if ($array['premios'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['premios'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['pos'] = $value2->{"lealtad_detalle.valor"};
                $array2['desc'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"lealtad_detalle.valor3"}));
                $array2['tipo'] = 0;

                array_push($array['premios'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                break;


            case "RANKAWARD":

                if ($array['premios'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['premios'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['pos'] = $value2->{"lealtad_detalle.valor"};
                $array2['desc'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"lealtad_detalle.valor3"}));
                $array2['tipo'] = 1;

                array_push($array['premios'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                break;

            case "RANK":

                if ($array['rangos'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['rangos'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['inicial'] = $value2->{"lealtad_detalle.valor"};
                $array2['final'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"lealtad_detalle.valor3"}));

                array_push($array['rangos'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                break;


            case "RANKLINE":

                if ($array['rangos'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['rangos'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['inicial'] = $value2->{"lealtad_detalle.valor"};
                $array2['final'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"lealtad_detalle.valor3"}));

                array_push($array['rangos'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                $array['typeRank'] = 2;

                break;

            /* url de la imagen principal*/
            case "IMGPPALURL":

                $array['image'] = $value2->{"lealtad_detalle.valor"};

                break;


            case "RANKIMGURL":

                $array['imageRank'] = $value2->{"lealtad_detalle.valor"};

                break;

            /* URL del fondo */
            case "BACKGROUNDURL":

                $array['background'] = $value2->{"lealtad_detalle.valor"};

                break;

            /* Si el usuario necesita subscribirse */
            case "USERSUBSCRIBE":

                if ($value2->{"lealtad_detalle.valor"} == 0) {
                    $needSubscribe = true;
                }

                break;

            /* Valida condicional de pais */
            case "CONDPAISUSER":

                if ($json->session->logueado) {

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                    if ($value2->{"lealtad_detalle.valor"} == $UsuarioMandante->getPaisId()) {
                        $userpais = true;
                    }
                }
                $userpaisCont++;

                break;
            /* Visibilidad del regalo */
            case "VISIBILIDAD":
                $array["type"] = $value2->{"lealtad_detalle.valor"};
                break;
            /* Validación de puntos para el regalo */
            case "PUNTOS":


                if ($value2->{"lealtad_detalle.moneda"} == $monedaFinal) {

                    $array["points"] = intval($value2->{"lealtad_detalle.valor"});


                    if ($Usuario->puntosLealtad < intval($array["points"])) {
                        $array["state"] = 1;
                    } else {
                        $array["state"] = 2;
                    }
                    $Puntos = intval($value2->{"lealtad_detalle.valor"});

                    if (($Puntos >= intval($ValorNivel1)) && ($Puntos < intval($ValorNivel2))) {

                        $array["level"] = 1;
                    }
                    if (($Puntos >= intval($ValorNivel2)) && ($Puntos < intval($ValorNivel3))) {
                        $array["level"] = 2;
                    }
                    if (($Puntos >= intval($ValorNivel3)) && ($Puntos < intval($ValorNivel4))) {
                        $array["level"] = 3;
                    }
                    if (($Puntos >= intval($ValorNivel4)) && ($Puntos < intval($ValorNivel5))) {
                        $array["level"] = 4;
                    }
                    if (($Puntos >= intval($ValorNivel5)) && ($Puntos < intval($ValorNivel6))) {
                        $array["level"] = 5;
                    }
                    if (($Puntos >= intval($ValorNivel6)) && ($Puntos < intval($ValorNivel7))) {
                        $array["level"] = 6;
                    }
                    if (($Puntos >= intval($ValorNivel7)) && ($Puntos < intval($ValorNivel8))) {
                        $array["level"] = 7;
                    }
                    if (($Puntos >= intval($ValorNivel8)) && ($Puntos < intval($ValorNivel9))) {
                        $array["level"] = 8;
                    }
                    if (($Puntos >= intval($ValorNivel9)) && ($Puntos < intval($ValorNivel10))) {
                        $array["level"] = 9;
                    }
                    if ($Puntos >= intval($ValorNivel10)) {
                        $array["level"] = 10;
                    }

                }
                break;
            default:

                break;
        }


    }

    if (!$needSubscribe) {
        $array['userjoin'] = true;
    } else {
        $array['userjoin'] = false;

    }

    if ($value->{"lealtad_interna.puntoventa_propio"} == 1) {

        $array['betshopown'] = true;

    } elseif ($value->{"lealtad_interna.puntoventa_propio"} == null) {

        $array['betshopown'] = false;

    } elseif ($value->{"lealtad_interna.puntoventa_propio"} == 0) {

        $array['betshopown'] = false;

    }


    $usuarioIdLogueado = '';
    if ($json->session->logueado) {
        $usuarioIdLogueado = $json->session->usuario;
    }

    $rules = [];


    array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "usuario_lealtad.lealtad_id", "data" => $array["id"], "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;

    $OrderedItem = 1;

    $MaxRows = 100000000;

    $jsonfiltrodetalles = json_encode($filtro);

    /* Se instancia los usuarios a redimir el regalo. */

    $UsuarioLealtad = new UsuarioLealtad();
    $UsuariosLealtad = $UsuarioLealtad->getUsuarioLealtadCustom("usuario_lealtad.*,max(usuario_lealtad.fecha_crea) fecha_crea", "usuario_lealtad.usulealtad_id", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "usuario_lealtad.lealtad_id");
    $UsuariosLealtad = json_decode($UsuariosLealtad);

    if (floatval($UsuariosLealtad->count[0]->{".count"}) > 0) {
        $cantidadRedimidos++;
    }

    foreach ($UsuariosLealtad->data as $key2 => $value2) {
//retornar estado cuando es A = 3 y cuando es R=4;
        $array["lid"] = $value2->{'usuario_lealtad.usulealtad_id'};

        $estado = ($value2->{'usuario_lealtad.estado'});
        switch ($estado) {
            case 'A':
                $array["state"] = 3;
                break;
            case 'R':
                if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId != 173) {

                    //$array["state"] = 4;
                    array_push($arrayRedimidos, $array["usulealtad_id"]);
                }
                break;
            case 'D':
                if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId != 173) {

                    //$array["state"] = 5;
                    array_push($arrayRedimidos, $array["usulealtad_id"]);
                }
                break;

        }
        $fechaCrea = ($value2->{'.fecha_crea'});

        if ($UsuarioMandante->mandante == 8) {

            // Obtener el día actual de la semana
            $current_day = date('w');

            // Ajustar $current_day para que el lunes sea el inicio de la semana
            // Si hoy es domingo (0), se ajusta a 7 para que se reste correctamente y obtener el lunes anterior
            $adjusted_day = ($current_day == 0) ? 7 : $current_day;

            // Calcular el inicio de la semana (lunes)
            $start_week = date('Y-m-d 00:00:00', strtotime(' - ' . ($adjusted_day - 1) . ' days'));

            // Calcular el fin de la semana (domingo)
            $end_week = date('Y-m-d 23:59:59', strtotime($start_week . ' + 6 days'));


            if ($fechaCrea >= $start_week && $fechaCrea <= $end_week) {
                $array["state"] = 5;


                if ($Usuario->puntosLealtad < intval($array["points"])) {
                    $array["state"] = 1;
                }

            } else {
                $array["state"] = 2;


                if ($Usuario->puntosLealtad < intval($array["points"])) {
                    $array["state"] = 1;
                }

            }


        }

        // array_push($array["ranking"], ($lista_temp2));

    }


    array_push($final, $array);
}

if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 173 && false) {
    if ($UsuarioMandante->usuarioMandante == 2546668) {
        print_r($UsuarioMandante);
        print_r($cantidadRedimidos);
        print_r(PHP_EOL);
        print_r($lealtad->count[0]->{".count"});
    }
    if ($cantidadRedimidos >= intval($lealtad->count[0]->{".count"})) {

        array_multisort($arrayRedimidos, SORT_DESC, array_keys($arrayRedimidos));

        $ModRest = ($cantidadRedimidos % intval($lealtad->count[0]->{".count"}));
        $ModRestSum = 0;
        if (true) {
            $contFor = 0;
            foreach ($final as $item) {
                $entroModRest = false;
                if ($ModRestSum < $ModRest) {
                    $contarrayRedimidos = 0;
                    foreach ($arrayRedimidos as $arrayRedimido) {
                        if ($contarrayRedimidos < $ModRest) {
                            if ($final[$contFor]["lid"] == $arrayRedimido) {
                                $entroModRest = true;
                            }
                        }
                        $contarrayRedimidos++;
                    }
                }
                if ($entroModRest) {
                    $ModRestSum++;
                } else {
                    $final[$contFor]["state"] = 2;
                }
                $contFor++;
            }
        }
    }

}

if (($UsuarioMandante->usuarioMandante == 886 || $UsuarioMandante->usuarioMandante == 73818) && false) {
    $contFor = 0;
    foreach ($final as $item) {
        $final[$contFor]["state"] = 2;
        $contFor++;
    }
}
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;
