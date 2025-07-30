<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
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
 * @var mixed $_ENV               Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $params             Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $_REQUEST           Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI                Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER            Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $method             Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $log                Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $params_json        Variable que almacena los parámetros de entrada o configuración en formato JSON.
 * @var mixed $requestid          Variable que almacena el identificador único de una solicitud o petición.
 * @var mixed $respuesta          Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $key                Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $values             Variable que almacena un conjunto de valores relacionados con una operación o proceso.
 * @var mixed $ticket_id          Variable que almacena el identificador único de un boleto o ticket.
 * @var mixed $token              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $id                 Variable que almacena un identificador genérico.
 * @var mixed $tmp_id             Variable que almacena un identificador temporal utilizado en un proceso.
 * @var mixed $ext_id             Variable que almacena un identificador externo relacionado con una transacción o entidad.
 * @var mixed $ext_token          Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $username           Variable que almacena el nombre de usuario.
 * @var mixed $unit_id            Variable que almacena el identificador único de una unidad o entidad dentro de un sistema.
 * @var mixed $staff_id           Variable que almacena el identificador único de un miembro del personal o empleado.
 * @var mixed $login_hash         Variable que almacena el valor hash utilizado para verificar la autenticidad del inicio de sesión.
 * @var mixed $barcode            Variable que almacena el código de barras asociado a un producto o ticket.
 * @var mixed $currency           Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $amount             Variable que almacena un monto o cantidad.
 * @var mixed $new_credit         Variable que almacena el monto de crédito nuevo asignado o actualizado.
 * @var mixed $old_credit         Variable que almacena el monto de crédito anterior antes de una actualización.
 * @var mixed $estimated_max_win  Variable que almacena el monto estimado máximo que se puede ganar en una transacción o juego.
 * @var mixed $events             Variable que almacena una lista de eventos.
 * @var mixed $system_bets        Variable que almacena las apuestas generadas automáticamente por el sistema.
 * @var mixed $usuario_id_externo Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $json_events        Variable que almacena eventos o datos relacionados en formato JSON.
 * @var mixed $cont_events        Variable que almacena la cantidad de eventos o el conteo de eventos en un proceso.
 * @var mixed $event              Variable que almacena un evento específico dentro de un sistema o proceso.
 * @var mixed $event_id           Variable que almacena el identificador de un evento.
 * @var mixed $pid                Variable que almacena el identificador de un proceso o transacción (por ejemplo, Process ID).
 * @var mixed $game               Variable que almacena la información relacionada con un juego específico.
 * @var mixed $start              Esta variable define el índice inicial o punto de partida para un proceso o iteración.
 * @var mixed $end                Variable que indica el final de un proceso o evento.
 * @var mixed $selections         Variable que almacena un conjunto de selecciones en una apuesta o juego.
 * @var mixed $selection          Variable que almacena una selección individual en una apuesta o juego.
 * @var mixed $selection_id       Variable que almacena el identificador único de una selección.
 * @var mixed $status             Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $odd                Variable que almacena las probabilidades o cuotas asociadas a una apuesta.
 * @var mixed $won                Variable que indica si una apuesta o evento ha sido ganado.
 * @var mixed $match_id           Variable que almacena el identificador único de un partido o evento.
 * @var mixed $system_bet         Variable que almacena una apuesta generada automáticamente por el sistema.
 * @var mixed $group_by           Variable que almacena la clave o criterio por el cual se agrupan los elementos o datos.
 * @var mixed $stake              Variable que almacena el monto apostado en una transacción o apuesta.
 * @var mixed $stringjson         Variable que almacena un objeto o conjunto de datos en formato JSON como cadena de texto.
 * @var mixed $ws_key_prematch    Variable que almacena la clave de acceso o autenticación para acceder a eventos antes del inicio de un partido o evento (pre-match).
 * @var mixed $Goldenrace         Variable que hace referencia a un proveedor o sistema de carreras de caballos virtuales.
 * @var mixed $response           Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $int64              Variable que almacena un valor entero de 64 bits (entero largo).
 * @var mixed $int32              Variable que almacena un valor entero de 32 bits.
 * @var mixed $extStaffId         Variable que almacena el identificador único del personal en un sistema externo.
 * @var mixed $entityId           Variable que almacena el identificador único de una entidad o institución.
 * @var mixed $ticket             Variable que almacena la referencia a un ticket o comprobante de una transacción.
 * @var mixed $json               Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $taxes              Variable que almacena los impuestos aplicados a una transacción o proceso.
 * @var mixed $jackpot            Variable que almacena el monto del premio mayor o jackpot asociado a un juego o evento.
 * @var mixed $gametype           Variable que almacena el tipo de juego o modalidad en un sistema de apuestas o entretenimiento.
 * @var mixed $ticket_id_explode  Variable que almacena una referencia desglosada o separada de un ticket.
 * @var mixed $gameId             Variable que almacena el identificador de un juego.
 * @var mixed $uid                Variable que almacena el identificador único de un usuario.
 * @var mixed $plus               Variable que almacena un valor que indica una operación de suma o incremento.
 * @var mixed $denomination       Variable que almacena el valor o denominación de una apuesta, transacción o producto.
 * @var mixed $bet                Variable que almacena la apuesta realizada en un juego o evento.
 * @var mixed $lines              Variable que almacena la cantidad de líneas o combinaciones de apuestas en un juego.
 * @var mixed $result             Variable que almacena el resultado de una operación o transacción.
 * @var mixed $transaccionId      Variable que almacena el identificador único de una transacción.
 * @var mixed $body               Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $minus              Variable que almacena un valor que indica una operación de resta o decremento.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\virtual\Goldenrace;

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$params = $_REQUEST['t'];
$params = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];
$method = $URI;

