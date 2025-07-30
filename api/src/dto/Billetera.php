<?php namespace Backend\dto;
use Backend\mysql\BilleteraMySqlDAO;
use Exception;
/** 
* Clase 'Billetera'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Billetera'
* 
* Ejemplo de uso: 
* $Billetera = new Billetera();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Billetera
{

    /**
    * Representación de la columna 'billeteraId' de la tabla 'Billetera'
    *
    * @var string
    */
    var $billeteraId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Billetera'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Billetera'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Billetera'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Billetera'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Constructor de clase
    *
    *
    * @param String $billeteraId id del área
    * @param String $codigo codigo del área
    *
    * @throws Exception si el área no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($billeteraId = "", $codigo = "")
    {

        if ($billeteraId != "") 
        {

            $BilleteraMySqlDAO = new BilleteraMySqlDAO();

            $Billetera = $BilleteraMySqlDAO->load($billeteraId);


            if ($Billetera != null && $Billetera != "") 
            {
                $this->billeteraId = $Billetera->billeteraId;
                $this->descripcion = $Billetera->descripcion;
                $this->estado = $Billetera->estado;
                $this->usucreaId = $Billetera->usucreaId;
                $this->usumodifId = $Billetera->usumodifId;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "108");
            }

        }
    }






    /**
     * Obtener el campo billeteraId de un objeto
     *
     * @return String billeteraId Id de el área relacionada
     * 
     */
    public function getBilleteraId()
    {
        return $this->billeteraId;
    }

    /**
     * Obtener la descripción de un objeto
     *
     * @return String Descripción descripcion del objeto
     *
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar la descripcion de un objeto
     *
     * @param String $Descripcion descripción del objeto
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el estado de un objeto
     *
     *
     * @return String Estado estado del objeto
     *
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el estado de un objeto
     *
     * @param String Estado estado del objeto
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo UsucreaId de un objeto
     *
     *
     * @return String UsucreaId del objeto
     *
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo UsucreaId de un objeto
     *
     * @param String $UsucreaId UsucreaId del objeto
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo UsumodifId de un objeto
     *
     *
     * @return String $UsumodifId
     *
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo UsomodifId de un objeto
     *
     * @param String $usomodifId usomodifId de un objeto
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }


    /**
    * Realizar una consulta en la tabla de áreas 'Área'
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
    * @throws si los clasificadores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getBilleterasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $BilleteraMySqlDAO = new BilleteraMySqlDAO();

        $clasificadores = $BilleteraMySqlDAO->queryBilleteraesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "") 
        {
            return $clasificadores;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "108");
        }

    }


}

?>