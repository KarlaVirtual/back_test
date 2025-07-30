<?php

/**
 * Este archivo contiene la implementación de un script para procesar transacciones
 * de cajeros en el sistema del Casino Miravalle Palace.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
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
 * @var mixed $TransactionType          Variable que indica el tipo de transacción realizada.
 * @var mixed $UsuarioRecarga           Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsarioId                 Variable que almacena el identificador único de un usuario.
 * @var mixed $Descripcion              Variable que almacena la descripción de un evento o transacción.
 * @var mixed $Valor                    Variable que almacena el valor monetario de una operación.
 * @var mixed $FechaCrea                Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $MovementId               Variable que almacena el identificador de un movimiento financiero.
 * @var mixed $CuentaCobro              Variable que almacena información sobre una cuenta de cobro.
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

$Mandante = new Mandante(3);

$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');
$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'casinomiravallepalaceVS';
} else {
    $usuario = 'casinomiravallepalaceVS';
}
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'casinomiravallepalaceVS';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token) {
    $dateFrom = $params->fromDate;
    $dateTo = $params->toDate;
    $dateFrom = date("Y-m-d H:i:s", strtotime($params->fromDate));

    $dateTo = date("Y-m-d H:i:s", strtotime($dateTo));

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
        $MaxRows = 100;
    }

    $rules = [];
    array_push($rules, array("field" => "d.mandante", "data" => "$Mandante->mandante", "op" => "eq"));


    if ($dateFrom != "") {
    }
    if ($dateTo != "") {
    }

    array_push($rules, array("field" => "d.estado", "data" => "A", "op" => "eq"));


    array_push($rules, array("field" => "d.pais_id", "data" => "146", "op" => "eq"));

    array_push($rules, array("field" => "d.eliminado", "data" => "N", "op" => "eq"));

    array_push($rules, array("field" => "x.ticket_id", "data" => "0", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonbetshop = json_encode($filtro);


    $PuntoVenta = new PuntoVenta();


    $mandantes = $PuntoVenta->getFlujoCajaConCajero2("", "x.flujocaja_id", "asc", $SkeepRows, $MaxRows, $jsonbetshop, true, "", $dateFrom, $dateTo, "", "", "");


    $mandantes = json_decode($mandantes);
    if ($mandantes->count[0]->{".count"} != "0") {
        $finalBetShops = [];

        foreach ($mandantes->data as $key => $value) {
            $array = [];
            $TransactionType = "Retail";

            if ($value->{"x.recarga_id"} != 0) {
                $UsuarioRecarga = new UsuarioRecarga($value->{"x.recarga_id"});

                $UsarioId = $UsuarioRecarga->getUsuarioId();
                $Descripcion = "DEPOSITO";
                $Valor = $UsuarioRecarga->getValor();
                $FechaCrea = $value->{"x.fecha_crea"};
                $MovementId = "D" . $value->{"x.flujocaja_id"};
            }

            if ($value->{".ticket_id"} != 0) {
                $UsarioId = $value->{"x.usucrea_id"};
                $Descripcion = "APUESTA DEPORTIVA";
                $Valor = $value->{".valor_entrada"};
                $FechaCrea = $value->{"x.fecha_crea"};
                $MovementId = "A" . $value->{"x.flujocaja_id"};
                if ($value->{"x.tipomov_id"} == 'S') {
                    $Descripcion = "PREMIO";

                    $Valor = $value->{".valor_salida"};
                }
            }

            if ($value->{"x.cuenta_id"} != 0) {
                $CuentaCobro = new CuentaCobro($value->{"x.cuenta_id"});

                $UsarioId = $CuentaCobro->getUsuarioId();
                $Descripcion = "RETIRO";
                $Valor = $CuentaCobro->getValor();
                $FechaCrea = $value->{"x.fecha_crea"};
                $MovementId = "R" . $value->{"x.flujocaja_id"};
            }

            $MovementId = $value->{"x.flujocaja_id"};

            $array["MovementId"] = $MovementId;
            $array["CashierName"] = $value->{"d.puntoventa"};
            $array["Type"] = $Descripcion;
            $array["TransactionDate"] = $FechaCrea;
            $array["UserId"] = $UsarioId;
            $array["Description"] = $Descripcion;
            $array["MovementType"] = "Saldo";
            $array["TransactionType"] = $TransactionType;
            $array["Amount"] = $Valor;
            $array["Currency"] = $value->{"d.moneda"};

            array_push($finalBetShops, $array);
        }

        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = intval($mandantes->count[0]->{".count(*)"});
        $response["Data"] = $finalBetShops;
    } else {
        $response["Error"] = true;
        $response["Mensaje"] = "No hay movimientos en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    throw new Exception("Usuario no coincide con token", "30012");
}
