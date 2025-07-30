<?php

/**
 * Consulta el movimiento de saldo más reciente para un usuario, organiza el contenido de una
 * notificación y la envía a través de WebSocket.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-19
 */

/**
 * Este script utiliza conexiones a bases de datos y servicios externos para consultar información
 * del usuario, procesar movimientos de saldo y enviar notificaciones en tiempo real.
 *
 * @var mixed $log                  Esta variable se utiliza para registrar mensajes y eventos de log en el sistema,
 *                                  facilitando la depuración y seguimiento.
 * @var mixed $argv                 Esta variable se utiliza para almacenar y manipular los argumentos pasados al
 *                                  script.
 * @var mixed $Usuario              Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $UsuarioRecarga       Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $CuentaCobro          Variable que almacena información sobre una cuenta de cobro.
 * @var mixed $Retiros              Variable que almacena información sobre retiros de fondos o bonos.
 * @var mixed $ItTicketDet          Variable que almacena detalles de un ticket en el sistema.
 * @var mixed $TransJuegoLog        Variable que almacena registros de transacciones de juego.
 * @var mixed $UsuarioBonoexec      Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $BonoLog              Variable que almacena registros de eventos relacionados con bonos.
 * @var mixed $SaldoUsuonlineAjuste Variable que almacena el ajuste del saldo de un usuario en línea.
 * @var mixed $UsuarioId            Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $Abreviado            Variable que almacena una versión abreviada de un valor o nombre.
 * @var mixed $IdMovimiento         Variable que almacena el identificador de un movimiento financiero o de juego.
 * @var mixed $Server               Variable que almacena información sobre el servidor en uso.
 * @var mixed $Ismobile             Variable que indica si la operación se realizó desde un dispositivo móvil.
 * @var mixed $Clasificador         Esta variable se utiliza para clasificar información dentro del sistema.
 * @var mixed $redisParam           Esta variable se utiliza para almacenar y manipular parámetros de Redis.
 * @var mixed $redisPrefix          Esta variable se utiliza para almacenar y manipular el prefijo utilizado en Redis.
 * @var mixed $redis                Esta variable se utiliza para almacenar y manipular instancias de Redis.
 * @var mixed $Crm                  Variable que almacena datos relacionados con la gestión de relaciones con clientes.
 * @var mixed $Response             Esta variable contiene la respuesta generada por una operación o solicitud.
 * @var mixed $data                 Esta variable contiene datos que se procesan o retornan, pudiendo incluir
 *                                  estructuras complejas (arrays u objetos).
 * @var mixed $rules                Esta variable contiene las reglas de validación o negocio, utilizadas para
 *                                  controlar el flujo de operaciones.
 * @var mixed $UsuarioMandante      Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $filters              Variable que almacena filtros aplicados en una consulta o búsqueda.
 * @var mixed $select               Variable que almacena una consulta o selección de datos.
 * @var mixed $UsuarioHistorial     Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $answer               Variable que almacena la respuesta de un proceso o servicio.
 * @var mixed $movimiento           Variable que almacena información sobre un movimiento o transacción.
 * @var mixed $UsuHistorialId       Variable que almacena el identificador del historial de un usuario.
 * @var mixed $balanceIncrease      Variable que almacena el incremento del balance de un usuario.
 * @var mixed $currency             Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $amount               Variable que almacena un monto o cantidad.
 * @var mixed $WebSocketUsuario     Esta variable representa la información del usuario, empleada para identificarlo
 *                                  dentro del sistema.
 * @var mixed $respuestaWS          Variable que almacena la respuesta de un servicio web.
 * @var mixed $e                    Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

require_once __DIR__ . '../../../../vendor/autoload.php';

use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\LealtadInterna;
use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRuleta;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\ConfigurationEnvironment;

$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    exit();
}

ini_set('display_errors', 'OFF');

// Obtiene los parámetros de entrada del script
$UsuarioId = $argv[1]; // ID del usuario
$Abreviado = $argv[2]; // Código abreviado para identificar la operación
$IdMovimiento = $argv[3]; // ID del movimiento
$Server = $argv[4]; // Información del servidor
$Ismobile = $argv[5]; // Indica si la operación se realizó desde un dispositivo móvil

// Crea una instancia del clasificador con el código abreviado
$Clasificador = new Clasificador("", $Abreviado);

// Verifica si el código abreviado corresponde a operaciones específicas y finaliza el script
if ($Abreviado == 'OPENINGGAMECRM') {
    exit();
}

if ($Abreviado == 'CHANGEPASSWORDCRM') {
    exit();
}

// Configuración de parámetros para Redis
$redisParam = ['ex' => 18000];

// Genera un prefijo único para Redis basado en los parámetros
$redisPrefix = "AGREGARCRM+UID" . $UsuarioId . '+' . $Abreviado . '+' . $IdMovimiento;

// Obtiene una instancia de Redis
$redis = RedisConnectionTrait::getRedisInstance(true);

// Si Redis está disponible, almacena los datos y finaliza el script
if ($redis != null) {
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit();
}

// Crea una instancia del CRM para gestionar movimientos
$Crm = new \Backend\integrations\crm\Crm();

// Procesa el movimiento en el CRM y obtiene la respuesta
$Response = $Crm->CrmMovements($UsuarioId, $Clasificador, $IdMovimiento, $Server, $Ismobile);

if (false) {
    try {
        // Inicializa las variables de datos y reglas
        $data = [];
        $rules = [];

        // Crea una instancia de Usuario con el ID de usuario proporcionado
        $Usuario = new Usuario($UsuarioId);

        // Crea una instancia de UsuarioMandante con el ID de usuario y el mandante del usuario
        $UsuarioMandante = new UsuarioMandante("", $UsuarioId, $Usuario->mandante);

        // Configura las reglas para consultar el movimiento de saldo más reciente
        array_push($rules, ["field" => "usuario_historial.tipo", "data" => "10, 15, 20, 30, 31, 40, 50", "op" => "in"]);
        array_push($rules, ["field" => "usuario_historial.movimiento", "data" => "'E', 'S', 'C'", "op" => "in"]);
        array_push($rules, ["field" => "usuario_historial.usuario_id", "data" => $UsuarioId, "op" => "eq"]);
        $filters = ["rules" => $rules, "groupOp" => "AND"];
        $select = "usuario_historial.usuhistorial_id, usuario_historial.movimiento, usuario_historial.valor";

        // Crea una instancia de UsuarioHistorial y obtiene el historial de usuario personalizado
        $UsuarioHistorial = new UsuarioHistorial();
        $answer = $UsuarioHistorial->getUsuarioHistorialsCustom(
            $select,
            "usuario_historial.usuhistorial_id",
            "desc",
            0,
            1,
            json_encode($filters),
            true
        );
        $movimiento = json_decode($answer);

        // Organiza el contenido de la notificación
        $UsuHistorialId = $movimiento->data[0]->{'usuario_historial.usuhistorial_id'};
        $balanceIncrease = $movimiento->data[0]->{'usuario_historial.movimiento'} == 'S' ? false : true;
        $currency = $UsuarioMandante->getMoneda();
        $amount = $movimiento->data[0]->{'usuario_historial.valor'};
        $data["NotificationBalance"] = [
            [
                "balanceIncrease" => $balanceIncrease,
                "currency" => $currency,
                "amount" => (intval($amount * 100) / 100)
            ]
        ];

        // Envía la notificación a través de WebSocket
        $WebSocketUsuario = new WebSocketUsuario('', '');
        $respuestaWS = $WebSocketUsuario->sendWSPieSocket($UsuarioMandante, $data);
    } catch (Exception $e) {
        // Captura cualquier excepción que ocurra en el bloque try
    }
}