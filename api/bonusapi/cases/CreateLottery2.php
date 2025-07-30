<?php

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno2;
use Backend\mysql\SorteoDetalle2MySqlDAO;
use Backend\mysql\SorteoInterno2MySqlDAO;

/**
 * Crear un sorteo con las reglas y configuraciones especificadas.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Name Nombre del sorteo.
 * @param string $params->Description Descripción del sorteo.
 * @param string $params->BeginDate Fecha de inicio del sorteo.
 * @param string $params->EndDate Fecha de finalización del sorteo.
 * @param int $params->mandanteUsuario ID del mandante del usuario.
 * @param string $params->ConditionProduct Condición del producto (OR, AND, NA).
 * @param int $params->Priority Prioridad del sorteo.
 * @param int $params->cupo Cupo actual del sorteo.
 * @param int $params->cupoMaximo Cupo máximo permitido.
 * @param int $params->jugadores Número actual de jugadores.
 * @param int $params->jugadoresMaximo Número máximo de jugadores.
 * @param string $params->CodeGlobal Código global del sorteo.
 * @param int $params->MinplayersCount Número mínimo de jugadores.
 * @param string $params->BackgroundURL URL de la imagen de fondo.
 * @param string $params->MainImageURL URL de la imagen principal.
 * @param array $params->RanksPrize Premios por rango.
 * @param object $params->ForeignRule Reglas externas del sorteo.
 * @param object $params->TriggerDetails Detalles de activación del sorteo.
 * 
 *
 * @return array $response Respuesta de la operación:
 *  - idLottery (int): ID del sorteo creado.
 *  - HasError (boolean): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta (success, danger, etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado adicional.
 *
 * @throws Exception Si ocurre un error durante la creación del sorteo.
 */

/* Asigna valores de `$params` a variables para su uso posterior en el código. */
$Name = $params->Name;
$Description = $params->Description;
$StartDate = $params->BeginDate;
$EndDate = $params->EndDate;
$mandanteUsuario = $params->mandanteUsuario;
$ConditionProduct = $params->ConditionProduct;

/* asigna valores de parámetros a variables específicas en PHP. */
$Priority = $params->Priority;
$cupo = $params->cupo;
$cupoMaximo = $params->cupoMaximo;
$jugadores = $params->jugadores;
$jugadoresMaximo = $params->jugadoresMaximo;
$CodeGlobal = $params->CodeGlobal;

/* Asignación de parámetros relacionados con prioridades, conteo de jugadores y URLs en variables. */
$Priority = $params->Priority;
$MinplayersCount = $params->MinplayersCount;
$BackgroundURL = $params->BackgroundURL;
$MainImageURL = $params->MainImageURL;
$RanksPrize = $params->RanksPrize;


$ForeignRule = $params->ForeignRule;

/* Verifica si $ForeignRuleInfo es un objeto y lo convierte a JSON si no lo es. */
$ForeignRuleInfo = $ForeignRule->Info;


if (!is_object($ForeignRuleInfo)) {
    $ForeignRuleJSON = json_decode($ForeignRuleInfo);

} else {
    /* asigna $ForeignRuleInfo a $ForeignRuleJSON si la condición 'else' se cumple. */

    $ForeignRuleJSON = $ForeignRuleInfo;
}


/* Reemplaza caracteres en texto y asigna valores de un objeto JSON a variables. */
$RulesText = str_replace("#######", "'", $params->RulesText);
$RulesText = str_replace("'", "\'", $RulesText);

$SportBonusRules = $ForeignRuleJSON->SportBonusRules;
$LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
$MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones

/* Asignación de valores desde un objeto JSON a variables para configuración de apuestas. */
$MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
$MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;
$MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
$LinesByPoints = ($params->LinesByPoints == true || $params->LinesByPoints == "true") ? 1 : 0;


$ProductTypeId = $params->ProductTypeId;


/* Asignación de datos de JSON a variables en un sistema de apuestas deportivas. */
$SportsbookDeports2 = $ForeignRuleJSON->SportsbookDeports2;
$SportsbookMarkets2 = $ForeignRuleJSON->SportsbookMarkets2;
$SportsbookLeagues2 = $ForeignRuleJSON->SportsbookLeagues2;
$SportsbookMatches2 = $ForeignRuleJSON->SportsbookMatches2;
$PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

