<?php namespace Backend\dto;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioPublicidad'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioPublicidad'
* 
* Ejemplo de uso: 
* $UsuarioPublicidad = new UsuarioPublicidad();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPublicidad
{

    /**
    * Representación de la columna 'usupublicidadId' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $usupublicidadId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'orden' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $orden;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'descripcion' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioPublicidad'
    *
    * @var string
    */
    var $estado;





    /**
    * Constructor de clase
    *
    *
    * @param String $usupublicidadId usupublicidadId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioPublicidad no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usupublicidadId="",$usuarioId = "")
    {

        if ($usupublicidadId != "") 
        {

            $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();

            $UsuarioPublicidad = $UsuarioPublicidadMySqlDAO->load($usupublicidadId);

            if ($UsuarioPublicidad != null && $UsuarioPublicidad != "") 
            {
            
                $this->usupublicidadId = $UsuarioPublicidad->usupublicidadId;
                $this->usuarioId = $UsuarioPublicidad->usuarioId;
                $this->orden = $UsuarioPublicidad->orden;
                $this->valor = $UsuarioPublicidad->valor;
                $this->usucreaId = $UsuarioPublicidad->usucreaId;
                $this->usumodifId = $UsuarioPublicidad->usumodifId;
                $this->descripcion = $UsuarioPublicidad->descripcion;
                $this->estado = $UsuarioPublicidad->estado;
            
            } 
            else
            {
                throw new Exception("No existe " . get_class($this), "50");
            }

        } 
        elseif ($usuarioId != "" ) 
        {


            $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();

            $UsuarioPublicidad = $UsuarioPublicidadMySqlDAO->queryByUsuarioId($usuarioId);
            $UsuarioPublicidad = $UsuarioPublicidad[0];

            if ($UsuarioPublicidad != null && $UsuarioPublicidad != "") 
            {
            
                $this->usupublicidadId = $UsuarioPublicidad->usupublicidadId;
                $this->usuarioId = $UsuarioPublicidad->usuarioId;
                $this->orden = $UsuarioPublicidad->orden;
                $this->valor = $UsuarioPublicidad->valor;
                $this->usucreaId = $UsuarioPublicidad->usucreaId;
                $this->usumodifId = $UsuarioPublicidad->usumodifId;
                $this->descripcion = $UsuarioPublicidad->descripcion;
                $this->estado = $UsuarioPublicidad->estado;
            
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "50");
            }
        }
    }






    /**
     * Obtener el campo usupublicidadId de un objeto
     *
     * @return String usupublicidadId usupublicidadId
     * 
     */
    public function getUsupublicidadId()
    {
        return $this->usupublicidadId;
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
     * Obtener el campo orden de un objeto
     *
     * @return String orden orden
     * 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Modificar el campo 'orden' de un objeto
     *
     * @param String $orden orden
     *
     * @return no
     *
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
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
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     * 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     * 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }





    /**
    * Realizar una consulta en la tabla de UsuarioBanner 'UsuarioBanner'
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
    *
    * @return Array resultado de la consulta
    * @throws Exception si los deportes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioPublicidadesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioPublicidadMySqlDAO = new UsuarioPublicidadMySqlDAO();

        $deportes = $UsuarioPublicidadMySqlDAO->queryUsuarioPublicidadesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($deportes != null && $deportes != "") 
        {
            return $deportes;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "50");
        }

    }


}

?>