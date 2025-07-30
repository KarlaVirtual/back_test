<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\dto\AuditoriaGeneral;

/**
 * Setting/UpdatePartnerSettings
 *
 * Actualizar ajustes de partner
 *
 * @param no
 *
 * @return
 *{"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * @OA\Post(path="apipv/Setting/UpdatePartnerSettings", tags={"Setting"}, description = "",
 * @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="Country",
 *                   description="Country",
 *                   type="string",
 *                   example= ""
 *               ),
 *                 @OA\Property(
 *                   property="Partner",
 *                   description="Partner",
 *                   type="string",
 *                   example= ""
 *               )
 *             )
 *         )
 *     ),
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               )
 *             )
 *         )
 *     )
 * )
 */

/**
 * Propósito: Este recurso sirve para guerdad las configuraciones de cada partner con su respectivo pais
 * Descripción de variables:
 *    - FirstDeposit: Esta variable activa o desactiva la opcion para validar si un usuario necesita un primer deposito para poder realizar un retirar
 *    - requiresVerificationForLoyalty: Esta variable confirma si el estado de verificación es requerida en el área de lealtad
 *    - VerifiedMail: Esta variable activa o desactiva la opcion para validar si un usuario necesita tener el correo verfiicado para poder realizar un retiro
 *    - VerifiedPhone: Esta variable activa o desactiva la opcion para validar si un usuario necesito tener el celular verificado para poder realizar un retiro
       *     - IsActivateOtpNotesPuntoDeVenta: Esta opcion activa o inactiva la validación de retiros con codigo OTP a los usuarios online para puntos de venta.
       *     - IsActivateOtpNotesCuentaBancaria: Esta opcion activa o inactiva la validación de retiros con codigo OTP a los usuarios online para cuentas bancarias.
 *     - OtpNotesTime: Esta variable guarda en microsegundos el tiempo en el que expira el codigo OTP
 *    - ActiveWithdrawExpiration: Esta variable activa la opcion para expirar notas de retiros que lleven tiempo aprovadas pero no pagas
 *    - WithdrawExpirationTime: Esta variable es la cantidad de dias definidas para que se expiren las notas de retiro aprovadas pero no pagadas
 **/

$Vertical = $params;
$BetShop = $params;
$Cashiers = $params;
$OnlineUsers = $params;
$codeQR = $params->codeQR;

$SportBetPv = $BetShop->SportsBetsPv; //punto de venta deportivas
$PaymentOfWithdrawalNotes = $BetShop->PayRetirementNotesPv; //punto de venta notas de retiro
$DepositsPv = $BetShop->PayDepositPv; //punto de venta deposito
$VirtualBetsPv = $BetShop->VirtualBetsPv; //punto de venta virtuales
$PayAwardPv = $BetShop->PayAwardsPv;

$SportBetCashiers = $Cashiers->SportsBetsAtm;  //cajeros deportivas
$PaymentOfWithdrawalNotesCashier = $Cashiers->PayRetirementNotesAtm; //cajeros pago nota de retiro
$DepositsCashiers = $Cashiers->PayDepositAtm; //cajeros venta depositos
$PayAwardAtm = $Cashiers->PayAwardsAtm; //pago de premios
$VirtualBetsAtm = $Cashiers->VirtualBetsAtm; // cajero virtuales

$SportBetOnlineUser = $OnlineUsers->SportsBetsUserOnline;  // usuarios online
$CasinoBetsOnlineUsers = $OnlineUsers->CasinoBetsUserOnline; // usuarios online
$LiveCasinoUserOnline = $OnlineUsers->LiveCasinoUserOnline; // usuarios online
$VirtualBetsOnlineUsers = $OnlineUsers->VirtualBetsUserOnline; // usuarios online
$PokerUserOnline = $OnlineUsers->PokerUserOnline; // usuarios online
$RetirementNotesUserOnline = $OnlineUsers->RetirementNotesUserOnline; // usuarios online
$DepositUserOnline = $OnlineUsers->DepositUserOnline; // usuarios online
$HipicaUserOnline = $OnlineUsers->HipicaUserOnline; // usuarios online
$CombinationOfWallets = $params->CombinationOfWallets; // Combinación de wallets

$LoyaltyBetCasinoLive = $params->LoyaltyBetCasinoLive;
$LoyaltyBetVirtuals = $params->LoyaltyBetVirtuals;


if ($SportBetPv != "" || $PaymentOfWithdrawalNotes != "" || $DepositsPv != "" || $VirtualBetsPv != "" || $PayAwardPv != "" || $SportBetCashiers != "" || $PaymentOfWithdrawalNotesCashier != "" || $DepositsCashiers != "" || $PayAwardAtm != "" || $VirtualBetsAtm != "" || $SportBetOnlineUser != "" || $CasinoBetsOnlineUsers != "" || $LiveCasinoUserOnline != "" || $VirtualBetsOnlineUsers != "" || $PokerUserOnline != "" || $RetirementNotesUserOnline != "" || $DepositUserOnline != "" || $HipicaUserOnline != "") {

    if ($codeQR == '') {
        throw new Exception("Inusual Detected", "110012");
    } else {
        $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);
        $Usuario2 = new Usuario($UsuarioMandante2->usuarioMandante);
        $Google = new GoogleAuthenticator();
        $returnCodeGoogle = $Google->verifyCode($Usuario2->saltGoogle, $codeQR);
        if (!$returnCodeGoogle) {
            throw new Exception("Inusual Detected", "11");
        }
    }
}


try {
    $Country = $params->Country;
    $Partner = $params->Partner;

    if ($Partner == "") {
        throw new Exception("Inusual Detected", "11");

    }

    $DepositRequests = $params;
    $General = $params;
    $Security = $params;
    $WithdrawalRequests = $params;
    $GeneralSettings = $params;
    $LoyaltyLevel = $params;
    $LoyaltyTypes = $params;


    $Profile = $Vertical->Profile;

    $DRequestMaxAmount = $DepositRequests->RequestMaxAmount;
    $DRequestMinAmount = $DepositRequests->RequestMinAmount;
    $DefaultAmountDeposits = $params->DefaultAmountDeposits;
    $SportBetPv = $BetShop->SportsBetsPv; //punto de venta deportivas
    $PaymentOfWithdrawalNotes = $BetShop->PayRetirementNotesPv; //punto de venta notas de retiro
    $DepositsPv = $BetShop->PayDepositPv; //punto de venta deposito
    $VirtualBetsPv = $BetShop->VirtualBetsPv; //punto de venta virtuales
    $PayAwardPv = $BetShop->PayAwardsPv;
    $TaxPayments = $BetShop->TaxPayments;

    $SportBetCashiers = $Cashiers->SportsBetsAtm;  //cajeros deportivas
    $PaymentOfWithdrawalNotesCashier = $Cashiers->PayRetirementNotesAtm; //cajeros pago nota de retiro
    $DepositsCashiers = $Cashiers->PayDepositAtm; //cajeros venta depositos
    $PayAwardAtm = $Cashiers->PayAwardsAtm; //pago de premios
    $VirtualBetsAtm = $Cashiers->VirtualBetsAtm; // cajero virtuales

    $SportBetOnlineUser = $OnlineUsers->SportsBetsUserOnline;  // usuarios online
    $CasinoBetsOnlineUsers = $OnlineUsers->CasinoBetsUserOnline; // usuarios online
    $LiveCasinoUserOnline = $OnlineUsers->LiveCasinoUserOnline; // usuarios online
    $VirtualBetsOnlineUsers = $OnlineUsers->VirtualBetsUserOnline; // usuarios online
    $PokerUserOnline = $OnlineUsers->PokerUserOnline; // usuarios online
    $RetirementNotesUserOnline = $OnlineUsers->RetirementNotesUserOnline; // usuarios online
    $DepositUserOnline = $OnlineUsers->DepositUserOnline; // usuarios online
    $HipicaUserOnline = $OnlineUsers->HipicaUserOnline; // usuarios online


    // hasta acá van las nuevas variables

    $Address = $General->Address;
    $CompanyName = $General->CompanyName;
    $Email = $General->Email;
    $Phone = $General->Phone;

    $DaysNotifyBeforePasswordExpire = $Security->DaysNotifyBeforePasswordExpire;
    $UserPasswordExpireDays = $Security->UserPasswordExpireDays;
    $BoPasswordExpireDays = $Security->BoUserPasswordExpireDays;
    $UserPasswordMinLength = $Security->UserPasswordMinLength;
    $UserTempPasswordExpireDays = $Security->UserTempPasswordExpireDays;
    $UserWrongLoginAttempts = $Security->UserWrongLoginAttempts;
    $EmailWrongLoginAttempts = $Security->EmailWrongLoginAttempts;

    // enviar plantilla de marketing despues de fallar ingreso maximo de veces

    $MaxActiveRequests = $WithdrawalRequests->MaxActiveRequests;
    $MaxRequestsPerDay = $WithdrawalRequests->MaxRequestsPerDay;
    $MinAmountWithdrawKasnet = $WithdrawalRequests->MinAmountWithdrawKasnet;
    $MaxAmountWithdrawKasnet = $WithdrawalRequests->MaxAmountWithdrawKasnet;
    $RequestMaxAmount = $WithdrawalRequests->RequestMaxAmountWithdraw;
    $RequestMinAmount = $WithdrawalRequests->RequestMinAmountWithdraw;
    $RequestMinAmountWithdrawBetShop = $WithdrawalRequests->RequestMinAmountWithdrawBetShop;
    $RequestMinAmountWithdrawBankAccount = $WithdrawalRequests->RequestMinAmountWithdrawBankAccount;
    $RequestMaxAmountWithdrawBetShop = $WithdrawalRequests->RequestMaxAmountWithdrawBetShop;


    /* Asignación de valores de configuración a variables relacionadas con impuestos y premios. */
    $TaxWithdrawBalanceAward = $GeneralSettings->TaxWithdrawBalanceAward;

    $TaxWithdrawBalanceAward2 = $GeneralSettings->TaxWithdrawBalanceAward2;
    $TaxWithdrawBalanceDeposit = $GeneralSettings->TaxWithdrawBalanceDeposit;
    $BetTaxValue = $GeneralSettings->BetTaxValue;
    $DepositTaxValue = $GeneralSettings->DepositTaxValue;

    /* asigna configuraciones de impuestos y niveles de lealtad a variables. */
    $TaxRegulator = $GeneralSettings->TaxRegulator;
    $TaxBetPayPrize = $GeneralSettings->TaxBetPayPrize;

    $PointsLevelOne = $LoyaltyLevel->PointsLevelOne;
    $PointsLevelTwo = $LoyaltyLevel->PointsLevelTwo;
    $PointsLevelThree = $LoyaltyLevel->PointsLevelThree;

    /* Asignación de puntos a diferentes niveles de lealtad desde una instancia de LoyaltyLevel. */
    $PointsLevelFour = $LoyaltyLevel->PointsLevelFour;
    $PointsLevelFive = $LoyaltyLevel->PointsLevelFive;
    $PointsLevelSix = $LoyaltyLevel->PointsLevelSix;
    $PointsLevelSeven = $LoyaltyLevel->PointsLevelSeven;
    $PointsLevelEight = $LoyaltyLevel->PointsLevelEight;
    $PointsLevelNine = $LoyaltyLevel->PointsLevelNine;

    /* Se asignan valores de puntos y tipos de lealtad a variables. */
    $PointsLevelTen = $LoyaltyLevel->PointsLevelTen;

    $LoyaltyDeposit = $LoyaltyTypes->LoyaltyDeposit;
    $LoyaltyBettingSportsSimple = $LoyaltyTypes->LoyaltyBettingSportsSimple;
    $LoyaltyBettingSportsCombinedTwo = $LoyaltyTypes->LoyaltyBettingSportsCombinedTwo;
    $LoyaltyBettingSportsCombinedThree = $LoyaltyTypes->LoyaltyBettingSportsCombinedThree;

    /* Se asignan valores de tipo lealtad a variables específicas en un objeto. */
    $LoyaltyBettingSportsCombinedFour = $LoyaltyTypes->LoyaltyBettingSportsCombinedFour;
    $LoyaltyBettingSportsCombinedFive = $LoyaltyTypes->LoyaltyBettingSportsCombinedFive;
    $LoyaltyBettingSportsCombinedSix = $LoyaltyTypes->LoyaltyBettingSportsCombinedSix;
    $LoyaltyBettingSportsCombinedSeven = $LoyaltyTypes->LoyaltyBettingSportsCombinedSeven;
    $LoyaltyBettingSportsCombinedEight = $LoyaltyTypes->LoyaltyBettingSportsCombinedEight;
    $LoyaltyBettingSportsCombinedNine = $LoyaltyTypes->LoyaltyBettingSportsCombinedNine;

    /* Asigna valores de tipos de lealtad a variables en un sistema de apuestas. */
    $LoyaltyBettingSportsCombinedTen = $LoyaltyTypes->LoyaltyBettingSportsCombinedTen;
    $SportsAwardLoyalty = $LoyaltyTypes->SportsAwardLoyalty;
    $LoyaltyBetCasino = $LoyaltyTypes->LoyaltyBetCasino;
    $CasinoAwardLoyalty = $LoyaltyTypes->CasinoAwardLoyalty;
    $LoyaltyWithdrawal = $LoyaltyTypes->LoyaltyWithdrawal;
    $LoyaltyBaseValue = $LoyaltyTypes->LoyaltyBaseValue;

    /* Se asignan variables de configuración de lealtad y ajustes generales fiscales. */
    $LoyaltyExpirationDate = $LoyaltyTypes->LoyaltyExpirationDate;
    $LoyaltyGiftMaxOffers = $LoyaltyTypes->LoyaltyGiftMaxOffers;

    $TaxBetShopPhysical = $GeneralSettings->TaxBetShopPhysical;
    $TaxPrizeBetShop = $GeneralSettings->TaxPrizeBetShop;


    $TaxWithdrawBalanceAwardFrom = $GeneralSettings->TaxWithdrawBalanceAwardFrom;

    /* extrae configuraciones generales de una variable llamada $GeneralSettings. */
    $TaxWithdrawBalanceDepositFrom = $GeneralSettings->TaxWithdrawBalanceDepositFrom;
    $TaxRegulatorFrom = $GeneralSettings->TaxRegulatorFrom;
    $MaxAccountsBank = $GeneralSettings->MaxAccountsBank;

    $RequireActiveRegister = $GeneralSettings->ActivateRegisterUser;
    $MinPercentageWagered = $GeneralSettings->MinPercentageWagered;


    /* Asignación de configuraciones generales a variables específicas relacionadas con la contabilidad y alertas. */
    $DaysAlertChangePassword = $GeneralSettings->DaysAlertChangePassword;


    $AccountingAccountsAwardsTicketsStores = $GeneralSettings->AccountingAccountsAwardsTicketsStores;
    $AccountingAccountsBetsTicketsStores = $GeneralSettings->AccountingAccountsBetsTicketsStores;
    $AccountingAccountsPaymentsWithdrawal = $GeneralSettings->AccountingAccountsPaymentsWithdrawal;

    /* Asignación de configuraciones generales a variables para su uso posterior en el sistema. */
    $AccountingAccountsRecharge = $GeneralSettings->AccountingAccountsRecharge;

    $Liquidations = $GeneralSettings->Liquidations;
    $AutomaticallyActive = strtolower($GeneralSettings->AutomaticallyActive);

    $ActiveRegistrationDeposit = $GeneralSettings->ActiveRegistrationDeposit;

    /* Asignación de configuraciones generales a variables relacionadas con cuentas y depósitos. */
    $AccountVerificationDeposit = $GeneralSettings->AccountVerificationDeposit;
    $ActiveRegistrationWithdraw = $GeneralSettings->ActiveRegistrationWithdraw;
    $AccountVerificationWithdraw = $GeneralSettings->AccountVerificationWithdraw;

    $ApproveBankAccounts = $GeneralSettings->ApproveBankAccounts;

    $VerificaFiltro = $GeneralSettings->VerificaFiltro;

    /* Asignación de variables desde propiedades de la clase GeneralSettings. */
    $VerificaFiltroPV = $GeneralSettings->VerificaFiltroPV;
    $VerificaDoc = $GeneralSettings->VerificaDoc;
    $VerificaDocPV = $GeneralSettings->VerificaDocPV;
    $SetMins = $GeneralSettings->SetMins;
    $NumRechazos = $GeneralSettings->NumRechazos;
    $LevelName = $GeneralSettings->LevelName;

    /* Obtiene configuraciones generales sobre SMS, pop-up, email e inbox, y tipo de registro. */
    $IsActiveSms = $GeneralSettings->IsActiveSms;
    $IsActivePopUp = $GeneralSettings->IsActivePopUp;
    $IsActiveEmail = $GeneralSettings->IsActiveEmail;
    $IsActiveInbox = $GeneralSettings->IsActiveInbox;

    $TypeRegister = strtolower($GeneralSettings->TypeRegister);


    /* Se asignan límites de depósito desde la configuración general a variables locales. */
    $LimitDepositDayDefault = $GeneralSettings->LimitDepositDayDefault;
    $LimitDepositMonthDefault = $GeneralSettings->LimitDepositMonthDefault;
    $LimitDepositWeekDefault = $GeneralSettings->LimitDepositWeekDefault;

    $LimitDepositDayGlobal = $GeneralSettings->LimitDepositDayGlobal;
    $LimitDepositMonthGlobal = $GeneralSettings->LimitDepositMonthGlobal;

    /* Variables que configuran límites y porcentajes para depósitos y retiradas en un sistema. */
    $LimitDepositWeekGlobal = $GeneralSettings->LimitDepositWeekGlobal;
    $LimitHoursCancelDeposit = $GeneralSettings->LimitHoursCancelDeposit;
    $LimitCancelDepositExecutions = $GeneralSettings->LimitCancelDepositExecutions;
    $MaximumAmountDailyWithdrawalsClient = $GeneralSettings->MaximumAmountDailyWithdrawalsClient;
    $PercentDepositValue = $params->PercentDepositValue;
    $PercentRetirementValue = $params->PercentRetirementValue;

    /* Asignación de parámetros de porcentajes para diferentes categorías de apuestas y premios. */
    $PercentValueSportsBets = $params->PercentValueSportsBets;
    $PercentValueNonSportBets = $params->PercentValueNonSportBets;
    $PercentValueSportsAwards = $params->PercentValueSportsAwards;
    $PercentValueNonSportsAwards = $params->PercentValueNonSportsAwards;
    $PercentValueSportsBonds = $params->PercentValueSportsBonds;
    $PercentValueNonSportsBounds = $params->PercentValueNonSportsBounds;

    /* Se asignan valores de parámetros a variables para configurar penalizaciones y sesiones. */
    $PercentValueTickets = $params->PercentValueTickets;
    $PenaltyWithdrawalBalanceWithdrawal = $params->PenaltyWithdrawalBalanceWithdrawal;
    $ApproveChangesInformation = $params->ApproveChangesInformation;
    $PenaltyWithdrawalBalanceRecharge = $params->PenaltyWithdrawalBalanceRecharge;
    $MinimumWithdrawalBalanceWithdrawalsPenalty = $params->MinimumWithdrawalBalanceWithdrawalsPenalty;
    $SessionInativityLength = $params->SessionInativityLength;

    /* Asignación de parámetros relacionados con comisiones y balances en un sistema de apuestas. */
    $TypeCommissionBetshop = $params->TypeCommissionBetshop;
    $SessionLength = $params->SessionLength;
    $TypeCommissionSettlements = $params->TypeCommissionSettlements;
    $MinimumWithdrawalRefillsPenalty = $params->MinimumWithdrawalRefillsPenalty;
    $BonusesBalance = $params->BonusesBalance;
    $WithdrawalsBalance = $params->WithdrawalsBalance;

    /* asigna valores de parámetros a variables relacionadas con balances y proveedores. */
    $RechargeBalance = $params->RechargeBalance;
    $Contingency = $params->Contingency;
    $FreebetBalance = $params->FreebetBalance;
    $ProviderSMS = $params->ProviderSMS;
    $ProviderCRM = $params->ProviderCRM;
    $ProviderVerification = $params->ProviderVerification;

    /* asigna valores de parámetros a variables específicas para su procesamiento posterior. */
    $ProviderEmail = $params->ProviderEmail;
    $ProviderSignature = $params->ProviderSignature;
    $ProviderCPF = $params->ProviderCPF;
    $NewReferredAwards = $params->ReferredAwards;
    $ExcludedCategoriesMinBetReferred = $params->ExcludedCategoriesMinBetReferred;
    $AcceptReferred = $params->AcceptReferred;

    /* Se asignan parámetros a variables para gestionar configuraciones y verificaciones en un programa. */
    $UrlLanding = $params->UrlLanding;
    $ReferentConditions = $params->ReferentConditions;
    $ReferredMinSelValue = $params->ReferredMinSelValue;
    $newProgramState = $AcceptReferred;
    $DepositPhoneVerification = $params->DepositPhoneVerification;
    $SportBetsPhoneVerification = $params->SportBetsPhoneVerification;

    /* Variables que almacenan la verificación y selección de bonos en un casino. */
    $CasinoBetsPhoneVerification = $params->CasinoBetsPhoneVerification;
    $WithdrawalPhoneVerification = $params->WithdrawalPhoneVerification;
    $LiveCasinoPhoneVerification = $params->LiveCasinoPhoneVerification;
    $VirtualPhoneVerification = $params->VirtualPhoneVerification;
    $LandingFreeCasinoBonusesSelected = $params->LandingFreeCasinoBonusesSelected;
    $LandingFreeBetBonusesSelected = $params->LandingFreeBetBonusesSelected;

    /* Asignación de parámetros a variables relacionadas con premios y retiro en un sistema. */
    $AwardLimitFulfillmentDayReferred = $params->AwardLimitFulfillmentDayReferred;
    $AwardLimitRedemptionDayReferent = $params->AwardLimitRedemptionDayReferent;
    $ActiveWithdrawExpiration = $params->ActiveWithdrawExpiration;
    $WithdrawExpirationTime = $params->WithdrawExpirationTime;
    $FirstDeposit = $params->FirstDeposit;
    $VerifiedMail = $params->VerifiedMail;

    /* Asigna parámetros relacionados con la verificación de teléfono y activación de notas OTP. */
    $VerifiedPhone = $params->VerifiedPhone;
    $OtpNotesTime = $params->OtpNotesTime;
    $IsActivateRiskStatus = $params->IsActivateRiskStatus;
    $IsActivateOtpNotesPuntoDeVenta = $params->IsActivateOtpNotesPuntoDeVenta;
    $IsActivateOtpNotesCuentaBancaria = $params->IsActivateOtpNotesCuentaBancaria;
    $IsActivateOtpNotesSmsCB = $params->IsActivateOtpNotesSmsCB;

    /* obtiene valores de parámetros relacionados con la activación de OTP. */
    $IsActivateOtpNotesEmailCB = $params->IsActivateOtpNotesEmailCB;
    $IsActivateOtpNotesSmsPV = $params->IsActivateOtpNotesSmsPV;
    $IsActivateOtpNotesEmailPV = $params->IsActivateOtpNotesEmailPV;
    $requiresVerificationForLoyalty = $params->RequiresVerificationForLoyalty;
    $ExchangeOfTheSameGiftEveryXTime = $params->ExchangeOfTheSameGiftEveryXTime; // "A" para establecer el tiempo minimo entre canje de un mismo regalo
    $TimeForExchange = $params->TimeForExchange; // Tiempo minimo para el canje de un mismo regalo en la tienda de lealtad (valor entero)
    $TypeOfTime = $params->TypeOfTime; // Tipo de tiempo minimo (D/H/M) para el canje de regalo en la tienda de lealtad
    $MinimumTimeBetweenAnyExchanges = $params->MinimumTimeBetweenAnyExchanges; // Tiempo minimo entre canje de cualquier regalo (valor entero)
    $TimeForExchangeGeneral = $params->TimeForExchangeGeneral; // Tiempo minimo para el canje de cualquier regalo en la tienda de lealtad (valor entero)
    $TypeOfTimeGeneral = $params->TypeOfTimeGeneral; // Tipo de tiempo minimo (D/H/M) para el canje de cualquier regalo en la tienda de lealtad
    $CommissionFreeTransactions = $params->CommissionFreeTransactions;
    $RestrictionTime = $params->RestrictionTime;

    /* Se crea un objeto Mandante y se configuran email y teléfono. */
    $Mandante = new Mandante($Partner);

    $Mandante->email = $Email;
    $Mandante->telefono = $Phone;

    $MandanteMySqlDAO = new MandanteMySqlDAO();

    $MandanteMySqlDAO->update($Mandante);
    $Transaction = $MandanteMySqlDAO->getTransaction();

    if (!empty($LandingFreeCasinoBonusesSelected) && !empty($LandingFreeBetBonusesSelected)) {

        /* Código crea un objeto DAO y concatena y ordena arrays de bonificaciones. */
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);

        //Concatenando Arrays de los tiposa de bonos ofertados y retornados por Frontend
        $newLandingBonuses = [];
        $newLandingBonuses = [$LandingFreeCasinoBonusesSelected, $LandingFreeBetBonusesSelected];
        asort($newLandingBonuses);


        /* Inicializa un arreglo y un objeto clasificador para gestionar bonificaciones de aterrizaje. */
        $oldLandingBonuses = [];
        $bonusesChanged = false;
        $Clasificador = new Clasificador('', 'BONUSFORLANDING');

        try {
            //Consultando si existe una colección anterior de bonos ofertados

            /* Se comparan bonos antiguos y nuevos; se identifica si han cambiado. */
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');
            $oldLandingBonuses = $MandanteDetalle->getValor();
            $oldLandingBonuses = explode(',', $oldLandingBonuses);
            asort($oldLandingBonuses);

            if (count($oldLandingBonuses) != count($newLandingBonuses)) $bonusesChanged = true;

            $bonusesChanged = array_reduce($newLandingBonuses, function ($carry, $bonus) use ($oldLandingBonuses) {
                if ($carry) return $carry;
                elseif (in_array($bonus, $oldLandingBonuses)) return $carry;

                /* asigna verdadero a `$carry` si se cumple una condición. */
                else return $carry = true;
            }, $bonusesChanged);
        } catch (Exception $e) {
            if ($e->getCode() != 34) throw $e;
            $bonusesChanged = true;
        } finally {

            /* Actualiza el estado de bonificaciones si han cambiado y no hay errores. */
            $newBonuses = '';
            $oldBonuses = '';
            if ($bonusesChanged && !isset($e)) {
//Se inactiva si existía una configuración previa
                $oldBonuses = $MandanteDetalle->getValor();
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }

            if ($bonusesChanged) {

                /* Se configuran detalles de un mandante con bonificaciones nuevas en formato de texto. */
                $newBonuses = implode(',', $newLandingBonuses);
                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
                $MandanteDetalle->setValor($newBonuses);

                /* establece detalles de un mandante y lo inserta en la base de datos. */
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);


//                $userAgent = $_SERVER['HTTP_USER_AGENT'];
//
//                function getOS($userAgent) {
//                    $os = "Desconocido";
//
//                    if (stripos($userAgent, 'Windows') !== false) {
//                        $os = 'Windows';
//                    } elseif (stripos($userAgent, 'Linux') !== false) {
//                        $os = 'Linux';
//                    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
//                        $os = 'Mac';
//                    }
//
//                    return $os;
//                }
//
//
//                if (esMovil()) {
//                    $dispositivo = 'Mobile';
//                } else {
//                    $dispositivo = "Desktop";
//                }
//
//                $so = getOS($userAgent);



                /* obtiene la dirección IP del usuario y configura una auditoría. */
                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];


                $AuditoriaGeneral = new AuditoriaGeneral();

                $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);

                /* Se registra información del usuario y su IP para auditoría de tipo "linkeo de landing". */
                $AuditoriaGeneral->setUsuarioIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]); // ajuste
                $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId(0);
                $AuditoriaGeneral->setUsuarioaprobarIp(0);
                $AuditoriaGeneral->setTipo("linkeo de landing");

                /* Se establecen valores y estado para una auditoría general en el sistema. */
                $AuditoriaGeneral->setValorAntes($oldBonuses);
                $AuditoriaGeneral->setValorDespues($newBonuses);
                $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsumodifId(0);
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setDispositivo(0);

                /* Inserta una observación vacía en la base de datos mediante AuditoriaGeneralMySqlDAO. */
                $AuditoriaGeneral->setObservacion("");

                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            }
        }
    }


    //...----------------------------------------------------------------


    if ($CombinationOfWallets != "") {

        /* realiza una actualización del estado del maximo de combinaciones de wallet permitidas por partner y pais */
        $Clasificador = new Clasificador("", "WALT");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $CombinationOfWallets) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Crea un nuevo registro de MandanteDetalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($CombinationOfWallets);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Manejo de excepciones que vuelve a lanzar el error si no se maneja. */

                throw $e;
            }
        }

    }



    //--------------------------------------------------------------------------------------------

    if ($IsActivateRiskStatus != "") {

        /* realiza una actualización del estado del MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador("", "RISKSTATUS");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $IsActivateRiskStatus) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Crea un nuevo registro de MandanteDetalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActivateRiskStatus);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Manejo de excepciones que vuelve a lanzar el error si no se maneja. */

                throw $e;
            }
        }

    }


