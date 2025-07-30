<?php 
namespace Backend\dto;
use Backend\mysql\ConsecutivoMySqlDAO;
/** 
* Clase 'Consecutivo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Consecutivo'
* 
* Ejemplo de uso: 
* $Consecutivo = new Consecutivo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Consecutivo
{

    /**
    * Representación de la columna 'consecutivoId' de la tabla 'Consecutivo'
    *
    * @var string
    */  
    var $consecutivoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'Consecutivo'
    *
    * @var string
    */  
    var $tipo;

    /**
    * Representación de la columna 'numero' de la tabla 'Consecutivo'
    *
    * @var string
    */  
    var $numero;

    /**
    * Constructor de clase
    *
    *
    * @param String $consecutivoId consecutivoId
    * @param String $tipo tipo
    * @param String $numero numero
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($consecutivoId="", $tipo="", $numero="")
    {

        if ($consecutivoId != "") 
        {

            $this->consecutivoId = $consecutivoId;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();
            $Consecutivo = $ConsecutivoMySqlDAO->load($transproductoId);

            $this->tipo = $Consecutivo->tipo;
            $this->numero = $Consecutivo->numero;

        } 
        elseif ($tipo != "") 
        {

            $this->tipo = $tipo;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();
            $Consecutivo = $ConsecutivoMySqlDAO->queryByTipo($tipo);

            $this->consecutivoId = $Consecutivo[0]->consecutivoId;
            $this->numero = $Consecutivo[0]->numero;

        } 
        elseif ($numero != "") 
        {
            $this->numero = $numero;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();
            $Consecutivo = $ConsecutivoMySqlDAO->queryByNumero($numero);

            $this->consecutivoId = $Consecutivo[0]->consecutivoId;
            $this->tipo = $Consecutivo[0]->tipo;
        }


    }







    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el campo numero de un objeto
     *
     * @return String numero numero
     * 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Modificar el campo 'numero' de un objeto
     *
     * @param String $numero numero
     *
     * @return no
     *
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }


}

?>