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
 * Accounting/GetDetailsSquareDay
 *
 * Obtener los detalles de cierres de día
 *
 * @param int $Id :  Identificador del cierre de caja.
 * @param int $MaxRows : Número máximo de filas a devolver.
 * @param int $OrderedItem : Ítem ordenado.
 * @param int $SkeepRows : Número de filas a omitir en la consulta.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors*  (array): retorna array vacio
 *  - *pos* (int): Posición de inicio de los datos devueltos.
 *  - *total_count* (int): Número total de registros que cumplen con los criterios de búsqueda.
 *  - *Data* (array): Datos del cierre de caja.
 *  - *Day* (String): Devuelve la fecha de la consulta
 *  - *BetshopId* (int): Devuelve el id del usuario linkeado al cierre de caja
 *
 * @throws no No contiene manejo de exepciones
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crean objetos de clase Clasificador con diferentes parámetros para clasificar tickets y transacciones. */
$TipoTickets = new Clasificador("", "ACCBETTICKET");
$TipoPremios = new Clasificador("", "ACCWINTICKET");
$TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
$TipoRecargas = new Clasificador("", "ACCREC");
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


$response["Data"] = array();

/* inicializa variables y obtiene datos de una solicitud HTTP. */
$total = 0;
$fecha = "";


$id = $_REQUEST["id"];

$FExport = $_REQUEST["FExport"];


/* Código que inicializa un objeto UsuarioMandante y define variables para manejo de usuarios. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Id = $params->Id;
$usuarioId = 0;

$MaxRows = $params->MaxRows;

/* maneja parámetros de paginación para la consulta de datos. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crean reglas de filtrado para consultas, agrupándolas con una operación lógica AND. */
$rules = [];

array_push($rules, array("field" => "usuario_cierrecaja.usucierrecaja_id", "data" => $id, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte datos a JSON, obtiene usuarios y los decodifica nuevamente. */
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.usucierrecaja_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);

/* Se inicializa un arreglo vacío llamado "final" en PHP. */
$final = [];

foreach ($data->data as $key => $value) {


    /* Se crea un array con datos de usuario extraídos de un objeto. */
    $array = [];


    $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
    $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};


    $array["UserName"] = $value->{"usuario.login"};

    /* asigna valores de un objeto a un array, formateando la fecha. */
    $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
    $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
    $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
    $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
    $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
    $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

    /* calcula el total financiero sumando y restando diferentes ingresos y gastos. */
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
    $array["Total"] = floatval($array["AmountBegin"]) + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];
    $dineroInicial = $array["AmountBegin"];

    /* agrega un array a `$final` y obtiene valores de fechas y usuario. */
    array_push($final, $array);

    $fecha = $array["Date"];
    $usuarioId = $value->{"usuario_cierrecaja.usuario_id"};


}

/* inicializa un arreglo con datos financieros y de productos. */
$response["Data"]["SquareDay"] = $final;
$response["Data"]["Products"] = array();
$response["Data"]["Incomes"] = array();
$response["Data"]["Expenses"] = array();
$response["Data"]["Tickets"] = array();
$response["Data"]["Deposit"] = array();

/* Inicializa arreglos de retiro e ingresos y crea un perfil de usuario. */
$response["Data"]["Withdraw"] = array();
$response["Data"]["IncomesCreditCards"] = array();

$UsuarioPerfil = new UsuarioPerfil($usuarioId);


$SkeepRows = 0;

/* inicializa variables para ordenar y limitar elementos en un conjunto de datos. */
$OrderedItem = 1;
$MaxRows = 1000;


$rules = [];
$grouping = "";


