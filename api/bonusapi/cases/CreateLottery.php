<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\UsuarioSorteo;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;

/**
 * Este script maneja la creación de un sorteo interno basado en los parámetros proporcionados.
 * Realiza validaciones, asignaciones y operaciones en la base de datos para configurar el sorteo.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la creación del sorteo:
 * @param string $params->Description Descripción del bono.
 * @param string $params->Name Nombre del bono.
 * @param bool $params->Stickers Indica si el sorteo utiliza stickers.
 * @param string $params->BeginDate Fecha de inicio de la campaña.
 * @param string $params->EndDate Fecha de fin de la campaña.
 * @param string $params->CodeGlobal Código global del sorteo.
 * @param int $params->TypeRanking Tipo de ranking.
 * @param int $params->Priority Prioridad del sorteo.
 * @param int $params->MaxplayersCount Número máximo de jugadores.
 * @param int $params->MinplayersCount Número mínimo de jugadores.
 * @param object $params->ForeignRule Reglas extranjeras del sorteo.
 *     - string|object $params->ForeignRule->Info Información de las reglas extranjeras (JSON o objeto).
 * @param string $params->MainImageURL URL de la imagen principal.
 * @param string $params->CardBackgroundURL URL del fondo de la tarjeta.
 * @param string $params->RankingImageURL URL de la imagen del ranking.
 * @param string $params->BackgroundURL URL del fondo.
 * @param bool $params->UserSubscribe Indica si los usuarios deben suscribirse.
 * @param array $params->TypeCondition Condiciones del tipo de sorteo.
 * @param array $params->PrizeDescription Descripción de los premios.
 * @param array $params->PrizeImageURL URLs de las imágenes de los premios.
 * @param array $params->Ranks Rangos del sorteo.
 * @param array $params->RanksPrize Premios por rango.
 * @param bool $params->LinesByPoints Indica si las líneas se calculan por puntos.
 * @param array $params->TriggerDetails Detalles del disparador:
 *     - int $params->TriggerDetails->Count Cantidad de depósitos.
 *    - bool $params->TriggerDetails->IsFromCashDesk Indica si proviene de caja.
 *   - string $params->TriggerDetails->PaymentSystemId ID del sistema de pago.
 *  - array $params->TriggerDetails->PaymentSystemIds IDs de los sistemas de pago.
 * - string $params->TriggerDetails->ConditionProduct Condición del producto.
 * - array $params->TriggerDetails->Regions Regiones aplicables.
 * - array $params->TriggerDetails->Departments Departamentos aplicables.
 * - array $params->TriggerDetails->Cities Ciudades aplicables.
 * - array $params->TriggerDetails->CashDesks Cajas aplicables.
 * - bool $params->TriggerDetails->BalanceZero Indica si el balance debe ser cero.
 * @param object $params->Casino Información del casino:
 *    - array $params->Casino->Product Productos del casino.
 *   - array $params->Casino->Provider Proveedores del casino.
 *  - array $params->Casino->Category Categorías del casino.
 * @param int $params->TypeBonusDeposit Tipo de bono de depósito.
 * @param string $params->RulesText Texto de las reglas.
 * @param int $params->TypeProduct Tipo de producto.
 * @param int $params->TypeOtherProduct Tipo de otro producto.
 * @param bool $params->IsVisibleForAllplayers Indica si es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si está abierto a todos los jugadores.
 * @param string $params->Prefix Prefijo del sorteo.
 * @param array $params->PlayersChosen Jugadores seleccionados.
 * @param int $params->ProductTypeId ID del tipo de producto.
 * @param int $params->TriggerId ID del disparador.
 * @param array $params->Games Juegos aplicables.
 * @param object $params->FreeSpinDefinition Definición de giros gratis:
 *   - int $params->FreeSpinDefinition->AutomaticForfeitureLevel Nivel de pérdida automática.
 *  - string $params->FreeSpinDefinition->BonusMoneyExpirationDate Fecha de expiración del dinero de bono.
 * - int $params->FreeSpinDefinition->BonusMoneyExpirationDays Días de expiración del dinero de bono.
 * - int $params->FreeSpinDefinition->FreeSpinsTotalCount Cantidad total de giros gratis.
 * - int $params->FreeSpinDefinition->WageringFactor Factor de apuesta.
 * @param string $params->PlayersIdCsv CSV de IDs de jugadores.
 *
 *
 *
 * @return array $response Respuesta generada por el script:
 *  - int $idLottery ID del sorteo creado.
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success, danger, etc.).
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo.
 *  - array $Result Resultado adicional.
 */

