<?php namespace Backend\sql;

use Backend\utils\RedisConnectionTrait;
use Exception;
use \PDO;

/**
 * Clase 'QueryExecutor'
 *
 * Esta clase provee herramientas para la generación de consultas a la base de datos
 *
 * Ejemplo de uso:
 * $QueryExecutor = new QueryExecutor();
 *
 *
 * @package ninguno
 * @author DT
 * @version ninguna
 * @date: 27.11.2007
 *
 */
class QueryExecutor
{

    /**
     * Realizar una consulta mediante la conexión actual
     *
     * @param Objeto $transaction transacción
     * @param String $sqlQuery consulta sql
     *
     * @return boolean $ resultado de la consulta
     */
    public static function execute($transaction, $sqlQuery)
    {


        $query = $sqlQuery->getQuery();
        //   $pos = strpos($query, "null");
        //   if ($pos != false) {
        //       if(true) {
        //         writeToFile($query);
        //       }
        //   }
        $withCache=false;


        if(
            strpos($query,'FROM categoria_mandante') !== false

            || strpos($query,'FROM clasificador') !== false
            || strpos($query,'FROM proveedor') !== false

        ){
            $withCache =false;
        }

        if($withCache){
            try {

                $redisParam = ['ex' => 7200];
                if(
                    (
                        strpos($query,'FROM usuario_configuracion') !== false

                    && (
                            strpos($query,"tipo='40'") !== false
                            || strpos($query,"tipo='42'") !== false
                            || strpos($query,"tipo='39'") !== false
                            || strpos($query,"tipo='33'") !== false
                            || strpos($query,"tipo='901'") !== false
                        )

                    )

                ){
                    $redisParam = ['ex' => 600];
                }

                $redisPrefix = 'SQL+';
                $cachedKey = $redisPrefix . $query;


                /* Conecta a Redis y recupera un valor basado en un clave generada. */
                $redis = RedisConnectionTrait::getRedisInstance(
                    true,
                    'redis-13988.c39707.us-central1-mz.gcp.cloud.rlrcp.com',
                    13988,
                    'LrWXJFKjCS9PYCnprkLA1gRCqhLEcu0D',
                    'default'
                );
                if ($redis != null) {
                    $cachedValue = ($redis->get($cachedKey));
                    if (!empty($cachedValue)) {
                        $tab  = json_decode($cachedValue, true);
                        if($tab != null){
                            return $tab;
                        }
                    }
                }
            }catch (Exception $e) {}

        }

        //$transaction = Transaction::getCurrentTransaction();
        if (!$transaction) {
            $connection = new Connection();
        } else {
            $connection = $transaction->getConnection();
        }
        if ($connection == null) {
            $connection = new Connection();
        }




        try {
            $result = $connection->executeQuery($query);
        } catch (Exception $e) {
            $errorCon =$connection->errorInfo();
            if(is_string($errorCon)){
                $errorConArray =json_decode($connection->errorInfo(),true);

                if(is_array($errorConArray)){

                    if(!in_array($errorConArray[1],array('1366'))  && strpos($errorConArray[2],'Duplicate entry') === false) {
                        $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo() . "<--" . $connection->errorInfo();
                        $Bbody = file_get_contents('php://input');
                        exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error-urg' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");
                    }
                }
            }

            $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo(). "<--" . $connection->errorInfo()  . $e->getMessage();
            $Bbody = file_get_contents('php://input');
            exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");


            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => '', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            throw new Exception("SQL Error: -->" . $query);
        }

        if ($result === false) {
            try {
                $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo();

                $Bbody = file_get_contents('php://input');
                exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

            } catch (Exception $e) {

            }

            try {
                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => '', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            throw new Exception("SQL Error: -->" . $query . "<--" . $connection->errorInfo());
        }

        $i = 0;
        $tab = array();

        while ($row = $result->fetch()) {
            $tab[$i++] = $row;
        }

        if($withCache) {

            if ($redis != null) {
                try {
                    $redis->set($cachedKey, json_encode($tab), $redisParam);

                } catch (Exception $e) {
                }
            }
        }
        // mysql_free_result($result);
        if (!$transaction) {
            $connection->close();
        }

        return $tab;
    }

    /**
     * Realizar una consulta mediante la conexión actual
     *
     * @param Objeto $transaction transacción
     * @param String $sqlQuery consulta sql
     *
     * @return boolean $ resultado de la consulta
     */
    public static function execute2($transaction, $sqlQuery)
    {
        //$transaction = Transaction::getCurrentTransaction();
        if (!$transaction) {
            $connection = new Connection();
        } else {
            $connection = $transaction->getConnection();
        }

        $query = $sqlQuery->getQuery();
        //   $pos = strpos($query, "null");
        //   if ($pos != false) {
        //       if(true) {
        //         writeToFile($query);
        //       }
        //   }
        $connection->getConnection()->setAttribute(PDO::ATTR_FETCH_TABLE_NAMES, true);
        try {
            $result = $connection->executeQuery($query);
            $connection->getConnection()->setAttribute(PDO::ATTR_FETCH_TABLE_NAMES, false);

        } catch (Exception $e) {
            $connection->getConnection()->setAttribute(PDO::ATTR_FETCH_TABLE_NAMES, false);
            $errorCon =$connection->errorInfo();
            if(is_string($errorCon)){
                $errorConArray =json_decode($connection->errorInfo(),true);
                if(is_array($errorConArray)){
                    if(strpos($query,'MAX_EXECUTION_TIME') !== false) {
                        $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo() . "<--" . $connection->errorInfo();

                        $Bbody = file_get_contents('php://input');
                        exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#error-max-execution-time' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");
                    }else {


                        if (!in_array($errorConArray[1], array( '1366')) && strpos($errorConArray[2],'Duplicate entry') === false) {
                            $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo() . "<--" . $connection->errorInfo();

                            $Bbody = file_get_contents('php://input');
                            exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error-urg' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

                        }
                    }
                }
            }
            $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo(). "<--" . $connection->errorInfo()  . $e->getMessage();

            $Bbody = file_get_contents('php://input');
            exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");


            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }


            if(strpos($query,'MAX_EXECUTION_TIME') !== false) {

                throw new Exception("SQL Error: -->" . $query,110010);
            }else{
                throw new Exception("SQL Error: -->" . $query . $e->getMessage());
            }

        }


        if (!$result) {
            try {
                $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo();

                $Bbody = file_get_contents('php://input');
                exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

            } catch (Exception $e) {

            }

            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            throw new Exception("SQL Error: -->" . $query . "<--" . $connection->errorInfo());
        }

        $i = 0;
        $tab = array();

        while ($row = $result->fetch()) {
            $tab[$i++] = $row;
        }

        // mysql_free_result($result);

        if (!$transaction) {
            $connection->close();
        }

        return $tab;
    }

