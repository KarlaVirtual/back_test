<?php


use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\Banco;
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
use Backend\sql\Transaction;
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
use Backend\integrations\payment\MONNETSERVICES;
use Backend\integrations\payout\GLOBOKASSERVICES;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\Integrations\payout\PAYBROKERSSERVICES;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\integrations\payment\ASTROPAYCARDSERVICES;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Client/MassiveCancelClientRequests
 *
 * Este script permite rechazar de forma masiva solicitudes de retiro o recarga.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params ->CSV Cadena codificada en base64 con los IDs de las solicitudes.
 * @param int $params ->Type Tipo de operación (0 para usuarios online, 1 para puntos de venta).
 * @param string $params ->Description Descripción del rechazo.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos adicionales.
 *
 * @throws Exception Si el estado de una solicitud ya fue procesado.
 * @throws Exception Si el usuario no pertenece al perfil esperado.
 */

/* obtiene y decodifica datos JSON de una entrada en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ClientIdCsv = $params->CSV;
$type = $params->Type;
$Description = $params->Description;


/* decodifica un CSV en base64 y lo formatea adecuadamente. */
$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);
$ClientIdCsv = trim($ClientIdCsv, "\xEF\xBB\xBF");
$ClientIdCsv = explode("\n", $ClientIdCsv);

/* verifica y elimina el último elemento de un array si está vacío. */
if (empty(end($ClientIdCsv))) {
    array_pop($ClientIdCsv);
}

/**
 * Detecta si un usuario está utilizando un dispositivo móvil mediante el User-Agent.
 *
 * @param string $tipoDispositivo Variable que será modificada para indicar el tipo de dispositivo ('movil' o 'pc').
 */
function detectarDispositivo(&$tipoDispositivo)
{
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

    $patronMovil = '/android|ipad|iphone|ipod|blackberry|opera mini|iemobile|mobile|webos|palm|symbian/i';

    if (preg_match($patronMovil, $userAgent)) {
        $tipoDispositivo = 'movil';
    } else {
        $tipoDispositivo = 'pc';
    }
}

/* La función determina si el dispositivo del usuario es móvil o no. */
$tipoDispositivo = '';

detectarDispositivo($tipoDispositivo);

function esMovil()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];


    /* Verifica si el user agent corresponde a un dispositivo móvil específico. */
    $dispositivosMoviles = array(
        'iPhone', 'iPad', 'Android', 'BlackBerry', 'Windows Phone',
        'Opera Mini', 'Mobile Safari', 'webOS'
    );

    foreach ($dispositivosMoviles as $dispositivo) {
        if (stripos($userAgent, $dispositivo) !== false) {
            return true;
        }
    }

    return false;
}


/* determina si el dispositivo es móvil o de escritorio basado en el user agent. */
if (esMovil()) {
    $dispositivo = 'Mobile';
} else {
    $dispositivo = "Desktop";
}


$userAgent = $_SERVER['HTTP_USER_AGENT'];

/* La función identifica el sistema operativo basado en el User-Agent proporcionado. */
function getOS($userAgent)
{
    $os = "Desconocido";

    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        /* Detecta si el User Agent incluye 'Linux' para asignar el sistema operativo correspondiente. */

        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        /* Detecta si el agente de usuario corresponde a un sistema operativo Mac. */

        $os = 'Mac';
    }

    return $os;
}


/* La función obtiene el sistema operativo del usuario a partir del agente de usuario. */
$so = getOS($userAgent);


