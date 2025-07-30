<?php

use Backend\mysql\JackpotInternoMySqlDAO;
use Backend\sql\Transaction;
use Backend\dto\JackpotInterno;
use Backend\dto\Usuario;
use Backend\dto\JackpotDetalle;
use Backend\mysql\UsuariojackpotGanadorMySqlDAO;
use Backend\dto\UsuariojackpotGanador;

/**
 * Funciones para la gestión de opciones de Jackpot.
 *
 * Estas funciones permiten gestionar las opciones relacionadas con los jackpots en las verticales de Casino,
 * Sportbook y otros. Se encargan de recibir parámetros, almacenar detalles y realizar las configuraciones necesarias
 * para cada tipo de jackpot, incluyendo la validación y el cálculo de valores relacionados con apuestas, verticales y notificaciones.
 *
 * La función `manageCasinoOptions` maneja las opciones específicas para los juegos de casino, mientras que
 * `manageSportbookOptions` se encarga de las opciones de apuestas deportivas. Ambas funciones procesan
 * los detalles y los organizan en una estructura adecuada para el jackpot.
 *
 * @param object $verticalObject : Objeto que representa las opciones de la vertical, puede incluir categorías, proveedores y productos (Casino) o deportes, mercados y partidos (Sportbook).
 * @param array &$detailsStack : Array donde se acumularán los detalles del jackpot, como categorías, productos, y otros parámetros necesarios para la configuración.
 * @param string $targetVertical : Identificador de la vertical objetivo (por ejemplo, "CASINO" o "SPORTBOOK").
 * @param bool $excluded : Indica si se deben excluir ciertos detalles de la configuración del jackpot (valor predeterminado es `false`).
 *
 * @return bool Retorna `true` cuando se han procesado correctamente las opciones de la vertical.
 *
 * @throws Exeptions si ocurren que el Jackpot no tenga un valor inicial en el pozo, Jackpot tenga valores negativos
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * manageCasinoOptions
 *
 * Gestiona las opciones de casino asignando categorías, proveedores y productos a una pila de detalles,
 * agregando un prefijo opcional para elementos excluidos.
 *
 * @param object $verticalObject Objeto que contiene las listas de categorías, proveedores y productos.
 * @param array &$detailsStack Referencia al array donde se almacenarán los detalles generados.
 * @param string $targetVertical Identificador del vertical objetivo al que se asocian los elementos.
 * @param bool $excluded Indica si los elementos deben ser marcados como excluidos. (Opcional, por defecto `false`).
 *
 * @return bool response Retorna `true` al finalizar el proceso de asignación.
 *
 *
 * @throws Exception Si el formato de los datos de entrada es incorrecto.
 * @access public
 */

/** Recepción de parámetros */
function manageCasinoOptions($verticalObject, &$detailsStack, $targetVertical, $excluded = false) {
    if ($excluded) $typePrefix = 'EXCLUDED_';
    else $typePrefix = '';

    $iterableItems = [
        'categories' => $verticalObject->Categories,
        'suppliers' => $verticalObject->Suppliers,
        'products' => $verticalObject->Products
    ];

    foreach ($iterableItems as $type => $items) {
        $detail = match($type) {
            'categories' => 'CONDCATEGORY',
            'suppliers' => 'CONDPROVIDER',
            'products' => 'CONDGAME',
            default => 'unknow'
        };
        $detailType = $typePrefix . $detail;

        foreach ($items as $item) {
            $detailsStack[] = ['type' => $detailType, 'value' => "{$targetVertical}_" . $item->Id];
        }
    }

    return true;
}

/**
 * manageSportbookOptions
 *
 * Gestiona las opciones de apuestas deportivas asignando deportes, mercados, ligas y partidos a una pila de detalles.
 * Permite marcar elementos como excluidos mediante un prefijo opcional.
 *
 * @param object $vertical Objeto que contiene las listas de deportes, mercados, ligas y partidos.
 * @param array &$detailsStack Referencia al array donde se almacenarán los detalles generados.
 * @param bool $excluded Indica si los elementos deben ser marcados como excluidos. (Opcional, por defecto `false`).
 *
 * @return bool response Retorna `true` al finalizar el proceso de asignación.
 *
 *
 * @throws Exception Si el formato de los datos de entrada es incorrecto.
 * @access public
 */
