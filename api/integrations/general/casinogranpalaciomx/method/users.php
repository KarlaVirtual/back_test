<?php

/**
 * Archivo que contiene la lógica para la autenticación y consulta de usuarios en el sistema.
 *
 * Este script realiza las siguientes funciones principales:
 * - Validación de tokens JWT para la autenticación de usuarios.
 * - Procesamiento de parámetros de entrada para la consulta de usuarios.
 * - Generación de filtros y reglas para la búsqueda de usuarios en la base de datos.
 * - Transformación de datos de usuarios en un formato estructurado para su respuesta.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $Usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow                  Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
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
 * @var mixed $dateFrom                 Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $rules                    Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                   Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $json                     Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $cadena                   Variable que almacena una cadena de texto.
 * @var mixed $no_permitidas            Variable que contiene una lista de valores no permitidos.
 * @var mixed $permitidas               Variable que contiene una lista de valores permitidos.
 * @var mixed $texto                    Variable que almacena un texto genérico.
 * @var mixed $usuarios                 Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $usuariosFinal            Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $Islocked                 Variable que indica si un elemento está bloqueado o no.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $tipoDoc                  Variable que almacena el tipo de documento de identificación.
 * @var mixed $Ciudad                   Variable que almacena el nombre de una ciudad.
 * @var mixed $departamento             Variable que almacena el nombre de un departamento o estado.
 * @var mixed $Pais                     Variable que almacena el nombre de un país.
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

/**
 * Clase principal para la gestión de usuarios.
 *
 * Este script utiliza múltiples dependencias y realiza operaciones como:
 * - Validación de tokens JWT.
 * - Generación de filtros para consultas.
 * - Transformación de datos de usuarios.
 */

// Inicialización de objetos principales
$Usuario = new Usuario();
//$UsuarioMandante = new UsuarioMandante($userNow);
$Mandante = new Mandante(4);

// Procesamiento de parámetros de entrada
$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');

// Obtención de encabezados HTTP
$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();

/**
 * Validación del entorno de configuración.
 *
 * Se define el usuario según el entorno (desarrollo o producción).
 */
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'casinogranpalaciomxVS';
} else {
    $usuario = 'casinogranpalaciomxVS';
}

// Generación del token JWT
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'casinogranpalaciomxVS';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

// Validación del token recibido en los encabezados
$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token) {
    // Procesamiento de parámetros de consulta
    $dateFrom = $params->fromDate;
    $dateTo = $params->toDate;

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    $MaxRows = $_REQUEST["count"];
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 100;
    }

    if ($MaxRows == "") {
        $MaxRows = 10000;
    }

    // Generación de reglas de filtrado
    $rules = [];
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
    array_push($rules, array("field" => "usuario.mandante", "data" => "$Mandante->mandante", "op" => "eq"));

    if ($dateFrom != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateFrom", "op" => "ge"));
    }
    if ($dateTo != "") {
        array_push($rules, array("field" => "usuario.fecha_crea", "data" => "$dateTo", "op" => "le"));
    }

    array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));


    array_push($rules, array("field" => "usuario.pais_id", "data" => "146", "op" => "eq"));

    array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    /**
     * Función para eliminar tildes de una cadena de texto.
     *
     * @param string $cadena Cadena de texto a procesar.
     *
     * @return string Cadena sin tildes.
     */
    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    // Consulta de usuarios y transformación de datos
    $usuarios = $Usuario->getUsuariosCustom2("(usuario.usuario_id),registro.origen_fondos,usuario.nombre,registro.tipo_doc,usuario.fecha_ult,usuario.moneda,usuario.login,usuario.estado,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.ciudad_id,registro.email,registro.direccion,registro.celular,registro.codigo_postal,c.*,g.*,usuario_mandante.usumandante_id,departamento.depto_nom", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

    $usuarios = json_decode($usuarios);

    if ($usuarios->count[0]->{".count"} != "0") {
        $usuariosFinal = array();

        foreach ($usuarios->data as $key => $value) {
            $Islocked = false;

            if ($value->{"usuario.estado"} == "I") {
                $Islocked = true;
            }

            $array = array();
            $array["UserId"] = $value->{"usuario.usuario_id"};
            $array["RFC"] = $value->{"registro.origen_fondos"};    //Registro Federal de Contribuyentes
            $array["CURP"] = ""; // La Clave Única de Registro de Población
            $array["UserName"] = $value->{"usuario.nombre"};
            switch ($value->{"registro.tipo_doc"}) {
                case "C":
                    $tipoDoc = "IFE";
                    break;
                case "E":
                    $tipoDoc = "Cedula Extranjeria";
                    break;
                case "P":
                    $tipoDoc = "Pasaporte";
                    break;
            }

            $array["DocumentId"] = $tipoDoc;
            $array["DocumentIdNumber"] = $value->{"registro.cedula"};;
            $array["Street"] = $value->{"registro.direccion"};
            $array["NumExt"] = "";
            $array["NumInt"] = "";
            $array["Colonia"] = ""; // Barrio
            $Ciudad = quitar_tildes($value->{"g.ciudad_nom"});
            $departamento = quitar_tildes($value->{"departamento.depto_nom"});
            $array["Municipio"] = $Ciudad;
            $array["Estado"] = $departamento;
            switch ($value->{"usuario.pais_id"}) {
                case "146":
                    $Pais = "México";
                    break;
            }
            $Pais = quitar_tildes($Pais);
            $array["Country"] = $Pais;
            $array["CP"] = $value->{"registro.codigo_postal"};
            $array["RegisterDate"] = $value->{"usuario.fecha_crea"};
            $array["UpdatedDate"] = "";
            switch ($value->{"usuario.estado"}) {
                case "A":
                    $status = "activo";
                    break;
                case "I":
                    $status = "inactivo";
                    if ($value->{"usuario.eliminado"} == "S") {
                        $status = "eliminado";
                    }
                    break;
            }
            $array["Status"] = $status;
            $array["UserAggregator"] = $value->{"usuario_mandante.usumandante_id"};

            array_push($usuariosFinal, $array);
        }

        // Respuesta exitosa
        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = intval($usuarios->count[0]->{".count"});
        $response["Data"] = $usuariosFinal;
    } else {
        // Respuesta sin datos
        $response["Error"] = false;
        $response["Mensaje"] = "No hay usuarios en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    // Excepción por token inválido
    throw new Exception("Usuario no coincide con token", "30012");
}


