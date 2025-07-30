<?php namespace Backend\dto;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Exception;
/**
* Clase 'ProveedorTercero'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProveedorTercero'
*
* Ejemplo de uso:
* $ProveedorTercero = new ProveedorTercero();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ProveedorTercero
{

    /**
    * Representación de la columna 'proveedortercId' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $proveedortercId;

    /**
    * Representación de la columna 'documento' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $documento;

    /**
    * Representación de la columna 'descripcion' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'paisId' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $paisId;

    /**
    * Representación de la columna 'email' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $email;

    /**
    * Representación de la columna 'mandante' de la tabla 'ProveedorTercero'
    *
    * @var string
    */
    var $mandante;

    /**
     * ProveedorTercero constructor.
     * @param $proveedortercId

    /**
    * Constructor de clase
    *
    *
    * @param String $proveedortercId proveedortercId proveedortercId
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si ProveedorTercero no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($proveedortercId = "", $codigo = "")
    {

        if ($proveedortercId != "")
        {

            $ProveedorTerceroMySqlDAO = new ProveedorTerceroMySqlDAO();

            $ProveedorTercero = $ProveedorTerceroMySqlDAO->load($proveedortercId);


            if ($ProveedorTercero != null && $ProveedorTercero != "")
            {
                $this->proveedortercId = $ProveedorTercero->proveedortercId;
                $this->documento = $ProveedorTercero->documento;
                $this->descripcion = $ProveedorTercero->descripcion;
                $this->estado = $ProveedorTercero->estado;
                $this->usucreaId = $ProveedorTercero->usucreaId;
                $this->usumodifId = $ProveedorTercero->usumodifId;
                $this->paisId = $ProveedorTercero->paisId;
                $this->email = $ProveedorTercero->email;
                $this->mandante = $ProveedorTercero->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "75");
            }

        }
        elseif ($codigo != "")
        {

            $ProveedorTerceroMySqlDAO = new ProveedorTerceroMySqlDAO();

            $ProveedorTercero = $ProveedorTerceroMySqlDAO->queryByAbreviado($codigo);

            $ProveedorTercero = $ProveedorTercero[0];

            if ($ProveedorTercero != null && $ProveedorTercero != "")
            {
                $this->proveedortercId = $ProveedorTercero->proveedortercId;
                $this->documento = $ProveedorTercero->documento;
                $this->descripcion = $ProveedorTercero->descripcion;
                $this->estado = $ProveedorTercero->estado;
                $this->usucreaId = $ProveedorTercero->usucreaId;
                $this->usumodifId = $ProveedorTercero->usumodifId;
                $this->paisId = $ProveedorTercero->paisId;
                $this->email = $ProveedorTercero->email;
                $this->mandante = $ProveedorTercero->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "75");
            }
        }
    }





    /**
     * Obtener el campo documento del objeto proveedorTercero
     * @return mixed
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Modificar el campo 'documento' de un objeto
     *
     * @param String $documento documento
     *
     * @return no
     *
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
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
     * Obtener el campo proveedortercId de un objeto
     * @return mixed
     */
    public function getProveedortercId()
    {
        return $this->proveedortercId;
    }

    /**
     * Obtener el campo paisId de un objeto
     * @return mixed
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Modificar el campo 'paisId' de un objeto
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtener el campo email de un objeto
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Modificar el campo 'email' de un objeto
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Obtener el campo mandante de un objeto
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }







    /**
    * Realizar una consulta en la tabla de ProveedorTercero 'ProveedorTercero'
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
    * @throws Exception si los clasificadores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProveedorTercerosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProveedorTerceroMySqlDAO = new ProveedorTerceroMySqlDAO();

        $clasificadores = $ProveedorTerceroMySqlDAO->queryProveedorTerceroesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "")
        {
            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "75");
        }

    }


}

?>