$TriggerDetails = $params->TriggerDetails;


/* asigna valores de $TriggerDetails a variables sobre ciudades y regiones. */
$Cities = $TriggerDetails->Cities;
$Regions = $TriggerDetails->Regions;
$CitiesUser = $TriggerDetails->CitiesUser;
$RegionsUser = $TriggerDetails->RegionsUser;


$DepartmentsUser = $TriggerDetails->DepartmentsUser;

/* obtiene datos de departamentos y define condiciones para suscripción de usuarios. */
$Departments = $TriggerDetails->Departments;
$UserSubscribe = $params->UserSubscribe;
$TypeProduct = ($params->TypeProduct == 0) ? 0 : 1;


$ConditionProduct = 'OR';


/* Evalúa la condición del producto; si no es válida, se establece como 'NA'. */
$ConditionProduct = $TriggerDetails->ConditionProduct;
if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
    $ConditionProduct = 'NA';
}

$EnableSportsbook = false;

/* desactiva funciones relacionadas con casino y depósitos en un sistema. */
$EnableCasino = false;
$EnableDeposit = false;

$EnableSportsbook2 = 0;
$EnableCasino2 = 0;
$EnableDeposit2 = 0;

try {

    /* Se crea un objeto "SorteoInterno2" con fechas y descripción específicas. */
    $sorteoInterno2 = new SorteoInterno2();

    $sorteoInterno2->setFechaInicio($StartDate);
    $sorteoInterno2->setFechaFin($EndDate);
    $sorteoInterno2->setdescription($Description);
    $sorteoInterno2->setTipo(1);

    /* establece propiedades de un objeto relacionado con un sorteo interno. */
    $sorteoInterno2->setName($Name);
    $sorteoInterno2->setState('A');
    $sorteoInterno2->setMandante($_SESSION['mandante']);
    $sorteoInterno2->setUsuCreaId($_SESSION['usuario']);
    $sorteoInterno2->setUsuModif(0);
    $sorteoInterno2->setCondicional($ConditionProduct);

    /* Se configura un objeto y se inserta en la base de datos. */
    $sorteoInterno2->setOrden($Priority);
    $sorteoInterno2->setRules($RulesText);
    $sorteoInterno2->setJsonTemp(json_encode($params));

    $sorteoInterno2MySqlDAO = new SorteoInterno2MySqlDAO();
    $sorteoId = $sorteoInterno2MySqlDAO->insert($sorteoInterno2);

    /* Commit de transacción seguido de verificación de moneda en un sorteo. */
    $sorteoInterno2MySqlDAO->getTransaction()->commit();


    $SorteoDetalleMySqlDAO = new SorteoDetalle2MySqlDAO();
    $transaccion = $SorteoDetalleMySqlDAO->getTransaction();


// $sorteoId = $SorteoInterno2->insert($transaccion);

    foreach ($MinBetPrice as $item) {
        if (!is_object($item->Amount)) {
            $moneda = $item->CurrencyId;
        }
    }

    foreach ($SportBonusRules as $key => $value) {

        /* Se crea un objeto 'SorteoDetalle2' y se le asignan propiedades específicas. */
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->setSorteoId($sorteoId);
        $SorteoDetalle2->setTipo('ITAINMENT' . $value->ObjectTypeId);
        $SorteoDetalle2->setMoneda($moneda);
        $SorteoDetalle2->setValor($value->ObjectId);
        $SorteoDetalle2->setDescription($value->Image);

        /* establece valores para un objeto y ajusta la fecha del sorteo. */
        $SorteoDetalle2->setValor2('');
        $SorteoDetalle2->setValor3('');
        $SorteoDetalle2->setUsucreaId(0);
        $SorteoDetalle2->setUsumodifId(0);
        if ($value->fixedTime == "" || $value->fixedTime == null) {
            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $EndDate)));
            $SorteoDetalle2->setFechaSorteo($fixedTime);
        } else {
            /* convierte una fecha en formato ISO a un formato legible. */

            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $value->fixedTime)));
            $SorteoDetalle2->setFechaSorteo($fixedTime);
        }


        /* Se establece una URL de imagen y se inserta en la base de datos. */
        $SorteoDetalle2->setImageUrl($value->Image);

        $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $sorteoDetalle2MySqlDAO->insert($SorteoDetalle2);

    }


    if ($SportsbookDeports2 != '') {

        /* divide una cadena en un array usando coma como separador. */
        $SportsbookDeports2 = explode(',', $SportsbookDeports2);


        foreach ($SportsbookDeports2 as $key => $value) {

            /* Código que configura un objeto SorteoDetalle2 con atributos específicos del sorteo. */
            $SorteoDetalle2 = new SorteoDetalle2();
            $SorteoDetalle2->setSorteoId($sorteoId);
            $SorteoDetalle2->setTipo("ITAINMENT" . '1');
            $SorteoDetalle2->setMoneda('');
            $SorteoDetalle2->setValor($value->ObjectId);
            $SorteoDetalle2->setDescription($value->Image);

            /* Se asignan valores a propiedades de un objeto y se establece una fecha. */
            $SorteoDetalle2->setValor2('');
            $SorteoDetalle2->setValor3('');
            $SorteoDetalle2->setUsucreaId(0);
            $SorteoDetalle2->setUsumodifId(0);
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $EndDate)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            } else {
                /* Convierte una fecha en formato específico y la asigna a un objeto. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $value->fixedTime)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            }


            /* Se establece una URL de imagen y se inserta en la base de datos. */
            $SorteoDetalle2->setImageUrl($value->Image);
            $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
            $sorteoDetalle2MySqlDAO->insert($SorteoDetalle2);
        }
    }


    if ($SportsbookLeagues2 != '') {

        /* Separa una cadena en elementos individuales usando la coma como delimitador. */
        $SportsbookLeagues2 = explode(',', $SportsbookLeagues2);

        foreach ($SportsbookLeagues2 as $key => $value) {

            /* Crea e inicializa un objeto SorteoDetalle2 con varios atributos personalizados. */
            $SorteoDetalle2 = new SorteoDetalle2();
            $SorteoDetalle2->setSorteoId($SorteoId);
            $SorteoDetalle2->setTipo("ITAINMENT" . '3');
            $SorteoDetalle2->setMoneda('');
            $SorteoDetalle2->setValor($value->ObjectId);
            $SorteoDetalle2->setDescription($value->Image);

            /* Configura propiedades de objeto y establece fecha de sorteo si está vacía. */
            $SorteoDetalle2->setValor2('');
            $SorteoDetalle2->setValor3('');
            $SorteoDetalle2->setUsucreaId(0);
            $SorteoDetalle2->setUsumodifId(0);
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $EndDate)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            } else {
                /* Convierte tiempo de formato T a fecha y lo asigna a SorteoDetalle2. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $value->fixedTime)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            }


            /* Se establece la URL de la imagen en el objeto SorteoDetalle2. */
            $SorteoDetalle2->setImageUrl($value->Image);

        }

    }


    if ($SportsbookMatches2 != '') {

        /* divide una cadena en un arreglo utilizando comas como delimitadores. */
        $SportsbookMatches2 = explode(',', $SportsbookMatches2);

        foreach ($SportsbookMatches2 as $key => $value) {

            /* Código que inicializa un objeto y establece sus propiedades relacionadas con un sorteo. */
            $SorteoDetalle2 = new SorteoDetalle2();
            $SorteoDetalle2->setSorteoId($SorteoId);
            $SorteoDetalle2->setTipo("ITAINMENT" . '4');
            $SorteoDetalle2->setMoneda('');
            $SorteoDetalle2->setValor($value->ObjectId);
            $SorteoDetalle2->setDescription($value->Image);

            /* configura propiedades de un objeto y establece una fecha si está vacía. */
            $SorteoDetalle2->setValor2('');
            $SorteoDetalle2->setValor3('');
            $SorteoDetalle2->setUsucreaId(0);
            $SorteoDetalle2->setUsumodifId(0);
            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $EndDate)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            } else {
                /* Convierte una fecha en formato ISO a un formato legible y la asigna. */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $value->fixedTime)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            }


            /* Se crea un objeto DAO y se inserta un registro en la base de datos. */
            $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
            $sorteoDetalle2MySqlDAO->insert($SorteoDetalle2);

        }

    }


    if ($SportsbookMarkets2 != '') {

        /* Separa una cadena en elementos de un array utilizando la coma como delimitador. */
        $SportsbookMarkets2 = explode(',', $SportsbookMarkets2);

        foreach ($SportsbookMarkets2 as $key => $value) {

            /* Crea una instancia de SorteoDetalle2 y establece varias propiedades. */
            $SorteoDetalle2 = new SorteoDetalle2();
            $SorteoDetalle2->setSorteoId($sorteoId);
            $SorteoDetalle2->setTipo("ITAINMENT" . '5');
            $SorteoDetalle2->setMoneda('');
            $SorteoDetalle2->setValor($value->ObjectId);
            $SorteoDetalle2->setDescription($value->Image);

            /* Se inicializan variables y se establece la fecha de sorteo si está vacía. */
            $SorteoDetalle2->setValor2('');
            $SorteoDetalle2->setValor3('');
            $SorteoDetalle2->setUsucreaId(0);
            $SorteoDetalle2->setUsumodifId(0);

            if ($value->fixedTime == "" || $value->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $EndDate)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            } else {
                /* Convierte la fecha "fixedTime" a formato legible y la asigna a "SorteoDetalle2". */

                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $value->fixedTime)));
                $SorteoDetalle2->setFechaSorteo($fixedTime);
            }


            /* establece una URL de imagen y guarda detalles en una base de datos. */
            $SorteoDetalle2->setImageUrl($value->Image);

            $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
            $sorteoDetalle2MySqlDAO->insert($SorteoDetalle2);
        }

    }


    /* Convierte una fecha y crea un objeto SorteoDetalle2 con atributos específicos. */
    $fecha = date('Y-m-d H:i:s', strtotime(str_replace('T', '', $EndDate)));

    $SorteoDetalle2 = new SorteoDetalle2();
    $SorteoDetalle2->setSorteoId($sorteoId);
    $SorteoDetalle2->tipo = "VISIBILIDAD";
    $SorteoDetalle2->moneda = '';

    /* Se asignan valores a propiedades de un objeto y se establece una fecha. */
    $SorteoDetalle2->valor = $TypeRule;
    $SorteoDetalle2->valor2 = '';
    $SorteoDetalle2->valor3 = '';
    $SorteoDetalle2->usucreaId = 0;
    $SorteoDetalle2->usumodifId = 0;
    $SorteoDetalle2->setFechaSorteo($fecha);

    /* Se inserta un nuevo detalle de sorteo en la base de datos MySQL. */
    $sorteoDetalle2MySqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
    $sorteoDetalle2MySqlDAO->insert($SorteoDetalle2);


    $SorteoDetalle2 = new SorteoDetalle2();
    $SorteoDetalle2->sorteo2Id = $sorteoId;

    /* asigna valores a un objeto para un sorteo de suscripción de usuarios. */
    $SorteoDetalle2->tipo = "USERSUBSCRIBE";
    $SorteoDetalle2->moneda = '';
    $SorteoDetalle2->valor = $UserSubscribe;
    $SorteoDetalle2->valor2 = '';
    $SorteoDetalle2->valor3 = '';
    $SorteoDetalle2->usucreaId = 0;

    /* inserta un nuevo detalle de sorteo en la base de datos. */
    $SorteoDetalle2->usumodifId = 0;
    $SorteoDetalle2->setFechaSorteo($fecha);
    $SorteoDetalle2MysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
    $SorteoDetalle2MysqlDAO->insert($SorteoDetalle2);


    $SorteoDetalle = new SorteoDetalle2();

    /* Asignación de valores a un objeto SorteoDetalle en PHP. */
    $SorteoDetalle->sorteo2Id = $sorteoId;
    $SorteoDetalle->tipo = "TIPOPRODUCTO";
    $SorteoDetalle->moneda = '';
    $SorteoDetalle->valor = $TypeProduct;
    $SorteoDetalle->valor2 = '';
    $SorteoDetalle->valor3 = '';

    /* Se inserta un registro de sorteo y opcionalmente otro para eventos en vivo. */
    $SorteoDetalle->usucreaId = 0;
    $SorteoDetalle->usumodifId = 0;
    $SorteoDetalle->setFechaSorteo($fecha);
    $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
    $SorteoDetalleMysqlDAO->insert($SorteoDetalle);


    if ($LiveOrPreMatch != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "LIVEORPREMATCH";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $LiveOrPreMatch;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalle2MysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalle2MysqlDAO->insert($SorteoDetalle);
    }


    /* inserta un registro en la base de datos si $MinSelCount no está vacío. */
    if ($MinSelCount != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "MINSELCOUNT";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $MinSelCount;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* Inserta un nuevo registro en SorteoDetalle2 si MinSelPrice no está vacío. */
    if ($MinSelPrice != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "MINSELPRICE";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $MinSelPrice;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* Inserta un nuevo registro en la base de datos si $MinSelPriceTotal no está vacío. */
    if ($MinSelPriceTotal != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "MINSELPRICETOTAL";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $MinSelPriceTotal;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    if ($MinBetPrice != "") {

        /* inserta detalles de sorteos basados en precios mínimos de apuestas. */
        foreach ($MinBetPrice as $item) {
            if (!is_object($item->Amount)) {

                $SorteoDetalle2 = new SorteoDetalle2();
                $SorteoDetalle2->sorteo2Id = $sorteoId;
                $SorteoDetalle2->tipo = "MINBETPRICE";
                $SorteoDetalle2->moneda = $item->CurrencyId;
                $SorteoDetalle2->valor = $item->Amount;
                $SorteoDetalle2->valor2 = '';
                $SorteoDetalle2->valor3 = '';
                $SorteoDetalle2->usucreaId = 0;
                $SorteoDetalle2->usumodifId = 0;
                $SorteoDetalle2->setFechaSorteo($fecha);
                $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
            }


        }
    }

    foreach ($RanksPrize as $key => $value) {


        foreach ($value->Amount as $key2 => $value2) {


            /* Asignación de tipo y descripción según condiciones de un objeto $value2. */
            $tipo = "RANKAWARD";
            if ($value2->type == 0) {
                $tipo = "RANKAWARDMAT";
                $description = $value2->description;
            } elseif ($value2->type == 2) {
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
                /* Asigna descripciones basadas en el valor de $value2->description. */

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


            /* Se crea una instancia y se configuran propiedades relacionadas con un sorteo. */
            $SorteoDetalle2 = new SorteoDetalle2();
            $SorteoDetalle2->sorteo2Id = $sorteoId;
            $SorteoDetalle2->tipo = $tipo;
            $SorteoDetalle2->moneda = $value->CurrencyId;
            $SorteoDetalle2->valor = $value2->position;
            $SorteoDetalle2->valor2 = $idBono;

            /* Asignación de valores a propiedades de un objeto SorteoDetalle2 en PHP. */
            $SorteoDetalle2->valor3 = $value2->amount;
            $SorteoDetalle2->descripcion = $description;
            $SorteoDetalle2->usucreaId = 0;
            $SorteoDetalle2->usumodifId = 0;
            $SorteoDetalle2->estado = 'A';

                    $SorteoDetalle2->permiteGanador =  $value2->winningCoupons;
                    $SorteoDetalle2->jugadorExcluido =  $value2->winningPlayers;





            /* asigna una fecha formateada a "fechaSorteo" dependiendo de la validez de "fixedTime". */
            if ($value2->fixedTime == "" || $value2->fixedTime == null) {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
                $SorteoDetalle2->fechaSorteo = $fixedTime;
            } else {
                $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value2->fixedTime)));
                $SorteoDetalle2->fechaSorteo = $fixedTime;
            }


            /* Inserta un objeto de detalle de sorteo en la base de datos usando DAO. */
            $SorteoDetalle2->imagenUrl = $value2->amount;
            $SorteoDetalleMySqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
            $id = $SorteoDetalleMySqlDAO->insert($SorteoDetalle2);

        }
    }


    if ($MinBetPriceSportsbook != "" && $EnableSportsbook == true) {
        foreach ($MinBetPriceSportsbook as $item) {


            /* verifica si 'Amount' no es un objeto y crea un registro. */
            if (!is_object($item->Amount)) {

                $SorteoDetalle2 = new SorteoDetalle2();
                $SorteoDetalle2->sorteo2Id = $sorteoId;
                $SorteoDetalle2->tipo = "MINBETPRICESPORTSBOOK";
                $SorteoDetalle2->moneda = $item->CurrencyId;
                $SorteoDetalle2->valor = $item->Amount;
                $SorteoDetalle2->valor2 = '';
                $SorteoDetalle2->valor3 = '';
                $SorteoDetalle2->usucreaId = 0;
                $SorteoDetalle2->usumodifId = 0;
                $SorteoDetalle2->setFechaSorteo($fecha);
                $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
            }


        }
    }


    if ($MinBetPrice2Sportsbook != "" && $EnableSportsbook == true) {

        /* Procesa una lista, creando y guardando objetos SorteoDetalle2 para entradas válidas. */
        foreach ($MinBetPrice2Sportsbook as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle2 = new SorteoDetalle2();
                $SorteoDetalle2->sorteo2Id = $sorteoId;
                $SorteoDetalle2->tipo = "MINBETPRICE2SPORTSBOOK";
                $SorteoDetalle2->moneda = $item->CurrencyId;
                $SorteoDetalle2->valor = $item->Amount;
                $SorteoDetalle2->valor2 = '';
                $SorteoDetalle2->valor3 = '';
                $SorteoDetalle2->usucreaId = 0;
                $SorteoDetalle2->usumodifId = 0;
                $SorteoDetalle2->setFechaSorteo($fecha);
                $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
            }

        }
    }


    if ($NumberSportsbookStickers != "" && $EnableSportsbook == true) {

        /* Recorre elementos y crea registros de SorteoDetalle2 según condiciones específicas. */
        foreach ($NumberSportsbookStickers as $item) {

            if (!is_object($item->Amount)) {

                $SorteoDetalle2 = new SorteoDetalle2();
                $SorteoDetalle2->sorteo2Id = $sorteoId;
                $SorteoDetalle2->tipo = "NUMBERSPORTSBOOKSTICKERS";
                $SorteoDetalle2->moneda = $item->CurrencyId;
                $SorteoDetalle2->valor = $item->Amount;
                $SorteoDetalle2->valor2 = '';
                $SorteoDetalle2->valor3 = '';
                $SorteoDetalle2->usucreaId = 0;
                $SorteoDetalle2->usumodifId = 0;
                $SorteoDetalle2->setFechaSorteo($fecha);
                $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
                $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
            }


        }
    }

    foreach ($PrizeImageURL as $key => $value) {

        /* Crea un objeto SorteoDetalle2 con propiedades de sorteo, tipo, moneda y valor. */
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "PREMIOIMAGEN";
        $SorteoDetalle2->moneda = $value->CurrencyId;
        $SorteoDetalle2->valor = $value->Amount;
        $SorteoDetalle2->valor2 = '';

        /* Se asignan valores a propiedades de un objeto y se establece una fecha formateada. */
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        if ($value->fixedTime == "" || $value->fixedTime == null) {
            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate)));
            $SorteoDetalle2->fechaSorteo = $fixedTime;
        } else {
            /* Convierte una fecha en formato ISO a un formato estándar y la asigna a una variable. */

            $fixedTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->fixedTime)));
            $SorteoDetalle2->fechaSorteo = $fixedTime;
        }

        /* Asigna una imagen y guarda el detalle del sorteo en MySQL. */
        $SorteoDetalle->imagenUrl = $value->Image;
        $SorteoDetalle2MysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalle2MysqlDAO->insert($SorteoDetalle2);
    }


    /* Crea un objeto SorteoDetalle2 y lo inserta en la base de datos si BackgroundURL no está vacío. */
    if ($BackgroundURL != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "BACKGROUNDURL";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $BackgroundURL;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalle2->imagenUrl = $BackgroundURL;
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* inserta un objeto SorteoDetalle2 si MainImageURL no está vacío. */
    if ($MainImageURL != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "IMGPPALURL";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $MainImageURL;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalle2->setImageUrl($MainImageURL);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* Inserta un nuevo registro en la base de datos si $ProductTypeId no está vacío. */
    if ($ProductTypeId !== "") {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "TIPOPRODUCTO";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $ProductTypeId;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* Crea un objeto SorteoDetalle2 y lo inserta en la base de datos. */
    if ($LinesByPoints == 1) {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "MULTLINEAS";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $LinesByPoints;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* inserta un registro en la base de datos si $MaxplayersCount no está vacío. */
    if ($MaxplayersCount !== "") {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "MAXPLAYERSCOUNT";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $MaxplayersCount;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* Crea un objeto SorteoDetalle2 y lo inserta en la base de datos si hay jugadores. */
    if ($MinplayersCount !== "") {

        $SorteoDetalle = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "MINPLAYERSCOUNT";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $MinplayersCount;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);
    }


    /* Crea un registro de repetición de sorteo si hay un bono de usuario. */
    if ($UserRepeatBonus != "") {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "REPETIRSORTEO";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $UserRepeatBonus;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Inserta un registro en SorteoDetalle2 si UserSubscribeRepeat no está vacío. */
    if ($UserSubscribeRepeat != "") {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "REPETIRSORTEO";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $UserSubscribeRepeat;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Insertar detalles del sorteo basados en múltiples identificadores de sistema de pago. */
    foreach ($PaymentSystemIds as $key => $value) {
        $SorteoDetalle = new SorteoDetalle2();
        $SorteoDetalle->sorteo2Id = $sorteoId;

        if ($value == "ALL" && $EnableDeposit2 == 1) {
            $SorteoDetalle->tipo = "CONDPAYMENT" . $value->Id;
        } else {
            $SorteoDetalle->tipo = "CONDPAYMENT" . $value->Id;
        }

        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalle2MysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalle2MysqlDAO->insert($SorteoDetalle2);
    }


    /* Itera sobre regiones, creando y guardando objetos SorteoDetalle2 en la base de datos. */
    foreach ($Regions as $key => $value) {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "CONDPAISPV";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* Inserta detalles del sorteo para cada departamento en la base de datos. */
    foreach ($Departments as $key => $value) {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "CONDDEPARTAMENTOPV";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);

    }


    /* inserta datos de ciudades en la base de datos mediante un bucle. */
    foreach ($Cities as $key => $value) {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "CONDCIUDADPV";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle);
    }


    /* inserta detalles de sorteos en la base de datos para diferentes regiones. */
    foreach ($RegionsUser as $key => $value) {
        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "CONDPAISUSER";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);

    }


    /* Inserta detalles de sorteos para cada departamento del usuario en la base de datos. */
    foreach ($DepartmentsUser as $key => $value) {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "CONDDEPARTAMENTOUSER";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);

    }


    /* Itera sobre ciudades, creando e insertando registros "SorteoDetalle2" en la base de datos. */
    foreach ($CitiesUser as $key => $value) {

        $SorteoDetalle2 = new SorteoDetalle2();
        $SorteoDetalle2->sorteo2Id = $sorteoId;
        $SorteoDetalle2->tipo = "CONDCIUDADUSER";
        $SorteoDetalle2->moneda = '';
        $SorteoDetalle2->valor = $value;
        $SorteoDetalle2->valor2 = '';
        $SorteoDetalle2->valor3 = '';
        $SorteoDetalle2->usucreaId = 0;
        $SorteoDetalle2->usumodifId = 0;
        $SorteoDetalle2->setFechaSorteo($fecha);
        $SorteoDetalleMysqlDAO = new SorteoDetalle2MySqlDAO($transaccion);
        $SorteoDetalleMysqlDAO->insert($SorteoDetalle2);

    }


    /* Genera una respuesta estructurada con información sobre un sorteo y sin errores. */
    $response["idLottery"] = $sorteoId;

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* Código que inicializa un arreglo vacío y confirma una transacción en una base de datos. */
    $response["Result"] = array();

    $transaccion->commit();
} catch (\Exception $e) {
    /* Manejo de excepciones: registra errores y prepara respuesta estructurada para el frontend. */


    $response["idLottery"] = $sorteoId;

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}


?>