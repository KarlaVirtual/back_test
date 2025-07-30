<?php namespace Backend\dto;
use Backend\mysql\UsuariocomisionPagadoMySqlDAO;
use Exception;
/** 
* Clase 'UsuariocomisionPagado'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuariocomisionPagado'
* 
* Ejemplo de uso: 
* $UsuariocomisionPagado = new UsuariocomisionPagado();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuariocomisionPagado
{
    /**
    * Representación de la columna 'usucomisionpagadoId' de la tabla 'UsuariocomisionPagado'
    *
    * @var string
    */
    var $usucomisionpagadoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuariocomisionPagado'
    *
    * @var string
    */
    var $usuarioId;


    /**
     * Representación de la columna 'fechaInicio' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $fechaInicio;


    /**
     * Representación de la columna 'fechaFin' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'valorPagado' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $valorPagado;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'tipo' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'CuentaCobro'
     *
     * @var string
     */
    var $fechaCrea;


    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $usucreaId;


    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */
    var $usumodifId;


    /**
     * Representación de la columna 'mandante' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */

    var $mandante;

    /**
     * Representación de la columna 'tipo_comision' de la tabla 'UsuariocomisionPagado'
     *
     * @var string
     */

    var $tipoComision;
    /**
    * Constructor de clase
    *
    *
    * @param String $usucomisionpagadoId usucomisionpagadoId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuariocomisionPagado no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usucomisionpagadoId="", $usuarioId="")
    {
        if ($usucomisionpagadoId != "") 
        {

            $UsuariocomisionPagadoMySqlDAO = new UsuariocomisionPagadoMySqlDAO();

            $UsuariocomisionPagado = $UsuariocomisionPagadoMySqlDAO->load($usucomisionpagadoId);

            if ($UsuariocomisionPagado != null && $UsuariocomisionPagado != "") 
            {
                $this->usucomisionpagadoId = $UsuariocomisionPagado->usucomisionpagadoId;
                $this->usuarioId = $UsuariocomisionPagado->usuarioId;
                $this->fechaInicio = $UsuariocomisionPagado->fechaInicio;
                $this->fechaFin = $UsuariocomisionPagado->fechaFin;
                $this->valorPagado = $UsuariocomisionPagado->valorPagado;
                $this->estado = $UsuariocomisionPagado->estado;
                $this->tipo = $UsuariocomisionPagado->tipo;
                $this->fechaCrea = $UsuariocomisionPagado->fechaCrea;
                $this->usucreaId = $UsuariocomisionPagado->usucreaId;
                $this->fechaModif = $UsuariocomisionPagado->fechaModif;
                $this->usumodifId = $UsuariocomisionPagado->usumodifId;
                $this->mandante = $UsuariocomisionPagado->mandante;
                $this->tipoComision = $UsuariocomisionPagado->tipoComision;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }




    /**
    * Realizar una consulta en la tabla de UsuariocomisionPagado 'UsuariocomisionPagado'
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
    public function getUsuariocomisionPagadoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsuariocomisionPagadoMySqlDAO = new UsuariocomisionPagadoMySqlDAO();

        $Productos = $UsuariocomisionPagadoMySqlDAO->queryUsuariocomisionPagadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

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
     * Realizar una consulta en la tabla de UsuariocomisionPagado 'UsuariocomisionPagado'
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
     * @param int $concesionarioId para consultar por concesionario
     *
     * @return Array resultado de la consulta
     * @throws Exception si los productos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuariocomisionPagadoCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$concesionarioId)
    {

        $UsuariocomisionPagadoMySqlDAO = new UsuariocomisionPagadoMySqlDAO();

        $Productos = $UsuariocomisionPagadoMySqlDAO->queryUsuariocomisionPagadosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$concesionarioId);

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
    * Realizar una consulta en la tabla de UsuariocomisionPagado 'UsuariocomisionPagado'
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
    * @param String $group columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuariocomisionPagadoGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $group)
    {

        $UsuariocomisionPagadoMySqlDAO = new UsuariocomisionPagadoMySqlDAO();

        $Productos = $UsuariocomisionPagadoMySqlDAO->queryUsuariocomisionPagadosGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group);

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
     * Obtener el campo usucomisionpagadoId de un objeto
     *
     * @return String usucomisionpagadoId usucomisionpagadoId
     *
     */
    public function getUsucomisionpagadoId()
    {
        return $this->usucomisionpagadoId;
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
     * Obtener el campo fechaInicio de un objeto
     *
     * @return String fechaInicio fechaInicio
     *
     */
    public function getfechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Modificar el campo 'fechaInicio' de un objeto
     *
     * @param String $fechaInicio fechaInicio
     *
     * @return no
     *
     */
    public function setfechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }


    /**
     * Obtener el campo fechaFin de un objeto
     *
     * @return String fechaFin fechaFin
     *
     */
    public function getfechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Modificar el campo 'fechaFin' de un objeto
     *
     * @param String $fechaFin fechaFin
     *
     * @return no
     *
     */
    public function setfechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }



    /**
     * Obtener el campo valorPagado de un objeto
     *
     * @return String valorPagado valorPagado
     *
     */
    public function getValorPagado()
    {
        return $this->valorPagado;
    }

    /**
     * Modificar el campo 'valorPagado' de un objeto
     *
     * @param String $valorPagado valorPagado
     *
     * @return no
     *
     */
    public function setValorPagado($valorPagado)
    {
        $this->valorPagado = $valorPagado;
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
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     *
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
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
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     *
     */
    public function getfechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setfechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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
     * @return String $mandante mandante
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





















}

?>
