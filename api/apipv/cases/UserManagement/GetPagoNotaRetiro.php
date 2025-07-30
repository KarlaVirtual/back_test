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
 * UserManagement/GetPagoNotaRetiro
 *
 * Este script obtiene el pago de una nota de retiro, procesando datos de entrada y generando una respuesta estructurada.
 *
 * @param string $params JSON decodificado que contiene los siguientes valores:
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual ordenar los resultados.
 * @param int $param->SkeepRows Número de filas a omitir en la consulta.
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "error").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - data (array): Datos procesados de las cuentas de cobro.
 *
 * @throws Exception Si el punto de venta no está autorizado para realizar notas de retiro.
 * @throws Exception Si los parámetros enviados son incorrectos.
 * @throws Exception Si ocurre un error general.
 */


/* obtiene y decodifica datos JSON y asigna valores de entrada. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$IdNota = intval($_REQUEST["IdNota"]);
$Clave = $_REQUEST["Clave"];
$Cedula = strtolower($_REQUEST["Cedula"]);


/* obtiene parámetros de entrada para filtrar filas en una solicitud. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* asigna valores predeterminados a variables si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un límite de filas y crea un objeto para la consulta de usuarios. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

/**Se instancian objetos para consulta de tablas CuentaCobro, UsuarioMandante o Usuario */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

/* verifica si un punto de venta puede realizar notas de retiro. */
$UsuarioPunto = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioPuntoVenta = new Usuario($UsuarioPunto->puntoventaId);

//Verificación de estado contingencia_retiro
/**Este bloque verifica que el usuario no tenga habilitada la columna contingencia_retiro, en caso
 * contrario evita que se realice la nota de retiro*/
if ($UsuarioPuntoVenta->contingenciaRetiro == 'A') {
    throw new Exception('Este punto de venta no tiene autorizado hacer notas de retiro', '110005');
}
//Fin Verificación de estado contingencia_retiro


/* crea reglas de validación basadas en condiciones específicas de entrada. */
$rules = [];
if ($Cedula = !"" && $Cedula != null) {
    array_push($rules, array("field" => "registro.cedula", "data" => strtolower($_REQUEST["Cedula"]), "op" => "eq"));
}
array_push($rules, array("field" => "cuenta_cobro.cuenta_id", "data" => "$IdNota", "op" => "eq"));

if ($_ENV['debug']) {
    print_r($_REQUEST);
}

/* verifica un entorno de depuración y prepara datos en JSON sin comillas simples. */
if ($_ENV['debug']) {
    print_r($rules);
}
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$IdNota = str_replace("'", "", $IdNota);

/* limpia una clave y crea objetos de cuentas y usuarios relacionados. */
$Clave = str_replace("'", "", $Clave);
$CuentaCobro = new CuentaCobro($IdNota, "", $Clave);

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$Usuario = new  Usuario($CuentaCobro->getUsuarioId());

try {
    $Clasificador = new Clasificador('', 'CONTINGENCIARETIROSRETAIL');
    $UsuarioConfiguracion = new UsuarioConfiguracion($CuentaCobro->getUsuarioId(), 'A', $Clasificador->getClasificadorId());
    $IsActivateContingencyRetailWithdrawals = $UsuarioConfiguracion->getEstado();

    // Verificación de contingencia de retiros retail
    if($IsActivateContingencyRetailWithdrawals == 'A') {
        throw new Exception("Este usuario no puede usar puntos de venta o red aliadas, comuníquese con soporte.", 300152);
    }
} catch (Exception $e) {
    if($e->getCode() == 300152) throw $e;
}

$Bonointerno = new BonoInterno();
$BonointernoMySqlDAO = new BonoInternoMySqlDAO();
$transaccion = $BonointernoMySqlDAO->getTransaction();
$sqlQuery2 = "SET @@SESSION.block_encryption_mode = 'aes-128-ecb';";
$Bonointerno->execQuery($transaccion,$sqlQuery2);


/* Se obtienen registros de cuentas de cobro en formato JSON. */
$cuentas = $CuentaCobro->getCuentasCobroCustom("usuario.nombre,cuenta_cobro.cuenta_id,cuenta_cobro.usuario_id,cuenta_cobro.usucambio_id,cuenta_cobro.observacion,cuenta_cobro.mensaje_usuario,usuario.login,cuenta_cobro.fecha_crea,cuenta_cobro.valor,cuenta_cobro.mediopago_id,usuario.moneda,cuenta_cobro.puntoventa_id,punto_venta.descripcion puntoventa,cuenta_cobro.mediopago_id, banco.descripcion banco_nombre,cuenta_cobro.estado,usuario_banco.cuenta", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json, true, "cuenta_cobro.cuenta_id");