function manageSportbookOptions($vertical, &$detailsStack, $excluded = false) {
    if ($excluded) $typePrefix = 'EXCLUDED_';
    else $typePrefix = '';

    $iterableItems = [
        'sport' => $vertical->Sport,
        'market' => $vertical->Market,
        'league' => $vertical->League,
        'match' => $vertical->Match
    ];

    foreach ($iterableItems as $type => $items) {
        $detail = match($type) {
            'sport' => 'ITAINMENT1',
            'league' => 'ITAINMENT3',
            'match' => 'ITAINMENT4',
            'market' => 'ITAINMENT5',
            default => 'unknow'
        };
        $detailType = $typePrefix . $detail;

        foreach ($items as $item) {
            $detailsStack[] = ['type' => $detailType, 'value' => $item];
        }
    }

    return true;
}


/** MODELO
 * {
 *   // CAIDA CANTIDAD DE APUESTAS == 1
 *  "TypeFall": {
 *  "Type": 1,
 *  "MinimumCantBet": FLOAT,
 *  "MaximumCantBet": FLOAT,
 *  }
 *  // CAIDA RANGO DE VALOR == 2 --En desuso
 *  "TypeFall": {
 *  "Type": 2,
 *  "MinimumWellValue": FLOAT,
 *  "MaximumWellValue": FLOAT,
 *  }
 * }
 * */
$typeFall = $params->TypeFall; //Objeto de criterios de caída del Jackpot

/** MODELO
 *  "Vertical": [
 *      // CASINO == 1, CASINO EN VIVO == 3, VIRTUALES == 4
 *      {
 *      "Id": 1 || 3 || 4,
 *      "InitialValue": FLOAT,
 *      // RebootJackpot == TRUE
 *      "InitialValueRebootJackpot": FLOAT,
 *
 *      "Categories": [
 *      {
 *      "Id": STRING,
 *      "Name": STRING
 *      },
 *      ],
 *      "Suppliers": [
 *      {
 *      "Id": STRING,
 *      "Name": STRING
 *      }
 *      ],
 *      "Products": [
 *      {
 *      "Id": STRING,
 *      "Name": STRING
 *      }
 *      ]
 *      },
 *      // DEPORTIVAS
 *      {
 *      "Id": 2,
 *      "InitialValue": FLOAT,
 *      // RebootJackpot == TRUE
 *      "InitialValueRebootJackpot": FLOAT,
 *      "Sport": [ 'ID', 'ID', 'ID'],
 *      "Market": [ 'ID', 'ID', 'ID'],
 *      "League": [ 'ID', 'ID', 'ID'],
 *      "Match": [ 'ID', 'ID', 'ID']
 *     }
 *  ]
 */
$verticals = $params->Verticals; //Verticales que acreditan la apuesta

/** "ExcludedVerticals":
 *  [
 *      // CASINO == 1, CASINO EN VIVO == 3, VIRTUALES == 4
 *      {
 *      "Id": 1 || 3 || 4,
 *      "InitialValue": FLOAT,
 *      // RebootJackpot == TRUE
 *      "InitialValueRebootJackpot": FLOAT,
 *
 *      "Categories": [
 *      {
 *      "Id": STRING,
 *      "Name": STRING
 *      }
 *      ],
 *      "Suppliers": [
 *      {
 *      "Id": STRING,
 *      "Name": STRING
 *      }
 *      ],
 *      "Products": [
 *      {
 *      "Id": STRING,
 *      "Name": STRING
 *      }
 *      ]
 *      },
 *      // DEPORTIVAS
 *      {
 *      "Id": 2,
 *      "InitialValue": FLOAT,
 *      // RebootJackpot == TRUE
 *      "InitialValueRebootJackpot": FLOAT,
 *
 *      "Sport": [ 'ID', 'ID', 'ID'],
 *      "Market": [ 'ID', 'ID', 'ID'],
 *      "League": [ 'ID', 'ID', 'ID'],
 *      "Match": [ 'ID', 'ID', 'ID']
 *      }
 * ]
 * */
$excludedVerticals = $params->ExcludedVerticals; //Vertical que excluyen la apuesta

$Transaction = new Transaction();

/** Solicitando información del operador */
$Usuario = new Usuario($_SESSION['usuario']);

