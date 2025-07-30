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
 * Obtener los juegos de casino de un cliente.
 *
 * Este script consulta las transacciones de juegos de casino realizadas por un cliente
 * en un rango de fechas y devuelve los datos en un formato estructurado.
 *
 * @param object $params
 * @param string $params ->FromDateLocal Fecha de inicio del rango (formato local).
 * @param string $params ->ToDateLocal Fecha de fin del rango (formato local).
 * @param string $params ->ClientId ID del cliente.
 * @param string $params ->Currency Moneda utilizada.
 * @param boolean $params ->IsDetails Indica si se deben incl
 * @param int $params ->MaxRows Número máximo de filas a devolver.
 * @param int $params ->OrderedItem Orden de los elementos.
 * @param int $params ->SkeepRows Número de filas a omitir.
 *
 *
 *
 * @return array $response
 *   - HasError: boolean Indica si ocurrió un error.
 *   - AlertType: string Tipo de alerta (por ejemplo, "success").
 *   - AlertMessage: string Mensaje de alerta.
 *   - ModelErrors: array Lista de errores del modelo (vacío si no hay errores).
 *   - Data: array Contiene los datos de las transacciones de juegos de casino.
 *
 * @throws Exception No se lanzan excepciones explícitas en este script.
 */


/* obtiene y decodifica datos JSON desde una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$FromDateLocal = $params->FromDateLocal;
$ToDateLocal = $params->ToDateLocal;

/* asigna parámetros de cliente y configuraciones a variables. */
$ClientId = $params->ClientId;
$Currency = $params->Currency;
$IsDetails = $params->IsDetails;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* inicializa variables según condiciones específicas para su procesamiento posterior. */
$SkeepRows = $params->SkeepRows;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor por defecto y crea un objeto de UsuarioMandante. */
if ($MaxRows == "") {
    $MaxRows = 10000;
}

$UsuarioMandante = new UsuarioMandante("", $ClientId, "0");


$rules = [];


/* Crea filtros de búsqueda para transacciones de juegos y los codifica en JSON. */
array_push($rules, array("field" => "transaccion_juego.usuario_id", "data" => "$UsuarioMandante->usumandanteId", "op" => "eq"));
array_push($rules, array("field" => "transaccion_juego.fecha_modif", "data" => "$FromDateLocal ", "op" => "ge"));
array_push($rules, array("field" => "transaccion_juego.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);


/* Establece la localización a checo y configura una consulta SQL condicionalmente. */
setlocale(LC_ALL, 'czech');

if (!$IsDetails) {
    $grouping = "producto.producto_id";
    $select = " producto.*, COUNT(transaccion_juego.transjuego_id) count,SUM(transaccion_juego.valor_ticket) apuestas, SUM(transaccion_juego.valor_premio) premios,proveedor.* ";
} else {
    /* Seleccione información de productos y transacciones, incluyendo detalles del proveedor. */

    $select = " producto.*, transaccion_juego.transjuego_id,transaccion_juego.usuario_id, 1 count,transaccion_juego.valor_ticket,transaccion_juego.valor_premio,proveedor.* ";
}


/* Se crea una transacción de juego, se obtiene y procesa datos en formato JSON. */
$TransaccionJuego = new TransaccionJuego();
$data = $TransaccionJuego->getTransaccionesCustom($select, "transaccion_juego.transjuego_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);
$final = [];

foreach ($data->data as $key => $value) {


    /* crea un arreglo condicional con información de transacciones de juego. */
    $array = [];

    if ($IsDetails) {
        $array["Id"] = $value->{"transaccion_juego.transjuego_id"};
        $array["Game"] = $value->{"producto.descripcion"};
        $array["ProviderName"] = $value->{"proveedor.descripcion"};
        $array["Bets"] = $value->{".count"};
        $array["Stakes"] = $value->{"transaccion_juego.valor_ticket"};
        $array["Winnings"] = $value->{"transaccion_juego.valor_premio"};
        $array["Profitness"] = ($array["Stakes"] - $array["Winnings"]) / ($array["Stakes"]) * 100;
        $array["Profit"] = 0;
    } else {
        /* extrae y calcula información sobre juegos y apuestas. */

        $array["Game"] = $value->{"producto.descripcion"};
        $array["ProviderName"] = $value->{"proveedor.descripcion"};
        $array["Bets"] = $value->{".count"};
        $array["Stakes"] = $value->{".apuestas"};
        $array["Winnings"] = $value->{".premios"};
        $array["Profitness"] = ($array["Stakes"] - $array["Winnings"]) / ($array["Stakes"]) * 100;
        $array["Profit"] = 0;

    }


    /* Añade el contenido de `$array` al final del array `$final`. */
    array_push($final, $array);

}


/* Código que configura una respuesta sin errores, con mensaje de éxito y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;
