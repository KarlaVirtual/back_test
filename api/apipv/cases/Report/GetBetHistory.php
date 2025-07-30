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
use Backend\dto\UsuarioDeporteResumen;
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
 * Obtiene el historial de apuestas según los filtros especificados
 *
 * Este endpoint permite consultar el historial de apuestas realizadas:
 * - Filtra por fechas, región, moneda, cliente y otros parámetros
 * - Permite búsqueda por ID de ticket, jugador, IP
 * - Soporta paginación y ordenamiento de resultados
 *
 * @param object $params Parámetros de entrada
 * @param string $params ->StartDateLocal Fecha inicial en formato Y-m-d H:i:s
 * @param string $params ->EndDateLocal Fecha final en formato Y-m-d H:i:s
 * @param string $params ->Region ID de la región
 * @param string $params ->Currency Código de moneda
 * @param string $params ->ClientId ID del cliente
 * @param string $params ->OrderedItem Campo por el cual ordenar
 *
 * @return array Estructura de respuesta JSON con el siguiente formato:
 * {
 *   "HasError": boolean,      // Indica si hubo error en la operación
 *   "AlertType": string,      // Tipo de alerta (success/error)
 *   "AlertMessage": string,   // Mensaje descriptivo
 *   "Data": {                 // Datos de respuesta
 *     "Tickets": array,       // Lista de tickets/apuestas
 *     "TotalRows": int,       // Total de registros encontrados
 *     "FilteredRows": int     // Registros en página actual
 *   }
 * }
 * @throws Exception Si ocurre un error durante el proceso
 */
