<?php
/** 
* Includes
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
require_once '_config.php';
$helper = glob(DIR_HELPER_GLOBAL.'*.php');
foreach($helper as $function)
{
    include_once $function;
}

/** 
* Clase 'Loader'
* 
* Esta clase provee funciones para la api 'Loader'
* 
* Ejemplo de uso: 
* $Loader = new Loader();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @final
* 
*/
final class Loader
{


    /**
     * Método constructor
     *
     * @param $loadClass loadClass	
     * 
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($loadClass)
    {
        include_once DIR_FUNCTION.'base.php';
        if(file_exists(DIR_FUNCTION.$loadClass.'/loader.php'))
        {
            include_once DIR_FUNCTION.$loadClass.'/loader.php';
        }
    }
}