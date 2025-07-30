<?php namespace Backend\dto;
use Backend\mysql\UsuarioComisionMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioComision'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioComision'
* 
* Ejemplo de uso: 
* $UsuarioComision = new UsuarioComision();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioComision
{

    /**
    * Representación de la columna 'usucomisionId' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $usucomisionId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'usuariorefId' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $usuariorefId;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $externoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'comision' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $comision;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioComision'
    *
    * @var string
    */
    var $usumodifId;


    /**
    * Constructor de clase
    *
    *
    * @param String $usucomisionId usucomisionId
    * @param String $usuarioId usuarioId
    * @param String $externoId externoId
    *
    * @return no
    * @throws Exception si UsuarioComision no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usucomisionId="", $usuarioId="",$externoId="")
    {
        if ($usucomisionId != "") 
        {

            $UsuarioComisionMySqlDAO = new UsuarioComisionMySqlDAO();

            $UsuarioComision = $UsuarioComisionMySqlDAO->load($usucomisionId);

            if ($UsuarioComision != null && $UsuarioComision != "") 
            {
                $this->usucomisionId = $UsuarioComision->usucomisionId;
                $this->usuarioId = $UsuarioComision->usuarioId;
                $this->usuariorefId = $UsuarioComision->usuariorefId;
                $this->externoId = $UsuarioComision->externoId;
                $this->tipo = $UsuarioComision->tipo;
                $this->valor = $UsuarioComision->valor;
                $this->comision = $UsuarioComision->comision;
                $this->usucreaId = $UsuarioComision->usucreaId;
                $this->usumodifId = $UsuarioComision->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioComision 'UsuarioComision'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $grouping columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioComisionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsuarioComisionMySqlDAO = new UsuarioComisionMySqlDAO();

        $Productos = $UsuarioComisionMySqlDAO->queryUsuarioComisionsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioComision 'UsuarioComision'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $grouping columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioComisionGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $group)
    {

        $UsuarioComisionMySqlDAO = new UsuarioComisionMySqlDAO();

        $Productos = $UsuarioComisionMySqlDAO->queryUsuarioComisionsGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }






    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     * 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'transsportId' de un objeto
     *
     * @param String $transsportId transsportId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String externoId externoId
     * 
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return no
     *
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
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
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     * 
     */
    public function getValor()
    {
        return $this->valor;
    }
    
    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo usucomisionId de un objeto
     *
     * @return String usucomisionId usucomisionId
     * 
     */
    public function getUsucomisionId()
    {
        return $this->usucomisionId;
    }

}

?>
