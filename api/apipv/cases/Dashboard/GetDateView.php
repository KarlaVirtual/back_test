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
 * Dashboard/GetDateView
 *
 * Este script obtiene información resumida de apuestas y premios por fecha, considerando filtros de región, moneda y perfil del usuario.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $params->FromDateLocal Fecha inicial del rango en formato "Y-m-d".
 * @param string $params->ToDateLocal Fecha final del rango en formato "Y-m-d".
 * @param string $params->Region Identificador de la región para filtrar los datos.
 * @param string $params->CurrencyId Moneda destino para la conversión.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Orden de los elementos (1 por defecto).
 * @param int $params->SkeepRows Número de filas a omitir (0 por defecto).
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes atributos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Información estructurada con los siguientes datos:
 *      - BetAmount (float): Monto total de apuestas convertidas.
 *      - WinningAmount (float): Monto total de premios convertidos.
 *      - BetCount (int): Total de apuestas realizadas.
 *
 * @throws Exception No se lanza ninguna excepción explícita en este script.
 */


/* inicializa un objeto y procesa una fecha a partir de parámetros. */
$ItTicketEnc = new ItTicketEnc();

$seguir = true;

if ($params->FromDateLocal != "") {
    $FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
} else {
    /* establece la variable `$seguir` como falsa en una condición alternativa. */

    $seguir = false;
}


/* Convierte una fecha de formato basado en texto a formato "Y-m-d". */
if ($params->ToDateLocal != "") {
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));
    $ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $params->ToDateLocal)));
} else {
    $seguir = false;
}

