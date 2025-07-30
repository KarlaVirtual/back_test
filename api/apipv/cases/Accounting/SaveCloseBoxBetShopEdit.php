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
 * Gestiona el cierre de caja y obtiene detalles de usuarios relacionados.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para el cierre de caja, incluyendo:
 * @param int $params ->CloseBoxId: Identificador del cierre de caja.
 *
 * @return void Modifica el parámetro $response con los siguientes cambios:
 *              - PdfPOS2: Contenido del PDF generado.
 *              - Pdf: Contenido del PDF codificado en base64.
 *              - Pdf2: Contenido del PDF en formato HTML.
 *              - PdfPOS: Contenido del PDF codificado en base64.
 *              - HasError: Indica si hubo un error.
 *              - AlertType: Tipo de alerta.
 *              - AlertMessage: Mensaje de alerta.
 *              - ModelErrors: Errores del modelo.
 */

/* gestiona la cierre de caja y obtiene detalles de usuarios relacionados. */
$CloseBoxId = $params->CloseBoxId;

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$Usucrea = 0;

if ($CloseBoxId != "") {
    $UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);
    $BetShops = $UsuarioCierrecaja->getUsuarioId();
    $Usuario = new Usuario($BetShops);
    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

    $fechaEspecifica = $UsuarioCierrecaja->getFechaCierre();

    $estado = 'C';

    $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);
    $Usucrea = $UsuarioMandante2->getUsuarioMandante();

}


/* Se crean clasificadores para distintos tipos de tickets, premios y transacciones. */
$TipoTickets = new Clasificador("", "ACCBETTICKET");
$TipoPremios = new Clasificador("", "ACCWINTICKET");
$TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
$TipoRecargas = new Clasificador("", "ACCREC");
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");

$TipoTicketsId = 0;

/* Variables inicializadas con valores cero para controlar diferentes tipos de premios y transacciones. */
$TipoPremiosId = 0;
$TipoNotasRetirosId = 0;
$TipoRecargasId = 0;
$dineroInicial = 0;

$otrosIngresosTarjetasCreditos = 0;


