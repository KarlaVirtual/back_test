<?php namespace Backend\dto;
use Backend\mysql\UsumarketingResumenDetalleMySqlDAO;
use Backend\mysql\UsumarketingResumenMySqlDAO;
use Exception;
/** 
* Clase 'UsumarketingResumen'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsumarketingResumen'
* 
* Ejemplo de uso: 
* $UsumarketingResumen = new UsumarketingResumen();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsumarketingResumen
{

    /**
    * Representación de la columna 'usumarkresumenId' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $usumarkresumenId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'usuariorefId' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $usuariorefId;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $externoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'ip' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $ip;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsumarketingResumen'
    *
    * @var string
    */
    var $usumodifId;


    /**
    * Constructor de clase
    *
    *
    * @param String $usumarkresumenId usumarkresumenId
    * @param String $usuarioId usuarioId
    * @param String $externoId externoId
    *
    * @return no
    * @throws Exception si UsuarioToken no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usumarkresumenId="", $usuarioId="",$externoId="")
    {
        if ($usumarkresumenId != "") 
        {

            $UsumarketingResumenMySqlDAO = new UsumarketingResumenMySqlDAO();

            $UsumarketingResumen = $UsumarketingResumenMySqlDAO->load($usumarkresumenId);

            if ($UsumarketingResumen != null && $UsumarketingResumen != "") 
            {
            
                $this->usumarkresumenId = $UsumarketingResumen->usumarkresumenId;
                $this->usuarioId = $UsumarketingResumen->usuarioId;
                $this->usuariorefId = $UsumarketingResumen->usuariorefId;
                $this->externoId = $UsumarketingResumen->externoId;
                $this->tipo = $UsumarketingResumen->tipo;
                $this->valor = $UsumarketingResumen->valor;
                $this->ip = $UsumarketingResumen->ip;
                $this->usucreaId = $UsumarketingResumen->usucreaId;
                $this->usumodifId = $UsumarketingResumen->usumodifId;
            
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }

    /**
    * Realizar una consulta en la tabla de UsumarketingResumen 'UsumarketingResumen'
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
    public function getUsumarketingResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsumarketingResumenMySqlDAO = new UsumarketingResumenMySqlDAO();

        $Productos = $UsumarketingResumenMySqlDAO->queryUsumarketingResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

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
    * Realizar una consulta en la tabla de UsumarketingResumen 'UsumarketingResumen'
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
    * @param String $group columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsumarketingResumenGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $group)
    {

        $UsumarketingResumenMySqlDAO = new UsumarketingResumenMySqlDAO();

        $Productos = $UsumarketingResumenMySqlDAO->queryUsumarketingResumensGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group);

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
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo usuariorefId de un objeto
     *
     * @return String usuariorefId usuariorefId
     * 
     */
    public function getUsuariorefId()
    {
        return $this->usuariorefId;
    }

    /**
     * Modificar el campo 'usuariorefId' de un objeto
     *
     * @param String $usuariorefId usuariorefId
     *
     * @return no
     *
     */
    public function setUsuariorefId($usuariorefId)
    {
        $this->usuariorefId = $usuariorefId;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String externoId externoId
     * 
     */    public function getExternoId()
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
     * Obtener el campo ip de un objeto
     *
     * @return String ip ip
     * 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Modificar el campo 'ip' de un objeto
     *
     * @param String $ip ip
     *
     * @return no
     *
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
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
     * Obtener el campo usumarkresumenId de un objeto
     *
     * @return String usumarkresumenId usumarkresumenId
     * 
     */
    public function getUsumarketingId()
    {
        return $this->usumarkresumenId;
    }


}

?>
