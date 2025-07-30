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
use Backend\imports\PHPExcel\PHPExcel;
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
 * Accounting/GetDetailsSquareDay
 *
 * Obtener los detalles de cierres de día
 *
 * @param int $Id : Contiene el Id para generar las SQL de cierre de caja
 * @param string $MaxRows : Información para generar las SQL contiene el limit de la consulta
 * @param string $OrderedItem : Información para generar las SQL contiene la columna para ordenar la consulta
 * @param string $SkeepRows : Información para generar las SQL contiene las filas omitidas
 *
 * @return object $response Objeto con los atributos de respuesta.
 *
 * Este objeto deja una respuesta de exito como la siguiente:
 *  $response["HasError"] = false;
 *  $response["AlertType"] = "success";
 *  $response["AlertMessage"] = "";
 *  $response["ModelErrors"] = [];
 *
 * Añade los detalles importantes detro del array data
 *  $response["Data"];
 *  $response["data"] = $final;
 *
 * Además de data especifica de la operación
 *  $response["pos"] = $SkeepRows;
 *  $response["total_count"] = $data->count[0]->{".count"};
 *  $response["Day"] = $fecha;
 *  $response["BetshopId"] = $usuarioId;
 *
 *
 * @throws no No contiene manejo de exepciones
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crean instancias de la clase Clasificador para diferentes tipos de tickets y premios. */
$TipoTickets = new Clasificador("", "ACCBETTICKET");
$TipoPremios = new Clasificador("", "ACCWINTICKET");
$TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
$TipoRecargas = new Clasificador("", "ACCREC");
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


$response["Data"] = array();

/* Código que inicializa variables y recibe parámetros de la solicitud HTTP. */
$total = 0;
$fecha = "";


$id = $_REQUEST["id"];

$FExport = $_REQUEST["FExport"];

/* obtiene parámetros de solicitud y crea un objeto UsuarioMandante. */
$Type = $_REQUEST["Type"];

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Id = $params->Id;
$usuarioId = 0;


/* asigna variables basadas en parámetros de solicitud y manejos de fila. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* asigna valores predeterminados si las variables están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y define reglas de filtrado. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$rules = [];

array_push($rules, array("field" => "usuario_cierrecaja.usucierrecaja_id", "data" => $id, "op" => "eq"));


/* Crea un filtro en formato JSON para obtener usuarios y cierrecajas personalizados. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.usucierrecaja_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Decodifica datos JSON y los almacena en un array vacío para procesamiento posterior. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un arreglo asociativo con información de usuario a partir de un objeto. */
    $array = [];


    $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
    $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};


    $array["UserName"] = $value->{"usuario.login"};

    /* asigna valores de un objeto a un array estructurado. */
    $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
    $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
    $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
    $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
    $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
    $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

    /* calcula el total de ingresos y gastos, incluyendo varios tipos de ingresos. */
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
    $array["Total"] = floatval($array["AmountBegin"]) + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];
    $dineroInicial = $array["AmountBegin"];

    /* Añade un array a $final y obtiene fecha y usuarioId de $array. */
    array_push($final, $array);

    $fecha = $array["Date"];
    $usuarioId = $value->{"usuario_cierrecaja.usuario_id"};


}

/* inicializa estructuras de datos para almacenar información financiera y tickets. */
$response["Data"]["SquareDay"] = $final;
$response["Data"]["Products"] = array();
$response["Data"]["Incomes"] = array();
$response["Data"]["Expenses"] = array();
$response["Data"]["Tickets"] = array();
$response["Data"]["Deposit"] = array();

/* Se inicializan arreglos y se crea un objeto de perfil de usuario. */
$response["Data"]["Withdraw"] = array();
$response["Data"]["IncomesCreditCards"] = array();

$UsuarioPerfil = new UsuarioPerfil($usuarioId);


$SkeepRows = 0;

/* Se definen variables para ordenar elementos y establecer reglas y agrupaciones. */
$OrderedItem = 1;
$MaxRows = 1000;


$rules = [];
$grouping = "";


