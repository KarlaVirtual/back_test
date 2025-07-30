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
use Backend\dto\Consecutivo;use Backend\dto\ConfigurationEnvironment;
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
 * Report/GetClientBonusReportDetail
 *
 * Obtiene el detalle de bonos de un usuario específico
 *
 * @param array $params {
 *   "id": int           // ID del bono a consultar
 * }
 * 
 * @return array {
 *   "HasError": boolean,         // Indica si hubo error
 *   "AlertType": string,         // Tipo de alerta (success, error)
 *   "AlertMessage": string,      // Mensaje de alerta
 *   "ModelErrors": array,        // Errores del modelo
 *   "data": array {
 *     "Id": int,                 // ID del bono
 *     "Date": string,            // Fecha de creación
 *     "Type": string,            // Tipo de registro
 *     "Amount": float,           // Monto del bono
 *     "Description": string      // Descripción del registro
 *   }[],
 *   "pos": int,                  // Posición actual
 *   "total_count": int          // Total de registros
 * }
 */

// Obtiene el ID del bono desde la solicitud
$Id = $_REQUEST["id"];

// Configura parámetros de paginación iniciales
$MaxRows = 1;
$OrderedItem = 1; 
$SkeepRows = 0;

// Construye reglas de filtrado para obtener el bono específico
$rules = [];
array_push($rules, array("field" => "usuario_bono.usubono_id", "data" => "$Id", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

// Define los campos a seleccionar de la base de datos
$select = " usuario_bono.*,usuario.moneda,bono_interno.* ";

// Obtiene los datos del bono del usuario
$UsuarioBono = new UsuarioBono();
$data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, "usuario_bono.usubono_id");

$data = json_decode($data);

// Inicializa el arreglo para almacenar resultados
$final = array();

// Obtiene el primer registro de datos
$value = $data->data[0];

// Procesa los datos del bono si existen
if ($value != "") {
    $array = array();

    // Construye el registro con la información del bono
    $array["Id"] = $value->{"usuario_bono.usubono_id"};
    $array["Date"] = $value->{"usuario_bono.fecha_crea"};
    $array["Type"] = "AsignacionBono";
    $array["Amount"] = floatval($value->{"usuario_bono.valor"});
    $array["Description"] = "Asignación del Bono";

    array_push($final, $array);

    $totalcount = $totalcount + $data->count[0]->{".count"};
}

// Prepara nuevas reglas para obtener el log del bono
$rules = [];
$SkeepRows=0;
$MaxRows=100;
array_push($rules, array("field" => "bono_log.id_externo", "data" => "$Id", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

// Define campos a seleccionar del log
$select = " bono_log.* ";

// Obtiene los registros de log del bono
$BonoLog = new BonoLog();
$data = $BonoLog->getBonoLogsCustom($select, "bono_log.bonolog_id", "asc", $SkeepRows, $MaxRows, $json, true, "bono_log.bonolog_id");

$data = json_decode($data);

$value = $data->data[0];

// Procesa cada registro del log del bono
foreach ($data->data as $value) {
    if ($value != "") {
        $array = array();

        // Construye el registro con la información del log
        $array["Id"] = $value->{"bono_log.bonolog_id"};
        $array["Date"] = $value->{"bono_log.fecha_crea"};
        $array["Type"] = "RedencionBono";
        $array["Amount"] = floatval($value->{"bono_log.valor"});
        $array["Description"] = "Bono Redimido";

        array_push($final, $array);

        $totalcount = $totalcount + $data->count[0]->{".count"};
    }
}

// Prepara la respuesta exitosa
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];

// Agrega información de paginación y datos a la respuesta
$response["pos"] = $SkeepRows;
$response["total_count"] = $totalcount;
$response["data"] = $final;