try {

    /* verifica si los parámetros están vacíos y asigna un valor al usuario. */
    if ($params == "" || $params == null) {
        exit();
    }
    $mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        $mandanteUsuario = $_SESSION['mandante'];
    }


    /* Código que asigna valores de parámetros a variables para un bono y sus características. */
    $Description = $params->Description; //Descripcion del bono
    $Name = $params->Name; //Nombre del bono

    $Stickers = $params->Stickers; //El sorteo es con STIKERS o no?


    $StartDate = $params->BeginDate; //Fecha Inicial de la campaña

    /* Asignación de variables provenientes de parámetros para gestionar una campaña y tipo de ranking. */
    $EndDate = $params->EndDate; //Fecha Final de la campaña


    $CodeGlobal = $params->CodeGlobal;
    $TypeRanking = $params->TypeRanking;

    $tipobono = 1;


    /* asigna un valor a `$Priority` si es inválido o vacío. */
    $ConditionProduct = 'OR';
    $Priority = $params->Priority;

    if ($Priority == "" || !is_numeric($Priority)) {
        $Priority = 0;
    }


    /* Inicializa variables para gestionar el cupo y jugadores en un sistema. */
    $cupo = 0;
    $cupoMaximo = 0;
    $jugadores = 0;
    $jugadoresMaximo = 0;
    $MaxplayersCount = $params->MaxplayersCount;
    $MinplayersCount = $params->MinplayersCount;

    /* Código establece límite de jugadores y procesa información de reglas extranjeras. */
    $jugadoresMaximo = $MaxplayersCount;

    $ForeignRule = $params->ForeignRule;
    $ForeignRuleInfo = $ForeignRule->Info;

    if (!is_object($ForeignRuleInfo)) {
        $ForeignRuleJSON = json_decode($ForeignRuleInfo);

    } else {
        /* Asigna información de regla extranjera a variable si no se cumple una condición previa. */

        $ForeignRuleJSON = $ForeignRuleInfo;
    }


    /* asigna propiedades de un objeto JSON a variables y verifica su validez. */
    $SportsbookDeports2 = $ForeignRuleJSON->SportsbookDeports2;
    $SportsbookMarkets2 = $ForeignRuleJSON->SportsbookMarkets2;
    $SportsbookLeagues2 = $ForeignRuleJSON->SportsbookLeagues2;
    $SportsbookMatches2 = $ForeignRuleJSON->SportsbookMatches2;

    if (!is_object($ForeignRuleInfo)) {
        $ForeignRuleJSON = json_decode($ForeignRuleInfo);

    } else {
        /* Asigna $ForeignRuleInfo a $ForeignRuleJSON si la condición anterior no se cumple. */

        $ForeignRuleJSON = $ForeignRuleInfo;
    }


    /* asigna valores de un objeto JSON a variables relacionadas con apuestas. */
    $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
    $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
    $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
    $MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;
    $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total


//Si el sorteo se va a crear con stikers entonces se recibirán las siguientes variables.


    /* verifica si los stickers están activos y define variables relacionadas. */
    if ($Stickers == True) {
//variables que contiene el numero de stikers necesarios por tipo condicion
        $NumberCasinoStickers = $ForeignRuleJSON->NumberCasinoStickers;
        $NumberDepositStickers = $ForeignRuleJSON->NumberDepositStickers;
        $NumberSportsbookStickers = $ForeignRuleJSON->NumberSportsbookStickers;

//Variables que contiene el valor minimo por apuesta por tipo de condicion

        $MinBetPriceCasino = $ForeignRuleJSON->MinBetPriceCasino;
        $MinBetPriceDeposit = $ForeignRuleJSON->MinBetPriceDeposit;
        $MinBetPriceSportsbook = $ForeignRuleJSON->MinBetPriceSportsbook;


        $MinBetPrice2Casino = $ForeignRuleJSON->MinBetPrice2Casino;
        $MinBetPrice2Deposit = $ForeignRuleJSON->MinBetPrice2Deposit;
        $MinBetPrice2Sportsbook = $ForeignRuleJSON->MinBetPrice2Sportsbook;

    }



    /* asigna URLs de imágenes y reglas a variables desde un objeto JSON. */
    $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

    $MainImageURL = $params->MainImageURL;
    $CardBackgroundURL = $params->CardBackgroundURL;
    $RankingImageURL = $params->RankingImageURL;
    $BackgroundURL = $params->BackgroundURL;

    /* Variables asignadas y configuración inicial de una suscripción de usuario y condición. */
    $UserSubscribe = $params->UserSubscribe;

    $TypeCondition = $params->TypeCondition;


    $EnableSportsbook = false;

    /* Variables que configuran la activación de servicios de casino y depósitos en un sistema. */
    $EnableCasino = false;
    $EnableDeposit = false;

    $EnableSportsbook2 = 0;
    $EnableCasino2 = 0;
    $EnableDeposit2 = 0;


    /* Itera sobre condiciones para habilitar características según identificador y valor. */
    foreach ($TypeCondition as $key => $value) {

        if ($value->id == 1 && $value->value == true) {
            $EnableSportsbook = true;
            $EnableSportsbook2 = 1;

        } elseif ($value->id == 2 && $value->value == true) {
            $EnableCasino = true;
            $EnableCasino2 = 1;

        } elseif ($value->id == 3 && $value->value == true) {
            $EnableDeposit = true;
            $EnableDeposit2 = 1;
        }elseif ($value->id==4 && $value->value==true){ //  Casino en vivo
            $EnableCasino=true; // Envio de parametro en true
            $EnableCasino2=1; // Establece el valor del parametro = 1
        }elseif ($value->id==5 && $value->value==true){ //  Virtuales
            $EnableCasino=true; // Envio de parametro en true
            $EnableCasino2=1; // Establece el valor del parametro = 1
        }

    }



    /* Asigna valores de parámetros a variables y valida LinesByPoints como booleano. */
    $PrizeDescription = $params->PrizeDescription;
    $PrizeImageURL = $params->PrizeImageURL;
    $Ranks = $params->Ranks;
    $RanksPrize = $params->RanksPrize;


    $LinesByPoints = ($params->LinesByPoints == true || $params->LinesByPoints == "true") ? 1 : 0;


    /* asigna valores de parámetros a variables para su uso posterior. */
    $AmountPrize = $params->AmountPrize;
    $TypeRule = ($params->TypeRule == 1) ? 1 : 0;


    $TriggerDetails = $params->TriggerDetails;
    $Count = $TriggerDetails->Count; //Cantidad de depositos

