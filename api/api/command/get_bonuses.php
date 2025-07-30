<?php


use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\Pais;


/**
 * Obtiene los detalles de los bonos según el tipo requerido
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @throws Exception Si ocurre un error al crear el objeto País.
 * @return array Respuesta con el código, rid y los datos de los bonos.
 */

// Se obtienen los parámetros del objeto JSON recibido.
$params=$json->params;
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

// Se obtienen otras propiedades de los parámetros.
$MaxRows = $params->Limit; // Límite de filas a recuperar.
$OrderedItem = $params->OrderedItem; // Elemento ordenado.
$SkeepRows = ($params->Offset) * $MaxRows; // Filas a omitir en la consulta.

$StateType = $params->StateType; // Tipo de estado.
$State = ($params->State == "I") ? 'I' : 'A'; // Estado, se verifica si es "I" o "A".

$Country = $params->Country;

$rules = []; // Se inicializa un array para las reglas de filtrado.

$jsonfiltro = json_encode($filtro); // Se convierte el filtro a formato JSON.

$BonoInterno = new BonoInterno(); // Se instancia la clase BonoInterno.
$BonoDetalle = new BonoDetalle(); // Se instancia la clase BonoDetalle.


$rules = [];

if ($State == "A" || $State == "I") {
    // Se agrega una regla para filtrar por estado.
    array_push($rules, array("field" => "bono_interno.estado", "data" => "$State", "op" => "eq"));

}
array_push($rules, array("field" => "bono_interno.publico", "data" => "A", "op" => "eq"));


// Se verifica si se proporcionó un país en los parámetros.
if($Country != "") {
    try {
        // Se intenta crear un objeto País con el país proporcionado.
        $Pais = new Pais("", $Country);

    } catch (Exception $e){
        // Se maneja cualquier excepción al intentar crear el objeto País.
    }

    // Se agregan reglas para filtrar por tipo y valor de país.
    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "bono_detalle.valor", "data" => $Pais->paisId, "op" => "eq"));
}else{
    // Si no se proporciona país, se filtra por tipo de producto.
    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
}


//array_push($rules, array("field" => "bono_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
//array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$jsonfiltro = json_encode($filtro); // Convierte el filtro a formato JSON

//Solicitud personalizada de los detalles
$bonos = $BonoDetalle->getBonoDetallesCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);

$bonos = json_decode($bonos); // Decodifica el JSON obtenido a un objeto PHP

$final = [];


