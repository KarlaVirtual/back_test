<?php namespace Backend\dto;
use Backend\mysql\CategoriaMySqlDAO;
/** 
* Clase 'Categoria'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Categoria'
* 
* Ejemplo de uso: 
* $Categoria = new Categoria();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Categoria
{

    /**
    * Representación de la columna 'categoriaId' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $categoriaId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $descripcion;

    /**
    * Representación de la columna 'tipo' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $tipo;

    /**
    * Representación de la columna 'slug' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $slug;

    /**
    * Representación de la columna 'estado' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $usumodifId;

    /**
    * Representación de la columna 'superior' de la tabla 'Categoria'
    *
    * @var string
    */  
    var $superior;


    /**
    * Constructor de clase
    *
    *
    * @param String $categoriaId id de la categoria
    * @param String $tipo tipo
    * @param String $slug slug
    *
    * @return no
    * @throws Exception si las categorias no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($categoriaId="", $tipo="", $slug="")
    {
        $this->categoriaId = $categoriaId;
        $this->tipo = $tipo;
        $this->slug = $slug;

        if ($categoriaId != "") 
        {

            $CategoriaMySqlDAO = new CategoriaMySqlDAO();

            $Categoria = $CategoriaMySqlDAO->load($categoriaId);

            if ($Categoria != null && $Categoria != "") 
            {
                $this->categoriaId = $Categoria->categoriaId;
                $this->descripcion = $Categoria->descripcion;
                $this->tipo = $Categoria->tipo;
                $this->slug = $Categoria->slug;
                $this->estado = $Categoria->estado;
                $this->usucreaId = $Categoria->usucreaId;
                $this->usumodifId = $Categoria->usumodifId;
                $this->superior = $Categoria->superior;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }

        } 
        elseif ($tipo != "") 
        {
            $CategoriaMySqlDAO = new CategoriaMySqlDAO();

            $Categoria = $CategoriaMySqlDAO->queryByTipo($tipo);

            $Categoria = $Categoria[0];

            if ($Categoria != null && $Categoria != "") 
            {
                $this->categoriaId = $Categoria->categoriaId;
                $this->descripcion = $Categoria->descripcion;
                $this->tipo = $Categoria->tipo;
                $this->slug = $Categoria->slug;
                $this->estado = $Categoria->estado;
                $this->usucreaId = $Categoria->usucreaId;
                $this->usumodifId = $Categoria->usumodifId;
                $this->superior = $Categoria->superior;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }

        } 
        elseif ($slug != "") 
        {
            $CategoriaMySqlDAO = new CategoriaMySqlDAO();

            $Categoria = $CategoriaMySqlDAO->queryBySlug($slug);

            $Categoria=$Categoria[0];

            if ($Categoria != null && $Categoria != "") 
            {

                $this->categoriaId = $Categoria->categoriaId;
                $this->descripcion = $Categoria->descripcion;
                $this->tipo = $Categoria->tipo;
                $this->slug = $Categoria->slug;
                $this->estado = $Categoria->estado;
                $this->usucreaId = $Categoria->usucreaId;
                $this->usumodifId = $Categoria->usumodifId;
                $this->superior = $Categoria->superior;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }

        }


    }





    /**
     * Obtener el campo categoriaId de un objeto
     *
     * @return String categoriaId categoriaId
     * 
     */
    public function getCategoriaId()
    {
        return $this->categoriaId;
    }

    /**
     * Modificar el campo 'categoriaId' de un objeto
     *
     * @param String $categoriaId categoriaId
     *
     * @return no
     *
     */
    public function setCategoriaId($categoriaId)
    {
        $this->categoriaId = $categoriaId;
    }


    /**
     * Obtener el campo Descripcion de un objeto
     *
     * @return String Descripcion descripcion
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
     * Modificar el campo 'tipoId' de un objeto
     *
     * @param String $tipoId tipoId
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }


    /**
     * Obtener el campo slug de un objeto
     *
     * @return String slug slug
     * 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Modificar el campo 'slug' de un objeto
     *
     * @param String $slug slug
     *
     * @return no
     *
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * Obtener el campo superior de un objeto
     *
     * @return String superior superior
     * 
     */
    public function getSuperior()
    {
        return $this->superior;
    }

    /**
     * Modificar el campo 'superior' de un objeto
     *
     * @param String $superior superior
     *
     * @return no
     *
     */
    public function setSuperior($superior)
    {
        $this->superior = $superior;
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
     * Obtener todas las categorias por tipo
     *
     * @return Array $ resultado del query
     * 
     */
    public function getCategoriasTipo($mandante="",$paisId="")
    {

        $CategoriaMySqlDAO = new CategoriaMySqlDAO($transaction);

        return $CategoriaMySqlDAO->queryByTipo($this->tipo,$mandante,$paisId);

    }





    /**
    * Realizar una consulta en la tabla de categorias 'Categoria'
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
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getCategoriasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CategoriaMySqlDAO = new CategoriaMySqlDAO();

        $Productos = $CategoriaMySqlDAO->queryCategoriasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

}

?>
