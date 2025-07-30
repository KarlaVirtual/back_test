<?php namespace Backend\sql;
use \PDO;
/** 
* Clase 'ConnectionFactory'
* 
* Implementación del patrón Factory bajo la clase Connection
* 
* Ejemplo de uso: 
* $ConnectionFactory = new ConnectionFactory();
*   
* 
* @package ninguno 
* @author DT
* @version ninguna
* @date: 27.11.2007
*/
class ConnectionFactory
{

    /**
     * Metodo para obtener la conexión
     *
     * @return Objeto $ conexión
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     * @static
     */
    static public function getConnection()
    {
        $conn = null;

        //$conn = new PDO("mysql:host=" . ConnectionProperty::getHost() . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());
        //$conn = new PDO("mysql:unix_socket=/tmp/proxysql.sock;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());

        if($_ENV['ENV_TYPE'] =='prod') {

            $conn = new PDO("mysql:host=" . ConnectionProperty::getHost() . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ));
        }else{

            $conn = new PDO("mysql:host=" . ConnectionProperty::getHost() . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());
        }
        //$conn = new PDO('mysql:unix_socket=/tmp/proxysql.sock;dbname=casino', 'casino', '12Hur12b*');

        //$conn->setAttribute(PDO::ATTR_TIMEOUT, 10);

        try{

            if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                $conn->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
            }

            if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                $conn->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
            }
            if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
               // $conn->exec('SET SESSION innodb_lock_wait_timeout = 5;');
            }
            if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                   // $conn->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
            }
            if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                $conn->exec("SET NAMES utf8mb4");
            }
        }catch (\Exception $e){

        }


        /* return $this->conn;
         $conn = mysql_connect(ConnectionProperty::getHost(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());
         mysql_select_db(ConnectionProperty::getDatabase());
         if(!$conn){
             throw new Exception('could not connect to database');
         }*/

        return $conn;
    }

    /**
     * Método para cerrar una conexión pasado como parámetro
     * 
     * @param Objeto $connection conexión
     */
    static public function close($connection)
    {
        $connection=null;
       // mysql_close($connection);
    }
}

?>

