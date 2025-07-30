<?php
/** 
* Clase 'PaysafeLogger'
* 
* Esta clase provee funciones para la api 'PaysafeLogger'
* 
* Ejemplo de uso: 
* $PaysafeLogger = new PaysafeLogger();
*   
* 
* @package ninguno 
* @author marcoeble
* @version ninguna
* @access public 
* @see no
* 
*/
class PaysafeLogger
{

    /**
    * Representación de 'filename'
    *
    * @var string
    * @access private
    */    
    private $filename = "";

    /**
     * Método constructor
     *
     * @param String $filename filename
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($filename = "log.txt")
    {
        $this->filename = $filename;
        setlocale(LC_TIME, "de_DE");
    }


    /**
     * Copiar registros a una archivo
     *
     *
     * @param String $request request
     * @param String $http http
     * @param String $response response
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function log($request, $http, $response)
    {
        file_put_contents($this->filename, strftime("Requested at: %A, %d. %B %Y %H:%M:%S\n"), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "\nRequest: ", FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, print_r($request, true), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "\nHTTP: ", FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, print_r($http, true), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "\nResponse: ", FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, print_r($response, true), FILE_APPEND | LOCK_EX);
        file_put_contents($this->filename, "--------------------------------------------\n", FILE_APPEND | LOCK_EX);
    }
}
