<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');

//Creacion de Ruleta


use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\RuletaDetalle;
use Backend\dto\RuletaInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRuleta;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Exception;

/**
 * CreateRoulette
 *
 * Crea una nueva ruleta en el sistema
 *
 * @param object $params {
 *   "Description": string,     // Descripción de la ruleta
 *   "Name": string,            // Nombre de la ruleta
 *   "BeginDate": string,       // Fecha inicial de la campaña (YYYY-MM-DD)
 *   "EndDate": string,         // Fecha final de la campaña (YYYY-MM-DD)
 *   "CodeGlobal": string,      // Código global de la ruleta
 *   "TypeRanking": string,     // Tipo de ranking
 *   "Priority": int,           // Prioridad de la ruleta
 *   "MaxplayersCount": int,    // Número máximo de jugadores permitidos
 *   "MinplayersCount": int,    // Número mínimo de jugadores requeridos
 *   "ForeignRule": {           // Reglas adicionales
 *     "Info": object           // Información de reglas en formato JSON
 *   }
 * }
 *
 * @return array {
 *   "HasError": boolean,       // Indica si hubo error
 *   "AlertType": string,       // Tipo de alerta (success, warning, error)
 *   "AlertMessage": string,    // Mensaje descriptivo
 *   "ModelErrors": array,      // Lista de errores del modelo
 *   "Data": {                  // Datos de la ruleta creada
 *     "Id": int,               // ID de la ruleta
 *     "Description": string    // Descripción de la ruleta
 *   }
 * }
 *
 * @throws Exception          // Errores de procesamiento
 */


// Validación inicial de parámetros
if ($params == "" || $params == null) {
    exit();
}

