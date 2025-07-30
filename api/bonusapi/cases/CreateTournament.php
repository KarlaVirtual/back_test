<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\BonoDetalle;
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\UsuarioTorneo;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;

/**
 * CreateTournament
 * 
 * Crea un nuevo torneo con las configuraciones especificadas
 *
 * @param object $params {
 *   "Description": string,      // Descripción del torneo
 *   "Name": string,            // Nombre del torneo
 *   "BeginDate": string,       // Fecha de inicio del torneo
 *   "EndDate": string,         // Fecha de fin del torneo
 *   "CodeGlobal": string,      // Código global del torneo
 *   "TypeRanking": int,        // Tipo de ranking
 *   "PointValueNetIncome": float, // Valor de puntos por ingreso neto
 *   "Priority": int,           // Prioridad del torneo
 *   "MaxplayersCount": int,    // Número máximo de jugadores
 *   "MinplayersCount": int,    // Número mínimo de jugadores
 *   "ForeignRule": object {    // Reglas adicionales
 *     "Info": object|string    // Información de reglas en formato JSON
 *   }
 * }
 *
 * @return array {
 *   "ErrorCode": int,          // Código de error (0 = éxito)
 *   "ErrorDescription": string, // Descripción del resultado
 *   "TournamentId": int        // ID del torneo creado
 * }
 *
 * @throws Exception           // Errores de procesamiento
 */


// Configuración inicial de errores y depuración
ini_set('display_errors', 'OFF');

if ($_ENV['debug']) {
    print_r($params);
}

// Validación inicial de parámetros y configuración del mandante
if ($params == "" || $params == null) {
    exit();
}
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}

// Extracción de parámetros básicos del torneo
$Description = $params->Description; //Descripcion del bono
$Name = $params->Name; //Nombre del bono
$StartDate = $params->BeginDate; //Fecha Inicial de la campaña
$EndDate = $params->EndDate; //Fecha Final de la campaña
$CodeGlobal = $params->CodeGlobal;
$TypeRanking = $params->TypeRanking;
$PointValueNetIncome = $params->PointValueNetIncome;
$PointValueQuota = $params->PointValueQuota; //Valor de puntos por cuota total

// Configuración del tipo y condiciones del bono
$tipobono = 1;
$ConditionProduct = 'OR';
$Priority = $params->Priority;

if ($Priority == "" || !is_numeric($Priority)) {
    $Priority = 0;
}

// Inicialización de variables de control de jugadores
$cupo = 0;
$cupoMaximo = 0;
$jugadores = 0;
$jugadoresMaximo = 0;
$MaxplayersCount = $params->MaxplayersCount;
$MinplayersCount = $params->MaxplayersCount;
$jugadoresMaximo = $MaxplayersCount;

// Procesamiento de reglas adicionales
$ForeignRule = $params->ForeignRule;
$ForeignRuleInfo = $ForeignRule->Info;

if (!is_object($ForeignRuleInfo)) {
    $ForeignRuleJSON = json_decode($ForeignRuleInfo);
} else {
    $ForeignRuleJSON = $ForeignRuleInfo;
}

// Extracción de configuraciones de apuestas deportivas
$SportsbookDeports2 = $ForeignRuleJSON->SportsbookDeports2;
$SportsbookMarkets2 = $ForeignRuleJSON->SportsbookMarkets2;
$SportsbookLeagues2 = $ForeignRuleJSON->SportsbookLeagues2;
$SportsbookMatches2 = $ForeignRuleJSON->SportsbookMatches2;

// Configuración de reglas de apuestas
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount;
$MinSelPrice = $ForeignRuleJSON->MinSelPrice;
$MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;
$MinBetPrice = $ForeignRuleJSON->MinBetPrice;
$SportBonusRules = $ForeignRuleJSON->SportBonusRules;

// Configuración de URLs de imágenes
$MainImageURL = $params->MainImageURL;
$RankingImageURL = $params->RankingImageURL;
$BackgroundURL = $params->BackgroundURL;
$BackgroundURL2 = $params->BackgroundURL2;
$ImgCenter = $params->ImgCenter;
$ImgCenter2 = $params->ImgCenter2;
$ImgRight = $params->ImgRight;
$ImgAwards = $params->ImgAwards;

