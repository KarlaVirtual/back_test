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
 * Report/GetBetHistory
 * 
 * Obtiene el historial de apuestas realizadas según los filtros especificados
 *
 * @param string $EndDateLocal Fecha final en formato Y-m-d H:i:s
 * @param string $StartDateLocal Fecha inicial en formato Y-m-d H:i:s  
 * @param string $Region Región a consultar
 * @param string $Currency Moneda
 * @param string $ClientId ID del cliente
 * @param string $FromId ID inicial
 * @param string $PlayerId ID del jugador
 * @param string $TicketId ID del ticket
 * @param string $Ip Dirección IP
 * @param int $CountrySelect ID del país
 * @param string $State Estado (A: Activo, I: Inactivo)
 * @param string $TypeBet Tipo de apuesta
 * @param string $TypeAwards Tipo de premio
 * @param string $TypeUser Tipo de usuario
 * @param float $ValueMinimum Valor mínimo
 * @param float $ValueMaximum Valor máximo
 * @param bool $IsDetails Incluir detalles
 * @param int $count Número máximo de registros
 * @param int $OrderedItem Campo de ordenamiento
 * @param int $start Registro inicial
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string, 
 *   "url": string,
 *   "success": boolean,
 *   "data": array {
 *     "tickets": array[] Lista de tickets encontrados,
 *     "total": int Total de registros,
 *     "filtered": int Total de registros filtrados
 *   }
 * }
 *
 * @throws Exception Si ocurre un error en la consulta
 *
 * @access public
 */