/* Define reglas de filtrado para productos y proveedores en una consulta. */
$rules = [];
array_push($rules, array("field" => "producto_tercero.tipo_id", "data" => "'" . $TipoTickets->getClasificadorId() . "','" . $TipoPremios->getClasificadorId() . "','" . $TipoNotasRetiros->getClasificadorId() . "','" . $TipoRecargas->getClasificadorId() . "'", "op" => "in"));
array_push($rules, array("field" => "proveedor_tercero.pais_id", "data" => $UsuarioMandante->getPaisId(), "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene productos de una base de datos. */
$json = json_encode($filtro);

$ProductoTercero = new ProductoTercero();

$data = $ProductoTercero->getProductoTercerosCustom("  producto_tercero.* ", "producto_tercero.productoterc_id", "asc", 0, 1000, $json, true);

$data = json_decode($data);

/* Se inicializa un arreglo vacío llamado "final" para almacenar elementos posteriormente. */
$final = [];
foreach ($data->data as $key => $value) {
    switch ($value->{"producto_tercero.tipo_id"}) {
        case $TipoTickets->getClasificadorId():
            /* asigna un ID de producto basado en el clasificador de tickets. */

            $TipoTicketsId = $value->{"producto_tercero.productoterc_id"};

            break;

        case $TipoPremios->getClasificadorId():
            /* asigna un ID de producto basado en un clasificador específico. */

            $TipoPremiosId = $value->{"producto_tercero.productoterc_id"};

            break;

        case $TipoNotasRetiros->getClasificadorId():
            /* Condicional que asigna el ID del producto según el clasificador de notas de retiros. */

            $TipoNotasRetirosId = $value->{"producto_tercero.productoterc_id"};

            break;

        case $TipoRecargas->getClasificadorId():
            /* Es un fragmento de código que asigna un ID basado en un clasificador específico. */

            $TipoRecargasId = $value->{"producto_tercero.productoterc_id"};

            break;
    }
}


/* Se generan reglas de filtrado para consultar usuarios y fechas. */
$rules = [];
array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_cierrecaja.fecha_crea", "data" => strtotime(date("Y-m-d 00:00:00") . ' - 1 days'), "op" => "ge"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se procesa un filtro JSON y se obtiene información personalizada de usuarios. */
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.usucierrecaja_id", "asc", 0, 10, $json, true);

$data = json_decode($data);

foreach ($data->data as $key => $value) {


    /* crea un array asociativo con datos de un usuario específico. */
    $array = [];


    $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
    $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
    $array["UserName"] = $value->{"usuario.login"};

    /* Asigna datos de cierre de caja a un array estructurado en PHP. */
    $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
    $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
    $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
    $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
    $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
    $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

    /* calcula el total de ingresos y gastos, almacenando resultados en un arreglo. */
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
    $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

    $dineroInicial = $array["Total"];

}


/* Inicializa variables para almacenar totales de ingresos y egresos en diferentes categorías. */
$TotalIngresosPropios = 0;
$TotalEgresosPropios = 0;

$TotalIngresosProductos = 0;
$TotalEgresosProductos = 0;

$TotalIngresosOtros = 0;

/* Inicializa variables para totalizar egresos y otros ingresos de productos, gastos e ingresos. */
$TotalEgresosOtros = 0;
$otrosIngresosTarjetasCreditos = 0;

$products = $params->products;
$expenses = $params->expenses;
$incomes = $params->incomes;

/* Crea una instancia de IngresoMySqlDAO y obtiene la transacción correspondiente. */
$IngresoMySqlDAO = new IngresoMySqlDAO();
$Transaction = $IngresoMySqlDAO->getTransaction();


foreach ($incomes as $income) {

    /* Se asignan valores de un objeto de ingreso a variables y se crea un nuevo objeto. */
    $Concept = $income->Concept;
    $Description = $income->Description;
    $Reference = $income->Reference;
    $Value = $income->Value;

    $Ingreso = new Ingreso();

    /* configura propiedades de un objeto "Ingreso" con valores específicos. */
    $Ingreso->setTipoId(0);
    $Ingreso->setDescripcion($Description);
    $Ingreso->setCentrocostoId(0);
    $Ingreso->setDocumento($Reference);
    $Ingreso->setEstado("A");
    $Ingreso->setValor($Value);

    /* configura propiedades de un objeto 'Ingreso' relacionado con un usuario y concepto. */
    $Ingreso->setImpuesto(0);
    $Ingreso->setRetraccion(0);
    $Ingreso->setUsuarioId($Usuario->puntoventaId);
    $Ingreso->setConceptoId($Concept);
    $Ingreso->setProductotercId(0);
    $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

    /* Configura un ingreso y lo inserta en la base de datos usando un DAO. */
    $Ingreso->setProveedortercId(0);

    $Ingreso->setUsucreaId($Usucrea);
    $Ingreso->setUsumodifId(0);


    $IngresoMySqlDAO->insert($Ingreso);


    /* Suma el valor a la variable TotalIngresosOtros para acumular ingresos adicionales. */
    $TotalIngresosOtros = $TotalIngresosOtros + $Value;

}


/* Concatena IDs de productos en una cadena; devuelve '0' si no hay productos. */
$productsText = "#";

foreach ($products as $product) {
    $productsText .= ',' . $product->ProductId;
}
if ($productsText == '#') {
    $productsText = '0';
}

/* reemplaza texto y construye reglas para filtrar ingresos por usuario y fecha. */
$productsText = str_replace("#,", "", $productsText);
$rules = [];

array_push($rules, array("field" => "ingreso.usuario_id", "data" => $Usuario->puntoventaId, "op" => "eq"));


if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

}


/* Se crea un filtro en formato JSON para validar entradas de productos. */
array_push($rules, array("field" => "ingreso.productoterc_id", "data" => $productsText, "op" => "in"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();


/* obtiene ingresos personalizados y lo convierte de JSON a objeto PHP. */
$data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", 0, 10000, $json, true);

$data = json_decode($data);

foreach ($products as $product) {


    /* Resta un valor específico de las apuestas de un producto basado en ciertos datos. */
    $valorAntes = 0;

    foreach ($data->data as $key => $value) {

        if ($product->ProductId == $value->{"ingreso.productoterc_id"}) {
            $product->Bets = $product->Bets - floatval($value->{"ingreso.valor"});
        }

    }

    if ($product->Bets != 0) {

        /* Se inicializan variables para un objeto de ingreso relacionado a las apuestas de un producto. */
        $Concept = 0;
        $Description = '';
        $Reference = '';
        $Value = $product->Bets;

        $Ingreso = new Ingreso();

        /* Código que configura propiedades de un objeto "Ingreso" con valores específicos. */
        $Ingreso->setTipoId(0);
        $Ingreso->setDescripcion($Description);
        $Ingreso->setCentrocostoId(0);
        $Ingreso->setDocumento($Reference);
        $Ingreso->setEstado("C");
        $Ingreso->setValor($Value);

        /* Se inicializan propiedades de un objeto Ingreso con valores y IDs específicos. */
        $Ingreso->setImpuesto(0);
        $Ingreso->setRetraccion(0);
        $Ingreso->setUsuarioId($Usuario->puntoventaId);
        $Ingreso->setConceptoId($Concept);
        $Ingreso->setProductotercId($product->ProductId);
        $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

        /* Se establecen propiedades en un objeto "Ingreso" con valores específicos. */
        $Ingreso->setProveedortercId(0);

        $Ingreso->setUsucreaId($Usucrea);
        $Ingreso->setUsumodifId(0);

        if ($fechaEspecifica != '') {
            $Ingreso->fechaCrea = $fechaEspecifica;
        }


        /* Inserta un ingreso en MySQL y acumula el total de ingresos de productos. */
        $IngresoMySqlDAO->insert($Ingreso);

        $TotalIngresosProductos = $TotalIngresosProductos + $Value;

    }

}


/* Se crea una instancia de EgresoMySqlDAO con una transacción específica. */
$EgresoMySqlDAO = new EgresoMySqlDAO($Transaction);

foreach ($expenses as $expense) {

    /* asigna propiedades de un gasto a variables y crea un objeto Egreso. */
    $Concept = $expense->Concept;
    $Description = $expense->Description;
    $Reference = $expense->Reference;
    $Value = $expense->Value;

    $Egreso = new Egreso();

    /* Configura parámetros para un objeto "Egreso" con valores específicos. */
    $Egreso->setTipoId(0);
    $Egreso->setDescripcion($Description);
    $Egreso->setCentrocostoId(0);
    $Egreso->setDocumento($Reference);
    $Egreso->setEstado("A");
    $Egreso->setValor($Value);

    /* Configuración de un objeto 'Egreso' con parámetros específicos relacionados al usuario y concepto. */
    $Egreso->setImpuesto(0);
    $Egreso->setRetraccion(0);
    $Egreso->setUsuarioId($Usuario->puntoventaId);
    $Egreso->setConceptoId($Concept);
    $Egreso->setProductotercId(0);
    $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

    /* Código para configurar y guardar un objeto "Egreso" en la base de datos. */
    $Egreso->setProveedortercId(0);

    $Egreso->setUsucreaId($Usucrea);
    $Egreso->setUsumodifId(0);


    $EgresoMySqlDAO->insert($Egreso);

    /* Suma el valor a la variable de total de egresos. */
    $TotalEgresosOtros = $TotalEgresosOtros + $Value;

}


/* define reglas para filtrar datos de egresos según usuario y fecha. */
$rules = [];

array_push($rules, array("field" => "egreso.usuario_id", "data" => $Usuario->puntoventaId, "op" => "eq"));


if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

}


/* Se crea un filtro con reglas y se convierte a formato JSON. */
array_push($rules, array("field" => "egreso.productoterc_id", "data" => $productsText, "op" => "in"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();


/* obtiene y decodifica datos de egresos en formato JSON. */
$data = $Egreso->getEgresosCustom("  egreso.* ", "egreso.egreso_id", "asc", 0, 1000, $json, true);

$data = json_decode($data);


foreach ($products as $product) {


    /* Resta el valor de egresos al precio del producto si coinciden los IDs. */
    $valorAntes = 0;

    foreach ($data->data as $key => $value) {

        if ($product->ProductId == $value->{"egreso.productoterc_id"}) {
            $product->Prize = $product->Prize - floatval($value->{"egreso.valor"});
        }

    }

    if ($product->Prize != 0) {

        /* Inicializa variables y crea un nuevo objeto Egreso en PHP. */
        $Concept = 0;
        $Description = '';
        $Reference = '';
        $Value = $product->Prize;

        $Egreso = new Egreso();

        /* Asigna valores a propiedades de un objeto "Egreso" en programación orientada a objetos. */
        $Egreso->setTipoId(0);
        $Egreso->setDescripcion($Description);
        $Egreso->setCentrocostoId(0);
        $Egreso->setDocumento($Reference);
        $Egreso->setEstado("C");
        $Egreso->setValor($Value);

        /* configura propiedades de un objeto "Egreso" con datos específicos. */
        $Egreso->setImpuesto(0);
        $Egreso->setRetraccion(0);
        $Egreso->setUsuarioId($Usuario->puntoventaId);
        $Egreso->setConceptoId($Concept);
        $Egreso->setProductotercId($product->ProductId);
        $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

        /* establece propiedades de un objeto 'Egreso' y configura fechas específicas. */
        $Egreso->setProveedortercId(0);

        $Egreso->setUsucreaId($Usucrea);
        $Egreso->setUsumodifId(0);

        if ($fechaEspecifica != '') {
            $Egreso->fechaCrea = $fechaEspecifica;
        }


        /* Inserta un egreso en la base de datos y suma su valor total. */
        $EgresoMySqlDAO->insert($Egreso);
        $TotalEgresosProductos = $TotalEgresosProductos + $Value;

    }

}

if (false) {

    /* Se inicializan variables y un array para reglas en un contexto de programación. */
    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 1000;


    $rules = [];

    /* Se construye un filtro en formato JSON para aplicar reglas de búsqueda. */
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => date("Y-m-d"), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => 'N', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Se obtiene y decodifica un JSON con datos de tickets agrupados por punto de venta. */
    $ItTicketEnc = new ItTicketEnc();
    $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_apuesta) vlr_apuesta, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, 0, true);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {

        /* Asigna 0 a .vlr_apuesta si está vacío en una estructura de datos. */
        if ($value->{".vlr_apuesta"} == "") {
            $value->{".vlr_apuesta"} = 0;
        }

        if ($value->{".vlr_apuesta"} > 0) {

            /* inicializa variables y obtiene un valor usando MandanteDetalle. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".vlr_apuesta"};

            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoTickets->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* captura excepciones, pero no realiza ninguna acción dentro del bloque. */


            }


            /* Crea un objeto "Ingreso" y configura sus propiedades con valores específicos. */
            $Ingreso = new Ingreso();
            $Ingreso->setTipoId($TipoTickets->getClasificadorId());
            $Ingreso->setDescripcion($Description);
            $Ingreso->setCentrocostoId(0);
            $Ingreso->setDocumento($Reference);
            $Ingreso->setEstado("A");

            /* Se asignan valores y atributos a un objeto de tipo Ingreso. */
            $Ingreso->setValor($Value);
            $Ingreso->setImpuesto(0);
            $Ingreso->setRetraccion(0);
            $Ingreso->setUsuarioId($Usuario->puntoventaId);
            $Ingreso->setConceptoId($Concept);
            $Ingreso->setProductotercId($TipoTicketsId);

            /* Se configura un objeto Ingreso y se inserta en base de datos usando DAO. */
            $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Ingreso->setProveedortercId(0);

            $Ingreso->setUsucreaId(0);
            $Ingreso->setUsumodifId(0);


            $IngresoMySqlDAO->insert($Ingreso);

            /* Acumula el valor en la variable TotalIngresosPropios sumando el nuevo ingreso. */
            $TotalIngresosPropios = $TotalIngresosPropios + $Value;

        }
    }


    /* Define reglas de filtrado para una consulta de base de datos con condiciones específicas. */
    $rules = [];
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.fecha_pago", "data" => date("Y-m-d"), "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Código que obtiene y procesa datos de tickets en formato JSON. */
    $json = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();

    $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_premio) vlr_premio, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {

        /* Asigna 0 a ".vlr_premio" si su valor es una cadena vacía. */
        if ($value->{".vlr_premio"} == "") {
            $value->{".vlr_premio"} = 0;
        }


        if ($value->{".vlr_premio"} > 0) {


            /* Inicializa variables y obtiene valor de premio mediante un objeto MandanteDetalle. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".vlr_premio"};
            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoPremios->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


            }


            /* Se crea un objeto "Egreso" y se establecen sus propiedades relevantes. */
            $Egreso = new Egreso();
            $Egreso->setTipoId($TipoPremios->getClasificadorId());
            $Egreso->setDescripcion($Description);
            $Egreso->setCentrocostoId(0);
            $Egreso->setDocumento($Reference);
            $Egreso->setEstado("A");

            /* configura atributos de un objeto "Egreso" con valores específicos. */
            $Egreso->setValor($Value);
            $Egreso->setImpuesto(0);
            $Egreso->setRetraccion(0);
            $Egreso->setUsuarioId($Usuario->puntoventaId);
            $Egreso->setConceptoId($Concept);
            $Egreso->setProductotercId($TipoPremiosId);

            /* establece propiedades de un objeto y lo inserta en base de datos. */
            $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Egreso->setProveedortercId(0);

            $Egreso->setUsucreaId(0);
            $Egreso->setUsumodifId(0);


            $EgresoMySqlDAO->insert($Egreso);


            /* Suma el valor a la variable total de egresos propios. */
            $TotalEgresosPropios = $TotalEgresosPropios + $Value;

        }

    }

    /* Añade el contenido de `$array` al final del array `$final`. */
    array_push($final, $array);
}