$sqlQuery2 = "SET @@SESSION.block_encryption_mode = 'aes-128-cbc';";

$Bonointerno->execQuery($transaccion,$sqlQuery2);



$cuentas = json_decode($cuentas);
if ($Usuario->paisId == $UsuarioPuntoVenta->paisId && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {


    /* valida el estado y medio de pago de CuentaCobro, lanzando excepciones si fallan. */
    if ($CuentaCobro->getEstado() != "A") {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($CuentaCobro->getMediopagoId() != "0") {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    /* Se declara un arreglo vacío en PHP, listo para almacenar valores. */
    $final = array();
    foreach ($cuentas->data as $key => $value) {


        /* crea un array asignando valores de un objeto a claves específicas. */
        $array = [];

        $array["Id"] = $value->{"cuenta_cobro.cuenta_id"};
        $array["ClientId"] = $value->{"cuenta_cobro.usuario_id"};
        $array["Email"] = $value->{"usuario.login"};
        $array["ClientName"] = $value->{"usuario.nombre"};

        /* Asigna valores de un objeto a un array asociativo y define método de pago. */
        $array["CreatedDate"] = $value->{"cuenta_cobro.fecha_crea"};

        $array["Stake"] = $value->{"cuenta_cobro.valor"};
        $array["Amount"] = $value->{"cuenta_cobro.valor"};

        $nombreMetodoPago = 'Efectivo';

        /* Asigna un estado de pago basado en la condición de una cuenta de cobro. */
        $idMetodoPago = 0;

        $estado = 'Pendiente de Pago';

        if ($value->{"cuenta_cobro.estado"} == "I") {
            $estado = 'Pagado';
            /* Verifica si el estado de cuenta de cobro es "R" para ejecutar condiciones específicas. */
        } elseif ($value->{"cuenta_cobro.estado"} == "R") {

            /* Asignación de estado y construcción de nombre de método de pago basado en condiciones. */
            $estado = 'Rechazado';
        }

        if ($value->{"banco.banco_nombre"} != '') {
            $nombreMetodoPago = $value->{"banco.banco_nombre"} . " - " . $value->{"usuario_banco.cuenta"};
        }


        /* asigna un método de pago si existe y actualiza un array. */
        if ($value->{"cuenta_cobro.metodopago_id"} != '') {
            $idMetodoPago = $value->{"cuenta_cobro.metodopago_id"};
        }

        $array["PaymentSystemName"] = $nombreMetodoPago;
        $array["PaymentSystemId"] = $idMetodoPago;

        /* Se asignan valores a un arreglo para un registro de pago en sistema. */
        $array["TypeName"] = "Payment";

        $array["ToCurrencyId"] = $value->{"cuenta_cobro.moneda"};
        $array["CashDeskId"] = $value->{"cuenta_cobro.puntoventa_id"};
        $array["CashDeskId"] = $value->{"cuenta_cobro.puntoventa_id"};
        $array["Cashdesk"] = $value->{"punto_venta.descripcion"};

        /* asigna valores a un array usando propiedades de un objeto. */
        $array["BetshopId"] = $value->{"cuenta_cobro.puntoventa_id"};
        $array["BetShopName"] = $value->{"punto_venta.puntoventa"};
        $array["Cashdesk"] = $value->{"punto_venta.puntoventa"};
        $array["RejectUserName"] = $value->{"cuenta_cobro.usurechaza_id"};
        $array["AllowUserName"] = $value->{"cuenta_cobro.usucambio_id"};
        $array["PaidUserName"] = $value->{"cuenta_cobro.usupago_id"};

        /* Se asignan valores a un array desde un objeto con datos de cuenta de cobro. */
        $array["Notes"] = $value->{"cuenta_cobro.mensaje_usuario"};
        $array["RejectReason"] = $value->{"cuenta_cobro.observacion"};
        $array["StateName"] = $estado;
        $array["State"] = $value->{"cuenta_cobro.estado"};
        $array["StateId"] = $value->{"cuenta_cobro.estado"};
        $array["Note"] = "";

        /* Se crea un array con datos de pago y se agrega a un array final. */
        $array["ExternalId"] = "";
        $array["PaymentDocumentId"] = "";


        $array2["PaymentDocumentData"] = $array;
        array_push($final, $array);
    }


    /* configura una respuesta sin errores y con datos finales. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["data"] = $final;
} else {
    /* Lanza una excepción con un mensaje y un código específico en caso de error. */

    throw new Exception("Error General", "100000");

}