try {
    // Inicializa el objeto ItTicketEnc para manejar tickets
    $ItTicketEnc = new ItTicketEnc();

    // Obtiene y decodifica los parámetros JSON del request
    $params = file_get_contents('php://input');
    $params = json_decode($params);

    // Procesa las fechas de inicio y fin del rango a consultar
    $ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->EndDateLocal) . ' +1 day'));
    $FromDateLocal = date("Y-m-d 23:59:29", strtotime(str_replace(" - ", " ", $params->StartDateLocal)));
    $Region = $params->Region;
    $Currency = $params->Currency;
    $ClientId = $params->ClientId;

    // Obtiene los parámetros de filtrado desde el request
    $FromId = $_REQUEST["FromId"];
    $PlayerId = $_REQUEST["PlayerId"];
    $TicketId = $_REQUEST["TicketId"];
    $Ip = $_REQUEST["Ip"];
    $CountrySelect = intval($_REQUEST["CountrySelect"]);
    $State = ($_REQUEST["State"] != 'A' && $_REQUEST["State"] != 'I') ? '' : $_REQUEST["State"];
    $State = $_REQUEST["State"];
    $TypeBet = $_REQUEST["TypeBet"];
    $TypeAwards = $_REQUEST["TypeAwards"];

    // Obtiene parámetros adicionales de filtrado
    $TypeUser = $_REQUEST["TypeUser"];
    $ValueMinimum = $_REQUEST["ValueMinimum"];
    $ValueMaximum = $_REQUEST["ValueMaximum"];
    $IsDetails = $_REQUEST["IsDetails"];

    // Configura los parámetros de paginación y ordenamiento
    $MaxRows = $_REQUEST["count"];
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
    $seguir = true;

    // Valida que los parámetros requeridos estén presentes
    if ($SkeepRows == "") {
        $seguir = false;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $seguir = false;
    }

    // Si los parámetros son válidos, procesa la consulta
    if ($seguir) {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        // Lógica específica para ambiente de desarrollo
        if ($ConfigurationEnvironment->isDevelopment()) {
            $FromDateLocal = $params->dateFrom;

            // Procesa el ID de usuario si está presente
            if ($FromId != "") {
                $UsuarioPerfil = new UsuarioPerfil($FromId, "");
            }

            // Procesa la fecha inicial si está presente en el request
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
                $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            // Procesa la fecha final
            $ToDateLocal = $params->dateTo;

            // Ajusta la fecha final si está presente en el request
            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }

            $rules = [];

            /* Inicializa el arreglo de reglas para filtrar las transacciones */

            /* if ($FromDateLocal == "") {
                 $FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
             }
             if ($ToDateLocal == "") {
                 $ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
             }
            */

            /* Agrega reglas de filtrado por fecha según el tipo de apuesta */
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

            /* Agrega reglas de filtrado por fecha final según el tipo de apuesta */
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

            /* Agrega reglas de filtrado por estado de eliminación */
            if ($TypeBet == 4) {
                // array_push($rules, array("field" => "transaccion_sportsbook.eliminado", "data" => "N", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "transaccion_sportsbook.eliminado", "data" => "N", "op" => "eq"));

            }
            // array_push($rules, array("field" => "transaccion_sportsbook.usuario_id", "data" => "$ClientId", "op" => "eq"));

            /* Agrega reglas de filtrado por región, moneda y otros parámetros básicos */
            if ($Region != "") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
            }

            if ($Currency != "") {
                array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
            }

            /* Agrega reglas de filtrado según el tipo de usuario y punto de venta */
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

            /* Agrega reglas de filtrado por jugador, IP y país */
            if ($PlayerId != "") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
            }

            if ($Ip != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.dir_ip", "data" => "$Ip", "op" => "cn"));
            }

            if ($CountrySelect != "" && $CountrySelect != "0") {
                array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
            }

            /* Agrega reglas de filtrado por estado de apuesta y ticket */
            if ($State != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.bet_status", "data" => "$State", "op" => "eq"));
            }

            if ($TicketId != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.ticket_id", "data" => "$TicketId", "op" => "eq"));
            }

            /* Agrega reglas de filtrado por montos máximos y mínimos */
            if ($ValueMaximum != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.vlr_premio", "data" => "$ValueMaximum", "op" => "le"));
            }

            if ($ValueMinimum != "") {
                array_push($rules, array("field" => "transaccion_sportsbook.vlr_premio", "data" => "$ValueMinimum", "op" => "ge"));
            }

            /* Agrega reglas de filtrado por tipo de usuario */
            if ($TypeUser == 1) {
                array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "eq"));
            }

            if ($TypeUser == 2) {
                array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "ne"));
            }

            /* Agrega reglas según el perfil del usuario en sesión */
            if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "CAJERO") {
                array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
            }

            /* Agrega reglas según condiciones de país y mandante */
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

            /* Prepara el filtro final y ejecuta la consulta */
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $TransaccionSportsbook = new TransaccionSportsbook();
            $tickets = $TransaccionSportsbook->getTransaccionesCustom(" usuario.nombre,usuario.login,usuario.moneda,transaccion_sportsbook.bet_status,transaccion_sportsbook.ticket_id,transaccion_sportsbook.usuario_id,transaccion_sportsbook.transsport_id,transaccion_sportsbook.vlr_apuesta,transaccion_sportsbook.vlr_premio,transaccion_sportsbook.estado,transaccion_sportsbook.fecha_crea,transaccion_sportsbook.dir_ip  ", "transaccion_sportsbook.transsport_id", "desc", $SkeepRows, $MaxRows, $json, true, '');
            $tickets = json_decode($tickets);
            /* Inicializa el arreglo que contendrá los resultados finales */
            $final = [];

            /* Itera sobre los tickets obtenidos para transformar los datos al formato requerido */
            foreach ($tickets->data as $key => $value) {

                $array = [];

                /* Asigna los valores básicos del ticket como ID, montos y estado */
                $array["Id"] = $value->{"transaccion_sportsbook.transsport_id"};
                $array["Amount"] = $value->{"transaccion_sportsbook.vlr_apuesta"};
                $array["Price"] = $value->{"transaccion_sportsbook.vlr_apuesta"};
                $array["WinningAmount"] = $value->{"transaccion_sportsbook.vlr_premio"};
                $array["StateName"] = $value->{"transaccion_sportsbook.estado"};
                $array["CreatedLocal"] = $value->{"transaccion_sportsbook.fecha_crea"} . " " . $value->{"transaccion_sportsbook.hora_crea"};
                $array["ClientLoginIP"] = $value->{"transaccion_sportsbook.dir_ip"};
                $array["Currency"] = $value->{"usuario.moneda"};

                /* Asigna información de identificación del ticket y usuario */
                $array["Id"] = $value->{"transaccion_sportsbook.ticket_id"};
                $array["UserId"] = $value->{"transaccion_sportsbook.usuario_id"};

                $array["UserName"] = $value->{"usuario_punto.nombre"};
                $array["UserName"] = '';
                $array["BetShop"] = $value->{"usuario_punto.nombre"};

                /* Maneja el caso cuando el nombre de usuario está vacío */
                if ($array["UserName"] == "") {
                    $array["UserName"] = $value->{"usuario.nombre"};
                }

                /* Asigna información adicional del ticket como estado, fecha y montos */
                $array["State"] = $value->{"transaccion_sportsbook.bet_status"};
                $array["Date"] = $value->{"transaccion_sportsbook.fecha_crea"} . " " . $value->{"transaccion_sportsbook.hora_crea"};
                $array["WinningAmount"] = $value->{"transaccion_sportsbook.vlr_premio"};
                $array["Tax"] = $value->{"transaccion_sportsbook.impuesto"};
                $array["WinningAmountTotal"] = floatval($array["WinningAmount"]) - floatval($array["Tax"]);
                $array["Odds"] = 0;
                $array["UserIP"] = $value->{"transaccion_sportsbook.dir_ip"};

                /* Agrega el ticket procesado al arreglo final */
                array_push($final, $array);

            }

            /* Prepara la respuesta con los resultados y metadata */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            /* Asigna los datos finales y contadores a la respuesta */
            $response["pos"] = $SkeepRows;
            $response["total_count"] = $tickets->count[0]->{".count"};
            $response["data"] = $final;

        } else {
            /* Inicializa las variables de fecha desde los parámetros */
            $FromDateLocal = $params->dateFrom;

            /* Crea objetos UsuarioPerfil según los IDs proporcionados */
            if ($FromId != "") {
                $UsuarioPerfil = new UsuarioPerfil($FromId, "");
            }
            if ($PlayerId != "") {
                $UsuarioPerfil = new UsuarioPerfil($PlayerId, "");
            }

            /* Procesa y formatea las fechas desde la solicitud considerando la zona horaria */
            if ($_REQUEST["dateFrom"] != "") {
                $FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
                $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
            }

            $ToDateLocal = $params->dateTo;

            if ($_REQUEST["dateTo"] != "") {
                $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
                $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
            }

            /* Inicia el procesamiento detallado si IsDetails es '1' */
            if ($IsDetails == '1') {
                $rules = [];

                /* Agrega reglas de filtrado por fecha según el tipo de apuesta */
                if ($FromDateLocal != "") {
                    if ($TypeBet == 2) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    } else if ($TypeBet == 3) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    } else if ($TypeBet == 4) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    } else {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    }
                }

                /* Agrega reglas adicionales de filtrado por fecha final */
                if ($ToDateLocal != "") {
                    if ($TypeBet == 2) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));
                    } else if ($TypeBet == 3) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));
                    } else if ($TypeBet == 4) {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    } else {
                        array_push($rules, array("field" => "(usuario_deporte_resumen.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));
                    }
                }

                /* Agrega reglas de filtrado por región y moneda */
                if ($Region != "") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
                }

                if ($Currency != "") {
                    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
                }

                /* Agrega reglas específicas según el ID de origen y tipo de usuario */
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

                /* Agrega reglas de filtrado según el ID del jugador y su perfil */
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

                /* Agrega reglas de filtrado por país y tipo de usuario */
                if ($CountrySelect != "" && $CountrySelect != "0") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                }

                $TipoUsuario = '';

                /* Configura reglas específicas según el tipo de usuario */
                if ($TypeUser == 1) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "eq"));
                    $TipoUsuario = 'U';
                }

                if ($TypeUser == 2) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "ne"));
                    $TipoUsuario = 'P';
                }

                /* Agrega reglas según el perfil de usuario en sesión */
                if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';
                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';
                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';
                }

                if ($_SESSION["win_perfil2"] == "CAJERO") {
                    array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';
                }

                if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                    $TipoUsuario = 'P';
                }

                /* Agrega reglas de filtrado por país y mandante según la sesión */
                if ($_SESSION['PaisCond'] == "S") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
                }

                if ($_SESSION['Global'] == "N") {
                    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
                } else {
                    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
                    }
                }

                // Inactivamos reportes para el país Colombia
                //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


                /* Define la consulta SQL principal para obtener datos de usuarios, puntos de pago y resumen de apuestas */
                $select = "usuario_punto_pago.nombre,usuario.mandante,pais.*,
            usuario_punto.nombre usuario_puntonombre,
            usuario_punto.usuario_id      usuario_puntousuario_id,
            usuario.nombre usuarionombre,
            usuario.login,
            usuario.moneda usuariomoneda,
            usuario_deporte_resumen.usuario_id,
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('1','2') AND usuario_deporte_resumen.estado ='A'  THEN usuario_deporte_resumen.valor WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','REFUND','STAKEDECREASE','1','2') THEN 0 ELSE usuario_deporte_resumen.valor END)  vlr_apuesta, 
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('2','3') AND usuario_deporte_resumen.estado ='P' THEN usuario_deporte_resumen.valor WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','REFUND','STAKEDECREASE') THEN usuario_deporte_resumen.valor ELSE 0 END) vlr_premio,
            usuario_deporte_resumen.fecha_crea ";

                /* Define consultas adicionales para totales y agrupación */
                $select2 = "a.*,
       SUM(a.vlr_apuesta) vlr_apuestatotal,
       SUM(a.vlr_premio)  vlr_premiototal";

                $grouping2 = "a.usuario_id,a.fecha_crea";

                /* Maneja diferentes tipos de consultas según el tipo de usuario */
                if ($TipoUsuario == 'U') {
                    /* Consulta específica para usuarios finales */
                    $select = "usuario_punto_pago.nombre,usuario.mandante,pais.*,
            usuario_punto.nombre,
            usuario.nombre,
            usuario.login,
            usuario.moneda usuariomoneda,
            usuario_deporte_resumen.usuario_id,
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','REFUND','STAKEDECREASE') THEN 0 ELSE usuario_deporte_resumen.valor END)  vlr_apuesta, 
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT','REFUND','STAKEDECREASE') THEN usuario_deporte_resumen.valor ELSE 0 END) vlr_premio,
            usuario_deporte_resumen.fecha_crea ";

                } elseif ($TipoUsuario == 'P') {
                    /* Consulta específica para puntos de venta */
                    $select = "usuario_punto_pago.nombre usuario_punto_pagonombre,usuario.mandante,pais.*,
            usuario_punto.nombre usuario_puntonombre,
            usuario_punto.usuario_id      usuario_puntousuario_id,
            usuario.nombre usuarionombre,
            usuario.login,
            usuario.moneda usuariomoneda,
            usuario_deporte_resumen.usuario_id,
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('1','2') AND usuario_deporte_resumen.estado ='A'  THEN usuario_deporte_resumen.valor ELSE 0 END)  vlr_apuesta, 
            SUM(CASE WHEN usuario_deporte_resumen.tipo IN ('2','3') AND usuario_deporte_resumen.estado ='P' THEN usuario_deporte_resumen.valor ELSE 0 END) vlr_premio,
            usuario_deporte_resumen.fecha_crea ";

                    $grouping2 = "a.usuario_puntousuario_id,a.fecha_crea";

                    /* Agrega reglas adicionales según el tipo de premios */
                    if ($TypeAwards == 2) {
                        array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => '1,3', "op" => "in"));

                    } else {
                        array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => '2', "op" => "in"));
                    }

                } else {
                    /* Maneja otros tipos de usuarios y agrega reglas según premios */
                    if ($TypeAwards == 2) {
                        array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => "'WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE', 'BET',1,3,'NEWCREDIT'", "op" => "in"));

                    } else {
                        if (date("Y-m-d H:i:s",strtotime($ToDateLocal)) < '2025-07-01 00:00:00') {

                            array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => "'WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE', 'BET',2,'NEWCREDIT','TAXBET','TAXWIN'", "op" => "in"));
                        } else {
                            array_push($rules, array("field" => "usuario_deporte_resumen.tipo", "data" => "'WIN', 'NEWCREDIT', 'CASHOUT', 'REFUND', 'STAKEDECREASE', 'BET',2,'NEWCREDIT','TAXBET','TAXWIN'", "op" => "in"));

                        } }
                }

                /* Prepara y ejecuta la consulta final */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioDeporteResumen = new UsuarioDeporteResumen();
                $tickets = $UsuarioDeporteResumen->getUsuarioDeporteResumenCustom($select, "usuario_deporte_resumen.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true,"usuario_deporte_resumen.usuario_id,usuario_deporte_resumen.estado,usuario_deporte_resumen.fecha_crea",$select2,$grouping2);
                $tickets = json_decode($tickets);

                /* Inicializa el arreglo para almacenar resultados procesados */
                $final = [];

                /* Itera sobre los tickets obtenidos para transformar los datos al formato requerido */
                foreach ($tickets->data as $key => $value) {

                    $array = [];

                    /* Asigna los valores básicos del ticket como ID, montos y estado */
                    $array["Id"] = 0;
                    $array["Amount"] = $value->{".vlr_apuestatotal"};
                    $array["Price"] = $value->{".vlr_apuestatotal"};
                    $array["WinningAmount"] = $value->{".vlr_premiototal"};

                    /* Asigna información de estado, fecha y datos del cliente */
                    $array["StateName"] = $value->{"it_ticket_enc.estado"};
                    $array["CreatedLocal"] = $value->{"a.fecha_crea"};
                    $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
                    $array["Currency"] = $value->{"a.usuariomoneda"};
                    $array["CurrencyId"] = $value->{"a.usuariomoneda"};

                    /* Asigna identificadores del ticket y usuario */
                    $array["Id"] = $value->{"it_ticket_enc.ticket_id"};
                    $array["UserId"] = $value->{"a.usuario_puntousuario_id"};

                    $array["UserName"] = $value->{"a.usuario_puntonombre"};

                    $array["BetShop"] = $value->{"a.usuario_puntonombre"};

                    /* Maneja el caso cuando el nombre de usuario está vacío */
                    if ($array["UserName"] == "") {
                        $array["UserName"] = $value->{"a.usuarionombre"};
                    }

                    /* Asigna estado y fecha del ticket */
                    $array["State"] = $value->{"it_ticket_enc.bet_status"};
                    $array["Date"] = $value->{"a.fecha_crea"};

                    /* Calcula montos finales incluyendo impuestos */
                    $array["WinningAmount"] = $value->{".vlr_premiototal"};
                    $array["Tax"] = 0;
                    $array["WinningAmountTotal"] = floatval($array["WinningAmount"]) - floatval($array["Tax"]);

                    /* Asigna valores adicionales como cuotas e IP */
                    $array["Odds"] = 0;
                    $array["UserIP"] = 0;
                    $array["BetShopPayment"] = $value->{"a.usuario_punto_pagonombre"};

                    /* Asigna información de socio y país */
                    $array["Partner"] = $value->{"a.mandante"};
                    $array["Country"] = $value->{"a.pais_nom"};
                    $array["CountryIcon"] = strtolower($value->{"a.iso"});

                    array_push($final, $array);
                }

                /* Prepara la respuesta con los resultados y metadata */
                $response["pos"] = $SkeepRows;
                $response["total_count"] = oldCount($final);
                $response["data"] = $final;

            } else {

                /* Inicializa el arreglo de reglas para el filtrado */
                $rules = [];

                /* Define la variable para dimensión de fecha */
                $daydimensionFecha=0;

                /* Agrega reglas de filtrado por fecha inicial según el tipo de apuesta */
                if ($FromDateLocal != "") {
                    if ($TypeBet == 2) {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre)", "data" => "$FromDateLocal", "op" => "ge"));
                        $daydimensionFecha=2;
                    } else if ($TypeBet == 3) {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_pago)", "data" => "$FromDateLocal", "op" => "ge"));
                        $daydimensionFecha=1;
                    } else if ($TypeBet == 4) {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre)", "data" => "$FromDateLocal", "op" => "ge"));
                        $daydimensionFecha=2;
                    } else {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_crea)", "data" => "$FromDateLocal", "op" => "ge"));
                    }
                }

                /* Agrega reglas de filtrado por fecha final según el tipo de apuesta */
                if ($ToDateLocal != "") {
                    if ($TypeBet == 2) {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre)", "data" => "$ToDateLocal", "op" => "le"));
                        $daydimensionFecha=2;
                    } else if ($TypeBet == 3) {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_pago)", "data" => "$ToDateLocal", "op" => "le"));
                        $daydimensionFecha=1;
                    } else if ($TypeBet == 4) {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre)", "data" => "$FromDateLocal", "op" => "ge"));
                        $daydimensionFecha=2;
                    } else {
                        array_push($rules, array("field" => "(it_ticket_enc.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));
                    }
                }

                /* Agrega reglas de filtrado por estado y eliminación */
                if ($TypeBet == 4) {
                    array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "J", "op" => "eq"));
                } else {
                    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
                }

                /* Agrega reglas de filtrado por región y moneda */
                if ($Region != "") {
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
                }

                if ($Currency != "") {
                    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
                }

                /* Agrega reglas de filtrado según el ID de origen y tipo de usuario */
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

                /* Agrega reglas de filtrado según el ID del jugador y su perfil */
                if ($PlayerId != "") {
                    if ($TypeBet == 3) {
                        if ($UsuarioPerfil->perfilId == "USUONLINE") {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                        }elseif ($UsuarioPerfil->perfilId == "MAQUINAANONIMA") {
                            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                        } else {
                            array_push($rules, array("field" => "it_ticket_enc.usumodifica_id", "data" => "$PlayerId", "op" => "eq"));
                        }
                    } else {
                        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
                    }
                }

                /* Agrega reglas de filtrado por IP, estado y ID del ticket */
                if ($Ip != "") {
                    array_push($rules, array("field" => "it_ticket_enc.dir_ip", "data" => "$Ip", "op" => "cn"));
                }

                if ($State != "") {
                    array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "$State", "op" => "eq"));
                }

                if ($TicketId != "") {
                    array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => "$TicketId", "op" => "eq"));
                }

                /* Agrega reglas de filtrado por valores máximos y mínimos */
                if ($ValueMaximum != "") {
                    array_push($rules, array("field" => "it_ticket_enc.vlr_premio", "data" => "$ValueMaximum", "op" => "le"));
                }

                if ($ValueMinimum != "") {
                    array_push($rules, array("field" => "it_ticket_enc.vlr_premio", "data" => "$ValueMinimum", "op" => "ge"));
                }

                /* Agrega reglas según el tipo de usuario y perfil de sesión */
                if ($TypeUser == 1) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "eq"));
                }

                if ($TypeUser == 2) {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => 0, "op" => "ne"));
                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
                    array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
                    array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }

                if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                    array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }

                if ($_SESSION["win_perfil2"] == "CAJERO") {
                    array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }

                if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
                    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
                }

                /* Agrega reglas según condiciones de país y mandante */
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

                /* Agrega reglas específicas para mandante 2 y selección de país */
                if($_SESSION['mandante'] == "2" && $_SESSION['Global'] == "N"){
                }else{
                    if ($CountrySelect != "" && $CountrySelect != "0") {
                        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
                    }
                }

                /* Prepara el filtro final y ejecuta la consulta */
                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $ItTicketEnc = new ItTicketEnc();
                $tickets = $ItTicketEnc->getTicketsCustom(" aes_decrypt(it_ticket_enc.clave, '".base64_decode($_ENV['APP_PASSKEY'])."') clave,usuario_punto_pago.nombre,usuario_punto.nombre usuario_puntonombre,usuario.nombre,usuario.login,usuario.moneda,it_ticket_enc.bet_status,it_ticket_enc.impuesto,it_ticket_enc.ticket_id,it_ticket_enc.usuario_id,it_ticket_enc.it_ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.fecha_crea,it_ticket_enc.hora_crea,it_ticket_enc.dir_ip  ", "it_ticket_enc.it_ticket_id", "desc", $SkeepRows, $MaxRows, $json, true,"","",true,$daydimensionFecha,false);
                $tickets = json_decode($tickets);

                /* Inicializa el arreglo para almacenar los resultados finales */
                $final = [];

                /* Itera sobre cada ticket obtenido para transformar los datos al formato requerido */
                foreach ($tickets->data as $key => $value) {

                    $array = [];

                    /* Asigna los valores básicos del ticket como ID, montos y estado */
                    $array["Id"] = $value->{"it_ticket_enc.it_ticket_id"};
                    $array["Amount"] = $value->{"it_ticket_enc.vlr_apuesta"};
                    $array["Price"] = $value->{"it_ticket_enc.vlr_apuesta"};
                    $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
                    $array["StateName"] = $value->{"it_ticket_enc.estado"};
                    $array["CreatedLocal"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};
                    $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
                    $array["Currency"] = $value->{"usuario.moneda"};

                    /* Asigna información de identificación del ticket y usuario */
                    $array["Id"] = $value->{"it_ticket_enc.ticket_id"};
                    $array["UserId"] = $value->{"it_ticket_enc.usuario_id"};

                    $array["UserName"] = $value->{"usuario_puntonombre"};
                    $array["UserName"] = '';
                    $array["BetShop"] = $value->{"usuario_puntonombre"};

                    /* Maneja el caso cuando el nombre de usuario está vacío */
                    if ($array["UserName"] == "") {
                        $array["UserName"] = $value->{"usuario.nombre"};
                    }

                    /* Asigna información adicional del ticket como estado, fecha y montos */
                    $array["State"] = $value->{"it_ticket_enc.bet_status"};
                    $array["Date"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};
                    $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
                    $array["Tax"] = $value->{"it_ticket_enc.impuesto"};
                    $array["WinningAmountTotal"] = floatval($array["WinningAmount"]) - floatval($array["Tax"]);

                    /* Asigna las probabilidades según el perfil del usuario */
                    if ($_SESSION["win_perfil2"] != "CAJERO" && $_SESSION["win_perfil2"] != "PUNTOVENTA") {
                        $array["Odds"] = $value->{".clave"};
                    }else{
                        $array["Odds"] = "";
                    }
                    $array["UserIP"] = $value->{"it_ticket_enc.dir_ip"};
                    $array["BetShopPayment"] = $value->{"usuario_punto_pago.nombre"};

                    /* Agrega el ticket procesado al arreglo final */
                    array_push($final, $array);
                }

                /* Asigna los datos finales y contadores a la respuesta */
                $response["pos"] = $SkeepRows;
                $response["total_count"] = $tickets->count[0]->{".count"};
                $response["data"] = $final;

            }

            /* Prepara la respuesta con los resultados y metadata */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            //$response["Data"] = array("Objects" => $final,
            //"Count" => $tickets->count[0]->{".count"});

        }
    } else {

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
}catch (Exception $e){
    print_r($e);
}