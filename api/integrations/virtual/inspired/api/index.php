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
 * @var mixed $params                  Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $_REQUEST                Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI                     Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER                 Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $method                  Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $log                     Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $body                    Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data                    Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $messageSuper            Variable que almacena un mensaje o alerta de nivel superior.
 * @var mixed $UsuarioRecargaMySqlDAO  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $message                 Variable que almacena un mensaje informativo o de error dentro del sistema.
 * @var mixed $MessageType             Variable que almacena el tipo de mensaje (por ejemplo, información, error, advertencia).
 * @var mixed $TransactionId           Variable que almacena el identificador de una transacción.
 * @var mixed $eventSuper              Variable que almacena un evento de nivel superior en un sistema o plataforma.
 * @var mixed $EventId                 Variable que almacena el identificador único de un evento.
 * @var mixed $EventGUID               Variable que almacena el identificador único global (GUID) de un evento.
 * @var mixed $EventType               Variable que almacena el tipo de evento (por ejemplo, deportivo, promocional).
 * @var mixed $EventTime               Variable que almacena la hora en que un evento tiene lugar.
 * @var mixed $OddsType                Variable que almacena el tipo de probabilidades o cuotas en un evento o apuesta.
 * @var mixed $PayOut                  Variable que almacena el monto de pago o reembolso asociado a una apuesta ganada.
 * @var mixed $NumEachWay              Variable que almacena el número de apuestas en forma de cada camino en una apuesta.
 * @var mixed $RaceName                Variable que almacena el nombre de una carrera o evento relacionado con apuestas.
 * @var mixed $WinnerFinishesAtFrame   Variable que almacena el fotograma o la posición en la que el ganador termina una carrera o evento.
 * @var mixed $RaceLengthS             Variable que almacena la longitud de una carrera o evento en segundos.
 * @var mixed $CourseName              Variable que almacena el nombre de un curso o evento deportivo.
 * @var mixed $CourseIsJumps           Variable que indica si el curso es de tipo "saltos" (por ejemplo, carreras de caballos de obstáculos).
 * @var mixed $CommScriptName          Variable que almacena el nombre de un script de comunicación utilizado en un sistema o aplicación.
 * @var mixed $Racers                  Variable que almacena una lista de los corredores o participantes en un evento o carrera.
 * @var mixed $eventoproveedor_id      Variable que almacena el identificador único de un evento relacionado con un proveedor.
 * @var mixed $nombre                  Variable que almacena el nombre de un elemento, objeto o entidad.
 * @var mixed $nombre_traduccion       Variable que almacena el nombre traducido de un elemento o entidad a otro idioma.
 * @var mixed $nombre_internacional    Variable que almacena el nombre internacional de un elemento o entidad.
 * @var mixed $estado                  Variable que almacena el estado de un proceso o entidad.
 * @var mixed $fecha                   Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $competencia_id          Variable que almacena el identificador único de una competencia.
 * @var mixed $proveedor_id            Variable que almacena el identificador único de un proveedor.
 * @var mixed $depositos               Variable que almacena una lista o información sobre los depósitos realizados en una transacción.
 * @var mixed $eventoDB                Variable que almacena información sobre un evento en la base de datos.
 * @var mixed $evento_id               Variable que almacena el identificador único de un evento.
 * @var mixed $eventarray              Variable que almacena un arreglo de eventos asociados a un proceso o sistema.
 * @var mixed $gamearray               Variable que almacena un arreglo de juegos disponibles o en proceso.
 * @var mixed $BetTypes                Variable que almacena los tipos de apuestas disponibles en un evento o juego.
 * @var mixed $NumBetTypes             Variable que almacena el número de tipos de apuestas disponibles.
 * @var mixed $BetType                 Variable que almacena el tipo específico de apuesta realizada en un evento.
 * @var mixed $Num                     Variable que almacena un número, generalmente asociado con conteos o identificadores.
 * @var mixed $Type                    Variable que almacena el tipo de un objeto o transacción.
 * @var mixed $Margin                  Variable que almacena el margen de ganancia o el margen de apuestas.
 * @var mixed $BetCode                 Variable que almacena el código único asignado a una apuesta.
 * @var mixed $Prices                  Variable que almacena los precios o valores asociados a una apuesta o evento.
 * @var mixed $abreviado               Esta variable se utiliza para almacenar y manipular valores abreviados.
 * @var mixed $fp                      Variable que almacena información sobre la forma de pago.
 * @var mixed $racer                   Variable que almacena la información sobre un corredor o participante en un evento.
 * @var mixed $Lane                    Variable que almacena el carril asignado a un corredor o participante en una carrera.
 * @var mixed $Name                    Variable que almacena el nombre de un elemento, jugador o entidad.
 * @var mixed $Price                   Variable que almacena el precio asociado a una apuesta o producto.
 * @var mixed $Fav                     Variable que indica si un corredor o equipo es el favorito en una competencia o apuesta.
 * @var mixed $ProbWin                 Variable que almacena la probabilidad de ganar asociada a una apuesta o participante.
 * @var mixed $Form                    Variable que almacena el historial o estado de forma de un corredor o participante.
 * @var mixed $RacerTextureID          Variable que almacena el identificador único de la textura asociada a un corredor en una interfaz gráfica.
 * @var mixed $Rank                    Variable que almacena el rango o posición de un corredor o participante en una competencia.
 * @var mixed $Human                   Variable que indica si el participante es un ser humano o una entidad automatizada.
 * @var mixed $HumanTextureID          Variable que almacena el identificador único de la textura asociada a un participante humano.
 * @var mixed $Place                   Variable que almacena el lugar o posición final de un corredor en una competencia.
 * @var mixed $id                      Variable que almacena un identificador genérico.
 * @var mixed $tipo                    Variable que indica el tipo de elemento o evento (por ejemplo, tipo de apuesta o evento).
 * @var mixed $valor                   Variable que almacena un valor monetario o numérico.
 * @var mixed $outcome                 Variable que almacena el resultado o desenlace de una apuesta o evento.
 * @var mixed $opcion_id               Variable que almacena el identificador único de una opción en una apuesta o juego.
 * @var mixed $opcion                  Variable que almacena una opción seleccionada en un evento o apuesta.
 * @var mixed $apuestaParent           Variable que almacena la apuesta principal o primaria a la que se refiere una sub-apuesta.
 * @var mixed $apuestaDB               Variable que almacena la información sobre una apuesta en la base de datos.
 * @var mixed $apuesta_id              Variable que almacena el identificador único de una apuesta.
 * @var mixed $estado_apuesta          Variable que almacena el estado actual de una apuesta (por ejemplo, pendiente, ganada, perdida).
 * @var mixed $eventoapuesta_idDB      Variable que almacena el identificador único de un evento de apuesta en la base de datos.
 * @var mixed $eventoapuesta_id        Variable que almacena el identificador único de un evento de apuesta.
 * @var mixed $apuestadetalleDB        Variable que almacena detalles sobre una apuesta en la base de datos.
 * @var mixed $apuestadetalle_id       Variable que almacena el identificador único de un detalle de apuesta.
 * @var mixed $opcionN                 Variable que almacena la opción N (N podría referirse a un número o índice de opción).
 * @var mixed $iterator                Variable que almacena un valor utilizado para iterar a través de elementos en un conjunto de datos.
 * @var mixed $mercado                 Variable que almacena información sobre el mercado de apuestas o el mercado de un producto.
 * @var mixed $NumBets                 Variable que almacena el número total de apuestas realizadas en un evento.
 * @var mixed $detalle                 Variable que almacena información detallada sobre una operación o elemento.
 * @var mixed $Outcome                 Variable que almacena el resultado de un evento o apuesta.
 * @var mixed $event                   Variable que almacena un evento específico dentro de un sistema o proceso.
 * @var mixed $Position                Variable que almacena la posición final de un participante en una competencia o evento.
 * @var mixed $e                       Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $result_array_final      Variable que almacena el arreglo final de resultados de un evento o proceso.
 * @var mixed $subid                   Variable que almacena un identificador relacionado con un subproceso o subevento.
 * @var mixed $objfin                  Variable que almacena el objeto final relacionado con un proceso o evento.
 * @var mixed $objfirst                Variable que almacena el primer objeto de un conjunto o proceso.
 * @var mixed $objinicio               Variable que almacena el objeto inicial relacionado con un proceso o evento.
 * @var mixed $what                    Variable que almacena una pregunta o consulta sobre algo en el sistema.
 * @var mixed $where                   Variable que almacena una consulta sobre la ubicación o contexto de algo en el sistema.
 * @var mixed $array_final             Variable que almacena el arreglo final de elementos en un proceso o conjunto de datos.
 * @var mixed $result_array            Variable que almacena un arreglo de resultados de un proceso o evento.
 * @var mixed $campos                  Variable que almacena campos o atributos asociados a un proceso o entidad.
 * @var mixed $cont                    Variable que almacena un contador o valor numérico relacionado con un proceso.
 * @var mixed $rules                   Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $key                     Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                   Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $field                   Variable que almacena un campo o área de datos en una base de datos o formulario.
 * @var mixed $op                      Variable que almacena una operación o acción a realizar en un proceso.
 * @var mixed $data_array              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $item                    Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $filtro                  Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonfiltro              Variable que almacena un filtro en formato JSON.
 * @var mixed $IntEventoApuestaDetalle Variable que almacena información detallada sobre un evento de apuesta.
 * @var mixed $apuestas                Variable que almacena un conjunto de apuestas realizadas.
 * @var mixed $final                   Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $apuesta                 Variable que almacena una apuesta realizada en un juego o evento.
 * @var mixed $array                   Variable que almacena una lista o conjunto de datos.
 * @var mixed $arrayd                  Variable que almacena un arreglo de datos relacionados con un proceso.
 * @var mixed $campo                   Variable que almacena un campo específico dentro de un conjunto de datos o formulario.
 * @var mixed $subidsum                Variable que almacena la suma de identificadores relacionados con un subproceso.
 * @var mixed $competencia             Variable que almacena información sobre una competencia o evento deportivo.
 * @var mixed $IntEventoApuesta        Variable que almacena información detallada sobre un evento de apuesta.
 * @var mixed $seguir                  Variable que indica si se debe continuar con una operación o proceso.
 * @var mixed $IntEventoDetalle        Variable que almacena detalles sobre un evento relacionado con una apuesta.
 * @var mixed $eventos                 Variable que almacena una lista o conjunto de eventos.
 * @var mixed $eventoid                Variable que almacena el identificador único de un evento.
 * @var mixed $evento                  Variable que almacena información sobre un evento específico.
 * @var mixed $eventoA                 Variable que almacena un evento específico A dentro de un proceso o sistema.
 * @var mixed $is_blocked              Variable que indica si una entidad o proceso está bloqueado o no.
 * @var mixed $IntCompetencia          Variable que almacena información detallada sobre una competencia.
 * @var mixed $competencias            Variable que almacena una lista de competencias o eventos deportivos.
 * @var mixed $IntRegion               Variable que almacena información detallada sobre una región geográfica o administrativa.
 * @var mixed $regiones                Variable que almacena una lista de regiones geográficas o administrativas.
 * @var mixed $region                  Variable que almacena información sobre una región geográfica.
 * @var mixed $IntDeporte              Variable que almacena información detallada sobre un deporte específico.
 * @var mixed $sports                  Variable que almacena información sobre deportes disponibles o involucrados en un sistema.
 * @var mixed $sport                   Variable que almacena información sobre un deporte.
 * @var mixed $responseW               Variable que almacena la respuesta de un sistema o proceso relacionado con el usuario.
 * @var mixed $WebsocketUsuario        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $response                Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'ON');