// Configuración de premios y rankings
$PrizeDescription = $params->PrizeDescription;
$PrizeImageURL = $params->PrizeImageURL;
$Ranks = $params->Ranks;
$RanksPrize = $params->RanksPrize;
$LinesByPoints = ($params->LinesByPoints == true || $params->LinesByPoints == "true") ? 1 : 0;
$AmountPrize = $params->AmountPrize;
$TypeRule = ($params->TypeRule == 1) ? 1 : 0;

// Procesamiento de detalles del disparador
$TriggerDetails = $params->TriggerDetails;
$Count = $TriggerDetails->Count;
$IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
$PaymentSystemId = $TriggerDetails->PaymentSystemId;
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

// Configuración de repetición y tipos de bonos
$UserRepeatBonus = $params->UserRepeatBonus;
$WinBonusId = $params->WinBonusId;
$TypeSaldo = $params->TypeSaldo;

// Validación y configuración de condiciones de productos
$ConditionProduct = $TriggerDetails->ConditionProduct;
if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
    $ConditionProduct = 'NA';
}

// Extracción de ubicaciones geográficas
$Regions = $TriggerDetails->Regions;
$Departments = $TriggerDetails->Departments;
$Cities = $TriggerDetails->Cities;
$CashDesks = $TriggerDetails->CashDesks;
$RegionsUser = $TriggerDetails->RegionsUser;
$DepartmentsUser = $TriggerDetails->DepartmentsUser;
$CitiesUser = $TriggerDetails->CitiesUser;

// Configuraciones adicionales
$BalanceZero = $TriggerDetails->BalanceZero;
$Casino = $params->Casino->Info;
$CasinoProduct = $Casino->Product;
$CasinoProvider = $Casino->Provider;
$CasinoCategory = $Casino->Category;
$TypeBonusDeposit = $params->TypeBonusDeposit;

// Procesamiento de reglas y texto
$RulesText = str_replace("#######", "'", $params->RulesText);
$RulesText = str_replace("'", "\'", $RulesText);
$UserSubscribe = $params->UserSubscribe;
$TypeProduct = ($params->TypeProduct == 0) ? 0 : 1;
$TypeOtherProduct = $params->TypeOtherProduct;
$tipobono = ($params->TypeProduct == 0) ? 1 : 2;

// Configuración del tipo de bono basado en el tipo de producto
if ($tipobono == 2) {
    if ($TypeOtherProduct == 0) {
    }
    if ($TypeOtherProduct == 3) {
        $tipobono = 3;
    }
    if ($TypeOtherProduct == 4) {
        $tipobono = 4;
    }
}

// Configuración de suscripción de usuario
if ($UserSubscribe) {
    $UserSubscribe = 1;
} else {
    $UserSubscribe = 0;
}

// Configuración de visibilidad y acceso
$IsVisibleForAllplayers = $params->IsVisibleForAllplayers;
$OpenToAllPlayers = $params->OpenToAllPlayers;
$Prefix = $params->Prefix;
$PlayersChosen = $params->PlayersChosen;
$ProductTypeId = $params->ProductTypeId;
$TriggerId = $params->TriggerId;
$TypeId = $params->TypeId;
$Games = $params->Games;
$condiciones = [];

// Configuración de giros gratis y definición de expresión regular
$FreeSpinDefinition = $params->FreeSpinDefinition;
$AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
$BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
$BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
$FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
$WageringFactor = $FreeSpinDefinition->WageringFactor;
$PlayersChosen = $params->PlayersChosen;
$regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;

// Procesamiento de jugadores VIP y validación de datos
$VisibleAllPlayers = $params->VisibleAllPlayers;
$PlayersIdCsv = $params->PlayersIdCsv;
$String = trim(substr($PlayersIdCsv, strpos($PlayersIdCsv, ','), strlen($PlayersIdCsv) - 1), ',');
$Data = explode("\n", base64_decode($String));
$PlayersVIP = array_filter($Data, function($item) {
    return !empty($item);
});

