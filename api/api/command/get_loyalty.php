<?php


use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Pais;
use Backend\dto\UsuarioLealtad;

/**
 * Procesa y obtiene datos de las campañas de lealtad solicitadas
 *
 * @param object \$json Contiene la sesión y los parámetros
 * @param string \$ToDateLocal Fecha de inicio local derivada de \$params->StartTimeLocal
 * @param string \$FromDateLocal Fecha de fin local derivada de \$params->EndTimeLocal
 * @param int \$TypeId Identificador del tipo derivado de \$params->TypeId
 * @param int \$MaxRows Límite de filas derivado de \$params->Limit
 * @param int \$OrderedItem Elemento ordenado derivado de \$params->OrderedItem
 * @param int \$SkeepRows Número de filas a omitir calculado de (\$params->Offset * \$MaxRows)
 * @param string \$StateType Tipo de estado derivado de \$params->StateType
 * @param string \$State Estado derivado de \$params->State
 * @param string \$Country País derivado de \$params->Country
 * @return array
 *  - code: int Código de respuesta
 *  - rid: string Identificador de respuesta
 *  - data: array Arreglo con información de lealtad
 */

// Inicialización de las variables de usuario y parámetros
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$params=$json->params;
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;

$FromDateLocal = $params->EndTimeLocal;
$FromDateLocal = $params->EndTimeLocal;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$StateType = $params->StateType;
$State = ($params->State == "I")? 'I':'A';

$Country = $params->Country;

// Inicializa las reglas como un array vacío
$rules = [];

$jsonfiltro = json_encode($filtro);

// Instancia los objetos LealtadInterno y LealtadDetalle
$LealtadInterno = new LealtadInterno();
$LealtadDetalle = new LealtadDetalle();

// Inicializa las reglas como un array vacío (duplicado)
$rules = [];

// Agrega reglas basadas en el estado
if ($State == "A" || $State == "I") {
    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "$State", "op" => "eq"));
}

// Agrega regla para el mandante asociado al usuario
array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));

// Si el usuario mandante no tiene un valor específico, agrega más reglas
if ($UsuarioMandante->getUsuarioMandante() != 73818) {
    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "499", "op" => "ne"));
    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "511", "op" => "ne"));

}

if($Country != "") {
    // Si la variable $Country no está vacía, se intenta crear un objeto de la clase Pais
    try {
        $Pais = new Pais("", $Country);

    } catch (Exception $e){

    }
    // Se añaden reglas al array $rules para filtrar por el tipo de país del usuario
    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "lealtad_detalle.valor", "data" => $Pais->paisId, "op" => "eq"));
}else{
    // Si $Country está vacío, se añade una regla para filtrar por tipo de producto
    array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

}

//array_push($rules, array("field" => "lealtad_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));
//array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
// Se establece el desfase de filas a 0 si no se ha definido previamente
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

// Se establece el elemento ordenado a 1 si no se ha definido previamente
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

// Se establece el número máximo de filas a 10 si no se ha definido previamente
if ($MaxRows == "") {
    $MaxRows = 10;
}

// Se codifica el filtro en formato JSON
$jsonfiltro = json_encode($filtro);

//$lealtad = $LealtadInterno->getLealtadsCustom(" lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);
$lealtad = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);

$lealtad = json_decode($lealtad);