use Backend\integrations\virtual\Goldenrace;

use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\websocket\WebsocketUsuario;


require(__DIR__ . '../../../../../vendor/autoload.php');


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
$messageSuper = $data;

try {
    $UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();


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

            //Obtenemos los eventos
            foreach ($messageSuper->events->event as $eventSuper) {
                $EventId = $eventSuper->attributes()->EventId;
                $EventGUID = $eventSuper->attributes()->EventGUID;
                $EventType = $eventSuper->attributes()->EventType;
                $EventTime = $eventSuper->attributes()->EventTime;
                $OddsType = $eventSuper->attributes()->OddsType;
                $PayOut = $eventSuper->attributes()->PayOut;
                $NumEachWay = $eventSuper->attributes()->NumEachWay;
                $RaceName = $eventSuper->attributes()->RaceName;
                $WinnerFinishesAtFrame = $eventSuper->attributes()->WinnerFinishesAtFrame;
                $RaceLengthS = $eventSuper->attributes()->RaceLengthS;
                $CourseName = $eventSuper->attributes()->CourseName;
                $CourseIsJumps = $eventSuper->attributes()->CourseIsJumps;
                $CommScriptName = $eventSuper->attributes()->CommScriptName;
                $Racers = $eventSuper->attributes()->Racers;


                $eventoproveedor_id = $EventId;
                $nombre = $RaceName;
                $nombre_traduccion = $RaceName;
                $nombre_internacional = $RaceName;
                $estado = 'A';
                $fecha = date("Y-m-d H:i:s", strtotime($EventTime));

                switch ($EventType) {
                    case 9:
                        $competencia_id = 1;

                        break;

                    case 0:
                        $competencia_id = 3;

                        break;

                    case 1:
                        $competencia_id = 9;

                        break;

                    default:
                        $competencia_id = 1;

                        break;
                }

                $proveedor_id = 16;

                $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento (nombre,nombre_traduccion,nombre_internacional,fecha,estado,usucrea_id,usumodif_id,competencia_id,proveedor_id,eventoproveedor_id)  SELECT * FROM (SELECT '" . $nombre . "' as nombre ,'" . $nombre_traduccion . "' as nombre_traduccion,'" . $nombre_internacional . "' as nombre_internacional,'" . $fecha . "' as fecha,'" . $estado . "' as estado,'0' as usucrea,'0' as usumodif,'" . $competencia_id . "' as competencia_id,'" . $proveedor_id . "' as proveedor_id,'" . $eventoproveedor_id . "' as eventoproveedor_id) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento WHERE eventoproveedor_id = '" . $eventoproveedor_id . "' ) ");


                $eventoDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT evento_id FROM int_evento WHERE eventoproveedor_id = '" . $eventoproveedor_id . "' ");

                $evento_id = $eventoDB[0]['int_evento.evento_id'];

                array_push($eventarray, $evento_id);
                array_push($gamearray, $evento_id);

                //Tipo Apuesta

                $BetTypes = $eventSuper->BetTypes;
                $NumBetTypes = $BetTypes->attributes()->NumBetTypes;


                foreach ($BetTypes->BetType as $BetType) {
                    $Num = $BetType->attributes()->Num;
                    $Type = $BetType->attributes()->Type;
                    $Margin = $BetType->attributes()->Margin;
                    $BetCode = $BetType->attributes()->BetCode;
                    $Prices = $BetType->attributes()->Prices;
                    $Num = $BetType->attributes()->Num;

                    $nombre = $Type;
                    $nombre_traduccion = $Type;
                    $nombre_internacional = $Type;
                    $abreviado = $Type;
                    $estado = 'A';

                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta (nombre,nombre_traduccion,nombre_internacional,abreviado,estado,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $nombre . "' as nombre ,'" . $nombre_traduccion . "' as nombre_traduccion,'" . $nombre_internacional . "' as nombre_internacional,'" . $abreviado . "' as abreviado,'" . $estado . "' as estado,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta WHERE abreviado = '" . $abreviado . "' ) ");
                }


                $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                fwrite($fp, "PASO1 " . time());
                fclose($fp);

                if ($EventType == 0 || $EventType == 1) {
                    //Caballos
                    foreach ($eventSuper->racer as $racer) {
                        $Num = $racer->attributes()->Num;
                        $Lane = $racer->attributes()->Lane;
                        $Name = $racer->attributes()->Name;
                        $Price = $racer->attributes()->Price;
                        $Fav = $racer->attributes()->Fav;
                        $ProbWin = $racer->attributes()->ProbWin;
                        $Form = $racer->attributes()->Form;
                        $RacerTextureID = $racer->attributes()->RacerTextureID;
                        $Rank = $racer->attributes()->Rank;
                        $Human = $racer->attributes()->Human;
                        $HumanTextureID = $racer->attributes()->HumanTextureID;
                        $Place = $racer->attributes()->Place;

                        $id = "Racer" . $Num;
                        $tipo = "RACER";
                        $valor = $Name;


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_detalle (valor,evento_id,tipo,id,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $valor . "' as valor ,'" . $evento_id . "' as evento_id,'" . $tipo . "' as tipo,'" . $id . "' as id,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_detalle WHERE evento_id = '" . $evento_id . "' AND tipo = '" . $tipo . "' AND id = '" . $id . "'  ) ");

                        $outcome = "Win";
                        $estado = "A";
                        $opcion_id = $Num;
                        $opcion = $outcome;

                        $apuestaParent = $outcome;

                        $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                        $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta_detalle (opcion_id,apuesta_id,estado,opcion,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $opcion_id . "' as opcion_id ,'" . $apuesta_id . "' as apuesta_id,'" . $estado . "' as estado,'" . $opcion . "' as opcion,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                        $outcome = "Place2";
                        $estado = "A";
                        $opcion_id = $Num;
                        $opcion = $outcome;

                        $apuestaParent = $outcome;

                        $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                        $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta_detalle (opcion_id,apuesta_id,estado,opcion,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $opcion_id . "' as opcion_id ,'" . $apuesta_id . "' as apuesta_id,'" . $estado . "' as estado,'" . $opcion . "' as opcion,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");
                    }
                    $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                    fwrite($fp, "PASO2 " . time());
                    fclose($fp);
                    //Caballos
                    foreach ($eventSuper->racer as $racer) {
                        $Num = $racer->attributes()->Num;
                        $Lane = $racer->attributes()->Lane;
                        $Name = $racer->attributes()->Name;
                        $Price = $racer->attributes()->Price;
                        $Fav = $racer->attributes()->Fav;
                        $ProbWin = $racer->attributes()->ProbWin;
                        $Form = $racer->attributes()->Form;
                        $RacerTextureID = $racer->attributes()->RacerTextureID;
                        $Rank = $racer->attributes()->Rank;
                        $Human = $racer->attributes()->Human;
                        $HumanTextureID = $racer->attributes()->HumanTextureID;
                        $Place = $racer->attributes()->Place;

                        $id = "Racer" . $Num;
                        $tipo = "RACER";
                        $valor = $Name;


                        $outcome = "Win";
                        $estado = "A";
                        $opcion_id = $Num;
                        $opcion = $outcome;
                        $nombre = "";
                        $estado_apuesta = "A";
                        $valor = $Price;


                        $apuestaParent = $outcome;

                        $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id,nombre FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                        $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];
                        $nombre = $apuestaDB[0]['int_apuesta.nombre'];


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta (evento_id,apuesta_id,nombre,estado,estado_apuesta,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $evento_id . "' as evento_id ,'" . $apuesta_id . "' as apuesta_id,'" . $nombre . "' as nombre,'" . $estado . "' as estado,'" . $estado_apuesta . "' as estado_apuesta,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                        $eventoapuesta_idDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT eventoapuesta_id,nombre FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "'  ");

                        $eventoapuesta_id = $eventoapuesta_idDB[0]['int_evento_apuesta.eventoapuesta_id'];

                        $apuestadetalleDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuestadetalle_id,opcion FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ");

                        $apuestadetalle_id = $apuestadetalleDB[0]['int_apuesta_detalle.apuestadetalle_id'];
                        $opcionN = $apuestadetalleDB[0]['int_apuesta_detalle.opcion'];


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta_detalle (estado,estado_apuesta,nombre,apuestadetalle_id,eventoapuesta_id,valor,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $estado . "' as estado ,'" . $estado_apuesta . "' as estado_apuesta,'" . $opcionN . "' as nombre,'" . $apuestadetalle_id . "' as apuestadetalle_id,'" . $eventoapuesta_id . "' as eventoapuesta_id,'" . $valor . "' as valor,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta_detalle WHERE eventoapuesta_id = '" . $eventoapuesta_id . "' AND apuestadetalle_id = '" . $apuestadetalle_id . "' ) ");


                        $outcome = "Place2";
                        $estado = "A";
                        $opcion_id = $Num;
                        $opcion = $outcome;
                        $valor = $Place;


                        $apuestaParent = $outcome;
                        $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id,nombre FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                        $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];
                        $nombre = $apuestaDB[0]['int_apuesta.nombre'];


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta (evento_id,apuesta_id,nombre,estado,estado_apuesta,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $evento_id . "' as evento_id ,'" . $apuesta_id . "' as apuesta_id,'" . $nombre . "' as nombre,'" . $estado . "' as estado,'" . $estado_apuesta . "' as estado_apuesta,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                        $eventoapuesta_idDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT eventoapuesta_id,nombre FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "'  ");

                        $eventoapuesta_id = $eventoapuesta_idDB[0]['int_evento_apuesta.eventoapuesta_id'];

                        $apuestadetalleDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuestadetalle_id,opcion FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ");

                        $apuestadetalle_id = $apuestadetalleDB[0]['int_apuesta_detalle.apuestadetalle_id'];
                        $opcionN = $apuestadetalleDB[0]['int_apuesta_detalle.opcion'];


                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta_detalle (estado,estado_apuesta,nombre,apuestadetalle_id,eventoapuesta_id,valor,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $estado . "' as estado ,'" . $estado_apuesta . "' as estado_apuesta,'" . $opcionN . "' as nombre,'" . $apuestadetalle_id . "' as apuestadetalle_id,'" . $eventoapuesta_id . "' as eventoapuesta_id,'" . $valor . "' as valor,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta_detalle WHERE eventoapuesta_id = '" . $eventoapuesta_id . "' AND apuestadetalle_id = '" . $apuestadetalle_id . "' ) ");
                    }

                    $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                    fwrite($fp, "PASO3 " . time());
                    fclose($fp);
                }
                if ($EventType == 9 || $EventType == 9) {
                    $id = 't1';
                    $tipo = "TEAM1";
                    $valor = $eventSuper->attributes()->Team1;
                    $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                    fwrite($fp, "PASO4 " . time());
                    fclose($fp);

                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_detalle (valor,evento_id,tipo,id,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $valor . "' as valor ,'" . $evento_id . "' as evento_id,'" . $tipo . "' as tipo,'" . $id . "' as id,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_detalle WHERE evento_id = '" . $evento_id . "' AND tipo = '" . $tipo . "' AND id = '" . $id . "'  ) ");

                    $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                    fwrite($fp, "PASO5 " . time());
                    fclose($fp);
                    $id = 't2';
                    $tipo = "TEAM2";
                    $valor = $eventSuper->attributes()->Team2;


                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_detalle (valor,evento_id,tipo,id,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $valor . "' as valor ,'" . $evento_id . "' as evento_id,'" . $tipo . "' as tipo,'" . $id . "' as id,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_detalle WHERE evento_id = '" . $evento_id . "' AND tipo = '" . $tipo . "' AND id = '" . $id . "'  ) ");

                    $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                    fwrite($fp, "PASO6 " . time());
                    fclose($fp);

                    $iterator = 0;
                    foreach ($eventSuper->children() as $mercado) {
                        $NumBets = $mercado->NumBets;
                        $apuestaParent = $mercado->getName();
                        $nombre = $BetTypes[$iterator]->Type;
                        $nombre_traduccion = $BetTypes[$iterator]->Type;
                        $nombre_internacional = $BetTypes[$iterator]->Type;
                        $abreviado = $apuestaParent;
                        $estado = "A";

                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta (nombre,nombre_traduccion,nombre_internacional,abreviado,estado,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $nombre . "' as nombre ,'" . $nombre_traduccion . "' as nombre_traduccion,'" . $nombre_internacional . "' as nombre_internacional,'" . $abreviado . "' as abreviado,'" . $estado . "' as estado,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta WHERE abreviado = '" . $abreviado . "' ) ");


                        $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                        $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];


                        $iterator = $iterator + 1;


                        foreach ($mercado->children() as $detalle) {
                            $Num = $detalle->attributes()->Num;
                            $outcome = $detalle->attributes()->Outcome;
                            $Price = $detalle->attributes()->Price;


                            $Outcome = "Win";
                            $estado = "A";
                            $opcion_id = $Num;
                            $opcion = $outcome;

                            $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta_detalle (opcion_id,apuesta_id,estado,opcion,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $opcion_id . "' as opcion_id ,'" . $apuesta_id . "' as apuesta_id,'" . $estado . "' as estado,'" . $opcion . "' as opcion,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");
                        }
                        $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                        fwrite($fp, "PASO6-1  " . time());
                        fclose($fp);

                        $estado = "A";
                        $nombre = "";
                        $estado_apuesta = "A";

                        $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta (evento_id,apuesta_id,nombre,estado,estado_apuesta,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $evento_id . "' as evento_id ,'" . $apuesta_id . "' as apuesta_id,'" . $nombre . "' as nombre,'" . $estado . "' as estado,'" . $estado_apuesta . "' as estado_apuesta,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                        $eventoapuesta_idDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT eventoapuesta_id,nombre FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "'  ");

                        $eventoapuesta_id = $eventoapuesta_idDB[0]['int_evento_apuesta.eventoapuesta_id'];


                        foreach ($mercado->children() as $detalle) {
                            $Num = $detalle->attributes()->Num;
                            $outcome = $detalle->attributes()->Outcome;
                            $Price = $detalle->attributes()->Price;

                            $estado = "A";
                            $opcion_id = $Num;
                            $opcion = $outcome;
                            $nombre = "";
                            $estado_apuesta = "A";
                            $valor = $Price;

                            if ($valor == "") {
                                $valor = 0;
                            }

                            $apuestaParent = $detalle->getName();

                            $apuestadetalleDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuestadetalle_id,opcion FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ");

                            $apuestadetalle_id = $apuestadetalleDB[0]['int_apuesta_detalle.apuestadetalle_id'];
                            $opcionN = $apuestadetalleDB[0]['int_apuesta_detalle.opcion'];


                            $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta_detalle (estado,estado_apuesta,nombre,apuestadetalle_id,eventoapuesta_id,valor,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $estado . "' as estado ,'" . $estado_apuesta . "' as estado_apuesta,'" . $opcionN . "' as nombre,'" . $apuestadetalle_id . "' as apuestadetalle_id,'" . $eventoapuesta_id . "' as eventoapuesta_id,'" . $valor . "' as valor,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta_detalle WHERE eventoapuesta_id = '" . $eventoapuesta_id . "' AND apuestadetalle_id = '" . $apuestadetalle_id . "' ) ");
                        }


                        $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                        fwrite($fp, "PASO6-2  " . time());
                        fclose($fp);
                    }
                    $fp = fopen('Wlog_' . date("Y-m-d") . '.log', 'a');
                    fwrite($fp, "PASO7 " . time());
                    fclose($fp);
                }

                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }

            $UsuarioRecargaMySqlDAO->getTransaction()->commit();


            break;


        case "NoMoreBets":


            $message->addAttribute('MessageType', $messageSuper->attributes()->MessageType . "_Response");
            $message->addAttribute('ControllerId', $messageSuper->attributes()->ControllerId);
            $message->addAttribute('TransactionId', $messageSuper->attributes()->TransactionId);
            $message->addAttribute('MessageDateTime', $messageSuper->attributes()->MessageDateTime);
            $message->addAttribute('MessageFormatVersion', $messageSuper->attributes()->MessageFormatVersion);

            foreach ($messageSuper->event as $eventSuper) {
                $EventId = $eventSuper->attributes()->EventId;
                $EventGUID = $eventSuper->attributes()->EventGUID;
                $EventType = $eventSuper->attributes()->EventType;
                $EventTime = $eventSuper->attributes()->EventTime;
                $OddsType = $eventSuper->attributes()->OddsType;
                $PayOut = $eventSuper->attributes()->PayOut;
                $NumEachWay = $eventSuper->attributes()->NumEachWay;
                $RaceName = $eventSuper->attributes()->RaceName;
                $WinnerFinishesAtFrame = $eventSuper->attributes()->WinnerFinishesAtFrame;
                $RaceLengthS = $eventSuper->attributes()->RaceLengthS;
                $CourseName = $eventSuper->attributes()->CourseName;
                $CourseIsJumps = $eventSuper->attributes()->CourseIsJumps;
                $CommScriptName = $eventSuper->attributes()->CommScriptName;
                $Racers = $eventSuper->attributes()->Racers;


                $eventoproveedor_id = $EventId;
                $nombre = $RaceName;
                $nombre_traduccion = $RaceName;
                $nombre_internacional = $RaceName;
                $estado = 'A';
                $fecha = date("Y-m-d H:i:s", strtotime($EventTime));

                switch ($EventType) {
                    case 9:
                        $competencia_id = 1;

                        break;

                    case 0:
                        $competencia_id = 3;

                        break;

                    case 1:
                        $competencia_id = 9;

                        break;

                    default:
                        $competencia_id = 1;

                        break;
                }

                $proveedor_id = 16;

                $depositos = $UsuarioRecargaMySqlDAO->querySQL(" UPDATE  int_evento SET estado='I' WHERE eventoproveedor_id = '" . $eventoproveedor_id . "' ");


                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }
            $UsuarioRecargaMySqlDAO->getTransaction()->commit();


            break;


        case "Result":


            $message->addAttribute('MessageType', $messageSuper->attributes()->MessageType . "_Response");
            $message->addAttribute('ControllerId', $messageSuper->attributes()->ControllerId);
            $message->addAttribute('TransactionId', $messageSuper->attributes()->TransactionId);
            $message->addAttribute('MessageDateTime', $messageSuper->attributes()->MessageDateTime);
            $message->addAttribute('MessageFormatVersion', $messageSuper->attributes()->MessageFormatVersion);

            foreach ($messageSuper->event as $eventSuper) {
                $EventId = $eventSuper->attributes()->EventId;
                $EventGUID = $eventSuper->attributes()->EventGUID;
                $EventType = $eventSuper->attributes()->EventType;
                $EventTime = $eventSuper->attributes()->EventTime;
                $OddsType = $eventSuper->attributes()->OddsType;
                $PayOut = $eventSuper->attributes()->PayOut;
                $NumEachWay = $eventSuper->attributes()->NumEachWay;
                $RaceName = $eventSuper->attributes()->RaceName;
                $WinnerFinishesAtFrame = $eventSuper->attributes()->WinnerFinishesAtFrame;
                $RaceLengthS = $eventSuper->attributes()->RaceLengthS;
                $CourseName = $eventSuper->attributes()->CourseName;
                $CourseIsJumps = $eventSuper->attributes()->CourseIsJumps;
                $CommScriptName = $eventSuper->attributes()->CommScriptName;
                $Racers = $eventSuper->attributes()->Racers;


                $eventoproveedor_id = $EventId;
                $nombre = $RaceName;
                $nombre_traduccion = $RaceName;
                $nombre_internacional = $RaceName;
                $estado = 'A';
                $fecha = date("Y-m-d H:i:s", strtotime($EventTime));

                switch ($EventType) {
                    case 9:
                        $competencia_id = 1;

                        break;

                    case 0:
                        $competencia_id = 3;

                        break;

                    case 1:
                        $competencia_id = 9;

                        break;

                    default:
                        $competencia_id = 1;

                        break;
                }

                $proveedor_id = 16;

                $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento (nombre,nombre_traduccion,nombre_internacional,fecha,estado,usucrea_id,usumodif_id,competencia_id,proveedor_id,eventoproveedor_id)  SELECT * FROM (SELECT '" . $nombre . "' as nombre ,'" . $nombre_traduccion . "' as nombre_traduccion,'" . $nombre_internacional . "' as nombre_internacional,'" . $fecha . "' as fecha,'" . $estado . "' as estado,'0' as usucrea,'0' as usumodif,'" . $competencia_id . "' as competencia_id,'" . $proveedor_id . "' as proveedor_id,'" . $eventoproveedor_id . "' as eventoproveedor_id) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento WHERE eventoproveedor_id = '" . $eventoproveedor_id . "' ) ");


                $eventoDB = $UsuarioRecargaMySqlDAO->querySQL("UPDATE int_evento  SET estado='I' WHERE eventoproveedor_id = '" . $eventoproveedor_id . "' ");

                $eventoDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT evento_id FROM int_evento WHERE eventoproveedor_id = '" . $eventoproveedor_id . "' ");

                $evento_id = $eventoDB[0]['int_evento.evento_id'];

                array_push($eventarray, $evento_id);
                array_push($gamearray, $evento_id);

                //Tipo Apuesta

                $BetTypes = $eventSuper->BetTypes;
                $NumBetTypes = $BetTypes->attributes()->NumBetTypes;


                foreach ($BetTypes->BetType as $BetType) {
                    $Num = $BetType->attributes()->Num;
                    $Type = $BetType->attributes()->Type;
                    $Margin = $BetType->attributes()->Margin;
                    $BetCode = $BetType->attributes()->BetCode;
                    $Prices = $BetType->attributes()->Prices;
                    $Num = $BetType->attributes()->Num;

                    $nombre = $Type;
                    $nombre_traduccion = $Type;
                    $nombre_internacional = $Type;
                    $abreviado = $Type;
                    $estado = 'A';

                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta (nombre,nombre_traduccion,nombre_internacional,abreviado,estado,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $nombre . "' as nombre ,'" . $nombre_traduccion . "' as nombre_traduccion,'" . $nombre_internacional . "' as nombre_internacional,'" . $abreviado . "' as abreviado,'" . $estado . "' as estado,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta WHERE abreviado = '" . $abreviado . "' ) ");
                }


                //Caballos
                foreach ($eventSuper->racer as $racer) {
                    $Num = $racer->attributes()->Num;
                    $Lane = $racer->attributes()->Lane;
                    $Name = $racer->attributes()->Name;
                    $Price = $racer->attributes()->Price;
                    $Fav = $racer->attributes()->Fav;
                    $ProbWin = $racer->attributes()->ProbWin;
                    $Form = $racer->attributes()->Form;
                    $RacerTextureID = $racer->attributes()->RacerTextureID;
                    $Rank = $racer->attributes()->Rank;
                    $Human = $racer->attributes()->Human;
                    $HumanTextureID = $racer->attributes()->HumanTextureID;
                    $Place = $racer->attributes()->Place;

                    $id = "Racer" . $Num;
                    $tipo = "RACER";
                    $valor = $Name;


                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_detalle (valor,evento_id,tipo,id,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $valor . "' as valor ,'" . $evento_id . "' as evento_id,'" . $tipo . "' as tipo,'" . $id . "' as id,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_detalle WHERE evento_id = '" . $evento_id . "' AND tipo = '" . $tipo . "' AND id = '" . $id . "'  ) ");

                    $outcome = "Win";
                    $estado = "A";
                    $opcion = $outcome;
                    $opcion_id = $Num;

                    $apuestaParent = $outcome;

                    $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                    $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];


                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta_detalle (opcion_id,apuesta_id,estado,opcion,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $opcion_id . "' as opcion_id ,'" . $apuesta_id . "' as apuesta_id,'" . $estado . "' as estado,'" . $opcion . "' as opcion,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                    $outcome = "Place2";
                    $estado = "A";
                    $opcion = $outcome;
                    $opcion_id = $Num;

                    $apuestaParent = $outcome;

                    $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                    $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];


                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_apuesta_detalle (opcion_id,apuesta_id,estado,opcion,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $opcion_id . "' as opcion_id ,'" . $apuesta_id . "' as apuesta_id,'" . $estado . "' as estado,'" . $opcion . "' as opcion,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");
                }

                //Caballos
                foreach ($eventSuper->racer as $racer) {
                    $Num = $racer->attributes()->Num;
                    $Lane = $racer->attributes()->Lane;
                    $Name = $racer->attributes()->Name;
                    $Price = $racer->attributes()->Price;
                    $Fav = $racer->attributes()->Fav;
                    $ProbWin = $racer->attributes()->ProbWin;
                    $Form = $racer->attributes()->Form;
                    $RacerTextureID = $racer->attributes()->RacerTextureID;
                    $Rank = $racer->attributes()->Rank;
                    $Human = $racer->attributes()->Human;
                    $HumanTextureID = $racer->attributes()->HumanTextureID;
                    $Place = $racer->attributes()->Place;

                    $Position = $racer->attributes()->Position;

                    $id = "Racer" . $Num;
                    $tipo = "RACER";
                    $valor = $Name;


                    $outcome = "Win";
                    $estado = "I";
                    $opcion = $Num;
                    $opcion_id = $outcome;
                    $nombre = "";
                    $estado_apuesta = "A";
                    $valor = $Price;


                    $apuestaParent = $outcome;

                    $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id,nombre FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                    $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];
                    $nombre = $apuestaDB[0]['int_apuesta.nombre'];


                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta (evento_id,apuesta_id,nombre,estado,estado_apuesta,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $evento_id . "' as evento_id ,'" . $apuesta_id . "' as apuesta_id,'" . $nombre . "' as nombre,'" . $estado . "' as estado,'" . $estado_apuesta . "' as estado_apuesta,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                    $eventoapuesta_idDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT eventoapuesta_id,nombre FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "'  ");

                    $eventoapuesta_id = $eventoapuesta_idDB[0]['int_evento_apuesta.eventoapuesta_id'];

                    $apuestadetalleDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuestadetalle_id,opcion FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion . "' AND apuesta_id = '" . $apuesta_id . "' ");

                    $apuestadetalle_id = $apuestadetalleDB[0]['int_apuesta_detalle.apuestadetalle_id'];
                    $opcionN = $apuestadetalleDB[0]['int_apuesta_detalle.opcion'];


                    if ($Position == "1") {
                        $estado_apuesta = "G";
                    } else {
                        $estado_apuesta = "P";
                    }

                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" UPDATE int_evento_apuesta_detalle  SET estado_apuesta='" . $estado_apuesta . "' WHERE eventoapuesta_id = '" . $eventoapuesta_id . "' AND apuestadetalle_id = '" . $apuestadetalle_id . "'  ");


                    $outcome = "Place2";
                    $estado = "I";
                    $opcion = $Num;
                    $opcion_id = $outcome;
                    $valor = $Place;


                    $apuestaParent = $outcome;
                    $apuestaDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuesta_id,nombre FROM int_apuesta WHERE abreviado = '" . $apuestaParent . "' ");

                    $apuesta_id = $apuestaDB[0]['int_apuesta.apuesta_id'];
                    $nombre = $apuestaDB[0]['int_apuesta.nombre'];


                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" INSERT INTO int_evento_apuesta (evento_id,apuesta_id,nombre,estado,estado_apuesta,usucrea_id,usumodif_id)  SELECT * FROM (SELECT '" . $evento_id . "' as evento_id ,'" . $apuesta_id . "' as apuesta_id,'" . $nombre . "' as nombre,'" . $estado . "' as estado,'" . $estado_apuesta . "' as estado_apuesta,'0' as usucrea,'0' as usumodif) AS tmp WHERE NOT EXISTS ( SELECT 1 FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "' ) ");

                    $eventoapuesta_idDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT eventoapuesta_id,nombre FROM int_evento_apuesta WHERE evento_id = '" . $evento_id . "' AND apuesta_id = '" . $apuesta_id . "'  ");

                    $eventoapuesta_id = $eventoapuesta_idDB[0]['int_evento_apuesta.eventoapuesta_id'];

                    $apuestadetalleDB = $UsuarioRecargaMySqlDAO->querySQL("SELECT apuestadetalle_id,opcion FROM int_apuesta_detalle WHERE opcion_id = '" . $opcion . "' AND apuesta_id = '" . $apuesta_id . "' ");

                    $apuestadetalle_id = $apuestadetalleDB[0]['int_apuesta_detalle.apuestadetalle_id'];
                    $opcionN = $apuestadetalleDB[0]['int_apuesta_detalle.opcion'];


                    if ($Position == "2") {
                        $estado_apuesta = "G";
                    } else {
                        $estado_apuesta = "P";
                    }

                    $depositos = $UsuarioRecargaMySqlDAO->querySQL(" UPDATE int_evento_apuesta_detalle  SET estado_apuesta='" . $estado_apuesta . "' WHERE eventoapuesta_id = '" . $eventoapuesta_id . "' AND apuestadetalle_id = '" . $apuestadetalle_id . "'  ");
                }


                $event = $message->addChild('event');
                $event->addAttribute('EventId', $eventSuper->attributes()->EventId);
                $event->addAttribute('EventGUID', $eventSuper->attributes()->EventGUID);
                $event->addAttribute('EventType', $eventSuper->attributes()->EventType);
                $event->addAttribute('EventTime', $eventSuper->attributes()->EventTime);
                $event->addAttribute('Status', "ACK");
                $event->addAttribute('Details', "Message accepted");
            }


            $UsuarioRecargaMySqlDAO->getTransaction()->commit();

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