/* Condicional que agrega reglas basadas en el perfil de usuario en un array. */
if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se configura un filtro para realizar consultas mediante condiciones en una base de datos. */
array_push($rules, array("field" => "DATE_FORMAT(ingreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();


/* obtiene ingresos personalizados y los convierte en un arreglo JSON decodificado. */
$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.*,cuenta_concepto.*,cuenta_producto.*,concepto.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un arreglo con datos extraídos y formateados de un objeto. */
    $array = [];


    $array["Id"] = $value->{"ingreso.ingreso_id"};
    $array["Description"] = $value->{"ingreso.descripcion"};
    $array["Date"] = date('Y-m-d H:i:s', strtotime($value->{"ingreso.fecha_crea"}));


    /* Condicional que asigna datos a un arreglo si la descripción del producto no está vacía. */
    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }
    $array["AccountConcept"] = $value->{"cuenta_concepto.referencia"};

    /* Asigna valores de un objeto a un arreglo asociativo en PHP. */
    $array["AccountConceptDescription"] = $value->{"cuenta_concepto.referencia"};
    $array["AccountProduct"] = $value->{"cuenta_producto.referencia"};
    $array["AccountProductDescription"] = $value->{"cuenta_producto.descripcion"};

    $array["Concept"] = $value->{"concepto.descripcion"};

    $array["Serie"] = $value->{"egreso.serie"};

    /* asigna un tipo de documento transformando su valor según condiciones específicas. */
    $array["TypeDocument"] = $value->{"egreso.tipo_documento"};

    switch ($array["TypeDocument"]) {
        case "195":
            $array["TypeDocument"] = '01';
            break;
        case "198":
            $array["TypeDocument"] = '02';
            break;
        case "201":
            $array["TypeDocument"] = '03';
            break;
        case "204":
            $array["TypeDocument"] = '14';
            break;
    }


    /* Asigna valores a un array y verifica si el estado es 'C'. */
    $array["Reference"] = $value->{"ingreso.documento"};
    $array["Amount"] = $value->{"ingreso.valor"};
    $array["IsCorrection"] = false;

    if ($value->{"ingreso.estado"} == 'C') {
        $array["IsCorrection"] = true;
    }


    if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {

        /* Suma el valor de "Amount" del arreglo a la variable total. */
        $total = $total + $array["Amount"];

        if ($value->{"producto_tercero.interno"} == "S") {


            /* Crea una instancia de la clase Clasificador utilizando el tipo de producto. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* Se agrega un ticket con descripción y cantidad ganadora al arreglo de respuesta. */

                    $array["Description"] = "Tickets";
                    $array["AmountWin"] = 0;
                    array_push($response["Data"]["Tickets"], $array);

                    break;

                case $TipoPremios->getClasificadorId():
                    /* muestra un caso en un switch basado en un identificador de clasificador. */


                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* utiliza un case para manejar diferentes clasificaciones de notas y retiros. */


                    break;

                case $TipoRecargas->getClasificadorId():
                    /* Asigna "Recargas" a Description y lo agrega a la respuesta. */

                    $array["Description"] = "Recargas";
                    array_push($response["Data"]["Deposit"], $array);

                    break;

            }
        } else {


            /* actualiza o agrega productos en un array según condiciones específicas. */
            $array["AmountWin"] = 0;

            if (!$array["IsCorrection"] || $FExport == 1) {


                $encontroProducto = false;
                foreach ($response["Data"]["Products"] as $key => $product) {

                    if ($product["ProductId"] == $array["ProductId"]) {
                        $response["Data"]["Products"][$key]["Amount"] = $response["Data"]["Products"][$key]["Amount"] + $array["Amount"];

                        $encontroProducto = true;

                    }

                }
                if (!$encontroProducto) {
                    array_push($response["Data"]["Products"], $array);
                }
            } else {
                /* Agrega un elemento al array "Products" si la condición anterior no se cumple. */

                array_push($response["Data"]["Products"], $array);
            }

        }

    } else {
        if ($value->{"ingreso.tipo_id"} != "0") {

            /* Se asigna un valor si el tipo de dinero coincide con un clasificador. */
            if ($TipoDineroInicial->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
                $dineroInicial = $value->{"ingreso.valor"};

            }


            $Tipo = new Clasificador($value->{"ingreso.tipo_id"});


            /* gestiona ingresos de tarjetas de crédito y los agrega a una respuesta. */
            switch ($Tipo->getTipo()) {
                case "TARJCRED":

                    $array["Description"] = "Tarjeta de Credito " . $value->{"producto_tercero.descripcion"};
                    $array["Amount"] = $array["Amount"];


                    array_push($response["Data"]["IncomesCreditCards"], $array);

                    $otrosIngresosTarjetasCreditos += $array["Amount"];
                    break;
            }

        } else {
            /* Agrega datos de ingresos a una respuesta y suma montos a una variable. */

            array_push($response["Data"]["Incomes"], $array);
            $otrosIngresos += $array["Amount"];
        }

    }


}

/* Acumula ingresos y establece reglas según el perfil del usuario en un punto de venta. */
$total = $total + $otrosIngresos;

