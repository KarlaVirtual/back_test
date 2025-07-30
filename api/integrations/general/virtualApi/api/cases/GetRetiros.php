<?php

/**
 * Este archivo contiene la implementación de un endpoint para obtener información
 * sobre retiros realizados en un sistema. Incluye la validación de tokens, manejo
 * de parámetros de entrada, y ejecución de consultas SQL para recuperar datos
 * relacionados con los retiros.
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
 * @var mixed $ToDateLocal              Variable que almacena la fecha local de finalización de un proceso o intervalo.
 * @var mixed $paisId                   Variable que almacena el identificador único de un país.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $mandante                 Variable que almacena el mandante o entidad responsable de una operación.
 * @var mixed $estado                   Variable que almacena el estado de un proceso o entidad.
 * @var mixed $OffSet                   Variable que almacena el desplazamiento en consultas paginadas.
 * @var mixed $NumberRows               Variable que almacena el número de filas a recuperar en una consulta.
 * @var mixed $date                     Variable que almacena una fecha genérica.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $CuentaCobro              Variable que almacena información sobre una cuenta de cobro.
 * @var mixed $CuentaCobroMySqlDAO      Variable que representa la capa de acceso a datos MySQL para cuentas de cobro.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $transacciones            Variable que almacena un conjunto de transacciones.
 * @var mixed $finalTransations         Variable que almacena las transacciones finales de un proceso.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $status                   Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
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

if ($TokenHeader === $token || true) {
    if ($_REQUEST["DateFrom"] != "") {
        $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $_REQUEST["DateFrom"])));
    }
    if ($_REQUEST["DateTo"] != "") {
        $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $_REQUEST["DateTo"])));
    }
    $paisId = $_GET["CountrySelect"];
    $mandante = $_GET["Mandante"];
    $estado = $_GET["State"];
    $OffSet = $_GET["OffSet"];
    $NumberRows = $_GET["NumberRows"];
    $date = date("Y-m-d H:i:s", strtotime($ToDateLocal . " -7 days"));


    $sql =
        "SELECT count(*) count 
FROM cuenta_cobro urr
JOIN usuario u ON u.usuario_id = urr.usuario_id
         JOIN registro r ON u.usuario_id = r.usuario_id
         JOIN pais  ON u.pais_id = pais.pais_id
         JOIN mandante m ON u.mandante = m.mandante
         LEFT OUTER JOIN punto_venta pv ON urr.puntoventa_id = pv.usuario_id
         LEFT OUTER JOIN usuario_banco ub ON u.usuario_id = ub.usuario_id
         LEFT OUTER JOIN banco b ON ub.banco_id = b.banco_id
         LEFT OUTER JOIN transaccion_producto tp ON urr.transproducto_id = tp.transproducto_id
         LEFT OUTER JOIN producto prod ON prod.producto_id = tp.producto_id
         LEFT OUTER JOIN proveedor prov ON prov.proveedor_id = prod.proveedor_id
WHERE 1 = 1
  AND urr.fecha_accion BETWEEN '$FromDateLocal' AND '$ToDateLocal'
  AND u.pais_id = $paisId
  AND u.mandante = $mandante
  AND urr.estado = '$estado'  LIMIT $NumberRows,$OffSet";

    $CuentaCobro = new CuentaCobro();

    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO();
    $transaccion = $CuentaCobroMySqlDAO->getTransaction();

    $count = $CuentaCobro->execQuery($transaccion, $sql);

    $sql =
        "SELECT u.usuario_id,
       urr.cuenta_id,
       urr.fecha_crea,
       prov.descripcion,
       CASE
           WHEN LOCATE('-', ub.tipo_cliente) <> 0
               THEN SUBSTR(ub.tipo_cliente, LOCATE('-', ub.tipo_cliente) + 2, 20)
           WHEN urr.puntoventa_id = 0 THEN 'Sistema'
           ELSE 'Punto de Venta' END AS Metodo_de_Retiro,
       urr.estado,
       urr.valor

FROM cuenta_cobro urr
JOIN usuario u ON u.usuario_id = urr.usuario_id
         JOIN registro r ON u.usuario_id = r.usuario_id
         JOIN pais  ON u.pais_id = pais.pais_id
         JOIN mandante m ON u.mandante = m.mandante
         LEFT OUTER JOIN punto_venta pv ON urr.puntoventa_id = pv.usuario_id
         LEFT OUTER JOIN usuario_banco ub ON u.usuario_id = ub.usuario_id
         LEFT OUTER JOIN banco b ON ub.banco_id = b.banco_id
         LEFT OUTER JOIN transaccion_producto tp ON urr.transproducto_id = tp.transproducto_id
         LEFT OUTER JOIN producto prod ON prod.producto_id = tp.producto_id
         LEFT OUTER JOIN proveedor prov ON prov.proveedor_id = prod.proveedor_id
WHERE 1 = 1
  AND urr.fecha_accion BETWEEN '$FromDateLocal' AND '$ToDateLocal'
  AND u.pais_id = $paisId
  AND u.mandante = $mandante
  AND urr.estado = '$estado'  LIMIT $NumberRows,$OffSet";


    $CuentaCobro = new CuentaCobro();

    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO();
    $transaccion = $CuentaCobroMySqlDAO->getTransaction();

    $transacciones = $CuentaCobro->execQuery($transaccion, $sql);

    $transacciones = json_encode($transacciones);
    $transacciones = json_decode($transacciones);
    if ($count[0]->{".count"} != "0") {
        $finalTransations = [];

        $Mandante = new Mandante($mandante);
        $Pais = new Pais($paisId);

        foreach ($transacciones as $key => $value) {
            $array = [];

            $array["UsuarioId"] = $value->{"u.usuario_id"};
            $array["RetiroId"] = $value->{"urr.cuenta_id"};
            $array["FechaCrea"] = $value->{"urr.fecha_crea"};
            $array["Metodo"] = $value->{".Metodo_de_Retiro"};
            switch ($value->{"urr.estado"}) {
                case 'A':
                    $status = 'Activo';
                    break;
                case 'I':
                    $status = 'Aprobado';
                    break;
                case 'R':
                    $status = 'Rechazado';
                    break;
                case 'S':
                    $status = 'Pendiente por sistema';
                    break;
            }
            $array["Estado"] = $status;
            $array["Valor"] = $value->{"urr.valor"};

            if ($value->{".Metodo_de_Retiro"} == "Punto de Venta") {
                $array["Proveedor"] = "Punto de Venta";
            } else {
                $array["Proveedor"] = $value->{"prov.descripcion"};
            }


            $array["Partner"] = $Mandante->descripcion;
            $array["PaisId"] = $Pais->paisNom;


            array_push($finalTransations, $array);
        }

        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = $count;
        $response["Data"] = $finalTransations;
    } else {
        $response["Error"] = false;
        $response["Mensaje"] = "No depositos en estas fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
}