foreach ($bonos->data as $key => $value) {
    /*Declaración y asignación de parámetros al array $array*/
    $array = [];


    $array["id"] = $value->{"bono_interno.bono_id"};
    $array["nombre"] = $value->{"bono_interno.nombre"};
    $array["descripcion_short"] = $value->{"bono_interno.descripcion"};
    $array["descripcion"] = $value->{"bono_interno.descripcion"};
    $array["fecha_inicio"] = strtotime($value->{"bono_interno.fecha_inicio"}) * 1000;
    $array["fecha_fin"] = strtotime($value->{"bono_interno.fecha_fin"}) * 1000;

    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

    if($ConfigurationEnvironment->isDevelopment()){
        $array["descripcion"] ='';
    }
    $array["reglas"] = $value->{"bono_interno.reglas"};
    $array["inscritos"] = $value->{"bono_interno.cantidad_torneos"};
    $array["state"] = 0;
    $array["inscritos"] = $value->{"bono_interno.cantidad_torneos"};
    $array["type"] = 0;
    $array["maximo"] = $value->{"bono_interno.maximo_torneos"};
    $array["rangos"] = array();
    $array["premios"] = array();
    $array["ranking"] = array();
    $array["games"] = array();
    $array["userjoin"] = false;
    $array["typeRank"] = 0;
    $array["inscritos"] = $value->{"bono_interno.cantidad_torneos"};
    $array["inscritos"] = $value->{"bono_interno.cantidad_torneos"};
    $array["image"] =  $value->{"bono_interno.imagen"};
    $array["image_int"] = $value->{"bono_interno.imagen"};

    switch ($value->{"bono_interno.tipo"}) {
        /*Asignación de información para bono depósito*/
        case "2":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Bono Deposito",
                "TypeId" => $value->{"bono_interno.tipo"}
            );

            break;

        /*Asignación de información para bono NO depósito*/
        case "3":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Bono No Deposito",
                "TypeId" => $value->{"bono_interno.tipo"}
            );

            break;

        /*Asignación de información para FreeCash*/
        case "4":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Bono Cash",
                "TypeId" => $value->{"bono_interno.tipo"}
            );

            break;


        /*Asignación de información para FreeBet*/
        case "6":
            $array["Type"] = array(
                "Id" => $value->{"bono_interno.tipo"},
                "Name" => "Freebet",
                "TypeId" => $value->{"bono_interno.tipo"}
            );

            break;


    }


   /* $games = "";


    $rules = [];


    array_push($rules, array("field" => "bono_detalle.bono_id", "data" => $array["id"], "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;

    $OrderedItem = 1;

    $MaxRows = 1000000000;

    $jsonfiltrodetalles = json_encode($filtro);


    $torneodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");

    $torneodetalles = json_decode($torneodetalles);

    $needSubscribe = false;
    $userpais = false;
    $userpaisCont = 0;

    foreach ($torneodetalles->data as $key2 => $value2) {


        switch ($value2->{"bono_detalle.tipo"}) {


            case "USERSUBSCRIBE":

                if ($value2->{"bono_detalle.valor"} == 0) {

                } else {
                    $needSubscribe = true;
                }

                break;

            case "RANKAWARDMAT":

                if ($array['premios'][$value2->{"bono_detalle.moneda"}] == "") {
                    $array['premios'][$value2->{"bono_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['pos'] = $value2->{"bono_detalle.valor"};
                $array2['desc'] = $value2->{"bono_detalle.valor2"};
                $array2['valor'] = $value2->{"bono_detalle.valor3"};
                $array2['tipo'] = 0;

                array_push($array['premios'][$value2->{"bono_detalle.moneda"}], ($array2));
                break;


            case "RANKAWARD":

                if ($array['premios'][$value2->{"bono_detalle.moneda"}] == "") {
                    $array['premios'][$value2->{"bono_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['pos'] = $value2->{"bono_detalle.valor"};
                $array2['desc'] = $value2->{"bono_detalle.valor2"};
                $array2['valor'] = $value2->{"bono_detalle.valor3"};
                $array2['tipo'] = 1;

                array_push($array['premios'][$value2->{"bono_detalle.moneda"}], ($array2));
                break;

            case "RANK":

                if ($array['rangos'][$value2->{"bono_detalle.moneda"}] == "") {
                    $array['rangos'][$value2->{"bono_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['inicial'] = $value2->{"bono_detalle.valor"};
                $array2['final'] = $value2->{"bono_detalle.valor2"};
                $array2['valor'] = $value2->{"bono_detalle.valor3"};

                array_push($array['rangos'][$value2->{"bono_detalle.moneda"}], ($array2));
                break;


            case "RANKLINE":

                if ($array['rangos'][$value2->{"bono_detalle.moneda"}] == "") {
                    $array['rangos'][$value2->{"bono_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['inicial'] = $value2->{"bono_detalle.valor"};
                $array2['final'] = $value2->{"bono_detalle.valor2"};
                $array2['valor'] = $value2->{"bono_detalle.valor3"};

                array_push($array['rangos'][$value2->{"bono_detalle.moneda"}], ($array2));
                $array['typeRank'] = 2;

                break;
            case "IMGPPALURL":

                $array['image'] = $value2->{"bono_detalle.valor"};

                break;


            case "RANKIMGURL":

                $array['imageRank'] = $value2->{"bono_detalle.valor"};

                break;

            case "BACKGROUNDURL":

                $array['background'] = $value2->{"bono_detalle.valor"};

                break;


            case "USERSUBSCRIBE":

                if ($value2->{"bono_detalle.valor"} == 0) {
                    $needSubscribe=true;
                }

                break;


            case "CONDPAISUSER":

                if ($json->session->logueado) {

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                    if ($value2->{"bono_detalle.valor"} == $UsuarioMandante->getPaisId()) {
                        $userpais = true;
                    }
                }
                $userpaisCont++;

                break;

            case "VISIBILIDAD":
                $array["type"] = $value2->{"bono_detalle.valor"};
                break;
            default:


                if (stristr($value2->{"bono_detalle.tipo"}, 'ITAINMENT')) {

                    $idGame = explode("ITAINMENT", $value2->{"bono_detalle.tipo"})[1];

                    switch ($idGame) {
                        case 1:
                            $array2 = [];
                            $array2['id'] = $value2->{"bono_detalle.torneodetalle_id"};
                            $array2['name'] = "DEPORTE";
                            $array2['img'] = $value2->{"bono_detalle.descripcion"};
                            $array2['url'] = "/deportes/" . $value2->{"bono_detalle.valor"};

                            array_push($array['games'], ($array2));

                            break;

                        case 3:
                            $array2 = [];
                            $array2['id'] = $value2->{"bono_detalle.torneodetalle_id"};
                            $array2['name'] = "LIGA";
                            $array2['img'] = $value2->{"bono_detalle.descripcion"};
                            $array2['url'] = "/deportes/liga/" . $value2->{"bono_detalle.valor"};

                            array_push($array['games'], ($array2));

                            break;

                        case 4:
                            $array2 = [];
                            $array2['id'] = $value2->{"bono_detalle.torneodetalle_id"};
                            $array2['name'] = "EVENTO";
                            $array2['img'] = $value2->{"bono_detalle.descripcion"};
                            $array2['url'] = "/deportes/partido/" . $value2->{"bono_detalle.valor"};

                            array_push($array['games'], ($array2));

                            break;

                        case 5:
                            $array2 = [];
                            $array2['id'] = $value2->{"bono_detalle.torneodetalle_id"};
                            $array2['name'] = "MERCADO";
                            $array2['img'] = $value2->{"bono_detalle.descripcion"};
                            $array2['url'] = "#";

                            array_push($array['games'], ($array2));

                            break;

                    }

                }

                if (stristr($value2->{"bono_detalle.tipo"}, 'CONDGAME')) {

                    $idGame = explode("CONDGAME", $value2->{"bono_detalle.tipo"})[1];

                    $games = $games . $idGame . ",";

                }

                break;
        }


    }*/

    /*if ($games != "") {
        $games = $games . '0';

        $es_movil = false;
        $whereDisp=" AND producto.desktop='S'";

        $rules2 = [];
        array_push($rules2, array("field" => "producto_mandante.prodmandante_id", "data" => $games, "op" => "in"));
        array_push($rules2, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

        $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
        $jsonfiltro2 = json_encode($filtro2);


// Any mobile device (phones or tablets).
        /*if ($detect->isMobile()) {
            $whereDisp=" AND producto.mobile='S'";
        }

        $ProductoMandante= new ProductoMandante();
        $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1000, $jsonfiltro2, true);
        $productos = json_decode($productos);

        foreach ($productos->data as $key2 => $value2) {

            $lista_temp2 = [];
            $lista_temp2['id'] = $value2->{'producto_mandante.prodmandante_id'};
            $lista_temp2['name'] = $value2->{'producto.descripcion'};
            $lista_temp2['img'] = $value2->{'producto.image_url'};
            $lista_temp2['url'] = "/new-casino/".$value2->{'producto_mandante.prodmandante_id'};

            array_push($array['games'], ($lista_temp2));

        }

    }*/


    array_push($final, $array);
}

//Formateo de la respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;