if (false) {

    /* Crea reglas de filtrado para consultas, definiendo campos, valores y operaciones. */
    $rules = [];
    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    //array_push($rules, array("field" => "DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') ", "data" => date("Y-m-d"), "op" => "eq"));
    array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(("Y-m-d 00:00:00")), "op" => "ge"));
    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(("Y-m-d 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* obtiene datos de recargas de usuario y los decodifica en JSON. */
    $json = json_encode($filtro);

    $UsuarioRecarga = new UsuarioRecarga();

    $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false);

    $data = json_decode($data);


    /* Se crea un arreglo asociativo con información sobre un producto y sus atributos. */
    $array = [];


    $array["Id"] = 0;
    $array["Product"] = "Doradobet Recargas - Pago Notas";
    $array["Bets"] = 0;

    /* Se inicializa el elemento "Prize" del array con el valor cero. */
    $array["Prize"] = 0;
    foreach ($data->data as $key => $value) {

        /* Asigna cero a ".total" si su valor actual es una cadena vacía. */
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }


        if ($value->{".total"} > 0) {

            /* Inicializa variables, crea objeto "MandanteDetalle" y obtiene un valor específico. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".total"};

            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoRecargas->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, captura errores sin ejecutar código adicional. */


            }

            /* crea un objeto "Ingreso" y establece sus propiedades correspondientes. */
            $Ingreso = new Ingreso();
            $Ingreso->setTipoId($TipoRecargas->getClasificadorId());
            $Ingreso->setDescripcion($Description);
            $Ingreso->setCentrocostoId(0);
            $Ingreso->setDocumento($Reference);
            $Ingreso->setEstado("A");

            /* Configura propiedades del objeto Ingreso con valores específicos relacionados a una transacción. */
            $Ingreso->setValor($Value);
            $Ingreso->setImpuesto(0);
            $Ingreso->setRetraccion(0);
            $Ingreso->setUsuarioId($Usuario->puntoventaId);
            $Ingreso->setConceptoId($Concept);
            $Ingreso->setProductotercId($TipoRecargasId);

            /* establece atributos en un objeto y lo inserta en la base de datos. */
            $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Ingreso->setProveedortercId(0);

            $Ingreso->setUsucreaId(0);
            $Ingreso->setUsumodifId(0);


            $IngresoMySqlDAO->insert($Ingreso);


            /* Suma el valor a la variable total de ingresos propios. */
            $TotalIngresosPropios = $TotalIngresosPropios + $Value;

        }
    }
}

