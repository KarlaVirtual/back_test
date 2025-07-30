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
use Backend\dto\LealtadHistorial;
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
 * Procesa el historial de lealtad del usuario y genera una respuesta con los datos obtenidos.
 *
 * @param object $params Objeto JSON decodificado que contiene los parámetros de entrada:
 * @param bool $IsDetails Indica si se deben incluir detalles adicionales.
 * @param int $CurrencyId ID de la moneda.
 * @param bool $IsTest Indica si es una prueba.
 * @param int $ProductId ID del producto.
 * @param int $ProviderId ID del proveedor.
 * @param string $Region Región seleccionada.
 * @param int $MaxRows Número máximo de filas a obtener.
 * @param int $OrderedItem Elemento ordenado.
 * @param int $SkeepRows Número de filas a omitir.
 * @param string $dateTo Fecha de fin en formato local.
 * @param string $dateFrom Fecha de inicio en formato local.
 *
 * @return array $response Respuesta generada con los siguientes campos:
 *                         - bool $HasError Indica si hubo un error.
 *                         - string $AlertType Tipo de alerta.
 *                         - string $AlertMessage Mensaje de alerta.
 *                         - array $ModelErrors Errores del modelo.
 *                         - int $pos Posición de las filas omitidas.
 *                         - int $total_count Conteo total de registros.
 *                         - array $data Datos obtenidos.
 *
 * @throws Exception Si ocurre un error durante el procesamiento.
 */
//error_reporting(E_ALL);
//ini_set("display_errors","ON");


/* crea un objeto Usuario y obtiene parámetros JSON del cuerpo de la solicitud. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$IsDetails = $params->IsDetails;
$CurrencyId = $params->CurrencyId;

/* Se extraen parámetros de entrada para procesar datos específicos en una función. */
$IsTest = $params->IsTest;
$ProductId = $params->ProductId;
$ProviderId = $params->ProviderId;
$Region = $params->Region;

$MaxRows = $params->MaxRows;

/* asigna valores de parámetros a variables relacionadas con fechas y elementos pedidos. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$ToDateLocal = $params->dateTo;


$FromDateLocal = $params->dateFrom;


/* Convierte fechas de entrada en formato "Y-m-d" según la zona horaria. */
if ($_REQUEST["dateFrom"] != "") {
    //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));

}
if ($_REQUEST["dateTo"] != "") {
    //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . ' +0 day' . $timezone . ' hour '));

}


/* valida y asigna valores de entrada a variables específicas. */
$PlayerId = $_REQUEST['PlayerId'];
$UserId = $_REQUEST['UserId'];

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';

$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';


/* obtiene valores de parámetros de una solicitud HTTP en PHP. */
$IsDetails = $_REQUEST["IsDetails"];
$Type = $_REQUEST["Type"];
$FromId = $_REQUEST["FromId"];


$Movement = $_REQUEST["Movement"];

/* Código PHP para capturar parámetros de una solicitud HTTP y manejarlos. */
$Type = $_REQUEST["Type"];
$ExternalId = $_REQUEST["ExternalId"];

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$seguir = true;


