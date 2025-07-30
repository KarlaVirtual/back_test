<?php namespace Backend\dto;
use Backend\mysql\UsuarioBannerDetalleMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioBanner'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioBanner'
* 
* Ejemplo de uso: 
* $UsuarioBanner = new UsuarioBanner();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioBanner
{

    /**
    * Representación de la columna 'usubannerId' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $usubannerId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'bannerId' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $bannerId;

    /**
    * Representación de la columna 'favorito' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $favorito;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'landingId' de la tabla 'UsuarioBanner'
    *
    * @var string
    */
    var $landingId;


    /**
    * Constructor de clase
    *
    *
    * @param String $usubannerId usubannerId
    * @param String $usuarioId usuarioId
    * @param String $bannerId bannerId
    *
    * @return no
    * @throws Exception si UsuarioBanner no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usubannerId="", $usuarioId="",$bannerId="")
    {
        if ($usubannerId != "") 
        {

            $this->usubannerId = $usubannerId;

            $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

            $UsuarioBanner = $UsuarioBannerMySqlDAO->load($usubannerId);

            if ($UsuarioBanner != null && $UsuarioBanner != "") 
            {
                $this->usubannerId = $UsuarioBanner->usubannerId;
                $this->usuarioId = $UsuarioBanner->usuarioId;
                $this->bannerId = $UsuarioBanner->bannerId;
                $this->favorito = $UsuarioBanner->favorito;
                $this->estado = $UsuarioBanner->estado;
                $this->usucreaId = $UsuarioBanner->usucreaId;
                $this->usumodifId = $UsuarioBanner->usumodifId;
                $this->landingId = $UsuarioBanner->landingId;
                $this->mandante = $UsuarioBanner->mandante;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        
        }
        elseif ($usuarioId != "" && $bannerId != "")
        {
        
            $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

            $UsuarioBanner = $UsuarioBannerMySqlDAO->queryByUsuarioIdAndBannerId($usuarioId,$bannerId);
            $UsuarioBanner = $UsuarioBanner[0];

            if ($UsuarioBanner != null && $UsuarioBanner != "") 
            {
                $this->usubannerId = $UsuarioBanner->usubannerId;
                $this->usuarioId = $UsuarioBanner->usuarioId;
                $this->bannerId = $UsuarioBanner->bannerId;
                $this->favorito = $UsuarioBanner->favorito;
                $this->estado = $UsuarioBanner->estado;
                $this->usucreaId = $UsuarioBanner->usucreaId;
                $this->usumodifId = $UsuarioBanner->usumodifId;
                $this->landingId = $UsuarioBanner->landingId;
                $this->mandante = $UsuarioBanner->mandante;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }

        }
        elseif ( $usuarioId != "") 
        {

            $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

            $UsuarioBanner = $UsuarioBannerMySqlDAO->queryByExternoIdAndProveedorId($usuarioId);
            $UsuarioBanner = $UsuarioBanner[0];

            if ($UsuarioBanner != null && $UsuarioBanner != "") 
            {
                $this->usubannerId = $UsuarioBanner->usubannerId;
                $this->usuarioId = $UsuarioBanner->usuarioId;
                $this->bannerId = $UsuarioBanner->bannerId;
                $this->favorito = $UsuarioBanner->favorito;
                $this->estado = $UsuarioBanner->estado;
                $this->usucreaId = $UsuarioBanner->usucreaId;
                $this->usumodifId = $UsuarioBanner->usumodifId;
                $this->landingId = $UsuarioBanner->landingId;
                $this->mandante = $UsuarioBanner->mandante;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

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
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioBannersCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioBannerMySqlDAO = new UsuarioBannerMySqlDAO();

        $Productos = $UsuarioBannerMySqlDAO->queryUsuarioBannersCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Obtener el campo bannerId de un objeto
     *
     * @return String bannerId bannerId
     * 
     */
    public function getBannerId()
    {
        return $this->bannerId;
    }

    /**
     * Modificar el campo 'bannerId' de un objeto
     *
     * @param String $bannerId bannerId
     *
     * @return no
     *
     */
    public function setBannerId($bannerId)
    {
        $this->bannerId = $bannerId;
    }

    /**
     * Obtener el campo favorito de un objeto
     *
     * @return String favorito favorito
     * 
     */
    public function getFavorito()
    {
        return $this->favorito;
    }

    /**
     * Modificar el campo 'favorito' de un objeto
     *
     * @param String $favorito favorito
     *
     * @return no
     *
     */
    public function setFavorito($favorito)
    {
        $this->favorito = $favorito;
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
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo landingId de un objeto
     *
     * @return String landingId landingId
     * 
     */
    public function getLandingId()
    {
        return $this->landingId;
    }

    /**
     * Modificar el campo 'landingId' de un objeto
     *
     * @param String $landingId landingId
     *
     * @return no
     *
     */
    public function setLandingId($landingId)
    {
        $this->landingId = $landingId;
    }

    /**
     * Obtener el campo usubannerId de un objeto
     *
     * @return String usubannerId usubannerId
     * 
     */
    public function getUsubannerId()
    {
        return $this->usubannerId;
    }

}

?>
