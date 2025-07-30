<?php namespace Backend\dto;
use Backend\mysql\UsuarioBilleteraDetalleMySqlDAO;
use Backend\mysql\UsuarioBilleteraMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioBilletera'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioBilletera'
* 
* Ejemplo de uso: 
* $UsuarioBilletera = new UsuarioBilletera();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioBilletera
{

    /**
    * Representación de la columna 'usubilleteraId' de la tabla 'UsuarioBilletera'
    *
    * @var string
    */
    var $usubilleteraId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioBilletera'
    *
    * @var string
    */
    var $usuarioId;


    /**
    * Representación de la columna 'billetera_id' de la tabla 'UsuarioBilletera'
    *
    * @var string
    */
    var $billeteraId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioBilletera'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioBilletera'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioBilletera'
    *
    * @var string
    */
    var $usumodifId;





    /**
    * Constructor de clase
    *
    *
    * @param String $usubilleteraId usubilleteraId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioBilletera no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usubilleteraId="", $usuarioId="", $billeteraId="")
    {
        if ($usubilleteraId != "")
        {

            $this->usubilleteraId = $usubilleteraId;

            $UsuarioBilleteraMySqlDAO = new UsuarioBilleteraMySqlDAO();

            $UsuarioBilletera = $UsuarioBilleteraMySqlDAO->load($usubilleteraId);

            if ($UsuarioBilletera != null && $UsuarioBilletera != "")
            {
                $this->usubilleteraId = $UsuarioBilletera->usubilleteraId;
                $this->usuarioId = $UsuarioBilletera->usuarioId;
                $this->billeteraId = $UsuarioBilletera->billeteraId;
                $this->estado = $UsuarioBilletera->estado;
                $this->usucreaId = $UsuarioBilletera->usucreaId;
                $this->usumodifId = $UsuarioBilletera->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "113");
            }

        }
        elseif ( $usuarioId != "" && $billeteraId !="")
        {


            $UsuarioBilleteraMySqlDAO = new UsuarioBilleteraMySqlDAO();

            $UsuarioBilletera = $UsuarioBilleteraMySqlDAO->queryByUsuarioIdAndBilleteraId($usuarioId,$billeteraId);

            if ($UsuarioBilletera != null && $UsuarioBilletera != "")
            {
                $this->usubilleteraId = $UsuarioBilletera->usubilleteraId;
                $this->usuarioId = $UsuarioBilletera->usuarioId;
                $this->billeteraId = $UsuarioBilletera->billeteraId;
                $this->estado = $UsuarioBilletera->estado;
                $this->usucreaId = $UsuarioBilletera->usucreaId;
                $this->usumodifId = $UsuarioBilletera->usumodifId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "113");
            }
        }

    }





    /**
    * Realizar una consulta en la tabla de UsuarioBilletera 'UsuarioBilletera'
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
    public function getUsuarioBilleterasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioBilleteraMySqlDAO = new UsuarioBilleteraMySqlDAO();

        $Productos = $UsuarioBilleteraMySqlDAO->queryUsuarioBilleterasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Obtener el campo usubilleteraId de un objeto
     *
     * @return String usubilleteraId usubilleteraId
     *
     */
    public function getUsubilleteraId()
    {
        return $this->usubilleteraId;
    }




}

?>
