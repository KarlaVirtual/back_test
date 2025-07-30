<?php namespace Backend\dto;
use Backend\mysql\FranquiciaMySqlDAO;
use Exception;
/**
 * Clase 'Franquicia'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Franquicia'
 *
 * Ejemplo de uso:
 * $Franquicia = new Franquicia();
 *
 *
 * @package ninguno
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class Franquicia
{

    /**
     * Representación de la columna 'FranquiciaId' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $franquiciaId;

    /**
     * Representación de la columna 'descripcion' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'paisId' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'verifica' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $verifica;

    /**
     * Representación de la columna 'usucrea_id' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'abreviado' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $abreviado;

    /**
     * Representación de la columna 'imagen' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $imagen;

    /**
     * Constructor de clase
     *
     *
     * @param String $franquiciaId id de la Franquicia
     *
     * @throws Exception si el Franquicia no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($franquiciaId="")
    {

        if($franquiciaId != "")
        {

            $FranquiciaMySqlDAO = new FranquiciaMySqlDAO();

            $Franquicia = $FranquiciaMySqlDAO->load($franquiciaId);


            if ($Franquicia != null && $Franquicia != "")
            {
                $this->descripcion = $Franquicia->descripcion;
                $this->franquiciaId = $Franquicia->franquiciaId;
                $this->estado = $Franquicia->estado;
                $this->tipo = $Franquicia->tipo;
                $this->verifica = $Franquicia->verifica;
                $this->usumodifId = $Franquicia->usumodifId;
                $this->abreviado = $Franquicia->abreviado;
                $this->usucreaId = $Franquicia->usucreaId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "35");
            }

        }

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
     * Realizar una consulta en la tabla de Franquicia 'Franquicia'
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
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFranquiciasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $FranquiciaMySqlDAO = new FranquiciaMySqlDAO();

        $bonos = $FranquiciaMySqlDAO->queryFranquiciasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($bonos != null && $bonos != "")
        {
            return $bonos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "35");
        }


    }



}
?>