/* Condiciona reglas según el perfil del usuario para acceder a datos específicos. */
if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se agregan reglas de filtrado y se codifican en formato JSON. */
array_push($rules, array("field" => "DATE_FORMAT(ingreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();


/* obtiene e procesa datos de ingresos en formato JSON para su uso posterior. */
$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.*,cuenta_concepto.*,cuenta_producto.*,concepto.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Se asignan valores a un arreglo utilizando propiedades de un objeto. */
    $array = [];


    $array["Id"] = $value->{"ingreso.ingreso_id"};
    $array["Description"] = $value->{"ingreso.descripcion"};
    $array["Date"] = date('Y-m-d H:i:s', strtotime($value->{"ingreso.fecha_crea"}));


    /* Condicional que asigna valores a un arreglo según la descripción del producto. */
    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }
    $array["AccountConcept"] = $value->{"cuenta_concepto.referencia"};

    /* Asignación de valores de un objeto a un array para estructurar datos financieros. */
    $array["AccountConceptDescription"] = $value->{"cuenta_concepto.referencia"};
    $array["AccountProduct"] = $value->{"cuenta_producto.referencia"};
    $array["AccountProductDescription"] = $value->{"cuenta_producto.descripcion"};

    $array["Concept"] = $value->{"concepto.descripcion"};

    $array["Serie"] = $value->{"egreso.serie"};

    /* asigna valores específicos a tipos de documentos basados en su código. */
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


    /* Codifica datos de ingreso, indicando documento, valor y estado de corrección. */
    $array["Reference"] = $value->{"ingreso.documento"};
    $array["Amount"] = $value->{"ingreso.valor"};
    $array["IsCorrection"] = false;

    if ($value->{"ingreso.estado"} == 'C') {
        $array["IsCorrection"] = true;
    }


    if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {

        /* Suma el valor de "Amount" al total existente en la variable $total. */
        $total = $total + $array["Amount"];

        if ($value->{"producto_tercero.interno"} == "S") {


            /* Se crea una instancia de la clase 'Clasificador' con el tipo de producto especificado. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* agrega información sobre tickets a una respuesta estructurada. */

                    $array["Description"] = "Tickets";
                    $array["AmountWin"] = 0;
                    array_push($response["Data"]["Tickets"], $array);

                    break;

                case $TipoPremios->getClasificadorId():
                    /* Estructura de control que evalúa el id del clasificador de premios. */


                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* Se evalúa el identificador del clasificador en un caso específico, sin acción definida. */


                    break;

                case $TipoRecargas->getClasificadorId():
                    /* asigna una descripción y agrega un elemento a un arreglo. */

                    $array["Description"] = "Recargas";
                    array_push($response["Data"]["Deposit"], $array);

                    break;

            }
        } else {


            /* actualiza montos de productos o agrega uno nuevo si no existe. */
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
                /* Agrega un elemento al array "Products" en la respuesta si se cumple una condición. */

                array_push($response["Data"]["Products"], $array);
            }

        }

    } else {
        if ($value->{"ingreso.tipo_id"} != "0") {

            /* verifica un tipo y asigna un valor a 'dineroInicial' basado en condiciones. */
            if ($TipoDineroInicial->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
                $dineroInicial = $value->{"ingreso.valor"};

            }


            $Tipo = new Clasificador($value->{"ingreso.tipo_id"});


            /* maneja ingresos de tarjetas de crédito y los agrega a una respuesta. */
            switch ($Tipo->getTipo()) {
                case "TARJCRED":

                    $array["Description"] = "Tarjeta de Credito " . $value->{"producto_tercero.descripcion"};
                    $array["Amount"] = $array["Amount"];


                    array_push($response["Data"]["IncomesCreditCards"], $array);

                    $otrosIngresosTarjetasCreditos += $array["Amount"];
                    break;
            }

        } else {
            /* Agrega datos a un arreglo y suma un valor a los ingresos totales. */

            array_push($response["Data"]["Incomes"], $array);
            $otrosIngresos += $array["Amount"];
        }

    }


}

/* suma ingresos y define reglas según el perfil del usuario. */
$total = $total + $otrosIngresos;

