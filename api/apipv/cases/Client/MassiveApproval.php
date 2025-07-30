<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
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
use Backend\integrations\payment\MONNETSERVICES;
use Backend\integrations\payout\GLOBOKASSERVICES;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\Integrations\payout\PAYBROKERSSERVICES;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\integrations\payment\ASTROPAYCARDSERVICES;

/**
 * Client/MassiveApproval
 *
 * Este script permite aprobar o rechazar solicitudes de retiro o recarga de forma masiva.
 *
 * @param object $params Objeto JSON decodificado con las siguientes propiedades:
 * @param string $params ->CSV Archivo CSV codificado en base64 que contiene los IDs de las solicitudes.
 * @param int $params ->Type Tipo de operación (0 para rechazar, 1 para aprobar).
 * @param string $params ->Description Descripción de la operación.
 *
 *
 * @return array $response Respuesta en formato JSON con las siguientes propiedades:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de validación.
 * - Data (array): Datos adicionales de la respuesta.
 */

/* recibe y decodifica datos JSON de una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ClientIdCsv = $params->CSV;
$type = $params->Type;
$Description = $params->Description;


/* decodifica un CSV en base64, reemplaza caracteres y lo convierte en array. */
$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);
$ClientIdCsv = trim($ClientIdCsv, "\xEF\xBB\xBF");
$ClientIdCsv = explode("\n", $ClientIdCsv);

/* verifica si el último elemento de un arreglo está vacío y obtiene la IP. */
if (empty(end($ClientIdCsv))) {
    array_pop($ClientIdCsv);
}

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


/* Valida el encabezado del CSV y define una función para detectar dispositivos. */
if (trim($ClientIdCsv[0]) !== "Id Retiro") {
    throw new Exception("Formato invalido", 202233);
}

