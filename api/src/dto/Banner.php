<?php namespace Backend\dto;
use Backend\mysql\BannerMySqlDAO;
use Exception;
/**
* Clase 'Banner'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Banner'
*
* Ejemplo de uso:
* $Banner = new Banner();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Banner
{

    /**
    * Representación de la columna 'bannerId' de la tabla 'Banner'
    *
    * @var string
    */
    var $bannerId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'Banner'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'nombre' de la tabla 'Banner'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'width' de la tabla 'Banner'
    *
    * @var string
    */
    var $width;

    /**
    * Representación de la columna 'estado' de la tabla 'Banner'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'height' de la tabla 'Banner'
    *
    * @var string
    */
    var $height;

    /**
    * Representación de la columna 'bsize' de la tabla 'Banner'
    *
    * @var string
    */
    var $bsize;

    /**
    * Representación de la columna 'tipo' de la tabla 'Banner'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'idioma' de la tabla 'Banner'
    *
    * @var string
    */
    var $idioma;

    /**
    * Representación de la columna 'productointernoId' de la tabla 'Banner'
    *
    * @var string
    */
    var $productointernoId;

    /**
    * Representación de la columna 'filename' de la tabla 'Banner'
    *
    * @var string
    */
    var $filename;

    /**
    * Representación de la columna 'publico' de la tabla 'Banner'
    *
    * @var string
    */
    var $publico;

    /**
    * Representación de la columna 'fechaExpiracion' de la tabla 'Banner'
    *
    * @var string
    */
    var $fechaExpiracion;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Banner'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Banner'
    *
    * @var string
    */
    var $usumodifId;

    var $paisId;

    var $mandante;


    /**
    * Constructor de clase
    *
    *
    * @param String $bannerId id del banner
    * @param String $usuarioId id de usuario
    *
    * @throws Exception si el banner no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($bannerId="", $usuarioId="")
    {
        if ($bannerId != "")
        {

            $this->bannerId = $bannerId;

            $BannerMySqlDAO = new BannerMySqlDAO();

            $Banner = $BannerMySqlDAO->load($bannerId);

            if ($Banner != null && $Banner != "")
            {
                $this->usuarioId = $Banner->usuarioId;
                $this->nombre = $Banner->nombre;
                $this->width = $Banner->width;
                $this->estado = $Banner->estado;
                $this->height = $Banner->height;
                $this->bsize = $Banner->bsize;
                $this->tipo = $Banner->tipo;
                $this->idioma = $Banner->idioma;
                $this->productointernoId = $Banner->productointernoId;
                $this->filename = $Banner->filename;
                $this->publico = $Banner->publico;
                $this->fechaExpiracion = $Banner->fechaExpiracion;
                $this->usucreaId = $Banner->usucreaId;
                $this->usumodifId = $Banner->usumodifId;
                $this->paisId = $Banner->paisId;
                $this->mandante = $Banner->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "26");
            }
        }
        elseif ( $usuarioId != "")
        {

            $this->externoId = $externoId;

            $BannerMySqlDAO = new BannerMySqlDAO();

            $Banner = $BannerMySqlDAO->queryByExternoIdAndProveedorId($externoId, $usuarioId);
            $Banner = $Banner[0];

            if ($Banner != null && $Banner != "")
            {
                $this->usuarioId = $Banner->usuarioId;
                $this->nombre = $Banner->nombre;
                $this->width = $Banner->width;
                $this->estado = $Banner->estado;
                $this->height = $Banner->height;
                $this->bsize = $Banner->bsize;
                $this->tipo = $Banner->tipo;
                $this->idioma = $Banner->idioma;
                $this->productointernoId = $Banner->productointernoId;
                $this->filename = $Banner->filename;
                $this->publico = $Banner->publico;
                $this->fechaExpiracion = $Banner->fechaExpiracion;
                $this->usucreaId = $Banner->usucreaId;
                $this->usumodifId = $Banner->usumodifId;
                $this->paisId = $Banner->paisId;
                $this->mandante = $Banner->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "26");
            }
        }

    }

    /**
     * Obtener el campo 'usuarioId' de un objeto
     *
     * @return String usuarioId Id del área relacionada
     *
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId Id del empleado
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo 'nombre' de un objeto
     *
     * @return String nombre nombre del área relacionada
     *
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param String $nombre nombre
     *
     * @return no
     *
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo 'width' de un objeto
     *
     * @return String Width width del área relacionada
     *
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Modificar el campo 'width' de un objeto
     *
     * @param String $width width
     *
     * @return no
     *
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Obtener el campo 'estado' de un objeto
     *
     * @return String Estado estado del área relacionada
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
     * Obtener el campo 'height' de un objeto
     *
     * @return String Height height
     *
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Modificar el campo 'height' de un objeto
     *
     * @param String $height height
     *
     * @return no
     *
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Obtener el campo 'Bsize' de un objeto
     *
     * @return String width Bsize del área relacionada
     *
     */    public function getBsize()
    {
        return $this->bsize;
    }

    /**
     * Modificar el campo 'Bsize' de un objeto
     *
     * @param String $Bsize Bsize
     *
     * @return no
     *
     */
    public function setBsize($bsize)
    {
        $this->bsize = $bsize;
    }

    /**
     * Obtener el campo 'tipo' de un objeto
     *
     * @return String Tipo tipo del área relacionada
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
     * Obtener el campo 'idioma' de un objeto
     *
     * @return String Idioma idioma del área relacionada
     *
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Modificar el campo 'idioma' de un objeto
     *
     * @param String $idioma idioma
     *
     * @return no
     *
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    }

    /**
     * Obtener el campo 'productoInternoId' de un objeto
     *
     * @return String ProductoInternoId productoInternoId del área relacionada
     *
     */
    public function getProductointernoId()
    {
        return $this->productointernoId;
    }

    /**
     * Modificar el campo 'productoInternoId' de un objeto
     *
     * @param String $productoInternoId productoInternoId
     *
     * @return no
     *
     */
    public function setProductointernoId($productointernoId)
    {
        $this->productointernoId = $productointernoId;
    }

    /**
     * Obtener el campo 'filename' de un objeto
     *
     * @return String Filename filename del área relacionada
     *
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Modificar el campo 'filename' de un objeto
     *
     * @param String $filename filename
     *
     * @return no
     *
     */    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Obtener el campo 'usucreaId' de un objeto
     *
     * @return String UsucreaId usucreaId del área relacionada
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
     * Obtener el campo 'usumodifId' de un objeto
     *
     * @return String UsumodifId usumodifId del área relacionada
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
     * Obtener el campo 'publico' de un objeto
     *
     * @return String Publico publico del área relacionada
     *
     */
    public function getPublico()
    {
        return $this->publico;
    }

    /**
     * Modificar el campo 'publico' de un objeto
     *
     * @param String $publico publico
     *
     * @return no
     *
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;
    }

    /**
     * Obtener el campo 'fechaExpiracion' de un objeto
     *
     * @return String Fecha fechaExpiracion del área relacionada
     *
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * Modificar el campo 'fechaExpiracion' de un objeto
     *
     * @param String $fechaExpiracion fechaExpiracion
     *
     * @return no
     *
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;
    }

    /**
     * Obtener el campo 'bannerId' de un objeto
     *
     * @return String BannerId bannerId del área relacionada
     *
     */
    public function getBannerId()
    {
        return $this->bannerId;
    }

    /**
     * @return mixed
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }


    /**
    * Realizar una consulta en la tabla de banner 'Banner'
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
    public function getBannersCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $BannerMySqlDAO = new BannerMySqlDAO();

        $Productos = $BannerMySqlDAO->queryBanners($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
    * Realizar una consulta en la tabla de banner 'Banner'
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
    public function getBannersUsuarioCustom($usuarioId,$select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $BannerMySqlDAO = new BannerMySqlDAO();

        $Productos = $BannerMySqlDAO->queryBannersUsuarioCustom($usuarioId,$select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
