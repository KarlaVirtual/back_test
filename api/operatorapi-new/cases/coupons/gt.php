<?php


use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;


/**
 * Este script maneja la generación de cupones para transacciones específicas.
 * 
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->transactionId ID de la transacción.
 * @param string $params->key Clave secreta para validar la operación.
 * @param string $params->value Valor asociado a la transacción.
 * @param string $params->partner Identificador del mandante.
 * @param int $params->country Código del país (debe ser 60).
 * 
 * @return array $response Respuesta que incluye:
 *  - string $transactionId ID del registro de log transaccional.
 *  - array $coupon Lista de códigos de cupones generados.
 * 
 * @throws Exception Si alguno de los siguientes errores ocurre:
 *  - "Field: Key empty" (código 2): Cuando el campo "key" está vacío.
 *  - "Field: value empty" (código 2): Cuando el campo "value" está vacío.
 *  - "Field: parnet empty" (código 2): Cuando el campo "partner" está vacío.
 *  - "Field: country empty" (código 2): Cuando el campo "country" está vacío.
 *  - "Código de país incorrecto" (código 10018): Cuando el código de país no es 60.
 *  - "Key invalida" (código 1): Cuando la clave proporcionada no coincide con la clave secreta.
 */

/* inicializa variables y registra la URI de la solicitud en un log. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información recibida y la guarda en un archivo de log. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$transactionId = $params->transactionId;

/* verifica si el campo "key" está vacío y genera una excepción. */
$key = $params->key;
$value = $params->value;
$mandante = $params->partner;
$country = $params->country;

if ($key == "") {
    throw new Exception("Field: Key empty", "2");

}


/* if ($transactionId == "") {
     throw new Exception("Field: transactionId empty", "2");

 }*/

/* Verifica si los campos 'value' y 'mandante' están vacíos, lanzando excepciones si lo están. */
if ($value == "") {
    throw new Exception("Field: value empty", "2");

}
if ($mandante == "") {
    throw new Exception("Field: parnet empty", "2");

}

/* verifica si el país está vacío o es inválido, lanzando excepciones. */
if ($country == "") {
    throw new Exception("Field: country empty", "2");

}

if ($country != 60) {
    throw new Exception("Código de país incorrecto", "10018");
}


/* Código inicializa una transacción con configuración de filas y clave secreta. */
$TransaccionProducto = new TransaccionProducto();

$SkeepRows = 0;
$MaxRows = 1000000;
$array = array();
$SecretKey = "MihPXlw2eCX%WGa3";

/* Se crean instancias de Proveedor, ConfigurationEnvironment y Producto en el código. */
$Proveedor = new Proveedor("", "GT");
$ConfigurationEnvironment = new ConfigurationEnvironment();

$Producto = new Producto("", "CUPONESGT", $Proveedor->proveedorId);

if ($key == $SecretKey) {


    /* Se crea y configura un objeto de transacción de producto en MySQL. */
    $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
    $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

    $TransaccionProducto = new TransaccionProducto();
    $TransaccionProducto->setProductoId($Producto->productoId);
    $TransaccionProducto->setUsuarioId(0);

    /* Se configuran atributos de un objeto TransaccionProducto con distintos valores. */
    $TransaccionProducto->setValor($value);
    $TransaccionProducto->setEstado('P');
    $TransaccionProducto->setTipo('T');
    $TransaccionProducto->setExternoId(0);
    $TransaccionProducto->setEstadoProducto('E');
    $TransaccionProducto->setMandante($mandante);

    /* Se inserta un producto en la transacción y se genera un código cifrado. */
    $TransaccionProducto->setFinalId(0);
    $TransaccionProducto->setFinalId(0);
    $TransaccionProducto->setUsutarjetacredId(0);

    $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

    $codigoCupon = $ConfigurationEnvironment->encryptCusNum(intval($TransaccionProducto->transproductoId));


    /* Agrega un cupón a un array y registra un log de transacción. */
    array_push($array, $codigoCupon);

    $TransprodLog = new TransprodLog();
    $TransprodLog->setTransproductoId($transproductoId);
    $TransprodLog->setEstado('P');
    $TransprodLog->setTipoGenera('A');

    /* establece propiedades en un objeto TransprodLog y crea un DAO para MySQL. */
    $TransprodLog->setComentario('Cupon generado por ' . $transproductoId);
    $TransprodLog->setTValue("");
    $TransprodLog->setUsucreaId(0);
    $TransprodLog->setUsumodifId(0);

    $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);

    /* Inserta un registro, confirma la transacción y devuelve el ID del registro creado. */
    $TransprodlogId = $TransprodLogMySqlDAO->insert($TransprodLog);

    $Transaction->commit();


    $response["transactionId"] = $TransprodlogId;

    /* Asignación de un array a la clave "coupon" en el array de respuesta. */
    $response["coupon"] = $array;


} else {
    /* Lanza una excepción si la clave es inválida, con un mensaje específico. */

    throw new Exception("Key invalida", "1");

}