/** Construcción JackpotInterno */
$jackpotType = $params->TypeJackpot;
$jackpotName = $params->Name;
$description = $params->Description;
$order = $params->Order;
$rebootJackpot = $params->RebootJackpot;
$beginDate = $params->BeginDate;
$imagen = $params->Imagen;
$imagen2 = $params->Imagen2;
$gif = $params->Gif;
$videoDesktop = $params->VideoDesktop;
$videoMobile = $params->VideoMobile;
$reglas = $params->RulesText;
$informacion = $params->JackpotInformation;
$Notifications = $params->Notifications;

/**
 * Propósito: guardar los tipos de notificaciones de jackpot
 *    $Notifications: la variable Notifications lo que permite es tomar el tipo de notificaciones que enviara el Jackpot pueden ser Popup,Email,Inbox,Sms
 */




foreach ($Notifications as $valor) { // proposito: el proposito de este foreach es recorrer la variable notifications y sacar sus tipos.
    switch ($valor->Type) {
        case 'Popup':
            $popupText = $valor->Text ?? null;
            $popupUrl = $valor->Url ?? null;
            break;

        case 'Email':
            $emailSubject = $valor->Subject ?? null;
            $emailHtml = $valor->Html ?? null;
            break;

        case 'Inbox':
            $inboxSubject = $valor->Subject ?? null;
            $inboxHtml = $valor->Html ?? null;
            break;

        case 'Sms':
            $smsText = $valor->Text ?? null;
            break;
    }
}




//Obteniendo tipo de Jackpot
if (empty($jackpotType) || $jackpotType == 'UNIQUE') $jackpotType = 0;
elseif ($jackpotType == 'LEVELS') $jackpotType = 1;

//Formateando fecha de inicio
if (!empty($beginDate)) {
    $beginDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $beginDate)));
}

//Si jackpot no es de reinicio se define fecha final
if (!$rebootJackpot) {
    $endDate = $params->EndDate;
    $endDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $endDate)));
}
else {
    $endDate = null;
}


$JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($Transaction);
$JackpotInterno = new JackpotInterno();

$JackpotInterno->jackpotPadre = 0;
$JackpotInterno->tipo = $jackpotType;
$JackpotInterno->reinicio = (int)$rebootJackpot;
$JackpotInterno->nombre = str_replace("'", "\'", $jackpotName);
$JackpotInterno->descripcion = str_replace("'", "\'", $description);
$JackpotInterno->orden = str_replace("'", "\'", $order);
$JackpotInterno->valorActual = 0; //Se actualiza después de obtenidos todos los detalles
$JackpotInterno->estado = 'A';
$JackpotInterno->mandante = $_SESSION['mandante'];
$JackpotInterno->fechaInicio = $beginDate;
$JackpotInterno->fechaFin = $endDate;
$JackpotInterno->imagen = str_replace("'", "\'", $imagen);
$JackpotInterno->imagen2 = str_replace("'", "\'", $imagen2);
$JackpotInterno->gif = str_replace("'", "\'", $gif);
$JackpotInterno->videoMobile = str_replace("'", "\'", $videoMobile);
$JackpotInterno->videoDesktop = str_replace("'", "\'", $videoDesktop);
$JackpotInterno->usucreaId = $Usuario->usuarioId;
$JackpotInterno->usumodifId = $Usuario->usuarioId;
$JackpotInterno->reglas = str_replace("'", "\'", $reglas);
$JackpotInterno->informacion = $informacion;

$JackpotInternoMySqlDAO->insert($JackpotInterno);

/** Acumulando detalles del Jackpot */
$currency = $params->Currency;
$jackpotDetails = []; //Array de objetos de la forma {'type' => $tipo, 'value' => $valor}

/**
 * Propósito: guardar el mensaje que contiene el correo
 * Descripción de variables:
 *    - $emailHtml: esta variable contine el contenido del correo que le va a llegar al usuario;
 *      $emailSubject: esta variable contiene el titulo del correo que le va a llegar al usuario;
 *    - $jackpotDetails: este arreglo contiene los detalles del jackpot
 */


if($emailHtml != "" and $emailSubject != ""){
    $jackpotDetails[] = ['type' => 'EMAILHTML', 'value' => $emailHtml];
    $jackpotDetails[] = ['type' => 'EMAILSUBJECT', 'value' => $emailSubject];
}