if (false) {

    /* Se crean reglas de filtrado para consultas con condiciones de igualdad y rango de tiempo. */
    $rules = [];
    array_push($rules, array("field" => "usuario_punto.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    //array_push($rules, array("field" => "DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') ", "data" => date("Y-m-d"), "op" => "eq"));
    array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(("Y-m-d 00:00:00")), "op" => "ge"));
    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(("Y-m-d 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene datos personalizados de cuentas por cobrar. */
    $json = json_encode($filtro);

    $CuentaCobro = new CuentaCobro();

    $data = $CuentaCobro->getCuentasCobroCustom("  SUM(cuenta_cobro.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, true, false);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {

        /* inicializa ".total" en 0 si está vacío. */
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }


        if ($value->{".total"} > 0) {


            /* Código que obtiene un valor de un objeto y maneja excepciones para calcular un concepto. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".total"};

            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoNotasRetiros->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* Este bloque captura excepciones en PHP sin realizar ninguna acción específica. */


            }


            /* Se crea un objeto Egreso configurando sus propiedades con datos específicos. */
            $Egreso = new Egreso();
            $Egreso->setTipoId($TipoNotasRetiros->getClasificadorId());
            $Egreso->setDescripcion($Description);
            $Egreso->setCentrocostoId(0);
            $Egreso->setDocumento($Reference);
            $Egreso->setEstado("A");

            /* Código que asigna valores a un objeto "Egreso" para gestión de transacciones. */
            $Egreso->setValor($Value);
            $Egreso->setImpuesto(0);
            $Egreso->setRetraccion(0);
            $Egreso->setUsuarioId($Usuario->puntoventaId);
            $Egreso->setConceptoId($Concept);
            $Egreso->setProductotercId($TipoNotasRetirosId);

            /* inserta un objeto Egreso con varios IDs en una base de datos. */
            $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Egreso->setProveedortercId(0);

            $Egreso->setUsucreaId(0);
            $Egreso->setUsumodifId(0);


            $EgresoMySqlDAO->insert($Egreso);


            /* Suma el valor a la variable de total de egresos propios acumulados. */
            $TotalEgresosPropios = $TotalEgresosPropios + $Value;

        }
    }
}


