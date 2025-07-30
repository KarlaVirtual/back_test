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
 * Agent/SaveAgent22222
 *
 * Guardar un agente versión 22222
 *
 * @param object $params Objeto que contiene los parámetros necesarios para guardar un agente.
 * @param string $params ->Address Dirección del agente.
 * @param int $params ->CityId ID de la ciudad del agente.
 * @param string $params ->ContactName Nombre del contacto del agente.
 * @param int $params ->CurrencyId ID de la moneda del agente.
 * @param string $params ->Email Correo electrónico del agente.
 * @param string $params ->FirstName Nombre del agente.
 * @param string $params ->Description Descripción del agente.
 * @param int $params ->Id ID del agente.
 * @param bool $params ->IsSuspended Indica si el agente está suspendido.
 * @param string $params ->LastLoginIp Última IP de inicio de sesión del agente.
 * @param string $params ->LastLoginLocalDate Última fecha de inicio de sesión del agente.
 * @param string $params ->LastName Apellido del agente.
 * @param string $params ->Phone Teléfono del agente.
 * @param string $params ->SystemName Nombre del sistema del agente.
 * @param int $params ->UserId ID del usuario del agente.
 * @param string $params ->UserName Nombre de usuario del agente.
 * @param string $params ->LoginAccess Acceso de inicio de sesión del agente.
 * @param int $params ->CountryId ID del país del agente.
 * @param string $params ->PreferredLanguage Idioma preferido del agente.
 * @param int $params ->Type Tipo de agente.
 * @param string $params ->Password Contraseña del agente.
 *
 * @return array Respuesta con el estado de la operación.
 *  - HasError:bool Indica si hubo un error en la operación
 *  - AlertType:string Tipo de alerta de la operación
 *  - AlertMessage:string Mensaje de alerta de la operación
 *  - ModelErrors:array Errores del modelo si los hay
 *  - Data:array Datos adicionales de la operación
 */


/* asigna valores de parámetros a variables para su uso posterior. */
$Address = $params->Address;
$CityId = $params->CityId;

$ContactName = $params->ContactName;
$CurrencyId = $params->CurrencyId;
$Email = $params->Email;

/* Asignación de valores de un objeto $params a variables correspondientes en PHP. */
$FirstName = $params->FirstName;
$FirstName = $params->Description;
$Id = $params->Id;
$IsSuspended = $params->IsSuspended;
$LastLoginIp = $params->LastLoginIp;
$LastLoginLocalDate = $params->LastLoginLocalDate;

/* Asignación de variables a partir de parámetros recibidos en un objeto. */
$LastName = $params->LastName;
$clave = $params->Phone;
$SystemName = $params->SystemName;
$UserId = $params->UserId;
$UserName = $params->UserName;
$UserName = $params->LoginAccess;

/* Asignación de parámetros a variables en un script, probablemente para procesamiento de datos de usuario. */
$Phone = $params->Phone;
$CountryId = $params->CountryId;
$CurrencyId = $params->CurrencyId;
$PreferredLanguage = $params->PreferredLanguage;
$Type = $params->Type;
$Password = $params->Password;


