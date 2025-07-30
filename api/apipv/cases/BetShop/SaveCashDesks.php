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
 * BetShop/SaveCashDesks
 *
 * Guardar los puntos de venta.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params->BetshopId Identificador del punto de venta.
 * @param int $params->CurrencyId Identificador de la moneda.
 * @param float $params->DepositCupo Cupo de depósito.
 * @param float $params->LimitAmount Límite de monto.
 * @param float $params->LivePercentage Porcentaje en vivo.
 * @param float $params->MaxBetLive Apuesta máxima en vivo.
 * @param float $params->MaxBetPreMatch Apuesta máxima pre-partido.
 * @param float $params->MinBalance Saldo mínimo.
 * @param float $params->MinBet Apuesta mínima.
 * @param string $params->Name Nombre del punto de venta.
 * @param float $params->PreMatchPercentage Porcentaje pre-partido.
 * @param float $params->RecargasPercentage Porcentaje de recargas.
 * @param boolean $params->UseTurnoverLimit Indica si se usa límite de facturación.
 * @param int $params->FromId Identificador del remitente.
 * @param string $params->Address Dirección del punto de venta.
 * @param string $params->UserName Nombre de usuario.
 * @param string $params->FirstName Primer nombre del usuario.
 *  @param string $params->LastName Apellido del usuario.
 * @param string $params->Password Contraseña del usuario.
 * @param boolean $params->CanReceipt Indica si puede recibir.
 * @param boolean $params->CanDeposit Indica si puede depositar.
 * 
 * 
 * @return array $response Respuesta con los siguientes valores:
 * - HasError: boolean Indica si ocurrió un error.
 * - AlertType: string Tipo de alerta.
 * - AlertMessage: string Mensaje de alerta.
 * - ModelErrors: array Errores del modelo.
 */


/* Asigna valores de un objeto $params a nuevas variables para su uso posterior. */
$params = $params;

$Address = $params->BetshopId;
$CityId = $params->CurrencyId;
$CountryId = $params->DepositCupo;
$CurrencyId = $params->LimitAmount;

/* asigna valores de parámetros a variables para usar en apuestas. */
$DocumentLegalID = $params->LivePercentage;
$MaxBetLive = $params->MaxBetLive;
$MaxBetPreMatch = $params->MaxBetPreMatch;
$MinBalance = $params->MinBalance;
$MinBet = $params->MinBet;
$Name = $params->Name;

/* Se asignan parámetros de configuración a variables para su uso posterior en el código. */
$PreMatchPercentage = $params->PreMatchPercentage;
$RecargasPercentage = $params->RecargasPercentage;
$UseTurnoverLimit = $params->UseTurnoverLimit;
$BetshopId = $params->FromId;

$Address = $params->Address;

/* asigna valores de un objeto a variables para manejo de usuario. */
$login = $params->UserName;
$FirstName = $params->FirstName;
$Id = $params->Id;
$LastName = $params->LastName;
$Password = $params->Password;
$UserName = $params->UserName;


/* depura nombres y asigna permisos de recepción y depósito. */
$login = $login;
$FirstName = DepurarCaracteres($Name);
$FirstName = DepurarCaracteres($FirstName);

$CanReceipt = $params->CanReceipt;
$CanDeposit = $params->CanReceipt;

/* asigna 'S' o 'N' a $CanReceipt según su valor booleano. */
$CanActivateRegister = $params->CanReceipt;

if ($CanReceipt == true) {
    $CanReceipt = 'S';
} else {
    $CanReceipt = 'N';
}


/* Convierte valores booleanos a caracteres 'S' o 'N' para depósitos y activaciones. */
if ($CanDeposit == true) {
    $CanDeposit = 'S';
} else {
    $CanDeposit = 'N';
}

if ($CanActivateRegister == true) {
    $CanActivateRegister = 'S';
} else {
/* Asigna 'N' a $CanActivateRegister si la condición del "if" no se cumple. */

    $CanActivateRegister = 'N';
}

