<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * OddsFeed/SavePartnerMatchBookings
 *
 * Este script actualiza el estado de los eventos asociados a partidos.
 *
 * @param array $params Arreglo de objetos que contienen los siguientes valores:
 * @param int $params->MatchId Identificador del partido.
 * @param bool $params->IsBlocked Indica si el partido está bloqueado.
 *
 * @return array $response Respuesta con los siguientes valores:
 * - ErrorCode (int): Código de error (0 si no hay errores).
 * - ErrorDescription (string): Descripción del resultado o del error.
 */


if (oldCount($params) > 0) {
    try {


        /* Actualiza estados de eventos en la base de datos según parámetros proporcionados. */
        $IntEventoMysqlDAO = new IntEventoMysqlDAO();

        $gamearray = array();
        foreach ($params as $param) {
            $state = "A";
            if ($param->IsBlocked) {
                $state = "I";

            }

            $IntEvento = new IntEvento($param->MatchId);
            $IntEvento->estado = $state;
            $IntEventoMysqlDAO->update($IntEvento);

            array_push($gamearray, $param->MatchId);


        }


        /* gestiona una transacción en MySQL y prepara un array para resultados. */
        $transaccion = $IntEventoMysqlDAO->getTransaction();

        $transaccion->commit();

        $result_array_final = array();
        $subid = "-";


        /* Se inicializan variables y un arreglo asociativo para datos de eventos deportivos. */
        $objfin = "";
        $objfirst = "";
        $objinicio = array();


        $what = array(
            "event" => [],
            "market" => [],
            "game" => [],
            "competition" => ["id", "name"],
            "region" => ["id"],
            "sport" => ["id", "alias"]
        );


        /* Se crea un filtro para seleccionar juegos por su identificador en un array. */
        $where = array(
            "game" => array(
                "id" => array(
                    "@in" => $gamearray
                )
            )
        );


        /* Convierte objetos en JSON a arreglos en PHP para su manipulación posterior. */
        $what = json_decode(json_encode($what));
        $where = json_decode(json_encode($where));

        $array_final = array();


        if ($what->event != "" && $what->event != undefined) {

            /* Inicializa un arreglo vacío y variables para almacenar campos y reglas. */
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->event != "" && $where->event != undefined) {

                foreach ($where->event as $key => $value) {


                    /* asigna un campo según el valor de la variable $key en PHP. */
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

                    /* verifica y procesa una lista de elementos para generar una cadena separada por comas. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* verifica un valor numérico y crea una regla si se proporciona un campo. */
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


                    /* asigna campos según el valor de la variable $key en un switch. */
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

                    /* Verifica si '@in' existe y no está vacío, construyendo una cadena de datos. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* verifica si un valor es numérico y agrega reglas a un arreglo. */
                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }

                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* Crea un filtro JSON y obtiene detalles de apuestas utilizando una clase personalizada. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
            $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Se decodifica un JSON y se inicializa un arreglo vacío. */
            $apuestas = json_decode($apuestas);


            $final = array();

            foreach ($apuestas->data as $apuesta) {


                /* Se crean dos arreglos vacíos en PHP, llamados $array y $arrayd. */
                $array = array();
                $arrayd = array();

                foreach ($what->event as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna un valor entero a un elemento de un array basado en una propiedad del objeto. */

                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                            break;

                        case "name":
                            /* Asignación de valor de opción a un elemento del array basado en el campo. */

                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion"};

                            break;

                        case "type":
                            /* Asignación de opción ID a un array según tipo específico en una estructura de código. */

                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                            break;

                        case "type_1":
                            /* Asignación de valor de "opcion_id" a un campo en un arreglo para "type_1". */

                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                            break;
                        case "price":
                            /* Asignación de valor de apuesta a un campo en un array según la clave "price". */

                            $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                            break;

                    }

                }


                /* Verifica si no hay eventos antiguos y asigna propiedades a un arreglo. */
                if (oldCount($what->event) == 0) {
                    $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                    $arrayd["name"] = $apuesta->{"int_apuesta_detalle.opcion"};
                    $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
                    $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
                    $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                }

                /* gestiona apuestas y actualiza valores según el estado del evento. */
                array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
                $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                $objfirst = "event";

                if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
                    $arrayd["price"] = "1";
                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                /* Se verifica si 'market' es un array y se actualizan datos en consecuencia. */
                if (is_array($what->market)) {

                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
                } else {
                    $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                }


                /* Se asigna el valor "event" a la variable $objfin en código PHP. */
                $objfin = "event";

            }


            /* asigna el contenido de $result_array a $result_array_final. */
            $result_array_final = $result_array;

        }


        if ($what->market != "" && $what->market != undefined) {

            /* Inicializa un arreglo, variables y un array para reglas en PHP. */
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];


            /* Se filtran apuestas usando reglas JSON, obteniendo resultados ordenados. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoApuesta = new IntEventoApuesta();
            $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);

            /* decodifica un JSON y crea un arreglo vacío para almacenar datos. */
            $apuestas = json_decode($apuestas);


            $final = array();

            foreach ($apuestas->data as $apuesta) {


                /* Se inicializan dos arreglos vacíos en PHP llamados `$array` y `$arrayd`. */
                $array = array();
                $arrayd = array();

                foreach ($what->market as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna el ID del evento de apuesta a un array, convirtiéndolo a entero. */

                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                            break;

                        case "name":
                            /* Asigna el nombre de la apuesta a un arreglo basado en el campo "name". */

                            $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                            break;

                        case "alias":
                            /* Asigna un valor abreviado a un campo en el array según el caso "alias". */

                            $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                            break;

                        case "order":
                            /* Asigna un valor entero a un campo en un array, basado en una condición. */

                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                            break;

                    }

                }


                /* Crea un arreglo con datos de apuesta si no existe un mercado previamente. */
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

                /* verifica condiciones para continuar, dependiendo de un evento en un array. */
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

                /* gestiona apuestas y organiza resultados en arrays según condiciones específicas. */
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


            /* Se asigna un array y se define una variable con el valor "market". */
            $result_array_final = $result_array;
            $objfin = "market";

        }

        if (is_array($what->game)) {


            /* Se inicializan variables para almacenar campos, contadores y reglas en PHP. */
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->competition != "" && $where->competition != undefined) {

                foreach ($where->competition as $key => $value) {


                    /* Asigna campos según el valor de $key en una estructura switch. */
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

                    /* verifica y procesa un array, concatenando sus elementos en una cadena. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* verifica si un valor es numérico y asigna reglas en consecuencia. */
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


                    /* Asignación de campos en función de la clave proporcionada mediante un switch. */
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

                    /* verifica un valor y construye una cadena a partir de un array. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* Verifica si un valor es numérico y crea reglas si hay un campo definido. */
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


                    /* asigna valores a la variable $field según el valor de $key. */
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

                    /* verifica y procesa un array, construyendo una cadena separada por comas. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* Verifica si el valor es numérico y lo agrega a las reglas si hay campo. */
                    if (is_numeric($value)) {
                        $op = "eq";
                        $data = $value;
                    }

                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* Se crea un filtro JSON y se obtienen detalles de eventos personalizados. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoDetalle = new IntEventoDetalle();
            $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Decodifica un JSON y prepara arrays para almacenar información de eventos. */
            $eventos = json_decode($eventos);


            $final = array();
            $arrayd = array();
            $eventoid = "";

            foreach ($eventos->data as $evento) {


                /* Se crea un array vacío en PHP, listo para almacenar elementos. */
                $array = array();

                foreach ($what->game as $campo) {


                    switch ($campo) {

                        case "team1_name":
                            /* Asignación condicional del valor del evento para el nombre del equipo 1. */


                            if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                            }


                            break;

                        case "team2_name":
                            /* Asigna un valor al arreglo si el tipo del evento es "TEAM2". */

                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }
                            break;

                        case "text_info":
                            /* Condición para procesar información de un evento según su tipo específico. */

                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                                // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }
                            break;

                    }


                }
                if (oldCount($what->game) == 0) {

                    switch ($evento->{"int_evento_detalle.tipo"}) {

                        case "TEAM1":
                            /* Asigna nombre de equipo y detalles a un array basado en evento. */


                            $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                            $arrayd["info"]["virtual"][0] = array(
                                "AnimalName" => "",
                                "Number" => 1,
                                "PlayerName" => $evento->{"int_evento_detalle.valor"}
                            );

                            break;

                        case "TEAM2":
                            /* Asigna nombre y datos del equipo 2 a un arreglo en PHP. */

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

                    /* Se asignan valores a un array y se inicializa una variable como no bloqueada. */
                    $arrayd["game_number"] = $eventoid;
                    $arrayd["id"] = $eventoid;
                    $arrayd["start_ts"] = $eventoA->{"int_evento.fecha"};
                    $arrayd["type"] = 0;

                    $is_blocked = 0;


                    /* verifica el estado de un evento y actualiza el estado de bloqueo. */
                    if ($eventoA->{"int_evento.estado"} != "A") {
                        $is_blocked = 1;
                    }

                    $arrayd["is_blocked"] = $is_blocked;

                    if (is_array($what->market)) {

                        $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

                    }


                    /* Verifica si "competition" es un array y asigna datos a "result_array". */
                    if (is_array($what->competition)) {

                        $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                    } else {
                        $result_array["game"][$eventoid] = $arrayd;


                    }

                    /* Se inicializa un arreglo vacío en PHP llamado $arrayd. */
                    $arrayd = array();
                }

                /* Se obtienen el identificador del evento y una copia del objeto evento. */
                $eventoid = intval($evento->{"int_evento.evento_id"});
                $eventoA = $evento;

                //array_push($final, $array);

            }


            /* asigna datos de un evento a un arreglo y verifica su estado. */
            $arrayd["game_number"] = $eventoid;
            $arrayd["id"] = $eventoid;
            $arrayd["start_ts"] = $evento->{"int_evento.fecha"};
            $arrayd["type"] = 0;
            $is_blocked = 0;

            if ($evento->{"int_evento.estado"} != "A") {
                $is_blocked = 1;
            }


            /* asigna estado de bloqueo y verifica si es un array de mercado. */
            $arrayd["is_blocked"] = $is_blocked;


            if (is_array($what->market)) {

                $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

            }


            /* Verifica si "competition" es un arreglo y actualiza "result_array" según evento. */
            if (is_array($what->competition)) {

                $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                }
            } else {
                /* Se agrega un evento a un arreglo si no hay eventos anteriores. */

                $result_array["game"][$eventoid] = $arrayd;

                if (oldCount($result_array["game"]) == 1) {
                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                }
            }

            /* Agrega un evento al arreglo si está vacío y establece el valor de $objfirst. */
            if (oldCount($objinicio) == 0) {
                array_push($objinicio, intval($evento->{"int_evento.evento_id"}));
                $objfirst = "game";

            }

            $objfin = "game";


            /* Copia el contenido de `$result_array` a `$result_array_final`. */
            $result_array_final = $result_array;

        }

        if ($what->competition != "" && $what->competition != undefined) {

            /* Código inicializa un array vacío y define variables para procesar datos. */
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->competition != "" && $where->competition != undefined) {

                foreach ($where->competition as $key => $value) {


                    /* asigna un campo según el valor de la variable $key. */
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

                    /* verifica si existe un valor en un array y lo concatena. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* Agrega reglas a un array si el campo no está vacío. */
                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* crea un filtro JSON y obtiene competencias personalizadas de una base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntCompetencia = new IntCompetencia();
            $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Se decodifica un JSON y se inicializa un array vacío llamado $final. */
            $competencias = json_decode($competencias);


            $final = array();

            foreach ($competencias->data as $competencia) {


                /* Se crean dos arreglos vacíos en PHP: `$array` y `$arrayd`. */
                $array = array();
                $arrayd = array();

                foreach ($what->competition as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna el ID de competencia convertido a entero en un arreglo específico. */

                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                            break;

                        case "name":
                            /* Asigna el nombre de una competencia a un arreglo basado en una clave específica. */

                            $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                            break;

                        case "alias":
                            /* Asigna un valor a un array a partir de un objeto basado en un campo específico. */

                            $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                            break;

                        case "order":
                            /* Asigna un valor entero a un campo de un arreglo basado en un objeto. */

                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                            break;

                    }

                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                /* verifica estructuras y asigna valores a arrays dependiendo de sus tipos. */
                if (is_array($what->game)) {

                    $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];

                }
                if (is_array($what->region)) {

                    $result_array["region"][intval($competencia->{"int_competencia.region_id"})]["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                } else {
                    /* Asignación de datos a un arreglo basado en el identificador de competencia. */

                    $result_array["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                }

                /* Verifica si `oldCount` es cero, agrega un ID a `objinicio` y define `objfirst`. */
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($competencia->{"int_competencia.competencia_id"}));

                    $objfirst = "competition";
                }

            }


            /* verifica si hay una sola competencia y asigna un objeto final. */
            if (oldCount($competencias->data) == 1) {
                //$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

            }

            $objfin = "competition";


            /* Asigna el contenido de `$result_array` a `$result_array_final`. */
            $result_array_final = $result_array;

        }

        if ($what->region != "" && $what->region != undefined) {

            /* Inicializa un arreglo y variables para almacenar campos y reglas de validación. */
            $result_array = array();
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->region != "" && $where->region != undefined) {

                foreach ($where->competition as $key => $value) {


                    /* establece un campo según el valor de la variable $key. */
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

                    /* Verifica si '@in' está definido y no vacío, luego concatena sus elementos. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* agrega reglas a un arreglo si el campo no está vacío. */
                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* Se filtran regiones personalizadas y se codifican en formato JSON para consulta. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntRegion = new IntRegion();
            $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Se decodifica un JSON de regiones y se inicializa un arreglo vacío. */
            $regiones = json_decode($regiones);


            $final = array();

            foreach ($regiones->data as $region) {


                /* Se crean dos arrays vacíos en PHP para utilizar posteriormente. */
                $array = array();
                $arrayd = array();

                foreach ($what->competition as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna un valor entero del ID de región a un arreglo basado en un campo específico. */

                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                            break;

                        case "name":
                            /* Se asigna el nombre de la región a un arreglo según el campo especificado. */

                            $arrayd[$campo] = $region->{"int_region.nombre"};

                            break;

                        case "alias":
                            /* Asigna el valor abreviado de una región a un campo en un arreglo. */

                            $arrayd[$campo] = $region->{"int_region.abreviado"};

                            break;

                        case "order":
                            /* Asigna un valor entero a un elemento del array según la región. */

                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                            break;

                    }

                }


                /* verifica si 'competition' y 'sport' son arrays para asignar valores. */
                if (is_array($what->competition)) {

                    $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];

                }

                if (is_array($what->sport)) {

                    $result_array["sport"][intval($region->{"int_region.deporte_id"})]["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                } else {
                    /* asigna un array a una región específica en un resultado. */

                    $result_array["region"][intval($region->{"int_region.region_id"})] = $arrayd;

                }

                /* Agrega un ID de región a un array si está vacío y establece un valor inicial. */
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($region->{"int_region.region_id"}));

                    $objfirst = "region";
                }
            }


            /* verifica si hay una región y asigna "region" a $objfin. */
            if (oldCount($regiones->data) == 1) {
                //$subid=$subid."301".$region->{"int_region.region_id"};

            }

            $objfin = "region";


            /* Asigna el contenido de `$result_array` a `$result_array_final`. */
            $result_array_final = $result_array;


        }

        if ($what->sport != "" && $what->sport != undefined) {

            /* Se inicializan variables para manejar campos y reglas en una estructura de datos. */
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->sport != "" && $where->sport != undefined) {

                foreach ($where->sport as $key => $value) {


                    /* asigna un campo específico basado en el valor de una clave. */
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

                    /* verifica y procesa un array de valores, generando una cadena delimitada por comas. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* agrega reglas a un arreglo si el campo no está vacío. */
                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* Se configura un filtro y se obtienen deportes desde la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntDeporte = new IntDeporte();
            $sports = $IntDeporte->getDeportesCustom("int_deporte.*", "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Se decodifica un JSON de deportes y se inicializa un array vacío. */
            $sports = json_decode($sports);


            $final = array();

            foreach ($sports->data as $sport) {


                /* Se definen dos arreglos vacíos en PHP: `$array` y `$arrayd`. */
                $array = array();
                $arrayd = array();

                foreach ($what->sport as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna un valor entero al arreglo según el ID del deporte. */

                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                            break;

                        case "name":
                            /* Asigna el nombre del deporte al arreglo según el campo especificado. */

                            $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                            break;

                        case "alias":
                            /* Se asigna el valor de "int_deporte.abreviado" a un campo en $arrayd. */

                            $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                            break;

                        case "order":
                            /* Asigna el ID del deporte como entero en un array basándose en el campo especificado. */

                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                            break;

                    }

                }


                /* Asignación y procesamiento de datos deportivos en un arreglo multidimensional. */
                $final[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                if (is_array($what->region)) {

                    $arrayd["region"] = $result_array_final["sport"][intval($sport->{"int_deporte.deporte_id"})]["region"];

                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
                } else {
                    /* asigna un array a un índice específico de otro array basado en una condición. */

                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;

                }


                /* Agrega un ID de deporte a un array si está vacío y establece "sport". */
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($sport->{"int_deporte.deporte_id"}));

                    $objfirst = "sport";
                }

                //array_push($final, $array);

            }


            /* Condicional que verifica si hay un solo elemento en $sports->data. */
            if (oldCount($sports->data) == 1) {
                //$subid=$subid."201".$sport->{"int_deporte.deporte_id"};

            }

            $result_array_final = $result_array;


            /* Se define una variable llamada $objfin con el valor "sport". */
            $objfin = "sport";

        }


        /* crea una respuesta y la envía mediante WebSocket a un usuario. */
        $responseW = array();

        $responseW = array("end" => $objfirst, "first" => $objfin, "ids" => $objinicio, "data" => $result_array_final);


        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        $WebsocketUsuario = new WebsocketUsuario(0, ($responseW));

        /* Envía un mensaje por WebSocket y devuelve una respuesta de éxito. */
        $WebsocketUsuario->sendWSMessage();


        $response["ErrorCode"] = 0;
        $response["ErrorDescription"] = "success";

        $response = $response;

    } catch (Exception $e) {
        /* Manejo de excepciones que captura errores y proporciona código y descripción del error. */

        $response["ErrorCode"] = $e->getCode();
        $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode() . $e->getMessage();

    }

}