/* Define reglas para filtrar datos según condiciones de sesión y cierre de caja. */
$SkeepRows = 0;
$MaxRows = 1000;

$rules = [];

if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $CloseBoxId != '') {

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} else {
    /* Agrega una regla al array para validar el ID del usuario. */

    array_push($rules, array("field" => "egreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

}


/* Agrega reglas de fecha a un array según si hay una fecha específica. */
if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

} else {
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

}


/* Crea un filtro JSON y obtiene datos de egresos personalizados de la base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();

$data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

/* calcula totales de egresos a partir de datos en formato JSON. */
$data = json_decode($data);


foreach ($data->data as $key => $value) {
    if ($value->{"egreso.productoterc_id"} != "0" && ($value->{"producto_tercero.tiene_cupo"} == "N" || $value->{"producto_tercero.tiene_cupo"} == "")) {
        if ($value->{"producto_tercero.interno"} == "S") {
            $TotalEgresosPropios = $TotalEgresosPropios + $value->{"egreso.valor"};

        } else {
            $TotalEgresosProductos = $TotalEgresosProductos + $value->{"egreso.valor"};
        }

    } else {
        if (($value->{"producto_tercero.tiene_cupo"} == "N" || $value->{"producto_tercero.tiene_cupo"} == "")) {
            $TotalEgresosOtros = $TotalEgresosOtros + $value->{"egreso.valor"};
        }
    }

}


/* Condición que agrega regla basada en perfil o ID de caja en un array. */
$rules = [];

if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $CloseBoxId != '') {

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} else {
    /* Añade una regla de comparación para usucajero_id en el array de reglas. */

    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

}


/* Agrega reglas para filtrar datos según tipo de ingreso y fecha específica. */
array_push($rules, array("field" => "ingreso.tipo_id", "data" => 0, "op" => "eq"));

if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

} else {
    /* Agrega una regla de fecha mínima al arreglo si se cumple una condición. */

    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

}


/* Se crea un filtro en JSON y se obtienen ingresos personalizados usando ese filtro. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();

$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Convierte una cadena JSON en un objeto o array en PHP. */
$data = json_decode($data);

foreach ($data->data as $key => $value) {


    /* Suma valores a diferentes totales según condiciones de producto e ingreso. */
    if ($value->{"ingreso.productoterc_id"} != "0") {
        if ($value->{"producto_tercero.interno"} == "S") {
            $TotalIngresosPropios = $TotalIngresosPropios + $value->{"ingreso.valor"};

        } else {
            $TotalIngresosProductos = $TotalIngresosProductos + $value->{"ingreso.valor"};
        }

    } else {
        /* suma ingresos basados en el tipo de ingreso y su valor asociado. */

        if ($value->{"ingreso.tipo_id"} != "0") {

            $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

            switch ($Tipo->getTipo()) {
                case "TARJCRED":
                    $otrosIngresosTarjetasCreditos += $value->{"ingreso.valor"};
                    break;
            }
        } else {
            $TotalIngresosOtros = $TotalIngresosOtros + $value->{"ingreso.valor"};

        }
    }

}


