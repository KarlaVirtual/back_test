<?php namespace Backend\sql;
use Exception;
/** 
* Clase 'SqlQuery'
* 
* Esta clase provee herramientas para la generación de consultas a la base de datos
* 
* Ejemplo de uso: 
* $SqlQuery = new SqlQuery();
*   
* 
* @package ninguno 
* @author Daniel Tamayo
* @version ninguna
* @access public
* @date: 27.11.2007
 * @version 1.0
* 
*/
class SqlQuery
{

    /**
   	* String de consulta
   	*
   	* @var string
   	* @access public
   	*/
	var $txt;

    /**
   	* Representación de la lista de valores
   	*
   	* @var array
   	* @access public
   	*/
	var $params = array();

    /**
   	* Indice del valor
   	*
   	* @var int
   	* @access public
   	*/
	var $idx = 0;

    /**
    * Constructor de clase
    *
	* @param String $txt texto
	*
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($txt)
    {
        $this->txt = $txt;
    }

	/**
	 * Constructor
	 *
	 * @param String $txt texto
	 */
	function SqlQuery($txt)
	{
		$this->txt = $txt;
	}

	/**
	 * Método que cambia los signos de interrogación por
	 * el valor que se pasa como parámetro
	 *
	 * @param String $value valor en cuestión
	 */
	public function setString($value)
	{
		//$value = mysql_real_escape_string($value);
		$this->params[$this->idx++] = "'".$value."'";
	}
	
	/**
	 * Método que cambia los signos de interrogación por
	 * el valor que se pasa como parámetro
	 *
	 * @param String $value valor en cuestión
	 */
	public function set($value)
	{
        $value = $this->DepurarCaracteres($value);
		//$value = mysql_real_escape_string($value);
		$this->params[$this->idx++] = "'".$value."'";
	}

/**
     * Método que depura caracteres específicos en un texto.
     *
     * @param string $texto_depurar El texto a depurar.
     * @return string El texto depurado.
     */
    function DepurarCaracteres($texto_depurar)
    {
        $texto_depurar = str_replace("\'", "'", $texto_depurar);
        $texto_depurar = str_replace("'", "\'", $texto_depurar);
        //$texto_depurar = str_replace('"', "", $texto_depurar);

        $texto_depurar = str_replace("�", "", $texto_depurar);
        $texto_depurar = str_replace("`", "", $texto_depurar);

        $texto_depurar = str_replace("�", "", $texto_depurar);
        $texto_depurar = str_replace("�", "", $texto_depurar);

        $texto_depurar = str_replace("�", "", $texto_depurar);
        $texto_depurar = str_replace("~", "", $texto_depurar);

        return $texto_depurar;
    }
	
	/**
	 * Método que cambia los signos de interrogación por
	 * el valor que se pasa como parámetro
	 *
	 * @param String $value valor en cuestión
	 */
    public function setSIN($value)
    {
        //$value = mysql_real_escape_string($value);
        $this->params[$this->idx++] = "".$value."";
    }
	

	/**
	 * Método que valida el número pasado como parámetro
	 * y cambia los signos de interrogación por dicho valor
	 *
	 * @param int $value valor en cuestión
	 */
	public function setNumber($value)
	{
		
		if($value===null)
		{
			$this->params[$this->idx++] = "null";
			return;
		}
		
		if(!is_numeric($value))
		{
			throw new Exception($value.' is not a number');
		}

		$this->params[$this->idx++] = "'".$value."'";
	}

	/**
	 * Obtener el query
	 *
	 * @return String $sql query
	 */
	public function getQuery()
	{
		if($this->idx==0)
		{
			return $this->txt;
		}
		
		$p = explode("?", $this->txt);
		
		$sql = '';
		
		for($i=0;$i<=$this->idx;$i++)
		{
		
			if($i>=oldCount($this->params))
			{
				$sql .= $p[$i];
			}else
			{
                if("null"===$this->params[$i])
                {
                    
                    $columnName = $this->getColumnName($p[$i]);
                    
                    if(isset($columnName))
                    {
                        $sql .= $columnName."is ".$this->params[$i];
                    
                    }
                    else
                    {
                        $sql .= $p[$i].$this->params[$i];
                    }
                }
                else
                {
                    $sql .= $p[$i].$this->params[$i];
                }
			}
		}

		return $sql;
	}
      
	/**
	 * Obtener el nombre de la columna
	 *
	 * @param String $textCopy textoS
	 * @return String $columnName nombre de la columna
	 *
	 * @access private
	 */  
    private function getColumnName($textCopy)
    {
                $trimmedUppercaseSql = trim(strtoupper($this->txt));

                if($this->startsWith($trimmedUppercaseSql, "SELECT "))
                {
                
                    $rightTrimmedTextCopy = rtrim($textCopy, " ");
                
                    $columnName = rtrim($rightTrimmedTextCopy, "=");
                
                    if(strlen($columnName) !== strlen($rightTrimmedTextCopy))
                    {
                        return $columnName;
                    }
                
                }
                
                return null;
    }
	
	/**
	 * Function que remplaza un caracter de un string
	 *
	 * @param String $str string viejo
	 * @param String $old caracter viejo
	 * @param String $new caracter nuevo
	 * @return String $str string nuevo
	 *
	 * @access private
	 */
	private function replaceFirst($str, $old, $new)
	{
		$len = strlen($str);
		for ($i=0;$i<$len;$i++)
		{
			if($str[$i]==$old)
			{
				$str = substr($str,0,$i).$new.substr($str,$i+1);
				return $str;
			}
		}

		return $str;
	}
        
    /**
     * Función que consulta si un string comienza con un texto
     * obtenido por medio de un parámetro
     * 
     * @param String $haystack string
     * @param String $needle texto en cuestión
     * @return boolean $ verdadero si la string comienza con dicho texto 
     */
    private function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
     * Función que consulta si un string termina con un texto
     * obtenido por medio de un parámetro
     * 
     * @param String $haystack string
     * @param String $needle texto en cuestión
     * @return boolean $ verdadero si la string termina con dicho texto 
     */
    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
            
        if ($length == 0) 
        {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }        

}
?>