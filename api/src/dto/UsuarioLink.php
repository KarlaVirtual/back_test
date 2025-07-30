<?php namespace Backend\dto;
use Backend\mysql\UsuarioLinkMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioLink'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioLink'
* 
* Ejemplo de uso: 
* $UsuarioLink = new UsuarioLink();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioLink
{

    /**
    * Representación de la columna 'usulinkId' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $usulinkId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'nombre' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'link' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $link;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'utmSource' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $utmSource;

    /**
    * Representación de la columna 'utmMedium' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $utmMedium;

    /**
    * Representación de la columna 'utmCampaing' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $utmCampaing;

    /**
    * Representación de la columna 'urlPersonalizada' de la tabla 'UsuarioLink'
    *
    * @var string
    */
    var $urlPersonalizada;

    /**
    * Constructor de clase
    *
    *
    * @param String $usulinkId usulinkId
    * @param String $usuarioId usuarioId
    * @param String $nombre nombre
    *
    * @return no
    * @throws Exception si UsuarioLink no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usulinkId="", $usuarioId="",$nombre="")
    {
        if ($usulinkId != "") 
        {

            $this->usulinkId = $usulinkId;

            $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();

            $UsuarioLink = $UsuarioLinkMySqlDAO->load($usulinkId);

            if ($UsuarioLink != null && $UsuarioLink != "") 
            {
                $this->usulinkId = $UsuarioLink->usulinkId;
                $this->usuarioId = $UsuarioLink->usuarioId;
                $this->nombre = $UsuarioLink->nombre;
                $this->link = $UsuarioLink->link;
                $this->utmSource = $UsuarioLink->utmSource;
                $this->utmMedium = $UsuarioLink->utmMedium;
                $this->utmCampaing = $UsuarioLink->utmCampaing;
                $this->urlPersonalizada = $UsuarioLink->urlPersonalizada;
                $this->estado = $UsuarioLink->estado;
                $this->usucreaId = $UsuarioLink->usucreaId;
                $this->usumodifId = $UsuarioLink->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        
        }
        elseif ($usuarioId != "" && $nombre != "")
        {
            $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();

            $UsuarioLink = $UsuarioLinkMySqlDAO->queryByUsuarioIdAndBannerId($usuarioId,$nombre);
            $UsuarioLink = $UsuarioLink[0];

            if ($UsuarioLink != null && $UsuarioLink != "") 
            {
                $this->usulinkId = $UsuarioLink->usulinkId;
                $this->usuarioId = $UsuarioLink->usuarioId;
                $this->nombre = $UsuarioLink->nombre;
                $this->link = $UsuarioLink->link;
                $this->utmSource = $UsuarioLink->utmSource;
                $this->utmMedium = $UsuarioLink->utmMedium;
                $this->utmCampaing = $UsuarioLink->utmCampaing;
                $this->urlPersonalizada = $UsuarioLink->urlPersonalizada;
                $this->estado = $UsuarioLink->estado;
                $this->usucreaId = $UsuarioLink->usucreaId;
                $this->usumodifId = $UsuarioLink->usumodifId;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }

        }
        elseif ( $usuarioId != "") 
        {


            $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();

            $UsuarioLink = $UsuarioLinkMySqlDAO->queryByExternoIdAndProveedorId($usuarioId);
            $UsuarioLink = $UsuarioLink[0];

            if ($UsuarioLink != null && $UsuarioLink != "") 
            {
                $this->usulinkId = $UsuarioLink->usulinkId;
                $this->usuarioId = $UsuarioLink->usuarioId;
                $this->nombre = $UsuarioLink->nombre;
                $this->link = $UsuarioLink->link;
                $this->utmSource = $UsuarioLink->utmSource;
                $this->utmMedium = $UsuarioLink->utmMedium;
                $this->utmCampaing = $UsuarioLink->utmCampaing;
                $this->urlPersonalizada = $UsuarioLink->urlPersonalizada;
                $this->estado = $UsuarioLink->estado;
                $this->usucreaId = $UsuarioLink->usucreaId;
                $this->usumodifId = $UsuarioLink->usumodifId;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }





    /**
    * Realizar una consulta en la tabla de UsuarioLink 'UsuarioLink'
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
    public function getUsuarioLinksCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioLinkMySqlDAO = new UsuarioLinkMySqlDAO();

        $Productos = $UsuarioLinkMySqlDAO->queryUsuarioLinksCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
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
     * Obtener el campo nombre de un objeto
     *
     * @return String nombre nombre
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
     * Obtener el campo link de un objeto
     *
     * @return String link link
     * 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Modificar el campo 'link' de un objeto
     *
     * @param String $link link
     *
     * @return no
     *
     */
    public function setLink($link)
    {
        $this->link = $link;
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
     * Obtener el campo usulinkId de un objeto
     *
     * @return String usulinkId usulinkId
     * 
     */
    public function getUsulinkId()
    {
        return $this->usulinkId;
    }

/**
     * Obtener el campo utmSource de un objeto
     *
     * @return String utmSource utmSource
     */
    public function getUtmSource() {
        return $this->utmSource;
    }

    /**
     * Modificar el campo 'utmSource' de un objeto
     *
     * @param String $utmSource utmSource
     *
     * @return no
     */
    public function setUtmSource($utmSource) {
        $this->utmSource = $utmSource;
    }

    /**
     * Obtener el campo utmMedium de un objeto
     *
     * @return String utmMedium utmMedium
     */
    public function getUtmMedium() {
        return $this->utmMedium;
    }

    /**
     * Modificar el campo 'utmMedium' de un objeto
     *
     * @param String $utmMedium utmMedium
     *
     * @return no
     */
    public function setUtmMedium($utmMedium) {
        $this->utmMedium = $utmMedium;
    }

    /**
     * Obtener el campo utmCampaing de un objeto
     *
     * @return String utmCampaing utmCampaing
     */
    public function getUtmCampaing() {
        return $this->utmCampaing;
    }

    /**
     * Modificar el campo 'utmCampaing' de un objeto
     *
     * @param String $utmCampaing utmCampaing
     *
     * @return no
     */
    public function setUtmCampaing($utmCampaing) {
        $this->utmCampaing = $utmCampaing;
    }

    /**
     * Obtener el campo urlPersonalizada de un objeto
     *
     * @return String urlPersonalizada urlPersonalizada
     */
    public function getUrlPersonalizada() {
        return $this->urlPersonalizada;
    }

    /**
     * Modificar el campo 'urlPersonalizada' de un objeto
     *
     * @param String $urlPersonalizada urlPersonalizada
     *
     * @return no
     */
    public function setUrlPersonalizada($urlPersonalizada) {
        $this->urlPersonalizada = $urlPersonalizada;
    }
}

?>