//------------------------------------------------------
    if ($DRequestMinAmount != "") {

        /* crea y actualiza un objeto MandanteDetalle según condiciones específicas. */
        $tipoDetalle = 16;
        $Clasificador = new Clasificador("", "MINDEPOSIT");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $DRequestMinAmount) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo detalle de mandante si el código es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DRequestMinAmount);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple una condición determinada. */

                throw $e;
            }
        }

    }

    if ($DRequestMaxAmount != "") {


        /* verifica y actualiza el estado de un objeto MandanteDetalle basado en condiciones. */
        $tipoDetalle = 17;
        $Clasificador = new Clasificador("", "MAXDEPOSIT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $DRequestMaxAmount) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo detalle de mandante en la base de datos bajo condición específica. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DRequestMaxAmount);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Maneja excepciones lanzando errores cuando se cumple una condición en el código. */

                throw $e;
            }
        }
    }

    if ($DaysNotifyBeforePasswordExpire != "") {

        /* Se actualiza el estado de MandanteDetalle si el valor no coincide con DaysNotifyBeforePasswordExpire. */
        $tipoDetalle = 18;
        $Clasificador = new Clasificador("", "DAYSNOTIFYPASSEXPIRE");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {

            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $DaysNotifyBeforePasswordExpire) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserts a new MandanteDetalle record if the error code is "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DaysNotifyBeforePasswordExpire);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en un bloque anterior. */

                throw $e;
            }
        }

    }


    //------------------------------------------------CODIGO NUEVO


    if($CommissionFreeTransactions == 1){ /*se verifica si no tiene comision por restriccion esta activo*/
        $CommissionFreeTransactions = "A"; /* se asigna el estado de activado*/
    }else if ($CommissionFreeTransactions){
        $CommissionFreeTransactions = "I"; /* se asigna el estado inactivo a no tiene transaccion por comision.*/
    }

    if ($CommissionFreeTransactions != "") { // se verifica si el campo no es vacio
        $Clasificador = new Clasificador("", "COMMISIONSFREETRANSACTION"); /* se realiza instacia a la clase clasificador*/
        $tipoDetalle = $Clasificador->getClasificadorId(); /*obtenemos el clasificador_id del detalle*/

        try {

            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A'); /*se instancia la clase mandante_detalle*/

            /*El código verifica si el valor actual de $MandanteDetalle es diferente de $CommissionFreeTransactions. Si es diferente, cambia el estado a 'I', actualiza el registro en la base de datos*/
            if ($MandanteDetalle->getValor() != $CommissionFreeTransactions) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {

            /*El código crea un nuevo registro en la tabla MandanteDetalle con los valores proporcionados, asignando el estado 'A'. Si ocurre una excepción distinta, esta se lanza nuevamente*/

            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($CommissionFreeTransactions);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }

    }


    //-----------------------------------------------------------------------

    if ($RestrictionTime != "") { /*se verifica si tiene tiempo de restriccion*/
        $Clasificador = new Clasificador("", "RESTRICTIONTIME"); /*Se realiza una instancia de la clase clasificador*/
        $tipoDetalle = $Clasificador->getClasificadorId(); /*Se obtiene el id del detalle*/

        try {

            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A'); /*Se realiza una instancia de la clase MandanteDetalle*/
            /* se obtiene el valor actual de tiempo y en caso de ser nuevo se inactiva y se obtiene el codigo de error*/
            if ($MandanteDetalle->getValor() != $RestrictionTime) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {

            /*Obtenemos el codigo de error 34 cuando no se encuentre alguna configuracion */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle(); /* se realiza instancia ala clase MandanteDetalle*/

                /*Se realiza la insercion de los datos nuevos del tiempo de transaccion sin comision */
                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($RestrictionTime);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }

    }

    /**
     * Verificamos el valor de `$requiresVerificationForLoyalty`:
     * - Si es 1, asignamos "A".
     * - Si es 0, asignamos "I".
     */
    if($requiresVerificationForLoyalty == 1){
        $requiresVerificationForLoyalty = "A";
    }else if($requiresVerificationForLoyalty == 0){
        $requiresVerificationForLoyalty = "I";
    }



    /**
     * Verifica y actualiza el estado de verificación de lealtad del partner.
     *
     * - Si el valor actual de `$requiresVerificationForLoyalty` es diferente al almacenado,
     *   se inactiva el registro previo y se actualiza con el nuevo valor.
     * - Registra la auditoría del cambio realizado.
     *
     * @throws Exception Si ocurre un error al actualizar el estado.
     */

    if ($requiresVerificationForLoyalty != "") {
        $Clasificador = new Clasificador("", "LOYALTYVERIFICATION");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $requiresVerificationForLoyalty) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];

                $AuditoriaGeneral = new AuditoriaGeneral();
                $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsuarioIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                $AuditoriaGeneral->setUsuarioaprobarIp(0);
                $AuditoriaGeneral->setTipo("linkeo de landing");
                $AuditoriaGeneral->setValorAntes(""); // Ajustar si es necesario
                $AuditoriaGeneral->setValorDespues(""); // Ajustar si es necesario
                $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsumodifId(0);
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setDispositivo(0);
                $AuditoriaGeneral->setObservacion("");

                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

                throw new Exception("Error al actualizar el estado", 34);
            }
        } catch (Exception $e) {
            if ($e->getCode() == 34) {
                $MandanteDetalle = new MandanteDetalle();
                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($requiresVerificationForLoyalty);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            } else {
                throw $e;
            }
        }
    }


    //-------------------------------------------------------------------------------

    if ($UserPasswordExpireDays != "") {


        /* Actualiza el estado de un registro si el valor no coincide con la contraseña. */
        $tipoDetalle = 19;
        $Clasificador = new Clasificador("", "DAYSEXPIREPASSWORD");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {

            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $UserPasswordExpireDays) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Insertar un nuevo registro de MandanteDetalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($UserPasswordExpireDays);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple una condición en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($BoPasswordExpireDays != "") {


        /* verifica y actualiza el estado de un mandante según parámetros específicos. */
        $Clasificador = new Clasificador("", "BODAYSEXPIREPASSWORD");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {

            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $BoPasswordExpireDays) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un detalle de mandante en base de datos si el código es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BoPasswordExpireDays);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple una condición en un bloque else. */

                throw $e;
            }
        }
    }

    if ($UserPasswordMinLength != "") {


        /* verifica y actualiza el estado de un detalle de mandante basándose en la longitud de la contraseña. */
        $tipoDetalle = 20;
        $Clasificador = new Clasificador("", "MINLENPASSWORD");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $UserPasswordMinLength) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo detalle de mandante si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($UserPasswordMinLength);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura y relanza una excepción si ocurre un error en el bloque try. */

                throw $e;
            }
        }

    }

    if ($UserTempPasswordExpireDays != "") {


        /* Actualiza el estado del MandanteDetalle si no coincide el valor esperado. */
        $tipoDetalle = 21;
        $Clasificador = new Clasificador("", "DAYSEXPIRETEMPPASS");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $UserTempPasswordExpireDays) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle si el código error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($UserTempPasswordExpireDays);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }

