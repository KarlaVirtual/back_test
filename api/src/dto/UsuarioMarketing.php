<?php namespace Backend\dto;
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Exception;
/**
 * Clase 'UsuarioMarketing'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioMarketing'
 *
 * Ejemplo de uso:
 * $UsuarioMarketing = new UsuarioMarketing();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioMarketing
{

    /**
     * Representación de la columna 'usumarketingId' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $usumarketingId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'usuariorefId' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $usuariorefId;

    /**
     * Representación de la columna 'externoId' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $externoId;

    /**
     * Representación de la columna 'tipo' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'valor' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'ip' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $ip;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioMarketing'
     *
     * @var string
     */
    var $usumodifId;

    var $linkId;

    var $bannerId;


    /**
     * Constructor de clase
     *
     *
     * @param String $usumarketingId usumarketingId
     * @param String $usuarioId usuarioId
     * @param String $externoId externoId
     *
     * @return no
     * @throws Exception si UsuarioMarketing no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usumarketingId="", $usuarioId = '', $externoId="")
    {
        if ($usumarketingId != "")
        {

            $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();

            $UsuarioMarketing = $UsuarioMarketingMySqlDAO->load($usumarketingId);

            if ($UsuarioMarketing != null && $UsuarioMarketing != "")
            {

                $this->usumarketingId = $UsuarioMarketing->usumarketingId;
                $this->usuarioId = $UsuarioMarketing->usuarioId;
                $this->usuariorefId = $UsuarioMarketing->usuariorefId;
                $this->externoId = $UsuarioMarketing->externoId;
                $this->tipo = $UsuarioMarketing->tipo;
                $this->valor = $UsuarioMarketing->valor;
                $this->ip = $UsuarioMarketing->ip;
                $this->usucreaId = $UsuarioMarketing->usucreaId;
                $this->usumodifId = $UsuarioMarketing->usumodifId;
                $this->linkId = $UsuarioMarketing->linkId;
                $this->bannerId = $UsuarioMarketing->bannerId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }

    /**
     * Realizar una consulta en la tabla de UsuarioMarketing 'UsuarioMarketing'
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
    public function getUsuarioMarketingCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();

        $Productos = $UsuarioMarketingMySqlDAO->queryUsuarioMarketingsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

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
     * Realizar una consulta en la tabla de UsuarioMarketing 'UsuarioMarketing'
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
    public function getUsuarioMarketingGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $group)
    {

        $UsuarioMarketingMySqlDAO = new UsuarioMarketingMySqlDAO();

        $Productos = $UsuarioMarketingMySqlDAO->queryUsuarioMarketingsGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group);

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
     * Obtener el campo usumarketingId de un objeto
     *
     * @return String usumarketingId usumarketingId
     *
     */
    public function getUsumarketingId()
    {
        return $this->usumarketingId;
    }

/**
     * Obtener el campo linkId de un objeto
     *
     * @return String linkId linkId
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Modificar el campo 'linkId' de un objeto
     *
     * @param String $linkId linkId
     *
     * @return no
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
    }

    /**
     * Obtener el campo bannerId de un objeto
     *
     * @return String bannerId bannerId
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
     */
    public function setBannerId($bannerId)
    {
        $this->bannerId = $bannerId;
    }


}

?>