/* verifica condiciones y asigna valores a variables en PHP. */
if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* verifica si $MaxRows está vacío y cambia $seguir a false. */
if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* alterna el valor de la variable $IsDetails entre verdadero y falso. */
    if ($IsDetails == 1) {
        $IsDetails = false;

    } else {
        $IsDetails = true;
    }


    /* Se creó un array de reglas dinámico basado en la variable $Region. */
    $rules = [];


    if ($Region != "") {
        array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => "$Region", "op" => "eq"));
    }

    /* establece una condición para agregar filtros según el ID del jugador. */
    $withUser = false;

    if ($PlayerId != "") {
        array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => "$PlayerId", "op" => "eq"));
        $withUser = true;

    }


    /* agrega reglas para filtrar usuarios basado en identificadores proporcionados. */
    if ($UserId != "") {
        array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => "$UserId", "op" => "eq"));
        $withUser = true;

    }

    if ($FromId != "") {
        array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => "$FromId", "op" => "eq"));
        $withUser = true;

    }


    /* Se agregan reglas de filtrado por fecha si están definidas en $FromDateLocal y $ToDateLocal. */
    if ($withUser) {

        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));


        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
        }
    } else {
        /* Agrega condiciones de fecha a un conjunto de reglas en un arreglo. */


        if ($FromDateLocal != "") {
            array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

            //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));

        }

        if ($ToDateLocal != "") {
            array_push($rules, array("field" => "lealtad_historial.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
            //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));
        }
    }


    /* Añade reglas a un array basadas en condiciones de tipo y movimiento. */
    if ($Type != "" && is_numeric($Type)) {

        array_push($rules, array("field" => "lealtad_historial.tipo", "data" => $Type, "op" => "eq"));

    }


    if ($Movement == '1') {

        array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => "E", "op" => "eq"));

    }


    /* Agrega reglas al array según el valor de $Movement. */
    if ($Movement == '2') {

        array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => "S", "op" => "eq"));

    }

    if ($Movement == '3') {

        array_push($rules, array("field" => "lealtad_historial.movimiento", "data" => "C", "op" => "eq"));

    }


    /* Se agregan condiciones para filtrar reglas basadas en ID externo y país seleccionado. */
    if ($ExternalId != "") {
        array_push($rules, array("field" => "lealtad_historial.externo_id", "data" => $ExternalId, "op" => "eq"));

    }

    if ($CountrySelect != "" && $CountrySelect != "0") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País

    /* verifica condiciones y agrega reglas a un array basado en sesiones. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega una regla si mandanteLista no está vacío ni es -1. */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    /*    if ($_SESSION['win_perfil2'] == "PUNTOVENTA" || $_SESSION['win_perfil2'] == "CAJERO" ) {

            array_push($rules, array("field" => "lealtad_historial.usuario_id", "data" => $_SESSION['usuario'], "op" => "eq"));
        }

        if($_SESSION['win_perfil2'] == "CONCESIONARIO" ){
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        }

        if($_SESSION['win_perfil2'] == "CONCESIONARIO2" ){
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        }
        if($_SESSION['win_perfil2'] == "CONCESIONARIO3" ){
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION['usuario'], "op" => "eq"));

        }*/
    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* Se define un filtro en formato JSON para consultas de lealtad historial. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "lealtad_historial.*,usuario.nombre ";

    $LealtadHistorial = new LealtadHistorial();

    /* obtiene y decodifica un historial de lealtad en formato JSON. */
    $data = $LealtadHistorial->getLealtadHistorialCustom($select, "lealtad_historial.lealtadhistorial_id", "desc", $SkeepRows, $MaxRows, $json, true, !$withUser);
    $data = json_decode($data);


    $final = [];

    $papuestas = 0;

    /* Se inicializan dos variables: $ppremios y $pcont, ambas en cero. */
    $ppremios = 0;
    $pcont = 0;

    foreach ($data->data as $key => $value) {


        /* Extrae datos de un objeto y asigna valores a un array en PHP. */
        $array["Id"] = $value->{"lealtad_historial.lealtadhistorial_id"};
        $array["UserId"] = $value->{"lealtad_historial.usuario_id"};
        $array["UserName"] = $value->{"usuario.nombre"};
        $array["Movement"] = ($value->{"lealtad_historial.movimiento"} === 'E') ? 0 : $value->{"lealtad_historial.movimiento"};

        if ($array["Movement"] === 'S') {
            $array["Movement"] = 1;
        }


        /* cambia 'C' por 2 en un arreglo y asigna un tipo. */
        if ($array["Movement"] === 'C') {
            $array["Movement"] = 2;
        }


        $array["Type"] = $value->{"lealtad_historial.tipo"};

        switch ($value->{"lealtad_historial.tipo"}) {
            case 10:
                /* Establece el tipo como 'Recarga' cuando el caso es 10 en un switch. */

                $array["Type"] = 'Recarga';

                break;
            case 15:
                /* asigna el tipo 'Ajuste de saldo' al índice 15 del array. */

                $array["Type"] = 'Ajuste de saldo';

                break;
            case 20:
                /* Asignación del tipo 'Apuestas Deportivas' en un arreglo basado en el caso 20. */

                $array["Type"] = 'Apuestas Deportivas';

                break;
            case 30:
                /* Define un caso en un switch que asigna 'Apuestas Casino' a una variable. */

                $array["Type"] = 'Apuestas Casino';

                break;
            case 31:
                /* Asignación de tipo 'Apuestas Casino en vivo' al elemento en el caso 31. */

                $array["Type"] = 'Apuestas Casino en vivo';

                break;
            case 40:
                /* Asigna "Nota de retiro Creada" al elemento "Type" del array en el caso 40. */

                $array["Type"] = 'Nota de retiro Creada';

                break;
            case 50:
                /* asigna 'Regalo Redimido' al tipo en el caso 50. */

                $array["Type"] = 'Regalo Redimido';

                break;
            case 51:
                $array["Type"] = 'Puntos expirados';
                break;

        }

        if (strtolower($_SESSION["idioma"]) == "en") {

            switch ($value->{"lealtad_historial.tipo"}) {
                case 10:
                    /* Asigna 'Deposit' al campo "Type" del array si el caso es 10. */

                    $array["Type"] = 'Deposit';

                    break;
                case 15:
                    /* Asignación de tipo 'Balance adjustment' al arreglo para el caso 15. */

                    $array["Type"] = 'Balance adjustment';

                    break;
                case 20:
                    /* asigna 'Sports bets' al índice 'Type' de un array si cumple la condición 20. */

                    $array["Type"] = 'Sports bets';

                    break;
                case 30:
                    /* asigna el tipo 'Casino Gambling' a un elemento del array. */

                    $array["Type"] = 'Casino Gambling';

                    break;
                case 40:
                    /* Asignar "Withdrawal note Created" a la clave "Type" en un array. */

                    $array["Type"] = 'Withdrawal note Created';

                    break;
                case 50:
                    /* Código asigna 'Bonus Redeemed' a un elemento del arreglo según el caso 50. */

                    $array["Type"] = 'Bonus Redeemed';

                    break;

                case 51:
                    $array["Type"] = 'Expired points';
                    break;
            }
        }


        /* Se asignan valores de un objeto a un array y se agrega a otro array. */
        $array["ExternalId"] = $value->{"lealtad_historial.externo_id"};
        $array["CreatedLocalDate"] = $value->{"lealtad_historial.fecha_crea"};
        $array["EndPoints"] = $value->{"lealtad_historial.creditos"};
        $array["Points"] = $value->{"lealtad_historial.valor"};

        array_push($final, $array);


    }
    /*if (!$IsDetails) {
        if ($pcont > 0) {
            $array["Game"] = $prod->{"producto.descripcion"};
            $array["ProviderName"] = $prod->{"proveedor.descripcion"};
            $array["Bets"] = $pcont;
            $array["Stakes"] = $papuestas;
            $array["Winnings"] = $ppremios;
            $array["Profit"] = 0;
            $array["BonusCashBack"] = 0;
            $array["CurrencyId"] = $prod->{"usuario_mandante.moneda"};

            array_push($final, $array);
        }
    }*/


    /* Código que inicializa una respuesta sin errores y establece propiedades para alertas. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = $SkeepRows;

    /* asigna un conteo total y datos finales a un arreglo de respuesta. */
    $response["total_count"] = $data->count[0]->{".count"};
    $response["data"] = $final;


} else {
    /* define una respuesta sin errores, con datos inicializados a cero. */

    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}
