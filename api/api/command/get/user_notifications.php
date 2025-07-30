<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Este script procesa notificaciones de usuario basadas en transacciones, movimientos y tipos definidos.
 * 
 * @param object $json Objeto JSON que contiene:
 * @param array $params->where->transactions Array de IDs de transacciones a consultar.
 * @param array $params->where->types Array de tipos de operación a consultar.
 * @param array $params->where->movements Array de movimientos a consultar.
 * @param int $params->where->start Número de filas a omitir en la consulta.
 * @param int $params->where->count Número máximo de filas a devolver.
 * @param object $session Objeto que contiene información de la sesión del usuario:
 * @param object $session->usuario Objeto que contiene información del usuario en sesión.
 * @param int $rid ID único de la solicitud.
 * 
 * 
 * @return array $response Respuesta en formato JSON que incluye:
 * - code: Código de estado de la operación (0 si es exitosa).
 * - rid: ID único de la solicitud.
 * - data: Objeto que contiene:
 *   - notifications: Array de notificaciones generadas con los siguientes campos:
 *     - id: ID del historial del usuario.
 *     - transactionId: ID de la transacción asociada.
 *     - balanceIncrease: Booleano que indica si el balance aumenta.
 *     - icon: URL del ícono asociado a la operación.
 *     - timestamps: Marca de tiempo de la operación.
 *     - currency: Moneda del usuario.
 *     - amount: Monto de la operación.
 * 
 * @throws Exception Si ocurre un error durante el procesamiento de datos o consultas.
 */

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // $diff->w = floor($diff->d / 7);
    // $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    /* manipula y formatea datos de transacciones en formato JSON. */
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

//Recibiendo parámetros
$queriedTransactions = $json->params->where->transactions;
$queriedTypes = $json->params->where->types;

/* Inicializa variables para procesar movimientos y establecer reglas desde un JSON. */
$aceptedTypes = "";
$queriedMovements = $json->params->where->movements;
$aceptedMovements = "";
$SkeepRows = $json->params->where->start;
$MaxRows = $json->params->where->count;
$rules = array();


/* Se crea una nueva instancia de UsuarioMandante utilizando datos de un usuario en formato JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

/**Definiendo esquemas de relación de las transacciones -- cuando se solicitan notificaciones por tipo de operación, el front envía
 *  los transactionId correspondientes --La solicitud por tipo de operación no ha sido desarrollada*/
$transactions = [
    [
        "transactionId" => 1001,
        "operationName" => "bonusEntrance",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695765758.png',
        "operationType" => 50,
        "operationMovement" => "E"
    ],
    [
        "transactionId" => 1002,
        "operationName" => "bonusLeaving",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695765831.png',
        "operationType" => 50,
        "operationMovement" => "S"
    ],
    [
        "transactionId" => 2001,
        "operationName" => "withdrawalCreation",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695765977.png',
        "operationType" => 40,
        "operationMovement" => "S"
    ],
    [
        "transactionId" => 2003,
        "operationName" => "withdrawalDelete",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766151.png',
        "operationType" => 40,
        "operationMovement" => "E"
    ],
    [
        "transactionId" => 3001,
        "operationName" => "sportbookBet",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766200.png',
        "operationType" => 20,
        "operationMovement" => "S"
    ],
    [
        "transactionId" => 3002,
        "operationName" => "sportbookPay",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766305.png',
        "operationType" => 20,
        "operationMovement" => "E"
    ],
    [
        "transactionId" => 3003,
        "operationName" => "sportbookRollback",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766360.png',
        "operationType" => 20,
        "operationMovement" => "C"
    ],
    [
        "transactionId" => 4001,
        "operationName" => "casinoBet",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766423.png',
        "operationType" => 30,
        "operationMovement" => "S"
    ],
    [
        "transactionId" => 4002,
        "operationName" => "casinoPay",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766471.png',
        "operationType" => 30,
        "operationMovement" => "E"
    ],
    [
        "transactionId" => 4003,
        "operationName" => "casinoRollback",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766544.png',
        "operationType" => 30,
        "operationMovement" => "C"
    ],
    [
        "transactionId" => 5001,
        "operationName" => "depositPay",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766602.png',
        "operationType" => 10,
        "operationMovement" => "E"
    ],
    [
        "transactionId" => 5002,
        "operationName" => "depositDelete",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766751.png',
        "operationType" => 10,
        "operationMovement" => "S"
    ],
    [
        "transactionId" => 6001,
        "operationName" => "adjustmentEntrance",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695766823.png',
        "operationType" => 15,
        "operationMovement" => "S"
    ],
    [
        "transactionId" => 6002,
        "operationName" => "adjustmentLeaving",
        "icon" => 'https://images.virtualsoft.tech/m/msjT1695767300.png',
        "operationType" => 15,
        "operationMovement" => "S"
    ]
];

/**Definiendo esquemas de relación de los movimientos -- cuando se solicitan notificaciones por movimiento, el front envía los
 * movementId correspondientes o lo envía vacío para consultarlos todos */

/* Array que define tres tipos de movimientos: "E", "S" y "C". */
$movements = [
    [
        "movementId" => 0,
        "operationMovement" => "E"
    ],
    [
        "movementId" => 1,
        "operationMovement" => "S"
    ],
    [
        "movementId" => 2,
        "operationMovement" => "C"
    ]
];

/**Definiendo esquemas de relación de los movimientos -- cuando se solicitan notificaciones por tipos, el front envía los los
 * typeId correspondientes o lo envía vacío para consultarlos todos*/
