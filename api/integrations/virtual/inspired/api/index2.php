<?php

/**
 * Este archivo contiene un script para procesar solicitudes HTTP en formato XML,
 * analizar su contenido y generar respuestas en base al tipo de mensaje recibido.
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
 * @var mixed $params        Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI           Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER       Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $method        Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $log           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $body          Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $messageSuper  Variable que almacena un mensaje o alerta de nivel superior.
 * @var mixed $message       Variable que almacena un mensaje informativo o de error dentro del sistema.
 * @var mixed $MessageType   Variable que almacena el tipo de mensaje (por ejemplo, información, error, advertencia).
 * @var mixed $TransactionId Variable que almacena el identificador de una transacción.
 * @var mixed $eventSuper    Variable que almacena un evento de nivel superior en un sistema o plataforma.
 * @var mixed $event         Variable que almacena un evento específico dentro de un sistema o proceso.
 * @var mixed $e             Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\virtual\Goldenrace;

$params = $_REQUEST['t'];
$params = file_get_contents('php://input');


$URI = $_SERVER['REQUEST_URI'];
$method = $URI;

$log = "\r\n" . "----------   " . $URI . "   ------------" . "\r\n";
$log = $log . ($params);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$body = trim(file_get_contents('php://input'));


if ($body != "") {
    $data = simplexml_load_string($body);
}

$log = time();

Header('Content-type: text/xml');

$messageSuper = $data;

try {
    $message = new SimpleXMLElement("<message></message>");


    $MessageType = $messageSuper->attributes()->MessageType;
    $TransactionId = $messageSuper->attributes()->TransactionId;


    $method = $URI;

    $log = $body;
    fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

    switch ($MessageType) {
        case "EventCard":


            $message->addAttribute('MessageType', $messageSuper->attributes()->MessageType . "_Response");
            $message->addAttribute('ControllerId', $messageSuper->attributes()->ControllerId);
            $message->addAttribute('TransactionId', $messageSuper->attributes()->TransactionId);
            $message->addAttribute('MessageDateTime', $messageSuper->attributes()->MessageDateTime);
            $message->addAttribute('MessageFormatVersion', $messageSuper->attributes()->MessageFormatVersion);

            foreach ($messageSuper->events->event as $eventSuper) {
                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }


            break;


        case "NoMoreBets":


            $message->addAttribute('MessageType', $messageSuper->attributes()->MessageType . "_Response");
            $message->addAttribute('ControllerId', $messageSuper->attributes()->ControllerId);
            $message->addAttribute('TransactionId', $messageSuper->attributes()->TransactionId);
            $message->addAttribute('MessageDateTime', $messageSuper->attributes()->MessageDateTime);
            $message->addAttribute('MessageFormatVersion', $messageSuper->attributes()->MessageFormatVersion);

            foreach ($messageSuper->event as $eventSuper) {
                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }


            break;


        case "Result":


            $message->addAttribute('MessageType', $messageSuper->attributes()->MessageType . "_Response");
            $message->addAttribute('ControllerId', $messageSuper->attributes()->ControllerId);
            $message->addAttribute('TransactionId', $messageSuper->attributes()->TransactionId);
            $message->addAttribute('MessageDateTime', $messageSuper->attributes()->MessageDateTime);
            $message->addAttribute('MessageFormatVersion', $messageSuper->attributes()->MessageFormatVersion);

            foreach ($messageSuper->event as $eventSuper) {
                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }


            break;


        case "Void":


            $message->addAttribute('MessageType', $messageSuper->attributes()->MessageType . "_Response");
            $message->addAttribute('ControllerId', $messageSuper->attributes()->ControllerId);
            $message->addAttribute('TransactionId', $messageSuper->attributes()->TransactionId);
            $message->addAttribute('MessageDateTime', $messageSuper->attributes()->MessageDateTime);
            $message->addAttribute('MessageFormatVersion', $messageSuper->attributes()->MessageFormatVersion);

            foreach ($messageSuper->event as $eventSuper) {
                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }


            break;
    }


    print_r($message->asXML());
} catch (Exception $e) {
    print_r($e);
}