// Se inicializa un array vacío para los resultados finales
$final = [];
foreach ($lealtad->data as $key => $value) {

    /**
     * Inicializa un array con información relacionada a lealtad interna.
     *
     * Este array contendrá diferentes propiedades que describen la lealtad interna,
     * incluyendo identificación, nombre, descripción, fechas, reglas, inscritos,
     * estado, tipo, máximo, rangos, premios, ranking, juegos, si el usuario se unió
     * y tipo de ranking.
     */

    $array = [];


    $array["id"] = $value->{"lealtad_interna.lealtad_id"};
    $array["nombre"] = $value->{"lealtad_interna.nombre"};
    $array["descripcion"] = $value->{"lealtad_interna.descripcion"};
    $array["fecha_inicio"] = strtotime($value->{"lealtad_interna.fecha_inicio"}) * 1000;
    $array["fecha_fin"] = strtotime($value->{"lealtad_interna.fecha_fin"}) * 1000;

    $array["reglas"] = $value->{"lealtad_interna.reglas"};
    $array["inscritos"] = $value->{"lealtad_interna.cantidad_lealtad"};
    $array["state"] = 0;
    $array["inscritos"] = $value->{"lealtad_interna.cantidad_lealtad"};
    $array["type"] = 0;
    $array["maximo"] = $value->{"lealtad_interna.maximo_lealtad"};
    $array["rangos"] = array();
    $array["premios"] = array();
    $array["ranking"] = array();
    $array["games"] = array();
    $array["userjoin"] = false;
    $array["typeRank"] = 0;
    $array["inscritos"] = $value->{"lealtad_interna.cantidad_lealtad"};
   // $array["inscritos"] = $value->{"lealtad_interna.cantidad_lealtad"};

    /*Definición origen de la lealtad según el tipo proporcionado*/
    switch ($value->{"lealtad_interna.tipo"}) {
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

    // Se inicializa una variable para almacenar juegos como una cadena vacía.
    $games = "";

    // Se inicializa un arreglo para las reglas de filtrado.
    $rules = [];

    // Se añade una regla al arreglo de reglas, especificando el campo, los datos y la operación.
    array_push($rules, array("field" => "lealtad_detalle.lealtad_id", "data" => $array["id"], "op" => "eq"));

    // Se construye el filtro combinando las reglas y el operador de agrupamiento.
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    // Se inicializa la variable para el número de filas a omitir.
    $SkeepRows = 0;

    // Se establece el número de ítems ordenados.
    $OrderedItem = 1;

    // Se define un límite máximo de filas.
    $MaxRows = 1000000000;

    // Se convierte el filtro en formato JSON.
    $jsonfiltrodetalles = json_encode($filtro);

    // Se obtiene detalles de lealtad utilizando un método personalizado del objeto $LealtadDetalle.
    $lealtaddetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");

    // Se decodifica el resultado JSON a un objeto PHP.
    $lealtaddetalles = json_decode($lealtaddetalles);

    // Se inicializa una variable que indica si se necesita suscribirse.
    $needSubscribe = false;
    $userpais = false;
    $userpaisCont = 0;

    /*Obtención y envío de las configuraciones de la lealtad*/
    foreach ($lealtaddetalles->data as $key2 => $value2) {


        switch ($value2->{"lealtad_detalle.tipo"}) {
            // Caso para la suscripción de usuario
            case "USERSUBSCRIBE":
                // Verifica si el valor de lealtad_detalle es cero
                if ($value2->{"lealtad_detalle.valor"} == 0) {

                } else {
                    // Se requiere suscribirse si el valor no es cero
                    $needSubscribe = true;
                }

                break;

            // Caso para el premio de rango Mat
            case "RANKAWARDMAT":
                // Inicializa un array si el premio para la moneda especificada está vacío
                if ($array['premios'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['premios'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['pos'] = $value2->{"lealtad_detalle.valor"};
                $array2['desc'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ','',str_replace('	','',$value2->{"lealtad_detalle.valor3"}));
                $array2['tipo'] = 0;

                array_push($array['premios'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                break;


            case "RANKAWARD":
                // Inicializa un array si el premio para la moneda especificada está vacío
                if ($array['premios'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['premios'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['pos'] = $value2->{"lealtad_detalle.valor"};
                $array2['desc'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ','',str_replace('	','',$value2->{"lealtad_detalle.valor3"}));
                $array2['tipo'] = 1;

                array_push($array['premios'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                break;

            case "RANK":
                // Verifica si no existe un array para la moneda, si no existe lo inicializa como un array vacío.
                if ($array['rangos'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['rangos'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['inicial'] = $value2->{"lealtad_detalle.valor"};
                $array2['final'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ','',str_replace('	','',$value2->{"lealtad_detalle.valor3"}));

                array_push($array['rangos'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                break;


            case "RANKLINE":
                // Verifica si no existe un array para la moneda, si no existe lo inicializa como un array vacío.
                if ($array['rangos'][$value2->{"lealtad_detalle.moneda"}] == "") {
                    $array['rangos'][$value2->{"lealtad_detalle.moneda"}] = array();
                }

                $array2 = [];
                $array2['inicial'] = $value2->{"lealtad_detalle.valor"};
                $array2['final'] = $value2->{"lealtad_detalle.valor2"};
                $array2['valor'] = str_replace(' ','',str_replace('	','',$value2->{"lealtad_detalle.valor3"}));

                array_push($array['rangos'][$value2->{"lealtad_detalle.moneda"}], ($array2));
                $array['typeRank'] = 2;

                break;
            case "IMGPPALURL":
                // Asigna la URL de la imagen principal al array.
                $array['image'] = $value2->{"lealtad_detalle.valor"};

                break;


            case "RANKIMGURL":
                // Asigna la URL de la imagen del rango al array.
                $array['imageRank'] = $value2->{"lealtad_detalle.valor"};

                break;

            case "BACKGROUNDURL":
                // Asigna la URL del fondo al array.
                $array['background'] = $value2->{"lealtad_detalle.valor"};

                break;


            case "USERSUBSCRIBE":
                // Verifica si el valor de lealtad_detalle es 0 y establece la necesidad de suscripción
                if ($value2->{"lealtad_detalle.valor"} == 0) {
                    $needSubscribe=true;
                }

                break;


            case "CONDPAISUSER":
                // Si el usuario está logueado, se crea una instancia de UsuarioMandante
                if ($json->session->logueado) {

                    $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                    if ($value2->{"lealtad_detalle.valor"} == $UsuarioMandante->getPaisId()) {
                        $userpais = true;
                    }
                }
                $userpaisCont++; // Incrementa el contador de usuario por país
                break;

            case "VISIBILIDAD":
                // Asigna el tipo de lealtad_detalle al arreglo
                $array["type"] = $value2->{"lealtad_detalle.valor"};
                break;
            default:
                // Verifica si el tipo contiene 'ITAINMENT'
                if (stristr($value2->{"lealtad_detalle.tipo"}, 'ITAINMENT')) {

                    $idGame = explode("ITAINMENT", $value2->{"lealtad_detalle.tipo"})[1];

                    switch ($idGame) {
                        case 1:
                            $array2 = [];
                            $array2['id'] = $value2->{"lealtad_detalle.lealtad_detalle_id"};
                            $array2['name'] = "DEPORTE";
                            $array2['img'] = $value2->{"lealtad_detalle.descripcion"};
                            $array2['url'] = "/deportes/" . $value2->{"lealtad_detalle.valor"};

                            array_push($array['games'], ($array2));

                            break;

                        case 3:
                            /*Almacenamiento información para LIGA*/
                            $array2 = [];
                            $array2['id'] = $value2->{"lealtad_detalle.lealtad_detalle_id"};
                            $array2['name'] = "LIGA";
                            $array2['img'] = $value2->{"lealtad_detalle.descripcion"};
                            $array2['url'] = "/deportes/liga/" . $value2->{"lealtad_detalle.valor"};

                            array_push($array['games'], ($array2));

                            break;

                        case 4:
                            /*Almacenamiento información para EVENTO*/
                            $array2 = [];
                            $array2['id'] = $value2->{"lealtad_detalle.lealtad_detalle_id"};
                            $array2['name'] = "EVENTO";
                            $array2['img'] = $value2->{"lealtad_detalle.descripcion"};
                            $array2['url'] = "/deportes/partido/" . $value2->{"lealtad_detalle.valor"};

                            array_push($array['games'], ($array2));

                            break;

                        case 5:
                            /*Almacenamiento información para MERCADO*/
                            $array2 = [];
                            $array2['id'] = $value2->{"lealtad_detalle.lealtad_detalle_id"};
                            $array2['name'] = "MERCADO";
                            $array2['img'] = $value2->{"lealtad_detalle.descripcion"};
                            $array2['url'] = "#";

                            array_push($array['games'], ($array2));

                            break;

                    }

                }

                /*Almacenamiento condición de juegos*/
                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                    $games = $games . $idGame . ",";

                }

                break;
        }


    }

    // Verifica si se necesita subscribir. Si no, establece 'userjoin' en true, de lo contrario en false.
    if(!$needSubscribe){
        $array['userjoin'] = true;
    }else{
        $array['userjoin'] = false;

    }

    // Comprueba si existe algún juego definido.
    if ($games != "") {
        $games = $games . '0';

        $es_movil = false;
        $whereDisp=" AND producto.desktop='S'";

        $rules2 = [];
        array_push($rules2, array("field" => "producto_mandante.prodmandante_id", "data" => $games, "op" => "in"));
        array_push($rules2, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

        $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
        $jsonfiltro2 = json_encode($filtro2);

        // Se crea una instancia de ProductoMandante.
        $ProductoMandante= new ProductoMandante();
        $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1000, $jsonfiltro2, true);
        $productos = json_decode($productos);

        // Se itera sobre los productos obtenidos.
        foreach ($productos->data as $key2 => $value2) {
            // Se crea un array temporal para almacenar la información del producto.
            $lista_temp2 = [];
            $lista_temp2['id'] = $value2->{'producto_mandante.prodmandante_id'};
            $lista_temp2['name'] = $value2->{'producto.descripcion'};
            $lista_temp2['img'] = $value2->{'producto.image_url'};
            $lista_temp2['url'] = "/casino/".$value2->{'producto_mandante.prodmandante_id'};

            // Se agrega el producto a la lista de juegos en el array principal.
            array_push($array['games'], ($lista_temp2));

        }

    }

    $usuarioIdLogueado='';
    if ($json->session->logueado) {
        $usuarioIdLogueado=$json->session->usuario;
    }

        // Inicializa las reglas de filtrado
        $rules = [];


    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $array["id"], "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $SkeepRows = 0;

    $OrderedItem = 1;

    $MaxRows = 10;

    $jsonfiltrodetalles = json_encode($filtro);

    // Obtiene los datos de lealtad de los usuarios mediante un método personalizado
    $UsuarioLealtad = new UsuarioLealtad();
    $UsuariosLealtad = $UsuarioLealtad->getUsuarioLealtadsCustom(" usuario_lealtad.valor,lealtad_interna.*,position.position,usuario_mandante.usumandante_id,usuario_mandante.usuario_mandante,usuario_mandante.nombres ", "usuario_lealtad.valor", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");
    $UsuariosLealtad = json_decode($UsuariosLealtad);

    // Inicializa la posición y un flag para verificar si el usuario está en el ranking
    $pos = 1;
    $entre10=false;
    foreach ($UsuariosLealtad->data as $key2 => $value2) {
        /*Definición objetos temporales de lealtad*/
        $lista_temp2 = [];
        $lista_temp2['pos'] = $pos;
        $lista_temp2['user'] = $value2->{'usuario_mandante.usuario_mandante'} . "**" . $value2->{'usuario_mandante.nombres'};
        $lista_temp2['user'] = substr($lista_temp2['user'], 0, 16);
        $lista_temp2['valor'] = $value2->{'usuario_lealtad.valor'};
        $lista_temp2['valor']=round(floatval($lista_temp2['valor']),2);
        $lista_temp2['is_user'] = false;
        if ($value2->{'usuario_mandante.usumandante_id'} == $usuarioIdLogueado) {
            $entre10 = true;
            $lista_temp2['is_user'] = true;

        }

        array_push($array["ranking"], ($lista_temp2));

        $pos++;

    }

    if(!$entre10 && $usuarioIdLogueado != ''){

        /*El código procesa y obtiene datos de lealtad, aplicando filtros y reglas para generar una respuesta
         con información detallada sobre lealtad interna, premios, rangos y juegos asociados.*/
        $rules = [];


        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $array["id"], "op" => "eq"));
        array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => $usuarioIdLogueado, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $SkeepRows = 0;

        $OrderedItem = 1;

        $MaxRows = 10;

        $jsonfiltrodetalles = json_encode($filtro);


        /*obtiene y procesa datos de lealtad, aplicando filtros y reglas para generar una respuesta con
         información detallada sobre lealtad interna, premios, rangos y juegos asociados.*/
        $UsuarioLealtad = new UsuarioLealtad();
        $UsuariosLealtad = $UsuarioLealtad->getUsuarioLealtadsCustom(" usuario_lealtad.valor,lealtad_interna.*,position.position,usuario_mandante.usumandante_id,usuario_mandante.usuario_mandante,usuario_mandante.nombres ", "usuario_lealtad.valor", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");
        $UsuariosLealtad = json_decode($UsuariosLealtad);

        /*procesa y obtiene datos de lealtad, aplicando filtros y reglas para generar una respuesta con información detallada sobre
         lealtad interna, premios, rangos y juegos asociados.*/
        foreach ($UsuariosLealtad->data as $key2 => $value2) {

            $lista_temp2 = [];
            $lista_temp2['pos'] = $pos;
            $lista_temp2['user'] = $value2->{'usuario_mandante.usuario_mandante'} . "**" . $value2->{'usuario_mandante.nombres'};
            $lista_temp2['user'] = substr($lista_temp2['user'], 0, 16);
            $lista_temp2['valor'] = $value2->{'usuario_lealtad.valor'};
            $lista_temp2['valor']=round(floatval($lista_temp2['valor']),2);
            $lista_temp2['is_user'] = false;
            if ($value2->{'usuario_mandante.usumandante_id'} == $usuarioIdLogueado) {
                $entre10 = true;
                $lista_temp2['is_user'] = true;

            }

            array_push($array["ranking"], ($lista_temp2));

            $pos++;

        }

    }

    array_push($final, $array);
}

//Formato de respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;
