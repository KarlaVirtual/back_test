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
 * Reference/SaveFreebetBonus2
 *
 * SaveFreeBetBonus
 *
 * Este método guarda un bono Freebet en el sistema. Recibe varios parámetros para definir las características del bono,
 * como su nombre, descripción, reglas y restricciones. También establece detalles como los jugadores que pueden acceder
 * al bono y los deportes aplicables, y maneja la expiración, las selecciones mínimas, y otros detalles específicos del bono.
 *
 * @param object $params Objeto que contiene los parámetros de entrada para guardar el bono Freebet.
 *  - *Description* (string): Descripción del bono.
 *  - *Prefix* (string): Prefijo utilizado para generar los códigos promocionales.
 *  - *MaxplayersCount* (int): Número máximo de jugadores que pueden obtener el bono.
 *  - *LiveOrPreMatch* (string): Indica si el bono aplica para eventos en vivo o pre-partido.
 *  - *MinSelCount* (int): Mínimo número de selecciones necesarias para activar el bono.
 *  - *MinSelPrice* (float): Mínimo precio por selección.
 *  - *Name* (string): Nombre del bono.
 *  - *PartnerBonus* (object): Objeto que contiene detalles adicionales del bono del partner, como fechas de inicio y fin, y reglas específicas.
 *  - *StartDate* (string): Fecha de inicio del bono (proporcionada por el partner).
 *  - *EndDate* (string): Fecha de fin del bono (proporcionada por el partner).
 *  - *ExpirationDays* (int): Número de días de expiración del bono.
 *  - *BonusDetails* (array): Detalles adicionales del bono, como montos mínimos y máximos, y otros parámetros de configuración.
 *  - *SportBonusRules* (array): Reglas del bono específicas para deportes, incluyendo el tipo y los identificadores de los objetos.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error al guardar el bono.
 *  - *AlertType* (string): Tipo de alerta que se mostrará al usuario (por ejemplo, "success" o "danger").
 *  - *AlertMessage* (string): Mensaje con los detalles del resultado de la operación.
 *  - *ModelErrors* (array): Lista de errores del modelo si los hay.
 *  - *Result* (array): Resultado final de la operación.
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asigna valores de parámetros a variables en un código PHP. */
$Description = $params->Description;

$Prefix = $params->Prefix;
$MaxplayersCount = $params->MaxplayersCount;

$LiveOrPreMatch = $params->LiveOrPreMatch;

/* asigna valores de parámetros a variables correspondientes para su uso posterior. */
$MinSelCount = $params->MinSelCount;
$MinSelPrice = $params->MinSelPrice;
$Name = $params->Name;
$PartnerBonus = $params->PartnerBonus;
$StartDate = $PartnerBonus->StartDate;
$EndDate = $PartnerBonus->EndDate;

/* Se asignan propiedades a un objeto `BonoInterno` utilizando datos de `$PartnerBonus`. */
$ExpirationDays = $PartnerBonus->ExpirationDays;
$BonusDetails = $PartnerBonus->BonusDetails;

$BonoInterno = new BonoInterno();
$BonoInterno->nombre = $Name;
$BonoInterno->descripcion = $Description;

/* Asigna valores a propiedades de un objeto BonoInterno en PHP. */
$BonoInterno->fechaInicio = $StartDate;
$BonoInterno->fechaFin = $EndDate;
$BonoInterno->tipo = 6;
$BonoInterno->estado = 'A';
$BonoInterno->usucreaId = 0;
$BonoInterno->usumodifId = 0;

/* Código para insertar un bono en base de datos y mostrar cantidad de jugadores máximos. */
$BonoInterno->mandante = 0;

$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$bonoId = $BonoInterno->insert($transaccion);
print_r($MaxplayersCount);

/* Imprime el contenido de la variable $Prefix en formato legible. */
print_r($Prefix);

if ($MaxplayersCount != "" && $Prefix != "") {


    /* Se inicializa un array vacío llamado "codigosarray". */
    $codigosarray = array();

    for ($i = 1; $i <= $MaxplayersCount; $i++) {

        /* Genera un código único asegurando que no esté en el arreglo existente. */
        $codigo = (new ConfigurationEnvironment())->GenerarClaveTicket(4);

        while (in_array($codigo, $codigosarray)) {
            $codigo = (new ConfigurationEnvironment())->GenerarClaveTicket(4);
        }

        $PromocionalLog = new PromocionalLog();


        /* Asignación de valores a propiedades de un objeto para registro promocional. */
        $PromocionalLog->$usuarioId = '0';

        $PromocionalLog->$promocionalId = '12';

        $PromocionalLog->$valor = '';

        $PromocionalLog->$valorPromocional = '';


        /* inicializa propiedades de un objeto para un registro promocional. */
        $PromocionalLog->$valorBase = '';

        $PromocionalLog->$estado = 'A';

        $PromocionalLog->$errorId = '';

        $PromocionalLog->$idExterno = '';


        /* Asigna valores a propiedades de un objeto llamado PromocionalLog. */
        $PromocionalLog->$mandante = '0';

        $PromocionalLog->$version = '2';

        $PromocionalLog->$usucreaId = '0';

        $PromocionalLog->$usumodifId = '0';


        /* Se crea un registro promocional y se inserta en la base de datos. */
        $PromocionalLog->$apostado = '0';
        $PromocionalLog->$rollowerRequerido = '0';
        $PromocionalLog->$codigo = $Prefix . $codigo;

        $PromocionalLog = new PromocionalLog($transaccion);
        $PromocionalLog->insert($PromocionalLog);


        /* Añade un elemento al final del array llamado $codigosarray. */
        array_push($codigosarray, $codigo);

    }
}


//Expiracion

foreach ($BonusDetails as $key => $value) {

    /* Inserta un registro de BonoDetalle si MinAmount no está vacío. */
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

    /* Inserta un nuevo registro de BonoDetalle en la base de datos si MaxAmount es válido. */
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


/* Crea un nuevo registro de BonoDetalle si $ExpirationDays no está vacío. */
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


/* Inserta un nuevo registro de bono si LiveOrPreMatch no está vacío. */
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


/* Inserta un nuevo bono con datos específicos si $MinSelCount no está vacío. */
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


/* Inserta un nuevo registro de BonoDetalle si $MinSelPrice no está vacío. */
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


/* Inserta detalles de bonificaciones en la base de datos utilizando un bucle foreach. */
foreach ($SportBonusRules as $key => $value) {
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

/* finaliza una transacción y prepara una respuesta sin errores. */
$transaccion->commit();

$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Result"] = array();