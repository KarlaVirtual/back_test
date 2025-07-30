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
 * UserManagement/GetUserAlerts
 *
 * Este script permite obtener las alertas de los usuarios con base en los parámetros proporcionados.
 *
 * @param object $params Objeto JSON con los siguientes campos:
 * @param string $params->Id ID del usuario.
 * @param integer $params->MaxRows Número máximo de registros a obtener.
 * @param integer $params->OrderedItem Orden de los registros.
 * @param integer $params->SkeepRows Número de registros a omitir.
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Errores del modelo.
 * - Data (array): Datos obtenidos, incluyendo:
 *   - Id (string): ID de la alerta.
 *   - PlayerId (string): ID del jugador.
 *   - Type (string): Tipo de alerta.
 *   - Query (array): Consultas asociadas.
 *   - Action (array): Acciones asociadas.
 *   - State (boolean): Estado de la alerta.
 *   - ColumnsQ (array): Columnas de consulta.
 *   - OperationsQ (array): Operaciones de consulta.
 *   - ColumnsA (array): Columnas de acción.
 *   - OperationsA (array): Operaciones de acción.
 *
 * @throws Exception Si ocurre un error durante el procesamiento de los datos.
 */


/* crea un objeto y obtiene un ID de parámetros JSON. */
$UsuarioAlerta = new UsuarioAlerta();

$params = file_get_contents('php://input');
$params = json_decode($params);


$Id = $params->Id;


/* asigna valores de parámetros y establece un valor predeterminado para $SkeepRows. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna valores por defecto a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se prepara un filtro en JSON para obtener alertas de usuarios personalizadas. */
$rules = [];


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $UsuarioAlerta->getUsuarioAlertasCustom("  usuario_alerta.* ", "usuario_alerta.usualerta_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* decodifica un JSON y prepara un arreglo final para usuarios. */
$usuarios = json_decode($usuarios);

$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {


    /* Se crea un arreglo asociativo a partir de un objeto con datos de alerta. */
    $array = [];

    $array["Id"] = $value->{"usuario_alerta.usualerta_id"};
    $array["PlayerId"] = $value->{"usuario_alerta.usuario_id"};
    $array["Type"] = $value->{"usuario_alerta.tipo"};
    $array["Query"] = json_decode($value->{"usuario_alerta.valor"});

    /* organiza datos en un array utilizando JSON y operadores de comparación. */
    $array["Action"] = json_decode($value->{"usuario_alerta.accion"});
    $array["State"] = $value->{"usuario_alerta.estado"};
    $array["ColumnsQ"] = array();
    $array["OperationsQ"] = ['>', '<', '<=', '>=', '==', '=', 'is'];
    $array["ColumnsA"] = array();
    $array["OperationsA"] = ['>', '<', '<=', '>=', '==', '=', 'is'];


    /* Convierte el estado "A" a verdadero; otros estados se establecen como falso. */
    if ($array["State"] == "A") {
        $array["State"] = true;
    } else {
        $array["State"] = false;

    }


    /* decodifica un JSON y almacena datos en un nuevo array. */
    $queries = json_decode($array["Query"]);
    foreach ($queries->operands as $query) {
        $arrayQ = array(
            "Id" => $query->colName->Id,
            "Name" => $query->colName->Name
        );
        array_push($array["ColumnsQ"], $arrayQ);
    }


    /* Decodifica un JSON y agrega columnas a un array estructurado. */
    $queries = json_decode($array["Action"]);
    foreach ($queries->operands as $query) {
        $arrayQ = array(
            "Id" => $query->colName->Id,
            "Name" => $query->colName->Name
        );
        array_push($array["ColumnsA"], $arrayQ);
    }


    /* Añade un array al final del array $usuariosFinal en PHP. */
    array_push($usuariosFinal, $array);

}


/* Código PHP para estructurar una respuesta sin errores y con datos de usuarios. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $usuariosFinal;
