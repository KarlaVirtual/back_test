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
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioOtrainfo;
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
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\sql\Transaction;

/**
 * Actualiza los límites de la cuenta de un usuario.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params ->id ID del cliente.
 * @param float|null $params ->LimitSportbook Límite para apuestas deportivas.
 * @param float|null $params ->LimitVirtuals Límite para juegos virtuales.
 * @param float|null $params ->LimitLiveCasino Límite para casino en vivo.
 * @param float|null $params ->LimitCasino Límite para casino.
 * @param float|null $params ->LimitDeposits Límite para depósitos.
 * @param float|null $params ->LimitWithdrawals Límite para retiros.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ("success" o "error").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si los parámetros son inválidos, si el usuario no tiene permisos o si ocurre un error en la operación.
 */

$ClientId = $params->id;
$Usuario = new Usuario($ClientId);
$UsuarioPerfil = new UsuarioPerfil($ClientId);
$ConfigurationEnvironment = new ConfigurationEnvironment();

/**
 * Verifica permisos de usuario y lanza excepciones si no tiene acceso.
 */
if ($UsuarioPerfil->perfilId == 'USUONLINE') {

    $permission = $ConfigurationEnvironment->checkUserPermission('Account/GetUserPermissions', $_SESSION['win_perfil'], $_SESSION['usuario'], 'customers');

    if (!$permission) throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('PUNTOVENTA', 'CAJERO'))) {

    throw new Exception('Permiso denegado', 100035);

} elseif (in_array($UsuarioPerfil->perfilId, array('CONCESIONARIO', 'CONCESIONARIO2', 'AFILIADOR'))) {

    throw new Exception('Permiso denegado', 100035);

} else {

    throw new Exception('Permiso denegado', 100035);

}

$LimitSportbook = $params->LimitSportbook;//producto_id = 0
$LimitVirtuals = $params->LimitVirtuals;//producto_id = 1
$LimitLiveCasino = $params->LimitLiveCasino;//producto_id = 2
$LimitCasino = $params->LimitCasino;//producto_id = 3
$LimitDeposits = $params->LimitDeposits;//producto_id = 4
$LimitWithdrawals = $params->LimitWithdrawals;//producto_id = 5
try {
    /*Actualiza límites de usuario y responde con éxito o error según el resultado.*/
    if ($LimitCasino != '' && $LimitCasino != null) {
        LimitClient($ClientId, 3, $LimitCasino);
    }
    if ($LimitLiveCasino != '' && $LimitLiveCasino != null) {
        LimitClient($ClientId, 2, $LimitLiveCasino);
    }
    if ($LimitSportbook != '' && $LimitSportbook != null) {
        LimitClient($ClientId, 0, $LimitSportbook);
    }
    if ($LimitVirtuals != '' && $LimitVirtuals != null) {
        LimitClient($ClientId, 1, $LimitVirtuals);
    }
    if ($LimitDeposits != '' && $LimitDeposits != null) {
        LimitClient($ClientId, 4, $LimitDeposits);
    }
    if ($LimitWithdrawals != '' && $LimitWithdrawals != null) {
        LimitClient($ClientId, 5, $LimitWithdrawals);
    }

    //Generación formato de respuesta exitoso
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
} catch (Exception $e) {
    //Generación formato de respuesta con error
    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}

/**
 * Establece el límite de un cliente para un producto específico.
 *
 * @param int $ClientId ID del cliente.
 * @param int $ProductId ID del producto.
 * @param float $Limit Límite a establecer.
 *
 * @throws Exception Si ocurre un error durante la operación.
 */
function LimitClient($ClientId, $ProductId, $Limit)
{
    $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $os = "Desconocido";
    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        $os = 'Mac';
    }
    $device = $_SESSION['sistema'] === 'D' ? 'Desktop' : 'Mobile';
    $transaction = new Transaction();
    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($transaction);
    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($transaction);
    try {
        $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $Clasificador->getClasificadorId(), $ProductId);

        if ($UsuarioConfiguracion->getValor() != $Limit) {
            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($_SESSION['dir_ip']);
            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario']);
            $UsuarioLog->setUsuariosolicitaIp($_SESSION['dir_ip']);
            $UsuarioLog->setUsuarioaprobarId($ClientId);
            $UsuarioLog->setTipo('UPDATELIMITPERCLIENT' . $ProductId);
            $UsuarioLog->setValorAntes($UsuarioConfiguracion->getValor());
            $UsuarioLog->setValorDespues($Limit);
            $UsuarioLog->setUsumodifId($_SESSION['usuario']);
            $UsuarioLog->setEstado('A');
            $UsuarioLog->setDispositivo($device);
            $UsuarioLog->setSoperativo($os);

            $UsuarioConfiguracion->setUsumodifId($_SESSION['usuario']);
            $UsuarioConfiguracion->setValor($Limit);

            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
        }

    } catch (Exception $e) {
        if ($e->getCode() == 46) {
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($ClientId);
            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
            $UsuarioConfiguracion->setValor($Limit);
            $UsuarioConfiguracion->setNota('Limite Usuario');
            $UsuarioConfiguracion->setUsucreaId($_SESSION['usuario']);
            $UsuarioConfiguracion->setUsumodifId($_SESSION['usuario']);
            $UsuarioConfiguracion->setProductoId($ProductId);
            $UsuarioConfiguracion->setEstado('A');

            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($ClientId);
            $UsuarioLog->setUsuarioIp($_SESSION['dir_ip']);
            $UsuarioLog->setUsuariosolicitaId($_SESSION['usuario']);
            $UsuarioLog->setUsuariosolicitaIp($_SESSION['dir_ip']);
            $UsuarioLog->setUsuarioaprobarId($ClientId);
            $UsuarioLog->setTipo('UPDATELIMITPERCLIENT' . $ProductId);
            $UsuarioLog->setValorAntes(0);
            $UsuarioLog->setValorDespues($Limit);
            $UsuarioLog->setUsumodifId($_SESSION['usuario']);
            $UsuarioLog->setEstado('A');
            $UsuarioLog->setDispositivo($device);
            $UsuarioLog->setSoperativo($os);

            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
        }
    }
    $transaction->commit();
}