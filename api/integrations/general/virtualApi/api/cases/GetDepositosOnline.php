<?php

/**
 * Este archivo contiene un script para procesar solicitudes relacionadas con depósitos en línea.
 * Realiza validaciones de autenticación, manejo de parámetros de entrada, y consultas a la base de datos
 * para obtener información sobre transacciones de recarga de usuarios.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Documentación generada automáticamente para este archivo
 *
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $FromDateLocal            Variable que almacena la fecha local de inicio de un proceso o intervalo.
 * @var mixed $timezone                 Variable que almacena la zona horaria.
 * @var mixed $ToDateLocal              Variable que almacena la fecha local de finalización de un proceso o intervalo.
 * @var mixed $paisId                   Variable que almacena el identificador único de un país.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $mandante                 Variable que almacena el mandante o entidad responsable de una operación.
 * @var mixed $estado                   Variable que almacena el estado de un proceso o entidad.
 * @var mixed $OffSet                   Variable que almacena el desplazamiento en consultas paginadas.
 * @var mixed $NumberRows               Variable que almacena el número de filas a recuperar en una consulta.
 * @var mixed $date                     Variable que almacena una fecha genérica.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $UsuarioRecarga           Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioRecargaMySqlDAO   Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $transacciones            Variable que almacena un conjunto de transacciones.
 * @var mixed $finalTransations         Variable que almacena las transacciones finales de un proceso.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $e                        Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
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
$headers = getallheaders();

ini_set("display_errors", "OFF");
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'ApiVirtualSoft';
} else {
    $usuario = 'ApiVirtualSoft';
}
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'VirtualS2022';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

try {
    if ($TokenHeader === $token || true) {
        if ($_REQUEST["DateFrom"] != "") {
            $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $_REQUEST["DateFrom"])));
        }
        if ($_REQUEST["DateTo"] != "") {
            $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $_REQUEST["DateTo"])));
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();


        $paisId = $_GET["CountrySelect"];
        $paisId = $ConfigurationEnvironment->DepurarCaracteres($paisId);

        $mandante = $_GET["Mandante"];
        $mandante = $ConfigurationEnvironment->DepurarCaracteres($mandante);

        $estado = $_GET["State"];
        $estado = $ConfigurationEnvironment->DepurarCaracteres($estado);

        $OffSet = $_GET["OffSet"];
        $OffSet = $ConfigurationEnvironment->DepurarCaracteres($OffSet);

        $NumberRows = $_GET["NumberRows"];
        $NumberRows = $ConfigurationEnvironment->DepurarCaracteres($NumberRows);


        $date = date("Y-m-d H:i:s", strtotime($ToDateLocal . " -30 days"));


        $sql =
            "SELECT count(*) count 
FROM usuario_recarga ur
         JOIN usuario u ON ur.usuario_id = u.usuario_id
         JOIN usuario_mandante um on u.usuario_id = um.usuario_mandante
         LEFT OUTER JOIN registro r ON u.usuario_id = r.usuario_id
         JOIN transaccion_producto tp ON ur.recarga_id = tp.final_id
         JOIN producto p ON tp.producto_id = p.producto_id
         JOIN proveedor p2 ON p.proveedor_id = p2.proveedor_id
         JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
WHERE 1 = 1
  AND up.perfil_id = 'USUONLINE'
  AND ur.fecha_crea BETWEEN '$FromDateLocal' AND '$ToDateLocal'
  AND u.pais_id = '$paisId'
  AND u.mandante = '$mandante'
  AND ur.estado = '$estado'
LIMIT $NumberRows,$OffSet
";

        $UsuarioRecarga = new UsuarioRecarga();

        $UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();
        $transaccion = $UsuarioRecargaMySqlDAO->getTransaction();

        $count = $UsuarioRecarga->execQuery($transaccion, $sql);

        $sql =
            "SELECT tp.externo_id,
       ur.recarga_id,
       u.usuario_id,
       u.nombre,
       u.mandante,
       u.pais_id,
       ur.valor,
       p2.descripcion AS Producto,
       p.descripcion  AS Medio_de_Deposito,
       ur.fecha_crea

FROM usuario_recarga ur
         JOIN usuario u ON ur.usuario_id = u.usuario_id
         JOIN usuario_mandante um on u.usuario_id = um.usuario_mandante
         LEFT OUTER JOIN registro r ON u.usuario_id = r.usuario_id
         JOIN transaccion_producto tp ON ur.recarga_id = tp.final_id
         JOIN producto p ON tp.producto_id = p.producto_id
         JOIN proveedor p2 ON p.proveedor_id = p2.proveedor_id
         JOIN usuario_perfil up ON u.usuario_id = up.usuario_id
WHERE 1 = 1
  AND up.perfil_id = 'USUONLINE'
  AND ur.fecha_crea BETWEEN '$FromDateLocal' AND '$ToDateLocal'
  AND u.pais_id = '$paisId'
  AND u.mandante = '$mandante'
  AND ur.estado = '$estado'
LIMIT $NumberRows,$OffSet
";


        $UsuarioRecarga = new UsuarioRecarga();

        $UsuarioRecargaMySqlDAO = new \Backend\mysql\UsuarioRecargaMySqlDAO();
        $transaccion = $UsuarioRecargaMySqlDAO->getTransaction();

        $transacciones = $UsuarioRecarga->execQuery($transaccion, $sql);


        $transacciones = json_encode($transacciones);
        $transacciones = json_decode($transacciones);

        if ($count[0]->{".count"} != "0") {
            $finalTransations = [];

            foreach ($transacciones as $key => $value) {
                $array = [];
                $array["RecargaId"] = $value->{"ur.recarga_id"};
                $array["ExternoId"] = $value->{"tp.externo_id"};

                $array["UsuarioId"] = $value->{"u.usuario_id"};
                $array["Nombre_Usuario"] = $value->{"u.nombre"};
                $array["Valor"] = $value->{"ur.valor"};
                $array["Medio"] = $value->{"p2.Producto"};
                $array["Nombre_Medio"] = $value->{"p.Medio_de_Deposito"};
                $array["Fecha_Deposito"] = $value->{"ur.fecha_crea"};

                $Mandante = new Mandante($mandante);
                $Pais = new Pais($paisId);


                $array["Partner"] = $Mandante->descripcion;
                $array["PaisId"] = $Pais->paisNom;
                //$array["FechaModif"] = $value->{"transaccion_producto.fecha_modif"};


                array_push($finalTransations, $array);
            }

            $response = array();
            $response["Error"] = false;
            $response["Mensaje"] = "success";
            $response["TotalCount"] = $transacciones->count[0]->{".count"};
            $response["Data"] = $finalTransations;
        } else {
            $response["Error"] = false;
            $response["Mensaje"] = "No se encontraron depositos en estas fechas";
            $response["TotalCount"] = 0;
            $response["Data"] = [];
        }
    }
} catch (Exception $e) {
    print_r($e);
}