/**
 * Propósito: gguardar el mensaje que le va a llegar al celular del usuario
 * Descripción de variables:
 *    - $smsText: esta variable contine el contenido del mensaje que le va a llegar al usuario a el celular;
 *    - $jackpotDetails: este arreglo contiene los detalles del jackpot en este caso el mensaje
 */

if($smsText != ""){
    $jackpotDetails[] = ['type' => 'SMSTEXT', 'value' => $smsText];
}

/**
 * Propósito: Guardar los detalles del pop up que le llegara al usuario
 * Descripción de variables:
 *    - $popupText: esta variable contine el contenido del pop up que le saldra al usuario cuando ingrese en la plataforma;
 *    - $popupUrl: esta variable contine la url a la cual redireccionara el pop up
 *    - $jackpotDetails: este arreglo contiene los detalles del jackpot en este caso el la url y el contenido del pop up
 */


if($popupText != "" and $popupUrl != ""){
    $jackpotDetails[] = ['type' => 'POPUPTEXT', 'value' => $popupText];
    $jackpotDetails[] = ['type' => 'POPUPURL', 'value' => $popupUrl];
}

/**
 * Propósito: Guardar los detalles del inbox  llegara al usuario en la bandeja de entrada
 * Descripción de variables:
 *    - $inboxHtml: esta variable contine el contenido del mensaje que le llegara al usuario en la bandeja;
 *    - $inboxSubject: esta variable contine el titulo del mensaje que llegara en la bandeja de entrada al usuario
 *    - $jackpotDetails: este arreglo contiene los detalles del jackpot en este caso el titulo y contenido del inbox
 */

if($inboxHtml != "" and $inboxSubject != ""){
    $jackpotDetails[] = ['type' => 'INBOXHTML', 'value' => $inboxHtml];
    $jackpotDetails[] = ['type' => 'INBOXURL', 'value' => $inboxSubject];
}


//Acumulando series
$series = $params->Series;
if ($rebootJackpot && $series !== null && $series >= 0) {
    $jackpotDetails[] = ['type' => 'TOTALSERIES', 'value' => $series];
    $jackpotDetails[] = ['type' => 'CURRENTSERIE', 'value' => 1];
}

//Acumulando país
$country = $params->Country;
if (!empty($country)) $jackpotDetails[] = ['type' => 'CONDPAISUSER', 'value' => $country];

//Condicional sobre el simbolo de moneda
$ShowCurrencySign= $params->ShowCurrencySign;

if (!empty($ShowCurrencySign) && $ShowCurrencySign == 'true'){
    $jackpotDetails[] = ['type' => 'SHOWCURRENCYSIGN', 'value' => '1']; // Si el simbolo de moneda se pide
} else {
    $jackpotDetails[] = ['type' => 'SHOWCURRENCYSIGN', 'value' => '0']; // si el simbolo de moneda no se pide
}

//Condicional sobre decimales o enteros
$CounterStyle= $params->CounterStyle;

if (!empty($CounterStyle)) $jackpotDetails[] = ['type' => 'COUNTERSTYLE', 'value' => $CounterStyle];

