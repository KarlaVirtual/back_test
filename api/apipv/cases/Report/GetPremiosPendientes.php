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
 * Report/GetPremiosPendientes
 * 
 * Obtiene los premios pendientes según los filtros especificados
 *
 * @param array $params {
 *   "StartDateLocal": string,  // Fecha inicial en formato Y-m-d H:i:s
 *   "EndDateLocal": string,    // Fecha final en formato Y-m-d H:i:s
 *   "Region": string,          // Región a filtrar
 *   "Currency": string,        // Moneda a filtrar
 *   "ClientId": int,           // ID del cliente
 *   "OrderedItem": int         // Campo de ordenamiento
 * }
 * 
 * @param array $_REQUEST {
 *   "FromId": int,             // ID de origen
 *   "PlayerId": int,           // ID del jugador
 *   "Ip": string,              // Dirección IP
 *   "CountrySelect": int,      // ID del país
 *   "State": string,           // Estado (A, I, E)
 *   "count": int,              // Registros por página
 *   "start": int,              // Índice inicial
 *   "Type": string             // Tipo de consulta
 * }
 *
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success, error)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Errores del modelo
 *   "Data": array {
 *     "UserId": int,          // ID del usuario
 *     "Amount": float,        // Monto pendiente
 *     "Status": string,       // Estado del premio
 *     "CreatedDate": string,  // Fecha de creación
 *     "Game": string         // Juego asociado
 *   }[],
 *   "total_count": int       // Total de registros
 * }
 */
// Inicializa el objeto para manejar tickets
$ItTicketEnc = new ItTicketEnc();

// Obtiene y decodifica los parámetros enviados en el cuerpo de la petición
$params = file_get_contents('php://input');
$params = json_decode($params);

// Procesa y formatea las fechas de inicio y fin
$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->EndDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->StartDateLocal)));
$Region = $params->Region;
$Currency = $params->Currency;
$ClientId = $params->ClientId;

// Obtiene los parámetros de filtrado desde la URL
$FromId = $_REQUEST["FromId"];
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$CountrySelect = intval($_REQUEST["CountrySelect"]);
$State = ($_REQUEST["State"] != 'A' && $_REQUEST["State"] != 'I' && $_REQUEST["State"] != 'E') ? '' : $_REQUEST["State"];

// Configura parámetros de paginación y ordenamiento
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$Type = $_REQUEST["Type"];
$seguir = true;

// Valida que los parámetros de paginación sean válidos
if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $seguir = false;
}

// Configura parámetros adicionales para la consulta
$withCount = true;
$daydimensionFechaP = 0;
$forceTimeDimension = false;

