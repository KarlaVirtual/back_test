<?php
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\cms\CMSProveedor;

/**
 * Obtiene los productos favoritos de un usuario.
 *
 * @param object $json Objeto JSON con los siguientes valores:
 * @param string $params->user_id ID del usuario.
 * @param bool|null $params->is_mobile Indica si la solicitud es desde un dispositivo móvil.
 * @param int|null $params->offset Desplazamiento para la paginación.
 * @param int|null $params->limit Límite de productos a recuperar.
 *
 * @return void Modifica el parámetro $response con los siguientes valores:
 *  - code (int): Código de respuesta (0 para éxito).
 *  - rid (mixed): ID de la solicitud.
 *  - data (array): Contiene los datos de la respuesta.
 *      - AlertMessage (string): Mensaje de alerta.
 *      - products (array): Lista de productos favoritos del usuario.
 *      - total_count (int): Número total de productos favoritos.
 *
 * @throws Exception Si los parámetros son inválidos (código 300023).
 */

$params = $json->params;

//Recepción de parámetros
$userId = $params->user_id;
$isMobile = $params->is_mobile !== null ? $params->is_mobile : false;
$offset = $params->offset ?? 0;
$limit = $params->limit ?? 10;

//Sanitización de parámetros
$safeParameters = true;

$unsafePattern = '/\D/';
if (preg_match($unsafePattern, $userId)) $safeParameters = false;
if (preg_match($unsafePattern, $offset)) $safeParameters = false;
if (preg_match($unsafePattern, $limit)) $safeParameters = false;
if (!in_array($isMobile, [true, false])) $safeParameters = false;

if (!$safeParameters) throw new exception('Parametros invalidos', 300023);

/**
 * Recuperando usuario solicitante de favoritos.
 * - $Usuario: instancia de la clase Usuario para el usuario solicitado.
 * - $UsuarioMandante: instancia de la clase UsuarioMandante asociada al usuario.
 */
$Usuario = new Usuario($userId);
$UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

//Definiendo backGround de casino
$partner_id = $UsuarioMandante->getMandante();
switch ($partner_id){

    case '0':
        $bgCasino='https://images.virtualsoft.tech/site/doradobet/fondo-casino2.png?v=1';
    break;

    case '3':
        $bgCasino='https://images.virtualsoft.tech/site/miravalle/bgCasino.jpg';
    break;

    case '4':
        $bgCasino='https://images.virtualsoft.tech/site/casinogranpalacio/bgCasino.jpg';
    break;

    case '5':
        $bgCasino='https://images.virtualsoft.tech/site/casinointercontinental/bgCasino.jpg';
    break;

    case '6':
        $bgCasino='https://images.virtualsoft.tech/site/netabet/bgCasino.jpg';
    break;

    case '7':
        $bgCasino='https://images.virtualsoft.tech/site/astoria/bgCasino.jpg';
    break;

    default:
        $bgCasino='https://images.virtualsoft.tech/productos/casino/casino-background2.jpg';
    break;
}

//Recuperando favoritos del usuario
$totalCount = true;
$Proveedor = new CMSProveedor('', '', $UsuarioMandante->getMandante(), $UsuarioMandante->getPaisId());
$Productos = $Proveedor->getProductos('', '', $offset, $limit, '', $isMobile, '', $UsuarioMandante->getUsumandanteId(), $totalCount);
$Productos = json_decode($Productos);
$Productos = $Productos->data;

