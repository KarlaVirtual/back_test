<?php namespace Backend\dto;

use Backend\utils\RedisConnectionTrait;

/**
* Clase Test usada para crear y probar la conexión a redis.
*/
class Test
{
    use RedisConnectionTrait;

    /**
     * Verifica el estado de la conexión a Redis realizando las siguientes operaciones:
     * - Establece una clave con un valor y un tiempo de expiración.
     * - Recupera el valor de la clave establecida.
     * - Elimina la clave.
     *
     * @return array Un arreglo asociativo con los resultados de las operaciones:
     *               - "settedValue": booleano indicando si la clave fue establecida correctamente.
     *               - "getValue": booleano indicando si el valor de la clave fue recuperado correctamente.
     *               - "deletedValue": booleano indicando si la clave fue eliminada correctamente.
     */
    public function redisConnectionStatus()
    {
        $key = "testing-key";
        $value = "random-value";

        $result = $this->setKey($key, $value, ['ex' => 86400]);
        $response["settedValue"] = $result != false;

        $getValue = $this->getKey($key);
        $response["getValue"] = $result != false;

        $result = $this->deleteKey($key);
        $response["deletedValue"] = $result != false;

        return $response;
    }
}
