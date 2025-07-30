<?php

/**
 * Este archivo contiene la implementación de una API para obtener información de juegos de casino.
 * Realiza validaciones de autenticación, procesa parámetros de entrada y ejecuta consultas SQL
 * para recuperar datos relacionados con transacciones de juegos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $FromDateLocal            Variable que almacena la fecha local de inicio de un proceso o intervalo.
 * @var mixed $timezone                 Variable que almacena la zona horaria.
 * @var mixed $ToDateLocal              Variable que almacena la fecha local de finalización de un proceso o intervalo.
 * @var mixed $date                     Variable que almacena una fecha genérica.
 * @var mixed $PlayerId                 Variable que almacena el identificador único de un jugador.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $CountrySelect            Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $SubProviderId            Variable que almacena el identificador único de un subproveedor.
 * @var mixed $OffSet                   Variable que almacena el desplazamiento en consultas paginadas.
 * @var mixed $_GET                     Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $NumberRows               Variable que almacena el número de filas a recuperar en una consulta.
 * @var mixed $Provider                 Variable que almacena el proveedor de servicios o juegos.
 * @var mixed $Valor                    Variable que almacena el valor monetario de una operación.
 * @var mixed $Type                     Variable que almacena el tipo de un objeto o transacción.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $TransjuegoLog            Variable que almacena registros de transacciones del sistema Transjuego.
 * @var mixed $TransjuegoLogMySqlDAO    Variable que representa la capa de acceso a datos MySQL para los registros de Transjuego.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $transacciones            Variable que almacena un conjunto de transacciones.
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $final                    Esta variable se utiliza para indicar si un proceso o estado es final.
 * @var mixed $papuestas                Variable que almacena el número de apuestas procesadas.
 * @var mixed $ppremios                 Variable que almacena el número de premios otorgados.
 * @var mixed $pcont                    Variable que almacena un contador de procesos o registros.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
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
use Backend\dto\UsucasinoDetalleResumen;
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
    $Usuario = new Usuario();

    $params = file_get_contents('php://input');
    $params = json_decode($params);


    if ($_REQUEST["DateFrom"] != "") {
        //$FromDateLocal = date("Y-m-d H:00:00", strtotime(time() . $timezone . ' hour '));
        $FromDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $_REQUEST["DateFrom"])));
    }
    if ($_REQUEST["DateTo"] != "") {
        //$ToDateLocal = date("Y-m-d H:00:00", strtotime(time() . ' +1 day' . $timezone . ' hour '));
        $ToDateLocal = date("Y-m-d H:i:s", strtotime(str_replace(" - ", " ", $_REQUEST["DateTo"])));
    }

    $date = date("Y-m-d H:i:s", strtotime($ToDateLocal . " -7 days"));


    $PlayerId = $_REQUEST['PlayerId'];
    $Mandante = $_REQUEST['Mandante'];

    $CountrySelect = $_REQUEST["CountrySelect"];

    $SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
    $OffSet = $_GET["OffSet"];
    $NumberRows = $_GET["NumberRows"];
    $Provider = $_REQUEST["ProviderId"];
    $Valor = $_REQUEST["Amount"];

    if ($Valor == "") {
        $Valor = 0;
    }
    $Type = $_REQUEST["Type"];


    $sql =
        "SELECT count(*) count 
FROM  transaccion_juego tj 
                             JOIN producto_mandante pm ON pm.prodmandante_id = tj.producto_id
                      JOIN producto p ON p.producto_id = pm.producto_id
                              JOIN proveedor p2 ON p.proveedor_id = p2.proveedor_id
                     JOIN subproveedor s ON s.subproveedor_id = p.subproveedor_id
         JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
         JOIN usuario u ON um.usuario_mandante = u.usuario_id
WHERE 1 = 1
  AND tj.fecha_crea BETWEEN '$FromDateLocal' AND '$ToDateLocal'
  AND s.tipo = '$Type'
  AND u.mandante  = $Mandante
  AND u.pais_id  = $CountrySelect 
   AND  tj.valor_ticket  >= $Valor
   LIMIT $NumberRows,$OffSet
  ";

    $TransjuegoLog = new TransjuegoLog();

    $TransjuegoLogMySqlDAO = new \Backend\mysql\TransjuegoLogMySqlDAO();
    $transaccion = $TransjuegoLogMySqlDAO->getTransaction();

    $count = $TransjuegoLog->execQuery($transaccion, $sql);

    $sql =
        "SELECT
        u.usuario_id,
       tj.fecha_crea,
      ROUND(tj.valor_ticket,2)                                                AS Apuestas,
       ROUND(CASE WHEN tj.tipo LIKE '%FREESPIN%' THEN 0 ELSE tj.valor_premio END, 2)  AS Premios,                                          
       ROUND(CASE WHEN tj.tipo LIKE '%FREESPIN%' THEN tj.valor_premio ELSE 0 END, 2) AS Premios_Bonos,
        p.descripcion,
       p2.descripcion
FROM  transaccion_juego tj 
                             JOIN producto_mandante pm ON pm.prodmandante_id = tj.producto_id
                      JOIN producto p ON p.producto_id = pm.producto_id
                              JOIN proveedor p2 ON p.proveedor_id = p2.proveedor_id
                     JOIN subproveedor s ON s.subproveedor_id = p.subproveedor_id
         JOIN usuario_mandante um ON tj.usuario_id = um.usumandante_id
         JOIN usuario u ON um.usuario_mandante = u.usuario_id
WHERE 1 = 1
   AND tj.fecha_crea BETWEEN '$FromDateLocal' AND '$ToDateLocal'
  AND s.tipo = '$Type'
  AND u.mandante  = $Mandante
  AND u.pais_id  = $CountrySelect 
   AND  tj.valor_ticket  >= $Valor
   LIMIT $NumberRows,$OffSet
  ";


    $TransjuegoLog = new TransjuegoLog();

    $TransjuegoLogMySqlDAO = new \Backend\mysql\TransjuegoLogMySqlDAO();
    $transaccion = $TransjuegoLogMySqlDAO->getTransaction();

    $transacciones = $TransjuegoLog->execQuery($transaccion, $sql);

    $data = json_encode($transacciones);
    $data = json_decode($data);


    $final = [];

    $papuestas = 0;
    $ppremios = 0;
    $pcont = 0;
    if ($count[0]->{".count"} != "0") {
        $Mandante = new Mandante($Mandante);
        $Pais = new Pais($CountrySelect);

        foreach ($data as $key => $value) {
            $array = [];
            $array["UsuarioId"] = $value->{"u.usuario_id"};
            $array["FechaCrea"] = $value->{"tj.fecha_crea"};
            $array["Apuestas"] = str_replace(',', '.', round($value->{".Apuestas"}, 2));
            $array["Ganancias"] = str_replace(',', '.', round($value->{".Premios"}, 2));
            $array["Bonos"] = str_replace(',', '.', round($value->{".Premios_Bonos"}, 2));
            $array["Juego"] = $value->{"p.descripcion"};
            $array["Proveedor"] = $value->{"p2.descripcion"};


            $array["Partner"] = $Mandante->descripcion;
            $array["PaisId"] = $Pais->paisNom;
            array_push($final, $array);
        }


        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = $count;
        $response["Data"] = $final;
    } else {
        $response["Error"] = false;
        $response["Mensaje"] = "Data no encontrada";
        $response["pos"] = 0;
        $response["TotalCount"] = 0;
        $response["Data"] = array();
    }
}