/**
 * Sends a message with game and event data to a WebSocket user.
 *
 * @param array $gamearray  Array of game IDs to process.
 * @param array $eventarray Array of event IDs to process.
 *
 * The function processes the provided game and event data, applies filters, and organizes the data
 * into a structured format. It then sends the processed data to a WebSocket user for further use.
 * The function handles multiple levels of data, including events, markets, games, competitions,
 * regions, and sports.
 *
 * @return void
 * @throws Exception If an error occurs during processing.
 */
function sendM($gamearray, $eventarray)
{
    if (oldCount($gamearray) > 0 && oldCount($eventarray) > 0) {
        try {

            $result_array_final = array();
            $subid = "-";

            $objfin = "";
            $objfirst = "";
            $objinicio = array();


            $what = array(
                "event" => ["id", "price"],
                "market" => ["id"],
                "game" => ["id"],
                "competition" => ["id", "name"],
                "region" => ["id"],
                "sport" => ["id", "alias"]
            );

            $where = array(
                "game" => array(
                    "id" => array(
                        "@in" => $gamearray
                    )
                ),
                "event" => array(
                    "id" => array(
                        "@in" => $eventarray
                    )
                )
            );

            $what = array(
                "event" => ["id", "price"],
                "market" => ["id"],
                "game" => ["id"],
                "competition" => ["id", "name"],
                "region" => ["id"],
                "sport" => ["id", "alias"]
            );

            $where = array(
                "game" => array(
                    "id" => array(
                        "@in" => $gamearray
                    )
                )
            );

            $what = json_decode(json_encode($what));
            $where = json_decode(json_encode($where));

            $array_final = array();


            if ($what->event != "" && $what->event != undefined) {
                $result_array = array();

                $campos = "";
                $cont = 0;

                $rules = [];

                if ($where->event != "" && $where->event != undefined) {
                    foreach ($where->event as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_evento_apuesta_detalle.eventapudetalle_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }

                        if (is_numeric($value)) {
                            $op = "eq";
                            $data = $value;
                        }

                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }

                if ($where->game != "" && $where->game != undefined) {
                    foreach ($where->game as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_evento.evento_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }

                        if (is_numeric($value)) {
                            $op = "eq";
                            $data = $value;
                        }

                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntEventoApuestaDetalle = new IntEventoApuestaDetalle();
                $apuestas = $IntEventoApuestaDetalle->getEventoApuestaDetallesCustom("int_evento_apuesta_detalle.*,int_apuesta_detalle.*", "int_evento_apuesta_detalle.eventapudetalle_id", "asc", 0, 10000, $jsonfiltro, true);
                $apuestas = json_decode($apuestas);


                $final = array();

                foreach ($apuestas->data as $apuesta) {
                    $array = array();
                    $arrayd = array();

                    foreach ($what->event as $campo) {
                        switch ($campo) {
                            case "id":
                                $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});

                                break;

                            case "name":
                                $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion"};

                                break;

                            case "type":
                                $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                                break;

                            case "type_1":
                                $arrayd[$campo] = $apuesta->{"int_apuesta_detalle.opcion_id"};

                                break;
                            case "price":
                                $arrayd[$campo] = $apuesta->{"int_evento_apuesta_detalle.valor"};

                                break;
                        }
                    }

                    if (oldCount($what->event) == 0) {
                        $arrayd["id"] = intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                        $arrayd["name"] = $apuesta->{"int_apuesta_detalle.opcion"};
                        $arrayd["name_template"] = $apuesta->{"int_apuesta_detalle.opcion"};
                        $arrayd["price"] = $apuesta->{"int_evento_apuesta_detalle.valor"};
                        $arrayd["type"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                        $arrayd["type_1"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                        $arrayd["type_id"] = $apuesta->{"int_apuesta_detalle.opcion_id"};
                    }
                    array_push($objinicio, intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"}));
                    $subidsum = $subidsum + intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"});
                    $objfirst = "event";

                    if ($apuesta->{"int_evento_apuesta_detalle.estado"} != "A") {
                        $arrayd["price"] = "1";
                    }

                    //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;


                    if (is_array($what->market)) {
                        $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                        $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] = $result_array["market"][intval($apuesta->{"int_evento_apuesta_detalle.eventoapuesta_id"})]["col_count"] + 1;
                    } else {
                        $result_array["event"][intval($apuesta->{"int_evento_apuesta_detalle.eventapudetalle_id"})] = $arrayd;
                    }


                    $objfin = "event";
                }


                $result_array_final = $result_array;
            }


            if ($what->market != "" && $what->market != undefined) {
                $result_array = array();

                $campos = "";
                $cont = 0;

                $rules = [];
                array_push($rules, array("field" => "int_evento.estado", "data" => "A", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntEventoApuesta = new IntEventoApuesta();
                $apuestas = $IntEventoApuesta->getEventoApuestasCustom("int_evento_apuesta.*,int_apuesta.*", "int_evento_apuesta.eventoapuesta_id", "asc", 0, 10000, $jsonfiltro, true);
                $apuestas = json_decode($apuestas);


                $final = array();

                foreach ($apuestas->data as $apuesta) {
                    $array = array();
                    $arrayd = array();

                    foreach ($what->market as $campo) {
                        switch ($campo) {
                            case "id":
                                $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                                break;

                            case "name":
                                $arrayd[$campo] = $apuesta->{"int_apuesta.nombre"};

                                break;

                            case "alias":
                                $arrayd[$campo] = $apuesta->{"int_apuesta.abreviado"};

                                break;

                            case "order":
                                $arrayd[$campo] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});

                                break;
                        }
                    }

                    if (oldCount($what->market) == 0) {
                        $arrayd["id"] = intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"});
                        $arrayd["market_type"] = $apuesta->{"int_apuesta.abreviado"};
                        $arrayd["name"] = $apuesta->{"int_apuesta.nombre"};
                        $arrayd["name_template"] = $apuesta->{"int_apuesta.nombre"};
                        $arrayd["optimal"] = false;
                        $arrayd["order"] = 1000;
                        $arrayd["point_sequence"] = 0;
                        $arrayd["sequence"] = 0;
                        $arrayd["cashout"] = 0;
                    }

                    //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;
                    $seguir = true;
                    if (is_array($what->event)) {
                        $arrayd["event"] = $result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"];
                        //$arrayd["col_count"]=$result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["col_count"];
                        if ($result_array_final["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})]["event"] == "") {
                            $seguir = true;
                        }
                        if (oldCount($arrayd["event"]) <= 0) {
                            $seguir = false;
                        }
                    }
                    if ($seguir) {
                        if (oldCount($objinicio) == 0) {
                            array_push($objinicio, intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"}));

                            $objfirst = "market";
                        }
                        if (is_array($what->game)) {
                            $result_array["game"][intval($apuesta->{"int_evento_apuesta.evento_id"})]["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
                        } else {
                            $result_array["market"][intval($apuesta->{"int_evento_apuesta.eventoapuesta_id"})] = $arrayd;
                        }
                    }
                }


                $result_array_final = $result_array;
                $objfin = "market";
            }

            if (is_array($what->game)) {
                $campos = "";
                $cont = 0;

                $rules = [];

                if ($where->competition != "" && $where->competition != undefined) {
                    foreach ($where->competition as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_competencia.competencia_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }

                        if (is_numeric($value)) {
                            $op = "eq";
                            $data = $value;
                        }


                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }
                if ($where->sport != "" && $where->sport != undefined) {
                    foreach ($where->sport as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_deporte.deporte_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }

                        if (is_numeric($value)) {
                            $op = "eq";
                            $data = $value;
                        }

                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }
                if ($where->game != "" && $where->game != undefined) {
                    foreach ($where->game as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_evento.evento_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }

                        if (is_numeric($value)) {
                            $op = "eq";
                            $data = $value;
                        }

                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntEventoDetalle = new IntEventoDetalle();
                $eventos = $IntEventoDetalle->getEventoDetallesCustom("int_evento_detalle.*,int_evento.*", "int_evento_detalle.evento_id", "asc", 0, 10000, $jsonfiltro, true);
                $eventos = json_decode($eventos);


                $final = array();
                $arrayd = array();
                $eventoid = "";

                foreach ($eventos->data as $evento) {
                    $array = array();

                    foreach ($what->game as $campo) {
                        switch ($campo) {
                            case "team1_name":

                                if ($evento->{"int_evento_detalle.tipo"} === "TEAM1") {
                                    $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                }


                                break;

                            case "team2_name":
                                if ($evento->{"int_evento_detalle.tipo"} == "TEAM2") {
                                    $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                }
                                break;

                            case "text_info":
                                if ($evento->{"int_evento_detalle.tipo"} == "TEAM1") {
                                    // $arrayd[$campo] = $evento->{"int_evento_detalle.valor"};
                                }
                                break;
                        }
                    }
                    if (oldCount($what->game) == 0) {
                        switch ($evento->{"int_evento_detalle.tipo"}) {
                            case "TEAM1":

                                $arrayd["team1_name"] = $evento->{"int_evento_detalle.valor"};
                                $arrayd["info"]["virtual"][0] = array(
                                    "AnimalName" => "",
                                    "Number" => 1,
                                    "PlayerName" => $evento->{"int_evento_detalle.valor"}
                                );

                                break;

                            case "TEAM2":
                                $arrayd["team2_name"] = $evento->{"int_evento_detalle.valor"};
                                $arrayd["info"]["virtual"][1] = array(
                                    "AnimalName" => "",
                                    "Number" => 2,
                                    "PlayerName" => $evento->{"int_evento_detalle.valor"}
                                );
                                break;
                        }
                    }

                    if ($eventoid != intval($evento->{"int_evento.evento_id"}) && $eventoid != "") {
                        $arrayd["game_number"] = $eventoid;
                        $arrayd["id"] = $eventoid;
                        $arrayd["start_ts"] = $eventoA->{"int_evento.fecha"};
                        $arrayd["type"] = 0;

                        $is_blocked = 0;

                        if ($eventoA->{"int_evento.estado"} != "A") {
                            $is_blocked = 1;
                        }

                        $arrayd["is_blocked"] = $is_blocked;

                        if (is_array($what->market)) {
                            $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];
                        }


                        if (is_array($what->competition)) {
                            $result_array["competition"][intval($eventoA->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                        } else {
                            $result_array["game"][$eventoid] = $arrayd;
                        }
                        $arrayd = array();
                    }
                    $eventoid = intval($evento->{"int_evento.evento_id"});
                    $eventoA = $evento;
                    //array_push($final, $array);

                }

                $arrayd["game_number"] = $eventoid;
                $arrayd["id"] = $eventoid;
                $arrayd["start_ts"] = $evento->{"int_evento.fecha"};
                $arrayd["type"] = 0;
                $is_blocked = 0;

                if ($evento->{"int_evento.estado"} != "A") {
                    $is_blocked = 1;
                }

                $arrayd["is_blocked"] = $is_blocked;


                if (is_array($what->market)) {
                    $arrayd["market"] = $result_array_final["game"][$eventoid]["market"];
                }

                if (is_array($what->competition)) {
                    $result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"][$eventoid] = $arrayd;
                    if (oldCount($result_array["competition"][intval($evento->{"int_evento.competencia_id"})]["game"]) == 1) {
                        //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                    }
                } else {
                    $result_array["game"][$eventoid] = $arrayd;

                    if (oldCount($result_array["game"]) == 1) {
                        //$subid=$subid."501".$evento->{"int_evento.evento_id"};

                    }
                }
                if (oldCount($objinicio) == 0) {
                    array_push($objinicio, intval($evento->{"int_evento.evento_id"}));
                    $objfirst = "game";
                }

                $objfin = "game";

                $result_array_final = $result_array;
            }

            if ($what->competition != "" && $what->competition != undefined) {
                $result_array = array();

                $campos = "";
                $cont = 0;

                $rules = [];

                if ($where->competition != "" && $where->competition != undefined) {
                    foreach ($where->competition as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_competencia.competencia_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }


                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntCompetencia = new IntCompetencia();
                $competencias = $IntCompetencia->getCompetenciasCustom("int_competencia.*", "int_competencia.competencia_id", "asc", 0, 10000, $jsonfiltro, true);
                $competencias = json_decode($competencias);


                $final = array();

                foreach ($competencias->data as $competencia) {
                    $array = array();
                    $arrayd = array();

                    foreach ($what->competition as $campo) {
                        switch ($campo) {
                            case "id":
                                $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                                break;

                            case "name":
                                $arrayd[$campo] = $competencia->{"int_competencia.nombre"};

                                break;

                            case "alias":
                                $arrayd[$campo] = $competencia->{"int_competencia.abreviado"};

                                break;

                            case "order":
                                $arrayd[$campo] = intval($competencia->{"int_competencia.competencia_id"});

                                break;
                        }
                    }

                    //                            $final[$competencia->{"int_competencia.competencia_id"}] = $arrayd;

                    if (is_array($what->game)) {
                        $arrayd["game"] = $result_array_final["competition"][intval($competencia->{"int_competencia.competencia_id"})]["game"];
                    }
                    if (is_array($what->region)) {
                        $result_array["region"][intval($competencia->{"int_competencia.region_id"})]["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                    } else {
                        $result_array["competition"][intval($competencia->{"int_competencia.competencia_id"})] = $arrayd;
                    }
                    if (oldCount($objinicio) == 0) {
                        array_push($objinicio, intval($competencia->{"int_competencia.competencia_id"}));

                        $objfirst = "competition";
                    }
                }

                if (oldCount($competencias->data) == 1) {
                    //$subid=$subid."401".$competencia->{"int_competencia.competencia_id"};

                }

                $objfin = "competition";

                $result_array_final = $result_array;
            }

            if ($what->region != "" && $what->region != undefined) {
                $result_array = array();
                $campos = "";
                $cont = 0;

                $rules = [];

                if ($where->region != "" && $where->region != undefined) {
                    foreach ($where->competition as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_region.region_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }


                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntRegion = new IntRegion();
                $regiones = $IntRegion->getRegionesCustom("int_region.*", "int_region.region_id", "asc", 0, 10000, $jsonfiltro, true);
                $regiones = json_decode($regiones);


                $final = array();

                foreach ($regiones->data as $region) {
                    $array = array();
                    $arrayd = array();

                    foreach ($what->competition as $campo) {
                        switch ($campo) {
                            case "id":
                                $arrayd[$campo] = intval($region->{"int_region.region_id"});

                                break;

                            case "name":
                                $arrayd[$campo] = $region->{"int_region.nombre"};

                                break;

                            case "alias":
                                $arrayd[$campo] = $region->{"int_region.abreviado"};

                                break;

                            case "order":
                                $arrayd[$campo] = intval($region->{"int_region.region_id"});

                                break;
                        }
                    }


                    if (is_array($what->competition)) {
                        $arrayd["competition"] = $result_array_final["region"][intval($region->{"int_region.region_id"})]["competition"];
                    }

                    if (is_array($what->sport)) {
                        $result_array["sport"][intval($region->{"int_region.deporte_id"})]["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                    } else {
                        $result_array["region"][intval($region->{"int_region.region_id"})] = $arrayd;
                    }
                    if (oldCount($objinicio) == 0) {
                        array_push($objinicio, intval($region->{"int_region.region_id"}));

                        $objfirst = "region";
                    }
                }

                if (oldCount($regiones->data) == 1) {
                    //$subid=$subid."301".$region->{"int_region.region_id"};

                }

                $objfin = "region";

                $result_array_final = $result_array;
            }

            if ($what->sport != "" && $what->sport != undefined) {
                $campos = "";
                $cont = 0;

                $rules = [];

                if ($where->sport != "" && $where->sport != undefined) {
                    foreach ($where->sport as $key => $value) {
                        $field = "";
                        $op = "";
                        $data = "";

                        switch ($key) {
                            case "id":
                                $field = "int_deporte.deporte_id";
                                break;

                            case "name":

                                break;

                            case "alias":

                                break;

                            case "order":

                                break;
                        }
                        if ($value->{'@in'} != undefined && $value->{'@in'} != "") {
                            $op = "in";
                            $data_array = $value->{'@in'};
                            $data = "";

                            foreach ($data_array as $item) {
                                $data = $data . $item . ",";
                            }
                            $data = trim($data, ",");
                        }


                        if ($field != "") {
                            array_push($rules, array("field" => $field, "data" => $data, "op" => $op));
                        }
                    }
                }

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $jsonfiltro = json_encode($filtro);


                $IntDeporte = new IntDeporte();
                $sports = $IntDeporte->getDeportesCustom("int_deporte.*", "int_deporte.deporte_id", "asc", 0, 10000, $jsonfiltro, true);
                $sports = json_decode($sports);


                $final = array();

                foreach ($sports->data as $sport) {
                    $array = array();
                    $arrayd = array();

                    foreach ($what->sport as $campo) {
                        switch ($campo) {
                            case "id":
                                $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                                break;

                            case "name":
                                $arrayd[$campo] = $sport->{"int_deporte.nombre"};

                                break;

                            case "alias":
                                $arrayd[$campo] = $sport->{"int_deporte.abreviado"};

                                break;

                            case "order":
                                $arrayd[$campo] = intval($sport->{"int_deporte.deporte_id"});

                                break;
                        }
                    }

                    $final[$sport->{"int_deporte.deporte_id"}] = $arrayd;

                    if (is_array($what->region)) {
                        $arrayd["region"] = $result_array_final["sport"][intval($sport->{"int_deporte.deporte_id"})]["region"];

                        $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
                    } else {
                        $result_array["sport"][intval($sport->{"int_deporte.deporte_id"})] = $arrayd;
                    }

                    if (oldCount($objinicio) == 0) {
                        array_push($objinicio, intval($sport->{"int_deporte.deporte_id"}));

                        $objfirst = "sport";
                    }
                    //array_push($final, $array);

                }

                if (oldCount($sports->data) == 1) {
                    //$subid=$subid."201".$sport->{"int_deporte.deporte_id"};

                }

                $result_array_final = $result_array;

                $objfin = "sport";
            }

            $responseW = array();

            $responseW = array("end" => $objfirst, "first" => $objfin, "ids" => $objinicio, "data" => $result_array_final);


            /*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
            $WebsocketUsuario = new WebsocketUsuario(0, ($responseW));
            $WebsocketUsuario->sendWSMessage();


            $response["ErrorCode"] = 0;
            $response["ErrorDescription"] = "success";

            $response = $response;
        } catch (Exception $e) {
            throw $e;
            $response["ErrorCode"] = $e->getCode();
            $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode() . $e->getMessage();
        }
    }
}
