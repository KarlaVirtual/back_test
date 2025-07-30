<?php 
namespace Backend\dto;
use Backend\mysql\MandanteBilleteraMySqlDAO;
use Exception;
/** 
* Clase 'MandanteBilletera'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'MandanteBilletera'
* 
* Ejemplo de uso: 
* $MandanteBilletera = new MandanteBilletera();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class MandanteBilletera
{

    /**
    * Representación de la columna 'manbilleteraId' de la tabla 'MandanteBilletera'
    *
    * @var string
    */
    var $manbilleteraId;

    /**
    * Representación de la columna 'mandante' de la tabla 'MandanteBilletera'
    *
    * @var string
    */
    var $mandante;


    /**
    * Representación de la columna 'billetera_id' de la tabla 'MandanteBilletera'
    *
    * @var string
    */
    var $billeteraId;

    /**
    * Representación de la columna 'estado' de la tabla 'MandanteBilletera'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'MandanteBilletera'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'MandanteBilletera'
    *
    * @var string
    */
    var $usumodifId;





    /**
    * Constructor de clase
    *
    *
    * @param String $manbilleteraId manbilleteraId
    * @param String $mandante mandante
    *
    * @return no
    * @throws Exception si MandanteBilletera no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($manbilleteraId="", $mandante="")
    {
        if ($manbilleteraId != "")
        {

            $this->manbilleteraId = $manbilleteraId;

            $MandanteBilleteraMySqlDAO = new MandanteBilleteraMySqlDAO();

            $MandanteBilletera = $MandanteBilleteraMySqlDAO->load($manbilleteraId);

            if ($MandanteBilletera != null && $MandanteBilletera != "")
            {
                $this->manbilleteraId = $MandanteBilletera->manbilleteraId;
                $this->mandante = $MandanteBilletera->mandante;
                $this->billeteraId = $MandanteBilletera->billeteraId;
                $this->estado = $MandanteBilletera->estado;
                $this->usucreaId = $MandanteBilletera->usucreaId;
                $this->usumodifId = $MandanteBilletera->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }

        }
        elseif ( $mandante != "")
        {


            $MandanteBilleteraMySqlDAO = new MandanteBilleteraMySqlDAO();

            $MandanteBilletera = $MandanteBilleteraMySqlDAO->queryByExternoIdAndProveedorId($mandante);
            $MandanteBilletera = $MandanteBilletera[0];

            if ($MandanteBilletera != null && $MandanteBilletera != "")
            {
                $this->manbilleteraId = $MandanteBilletera->manbilleteraId;
                $this->mandante = $MandanteBilletera->mandante;
                $this->billeteraId = $MandanteBilletera->billeteraId;
                $this->estado = $MandanteBilletera->estado;
                $this->usucreaId = $MandanteBilletera->usucreaId;
                $this->usumodifId = $MandanteBilletera->usumodifId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }





    /**
    * Realizar una consulta en la tabla de MandanteBilletera 'MandanteBilletera'
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
    public function getMandanteBilleterasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $MandanteBilleteraMySqlDAO = new MandanteBilleteraMySqlDAO();

        $Productos = $MandanteBilleteraMySqlDAO->queryMandanteBilleterasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }





    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     *
     */
    public function getUsuarioId()
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
    public function setUsuarioId($mandante)
    {
        $this->mandante = $mandante;
    }


    /**
     * Obtener el campo billeteraId de un objeto
     *
     * @return String billeteraId billeteraId
     *
     */
    public function getBilleteraId()
    {
        return $this->billeteraId;
    }

    /**
     * Modificar el campo 'billeteraId' de un objeto
     *
     * @param String $billeteraId billeteraId
     *
     * @return no
     *
     */
    public function setBilleteraId($billeteraId)
    {
        $this->billeteraId = $billeteraId;
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
     * Obtener el campo manbilleteraId de un objeto
     *
     * @return String manbilleteraId manbilleteraId
     *
     */
    public function getUsubilleteraId()
    {
        return $this->manbilleteraId;
    }
}

?>