/** Definiendo verticales + exclusiones del Jackpot */
$possibleVerticals = [
    1 => 'CASINO',
    2 => 'SPORTBOOK',
    3 => 'LIVECASINO',
    4 => 'VIRTUAL'
];
$jackpotWellVerticalsIncome = [];
//Definiendo verticales del Jackpot
foreach ($verticals as $vertical) {
    $currentVertical = $possibleVerticals[$vertical->Type];
    if ((float) $vertical->InitialValue < 0) Throw new Exception('Jackpot no acepta valores negativos', 300040);

    //Definiendo Valores iniciales del Jackpot
    $initialValueType = match($currentVertical) {
        'CASINO' => 'JACKPOTINITVALUE_CASINO',
        'LIVECASINO' => 'JACKPOTINITVALUE_LIVECASINO',
        'VIRTUAL' => 'JACKPOTINITVALUE_VIRTUAL',
        'SPORTBOOK' => 'JACKPOTINITVALUE_SPORTBOOK',
        default => 'ERROR_INITIALVALUE'
    };

    $jackpotDetails[] = ['type' => $initialValueType, 'value' => (float) $vertical->InitialValue];
    $jackpotWellVerticalsIncome[] = end($jackpotDetails);


    /** Definiendo criterios de conteo de las apuestas según la vertical*/
    $minimumBet = (float) $vertical->MinimumBet;
    $maximumBet = (float) $vertical->MaximumBet;
    $accumulationPercentage = (float) $vertical->AccumulationPercentage;

    //Validando que parámetros estén dentro de los parámetros lógicos
    if ($accumulationPercentage <= 0 || $accumulationPercentage > 100) $percentageType = 'JACKPOTPERCENTAGE_ERROR';
    else $percentageType = 'JACKPOTPERCENTAGE_' . $currentVertical;

    if ($minimumBet <= 0 || $minimumBet > $maximumBet)  {
        $minimumBetType = 'MINAMOUNT_ERROR';
        $maximumBetType = 'MAXAMOUNT_ERROR';
    }
    else {
        $minimumBetType = 'MINAMOUNT_' . $currentVertical;
        $maximumBetType = 'MAXAMOUNT_' . $currentVertical;
    }

    $jackpotDetails[] = ['type' => $minimumBetType, 'value' => $minimumBet];
    $jackpotDetails[] = ['type' => $maximumBetType, 'value' => $maximumBet];
    $jackpotDetails[] = ['type' => $percentageType, 'value' => $accumulationPercentage];


    if ($rebootJackpot) {
        //En caso de contar con reincio se define valor inicial para Jackpots de reinicio
        $initialValueNextSerieType = match ($currentVertical) {
            'CASINO' => 'JACKPOTINITVALUE_CASINO_NEXTSERIE',
            'LIVECASINO' => 'JACKPOTINITVALUE_LIVECASINO_NEXTSERIE',
            'VIRTUAL' => 'JACKPOTINITVALUE_VIRTUAL_NEXTSERIE',
            'SPORTBOOK' => 'JACKPOTINITVALUE_SPORTBOOK_NEXTSERIE',
            default => 'ERROR_INITIALVALUE_NEXTSERIE'
        };
        $jackpotDetails[] = ['type' => $initialValueNextSerieType, 'value' => (int)$vertical->InitialValueRebootJackpot];
    }

    //Definiendo cateogrías, proveedores y productos
    if (in_array($currentVertical, ['CASINO', 'LIVECASINO', 'VIRTUAL'])) manageCasinoOptions($vertical, $jackpotDetails, $currentVertical);
    //Definiendo deportes, eventos, ligas y mercados de sportbook
    elseif ($currentVertical == 'SPORTBOOK') manageSportbookOptions($vertical, $jackpotDetails);
}

//Definiendo exclusiones del jackpot
foreach ($excludedVerticals as $excludedVertical) {
    $currentVertical = $possibleVerticals[$excludedVertical->Type];

    //Excluyendo categorias, productos y proveedores
    if (in_array($currentVertical, ['CASINO', 'LIVECASINO', 'VIRTUAL'])) manageCasinoOptions($excludedVertical, $jackpotDetails, $currentVertical, true);
    //Excluyendo deportes, eventos, ligas y mercados de sportbook
    elseif ($currentVertical == 'SPORTBOOK') manageSportbookOptions($excludedVertical, $jackpotDetails, true);
}


/** Definiendo saldo objetivo
 * Saldo recarga = 1
 * Saldo retiro = 2
 * */
$targetBalanceType = in_array($params->TargetBalanceType, [1,2]) ? $params->TargetBalanceType : 2;

$jackpotDetails[] = ['type' => 'TIPOSALDO', 'value' => $targetBalanceType];


/** Definiendo tipo de caída */
$typeFall = $params->TypeFall;

//Caída por cantidad de apuestas
if ($typeFall->Type == 1) {
    $minimumCantBet = (int) $typeFall->MinimumCantBet;
    $maximumCantBet = (int) $typeFall->MaximumCantBet;
    if ($minimumBet <= $maximumBet) {
        $winnerCantBet = rand($minimumCantBet, $maximumCantBet);
    }
    else $winnerCantBet = 'ERROR_WINNERBET';

    $jackpotDetails[] = ['type' => 'FALLCRITERIA_MINBETQUANTITY', 'value' => $minimumCantBet];
    $jackpotDetails[] = ['type' => 'FALLCRITERIA_MAXBETQUANTITY', 'value' => $maximumCantBet];
    $jackpotDetails[] = ['type' => 'FALLCRITERIA_WINNERBET', 'value' => $winnerCantBet];
}
else $jackpotDetails[] = ['type' => 'FALLCRITERIA_ERROR', 'value' => 'GENERALCREATIONERROR'];


