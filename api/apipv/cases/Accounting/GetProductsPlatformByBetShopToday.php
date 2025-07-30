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
 * Accounting/GetProductsPlatformByBetShopToday
 *
 * Obtener los productos de la plataforma de un punto de venta de el día hoy
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * Accounting/GetProductsPlatformByBetShopToday
 *
 * Obtener los productos de la plataforma de un punto de venta de el día en curso
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 *  - int $CloseBoxId ID del cierre de caja.
 *  - int $Id ID del producto.
 *  - int $MaxRows Número máximo de filas a obtener.
 *  - int $OrderedItem Elemento ordenado.
 *  - int $SkeepRows Número de filas a omitir.
 *
 * @return array Respuesta en formato JSON con los siguientes campos:
 *  - bool $HasError Indica si hubo un error.
 *  - string $AlertType Tipo de alerta.
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo.
 *  - array $Data Datos finales.
 *  - int $pos Posición de inicio.
 *  - int $total_count Conteo total de elementos.
 */


/* Se crean objetos de UsuarioMandante y Usuario utilizando datos de sesión y parámetros. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$CloseBoxId = $params->CloseBoxId;
$fechaEspecifica = '';
$BetShopId = 0;

/* verifica un ID y obtiene la fecha y usuario del cierre de caja. */
if ($CloseBoxId != "") {
    $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
    $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

    $BetShopId = $UsuarioCierrecaja->getUsuarioId();
}

/* Se inicializa un arreglo vacío llamado "final" en PHP. */
$final = [];

