<?php
namespace Backend\utils;

use Backend\dto\ConfigurationEnvironment;
use Redis;
use Exception;

/**
 *Clase que contiene métodos para la conexión a Redis.
 *
 * @author Desconocido
 *@package Ninguno
 *@category Ninguno
 *@version 1.0
 *@since Desconocido
 */
trait RedisConnectionTrait
{
    /**
     * Instancia de Redis
     * @var mixed $redisInstance
     */
    private static $redisInstance = null;



    /**
     * Obtiene una instancia de Redis.
     *
     * @param bool $enabled Indica si la conexión a Redis está habilitada. Por defecto es false.
     * @param string $redisHost Dirección del host de Redis. Por defecto es un host TLS en Oracle Cloud.
     * @param int $redisPort Puerto del servidor Redis. Por defecto es 6379.
     * @param string $pass Contraseña para autenticar la conexión a Redis. Por defecto es una cadena específica.
     * 
     * @return Redis|null Devuelve una instancia de Redis si está habilitada, o null si no está habilitada o si ocurre un error.
     * 
     * @throws Exception Si ocurre un error al intentar conectarse a Redis.
     */
    public static function getRedisInstance($enabled=false,$redisHost = 'redis-18995.c39707.us-central1-mz.gcp.cloud.rlrcp.com', $redisPort = 18995, $pass = 'b8zIfRaZeYGLc73EaZeDmf1SzSl7RAei', $user = 'default')
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if($ConfigurationEnvironment->isDevelopment()){
            $redisPort = $_ENV['REDIS_PORT'];
            $redisHost = $_ENV['REDIS_HOST'];
            $pass= $_ENV['REDIS_PASSWORD'];
            $user= $_ENV['REDIS_USER'];
        }
        if(!$enabled){
            return null;
        }
        if($ConfigurationEnvironment->isDevelopment()){

            if (self::$redisInstance === null) {
                try {
                    self::$redisInstance = new Redis();
                    //self::$redisInstance->setOption(Redis::OPT_CONNECT_TIMEOUT, 2);
                    self::$redisInstance->pconnect($redisHost, $redisPort, 2);
                    //self::$redisInstance->auth($pass);

                    if($user !=''){
                        // Autenticación con ACL (usuarios)
                        self::$redisInstance->auth([$user, $pass]);
                    }

                } catch (Exception $e) {
                    if ($_ENV["debugFixed"] == "1") print_r($e);
                    self::$redisInstance = null;
                }
            }
        }else{

            if (self::$redisInstance === null) {
                try {
                    self::$redisInstance = new Redis();
                    //self::$redisInstance->setOption(Redis::OPT_CONNECT_TIMEOUT, 2);
                    self::$redisInstance->pconnect($redisHost, $redisPort, 2);
                    if($user !=''){
                        // Autenticación con ACL (usuarios)
                        self::$redisInstance->auth([$user, $pass]);
                    }

                    //self::$redisInstance->auth($pass);
                } catch (Exception $e) {
                    if ($_ENV["debugFixed"] == "1") print_r($e);
                    self::$redisInstance = null;
                }
            }
        }

        return self::$redisInstance;
    }

    /**
         * Establece un valor en Redis para una clave dada.y redireccionemos todo el trafico porque esos estan d
         *
         * @param string $key La clave para establecer el valor.
         * @param mixed $value El valor a establecer.
         * @param mixed $param Parámetros adicionales para la configuración del valor.
         * @return bool True si se establece correctamente, false en caso contrario.
         */
        public function setKey($key, $value, $param)
        {
            $redis = self::getRedisInstance();
            if ($redis === null) {
                return false;
            }

            return $redis->set($key, $value, $param);
        }

        /**
         * Obtiene el valor de Redis para una clave dada.
         *
         * @param string $key La clave para obtener el valor.
         * @return mixed El valor asociado a la clave, o false si no se encuentra.
         */
        public function getKey($key)
        {
            $redis = self::getRedisInstance();
            if ($redis === null) {
                return false;
            }

            return $redis->get($key);
        }

        /**
         * Elimina una clave de Redis.
         *
         * @param string $key La clave a eliminar.
         * @return int El número de claves eliminadas, o false si ocurre un error.
         */
        public function deleteKey($key)
        {
            $redis = self::getRedisInstance();
            if ($redis === null) {
                return false;
            }

            return $redis->del($key);
        }

        /**
         * Actualiza el valor en caché para una clave dada si existe.
         *
         * @param string $key La clave a actualizar.
         * @param mixed $newValue El nuevo valor a establecer.
         * @param mixed|null $param Parámetros adicionales para la configuración del valor.
         * @return bool True si se actualiza correctamente, false en caso contrario.
         */
        public function updateCache($key, $newValue, $param = null)
        {
            $redis = self::getRedisInstance();
            if ($redis === null) {
                return false;
            }

            if ($redis->exists($key)) {
                return $this->setKey($key, $newValue, $param);
            }
        }

        /**
         * Cierra la conexión a Redis.
         */
        public static function closeConnection()
        {
            if (self::$redisInstance !== null) {
                self::$redisInstance->close();
            }
        }
}