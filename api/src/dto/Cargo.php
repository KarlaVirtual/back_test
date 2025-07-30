<?php 
namespace Backend\dto;
use Backend\mysql\CargoMySqlDAO;
use Exception;
/**
* Clase 'Cargo'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Cargo'
*
* Ejemplo de uso:
* $Cargo = new Cargo();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Cargo
{

    /**
    * Representación de la columna 'cargoId' de la tabla 'Cargo'
    *
    * @var string
    */
    var $cargoId;

    /**
    * Representación de la columna 'tipoId' de la tabla 'Cargo'
    *
    * @var string
    */
    var $tipoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Cargo'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Cargo'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Cargo'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Cargo'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Constructor de clase
    *
    *
    * @param String $cargoId id del cargo
    * @param String $codigo codigo del cargo
    *
    * @return no
    * @throws Exception si el cargo no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($cargoId = "", $codigo = "")
    {

        if ($cargoId != "")
        {

            $CargoMySqlDAO = new CargoMySqlDAO();

            $Cargo = $CargoMySqlDAO->load($cargoId);


            if ($Cargo != null && $Cargo != "")
            {
                $this->cargoId = $Cargo->cargoId;
                $this->tipoId = $Cargo->tipoId;
                $this->descripcion = $Cargo->descripcion;
                $this->estado = $Cargo->estado;
                $this->usucreaId = $Cargo->usucreaId;
                $this->usumodifId = $Cargo->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "71");
            }

        }
        elseif ($codigo != "")
        {
            $CargoMySqlDAO = new CargoMySqlDAO();

            $Cargo = $CargoMySqlDAO->queryByAbreviado($codigo);

            $Cargo = $Cargo[0];

            if ($Cargo != null && $Cargo != "")
            {
                $this->cargoId = $Cargo->cargoId;
                $this->tipoId = $Cargo->tipoId;
                $this->descripcion = $Cargo->descripcion;
                $this->estado = $Cargo->estado;
                $this->usucreaId = $Cargo->usucreaId;
                $this->usumodifId = $Cargo->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "71");
            }
        }
    }






    /**
     * Obtener el campo Tipo de un objeto
     *
     * @return String Tipo tipo
     *
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Modificar el campo 'tipoId' de un objeto
     *
     * @param String $tipoId tipoId
     *
     * @return no
     *
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
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
     * Obtener el campo Estado de un objeto
     *
     * @return String Estado estado
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
     * Obtener el campo Abreviado de un objeto
     *
     * @return String Abreviado abreviado
     *
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Modificar el campo 'Abreviado' de un objeto
     *
     * @param String $Abreviado abreviado
     *
     * @return no
     *
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId Id del empleado
     *
     * @return no
     *
     */
    public function getCargoId()
    {
        return $this->cargoId;
    }






    /**
    * Realizar una consulta en la tabla de cargo 'Cargo'
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
    public function getCargosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CargoMySqlDAO = new CargoMySqlDAO();

        $clasificadores = $CargoMySqlDAO->queryCargoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "")
        {
            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "71");
        }

    }


}

?>