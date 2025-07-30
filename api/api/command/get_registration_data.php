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
 * Obtiene la información de documentos por aceptar en el momento de registro.
 *
 * @param object $json Objeto JSON que contiene la sesión y otros datos.
 * @param object $json->session Objeto que contiene la sesión del usuario.
 * @param object $json->session->mandante Mandante de la sesión.
 * @param int $SkeepRows Número de filas a omitir en la consulta.
 * @param int $OrderedItem Número de ítems ordenados.
 * @param int $MaxRows Número máximo de filas a obtener.
 * @return array $response Respuesta estructurada con código, identificador y datos finales.
 * @throws Exception Si ocurre un error durante la obtención de descargas.
 */

// Se obtiene el mandante de la sesión en el objeto JSON.
$mandante = $json->session->mandante;

// Se establece el número de filas a omitir, por defecto es 0 si no se proporciona valor.
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

// Se establece el ítem ordenado, por defecto es 1 si no se proporciona valor.
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

// Se establece el número máximo de filas a recuperar, por defecto es 1000 si no se proporciona valor.
if ($MaxRows == "") {
    $MaxRows = 1000;
}

// Se inicializa un arreglo para las reglas de filtrado.
$rules = [];

// Se agregan reglas para filtrar el estado y el mandante.
array_push($rules, array("field" => "descarga.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "descarga.mandante", "data" => $mandante, "op" => "eq"));

// Se crea un filtro utilizando las reglas y la operación de agrupación AND.
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json_filter = json_encode($filtro);

// Se instancia un objeto de la clase Descarga.
$Descarga = new Descarga();
$usuarios = $Descarga->getDescargasCustom(" descarga.* ", "descarga.descarga_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

// Se decodifica el JSON recibido en un objeto.
$usuarios = json_decode($usuarios);
$arrayDocumentos = [];

// Se itera sobre cada usuario para construir el arreglo de documentos.
foreach ($usuarios->data as $key => $value) {

    // Se inicializa un arreglo para cada documento.
    $array = [];

    $array["Id"] = $value->{"descarga.descarga_id"};
    $array["Name"] = $value->{"descarga.descripcion"};
    $array["Crypt"] = $value->{"descarga.encriptacion_metodo"};
    $array["Checksum"] = $value->{"descarga.encriptacion_valor"};
    $array["slug"] = $value->{"descarga.ruta"};

    // Se agrega el documento al arreglo de documentos.
    array_push($arrayDocumentos, $array);
}

/**
 * Inicializa una respuesta en formato de array con códigos y datos específicos.
 *
 * La respuesta incluye un código, un identificador de solicitud (rid),
 * límites de depósito por día, semana y mes, y datos adicionales
 * que se obtienen de documentos.
 */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "limitsDepositDefault" =>
        array(
            "LimitDay" => "1000",
            "LimitWeek" => "10000",
            "m" => $mandante,
            "LimitMonth" => "100000"),

    "checksumData" =>
        $arrayDocumentos


);