$games = [];
foreach ($Productos as $producto) {
    //Iterando y almacenando productos
    $game = array();
    $game["id"] = $producto->id; // ID del producto
    $game["name"] = $producto->descripcion; // Descripción del producto
    $game["producto_id"] = $producto->producto_id; // ID del producto
    $game["provider"] = $producto->proveedor->abreviado; // Abreviación del proveedor
    $game["show_as_provider"] = $producto->proveedor->descripcion; // Descripción del proveedor
    $game["server_game_id"] = $producto->id; // ID del juego en el servidor
    $game["status"] = "published"; // Estado del juego

    // Si no hay fondo definido, se asigna un fondo predeterminado
    if ($producto->background == "") {
        $producto->background = $bgCasino; // Asignación del fondo predeterminado
    }

    $game["background"] = $producto->background; // Fondo del juego
    $game["categories"] = array($producto->categoria->id); // Categorías asociadas al producto
    $game["cats"] = array("id" => $producto->categoria->id, "title" => $producto->categoria->descripcion); // Información de la categoría
    $game["extearnal_game_id"] = $producto->id; // ID externo del juego
    $game["front_game_id"] = $producto->externo_id; // ID del juego en el frontend
    $game["game_options"] = ""; // Opciones del juego
    $game["game_skin_id"] = ""; // ID de la skin del juego
    $game["icon_2"] = str_replace("http:","https:",$producto->image); // Icono 2 del juego con protocolo seguro
    $game["icon_3"] =  str_replace("http:","https:",$producto->image2); // Icono 3 del juego con protocolo seguro
    $game["ratio"] = "16:9"; // Relación de aspecto del juego

    // Verificaciones para asignar un jackpot dependiendo del ID del juego en frontend
    if(in_array($game["front_game_id"],array(
        'gpas_aogggriffin_pop','gpas_aogrotu_pop','aogmt','gpas_aogiw_pop','gpas_aoggosun_pop','aogmm','wop','gpas_aogwfot_pop','gpas_aogww_pop'
    ))){
        $game["jackpot"]=1; // Asignar jackpot
        $game["front_game_id"]='mrj-1'; // ID del juego actualizado
    }
    if(in_array($game["front_game_id"],array(
        'anwild','gpas_awild2pp_pop'
    ))){
        $game["jackpot"]=1; // Asignar jackpot
        $game["front_game_id"]='ptjp-1'; // ID del juego actualizado
    }
    if(in_array($game["front_game_id"],array(
        'gpas_fballiwpp_pop','gpas_focashco_pop'
    ))){
        $game["jackpot"]=1; // Asignar jackpot
        $game["front_game_id"]='ptjp-1'; // ID del juego actualizado
    }
    if(in_array($game["front_game_id"],array(
        'fdtjg'
    ))){
        $game["jackpot"]=1; // Asignar jackpot
        $game["front_game_id"]='fdtjp-2'; // ID del juego actualizado
    }
    if(in_array($game["front_game_id"],array(
        'gpas_azbolipp_pop','gpas_ppayspp_pop','gpas_pigeonfspp_pop','gpas_sstrikepp_pop','gpas_soicepp_pop','gpas_tttotemspp_pop','gpas_mblockspp_pop','gpas_wlinxpp_pop','gpas_fmhitbarpp_pop','gpas_hgextremepp_pop','gpas_kgomoonpp_pop','gpas_dostormspp_pop','gpas_bokings2pp_pop','gpas_eemeraldspp_pop','gpas_betwildspp_pop','gpas_bbellspp_pop'
    ))){
        $game["jackpot"]=1;
        $game["front_game_id"]='ptjp-1';
    }
    if(in_array($game["front_game_id"],array(
        'tmccoy','asct','gpas_bgeorge_pop'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='sljp-3';
    }

    if(in_array($game["front_game_id"],array(
        'gpas_bbmwayslo_pop'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='bjp-4';
    }
    if(in_array($game["front_game_id"],array(
        'cbells'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='jbells4-4';
    }
    if(in_array($game["front_game_id"],array(
        'gpas_eape2_pop'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='bjp-4';
    }
    if(in_array($game["front_game_id"],array(
        'evj'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='evjj-1';
    }
    if(in_array($game["front_game_id"],array(
        'fcgz'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='drgj-1';
    }
    if(in_array($game["front_game_id"],array(
        'gpas_fbars_pop'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='jhreelsj-2';
    }
    if(in_array($game["front_game_id"],array(
        'fmjp'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='fmjp8';
    }
    if(in_array($game["front_game_id"],array(
        'grbjp'
    ))){
        // Asigna un jackpot y un ID de juego frontal cuando el juego cumple una de las condiciones
        $game["jackpot"]=1;
        $game["front_game_id"]='grbjpj-1';
    }
    // Asignación del valor 1 al jackpot y un identificador del juego específico si se cumple la condición en el array.
    if(in_array($game["front_game_id"],array(
        'jbells'
    ))){
        $game["jackpot"]=1; // Se establece el jackpot del juego en 1
        $game["front_game_id"]='jbells4-4'; // Se asigna un nuevo identificador para el juego
    }

    // Asignación del valor 1 al jackpot y un identificador del juego diferente si se cumple la condición en el array.
    if(in_array($game["front_game_id"],array(
        'jpgt'
    ))){
        $game["jackpot"]=1; // Se establece el jackpot del juego en 1
        $game["front_game_id"]='jpgt6-1'; // Se asigna un nuevo identificador para el juego
    }

    // Asignación del valor 1 al jackpot y un tercer identificador del juego si se cumple la condición en el array.
    if(in_array($game["front_game_id"],array(
        'zcjbjp'
    ))){
        $game["jackpot"]=1;
        $game["front_game_id"]='drgj-1';
    }

    if($isMobile){
        $game["rows"] = 1;
        $game["columns"] = 1;
        $game["grid_column"] = 1;
        $game["grid_row"] = 1;

    }else{
        $game["rows"] = $producto->fila;
        $game["columns"] = $producto->columna;
        $game["grid_column"] = $producto->fila;
        $game["grid_row"] = $producto->columna;

    }

    // Asignar propiedades de borde y fondo a juegos específicos
    $game["types"] = array(
        "realMode" => 1,
        "funMode" => 0

    );

    if ($game["id"] == "4158") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

        $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
    }


    if ($game["id"] == "4194") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

        $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
    }




    if ($game["id"] == "4566") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

        $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
    }


    if ($game["id"] == "4428") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

    }

    if ($game["id"] == "4803") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

    }

    if ($game["id"] == "4194") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

    }

    if ($game["id"] == "4566") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

    }

    if ($game["id"] == "4566") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

    }

    if(in_array($game["id"],array(5457,8717, 5459, 8720,593,6107,9251,11858,11861,5217,51637))){
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';

    }

    /*Decodifica HTML, asigna propiedades de borde y fondo, y agrega el juego a la lista.*/
    $game["name"] = html_entity_decode(preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $game["name"]));
    $game["icon_2"] = html_entity_decode(preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $game["icon_2"]));


    if ($game["id"] == "4428") {
        $game["isBorderNeon"] = true;
        $game["classBorderNeon"] = 'neon1';
        $game["background"] = "https://images.ctfassets.net/m9t8fn3f4fre/6qw749IFEcseo46qaoyG2K/8c32ad6e4d003d721fa53b6cb6e79754/energoonz.jpg";
    }

    array_push($games, $game);
}

/*Formato de respuesta*/
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"]["AlertMessage"] = "Ejecucion exitosa";
$response["data"]["products"] = $games;
$response["data"]["total_count"] = intval($totalCount) ?? 0;
?>