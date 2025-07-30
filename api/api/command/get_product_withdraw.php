<?php
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;

/**
 * Obtención de los productos de payout
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la operación.
 * @param int $params->site_id ID del sitio.
 * @param object $json Objeto JSON que contiene la sesión y otros datos.
 * @param object $json->session Objeto que contiene la sesión del usuario.
 * @param object $json->session->usuario Usuario de la sesión.
 * @param int $SkeepRows Número de filas a omitir en la consulta.
 * @param int $OrderedItem Número de ítems ordenados.
 * @param int $MaxRows Número máximo de filas a obtener.
 * @return array $response Respuesta estructurada con código, identificador y datos finales.
 * @throws Exception Si el celular no está verificado.
 */

// Obtiene los parámetros del objeto JSON
$params = $json->params;
// Asigna el ID del sitio a la variable $site_id
$site_id = $params->site_id;
// Crea una instancia de UsuarioMandante utilizando el usuario de la sesión
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
// Crea una instancia de Usuario utilizando el usuario mandante
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

// Crea una instancia de Clasificador para obtener el tipo de detalle relacionado con la verificación de teléfono
$Clasificador = new Clasificador('', 'PHONEVERIFICATION');
// Obtiene el ID del clasificador
$tipoDetalle = $Clasificador->getClasificadorId();

try {
    // Intenta crear una instancia de MandanteDetalle con los parámetros proporcionados
    $MandanteDetalle = new MandanteDetalle('', $site_id, $tipoDetalle, $Usuario->paisId, '', 3);
    // Verifica si el estado del MandanteDetalle es 'A'
    if ($MandanteDetalle->estado == 'A') {
        // Verifica si el celular del usuario está verificado
        if ($Usuario->verifCelular == 'N') {
            // Lanza una excepción si el celular no está verificado
            throw new Exception('Celular no verificado.', 100095);
        }
    }
} catch (Exception $e) {
    // Si la excepción lanzada es de tipo 100095, vuelve a lanzarla
    if ($e->getCode() == 100095) throw $e;
}

// Verifica si $SkeepRows no está definido, y lo inicializa a 0 si es necesario
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

// Verifica si $OrderedItem no está definido, y lo inicializa a 100 si es necesario
if ($OrderedItem == "") {
    $OrderedItem = 100;
}

// Verifica si $MaxRows no está definido, y lo inicializa a 100 si es necesario
if ($MaxRows == "") {
    $MaxRows = 100;
}



$rules = [];

/**
 * Se define un arreglo de reglas para filtrar productos.
 *
 * Estas reglas se aplicarán a la consulta que recupera productos de la base de datos.
 */

//$Proveedor = new \Backend\dto\Proveedor('', 'GLOBOKASRETIROS');

array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "proveedor.abreviado", "data" => "'GLOBOKASRETIROS','CONEKTARETIROS'", "op" => "in"));
array_push($rules, array("field" => "producto.pago_terceros", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "producto_mandante.mandante", "data" => $site_id, "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonproduct = json_encode($filtro);

$Producto = new \Backend\dto\Producto();

$productos = $Producto->getProductosCustomMandante("producto.*,producto_mandante.* ", "producto.producto_id", "asc", $SkeepRows, $MaxRows, $jsonproduct, true,$site_id);
$productos = json_decode($productos);
$final=array();

foreach ($productos->data as $key => $value) {

    $array = [];

    $array["name"] = $value->{"producto.descripcion"};
    $array["value"] = $value->{"producto.producto_id"};
    $array["min"] = $value->{"producto_mandante.min"};
    $array["max"] = $value->{"producto_mandante.max"};
    $array["icon"] = $value->{"producto.image_url"};

    array_push($final, $array);
}

/**
 * Se verifica si el usuario tiene un mandante específico y país.
 *
 * Si se cumplen las condiciones, se agrega un producto "Tiendas TAMBO" al arreglo final.
 */
if ($Usuario->mandante == 0 && $Usuario->paisId == 173) {
    $array["value"] = "5758546";
    $array["name"] = 'Tiendas TAMBO';
    $array["min"] = '50';
    $array["max"] = '100';
    $array["icon"] = "https://images.virtualsoft.tech/m/msj0212T1706185558.png";
    array_push($final,$array);
}

/**
 * Inicializa un array de respuesta con código, identificador y datos finales.
 *
 * @var array $response Array que almacena la respuesta estructurada.
 * @var int $response["code"] Código de respuesta, 0 indica éxito.
 * @var mixed $response["rid"] Identificador de solicitud proveniente del objeto JSON.
 * @var mixed $response["data"] Datos finales que se desean retornar.
 */


$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $final;