$types = [
    [
        "typeId" => "A",
        "operationType" => 10
    ],
    [
        "typeId" => "B",
        "operationType" => 15
    ],
    [
        "typeId" => "C",
        "operationType" => 20
    ],
    [
        "typeId" => "D",
        "operationType" => 30
    ],
    [
        "typeId" => "E",
        "operationType" => 31
    ],
    [
        "typeId" => "F",
        "operationType" => 40
    ],
    [
        "typeId" => "H",
        "operationType" => 50
    ]
];

/** Definiendo types, movements y transactions para la consulta
 * Si queriedTransactions contiene un array >= 1 elemento, el resto de parámetros (Types y Movements) no serán tenidos en
 * cuenta para la consulta*/

/* Inicializa arreglos y llena queriedTypes si está vacío y queriedTransactions también. */
$queriedTransactions = is_array($queriedTransactions) ? $queriedTransactions : [];

$queriedTypes = is_array($queriedTypes) ? $queriedTypes : [];
if (count($queriedTypes) == 0 && count($queriedTransactions) == 0) {
    foreach ($types as $type) {
        array_push($queriedTypes, $type['typeId']);
    }
}


/* Inicializa `queriedMovements` y lo llena si está vacío y sin transacciones. */
$queriedMovements = is_array($queriedMovements) ? $queriedMovements : [];
if (count($queriedMovements) == 0 && count($queriedTransactions) == 0) {
    foreach ($movements as $movement) {
        array_push($queriedMovements, $movement['movementId']);
    }
}

/**Defiendo tipos y movimentos a consultar con base en queriedTransactions --NO se ha desarrollado */

/**Realizando conversión de los types entregados por front a los tipos utilizados por back*/

/* Concatena tipos de operación aceptados basados en un array de tipos consultados. */
if (count($queriedTypes) != 0) {
    for ($i = 0; $i < count($types); $i++) {
        if (in_array($types[$i]["typeId"], $queriedTypes) && $aceptedTypes != "") {
            $aceptedTypes .= ", " . $types[$i]['operationType'];
        } elseif (in_array($types[$i]["typeId"], $queriedTypes)) {
            $aceptedTypes .= $types[$i]['operationType'];
        }
    }
}


/**Realizando conversión de los movements entregados por front a los movimientos utilizados por back*/

/* acumula movimientos aceptados basándose en condiciones específicas de arrays. */
if (count($queriedMovements) != 0) {
    for ($i = 0; $i < count($movements); $i++) {
        if (in_array($movements[$i]["movementId"], $queriedMovements) && $aceptedMovements != "") {
            $aceptedMovements .= ", " . "'" . $movements[$i]['operationMovement'] . "'";
        } elseif (in_array($movements[$i]["movementId"], $queriedMovements)) {
            $aceptedMovements .= "'" . $movements[$i]['operationMovement'] . "'";
        }
    }
}

/**Armando consulta SQL*/

/* define reglas de filtrado para consultas sobre el historial de usuarios. */
$grouping = "";
$select = "usuario_historial.*,usuario.nombre";
array_push($rules, array("field" => "usuario_historial.tipo", "data" => $aceptedTypes, "op" => "in"));
array_push($rules, array("field" => "usuario_historial.movimiento", "data" => $aceptedMovements, "op" => "in"));
array_push($rules, array("field" => "usuario_historial.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
$filters = array("rules" => $rules, "groupOp" => "AND");

/**Ejecutando consulta SQL */

/* Se obtiene y decodifica el historial de usuarios, almacenándolo en una variable. */
$UsuarioHistorial = new UsuarioHistorial();
$data = $UsuarioHistorial->getUsuarioHistorialsCustom($select, "usuario_historial.usuhistorial_id", "desc", $SkeepRows, $MaxRows, json_encode($filters), true);
$movimientos = json_decode($data);
$notificaciones = array();

//Definiendo el contenido para notificación con base en la info recibida desde usuario_historial
foreach ($movimientos->data as $movimiento) {
    /**Identificando transaction correspondiente */

    /* Filtra transacciones según tipo y movimiento del historial del usuario. Verifica valor. */
    $result = array_filter($transactions, function ($item) use ($movimiento) {
        if ($item['operationType'] == $movimiento->{'usuario_historial.tipo'} && $item['operationMovement'] == $movimiento->{'usuario_historial.movimiento'}) return $item;
    });
    $key = key($result);

    /**Verificando valor válido*/
    $amount = $movimiento->{'usuario_historial.valor'};

    /* Verifica y formatea un monto, luego construye una notificación con los datos correspondientes. */
    $amount = strlen($amount) == 0 ? '-' : $amount;
    $pattern = '#[^\d\.]+|(.*\..*){2}#';
    $coincidences = [];
    preg_match($pattern, $amount, $coincidences);
    $amount = count($coincidences) > 0 ? '-' : (intval($amount * 100) / 100);

    /**Llenando notificación */
    $notificacion = [
        "id" => $movimiento->{'usuario_historial.usuhistorial_id'},
        "transactionId" => $result[$key]["transactionId"],
        "balanceIncrease" => $movimiento->{'usuario_historial.movimiento'} == 'S' ? false : true,
        "icon" => $result[$key]["icon"],
        "timestamps" => strtotime($movimiento->{'usuario_historial.fecha_crea'}),
        "currency" => $UsuarioMandante->getMoneda(),
        "amount" => $amount
    ];

    /* Agrega un elemento al final del arreglo de notificaciones en PHP. */
    array_push($notificaciones, $notificacion);
}


/* Crea una respuesta JSON con código, ID de solicitud y notificaciones. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array("notifications" => $notificaciones);