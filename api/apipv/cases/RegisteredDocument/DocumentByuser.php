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

/**
 * RegisteredDocument/DocumentByuser
 *
 * Este recurso se encarga de obtener documentos asociados a un usuario, filtrados por diversos parámetros,
 * como el nombre, el estado, la versión y la ruta. Los resultados se devuelven en un formato estructurado,
 * incluyendo información sobre la descarga, como el ID, nombre, ruta, versión y fechas de creación y modificación.
 *
 * @param string $Type : Tipo de documento a filtrar.
 * @param string $Name : Nombre del documento a filtrar.
 * @param string $Status : Estado del documento a filtrar.
 * @param string $Route : Ruta del documento a filtrar.
 * @param string $Version : Versión del documento a filtrar.
 * @param string $PlayerId : ID del jugador para filtrar los documentos asociados a un usuario específico.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta que se mostrará (en este caso "danger" por defecto).
 *  - *AlertMessage* (string): Mensaje de alerta (en este caso vacío).
 *  - *ModelErrors* (array): Errores del modelo (array vacío en caso de éxito).
 *  - *data* (array): Contiene el resultado de la consulta de documentos, donde cada elemento es un documento con:
 *    - *id* (int): ID de la descarga.
 *    - *nombre* (string): Nombre de la descarga.
 *    - *ruta* (string): Ruta del documento.
 *    - *version* (string): Versión del documento.
 *    - *fecha_crea* (string): Fecha de creación del documento.
 *    - *fecha_modif* (string): Fecha de modificación del documento.
 *
 *
 * Objeto de respuesta en caso de error:
 *  "HasError" => true,
 *  "AlertType" => "danger",
 *  "AlertMessage" => "[Mensaje de error]",
 *  "ModelErrors" => [Errores específicos del modelo],
 *  "data" => array().
 *
 * @throws Exception Si ocurre un error durante la ejecución del proceso de obtención de documentos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene y decodifica datos JSON desde una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Type = $params->Type;
$Name = $params->Name;
$Status = $params->Status;

/* asigna valores de parámetros a variables y establece un contador de filas. */
$Route = $params->Route;
$Version = $params->Version;
$PlayerId = $params->PlayerId;


$SkeepRows = 0;

/* Configura una regla para filtrar usuarios por ID en una consulta. */
$Maxrows = 10;


$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $PlayerId, "op" => "eq"));
}


/* Agrega reglas de filtrado basadas en el nombre y estado en un arreglo. */
if ($Name != "") {
    array_push($rules, array("field" => "descarga.descripcion", "data" => $Name, "op" => "cn"));
}

if ($status != "") {
    array_push($rules, array("field" => "descarga.estado", "data" => $Status, "op" => "eq"));
}


/* Agrega reglas a un array si las variables no están vacías. */
if ($Version != "") {
    array_push($rules, array("field" => "documento_usuario.version", "data" => $Version, "op" => "eq"));
}

if ($Route != "") {
    array_push($rules, array("field" => "descarga.ruta", "data" => $Route, "op" => "eq"));
}


// array_push($rules,array("field"=>"documento_usuario.usuario_id","data"=>$_SESSION["usuario"],"op"=>"eq"));


/* crea un filtro JSON y recupera documentos de usuario personalizados. */
$filters = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filters);

$documentoUser = new DocumentoUsuario();

$data = $documentoUser->getDocumentosUsuarioCustom("documento_usuario.*, descarga.*", "documento_usuario.docusuario_id", "desc", $SkeepRows, $Maxrows, $json, true);


/* Transforma datos JSON en un array asociativo con información específica de descargas. */
$data = json_decode($data);


$final = [];

foreach ($data->data as $key => $value) {
    $array = [];

    $array["id"] = $value->{"descarga.descarga_id"};
    $array["nombre"] = $value->{"descarga.descripcion"};
    $array["ruta"] = $value->{"descarga.ruta"};
    $array["version"] = $value->{"descarga.version"};
    $array["fecha_crea"] = $value->{"descarga.fecha_crea"};
    $array["fecha_modif"] = $value->{"descarga.fecha_modif"};

    array_push($final, $array);

}


/* Código que establece una respuesta sin errores y almacena datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $final;


?>