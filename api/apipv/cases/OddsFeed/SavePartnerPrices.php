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
 * OddsFeed/SavePartnerPrices
 *
 * Este script actualiza los precios de las apuestas asociadas a eventos.
 *
 * @param array $params Arreglo de objetos que contienen los siguientes valores:
 * @param int $params->Id Identificador del detalle de la apuesta.
 * @param float $params->PartnerPrice Precio asociado al socio.
 *
 * @return array $response Respuesta con los siguientes valores:
 * - ErrorCode (int): Código de error (0 si no hay errores).
 * - ErrorDescription (string): Descripción del resultado o del error.
 */


if (oldCount($params) > 0) {
    try {


        /* Actualiza detalles de apuestas y almacena identificadores de eventos y juegos en arreglos. */
        $IntEventoApuestaDetalleMySqlDAO = new IntEventoApuestaDetalleMySqlDAO();


        $gamearray = array();
        $eventarray = array();
        foreach ($params as $param) {

            $IntEventoApuestaDetalle = new IntEventoApuestaDetalle($param->Id);
            $IntEventoApuestaDetalle->valor = $param->PartnerPrice;
            $IntEventoApuestaDetalleMySqlDAO->update($IntEventoApuestaDetalle);

            $IntEventoApuesta = new IntEventoApuesta($IntEventoApuestaDetalle->eventoapuestaId);

            array_push($eventarray, $param->Id);
            array_push($gamearray, $IntEventoApuesta->eventoId);


        }


        /* obtiene una transacción y la confirma, inicializando un array vacío. */
        $transaccion = $IntEventoApuestaDetalleMySqlDAO->getTransaction();

        $transaccion->commit();

        $result_array_final = array();
        $subid = "-";


        /* define variables y un arreglo asociativo con atributos de diferentes entidades. */
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


        /* Se construye un arreglo de condiciones para filtrar juegos y eventos específicos. */
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


        /* Convierte objetos a arrays y crea un array final vacío en PHP. */
        $what = json_decode(json_encode($what));
        $where = json_decode(json_encode($where));

        $array_final = array();


        if ($what->event != "" && $what->event != undefined) {

            /* Código inicializa un array vacío y define variables para almacenar campos y reglas. */
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

                    /* Verifica si '@in' existe y forma una cadena a partir de su contenido. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* Verifica si un valor es numérico y lo agrega a reglas si campo no está vacío. */
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


                    /* Código que asigna un campo basado en el valor de la variable $key. */
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

                    /* Verifica si la clave '@in' existe y no está vacía, luego concatena sus valores. */
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


            /* Se define un filtro y se obtienen detalles de apuestas usando ese filtro. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
            $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Se decodifica un JSON y se inicializa un arreglo vacío en PHP. */
            $apuestas = json_decode($apuestas);


            $final = array();

            foreach ($apuestas->data as $apuesta) {


                /* Se inicializan dos arreglos vacíos en PHP: `$array` y `$arrayd`. */
                $array = array();
                $arrayd = array();

                foreach ($what->event as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna el valor entero del ID de evento a un arreglo específico. */

                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                            break;

                        case "name":
                            /* asigna un valor de "opción" a un elemento del arreglo. */

                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion"};

                            break;

                        case "type":
                            /* Se asigna un valor a un arreglo basado en un campo específico. */

                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                            break;

                        case "type_1":
                            /* Asigna un valor de opción a un campo en un arreglo basado en tipo específico. */

                            $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                            break;
                        case "price":
                            /* Asignación de valor de apuesta a un arreglo basado en el campo "price". */

                            $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                            break;

                    }

                }


                /* Condicional que asigna valores a un arreglo si no hay eventos antiguos. */
                if (oldCount($what->event) == 0) {
                    $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                    $arrayd["name"] = $apuesta->{"int_apuesta_detalle.opcion"};
                    $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
                    $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
                    $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                }

                /* Se agrega un ID de apuesta a un arreglo y se verifica su estado. */
                array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
                $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                $objfirst = "event";

                if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
                    $arrayd["price"] = "1";
                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                /* maneja la estructura de un array, actualizando eventos y conteos. */
                if (is_array($what->market)) {

                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                    $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
                } else {
                    $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                }


                /* Se asigna la cadena "event" a la variable $objfin en PHP. */
                $objfin = "event";

            }


            /* asigna el contenido de $result_array a $result_array_final. */
            $result_array_final = $result_array;

        }


        if ($what->market != "" && $what->market != undefined) {

            /* Se inicializan variables para almacenar resultados y reglas en un arreglo. */
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];


            /* crea un filtro JSON y obtiene apuestas desde la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoApuesta = new IntEventoApuesta();
            $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);

            /* decodifica datos JSON y inicializa un arreglo vacío. */
            $apuestas = json_decode($apuestas);


            $final = array();

            foreach ($apuestas->data as $apuesta) {


                /* Se declaran dos arrays vacíos en PHP: $array y $arrayd. */
                $array = array();
                $arrayd = array();

                foreach ($what->market as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna un valor entero a un campo en un arreglo usando el ID de un evento. */

                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                            break;

                        case "name":
                            /* Asigna un valor a un arreglo desde una propiedad de un objeto basado en "name". */

                            $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                            break;

                        case "alias":
                            /* Asigna un valor abreviado de apuesta a un campo en un array. */

                            $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                            break;

                        case "order":
                            /* asigna un valor entero a un array basado en una condición específica. */

                            $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                            break;

                    }

                }


                /* Verifica si el conteo es cero y asigna valores a un array. */
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

                /* Evalúa la existencia y validez de eventos en un arreglo. */
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

                /* procesa apuestas y organiza datos en estructuras según condiciones específicas. */
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


            /* Se asigna un array a otra variable y se define una variable como "market". */
            $result_array_final = $result_array;
            $objfin = "market";

        }

        if (is_array($what->game)) {


            /* Se inicializan variables para campos, contador y reglas en un script. */
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->competition != "" && $where->competition != undefined) {

                foreach ($where->competition as $key => $value) {


                    /* asigna un valor a `$field` basado en el valor de `$key`. */
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

                    /* verifica un valor y concatenates elementos de un array en una cadena. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* verifica si un valor es numérico y agrega reglas a un array. */
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


                    /* asigna un campo específico según el valor de la variable "key". */
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


                    /* verifica si un valor es numérico y lo almacena en una regla. */
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


                    /* asigna un campo según el valor de la variable "key". */
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

                    /* Verifica y procesa un array, concatenando sus elementos en una cadena. */
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


            /* Se crea un filtro en formato JSON y se obtienen detalles de eventos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntEventoDetalle = new IntEventoDetalle();
            $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);

            /* decodifica JSON y inicializa arreglos y variables para eventos. */
            $eventos = json_decode($eventos);


            $final = array();
            $arrayd = array();
            $eventoid = "";

            foreach ($eventos->data as $evento) {


                /* Se inicializa un arreglo vacío en PHP para almacenar elementos posteriormente. */
                $array = array();

                foreach ($what->game as $campo) {


                    switch ($campo) {

                        case "team1_name":
                            /* Verifica si el tipo de evento es "TEAM1" y asigna un valor al array. */


                            if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};

                            }


                            break;

                        case "team2_name":
                            /* Asignación de valor a un array según condición de evento para equipo 2. */

                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                                $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }
                            break;

                        case "text_info":
                            /* Condicional para manejar eventos de tipo "TEAM1" en un caso específico. */

                            if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                                // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                            }
                            break;

                    }


                }
                if (oldCount($what->game) == 0) {

                    switch ($evento->{"int_evento_detalle.tipo"}) {

                        case "TEAM1":
                            /* Asignación de valores a un array según el caso "TEAM1" en un evento. */


                            $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                            $arrayd["info"]["virtual"][0] = array(
                                "AnimalName" => "",
                                "Number" => 1,
                                "PlayerName" => $evento->{"int_evento_detalle.valor"}
                            );

                            break;

                        case "TEAM2":
                            /* Asignación de datos del evento a un arreglo para el equipo 2 en formato estructurado. */

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

                    /* inicializa un array con información de un evento y un estado. */
                    $arrayd["game_number"] = $eventoid;
                    $arrayd["id"] = $eventoid;
                    $arrayd["start_ts"] = $eventoA->{"int_evento.fecha"};
                    $arrayd["type"] = 0;

                    $is_blocked = 0;


                    /* verifica el estado de un evento y bloquea si es necesario. */
                    if ($eventoA->{"int_evento.estado"} != "A") {
                        $is_blocked = 1;
                    }

                    $arrayd["is_blocked"] = $is_blocked;

                    if (is_array($what->market)) {

                        $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

                    }


                    /* verifica si es un arreglo y organiza datos en un arreglo resultado. */
                    if (is_array($what->competition)) {

                        $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                    } else {
                        $result_array["game"][$eventoid] = $arrayd;


                    }

                    /* Se define un array vacío en PHP llamado $arrayd. */
                    $arrayd = array();
                }

                /* Se asigna el ID del evento a una variable y se copia el objeto evento. */
                $eventoid = intval($evento->{"int_evento.evento_id"});
                $eventoA = $evento;

                //array_push($final, $array);

            }


            /* asigna valores a un arreglo y verifica el estado del evento. */
            $arrayd["game_number"] = $eventoid;
            $arrayd["id"] = $eventoid;
            $arrayd["start_ts"] = $evento->{"int_evento.fecha"};
            $arrayd["type"] = 0;
            $is_blocked = 0;

            if ($evento->{"int_evento.estado"} != "A") {
                $is_blocked = 1;
            }


            /* asigna valores a un arreglo basado en condiciones específicas. */
            $arrayd["is_blocked"] = $is_blocked;


            if (is_array($what->market)) {

                $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];

            }


            /* Verifica si 'competition' es un arreglo y almacena datos de eventos. */
            if (is_array($what->competition)) {

                $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                }
            } else {
                /* asigna un arreglo a un evento, verificando la cantidad de juegos. */

                $result_array["game"][$eventoid] = $arrayd;

                if (oldCount($result_array["game"]) == 1) {
                    //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                }
            }

            /* verifica si 'oldCount' es cero y agrega un nuevo evento. */
            if (oldCount($objinicio) == 0) {
                array_push($objinicio, intval($evento->{"int_evento.evento_id"}));
                $objfirst = "game";

            }

            $objfin = "game";


            /* Se asigna el contenido de `$result_array` a `$result_array_final`. */
            $result_array_final = $result_array;

        }

        if ($what->competition != "" && $what->competition != undefined) {

            /* Se inicia un array, variables y un arreglo para reglas en PHP. */
            $result_array = array();

            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->competition != "" && $where->competition != undefined) {

                foreach ($where->competition as $key => $value) {


                    /* asigna un valor a la variable $field según el caso del switch. */
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

                    /* verifica y procesa un array, creando una cadena con sus elementos. */
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


            /* Código crea un filtro JSON y obtiene competencias personalizadas mediante una consulta. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntCompetencia = new IntCompetencia();
            $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Decodifica un JSON de competencias y crea un array vacío para resultados. */
            $competencias = json_decode($competencias);


            $final = array();

            foreach ($competencias->data as $competencia) {


                /* Se crean dos arrays vacíos para almacenar datos en PHP. */
                $array = array();
                $arrayd = array();

                foreach ($what->competition as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asignación del ID de competencia convertido a entero en un array. */

                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                            break;

                        case "name":
                            /* Asigna el nombre de la competencia a un array según el campo especificado. */

                            $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                            break;

                        case "alias":
                            /* Asigna un valor a un arreglo usando una propiedad del objeto competencia en PHP. */

                            $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                            break;

                        case "order":
                            /* Asigna un valor entero al campo del array basado en competencia_id. */

                            $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                            break;

                    }

                }

                //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                /* Verifica arrays y asigna datos de competencia y región a estructuras adecuadas. */
                if (is_array($what->game)) {

                    $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];

                }
                if (is_array($what->region)) {

                    $result_array["region"][intval($competencia->{"int_competencia.region_id"})]["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                } else {
                    /* Asigna un array a una posición específica en el resultado basado en ID de competencia. */

                    $result_array["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                }

                /* Verifica si el contador es cero y agrega un ID de competencia al array. */
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($competencia->{"int_competencia.competencia_id"}));

                    $objfirst = "competition";
                }

            }


            /* Condición que verifica si hay una competencia, luego define una variable. */
            if (oldCount($competencias->data) == 1) {
                //$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

            }

            $objfin = "competition";


            /* Asignación de un array a otra variable en PHP, manteniendo los valores originales. */
            $result_array_final = $result_array;

        }

        if ($what->region != "" && $what->region != undefined) {

            /* Inicializa un arreglo vacío y variables para almacenar campos y reglas en PHP. */
            $result_array = array();
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->region != "" && $where->region != undefined) {

                foreach ($where->competition as $key => $value) {


                    /* Código que asigna valores a variables según el valor de la clave en un switch. */
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


                    /* Agrega reglas a un array si el campo no está vacío. */
                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* Se crea un filtro JSON y se obtienen regiones personalizadas desde la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntRegion = new IntRegion();
            $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Decodifica un JSON en PHP y crea un arreglo vacío para almacenar datos. */
            $regiones = json_decode($regiones);


            $final = array();

            foreach ($regiones->data as $region) {


                /* Se crean dos arreglos vacíos en PHP: `$array` y `$arrayd`. */
                $array = array();
                $arrayd = array();

                foreach ($what->competition as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Asigna el ID de región como entero al arreglo según el campo especificado. */

                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                            break;

                        case "name":
                            /* Asignación de un valor a un array a partir de un atributo de un objeto. */

                            $arrayd[$campo] = $region->{"int_region.nombre"};

                            break;

                        case "alias":
                            /* Asigna un valor abreviado de región a un array según el campo especificado. */

                            $arrayd[$campo] = $region->{"int_region.abreviado"};

                            break;

                        case "order":
                            /* Asignación del ID de región convertido a entero dentro de un arreglo. */

                            $arrayd[$campo] = intval($region->{"int_region.region_id"});

                            break;

                    }

                }


                /* Verifica si son arreglos y asigna datos de competiciones y deportes. */
                if (is_array($what->competition)) {

                    $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];

                }

                if (is_array($what->sport)) {

                    $result_array["sport"][intval($region->{"int_region.deporte_id"})]["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                } else {
                    /* Asignación de un arreglo a un índice específico según el ID de región. */

                    $result_array["region"][intval($region->{"int_region.region_id"})] = $arrayd;

                }

                /* Se verifica si 'objinicio' está vacío, agregando un 'region_id' y asignando 'objfirst'. */
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($region->{"int_region.region_id"}));

                    $objfirst = "region";
                }
            }


            /* verifica si hay una región y asigna un objeto a "region". */
            if (oldCount($regiones->data) == 1) {
                //$subid=$subid."301".$region->{"int_region.region_id"};

            }

            $objfin = "region";


            /* Se asigna el contenido de `$result_array` a `$result_array_final`. */
            $result_array_final = $result_array;


        }

        if ($what->sport != "" && $what->sport != undefined) {

            /* Inicializa variables para campos, contador y un arreglo de reglas en PHP. */
            $campos = "";
            $cont = 0;

            $rules = [];

            if ($where->sport != "" && $where->sport != undefined) {

                foreach ($where->sport as $key => $value) {


                    /* Asignación de valores a variables según diferentes casos del switch sobre la clave. */
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

                    /* Verifica si '@in' existe y no está vacío, luego concatena sus elementos. */
                    if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                        $op = "in";
                        $data_array = $value->{'@in'};
                        $data = "";

                        foreach ($data_array as $item) {
                            $data = $data . $item . ",";
                        }
                        $data = trim($data, ",");
                    }


                    /* agrega reglas a un arreglo si la variable $field no está vacía. */
                    if ($field != "") {
                        array_push($rules, array("field" => $field, "data" => $data, "op" => $op));

                    }

                }
            }


            /* Genera un filtro JSON y obtiene deportes personalizados de la base de datos. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            $IntDeporte = new IntDeporte();
            $sports = $IntDeporte->getDeportesCustom("int_deporte.*", "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);

            /* Se decodifica un JSON y se inicializa un arreglo vacío llamado $final. */
            $sports = json_decode($sports);


            $final = array();

            foreach ($sports->data as $sport) {


                /* Se crean dos arreglos vacíos en PHP: `$array` y `$arrayd`. */
                $array = array();
                $arrayd = array();

                foreach ($what->sport as $campo) {
                    switch ($campo) {
                        case "id":
                            /* Se asigna un valor entero al arreglo usando un identificador de deporte específico. */

                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                            break;

                        case "name":
                            /* Asigna el nombre del deporte a un array según el valor de "campo". */

                            $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                            break;

                        case "alias":
                            /* Asigna un valor a un array usando una propiedad de objeto en PHP. */

                            $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                            break;

                        case "order":
                            /* Asigna un valor entero al campo del arreglo basado en un objeto de deportes. */

                            $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                            break;

                    }

                }


                /* Asigna datos deportivos a un array, incluyendo región si es un array. */
                $final[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                if (is_array($what->region)) {

                    $arrayd["region"] = $result_array_final["sport"][intval($sport->{"int_deporte.deporte_id"})]["region"];

                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
                } else {
                    /* Guarda un arreglo de datos deportivos en el índice correspondiente de un array. */

                    $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;

                }


                /* Añade un ID de deporte a un arreglo si está vacío y asigna 'sport' a objfirst. */
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($sport->{"int_deporte.deporte_id"}));

                    $objfirst = "sport";
                }

                //array_push($final, $array);

            }


            /* verifica si hay un solo deporte y asigna un valor a `$subid`. */
            if (oldCount($sports->data) == 1) {
                //$subid=$subid."201".$sport->{"int_deporte.deporte_id"};

            }

            $result_array_final = $result_array;


            /* asigna la cadena "sport" a la variable $objfin. */
            $objfin = "sport";

        }


        /* Se crea un array con datos y se envía a través de WebSocket al usuario. */
        $responseW = array();

        $responseW = array("end" => $objfirst, "first" => $objfin, "ids" => $objinicio, "data" => $result_array_final);


        /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
        $WebsocketUsuario = new WebsocketUsuario(0, ($responseW));

        /* Envía un mensaje WebSocket y establece una respuesta de éxito. */
        $WebsocketUsuario->sendWSMessage();


        $response["ErrorCode"] = 0;
        $response["ErrorDescription"] = "success";

        $response = $response;

    } catch (Exception $e) {
        /* maneja excepciones y genera una respuesta con código y descripción del error. */

        $response["ErrorCode"] = $e->getCode();
        $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode() . $e->getMessage();

    }

}
