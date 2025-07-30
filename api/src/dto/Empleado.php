<?php 
namespace Backend\dto;
use Backend\mysql\EmpleadoMySqlDAO;
use Exception;
/** 
* Clase 'Empleado'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Empleado'
* 
* Ejemplo de uso: 
* $Empleado = new Empleado();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Empleado
{

    /**
    * Representación de la columna 'empleadoId' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $empleadoId;

    /**
    * Representación de la columna 'nombre' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $nombre;

    /**
    * Representación de la columna 'apellido' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $apellido;

    /**
    * Representación de la columna 'estado' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $usumodifId;

    /**
    * Representación de la columna 'tipodocId' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $tipodocId;

    /**
    * Representación de la columna 'documento' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $documento;
    
    /**
    * Representación de la columna 'cargoId' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $cargoId;
    
    /**
    * Representación de la columna 'areaId' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $areaId;
    
    /**
    * Representación de la columna 'salario' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $salario;
    
    /**
    * Representación de la columna 'mandante' de la tabla 'Empleado'
    *
    * @var string
    */ 
    var $mandante;

    /**
    * Constructor de clase
    *
    *
    * @param String $empleadoId id del empleado
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si el empleado no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($empleadoId = "", $codigo = "")
    {

        if ($empleadoId != "") {

            $EmpleadoMySqlDAO = new EmpleadoMySqlDAO();

            $Empleado = $EmpleadoMySqlDAO->load($empleadoId);


            if ($Empleado != null && $Empleado != "") {
                $this->empleadoId = $Empleado->empleadoId;
                $this->nombre = $Empleado->nombre;
                $this->apellido = $Empleado->apellido;
                $this->estado = $Empleado->estado;
                $this->usucreaId = $Empleado->usucreaId;
                $this->usumodifId = $Empleado->usumodifId;

                $this->tipodocId = $Empleado->tipodocId;
                $this->documento = $Empleado->documento;
                $this->cargoId = $Empleado->cargoId;
                $this->areaId = $Empleado->areaId;
                $this->salario = $Empleado->salario;
                $this->mandante = $Empleado->mandante;

            } else {
                throw new Exception("No existe " . get_class($this), "73");
            }

        } elseif ($codigo != "") {
            $EmpleadoMySqlDAO = new EmpleadoMySqlDAO();

            $Empleado = $EmpleadoMySqlDAO->queryByAbreviado($codigo);

            $Empleado = $Empleado[0];

            if ($Empleado != null && $Empleado != "") {
                $this->empleadoId = $Empleado->empleadoId;
                $this->nombre = $Empleado->nombre;
                $this->apellido = $Empleado->apellido;
                $this->estado = $Empleado->estado;
                $this->usucreaId = $Empleado->usucreaId;
                $this->usumodifId = $Empleado->usumodifId;

                $this->tipodocId = $Empleado->tipodocId;
                $this->documento = $Empleado->documento;
                $this->cargoId = $Empleado->cargoId;
                $this->areaId = $Empleado->areaId;
                $this->salario = $Empleado->salario;
                $this->mandante = $Empleado->mandante;
            } else {
                throw new Exception("No existe " . get_class($this), "73");
            }
        }
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
     * Obtener el campo apellido de un objeto
     *
     * @return String apellido apellido
     * 
     */ 
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Modificar el campo 'apellido' de un objeto
     *
     * @param String $apellido apellido
     *
     * @return no
     *
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
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
     * Obtener el campo tipodocId de un objeto
     *
     * @return String tipodocId tipodocId
     * 
     */ 
    public function getTipodocId()
    {
        return $this->tipodocId;
    }

    /**
     * Modificar el campo 'tipodocId' de un objeto
     *
     * @param String $tipodocId tipodocId
     *
     * @return no
     *
     */
    public function setTipodocId($tipodocId)
    {
        $this->tipodocId = $tipodocId;
    }

    /**
     * Obtener el campo documento de un objeto
     *
     * @return String documento documento
     * 
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
     * Obtener el campo cargoId de un objeto
     *
     * @return String cargoId cargoId
     * 
     */ 
    public function getCargoId()
    {
        return $this->cargoId;
    }

    /**
     * Modificar el campo 'cargoId' de un objeto
     *
     * @param String $cargoId cargoId
     *
     * @return no
     *
     */
    public function setCargoId($cargoId)
    {
        $this->cargoId = $cargoId;
    }

    /**
     * Obtener el campo areaId de un objeto
     *
     * @return String areaId areaId
     * 
     */ 
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * Modificar el campo 'areaId' de un objeto
     *
     * @param String $areaId areaId
     *
     * @return no
     *
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;
    }

    /**
     * Obtener el campo salario de un objeto
     *
     * @return String salario salario
     * 
     */ 
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Modificar el campo 'salario' de un objeto
     *
     * @param String $salario salario
     *
     * @return no
     *
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;
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
     * Obtener el campo empleadoId de un objeto
     *
     * @return String empleadoId empleadoId
     * 
     */ 
    public function getEmpleadoId()
    {
        return $this->empleadoId;
    }







    /**
    * Realizar una consulta en la tabla de empleados 'Empleado'
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
    public function getEmpleadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $EmpleadoMySqlDAO = new EmpleadoMySqlDAO();

        $clasificadores = $EmpleadoMySqlDAO->queryEmpleadoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "73");
        }

    }


}

?>