/* Se agregan reglas para validar el usuario si está en "PUNTOVENTA" o tiene identificador. */
$rules = [];

if ($_SESSION["win_perfil2"] == "PUNTOVENTA" || $CloseBoxId != '') {

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
} else {
    /* Se agrega una regla al array si no se cumple una condición previa. */

    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

}


/* Agrega reglas para validar ingresos basados en tipo y fecha específica. */
array_push($rules, array("field" => "ingreso.tipo_id", "data" => 0, "op" => "ne"));

if ($fechaEspecifica != '') {
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00", strtotime($fechaEspecifica)), "op" => "ge"));
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 23:59:59", strtotime($fechaEspecifica)), "op" => "le"));

} else {
    /* Agrega una regla al arreglo si la condición previa no se cumple. */

    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));

}


/* Código que filtra y obtiene ingresos personalizados en formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();

$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Convierte una cadena JSON en un objeto o arreglo en PHP. */
$data = json_decode($data);

foreach ($data->data as $key => $value) {

    /* suma ingresos a diferentes totales según condiciones específicas. */
    if ($value->{"ingreso.productoterc_id"} != "0") {
        if ($value->{"producto_tercero.interno"} == "S") {
            $TotalIngresosPropios = $TotalIngresosPropios + $value->{"ingreso.valor"};

        } else {
            $TotalIngresosProductos = $TotalIngresosProductos + $value->{"ingreso.valor"};
        }

    } else {
        /* clasifica ingresos y acumula valores según el tipo de ingreso. */


        if ($value->{"ingreso.tipo_id"} != "0") {

            $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

            switch ($Tipo->getTipo()) {
                case "TARJCRED":
                    $otrosIngresosTarjetasCreditos += $value->{"ingreso.valor"};
                    break;
            }
        } else {
            $TotalIngresosOtros = $TotalIngresosOtros + $value->{"ingreso.valor"};

        }
    }
}


/* Se definen reglas de filtrado para ingresos según varios criterios. */
$rules = [];
array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte un filtro a JSON y obtiene ingresos personalizados desde una base de datos. */
$json = json_encode($filtro);

$Ingreso = new Ingreso();

$data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);


/* Recorremos datos, obtenemos un valor y asignamos un usuario a cierre de caja. */
foreach ($data->data as $key => $value) {
    $dineroInicial = $value->{"ingreso.valor"};
}

$UsuarioCierrecaja = new UsuarioCierrecaja($CloseBoxId);

$UsuarioCierrecaja->setUsuarioId($UsuarioMandante->getUsuarioMandante());
//$UsuarioCierrecaja->setFechaCierre(date("Y-m-d"));

/* establece ingresos y egresos en un objeto `UsuarioCierrecaja`. */
$UsuarioCierrecaja->setIngresosPropios($TotalIngresosPropios);
$UsuarioCierrecaja->setEgresosPropios($TotalEgresosPropios);
$UsuarioCierrecaja->setIngresosProductos($TotalIngresosProductos);
$UsuarioCierrecaja->setEgresosProductos($TotalEgresosProductos);
$UsuarioCierrecaja->setIngresosOtros($TotalIngresosOtros);
$UsuarioCierrecaja->setEgresosOtros($TotalEgresosOtros);
//$UsuarioCierrecaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());

/* Actualiza información de usuario y cierra transacción en una base de datos. */
$UsuarioCierrecaja->setUsumodifId($Usucrea);
//$UsuarioCierrecaja->setDineroInicial($dineroInicial);
$UsuarioCierrecaja->setIngresosTarjetacredito($otrosIngresosTarjetasCreditos);
$UsuarioCierrecajaMySqlDAO = new UsuarioCierrecajaMySqlDAO($Transaction);
$UsuarioCierrecajaMySqlDAO->update($UsuarioCierrecaja);

/*$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$Usuario->fechaCierrecaja = date("Y-m-d H:i:s");

$UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
$UsuarioMySqlDAO->update($Usuario);*/
$Transaction->commit();


/* Obtiene la fecha de cierre y inicializa la respuesta sin errores. */
$fechaCierreAct = $UsuarioCierrecaja->getFechaCierre();


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* inicializa un array para errores y una cadena para PDF, luego instancia una clase. */
$response["ModelErrors"] = [];

$response["PdfPOS"] = "";


/**
 * PDF GENERATION
 */

$TipoTickets = new Clasificador("", "ACCBETTICKET");

/* Código define clasificadores para premios, notas de retiros, recargas y dinero inicial. */
$TipoPremios = new Clasificador("", "ACCWINTICKET");
$TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
$TipoRecargas = new Clasificador("", "ACCREC");
$TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


$response["Data"] = array();

/* Inicializa variables para total, fecha, usuario y contadores de ingresos y egresos. */
$total = 0;
$fecha = "";
$usuarioId = 0;

$ingresosProductos = 0;
$egresosProductos = 0;

/* Inicializa variables relacionadas con ingresos, egresos y obtiene el ID de cierre de caja. */
$otrosIngresosTarjetasCreditos = 0;
$otrosIngresos = 0;
$otrosEgresos = 0;
$dineroInicial = 0;

