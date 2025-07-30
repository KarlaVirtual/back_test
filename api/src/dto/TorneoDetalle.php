<?php namespace Backend\dto;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Exception;
/** 
* Clase 'TorneoDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TorneoDetalle'
* 
* Ejemplo de uso: 
* $TorneoDetalle = new TorneoDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TorneoDetalle
{

    /**
    * Representación de la columna 'torneodetalleId' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $torneodetalleId;

    /**
    * Representación de la columna 'torneoId' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $torneoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $tipo;

    /**
    * Representación de la columna 'moneda' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $moneda;

    /**
    * Representación de la columna 'valor' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $valor;
    
    /**
    * Representación de la columna 'valor2' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $valor2;

    /**
    * Representación de la columna 'valor3' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $valor3;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $usucreaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $fechaCrea;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $usumodifId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $fechaModif;

    /**
    * Representación de la columna 'descripcion' de la tabla 'TorneoDetalle'
    *
    * @var string
    */
    public $descripcion;

    /**
    * Constructor de clase
    *
    *
    * @param String $torneodetalleId id de torneodetalle
    * @param String $torneoId id del torneo
    * @param String $tipo tipo
    * @param String $moneda moneda 
    *
    * @return no
    * @throws Exception si TorneoDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($torneodetalleId="",$torneoId="",$tipo="",$moneda="")
    {

        if ($torneodetalleId != "") 
        {


            $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();

            $TorneoDetalle = $TorneoDetalleMySqlDAO->load($torneodetalleId);


            $this->success = false;

            if ($TorneoDetalle != null && $TorneoDetalle != "") 
            {
                $this->torneodetalleId = $TorneoDetalle->torneodetalleId;
                $this->torneoId = $TorneoDetalle->torneoId;
                $this->valor = $TorneoDetalle->valor;
                $this->valor2 = $TorneoDetalle->valor2;
                $this->valor3 = $TorneoDetalle->valor3;
                $this->tipo = $TorneoDetalle->tipo;
                $this->moneda = $TorneoDetalle->moneda;
                $this->descripcion = $TorneoDetalle->descripcion;

                $this->usucreaId = $TorneoDetalle->usucreaId;
                $this->fechaCrea = $TorneoDetalle->fechaCrea;
                $this->usumodifId = $TorneoDetalle->usumodifId;
                $this->fechaModif = $TorneoDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }
        }
        elseif ($torneoId != "" AND $tipo!="")
        {
        
            $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();

            $TorneoDetalle = $TorneoDetalleMySqlDAO->querybyTorneoIdAndTipo($torneoId,$tipo);

            $TorneoDetalle = $TorneoDetalle[0];

            $this->success = false;

            if ($TorneoDetalle != null && $TorneoDetalle != "") 
            {
                $this->torneodetalleId = $TorneoDetalle->torneodetalleId;
                $this->torneoId = $TorneoDetalle->torneoId;
                $this->valor = $TorneoDetalle->valor;
                $this->valor2 = $TorneoDetalle->valor2;
                $this->valor3 = $TorneoDetalle->valor3;

                $this->tipo = $TorneoDetalle->tipo;
                $this->moneda = $TorneoDetalle->moneda;
                $this->descripcion = $TorneoDetalle->descripcion;

                $this->usucreaId = $TorneoDetalle->usucreaId;
                $this->fechaCrea = $TorneoDetalle->fechaCrea;
                $this->usumodifId = $TorneoDetalle->usumodifId;
                $this->fechaModif = $TorneoDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }
        elseif ($torneoId != "" AND $tipo!="" AND $moneda!="")
        {
        
            $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();

            $TorneoDetalle = $TorneoDetalleMySqlDAO->querybyTorneoIdAndTipoAndMoneda($torneoId,$tipo,$moneda);

            $TorneoDetalle = $TorneoDetalle[0];

            $this->success = false;

            if ($TorneoDetalle != null && $TorneoDetalle != "") 
            {
            
                $this->torneodetalleId = $TorneoDetalle->torneodetalleId;
                $this->torneoId = $TorneoDetalle->torneoId;
                $this->valor = $TorneoDetalle->valor;
                $this->valor2 = $TorneoDetalle->valor2;
                $this->valor3 = $TorneoDetalle->valor3;

                $this->tipo = $TorneoDetalle->tipo;
                $this->moneda = $TorneoDetalle->moneda;

                $this->descripcion = $TorneoDetalle->descripcion;
                $this->usucreaId = $TorneoDetalle->usucreaId;
                $this->fechaCrea = $TorneoDetalle->fechaCrea;
                $this->usumodifId = $TorneoDetalle->usumodifId;
                $this->fechaModif = $TorneoDetalle->fechaModif;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "21");
            }

        }

    }

    /**
    * Realizar una consulta en la tabla de detalles de torneos 'TorneoDetalle'
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
    * @throws Exception si los torneos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTorneoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();

        $torneos = $TorneoDetalleMySqlDAO->queryTorneoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($torneos != null && $torneos != "") 
        {
            return $torneos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


}