$log = "\r\n" . "----------   " . $URI . "   ------------" . "\r\n";
$log = $log . ($params);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

if ( ! empty($params)) {
    if ($_REQUEST['test'] == "spend") {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = array();

        foreach ($params_json as $key => $values) {
            $ticket_id = $key;

            $token = "#";

            $id = $values->id;
            $ticket_id = $id;
            $tmp_id = $values->tmp_id;
            $ext_id = $values->ext_id;
            $ext_token = $values->ext_token;
            $username = $values->username;
            $unit_id = $values->unit_id;
            $staff_id = $values->staff_id;
            $login_hash = $values->login_hash;
            $barcode = $values->barcode;
            $currency = $values->currency;
            $amount = $values->amount;
            $new_credit = $values->new_credit;
            $old_credit = $values->old_credit;
            $amount = $values->amount;
            $estimated_max_win = $values->estimated_max_win;
            $events = $values->events;
            $system_bets = $values->system_bets;
            $usuario_id_externo = $unit_id;

            $json_events = "";
            $cont_events = 0;

            foreach ($events as $event) {
                $event_id = $event->event_id;
                $pid = $event->pid;
                $game = $event->game;
                $start = $event->start;
                $end = $event->end;
                $selections = $event->selections;
                foreach ($selections as $selection) {
                    $selection_id = $selection->id;
                    $status = $selection->status;
                    $odd = $selection->odd;
                    $won = $selection->won;
                    $match_id = $selection->match_id;
                }
                if ($cont_events == 0) {
                    $json_events = $json_events . '{"evento":"' . $game . '","estado":"' . $status . '","fecha":"' . $start->format('Y-m-d') . '","hora":"' . $start->format('H:i') . '","eventoid":"' . $event_id . '","agrupador":"#","agrupadorid":"' . $game . '","opcion":"' . $id . '","logro":"' . $odd . '","premio":"' . $won . '","partido_id":"' . $match_id . '"}';
                } else {
                    $json_events = $json_events . ',{"evento":"' . $game . '","estado":"' . $status . '","fecha":"' . $start->format('Y-m-d') . '","hora":"' . $start->format('H:i') . '","eventoid":"' . $event_id . '","agrupador":"#","agrupadorid":"' . $game . '","opcion":"' . $id . '","logro":"' . $odd . '","premio":"' . $won . '","partido_id":"' . $match_id . '"}';
                }

                $cont_events = $cont_events + 1;
            }

            foreach ($system_bets as $system_bet) {
                $group_by = $system_bet->group_by;
                $stake = $system_bet->stake;
                $status = $system_bet->status;
                $won = $system_bet->won;
            }

            $stringjson = "{";
            $stringjson = $stringjson . '"Token":"' . $usuario_id_externo . '"';
            $stringjson = $stringjson . ',"KeyWinplay":"' . $ws_key_prematch . '"';
            $stringjson = $stringjson . ',"TypeWinplay":"BET"';

            $stringjson = $stringjson . ',"TransactionID":"' . $id . '"';
            $stringjson = $stringjson . ',"valor":"' . $amount . '"';
            $stringjson = $stringjson . ',"ticketid":"' . $id . '"';
            $stringjson = $stringjson . ',"GameReference":"#"';
            $stringjson = $stringjson . ',"Description":"#"';
            $stringjson = $stringjson . ',"usuarioid":"' . $usuario_id_externo . '"';
            $stringjson = $stringjson . ',"FrontendType":"#"';
            $stringjson = $stringjson . ',"BetStatus":"#"';
            $stringjson = $stringjson . ',"IsSystem":"#"';
            $stringjson = $stringjson . ',"EventCount":"1"';
            $stringjson = $stringjson . ',"BankerCount":"1"';
            $stringjson = $stringjson . ',"PremioProyectado":"0"';
            $stringjson = $stringjson . ',"Events":"#"';

            $stringjson = $stringjson . ',"EventsDescription":' . "[" . $json_events . "]";

            $stringjson = $stringjson . "}";

            $Goldenrace = new Goldenrace($ext_id, $unit_id);
            $response = $Goldenrace->DebitSpend($amount, $id, $tmp_id, $stringjson);
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r(
            json_encode(($respuesta))
        );
    }

    if (strpos($URI, "/spend") !== false) {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = array();

        foreach ($params_json as $key => $values) {
            $ticket_id = $key;

            $token = "#";

            $id = $values->id;
            $ticket_id = $id;
            $tmp_id = $values->tmp_id;
            $ext_id = $values->ext_id;
            $ext_token = $values->ext_token;
            $username = $values->username;
            $unit_id = $values->unit_id;
            $staff_id = $values->staff_id;
            $login_hash = $values->login_hash;
            $barcode = $values->barcode;
            $currency = $values->currency;
            $amount = $values->amount;
            $new_credit = $values->new_credit;
            $old_credit = $values->old_credit;
            $amount = $values->amount;
            $estimated_max_win = $values->estimated_max_win;
            $events = $values->events;
            $system_bets = $values->system_bets;
            $usuario_id_externo = $unit_id;

            $json_events = "";
            $cont_events = 0;

            foreach ($events as $event) {
                $event_id = $event->event_id;
                $pid = $event->pid;
                $game = $event->game;
                $start = $event->start;
                $end = $event->end;
                $selections = $event->selections;
                foreach ($selections as $selection) {
                    $selection_id = $selection->id;
                    $status = $selection->status;
                    $odd = $selection->odd;
                    $won = $selection->won;
                    $match_id = $selection->match_id;
                }
                if ($cont_events == 0) {
                    $json_events = $json_events . '{"evento":"' . $game . '","estado":"' . $status . '","fecha":"' . $start->format('Y-m-d') . '","hora":"' . $start->format('H:i') . '","eventoid":"' . $event_id . '","agrupador":"#","agrupadorid":"' . $game . '","opcion":"' . $id . '","logro":"' . $odd . '","premio":"' . $won . '","partido_id":"' . $match_id . '"}';
                } else {
                    $json_events = $json_events . ',{"evento":"' . $game . '","estado":"' . $status . '","fecha":"' . $start->format('Y-m-d') . '","hora":"' . $start->format('H:i') . '","eventoid":"' . $event_id . '","agrupador":"#","agrupadorid":"' . $game . '","opcion":"' . $id . '","logro":"' . $odd . '","premio":"' . $won . '","partido_id":"' . $match_id . '"}';
                }

                $cont_events = $cont_events + 1;
            }

            foreach ($system_bets as $system_bet) {
                $group_by = $system_bet->group_by;
                $stake = $system_bet->stake;
                $status = $system_bet->status;
                $won = $system_bet->won;
            }

            $stringjson = "{";
            $stringjson = $stringjson . '"Token":"' . $usuario_id_externo . '"';
            $stringjson = $stringjson . ',"KeyWinplay":"' . $ws_key_prematch . '"';
            $stringjson = $stringjson . ',"TypeWinplay":"BET"';

            $stringjson = $stringjson . ',"TransactionID":"' . $id . '"';
            $stringjson = $stringjson . ',"valor":"' . $amount . '"';
            $stringjson = $stringjson . ',"ticketid":"' . $id . '"';
            $stringjson = $stringjson . ',"GameReference":"#"';
            $stringjson = $stringjson . ',"Description":"#"';
            $stringjson = $stringjson . ',"usuarioid":"' . $usuario_id_externo . '"';
            $stringjson = $stringjson . ',"FrontendType":"#"';
            $stringjson = $stringjson . ',"BetStatus":"#"';
            $stringjson = $stringjson . ',"IsSystem":"#"';
            $stringjson = $stringjson . ',"EventCount":"1"';
            $stringjson = $stringjson . ',"BankerCount":"1"';
            $stringjson = $stringjson . ',"PremioProyectado":"0"';
            $stringjson = $stringjson . ',"Events":"#"';

            $stringjson = $stringjson . ',"EventsDescription":' . "[" . $json_events . "]";

            $stringjson = $stringjson . "}";

            $Goldenrace = new Goldenrace($ext_id, $unit_id);
            $response = $Goldenrace->DebitSpend($amount, $id, $tmp_id, $stringjson);
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }


    if ($_REQUEST['test'] == "sell") {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = [];

        foreach ($params_json as $key => $values) {
            print_r($values);

            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $ticket->stake;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);
            $response = $Goldenrace->Debit($id, $amount, $id, json_encode($values));

            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_decode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r(
            json_decode(($respuesta))
        );
    }

    if (strpos($URI, "/sell") !== false) {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = [];

        foreach ($params_json as $key => $values) {
            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $ticket->stake;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);
            $response = $Goldenrace->Debit($id, $amount, $id, json_encode($values));
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }

    if (strpos($URI, "/cancel") !== false) {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = [];

        foreach ($params_json as $key => $values) {
            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $values->amount;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);
            $response = $Goldenrace->Rollback($id, $amount, $id, json_encode($values));
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }

    if ($_REQUEST['test'] == "cancel") {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = [];

        foreach ($params_json as $key => $values) {
            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $values->amount;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);
            $response = $Goldenrace->Rollback($id, $amount, $id, json_encode($values));
            print_r($response);
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }

    if (strpos($URI, "/payout") !== false) {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = [];

        foreach ($params_json as $key => $values) {
            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $values->amount;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);

            if (false && in_array($id, array(195511195, 195616083, 195442442, 195661053, 195660274, 195152344, 195357393, 195656913, 195159997, 195650998, 195165620, 195517865, 195181481, 195622054, 195655843, 195637662, 195661725, 195165596, 195468696, 195429008, 195633037, 195658636, 195658888, 195211910, 195649146, 195649134, 195240038, 195176277, 195182667, 195552840, 195152416))) {
                $response = array(
                    "type" => "WalletCreditResponse",
                    "ticketId" => $id,
                    "result" => "error",
                    "errorId" => '0',
                    "errorMessage" => 'TRANSACTION_ALREADY_EXISTS'
                );
            } else {
                $response = $Goldenrace->Credit($id, $amount, $id, json_encode($values));
            }
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }

    if ($_REQUEST['test'] == "solve") {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = array();

        foreach ($params_json as $key => $values) {
            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $values->amount;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);
            $response = $Goldenrace->Solve($id, $amount, $id, json_encode($values));
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }

    if (strpos($URI, "/solve") !== false) {
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));

        $respuesta = [];

        foreach ($params_json as $key => $values) {
            $token = "#";

            $id = $values->ticketId;
            $ticket_id = $id;
            $ext_id = $values->extId;
            $extStaffId = $values->extStaffId;
            $ext_token = $values->extToken;

            $entityId = $values->entityId;

            $ticket = $values->ticket;
            $amount = $values->amount;

            $events = $values->ticket;

            $json_events = "";
            $cont_events = 0;

            $Goldenrace = new Goldenrace($ext_id, $entityId);
            $response = $Goldenrace->Solve($id, $amount, $id, json_encode($values));
            array_push($respuesta, $response);
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(($respuesta));
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


        print_r(
            json_encode(($respuesta))
        );
    }


    if ($_REQUEST['test'] == "getCredit") {
        //Obtenemos la variable 't' via POST
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));
        $respuesta = array();

        foreach ($params_json as $json) {
            $ext_id = $json->ext_id;
            $unit_id = $json->unit_id;
            $currency = $json->currency;
            $login_hash = $json->login_hash;
            $username = $json->username;

            $usuario_id_externo = str_replace("Usuario", "", $username);

            $Goldenrace = new Goldenrace($ext_id, $usuario_id_externo);
            $response = $Goldenrace->Auth();
            array_push($respuesta, $response);
            $respuesta = $response;
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(
                array($requestid => $respuesta)
            );

        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r(
            json_encode(
                array($requestid => $respuesta)
            )
        );
    }

    if (strpos($URI, "/getCredit") !== false) {
        //Obtenemos la variable 't' via POST
        $params_json = json_decode($params);
        $requestid = key(get_object_vars($params_json));
        $respuesta = [];

        foreach ($params_json as $json) {
            $ext_id = $json->ext_id;
            $unit_id = $json->unit_id;
            $currency = $json->currency;
            $login_hash = $json->login_hash;
            $username = $json->username;

            $usuario_id_externo = str_replace("Usuario", "", $username);

            $Goldenrace = new Goldenrace($ext_id, $usuario_id_externo);
            $response = $Goldenrace->Auth();
            array_push($respuesta, $response);
            $respuesta = $response;
        }
        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(
                array($requestid => $respuesta)
            );
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r(
            json_encode(
                array($requestid => $respuesta)
            )
        );
    }

    if ($_REQUEST['test'] == "login") {
        //Obtenemos la variable 't' via POST
        $json = json_decode($params);
        $respuesta = array();

        $ext_id = $json->extId;
        $unit_id = $json->unitId;
        $currency = $json->currency;
        $login_hash = $json->login_hash;
        $username = $json->username;

        $usuario_id_externo = str_replace("Usuario", "", $username);

        $Goldenrace = new Goldenrace($ext_id, $unit_id);
        $response = $Goldenrace->Auth();
        array_push($respuesta, $response);
        $respuesta = $response;

        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(
                $respuesta
            );
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r(
            json_encode(
                $respuesta
            )
        );
    }

    if (strpos($URI, "/login") !== false) {
        //Obtenemos la variable 't' via POST
        $json = json_decode($params);
        $respuesta = array();

        $ext_id = $json->extId;
        $unit_id = $json->unitId;
        $currency = $json->currency;
        $login_hash = $json->login_hash;
        $username = $json->username;

        $usuario_id_externo = str_replace("Usuario", "", $username);

        $Goldenrace = new Goldenrace($ext_id, $unit_id);
        $response = $Goldenrace->Auth();
        array_push($respuesta, $response);
        $respuesta = $response;

        $log = "\r\n" . "----------   RESPUESTA" . $URI . "   ------------" . "\r\n";
        $log = $log . json_encode(
                $respuesta
            );
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);
        print_r(
            json_encode(
                $respuesta
            )
        );
    }


    if ($method == "payout") {
        $response = "";

        //Obtenemos la variable 't' via POST

        $params_json = json_decode($params);
        foreach ($params_json as $key => $values) {
            $ticket_id = $key;

            $token = "#";

            $ext_id = $values->ext_id;
            $unit_id = $values->unit_id;
            $currency = $values->currency;
            $staff_id = $values->staff_id;
            $amount = $values->amount;
            $taxes = $values->taxes;
            $status = $values->status;
            $won = $values->won;
            $jackpot = $values->jackpot;
            $event_id = $values->event_id;
            $gametype = $values->gametype;
            $tmp_id = $values->tmp_id;
            $usuario_id_externo = $unit_id;
            $token = $usuario_id_externo;
            $gametype = "#";

            if ($status == "won") {
                $stringjson = "{";
                $stringjson = $stringjson . '"Token":"' . $token . '"';
                $stringjson = $stringjson . ',"KeyWinplay":"' . $ws_key_prematch . '"';
                $stringjson = $stringjson . ',"TypeWinplay":"WIN"';
                $stringjson = $stringjson . ',"TransactionID":"' . $ticket_id . '"';

                $stringjson = $stringjson . ',"valor":"' . $won . '"';
                $stringjson = $stringjson . ',"ticketid":"' . $ticket_id . '"';
                $stringjson = $stringjson . ',"GameReference":"' . $gametype . '"';
                $stringjson = $stringjson . ',"Description":"' . $gametype . '"';
                $stringjson = $stringjson . ',"usuarioid":"' . $usuario_id_externo . '"';
                $stringjson = $stringjson . ',"FrontendType":"#"';
                $stringjson = $stringjson . ',"BetStatus":"W"';
                $stringjson = $stringjson . "}";
            } elseif ($status == "cancel") {
                $stringjson = "{";
                $stringjson = $stringjson . '"Token":"' . $token . '"';
                $stringjson = $stringjson . ',"KeyWinplay":"' . $ws_key_prematch . '"';
                $stringjson = $stringjson . ',"TypeWinplay":"LOSS"';
                $stringjson = $stringjson . ',"TransactionID":"' . $ticket_id . '"';

                $stringjson = $stringjson . ',"valor":"' . $won . '"';
                $stringjson = $stringjson . ',"ticketid":"' . $ticket_id . '"';
                $stringjson = $stringjson . ',"GameReference":"' . $gametype . '"';
                $stringjson = $stringjson . ',"Description":"' . $gametype . '"';
                $stringjson = $stringjson . ',"usuarioid":"' . $usuario_id_externo . '"';
                $stringjson = $stringjson . ',"FrontendType":"#"';
                $stringjson = $stringjson . ',"BetStatus":"C"';
                $stringjson = $stringjson . "}";
            } elseif ($status == "rollback") {
                $ticket_id_explode = explode("_", $key);

                $ticket_id = $ticket_id_explode[0];


                $stringjson = "{";
                $stringjson = $stringjson . '"Token":"' . $token . '"';
                $stringjson = $stringjson . ',"KeyWinplay":"' . $ws_key_prematch . '"';
                $stringjson = $stringjson . ',"TypeWinplay":"REFUND"';
                $stringjson = $stringjson . ',"TransactionID":"' . $ticket_id . '"';
                $stringjson = $stringjson . ',"valor":"' . $amount . '"';
                $stringjson = $stringjson . ',"ticketid":"' . $ticket_id . '"';
                $stringjson = $stringjson . ',"GameReference":"' . $gametype . '"';
                $stringjson = $stringjson . ',"Description":"' . $gametype . '"';
                $stringjson = $stringjson . ',"usuarioid":"' . $usuario_id_externo . '"';
                $stringjson = $stringjson . ',"FrontendType":"#"';
                $stringjson = $stringjson . ',"BetStatus":"R"';
                $stringjson = $stringjson . "}";
            }


            //Llamamos funcion externa para registrar la accion

            $response = $Goldenrace->Credit($gameId, $uid, $game, $plus, $denomination, $bet, $lines, $result, $transaccionId, $body);
        }
    }

    if ($method == "confirm") {
        $params_json = json_decode($params);

        foreach ($params_json as $key => $values) {
            $ticket_id = $key;

            $token = "#";

            $id = $values->id;
            $ticket_id = $id;
            $tmp_id = $values->tmp_id;
            $ext_id = $values->ext_id;
            $ext_token = $values->ext_token;
            $username = $values->username;
            $unit_id = $values->unit_id;
            $staff_id = $values->staff_id;
            $login_hash = $values->login_hash;
            $barcode = $values->barcode;
            $currency = $values->currency;
            $amount = $values->amount;
            $new_credit = $values->new_credit;
            $old_credit = $values->old_credit;
            $amount = $values->amount;
            $estimated_max_win = $values->estimated_max_win;
            $events = $values->events;
            $system_bets = $values->system_bets;
            $usuario_id_externo = $unit_id;

            $json_events = "";
            $cont_events = 0;

            foreach ($events as $event) {
                $event_id = $event->event_id;
                $pid = $event->pid;
                $game = $event->game;
                $start = $event->start;
                $end = $event->end;
                $selections = $event->selections;
                foreach ($selections as $selection) {
                    $selection_id = $selection->id;
                    $status = $selection->status;
                    $odd = $selection->odd;
                    $won = $selection->won;
                    $match_id = $selection->match_id;
                }
                if ($cont_events == 0) {
                    $json_events = $json_events . '{"evento":"' . $game . '","estado":"' . $status . '","fecha":"' . $start->format('Y-m-d') . '","hora":"' . $start->format('H:i') . '","eventoid":"' . $event_id . '","agrupador":"#","agrupadorid":"' . $game . '","opcion":"' . $id . '","logro":"' . $odd . '","premio":"' . $won . '","partido_id":"' . $match_id . '"}';
                } else {
                    $json_events = $json_events . ',{"evento":"' . $game . '","estado":"' . $status . '","fecha":"' . $start->format('Y-m-d') . '","hora":"' . $start->format('H:i') . '","eventoid":"' . $event_id . '","agrupador":"#","agrupadorid":"' . $game . '","opcion":"' . $id . '","logro":"' . $odd . '","premio":"' . $won . '","partido_id":"' . $match_id . '"}';
                }

                $cont_events = $cont_events + 1;
            }

            foreach ($system_bets as $system_bet) {
                $group_by = $system_bet->group_by;
                $stake = $system_bet->stake;
                $status = $system_bet->status;
                $won = $system_bet->won;
            }

            $stringjson = "{";
            $stringjson = $stringjson . '"Token":"' . $usuario_id_externo . '"';
            $stringjson = $stringjson . ',"KeyWinplay":"' . $ws_key_prematch . '"';
            $stringjson = $stringjson . ',"TypeWinplay":"BET"';

            $stringjson = $stringjson . ',"TransactionID":"' . $id . '"';
            $stringjson = $stringjson . ',"valor":"' . $amount . '"';
            $stringjson = $stringjson . ',"ticketid":"' . $id . '"';
            $stringjson = $stringjson . ',"GameReference":"#"';
            $stringjson = $stringjson . ',"Description":"#"';
            $stringjson = $stringjson . ',"usuarioid":"' . $usuario_id_externo . '"';
            $stringjson = $stringjson . ',"FrontendType":"#"';
            $stringjson = $stringjson . ',"BetStatus":"#"';
            $stringjson = $stringjson . ',"IsSystem":"#"';
            $stringjson = $stringjson . ',"EventCount":"1"';
            $stringjson = $stringjson . ',"BankerCount":"1"';
            $stringjson = $stringjson . ',"PremioProyectado":"0"';
            $stringjson = $stringjson . ',"Events":"#"';

            $stringjson = $stringjson . ',"EventsDescription":' . "[" . $json_events . "]";

            $stringjson = $stringjson . "}";


            //Llamamos funcion externa para registrar la accion


            //Creamos los datos a enviar

            $response = $Goldenrace->Debit($gameId, $uid, $game, $minus, $denomination, $bet, $lines, $result, $transaccionId, $body);
        }
    }
}