if ($BetShopId == 0) {


    /* Se asignan valores de parámetros y se obtiene "count" de una solicitud. */
    $Id = $params->Id;

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    $MaxRows = $_REQUEST["count"];

    /* Asignación de filas para omitir, con valor predeterminado de cero si está vacío. */
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000;
    }


    /* Se definen reglas basadas en el perfil de usuario en sesión. */
    $rules = [];
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }

    /* Crea un filtro JSON con reglas para buscar tickets no eliminados en fecha actual. */
    array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => 'N', "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Se crea un objeto y se obtienen tickets personalizados en formato JSON. */
    $ItTicketEnc = new ItTicketEnc();

    $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_apuesta) vlr_apuesta, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, 0, true);

    $data = json_decode($data);
    $array = [];


    /* Inicializa un arreglo y actualiza el número de apuestas a partir de datos externos. */
    $array["Id"] = 0;
    $array["Product"] = "Doradobet Tickets";
    $array["Bets"] = 0;
    $array["Prize"] = 0;

    foreach ($data->data as $key => $value) {
        if ($value->{".vlr_apuesta"} == "") {
            $value->{".vlr_apuesta"} = 0;
        }
        $array["Bets"] = $value->{".vlr_apuesta"};
    }


    /* Se definen reglas basadas en el perfil de sesión del usuario. */
    $rules = [];

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        /* Añade una regla al arreglo si la condición del 'else' se cumple. */

        array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    }

    /* Agrega reglas de filtrado con fecha de pago y convierte a JSON. */
    array_push($rules, array("field" => "it_ticket_enc.fecha_pago", "data" => date("Y-m-d"), "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();


    /* obtiene y procesa datos de premios, asignando cero a valores vacíos. */
    $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_premio) vlr_premio, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, 1, true);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {
        if ($value->{".vlr_premio"} == "") {
            $value->{".vlr_premio"} = 0;
        }
        $array["Prize"] = $value->{".vlr_premio"};
    }

    /* Se agrega una regla a un arreglo según la sesión y perfil del usuario. */
    array_push($final, $array);

    $rules = [];

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        /* Añade una regla al arreglo si la condición en el bloque anterior no se cumple. */


        array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }
    //array_push($rules, array("field" => "DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') ", "data" => date("Y-m-d"), "op" => "eq"));

    /* Se construye un filtro en formato JSON con reglas de tiempo. */
    array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Se inicializa una cuenta de cobro, se obtiene y decodifica data JSON. */
    $CuentaCobro = new CuentaCobro();

    $data = $CuentaCobro->getCuentasCobroCustom("  SUM(cuenta_cobro.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, true, true);

    $data = json_decode($data);

    $array = [];


    /* Inicializa un arreglo y actualiza el premio basado en los datos. */
    $array["Id"] = 0;
    $array["Product"] = "Doradobet Recargas - Pago Notas";
    $array["Bets"] = 0;
    $array["Prize"] = 0;

    foreach ($data->data as $key => $value) {
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }
        $array["Prize"] = $value->{".total"};
    }


    /* Se define una regla condicional para validar datos de "PUNTOVENTA". */
    $rules = [];

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        /* Añade una regla al array si la condición no se cumple. */

        array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }
    //array_push($rules, array("field" => "DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') ", "data" => date("Y-m-d"), "op" => "eq"));

    /* Se crean reglas de filtrado para un rango de timestamps en formato JSON. */
    array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(date("Y-m-d 00:00:00")), "op" => "ge"));
    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(date("Y-m-d 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Se crea un objeto, se obtiene datos y se ajusta el total a cero si es necesario. */
    $UsuarioRecarga = new UsuarioRecarga();

    $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }
        $array["Bets"] = $value->{".total"};
    }


    /* Añade el contenido de `$array` al final de `$final` en PHP. */
    array_push($final, $array);


    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {


        /* Se define un conjunto de reglas de validación basadas en datos de usuario y producto. */
        $rules = [];
        array_push($rules, array("field" => "ingreso.usuario_id", "data" => $Usuario->puntoventaId, "op" => "eq"));
        array_push($rules, array("field" => "producto_tercero.interno", "data" => "N", "op" => "eq"));
        array_push($rules, array("field" => "producto_tercero.tiene_cupo", "data" => "N", "op" => "eq"));
        // array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "ne"));
        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));


        /* Crea un filtro JSON y obtiene ingresos personalizados desde una base de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();

        $data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);


        /* Se decodifica JSON y se crean reglas para validar condiciones específicas. */
        $data = json_decode($data);

        $rules = [];
        array_push($rules, array("field" => "egreso.usuario_id", "data" => $Usuario->puntoventaId, "op" => "eq"));
        array_push($rules, array("field" => "producto_tercero.interno", "data" => "N", "op" => "eq"));
        array_push($rules, array("field" => "producto_tercero.tiene_cupo", "data" => "N", "op" => "eq"));
        // array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "ne"));

        /* Añade una regla de filtro para fechas y convierte a JSON en PHP. */
        array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Egreso = new Egreso();


        /* Se obtienen egresos personalizados y se decodifican en formato JSON. */
        $data2 = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data2 = json_decode($data2);


        foreach ($data->data as $key => $value) {

            /* Se crea un arreglo PHP con información de un producto y su ingreso asociado. */
            $array = [];


            $array["Id"] = 0;
            $array["Product"] = $value->{"producto_tercero.descripcion"};
            $array["Bets"] = $value->{"ingreso.valor"};

            /* asigna un premio basado en condiciones coincidentes entre dos conjuntos de datos. */
            $array["Prize"] = 0;

            foreach ($data2->data as $key2 => $value2) {

                if ($value->{"producto_tercero.productoterc_id"} == $value2->{"producto_tercero.productoterc_id"} && $value->{"egreso.usucajero_id"} == $value2->{"ingreso.usucajero_id"}) {
                    $array["Prize"] = $value2->{"egreso.valor"};

                }
                $value2->ingreso = true;
            }


            /* añade elementos a `$final` si "Bets" o "Prize" son diferentes de cero. */
            if ($array["Bets"] != 0 || $array["Prize"] != 0) {
                array_push($final, $array);
            }

        }


        /* Recorre datos, crea un array si 'ingreso' es falso, y lo agrega a 'final'. */
        foreach ($data2->data as $key2 => $value2) {

            if (!$value2->ingreso) {

                $array = [];


                $array["Id"] = 0;
                $array["Product"] = $value->{"producto_tercero.descripcion"};
                $array["Bets"] = 0;
                $array["Prize"] = $value2->{"egreso.valor"};


                if ($array["Bets"] != 0 || $array["Prize"] != 0) {
                    array_push($final, $array);
                }
            }

        }

    }

} else {

    /* Se crean instancias de la clase "Clasificador" para diferentes tipos de tickets y operaciones. */
    $TipoTickets = new Clasificador("", "ACCBETTICKET");
    $TipoPremios = new Clasificador("", "ACCWINTICKET");
    $TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
    $TipoRecargas = new Clasificador("", "ACCREC");

    $array = [];


    /* Se inicializa un array con datos de un producto y otro array vacío. */
    $array["Id"] = 0;
    $array["Product"] = "Doradobet Recargas - Pago Notas";
    $array["Bets"] = 0;
    $array["Prize"] = 0;

    $array2 = [];


    /* Se define un arreglo con información de un producto y un arreglo vacío para reglas. */
    $array2["Id"] = 0;
    $array2["Product"] = "Doradobet Tickets";
    $array2["Bets"] = 0;
    $array2["Prize"] = 0;

    $rules = [];


    /* Agrega reglas de filtrado para usuario y fecha en un array. */
    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $BetShopId, "op" => "eq"));


    if ($fechaEspecifica != '') {
        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

    }


    /* Se construye un filtro en JSON para validar ingresos según tipos de reclamos. */
    array_push($rules, array("field" => "ingreso.tipo_id", "data" => "'" . $TipoRecargas->getClasificadorId() . "','" . $TipoTickets->getClasificadorId() . "'", "op" => "in"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $Ingreso = new Ingreso();


    /* obtiene ingresos, los decodifica y clasifica según tipos específicos. */
    $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", 0, 10, $json, true);

    $data = json_decode($data);

    foreach ($data->data as $key => $value) {

        if ($TipoRecargas->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
            $array["Bets"] = $value->{"ingreso.valor"};
        }

        if ($TipoTickets->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
            $array2["Bets"] = $value->{"ingreso.valor"};
        }
    }


    /* crea reglas de filtro para consultar registros de egresos específicos. */
    $rules = [];

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $BetShopId, "op" => "eq"));


    if ($fechaEspecifica != '') {
        array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
        array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

    }


    /* Se define un filtro para reglas de egreso y se convierte a JSON. */
    array_push($rules, array("field" => "egreso.tipo_id", "data" => "'" . $TipoNotasRetiros->getClasificadorId() . "','" . $TipoPremios->getClasificadorId() . "'", "op" => "in"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $Egreso = new Egreso();


    /* obtiene y filtra datos de egresos según tipos específicos. */
    $data = $Egreso->getEgresosCustom("  egreso.* ", "egreso.egreso_id", "asc", 0, 10, $json, true);

    $data = json_decode($data);

    foreach ($data->data as $key => $value) {

        if ($TipoNotasRetiros->getClasificadorId() == $value->{"egreso.tipo_id"}) {
            $array["Prize"] = $value->{"egreso.valor"};
        }

        if ($TipoPremios->getClasificadorId() == $value->{"egreso.tipo_id"}) {
            $array2["Prize"] = $value->{"egreso.valor"};
        }
    }


    /* Agrega los elementos de $array y $array2 a $final. */
    array_push($final, $array);
    array_push($final, $array2);


}


/* Código que configura la respuesta de una operación, indicando éxito y errores vacíos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna datos a una respuesta estructurada en formato de array asociativo. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $clasificadores->count[0]->{".count"};
$response["data"] = $final;