//$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises


    /* Variables asignan datos del sistema de pago y bonificaciones del usuario. */
    $IsFromCashDesk = $TriggerDetails->IsFromCashDesk;
    $PaymentSystemId = $TriggerDetails->PaymentSystemId;
    $PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

    $UserRepeatBonus = $params->UserRepeatBonus;
    $UserSubscribeRepeat = $params->UserSubscribeRepeat;


    /* asigna valores y valida condiciones de productos en una lógica específica. */
    $WinBonusId = $params->WinBonusId;
    $TypeSaldo = $params->TypeSaldo;


    $ConditionProduct = $TriggerDetails->ConditionProduct;
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }


    /* Asignación de variables desde un objeto $TriggerDetails en PHP. */
    $Regions = $TriggerDetails->Regions;
    $Departments = $TriggerDetails->Departments;
    $Cities = $TriggerDetails->Cities;
    $CashDesks = $TriggerDetails->CashDesks;


    $RegionsUser = $TriggerDetails->RegionsUser;

    /* extrae información de un objeto llamado TriggerDetails y de parámetros del casino. */
    $DepartmentsUser = $TriggerDetails->DepartmentsUser;
    $CitiesUser = $TriggerDetails->CitiesUser;

    $BalanceZero = $TriggerDetails->BalanceZero;

    $Casino = $params->Casino->Info;

    /* Se asignan propiedades del objeto Casino a variables y se obtiene un tipo de bono. */
    $CasinoProduct = $Casino->Product;
    $CasinoProvider = $Casino->Provider;
    $CasinoCategory = $Casino->Category;


    $TypeBonusDeposit = $params->TypeBonusDeposit;



    /* reemplaza caracteres y asigna valores basados en condiciones específicas. */
    $RulesText = str_replace("#######", "'", $params->RulesText);
    $RulesText = str_replace("'", "\'", $RulesText);
    $UserSubscribe = $params->UserSubscribe;
    $TypeProduct = ($params->TypeProduct == 0) ? 0 : 1;
    $TypeOtherProduct = $params->TypeOtherProduct;
    $tipobono = ($params->TypeProduct == 0) ? 1 : 2;


    /* cambia el valor de `$tipobono` basado en `$TypeOtherProduct`. */
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


    /* Asigna 1 o 0 a $UserSubscribe según su estado; obtiene visibilidad de $params. */
    if ($UserSubscribe) {
        $UserSubscribe = 1;
    } else {
        $UserSubscribe = 0;
    }


    $IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios


    /* Variables para configuración de jugadores y tipo de producto en un sistema. */
    $OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
    $Prefix = $params->Prefix;

    $PlayersChosen = $params->PlayersChosen;


    $ProductTypeId = $params->ProductTypeId;


    /* Se extraen valores de parámetros y se inicializa un array para condiciones. */
    $TriggerId = $params->TriggerId;

    $TypeId = $params->TypeId;

    $Games = $params->Games;

    $condiciones = [];



    /* Se extraen parámetros de definición de giros gratis en una variable. */
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


    /* procesa una lista de jugadores VIP a partir de una cadena codificada. */
    $VisibleAllPlayers = $params->VisibleAllPlayers;
    $PlayersIdCsv = $params->PlayersIdCsv;
    $String = trim(substr($PlayersIdCsv, strpos($PlayersIdCsv, ','), strlen($PlayersIdCsv) - 1), ',');
    $Data = explode("\n", base64_decode($String));
    $PlayersVIP = array_filter($Data, function ($item) {
            /* crea un objeto SorteoInterno y asigna nombre y descripción. */
        return !empty($item);
    });

    $SorteoInterno = new SorteoInterno();
    $SorteoInterno->nombre = $Name;
    $SorteoInterno->descripcion = $Description;

    /* Se asignan valores a propiedades del objeto SorteoInterno para configurarlo. */
    $SorteoInterno->fechaInicio = $StartDate;
    $SorteoInterno->fechaFin = $EndDate;
    $SorteoInterno->estado = 'A';
    $SorteoInterno->usucreaId = 0;
    $SorteoInterno->usumodifId = 0;
    $SorteoInterno->mandante = $mandanteUsuario;

    /* Se asignan propiedades a un objeto SorteoInterno para gestionar un sorteo. */
    $SorteoInterno->condicional = $ConditionProduct;
    $SorteoInterno->orden = $Priority;
    $SorteoInterno->cupoActual = $cupo;
    $SorteoInterno->cupoMaximo = $cupoMaximo;
    $SorteoInterno->cantidadSorteos = $jugadores;
    $SorteoInterno->maximoSorteos = $jugadoresMaximo;

    /* Configuración de un sorteo interno con reglas y opciones habilitadas basadas en stickers. */
    $SorteoInterno->codigo = $CodeGlobal;
    $SorteoInterno->reglas = $RulesText;
    $SorteoInterno->jsonTemp = json_encode($params);


    if ($Stickers == True) {
        $Stickers2 = 1; //"1" Indica que el sorteo es con stikers
        $tipobono = 5;
        $SorteoInterno->habilitaCasino = $EnableCasino2;
        $SorteoInterno->habilitaDeportivas = $EnableSportsbook2;
        $SorteoInterno->habilitaDeposito = $EnableDeposit2;
    } else if ($Stickers == False) {
        /* asigna "0" a $Stickers2 si $Stickers es falso, indicando un sorteo sin stickers. */

        $Stickers2 = 0; //"0" Indica que el sorteo es sin stikers
    }


    /* Se asignan valores y se obtiene una transacción desde la base de datos. */
    $SorteoInterno->tipo = $tipobono;

    $SorteoInterno->pegatinas = $Stickers2;

    $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
    $transaccion = $SorteoDetalleMySqlDAO->getTransaction();

    /* Se inserta una transacción en la base de datos y se obtiene su ID. */
    $sorteoId = $SorteoInterno->insert($transaccion);

    foreach ($SportBonusRules as $key => $value) {


        /* Se crea un objeto SorteoDetalle con propiedades asignadas desde $value y $sorteoId. */
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value->ObjectId;
        $SorteoDetalle->descripcion = $value->Image;

        /* Asignación de valores y condición para establecer la fecha del sorteo. */
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        if ($value->fixedTime == "" || $value->fixedTime == null) {
            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
            $SorteoDetalle->fechaSorteo = $fixedTime;
        } else {
            /* Convierte una fecha en formato específico a uno estándar y la asigna a una variable. */

            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
            $SorteoDetalle->fechaSorteo = $fixedTime;
        }

        /* Se asigna una imagen y se inserta el detalle en la base de datos. */
        $SorteoDetalle->imagenUrl = $value->Image;

        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }

    if ($VisibleAllPlayers !== '') {

        /* Crea un nuevo objeto SorteoDetalle con propiedades basadas en condiciones específicas. */
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = 'USUARIOVISIBILIDAD';
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = !$VisibleAllPlayers ? 0 : 1;
        $SorteoDetalle->descripcion = '';

        /* Se asignan valores a propiedades de un objeto dependiendo de la condición de tiempo. */
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        if ($value->fixedTime == '') $SorteoDetalle->fechaSorteo = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
        else $SorteoDetalle->fechaSorteo = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));

        /* Asigna una imagen y almacena el detalle del sorteo en la base de datos. */
        $SorteoDetalle->imagenUrl = $value->Image;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }

    foreach ($PlayersVIP as $value) {

        /* Se crea un objeto UsuarioSorteo y se inicializan sus propiedades. */
        $UsuarioSorteo = new UsuarioSorteo();
        $UsuarioSorteo->usuarioId = $value;
        $UsuarioSorteo->sorteoId = $sorteoId;
        $UsuarioSorteo->valor = '0';
        $UsuarioSorteo->posicion = '0';
        $UsuarioSorteo->valorBase = '0';
        $UsuarioSorteo->estado = 'I';
        $UsuarioSorteo->usucreaId = '0';
        $UsuarioSorteo->usumodifId = '0';
        $UsuarioSorteo->mandante = $mandanteUsuario;
        $UsuarioSorteo->errorId = '0';
        $UsuarioSorteo->idExterno = '0';
        $UsuarioSorteo->version = '0';
        $UsuarioSorteo->apostado = '0';
        $UsuarioSorteo->codigo = '';
        $UsuarioSorteo->externoId = '0';
        $UsuarioSorteo->valorPremio = '0';
        $UsuarioSorteo->premio = '';

        /* Se asigna un premio vacío a un usuario y se inserta en la base de datos. */
        $UsuarioSorteo->premioId = '';


        $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($transaccion);
        $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
    }

    if ($SportsbookDeports2 != '') {

        /* divide una cadena en elementos usando comas como delimitador. */
        $SportsbookDeports2 = explode(',', $SportsbookDeports2);

        foreach ($SportsbookDeports2 as $key => $value) {


            /* Crea un objeto SorteoDetalle y lo inicializa con datos específicos. */
            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = "ITAINMENT" . '1';
            $SorteoDetalle->moneda = '';
            $SorteoDetalle->valor = $value->ObjectId;
            $SorteoDetalle->descripcion = $value->Image;

            /* Asignación de valores y verificación de fecha para objeto SorteoDetalle en PHP. */
            $SorteoDetalle->valor2 = '';
            $SorteoDetalle->valor3 = '';
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            } else {
                /* Convierte una fecha de formato ISO a una fecha legible y la asigna a una variable. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            }

            /* Se asigna una imagen y se inserta en la base de datos. */
            $SorteoDetalle->imagenUrl = $value->Image;
            $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

        }
    }

    if ($SportsbookLeagues2 != '') {

        /* Separa una cadena de texto en un array usando comas como delimitador. */
        $SportsbookLeagues2 = explode(',', $SportsbookLeagues2);


        foreach ($SportsbookLeagues2 as $key => $value) {


            /* Se crea un objeto SorteoDetalle con propiedades específicas del sorteo. */
            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = "ITAINMENT" . '3';
            $SorteoDetalle->moneda = '';
            $SorteoDetalle->valor = $value->ObjectId;
            $SorteoDetalle->descripcion = $value->Image;

            /* Se asignan valores a propiedades y se manipula la fecha de sorteo condicionadamente. */
            $SorteoDetalle->valor2 = '';
            $SorteoDetalle->valor3 = '';
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            } else {
                /* Convierte una fecha en formato T a Y-m-d H:i:s y la asigna a una variable. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            }

            /* Asigna una imagen y guarda el detalle del sorteo en la base de datos. */
            $SorteoDetalle->imagenUrl = $value->Image;
            $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

        }
    }


    if ($SportsbookMatches2 != '') {

        /* divide una cadena en partes, separándolas por comas y las almacena en un array. */
        $SportsbookMatches2 = explode(',', $SportsbookMatches2);


        foreach ($SportsbookMatches2 as $key => $value) {


            /* Se crea un objeto SorteoDetalle con propiedades específicas del sorteo. */
            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = "ITAINMENT" . '4';
            $SorteoDetalle->moneda = '';
            $SorteoDetalle->valor = $value->ObjectId;
            $SorteoDetalle->descripcion = $value->Image;

            /* Asignación de valores y fecha de sorteo basada en condiciones. */
            $SorteoDetalle->valor2 = '';
            $SorteoDetalle->valor3 = '';
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            } else {
                /* Convierte una fecha en formato T a un formato legible y asigna a $fechaSorteo. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            }

            /* Asignación de una imagen y almacenamiento en base de datos mediante DAO en PHP. */
            $SorteoDetalle->imagenUrl = $value->Image;
            $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

        }
    }


    if ($SportsbookMarkets2 != '') {

        /* divide una cadena en partes usando comas como delimitadores. */
        $SportsbookMarkets2 = explode(',', $SportsbookMarkets2);


        foreach ($SportsbookMarkets2 as $key => $value) {


            /* Crea un objeto "SorteoDetalle" y asigna propiedades relacionadas a un sorteo. */
            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = "ITAINMENT" . '5';
            $SorteoDetalle->moneda = '';
            $SorteoDetalle->valor = $value->ObjectId;
            $SorteoDetalle->descripcion = $value->Image;

            /* inicializa propiedades y establece la fecha de sorteo si está vacía. */
            $SorteoDetalle->valor2 = '';
            $SorteoDetalle->valor3 = '';
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            } else {
                /* Convierte una fecha en formato T a uno estándar y la asigna a un objeto. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            }

            /* Asigna una imagen y guarda los detalles de un sorteo en una base de datos. */
            $SorteoDetalle->imagenUrl = $value->Image;
            $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

        }
    }



    /* Se crea un objeto SorteoDetalle con propiedades relacionadas al sorteo y valores específicos. */
    $SorteoDetalle = new SorteoDetalle();
    $SorteoDetalle->sorteoId = $sorteoId;
    $SorteoDetalle->tipo = "VISIBILIDAD";
    $SorteoDetalle->moneda = '';
    $SorteoDetalle->valor = $TypeRule;
    $SorteoDetalle->valor2 = '';

    /* Se crea un registro en la base de datos con datos del sorteo. */
    $SorteoDetalle->valor3 = '';
    $SorteoDetalle->usucreaId = 0;
    $SorteoDetalle->usumodifId = 0;
    $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);

    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    /* Crea un objeto SorteoDetalle y asigna valores relacionados al sorteo. */
    $SorteoDetalle = new SorteoDetalle();
    $SorteoDetalle->sorteoId = $sorteoId;
    $SorteoDetalle->tipo = "USERSUBSCRIBE";
    $SorteoDetalle->moneda = '';
    $SorteoDetalle->valor = $UserSubscribe;
    $SorteoDetalle->valor2 = '';

    /* Se inicializa un objeto y se inserta en la base de datos. */
    $SorteoDetalle->valor3 = '';
    $SorteoDetalle->usucreaId = 0;
    $SorteoDetalle->usumodifId = 0;
    $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    $SorteoDetalle = new SorteoDetalle();

    /* Asignación de propiedades a un objeto SorteoDetalle con información del sorteo. */
    $SorteoDetalle->sorteoId = $sorteoId;
    $SorteoDetalle->tipo = "TIPOPRODUCTO";
    $SorteoDetalle->moneda = '';
    $SorteoDetalle->valor = $TypeProduct;
    $SorteoDetalle->valor2 = '';
    $SorteoDetalle->valor3 = '';

    /* Crea un registro de sorteo en la base de datos, incluyendo detalles opcionales. */
    $SorteoDetalle->usucreaId = 0;
    $SorteoDetalle->usumodifId = 0;
    $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    if ($LiveOrPreMatch != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "LIVEORPREMATCH";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $LiveOrPreMatch;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Inserta un registro en la base de datos si $MinSelCount no está vacío. */
    if ($MinSelCount != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "MINSELCOUNT";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $MinSelCount;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Crea un registro de SorteoDetalle si $MinSelPrice no está vacío. */
    if ($MinSelPrice != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "MINSELPRICE";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $MinSelPrice;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Inserta un registro de SorteoDetalle si MinSelPriceTotal no está vacío. */
    if ($MinSelPriceTotal != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "MINSELPRICETOTAL";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $MinSelPriceTotal;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }
    if ($MinBetPrice != "") {

        /* Inserta detalles de sorteos en la base de datos si el monto no es un objeto. */
        foreach ($MinBetPrice as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICE";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }


    if ($MinBetPriceCasino != "" && $EnableCasino == true) {

        foreach ($MinBetPriceCasino as $item) {


            /* Se verifica si Amount no es un objeto y se inserta SorteoDetalle en la base de datos. */
            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICECASINO";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);

                try {
                    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
                } catch (Exception $e) {
                    print_r($e);
                }
            }


        }
    }

    if ($MinBetPrice2Casino != "" && $EnableCasino == true) {

        foreach ($MinBetPrice2Casino as $item) {


            /* Verifica si 'Amount' no es un objeto y luego inserta 'SorteoDetalle' en la base de datos. */
            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICE2CASINO";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);

                try {
                    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
                } catch (Exception $e) {
                    print_r($e);
                }
            }


        }
    }

    if ($MinBetPriceDeposit != "" && $EnableDeposit == true) {

        /* Inserta detalles de sorteos para montos mínimos de apuestas en la base de datos. */
        foreach ($MinBetPriceDeposit as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICEDEPOSIT";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }

    if ($MinBetPrice2Deposit != "" && $EnableDeposit == true) {

        /* inserta detalles de sorteos para cada apuesta mínima válida en una base de datos. */
        foreach ($MinBetPrice2Deposit as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICE2DEPOSIT";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }

    if ($MinBetPriceSportsbook != "" && $EnableSportsbook == true) {

        /* inserta detalles de apuestas mínimas en la base de datos, si son válidos. */
        foreach ($MinBetPriceSportsbook as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICESPORTSBOOK";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }

    if ($MinBetPrice2Sportsbook != "" && $EnableSportsbook == true) {

        /* Se insertan detalles de sorteos para apuestas mínimas en la base de datos. */
        foreach ($MinBetPrice2Sportsbook as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "MINBETPRICE2SPORTSBOOK";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }


    if ($NumberCasinoStickers != "" && $EnableCasino == true) {

        /* Inserta detalles de sorteos para cada sticker no objeto en una base de datos. */
        foreach ($NumberCasinoStickers as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "NUMBERCASINOSTICKERS";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }

    if ($NumberDepositStickers != "" && $EnableDeposit == true) {

        /* Se insertan detalles de sorteos desde un arreglo, excluyendo elementos no válidos. */
        foreach ($NumberDepositStickers as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "NUMBERDEPOSITSTICKERS";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }

    if ($NumberSportsbookStickers != "" && $EnableSportsbook == true) {

        /* Inserta detalles de sorteos basados en cantidades no objetos de $NumberSportsbookStickers. */
        foreach ($NumberSportsbookStickers as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle = new SorteoDetalle();
                $SorteoDetalle->sorteoId = $sorteoId;
                $SorteoDetalle->tipo = "NUMBERSPORTSBOOKSTICKERS";
                $SorteoDetalle->moneda = $item->CurrencyId;
                $SorteoDetalle->valor = $item->Amount;
                $SorteoDetalle->valor2 = '';
                $SorteoDetalle->valor3 = '';
                $SorteoDetalle->usucreaId = 0;
                $SorteoDetalle->usumodifId = 0;
                $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
            }


        }
    }


    /* inserta detalles de premios en una base de datos para un sorteo. */
    foreach ($PrizeDescription as $key => $value) {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "PREMIODESCRIPCION";
        $SorteoDetalle->moneda = $value->CurrencyId;
        $SorteoDetalle->valor = $value->Amount;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }

    foreach ($PrizeImageURL as $key => $value) {

        /* Se crea una nueva instancia de SorteoDetalle con propiedades específicas del sorteo. */
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "PREMIOIMAGEN";
        $SorteoDetalle->moneda = $value->CurrencyId;
        $SorteoDetalle->valor = $value->Amount;
        $SorteoDetalle->valor2 = '';

        /* Asignación de valores y verificación de fecha en una clase de SorteoDetalle. */
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;


        if ($value->fixedTime == "" || $value->fixedTime == null) {
            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
            $SorteoDetalle->fechaSorteo = $fixedTime;
        } else {
            /* Convierte una fecha en formato ISO a un formato legible y la asigna a una variable. */

            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
            $SorteoDetalle->fechaSorteo = $fixedTime;
        }

        /* Asigna una imagen y guarda un detalle de sorteo en la base de datos. */
        $SorteoDetalle->imagenUrl = $value->Image;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /*foreach ($AmountPrize as $key => $value) {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "PREMIOMONTO";
        $SorteoDetalle->moneda = $value->CurrencyId;
        $SorteoDetalle->valor = $value->Amount;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }*/



    /* inserta detalles de sorteos basados en rangos y cantidades especificadas. */
    foreach ($Ranks as $key => $value) {
        foreach ($value->Amount as $key2 => $value2) {

            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = ($TypeRanking == 1) ? "RANKLINE" : "RANK";
            $SorteoDetalle->moneda = $value->CurrencyId;
            $SorteoDetalle->valor = $value2->initialRange;
            $SorteoDetalle->valor2 = $value2->finalRange;
            $SorteoDetalle->valor3 = $value2->credits;
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $SorteoDetalleMySqlDAO->insert($SorteoDetalle);
        }
    }


    foreach ($RanksPrize as $key => $value) {

        foreach ($value->Amount as $key2 => $value2) {

            /* Asigna un tipo de premio según el valor de $value2->type. */
            $idBono = null;


            $tipo = "RANKAWARD";
            if ($value2->type == 0) {
                $tipo = "RANKAWARDMAT";
                $description = $value2->description;
            } elseif ($value2->type == 2) {
                /* asigna descripciones basadas en el tipo de bono recibido. */

                $tipo = "BONO";
                $idBono = $value2->amount;
                $BonoInterno = new BonoInterno($idBono);
                switch ($BonoInterno->tipo) {
                    case "5":
                        $description = "Saldo FreeCasino";
                        break;
                    case "6":
                        $description = "Saldo FreeBet";
                        break;
                }
            } else {
                /* Asigna descripciones a variable según el valor de $value2->description. */

                if ($value2->description == 0) {
                    $description = "Saldo Creditos";
                }
                if ($value2->description == 1) {
                    $description = "Saldo Premios";
                }
                if ($value2->description == 2) {
                    $description = "Saldo Bonos";
                }

            }



            /* Crea un objeto SorteoDetalle y asigna propiedades específicas del sorteo. */
            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = $tipo;
            $SorteoDetalle->moneda = $value->CurrencyId;
            $SorteoDetalle->valor = $value2->position;
            $SorteoDetalle->valor2 = $idBono;

            /* Se asignan valores a un objeto SorteoDetalle, incluyendo estado y permitiendo ganadores. */
            $SorteoDetalle->valor3 = $value2->amount;
            $SorteoDetalle->descripcion = $description;
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            $SorteoDetalle->estado = 'A';

            $SorteoDetalle->permiteGanador =  $value2->winningCoupons;

            /* Asignar jugadores excluidos y establecer la fecha del sorteo si es necesario. */
            $SorteoDetalle->jugadorExcluido =  $value2->winningPlayers;


            if ($value2->fixedTime == "" || $value2->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            } else {
                /* Convierte una fecha en formato ISO a formato legible y la asigna. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value2->fixedTime)));
                $SorteoDetalle->fechaSorteo = $fixedTime;
            }



            /* Se asigna una imagen y se guarda el detalle en la base de datos. */
            $SorteoDetalle->imagenUrl = $value2->urlImg;
            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $id = $SorteoDetalleMySqlDAO->insert($SorteoDetalle);

            $value2->detailId = $id;
        }
    }


    /* Código que actualiza un sorteo e inserta detalles de imagen si está disponible. */
    $params->RanksPrize = $RanksPrize;
    $SorteoInterno->jsonTemp = json_encode($params);
    $SorteoInterno->update($transaccion);

    if ($MainImageURL != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "IMGPPALURL";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $MainImageURL;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalle->imagenUrl = $MainImageURL;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }



    /* Se inserta un detalle de sorteo si el URL de fondo de tarjeta no está vacío. */
    if ($CardBackgroundURL != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CARDBACKGROUNDURL";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $CardBackgroundURL;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalle->imagenUrl = $CardBackgroundURL;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }

    /* Inserta detalles de un sorteo si la URL de la imagen no está vacía. */
    if ($RankingImageURL != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "RANKIMGURL";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $RankingImageURL;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalle->imagenUrl = $RankingImageURL;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* inserta un detalle de sorteo si BackgroundURL no está vacío. */
    if ($BackgroundURL != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "BACKGROUNDURL";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $BackgroundURL;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Inserta un nuevo detalle de sorteo si $ProductTypeId no está vacío. */
    if ($ProductTypeId !== "") {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "TIPOPRODUCTO";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $ProductTypeId;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Inserta un detalle de sorteo en la base de datos si LinesByPoints es 1. */
    if ($LinesByPoints == 1) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "MULTLINEAS";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $LinesByPoints;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Inserta un registro de detalles de sorteo si el conteo de jugadores es válido. */
    if ($MaxplayersCount !== "") {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "MAXPLAYERSCOUNT";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $MaxplayersCount;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }



    /* Se inserta un registro de "SorteoDetalle" si $MinplayersCount no está vacío. */
    if ($MinplayersCount !== "") {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "MINPLAYERSCOUNT";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $MinplayersCount;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* Inserta detalles de un sorteo en base de datos si el bono del usuario no está vacío. */
    if ($UserRepeatBonus != "") {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "REPETIRSORTEO";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $UserRepeatBonus;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    }


    /* Inserta un registro de sorteo si el usuario repite su suscripción. */
    if ($UserSubscribeRepeat != "") {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "REPETIRSORTEO";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $UserSubscribeRepeat;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    }


    /* asigna "ALL" a $PaymentSystemIds["Id"] si está vacío. */
    if (empty($PaymentSystemIds)) {
        $PaymentSystemIds["Id"] = "ALL";
    }



    /* inserta detalles de sorteos basados en identificadores de sistemas de pago. */
    foreach ($PaymentSystemIds as $key => $value) {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;

        if ($value == "ALL" && $EnableDeposit2 == 1) {
            $SorteoDetalle->tipo = "CONDPAYMENT" . $value->Id;
        } else {
            $SorteoDetalle->tipo = "CONDPAYMENT" . $value->Id;
        }

        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }



    /* inserta detalles de sorteos en una base de datos para cada región. */
    foreach ($Regions as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDPAISPV";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* inserta detalles de un sorteo para cada departamento en una base de datos. */
    foreach ($Departments as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDDEPARTAMENTOPV";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Inserta detalles de sorteos en la base de datos para cada ciudad en la lista. */
    foreach ($Cities as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDCIUDADPV";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Inserta un registro en SorteoDetalle si el balance es cero. */
    if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDBALANCE";
        $SorteoDetalle->moneda = '';

        $SorteoDetalle->valor = '0';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    }



    /* Inserta detalles del sorteo para cada región en la base de datos. */
    foreach ($RegionsUser as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDPAISUSER";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Itera sobre departamentos de usuario, creando y guardando detalles de sorteo en la base. */
    foreach ($DepartmentsUser as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDDEPARTAMENTOUSER";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    }


    /* Inserta detalles de sorteos por cada ciudad asociada al usuario. */
    foreach ($CitiesUser as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDCIUDADUSER";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Crea y guarda detalles del sorteo para cada caja en una base de datos. */
    foreach ($CashDesks as $key => $value) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDPUNTOVENTA";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Inserta detalles de sorteos para cada juego en la base de datos. */
    foreach ($Games as $key => $value) {
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "CONDGAME" . $value->Id;
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $value->WageringPercent;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* asigna "ALL" a $CasinoProduct["Id"] si está vacío. */
    if (empty($CasinoProduct)) {
        $CasinoProduct["Id"] = "ALL";
    }


    foreach ($CasinoProduct as $key => $value) {


        /* Crea un nuevo detalle de sorteo basado en condiciones dadas. */
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;

        if ($value == "ALL" && $EnableCasino2 == 1) {
            $SorteoDetalle->tipo = "CONDGAME" . "_ALL";
            $SorteoDetalle->valor = 200;
        } else {
            /* Asigna tipo y valor a $SorteoDetalle basándose en una condición específica. */

            $SorteoDetalle->tipo = "CONDGAME" . $value->Id;
            $SorteoDetalle->valor = 100;
        }



        /* Inicializa propiedades de un objeto "SorteoDetalle" con valores por defecto. */
        $SorteoDetalle->moneda = '';

        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;

        /* Se crea una instancia DAO y se inserta un detalle de sorteo en MySQL. */
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* verifica si $CasinoProvider está vacío y le asigna un valor. */
    if (empty($CasinoProvider)) {
        $CasinoProvider["Id"] = "ALL";
    }


    foreach ($CasinoProvider as $key => $value) {

        /* Crea un objeto y asigna valores según condiciones específicas de un sorteo. */
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;


        if ($value == "ALL" && $EnableCasino2 == 1) {
            $SorteoDetalle->tipo = "CONDSUBPROVIDER" . "_ALL";
            $SorteoDetalle->valor = 200;
        } else {
            /* Asigna un tipo y valor al objeto SorteoDetalle basado en una condición específica. */

            $SorteoDetalle->tipo = "CONDSUBPROVIDER" . $value->Id;
            $SorteoDetalle->valor = 100;
        }


        /* inicializa propiedades de un objeto SorteoDetalle con valores vacíos o cero. */
        $SorteoDetalle->moneda = '';

        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;

        /* Se crea un objeto DAO y se inserta un detalle en la base de datos. */
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Verifica si la categoría del casino está vacía y asigna "ALL" al ID. */
    if (empty($CasinoCategory)) {
        $CasinoCategory["Id"] = "ALL";
    }

    foreach ($CasinoCategory as $key => $value) {

        /* Crea un objeto sorteado, configurando tipo y valor basado en condiciones específicas. */
        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;

        if ($value == "ALL" && $EnableCasino2 == 1) {
            $SorteoDetalle->tipo = "CONDCATEGORY" . "_ALL";
            $SorteoDetalle->valor = 200;
        } else {
            /* Asigna un tipo y valor al objeto SorteoDetalle según la condición. */

            $SorteoDetalle->tipo = "CONDCATEGORY" . $value->Id;
            $SorteoDetalle->valor = 100;
        }



        /* Se inicializan propiedades de SorteoDetalle y se crea un objeto DAO para base de datos. */
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);

        /* Inserta un objeto $SorteoDetalle en la base de datos mediante una llamada a DAO. */
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Valida y registra un nuevo detalle de sorteo si el ID del bono es diferente a cero. */
    if ($WinBonusId != 0) {

        $SorteoDetalle = new SorteoDetalle();
        $SorteoDetalle->sorteoId = $sorteoId;
        $SorteoDetalle->tipo = "WINBONOID";
        $SorteoDetalle->moneda = '';
        $SorteoDetalle->valor = $WinBonusId;
        $SorteoDetalle->valor2 = '';
        $SorteoDetalle->valor3 = '';
        $SorteoDetalle->usucreaId = 0;
        $SorteoDetalle->usumodifId = 0;
        $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    } else {
        /* Condicional que inserta un registro de SorteoDetalle si la condición es falsa. */


        if (false) {
            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $sorteoId;
            $SorteoDetalle->tipo = "TIPOSALDO";
            $SorteoDetalle->moneda = '';
            $SorteoDetalle->valor = $TypeSaldo;
            $SorteoDetalle->valor2 = '';
            $SorteoDetalle->valor3 = '';
            $SorteoDetalle->usucreaId = 0;
            $SorteoDetalle->usumodifId = 0;
            $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
            $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

        }

    }


    /*


            if ($TriggerId != "") {
                if($CodePromo != ""){

                    $SorteoDetalle = new SorteoDetalle();
                    $SorteoDetalle->sorteoId = $sorteoId;
                    $SorteoDetalle->tipo = "CODEPROMO";
                    $SorteoDetalle->moneda = '';
                    $SorteoDetalle->valor = $CodePromo;
                    $SorteoDetalle->usucreaId = 0;
                    $SorteoDetalle->usumodifId = 0;
                    $SorteoDetalleMysqlDAO = new SorteoDetalleMySqlDAO($transaccion);
                    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

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
                    $UsuarioBono->setBonoId($sorteoId);
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
                    $UsuarioBono->setBonoId($sorteoId);
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


                        $respuesta = $BonoInterno->agregarBonoFree($sorteoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

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

                    $respuesta = $BonoInterno->agregarBonoFree($sorteoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


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


                        $respuesta = $BonoInterno->agregarBonoFree($sorteoId, $jugadoresAsignarFinal[$i]["Id"], "0", $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                    }

                }
            }
            */

    $transaccion->commit();


    //$transaccion->commit();
    /*Generación respuesta de éxito*/
    $response["idLottery"] = $sorteoId;

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
} catch (Exception $e) {
    /*Generación respuesta de error*/
    if ($_ENV['debug']) {
        print_r($e);
    }
    $response["idLottery"] = $sorteoId;

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}