// Validación de jugadores y procesamiento de errores
$players = $params->Players;
if($players != "" && $players != null) {
    $regularExpression = "#([^0-9,]{1})#";
    $coincidence =preg_match($regularExpression, strval($players));

    if($coincidence == 1) {
        //ERROR Se encontraron carácteres o patrones inválidos para agregar los jugadores
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Jugadores escritos en el campo de forma inválida";
        $response["ModelErrors"] = [];
    }
    elseif ($coincidence === false){
        //ERROR Fallo del preg_match
        throw new Exception("Error en proceso de validación", 110011);
    }

    //Llenando listado de participantes
    $players = explode(',', $players);
    $PlayersVIP = array_filter($players, function($item) {
        return !empty($item);
    });
}

// Creación y configuración del objeto TorneoInterno
$TorneoInterno = new TorneoInterno();
$TorneoInterno->nombre = $Name;
$TorneoInterno->descripcion = $Description;
$TorneoInterno->fechaInicio = $StartDate;
$TorneoInterno->fechaFin = $EndDate;
$TorneoInterno->tipo = $tipobono;
$TorneoInterno->estado = 'A';
$TorneoInterno->usucreaId = 0;
$TorneoInterno->usumodifId = 0;
$TorneoInterno->mandante = $mandanteUsuario;
$TorneoInterno->condicional = $ConditionProduct;
$TorneoInterno->orden = $Priority;
$TorneoInterno->cupoActual = $cupo;
$TorneoInterno->cupoMaximo = $cupoMaximo;
$TorneoInterno->cantidadTorneos = $jugadores;
$TorneoInterno->maximoTorneos = $jugadoresMaximo;
$TorneoInterno->codigo = $CodeGlobal;
$TorneoInterno->reglas = $RulesText;
$TorneoInterno->jsonTemp = json_encode($params);

// Inserción del torneo en la base de datos
$TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();
$transaccion = $TorneoDetalleMySqlDAO->getTransaction();
$torneoId = $TorneoInterno->insert($transaccion);

