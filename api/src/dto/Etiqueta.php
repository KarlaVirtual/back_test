<?php 
namespace Backend\dto;

use Backend\mysql\EtiquetaMySqlDAO;

/**
 * Clase 'Etiqueta'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Etiqueta'
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 */



class Etiqueta
{
    /**
     * Representación de la columna 'etiquetaId' de la tabla 'Etiqueta'
     *
     * @var int
     */
    public $etiquetaId;

    /**
     * Representación de la columna 'etiquetaId' de la tabla 'Etiqueta'
     *
     * @var int
     */
    public $descripcion;

    /**
     * Representación de la columna 'nombre' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $nombre;


    /**
     * Representación de la columna 'fecha_crea' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $fechaCrea;

    /**
     * Representación de la columna 'fecha_modif' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $fechaModif;


    /**
     * Representación de la columna 'usucrea_id' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $usucreaId;


    /**
     * Representación de la columna 'usumodif_id' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $usumodifId;


    /**
     * Representación de la columna 'estado' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $estado;
    /**
     * Representación de la columna 'mandante' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $mandante;
    /**
     * Representación de la columna 'pais_id' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $pais_id;
    /**
     * Representación de la columna 'tipo' de la tabla 'Etiqueta'
     *
     * @var string
     */
    public $tipo;



    /**
     * Constructor de clase
     *
     * @param int $etiquetaId ID de la etiqueta
     * @param string $nombre Nombre del etiqueta
     */
    public function __construct($etiquetaId ="", $nombre ="")
    {



        if ($etiquetaId != "")
        {


            $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();

            $Etiqueta = $EtiquetaMySqlDAO->load($etiquetaId);


            if ($Etiqueta != null && $Etiqueta != "")
            {

                $this->nombre = $Etiqueta->nombre;
                $this->descripcion = $Etiqueta->descripcion;
                $this->fechaCrea = $Etiqueta->fechaCrea;
                $this->fechaModif = $Etiqueta->fechaModif;
                $this->usucreaId = $Etiqueta->usucreaId;
                $this->usumodifId = $Etiqueta->usumodifId;
                $this->estado = $Etiqueta->estado;
                $this->etiquetaId = $Etiqueta->etiquetaId;

            } else {
                throw new Exception("No existe " . get_class($this), "201");

            }

        }


    }

    /**
     * Obtener el campo 'etiquetaId' de un objeto
     *
     * @return int etiquetaId
     */
    public function getEtiquetaId()
    {
        return $this->etiquetaId;
    }

    /**
     * Modificar el campo 'etiquetaId' de un objeto
     *
     * @param int $etiquetaId
     */
    public function setEtiquetaId($etiquetaId)
    {
        $this->etiquetaId = $etiquetaId;
    }

    /**
     * Obtener el campo 'nombre' de un objeto
     *
     * @return string Nombre del etiqueta
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param string $nombre Nombre del etiqueta
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String descripcion descripcion
     *
     */

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }


    /**
     * Obtener el campo 'fechaCrea' de un objeto etiqueta
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Definir el campo 'fechaCrea' de un objeto etiqueta
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }


    /**
     * Obtener el campo 'fechaModif' de un objeto etiqueta
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaCrea;
    }

    /**
     * Definir el campo 'fechaModif' de un objeto etiqueta
     * @param mixed $fechaCrea
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo 'mandante' de un objeto etiqueta
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo 'mandante' de un objeto etiqueta
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->pais_id = $paisId;
    }


    /**
     * Establece el tipo de la etiqueta.
     *
     * @param string $tipo El tipo de la etiqueta.
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }


    /**
     * Realizar una consulta en la tabla de productos 'Etiqueta'
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
    public function getEtiquetasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$filtroNUll = false)
    {

        $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();

        $Etiquetas = $EtiquetaMySqlDAO->queryEtiquetasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$filtroNUll);

        if ($Etiquetas != null && $Etiquetas != "")
        {
            return $Etiquetas;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }
}
?>
