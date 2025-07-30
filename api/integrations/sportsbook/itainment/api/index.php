<?php

/**
 * Archivo principal para manejar las integraciones con el sistema de apuestas deportivas de Itainment.
 *
 * Este archivo procesa solicitudes HTTP, analiza datos XML y ejecuta diferentes operaciones relacionadas
 * con apuestas, como obtener detalles de cuentas, realizar apuestas, otorgar premios, entre otros.
 *
 * @category   Integración
 * @package    API
 * @subpackage Sportsbook
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                 Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $URI                  Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER              Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body                 Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $method               Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $data                 Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $log                  Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $Auth                 Variable que almacena información de autenticación.
 * @var mixed $Login                Variable que almacena la información de inicio de sesión de un usuario en el sistema.
 * @var mixed $Password             Variable que almacena una contraseña o clave de acceso.
 * @var mixed $Params               Variable que almacena parámetros utilizados para configurar o pasar datos a una función o proceso.
 * @var mixed $Token                Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Itainment            Variable que puede estar relacionada con un sistema o plataforma de entretenimiento (como juegos en línea).
 * @var mixed $response             Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $ExternalUserID       Variable que almacena el identificador único del usuario en un sistema externo.
 * @var mixed $SiteId               Variable que almacena el identificador único de un sitio o plataforma.
 * @var mixed $TransactionID        Variable que almacena el identificador único de una transacción.
 * @var mixed $BetReferenceNum      Variable que almacena el número de referencia asociado a una apuesta.
 * @var mixed $BetAmount            Variable que almacena el monto de una apuesta realizada.
 * @var mixed $GameReference        Variable que almacena la referencia única de un juego en una plataforma o sistema.
 * @var mixed $Description          Variable que almacena una descripción detallada de un proceso, elemento o transacción.
 * @var mixed $FrontendType         Variable que almacena el tipo de interfaz frontend (por ejemplo, móvil, web) utilizada para interactuar con el sistema.
 * @var mixed $BetStatus            Variable que almacena el estado de una apuesta (por ejemplo, pendiente, ganada, perdida).
 * @var mixed $BonusBalance         Variable que almacena el saldo disponible de bonificaciones o premios.
 * @var mixed $BonusAccountId       Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $CantLineas           Variable que almacena la cantidad de líneas en un juego o apuesta (por ejemplo, líneas de pago en una tragamonedas).
 * @var mixed $BetMode              Variable que almacena el modo en que se realiza una apuesta (por ejemplo, manual o automática).
 * @var mixed $DirIp                Variable que almacena la dirección IP del dispositivo o usuario.
 * @var mixed $IsSystem             Variable que indica si una operación fue realizada por el sistema (automáticamente) en lugar de un usuario.
 * @var mixed $EventCount           Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $BankerCount          Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $Events               Variable que almacena una lista de eventos asociados a una operación o sistema.
 * @var mixed $PremioProy           Variable que almacena el premio proyectado en un juego o transacción.
 * @var mixed $impuesto             Variable que almacena el monto de impuesto aplicado a una transacción o proceso.
 * @var mixed $ValorTotal           Variable que almacena el valor total de una transacción o cálculo.
 * @var mixed $datos                Variable que almacena datos genéricos.
 * @var mixed $detalles             Variable que almacena detalles adicionales o información más específica sobre un proceso o elemento.
 * @var mixed $info                 Variable que almacena información general o específica relacionada con un proceso o acción.
 * @var mixed $time                 Variable que almacena información de tiempo.
 * @var mixed $tosub                Variable que podría estar relacionada con un proceso de suscripción o envío de información.
 * @var mixed $detalle              Variable que almacena información detallada sobre una operación o elemento.
 * @var mixed $cont2                Variable que puede almacenar un contador o valor relacionado con un proceso o conjunto de datos.
 * @var mixed $info2                Variable que almacena información adicional, posiblemente complementaria a la variable "info".
 * @var mixed $gameId               Variable que almacena el identificador de un juego.
 * @var mixed $WinReferenceNum      Variable que almacena el número de referencia único asociado a una ganancia o premio.
 * @var mixed $WinAmount            Variable que almacena el monto de una ganancia o premio obtenido.
 * @var mixed $GameStatus           Variable que almacena el estado actual de un juego (por ejemplo, en curso, finalizado).
 * @var mixed $isEnd                Variable que indica si un proceso o juego ha terminado (valor booleano).
 * @var mixed $BonusAmount          Variable que almacena el monto de un bono otorgado en un proceso o transacción.
 * @var mixed $Description_Explode  Variable que almacena una descripción que ha sido desglosada o separada en varios elementos.
 * @var mixed $Description2_Explode Variable que almacena una segunda descripción que ha sido desglosada o separada en partes.
 * @var mixed $BonusPlanId          Variable que almacena el identificador único de un plan de bonificación o promoción.
 * @var mixed $BonusCode            Variable que almacena el código asociado a un bono o promoción.
 * @var mixed $BonusStatus          Variable que almacena el estado actual de un bono (por ejemplo, activo, usado, caducado).
 * @var mixed $RefundAmount         Variable que almacena el monto a ser reembolsado en una transacción o proceso.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\sportsbook\Itainment;

date_default_timezone_set('America/Bogota');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        error_reporting(E_ALL);
        ini_set("display_errors", "ON");
        $_ENV['debug'] = true;
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];

$body = trim(file_get_contents('php://input'));

$method = "";
date_default_timezone_set('America/Bogota');

if ($body != "") {
        Header('Content-type: application/xml');
        $data = simplexml_load_string($body);
        $method = $data->Method->attributes()->Name;
}

switch ($method) {
        case "GetAccountDetails":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;


                $Itainment = new Itainment("", "", $Token);
                $response = ($Itainment->Auth());

                print_r($response);
                break;

        case "GetBalance":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $ExternalUserID = $Params->ExternalUserID->attributes()->Value;
                $SiteId = $Params->SiteId->attributes()->SiteId;

                $Itainment = new Itainment("", "", $Token);
                $response = ($Itainment->getBalance());

                print_r($response);
                break;

        case "PlaceBet":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = (string)($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->BetReferenceNum->attributes()->Value);
                $BetAmount = intval($Params->BetAmount->attributes()->Value);
                $BetAmount = $BetAmount / 100;

                $GameReference = $Params->GameReference->attributes()->Value;
                $Description = $Params->Description->attributes()->Description;
                $ExternalUserID = $Params->ExternalUserID->attributes()->Value;
                $SiteId = $Params->SiteId->attributes()->SiteId;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $BonusBalance = $Params->BonusBalance->attributes()->Value;
                $BonusAccountId = $Params->BonusAccountId->attributes()->Value;

                if (! empty($data->Method->Params->Bet)) {
                        $CantLineas = $Params->Bet->EventCount->attributes()->Value;
                        $BetMode = $Params->BetMode->attributes()->Value;
                        $DirIp = $Params->ClientIP->attributes()->Value;
                        $IsSystem = $Params->Bet->IsSystem->attributes()->Value;
                        $EventCount = $Params->Bet->EventCount->attributes()->Value;
                        $BankerCount = $Params->Bet->BankerCount->attributes()->Value;
                        $Events = $Params->Bet->Events->attributes()->Value;

                        $DirIp = $Params->ClientIP->attributes()->Value;
                        $PremioProy = $Params->Bet->BetStake->Winnings->attributes()->Value;
                        $PremioProy = intval($PremioProy);
                        $PremioProy = $PremioProy / 100;

                        $impuesto = 0;

                        if (! empty($Params->Bet)) {
                                $Itainment = new Itainment("", "", $Token);

                                $datos = array(
                                        "TransactionID" => (string)$TransactionID,
                                        "BetReferenceNum" => (string)$BetReferenceNum,
                                        "BetAmount" => (string)$BetAmount,
                                        "PremioProy" => (string)$GameReference,
                                        "GameReference" => (string)$GameReference,
                                        "BetStatus" => (string)$BetStatus,
                                        "CantLineas" => (string)$CantLineas,
                                        "BetMode" => (string)$BetMode,
                                        "DirIp" => (string)$DirIp,
                                        "PremioProy" => (string)$PremioProy
                                );

                                $detalles = array();

                                foreach ($data->Method->Params->Bet->EventList->children() as $info) {
                                        $time = new DateTime($info->EventDate->attributes()['Value']);
                                        $tosub = new DateInterval('PT5H0M');
                                        $time->sub($tosub);

                                        $detalle = array(
                                                "evento" => (string)str_replace("##", " ", str_replace(array("\r", "\n", '/&(?!;{6})/', "     ", '     ', "/\r|\n/", "\n/", "/\r", "\r\n"), '', preg_replace('/\s*/m', '', preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}\x{231A}\x{23F0}\x{23F3}\x{2B06}\x{2194}\x{23E9}\x{23EA}\x{2B06}\x{2934}\x{2B05}\x{1F004}-\x{1F0CF}\x{1F3C0}-\x{1F3FF}\x{1F1E6}-\x{1F1FF}\x{2B06}\x{2934}\x{2194}\x{1F004}\x{1F0CF}\x{1F004}-\x{1F0CF}]+/u', '', (str_replace(" ", "##", $info->attributes()['Value'])))))),
                                                "fecha" => (string)$time->format('Y-m-d H:i'),
                                                "hora" => (string)$time->format('H:i'),
                                                "eventoid" => (string)$info->EventID->attributes()['Value'],
                                                "sportid" => (string)$info->SportID->attributes()['Value'],
                                                "ligaid" => (string)$info->ChampionshipID->attributes()['Value'],
                                                "agrupador" => (string)str_replace(array("\r", "\n", '/&(?!;{6})/', "      ", "\xE2", "\x80", "\x8B"), '', preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}\x{231A}\x{23F0}\x{23F3}\x{2B06}\x{2194}\x{23E9}\x{23EA}\x{2B06}\x{2934}\x{2B05}\x{1F004}-\x{1F0CF}\x{1F3C0}-\x{1F3FF}\x{1F1E6}-\x{1F1FF}\x{2B06}\x{2934}\x{2194}\x{1F004}\x{1F0CF}\x{1F004}-\x{1F0CF}]+/u', '', str_replace(' ', " ", str_replace('\\', "", $info->Market->attributes()['Value'])))),
                                                "agrupadorid" => (string)$info->Market->MarketID->attributes()['Value'],
                                                "opcion" => (string)str_replace(array("\r", "\n", '/&(?!;{6})/', "     ", "\xE2", "\x80", "\x8B"), '', preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}\x{231A}\x{23F0}\x{23F3}\x{2B06}\x{2194}\x{23E9}\x{23EA}\x{2B06}\x{2934}\x{2B05}\x{1F004}-\x{1F0CF}\x{1F3C0}-\x{1F3FF}\x{1F1E6}-\x{1F1FF}\x{2B06}\x{2934}\x{2194}\x{1F004}\x{1F0CF}\x{1F004}-\x{1F0CF}]+/u', '', preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/', " ", str_replace(' ', " ", str_replace('\\', "", $info->Market->Outcome->attributes()['Value']))))),
                                                "logro" => (string)$info->Market->Odds->attributes()['Value'],
                                                "matchid" => (string)$info->EventID->attributes()['Value'],
                                        );

                                        array_push($detalles, $detalle);

                                        $cont2 = 0;
                                        foreach ($info->children() as $info2) {
                                                $time = new DateTime($info2->EventDate->attributes()['Value']);
                                                $tosub = new DateInterval('PT5H0M');

                                                if ($info2->getName() == "Market") {
                                                        $cont2 = $cont2 + 1;
                                                        if ($cont2 > 1) {
                                                                $detalle = array(
                                                                        "evento" => (string)str_replace("##", " ", str_replace(array("\r", "\n", '/&(?!;{6})/', "     ", '     ', "/\r|\n/", "\n/", "/\r", "\r\n"), '', preg_replace('/\s*/m', '', (str_replace(" ", "##", $info->attributes()['Value']))))),
                                                                        "fecha" => (string)$time->format('Y-m-d H:i'),
                                                                        "hora" => (string)$time->format('H:i'),
                                                                        "eventoid" => (string)$info2->EventID->attributes()['Value'],
                                                                        "sportid" => (string)$info2->SportID->attributes()['Value'],
                                                                        "ligaid" => (string)$info2->ChampionshipID->attributes()['Value'],
                                                                        "agrupador" => (string)str_replace(array("\r", "\n", '/&(?!;{6})/', "      ", "\xE2", "\x80", "\x8B"), '', (str_replace(' ', " ", str_replace('\\', "", $info->Market->attributes()['Value'])))),
                                                                        "agrupadorid" => (string)$info2->Market->MarketID->attributes()['Value'],
                                                                        "opcion" => (string)str_replace(array("\r", "\n", '/&(?!;{6})/', "     ", "\xE2", "\x80", "\x8B"), '', (str_replace(' ', " ", str_replace('\\', "", preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/', " ", $info->Market->Outcome->attributes()['Value']))))),
                                                                        "logro" => (string)$info2->Market->Odds->attributes()['Value'],
                                                                        "matchid" => (string)$info2->EventID->attributes()['Value'],
                                                                );

                                                                array_push($detalles, $detalle);
                                                        }
                                                }
                                        }
                                }

                                $gameId = '1';
                                $response = ($Itainment->Debit($gameId, $BetReferenceNum, "", $BetAmount, $TransactionID, json_decode(json_encode($detalles)), json_decode(json_encode($datos)), $impuesto, $data->asXML()));
                        }

                        print_r($response);
                } else {
                        $BetMode = $Params->BetMode->attributes()->Value;
                        $DirIp = $Params->ClientIP->attributes()->Value;

                        $Itainment = new Itainment("", "", $Token);

                        $datos = array(
                                "TransactionID" => (string)$TransactionID,
                                "BetReferenceNum" => (string)$BetReferenceNum,
                                "BetAmount" => (string)$BetAmount,
                                "PremioProy" => (string)$GameReference,
                                "GameReference" => (string)$GameReference,
                                "BetStatus" => (string)$BetStatus,
                                "CantLineas" => (string)$CantLineas,
                                "BetMode" => (string)$BetMode,
                                "DirIp" => (string)$DirIp,
                                "PremioProy" => (string)$PremioProy
                        );

                        $gameId = '1';
                        $response = ($Itainment->CheckDebit($gameId, $BetReferenceNum, strval($TransactionID), json_decode(json_encode($datos)), $data->asXML()));

                        print_r($response);
                }
                break;

        case "AwardWinnings":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;

                $Token = $Params->Token->attributes()->Value;
                $TransactionID = ($Params->TransactionID->attributes()->Value);
                $WinReferenceNum = intval($Params->WinReferenceNum->attributes()->Value);
                $WinAmount = intval($Params->WinAmount->attributes()->Value);
                $WinAmount = $WinAmount / 100;
                $GameReference = $Params->GameReference->attributes()->Value;
                $GameStatus = $Params->GameStatus->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;

                $Description = str_replace('   ', " ", str_replace('\\', "", $Description));
                $Description = str_replace('  ', " ", $Description);

                $FrontendType = $Params->FrontendType->attributes()->Value;
                $ExternalUserID = $Params->ExternalUserID->attributes()->Value;
                $SiteId = $Params->SiteId->attributes()->SiteId;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token, $ExternalUserID);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "WinReferenceNum" => (string)$WinReferenceNum,
                        "WinAmount" => (string)$WinAmount,
                        "GameReference" => (string)$GameReference,
                        "GameStatus" => (string)$GameStatus,
                        "BetStatus" => (string)$BetStatus,
                        "FrontendType" => (string)$FrontendType,
                        "Description" => (string)$Description,
                        "UserId" => (string)$ExternalUserID,
                        "TipoTransaccion" => 'WIN'
                );

                $gameId = '1';
                $response = ($Itainment->Credit($gameId, $WinReferenceNum, "", $WinAmount, $TransactionID, $isEnd, json_decode(json_encode($datos)), "AwardWinnings", $data->asXML()));

                print_r($response);
                break;

        case "AwardBonus":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value; //OK
                $TransactionID = ($Params->TransactionID->attributes()->Value); //OK
                $BonusAmount = intval($Params->BonusAmount->attributes()->Value); //OK
                $ExternalUserID = $Params->ExternalUserID->attributes()->Value; //OK
                $Description = $Params->Description->attributes()->Value; //OK
                $Description = str_replace('   ', " ", str_replace('\\', "", $Description)); //OK
                $Description = str_replace('  ', " ", $Description); //OK
                $Description_Explode = explode(": ", $Description);

                $Description2_Explode = explode(",", $Description_Explode[1]);
                $Description_Explode = explode(": ", $Description);

                $BonusPlanId = $Description_Explode[0];
                $BonusPlanId = str_replace(' ', '', $BonusPlanId);

                $Description2_Explode = explode(",", $Description_Explode[1]);

                $BonusAccountId = $Params->BonusAccountId->attributes()->Value; //OK$BonusAmount = $BonusAmount / 100;//OK
                $BonusBalance = $Params->BonusBalance->attributes()->Value; //OK$BonusAmount = $BonusAmount / 100;//OK
                $SiteId = $Params->SiteId->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token, $ExternalUserID);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "BonusAmount" => (string)$BonusAmount,
                        "Description" => (string)$Description,
                        "UserId" => (string)$ExternalUserID,
                        "BonusPlanId" => (string)$BonusPlanId,
                        "BonusAccountId" => (string)$BonusAccountId,
                        "BonusBalance" => (string)$BonusBalance,
                        "TipoTransaccion" => 'WINBONUS'
                );

                $gameId = '1';
                $response = ($Itainment->AwardBonus($gameId, (string)$BonusAccountId, (string)$BonusPlanId, $BonusAmount, $TransactionID, json_decode(json_encode($datos)), "AwardBonus", $data->asXML()));

                print_r($response);
                break;

        case "BonusBalance":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value; //OK
                $TransactionID = ($Params->TransactionId->attributes()->Value); //OK
                $BonusAmount = intval($Params->BonusBalance->attributes()->Value); //OK
                $ExternalUserID = $Params->ExternalUserId->attributes()->Value; //OK
                $Description = $Params->Description->attributes()->Value; //OK
                $Description = str_replace('   ', " ", str_replace('\\', "", $Description)); //OK
                $Description = str_replace('  ', " ", $Description); //OK
                $Description_Explode = explode(": ", $Description);

                $Description2_Explode = explode(",", $Description_Explode[1]);
                $Description_Explode = explode(": ", $Description);

                $BonusPlanId = $Params->BonusPlanId->attributes()->Value;
                $Description2_Explode = explode(",", $Description_Explode[1]);

                $BonusAccountId = $Params->BonusAccountId->attributes()->Value; //OK
                $BonusBalance = $Params->BonusBalance->attributes()->Value; //OK
                $SiteId = $Params->SiteId->attributes()->Value;
                $BonusCode = $Params->BonusCode->attributes()->Value;
                $BonusStatus = $Params->BonusStatus->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", '', (string)$ExternalUserID);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "BonusAmount" => (string)$BonusAmount,
                        "Description" => (string)$Description,
                        "UserId" => (string)$ExternalUserID,
                        "BonusPlanId" => (string)$BonusPlanId,
                        "BonusAccountId" => (string)$BonusAccountId,
                        "BonusBalance" => (string)$BonusBalance,
                        "BonusCode" => (string)$BonusCode,
                        "BonusStatus" => (string)$BonusStatus,
                        "TipoTransaccion" => 'CODEBONUS'
                );

                $gameId = '1';
                $response = ($Itainment->BonusBalance($gameId, (string)$BonusStatus, (string)$BonusAccountId, (string)$BonusPlanId, $BonusAmount, $TransactionID, json_decode(json_encode($datos)), "BonusBalance", $data->asXML()));

                print_r($response);
                break;

        case "LossSignal":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = ($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->BetReferenceNum->attributes()->Value);

                $WinAmount = 0;
                $GameReference = $Params->GameReference->attributes()->Value;
                $GameStatus = $Params->GameStatus->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "BetReferenceNum" => (string)$BetReferenceNum,
                        "WinAmount" => (string)$WinAmount,
                        "GameReference" => (string)$GameReference,
                        "GameStatus" => (string)$GameStatus,
                        "BetStatus" => (string)$BetStatus,
                        "FrontendType" => (string)$FrontendType,
                        "Description" => (string)$Description,
                        "TipoTransaccion" => 'LOSS'
                );

                $gameId = '1';
                $response = ($Itainment->Credit($gameId, $BetReferenceNum, "", $WinAmount, $TransactionID, $isEnd, json_decode(json_encode($datos)), "LossSignal", $data->asXML()));

                print_r($response);
                break;

        case "NewCredit":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = ($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->NewCreditReferenceNum->attributes()->Value);

                $WinAmount = intval($Params->NewCreditAmount->attributes()->Value);
                $WinAmount = $WinAmount / 100;

                $GameReference = $Params->GameReference->attributes()->Value;
                $GameStatus = $Params->GameStatus->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "NewCreditReferenceNum" => (string)$BetReferenceNum,
                        "NewCreditAmount" => (string)$WinAmount,
                        "GameReference" => (string)$GameReference,
                        "GameStatus" => (string)$GameStatus,
                        "BetStatus" => (string)$BetStatus,
                        "FrontendType" => (string)$FrontendType,
                        "Description" => (string)$Description,
                        "TipoTransaccion" => 'NEWCREDIT'
                );

                $gameId = '1';
                $response = ($Itainment->Credit($gameId, $BetReferenceNum, "", $WinAmount, $TransactionID, $isEnd, json_decode(json_encode($datos)), "NewCredit", $data->asXML()));

                print_r($response);
                break;

        case "NewDebit":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = ($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->NewDebitReferenceNum->attributes()->Value);

                $WinAmount = intval($Params->NewDebitAmount->attributes()->Value);
                $WinAmount = $WinAmount / 100;

                $GameReference = $Params->GameReference->attributes()->Value;
                $GameStatus = $Params->GameStatus->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "NewDebitReferenceNum" => (string)$BetReferenceNum,
                        "NewDebitAmount" => (string)$WinAmount,
                        "GameReference" => (string)$GameReference,
                        "GameStatus" => (string)$GameStatus,
                        "BetStatus" => (string)$BetStatus,
                        "FrontendType" => (string)$FrontendType,
                        "Description" => (string)$Description,
                        "TipoTransaccion" => 'NEWDEBIT'
                );

                $gameId = '1';
                $response = ($Itainment->Credit($gameId, $BetReferenceNum, "", $WinAmount, $TransactionID, $isEnd, json_decode(json_encode($datos)), "NewDebit", $data->asXML()));

                print_r($response);
                break;

        case "stakeDecrease":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = ($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->stakeDecreaseReferenceNum->attributes()->Value);
                $WinAmount = intval($Params->stakeDecreaseAmount->attributes()->Value);
                $WinAmount = $WinAmount / 100;

                $GameReference = $Params->GameReference->attributes()->Value;
                $GameStatus = $Params->GameStatus->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "stakeDecreaseReferenceNum" => (string)$BetReferenceNum,
                        "stakeDecreaseAmount" => (string)$WinAmount,
                        "GameReference" => (string)$GameReference,
                        "GameStatus" => (string)$GameStatus,
                        "BetStatus" => (string)$BetStatus,
                        "FrontendType" => (string)$FrontendType,
                        "Description" => (string)$Description,
                        "TipoTransaccion" => 'STAKEDECREASE'
                );

                $gameId = '1';
                $response = ($Itainment->Credit($gameId, $BetReferenceNum, "", $WinAmount, $TransactionID, $isEnd, json_decode(json_encode($datos)), "stakeDecrease", $data->asXML()));

                print_r($response);
                break;

        case "CashoutBet":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = ($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->BetReferenceNum->attributes()->Value);
                $WinAmount = intval($Params->CashoutAmount->attributes()->Value);
                $WinAmount = $WinAmount / 100;
                $GameReference = $Params->GameReference->attributes()->Value;
                $GameStatus = $Params->GameStatus->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;

                $isEnd = true;

                $Itainment = new Itainment("", "", $Token);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "BetReferenceNum" => (string)$BetReferenceNum,
                        "CashoutAmount" => (string)$WinAmount,
                        "GameReference" => (string)$GameReference,
                        "GameStatus" => (string)$GameStatus,
                        "BetStatus" => (string)$BetStatus,
                        "FrontendType" => (string)$FrontendType,
                        "Description" => (string)$Description,
                        "TipoTransaccion" => 'CASHOUT'
                );

                $gameId = '1';
                $response = ($Itainment->Credit($gameId, $BetReferenceNum, "", $WinAmount, $TransactionID, $isEnd, json_decode(json_encode($datos)), "CashoutBet", $data->asXML()));

                print_r($response);
                break;

        case "RefundBet":

                $Auth = $data->Method->Auth->attributes();
                $Login = $data->Method->Login->attributes()->Login;
                $Password = $data->Method->Password->attributes()->Password;

                $Params = $data->Method->Params;
                $Token = $Params->Token->attributes()->Value;
                $TransactionID = (string)($Params->TransactionID->attributes()->Value);
                $BetReferenceNum = intval($Params->BetReferenceNum->attributes()->Value);
                $RefundAmount = intval($Params->RefundAmount->attributes()->Value);
                $RefundAmount = $RefundAmount / 100;
                $GameReference = $Params->GameReference->attributes()->Value;
                $BetStatus = $Params->BetStatus->attributes()->Value;
                $Description = $Params->Description->attributes()->Value;
                $FrontendType = $Params->FrontendType->attributes()->Value;
                $ExternalUserID = $Params->ExternalUserID->attributes()->Value;

                if ($_ENV['debug']) {
                        print_r('ExternalUserID');
                        print_r($Params);
                }

                $Itainment = new Itainment("", "", $Token, $ExternalUserID);

                $datos = array(
                        "TransactionID" => (string)$TransactionID,
                        "BetReferenceNum" => (string)$BetReferenceNum,
                        "RefundAmount" => (string)$RefundAmount,
                        "GameReference" => (string)$GameReference,
                        "Description" => (string)$Description,
                        "BetStatus" => (string)$BetStatus,
                        "TipoTransaccion" => 'REFUND'
                );

                $gameId = '1';
                $response = ($Itainment->Rollback($gameId, $BetReferenceNum, "", $RefundAmount, $TransactionID, json_decode(json_encode($datos)), $data->asXML()));

                print_r($response);
                break;

        default:
                break;
}