if ($seguir) {


    /* asigna valores de parámetros a variables para su procesamiento posterior. */
    $Region = $params->Region;
    $Region = $params->Country;
    $CurrencyId = $params->CurrencyId;

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;

    /* asigna valores predeterminados a variables si están vacías. */
    $SkeepRows = $params->SkeepRows;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* asigna un valor predeterminado a $MaxRows y crea un objeto Mandante. */
    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    $Mandante = new Mandante($_SESSION["mandante"]);

    if ($Mandante->propio == "S") {


        /* define reglas para filtrar tickets por fechas de creación. */
        $rules = [];
        //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


        /* Agrega reglas de filtrado basado en región y moneda si están definidas. */
        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }


        /* verifica el perfil del usuario y asigna reglas específicas. */
        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if ($_SESSION["win_perfil2"] == "CAJERO") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        /* añade reglas a un arreglo según el perfil del usuario en sesión. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        /* Agrega reglas a un arreglo según el perfil del usuario y su país. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }

        // Si el usuario esta condicionado por el mandante y no es de Global

        /* Condiciona reglas basadas en la sesión de 'Global' y 'mandanteLista'. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Se configura un filtro JSON para consultar tickets sin eliminar de una base de datos. */
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $tickets = $ItTicketEnc->getTicketsCustom(" usuario.moneda,COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda", "", false, "0", true);


        /* Convierte datos JSON de boletos y inicializa variables para apuestas y premios. */
        $tickets = json_decode($tickets);


        $valor_convertido_apuestas = 0;
        $valor_convertido_premios = 0;
        $total = 0;

        /* convierte apuestas en diferentes monedas según el perfil de usuario. */
        foreach ($tickets->data as $key => $value) {

            if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO" || $_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3") {
                $converted_currency = round($value->{".apuestas"}, 2);
                $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;

            } else {
                $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $_SESSION["monedaReporte"], round($value->{".apuestas"}, 2));
                $valor_convertido_apuestas = $valor_convertido_apuestas + $converted_currency;

            }


            $total = $total + $value->{".count"};

        }

        /* define reglas de filtrado para entradas de tickets por fecha. */
        $rules = [];
        //array_push($rules, array("field" => "it_ticket_enc.estado", "data" => "I", "op" => "eq"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_pago,' ',it_ticket_enc.hora_pago)", "data" => "$FromDateLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_pago,' ',it_ticket_enc.hora_pago)", "data" => "$ToDateLocal", "op" => "le"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));


        /* Agrega reglas basadas en condiciones de región y moneda en un array. */
        if ($Region != "") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => "$Region", "op" => "eq"));
        }

        if ($Currency != "") {
            array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));
        }


        /* Verifica el perfil del usuario y añade reglas a un array según corresponda. */
        if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }

        if ($_SESSION["win_perfil2"] == "CAJERO") {
            array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        /* Condiciona reglas en un arreglo según el perfil de usuario en sesión. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO") {
            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        if ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {
            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        /* valida condiciones de acceso basadas en perfil y país del usuario. */
        if ($_SESSION["win_perfil2"] == "CONCESIONARIO3") {
            array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => $_SESSION["usuario"], "op" => "eq"));
        }


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }

        // Si el usuario esta condicionado por el mandante y no es de Global

        /* ajusta reglas basadas en variables de sesión sobre los mandantes. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }

        // Inactivamos reportes para el país Colombia
        //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


        /* Se define un filtro JSON y se consultan tickets con reglas específicas. */
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $tickets = $ItTicketEnc->getTicketsCustom(" usuario.moneda,COUNT(*) count,SUM(it_ticket_enc.vlr_apuesta) apuestas, SUM(CASE WHEN it_ticket_enc.premiado = 'S' THEN it_ticket_enc.vlr_premio ELSE 0 END) premios  ", "it_ticket_enc.it_ticket_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda", "", false, "1", true);


        /* convierte premios de tickets a una moneda específica según el perfil de usuario. */
        $tickets = json_decode($tickets);

        $valor_convertido_premios = 0;
        foreach ($tickets->data as $key => $value) {

            if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $_SESSION["win_perfil2"] == "CAJERO" || $_SESSION["win_perfil2"] == "CONCESIONARIO" || $_SESSION["win_perfil2"] == "CONCESIONARIO2" || $_SESSION["win_perfil2"] == "CONCESIONARIO3") {

                $converted_currency = round($value->{".premios"}, 2);
                $valor_convertido_premios = $valor_convertido_premios + $converted_currency;
            } else {
                $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $_SESSION["monedaReporte"], round($value->{".premios"}, 2));
                $valor_convertido_premios = $valor_convertido_premios + $converted_currency;

            }
        }

        /* asigna valores de apuesta, premio y conteo a un array. */
        $response["Data"] = array(
            "BetAmount" => intval($valor_convertido_apuestas),
            "WinningAmount" => intval($valor_convertido_premios),
            "BetCount" => $total,

        );
        $response = array(
            array(
                "id" => 1,
                "name" => "APUESTAS",
                "icon" => "icon-money",
                "value" => number_format(round($valor_convertido_apuestas, 2))
            ),
            array(
                "id" => 2,
                "name" => "PREMIOS PAGADOS",
                "icon" => "icon-money",
                "value" => number_format(round($valor_convertido_premios, 2))
            )
            /*,
                array(
                    "id" => 3,
                    "name" => "CANTIDAD DE APUESTAS",
                    "icon" => "icon-money",
                    "value" => $total
                )*/
        );
    } else {

        /* establece variables y maneja un caso para filas a omitir. */
        exit();

        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* asigna valores predeterminados si las variables están vacías. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10;
        }


        /* Genera reglas de filtrado para transacciones, considerando condiciones de fecha y país. */
        $rules = [];
        array_push($rules, array("field" => "transaccion_juego.fecha_modif", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "transaccion_juego.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        }

        // Si el usuario esta condicionado por el mandante y no es de Global

        /* Condiciona reglas basadas en la sesión del usuario para filtrar mandantes. */
        if ($_SESSION['Global'] == "N") {
            array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        } else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                array_push($rules, array("field" => "usuario_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
            }

        }


        /* Genera un filtro en JSON y obtiene transacciones personalizadas de juegos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $TransaccionJuego = new TransaccionJuego();
        $transacciones = $TransaccionJuego->getTransaccionesCustom(" SUM(transaccion_juego.valor_ticket) apuestas, SUM(CASE WHEN transaccion_juego.premiado = 'S' THEN transaccion_juego.valor_premio ELSE 0 END) premios,usuario_mandante.moneda  ", "transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_mandante.moneda");

        $transacciones = json_decode($transacciones);


        /* Suma las apuestas y premios, convirtiendo a la moneda seleccionada por el usuario. */
        $valor_convertido = 0;
        $apuestas = 0;
        $premios = 0;

        foreach ($transacciones->data as $key => $value) {
            if ($_SESSION["monedaReporte"] != "") {
                $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $_SESSION["monedaReporte"], round($value->{".apuestas"}, 2));
                $apuestas = $apuestas + $converted_currency;

                $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario_mandante.moneda"}, $_SESSION["monedaReporte"], round($value->{".premios"}, 2));
                $premios = $premios + $converted_currency;

            } else {
                $converted_currency = round($value->{".apuestas"}, 2);
                $apuestas = $apuestas + $converted_currency;

                $converted_currency = round($value->{".premios"}, 2);
                $premios = $premios + $converted_currency;

            }

        }


        /* Se definen reglas de filtrado para transacciones en un rango de fechas. */
        $rules = [];

        array_push($rules, array("field" => "ApiTransactions.trnFecReg", "data" => "$FromDateLocal ", "op" => "ge"));
        array_push($rules, array("field" => "ApiTransactions.trnFecReg", "data" => "$ToDateLocal", "op" => "le"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");


        /* convierte un filtro a JSON y configura la localización a checo. */
        $json = json_encode($filtro);

        setlocale(LC_ALL, 'czech');


        $ApiTransaction = new ApiTransaction();

        /* Calcula apuestas y premios convertidos de moneda en transacciones de un casino. */
        $casino2 = $ApiTransaction->getTransaccionesCustom(" usuario.moneda,SUM(CASE WHEN ApiTransactions.trnType = 'BET' THEN ApiTransactions.trnMonto ELSE 0 END) apuestas, SUM(CASE WHEN ApiTransactions.trnType = 'WIN' THEN ApiTransactions.trnMonto ELSE 0 END) premios ", "ApiTransactions.trnID", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");

        $casino2 = json_decode($casino2);

        foreach ($casino2->data as $key => $value) {
            $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $_SESSION["monedaReporte"], round($value->{".apuestas"}, 2));
            $apuestas = $apuestas + $converted_currency;

            $converted_currency = (new ConfigurationEnvironment())->currencyConverter($value->{"usuario.moneda"}, $_SESSION["monedaReporte"], round($value->{".premios"}, 2));
            $premios = $premios + $converted_currency;

        }


        /* inicializa una respuesta exitosa con datos de apuestas y premios. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["Data"] = array(
            "BetAmount" => $apuestas,
            "WinningAmount" => $premios,

        );


        $response = array(
            array(
                "id" => 1,
                "name" => "APUESTAS",
                "icon" => "icon-money",
                "value" => number_format((round($apuestas, 2)))
            ),
            array(
                "id" => 2,
                "name" => "PREMIOS",
                "icon" => "icon-money",
                "value" => number_format(round($premios, 2))
            )
            /*,
                array(
                    "id" => 3,
                    "name" => "CANTIDAD DE APUESTAS",
                    "icon" => "icon-money",
                    "value" => $total
                )*/
        );

    }


} else {
    /* Se inicializa un arreglo vacío llamado `$response` si no se cumple una condición. */

    $response = array();
}