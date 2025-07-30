<?php


use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Pais;
use Backend\dto\UsuarioTorneo;
use Backend\mysql\TorneoInternoMySqlDAO;

/**
 * Este script obtiene información detallada sobre torneos internos, aplicando filtros y reglas.
 * Devuelve datos en formato JSON para su uso en aplicaciones front-end.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud:
 * @param string $params->StartTimeLocal Fecha de inicio en formato local.
 * @param string $params->EndTimeLocal Fecha de fin en formato local.
 * @param int $params->TypeId Identificador del tipo de torneo.
 * @param string $params->site_id ID del sitio.
 * @param bool $params->isMobile Indica si la solicitud proviene de un dispositivo móvil.
 * @param int $params->Limit Límite máximo de filas.
 * @param int $params->Offset Desplazamiento para la paginación.
 * @param string $params->State Estado del torneo (I: Inactivo, A: Activo).
 * @param string $params->Country País del usuario.
 * @param int $params->idTournament ID del torneo.
 * 
 *
 * @return array $response Respuesta generada por el script:
 *  - int $code Código de respuesta (0: Éxito).
 *  - string $rid Identificador de la solicitud.
 *  - array $data Datos de los torneos obtenidos.
 */

/**
 * Obtiene los encabezados de la solicitud HTTP.
 *
 * @return array Un arreglo asociativo de los encabezados de la solicitud.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}


/**
 * Ejecuta una consulta SQL y devuelve el resultado.
 *
 * @param string $sql La consulta SQL a ejecutar.
 * @return mixed El resultado de la consulta SQL.
 */
function execQuery($sql)
{
    $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();
    $return = $TorneoInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;
}


ini_set('memory_limit', '-1');

$ConfigurationEnvironment = new ConfigurationEnvironment();

$headers = getRequestHeaders();

if (true) {

    /**
     * Se obtienen los parámetros del objeto JSON y se realizan varias asignaciones y operaciones con fechas.
     */
    $params = $json->params; // Se obtienen los parámetros de la solicitud JSON.
    $ToDateLocal = $params->StartTimeLocal; // Fecha de inicio en formato local.
    $FromDateLocal = $params->EndTimeLocal; // Fecha de fin en formato local.
    $TypeId = $params->TypeId; // Identificador del tipo.
    $site_id = $json->params->site_id; // ID del sitio.
    $site_id = strtolower($site_id); // Se convierte el ID del sitio a minúsculas.

    $isMobile = strtolower($json->params->isMobile); // Se verifica si es móvil y se convierte a minúsculas.

    $FromDateLocal = $params->EndTimeLocal; // Se vuelve a asignar la fecha de fin en formato local.
    $FromDateLocal = $params->EndTimeLocal; // Se vuelve a asignar (duplicado).

    $MaxRows = $params->Limit; // Límite máximo de filas.
    $OrderedItem = $params->OrderedItem; // Elemento ordenado.
    $SkeepRows = ($params->Offset) * $MaxRows; // Filas a omitir calculadas a partir del desplazamiento.

    $StateType = $params->StateType; // Tipo de estado.
    $idTournament = $params->idTournament; // ID del torneo.

    $State = ($params->State == "I") ? 'I' : 'A'; // Se establece el estado según la condición.

    $Country = $params->Country; // País.

    $user_data = $json->session; // Datos del usuario de la sesión.

    $rules = []; // Inicialización del arreglo de reglas.

    $jsonfiltro = json_encode($filtro); // Se codifica el filtro a formato JSON.

    $TorneoInterno = new TorneoInterno(); // Instancia de la clase TorneoInterno.
    $TorneoDetalle = new TorneoDetalle(); // Instancia de la clase TorneoDetalle.

    $fechaActual = new DateTime(); // Se obtiene la fecha y hora actual.

    // Restar dos meses
    $fechaObjetivo = clone $fechaActual; // Se clona la fecha actual para manipulación.
    $fechaObjetivo->sub(new DateInterval('P2M')); // Se restan dos meses a la fecha objetivo.

    $fechaObjetivo->setDate($fechaObjetivo->format('Y'), $fechaObjetivo->format('m'), 1);

    $fechaMesAnterior = $fechaObjetivo->format('Y-m-d H:i:s');

    $fechaActual = $fechaActual->format("Y-m-d H:i:s");

    $rules = [];

    // Verifica el estado y agrega reglas si es "A" o "I"
    if ($State == "A" || $State == "I") {

        array_push($rules, array("field" => "torneo_interno.estado", "data" => "$State", "op" => "eq"));

    }
    $countryCode = strtolower($headers["Cf-Ipcountry"]);

// Asigna el país según el código del país y el site_id
    if ($countryCode != "" && $site_id == 8) {
        if ($countryCode == "ni") {
            $Country = 'ni';
        } elseif ($countryCode == "cr") {
            $Country = 'cr';
        } else {
            $Country = 'pe';
        }
    }

// Si el site_id es 8, establece el país vacío
    if ($site_id == 8) {
        $Country = '';
    }

    if ($UsuarioMandanteSite != null) {

        $Pais = new Pais($UsuarioMandanteSite->paisId);


        array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
        array_push($rules, array("field" => "torneo_detalle.valor", "data" => $Pais->paisId, "op" => "eq"));


    } else {

        if ($Country != "") {
            try {
                $Pais = new Pais("", $Country);

            } catch (Exception $e) {

            }
            array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
            array_push($rules, array("field" => "torneo_detalle.valor", "data" => $Pais->paisId, "op" => "eq"));

        } else {
            array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

        }
    }

    if ($idTournament != "") {
        // Agrega una regla para filtrar por el ID del torneo
        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $idTournament, "op" => "eq"));

    }
