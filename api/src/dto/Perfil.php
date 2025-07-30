<?php namespace Backend\dto;
use Backend\mysql\PerfilMySqlDAO;
use Exception;
/** 
* Clase 'Perfil'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Perfil'
* 
* Ejemplo de uso: 
* $Perfil = new Perfil();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Perfil
{

    /**
    * Representación de la columna 'perfilId' de la tabla 'Perfil'
    *
    * @var string
    */ 
    public $perfilId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Perfil'
    *
    * @var string
    */ 
    public $descripcion;

    /**
    * Representación de la columna 'apuestaMin' de la tabla 'Perfil'
    *
    * @var string
    */ 
    public $apuestaMin;

    /**
    * Representación de la columna 'contingencia' de la tabla 'Perfil'
    *
    * @var string
    */ 
    public $contingencia;

    /**
    * Representación de la columna 'tipo' de la tabla 'Perfil'
    *
    * @var string
    */ 
    public $tipo;

    /**
    * Representación de la columna 'diasClave' de la tabla 'Perfil'
    *
    * @var string
    */ 
    public $diasClave;


    /**
     * Constructor de clase
     *
     *
     * @param String perfilId id del perfil
     *
     *
    * @return no
    * @throws Exception si el perfil no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($perfilId="")
    {

        if ($perfilId != "") 
        {
            $this->perfilId = $perfilId;

            $PerfilMySqlDAO = new PerfilMySqlDAO();
            $Perfil = $PerfilMySqlDAO->load($perfilId);

            if ($Perfil != "" && $Perfil != null) 
            {

                $this->perfilId = $Perfil->perfilId;
                $this->descripcion = $Perfil->descripcion;
                $this->apuestaMin = $Perfil->apuestaMin;
                $this->contingencia = $Perfil->contingencia;
                $this->tipo = $Perfil->tipo;
                $this->diasClave = $Perfil->diasClave;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");

            }

        }

    }

    /**
     * Obtener el campo perfilId de un objeto
     *
     * @return String perfilId perfilId
     * 
     */
    public function getPerfilId()
    {
        return $this->perfilId;
    }

    /**
     * Modificar el campo 'perfilId' de un objeto
     *
     * @param String $perfilId perfilId
     *
     * @return no
     *
     */
    public function setPerfilId($perfilId)
    {
        $this->perfilId = $perfilId;
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
     * Obtener el campo apuestaMin de un objeto
     *
     * @return String apuestaMin apuestaMin
     * 
     */
    public function getApuestaMin()
    {
        return $this->apuestaMin;
    }

    /**
     * Modificar el campo 'apuestaMin' de un objeto
     *
     * @param String $apuestaMin apuestaMin
     *
     * @return no
     *
     */
    public function setApuestaMin($apuestaMin)
    {
        $this->apuestaMin = $apuestaMin;
    }

    /**
     * Obtener el campo contingencia de un objeto
     *
     * @return String contingencia contingencia
     * 
     */
    public function getContingencia()
    {
        return $this->contingencia;
    }

    /**
     * Modificar el campo 'contingencia' de un objeto
     *
     * @param String $contingencia econtingenciastado
     *
     * @return no
     *
     */
    public function setContingencia($contingencia)
    {
        $this->contingencia = $contingencia;
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
     * Obtener el campo diasClave de un objeto
     *
     * @return String diasClave diasClave
     * 
     */
    public function getDiasClave()
    {
        return $this->diasClave;
    }

    /**
     * Modificar el campo 'diasClave' de un objeto
     *
     * @param String $diasClave diasClave
     *
     * @return no
     *
     */
    public function setDiasClave($diasClave)
    {
        $this->diasClave = $diasClave;
    }



    /**
    * Realizar una consulta en la tabla de perfiles 'PerfilusuarioId'
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
    * @throws Exception si los perfiles no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getPerfilesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PerfilMySqlDAO = new PerfilMySqlDAO();

        $perfiles = $PerfilMySqlDAO->queryPerfilesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($perfiles != null && $perfiles != "") 
        {
            return $perfiles;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
