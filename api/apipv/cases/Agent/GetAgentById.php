<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\AuditoriaGeneral;
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
 * Obtener detalles del agente por ID.
 *
 * @param string $params Datos JSON codificados que contienen la información de entrada.
 * @param string $id ID del agente.
 *
 * @return array
 * - HasError: boolean Indica si hubo un error.
 * - AlertType: string Tipo de alerta.
 * - AlertMessage: string Mensaje de alerta.
 * - ModelErrors: array Errores de modelo.
 * - Data: array Datos de la cuenta del agente.
 * - data: array Datos de la cuenta del agente (duplicado).
 *
 * @throws Exception Si no hay saldo para transferir.
 */


/* recibe datos JSON, los decodifica y asigna un ID a un objeto Usuario. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$id = $_GET["id"];

$Usuario = new Usuario();

$Usuario->usuarioId = $id;

/* verifica una sesión y asigna un valor antes de obtener detalles del administrador. */
if ($_SESSION['Global'] == "N") {

    $Usuario->mandante = $_SESSION["mandante"];
}


/*obteniendo Id de la sesion del usuario activa */
$Id = $_SESSION["usuario"];
$perfil = $_SESSION["win_perfil"];


/*Se crea una nueva instancia de la clase Usuario utilizando el ID proporcionado*/

try {
    $user = new Usuario($id);
    $Country = $user->paisId; //obteniendo pais del usuario proporcionado
    $Mandante = $user->mandante; //obteniendo partner al que pertenece el usuario proporcionado

} catch (Exception $e) {

}


/*Realizando validacion para verificar si el partner del usuario solicitado es igual al partner del usuario con la seccion activa      */
if ($_SESSION['mandante'] != $Mandante and $_SESSION['pais_id'] != $Country) {

    /* Generando formato de respuesta para cuando no se cumple las condiciones */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "No se encontraron resultados para el ID ingresado";
    $response["ModelErrors"] = [];

    return;
}


/*Realizando validacion del perfil del usuario con sesion activa */

if ($perfil == "CONCESIONARIO" || $perfil == "CONCESIONARIO2") {

    // Inicializa la variable $permitir como false
    $permitir = false;

    $UsuarioPerfil = new UsuarioPerfil($id);
    $Concesionario = new Concesionario($id, '0');

    if ($perfil == "CONCESIONARIO") {
        if ($Concesionario->usupadreId == $Id) {
            $permitir = true;
        }
    } else if ($perfil == "CONCESIONARIO2") {
        if ($Concesionario->usupadre2Id == $Id) {
            $permitir = true;
        }
    }

    /*validando si es posible continuar con la busqueda del usuario solicitado */
    if ($permitir == false) {
        /*Generando respuesta en caso de no tener el permiso para ver el usuario ingresado */
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "No se encontraron resultados para el ID ingresado";
        $response["ModelErrors"] = [];

        return;
    }

}


/*definiendo direccion ip de la sesion activa*/
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


/**
 * Registrando la búsqueda de usuario en la auditoría general.
 */
$AuditoriaGeneral = new AuditoriaGeneral();


/* Estableciendo los detalles de la auditoría*/


$AuditoriaGeneral->setUsuarioId($id);
$AuditoriaGeneral->setUsuarioIp($ip);
$AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
$AuditoriaGeneral->setUsuariosolicitaIp($ip);
$AuditoriaGeneral->setUsuarioaprobarIp(0);
$AuditoriaGeneral->setTipo("BUSQUEDADEUSUARIO");
$AuditoriaGeneral->setValorAntes("");
$AuditoriaGeneral->setValorDespues("");
$AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
$AuditoriaGeneral->setUsumodifId(0);
$AuditoriaGeneral->setEstado("A");
$AuditoriaGeneral->setDispositivo(0);
$AuditoriaGeneral->setObservacion('GetAgentById');


/* Insertando el registro de auditoría en la base de datos y confirmando la transacción*/

$AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
$AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
$AuditoriaGeneralMySqlDAO->getTransaction()->commit();


$Agent = $Usuario->getAdminDetails();


//$usuarios = json_decode($usuarios);


/* Se inicializan dos arrays, uno vacío y otro con un ID de usuario. */
$usuariosFinal = [];


$array = [];

$array["Id"] = $Agent["a.usuario_id"];

/* Asigna valores del agente a un array con información del usuario. */
$array["UserName"] = $Agent["a.login"];
$array["SystemName"] = 1;
$array["IsSuspended"] = ($Agent["a.estado"] == 'A' ? false : true);
$array["FirstName"] = $Agent["a.nombre"];
$array["Name"] = $Agent["a.nombre"];
$array["LastName"] = "T";

/* asigna valores de un agente a un array asociativo en PHP. */
$array["Phone"] = '';
$array["LastLoginLocalDate"] = $Agent["a.fecha_ult"];
$array["LastLoginIp"] = $Agent["a.dir_ip"];
$array["CurrencyId"] = $Agent["a.moneda"];
$array["Currency"] = $Agent["a.moneda"];
$array["Address"] = $Agent["e.direccion"];

/* asigna valores de un agente a un array asociativo. */
$array["Email"] = $Agent["e.email"];
$array["AgentAmount"] = $Agent["e.creditos_base"];
$array["AgentAmount2"] = $Agent["e.cupo_recarga"];
$array["State"] = $Agent["a.estado"];

$array['UserId'] = $Agent["a.usuario_id"];


/* Asignación de usuarios y configuración de respuesta sin errores ni mensajes de alerta. */
$usuariosFinal = $array;


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* inicializa un array para errores y asigna datos de usuarios a dos claves. */
$response["ModelErrors"] = [];

$response["Data"] = $usuariosFinal;
$response["data"] = $usuariosFinal;
