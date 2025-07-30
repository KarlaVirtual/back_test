<?php

/**
 * Este archivo contiene la implementación de un script para procesar solicitudes relacionadas con usuarios,
 * incluyendo consultas a la base de datos y generación de respuestas en formato JSON.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $params          Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers         Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $dateFrom        Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo          Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $partner         Variable que almacena información sobre un socio o afiliado.
 * @var mixed $paisId          Variable que almacena el identificador único de un país.
 * @var mixed $sql             Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $Usuario         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $transaccion     Variable que almacena datos relacionados con una transacción.
 * @var mixed $count           Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $Usuarios        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuariosData    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $key             Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value           Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array           Variable que almacena una lista o conjunto de datos.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 */

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

$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');
ini_set('memory_limit', '-1');
$headers = getallheaders();


$dateFrom = $params->fromDate;
$dateTo = $params->toDate;
$partner = $params->partner;
$paisId = $params->paisId;


$sql =
    "SELECT count(*) count
FROM usuario u
         JOIN registro r ON u.usuario_id = r.usuario_id
         JOIN usuario_otrainfo uoi ON u.usuario_id = uoi.usuario_id
         JOIN mandante m ON u.mandante = m.mandante
         JOIN pais p on u.pais_id = p.pais_id
         JOIN usuario_mandante um ON u.usuario_id = um.usuario_mandante
         JOIN data_completa2 dc2 ON um.usumandante_id = dc2.usuario_id
WHERE 1 = 1
  AND u.fecha_crea BETWEEN '$dateFrom' AND '$dateTo'
   AND u.pais_id = $paisId
    AND u.mandante = $partner";


$Usuario = new Usuario();
$UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();
$transaccion = $UsuarioMySqlDAO->getTransaction();

$count = $Usuario->execQuery($transaccion, $sql);


$sql =
    "SELECT u.usuario_id                                                AS Player_ID,
       um.usumandante_id                                             AS Casino_ID,
       u.fecha_crea                                                AS Registered_Date,
       u.login                                                     AS Email,
       r.celular                                                   AS 'Mobile Number',
       CASE WHEN up.consentimiento_email = 'S' THEN 1 ELSE 0 END AS IsOptimEmail,
       CASE WHEN up.consentimiento_sms = 'S' THEN 1 ELSE 0 END AS IsOptimSMS,
       CASE WHEN up.consentimiento_push = 'S' THEN 1 ELSE 0 END AS IsOptimPush,
       CASE WHEN u.verif_correo = 'S' THEN 1 ELSE 0 END            AS IsEmailVerified,
       CASE WHEN u.verif_celular = 'S' THEN 1 ELSE 0 END           AS IsMobileVerified,
       CASE WHEN u.verificado = 'S' THEN uoi.fecha_nacim ELSE '' END AS DateOfBirth,
       CASE WHEN u.permite_enviopublicidad = 'S' THEN 1 ELSE 0 END AS IsOptIn,
       CASE WHEN u.contingencia = 'A' THEN 1 ELSE 0 END              AS IsBlocked, 
       CASE WHEN u.test = 'S' THEN 1 ELSE 0 END                    AS IsTest,
       m.descripcion                                               AS CasinoName,
       u.login                                                     AS Alias,
       r.sexo                                                      AS Gender,
       p.pais_nom                                                  AS Country,
       u.moneda                                                    AS Currency,
       r.nombre1                                                   AS 'First Name',
       r.apellido1                                                 AS 'Last Name',
       'Mobile'                                                       AS RegisteredPlatform,
       r.afiliador_id                                              AS 'AffiliateID',
       u.idioma                                                    AS Language,
       CASE
           WHEN r.link_id != 0 THEN 'Link'
           WHEN r.banner_id != 0 THEN 'Banner'
           WHEN r.codpromocional_id != 0 THEN 'Codigo Promocional'
           WHEN r.afiliador_id != 0 THEN 'Afiliador'
           ELSE 'Pagina' END                                        AS ReferralType,
       ROUND(r.creditos + r.creditos_base, 2)                      AS Balance,
       dc2.fecha_ultimo_deposito                                     AS LastDepositDate,
       dc2.fecha_ultimo_retiro                                       AS LastWithdrawalDate,
       dc2.fecha_ultima_apuestadeportivas                            AS LastSportBetDate,
       dc2.fecha_ultima_apuestacasino                                AS LastCasinoBetDate,
       dc2.fecha_ultima_apuestacasinovivo                            AS LastLiveCasinoBetDate,
       dc2.ultimo_inicio_sesion                                      AS LastLoginDate,
       u.fecha_actualizacion                                         AS LastUpdated
