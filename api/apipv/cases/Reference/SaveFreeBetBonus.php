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


/**
 * Reference/SaveFreeBetBonus
 *
 * Guardar el bono freebet
 *
 * @param object $params Objeto que contiene los parámetros de entrada para guardar el bono Freebet.
 * - *Description* (string): Descripción del bono.
 * - *Name* (string): Nombre del bono.
 * - *PartnerBonus* (string): Información sobre el bono del partner.
 * - *LiveOrPreMatch* (string): Indica si el bono aplica para eventos en vivo o pre-partido.
 * - *MinSelCount* (int): Mínimo número de selecciones necesarias para activar el bono.
 * - *MinSelPrice* (float): Mínimo precio por selección.
 * - *MinSelPriceTotal* (float): Precio total mínimo para las selecciones.
 * - *TriggerId* (int): Identificador del disparador (evento) que activa el bono.
 * - *CodePromo* (string): Código promocional asociado al bono.
 * - *MaxplayersCount* (int): Número máximo de jugadores que pueden obtener el bono.
 * - *Prefix* (string): Prefijo del bono.
 * - *PlayersChosen* (array): Lista de jugadores seleccionados que pueden obtener el bono.
 * - *ProductTypeId* (int): Identificador del tipo de producto al que se asocia el bono.
 * - *Games* (array): Lista de juegos a los que aplica el bono.
 * - *UserRepeatBonus* (bool): Indica si el bono se puede repetir para un usuario.
 * - *SportBonusRules* (array): Reglas específicas del bono para los deportes.
 * - *TriggerDetails* (string): Detalles adicionales sobre el disparador del bono.
 * - *Priority* (int): Prioridad del bono.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta a mostrar (ej. "success").
 *  - *AlertMessage* (string): Mensaje de alerta (vacío si no hay mensaje).
 *  - *ModelErrors* (array): Array vacío, no se manejan errores de modelo aquí.
 *  - *Result* (array): Array vacio
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


try {


    /* Asigna valores de parámetros a variables relacionadas con un bono. */
    $Description = $params->Description; //Descripcion del bono
    $Name = $params->Name; //Nombre del bono

    $PartnerBonus = $params->PartnerBonus;

    $StartDate = $PartnerBonus->StartDate; //Fecha Inicial de la campaña

    /* Asignación de variables relacionadas con fechas y condiciones de un bono de socio. */
    $EndDate = $PartnerBonus->EndDate; //Fecha Final de la campaña

    $ExpirationDays = $PartnerBonus->ExpirationDays; // Dias de expiracion del bono

    $LiveOrPreMatch = $params->LiveOrPreMatch;
    $MinSelCount = $params->MinSelCount;

    /* Asignación de precios mínimos y ID de disparador desde parámetros y JSON. */
    $MinSelPrice = $params->MinSelPrice;
    $MinSelPriceTotal = $params->MinSelPriceTotal;

    $MinBetPrice = $ForeignRuleJSON->MinBetPrice; // Minimo apuesta cuota total

    $TriggerId = $params->TriggerId;

    /* asigna valores de parámetros a variables relacionadas con promociones y jugadores. */
    $CodePromo = $params->CodePromo;


    $MaxplayersCount = $params->MaxplayersCount; // Maximo jugadores que lo pueden obtener
    $Prefix = $params->Prefix;

    $PlayersChosen = $params->PlayersChosen;

    /* Asigna valores de parámetros a variables y crea un array vacío para condiciones. */
    $ProductTypeId = $params->ProductTypeId;


    $Games = $params->Games;

    $condiciones = [];


    /* Asignación de variables para detalles de bonificaciones y parámetros de usuario. */
    $UserRepeatBonus = $params->UserRepeatBonus;
    $SportBonusRules = $params->SportBonusRules;

    $BonusDetails = $PartnerBonus->BonusDetails;

    $TriggerDetails = $params->TriggerDetails;


    /* Asignación de valor 'NA' si $ConditionProduct no es "OR" ni "AND". */
    $ConditionProduct = $TriggerDetails->ConditionProduct;
    if ($ConditionProduct != "OR" && $ConditionProduct != "AND") {
        $ConditionProduct = 'NA';
    }


    $tipobono = 6;

    /* valida y ajusta el valor de "Priority" a un número. */
    $Priority = $params->Priority;

    if ($Priority == "" || !is_numeric($Priority)) {
        $Priority = 0;
    }

    $cupo = 0;

    /* Asignación de valores máximos de cupo y jugadores basados en condiciones específicas. */
    $cupoMaximo = 0;
    $jugadores = 0;
    $jugadoresMaximo = 0;

    if ($MaximumAmount != "" && $tipobono == 2) {
        $cupoMaximo = $MaximumAmount[0]->Amount;
    }


    /* Asigna el número máximo de jugadores y guarda el ID del usuario en sesión. */
    if ($MaxplayersCount != "" && $tipobono == 2) {
        $jugadoresMaximo = $MaxplayersCount;
    }

    $usucrea_id = $_SESSION['usuario2'];
    $usumodif_id = $_SESSION['usuario2'];


    /* crea un objeto 'BonoInterno' y asigna valores a sus propiedades. */
    $BonoInterno = new BonoInterno();
    $BonoInterno->nombre = $Name;
    $BonoInterno->descripcion = $Description;
    $BonoInterno->fechaInicio = $StartDate;
    $BonoInterno->fechaFin = $EndDate;
    $BonoInterno->tipo = $tipobono;

    /* Asigna valores a propiedades de un objeto BonoInterno en PHP. */
    $BonoInterno->estado = 'A';
    $BonoInterno->usucreaId = 0;
    $BonoInterno->usumodifId = 0;
    $BonoInterno->mandante = 0;
    $BonoInterno->condicional = $ConditionProduct;
    $BonoInterno->orden = $Priority;

    /* Se asignan valores a propiedades del objeto BonoInterno y se crea un DAO. */
    $BonoInterno->cupoActual = $cupo;
    $BonoInterno->cupoMaximo = $cupoMaximo;
    $BonoInterno->cantidadBonos = $jugadores;
    $BonoInterno->maximoBonos = $jugadoresMaximo;


    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

    /* obtiene una transacción y la inserta en la base de datos. */
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $bonoId = $BonoInterno->insert($transaccion);


    if ($MaxplayersCount != "" && $Prefix != "") {


        /* asigna jugadores seleccionados a un array con valor inicial cero. */
        $jugadoresAsignar = array();
        $jugadoresAsignarFinal = array();

        if ($PlayersChosen != "") {
            $jugadoresAsignar = explode(",", $PlayersChosen);

            foreach ($jugadoresAsignar as $item) {

                array_push($jugadoresAsignarFinal, array("Id" => $item, "Valor" => 0));

            }
        }


        /* Se define un array vacío llamado $codigosarray en PHP. */
        $codigosarray = array();

        for ($i = 1; $i <= $MaxplayersCount; $i++) {

            /* Genera un código único y asigna valores de jugadores. */
            $codigo = (new ConfigurationEnvironment())->GenerarClaveTicket(4);

            while (in_array($codigo, $codigosarray)) {
                $codigo = (new ConfigurationEnvironment())->GenerarClaveTicket(4);
            }

            if ($jugadoresAsignarFinal[$i]["Id"] != "") {
                $usuarioId = $jugadoresAsignarFinal[$i]["Id"];

                $valor = $jugadoresAsignarFinal[$i]["Valor"];

                $valor_bono = $jugadoresAsignarFinal[$i]["Valor"];

                $valor_base = $jugadoresAsignarFinal[$i]["Valor"];


            } else {
                /* Asignación de valores predeterminados a variables cuando una condición no se cumple. */

                $usuarioId = '0';
                $valor = '0';

                $valor_bono = '0';

                $valor_base = '0';

            }


            /* Inicializa variables con valores predeterminados para estado, error, id externo y mandante. */
            $estado = 'L';

            $errorId = '0';

            $idExterno = '0';

            $mandante = '0';


            /* Inicializa variables para identificar usuarios y un monto apostado en cero. */
            $usucreaId = '0';

            $usumodifId = '0';


            $apostado = '0';

            /* establece un bono de usuario con un ID específico. */
            $rollowerRequerido = '0';
            $codigo = $Prefix . $codigo;

            $UsuarioBono = new UsuarioBono();

            $UsuarioBono->setUsuarioId($usuarioId);

            /* Código que establece atributos en un objeto UsuarioBono. */
            $UsuarioBono->setBonoId($bonoId);
            $UsuarioBono->setValor($valor);
            $UsuarioBono->setValorBono($valor_bono);
            $UsuarioBono->setValorBase($valor_base);
            $UsuarioBono->setEstado($estado);
            $UsuarioBono->setErrorId($errorId);

            /* Configura propiedades del objeto UsuarioBono con diferentes valores y parámetros. */
            $UsuarioBono->setIdExterno($idExterno);
            $UsuarioBono->setMandante($mandante);
            $UsuarioBono->setUsucreaId($usucreaId);
            $UsuarioBono->setUsumodifId($usumodifId);
            $UsuarioBono->setApostado($apostado);
            $UsuarioBono->setRollowerRequerido($rollowerRequerido);

            /* Asignación de valores y almacenamiento de un objeto UsuarioBono en base de datos MySQL. */
            $UsuarioBono->setCodigo($codigo);
            $UsuarioBono->setVersion(2);
            $UsuarioBono->setExternoId(0);

            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

            $UsuarioBonoMysqlDAO->insert($UsuarioBono);


            /* Añade un elemento `$codigo` al final del arreglo `$codigosarray`. */
            array_push($codigosarray, $codigo);

        }
    }


    //Expiracion


    /* Inserta un registro de bono si se especifican días de expiración. */
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


    /* Condición que inserta un detalle de bono si hay un bono repetido. */
    if ($UserRepeatBonus != "") {

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


    /* Inserta un nuevo detalle de bono si el prefijo no está vacío. */
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


    /* Inserta un nuevo bono en la base de datos si se utiliza una billetera congelada. */
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


    /* Inserta un nuevo registro de bono si se suprime un retiro. */
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


    /* Inserta un registro de bono detalle si $ScheduleCount no está vacío. */
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


    /* Crea un registro de bono si $ScheduleName no está vacío. */
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


    /* Inserta un detalle de bono si el periodo programado no está vacío. */
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


    /* Inserta un registro de BonoDetalle si SchedulePeriodType no está vacío. */
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


    /* Inserta un nuevo bonodetalle en la base de datos si el tipo de producto es válido. */
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


    /* Inserta un objeto BonoDetalle en la base de datos si $Count no está vacío. */
    if ($Count != "") {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CANTDEPOSITOS";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $Count;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta un nuevo detalle de bono si se permiten ciertos lugares. */
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


    /* Inserta un registro de detalle de bono si la fecha de expiración no está vacía. */
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


    /* Inserta un nuevo detalle de bono en la base de datos si el porcentaje no está vacío. */
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


    /* inserta un bono en la base de datos si $BonusWFactor no está vacío. */
    if ($BonusWFactor != "") {
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


    /* Inserta un nuevo registro de bono detalle si $DepositWFactor no está vacío. */
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


    /* Inserta un nuevo detalle de bono si el número de depósito no está vacío. */
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


    /* Inserta un registro de BonoDetalle si proviene de una caja. */
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


    }


    /* Insertar un registro de bono de detalle si se especifica el conteo de jugadores. */
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


    /* Inserta detalles del bono en la base de datos para cada pago máximo. */
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


    /* Inserta detalles de bonos de máximo depósito en la base de datos. */
    foreach ($MaximumDeposit as $key => $value) {
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


    /* Inserta detalles de bonos mínimos de depósito en la base de datos. */
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


    }


    /* Inserta detalles de bonos en la base de datos para cada requisito monetario. */
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


    /* Inserta detalles de bonificación en base de datos utilizando un arreglo de IDs. */
    foreach ($PaymentSystemIds as $key => $value) {
        $BonoDetalle = new BonoDetalle();
        $BonoDetalle->bonoId = $bonoId;
        $BonoDetalle->tipo = "CONDPAYMENT";
        $BonoDetalle->moneda = '';
        $BonoDetalle->valor = $value;
        $BonoDetalle->usucreaId = 0;
        $BonoDetalle->usumodifId = 0;
        $BonoDetalleMysqlDAO = new BonoDetalleMySqlDAO($transaccion);
        $BonoDetalleMysqlDAO->insert($BonoDetalle);


    }


    /* Inserta detalles de bono en la base de datos para cada región. */
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


    /* inserta detalles de bonificaciones por cada departamento en una base de datos. */
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


    /* Inserta detalles de bonificación para cada ciudad en una base de datos. */
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


    /* Se inserta un bono detalle en la base de datos para cada región del usuario. */
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


    /* Recorre departamentos y registra detalles de bonificaciones en la base de datos. */
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


    /* Inserta detalles de bono para cada ciudad del usuario en la base de datos. */
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


    /* itera sobre un arreglo y guarda detalles de bonos en una base de datos. */
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


    /* Inserta detalles de bonos en la base de datos para cada juego en $Games. */
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


    /* Itera sobre reglas de bonificación deportiva y guarda detalles en la base de datos. */
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


    /* Inserta un nuevo registro de bono si $LiveOrPreMatch no está vacío. */
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


    /* inserta un registro de bono si MinSelCount no está vacío. */
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


    /* Inserta un detalle de bono si el precio mínimo no está vacío. */
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


    /* Inserta un nuevo registro de BonoDetalle si MinSelPriceTotal no está vacío. */
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


    /* Inserta un registro de bono en la base de datos si hay un código promocional. */
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


    /* Inserta un nuevo registro de bono si el precio mínimo de apuesta no está vacío. */
    if ($MinBetPrice != "") {

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

        /* Se inserta un detalle de bono en la base de datos si MaxAmount no está vacío. */
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


    /* finaliza una transacción y prepara una respuesta sin errores. */
    $transaccion->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* Se inicializa un array vacío en la clave "Result" de la variable $response. */
    $response["Result"] = array();

} catch (Exception $e) {
    /* Maneja excepciones en PHP e imprime el error si ocurre uno. */


    print_r($e);
}