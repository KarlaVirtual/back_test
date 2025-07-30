<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Deporte;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\EquipoFavorito;
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
use Backend\dto\UsuarioMarketing;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\BonoInternoMySqlDAO;
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
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Procesa la solicitud para obtener deportes.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param int $json->rid Identificador de la solicitud.
 * @param object $json->params Objeto que contiene los parámetros de la solicitud.
 * @param int $json->params->site_id Identificador del sitio.
 * @param int $json->params->MaxRows Número máximo de filas a obtener.
 * @param int $json->params->OrderedItem Elemento ordenado.
 * @param int $json->params->SkeepRows Número de filas a omitir.
 * @param int $json->params->count Número máximo de filas a obtener.
 * @param int $json->params->start Número de filas a omitir.
 *
 * @return array Respuesta con el código de estado, identificador de la solicitud, datos de los deportes y el conteo total.
 *
 * @throws Exception Si ocurre un error durante el procesamiento de la solicitud.
 */

$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);

/*Recepción de parámetros*/
$params = $json->params;
$site_id = $json->params->site_id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;

/*El código verifica si las variables $SkeepRows y $OrderedItem están vacías y les asigna valores predeterminados si es necesario.*/
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 20;
}
    // Inicializa un array vacío para las reglas
    $rules = [];

    // Configura el filtro para la consulta con las reglas y la operación de grupo
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    // Codifica el filtro en formato JSON
    $jsonfiltro = json_encode($filtro);

    // Selecciona todos los campos de la tabla int_deporte
    $select = "int_deporte.*";

    // Instancia un nuevo objeto de la clase IntDeporte
    $IntDeporte = new IntDeporte();

    // Obtiene los deportes personalizados utilizando el método getDeportesCustom
    $data = $IntDeporte->getDeportesCustom($select, "int_deporte.deporte_id", 'asc', $SkeepRows, $MaxRows, $jsonfiltro, true);
    $equipos = json_decode($data);
    $equiposData = array();

    // Itera sobre cada uno de los elementos del objeto equipos
    foreach ($equipos->data as $key => $value) {
        $array = array();

        // Asigna el ID y el nombre del deporte al array
        $array["Id"] = $value->{"int_deporte.deporte_id"};
        $array["Name"] = $value->{"int_deporte.nombre"};

        // Agrega el array del equipo al array de equipos
        array_push($equiposData, $array);
    }

    // Inicializa un array para la respuesta
    $response = array();
    $response["code"] = 0;
    $response["data"] = $equiposData;
    $response["total_count"] = $equipos->count[0]->{".count"};
    $response["rid"] = $json->rid;