try {

    $mandanteUsuario = 0;
    // Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        $mandanteUsuario = $_SESSION['mandante'];
    }

    // Extracción de parámetros básicos de la ruleta
    $Description = $params->Description; //Descripcion del ruleta
    $Name = $params->Name; //Nombre del ruleta
    $StartDate = $params->BeginDate; //Fecha Inicial de la campaña
    $EndDate = $params->EndDate; //Fecha Final de la campaña
    $CodeGlobal = $params->CodeGlobal;
    $TypeRanking = $params->TypeRanking;
    $tiporuleta = 6;
    $ConditionProduct = 'OR';

    // Configuración de prioridad
    $Priority = $params->Priority;
    if ($Priority == "" || !is_numeric($Priority)) {
        $Priority = 0;
    }

    // Inicialización de contadores y límites de jugadores
    $cupo = 0;
    $cupoMaximo = 0;
    $jugadores = 0;
    $jugadoresMaximo = 0;
    $MaxplayersCount = $params->MaxplayersCount;
    $MinplayersCount = $params->MinplayersCount;
    $jugadoresMaximo = $MaxplayersCount;

    // Procesamiento de reglas adicionales
    $ForeignRule = $params->ForeignRule;
    $ForeignRuleInfo = $ForeignRule->Info;

    if (!is_object($ForeignRuleInfo)) {
        $ForeignRuleJSON = json_decode($ForeignRuleInfo);
    } else {
        $ForeignRuleJSON = $ForeignRuleInfo;
    }

    // Extracción de configuraciones de deportes
    $SportsbookDeports2 = $ForeignRuleJSON->SportsbookDeports2;
    $SportsbookMarkets2 = $ForeignRuleJSON->SportsbookMarkets2;
    $SportsbookLeagues2 = $ForeignRuleJSON->SportsbookLeagues2;
    $SportsbookMatches2 = $ForeignRuleJSON->SportsbookMatches2;

    // Reprocesamiento de reglas si es necesario
    if (!is_object($ForeignRuleInfo)) {
        $ForeignRuleJSON = json_decode($ForeignRuleInfo);
    } else {
        $ForeignRuleJSON = $ForeignRuleInfo;
    }

    // Configuración de reglas de apuestas y giros
    $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
    $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
    $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
    $MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;
    $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
    $MaxDailySpins = $ForeignRuleJSON->MaxDailySpins; // Minimo apuesta cuota total

    $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

    // Configuración de URLs e imágenes
    $MainImageURL = $params->MainImageURL;
    $RankingImageURL = $params->RankingImageURL;
    $BackgroundURL = $params->BackgroundURL;
    $PrizeDescription = $params->PrizeDescription;
    $prizeWinImageURL= $params->prizeWinImageURL;
    $PrizeImageURL = $params->PrizeImageURL;

    // Configuración de rankings y premios
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

    // Configuración de suscripciones y condiciones
    $IsForLogin = $params->IsForLogin;
    $UserRouletteRepeat = $params->UserRouletteRepeat;    $ExpirationDays = $params->ExpirationDays;
    $ExpirationDate = $params->ExpirationDate;

    if (!empty($ExpirationDate)) {
        $ExpirationDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $ExpirationDate)));
    }

    $isForRegistration = $params->IsForRegistration;

    $UserSubscribeRepeat = $params->UserSubscribeRepeat;
    $WinBonusId = $params->WinBonusId;
    $TypeSaldo = $params->TypeSaldo;
    $ConditionProduct = $TriggerDetails->ConditionProduct;

    // Validación de condición de producto
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }

    // Configuración de ubicaciones
    $Regions = $TriggerDetails->Regions;
    $Departments = $TriggerDetails->Departments;
    $Cities = $TriggerDetails->Cities;
    $CashDesks = $TriggerDetails->CashDesks;
    $RegionsUser = $TriggerDetails->RegionsUser;
    $DepartmentsUser = $TriggerDetails->DepartmentsUser;
    $CitiesUser = $TriggerDetails->CitiesUser;

    // Configuración de casino
    $Casino = $params->Casino->Info;
    $CasinoProduct = $Casino->Product; //condgame
    $CasinoProvider = $Casino->Provider; //subprovider
    $CasinoCategory = $Casino->Category;
    $TypeBonusDeposit = $params->TypeBonusDeposit;

    // Procesamiento de reglas y texto
    $RulesText = str_replace("#######", "'", $params->RulesText);
    $RulesText = str_replace("'", "\'", $RulesText);
    $UserSubscribe = $params->UserSubscribe;
    $TypeProduct = ($params->TypeProduct == 0) ? 0 : 1;
    $TypeOtherProduct = $params->TypeOtherProduct;

    // Determinación del tipo de ruleta
    $tiporuleta = ($params->TypeProduct === 0) ? 1 : 2;

    if ($tiporuleta == 2) {
        if ($TypeOtherProduct == 0) {
            $tiporuleta = 6;
        }
        if ($TypeOtherProduct == 2) {
            $tiporuleta = 2;
        }
        if ($TypeOtherProduct == 3) {
            $tiporuleta = 3;
        }
        if ($TypeOtherProduct == 4) {
            $tiporuleta = 4;
        }
    }
    if($PaymentSystemIds > 0 && $PaymentSystemIds != "" && $PaymentSystemIds != null){
        $tiporuleta = 5;
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

    // Procesamiento de lista de jugadores desde CSV
    $PlayersIdCsv = $params->PlayersIdCsv;
    $PlayersIdCsv = explode("base64,",$PlayersIdCsv);
    $PlayersIdCsv = $PlayersIdCsv[1];
    $PlayersIdCsv = base64_decode($PlayersIdCsv);
    $PlayersIdCsv = str_replace(";",",",$PlayersIdCsv);
    $lines = explode(PHP_EOL, $PlayersIdCsv);
    $lines = preg_split('/\r\n|\r|\n/', $PlayersIdCsv);
    $array = array();
    foreach ($lines as $line) {
        $array[] = str_getcsv($line);
    }

    // Procesamiento final de IDs de jugadores
    $arrayfinal =  array_column($array,'0');
    $posiciones = array_keys($arrayfinal);
    $ultima = strval(end($posiciones));
    $arrayfinal = json_decode(json_encode($arrayfinal));
    $arrayfinal2 = array();
    foreach ($arrayfinal as $item){
        if($item != ""){
            array_push($arrayfinal2, $item);
        }
    }
    $arrayfinal= $arrayfinal2;
    $ids = implode(",",$arrayfinal);
    if($ids != ""){
        $clients = $ids;
        $clients = explode(",", $clients);
    }

    // Configuración de giros gratis
    $FreeSpinDefinition = $params->FreeSpinDefinition;
    $AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
    $BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
    $BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
    $FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
    $WageringFactor = $FreeSpinDefinition->WageringFactor;
    $PlayersChosen = $params->PlayersChosen;

    // Definición de expresión regular para validación
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

    // Creación y configuración del objeto RuletaInterno
    $RuletaInterno = new RuletaInterno();
    $RuletaInterno->nombre = $Name;
    $RuletaInterno->descripcion = $Description;
    $RuletaInterno->fechaInicio = $StartDate;
    $RuletaInterno->fechaFin = $EndDate;
    $RuletaInterno->tipo = $tiporuleta;
    $RuletaInterno->estado = 'A';
    $RuletaInterno->usucreaId = 0;
    $RuletaInterno->usumodifId = 0;
    $RuletaInterno->mandante = $mandanteUsuario;
    $RuletaInterno->condicional = $ConditionProduct;
    $RuletaInterno->orden = $Priority;
    $RuletaInterno->cupoActual = $cupo;
    $RuletaInterno->cupoMaximo = $cupoMaximo;
    $RuletaInterno->cantidadRuletas = $jugadores;
    $RuletaInterno->maximoRuletas = $jugadoresMaximo;
    $RuletaInterno->codigo = $BackgroundURL;
    $RuletaInterno->reglas = $RulesText;

    // Inserción de la ruleta en la base de datos
    $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
    $transaccion = $RuletaDetalleMySqlDAO->getTransaction();
    $ruletaId = $RuletaInterno->insert($transaccion);

    // Creación de registros de usuario-ruleta para cada cliente
    if(!empty($clients)){
        foreach ($clients as $key => $ClientId) {

            try {
                //Los ids de los usuarios en el archivo corresponden a los ids de la plataforma de usuarios online
                $UsuarioMandante = new UsuarioMandante("", $ClientId, $mandanteUsuario);

                $UsuarioRuleta = new UsuarioRuleta();
                $UsuarioRuleta->ruletaId = $ruletaId;
                $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                $UsuarioRuleta->valor = 0;
                $UsuarioRuleta->posicion = 0;
                $UsuarioRuleta->valorBase = 0;
                $UsuarioRuleta->fechaCrea = 0;
                $UsuarioRuleta->usucreaId = 0;
                $UsuarioRuleta->fechaModif = 0;
                $UsuarioRuleta->usumodifId = 0;
                if($isForRegistration == '1' || $IsForLogin == '1') {
                    $UsuarioRuleta->estado = "PP";
                }else {
                    $UsuarioRuleta->estado = "PR";
                }
                $UsuarioRuleta->errorId = 0;
                $UsuarioRuleta->idExterno = 0;
                $UsuarioRuleta->mandante = 0;
                $UsuarioRuleta->version = 0;
                $UsuarioRuleta->apostado = 0;
                $UsuarioRuleta->codigo = 0;
                $UsuarioRuleta->externoId = 0;
                $UsuarioRuleta->valorPremio = 0;
                $UsuarioRuleta->premio = "";
                $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

            } catch (\Throwable $th) {
                if ($th->getMessage() != "No existe Backend\dto\UsuarioMandante") {
                    throw $th;
                }
            }
        }

        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CSVREGISTRADO";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = 1;
        $RuletaDetalle->descripcion = '';
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Creación de detalles de ruleta para reglas de bonos deportivos
    foreach ($SportBonusRules as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value->ObjectId;
        $RuletaDetalle->descripcion = $value->Image;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Creación de detalles de ruleta para deportes
    if($SportsbookDeports2 != ''){
        $SportsbookDeports2 = explode(',',$SportsbookDeports2);
        foreach ($SportsbookDeports2 as $key => $value) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = "ITAINMENT" . '1';
            $RuletaDetalle->moneda = '';
            $RuletaDetalle->valor = $value;
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
        }
    }



    // Bloque para procesar las ligas de Sportsbook
    if($SportsbookLeagues2 != ''){
        $SportsbookLeagues2 = explode(',',$SportsbookLeagues2);

        foreach ($SportsbookLeagues2 as $key => $value) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = "ITAINMENT" . '3'; //Esta condiciones es para sportsbook
            $RuletaDetalle->moneda = '';
            $RuletaDetalle->valor = $value;
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
        }
    }

    // Bloque para procesar los partidos de Sportsbook
    if($SportsbookMatches2 != ''){
        $SportsbookMatches2 = explode(',',$SportsbookMatches2);

        foreach ($SportsbookMatches2 as $key => $value) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = "ITAINMENT" . '4'; //Esta condiciones es para sportsbook
            $RuletaDetalle->moneda = '';
            $RuletaDetalle->valor = $value;
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
        }
    }

    // Bloque para procesar los mercados de Sportsbook
    if($SportsbookMarkets2 != ''){
        $SportsbookMarkets2 = explode(',',$SportsbookMarkets2);

        foreach ($SportsbookMarkets2 as $key => $value) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = "ITAINMENT" . '5'; //Esta condiciones es para sportsbook
            $RuletaDetalle->moneda = '';
            $RuletaDetalle->valor = $value;
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
        }
    }

