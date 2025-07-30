<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
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
 * RegisteredDocument/GetDocuments
 *
 * GetDocuments
 *
 * Este recurso se encarga de obtener una lista de documentos (o documentos de un usuario específico)
 * a través de filtros basados en parámetros de entrada como el ID, nombre, ruta, versión, tipo, estado de activación, etc.
 * Además, si el parámetro `PlayerId` se proporciona, se obtienen los documentos asociados a ese usuario en particular.
 * La respuesta se estructura de manera paginada, permitiendo limitar los resultados mediante los parámetros `start` (SkeepRows) y `count` (MaxRows).
 *
 * @param string $Id : ID del documento para filtrar los resultados.
 * @param string $Name : Nombre del documento para filtrar por descripción.
 * @param string $Route : Ruta del documento para filtrar por la ruta.
 * @param string $Version : Versión del documento para filtrar.
 * @param string $Type : Tipo del documento para filtrar.
 * @param string $IsActivate : Estado de activación del documento (activo o no activo).
 * @param int $MaxRows : Número máximo de registros a recuperar.
 * @param int $SkeepRows : Número de registros a omitir (paginación).
 * @param string $PlayerId : ID del usuario para filtrar los documentos asociados a ese usuario específico.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta (en este caso "success" o "danger").
 *  - *AlertMessage* (string): Mensaje relacionado con la alerta.
 *  - *ModelErrors* (array): Errores del modelo, en caso de que existan.
 *  - *data* (array): Contiene los documentos recuperados, que incluyen:
 *    - *Id* (int): ID del documento.
 *    - *Name* (string): Descripción del documento.
 *    - *Route* (string): Ruta del documento.
 *    - *Version* (string): Versión del documento.
 *    - *Type* (string): Tipo del documento.
 *    - *Date* (string): Fecha de creación del documento.
 *    - *IsActivate* (string): Estado de activación del documento.
 *    - *EncryptionMethod* (string): Método de encriptación del documento.
 *    - *EncryptionValue* (string): Valor de encriptación del documento.
 *    - *ExternalId* (string): ID externo del documento.
 *    - *PaisId* (string): ID del país relacionado con el documento.
 *    - *ProveedorId* (string): ID del proveedor del documento.
 *    - *Profile* (string): ID del perfil asociado al documento.
 *  - *pos* (int): Posición de la primera fila de la página actual (para paginación).
 *  - *total_count* (int): Total de documentos disponibles.
 *
 * Objeto en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "danger",
 *  - *AlertMessage* => "Error message",
 *  - *data* => array().
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene y decodifica datos JSON de la entrada y parámetros GET. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $_GET['Id'];
$Name = $_GET['Name'];
$Route = $_GET['Route'];

/* Código PHP que captura parámetros de una solicitud GET para su posterior procesamiento. */
$Version = $_GET['Version'];
$Type = $_GET['Type'];
$IsActivate = $_GET['IsActivate'];
$MaxRows = $_GET['count'];
$SkeepRows = $_GET['start'];

$PlayerId = $_GET['PlayerId'];

/* asigna valores predeterminados a variables si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* crea reglas de filtrado basadas en condiciones de ID y nombre. */
$rules = [];

if ($Id != "") {
    array_push($rules, array("field" => "descarga.descarga_id", "data" => $Id, "op" => "eq"));
}

if ($Name != "") {
    array_push($rules, array("field" => "descarga.descripcion", "data" => $Name, "op" => "eq"));
}

/* Agrega reglas basadas en condiciones de ruta y versión si no están vacías. */
if ($Route != "") {
    array_push($rules, array("field" => "descarga.ruta", "data" => $Route, "op" => "eq"));
}
if ($Version != "") {
    array_push($rules, array("field" => "descarga.version", "data" => $Version, "op" => "eq"));
}

/* Agrega reglas a un array basadas en variables de tipo y estado. */
if ($Type != "") {
    array_push($rules, array("field" => "descarga.tipo", "data" => $Type, "op" => "eq"));
}