    /**
     * Realizar una consulta mediante update a la conexión actual
     *
     * @param Objeto $transaction transacción
     * @param String $sqlQuery consulta sql
     *
     * @return boolean $ resultado de la consulta
     */
    public static function executeUpdate($transaction, $sqlQuery, $insert = "")
    {
        //$transaction = Transaction::getCurrentTransaction();

        if (!$transaction) {
            $connection = new Connection();
        } else {
            $connection = $transaction->getConnection();
        }
        if ($connection == null) {
            $connection = new Connection();
        }
        if (!$connection->inTransaction()) {
            try{
                $connection->beginTransaction();

            }catch (Exception $e){
                $traceString = print_r($e->getTrace(), true);  // El segundo parámetro true devuelve el resultado como una cadena
                $message = '*SQL Error* ' . 'CONNECTION' . ' ' . $e->getMessage();

                $Bbody = file_get_contents('php://input');
                exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message.$traceString) . "' '#virtualsoft-cron-error-urg' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");
                throw $e;
            }
        }

        $query = $sqlQuery->getQuery();
        try {
            $result = $connection->executeQuery($query);

        } catch (Exception $e) {
            if($_REQUEST['isDebug']=='1'){
                print_r($e);
            }

            $errorCon =$connection->errorInfo();
            if(is_string($errorCon)){
                $errorConArray =json_decode($connection->errorInfo(),true);

                if(is_array($errorConArray)){

                    if(!in_array($errorConArray[1],array('1366')) && strpos($errorConArray[2],'Duplicate entry') === false){

                        $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo(). "<--" . $connection->errorInfo()  . $e->getMessage();

                        $Bbody = file_get_contents('php://input');
                        exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error-urg' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");
                    }
                }
            }

            $message = '*SQL Error* '  . ' ' . $connection->errorInfo(). "<--" . $connection->errorInfo()  . $e->getMessage() . ' SQL: '. $query;

            $Bbody = file_get_contents('php://input');
            exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

            try {
                $connection->rollBack();
            }catch (Exception $e){

            }

            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            throw new Exception("SQL Error: -->" . $query);
        }


        if ($result === false) {

            try {
                $connection->rollBack();
            }catch (Exception $e){

            }
            try {
                $message = '*SQL Error* ' . $query . ' ' . $connection->errorInfo();

                $Bbody = file_get_contents('php://input');
                exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

            } catch (Exception $e) {

            }

            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            throw new Exception("SQL Error: -->" . $query . "<--" . $connection->errorInfo());
        }

        if ($insert == "") {
            if($_ENV['enabledCRMQuery']) {
                $transaction->searchCRMQuery($query);
            }
            return $result->rowCount();
        } else {
            $res = $connection->getConnection()->lastInsertId();
            if($_ENV['enabledCRMQuery']) {
                $transaction->searchCRMQuery($query, $res);
            }
            return $res;
        }

    }


    /**
     * Realizar una consulta mediante insert a la conexión actual
     *
     * @param Objeto $transaction transacción
     * @param String $sqlQuery consulta sql
     *
     * @return boolean $ resultado de la consulta
     */
    public static function executeInsert($transaction, $sqlQuery)
    {
        return QueryExecutor::executeUpdate($transaction, $sqlQuery, "insert");
    }

    /**
     * Realizar una consulta mediante la conexión actual
     *
     * @param Objeto $transaction transacción
     * @param String $sqlQuery consulta sql
     *
     * @return boolean $ resultado de la consulta
     */
    public static function queryForString($transaction, $sqlQuery)
    {

        if (!$transaction) {
            $connection = new Connection();
        } else {
            $connection = $transaction->getConnection();
        }

        if ($connection == null) {
            $connection = new Connection();
        }

        try {
            $result = $connection->executeQuery($transaction, $sqlQuery->getQuery());

        } catch (Exception $e) {
            $errorCon =$connection->errorInfo();
            if(is_string($errorCon)){
                $errorConArray =json_decode($connection->errorInfo(),true);

                if(is_array($errorConArray)){

                    if(!in_array($errorConArray[1],array('1366')) && strpos($errorConArray[2],'Duplicate entry') === false){
                        $message = '*SQL Error* ' . $sqlQuery->getQuery() . ' ' . $connection->errorInfo(). "<--" . $connection->errorInfo()  . $e->getMessage();

                        $Bbody = file_get_contents('php://input');
                        exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error-urg' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

                    }
                }
            }

            $message = '*SQL Error* ' . $sqlQuery->getQuery(). "<--" . $connection->errorInfo(). "<--" . $connection->errorInfo()  . $e->getMessage();

            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            $Bbody = file_get_contents('php://input');
            exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");


            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }


            throw new Exception("SQL Error: -->" . $sqlQuery->getQuery() . "<--" . $connection->errorInfo());
        }

        if (!$result) {
            try {
                $message = '*SQL Error* ' . $sqlQuery->getQuery();

                $Bbody = file_get_contents('php://input');
                exec("php -f ".__DIR__."../imports/Slack/message.php '" . base64_encode($message) . "' '#virtualsoft-cron-error' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");

            } catch (Exception $e) {

            }

            try {

                
                // Aquí puedes hacer lo que quieras con la salida capturada
                $arrayREQGEN = array(
                    "TYPE" => "SQLERROR", // Define el tipo según tu contexto
                    "MICROTIME" => 0,
                    "SERVER" => json_encode($_SERVER),
                    "USERID" => isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '-', // Si usas sesiones y tienes un ID de usuario
                    "DATE" => date('Y-m-d H:i:s'),
                    "DEVICE" => '',
                    "BROWSER_VERSION" => '',
                    "NETWORK_TYPE" => 'Unknown', // Necesitarías una manera específica de determinar esto
                    "URL" => $URLPP12,
                    "GETPARAMS" => json_encode($_GET),
                    "POSTBODY" => file_get_contents('php://input'),
                    "RESPONSE" => $message,
                    "TIMERESPONSE" => 0,
                    "STATUSCODE" => 408
                );

                fwriteCustom('RQQ_log_'.'SQLERROR'.'_'  . date("Y-m-d") . '.log', json_encode($arrayREQGEN) . "\n");
            } catch (Exception $e) {

            }

            throw new Exception("SQL Error: -->" . $sqlQuery->getQuery() . "<--" . $connection->errorInfo());
        }

        $row = $result->fetch(PDO::FETCH_OBJ);

        return $row;

    }

}
