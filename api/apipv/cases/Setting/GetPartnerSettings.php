<?php

use Backend\dto\ApiTransaction;
use Backend\sql\Transaction;
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
use Backend\dto\CategoriaMandante;

/**
 * Setting/GetPartnerSetting
 *
 * Este script obtiene los ajustes de un partner específico basado en los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Country Código del país asociado al partner.
 * @param string $params->Partner Identificador del partner.
 * @param int $params->MaxRows (opcional) Número máximo de filas a procesar.
 * @param int $params->OrderedItem (opcional) Elemento ordenado.
 * @param int $params->SkeepRows (opcional) Número de filas a omitir.
 *
 * @return array Respuesta en formato JSON con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de modelo.
 * - data (array): Configuración del mandante.
 * - ReferredBonuses (array): Bonos utilizables en el programa de referidos.
 * - GamesCategories (array): Categorías asignables a los juegos de casino.
 * - LandingFreeBetBonuses (array): Bonos de apuestas gratuitas ofertados en la landing.
 * - LandingFreeCasinoBonuses (array): Bonos de casino gratuitos ofertados en la landing.
 */

/**
 * @OA\Post(path="apipv/Setting/GetPartnesSettings", tags={"Setting"}, description = "",
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
 *               @OA\Property(
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
 *               ),
 *               @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               )
 *             )
 *         )
 *     )
 * )
 */

$country = $params->Country;
$partner = $params->Partner;

if ($partner != "") {



    /* Se crean instancias de clases Mandante y MandanteDetalle, y se inicializa un array. */
    $Mandante = new Mandante($partner);

    $MandanteDetalle = new MandanteDetalle();


    $mandanteConfig = array();


    /* Configura un array con información de contacto de "Mandante" y aviso de expiración. */
    $mandanteConfig = array();
    $mandanteConfig["Address"] = $Mandante->contacto;
    $mandanteConfig["CompanyName"] = $Mandante->descripcion;
    $mandanteConfig["Email"] = $Mandante->email;
    $mandanteConfig["Phone"] = $Mandante->telefono;


    $mandanteConfig["DaysNotifyBeforePasswordExpire"] = "";

    /* Son configuraciones relacionadas con la gestión de contraseñas y solicitudes de usuario. */
    $mandanteConfig["UserPasswordMinLength"] = "";
    $mandanteConfig["UserTempPasswordExpireDays"] = "";
    $mandanteConfig["UserWrongLoginAttempts"] = "";
    $mandanteConfig["UserPasswordExpireDays"] = "";

    $mandanteConfig["MaxActiveRequests"] = "";

    /* Configuración de límites y cantidades para solicitudes de retiro. */
    $mandanteConfig["MaxRequestsPerDay"] = "";
    $mandanteConfig["MinAmountWithdrawKasnet"] = "";
    $mandanteConfig["MaxAmountWithdrawKasnet"] = "";
    $mandanteConfig["RequestMaxAmountWithdrawBetShop"] = "";
    $mandanteConfig["RequestMaxAmountWithdraw"] = "";
    $mandanteConfig["RequestMinAmountWithdraw"] = "";


    /* Configuración de montos y tasas para transacciones en el sistema. */
    $mandanteConfig["RequestMaxAmount"] = "";
    $mandanteConfig["RequestMinAmount"] = "";

    $mandanteConfig["TaxWithdrawBalanceAward"] = "";
    $mandanteConfig["TaxWithdrawBalanceAward2"] = "";
    $mandanteConfig["TaxWithdrawBalanceDeposit"] = "";

    /* Configura valores relacionados con impuestos en un sistema de gestión financiera. */
    $mandanteConfig["BetTaxValue"] = "";
    $mandanteConfig["DepositTaxValue"] = "";
    $mandanteConfig["TaxRegulator"] = "";
    $mandanteConfig["TaxWithdrawBalanceAwardFrom"] = "";
    $mandanteConfig["TaxWithdrawBalanceDepositFrom"] = "";
    $mandanteConfig["TaxBetPayPrize"] = "";

    /* Inicializa configuraciones vacías para pagos de impuestos y niveles de puntos. */
    $mandanteConfig["TaxPayments"] = "";


    $mandanteConfig["PointsLevelOne"] = "";
    $mandanteConfig["PointsLevelTwo"] = "";
    $mandanteConfig["PointsLevelThree"] = "";

    /* Variable de configuración vacía para niveles de puntos del mandante. */
    $mandanteConfig["PointsLevelFour"] = "";
    $mandanteConfig["PointsLevelFive"] = "";
    $mandanteConfig["PointsLevelSix"] = "";
    $mandanteConfig["PointsLevelSeven"] = "";
    $mandanteConfig["PointsLevelEight"] = "";
    $mandanteConfig["PointsLevelNine"] = "";

    /* Configuración de parámetros relacionados con puntos y lealtad en apuestas deportivas. */
    $mandanteConfig["PointsLevelTen"] = "";

    $mandanteConfig["LoyaltyDeposit"] = "";
    $mandanteConfig["LoyaltyBettingSportsSimple"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedTwo"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedThree"] = "";

    /* Configuración vacía para opciones de apuestas combinadas en un sistema de lealtad. */
    $mandanteConfig["LoyaltyBettingSportsCombinedFour"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedFive"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedSix"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedSeven"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedEight"] = "";
    $mandanteConfig["LoyaltyBettingSportsCombinedNine"] = "";

    /* Configuración de lealtad para apuestas y casino en un sistema. */
    $mandanteConfig["LoyaltyBettingSportsCombinedTen"] = "";
    $mandanteConfig["SportsAwardLoyalty"] = "";
    $mandanteConfig["LoyaltyBetCasino"] = "";
    $mandanteConfig["CasinoAwardLoyalty"] = "";
    $mandanteConfig["LoyaltyWithdrawal"] = "";
    $mandanteConfig["LoyaltyBaseValue"] = "";

    /* Configuración de parámetros para un sistema de lealtad y registro de usuarios. */
    $mandanteConfig["LoyaltyExpirationDate"] = "";

    $mandanteConfig["MaxAccountsBank"] = "";
    $mandanteConfig["ActivateRegisterUser"] = "I";
    $mandanteConfig["MinPercentageWagered"] = "0";
    $mandanteConfig["MonthsAlertChangePassword"] = "0";


    /* Variables para configurar porcentajes relacionados con depósitos, retiros y apuestas. */
    $mandanteConfig['PercentDepositValue'] = '';
    $mandanteConfig['PercentRetirementValue'] = '';
    $mandanteConfig['PercentValueSportsBets'] = '';
    $mandanteConfig['PercentValueNonSportBets'] = '';
    $mandanteConfig['PercentValueSportsAwards'] = '';
    $mandanteConfig['PercentValueNonSportsAwards'] = '';

    /* Configuración de porcentajes y límites para apuestas y tiendas físicas. */
    $mandanteConfig['PercentValueSportsBonds'] = '';
    $mandanteConfig['PercentValueNonSportsBounds'] = '';
    $mandanteConfig['PercentValueTickets'] = '';

    $mandanteConfig['LimitBalanceFormatSplaft'] = '';
    $mandanteConfig['TaxBetShopPhysical'] = '';

    /* configura variables relacionadas con finanzas y penalizaciones de cuentas. */
    $mandanteConfig['TaxPrizeBetShop'] = '';
    $mandanteConfig['ApproveBankAccounts'] = '';
    $mandanteConfig['PenaltyWithdrawalBalanceWithdrawal'] = '';
    $mandanteConfig['ApproveChangesInformation'] = '';
    $mandanteConfig['PenaltyWithdrawalBalanceRecharge'] = '';
    $mandanteConfig['MinimumWithdrawalBalanceWithdrawalsPenalty'] = '';

    /* Configuración de parámetros vacíos relacionados con sesiones, comisiones, penalizaciones y bonificaciones. */
    $mandanteConfig['SessionInativityLength'] = '';
    $mandanteConfig['TypeCommissionBetshop'] = '';
    $mandanteConfig['SessionLength'] = '';
    $mandanteConfig['TypeCommissionSettlements'] = '';
    $mandanteConfig['MinimumWithdrawalRefillsPenalty'] = '';
    $mandanteConfig['BonusesBalance'] = '';

    /* Configuración de balances y contingencias para un sistema de gestión financiera. */
    $mandanteConfig['WithdrawalsBalance'] = '';
    $mandanteConfig['RechargeBalance'] = '';
    $mandanteConfig['FreecasinoBalance'] = '';
    $mandanteConfig['Contingency'] = '';
    $mandanteConfig['FreebetBalance'] = '';
    $mandanteConfig['ProviderSMS'] = '';

    /* Configuración de proveedor con campos para email, CRM, verificación, firma y CPF. */
    $mandanteConfig['ProviderEmail'] = '';
    $mandanteConfig['ProviderCRM'] = '';
    $mandanteConfig['ProviderVerification'] = '';
    $mandanteConfig['ProviderSignature'] = '';
    $mandanteConfig['ProviderCPF'] = '';
    $mandanteConfig['VerificaFiltro'] = '';

    /* Configuración de parámetros para un sistema de gestión de mandantes. */
    $mandanteConfig['VerificaFiltroPV'] = '';
    $mandanteConfig['LevelName'] = '';
    $mandanteConfig['SetMins'] = "15";
    $mandanteConfig['NumRechazos'] = "0";
    $mandanteConfig['NumRechazosDocument'] = "0";
    $mandanteConfig['IsActiveSms'] = '';

    /* Configuración de opciones relacionadas con notificaciones y premios en la aplicación. */
    $mandanteConfig['IsActivePopUp'] = '';
    $mandanteConfig['IsActiveEmail'] = '';
    $mandanteConfig['IsActiveInbox'] = '';
    $mandanteConfig['AwardLimitFulfillmentDayReferred'] = '';
    $mandanteConfig['AwardLimitRedemptionDayReferent'] = '';
    $mandanteConfig['LandingFreeBetBonusesSelected'] = '';  //landing freebet

    /* Configuración de mandante con variables para bonificaciones, referencias y categorías excluidas. */
    $mandanteConfig['LandingFreeCasinoBonusesSelected'] = ''; //landing freecasino seleccionadas
    $mandanteConfig['ReferredAwards'] = [];
    $mandanteConfig['ExcludedCategoriesMinBetReferred'] = [];
    $mandanteConfig['AcceptReferred'] = false;
    $mandanteConfig['UrlLanding'] = '';
    $mandanteConfig['ReferredMinSelValue'] = '';

    /* Configuración inicial de parámetros relacionados con madante y condiciones de usuario. */
    $mandanteConfig['ReferentConditions'] = [];
    $mandanteConfig['ActivateWithdrawalNotes'] = 'I'; //Por defecto esta configuración está inactiva
    $mandanteConfig['MaximumAmountDailyWithdrawalsClient'] = 0; //Por defecto esta configuración está inactiva
    $mandanteConfig['FirstDeposit'] = 'I';
    $mandanteConfig['VerifiedMail'] = 'I';
    $mandanteConfig['VerifiedPhone'] = 'I';

    /* Configuración de parámetros relacionados con OTP y fechas de expiración de retiros. */
    $mandanteConfig['IsActivateOtpNotesPuntoDeVenta'] = 'I';
    $mandanteConfig['IsActivateOtpNotesCuentaBancaria'] = 'I';
    $mandanteConfig['OtpNotesTime'] = 0;
    $mandanteConfig['ActiveWithdrawExpiration'] = 'I';
    $mandanteConfig['WithdrawExpirationTime'] = 0;
    /* Se inicializa el estado en I la verificacion de premios de lealtad.*/
    $mandanteConfig["RequiresVerificationForLoyalty"] = 'I';
    $mandanteConfig["CommissionFreeTransactions"] = "I";
    $mandanteConfig["RestrictionTime"] = "";
    $referredBonuses = [];

    /* Se inicializa un arreglo vacío para categorías de juegos. */
    $GamesCategories = [];


    /* Asignación de parámetros y ajuste de filas a ignorar en un script. */
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    /* Se inicializa el estado en I en la parametrización de los switches para apuestas anónimas*/
    $mandanteConfig['IsActiveAnonymousBets'] = 'I';
    $mandanteConfig['IsActiveAnonymousDocument'] = 'I';
    $mandanteConfig['IsActiveAnonymousPhone'] = 'I';

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* inicializa `$OrderedItem` en 1 si está vacío; `$MaxRows` no tiene acción definida. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
    }

    /* Define variables y crea instancias de clases para manejo de transacciones y bonos. */
    $MaxRows = 100000000;

    $mismenus = "0";

    $Transaction = new Transaction();
    $BonoInterno = new BonoInterno();


