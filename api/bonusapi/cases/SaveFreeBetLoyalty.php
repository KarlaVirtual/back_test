<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


use Backend\dto\LealtadDetalle;
use Backend\dto\LealtadInterna;
use Backend\dto\UsuarioLealtad;
use Backend\mysql\LealtadDetalleMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;


/* Asignación de mandanteUsuario según la condición de sesión del usuario. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}


/**
 * Guarda la configuración de lealtad para apuestas gratuitas en la base de datos.
 *
 * @param object $params Objeto que contiene:
 * @param string $params->Description Descripción de la lealtad.
 * @param string $params->Name Nombre de la lealtad.
 * @param object $params->PartnerLealtad Detalles del socio de la lealtad:
 * @param string $params->PartnerLealtad->StartDate Fecha de inicio.
 * @param string $params->PartnerLealtad->EndDate Fecha de fin.
 * @param int $params->PartnerLealtad->ExpirationDays Días de expiración.
 * @param array $params->PartnerLealtad->LealtadDetails Detalles adicionales de la lealtad.
 * @param string $params->LiveOrPreMatch Tipo de apuesta (en vivo o pre-partido).
 * @param int $params->MinSelCount Mínimo de selecciones.
 * @param float $params->MinSelPrice Precio mínimo por selección.
 * @param float $params->MinSelPriceTotal Precio mínimo total.
 * @param string $params->TriggerId Identificador del disparador.
 * @param string $params->CodePromo Código promocional.
 * @param int $params->MaxplayersCount Máximo de jugadores.
 * @param string $params->Prefix Prefijo para códigos.
 * @param string $params->PlayersChosen Jugadores seleccionados.
 * @param int $params->ProductTypeId Identificador del tipo de producto.
 * @param array $params->Games Juegos asociados.
 * @param bool $params->UserRepeatLealtad Indica si el usuario puede repetir la lealtad.
 * @param array $params->SportLealtadRules Reglas deportivas asociadas.
 * @param object $params->TriggerDetails Detalles del disparador:
 * @param string $params->TriggerDetails->ConditionProduct Condición del producto.
 * @param object $params->Casino Información del casino:
 * @param array $params->Casino->Product Productos del casino.
 * @param string $params->TypeLealtadDeposit Tipo de depósito de lealtad.
 * 
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success o danger).
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Result Resultado de la operación.
 *
 * @throws Exception Si ocurre un error al insertar o actualizar registros en la base de datos.
 */

