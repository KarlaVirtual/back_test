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
 * Obtiene la configuración de autoexclusión del producto para un usuario.
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

// Se crea una instancia de UsuarioMandante pasando el usuario de la sesión en formato JSON.
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$tipo = "EXCPRODUCT";

$Tipo = new Clasificador("", $tipo);

// Se asignan los parámetros de paginación desde el JSON.
$MaxRows = $json->params->count; // Número máximo de filas a recuperar.
$SkeepRows = $json->params->start; // Número de filas a omitir.

// Si no se especifica el número de filas a omitir, se establece en 0.
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

// Si no se especifica el número máximo de filas, se establece en 1000.
if ($MaxRows == "") {
    $MaxRows = 1000;
}

// Se inicializa un arreglo de reglas para filtrar resultados.
$rules = [];
array_push($rules, array("field" => "usuario_configuracion.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_configuracion.tipo", "data" => $Tipo->getClasificadorId(), "op" => "eq"));

// Se crea un filtro a partir de las reglas definidas.
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$UsuarioConfiguracion = new UsuarioConfiguracion();

$configuraciones = $UsuarioConfiguracion->getUsuarioConfiguracionesCustom(" usuario_configuracion.* ", "usuario_configuracion.usuconfig_id", "asc", $SkeepRows, $MaxRows, $json2, true);


$configuraciones = json_decode($configuraciones);

// Se inicializa un arreglo para almacenar los datos de configuraciones.
$configuracionesData = array();
foreach ($configuraciones->data as $key => $value) {


    $arraybanco = array();
    $arraybanco["Id"] = ($value->{"usuario_configuracion.usuconfig_id"});
    $arraybanco["state"] = ($value->{"usuario_configuracion.estado"});
    $arraybanco["to_date"] = $value->{"usuario_configuracion.valor"};
    $arraybanco["since_date"] = $value->{"usuario_configuracion.fecha_crea"};

    // Verifica si la fecha de creación más 24 horas es mayor que la fecha actual, o si el valor es menor que la fecha actual
    if(
        date('Y-m-d H:i:s', strtotime($value->{'usuario_configuracion.fecha_crea'} . '+24 hours')) >  date('Y-m-d H:i:s') ||
        $value->{'usuario_configuracion.valor'} < date('Y-m-d H:i:s')
    ) {
        $arraybanco['button_show'] = false; // No mostrar el botón si se cumplen las condiciones
    }

    // Se asigna un valor al estado dependiendo de su estado específico
    if ($value->{"usuario_configuracion.estado"} == "A") {
        $arraybanco["state"] = '1';

    } elseif ($value->{"usuario_configuracion.estado"} == "I") {
        $arraybanco["state"] = '2';

    } elseif ($value->{"usuario_configuracion.estado"} == "C") {
        $arraybanco["state"] = '3';

    }

    /*Define el tipo de producto según el ID obtenido de la base de datos, asignándole al arrayBanco*/
    switch ($value->{"usuario_configuracion.producto_id"}) {
        case "0":
            $arraybanco["type_product"] = "sportsbook";

            break;

        case "1":
            $arraybanco["type_product"] = "Virtual sports";

            break;

        case "2":
            $arraybanco["type_product"] = "Live casino";

            break;

        case "3":
            $arraybanco["type_product"] = "casino";

            break;

        case "4":
            $arraybanco["type_product"] = "Prematch";

            break;

        case "5":
            $arraybanco["type_product"] = "Live";

            break;
    }


    array_push($configuracionesData, $arraybanco);


}

//Formateo de la respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $configuracionesData;
$response["total_count"] = $configuraciones->count[0]->{".count"};