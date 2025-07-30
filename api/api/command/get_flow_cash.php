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
 * Obtener el flujo de caja
 *
 * @param no
 *
 * @return array Respuesta en formato JSON:
 * - HasError (boolean) Indica si hubo un error.
 * - AlertType (string) Tipo de alerta.
 * - AlertMessage (string) Mensaje de alerta.
 * - url (string) URL de redirección.
 * - success (string) Mensaje de éxito.
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/*Obtención información del usuario*/
$ConfigurationEnvironment = new ConfigurationEnvironment();

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$UsuarioMandante = new UsuarioMandante(1);
$Mandante = new Mandante($UsuarioMandante->getMandante());

$UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);

$_SESSION["win_perfil2"] =$UsuarioPerfil->perfilId;
$_SESSION['usuario'] = $UsuarioMandante->usuarioMandante;

$PuntoVenta = new PuntoVenta();

$params = $json->params;



/*Generación filtros para posterior consulta*/
$ToDateLocal = $params->endDate;

if($ToDateLocal != "" && $ToDateLocal != "null"){
    $ToDateLocal = date("Y-m-d", $ToDateLocal);

}

$FromDateLocal = $params->startDate;

if($FromDateLocal != "" && $FromDateLocal != "null") {
    // Convierte $FromDateLocal a formato de fecha "Y-m-d"
    $FromDateLocal = date("Y-m-d", $FromDateLocal);
}

// Convierte los valores de count y start de $params a minúsculas
$MaxRows = strtolower($params->count);
$SkeepRows = strtolower($params->start);

// Asigna el valor de OrderedItem de $params
$OrderedItem = $params->OrderedItem;

// Asigna el valor de ExternalId de $params
$ExternalId = $params->ExternalId;

// Determina el valor de TypeDetail basado en la solicitud
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;
$TypeTotal = ($_REQUEST["Type"] == "1") ? 0 : 1;

$seguir = true;

// Verifica si SkeepRows y MaxRows son numéricos
if (!is_numeric($SkeepRows)|| !is_numeric($MaxRows) ) {

    $seguir = false;

}

if ($seguir) {
    // Verifica si la variable $FromDateLocal está vacía y, si es así, la establece en la fecha actual.
    if ($FromDateLocal == "") {


        $FromDateLocal = date("Y-m-d", (time()));

    }
    if ($ToDateLocal == "") {

        $ToDateLocal = date("Y-m-d", (time()));


    }

    // Inicializa un array para las reglas.
    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";  // Inicializa la variable $grouping.
    $select = ""; // Inicializa la variable $select.
    if ($IsDetails) {

    } else {
        // Si $IsDetails es falso, determina el valor de $grouping basado en $TypeTotal.
        if ($TypeTotal == 0) {
            $grouping = 0;

        } else {
            $grouping = 1;

        }

        // Establece el valor de $select para realizar operaciones de suma en los campos específicos.
        $select = "SUM(usuario_recarga.valor) valoru,SUM(transaccion_producto.valor) valor, ";
        //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "'A',''", "op" => "in"));

    }

    // Verifica si $SkeepRows está vacío y, si es así, lo establece en 0.
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    // Verifica si $OrderedItem está vacío y, si es así, lo establece en 1.
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }
    $MaxRows = 1000000;

    // Se determina el tipo de usuario en la sesión para ejecutar la consulta correspondiente
    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        $transacciones = $PuntoVenta->getFlujoCajaConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CAJERO") {

        $transacciones = $PuntoVenta->getFlujoCajaConCajero($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario']);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO") {


        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, $_SESSION['usuario'], "", "", "", "", $BetShopId);

    } elseif ($_SESSION["win_perfil2"] == "CONCESIONARIO2") {


        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "x.fecha_crea", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", $_SESSION['usuario'], "", "", "", $BetShopId);

    } else {

        $Pais = "";

        // Verificar si se ha seleccionado un país y no es un valor vacío o cero
        if ($CountrySelect != "" && $CountrySelect != "0") {
            $Pais = $CountrySelect;
        }

        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            $Pais = $_SESSION['pais_id'];
        }

        $Mandante = "";
        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Mandante = $_SESSION["mandante"];
        }else {

            if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
                $Mandante= $_SESSION["mandanteLista"];
            }

        }


        // Inactivamos reportes para el país Colombia
        array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

        // Obtener transacciones del flujo de caja con los parámetros proporcionados
        $transacciones = $PuntoVenta->getFlujoCaja($select . " transaccion_producto.*,producto.*,usuario.moneda,usuario_recarga.* ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping, $FromDateLocal, $ToDateLocal, "", "", "", $Pais, $Mandante, $BetShopId);

    }

    // Decodificar el resultado de transacciones desde formato JSON
    $transacciones = json_decode($transacciones);

    $final = []; // Inicializar un array vacío para almacenar el resultado final
    $totalm = 0; // Inicializar el total en cero
    foreach ($transacciones->data as $key => $value) {
        $array = [];
        $array["Punto"] = $value->{"d.puntoventa"};


        $saldo = 0;

        $array["date"] = $value->{"x.fecha_crea"};
        $array["hour"] = $value->{"x.hora_crea"};
        $array["ticketId"] = $value->{".ticket_id"};
        $array["paymentMethod"] = $value->{".forma_pago1"};
        $array["paymentBonustc"] = 0;
        $array["cashInputValue"] = $value->{".valor_entrada_efectivo"};
        $array["valueInputsBonustc"] = $value->{".valor_entrada_bono"};
        $array["valueInputsTransfers"] = $value->{".valor_entrada_traslado"};
        $array["valueInputsRecharge"] = $value->{".valor_entrada_recarga"};
        $array["valueOutputsCash"] = $value->{".valor_salida_efectivo"};
        $array["valueOutputsTransfers"] = $value->{".valor_salida_traslado"};
        $array["valueOutputsWithdrawalNotes"] = $value->{".valor_salida_notaret"};

        $saldo = floatval($array["cashInputValue"]) + floatval($array["valueInputsBonustc"]) + floatval($array["valueInputsRecharge"]) + floatval($array["valueInputsTransfers"]) - floatval($array["valueOutputsCash"]) - floatval($array["valueOutputsTransfers"]) - floatval($array["valueOutputsWithdrawalNotes"]);
        $array["balance"] = $saldo;
        $array["currency"] = $value->{"d.moneda"};


        array_push($final, $array);
    }



    // $response["HasError"] = false;
    // $response["AlertType"] = "success";
    // $response["AlertMessage"] = "";
    // $response["ModelErrors"] = [];

    // $response["Data"] = array("Documents" => array("Objects" => $final,"Count" => $transacciones->count[0]->{".count"}),"TotalAmount" => $totalm,);

    //Generación de formato a respuesta
    $response["code"] = 0;
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $transacciones->count[0]->{".count"};
    $response["data"] = $final;
} else {
    //Generación de formato a respuesta vacía
    $response["code"] = 0;
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = [];

}