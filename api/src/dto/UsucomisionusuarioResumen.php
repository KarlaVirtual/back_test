<?php namespace Backend\dto;
use Backend\mysql\UsucomisionusuarioResumenMySqlDAO;
use Exception;
/** 
* Clase 'UsucomisionusuarioResumen'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsucomisionusuarioResumen'
* 
* Ejemplo de uso: 
* $UsucomisionusuarioResumen = new UsucomisionusuarioResumen();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsucomisionusuarioResumen
{

    /**
    * Representación de la columna 'usucomusuresumenId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usucomusuresumenId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'usuariorefId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usuariorefId;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $externoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'comision' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $comision;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucambioId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usucambioId;

    /**
    * Representación de la columna 'usupagoId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usupagoId;

    /**
    * Representación de la columna 'usurechazaId' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $usurechazaId;

    /**
    * Representación de la columna 'mensajeUsuario' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $mensajeUsuario;

    /**
    * Representación de la columna 'observacion' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $observacion;

    /**
    * Representación de la columna 'valorPagado' de la tabla 'UsucomisionusuarioResumen'
    *
    * @var string
    */
    var $valorPagado;





    /**
    * Constructor de clase
    *
    *
    * @param String $usucomusuresumenId usucomusuresumenId
    * @param String $usuarioId usuarioId
    * @param String $externoId externoId
    *
    * @return no
    * @throws Exception si UsucomisionusuarioResumen no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usucomusuresumenId="", $usuarioId="",$externoId="")
    {
        if ($usucomusuresumenId != "") 
        {

            $UsucomisionusuarioResumenMySqlDAO = new UsucomisionusuarioResumenMySqlDAO();

            $UsucomisionusuarioResumen = $UsucomisionusuarioResumenMySqlDAO->load($usucomusuresumenId);

            if ($UsucomisionusuarioResumen != null && $UsucomisionusuarioResumen != "") 
            {
                $this->usucomusuresumenId = $UsucomisionusuarioResumen->usucomusuresumenId;
                $this->usuarioId = $UsucomisionusuarioResumen->usuarioId;
                $this->usuariorefId = $UsucomisionusuarioResumen->usuariorefId;
                $this->externoId = $UsucomisionusuarioResumen->externoId;
                $this->tipo = $UsucomisionusuarioResumen->tipo;
                $this->valor = $UsucomisionusuarioResumen->valor;
                $this->comision = $UsucomisionusuarioResumen->comision;
                $this->usucreaId = $UsucomisionusuarioResumen->usucreaId;
                $this->usumodifId = $UsucomisionusuarioResumen->usumodifId;
                $this->estado = $UsucomisionusuarioResumen->estado;
                $this->usucambioId = $UsucomisionusuarioResumen->usucambioId;
                $this->usupagoId = $UsucomisionusuarioResumen->usupagoId;
                $this->usurechazaId = $UsucomisionusuarioResumen->usurechazaId;
                $this->mensajeUsuario = $UsucomisionusuarioResumen->mensajeUsuario;
                $this->observacion = $UsucomisionusuarioResumen->observacion;
                $this->valorPagado = $UsucomisionusuarioResumen->valorPagado;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }




    /**
    * Realizar una consulta en la tabla de UsucomisionusuarioResumen 'UsucomisionusuarioResumen'
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
    public function getUsucomisionusuarioResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsucomisionusuarioResumenMySqlDAO = new UsucomisionusuarioResumenMySqlDAO();

        $Productos = $UsucomisionusuarioResumenMySqlDAO->queryUsucomisionusuarioResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

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
    * Realizar una consulta en la tabla de UsucomisionusuarioResumen 'UsucomisionusuarioResumen'
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
    public function getUsucomisionusuarioResumenGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $group)
    {

        $UsucomisionusuarioResumenMySqlDAO = new UsucomisionusuarioResumenMySqlDAO();

        $Productos = $UsucomisionusuarioResumenMySqlDAO->queryUsucomisionusuarioResumensGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group);

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
     * Obtener el campo usucomusuresumenId de un objeto
     *
     * @return String usucomusuresumenId usucomusuresumenId
     * 
     */
    public function getUsucomisionId()
    {
        return $this->usucomusuresumenId;
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
     * Obtener el campo comision de un objeto
     *
     * @return String comision comision
     * 
     */
    public function getComision()
    {
        return $this->comision;
    }

    /**
     * Modificar el campo 'comision' de un objeto
     *
     * @param String $comision comision
     *
     * @return no
     *
     */
    public function setComision($comision)
    {
        $this->comision = $comision;
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
     * Obtener el campo usucambioId de un objeto
     *
     * @return String usucambioId usucambioId
     * 
     */
    public function getUsucambioId()
    {
        return $this->usucambioId;
    }

    /**
     * Modificar el campo 'usucambioId' de un objeto
     *
     * @param String $usucambioId usucambioId
     *
     * @return no
     *
     */
    public function setUsucambioId($usucambioId)
    {
        $this->usucambioId = $usucambioId;
    }

    /**
     * Obtener el campo usupagoId de un objeto
     *
     * @return String usupagoId usupagoId
     * 
     */
    public function getUsupagoId()
    {
        return $this->usupagoId;
    }

    /**
     * Modificar el campo 'usupagoId' de un objeto
     *
     * @param String $usupagoId usupagoId
     *
     * @return no
     *
     */
    public function setUsupagoId($usupagoId)
    {
        $this->usupagoId = $usupagoId;
    }

    /**
     * Obtener el campo usurechazaId de un objeto
     *
     * @return String usurechazaId usurechazaId
     * 
     */
    public function getUsurechazaId()
    {
        return $this->usurechazaId;
    }

    /**
     * Modificar el campo 'usurechazaId' de un objeto
     *
     * @param String $usurechazaId usurechazaId
     *
     * @return no
     *
     */
    public function setUsurechazaId($usurechazaId)
    {
        $this->usurechazaId = $usurechazaId;
    }

    /**
     * Obtener el campo mensajeUsuario de un objeto
     *
     * @return String mensajeUsuario mensajeUsuario
     * 
     */
    public function getMensajeUsuario()
    {
        return $this->mensajeUsuario;
    }

    /**
     * Modificar el campo 'mensajeUsuario' de un objeto
     *
     * @param String $mensajeUsuario mensajeUsuario
     *
     * @return no
     *
     */
    public function setMensajeUsuario($mensajeUsuario)
    {
        $this->mensajeUsuario = $mensajeUsuario;
    }

    /**
     * Obtener el campo observacion de un objeto
     *
     * @return String observacion observacion
     * 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Modificar el campo 'observacion' de un objeto
     *
     * @param String $observacion observacion
     *
     * @return no
     *
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * Obtener el campo usucomusuresumenId de un objeto
     *
     * @return String usucomusuresumenId usucomusuresumenId
     * 
     */
    public function getUsucomresumenId()
    {
        return $this->usucomusuresumenId;
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



}

?>