$rules = [];
$grouping = "";


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    /* Agrega una regla al arreglo si no se cumple una condición previa. */

    array_push($rules, array("field" => "egreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se crean reglas de filtrado y se convierten a formato JSON para usarlas. */
array_push($rules, array("field" => "DATE_FORMAT(egreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();


/* obtiene y decodifica datos de egresos personalizados en formato JSON. */
$data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.*,cuenta_concepto.*,cuenta_producto.*,proveedor_tercero.*,documento.descripcion,cuenta_producto_egreso.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un array con datos de un objeto "egreso". */
    $array = [];


    $array["Id"] = $value->{"egreso.ingreso_id"};
    $array["Description"] = $value->{"egreso.descripcion"};
    $array["Date"] = date('Y-m-d H:i:s', strtotime($value->{"egreso.fecha_crea"}));


    /* asigna valores a un array basado en condiciones del producto. */
    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }
    $array["AccountConcept"] = $value->{"cuenta_concepto.referencia"};

    /* Asigna valores a un arreglo desde un objeto con referencias a propiedades específicas. */
    $array["AccountConceptDescription"] = $value->{"cuenta_concepto.referencia"};
    $array["AccountProduct"] = $value->{"cuenta_producto_egreso.referencia"};
    $array["AccountProductDescription"] = $value->{"cuenta_producto_egreso.descripcion"};
    $array["AccountProductWin"] = $value->{"cuenta_producto_egreso.referencia"};
    $array["AccountProductDescriptionWin"] = $value->{"cuenta_producto_egreso.descripcion"};

    $array["Serie"] = $value->{"egreso.serie"};

    /* Asigna valores de un objeto a un arreglo, limpiando algunos con expresiones regulares. */
    $array["TypeDocument"] = $value->{"egreso.tipo_documento"};
    $array["NameDocument"] = $value->{"documento.descripcion"};

    $array["ProviderName"] = preg_replace("/\r|\n/", "", $value->{"proveedor_tercero.descripcion"});
    $array["ProviderDocument"] = preg_replace("/\r|\n/", "", $value->{"proveedor_tercero.documento"});

    $array["Reference"] = $value->{"egreso.documento"};

    /* Se asignan valores a un arreglo basado en condiciones del objeto $value. */
    $array["Amount"] = $value->{"egreso.valor"};
    $array["IsCorrection"] = false;

    if ($value->{"egreso.estado"} == 'C') {
        $array["IsCorrection"] = true;
    }


    if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {


        /* Resta el valor de "Amount" en el arreglo del total acumulado. */
        $total = $total - $array["Amount"];

        if ($value->{"producto_tercero.interno"} == "S") {


            /* Código que crea un objeto 'Clasificador' utilizando el tipo de producto de un tercero. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* Es una estructura de control que evalúa el identificador de clasificador de tickets. */


                    break;

                case $TipoPremios->getClasificadorId():
                    /* asigna valores de un array a un objeto de respuesta basado en un clasificador. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];
                    $response["Data"]["Tickets"][0]["AccountProductWin"] = $array["AccountProduct"];
                    $response["Data"]["Tickets"][0]["AccountProductDescriptionWin"] = $array["AccountProductDescription"];

                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* asigna una descripción y almacena información de retiros en un array. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;

                case $TipoRecargas->getClasificadorId():
                    /* Fragmento de código que utiliza un switch-case para manejar diferentes tipos de recargas. */


                    break;
                case "ACCWINTICKET":
                    /* Asigna valores de un array a un ticket en la respuesta. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];
                    $response["Data"]["Tickets"][0]["AccountProductWin"] = $array["AccountProduct"];
                    $response["Data"]["Tickets"][0]["AccountProductDescriptionWin"] = $array["AccountProductDescription"];

                    break;
                case "ACCPAYWD":
                    /* asigna una descripción y agrega datos a un arreglo de respuestas. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;
            }
        } else {

            if (!$array["IsCorrection"] || $FExport == 1) {


                /* actualiza la información de productos si se encuentra coincidencia por ProductId. */
                $encontroProducto = false;
                foreach ($response["Data"]["Products"] as $key => $product) {

                    if ($product["ProductId"] == $array["ProductId"]) {
                        $response["Data"]["Products"][$key]["AmountWin"] = $response["Data"]["Products"][$key]["AmountWin"] + $array["Amount"];
                        $response["Data"]["Products"][$key]["AccountProductWin"] = $array["AccountProduct"];
                        $response["Data"]["Products"][$key]["AccountProductDescriptionWin"] = $array["AccountProductDescription"];

                        $encontroProducto = true;

                    }
                }

                /* verifica si no se encontró un producto y actualiza valores en un arreglo. */
                if (!$encontroProducto) {
                    $array["AmountWin"] = $array["Amount"];
                    $array["Amount"] = 0;


                    array_push($response["Data"]["Products"], $array);
                }
            } else {
                /* ajusta valores en un array y lo añade a una respuesta. */

                $array["AmountWin"] = $array["Amount"];
                $array["Amount"] = 0;

                array_push($response["Data"]["Products"], $array);
            }

        }


    } else {


        /* Condiciona el procesamiento de datos según el tipo de egreso especificado. */
        if ($value->{"egreso.tipo_id"} != "0") {
            $Tipo = new Clasificador($value->{"egreso.tipo_id"});

            switch ($Tipo->getAbreviado()) {
                case "ACCWINTICKET":
                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;
                case "ACCPAYWD":
                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;
            }

        } else {
            /* Agrega datos de gastos a la respuesta y acumula el total de egresos. */

            array_push($response["Data"]["Expenses"], $array);
            $otrosEgresos += $value->{"egreso.valor"};

        }


    }


}


if ($Type == 'Excel') {

    /* Código en PHP para crear un archivo Excel con propiedades y datos iniciales. */
    $objPHPExcel = new PHPExcel();

// Set document properties
    $objPHPExcel->getProperties()->setCreator("Virtualsoft")
        ->setLastModifiedBy("Virtualsoft");


// Add some data
    /* Establece los encabezados de columnas en una hoja de cálculo. */
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '')
        ->setCellValue('B1', 'Sub Diario')
        ->setCellValue('C1', 'Número de Comprobante')
        ->setCellValue('D1', 'Fecha de Comprobante')
        ->setCellValue('E1', 'Código de Moneda')
        ->setCellValue('F1', 'Glosa Principal')
        ->setCellValue('G1', 'Tipo de Cambio')
        ->setCellValue('H1', 'Tipo de Conversión')
        ->setCellValue('I1', 'Flag de Conversión de Moneda')
        ->setCellValue('J1', 'Fecha Tipo de Cambio')
        ->setCellValue('K1', 'Cuenta Contable')
        ->setCellValue('L1', 'Código de Anexo')
        ->setCellValue('M1', 'Código de Centro de Costo')
        ->setCellValue('N1', 'Debe / Haber')
        ->setCellValue('O1', 'Importe Original')
        ->setCellValue('P1', 'Importe en Dólares')
        ->setCellValue('Q1', 'Importe en Soles')
        ->setCellValue('R1', 'Tipo de Documento')
        ->setCellValue('S1', 'Número de Documento')
        ->setCellValue('T1', 'Fecha de Documento')
        ->setCellValue('U1', 'Fecha de Vencimiento')
        ->setCellValue('V1', 'Código de Area')
        ->setCellValue('W1', 'Glosa Detalle')
        ->setCellValue('X1', 'Código de Anexo Auxiliar')
        ->setCellValue('Y1', 'Medio de Pago')
        ->setCellValue('Z1', 'Tipo de Documento de Referencia')
        ->setCellValue('AA1', 'Número de Documento Referencia')
        ->setCellValue('AB1', 'Fecha Documento Referencia')
        ->setCellValue('AC1', 'Nro Máq. Registradora Tipo Doc. Ref.')
        ->setCellValue('AD1', 'Base Imponible Documento Referencia')
        ->setCellValue('AE1', 'IGV Documento Provisión')
        ->setCellValue('AF1', 'Tipo Referencia en estado MQ')
        ->setCellValue('AG1', 'Número Serie Caja Registradora')
        ->setCellValue('AH1', 'Fecha de Operación')
        ->setCellValue('AI1', 'Tipo de Tasa')
        ->setCellValue('AJ1', 'Tasa Detracción/Percepción')
        ->setCellValue('AK1', 'Importe Base Detracción/Percepción Dólares')
        ->setCellValue('AL1', 'Importe Base Detracción/Percepción Soles')
        ->setCellValue('AM1', 'Tipo Cambio para F')
        ->setCellValue('AN1', 'Importe de IGV sin derecho crédito fiscal');


// Add some data
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A2', '')
        ->setCellValue('B2', '')
        ->setCellValue('C2', '')
        ->setCellValue('D2', '')
        ->setCellValue('E2', '')
        ->setCellValue('F2', '')
        ->setCellValue('G2', '')
        ->setCellValue('H2', '')
        ->setCellValue('I2', '')
        ->setCellValue('J2', '')
        ->setCellValue('K2', '')
        ->setCellValue('L2', '')
        ->setCellValue('M2', '')
        ->setCellValue('N2', '')
        ->setCellValue('O2', '')
        ->setCellValue('P2', '')
        ->setCellValue('Q2', '')
        ->setCellValue('R2', '')
        ->setCellValue('S2', '')
        ->setCellValue('T2', '')
        ->setCellValue('U2', '')
        ->setCellValue('V2', '')
        ->setCellValue('W2', '')
        ->setCellValue('X2', '')
        ->setCellValue('Y2', '')
        ->setCellValue('Z2', '')
        ->setCellValue('AA2', '')
        ->setCellValue('AB2', '')
        ->setCellValue('AC2', '')
        ->setCellValue('AD2', '')
        ->setCellValue('AE2', '')
        ->setCellValue('AF2', '')
        ->setCellValue('AG2', '')
        ->setCellValue('AH2', '')
        ->setCellValue('AI2', '')
        ->setCellValue('AJ2', '')
        ->setCellValue('AK2', '')
        ->setCellValue('AL2', '')
        ->setCellValue('AM2', '')
        ->setCellValue('AN2', '');


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A3', '')
        ->setCellValue('B3', '')
        ->setCellValue('C3', '')
        ->setCellValue('D3', '')
        ->setCellValue('E3', '')
        ->setCellValue('F3', '')
        ->setCellValue('G3', '')
        ->setCellValue('H3', '')
        ->setCellValue('I3', '')
        ->setCellValue('J3', '')
        ->setCellValue('K3', '')
        ->setCellValue('L3', '')
        ->setCellValue('M3', '')
        ->setCellValue('N3', '')
        ->setCellValue('O3', '')
        ->setCellValue('P3', '')
        ->setCellValue('Q3', '')
        ->setCellValue('R3', '')
        ->setCellValue('S3', '')
        ->setCellValue('T3', '')
        ->setCellValue('U3', '')
        ->setCellValue('V3', '')
        ->setCellValue('W3', '')
        ->setCellValue('X3', '')
        ->setCellValue('Y3', '')
        ->setCellValue('Z3', '')
        ->setCellValue('AA3', '')
        ->setCellValue('AB3', '')
        ->setCellValue('AC3', '')
        ->setCellValue('AD3', '')
        ->setCellValue('AE3', '')
        ->setCellValue('AF3', '')
        ->setCellValue('AG3', '')
        ->setCellValue('AH3', '')
        ->setCellValue('AI3', '')
        ->setCellValue('AJ3', '')
        ->setCellValue('AK3', '')
        ->setCellValue('AL3', '')
        ->setCellValue('AM3', '')
        ->setCellValue('AN3', '');

    $cont = 4;

    /* Se crea un usuario y un punto de venta, luego se obtiene un código personalizado. */
    $Usuario = new Usuario($usuarioId);
    $PuntoVenta = new PuntoVenta("", $usuarioId);

    $CodigoAnexo = $PuntoVenta->getCodigoPersonalizado();
    $SumaParaCancelar = 0;

    foreach ($response["Data"]["Tickets"] as $item) {

        /* establece valores en celdas de una hoja de Excel usando PHP. */
        $SubDiario = '07';

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, '')
            ->setCellValue('B' . $cont, $SubDiario)
            ->setCellValue('C' . $cont, '')
            ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('E' . $cont, 'MN')
            ->setCellValue('F' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('G' . $cont, '')
            ->setCellValue('H' . $cont, 'V')
            ->setCellValue('I' . $cont, 'S')
            ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('K' . $cont, $item["AccountProduct"])
            ->setCellValue('L' . $cont, $CodigoAnexo)
            ->setCellValue('M' . $cont, '')
            ->setCellValue('N' . $cont, 'H')
            ->setCellValue('O' . $cont, $item["Amount"])
            ->setCellValue('P' . $cont, '')
            ->setCellValue('Q' . $cont, '')
            ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
            ->setCellValue('S' . $cont, 'VENTAS ' . date('m-Y', strtotime(($item["Date"]))))
            ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('V' . $cont, '')
            ->setCellValue('W' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('X' . $cont, '')
            ->setCellValue('Y' . $cont, '')
            ->setCellValue('Z' . $cont, '')
            ->setCellValue('AA' . $cont, '')
            ->setCellValue('AB' . $cont, '')
            ->setCellValue('AC' . $cont, '')
            ->setCellValue('AD' . $cont, '')
            ->setCellValue('AE' . $cont, '')
            ->setCellValue('AF' . $cont, '')
            ->setCellValue('AG' . $cont, '')
            ->setCellValue('AH' . $cont, '')
            ->setCellValue('AI' . $cont, '')
            ->setCellValue('AJ' . $cont, '')
            ->setCellValue('AK' . $cont, '')
            ->setCellValue('AL' . $cont, '')
            ->setCellValue('AM' . $cont, '')
            ->setCellValue('AN' . $cont, '');

        $SumaParaCancelar -= floatval($item["Amount"]);

        $cont++;

        $SubDiario = '07';

        /* establece valores en celdas de una hoja de cálculo con PHPExcel. */
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, '')
            ->setCellValue('B' . $cont, $SubDiario)
            ->setCellValue('C' . $cont, '')
            ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('E' . $cont, 'MN')
            ->setCellValue('F' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('G' . $cont, '')
            ->setCellValue('H' . $cont, 'V')
            ->setCellValue('I' . $cont, 'S')
            ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('K' . $cont, $item["AccountProductWin"])
            ->setCellValue('L' . $cont, $CodigoAnexo)
            ->setCellValue('M' . $cont, '')
            ->setCellValue('N' . $cont, 'D')
            ->setCellValue('O' . $cont, $item["AmountWin"])
            ->setCellValue('P' . $cont, '')
            ->setCellValue('Q' . $cont, '')
            ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
            ->setCellValue('S' . $cont, 'VENTAS ' . date('m-Y', strtotime(($item["Date"]))))
            ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('V' . $cont, '')
            ->setCellValue('W' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('X' . $cont, '')
            ->setCellValue('Y' . $cont, '')
            ->setCellValue('Z' . $cont, '')
            ->setCellValue('AA' . $cont, '')
            ->setCellValue('AB' . $cont, '')
            ->setCellValue('AC' . $cont, '')
            ->setCellValue('AD' . $cont, '')
            ->setCellValue('AE' . $cont, '')
            ->setCellValue('AF' . $cont, '')
            ->setCellValue('AG' . $cont, '')
            ->setCellValue('AH' . $cont, '')
            ->setCellValue('AI' . $cont, '')
            ->setCellValue('AJ' . $cont, '')
            ->setCellValue('AK' . $cont, '')
            ->setCellValue('AL' . $cont, '')
            ->setCellValue('AM' . $cont, '')
            ->setCellValue('AN' . $cont, '');

        $SumaParaCancelar += floatval($item["AmountWin"]);

        $cont++;


    }


    foreach ($response["Data"]["Products"] as $item) {


        /* Código que establece valores en celdas de Excel usando PHP. */
        $SubDiario = '07';
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, '')
            ->setCellValue('B' . $cont, $SubDiario)
            ->setCellValue('C' . $cont, '')
            ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('E' . $cont, 'MN')
            ->setCellValue('F' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('G' . $cont, '')
            ->setCellValue('H' . $cont, 'V')
            ->setCellValue('I' . $cont, 'S')
            ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('K' . $cont, $item["AccountProduct"])
            ->setCellValue('L' . $cont, $CodigoAnexo)
            ->setCellValue('M' . $cont, '')
            ->setCellValue('N' . $cont, 'H')
            ->setCellValue('O' . $cont, $item["Amount"])
            ->setCellValue('P' . $cont, '')
            ->setCellValue('Q' . $cont, '')
            ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
            ->setCellValue('S' . $cont, 'VENTAS ' . date('m-Y', strtotime(($item["Date"]))))
            ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('V' . $cont, '')
            ->setCellValue('W' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('X' . $cont, '')
            ->setCellValue('Y' . $cont, '')
            ->setCellValue('Z' . $cont, '')
            ->setCellValue('AA' . $cont, '')
            ->setCellValue('AB' . $cont, '')
            ->setCellValue('AC' . $cont, '')
            ->setCellValue('AD' . $cont, '')
            ->setCellValue('AE' . $cont, '')
            ->setCellValue('AF' . $cont, '')
            ->setCellValue('AG' . $cont, '')
            ->setCellValue('AH' . $cont, '')
            ->setCellValue('AI' . $cont, '')
            ->setCellValue('AJ' . $cont, '')
            ->setCellValue('AK' . $cont, '')
            ->setCellValue('AL' . $cont, '')
            ->setCellValue('AM' . $cont, '')
            ->setCellValue('AN' . $cont, '');


        /* Código que actualiza una suma y configura una hoja de Excel. */
        $SumaParaCancelar -= floatval($item["Amount"]);

        $cont++;

        $SubDiario = '07';

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, '')
            ->setCellValue('B' . $cont, $SubDiario)
            ->setCellValue('C' . $cont, '')
            ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('E' . $cont, 'MN')
            ->setCellValue('F' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('G' . $cont, '')
            ->setCellValue('H' . $cont, 'V')
            ->setCellValue('I' . $cont, 'S')
            ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('K' . $cont, $item["AccountProductWin"])
            ->setCellValue('L' . $cont, $CodigoAnexo)
            ->setCellValue('M' . $cont, '')
            ->setCellValue('N' . $cont, 'D')
            ->setCellValue('O' . $cont, $item["AmountWin"])
            ->setCellValue('P' . $cont, '')
            ->setCellValue('Q' . $cont, '')
            ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
            ->setCellValue('S' . $cont, 'VENTAS ' . date('m-Y', strtotime(($item["Date"]))))
            ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('V' . $cont, '')
            ->setCellValue('W' . $cont, 'VENTA ' . $Usuario->nombre)
            ->setCellValue('X' . $cont, '')
            ->setCellValue('Y' . $cont, '')
            ->setCellValue('Z' . $cont, '')
            ->setCellValue('AA' . $cont, '')
            ->setCellValue('AB' . $cont, '')
            ->setCellValue('AC' . $cont, '')
            ->setCellValue('AD' . $cont, '')
            ->setCellValue('AE' . $cont, '')
            ->setCellValue('AF' . $cont, '')
            ->setCellValue('AG' . $cont, '')
            ->setCellValue('AH' . $cont, '')
            ->setCellValue('AI' . $cont, '')
            ->setCellValue('AJ' . $cont, '')
            ->setCellValue('AK' . $cont, '')
            ->setCellValue('AL' . $cont, '')
            ->setCellValue('AM' . $cont, '')
            ->setCellValue('AN' . $cont, '');

        $SumaParaCancelar += floatval($item["AmountWin"]);

        $cont++;


    }


    /* establece una variable que cambia según el valor de otra. */
    $HoD = 'H';

    if ($SumaParaCancelar < 0) {
        $HoD = 'D';
        $SumaParaCancelar = -$SumaParaCancelar;
    }


    /* establece valores en una hoja de Excel usando PHPExcel. */
    $SubDiario = '07';

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $cont, '')
        ->setCellValue('B' . $cont, $SubDiario)
        ->setCellValue('C' . $cont, '')
        ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('E' . $cont, 'MN')
        ->setCellValue('F' . $cont, 'VENTA ' . $Usuario->nombre)
        ->setCellValue('G' . $cont, '')
        ->setCellValue('H' . $cont, 'V')
        ->setCellValue('I' . $cont, 'S')
        ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('K' . $cont, '121203')
        ->setCellValue('L' . $cont, $CodigoAnexo)
        ->setCellValue('M' . $cont, '')
        ->setCellValue('N' . $cont, $HoD)
        ->setCellValue('O' . $cont, $SumaParaCancelar)
        ->setCellValue('P' . $cont, '')
        ->setCellValue('Q' . $cont, '')
        ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
        ->setCellValue('S' . $cont, 'VENTAS ' . date('m-Y', strtotime(($item["Date"]))))
        ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('V' . $cont, '')
        ->setCellValue('W' . $cont, 'VENTA ' . $Usuario->nombre)
        ->setCellValue('X' . $cont, '')
        ->setCellValue('Y' . $cont, '')
        ->setCellValue('Z' . $cont, '')
        ->setCellValue('AA' . $cont, '')
        ->setCellValue('AB' . $cont, '')
        ->setCellValue('AC' . $cont, '')
        ->setCellValue('AD' . $cont, '')
        ->setCellValue('AE' . $cont, '')
        ->setCellValue('AF' . $cont, '')
        ->setCellValue('AG' . $cont, '')
        ->setCellValue('AH' . $cont, '')
        ->setCellValue('AI' . $cont, '')
        ->setCellValue('AJ' . $cont, '')
        ->setCellValue('AK' . $cont, '')
        ->setCellValue('AL' . $cont, '')
        ->setCellValue('AM' . $cont, '')
        ->setCellValue('AN' . $cont, '');


    if ($HoD == 'H') {
        $HoD = 'D';
    } else {
        /* Asignación de la variable $HoD con el valor 'H' si no se cumple la condición. */

        $HoD = 'H';
    }

    $cont++;


    /* establece valores en celdas de una hoja de Excel usando PHP. */
    $SubDiario = '01';

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $cont, '')
        ->setCellValue('B' . $cont, $SubDiario)
        ->setCellValue('C' . $cont, '')
        ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('E' . $cont, 'MN')
        ->setCellValue('F' . $cont, 'CIERRE ' . $Usuario->nombre)
        ->setCellValue('G' . $cont, '')
        ->setCellValue('H' . $cont, 'V')
        ->setCellValue('I' . $cont, 'S')
        ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('K' . $cont, '121203')
        ->setCellValue('L' . $cont, $CodigoAnexo)
        ->setCellValue('M' . $cont, '')
        ->setCellValue('N' . $cont, $HoD)
        ->setCellValue('O' . $cont, $SumaParaCancelar)
        ->setCellValue('P' . $cont, '')
        ->setCellValue('Q' . $cont, '')
        ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
        ->setCellValue('S' . $cont, date('dmY', strtotime(($item["Date"]))))
        ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('V' . $cont, '')
        ->setCellValue('W' . $cont, 'CIERRE ' . $Usuario->nombre)
        ->setCellValue('X' . $cont, '')
        ->setCellValue('Y' . $cont, '')
        ->setCellValue('Z' . $cont, '')
        ->setCellValue('AA' . $cont, '')
        ->setCellValue('AB' . $cont, '')
        ->setCellValue('AC' . $cont, '')
        ->setCellValue('AD' . $cont, '')
        ->setCellValue('AE' . $cont, '')
        ->setCellValue('AF' . $cont, '')
        ->setCellValue('AG' . $cont, '')
        ->setCellValue('AH' . $cont, '')
        ->setCellValue('AI' . $cont, '')
        ->setCellValue('AJ' . $cont, '')
        ->setCellValue('AK' . $cont, '')
        ->setCellValue('AL' . $cont, '')
        ->setCellValue('AM' . $cont, '')
        ->setCellValue('AN' . $cont, '');


    $cont++;

    if ($HoD == "H") {
        $SumaParaCancelar = -$SumaParaCancelar;
    }

    foreach ($response["Data"]["Deposit"] as $item) {


        /* asigna valores a celdas en una hoja de cálculo de Excel. */
        $SubDiario = '01';
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, '')
            ->setCellValue('B' . $cont, $SubDiario)
            ->setCellValue('C' . $cont, '')
            ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('E' . $cont, 'MN')
            ->setCellValue('F' . $cont, 'CIERRE ' . $Usuario->nombre)
            ->setCellValue('G' . $cont, '')
            ->setCellValue('H' . $cont, 'V')
            ->setCellValue('I' . $cont, 'S')
            ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('K' . $cont, $item["AccountProduct"])
            ->setCellValue('L' . $cont, '')
            ->setCellValue('M' . $cont, '')
            ->setCellValue('N' . $cont, 'H')
            ->setCellValue('O' . $cont, $item["Amount"])
            ->setCellValue('P' . $cont, '')
            ->setCellValue('Q' . $cont, '')
            ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
            ->setCellValue('S' . $cont, date('dmY', strtotime(($item["Date"]))))
            ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('V' . $cont, '')
            ->setCellValue('W' . $cont, 'CIERRE ' . $Usuario->nombre)
            ->setCellValue('X' . $cont, '')
            ->setCellValue('Y' . $cont, '')
            ->setCellValue('Z' . $cont, '')
            ->setCellValue('AA' . $cont, '')
            ->setCellValue('AB' . $cont, '')
            ->setCellValue('AC' . $cont, '')
            ->setCellValue('AD' . $cont, '')
            ->setCellValue('AE' . $cont, '')
            ->setCellValue('AF' . $cont, '')
            ->setCellValue('AG' . $cont, '')
            ->setCellValue('AH' . $cont, '')
            ->setCellValue('AI' . $cont, '')
            ->setCellValue('AJ' . $cont, '')
            ->setCellValue('AK' . $cont, '')
            ->setCellValue('AL' . $cont, '')
            ->setCellValue('AM' . $cont, '')
            ->setCellValue('AN' . $cont, '');


        /* reduce $SumaParaCancelar restando el valor numérico de $item["Amount"]. */
        $SumaParaCancelar -= floatval($item["Amount"]);

        $cont++;


    }


    foreach ($response["Data"]["Withdraw"] as $item) {


        /* establece valores en celdas de una hoja de cálculo Excel usando PHP. */
        $SubDiario = '01';
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, '')
            ->setCellValue('B' . $cont, $SubDiario)
            ->setCellValue('C' . $cont, '')
            ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('E' . $cont, 'MN')
            ->setCellValue('F' . $cont, 'CIERRE ' . $Usuario->nombre)
            ->setCellValue('G' . $cont, '')
            ->setCellValue('H' . $cont, 'V')
            ->setCellValue('I' . $cont, 'S')
            ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('K' . $cont, $item["AccountProduct"])
            ->setCellValue('L' . $cont, '')
            ->setCellValue('M' . $cont, '')
            ->setCellValue('N' . $cont, 'D')
            ->setCellValue('O' . $cont, $item["Amount"])
            ->setCellValue('P' . $cont, '')
            ->setCellValue('Q' . $cont, '')
            ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
            ->setCellValue('S' . $cont, date('dmY', strtotime(($item["Date"]))))
            ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
            ->setCellValue('V' . $cont, '')
            ->setCellValue('W' . $cont, 'CIERRE ' . $Usuario->nombre)
            ->setCellValue('X' . $cont, '')
            ->setCellValue('Y' . $cont, '')
            ->setCellValue('Z' . $cont, '')
            ->setCellValue('AA' . $cont, '')
            ->setCellValue('AB' . $cont, '')
            ->setCellValue('AC' . $cont, '')
            ->setCellValue('AD' . $cont, '')
            ->setCellValue('AE' . $cont, '')
            ->setCellValue('AF' . $cont, '')
            ->setCellValue('AG' . $cont, '')
            ->setCellValue('AH' . $cont, '')
            ->setCellValue('AI' . $cont, '')
            ->setCellValue('AJ' . $cont, '')
            ->setCellValue('AK' . $cont, '')
            ->setCellValue('AL' . $cont, '')
            ->setCellValue('AM' . $cont, '')
            ->setCellValue('AN' . $cont, '');


        /* Acumula el valor de "Amount" como un número decimal en $SumaParaCancelar. */
        $SumaParaCancelar += floatval($item["Amount"]);

        $cont++;


    }


    /* Asignación de 'H' o 'D' según el valor de $SumaParaCancelar. */
    $HoD = 'H';

    if ($SumaParaCancelar < 0) {
        $HoD = 'D';
        $SumaParaCancelar = -$SumaParaCancelar;
    }


    /* Se inicializa un valor y se obtiene una referencia de cuenta contable si está definida. */
    $SubDiario = '01';

    $cuentaContablePV = 0;

    if ($PuntoVenta->cuentacontableId != '') {
        $CuentaContable = new CuentaContable($PuntoVenta->cuentacontableId);
        $cuentaContablePV = $CuentaContable->getReferencia();
    }


    /* establece valores en celdas específicas de una hoja de Excel. */
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $cont, '')
        ->setCellValue('B' . $cont, $SubDiario)
        ->setCellValue('C' . $cont, '')
        ->setCellValue('D' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('E' . $cont, 'MN')
        ->setCellValue('F' . $cont, 'CIERRE ' . $Usuario->nombre)
        ->setCellValue('G' . $cont, '')
        ->setCellValue('H' . $cont, 'V')
        ->setCellValue('I' . $cont, 'S')
        ->setCellValue('J' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('K' . $cont, $cuentaContablePV)
        ->setCellValue('L' . $cont, '')
        ->setCellValue('M' . $cont, '')
        ->setCellValue('N' . $cont, $HoD)
        ->setCellValue('O' . $cont, $SumaParaCancelar)
        ->setCellValue('P' . $cont, '')
        ->setCellValue('Q' . $cont, '')
        ->setCellValue('R' . $cont, ($SubDiario == '07' ? 'VR' : 'CD'))
        ->setCellValue('S' . $cont, date('dmY', strtotime(($item["Date"]))))
        ->setCellValue('T' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('U' . $cont, date('d/m/Y', strtotime(($item["Date"]))))
        ->setCellValue('V' . $cont, '003')
        ->setCellValue('W' . $cont, 'CIERRE ' . $Usuario->nombre)
        ->setCellValue('X' . $cont, '')
        ->setCellValue('Y' . $cont, '')
        ->setCellValue('Z' . $cont, '')
        ->setCellValue('AA' . $cont, '')
        ->setCellValue('AB' . $cont, '')
        ->setCellValue('AC' . $cont, '')
        ->setCellValue('AD' . $cont, '')
        ->setCellValue('AE' . $cont, '')
        ->setCellValue('AF' . $cont, '')
        ->setCellValue('AG' . $cont, '')
        ->setCellValue('AH' . $cont, '')
        ->setCellValue('AI' . $cont, '')
        ->setCellValue('AJ' . $cont, '')
        ->setCellValue('AK' . $cont, '')
        ->setCellValue('AL' . $cont, '')
        ->setCellValue('AM' . $cont, '')
        ->setCellValue('AN' . $cont, '');


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Export');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet

    /* Código para generar y descargar un archivo Excel en formato .xlsx. */
    $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="01simple.xlsx"');

    /* controla la caché para asegurar contenido actualizado en navegadores. */
    header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

    /* Genera un archivo Excel 2007 y establece encabezados para la caché y modificación. */
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    $filename = __DIR__ . '/' . time() . '.xls';


    /* guarda un archivo y lo convierte a formato base64. */
    $objWriter->save($filename);


    $type = pathinfo($filename, PATHINFO_EXTENSION);
    $data22 = file_get_contents($filename);
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data22);


    /* codifica datos en Base64 y prepara una respuesta para un archivo Excel. */
    $response["base64data"] = base64_encode($data22);
    $response["contentType"] = 'application/vnd.ms-excel';
    $response["name"] = 'report';

    unlink($filename);


}


/* calcula un total ajustando ingresos y egresos, y actualiza la respuesta. */
$total = $total - $otrosEgresos - $otrosIngresosTarjetasCreditos;

$total = $total + $dineroInicial;

$response["Data"]["Total"] = $total;


$response["HasError"] = false;

/* asigna valores a un arreglo de respuesta en PHP. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};

/* Se asignan datos a un arreglo de respuesta en PHP. */
$response["data"] = $final;
$response["Day"] = $fecha;
$response["BetshopId"] = $usuarioId;