//VISIBILIDAD, USERSUBSCRIBE -> No aplican para RULETA

    // Bloque para registrar el tipo de producto
    $RuletaDetalle = new RuletaDetalle();
    $RuletaDetalle->ruletaId = $ruletaId;
    $RuletaDetalle->tipo = "TIPOPRODUCTO"; //Este caso se debe mapear en CASINO OK!!!
    $RuletaDetalle->moneda = '';
    $RuletaDetalle->valor = $TypeProduct;
    $RuletaDetalle->valor2 = '';
    $RuletaDetalle->valor3 = '';
    $RuletaDetalle->usucreaId = 0;
    $RuletaDetalle->usumodifId = 0;
    $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
    $RuletaDetalleMysqlDAO->insert($RuletaDetalle);

    // Bloque para registrar si es Live o PreMatch
    if ($LiveOrPreMatch != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "LIVEORPREMATCH"; //Esta condicion es para SPORTSBOOK
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $LiveOrPreMatch;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar el número mínimo de selecciones
    if ($MinSelCount != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "MINSELCOUNT"; //Esto se debe agregar en casino? //Esta condicion es para SPORTSBOOK
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $MinSelCount;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar el precio mínimo de selección
    if ($MinSelPrice != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "MINSELPRICE"; //Esto se debe poner en casino? //Esta condicion es para SPORTSBOOK
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $MinSelPrice;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar el precio mínimo total de selección
    if ($MinSelPriceTotal != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "MINSELPRICETOTAL"; //Esto se debe poner en casino? //Esta condicion es para SPORTSBOOK
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $MinSelPriceTotal;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar el precio mínimo de apuesta por moneda
    if ($MinBetPrice != "") {
        foreach ($MinBetPrice as $item) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = "MINBETPRICE"; //Esto si se pone en Casino y significa el monto minimo de apuesta. OK!!!
            $RuletaDetalle->moneda = $item->CurrencyId;
            $RuletaDetalle->valor = $item->Amount;
            $RuletaDetalle->valor2 = '';
            $RuletaDetalle->valor3 = '';
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
        }

    }else {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "MINBETPRICE";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = 0;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar el máximo de giros diarios por moneda
    if ($MaxDailySpins != "") {
        foreach ($MaxDailySpins as $item) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = "MAXDAILYSPINS"; //Esto se pone en casino e indica el numero de veces que puede rodar la ruleta en un dia. OK!!!
            $RuletaDetalle->moneda = $item->CurrencyId;
            $RuletaDetalle->valor = $item->Amount;
            $RuletaDetalle->valor2 = '';
            $RuletaDetalle->valor3 = '';
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
        }
    }

    // Bloque para registrar las descripciones de premios
    foreach ($PrizeDescription as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "PREMIODESCRIPCION"; //Esto no se pone en casino porque no es condicional, refiere al premio
        $RuletaDetalle->moneda = $value->CurrencyId;
        $RuletaDetalle->valor = $value->Amount;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar las imágenes de premios
    foreach ($PrizeImageURL as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "PREMIOIMAGEN"; //Esto no se pone en casino porque no es condicional, refiere al premio
        $RuletaDetalle->moneda = $value->CurrencyId;
        $RuletaDetalle->valor = $value->Amount;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los rangos y sus valores
    foreach ($Ranks as $key => $value) {
        foreach ($value->Amount as $key2 => $value2) {
            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = ($TypeRanking == 1) ? "RANKLINE" : "RANK"; //Esto se debe poner en casino?  no se debe poner por ser referente al premio, no es condicion
            $RuletaDetalle->moneda = $value->CurrencyId;
            $RuletaDetalle->valor = $value2->initialRange;
            $RuletaDetalle->valor2 = $value2->finalRange;
            $RuletaDetalle->valor3 = $value2->credits;
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMySqlDAO->insert($RuletaDetalle);
        }
    }



    // Bloque para registrar los premios por rango
    if (empty($RanksPrize[0]->Amount)) {
        throw new Exception("No se puede crear la ruleta porque no hay premios establecidos", 422);
    }

    foreach ($RanksPrize as $key => $value) {
        foreach ($value->Amount as $key2 => $value2) {
            $tipo = "RANKAWARD"; //Esto indica premio saldo, no se debe poner por ser referente al premio, no es condicion
            if ($value2->type == 0) {
                $tipo = "RANKAWARDMAT"; //Esto indica premio meterial, fisico.  no se debe poner por ser referente al premio, no es condicion
                $description = $value2->description;
            }elseif ($value2->type == 2){
                $tipo = "BONO"; //Premio tipo bonus, no se debe poner por ser referente al premio, no es condicion
                $idBono = $value2->amount;
                $BonoInterno = new BonoInterno($idBono);
                switch ($BonoInterno->tipo){
                    case "2":
                        $description = "Bono Deposito";
                        break;
                    case "3":
                        $description = "Bono No Deposito";
                        break;
                    case "5":
                        $description = "Saldo FreeCasino";
                        break;
                    case "6":
                        $description = "Saldo FreeBet";
                        break;
                }
            }elseif ($value2->type == 3){
                $tipo = "RANKAWARDFREESPIN"; // Premio de tipo "Giro extra"
                $description = "Giro extra";
                $value2->amount = 0;
            }else{
                if($value2->description == 0){
                    $description = "Saldo Creditos";
                }
                if($value2->description == 1){
                    $description = "Saldo Premios";
                }
            }

            $RuletaDetalle = new RuletaDetalle();
            $RuletaDetalle->ruletaId = $ruletaId;
            $RuletaDetalle->tipo = $tipo;
            $RuletaDetalle->moneda = $value->CurrencyId;
            $RuletaDetalle->valor = $value2->amount;
            $RuletaDetalle->descripcion = $description;
            $RuletaDetalle->valor2 =$value2->prizeImageURL;
            $RuletaDetalle->valor3 =$value2->prizeWinImageURL;
            $RuletaDetalle->porcentaje = $value2->percent;
            $RuletaDetalle->usucreaId = 0;
            $RuletaDetalle->usumodifId = 0;
            $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO($transaccion);
            $RuletaDetalleMySqlDAO->insert($RuletaDetalle);
        }
    }

    // Bloque para registrar la imagen principal
    if ($MainImageURL != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "IMGPPALURL"; //Esto se aplica en casino?
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $MainImageURL;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }


    /* Registra en ruleta detalle en la base de datos si se permite PV. */
    if ($IsFromCashDesk) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDEFECTIVO"; //No aplica para casino ya que es para depositos solo aplica para depositos
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $IsFromCashDesk;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar si el usuario puede participar múltiples veces
    if ($UserSubscribeRepeat != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "REPETIRSORTEO"; //Esto se aplica en casino? SI APLICA ES LA VARIABLE QUE CAMBIA en el boton Usuario puede participar varias veces? del frontend OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $UserSubscribeRepeat;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los sistemas de pago permitidos
    foreach ($PaymentSystemIds as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDPAYMENT"; //No aplica para casino ya que es para depositos solo aplica para depositos
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar las regiones permitidas
    foreach ($Regions as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDPAISPV"; //No aplica para casino porque es punto de venta solo aplica para depositos
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }


    foreach ($Departments as $key => $value) {

        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDDEPARTAMENTOPV"; //No aplica para casino porque es punto de venta solo aplica para depositos
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);

    }

    // Bloque para registrar las ciudades permitidas para puntos de venta
    foreach ($Cities as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDCIUDADPV"; //No aplica para casino porque es punto de venta solo aplica para depositos
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar las regiones permitidas para usuarios
    foreach ($RegionsUser as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDPAISUSER"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = $AmountPrize[0]->CurrencyId; //Se agrega moneda necesaria para consultas externas
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los departamentos permitidos para usuarios
    foreach ($DepartmentsUser as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDDEPARTAMENTOUSER"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar las ciudades permitidas para usuarios
    foreach ($CitiesUser as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDCIUDADUSER"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los puntos de venta permitidos
    foreach ($CashDesks as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDPUNTOVENTA"; //No aplica para casino solo para depositos
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los juegos permitidos
    foreach ($Games as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDGAME"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value->Id;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los productos de casino permitidos
    foreach ($CasinoProduct as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDGAME"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value->Id;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar los proveedores de casino permitidos
    foreach ($CasinoProvider as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDSUBPROVIDER"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value->Id;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar las categorías de casino permitidas
    foreach ($CasinoCategory as $key => $value) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "CONDCATEGORY"; //Si aplica para casino OK!!!
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $value->Id;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Bloque para registrar el bono ganador o tipo de saldo según corresponda
    if ($WinBonusId != 0) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "WINBONOID";//Esto aplica para casino? NO ES CONDICIONAL
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $WinBonusId;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    } else {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "TIPOSALDO"; //No aplica para casino, no es condicional. Es respecto al pago de premios
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $TypeSaldo;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    if ($isForRegistration != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "ESPARAREGISTRO";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $isForRegistration;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    if (!empty($ExpirationDays)) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "EXPIRACIONDIA";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $ExpirationDays;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    if (!empty($ExpirationDate)) {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "EXPIRACIONFECHA";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $ExpirationDate;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    if ($IsForLogin != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "PARAPRIMERLOGINDIA";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $IsForLogin;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    if ($UserRouletteRepeat != "") {
        $RuletaDetalle = new RuletaDetalle();
        $RuletaDetalle->ruletaId = $ruletaId;
        $RuletaDetalle->tipo = "USUARIOREPITE";
        $RuletaDetalle->moneda = '';
        $RuletaDetalle->valor = $UserRouletteRepeat;
        $RuletaDetalle->valor2 = '';
        $RuletaDetalle->valor3 = '';
        $RuletaDetalle->usucreaId = 0;
        $RuletaDetalle->usumodifId = 0;
        $RuletaDetalleMysqlDAO = new RuletaDetalleMySqlDAO($transaccion);
        $RuletaDetalleMysqlDAO->insert($RuletaDetalle);
    }

    // Confirma la transacción y prepara la respuesta
    $transaccion->commit();

    $response["idRoulette"] = $ruletaId;
    $response["HasError"] = false;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();

}catch (Exception $e){
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = $e->getMessage();
    $response["ModelErrors"] = [];
    $response["ErrorCode"] = $e->getCode();
}