$rules = [];
$grouping = "";


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    /* Agrega una regla al arreglo si no se cumple una condición previa. */

    array_push($rules, array("field" => "egreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se crea un filtro JSON para reglas de comparación de fechas en Egreso. */
array_push($rules, array("field" => "DATE_FORMAT(egreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();


/* obtiene y decodifica datos de egresos en formato JSON. */
$data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.*,cuenta_concepto.*,cuenta_producto.*,proveedor_tercero.*,documento.descripcion ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Código que crea un array con detalles de un egreso: ID, descripción y fecha. */
    $array = [];


    $array["Id"] = $value->{"egreso.ingreso_id"};
    $array["Description"] = $value->{"egreso.descripcion"};
    $array["Date"] = date('Y-m-d H:i:s', strtotime($value->{"egreso.fecha_crea"}));


    /* Condicional que asigna valores a un arreglo si la descripción del producto no está vacía. */
    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }
    $array["AccountConcept"] = $value->{"cuenta_concepto.referencia"};

    /* Asignación de valores a un array utilizando propiedades de un objeto. */
    $array["AccountConceptDescription"] = $value->{"cuenta_concepto.referencia"};
    $array["AccountProduct"] = $value->{"cuenta_producto.referencia"};
    $array["AccountProductDescription"] = $value->{"cuenta_producto.descripcion"};

    $array["Serie"] = $value->{"egreso.serie"};
    $array["TypeDocument"] = $value->{"egreso.tipo_documento"};

    /* Asignación de valores a un arreglo limpiando saltos de línea en algunos campos. */
    $array["NameDocument"] = $value->{"documento.descripcion"};

    $array["ProviderName"] = preg_replace("/\r|\n/", "", $value->{"proveedor_tercero.descripcion"});
    $array["ProviderDocument"] = preg_replace("/\r|\n/", "", $value->{"proveedor_tercero.documento"});

    $array["Reference"] = $value->{"egreso.documento"};

    /* asigna valores y determina si un egreso es una corrección. */
    $array["Amount"] = $value->{"egreso.valor"};
    $array["IsCorrection"] = false;

    if ($value->{"egreso.estado"} == 'C') {
        $array["IsCorrection"] = true;
    }


    if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {


        /* Resta el valor de "Amount" del arreglo a la variable total. */
        $total = $total - $array["Amount"];

        if ($value->{"producto_tercero.interno"} == "S") {


            /* Se crea un objeto "Clasificador" usando el tipo de producto como argumento. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* Es un fragmento de código PHP para manejar diferentes tipos de tickets mediante un switch. */


                    break;

                case $TipoPremios->getClasificadorId():
                    /* Asignación del monto ganado basado en el clasificador de premios. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* Se asigna una descripción y se añade un elemento a un array de retiros. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;

                case $TipoRecargas->getClasificadorId():
                    /* Estructura de control para evaluar el clasificador de recargas, sin acciones definidas. */


                    break;
                case "ACCWINTICKET":
                    /* Asigna el monto ganado al primer ticket en la respuesta. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;
                case "ACCPAYWD":
                    /* Asignación de descripción y adición a la respuesta para pagos de notas de retiro. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;
            }
        } else {


            /* verifica y actualiza productos en un array basado en condiciones específicas. */
            if (!$array["IsCorrection"] || $FExport == 1) {


                $encontroProducto = false;
                foreach ($response["Data"]["Products"] as $key => $product) {

                    if ($product["ProductId"] == $array["ProductId"]) {
                        $response["Data"]["Products"][$key]["AmountWin"] = $response["Data"]["Products"][$key]["AmountWin"] + $array["Amount"];

                        $encontroProducto = true;

                    }
                }
                if (!$encontroProducto) {
                    $array["AmountWin"] = $array["Amount"];
                    $array["Amount"] = 0;

                    array_push($response["Data"]["Products"], $array);
                }
            } else {
                /* asigna valores a un arreglo y agrega elementos a otro arreglo. */

                $array["AmountWin"] = $array["Amount"];
                $array["Amount"] = 0;

                array_push($response["Data"]["Products"], $array);
            }

        }


    } else {


        /* gestiona tipos de egreso y estructura respuestas según condiciones específicas. */
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
            /* Añade datos de gastos a la respuesta y acumula el valor de egresos. */

            array_push($response["Data"]["Expenses"], $array);
            $otrosEgresos += $value->{"egreso.valor"};

        }


    }


}


/* calcula un total ajustando ingresos y egresos, y lo almacena en una respuesta. */
$total = $total - $otrosEgresos - $otrosIngresosTarjetasCreditos;

$total = $total + $dineroInicial;

$response["Data"]["Total"] = $total;


$response["HasError"] = false;

/* configura un arreglo de respuesta con información sobre una operación exitosa. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};

/* organiza y define registros contables en un formato de respuesta estructurada. */
$response["data"] = $final;
$response["Day"] = $fecha;
$response["BetshopId"] = $usuarioId;

$records = array(
    array('', 'Sub Diario', 'Número de Comprobante', 'Fecha de Comprobante', 'Código de Moneda', 'Glosa Principal', 'Tipo de Cambio', 'Tipo de Conversión', 'Flag de Conversión de Moneda', 'Fecha Tipo de Cambio', 'Cuenta Contable', 'Código de Anexo', 'Código de Centro de Costo', 'Debe / Haber', 'Importe Original', 'Importe en Dólares', 'Importe en Soles', 'Tipo de Documento', 'Número de Documento', 'Fecha de Documento', 'Fecha de Vencimiento', 'Código de Area', 'Glosa Detalle', 'Código de Anexo Auxiliar', 'Medio de Pago', 'Tipo de Documento de Referencia', 'Número de Documento Referencia', 'Fecha Documento Referencia', 'Nro Máq. Registradora Tipo Doc. Ref.', 'Base Imponible Documento Referencia', 'IGV Documento Provisión', 'Tipo Referencia en estado MQ', 'Número Serie Caja Registradora', 'Fecha de Operación', 'Tipo de Tasa', 'Tasa Detracción/Percepción', 'Importe Base Detracción/Percepción Dólares', 'Importe Base Detracción/Percepción Soles', 'Tipo Cambio para F', 'Importe de IGV sin derecho crédito fiscal'),
    array('', 'Ver T.G. 02', 'Los dos primeros dígitos son el mes y los otros 4 siguientes un correlativo', 'Ver T.G. 03', '', 'Llenar  solo si Tipo de Conversión es C. Debe estar entre >=0 y <=9999.999999', 'Solo: \'C\'= Especial, \'M\'=Compra, \'V\'=Venta , \'F\' De acuerdo a fecha', 'Solo: \'S\' = Si se convierte, \'N\'= No se convierte', 'Si  Tipo de Conversión \'F\'', 'Debe existir en el Plan de Cuentas', 'Si Cuenta Contable tiene seleccionado Tipo de Anexo, debe existir en la tabla de Anexos', 'Si Cuenta Contable tiene habilitado C. Costo, Ver T.G. 05', '\'D\' ó \'H\'', 'Importe original de la cuenta contable. Obligatorio, debe estar entre >=0 y <=99999999999.99 ', 'Importe de la Cuenta Contable en Dólares. Obligatorio si Flag de Conversión de Moneda esta en \'N\', debe estar entre >=0 y <=99999999999.99 ', 'Importe de la Cuenta Contable en Soles. Obligatorio si Flag de Conversión de Moneda esta en \'N\', debe estra entre >=0 y <=99999999999.99 ', 'Si Cuenta Contable tiene habilitado el Documento Referencia Ver T.G. 06', 'Si Cuenta Contable tiene habilitado el Documento Referencia Incluye Serie y Número', 'Si Cuenta Contable tiene habilitado el Documento Referencia', 'Si Cuenta Contable tiene habilitada la Fecha de Vencimiento', 'Si Cuenta Contable tiene habilitada el Area. Ver T.G. 26', '', 'Si Cuenta Contable tiene seleccionado Tipo de Anexo Referencia', 'Si Cuenta Contable tiene habilitado Tipo Medio Pago. Ver T.G. \'S1\'', 'Si Tipo de Documento es \'NA\' ó \'ND\' Ver T.G. 06', 'Si Tipo de Documento es \'NC\', \'NA\' ó \'ND\', incluye Serie y Número', 'Si Tipo de Documento es \'NC\', \'NA\' ó \'ND\'', 'Si Tipo de Documento es \'NC\', \'NA\' ó \'ND\'. Solo cuando el Tipo Documento de Referencia \'TK\'', 'Si Tipo de Documento es \'NC\', \'NA\' ó \'ND\'', 'Si Tipo de Documento es \'NC\', \'NA\' ó \'ND\'', 'Si la Cuenta Contable tiene Habilitado Documento Referencia 2 y  Tipo de Documento es \'TK\'', 'Si la Cuenta Contable teien Habilitado Documento Referencia 2 y  Tipo de Documento es \'TK\'', 'Si la Cuenta Contable tiene Habilitado Documento Referencia 2. Cuando Tipo de Documento es \'TK\', consignar la fecha de emision del ticket', 'Si la Cuenta Contable tiene configurada la Tasa:  Si es \'1\' ver T.G. 28 y \'2\' ver T.G. 29', 'Si la Cuenta Contable tiene conf. en Tasa:  Si es \'1\' ver T.G. 28 y \'2\' ver T.G. 29. Debe estar entre >=0 y <=999.99', 'Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99', 'Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99', 'Especificar solo si Tipo Conversión es \'F\'. Se permite \'M\' Compra y \'V\' Venta.', 'Especificar solo para comprobantes de compras con IGV sin derecho de crédito Fiscal. Se detalle solo en la cuenta 42xxxx'),
);


/* itera sobre datos y abre un recurso temporal en memoria. */
foreach ($response["Data"] as $datum) {


}

// we use a threshold of 1 MB (1024 * 1024), it's just an example
$fd = fopen('php://temp/maxmemory:1048576', 'w');

/* verifica si se abre un archivo temporal y define encabezados y registros. */
if ($fd === FALSE) {
    die('Failed to open temporary file');
}

$headers = array('id', 'name', 'age', 'species');
$records = array(
    array('1', 'gise', '4', 'cat'),
    array('2', 'hek2mgl', '36', 'human')
);


/* escribe encabezados y registros en un archivo CSV. */
fputcsv($fd, $headers);
foreach ($records as $record) {
    fputcsv($fd, $record);
}

rewind($fd);

/* lee el contenido de un archivo y cierra el descriptor. */
$csv = stream_get_contents($fd);
fclose($fd); // releases the memory (or tempfile)

