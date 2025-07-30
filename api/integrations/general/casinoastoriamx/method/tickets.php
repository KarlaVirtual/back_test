<?php

/**
 * Este archivo contiene un script para procesar y generar información relacionada con tickets y transacciones
 * en un sistema de apuestas. Incluye la validación de tokens, consultas a la base de datos y la generación
 * de respuestas en formato JSON.
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
 * @var mixed $statusInit               Variable que representa el estado inicial de una operación o transacción.
 * @var mixed $statusEnd                Variable que representa el estado final de una operación o transacción.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $transaccionJuego         Variable que representa una transacción específica de juego.
 * @var mixed $TransaccionJuegoMySqlDAO Objeto que maneja operaciones de base de datos para transacciones de juego en MySQL.
 * @var mixed $apuestas                 Variable que almacena un conjunto de apuestas realizadas.
 * @var mixed $apuestasData             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $array2                   Variable que almacena otra lista de datos.
 * @var mixed $state                    Variable que almacena el estado actual de un elemento o proceso.
 * @var mixed $premiado                 Variable que indica si una apuesta ha resultado ganadora.
 * @var mixed $WinTaxType               Variable que representa el tipo de impuesto aplicado a las ganancias de apuestas.
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

$Mandante = new Mandante(7);

$params = file_get_contents('php://input');
$params = json_decode($params);

$dateFrom = date("Y-m-d H:i:s", strtotime($params->fromDate));
$dateTo = date("Y-m-d H:i:s", strtotime($params->toDate));
header('Content-Type: application/json');
/*error_reporting(E_ALL);
ini_set("display_errors","ON");*/
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
        "SELECT * FROM (SELECT it_ticket_enc.ticket_id,
       it_ticket_enc.usuario_id,
       it_ticket_enc.bet_status,
       it_ticket_enc.fecha_crea,it_ticket_enc.fecha_crea_time,it_ticket_enc.fecha_cierre_time,it_ticket_enc.fecha_pago_time,
       usuario.nombre AS Nombre_Usuario,
       it_ticket_enc.fecha_cierre,
       it_ticket_enc.fecha_pago,
       usuario_punto_pago.nombre,
       it_ticket_enc.fecha_modifica,
       it_ticket_enc.fecha_maxpago,
       it_ticket_enc.vlr_apuesta,
       it_ticket_enc.vlr_premio, usuario.moneda,usuario_perfil.perfil_id,punto_venta.impuesto_pagopremio,it_ticket_enc.impuesto
                      
FROM it_ticket_enc
         INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
         INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)

         LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)
            LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id = usuario.puntoventa_id)
WHERE 1 = 1
  AND (it_ticket_enc.fecha_crea_time >= '$dateFrom'
    AND it_ticket_enc.fecha_crea_time <= '$dateTo')
    AND usuario.mandante = '7'
     UNION
SELECT it_ticket_enc.ticket_id,
       it_ticket_enc.usuario_id,
       it_ticket_enc.bet_status,
       it_ticket_enc.fecha_crea,it_ticket_enc.fecha_crea_time,it_ticket_enc.fecha_cierre_time,it_ticket_enc.fecha_pago_time,
       usuario.nombre AS Nombre_Usuario,
       it_ticket_enc.fecha_cierre,
       it_ticket_enc.fecha_pago,
       usuario_punto_pago.nombre,
       it_ticket_enc.fecha_modifica,
       it_ticket_enc.fecha_maxpago,
       it_ticket_enc.vlr_apuesta,
       it_ticket_enc.vlr_premio, usuario.moneda,usuario_perfil.perfil_id,punto_venta.impuesto_pagopremio,it_ticket_enc.impuesto
FROM it_ticket_enc
         INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
        INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)

         LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)
            LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id = usuario.puntoventa_id)
WHERE 1 = 1
  AND (it_ticket_enc.fecha_pago_time >= '$dateFrom'
        AND it_ticket_enc.fecha_pago_time <= '$dateTo')
  AND usuario.mandante = '7'
    AND premiado = 'S'
  AND premio_pagado = 'S'
    AND it_ticket_enc.estado != ''
UNION
SELECT it_ticket_enc.ticket_id,
       it_ticket_enc.usuario_id,
       it_ticket_enc.bet_status,
       it_ticket_enc.fecha_crea,it_ticket_enc.fecha_crea_time,it_ticket_enc.fecha_cierre_time,it_ticket_enc.fecha_pago_time,
       usuario.nombre AS Nombre_Usuario,
       it_ticket_enc.fecha_cierre,
       it_ticket_enc.fecha_pago,
       usuario_punto_pago.nombre,
       it_ticket_enc.fecha_modifica,
       it_ticket_enc.fecha_maxpago,
       it_ticket_enc.vlr_apuesta,
       it_ticket_enc.vlr_premio, usuario.moneda,usuario_perfil.perfil_id,punto_venta.impuesto_pagopremio,it_ticket_enc.impuesto
FROM it_ticket_enc
         INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
         INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = it_ticket_enc.usumodifica_id)
         LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id = usuario.puntoventa_id)
WHERE 1 = 1
  AND (it_ticket_enc.fecha_cierre_time >= '$dateFrom'
        AND it_ticket_enc.fecha_cierre_time <= '$dateTo')
  AND usuario.mandante = '7') it_ticket_enc
