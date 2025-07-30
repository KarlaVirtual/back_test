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
 * Report/GetCasinoGamesReportDetail
 *
 * Obtiene el detalle de transacciones de juegos de casino
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "data": array {
 *     "Id": int,
 *     "TransactionId": string,
 *     "Amount": float,
 *     "Type": string,
 *     "Value": string,
 *     "Response": string,
 *     "Code": string,
 *     "CreateLocalDate": datetime
 *   },
 *   "pos": int,
 *   "total_count": int
 * }
 */

// Inicializa el objeto Usuario y obtiene el ID de la solicitud
$Usuario = new Usuario();
$Id = intval($_REQUEST["id"]);

if ($Id != "") {
    // Obtiene los objetos TransaccionJuego y Mandante relacionados con el ID
    $TransaccionJuego = new TransaccionJuego($Id);
    $Mandante = new Mandante ($TransaccionJuego->getMandante());

    if ($Mandante->propio == "S") {
        // Para mandantes propios, configura las reglas de filtrado para logs de transacciones
        $rules = [];
        array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $Id, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        // Define los campos a seleccionar y el agrupamiento
        $select = "transjuego_log.*";
        $grouping = "transjuego_log.transjuegolog_id";

        // Obtiene los logs de transacciones de juego
        $TransjuegoLog = new TransjuegoLog();
        $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);

        // Inicializa arreglos y contadores para procesar los datos
        $final = [];
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        // Procesa cada registro de log y construye el array de respuesta
        foreach ($data->data as $key => $value) {
            $CurrencyId = $value->{"usuario_mandante.moneda"};
            $array = [];

            // Asigna los valores del log a la estructura de respuesta
            $array["Id"] = $value->{"transjuego_log.transjuegolog_id"};
            $array["TransactionId"] = $value->{"transjuego_log.transaccion_id"};
            $array["Amount"] = $value->{"transjuego_log.valor"};
            $array["Type"] = $value->{"transjuego_log.tipo"};
            $array["Value"] = $value->{"transaccion_api.t_value"};
            $array["Response"] = $value->{"transaccion_api.respuesta"};
            $array["Code"] = $value->{"transaccion_api.respuesta_codigo"};
            $array["CreateLocalDate"] = $value->{"transaccion_api.fecha_crea"};

            array_push($final, $array);
        }
    } else {
        // Para mandantes externos, configura las reglas para transacciones API
        $rules = [];
        array_push($rules, array("field" => "transaccion_api.identificador", "data" => $TransaccionJuego->getTicketId(), "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        // Define los campos a seleccionar y el agrupamiento para transacciones API
        $select = "transaccion_api_mandante.*,transaccion_api.*,usuario_mandante.*";
        $grouping = "transaccion_api_mandante.transapimandante_id";

        // Obtiene las transacciones API del mandante
        $TransaccionApiMandante = new TransaccionApiMandante();
        $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api_mandante.transapimandante_id", "asc", 0, 100, $json, true, $grouping);
        $data = json_decode($data);

        // Inicializa arreglos y contadores para procesar los datos
        $final = [];
        $papuestas = 0;
        $ppremios = 0;
        $pcont = 0;

        // Procesa cada transacción API y construye el array de respuesta
        foreach ($data->data as $key => $value) {
            $CurrencyId = $value->{"usuario_mandante.moneda"};
            $array = [];

            // Asigna los valores de la transacción API a la estructura de respuesta
            $array["Id"] = $value->{"transaccion_api_mandante.transapimandante_id"};
            $array["TransactionId"] = $value->{"transaccion_api_mandante.transapimandante_id"};
            $array["Amount"] = $value->{"transaccion_api_mandante.valor"};
            $array["Type"] = $value->{"transaccion_api_mandante.tipo"};
            $array["Value"] = $value->{"transaccion_api_mandante.t_value"};
            $array["Response"] = $value->{"transaccion_api_mandante.respuesta"};
            $array["Code"] = $value->{"transaccion_api_mandante.respuesta_codigo"};
            $array["CreateLocalDate"] = $value->{"transaccion_api_mandante.fecha_crea"};

            array_push($final, $array);
        }
    }

    // Prepara la respuesta exitosa con los datos procesados
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = 0;
    $response["total_count"] = oldCount($final);
    $response["data"] = $final;
} else {
    // Prepara una respuesta vacía cuando no hay ID
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