FROM usuario u
         JOIN registro r ON u.usuario_id = r.usuario_id
         JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
         JOIN usuario_otrainfo uoi ON u.usuario_id = uoi.usuario_id
         JOIN mandante m ON u.mandante = m.mandante
         JOIN pais p on u.pais_id = p.pais_id
         JOIN usuario_mandante um ON u.usuario_id = um.usuario_mandante
         JOIN data_completa2 dc2 ON um.usumandante_id = dc2.usuario_id

WHERE 1 = 1
    AND u.fecha_crea BETWEEN '$dateFrom' AND '$dateTo' 
    AND u.pais_id = $paisId
    AND u.mandante = $partner";


$Usuario = new Usuario();
$UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();
$transaccion = $UsuarioMySqlDAO->getTransaction();


$Usuarios = $Usuario->execQuery($transaccion, $sql);

$UsuariosData = array();
$Usuarios = json_decode(json_encode($Usuarios), true);


foreach ($Usuarios as $key => $value) {
    $array = array();
    $array["PlayerID"] = $value["u.Player_ID"];
    $array["CasinoID"] = $value["um.Casino_ID"];
    $array["RegisteredDate"] = $value["u.Registered_Date"];
    $array["Email"] = $value["u.Email"];
    $array["MobileNumber"] = intval($value["r.Mobile Number"]);
    $array["IsOptinEmail"] = intval($value[".IsOptimEmail"]);
    $array["IsOptinSMS"] = intval($value[".IsOptimEmail"]);
    $array["IsOptimPush"] = intval($value[".IsOptimPush"]);
    $array["IsEmailVerified"] = intval($value[".IsEmailVerified"]);
    $array["IsMobileVerified"] = intval($value[".IsMobileVerified"]);
    $array["DateOfBirth"] = $value[".DateOfBirth"];
    $array["IsOptIn"] = intval($value[".IsOptIn"]);
    $array["IsBlocked"] = intval($value[".IsBlocked"]);
    $array["IsTest"] = intval($value[".IsTest"]);
    $array["CasinoName"] = $value["m.CasinoName"];
    $array["Alias"] = $value["u.Alias"];
    $array["Gender"] = $value["r.Gender"];
    $array["Country"] = $value["p.Country"];
    $array["Currency"] = $value["u.Currency"];
    $array["FirstName"] = $value["r.First Name"];
    $array["LastName"] = $value["r.Last Name"];
    $array["ReferralType"] = $value[".ReferralType"];
    $array["AffiliateID"] = $value["r.AffiliateID"];
    $array["Language"] = $value["u.Language"];
    $array["RegisteredPlatform"] = $value[".RegisteredPlatform"];
    $array["Balance"] = floatval(round($value[".Balance"], 2));
    $array["LastLoginDate"] = ($value["dc2.LastLoginDate"] == null) ? '' : $value["dc2.LastLoginDate"];
    $array["LastUpdated"] = ($value["u.LastUpdated"] == null) ? '' : $value["u.LastUpdated"];
    $array["LastLiveCasinoBetDate"] = ($value["dc2.LastLiveCasinoBetDate"] == null) ? '' : $value["dc2.LastLiveCasinoBetDate"];


    array_push($UsuariosData, $array);
}

if ($count[0][".count"] != 0) {
    $response = array();
    $response["Error"] = false;
    $response["Mensaje"] = "success";
    $response["TotalCount"] = $count[0][".count"];
    $response["Data"] = $UsuariosData;
} else {
    $response["Error"] = false;
    $response["Mensaje"] = "No hay usuarios en este rango de fechas";
    $response["TotalCount"] = 0;
    $response["Data"] = [];
}




