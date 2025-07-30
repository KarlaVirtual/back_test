<?php

use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\FranquiciaMandante;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioRecarga;

/**
 * get_franchises
 *
 * Obtener las franquicias disponibles para el usuario de la sesión. También incluye las franquicias recientemente usadas por el usuario a partir de sus recargas.
 *
 * @param object $json ->session->usuario : Usuario autenticado en la sesión.
 * @param object $json ->params->site_id : ID del sitio o mandante del cual se desean obtener franquicias.
 * @param object $json ->params->country : País actual del usuario.
 * @param int $json ->rid : Identificador único de la solicitud (Request ID).
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de estado de la respuesta (0 si es exitoso).
 *  - *rid* (int): ID de la solicitud recibido en $json->rid.
 *  - *data* (array): Listado de franquicias activas asignadas al partner y país.
 *      - *id_franquicia* (int): Identificador único de la franquicia.
 *      - *nombre_franquicia* (string): Nombre o descripción de la franquicia.
 *      - *imagen* (string): Ruta o URL de la imagen representativa de la franquicia.
 *  - *data2* (array): Listado de franquicias usadas recientemente por el usuario (máx. 3).
 *      - *id_franquicia* (int): Identificador único de la franquicia utilizada.
 *      - *nombre_franquicia* (string): Nombre o descripción de la franquicia utilizada.
 *      - *imagen* (string): Ruta o URL de la imagen representativa de la franquicia utilizada.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error descriptivo]",
 * "data" => array(),
 *
 * @throws Exception Si ocurre un error al obtener las franquicias disponibles o si el celular del usuario no está verificado.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


// Se obtiene el ID del sitio y se convierte a minúsculas
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);
$Country = $json->params->country;

// Se crea una instancia de UsuarioMandante utilizando la información de la sesión.
// Verifica si no existe una sesión
if ($json->session == null) {
    $json->session = new stdClass();
    if ($json->session->usuario == '' && $json->session->usuario == null) {
        syslog(LOG_WARNING, " get_franchisesLANDING");

        // Se inicializa el usuario como vacío
        $json->session->usuario = '';
        try {
            // Se crea un nuevo objeto Pais con el país obtenido
            $Pais = new Pais($Country);

        } catch (Exception $e) {
            // En caso de excepción, se crea un objeto Pais con los valores por defecto
            $Pais = new Pais('', $Country);
        }
        // Se crea un nuevo objeto Usuario
        $Usuario = new stdClass();
        $Usuario->mandante = $site_id;
        $Usuario->paisId = $Pais->paisId;
    }
} else {
    // Si ya existe una sesión, se obtiene el usuario mandante
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
}

// Se crea un objeto Clasificador para el tipo 'PHONEVERIFICATION'
$Clasificador = new Clasificador('', 'PHONEVERIFICATION');
$tipoDetalle = $Clasificador->getClasificadorId();

try {
    $MandanteDetalle = new MandanteDetalle('', $site_id, $tipoDetalle, $Usuario->paisId, '', 0);
    if ($MandanteDetalle->estado == 'A') {
        if ($Usuario->verifCelular == 'N') {
            throw new Exception('Celular no verificado.', 100095);
        }
    }
} catch (Exception $e) {
    // Si se lanza una excepción con el código 100095, la vuelve a lanzar
    if ($e->getCode() == 100095) throw $e;
}


// Definición de variables para la consulta
$MaxRows = 1000; // Número máximo de filas a recuperar
$OrderedItem = 1; // Ítem ordenado
$SkeepRows = 0; // Fila para omitir

// Se inicializa un arreglo de reglas para las consultas
$rules = [];
array_push($rules, array("field" => "franquicia.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "franquicia_mandante_pais.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "franquicia_mandante_pais.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "franquicia_mandante_pais.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));


// Se agrupan las reglas en un filtro
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

// Se crea una instancia de ProductoMandante
$FranquiciaMandante = new FranquiciaMandante();

// Se recuperan los productos del mandante y país utilizando el filtro JSON
//Obtenemos los Franquicias que ya han sido asignados a un partner y pais y los productos que ya han sido asignados a esta Franquicia
$Franquicias = $FranquiciaMandante->getFranquiciasMandanteCustom(" franquicia_mandante_pais.*,mandante.*,franquicia.*", "franquicia.franquicia_id", "ASC,franquicia.franquicia_id", $SkeepRows, $MaxRows, $json2, true);
// Se decodifica el JSON de productos mandantes
$Franquicias = json_decode($Franquicias);

if (!isset($Franquicias->count[0]->{'.count'}) || intval($Franquicias->count[0]->{'.count'}) === 0) {
    throw new Exception("No existen franquicias en el partner", "300159");
}

// Se inicializa un arreglo para almacenar los datos de productos mandantes
$FranquiciasM = array();

$moneda = $Usuario->moneda;
foreach ($Franquicias->data as $key => $value) {
    /*Definición e iteración para objetos de respuesta */

    $array = array();
    $array["id_franquicia"] = ($value->{"franquicia.franquicia_id"});
    $array["nombre_franquicia"] = ($value->{"franquicia.descripcion"});
    $array["imagen"] = ($value->{"franquicia.imagen"});

    array_push($FranquiciasM, $array);
}

/** Devolver las franquicias usadas recientemente por el usuario */

$UsuarioRecarga = new UsuarioRecarga();

$select = "transaccion_producto.producto_id,
    producto.descripcion,
    MAX(usuario_recarga.fecha_crea) as ultima_fecha,
    franquicia.franquicia_id, franquicia.descripcion, franquicia.imagen";
$order = "ultima_fecha";
$orderType = "desc";
$SkeepRows = 0;
$MaxRows = 3;

$rules = [];
array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
array_push($rules, array("field" => "franquicia.franquicia_id", "data" => "null", "op" => "nc"));
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);

$transacciones = $UsuarioRecarga->getUsuarioRecargasCustomPRO($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, "transaccion_producto.producto_id", "", true, " LEFT OUTER JOIN franquicia_producto ON (producto.producto_id = franquicia_producto.producto_id and producto.estado = franquicia_producto.estado) LEFT OUTER JOIN franquicia ON (franquicia_producto.franquicia_id = franquicia.franquicia_id and franquicia_producto.estado = franquicia.estado)");

$transacciones = json_decode($transacciones);
$transaccionesM = array();
// Si no existen franquicias usadas recientemente devuelve vacio
if (!isset($transacciones->count[0]->{'.count'}) || intval($transacciones->count[0]->{'.count'}) === 0) {
    $transaccionesM = [];
} // Si existen las guarda y las devuelve
else {
    foreach ($transacciones->data as $key => $value) {
        /*Definición e iteración para objetos de respuesta */

        $array = array();
        if (empty($value->{"franquicia.franquicia_id"})) break; // Si no hay una franquicia enlazada al producto pasa a la siguiente
        $array["id_franquicia"] = ($value->{"franquicia.franquicia_id"});
        $array["nombre_franquicia"] = ($value->{"franquicia.descripcion"});
        $array["imagen"] = ($value->{"franquicia.imagen"});

        array_push($transaccionesM, $array);
    }
}


/*Formato de respuesta*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $FranquiciasM;
$response["data2"] = $transaccionesM;