//array_push($rules, array("field" => "torneo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

//array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "torneo_interno.mandante", "data" => $site_id, "op" => "eq"));

    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => $fechaMesAnterior, "op" => "ge"));

//array_push($rules,array("field"=>"torneo_interno.fecha_fin","data"=>$fechaActual,"op"=>"le"));

    // Si el modo de depuración está activado, imprime las reglas
    if ($_ENV['debug']) {
        print_r($rules);
    }

    // Crea un filtro con las reglas y una operación de grupo 'AND'
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    // Si no se han especificado filas a omitir, se establece en 0
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    // Si no se ha especificado un ítem ordenado, se establece por defecto a 1
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    // Si no se ha especificado un número máximo de filas, se establece por defecto a 10
    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    // Convierte el filtro en formato JSON
    $jsonfiltro = json_encode($filtro);

//$torneos = $TorneoInterno->getTorneosCustom(" torneo_interno.* ", "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);
    $torneos = $TorneoDetalle->getTorneoDetallesCustom(" 
    torneo_interno.torneo_id,
    torneo_interno.descripcion,
    torneo_interno.nombre as titulotorneo,
    torneo_interno.fecha_inicio,
    torneo_interno.fecha_fin,
    torneo_interno.reglas,
    torneo_interno.cantidad_torneos,
    torneo_interno.tipo,
    torneo_interno.maximo_torneos,

    torneo_detalle.*", "torneo_interno.orden", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE, "torneo_interno.torneo_id");

    // Decodifica el resultado JSON en un objeto PHP
    $torneos = json_decode($torneos);

    // Inicializa un array para almacenar datos finales
    $final = [];

    // Inicializa un contador a 0
    $cont = 0;
    foreach ($torneos->data as $key => $value) {

        // Inicializa un array vacío
        $array = [];

        /*Asignamos valores al objeto de respuesta*/
        $array["id"] = $value->{"torneo_interno.torneo_id"};
        $array["nombre"] = $value->{"torneo_interno.titulotorneo"};
        $array["descripcion"] = $value->{"torneo_interno.descripcion"};
        $array["fecha_inicio"] = strtotime($value->{"torneo_interno.fecha_inicio"}) * 1000;
        $array["fecha_fin"] = strtotime($value->{"torneo_interno.fecha_fin"}) * 1000;

        $array["reglas"] = $value->{"torneo_interno.reglas"};
        $array["inscritos"] = $value->{"torneo_interno.cantidad_torneos"};
        $array["state"] = 0;
        $array["type"] = 0;
        $array["maximo"] = $value->{"torneo_interno.maximo_torneos"};
        $array["rangos"] = array();
        $array["premios"] = array();
        $array["ranking"] = array();
        $array["games"] = array();
        $array["userjoin"] = false;
        $array["typeRank"] = 0;

        /**
         * Se evalúa el tipo de torneo interno y se asigna la información correspondiente
         * al arreglo $array según el tipo identificado.
         */
        switch ($value->{"torneo_interno.tipo"}) {
            /*El código obtiene detalles de torneos desde una base de datos, aplica filtros basados en parámetros de la solicitud y devuelve los
            resultados en formato JSON.*/
            case "2":
                $array["Type"] = array(
                    "Id" => $value->{"torneo_interno.tipo"},
                    "Name" => "Bono Deposito",
                    "TypeId" => $value->{"torneo_interno.tipo"}
                );

                break;

            /*El código obtiene detalles de torneos desde una base de datos, aplica filtros basados en parámetros de la solicitud y
            devuelve los resultados en formato JSON.*/
            case "3":
                $array["Type"] = array(
                    "Id" => $value->{"torneo_interno.tipo"},
                    "Name" => "Bono No Deposito",
                    "TypeId" => $value->{"torneo_interno.tipo"}
                );

                break;

            /*El código obtiene detalles de torneos desde una base de datos, aplica filtros basados en parámetros de la solicitud y
            devuelve los resultados en formato JSON.*/
            case "4":
                $array["Type"] = array(
                    "Id" => $value->{"torneo_interno.tipo"},
                    "Name" => "Bono Cash",
                    "TypeId" => $value->{"torneo_interno.tipo"}
                );

                break;


            /*El código obtiene detalles de torneos desde una base de datos, aplica filtros basados en parámetros de la solicitud
            y devuelve los resultados en formato JSON.*/
            case "6":
                $array["Type"] = array(
                    "Id" => $value->{"torneo_interno.tipo"},
                    "Name" => "Freebet",
                    "TypeId" => $value->{"torneo_interno.tipo"}
                );

                break;


        }


        $games = "";


        if ($idTournament == "") {

// Inicializa un arreglo para las reglas de filtrado
            $rules = [];

// Agrega una regla para filtrar por el id del torneo
            array_push($rules, array("field" => "torneo_detalle.torneo_id", "data" => $array["id"], "op" => "eq"));

// Define el filtro con las reglas y la operación de agrupamiento
            $filtro = array("rules" => $rules, "groupOp" => "AND");

// Inicializa las variables de paginación
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 1000000000;

// Codifica el filtro en formato JSON
            $jsonfiltrodetalles = json_encode($filtro);

// Obtiene los detalles del torneo utilizando el filtro y otras configuraciones
            $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");

// Decodifica la respuesta en formato JSON
            $torneodetalles = json_decode($torneodetalles);

// Inicializa variables para controlar la suscripción del usuario
            $needSubscribe = false;
            $userpais = false;
            $userpaisCont = 0;

// Asigna el arreglo de proveedores
            $array['providers'] = $providers;
            $providers = array();
            $subproviders = array();
            $gamesOrder = [];

// Busca la visibilidad del usuario en los detalles del torneo
            $search = array_search('USUARIOVISIBILIDAD', array_column($torneodetalles->data, 'torneo_detalle.tipo'));

// Determina si el torneo es visible para el usuario
            $is_visible = $search === false ? true : boolval($torneodetalles->data[$search]->{'torneo_detalle.valor'});

            if (!$is_visible) {
                if (!empty($user_data)) {

                    $rules = [];

                    // Agrega reglas para filtrar por el id del usuario y el torneo
                    array_push($rules, ['field' => 'usuario_torneo.usuario_id', 'data' => $user_data->usuario, 'op' => 'eq']);
                    array_push($rules, ['field' => 'usuario_torneo.torneo_id', 'data' => $value->{'torneo_interno.torneo_id'}, 'op' => 'eq']);

                    // Codifica los filtros en formato JSON
                    $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                    $UsuarioTorneo = new UsuarioTorneo();

                    // Realiza la consulta para obtener los torneos asociados al usuario
                    $query = (string)$UsuarioTorneo->getUsuarioTorneosCustom('usuario_torneo.torneo_id', 'usuario_torneo.usutorneo_id', 'ASC', 1, 1, $filters, true);
                    // Decodifica la respuesta de la consulta
                    $query = json_decode($query, true);

                    // Verifica si el usuario no está asociado al torneo
                    if ($query['count'][0]['.count'] == 0) continue;

                } else continue;
            }

            foreach ($torneodetalles->data as $key2 => $value2) {

                switch ($value2->{"torneo_detalle.tipo"}) {


                    case "USERSUBSCRIBE":
                        // Verifica si el valor del torneo indica que se necesita suscribir
                        if ($value2->{"torneo_detalle.valor"} == 1) $needSubscribe = true;
                        break;
                    case "RANKAWARDMAT":
                        // Inicializa el array de premios para la moneda especificada si está vacío
                        if ($array['premios'][$value2->{"torneo_detalle.moneda"}] == "") {
                            $array['premios'][$value2->{"torneo_detalle.moneda"}] = array();
                        }

                        // Crea un nuevo array para los premios de tipo RANKAWARDMAT
                        $array2 = [];
                        $array2['pos'] = $value2->{"torneo_detalle.valor"};
                        $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                        $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                        $array2['tipo'] = 0;

                        // Agrega el nuevo premio al array de premios
                        array_push($array['premios'][$value2->{"torneo_detalle.moneda"}], ($array2));
                        break;


                    case "RANKAWARD":
                        // Inicializa el array de premios para la moneda especificada si está vacío
                        if ($array['premios'][$value2->{"torneo_detalle.moneda"}] == "") {
                            $array['premios'][$value2->{"torneo_detalle.moneda"}] = array();
                        }

                        // Crea un nuevo array para los premios de tipo RANKAWARD
                        $array2 = [];
                        $array2['pos'] = $value2->{"torneo_detalle.valor"};
                        $array2['desc'] = $value2->{"torneo_detalle.valor2"};
                        $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));
                        $array2['tipo'] = 1;

                        // Agrega el nuevo premio al array de premios
                        array_push($array['premios'][$value2->{"torneo_detalle.moneda"}], ($array2));
                        break;

                    case "RANK":
                        // Inicializa el array de rangos para la moneda especificada si está vacío
                        if ($array['rangos'][$value2->{"torneo_detalle.moneda"}] == "") {
                            $array['rangos'][$value2->{"torneo_detalle.moneda"}] = array();
                        }

                        // Crea un nuevo array para los rangos
                        $array2 = [];
                        $array2['inicial'] = $value2->{"torneo_detalle.valor"};
                        $array2['final'] = $value2->{"torneo_detalle.valor2"};
                        $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));

                        // Agrega el nuevo rango al array de rangos
                        array_push($array['rangos'][$value2->{"torneo_detalle.moneda"}], ($array2));
                        break;


                    case "RANKLINE":
                        /*
                         * Procesa el rango y lo añade al arreglo correspondiente según la moneda.
                         * */

                        if ($array['rangos'][$value2->{"torneo_detalle.moneda"}] == "") {
                            $array['rangos'][$value2->{"torneo_detalle.moneda"}] = array();
                        }

                        $array2 = [];
                        $array2['inicial'] = $value2->{"torneo_detalle.valor"};
                        $array2['final'] = $value2->{"torneo_detalle.valor2"};
                        $array2['valor'] = str_replace(' ', '', str_replace('	', '', $value2->{"torneo_detalle.valor3"}));

                        array_push($array['rangos'][$value2->{"torneo_detalle.moneda"}], ($array2));
                        $array['typeRank'] = 2;

                        break;
                    case "IMGPPALURL":
                        /*Almacena la URL de la imagen principal del torneo.*/
                        $array['image'] = $value2->{"torneo_detalle.valor"};

                        break;


                    case "RANKIMGURL":

                        $array['imageRank'] = $value2->{"torneo_detalle.valor"};

                        break;

                    case "BACKGROUNDURL":

                        $array['background'] = $value2->{"torneo_detalle.valor"};

                        break;

                    case "BACKGROUNDURL2":

                        $array['imgBg2'] = $value2->{"torneo_detalle.valor"};

                        break;
                    case "IMGCENTER":

                        $array['imgCentro'] = $value2->{"torneo_detalle.valor"};

                        break;
                    case "IMGCENTER2":

                        $array['imgC2'] = $value2->{"torneo_detalle.valor"};

                        break;
                    case "IMGRIGHT":

                        $array['imgDerecha'] = $value2->{"torneo_detalle.valor"};

                        break;
                    case "IMGAWARDS":

                        $array['imgAwards'] = $value2->{"torneo_detalle.valor"};

                        break;
                    case "CONDPAISUSER":

                        if ($json->session->logueado) {

                            $UsuarioMandante = new UsuarioMandante($json->session->usuario);

                            if ($value2->{"torneo_detalle.valor"} == $UsuarioMandante->getPaisId()) {
                                $userpais = true;
                            }
                        }
                        $userpaisCont++;

                        break;

                    case "VISIBILIDAD":
                        $array["type"] = $value2->{"torneo_detalle.valor"};
                        if ($array['type'] == 1) $needSubscribe = true;
                        break;
                    default:
                        // Se verifica si el tipo de torneo es 'ITAINMENT'
                        if (stristr($value2->{"torneo_detalle.tipo"}, 'ITAINMENT')) {

                            $idGame = explode("ITAINMENT", $value2->{"torneo_detalle.tipo"})[1];

                            // Se evalúa el ID del juego para determinar el comportamiento
                            switch ($idGame) {
                                case 1:
                                    // Se inicializa un array para almacenar la información del juego
                                    $array2 = [];
                                    $array2['id'] = $value2->{"torneo_detalle.torneodetalle_id"};
                                    $array2['name'] = "DEPORTE";
                                    $array2['img'] = $value2->{"torneo_detalle.descripcion"};
                                    $array2['icon_3'] = $value2->{"torneo_detalle.descripcion"};
                                    $array2['url'] = "/deportes/" . $value2->{"torneo_detalle.valor"};

                                    // Se añade la información del juego al array principal
                                    array_push($array['games'], ($array2));

                                    break;

                                case 3:
                                    // Se inicializa un array para almacenar la información del juego
                                    $array2 = [];
                                    $array2['id'] = $value2->{"torneo_detalle.torneodetalle_id"}; // ID del torneo detalle
                                    $array2['name'] = "LIGA"; // Nombre del juego
                                    $array2['img'] = $value2->{"torneo_detalle.descripcion"}; // Descripción como imagen
                                    $array2['icon_3'] = $value2->{"torneo_detalle.descripcion"}; // Ícono del juego
                                    $array2['url'] = "/deportes/liga/" . $value2->{"torneo_detalle.valor"}; // URL del juego

                                    // Se añade la información del juego al array principal
                                    array_push($array['games'], ($array2));
                                    break;

                                case 4:
                                    // Se inicializa un array para almacenar la información del juego
                                    $array2 = [];
                                    $array2['id'] = $value2->{"torneo_detalle.torneodetalle_id"}; // ID del torneo detalle
                                    $array2['name'] = "EVENTO"; // Nombre del juego
                                    $array2['img'] = $value2->{"torneo_detalle.descripcion"}; // Descripción como imagen
                                    $array2['icon_3'] = $value2->{"torneo_detalle.descripcion"}; // Ícono del juego
                                    $array2['url'] = "/deportes/partido/" . $value2->{"torneo_detalle.valor"}; // URL del juego

                                    // Se añade la información del juego al array principal
                                    array_push($array['games'], ($array2));

                                    break;

                                case 5:
                                    // Inicializa un array para almacenar detalles del juego
                                    $array2 = [];
                                    $array2['id'] = $value2->{"torneo_detalle.torneodetalle_id"};
                                    $array2['name'] = "MERCADO";
                                    $array2['img'] = $value2->{"torneo_detalle.descripcion"};
                                    $array2['icon_3'] = $value2->{"torneo_detalle.descripcion"};
                                    $array2['url'] = "#";

                                    // Agrega el array del juego al array principal 'games'
                                    array_push($array['games'], ($array2));

                                    break;

                            }

                        }

                        // Verifica si el tipo del detalle del torneo contiene 'CONDGAME'
                        if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                            // Extrae el ID del juego usando 'CONDGAME' como separador
                            $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                            // Asigna el valor correspondiente a 'gamesOrder' usando el ID del juego
                            $gamesOrder[$idGame] = $value2->{"torneo_detalle.valor2"};

                            // Concatenando el ID del juego a la cadena 'games'
                            $games = $games . $idGame . ",";

                        }

                        // Verifica si el tipo del detalle del torneo contiene 'CONDPROVIDER'
                        if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                            // Extrae el ID del proveedor usando 'CONDPROVIDER' como separador
                            $idGame = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                            // Agrega el ID del proveedor al array 'providers'
                            array_push($providers, $idGame);

                        }

                        // Verifica si el tipo del detalle del torneo contiene 'CONDSUBPROVIDER'
                        if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                            // Extrae el ID del subproveedor usando 'CONDSUBPROVIDER' como separador
                            $idGame = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                            // Agrega el ID del subproveedor al array 'subproviders'
                            array_push($subproviders, $idGame);

                        }

                        break;
                }


            }

            /**
             * Comprobamos si el id del torneo interno es 1721.
             * Si es verdadero, se agregan varias entradas a la lista de juegos.
             * Cada entrada incluye un id, un nombre, una imagen, un icono y una URL.
             */
            if ($value->{"torneo_interno.torneo_id"} == 1721) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200058.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200058.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200085.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200085.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200103.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200103.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200118.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200118.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));

            }

            /**
             * Se verifica si el torneo interno tiene el ID 1721.
             * Si es verdadero, se crea una lista temporal de juegos
             * que luego se agrega al array de juegos existente.
             */
            if ($value->{"torneo_interno.torneo_id"} == 1721) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200058.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200058.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200085.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200085.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200103.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200103.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657200118.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657200118.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));

            }

            /**
             * Verifica si el ID del torneo interno es 1727.
             * Si es así, se agregan varios elementos de juego a la lista de juegos.
             * Cada elemento tiene un ID, un nombre, una imagen, un icono y una URL.
             */
            if ($value->{"torneo_interno.torneo_id"} == 1727) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657316500.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657316500.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657316531.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657316531.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657316544.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657316544.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1657316558.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1657316558.png';
                $lista_temp2['url'] = "/deportes";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.torneo_id"} == 1082) {
                /**
                 * Se inicializa un arreglo temporal para almacenar información de juegos.
                 * Este arreglo se usará para agregar múltiples juegos a la lista existente en $array['games'].
                 * Cada juego tiene un id, nombre, imagen, ícono y una URL asociada.
                 */

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530274.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530274.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530306.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530306.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530327.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530327.png';
                $lista_temp2['url'] = "/betgamestv";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530367.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530367.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530397.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530397.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530428.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530428.png';
                $lista_temp2['url'] = "/betgamestv";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530454.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530454.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530494.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530494.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1633530512.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1633530512.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.torneo_id"} == 983) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }
            if ($value->{"torneo_interno.torneo_id"} == 1049) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/betgamestv";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.torneo_id"} == 1022) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/TVBET";
                array_push($array['games'], ($lista_temp2));

            }

            /**
             * Verifica si el ID del torneo interno es 1676.
             * Si se cumple la condición, se agregan varios elementos a la lista de juegos dentro del array.
             * Cada elemento incluye un ID, nombre, imagen, icono y URL.
             */
            if ($value->{"torneo_interno.torneo_id"} == 1676) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));

            }

            /**
             * Verifica si el torneo interno tiene un ID específico (1873).
             * Si es así, se agregan ciertos elementos a la lista de juegos (`$array['games']`).
             * Cada elemento contiene un ID, un nombre, una imagen, un icono y una URL.
             */
            if ($value->{"torneo_interno.torneo_id"} == 1873) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino-vivo/proveedor/EEVOLUTION";
                array_push($array['games'], ($lista_temp2));

            }

            /**
             * Verifica si el ID del torneo interno se encuentra entre los IDs específicos.
             * Si es así, se añaden varios elementos al array de juegos con diferentes URLs e imágenes.
             */
            if ($value->{"torneo_interno.torneo_id"} == 977 || $value->{"torneo_interno.torneo_id"} == 1317 || $value->{"torneo_interno.torneo_id"} == 1400 || $value->{"torneo_interno.torneo_id"} == 1478 || $value->{"torneo_interno.torneo_id"} == 1547 || $value->{"torneo_interno.torneo_id"} == 1625) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }
            if ($value->{"torneo_interno.torneo_id"} == 1124) {
                /**
                 * Se inicializa un arreglo temporal para almacenar información de juegos.
                 * Cada juego tiene un ID, nombre, imagen, icono, y URL.
                 * Los datos de los juegos se añaden al arreglo principal `$array['games']`.
                 */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822739.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822739.png';
                $lista_temp2['url'] = "/live-casino-vivo";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822766.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822766.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822838.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822838.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822852.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822852.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];                // Inicializa un array temporal para almacenar los datos del juego
                $lista_temp2['id'] = '0'; // ID del juego, en este caso '0' para juegos sin ID específico
                $lista_temp2['name'] = ""; // Nombre del juego, vacío en este caso
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822872.png'; // URL de la imagen del juego
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822872.png'; // URL del icono del juego
                $lista_temp2['url'] = "/live-casino"; // URL de acceso al juego
                array_push($array['games'], ($lista_temp2)); // Agrega el juego al array principal

                // Inicializa un array temporal para el segundo juego
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822885.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822885.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822899.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822899.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822910.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822910.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1634822920.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1634822920.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }
            if ($value->{"torneo_interno.torneo_id"} == 1659) {

                /* crea un arreglo asociativo en PHP con información de un elemento. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1654619300.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1654619300.png';
                $lista_temp2['url'] = "/apuestas";

                /* Se agrega un nuevo juego a un array, inicializando sus propiedades. */
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1654619316.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1654619316.png';

                /* agrega un elemento con información a un arreglo de juegos. */
                $lista_temp2['url'] = "/apuestas";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1654619332.png';

                /* asigna valores y agrega elementos a un array en PHP. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1654619332.png';
                $lista_temp2['url'] = "/apuestas";
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";

                /* Se asignan valores a un array y se agrega a otro array. */
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1654619351.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1654619351.png';
                $lista_temp2['url'] = "/apuestas";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se crea un juego con datos y se agrega a un array. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1654619370.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1654619370.png';
                $lista_temp2['url'] = "/apuestas";
                array_push($array['games'], ($lista_temp2));


            }

            /* Condicional que agrega un elemento a un array si torneo_id es 1757. */
            if ($value->{"torneo_interno.torneo_id"} == 1757) {
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1659199417.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1659199417.png';
                $lista_temp2['url'] = "/apuestas";
                array_push($array['games'], ($lista_temp2));


            }

            if (count($subproviders) > 0 && $games == '') {


                /* Código que define reglas para filtrar productos según condiciones específicas. */
                $es_movil = false;
                $whereDisp = " AND producto.desktop='S'";

                $rules2 = [];
//array_push($rules2, array("field" => "producto_mandante.prodmandante_id", "data" => $games, "op" => "in"));
                array_push($rules2, array("field" => "producto.subproveedor_id", "data" => implode(',', $subproviders), "op" => "eq"));

                /* Añade reglas de filtrado a un arreglo basado en condiciones específicas. */
                array_push($rules2, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
                array_push($rules2, array("field" => "producto.mostrar", "data" => "S", "op" => "eq"));
                array_push($rules2, array("field" => "producto_mandante.mandante", "data" => $site_id, "op" => "eq"));

                if ($site_id == 0) {

                    array_push($rules2, array("field" => "producto_mandante.pais_id", "data" => $Pais->paisId, "op" => "eq"));
                }


// Any mobile device (phones or tablets).

                /* determina la disponibilidad del producto según el tipo de dispositivo. */
                if ($isMobile == '1') {
                    $whereDisp = " AND producto.mobile='S'";
                    array_push($rules2, array("field" => "producto.mobile", "data" => "S", "op" => "eq"));
                }
                if ($isMobile != '1') {
                    $whereDisp = " AND producto.desktop='S'";
                    array_push($rules2, array("field" => "producto.desktop", "data" => "S", "op" => "eq"));
                }

                /* Se crea un filtro y se obtienen productos desde la base de datos. */
                $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                $jsonfiltro2 = json_encode($filtro2);


                $ProductoMandante = new ProductoMandante();
                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1000, $jsonfiltro2, true);

                /* procesa un JSON de productos, extrayendo datos y formateándolos en un array. */
                $productos = json_decode($productos);

                foreach ($productos->data as $key2 => $value2) {

                    $lista_temp2 = [];
                    $lista_temp2['id'] = $value2->{'producto_mandante.prodmandante_id'};
                    $lista_temp2['name'] = $value2->{'producto.descripcion'};
                    $lista_temp2['img'] = $value2->{'producto.image_url'};
                    $lista_temp2['icon_3'] = $value2->{'producto.image_url2'};
                    $lista_temp2['url'] = "/new-casino/" . $value2->{'producto_mandante.prodmandante_id'};

                    array_push($array['games'], ($lista_temp2));

                }

            }
            if ($value->{"torneo_interno.tipo"} == 3 && (in_array('68', $providers)) && $site_id == "0") {

                /* Se crea un array asociativo con información de un elemento, incluyendo imágenes y URL. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/vivogaming";

                /* Agrega un nuevo juego con ID, nombre y URL de imagen a un arreglo. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';

                /* Se agrega un nuevo juego a la lista con icono y URL especificados. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un objeto de juego y se añade a una lista de juegos. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se construye un nuevo juego y se agrega a la lista de games. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));


                /* Código PHP inicializa un array con datos relacionados a un recurso de juego. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/vivogaming";

                /* Se agrega un nuevo juego a un array con propiedades específicas. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';

                /* asigna valores a un arreglo y agrega elementos a otro. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un arreglo temporal con datos de un juego y se añade a otro arreglo. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Crea un registro de juego con datos en la variable `$lista_temp2`. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/vivogaming";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.tipo"} == 3 && (in_array('185', $subproviders)) && $site_id == "0") {

                /* Se crea un array asociativo con datos sobre un recurso digital. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/pragmatic-vivo";

                /* Agrega un nuevo juego al array 'games' con datos predeterminados. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';

                /* Se asignan valores a variables y se añaden a un arreglo de juegos. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/pragmatic-vivo";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un array con datos de un juego y se agrega a otro array. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['url'] = "/pragmatic-vivo";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se crea un array con datos de un juego y se agrega a otro array. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['url'] = "/pragmatic-vivo";
                array_push($array['games'], ($lista_temp2));


                /* define un arreglo asociativo con información sobre un recurso. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/pragmatic-vivo";

                /* Agrega un nuevo juego con atributos a un arreglo en PHP. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';

                /* Se asignan valores a un arreglo y se agrega a otro arreglo. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Define un juego y lo agrega a una lista de juegos. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['url'] = "/pragmatic-vivo";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se crea un array con datos de un juego y se añade a otro array. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/pragmatic-vivo";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.tipo"} == 3 && $site_id == "8") {

                /* Código en PHP que inicializa un array con información sobre un casino en línea. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino";

                /* agrega un nuevo juego al arreglo 'games' con detalles específicos. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';

                /* asigna valores a un array y lo agrega a otro array. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un juego con datos e imágenes, y se agrega a un array. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se crea un arreglo con información de un juego y se añade a otro arreglo. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));


                /* Crea un arreglo en PHP con datos de un casino en línea. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino";

                /* agrega un elemento con detalles de juego a un arreglo existente. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';

                /* Se agrega información a un arreglo sobre un juego y se reinicia la variable. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un elemento de juego y se agrega a un array de juegos. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Crea un elemento de videojuego y lo agrega a un arreglo. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.tipo"} == 3 && $site_id == "14") {

                /* Se crea un array asociativo con información sobre un casino en vivo. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813282.png';
                $lista_temp2['url'] = "/live-casino";

                /* Se agrega un elemento nuevo a un arreglo de juegos con una imagen específica. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';

                /* Se agrega un elemento con URL e ícono a un array de juegos. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813318.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un elemento con datos de juego y se añade a un array. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813346.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* crea un elemento de juego y lo agrega a un array. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813372.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));


                /* Se crea un array asociativo con información sobre un casino en vivo. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813398.png';
                $lista_temp2['url'] = "/live-casino";

                /* Agrega un nuevo juego con ID, nombre e imagen a un array existente. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';

                /* Se asignan valores a un array y se añaden a otro array de juegos. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813420.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';

                /* Se crea un objeto con datos de juego y se añade a un array. */
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813440.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se crea un array para un juego con propiedades específicas y se agrega a otro array. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1579813462.png';
                $lista_temp2['url'] = "/live-casino";
                array_push($array['games'], ($lista_temp2));

            }

            if ($value->{"torneo_interno.tipo"} == 4) {

                /* Código en PHP que inicializa un array con datos específicos para una entidad. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1566489458.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1566489458.png';
                $lista_temp2['url'] = "/virtualnew";


                /* Añade un juego con información inicial a un array en PHP. */
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1566489487.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1566489487.png';

                /* Se agrega una nueva entrada a un arreglo de juegos, luego se reinicia una variable. */
                $lista_temp2['url'] = "/virtualnew";

                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";

                /* Se asignan valores a un arreglo y se añade a otro arreglo principal. */
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1566489506.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1566489506.png';
                $lista_temp2['url'] = "/virtualnew";

                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];

                /* Se crea un nuevo elemento en el array 'games' con datos de juego. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1566489532.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1566489532.png';
                $lista_temp2['url'] = "/virtualnew";

                array_push($array['games'], ($lista_temp2));

            }
            if ($value->{"torneo_interno.torneo_id"} == 2120) {

                /* Se crea un array con información de un elemento, incluyendo id, nombre, imagen y URL. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207686.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207686.png';
                $lista_temp2['url'] = "/deportes";


                /* Agrega un nuevo juego con ID, nombre e imágenes a un arreglo. */
                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207696.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207696.png';

                /* Agrega una URL de deportes a un arreglo de juegos y reinicia variables. */
                $lista_temp2['url'] = "/deportes";

                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";

                /* Se asignan valores a `$lista_temp2` y se agrega a `$array['games']`. */
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207706.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207706.png';
                $lista_temp2['url'] = "/deportes";

                array_push($array['games'], ($lista_temp2));
                $lista_temp2 = [];

                /* Se crea un elemento con datos y se agrega a un array de juegos. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207717.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207717.png';
                $lista_temp2['url'] = "/deportes";

                array_push($array['games'], ($lista_temp2));


                /* Se crea un arreglo PHP con datos relacionados a un recurso de deportes. */
                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207726.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207726.png';
                $lista_temp2['url'] = "/deportes";


                /* Se añade un nuevo juego a un array con detalles específicos. */
                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207735.png';

                /* Agrega un elemento con imagen y URL a un array de juegos. */
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207735.png';
                $lista_temp2['url'] = "/deportes";

                array_push($array['games'], ($lista_temp2));

                $lista_temp2 = [];

                /* Se crea un arreglo con detalles de un juego y se agrega a otro arreglo. */
                $lista_temp2['id'] = '0';
                $lista_temp2['name'] = "";
                $lista_temp2['img'] = 'https://images.virtualsoft.tech/m/msjT1671207744.png';
                $lista_temp2['icon_3'] = 'https://images.virtualsoft.tech/m/msjT1671207744.png';
                $lista_temp2['url'] = "/deportes";

                array_push($array['games'], ($lista_temp2));

            }


            if ($needSubscribe) {


                /* Consulta torneos disponibles para un usuario específico y maneja excepciones. */
                if ($UsuarioMandanteSite != null) {


                    try {
//Obtenemos todos los torneos disponibles
                        $sqlTorneo = "select a.usutorneo_id,a.torneo_id,a.apostado,a.fecha_crea,torneo_interno.condicional,torneo_interno.tipo from usuario_torneo a INNER JOIN torneo_interno ON (torneo_interno.torneo_id = a.torneo_id ) where  a.estado='A' AND (torneo_interno.tipo = 2 OR torneo_interno.tipo = 3 OR torneo_interno.tipo = 1)  AND a.torneo_id='" . $array['id'] . "'  AND a.usuario_id='" . $UsuarioMandanteSite->usumandanteId . "'";
                        $torneosDisponibles = execQuery($sqlTorneo);

                        if (count($torneosDisponibles) > 0) {
                            $array['userjoin'] = true;
                        }

                    } catch (Exception $e) {

                    }
                }
            } else if ($array['userjoin'] === false) $array['userjoin'] = true;

            if ($games != "") {

                /* Se concatena '0' a $games y se establece una condición para desktop. */
                $games = $games . '0';

                $es_movil = false;
                $whereDisp = " AND producto.desktop='S'";

                $rules2 = [];

                /* Se añaden reglas a un array según condiciones específicas de productos y países. */
                array_push($rules2, array("field" => "producto_mandante.prodmandante_id", "data" => $games, "op" => "in"));
                array_push($rules2, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
                array_push($rules2, array("field" => "producto.mostrar", "data" => "S", "op" => "eq"));

                if ($site_id == 0) {

                    array_push($rules2, array("field" => "producto_mandante.pais_id", "data" => $Pais->paisId, "op" => "eq"));
                }


// Any mobile device (phones or tablets).

                /* Condiciona la consulta SQL según si es móvil o escritorio. */
                if ($isMobile == '1') {
                    $whereDisp = " AND producto.mobile='S'";
                    array_push($rules2, array("field" => "producto.mobile", "data" => "S", "op" => "eq"));
                }
                if ($isMobile != '1') {
                    $whereDisp = " AND producto.desktop='S'";
                    array_push($rules2, array("field" => "producto.desktop", "data" => "S", "op" => "eq"));
                }

                /* Se crea un filtro JSON y se obtienen productos relacionados con mandantes. */
                $filtro2 = array("rules" => $rules2, "groupOp" => "AND");
                $jsonfiltro2 = json_encode($filtro2);


                $ProductoMandante = new ProductoMandante();
                $productos = $ProductoMandante->getProductosMandanteCustom(" producto_mandante.*,mandante.*,producto.* ", "producto_mandante.prodmandante_id", "asc", 0, 1000, $jsonfiltro2, true);

                /* reordena productos según un orden especificado en `$gamesOrder`. */
                $productos = json_decode($productos);
                $newProducts = (object)['data' => []];

//Consultando orden que deberían tener los juegos
                foreach ($productos->data as $producto) {
                    $idProduct = $producto->{'producto_mandante.prodmandante_id'};
                    $posicion = $gamesOrder[$idProduct];
                    $newProducts->data[$posicion] = $producto;
                }

                /* Ordena productos y asigna datos a un nuevo arreglo para visualización en el sitio. */
                ksort($newProducts->data);
                $productos->data = $newProducts->data;

                foreach ($productos->data as $key2 => $value2) {

                    $lista_temp2 = [];
                    $lista_temp2['id'] = $value2->{'producto_mandante.prodmandante_id'};
                    $lista_temp2['name'] = $value2->{'producto.descripcion'};
                    $lista_temp2['img'] = $value2->{'producto.image_url'};
                    $lista_temp2['icon_3'] = $value2->{'producto.image_url2'};
                    $lista_temp2['url'] = "/new-casino/" . $value2->{'producto_mandante.prodmandante_id'};

                    array_push($array['games'], ($lista_temp2));

                }

            }


        }
        if ($idTournament != "") {


            /* Se verifica si el usuario está logueado y se asigna su ID. */
            $usuarioIdLogueado = '';
            if ($json->session->logueado) {
                $usuarioIdLogueado = $json->session->usuario;
            }

            $rules = [];


            /* Se construye un filtro de reglas para una consulta SQL. */
            array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $array["id"], "op" => "eq"));
            array_push($rules, array("field" => "usuario_torneo.valor", "data" => '0', "op" => "gt"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $SkeepRows = 0;


            /* Código PHP para inicializar variables y convertir un arreglo a formato JSON. */
            $OrderedItem = 1;

            $MaxRows = 20;

            $jsonfiltrodetalles = json_encode($filtro);


            $UsuarioTorneo = new UsuarioTorneo();

            /* Genera un ranking de torneos filtrando y procesando datos de usuarios. */
            $usuariosTorneos = $UsuarioTorneo->getUsuarioTorneosCustom(" usuario_torneo.valor,torneo_interno.*,usuario_mandante.usumandante_id,usuario_mandante.usuario_mandante,usuario_mandante.nombres ", "usuario_torneo.valor", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, "");
            $usuariosTorneos = json_decode($usuariosTorneos);

            $pos = 1;
            $entre10 = false;
            foreach ($usuariosTorneos->data as $key2 => $value2) {

                $lista_temp2 = [];
                $lista_temp2['pos'] = $pos;
                $lista_temp2['user'] = $value2->{'usuario_mandante.usuario_mandante'} . "**" . $ConfigurationEnvironment->DepurarCaracteres($value2->{'usuario_mandante.nombres'});
                $lista_temp2['user'] = $ConfigurationEnvironment->DepurarCaracteres(substr($lista_temp2['user'], 0, 16));
                $lista_temp2['valor'] = $value2->{'usuario_torneo.valor'};
                $lista_temp2['valor'] = round(floatval($lista_temp2['valor']), 2);
                $lista_temp2['is_user'] = false;
                if ($value2->{'usuario_mandante.usumandante_id'} == $usuarioIdLogueado) {
                    $entre10 = true;
                    $lista_temp2['is_user'] = true;

                }

                array_push($array["ranking"], ($lista_temp2));

                $pos++;

            }

            if (!$entre10 && $usuarioIdLogueado != '') {


                /* Se definen reglas de filtrado para consultas en base de datos. */
                $rules = [];


                array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $array["id"], "op" => "eq"));
                array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => $usuarioIdLogueado, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");


                /* Se configuran variables para el manejo de datos en un sistema de filtrado. */
                $SkeepRows = 0;

                $OrderedItem = 1;

                $MaxRows = 10;

                $jsonfiltrodetalles = json_encode($filtro);


                /* Se crea un objeto UsuarioTorneo y se obtienen datos en formato JSON. */
                $UsuarioTorneo = new UsuarioTorneo();
                $usuariosTorneos = $UsuarioTorneo->getUsuarioTorneosCustom(" usuario_torneo.valor,torneo_interno.*,position.position,usuario_mandante.usumandante_id,usuario_mandante.usuario_mandante,usuario_mandante.nombres ", "usuario_torneo.valor", "desc", $SkeepRows, $MaxRows, $jsonfiltrodetalles, TRUE, true);
                $usuariosTorneos = json_decode($usuariosTorneos);


                /* Recorre datos de usuarios en torneos y organiza información en un arreglo ranking. */
                foreach ($usuariosTorneos->data as $key2 => $value2) {

                    $lista_temp2 = [];
                    $lista_temp2['pos'] = $value2->{'position.position'};
                    $lista_temp2['user'] = $value2->{'usuario_mandante.usuario_mandante'} . "**" . $ConfigurationEnvironment->DepurarCaracteres($value2->{'usuario_mandante.nombres'});
                    $lista_temp2['user'] = $ConfigurationEnvironment->DepurarCaracteres(substr($lista_temp2['user'], 0, 16));
                    $lista_temp2['valor'] = $value2->{'usuario_torneo.valor'};
                    $lista_temp2['valor'] = round(floatval($lista_temp2['valor']), 2);
                    $lista_temp2['is_user'] = false;
                    if ($value2->{'usuario_mandante.usumandante_id'} == $usuarioIdLogueado) {
                        $entre10 = true;
                        $lista_temp2['is_user'] = true;

                    }

                    array_push($array["ranking"], ($lista_temp2));

                    $pos++;

                }

            }


        }


        /* Condicional que asigna o agrega un arreglo basado en el valor de $idTournament. */
        if ($idTournament != "") {
            $final = $array;

        } else {
            array_push($final, $array);

        }
    }

}

/*Generación formato de respuesta*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;
if ($_ENV['debug']) {
    print_r('entro');
    print_r($response);
    exit();
}