try {
    $Helpers = new \Backend\dto\Helpers();

    /* Se crea un objeto, se recibe JSON y se formatea una fecha. */
    $ItTicketEnc = new ItTicketEnc();

    $params = file_get_contents('php://input');
    $params = json_decode($params);

    $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->EndDateLocal) . ' +1 day'));

    /* procesa fechas y parámetros de entrada para una solicitud específica. */
    $FromDateLocal = date("Y-m-d 23:59:29", strtotime(str_replace(" - ", " ", $params->StartDateLocal)));
    $Region = $params->Region;
    $Currency = $params->Currency;
    $ClientId = $params->ClientId;

    $Delete = $_REQUEST["Delete"];
    $FromId = $_REQUEST["FromId"];
    $PlayerId = $_REQUEST["PlayerId"];
    $TicketId = $_REQUEST["TicketId"];
    $Ip = $_REQUEST["Ip"];
    $CountrySelect = intval($_REQUEST["CountrySelect"]);

    $Region = intval($_REQUEST["CountrySelect"]);


    /* asigna valores de entrada a variables dependiendo de condiciones. */
    $State = ($_REQUEST["State"] != 'A' && $_REQUEST["State"] != 'I') ? '' : $_REQUEST["State"];
    $State = $_REQUEST["State"];
    $TypeBet = $_REQUEST["TypeBet"];
    $TypeAwards = $_REQUEST["TypeAwards"];

    $TypeUser = $_REQUEST["TypeUser"];

    /* obtiene valores de parámetros enviados a través de una solicitud HTTP. */
    $ValueMinimum = $_REQUEST["ValueMinimum"];
    $ValueMaximum = $_REQUEST["ValueMaximum"];

    $IsDetails = $_REQUEST["IsDetails"];
    $WalletId = $_REQUEST["WalletId"];
    $BeneficiaryId = $_REQUEST["BeneficiaryId"];

    $MaxRows = $_REQUEST["count"];
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
    $seguir = true;

    if ($SkeepRows == "") {
        $seguir = false;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $seguir = false;
    }

    if ($seguir) {


        /* Se crea una nueva instancia de la clase ConfigurationEnvironment en la variable. */
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment() && false) {

            /* establece una fecha local a partir de parámetros recibidos y ajusta el tiempo. */
            $FromDateLocal = $params->dateFrom;

            if ($FromId != "") {
                $UsuarioPerfil = new UsuarioPerfil($FromId, "");
            }

            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }


            /* ajusta y formatea una fecha a un formato específico con zona horaria. */
            $ToDateLocal = $params->dateTo;

            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }

            $rules = [];

            /* if ($FromDateLocal == "") {
                 $FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
             }
             if ($ToDateLocal == "") {
                 $ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
             }
            */


            /* Agrega reglas basadas en fechas según el tipo de apuesta especificado. */
            if ($FromDateLocal != "") {
                //array_push($rules, array("field" => "CONCAT(transaccion_sportsbook.fecha_crea,' ',transaccion_sportsbook.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                if ($TypeBet == 2) {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_cierre)", "data" => "$FromDateLocal", "op" => "ge"));
                } else if ($TypeBet == 3) {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_pago)", "data" => "$FromDateLocal", "op" => "ge"));

                } else if ($TypeBet == 4) {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_cierre)", "data" => "$FromDateLocal", "op" => "ge"));

                } else {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                }
            }

            /* añade reglas de filtrado basadas en fechas y tipos de apuestas. */
            if ($ToDateLocal != "") {
                //array_push($rules, array("field" => "CONCAT(transaccion_sportsbook.fecha_crea,' ',transaccion_sportsbook.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));

                if ($TypeBet == 2) {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_cierre)", "data" => "$ToDateLocal", "op" => "le"));
                } else if ($TypeBet == 3) {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_pago)", "data" => "$ToDateLocal", "op" => "le"));

                } else if ($TypeBet == 4) {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_cierre)", "data" => "$FromDateLocal", "op" => "ge"));

                } else {
                    array_push($rules, array("field" => "(transaccion_sportsbook.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));

                }
            }


            /* Condiciona la adición de reglas según el tipo de apuesta. */
            if ($TypeBet == 4) {
                // array_push($rules, array("field" => "transaccion_sportsbook.eliminado", "data" => "N", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "transaccion_sportsbook.eliminado", "data" => "S", "op" => "ne"));

            }
            // array_push($rules, array("field" => "transaccion_sportsbook.usuario_id", "data" => "$ClientId", "op" => "eq"));


            /* Agrega condiciones a un arreglo si se cumple la validación de variables. */
            if ($Region != "" && $Region != "0") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }


            /* establece reglas basadas en el perfil de usuario y el tipo de apuesta. */
            if ($FromId != "") {
                if ($TypeBet == 3) {
                    if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                        array_push($rules, array("field" => "transaccion_sportsbook.usumodif_id", "data" => "$FromId", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "transaccion_sportsbook.usumodif_id", "data" => "$FromId", "op" => "eq"));
                    }
                } else {
                    if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$FromId", "op" => "eq"));
                    }
                }


            }


            /* agrega reglas a un array basado en condiciones de PlayerId e Ip. */
            if ($PlayerId != "") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));

            }

            if ($Ip != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.dir_ip", "data" => "$Ip", "op" => "cn"));

            }


            /* Filtra transacciones según país y estado, agregando reglas a un arreglo. */
            if ($CountrySelect != "" && $CountrySelect != "0") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
            }

            if ($State != "") {

                if ($State == 'PP') {
                    array_push($rules, array("field" => "transaccion_sportsbook.premiado", "data" => "S", "op" => "eq"));
                    array_push($rules, array("field" => "transaccion_sportsbook.premio_pagado", "data" => "N", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "transaccion_sportsbook.bet_status", "data" => "$State", "op" => "eq"));

                }

            }


            /* Agrega reglas de filtrado según TicketId y ValorMáximo en un array. */
            if ($TicketId != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.ticket_id", "data" => "$TicketId", "op" => "eq"));

            }


            if ($ValueMaximum != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.vlr_premio", "data" => "$ValueMaximum", "op" => "le"));

            }


            /* Condiciones que añaden reglas a un arreglo según valores y tipo de usuario. */
            if ($ValueMinimum != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.vlr_premio", "data" => "$ValueMinimum", "op" => "ge"));

            }

            if ($TypeUser == 1) {
                array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "eq"));
            }


            /* Condiciones que añaden reglas basadas en el tipo de usuario y perfil de sesión. */
            if ($TypeUser == 2) {
                array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "ne"));

            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));

            }


            /* asigna reglas basadas en el perfil de usuario en la sesión. */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));

            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));

            }


            /* Código que agrega reglas según el perfil de usuario en sesión. */
            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

            }


            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));

            }


            // Si el usuario esta condicionado por País

            /* Agrega reglas basadas en condiciones de sesión para validación de usuario. */
            if ($_SESSION['PaisCond'] == "S") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
            }
            // Si el usuario esta condicionado por el mandante y no es de Global
            if ($_SESSION['Global'] == "N") {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            } else {
                /* Añade reglas si "mandanteLista" no está vacío ni es "-1". */

                if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                }

            }

            // Inactivamos reportes para el país Colombia
            //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


            /* Filtra transacciones deportivas y las convierte a formato JSON para su procesamiento. */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $TransaccionSportsbook = new TransaccionSportsbook();
            $tickets = $TransaccionSportsbook->getTransaccionesCustom(" usuario.nombre,usuario.login,usuario.moneda,transaccion_sportsbook.bet_status,transaccion_sportsbook.ticket_id,transaccion_sportsbook.usuario_id,transaccion_sportsbook.transsport_id,transaccion_sportsbook.vlr_apuesta,transaccion_sportsbook.vlr_premio,transaccion_sportsbook.estado,transaccion_sportsbook.fecha_crea,transaccion_sportsbook.dir_ip  ", "transaccion_sportsbook.transsport_id", "desc", $SkeepRows, $MaxRows, $json, true, '');

            /* decodifica un JSON de boletos en un array y crea un array vacío. */
            $tickets = json_decode($tickets);

            $final = [];

            foreach ($tickets->data as $key => $value) {

                /* Se crea un array con datos de transacciones deportivas específicas. */
                $array = [];

                $array["Id"] = $value->{"transaccion_sportsbook.transsport_id"};
                $array["Amount"] = $value->{"transaccion_sportsbook.vlr_apuesta"};
                $array["Price"] = $value->{"transaccion_sportsbook.vlr_apuesta"};
                $array["WinningAmount"] = $value->{"transaccion_sportsbook.vlr_premio"};

                /* Extrae y organiza datos sobre una transacción de apuestas en un array asociativo. */
                $array["StateName"] = $value->{"transaccion_sportsbook.estado"};
                $array["CreatedLocal"] = $value->{"transaccion_sportsbook.fecha_crea"} . " " . $value->{"transaccion_sportsbook.hora_crea"};
                $array["ClientLoginIP"] = $value->{"transaccion_sportsbook.dir_ip"};
                $array["Currency"] = $value->{"usuario.moneda"};


                $array["Id"] = $value->{"transaccion_sportsbook.ticket_id"};

                /* Asignación de datos de usuario y nombre basado en condiciones del objeto recibido. */
                $array["UserId"] = $value->{"transaccion_sportsbook.usuario_id"};

                $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true);
                $array["UserName"] = $Helpers->decode_data($value->{"usuario_punto.nombre"});
                $array["UserName"] = '';
                $array["BetShop"] = $Helpers->decode_data($value->{"usuario_punto.nombre"});

                if ($array["UserName"] == "") {
                    $array["UserName"] = $Helpers->decode_data($value->{"usuario.nombre"});
                }

                $array["State"] = $value->{"transaccion_sportsbook.bet_status"};
                $array["Date"] = $value->{"transaccion_sportsbook.fecha_crea"} . " " . $value->{"transaccion_sportsbook.hora_crea"};
                $array["WinningAmount"] = $value->{"transaccion_sportsbook.vlr_premio"};
                $array["Tax"] = $value->{"transaccion_sportsbook.impuesto"};
                $array["WinningAmountTotal"] = floatval($array["WinningAmount"]) - floatval($array["Tax"]);
                $array["Key"] = $value->{"transaccion_sportsbook.clave"};

                /* inicializa un array y lo agrega a una colección final. */
                $array["Odds"] = 0;
                $array["UserIP"] = $value->{"transaccion_sportsbook.dir_ip"};
                $array['TypeBet'] = $value->{'categoria_mandante.descripcion'} ?: '';


                array_push($final, $array);

            }

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            //$response["Data"] = array("Objects" => $final,
            //"Count" => $tickets->count[0]->{".count"});

            $response["pos"] = $SkeepRows;

            /* Asigna total de tickets y datos finales a la respuesta. */
            $response["total_count"] = $tickets->count[0]->{".count"};
            $response["data"] = $final;

        } else {

            /* crea instancias de UsuarioPerfil según parámetros de entrada. */
            $FromDateLocal = $params->dateFrom;

            if ($FromId != "") {
                $UsuarioPerfil = new UsuarioPerfil($FromId, "");
            }
            if ($PlayerId != "") {
                $UsuarioPerfil = new UsuarioPerfil($PlayerId, "");
            }

            /* procesa fechas recibidas y ajusta según la zona horaria especificada. */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
                $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            $ToDateLocal = $params->dateTo;

            /* convierte una fecha recibida en formato de solicitud a formato local. */
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }
            if ($IsDetails == '1') {


                /* Se inicializa un array vacío para contener las reglas. */
                $rules = [];

                /* if ($FromDateLocal == "") {
                     $FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                 }
                 if ($ToDateLocal == "") {
                     $ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                 }
                */


                /* establece reglas de filtrado de fechas según el tipo de usuario o apuestas. */
                if ($FromDateLocal != "") {
                    //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    if ($TypeUser == 3) {
                        //Filtro fecha de creación para tipo de usuario: Beneficiario
                        array_push($rules, ['field' => 'it_ticket_enc.fecha_crea_time', 'data' => $FromDateLocal . ' 00:00:00', 'op' => 'ge']);
                    } elseif ($TypeBet == 2) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));

                    } else if ($TypeBet == 3) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));

                    } else if ($TypeBet == 4) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));

                    } else {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    }
                }

                /* agrega reglas de filtrado de fechas según el tipo de usuario y apuesta. */
                if ($ToDateLocal != "") {
                    //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
                    if ($TypeUser == 3) {
                        //Filtro fecha de creación para tipo de usuario: Beneficiario
                        array_push($rules, ['field' => 'it_ticket_enc.fecha_crea_time', 'data' => $ToDateLocal . ' 23:59:59', 'op' => 'le']);
                    } elseif ($TypeBet == 2) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));
                    } else if ($TypeBet == 3) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));

                    } else if ($TypeBet == 4) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));

                    } else {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));

                    }
                }

                // array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "$ClientId", "op" => "eq"));


                /* Condiciones que añaden reglas de filtro basadas en región y moneda. */
                if ($Region != "" && $Region != "0") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
                }

                if ($Currency != "") {
                    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
                }


                /* Condiciona la creación de reglas basadas en el tipo de apuesta y perfil de usuario. */
                if ($FromId != "") {
                    if ($TypeBet == 3) {
                        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                            array_push($rules, array("field" => "usuario_punto_pago.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                        } else {
                            array_push($rules, array("field" => "usuario_deporte_resumen.usuario_id", "data" => "$FromId", "op" => "eq"));
                        }
                    } else {
                        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                        } else {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$FromId", "op" => "eq"));
                        }
                    }


                }


                /* verifica el tipo de apuesta y el perfil del usuario para agregar reglas. */
                if ($PlayerId != "") {
                    if ($TypeBet == 3) {
                        if ($UsuarioPerfil->perfilId == "USUONLINE") {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));

                        } elseif ($UsuarioPerfil->perfilId == "MAQUINAANONIMA") {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                        } else {
                            array_push($rules, array("field" => "it_ticket_enc.usumodifica_id", "data" => "$PlayerId", "op" => "eq"));
                        }


                    } else {
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));

                    }


                }

                /* Agrega reglas de filtrado basadas en país y beneficiario si están presentes. */
                if ($CountrySelect != "" && $CountrySelect != "0") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                }

                if ($BeneficiaryId != '') {
                    array_push($rules, array("field" => "it_ticket_enc.beneficiario_id", "data" => $BeneficiaryId, "op" => "eq"));
                }


                /* asigna un tipo de usuario y establece reglas basadas en el tipo. */
                $TipoUsuario = '';

                if ($TypeUser == 1) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "eq"));
                    $TipoUsuario = 'U';
                }

                /* Condicionales que aplican reglas según el tipo de usuario en una aplicación. */
                if ($TypeUser == 2) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "ne"));
                    $TipoUsuario = 'P';

                }

                if ($TypeUser == 3) {
                    //Sólo apuestas vinculadas a beneficiarios
                    array_push($rules, array("field" => "it_ticket_enc.beneficiario_id", "data" => 0, "op" => "ne"));
                    array_push($rules, array("field" => "it_ticket_enc.beneficiario_id", "data" => 0, "op" => "nn"));
                    $tipoUsuario = 'A';

                    //No se cuentan apuestas rechazadas (J), rechazada MTS (M) y rechazada by portal (D)
                    array_push($rules, ['field' => 'it_ticket_enc.bet_status', 'data' => '"J", "M", "D"', 'op' => 'ni']);

                    //Condiciones extra para reducir el tiempo de la consulta
                }

                /* evalúa el perfil del usuario y agrega reglas de acceso. */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';

                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';

                }

                /* Verifica el perfil de usuario y agrega reglas según sea el caso. */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';

                }


                if ($_SESSION["win_perfil2"] == "CAJERO") {
                    array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';

                }


                /* verifica el perfil de usuario y asigna reglas basadas en ello. */
                if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';

                }

                if ($_SESSION["win_perfil2"] == "AFILIADOR") {
                    array_push($rules, array("field" => "it_ticket_enc.beneficiario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $tipoUsuario = 'A';
                }


                // Si el usuario esta condicionado por País

                /* verifica condiciones de sesión y añade reglas a un array. */
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }
                // Si el usuario esta condicionado por el mandante y no es de Global
                if ($_SESSION['Global'] == "N") {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {


                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }

                // Inactivamos reportes para el país Colombia
                //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

                /*
                 * 1 A $sqlApuestasDeportivasPuntoVentaDia APUESTA FECHA CREA
                 * 2 A $sqlApuestasDeportivasPuntoVentaoDiaCierre APUESTA FECHA CIERRE
                 * 2 P $sqlPremiosDeportivasPuntoVentaoDia PREMIO
                 * 3 P $sqlPremiosPagadosDeportivasPuntoVentaoDia PREMIO - IMPUESTO
                 */

                /* Consulta SQL que selecciona datos de usuarios y sus apuestas con condiciones específicas. */
                $select = "usuario_punto_pago.nombre,usuario.mandante,pais.*,
            usuario_punto.nombre usuario_puntonombre,
            usuario_punto.usuario_id      usuario_puntousuario_id,
            usuario.nombre usuarionombre,
            usuario.login,
            usuario.moneda usuariomoneda,
            usuario_deporte_resumen.usuario_id,
            SUM(
            CASE 
            WHEN usuario_deporte_resumen.tipo IN ('1','2') AND usuario_deporte_resumen.estado ='A'
            
            THEN usuario_deporte_resumen.valor 
                        WHEN usuario_deporte_resumen.estado ='P'  THEN 0   

            WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','STAKEDECREASE','NEWDEBIT','1','2') 
            THEN 0 
            WHEN usuario_deporte_resumen.tipo IN ('REFUND') 
            THEN -usuario_deporte_resumen.valor 
            ELSE 
            usuario_deporte_resumen.valor 
            END
            )  vlr_apuesta, 
            SUM(
            CASE 
            WHEN usuario_deporte_resumen.tipo IN ('2','3') AND usuario_deporte_resumen.estado ='P' 
            THEN usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','STAKEDECREASE')   
            THEN usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('NEWDEBIT')   
            THEN -usuario_deporte_resumen.valor 
            ELSE 0 
            END) vlr_premio,
            usuario_deporte_resumen.fecha_crea ";


                /* Consulta SQL que agrega y agrupa datos financieros de usuarios según su tipo. */
                $select2 = "a.*,
         SUM(a.vlr_apuesta) vlr_apuestatotal,
         SUM(a.vlr_premio)  vlr_premiototal";

                $grouping2 = "a.usuario_id,a.fecha_crea";


                if ($TipoUsuario == 'U') {

                    $select = "usuario_punto_pago.nombre,usuario.mandante,pais.*,
            usuario_punto.nombre usuario_puntonombre,
            usuario.nombre usuarionombre,
            usuario.login,
            usuario.moneda usuariomoneda,
            usuario_deporte_resumen.usuario_id,
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('TAXBET') THEN 0 ELSE usuario_deporte_resumen.valor END)  impuesto_apuesta, 
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('TAXWIN') THEN 0 ELSE usuario_deporte_resumen.valor END)  impuesto_premio,
            SUM(
            CASE 
            WHEN usuario_deporte_resumen.tipo IN ('1','2') AND usuario_deporte_resumen.estado ='A'
            
            THEN usuario_deporte_resumen.valor 
                        WHEN usuario_deporte_resumen.estado ='P'  THEN 0   

            WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','STAKEDECREASE','1','2','NEWDEBIT') 
            THEN 0 
            WHEN usuario_deporte_resumen.tipo IN ('REFUND') 
            THEN -usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('CLOSEDBET') 
            THEN usuario_deporte_resumen.valor 
            ELSE 
            0 
            END
            )  vlr_apuesta, 
            SUM(
            CASE 
            WHEN usuario_deporte_resumen.tipo IN ('2','3') AND usuario_deporte_resumen.estado ='P' 
            THEN usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','STAKEDECREASE')   
            THEN usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('NEWDEBIT')   
            THEN -usuario_deporte_resumen.valor 
            ELSE 0 
            END) vlr_premio,
            usuario_deporte_resumen.fecha_crea ";

                } elseif ($TipoUsuario == 'P') {



                    /* Consulta SQL que selecciona datos de usuarios y sus apuestas o premios. */
                    $select = "usuario_punto_pago.nombre usuario_punto_pagonombre,usuario.mandante,pais.*,
            usuario_punto.nombre usuario_puntonombre,
            usuario_punto.usuario_id      usuario_puntousuario_id,
            usuario.nombre usuarionombre,
            usuario.login,
            usuario.moneda usuariomoneda,
            usuario_deporte_resumen.usuario_id,
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('TAXBET') THEN 0 ELSE usuario_deporte_resumen.valor END)  impuesto_apuesta, 
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('TAXWIN') THEN 0 ELSE usuario_deporte_resumen.valor END)  impuesto_premio,
            SUM(
            CASE 
            WHEN usuario_deporte_resumen.tipo IN ('1','2') AND usuario_deporte_resumen.estado ='A'
            
            THEN usuario_deporte_resumen.valor 
                        WHEN usuario_deporte_resumen.estado ='P'  THEN 0   

            WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','STAKEDECREASE','1','2','NEWDEBIT') 
            THEN 0 
            WHEN usuario_deporte_resumen.tipo IN ('REFUND') 
            THEN -usuario_deporte_resumen.valor 
            ELSE 
            usuario_deporte_resumen.valor 
            END
            )  vlr_apuesta, 
            SUM(
            CASE 
            WHEN usuario_deporte_resumen.tipo IN ('2','3') AND usuario_deporte_resumen.estado ='P' 
            THEN usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','STAKEDECREASE')   
            THEN usuario_deporte_resumen.valor 
            WHEN usuario_deporte_resumen.tipo IN ('NEWDEBIT')   
            THEN -usuario_deporte_resumen.valor 
            ELSE 0 
            END) vlr_premio,
            usuario_deporte_resumen.fecha_crea ";


                    /* Código que agrupa datos y aplica reglas basadas en condiciones de tipo de premio. */
                    $grouping2 = "a.usuario_puntousuario_id,a.fecha_crea";


                    if ($TypeAwards == 2) {
                        array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => '1,3', "op" => "in"));

                    } else {
                        /* Agrega una regla al array si se cumple cierta condición en el código. */

                        array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => '2', "op" => "in"));
                    }


                } elseif ($tipoUsuario == 'A') {
                    /* Calcula sumas de apuestas y premios para un tipo de usuario específico. */
                    $select = "sum(vlr_apuesta) as vlr_apuestatotal,
                    sum(vlr_premio) as vlr_premiototal,
                    afiliador.nombre,
                    afiliador.usuario_id,
                    usuario.moneda,
                    it_ticket_enc.fecha_cierre";

                    $grouping = "afiliador.usuario_id, usuario.moneda, it_ticket_enc.fecha_cierre";
                } else {
                    /* Condicional que agrega reglas basadas en el tipo de premios en un arreglo. */
                    if ($TypeAwards == 2) {
                        array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => "'WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE', 'BET',1,3,'NEWCREDIT','TAXBET','TAXWIN','NEWDEBIT'", "op" => "in"));

                    } else {
                        if (date("Y-m-d H:i:s",strtotime($ToDateLocal)) < '2025-07-01 00:00:00') {

                            array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => "'WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE', 'BET',2,'NEWCREDIT','TAXBET','TAXWIN','NEWDEBIT'", "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => "'WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE', 'CLOSEDBET',2,'NEWCREDIT','TAXBET','TAXWIN','NEWDEBIT'", "op" => "in"));

                        }
                    }
                }


                /* Código PHP que obtiene tickets personalizados basado en reglas y usuario. */
                $MaxRows = '1000000000000';


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                if ($tipoUsuario == 'A') {
                    $ItTicketEnc = new ItTicketEnc();
                    $tickets = $ItTicketEnc->getTicketsCustom($select, 'afiliador.usuario_id DESC, it_ticket_enc.fecha_cierre', 'DESC', $SkeepRows, $MaxRows, $json, true, $grouping);
                } else {
                    /* Crea un resumen de usuario deportivo y obtiene datos personalizados con parámetros específicos. */
                    $UsuarioDeporteResumen = new UsuarioDeporteResumen();
                    $tickets = $UsuarioDeporteResumen->getUsuarioDeporteResumenCustom($select, "usuario_deporte_resumen.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true, "usuario_deporte_resumen.usuario_id,usuario_deporte_resumen.estado,usuario_deporte_resumen.fecha_crea", $select2, $grouping2);
                }


                /* decodifica un JSON de tickets y prepara variables para procesar datos. */
                $tickets = json_decode($tickets);

                $final = [];
                $lastIterationUser = null;
                $paisAfiliador = null;
                $paisIsoAfiliador = null;
                foreach ($tickets->data as $key => $value) {


                    /* gestiona información de afiliadores reiniciando o actualizando variables según condiciones. */
                    if (!empty($lastIterationUser)) {
                        if (isset($value->{"afiliador.usuario_id"})) {
                            /*CONTEXTO: Consulta resumida agrupada por ID de afiliadores*/
                            //Se reinicia $lastIterationUser para consultar parámetros de un afiliador diferente
                            if ($value->{"afiliador.usuario_id"} != $lastIterationUser) $lastIterationUser = null;
                            else {
                                //Si es el mismo afiliador se setea la información tomanda del registro anterior
                                $value->{"a.pais_nom"} = $paisAfiliador;
                                $value->{"a.iso"} = $paisIsoAfiliador;
                            }
                        }
                    }


                    /* Código que asigna datos de usuario y país en una consulta de afiliadores. */
                    if (!$lastIterationUser) {
                        if (!$lastIterationUser && isset($value->{"afiliador.usuario_id"})) {
                            /*CONTEXTO: Consulta resumida agrupada por ID de afiliadores*/
                            //Consultamos los parámetros que queremos asignar para este contexto (Nombre pais e ISO cuando se repita el afiliador)
                            $Usuario = new Usuario($value->{"afiliador.usuario_id"});
                            $Pais = new Pais($Usuario->paisId);

                            //Se asignan los parámetros consultados
                            $paisAfiliador = $Pais->paisNom;
                            $paisIsoAfiliador = $Pais->iso;
                            $value->{"a.pais_nom"} = $Pais->paisNom;
                            $value->{"a.iso"} = $Pais->iso;

                            //Almacenamos para verificar en futuras iteraciones
                            $lastIterationUser = $value->{"afiliador.usuario_id"};
                        }
                    }


                    /* Crea un arreglo con información de apuestas y premios desde un objeto dado. */
                    $array = [];

                    $array["Id"] = 0;
                    $array["Amount"] = $value->{".vlr_apuestatotal"};
                    $array["Price"] = $value->{".vlr_apuestatotal"};
                    $array["WinningAmount"] = $value->{".vlr_premiototal"};


                    /* Asigna valores de un objeto a un arreglo, gestionando la moneda si está vacía. */
                    $array["StateName"] = $value->{"it_ticket_enc.estado"};
                    $array["CreatedLocal"] = $value->{"a.fecha_crea"};
                    $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};

                    if ($array["Currency"] == '') {
                        if ($array["Currency"] == '') $array["Currency"] = $value->{"a.usuariomoneda"};
                        if ($array["Currency"] == '') $array["Currency"] = $value->{"usuario.moneda"};
                    }


                    /* Asigna moneda y ticket ID a un array si CurrencyId está vacío. */
                    if ($array["CurrencyId"] == '') {
                        if ($array["CurrencyId"] == '') $array["Currency"] = $value->{"a.usuariomoneda"};
                        if ($array["CurrencyId"] == '') $array["Currency"] = $value->{"usuario.moneda"};
                    }

                    $array["Id"] = $value->{"it_ticket_enc.ticket_id"};

                    /* Inicializa un arreglo con datos de usuario y maneja nombres vacíos adecuadamente. */
                    $array["UserId"] = $value->{"a.usuario_puntousuario_id"};

                    $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true);
                    $array["UserName"] = $Helpers->decode_data($value->{"a.usuario_puntonombre"});

                    $array["BetShop"] = $Helpers->decode_data($value->{"a.usuario_puntonombre"});


                    if ($array["UserName"] == "") {
                        if ($array["UserName"] == "") $array["UserName"] = $Helpers->decode_data($value->{"a.usuarionombre"});
                        if ($array["UserName"] == "") $array["UserName"] = 'Beneficiario: ' . $Helpers->decode_data($value->{"afiliador.nombre"});
                    }


                    /* Asignación condicional de "BeneficiaryId" y "State" desde un objeto $value. */
                    if ($array["BeneficiaryId"] == "") {
                        if (isset($value->{"afiliador.usuario_id"})) $array["BeneficiaryId"] = $value->{"afiliador.usuario_id"};
                    }


                    $array["State"] = $value->{"it_ticket_enc.bet_status"};


                    /* verifica y asigna fechas y monto de premio si están vacíos. */
                    if ($array["Date"] == "") {
                        if ($array["Date"] == "") $array["Date"] = $value->{"a.fecha_crea"};
                        if ($array["Date"] == "") $array["Date"] = $value->{"it_ticket_enc.fecha_cierre"};
                    }

                    $array["WinningAmount"] = $value->{".vlr_premiototal"};

                    /* asigna valores a un array, calculando impuestos y ganancias. */
                    $array["TaxBet"] = $value->{".impuesto_apuesta"};
                    $array["Tax"] = $value->{".impuesto_premio"};
                    $array["WinningAmountTotal"] = floatval($array["WinningAmount"]) - floatval($array["Tax"]);
                    $array["Key"] = $value->{".clave"};

                    $array["Odds"] = 0;
                    $array["UserIP"] = 0;
                    $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true);
                    $array["BetShopPayment"] = $Helpers->decode_data($value->{"a.usuario_punto_pagonombre"});

                    $array["Partner"] = $value->{"a.mandante"};

                    $array["Country"] = $value->{"a.pais_nom"};

                    /* Se almacena el icono del país en minúsculas y se añade a un array. */
                    $array["CountryIcon"] = strtolower($value->{"a.iso"});

                    array_push($final, $array);

                }


                /* asigna valores a un array de respuesta con datos y conteos. */
                $response["pos"] = $SkeepRows;
                $response["total_count"] = oldCount($final);
                $response["data"] = $final;


            } else {


                $isInnerConce = false;


                $rules = [];

                /* if ($FromDateLocal == "") {
                     $FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
                 }
                 if ($ToDateLocal == "") {
                     $ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
                 }
                */


                $daydimensionFecha = 0;


                /* Inicializa la variable $typeDimension con el valor de cero. */
                $typeDimension = 0;
                if (in_array($_SESSION['mandante'], array(3, 4, 5, 6, 7, 10, 22))) {
                    $timezone = '+ 6 ';
                    $typeDimension = 4;

                    if ((date("Y-m-d", strtotime(str_replace(" - ", " ", $FromDateLocal))) == date("Y-m-d", strtotime('-6 hour '))) && (date("Y-m-d", strtotime(str_replace(" - ", " ", $ToDateLocal))) == date("Y-m-d", strtotime('-6 hour ')))) {

                        /* Se asigna el valor 5 a la variable $typeDimension. */
                        $typeDimension = 5;


                        //  $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                        //  $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));


                        /* Se verifica una fecha y se añaden reglas según el tipo de apuesta. */
                        if ($FromDateLocal != "") {
                            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                            if ($TypeBet == 2) {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 2;
                            } else if ($TypeBet == 3) {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 1;

                            } else if ($TypeBet == 4) {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 2;

                            } else {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                            }
                        }

                        /* agrega reglas basadas en diferentes tipos de apuestas y fechas. */
                        if ($ToDateLocal != "") {
                            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));

                            if ($TypeBet == 2) {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$ToDateLocal", "op" => "le"));
                                $daydimensionFecha = 2;
                            } else if ($TypeBet == 3) {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$ToDateLocal", "op" => "le"));
                                $daydimensionFecha = 1;

                            } else if ($TypeBet == 4) {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 2;

                            } else {
                                array_push($rules, array("field" => "(time_dimension5.dbdate1)", "data" => "$ToDateLocal", "op" => "le"));

                            }
                        }

                    } else {


                        //  $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $FromDateLocal) . $timezone . ' hour '));
                        //  $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $ToDateLocal) . $timezone . ' hour '));


                        /* Condicional que agrega reglas basadas en la fecha y tipo de apuesta. */
                        if ($FromDateLocal != "") {
                            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                            if ($TypeBet == 2) {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 2;
                            } else if ($TypeBet == 3) {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 1;

                            } else if ($TypeBet == 4) {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 2;

                            } else {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                            }
                        }

                        /* Condiciones para agregar reglas basadas en fechas y tipo de apuesta. */
                        if ($ToDateLocal != "") {
                            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));

                            if ($TypeBet == 2) {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$ToDateLocal", "op" => "le"));
                                $daydimensionFecha = 2;
                            } else if ($TypeBet == 3) {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$ToDateLocal", "op" => "le"));
                                $daydimensionFecha = 1;

                            } else if ($TypeBet == 4) {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$FromDateLocal", "op" => "ge"));
                                $daydimensionFecha = 2;

                            } else {
                                array_push($rules, array("field" => "(time_dimension4.dbdate1)", "data" => "$ToDateLocal", "op" => "le"));

                            }
                        }
                    }
                } else {


                    /* verifica fechas y añade reglas basadas en el tipo de apuesta. */
                    if ($FromDateLocal != "") {
                        $FromDateLocal = $FromDateLocal . ' 00:00:00';
                        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea_time,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                        if ($TypeBet == 2) {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
                            $daydimensionFecha = 2;
                        } else if ($TypeBet == 3) {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_pago_time)", "data" => "$FromDateLocal", "op" => "ge"));
                            $daydimensionFecha = 1;

                        } else if ($TypeBet == 4) {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
                            $daydimensionFecha = 2;

                        } else {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_crea_time)", "data" => "$FromDateLocal", "op" => "ge"));
                        }
                    }

                    /* Condicional que ajusta reglas según tipo de apuesta y fecha especificada. */
                    if ($ToDateLocal != "") {
                        $ToDateLocal = $ToDateLocal . ' 23:59:59';
                        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));

                        if ($TypeBet == 2) {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$ToDateLocal", "op" => "le"));
                            $daydimensionFecha = 2;
                        } else if ($TypeBet == 3) {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_pago_time)", "data" => "$ToDateLocal", "op" => "le"));
                            $daydimensionFecha = 1;

                        } else if ($TypeBet == 4) {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
                            $daydimensionFecha = 2;

                        } else {
                            array_push($rules, array("field" => "(it_ticket_enc.fecha_crea_time)", "data" => "$ToDateLocal", "op" => "le"));

                        }
                    }
                }


                /* agrega condiciones a un arreglo según el tipo de apuesta y eliminación. */
                if ($TypeBet == 4) {
                    array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "J", "op" => "eq"));

                } else {

                    if ($Delete == 'S') {

                    } else {
                        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "S", "op" => "ne"));
                    }

                }
                // array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "$ClientId", "op" => "eq"));


                /* Agrega reglas basadas en condiciones de región y moneda para usuarios. */
                if ($Region != "" && $Region != "0") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
                }

                if ($Currency != "") {
                    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
                }


                /* Condiciona la adición de reglas basadas en perfil de usuario y tipo de apuesta. */
                if ($FromId != "") {
                    if ($TypeBet == 3) {
                        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                            array_push($rules, array("field" => "usuario_punto_pago.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                        } else {
                            array_push($rules, array("field" => "it_ticket_enc.usumodifica_id", "data" => "$FromId", "op" => "eq"));
                        }
                    } else {
                        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
                            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "$FromId", "op" => "eq"));

                        } else {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$FromId", "op" => "eq"));
                        }
                    }


                }


                /* agrega reglas basadas en el perfil de usuario y tipo de apuesta. */
                if ($PlayerId != "") {
                    if ($TypeBet == 3) {
                        if ($UsuarioPerfil->perfilId == "USUONLINE") {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));

                        } elseif ($UsuarioPerfil->perfilId == "MAQUINAANONIMA") {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                        } else {

                            array_push($rules, array("field" => "it_ticket_enc.usumodifica_id", "data" => "$PlayerId", "op" => "eq"));
                        }


                    } else {
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));

                    }


                }


                /* Verifica condiciones para establecer una variable según el perfil de usuario. */
                if ($FromId != "" || $PlayerId != "") {

                    if ($UsuarioPerfil->perfilId != "USUONLINE") {
                        $isInnerConce = true;
                    }
                }


                /* agrega reglas de filtrado basadas en condiciones de IP y estado. */
                if ($Ip != "") {
                    array_push($rules, array("field" => "it_ticket_enc.dir_ip", "data" => "$Ip", "op" => "cn"));

                }

                if ($State != "") {
                    if ($State == 'PP') {
                        array_push($rules, array("field" => "it_ticket_enc.premiado", "data" => "S", "op" => "eq"));
                        array_push($rules, array("field" => "it_ticket_enc.premio_pagado", "data" => "N", "op" => "eq"));

                    } else {
                        array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "$State", "op" => "eq"));
                    }
                }


                /* Se agregan reglas de filtrado para TicketId y BeneficiaryId en un arreglo. */
                if ($TicketId != "") {
                    array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => "$TicketId", "op" => "eq"));

                }

                if ($BeneficiaryId != '') {
                    array_push($rules, ['field' => 'it_ticket_enc.beneficiario_id', 'data' => $BeneficiaryId, 'op' => 'eq']);
                }


                /* Agrega reglas de validación para valores máximos y mínimos en un arreglo. */
                if ($ValueMaximum != "") {
                    array_push($rules, array("field" => "it_ticket_enc.vlr_premio", "data" => "$ValueMaximum", "op" => "le"));

                }

                if ($ValueMinimum != "") {
                    array_push($rules, array("field" => "it_ticket_enc.vlr_premio", "data" => "$ValueMinimum", "op" => "ge"));

                }


                /* Agrega reglas basadas en parámetros válidos de WalletId y tipo de usuario. */
                if ($WalletId != "" && is_numeric($WalletId)) {
                    array_push($rules, array("field" => "it_ticket_enc.wallet", "data" => "$WalletId", "op" => "ge"));

                }

                if ($TypeUser == 1) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "eq"));
                }


                /* agrega reglas basadas en el tipo de usuario al arreglo $rules. */
                if ($TypeUser == 2) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "ne"));

                }

                if ($TypeUser == 3) {
                    array_push($rules, array('field' => 'it_ticket_enc.beneficiario_id', 'data' => '0', 'op' => 'ne'));
                    array_push($rules, array('field' => 'it_ticket_enc.beneficiario_id', 'data' => '0', 'op' => 'nn'));
                }


                /* Código que agrega reglas basadas en el perfil de usuario de sesión. */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                    $isInnerConce = true;
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));

                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                    $isInnerConce = true;
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));

                }


                /* asigna reglas basadas en el perfil de usuario de sesión. */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                    $isInnerConce = true;
                    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));

                }


                if ($_SESSION["win_perfil2"] == "CAJERO") {
                    $isInnerConce = true;
                    array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));

                }


                /* Condiciona reglas basadas en perfil de usuario y país en una sesión. */
                if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                    $isInnerConce = true;
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));

                }


                // Si el usuario esta condicionado por País
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }
                // Si el usuario esta condicionado por el mandante y no es de Global

                /* gestiona reglas según el estado de sesión de 'mandante'. */
                if ($_SESSION['Global'] == "N") {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {

                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }

                }


                /* verifica condiciones de sesión y agrega reglas según selección de país. */
                if ($_SESSION['mandante'] == "2" && $_SESSION['Global'] == "N") {

                } else {

                    if ($CountrySelect != "" && $CountrySelect != "0") {
                        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                    }

                    // Inactivamos reportes para el país Colombia
                    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

                }


                /* verifica si el usuario es 449 y define una variable de orden. */
                if ($_SESSION['usuario'] == 449) {

                }


                $order = "it_ticket_enc.fecha_crea_time";

                /* asigna un criterio de ordenamiento según el tipo de apuesta. */
                $orderType = "desc";

                if ($TypeBet == 2) {
                    $order = "it_ticket_enc.fecha_cierre_time";
                } else if ($TypeBet == 3) {
                    $order = "it_ticket_enc.fecha_pago_time";

                } else if ($TypeBet == 4) {
                    /* Condicional que asigna un valor a $order si $TypeBet es igual a 4. */

                    $order = "it_ticket_enc.fecha_cierre_time";

                }

                /* procesa datos de entrada, los convierte a JSON y elimina barras invertidas. */
                $data = $_REQUEST;
                $data = json_encode($data);

                $data = preg_replace('/\\\\/', '', $data);

                $data = json_decode($data);

                if ($data->sort != "") {


                    /* Condiciona reglas y ordena resultados según el ID de usuario. */
                    if ($data->sort->UserId != "") {
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => "0", "op" => "ge"));
                        $order = "usuario.usuario_id";
                        $orderType = ($data->sort->UserId == "asc") ? "asc" : "desc";

                    }

                    /* Verifica y ordena datos según la cantidad especificada en la variable `$data`. */
                    if ($data->sort->Amount != "") {
                        array_push($rules, array("field" => "it_ticket_enc.vlr_apuesta", "data" => "0", "op" => "ge"));
                        $order = "it_ticket_enc.vlr_apuesta";
                        $orderType = ($data->sort->Amount == "asc") ? "asc" : "desc";

                    }

                    /* configura criterios de ordenación basados en fechas y valores específicos. */
                    if ($data->sort->Date != "") {
                        //$order = "it_ticket_enc.fecha_crea_time";
                        $orderType = ($data->sort->Date == "asc") ? "asc" : "desc";

                    }
                    if ($data->sort->GGR != "") {
                        $order = "it_ticket_enc.vlr_apuesta - it_ticket_enc.vlr_premio";
                        $orderType = ($data->sort->GGR == "asc") ? "asc" : "desc";

                    }
                }


                /* Se crea un filtro en formato JSON para un objeto ItTicketEnc. */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $ItTicketEnc = new ItTicketEnc();
                $tickets = $ItTicketEnc->getTicketsCustom(
                    "it_ticket_enc.wallet,
                    aes_decrypt(it_ticket_enc.clave, '" . base64_decode($_ENV['APP_PASSKEY']) . "') clave,
                    usuario_punto_pago.nombre,
                    usuario_punto.login usuario_puntologin,
                    usuario.nombre,
                    usuario.login,
                    usuario.moneda,
                    it_ticket_enc.bet_status,
                    it_ticket_enc.impuesto,
                    it_ticket_enc.ticket_id,
                    it_ticket_enc.usuario_id,
                    it_ticket_enc.it_ticket_id,
                    it_ticket_enc.vlr_apuesta,
                    it_ticket_enc.vlr_premio,
                    it_ticket_enc.estado,
                    it_ticket_enc.fecha_crea,
                    it_ticket_enc.hora_crea,
                    it_ticket_enc.fecha_pago,
                    it_ticket_enc.hora_pago,
                    it_ticket_enc.fecha_cierre,
                    it_ticket_enc.hora_cierre,
                    it_ticket_enc.dir_ip,
                    it_ticket_enc.beneficiario_id,
                    it_ticket_enc.impuesto_apuesta,
                    CASE
                        WHEN it_ticket_enc.bet_mode = 'PreLive' THEN 'Prematch'
                        WHEN it_ticket_enc.bet_mode = 'Live' THEN 'Live'
                        WHEN it_ticket_enc.bet_mode = 'Mixed' THEN 'Mixed'
                        WHEN it_ticket_enc.bet_mode = 'VSPORT' THEN 'Virtuales'
                        ELSE ''
                    END AS bet_mode,
                    itei1.valor AS usuario_id_cliente",
                    $order,
                    $orderType,
                    $SkeepRows,
                    $MaxRows,
                    $json,
                    true,
                    "",
                    "",
                    true,
                    $daydimensionFecha,
                    false,
                    $typeDimension,
                    $isInnerConce
                );


                /* decodifica JSON y detiene la ejecución si se cumple una condición. */
                $tickets = json_decode($tickets);


                if ($_REQUEST['test2'] == 1) {
                    exit();
                }

                /* Se inicializa un arreglo vacío llamado "final" para almacenar datos. */
                $final = [];

                foreach ($tickets->data as $key => $value) {


                    /* asigna un valor a "Wallet" según una condición específica. */
                    $array = [];


                    $array["Wallet"] = $value->{"it_ticket_enc.wallet"};

                    if ($array["Wallet"] == '1') {
                        $array["Wallet"] = "Quisk";

                    } else {
                        $array["Wallet"] = "Wallet";
                    }

                    $array["Id"] = $value->{"it_ticket_enc.it_ticket_id"};
                    $array["Amount"] = $value->{"it_ticket_enc.vlr_apuesta"};
                    $array["Price"] = $value->{"it_ticket_enc.vlr_apuesta"};
                    $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
                    $array["StateName"] = $value->{"it_ticket_enc.estado"};
                    $array["CreatedLocal"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};

                    $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
                    $array["Currency"] = $value->{"usuario.moneda"};
                    $array["DatePayment"] = $value->{"it_ticket_enc.fecha_pago"} . " " . $value->{"it_ticket_enc.hora_pago"};

                    $array["ClosingDate"] = $value->{"it_ticket_enc.fecha_cierre"} . " " . $value->{"it_ticket_enc.hora_cierre"};


                    $array["Id"] = $value->{"it_ticket_enc.ticket_id"};

                    /* asigna valores de un objeto a un array asociativo en PHP. */
                    $array["UserId"] = $value->{"it_ticket_enc.usuario_id"};
                    $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true);
                    $array["UserName"] = $Helpers->decode_data($value->{"usuario_puntonombre"});
                    $array["UserName"] = '';
                    $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true);
                    $array["BetShop"] = $Helpers->decode_data($value->{"usuario_punto.usuario_puntologin"});
                    $array["TypeBe"] = $value->{".bet_mode"};
                    $array["ClientId"] = $value->{"itei1.usuario_id_cliente"} ?: $array["UserId"];


                    /* Asigna nombre de usuario y estado si están vacíos en el arreglo. */
                    if ($array["UserName"] == "") {
                        $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true);
                        $array["UserName"] = $Helpers->decode_data($value->{"usuario.nombre"});
                    }


                    $array["State"] = $value->{"it_ticket_enc.bet_status"};

                    /* Almacena datos de un ticket, calculando ganancias netas y condiciones de visualización. */
                    $array["Date"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};
                    $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
                    $array["Tax"] = $value->{"it_ticket_enc.impuesto"};
                    $array["TaxBet"] = $value->{"it_ticket_enc.impuesto_apuesta"};
                    $array["WinningAmountTotal"] = floatval($array["WinningAmount"]) - floatval($array["Tax"]);
                    if ($_SESSION["win_perfil2"] != "CAJERO" && $_SESSION["win_perfil2"] != "PUNTOVENTA") {
                        $array["Odds"] = "";
                    } else {
                        /* Asignación de un valor vacío al elemento "Odds" del array si se cumple la condición. */


                        $array["Odds"] = "";

                    }

                    /* asigna valores de un objeto a un array asociativo en PHP. */
                    $array["UserIP"] = $value->{"it_ticket_enc.dir_ip"};
                    $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true);
                    $array["BetShopPayment"] = $Helpers->decode_data($value->{"usuario_punto_pago.nombre"});
                    $array['TypeBet'] = $value->{'categoria_mandante.descripcion'} ?: '';
                    $array['BeneficiaryId'] = $value->{'it_ticket_enc.beneficiario_id'};

                    $array["Key"] = $value->{".clave"};

                    array_push($final, $array);

                }

                /* asigna valores a un array de respuesta con datos de tickets. */
                $response["pos"] = $SkeepRows;
                $response["total_count"] = $tickets->count[0]->{".count"};
                $response["data"] = $final;

            }


            /* Código que inicializa un arreglo de respuesta sin errores y con mensaje de éxito. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            //$response["Data"] = array("Objects" => $final,
            //"Count" => $tickets->count[0]->{".count"});

        }
    } else {
        /* define la estructura de respuesta para una operación sin errores. */


        $response["HasError"] = false;
        $response["AlertType"] = "success2";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        //$response["Data"] = array("Objects" => $final,
        //"Count" => $tickets->count[0]->{".count"});

        $response["pos"] = 0;
        $response["total_count"] = 0;
        $response["data"] = array();

    }
} catch (Exception $e) {
}
