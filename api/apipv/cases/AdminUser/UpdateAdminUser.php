<?php

use Backend\dto\ApiTransaction;
use Backend\dto\AuditoriaGeneral;
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

/**
 * Actualiza la información de un usuario administrador.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la actualización:
 * @param int $Id ID del usuario.
 * @param array $Partners Lista de socios.
 * @param string $AgentId ID del agente.
 * @param string $Login Nuevo login del usuario.
 * @param string $RegionPerfil Región del perfil del usuario.
 *
 * @return array $response Respuesta de la operación:
 * - bool $HasError: Indica si hubo un error.
 * - string $AlertType: Tipo de alerta.
 * - string $AlertMessage: Mensaje de alerta.
 * - array $ModelErrors: Errores del modelo.
 * - int $pos: Posición de los registros.
 * - int $total_count: Conteo total de registros.
 * - array $data: Datos finales.
 *
 * @throws Exception Si el perfil del usuario no está permitido.
 * @throws Exception Si el perfil del usuario pertenece a ciertos tipos específicos.
 * @throws Exception Si el perfil del usuario no tiene permiso para editar el usuario.
 */


/* Se inicializa un entorno de configuración y se extraen parámetros específicos. */
$ConfigurationEnvironment = new ConfigurationEnvironment();


$Id = $params->Id;
$Partners = $params->Partners;
$AgentId = $params->AgentId;

/* asigna valores de parámetros y obtiene la IP del usuario. */
$login = $params->Login;
$RegionPerfil = $params->RegionPerfil;
$Usuario = new Usuario($Id);
$UsuarioPerfil = new UsuarioPerfil($Id);

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* detecta si el visitante usa un dispositivo móvil según el User-Agent. */
$ip = explode(",", $ip)[0];

function esMovil()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $dispositivosMoviles = array(
        'iPhone', 'iPad', 'Android', 'BlackBerry', 'Windows Phone',
        'Opera Mini', 'Mobile Safari', 'webOS'
    );


    /* verifica si el user agent corresponde a un dispositivo móvil. */
    foreach ($dispositivosMoviles as $dispositivo) {
        if (stripos($userAgent, $dispositivo) !== false) {
            return true;
        }
    }

    return false;
}

if (esMovil()) {
    $dispositivo = 'Mobile';
} else {
    /* asigna "Desktop" a la variable $dispositivo si no se cumple la condición previa. */

    $dispositivo = "Desktop";
}


/* Determina el sistema operativo del usuario a partir del agente de usuario. */
$userAgent = $_SERVER['HTTP_USER_AGENT'];

function getOS($userAgent)
{
    $os = "Desconocido";

    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        /* verifica si el agente de usuario contiene 'Linux' y asigna el sistema operativo. */

        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        /* Detecta si el agente de usuario corresponde a un sistema operativo Mac. */

        $os = 'Mac';
    }

    return $os;
}


/* obtiene el sistema operativo y configura un entorno de configuración. */
$so = getOS($userAgent);


$valorDespues = $login;


$ConfigurationEnvironment = new ConfigurationEnvironment();


/* lanza una excepción si el perfil del usuario no está permitido. */
if ($UsuarioPerfil->perfilId == 'USUONLINE') {

    throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {

    throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'AFILIADOR'))) {
    /* Lanza una excepción si el perfil del usuario pertenece a ciertos tipos específicos. */


    throw new Exception('Permiso denegado', 100035);

} else {
    /* Verificación permiso del solicitante para el perfil actual */


    $permission = $ConfigurationEnvironment->checkUserPermission('AdminUser/UpdateAdminUser', $_SESSION['win_perfil'], $_SESSION['usuario'], 'editAdminUserManagementButton');

    if (!$permission) throw new Exception('Permiso denegado', 100035);

}


/* Obtención perfil requerido */
$Perfil = new Perfil($UsuarioPerfil->perfilId);

if ($Perfil->tipo == "A") {
    $PartnersString = implode(",", $Partners);
    $UsuarioPerfil->mandanteLista = $PartnersString;

    if ($AgentId != "") {
        $UsuarioPerfil->consultaAgente = $AgentId;
    }

    if ($RegionPerfil != "") {
        $UsuarioPerfil->region = $RegionPerfil;
    }

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
    $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);
    $UsuarioPerfilMySqlDAO->getTransaction()->commit();

    $login_antiguo = $Usuario->login;

    if ($Usuario->login != $login) {

        $Usuario->setLogin($login);

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Transaction = $UsuarioMySqlDAO->getTransaction();
        $UsuarioMySqlDAO->update($Usuario);
        $UsuarioMySqlDAO->getTransaction()->commit();


        if ($_SESSION["win_perfil"] == "USUONLINE" || $_SESSION["win_perfil"] == "AFILIADOR") {
            $Registro = new Registro("", $Usuario->usuarioId);
            $Registro->setEmail($login);

            $RegistroMySqlDAO = new RegistroMySqlDAO();
            $RegistroMySqlDAO->update($RegistroMySqlDAO);
            $RegistroMySqlDAO->getTransaction()->commit();
        }

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId);
        $UsuarioMandante->setEmail($login);

        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
        $Transaction = $UsuarioMandanteMySqlDAO->getTransaction();
        $UsuarioMandanteMySqlDAO->update($UsuarioMandante);
        $UsuarioMandanteMySqlDAO->getTransaction()->commit();

        $AuditoriaGeneral = new AuditoriaGeneral();
        $AuditoriaGeneral->setUsuarioId($Id);
        $AuditoriaGeneral->setUsuarioIp($ip);
        $AuditoriaGeneral->setUsuariosolicitaId($Id);
        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
        $AuditoriaGeneral->setUsuarioaprobarId($Id);
        $AuditoriaGeneral->setUsuarioaprobarIp($ip);
        $AuditoriaGeneral->setTipo("CAMBIODELOGIN");
        $AuditoriaGeneral->setValorAntes($login_antiguo);
        $AuditoriaGeneral->setValorDespues($valorDespues);
        $AuditoriaGeneral->setUsucreaId(0);
        $AuditoriaGeneral->setUsumodifId(0);
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setDispositivo($dispositivo);
        $AuditoriaGeneral->setSoperativo($so);
        $AuditoriaGeneral->setSversion(0);
        $AuditoriaGeneral->setImagen(0);
        $AuditoriaGeneral->setObservacion("Cambio de login");
        $AuditoriaGeneral->setData("");
        $AuditoriaGeneral->setCampo(0);


        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

    }

} else {
    /* proporciona información sobre la limitación de datos hasta octubre de 2023. */


    if ($AgentId != "") {
        $UsuarioPerfil->consultaAgente = $AgentId;
    }

    if ($RegionPerfil != "") {
        $UsuarioPerfil->region = $RegionPerfil;
    }

    $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
    $UsuarioPerfilMySqlDAO->update($UsuarioPerfil);
    $UsuarioPerfilMySqlDAO->getTransaction()->commit();

}


/* inicializa una respuesta sin errores, con éxito y sin mensajes. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