//Solicitando bonos disponibles para landing

    /* Consulta SQL que selecciona bonos activos según ciertas condiciones de país y tipo. */
    $sql = "select bono_interno.bono_id, bono_interno.nombre, bono_interno.tipo
            from bono_interno
            inner join bono_detalle as detalle_pais on (bono_interno.bono_id = detalle_pais.bono_id)
            inner join bono_detalle as detalle_landing on (bono_interno.bono_id = detalle_landing.bono_id)
            where
            bono_interno.estado = 'A'
            and now() between bono_interno.fecha_inicio and bono_interno.fecha_fin
            and bono_interno.tipo in (3,5, 6,8)
            and bono_interno.mandante = '" . $partner . "'
            and detalle_pais.tipo = 'CONDPAISUSER'
            and detalle_pais.valor = " . $country . "
            and detalle_landing.tipo = 'ISFORLANDING'
            and detalle_landing.valor = 1
            order by bono_interno.tipo";
    $bonusesForLanding = $BonoInterno->execQuery($Transaction, $sql);

//Solicitando bonos ofertados en la landing
    $landingCurrentOffer = [];

    /* crea un clasificador y obtiene una oferta actual mediante un detalle. */
    try {
        $Clasificador = new Clasificador('', 'BONUSFORLANDING');
        $MandanteDetalle = new MandanteDetalle('', $partner, $Clasificador->getClasificadorId(), $country, 'A');
        $landingCurrentOffer = $MandanteDetalle->getValor();
        $landingCurrentOffer = explode(',', $landingCurrentOffer);
    } catch (Exception $e) {
        if ($e->getCode() != 34) throw $e;
    }


    /* Se inicializan dos arreglos para almacenar bonificaciones de apuestas y casinos gratuitas. */
    $landingFreeBetBonuses = [];
    $landingFreeCasinoBonuses = [];
    foreach ($bonusesForLanding as $bonusForLanding) {

        /* Crea un objeto de bono y lo almacena si es tipo FreeSpin. */
        $bonusObject = [
            'id' => $bonusForLanding->{'bono_interno.bono_id'},
            'value' => $bonusForLanding->{'bono_interno.nombre'} != '' ? $bonusForLanding->{'bono_interno.nombre'} : $bonusForLanding->{'bono_interno.bono_id'}
        ];
        $bonusObject = (object)$bonusObject;

//Almacenando bonos ofertados
        if ($bonusForLanding->{'bono_interno.tipo'} == 8) {
//Tipo 8 es FreeSpin
            array_push($landingFreeCasinoBonuses, $bonusObject);

            if (in_array($bonusForLanding->{'bono_interno.bono_id'}, $landingCurrentOffer)) {
                $mandanteConfig['LandingFreeCasinoBonusesSelected'] = $bonusForLanding->{'bono_interno.bono_id'};
            }
        }

//Almacenando bonos ofertados

        /* Verifica si el bono es tipo 5 y lo agrega a la lista correspondiente. */
        if ($bonusForLanding->{'bono_interno.tipo'} == 5) {
//Tipo 5 es FreeCasino
            array_push($landingFreeCasinoBonuses, $bonusObject);

            if (in_array($bonusForLanding->{'bono_interno.bono_id'}, $landingCurrentOffer)) {
                $mandanteConfig['LandingFreeCasinoBonusesSelected'] = $bonusForLanding->{'bono_interno.bono_id'};
            }
        }


        /* Verifica si un bono es tipo FreeBet y lo añade a una lista. */
        if ($bonusForLanding->{'bono_interno.tipo'} == 6) {
//Tipo 6 es FreeBet
            array_push($landingFreeBetBonuses, $bonusObject);

            if (in_array($bonusForLanding->{'bono_interno.bono_id'}, $landingCurrentOffer)) {
                $mandanteConfig['LandingFreeBetBonusesSelected'] = $bonusForLanding->{'bono_interno.bono_id'};
            }
        }

        /* Condición para asignar bonos de tipo No Depósito a ofertas actuales. */
        if ($bonusForLanding->{'bono_interno.tipo'} == 3) {
//Tipo 3 es No Depósito
            array_push($landingFreeBetBonuses, $bonusObject);

            if (in_array($bonusForLanding->{'bono_interno.bono_id'}, $landingCurrentOffer)) {
                $mandanteConfig['LandingFreeBetBonusesSelected'] = $bonusForLanding->{'bono_interno.bono_id'};
            }
        }
    }

    //Solicitando objetos de premios referidos
    $mandanteConfig['ReferredAwards'] = $MandanteDetalle->getPartnerAjustesReferidos($partner, $country);


    //Solicitando bonos utilizables programa de referidos
    $Transaction = new Transaction();
    $BonoInterno = new BonoInterno();
    $sql = "select bono_interno.bono_id, bono_interno.nombre, bono_interno.descripcion
        from bono_interno
        inner join bono_detalle bdetalle_a on (bono_interno.bono_id = bdetalle_a.bono_id)
        inner join bono_detalle bdetalle_b on (bono_interno.bono_id = bdetalle_b.bono_id)
        where bono_interno.estado = 'A'
        and bono_interno.mandante = " . $partner . "
        and bdetalle_a.tipo = 'CONDPAISUSER'
        and bdetalle_a.valor = " . $country . "
        and bdetalle_b.tipo = 'BONOREFERENTE'";
    $availableBonuses = $BonoInterno->execQuery($Transaction, $sql);

    $referredBonuses = array_map(function ($availableBonus) {
        $bonus = (object)[];
        $bonus->BonoId = $availableBonus->{'bono_interno.bono_id'};
        $bonus->Name = $availableBonus->{'bono_interno.nombre'};
        $bonus->Description = $availableBonus->{'bono_interno.descripcion'};
        return $bonus;
    }, $availableBonuses);


    //Solicitando categorias asignables a los juegos de casino --Referidos
    $CategoriaMandante = new CategoriaMandante();
    $rules = [];
    array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => 'CASINO', 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.estado', 'data' => 'A', 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.mandante', 'data' => -1, 'op' => 'eq']);
    $filters = ["rules" => $rules, "groupOp" => "AND"];
    $select = 'categoria_mandante.catmandante_id, categoria_mandante.descripcion, categoria_mandante.tipo';
    $GamesCategories = $CategoriaMandante->getCategoriaMandanteCustom($select, 'categoria_mandante.catmandante_id', 'ASC', 0, 100, json_encode($filters), true);
    $GamesCategories = json_decode($GamesCategories)->data;

    $GamesCategories = array_map(function ($gameCategory) {
        $category = (object)[];
        $category->id = $gameCategory->{'categoria_mandante.catmandante_id'};
        $category->description = $gameCategory->{'categoria_mandante.descripcion'};
        $category->tipo = $gameCategory->{'categoria_mandante.tipo'};
        return $category;
    }, $GamesCategories);


    //Inicializando referidos --Varios valores y parámetros sólo serán creados una vez se agregue el primer premio al programa
    try {
        $Clasificador = new Clasificador('', 'ACEPTAREFERIDO');
        $MandanteDetalle = new MandanteDetalle('', $partner, $Clasificador->getClasificadorId(), $country, 'A');
    } catch (Exception $e) {
        if ($e->getCode() != 34) throw $e;
        try {
            $refersProgramTransaction = new Transaction();
            $MandanteDetalleMySlqDAO = new MandanteDetalleMySqlDAO($refersProgramTransaction);

            //Definiendo condiciones de referente por defecto
            $defaultReferentConditions = ['CONDMINDEPOSITREFERENT', 'CONDVERIFIEDREFERENT'];
            foreach ($defaultReferentConditions as $defaultReferentCondition) {
                $Clasificador = new Clasificador('', $defaultReferentCondition);

                $MandanteDetalle = new MandanteDetalle();
                $MandanteDetalle->setMandante($partner);
                $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
                $MandanteDetalle->setValor('0');
                $MandanteDetalle->setUsucreaId(0);
                $MandanteDetalle->setUsumodifId(0);
                $MandanteDetalle->setPaisId($country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySlqDAO->insert($MandanteDetalle);
            }

            //Definiendo cuota mínima en sportsBook
            $Clasificador = new Clasificador('', 'MINSELPRICEREFERRED');
            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($partner);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor('0.0');
            $MandanteDetalle->setUsucreaId(0);
            $MandanteDetalle->setUsumodifId(0);
            $MandanteDetalle->setPaisId($country);
            $MandanteDetalle->setEstado('A');
            $MandanteDetalleMySlqDAO->insert($MandanteDetalle);

            //Definiendo estado del programa de referidos
            $Clasificador = new Clasificador('', 'ACEPTAREFERIDO');
            $MandanteDetalle = new MandanteDetalle();
            $MandanteDetalle->setMandante($partner);
            $MandanteDetalle->setTipo($Clasificador->getClasificadorId());
            $MandanteDetalle->setValor('0');
            $MandanteDetalle->setUsucreaId(0);
            $MandanteDetalle->setUsumodifId(0);
            $MandanteDetalle->setPaisId($country);
            $MandanteDetalle->setEstado('A');
            $MandanteDetalleMySlqDAO->insert($MandanteDetalle);

            //Guardando incialización del programa
            $refersProgramTransaction->commit();
        } catch (Exception $e) {
        }
    }



    /* Se define un arreglo de reglas para filtrar datos en una consulta. */
    $rules = [];


    array_push($rules, array("field" => "mandante_detalle.mandante", "data" => "$Mandante->mandante", "op" => "eq"));
    array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));

    array_push($rules, array("field" => "mandante_detalle.pais_id", "data" => "$country", "op" => "eq"));



    /* Crea un filtro JSON y obtiene detalles mediante una consulta específica. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $mandanteDetalles = $MandanteDetalle->getMandanteDetallesCustom(" mandante_detalle.*,clasificador.* ", "mandante_detalle.manddetalle_id", "asc", $SkeepRows, $MaxRows, $json2, true);

    $mandanteDetalles = json_decode($mandanteDetalles);


    foreach ($mandanteDetalles->data as $key => $value) {

        switch ($value->{'clasificador.abreviado'}) {
            case "MINDEPOSIT":
                /* Asignación del valor mínimo de depósito a la configuración del mandante. */

                $mandanteConfig["RequestMinAmount"] = $value->{'mandante_detalle.valor'};
                break;
            case "MAXDEPOSIT":
                /* Asignación del valor del depósito máximo en la configuración de mandante. */

                $mandanteConfig["RequestMaxAmount"] = $value->{'mandante_detalle.valor'};
                break;
            case "DAYSNOTIFYPASSEXPIRE":
                /* Asigna un valor a la configuración de aviso antes del vencimiento de contraseña. */

                $mandanteConfig["DaysNotifyBeforePasswordExpire"] = $value->{'mandante_detalle.valor'};
                break;
            case "DAYSEXPIREPASSWORD":
                /* asigna un valor a la expiración de contraseña del usuario. */

                $mandanteConfig["UserPasswordExpireDays"] = $value->{'mandante_detalle.valor'};
                break;
            case "BODAYSEXPIREPASSWORD":
                /* Asigna el valor de los días de expiración de contraseña a una configuración específica. */

                $mandanteConfig["BoUserPasswordExpireDays"] = $value->{'mandante_detalle.valor'};
                break;
            case "MINLENPASSWORD":
                /* Se asigna un valor mínimo de longitud para las contraseñas de usuario. */

                $mandanteConfig["UserPasswordMinLength"] = $value->{'mandante_detalle.valor'};
                break;
            case "DAYSEXPIRETEMPPASS":
                /* Asigna un valor a la configuración de expiración de contraseñas temporales. */

                $mandanteConfig["UserTempPasswordExpireDays"] = $value->{'mandante_detalle.valor'};
                break;
            case "WRONGATTEMPTSLOGIN":
                /* Asigna un valor a los intentos de inicio de sesión incorrectos en la configuración. */

                $mandanteConfig["UserWrongLoginAttempts"] = $value->{'mandante_detalle.valor'};
                break;
            case "MAXWITHDRAWACTIVEREQUEST":
                /* asigna un valor a la configuración de solicitudes activas máximas. */

                $mandanteConfig["MaxActiveRequests"] = $value->{'mandante_detalle.valor'};
                break;
            case "MAXWITHDRAWDAY":
                /* asigna un valor a la configuración de solicitudes máximas diarias. */

                $mandanteConfig["MaxRequestsPerDay"] = $value->{'mandante_detalle.valor'};
                break;
            case "MINWITHDRAWDAYKASNET":
                /* Asigna el valor mínimo de retiro para Kasnet en la configuración del mandante. */

                $mandanteConfig["MinAmountWithdrawKasnet"] = $value->{'mandante_detalle.valor'};
                break;
            case "MAXWITHDRAWDAYKASNET":
                /* Asigna un valor máximo de retiro a la configuración de Kasnet según la entrada. */

                $mandanteConfig["MaxAmountWithdrawKasnet"] = $value->{'mandante_detalle.valor'};
                break;
            case "MINWITHDRAW":
                /* Asigna un valor mínimo de retiro a la configuración del mandante. */

                $mandanteConfig["RequestMinAmountWithdraw"] = $value->{'mandante_detalle.valor'};
                break;
            case "MINWITHDRAWBETSHOP":
                /* Asigna un valor mínimo de retiro para BetShop en la configuración. */

                $mandanteConfig["RequestMinAmountWithdrawBetShop"] = $value->{'mandante_detalle.valor'};
                break;
            case "MINWITHDRAWACCBANK":
                /* Asigna un valor mínimo de retirada a una cuenta bancaria desde la configuración. */

                $mandanteConfig["RequestMinAmountWithdrawBankAccount"] = $value->{'mandante_detalle.valor'};
                break;


            /*se devuelve el valor configurado para el partner si requiere o no verificacion para premios lealtad*/
            case "LOYALTYVERIFICATION":
                $mandanteConfig["RequiresVerificationForLoyalty"] = $value->{'mandante_detalle.valor'};
                break;

            case "MAXWITHDRAW":
                /* Asigna un valor máximo de retiro a la configuración de mandante. */

                $mandanteConfig["RequestMaxAmountWithdraw"] = $value->{'mandante_detalle.valor'};
                break;
            case "MAXWITHDRAWBETSHOP":
                /* Asignación del valor máximo de retiro para apuestas en la configuración del mandante. */

                $mandanteConfig["RequestMaxAmountWithdrawBetShop"] = $value->{'mandante_detalle.valor'};
                break;
            /*Se retorna el estado de si hay comision sin transaccion activa para la marca pais*/
            case "COMMISIONSFREETRANSACTION":
                $mandanteConfig["CommissionFreeTransactions"] = $value->{"mandante_detalle.valor"};
                break;
            /*Se retorna el tiempo de gracia en caso  de si hay comision sin transaccion activa para la marca pais*/
            case "RESTRICTIONTIME":
                $mandanteConfig["RestrictionTime"] = $value->{"mandante_detalle.valor"};
                break;
            case "LIMSALDFORMSPLAFT":
                /* Asigna un valor a la configuración de límite en un formato específico. */

                $mandanteConfig['LimitBalanceFormatSplaft'] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXWITHDRAWDEPOSIT":
                /* Asigna el valor de "mandante_detalle.valor" a "TaxWithdrawBalanceDeposit". */

                $mandanteConfig["TaxWithdrawBalanceDeposit"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXBET":
                /* Se asigna un valor de impuesto a una configuración específica del mandante. */

                $mandanteConfig["BetTaxValue"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXDEPOSIT":
                /* Asigna el valor de depósito de impuestos desde un objeto a la configuración. */

                $mandanteConfig["DepositTaxValue"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXWITHDRAWAWARD":
                /* Asignación del valor de "mandante_detalle.valor" a la clave "TaxWithdrawBalanceAward". */

                $mandanteConfig["TaxWithdrawBalanceAward"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXWITHDRAWAWARDISR":
                /* Asigna un valor de configuración relacionado con la retención de impuestos. */

                $mandanteConfig["TaxWithdrawBalanceAward2"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXREGULATOR":
                /* Asigna un valor específico a la configuración de un regulador fiscal en un array. */

                $mandanteConfig["TaxRegulator"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXWITHDRAWDEPOSITFROM":
                /* Asigna un valor específico a la configuración relacionada con el retiro de impuestos. */

                $mandanteConfig["TaxWithdrawBalanceDepositFrom"] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXWITHDRAWAWARDFROM":
                /* Asignar valor de 'mandante_detalle.valor' a configuración de retiro de impuestos. */

                $mandanteConfig["TaxWithdrawBalanceAwardFrom"] = $value->{'mandante_detalle.valor'};
                break;
            case 'TAXBETSHOPPHYSICAL':

                /* Asignación de valores a configuraciones según el tipo de caso en PHP. */
                $mandanteConfig['TaxBetShopPhysical'] = $value->{'mandante_detalle.valor'};
            case "TAXREGULATORFROM":
                $mandanteConfig["TaxRegulatorFrom"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELONE":
                $mandanteConfig["PointsLevelOne"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELTWO":
                /* Asigna un valor a la configuración de puntos del nivel dos. */

                $mandanteConfig["PointsLevelTwo"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELTHREE":
                /* Asigna un valor a la clave "PointsLevelThree" en un array basado en condición. */

                $mandanteConfig["PointsLevelThree"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELFOUR":
                /* Asigna un valor específico a la configuración de puntos nivel cuatro. */

                $mandanteConfig["PointsLevelFour"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELFIVE":
                /* Asigna un valor a la configuración de puntos nivel cinco en un arreglo. */

                $mandanteConfig["PointsLevelFive"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELSIX":
                /* Asigna el valor de "mandante_detalle.valor" a "PointsLevelSix" en una configuración. */

                $mandanteConfig["PointsLevelSix"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELSEVEN":
                /* Asigna un valor específico a la configuración de puntos del nivel siete. */

                $mandanteConfig["PointsLevelSeven"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELEIGHT":
                /* Asignación del valor de "mandante_detalle.valor" a "PointsLevelEight" en configuración. */

                $mandanteConfig["PointsLevelEight"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELNIVE":
                /* asigna un valor especificado a la configuración de "PointsLevelNine". */

                $mandanteConfig["PointsLevelNine"] = $value->{'mandante_detalle.valor'};
                break;
            case "POINTSLEVELTEN":
                /* Asigna un valor a PointsLevelTen desde mandante_detalle.valor. */

                $mandanteConfig["PointsLevelTen"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYDEPOSIT":
                /* Asigna el valor del depósito de lealtad a la configuración del mandante. */

                $mandanteConfig["LoyaltyDeposit"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSSIMPLE":
                /* Asignación de valor a la configuración de "LoyaltyBettingSportsSimple" según el caso. */

                $mandanteConfig["LoyaltyBettingSportsSimple"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDTWO":
                /* Asignación del valor de "mandante_detalle.valor" a la configuración de "LoyaltyBettingSportsCombinedTwo". */

                $mandanteConfig["LoyaltyBettingSportsCombinedTwo"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDTHREE":
                /* Asignación de valor a la configuración de "LoyaltyBettingSportsCombinedThree" en el array `$mandanteConfig`. */

                $mandanteConfig["LoyaltyBettingSportsCombinedThree"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDFOUR":
                /* Se asigna un valor específico a la configuración de Loyalty Betting Sports Combined Four. */

                $mandanteConfig["LoyaltyBettingSportsCombinedFour"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDFIVE":
                /* Configura el valor de "LoyaltyBettingSportsCombinedFive" desde un objeto recibido. */

                $mandanteConfig["LoyaltyBettingSportsCombinedFive"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDSIX":
                /* asigna un valor a "LoyaltyBettingSportsCombinedSix" en función de una condición. */

                $mandanteConfig["LoyaltyBettingSportsCombinedSix"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDSEVEN":
                /* Asigna un valor específico a la configuración de "LoyaltyBettingSportsCombinedSeven". */

                $mandanteConfig["LoyaltyBettingSportsCombinedSeven"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDEIGHT":
                /* Asigna un valor específico a la configuración de "LoyaltyBettingSportsCombinedEight". */

                $mandanteConfig["LoyaltyBettingSportsCombinedEight"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDNINE":
                /* Asigna un valor a la configuración de "LoyaltyBettingSportsCombinedNine" en un caso específico. */

                $mandanteConfig["LoyaltyBettingSportsCombinedNine"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETTINGSPORTSCOMBINEDTEN":
                /* Asigna un valor a la configuración de "LoyaltyBettingSportsCombinedTen" basado en el input. */

                $mandanteConfig["LoyaltyBettingSportsCombinedTen"] = $value->{'mandante_detalle.valor'};
                break;
            case "SPORTSAWARDLOYALTY":
                /* Asigna un valor de detalle a la configuración de lealtad de premios deportivos. */

                $mandanteConfig["SportsAwardLoyalty"] = $value->{'mandante_detalle.valor'};
                break;

            case "LOYALTYBETCASINO":
                /* Asigna un valor específico a la configuración de LoyaltyBetCasino en un arreglo. */

                $mandanteConfig["LoyaltyBetCasino"] = $value->{'mandante_detalle.valor'};
                break;
            case "LOYALTYBETCASVIVO":
                /* Configura el valor de "LoyaltyBetCasinoLive" en función del caso específico. */

                $mandanteConfig["LoyaltyBetCasinoLive"] = $value->{"mandante_detalle.valor"};
                break;
            case "LOYALTYBETVIRTUALES":
                /* Asigna valor de configuración a "LoyaltyBetVirtuals" en función de un caso específico. */

                $mandanteConfig["LoyaltyBetVirtuals"] = $value->{"mandante_detalle.valor"};
                break;

            case "CASINOAWARDLOYALTY":
                /* Asigna un valor específico a la configuración de lealtad de Casino Award. */

                $mandanteConfig["CasinoAwardLoyalty"] = $value->{'mandante_detalle.valor'};
                break;


            case "LOYALTYWITHDRAWAL":
                /* Configura el valor de retiro de lealtad en función del detalle del mandante. */

                $mandanteConfig["LoyaltyWithdrawal"] = $value->{'mandante_detalle.valor'};
                break;

            case "LOYALTYBASEVALUE":
                /* Asigna el valor de "mandante_detalle.valor" a "LoyaltyBaseValue" en configuración. */

                $mandanteConfig["LoyaltyBaseValue"] = $value->{'mandante_detalle.valor'};
                break;

                /*Se obtiene el valor del maximo de wallets permitidas para el partner pais*/

            case "WALT":
                $mandanteConfig["CombinationOfWallets"] = $value->{'mandante_detalle.valor'};
                break;

            case "LOYALTYEXPIRATIONDATE":
                /* Asigna el valor de la fecha de expiración de lealtad a una configuración específica. */

                $mandanteConfig["LoyaltyExpirationDate"] = $value->{'mandante_detalle.valor'};
                break;
            case "MAXACCOUNTSBANK":
                /* Se asigna un valor a la configuración de cuentas bancarias de un mandante. */


                $mandanteConfig["MaxAccountsBank"] = $value->{'mandante_detalle.valor'};
                break;


            case "REQREGACT":
                /* Asigna valor de configuración para activar registro de usuario según solicitud. */


                $mandanteConfig["ActivateRegisterUser"] = $value->{'mandante_detalle.valor'};
                break;


            case "MINPERCTDEP":
                /* Configura el porcentaje mínimo apostado en función del valor especificado. */


                $mandanteConfig["MinPercentageWagered"] = $value->{'mandante_detalle.valor'};
                break;


            case "DAYALERTCHANGEPASS":
                /* asigna un valor específico a la configuración de alertas de cambio de contraseña. */


                $mandanteConfig["DaysAlertChangePassword"] = $value->{'mandante_detalle.valor'};
                break;


            case "LIQUIDAFF":
                /* Asignar "A" o "I" a Liquidations según valor mandante_detalle. */


                $mandanteConfig["Liquidations"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;


            case "REGISTERACTIVATION":
                /* Configura el estado de actividad automática basado en un valor recibido. */


                $mandanteConfig["AutomaticallyActive"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;


            case "TYPEREGISTER":
                /* Asigna "C" o "L" a TypeRegister según el valor de mandante_detalle.valor. */


                $mandanteConfig["TypeRegister"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "C" : "L";
                break;

            case 'RISKSTATUS':
                /* Evalúa el estado de riesgo y activa "A" o "I" según el valor. */

                $mandanteConfig['IsActivateRiskStatus'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'MAXDEPOSITO':

            case 'LIMITEDEPOSITODIARIODEFT':
                /* Asigna un valor de configuración para el límite de depósito diario. */

                $mandanteConfig['LimitDepositDayDefault'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEDEPOSITOSEMANADEFT':
                /* Asigna el valor de límite de depósito semanal desde el objeto `$value`. */

                $mandanteConfig['LimitDepositWeekDefault'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEDEPOSITOMENSUALDEFT':
                /* Asigna un valor a la configuración del límite de depósito mensual para el mandante. */

                $mandanteConfig['LimitDepositMonthDefault'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEDEPOSITODIARIOGLOBAL':
                /* Asigna un límite de depósito diario global desde la configuración del mandante. */

                $mandanteConfig['LimitDepositDayGlobal'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEDEPOSITOSEMANAGLOBAL':
                /* Asigna un valor a la configuración de límite semanal de depósitos global. */

                $mandanteConfig['LimitDepositWeekGlobal'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEDEPOSITOMENSUALGLOBAL':
                /* asigna un valor a la configuración de límite de depósito mensual global. */

                $mandanteConfig['LimitDepositMonthGlobal'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEANULACIONDEPOSITOSPORPERIODO':
                /* Asignación de valor a configuración de límites de cancelación de depósitos por periodo. */

                $mandanteConfig['LimitCancelDepositExecutions'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITEHORASPARAANULARDEPOSITO':
                /* Asigna el valor de límite de horas para cancelar depósito en una configuración. */

                $mandanteConfig['LimitHoursCancelDeposit'] = $value->{'mandante_detalle.valor'};
                break;
            case "TAXBETPAYPRIZE":
                /* Asignación del valor de "mandante_detalle.valor" a TaxBetPayPrize en configuración. */


                $mandanteConfig["TaxBetPayPrize"] = $value->{'mandante_detalle.valor'};
                break;
            case "PRIZEPAYMENTTAX":
                /* Asigna un valor a la configuración de impuestos en caso de "PRIZEPAYMENTTAX". */

                $mandanteConfig["TaxPayments"] = $value->{'mandante_detalle.valor'};
                break;

            case 'TAXPRIZEBETSHOP':
                /* Asigna el valor del detalle de mandante al arreglo de configuración bajo 'TaxPrizeBetShop'. */

                $mandanteConfig['TaxPrizeBetShop'] = $value->{'mandante_detalle.valor'};
                break;
            case 'APPBANKACC':
                /* asigna un valor específico a la configuración de cuentas bancarias aprobadas. */

                $mandanteConfig['ApproveBankAccounts'] = $value->{'mandante_detalle.valor'};
                break;
            case 'WPPWBALANCEW':
                /* Asigna el valor de 'mandante_detalle.valor' a 'PenaltyWithdrawalBalanceWithdrawal'. */

                $mandanteConfig['PenaltyWithdrawalBalanceWithdrawal'] = $value->{'mandante_detalle.valor'};
                break;
            case 'APPCHANPERSONALINF';

                /* Asigna el valor de "mandante_detalle.valor" a "ApproveChangesInformation". */
                $mandanteConfig['ApproveChangesInformation'] = $value->{'mandante_detalle.valor'};
                break;

            case "ACTREGFORDEPOSIT":
                /* Establece el estado de registro activo según un valor especificado. */


                $mandanteConfig["ActiveRegistrationDeposit"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'PPWWBALANCERECHARGES':
                /* Asigna el valor de 'mandante_detalle.valor' a la configuración de penalización. */

                $mandanteConfig['PenaltyWithdrawalBalanceRecharge'] = $value->{'mandante_detalle.valor'};
                break;
            case 'MINBALANCEWPW':
                /* Asigna un valor mínimo para retiros en caso de penalización. */

                $mandanteConfig['MinimumWithdrawalBalanceWithdrawalsPenalty'] = $value->{'mandante_detalle.valor'};
                break;
            case 'SESSIONINACTIVITYMIN';

                /* Asigna el valor de 'mandante_detalle.valor' a la duración de inactividad de sesión. */
                $mandanteConfig['SessionInativityLength'] = $value->{'mandante_detalle.valor'};
                break;
            case "ACCVERIFFORDEPOSIT":
                /* Asigna "A" o "I" según la verificación de depósito del mandante. */


                $mandanteConfig["AccountVerificationDeposit"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;

            case 'TYPECOMMISIONPOINTSALE':
                /* Asignación de valor a la configuración de comisión de punto de venta en Betshop. */

                $mandanteConfig['TypeCommissionBetshop'] = $value->{'mandante_detalle.valor'};
                break;
            case 'SESSIONDURATIONMIN':
                /* Asigna el valor de duración de sesión a la configuración del mandante. */

                $mandanteConfig['SessionLength'] = $value->{'mandante_detalle.valor'};
                break;
            case 'TYPESETTLECOMMSSIONS':
                /* Asigna un valor específico a la configuración de comisiones del mandato. */

                $mandanteConfig['TypeCommissionSettlements'] = $value->{'mandate_detalle.valor'};
                break;
            case "ACTREGFORWITHDRAW":
                /* Configura el estado de "ActiveRegistrationWithdraw" según el valor proporcionado. */


                $mandanteConfig["ActiveRegistrationWithdraw"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'MINWITHRECHARGESPENALIZE':
                /* asigna un valor a una configuración específica en función de una condición. */

                $mandanteConfig['MinimumWithdrawalRefillsPenalty'] = $value->{'mandante_detalle.valor'};
                break;
            case "ACCVERIFFORWITHDRAW":
                /* Configura la verificación de cuenta para retiros según el valor proporcionado. */


                $mandanteConfig["AccountVerificationWithdraw"] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'BALANCEBONDS':
                /* Asigna el valor de 'mandnate_detalle.valor' a 'BonusesBalance' en 'BALANCEBONDS'. */

                $mandanteConfig['BonusesBalance'] = $value->{'mandnate_detalle.valor'};
                break;
            case 'WITHDRAWALBALANCE':
                /* Asigna un valor a WithdrawalsBalance desde mandante_detalle.valor en el caso de retiro. */

                $mandanteConfig['WithdrawalsBalance'] = $value->{'mandante_detalle.valor'};
                break;
            case 'RECHARGEABLEBALANCE':
                /* Asigna el valor de 'mandante_detalle.valor' a 'RechargeBalance' del array 'mandanteConfig'. */

                $mandanteConfig['RechargeBalance'] = $value->{'mandante_detalle.valor'};
                break;
            case 'FREECASINOBALANCE':
                /* asigna un valor específico a 'FreecasinoBalance' en una configuración. */

                $mandanteConfig['FreecasinoBalance'] = $value->{'mandante_detalle.valor'};
                break;
            case 'TOTALCONTINGENCE':
                /* Asigna 'A' o 'I' a 'Contingency' según el valor del detalle. */

                $mandanteConfig['Contingency'] = $value->{'mandante_detalle.valor'} == 1 ? 'A' : 'I';
                break;
            case 'PROVSMS':
                /* asigna un valor a la configuración del proveedor de SMS. */

                $mandanteConfig['ProviderSMS'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PROVEMAIL':
                /* Asigna valor de 'mandante_detalle.valor' a 'ProviderEmail' en $mandanteConfig. */

                $mandanteConfig['ProviderEmail'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PROVCRM':
                /* Asigna un valor de configuración basado en el caso 'PROVCRM'. */

                $mandanteConfig['ProviderCRM'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PROVVERIFICA':
                /* Asigna un valor a la configuración de verificación de proveedor en un arreglo. */

                $mandanteConfig['ProviderVerification'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PROVSIGNATURE':
                /* Asigna el valor de 'mandante_detalle.valor' a 'ProviderSignature' en la configuración. */

                $mandanteConfig['ProviderSignature'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PROVCPF':
                /* asigna un valor a ‘ProviderCPF’ según un caso específico. */

                $mandanteConfig['ProviderCPF'] = $value->{'mandante_detalle.valor'};
                break;
            case 'FREEBETBALANCE':
                /* Asignación del valor de 'FreebetBalance' desde 'mandante_detalle.valor' en la configuración. */

                $mandanteConfig['FreebetBalance'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVADEPO':
                /* Asigna el valor del depósito al porcentaje en la configuración del mandante. */

                $mandanteConfig['PercentDepositValue'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVARETR':
                /* Asigna un valor de retiro porcentual a la configuración del mandante. */

                $mandanteConfig['PercentRetirementValue'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVAAPUESDEPOR':
                /* Asigna un valor porcentual de apuestas deportivas a una configuración específica. */

                $mandanteConfig['PercentValueSportsBets'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVAAPUESNODEPOR':
                /* Asigna un valor específico a la configuración cuando el caso coincide. */

                $mandanteConfig['PercentValueNonSportBets'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVAPREMDEPOR':
                /* Asigna un valor a la configuración de premios deportivos según datos específicos. */

                $mandanteConfig['PercentValueSportsAwards'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVAPREMNODEPOR':
                /* Asigna un valor a 'PercentValueNonSportsAwards' según el nodo 'mandante_detalle.valor'. */

                $mandanteConfig['PercentValueNonSportsAwards'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVABONDEPOR':
                /* Asignando un valor a la configuración de bonos deportivos en función de la entrada. */

                $mandanteConfig['PercentValueSportsBonds'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVABONNODEPOR':
                /* Asigna un valor específico a la configuración de porcentaje en un caso particular. */

                $mandanteConfig['PercentValueNonSportsBounds'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PORCENVATICKET':
                /* Asigna el valor del ticket a la configuración del mandante para el caso específico. */

                $mandanteConfig['PercentValueTickets'] = $value->{'mandante_detalle.valor'};
                break;
            case 'VERIFICANUMDOC':
                /* asigna "A" o "I" a VerificaFiltro según un valor comprobado. */

                $mandanteConfig['VerificaFiltro'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'VERIFICANUMDOCPV':
                /* Configura 'VerificaFiltroPV' según el valor de 'mandante_detalle.valor'. */

                $mandanteConfig['VerificaFiltroPV'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'LEVELNAME':
                /* Asigna el valor de 'mandante_detalle.valor' a 'LevelName' en la configuración. */

                $mandanteConfig['LevelName'] = $value->{'mandante_detalle.valor'};
                break;
            case 'SETMINS':
                /* asigna un valor a una configuración específica de 'SetMins'. */

                $mandanteConfig['SetMins'] = $value->{'mandante_detalle.valor'};
                break;
            case 'NUMRECHAZOS':
                /* Asigna el valor de 'mandante_detalle.valor' a 'NumRechazos' en configuración. */

                $mandanteConfig['NumRechazos'] = $value->{'mandante_detalle.valor'};
                break;
            case 'NUMRECHAZOSDOCUMENT':
                /* asigna un valor a la configuración basado en una condición específica. */

                $mandanteConfig['NumRechazosDocument'] = $value->{'mandante_detalle.valor'};
                break;
            case 'ISACTIVESMS':
                /* asigna "A" o "I" según el valor para IsActiveSms. */

                $mandanteConfig['IsActiveSms'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'ISACTIVEPOPUP':
                /* Configura el estado del popup como "A" si activo, "I" si inactivo. */

                $mandanteConfig['IsActivePopUp'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'ISACTIVEEMAIL':
                /* Establece 'IsActiveEmail' según el valor de 'mandante_detalle.valor'. */

                $mandanteConfig['IsActiveEmail'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'ISACTIVEINBOX':
                /* Configura el estado de la bandeja de entrada basado en un valor entero. */

                $mandanteConfig['IsActiveInbox'] = (intval($value->{'mandante_detalle.valor'}) == 1) ? "A" : "I";
                break;
            case 'EXCLUDEDCASINOCATEGORYREFERS':
                /* asigna categorías excluidas a partir de un valor separado por comas. */

                $mandanteConfig['ExcludedCategoriesMinBetReferred'] = explode(',', $value->{'mandante_detalle.valor'});
                break;
            case 'ACEPTAREFERIDO':
                /* asigna un valor a la configuración de aceptación de referido. */

                $mandanteConfig['AcceptReferred'] = $value->{'mandante_detalle.valor'};
                break;
            case 'URLLANDING':
                /* Asigna una URL de aterrizaje a la configuración del mandante según su valor. */

                $mandanteConfig['UrlLanding'] = $value->{'mandante_detalle.valor'};
                break; // verticales desde acá
            case 'SPORTBETSHOP':
                /* asigna un valor específico a una configuración según el caso 'SPORTBETSHOP'. */

                $mandanteConfig['SportsBetsPv'] = $value->{'mandante_detalle.valor'};
                break;
            case 'WITHDRAWALBETSHOP':
                /* Asignación de valor de retiro para la configuración de la tienda de apuestas. */

                $mandanteConfig['PayRetirementNotesPv'] = $value->{'mandante_detalle.valor'};
                break;
            case 'DEPOSITBETSHOP':
                /* Configura el valor de depósito para la tienda según el detalle del mandante. */

                $mandanteConfig['PayDepositPv'] = $value->{'mandante_detalle.valor'};
                break;
            case 'VIRTUALBETSHOP':
                /* Asigna un valor a la configuración de 'VirtualBetsPv' en función de 'mandante_detalle.valor'. */

                $mandanteConfig['VirtualBetsPv'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CASHIERSPORT':
                /* Asigna un valor específico de configuración a 'SportsBetsAtm' para 'CASHIERSPORT'. */

                $mandanteConfig['SportsBetsAtm'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CASHIERWITHDRAWLL':
                /* Asigna valor de 'mandante_detalle.valor' a configuración de retiro en efectivo. */

                $mandanteConfig['PayRetirementNotesAtm'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CASHIERDEPOSIT':
                /* Asigna el valor de depósito en efectivo a la configuración del mandante. */

                $mandanteConfig['PayDepositAtm'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CASHIERPAYPRIZE':
                /* Asignación de valor a configuración de premiación en el cajero según caso específico. */

                $mandanteConfig['PayAwardAtm'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CASHIERVIRTUALS':
                /* Asigna un valor de configuración para apuestas virtuales en efectivo. */

                $mandanteConfig['VirtualBetsAtm'] = $value->{'mandante_detalle.valor'};
                break;
            case 'SPORTUSUONLINE':
                /* Asignación de valor a configuración de apuestas deportivas en función de un caso específico. */

                $mandanteConfig['SportsBetsUserOnline'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CASINOUSUONLINE':
                /* Asignación de valor de configuración para usuario online en el casino. */

                $mandanteConfig['CasinoBetsUserOnline'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIVECASINOUSUONLINE':
                /* asigna un valor específico a la configuración de usuario de Live Casino. */

                $mandanteConfig['LiveCasinoUserOnline'] = $value->{'mandante_detalle.valor'};
                break;
            case 'VIRTUALUSUONLINE':
                /* Asigna un valor a la configuración de usuario virtual basado en una condición. */

                $mandanteConfig['VirtualBetsUserOnline'] = $value->{"mandante_detalle.valor"};
                break;
            case 'POKERUSUONLINE':
                /* asigna un valor específico a una configuración relacionada con PokerUserOnline. */

                $mandanteConfig['PokerUserOnline'] = $value->{"mandante_detalle.valor"};
                break;
            case 'WITHDRAWALUSUONLINE':
                /* Asignación del valor de retiro en la configuración del usuario en línea. */

                $mandanteConfig['RetirementNotesUserOnline'] = $value->{"mandante_detalle.valor"};
                break;
            case 'DEPOSITUSUONLINE':
                /* Asigna el valor de configuración para "DepositUserOnline" desde el objeto $value. */

                $mandanteConfig['DepositUserOnline'] = $value->{"mandante_detalle.valor"};
                break;
            case 'HIPICUSUONLINE':
                /* Asigna un valor a la configuración de HipicaUserOnline basado en el caso correspondiente. */

                $mandanteConfig['HipicaUserOnline'] = $value->{"mandante_detalle.valor"};
                break;
            case 'PAYPRIZEBETSHOP':
                /* Asignación de valor de premio a la configuración del mandante en un caso específico. */

                $mandanteConfig['PayAwardsPv'] = $value->{"mandante_detalle.valor"};
                break;

            case "DEFAULTAMOUNTPAYMENTGATEWAYS":
                /* Asigna un valor a la configuración de depósitos predeterminados según un caso específico. */

                $mandanteConfig['DefaultAmountDeposits'] = $value->{"mandante_detalle.valor"};
                break;


//hasta acá la ultima vertical
            case 'ACTIVATESENDEMAIL':
                /* asigna un valor de configuración para correos tras intentos fallidos de inicio de sesión. */

                $mandanteConfig['EmailWrongLoginAttempts'] = $value->{"mandante_detalle.valor"};
                break;
            case 'CONDMINDEPOSITREFERENT':
                /* Define una condición de valor mínimo de depósito para referentes en una configuración. */

                $referentCondition = (object)[];
                $referentCondition->Label = 'Valor mínimo de depósito para ser referente';
                $referentCondition->Condition = 'CONDMINDEPOSITREFERENT';
                $referentCondition->Value = (int)$value->{'mandante_detalle.valor'};
                $mandanteConfig['ReferentConditions'][1] = $referentCondition;
                ksort($mandanteConfig['ReferentConditions']);
                break;
            case 'CONDVERIFIEDREFERENT':
                /* Crea y configura una condición de verificación para un referente. */

                $referentCondition = (object)[];
                $referentCondition->Label = '¿El referente debe estar verificado?';
                $referentCondition->Type = 'switch';
                $referentCondition->Condition = 'CONDVERIFIEDREFERENT';
                $referentCondition->Value = (int)$value->{'mandante_detalle.valor'};
                $mandanteConfig['ReferentConditions'][0] = $referentCondition;
                ksort($mandanteConfig['ReferentConditions']);
                break;
            case 'MINSELPRICEREFERRED':
                /* Se asigna un valor a la configuración de 'ReferredMinSelValue' del mandante. */

                $mandanteConfig['ReferredMinSelValue'] = $value->{'mandante_detalle.valor'};
                break;
            case 'PHONEVERIFICATION':
                switch ($value->{'mandante_detalle.valor'}) {
                    case 0:
                        /* Configura la verificación telefónica de depósitos según el estado del mandante. */

// DepositPhoneVerification
                        $mandanteConfig['DepositPhoneVerification'] = $value->{'mandante_detalle.estado'};
                        break;
                    case 1:
                        /* Configura la verificación telefónica para apuestas deportivas basado en el estado del mandante. */

// SportBetsPhoneVerification
                        $mandanteConfig['SportBetsPhoneVerification'] = $value->{'mandante_detalle.estado'};
                        break;
                    case 2:
                        /* Asigna el estado de verificación telefónica de Casino Bets a la configuración del mandante. */

// CasinoBetsPhoneVerification
                        $mandanteConfig['CasinoBetsPhoneVerification'] = $value->{'mandante_detalle.estado'};
                        break;
                    case 3:
                        /* Configura la verificación de teléfono para retiros según el estado de mandante. */

// WithdrawalPhoneVerification
                        $mandanteConfig['WithdrawalPhoneVerification'] = $value->{'mandante_detalle.estado'};
                        break;
                    case 4:
                        /* Asignación de configuración para verificación telefónica en el casino en vivo. */

// LiveCasinoPhoneVerification
                        $mandanteConfig['LiveCasinoPhoneVerification'] = $value->{'mandante_detalle.estado'};
                        break;
                    case 5:
                        /* Configura la verificación telefónica virtual según el estado del detalle del mandante. */

//VirtualPhoneVerification
                        $mandanteConfig['VirtualPhoneVerification'] = $value->{'mandante_detalle.estado'};
                        break;
                }
                break;
            case 'LIMITCOMISIONCONCESIONARIOSBGGRPVIP':
                /* Asigna configuraciones para concesionarios en un sistema de apuestas deportivas. */

                $mandanteConfig['IsSportbookGgrPvIpConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvIpConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIODEPOSITPV':
                /* Configura propiedades relacionadas con concesionarios de depósito en un array. */

                $mandanteConfig['IsDepositPvConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositPvConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIONOTESPV':
                /* Configura opciones de retiro para concesionarios en función de valores específicos. */

                $mandanteConfig['IsWithdrawalsNotesPvConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['WithdrawalsNotesPvConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIOSBGGRAFILIADOS':
                /* Configura parámetros para concesionarios en un sistema de apuestas deportivas. */

                $mandanteConfig['IsSportbookGgrAfConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrAfConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIOSBNGRAFILIADOS':
                /* Asigna valores de configuración dependiendo del estado y valor de concesionarios. */

                $mandanteConfig['IsSportbookNgrAfConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookNgrAfConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIOSBGGRPV':
                /* asigna valores de configuración para concesionarios en un sistema. */

                $mandanteConfig['IsSportbookGgrPvConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIOAPSPV':
                /* Asigna valores a configuraciones de concesionario según el estado y valor proporcionados. */

                $mandanteConfig['IsBetSportPvConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['BetSportPvConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIOCASINONGRAFILIADOS':
                /* asigna valores a la configuración de un concesionario de casino. */

                $mandanteConfig['IsCasinoNgrAfConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['CasinoNgrAfConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIODEPOSITCT':
                /* Configura comisiones de concesionarios en transacciones de depósito según valores especificados. */

                $mandanteConfig['IsDepositCommissionTransactionConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositCommissionTransactionConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONCONCESIONARIODEPOSITAFILIADOS':
                /* Asignación de configuración de comisiones para concesionarios de depósitos de afiliados. */

                $mandanteConfig['IsDepositAfConcessionaire'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositAfConcessionaire'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIOSBGGRPVIP':
                /* Configura parámetros para subdealers de gestión GGR en apuestas deportivas. */

                $mandanteConfig['IsSportbookGgrPvIpSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvIpSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIODEPOSITPV':
                /* Configura valores de un mandante para depósitos en concesionarios según estado y valor. */

                $mandanteConfig['IsDepositPvSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositPvSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIONOTESPV':
                /* Asignación de configuración de mandante para notas de retiro de subdistribuidores. */

                $mandanteConfig['IsWithdrawalsNotesPvSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['WithdrawalsNotesPvSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIOSBGGRAFILIADOS':
                /* Asigna valores de configuración para el subdealer en un sistema de apuestas deportivas. */

                $mandanteConfig['IsSportbookGgrAfSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrAfSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIOSBNGRAFILIADOS':
                /* Asigna valores de configuración a un mandante según el estado y valor del detalle. */

                $mandanteConfig['IsSportbookNgrAfSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookNgrAfSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIOSBGGRPV':
                /* Configura valores de estado y valor para un subdealero de sportsbook específico. */

                $mandanteConfig['IsSportbookGgrPvSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIOAPSPV':
                /* Configuración de mandante para BetSport en un subdealer según estado y valor. */

                $mandanteConfig['IsBetSportPvSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['BetSportPvSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIOCASINONGRAFILIADOS':
                /* Configura propiedades de un mandante según estado y valor de un subdealer relacionado. */

                $mandanteConfig['IsCasinoNgrAfSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['CasinoNgrAfSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIODEPOSITCT':
                /* Asigna valores de configuración de comisiones para subconcesionarios en una transacción. */

                $mandanteConfig['IsDepositCommissionTransactionSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositCommissionTransactionSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONSUBCONCESIONARIODEPOSITAFILIADOS':
                /* Establece configuraciones para depósitos de subdealers basados en datos recibidos. */

                $mandanteConfig['IsDepositAfSubdealer'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositAfSubdealer'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVSBGGRPVIP':
                /* Asigna configuraciones de comisiones según el estado y valor de mandante. */

                $mandanteConfig['IsSportbookGgrPvIpPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvIpPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVDEPOSITPV':
                /* Asigna valores de configuración para un punto de venta de depósito. */

                $mandanteConfig['IsDepositPvPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositPvPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVNOTESPV':
                /* Asigna valores a una configuración según el estado y valor de un detalle. */

                $mandanteConfig['IsWithdrawalsNotesPvPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['WithdrawalsNotesPvPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVSBGGRAFILIADOS':
                /* Configura opciones de comisiones para ventas en puntos de venta de sportsbook. */

                $mandanteConfig['IsSportbookGgrAfPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrAfPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVSBNGRAFILIADOS':
                /* Asigna valores de configuración de mandante según el caso específico proporcionado. */

                $mandanteConfig['IsSportbookNgrAfPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookNgrAfPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVSBGGRPV':
                /* asigna configuraciones de apuestas deportivas a variables según condiciones específicas. */

                $mandanteConfig['IsSportbookGgrPvPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVAPSPV':
                /* Asigna valores de configuración según el estado y valor de 'mandante_detalle'. */

                $mandanteConfig['IsBetSportPvPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['BetSportPvPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVCASINONGRAFILIADOS':
                /* Configura valores de 'mandante' para asignaciones en un sistema de casino. */

                $mandanteConfig['IsCasinoNgrAfPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['CasinoNgrAfPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVDEPOSITCT':
                /* Configura comisiones de depósito para transacciones de punto de venta. */

                $mandanteConfig['IsDepositCommissionTransactionPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositCommissionTransactionPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONPVDEPOSITAFILIADOS':
                /* asigna valores a la configuración según un caso específico. */

                $mandanteConfig['IsDepositAfPointSale'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositAfPointSale'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORSBGGRPVIP':
                /* Configura parámetros de afiliación para un sportsbook específico en la aplicación. */

                $mandanteConfig['IsSportbookGgrPvIpAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvIpAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORDEPOSITPV':
                /* Asigna configuraciones de mandante para afiliados en depósitos de PV según condiciones. */

                $mandanteConfig['IsDepositPvAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositPvAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORNOTESPV':
                /* Asignación de configuración para retiros de notas de afiliados según el estado y valor. */

                $mandanteConfig['IsWithdrawalsNotesPvAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['WithdrawalsNotesPvAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORSBGGRAFILIADOS':
                /* Configura valores relacionados con comisiones para afiliados en una plataforma de apuestas. */

                $mandanteConfig['IsSportbookGgrAfAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrAfAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORSBNGRAFILIADOS':
                /* Configura parámetros de una comisión para afiliados en un sistema de apuestas. */

                $mandanteConfig['IsSportbookNgrAfAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookNgrAfAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORSBGGRPV':
                /* Configura parámetros para afiliados en el sistema sportsbook según su estado y valor. */

                $mandanteConfig['IsSportbookGgrPvAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['SportbookGgrPvAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORAPSPV':
                /* Asigna configuraciones de afiliador en función del estado y valor del mandante. */

                $mandanteConfig['IsBetSportPvAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['BetSportPvAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORCASINONGRAFILIADOS':
                /* Configura parámetros relacionados con afiliados de casino en función de un valor específico. */

                $mandanteConfig['IsCasinoNgrAfAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['CasinoNgrAfAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORDEPOSITCT':
                /* Asigna valores de comisión de afiliador a una configuración específica. */

                $mandanteConfig['IsDepositCommissionTransactionAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositCommissionTransactionAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITCOMISIONAFILIADORDEPOSITAFILIADOS':
                /* Asigna valores de comisión a la configuración de un mandante en función de un estado. */

                $mandanteConfig['IsDepositAfAffiliate'] = $value->{'mandante_detalle.estado'};
                $mandanteConfig['DepositAfAffiliate'] = $value->{'mandante_detalle.valor'};
                break;
            case 'ACTIVEANONYMOUSBETS':
                /* asigna un valor a la configuración de apuestas anónimas. */

                $mandanteConfig['IsActiveAnonymousBets'] = $value->{'mandante_detalle.estado'};
                break;
            case 'DOCUAPUANONIMA':
                /* asigna un valor a la configuración de apuestas anónimas para parametrización con documento. */

                $mandanteConfig['IsActiveAnonymousDocument'] = $value->{'mandante_detalle.valor'};
                break;
            case 'CELUAPUANONIMA':
                /* asigna un valor a la configuración de apuestas anónimas para parametrización con celular. */

                $mandanteConfig['IsActiveAnonymousPhone'] = $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITDAYSCONDSFULFILLMENTREFERRED':
                /* Asigna un valor a 'AwardLimitFulfillmentDayReferred' dependiendo de la condición de $value. */

                $mandanteConfig['AwardLimitFulfillmentDayReferred'] = $value->{'mandante_detalle.valor'} == -1 ? '0' : $value->{'mandante_detalle.valor'};
                break;
            case 'LIMITDAYSAWARDCHOICINGREFERENT':
                /* Asigna un límite de redención basado en un valor específico del mandante. */

                $mandanteConfig['AwardLimitRedemptionDayReferent'] = $value->{'mandante_detalle.valor'} == -1 ? '0' : $value->{'mandante_detalle.valor'};
                break;
            case 'CODEMINCETUR':
                /* Asigna un valor a la configuración de 'CodeMincetur' desde un objeto. */

                $mandanteConfig['CodeMincetur'] = $value->{'mandante_detalle.valor'};
                break;
            case 'ACTIVATEWITHDRAWALNOTES':
                /* asigna un valor específico a la configuración de notas de retiro. */

                $mandanteConfig['ActivateWithdrawalNotes'] = $value->{'mandante_detalle.estado'};
                break;
            case 'MAXAMOUNTUSERWITHDRAWALS':
                /* Asigna un valor máximo a los retiros diarios de un cliente específico. */

                $mandanteConfig['MaximumAmountDailyWithdrawalsClient'] = $value->{'mandante_detalle.valor'};
                break;
            case 'FIRSTDEPOSITWITHDRAW':
                /* asigna un valor específico de una configuración de depósito. */

                $mandanteConfig['FirstDeposit'] = $value->{'mandante_detalle.valor'};
                break;
            case 'VERIFMAILWITHDRAW':
                /* Se asigna un valor a la configuración de verificación de correo electrónico. */

                $mandanteConfig['VerifiedMail'] = $value->{'mandante_detalle.valor'};
                break;
            case 'VERIFPHONEWITHDRAW':
                /* Asignando el valor de teléfono verificado a la configuración del mandante en caso específico. */

                $mandanteConfig['VerifiedPhone'] = $value->{'mandante_detalle.valor'};
                break;
            case 'OTPCODESALESPOINT':
                /* Configura la activación de OTP para puntos de venta según el valor recibido. */

                $mandanteConfig['IsActivateOtpNotesPuntoDeVenta'] = $value->{'mandante_detalle.valor'};
                break;
            case 'OTPCODEBANKACCOUNTSMS':
                /* Asigna un valor de configuración para OTP en SMS según el caso específico. */

                $mandanteConfig['IsActivateOtpNotesSmsCB'] = $value->{'mandante_detalle.valor'};
                break;
            case 'OTPCODEBANKACCOUNTEMAIL':
                /* Asigna un valor de configuración para activar OTP en correos de cuentas bancarias. */

                $mandanteConfig['IsActivateOtpNotesEmailCB'] = $value->{'mandante_detalle.valor'};
                break;
            case 'OTPCODESALESPOINTSMS':
                /* Asigna un valor específico a la configuración de SMS de OTP para ventas. */

                $mandanteConfig['IsActivateOtpNotesSmsPV'] = $value->{'mandante_detalle.valor'};
                break;
            case 'OTPCODESALESPOINTEMAIL':
                /* Asigna un valor de configuración para activar el envío de correos OTP. */

                $mandanteConfig['IsActivateOtpNotesEmailPV'] = $value->{'mandante_detalle.valor'};
                break;
            case 'OTPCODEBANKACCOUNT':
                /* Asigna un valor de configuración de OTP para cuentas bancarias. */

                $mandanteConfig['IsActivateOtpNotesCuentaBancaria'] = $value->{'mandante_detalle.valor'};
                break;
            case 'MAXTIMEOTPCODE':
                /* Asigna el valor de tiempo para notas OTP en la configuración del mandante. */

                $mandanteConfig['OtpNotesTime'] = $value->{'mandante_detalle.valor'};
                break;
            case 'WITHDRAWAUTOEXPIRE':
                /* Asigna un valor de expiración automática a la configuración de retiro del mandante. */

                $mandanteConfig['ActiveWithdrawExpiration'] = $value->{'mandante_detalle.valor'};
                break;
            case 'WITHDRAWAUTOEXPIRETIME':
                /* asigna un valor de expiración de retiro a una configuración específica. */

                $mandanteConfig['WithdrawExpirationTime'] = $value->{'mandante_detalle.valor'};
                break;
            case 'EXCHANGEGIFTEVERYXTIME':
                $mandanteConfig['ExchangeOfTheSameGiftEveryXTime'] = $value->{'mandante_detalle.valor'}; // (I/A) indica si se puede canjear el mismo regalo en el tiempo configurado.
                break;
            case 'TIMEFOREXCHANGEGIFT':
                $mandanteConfig['TimeForExchange'] = $value->{'mandante_detalle.valor'}; // (I/A) indica el tiempo entre canjes del mismo regalo.
                break;
            case 'TYPEOFTIMEFOREXCHANGEGIFT':
                $mandanteConfig['TypeOfTime'] = $value->{'mandante_detalle.valor'}; // (I/A) indica el tipo de tiempo entre canjes del mismo regalo.
                break;
            case 'MINIMUMTIMEBETWEENANYEXCHANGES':
                $mandanteConfig['MinimumTimeBetweenAnyExchanges'] = $value->{'mandante_detalle.valor'};// (I/A) indica el tiempo mínimo entre canjes de cualquier regalo.
                break;
            case 'TYPEOFTIMEFOREXCHANGEGIFTGENERAL':
                $mandanteConfig['TypeOfTimeGeneral'] = $value->{'mandante_detalle.valor'}; // (I/A) indica el tipo de tiempo entre canjes de cualquier regalo.
                break;
            case 'TIMEFOREXCHANGEGIFTGENERAL':
                $mandanteConfig['TimeForExchangeGeneral'] = $value->{'mandante_detalle.valor'}; // (I/A) indica el tiempo entre canjes de cualquier regalo.
                break;
            default:
                break;
        }
    }


    /* Código PHP que define una respuesta JSON con estado y datos de configuración. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["data"] = $mandanteConfig;
    $response["ReferredBonuses"] = $referredBonuses;
    $response["GamesCategories"] = $GamesCategories;
    $response["LandingFreeBetBonuses"] = $landingFreeBetBonuses;
    $response["LandingFreeCasinoBonuses"] = $landingFreeCasinoBonuses;

}
