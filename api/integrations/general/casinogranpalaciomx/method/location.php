<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con la obtención de información
 * sobre puntos de venta (BetShops) en un rango de fechas específico. Incluye validaciones de token JWT,
 * manejo de parámetros de entrada y generación de respuestas en formato JSON.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow                  Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $dateFrom                 Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $rules                    Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                   Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonbetshop              Esta variable contiene datos en formato JSON específicos para la integración con Betshop.
 * @var mixed $PuntoVenta               Variable que almacena información sobre un punto de venta.
 * @var mixed $mandantes                Variable que almacena una lista de mandantes o responsables.
 * @var mixed $finalBetShops            Variable que contiene información sobre las casas de apuestas finales.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 */

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


//$UsuarioMandante = new UsuarioMandante($userNow);
$Mandante = new Mandante(4);

/**
 * Obtiene los parámetros de entrada de la solicitud HTTP.
 */
$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');
$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();

/**
 * Determina el usuario según el entorno de configuración.
 */
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'casinogranpalaciomxVS';
} else {
    $usuario = 'casinogranpalaciomxVS';
}

/**
 * Genera el encabezado y el payload del token JWT.
 */
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'casinogranpalaciomxVS';

/**
 * Genera la firma y el token JWT.
 */
$signature = hash('sha256', $header . $payload . $key);
$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

/**
 * Obtiene el token de los encabezados HTTP.
 */
$TokenHeader = $headers["token"];
if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

/**
 * Valida el token y procesa la solicitud si es válido.
 */
if ($TokenHeader === $token) {
    /**
     * Extrae los parámetros de la solicitud.
     */
    $dateFrom = $params->fromDate;
    $dateTo = $params->toDate;

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    $MaxRows = $_REQUEST["count"];
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 100;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000;
    }

    /**
     * Construye las reglas de filtrado para la consulta.
     */
    $rules = [];
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));
    array_push($rules, array("field" => "usuario.mandante", "data" => "$Mandante->mandante", "op" => "eq"));


    if ($dateFrom != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));
        //array_push($rules, array("field" => "usuario.fecha_actualizacion", "data" => "$dateFrom", "op" => "ge"));

    }
    if ($dateTo != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));
    }

    array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));


    array_push($rules, array("field" => "usuario.pais_id", "data" => "146", "op" => "eq"));

    array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonbetshop = json_encode($filtro);


    $PuntoVenta = new PuntoVenta();

    /**
     * Realiza la consulta de puntos de venta con los filtros aplicados.
     */
    $mandantes = $PuntoVenta->getPuntoVentasCustom("usuario.estado_valida,usuario.fecha_crea,usuario.fecha_actualizacion,usuario.moneda,usuario.fecha_ult,punto_venta.*", "punto_venta.puntoventa_id", "asc", 0, 100000, $jsonbetshop, true);

    $mandantes = json_decode($mandantes);
    if ($mandantes->count[0]->{".count"} != "0") {
        $finalBetShops = [];

        /**
         * Procesa los datos de los puntos de venta y los almacena en un arreglo.
         */
        foreach ($mandantes->data as $key => $value) {
            $array = [];

            $array["LocationId"] = $value->{"punto_venta.usuario_id"};
            $array["LocationName"] = $value->{"punto_venta.descripcion"};
            $array["LocationAddress"] = $value->{"punto_venta.direccion"};
            $array["TicketValidity"] = 90; //Días de vigencia de los tickets
            $array["DefaultCurrency"] = $value->{"punto_venta.moneda"};
            $array["ExchangeRate"] = 1; //Tipo de cambio vigente de la sala
            $array["BetTaxRate"] = 0;//Porcentaje de impuesto sobre apuesta vigente //
            $array["WinTaxBase"] = "WIN";
            $array["WinTaxRate"] = $value->{"punto_venta.impuesto_pagopremio"};
            $array["RegisterDate"] = $value->{"usuario.fecha_crea"};
            $array["UpdatedDate"] = $value->{"usuario.fecha_actualizacion"};


            array_push($finalBetShops, $array);
        }

        /**
         * Genera la respuesta con los datos obtenidos.
         */
        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = intval($mandantes->count[0]->{".count"});
        $response["Data"] = $finalBetShops;
    } else {
        /**
         * Respuesta en caso de no encontrar puntos de venta en el rango de fechas.
         */
        $response["Error"] = false;
        $response["Mensaje"] = "No hay salas creadas en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    /**
     * Lanza una excepción si el token no es válido.
     */
    throw new Exception("Usuario no coincide con token", "30012");
}