array_shift($ClientIdCsv);


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
    try {
        foreach ($ClientIdCsv as $id) {

            /* Crea objetos para manejar cuentas de cobro y usuarios asociados. */
            $CuentaCobro = new CuentaCobro($id);
            $Usuario = new Usuario($CuentaCobro->getUsuarioId());
            $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());
            if ($UsuarioPerfil->getPerfilId() == 'USUONLINE') {

                /* Se obtiene el estado anterior de la cuenta de cobro almacenándolo en una variable. */
                $EstadoAnterior = $CuentaCobro->getEstado();
                if ($CuentaCobro->getEstado() == "P" || $CuentaCobro->getEstado() == "S") {
                    /* Código para crear una transacción y actualizar estado y observaciones de cuenta. */
                    $transaction = new Transaction();

                    $CuentaCobro->setEstado("I");
                    $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
                    $CuentaCobro->setObservacion($Description);

                    if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                        $CuentaCobro->usucambioId = 0;
                    }
                    if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                        $CuentaCobro->usupagoId = 0;
                    }
                    if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                        $CuentaCobro->usurechazaId = 0;
                    }
                    if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
                    }

                    /* Actualiza la fecha de acción y gestiona transacciones para CuentaCobro en MySQL. */
                    if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                        $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
                    }


                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($transaction);
                    $contUpdate=$CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado IN ('P','S') ");
                    if ($contUpdate == 0) {
                        $transaction->rollback();
                        continue;
                    }


                    /* Se crea una auditoría general registrando información del usuario y la solicitud. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION['usuario2']);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp("");
                    $AuditoriaGeneral->setUsuarioaprobarId($_SESSION["usuario2"]);

                    /* registra una auditoría de aprobación de notas de retiro en un sistema. */
                    $AuditoriaGeneral->setUsuarioaprobarIp($ip);
                    $AuditoriaGeneral->setTipo("APROBACION MASIVA NOTA DE RETIRO");
                    $AuditoriaGeneral->setValorAntes($EstadoAnterior);
                    $AuditoriaGeneral->setValorDespues($CuentaCobro->getEstado());
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setUsumodifId($_SESSION["usuario2"]);

                    /* Se configuran atributos de un objeto AuditoriaGeneral con datos específicos. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                    $AuditoriaGeneral->setSoperativo($so);
                    $AuditoriaGeneral->setSversion(0);
                    $AuditoriaGeneral->setImagen("");
                    $AuditoriaGeneral->setObservacion($Description ?: ''); // Asigna un valor por defecto si está vacío

                    /* Inserta datos vacíos en la auditoría utilizando un DAO en MySQL. */
                    $AuditoriaGeneral->setData($CuentaCobro->getCuentaId());
                    $AuditoriaGeneral->setCampo("");


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


                    /* Finaliza una transacción, confirmando todos los cambios realizados en la base de datos. */
                    $transaction->commit();


                } else {
                    /* Rollback de transacción y excepción lanzada si se intenta modificar un retiro procesado. */

                    $transaction->rollback();
                    throw new Exception('No puedes cambiar el estado de un retiro ya procesado.');
                }
            } else {
                /* revierte la transacción y lanza una excepción si no está autenticado. */

                $transaction->rollback();
                throw new Exception('No es Usuario Online');
            }
        }


        /* Inicializa una respuesta sin errores y lista para alertas y datos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Data"] = [];

    } catch (Exception $e) {
        /* Manejo de excepciones que imprime errores y realiza un rollback de la transacción. */

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
} else if ($type == 1) {
    try {
        foreach ($ClientIdCsv as $id) {

            /* Se crean instancias de clases relacionadas a cuentas y usuarios específicos. */
            $CuentaCobro = new CuentaCobro($id);
            $Usuario = new Usuario($CuentaCobro->getUsuarioId());
            $UsuarioPerfil = new UsuarioPerfil($CuentaCobro->getUsuarioId());
            if ($UsuarioPerfil->getPerfilId() == 'PUNTOVENTA') {
                if ($CuentaCobro->getEstado() == "P") {

                    /* actualiza el estado de una cuenta de cobro y registra cambios. */
                    $EstadoAnterior = $CuentaCobro->getEstado();
                    $transaction = new Transaction();
                    $CuentaCobro->setEstado("I");

                    $CuentaCobro->setUsucambioId($_SESSION['usuario2']);
                    $CuentaCobro->setObservacion($Description);

                    /* Actualiza la fecha de acción y maneja la transacción para estado 'P'. */
                    $CuentaCobro->setFechaAccion(date("Y-m-d H:i:s"));
                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($transaction);
                    $contUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND estado='P' ");
                    if ($contUpdate == 0) {
                        $transaction->rollback();
                        continue;
                    }

                    /* actualiza el crédito de afiliación del usuario en la base de datos. */
                    $Usuario->creditosAfiliacion = "creditos_afiliacion + " . $CuentaCobro->getValor();
                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
                    $UsuarioMySqlDAO->update($Usuario);


                    $AuditoriaGeneral = new AuditoriaGeneral();

                    /* Configuración de auditoría asignando IDs de usuario y direcciones IP correspondientes. */
                    $AuditoriaGeneral->setUsuarioId($_SESSION['usuario2']);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($CuentaCobro->getUsuarioId());
                    $AuditoriaGeneral->setUsuariosolicitaIp("");
                    $AuditoriaGeneral->setUsuarioaprobarId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setUsuarioaprobarIp($ip);

                    /* registra una auditoría para la aprobación masiva de notas de retiro. */
                    $AuditoriaGeneral->setTipo("APROBACION MASIVA NOTA DE RETIRO");
                    $AuditoriaGeneral->setValorAntes($EstadoAnterior);
                    $AuditoriaGeneral->setValorDespues($CuentaCobro->getEstado());
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setUsumodifId($_SESSION["usuario2"]);
                    $AuditoriaGeneral->setEstado("A");

                    /* Se configuran atributos de un objeto de auditoría general relacionados con un dispositivo. */
                    $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                    $AuditoriaGeneral->setSoperativo($so);
                    $AuditoriaGeneral->setSversion(0);
                    $AuditoriaGeneral->setImagen("");
                    $AuditoriaGeneral->setObservacion($Description ?: '');
                    $AuditoriaGeneral->setData($CuentaCobro->getCuentaId());

                    /* inserta un registro de auditoría en una base de datos y confirma la transacción. */
                    $AuditoriaGeneral->setCampo("");


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


                    $transaction->commit();


                } else {
                    /* Revierte la transacción y lanza una excepción si se intenta modificar un retiro procesado. */

                    $transaction->rollback();
                    throw new Exception('No puedes cambiar el estado de un retiro ya procesado.');
                }
            } else {
                /* Rollback de transacción y excepción si la condición no se cumple. */

                $transaction->rollback();
                throw new Exception('No es Punto de venta');
            }
        }

        /* Inicializa una respuesta estructurada sin errores y lista para datos y mensajes. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    } catch (Exception) {
        /* Manejo de excepciones que realiza un rollback y devuelve un mensaje de error. */

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