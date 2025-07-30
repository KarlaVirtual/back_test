<?php


use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;

/**
 * Obtener el ultimo deposito pagado al usuario
 *
 *
 * @param object $params : Objeto con los parámetros para filtrar las transacciones.
 *   - *country* (string): Pais de la plataforma.
 *   - *site_id* (int): ID de la madante de la plataforma.

 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error (no se utiliza en este recurso).
 *  - *data* (array): Contiene la lista de transacciones obtenidas del sistema.
 *    Cada elemento incluye:
 *      - *monto* (float): Monto de la transacción.
 *      - *metodo* (string): Método o canal utilizado (por ejemplo, nombre de la franquicia).
 *      - *tienda* (string): Descripción de la tienda y mandante.
 *      - *id* (int): ID único de la transacción.
 *      - *fecha_hora* (string): Fecha y hora en que se realizó la transacción.
 *      - *saldo_total* (string): Saldo total del usuario al momento de la consulta.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "result" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception Si ocurre un error al obtener datos del sistema o de la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

// Se obtiene el ID del sitio y se convierte a minúsculas
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);
$Country =$json->params->country;
$Id =$json->params->Id;

// Se crea una instancia de UsuarioMandante utilizando la información de la sesión.
// Verifica si no existe una sesión
if($json->session == null){
    $json->session = new stdClass();
    if($json->session->usuario =='' && $json->session->usuario==null){
        syslog(LOG_WARNING, " get_lastDepositLANDING" );

        // Se inicializa el usuario como vacío
        $json->session->usuario = '';
        try {
            // Se crea un nuevo objeto Pais con el país obtenido
            $Pais = new Pais($Country);

        } catch (Exception $e){
            // En caso de excepción, se crea un objeto Pais con los valores por defecto
            $Pais = new Pais('', $Country);
        }
        // Se crea un nuevo objeto Usuario
        $Usuario = new stdClass();
        $Usuario->mandante=$site_id;
        $Usuario->paisId=$Pais->paisId;
    }
}else{
    // Si ya existe una sesión, se obtiene el usuario mandante
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
}

$UsuarioRecarga = new UsuarioRecarga();
$params = file_get_contents('php://input');
$params = json_decode($params);

$State = 'A';


/**
 * Configuración de reglas de filtrado para la consulta
 * Se construyen las reglas según los parámetros recibidos
 */
$rules = [];

if ($Country != '') {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $Usuario->paisId, "op" => "eq"));
}


if ($Id != '') {
    array_push($rules, array("field" => "usuario_recarga.recarga_id", "data" => $Id, "op" => "eq"));
}
else{
    throw new Exception("Id de la transacción obligatorio", "300168");
}




// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}
array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "$State", "op" => "eq"));
array_push($rules, array("field" => "franquicia.franquicia_id", "data" => "null", "op" => "nc")); // Recurso solo recibirá productos enlazados a una franquicia
array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));

/**
 * Configuración de agrupación y selección según el tipo de detalles requeridos
 */
$grouping = "";
$select = "";

$select = " usuario.puntoventa_id,franquicia.descripcion,usuario.pais_id,pais.pais_nom,pais.iso,usuario.mandante,usuario_punto.login,usuario_punto.nombre,transaccion_producto.*,producto.*,proveedor.*,usuario.moneda,usuario_recarga.* ";

$filtro = array("rules" => $rules, "groupOp" => "AND");


/**
 * Configuración de ordenamiento
 */
$json = json_encode($filtro);

$order = "usuario_recarga.recarga_id";
$orderType = "desc";

// Definición de variables para la consulta
$MaxRows = 1; // Número máximo de filas a recuperar
$OrderedItem = 1; // Ítem ordenado
$SkeepRows = 0; // Fila para omitir


$transacciones = $UsuarioRecarga->getUsuarioRecargasCustomPRO($select, $order, $orderType, $SkeepRows, $MaxRows, $json, true, $grouping,"",true, "LEFT OUTER JOIN franquicia_producto ON (producto.producto_id = franquicia_producto.producto_id and producto.estado = franquicia_producto.estado) LEFT OUTER JOIN franquicia ON (franquicia_producto.franquicia_id = franquicia.franquicia_id and franquicia_producto.estado = franquicia.estado)");

/**
 * Procesamiento de resultados
 */
$transacciones = json_decode($transacciones);

$final = [];
$totalm = 0;

/**
 * Procesamiento de cada transacción
 * Se agregan detalles y se formatean los datos según el tipo de consulta
 */

foreach ($transacciones->data as $key => $value) {

    $array = [];
    $array["monto"] = $value->{"usuario_recarga.valor"};
    $array["metodo"] = 'Franquicia: '.$value->{"franquicia.descripcion"};
    $array["tienda"] = $value->{"producto.descripcion"}.'-'.$value->{"usuario.mandante"};
    $array["id"] = $value->{"usuario_recarga.recarga_id"};
    $array["fecha_hora"] = $value->{"usuario_recarga.fecha_crea"};
    $array["saldo_total"]  = (string)$Usuario->getBalance();
    array_push($final, $array);
}



/*Formato de respuesta*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;

