<?php namespace Backend\dto;
use Backend\mysql\ConfigMandanteMySqlDAO;
use Exception;
/** 
* Clase 'ConfigMandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ConfigMandante'
* 
* Ejemplo de uso: 
* $ConfigMandante = new ConfigMandante();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ConfigMandante
{

    /**
    * Representación de la columna 'confmandanteId' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
	var $confmandanteId;

    /**
    * Representación de la columna 'config' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
	var $config;

    /**
    * Representación de la columna 'mandante' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
    var $mandante;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ConfigMandante'
    *
    * @var string
    */ 
    var $usumodifId;


    /**
     * Constructor de clase
     *
     *
     * @param String confmandanteId id del config mandante
     * @param String config config
     * @param String valor valor
     *
     *
    * @return no
    * @throws Exception si ConfigMandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($confmandanteId="", $mandante="")
    {

        if($confmandanteId != "")
        {

            $ConfigMandanteMySqlDAO = new ConfigMandanteMySqlDAO();

            $ConfigMandante = $ConfigMandanteMySqlDAO->load($confmandanteId);


            $this->success = false;

            if ($ConfigMandante != null && $ConfigMandante != "")
            {
                $this->mandante = $ConfigMandante->mandante;
                $this->confmandanteId = $ConfigMandante->confmandanteId;
                $this->config = $ConfigMandante->config;
                $this->fechaCrea = $ConfigMandante->fechaCrea;
                $this->usucreaId = $ConfigMandante->usucreaId;
                $this->fechaModif = $ConfigMandante->fechaModif;
                $this->usumodifId = $ConfigMandante->usumodifId;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "114");
            }

        }elseif ($mandante != "")
        {
        
            $ConfigMandanteMySqlDAO = new ConfigMandanteMySqlDAO();

            $ConfigMandante = $ConfigMandanteMySqlDAO->loadByMandante($mandante);


            $this->success = false;

            if ($ConfigMandante != null && $ConfigMandante != "") 
            {

                $this->mandante = $ConfigMandante->mandante;
                $this->confmandanteId = $ConfigMandante->confmandanteId;
                $this->config = $ConfigMandante->config;
                $this->fechaCrea = $ConfigMandante->fechaCrea;
                $this->usucreaId = $ConfigMandante->usucreaId;
                $this->fechaModif = $ConfigMandante->fechaModif;
                $this->usumodifId = $ConfigMandante->usumodifId;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "114");
            }
        }

    }


    /**
    * Realizar una consulta en la tabla de lenguajes mandantes 'ConfigMandantes'
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
    * @throws Exception si los bonos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getConfigMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $ConfigMandanteMySqlDAO = new ConfigMandanteMySqlDAO();

        $bonos = $ConfigMandanteMySqlDAO->queryConfigMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($bonos != null && $bonos != "") 
        {
            return $bonos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "114");
        }


    }

    /**
     * @return string
     */
    public function getConfmandanteId()
    {
        return $this->confmandanteId;
    }


    /**
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $config
     */
    public function setConfig( $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param string $mandante
     */
    public function setMandante( $mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * @param string $fechaCrea
     */
    public function setFechaCrea( $fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * @param string $usucreaId
     */
    public function setUsucreaId( $usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * @param string $fechaModif
     */
    public function setFechaModif( $fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * @param string $usumodifId
     */
    public function setUsumodifId( $usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }









}
?>