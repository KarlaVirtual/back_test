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
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * AgentList/ChangePassword
 *
 * Actualizar la contraseña de un usuario
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la operación
 * @param int $params ->FromId ID del usuario que solicita el cambio de contraseña
 * @param string $params ->codeQR Código QR para la autenticación de Google
 * @param string $params ->NewPassword Nueva contraseña del usuario
 * @param string $params ->ConfirmPassword Confirmación de la nueva contraseña
 *
 * @return array $response Array con el resultado de la operación
 *  -HasError:bool Indica si hubo un error en la operación
 *  -AlertType:string Tipo de alerta generada
 *  -AlertMessage:string Mensaje de alerta generado
 *  -ModelErrors:array Errores del modelo si los hay
 *
 * @throws Exception Si el código QR está vacío
 * @throws Exception Si el código de autenticación de Google es inválido
 * @throws Exception Si el usuario no tiene permisos para realizar la operación
 */


/* verifica si el código QR está vacío y lanza una excepción si es cierto. */
$FromId = $params->FromId;

$codeQR = $params->codeQR;

if ($codeQR == '') {
    throw new Exception("Inusual Detected", "110012");
} else {
    /* Verifica un código de autenticación de Google y lanza excepción si es inválido. */

    $UsuarioMandante2 = new UsuarioMandante($_SESSION['usuario2']);
    $Usuario2 = new Usuario($UsuarioMandante2->usuarioMandante);
    $Google = new GoogleAuthenticator();
    $returnCodeGoogle = $Google->verifyCode($Usuario2->saltGoogle, $codeQR);
    if (!$returnCodeGoogle) {
        throw new Exception("Inusual Detected", "11");
    }
}


/* Declaración clase con configuración del ambiente de configuración */
$ConfigurationEnvironment = new ConfigurationEnvironment();


if ($FromId != "" && is_numeric($FromId)) {

    $Usuario = new Usuario($FromId);

    $UsuarioPerfil = new UsuarioPerfil($FromId);

    if ($UsuarioPerfil->perfilId == 'USUONLINE') {
        //Verifica disponibilidad del permiso para usuarios online

        $permission = $ConfigurationEnvironment->checkUserPermission('AgentList/ChangePassword', $_SESSION['win_perfil'], $_SESSION['usuario'], 'customersConfigurationSecurityChangesPassword');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {
        //Verifica disponibilidad del permiso para puntos de venta y cajeros

        $permission = $ConfigurationEnvironment->checkUserPermission('AgentList/ChangePassword', $_SESSION['win_perfil'], $_SESSION['usuario'], 'betShopManagementConfigurationChangePasswordMenu');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'CONCESIONARIO3', 'AFILIADOR'))) {
        //Verifica disponibilidad del servicio para consecionarios y afiliadores

        $permission = $ConfigurationEnvironment->checkUserPermission('AgentList/ChangePassword', $_SESSION['win_perfil'], $_SESSION['usuario'], 'AgentConfigurationChangePasswordMenu');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    } else {

        $permission = $ConfigurationEnvironment->checkUserPermission('AgentList/ChangePassword', $_SESSION['win_perfil'], $_SESSION['usuario'], 'AdminuserConfigurationChangePasswordMenu');

        if (!$permission) throw new Exception('Permiso denegado', 100035);

    }


    $NewPassword = $params->NewPassword;
    $ConfirmPassword = $params->ConfirmPassword;

    // Verifica si la nueva contraseña es igual a la contraseña de confirmación
    if ($NewPassword == $ConfirmPassword) {

        // Crea una instancia del DAO de usuario para registros MySQL
        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
        $Transaction = $UsuarioLogMySqlDAO->getTransaction();

        $Usuario->changeClave($NewPassword);

        // Crea una nueva instancia de registro de usuario
        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');

        // Establece el ID del usuario que solicita el cambio
        $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario2']);
        $UsuarioLog->setUsuariosolicitaIp($ip);

        // Establece el tipo de acción de registro
        $UsuarioLog->setTipo("CAMBIOCLAVE");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes("");
        $UsuarioLog->setValorDespues("");
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        // Inserta el registro de usuario en la base de datos
        $UsuarioLogMySqlDAO->insert($UsuarioLog);
        $Transaction->commit();

        // Prepara la respuesta para indicar que no hubo errores
        $response["HasError"] = false;
        $response["AlertType"] = "Success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    } else {
        // Prepara la respuesta para indicar que hubo un error
        $response["HasError"] = true;
        $response["AlertType"] = "Error";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

    }

} else {
    /* Generación formato de respuesta*/

    $response["HasError"] = true;
    $response["AlertType"] = "Error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}