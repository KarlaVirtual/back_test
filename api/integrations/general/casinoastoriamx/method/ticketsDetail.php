<?php

/**
 * Este archivo contiene un script para procesar y obtener detalles de tickets y transacciones
 * relacionadas con un sistema de apuestas. Incluye validaciones de token, consultas SQL para
 * recuperar datos de tickets y transacciones, y la generación de una respuesta en formato JSON.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Clase principal para manejar la lógica de obtención de detalles de tickets y transacciones.
 *
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow                  Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $dateFrom                 Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $final                    Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $ItTicketEnc              Variable que representa una entidad de ticket en el sistema.
 * @var mixed $ItTicketEncMySqlDAO      Objeto que maneja operaciones de base de datos para la entidad ItTicketEnc en MySQL.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $Tickets                  Variable que almacena un conjunto de tickets generados.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $transaccionJuego         Variable que representa una transacción específica de juego.
 * @var mixed $TransaccionJuegoMySqlDAO Objeto que maneja operaciones de base de datos para transacciones de juego en MySQL.
 * @var mixed $apuestas                 Variable que almacena un conjunto de apuestas realizadas.
 * @var mixed $apuestasData             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $array2                   Variable que almacena otra lista de datos.
 * @var mixed $aux                      Variable auxiliar utilizada para almacenar datos temporales.
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
$Mandante = new Mandante(7);

$params = file_get_contents('php://input');
$params = json_decode($params);

$dateFrom = date("Y-m-d H:i:s", strtotime($params->fromDate));
$dateTo = date("Y-m-d H:i:s", strtotime($params->toDate));
header('Content-Type: application/json');

$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'casinoastoriamxVS';
} else {
    $usuario = 'casinoastoriamxVS';
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

$key = 'casinoastoriamxVS';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token) {
    $final = array();

    $sql =
        "SELECT it_ticket_det.ticket_id,
       it_ticket_det.apuesta,
       it_ticket_det.logro,
       it_ticket_det.agrupador,
       it_ticket_det.opcion,
       it_ticket_det.fecha_evento,
       it_ticket_det.hora_evento
FROM it_ticket_det
         INNER JOIN it_ticket_enc ON (it_ticket_det.ticket_id = it_ticket_enc.ticket_id)
         INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
WHERE 1 = 1
  AND (it_ticket_enc.fecha_crea_time >= '$dateFrom'
    AND it_ticket_enc.fecha_crea_time <= '$dateTo')
    AND usuario.mandante = '7'
GROUP BY it_ticket_enc.ticket_id desc";


    $ItTicketEnc = new ItTicketEnc();

    $ItTicketEncMySqlDAO = new \Backend\mysql\ItTicketEncMySqlDAO();
    $transaccion = $ItTicketEncMySqlDAO->getTransaction();
//$transaccion->getConnection()->beginTransaction();
    $Tickets = $ItTicketEnc->execQuery($transaccion, $sql);


    $final = [];
    $Tickets = json_decode(json_encode($Tickets), true);


    foreach ($Tickets as $key => $value) {
        $array = [];

        $array["TicketNumber"] = $value["it_ticket_det.ticket_id"];
        $array["WagerHeader"] = $value["it_ticket_det.apuesta"];
        $array["WagerDetail"] = "Apuesta: " . $value["it_ticket_det.apuesta"] . " Logro: " . $value["it_ticket_det.logro"] . " Agrupador: " . $value["it_ticket_det.agrupador"] . " Opcíon: " . $value["it_ticket_det.opcion"];
        $array["EventDate"] = $value["it_ticket_det.fecha_evento"] . ' ' . $value["it_ticket_det.hora_evento"];
        $array["WagerSource"] = "";

        array_push($final, $array);
    }


    $sql =
        "SELECT tj.*,
       producto.descripcion,
       tl.tipo,
       (CASE
            WHEN tl.tipo LIKE 'DEBIT%' AND tl.tipo NOT LIKE '%FREESPIN' THEN tl.valor
            ELSE 0 END) AS Apuestas,
       (CASE
            WHEN (tl.tipo LIKE 'CREDIT%' OR tl.tipo LIKE 'ROLLBACK') AND
                 tl.tipo NOT LIKE '%FREESPIN' THEN tl.valor
            ELSE 0 END) AS Premios
FROM transjuego_log tl
         JOIN transaccion_juego tj ON tl.transjuego_id = tj.transjuego_id
         INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = tj.producto_id)
         INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
         INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = tj.usuario_id)
where 1 = 1
  AND (tl.fecha_crea >= '$dateFrom'
    AND tl.fecha_crea <= '$dateTo')
  AND usuario_mandante.mandante = '7'";

    $transaccionJuego = new TransaccionJuego();

    $TransaccionJuegoMySqlDAO = new \Backend\mysql\TransaccionJuegoMySqlDAO();
    $transaccion = $TransaccionJuegoMySqlDAO->getTransaction();
//$transaccion->getConnection()->beginTransaction();
    $apuestas = $ItTicketEnc->execQuery($transaccion, $sql);


    $apuestasData = array();

    $apuestas = json_decode(json_encode($apuestas), true);

    foreach ($apuestas as $key => $value) {
        $array2 = array();

        $array2["TicketNumber"] = $value["tj.ticket_id"];
        $array2["WagerHeader"] = $value["producto.descripcion"];
        $array2["WagerDetail"] = "Apuesta: " . $value["tj.ticket_id"] . " valor: " . $value["tj.valor_ticket"] . " premio: " . $value["tj.valor_premio"];
        $array2["EventDate"] = $value["tj.fecha_crea"];
        $array2["WagerSource"] = "";

        array_push($apuestasData, $array2);
    }


//Combinamos los arrays
    $aux = array_merge($apuestasData, $final);

    if ( ! empty($aux)) {
        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = intval(count($aux));
        $response["Data"] = $aux;
    } else {
        $response["Error"] = false;
        $response["Mensaje"] = "No hay tickets en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    throw new Exception("Usuario no coincide con token", "30012");
}


