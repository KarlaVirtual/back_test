<?php
require_once __DIR__ . '../../../vendor/autoload.php';

/**
 * command/testing-redis
 *
 * Test sobre conexión con redis
 *
 * @param no
 *
 * @return object $response contiene la respuesta del recurso si existe o no una conexión con redis
 *   - *error* (string): Mensaje de error
 *   - *message* (string): Contiene el mensaje que se muestra en la vista.
 *
 *
 *  Objeto en caso de error:
 *   $response["error"] = $e->getMessage();
 *   $response["message"] = "Error!";
 *
 * @throws Exception Si no existe o no se logra una conexión con redis
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Establece una variable de entorno y verifica el estado de conexión a Redis. */
$_ENV["debugFixed"] = "1";
try {
    $Test = new \Backend\dto\Test();
    $response = $Test->redisConnectionStatus();

    $response["message"] = "Redis checked: settedValue, getValue y deletedValue must be true";
} catch (\Exception $e) {
    /* Manejo de excepciones que captura errores y los almacena en un array de respuesta. */

    print_r($e);
    $response["error"] = $e->getMessage();
    $response["message"] = "Error!";
}