if ($IsActivate != "") {
    array_push($rules, array("field" => "descarga.estado", "data" => $IsActivate, "op" => "eq"));
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Agrega reglas a un arreglo basado en valores de sesión y condiciones específicas. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "descarga.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}


if ($PlayerId != "" && $PlayerId != null) {

    /* Se construye un filtro JSON con reglas para consultar documentos de usuario. */
    array_push($rules, array("field" => "documento_usuario.usuario_id", "data" => $PlayerId, "op" => "eq"));
    array_push($rules, array("field" => "documento_usuario.estado_aprobacion", "data" => 'A', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json_filter = json_encode($filtro);

    $DocumentoUsuario = new DocumentoUsuario();

    /* Se obtienen y decodifican documentos de usuario en formato JSON. */
    $usuarios = $DocumentoUsuario->getDocumentosUsuarioCustom(" descarga.*,documento_usuario.*,descarga_version.* ", "documento_usuario.docusuario_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

    $usuarios = json_decode($usuarios);
    $arrayDocumentos = [];


    /* Recorre usuarios y almacena datos en un array estructurado para cada uno. */
    foreach ($usuarios->data as $key => $value) {

        $array = [];

        $array["Id"] = $value->{"documento_usuario.docusuario_id"};
        $array["Name"] = $value->{"descarga.descripcion"};
        $array["Route"] = $value->{"descarga.ruta"};
        $array["Version"] = $value->{"descarga_version.version"};
        $array["Type"] = $value->{"descarga.tipo"};
        $array["Date"] = $value->{"documento_usuario.fecha_crea"};
        $array["IsActivate"] = $value->{"descarga.estado"};
        $array["EncryptionMethod"] = $value->{"descarga_version.encriptacion"};
        $array["EncryptionValue"] = $value->{"descarga_version.encriptacion"};
        $array["ExternalId"] = $value->{"descarga.external_id"};
        $array["PaisId"] = $value->{"descarga.pais_id"};
        $array["ProveedorId"] = $value->{"descarga.proveedor_id"};
        $array["Profile"] = $value->{"descarga.perfil_id"};

        array_push($arrayDocumentos, $array);
    }

} else {


    /* Se crea un filtro JSON para obtener registros en la descarga personalizada. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json_filter = json_encode($filtro);

    $Descarga = new Descarga();
    $usuarios = $Descarga->getDescargasCustom(" descarga.*", "descarga.descarga_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

    $usuarios = json_decode($usuarios);

    /* Crea un array con información de documentos a partir de usuarios procesados. */
    $arrayDocumentos = [];

    foreach ($usuarios->data as $key => $value) {

        $array = [];

        $array["Id"] = $value->{"descarga.descarga_id"};
        $array["Name"] = $value->{"descarga.descripcion"};
        $array["Route"] = $value->{"descarga.ruta"};
        $array["Version"] = $value->{"descarga.version"};
        $array["Date"] = $value->{"documento_usuario.fecha_crea"};
        $array["Type"] = $value->{"descarga.tipo"};
        $array["IsActivate"] = $value->{"descarga.estado"};
        $array["EncryptionMethod"] = $value->{"descarga.encriptacion_metodo"};
        $array["EncryptionValue"] = $value->{"descarga.encriptacion_valor"};
        $array["ExternalId"] = $value->{"descarga.external_id"};
        $array["PaisId"] = $value->{"descarga.pais_id"};
        $array["ProveedorId"] = $value->{"descarga.proveedor_id"};
        $array["Profile"] = $value->{"descarga.perfil_id"};
        array_push($arrayDocumentos, $array);
    }
}


/* Código asigna respuestas para una operación, indicando éxito y datos procesados. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = $arrayDocumentos;

$response["pos"] = $SkeepRows;

/* Se obtiene el conteo de usuarios y se asigna a la variable total_count. */
$response["total_count"] = $usuarios->count[0]->{".count"};