// Bloque para validar y procesar eventos repetidos en el torneo
// Maneja la configuración de repetición para deportes, mercados y ligas
if(!empty($params->RepeatEvents)){

    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->descripcion = $value->Image;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;

    // Define la estructura de productos y sus tipos de repetición correspondientes
    $products = [
        'SportsbookDeports' => ['REPETIRDEPORTEBOOL', 'REPETIRDEPORTE'],
        'SportsbookMarkets' => ['REPETIRMERCADOBOOL', 'REPETIRMERCADO'],
        'SportsbookLeagues' => ['REPETIRLIGABOOL', 'REPETIRLIGA'],
    ];

    // Itera sobre los eventos repetidos y guarda la configuración en la base de datos
    foreach ($params->RepeatEvents as $key => $event) {
        if (isset($products[$key])) {
            [$repeatBool, $type] = $products[$key];

            $TorneoDetalle->tipo = $repeatBool;
            $TorneoDetalle->valor = ($event->Selected) ? '1' : '0';
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

            $TorneoDetalle->tipo = $type;
            $TorneoDetalle->valor = $event->Events;
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }
}

// Procesa y guarda las reglas de bonos deportivos asociadas al torneo
foreach ($SportBonusRules as $key => $value) {

    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value->ObjectId;
    $TorneoDetalle->descripcion = $value->Image;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}
    // Guarda la configuración de visibilidad del torneo para todos los jugadores
    if($VisibleAllPlayers !== '') {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = 'USUARIOVISIBILIDAD';
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = !$VisibleAllPlayers ? 0 : 1;
        $TorneoDetalle->descripcion = '';
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Registra los jugadores VIP que pueden participar en el torneo
    foreach($PlayersVIP as $value) {
        $UsuarioTorneo = new UsuarioTorneo();
        $UsuarioTorneo->usuarioId = $value;
        $UsuarioTorneo->torneoId = $torneoId;
        $UsuarioTorneo->valor = '0';
        $UsuarioTorneo->posicion = '0';
        $UsuarioTorneo->valorBase = '0';
        $UsuarioTorneo->estado = 'A';
        $UsuarioTorneo->usucreaId = 0;
        $UsuarioTorneo->usumodifId = 0;
        $UsuarioTorneo->mandante = $mandanteUsuario;
        $UsuarioTorneo->errorId = '0';
        $UsuarioTorneo->idExterno = '0';
        $UsuarioTorneo->version = '0';
        $UsuarioTorneo->apostado = '0';
        $UsuarioTorneo->codigo = '0';
        $UsuarioTorneo->externoId = '0';
        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($transaccion);
        $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);
    }

    // Procesa y guarda los deportes seleccionados para el torneo
    if($SportsbookDeports2 != ''){
        $SportsbookDeports2 = explode(',',$SportsbookDeports2);

        foreach ($SportsbookDeports2 as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "ITAINMENT" . '1';
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }

    // Procesa y guarda las ligas seleccionadas para el torneo
    if($SportsbookLeagues2 != ''){
        $SportsbookLeagues2 = explode(',',$SportsbookLeagues2);

        foreach ($SportsbookLeagues2 as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "ITAINMENT" . '3';
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = trim($value);
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }

    // Procesa y guarda los partidos seleccionados para el torneo
    if($SportsbookMatches2 != ''){
        $SportsbookMatches2 = explode(',',$SportsbookMatches2);

        foreach ($SportsbookMatches2 as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "ITAINMENT" . '4';
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }

    // Procesa y guarda los mercados seleccionados para el torneo
    if($SportsbookMarkets2 != ''){
        $SportsbookMarkets2 = explode(',',$SportsbookMarkets2);

        foreach ($SportsbookMarkets2 as $key => $value) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = "ITAINMENT" . '5';
            $TorneoDetalle->moneda = '';
            $TorneoDetalle->valor = $value;
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }

    // Guarda la configuración de visibilidad del torneo
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "VISIBILIDAD";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $TypeRule;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

    // Guarda la configuración de suscripción de usuarios
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "USERSUBSCRIBE";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $UserSubscribe;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

    // Guarda el tipo de producto si está definido
    if($TypeProduct != ''){
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "TIPOPRODUCTO";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $TypeProduct;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la configuración de ranking por ingreso neto si aplica
    if ($TypeRanking == 2) {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "RANKNETINCOME";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $PointValueNetIncome;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    } else if ($TypeRanking == 3) {
        // Guarda la configuración de puntos por cuota total
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "RANKQUOTAS";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $PointValueQuota;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la configuración de apuestas en vivo o pre-partido
    if ($LiveOrPreMatch != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "LIVEORPREMATCH";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $LiveOrPreMatch;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda el número mínimo de selecciones requeridas
    if ($MinSelCount != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "MINSELCOUNT";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $MinSelCount;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda el precio mínimo por selección
    if ($MinSelPrice != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "MINSELPRICE";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $MinSelPrice;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda el precio mínimo total de selecciones
    if ($MinSelPriceTotal != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "MINSELPRICETOTAL";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $MinSelPriceTotal;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda el precio mínimo de apuesta (deshabilitado)
    if ($MinBetPrice != "" && false) {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "MINBETPRICE";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $MinBetPrice;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda las descripciones de los premios
    foreach ($PrizeDescription as $key => $value) {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "PREMIODESCRIPCION";
        $TorneoDetalle->moneda = $value->CurrencyId;
        $TorneoDetalle->valor = $value->Amount;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda las URLs de las imágenes de los premios
    foreach ($PrizeImageURL as $key => $value) {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "PREMIOIMAGEN";
        $TorneoDetalle->moneda = $value->CurrencyId;
        $TorneoDetalle->valor = $value->Amount;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    /*foreach ($AmountPrize as $key => $value) {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "PREMIOMONTO";
        $TorneoDetalle->moneda = $value->CurrencyId;
        $TorneoDetalle->valor = $value->Amount;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }*/

    // Guarda la configuración de rangos y puntos
    foreach ($Ranks as $key => $value) {
        foreach ($value->Amount as $key2 => $value2) {
            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = ($TypeRanking == 1) ? "RANKLINE" : "RANK";
            $TorneoDetalle->moneda = $value->CurrencyId;
            $TorneoDetalle->valor = $value2->initialRange;
            $TorneoDetalle->valor2 = $value2->finalRange;
            $TorneoDetalle->valor3 = $value2->credits;
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }

    // Guarda los premios por posición en el ranking
    foreach ($RanksPrize as $key => $value) {
        foreach ($value->Amount as $key2 => $value2) {
            $description = "RANKAWARD";
            if ($value2->type == 0) {
                $description = "RANKAWARDMAT";
            }else if($value2->type == 3) {
                $description = "RANKAWARDBONUS";
            }

            $TorneoDetalle = new TorneoDetalle();
            $TorneoDetalle->torneoId = $torneoId;
            $TorneoDetalle->tipo = $description;
            $TorneoDetalle->moneda = $value->CurrencyId;
            $TorneoDetalle->valor = $value2->position;
            $TorneoDetalle->valor2 = $value2->description;
            $TorneoDetalle->valor3 = $value2->amount;
            $TorneoDetalle->usucreaId = 0;
            $TorneoDetalle->usumodifId = 0;
            $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
            $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
        }
    }

    // Guarda la URL de la imagen principal
    if ($MainImageURL != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "IMGPPALURL";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $MainImageURL;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la URL de la imagen del ranking
    if ($RankingImageURL != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "RANKIMGURL";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $RankingImageURL;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la URL de la imagen de fondo
    if ($BackgroundURL != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "BACKGROUNDURL";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $BackgroundURL;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la URL de la imagen de fondo secundaria
    if ($BackgroundURL2 != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "BACKGROUNDURL2";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $BackgroundURL2;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la URL de la imagen central
    if ($ImgCenter != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "IMGCENTER";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $ImgCenter;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }

    // Guarda la URL de la imagen central secundaria
    if ($ImgCenter2 != "") {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "IMGCENTER2";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $ImgCenter2;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }
// Bloque para guardar la URL de la imagen derecha
if ($ImgRight != "") {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "IMGRIGHT";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $ImgRight;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar la URL de la imagen de premios
if ($ImgAwards != "") {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "IMGAWARDS";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $ImgAwards;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar el tipo de producto del torneo
if ($ProductTypeId !== "") {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "TIPOPRODUCTO";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $ProductTypeId;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar si las líneas se calculan por puntos
if ($LinesByPoints == 1) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "MULTLINEAS";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $LinesByPoints;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los sistemas de pago permitidos
foreach ($PaymentSystemIds as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDPAYMENT";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar las regiones permitidas para puntos de venta
foreach ($Regions as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDPAISPV";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los departamentos permitidos para puntos de venta
foreach ($Departments as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDDEPARTAMENTOPV";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar las ciudades permitidas para puntos de venta
foreach ($Cities as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDCIUDADPV";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar si se permite balance cero
if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDBALANCE";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = '0';
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar las regiones permitidas para usuarios
foreach ($RegionsUser as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDPAISUSER";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los departamentos permitidos para usuarios
foreach ($DepartmentsUser as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDDEPARTAMENTOUSER";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar las ciudades permitidas para usuarios
foreach ($CitiesUser as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDCIUDADUSER";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los puntos de venta permitidos
foreach ($CashDesks as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDPUNTOVENTA";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los juegos y sus porcentajes de apuesta
foreach ($Games as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDGAME" . $value->Id;
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $value->WageringPercent;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los productos de casino y su orden
foreach ($CasinoProduct as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDGAME" . $value->Id;
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = 100;
    $TorneoDetalle->valor2 = $value->Order;
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar los proveedores de casino
foreach ($CasinoProvider as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = 100;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar las categorías de casino
foreach ($CasinoCategory as $key => $value) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "CONDCATEGORY" . $value->Id;
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = 100;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
}

// Bloque para guardar el ID del bono de victoria y el tipo de saldo
if ($WinBonusId != 0) {
    $TorneoDetalle = new TorneoDetalle();
    $TorneoDetalle->torneoId = $torneoId;
    $TorneoDetalle->tipo = "WINBONOID";
    $TorneoDetalle->moneda = '';
    $TorneoDetalle->valor = $WinBonusId;
    $TorneoDetalle->valor2 = '';
    $TorneoDetalle->valor3 = '';
    $TorneoDetalle->usucreaId = 0;
    $TorneoDetalle->usumodifId = 0;
    $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
    $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
} else {
    if (false) {
        $TorneoDetalle = new TorneoDetalle();
        $TorneoDetalle->torneoId = $torneoId;
        $TorneoDetalle->tipo = "TIPOSALDO";
        $TorneoDetalle->moneda = '';
        $TorneoDetalle->valor = $TypeSaldo;
        $TorneoDetalle->valor2 = '';
        $TorneoDetalle->valor3 = '';
        $TorneoDetalle->usucreaId = 0;
        $TorneoDetalle->usumodifId = 0;
        $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMysqlDAO->insert($TorneoDetalle);
    }
}


/*


        if ($TriggerId != "") {
            if($CodePromo != ""){

                $TorneoDetalle = new TorneoDetalle();
                $TorneoDetalle->torneoId = $torneoId;
                $TorneoDetalle->tipo = "CODEPROMO";
                $TorneoDetalle->moneda = '';
                $TorneoDetalle->valor = $CodePromo;
                $TorneoDetalle->usucreaId = 0;
                $TorneoDetalle->usumodifId = 0;
                $TorneoDetalleMysqlDAO = new TorneoDetalleMySqlDAO($transaccion);
                $TorneoDetalleMysqlDAO->insert($TorneoDetalle);

            }
        }



        if ($FreeSpinsTotalCount != "" && $Prefix != "") {
            $jugadoresAsignar = array();
            $jugadoresAsignarFinal = array();

            if ($PlayersChosen != "") {
                $jugadoresAsignar = explode(",", $PlayersChosen);


                foreach ($jugadoresAsignar as $item) {

                    array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

                }

            }


            $codigosarray = array();

            for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {
                $codigo = GenerarClaveTicket(4);

                while (in_array($codigo, $codigosarray)) {
                    $codigo = GenerarClaveTicket(4);
                }


                $usuarioId = '0';
                $valor = $AutomaticForfeitureLevel;

                $valor_bono = $AutomaticForfeitureLevel;

                $valor_base = $AutomaticForfeitureLevel;

                $estado = 'L';

                $errorId = '0';

                $idExterno = '0';

                $mandante = '0';


                $usucreaId = '0';

                $usumodifId = '0';


                $apostado = '0';
                $rollowerRequerido = '0';
                $codigo = $Prefix . $codigo;


                $UsuarioBono = new UsuarioBono();

                $UsuarioBono->setUsuarioId($usuarioId);
                $UsuarioBono->setBonoId($torneoId);
                $UsuarioBono->setValor($valor);
                $UsuarioBono->setValorBono($valor_bono);
                $UsuarioBono->setValorBase($valor_base);
                $UsuarioBono->setEstado($estado);
                $UsuarioBono->setErrorId($errorId);
                $UsuarioBono->setIdExterno($idExterno);
                $UsuarioBono->setMandante($mandante);
                $UsuarioBono->setUsucreaId($usucreaId);
                $UsuarioBono->setUsumodifId($usumodifId);
                $UsuarioBono->setApostado($apostado);
                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                $UsuarioBono->setCodigo($codigo);

                $UsuarioBonoMysqlDAO= new UsuarioBonoMySqlDAO($transaccion);

                $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                    $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                }

                array_push($codigosarray, $codigo);

            }
        }

        if ($MaxplayersCount != "" && $Prefix != "") {

            $jugadoresAsignar = array();
            $jugadoresAsignarFinal = array();

            foreach ($MinimumAmount as $key => $value) {

                $jugadoresAsignar = explode(",", $value->Amount);

                foreach ($MaxPayout as $key2 => $value2) {

                    if ($value->CurrencyId == $value2->CurrencyId) {

                        foreach ($jugadoresAsignar as $item) {

                            if($item != 0){

                                array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $value2->Amount));

                            }

                        }

                    }
                }

            }

            $codigosarray = array();

            for ($i = 0; $i < $MaxplayersCount; $i++) {
                $codigo = GenerarClaveTicket(4);

                while (in_array($codigo, $codigosarray)) {
                    $codigo = GenerarClaveTicket(4);
                }


                $usuarioId = '0';
                $valor = '0';

                $valor_bono = '0';

                $valor_base = '0';
                $estado = 'L';

                $errorId = '0';

                $idExterno = '0';

                $mandante = '0';


                $usucreaId = '0';

                $usumodifId = '0';


                $apostado = '0';
                $rollowerRequerido = '0';
                $codigo = $Prefix . $codigo;

                $UsuarioBono = new UsuarioBono();

                $UsuarioBono->setUsuarioId($usuarioId);
                $UsuarioBono->setBonoId($torneoId);
                $UsuarioBono->setValor($valor);
                $UsuarioBono->setValorBono($valor_bono);
                $UsuarioBono->setValorBase($valor_base);
                $UsuarioBono->setEstado($estado);
                $UsuarioBono->setErrorId($errorId);
                $UsuarioBono->setIdExterno($idExterno);
                $UsuarioBono->setMandante($mandante);
                $UsuarioBono->setUsucreaId($usucreaId);
                $UsuarioBono->setUsumodifId($usumodifId);
                $UsuarioBono->setApostado($apostado);
                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                $UsuarioBono->setCodigo($codigo);

                $UsuarioBonoMysqlDAO= new UsuarioBonoMySqlDAO($transaccion);

                $inse=$UsuarioBonoMysqlDAO->insert($UsuarioBono);


                if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                    $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                }

                array_push($codigosarray, $codigo);

            }
            for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                    $BonoInterno = new BonoInterno();

                    $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                    $Usuario = $BonoInterno->execQuery($transaccion,$usuarioSql);


                    $dataUsuario = $Usuario;
                    $detalles = array(
                        "PaisUSER" => $dataUsuario[0]->pais_id,
                        "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                        "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                    );
                    $detalles = json_decode(json_encode($detalles));


                    $respuesta = $BonoInterno->agregarBonoFree($torneoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                }

            }
        }



        if ($FreeSpinsTotalCount != "" && $Prefix != "") {

            for ($i = 1; $i <= oldCount($jugadoresAsignarFinal); $i++) {


                $BonoInterno = new BonoInterno();

                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                $Usuario = $BonoInterno->execQuery($transaccion,$usuarioSql);


                $dataUsuario = $Usuario;
                $detalles = array(
                    "PaisUSER" => $dataUsuario[0]->pais_id,
                    "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                    "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                );
                $detalles = json_decode(json_encode($detalles));

                $respuesta = $BonoInterno->agregarBonoFree($torneoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


            }
        }
        if ($MaxplayersCount != "" && $Prefix != "") {

            for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                    $BonoInterno = new BonoInterno();

                    $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                    $Usuario = $BonoInterno->execQuery($transaccion,$usuarioSql);


                    $dataUsuario = $Usuario;
                    $detalles = array(
                        "PaisUSER" => $dataUsuario[0]->pais_id,
                        "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                        "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                    );
                    $detalles = json_decode(json_encode($detalles));


                    $respuesta = $BonoInterno->agregarBonoFree($torneoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                }

            }
        }
        */
// Confirma la transacción en la base de datos
$transaccion->commit();

// Comentario de commit anterior
//$transaccion->commit();

// Configura el ID del torneo en la respuesta
$response["idTournament"] = $torneoId;

// Configura los valores de respuesta por defecto
// indicando que no hubo errores y estableciendo valores iniciales
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Result"] = array();
