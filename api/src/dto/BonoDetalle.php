<?php 
namespace Backend\dto;
use Backend\mysql\BonoDetalleMySqlDAO;
use Exception;
/** 
* Clase 'BonoDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'BonoDetalle'
* 
* Ejemplo de uso: 
* $BonoDetalle = new BonoDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class BonoDetalle
{

    /**
    * Representación de la columna 'bonodetalleId' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $bonodetalleId;

    /**
    * Representación de la columna 'bonoId' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $bonoId;

    /**
    * Representación de la columna 'tipo' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $tipo;

    /**
    * Representación de la columna 'moneda' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $moneda;

    /**
    * Representación de la columna 'valor' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $valor;

    /**
    * Representación de la columna 'usucreaId' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $usucreaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $fechaCrea;

    /**
    * Representación de la columna 'usumodifId' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $usumodifId;

    /**
    * Representación de la columna 'fechaModif' de la tabla BonoDetalle
    *
    * @var string
    */  
    public $fechaModif;


    /**
    * Constructor de clase
    *
    *
    * @param String $bonodetalleId id de BonoDetalle
    * @param String $bonoId id del bono
    * @param String $tipo tipo
    * @param String $tipo tipo
    *
    * @throws Exception si el BonoDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($bonodetalleId="",$bonoId="",$tipo="",$moneda="")
    {

        if ($bonodetalleId != "") 
        {

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

            $BonoDetalle = $BonoDetalleMySqlDAO->load($bonodetalleId);


            $this->success = false;

            if ($BonoDetalle != null && $BonoDetalle != "") 
            {
                $this->bonodetalleId = $BonoDetalle->bonodetalleId;
                $this->bonoId = $BonoDetalle->bonoId;
                $this->valor = $BonoDetalle->valor;
                $this->tipo = $BonoDetalle->tipo;
                $this->moneda = $BonoDetalle->moneda;

                $this->usucreaId = $BonoDetalle->usucreaId;
                $this->fechaCrea = $BonoDetalle->fechaCrea;
                $this->usumodifId = $BonoDetalle->usumodifId;
                $this->fechaModif = $BonoDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }
        elseif ($bonoId != "" AND $tipo!="")
        {
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

            $BonoDetalle = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($bonoId,$tipo);

            $BonoDetalle = $BonoDetalle[0];

            $this->success = false;

            if ($BonoDetalle != null && $BonoDetalle != "") 
            {
                $this->bonodetalleId = $BonoDetalle->bonodetalleId;
                $this->bonoId = $BonoDetalle->bonoId;
                $this->valor = $BonoDetalle->valor;
                $this->tipo = $BonoDetalle->tipo;
                $this->moneda = $BonoDetalle->moneda;

                $this->usucreaId = $BonoDetalle->usucreaId;
                $this->fechaCrea = $BonoDetalle->fechaCrea;
                $this->usumodifId = $BonoDetalle->usumodifId;
                $this->fechaModif = $BonoDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }
        elseif ($bonoId != "" AND $tipo!="" AND $moneda!="")
        {
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

            $BonoDetalle = $BonoDetalleMySqlDAO->querybyBonoIdAndTipoAndMoneda($bonoId,$tipo,$moneda);

            $BonoDetalle = $BonoDetalle[0];

            $this->success = false;

            if ($BonoDetalle != null && $BonoDetalle != "") 
            {
                $this->bonodetalleId = $BonoDetalle->bonodetalleId;
                $this->bonoId = $BonoDetalle->bonoId;
                $this->valor = $BonoDetalle->valor;
                $this->tipo = $BonoDetalle->tipo;
                $this->moneda = $BonoDetalle->moneda;

                $this->usucreaId = $BonoDetalle->usucreaId;
                $this->fechaCrea = $BonoDetalle->fechaCrea;
                $this->usumodifId = $BonoDetalle->usumodifId;
                $this->fechaModif = $BonoDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }

        

    }

    /**
    * Realizar una consulta en la tabla de bonos 'bono_detalle'
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
    * @throws Exception si los bonos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getBonoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn, $joins = [])
    {

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

        $bonos = $BonoDetalleMySqlDAO->queryBonoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn, $joins);

        if ($bonos != null && $bonos != "") 
        {
            return $bonos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Recupera detalles de bonos personalizados basados en los parámetros proporcionados.
     *
     * @param string $select Las columnas a seleccionar.
     * @param string $sidx El índice para ordenar.
     * @param string $sord El orden de clasificación (ASC/DESC).
     * @param int $start El punto de inicio para la consulta.
     * @param int $limit El número máximo de registros a devolver.
     * @param array $filters Los filtros a aplicar a la consulta.
     * @param bool $searchOn Si la búsqueda está habilitada.
     * @param string $grouping Parámetro de agrupación opcional.
     *
     * @return array Los detalles de bonos personalizados.
     * @throws Exception Si no se encuentran detalles de bonos.
     */
    public function getBonoDetallesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();

        $bonos = $BonoDetalleMySqlDAO->queryBonoDetallesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($bonos != null && $bonos != "")
        {
            return $bonos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
}