$id = $UsuarioCierrecaja->getUsucierrecajaId();

/* Código para asignar valores de parámetros y solicitudes HTTP a variables. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* inicializa variables si están vacías para evitar errores en su uso. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite de filas y define reglas de filtrado. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$rules = [];

array_push($rules, array("field" => "usuario_cierrecaja.usucierrecaja_id", "data" => $id, "op" => "eq"));


/* Se crea un filtro JSON para obtener datos de usuarios y cierres de caja. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.fecha_cierre", "asc", $SkeepRows, $MaxRows, $json, true);


/* Convierte datos JSON a objeto PHP y prepara un arreglo vacío. */
$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* Crea un array asociativo con datos de cierre de caja y usuario. */
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

    /* Se asignan ingresos y gastos a un array y se calcula el total. */
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
    $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

    array_push($final, $array);


    /* Asignación de variables a partir de un array con datos financieros y de usuario. */
    $fecha = $array["Date"];
    $usuarioId = $value->{"usuario_cierrecaja.usuario_id"};

    $total = $array["AmountBegin"];

    $dineroInicial = $array["Total"];


}

/* inicializa un arreglo de respuesta con datos vacíos para varias categorías. */
$response["Data"]["SquareDay"] = $final;
$response["Data"]["Products"] = array();
$response["Data"]["Incomes"] = array();
$response["Data"]["Expenses"] = array();
$response["Data"]["Tickets"] = array();
$response["Data"]["Deposit"] = array();

/* inicializa un array para retiradas y establece variables de control. */
$response["Data"]["Withdraw"] = array();

$SkeepRows = 0;
$OrderedItem = 1;
$MaxRows = 1000;

$UsuarioPerfil = new UsuarioPerfil($usuarioId);

/* Crea un objeto usuario y define reglas según el perfil "PUNTOVENTA". */
$Usuario = new Usuario($usuarioId);

$rules = [];
$grouping = "";


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "ingreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    /* Agrega una regla para filtrar por ID de usuario en un arreglo. */

    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se crea un filtro JSON para consultas de fechas en una base de datos. */
array_push($rules, array("field" => "DATE_FORMAT(ingreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Ingreso = new Ingreso();


/* obtiene ingresos personalizados, decodifica JSON y inicializa un arreglo vacío. */
$data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un arreglo con datos de ingreso y producto si está disponible. */
    $array = [];


    $array["Id"] = $value->{"ingreso.ingreso_id"};
    $array["Description"] = $value->{"ingreso.descripcion"};

    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }


    /* Asigna valores de un objeto a un array asociativo en PHP. */
    $array["Reference"] = $value->{"ingreso.documento"};
    $array["Amount"] = $value->{"ingreso.valor"};

    if ($value->{"producto_tercero.descripcion"} != "") {

        if ($value->{"producto_tercero.interno"} == "S") {


            /* Se instancia un objeto "Clasificador" con el tipo de producto del valor dado. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* asigna valores a un arreglo según el identificador del clasificador de tickets. */

                    $array["Description"] = "Tickets";
                    $array["AmountWin"] = 0;
                    array_push($response["Data"]["Tickets"], $array);

                    break;

                case $TipoPremios->getClasificadorId():
                    /* Estructura de control para manejar casos según el identificador de clasificador de premios. */


                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* Es un fragmento de código en PHP para manejar diferentes casos basados en un clasificador. */


                    break;

                case $TipoRecargas->getClasificadorId():
                    /* asigna "Recargas" a una descripción y la agrega a una respuesta. */

                    $array["Description"] = "Recargas";
                    array_push($response["Data"]["Deposit"], $array);

                    break;

            }
        } else {
            /* Actualiza o añade un producto en un arreglo de respuesta basado en su ID. */

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

        /* Condicional que procesa ingresos según tipo de dinero y categoría específica. */
        if ($value->{"ingreso.tipo_id"} != "0") {

            if ($TipoDineroInicial->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
                //$dineroInicial = $value->{"ingreso.valor"};
            }


            $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

            switch ($Tipo->getTipo()) {
                case "TARJCRED":
                    $otrosIngresosTarjetasCreditos += $array["Amount"];
                    break;
            }

        } else {
            /* Agrega un ingreso al array y actualiza el total de "otros ingresos". */

            array_push($response["Data"]["Incomes"], $array);
            $otrosIngresos += $array["Amount"];
        }

    }


    /* Suma el valor de "Amount" del array a la variable total. */
    $total = $total + $array["Amount"];

}


/* define reglas basadas en el perfil de usuario para filtrar información. */
$rules = [];
$grouping = "";


