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
 * Obtiene la configuración de autoexclusión del casino para un usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param object $json->session Información de la sesión del usuario.
 * @param object $json->session->usuario Información del usuario en la sesión.
 * @param object $json->params Parámetros de la solicitud.
 * @param int $json->params->count Número máximo de filas a obtener.
 * @param int $json->params->start Número de filas a omitir.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 *
 * @return array Respuesta con el código, ID de la respuesta, datos de configuraciones y conteo total.
 */

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$tipo = "EXCCASINOCATEGORY";

$Tipo = new Clasificador("", $tipo);
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;

/**
 * Se verifica si $SkeepRows está vacío y se establece en cero si es así.
 */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

/**
 * Se verifica si $MaxRows está vacío y se establece en 1000 si es así.
 */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

/**
 * Se crean las reglas de filtrado para la consulta.
 *
 * @var array $rules Array que contendrá las reglas de filtrado.
 */
$rules = [];
array_push($rules, array("field" => "usuario_configuracion.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_configuracion.tipo", "data" => $Tipo->getClasificadorId(), "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$UsuarioConfiguracion = new UsuarioConfiguracion();

$configuraciones = $UsuarioConfiguracion->getUsuarioConfiguracionesCustom(" usuario_configuracion.*,categoria.descripcion ", "usuario_configuracion.estado", "asc", $SkeepRows, $MaxRows, $json2, true);


$configuraciones = json_decode($configuraciones);

$configuracionesData = array();

foreach ($configuraciones->data as $key => $value) {


    $arraybanco = array();
    $arraybanco["Id"] = ($value->{"usuario_configuracion.usuconfig_id"}); // ID del usuario configuración
    $arraybanco["state"] = ($value->{"usuario_configuracion.estado"}); // Estado del usuario configuración
    $arraybanco["to_date"] = $value->{"usuario_configuracion.valor"}; // Valor correspondiente a la fecha
    $arraybanco["category"] = $value->{"usuario_configuracion.producto_id"}; // ID del producto
    $arraybanco["category"] = $value->{"categoria.descripcion"}; // Descripción de la categoría

    // Se asigna un valor numérico al estado basado en la descripción textual
    if ($value->{"usuario_configuracion.estado"} == "A") {
        $arraybanco["state"] = '1';

    } elseif ($value->{"usuario_configuracion.estado"} == "I") {
        $arraybanco["state"] = '2';

    } elseif ($value->{"usuario_configuracion.estado"} == "C") {
        $arraybanco["state"] = '3';

    }

    array_push($configuracionesData, $arraybanco); // Agrega la configuración formateada al array

}

// Se prepara la respuesta final
$response = array();
$response["code"] = 0; // Código de respuesta
$response["rid"] = $json->rid; // ID de la respuesta
$response["data"] = $configuracionesData; // Datos de configuraciones
$response["total_count"] = $configuraciones->count[0]->{".count"}; // Conteo total de configuraciones