try {


    /* Asignación de variables desde un objeto $params para gestión de lealtad. */
    $Description = $params->Description; //Descripcion del lealtad
    $Name = $params->Name; //Nombre del lealtad

    $PartnerLealtad = $params->PartnerLealtad;

    $StartDate = $PartnerLealtad->StartDate; //Fecha Inicial de la campaña

    /* Asignación de variables para manejar fechas y parámetros de una campaña de lealtad. */
    $EndDate = $PartnerLealtad->EndDate; //Fecha Final de la campaña

    $ExpirationDays = $PartnerLealtad->ExpirationDays; // Dias de expiracion del lealtad

    $LiveOrPreMatch = $params->LiveOrPreMatch;
    $MinSelCount = $params->MinSelCount;

    /* Asignación de parámetros y valores relacionados con precios y apuestas en una configuración. */
    $MinSelPrice = $params->MinSelPrice;
    $MinSelPriceTotal = $params->MinSelPriceTotal;

    $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total

    $TriggerId = $params->TriggerId;

    /* Asignación de parámetros: código promocional, máximo de jugadores, prefijo y jugadores seleccionados. */
    $CodePromo = $params->CodePromo;


    $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
    $Prefix = $params->Prefix;

    $PlayersChosen = $params->PlayersChosen;

    /* asigna valores de parámetros a variables para su uso posterior. */
    $ProductTypeId = $params->ProductTypeId;


    $Games = $params->Games;

    $condiciones = [];


    /* Se asignan parámetros y detalles de lealtad a variables para su uso posterior. */
    $UserRepeatLealtad = $params->UserRepeatLealtad;
    $SportLealtadRules = $params->SportLealtadRules;

    $LealtadDetails = $PartnerLealtad->LealtadDetails;

    $TriggerDetails = $params->TriggerDetails;


    /* Asigna información de casino y tipo de lealtad a variables en el código. */
    $Casino = $params->Casino->Info;
    $CasinoProduct = $Casino->Product;

    $TypeLealtadDeposit = $params->TypeLealtadDeposit;


    $ConditionProduct = $TriggerDetails->ConditionProduct;

    /* verifica condiciones y asigna 'NA' si son diferentes de "OR" o "AND". */
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }


    $tipolealtad = 6;

    /* Inicializa variables para manejar puntos y cupos en un sistema. */
    $Points = $params->Points;

    $points = 0;

    $cupo = 0;
    $cupoMaximo = 0;

    /* inicializa variables y verifica condiciones para establecer un cupo máximo. */
    $jugadores = 0;
    $jugadoresMaximo = 0;

    if ($MaximumAmount != "" && $tipolealtad == 2) {
        $cupoMaximo = $MaximumAmount[0]->Amount;
    }


    /* Condicional para asignar jugadores máximos según tipo de lealtad y usuario de sesión. */
    if ($MaxplayersCount != "" && $tipolealtad == 2) {
        $jugadoresMaximo = $MaxplayersCount;
    }

    $usucrea_id = $_SESSION["usuario"];
    $usumodif_id = $_SESSION["usuario"];


    /* Crea una instancia de LealtadInterna y asigna valores a sus propiedades. */
    $LealtadInterna = new LealtadInterna();
    $LealtadInterna->nombre = $Name;
    $LealtadInterna->descripcion = $Description;
    $LealtadInterna->fechaInicio = $StartDate;
    $LealtadInterna->fechaFin = $EndDate;
    $LealtadInterna->tipo = $tipolealtad;

    /* Asignación de propiedades a un objeto LealtadInterna, configurando estado y valores relacionados. */
    $LealtadInterna->estado = 'A';
    $LealtadInterna->usucreaId = 0;
    $LealtadInterna->usumodifId = 0;
    $LealtadInterna->mandante = $mandanteUsuario;
    $LealtadInterna->condicional = $ConditionProduct;
    $LealtadInterna->puntos = $points;

    /* asigna valores a atributos de un objeto y crea un DAO. */
    $LealtadInterna->cupoActual = $cupo;
    $LealtadInterna->cupoMaximo = $cupoMaximo;
    $LealtadInterna->cantidadLealtad = $jugadores;
    $LealtadInterna->maximoLealtad = $jugadoresMaximo;


    $LealtadDetalleMySqlDAO = new LealtadDetalleMySqlDAO();

    /* obtiene una transacción y la inserta en la base de datos. */
    $transaccion = $LealtadDetalleMySqlDAO->getTransaction();

    $lealtadId = $LealtadInterna->insert($transaccion);


    if ($MaxplayersCount != "" && $Prefix != "") {


        /* asigna jugadores elegidos a un array con valor inicial 0. */
        $jugadoresAsignar = array();
        $jugadoresAsignarFinal = array();

        if ($PlayersChosen != "") {
            $jugadoresAsignar = explode(",", $PlayersChosen);

            foreach ($jugadoresAsignar as $item) {

                array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => 0));

            }
        }


        /* Se inicializa un arreglo vacío llamado "codigosarray" en PHP. */
        $codigosarray = array();

        for ($i = 1; $i <= $MaxplayersCount; $i++) {

            /* Genera un código único asegurándose de que no se repita en un array. */
            $codigo = GenerarClaveTicket(4);

            while (in_array($codigo, $codigosarray)) {
                $codigo = GenerarClaveTicket(4);
            }

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];

                $valor = $jugadoresAsignarFinal[$i]["Valor"];

                $valor_lealtad = $jugadoresAsignarFinal[$i]["Valor"];

                $valor_base = $jugadoresAsignarFinal[$i]["Valor"];


            } else {
                /* asigna valores predeterminados a variables si no se cumplen ciertas condiciones. */

                $usuarioId = '0';
                $valor = '0';

                $valor_lealtad = '0';

                $valor_base = '0';

            }


            /* Variables inicializadas en PHP: estado, errorId, idExterno y mandante. */
            $estado = 'L';

            $errorId = '0';

            $idExterno = '0';

            $mandante = '0';


            /* Variables inicializadas en PHP, representando IDs y un valor apostado. */
            $usucreaId = '0';

            $usumodifId = '0';


            $apostado = '0';

            /* Código para configurar un usuario de lealtad con un código específico. */
            $rollowerRequerido = '0';
            $codigo = $Prefix . $codigo;

            $UsuarioLealtad = new UsuarioLealtad();

            $UsuarioLealtad->setUsuarioId($usuarioId);

            /* establece propiedades de un objeto UsuarioLealtad. */
            $UsuarioLealtad->setLealtadId($lealtadId);
            $UsuarioLealtad->setValor($valor);
            $UsuarioLealtad->setValorLealtad($valor_lealtad);
            $UsuarioLealtad->setValorBase($valor_base);
            $UsuarioLealtad->setEstado($estado);
            $UsuarioLealtad->setErrorId($errorId);

            /* Código que configura atributos de un objeto UsuarioLealtad con valores específicos. */
            $UsuarioLealtad->setIdExterno($idExterno);
            $UsuarioLealtad->setMandante($mandante);
            $UsuarioLealtad->setUsucreaId($usucreaId);
            $UsuarioLealtad->setUsumodifId($usumodifId);
            $UsuarioLealtad->setApostado($apostado);
            $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);

            /* configura un objeto y lo inserta en la base de datos. */
            $UsuarioLealtad->setCodigo($codigo);
            $UsuarioLealtad->setVersion(0);
            $UsuarioLealtad->setExternoId($idExterno);

            $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($transaccion);

            $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);


            /* Añade el elemento $codigo al final del array $codigosarray en PHP. */
            array_push($codigosarray, $codigo);

        }
    }


    //Expiracion


    /* Inserta un nuevo detalle de lealtad si existe una cantidad de días de expiración. */
    if ($ExpirationDays != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "EXPDIA";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $ExpirationDays;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Se inserta un registro de lealtad si el usuario repite la lealtad. */
    if ($UserRepeatLealtad != "" && ($UserRepeatLealtad == "true" || $UserRepeatLealtad == true)) {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "REPETIRBONO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $UserRepeatLealtad;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta un nuevo registro de LealtadDetalle si el prefijo no está vacío. */
    if ($Prefix != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "PREFIX";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $Prefix;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta un nuevo detalle de lealtad si se utiliza una billetera congelada. */
    if ($UseFrozeWallet != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "FROZEWALLET";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $UseFrozeWallet;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un nuevo detalle de lealtad si se proporciona un valor de supresión. */
    if ($SuppressWithdrawal != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "SUPPRESSWITHDRAWAL";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $SuppressWithdrawal;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta un registro de detalle de lealtad si ScheduleCount no está vacío. */
    if ($ScheduleCount != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "SCHEDULECOUNT";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $ScheduleCount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Se inserta un registro de lealtad si $ScheduleName no está vacío. */
    if ($ScheduleName != "") {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "SCHEDULENAME";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $ScheduleName;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta un nuevo registro de lealtad si $SchedulePeriod no está vacío. */
    if ($SchedulePeriod != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "SCHEDULEPERIOD";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $SchedulePeriod;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta un nuevo registro en la base de datos si SchedulePeriodType no está vacío. */
    if ($SchedulePeriodType != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "SCHEDULEPERIODTYPE";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $SchedulePeriodType;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Añade un detalle de lealtad si $ProductTypeId no está vacío. */
    if ($ProductTypeId !== "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "TIPOPRODUCTO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $ProductTypeId;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }

    /*if ($Count != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CANTDEPOSITOS";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $Count;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }*/


    /* Crea e inserta un objeto LealtadDetalle si $AreAllowed no está vacío. */
    if ($AreAllowed != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "PAISESPERMITIDOS";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $AreAllowed;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un registro de lealtad si la fecha de expiración no está vacía. */
    if ($ExpirationDate != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "EXPFECHA";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $ExpirationDate;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un detalle de lealtad basado en porcentaje si se cumple la condición. */
    if ($TypeLealtadDeposit == '1') {

        if ($LealtadPercent != "") {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "PORCENTAJE";
            $LealtadDetalle->moneda = '';
            $LealtadDetalle->valor = $LealtadPercent;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


        }
    }

    /*if ($LealtadWFactor != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "WFACTORBONO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $LealtadWFactor;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }

    if ($DepositWFactor != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "WFACTORDEPOSITO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $DepositWFactor;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    if ($DepositNumber != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "NUMERODEPOSITO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $DepositNumber;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }

    if ($IsFromCashDesk) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDEFECTIVO";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = 'true';
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }*/


    /* Inserta un nuevo detalle de lealtad si hay un conteo de jugadores máximo. */
    if ($MaxplayersCount != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MAXJUGADORES";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $MaxplayersCount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta detalles de lealtad en la base de datos desde una colección de máximos pagos. */
    foreach ($MaxPayout as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MAXPAGO";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->Amount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }

    /* Inserta detalles de lealtad en la base de datos si hay puntos disponibles. */
    if ($Points != 0) {

        foreach ($Points as $key => $value) {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "PUNTOS";
            $LealtadDetalle->moneda = $value->CurrencyId;
            $LealtadDetalle->valor = $value->Amount;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
        }


    }
    /*foreach ($MaximumDeposit as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MAXDEPOSITO";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->Amount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }

    foreach ($MinimumDeposit as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MINDEPOSITO";
        $LealtadDetalle->moneda = $value->CurrencyId;
        $LealtadDetalle->valor = $value->Amount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }*/


    /* Inserta detalles de lealtad en la base de datos según requisitos monetarios. */
    if ($TypeLealtadDeposit == '0') {

        foreach ($MoneyRequirement as $key => $value) {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "VALORBONO";
            $LealtadDetalle->moneda = $value->CurrencyId;
            $LealtadDetalle->valor = $value->Amount;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


        }

    }

    /*foreach ($PaymentSystemIds as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDPAYMENT";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }*/


    /* Itera sobre regiones, creando y guardando detalles de lealtad en la base de datos. */
    foreach ($Regions as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDPAISPV";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta detalles de lealtad en una base de datos para cada departamento. */
    foreach ($Departments as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDDEPARTAMENTOPV";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta detalles de lealtad en la base de datos para cada ciudad proporcionada. */
    foreach ($Cities as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDCIUDADPV";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* inserta un registro de lealtad si $BalanceZero es verdadero. */
    if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDBALANCE";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = '0';
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);
    }


    /* Inserta detalles de lealtad en una base de datos para cada región de usuario. */
    foreach ($RegionsUser as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDPAISUSER";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    /* Inserta detalles de lealtad para cada departamento de usuario en la base de datos. */
    foreach ($DepartmentsUser as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDDEPARTAMENTOUSER";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta detalles de lealtad por cada ciudad en la base de datos. */
    foreach ($CitiesUser as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDCIUDADUSER";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* inserta detalles de lealtad para cada caja en una base de datos. */
    foreach ($CashDesks as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDPUNTOVENTA";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Itera sobre juegos y crea registros de detalles de lealtad en la base de datos. */
    foreach ($Games as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDGAME" . $value->Id;
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value->WageringPercent;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Crea y guarda detalles de lealtad para productos de casino en una base de datos. */
    foreach ($CasinoProduct as $key => $value) {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "CONDGAME" . $value->Id;
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value->Percentage;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta detalles de lealtad a partir de las reglas de deportes proporcionadas. */
    foreach ($SportLealtadRules as $key => $value) {
        $dataA = array(
            "ObjectTypeId" => $value->ObjectTypeId,
            "Id" => $value->ObjectId,
            "Name" => $value->Name,
            "SportName" => $value->SportName,
            "ObjectTypeId" => $value->Name
        );
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $value->ObjectId;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Crea un registro de detalle de lealtad si $LiveOrPreMatch no está vacío. */
    if ($LiveOrPreMatch != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "LIVEORPREMATCH";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $LiveOrPreMatch;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un registro de detalle de lealtad si $MinSelCount no está vacío. */
    if ($MinSelCount != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MINSELCOUNT";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $MinSelCount;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un objeto LealtadDetalle en la base de datos si MinSelPrice no está vacío. */
    if ($MinSelPrice != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MINSELPRICE";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $MinSelPrice;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un objeto LealtadDetalle en la base de datos si MinSelPriceTotal no está vacío. */
    if ($MinSelPriceTotal != "") {
        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MINSELPRICETOTAL";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $MinSelPriceTotal;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

    }


    /* Inserta un detalle de lealtad si hay un TriggerId y un código promocional. */
    if ($TriggerId != "") {
        if ($CodePromo != "") {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "CODEPROMO";
            $LealtadDetalle->moneda = '';
            $LealtadDetalle->valor = $CodePromo;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

        }
    }


    /* Inserta un nuevo registro de lealtad si MinBetPrice no está vacío y es falso. */
    if ($MinBetPrice != "" && false) {

        $LealtadDetalle = new LealtadDetalle();
        $LealtadDetalle->lealtadId = $lealtadId;
        $LealtadDetalle->tipo = "MINBETPRICE";
        $LealtadDetalle->moneda = '';
        $LealtadDetalle->valor = $MinBetPrice;
        $LealtadDetalle->usucreaId = 0;
        $LealtadDetalle->usumodifId = 0;
        $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
        $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


    }


    foreach ($LealtadDetails as $key => $value) {

        /* Inserta un nuevo detalle de lealtad si MinAmount no está vacío. */
        if ($value->MinAmount != "") {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "MINAMOUNT";
            $LealtadDetalle->moneda = $value->CurrencyId;
            $LealtadDetalle->valor = $value->MinAmount;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);


        }

        /* Inserta un registro de lealtad si MaxAmount no es vacío. */
        if ($value->MaxAmount != "") {
            $LealtadDetalle = new LealtadDetalle();
            $LealtadDetalle->lealtadId = $lealtadId;
            $LealtadDetalle->tipo = "MAXAMOUNT";
            $LealtadDetalle->moneda = $value->CurrencyId;
            $LealtadDetalle->valor = $value->MaxAmount;
            $LealtadDetalle->usucreaId = 0;
            $LealtadDetalle->usumodifId = 0;
            $LealtadDetalleMysqlDAO = new LealtadDetalleMySqlDAO($transaccion);
            $LealtadDetalleMysqlDAO->insert($LealtadDetalle);

        }
    }


    /* finaliza una transacción y configura una respuesta sin errores. */
    $transaccion->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* Inicializa un array vacío llamado "Result" en la variable $response. */
    $response["Result"] = array();

} catch (Exception $e) {
    /* Captura excepciones en PHP, pero no realiza ninguna acción con ellas. */


    //print_r($e);
}