if ($type == 0) {

    /* Se crea una nueva instancia de la clase Transaction. */
    $transaction = new Transaction();
    try {
        foreach ($ClientIdCsv as $id) {

            /* Se crean instancias de CuentaCobro, Usuario y UsuarioPerfil usando identificadores específicos. */
            $CuentaCobro = new CuentaCobro($id);
            $Usuario = new Usuario($CuentaCobro->getUsuarioId());
            $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());
            if ($UsuarioPerfil->getPerfilId() == 'USUONLINE') {
                if ($CuentaCobro->getEstado() != 'R' && $CuentaCobro->getEstado() != "E") {
                    $beforeState = $CuentaCobro->getEstado();

                    /* Modifica el estado de CuentaCobro basado en su estado actual y asigna un usuario. */
                    if ($CuentaCobro->getEstado() == "I") {
                        $CuentaCobro->setEstado('D');
                    } else {
                        $CuentaCobro->setEstado('R');
                    }
                    $CuentaCobro->setUsurechazaId($_SESSION['usuario2']);

                    /* Actualiza la cuenta de cobro y acredita una ganancia al usuario correspondiente. */
                    $CuentaCobro->setObservacion($Description);
                    $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($transaction);
                    $CuentaCobroMySqlDAO->update($CuentaCobro);

                    $Usuario->creditWin($CuentaCobro->getValor(), $transaction);

                    /* Se crea un nuevo historial de usuario con ID y movimiento específicos. */
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setUsucreaId(0);

                    /* Se registra un historial de usuario vinculando cuentas y valores en MySQL. */
                    $UsuarioHistorial->setTipo(40);
                    $UsuarioHistorial->setValor($CuentaCobro->getValor());
                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    // ** Auditoría: registrar en la tabla `auditoria_general` **
                    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                    $ip = explode(",", $ip)[0];

                    /* Se crea una auditoría general registrando información del usuario y la solicitud. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION['usuario2']);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION['usuario2']);
                    $AuditoriaGeneral->setUsuariosolicitaIp("");
                    $AuditoriaGeneral->setUsuarioaprobarId($_SESSION['usuario2']);

                    /* registra una auditoría de aprobación de notas de retiro en un sistema. */
                    $AuditoriaGeneral->setUsuarioaprobarIp($ip);
                    $AuditoriaGeneral->setTipo("RECHAZO MASIVO NOTA DE RETIRO");
                    $AuditoriaGeneral->setValorAntes($beforeState);
                    $AuditoriaGeneral->setValorDespues($CuentaCobro->getEstado());
                    $AuditoriaGeneral->setUsucreaId($_SESSION['usuario2']);
                    $AuditoriaGeneral->setUsumodifId($_SESSION['usuario2']);

                    /* Se configuran atributos de un objeto AuditoriaGeneral con datos específicos. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                    $AuditoriaGeneral->setSoperativo($so);
                    $AuditoriaGeneral->setSversion(0);
                    $AuditoriaGeneral->setImagen("");
                    $AuditoriaGeneral->setObservacion($Description ?: ''); // Asigna un valor por defecto si está vacío

                    /* Inserta datos vacíos en la auditoría utilizando un DAO en MySQL. */
                    $AuditoriaGeneral->setData($CuentaCobro->getCuentaId());
                    $AuditoriaGeneral->setCampo('');

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

                } else {
                    /* Lanza una excepción si se intenta modificar un retiro ya procesado. */

                    throw new Exception('No puedes cambiar el estado de un retiro ya procesado.');
                }
            } else {
                /* Lanza una excepción si el usuario no está en línea. */

                throw new Exception('No es Usuario Online');
            }
        }

        /* Finaliza la transacción, guardando todos los cambios realizados en la base de datos. */
        $transaction->commit();

        /* Inicializa una respuesta estructurada sin errores y lista para datos y mensajes. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    } catch (Exception $e) {
        /* Manejo de excepciones: muestra errores en debug y realiza un rollback si es necesario. */

        if ($_ENV['debug']) {
            print_r($e);
        }
        if ($_ENV["debugFixed2"] == '1') {
            print_r($e);
        }
        if ($transaction->getConnection()->isBeginTransaction == 2) {
            $transaction->rollback();
        }
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = $e->getMessage();
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    }
}
if ($type == 1) {
    // Crear una nueva instancia de la clase Transaction

    /* Crea una nueva instancia de la clase Transaction para gestionar transacciones. */
    $transaction = new Transaction();
    try {
        foreach ($ClientIdCsv as $id) {

            /* Actualiza el estado de CuentaCobro y créditos del usuario dependiendo de condiciones específicas. */
            $CuentaCobro = new CuentaCobro($id);
            $Usuario = new Usuario($CuentaCobro->getUsuarioId());
            $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());

            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                if ($CuentaCobro->getEstado() != 'R' && $CuentaCobro->getEstado() != "E" && $CuentaCobro->getEstado() != "I") {
                    $CuentaCobro->setEstado('R');
                    $CuentaCobro->setUsurechazaId($_SESSION['usuario2']);
                    $CuentaCobro->setObservacion($Description);
                    $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($transaction);
                    $CuentaCobroMySqlDAO->update($CuentaCobro);

                    $Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
                    $UsuarioMySqlDAO->update($Usuario);

                } else {
                    throw new Exception('No puedes cambiar el estado de un retiro ya procesado.');
                }
            } else {
                /* lanza una excepción si la condición previa no se cumple. */

                throw new Exception('No es Punto de venta');
            }
        }

        /* Confirma transacción y establece respuesta sin errores, con alerta de éxito. */
        $transaction->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];

        /* Inicializa un array vacío llamado "Data" en la variable $response. */
        $response["Data"] = [];
    } catch (Exception $e) {
        /* Manejo de excepciones con rollback y respuesta personalizada en caso de error. */
        if ($_ENV['debug']) {
            print_r($e);
        }
        if ($_ENV["debugFixed2"] == '1') {
            print_r($e);
        }
        if ($transaction->getConnection()->isBeginTransaction == 2) {
            $transaction->rollback();
        }
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'Error al procesar';
        $response["ModelErrors"] = [];
        $response["Data"] = [];

    }
}