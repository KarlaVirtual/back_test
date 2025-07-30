<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioBono;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;



/**
 * Guarda la configuración de bonos para apuestas gratuitas en la base de datos.
 *
 * @param object $params Objeto que contiene:
 * @param string $params->Description Descripción del bono.
 * @param string $params->Name Nombre del bono.
 * @param object $params->PartnerBonus Detalles del socio del bono:
 * - string $params->PartnerBonus->StartDate Fecha de inicio.
 * - string $params->PartnerBonus->EndDate Fecha de fin.
 * - int $params->PartnerBonus->ExpirationDays Días de expiración.
 * - array $params->PartnerBonus->BonusDetails Detalles adicionales del bono.
 * @param string $params->LiveOrPreMatch Tipo de apuesta (en vivo o pre-partido).
 * @param int $params->MinSelCount Mínimo de selecciones.
 * @param float $params->MinSelPrice Precio mínimo por selección.
 * @param float $params->MinSelPriceTotal Precio mínimo total.
 * @param float $params->MinBetPrice Precio mínimo de apuesta.
 * @param string $params->TriggerId Identificador del disparador.
 * @param string $params->CodePromo Código promocional.
 * @param int $params->MaxplayersCount Máximo de jugadores.
 * @param string $params->Prefix Prefijo para códigos.
 * @param string $params->PlayersChosen Jugadores seleccionados.
 * @param int $params->ProductTypeId Identificador del tipo de producto.
 * @param array $params->Games Juegos asociados.
 * @param bool $params->UserRepeatBonus Indica si el usuario puede repetir el bono.
 * @param array $params->SportBonusRules Reglas deportivas asociadas.
 * @param object $params->TriggerDetails Detalles del disparador:
 * - string $params->TriggerDetails->ConditionProduct Condición del producto.
 * @param object $params->Casino Información del casino:
 * - array $params->Casino->Product Productos del casino.
 * @param string $params->TypeBonusDeposit Tipo de depósito del bono.
 * @param int $params->Priority Prioridad del bono.
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 *  - bool $HasError Indica si ocurrió un error.
 *  - string $AlertType Tipo de alerta (success o danger).
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Lista de errores del modelo.
 *  - array $Result Resultado de la operación.
 */

 /* Asigna el valor del mandante si el usuario no es global. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}

try {


    /* asigna valores de parámetros a variables relacionadas con un bono. */
    $Description = $params->Description; //Descripcion del bono
    $Name = $params->Name; //Nombre del bono

    $PartnerBonus = $params->PartnerBonus;

    $StartDate = $PartnerBonus->StartDate; //Fecha Inicial de la campaña

    /* Asignación de valores de fechas y parámetros para una campaña de bonos. */
    $EndDate = $PartnerBonus->EndDate; //Fecha Final de la campaña

    $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono

    $LiveOrPreMatch = $params->LiveOrPreMatch;
    $MinSelCount = $params->MinSelCount;

    /* asigna valores de parámetros a variables para su posterior uso. */
    $MinSelPrice = $params->MinSelPrice;
    $MinSelPriceTotal = $params->MinSelPriceTotal;

    $MinBetPrice = $params->MinBetPrice; // Minimo apuesta cuota total

    $TriggerId = $params->TriggerId;

    /* Se definen variables a partir de parámetros para gestionar una promoción de jugadores. */
    $CodePromo = $params->CodePromo;


    $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
    $Prefix = $params->Prefix;

    $PlayersChosen = $params->PlayersChosen;

    /* asigna variables de parámetros y define un arreglo vacío llamado condiciones. */
    $ProductTypeId = $params->ProductTypeId;


    $Games = $params->Games;

    $condiciones = [];


    /* Asignación de parámetros relacionados con bonificaciones de usuario y deportes. */
    $UserRepeatBonus = $params->UserRepeatBonus;
    $SportBonusRules = $params->SportBonusRules;

    $BonusDetails = $PartnerBonus->BonusDetails;

    $TriggerDetails = $params->TriggerDetails;


    /* Código extrae información de un casino y condiciones de producto para bonificaciones. */
    $Casino = $params->Casino->Info;
    $CasinoProduct = $Casino->Product;

    $TypeBonusDeposit = $params->TypeBonusDeposit;


    $ConditionProduct = $TriggerDetails->ConditionProduct;

    /* Se valida una condición y se asigna 'NA' si no se cumple. */
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }


    $tipobono = 6;

    /* Establece la prioridad a 0 si no es válida o no es numérica. */
    $Priority = $params->Priority;

    if ($Priority == "" || !is_numeric($Priority)) {
        $Priority = 0;
    }

    $cupo = 0;

    /* asigna un límite máximo basado en condiciones específicas. */
    $cupoMaximo = 0;
    $jugadores = 0;
    $jugadoresMaximo = 0;

    if ($MaximumAmount != "" && $tipobono == 2) {
        $cupoMaximo = $MaximumAmount[0]->Amount;
    }


    /* Asigna el máximo de jugadores y usuarios en sesión si se cumplen condiciones. */
    if ($MaxplayersCount != "" && $tipobono == 2) {
        $jugadoresMaximo = $MaxplayersCount;
    }

    $usucrea_id = $_SESSION["usuario"];
    $usumodif_id = $_SESSION["usuario"];


    /* Se crea un objeto "BonoInterno" y se asignan sus propiedades. */
    $BonoInterno = new BonoInterno();
    $BonoInterno->nombre = $Name;
    $BonoInterno->descripcion = $Description;
    $BonoInterno->fechaInicio = $StartDate;
    $BonoInterno->fechaFin = $EndDate;
    $BonoInterno->tipo = $tipobono;

    /* Se asignan propiedades a un objeto BonoInterno, configurando su estado y otros atributos. */
    $BonoInterno->estado = 'A';
    $BonoInterno->usucreaId = 0;
    $BonoInterno->usumodifId = 0;
    $BonoInterno->mandante = $mandanteUsuario;
    $BonoInterno->condicional = $ConditionProduct;
    $BonoInterno->orden = $Priority;

    /* Asigna valores a las propiedades de un objeto y crea una instancia de DAO. */
    $BonoInterno->cupoActual = $cupo;
    $BonoInterno->cupoMaximo = $cupoMaximo;
    $BonoInterno->cantidadBonos = $jugadores;
    $BonoInterno->maximoBonos = $jugadoresMaximo;


    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

    /* obtiene una transacción y la inserta en BonoInterno. */
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $bonoId = $BonoInterno->insert($transaccion);


    if ($MaxplayersCount != "" && $Prefix != "") {


        /* asigna jugadores elegidos a un nuevo array con valor inicial cero. */
        $jugadoresAsignar = array();
        $jugadoresAsignarFinal = array();

        if ($PlayersChosen != "") {
            $jugadoresAsignar = explode(",", $PlayersChosen);

            foreach ($jugadoresAsignar as $item) {

                array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => 0));

            }
        }


        /* Se crea un array vacío llamado $codigosarray en PHP. */
        $codigosarray = array();

        for ($i = 1; $i <= $MaxplayersCount; $i++) {

            /* Genera un código único comprobando que no se repita en un array. */
            $codigo = GenerarClaveTicket(4);

            while (in_array($codigo, $codigosarray)) {
                $codigo = GenerarClaveTicket(4);
            }

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];

                $valor = $jugadoresAsignarFinal[$i]["Valor"];

                $valor_bono = $jugadoresAsignarFinal[$i]["Valor"];

                $valor_base = $jugadoresAsignarFinal[$i]["Valor"];


            } else {
                /* Asignación de valores predeterminados a variables en caso de una condición no cumplida. */

                $usuarioId = '0';
                $valor = '0';

                $valor_bono = '0';

                $valor_base = '0';

            }


            /* Variables inicializadas con valores predeterminados para gestionar un estado y errores. */
            $estado = 'L';

            $errorId = '0';

            $idExterno = '0';

            $mandante = '0';


            /* Variables iniciales de identificadores y monto apostado se establecen en cero. */
            $usucreaId = '0';

            $usumodifId = '0';


            $apostado = '0';

            /* inicializa variables y establece un ID de usuario en un objeto. */
            $rollowerRequerido = '0';
            $codigo = $Prefix . $codigo;

            $UsuarioBono = new UsuarioBono();

            $UsuarioBono->setUsuarioId($usuarioId);

            /* Asigna propiedades a un objeto UsuarioBono usando diferentes valores proporcionados. */
            $UsuarioBono->setBonoId($bonoId);
            $UsuarioBono->setValor($valor);
            $UsuarioBono->setValorBono($valor_bono);
            $UsuarioBono->setValorBase($valor_base);
            $UsuarioBono->setEstado($estado);
            $UsuarioBono->setErrorId($errorId);

            /* Asigna valores a propiedades de un objeto UsuarioBono en programación orientada a objetos. */
            $UsuarioBono->setIdExterno($idExterno);
            $UsuarioBono->setMandante($mandante);
            $UsuarioBono->setUsucreaId($usucreaId);
            $UsuarioBono->setUsumodifId($usumodifId);
            $UsuarioBono->setApostado($apostado);
            $UsuarioBono->setRollowerRequerido($rollowerRequerido);

            /* Se establece información de un bono de usuario y se guarda en la base de datos. */
            $UsuarioBono->setCodigo($codigo);
            $UsuarioBono->setVersion(0);
            $UsuarioBono->setExternoId($idExterno);

            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

            $UsuarioBonoMysqlDAO->insert($UsuarioBono);


            /* Añade un elemento `$codigo` al final del arreglo `$codigosarray`. */
            array_push($codigosarray, $codigo);

        }
    }


    //Expiracion


    /* Inserta un nuevo BonoDetalle en base de datos si $ExpirationDays no está vacío. */
    if ($ExpirationDays != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "EXPDIA";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $ExpirationDays;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Condicional para insertar un bono si se cumple la condición de UserRepeatBonus. */
    if ($UserRepeatBonus != "" && ($UserRepeatBonus == "true" || $UserRepeatBonus == true)) {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "REPETIRBONO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $UserRepeatBonus;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* inserta un nuevo detalle de bono si $Prefix no está vacío. */
    if ($Prefix != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "PREFIX";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $Prefix;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta un registro de bono en la base de datos si se utiliza una billetera congelada. */
    if ($UseFrozeWallet != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "FROZEWALLET";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $UseFrozeWallet;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta un registro de bono detalle si hay un retiro suprimido. */
    if ($SuppressWithdrawal != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "SUPPRESSWITHDRAWAL";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $SuppressWithdrawal;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta un nuevo objeto BonoDetalle si ScheduleCount no está vacío. */
    if ($ScheduleCount != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "SCHEDULECOUNT";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $ScheduleCount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta un nuevo detalle de bono si el nombre del horario no está vacío. */
    if ($ScheduleName != "") {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "SCHEDULENAME";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $ScheduleName;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta un nuevo registro de bono si $SchedulePeriod no está vacío. */
    if ($SchedulePeriod != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "SCHEDULEPERIOD";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $SchedulePeriod;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Crea un nuevo registro de bono si el tipo de periodo de programación no está vacío. */
    if ($SchedulePeriodType != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "SCHEDULEPERIODTYPE";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $SchedulePeriodType;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta un nuevo registro en la base de datos si el tipo de producto no está vacío. */
    if ($ProductTypeId !== "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "TIPOPRODUCTO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $ProductTypeId;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }

    /*if ($Count != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CANTDEPOSITOS";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $Count;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }*/


    /* inserta detalles de bono si $AreAllowed no está vacío. */
    if ($AreAllowed != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "PAISESPERMITIDOS";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $AreAllowed;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta un nuevo registro de BonoDetalle si la fecha de expiración no está vacía. */
    if ($ExpirationDate != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "EXPFECHA";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $ExpirationDate;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* inserta un bono de porcentaje si el tipo de bono es '1'. */
    if ($TypeBonusDeposit == '1') {

        if ($BonusPercent != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "PORCENTAJE";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $BonusPercent;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }
    }

    /*if ($BonusWFactor != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "WFACTORBONO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $BonusWFactor;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }

    if ($DepositWFactor != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "WFACTORDEPOSITO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $DepositWFactor;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    if ($DepositNumber != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "NUMERODEPOSITO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $DepositNumber;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }

    if ($IsFromCashDesk) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDEFECTIVO";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = 'true';
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }*/


    /* Inserts a new BonoDetalle entry if MaxplayersCount is not empty. */
    if ($MaxplayersCount != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MAXJUGADORES";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $MaxplayersCount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta detalles de bono basados en un arreglo de pagos máximos. */
    foreach ($MaxPayout as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MAXPAGO";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->Amount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }

    /*foreach ($MaximumDeposit as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MAXDEPOSITO";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->Amount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }

    foreach ($MinimumDeposit as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MINDEPOSITO";
        $BonoDetalle->moneda = $value->CurrencyId;
        $BonoDetalle->valor = $value->Amount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }*/


    /* Inserta detalles de un bono en la base de datos según requisitos monetarios. */
    if ($TypeBonusDeposit == '0') {

        foreach ($MoneyRequirement as $key => $value) {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "VALORBONO";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->Amount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

    }

    /*foreach ($PaymentSystemIds as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPAYMENT";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }*/


    /* Itera sobre regiones, creando e insertando objetos BonoDetalle en la base de datos. */
    foreach ($Regions as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPAISPV";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta detalles de bonos por departamentos en una base de datos. */
    foreach ($Departments as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDDEPARTAMENTOPV";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta detalles del bono para cada ciudad en la base de datos. */
    foreach ($Cities as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDCIUDADPV";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Se inserta un bono con saldo cero si la condición es verdadera. */
    if ($BalanceZero == "true" || $BalanceZero == 1 || $BalanceZero == true) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDBALANCE";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = '0';
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);
    }


    /* Se insertan detalles de bono para cada región de un usuario. */
    foreach ($RegionsUser as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPAISUSER";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta detalles de bono por cada departamento del usuario en la base de datos. */
    foreach ($DepartmentsUser as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDDEPARTAMENTOUSER";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta detalles de bonificaciones para cada ciudad del usuario en la base de datos. */
    foreach ($CitiesUser as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDCIUDADUSER";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta registros de BonoDetalle para cada caja en el sistema transaccional. */
    foreach ($CashDesks as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPUNTOVENTA";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }

    /* Insertar detalles de bonos para cada caja usando un bucle. */
    foreach ($CashDesks as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPUNTOVENTA";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta detalles de bonos para cada juego en la base de datos. */
    foreach ($Games as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDGAME" . $value->Id;
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value->WageringPercent;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta detalles de bonos en la base de datos para cada producto de casino. */
    foreach ($CasinoProduct as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDGAME" . $value->Id;
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value->Percentage;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* inserta detalles de bonificación en una base de datos usando un bucle. */
    foreach ($SportBonusRules as $key => $value) {
        $dataA = array(
            "ObjectTypeId" => $value->ObjectTypeId,
            "Id" => $value->ObjectId,
            "Name" => $value->Name,
            "SportName" => $value->SportName,
            "ObjectTypeId" => $value->Name
        );
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "ITAINMENT" . $value->ObjectTypeId;
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value->ObjectId;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta un nuevo detalle de bono si $LiveOrPreMatch no está vacío. */
    if ($LiveOrPreMatch != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "LIVEORPREMATCH";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $LiveOrPreMatch;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta un registro de bono si $MinSelCount no está vacío. */
    if ($MinSelCount != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MINSELCOUNT";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $MinSelCount;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta un nuevo registro de BonoDetalle si MinSelPrice no está vacío. */
    if ($MinSelPrice != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MINSELPRICE";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $MinSelPrice;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Crea y guarda un objeto BonoDetalle si el precio mínimo total es válido. */
    if ($MinSelPriceTotal != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MINSELPRICETOTAL";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $MinSelPriceTotal;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);

    }


    /* Inserta un registro de bono si TriggerId y CodePromo no están vacíos. */
    if ($TriggerId != "") {
        if ($CodePromo != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "CODEPROMO";
            $BonoDetalle->moneda = '';
            $BonoDetalle->valor = $CodePromo;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }
    }


    /* Inserta un detalle de bono si se cumple la condición del mínimo precio de apuesta. */
    if ($MinBetPrice != "" && false) {

        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "MINBETPRICE";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $MinBetPrice;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    foreach ($BonusDetails as $key => $value) {

        /* Inserta un nuevo registro de BonoDetalle si MinAmount no está vacío. */
        if ($value->MinAmount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MINAMOUNT";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->MinAmount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);


        }

        /* Crea un registro de BonoDetalle si MaxAmount no está vacío, usando valores específicos. */
        if ($value->MaxAmount != "") {
            $BonoDetalle = new BonoDetalle();
            $BonoDetalle->bonoId = $bonoId;
            $BonoDetalle->tipo = "MAXAMOUNT";
            $BonoDetalle->moneda = $value->CurrencyId;
            $BonoDetalle->valor = $value->MaxAmount;
            $BonoDetalle->usucreaId = 0;
            $BonoDetalle->usumodifId = 0;
            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
            $BonoDetalleMysqlDAO->insert($BonoDetalle);

        }
    }


    /* Confirma la transacción y prepara una respuesta sin errores. */
    $transaccion->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* Inicia un array vacío llamado "Result" en la variable $response. */
    $response["Result"] = array();

} catch (Exception $e) {
    /* Manejo de excepciones en PHP, captura errores sin mostrar detalles. */


    //print_r($e);
}