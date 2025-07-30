<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioBono;
use Backend\integrations\casino\PLAYNGOSERVICES;
use Backend\integrations\casino\PLAYTECHSERVICES;
use Backend\integrations\casino\PRAGMATICSERVICES;
use Backend\integrations\casino\REDRAKESERVICESBONUS;
use Backend\integrations\casino\TOMHORNSERVICES;
use Backend\integrations\casino\MASCOTSERVICES;
use Backend\integrations\casino\G7777GAMINGSERVICES;
use Backend\integrations\casino\WORLDMATCHSERVICES;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;


/**
 * Este script procesa la creación de bonos de giros gratis para diferentes proveedores de casino.
 * Utiliza múltiples servicios y clases para gestionar la lógica de negocio y las transacciones.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes valores:
 * @param string $params->Description Descripción del bono.
 * @param string $params->Name Nombre del bono.
 * @param string $params->BeginDate Fecha inicial de la campaña.
 * @param string $params->EndDate Fecha final de la campaña.
 * @param array $params->DateProgram Fechas programadas para el bono.
 * @param object $params->PartnerBonus Información del bono asociado.
 * @param string $params->PartnerBonus->ExpirationDate Fecha de expiración del bono.
 * @param int $params->PartnerBonus->ExpirationDays Días de expiración del bono.
 * @param string $params->LiveOrPreMatch Tipo de apuesta (en vivo o pre-partido).
 * @param int $params->MinSelCount Cantidad mínima de selecciones.
 * @param float $params->MinSelPrice Cuota mínima seleccionada.
 * @param float $params->CurrentCost Costo actual.
 * @param object $params->DepositDefinition Definición del depósito.
 * @param object $params->DepositDefinition->BonusDefinition Definición del bono.
 * @param int $params->DepositDefinition->BonusDefinitionId ID de la definición del bono.
 * @param float $params->DepositDefinition->BonusPercent Porcentaje del bono.
 * @param float $params->DepositDefinition->BonusWFactor Factor de rollover del bono.
 * @param int $params->DepositDefinition->DepositNumber Número de depósitos.
 * @param float $params->DepositDefinition->DepositWFactor Factor de rollover del depósito.
 * @param bool $params->DepositDefinition->SuppressWithdrawal Indica si se suprime el retiro.
 * @param bool $params->DepositDefinition->UseFrozeWallet Indica si se usa una billetera congelada.
 * @param bool $params->IsVisibleForAllplayers Indica si es visible para todos los jugadores.
 * @param bool $params->OpenToAllPlayers Indica si está abierto a todos los jugadores.
 * @param int $params->MaxplayersCount Número máximo de jugadores.
 * @param string $params->Prefix Prefijo para los códigos generados.
 * @param array $params->NumberOfCartons Número de cartones.
 * @param string $params->PlayersChosen IDs de jugadores seleccionados.
 * @param object $params->ForeignRule Reglas externas.
 * @param mixed $params->ForeignRule->Info Información de las reglas externas.
 * @param int $params->TriggerId ID del disparador.
 * @param string $params->CodePromo Código promocional.
 * @param float $params->MinBetPrice Apuesta mínima total.
 * @param array $params->SportBonusRules Reglas de bonos deportivos.
 * @param int $params->ProductTypeId ID del tipo de producto.
 * @param int $params->TypeId ID del tipo de bono.
 * @param array $params->Games Juegos asociados al bono.
 * @param array $params->MaxPayout Pago máximo.
 * @param float $params->MaximumBonusAmount Monto máximo del bono.
 * @param float $params->MaximumDeposit Depósito máximo.
 * @param float $params->MinimumDeposit Depósito mínimo.
 * @param float $params->MoneyRequirement Requisito monetario.
 * @param float $params->MoneyRequirementAmount Monto del requisito monetario.
 * @param object $params->Schedule Programación del bono.
 * @param int $params->Schedule->Count Cantidad de eventos programados.
 * @param string $params->Schedule->Name Nombre de la programación.
 * @param string $params->Schedule->Period Periodo de la programación.
 * @param string $params->Schedule->PeriodType Tipo de periodo.
 * @param object $params->TriggerDetails Detalles del disparador.
 * @param int $params->TriggerDetails->Count Cantidad de depósitos.
 * @param bool $params->TriggerDetails->IsFromCashDesk Indica si proviene de caja.
 * @param int $params->TriggerDetails->PaymentSystemId ID del sistema de pago.
 * @param array $params->TriggerDetails->PaymentSystemIds IDs de sistemas de pago.
 * @param array $params->TriggerDetails->Regions Regiones permitidas.
 * @param array $params->TriggerDetails->Departments Departamentos permitidos.
 * @param array $params->TriggerDetails->Cities Ciudades permitidas.
 * @param array $params->TriggerDetails->CashDesk Cajas permitidas.
 * @param array $params->TriggerDetails->CashDesksNot Cajas no permitidas.
 * @param array $params->TriggerDetails->RegionsUser Regiones del usuario.
 * @param array $params->TriggerDetails->DepartmentsUser Departamentos del usuario.
 * @param array $params->TriggerDetails->CitiesUser Ciudades del usuario.
 * @param bool $params->TriggerDetails->BalanceZero Indica si el balance debe ser cero.
 * @param string $params->TriggerDetails->ConditionProduct Condición del producto (OR/AND/NA).
 * @param bool $params->UserRepeatBonus Indica si el usuario puede repetir el bono.
 * @param array $params->Rounds Rondas de giros gratis.
 * @param array $params->FreeRoundsMaxDays Días máximos para los giros gratis.
 * @param int $params->WinBonusId ID del bono de ganancia.
 * @param string $params->TypeSaldo Tipo de saldo.
 * @param int $params->Priority Prioridad del bono.
 * @param object $params->FreeSpinDefinition Definición de giros gratis.
 * @param int $params->FreeSpinDefinition->AutomaticForfeitureLevel Nivel de pérdida automática.
 * @param string $params->FreeSpinDefinition->BonusMoneyExpirationDate Fecha de expiración del dinero del bono.
 * @param int $params->FreeSpinDefinition->BonusMoneyExpirationDays Días de expiración del dinero del bono.
 * @param int $params->FreeSpinDefinition->FreeSpinsTotalCount Total de giros gratis.
 * @param float $params->FreeSpinDefinition->WageringFactor Factor de apuesta.
 * @param object $params->Casino Información del casino.
 * @param object $params->Casino->Info Información general.
 * @param string $params->Casino->Info->Category Categoría del casino.
 * @param string $params->Casino->Info->Provider Proveedor del casino.
 * @param string $params->Casino->Info->Product Producto del casino.
 * @param bool $params->IsLoyalty Indica si es un bono de lealtad.
 * @param bool $params->IsCRM Indica si es un bono de CRM.
 * @param object $params->BonusDetails Detalles del bono.
 * @param string $params->CodeGlobal Código global.
 * @param string $params->RulesText Texto de las reglas.
 * @param string $params->PlayersIdCsv CSV de IDs de jugadores.
 * @param string $params->TypeBonusDeposit Tipo de bono de depósito.
 * @param string $params->Type Tipo de bono (1 o 0).
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes valores:
 *  - idBonus: int ID del bono creado.
 *  - HasError: bool Indica si ocurrió un error.
 *  - AlertType: string Tipo de alerta (e.g., danger, success).
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores del modelo.
 *  - Result: array Resultado de la operación.
 */

