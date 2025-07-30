<?php namespace mKomorowski\Betfair;
/** 
* Clase 'Database'
* 
* Esta clase provee las propiedades de conexión a la base de datos
* 
* Ejemplo de uso: 
* $Database = new Database();
*   
* 
* @package ninguno 
* @author DT
* @version ninguna
* @access public 
* @see no
* @date: 27.11.2007
* 
*/
class Database
{

    /**
    * Host de conexión a la base de datos
    *
    * @var String
    * @access private
    * @static
    */
    private $host = "localhost";

    /**
    * Nombre de la base de datos
    *
    * @var String
    * @access private
    * @static
    */
    private $db_name = "casino";
    
    /**
    * Usuario de conexión a la base de datos
    *
    * @var String
    * @access private
    * @static
    */
    private $username = "casino";
    
    /**
    * Contraseña de conexión a la base de datos
    *
    * @var String
    * @access private
    * @static
    */
    private $password = "12Hur12b*";
    
    /**
    * Representación del objeto de conexión a la base de datos
    *
    * @var Objeto
    * @access public
    * @static
    */
    public $conn;


    /**
     * Metodo para obtener la conexión
     *
     * @return Objeto $conn connexión a la base de datos
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     * @static
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Database connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

}