<?php namespace Backend\sql;
/** 
* Clase 'Connection'
* 
* Esta clase provee un objeto de conexión a la base de datos
* 
* Ejemplo de uso: 
* $Connection = new Connection();
*   
* 
* @author Daniel Tamayo
* @package ninguno 
* @version ninguna
* @date: 27.11.2007
*/
class Connection
{

    /**
     * Representación del objeto conexión
     *
     * @var objeto
     * @access private
     */
    private $connection;

    /**
     * Variable para saber si se debe hacer un beginTransaction
     *
     * @var int
     * @access public
     */
    public $isBeginTransaction=1;


    /**
     * Constructor de clase
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct()
    {
        if($_ENV["enabledConnectionGlobal"] != null && $_ENV["enabledConnectionGlobal"] == 1){
            if($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"]   != '' ){
                $this->connection = ($_ENV["connectionGlobal"])->connection;
                $this->isBeginTransaction = ($_ENV["connectionGlobal"])->isBeginTransaction;

            }else{
                $this->connection = ConnectionFactory::getConnection();
                $_ENV["connectionGlobal"]=$this;
            }
        }else{
            $this->connection = ConnectionFactory::getConnection();
        }


        //$this->connection = ConnectionFactory::getConnection();
        //$this->connection ->beginTransaction();
    }
    
    /**
     * Inicia una transacción en la conexión de base de datos si no hay una transacción activa.
     *
     * @return void
     */
    public function beginTransaction(){
        if(!$this->connection->inTransaction()){

            $this->connection->beginTransaction();
            $this->isBeginTransaction=2;
            if($_REQUEST['test']=='1'){
                print_r(' JERSON4 AQUI');
                print_r(' Connection');
                print_r($this);
            }
        }
    }


    /**
     * Verifica si hay una transacción activa en la conexión actual.
     *
     * @return bool Devuelve true si hay una transacción activa, de lo contrario false.
     */
    public function inTransaction(){
       return $this->connection->inTransaction();
    }

    /**
     * Metodo para obtener la conexión
     *
     * @return Objeto $ conexión
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Método para crear y asignar una conexión nueva mediante el objeto 'connection'
     */
    public function Connection()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    /**
     * Método para cerrar la conexión
     */
    public function close()
    {
        ConnectionFactory::close($this->connection);
    }

    /**
     * Método para realizar una consulta mediante la conexión actual
     *
     * @param String $sql consulta sql
     * @return boolean $ resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function executeQuery($sql)
    {
        return $this->connection->query($sql);
        //return mysql_query($sql, $this->connection);
    }

    /**
     * Método para obtener los errores de una conexión
     *
     * @return Array $ lista de errores
     */
    public function errorInfo()
    {
        return json_encode($this->connection->errorInfo());
        //return mysql_query($sql, $this->connection);
    }

    /**
     * Método para hacerle commit a la conexión actual
     */
    public function commit()
    {
         $this->connection->commit();
        $this->isBeginTransaction=1;
        if($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"]   != '' ){
                $_ENV["connectionGlobal"]=$this;
            }
    }

    /**
     * Método para hacerle rollback a la conexión actual
     */
    public function rollBack()
    {
        $this->isBeginTransaction=1;

        $this->connection->rollBack();
        if($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"]   != '' ){
            $_ENV["connectionGlobal"]=$this;
        }
    }


    /**
     * Método para obtener un atributo requerido de una conexión
     *
     * @param String $string atributo requerido
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getAttr($string)
    {
        $this->connection->getAttribute($string);
    }


    /**
     * Establece la conexión de base de datos.
     *
     * @param mixed $conn La conexión a establecer.
     * @return void
     */
    public function setConnection($conn)
    {
        $this->connection = $conn;
    }

}

?>