/* incluye autoload y configura la visualización de errores; define una función. */
require('/home/home2/backend/api/vendor/autoload.php');

ini_set("display_errors", "OFF");

/**
 * GenerarClaveTicket
 *
 * Genera una clave aleatoria alfanumérica con la longitud especificada.
 *
 * @param int $length Longitud de la clave a generar.
 *
 * @return string response Retorna una cadena aleatoria compuesta por números y letras mayúsculas.
 *
 * @throws Exception Si la longitud proporcionada no es válida.
 * @access public
 */
function GenerarClaveTicket($length)
{

    /* Genera una cadena aleatoria utilizando caracteres alfanuméricos y configura errores debug. */
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


if ($_ENV['debug']) {
    error_reporting(E_ALL);
    ini_set("display_errors", "ON");

}

/* Registra información de un proceso en un archivo de log, incluyendo un timestamp. */
$log = "";
$log = $log . "CreateBonusFreeSpin " . time();

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . (file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


/* Asignación de mandante a usuario si no pertenece a Global y log de advertencia. */
$mandanteUsuario = 0;
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $mandante;
}
syslog(LOG_WARNING, "CreateBonusFreeSpinEXEC: ");

try {

    /* decodifica parámetros en base64 y los convierte en un objeto JSON. */
    $mandante = intval($argv[3]);

    $params = base64_decode($argv[1]);
    $params = json_decode($params);

    $bonoId = intval($argv[2]);
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC '.$bonoId."' '#dev' > /dev/null & ");


    /* extrae y asigna parámetros de un objeto relacionado con bonos. */
    $Description = $params->Description; //Descripcion del bono
    $Name = $params->Name; //Nombre del bono
    $StartDate = $params->BeginDate; //Fecha Inicial de la campaña
    $EndDate = $params->EndDate; //Fecha Final de la campaña
    $DateProgram = $params->DateProgram;


    $PartnerBonus = $params->PartnerBonus;


    /* asigna fechas de expiración si están vacías inicialmente. */
    $ExpirationDate = $PartnerBonus->ExpirationDate; //Fecha de expiracion del bono
    $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono


    if ($ExpirationDate == "" && $ExpirationDays == "") {
        $ExpirationDate = $params->ExpirationDate; //Fecha de expiracion del bono
        $ExpirationDays = $params->ExpirationDays; // Dias de expiracion del bono

    }


    /* asigna valores de parámetros a variables relacionadas con una apuesta. */
    $LiveOrPreMatch = $params->LiveOrPreMatch;
    $MinSelCount = $params->MinSelCount;
    $MinSelPrice = $params->MinSelPrice;


    $CurrentCost = $params->CurrentCost;
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART0'."' '#dev' > /dev/null & ");


    /* Asignación de valores de definición de depósito y bonificación a variables en PHP. */
    $DepositDefinition = $params->DepositDefinition;

    $BonusDefinition = $DepositDefinition->BonusDefinition;
    $BonusDefinitionId = $DepositDefinition->BonusDefinitionId;
    $BonusPercent = $DepositDefinition->BonusPercent; // Porcentaje del bono
    $BonusWFactor = $DepositDefinition->BonusWFactor; //Rollower bono

    /* Asignación de variables desde un objeto DepositDefinition y parámetros de visibilidad para jugadores. */
    $DepositNumber = $DepositDefinition->DepositNumber;
    $DepositWFactor = $DepositDefinition->DepositWFactor; //Rollower Deposito

    $SuppressWithdrawal = $DepositDefinition->SuppressWithdrawal; //Suprimir la retirada
    $UseFrozeWallet = $DepositDefinition->UseFrozeWallet;

    $IsVisibleForAllplayers = $params->IsVisibleForAllplayers; //Es para todos los usuarios

    /* Variables para gestionar jugadores y parámetros en un juego o sistema de participación. */
    $OpenToAllPlayers = $params->OpenToAllPlayers;//Es para todos los usuarios
    $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
    $Prefix = $params->Prefix;
    $numberOfCartons = $params->NumberOfCartons;

    $PlayersChosen = $params->PlayersChosen;


    /* verifica si $ForeignRuleInfo es un objeto y lo decodifica si no lo es. */
    $ForeignRule = $params->ForeignRule;
    $ForeignRuleInfo = $ForeignRule->Info;
//print_r($params);

    if (!is_object($ForeignRuleInfo)) {
        $ForeignRuleJSON = json_decode($ForeignRuleInfo);

    } else {
        /* Condicional que asigna valor a $ForeignRuleJSON si no se cumple la condición previa. */

        $ForeignRuleJSON = $ForeignRuleInfo;
    }
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART1'."' '#dev' > /dev/null & ");


    /* Extrae datos de un objeto JSON para establecer reglas de apuestas. */
    $LiveOrPreMatch = $ForeignRuleJSON->LiveOrPreMatch;
    $MinSelCount = $ForeignRuleJSON->MinSelCount; // Minimo cantidad de selecciones
    $MinSelPrice = $ForeignRuleJSON->MinSelPrice; // Minimo cuota seleccionada
    $MinSelPriceTotal = $ForeignRuleJSON->MinSelPriceTotal;

    $TriggerId = $params->TriggerId;

    /* asigna valores de parámetros y reglas a variables para apuestas. */
    $CodePromo = $params->CodePromo;


    $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total
    $SportBonusRules = $ForeignRuleJSON->SportBonusRules;

    $ProductTypeId = $params->ProductTypeId;


    /* asigna un TriggerId basado en la validez de un código promocional. */
    $TriggerId = $params->TriggerId;

    if ($CodePromo != "") {
        $TriggerId = 1;
    }

    $TypeId = $params->TypeId;


    /* asigna parámetros y ejecuta un script PHP para procesar un juego. */
    $Games = $params->Games;

    $condiciones = [];

    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART2'."' '#dev' > /dev/null & ");

    $MaxPayout = $params->MaxPayout; //Pago Maximo

    /* asigna valores de parámetros relacionados con límites financieros a variables. */
    $MaximumBonusAmount = $params->MaximumBonusAmount; //Maximo valordel bono
    $MaximumDeposit = $params->MaximumDeposit;
    $MinimumDeposit = $params->MinimumDeposit;
//$MinimumAmount = $params->MinimumAmount;
    $MaximumAmount = $params->MaximumAmount;
    $MoneyRequirement = $params->MoneyRequirement;

    /* extrae y asigna valores de parámetros de programación y requisitos monetarios. */
    $MoneyRequirementAmount = $params->MoneyRequirementAmount;

    $Schedule = $params->Schedule; //Programar bono
    $ScheduleCount = $Schedule->Count; //
    $ScheduleName = $Schedule->Name; //Descripcion de la programacion
    $SchedulePeriod = $Schedule->Period;

    /* asigna valores de un objeto a variables para procesamiento posterior. */
    $SchedulePeriodType = $Schedule->PeriodType;

    $TriggerDetails = $params->TriggerDetails;
    $Count = $TriggerDetails->Count; //Cantidad de depositos

//$AreAllowed = $TriggerDetails->AreAllowed; //Son Permitidos los paises

    $IsFromCashDesk = $TriggerDetails->IsFromCashDesk;

    /* Se asignan identificadores y ubicaciones desde la variable TriggerDetails. */
    $PaymentSystemId = $TriggerDetails->PaymentSystemId;
    $PaymentSystemIds = $TriggerDetails->PaymentSystemIds;

    $Regions = $TriggerDetails->Regions;
    $Departments = $TriggerDetails->Departments;
    $Cities = $TriggerDetails->Cities;

    /* Se asignan variables a partir de detalles de un disparador y parámetros de usuario. */
    $CashDesks = $TriggerDetails->CashDesk;
    $CashDesksNot = $TriggerDetails->CashDesksNot;
    $RegionsUser = $TriggerDetails->RegionsUser;
    $DepartmentsUser = $TriggerDetails->DepartmentsUser;
    $CitiesUser = $TriggerDetails->CitiesUser;
    $UserRepeatBonus = $params->UserRepeatBonus;

    /* ejecuta un script PHP y establece parámetros relacionados con giros gratuitos. */
    $rounds = $params->Rounds;

    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART3'."' '#dev' > /dev/null & ");

    $FreeRoundsMaxDays = $params->FreeRoundsMaxDays;
    $BalanceZero = $TriggerDetails->BalanceZero;


    /* Se asignan variables y se valida si Priority es numérico o vacío. */
    $WinBonusId = $params->WinBonusId;
    $TypeSaldo = $params->TypeSaldo;
    $Priority = $params->Priority;

    if ($Priority == "" || !is_numeric($Priority)) {
        $Priority = 0;
    }


    /* Verifica y asigna condiciones de producto, establece 'NA' si es inválido. */
    $ConditionProduct = $TriggerDetails->ConditionProduct;
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }

    $FreeSpinDefinition = $params->FreeSpinDefinition;

    /* Se asignan valores de definición de giros gratis a variables para manejo posterior. */
    $AutomaticForfeitureLevel = $FreeSpinDefinition->AutomaticForfeitureLevel;
    $BonusMoneyExpirationDate = $FreeSpinDefinition->BonusMoneyExpirationDate;
    $BonusMoneyExpirationDays = $FreeSpinDefinition->BonusMoneyExpirationDays;
    $FreeSpinsTotalCount = $FreeSpinDefinition->FreeSpinsTotalCount;
    $WageringFactor = $FreeSpinDefinition->WageringFactor;
    $PlayersChosen = $params->PlayersChosen;

    /* obtiene información sobre un casino y su configuración desde parámetros. */
    $Casino = $params->Casino->Info;
    $CasinoCategory = $Casino->Category;
    $CasinoProvider = $Casino->Provider;
    $CasinoProduct = $Casino->Product;
    $IsLoyalty = $params->IsLoyalty;
    $IsCRM = $params->IsCRM;

    /* procesa datos de bonificaciones y jugadores desde parámetros de entrada. */
    $BonusDetails = $params->BonusDetails;
    $CodeGlobal = $params->CodeGlobal;
    $RulesText = str_replace("'", "\'", $params->RulesText);

    $PlayersIdCsv = $params->PlayersIdCsv;
    $PlayersIdCsv = explode("base64,", $PlayersIdCsv);

    /* Decodifica, reemplaza punto y coma por coma, y divide el texto en líneas. */
    $PlayersIdCsv = $PlayersIdCsv[1];

    $PlayersIdCsv = base64_decode($PlayersIdCsv);

    $PlayersIdCsv = str_replace(";", ",", $PlayersIdCsv);

    $lines = explode(PHP_EOL, $PlayersIdCsv);

    /* convierte un CSV de jugadores en un arreglo de arrays. */
    $lines = preg_split('/\r\n|\r|\n/', $PlayersIdCsv);

    $array = array();
    foreach ($lines as $line) {

        $array[] = str_getcsv($line);

    }
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART5'."' '#dev' > /dev/null & ");


    /* extrae la primera columna de un array y obtiene sus claves. */
    $arrayfinal = array_column($array, '0');
//$arrayfinal3 =  array_column($array,'1');


    /*$primera = substr($arrayfinal[0], 3);*/

    /*$arrayfinal[0] = $primera;*/

    $posiciones = array_keys($arrayfinal);


    /* procesa un array, eliminando un elemento y filtrando vacíos. */
    $ultima = strval(end($posiciones));
    $arrayfinal = json_decode(json_encode($arrayfinal));
    $arrayfinal2 = array();
    unset($arrayfinal[$ultima]);
    foreach ($arrayfinal as $item) {

        if ($item != "") {

            array_push($arrayfinal2, $item);
        }
    }
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART6'."' '#dev' > /dev/null & ");


    /* asigna valores y convierte un arreglo en una cadena de IDs. */
    $arrayfinal = $arrayfinal2;

    $ids = implode(",", $arrayfinal);

    $TypeBonusDeposit = $params->TypeBonusDeposit;

    $Type = ($params->Type == '1') ? '1' : '0';


    /* Se inicializan variables y se establece un límite según el tipo de bono. */
    $tipobono = $TypeId;
    $cupo = 0;
    $cupoMaximo = 0;
    $jugadores = 0;
    $jugadoresMaximo = 0;

    if ($MaximumAmount != "" && $tipobono == 2) {
        $cupoMaximo = $MaximumAmount[0]->Amount;
    }


    /* Asigna valores a variables según condiciones específicas sobre jugadores y cupos máximos. */
    if ($MaxplayersCount != "" && $tipobono == 2) {
        $jugadoresMaximo = $MaxplayersCount;
    }

    if ($cupoMaximo == "") {
        $cupoMaximo = 0;
    }
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART7'."' '#dev' > /dev/null & ");


    /* Se crea una instancia de BonoDetalleMySqlDAO y se obtiene una transacción. */
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART9'."' '#dev' > /dev/null & ");
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PART9'.json_encode($DateProgram)."' '#dev' > /dev/null & ");

    if (oldCount($DateProgram) > 0) {
        //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO11'."' '#dev' > /dev/null & ");

        foreach ($DateProgram as $value) {
            //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO1'."' '#dev' > /dev/null & ");

            /* Convierte fechas y ejecuta un script PHP en segundo plano. Obtiene ID de proveedor. */
            $StartDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->BeginDate)));
            $EndDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->EndDate)));

            //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO2'."' '#dev' > /dev/null & ");


            $idProveedor = $CasinoProvider[0]->Id;
            //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO21 '.$idProveedor."' '#dev' > /dev/null & ");


            /* Crea un objeto SubProveedor y un objeto Proveedor con el ID especificado. */
            $SubProveedor = new \Backend\dto\Subproveedor($idProveedor);
            //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO22 '.$SubProveedor->proveedorId."' '#dev' > /dev/null & ");
            $Proveedor = new Proveedor($SubProveedor->proveedorId);

            $respuestaGeneral = "";

            if ($Proveedor->abreviado != "REDRAKE" && $Proveedor->abreviado != "TOMHORN" && $Proveedor->abreviado != "IESGAMES" && $Proveedor->abreviado != "MASCOT") { //|| $Proveedor->abreviado != "TOMHORN"

                if ($FreeSpinsTotalCount != "" && $Prefix != "") {

                    /* asigna jugadores seleccionados a un array con su valor correspondiente. */
                    $jugadoresAsignar = array();
                    $jugadoresAsignarFinal = array();

                    if ($PlayersChosen != "") {
                        $jugadoresAsignar = explode(",", $PlayersChosen);


                        foreach ($jugadoresAsignar as $item) {

                            array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $AutomaticForfeitureLevel));

                        }

                    }


                    /* Se inicializa un array vacío llamado `$codigosarray` en PHP. */
                    $codigosarray = array();

                    for ($i = 1; $i <= $FreeSpinsTotalCount; $i++) {

                        /* Genera un código único de 4 caracteres, evitando duplicados en un arreglo. */
                        $codigo = GenerarClaveTicket(4);

                        while (in_array($codigo, $codigosarray)) {
                            $codigo = GenerarClaveTicket(4);
                        }


                        $usuarioId = '0';

                        /* Se asigna el valor de $AutomaticForfeitureLevel a tres variables y se establece $estado. */
                        $valor = $AutomaticForfeitureLevel;

                        $valor_bono = $AutomaticForfeitureLevel;

                        $valor_base = $AutomaticForfeitureLevel;

                        $estado = 'L';


                        /* Variables inicializadas a cero, probablemente para gestionar errores y usuarios en un sistema. */
                        $errorId = '0';

                        $idExterno = '0';

                        $mandante = '0';


                        $usucreaId = '0';


                        /* Variables inicializan valores en un código que probablemente maneja apuestas o identificadores. */
                        $usumodifId = '0';


                        $apostado = '0';
                        $rollowerRequerido = '0';
                        $codigo = $Prefix . $codigo;


                        /* Se crea un objeto UsuarioBono y se establecen sus propiedades. */
                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);
                        $UsuarioBono->setBonoId($bonoId);
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);

                        /* Se asignan atributos a la instancia UsuarioBono utilizando métodos setter. */
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);
                        $UsuarioBono->setIdExterno($idExterno);
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);

                        /* asigna valores a propiedades de un objeto UsuarioBono y lo inicializa. */
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                        $UsuarioBono->setCodigo($codigo);

                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);


                        /* Inserta un bono y asigna un código a jugadores según su ID. */
                        $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                        }

                        array_push($codigosarray, $codigo);

                    }
                }


                /* Se activa una variable si se cumplen ciertas condiciones de conteo y prefijo. */
                $darAUsuariosEspecificos = false;
                if (($MaxplayersCount != "" && $Prefix != "") || ($MaxplayersCount != "")) {
                    $darAUsuariosEspecificos = true;
                }

                if ($darAUsuariosEspecificos) {


                    /* Se crean dos arrays vacíos para asignar jugadores en un programa. */
                    $jugadoresAsignar = array();
                    $jugadoresAsignarFinal = array();

                    /* foreach ($MinimumAmount as $key => $value) {

                         $jugadoresAsignar = explode(",", $value->Amount);

                         foreach ($MaxPayout as $key2 => $value2) {

                             if ($value->CurrencyId == $value2->CurrencyId) {

                                 foreach ($jugadoresAsignar as $item) {

                                     if ($item != 0) {

                                         array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => $value2->Amount));

                                     }

                                 }

                             }
                         }

                     }*/


                    /* Se crea un array vacío en PHP para almacenar códigos. */
                    $codigosarray = array();


                    for ($i = 0; $i < $MaxplayersCount; $i++) {

                        /* Genera un código único de 4 caracteres que no esté en un array dado. */
                        $codigo = GenerarClaveTicket(4);

                        while (in_array($codigo, $codigosarray)) {
                            $codigo = GenerarClaveTicket(4);
                        }


                        $usuarioId = '0';

                        /* Establece el estado de un usuario según el tipo de bono y la asignación. */
                        $estado = 'L';

                        /*if ($tipobono != 2) {
                            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                                $estado = 'A';

                            }

                        }*/

                        if ($tipobono == "2") {
                            if ($jugadoresAsignarFinal[$i] != "" && $jugadoresAsignarFinal[$i]["Id"] != null) {
                                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];
                                $estado = 'P';

                            }
                        }


                        /* Variables inicializadas en cero, listas para almacenar valores en el código. */
                        $valor = '0';

                        $valor_bono = '0';

                        $valor_base = '0';

                        $errorId = '0';


                        /* Inicializa variables con valores predeterminados para uso posterior en el código. */
                        $idExterno = '0';

                        $mandante = '0';


                        $usucreaId = '0';


                        /* Asignación de variables iniciales y concatenación de un prefijo al código. */
                        $usumodifId = '0';


                        $apostado = '0';
                        $rollowerRequerido = '0';
                        $codigo = $Prefix . $codigo;


                        /* Se crea un objeto UsuarioBono y se establecen sus propiedades. */
                        $UsuarioBono = new UsuarioBono();

                        $UsuarioBono->setUsuarioId($usuarioId);
                        $UsuarioBono->setBonoId($bonoId);
                        $UsuarioBono->setValor($valor);
                        $UsuarioBono->setValorBono($valor_bono);

                        /* Se establecen propiedades de la clase UsuarioBono con valores específicos. */
                        $UsuarioBono->setValorBase($valor_base);
                        $UsuarioBono->setEstado($estado);
                        $UsuarioBono->setErrorId($errorId);
                        $UsuarioBono->setIdExterno($idExterno);
                        $UsuarioBono->setMandante($mandante);
                        $UsuarioBono->setUsucreaId($usucreaId);

                        /* configura propiedades de un objeto `UsuarioBono` con varios valores. */
                        $UsuarioBono->setUsumodifId($usumodifId);
                        $UsuarioBono->setApostado($apostado);
                        $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                        $UsuarioBono->setCodigo($codigo);
                        $UsuarioBono->setVersion(0);
                        $UsuarioBono->setExternoId(0);


                        /* Inserta un usuario en MySQL y asigna un código a un jugador específico. */
                        $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                        $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                            $jugadoresAsignarFinal[$i]["Code"] = $codigo;
                        }


                        /* Añade el elemento $codigo al final del array $codigosarray en PHP. */
                        array_push($codigosarray, $codigo);


                    }
                }
            }
            //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO4444 '.$tipobono."' '#dev' > /dev/null & ");

            if ($tipobono == 8) {

                //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO2'."' '#dev' > /dev/null & ");
                //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC ENTRO22222'.$Proveedor->getAbreviado()."' '#dev' > /dev/null & ");
                switch ($Proveedor->getAbreviado()) {

                    case "IESGAMES":

                        /* Inserta detalles de bono para cada carton en base de datos utilizando PHP. */
                        foreach ($numberOfCartons as $key => $value) {
                            $BonoDetalle = new BonoDetalle();
                            $BonoDetalle->bonoId = $bonoId;
                            $BonoDetalle->tipo = "NUMBERCARTONS";
                            $BonoDetalle->moneda = $value->CurrencyId;
                            $BonoDetalle->valor = $value->Amount;
                            $BonoDetalle->usucreaId = 0;
                            $BonoDetalle->usumodifId = 0;
                            $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
                            $BonoDetalleMysqlDAO->insert($BonoDetalle);
                        }

                        if ($arrayfinal != "" && $arrayfinal != null) {
                            foreach ($arrayfinal as $key => $value) {


                                /* Inicializa variables $usuarioId, $estado, $valor y $valor_bono con valores específicos. */
                                $usuarioId = $value;
                                $estado = 'P';

                                $valor = '0';

                                $valor_bono = '0';


                                /* Se definen variables con valores iniciales en un script PHP. */
                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';

                                $mandante = '0';


                                /* inicializa variables y genera un código de ticket aleatorio de 4 caracteres. */
                                $usucreaId = '0';

                                $usumodifId = '0';

                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);

                                /* Inicializa variables y establece el ID de usuario en un objeto UsuarioBono. */
                                $apostado = '0';
                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;

                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);

                                /* establece propiedades de un objeto "UsuarioBono" con valores específicos. */
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);

                                /* Configura propiedades de un objeto UsuarioBono con valores proporcionados. */
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);

                                /* Se configura un objeto UsuarioBono y se inserta en la base de datos. */
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            }

                        } else {
                            for ($i = 0; $i < $MaxplayersCount; $i++) {

                                /* Se inicializan variables para usuario, estado y valores de bono. */
                                $usuarioId = 0;
                                $estado = 'L';

                                $valor = '0';

                                $valor_bono = '0';


                                /* Variables inicializadas en PHP, todas asignadas a valor 0 como base. */
                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';

                                $mandante = '0';


                                /* Inicializa variables y genera un código de ticket aleatorio de 4 caracteres. */
                                $usucreaId = '0';

                                $usumodifId = '0';

                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);

                                /* Inicializa variables y establece un usuario para gestionar bonos en un sistema. */
                                $apostado = '0';
                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;

                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);

                                /* establece propiedades de un objeto UsuarioBono utilizando diversos valores. */
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);

                                /* Configura propiedades de un objeto UsuarioBono con datos específicos de entrada. */
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);

                                /* Se insertan datos de UsuarioBono en la base de datos usando MySQL. */
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            }
                        }
                        break;
                    case "REDRAKE":


                        /* Inicializa un objeto y recorre productos de casino obteniendo sus identificadores. */
                        $REDRAKESERVICESBONUS = new REDRAKESERVICESBONUS();

                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;

                        }

                        /* Se crean objetos "ProductoMandante" y "Producto" utilizando datos específicos. */
                        $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                        $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                        $games = $producto->externoId;

                        foreach ($MaxPayout as $keyP => $valueP) { //Valor por ronda


                            /* asigna montos según la moneda en un contexto de rondas. */
                            $roundvalue = $valueP->Amount;

                            /* foreach ($MinimumAmount as $key => $value) { //lista de jugadores separado por comas
                                 if ($value->CurrencyId == $valueP->CurrencyId) {
                                     $awardedUsersIds = $value->Amount;
                                 }

                             }*/

                            foreach ($rounds as $key => $value) {  //Cantidad de rondas
                                if ($value->CurrencyId == $valueP->CurrencyId) {
                                    $round = $value->Amount;
                                }
                            }


                            /* asigna la cantidad máxima de días para freespins según la moneda. */
                            foreach ($FreeRoundsMaxDays as $key => $value) { //cantidad de días que dura el freespin
                                if ($value->CurrencyId == $valueP->CurrencyId) {
                                    $freeRoundsMaxDays = $value->Amount;
                                }
                            }
                            if ($roundvalue != "" && $ids != "" && $round != "" && $freeRoundsMaxDays != "") {


                                /* asigna identificadores, reemplaza comas y otorga un bono si es válido. */
                                $ids = 'Usuario' . $ids;

                                $ids = str_replace(',', ',Usuario', $ids);

                                $response2 = $REDRAKESERVICESBONUS->awardFRBonus($bonoId, $round, $roundvalue, $StartDate, $EndDate, $freeRoundsMaxDays, $ids, $games);

                                if ($response2["code"] === 0) {

                                    $currency = $valueP->CurrencyId;
                                    $status = "OK";
                                } else {
                                    /* asigna una moneda y un estado de error si se cumple una condición. */

                                    $currency = $valueP->CurrencyId;
                                    $status = "ERROR";
                                }


                                /* Concatena información sobre moneda y estado en una respuesta y registra un log. */
                                $respuestaGeneral = $respuestaGeneral . "Moneda: " . $currency . " Estado" . $status . " ";

                                $log = "";
                                $log = $log . "Respuesta REDRAKE" . time();

                                $log = $log . "\r\n" . "-------------------------" . "\r\n";

                                /* agrega un mensaje a un registro log con la fecha actual. */
                                $log = $log . ($respuestaGeneral);
                                //Save string to log, use FILE_APPEND to append.

                                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                            }
                        }


                        break;

                    case "7777GAMING":


                        /* Se crea un objeto de servicios y se extrae el ID de un producto de casino. */
                        $G7777GAMINGSERVICES = new G7777GAMINGSERVICES();

                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;
                        }
                        $productoMandante = new ProductoMandante("", $mandante, $Idgames);

                        /* Se crea un objeto 'Producto' y se obtiene su 'externoId'. */
                        $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                        $games = $producto->externoId;

                        foreach ($MaxPayout as $keyP => $valueP) { //Valor por ronda


                            /* Asignación de valores basados en condiciones de moneda en un bucle de rondas. */
                            $roundvalue = $valueP->Amount;

                            /* foreach ($MinimumAmount as $key => $value) { //lista de jugadores separado por comas
                                 if ($value->CurrencyId == $valueP->CurrencyId) {
                                     $awardedUsersIds = $value->Amount;
                                 }

                             }*/

                            foreach ($rounds as $key => $value) {  //Cantidad de rondas
                                if ($value->CurrencyId == $valueP->CurrencyId) {
                                    $round = $value->Amount;
                                }
                            }


                            /* asigna días de freespin según coincidencia de CurrencyId. */
                            foreach ($FreeRoundsMaxDays as $key => $value) { //cantidad de días que dura el freespin
                                if ($value->CurrencyId == $valueP->CurrencyId) {
                                    $freeRoundsMaxDays = $value->Amount;
                                }
                            }
                            if ($roundvalue != "" && $ids != "" && $round != "" && $freeRoundsMaxDays != "") {


                                /* realiza una llamada a un servicio de bonificación y verifica la respuesta. */
                                $ids = 'Usuario' . $ids;

                                $ids = str_replace(',', ',', $ids);

                                $response2 = $G7777GAMINGSERVICES->Bonus_($bonoId, $round, $roundvalue, $StartDate, $EndDate, $freeRoundsMaxDays, $ids, $games);

                                if ($response2["code"] === 0) {

                                    $currency = $valueP->CurrencyId;
                                    $status = "OK";
                                } else {
                                    /* Condición que asigna un valor de moneda y establece el estado como "ERROR". */

                                    $currency = $valueP->CurrencyId;
                                    $status = "ERROR";
                                }


                                /* Concatena información sobre moneda y estado, y genera un log para REDRAKE. */
                                $respuestaGeneral = $respuestaGeneral . "Moneda: " . $currency . " Estado" . $status . " ";

                                $log = "";
                                $log = $log . "Respuesta REDRAKE" . time();

                                $log = $log . "\r\n" . "-------------------------" . "\r\n";

                                /* añade una respuesta a un log diario mediante escritura en un archivo. */
                                $log = $log . ($respuestaGeneral);
                                //Save string to log, use FILE_APPEND to append.

                                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                            }
                        }
                        break;

                    case "PRAGMATIC":


                        /* Crea una instancia de PRAGMATICSERVICES y recorre los productos de casino. */
                        $PRAGMATICSERVICES = new PRAGMATICSERVICES();

                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;

                        }


                        /* Se inicializan objetos de producto y se procesan rondas con datos específicos. */
                        $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                        $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                        $games = $producto->externoId;


                        foreach ($rounds as $key => $value) {  //Cantidad de rondas

                            $round = $value->Amount;
                            $currency = $value->CurrencyId;

                        }


                        if ($ids != "" && $round != "") {


                            //$ids = str_replace(',',',Usuario',$ids);


                            /* convierte una fecha en timestamp y crea un servicio con respuesta verificada. */
                            $timestamp = strtotime($EndDate);

                            $response = $PRAGMATICSERVICES->CreateFRB($bonoId, $round, $currency, $timestamp, $ids, $games);

                            if ($response["error"] === 0) {

                                $currency = $value->CurrencyId;
                                $status = "OK";
                            } else {
                                /* asigna valores de moneda y estado en caso de error. */

                                $currency = $value->CurrencyId;
                                $status = "ERROR";
                            }

                            /* genera y guarda un log con la fecha y respuesta actual. */
                            $log = "";
                            $log = $log . "/" . time();

                            $log = $log . "\r\n" . "-------------------------" . "\r\n";
                            $log = $log . ($response);
                            //Save string to log, use FILE_APPEND to append.

                            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


                        }
                        break;

                    case "PLAYNGO":


                        /* Crea un array de identificadores de juegos a partir de productos de casino. */
                        $PLAYNGOSERVICES = new PLAYNGOSERVICES();

                        $games = array();
                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;
                            $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                            $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                            $gamesExId = $producto->externoId;
                            array_push($games, $gamesExId);
                        }


                        /* Asigna valores a variables desde un arreglo y una cadena de identidades. */
                        $roundsFree = $rounds[0]->Amount;
                        $users = explode(",", $ids);
                        $roundvalue = $MaxPayout[0]->Amount;

                        foreach ($users as $user) { // Bono por usuario

                            if ($roundvalue != "" && $user != "" && $roundsFree != "") {


                                /* Se añade una oferta de juego gratuito y se verifica el estado de respuesta. */
                                $response2 = $PLAYNGOSERVICES->AddFreegameOffers($bonoId, $roundsFree, $roundvalue, $EndDate, $user, $games, $inse);

                                if ($response2["code"] === 0) {
                                    $currency = $rounds[0]->CurrencyId;
                                    $status = "OK";
                                } else {
                                    /* Código que asigna un valor de divisa y un estado de error en condiciones específicas. */

                                    $currency = $rounds[0]->CurrencyId;
                                    $status = "ERROR";
                                }


                                /* Concatena información sobre moneda y estado, y registra la respuesta en un log. */
                                $respuestaGeneral = $respuestaGeneral . "Moneda: " . $currency . " Estado " . $status . " ";

                                $log = "";
                                $log = $log . "Respuesta REDRAKE" . time();

                                $log = $log . "\r\n" . "-------------------------" . "\r\n";

                                /* guarda una respuesta en un archivo de log diario. */
                                $log = $log . ($respuestaGeneral);
                                //Save string to log, use FILE_APPEND to append.

                                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                            }
                        }
                        break;

                    case "PLAYTECH":
                        //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC PLAYTECH2'."' '#dev' > /dev/null & ");


                        /* crea un array de IDs de juegos según diferentes proveedores de casino. */
                        $PLAYTECHSERVICES = new PLAYTECHSERVICES();

                        $Provider = $CasinoProvider[0]->Name;

                        $games = array();
                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;
                            $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                            $Producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                            $ProductoDetalle = new ProductoDetalle('', $Producto->productoId, 'GAMEID');
                            $prod = $ProductoDetalle->pValue;

                            $Provider = new Subproveedor($Producto->subproveedorId);

                            if ($Provider->tipo == 'LIVECASINO') {
                                $game = explode(";", $prod);
                                $gamesExId = $game[0];
                            } else {
                                $gamesExId = $Producto->externoId;
                            }
                            array_push($games, $gamesExId);
                        }


                        /* asigna valores de parámetros a variables específicas relacionadas con un juego. */
                        $GoldenChip = $params->GoldenChip;
                        $TemplateCode = $params->TemplateCode[0]->Amount;

                        $roundsFree = $rounds[0]->Amount;
                        $roundsValue = $MaxPayout[0]->Amount;
                        $users = explode(",", $ids);

                        /* Asignación de valores específicos si el código de plantilla coincide con '183826'. */
                        if ($TemplateCode == '183826') {
                            $roundsValue = 2;
                            $roundsFree = 5;
                        }

                        foreach ($users as $user) { // Bono por usuario

                            if ($user != "" && $roundsFree != "") {


                                /* Se solicitan giros gratis y se verifica el estado de la respuesta. */
                                $response2 = $PLAYTECHSERVICES->givefreespins($bonoId, $roundsFree, $roundsValue, $EndDate, $user, $games, $inse, $GoldenChip, $TemplateCode);

                                if ($response2["code"] === 0) {
                                    $currency = $rounds[0]->CurrencyId;
                                    $status = "OK";
                                } else {
                                    /* Asignación de variables "currency" y "status" en caso de error en la condición. */

                                    $currency = $rounds[0]->CurrencyId;
                                    $status = "ERROR";
                                }


                                /* Concatenación de datos de respuesta y registro de información para registro de auditoría. */
                                $respuestaGeneral = $respuestaGeneral . "ResponseCode: " . $response2["response_code"] . " Moneda: " . $currency . " Estado: " . $status . " ";

                                $log = "";
                                $log = $log . "\r\n" . "-------------------------" . "\r\n";
                                $log = $log . "Respuesta PLAYTECH" . time();
                                $log = $log . "\r\n" . "-------------------------" . "\r\n";

                                /* guarda información en un archivo de log, añadiendo datos diarios. */
                                $log = $log . ($respuestaGeneral);
                                //Save string to log, use FILE_APPEND to append.

                                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                            }
                        }
                        break;

                    case "MASCOT":
                        if ($arrayfinal != "" && $arrayfinal != null) {
                            foreach ($arrayfinal as $key => $value) {

                                /* recorre rondas, asigna valores y define variables para un usuario. */
                                foreach ($rounds as $key2 => $value2) {
                                    $apostado = $value2->Amount;
                                }
                                $usuarioId = $value;
                                $estado = 'A';

                                $valor = '0';


                                /* Inicializa variables de bono, base, error e ID externo en cero. */
                                $valor_bono = '0';

                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';


                                /* Variables inicializadas en PHP para representar IDs de mandante, creador y modificador. */
                                $mandante = '0';


                                $usucreaId = '0';

                                $usumodifId = '0';


                                /* Se genera un código ticket con un prefijo y longitud específica. */
                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);

                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;


                                /* Se crea un objeto UsuarioBono y se configuran sus propiedades. */
                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);

                                /* establece diversos atributos para un objeto UsuarioBono. */
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);

                                /* Se configuran atributos de un objeto UsuarioBono con valores específicos. */
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);


                                /* Crea una instancia de UsuarioBonoMySqlDAO y añade un UsuarioBono. */
                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                            }

                        } else {

                            for ($i = 0; $i < $MaxplayersCount; $i++) {

                                /* Variables inicializadas para un usuario y su estado, además de valores para bonificaciones. */
                                $usuarioId = 0;
                                $estado = 'L';

                                $valor = '0';

                                $valor_bono = '0';


                                /* inicializa variables con valores cero para su uso posterior. */
                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';

                                $mandante = '0';


                                /* inicializa variables y genera un código único de 4 caracteres. */
                                $usucreaId = '0';

                                $usumodifId = '0';

                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);

                                /* inicializa variables y configura un objeto UsuarioBono con un identificador de usuario. */
                                $apostado = '0';
                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;

                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);

                                /* Se asignan valores a las propiedades del objeto UsuarioBono. */
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);

                                /* Configura propiedades del objeto UsuarioBono con datos proporcionados. */
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);

                                /* Configura un objeto UsuarioBono y lo inserta en la base de datos usando DAO. */
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            }
                        }

                        /* Instancia de MASCOTSERVICES y recorrido de productos de casino para obtener identificadores. */
                        $MASCOTSERVICES = new MASCOTSERVICES();

                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;

                        }


                        /* crea objetos de productos y procesa rondas con sus respectivas monedas. */
                        $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                        $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                        $games = $producto->externoId;


                        foreach ($rounds as $key => $value) {  //Cantidad de rondas

                            $round = $value->Amount;
                            $currency = $value->CurrencyId;

                        }

                        if ($ids != "" && $round != "") {


                            //$ids = str_replace(',',',Usuario',$ids);

                            //$timestamp = strtotime($EndDate);


                            /* Se establece un bono y se verifica su identificación y estado. */
                            $response2 = $MASCOTSERVICES->SetBonus($bonoId, $round, $currency, $ids, $games);


                            //print_r($response2);

                            if ($response2->result->Bonus->Id === $bonoId) {

                                $currency = $value->CurrencyId;
                                $status = "OK";
                            } else {
                                /* establece una variable de moneda y un estado de error si se cumple una condición. */

                                $currency = $value->CurrencyId;
                                $status = "ERROR";
                            }


                            /* Decodifica una respuesta JSON y registra la hora actual en formato de texto. */
                            $response2 = json_decode($response2);

                            $log = "";
                            $log = $log . "/" . time();

                            $log = $log . "\r\n" . "-------------------------" . "\r\n";

                            /* guarda registros en un archivo, añadiendo información con cada ejecución. */
                            $log = $log . ($response2);
                            //Save string to log, use FILE_APPEND to append.

                            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


                        }


                        break;


                    case "TOMHORN":


                        /* Se instancia un objeto y se itera sobre productos de casino. */
                        $TOMHORNSERVICES = new TOMHORNSERVICES();

                        foreach ($CasinoProduct as $key => $value) {
                            $Idgames = $value->Id;

                        }

                        /* Se crean objetos ProductoMandante y Producto; se obtiene externoId del producto. */
                        $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                        $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                        $games = $producto->externoId;


                        foreach ($MaxPayout as $keyP => $valueP) { //Valor por ronda


                            /* Extrae y asigna valores de cantidad y moneda de objetos en bucles. */
                            $roundvalue = $valueP->Amount;

                            /*  foreach ($MinimumAmount as $key => $value) { //lista de jugadores separado por comas
                                  if ($value->CurrencyId == $valueP->CurrencyId) {
                                      $awardedUsersIds = $value->Amount;

                                  }

                              }*/


                            foreach ($rounds as $key => $value) {  //Cantidad de rondas
                                if ($value->CurrencyId == $valueP->CurrencyId) {
                                    $round = $value->Amount;

                                }
                            }


                            /* Itera sobre FreeRoundsMaxDays para encontrar el monto según CurrencyId coincidente. */
                            foreach ($FreeRoundsMaxDays as $key => $value) { //cantidad de días que dura el freespin
                                if ($value->CurrencyId == $valueP->CurrencyId) {
                                    $freeRoundsMaxDays = $value->Amount;
                                }
                            }


                            if ($roundvalue != "" && $ids != "" && $round != "") {


                                /* Código para crear "freespins" y verificar su estado de respuesta. */
                                $currency = $valueP->CurrencyId;


                                $response2 = $TOMHORNSERVICES->CreateFreespin($producto, $currency, $Name, $round, $roundvalue, $StartDate, $EndDate, $ids, $bonoId, $mandante);


                                if ($response2->response->Code === 0) {

                                    $currency = $valueP->CurrencyId;
                                    $status = "OK";
                                } else {
                                    /* asigna un valor a la moneda y establece el estado como "ERROR". */


                                    $currency = $valueP->CurrencyId;
                                    $status = "ERROR";
                                }

                                /* Concatena información de moneda y estado en una variable de respuesta y log. */
                                $respuestaGeneral = $respuestaGeneral . "Moneda: " . $currency . " Estado: " . $status . " ";

                                $log = "";
                                $log = $log . "Respuesta TOMHORN" . time();

                                $log = $log . "\r\n" . "-------------------------" . "\r\n";

                                /* registra datos JSON en un archivo log con fecha actual. */
                                $log = $log . json_encode($response2);
                                //Save string to log, use FILE_APPEND to append.

                                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                            }
                        }
                        break;
                    case "CTGAMING":
                        if ($arrayfinal != "" && $arrayfinal != null) {
                            foreach ($arrayfinal as $key => $value) {

                                /* Recorre un arreglo, asigna valores y define variables para el usuario y estado. */
                                foreach ($rounds as $key2 => $value2) {
                                    $apostado = $value2->Amount;
                                }
                                $usuarioId = $value;
                                $estado = 'A';

                                $valor = '0';


                                /* Inicializa variables con valores predeterminados para cálculos posteriores en el código. */
                                $valor_bono = '0';

                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';


                                /* Variables inicializadas con valor '0' para mandante, usuario creador y modificador. */
                                $mandante = '0';


                                $usucreaId = '0';

                                $usumodifId = '0';


                                /* genera un array y crea un código de ticket con un prefijo. */
                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);

                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;


                                /* Se crea un objeto UsuarioBono y se configuran sus propiedades. */
                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);

                                /* Se asignan varios valores a las propiedades del objeto UsuarioBono. */
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);

                                /* configura propiedades de un objeto UsuarioBono con diferentes valores. */
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);


                                /* Se crea un DAO para insertar un objeto UsuarioBono en la base de datos. */
                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                            }

                        } else {

                            for ($i = 0; $i < $MaxplayersCount; $i++) {

                                /* Define variables para usuario, estado, valor y bono en un sistema. */
                                $usuarioId = 0;
                                $estado = 'L';

                                $valor = '0';

                                $valor_bono = '0';


                                /* Se inicializan variables con valores predeterminados en PHP. */
                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';

                                $mandante = '0';


                                /* inicializa variables y genera un código de ticket de 4 caracteres. */
                                $usucreaId = '0';

                                $usumodifId = '0';

                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);

                                /* Se establece un bono para un usuario específico con un código y parámetros iniciales. */
                                $apostado = '0';
                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;

                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);

                                /* Código que configura propiedades de un objeto UsuarioBono con valores específicos. */
                                $UsuarioBono->setBonoId($bonoId);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);

                                /* Se establecen propiedades del objeto UsuarioBono con datos específicos. */
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);

                                /* Se establece un bono de usuario y se inserta en la base de datos. */
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            }
                        }


                        break;
                }


            }
            if ($darAUsuariosEspecificos) {


                if ($tipobono != 2) {

                    for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                            /* Se consulta información de usuarios y su ubicación geográfica mediante varias tablas. */
                            $BonoInterno = new BonoInterno();

                                /* Se asigna un ID de jugador y se ejecuta una consulta para obtener datos del usuario. */
                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.moneda,usuario.mandante FROM registro
  INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                            $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                            $dataUsuario = $Usuario;

                            /* Verifica si el mandante existe y asigna detalles a un bono. */
                            if ($dataUsuario[0]->{'usuario.mandante'} != "") {
                                $detalles = array(
                                    "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
                                    "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                                    "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                                    "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'},
                                    "ValorDeposito" => 0

                                );
                                $detalles = json_decode(json_encode($detalles));

                                $respuesta = $BonoInterno->agregarBonoFree($bonoId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                            }

                        }

                    }
                }
            }


            /* verifica un estado y realiza una transacción si no hay error. */
            if ($status != "ERROR") {
                $transaccion->commit();

            }

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

            /* La línea obtiene una transacción mediante el método getTransaction del DAO correspondiente. */
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


            if ($FreeSpinsTotalCount != "" && $Prefix != "") {

                for ($i = 1; $i <= oldCount($jugadoresAsignarFinal); $i++) {


                    /* Código para consultar información del usuario uniendo varias tablas en SQL. */
                    $BonoInterno = new BonoInterno();

                    /* asigna un ID a un usuario y realiza una consulta a la base de datos. */
                    $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                    $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                    $dataUsuario = $Usuario;

                    /* Crea un array con detalles del usuario sobre país, departamento y ciudad. */
                    $detalles = array(
                        "PaisUSER" => $dataUsuario[0]->pais_id,
                        "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                        "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                    );

                    /* Convierte detalles a objeto y agrega un bono interno para un jugador específico. */
                    $detalles = json_decode(json_encode($detalles));


                    $respuesta = $BonoInterno->agregarBonoFree($bonoId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);


                }
            }
            if ($MaxplayersCount != "" && $Prefix != "") {
                if ($tipobono == 2) {

                    for ($i = 0; $i < oldCount($jugadoresAsignarFinal); $i++) {

                        if ($jugadoresAsignarFinal[$i]["Id"] != "") {

                            /* Código para consultar información de usuario desde múltiples tablas relacionadas en SQL. */
                            $BonoInterno = new BonoInterno();

                            /* Código para asignar y ejecutar una consulta SQL relacionada con usuarios. */
                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $jugadoresAsignarFinal[$i]["Id"] . "'";

                            $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                            $dataUsuario = $Usuario;

                            /* crea un array con información de ubicación del usuario. */
                            $detalles = array(
                                "PaisUSER" => $dataUsuario[0]->pais_id,
                                "DepartamentoUSER" => $dataUsuario[0]->depto_id,
                                "CiudadUSER" => $dataUsuario[0]->ciudad_id,

                            );

                            /* agrega un bono a un jugador utilizando detalles y datos del usuario. */
                            $detalles = json_decode(json_encode($detalles));


                            $respuesta = $BonoInterno->agregarBonoFree($bonoId, $jugadoresAsignarFinal[$i]["Id"], $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, $jugadoresAsignarFinal[$i]["Code"], $transaccion);

                        }

                    }
                }
            }

//$transaccion->commit();

            /* construye una respuesta con detalles sobre un bono y errores. */
            $response["idBonus"] = $bonoId;
            $response["HasError"] = false;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = $respuestaGeneral; //variable de moneda concatenado
            $response["ModelErrors"] = [];
            $response["Result"] = array();

            return $response;
        }
    }

    /* confirma y guarda todos los cambios realizados en una transacción de base de datos. */
    $transaccion->commit();
} catch (Exception $e) {
    /* Maneja excepciones, registrando errores y preparando una respuesta estructurada. */

    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' ".'CreateBonusFreeSpinEXEC '.$e->getMessage()."' '#dev' > /dev/null & ");
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["Result"] = array();
}
