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

/*
* Este script realiza la categorización masiva de clientes a partir de un archivo CSV enviado en el cuerpo de la solicitud.
*
* @param object $params Objeto JSON decodificado con las siguientes propiedades:
* @param strng $params->CSV Archivo CSV codificado en base64 que contiene los IDs de los clientes.
* @param string $params->Categorization Tipo de categorización a aplicar.
* @param string $params->Description Descripción de la categorización. 
*
* @return array $response Respuesta en formato JSON con las siguientes propiedades:
* - HasError (boolean): Indica si ocurrió un error.
* - AlertType (string): Tipo de alerta (e.g., "success", "danger").
* - AlertMessage (string): Mensaje de alerta o error.
* - ModelErrors (array): Lista de errores de validación.
* - Data (array): Datos adicionales de la respuesta.
*
*/

/**
 * @OA\Post(path="apipv/Client/MassiveContingency", tags={"Client"}, description = "",
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * ),
 */


/* obtiene, decodifica y asigna datos JSON a variables PHP. */
$params = file_get_contents('php://input');
//$params = base64_decode($params);
$params = json_decode($params);

$ClientIdCsv = $params->CSV;
$type = $params->Categorization;


/* decodifica una cadena CSV codificada en base64. */
$Description = $params->Description;

$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];

$ClientIdCsv = base64_decode($ClientIdCsv);


/* Convierte un CSV en líneas, reemplazando punto y coma por comas y separando filas. */
$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);

$lines = explode(PHP_EOL, $ClientIdCsv);
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);


$array = array();

/* Convierte líneas CSV en un array y organiza columnas en otro array. */
foreach ($lines as $line) {
    $array[] = str_getcsv($line);

}
$countArray = oldCount($array[0]);

for ($i = 0; $i <= $countArray; $i++) {
    $arrayfinal = array();
    $arrayfinal = array_column($array, $i);
}

/* obtiene claves y convierte un array en formato JSON. */
$arrayfinal = array_column($array, '0');

$posiciones = array_keys($arrayfinal);

$ultima = strval(end($posiciones));
$arrayfinal = json_decode(json_encode($arrayfinal));
//unset($arrayfinal[$ultima]);


/* convierte un arreglo en una cadena y luego la convierte nuevamente en un arreglo. */
$ids = implode(",", $arrayfinal);
if ($ids != "") {

    $clients = $ids;
    $clients = explode(",", $clients);
}


// Si el usuario esta condicionado por País

/* verifica condiciones de sesión para asignar valores a variables específicas. */
if ($_SESSION['PaisCond'] == "S") {
    $Pais = $_SESSION['pais_id'];
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $Mandante = $_SESSION['mandante'];
} else {
    /* verifica si "mandanteLista" está definida y no es "-1" antes de asignarla. */

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $Mandante = $_SESSION["mandanteLista"];
    }

}

/* inicializa un array de respuesta sin errores y con datos vacíos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = '';
$response["ModelErrors"] = [];

$response["Data"] = [];
if ((oldCount($clients) > 0 || (oldCount($clients) == 0))) {


    foreach ($clients as $key => $ClientId) {


        /* limpia y formatea un ID de cliente eliminando caracteres no deseados. */
        $ClientId = preg_replace('/[\xE2\x80\xAF]/', '', $ClientId);
        $ClientId = str_replace(" ", '', $ClientId);
        $ClientId = preg_replace("/[^0-9.]/", "", $ClientId);

        if ($ClientId != "" && $ClientId != "0") {
            try {


                /* actualiza un usuario en la base de datos según condiciones específicas. */
                $Usuario = new Usuario(strtolower($ClientId));

                if ($Pais == $Usuario->paisId || $Pais == "") {

                    $Usuario->claveTv = $type;


                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $UsuarioMySqlDAO->update($Usuario);
                    $UsuarioMySqlDAO->getTransaction()->commit();

                    $msg = "entro6";
                } else {
                    /* Lanza una excepción si los usuarios no están asociados al socio especificado. */

                    throw new Exception("Usuarios no pertenecen al partner", '60003');
                }


            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, almacenando el mensaje de error en una variable. */

                $msg = $e->getMessage();

            }

        } else {
            /* maneja una respuesta sin errores, preparando datos para su envío. */

            /* $response["HasError"] = false;
             $response["AlertType"] = "success";
             $response["AlertMessage"] = $msg;
             $response["ModelErrors"] = [];

             $response["Data"] = [];*/
        }
    }
} else {
    /* Código que configura la respuesta sin errores en un formato estructurado. */

    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = [];

}
