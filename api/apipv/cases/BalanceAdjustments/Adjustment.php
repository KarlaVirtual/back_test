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
 * BalanceAdjustments/Adjustment
 *
 * Guardar un ajuste
 *
 * @param object $params Objeto que contiene los parámetros necesarios para el ajuste:
 * @param float $Amount Monto del ajuste.
 * @param string $Description Descripción del ajuste.
 * @param int $TypeBalance Tipo de saldo (0 o 1).
 * @param int $UserId ID del usuario.
 * @param string $codeQR Código QR para verificación.
 * @param string $Type Tipo de ajuste.
 *
 * @return array Respuesta de la operación:
 *  - bool $HasError Indica si hubo un error.
 *  - string $AlertType Tipo de alerta.
 *  - string $AlertMessage Mensaje de alerta.
 *  - array $ModelErrors Errores del modelo.
 *  - int $pos Posición de los datos.
 *  - int $total_count Conteo total de datos.
 *  - array $data Datos de la operación.
 *
 * @throws Exception Si se detecta una inconsistencia con el código QR o el mandante del usuario.
 */


/* obtiene valores de parámetros para procesar un pago o transacción. */
$Amount = $params->Amount;
$Description = $params->Description;
$TypeBalance = $params->TypeBalance;
$UserId = $params->UserId;

$codeQR = $params->codeQR;

/* Verifica un código QR y lanza excepciones si hay inconsistencias. */
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


/* verifica si $TypeBalance es 0 o 1; de lo contrario, detiene el proceso. */
$Type = $params->Type;

$seguir = true;

if ($TypeBalance != 0 && $TypeBalance != 1) {
    $seguir = false;
}


/* valida que la descripción no esté vacía y que UserId sea numérico. */
if ($Description == "") {
    $seguir = false;
}

if (!is_numeric($UserId)) {
    $seguir = false;
}


/* Verifica si $Amount no es numérico; si es así, establece $seguir como falso. */
if (!is_numeric($Amount)) {
    $seguir = false;
}
try {


    if ($seguir) {


        /* crea un objeto UsuarioMandante y establece tipo y monto según condiciones. */
        $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

        $tipo = 'E';

        $AmountS = $Amount;
        if ($Amount < 0) {
            $tipo = 'S';
            $AmountS = -$AmountS;
        }


        /* Se verifica el mandante del usuario y se genera una excepción si no coincide. */
        $Usuario = new Usuario($UserId);

        if ($Usuario->mandante != $_SESSION['mandante']) {
            throw new Exception("Inusual Detected", "11");
        }

        $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


        /* configura propiedades de un objeto relacionado con ajustes de saldo de usuario. */
        $SaldoUsuonlineAjuste->setTipoId($tipo);
        $SaldoUsuonlineAjuste->setUsuarioId($UserId);
        $SaldoUsuonlineAjuste->setValor($AmountS);
        $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
        $SaldoUsuonlineAjuste->setUsucreaId($UsuarioMandante->getUsuarioMandante());
        $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());

        /* Se ajusta saldo y se registra IP si el motivo está vacío. */
        $SaldoUsuonlineAjuste->setObserv($Description);
        if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
            $SaldoUsuonlineAjuste->setMotivoId(0);
        }
        $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

        $SaldoUsuonlineAjuste->setDirIp($dir_ip);

        /* configura un objeto de saldo en línea con datos del usuario. */
        $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
        $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);
        if ($Type != '') {
            $SaldoUsuonlineAjuste->setTipo($Type);
        }


        $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO();


        /* Se gestiona una transacción y se ajusta el saldo del usuario. */
        $Transaction = $SaldoUsuonlineAjusteMysql->getTransaction();

        $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


        if ($TypeBalance == 0) {

            $Usuario->credit($Amount, $Transaction);

        } else {
            /* ejecuta una función para acreditar ganancias al usuario en una transacción. */

            $Usuario->creditWin($Amount, $Transaction);

        }


        /* convierte montos negativos en positivos y crea un historial de usuario. */
        if ($Amount < 0) {
            $Amount = -$Amount;
        }


        $UsuarioHistorial = new UsuarioHistorial();

        /* Se asignan valores a un objeto UsuarioHistorial para registrar un movimiento. */
        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento($tipo);
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(15);

        /* Inserta un historial de usuario en la base de datos y confirma la transacción. */
        $UsuarioHistorial->setValor($Amount);
        $UsuarioHistorial->setExternoId($ajusteId);

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


        $Transaction->commit();

    }
} catch (Exception $e) {
    /* Captura excepciones y las vuelve a lanzar sin modificaciones. */

    throw $e;
}