//    if ($UserWrongLoginAttempts != "") {
//
//        $tipoDetalle = 22;
//        $Clasificador = new Clasificador("", "WRONGATTEMPTSLOGIN");
//        $tipoDetalle = $Clasificador->getClasificadorId();
//        try {
//            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');
//
//            if ($MandanteDetalle->getValor() != $UserWrongLoginAttempts) {
//
//                $MandanteDetalle->setEstado('I');
//                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
//
//                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
//                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
//
//                throw new Exception("", "34");
//            }
//        } catch (Exception $e) {
//
//            if ($e->getCode() == "34") {
//
//                $MandanteDetalle = new MandanteDetalle();
//
//                $MandanteDetalle->setMandante($Mandante->mandante);
//                $MandanteDetalle->setTipo($tipoDetalle);
//                $MandanteDetalle->setValor($UserWrongLoginAttempts);
//                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
//                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
//                $MandanteDetalle->setPaisId($Country);
//                $MandanteDetalle->setEstado('A');
//
//                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
//                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
//
//            } else {
//                throw $e;
//            }
//        }
//    }


    if ($UserWrongLoginAttempts != "") {
        try {

            /* gestiona intentos de inicio de sesión incorrectos, actualizando el estado en la base de datos. */
            $Clasificador = new Clasificador("", "WRONGATTEMPTSLOGIN");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $UserWrongLoginAttempts) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un registro de detalles del mandante si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($UserWrongLoginAttempts);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se cumple la condición en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP; captura errores sin realizar acciones específicas. */


        }
    }


    if ($MaxActiveRequests != "") {


        /* Se actualiza el estado de MandanteDetalle si no cumple la condición de solicitudes. */
        $tipoDetalle = 23;
        $Clasificador = new Clasificador("", "MAXWITHDRAWACTIVEREQUEST");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MaxActiveRequests) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MaxActiveRequests);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si se produce un error en el bloque anterior. */

                throw $e;
            }
        }

    }

    if ($MaxRequestsPerDay != "") {


        /* gestiona detalles de mandantes y actualiza su estado según condiciones específicas. */
        $tipoDetalle = 24;
        $Clasificador = new Clasificador("", "MAXWITHDRAWDAY");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MaxRequestsPerDay) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MaxRequestsPerDay);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }

    }
    if ($MinAmountWithdrawKasnet != "") {


        /* Se verifica y actualiza el estado de MandanteDetalle según el monto mínimo establecido. */
        $tipoDetalle = 222;
        $Clasificador = new Clasificador("", "MINWITHDRAWDAYKASNET");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MinAmountWithdrawKasnet) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Insertar un objeto MandanteDetalle en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MinAmountWithdrawKasnet);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanzará una excepción si se encuentra un error en el bloque anterior. */

                throw $e;
            }
        }

    }
    if ($MaxAmountWithdrawKasnet != "") {


        /* Código para actualizar estado de MandanteDetalle si no cumple con una condición específica. */
        $tipoDetalle = 223;
        $Clasificador = new Clasificador("", "MAXWITHDRAWDAYKASNET");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MaxAmountWithdrawKasnet) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro de MandanteDetalle en base a condiciones específicas. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MaxAmountWithdrawKasnet);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }

    }
    if ($RequestMinAmount != "") {


        /* verifica y actualiza el estado de un detalle si no coincide con un valor. */
        $tipoDetalle = 25;
        $Clasificador = new Clasificador("", "MINWITHDRAW");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');
            if ($MandanteDetalle->getValor() != $RequestMinAmount) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }

        } catch (Exception $e) {


            /* inserta un objeto MandanteDetalle en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($RequestMinAmount);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si no se cumple la condición previa en el código. */

                throw $e;
            }
        }
    }

    if ($RequestMaxAmount != "") {


        /* maneja la actualización y validación de detalles de un mandante. */
        $tipoDetalle = 26;
        $Clasificador = new Clasificador("", "MAXWITHDRAW");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $RequestMaxAmount) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Código para insertar un registro de detalle de mandante en base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($RequestMaxAmount);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si se cumple una condición no manejada previamente. */

                throw $e;
            }
        }
    }


    if ($TaxWithdrawBalanceAward != "") {

        /* gestiona detalles de mandantes y actualiza estados según condiciones específicas. */
        $tipoDetalle = 36;
        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxWithdrawBalanceAward) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxWithdrawBalanceAward);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Bloque que lanza una excepción si se produce un error en el código. */

                throw $e;
            }
        }
    }
    if ($TaxWithdrawBalanceAward2 != "") {


        /* actualiza el estado de un detalle financiero si no coincide con un valor. */
        $tipoDetalle = 186;
        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDISR");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxWithdrawBalanceAward2) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro de MandanteDetalle en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxWithdrawBalanceAward2);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* captura una excepción y la vuelve a lanzar para ser manejada más adelante. */

                throw $e;
            }
        }
    }
    if ($TaxWithdrawBalanceAwardFrom != "") {


        /* gestiona detalles de mandantes y actualiza su estado según condiciones específicas. */
        $tipoDetalle = 50;
        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxWithdrawBalanceAwardFrom) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxWithdrawBalanceAwardFrom);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si se encuentra un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($TaxWithdrawBalanceDeposit != "") {


        /* gestiona la creación y actualización de un objeto MandanteDetalle. */
        $tipoDetalle = 37;
        $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxWithdrawBalanceDeposit) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo detalle de mandante en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxWithdrawBalanceDeposit);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción cuando ocurre un error no manejado previamente. */

                throw $e;
            }
        }
    }
    if ($BetTaxValue != "") {


        /* Se crea un clasificador y se actualiza un mandante si su valor no coincide. */
        $Clasificador = new Clasificador("", "TAXBET");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $BetTaxValue) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Instrucciones para crear y guardar un objeto MandanteDetalle en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BetTaxValue);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en un bloque try. */

                throw $e;
            }
        }
    }

    if ($DepositTaxValue != "") {


        /* Código que actualiza el estado de un mandante según un valor de impuesto. */
        $Clasificador = new Clasificador("", "TAXDEPOSIT");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $DepositTaxValue) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositTaxValue);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
    }

    if ($TaxWithdrawBalanceDepositFrom != "") {


        /* gestiona detalles del mandante y actualiza su estado si no coinciden. */
        $tipoDetalle = 51;
        $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSITFROM");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxWithdrawBalanceDepositFrom) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle en la base de datos si la condición se cumple. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxWithdrawBalanceDepositFrom);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se encuentra un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($TaxRegulator != "") {


        /* Código que actualiza el estado de MandanteDetalle basado en condiciones específicas. */
        $tipoDetalle = 38;
        $Clasificador = new Clasificador("", "TAXREGULATOR");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxRegulator) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro en la base de datos si se cumple la condición del código. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxRegulator);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción en caso de error en el bloque anterior. */

                throw $e;
            }
        }
    }


    if ($TaxRegulatorFrom != "") {


        /* actualiza un registro si el valor no coincide, lanzando una excepción. */
        $tipoDetalle = 52;
        $Clasificador = new Clasificador("", "TAXREGULATORFROM");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxRegulatorFrom) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condicional para insertar un detalle de mandante en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxRegulatorFrom);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción ($e) si no se cumple una condición previa. */

                throw $e;
            }
        }
    }

    if ($MaxAccountsBank != "") {


        /* Se actualiza el estado de MandanteDetalle si no coincide con MaxAccountsBank. */
        $Clasificador = new Clasificador("", "MAXACCOUNTSBANK");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MaxAccountsBank) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro si el código de error es "34". */
            if ($e->getCode() == "34") {


                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MaxAccountsBank);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);


            } else {
                /* Se lanza una excepción si ocurre un error en el código anterior. */


                throw $e;
            }
        }
    }


    if ($ActivateRegisterUser != "") {


        /* Código que actualiza el estado de un detalle de mandante según ciertas condiciones. */
        $tipoDetalle = 54;

        $Clasificador = new Clasificador("", "REQREGACT");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ActivateRegisterUser) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo detalle de mandante en la base de datos tras validar un código. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ActivateRegisterUser);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza la excepción $e si se cumple una condición específica. */

                throw $e;
            }
        }
    }


    if ($RequireActiveRegister != "") {


        /* gestiona el estado de un detalle de mandante basado en ciertas condiciones. */
        $tipoDetalle = 54;

        $Clasificador = new Clasificador("", "REQREGACT");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $RequireActiveRegister) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta detalles de un mandante si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($RequireActiveRegister);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si la condición anterior no se cumple. */

                throw $e;
            }
        }
    }


    if ($MinPercentageWagered != "") {


        /* valida y actualiza el estado de un detalle según ciertos criterios. */
        $tipoDetalle = 55;
        $Clasificador = new Clasificador("", "MINPERCTDEP");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MinPercentageWagered) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro en la base de datos si se cumple una condición específica. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MinPercentageWagered);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura una excepción y la vuelve a lanzar para manejo posterior. */

                throw $e;
            }
        }
    }

    if ($DaysAlertChangePassword != "") {


        /* gestiona un cambio de estado de un registro según un valor específico. */
        $tipoDetalle = 56;
        $Clasificador = new Clasificador("", "DAYALERTCHANGEPASS");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $DaysAlertChangePassword) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si el código es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DaysAlertChangePassword);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en la ejecución. */

                throw $e;
            }
        }
    }

    /* Se asigna la configuración de cuentas contables a una variable en código PHP. */
    $AccountingAccountsBetsTicketsStores = $GeneralSettings->AccountingAccountsBetsTicketsStores;
    if ($AccountingAccountsBetsTicketsStores != "") {


        /* Código que actualiza el estado de MandanteDetalle si el valor no coincide. */
        $tipoDetalle = 59;
        $Clasificador = new Clasificador("", "ACCBETTICKET");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $AccountingAccountsBetsTicketsStores) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro de 'MandanteDetalle' tras una verificación de código. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AccountingAccountsBetsTicketsStores);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción $e si no se cumple la condición anterior. */

                throw $e;
            }
        }
    }


    /* Se asigna el valor de configuración de cuentas a una variable específica. */
    $AccountingAccountsAwardsTicketsStores = $GeneralSettings->AccountingAccountsAwardsTicketsStores;

    if ($AccountingAccountsAwardsTicketsStores != "") {


        /* Código que actualiza el estado de MandanteDetalle si no cumple una condición específica. */
        $tipoDetalle = 60;
        $Clasificador = new Clasificador("", "ACCWINTICKET");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $AccountingAccountsAwardsTicketsStores) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro en MandanteDetalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AccountingAccountsAwardsTicketsStores);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si no se cumple una condición en código. */

                throw $e;
            }
        }
    }


    /* Asigna valores de configuración a la variable de retiro de pagos de contabilidad. */
    $AccountingAccountsPaymentsWithdrawal = $GeneralSettings->AccountingAccountsPaymentsWithdrawal;

    if ($AccountingAccountsPaymentsWithdrawal != "") {


        /* verifica y actualiza el estado de un detalle del mandante. */
        $tipoDetalle = 61;
        $Clasificador = new Clasificador("", "ACCPAYWD");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $AccountingAccountsPaymentsWithdrawal) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro en MandanteDetalle si se cumple la condición del código de error. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AccountingAccountsPaymentsWithdrawal);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }


    /* Asigna la configuración de cuentas contables a la variable `AccountingAccountsRecharge`. */
    $AccountingAccountsRecharge = $GeneralSettings->AccountingAccountsRecharge;

    if ($AccountingAccountsRecharge != "") {


        /* actualiza el estado de MandanteDetalle si no coincide con un valor específico. */
        $tipoDetalle = 62;
        $Clasificador = new Clasificador("", "ACCREC");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $AccountingAccountsRecharge) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Crea un objeto MandanteDetalle y lo inserta en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AccountingAccountsRecharge);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si ocurre un error en el bloque anterior del código. */

                throw $e;
            }
        }
    }

    if ($Liquidations != "") {


        /* Código que valida y actualiza el estado de un objeto MandanteDetalle en una transacción. */
        $tipoDetalle = 87;
        $Clasificador = new Clasificador("", "LIQUIDAFF");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $Liquidations) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($Liquidations);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción `$e` si no se cumple una condición previamente definida. */

                throw $e;
            }
        }
    }


    if ($TypeRegister != "") {


        /* actualiza el estado de un objeto si no coincide con un tipo específico. */
        $Clasificador = new Clasificador("", "TYPEREGISTER");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TypeRegister) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos bajo ciertas condiciones. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TypeRegister);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si se produce un error en el bloque previo. */

                throw $e;
            }
        }
    }

    if ($VerificaFiltro != "") {


        /* verifica y actualiza el estado de un objeto MandanteDetalle según condiciones específicas. */
        $Clasificador = new Clasificador("", "VERIFICANUMDOC");
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');


            if ($MandanteDetalle->getValor() != $VerificaFiltro) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta detalles de un mandante en la base de datos si el código es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($VerificaFiltro);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura la excepción y vuelve a lanzarla si ocurre un error. */

                throw $e;
            }
        }
    }

    if ($NumRechazosDocument != "") {


        /* Código que actualiza el estado de un objeto si no coincide con un valor específico. */
        $Clasificador = new Clasificador("", "NUMRECHAZOSDOCUMENT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $NumRechazosDocument) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($NumRechazosDocument);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque previo. */

                throw $e;
            }
        }
    }

    if ($LevelName != "") {


        /* crea un clasificador y actualiza el estado de un objeto según ciertas condiciones. */
        $Clasificador = new Clasificador("", "LEVELNAME");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LevelName) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condición que crea e inserta un objeto MandanteDetalle en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LevelName);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición específica. */

                throw $e;
            }
        }
    }

    if ($VerificaFiltroPV != "") {


        /* verifica un documento y actualiza el estado en base a una condición. */
        $Clasificador = new Clasificador("", "VERIFICANUMDOCPV");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $VerificaFiltroPV) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($VerificaFiltroPV);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Re-lanza la excepción $e si se cumple la condición del bloque else. */

                throw $e;
            }
        }
    }

    if ($NumRechazos != "") {


        /* Código para validar y actualizar el estado de un objeto MandanteDetalle según condiciones específicas. */
        $Clasificador = new Clasificador("", "NUMRECHAZOS");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $NumRechazos) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condicional que inserta un registro de detalle si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($NumRechazos);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si se encuentra un error en el bloque de código anterior. */

                throw $e;
            }
        }
    }

    if ($SetMins != "") {


        /* Código que actualiza estado de MandanteDetalle según verificación de valores. */
        $Clasificador = new Clasificador("", "SETMINS");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $SetMins) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SetMins);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en un bloque anterior. */

                throw $e;
            }
        }
    }

    if ($IsActiveSms != "") {


        /* actualiza el estado de MandanteDetalle basado en una condición específica. */
        $Clasificador = new Clasificador("", "ISACTIVESMS");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $IsActiveSms) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro de MandanteDetalle si se cumple una condición específica. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActiveSms);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
    }

    if ($IsActivePopUp != "") {


        /* Se actualiza el estado de MandanteDetalle si no coincide con IsActivePopUp. */
        $Clasificador = new Clasificador("", "ISACTIVEPOPUP");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $IsActivePopUp) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de mandante si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActivePopUp);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si no se cumple una condición previa en el código. */

                throw $e;
            }
        }
    }

    if ($IsActiveEmail != "") {


        /* Código para actualizar el estado de MandanteDetalle si no coincide con IsActiveEmail. */
        $Clasificador = new Clasificador("", "ISACTIVEEMAIL");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $IsActiveEmail) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo detalle de mandante si se cumple una condición específica. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActiveEmail);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si una condición anterior no se cumple en el código. */

                throw $e;
            }
        }
    }

    if ($IsActiveInbox != "") {


        /* Se verifica y actualiza el estado de un MandanteDetalle según ciertas condiciones. */
        $Clasificador = new Clasificador("", "ISACTIVEINBOX");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $IsActiveInbox) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Código que crea un objeto y lo inserta en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActiveInbox);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura excepción y la relanza si no se encuentra un manejo adecuado. */

                throw $e;
            }
        }
    }

    if ($AutomaticallyActive != "") {


        /* crea un clasificador y actualiza el estado de un mandante según condiciones. */
        $Clasificador = new Clasificador("", "REGISTERACTIVATION");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');


            if (strtolower($MandanteDetalle->getValor()) != $AutomaticallyActive) {


                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condición que inserta un objeto en la base de datos cuando se cumple un código específico. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AutomaticallyActive);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura una excepción y la vuelve a lanzar para maniobrar la gestión de errores. */

                throw $e;
            }
        }
    }



    /* asigna valores "A" o "I" según intentos fallidos de inicio de sesión. */
    if ($EmailWrongLoginAttempts == 1) {
        $EmailWrongLoginAttempts = "A";
    } else if ($EmailWrongLoginAttempts == 0) {
        $EmailWrongLoginAttempts = "I";
    }


    if ($EmailWrongLoginAttempts != "") {
        try {

            /* Clasificador y MandanteDetalle manejan intentos de login y actualizan estados en la base de datos. */
            $Clasificador = new Clasificador("", "ACTIVATESENDEMAIL");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $EmailWrongLoginAttempts) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta detalles de un mandante en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($EmailWrongLoginAttempts);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se cumple una condición específica en programación. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para capturar errores sin interrumpir la ejecución. */


        }
    }


    if ($RequestMinAmountWithdrawBetShop != "") {

        try {

            /* valida y actualiza detalles de mandantes, gestionando excepciones en caso de error. */
            $Clasificador = new Clasificador("", "MINWITHDRAWBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $RequestMinAmountWithdrawBetShop) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($RequestMinAmountWithdrawBetShop);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se encuentra un error en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque para manejar excepciones sin realizar ninguna acción específica. */


        }

    }


    if ($DefaultAmountDeposits != "") {

        try {

            /* Se actualiza el estado de MandanteDetalle si no coincide con el valor predeterminado. */
            $Clasificador = new Clasificador("", "DEFAULTAMOUNTPAYMENTGATEWAYS");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $DefaultAmountDeposits) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un nuevo registro en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($DefaultAmountDeposits);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se cumple la condición del bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para evitar fallos en el código. */


        }
    }

    // verticales desde acá

    if ($SportBetPv != "") {
        try {

            /* actualiza el estado de un objeto basado en condiciones específicas. */
            $Clasificador = new Clasificador("", "SPORTBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $SportBetPv) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un detalle de mandante si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($SportBetPv);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si no se cumple la condición estipulada en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para evitar errores durante la ejecución del código. */


        }
    }


    if ($PayAwardPv != "") {

        try {

            /* Crea un clasificador y actualiza el estado del mandante si no coincide. */
            $Clasificador = new Clasificador("", "PAYPRIZEBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PayAwardPv) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PayAwardPv);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se produce un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque de código en PHP que maneja excepciones sin realizar ninguna acción. */


        }
    }


    if ($PaymentOfWithdrawalNotes != "") {
        try {

            /* Código que actualiza el estado de un objeto en función de condiciones específicas. */
            $Clasificador = new Clasificador("", "WITHDRAWALBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PaymentOfWithdrawalNotes) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PaymentOfWithdrawalNotes);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se produce un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP; captura errores sin realizar ninguna acción específica. */


        }
    }


    if ($DepositsPv != "") {
        try {

            /* Código para clasificar y actualizar estado de un objeto MandanteDetalle basado en condiciones. */
            $Clasificador = new Clasificador("", "DEPOSITBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $DepositsPv) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Insertar un registro de detalle en la base de datos si el código es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($DepositsPv);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza la excepción $e si se cumple una condición específica. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP para manejo de errores. */


        }
    }

    if ($VirtualBetsPv != "") {
        try {

            /* Código que actualiza el estado de un mandante si el valor no coincide. */
            $Clasificador = new Clasificador("", "VIRTUALBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $VirtualBetsPv) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Código que inserta detalles en una base de datos si se cumple una condición específica. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($VirtualBetsPv);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si ocurre un error en el bloque try. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* El bloque captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }
    }


    if ($VirtualBetsPv != "") {
        try {

            /* gestiona un detalle de mandante y actualiza su estado si es necesario. */
            $Clasificador = new Clasificador("", "CASHIERSPORT");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $SportBetCashiers) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un objeto MandanteDetalle en la base de datos si el código es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($SportBetCashiers);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si no se cumple una condición previa en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }
    }


    if ($VirtualBetsPv != "") {
        try {

            /* Se realiza una verificación y actualización del estado de un objeto MandanteDetalle. */
            $Clasificador = new Clasificador("", "CASHIERWITHDRAWLL");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PaymentOfWithdrawalNotesCashier) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante si el código del error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PaymentOfWithdrawalNotesCashier);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Captura una excepción y la relanza para ser manejada posteriormente. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, capturando errores para evitar interrupciones en el programa. */


        }
    }


    if ($DepositsCashiers != "") {
        try {

            /* Se valida y actualiza el estado de un mandante basado en condiciones específicas. */
            $Clasificador = new Clasificador("", "CASHIERDEPOSIT");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $DepositsCashiers) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro en MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($DepositsCashiers);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* maneja excepciones lanzando el error si no se cumple una condición. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del programa. */


        }
    }


    if ($PayAwardAtm != "") {
        try {

            /* Código para verificar y actualizar el estado de un detalle de mandante. */
            $Clasificador = new Clasificador("", "CASHIERPAYPRIZE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PayAwardAtm) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un registro en la base de datos si se cumple una condición específica. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PayAwardAtm);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se cumple cierta condición en un bloque else. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura y maneja excepciones en PHP, evitando que el script se detenga. */


        }
    }

    if ($VirtualBetsAtm != "") {
        try {

            /* Clase "Clasificador" obtiene ID y verifica condición para modificar estado de "MandanteDetalle". */
            $Clasificador = new Clasificador("", "CASHIERVIRTUALS");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $VirtualBetsAtm) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un detalle de mandante en base de datos bajo una condición específica. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($VirtualBetsAtm);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se cumple una condición en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* captura excepciones en PHP sin realizar ninguna acción o manejo. */


        }
    }


    if ($SportBetOnlineUser != "") {
        try {

            /* actualiza el estado de un registro dependiendo de condiciones específicas. */
            $Clasificador = new Clasificador("", "SPORTUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $SportBetOnlineUser) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro en MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($SportBetOnlineUser);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Manejo de excepciones: lanza la excepción si no se cumple una condición previa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP y permite manejar errores sin detener la ejecución. */


        }
    }


    if ($CasinoBetsOnlineUsers != "") {
        try {

            /* Se crea un clasificador y se actualiza el estado de MandanteDetalle según condiciones. */
            $Clasificador = new Clasificador("", "CASINOUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $CasinoBetsOnlineUsers) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo 'MandanteDetalle' en la base de datos si el código es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($CasinoBetsOnlineUsers);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si no se cumple una condición específica. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para evitar errores durante la ejecución del código. */


        }
    }


    if ($LiveCasinoUserOnline != "") {
        try {

            /* actualiza el estado de un mandante según condiciones específicas en una transacción. */
            $Clasificador = new Clasificador("", "LIVECASINOUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LiveCasinoUserOnline) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de MandanteDetalle en la base de datos si se cumple la condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LiveCasinoUserOnline);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Captura y lanza nuevamente la excepción si no se cumple la condición previa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para evitar que la aplicación falle. */


        }
    }


    if ($VirtualBetsOnlineUsers != "") {
        try {

            /* Actualiza el estado de un MandanteDetalle si no coincide con un valor específico. */
            $Clasificador = new Clasificador("", "VIRTUALUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $VirtualBetsOnlineUsers) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un detalle de mandante si el código de error es 34. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($VirtualBetsOnlineUsers);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Captura y relanza una excepción si no se cumple una condición específica. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP para manejar errores sin interrumpir el flujo del programa. */


        }
    }


    if ($PokerUserOnline != "") {
        try {

            /* Código que clasifica y actualiza el estado de un "Mandante" basado en condiciones. */
            $Clasificador = new Clasificador("", "POKERUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PokerUserOnline) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Código para insertar un nuevo registro en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PokerUserOnline);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si ocurre un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */


        }
    }


    if ($RetirementNotesUserOnline != "") {
        try {

            /* actualiza el estado de un mandante si no coincide con un valor específico. */
            $Clasificador = new Clasificador("", "WITHDRAWALUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $RetirementNotesUserOnline) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de MandanteDetalle si el código del error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($RetirementNotesUserOnline);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si ocurre un error en el bloque anterior del código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }
    }


    if ($DepositUserOnline != "") {
        try {

            /* Código que actualiza el estado de 'MandanteDetalle' si no coincide con un valor específico. */
            $Clasificador = new Clasificador("", "DEPOSITUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $DepositUserOnline) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }

            } catch (Exception $e) {
                /* Maneja excepciones, creando y guardando un objeto si el código es "34". */

                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($DepositUserOnline);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar acciones específicas en el bloque vacío. */


        }
    }

    if ($HipicaUserOnline != "") {
        try {

            /* actualiza el estado de un objeto si su valor no coincide. */
            $Clasificador = new Clasificador("", "HIPICUSUONLINE");
            $tipoDetalle = $Clasificador->getClasificadorId();

            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $HipicaUserOnline) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta detalles de Mandante si el código de error es 34, usando MySQL. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($HipicaUserOnline);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se produce un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, permite capturar y gestionar errores en el código. */


        }
    }


// hasta aca las verticales


    if ($RequestMinAmountWithdrawBankAccount != "") {

        try {

            /* Código para clasificar y actualizar estado de un mandante según un monto mínimo. */
            $Clasificador = new Clasificador("", "MINWITHDRAWACCBANK");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $RequestMinAmountWithdrawBankAccount) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($RequestMinAmountWithdrawBankAccount);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si no se cumple una condición previa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Código PHP que captura excepciones, permitiendo manejar errores sin interrumpir la ejecución. */


        }
    }

    if ($RequestMaxAmountWithdrawBetShop != "") {

        try {

            /* Código que verifica y actualiza el estado de un mandante en base a un clasificador. */
            $Clasificador = new Clasificador("", "MAXWITHDRAWBETSHOP");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $RequestMaxAmountWithdrawBetShop) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Se inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($RequestMaxAmountWithdrawBetShop);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si no se cumple una condición previa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en bloques de código PHP sin realizar ninguna acción específica. */


        }
    }

    if ($TaxBetPayPrize != "") {

        try {

            /* Código para actualizar el estado de MandanteDetalle si cumple una condición específica. */
            $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $AutomaticallyActive) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un objeto MandanteDetalle en la base de datos si el código es 34. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($TaxBetPayPrize);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si no se cumple una condición previa especificada. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP pero no realiza ninguna acción. */


        }

    }
    if ($PointsLevelOne != "") {

        try {

            /* Se actualiza el estado de MandanteDetalle si no coincide con PointsLevelOne. */
            $Clasificador = new Clasificador("", "POINTSLEVELONE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelOne) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro en MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelOne);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si no se cumple una condición previa en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP y permite manejar errores sin interrumpir el flujo. */


        }

    }
    if ($PointsLevelTwo != "") {

        try {

            /* Código para validar y actualizar estado de un objeto MandanteDetalle basado en condiciones. */
            $Clasificador = new Clasificador("", "POINTSLEVELTWO");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelTwo) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Condición que inserta un nuevo registro de mandante si el código es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelTwo);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se cumple una condición específica en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP; captura errores sin realizar ninguna acción específica. */


        }

    }
    if ($PointsLevelThree != "") {

        try {

            /* Se crea un clasificador y se actualiza el estado de MandanteDetalle si no coincide. */
            $Clasificador = new Clasificador("", "POINTSLEVELTHREE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelThree) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un nuevo detalle de mandante si se cumple un código específico. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelThree);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* maneja excepciones, lanzando un error si no se puede manejar. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }

    }
    if ($PointsLevelFour != "") {

        try {

            /* Código que clasifica y actualiza el estado de un objeto según condiciones específicas. */
            $Clasificador = new Clasificador("", "POINTSLEVELFOUR");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelFour) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelFour);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Manejo de excepciones: lanza el error capturado si no se cumple la condición. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para prevenir errores durante la ejecución del código. */


        }

    }
    if ($PointsLevelFive != "") {

        try {

            /* Código para actualizar el estado de MandanteDetalle basado en condiciones específicas. */
            $Clasificador = new Clasificador("", "POINTSLEVELFIVE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelFive) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Código que inserta un objeto MandanteDetalle en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelFive);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Si ocurre un error, se lanza la excepción para su manejo. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque de código en PHP que maneja excepciones, sin acciones específicas en este caso. */


        }

    }
    if ($PointsLevelSix != "") {

        try {

            /* Se actualiza el estado de MandanteDetalle basado en ciertos criterios de puntos. */
            $Clasificador = new Clasificador("", "POINTSLEVELSIX");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelSix) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un objeto MandanteDetalle en la base de datos si se cumple una condición específica. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelSix);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si ocurre un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del programa. */


        }

    }
    if ($PointsLevelSeven != "") {

        try {

            /* actualiza el estado de MandanteDetalle si no coincide con el valor esperado. */
            $Clasificador = new Clasificador("", "POINTSLEVELSEVEN");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelSeven) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Condición para crear un objeto MandanteDetalle y guardarlo en la base de datos. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelSeven);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se encuentra un error en el bloque previo. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin realizar ninguna acción. */


        }

    }
    if ($PointsLevelEight != "") {

        try {

            /* Crea y actualiza un objeto MandanteDetalle según condiciones específicas. */
            $Clasificador = new Clasificador("", "POINTSLEVELEIGHT");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelEight) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Condicional que crea e inserta un objeto MandanteDetalle en base de datos. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelEight);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si no se cumple la condición en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Maneja excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


        }

    }
    if ($PointsLevelNine != "") {

        try {

            /* verifica y actualiza el estado de un detalle de mandante. */
            $Clasificador = new Clasificador("", "POINTSLEVELNIVE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelNine) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante si el código del error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelNine);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se cumple una condición, interrumpiendo el flujo normal del programa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


        }

    }
    if ($PointsLevelTen != "") {

        try {

            /* gestiona un clasificador y actualiza el estado de un mandante según condiciones. */
            $Clasificador = new Clasificador("", "POINTSLEVELTEN");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $PointsLevelTen) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($PointsLevelTen);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Maneja excepciones lanzando un error si no se cumple una condición previa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */


        }

    }

    if ($LoyaltyDeposit != "") {

        try {

            /* Se crea un objeto y se valida el estado de 'MandanteDetalle'. */
            $Clasificador = new Clasificador("", "LOYALTYDEPOSIT");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyDeposit) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un nuevo detalle de mandante en la base de datos bajo ciertas condiciones. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyDeposit);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Captura un error y lo vuelve a lanzar para tratamiento posterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Maneja excepciones en PHP, permitiendo continuar la ejecución sin detenerse por errores. */


        }

    }

    if ($LoyaltyBettingSportsSimple != "") {

        try {

            /* crea y actualiza un objeto MandanteDetalle en función de ciertas condiciones. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSSIMPLE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsSimple) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsSimple);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si una condición no se cumple en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que maneja excepciones en PHP sin realizar ninguna acción específica. */


        }

    }

    if ($LoyaltyBettingSportsCombinedTwo != "") {

        try {

            /* gestiona el estado de MandanteDetalle según ciertas condiciones y errores. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTWO");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedTwo) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un nuevo registro en la base de datos bajo ciertas condiciones. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedTwo);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción cuando se produce un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Maneja excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


        }

    }
    if ($LoyaltyBettingSportsCombinedThree != "") {

        try {

            /* Clasificador crea y actualiza MandanteDetalle basado en condiciones específicas de valor. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTHREE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedThree) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante en la base de datos si se cumple la condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedThree);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Captura errores y los vuelve a lanzar para un manejo superior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque para manejar excepciones en PHP, evita que el programa falle inesperadamente. */


        }

    }
    if ($LoyaltyBettingSportsCombinedFour != "") {

        try {

            /* crea y actualiza un objeto de MandanteDetalle basado en ciertas condiciones. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDFOUR");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedFour) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de detalle del mandante en la base de datos. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedFour);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si ocurre un error en el bloque anterior del código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, permite capturar errores sin interrumpir el flujo. */


        }

    }
    if ($LoyaltyBettingSportsCombinedFive != "") {

        try {

            /* actualiza el estado de un detalle de mandante si no coincide con un valor específico. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDFIVE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedFive) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserts a new MandanteDetalle record when the error code is "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedFive);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si se encuentra un error en el bloque try. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución del código. */


        }

    }
    if ($LoyaltyBettingSportsCombinedSix != "") {

        try {

            /* clasifica y actualiza el estado de MandanteDetalle en función de ciertas condiciones. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDSIX");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedSix) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Se inserta un nuevo detalle de mandante en base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedSix);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se produce un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción específica. */


        }

    }
    if ($LoyaltyBettingSportsCombinedSeven != "") {

        try {

            /* Clase para clasificar y actualizar detalles de mandante con manejo de excepciones. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDSEVEN");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedSeven) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Condición que inserta un nuevo registro en la base de datos si se cumple el código. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedSeven);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si la condición anterior no se cumple. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */


        }

    }
    if ($LoyaltyBettingSportsCombinedEight != "") {

        try {

            /* gestiona un clasificador y actualiza el estado de un mandante. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDEIGHT");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedEight) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un detalle de mandante en la base de datos si el código es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedEight);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si ocurre un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP sin realizar acciones adicionales. */


        }

    }
    if ($LoyaltyBettingSportsCombinedNine != "") {

        try {

            /* crea y actualiza un objeto de clasificador, gestionando su estado y errores. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDNINE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedNine) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un registro de MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedNine);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* maneja excepciones lanzando el error si no se cumple una condición. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución del script. */


        }

    }
    if ($LoyaltyBettingSportsCombinedTen != "") {

        try {

            /* clasifica y actualiza el estado de MandanteDetalle en función de condiciones. */
            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTEN");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBettingSportsCombinedTen) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de MandanteDetalle en la base de datos si se cumple la condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBettingSportsCombinedTen);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Lanza una excepción si se produce un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


        }

    }
    if ($SportsAwardLoyalty != "") {

        try {

            /* Código para validar y actualizar el estado de un objeto MandanteDetalle según condiciones específicas. */
            $Clasificador = new Clasificador("", "SPORTSAWARDLOYALTY");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $SportsAwardLoyalty) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro de MandanteDetalle si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($SportsAwardLoyalty);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si se cumple una condición no especificada. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */


        }

    }

    if ($LoyaltyBetCasinoLive != "") {


        try {

            /* establece estados para detalles de mandantes basados en un clasificador específico. */
            $Clasificador = new Clasificador("", "LOYALTYBETCASVIVO");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBetCasinoLive) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Condicional que inserta un nuevo detalle de mandante en la base de datos. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBetCasinoLive);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se produce un error durante la ejecución. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP, permitiendo manejar errores sin detener la ejecución del script. */


        }

    }


    if ($LoyaltyBetCasino != "") {

        try {

            /* Código que clasifica y actualiza el estado de un entidad según condiciones específicas. */
            $Clasificador = new Clasificador("", "LOYALTYBETCASINO");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBetCasino) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Validación y creación de un objeto MandanteDetalle para inserción en la base de datos. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBetCasino);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si no se cumple una condición previa. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP; captura errores sin realizar acciones específicas dentro del bloque. */


        }

    }


    if ($CasinoAwardLoyalty != "") {

        try {

            /* Código en PHP que actualiza un registro de mandante basado en condiciones específicas. */
            $Clasificador = new Clasificador("", "CASINOAWARDLOYALTY");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $CasinoAwardLoyalty) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Registro de un nuevo detalle de mandante si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($CasinoAwardLoyalty);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si ocurre un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque para manejar excepciones en PHP sin realizar ninguna acción específica. */


        }

    }

    if ($LoyaltyWithdrawal != "") {

        try {

            /* Clase que crea un clasificador y actualiza estado basado en condición específica. */
            $Clasificador = new Clasificador("", "LOYALTYWITHDRAWAL");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyWithdrawal) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un objeto MandanteDetalle en la base de datos bajo condición específica. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyWithdrawal);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si ocurre un error en el bloque anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo. */


        }

    }


    if ($LoyaltyBetVirtuals != "") {
        try {

            /* Se crea y actualiza un objeto MandanteDetalle basado en clasificador y condiciones específicas. */
            $Clasificador = new Clasificador("", "LOYALTYBETVIRTUALES");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBetVirtuals) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* inserta un registro de "MandanteDetalle" si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBetVirtuals);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* captura excepciones y vuelve a lanzarlas si ocurren errores. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, atrapando errores sin realizar ninguna acción específica. */


        }
    }


    if ($LoyaltyBaseValue != "") {

        try {

            /* Código que actualiza el estado de un objeto basado en una comparación de valores. */
            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyBaseValue) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyBaseValue);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si no se cumple una condición previa en el código. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del código. */


        }

    }

    if ($LoyaltyExpirationDate != "") {

        try {

            /* Código que actualiza el estado de un detalle de mandante si no coincide con la fecha de expiración. */
            $Clasificador = new Clasificador("", "LOYALTYEXPIRATIONDATE");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LoyaltyExpirationDate) {

                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro en la base de datos si el código de error es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($LoyaltyExpirationDate);
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si no se cumple una condición anterior. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP que captura errores sin realizar ninguna acción. */


        }

        if ($LoyaltyGiftMaxOffers != "") {

            try {

                /* Se crea y actualiza un detalle de mandante dependiendo de condiciones específicas. */
                $Clasificador = new Clasificador("", "MAXOFFERSFORLOYALTYGIFT");
                $tipoDetalle = $Clasificador->getClasificadorId();


                try {
                    $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                    if ($MandanteDetalle->getValor() != $LoyaltyGiftMaxOffers) {

                        $MandanteDetalle->setEstado('I');
                        $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                        $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                        throw new Exception("", "34");
                    }
                } catch (Exception $e) {


                    /* Inserta un nuevo objeto MandanteDetalle en la base de datos si se cumple una condición. */
                    if ($e->getCode() == "34") {

                        $MandanteDetalle = new MandanteDetalle();

                        $MandanteDetalle->setMandante($Mandante->mandante);
                        $MandanteDetalle->setTipo($tipoDetalle);
                        $MandanteDetalle->setValor($$LoyaltyGiftMaxOffers);
                        $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                        $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                        $MandanteDetalle->setPaisId($Country);
                        $MandanteDetalle->setEstado('A');

                        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                        $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                    } else {
                        /* lanza una excepción si se encuentra un error en el bloque anterior. */

                        throw $e;
                    }
                }
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, captura errores sin procesar otro código dentro del bloque. */


            }

        }

    }

    /* if (($RequireActiveRegister =='I' || $RequireActiveRegister =='A')) {
    $RequireActiveRegister=($RequireActiveRegister =='A') ? 1 : 0;
    $Clasificador = new Clasificador("", "REGISTERACTIVATION");
    $tipoDetalle = $Clasificador->getClasificadorId();
    try {
    $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

    if ($MandanteDetalle->getValor() != $RequireActiveRegister) {

    $MandanteDetalle->setEstado('I');
    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

    throw new Exception("", "34");
    }
    } catch (Exception $e) {

    if ($e->getCode() == "34") {

    $MandanteDetalle = new MandanteDetalle();

    $MandanteDetalle->setMandante($Mandante->mandante);
    $MandanteDetalle->setTipo($tipoDetalle);
    $MandanteDetalle->setValor($RequireActiveRegister);
    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
    $MandanteDetalle->setPaisId($Country);
    $MandanteDetalle->setEstado('A');

    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

    } else {
    throw $e;
    }
    }
    }*/
    if (is_numeric($ActiveRegistrationDeposit)) {


        /* Código para actualizar el estado de un registro de mandante bajo ciertas condiciones. */
        $Clasificador = new Clasificador("", "ACTREGFORDEPOSIT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ActiveRegistrationDeposit) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un detalle de mandante en base de datos si el código es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ActiveRegistrationDeposit);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }


    if (is_numeric($AccountVerificationDeposit)) {


        /* Código que actualiza el estado de un objeto dependiendo de una verificación de cuenta. */
        $Clasificador = new Clasificador("", "ACCVERIFFORDEPOSIT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $AccountVerificationDeposit) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si el código de error es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AccountVerificationDeposit);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si no se cumple la condición previa en el código. */

                throw $e;
            }
        }
    }


    if (is_numeric($ActiveRegistrationWithdraw)) {


        /* verifica el estado de un registro y lo actualiza según condiciones específicas. */
        $Clasificador = new Clasificador("", "ACTREGFORWITHDRAW");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ActiveRegistrationWithdraw) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* inserta un nuevo registro de "MandanteDetalle" en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ActiveRegistrationWithdraw);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
    }


    if (is_numeric($AccountVerificationWithdraw)) {


        /* Código que verifica y actualiza el estado de un 'MandanteDetalle' basado en condiciones. */
        $Clasificador = new Clasificador("", "ACCVERIFFORWITHDRAW");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $AccountVerificationWithdraw) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo detalle de mandante en la base de datos según código. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($AccountVerificationWithdraw);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si se cumple una condición no especificada en el bloque anterior. */

                throw $e;
            }
        }
    }


    if (is_numeric($LimitDepositDayDefault)) {


        /* Se actualiza el estado de MandanteDetalle si el valor no cumple un límite. */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIODEFT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LimitDepositDayDefault) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condicional que inserta un nuevo MandanteDetalle en la base de datos si se cumple un código específico. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LimitDepositDayDefault);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción cuando se encuentra un error no manejado. */

                throw $e;
            }
        }
    }


    if (is_numeric($LimitDepositWeekDefault)) {


        /* valida y actualiza el estado de un objeto MandanteDetalle. */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITOSEMANADEFT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LimitDepositWeekDefault) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condición que inserta un nuevo registro de MandanteDetalle en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LimitDepositWeekDefault);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple la condición del bloque "else". */

                throw $e;
            }
        }
    }


    if (is_numeric($LimitDepositMonthDefault)) {


        /* Código para actualizar el estado de un detalle de mandante basado en un límite. */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITOMENSUALDEFT");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LimitDepositMonthDefault) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inicia un proceso de inserción en base de datos si se cumple la condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LimitDepositMonthDefault);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Maneja excepciones lanzando el error cuando ocurre una situación no prevista. */

                throw $e;
            }
        }
    }


    if (is_numeric($LimitDepositDayGlobal)) {



        /* Código que verifica y actualiza el estado de un mandante según un límite diario. */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIOGLOBAL");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LimitDepositDayGlobal) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un objeto MandanteDetalle en la base de datos si el código es "34". */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LimitDepositDayGlobal);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Manejo de excepciones en programación; arroja un error si no se maneja adecuadamente. */

                throw $e;
            }
        }
    }


    if (is_numeric($LimitDepositWeekGlobal)) {


        /* gestiona actualizaciones de estado para detalles de mandantes según condiciones específicas. */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITOSEMANAGLOBAL");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LimitDepositWeekGlobal) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Crea e inserta un objeto MandanteDetalle en la base de datos si se cumple la condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LimitDepositWeekGlobal);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple una condición específica. */

                throw $e;
            }
        }
    }


    if (is_numeric($LimitDepositMonthGlobal)) {


        /* Clase que valida y actualiza el estado de un mandante según un límite de depósito. */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITOMENSUALGLOBAL");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $LimitDepositMonthGlobal) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Condicional que crea un objeto y lo inserta en la base de datos. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($LimitDepositMonthGlobal);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* captura una excepción y la vuelve a lanzar para su manejo posterior. */

                throw $e;
            }
        }
    }

    if (is_numeric($LimitHoursCancelDeposit)) {
        try {

            /* Verifica y actualiza el estado de un depósito según el límite de horas. */
            $LimitHoursCancelDeposit = intval($LimitHoursCancelDeposit);
            $Clasificador = new Clasificador("", "LIMITEHORASPARAANULARDEPOSITO");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LimitHoursCancelDeposit) {
                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo registro en MandanteDetalle si el código es "34". */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor(strval($LimitHoursCancelDeposit));
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* lanza una excepción si se cumple una condición no especificada previamente. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del programa. */


        }

    }

    if (is_numeric($LimitCancelDepositExecutions)) {

        try {

            /* verifica y actualiza el estado de un objeto según condiciones específicas. */
            $LimitCancelDepositExecutions = intval($LimitCancelDepositExecutions);
            $Clasificador = new Clasificador("", "LIMITEANULACIONDEPOSITOSPORPERIODO");
            $tipoDetalle = $Clasificador->getClasificadorId();


            try {
                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

                if ($MandanteDetalle->getValor() != $LimitCancelDepositExecutions) {
                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                    throw new Exception("", "34");
                }
            } catch (Exception $e) {


                /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
                if ($e->getCode() == "34") {

                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor(strval($LimitCancelDepositExecutions));
                    $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                    $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

                } else {
                    /* Se lanza una excepción si se cumple la condición del bloque else. */

                    throw $e;
                }
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, captura errores sin realizar ninguna acción específica. */


        }

    }

    if (is_float($TaxBetShopPhysical)) {


        /* Código que gestiona y actualiza el estado del MandanteDetalle basado en ciertas condiciones. */
        $Clasificador = new Clasificador("", "TAXBETSHOPPHYSICAL");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxBetShopPhysical) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxBetShopPhysical);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }


    if (is_float($TaxPrizeBetShop)) {


        /* actualiza el estado de un objeto basado en una condición específica. */
        $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxPrizeBetShop) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception("", "34");
            }
        } catch (Exception $e) {


            /* Inserta un nuevo objeto MandanteDetalle en la base de datos si se cumple la condición. */
            if ($e->getCode() == "34") {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxPrizeBetShop);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si se produce un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if (!empty($PercentDepositValue) && is_numeric($PercentDepositValue)) {

        /* Código para actualizar estado de un MandanteDetalle si no coincide con un valor especificado. */
        $Clasificador = new Clasificador('', 'PORCENVADEPO');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentDepositValue) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta un detalle de mandante si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentDepositValue);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if (!empty($PercentRetirementValue) && is_numeric($PercentRetirementValue)) {

        /* actualiza el estado de un objeto 'MandanteDetalle' basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'PORCENVARETR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentRetirementValue) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentRetirementValue);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición anterior. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueSportsBets) && is_numeric($PercentValueSportsBets)) {

        /* Se inicializa un clasificador y se valida un detalle de mandante, actualizando su estado. */
        $Clasificador = new Clasificador('', 'PORCENVAAPUESDEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueSportsBets) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Verifica un código, crea un objeto y lo inserta en la base de datos. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueSportsBets);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueNonSportBets) && is_numeric($PercentValueNonSportBets)) {

        /* Código para clasificar y actualizar estado de mandante según condiciones específicas. */
        $Clasificador = new Clasificador('', 'PORCENVAAPUESNODEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueNonSportBets) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueNonSportBets);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueSportsAwards) && is_numeric($PercentValueSportsAwards)) {

        /* Crea un clasificador, verifica condiciones y actualiza estado en la base de datos. */
        $Clasificador = new Clasificador('', 'PORCENVAPREMDEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueSportsAwards) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un objeto MandanteDetalle en la base de datos si se cumple una condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueSportsAwards);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si no se cumple la condición anterior. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueNonSportsAwards) && is_numeric($PercentValueNonSportsAwards)) {

        /* actualiza el estado de MandanteDetalle si no coincide con un valor específico. */
        $Clasificador = new Clasificador('', 'PORCENVAPREMNODEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueNonSportsAwards) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si se cumple una condición específica de código. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueNonSportsAwards);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* El bloque lanza una excepción si se encuentra un error en el código. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueSportsBonds) && is_numeric($PercentValueSportsBonds)) {

        /* gestiona un clasificador y actualiza el estado de un detalle. */
        $Clasificador = new Clasificador('', 'PORCENVABONDEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueSportsBonds) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si el código es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueSportsBonds);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si se cumple una condición no esperada en el código. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueNonSportsBounds) && is_numeric($PercentValueNonSportsBounds)) {

        /* verifica y actualiza el estado de MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'PORCENVABONNODEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueNonSportsBounds) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Código para insertar un objeto MandanteDetalle en base de datos si código es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueNonSportsBounds);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple una condición en el bloque anterior. */

                throw $e;
            }
        }
    }

    if (!empty($PercentValueTickets) && is_numeric($PercentValueTickets)) {

        /* Código que actualiza el estado de un objeto según condiciones específicas en un sistema. */
        $Clasificador = new Clasificador('', 'PORCENVATICKET');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PercentValueTickets) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle en base a condiciones específicas. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PercentValueTickets);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se produce un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($ApproveBankAccounts != '' && in_array($ApproveBankAccounts, ['A', 'I'])) {

        /* Crea un objeto, verifica y actualiza el estado de 'MandanteDetalle'. */
        $Clasificador = new Clasificador('', 'APPBANKACC');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ApproveBankAccounts) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Crea y guarda un nuevo registro de MandanteDetalle en la base de datos. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ApproveBankAccounts);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si no se cumple una condición específica en el código. */

                throw $e;
            }
        }
    }

    if ($PenaltyWithdrawalBalanceWithdrawal != '' && is_numeric($PenaltyWithdrawalBalanceWithdrawal)) {

        /* Código para clasificar y actualizar estado de mandante basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'WPPWBALANCEW');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PenaltyWithdrawalBalanceWithdrawal) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo registro de MandanteDetalle en base a una condición específica. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PenaltyWithdrawalBalanceWithdrawal);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición previa. */

                throw $e;
            }
        }
    }

    if ($ApproveChangesInformation != '' && in_array($ApproveChangesInformation, ['A', 'I'])) {

        /* Código que actualiza el estado de MandanteDetalle si no coincide con un valor esperado. */
        $Clasificador = new Clasificador('', 'APPCHANPERSONALINF');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ApproveChangesInformation) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo objeto MandanteDetalle en base de datos si se cumple la condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ApproveChangesInformation);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si no se cumple una condición previa en el código. */

                throw $e;
            }
        }
    }

    if ($PenaltyWithdrawalBalanceRecharge != '' && is_numeric($PenaltyWithdrawalBalanceRecharge)) {

        /* Se genera un objeto y se actualiza su estado si no cumple una condición. */
        $Clasificador = new Clasificador('', 'PPWWBALANCERECHARGES');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $PenaltyWithdrawalBalanceRecharge) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de detalle del mandante en la base de datos. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($PenaltyWithdrawalBalanceRecharge);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si no se cumple una condición específica. */

                throw $e;
            }
        }
    }

    if ($MinimumWithdrawalBalanceWithdrawalsPenalty != '' && is_numeric($MinimumWithdrawalBalanceWithdrawalsPenalty)) {

        /* Código que actualiza el estado de MandanteDetalle si no cumple con un valor mínimo. */
        $Clasificador = new Clasificador('', 'MINBALANCEWPW');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MinimumWithdrawalBalanceWithdrawalsPenalty) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MinimumWithdrawalBalanceWithdrawalsPenalty);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se encuentra un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($SessionInativityLength != '' && is_numeric($SessionInativityLength)) {

        /* Código que actualiza el estado de un objeto según la inactividad de sesión. */
        $Clasificador = new Clasificador('', 'SESSIONINACTIVITYMIN');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $SessionInativityLength) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inicia un proceso de inserción basado en un código de error específico. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SessionInativityLength);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Maneja excepciones: lanza de nuevo el error capturado si ocurre un problema. */

                throw $e;
            }
        }
    }

    if ($TypeCommissionBetshop != '' && is_numeric($TypeCommissionBetshop)) {

        /* Código que gestiona detalles de un mandante y actualiza su estado según condiciones. */
        $Clasificador = new Clasificador('', 'TYPECOMMISIONPOINTSALE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TypeCommissionBetshop) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TypeCommissionBetshop);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción en caso de un error durante la ejecución. */

                throw $e;
            }
        }
    }

    if ($SessionLength != '' && is_numeric($SessionLength)) {

        /* actualiza el estado de un objeto si no coincide con la duración de sesión. */
        $Clasificador = new Clasificador('', 'SESSIONDURATIONMIN');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $SessionLength) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* inserta un nuevo detalle si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SessionLength);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en la ejecución. */

                throw $e;
            }
        }
    }

    if ($TypeCommissionSettlements != '' && is_numeric($TypeCommissionSettlements)) {

        /* Código que actualiza el estado de un objeto MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'TYPESETTLECOMMSSIONS');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TypeCommissionSettlements) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si se cumple la condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TypeCommissionSettlements);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se produce un error en la ejecución. */

                throw $e;
            }
        }
    }

    if ($MinimumWithdrawalRefillsPenalty != '' && is_numeric($MinimumWithdrawalRefillsPenalty)) {

        /* verifica y actualiza el estado de MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'MINWITHRECHARGESPENALIZE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MinimumWithdrawalRefillsPenalty) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Insertar detalles del mandante en la base de datos si el código es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($MinimumWithdrawalRefillsPenalty);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura y vuelve a lanzar una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($BonusesBalance != '' && is_numeric($BonusesBalance)) {

        /* actualiza el estado de un objeto si no coincide con un valor específico. */
        $Clasificador = new Clasificador('', 'BALANCEBONDS');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $BonusesBalance) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro en MandanteDetalle si el código es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BonusesBalance);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si ocurre un error en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($BonusesBalance != '' && is_numeric($BonusesBalance)) {

        /* valida y actualiza el estado de un objeto 'MandanteDetalle' en una transacción. */
        $Clasificador = new Clasificador('', 'BALANCEBONDS');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $BonusesBalance) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Condicional que inserta un registro en la base de datos si se cumple una condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BonusesBalance);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura y relanza una excepción, propagando el error en el flujo de ejecución. */

                throw $e;
            }
        }
    }

    if ($WithdrawalsBalance != '' && is_numeric($WithdrawalsBalance)) {

        /* Código que gestiona actualizaciones de un objeto MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'WITHDRAWALBALANCE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $WithdrawalsBalance) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo detalle de mandante si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($WithdrawalsBalance);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se lanza una excepción si no se cumple la condición previa en el bloque. */

                throw $e;
            }
        }
    }

    if ($RechargeBalance != '' && is_numeric($RechargeBalance)) {

        /* actualiza el estado de un detallado si no coincide con un saldo. */
        $Clasificador = new Clasificador('', 'RECHARGEABLEBALANCE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $RechargeBalance) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($RechargeBalance);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se cumple una condición específica. */

                throw $e;
            }
        }
    }

    if ($FreecasinoBalance != '' && is_numeric($FreecasinoBalance)) {

        /* Código para actualizar el estado de un objeto MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'FREECASINOBALANCE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $FreecasinoBalance) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Se inserta un nuevo registro de MandanteDetalle si el código es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($FreecasinoBalance);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* captura una excepción y la vuelve a lanzar, interrumpiendo el flujo normal. */

                throw $e;
            }
        }
    }

    if ($Contingency !== '' && is_numeric($Contingency)) {

        /* gestiona la actualización de un objeto MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'TOTALCONTINGENCE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $Contingency) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* inserta detalles de "Mandante" si se cumple un código específico. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($Contingency);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si alguna falla ocurre en el bloque anterior. */

                throw $e;
            }
        }
    }

    if ($FreebetBalance != '' && is_numeric($FreebetBalance)) {

        /* Código que valida y actualiza el estado de un detalle de mandante basado en condiciones. */
        $Clasificador = new Clasificador('', 'FREEBETBALANCE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $FreebetBalance) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de mandante detalle si el código es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($FreebetBalance);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* maneja excepciones; si falla, lanza nuevamente la excepción capturada. */

                throw $e;
            }
        }
    }

    if ($ProviderSMS != '' && is_numeric($ProviderSMS)) {

        /* actualiza el estado de un objeto dependiendo de una condición específica. */
        $Clasificador = new Clasificador('', 'PROVSMS');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ProviderSMS) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante si el código del error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ProviderSMS);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura y relanza una excepción si no se cumplen condiciones específicas en el código. */

                throw $e;
            }
        }
    }
    if ($ProviderCRM != '' && is_numeric($ProviderCRM)) {

        /* actualiza el estado de un objeto MandanteDetalle si su valor no coincide. */
        $Clasificador = new Clasificador('', 'PROVCRM');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ProviderCRM) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro en MandanteDetalle si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ProviderCRM);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Maneja una excepción lanzando el error actual para su tratamiento posterior. */

                throw $e;
            }
        }
    }
    if ($ProviderVerification != '' && is_numeric($ProviderVerification)) {

        /* Se crea y actualiza un objeto MandanteDetalle según condiciones específicas. */
        $Clasificador = new Clasificador('', 'PROVVERIFICA');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ProviderVerification) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de "Mandante" si el código de error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ProviderVerification);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si se encuentra un error en el bloque anterior. */

                throw $e;
            }
        }
    }
    if ($ProviderEmail != '' && is_numeric($ProviderEmail)) {

        /* Clase para manejar mandantes y actualizar su estado si el valor no coincide. */
        $Clasificador = new Clasificador('', 'PROVEMAIL');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ProviderEmail) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo registro de MandanteDetalle si el código del error es '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ProviderEmail);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Captura un error y lo vuelve a lanzar para un manejo posterior. */

                throw $e;
            }
        }
    }


    if ($ProviderSignature != '' && is_numeric($ProviderSignature)) {

        /* Código que verifica y actualiza el estado de un objeto MandanteDetalle en función de condiciones. */
        $Clasificador = new Clasificador('', 'PROVSIGNATURE');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ProviderSignature) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserts a new MandanteDetalle record if the error code is '34'. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ProviderSignature);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Se captura una excepción y se relanza para su manejo posterior. */

                throw $e;
            }
        }
    }

    if ($ProviderCPF != '' && is_numeric($ProviderCPF)) {

        /* crea y actualiza un objeto según condiciones específicas de un clasificador. */
        $Clasificador = new Clasificador('', 'PROVCPF');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $ProviderCPF) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* Inserta un nuevo detalle de mandante en la base de datos si se cumple una condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ProviderCPF);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* Lanza una excepción si no se cumple una condición en el código. */

                throw $e;
            }
        }
    }

    /** INICIO Referidos */

    if (is_array($NewReferredAwards)) {
        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();
        $currentAwardsPlan = (object)[];
        $initialAwardsPlan = '';


        //Actualizando programa de referidos
        $MandanteDetalle = new MandanteDetalle();
        $oldReferredAwards = $MandanteDetalle->getPartnerAjustesReferidos($Partner, $Country, true);
        $changesInAwardsPlan = false;

        //Verificando disponibilidad del plan de premios referidos
        try {
            $Clasificador = new Clasificador('', 'AWARDSPLANREFERRED');
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');
            $currentAwardsPlan = $MandanteDetalle->getValor();
            $initialAwardsPlan = $currentAwardsPlan;

            $currentAwardsPlan = json_decode($currentAwardsPlan);
        } catch (Exception $e) {
            if ($e->getCode() != 34) throw $e;
        }

        //Verificando estado final del plan de premios --Sólo si el programa está activo
        if ($newProgramState) {
            if (!count($NewReferredAwards)) throw new Exception('Configuración no permitida en el programa de referidos', 4021);

            foreach ($NewReferredAwards as $NewReferredAward) {
                $activeConditions = 0;
                foreach ($NewReferredAward->Conditions as $Condition) {
                    if ((int)$Condition->Value > 0) $activeConditions += 1;
                }
                if ((int)$NewReferredAward->IsVerified) $activeConditions += 1;

                if (empty($NewReferredAward->AwardName)) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
                if (!$activeConditions) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
                if (!count($NewReferredAward->OfertedBonuses)) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
            }
        }


        /* Se procesan premios referidos, creando o actualizando según su estado. */
        $finalAwardsPlanReferred = [];
        foreach ($NewReferredAwards as $NewReferredAward) {
            if ($NewReferredAward->IsNew) {
                $currentAwardsPlan = $MandanteDetalle->createNuevoPremioReferidos($Transaction, $Partner, $Country, $Usuario, $currentAwardsPlan, $NewReferredAward, $newProgramState);
            } else {
                $currentAwardsPlan = $MandanteDetalle->updatePremioReferidos($Transaction, $Partner, $Country, $Usuario, $oldReferredAwards, $currentAwardsPlan, $NewReferredAward, $newProgramState);
            }
        }


        /* verifica si hay premios referidos antes de eliminarlos. */
        if ($currentAwardsPlan->awardsplanreferred !== null) {
            $currentAwardsPlan = $MandanteDetalle->deletePremiosReferidos($Transaction, $Partner, $Country, $Usuario, $NewReferredAwards, $currentAwardsPlan, $oldReferredAwards, $newProgramState);
        }


        //Verificando cambios en el json plan de referidos
        if ($currentAwardsPlan->awardsplanreferred !== null) $currentAwardsPlan = json_encode($currentAwardsPlan);
        else $currentAwardsPlan = "";

        if ($currentAwardsPlan != $initialAwardsPlan) {
            //Actualizando plan de premios en caso de que haya habido cambios en el programa de referidos
            $MandanteDetalle = new MandanteDetalle();
            $Clasificador = new Clasificador('', 'AWARDSPLANREFERRED');
            $awardsplanreferred = '{"awardsplanreferred":' . json_encode($finalAwardsPlanReferred) . '}';

            //Solicitando plan de premios anterior
            $rules = [];
            array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle.mandante', 'data' => $Partner, 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle.pais_id', 'data' => $Country, 'op' => 'eq']);
            array_push($rules, ['field' => 'mandante_detalle.tipo', 'data' => $Clasificador->getClasificadorId(), 'op' => 'eq']);
            $select = 'mandante_detalle.manddetalle_id';
            $filters = ['rules' => $rules, 'groupOp' => 'AND'];


            /* Obtiene y actualiza detalles de un mandante, inactivando registros existentes. */
            $planPremios = $MandanteDetalle->getMandanteDetallesCustom($select, 'mandante_detalle.manddetalle_id', 'ASC', 0, 1, json_encode($filters), true);
            $planPremios = json_decode($planPremios)->data[0];
            $oldId = $planPremios->{'mandante_detalle.manddetalle_id'};

