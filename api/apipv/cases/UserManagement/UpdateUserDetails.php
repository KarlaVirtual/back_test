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
 * UserManagement/UpdateUserDetails
 *
 * Crea un nuevo usuario asociado a un punto de venta determinado.
 *
 * Este recurso permite registrar un nuevo usuario en el sistema, estableciendo sus credenciales, permisos y configuraciones iniciales.
 * El usuario creado se asocia a un punto de venta y su configuración de acceso se almacena en la base de datos.
 *
 * @param string $CashDeskId : Identificador del punto de venta asociado al usuario.
 * @param string $Address : Dirección del usuario.
 * @param string $UserName : Nombre de usuario para el inicio de sesión.
 * @param string $FirstName : Nombre del usuario.
 * @param string $LastName : Apellido del usuario.
 * @param string $Password : Contraseña del usuario.
 * @param bool $CanReceipt : Indica si el usuario tiene permisos para generar recibos.
 * @param bool $CanDeposit : Indica si el usuario tiene permisos para realizar depósitos.
 * @param bool $CanActivateRegister : Indica si el usuario puede activar registros.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío en caso de éxito.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se asigna el valor de CashDeskId desde los parámetros proporcionados. */
$CashDeskId = $params->CashDeskId;

if ($CashDeskId != "") {


    /* Variables asignadas desde el objeto `$params`, almacenando datos de usuario. */
    $Address = $params->Address;
    $login = $params->UserName;
    $FirstName = $params->FirstName;
    $Id = $params->Id;
    $LastName = $params->LastName;
    $Password = $params->Password;

    /* asigna permisos basados en parámetros de usuario y verifica el permiso de recibos. */
    $UserName = $params->UserName;

    $CanReceipt = $params->CanReceipt;
    $CanDeposit = $params->CanReceipt;
    $CanActivateRegister = $params->CanReceipt;

    if ($CanReceipt == true) {
        $CanReceipt = 'S';
    } else {
        /* Asigna 'N' a $CanReceipt si la condición anterior no se cumple. */

        $CanReceipt = 'N';
    }


    /* asigna 'S' o 'N' según condiciones booleanas. */
    if ($CanDeposit == true) {
        $CanDeposit = 'S';
    } else {
        $CanDeposit = 'N';
    }

    if ($CanActivateRegister == true) {
        $CanActivateRegister = 'S';
    } else {
        /* establece que si no se cumple una condición, la variable recibe 'N'. */

        $CanActivateRegister = 'N';
    }


    /* gestiona consecutivos de usuario y crea un objeto PuntoVenta. */
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


    /* Se asignan propiedades a un objeto Usuario en PHP. */
    $Usuario->nombre = $FirstName;

    $Usuario->estado = 'A';

    $Usuario->fechaUlt = date('Y-m-d H:i:s');

    $Usuario->claveTv = '';


    /* Inicializa propiedades del objeto Usuario con valores específicos para su estado y observaciones. */
    $Usuario->estadoAnt = 'I';

    $Usuario->intentos = 0;

    $Usuario->estadoEsp = 'I';

    $Usuario->observ = '';


    /* Código que inicializa propiedades de un objeto Usuario con valores predeterminados. */
    $Usuario->dirIp = '';

    $Usuario->eliminado = 'N';

    $Usuario->mandante = '0';

    $Usuario->usucreaId = '0';


    /* Se asignan valores a propiedades del objeto Usuario y se genera un token. */
    $Usuario->usumodifId = '0';

    $Usuario->claveCasino = '';
    $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

    $Usuario->tokenItainment = $token_itainment;


    /* Se declaran propiedades de un objeto Usuario, inicialmente vacías. */
    $Usuario->fechaClave = '';

    $Usuario->retirado = '';

    $Usuario->fechaRetiro = '';

    $Usuario->horaRetiro = '';


    /* Asignación de valores a propiedades del objeto Usuario en un sistema administrativo. */
    $Usuario->usuretiroId = '0';

    $Usuario->bloqueoVentas = 'N';

    $Usuario->infoEquipo = '';

    $Usuario->estadoJugador = 'AC';


    /* Código inicializa propiedades de un objeto Usuario con valores predeterminados. */
    $Usuario->tokenCasino = '';

    $Usuario->sponsorId = 0;

    $Usuario->verifCorreo = 'N';

    $Usuario->paisId = '1';


    /* Asigna propiedades del objeto $PuntoVenta al objeto $Usuario y establece otros valores. */
    $Usuario->moneda = $PuntoVenta->moneda;

    $Usuario->idioma = $PuntoVenta->idioma;

    $Usuario->permiteActivareg = $CanActivateRegister;

    $Usuario->test = 'N';


    /* Se configuran propiedades de un objeto Usuario, incluyendo tiempo, cambios y zona horaria. */
    $Usuario->tiempoLimitedeposito = '0';

    $Usuario->tiempoAutoexclusion = '0';

    $Usuario->cambiosAprobacion = 'S';

    $Usuario->timezone = '-5';


    /* Asigna valores a las propiedades de un objeto Usuario en PHP. */
    $Usuario->puntoventaId = $PuntoVenta->usuarioId;

    $Usuario->fechaCrea = date('Y-m-d H:i:s');

    $Usuario->origen = 0;

    $Usuario->fechaActualizacion = $Usuario->fechaCrea;


    /* Se crean instancias de usuario y su configuración, insertando datos en la base. */
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $UsuarioMySqlDAO->insert($Usuario);

    $consecutivo_usuario = $Usuario->usuarioId;


    $UsuarioConfig = new UsuarioConfig();

    /* actualiza configuraciones de usuario y cambia la contraseña en MySQL. */
    $UsuarioConfig->permiteRecarga = $CanDeposit;
    $UsuarioConfig->pinagent = '';
    $UsuarioConfig->reciboCaja = $CanReceipt;
    $UsuarioConfig->mandante = 0;
    $UsuarioConfig->usuarioId = $consecutivo_usuario;


    $UsuarioMySqlDAO->updateClave($Usuario, $Password);


    /* Se crea un objeto DAO para insertar configuración de usuario en MySQL. */
    $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());

    $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

    $UsuarioMySqlDAO->getTransaction()->commit();


}