GROUP BY ticket_id ";


    $ItTicketEnc = new ItTicketEnc();

    $ItTicketEncMySqlDAO = new \Backend\mysql\ItTicketEncMySqlDAO();
    $transaccion = $ItTicketEncMySqlDAO->getTransaction();
    $Tickets = $ItTicketEnc->execQuery($transaccion, $sql);

    $Tickets = json_decode(json_encode($Tickets), true);

    foreach ($Tickets as $key => $value) {
        $statusInit = $value["it_ticket_enc.bet_status"];


        $statusEnd = "";

        switch ($statusInit) {
            case 'N':
                $statusEnd = "Loser";
                break;
            case 'S':
                $statusEnd = "Won";
                if ($value["it_ticket_enc.fecha_maxpago"] > date("Y-m-d")) {
                    $statusEnd = "Expired";
                }
                break;
            case 'T':
                $statusEnd = "Cashed";
                break;
            case 'J':
                $statusEnd = "Cancel";
                break;
            case 'M':
                $statusEnd = "Cancel";
                break;
            case 'D':
                $statusEnd = "Cancel";
                break;
            case 'A':
                $statusEnd = "Expired";
                break;

            default:
                $statusEnd = "Sold";
                break;
        }

        $array["TicketNumber"] = $value["it_ticket_enc.ticket_id"];
        $array["LocationId"] = $value["it_ticket_enc.usuario_id"];
        if ($value["usuario.puntoventa_id"] != '' && $value["usuario.puntoventa_id"] != '0') {
            $array["LocationId"] = $value["usuario.puntoventa_id"];
        }
        $array["ETSN"] = $value["it_ticket_enc.ticket_id"];
        $array["TicketStatus"] = $statusEnd;
        $array["TicketType"] = "D";
        $array["SoldDate"] = $value["it_ticket_enc.fecha_crea_time"];
        $array["SoldTerminal"] = '';
        $array["TransactionType"] = "Online";


        $array["SettledDate"] = $value["it_ticket_enc.fecha_cierre_time"];
        $array["CashedDate"] = $value["it_ticket_enc.fecha_pago_time"];
        $array["CashedTerminal"] = $value["it_ticket_enc.nombre"];
        $array["VoidedDate"] = $value["it_ticket_enc.fecha_cierre_time"];
        $array["ExpiredDate"] = $value["it_ticket_enc.fecha_maxpago"];
        $array["SoldValue"] = $value["it_ticket_enc.vlr_apuesta"];
        $array["RefundValue"] = in_array($value["it_ticket_enc.bet_status"], array('J', 'M', 'D', 'A')) ? $value["it_ticket_enc.vlr_premio"] : 0;
        $array["SettledValue"] = strval(floatval($value["it_ticket_enc.vlr_premio"]) - floatval($value["it_ticket_enc.impuesto"]));
        $array["BetTaxRate"] = 0;
        $array["BetTaxValue"] = 0;
        $array["WinTaxType"] = '';


        if ($value["it_ticket_enc.perfil_id"] != 'USUONLINE') {
            $array["SoldTerminal"] = $value["it_ticket_enc.Nombre_Usuario"];
            $array["TransactionType"] = "Retail";
            $array["WinTaxType"] = 'WIN';
        } else {
            $array["LocationId"] = '0';
        }

        if (in_array($value["it_ticket_enc.bet_status"], array('J', 'M', 'D', 'A'))) {
            $array["WinValue"] = 0;
            $array["WinTaxRate"] = 0;
            $array["WinTaxValue"] = 0;
        } else {
            $array["WinValue"] = $value["it_ticket_enc.vlr_premio"];
            $array["WinTaxRate"] = $value["it_ticket_enc.impuesto_pagopremio"];
            $array["WinTaxValue"] = $value["it_ticket_enc.impuesto"];
        }
        $array["UserId"] = $value["it_ticket_enc.usuario_id"];
        $array["BalanceType"] = "Saldo";
        $array["Currency"] = $value["it_ticket_enc.moneda"];

        array_push($final, $array);
    }

    $sql =
        "SELECT tj.*,
       producto.descripcion,usuario_mandante.moneda,usuario_mandante.nombres,usuario_mandante.usuario_mandante,
       tl.tipo,tl.fecha_crea,tl.fecha_modif,
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
        $state = $value["tj.estado"];
        $premiado = $value["tj.premiado"];

        $state = "Settled";

        $WinTaxType = "WIN";


        $array2["TicketNumber"] = $value["tj.ticket_id"];
        $array2["LocationId"] = '0';
        $array2["ETSN"] = $value["tj.ticket_id"];
        $array2["TicketStatus"] = $state;
        $array2["TicketType"] = "C";
        $array2["SoldDate"] = $value["tl.fecha_crea"];
        $array2["SoldTerminal"] = '';
        $array2["SettledDate"] = $value["tl.fecha_crea"];
        $array2["CashedDate"] = $value["tj.fecha_pago"];
        $array2["CashedTerminal"] = "";
        $array2["VoidedDate"] = $value["tl.fecha_modif"];
        $array2["ExpiredDate"] = $value["tj.fecha_pago"];
        $array2["SoldValue"] = $value["tj.valor_ticket"];
        $array2["RefundValue"] = 0;
        $array2["SettledValue"] = $value["tj.valor_ticket"];
        $array2["BetTaxRate"] = 0;
        $array2["BetTaxValue"] = 0;
        $array2["WinTaxType"] = $WinTaxType;
        $array2["WinTaxRate"] = 0;
        $array2["WinTaxValue"] = 0;
        $array2["WinValue"] = $value["tj.valor_premio"];
        $array2["UserId"] = $value["usuario_mandante.usuario_mandante"];
        $array2["TransactionType"] = "Online";
        $array2["BalanceType"] = "Saldo";
        $array2["Currency"] = $value["usuario_mandante.moneda"];


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