if ($seguir) {

    // Procesa la fecha inicial
    $FromDateLocal = $params->dateFrom;

    if ($FromId != "") {
        $UsuarioPerfil = new UsuarioPerfil($FromId, "");
    }

    // Ajusta las fechas según zona horaria si vienen por REQUEST
    if ($_REQUEST["dateFrom"] != "") {
        $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
    }

    $ToDateLocal = $params->dateTo;

    if ($_REQUEST["dateTo"] != "") {
        $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
    }

    // Inicializa el array de reglas para filtrar
    $rules = [];

    // Agrega reglas de filtrado por fechas según el estado
    if ($FromDateLocal != "") {
        if ($State == 'E') {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_maxpago)", "data" => "$FromDateLocal ", "op" => "ge"));
            $daydimensionFechaP = 3;

        } else {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_crea)", "data" => "$FromDateLocal ", "op" => "ge"));

        }
    }
    if ($ToDateLocal != "") {
        if ($State == 'E') {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_maxpago)", "data" => "$ToDateLocal", "op" => "le"));
            $daydimensionFechaP = 3;

        } else {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_crea)", "data" => "$ToDateLocal", "op" => "le"));

        }
    }

    // Agrega reglas básicas de filtrado
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

    if ($State != "E") {
        array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
    }

    // Agrega filtros por región y moneda
    if ($Region != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
    }

    if ($Currency != "") {
        array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
    }

    // Agrega filtros según el tipo de usuario y permisos
    if ($FromId != "") {
        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "$FromId", "op" => "eq"));
        } else {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$FromId", "op" => "eq"));
        }
    }

    // Agrega filtros por jugador, IP y país
    if ($PlayerId != "") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$PlayerId", "op" => "eq"));
    }

    if ($Ip != "") {
        array_push($rules, array("field" => "it_ticket_enc.dir_ip", "data" => "$Ip", "op" => "cn"));
    }

    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Agrega filtros por estado y perfiles especiales
    if ($State != "") {
        array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "$State", "op" => "eq"));
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

    // Agrega filtros para perfiles de cajero y punto de venta
    if ($_SESSION["win_perfil2"] == "CAJERO") {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    // Agrega filtros por país y mandante según configuración
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

    // Agrega filtros finales para premios
    array_push($rules, array("field" => "it_ticket_enc.premiado", "data" => "S", "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.premio_pagado", "data" => "N", "op" => "eq"));
    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "0", "op" => "ne"));

    // Prepara el JSON de filtros
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    // Define los campos a seleccionar y agrupación
    $select = " usuario.login,usuario.moneda,it_ticket_enc.ticket_id,it_ticket_enc.usuario_id,it_ticket_enc.it_ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.fecha_crea,it_ticket_enc.hora_crea,it_ticket_enc.dir_ip,it_ticket_enc.fecha_cierre,it_ticket_enc.hora_cierre,it_ticket_enc.fecha_maxpago  ";
    $grouping = "";

    // Ajusta la consulta según el tipo
    if ($Type == 1) {
        $withCount = false;
        $forceTimeDimension = true;
        $select = " usuario.login,usuario.moneda,SUM(it_ticket_enc.vlr_apuesta) vlr_apuesta,SUM(it_ticket_enc.vlr_premio) vlr_premio,it_ticket_enc.estado,it_ticket_enc.fecha_crea,it_ticket_enc.hora_crea,it_ticket_enc.dir_ip,it_ticket_enc.fecha_cierre,it_ticket_enc.hora_cierre,it_ticket_enc.fecha_maxpago  ";
        $grouping = "usuario.usuario_id,it_ticket_enc.fecha_crea";
    }

    // Ejecuta la consulta y procesa resultados
    $ItTicketEnc = new ItTicketEnc();
    $tickets = $ItTicketEnc->getTicketsCustom($select, "it_ticket_enc.it_ticket_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, "", $withCount, $daydimensionFechaP, $forceTimeDimension);
    $tickets = json_decode($tickets);

    $final = [];

    // Procesa cada ticket y formatea la respuesta
    foreach ($tickets->data as $key => $value) {
        if ($Type == 1) {
            $array = [];
            // Formatea datos para tipo 1
            $array["Id"] = $value->{"it_ticket_enc.it_ticket_id"};
            $array["Amount"] = $value->{".vlr_apuesta"};
            $array["Price"] = $value->{".vlr_apuesta"};
            $array["WinningAmount"] = $value->{".vlr_premio"};
            $array["StateName"] = $value->{"it_ticket_enc.estado"};
            $array["CreatedLocal"] = $value->{"it_ticket_enc.fecha_crea"};
            $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
            $array["Currency"] = $value->{"usuario.moneda"};


            $array["Id"] = $value->{"it_ticket_enc.ticket_id"};
            $array["UserId"] = $value->{"it_ticket_enc.usuario_id"};

            $array["UserName"] = $value->{"usuario.login"};
            $array["State"] = $value->{"it_ticket_enc.estado"};
            $array["Date"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};
            $array["WinningAmount"] = $value->{".vlr_premio"};
            $array["Odds"] = 0;
            $array["UserIP"] = $value->{"it_ticket_enc.dir_ip"};


            $array["NoTicket"] = $value->{"it_ticket_enc.ticket_id"};
            $array["ValueBet"] = $value->{".vlr_apuesta"};
            $array["Price"] = $value->{".vlr_apuesta"};
            $array["WinningAmount"] = $value->{".vlr_premio"};
            $array["StateName"] = $value->{"it_ticket_enc.estado"};
            $array["CreationDate"] = $value->{"it_ticket_enc.fecha_crea"};
            $array["CreationTime"] = $value->{"it_ticket_enc.hora_crea"};
            $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
            $array["Currency"] = $value->{"usuario.moneda"};


            $array["Id"] = $value->{"it_ticket_enc.ticket_id"};
            $array["UserId"] = $value->{"it_ticket_enc.usuario_id"};

            $array["PointSale"] = $value->{"usuario.login"};
            $array["State"] = $value->{"it_ticket_enc.estado"};
            $array["DatePrize"] = $value->{"it_ticket_enc.fecha_cierre"};
            $array["TimePrize"] = $value->{"it_ticket_enc.hora_cierre"};
            $array["ValuePrize"] = $value->{".vlr_premio"};
            $array["Odds"] = 0;
            $array["ExpiredDate"] = $value->{"it_ticket_enc.fecha_maxpago"};

            array_push($final, $array);

        } else {
            $array = [];
            // Formatea datos para otros tipos
            $array["Id"] = $value->{"it_ticket_enc.it_ticket_id"};
            $array["Amount"] = $value->{"it_ticket_enc.vlr_apuesta"};
            $array["Price"] = $value->{"it_ticket_enc.vlr_apuesta"};
            $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
            $array["StateName"] = $value->{"it_ticket_enc.estado"};
            $array["CreatedLocal"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};
            $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
            $array["Currency"] = $value->{"usuario.moneda"};


            $array["Id"] = $value->{"it_ticket_enc.ticket_id"};
            $array["UserId"] = $value->{"it_ticket_enc.usuario_id"};

            $array["UserName"] = $value->{"usuario.login"};
            $array["State"] = $value->{"it_ticket_enc.estado"};
            $array["Date"] = $value->{"it_ticket_enc.fecha_crea"} . " " . $value->{"it_ticket_enc.hora_crea"};
            $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
            $array["Odds"] = 0;
            $array["UserIP"] = $value->{"it_ticket_enc.dir_ip"};


            $array["NoTicket"] = $value->{"it_ticket_enc.ticket_id"};
            $array["ValueBet"] = $value->{"it_ticket_enc.vlr_apuesta"};
            $array["Price"] = $value->{"it_ticket_enc.vlr_apuesta"};
            $array["WinningAmount"] = $value->{"it_ticket_enc.vlr_premio"};
            $array["StateName"] = $value->{"it_ticket_enc.estado"};
            $array["CreationDate"] = $value->{"it_ticket_enc.fecha_crea"};
            $array["CreationTime"] = $value->{"it_ticket_enc.hora_crea"};
            $array["ClientLoginIP"] = $value->{"it_ticket_enc.dir_ip"};
            $array["Currency"] = $value->{"usuario.moneda"};


            $array["Id"] = $value->{"it_ticket_enc.ticket_id"};
            $array["UserId"] = $value->{"it_ticket_enc.usuario_id"};

            $array["PointSale"] = $value->{"usuario.login"};
            $array["State"] = $value->{"it_ticket_enc.estado"};
            $array["DatePrize"] = $value->{"it_ticket_enc.fecha_cierre"};
            $array["TimePrize"] = $value->{"it_ticket_enc.hora_cierre"};
            $array["ValuePrize"] = $value->{"it_ticket_enc.vlr_premio"};
            $array["Odds"] = 0;
            $array["ExpiredDate"] = $value->{"it_ticket_enc.fecha_maxpago"};

            array_push($final, $array);
        }

    }

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $tickets->count[0]->{".count"};
    $response["data"] = $final;

    if ($Type == "1") {
        $response["total_count"] = oldCount($final);
    }
} else {
    // Prepara respuesta para caso de error
    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
