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
 * Accounting/GetDetailsSquarePDF
 *
 * Obtener los detalles de un cierre de caja en PDF
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
 * Tambien añaden el pdf y el pdf codificado en base64
 *  $response["Pdf2"] = $pdf;
 *  $response["PdfPOS"] = base64_encode($data);
 *
 * @throws no No contiene manejo de exepciones
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crean objetos 'Clasificador' para diferentes tipos de tickets y transacciones. */
$TipoTickets = new Clasificador("", "ACCBETTICKET");
$TipoPremios = new Clasificador("", "ACCWINTICKET");
$TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
$TipoRecargas = new Clasificador("", "ACCREC");
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


$response["Data"] = array();

/* Se inicializan variables para manejar ingresos y egresos de productos. */
$total = 0;
$fecha = "";
$usuarioId = 0;

$ingresosProductos = 0;
$egresosProductos = 0;

/* Inicializa variables y obtiene un ID de solicitudes HTTP. */
$otrosIngresosTarjetasCreditos = 0;
$otrosIngresos = 0;
$otrosEgresos = 0;
$dineroInicial = 0;

$id = $_REQUEST["id"];


/* Se crean variables con datos de sesión y parámetros para un usuario mandante. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$Id = $params->Id;
$id = $Id;
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* ajusta el número de filas a omitir basado en parámetros de solicitud. */
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores predeterminados a variables vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crea un filtro con reglas para comparar un ID en una consulta. */
$rules = [];

array_push($rules, array("field" => "usuario_cierrecaja.usucierrecaja_id", "data" => $id, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se convierte un filtro a JSON y se obtienen datos de usuarios. */
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.usucierrecaja_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);

/* Se inicializa un arreglo vacío llamado final para almacenar datos posteriormente. */
$final = [];

foreach ($data->data as $key => $value) {


    /* Se crea un array asociativo con información del usuario de cierre de caja. */
    $array = [];


    $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
    $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
    $array["UserName"] = $value->{"usuario.login"};

    /* asigna datos de cierre de caja a un array estructurado. */
    $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
    $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
    $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
    $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
    $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
    $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

    /* calcula el total de ingresos menos egresos, almacenando valores en un array. */
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
    $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

    $dineroInicial = $array["AmountBegin"];


    /* almacena un array y extrae fecha, usuario y total. */
    array_push($final, $array);

    $fecha = $array["Date"];
    $usuarioId = $value->{"usuario_cierrecaja.usuario_id"};

    $total = $array["AmountBegin"];


}

/* inicializa un arreglo de datos con diferentes categorías. */
$response["Data"]["SquareDay"] = $final;
$response["Data"]["Products"] = array();
$response["Data"]["Incomes"] = array();
$response["Data"]["Expenses"] = array();
$response["Data"]["Tickets"] = array();
$response["Data"]["Deposit"] = array();

/* Se inicializa un arreglo para retiros y se establece un usuario perfil. */
$response["Data"]["Withdraw"] = array();

$SkeepRows = 0;
$OrderedItem = 1;
$MaxRows = 1000;

$UsuarioPerfil = new UsuarioPerfil($usuarioId);

/* Crea una regla de acceso basada en el perfil de usuario. */
$Usuario = new Usuario($usuarioId);

$rules = [];
$grouping = "";


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    /* Añade una regla de comparación para el campo usucajero_id con el usuarioId. */

    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se crea un filtro JSON para reglas de comparación de fechas en ingresos. */
array_push($rules, array("field" => "DATE_FORMAT(ingreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();


/* obtiene datos de ingresos y productos, luego los decodifica a JSON. */
$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Crea un array con datos de ingresos y productos, verificando la descripción del producto. */
    $array = [];


    $array["Id"] = $value->{"ingreso.ingreso_id"};
    $array["Description"] = $value->{"ingreso.descripcion"};

    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }


    /* asigna valores de un objeto a un array en PHP. */
    $array["Reference"] = $value->{"ingreso.documento"};
    $array["Amount"] = $value->{"ingreso.valor"};

    if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {

        if ($value->{"producto_tercero.interno"} == "S") {


            /* Se crea un objeto "Clasificador" utilizando el tipo de producto del tercero. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* asigna valores y añade información a un array sobre tickets. */

                    $array["Description"] = "Tickets";
                    $array["AmountWin"] = 0;
                    array_push($response["Data"]["Tickets"], $array);

                    break;

                case $TipoPremios->getClasificadorId():
                    /* Estructura de control que evalúa el identificador de clasificación de premios. */


                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* Condicional para ejecutar código según el valor de 'ClasificadorId' en 'TipoNotasRetiros'. */


                    break;

                case $TipoRecargas->getClasificadorId():
                    /* asigna una descripción y añade datos a un arreglo basado en un caso. */

                    $array["Description"] = "Recargas";
                    array_push($response["Data"]["Deposit"], $array);

                    break;

            }
        } else {
            /* actualiza o añade productos en un array según su ID. */

            $array["AmountWin"] = 0;

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
        }

    } else {

        /* Condiciona el procesamiento de ingresos dependiendo del tipo y clasificación de dinero. */
        if ($value->{"ingreso.tipo_id"} != "0") {

            if ($TipoDineroInicial->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
                $dineroInicial = $value->{"ingreso.valor"};
            }


            $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

            switch ($Tipo->getTipo()) {
                case "TARJCRED":
                    $otrosIngresosTarjetasCreditos += $array["Amount"];
                    break;
            }

        } else {
            /* Agrega datos de ingresos a la respuesta y suma el monto a otros ingresos. */

            array_push($response["Data"]["Incomes"], $array);
            $otrosIngresos += $array["Amount"];
        }

    }


    /* Suma el valor "Amount" de un array a la variable total. */
    $total = $total + $array["Amount"];

}


