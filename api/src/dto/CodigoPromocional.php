<?php namespace Backend\dto;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Exception;
/**
* Clase 'CodigoPromocional'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'CodigoPromocional'
*
* Ejemplo de uso:
* $CodigoPromocional = new CodigoPromocional();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class CodigoPromocional
{

    /**
    * Representación de la columna 'codpromocionalId' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $codpromocionalId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'codigo' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $codigo;

    /**
    * Representación de la columna 'link' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $linkId;

    /**
    * Representación de la columna 'estado' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'CodigoPromocional'
    *
    * @var string
    */
    var $usumodifId;

    var $funcion;

    var $descripcion;

    var $mandante;
    var $paisId;

    /**
    * Constructor de clase
    *
    *
    * @param String $codpromocionalId codpromocionalId
    * @param String $usuarioId codpromocionalId
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si el CodigoPromocional no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($codpromocionalId="", $usuarioId="",$codigo="",$function="")
    {
        if ($codpromocionalId != "")
        {

            $this->codpromocionalId = $codpromocionalId;

            $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

            $CodigoPromocional = $CodigoPromocionalMySqlDAO->load($codpromocionalId);

            if ($CodigoPromocional != null && $CodigoPromocional != "")
            {
                $this->codpromocionalId = $CodigoPromocional->codpromocionalId;
                $this->usuarioId = $CodigoPromocional->usuarioId;
                $this->codigo = $CodigoPromocional->codigo;
                $this->linkId = $CodigoPromocional->linkId;
                $this->funcion = $CodigoPromocional->funcion;
                $this->estado = $CodigoPromocional->estado;
                $this->usucreaId = $CodigoPromocional->usucreaId;
                $this->usumodifId = $CodigoPromocional->usumodifId;
                $this->descripcion = $CodigoPromocional->descripcion;
                $this->mandante = $CodigoPromocional->mandante;
                $this->paisId = $CodigoPromocional->paisId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "85");
            }

        }
        elseif ($usuarioId != "" && $codigo != "")
        {
            $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

            $CodigoPromocional = $CodigoPromocionalMySqlDAO->queryByUsuarioIdAndBannerId($usuarioId,$codigo);
            $CodigoPromocional = $CodigoPromocional[0];

            if ($CodigoPromocional != null && $CodigoPromocional != "")
            {
                $this->codpromocionalId = $CodigoPromocional->codpromocionalId;
                $this->usuarioId = $CodigoPromocional->usuarioId;
                $this->codigo = $CodigoPromocional->codigo;
                $this->linkId = $CodigoPromocional->linkId;
                $this->funcion = $CodigoPromocional->funcion;
                $this->estado = $CodigoPromocional->estado;
                $this->usucreaId = $CodigoPromocional->usucreaId;
                $this->usumodifId = $CodigoPromocional->usumodifId;
                $this->descripcion = $CodigoPromocional->descripcion;
                $this->mandante = $CodigoPromocional->mandante;
                $this->paisId = $CodigoPromocional->paisId;

            }
            else {
                throw new Exception("No existe " . get_class($this), "85");
            }

        }elseif ($codigo != "" && $function != ""){
            $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

            $CodigoPromocional = $CodigoPromocionalMySqlDAO->queryByCodigoAndFunction($codigo,$function);
            $CodigoPromocional = $CodigoPromocional[0];

            if ($CodigoPromocional != null && $CodigoPromocional != "") {
                $this->codpromocionalId = $CodigoPromocional->codpromocionalId;
                $this->usuarioId = $CodigoPromocional->usuarioId;
                $this->codigo = $CodigoPromocional->codigo;
                $this->linkId = $CodigoPromocional->linkId;
                $this->funcion = $CodigoPromocional->funcion;
                $this->estado = $CodigoPromocional->estado;
                $this->usucreaId = $CodigoPromocional->usucreaId;
                $this->usumodifId = $CodigoPromocional->usumodifId;
                $this->descripcion = $CodigoPromocional->descripcion;
                $this->mandante = $CodigoPromocional->mandante;
                $this->paisId = $CodigoPromocional->paisId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "85");
            }

        }
        elseif ( $usuarioId != "") {


            $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

            $CodigoPromocional = $CodigoPromocionalMySqlDAO->queryByExternoIdAndProveedorId($usuarioId);
            $CodigoPromocional = $CodigoPromocional[0];

            if ($CodigoPromocional != null && $CodigoPromocional != "")
            {
                $this->codpromocionalId = $CodigoPromocional->codpromocionalId;
                $this->usuarioId = $CodigoPromocional->usuarioId;
                $this->codigo = $CodigoPromocional->codigo;
                $this->linkId = $CodigoPromocional->linkId;
                $this->funcion = $CodigoPromocional->funcion;
                $this->estado = $CodigoPromocional->estado;
                $this->usucreaId = $CodigoPromocional->usucreaId;
                $this->usumodifId = $CodigoPromocional->usumodifId;
                $this->descripcion = $CodigoPromocional->descripcion;
                $this->mandante = $CodigoPromocional->mandante;
                $this->paisId = $CodigoPromocional->paisId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "85");
            }
        }

    }






    /**
    * Realizar una consulta en la tabla de codigo_promocional 'CodigoPromocional'
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
    public function getCodigoPromocionalsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

        $Productos = $CodigoPromocionalMySqlDAO->queryCodigoPromocionalsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
     * Obtener el campo codigo de un objeto
     *
     * @return String codigo codigo
     *
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Modificar el campo 'codigo' de un objeto
     *
     * @param String $codigo codigo
     *
     * @return no
     *
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Obtener el campo link de un objeto
     *
     * @return String link link
     *
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Modificar el campo 'link' de un objeto
     *
     * @param String $link link
     *
     * @return no
     *
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
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
     * @return mixed
     */
    public function getFuncion()
    {
        return $this->funcion;
    }

    /**
     * @param mixed $funcion
     */
    public function setFuncion($funcion)
    {
        $this->funcion = $funcion;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }



    /**
     * Obtener el campo codpromocionalId de un objeto
     *
     * @return String codpromocionalId codpromocionalId
     *
     */
    public function getCodpromocionalId()
    {
        return $this->codpromocionalId;
    }

    /**
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el ID del país asociado al código promocional.
     *
     * @return int El ID del país.
     */
    public function getPaisId() {
        return $this->paisId;
    }

    /**
     * Establece el ID del país.
     *
     * @param int $paisId El ID del país.
     */
    public function setPaisId($paisId) {
        $this->paisId = $paisId;
    }

    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getCodigoPromocionalsCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

        $Productos = $CodigoPromocionalMySqlDAO->queryCodigoPromocionalsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }


}

?>
