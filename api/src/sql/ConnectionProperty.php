<?php namespace Backend\sql;
/** 
* Clase 'ConnectionProperty'
* 
* Esta clase provee las propiedades de conexión a la base de datos en un entorno local
* 
* Ejemplo de uso: 
* $ConnectionProperty = new ConnectionProperty();
*	
* 
* @package ninguno 
* @author Daniel Tamayo
* @version 1.0
* @date: 27.11.2007
*/
class ConnectionProperty
{

    /**
   	* Host de conexión a la base de datos
   	*
   	* @var String
   	* @access private
   	* @static
   	*/
	private static $host;

    /**
   	* Usuario de conexión a la base de datos
   	*
   	* @var String
   	* @access private
   	* @static
   	*/
	private static $user ;

    /**
   	* Contraseña de conexión a la base de datos
   	*
   	* @var String
   	* @access private
    * @static
   	*/
	private static $password;

    /**
   	* Nombre de la base de datos
   	*
   	* @var String
   	* @access private
   	* @static
   	*/
	private static $database;

	public function __construct()
	{
		self::$host = $_ENV['DB_HOST'];
		self::$user = $_ENV['DB_USER'];
		self::$password = $_ENV['DB_PASSWORD'];
		self::$database = $_ENV['DB_NAME'];

	}


	/**
     * Metodo para obtener el host de conexión
     *
     * @return String $ host
     */
	public static function getHost()
	{
		return $_ENV['DB_HOST'];
	}

    /**
     * Metodo para obtener el usuario de conexión
     *
     * @return String $ user
     */
	public static function getUser()
	{
		return $_ENV['DB_USER'];
	}

    /**
     * Metodo para obtener la contraseña de conexión
     *
     * @return String $ password
     */
	public static function getPassword()
	{
		return $_ENV['DB_PASSWORD'];
	}

    /**
     * Metodo para obtener la base de datos de conexión
     *
     * @return String $ database
     */
	public static function getDatabase()
	{
		return $_ENV['DB_NAME'];
	}
}
?>