/* Código que actualiza un usuario si su ID y UserId no están vacíos. */
if ($Id != "" && $UserId != "") {
    $Usario = new Usuario($UserId);

    $Usario->setCelular($Phone);
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO->update($Usuario);

    $UsuarioMySqlDAO->getTransaction()->commit();
} else {

    /* asigna valores iniciales y verifica condiciones para permisos de usuario. */
    $login = $UserName;

    $CanReceipt = false;
    $CanDeposit = false;
    $CanActivateRegister = false;

    if ($CanReceipt == true) {
        $CanReceipt = 'S';
    } else {
        /* Asignación de valor 'N' a la variable $CanReceipt si la condición anterior no se cumple. */

        $CanReceipt = 'N';
    }


    /* convierte booleanos en caracteres 'S' o 'N' para depósitos y activación. */
    if ($CanDeposit == true) {
        $CanDeposit = 'S';
    } else {
        $CanDeposit = 'N';
    }

    if ($CanActivateRegister == true) {
        $CanActivateRegister = 'S';
    } else {
        /* asigna 'N' a $CanActivateRegister si no se cumple una condición previa. */

        $CanActivateRegister = 'N';
    }


    /* Inicializa un contador de usuarios y crea un objeto PuntoVenta basado en un ID. */
    $consecutivo_usuario = 0;
    /*$Consecutivo = new Consecutivo("", "USU", "");

        $consecutivo_usuario = $Consecutivo->numero;

        $consecutivo_usuario++;

        $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

        $Consecutivo->setNumero($consecutivo_usuario);


        $ConsecutivoMySqlDAO->update($Consecutivo);

        $ConsecutivoMySqlDAO->getTransaction()->commit();*/

    $PuntoVenta = new PuntoVenta($CashDeskId);


    /* Se crea un objeto Usuario y se asignan valores a sus propiedades. */
    $Usuario = new Usuario();


    $Usuario->usuarioId = $consecutivo_usuario;

    $Usuario->login = $login;


    /* Asigna valores a propiedades del objeto Usuario, incluyendo fecha actual y estado activo. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


    /* Se inicializan propiedades del objeto Usuario para gestionar su estado y observaciones. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'I';

    $Usuario->observ = '';


    /* Se asignan valores iniciales a propiedades del objeto $Usuario. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = '0';

    $Usuario->usucreaId = '0';


    /* Se asignan valores y se genera un token para el usuario. */
    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

    $Usuario->tokenItainment = $token_itainment;


    /* Inicializa variables del objeto Usuario para almacenar fechas y estado de retiro. */
    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';


    /* Se asignan valores a propiedades de un objeto Usuario en PHP. */
    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = 'N';

    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'AC';


    /* Asignación de propiedades a un objeto Usuario: token, patrocinador, verificación y país. */
    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';

    $Usuario->paisId = $CountryId;


    /* asigna valores a propiedades del objeto $Usuario. */
    $Usuario->moneda = $CurrencyId;

    $Usuario->idioma = $PreferredLanguage;

    $Usuario->permiteActivareg = $CanActivateRegister;

    $Usuario->test = 'N';


    /* Código que establece propiedades de un usuario, incluyendo tiempo de depósito y zona horaria. */
    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';


    /* Código para asignar propiedades a un objeto Usuario en PHP. */
    $Usuario->puntoventaId = $consecutivo_usuario;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;

    /* Se establece el estado de validación y fechas en el objeto Usuario. */
    $Usuario->documentoValidado = "A";
    $Usuario->fechaDocvalido = $Usuario->fechaCrea;
    $Usuario->usuDocvalido = 0;
    $Usuario->estadoValida = 'A';
    $Usuario->usuvalidaId = 0;
    $Usuario->fechaValida = $Usuario->fechaCrea;

    /* Asigna un valor 'I' a diversas propiedades de contingencia del objeto Usuario. */
    $Usuario->contingencia = 'I';
    $Usuario->contingenciaDeportes = 'I';
    $Usuario->contingenciaCasino = 'I';
    $Usuario->contingenciaCasvivo = 'I';
    $Usuario->contingenciaVirtuales = 'I';
    $Usuario->contingenciaPoker = 'I';

    /* Código asigna valores de restricción y ubicación a un objeto usuario. */
    $Usuario->restriccionIp = 'I';
    $Usuario->ubicacionLongitud = 0;
    $Usuario->ubicacionLatitud = 0;
    $Usuario->usuarioIp = $IP;
    $Usuario->tokenGoogle = "I";
    $Usuario->tokenLocal = "I";

    /* inserta un usuario en la base de datos y guarda su ID. */
    $Usuario->saltGoogle = '';

    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $UsuarioMySqlDAO->insert($Usuario);
    $consecutivo_usuario = $Usuario->usuarioId;


    /* Configura las propiedades de un objeto UsuarioConfig según ciertos parámetros establecidos. */
    $UsuarioConfig = new UsuarioConfig();
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfig->pinagent = '';
    $UsuarioConfig->reciboCaja = $CanReceipt;
    $UsuarioConfig->mandante = 0;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;


    /* Se crea una nueva instancia de la clase Concesionario. */
    $Concesionario = new Concesionario();

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {

        /* Se crea un objeto de usuario con perfil basado en un tipo específico. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        if ($Type == 1) {
            $UsuarioPerfil->perfilId = 'AFILIADOR';
        } else {
            $UsuarioPerfil->perfilId = 'CONCESIONARIO2';
        }


        /* Se asignan valores a propiedades de un objeto UsuarioPerfil y se crea Concesionario. */
        $UsuarioPerfil->mandante = 0;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';


        $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);


        /* Código que configura identificadores de usuario y porcentaje para un objeto concesionario. */
        $Concesionario->setUsupadreId($ConcesionarioU->getUsupadreId());
        $Concesionario->setUsuhijoId($consecutivo_usuario);
        $Concesionario->setusupadre2Id(0);
        $Concesionario->setusupadre3Id(0);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);

        /* Se establecen valores iniciales en un objeto de concesionario. */
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante(0);

        /* inicializa un concesionario con IDs de usuario y estado activo. */
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);
        $Concesionario->setEstado("A");
    } else {


        /* Código que asigna un perfil a un usuario según tipo especificado. */
        $UsuarioPerfil = new UsuarioPerfil();
        $UsuarioPerfil->usuarioId = $consecutivo_usuario;
        if ($Type == 1) {
            $UsuarioPerfil->perfilId = 'AFILIADOR';
        } else {
            $UsuarioPerfil->perfilId = 'CONCESIONARIO';
        }


        /* Se configuran parámetros de usuario y concesionario, estableciendo IDs y propiedades. */
        $UsuarioPerfil->mandante = 0;
        $UsuarioPerfil->pais = 'S';
        $UsuarioPerfil->global = 'N';

        $Concesionario->setUsupadreId(0);
        $Concesionario->setUsuhijoId($consecutivo_usuario);

        /* Se inicializan varios identificadores y porcentajes en un objeto "Concesionario". */
        $Concesionario->setusupadre2Id(0);
        $Concesionario->setusupadre3Id(0);
        $Concesionario->setusupadre4Id(0);
        $Concesionario->setPorcenhijo(0);
        $Concesionario->setPorcenpadre1(0);
        $Concesionario->setPorcenpadre2(0);

        /* Establece valores iniciales a diversas propiedades del objeto Concesionario. */
        $Concesionario->setPorcenpadre3(0);
        $Concesionario->setPorcenpadre4(0);
        $Concesionario->setProdinternoId(0);
        $Concesionario->setMandante(0);
        $Concesionario->setUsucreaId(0);
        $Concesionario->setUsumodifId(0);

        /* Se establece el estado del concesionario a "A" mediante un método. */
        $Concesionario->setEstado("A");


    }


    /* Se crea un objeto PuntoVenta con información de contacto y ubicación específica. */
    $PuntoVenta = new PuntoVenta();
    $PuntoVenta->descripcion = $FirstName;
    $PuntoVenta->nombreContacto = $ContactName;
    $PuntoVenta->ciudadId = $CityId->Id;
    $PuntoVenta->ciudadId = $CityId;
    $PuntoVenta->direccion = $Address;

    /* Asigna valores a propiedades del objeto PuntoVenta. */
    $PuntoVenta->barrio = $District;
    $PuntoVenta->telefono = $Phone;
    $PuntoVenta->email = $Email;
    $PuntoVenta->periodicidadId = 0;
    $PuntoVenta->clasificador1Id = 0;
    $PuntoVenta->clasificador2Id = 0;

    /* Asigna valores iniciales a propiedades de un objeto PuntoVenta en PHP. */
    $PuntoVenta->clasificador3Id = 0;
    $PuntoVenta->valorRecarga = 0;
    $PuntoVenta->valorCupo = '0';
    $PuntoVenta->valorCupo2 = '0';
    $PuntoVenta->porcenComision = '0';
    $PuntoVenta->porcenComision2 = '0';

    /* Se configura un objeto PuntoVenta con diversos atributos iniciales, como estado y moneda. */
    $PuntoVenta->estado = 'A';
    $PuntoVenta->usuarioId = '0';
    $PuntoVenta->mandante = 0;
    $PuntoVenta->moneda = $CurrencyId;
    //$PuntoVenta->moneda = $CurrencyId->Id;
    $PuntoVenta->idioma = 'ES';

    /* Inicializa variables relacionadas con créditos y usuario en un punto de venta. */
    $PuntoVenta->cupoRecarga = 0;
    $PuntoVenta->creditosBase = 0;
    $PuntoVenta->creditos = 0;
    $PuntoVenta->creditosAnt = 0;
    $PuntoVenta->creditosBaseAnt = 0;
    $PuntoVenta->usuarioId = $consecutivo_usuario;


    /* Se crea un objeto UsuarioPremiomax y se inicializan sus propiedades. */
    $UsuarioPremiomax = new UsuarioPremiomax();


    $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

    $UsuarioPremiomax->premioMax = 0;


    /* Inicializa propiedades de un objeto UsuarioPremiomax a valores predeterminados. */
    $UsuarioPremiomax->usumodifId = 0;

    $UsuarioPremiomax->fechaModif = "";

    $UsuarioPremiomax->cantLineas = 0;

    $UsuarioPremiomax->premioMax1 = 0;


    /* Inicializa propiedades de objeto UsuarioPremiomax con valores predeterminados de cero. */
    $UsuarioPremiomax->premioMax2 = 0;

    $UsuarioPremiomax->premioMax3 = 0;

    $UsuarioPremiomax->apuestaMin = 0;

    $UsuarioPremiomax->valorDirecto = 0;


    /* Se inicializan propiedades de un objeto UsuarioPremiomax con valores predeterminados. */
    $UsuarioPremiomax->premioDirecto = 0;

    $UsuarioPremiomax->mandante = 0;

    $UsuarioPremiomax->optimizarParrilla = "N";

    $UsuarioPremiomax->textoOp1 = "";


    /* Se inicializan propiedades de un objeto con valores vacíos o cero. */
    $UsuarioPremiomax->textoOp2 = "";

    $UsuarioPremiomax->urlOp2 = "";

    $UsuarioPremiomax->textoOp3 = 0;

    $UsuarioPremiomax->urlOp3 = 0;


    /* Se inicializan valores y se actualiza la clave del usuario en la base de datos. */
    $UsuarioPremiomax->valorEvento = 0;

    $UsuarioPremiomax->valorDiario = 0;

    $UsuarioMySqlDAO->updateClave($Usuario, $Password);

    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* Código para insertar datos en una base de datos usando DAO en MySQL. */
    $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $ConcesionarioMySqlDAO->insert($Concesionario);

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);


    /* Se crean instancias DAO y se insertan datos en la base de datos. */
    $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());


    /* Inserta un punto de venta y obtiene la transacción del usuario en MySQL. */
    $PuntoVentaMySqlDAO->insert($PuntoVenta);

    $UsuarioMySqlDAO->getTransaction()->commit();
}


/* construye una respuesta JSON indicando éxito en una operación. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Operation has completed successfully";
$response["ModelErrors"] = [];


$response["Data"] = array();

