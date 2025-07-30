<?php namespace Backend\dto;
use Backend\mysql\FranquiciaMandanteMySqlDAO;
use Exception;
/**
 * Clase 'FranquiciaMandante'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'FranquiciaMandante'
 *
 * Ejemplo de uso:
 * $FranquiciaMandante = new FranquiciaMandante();
 *
 *
 * @package ninguno
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class FranquiciaMandante
{

    /**
     * Representación de la columna 'franquiciamandanteId' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $franquiciamandanteId;

    /**
     * Representación de la columna 'FranquiciaId' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $franquiciaId;

    /**
     * Representación de la columna 'mandante' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'estado' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'verifica' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $verifica;

    /**
     * Representación de la columna 'pais_id' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $paisId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $usumodifId;


    /**
     * Representación de la columna 'detalle' de la tabla 'FranquiciaMandante'
     *
     * @var string
     */
    var $detalle;

    /**
     * Constructor de clase
     *
     *
     * @param String $franquiciaId id del Franquicia
     * @param String $mandante mandante
     * @param String $franquiciamandanteId franquiciamandanteId
     *
     * @return no
     * @throws Exception si FranquiciaMandante no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($franquiciaId="", $mandante="", $franquiciamandanteId="")
    {
        if ($franquiciaId != "" && $mandante != "") {

            $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO();

            $FranquiciaMandante = $FranquiciaMandanteMySqlDAO->queryByFranquiciaIdAndMandante($franquiciaId, $mandante);
            $FranquiciaMandante = $FranquiciaMandante[0];
            if ($FranquiciaMandante != null && $FranquiciaMandante != "") {
                $this->franquiciamandanteId = $FranquiciaMandante->franquiciamandanteId;
                $this->franquiciaId = $FranquiciaMandante->franquiciaId;
                $this->mandante = $FranquiciaMandante->mandante;
                $this->estado = $FranquiciaMandante->estado;
                $this->verifica = $FranquiciaMandante->verifica;
                $this->detalle = $FranquiciaMandante->detalle;
                $this->usucreaId = $FranquiciaMandante->usucreaId;
                $this->usumodifId = $FranquiciaMandante->usumodifId;
            }
            else {
                throw new Exception("No existe " . get_class($this), "27");
            }
        }
        elseif ($franquiciamandanteId != "") {
            $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO();

            $FranquiciaMandante = $FranquiciaMandanteMySqlDAO->load($franquiciamandanteId);

            if ($FranquiciaMandante != null && $FranquiciaMandante != "") {
                $this->franquiciamandanteId = $FranquiciaMandante->franquiciamandanteId;
                $this->franquiciaId = $FranquiciaMandante->franquiciaId;
                $this->mandante = $FranquiciaMandante->mandante;
                $this->estado = $FranquiciaMandante->estado;
                $this->verifica = $FranquiciaMandante->verifica;
                $this->detalle = $FranquiciaMandante->detalle;
                $this->usucreaId = $FranquiciaMandante->usucreaId;
                $this->usumodifId = $FranquiciaMandante->usumodifId;
            }
            else {
                throw new Exception("No existe " . get_class($this), "27");
            }
        }

    }

    /**
     * Realizar una consulta en la tabla de FranquiciaMandante 'FranquiciaMandante'
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
    public function getFranquiciasMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO();

        $Productos = $FranquiciaMandanteMySqlDAO->queryFranquiciasMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
?>