if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

    array_push($rules, array("field" => "egreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
} else {
    /* Agrega una regla al array si no se cumple una condición previa. */

    array_push($rules, array("field" => "egreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
}


/* Se añaden reglas de filtrado y se convierten a formato JSON para uso posterior. */
array_push($rules, array("field" => "DATE_FORMAT(egreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$Egreso = new Egreso();


/* obtiene y decodifica datos de egresos y productos en formato JSON. */
$data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un arreglo con datos de ingresos y productos si están disponibles. */
    $array = [];


    $array["Id"] = $value->{"egreso.ingreso_id"};
    $array["Description"] = $value->{"egreso.descripcion"};

    if ($value->{"producto_tercero.descripcion"} != "") {
        $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
        $array["Description"] = $value->{"producto_tercero.descripcion"};

    }


    /* asigna valores a un arreglo y actualiza un total restando la cantidad. */
    $array["Reference"] = $value->{"egreso.documento"};
    $array["Amount"] = $value->{"egreso.valor"};


    $total = $total - $array["Amount"];

    if ($value->{"producto_tercero.descripcion"} != "") {
        if ($value->{"producto_tercero.interno"} == "S") {


            /* Se crea una instancia de la clase "Clasificador" usando un id de tipo de producto. */
            $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

            // switch ($Tipo->getAbreviado()) {
            switch ($value->{"producto_tercero.tipo_id"}) {

                case $TipoTickets->getClasificadorId():
                    /* es un case para manejar diferentes tipos de tickets según su clasificador. */


                    break;

                case $TipoPremios->getClasificadorId():
                    /* Asigna el monto de premio basado en el clasificador del tipo de premio. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    /* asigna una descripción a un tipo de nota y la agrega a una respuesta. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;

                case $TipoRecargas->getClasificadorId():
                    /* Es un fragmento de código que evalúa un clasificador y realiza acciones condicionales. */


                    break;
                case "ACCWINTICKET":
                    /* Asigna el valor de "Amount" a "AmountWin" en el ticket correspondiente. */

                    $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                    break;
                case "ACCPAYWD":
                    /* asigna una descripción y agrega datos a un array de retiros. */

                    $array["Description"] = "Pago Notas de Retiro";
                    array_push($response["Data"]["Withdraw"], $array);

                    break;
            }
        } else {
            /* Verifica si un producto existe; si no, lo añade al array de productos. */

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


        /* Condiciona acciones según el tipo de egreso en función de su ID. */
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
            /* Añade datos de gastos a la respuesta y suma el valor de egresos. */

            array_push($response["Data"]["Expenses"], $array);
            $otrosEgresos += $value->{"egreso.valor"};

        }


    }


}


/* Genera tablas HTML con productos y sus respectivos montos, calculando ingresos y egresos. */
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


/* Se suman ingresos y egresos de productos a partir de un array de respuesta. */
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


/* Genera una tabla en HTML para un cierre diario de caja. */
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

/* Creación de un documento PDF con márgenes espejados y visualización en dos páginas. */
$mpdf = new mPDF('c', array(80, 150), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


/* genera un PDF y lo guarda en la ruta especificada. */
$mpdf->WriteHTML($pdf);

$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);

/* carga un archivo, lo codifica en base64 y lo almacena en $response. */
$data = file_get_contents($path);
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);


/* Configura mPDF con márgenes espejados y modo de visualización de página completa. */
$mpdf = new mPDF('c', array(45, 350), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


/* Genera un PDF a partir de HTML y lo guarda en un archivo temporal. */
$mpdf->WriteHTML($pdfPOS);

$mpdf->Output('/tmp' . "/mpdf.pdf", "F");

$path = '/tmp' . '/mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);

/* convierte archivos a base64 y los almacena en una respuesta JSON. */
$data = file_get_contents($path);
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["PdfPOS"] = base64_encode($data);


/* Código configura reglas de filtrado con condiciones específicas para consultar datos. */
$rules = [];
array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_cierrecaja.fecha_cierre", "data" => $fechaCierreAct, "op" => "gt"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Codifica y decodifica datos JSON para obtener información de usuarios de cierre de caja. */
$json = json_encode($filtro);

$UsuarioCierrecaja = new UsuarioCierrecaja();

$data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.fecha_cierre", "asc", 0, 100, $json, true);

$data = json_decode($data);

foreach ($data->data as $key => $value) {


    /* Se crea un array asociativo con datos de usuario y su ID. */
    $array = [];


    $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
    $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
    $array["UserName"] = $value->{"usuario.login"};

    /* Asigna valores a un arreglo basado en datos de un objeto asociado a cierre de caja. */
    $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
    $array["AmountBegin"] = $dineroInicial;
    $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
    $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
    $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
    $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

    /* Asigna ingresos y gastos a un array usando datos del usuario en una clase. */
    $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
    $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
    $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};


    $UsuarioCierrecaja = new UsuarioCierrecaja($value->{"usuario_cierrecaja.usucierrecaja_id"});

    /* Actualiza un registro de usuario y calcula el dinero inicial basado en ingresos y gastos. */
    $UsuarioCierrecaja->setUsumodifId($Usucrea);
    $UsuarioCierrecaja->setDineroInicial($dineroInicial);
    $UsuarioCierrecajaMySqlDAO = new UsuarioCierrecajaMySqlDAO();
    $UsuarioCierrecajaMySqlDAO->update($UsuarioCierrecaja);

        /* Cálculo de gastos y actualizaciones de usuario en base de datos. */
    $dineroInicial = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
        - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

    /*$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $Usuario->fechaCierrecaja = date("Y-m-d H:i:s");

    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
    $UsuarioMySqlDAO->update($Usuario);*/
    $UsuarioCierrecajaMySqlDAO->getTransaction()->commit();

}