/** Definiendo tipo de caída para el último día  */
if (!$JackpotInterno->reinicio) {
    $typeFallEndDate = $params->TypeFallEndDate;

    if ($typeFallEndDate->Type == 1) {
        $lastDayMinimumCantBet = (int) $typeFallEndDate->MinimumCantBet;
        $lastDayMaximumCantBet = (int) $typeFallEndDate->MaximumCantBet;

        if ($lastDayMinimumCantBet <= $lastDayMaximumCantBet) {
            $jackpotDetails[] = ['type' => 'FALLCRITERIA_LASTDAYMINBETQUANTITY', 'value' => $lastDayMinimumCantBet];
            $jackpotDetails[] = ['type' => 'FALLCRITERIA_LASTDAYMAXBETQUANTITY', 'value' => $lastDayMaximumCantBet];
        }
    }
}

/** Inserción general de los detalles del Jackpot */
$jackpotDetails = json_decode(json_encode($jackpotDetails));
$JackpotDetalle = new JackpotDetalle();
$cleanCreation = $JackpotDetalle->insertarDetallesJackpot($JackpotInterno->jackpotId, $jackpotDetails, $currency, $Transaction, $Usuario->usuarioId);


/** Definiendo ganancia inicial del jackpot */
$totalIncome = 0;
$jackpotWellVerticalsIncome = json_decode(json_encode($jackpotWellVerticalsIncome));
$UsuarioJackpotGanadorMySqlDAO = new UsuariojackpotGanadorMySqlDAO($Transaction);
foreach ($jackpotWellVerticalsIncome as $wellVerticalIncome) {
    $wellVertical = $wellVerticalIncome->type;
    $income = $wellVerticalIncome->value;

    if ($income < 0) Throw new Exception('Jackpot no acepta valores negativos', 300040);
    else $income = (float) $income;

    $jackpotAccumulatedByVertical = match ($wellVertical) {
        'JACKPOTINITVALUE_CASINO' => 'INCOME_CASINO',
        'JACKPOTINITVALUE_LIVECASINO' => 'INCOME_LIVECASINO',
        'JACKPOTINITVALUE_VIRTUAL' => 'INCOME_VIRTUAL',
        'JACKPOTINITVALUE_SPORTBOOK' => 'INCOME_SPORTBOOK',
        default => 'INCOME_ERROR'
    };

    $UsuarioJackpotGanador = new UsuariojackpotGanador();

    $UsuarioJackpotGanador->usujackpotId = 0;
    $UsuarioJackpotGanador->jackpotId = $JackpotInterno->getJackpotId();
    $UsuarioJackpotGanador->tipo = $jackpotAccumulatedByVertical;
    $UsuarioJackpotGanador->usuarioId = 0;
    $UsuarioJackpotGanador->valorPremio = (float) $income;
    $UsuarioJackpotGanador->estado = 'A';
    $UsuarioJackpotGanador->usucreaId = 0;
    $UsuarioJackpotGanador->usumodifId = 0;

    $UsuarioJackpotGanadorMySqlDAO->insert($UsuarioJackpotGanador);
    $totalIncome += $income;
}

//Actualizando valor del pozo en JackpotInterno
if ($totalIncome <= 0) Throw new Exception('Jackpot requiere un valor inicial en el pozo',300041);
$JackpotInterno->setValorActual($totalIncome);
$JackpotInterno->setValorBase($totalIncome);

$JackpotInternoMySqlDAO->update($JackpotInterno);
$Transaction->commit();

if ($cleanCreation) {
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["JackpotID"] = $JackpotInterno->getJackpotId();
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}
else {
    $response["HasError"] = true;
    $response["AlertType"] = "Warning";
    $response["AlertMessage"] = "JackpotID: " . $JackpotInterno->getJackpotId() . "presentó errores en su creación";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}
?>