if ($BetshopId != "" && is_numeric($BetshopId)) {


/* gestiona la numeración de usuarios y actualiza una base de datos. */
    $consecutivo_usuario = 0;

    /*$Consecutivo = new Consecutivo("", "USU", "");

    $consecutivo_usuario = $Consecutivo->numero;

    $consecutivo_usuario++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

    $Consecutivo->setNumero($consecutivo_usuario);


    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();*/

    $UsuarioPV = new Usuario($BetshopId);
    //$PuntoVenta = new PuntoVenta("",$BetshopId);



/* Se crea un objeto Usuario y se asignan propiedades. */
    $Usuario = new Usuario();


    $Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $login;


/* Código que asigna valores a atributos del objeto $Usuario. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


/* Se asignan valores iniciales a propiedades del objeto Usuario. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'A';

    $Usuario->observ = '';


/* Código inicializa propiedades del objeto Usuario, como IP, estado y creador. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = $UsuarioPV->mandante;

    $Usuario->usucreaId = '0';


/* Inicializa propiedades del objeto Usuario y genera un token de autenticación. */
    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

    $Usuario->tokenItainment = $token_itainment;


/* Inicializa propiedades de un objeto Usuario con valores vacíos. */
    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';


/* Asignación de propiedades para un objeto Usuario en programación. */
    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = 'N';

    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'AC';


/* Se asignan valores iniciales a propiedades del objeto $Usuario. */
    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';

    $Usuario->paisId = $UsuarioPV->paisId;


/* Se asignan valores de un objeto $UsuarioPV a otro objeto $Usuario. */
    $Usuario->moneda = $UsuarioPV->moneda;

    $Usuario->idioma = $UsuarioPV->idioma;

    $Usuario->permiteActivareg = $CanActivateRegister;

    $Usuario->test = 'N';


/* Establece parámetros de usuario para depósito, autoexclusión, cambios y zona horaria. */
    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';


/* Asigna valores a propiedades de un objeto Usuario, incluyendo fechas y origen. */
    $Usuario->puntoventaId = $UsuarioPV->usuarioId;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;

/* Asigna y valida datos de usuario, incluyendo estado y fechas de validación. */
    $Usuario->documentoValidado = "A";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;

    $Usuario->estadoValida = 'N';
    $Usuario->usuvalidaId = 0;

/* Asigna valores iniciales a propiedades del objeto Usuario en PHP. */
    $Usuario->fechaValida = date('Y-m-d H:i:s');
    $Usuario->estadoValida = 'N';
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';
    $Usuario->contingenciaCasvivo = 'I';

/* Asignación de valores a propiedades del objeto Usuario relacionadas con restricciones y ubicaciones. */
    $Usuario->contingenciaVirtuales = 'I';
    $Usuario->contingenciaPoker = 'I';
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = '';
    $Usuario->ubicacionLatitud = '';
    $Usuario->usuarioIp = '';

/* inicializa un usuario y lo inserta en la base de datos. */
    $Usuario->tokenGoogle = 'I';
    $Usuario->tokenLocal = 'I';
    $Usuario->saltGoogle = '';

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $UsuarioMySqlDAO->insert($Usuario);

/* Código para asignar configuraciones de usuario en una aplicación. */
    $consecutivo_usuario = $Usuario->usuarioId;

    $UsuarioConfig = new UsuarioConfig();
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfig->pinagent = '';
    $UsuarioConfig->reciboCaja = $CanReceipt;

/* Se asignan valores a propiedades de usuario y se crea un perfil de cajero. */
    $UsuarioConfig->mandante = $UsuarioPV->mandante;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;

    $UsuarioPerfil = new UsuarioPerfil();
    $UsuarioPerfil->usuarioId = $consecutivo_usuario;
    $UsuarioPerfil->perfilId = 'CAJERO';

/* Se asignan valores a las propiedades del objeto UsuarioPerfil y se crea UsuarioPremiomax. */
    $UsuarioPerfil->mandante = $UsuarioPV->mandante;
    $UsuarioPerfil->pais = 'S';
    $UsuarioPerfil->global = 'N';

    $UsuarioPremiomax = new UsuarioPremiomax();


    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;


/* Inicializa propiedades de un objeto UsuarioPremiomax con valores predeterminados. */
    $UsuarioPremiomax->premioMax = 0;

    $UsuarioPremiomax->usumodifId = 0;

    $UsuarioPremiomax->fechaModif = "";

    $UsuarioPremiomax->cantLineas = 0;


/* Inicializa variables de premio y establece un valor mínimo de apuesta si está vacío. */
    $UsuarioPremiomax->premioMax1 = 0;

    $UsuarioPremiomax->premioMax2 = 0;

    $UsuarioPremiomax->premioMax3 = 0;

    if ($MinBet == "") {
        $MinBet = "0";
    }

/* Asigna valores a propiedades del objeto $UsuarioPremiomax basándose en datos de $UsuarioPV. */
    $UsuarioPremiomax->apuestaMin = $MinBet;

    $UsuarioPremiomax->valorDirecto = 0;

    $UsuarioPremiomax->premioDirecto = 0;

    $UsuarioPremiomax->mandante = $UsuarioPV->mandante;


/* Se asignan valores iniciales a propiedades de un objeto $UsuarioPremiomax. */
    $UsuarioPremiomax->optimizarParrilla = "N";

    $UsuarioPremiomax->textoOp1 = "";

    $UsuarioPremiomax->textoOp2 = "";

    $UsuarioPremiomax->urlOp2 = "";


/* Asignación de valores iniciales a propiedades del objeto UsuarioPremiomax. */
    $UsuarioPremiomax->textoOp3 = 0;

    $UsuarioPremiomax->urlOp3 = 0;

    $UsuarioPremiomax->valorEvento = 0;

    $UsuarioPremiomax->valorDiario = 0;



/* Se insertan configuraciones y perfiles de usuario utilizando DAO en MySQL. */
    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);


/* Código para insertar datos de usuario y actualizar contraseña en una base de datos. */
    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

    $UsuarioMySqlDAO->getTransaction()->commit();

    $UsuarioMySqlDAO->updateClave($Usuario, $Password);


/* inicializa una respuesta sin errores y con mensaje de éxito. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


} else {
/* Manejo de errores en respuesta JSON, indicando fallo y mensaje específico. */

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Error no puede crearlo";
    $response["ModelErrors"] = [];

}

/* Función que elimina caracteres específicos de un texto. */
/**
 * Elimina caracteres específicos de un texto.
 *
 * @param string $texto_depurar El texto que se va a depurar.
 * @return string El texto depurado sin los caracteres específicos.
 */
function DepurarCaracteres($texto_depurar)
{
    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);

    /* elimina caracteres específicos de la variable $texto_depurar. */
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);

    /* elimina caracteres no deseados de la variable $texto_depurar. */
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);

    /* elimina caracteres específicos de la variable $texto_depurar. */
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);

    return $texto_depurar;
}