//Inactivando y generando nueva inserción
            $MandanteDetalleAward = new MandanteDetalle($oldId);

            /* actualiza el estado de un objeto y crea uno nuevo con otro estado. */
            $MandanteDetalleAward->setEstado('I');
            $MandanteDetalleAward->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalleMySqlDAO->update($MandanteDetalleAward);

            $MandanteDetalleNewAwardPlan = new MandanteDetalle();
            $MandanteDetalleNewAwardPlan->setEstado('A');

            /* Detalles de un nuevo plan de premios se configuran usando el objeto MandanteDetalle. */
            $MandanteDetalleNewAwardPlan->setMandante($Partner);
            $MandanteDetalleNewAwardPlan->setPaisId($Country);
            $MandanteDetalleNewAwardPlan->setUsucreaId($_SESSION["usuario"]);
            $MandanteDetalleNewAwardPlan->setUsumodifId($_SESSION["usuario"]);
            $MandanteDetalleNewAwardPlan->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalleNewAwardPlan->setValor($currentAwardsPlan);

            /* Inserta un nuevo registro y crea un log de auditoría para seguimiento. */
            $MandanteDetalleMySqlDAO->insert($MandanteDetalleNewAwardPlan);

//Registro de auditoria
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $MandanteDetalleAward->getValor(), $MandanteDetalleNewAwardPlan->getValor(), $Clasificador->getClasificadorId());
        }
    }

    if ($ExcludedCategoriesMinBetReferred != '' && is_array($ExcludedCategoriesMinBetReferred)) {

        /* crea instancias de clases relacionadas a usuarios y auditoría. */
        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();
        $beforeValue = '';
        $Clasificador = new Clasificador('', 'EXCLUDEDCASINOCATEGORYREFERS');


        /* verifica condiciones y actualiza el estado de MandanteDetalle si no se cumplen. */
        try {
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');

            $lastCategoriesIds = explode(',', $MandanteDetalle->getValor());

            $sameArray = array_reduce($lastCategoriesIds, function ($carry, $idCategory) use ($ExcludedCategoriesMinBetReferred) {
                if (!in_array($idCategory, $ExcludedCategoriesMinBetReferred)) return $carry = false;
                elseif ($carry) return $carry;
            }, true);

            if (!$sameArray || count($lastCategoriesIds) != count($ExcludedCategoriesMinBetReferred)) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $beforeValue = $MandanteDetalle->getValor();

                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Código maneja excepciones y registra detalles de un mandante en la base de datos. */
            if ($e->getCode() != 34) throw $e;

            $newCategoriesIds = implode(',', $ExcludedCategoriesMinBetReferred);

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Partner);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($newCategoriesIds);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

//Registro en auditoria
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
        }
    }

    if ($UrlLanding != '' && $UrlLanding != null) {

        /* actualiza un detalle de mandante y maneja excepciones en caso de discrepancias. */
        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();
        $beforeValue = '';
        $Clasificador = new Clasificador('', 'URLLANDING');

        try {
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->getValor() != $UrlLanding) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $beforeValue = $MandanteDetalle->getValor();

                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones y creación de registros en base de datos y auditoría. */
            if ($e->getCode() != 34) throw $e;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Partner);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($UrlLanding);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