/* Código que define reglas para usuario con perfil "PUNTOVENTA" en un sistema. */
$rules = [];
$grouping = "";


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    /* Agrega una regla que verifica si el usucajero_id es igual al usuarioId. */

    array_push($rules, array("field" => "egreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Agrega reglas de filtrado y convierte el conjunto a formato JSON. */
array_push($rules, array("field" => "DATE_FORMAT(egreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();


/* obtiene y decodifica datos de egresos personalizados en formato JSON. */
$data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Se crea un array con información de ingresos y productos relacionados. */
    $array = [];


    $array["Id"] = $value->{"egreso.ingreso_id"};
    $array["Description"] = $value->{"egreso.descripcion"};

    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }


    /* Asignación de valores a un arreglo y cálculo de un total negativo. */
    $array["Reference"] = $value->{"egreso.documento"};
    $array["Amount"] = $value->{"egreso.valor"};


    $total = $total - $array["Amount"];

    if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {
        if ($value->{"producto_tercero.interno"} == "S") {


            /* Se crea un objeto "Clasificador" utilizando el valor de "tipo_id" de "producto_tercero". */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* Se trata de una estructura condicional que maneja casos según el clasificador de tickets. */


                    break;

                case $TipoPremios->getClasificadorId():
                    /* Asignación del monto ganado según el clasificador de premios. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* asigna una descripción y añade una nota de retiro al array de respuestas. */

                    $array["Description"] = "Pago Notas de Retiro";
                    $response["Data"]["Withdraw"][0]["AmountWin"] = $array["Amount"];

                    array_push($response["Data"]["Withdraw"], $array);

                    break;

                case $TipoRecargas->getClasificadorId():
                    /* Es un fragmento de código que evalúa el identificador de clasificador de recargas. */


                    break;
                case "ACCWINTICKET":
                    /* Asignación del valor "Amount" al primer ticket ganado en la respuesta. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;
                case "ACCPAYWD":
                    /* asigna una descripción y agrega un elemento al arreglo de retiros. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;
            }
        } else {
            /* actualiza o agrega un producto en la respuesta según sus condiciones. */

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
        }


    } else {


        /* asigna valores a un arreglo según el tipo de egreso especificado. */
        if ($value->{"egreso.tipo_id"} != "0") {
            $Tipo = new Clasificador($value->{"egreso.tipo_id"});

            switch ($Tipo->getAbreviado()) {
                case "ACCWINTICKET":
                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;
                case "ACCPAYWD":
                    $array["Description"] = "Pago Notas de Retiro";
                    $response["Data"]["Withdraw"][0]["AmountWin"] = $array["Amount"];

                    array_push($response["Data"]["Withdraw"], $array);

                    break;
            }

        } else {
            /* Agrega un nuevo gasto al array y suma su valor total a otros egresos. */

            array_push($response["Data"]["Expenses"], $array);
            $otrosEgresos += $value->{"egreso.valor"};

        }


    }


}


/* Genera tablas HTML con productos y calcula ingresos y egresos a partir de una respuesta. */
$htmlProduct = "";
$htmlProductExpense = "";

foreach ($response["Data"]["Products"] as $product) {
    $htmlProduct .= '<tr>
                    <td width="70%" align="left">' . $product["Description"] . '</td>
                    <td align="left">S/ ' . $product["Amount"] . '</td>

                </tr>';
    $htmlProductExpense .= '<tr>
                    <td width="70%" align="left">' . $product["Description"] . '</td>
                    <td align="left">S/ ' . $product["AmountWin"] . '</td>

                </tr>';
    $ingresosProductos += $product["Amount"];
    $egresosProductos += $product["AmountWin"];


}


/* Suma ingresos y egresos de productos a partir de una respuesta JSON. */
$ingresosProductos += $response["Data"]["Tickets"][0]["Amount"];
$ingresosProductos += $response["Data"]["Deposit"][0]["Amount"];
$egresosProductos += $response["Data"]["Tickets"][0]["AmountWin"];
$egresosProductos += $response["Data"]["Withdraw"][0]["AmountWin"];


$pdf = ' <html> <body>  <style>
 td{
  font-size:12px;
 }
</style><table style="
    width: 1000px;
    margin: 0 auto;
    border-collapse: collapse;
    width: 1000px;
    max-width: 1000px;
    margin: 0 auto;
"><tbody><tr><td align="center"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" style="width:120px;"></td><td><div style="font-weight: bold;border: 0px;font-size: 20px;">CIERRE DIARIO DE
    CAJA</div></td></tr></tbody></table> 
<table style="/* width:430px; */height: 355px;/* border:1px solid black; */border-collapse: collapse;width: 800px;max-width: 800px;margin: 0 auto;padding: 0px 10px;border: 0px;">
    <tbody>
    
    
    <tr>

        <td width="50%" align="left">Local: ' . $Usuario->nombre . '</td>
        <td width="50%" align="left">Fecha: ' . $fecha . '</td>
    </tr>
    <tr style="
    /* border-collapse: collapse; */ 
">
        <td width="50%" align="left"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Ingresos diarios por
            Juegos</font>
            <table >
                <tbody>
                <tr>
                    <td width="70%" align="left">Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["Amount"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Recargas</td>
                    <td align="left">S/  ' . $response["Data"]["Deposit"][0]["Amount"] . '</td>

                </tr>
                ' . $htmlProduct . '

                <tr style="
        font-weight: bold;
        /* padding-top: 6px; */
    ">
                    <td width="70%" align="left" style="font-weight: bold;">(1) INGRESO TOTAL DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $ingresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="left" width="50%"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Salidas diarias por premios o
            devoluciones </font>
            <table style="
    ">
                <tbody>
                <tr>
                    <td width="70%" align="left">Pago Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["AmountWin"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Retiros</td>
                    <td align="left">S/ ' . $response["Data"]["Withdraw"][0]["AmountWin"] . '</td>

                </tr>
 ' . $htmlProductExpense . '
                <tr style="
        font-weight: bold;
    ">
                    <td width="70%" align="left"  style="font-weight: bold;font-size:12px;">(2) SALIDAS TOTALES DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $egresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td width="50%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr>
                    <td align="left" width="70%"  style="font-size:12px;">IMPORTE TOTAL DE CAJA (1) - (2)</td>
                    <td align="left" style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos - $egresosProductos) . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(4) GASTOS ADICIONALES DEL DÍA</td>


                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top" style="padding-left: 15px;">
            <table style="8
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%" style="font-size:12px;">GASTOS CON FAC</td>
                    <td align="left" >S/ 0</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top" style="padding-left: 15px;">
            <table style="
        width: 100%;
    ">
                <tbody>


                <tr>
                    <td align="left" width="70%" style="font-size:12px;">GASTOS CON OTROS COMP</td>
                    <td align="left" >S/ 0</td>

                </tr>

                </tbody> 
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top" style="padding-left: 15px;">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%" style="font-size:12px;">MOVILIDADES</td>
                    <td align="left" >S/ 0</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top" style="padding-left: 15px;">
            <table style="
        width: 100%;
    ">
                <tbody>


                <tr>
                    <td align="left" width="70%" style="font-size:11px;">OTROS GASTOS</td>
                    <td align="left" >S/ ' . $otrosEgresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL DE CAJA EFECTIVO (3) - (4)</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos) . '
                    </td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>

        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL DE CAJA TARJETAS</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . $otrosIngresosTarjetasCreditos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">Cajero
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
    </tr>
    <tr>

        <td width="50%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:10px;">SALDO INICIAL</td>
                    <td  style="font-size:11px;" >S/ ' . $dineroInicial . '</td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:11px;">SALDO FINAL</td>
                    <td  style="font-size:11px;" >S/ ' . ($dineroInicial + $ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos - $otrosIngresosTarjetasCreditos) . '</td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    </tbody>
</table>';


$pdf = ' <html> <body>  <style>
 td{
  font-size:12px;
 }
</style><table style="
    width: 1000px;
    margin: 0 auto;
    border-collapse: collapse;
    width: 1000px;
    max-width: 1000px;
    margin: 0 auto;
"><tbody><tr><td align="center"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" style="width:120px;"></td><td><div style="font-weight: bold;border: 0px;font-size: 20px;">CIERRE DIARIO DE
    CAJA</div></td></tr></tbody></table> 
<table style="/* width:430px; */height: 355px;/* border:1px solid black; */border-collapse: collapse;width: 800px;max-width: 800px;margin: 0 auto;padding: 0px 10px;border: 0px;">
    <tbody>
    
    
    <tr>

        <td width="50%" align="left">Local: ' . $Usuario->nombre . '</td>
        <td width="50%" align="left">Fecha: ' . $fecha . '</td>
    </tr>
    <tr style="
    /* border-collapse: collapse; */ 
">
        <td width="50%" align="left"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Ingresos diarios por
            Juegos</font>
            <table >
                <tbody>
                <tr>
                    <td width="70%" align="left">Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["Amount"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Recargas</td>
                    <td align="left">S/  ' . $response["Data"]["Deposit"][0]["Amount"] . '</td>

                </tr>
                ' . $htmlProduct . '

                <tr style="
        font-weight: bold;
        /* padding-top: 6px; */
    ">
                    <td width="70%" align="left" style="font-weight: bold;">(1) INGRESO TOTAL DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $ingresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="left" width="50%"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Salidas diarias por premios o
            devoluciones </font>
            <table style="
    ">
                <tbody>
                <tr>
                    <td width="70%" align="left">Pago Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["AmountWin"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Retiros</td>
                    <td align="left">S/ ' . $response["Data"]["Withdraw"][0]["AmountWin"] . '</td>

                </tr>
 ' . $htmlProductExpense . '
                <tr style="
        font-weight: bold;
    ">
                    <td width="70%" align="left"  style="font-weight: bold;font-size:12px;">(2) SALIDAS TOTALES DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $egresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td width="50%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr>
                    <td align="left" width="70%"  style="font-size:12px;">IMPORTE TOTAL DE CAJA (1) - (2)</td>
                    <td align="left" style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos - $egresosProductos) . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(4) GASTOS ADICIONALES DEL DÍA</td>


                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top" style="padding-left: 15px;">
            <table style="8
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%" style="font-size:12px;">GASTOS CON FAC</td>
                    <td align="left" >S/ ' . $otrosEgresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(5) OTROS INGRESOS</td>

                    <td align="left" >S/ ' . $otrosIngresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL CAJA EFECTIVO (3)-(4)+(5)</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos) . '
                    </td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>

        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL DE CAJA TARJETAS</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . $otrosIngresosTarjetasCreditos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">Cajero
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
    </tr>
    <tr>

        <td width="50%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:10px;">SALDO INICIAL</td>
                    <td  style="font-size:11px;" >S/ ' . $dineroInicial . '</td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:11px;">SALDO FINAL</td>
                    <td  style="font-size:11px;" >S/ ' . ($dineroInicial + $ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos - $otrosIngresosTarjetasCreditos) . '</td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    </tbody>
</table>';


/* Genera una tabla HTML para un reporte de cierre diario de caja. */
$pdf2 = '<table style="/* width:430px; */height: 355px;/* border:1px solid black; */border-collapse: collapse;width: 500px;max-width: 500px;margin: 0 auto;">
    <tbody>
    <tr>

<td width="50%">CIERRE DIARIO DE
            CAJA</td><td>C</td>    </tr>





    </tbody>
</table>
</body> 
</html>';


$pdfPOS = ' <html> <body>  <style>
 td{
  font-size:12px;
 }
</style><table style="
    width: 500px;
    margin: 0 auto;
    border-collapse: collapse;
    width: 500px;
    max-width: 500px;
    margin: 0 auto;
"><tbody><tr><td align="center"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" style="width:120px;"></td></tr><tr><td><div style="font-weight: bold;border: 0px;font-size: 20px;">CIERRE DIARIO DE
    CAJA</div></td></tr></tbody></table> 
<table style="/* width:430px; */height: 355px;/* border:1px solid black; */border-collapse: collapse;width: 500px;max-width: 500px;margin: 0 auto;padding: 0px 10px;border: 0px;">
    <tbody>
    
    
    <tr>

        <td width="50%" align="left">Local: ' . $Usuario->nombre . '</td></tr><tr>
        <td width="50%" align="left">Fecha: ' . $fecha . '</td>
    </tr>
    <tr style="
    /* border-collapse: collapse; */ 
">
        <td width="100%" align="left"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Ingresos diarios por
            Juegos</font>
            <table >
                <tbody>
                <tr>
                    <td width="70%" align="left">Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["Amount"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Recargas</td>
                    <td align="left">S/  ' . $response["Data"]["Deposit"][0]["Amount"] . '</td>

                </tr>
                ' . $htmlProduct . '

                <tr style="
        font-weight: bold;
        /* padding-top: 6px; */
    ">
                    <td width="70%" align="left" style="font-weight: bold;">(1) INGRESO TOTAL DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $ingresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        </tr><tr>
        <td align="left" width="100%"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Salidas diarias por premios o
            devoluciones </font>
            <table style="
    ">
                <tbody>
                <tr>
                    <td width="70%" align="left">Pago Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["AmountWin"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Retiros</td>
                    <td align="left">S/ ' . $response["Data"]["Withdraw"][0]["AmountWin"] . '</td>

                </tr>
 ' . $htmlProductExpense . '
                <tr style="
        font-weight: bold;
    ">
                    <td width="70%" align="left"  style="font-weight: bold;font-size:12px;">(2) SALIDAS TOTALES DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $egresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td width="100%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr>
                    <td align="left" width="70%"  style="font-size:12px;">IMPORTE TOTAL DE CAJA (1) - (2)</td>
                    <td align="left" style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos - $egresosProductos) . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(4) GASTOS ADICIONALES DEL DÍA</td>


                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="100%" align="center" valign="top" style="padding-left: 15px;">
            <table style="8
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%" style="font-size:12px;">GASTOS CON FAC</td>
                    <td align="left" >S/ ' . $otrosEgresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    
    <tr>
        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(5) OTROS INGRESOS</td>

                    <td align="left" >S/ ' . $otrosIngresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL CAJA EFECTIVO (3)-(4)+(5)</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos) . '
                    </td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>

        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL DE CAJA TARJETAS</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . $otrosIngresosTarjetasCreditos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        
    </tr>
    <tr>

        <td width="100%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:14px;">SALDO INICIAL</td>
                    <td  style="font-size:14px;" >S/ ' . $dineroInicial . '</td>

                </tr>
                </tbody>
            </table>
        </td></tr><tr>
        <td align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:14px;">SALDO FINAL</td>
                    <td  style="font-size:14px;" >S/ ' . ($dineroInicial + $ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos - $otrosIngresosTarjetasCreditos) . '</td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
    <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">Cajero
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
</tr>
    <tr>
    <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
</tr>


    </tbody>
</table>';


require_once "mpdf6.1/mpdf.php";

/* Crea un documento PDF con márgenes espejados y visualización en dos páginas. */
$mpdf = new mPDF('c', array(80, 150), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


/* Genera un PDF a partir de HTML y lo guarda en una ruta específica. */
$mpdf->WriteHTML($pdf);

$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);

/* convierte archivos a formato Base64 para su manipulación y almacenamiento. */
$data = file_get_contents($path);
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);


/* asigna un PDF, total y estado de error a una respuesta. */
$response["Pdf2"] = $pdf;


$response["Data"]["Total"] = $total;


$response["HasError"] = false;

/* establece una respuesta estructurada con mensajes y conteos de datos. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;
$response["total_count"] = $data->count[0]->{".count"};

/* genera un archivo PDF con márgenes espejados utilizando mPDF. */
$response["data"] = $final;


$mpdf = new mPDF('c', array(45, 350), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)


/* Configura mPDF para mostrar en modo página completa y escribe contenido HTML. */
$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($pdfPOS);


/* genera un archivo PDF y lo guarda en una ruta temporal. */
$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);

/* Crea y codifica datos en base64, incluyendo un PDF y un tipo específico. */
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["PdfPOS"] = base64_encode($data);