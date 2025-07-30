<?php

/**
 * Script para manejar la integración de usuarios en la plataforma Netabet.
 *
 * Este archivo contiene la lógica para procesar solicitudes relacionadas con usuarios,
 * incluyendo validación de tokens, generación de filtros y obtención de datos personalizados.
 *
 * @category   Integración
 * @package    API
 * @subpackage Netabet
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $Usuario                  Instancia de la clase Usuario para manejar operaciones relacionadas con usuarios.
 * @var mixed $Mandante                 Instancia de la clase Mandante con un ID específico para identificar el mandante actual.
 * @var mixed $params                   Contiene los parámetros enviados en la solicitud HTTP como un JSON y los decodifica.
 * @var mixed $headers                  Almacena todos los encabezados de la solicitud HTTP.
 * @var mixed $ConfigurationEnvironment Instancia de la clase ConfigurationEnvironment para determinar el entorno actual (desarrollo o producción).
 * @var mixed $usuario                  Define el usuario según el entorno (desarrollo o producción).
 * @var mixed $header                   Encabezado del token JWT con el algoritmo y tipo especificados.
 * @var mixed $payload                  Carga útil del token JWT con información básica como código, mensaje y usuario.
 * @var mixed $key                      Clave secreta utilizada para firmar el token JWT.
 * @var mixed $signature                Genera la firma del token JWT utilizando el algoritmo SHA-256.
 * @var mixed $token                    Construye el token JWT completo y lo precede con el prefijo "Bearer".
 * @var mixed $TokenHeader              Obtiene el token de los encabezados de la solicitud HTTP.
 * @var mixed $dateFrom                 Representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Representa una fecha de finalización en un rango de fechas.
 * @var mixed $MaxRows                  Define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Indica el número de registros a omitir en una consulta.
 * @var mixed $rules                    Contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                   Almacena las reglas de filtro en formato JSON.
 * @var mixed $usuarios                 Contiene los datos de los usuarios obtenidos de la base de datos.
 * @var mixed $usuariosFinal            Almacena la lista final de usuarios procesados.
 * @var mixed $response                 Almacena la respuesta generada por una operación o petición.
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

$Usuario = new Usuario();
$Mandante = new Mandante(6);

$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');

$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'netabetVirtualSoft';
} else {
    $usuario = 'netabetVirtualSoft';
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

$key = 'netabetVirtualS';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token) {
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
     * Elimina las tildes y caracteres especiales de una cadena de texto.
     *
     * @param string $cadena La cadena de texto a procesar.
     *
     * @return string La cadena de texto sin tildes ni caracteres especiales.
     */
    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

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
            $array["Colonia"] = ""; // barrio?
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


        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = intval($usuarios->count[0]->{".count"});
        $response["Data"] = $usuariosFinal;
    } else {
        $response["Error"] = false;
        $response["Mensaje"] = "No hay usuarios en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    throw new Exception("Usuario no coincide con token", "30012");
}


