<?php


use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;

use Backend\dto\DescargaVersion;

use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DescargaMySqlDAO;
use Backend\mysql\DescargaVersionMySqlDAO;
use Backend\mysql\descargaVersionMysqlDao as MysqlDescargaVersionMysqlDao;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Firebase\JWT\Key;

/**
 * RegisteredDocument/GetVersion
 *
 * Obtener versiones de documentos
 *
 * Este recurso permite obtener las versiones de un documento específico, basado en su ID, versión y ruta proporcionados
 * a través de parámetros de la consulta GET. Los resultados son paginados, utilizando los parámetros `start` (skeepRows)
 * y `count` (MaxRows) para controlar la cantidad de registros devueltos. Además, el recurso filtra los documentos según
 * el ID del documento pasado como parámetro.
 *
 * @param string $id : ID del documento para filtrar los resultados.
 * @param string $version : Versión del documento para filtrar los resultados.
 * @param string $ruta : Ruta del documento para filtrar los resultados.
 * @param int $MaxRows : Número máximo de resultados a recuperar (paginación).
 * @param int $skeepRows : Número de resultados a omitir (paginación).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *hasError* (bool): Indica si hubo un error durante el procesamiento de la solicitud.
 *  - *alertType* (string): Tipo de alerta generada (por ejemplo, "success").
 *  - *data* (array): Contiene los detalles de las versiones de los documentos que cumplen con los filtros, incluyendo:
 *    - *Version* (string): Versión del documento.
 *    - *DocumentUrl* (string): URL del documento.
 *    - *EncryptionValue* (string): Valor de encriptación asociado a la versión del documento.
 *    - *CreatedDate* (string): Fecha de creación de la versión del documento.
 *
 * Objeto en caso de error:
 *  - *hasError* => true,
 *  - *alertType* => "danger",
 *  - *data* => array().
 *
 * @throws Exception Si ocurre un error durante la obtención de las versiones del documento.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene parámetros de la URL para usarlos en la variable $skeepRows. */
$id = $_GET["id"];
$version = $_GET["version"];
$ruta = $_GET["ruta"];


$skeepRows = 0;

/* Código que define una regla basada en un ID no vacío para una consulta. */
$MaxRows = 10;

$rules = [];

if ($id != "") {
    array_push($rules, array("field" => "descarga_version.documento_id", "data" => $id, "op" => "eq"));
}


/* Crea un filtro en JSON para obtener datos de "descarga_version". */
$filtro = array($rules, "rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$descargaVersion = new DescargaVersion();

$datos = $descargaVersion->getdescargaVersionCustom("descarga_version.*", "descarga_version.descarga_version_id", "desc", $skeepRows, $MaxRows, $json, true, "");


/* decodifica datos JSON y extrae información en un nuevo arreglo. */
$datos = json_decode($datos);


$final = [];

foreach ($datos->data as $key => $value) {
    $array = [];
    $array["Version"] = $value->{"descarga_version.version"};
    $array["DocumentUrl"] = $value->{"descarga_version.url"};
    $array["EncryptionValue"] = $value->{"descarga_version.encriptacion"};
    $array["CreatedDate"] = $value->{"descarga_version.fecha_crea"};

    array_push($final, $array);
}


/* Código PHP que define una respuesta sin errores y con datos exitosos. */
$response["hasError"] = false;
$response["alertType"] = "success";
$response["data"] = $final;


?>