//Registro en auditoria
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
        }
    }

    if ($ReferentConditions != '' && is_array($ReferentConditions)) {


        /* Se inicializa una variable llamada $activeCondition con el valor entero cero. */
        $activeCondition = 0;
        foreach ($ReferentConditions as $ReferentCondition) {

            /* gestiona condiciones de auditoría y actualización de detalles de mandante. */
            $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $AuditoriaGeneral = new AuditoriaGeneral();
            $beforeValue = '';
            $Clasificador = new Clasificador('', $ReferentCondition->Condition);

            try {
                if ($ReferentCondition->Value > 0) $activeCondition += 1;

                $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');

                if ($MandanteDetalle->getValor() != $ReferentCondition->Value) {
                    $MandanteDetalle->setEstado('I');
                    $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                    $beforeValue = $MandanteDetalle->getValor();

                    throw new Exception('', 34);
                }
            } catch (Exception $e) {
                /* Manejo de excepciones y creación de registro en base de datos con auditoría. */

                if ($e->getCode() != 34) throw $e;

                $MandanteDetalle = new MandanteDetalle();
                $MandanteDetalle->setMandante($Partner);
                $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
                $MandanteDetalle->setValor($ReferentCondition->Value);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

//Registro en auditoria
                $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
            }
        }


        /* Lanza una excepción si la condición no activa y hay un nuevo estado de programa. */
        if (!$activeCondition && $newProgramState) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
    }

    if ($ReferredMinSelValue != '' && $ReferredMinSelValue != null && is_numeric($ReferredMinSelValue)) {

        /* Se crea y actualiza un objeto de detalle, lanzando excepción si se modifica el valor. */
        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();
        $beforeValue = '';
        $Clasificador = new Clasificador('', 'MINSELPRICEREFERRED');

        try {
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->getValor() != $ReferredMinSelValue) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $beforeValue = $MandanteDetalle->getValor();

                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que registra un nuevo detalle de mandante y auditoría. */
            if ($e->getCode() != 34) throw $e;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Partner);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($ReferredMinSelValue);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            //Registro en auditoria
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
        }
    }

    if ($AwardLimitFulfillmentDayReferred !== null && is_numeric($AwardLimitFulfillmentDayReferred)) {
        if ((int)$AwardLimitFulfillmentDayReferred <= 0) $AwardLimitFulfillmentDayReferred = -1;

        /* Código para iniciar clases relacionadas con usuarios y auditoría en una aplicación. */
        $Clasificador = new Clasificador('', 'LIMITDAYSCONDSFULFILLMENTREFERRED');
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);

        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Se verifica y actualiza el estado de un objeto MandanteDetalle según una condición. */
        $beforeValue = '';

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->getValor() != $AwardLimitFulfillmentDayReferred) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $beforeValue = $MandanteDetalle->getValor();
                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta detalles de un mandante y registra auditoría. */
            if ($e->getCode() != 34) throw $e;
            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($AwardLimitFulfillmentDayReferred);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
        }
    }

    if ($AwardLimitRedemptionDayReferent !== null && is_numeric($AwardLimitRedemptionDayReferent)) {
        if ((int)$AwardLimitRedemptionDayReferent <= 0) $AwardLimitRedemptionDayReferent = -1;

        /* Se crean instancias de clases para gestionar usuarios y auditorías en una transacción. */
        $Clasificador = new Clasificador('', 'LIMITDAYSAWARDCHOICINGREFERENT');
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);

        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Se crea un objeto y se actualiza su estado si no cumple con un valor específico. */
        $beforeValue = '';

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->getValor() != $AwardLimitRedemptionDayReferent) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $beforeValue = $MandanteDetalle->getValor();
                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Captura excepciones y registra información de un objeto MandanteDetalle en la base de datos. */
            if ($e->getCode() != 34) throw $e;
            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($AwardLimitRedemptionDayReferent);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
        }
    }

    if ($AcceptReferred !== '' && $AcceptReferred !== null) {
        //Bloque de valdiaciones necesarias para activar el programa de referidos
        if ($AcceptReferred && empty($NewReferredAwards)) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
        if ($AcceptReferred && empty($UrlLanding)) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
        if ($AcceptReferred && $AwardLimitFulfillmentDayReferred === null) throw new Exception('Configuración no permitida en el programa de referidos', 4021);
        if ($AcceptReferred && $AwardLimitFulfillmentDayReferred === null) throw new Exception('Configuración no permitida en el programa de referidos', 4021);


        /* gestiona detalles de un mandante y actualiza su estado si es necesario. */
        $UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $AuditoriaGeneral = new AuditoriaGeneral();
        $beforeValue = '';
        $Clasificador = new Clasificador('', 'ACEPTAREFERIDO');

        try {
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->getValor() != $AcceptReferred) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $beforeValue = $MandanteDetalle->getValor();

                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que registra detalles y auditoría en base de datos. */
            if ($e->getCode() != 34) throw $e;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Partner);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($AcceptReferred);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            //Registro en auditoria
            $AuditoriaGeneral->createLogProgReferidos($Transaction, $Usuario, $beforeValue, $MandanteDetalle->getValor(), $MandanteDetalle->getTipo());
        }
    }

    /** FIN Referidos */

    if ($DepositPhoneVerification != '') {

        /* actualiza el estado de un objeto MandanteDetalle bajo ciertas condiciones. */
        $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '', 0);
            if ($MandanteDetalle->getEstado() != $DepositPhoneVerification) {

                $MandanteDetalle->setEstado($DepositPhoneVerification);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Captura excepciones, verifica código y crea un objeto para insertarlo en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor(0);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($DepositPhoneVerification);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if ($SportBetsPhoneVerification != '') {

        /* verifica y actualiza el estado de un detalle de mandante relacionado con telefonía. */
        $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '', 1);
            if ($MandanteDetalle->getEstado() != $SportBetsPhoneVerification) {

                $MandanteDetalle->setEstado($SportBetsPhoneVerification);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones para insertar un objeto MandanteDetalle si la condición es verdadera. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor(1);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($SportBetsPhoneVerification);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if ($CasinoBetsPhoneVerification != '') {

        /* actualiza el estado de un objeto `MandanteDetalle` bajo ciertas condiciones. */
        $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '', 2);
            if ($MandanteDetalle->getEstado() != $CasinoBetsPhoneVerification) {

                $MandanteDetalle->setEstado($CasinoBetsPhoneVerification);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones para insertar nuevos detalles de mandante en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor(2);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($CasinoBetsPhoneVerification);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if ($WithdrawalPhoneVerification != '') {

        /* Se verifica y actualiza el estado de un objeto MandanteDetalle en la base de datos. */
        $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '', 3);
            if ($MandanteDetalle->getEstado() != $WithdrawalPhoneVerification) {

                $MandanteDetalle->setEstado($WithdrawalPhoneVerification);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones y crea un objeto MandanteDetalle si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor(3);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($WithdrawalPhoneVerification);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if ($LiveCasinoPhoneVerification != '') {

        /* gestiona la verificación telefónica actualizando el estado de MandanteDetalle. */
        $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '', 4);
            if ($MandanteDetalle->getEstado() != $LiveCasinoPhoneVerification) {

                $MandanteDetalle->setEstado($LiveCasinoPhoneVerification);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones: inserta detalles de mandante si el código de error es 34. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor(4);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($LiveCasinoPhoneVerification);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if ($VirtualPhoneVerification != '') {

        /* Código que actualiza el estado de un objeto MandanteDetalle en función de condiciones específicas. */
        $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '', 5);
            if ($MandanteDetalle->getEstado() != $VirtualPhoneVerification) {

                $MandanteDetalle->setEstado($VirtualPhoneVerification);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para insertar un nuevo detalle de mandante. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor(5);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($VirtualPhoneVerification);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

//LIMITE DE COMISIONES

    /* Asignación de parámetros de configuración para un concesionario de apuestas deportivas. */
    $IsSportbookGgrPvIpConcessionaire = $params->IsSportbookGgrPvIpConcessionaire;
    $SportbookGgrPvIpConcessionaire = ($params->SportbookGgrPvIpConcessionaire != '') ? $params->SportbookGgrPvIpConcessionaire : 0;
    if ($IsSportbookGgrPvIpConcessionaire != '') {

        /* Actualiza detalles del mandante según condiciones específicas en la base de datos. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIOSBGGRPVIP');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvIpConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvIpConcessionaire);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Captura excepciones y maneja errores insertando un objeto MandanteDetalle en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvIpConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores a variables según condiciones del parámetro "params". */
    $IsDepositPvConcessionaire = $params->IsDepositPvConcessionaire;
    $DepositPvConcessionaire = ($params->DepositPvConcessionaire != '') ? $params->DepositPvConcessionaire : 0;
    if ($IsDepositPvConcessionaire != '') {

        /* Actualiza el detalle del mandante si el valor de concesionario ha cambiado. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIODEPOSITPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositPvConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositPvConcessionaire);
                $MandanteDetalle->setEstado($IsDepositPvConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, inserta datos en la base de datos si hay un código específico. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositPvConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositPvConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* asigna valores basados en parámetros, previniendo resultados nulos. */
    $IsWithdrawalsNotesPvConcessionaire = $params->IsWithdrawalsNotesPvConcessionaire;
    $WithdrawalsNotesPvConcessionaire = ($params->WithdrawalsNotesPvConcessionaire != '') ? $params->WithdrawalsNotesPvConcessionaire : 0;
    if ($IsWithdrawalsNotesPvConcessionaire != '') {

        /* actualiza el estado de un detalle de mandante según condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIONOTESPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($WithdrawalsNotesPvConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($WithdrawalsNotesPvConcessionaire);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta datos si el código de error es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($WithdrawalsNotesPvConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros para manejar datos de apuestas deportivas. */
    $IsSportbookGgrAfConcessionaire = $params->IsSportbookGgrAfConcessionaire;
    $SportbookGgrAfConcessionaire = ($params->SportbookGgrAfConcessionaire != '') ? $params->SportbookGgrAfConcessionaire : 0;
    if ($IsSportbookGgrAfConcessionaire != '') {

        /* Actualiza detalles del mandante según valores y estado del concesionario de apuestas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIOSBGGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrAfConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrAfConcessionaire);
                $MandanteDetalle->setEstado($IsSportbookGgrAfConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, crea un objeto y lo inserta en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrAfConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrAfConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros a variables, manejando un caso de valor vacío. */
    $IsSportbookNgrAfConcessionaire = $params->IsSportbookNgrAfConcessionaire;
    $SportbookNgrAfConcessionaire = ($params->SportbookNgrAfConcessionaire != '') ? $params->SportbookNgrAfConcessionaire : 0;
    if ($IsSportbookNgrAfConcessionaire != '') {

        /* Código que actualiza detalles de un mandante en función de condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIOSBNGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookNgrAfConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookNgrAfConcessionaire);
                $MandanteDetalle->setEstado($IsSportbookNgrAfConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, creando un objeto si la excepción tiene código 34. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookNgrAfConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookNgrAfConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación condicional de valores a variables relacionadas con concesiones de sportsbook. */
    $IsSportbookGgrPvConcessionaire = $params->IsSportbookGgrPvConcessionaire;
    $SportbookGgrPvConcessionaire = ($params->SportbookGgrPvConcessionaire != '') ? $params->SportbookGgrPvConcessionaire : 0;
    if ($IsSportbookGgrPvConcessionaire != '') {

        /* Código que actualiza detalles de un mandante según condiciones específicas en la base de datos. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIOSBGGRPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvConcessionaire);
                $MandanteDetalle->setEstado($IsSportbookGgrPvConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones para insertar detalles de mandante en base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación de valores de parámetros para identificar concesionarios de apuestas deportivas. */
    $IsBetSportPvConcessionaire = $params->IsBetSportPvConcessionaire;
    $BetSportPvConcessionaire = ($params->BetSportPvConcessionaire != '') ? $params->BetSportPvConcessionaire : 0;
    if ($IsBetSportPvConcessionaire != '') {

        /* Actualiza el estado y valor de MandanteDetalle si es diferente al actual. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIOAPSPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($BetSportPvConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($BetSportPvConcessionaire);
                $MandanteDetalle->setEstado($IsBetSportPvConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones e inserción de detalle en base de datos en caso específico. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BetSportPvConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsBetSportPvConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros a variables, estableciendo un predeterminado de cero. */
    $IsCasinoNgrAfConcessionaire = $params->IsCasinoNgrAfConcessionaire;
    $CasinoNgrAfConcessionaire = ($params->CasinoNgrAfConcessionaire != '') ? $params->CasinoNgrAfConcessionaire : 0;
    if ($IsCasinoNgrAfConcessionaire != '') {

        /* Crea un clasificador y actualiza detalles del mandante si hay diferencias. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIOCASINONGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($CasinoNgrAfConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($CasinoNgrAfConcessionaire);
                $MandanteDetalle->setEstado($IsCasinoNgrAfConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, insertando datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($CasinoNgrAfConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsCasinoNgrAfConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores a variables según condiciones de los parámetros recibidos. */
    $IsDepositCommissionTransactionConcessionaire = $params->IsDepositCommissionTransactionConcessionaire;
    $DepositCommissionTransactionConcessionaire = ($params->DepositCommissionTransactionConcessionaire != '') ? $params->DepositCommissionTransactionConcessionaire : 0;
    if ($IsDepositCommissionTransactionConcessionaire != '') {

        /* Código que actualiza detalles de un mandante en base a condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIODEPOSITCT');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositCommissionTransactionConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositCommissionTransactionConcessionaire);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones para insertar detalles del mandante si el código es 34. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositCommissionTransactionConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros a variables, manejando un caso para depósitos vacíos. */
    $IsDepositAfConcessionaire = $params->IsDepositAfConcessionaire;
    $DepositAfConcessionaire = ($params->DepositAfConcessionaire != '') ? $params->DepositAfConcessionaire : 0;
    if ($IsDepositAfConcessionaire != '') {

        /* actualiza un registro de detalles del mandante en la base de datos. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONCONCESIONARIODEPOSITAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositAfConcessionaire != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositAfConcessionaire);
                $MandanteDetalle->setEstado($IsDepositAfConcessionaire);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Captura excepciones para manejar errores específicos y guardar detalles de mandante en base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositAfConcessionaire);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositAfConcessionaire);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros a variables, retornando 0 si son vacías. */
    $IsSportbookGgrPvIpSubdealer = $params->IsSportbookGgrPvIpSubdealer;
    $SportbookGgrPvIpSubdealer = ($params->SportbookGgrPvIpSubdealer != '') ? $params->SportbookGgrPvIpSubdealer : 0;
    if ($IsSportbookGgrPvIpSubdealer != '') {

        /* Código que actualiza información de un detalle de mandante en una base de datos. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIOSBGGRPVIP');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvIpSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvIpSubdealer);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones para insertar detalle de mandante si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvIpSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación de variables a partir de parámetros; se establece un valor por defecto. */
    $IsDepositPvSubdealer = $params->IsDepositPvSubdealer;
    $DepositPvSubdealer = ($params->DepositPvSubdealer != '') ? $params->DepositPvSubdealer : 0;
    if ($IsDepositPvSubdealer != '') {

        /* actualiza detalles de un mandante basado en ciertas condiciones dentro de un intento. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIODEPOSITPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositPvSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositPvSubdealer);
                $MandanteDetalle->setEstado($IsDepositPvSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones para insertar datos de MandanteDetalle en base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositPvSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositPvSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores a variables según condiciones del objeto $params. */
    $IsWithdrawalsNotesPvSubdealer = $params->IsWithdrawalsNotesPvSubdealer;
    $WithdrawalsNotesPvSubdealer = ($params->WithdrawalsNotesPvSubdealer != '') ? $params->WithdrawalsNotesPvSubdealer : 0;
    if ($IsWithdrawalsNotesPvSubdealer != '') {

        /* actualiza el valor y estado de un objeto MandanteDetalle según condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIONOTESPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($WithdrawalsNotesPvSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($WithdrawalsNotesPvSubdealer);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones para insertar detalles del mandante si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($WithdrawalsNotesPvSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros a variables, manejando condiciones sobre el segundo parámetro. */
    $IsSportbookGgrAfSubdealer = $params->IsSportbookGgrAfSubdealer;
    $SportbookGgrAfSubdealer = ($params->SportbookGgrAfSubdealer != '') ? $params->SportbookGgrAfSubdealer : 0;
    if ($IsSportbookGgrAfSubdealer != '') {

        /* Código para actualizar detalles de un mandante en función de condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIOSBGGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrAfSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrAfSubdealer);
                $MandanteDetalle->setEstado($IsSportbookGgrAfSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, inserta un objeto MandanteDetalle si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrAfSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrAfSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros a variables, con manejo de condiciones y valores predeterminados. */
    $IsSportbookNgrAfSubdealer = $params->IsSportbookNgrAfSubdealer;
    $SportbookNgrAfSubdealer = ($params->SportbookNgrAfSubdealer != '') ? $params->SportbookNgrAfSubdealer : 0;
    if ($IsSportbookNgrAfSubdealer != '') {

        /* Código que actualiza detalles de un mandante en base a ciertas condiciones. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIOSBNGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookNgrAfSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookNgrAfSubdealer);
                $MandanteDetalle->setEstado($IsSportbookNgrAfSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones y registra detalles de un mandante en caso de código 34. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookNgrAfSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookNgrAfSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros a variables, asignando 0 si no hay datos. */
    $IsSportbookGgrPvSubdealer = $params->IsSportbookGgrPvSubdealer;
    $SportbookGgrPvSubdealer = ($params->SportbookGgrPvSubdealer != '') ? $params->SportbookGgrPvSubdealer : 0;
    if ($IsSportbookGgrPvSubdealer != '') {

        /* actualiza detalles de un mandante según ciertos criterios y condiciones. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIOSBGGRPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvSubdealer);
                $MandanteDetalle->setEstado($IsSportbookGgrPvSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Captura excepciones, verifica código y crea un objeto para insertarlo en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros relacionados con apuestas deportivas, manejando valores vacíos. */
    $IsBetSportPvSubdealer = $params->IsBetSportPvSubdealer;
    $BetSportPvSubdealer = ($params->BetSportPvSubdealer != '') ? $params->BetSportPvSubdealer : 0;
    if ($IsBetSportPvSubdealer != '') {

        /* Código para actualizar detalles de un mandante basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIOAPSPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($BetSportPvSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($BetSportPvSubdealer);
                $MandanteDetalle->setEstado($IsBetSportPvSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones y crea un objeto MandanteDetalle si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BetSportPvSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsBetSportPvSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación de valores basados en parámetros, manejando condiciones específicas para un casino. */
    $IsCasinoNgrAfSubdealer = $params->IsCasinoNgrAfSubdealer;
    $CasinoNgrAfSubdealer = ($params->CasinoNgrAfSubdealer != '') ? $params->CasinoNgrAfSubdealer : 0;
    if ($IsCasinoNgrAfSubdealer != '') {

        /* actualiza detalles de un mandante en base a condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIOCASINONGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($CasinoNgrAfSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($CasinoNgrAfSubdealer);
                $MandanteDetalle->setEstado($IsCasinoNgrAfSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones y registra detalles de "Mandante" dependiendo del código de error. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($CasinoNgrAfSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsCasinoNgrAfSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores a dos variables basadas en parámetros recibidos. */
    $IsDepositCommissionTransactionSubdealer = $params->IsDepositCommissionTransactionSubdealer;
    $DepositCommissionTransactionSubdealer = ($params->DepositCommissionTransactionSubdealer != '') ? $params->DepositCommissionTransactionSubdealer : 0;
    if ($IsDepositCommissionTransactionSubdealer != '') {

        /* Actualiza detalles de comisiones de un mandante basado en una transacción específica. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIODEPOSITCT');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositCommissionTransactionSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositCommissionTransactionSubdealer);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, inserta un registro en base de datos si el código es 34. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositCommissionTransactionSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* asigna valores a variables basadas en parámetros de entrada. */
    $IsDepositAfSubdealer = $params->IsDepositAfSubdealer;
    $DepositAfSubdealer = ($params->DepositAfSubdealer != '') ? $params->DepositAfSubdealer : 0;
    if ($IsDepositAfSubdealer != '') {

        /* actualiza el valor y estado de un objeto MandanteDetalle basado en condiciones. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONSUBCONCESIONARIODEPOSITAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositAfSubdealer != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositAfSubdealer);
                $MandanteDetalle->setEstado($IsDepositAfSubdealer);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta un objeto en base de datos si se cumple una condición. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositAfSubdealer);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositAfSubdealer);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros a variables, manejando casos de valores vacíos. */
    $IsSportbookGgrPvIpPointSale = $params->IsSportbookGgrPvIpPointSale;
    $SportbookGgrPvIpPointSale = ($params->SportbookGgrPvIpPointSale != '') ? $params->SportbookGgrPvIpPointSale : 0;
    if ($IsSportbookGgrPvIpPointSale != '') {

        /* actualiza los detalles del mandante basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVSBGGRPVIP');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvIpPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvIpPointSale);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejamos excepciones; insertamos un objeto si el código de error es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvIpPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores a variables basadas en parámetros y verifica si están vacíos. */
    $IsDepositPvPointSale = $params->IsDepositPvPointSale;
    $DepositPvPointSale = ($params->DepositPvPointSale != '') ? $params->DepositPvPointSale : 0;
    if ($IsDepositPvPointSale != '') {

        /* Código que actualiza los detalles de un mandante en función de condiciones especificadas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVDEPOSITPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositPvPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositPvPointSale);
                $MandanteDetalle->setEstado($IsDepositPvPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones específicas y crea un nuevo registro en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositPvPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositPvPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna si hay notas de retiro; si no, establece el valor en cero. */
    $IsWithdrawalsNotesPvPointSale = $params->IsWithdrawalsNotesPvPointSale;
    $WithdrawalsNotesPvPointSale = ($params->WithdrawalsNotesPvPointSale != '') ? $params->WithdrawalsNotesPvPointSale : 0;
    if ($IsWithdrawalsNotesPvPointSale != '') {

        /* Actualiza el valor y estado de MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVNOTESPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($WithdrawalsNotesPvPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($WithdrawalsNotesPvPointSale);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que crea un objeto y lo inserta en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($WithdrawalsNotesPvPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asigna un valor condicionado a una variable según un parámetro dado. */
    $IsSportbookGgrAfPointSale = $params->IsSportbookGgrAfPointSale;
    $SportbookGgrAfPointSale = ($params->SportbookGgrAfPointSale != '') ? $params->SportbookGgrAfPointSale : 0;
    if ($IsSportbookGgrAfPointSale != '') {

        /* Actualiza detalles del mandante si el valor de Sportbook cambia. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVSBGGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrAfPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrAfPointSale);
                $MandanteDetalle->setEstado($IsSportbookGgrAfPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones para insertar un objeto MandanteDetalle si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrAfPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrAfPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* asigna valores relacionados con apuestas deportivas según parámetros proporcionados. */
    $IsSportbookNgrAfPointSale = $params->IsSportbookNgrAfPointSale;
    $SportbookNgrAfPointSale = ($params->SportbookNgrAfPointSale != '') ? $params->SportbookNgrAfPointSale : 0;
    if ($IsSportbookNgrAfPointSale != '') {

        /* Código que actualiza detalles de un mandante según condiciones específicas y clasificador. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVSBNGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookNgrAfPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookNgrAfPointSale);
                $MandanteDetalle->setEstado($IsSportbookNgrAfPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones y guarda detalles en base de datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookNgrAfPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookNgrAfPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores a variables desde parámetros, con verificación de vacío. */
    $IsSportbookGgrPvPointSale = $params->IsSportbookGgrPvPointSale;
    $SportbookGgrPvPointSale = ($params->SportbookGgrPvPointSale != '') ? $params->SportbookGgrPvPointSale : 0;
    if ($IsSportbookGgrPvPointSale != '') {

        /* Actualiza detalles de mandante si el valor de Sportbook cambia. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVSBGGRPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvPointSale);
                $MandanteDetalle->setEstado($IsSportbookGgrPvPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones para insertar detalles de mandante si el código de error es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros a variables, manejando posible ausencia de datos. */
    $IsBetSportPvPointSale = $params->IsBetSportPvPointSale;
    $BetSportPvPointSale = ($params->BetSportPvPointSale != '') ? $params->BetSportPvPointSale : 0;
    if ($IsBetSportPvPointSale != '') {

        /* actualiza detalles de un mandante si cambian ciertos valores. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVAPSPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($BetSportPvPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($BetSportPvPointSale);
                $MandanteDetalle->setEstado($IsBetSportPvPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Captura excepciones y inserta detalles en base de datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BetSportPvPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsBetSportPvPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación de valores de parámetros relacionados con las ventas en un casino. */
    $IsCasinoNgrAfPointSale = $params->IsCasinoNgrAfPointSale;
    $CasinoNgrAfPointSale = ($params->CasinoNgrAfPointSale != '') ? $params->CasinoNgrAfPointSale : 0;
    if ($IsCasinoNgrAfPointSale != '') {

        /* Código para actualizar detalles de un mandante basándose en un clasificador específico. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVCASINONGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($CasinoNgrAfPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($CasinoNgrAfPointSale);
                $MandanteDetalle->setEstado($IsCasinoNgrAfPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepción: inserta detalles en la base de datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($CasinoNgrAfPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsCasinoNgrAfPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación de variables basadas en parámetros con validación para evitar valores vacíos. */
    $IsDepositCommissionTransactionPointSale = $params->IsDepositCommissionTransactionPointSale;
    $DepositCommissionTransactionPointSale = ($params->DepositCommissionTransactionPointSale != '') ? $params->DepositCommissionTransactionPointSale : 0;
    if ($IsDepositCommissionTransactionPointSale != '') {

        /* actualiza datos de un mandante según comisiones de transacciones. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVDEPOSITCT');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositCommissionTransactionPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositCommissionTransactionPointSale);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta un registro en base de datos si código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositCommissionTransactionPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros, con control sobre depósitos en punto de venta. */
    $IsDepositAfPointSale = $params->IsDepositAfPointSale;
    $DepositAfPointSale = ($params->DepositAfPointSale != '') ? $params->DepositAfPointSale : 0;
    if ($IsDepositAfPointSale != '') {

        /* Código para actualizar detalles de mandante basado en comisión y condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONPVDEPOSITAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositAfPointSale != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositAfPointSale);
                $MandanteDetalle->setEstado($IsDepositAfPointSale);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, crea un objeto y lo inserta en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositAfPointSale);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositAfPointSale);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros a variables, manejando un caso para vacío. */
    $IsSportbookGgrPvIpAffiliate = $params->IsSportbookGgrPvIpAffiliate;
    $SportbookGgrPvIpAffiliate = ($params->SportbookGgrPvIpAffiliate != '') ? $params->SportbookGgrPvIpAffiliate : 0;
    if ($IsSportbookGgrPvIpAffiliate != '') {

        /* actualiza un registro de MandanteDetalle basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORSBGGRPVIP');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvIpAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvIpAffiliate);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta datos en la base si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvIpAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvIpAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* asigna valores de parámetros a variables, manejando nulos o vacíos. */
    $IsDepositPvAffiliate = $params->IsDepositPvAffiliate;
    $DepositPvAffiliate = ($params->DepositPvAffiliate != '') ? $params->DepositPvAffiliate : 0;
    if ($IsDepositPvAffiliate != '') {

        /* actualiza detalles de un mandante basado en condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORDEPOSITPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositPvAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositPvAffiliate);
                $MandanteDetalle->setEstado($IsDepositPvAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Captura excepciones y maneja inserciones de datos en base según código específico. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositPvAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositPvAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asignación de variables para gestionar notas de retiros de afiliados. */
    $IsWithdrawalsNotesPvAffiliate = $params->IsWithdrawalsNotesPvAffiliate;
    $WithdrawalsNotesPvAffiliate = ($params->WithdrawalsNotesPvAffiliate != '') ? $params->WithdrawalsNotesPvAffiliate : 0;
    if ($IsWithdrawalsNotesPvAffiliate != '') {

        /* actualiza detalles de un mandante según condiciones específicas de afiliación. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORNOTESPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($WithdrawalsNotesPvAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($WithdrawalsNotesPvAffiliate);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta un detalle si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($WithdrawalsNotesPvAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsWithdrawalsNotesPvAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores a variables según condiciones específicas del objeto $params. */
    $IsSportbookGgrAfAffiliate = $params->IsSportbookGgrAfAffiliate;
    $SportbookGgrAfAffiliate = ($params->SportbookGgrAfAffiliate != '') ? $params->SportbookGgrAfAffiliate : 0;
    if ($IsSportbookGgrAfAffiliate != '') {

        /* Código para actualizar detalles de un mandante en función de condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORSBGGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrAfAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrAfAffiliate);
                $MandanteDetalle->setEstado($IsSportbookGgrAfAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones y guarda detalles de mandante si hay un código específico. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrAfAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrAfAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros a variables, usando una condición para manejar vacíos. */
    $IsSportbookNgrAfAffiliate = $params->IsSportbookNgrAfAffiliate;
    $SportbookNgrAfAffiliate = ($params->SportbookNgrAfAffiliate != '') ? $params->SportbookNgrAfAffiliate : 0;
    if ($IsSportbookNgrAfAffiliate != '') {

        /* Actualiza el valor y estado de MandanteDetalle si es diferente al actual. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORSBNGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookNgrAfAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookNgrAfAffiliate);
                $MandanteDetalle->setEstado($IsSportbookNgrAfAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepción que crea un objeto y lo inserta en base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookNgrAfAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookNgrAfAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan variables basadas en parámetros, manejando valores vacíos. */
    $IsSportbookGgrPvAffiliate = $params->IsSportbookGgrPvAffiliate;
    $SportbookGgrPvAffiliate = ($params->SportbookGgrPvAffiliate != '') ? $params->SportbookGgrPvAffiliate : 0;
    if ($IsSportbookGgrPvAffiliate != '') {

        /* Actualiza valores de MandanteDetalle si no coinciden con el Sportbook GgrPv Affiliate. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORSBGGRPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($SportbookGgrPvAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($SportbookGgrPvAffiliate);
                $MandanteDetalle->setEstado($IsSportbookGgrPvAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones: inserta un detalle si el código de error es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($SportbookGgrPvAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsSportbookGgrPvAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores de parámetros relacionados con una afiliación de apuestas deportivas. */
    $IsBetSportPvAffiliate = $params->IsBetSportPvAffiliate;
    $BetSportPvAffiliate = ($params->BetSportPvAffiliate != '') ? $params->BetSportPvAffiliate : 0;
    if ($IsBetSportPvAffiliate != '') {

        /* Se actualiza el valor y estado de MandanteDetalle si cambia respecto a BetSportPvAffiliate. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORAPSPV');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($BetSportPvAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($BetSportPvAffiliate);
                $MandanteDetalle->setEstado($IsBetSportPvAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones: inserta un objeto si el código de error es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($BetSportPvAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsBetSportPvAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Asigna valores a variables según condiciones de parámetros recibidos. */
    $IsCasinoNgrAfAffiliate = $params->IsCasinoNgrAfAffiliate;
    $CasinoNgrAfAffiliate = ($params->CasinoNgrAfAffiliate != '') ? $params->CasinoNgrAfAffiliate : 0;
    if ($IsCasinoNgrAfAffiliate != '') {

        /* actualiza información de un mandante según ciertos criterios y condiciones. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORCASINONGRAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($CasinoNgrAfAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($CasinoNgrAfAffiliate);
                $MandanteDetalle->setEstado($IsCasinoNgrAfAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, crea un objeto y lo inserta en la base de datos si corresponde. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($CasinoNgrAfAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsCasinoNgrAfAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de comisiones de transacciones dependiendo de la existencia de parámetros. */
    $IsDepositCommissionTransactionAffiliate = $params->IsDepositCommissionTransactionAffiliate;
    $DepositCommissionTransactionAffiliate = ($params->DepositCommissionTransactionAffiliate != '') ? $params->DepositCommissionTransactionAffiliate : 0;
    if ($IsDepositCommissionTransactionAffiliate != '') {

        /* Actualiza detalles de comisión de afiliador en base a condiciones específicas. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORDEPOSITCT');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositCommissionTransactionAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositCommissionTransactionAffiliate);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de errores para insertar un objeto MandanteDetalle en la base de datos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositCommissionTransactionAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositCommissionTransactionAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }


    /* Se asignan valores de parámetros a variables, manejando un caso de depósito vacío. */
    $IsDepositAfAffiliate = $params->IsDepositAfAffiliate;
    $DepositAfAffiliate = ($params->DepositAfAffiliate != '') ? $params->DepositAfAffiliate : 0;
    if ($IsDepositAfAffiliate != '') {

        /* Actualiza el valor y estado de MandanteDetalle si son diferentes al depósito actual. */
        $Clasificador = new Clasificador('', 'LIMITCOMISIONAFILIADORDEPOSITAFILIADOS');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');

            if ($DepositAfAffiliate != $MandanteDetalle->getValor()) {
                $MandanteDetalle->setValor($DepositAfAffiliate);
                $MandanteDetalle->setEstado($IsDepositAfAffiliate);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Manejo de excepciones que inserta un objeto en la base de datos si el código es 34. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($DepositAfAffiliate);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsDepositAfAffiliate);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }
//FIN LIMITE DE COMISIONES

    /* Asigna el valor de IsActiveAnonymousBets de $params a $IsActiveAnonymousBets. */
    $IsActiveAnonymousBets = $params->IsActiveAnonymousBets;
    if ($IsActiveAnonymousBets !== "") {

        /* Actualiza el estado de apuestas anónimas en la base de datos, si es necesario. */
        $IsActiveAnonymousBets = ($params->IsActiveAnonymousBets == 1) ? 'A' : 'I';
        $Clasificador = new Clasificador('', 'ACTIVEANONYMOUSBETS');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');
            if ($IsActiveAnonymousBets !== $MandanteDetalle->getEstado()) {
                $MandanteDetalle->setValor($IsActiveAnonymousBets);
                $MandanteDetalle->setEstado($IsActiveAnonymousBets);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, creando un objeto y guardando datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActiveAnonymousBets);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($IsActiveAnonymousBets);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    /* Asigna el valor de IsActiveAnonymousDocument de $params a IsActiveAnonymousDocument. */
    $IsActiveAnonymousDocument = $params->IsActiveAnonymousDocument;
    if ($IsActiveAnonymousDocument !== "") {

        /* Actualiza el estado de apuestas anónimas con número de documento en la base de datos, si es necesario. */
        $IsActiveAnonymousDocument = ($params->IsActiveAnonymousDocument == 1) ? 'A' : 'I';
        $Clasificador = new Clasificador('', 'DOCUAPUANONIMA');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');
            if ($IsActiveAnonymousDocument !== $MandanteDetalle->getValor()) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, creando un objeto y guardando datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActiveAnonymousDocument);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    /* Asigna el valor de IsActiveAnonymousPhone de $params a IsActiveAnonymousPhone. */
    $IsActiveAnonymousPhone = $params->IsActiveAnonymousPhone;
    if ($IsActiveAnonymousPhone !== "") {

        /* Actualiza el estado de apuestas anónimas con celular en la base de datos, si es necesario. */
        $IsActiveAnonymousPhone = ($params->IsActiveAnonymousPhone == 1) ? 'A' : 'I';
        $Clasificador = new Clasificador('', 'CELUAPUANONIMA');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');
            if ($IsActiveAnonymousPhone !== $MandanteDetalle->getValor()) {
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Maneja excepciones, creando un objeto y guardando datos si el código es '34'. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($IsActiveAnonymousPhone);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }
//Codigo Mincetur por Partner

    /* Se asigna el valor de CodeMincetur desde los parámetros a una variable. */
    $CodeMincetur = $params->CodeMincetur;
    if ($CodeMincetur != '' && $CodeMincetur != null) {
        try {

            /* Código para actualizar un registro de mandante si el valor ha cambiado. */
            $Clasificador = new Clasificador('', 'CODEMINCETUR');
            $tipoDetalle = $Clasificador->getClasificadorId();
            try {
                $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');
                if ($MandanteDetalle->getValor !== $CodeMincetur) {
                    $MandanteDetalle->setValor($CodeMincetur);
                    $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                }
            } catch (Exception $e) {
                /* Manejo de excepciones que inserta detalles de mandante en base de datos. */

                if ($e->getCode() == 34) {
                    $MandanteDetalle = new MandanteDetalle();

                    $MandanteDetalle->setMandante($Mandante->mandante);
                    $MandanteDetalle->setTipo($tipoDetalle);
                    $MandanteDetalle->setValor($CodeMincetur);
                    $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                    $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                    $MandanteDetalle->setPaisId($Country);
                    $MandanteDetalle->setEstado('A');

                    $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                    $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
                }
            }
        } catch (Exception $e) {
            /* Captura excepciones en PHP sin realizar ninguna acción específica. */

        }
    }

//SE RECIBE EL PARAMETRO PARA CONFIGURAR SI SE DESEA APROBAR LAS NOTAS DE RETIRO POR PARTNER

    /* Asigna el valor de 'ActivateWithdrawalNotes' desde $params a la variable correspondiente. */
    $ActivateWithdrawalNotes = $params->ActivateWithdrawalNotes;
    if ($ActivateWithdrawalNotes !== "") {

        /* actualiza el estado de un detalle del mandante con base en condiciones específicas. */
        $Clasificador = new Clasificador('', 'ACTIVATEWITHDRAWALNOTES');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, '');
            if ($ActivateWithdrawalNotes !== $MandanteDetalle->getEstado()) {
                $MandanteDetalle->setValor($ActivateWithdrawalNotes);
                $MandanteDetalle->setEstado($ActivateWithdrawalNotes);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $e) {
            /* Maneja excepciones para insertar datos en la base de datos según códigos específicos. */

            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($ActivateWithdrawalNotes);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado($ActivateWithdrawalNotes);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                throw $e;
            }
        }
    }

    if ($MaximumAmountDailyWithdrawalsClient !== null && $MaximumAmountDailyWithdrawalsClient !== '' && is_numeric($MaximumAmountDailyWithdrawalsClient)) {
        /** Actualizando máximo valor a retirar por día por parte de los usuarios online
         *Cuando el valor es cero no se realiza la validación, si el valor es negativo ningún retiro es autorizado
         */

        /* verifica y actualiza el estado de un registro de mandato según condiciones específicas. */
        $Clasificador = new Clasificador(null, 'MAXAMOUNTUSERWITHDRAWALS');
        $tipoDetalle = $Clasificador->getClasificadorId();
        $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);

        try {
            $MandanteDetalle = new MandanteDetalle(null, $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $MaximumAmountDailyWithdrawalsClient) {
//Inactivando anterior registro
                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                throw new Exception('', 34);
            }
        } catch (Exception $e) {
            /* Manejando excepciones, se asignan valores y se inserta un registro en la base de datos. */

            if ($e->getCode() != 34) throw $e;

            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($tipoDetalle);
            $MandanteDetalle->setValor($MaximumAmountDailyWithdrawalsClient);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    if (!empty($FirstDeposit)) {

        /* Actualiza el valor de un depósito inicial en la base de datos si es diferente. */
        try {
            $Clasificador = new Clasificador('', 'FIRSTDEPOSITWITHDRAW');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($FirstDeposit !== $MandanteDetalle->valor) {
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setValor($FirstDeposit);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $MandanteDetalleMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
            /* Manejo de excepciones y creación de registro en base de datos de MandanteDetalle. */
            if ($ex->getCode() != 34) throw new $ex;
            $MandanteDetalle = new MandanteDetalle();

            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($FirstDeposit);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $MandanteDetalleMySqlDAO->getTransaction()->commit();
        }
    }

    if (!empty($VerifiedMail)) {

        /* actualiza el valor del detalle del mandante si el correo no coincide. */
        try {
            $Clasificador = new Clasificador('', 'VERIFMAILWITHDRAW');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($VerifiedMail !== $MandanteDetalle->valor) {
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setValor($VerifiedMail);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $MandanteDetalleMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
            /* inserta un registro de "MandanteDetalle" en la base de datos. */
            if ($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($VerifiedMail);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $MandanteDetalleMySqlDAO->getTransaction()->commit();
        }
    }

    if (!empty($VerifiedPhone)) {

        /* Actualiza el valor de teléfono verificado en la base de datos si es diferente. */
        try {
            $Clasificador = new Clasificador('', 'VERIFPHONEWITHDRAW');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($VerifiedPhone !== $MandanteDetalle->valor) {
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setValor($VerifiedPhone);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $MandanteDetalleMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
            /* Manejo de excepciones al insertar detalles de mandante en la base de datos. */
            if ($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($VerifiedPhone);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $MandanteDetalleMySqlDAO->getTransaction()->commit();
        }
    }

    if (!empty($OtpNotesTime)) {

        /* Actualiza el valor de MandanteDetalle si difiere de OtpNotesTime. */
        try {
            $Clasificador = new Clasificador('', 'MAXTIMEOTPCODE');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->getValor() !== $OtpNotesTime) {
                $MandanteDetalle->setValor($OtpNotesTime);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $MandanteDetalleMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
            /* Manejo de excepciones para insertar datos en base de datos usando un objeto. */
            if ($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($OtpNotesTime);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $MandanteDetalleMySqlDAO->getTransaction()->commit();
        }
    }

    if (!empty($ActiveWithdrawExpiration)) {

        /* Actualiza el valor de MandanteDetalle si es diferente al valor activo establecido. */
        try {
            $Clasificador = new Clasificador('', 'WITHDRAWAUTOEXPIRE');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if ($MandanteDetalle->valor !== $ActiveWithdrawExpiration) {
                $MandanteDetalle->valor = $ActiveWithdrawExpiration;
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $MandanteDetalleMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
            /* Captura excepciones y guarda información en base de datos si no hay errores. */
            if ($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($ActiveWithdrawExpiration);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $MandanteDetalleMySqlDAO->getTransaction()->commit();
        }
    }

    if (!empty($WithdrawExpirationTime)) {

        /* Actualiza el valor de MandanteDetalle si es diferente a WithdrawExpirationTime. */
        try {
            $Clasificador = new Clasificador('', 'WITHDRAWAUTOEXPIRETIME');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');
            if ($MandanteDetalle->valor !== $WithdrawExpirationTime) {
                $MandanteDetalle->valor = $WithdrawExpirationTime;
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                $MandanteDetalleMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
            /* Manejo de excepciones para insertar un objeto en la base de datos. */
            if ($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($WithdrawExpirationTime);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            $MandanteDetalleMySqlDAO->getTransaction()->commit();
        }
    }

    if (!empty($TaxPayments)) {

        /* verifica y actualiza el estado de un objeto 'MandanteDetalle' basado en condiciones. */
        $Clasificador = new Clasificador('', 'PRIZEPAYMENTTAX');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $tipoDetalle, $Country, 'A');

            if ($MandanteDetalle->getValor() != $TaxPayments) {

                $MandanteDetalle->setEstado('I');
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);

                throw new Exception('', '34');
            }
        } catch (Exception $e) {


            /* inserta datos de "MandanteDetalle" en la base de datos si se cumple una condición. */
            if ($e->getCode() == '34') {

                $MandanteDetalle = new MandanteDetalle();

                $MandanteDetalle->setMandante($Mandante->mandante);
                $MandanteDetalle->setTipo($tipoDetalle);
                $MandanteDetalle->setValor($TaxPayments);
                $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
                $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);

            } else {
                /* lanza una excepción si una condición previa no se cumple. */

                throw $e;
            }
        }
    }

    if(!empty($IsActivateOtpNotesPuntoDeVenta)) {
        try {

            /* Actualiza el estado de un detalle y registra auditoría en base de datos. */
            $Clasificador = new Clasificador('', 'OTPCODESALESPOINT');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $IsActivateOtpNotesPuntoDeVenta) {

                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'OTP_CODE_SALES_POINT_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $IsActivateOtpNotesPuntoDeVenta,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'OTPCODESALESPOINT'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($IsActivateOtpNotesPuntoDeVenta);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            /* maneja excepciones y guarda detalles en la base de datos. */
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($IsActivateOtpNotesPuntoDeVenta);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    if(!empty($IsActivateOtpNotesCuentaBancaria)) {
        try {

            /* actualiza un detalle de mandante y registra auditoría para cambios. */
            $Clasificador = new Clasificador('', 'OTPCODEBANKACCOUNT');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $IsActivateOtpNotesCuentaBancaria) {

                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'OTP_CODE_BANK_ACCOUNT_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $IsActivateOtpNotesCuentaBancaria,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'OTPCODEBANKACCOUNT'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($IsActivateOtpNotesCuentaBancaria);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            /* Manejo de excepciones y almacenamiento de detalles del mandante en base de datos. */
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($IsActivateOtpNotesCuentaBancaria);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    if(!empty($IsActivateOtpNotesSmsCB)) {
        try {

            /* Código para actualizar el valor de un detalle y auditar cambios en la base de datos. */
            $Clasificador = new Clasificador('', 'OTPCODEBANKACCOUNTSMS');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $IsActivateOtpNotesSmsCB) {

                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'OTP_CODE_BANK_ACCOUNT_SMS_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $IsActivateOtpNotesSmsCB,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'OTPCODEBANKACCOUNTSMS'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($IsActivateOtpNotesSmsCB);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            /* maneja excepciones y crea un objeto para insertar en la base de datos. */
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($IsActivateOtpNotesSmsCB);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    if(!empty($IsActivateOtpNotesEmailCB)) {
        try {

            /* Actualiza el estado de un detalle de mandante y registra auditoría correspondiente. */
            $Clasificador = new Clasificador('', 'OTPCODEBANKACCOUNTEMAIL');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $IsActivateOtpNotesEmailCB) {

                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'OTP_CODE_BANK_ACCOUNT_EMAIL_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $IsActivateOtpNotesEmailCB,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'OTPCODEBANKACCOUNTEMAIL'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($IsActivateOtpNotesEmailCB);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            /* maneja excepciones y crea un objeto 'MandanteDetalle' para insertarlo en la base de datos. */
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($IsActivateOtpNotesEmailCB);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    if(!empty($IsActivateOtpNotesSmsPV)) {
        try {

            /* actualiza un valor y registra cambios en la auditoría si es necesario. */
            $Clasificador = new Clasificador('', 'OTPCODESALESPOINTSMS');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $IsActivateOtpNotesSmsPV) {

                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'OTP_CODE_SALES_POINT_SMS_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $IsActivateOtpNotesSmsPV,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'OTPCODESALESPOINTSMS'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($IsActivateOtpNotesSmsPV);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            /* Captura excepciones y crea un nuevo registro en la base de datos. */
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($IsActivateOtpNotesSmsPV);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    if(!empty($IsActivateOtpNotesEmailPV)) {
        try {

            /* Actualiza el valor de MandanteDetalle y registra auditoría si hay cambio. */
            $Clasificador = new Clasificador('', 'OTPCODESALESPOINTEMAIL');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $IsActivateOtpNotesEmailPV) {

                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'OTP_CODE_SALES_POINT_EMAIL_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $IsActivateOtpNotesEmailPV,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'OTPCODESALESPOINTEMAIL'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($IsActivateOtpNotesEmailPV);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            /* Captura excepciones y crea un objeto antes de insertarlo en la base de datos. */
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($IsActivateOtpNotesEmailPV);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    // Tipo de tiempo (Dias, Horas, Minutos) para el canje de regalo en la tienda de lealtad
    if(!empty($TypeOfTime)) {
        try {
            $Clasificador = new Clasificador('', 'TYPEOFTIMEFOREXCHANGEGIFT');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $TypeOfTime) {

                // Auditoria
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'TYPE_OF_TIME_FOR_EXCHANGE_GIFT_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $TypeOfTime,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'TYPEOFTIMEFOREXCHANGEGIFT'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($TypeOfTime);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($TypeOfTime);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    // Tiempo para el canje de regalo en la tienda de lealtad
    if(!empty($TimeForExchange)) {
        try {
            $Clasificador = new Clasificador('', 'TIMEFOREXCHANGEGIFT');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $TimeForExchange) {

                // Auditoria
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'TIME_FOR_EXCHANGE_GIFT_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $TimeForExchange,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'TIMEFOREXCHANGEGIFT'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($TimeForExchange);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($TimeForExchange);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    // "A" si para realizar el canje de un regalo se debe esperar un lapso de tiempo
    if(!empty($ExchangeOfTheSameGiftEveryXTime)) {
        try {
            $Clasificador = new Clasificador('', 'EXCHANGEGIFTEVERYXTIME');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $ExchangeOfTheSameGiftEveryXTime) {

                // Auditoria
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'EXCHANGE_GIFT_EVERY_X_TIME_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $ExchangeOfTheSameGiftEveryXTime,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'EXCHANGEGIFTEVERYXTIME'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($ExchangeOfTheSameGiftEveryXTime);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($ExchangeOfTheSameGiftEveryXTime);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    // Tiempo minimo entre canje de cualquier regalo (valor entero)
    if(!empty($MinimumTimeBetweenAnyExchanges)) {
        try {
            $Clasificador = new Clasificador('', 'MINIMUMTIMEBETWEENANYEXCHANGES');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $MinimumTimeBetweenAnyExchanges) {

                // Auditoria
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'MINIMUM_TIME_BETWEEN_ANY_EXCHANGES_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $MinimumTimeBetweenAnyExchanges,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'MINIMUMTIMEBETWEENANYEXCHANGES'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($MinimumTimeBetweenAnyExchanges);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($MinimumTimeBetweenAnyExchanges);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    // Tipo de tiempo (Dias, Horas, Minutos) para el canje de cualquier regalo en la tienda de lealtad
    if(!empty($TypeOfTimeGeneral)) {
        try {
            $Clasificador = new Clasificador('', 'TYPEOFTIMEFOREXCHANGEGIFTGENERAL');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $TypeOfTimeGeneral) {

                // Auditoria
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'TYPE_OF_TIME_FOR_EXCHANGE_GIFT_GENERAL_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $TypeOfTimeGeneral,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'TYPEOFTIMEFOREXCHANGEGIFTGENERAL'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($TypeOfTimeGeneral);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($TypeOfTimeGeneral);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    // Tiempo para el canje de cualquier regalo en la tienda de lealtad
    if(!empty($TimeForExchangeGeneral)) {
        try {
            $Clasificador = new Clasificador('', 'TIMEFOREXCHANGEGIFTGENERAL');
            $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Country, 'A');

            if($MandanteDetalle->getValor() !== $TimeForExchangeGeneral) {

                // Auditoria
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                $auditoriaGeneral = (object) [
                    'usuarioId' => $_SESSION["usuario"],
                    'usuariosolicitaId' => $_SESSION["usuario"],
                    'tipo' => 'TIME_FOR_EXCHANGE_GIFT_GENERAL_EDITED',
                    'estado' => 'A',
                    'valorAntes' => $MandanteDetalle->getValor(),
                    'valorDespues' => $TimeForExchangeGeneral,
                    'usucreaId' => $_SESSION["usuario"],
                    'observacion' => json_encode(['action' => 'edit', 'field' => 'TIMEFOREXCHANGEGIFTGENERAL'])
                ];
                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);

                $MandanteDetalle->setValor($TimeForExchangeGeneral);
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
            }
        } catch (Exception $ex) {
            if($ex->getCode() != 34) throw new $ex;

            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($Mandante->mandante);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor($TimeForExchangeGeneral);
            $MandanteDetalle->setUsucreaId($_SESSION['usuario']);
            $MandanteDetalle->setUsumodifId($_SESSION['usuario']);
            $MandanteDetalle->setPaisId($Country);
            $MandanteDetalle->setEstado('A');

            $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
            $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
        }
    }

    $Transaction->commit();
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
} catch (Exception $e) {
    if ($_ENV['debug']) {
        print_r($e);
    }
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "Seleccione un pais de referencia";
    $response["ModelErrors"] = [];
}
